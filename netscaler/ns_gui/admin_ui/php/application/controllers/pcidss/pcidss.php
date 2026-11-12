<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once(APPPATH. "controllers/common/abstract_controller.php");
require_once "creport.php";
require_once("_ns_wsi_definitions.php");

global $g_wsi_definitions;
$g_wsi_definitions = $wsi_definitions;

function count_bindings($element, $key, &$t)
{
	$arg = $t->get_args();
	if(is_array($element))
	{
        $arg['global_pargs']['bindings'] += sizeof($element);
		$t->set_args($arg);
	}
}


class pcidss extends abstract_controller
{
    private $nsEntitydef;
    private $args;
    private $profile_no;
    private $signatures = ",";
    private $default_sig_map;
    private $signatures_processed = 0;
    public static $builtinProfiles = array("APPFW_BYPASS","APPFW_RESET","APPFW_DROP","APPFW_BLOCK");
    private static $DEFAULT_SIG_LIMIT = 1000000;
    private static $WT_CUSTOM_SIG = 40;
    private static $WT_DEFAULT_SIG = 1;
    private static $MAX_SIG_LIMIT_PER_PDF = 2000;
    private static $NUM_SIG_OBJS_PER_PDF = 30;
    private static $EXP_COUNT_CUSTOM_SIG =  "grep -c 'enabled=\"ON\".*id *= *\"[0-9]\{7,\}\" ' ";
    private static $EXP_COUNT_DEFAULT_SIG = "grep -c 'enabled=\"ON\".*id *= *\"[0-9]\{1,6\}\" ' ";

    function __construct()
    {
        parent::__construct();
        require_once("entity_table_definitions.php");
        $this->nsEntitydef = new entity_table_definitions();
    }
	public function get_args()
	{
		return $this->args;
	}
	public function set_args($param)
	{
		$this->args = $param;
	}

    private function init($argsList)
    {
        session_cache_limiter('must-revalidate');
        if(isset($argsList['sid']))
        {
            require_once(APPPATH. "controllers/common/utils.php");
            utils::setup_webstart_session($argsList['sid']);
            $this->sid = $argsList['sid'];
            $_SESSION["username"] = $this->username =  $argsList['username'];
        }
        else
        if(!$this->start_session())
            return false;

        $pdf = new Creport('a4','portrait');
        $pdf->selectFont(APPPATH. "controllers/pcidss/fonts/Times-Roman.afm");
        $pdf->ezSetMargins(30,50,30,30);
        $this->args = array();
        $username  = isset($_SESSION["username"])? $_SESSION["username"] : "";
        $this->args['global_pargs'] = Array(
                    'tabChars'      => "                ",
                    'pdf'           => $pdf,
                    'nsEntitydef'   => $this->nsEntitydef,
                    'which_report'  => $argsList['type'],
                    'fontSize'      => 9,
                );
        if(isset($_POST['local_time']) and $_POST['local_gmtoffset'])
        {
            $local_time_in_secs = $_POST['local_time'] / 1000;
            $local_gmt_offset_in_secs = $_POST['local_gmtoffset'] * 60 * -1;
            $client_time = $local_time_in_secs + $local_gmt_offset_in_secs + date("Z") * -1;
            $this->args['global_pargs']['client_time'] = date("M d Y H:i:s ", $client_time);
            $this->args['global_pargs']['client_time'] .= $this->get_timezone_gmt($_POST['local_gmtoffset']);
        }
        $this->args['global_pargs']['nslocaltime'] = date("M d Y H:i:s (\G\M\T P)", time());
        $this->args['global_pargs']['username'] = $username;

        $pdf->ezStartPageNumbers(110,20, $this->args['global_pargs']['fontSize'] - 1,'right',
                                            '<b><i>Citrix NetScaler PCI-DSS v3.2 Compliance Report - '.
                                                (isset($this->args['global_pargs']['client_time'])? $this->args['global_pargs']['client_time'] : "").
                                                " - Page {PAGENUM} of {TOTALPAGENUM}</i></b>"
                                            ,1);

        $this->load->view("pcidss/pcidss_header", $this->args);
        return true;
    }

    private function get_timezone_gmt($offset) // offset returned by JavaScript Date.getTimezoneOffset
    {
        if(!isset($offset))
            return "";
        $sign = ($offset > 0)? "-": (($offset < 0)? "+":"");
        if($offset < 0)
            $offset = -1 * $offset;
        $h = intval($offset/60);
        $m = $offset % 60;
        if($offset != 0)
            return sprintf("(GMT $sign%02d:%02d)",$h,$m);
        else
            return "GMT";
    }

