<?php  if(!defined("BASEPATH")) exit("No direct script access allowed");

class neo_proxy
{

    private $url;
    private $sessionid;
    private $ip_address;
    private $username;
    private $password;
    private $APP_ERROR_CODES = array(200, 201, 400, 401, 403, 404, 405, 406, 409, 500, 501, 503, 599);

    public function __construct($ip_address, $username = null, $password = null, $sessionid = null, $secure_access = false)
    {
        $this->ip_address = $ip_address;
        $this->username = $username;
        $this->password = $password;
        $this->sessionid = $sessionid;

        if($secure_access == true)
        {
            $this->url = "https://" . $this->get_enclosed_address_if_ipv6($ip_address) . "/nitro/v1/";
        }
        else
        {
            $this->url = "http://" . $this->get_enclosed_address_if_ipv6($ip_address) . "/nitro/v1/";
        }
    }

    public function login(&$login_response_obj)
    {
        if($this->sessionid != null)
        {
            $login_response_obj = new response(array("sessionid" => $this->sessionid));
            return true;
        }
        else
    	{
            $url = $this->url . "config";
            $payload_json = array("login" => array("username" => $this->username, "password" => $this->password));
            
            $login_response_obj = $this->execute_req(HttpRequest::METH_POST, $url, null, $payload_json, "login");      
            $login_status = $this->is_command_successful($login_response_obj);

            if(!$login_status)
                $login_response_obj = new response(array("errorcode" => -1, "message" =>"Unable to login to ".$this->ip_address));

            return $login_status;
        }    
    }    

    private function logout($sessionid)
    {
        $url = $this->url . "config/logout";
        $payload_json = array("sessionid" => $sessionid, "logout" => array());
        return $this->execute_req(HttpRequest::METH_POST, $url, null, $payload_json, "logout");
    }

    private function execute_req($req_type, $url, $session_id, $payload, $obj_name, $timeout = PROXY_REQ_TIMEOUT, $is_sdx = false)
    {
        $http_request = new HttpRequest($url, $req_type);
        $http_request->setOptions(array("timeout"=>$timeout));

        if($timeout != PROXY_REQ_TIMEOUT)
        	set_time_limit($timeout);
            
        if($session_id != null)
        {
            if ($is_sdx)
                $http_request->addCookies(array("SESSID" => $session_id));           
            else
                $http_request->addCookies(array("sessionid" => $session_id));
        }
        
        if($payload != null)
        {
            $payload = json_encode($payload, JSON_FORCE_OBJECT);
            $http_request->setPostFields( array("object" => $payload));
        }

        $resp_array = array("errorcode" => -1, "message" => "Unable to complete $obj_name request");

        try
        {
            log_message('debug', "Request : Object Name=" .$obj_name ." Method=" .$req_type ." url=" .$url ." payload=" .($payload == null ? "null" : $payload));
            $http_message = $http_request->send();
            $resp = $http_message->getBody();
            $resp_code = $http_message->getResponseCode();

            log_message('debug', "Response code is : " .$resp_code);
            log_message('debug', "Response : " .$resp);

            if($this->is_valid_error_code($resp_code))
            {
                $tmp_resp_array = json_decode($resp, true);
                $error_no = json_last_error() ;
                if ($error_no != JSON_ERROR_NONE)
                     log_message('debug',"Error no is " .$error_no);
                else
                    $resp_array = $tmp_resp_array;
            }

            return new response($resp_array);
        }
        catch (HttpException $ex)
        {
            log_message('debug', "Response : Exception, " .$ex);
            return new response($resp_array);
        }
    }

    private function get_req_url($request)
    {
        $return_url = $this->url ;
        $command = $request->get_request_name();

        if(preg_match("/^stat/", $command))
        {
            $return_url = $return_url . "stat/";
            $return_url = $return_url. preg_replace("/^stat/", "", $command);
        }
        else if(preg_match("/^get/", $command))
        {
            $return_url = $return_url . "config/" ;
            $return_url = $return_url. preg_replace("/^get/", "", $command);
        }
        else if(preg_match("/^post/", $command))
        {
            $return_url = $return_url . "config" ;
        }
        else if(preg_match("/^delete/", $command))
        {
            $return_url = $return_url . "config" ;
        }

        if($request->get_additional_command_parms() != null && $request->get_additional_command_parms() != "")
        {
            $return_url = $return_url ."/" .$request->get_additional_command_parms();
        }

        // TODO: for get handle the request parameters also

        return $return_url;
    }

    public function get($requests, &$responses, $is_sdx=false)
    {
        $responses = array();

        if(!$this->login($login_response))
        {
            $responses[] = $login_response;
            return false;
        }

        $login_response_obj = $login_response->get_response();
        
        if ($is_sdx)
        {
            if(!isset($login_response_obj["login"]))
                return false;

            $sessionid = $login_response_obj["login"][0]["sessionid"];
        }    
        else
        {
            if(!isset($login_response_obj["sessionid"]))
                return false;

            $sessionid = $login_response_obj["sessionid"];   
        }    
         
        $status = true;

        foreach($requests as $request)
        {
            $get_url = $this->get_req_url($request);
            $get_response_obj = $this->execute_req(HttpRequest::METH_GET, $get_url, $sessionid, null, $request->get_obj_name(), $request->get_timeout(), $is_sdx);

            if ($is_sdx)                         
                $responses[] = $get_response_obj->get_response();
            else            
                $responses[] = $get_response_obj;

            if(!$this->is_command_successful($get_response_obj))
            {
                $status = false;
                break;
            }
        }

        if($this->sessionid == null)
            $this->logout($sessionid);
         
        return $status;
    }

