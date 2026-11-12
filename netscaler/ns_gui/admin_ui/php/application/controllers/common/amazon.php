<?php if(!defined("BASEPATH")) exit("No direct script access allowed");

class amazon extends abstract_controller
{
    public $port,$host,$content,$forward_path,$content_type,$user_agent,
        $XFF,$request_method,$IMS,$cacheTime,$cookie,$resultHeader, $result;
    private $http_code,$lastModified,$version;
        private $APP_ERROR_CODES = array(200, 201, 400, 401, 403, 404, 405, 406, 409, 500, 501, 503, 599);
       const DATE_FORMAT_ISO8601 = 'Y-m-d\TH:i:s\Z';
    private $AMAZON_IP = "205.251.235.185";
    
    function __construct($current_request_type)
        {
        parent::__construct();
        $this->current_request_type = $current_request_type;
        $this->server="PHP Reverse Proxy (PRP) 1.0";
        $this->port="";
        $this->host=$this->AMAZON_IP;
        $this->host_dns = "ec2.amazonaws.com";
        $this->protocol="https";
        $this->response_headers="";
        $this->user_agent="";
        $this->XFF="";
        $this->request_method="GET";
        $this->IMS=false;    // If-Modified-Since
        $this->cacheTime=72000;
        $this->lastModified=gmdate("D, d M Y H:i:s",time()-72000)." GMT";
        $this->cookie="";
        $this->accesskeyid="";
        $this->secretaccesskey="";
        $this->url = "";
    }
    
    function getAmazonQueryURL($query_string)
    {
        return ($this->protocol."://".$this->url."/?".$query_string);
    }
    
    public function to_query_string($array)
    {
        $temp = array();

        foreach ($array as $key => $value)
        {
            if (is_string($key) && !is_array($value))
            {
                $temp[] = rawurlencode($key) . '=' . rawurlencode($value);
            }
        }
        return implode('&', $temp);
    }

    public function to_signable_string($array)
    {
        $t = array();

        foreach ($array as $k => $v){
            $t[] = $k.$v;
        }
        return implode('', $t);
    }

    public function hmac_sha1_base64($str, $key, $warn_nonasciikey = true)
    {
        $key_lenght = strlen($key);
          if ($key_lenght > 64) {
            $key        = sha1($key);
            $key_length = 40;
        }
        //pad secret with 0x0 to get a 64 byte secret
        if ($key_lenght < 64) {
           $secret = $key.str_repeat(chr(0), (64 - $key_lenght));
        }
        else {
             $secret = $key;
        }
    //hash and return it
    return base64_encode(sha1(($secret^str_repeat(chr(0x5c), 64)).sha1(($secret^str_repeat(chr(0x36), 64)).$str, true), true));
    }
    
    function preConnect()
    {
        //session_start();
        //{"amazon":{"aws_access_key_id":"xxx","aws_secret_access_key":"yyyy"}}

        if(isset($_SESSION["aws_access_key_id"]) && ($_SERVER['REQUEST_METHOD'] == "GET"))
        {
            $this->accesskeyid = $_SESSION["aws_access_key_id"];
            $this->secretaccesskey = $_SESSION["aws_secret_access_key"];
            $this->url = $_SESSION["aws_region"];
        }
        else
        {
            $data = $this->validate_arguments_for_post(array("object"), array(), false);
            $object = $data["object"];
            $json_obj = json_decode($object);
            $amazon_obj = $json_obj->{'amazon'};
        
            $this->accesskeyid = $amazon_obj->{'aws_access_key_id'};
            $this->secretaccesskey = $amazon_obj->{'aws_secret_access_key'};
            $this->url = $amazon_obj->{'aws_region'};
        }

        $this->user_agent=$_SERVER['HTTP_USER_AGENT'];
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
         $output = $this->execute_req();
         if(!empty($this->response_headers['last-modified']))
            $this->lastModified=$info['last-modified'];

         $this->output();
         return $output;
    }
    
