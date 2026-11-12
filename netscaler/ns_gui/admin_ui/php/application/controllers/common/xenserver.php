<?php if(!defined("BASEPATH")) exit("No direct script access allowed");

require_once(BASEPATH . "/libraries/xmlrpc.inc");

class xenserver extends abstract_controller
{
    public function __construct($current_request_type)
    {
        parent::__construct();
        $this->current_request_type = $current_request_type;
    }
    
    
    public function login()
    {
		$data = $this->validate_arguments_for_post(array("object"), array(), false);
		
		//{"xenserver":{"ip_address":"10.102.126.11","username":"root","password":"freebsd"}}
		$object = $data["object"];
		$json_obj = json_decode($object, true);
		$xenserver_obj = $json_obj['xenserver'];
		$ip_address = $xenserver_obj['ip_address'];
		$username = $xenserver_obj['username']; 
		$password = $xenserver_obj['password'];
	
		try 
		{
		    $rpcmsg =new xmlrpcmsg('session.login_with_password',
				array(php_xmlrpc_encode($username), php_xmlrpc_encode($password))
				);
		    $session_value;
		    $client = new xmlrpc_client("/", $ip_address, 80);
		    $result=&$client->send($rpcmsg);
		
			if(!($result->faultCode()))
			{
				$result_value = $result->value();
				$session_value_present = $result_value->structmemexists('Value');
				if($session_value_present === true)
				{
				    $session_value = $result_value->structmem('Value');
				    $session_value = htmlspecialchars($session_value->scalarval());
				}
			}
			else
			{
			    // The XMLRPC server returns a XMLRPC error
			    $retval = array("errorcode" => htmlspecialchars($result->faultCode()), "message" => "Cannot connect. Please provide valid XenServer details.", "severity" => "ERROR");
		        return $retval;
			}
		    //session_start();
		    
		    if(! isset($session_value))
		    {
		        $retval = array("errorcode" => -1, "message" => "Invalid credentials", "severity" => "ERROR");
			    return $retval;
		    }
		    
		    $_SESSION["XENAPI_SESSION"] = $session_value;
		    $_SESSION["XENAPI_IPADDR"] = $ip_address;

		    $retval = array("errorcode" => 0, "message" => "Login successful", "severity" => "NONE");
		    return $retval;
		}
		catch (Exception $e)
		{
		    // Other errors (HTTP or networking problems...)
	     	$retval = array("errorcode" => -1, "message" => "Unknown Error", "severity" => "ERROR");
	     	return $retval;
		}
    }
    
    private function api()
    {
	try
	{	
	    //session_start();
	    if(! isset($_SESSION["XENAPI_SESSION"]))
	    {
		return $this->get_error_result_object("Not Logged In", -1);
	    }
	    $ip_address = $_SESSION["XENAPI_IPADDR"];
	    $session_value = $_SESSION["XENAPI_SESSION"];
	    $arg_list = func_get_args();
	
	    $operation = urldecode($arg_list[0]);
	    $arg_list[0] = $session_value;
	    
	    $args_list = array();
	    $result;
	    reset($arg_list);
	    foreach($arg_list as $key => $val) {  $args_list[]=php_xmlrpc_encode($val);}
	    $rpcmsg =new xmlrpcmsg($operation,$args_list);
	    $client = new xmlrpc_client("/", $ip_address, 80);
	    $result2=&$client->send($rpcmsg);
		if(!($result2->faultCode()))
		{
			$result = $result2->value();
			$result = $result->structmem('Value');
			//$result = htmlspecialchars($result->scalarval());
		}
		else
		{

		    // The XMLRPC server returns a XMLRPC error
		    $retval = array("errorcode" => htmlspecialchars($result->faultCode()), "message" => htmlspecialchars($result->faultString()), "severity" => "ERROR");
		    //$this->print_result($retval);
	             return $retval;
		}

	    return $result;
	    
	
	} catch (Exception $e) {
	
           $retval = array("errorcode" => -1, "message" => $e->getMessage(), "severity" => "ERROR");
     	    //$this->print_result($retval);
             return $retval;
	}
    }
    
