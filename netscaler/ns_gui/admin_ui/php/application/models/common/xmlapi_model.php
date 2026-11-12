<?php  if(!defined("BASEPATH")) exit("No direct script access allowed");

require_once("abstract_model.php");

require_once(BASEPATH . "/libraries/class.nusoap_base.php");
require_once(BASEPATH . "/libraries/class.soapclient.php");
require_once(BASEPATH . "/libraries/class.soap_parser.php");
require_once(BASEPATH . "/libraries/class.soap_transport_http.php");

class xmlapi_model extends abstract_model
{
    private $soap_client;

    function xmlapi_model()
    {
        parent::abstract_model();
        $this->soap_client = new nusoap_client("http://" . $this->server_ip . "/soap");
    }

    protected function command_execution($command, $parameters, $use_api = true)
    {
        //Reporting can use API & exe to execute commands. To make it work, comment the following line.
        $use_api = true;
        if(!$use_api)
        {
            $exec_command = "/netscaler/nscollect " . $this->convert_parameters_to_string($command, $parameters);
            $this->benchmark->mark("ns_exe_start");
            $exe_result = exec($exec_command);
            $this->benchmark->mark("ns_exe_end");
            $elapsed_time = $this->benchmark->elapsed_time("ns_exe_start", "ns_exe_end");
            log_message("profile", $elapsed_time . " --> EXE_EXECUTION_TIME " . $command);
            $this->result["rc"] = 0;
            $this->result["message"] = "Done";
            $this->result["List"] = array(array("response"    =>    $exe_result));
            $return_value = 0;
        }
        else //Send a command to SOAP server by passing the current NSAPI token in soap cookie
        {
            if(isset($_SESSION["NSAPI"]))
            {
                $cookies = array(array( "name"      =>  "NSAPI",
                                        "value"     =>  $_SESSION["NSAPI"],
                                        "domain"    =>  $_SESSION["NSAPI_DOMAIN"],
                                        "path"      =>  $_SESSION["NSAPI_PATH"]));
                $this->soap_client->UpdateCookies($cookies);
            }
            $this->benchmark->mark("ns_api_start");
            $this->result = $this->soap_client->call($command, $parameters, "urn:NSConfig");
            $this->benchmark->mark("ns_api_end");
            $elapsed_time = $this->benchmark->elapsed_time("ns_api_start", "ns_api_end");
            log_message("profile", $elapsed_time . " --> API_EXECUTION_TIME " . $command);
            $return_value = 0;

            if($this->soap_client->getError())
            {
                $return_value = -1;
                $this->set_error_code();
                $this->set_error_message($command, $this->soap_client->getError());
            }
            else if($this->result["rc"] != 0)
                $return_value = $this->result["rc"];
        }
        return $return_value;
    }

    //Perform login and setup session based on response
    function execute_login_command($cmd, $parameters)
    {
        global $g_session_started;
        $return_value = $this->command_execution($cmd, $parameters);

        if($return_value == 0 || $return_value == 1034)
        {
            $soap_cookies = $this->soap_client->getCookies();
            $soap_session_cookie = $soap_cookies[0];

            if(isset($_SERVER["HTTP_REFERER"]) && preg_match("/^https/", $_SERVER["HTTP_REFERER"]))
            {
                $params = session_get_cookie_params();
                session_set_cookie_params($params["lifetime"], $params["path"], $params["domain"], true);
            }
            if(!isset($_SESSION["NSAPI"]))
            {
                //If user is sending any SESSID while logging in, ignore that
                if(isset($_COOKIE[session_name()]))
                    unset($_COOKIE[session_name()]);
                session_start();
                $g_session_started = true;
            }

            //In case of challenge-response, a new NSAPI cookie is assigned for each response.
            $_SESSION["NSAPI"] = $soap_session_cookie["value"];
            $_SESSION["NSAPI_DOMAIN"] = $soap_session_cookie["domain"];
            $_SESSION["NSAPI_PATH"] = $soap_session_cookie["path"];
        }
        return $return_value;
    }

    function convert_parameters_to_string($command, $parameters)
    {
        $return_str = $command;
        $return_str = preg_replace("/^getsystem/", "", $return_str);
        $return_str = preg_replace("/^get/", "", $return_str);
        if(empty($parameters))
            return $return_str;
        $pos_counter = array("counters" => 1, "countergroup" => 1, "unit" => 1, "name" => 1 , "type" => 1);
        foreach($parameters as $param_name=>$param_value)
        {
            if(isset($pos_counter[$param_name]))
                $return_str = $return_str . " " . $param_value;
            else
                $return_str = $return_str . " -" . $param_name . " " . $param_value;
        }
        return $return_str;
    }
}
?>