    function parse_error_response($vals)
    {
        foreach ($vals as $xml_elem)
        {
           if ($xml_elem["tag"] == "MESSAGE")
           {
              $this->result = array('errorcode' => -1, 'message' => $xml_elem["value"], 'severity' => 'ERROR');
              return $this->result;
           }
           else
              continue;
        }
    }

    function query_and_get_response($http_request)
    {
        $http_request->setHeaders(
            Array(
              "X-Forwarded-For => ".$this->XFF,
              "User-Agent => ".$this->user_agent
            )
        );

        try
        {
            $http_message = $http_request->send();
            $resp = $http_message->getBody();
            $resp_code = $http_message->getResponseCode();
        }
        catch (HttpException $ex)
        {
            $this->result = array('errorcode' => -1, 'message' => 'Not able to connect to domain: https://ec2.amazonaws.com', 'severity' => 'ERROR');
            return $this->result;
        }
        
        try
        {
            $this->response_headers = $http_message->getHeaders();
            if (isset($this->response_headers["Content-Type"]))
               $this->content_type =  $this->response_headers["Content-Type"];
            $this->server = $this->response_headers["Server"];
        }
        catch (HttpException $ex)
        {
            $this->result = array('errorcode' => -1, 'message' => 'Invalid response headers', 'severity' => 'ERROR');
            return $this->result;
        }
        
        if($this->is_valid_error_code($resp_code))
        {
            try
            {
               $xml_parser = xml_parser_create();
               xml_parse_into_struct($xml_parser, $resp, $vals);
               xml_parser_free($xml_parser);
            }
            catch (HttpException $ex)
            {
               $this->result = array('errorcode' => -1, 'message' => 'PHP XML Parser Error: Invalid response', 'severity' => 'ERROR');
               return $this->result;
            }
            $this->result = array('errorcode' => 0, 'message' => 'Done', 'severity' => 'NONE', 'xml_parsed_data' => $vals);    
            return $this->result;
         }
         else
         {
            $this->result = array('errorcode' => -1, 'message' => 'Invalid AWS Response code', 'severity' => 'ERROR');    
            return $this->result;         
         }
    }
    
    
    private function execute_req_to_get_eips($snips)
    {
        $current_time = time();
        $timestamp = gmdate(amazon::DATE_FORMAT_ISO8601, $current_time);
        $query = array();

        $query['Action'] = "DescribeInstances";
        $query['AWSAccessKeyId'] = $this->accesskeyid;
        
        $query['SignatureVersion'] = '1';
        $query['Timestamp'] = $timestamp;
        $query['Version'] = "2010-08-31";
        
        $string_to_sign = $this->to_signable_string($query);
        $query['Signature'] = $this->hmac_sha1_base64(utf8_encode($string_to_sign), utf8_encode($this->secretaccesskey), true);
        $querystring = $this->to_query_string($query);
        $url = $this->getAmazonQueryURL($querystring);
        $http_request_describe_AMIs = new HttpRequest($url, HttpRequest::METH_GET);
        $func_response = $this->query_and_get_response($http_request_describe_AMIs);

        $prev_xml_elem_value = "";
        $result_obj = array();
        
        try
        {
            if ($func_response["errorcode"]!=0)
                return $func_response;
            
            $private_ip_address = "";
            $public_ip = "";
            foreach ($func_response["xml_parsed_data"] as $xml_elem)
            {
               if ($xml_elem["tag"]=="ERRORS" || $xml_elem["tag"]=="ERROR")
                  return $this->parse_error_response($func_response["xml_parsed_data"]);
                
                if ($xml_elem["tag"] == "privateipaddress" || $xml_elem["tag"] == "PRIVATEIPADDRESS" || $xml_elem["tag"] == "privateIpAddress")
               {
                    $private_ip_address = $xml_elem["value"];
               }
                else  if ($xml_elem["tag"] == "ipAddress" || $xml_elem["tag"] == "ipaddress" || $xml_elem["tag"] == "IPADDRESS")
               {
                    $json = array ("elastic_ipaddress" => $xml_elem["value"], "private_ip" => $private_ip_address);
                    $result_obj[] = $json;
               }
               
            }
            
            $final_result = array();
            for ($i = 0; $i < sizeof($snips); $i++)
            {
                for ($j = 0; $j < sizeof($result_obj); $j++)
                {
                    if ($result_obj[$j]["private_ip"] == $snips[$i])
                    {
                        $final_result[] = $result_obj[$j];
                    }
                    else
                    {
                        $json = array ("elastic_ipaddress" => $snips[$i], "private_ip" => $snips[$i]);
                        $final_result[] = $json;
                    }
                }
            }    
                  
            $this->result = array('errorcode' => 0, 'message' => 'Done', 'severity' => 'NONE', 'remote_aws_eips' => $final_result);
            $_SESSION["aws_access_key_id"] = $this->accesskeyid;
            $_SESSION["aws_secret_access_key"] = $this->secretaccesskey;
            $_SESSION["aws_region"] = $this->url;
            return $this->result;
        }
        catch (HttpException $ex)
        {
            $this->result = array('errorcode' => -1, 'message' => 'Error parsing XML response from AWS', 'severity' => 'ERROR');
            return $this->result;
        }
    }
    