    public function custom_api()
    {
	    $args_list = func_get_args();
	    $custom_api_name = $args_list[0];
	    $result = null;
	    switch($custom_api_name)
	    {
		case 'get_all_running_netscalers':
		{
			$args_list[0] = 'VM.get_all_records';
			$vms_list = call_user_func_array(array($this, 'api'), $args_list);
			if(!($vms_list instanceof xmlrpcval))
			{
			    return $vms_list;
			}
			
			$args_list[0] = 'VM_guest_metrics.get_all_records';
			$vm_guest_metrics_list = call_user_func_array(array($this, 'api'), $args_list);
			if(!($vm_guest_metrics_list instanceof xmlrpcval))
			{
			    return $vm_guest_metrics_list;
			}
			
			$running_ns_list = array();
			$vms_list->structreset();
			$vm_guest_metrics_list->structreset();
			$vm_guest_metric_ipaddr_map = array();
			
			while(list($vm_guest_metric, $vm_guest_metric_info) = $vm_guest_metrics_list->structeach())
			{
			    $vm_nw_info = $vm_guest_metric_info->structmem('networks');
			    $vm_os_info = $vm_guest_metric_info->structmem('os_version');
			    if($vm_nw_info instanceof xmlrpcval && $vm_os_info instanceof xmlrpcval)
			    {
				$vm_ip_exists = $vm_nw_info->structmemexists('0/ip');
				$vm_os_name_exists = $vm_os_info->structmemexists('uname');
				if($vm_ip_exists === true && $vm_os_name_exists === true)
				{
				    $ip_address = $vm_nw_info->structmem('0/ip');
				    $os_name = $vm_os_info->structmem('uname');
				    if(strripos($os_name->scalarval(), 'NetScaler') !==false)
				    {
					$vm_guest_metric_ipaddr_map[$vm_guest_metric] = $ip_address->scalarval();
				    }
				}
			    }
			}
			while( list($vm, $vm_info) = $vms_list->structeach())
			{
				$vm_type = $vm_info->structmem('name_description');
				$pos = strripos($vm_type->scalarval(), 'NetScaler');
				$power_state = $vm_info->structmem('power_state');
				
				if( ( $power_state->scalarval() == "Running"))// && ($pos !== false))
				{
				    $guest_metric_data_api_args = array('VM.get_guest_metrics', $vm);
				    $guest_metric_data = call_user_func_array(array($this, 'api'), $guest_metric_data_api_args);
				    if($guest_metric_data instanceof xmlrpcval){
					if(isset($vm_guest_metric_ipaddr_map[$guest_metric_data->scalarval()]))
					{
					$ip_address = $vm_guest_metric_ipaddr_map[$guest_metric_data->scalarval()];
					$ipaddress = array( 'ipaddress' => $ip_address);
					$running_ns_list[] = $ipaddress;
					}
				    }
				}
			}
			$result = array( 'errorcode' => 0, "message" => "Get All Running NetScalers successful", 'get_all_running_netscalers' => $running_ns_list);
			break;
		}
		case 'get_all_ns_templates':
		{
			$args_list[0] = 'VM.get_all_records';
			$vms_list = call_user_func_array(array($this, 'api'), $args_list);
			if($vms_list == false)
			{
			    return;
			}

			$ns_templates_list = array();
			$count = 0;
			$vms_list->structreset();
			while( list($vm, $vm_info) = $vms_list->structeach())
			{
				$vm_type = $vm_info->structmem('name_description');
				$pos = strripos($vm_type->scalarval(), 'NetScaler');
				$is_a_template = $vm_info->structmem('is_a_template');
				if( ($is_a_template->scalarval() == 1) && ($pos !== false))
				{
					$name_label = $vm_info->structmem('name_label');
					$uuid = $vm_info->structmem('uuid');
					$ns_templates_list[] = array("OpaqueRef" => $vm,
								     "UUID" => $uuid->scalarval(),
								     "name_label" => $name_label->scalarval()
								     );
				}
				$count ++;
			}
			$result = array( 'get_all_ns_templates' => $ns_templates_list);
			break;
		}
	    }
            return $result;
    }
    
    //arg_list could be a mix of paths & query strings (/config/lbvserver/v1?rawdata=yes&pageno=2&pagesize=500&view=detail)
    //removes query parameters from the last URI segment and returns all query parameters as key-value pairs
    private function parse_query_string(&$arg_list)
    {
        $query_params = array();
        $error = false;
        if(($count = count($arg_list)) <= 0)
            return $query_params;

        $last_segment = $arg_list[$count - 1];
        if(strpos($last_segment, "?") === false)
            return $query_params;

        $last_segment_arr = explode("?", $last_segment, 2);
        $arg_list[$count - 1] = urldecode($last_segment_arr[0]);
        if(($query_params = $this->tokenize_string_to_key_value_arr($last_segment_arr[1], '&', '=', array("args", "filter"))) === false)
        {
	    header("HTTP/1.0 599 Netscaler specific error");
            $this->print_error_message("Invalid query parameters");
            return false;
        }
	return $query_params;
    }
    
    //Given a string like "name1:value1,name2:value2", returns an array of key-value pairs (name1 => value1 etc)
    //If input string is invalid, returns false.
    private function tokenize_string_to_key_value_arr($query_params_str, $first_level_tokenizer = ',', $second_level_tokenizer = ':', $no_decode_array = array())
    {
        $key_value_arr = array();
        $query_params_arr = explode($first_level_tokenizer, $query_params_str);
        $count = count($query_params_arr);
        for($i = 0; $i < $count; $i++)
        {
            if(strpos($query_params_arr[$i], $second_level_tokenizer) === false)
                return false;
            list($key, $value) = explode($second_level_tokenizer, $query_params_arr[$i], 2);
            if($key == "" || $value == "")
                return false;
			$val = in_array($key, $no_decode_array) ? $value : urldecode($value);
			$values = (strchr($val, ';') != NULL && !in_array($key, $no_decode_array)) ? explode(";", $val) : $val;
			$key_value_arr[urldecode($key)] = $values;
		}
		return $key_value_arr;
    }
    
    private function get_error_result_object($error_message, $error_code)
    {
        return array("errorcode" => $error_code, "message" => $error_message, "severity" => "ERROR");
    }

}
?>
