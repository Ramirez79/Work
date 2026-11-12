<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once("constants.php");
require_once("input_validator.php");

$g_session_started = false;
$g_branding = null;
abstract class abstract_controller extends CI_Controller
{
    var $app_selected_map = array(
            'st'    =>  '/menu/st',
            'neo'  =>  '/menu/neo',
            'rep'   =>  '/menu/rep',
            'cb'   =>  '/menu/cb',
            'doc'   =>  '/menu/doc',
            'dw'    =>  '/menu/dw'
    );
    var $current_request_type = PAGE_REQUEST;
    var $current_request_file_type;
    private $model;

    function __construct()
    {
        parent::__construct();
        $this->ensure_session_save_path();
    }

    private function ensure_session_save_path()
    {
        $path = session_save_path();
        $is_valid = true;
        if(!is_dir($path))
        {
            if(!@mkdir($path))
                $is_valid = false;
        }
        else if((fileperms($path) & 0x0007) != 0x0007)
             $is_valid = false;
        if(!$is_valid)
        {
            require_once("utils.php");
            print $this->load->view('common/errormessage', array('error' =>  "SESSION_SAVE_PATH_ERROR"), true);
            exit();
        }
    }

    public final function redirect($URL)
    {
        redirect($URL);
    }

    protected function show_error_page($error)
    {
        $this->redirect("/menu/er?error=$error");
    }

    protected function check_and_redirect_banner($url)
    {
        if(file_exists(LOGIN_BANNER_FILE) && (filesize(LOGIN_BANNER_FILE) > 0))
        {
            $_SESSION['redirect_url'] = $url;
            $this->redirect("/login/banner");
        }
        $this->redirect($url);
    }

    protected function show_404()
    {
        header("HTTP/1.0 404 Not Found");
        echo "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\"><HTML><HEAD><meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" /><TITLE>404 Not Found</TITLE>";
        echo "</HEAD><BODY><H1>Not Found</H1>The requested URL was not found on this server.<P></BODY></HTML>";
        exit;
    }

    protected function check_permissions($command, $exit_on_error=false)
    {
        $error_code = $this->execute_command("getclipermission",  array("commandname" => $command));
     
        if($exit_on_error == true)
            return $error_code;
        
        if($error_code == 0)
	       return;
    	else if($error_code == 2138)
    	{
                print json_encode(array('errorcode' => $error_code, 'message' => "Not authorized to execute this command [{$command}]", 'severity' =>"ERROR"));
    	    exit;
    	}
            else
    	{
                print json_encode(array('errorcode' => $error_code, 'message' => "", 'severity' =>"ERROR"));
    	    exit;
    	}
    }
    
    protected function move_and_check_file($dest_file, $src_file, $net_sftp)
    {
        if (is_file($src_file))
        {  
            $success = $net_sftp->put($dest_file , $src_file, NET_SFTP_LOCAL_FILE);

            if (!$success)
            {
                $this->print_error_for_api_request("Unable to create output file", 257);
                exit;
            }
        }
    }
    
    protected function validate_request()
    {
        $header_key = "rand_key";
        $rand_key = "";
        $headers = apache_request_headers();
        
        if(isset($headers[$header_key]))		
    	{
            $rand_key = $headers[$header_key];
        }
        else if(isset($_POST[$header_key]))
        {
            $rand_key = $_POST[$header_key];
        }
        else if(isset($_GET[$header_key]))
        {
            $rand_key = $_GET[$header_key];
        }

        if(!isset($rand_key) || (isset($_SESSION) && strcmp($rand_key, $_SESSION["rand"]) !== 0))
        {
            $this->show_404();
            return false;
        }

        return true;
    }
    
    protected function send_no_cache_headers()
    {
        header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
    }

    protected function send_x_frame_options()
    {
        // Don't allow page to be embedded inside a frame except same origin
        header("X-Frame-Options: SAMEORIGIN");
    }

    // If page number is not set, initilizes it to use in caching the result
    // Else increments the page number
    // Sets up "rno" as well
    protected function setup_page_rno(&$data, $page_number_key)
    {
        if(!isset($_SESSION[$page_number_key]))
            $_SESSION[$page_number_key] = 1;
        else if(!$this->is_ajax_request($data)) //Don't increment the page count for AJAX data requests
            $_SESSION[$page_number_key]++;

        $this->setup_rno($data, $page_number_key);
    }

