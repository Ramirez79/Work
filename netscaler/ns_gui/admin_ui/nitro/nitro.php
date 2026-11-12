<?php
if(defined("BASEPATH"))
    return;

define("MISMATCH_OBJECTNAME_ERROR","Content type doesn't match the resource name");
define("MISSING_OBJECTNAME_ERROR", "Missing objectname");

// HTTP error codes mapping
$badrequest = array(1088 => 400, // No such command
                    1090 => 400, // No such argument
                    1074 => 400, // Invalid value
                    1075 => 400, //  Invalid name; names must begin with an alphanumeric character or underscore and must contain only              alphanumerics, '_', '#', '\.', ' ', ':', '@', '=' or '-'
                    1091 => 400, // Required argument value missing
                    1092 => 400, // Arguments cannot both be specified
                    1093 => 400, // Argument pre-requisite missing
                    1094 => 400, // Too few arguments
                    1095 => 400, // Required argument missing
                    1096 => 400, // Argument(s) out of order
                    1097 => 400, // Invalid argument value
                    1098 => 400, // Arguments cannot have the same value
                    1099 => 400, // Ambiguous argument name
                    1104 => 400, // Arguments must have the same value
                    1105 => 400, // Ambiguous argument value
                    1112 => 400, // Argument has too many values.
                    1128 => 400, // Incomplete command
                    1232 => 400, // Invalid object name
                    1233 => 400, // Invalid JSON input
                    1234 => 400, // Invalid JSON data type
                    1235 => 400, // Invalid XML input
                    1236 => 400, // Invalid NITRO data type
                    1252 => 400); // Invalid Application input

$unauthorized = array(1025 => 401, // Login failed
                    1026 => 401, // Not logged in
                    1027 => 401, // Not logged in or connection timed out
                    354 => 401, // Invalid username or password
                    355 => 401, // Invalid username or password
                    336 => 401, // Authentication error
                    337 => 401, // Need authenticator
                    352 => 401, // Login is required
                    635 => 401, // Authentication required
                    62 => 401, // Authentication failed.
                    444 => 401, // Session expired or killed. Please login again
                    451 => 401, // Invalid session. Please login again
                    471 => 401, // Session expired, Please login again
	
					317 => 401); // Connection refused.
$forbidden = array(2656 => 403, // Unauthorized
                    2138 => 403);  // Not authorized to execute this command

$notfound = array(258 => 404, // No such resource
                344 => 404, // No Service
                351 => 404, // No such server
                3847 => 404, // HTML injection variable does not exist
                461 => 404, // No such Vserver
                462 => 404, // No such service
                463 => 404, // No such service/serviceGroup
                472 => 404, // Vlan does not exist
                674 => 404, // No such selector
                710 => 404, // No backend LB vserver found.
                372 => 404, // System group does not exist
                373 => 404, // System command policy does not exist
                397 => 404, // IP does not exist
                430 => 404, // No such backup vserver
                538 => 404, // Action does not exist
                678 => 404, // Content group does not exist
                864 => 404, // ACL rule does not exist
                1259 => 404, // Application does not exist
                1302 => 404, // Service unavailable
                1550 => 404, // CRL does not exist
                1568 => 404, // cacert does not exists
                1575 => 404, // No such FIPS key
                1592 => 404, // No such responder certificate.
                1593 => 404, // No such signing certificate.
                3621 => 404, // No such cipher/cipherAlias/cipherGroup
                1621 => 404, // No such certificate request file exists
                1625 => 404, // No such CA certificate file exists
                1626 => 404, // No such serial file exists
                1627 => 404, // No such PKCS12 file exists
                1631 => 404, // No such certificate file exists
                1632 => 404, // No such key file exists
                1651 => 404, // No such FIPS key
                1794 => 404, // No such DNS vserver exists
                1796 => 404, // GSLB site not found
                1829 => 404, // The qualifier does not exist in the repository
                1833 => 404, // The GSLB site does not exist
                1835 => 404, // The GSLB service does not exist
                1837 => 404, // The GSLB vserver does not exist
                1839 => 404, // The domain does not exist
                1940 => 404, // The given selector does not exist
                1941 => 404, // The given identifier does not exist
                2054 => 404, // No such policy exists
                2061 => 404, // No such intranet application exists
                2171 => 404, // Monitor does not exist
                2174 => 404, // Metric table does not exist
                2178 => 404, // Metric does not exist
                2344 => 404, // Route does not exist
                2370 => 404, // LB group does not exist
                2565 => 404, // vserver does not exist
                2566 => 404, // Name server does not exist
                2626 => 404, // User does not exist
                2627 => 404, // Group does not exist
                2642 => 404, // Group does not exist
                2658 => 404, // Farm does not exist
                2663 => 404, // Site does not exist
                2822 => 404, // Pattern does not exist in patset
                2823 => 404, // Patset does not exist
                2856 => 404, // String map does not exist
                3087 => 404, // Policy Label does not exist
                3906 => 404, // Resource of the given name does not exist
                3187 => 404, // Imported file does not exist [Please import the file before use]. See /var/log/ns.log for more details.
                3190 => 404, // No such profile.
                3201 => 404, // No such resource. Object your are trying to update or remove does not exist.
                2348 => 404, // Profile does not exist
                3331 => 404, // No such interface
                3840 => 404, // Prebody file not found
                3843 => 404, // Postbody file not found
                3938 => 404, // No matching collector
                3942 => 404, // No such AppFlow action exists.
                3955 => 404, // IP set does not exist
                3956 => 404, // Netprofile does not exist
                2651 => 401); // No Authentication Host specified


$method_not_allowed = array(1237 => 405, // Invalid method name. It should be either post, put, get, stat or delete
                            1240 => 405); // Invalid nitro action or operation