    private function fetchEntity($entity, $parameters='')
    {
        $entity_def = $this->nsEntitydef->getDefinition($entity);
        $command = $entity_def['command'];

        if( $this->execute_command($command, $parameters) == 0)
        {
            $result = $this->get_model()->get_result(); //store the feature list result
            if(isset($this->sid))
            {
                require_once(APPPATH. "controllers/common/utils.php");
                utils::setup_webstart_user_session($this->sid, $this->username, $_POST["local_gmtoffset"]);
                unset($this->sid);
                unset($this->username);
            }
            if(isset($result['List']) && is_array($result['List']))
            {
                // to filter out extra items filled by soap layer, is_array - call back function
                if(isset($result['List']['!SOAP-ENC:offset']))
                    unset($result['List']['!SOAP-ENC:offset']);
#                $entityList = array_filter($result['List'], "is_array");
                if(!isset($this->args['global_pargs']['bindings']))
                    $this->args['global_pargs']['bindings'] = 0;
                if(preg_match("/.+binding$/",$command)) // bindings command, count the number of bindings.
                {
                    if(isset( $result['List'][0]))
                        array_walk($result['List'][0], "count_bindings", $this);
                }
                else
                {
					# Entities query
                    $this->args['global_pargs']['bindings'] += sizeof($result['List']);
                }
                return $result['List'];
            }
        }
        else
        {
            if($this->get_model()->get_error_code() == NSERR_NOTAUTHORIZED)
                $data['custom_error'] = " Not authorized to retrieve ". $entity_def['title'] ." information";
            else
            {
                $data['custom_error'] = "Error retrieving ". $entity_def['title'] ." information.";
                $data['custom_error'] .= "<br>Error: " . $this->get_model()->get_error_message();
            }
            $this->load->view('common/errormessage', $data);
            throw new Exception("Value must be 1 or below");
        }
    }

    private function fwconfig()
    {

        $ip = getenv("HTTP_HOST");
        $this->args['appfwpolicy'] = $this->fetchEntity('appfwpolicy');
        $settings = $this->fetchEntity('appfwsettings');
       
        $settings_arr = array();
        foreach ($settings[0] as $key => $value) {
            array_push( $settings_arr, array('name' => $key, 'value' =>$value ));
        }
          $this->args['appfwsettings'] = $settings_arr;
        
        if(isset($this->args['appfwpolicy']))
        foreach( $this->args['appfwpolicy'] as &$policy)
        {
            if(isset($policy['activepolicy']) && $policy['activepolicy'] == 1 )
            {
                $policy['activepolicy'] = "Yes";
            }
			else
				$policy['activepolicy'] = "No";
        }

        if(!isset($this->args['appfwprofile'])) // profiles could already be set in main
            $this->args['appfwprofile'] = $this->fetchEntity('appfwprofile');
        if(isset($this->args['appfwprofile']))
        foreach ($this->args['appfwprofile'] as &$profile)
        {
            $NO_POLICY_VALUE = "<b><c:colorit:\$r=1;>INACTIVE</c:colorit></b>";
            $profilePolicies = $NO_POLICY_VALUE;
            if(isset($this->args['appfwpolicy']))
            foreach( $this->args['appfwpolicy'] as $policy1)
            {
                if($profile['name'] == $policy1['profilename'])
                {
                    if($profilePolicies == $NO_POLICY_VALUE) // first entry initilize the list
                        $profilePolicies = "";
                    else
                        $profilePolicies = $profilePolicies . ", ";
                    $profilePolicies = $profilePolicies  . $policy1['name'];
                }
            }
            $profile['policies'] = $profilePolicies;
            $profile['type_disp'] = implode(" ", $profile['type']);

            // setting profile type for builtin profiles
            if(in_array($profile['name'], pcidss::$builtinProfiles))
            {
                $profile['type_disp'] = "BUILT-IN";
            }

            if($this->args['global_pargs']['which_report'] == "fwconfig")
            {
                $profile['name'] = "<c:alink:http://$ip/pcidss/launch_report?type=fwprofile&profile_name=". $profile['name'] . ">". $profile['name']."</c:alink>";
            }
        }

        $this->load->view("pcidss/pcidss_fwconfig", $this->args);
    }

    private function main()
    {
        $appfwprofiles = $this->fetchEntity('appfwprofile');
        $featureList = $this->fetchEntity('nsfeature');
        if(isset($featureList) and sizeof($featureList) > 0)
            if(isset($featureList[0]['appfw']))
                $this->args['appfw_feature_enabled'] =  $featureList[0]['appfw'];
        $nsdefaultpasschanged = $this->fetchEntity('nsdefaultpasschanged');
        if(isset($nsdefaultpasschanged) and sizeof($nsdefaultpasschanged) > 0)
            $this->args['nsdefaultpasschanged'] =  $nsdefaultpasschanged[0]['changed'];
        $nsipList = $this->fetchEntity('nsip', array( "type" => "NSIP"));     // fetch nsip
        if(isset($nsipList) and sizeof($nsipList) > 0)
        {
            if(isset($nsipList[0]['gui']))
                $this->args['guiAccess'] =  $nsipList[0]['gui'];
            if(isset($nsipList[0]['telnet']))
                $this->args['telnetAccess'] =  $nsipList[0]['telnet'];
            if(isset($nsipList[0]['ssh']))
                $this->args['sshAccess'] =  $nsipList[0]['ssh'];
        }

        $ntpServers = $this->fetchEntity('ntpserver');     // fetch ntp servers
        $this->args['ntpServers'] = 0;
        if(isset($ntpServers) and sizeof($ntpServers) > 0)
            $this->args['ntpServers'] = sizeof($ntpServers);
        $ntpsync = $this->fetchEntity('ntpsync');     // fetch ntp synhronization info
        if(isset($ntpsync) and sizeof($ntpsync) > 0)
            if(isset($ntpsync[0]['state']))
            {
                $this->args['ntpsync'] =  $ntpsync[0]['state'];
            }



        $this->args['appfwProfileExecutiveSummary'] = $this->nsEntitydef->getAppfwProfileExecutiveSummaryData();
        $this->args['appfwconfidfield'] = $this->fetchEntity('appfwconfidfield');
        $ccData = array();
        $this->args['appfwprofile'] = $appfwprofiles; // for testing anchors/internal links & storing for futher use
        if(isset($appfwprofiles))
        foreach ($appfwprofiles as $fwProfile)
        {
            if(!in_array($fwProfile['name'], pcidss::$builtinProfiles))
                $ccData[] = $this->getCreditCardData($fwProfile);
        }
        $this->args['creditcard'] = $ccData;
        $this->load->view("pcidss/pcidss_main", $this->args);
    }

