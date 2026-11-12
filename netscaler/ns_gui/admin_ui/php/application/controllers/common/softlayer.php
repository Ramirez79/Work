<?php if(!defined("BASEPATH")) exit("No direct script access allowed");

class Softlayer  extends abstract_controller
{
	public $port,$host,$content,$forward_path,$content_type,$user_agent,
		$XFF,$request_method,$IMS,$cacheTime,$cookie, $username, $apiaccesskey;
	private $http_code,$lastModified,$version,$resultHeader;
	private $APP_ERROR_CODES = array(200, 201, 400, 401, 403, 404, 405, 406, 409, 500, 501, 503, 599);
	
	private $SOFTLAYER_IP = "66.228.119.116";
	private $SOFTLAYER_PORT = "443";
	private $SOFTLAYER_QUERY_PATH = "/rest/v3/SoftLayer_Account/Hardware.json"; 
	
	function __construct($current_request_type)
	{
		parent::__construct();
		$this->current_request_type = $current_request_type;
		$this->version="PHP Reverse Proxy (PRP) 1.0";
		$this->port=$this->SOFTLAYER_PORT;
		$this->host= $this->SOFTLAYER_IP;
		$this->protocol="https";
		$this->query_path=$this->SOFTLAYER_QUERY_PATH;
		$this->content="";
		$this->response_headers="";
		$this->user_agent="";
		$this->XFF="";
		$this->request_method="GET";
		$this->IMS=false;	// If-Modified-Since
		$this->cacheTime=72000;
		$this->lastModified=gmdate("D, d M Y H:i:s",time()-72000)." GMT";
		$this->cookie="";
		$this->credentials= null;
		$this->username = null;
		$this->apiaccesskey = null;
	}
	
	function translateServer($serverName)
	{
		return $this->protocol."://".$this->credentials.$serverName.":".$this->port.$this->query_path;;
	}
	
	function preConnect()
	{
		//"nseng:430abdf2a0a076a52868917180cf3fd30b5025b0290eff2cd3b6f2c9e06cc6c2@";
	
		//session_start();
		//{"softlayer":{"username":"xxx","apiaccesskey":"yyyy"}}
		
		if(isset($_SESSION["sl_username"]) && ($_SERVER['REQUEST_METHOD'] == "GET"))
		{
			$this->username = $_SESSION["sl_username"]; 
			$this->apiaccesskey = $_SESSION["sl_apiaccesskey"];
		}
		else{
			$data = $this->validate_arguments_for_post(array("object"), array(), false);
			$object = $data["object"];
			$json_obj = json_decode($object);
			
			$softlayer_obj = $json_obj->{'softlayer'};
			$this->username = $softlayer_obj->{'username'};
			$this->apiaccesskey = $softlayer_obj->{'apiaccesskey'};
		}
		
		$this->credentials = $this->username. ":". $this->apiaccesskey."@";
		
		$this->user_agent=$_SERVER['HTTP_USER_AGENT'];
		$this->request_method=$_SERVER['REQUEST_METHOD'];
		$tempCookie="";
		foreach ($_COOKIE as $i => $value) {
			$tempCookie=$tempCookie." $i=$_COOKIE[$i];";
		}
		$this->cookie=$tempCookie;
		if(empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$this->XFF=$_SERVER['REMOTE_ADDR'];
		} else {
			$this->XFF=$_SERVER['HTTP_X_FORWARDED_FOR'].", ".$_SERVER['REMOTE_ADDR'];
		}
	}
	
	function connect()
	{
	    $this->preConnect();
            $output = $this->execute_req($_SERVER['REQUEST_METHOD'], $this->translateServer($this->host));
            //$this->postConnect($this->response_headers, $output);
            //return $this->content;
	    return $output;
	}
    
	private function execute_req($req_type, $url)
	{
		$result_obj = array();
		$http_request = new HttpRequest($url, HttpRequest::METH_GET);
		if($this->request_method=="POST")
		{
		    $http_request->setPostFields($_POST);
		}	
		try
		{
		    $http_message = $http_request->send();
		    $this->response_headers = $http_message->getHeaders();
		    $resp = $http_message->getBody();
		    $resp_code = $http_message->getResponseCode();
	
		    if($this->is_valid_error_code($resp_code))
		    {
			$tmp_resp_array = json_decode($resp, true);
			foreach ($tmp_resp_array as $item)
			{
			   if (isset($item["primaryIpAddress"]))
			   {
			      $json= array("ipaddress" => $item["primaryIpAddress"]);
			      $result_obj[] = $json;
			   }
			}
			
			$result = array('errorcode' => 0, 'message' => 'Done', 'severity' => 'NONE', 'softlayer' => $result_obj);
			$_SESSION["sl_username"] = $this->username;
			$_SESSION["sl_apiaccesskey"] = $this->apiaccesskey;
		    }
		    else{
			$result = array('errorcode' => -1, 'message' => 'JSON Parsing Error', 'severity' => 'ERROR');
		    }
		    return $result;
		}
		catch (HttpException $ex)
		{
			$err_message = $ex->getMessage();
			if (isset($ex->innerException)){
				$err_message = $ex->innerException->getMessage();
			}
		    return array('errorcode' => -1, 'message' => $err_message, 'severity' => 'ERROR');
		}
	}
        
	function postConnect($info,$output)
	{
		$this->content_type=$info['Content-Type'];
		if(!empty($info['last-modified'])){
			$this->lastModified=$info['last-modified'];
		}
		if (!empty($info['header_size'])){
		   $this->resultHeader=substr($output,0,$info['header-size']);
		}
		else
		    $this->resultHeader="";
		    
		$this->content=$output;//substr($output,0,$info['Content-Length']);
		$this->output();
	}
	
	function output()
	{
		$currentTimeString=gmdate("D, d M Y H:i:s",time());
		$expiredTime=gmdate("D, d M Y H:i:s",(time()+$this->cacheTime));
		if($this->IMS){
			header("HTTP/1.1 304 Not Modified");
			header("Date: Wed,". $currentTimeString . "GMT");
			header("Last-Modified:". $this->lastModified);
			header("Server:". $this->version);
		}
		else
		{
			header("HTTP/1.1 200 OK");
			header("Date: Wed," . $currentTimeString . "GMT");
			header("Content-type:" . $this->content_type);
			header("Last-Modified:" . $this->lastModified);
			header("Cache-Control:" . $this->cacheTime);
			header("Expires:" . $expiredTime . "GMT");
			header("Server:" . $this->version);
			preg_match("/Set-Cookie:[^\n]*/i",$this->resultHeader,$result);
			foreach($result as $i=>$value){
				header($result[$i]);
			}
			print json_encode($this->content);
		}
	}
    
	function is_valid_error_code($error_code)
	{
	   return  in_array($error_code, $this->APP_ERROR_CODES);
	}    
}
?>
