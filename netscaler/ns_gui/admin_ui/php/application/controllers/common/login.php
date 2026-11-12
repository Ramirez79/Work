<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once("abstract_controller.php");


class login extends abstract_controller
{
    function __construct()
    {
        parent::__construct();
        detect_xen_center();
    }

    protected function getView()
    {
        return 'common/login_view';
    }

    protected function getViewData()
    {
        $data['loginerror'] = false;
        $data['https_port'] = "443";
        $is_sgw = $this->check_for_simplified_gateway();
        if($is_sgw == "true"){
        	$data['https_port'] = "8443";
        }
        return $data;
    }

    public function do_login()
    {
        require_once("utils.php");
        $this->load->helper('cookie');

        $response = $this->input->post('response');
        $returnVal = 0;
        $startupapp = "";
        $timeout = "";
        $unit = "";
        $timezone_offset = "";
        $jvm_memory = "";
		$bw = BW_NOT_SET;

        if(strcmp($response, ""))
        {
            //User has submitted response for the challenge
            if(!$this->start_session())
                return;
            $username = $_SESSION['username'];
            $startupapp = $_SESSION["startupapp"];
            $timezone_offset = $_SESSION['timezone_offset'];
            $parameters = array("response" => $response);
            $returnVal = $this->execute_login_command("loginchallengeresponse", $parameters);
            if($returnVal != 0 && $returnVal != 1034 && $returnVal != 1045)
               $this->show_error_page("INCORRECT_RESPONSE");
        }
        else
        {
            //User has submitted inital login request using username & password
            $username = $this->input->post("username");
            $password = $this->input->post("password");
            $standalone = $this->input->post("standalone");
            
            $startupapp = input_validator::validate_and_get_cookie("startupapp");
            $timezone_offset = intval(input_validator::validate_and_get("timezone_offset", $this->input->post("timezone_offset")));
            $eula = $this->input->post("eula");            

            $parameters = array("username" => $username,
                                "password" => $password,
                                );

            if(isset($eula) && $eula == true)
            	   $parameters["eula"] = true;
            
            

            if(strpos($username, "##") === 0) //If token is used to login, get username from actual_username field (used in SSO from SDX)
            {
                $actual_username = $this->input->post("actual_username");
                $url = "/menu/ss?" . "sid=" . urlencode($username) . $password;
                if($actual_username !== false)
                    $username = $actual_username;
                else
                    $username = "";
                $url = $url . "&username=" . $username;
                $url = $url . "&startin=" . $startupapp ;
                if(isset($standalone))
                    $url = $url . "&standalone=" . $standalone;
                
                $this->redirect($url);
            }

            $returnVal = $this->execute_login_command( "login", $parameters );
        }
        
	$is_sgw = $this->check_for_simplified_gateway();
	$https_port = "443";
	if($is_sgw == "true"){
	    $https_port = "8443";
	}
	
        if($returnVal == 0)
        {
            set_cookie("startupapp","$startupapp", 60*60*24*30*12);
            $this->setupUserSession($username, $timeout, $unit, $timezone_offset, $jvm_memory, $password);
            $this->loginSuccess($startupapp);   
        }
        else if($returnVal == 1034)
        {
            //Store the username
            $_SESSION['username'] = $username;
            $_SESSION["startupapp"] = $startupapp;
            $_SESSION['timezone_offset'] = $timezone_offset;
            //Need to challenge the user and get the response
            $result = $this->get_model()->get_result();
            $data['message'] = "";
            if(isset($result['message']))
            {
                $data['message'] = $result['message'];
                $this->load->view("common/login_challenge_view", $data);
            }
        }
        else if($returnVal == 1045)
        {
            $this->load->view('common/login_view', array('eula_failed' => true, 'username' => $username, 'https_port' => $https_port));
        }
        else if($returnVal == 1074)
        {
            $result = $this->get_model()->get_result();
            preg_match_all('/\d+/', $result["message"], $matches);
            $this->load->view('common/login_view', array('errorcode' => "1074", 'loginerror' => "Timeout cannot be greater than " .$matches[0][0] ."sec", 'https_port' => $https_port));
        }
        else
        {
            $result = $this->get_model()->get_result();
            $this->loginFail($result["message"]);
		}
    }