$conflict = array(304 => 409,   //Address already in use
                315 => 409,   // Resource in use
                424 => 409,  // Channel in use by LACP
                528 => 409,  // Action name is already in use
                532 => 409,  // Policy name is already in use
                540 => 409,  // Caching policy name is already in use
                562 => 409,  // Content group name is already in use
                641 => 409,  // Map entry is already in use
                643 => 409,  // Map table is in use
                1254 => 409, // Public endpoint in use
				
				1263 => 409, // Service group with this name already exists
                1364 => 409, // Policy name already in use
                2830 => 409, // Pattern index already in use, try using other index
                2961 => 409, // Profile name is already in use
                2963 => 409, // Action name is already in use
                3094 => 409, // Policy name already in use
                3121 => 409, // The StartURL check is already in use
                3123 => 409, // The DenyURL check is already in use
                3125 => 409, // The CookieConsistency check is already in use
                3127 => 409, // The FieldConsistency check is already in use
                129 => 409,  //    The CrossSiteScripting check is already in use
                3131 => 409, // The SQLInjection check is already in use
                3133 => 409, // The FieldFormat check is already in use
                3135 => 409, // The SafeObject check is already in use
                3137 => 409, //Profile name is already in use
                3171 => 409, // The confidential field is already in use.
                3173 => 409, // The XML DoS URL check is already in use.
                3183 => 409, // The XML WS-I URL check is already in use.
                3193 => 409, // The XML MSGVAL URL check is already in use.
                3218 => 409, // The XML Attachment URL check is already in use.
                3229 => 409, // The CrossSiteRequestForgery check is already in use
                3260 => 409, // The XMLXSS check is already in use
                3262 => 409, // The XMLSQLInjection check is already in use
                3249 => 409, // Profile in use
                3329 => 409, // Interface already bound
                3941 => 409, // AppFlow collector name already in use.
                273 => 409, //    Resource already exists
                427 => 409, // Domain name for the reverse domain name already exists
                429 => 409, // IP, port conflict with an entity already bound to the vserver
                433 => 409, // Server name already exists
                441 => 409, // Operation not permitted. (An overlapping rule already exists)
                442 => 409, // Operation not permitted. (A rule which is subset of this rule already exists)
                453 => 409, // User already bound to system group
                454 => 409, // Policy already bound to system group
                468 => 409, //Operation not permitted. (An overlapping rule already exists)
                469 => 409, // Operation not permitted. (A rule which is subset of this rule already exists)
                599 => 409, // Forward proxy is already present
                664 => 409, // Bind entry already exists
                665 => 409, //    Default already exists
                672 => 409, //    Selector already exists
                773 => 409, // RNAT to the target network with specified NAT IP already exists.
                774 => 409, //    RNAT to the target network already exists.
                869 => 409, // ACL with identical parameter specification already exists
                872 => 409, // ACL with this priority already exists
                880 => 409, // PBR6 with identical parameter specification already exists
                883 => 409, // PBR6 with this priority already exists
                896 => 409, // ACL6 with identical parameter specification already exists
                899 => 409, // ACL6 with this priority already exists
                915 => 409, // PBR with identical parameter specification already exists
                916 => 409, // PBR with this priority already exists
                930 => 409, // This acl is already configured
                1248 => 409, // Template already exists
                1318 => 409, // This vserver is already bound to a WLM
                1321 => 409, // WLM already configured on this IP, port
                1335 => 409, // Server already exists
                1346 => 409, // A push vserver is already bound. Unbind before binding new vserver
                1351 => 409, // Error in binding listen policy to vserver(normal vserver already present)
                1353 => 409, // Error in binding listen policy to vserver(vserver with new priority already present)
                1355 => 409, // The vserver already has None Listen Policy
                1642 => 409, // Cannot create output file. File already exists
                1653 => 409, // Certificate serial number match with another certificate already revoked in the CRL
                1813 => 409, // A GSLB service with the same IP address is already bound to the GSLB vserver
                1834 => 409, // The local site already exists
                1842 => 409, // The domain is already bound to a GSLB vserver
                1852 => 409, // The persistence ID is already being used by a GSLB vserver
                1914 => 409, // GSLB Site IP already exists
                1982 => 409, // Zone already configured.
                1989 => 409, // The DNSKEY is already added for this zone
                2133 => 409, // The monitor is already bound to the service
                2371 => 409, // Vserver is already bound to a LB group
                2561 => 409, // Name server already exists.
                2631 => 409, // User already exists
                2632 => 409, //    Group already exists
                2633 => 409, // User is already bound to the group
                2662 => 409, // Site already exists
                2854 => 409, // Advanced expression entity with same name already exists.
                2964 => 409, // An object with this priority already exists
                3041 => 409, // A policy is already bound to the specified priority
                3865 => 409, // Cannot bind an advanced policy to a virtual server to which a classic policy is already bound
                3872 => 409, // Cannot bind a classic policy to a virtual server to which an advanced policy is already bound
                3198 => 409, // Object already exists
                3846 => 409, // HTML injection variable already exists
                3923 => 409, // Vlan already bound to some netbridge
                3925 => 409, // Tunnel already bound to this netbridge
                3926 => 409, // Subnet already bound to this netbridge
                3954 => 409, // IP set is already bound to the network profile
                440 => 409, // An ACL with the same name exists
                467 => 409, // Simple ACL6 with the same name exists
                825 => 409, // VIP exists for this host route
                928 => 409, // A forwarding session with same name exists
                929 => 409, // A forwarding session with same network exists
                1807 => 409, // Alias name record exists for the host name
                1809 => 409, // GSLB binding exists for the given host
                1819 => 409, // GSLB binding exists - unbind the GSLB vserver first
                1831 => 409, // A GSLB site with the same name exists
                1832 => 409, // A GSLB site with the same IP address exists
                1852 => 409, // The persistence ID is already being used by a GSLB vserver
                1857 => 409, // The public IP and public port are being used by another GSLB service
                1951 => 409, // GSLB record cannot be created - another AAAA record is configured with the same name
                1964 => 409, // AAAA record cannot be created - another AAAA record is configured with the same name
                1979 => 409, // Cname record can't be created. Another record exists for the configured domain.
                1980 => 409, // Cname record exists for the configured domain.
                3250 => 409, // A STA or WI DBS configuration exists. Unset it first
                2163 => 409, // Only one monitor of this type can be bound to a service
                2175 => 409, // Metric table exists
                2179 => 409, //    Metric exists
                2180 => 409, // SNMP OID exists
                2305 => 409, // Service exists with the same port and service type
                2568 => 409); // Name server exists with this IP address


$internal_server_error = array(1184 => 500); // Command not implemented on server"

$service_unavailable = array(62 => 503, // Authentication failed.
                    1024 => 503, // Connection failed
                    358 => 503); // Command propagation failed due to user authentication problems
$obj = new nitro();
if(!preg_match("#^\/nitro\/v[1-9]\/config#", $_SERVER["REQUEST_URI"])&&!preg_match("#^\/nitro\/v[1-9]/stat#", $_SERVER["REQUEST_URI"])
)
{
header("HTTP/1.1 404 Not Found");
$obj->print_page_not_found($_SERVER["REQUEST_URI"]);
exit;
}
$segments = preg_split("/\//", $_SERVER["REQUEST_URI"], -1, PREG_SPLIT_NO_EMPTY);
$version_supported = false;
$count = count($segments);
$is_mimetype_set = false;
$Accept_mimetype = null;
$action = null;
$format = null;
$is_bulk = false;
$is_header_warning_required = false;

if($count >= 2)
{
    if(strpos($segments[1], "?") !== false)
    {
        list($segments[1], $query_params) = explode("?", $segments[1], 2);
        if($count > 2)
            array_splice($segments, 2);
        $segments[] = "?" . $query_params;
    }
    $version_number = $segments[1];
    if(method_exists($obj, $version_number))
        $version_supported = true;
}
if(!$version_supported)
{
    header("HTTP/1.1 505 HTTP Version Not Supported");
    $obj->print_error_message("Version not supported");
    exit;
}
$obj->is_direct_invocation = false;
call_user_func_array(array($obj, $version_number), array_slice($segments, 2));

/*
 * Parses URL segments and query segments, validates each parameter and
 * passes JSON request to PHP extension. Compresses JSON response if the
 * client can accept gzip/deflate encoding.
 *
 * Not all query parameters will be passed onto PHP extension by default.
 * If a new parameter is introduced, validate and add it to JSON request
 * in validate_and_get_json_request_params().
 */
class nitro
{
    //When NITRO is accessed through Apache, this is set to false.
    //When NITRO is accessed directly from PHP, this is set to true.
    public $is_direct_invocation = true;
    public $resource_name = null;
    public $request_method = "GET";
    public $username = "";
    public $password = "";

