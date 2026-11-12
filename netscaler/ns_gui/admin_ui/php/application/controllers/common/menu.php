<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once("abstract_controller.php");

class menu extends abstract_controller
{
	function __construct()
	{
		parent::__construct();
        $this->send_x_frame_options();
   	}

	public function documentation()
	{
        if(!$this->start_session(true))
            return;
        
        $this->load->helper('cookie');
        set_cookie("startupapp","doc", 60*60*24*30*12);
        
        $data['title'] = get_branding()->get_title() . " - Documentation";
	$data['is_sgw'] = "false";
	if(isset($_SESSION["ns_is_sgw"])){
	     $data['is_sgw'] = $_SESSION["ns_is_sgw"];
	}
		if(is_agee())
			$this->load->view("docs/agee_docs", $data);
		else if(is_cisco_paltform())
			$this->load->view("docs/cisco_docs", $data);
		else
			$this->load->view("docs/docs", $data);
	}

	public function configuration()
	{
        if(!$this->start_session(true))
            return;
        $data['ns_version'] = $this->get_ns_version();
	    $data['title'] = get_branding()->get_title() . ' - Configuration';
		$this->load->view("gui/gui", $data);
	}

    // Returns the build number with extra characters removed
    private function get_ns_version()
    {
        if($_SESSION['nsversion_error'] == false)
        {
            $version_arr = explode(",", $_SESSION['nsversion']);
            $return_str = $version_arr[0];
            //Remove dot, semi-colon and space
            return preg_replace("/\.|:| /", "", $return_str);
        }
        return preg_replace("/\./", "", $_SESSION['rand']);
    }

    public function downloads()
	{
        if(!$this->start_session(true))
            return;
		
        $this->load->helper('cookie');
        set_cookie("startupapp","dw", 60*60*24*30*12);
        
	   $data['title'] = get_branding()->get_title() . ' - Downloads';
	   $data['is_sgw'] = "false";
	   if(isset($_SESSION["ns_is_sgw"])){
		$data['is_sgw'] = $_SESSION["ns_is_sgw"];
	   }
		if(is_cisco_paltform())
			$this->load->view("docs/cisco_downloads", $data);
		else
			$this->load->view("docs/downloads", $data);
	}

    public function branding()
    {
        $session_started = $this->start_session(false, false);

        $this->output->set_header("Content-type: application/javascript");
        $branding_folder = $_SERVER["DOCUMENT_ROOT"] . "/admin_ui/common/js/ns/branding/";
        $output = file_get_contents($branding_folder . "branding.js.txt");
        if($session_started && !is_ns())
            $output .= "\n\n" . file_get_contents($branding_folder . "branding_" . strtolower($_SESSION["nsbrand"]) . ".js.txt");
        $this->output->set_output($output);
    }
    
    public function neoglobaldata()
    {
        $session_started = $this->start_session(false, false);

        $this->output->set_header("Content-type: application/javascript");
        $output = "var global_data = ".$this->getData() ;
        ob_start("ob_gzhandler");
        $this->output->set_output($output);
    }

    public function neo()
    {
        if(!$this->start_session(true))
            return;
        
        $this->load->helper('cookie');
        set_cookie("startupapp","neo", 60*60*24*30*12);
        $current_partition = $this->get_default_partition();
        $current_path = isset($current_partition["partitionid"]) ? ("/" . $current_partition["partitionname"]) : "";
        
        $search = array("/\{title_brand\}/", "/\{neo_logout_url\}/", "/\{neo_machine_sysid\}/", "/\{user_rand}/", "/\{gui_rand_token}/", "/\{partition_dir}/");
        $replace = array(get_branding()->get_title(), get_logout_url(), get_sysid(), $_SESSION["rand"], $_SESSION["rand"], $current_path);
        $neo_html = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/admin_ui/neo/html/neo.html");
        $neo_html = preg_replace($search, $replace, $neo_html);
        
        ob_start("ob_gzhandler");
        $this->output->set_header("Content-type: text/html;application/octet-stream;application/ecmascript;application/json;application/xml");
                
        $this->output->set_output($neo_html);
    }
    