    //Login success should always return a redirect (status code: 302) - security requirement
    private function loginSuccess($startupapp)
    {
        $url = $this->input->post('url');
        $valid_url = false;
        //If bookmarked URL is available, validate and use it.
        //Else goto the app selected
        if($url != "")
        {
            $RTR =& load_class("Router");
            $valid_url = $RTR->validate_uri($url);
        }
        if(!$valid_url)
            $url = $this->get_url_for_app($startupapp);
        $this->check_and_redirect_banner($url);
    }

    private function loginFail($error_message)
    {
        $data['loginerror'] = $error_message;
        // For testing XSS error uncomment below line & remove htmlspecialcharacters in login_view.
        // $data['loginerror'] = "abc'd\");alert(\"hello";
        $this->load->view($this->getView(), $data);
    }

    public function banner()
    {
        if(!$this->start_session(true))
            return;
        $data['title'] = get_branding()->get_title() . ' - Logon notice';
        $banner_contents = false;
        if(file_exists(LOGIN_BANNER_FILE) && (filesize(LOGIN_BANNER_FILE) > 0))
            $banner_contents = file_get_contents(LOGIN_BANNER_FILE);
        if($banner_contents == false) //If file read returns failure, goto original URL
        {
            $url = "";
            if(isset($_SESSION["redirect_url"]))
                $url = $_SESSION["redirect_url"];
            else
                $url = $this->get_url_for_app(input_validator::get_default_value("startupapp"));
            parent::redirect($url);
        }
        else
            $data['banner_contents'] = preg_replace("/\\\n/", "<br>", htmlspecialchars($banner_contents));
        $data['url'] = $_SESSION['redirect_url'];
        //To avoid printing top banner on login banner screen
        $data['standalone'] = "YES";
        $this->load->view('common/login_banner', $data);
    }

    private function getTimeout($timeout, $unit)
    {
        if($unit == "Minutes")
            return $timeout * 60;
        if($unit == "Hours")
            return $timeout * 60 * 60;
        return $timeout * 60 * 60 * 24; //$unit is "Days"
    }

    //Takes (client - GMT) seconds and calculates (local - GMT) seconds, where this script runs
    //Using these, it computes the offset to be ADDED to local time to convert into client time.
    private function getTimeZoneOffset($client_timezone_offset)
    {
        //Assume XML API is always on board. If we connect to Netscaler 'N1' using API and this script
        //runs in Netscaler 'N2', then we need to find out the timezone offset of 'N1'.
        $local_timezone_offset = date('Z');
        return $client_timezone_offset - $local_timezone_offset;
    }
    