    // If "rno" is not set, initializes it to use in caching the result
    // Else clears previously cached result
    protected function setup_rno(&$data, $page_number_key)
    {
        if(!isset($data["rno"]))
        {
            if(isset($_SESSION[$page_number_key]))
                $data["rno"] = $_SESSION[$page_number_key] . "_0";
        }
        else
            $this->clear_cache($data);
    }

    // Clears the previously cached result based on current rno & func
    // Optionally cleans up the currently cached result itself
    protected function clear_cache($data, $clear_current = false)
    {
        list($page_number, $request_number) = explode("_", $data["rno"]);
        $request_number = intval($request_number);
        if($request_number == 0)
            $this->remove_cached_object($page_number . "_1000_" . $data["func"]); //This number should be consistent with stats_controller.js
        else
            $this->remove_cached_object($page_number . "_" . ($request_number - 1) . "_" . $data["func"]);
        if($clear_current)
            $this->remove_cached_object($page_number . "_" . $request_number . "_" . $data["func"]);
    }

    // Clear the object stored in session. Objects are identified by unique ids.
    protected function remove_cached_object($object_id)
    {
        if(isset($_SESSION[$object_id]))
        {
            unset($_SESSION[$object_id]);
        }
    }

    protected function get_model()
    {
        return $this->model;
    }

    // Lazy initialization of nitro model
    private function get_nitro_model()
    {
        if(!isset($this->nitro_model))
        {
            $this->load->model('common/nitro_model');
            if(!isset($this->nitro_model))
                $this->nitro_model = new nitro_model();
        }
        $this->model = $this->nitro_model;
        return $this->nitro_model;
    }

    protected function get_client_ip()
    {
        // If client comes on NSIPv6, "internal NAT" will map IPv6 request to IPv4 Apache.
        // Original IPv6 client address will be sent in "cip-header" as "[<ipv6>]" ex: [2980::10]
        // If client comes on NSIPv4, still "cip-header" will be present ex: 12.32.23.33
        if(isset($_SERVER["HTTP_CIP_HEADER"]))
        {
            return preg_replace("/[\[\]]/", "", $_SERVER["HTTP_CIP_HEADER"]);
        }
        
        return $_SERVER["REMOTE_ADDR"];
    }

    protected function get_http_host_ip()
    {
        //If client comes on NSIPv6, "internal NAT" will map IPv6 request to IPv4 Apache.
        //Original IPv6 address will be sent in "vip-header" as "[<ipv6>]_<port>" ex: [2980::10]_80
        if(isset($_SERVER["HTTP_VIP_HEADER"]))
            return preg_replace("/_\d+$/", "", $_SERVER["HTTP_VIP_HEADER"]);
        //In client side SSH tunnelling, host name might have port; assumes IPv4 address
        return preg_replace("/:\d+$/", "", $_SERVER['HTTP_HOST']);
    }

    protected function get_server_address()
    {
        //Added for getting the ip to which client is conneting as part for the new login mechanisim
        //Tested for access to NSIP, CLIP access using ssh tunneling and loalhost
         if(isset($_SERVER["SERVER_ADDR"]))
            return $_SERVER["SERVER_ADDR"];

         return null;
    }

    protected function get_http_host_port()
    {
        preg_match("/.*(:\d+)$/", $_SERVER['HTTP_HOST'], $matches);
        return (isset($matches[1]) ? $matches[1] : "");
    }

    //To be called by those controllers who accept "sessionid" in URL but don't fire any commands. Ex: syslog
    protected function validate_session(&$response)
    {
        $return_value = $this->execute_command("getclimode", '');
        $result = $this->get_model()->get_result();
        if(in_array($return_value, array(NSERR_NOUSER, NSERR_TCPCONNFAIL, NSERR_LOGINFAIL, NSERR_NOLOGIN, NSERR_AUTHTIMEOUT)))
        {
            $response["errorcode"] = $return_value;
            $response["message"] = $result["message"];
            return false;
        }
        return true;
    }