    private function fwProfile($profileName, $profile=null)
    {
        if(!isset($profileName))
            return false;
        if(!isset($profile))
        {
            $profile = $this->fetchEntity('appfwprofile', array( "name" => $profileName));               // fetch profile info through nitro
            if(isset($profile))
            {
                $profile = $profile['0'];
            }
        }
        $this->spliceProfile($profile);
        $profileData = $this->fetchEntity('appfwprofile_binding', array( "name" => $profileName));     // fetch bindings through xmlapi

        if(isset($profileData))
        {
            $this->spliceArgsforProfile($profileData);
            $this->load->view("pcidss/pcidss_profile", $this->args);
            return true;
        }else
        {
            $this->show_error_page("RESOURCE_NOT_FOUND");
        }
        return false;
    }

    private function full_report()
    {
        $this->main();
        $this->all_profiles("0");
    }

    private function all_signatures($set=0, $sig_name="", $sig_start_no = 0)
    {
        $ip = getenv("HTTP_HOST");
        if( $sig_start_no < 0)
            return;
		
		$set = intval($set);
		if( $set < 0 )
		{
			return;
		}
        if(strlen($sig_name) > 0 && $sig_start_no > 0)
        {
            if(!$this->print_signature($sig_name, $sig_start_no,"", "type=all_signatures&set=$set"))
            {
                // Max limit reached on pdf, exit without processing other signatures
                return;
            }
            else
            {
                // current signature got successfully printed and we have to start processing next signature
                $set = $set + 1;
            }
        }
        $sig_objs = $this->fetchEntity('appfwcustomsettings');
        // Due to nitro system command call. The response is like cli command .
        // This format handling is done locally.
        // Ex. Output : "1)\tUrl: default_signatures.xml\tName: \"aaaaaaaaaaa\"\n2)\tUrl: wiz_pol\tName: \"wiz_pol\"
        $sig_objs = $sig_objs['0']['response'];
        $this->args['global_pargs']['pdf']->ezText("\n");
        $this->args['global_pargs']['pdf']->ezText($sig_objs);
        $this->args['global_pargs']['pdf']->ezText("\n");
        if(preg_match_all("/Name\\s*:\\s*\"(.*?)\"/", $sig_objs, $matches))
            $sig_obj_names = $matches[1];
        else
            return;
		$sigs_found = false;
        if(isset($sig_objs))
        {
            $end = $set + pcidss::$NUM_SIG_OBJS_PER_PDF;
            $end = (count($sig_obj_names) <  $end)? count($sig_obj_names) : $end;
            $start = $set;
            for($i = $start; $i < $end; ++$i)
            {
				$sig_obj_name = $sig_obj_names[$i];
                $sig_file_path = $this->export_sig($sig_obj_name);
               // if($sig_file_path == null)
				//	continue;

				$sigs_found = true;
                $sigs = 0;
                if($this->signatures_processed != 0) // if signatures are already processed find out if further signatures can be processed
                {
                    $sigs = exec(pcidss::$EXP_COUNT_CUSTOM_SIG . $sig_file_path) * pcidss::$WT_CUSTOM_SIG;
                    $sigs += exec(pcidss::$EXP_COUNT_DEFAULT_SIG . $sig_file_path) * pcidss::$WT_DEFAULT_SIG;
                }
                if( ($this->signatures_processed + $sigs) < pcidss::$MAX_SIG_LIMIT_PER_PDF)
                {
                    if(!$this->print_signature_file($sig_obj_name, $sig_file_path, false, 0, "type=all_signatures&set=". $i))
                        break;
                }
                else
                {
                    // cannot print current signature, print the next link
                    $next_link = "<c:alink:http://$ip/pcidss/launch_report?type=all_signatures&set=$i>". "Remaining Signatures..."  ."</c:alink>";
                    $this->args['global_pargs']['pdf']->ezText($next_link);
                    break;
                }
            }
        }
		if(!$sigs_found)
		{
			$no_sig_msg = "\n\nNo Signatures found!";
            $this->args['global_pargs']['pdf']->ezText($no_sig_msg);
		}
        //$sig_link = "<c:alink:/pcidss/launch_report?type=signature&sig_name=_default_signature_" . ">". "Default Signatures report"  ."</c:alink>";
        //$this->args['global_pargs']['pdf']->ezText($sig_link);

    }

    // export signature and return sig_file_path
    private function export_sig($sig_name)
    {
//        if(true)
//            return "c:\\temp\\pcidss_temp1.xml";
        $sig_file_name =  "pcidss_temp_". rand(0,999999999) . ".xml"; // can add client ip if required
        if($this->execute_command('exportappfwcustomsettings', array("name"=> $sig_name, "target" => "local:".$sig_file_name)) != 0)
            return null;
        $sig_file_path = "/var/tmp/" . $sig_file_name;
        return $sig_file_path;
    }