    public function v1()
    {
    	$is_gui = false;
        $arg_list = func_get_args();

        $headers = apache_request_headers();
		
		$headers = array_change_key_case($headers, CASE_LOWER);

        if(($query_params = $this->parse_query_string($arg_list)) === false)
            return;

	$nitro_web_application = $this->get_headervalue($headers, "NITRO_WEB_APPLICATION");
	if(isset($nitro_web_application) || $this->is_direct_invocation)
		$is_gui = true;
        if(!$this->is_direct_invocation)
            $this->request_method = $_SERVER["REQUEST_METHOD"];

        if($this->is_direct_invocation && isset($query_params["method"]))
            $this->request_method = $query_params["method"];

        if($this->setup_authentication($query_params, $headers) === false)
            return;

        if(in_array($this->request_method, array("GET", "DELETE"), true))
        {
            if(($operation = $this->validate_and_get_operation($arg_list)) === false)
                return;

            $entity_type = $this->validate_and_get_entity_type($arg_list);

            //To get all config/stat entities Ex: /nitro/v1/config or /nitro/v1/stat
            //form GET request with "configobjects" or "statobjects"
            if($entity_type == null)
            {
                $entity_type = $operation . "objects";
                $operation = "config";
            }

	    if($is_gui)
                $this->resource_name = $entity_type;

            $request = array();
			
			$query_params_available = array("count" => 1,
								"filter" => 1,
								"action" => 1,
								"args" => 1,
								"attrs" => 1,
								"entityname" => 1,
								"pageno" => 1,
								"pagesize" => 1,
								"format" => 1,
								"httpheaders"=> 1,
								"count" => 1,
								"warning" => 1,
								"statbindings" => 1,
								"bulkbindings" => 1,
								"idempotent" => 1,
								"view" => 1,
								"sessionid" => 1,
								"rawdata" => 1);

			foreach($query_params as $key => $value)	 {
				if (!array_key_exists($key, $query_params_available)) {
					$this->print_error_message("Invalid query parameters");
					return;
				}
			}
            if(($request["params"] = $this->validate_and_get_json_request_params($query_params, $is_gui)) === false)
                return;

            // Comparing the objectnames in Accept header and URL.
            $Accept = $this->get_headervalue($headers, "Accept");
            if (isset($Accept) && (strncmp($Accept, 'application', 11) == 0)) {

                $Accept_headers = preg_split("/[;]|[,]/", $Accept, 0);
                $GLOBALS['Accept_mimetype'] = $Accept;
                $Accept_length = count($Accept_headers);
                $is_acceptable_format = false;

                for($i = 0; $i < $Accept_length; $i++) {
                    if (strncmp($Accept_headers[$i], 'application/vnd.com.citrix.netscaler.', 37) == 0) {
                        $nitro_resrc = explode('+', substr($Accept_headers[$i], 37), 2);
                        $nitro_obj = preg_split("/[.]/", $nitro_resrc[0], 0);
                        $GLOBALS['is_mimetype_set'] = true;

                        // Setting the format sent in the Accept header.
                        if (isset($nitro_resrc[1]) && in_array($nitro_resrc[1], array("json", "xml")) ||
                                !$nitro_resrc[1]) {
                            $is_acceptable_format = true;
                            $request["params"]["format"] = $nitro_resrc[1];
                            $GLOBALS['format'] = $nitro_resrc[1];
                            $request["params"]["httpheaders"] = "yes";
			    $GLOBALS['is_header_warning_required'] = true;
                        }

                        // _list is removed from the object name in content type
                        if (($pos = strpos($nitro_obj[0], '_list')) != null) {
                            $objname = substr($nitro_obj[0], 0, $pos);
			} else if (strcmp($nitro_obj[0], 'config') == 0) {
                            $objname = 'configobjects';
                        } else if (strcmp($nitro_obj[0], 'stat') == 0) {
                            $objname = 'statobjects';
                        } else {
                            $objname = $nitro_obj[0];
                        }

                        if (strcmp($objname, $entity_type) != 0)
                        {
                            header("HTTP/1.1 406 Not Acceptable");
                            $this->print_error_message(MISMATCH_OBJECTNAME_ERROR);
                            return;
                        }
                    } else if (strcmp($Accept_headers[$i], 'application/json') == 0) {
                        $is_acceptable_format = true;
                        $request["params"]["format"] = "json";
                        $GLOBALS['format'] = "json";
			$GLOBALS['is_header_warning_required'] = true;
                    } else if (strcmp($Accept_headers[$i], 'application/xml') == 0) {
                        $is_acceptable_format = true;
                        $request["params"]["format"] = "xml";
                        $GLOBALS['format'] = "xml";
			$GLOBALS['is_header_warning_required'] = true;
                    }
                }
            }



            $entity_name = $this->validate_and_get_entity_name($arg_list);

            if(($request[$entity_type] = $this->validate_and_get_json_entity_params($query_params, $entity_name)) === false)
                return;

            $request_str = json_encode($request);

            if($operation === "config")
                $response =  nsrest_exec($is_gui, $this->request_method, $request_str, $this->username, $this->password, $this->get_client_ip(), $_SERVER["SERVER_ADDR"]);
            else if($operation === "stat")
                $response =  nsrest_exec($is_gui, "STAT", $request_str, $this->username, $this->password, $this->get_client_ip(), $_SERVER["SERVER_ADDR"]);

            if(isset($_SERVER["PHP_AUTH_USER"]))
            {
                if(in_array($response["errorcode"], array(354, 1024, 1025, 1026, 1027)))
                {
                    $response_obj = (array) json_decode($response["response"]);
                    $this->authentication_failed($response_obj, $headers);
                    return;
                }
            }

        if($this->is_direct_invocation)
                return $response["response"];
            $this->send_response($response, $this->request_method, $entity_type, $is_gui);
        }
        else if($this->request_method === "POST")
        {
            $content = file_get_contents('php://input');
	    $entity_type = $this->validate_and_get_entity_type($arg_list);
            $post_body = $this->get_postobject($_SERVER, $headers, $entity_type, $content, $query_params);

            if(!isset($post_body))
                return;

	    // Hard-code for techsupport entity_type, to change op to "GET"
	    // 'techsupport' ot can take number of inputs like casenum, description, username, passowrd etc.
	    // If this has to be given in GET request, all of them has to be specified in URL and also username/password gets logged in httpaccess.log
	    if(isset($entity_type) && strcmp($entity_type , 'techsupport') == 0) {
	        $this->request_method = "GET";
	    }

            $response = nsrest_exec($is_gui, $this->request_method, $post_body, $this->username, $this->password, $this->get_client_ip(), $_SERVER["SERVER_ADDR"]);
            if($this->is_direct_invocation)
                return $response["response"];
            $this->send_response($response, $this->request_method, $this->validate_and_get_entity_type($arg_list), $is_gui);
        }
        else if($this->request_method === "PUT")
        {

            $content = file_get_contents('php://input');

            $request = $this->get_putobject($_SERVER, $headers, $this->validate_and_get_entity_type($arg_list),$this->validate_and_get_entity_name($arg_list), $content, $query_params);
            if(!isset($request))
                return;

            $response = nsrest_exec($is_gui, $this->request_method, $request, $this->username, $this->password, $this->get_client_ip(), $_SERVER["SERVER_ADDR"]);
            if($this->is_direct_invocation)
                return $response["response"];
            $this->send_response($response, $this->request_method, $this->validate_and_get_entity_type($arg_list), $is_gui);
        }
        else if($this->request_method === "OPTIONS")
            $this->send_access_headers(true);
    }

    //arg_list could be a mix of paths & query strings (/config/lbvserver/v1?rawdata=yes&pageno=2&pagesize=500&view=detail)
    //removes query parameters from the last URI segment and returns all query parameters as key-value pairs
    private function parse_query_string(&$arg_list)
    {
        $query_params = array();
        $error = false;
        if(($count = count($arg_list)) <= 0)
            return $query_params;

        if ($count > 3) { // count can not be greater than 3 for get_by_name case. /config/lbvserver/lbv1?args=<key>:<value>&pageno=1&pagesize=10&filter=<key>:<value>
           $this->print_error_message("Invaild query parameters [encode the special characters if any]");
           return false;
        }

        if ($count == 3 && strpos($arg_list[1], "?")) { // Get_all or Singleton resources case. /config/systemfile?args=<key>:<value>&pageno=1&pagesize=10&filter=<key>:<value>
            $this->print_error_message("Invaild query parameters [encode the special characters if any]");
            return false;
        }

        $last_segment = $arg_list[$count - 1];
        if(strpos($last_segment, "?") === false)
            return $query_params;

        $last_segment_arr = explode("?", $last_segment, 2);
        $arg_list[$count - 1] = urldecode($last_segment_arr[0]);
        if(($query_params = $this->tokenize_string_to_key_value_arr($last_segment_arr[1], '&', '=', array("args", "filter"))) === false)
        {
            header("HTTP/1.1 599 Netscaler specific error");
            $this->print_error_message("Invalid query parameters");
            return false;
        }
        return $query_params;
    }

