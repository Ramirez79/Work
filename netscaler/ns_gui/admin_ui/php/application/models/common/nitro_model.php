<?php if(!defined("BASEPATH")) exit("No direct script access allowed");

require_once("abstract_model.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/admin_ui/nitro/nitro.php");

class nitro_model extends abstract_model
{
    function nitro_model()
    {
        parent::abstract_model();
    }
	
	function execute_login_command($cmd, $parameters)
    {
        global $g_session_started;
                
        $resp = $this->do_login($parameters);
        $this->result = $resp;

        if(isset($this->result["errorcode"]))
        {
            $this->result["rc"] = $this->result["errorcode"];
            unset($this->result["errorcode"]);
        }
        $return_value = $this->result["rc"];
                
        if($return_value == 0 || $return_value == 1034)
        {
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
            $_SESSION["NSAPI"] = $this->result["sessionid"];
            $_SESSION["NSAPI_DOMAIN"] = "";
            $_SESSION["NSAPI_PATH"] = "/";
         
         }   
		
        return $return_value;
    }
    
    function do_login($parameters)
    {
        try
        {
           $payload = json_encode(array("login" => $parameters), JSON_FORCE_OBJECT);
	       $resp = $this->execute_command("post", array("requestbody" =>  $payload, "method" => "POST"));
	       return $this->result; 
        }
        catch (HttpException $ex)
        {
            return array("errorcode" => -1, "message" => "Unable to connect to applicance");
        }
    }
    
    function do_logout()
    {
        try
        {
            $payload = json_encode(array("sessionid" => $_SESSION["NSAPI"], "logout" => array()), JSON_FORCE_OBJECT);
            $resp = $this->execute_command("post", array("requestbody" =>  $payload, "method" => "POST"));
            return $this->result; 
            
        }
        catch (HttpException $ex)
        {
            return array("errorcode" => -1, "message" => "Unable to connect to applicance");
        }
        
        $return_value = $this->result["rc"];
        return $return_value;
    }
    
    protected function command_execution($command, $parameters, $use_api = true)
    {
        //Construct query params
        if($command == "logout")
        {
            $this->do_logout();
            return;
        }
        
        $query_params = "?view=detail";
        if(isset($_SESSION["NSAPI"]))
            $query_params .= "&sessionid=" . urlencode($_SESSION["NSAPI"]);
        if(!ns_empty($parameters))
        {
            $args = "";
            foreach($parameters as $key => $value)
            {
                if($key == NITRO_MODEL_COUNT_PARAM)
                {
                    $query_params .= "&count=" . $value;
                    continue;
                }
		        if($key == "requestbody")
                {
                    $query_params .= "&requestbody=" . $value;
                    continue;
                }
                if($key == "method")
                {
                    $query_params .= "&method=" . $value;
                    continue;
                }
	            if($key == "statbindings")
                {
                    $query_params .= "&statbindings=" . $value;
                    continue;   
                }
                
                /*if($key == "bindings" && $value == "1")
                {
                    $query_params .= "&rawdata=yes";
                    continue;
                }*/
                
                $args .= (($args != "")? "," : "");
                
                
                $args .= $key . ":" . urlencode($value);
            }
            if($args != "")
                $query_params .= "&args=" . $args;
        }

        //Construct arguments
        $arg_list = array();
            if(preg_match("/^stat/", $command))
        {
            $arg_list[0] = "stat";
            $arg_list[1] = preg_replace("/^stat/", "", $command);
        }
        else if(preg_match("/^get/", $command))
        {
            $arg_list[0] = "config";
            $arg_list[1] = preg_replace("/^get/", "", $command);
        }
	else if(preg_match("/^post/", $command))
        {
            $arg_list[0] = "config";
            $arg_list[1] = preg_replace("/^post/", "", $command);
        }
	else 
            return -1;

        //Execute command over NITRO
        $this->benchmark->mark("ns_nitro_start");
        if($this->is_onboard_api())
        {
            $nitro = new nitro();
            $nitro_return_value = $nitro->v1($arg_list[0], $arg_list[1] . $query_params);
        }
        else
            $nitro_return_value = file_get_contents("http://" . $this->server_ip ."/nitro/v1/" . $arg_list[0] . "/" . $arg_list[1] . $query_params);
        $this->benchmark->mark("ns_nitro_end");
        $elapsed_time = $this->benchmark->elapsed_time("ns_nitro_start", "ns_nitro_end");
        log_message("profile", $elapsed_time . " --> NITRO_EXECUTION_TIME " . $command);

        //Process result
        if(ns_empty($nitro_return_value) || $nitro_return_value === false)
        {
            $this->set_error_code();
            $this->set_error_message($command);
        }
        else
        {
            $this->result = json_decode($nitro_return_value, true);
            if(isset($this->result["errorcode"]))
            {
                $this->result["rc"] = $this->result["errorcode"];
                unset($this->result["errorcode"]);
            }
            $arg_list[1] = $this->get_command_with_correct_case($arg_list[1]);
            if(isset($this->result[$arg_list[1]]))
            {
                if(is_array($this->result[$arg_list[1]]) && isset($this->result[$arg_list[1]][0]))
                    $this->result["List"] = $this->result[$arg_list[1]];
                else
                    $this->result["List"] = array($this->result[$arg_list[1]]);
                unset($this->result[$arg_list[1]]);
            }
            else
                $this->result["List"] = array();
        }

        return $this->get_error_code();
    }

    //Similar to java/ns/comm/rm/NitroProcessor.java and based on usr.src/netscaler/config/nitro/nitroutil.c
    private function get_command_with_correct_case($command)
    {
        if($command == "interface")
            $command = "Interface";
        else if($command == "import")
            $command = "Import";
        else if($command == "internal")
            $command = "Internal";
        else if($command == "namespace")
            $command = "Namespace";
        else if($command == "operator")
            $command = "Operator";
        else if($command == "static")
            $command = "Static";
        else if($command == "string")
            $command = "String";

        return $command;
    }
}
?>
