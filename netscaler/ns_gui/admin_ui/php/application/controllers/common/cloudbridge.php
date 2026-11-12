<?php if(!defined("BASEPATH")) exit("No direct script access allowed");

class cloudbridge extends abstract_controller
{
    public function __construct($current_request_type)
    {
        parent::__construct();
        $this->current_request_type = $current_request_type;
    }

    public function get_remoteip()
    {
        $data = $this->validate_arguments_for_post(array("object"), array(), false);
        $ns_ip_request = $data["object"];
        $nsip = json_decode( $ns_ip_request ,true);
        $this->set_session_properties($nsip["remoteip"]);
        $proxy = $this->get_proxy();

        if($proxy == null)
            return;

        $license_response = $this->is_remote_ip_having_cb_license($proxy);
        if( $license_response !== true)
        {
            return $license_response;
        }
        $req_obj = new request("getnsip", null, "Get Remote IP");
        $requests = array($req_obj);
        $proxy->get($requests, $responses);
        $response = $responses[0]->get_response();
        return $response;
    }

    private function is_remote_ip_having_cb_license($proxy)
    {
        $remoteip = $_SESSION["remoteip"];
        $req_obj = new request("getnslicense", null, "Get Remote Netscaler's License Info");
        $requests = array($req_obj);
        $proxy->get($requests, $responses);
        $response = $responses[0]->get_response();
        if($response["errorcode"] != 0 )
        {
            return $response;
        }
        //TODO: Add other things here..
        if($response["nslicense"]["cloudbridge"] === false)
        {
            $message = "Cloud Bridge feature is not licensed on the remote netscaler " . $_SESSION["remoteip"] . ".";
            $response = array(
            "errorcode" => -1,
            "message" => $message
            );
            return $response;
        }

        return true;
    }

    public function get_remote_nsip($object_name)
    {
        if ($_SERVER["REQUEST_METHOD"] == "GET")
        {
            $proxy = $this->get_proxy();
    
            if($proxy == null)
                return;
    
            $req_obj = new request("getnsip", null, "Get Remote IP", $object_name);
            $requests = array($req_obj);
            $proxy->get($requests, $responses);
            $response = $responses[0]->get_response();
            $response["remote_nsip"] = $response["nsip"];
            return $response;
        }
        else
            return $this->add_remote_nsip();
    }
    
    public function add_remote_nsip()
    {
        $data = $this->validate_arguments_for_post(array("object"), array(), false);
        $remote_nsip_request = $data["object"];
        $remote_nsip = json_decode( $remote_nsip_request ,true);
        $proxy = $this->get_proxy();

        if($proxy == null)
            return;

        $license_response = $this->is_remote_ip_having_cb_license($proxy);
        if( $license_response !== true)
        {
            return $license_response;
        }
        $proxy->post(array(new request("post", $remote_nsip, "Configure Remote SNIP")), $responses);
        $response = $responses[0]->get_response();
        return $response;
    }    

    public function remoteiptunnel($object_name)
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST')
        {
            $data = $this->validate_arguments_for_post(array("object"), array(), false);
            $iptunnel_add_request = $data["object"];
            $iptunnel = json_decode( $iptunnel_add_request ,true);
        }

        $request = $this->get_proxy();
        
        if($request == null)
            return;
        
        if ($this->input->server('REQUEST_METHOD') == 'POST')
        {
            $request->post(array(new request("post", $iptunnel, "Configure Remote IP Tunnel")), $responses);
        }
        else
        {
            $req_obj = new request("delete", "", "Configure Remote IP Tunnel");
            $req_obj->set_additional_command_parms("iptunnel/" .$object_name);
            $success = $request->delete(array($req_obj) ,$responses);
            $response_array = $responses[0]->get_response();
        }
        $response = $responses[0]->get_response();