    //All stat commands (global/entity without bindings) & get commands (except appfwprofile) are fetched through NITRO
    protected function use_nitro($command, $parameters)
    {
        if(preg_match("/^stat/", $command))
            return !is_array($parameters) || !isset($parameters["bindings"]);
        return preg_match("/^get/", $command);
    }

    protected function execute_login_command($command, $parameters)
    {
        return $this->get_nitro_model()->execute_login_command($command, $parameters);
    }

    protected function execute_command($command, $parameters, $use_cache=false, $object_id='', $use_api = true)
    {
            $return_value = $this->get_nitro_model()->execute_command($command, $parameters, $use_cache, $object_id, $use_api);
        
        if($this->get_model()->get_error_code() == NSERR_SESSION_EXPIRED)
        {
            $this->destroy_session();
            switch($this->current_request_type)
            {
                case PAGE_REQUEST:
                    $data['loginerror'] = NSERR_SESSION_EXPIRED_MESSAGE;
                    print $this->load->view('common/login_view', $data, true);
                    exit;
                case AJAX_REQUEST:
                    header(XML_HEADER);
                    print "<messages>\n<error val=\"" . NSERR_SESSION_EXPIRED_MESSAGE . "\" rc=\"" . NSERR_SESSION_EXPIRED . "\"/>\n</messages>";
                    exit;
                case IMAGE_REQUEST:
                    $data['error_message'] = NSERR_SESSION_EXPIRED_MESSAGE;
                    print $this->load->view("reporting/chart", $data, true);
                    exit;
                case FILE_REQUEST:
                    $this->print_error_for_file_request(NSERR_SESSION_EXPIRED_MESSAGE, true);
                    $this->output->_display();
                    exit;
                case API_REQUEST:
                    $this->print_error_for_api_request(NSERR_SESSION_EXPIRED_MESSAGE, NSERR_SESSION_EXPIRED);
                    exit;
            }
        }
        return $return_value;
    }

    function index()
    {
        if(!$this->start_session())
            return;

        $this->redirect($this->get_url_for_app(input_validator::validate_and_get_cookie("startupapp")));
    }

    function get_url_for_app($app)
    {
        if(is_agee())
            return "/menu/agee?standalone=no";
        else if(is_ocb())
            return "/menu/cb?standalone=no";
	else if($app == "neo")
            return "/menu/neo";
	
        if(isset($this->app_selected_map[$app]))
            return $this->app_selected_map[$app];
        return $this->app_selected_map["neo"];
    }

    protected function getView()
    {
        return 'common/login_view';
    }

    protected function getViewData()
    {
        $data['loginerror'] = "You are not logged in. Please login.";
        return $data;
    }

    protected function check_is_secondary(&$data)
    {
        //Get hanode details for self node (id: 0) and determine the state of self node.
        $this->execute_command("gethanode", array("id" => 0));
        $result = $this->get_model()->get_result();
        if($result["rc"] != 0 || count($result["List"]) == 0 || !isset($result["List"][0]["state"]))
            return;
        if($result["List"][0]["state"] === NS_S_SECONDARY)
            $data["nserr_not_primary"] = NSERR_NOT_PRIMARY;
    }

    protected function validate_request_number(&$data)
    {
        if(isset($data["rno"]) && !preg_match("/^-?\d+_\d+$/", $data["rno"]))
        {
            unset($data["rno"]);
            return false;
        }
        return true;
    }

    protected function validate_event_number(&$data)
    {
        if(isset($data["evtno"]) && !preg_match("/^\d+$/", $data["evtno"]))
        {
            unset($data["evtno"]);
            return false;
        }
        return true;
    }

    protected function retrieve_data_for_top_gauges(&$data)
    {
        $data["func"] = "statns";
        $data["is_ajax_request"] = $this->is_ajax_request($data);
        $this->setup_page_rno($data, GAUGES_PAGE_NUMBER_KEY);
        $hide_top_gauges = isset($data["hide_top_gauges"]) ? $data["hide_top_gauges"] : false;

        //Spoof command execution in cases like top gauges is closed
        if($hide_top_gauges)
        {
            $empty_result = $this->get_empty_result();
            $_SESSION[$data["rno"] . "_" . $data["func"]] = $empty_result;
            $data["event_result"] = $empty_result;
        }

        //Retrieve statns for showing HTTP requests/s (this result will be shared by dials)
        $this->execute_command($data["func"], "", true, $data["rno"] . "_" . $data["func"]);
        $data["http_result"] = $this->get_model()->get_result();

        //Retrieve system events
        if(!$hide_top_gauges)
        {
            $this->execute_command("getnsevents", isset($data["evtno"]) ? array("eventno" => $data["evtno"]) : "");
            $data["event_result"] = $this->get_model()->get_result();
        }
    }