    public function post($requests, &$responses)
    {
        $responses = array();
        if(!$this->login($login_response))
        {
            $responses[] = $login_response;
            return false;
        }

        $login_response_obj = $login_response->get_response();
        $sessionid = $login_response_obj["sessionid"];
        $status = true;

        foreach($requests as $request)
        {
            $post_url = $this->get_req_url($request);
            $post_body = $request->get_parameters_add_item("sessionid", $sessionid);

            $post_response_obj = $this->execute_req(HttpRequest::METH_POST, $post_url, $sessionid, $post_body, $request->get_obj_name(), $request->get_timeout());
			array_push($responses, $post_response_obj);

            if(!$this->is_command_successful($post_response_obj))
            {
                $status = false;
                break;
            }
        }

        if($this->sessionid == null)
            $this->logout($sessionid);
        
        return $status;
    }


    public function delete($requests, &$responses)
    {
        $responses = array();
        if(!$this->login($login_response))
        {
            $responses[] = $login_response;
            return false;
        }

        $login_response_obj = $login_response->get_response();
        $sessionid = $login_response_obj["sessionid"];
        $status = true;

        foreach($requests as $request)
        {
            $post_url = $this->get_req_url($request);
            $post_response_obj = $this->execute_req(HttpRequest::METH_DELETE, $post_url, $sessionid, null, $request->get_obj_name(), $request->get_timeout());
            $responses[] = $post_response_obj;

            if(!$this->is_command_successful($post_response_obj))
            {
                $status = true;
                break;
            }

        }

        if($this->sessionid == null)
            $this->logout($sessionid);
        
        return true;
    }

    public function is_command_successful($response_obj)
    {
        $response = $response_obj->get_response();

        if(is_array($response))
        {
            if(isset($response["rc"]))
            {
                $response["errorcode"] = $response["rc"];
                unset($response["rc"]);
                $response_obj->set_response($response);
            }

            return $response["errorcode"] == 0;
       }

       return false;
    }

    function is_valid_error_code($error_code)
    {
        return  in_array($error_code, $this->APP_ERROR_CODES);
    }
     
    private function get_enclosed_address_if_ipv6($ipaddress)
	{
		if (preg_match("/^\s*((([0-9A-Fa-f]{1,4}:){7}([0-9A-Fa-f]{1,4}|:))|(([0-9A-Fa-f]{1,4}:){6}(:[0-9A-Fa-f]{1,4}|((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){5}(((:[0-9A-Fa-f]{1,4}){1,2})|:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){4}(((:[0-9A-Fa-f]{1,4}){1,3})|((:[0-9A-Fa-f]{1,4})?:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){3}(((:[0-9A-Fa-f]{1,4}){1,4})|((:[0-9A-Fa-f]{1,4}){0,2}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){2}(((:[0-9A-Fa-f]{1,4}){1,5})|((:[0-9A-Fa-f]{1,4}){0,3}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){1}(((:[0-9A-Fa-f]{1,4}){1,6})|((:[0-9A-Fa-f]{1,4}){0,4}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(:(((:[0-9A-Fa-f]{1,4}){1,7})|((:[0-9A-Fa-f]{1,4}){0,5}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:)))(%.+)?(\/(1[0-2][0-8]|[1-9][0-9]|[0-9]))?\s*$/",$ipaddress))
			return "[". $ipaddress ."]";
		else
			return $ipaddress;
	}
}

class request
{
    private $parameters;
    private $request_name;
    private $obj_name;
    private $additional_command_parms = "";
    private $timeout = PROXY_REQ_TIMEOUT;

    public function __construct($request_name, $parameters, $obj_name, $additional_parms=null)
    {
        $this->request_name = $request_name;
        $this->parameters = $parameters;
        $this->obj_name = $obj_name;
        $this->additional_command_parms = ((isset($additional_parms)) ? $additional_parms : "");
    }

    public function get_request_name()
    {
        return  $this->request_name;
    }

    public function get_parameters()
    {
        return $this->parameters;
    }

    public function get_parameters_add_item($key, $value)
    {
        return $this->parameters = array_merge($this->parameters, array($key => $value));
    }

    public function set_additional_command_parms($param)
    {
        $this->additional_command_parms = $param;
    }

    public function get_additional_command_parms()
    {
        return $this->additional_command_parms;
    }

    public function set_timeout($timeout)
    {
        $this->timeout = $timeout;
    }

    public function get_timeout()
    {
        return $this->timeout;
    }

    public function get_obj_name()
    {
        return $this->obj_name;
    }
}

class response
{
    private $response;

    public function __construct($response_array)
    {
        $this->response = $response_array;
    }

    public function get_response()
    {
        return $this->response;
    }

    public function set_response($response_array)
    {
        $this->response = $response_array;
    }
}

?>