    //If HTTP basic authentication credentials are present, use them else take sessionid.
    //sessionid can be sent as cookie or query parameter or stored in server side session as NSAPI
    //sessionid validation is done at the back end
    //credentials can be set in headers like X-NITRO-USER and X-NITRO-PASS
    private function setup_authentication($query_params, $headers)
    {
		$Set_Cookie = $this->get_headervalue($headers, "Set-Cookie");
        $X_NITRO_USER = $this->get_headervalue($headers, "X-NITRO-USER");
        $X_NITRO_PASS = $this->get_headervalue($headers, "X-NITRO-PASS");
        if ($Set_Cookie)
            $cookie = explode("=", $Set_Cookie, 2);

        $nitro_web_application = $this->get_headervalue($headers, "NITRO_WEB_APPLICATION");

        if(isset($nitro_web_application) == true && $this->is_direct_invocation == false)
        {
            session_start();
            if(isset($_SESSION["NSAPI"]))
            {
                $this->username = $_SESSION["NSAPI"];
                $rand_key = $this->get_headervalue($headers, "rand_key");
                if(!isset($rand_key) || (strcmp($rand_key, $_SESSION["rand"]) != 0))
                {
                    $this->authentication_failed(array("message" => "Not logged in", "errorcode" => 1026), $headers );
                    return false;
                }
            }
            else
            {
                setcookie(session_name(), "", 1, '/');
                session_destroy();

                if(in_array($this->request_method, array("GET", "DELETE"), true))
                {
                    $this->authentication_failed(array("message" => "Not logged in", "errorcode" => 1026), $headers );
                    return false;
                }
            }
        }
        else 
        {
            if(isset($_SERVER["PHP_AUTH_USER"])) 
            {
                $this->username = $_SERVER["PHP_AUTH_USER"];
                $this->password = $_SERVER["PHP_AUTH_PW"];
            } 
            else if(isset($cookie)) 
            {
                if ((strcmp($cookie[0], "sessionid") == 0) || (strcmp($cookie[0], "NITRO_AUTH_TOKEN") == 0))
                    $this->username = $cookie[1];
            } 
            else if(isset($query_params["sessionid"]))
            {
                $this->username = $query_params["sessionid"];
            }
            else if(isset($_COOKIE["sessionid"]))
            {
                $this->username = $_COOKIE["sessionid"];
            }
            else if(isset($_COOKIE["NITRO_AUTH_TOKEN"]))
            {
                $this->username = $_COOKIE["NITRO_AUTH_TOKEN"];
            }
            else if(isset($X_NITRO_USER)) 
            {
                $this->username = $X_NITRO_USER;
                if (!isset($X_NITRO_PASS)) 
                {
                    header("HTTP/1.1 401 Unauthorized");
                    $this->print_error_message("Password is not specified");
                    return false;
                }
                $this->password = $X_NITRO_PASS;
            }
            else
            {
                setcookie(session_name(), "", 1, '/');
                session_destroy();

                if(in_array($this->request_method, array("GET", "DELETE"), true))
                {
                    $this->authentication_failed(array("message" => "Please provide credentials", "errorcode" => -1), $headers );
                    return false;
                }
            }
        }
        return true;
    }

    private function validate_and_get_operation($arg_list)
    {
        $operation = urldecode($arg_list[0]);
        if(!in_array($operation, array("config", "stat")))
        {
            header("HTTP/1.1 599 Netscaler specific error");
            $this->print_error_message("Invalid operation: Possible values are config or stat");
            return false;
        }
        return $operation;
    }

    private function validate_and_get_entity_type($arg_list)
    {
        return isset($arg_list[1]) ? urldecode($arg_list[1]) : null;
    }

    private function validate_and_get_entity_name($arg_list)
    {
        //urldecode twice for interface case 1%252F1 -> 1%2F1 -> 1/1
        return isset($arg_list[2]) ? urldecode(urldecode($arg_list[2])) : null;
    }