    public function get_empty_result()
    {
         return array("rc" => 0, "List" => array(1));
    }

    protected function is_ajax_request($data)
    {
        return (isset($data['outputformat']) && (strcmp($data['outputformat'], "DATA") == 0));
    }

    protected function start_session_for_image_request($validate_backend_session = false)
    {
        $this->current_request_type = IMAGE_REQUEST;
        if(!$this->start_session($validate_backend_session, false))
        {
            require_once("utils.php");
            $data['error_message'] = utils::get_error_message("SESSION_CORRUPTED_PLAIN");
            print $this->load->view("reporting/chart", $data, true);
            return false;
        }
        return true;
    }

    protected function start_session_for_ajax_request($validate_backend_session = false, $validate = true)
    {
        $this->current_request_type = AJAX_REQUEST;
        if(!$this->start_session($validate_backend_session, false))
        {
            if(!$validate)
                return false;
            require_once("utils.php");
            header(XML_HEADER);
            $error_message = utils::get_error_message("SESSION_CORRUPTED_PLAIN");
            $error_message = preg_replace("/\n/", "\\n", $error_message);
            print "<messages>\n<error val=\"$error_message\" rc=\"" . NSERR_SESSION_EXPIRED . "\"/>\n</messages>";
            return false;
        }
        return true;
    }

    protected function is_file_request($data)
    {
        return (isset($data['outputformat']) && (strcmp($data['outputformat'], "FILE") == 0));
    }

    protected function print_error_for_file_request($error_message, $redirect = false, $reload = false)
    {
        $redirect_text = "";
        if($redirect)
            $redirect_text = "parent.location.href = \"/\";\n";
        $reload_text = "";
        if($reload)
            $reload_text = "parent.location.reload();\n";
        $this->output->set_output("<html>\n<script type=\"text/javascript\">\nalert(\"" . addcslashes($error_message, "\n") . "\");\n$redirect_text$reload_text</script>\n<body></body></html>\n");
    }

    protected function start_session_for_file_request($validate_backend_session, $file_type)
    {
        session_cache_limiter('must-revalidate');
        $this->current_request_type = FILE_REQUEST;
        $this->current_request_file_type = $file_type;
        if(!$this->start_session($validate_backend_session, false))
        {
            require_once("utils.php");
            $this->print_error_for_file_request(utils::get_error_message("SESSION_CORRUPTED_PLAIN"), true);
            return false;
        }
        return true;
    }

    protected function print_error_for_api_request($message, $error_code = -1)
    {
        header(JSON_HEADER);

        require_once(APPPATH . "controllers/common/rapi_utils.php");
         
        $response = array(
            "errorcode" => $error_code,
            "message" => $message
        );
        print json_encode(rapi_utils::sanitize_response($response));
    }

    protected function start_session_for_api_request($validate_backend_session = false, $print_on_error = true)
    {
        $this->current_request_type = API_REQUEST;
        if(!$this->start_session($validate_backend_session, false))
        {
            if($print_on_error)
                $this->print_error_for_api_request(NSERR_SESSION_EXPIRED_MESSAGE, NSERR_SESSION_EXPIRED);
            return false;
        }
        return true;
    }