    private function print_signature($sig_name, $sig_start_no=0, $sig_file_path="", $query_params="type=signature", $detailed_view="false")
    {
        $full=false;
        if(strlen($sig_file_path) <= 0) // file to be exported
        {
            if($sig_name == "_default_signature_")
            {
                $sig_file_path = "/netscaler/default_signatures.xml";
                $full = true;
            }
            else
                $sig_file_path = $this->export_sig($sig_name);
            if($sig_file_path == null)
                return false;
        }
        return $this->print_signature_file($sig_name, $sig_file_path, $full, $sig_start_no, $query_params, $detailed_view);
    }

    private function signature($sig_name, $sig_start_no=0, $detailed_view="false")
    {
        $this->print_signature($sig_name,$sig_start_no,"","type=signature",$detailed_view);
    }

    private function all_profiles($set)
    {
        $MAX_BINDINGS_PER_PDF = 3000;
        $NUM_PROFILE_PER_PDF = 60;
        if($set == "0")
            $this->fwconfig();
        if(!isset($this->args['appfwprofile'])) // profiles might already be fetched in fwconfig or main
            $this->args['appfwprofile'] = $this->fetchEntity('appfwprofile');

        if(isset($this->args['appfwprofile']))
        {
            if($this->args['global_pargs']['bindings'] >= $MAX_BINDINGS_PER_PDF) //might happen during display of config table.
            {
                // if entities queried has already exceeded the limit, then display a few profiles
                $MAX_BINDINGS_PER_PDF = $this->args['global_pargs']['bindings'] + 200; //Setting binding limit to 200.
                $NUM_PROFILE_PER_PDF = 5; //Setting profile number limit to 5.
            }

            $profiles = $this->args['appfwprofile'];
	    if($set !== "0")
	    {
		$set = intval($set);
	    }
			if( $set < 0 )
			{
				$this->fwconfig();
				$set = 0;
			}
            $end = $set + $NUM_PROFILE_PER_PDF;
            $end = (sizeof($profiles) <  $end)? sizeof($profiles) : $end;
            $start = $set;
            for($i = $start; $i < $end && $this->args['global_pargs']['bindings'] < $MAX_BINDINGS_PER_PDF; ++$i)
            {
                $profile = $profiles[$i];
                $pargs = $this->args['global_pargs'];
                $pargs['set'] = $set;
                $this->args = array('global_pargs' => $pargs);
                $this->profile_no = $i + 1;
                $this->fwProfile($profile['name'], $profile);
            }
            if(sizeof($profiles) > $i)
            {
                $links = "<c:alink:/pcidss/launch_report?type=allprofiles&set=".$i.">Remaining Profiles...</c:alink>";
                $this->args['global_pargs']['pdf']->ezText($links, $this->args['global_pargs']['fontSize'],array('justification'=>'centre'));
            }
        }
    }

    private function spliceArgsforProfile($profileData)
    {
         foreach($profileData as $profile)
        {
            $this->args['starturl'] = null;
            $this->args['denyurl'] = null;
            $this->args['cookieconsistency'] = null;
            $this->args['contenttype'] = null;
            $this->args['fieldconsistency'] = null;
            $this->args['fieldformat'] = null;
            $this->args['safeobject'] = null;
            $this->args['sqlinjection'] = null;
            $this->args['crosssitescripting'] = null;
            $this->args['xmlsqlinjection'] = null;
            $this->args['xmlcrosssitescripting'] = null;
            $this->args['xmlwsi'] = null;
            $this->args['xdoscheck'] = null;
            $this->args['xmlvalidation'] = null;
            $this->args['xmlattachment'] = null;
            $this->args['csrf'] = null;

            if(isset($profile['appfwprofile_starturl_binding']))
                $this->args['starturl'] = $profile['appfwprofile_starturl_binding'];
            if(isset($profile['appfwprofile_denyurl_binding']))
                $this->args['denyurl'] = $profile['appfwprofile_denyurl_binding'];
            if(isset($profile['appfwprofile_cookieconsistency_binding']))
                $this->args['cookieconsistency'] = $profile['appfwprofile_cookieconsistency_binding'];
            if(isset($profile['appfwprofile_contenttype_binding']))
                $this->args['contenttype'] = $profile['appfwprofile_contenttype_binding'];
            if(isset($profile['appfwprofile_fieldconsistency_binding']))
                $this->args['fieldconsistency'] = $profile['appfwprofile_fieldconsistency_binding'];
            if(isset($profile['appfwprofile_fieldformat_binding']))
                $this->args['fieldformat'] = $profile['appfwprofile_fieldformat_binding'];
            if(isset($profile['appfwprofile_safeobject_binding']))
                $this->args['safeobject'] = $profile['appfwprofile_safeobject_binding'];
            if(isset($profile['appfwprofile_sqlinjection_binding']))
                $this->args['sqlinjection'] = $profile['appfwprofile_sqlinjection_binding'];
            if(isset($profile['appfwprofile_crosssitescripting_binding']))
                $this->args['crosssitescripting'] = $profile['appfwprofile_crosssitescripting_binding'];
            if(isset($profile['appfwprofile_xmlsqlinjection_binding']))
                $this->args['xmlsqlinjection'] = $profile['appfwprofile_xmlsqlinjection_binding'];
            if(isset($profile['appfwprofile_xmlxss_binding']))
                $this->args['xmlcrosssitescripting'] = $profile['appfwprofile_xmlxss_binding'];
            if(isset($profile['appfwprofile_xmldosurl_binding']))
                $this->init_entity_data('xdoscheck', $profile['appfwprofile_xmldosurl_binding'][0]);
            if(isset($profile['appfwprofile_xmlwsiurl_binding']))
                $this->args['xmlwsi'] =  $this->getWsiData($profile['appfwprofile_xmlwsiurl_binding'][0]); // special call
            if(isset($profile['appfwprofile_xmlvalidationurl_binding']))
                $this->args['xmlvalidation'] =  $this->getXMLValidateData($profile['appfwprofile_xmlvalidationurl_binding'][0]); // special call
            if(isset($profile['appfwprofile_xmlattachmenturl_binding']))
                $this->init_entity_data('xmlattachment', $profile['appfwprofile_xmlattachmenturl_binding'][0]);
            if(isset($profile['appfwprofile_csrftag_binding']))
                $this->args['csrf'] = $profile['appfwprofile_csrftag_binding'];
        }
    }