    public function setupUserSession($username, $timeout, $unit, $timezone_offset, $jvm_memory, $password="")
    {
        // Obtain product skew, AGEE or NS
        $nslicense = NS;
        $nsvpx = "";
        $licenseList = "";
        $_SESSION["sysid"] = get_sysid();
        $_SESSION["oemid"] = get_oem_id();
        $_SESSION["superuser"] = $this->isSuperUser($username);
		
        if($this->execute_command('getnslicense', '') == 0)
        {
            $result = $this->get_model()->get_result();
            $licenseList = $result['List'][0];

            if($this->is_agee_brand_from_license($licenseList))
            {
                $nslicense = AGEE;
            }
            else if($this->is_ocb_brand_from_license($licenseList))
            {
                $nslicense = OCB;
            }

            if(isset($licenseList["nsxn"]) && $licenseList["nsxn"])
                $nsvpx = "VPX";
        }

		$retn = $this->execute_command('getsystembwserver','');
		if( $retn == 0)
		{
			$bw = BW_SET;	
		}
		else {
			$bw = BW_NOT_SET;
		}
		$_SESSION['nsbw'] = $bw;

        if(is_bytemobile())
            $nslicense = BYTEMOBILE;
	
        // Obtain NS Version
        $nsversion = "";
        $ns_mode = NS_KMPE_MODE;
        $nsversion_error = false;
        if($this->execute_command('getnsversion', '') == 0)
        {
            $result = $this->get_model()->get_result();
            $versionList = $result['List'][0];
            $nsversion = $versionList['version'];
            if(isset($versionList['mode']) && $versionList['mode'] > 0)
                $ns_mode = NS_VMPE_MODE;
            $nsversion = str_replace( '"', '', $nsversion);
            $nsversion = str_replace( 'NetScaler', '', $nsversion);
        }
        else
        {
            $error = $this->get_model()->get_error_message() . " - getnsversion.";
            $nsversion_error = true;
            if($this->get_model()->get_error_code() == NSERR_NOTAUTHORIZED)
                $nsversion = "<div title='$error' class='inline_display'><img src='/admin_ui/common/images/stop.png' alt='Error'> Not authorized to retrieve version information </div>";
            else
                $nsversion = "<div title='$error' class='inline_display'><img src='/admin_ui/common/images/stop.png' alt='Error'> Error retrieving version information </div>";
        }

        //Obtain host name
        $hostDesc = $this->get_http_host_ip();
        if( $this->execute_command('getnshostname', '') == 0)
        {
            $result = $this->get_model()->get_result();
            $hostDescList = $result['List'][0];
            if(isset($hostDescList['hostname']))
            {
                $hostName = preg_replace("/\"/", "", $hostDescList['hostname']);
                $hostName= htmlentities($hostName);
                $hostDesc = $hostDesc . ' (' . $hostName . ')';
                $hostDesc= htmlentities($hostDesc);
            }
        }

        //Obtain hardware description or model info (if it is not VPX)
        $ns_model = "";
        if($this->execute_command("getnshardware", "") == 0)
        {
            $result = $this->get_model()->get_result();
            $ns_hardware = $result["List"][0];
        }
        
        $_SESSION["ns_is_sgw"] = $this->check_for_simplified_gateway();
        //is_cisco_platform cookie is used by docs team to hide citrix liks in context help
        $is_cisco_platform = is_cisco_paltform();
        set_cookie("is_cisco_platform", get_oem_id(), 60*60*24*30*12, "", '/', "", true);
        
        if(!empty($licenseList) && isset($licenseList["modelid"]) && $licenseList["modelid"] > 0)
            $ns_model = $licenseList["modelid"];
        else if($nsvpx == "" && isset($ns_hardware["hwdescription"]))
        {
            $ns_model = $ns_hardware["hwdescription"];
            $index = strpos($ns_model, " ");
            if($index !== false)
                $ns_model = substr($ns_model, 0, $index);
        }

        // If this is a new session, then the variable
          // $username will not be registered
        if(is_agee())
        {
            $_SESSION['nsbrandDesc'] = "NetScaler Gateway";
        }
        else if(is_sgw())
        {
        	$_SESSION['nsbrandDesc'] = "NetScaler HDX Proxy";
        }
        else if(is_ocb())
        {
            if($this->execute_command("getnshardware", "") == 0)
            {
                $result = $this->get_model()->get_result();
                $ns_hardware = $result["List"][0];
                if(is_sdx_present($ns_hardware["sysid"]))
                {
                      $_SESSION['nsbrandDesc'] = "NetScaler";
                }
                else
                {
                    $_SESSION['nsbrandDesc'] = "Citrix CloudBridge Connector";
                }
            }
        }
        else if($is_cisco_platform)
        {
            $_SESSION['nsbrandDesc'] = "Citrix NetScaler 1000V";
        }
        else
        {
            $_SESSION['nsbrandDesc'] = "NetScaler";
        }
        
        $_SESSION['username'] = $username;
        $_SESSION['timezone_offset'] = $this->getTimeZoneOffset($timezone_offset);
        $_SESSION['nsversion'] = $nsversion;
        $_SESSION['nsversion_error'] = $nsversion_error;
        $_SESSION['ns_mode'] = $ns_mode;
        $_SESSION['nshostDesc'] = $hostDesc;
        $_SESSION['nsbrand'] = $nslicense;
        $_SESSION['nsvpx'] = $nsvpx;
        $_SESSION["ns_model"] = $ns_model;

        if($nsvpx != '' && !$is_cisco_platform)
            $_SESSION['nsbrandDesc'] = $_SESSION['nsbrandDesc'] . " " . $nsvpx;

        if(is_bytemobile())
            $_SESSION['nsbrandDesc'] = get_branding()->get_brand_name();
        
        $this->check_for_aws_instance();
        
        //rand is used to prevent CSRF attack as value [Ex: logout link, all POSTs etc]
        //rand_key is used to prevent CSRF attack as key [Ex: all POSTs]
        //Ex: $_SESSION["rand_key"]=$_SESSION["rand"]&param1=value1 should be sent in POST
        $_SESSION["rand"] = utils::generate_random_number();
        $_SESSION["rand_key"] = preg_replace("/\./", "", utils::generate_random_number()); //dot in POST key names get converted to underscore.
        if(!empty($licenseList))
            $_SESSION['licenseMap'] = $licenseList;
        require_once(APPPATH."views/common/locale_utils.php");
        locale_utils::set_up_locale();
    }