    //Validates and starts PHP session.
    //$validate_backend_session - If true, a no-op command will be fired to validate session state in the back-end
    //$redirect - If false, no automatic redirects will be issued in invalid cases. Instead false will be returned.
    public function start_session($validate_backend_session = false, $redirect = true)
    {
        global $g_session_started;
        //check for SESSID cookie
        if(!isset($_COOKIE[session_name()]))
        {
            if(!$redirect)
                return 0;
            $this->load->view($this->getView(), $this->getViewData());
            return 0;
        }
        if(isset($_SERVER['HTTP_REFERER']) && preg_match("/^https/", $_SERVER['HTTP_REFERER']))
        {
            $params = session_get_cookie_params();
            session_set_cookie_params($params["lifetime"], $params["path"], $params["domain"], TRUE);
        }
        //Valid characters and length of SESSID come from php.ini.
        //Refer session.hash_bits_per_character and session.hash_function
        //Cookie with invalid characters will cause the SESSID to be regenerated.
        if(!preg_match('/^[a-f0-9]{32}$/', $_COOKIE[session_name()]))
        {
            self::delete_cookie(session_name());
            if(!$redirect)
                return 0;
            $this->show_error_page("SESSION_CORRUPTED");
        }
        session_start();
        $g_session_started = true;
        //Check for valid session. This is to avoid user adding/editing SESSID cookie
        //to get access to NS GUI.
        if(!isset($_SESSION['NSAPI']))
        {
            $this->destroy_session();
            if(!$redirect)
                return 0;
            $this->show_error_page("SESSION_CORRUPTED");
        }
        if($validate_backend_session)
            $this->execute_command("getclimode", '');
        return 1;
    }

    // Destroys the session.
    public function destroy_session()
    {
        global $g_session_started;
        self::delete_cookie(session_name());
        try
        {
            // Calling session_destroy() without calling session_start() is an error (hence not caught in this exception block).
            if($g_session_started)
                session_destroy();
            $g_session_started = false;
        }
        catch(Exception $e)
        {
            // This could happen when two scripts are running parallely and one already destroyed the session.
            // Second script will fail with "Session object destruction failed". Just ignore this.
        }
    }

    public static function delete_cookie($cookie_name)
    {
        // Use 1 second since epoch instead of relative time from now like "time() - 42000"
        // which won't delete cookie in IE if the time is changed to a future date.
        if(isset($_COOKIE[$cookie_name]))
            setcookie($cookie_name, "deleted", 1, '/');
    }

    // Validates method arguments and shows an error page if invalid
    // Else returns a map of name-value pairs
    public function validate_arguments($arg_list, $mandatory_arg_name_list, $optional_arg_name_list = array())
    {
        $num_args = count($arg_list);
        $data = array();
        if($num_args % 2 != 0)
            $this->show_404();

        //Merge both mandatory & optional argname arrays and convert them into a map for quick lookup (swap keys & values)
        //Ex: mandatory - array("a1", "a2"), optional - array("a3", "a4"), convert it to array("a1" => 0, "a2" => 1, "a3" => 2, "a4" => 3)
        $all_arg_name_map = array_flip(array_merge($mandatory_arg_name_list, $optional_arg_name_list));
        for ($i = 0; $i < $num_args; $i = $i+2)
        {
            if(!isset($all_arg_name_map[$arg_list[$i]]))
                $this->show_404();
            $data[$arg_list[$i]] = $arg_list[$i+1];

        }
        foreach($mandatory_arg_name_list as $mandatory_arg_name)
        {
            if(!isset($data[$mandatory_arg_name]))
                $this->show_404();
        }
        return $data;
    }

    // Validates arguments for HTTP method of type POST and shows an error page if invalid
    // Else returns a map of name-value pairs
    // If validate_nonce is true and $_SESSION["rand_key"]=$_SESSION["rand"] in not present in POST body, it will show an error page.
    // On client side, use ns.ajax_handler to POST which will automatically send $_SESSION["rand_key"]=$_SESSION["rand"].
    // Should be in sync with utils.js::post_data() utils.js::ns.ajax_handler().
    public function validate_arguments_for_post($mandatory_arg_name_list, $optional_arg_name_list = array(), $validate_nonce = true)
    {
        if(!is_array($_POST))
        {
            $this->show_404();
        }

        $data = array();

        if($validate_nonce)
        {
            $mandatory_arg_name_list[] = $_SESSION["rand_key"];
        }

        // Merge both mandatory & optional argname arrays and convert them into a map for quick lookup (swap keys & values)
        // Ex: mandatory - array("a1", "a2"), optional - array("a3", "a4"), convert it to array("a1" => 0, "a2" => 1, "a3" => 2, "a4" => 3)
        $all_arg_name_map = array_flip(array_merge($mandatory_arg_name_list, $optional_arg_name_list));
        
        foreach($_POST as $key=>$value)
        {
            if(!isset($all_arg_name_map[$key]) ||
               ($validate_nonce && strcmp($key, $_SESSION["rand_key"]) === 0 && strcmp($value, $_SESSION["rand"]) !== 0))
            {
                $this->show_404();
            }

            $data[$key] = $value;
        }

        foreach($mandatory_arg_name_list as $mandatory_arg_name)
        {
            if(!isset($data[$mandatory_arg_name]))
                $this->show_404();
        }

        if($validate_nonce)
        {
            unset($data[$_SESSION["rand_key"]]);
        }

        return $data;
    }