        return $response;
    }
    
    public function remotepbr($object_name)
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST')
        {
            $data = $this->validate_arguments_for_post(array("object"), array(), false);
            $pbr_add_request = $data["object"];
            $pbr = json_decode( $pbr_add_request ,true);
        }

        $request = $this->get_proxy();
        if($request == null)
            return;
        
        if ($this->input->server('REQUEST_METHOD') == 'POST')
        {
            $request->post(array(new request("post", $pbr, "Configure Remote PBR")), $responses);
        }
        else
        {
            $req_obj = new request("delete", "", "Configure Remote PBR");
            $req_obj->set_additional_command_parms("nspbr/" .$object_name);
            $success = $request->delete(array($req_obj) ,$responses);
            $response_array = $responses[0]->get_response();
        }
        $response = $responses[0]->get_response();
        
        return $response;
    }

    public function remotenetbridge()
    {
        $data = $this->validate_arguments_for_post(array("object"), array(), false);
        $netbridge_add_request = $data["object"];
        $netbridge = json_decode( $netbridge_add_request ,true);

        $request = $this->get_proxy();
        if($request == null)
            return;

        $request->post(array(new request("post", $netbridge, "Configure Remote Netbridge")), $responses);
        $response = $responses[0]->get_response();

        return $response;
    }

    public function remotenetbridgebinding()
    {
        $data = $this->validate_arguments_for_post(array("object"), array(), false);
        $netbridge_binding_request = $data["object"];
        $netbridgebinding = json_decode( $netbridge_binding_request ,true);

        $request = $this->get_proxy();
        
        if($request == null)
            return;

        $request->post(array(new request("put", $netbridgebinding, "Binding Netbridge to IPTunnel")), $responses);
        $response = $responses[0]->get_response();

        return $response;
    }
    
    public function remotentpserver($object_name)
    {
        $data = $this->validate_arguments_for_post(array("object"), array(), false);
        $ntpserver_add_request = $data["object"];
        $ntpserevr = json_decode( $ntpserver_add_request ,true);
        $request = $this->get_proxy();
        if($request == null)
            return;
        
        $request->post(array(new request("post", $ntpserevr, "Enable Feature")), $responses);
        $response = $responses[0]->get_response();

        return $response;
    }
    
    public function remotensmode($object_name)
    {
        $data = $this->validate_arguments_for_post(array("object"), array(), false);
        $ipsecprofile_add_request = $data["object"];
        $ipsecprofile = json_decode( $ipsecprofile_add_request ,true);
        $request = $this->get_proxy();
        if($request == null)
            return;
        
        $request->post(array(new request("post", $ipsecprofile, "Enable Feature")), $responses);
        $response = $responses[0]->get_response();

        return $response;
    }
    
    public function remotensfeature($object_name)
    {
        $data = $this->validate_arguments_for_post(array("object"), array(), false);
        $ipsecprofile_add_request = $data["object"];
        
        $ipsecprofile = json_decode( $ipsecprofile_add_request ,true);
        $request = $this->get_proxy();
        
        if($request == null)
            return;
        
        $request->post(array(new request("post", $ipsecprofile, "Enable Feature")), $responses);
        $response = $responses[0]->get_response();

        
        return $response;
    }

    public function remoteipsecprofile($object_name)
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST')
        {
            $data = $this->validate_arguments_for_post(array("object"), array(), false);
            $ipsecprofile_add_request = $data["object"];
            $ipsecprofile = json_decode( $ipsecprofile_add_request ,true);
        }

        $request = $this->get_proxy();
        if($request == null)
            return;
        
        if ($this->input->server('REQUEST_METHOD') == 'POST')
            $request->post(array(new request("post", $ipsecprofile, "Configure Remote IPSec Profile")), $responses);
        else
        {
            $req_obj = new request("delete", "", "Configure Remote IPSec Profile");
            $req_obj->set_additional_command_parms("ipsecprofile/" .$object_name);
            $success = $request->delete(array($req_obj) ,$responses);
            $response_array = $responses[0]->get_response();
        }
        $response = $responses[0]->get_response();

        return $response;
    }

    public function get_proxy()
    {
        $ip_to_connect = $_SESSION["remoteip"];
        if($ip_to_connect == null || $ip_to_connect == "")
        {
            $this->print_error_for_api_request("Please enter a IPAddress" ,999);
            return;
        }

        $username = $_SESSION["remote_ns_username"];
        if($username == null || $username == "")
        {
            $this->print_error_for_api_request("Please enter the user name" ,999);
            return;
        }

        $password = $_SESSION["remote_ns_password"];
        if($password == null || $password == "")
        {
            $this->print_error_for_api_request("Please enter the password" ,999);
            return;
        }

        require_once(APPPATH. "controllers/common/neo_proxy.php");
        $proxy = new neo_proxy($ip_to_connect, $username, $password);
        return $proxy;
    }

    public function set_session_properties($remoteip)
    {
        $_SESSION["remoteip"] = $remoteip["ipaddress"];
        $_SESSION["remote_ns_username"] = $remoteip["username"];
        $_SESSION["remote_ns_password"] = $remoteip["password"];
    }
    
    public function import_public_key()
    {
        if(!isset($_FILES["public_key_file"]))
            return self::print_error_mess("IMPORT_REPORTS_INVAL_REQUEST", true);

        $imported_file_details = $_FILES["public_key_file"];
        $response = array("errorcode" => 0, "message" => "Upload Successful");
        $retval = $this->import_file($imported_file_details, true, true);
        if($retval === true)
        {
            print json_encode($response);
            return $response;
        }
        $response["errorcode"] = -1;
        $response["message"] = $retval;
        print json_encode($response);
        return $response;
    }
    
    public function import_private_key()
    {
        if(!isset($_FILES["private_key_file"]))
            return self::print_error_mess("IMPORT_REPORTS_INVAL_REQUEST", true);

        $imported_file_details = $_FILES["private_key_file"];
        $response = array("errorcode" => 0, "message" => "Upload Successful");
        $retval = $this->import_file($imported_file_details, true, false);
        if($retval === true)
        {
            print json_encode($response);
            return $response;
        }
        $response["errorcode"] = -1;
        $response["message"] = $retval;
        print json_encode($response);
        return $response;
    }
    public function import_peer_public_key()
    {
        if(!isset($_FILES["peer_public_key_file"]))
            return self::print_error_mess("IMPORT_REPORTS_INVAL_REQUEST", true);

        $imported_file_details = $_FILES["peer_public_key_file"];
        $response = array("errorcode" => 0, "message" => "Upload Successful");
        $retval = $this->import_file($imported_file_details, true, true);
        if($retval === true)
        {
            print json_encode($response);
            return $response;
        }
        $response["errorcode"] = -1;
        $response["message"] = $retval;
        print json_encode($response);
        return $response;
    }

    public function import_peer_private_key()
    {
        if(!isset($_FILES["peer_private_key_file"]))
            return self::print_error_mess("IMPORT_REPORTS_INVAL_REQUEST", true);

        $imported_file_details = $_FILES["peer_private_key_file"];
        $response = array("errorcode" => 0, "message" => "Upload Successful");
        $retval = $this->import_file($imported_file_details, false, true);
        if($retval === true)
        {
            print json_encode($response);
            return $response;
        }
        $response["errorcode"] = -1;
        $response["message"] = $retval;
        print json_encode($response);
        return $response;
    }
    
    private function import_file($imported_file_details, $upload_to_local, $upload_to_remote)
    {
        
        if(!is_uploaded_file($imported_file_details["tmp_name"]))
        {
            switch($imported_file_details["error"])
            {
                case UPLOAD_ERR_INI_SIZE:
                    return self::print_error_mess("IMPORT_REPORTS_ERR_INI_SIZE", true);
                case UPLOAD_ERR_FORM_SIZE:
                    return self::print_error_mess("IMPORT_REPORTS_ERR_FORM_SIZE", true);
                case UPLOAD_ERR_PARTIAL:
                    return self::print_error_mess("IMPORT_REPORTS_ERR_PARTIAL", true);
                case UPLOAD_ERR_NO_FILE:
                    return self::print_error_mess("IMPORT_REPORTS_ERR_NO_FILE", true);
                case UPLOAD_ERR_NO_TMP_DIR:
                    return self::print_error_mess("IMPORT_REPORTS_ERR_NO_TMP_DIR", true);
                case UPLOAD_ERR_CANT_WRITE:
                    return self::print_error_mess("IMPORT_REPORTS_ERR_CANT_WRITE", true);
                case UPLOAD_ERR_EXTENSION:
                    return self::print_error_mess("IMPORT_REPORTS_ERR_EXTENSION", true);
                default:
                {
                    $err_message =  $error_prefix . "There was a problem with your upload(" . $imported_file_details["error"] . ").\nPlease try again.";
                    return $err_message;
                }
            }
        }

        if($imported_file_details["size"] == 0)
            return self::print_error_mess("IMPORT_REPORTS_ERR_EMPTY_FILE", true);

        if ($upload_to_local)
        {
            //uploading to Local Netscaler
            require_once(APPPATH . "controllers/common/rapi_utils.php");
            if(($net_sftp = rapi_utils::get_sftp($response)) === false)
            {
                return $response["message"];
            }
    
            $ssl_dir = $this->get_directory_path("/nsconfig/ssl/");
            $sftp_result = $net_sftp->put($ssl_dir . $imported_file_details["name"], $imported_file_details["tmp_name"], NET_SFTP_LOCAL_FILE);
            if($sftp_result === false)
            {
                return self::print_error_mess("IMPORT_REPORTS_ERR_FILE_COPY", true);
            }
        }
        
        if ($upload_to_remote)
        {
            // Uploading to Remote Netscaler
            $remoteip = $_SESSION["remoteip"];
            $remote_ns_username = $_SESSION["remote_ns_username"];
            $remote_ns_password = $_SESSION["remote_ns_password"];
            if(!isset($remoteip, $remote_ns_username, $remote_ns_password))
            {
                $response["errorcode"] = NSERR_NOUSER;
                $response["message"] = NSERR_NOUSER_MESSAGE;
                return $response["message"];
            }
    
            require_once("Net/SFTP.php");
            $remote_net_sftp = new Net_SFTP($remoteip);
            if(!$remote_net_sftp->login($remote_ns_username, $remote_ns_password))
            {
                $response["errorcode"] = NSERR_NOTAUTHORIZED;
                $response["message"] = NSERR_NOTAUTHORIZED_MESSAGE;
                return $response["message"];
            }
    
            $ssl_dir = $this->get_directory_path("/nsconfig/ssl/");
            $remote_sftp_result = $remote_net_sftp->put($ssl_dir . $imported_file_details["name"], $imported_file_details["tmp_name"], NET_SFTP_LOCAL_FILE);
            if($remote_sftp_result === false)
            {
                return self::print_error_mess("IMPORT_REPORTS_ERR_FILE_COPY", true);
            }
        }
        
        return true;
    }
    
    protected function print_error_mess($error_message, $print = true)
    {
        require_once(APPPATH."controllers/common/utils.php");
        $error = utils::get_error_message($error_message);
        return $error;
    }
}
?>