    private function is_agee_brand_from_license($licenselist)
    {
        $agee_brand_keys_list = array("agee" =>true, "wl" =>true, "cmp" =>true, "ssl" =>true, "routing" =>true, "ospf" =>true, "rip" =>true, "bgp" =>true, "isis" =>true, "sslvpn" =>true, "rewrite" =>true, "responder" =>true, "appflow" =>true);
        return $this->is_brand_from_license($licenselist, $agee_brand_keys_list);
    }

    private function is_ocb_brand_from_license($licenselist)
    {
        $ocb_brand_keys_list = array("cloudbridgeappliance" =>true, "cloudbridge" =>true, "lb" =>true,  "cs" =>true, "wl" =>true, "ssl" =>true, "gslb" =>true, "routing" =>true, "cf" =>true, "ospf" =>true, "rip" =>true, "bgp" =>true, "rewrite" =>true, "ipv6pt" =>true, "responder" =>true, "appflow" =>true, "wionns" =>true, "isis" =>true);
        return $this->is_brand_from_license($licenselist, $ocb_brand_keys_list);
    }

    private function is_brand_from_license($licenselist, $brandkeyslist)
    {
        $is_brand = true;
        $ignore_license_keys = array("nsxn" => true, "modelid" => true, "f_ica_users" => true, "f_sslvpn_users" => true, "ch" =>true);
        foreach($licenselist as $license_key => $license_value)
        {
            if(isset($ignore_license_keys[$license_key]))
                continue;

            if(isset($brandkeyslist[$license_key]))
            {
                if(!(isset($licenselist[$license_key]) && $licenselist[$license_key]))
                {
                    $is_brand = false;
                    break;
                }
            }
            else if(isset($licenselist[$license_key]) && $licenselist[$license_key] === true)
            {
                $is_brand = false;
                break;
            }
        }

        return $is_brand;
    }
    
    private function check_for_simplified_gateway()
    {
        $ns_is_sgw = "false";
        $ns_sgw_cmd  = trim((string)exec("sysctl -n netscaler.is_simple_gateway"));
        /*Un comment to test it
        $aws_ns_brand  = trim('NetScaler VPX AWS MP Enterprise Edition - 200 Mbps (PIN# 4051153)');
        */
        if (isset($ns_sgw_cmd) &&  !(strpos($ns_sgw_cmd, "Unknown") > 0) && !(strpos($ns_sgw_cmd, "unknown") > 0) && strlen($ns_sgw_cmd) > 0)
        {
	    if($ns_sgw_cmd == "1")
	    {
		$ns_is_sgw = "true";
	    }
        }
        return $ns_is_sgw;
    }
    
    
    private function check_for_aws_instance()
    {
        $_SESSION["ns_aws_pin"] = "";
        $aws_ns_brand  = trim((string)exec("sysctl -n netscaler.aws_descr"));
        /*Un comment to test it
        $aws_ns_brand  = trim('NetScaler VPX AWS MP Enterprise Edition - 200 Mbps (PIN# 4051153)');
        */
        if (isset($aws_ns_brand) &&  !(strpos($aws_ns_brand, "Unknown") > 0) && strlen($aws_ns_brand) > 0)
        {
            $pin_index = strpos($aws_ns_brand, "PIN");
            if (isset($pin_index) && $pin_index > 0)
            {
                $aws_ns_brand_with_pin = $aws_ns_brand;
                $aws_ns_brand = substr($aws_ns_brand, 0, $pin_index-2);
                $aws_pin = substr($aws_ns_brand_with_pin, $pin_index+4, strripos($aws_ns_brand_with_pin, ")") - $pin_index - 4);
            }
            else
            {
                $aws_pin = "";
                $aws_ns_brand = $aws_ns_brand;            
            }
            $_SESSION["nsbrandDesc"] = $aws_ns_brand;
            $_SESSION["ns_aws_pin"] = $aws_pin;
            $_SESSION["ns_model"] = "";
        }
    }
}
?>