    // Validates arguments for HTTP method of type PUT and shows an error page if invalid
    // Else returns a map of name-value pairs
    // If validate_nonce is true and $_SESSION["rand_key"]=$_SESSION["rand"] in not present in POST body, it will show an error page.
    // On client side, use ns.ajax_handler to POST which will automatically send $_SESSION["rand_key"]=$_SESSION["rand"].
    // Should be in sync with utils.js::post_data() utils.js::ns.ajax_handler().
    public function validate_arguments_for_put($mandatory_arg_name_list, $optional_arg_name_list = array(), $validate_nonce = true)
    {
        $headers = apache_request_headers();
        $headers = array_change_key_case($headers, CASE_LOWER);

        $data = array();
        $data["object"] = file_get_contents('php://input');

        if($validate_nonce)
        {
            $mandatory_arg_name_list[] = $_SESSION["rand_key"];
        }

        // Merge both mandatory & optional argname arrays and convert them into a map for quick lookup (swap keys & values)
        // Ex: mandatory - array("a1", "a2"), optional - array("a3", "a4"), convert it to array("a1" => 0, "a2" => 1, "a3" => 2, "a4" => 3)
        $all_arg_name_map = array_flip(array_merge($mandatory_arg_name_list, $optional_arg_name_list));
        
        foreach($headers as $key=>$value)
        {
            if($validate_nonce && strcmp($key, $_SESSION["rand_key"]) === 0 && strcmp($value, $_SESSION["rand"]) !== 0)
            {
                $this->show_404();
            }

            $data[$key] = $value;
        }

        foreach($mandatory_arg_name_list as $mandatory_arg_name)
        {
            if(!isset($data[$mandatory_arg_name]))
            {
                $this->show_404();
            }
        }

        if($validate_nonce)
        {
            unset($data[$_SESSION["rand_key"]]);
        }

        return $data;
    }

    protected function print_error_mess($error_message, $print = true)
    {
        require_once(APPPATH."controllers/common/utils.php");
        $error = utils::get_error_message($error_message);

        if($print)
        {
            print $error;
        }

        return $error;
    }

    // Used to move files generally after upload. Folders like /nsconfig/licenses will not have permissions for upload by apache user in that case we use nitro file copy
    public function move_ssl_files($dest_file_abs_path, $filename, $file_content, $print_response = true)
    {
        require_once(APPPATH. "controllers/common/neo_proxy.php");
        
        $systemfile = array("filename" => $filename, "filecontent" => base64_encode($file_content), "filelocation" => $dest_file_abs_path);

        $request = new neo_proxy("127.0.0.1", null, null, $_SESSION["NSAPI"]);
        $req_obj = array("params" =>  array("override" => "YES"), "systemfile" => $systemfile);                

        $success = $request->post(array(new request("post", $req_obj, "File Upload")),$responses);
        if(!$success)
        {
            if($print_response)
            {
                print json_encode($responses[0]->get_response()) ;
            }

            return false;
        }

		return true;
    }