    private function getCmdPolicy(&$global_data_index, &$global_data_array)
    {
        $username = $_SESSION['username'];
        
        if($username == "nsroot")
        {
            $result = array();
            $result["message"] = "Done";
            $result["severity"] = "NONE";
            $result["cmdpolicy_binding"] = array("superuser");
            $global_data_index ["cmdpolicy"] = sizeof($global_data_index);
            array_push($global_data_array,$result);
            return;
        }
	
	if($this->execute_command('getsystemuser_systemgroup_binding', array("username" => $username)) == 0)
        {
            $result = $this->get_model()->get_result();
            if($result["rc"] == 0 && count($result["List"]) > 0)
            {
                foreach( $result["List"] as $systemgroup)
                {
		    if($this->execute_command('getsystemgroup_systemcmdpolicy_binding', array("groupname" => $systemgroup["groupname"])) == 0)
		    {
		       $systemgroupcmdpolicybindresult = $this->get_model()->get_result();
		       if($systemgroupcmdpolicybindresult["rc"] == 0 && count($systemgroupcmdpolicybindresult["List"]) > 0)
		       {
			    foreach( $systemgroupcmdpolicybindresult["List"] as $systemgroupcmdpolicybindobj)
			    {
				if($systemgroupcmdpolicybindobj["policyname"] ==  "superuser")
				{
					$result = array();
					$result["message"] = "Done";
					$result["severity"] = "NONE";
					$result["cmdpolicy_binding"] = array("superuser");
					$global_data_index ["cmdpolicy"] = sizeof($global_data_index);
					array_push($global_data_array,$result);
					return;
				}
			    }
		       }       
		    }		
                }
            }     
        }
        
        if($this->execute_command('getsystemuser_systemcmdpolicy_binding', array("username" => $username)) == 0)
        {
            $result = $this->get_model()->get_result();
            if($result["rc"] == 0 && count($result["List"]) > 0)
            {
                $cmdpolicy_binding = array();
                foreach( $result["List"] as $systemcmdpolicy)
                {
                   $cmdpolicy_binding[ ] = $systemcmdpolicy["policyname"];
                }
                
                $result["cmdpolicy_binding"] = $cmdpolicy_binding;
                unset($result["List"]);
                unset($result["rc"]);
            
                $global_data_index ["cmdpolicy"] = sizeof($global_data_index);
                array_push($global_data_array,$result);
            }   
        }
    }
    
    private function getData()
    {
        $global_data_array = array();
        $global_data_index = array();
        $global_data_requests = array("getclusterinstance", "getnsconfig", "getnshardware", "system_info", "getsystembwserver", "getnslicense", "getsslfipskey", "getsslfips",
                                 "getnsfeature", "getnsversion", "getsslparameter", "statssl","getnslicenseserver");
        
        $global_data_requests_count = count($global_data_requests);
        for($i = 0; $i < $global_data_requests_count; $i++)
        {
            $global_data_request = $global_data_requests[$i];

            if(preg_match('/^get/', $global_data_request))
                $this->getDataFromNitro($global_data_request, $global_data_array);
            else if(preg_match('/^stat/', $global_data_request))
                $this->getDataFromNitro($global_data_request, $global_data_array);
            else
                $this->getDataFromRapi($global_data_request, $global_data_array);   
            
            if(preg_match("/^stat/", $global_data_request))
            {
                $global_data_index[$global_data_request] = $i;
            }
            else if(preg_match("/^get/", $global_data_request))
            {
                $global_data_index[preg_replace('/^get/', '', $global_data_request)] = $i;
            }
            else
            {
                $global_data_index[$global_data_request] = $i;
            }
            
        }
        
        $this->getCmdPolicy($global_data_index, $global_data_array);
        
        return json_encode(array("data" => $global_data_array, "indexof" => $global_data_index));
    }
    