    function get_elastic_ips($snips)
    {
       $this->preConnect();
       $output = $this->execute_req_to_get_eips($snips);
         if(!empty($this->response_headers['last-modified']))
            $this->lastModified=$info['last-modified'];

         $this->output();
         return $output;
    }
    
    private function execute_req()
    {
        $result_obj3 = array();            
        $result_obj2 = array();    
        $result_obj = array();
        
        // Determine signing values
        $current_time = time();
        $timestamp = gmdate(amazon::DATE_FORMAT_ISO8601, $current_time);
        $query = array();

        $query['Action'] = "DescribeInstances";
        $query['AWSAccessKeyId'] = $this->accesskeyid;
        $query['SignatureVersion'] = "1";
        $query['Timestamp'] = $timestamp;
        $query['Version'] = "2009-08-15";

        $string_to_sign = $this->to_signable_string($query);
        $query['Signature'] = $this->hmac_sha1_base64(utf8_encode($string_to_sign), utf8_encode($this->secretaccesskey), true);
        $querystring = $this->to_query_string($query);
        $url = $this->getAmazonQueryURL($querystring);

        $http_request = new HttpRequest($url, HttpRequest::METH_GET);
        if($this->request_method=="POST"){
            $http_request->setPostFields($_POST);
        }    
        
        $func_response = $this->query_and_get_response($http_request);
      
        try
        {
            if ($func_response["errorcode"]!=0)
                return $func_response;
                      
            //Here RESERVATIONSET is the main XML tag and is repeated when parsing each <item> under it;
            //hence can be used to signify the start of parsing of a new <item> that is an AWS instance.
            $var_bool="true";
            $check_for_state = "false";           
            $imageid = ""; 

            foreach ($func_response["xml_parsed_data"] as $xml_elem)
            {
               if ($xml_elem["tag"]=="ERRORS" || $xml_elem["tag"]=="ERROR")
                  return $this->parse_error_response($func_response["xml_parsed_data"]);
               
               if ($xml_elem["tag"]=="RESERVATIONSET")
               {
                  $var_bool="true";
                  $check_for_state = "true";                              
               }
               else if ($var_bool=="false")
                  continue;
                 
               if ($xml_elem["tag"]=="IMAGEID")
                   $imageid = $xml_elem["value"];
               else if ($xml_elem["tag"] == "IPADDRESS")
               {
                    $json= array("imageid" => $imageid, "ipaddress" => $xml_elem["value"]);
                    $result_obj[] = $json;
                    $var_bool = "false";
               }
             else if ($xml_elem["tag"]=="NAME" && $check_for_state=="true")
              {
                if (strcasecmp($xml_elem["value"], "RUNNING") != 0)
                    $var_bool="false";
                $check_for_state = "false";
              }
            }

            $query2["Action"] = "DescribeImages";             
            $query2['AWSAccessKeyId'] = $this->accesskeyid;
            $m = 1;    
            foreach ($result_obj as $xml_elem)
            {
                $query2["ImageId.".$m]=$xml_elem["imageid"];
                $m = $m+1;
            }
                    
            foreach($query as $key=>$row) {
                if ($key=="Action" || $key=="AWSAccessKeyId" || $key=="Signature")
                continue;
                
                $query2[$key] = $row;
            }                                              
            $string_to_sign2 = $this->to_signable_string($query2);
            $query2['Signature'] = $this->hmac_sha1_base64(utf8_encode($string_to_sign2), utf8_encode($this->secretaccesskey), true);
            $querystring2 = $this->to_query_string($query2);
            $url2 = $this->getAmazonQueryURL($querystring2);

            $http_request_describe_AMIs = new HttpRequest($url2, HttpRequest::METH_GET);
            $func_response2 = $this->query_and_get_response($http_request_describe_AMIs);

            $prev_xml_elem_value = "";
            if ($func_response["errorcode"]!=0)
                return $func_response;
                                            
            foreach ($func_response2["xml_parsed_data"] as $xml_elem)
                {
                   if ($xml_elem["tag"]=="ERRORS" || $xml_elem["tag"]=="ERROR")
                      return $this->parse_error_response($func_response2["xml_parsed_data"]);

                   if (($xml_elem["tag"] == "IMAGELOCATION" && strpos($xml_elem["value"], 'NetScaler') !== false && strpos($xml_elem["value"], 'CloudConnector') !== false) || ($xml_elem["tag"] == "IMAGELOCATION" && strpos($xml_elem["value"], 'NetScaler') !== false && strpos($xml_elem["value"], 'CloudBridge Connector') !== false))
                        array_push($result_obj2, $prev_xml_elem_value);

                   if ($xml_elem["tag"] == "IMAGEID")
                        $prev_xml_elem_value = $xml_elem["value"];
                }
            
            foreach ($result_obj as $result_data)
            {                     
                if (in_array($result_data["imageid"], $result_obj2))
                {
                    $json = array ("ipaddress" => $result_data["ipaddress"]);
                    $result_obj3[] = $json;
                }
            }
                  
            $this->result = array('errorcode' => 0, 'message' => 'Done', 'severity' => 'NONE', 'amazon' => $result_obj3);
            $_SESSION["aws_access_key_id"] = $this->accesskeyid;
            $_SESSION["aws_secret_access_key"] = $this->secretaccesskey;
            $_SESSION["aws_region"] = $this->url;
          
            return $this->result;
        }
        catch (HttpException $ex)
        {
            $this->result = array('errorcode' => -1, 'message' => 'Error parsing XML response from AWS', 'severity' => 'ERROR');
            return $this->result;
        }
    }
    
    function output()
    {
        $currentTimeString=gmdate("D, d M Y H:i:s",time());
        $expiredTime=gmdate("D, d M Y H:i:s",(time()+$this->cacheTime));
        if($this->IMS){
            header("HTTP/1.1 304 Not Modified");
            header("Date: Wed,". $currentTimeString . "GMT");
            header("Last-Modified:". $this->lastModified);
            header("Server:". $this->server);
        }
        else
        {
            header("HTTP/1.1 200 OK");
            header("Date: Wed," . $currentTimeString . "GMT");
            header("Content-type:" . $this->content_type);
            header("Last-Modified:" . $this->lastModified);
            header("Cache-Control:" . $this->cacheTime);
            header("Expires:" . $expiredTime . "GMT");
            header("Server:" . $this->server);
            //preg_match("/Set-Cookie:[^\n]*/i",$this->resultHeader,$this->result);
            //foreach($this->result as $i=>$value){
            //    header($this->result[$i]);
            //}
        }
    }
    
     function is_valid_error_code($error_code)
     {
        return  in_array($error_code, $this->APP_ERROR_CODES);
     }    
}
?>
