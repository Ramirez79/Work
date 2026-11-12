<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
//This class has utility and validate functions used in Dashboard page
class utils
{
    //Validate function name eg: statns
    function validate_function($func)
    {
        $_ns_stat_definitions = new _ns_stat_definitions();
        if(!$_ns_stat_definitions->get_command($func))
            return 0;
        return 1;
    }

    //Validate argname eg: sitename
    function validate_argname($arg, $cmd)
    {
        $argname = self::get_argname($cmd);
        if (!strcmp($argname, $arg) || !strcmp($arg, "name"))
            return 1;
        return 0;
    }

    //Validate arg value based on VALTYPE eg: local_service1
    function validate_argvalue($value, $type)
    {
        if (!strcmp($type, "NS_VALNAME"))
        {
            if (preg_match("/^[ 0-9a-zA-Z#_.\@:=-]*$/m", $value))
                return 1;
        }
        else if ((!strcmp($type, "NS_VALUINT")) || (!strcmp($type, "NS_VALINT")))
        {
            if (preg_match("/^[0-9]*$/m", $value))
                return 1;
        }
        else if (!strcmp($type, "NS_VALIFN"))
        {
            if (preg_match("/^[0-9a-zA-Z\/.]*$/m", $value))
                return 1;
        }
        else if(!strcmp($type, "NS_VALSGNAME"))
        {
            if (preg_match("/^[ 0-9a-zA-Z#_.\@:=\-\?]*$/m", $value))
                return 1;
        }
        else if(!strcmp($type, "NS_VALIPV"))
        {
            if (preg_match("/^[0-9.]*$/m", $value))
                return 1;
        }
        else
        {
            if (preg_match("/^[0-9a-zA-Z]*$/m", $value))
                return 1;
        }
        return 0;
    }

    //Validate token
    function validate_sid($value)
    {
        if (preg_match("/^[ 0-9a-zA-Z#]*$/", $value))
            return 1;
        return 0;
    }

    //Validate output format eg: DATA, HTML
    function validate_outputformat($format)
    {
        $format = strtoupper($format);
        if (!strcmp($format, "HTML") || !strcmp($format, "XML") || !strcmp($format, "DATA"))
            return 1;
        return 0;
    }

    //Validate detail view eg: YES, NO
    function validate_detailview($view)
    {
        $view = strtoupper($view);
        if (!strcmp($view, "YES") || !strcmp($view, "NO"))
            return 1;
        return 0;
    }

    //Validate user name eg: nsroot
    function validate_username($username)
    {
        return self::validate_argvalue($username, "NS_VALNAME");
    }

    //Get the arg name for a particular stat command
    //Eg: servicegroupname for statservicegroup
    function get_argname($cmd)
    {
        if (isset($cmd) and isset($cmd['args']) and count($cmd['args']))
        {
            if(isset($cmd['namearg']))
                return $cmd['namearg'];
        }
        return "";
    }

    function show_error_page($error)
    {
        abstract_controller::redirect("/menu/er?error=$error");
    }