    private function getDataFromNitro($cmd, &$global_data_array)
    {
        $this->execute_command($cmd, array());
        $result = $this->get_model()->get_result();

        if($result["rc"] == 0)
        {
            if(count($result["List"]) > 0)
                $result[preg_replace('/^get/', '', $cmd)] = $result["List"][0];
            
            unset($result["List"]);
            unset($result["rc"]);
            
            array_push($global_data_array,$result);
        }
        else
        {
            array_push($global_data_array,$result['message']);
        }
        
    }
    
    private function getDataFromRapi($cmd, &$global_data_array)
    {
        require_once(APPPATH . "controllers/common/rapi.php");
        $rapi = new rapi(true);
        $result = $rapi->main($cmd);
        array_push($global_data_array,$result);
    }

    public function topn()
    {
        if(!$this->start_session())
            return;
        $data = $this->validate_arguments(func_get_args(), array("name"));
        $data["name"] = urldecode($data["name"]);

        if(isset($data["name"]))
            $data["name"] = htmlentities($data["name"], ENT_QUOTES, "UTF-8");
        
        $search = array("/\{title_brand\}/", "/\{topn_logout_url\}/", "/\{GLOBAL_STREAM_IDENTIFIER_NAME\}/", "/\{user_rand}/");
        $replace = array($_SESSION["nsbrandDesc"], get_logout_url(), $data["name"], $_SESSION["rand"]);
        $topn_html = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/admin_ui/reporting/topn/html/topn.html");
        $topn_html = preg_replace($search, $replace, $topn_html);

        $this->output->set_header("Content-type: text/html");

        $this->output->set_output($topn_html);
    }

    public function agee()
    {
        $data = $this->validate_arguments(func_get_args(), array("standalone"), array("show_ftu"));

        if(!$this->start_session())
            return;

        $search = array("/\{title_brand\}/", "/\{agee_logout_url\}/", "/\{user_rand}/");
        $replace = array($_SESSION["nsbrandDesc"], get_logout_url(), $_SESSION["rand"]);
        $agee_html = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/admin_ui/home/agee/html/agee.html");
        $agee_html = preg_replace($search, $replace, $agee_html);

        $this->output->set_header("Content-type: text/html");

        $this->output->set_output($agee_html);
    }

    public function xenmobile()
    {
        $data = $this->validate_arguments(func_get_args(), array("standalone"));

        if(!$this->start_session())
            return;

        $search = array("/\{title_brand\}/", "/\{neo_logout_url\}/", "/\{user_rand}/");
        $replace = array($_SESSION["nsbrandDesc"], get_logout_url(), $_SESSION["rand"]);
        $agee_html = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/admin_ui/home/xenmobile/html/xenmobile.html");
        $agee_html = preg_replace($search, $replace, $agee_html);

        $this->output->set_header("Content-type: text/html");
        $this->output->set_output($agee_html);
    }

	public function cb()
    {
    	$data = $this->validate_arguments(func_get_args(), array("standalone"), array("show_ftu"));

        if(!$this->start_session())
            return;
        
        $this->load->helper('cookie');
        
        $search = array("/\{title_brand\}/", "/\{cb_logout_url\}/", "/\{user_rand}/");
        $replace = array($_SESSION["nsbrandDesc"], get_logout_url(), $_SESSION["rand"]);
        $cb_html = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/admin_ui/home/cloudbridge/html/cloudbridge.html");
        $cb_html = preg_replace($search, $replace, $cb_html);

        $this->output->set_header("Content-type: text/html");

        $this->output->set_output($cb_html);
    }