    private function spliceProfile($profile)
    {
        if(!isset($profile['type_disp']))
            $profile['type_disp'] = implode(" ", $profile['type']);
                if(isset($this->profile_no))
                    $profile['profile_no'] = $this->profile_no;
                $this->args['profile'] = $profile;
                if(in_array($profile['name'], pcidss::$builtinProfiles))
                {
                    $this->args['profile']['type_disp'] = "BUILT-IN";
                    return;
                }

                $this->args['creditcard'] = array($this->getCreditCardData($profile));
                $this->init_entity('starturl', $profile);
                $this->init_entity('denyurl', $profile);
                $this->init_entity('cookieconsistency', $profile);
                $this->init_entity('contenttype', $profile);
                $this->init_entity('safeobject', $profile);
                $this->init_entity('bufferoverflow', $profile);
                $this->init_entity_data('bufferoverflow', $profile);
                if(strpos($profile['type_disp'],'HTML') !== false)
                {
                    $this->init_entity('fieldconsistency', $profile);
                    $this->init_entity('fieldformat', $profile);
                    $this->init_entity('csrf', $profile);
                    $this->init_entity('sqlinjection', $profile);
                    $this->init_entity('crosssitescripting', $profile);
                }
                if(strpos($profile['type_disp'],'XML') !== false)
                {
                    $this->init_entity('xmlformat', $profile);
                    $this->init_entity('xdoscheck', $profile);
                    $this->init_entity('xmlcrosssitescripting', $profile);
                    $this->init_entity('xmlsqlinjection', $profile);
                    $this->init_entity('xmlattachment', $profile);
                    $this->init_entity('xmlwsi', $profile);
                    $this->init_entity('xmlvalidation', $profile);
                    $this->init_entity('xmlsoapfault', $profile);
                }
            }

    private function parse_signature($signature)
    {
        preg_match("/(?:.*?)<SignatureRule(.+?)\\>(.*?)<\\/SignatureRule>/ms", $signature, $matches);
        if(count($matches) > 0)
        {
            $sig_attr = $matches[1];
            $sig_rule = $matches[2];
            unset($id);
            unset($actions);
            unset($enabled);
            preg_match("/.*\\bid=\"(\\d+)\"/", $sig_attr, $id);
            preg_match("/.*actions=\"(.*?)\"/", $sig_attr, $actions);
            if(count($actions)<=0)
                $actions[1]=""; // all actions are off
            preg_match("/.*enabled=\"(.*?)\"/", $sig_attr, $enabled);
            if(count($id)>0 and count($enabled)>0)
            {
                $attrs = array($id[1], $actions[1], $enabled[1]);
            }
            else
            {
                return null;
            }
//            $attrs = array("id"=>$id, "actions"=>$actions, "enabled"=> $enabled);
            return array($attrs, $sig_rule);
        }
        return null;
    }