    function get_error_message($errorCode)
    {
        $errorCodeMap = Array(
            "INCORRECT_RESPONSE"    => "Incorrect response. Please close the browser and try again",
            "INVALID_FUNC"          => "Error : Invalid value [function]",
            "INVALID_ARGNAME"       => "Error : Invalid value [argname]",
            "INVALID_ARG"           => "Error : Invalid value [arg]",
            "INVALID_UNAME"         => "Error : Invalid value [username]",
            "INVALID_OPFORMAT"      => "Error : Invalid value [outputformat]",
            "INVALID_DETAILVIEW"    => "Error : Invalid value [detailview]",
            "INVALID_SID"           => "Error : Invalid value [sid]",
            "INAVLID_PAGENO"           => "Error : Invalid value [pageno]",
            "INVALID_COC_NAME"      => "Error : Invalid comparative chart name.",
            "INVALID_REPORT_NAME"   => "Error : Invalid report name. Click <a href=\"#noAnchor\" onclick=\"parent.location.href = '/menu/rep'\">here</a> to view reporting.",
            "INVALID_DS_NAME"       => "Error : Invalid data source name. Click <a href=\"#noAnchor\" onclick=\"parent.location.href = '/menu/rep'\">here</a> to view reporting.",
            "NO_DATA_AVAILABLE"             => "\nNO DATA TO  CHART",
            "INVALID_REQUEST"               => "Invalid request. Validation failed.",
            "ERROR_WRITING_REPORTS_FILE"    => "Error writing reports to the file (" . CUSTOM_REPORTS_FILE_PATH . ").",
            "ERROR_WRITING_REPORTS_CONF_FILE"    => "Error writing global configuration to the file (" . REPORTS_CONF_FILE_PATH . ").",
            "MAX_CUSTOM_REPORTS"            => "Maximum number of custom reports allowed is " . MAX_NO_OF_CUSTOM_REPORTS,
            "MAX_CUSTOM_FOLDERS"            => "Maximum number of custom folders allowed is " . MAX_NO_OF_CUSTOM_FOLDERS,
            "ERROR_REPORT_NOT_FOUND"        => "Report not found",
            "IMPORT_REPORTS_INVAL_REQUEST"  => "Invalid request: required file field missing.",
            "IMPORT_REPORTS_ERR_INI_SIZE"   => "File exceeds the maximum allowed size.",
            "IMPORT_REPORTS_ERR_FORM_SIZE"  => "File exceeds the permitted size.",
            "IMPORT_REPORTS_ERR_PARTIAL"    => "File was only partially uploaded.\nPlease try again.",
            "IMPORT_REPORTS_ERR_NO_FILE"    => "No file was selected.",
            "IMPORT_REPORTS_ERR_NO_TMP_DIR" => "Temporary folder doesn't exist.",
            "IMPORT_REPORTS_ERR_CANT_WRITE" => "Failed to write file to temporary folder.",
            "IMPORT_REPORTS_ERR_EXTENSION"  => "Invalid file type.",
            "IMPORT_REPORTS_ERR_EMPTY_FILE" => "File is empty.",
            "IMPORT_REPORTS_ERR_INVAL_FILE" => "Invalid file: validation failed.",
            "IMPORT_REPORTS_ERR_FILE_OPEN"  => "Invalid file: couldn't uncompress.\nPlease try again.",
            "IMPORT_REPORTS_ERR_FILE_DECODE"=> "Invalid file: couldn't decode uncompressed data.",
            "IMPORT_REPORTS_ERR_FILE_COPY"=> "File copy to the destination directory failed",
            "ERROR_CHART_NOT_FOUND"         => "Chart not found",
            "ERROR_WRITING_CHARTS_FILE"     => "Error writing charts to the file (" . CUSTOM_CHARTS_FILE_PATH . ").",
            "MAX_CUSTOM_CHARTS"             => "Maximum number of custom charts allowed is " . MAX_NO_OF_CUSTOM_CHARTS,
            "RESOURCE_NOT_FOUND"            => "Resource not found",
            "FOLDER_ALREADY_EXISTS"         => "Folder already exists",
            "TMP_FOLDER_CANNOT_BE_CREATED"  => "Unable to create temporay folder",
            "PARENT_FOLDER_DOESNT_EXIST"    => "Parent folder doesn't exist",
            "SESSION_SAVE_PATH_ERROR"       =>  "Access denied: " . session_save_path() . " does not exist on the appliance with read/write/execute permissions.",
            "SESSION_CORRUPTED_PLAIN"     => "Session is invalid. Possible reasons are\nSession expired or cancelled\nFile system (/var) is full on the appliance\nData in the browser's cache is obsolete. Open a new browser instance to login",
            "SESSION_CORRUPTED"     => "<div class=\"ns_alert_text\" style=\"padding-left: 300px\">Session is invalid. Possible reasons are<ul><li>Session expired or cancelled</li><li>File system (/var) is full on the appliance</li><li>Data in the browser's cache is obsolete. Open a new browser instance to login</li></ul></div>");
        if(isset($errorCodeMap[$errorCode]))
            return $errorCodeMap[$errorCode];
        return "Error : Invalid error code";
    }

    function set_default_argname($cmd)
    {
        if ( isset($cmd['args']) and (count($cmd['args']) > 0))
        {
            if(isset($cmd['namearg']))
                return $cmd['namearg'] ;
        }
        return "name";
    }