    public function error()
    {
        $arg_list = func_get_args();
        $data = $this->validate_arguments($arg_list, array('error'));
        
        if($data["error"] == "SESSION_CORRUPTED")
        {
            $additional_message = "Please login again."; 
            $freespace = disk_free_space("/var");
            
            if(!is_bool($freespace)  && intval($freespace)  == 0)
            {
                $additional_message = "File system (/var) is full on the appliance";
            }
            
            print $this->load->view('common/login_view', array('loginerror' => "Session is invalid. " .$additional_message), true);
            exit();
        }
        else
        {
            require_once("utils.php");
            $this->load->view('common/errormessage', $data);
        }
    }
    
    public function neo_configuration_applet()
    {
        $arg_list = func_get_args();
        $data = $this->validate_arguments($arg_list, array("gui_token"), array("node_name", "detail_view", "detail_view_params", "detail_view_title"));

        if(!$this->start_session(true))
            return;
        if($_SESSION["rand"] != $data["gui_token"])
        {
        	$this->show_404();
        	return;
        }

        if(isset($data["node_name"]))
        	$data["node_name"] = htmlspecialchars(urldecode($data["node_name"]));
        else if(isset($data["detail_view"]))
        {
        	$data["detail_view"] = htmlspecialchars(urldecode($data["detail_view"]));
        	$data["standalone"] = "YES";
        	$data["no_footer_link"] = true;

        	if(isset($data["detail_view_params"]))
        		$data["detail_view_params"] = htmlspecialchars(urldecode($data["detail_view_params"]));

        	if(isset($data["detail_view_title"]))
        		$data["detail_view_title"] = htmlspecialchars(urldecode($data["detail_view_title"]));
        }

        $data['title'] = get_branding()->get_title() . ' Configuration Utility';
        $data['ns1'] = $_SESSION['username'];
        $data['ns2'] = get_sysid();
        $data['ns3'] = $this->get_http_host_ip();
        $data['ns4'] = $_SESSION['nsbrand'];
        $data['ns5'] = "true";
        $data['ns7'] = urlencode($_SESSION['NSAPI']);
        $data['ns8'] = "";
        $data['ns9'] = "";
        $data['ns10'] = $_SESSION['nsvpx'];

        $data["jvm_memory"] = "256M";

        $this->output->set_header("Content-type: application/javascript");
        $this->load->view("gui/neoappletloader", $data);
    }

    public function configuration_applet()
	{
        $arg_list = func_get_args();
        $data = $this->validate_arguments($arg_list, array("gui_token"), array("node_name", "detail_view", "detail_view_params", "detail_view_title"));

        if(!$this->start_session(true))
            return;

		if($_SESSION["rand"] != $data["gui_token"])
		{
			$this->show_404();
			return;
		}

        if(isset($data["node_name"]))
        	$data["node_name"] = htmlspecialchars(urldecode($data["node_name"]));
		else if(isset($data["detail_view"]))
		{
			$data["detail_view"] = htmlspecialchars(urldecode($data["detail_view"]));
			$data["standalone"] = "YES";
			$data["no_footer_link"] = true;

			if(isset($data["detail_view_params"]))
				$data["detail_view_params"] = htmlspecialchars(urldecode($data["detail_view_params"]));

			if(isset($data["detail_view_title"]))
				$data["detail_view_title"] = htmlspecialchars(urldecode($data["detail_view_title"]));
		}

        $data['title'] = get_branding()->get_title() . ' Configuration Utility';
        $data['ns1'] = $_SESSION['username'];
        $data['ns2'] = get_sysid();
        $data['ns3'] = $this->get_http_host_ip();
        $data['ns4'] = $_SESSION['nsbrand'];
        $data['ns5'] = "true";
        $data['ns7'] = urlencode($_SESSION['NSAPI']);
        $data['ns8'] = "";
        $data['ns9'] = "";
        $data['ns10'] = $_SESSION['nsvpx'];
        $data["jvm_memory"] = "256M";

        $this->output->set_header("Content-type: text/html;application/octet-stream;application/ecmascript;application/json;application/xml");
		$this->load->view("gui/guiapplet", $data);
	}