    private function print_signature_file($sig_name, $sig_file, $full = false, $sig_no = 0, $query_params="signature", $detailed_view="false")
    {
        $ip = getenv("HTTP_HOST");
        if( $sig_no < 0)
            return;


        $sig_rules=array();
        $def_rules=array();
        $rule=array();

        $offset = 0;
        $read_info = file_get_contents($sig_file);
        $sig_cnt = 0;
        $process_sig = $sig_no == 0;
        $i_def = 0;

        while(true)
        {
            // Processing 50 signatures at a time.
            preg_match("/((?:<SignatureRule[^>]*>.*?<\\/SignatureRule>.*?(?=<SignatureRule)){1,50}|<SignatureRule[^>]*>.*?<\\/SignatureRule>)(.)/ms",$read_info, $signatures_set, PREG_OFFSET_CAPTURE, $offset);
            if(count($signatures_set) == 0)
            {
                // no more signatures left; break and print the signatures
                break;
            }
            // Resetting the offset
            $offset = $signatures_set[2][1];
            preg_match_all("/<SignatureRule[^>]*>.*?<\\/SignatureRule>/ms",$signatures_set[1][0], $signatures, PREG_PATTERN_ORDER);
            if($sig_no != 0)
            {
                // have to process part signatures.
                if(!$process_sig)
                {
                    if($sig_no > $sig_cnt + count($signatures[0])) // still not reached the required sig.
                    {
                        $sig_cnt += count($signatures[0]);
                        continue;
                    }
                    // reached the required signature set
                    $process_sig = true;
                    $signatures[0] = array_slice($signatures[0], $sig_no - $sig_cnt - 1);
                    $sig_cnt =  $sig_no;  //  $sig_cnt from now will contain signatures that are already printed
                }
            }

            foreach ($signatures[0] as $signature)
            {
                $is_def_sig = false;
                $sig = $this->parse_signature($signature);
                if($sig != null)
                {
                    list($sig_attrs, $sig_rule) = $sig;
                    # <Location area="HTTP_URL"/> will be modified to <Location area="HTTP_URL"><Location>
                    $sig_rule = preg_replace("/<(\\S+)(\\s+[^><]+?)\\/>/", "<\\1\\2></\\1>",$sig_rule);
                    //  for checking. to be removed later after stanley's fix for quotes.
                    $sig_rule = str_replace("!\"", "!&quot;", $sig_rule);
                    list($id, $actions,$enabled) = $sig_attrs;
                    $sig_cnt++;
                    $id_append="";
                    if(!$full)
                    {
                        if( $detailed_view=="false" )  //Show disabled rules and default rule details in case of detailed_view
						{
							if($enabled == "OFF")
								continue;
							/// processing default signature
							if($id < pcidss::$DEFAULT_SIG_LIMIT)
							{
								if(count($rule) == 4 * 2) // 3 is number of cols - id, enabled, actions; 2 - is number of sig in row
									$rule=array();
								$is_def_sig = true;
								$id_append = $i_def % 4;
								$i_def++;
								$cur_sig_weight = pcidss::$WT_DEFAULT_SIG;
							}
							else
							{
								# custom signature
								$cur_sig_weight = pcidss::$WT_CUSTOM_SIG;
							}
						}
                        else
                        {
                            # custom signature
                            $cur_sig_weight = pcidss::$WT_CUSTOM_SIG;
                        }
                    }
                    else
                    {
                        // for default all signatures are printed in full
                        $cur_sig_weight = pcidss::$WT_CUSTOM_SIG;
                    }

                    if($this->signatures_processed + $cur_sig_weight > pcidss::$MAX_SIG_LIMIT_PER_PDF)
                    {
                        // Reached limit per pdf, have to print the signature.
                        if($detailed_view=="true")
							$next_link = "<c:alink:http://$ip/pcidss/launch_report?$query_params&sig_name=$sig_name&sig_start_no=$sig_cnt&detailed_view=$detailed_view" . ">". "Rest of Signature Report..."  ."</c:alink>";
						else
							$next_link = "<c:alink:http://$ip/pcidss/launch_report?$query_params&sig_name=$sig_name&sig_start_no=$sig_cnt" . ">". "Rest of Signature Report..."  ."</c:alink>";
                        break 2;  // break out of 2 loops
                    }
                    else
                    {
                        $this->signatures_processed += $cur_sig_weight;
                    }

                    $rule['id'. $id_append] = $id;
                    $rule['actions'.$id_append] = $actions;
					if($detailed_view == "true")
					{
						$rule['status'] = ($enabled == "OFF")? "No" : "Yes";
					}

                    if(!$is_def_sig)
                    {
                        # Removing references from signature rule
                        $rule['pattern'] = preg_replace('/<Reference>.+?<\\/Reference>/', '', $sig_rule);
                        $rule['pattern'] = trim($rule['pattern']);

                        preg_match_all("/<Reference>(.+?),(.+?)<\\/Reference>/m", $sig_rule, $refs);
                        if(count($refs) > 0)
                        {
                            $rule['references'] = "";
                            // $refs[1] is an array that has entries like bugtraq, nessus
                            // $refs[2] contains corresponding ids
                            $count = count($refs[1]);
                            for ($i = 0; $i < $count; $i++)
                            {
                                if( preg_match("/bugtraq/", $refs[1][$i]))
                                {
                                    $rule['references'] .= "<c:alink:http://www.securityfocus.com/bid/" . $refs[2][$i].">".$refs[1][$i]." - ".$refs[2][$i]."</c:alink>\n";
                                }
                                else if( preg_match("/cve/", $refs[1][$i]))
                                {
                                    $rule['references'] .= "<c:alink:http://cve.mitre.org/cgi-bin/cvename.cgi?name=CVE-" . $refs[2][$i].">".$refs[1][$i]." - ".$refs[2][$i]."</c:alink>\n";
                                }
                                else if( preg_match("/nessus/", $refs[1][$i]))
                                {
                                    $rule['references'] .= "<c:alink:http://www.nessus.org/plugins/index.php?view=single&id=" . $refs[2][$i].">".$refs[1][$i]." - ".$refs[2][$i]."</c:alink>\n";
                                }
                                else if( preg_match("/url/", $refs[1][$i]))
                                {
                                    preg_match("/[^\\/]+/", $refs[2][$i], $domain);
                                    $rule['references'] .= "<c:alink:http://" . $refs[2][$i] .">".$refs[1][$i]." - ".$domain[0]."</c:alink>\n";
                                }
                                else if( preg_match("/arachnids/", $refs[1][$i]))
                                {
                                    $rule['references'] .= $refs[1][$i] . " - " . $refs[2][$i]. "\n";
                                }
                                else
                                {
                                     $rule['references'] .= "Invalid reference\n";
                                }

                            }
                        }
                    }
                    if($is_def_sig)
                    {
                        if(count($rule) == 4 * 2)
                            array_push($def_rules, $rule);
                    }
                    else
                    {
                        array_push($sig_rules, $rule);
                        // reset rules  but default rules stored are not erased
                        unset($rule['actions']);
                        unset($rule['id']);
                        unset($rule['pattern']);
                        unset($rule['references']);
						unset($rule['status']);

                    }
                }
            }
        }
        // At the end of signature parsing there might be left over default rules, flush them
        if(count($rule) > 0 && count($rule) < 4 * 2)
            array_push($def_rules, $rule);

        $this->args['sig_name'] = ($sig_no == 0)? $sig_name : $sig_name . "...";
        $this->args['def_rules'] = $def_rules;
        $this->args['sig_rules'] = $sig_rules;
        if($detailed_view == "true")
			$this->args['detailed_view'] = $detailed_view;
        if(isset($next_link))
            $this->args['next_link'] = $next_link;
        $this->args['default_sig_link'] = "<c:alink:http://$ip/pcidss/launch_report?type=signature&sig_name=_default_signature_" . ">". "Default Signatures report"  ."</c:alink>";

        $this->load->view("pcidss/pcidss_signatures", $this->args);
        exec('rm -rf "$sig_file"');
        return !isset($next_link); // still there are pending rules in this signature
    }