    private function validate_and_get_json_request_params($query_params, $is_gui)
    {
        $json_request_params = array();

        if(isset($query_params["rawdata"]))
        {
            if(!in_array($query_params["rawdata"], array("yes", "no")))
            {
                header("HTTP/1.1 599 Netscaler specific error");
                $this->print_error_message("Invalid rawdata: Possible values are yes or no");
                return false;
            }
            $json_request_params["rawdata"] = $query_params["rawdata"];
        }

        if(isset($query_params["view"]))
        {
            if(!in_array($query_params["view"], array("summary", "detail")))
            {
                header("HTTP/1.1 599 Netscaler specific error");
                $this->print_error_message("Invalid view: Possible values are summary or detail");
                return false;
            }
            $json_request_params["view"] = $query_params["view"];
        }

        if(isset($query_params["count"]))
        {
            if(!in_array($query_params["count"], array("yes", "no")))
            {
                header("HTTP/1.1 599 Netscaler specific error");
                $this->print_error_message("Invalid count: Possible values are yes or no");
                return false;
            }
            $json_request_params["count"] = $query_params["count"];
        }

        if(isset($query_params["warning"]))
        {
            if(!in_array($query_params["warning"], array("yes", "no")))
            {
                $this->print_error_message("Invalid warning: Possible values are yes or no");
                return false;
            }
            $json_request_params["warning"] = $query_params["warning"];
        }

	if(isset($query_params["statbindings"]))
	{
	    if(!in_array($query_params["statbindings"], array("yes", "no")))
	    {
		$this->print_error_message("Invalid statbindings: Possible values are yes or no");
		return false;
	    }
	    $json_request_params["statbindings"] = $query_params["statbindings"];
	}

	if(isset($query_params["bulkbindings"]))
	{
	    if(!in_array($query_params["bulkbindings"], array("yes", "no")))
            {
                $this->print_error_message("Invalid bulkbindings: Possible values are yes or no");
                return false;
            }
            $json_request_params["bulkbindings"] = $query_params["bulkbindings"];
        }

	if(isset($query_params["idempotent"]))
	{
	    if(!in_array($query_params["idempotent"], array("yes", "no")))
	    {
		$this->print_error_message("Invalid idempotent: Possible values are yes or no");
		return false;
	    }
	    $json_request_params["idempotent"] = $query_params["idempotent"];
	}

        if(isset($query_params["httpheaders"]))
        {
            if(!in_array($query_params["httpheaders"], array("yes", "no")))
            {
                $this->print_error_message("Invalid httpheaders: Possible values are yes or no");
                return false;
            }
            $json_request_params["httpheaders"] = $query_params["httpheaders"];
        }


        if(isset($query_params["format"]))
        {
            if(!in_array($query_params["format"], array("json", "xml")))
            {
                header("HTTP/1.1 406 Not Acceptable");
                $this->print_error_message("Invalid format: Possible values are xml or json");
                return false;
            }
            $json_request_params["format"] = $query_params["format"];
        }

        if(($json_request_params["filter"] = $this->validate_and_get_json_filter_params($query_params)) === false)
            return false;

        if(isset($query_params["pageno"]))
        {
            if(!preg_match("/^\d+$/", $query_params["pageno"]))
            {
                header("HTTP/1.1 599 Netscaler specific error");
                $this->print_error_message("Invalid pageno: only integer values are allowed");
                return false;
            }
            $json_request_params["pageno"] = intval($query_params["pageno"]);
        }

        if(isset($query_params["pagesize"]))
        {
            if(!preg_match("/^\d+$/", $query_params["pagesize"]))
            {
                header("HTTP/1.1 599 Netscaler specific error");
                $this->print_error_message("Invalid pagesize: only integer values are allowed");
                return false;
            }
            $json_request_params["pagesize"] = intval($query_params["pagesize"]);
        }

        if(isset($query_params["entityname"]))
        {
            $json_request_params["entityname"] = $query_params["entityname"];
        }

        if(isset($query_params["attrs"]))
        {
            $attrs_array = preg_split("/[,]/", $query_params["attrs"], 0);
            $json_request_params["attrs"] = $attrs_array;
        }

        return $json_request_params;
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
            
            if($key == "requestbody" && $this->is_direct_invocation)
            {
                $key_value_arr[urldecode($key)] = $val;
                continue;
            }
            
	    $resource_name = $this->resource_name;
            if(isset($resource_name) && (substr($resource_name, 0, 5) == "appfw")) {
                $key_value_arr[urldecode($key)] = $val;
                continue;
            }

            $values = (strchr($val, ';') != NULL && !in_array($key, $no_decode_array)) ? explode(";", $val) : $val;
            $key_value_arr[urldecode($key)] = $values;
        }
        return $key_value_arr;
    }

    private function validate_and_get_json_entity_params($query_params, $entity_name)
    {
        $error = false;
        $json_entity_params = array();
        if(isset($query_params["args"]))
        {
            if(($json_entity_params = $this->tokenize_string_to_key_value_arr($query_params["args"])) === false)
            {
                header("HTTP/1.1 599 Netscaler specific error");
                $this->print_error_message("Invalid args in query parameters");
                return false;
            }
        }
        if($entity_name !== null)
            $json_entity_params["arguid"] = $entity_name;
        return $json_entity_params;
    }

    private function validate_and_get_json_filter_params($query_params)
    {
        $error = false;
        $json_filter_params = array();
        if(isset($query_params["filter"]))
        {
            if(($json_filter_params = $this->tokenize_string_to_key_value_arr($query_params["filter"])) === false)
            {
                header("HTTP/1.1 599 Netscaler specific error");
                $this->print_error_message("Invalid filter in query parameters");
                return false;
            }
        }
        return $json_filter_params;
    }

    private function send_headers($send_access_headers = true)
    {
        $this->send_no_cache_headers();
        if($send_access_headers)
            $this->send_access_headers();
    }

    //Sending no-cache headers is usually taken care by PHP if we use sessions ["session.cache_limiter = nocache" in php.ini].
    //As we don't use PHP sessions in NITRO, we need to explicitly inject these headers in all NITRO output.
    private function send_no_cache_headers()
    {
        header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
    }

    //Sending access control headers for CORS (Cross Origin Resource Sharing) in NITRO
    //to avoid SOP (Single Origin Policy) for cross-domain AJAX requests
    private function send_access_headers($preflighted_request = false)
    {
        if(!isset($_SERVER["HTTP_ORIGIN"]))
            return;

        //Till we have a mechanism to validate $_SERVER["HTTP_ORIGIN"] for known origins/domains,
        //grant access to all origins by sending the following headers
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Credentials: false");

        if($preflighted_request)
        {
            header("Access-Control-Allow-Methods: GET, DELETE, POST, PUT");
            header("Access-Control-Allow-Headers: NITRO_WEB_APPLICATION, IF-MODIFIED-SINCE");
            header("Access-Control-Max-Age: 3600");
        }
    }

    //Set-cookie is set for login request. For new content type NITRO_AUTH_TOKEN is set, otherwise sessionid is set.
    //Content type is set same as Accept header sent by client. If Accept header is not set then default content type is set.
    //In case of error for new content type in request, error is set in content type of response.
    private function send_response($response, $method, $entitytype, $is_gui)
    {
        $this->send_headers();
        if($this->is_xml($response["response"])) {

            // Setting the Cookie for the Login request
            if ($method == "POST") {
	        libxml_disable_entity_loader(true);
                $nitro_resp = simplexml_load_string($response["response"]);
		libxml_disable_entity_loader(false);
                if (isset($nitro_resp)) {
                    foreach ($nitro_resp->children() as $child){
                        if (strcmp($child->getName(), "sessionid") == 0) {
                            if ($GLOBALS['is_mimetype_set']) {
                                setcookie("NITRO_AUTH_TOKEN", $child, NULL, "/nitro/v1");
                                $response["response"] = NULL;
                            } else
                                setcookie("sessionid", $child, NULL, "/nitro/v1");
                            break;
                            }
                        }
                    }
                }

            if ($response["errorcode"] != 0 && $GLOBALS['is_mimetype_set']) {
                header("Content-type: application/vnd.com.citrix.netscaler.error+xml;");
            } else if ($GLOBALS['is_mimetype_set']) {
                $content_type = $this->gen_contenttype($response, $entitytype);
                $mime_type = $content_type . "+xml";
                if ($method == "GET" && isset($GLOBALS['Accept_mimetype'])) {

                    $Accept_headers = preg_split("/[;]|[,]/", $GLOBALS['Accept_mimetype'], 0);
                    $Accept_length = count($Accept_headers);
                    $is_valid_acceptheader = false;

                    for($i = 0; $i < $Accept_length; $i++) {
                        if ((strcmp($Accept_headers[$i], "application/xml") == 0) ||
                            (strcmp($Accept_headers[$i], $mime_type) == 0) ||
			    (strcmp($Accept_headers[$i], "application/vnd.com.citrix.netscaler.config+xml") == 0) ||
			    (strcmp($Accept_headers[$i], "application/vnd.com.citrix.netscaler.stat+xml") == 0)) {
                            $is_valid_acceptheader = true;
                            if ($response["response"]) {
                                if (isset($mime_type))
                                    header("Content-type:" .$mime_type);
                                else
                                    header("Content-type:application/xml");
                            }
                        }
                    }
                    if (!$is_valid_acceptheader) {
                        header("HTTP/1.1 406 Not Acceptable");
                        $this->print_error_message("Invalid accept header");
                        return;
                    }
                }
            } else if ($response["response"]){
                header("Content-type: application/xml; charset=utf-8");
            }
        } else {

            // Setting the Cookie for the Login request
            if ($method == "POST")  {
                $nitro_resp = json_decode($response["response"]);
                $sessionid = "sessionid";
                if(isset($nitro_resp->$sessionid))    {
                    if ($GLOBALS['is_mimetype_set']) {
                        setcookie("NITRO_AUTH_TOKEN", $nitro_resp->$sessionid, NULL, "/nitro/v1");
                        $response["response"] = NULL;
                    } else
                        setcookie("sessionid", $nitro_resp->$sessionid, NULL, "/nitro/v1");
                }
            }

            if ($response["errorcode"] != 0 && $GLOBALS['is_mimetype_set']) {
                header("Content-type: application/vnd.com.citrix.netscaler.error+json;");
            } else if ($GLOBALS['is_mimetype_set']) {
                $content_type = $this->gen_contenttype($response, $entitytype);
                $mime_type = $content_type . "+json";

                if ($method == "GET" && isset($GLOBALS['Accept_mimetype'])) {
                    $Accept_headers = preg_split("/[;]|[,]/", $GLOBALS['Accept_mimetype'], 0);
                    $Accept_length = count($Accept_headers);
                    $is_valid_acceptheader = false;

                    for($i = 0; $i < $Accept_length; $i++) {
                        if ((strcmp($Accept_headers[$i], "application/json") == 0) ||
                            (strcmp($Accept_headers[$i], $mime_type) == 0) || (strcmp($Accept_headers[$i], $content_type) == 0) ||
			    (strcmp($Accept_headers[$i], "application/vnd.com.citrix.netscaler.config+json") == 0) ||
			    (strcmp($Accept_headers[$i], "application/vnd.com.citrix.netscaler.stat+json") == 0)) {
                            $is_valid_acceptheader = true;
                            if ($response["response"]) {
                                if (isset($mime_type))
                                    header("Content-type:" .$mime_type);
                                else
                                    header("Content-type:application/json");
                            }
                        }
                    }
                    if (!$is_valid_acceptheader) {
                        header("HTTP/1.1 406 Not Acceptable");
                        $this->print_error_message("Invalid accept header");
                        return;
                    }
                }
            } else if ($response["response"]){
                header("Content-type: application/json; charset=utf-8");
            }
        }

        // Generating the http status
        $this->gen_httpstatus($response["errorcode"], $method);
        if (strcmp($response["warning_mes"], "(null)") != 0 && $GLOBALS['is_header_warning_required'] && !$is_gui) {
            header("HTTP/1.1 209 Netscaler specific warning");
            header("X-NITRO-WARNING:" . $response["warning_mes"]);
        }

        if ($method == "POST" && (strcmp($entitytype, "logout") == 0)) {
            if ($GLOBALS['is_mimetype_set'])
                setcookie("NITRO_AUTH_TOKEN", "", 1, "/nitro/v1");
            else
                setcookie("sessionid", "", 1, "/nitro/v1");
        }

        if($this->request_method === "GET")
            ob_start("ob_gzhandler");

	if (($method == "POST" || $method == "PUT") && (strcmp($entitytype, "macroapi") == 0)) {
	    $GLOBALS['is_bulk'] = true;
	    $this->gen_httpstatus($response["errorcode"], $method);
	    if($response["errorcode"] == 0 && isset($GLOBALS['format']))
	    	return;
	}

        print $response["response"];
    }

    private function is_xml($response)
    {
        return $response != null && strlen($response) > 0 && $response[0] == '<';
    }

    public function print_error_message($error_message, $error_code = -1)
    {
        $this->send_headers();
        if (isset($GLOBALS['format']) && (strcmp($GLOBALS['format'], "xml") == 0)) {
            header("Content-type: application/xml; charset=utf-8");
            $xml = new SimpleXMLElement('<nitroResponse/>');
            array_walk_recursive(array_flip($this->get_error_result_object($error_message, $error_code)), array ($xml, 'addChild'));
            print $xml->asXML();
        } else {
            header("Content-type: application/json; charset=utf-8");
            print json_encode($this->get_error_result_object($error_message, $error_code));
        }
    }

    private function authentication_failed($error_message_obj, $headers)
    {
		$nitro_web_application = $this->get_headervalue($headers, "NITRO_WEB_APPLICATION");
        if(isset($nitro_web_application))
        {
             $this->print_error_message($error_message_obj["message"], $error_message_obj["errorcode"]);
        }
        else
        {
            $this->send_headers(false);
            header("WWW-Authenticate: Basic realm=\"NITRO\"");
            header("HTTP/1.1 401 Unauthorized");
            $this->print_authentication_cancelled_page($error_message_obj["message"]);
        }
    }

    private function print_authentication_cancelled_page($error_message)
    {
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>NITRO Login Failure</title>
    </head>
    <body>
        <center>
            <br><br><br><br>
            <h4>Authentication failed - <?=$error_message?></h4>
        </center>
    </body>
</html>
<?php
    }

    public function print_page_not_found($request_url)
    {
?>
<html>
    <head>
        <title>404 Not Found</title>
    </head>
    <body>
            <h1>Not Found</h1>
            <p>The requested URL <?=str_replace("%2F", "/", urlencode($request_url))?> was not found on this server</p>
    </body>
</html>
<?php
    }

    private function get_error_result_object($error_message, $error_code)
    {
        return array("errorcode" => $error_code, "message" => $error_message, "severity" => "ERROR");
    }

    private function get_client_ip()
    {
        //If client comes on NSIPv6, "internal NAT" will map IPv6 request to IPv4 Apache.
        //Original IPv6 client address will be sent in "cip-header" as "[<ipv6>]" ex: [2980::10]
        //If client comes on NSIPv4, still "cip-header" will be present ex: 12.32.23.33
        if(isset($_SERVER["HTTP_CIP_HEADER"]))
            return preg_replace("/[\[\]]/", "", $_SERVER["HTTP_CIP_HEADER"]);
        return $_SERVER["REMOTE_ADDR"];
    }

    private function get_params($format, $query_params, $Accept, $headers)
    {

        $action = null;
        $onerror = null;
	$override = null;
	$warning = null;
	$idempotent = null;

        // Setting the action sent in query params
        if (isset($query_params["action"]))
            $action = $query_params["action"];

        if (isset($query_params["override"])) {
            $override = $query_params["override"];
	}

	if (isset($query_params["warning"]))
	    $warning = $query_params["warning"];

	if (isset($query_params["idempotent"]))
	    $idempotent = $query_params["idempotent"];
	
	// Setting the action in global parameter
        $GLOBALS['action'] = $action;

        // Setting the format sent in Accept header.
        $Accept_headers = preg_split("/[;]|[,]/", $Accept, 0);
        $GLOBALS['Accept_mimetype'] = $Accept;
        $Accept_length = count($Accept_headers);
        $is_acceptable_format = false;
        $GLOBALS['Accept_mimetype'] = $Accept;

        for($i = 0; $i < $Accept_length; $i++) {
            if (isset($Accept_headers[$i]) && (strncmp($Accept_headers[$i], 'application', 11) == 0)) {
                if (strncmp($Accept_headers[$i], 'application/vnd.com.citrix.netscaler.', 37) == 0) {
                    $resrc = explode('+', substr($Accept_headers[$i], 37), 2);
                    if (count($resrc) > 1 && in_array($resrc[1], array("json", "xml"))) {
                        $format = $resrc[1];
                        $is_acceptable_format = true;
                    } else if(!$resrc[1]) {
                        $is_acceptable_format = true;
                    }
                } else if (strcmp($Accept_headers[$i], 'application/json') == 0) {
                    $format = "json";
                    $is_acceptable_format = true;
                } else if (strcmp($Accept_headers[$i], 'application/xml') == 0) {
                    $format = "xml";
                    $is_acceptable_format = true;
                }
            }
        }

        if (isset($Accept) && (strcmp($Accept, "*/*") != 0) && !$is_acceptable_format) {
            header("HTTP/1.1 406 Not Acceptable");
            $this->print_error_message("Invalid format: Possible encoding values in Accept header can be xml or json only");
            return false;
        }

        // Setting the header X-NITRO-ONERROR for bulk request
		$nitro_error = $this->get_headervalue($headers, "X-NITRO-ONERROR");
        if (isset($nitro_error))
            $onerror = $this->get_headervalue($headers, "X-NITRO-ONERROR");

        // Constructing the params.
        $params = $this->validate_and_post_json_request_params($action, $format, $onerror, $override, $warning, $idempotent);
        return $params;

    }


    private function validate_headers_and_getrequest($headers, $method, $entity_type, $entity_name, $content, $query_params)
    {
        $format = "json";
        $put_body = null;

        $Content_Type = $this->get_headervalue($headers, "Content-Type");
        $Accept = $this->get_headervalue($headers, "Accept");

        $GLOBALS['is_mimetype_set'] = true;

        $nitro_resrc = explode('+', substr($Content_Type, 37), 2);

        // Objectname is missing
        if (!isset($entity_type))
        {
            header("HTTP/1.1 406 Not Acceptable");
            $this->print_error_message(MISSING_OBJECTNAME_ERROR);
            return null;
        }

        // _list is removed from the object name in content type
        if (($pos = strpos($nitro_resrc[0], '_list')) != null) {
            $GLOBALS['is_bulk'] = true;
            $objname = substr($nitro_resrc[0], 0, $pos);
        } else {
            $objname = $nitro_resrc[0];
        }

        // Comparing the objectnames in Content type and URL
        if (strcmp($objname, $entity_type) != 0)
        {
			header("HTTP/1.1 406 Not Acceptable");
            $this->print_error_message(MISMATCH_OBJECTNAME_ERROR);
            return null;
        }

        // Setting the format sent in the Content type.
        if (count($nitro_resrc) > 1) {
            $format = $nitro_resrc[1];
        }

        if(!in_array($format, array("json", "xml")))
        {
            header("HTTP/1.1 406 Not Acceptable");
            $this->print_error_message("Invalid format: Possible encoding values in Content-Type  can be xml or json only");
            return null;
        }

        if (!((($content[0] === '<') && strcmp($format, 'xml') == 0) ||
            (($content[0] === '{') && strcmp($format, 'json') == 0))) {
            header("HTTP/1.1 400 Bad Request");
               $this->print_error_message("Invalid payload format. Default format is json");
               return null;
        }

        if (($params = $this->get_params($format, $query_params, $Accept, $headers)) == false)
            return null;

        // Validating whether entityname is present or not.
	/*
	 * commenting the below validation for vnd contentType.
	 * entityname should be given in the URL for PUT request. But for singleton resource, entityname does not exit.
	 *
         * if ($method == "PUT" && $this->validate_entityname($entity_name, $nitro_resrc[0]) === false) {
         *   return null;
         * }
	 */


        if (($request = $this->get_payload($content, $entity_type, $params, $nitro_resrc[0])) === false)
            return null;

        return $request;

    }

    private function set_globalformat($Content_type, $Accept)
    {
        if (isset($Accept) && (strcmp($Accept, "*/*") != 0)) {
            if (strncmp($Accept, 'application/vnd.com.citrix.netscaler.', 37) == 0) {
                $nitro_resrc = explode('+', substr($Accept, 37), 2);
                $GLOBALS['format'] = $nitro_resrc[1];
            } else if ((strcmp($Accept, 'application/json') == 0) ||
            (strcmp($Accept, 'application/xml') == 0)) {
                $nitro_resrc = explode('/', $Accept, 2);
                $GLOBALS['format'] = $nitro_resrc[1];
            }
        } else if (isset($Content_type)) {
            if (strncmp($Content_type, 'application/vnd.com.citrix.netscaler.', 37) == 0) {
                $nitro_resrc = explode('+', substr($Content_type, 37), 2);
                $GLOBALS['format'] = $nitro_resrc[1];
            } else if ((strcmp($Content_type, 'application/json') == 0) ||
            (strcmp($Content_type, 'application/xml') == 0)) {
                $nitro_resrc = explode('/', $Content_type, 2);
                $GLOBALS['format'] = $nitro_resrc[1];
            }
        }
    }

    private function get_putobject($_SERVER, $headers, $entity_type, $entity_name, $content, $query_params) {

        $Content_Type = $this->get_headervalue($headers, "Content-Type");
        $Accept = $this->get_headervalue($headers, "Accept");

        $this->set_globalformat($Content, $Accept);
        if (isset($Content_Type) && (strncmp($Content_Type, 'application/vnd.com.citrix.netscaler.', 37) == 0)) {
            $put_body = $this->validate_headers_and_getrequest($headers, "PUT", $entity_type, $entity_name, $content, $query_params);
	    $GLOBALS['is_header_warning_required'] = true;
            if(!isset($put_body))
                return;
        } else if (isset($Content_Type) && ((strstr($Content_Type, 'application/json')) ||
            (strstr($Content_Type, 'application/xml')))) {

	    $GLOBALS['is_header_warning_required'] = true;
            $nitro_resrc = explode('/', $Content_Type, 2);
            if (!isset($entity_type)) {
                header("HTTP/1.1 406 Not Acceptable");
                $this->print_error_message(MISSING_OBJECTNAME_ERROR);
                return null;
            }

            $put_body = $content;

            if (!isset($put_body)) {
                header("HTTP/1.1 400 Bad Request");
                $this->print_error_message("Invalid POST request");
                return null;
            }

            if (!((($put_body[0] === '<') && strstr($nitro_resrc[1], 'xml')) ||
            (($put_body[0] === '{') && strstr($nitro_resrc[1], 'json')))) {
                header("HTTP/1.1 400 Bad Request");
                $this->print_error_message("Invalid payload format. Default format is json");
                return null;
            }

            if ($this->validate_objectname($put_body, $entity_type) === false) {
                return null;
            }

            if (($params = $this->get_params($nitro_resrc[1], $query_params, $Accept, $headers)) == false)
                return null;

            if (($post_body = $this->get_payload($content, $entity_type, $params, null)) === false)
                return $post_body;

	    $params_str = "params";
	    $warning_str = "warning";
	    $onerror_str = "onerror";
	    $httpheaders_str = "httpheaders";

	    $req_body = json_decode($content);
	    $req_params = $req_body->$params_str;

	    $put_body = json_decode($post_body);
	    $put_body_params = $put_body->$params_str;

	    if(!isset($put_body_params->$warning_str) && isset($req_params->$warning_str))
	         $put_body_params->$warning_str = $req_params->$warning_str;
	    if(!isset($put_body_params->$onerror_str) && isset($req_params->$onerror_str))
	    	 $put_body_params->$onerror_str = $req_params->$onerror_str;
	    $put_body_params->$httpheaders_str = "no";

	    $put_body->$params_str = $put_body_params;
	    $put_final_body = json_encode($put_body);

	    return $put_final_body;

        } else if ($entity_type != null) {
            $put_body = $content;
            if ($this->validate_objectname($put_body, $entity_type) === false) {
                return null;
            }

        } else {
            $put_body = $content;
        }

        if(!isset($put_body))
        {
            header("HTTP/1.1 400 Bad Request");
               $this->print_error_message("Invalid PUT request");
               return null;
        }

        return $put_body;

    }
	
	private function get_headervalue($headers, $header_key)
	{
		$header_key = strtolower($header_key);
		
		if (isset($headers[$header_key]))		
			return $headers[$header_key];
	}
	
    private function get_postobject($_SERVER, $headers, $entity_type, $content, $query_params)
    {
		$Content_Type = $this->get_headervalue($headers, "Content-Type");
        $Accept = $this->get_headervalue($headers, "Accept");
        
		$post_body = null;

        $this->set_globalformat($Content_Type, $Accept);
        if (isset($Content_Type) && (strncmp($Content_Type, 'application/vnd.com.citrix.netscaler.', 37) == 0)) {
            $post_body = $this->validate_headers_and_getrequest($headers, "POST", $entity_type, NULL, $content, $query_params);
	    $GLOBALS['is_header_warning_required'] = true;
            if(!isset($post_body))
                return null;
        } else if (isset($Content_Type) && ((strstr($Content_Type, 'application/json')) ||
            (strstr($Content_Type, 'application/xml')))) {

	    $GLOBALS['is_header_warning_required'] = true;
            $nitro_resrc = explode('/', $Content_Type, 2);
            if (!isset($entity_type)) {
                header("HTTP/1.1 406 Not Acceptable");
                $this->print_error_message(MISSING_OBJECTNAME_ERROR);
                return null;
            }

            $post_body = $content;

            if (!isset($post_body)) {
                header("HTTP/1.1 400 Bad Request");
                $this->print_error_message("Invalid POST request");
                return null;
            }

            if (!((($post_body[0] === '<') && strstr($nitro_resrc[1], 'xml')) ||
            (($post_body[0] === '{') && strstr($nitro_resrc[1], 'json')))) {
                header("HTTP/1.1 400 Bad Request");
                $this->print_error_message("Invalid payload format. Default format is json");
                return null;
            }

            if ($this->validate_objectname($post_body, $entity_type) === false) {
                return null;
            }

            if (($params = $this->get_params($nitro_resrc[1], $query_params, $Accept, $headers)) == false)
                return null;

            if (($post_body = $this->get_payload($content, $entity_type, $params, null)) === false)
                return $post_body;

        } else if ($entity_type != null) {
            $post_body = $_POST["object"];
            if ($this->validate_objectname($post_body, $entity_type) === false) {
                return null;
            }

        } else {
            if($this->is_direct_invocation && isset($query_params["requestbody"]))
                $post_body = $query_params["requestbody"];
            else 
                $post_body = $_POST["object"];
        }

        if(!isset($post_body))
        {
            header("HTTP/1.1 400 Bad Request");
            $this->print_error_message("Invalid POST request");
               return null;
        }

        return $post_body;

    }

    // Validating and constructing params in nitro payload.
     private function validate_and_post_json_request_params($action, $format, $onerror, $override, $warning, $idempotent)
     {
        $json_request_params = array();

        if(isset($format))
           {
            if(!in_array($format, array("json", "xml")))
            {
                header("HTTP/1.1 406 Not Acceptable");
                $this->print_error_message("Invalid format: Possible encoding values in Content-Type  can be xml or json only");
                return false;
            }
            $json_request_params["format"] = $format;

        }
		
	if(isset($override))
        {
            if(!in_array($override, array("yes", "no")))
            {
                header("HTTP/1.1 406 Not Acceptable");
                $this->print_error_message("Invalid value for override: Possible values are yes or no");
                return false;
            }
            $json_request_params["override"] = $override;
        }

	if(isset($warning))
	{
	    if(!in_array($warning, array("yes", "no")))
	    {
		header("HTTP/1.1 406 Not Acceptable");
		$this->print_error_message("Invalid value for warning: Possible values are yes or no");
		return false;
	    }
	    $json_request_params["warning"] = $warning;
	}

	if(isset($idempotent))
        {
            if(!in_array($idempotent, array("yes", "no")))
            {
                header("HTTP/1.1 406 Not Acceptable");
                $this->print_error_message("Invalid value for idempotent: Possible values are yes or no");
                return false;
            }
            $json_request_params["idempotent"] = $idempotent;
        }

        if(isset($action))
            $json_request_params["action"] = $action;

        if(isset($onerror))
            $json_request_params["onerror"] = $onerror;

        $json_request_params["httpheaders"] = "yes";

        return $json_request_params;
    }

    // Validating the objectname given in the bosy and the URL.
    private function validate_objectname($post_body, $entity_type)
    {
	// For Performing MACRO-API, don't validate the entityname
	if (strcmp($entity_type, 'macroapi') == 0)
	    return true;

        if (preg_match("/^</", $post_body)) {
            $count = 0;
            $nitro_resrc = DOMDocument::loadXML($post_body);
            $count = $nitro_resrc->getElementsByTagName($entity_type)->length;
            if ($count == 0) {
                header("HTTP/1.1 406 Not Acceptable");
                $this->print_error_message(MISMATCH_OBJECTNAME_ERROR);
                return false;
            }
        } else if (preg_match("/^{/", $post_body)) {
            $nitro_resrc = json_decode($post_body);
            if(!isset($nitro_resrc->$entity_type))
            {
                header("HTTP/1.1 406 Not Acceptable");
                $this->print_error_message(MISMATCH_OBJECTNAME_ERROR);
                return false;
            }
        }
        return true;
    }

    // Checking whether entityname is present or not in the URL for single object in PUT request
    private function validate_entityname($entity_name, $objectname)
    {
        if (strstr($objectname, "_list") != false)
            return;

        if (!isset($entity_name)) {
            header("HTTP/1.1 599 Netscaler specific error");
            $this->print_error_message("Entityname is missing");
            return false;
        }
    }

    // Constructing the nitro payload.
    private function get_payload($content, $entity_type, $params, $objectname) {

        $error = false;
        $request = array();
        $entity_list = $entity_type . "_list";
        if (preg_match("/^</", $content)) {
	    libxml_disable_entity_loader(true);
            $req = simplexml_load_string($content);
	    libxml_disable_entity_loader(false);
            if ($req == null) {
                header("HTTP/1.1 400 Bad Request");
                $this->print_error_message("Invalid Xml Input");
                return false;
            }

            if (isset($objectname)) {
                if (strcmp($req->getName(), $objectname) != 0) {
                    header("HTTP/1.1 400 Bad Request");
                    $this->print_error_message("Invalid Xml Payload. Mismatch between content-type and payload");
                    return false;
                }
            }

	   $xml = "<nitroRequest>\n" . "" . $content . "" .  $this->arrayToXMLString($params,"params") . "</nitroRequest>";
	   return $xml;

        } else {
            $req = json_decode($content);

            if ($req == null) {
                header("HTTP/1.1 400 Bad Request");
                $this->print_error_message("Invalid Json Input");
                return false;
            }

            // Validating the payload and the content type
            if ($objectname) {
                if (is_array($req->$entity_type) && (strstr($objectname, "_list") == NULL))
                    $error = true;
                if (!(is_array($req->$entity_type)) && (strstr($objectname, "_list") != NULL))
                    $error = true;
            }

            if ($error) {
                header("HTTP/1.1 400 Bad Request");
                $this->print_error_message("Invalid Json Payload. Mismatch between content-type and payload");
                return false;
            }

	    if (strcmp($entity_type, "macroapi") == 0) {
		// For bulk requests, complete requst-payload has to sent.
	        $param_str = "params";
		$req->$param_str = $params;
		$post_body = json_encode($req);
	    } else {
	        $request[$entity_type] = $req->$entity_type;
		$request["params"] = $params;
		$post_body = json_encode($request);
	    }

            return $post_body;
	}

    }

    private function arrayToXMLString($data, $rootnode) {
	$xmlString = "\n<" . $rootnode . ">\n";
	foreach($data as $key => $value) {
		if (is_array($value)) {
			$xmlString = $xmlString . "" . $this->arrayToXMLString($value,$key);
			continue;
		}
		$xmlString = $xmlString . "<" . $key . ">";
		$xmlString = $xmlString . "" . $value ;
		$xmlString = $xmlString . "</" . $key . ">\n";
	}
	$xmlString = $xmlString . "</" . $rootnode . ">\n";
	print $xmlstring;
	return $xmlString;
    }
 

    private function gen_contenttype($response, $entitytype) {

        $content_type = null;

        if (isset($response["is_list"]) && $response["is_list"] == 1)
            $objname = $entitytype . "_list";
        else
            $objname = $entitytype;

        $content_type = "application/vnd.com.citrix.netscaler." . $objname;

        return $content_type;
    }

    private function gen_httpstatus($errorcode, $method) {

        $action = $GLOBALS['action'];

        if ($GLOBALS['is_bulk'] && $errorcode) {
            header("HTTP/1.1 207 Multi Status");
        } else if (!$errorcode) {
            if ($method == "POST" && (!(isset($action)) || (in_array($action, array("add", "bind")))))
                header("HTTP/1.1 201 Created");
            else
                header("HTTP/1.1 200 OK");
        } else    if (isset($GLOBALS['badrequest'][$errorcode])) {
            header("HTTP/1.1 400 Bad Request");
        } else if (isset($GLOBALS['unauthorized'][$errorcode])) {
            header("HTTP/1.1 401 Unauthorized");
        } else    if (isset($GLOBALS['forbidden'][$errorcode])) {
            header("HTTP/1.1 403 Forbidden");
        } else if (isset($GLOBALS['notfound'][$errorcode])) {
            header("HTTP/1.1 404 Not Found ");
        } else if (isset($GLOBALS['method_not_allowed'][$errorcode])) {
            header("HTTP/1.1 405 Method Not Allowed");
        } else if (isset($GLOBALS['conflict'][$errorcode])) {
            header("HTTP/1.1 409 Conflict");
        } else if (isset($GLOBALS['internal_server_error'][$errorcode])) {
            header("HTTP/1.1 500 Internal Server Error ");
        } else if (isset($GLOBALS['service_unavailable'][$errorcode])) {
            header("HTTP/1.1 503 Service Unavailable ");
        } else {
            header("HTTP/1.1 599 Netscaler specific error");
        }

    }

}
?>