    public function configuration_webstart()
	{
        $arg_list = func_get_args();
        $data = $this->validate_arguments($arg_list, array('nsbrand', 'protocol', 'id'), array('nsvpx'));
		$data['serv'] = $this->get_http_host_ip();
        $data['port'] = $this->get_http_host_port();

		$data['content'] = $this->load->view("gui/gui_ws.xml", $data, TRUE);
		$this->output->set_header("Content-type: application/x-java-jnlp-file");
		$this->output->set_header("<?xml version=\"1.0\" encoding=\"utf-8\"?>");
        $this->output->set_header("Cache-Control: max-age=10");
		$this->output->set_output($data['content']);
	}

    public function logout()
    {
        $arg_list = func_get_args();
        
        if(!$this->start_session())
        {
            return;
        }
        
        $data = $this->validate_arguments($arg_list, array('rand'));

        // rand is used to prevent CSRF attack
        if(!isset($_SESSION['rand']) || strcmp($data['rand'], $_SESSION['rand']) !== 0)
    	{
            parent::redirect('', 'location');
    	}
    	else
    	{
    		$this->execute_command('logout', '');
    		$this->destroy_session();
    		parent::redirect('', 'location');
    	}
    }

    // If the session gets expired while viewing applet, user can relogin from the applet
    // itself. In this case, a new token is given to the applet client, which should replace
    // the NSAPI token stored in PHP session.
    public function update_token()
    {
        if(!$this->start_session_for_ajax_request())
            return;
        $arg_list = func_get_args();
        $data = $this->validate_arguments($arg_list, array('NSAPI', 'rand'));

        if(!isset($_SESSION['NSAPI'], $_SESSION['rand']) || strcmp($data['rand'], $_SESSION['rand']) !== 0)
        {
            $this->show_404();
        }

        $_SESSION['NSAPI'] = urldecode($data['NSAPI']);
    }

    // While loading on the client, it reads the location's hash (things after # in URL) and posts that.
    // Used in invoking Dashboard for appview from webstart client.
    public function webstart_post()
    {
        $data['standalone'] = "YES";
        $data['title'] = "Loading";

        print $this->load->view("common/header", $data, true);
        print "\n<script type=\"text/javascript\">\n";
        print "\tpost_data(window.location.hash.substring(1));\n";
        print "</script>\n";
        print $this->load->view("common/footer", $data, true);
    }

    // Used as part of SSO from SDX GUI to NS GUI to find whether a PHP session exists
    public function check_session_sdx()
    {
        $session_started = $this->start_session(false, false);

        if(!$session_started)
        {
            $this->send_no_cache_headers();
        }

        $host_ip = $this->get_http_host_ip();
        $session_started_str = $session_started ? "true" : "false";
        print "\tif(typeof(svm) != 'undefined' && typeof(svm.session_started) == 'function')\n";
        print "\t\tsvm.session_started('$host_ip', $session_started_str, true);\n";
    }

    // Used by SDX GUI to pass the token & username to NS GUI and skip login step (after check_session_sdx() above)
    public function setup_session()
    {
        $data = $this->validate_arguments(func_get_args(), array("sid", "username"), array("startin", "standalone", "force_setup"));
        $is_session_setup = $this->start_session(false, false);
	
        require_once(APPPATH . "controllers/common/utils.php");
        
        if(!$is_session_setup)
        {
            // Spoof as if user has logged in with the passed token
            utils::setup_webstart_session($data["sid"]);

            //Fire a command and validate the token
            $response = array();
            if(!$this->validate_session($response)) //Invalid token
            {
                $login_data["loginerror"] = $response["message"];
                print $this->load->view("common/login_view", $login_data, true);
                exit;
            }

            // Valid token - setup the real PHP session (as if user has logged in from login page)
            // and redirect to the right app
            utils::setup_webstart_user_session($data["sid"], $data["username"]);
        }
    	else if(isset($data["force_setup"]))
    	{
    	    utils::setup_webstart_user_session(urldecode($data["sid"]), $data["username"], null, true);
    	}

    	if(isset($data["startin"]) && isset($data["standalone"]))
    	{
    		$url = $this->get_url_for_app($data["startin"]);
    		$url = $url . "?" . "standalone=" . $data["standalone"];
    		$this->check_and_redirect_banner($url);
    	}
    	else
    	        $this->check_and_redirect_banner($this->get_url_for_app(input_validator::validate_and_get_cookie("startupapp")));
    }
    