    public function report()
    {
        try
        {
            $arg_list = func_get_args();
            $data = $this->validate_arguments($arg_list, array('type'), array('profile_name','sid','username', 'set', 'sig_name', 'sig_start_no', 'detailed_view'));
            $genPDF = true;
            if(
//                (isset($data['set']) && !ctype_digit($data['set'])) ||
//                (isset($data['sig_start_no']) && !ctype_digit($data['sig_start_no'])) ||
                (isset($data['sig_name']) && strlen($data['sig_name']) <= 0)
                )
            {
                $this->show_404();
                return;
            }
            switch($data ['type'])
            {
                case 'main':
                    if($genPDF = $this->init($data))
                        $this->main();
                break;
                case 'fwconfig':
                    if($genPDF = $this->init($data))
                        $this->fwconfig();
                break;
                case 'fwprofile':
                    if($genPDF = $this->init($data))
                        $genPDF = $this->fwProfile($data['profile_name']);
                break;
                case 'full_report':
                    if($genPDF = $this->init($data))
                        $this->full_report();
                break;
                case 'allprofiles':
                    if($genPDF = $this->init($data))
                        if(isset($data['set']))
                            $this->all_profiles($data['set']);
                        else
                            $this->all_profiles("0");
                break;
                case 'all_signatures':
                    if($genPDF = $this->init($data))
                        if(isset($data['set']))
                        {
                            if(isset($data['sig_name']) && isset($data['sig_start_no']))
                                $this->all_signatures($data['set'], $data['sig_name'], $data['sig_start_no']);
                            else
                                $this->all_signatures($data['set']);
                        }
                        else
                        {
                            $this->all_signatures();
                        }
                break;
                case 'signature':
                    if($genPDF = $this->init($data))
                        if(isset($data['sig_name']))
                        {
                            if(isset($data['sig_start_no']))
							{
								if(isset($data['detailed_view']))
									$this->signature($data['sig_name'], $data['sig_start_no'], $data['detailed_view']);
								else
									$this->signature($data['sig_name'], $data['sig_start_no']);
							}
                            else
							{
                                if(isset($data['detailed_view']))
									$this->signature($data['sig_name'], 0 , $data['detailed_view']);
								else
									$this->signature($data['sig_name']);
							}
                        }
                        else
                        {
                           $this->show_404();
                           $genPDF = false;
                        }
                break;
                default:
                    $this->show_404();
                    $genPDF = false;
                break;
            }
            if($genPDF)
            {
                $options['Content-Disposition'] = "pci-dss.pdf";
                $this->args['global_pargs']['pdf']->ezStopPageNumbers(1,1);
                $this->args['global_pargs']['pdf']->ezStream($options);
            }
        }
        catch(Exception $e)
        {
            // gracefull exit
        }
    }

    public function launch_report()
    {
        $temp_report_url_entities = explode("&", $_SERVER["REQUEST_URI"]);
        for ($i = 0; $i < count($temp_report_url_entities); ++$i) 
        {
            $temp_report_url_entities[$i] = htmlentities($temp_report_url_entities[$i]);
        }
        $temp_report_url = implode ("&", $temp_report_url_entities);
        $report_url = str_replace("/pcidss/launch_report", "/pcidss/report", $temp_report_url);
        //$report_url = implode ("&", $temp_report_url_entities);
	    $data['standalone'] = "YES";
        $data['title'] = "Loading";
        print $this->load->view("common/header", $data, true);
        print '<form name="personal" action="" method="post">';
        print '<input type=hidden name="local_time" value= "">';
        print '<input type=hidden name="local_gmtoffset" value= "">';
        print '</form>';
        print "\n<script type=\"text/javascript\">\n";
        print "var gmtoffset = new Date().getTimezoneOffset()\n";
        print "var timeval = new Date().getTime()\n";
        print 'var report_url = "' . $report_url . '";';
        print 'document.personal.action = report_url;';
        print 'document.personal.local_time.value = timeval;';
        print 'document.personal.local_gmtoffset.value = gmtoffset;';
        print 'document.personal.submit();';
        print "</script>\n";
        print $this->load->view("common/footer", $data, true);
    }

    private function init_entity_data($entityname, $profile)
    {
        $defn = $this->nsEntitydef->getDefinition($entityname);
        if(isset($defn['data']))
        {
            eval("\$data = array(" . $defn['data'] . ");");
            $this->args[$entityname] = $data;
        }
    }