    //Validate all the parameters to Dashboard page
    function validateParams(&$data)
    {
        $parameter = "";
        if (!isset($data['func']))
        {
            if(isset($_COOKIE['dmon']))
                $data['func'] = $_COOKIE['dmon'];
            else
            {
                $data['func'] = is_agee() ? "stataaa" : "statns";
                
                require_once(APPPATH . "controllers/common/rapi.php");
                $rapi = new rapi(true);
                if($rapi->is_partiton_configured_and_is_not_default_partition())
                    $data['func'] = "statnspartition";

                $_COOKIE['dmon'] = $data['func'];
            }
        }
        $function = $data['func'];
        if (!self::validate_function($function))
        {
            abstract_controller::delete_cookie('dmon');
            self::show_error_page("INVALID_FUNC");
            exit(0);
        }
        $_ns_stat_definitions = new _ns_stat_definitions();
        $data['cmd'] = $_ns_stat_definitions->get_command($function);
        $cmd = $data['cmd'];
        if (isset($data['argname']))
        {
            if(!self::validate_argname($data['argname'], $cmd))
            {
                self::show_error_page("INVALID_ARGNAME");
                exit(0);
            }
        }
        else
            $data['argname'] = self::set_default_argname($cmd);

        if (!isset($cmd['namearg']))
            $data['argval'] = "";
        else
        {
            if (isset($data['argval']))
            {
                $data['argval'] = urldecode(preg_replace("/%252F/", "/", $data['argval']));
                if (!self::validate_argvalue($data['argval'], $function == "statservicegroupmember" ? "NS_VALSGNAME" : $cmd['nameargtype']))
                {
                    self::show_error_page("INVALID_ARG");
                    exit(0);
                }
            }
            else
                $data['argval'] = "";
        }
        if($function == "statservicegroupmember")
        {
            if(!($parameter = self::get_parameters_for_service_group_member($data['argval'])))
                self::show_error_page("INVALID_ARG");
        }
        else if(!ns_empty($data["argval"]))
        {
            $parameter[$data['argname']] = $data['argval'];
            if(isset($cmd['bindings']))
            {
                $parameter['bindings'] = $cmd['bindings'];
            }
        }

        if (isset($data['outputformat']))
        {
            if (!self::validate_outputformat($data['outputformat']))
            {
                self::show_error_page("INVALID_OPFORMAT");
                exit(0);
            }
        }
        else
            $data['outputformat'] = "HTML";

        if (isset($data['detailview']))
        {
            if (!self::validate_detailview($data['detailview']))
            {
                self::show_error_page("INVALID_DETAILVIEW");
                exit(0);
            }
        }
        else
            $data['detailview'] = "NO";

        if (isset($data['sid']))
            self::setup_webstart_session($data['sid']);

        if(isset($cmd["namearg"]))
        {
            if(!isset($data["pageno"]))
                $data["pageno"] = 1;
            else if(!preg_match("/^\d+$/", $data["pageno"]))
                self::show_error_page("INAVLID_PAGENO");
            $data["pagesize"] = input_validator::validate_and_get_cookie("stps");
        }
        else
        {
            if(isset($data["pageno"]))
                unset($data["pageno"]);
            if(isset($data["pagesize"]))
                unset($data["pagesize"]);
        }

        return $parameter;
    }

    //Validates sid and sets up the webstart user session before invoking a command
    function setup_webstart_session(&$sid, $redirect_on_error = true)
    {
        $sid = urldecode($sid);
        if(!self::validate_sid($sid))
        {
            if($redirect_on_error)
            {
                self::show_error_page("INVALID_SID");
                exit(0);
            }
            return false;
        }
        $_SESSION['NSAPI'] = $sid;
        $_SESSION['NSAPI_DOMAIN'] = '';
        $_SESSION['NSAPI_PATH'] = "/";
        return true;
    }

    //Sets up the webstart user session after invoking a command on success
    function setup_webstart_user_session($sid, $username, $timezone_offset = null, $force_setup = false)
    {
        global $g_session_started;
        if($g_session_started && $force_setup === false)
            return;
        $username = htmlspecialchars(urldecode($username));
        if($timezone_offset == null)
            $timezone_offset = input_validator::get_default_value("timezone_offset");

        if(isset($_SERVER['HTTP_REFERER']) && preg_match("/^https/", $_SERVER['HTTP_REFERER']))
        {
            $params = session_get_cookie_params();
            session_set_cookie_params($params["lifetime"], $params["path"], $params["domain"], TRUE);
        }
        if(!$g_session_started)
            session_start();
        $g_session_started = true;
        $_SESSION['NSAPI'] = $sid;
        $_SESSION['NSAPI_DOMAIN'] = '';
        $_SESSION['NSAPI_PATH'] = "/";

        require_once(APPPATH. "controllers/common/login.php");
        $login = new login();
        $this->load->helper('cookie');
        $login->setupUserSession($username, input_validator::get_default_value("timeout"), input_validator::get_default_value("unit"), $timezone_offset, input_validator::get_default_value("jvm_memory"));
    }

    //Returns a random number whose first part (before a dot) is a pseudo-random number and
    //the second part (after dot) is the number of microseconds since epoch
    function generate_random_number()
    {
        list($usec, $sec) = explode(" ", microtime());
        $time_in_micro_seconds = number_format((double) $sec + (double) $usec, 6, ".", "");
        return strval(mt_rand()) . "." . strval(preg_replace("/\./", "", $time_in_micro_seconds));
    }

    function get_parameters_for_service_group_member($full_name)
    {
        //If the order here is changed. Please make the changes in dashboard.js 'clear_stats_ok_clicked' function.
        //There is special handling for this command there too.
        if(!preg_match("/^(.*)\?(.*)\?(\d+)$/", $full_name, $matches))
            return false;
        return array(   "servicegroupname"              =>  $matches[1],
                        "servername"                    =>  $matches[2],
                        "port"                          =>  $matches[3]);
    }
}
?>