    public function import_file($url = "/nsconfig/ssl/", $start_Session = true, $use_ssh = false)
    {
        if($start_Session && !$this->start_session(true, false))
        {
	    $retVal = $this->print_error_mess("SESSION_CORRUPTED_PLAIN", false);
            $response["errorcode"] = -1;
            $response["message"] = $retVal;
	    
            print json_encode($response);
            return;
        }
	
        if($this->validate_request() === false)
        {
            return;
        }
	
        $url = $this->get_directory_path($url);
        $response = array("errorcode" => 0, "message" => "Upload Successful");
	
        if(!isset($_FILES["import_file_name"]))
        {
            $retVal = $this->print_error_mess("IMPORT_REPORTS_INVAL_REQUEST", false);
            $response["errorcode"] = -1;
            $response["message"] = $retVal;
	    
            print json_encode($response);
            return;
        }

        $imported_file_details = $_FILES["import_file_name"];

        if(!is_uploaded_file($imported_file_details["tmp_name"]))
        {
            switch($imported_file_details["error"])
            {
                case UPLOAD_ERR_INI_SIZE:
                    $retVal = $this->print_error_mess("IMPORT_REPORTS_ERR_INI_SIZE", false);
                case UPLOAD_ERR_FORM_SIZE:
                    $retVal = $this->print_error_mess("IMPORT_REPORTS_ERR_FORM_SIZE", false);
                case UPLOAD_ERR_PARTIAL:
                    $retVal = $this->print_error_mess("IMPORT_REPORTS_ERR_PARTIAL", false);
                case UPLOAD_ERR_NO_FILE:
                    $retVal = $this->print_error_mess("IMPORT_REPORTS_ERR_NO_FILE", false);
                case UPLOAD_ERR_NO_TMP_DIR:
                    $retVal = $this->print_error_mess("IMPORT_REPORTS_ERR_NO_TMP_DIR", false);
                case UPLOAD_ERR_CANT_WRITE:
                    $retVal = $this->print_error_mess("IMPORT_REPORTS_ERR_CANT_WRITE", false);
                case UPLOAD_ERR_EXTENSION:
                    $retVal = $this->print_error_mess("IMPORT_REPORTS_ERR_EXTENSION", false);
                default:
                {
                    $retVal = $error_prefix . "There was a problem with your upload(" . $imported_file_details["error"] . ").\nPlease try again.";
                    $response["errorcode"] = -1;
                    $response["message"] = $retVal;
		    
                    print json_encode($response);
                    return;
                }
            }
        }

        if($imported_file_details["size"] == 0)
        {
            $retVal = $this->print_error_mess("IMPORT_REPORTS_ERR_EMPTY_FILE", false);
            $response["errorcode"] = -1;
            $response["message"] = $retVal;
	    
            print json_encode($response);
            return;
        }

		if(preg_match('/ssl\/*$/', $url))
        {
            $file_content = file_get_contents($_FILES['import_file_name']['tmp_name']);            
            if(!$this->move_ssl_files($url, $imported_file_details["name"], $file_content) === true)
                return;
        }
        else
        {
            if(!$this->move_files($url . $imported_file_details["name"], $imported_file_details["tmp_name"], $use_ssh) === true)
                return;
        }
        
        print json_encode($response);
        return SUCCESS_RESULT;
    }
}
?>