    public function move_files($dest_file_abs_path, $source_file_abs_path, $use_ssh = false, $print_response = true)
    {
        require_once(APPPATH . "controllers/common/rapi_utils.php");

        if(($net_sftp = rapi_utils::get_sftp($sftp_response)) === false)
        {
            $response["errorcode"] = -1;
            $response["message"] = $sftp_response["message"];

            if($print_response)
            {
                print json_encode($response);
            }

            return false;
        }

        if(!file_exists(dirname($dest_file_abs_path)))
        {
            $this->_recursive_mkdir($net_sftp, dirname($dest_file_abs_path));
        }

        if($use_ssh)
        {
            if(($net_ssh = rapi_utils::get_ssh($ssh_response, 10)) !== false)
            {
                $net_ssh->setTimeout(5);
                $net_ssh->write("shell\n");
                $net_ssh->write("mv {$source_file_abs_path} {$dest_file_abs_path}\n");

                $net_ssh->read('');
            }
            else
            {
                $retval = $this->print_error_mess("IMPORT_REPORTS_ERR_FILE_COPY", false);
                $response["errorcode"] = -1;
                $response["message"] = $retval;

                if($print_response)
                {
                    print json_encode($response);
                }

                return false;                
            }
        }    
        else
        {    
            $sftp_result = $net_sftp->put($dest_file_abs_path, $source_file_abs_path, NET_SFTP_LOCAL_FILE);
            if($sftp_result === false)
            {
                $retval = $this->print_error_mess("IMPORT_REPORTS_ERR_FILE_COPY", false);
                $response["errorcode"] = -1;
                $response["message"] = $retval;

                if($print_response)
                {
                    print json_encode($response);
                }

                return false;
            }
        }

        return true;
    }

    protected function get_directory_path($path)
    {
        $all_partitions = $this->get_partition_data();

        if(sizeof($all_partitions) == 0)
        {
            return $path;
        }
           
        $partiton_info = $this->get_default_partition($all_partitions);

        if(sizeof($partiton_info) == 0)
        {
            return $path;   
        }
            
        if($path === "/nsconfig/ssl" || $path === "/nsconfig/ssl/")
        {
            $path = "/nsconfig/partitions/" . $partiton_info["partitionname"] . "/ssl/";
        }
        
        if($path === "/var/netscaler/ssl" || $path === "/var/netscaler/ssl/")
        {
            $path = "/var/partitions/" . $partiton_info["partitionname"]  . $path;
        }
        
        return $path;
    }
    
    protected function isSuperUser($username)
    {
        if($username == "nsroot")
            return "true";
        
	if($this->execute_command('getsystemuser_systemcmdpolicy_binding', array("username" => $username)) == 0)
        {
            $result = $this->get_model()->get_result();
            if($result["rc"] == 0 && count($result["List"]) > 0)
            {
                foreach( $result["List"] as $systemcmdpolicy)
                {
                   if($systemcmdpolicy["policyname"] ==  "superuser")
                        return "true";
                }
            }
            
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
				    return "true";
			    }
		       }       
		    }		
                }
            }     
        }

        return "false";
    }

    protected function get_partition_data()
    {
        if($this->isSuperUser($_SESSION["username"]) == "true")
        {
            $this->execute_command("getnspartition", array());
            $all_partitions = $this->get_model()->get_result();
        
            if($all_partitions["rc"] != 0 && count($all_partitions["List"])  == 0)
                return array();
        
            return $all_partitions["List"];
        }
        else
        {
            $this->execute_command("getsystemuser_nspartition_binding", array("username" => trim($_SESSION["username"])));
            $all_partitions = $this->get_model()->get_result();
            
            if($all_partitions["rc"] != 0 && count($all_partitions["List"])  == 0)
                return array();
        
            return $all_partitions["List"];   
        }
    }
    
    protected function get_default_partition($all_partitions)
    {
        $this->execute_command("getnspartition", array());
        $all_partitions = $this->get_model()->get_result();
        
        if($all_partitions["rc"] == 0 && count($all_partitions["List"])  > 0)
        {
            $all_partitions = $all_partitions["List"];
            for ($i = 0; $i < count($all_partitions); $i++) 
            {
                if(isset($all_partitions[$i]["partitiontype"]) && strcmp($all_partitions[$i]["partitiontype"], "Current Partition") == 0)
                {
                    return $all_partitions[$i];
                }
            }
        }
        
        return array();
    }
	
	protected function recursive_mkdir(&$net_sftp, $path)
	{
		if(!file_exists($path))
		{
			$this->recursive_mkdir($net_sftp, dirname($path));
			$net_sftp->mkdir($path);
		}
	}
}
?>