    private function eval_actions($map, $profile, $resrc_key)
    {
       if(isset($profile[$resrc_key]))
       {
           if(isset($map[$profile[$resrc_key]]))
           {
               return $map[$profile[$resrc_key]];
           }
       }
       return "";
    }

    private function init_entity($entityname, $profile)
    {
        $defn = $this->nsEntitydef->getDefinition($entityname);

        if(isset($defn['action_attr']))
        {
            $actions = implode(", ",$profile[$defn['action_attr']]);
            if(isset($defn['actions']))
                eval("\$actions .= ', ' ." . $defn['actions'] . ";");
        }

        if(isset($actions))
        {
            $NO_ACTION = "<c:colorit:\$r=1;><b>None</b></c:colorit>";
            $actions = str_replace("block","Block,", $actions);
            $actions = str_replace("log","Log,", $actions);
            $actions = str_replace("stats","Statistics,", $actions);
            $actions = str_replace("remove","Remove,", $actions);
            $actions = str_replace("learn","Learn,", $actions);
            $actions = str_replace("none","", $actions);
            $actions = preg_replace("/(,\\s?)+/",", ", $actions); // Redundant commas have to be removed.
            $actions = preg_replace("/^,\\s?/","", $actions); // remove the beginning comma if present
            $actions = preg_replace("/,\\s?\$/","", $actions); // remove the end comma if present
            if($actions == "")
                $actions = $NO_ACTION;

            $this->args[$entityname . "_actions"] = $actions;
        }
    }

    private function getCreditCardData($profile)
    {
        $this->init_entity('creditcard', $profile);
        $UNP = "<b><c:colorit:\$r=1;>Unprotected</c:colorit></b>";
        $out = $UNP;
        if(!isset($profile['creditcard']))
            $profile['creditcard'] = array();
        if(strpos($this->args['creditcard_actions'], "Block") !== false)
            $out = "Blocked";
        else if(strpos($this->args['creditcard_actions'], "X-Out") !== false)
             $out = "X-Out";
        $creditCardData = array( "profile" => $profile['name'],
            "ae"    => ($out != $UNP and in_array("amex", $profile['creditcard']))? $out: $UNP,
            "dc"    => ($out != $UNP and in_array("dinersclub", $profile['creditcard']))? $out: $UNP,
            "d"     => ($out != $UNP and in_array("discover", $profile['creditcard']))? $out: $UNP,
            "jcb"   => ($out != $UNP and in_array("jcb", $profile['creditcard']))? $out: $UNP,
            "mc"    => ($out != $UNP and in_array("mastercard", $profile['creditcard']))? $out: $UNP,
            "visa"  => ($out != $UNP and in_array("visa", $profile['creditcard']))? $out: $UNP,
            "actions" => $this->args['creditcard_actions'],
        );
        return $creditCardData;
    }

    private function getWsiData($profile)
    {
        global $g_wsi_definitions;
        $wsiData = array();
        $wsichecks = array_keys($g_wsi_definitions);
        foreach($wsichecks as $wsicheck)
        {
            if(strpos($profile['xmlwsichecks'], $wsicheck) !== false)
                $wsiData[] = array("ruleid" => $wsicheck, "rule" => $g_wsi_definitions[$wsicheck], "state" => "Yes");
            else
                $wsiData[] = array("ruleid" => $wsicheck, "rule" => $g_wsi_definitions[$wsicheck], "state" => "No");
        }
        return $wsiData;
    }

    private function getXMLValidateData($profile)
    {
        $defn = $this->nsEntitydef->getDefinition('xmlvalidation');
        $out = "";
        $ind = "\n".$this->args['global_pargs']['tabChars'];
        $str_validateresponse = $ind."<b><i>Validate Response : </i></b>";
        if(isset($profile['xmlwsdl']) and strlen($profile['xmlwsdl']) > 0)
        {
            $out = $ind . "<b><i>Type : </i></b>Validate with a WSDL".
                   $ind . "<b><i>WSDL Object : </i></b>". $profile['xmlwsdl'] .
                   $ind . "<b><i>Allow additional headers not defined in the WSDL : </i></b>". $profile['xmladditionalsoapheaders'] .
                   $ind . "<b><i>End Point Check : </i></b>". $defn['map']['xmlendpointcheck'][$profile['xmlendpointcheck']] .
                   $str_validateresponse . $profile['xmlvalidateresponse'];
        }
        else
        if ((isset($profile['xmlrequestschema']) and strlen($profile['xmlrequestschema']) > 0) or (isset($profile['xmlresponseschema']) and strlen($profile['xmlresponseschema']) > 0 ))
        {
            $out = $ind."<b><i>Validation Type : </i></b>Validate with an XML Schema";
            if(isset($profile['xmlrequestschema']) and strlen($profile['xmlrequestschema']) > 0)
                $out .= $ind."<b><i>XML Schema Request Object : </i></b>". $profile['xmlrequestschema'];
            if(isset($profile['xmlresponseschema']) and strlen($profile['xmlresponseschema']) > 0)
                $out .= $ind."<b><i>XML Schema Response Object : </i></b>". $profile['xmlresponseschema'];
            $out .= $str_validateresponse . $profile['xmlvalidateresponse'];
        }
        else if (isset($profile['xmlvalidatesoapenvelope']) and $profile['xmlvalidatesoapenvelope'] == "ON")
        {
            $out = $ind."<b><i>Validation Type : </i></b>Validate SOAP Envelope Only".
                    $str_validateresponse . $profile['xmlvalidateresponse'];
        }
        return $out;
    }
}
?>
