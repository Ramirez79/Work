<?php if(!defined("BASEPATH")) exit("No direct script access allowed");

require_once(APPPATH. "controllers/common/abstract_controller.php");

function exception_error_handler($errno, $errstr, $errfile, $errline ) {
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}

define("APPFW_SCAN_XSL_FILES", "/var/tmp/_appfw_scan_xsl_files");	// cleaned up by /netscaler/upgrade_appfw_imports.pl during install

class rapi extends abstract_controller
{
    //When rapi is accessed through Apache, this is set to false.
    //When rapi is accessed directly from PHP, this is set to true.
    private $is_direct_invocation;
    private $session_started;
    private $cluster_doesnot_exist_error_code = 2448;
    private $extended_timeout = 115;
    private $args;
    private $filter;

    //Query params & object types for which they are applicable
    //If a query param is globally applicable, leave the value array empty
    //If sessionid is applicable for an object type, its handler shouldn't rely on $_SESSION (as no PHP session exists)
    //Moreover, if its handler is not going to fire any command, it should explicitly validate the back-end session
    //using abstract_controller::validate_session() Ex: Refer syslog.php
    private $VALID_QUERY_PARAMS = array(
        "sessionid" => array("agee_alerts"),
        "args" => array("remotefiledir", "check_internet_conn", "configuration_diff", "sslcertfile_properties"), //Applicable arg names should be defined in VALID_ARGS below
        "filter" => array("systemlog_messages", "ipsec_logs", "sslpolicy", "read_file", "read_url", "filedownload", "filedownload_with_save", "uploadfile", "read_webinterface_conf_file", "vpneula", "sslcertkey", "sslkeyfiles", "sslcsrfiles", "sslcertfiles"), //Applicable filter names should be defined in VALID_FILTERS below
        "pageno" => array("sslpolicy", "nspartition_systemuser_binding", "vpneula", "sslkeyfiles", "sslcsrfiles", "sslcertfiles", "sslcertkey"),
        "pagesize" => array("sslpolicy", "nspartition_systemuser_binding", "vpneula", "sslkeyfiles", "sslcsrfiles", "sslcertfiles", "sslcertkey"),
        "count" => array("sslpolicy", "nspartition_systemuser_binding", "vpneula", "sslkeyfiles", "sslcsrfiles", "sslcertfiles", "sslcertkey"),
        "rand_key" => array("filedownload", "filedownload_with_save", "filedownload_default_signatures")
    );

    //If args is applicable for an object type (see VALID_QUERY_PARAMS above), define the valid args names here
    //To accept anything as args, leave the value array empty Ex: "object_type" => array()
    private $VALID_ARGS = array(
        "configuration_diff" => array("base_file", "second_file"),
        "remotefiledir" => array("rand", "path", "name", "is_file"),
        "check_internet_conn" => array("path"),
        "sslcertfile_properties" => array("filename")
    );

    //If filter is applicable for an object type (see VALID_QUERY_PARAMS above), define the valid filter names here
    //To accept anything as filter, leave the value array empty Ex: "object_type" => array()
    private $VALID_FILTERS = array(
        "systemlog_messages" => array("file_name", "start"),
        "ipsec_logs" => array("tunnel_name", "date", "remote", "local", "message"),
        "sslpolicy" => array("name"),
        "read_file" => array("path"),
        "read_url" => array("path"),
        "filedownload" => array("path","filename","remove"),
        "filedownload_with_save" => array("path"),
        "uploadfile" => array("dir"),
        "read_webinterface_conf_file" => array("path","filename"),
        "vpneula" => array("name"),
        "sslcertkey" => array("cert_type", "certkey", "status"),
        "sslkeyfiles" => array("filename"),
        "sslcsrfiles" => array("filename"),
        "sslcertfiles" => array("filename")
        );

    //Object types for which object name is applicable
    private $VALID_OBJECT_TYPES_FOR_OBJECT_NAME = array("appunit_details", "removeclusternode", "xenserver_custom_api", "remote_nsip", "sslpolicy", "nspartition_systemuser_binding", "vpneula", "remoteipsecprofile", "remoteiptunnel", "remotenspbr");

    public function __construct($is_direct_invocation = false)
    {
        parent::__construct();
        $this->is_direct_invocation = $is_direct_invocation;
        if($this->is_direct_invocation) //If accessed from PHP, session would've been started already
            $this->session_started = true;
    }

    public function main()
    {
        if(!$this->session_started && $this->start_session_for_api_request(true, false))
            $this->session_started = true;

        $arg_list = $this->get_arg_list(func_get_args());
        if(($query_params = $this->get_query_params($arg_list)) === false)
            return;

        $object_type = $this->validate_and_get_object_type($arg_list);
        $object_name = $this->validate_and_get_object_name($arg_list, $object_type);
        if($this->validate_query_params($query_params, $object_type) === false)
            return;

        if($this->is_direct_invocation === false)
        { 
            if($this->validate_request() === false)
                return;
        }

        if(!$this->session_started)
        {
            $this->print_error_for_api_request(NSERR_SESSION_EXPIRED_MESSAGE, NSERR_SESSION_EXPIRED);
            return;
        }

        $response = null; 
        switch($object_type)
        {
            case "system_info":
                $response = $this->system_info();
                break;
            case "agee_alerts":
                require_once(APPPATH. "controllers/common/agee.php");
                $agee = new agee($this->current_request_type);
                $response = $agee->get_alerts();
                break;
            case "appunit_details":
                require_once(APPPATH. "controllers/common/appexpert.php");
                $appexpert = new appexpert($this->current_request_type);
                $response = $appexpert->get_appunit_details($object_name);
                break;
            case "app_templates":
                require_once(APPPATH. "controllers/common/appexpert.php");
                $appexpert = new appexpert($this->current_request_type);
                $response = $appexpert->get_app_templates();
                break;
            case "configuration_diff":
                require_once(APPPATH."controllers/common/config_diff.php");
                $config_diff_obj = new config_diff($this->current_request_type);
                $response = $config_diff_obj->get_config_diff($this->args);
                return;
            case "lb_entity_templates":
                require_once(APPPATH. "controllers/common/appexpert.php");
                $appexpert = new appexpert($this->current_request_type);
                $response = $appexpert->get_lb_entity_templates();
                break;
           case "systemlog_messages":
                require_once(APPPATH. "controllers/common/syslog.php");
                $syslog = new syslog($this->current_request_type);
                $response = $syslog->get_systemlog_messages($object_name, $this->filter);
                break;
            case "systemlog_files_inside_dir":
                require_once(APPPATH. "controllers/common/syslog.php");
                $syslog = new syslog($this->current_request_type);
                $response = $syslog->get_systemlog_files_inside_dir();
                break;
            case "remoteiptunnel":
                require_once(APPPATH. "controllers/common/cloudbridge.php");
                $cloudbridge = new cloudbridge($this->current_request_type);
                $response = $cloudbridge->remoteiptunnel($object_name);
                break;
            case "remotenspbr":
                require_once(APPPATH. "controllers/common/cloudbridge.php");
                $cloudbridge = new cloudbridge($this->current_request_type);
                $response = $cloudbridge->remotepbr($object_name);
                break; 
             case "remoteip":
                require_once(APPPATH. "controllers/common/cloudbridge.php");
                $cloudbridge = new cloudbridge($this->current_request_type);
                $response = $cloudbridge->get_remoteip($object_name);
                break;
            case "remote_nsip":
                require_once(APPPATH. "controllers/common/cloudbridge.php");
                $cloudbridge = new cloudbridge($this->current_request_type);
                $response = $cloudbridge->get_remote_nsip(isset($object_name) ? $object_name : "");
                break;
            case "remote_aws_eips":
                require_once(APPPATH. "controllers/common/cloudbridge.php");
                $cloudbridge = new cloudbridge($this->current_request_type);
                $response = $cloudbridge->get_remote_nsip(isset($object_name) ? $object_name : "");
                $remote_snips = $this->get_snips($response);
                
                require_once(APPPATH. "controllers/common/amazon.php");
                $amazon = new amazon($this->current_request_type);
                $response = $amazon->get_elastic_ips($remote_snips);
                break;
            case "remotenetbridge":
                require_once(APPPATH. "controllers/common/cloudbridge.php");
                $cloudbridge = new cloudbridge($this->current_request_type);
                $response = $cloudbridge->remotenetbridge($object_name);
                break;
            case "remotenetbridgebinding":
                require_once(APPPATH. "controllers/common/cloudbridge.php");
                $cloudbridge = new cloudbridge($this->current_request_type);
                $response = $cloudbridge->remotenetbridgebinding($object_name);
                break;
            case "remoteipsecprofile":
                require_once(APPPATH. "controllers/common/cloudbridge.php");
                $cloudbridge = new cloudbridge($this->current_request_type);
                $response = $cloudbridge->remoteipsecprofile($object_name);
                break;
            case "remotensfeature":
                require_once(APPPATH. "controllers/common/cloudbridge.php");
                $cloudbridge = new cloudbridge($this->current_request_type);
                $response = $cloudbridge->remotensfeature($object_name);
                break;
            case "remotensmode":
                require_once(APPPATH. "controllers/common/cloudbridge.php");
                $cloudbridge = new cloudbridge($this->current_request_type);
                $response = $cloudbridge->remotensmode($object_name);
                break;
            case "remotentpserver":
                require_once(APPPATH. "controllers/common/cloudbridge.php");
                $cloudbridge = new cloudbridge($this->current_request_type);
                $response = $cloudbridge->remotentpserver($object_name);
                break;
            case "cbimportpublickey":
                require_once(APPPATH. "controllers/common/cloudbridge.php");
                $cloudbridge = new cloudbridge($this->current_request_type);
                $response = $cloudbridge->import_public_key($object_name);
                return $response;
            case "cbimportprivatekey":
                require_once(APPPATH. "controllers/common/cloudbridge.php");
                $cloudbridge = new cloudbridge($this->current_request_type);
                $response = $cloudbridge->import_private_key($object_name);
                return $response;
            case "cbimportpeerpublickey":
                require_once(APPPATH. "controllers/common/cloudbridge.php");
                $cloudbridge = new cloudbridge($this->current_request_type);
                $response = $cloudbridge->import_peer_public_key($object_name);
                return $response;
            case "cbimportpeerprivatekey":
                require_once(APPPATH. "controllers/common/cloudbridge.php");
                $cloudbridge = new cloudbridge($this->current_request_type);
                $response = $cloudbridge->import_peer_private_key($object_name);
                return $response;
            case "xenserver_login":
                require_once(APPPATH. "controllers/common/xenserver.php");
                $xenserver = new xenserver($this->current_request_type);
                $response = $xenserver->login();
                break;
            case "xenserver_custom_api":
                require_once(APPPATH. "controllers/common/xenserver.php");
                $xenserver = new xenserver($this->current_request_type);
                $response = $xenserver->custom_api($object_name);
                break;
            case "softlayer":
                require_once(APPPATH. "controllers/common/softlayer.php");
                $softlayer = new softlayer($this->current_request_type);
                $response = $softlayer->connect();
                break;
            case "amazon":
                require_once(APPPATH. "controllers/common/amazon.php");
                $amazon = new amazon($this->current_request_type);
                $response = $amazon->connect();
                break;
            case "is_clip":
                $response = $this->is_clip();
                break;
            case "is_cco":
                $response = $this->is_cco();
                break;
            case "clusternodeaddproxy":
                return $this->clusternodeaddproxy();
                break;
            case "remotenetscalers":
                return $this->remotenetscalers();
                break;
            case "clusterjoinproxy":
                return $this->clusterjoinproxy();
                break;
            case "removeclusternode":
                return $this->rmclusternode();
                break;
            case "remotemachinereboot":
                return $this->remotemachinereboot($object_name);
                break;
            case "remotefiledir":
                $this->check_permissions("shell");
                $response = $this->get_remote_file_browser();
                break;
            case "sdx":
                return $this->connect_to_sdx();
                break;
            case "remote_shell":
            	return $this->execute_shell_command();
            	break;
            case "regex_evaluator":
            	return $this->evaluate_regex();
            	break; 
            case "importlicensefiles":
                require_once(APPPATH . "controllers/common/menu.php");
                $menu = new menu();
                $menu->import_file("/nsconfig/license/", false);
                return;
            case "uploadwindowsonlineplugin":
                require_once(APPPATH . "controllers/common/menu.php");
                $menu = new menu();
                $menu->import_file("/var/wi/Clients/Windows/Online Plug-in/", false);
                return;
            case "uploadvartmp":
				// upload file to /var/tmp directory (used by import Signatures)
                require_once(APPPATH . "controllers/common/menu.php");
                $menu = new menu();
                $menu->import_file("/var/tmp/", false);
                return;
            case "uploadappfwprofile":
				// used by import appfw profile (used by import profile)
                require_once(APPPATH . "controllers/common/menu.php");
                $menu = new menu();
        		$this->set_import_filename_to_lowercase();	// change imported filename to lowercase and replace spaces with "%20"
                $menu->import_file("/var/tmp/", false);
                return;
            case "getappfwscanxsl":
				$response = $this->appfw_get_scan_xsl_files();
                break;
            case "uploadwindowsofflineplugin":
                require_once(APPPATH . "controllers/common/menu.php");
                $menu = new menu();
                $menu->import_file("/var/wi/Clients/Windows/Offline Plug-in/", false);
                return;
            case "upload_portal_icon":
                require_once(APPPATH . "controllers/common/menu.php");
                $menu = new menu();
                $menu->import_file("/var/netscaler/logon/", false);
                return;  
            case "uploadlinuxonlineplugin":
                require_once(APPPATH . "controllers/common/menu.php");
                $menu = new menu();
                $menu->import_file("/var/wi/Clients/Linux/Online Plug-in/", false);
                return;
            case "uploadlinuxofflineplugin":
                require_once(APPPATH . "controllers/common/menu.php");
                $menu = new menu();
                $menu->import_file("/var/wi/Clients/Linux/Offline Plug-in/", false);
                return;
            case "uploadmacintoshplugin":
                require_once(APPPATH . "controllers/common/menu.php");
                $menu = new menu();
                $menu->import_file("/var/wi/Clients/Macintosh/", false);
                return;
            case "importkeytabfiles":
                require_once(APPPATH . "controllers/common/menu.php");
                $menu = new menu();
                $menu->import_file("/nsconfig/krb/", false);
                return;
            case "installwipackage":
                require_once(APPPATH . "controllers/common/menu.php");
                $menu = new menu();
                $menu->import_file("/var/", false, true);
                return;
            case "import_system_upgrade_file":
                require_once(APPPATH . "controllers/common/menu.php");
                $menu = new menu();
                $menu->import_file("/var/nsinstall/", false, true);
                return;      
            case "import_system_backup_file":
                require_once(APPPATH . "controllers/common/menu.php");
                $menu = new menu();
                $menu->import_file("/var/ns_sys_backup/", false, true);
                return;                           
            case "upgrade_epa_libraries":
                require_once(APPPATH . "controllers/common/menu.php");
                $filename = basename($_FILES["import_file_name"]["name"]);
                $menu = new menu();
                $menu->import_file("/var/tmp/", false);
                return;
            case "import_responder_html_page":
                require_once(APPPATH . "controllers/common/menu.php");
                $menu = new menu();
                $menu->import_file("/var/tmp/", false);
                return;                
            case "extract_from_tar_and_move_files_for_epa":
                $data = $this->validate_arguments_for_post(array("object"), array(), false);
                $data = json_decode($data["object"] ,true);
                $filename = $data["upgradeepalibraries"]["name"];
                $epa_version = $data["upgradeepalibraries"]["EPALib"];
                $this->extract_from_tar("/var/tmp/",$filename, $epa_version);
                return;
            case "export_cert_for_wi":
                $data = $this->validate_arguments_for_post(array("object"), array(), false);
                $data = json_decode($data["object"] ,true);
                $filename = $data["export_cert"]["certificate"];
                $this->export_cert_for_wi($filename);
                return;
            case "load_xml_file":
                $data = $this->validate_arguments_for_post(array("object"), array(), false);
                $data = json_decode($data["object"] ,true);
                $filename = $data["load_xml_file"]["filename"];
                $dirname = $data["load_xml_file"]["dirname"];
                $this->load_xml_file($dirname, $filename);
                return;
            case "move_epa_files_to_dir":
                $data = $this->validate_arguments_for_post(array("object"), array(), false);
                $data = json_decode($data["object"] ,true);
                $dirname = $data["move_epa_files_to_dir"]["dirname"];
                $this->move_epa_files_to_dir($dirname);
                return;
            case "uploadfile":
                require_once(APPPATH . "controllers/common/menu.php");
                $menu = new menu();
                $menu->import_file($this->filter["dir"], false);
                return;
            case "uploadfile_access_interface":
                require_once(APPPATH . "controllers/common/menu.php");
                $menu = new menu();
                $menu->import_file("/var/vpn/vpns/", false);
                return;
            case "uploadfile_loginschema":
                require_once(APPPATH . "controllers/common/menu.php");
                $menu = new menu();
                $menu->import_file("/nsconfig/loginschema/", false);
                return;
            case "upload_apptemplate_file":
                require_once(APPPATH . "controllers/common/menu.php");
                $menu = new menu();
                $menu->import_file("/var/nstemplates/applications/", false);
                return;
            case "upload_lbtemplate_file":
                require_once(APPPATH . "controllers/common/menu.php");
                $menu = new menu();
                $menu->import_file("/var/nstemplates/entities/lb\ vserver/", false);
                return;
            case "upload_lbtemplate_deploymentfile":
                require_once(APPPATH . "controllers/common/menu.php");
                $menu = new menu();
                $menu->import_file("/var/nstemplates/entities/lb\ vserver/deployment_files/", false);
                return;
            case "upload_apptemplate_deploymentfile":
                require_once(APPPATH . "controllers/common/menu.php" );
                $menu = new menu();
                $menu->import_file("/var/nstemplates/applications/deployment_files/", false);
                return;
            case "upload_policyextension_file":
                require_once(APPPATH . "controllers/common/menu.php" );
                $menu = new menu();
                $menu->import_file("/var/download/extensions/", false);
                return;
            case "movelicensefiles":
                return $this->move_license_files();
            case "movecustomizationfiles":
                return $this->move_customization_files();
            case "copycustomthemefiles":
                return $this->copy_customtheme_files();
            case "deletcustomthemedir":
                return $this->delete_custom_theme_dir();
            case "deletcustomthemeimage":
                return $this->delete_custom_theme_image();
            case "createsymboliclink":
                return $this->create_symbolic_link();
            case "ipsec_logs":
            	return $this->ipsec_debug_logs($this->filter);
                break;
            case "createtmpfolder":
                return $this->create_tmp_folder();
                break;
            case "cbdashboardresrc":
                return $this->get_cb_dashboard_resrc();
                break;
            case "sslpolicy":
                $response = $this->get_sslpolicy($object_name,$arg_list,$query_params);
                break;
            case "importdnskey":
                require_once(APPPATH . "controllers/common/menu.php");
                $menu = new menu();
                $menu->import_file("/nsconfig/dns/", false);
                return;
            case "uploadtext":
            	return $this->prepare_text_upload();
            	break;
            case "read_webinterface_conf_file":
                $this->check_permissions("shell");
                return $this->read_webinterface_conf_file($this->filter["path"],$this->filter["filename"]);
            case "wiconfig":
            	return $this->prepare_wiconfig_file();
            	break;
            case "copyfile":
            	return $this->prepare_file_copy();
            	break;
            case "downloadasfile":
                return $this->download_as_file();
            case "ldap_retrieveattributes":
				require_once(APPPATH . "controllers/common/auth_utils.php");
				$data = $this->validate_arguments_for_post(array("object"), array(), false);
                $data = json_decode($data["object"] ,true);
                return $this->retrieve_ldap_attributes($data["ldap_retrieveattributes"]);
            case "read_file":
                $this->check_permissions("shell");
                return $this->read_file_function($this->filter["path"]);
            case "read_url":
                return $this->read_url_function($this->filter["path"]);
            case "check_internet_conn":
                return $this->check_internet_connection($this->args["path"]);
            case "filedownload_with_save":
                $this->check_permissions("shell");
                if(isset($this->filter["filename"]))
                    return $this->file_download($this->filter["path"],"");
            case "filedownload":
                $this->check_permissions("shell");
				if (isset($this->filter["remove"])) 
                {
					return $this->file_download($this->filter["path"],$this->filter["filename"], $this->filter["remove"]);
				}
                else
                {
                    if(isset($this->filter["filename"]))
                        return $this->file_download($this->filter["path"],$this->filter["filename"]);
                    else
                        return $this->file_download($this->filter["path"]);
				}
            case "filedownload_default_signatures":
                $this->check_permissions("shell");

                if(file_exists("/nsconfig/updated_signatures.xml")) //Check if there is an update on the default signatures
                    return $this->file_download("/nsconfig/updated_signatures.xml","updated_signatures.xml");
                else
                    return $this->file_download("/netscaler/default_signatures.xml","default_signatures.xml");
                
                break;
            case "filedownload_sgw_config":
                $this->check_permissions("shell");
                if(file_exists("/var/download/GatewayConfig.zip")) //Check if there is an update on the default signatures
                    return $this->file_download("/var/download/","GatewayConfig.zip");
                
                break;       
            case "batch_command":
                return $this->batch_config_download();
            case "mkdir":
                return $this->make_directory();
            case "halogincredential":
                return $this->halogincredential();
                break;
            case "execute_import_command_in_secondarynode":
                $response = $this->execute_import_command_in_secondarynode();
                break;
            case "execute_command_secondarynode":
                $response = $this->execute_command_secondarynode();
                break;
            case "importing_fipskey_in_secondarynode":
                $response = $this->importing_fipskey_in_secondarynode();
                break;
            case "downloading_file_from_secondarynode":
                return $this->downloading_file_from_secondarynode();
                break;
            case "copy_file_from_tmp_to_ssl":
                $data = $this->validate_arguments_for_post(array("object"), array(), false);
                $copy_request = $data["object"];
                $file_data = json_decode( $copy_request ,true);
                $tmp_file_name = $file_data["sslfipssimtarget"]["targetsecret"];
                $ssl_dir = $this->get_directory_path("/nsconfig/ssl");
                return $this->copy_file_from_tmp($ssl_dir,$tmp_file_name);
                break;
            case "enable_sslfipssimtarget":
                $response = $this->enable_sslfipssimtarget();
                break;
            case "uploading_certfile_to_remote_netscaler":
                return $this->uploading_certfile_to_remote_netscaler();
                break;
            case "ns_ftu_resource":
                $current_request_method = $_SERVER["REQUEST_METHOD"];
                if($current_request_method == "POST")
                    $response = $this->update_ftu_resource();
                else
                    $response = $this->get_ftu_resource();
                return;
            case "open_websocket":
                return $this->open_websocket_connection();
            case "close_websocket":
                return $this->close_websocket_connection();
            case "websocket_send":
                return $this->send_data_to_websocket();
            case "nspartition_systemuser_binding":
                $response = $this->nspartition_systemuser_binding($object_name, $query_params);
                break;
            case "vpneula": 
                $current_request_method = $_SERVER["REQUEST_METHOD"];

                if($current_request_method == "GET")
                {    
                    $response = $this->get_vpneula($object_name, $query_params);                
                }    
                else if($current_request_method == "POST")
                {    
                    $response = $this->update_vpneula(true);
                }    
                else if($current_request_method == "PUT")
                {    
                    $response = $this->update_vpneula(false);
                }    
                else if($current_request_method == "DELETE")
                {    
                    $response = $this->delete_vpneula($object_name);
                }    
                break;
            case "sslcertkey":
                $current_request_method = $_SERVER["REQUEST_METHOD"];

                if($current_request_method == "GET")
                {
                    return $this->get_segregrated_installed_certificates($query_params);
                    
                }
                break;
            case "sslkeyfiles":
                $current_request_method = $_SERVER["REQUEST_METHOD"];
                if($current_request_method == "GET")
                {    
                    return $this->get_sslfiles("sslkeyfiles", "sslkeyfiles", $query_params);
                }
                break;
            case "sslcsrfiles":
                $current_request_method = $_SERVER["REQUEST_METHOD"];
                if($current_request_method == "GET")
                {    
                    return $this->get_sslfiles("sslcsrfiles", "sslcsrfiles", $query_params);
                    
                }
                break;
            case "sslcertfiles":
                $current_request_method = $_SERVER["REQUEST_METHOD"];
                if($current_request_method == "GET")
                {    
                    return $this->get_sslfiles("sslcertfiles", "sslcertfiles", $query_params);
                    
                }
                break;
            case "sslcertfile_properties":
                return $this->get_sslfile_property($this->args["filename"]);
                break;
            default:
                $response = array("message" => "Invalid object name");
        }
        if($response != null && $object_type != "message" && isset($response[$object_type]))
        {
            $response["errorcode"] = 0;
            $response["message"] = "Done";
        }
        if(!isset($response["errorcode"]))
        {
            $response["errorcode"] = -1;
        }
        if($this->is_direct_invocation)
            return $response;
        if($response != null)
        {
            $this->send_response($response);
        }
    }

    // Converts request URI (URL access) or function parameters (PHP access) into arg_list
    // Ex: /rapi/appunit_details/lbv1?sessionid=abcdef to ["appunit_details", "lbv1?sessionid=abcdef"]
    private function get_arg_list($arg_list)
    {
        if(!$this->is_direct_invocation)
            $arg_list = array_slice(preg_split("/\//", $_SERVER["REQUEST_URI"], -1, PREG_SPLIT_NO_EMPTY), 1);

        $count_arg_list = count($arg_list);
        if($count_arg_list != 1 && $count_arg_list != 2) //obj_type & <obj_name>
            $this->show_404();

        return $arg_list;
    }

    // Removes query parameters from the last arg if present
    // Ex: ["appunit_details", "lbv1?sessionid=abcdef"] to ["appunit_details", "lbv1"]
    // Validates & returns all query parameters as key-value pairs - {"sessionid": "abcdef"}
    private function get_query_params(&$arg_list)
    {
        $query_params = array();
        $count_arg_list = count($arg_list);
        $last_arg = $arg_list[$count_arg_list - 1];
        if(strpos($last_arg, "?") === false)
            return $query_params;

        list($arg_list[$count_arg_list - 1], $query_string) = explode("?", $last_arg, 2);
        if(($query_params = $this->tokenize_string_to_key_value_arr($query_string, $this->VALID_QUERY_PARAMS, '&', '=', array("args", "filter"))) === false)
        {
            $this->print_error_for_api_request("Invalid query parameters");
            return false;
        }
        return $query_params;
    }

    // Returns the object type from arg_list
    private function validate_and_get_object_type($arg_list)
    {
        return urldecode($arg_list[0]);
    }

    // Returns the object name from arg_list only if applicable for a given object type
    private function validate_and_get_object_name($arg_list, $object_type)
    {
        $count_arg_list = count($arg_list);
        if(in_array($object_type, $this->VALID_OBJECT_TYPES_FOR_OBJECT_NAME) && $count_arg_list > 1) //count_arg_list>1 for get_by_name requests
        {
            if($count_arg_list == 2 && $arg_list[1] != "")
                return urldecode(urldecode($arg_list[1])); //urldecode twice for interface case 1%252F1 -> 1%2F1 -> 1/1
            else
                $this->show_404();
        } else if($count_arg_list != 1)
            $this->show_404();

        return null;
    }

    // Validate query parameters for a given object type
    private function validate_query_params($query_params, $object_type)
    {
        foreach($query_params as $key => &$value)
        {
            if(count($this->VALID_QUERY_PARAMS[$key]) != 0 && !in_array($object_type, $this->VALID_QUERY_PARAMS[$key]))
            {
                $this->print_error_for_api_request("Invalid query parameters");
                return false;
            }
            switch($key)
            {
                case "sessionid":
                    require_once(APPPATH . "controllers/common/utils.php");
                    if(!$this->session_started && (utils::setup_webstart_session($value, false) === false))
                    {
                        $this->print_error_for_api_request("Invalid query parameters");
                        return false;
                    }
                    $this->session_started = true;
                    break;
                case "args":
                    if(($args = $this->tokenize_string_to_key_value_arr($value, array_flip($this->VALID_ARGS[$object_type]))) === false)
                    {
                        $this->print_error_for_api_request("Invalid args in query parameters");
                        return false;
                    }
                    $this->args = $args;
                    break;
                case "filter":
                    if(($filter = $this->tokenize_string_to_key_value_arr($value, array_flip($this->VALID_FILTERS[$object_type]))) === false)
                    {
                        $this->print_error_for_api_request("Invalid filter in query parameters");
                        return false;
                    }
                    $this->filter = $filter;
                    break;
            }
        }
        return true;
    }

    // Given a string like "name1=value1&name2=value2", returns an array of key-value pairs (name1 => value1 etc)
    // If input string is invalid, returns false.
    private function tokenize_string_to_key_value_arr($query_params_str, $allowed_keys_map = array(), $first_level_tokenizer = ',', $second_level_tokenizer = ':', $no_decode_array = array())
    {
        $key_value_arr = array();
        $query_params_arr = explode($first_level_tokenizer, $query_params_str);
        $count_query_params = count($query_params_arr);
        $count_allowed_keys = count($allowed_keys_map);

        for($i = 0; $i < $count_query_params; $i++)
        {
            if(strpos($query_params_arr[$i], $second_level_tokenizer) === false)
                return false;

            list($key, $value) = explode($second_level_tokenizer, $query_params_arr[$i], 2);
            if($key == "" || $value == "" || ($count_allowed_keys > 0 && !isset($allowed_keys_map[$key])))
                return false;

            $val = in_array($key, $no_decode_array) ? $value : urldecode($value);
            $values = (strchr($val, ';') != NULL && !in_array($key, $no_decode_array)) ? explode(";", $val) : $val;
            $key_value_arr[urldecode($key)] = $values;
        }

        return $key_value_arr;
    }

    private function send_response($response)
    {
        $this->send_no_cache_headers();
        header(JSON_HEADER);
        ob_start("ob_gzhandler");
        print json_encode($response);
    } 

    private function system_info()
    {
        $system_info = array("system_info" => array(
            "username" => trim($_SESSION["username"]),
            "nsversion" => trim($_SESSION["nsversion"]),
            "hostname" => trim($_SESSION["nshostDesc"]),
            "nsbrand" => trim($_SESSION["nsbrandDesc"]),
            "nsmodel" => trim($_SESSION["ns_model"]),
            "aws_pin" => trim($_SESSION["ns_aws_pin"]),
            "oem_id" => trim($_SESSION["oemid"]),
            "is_sgw" => trim($_SESSION["ns_is_sgw"])
        ));
    
        $system_info = $this->get_ftu_info($system_info);
        $system_info = $this->get_partiton_info($system_info);
        $system_info = $this->get_ha_nodes($system_info);
                
        return $system_info;
    }

    private function get_ftu_info($system_info)
    {
        require_once(APPPATH. "controllers/common/remotefilebrowser.php");
        require_once(APPPATH . "controllers/common/rapi_utils.php");
        $remotefilebrowser = new remotefilebrowser($this->current_request_type);
        $license_dir_files = $remotefilebrowser->get_contents(array("path" => "/nsconfig/license/", "is_file" => "true", "rand" => $_SESSION['rand']));
        
        $this->execute_command("getnsip", array(NITRO_MODEL_COUNT_PARAM => "yes", "type" => "snip"));
        $snip_result = $this->get_model()->get_result();
        
        $system_info["system_info"]["licenses"] = (isset($license_dir_files["remotefiledir"]) && (count($license_dir_files["remotefiledir"]) > 0));
        $system_info["system_info"]["snipconfigured"] = (($snip_result["rc"] ==0) && ($snip_result["List"][0]["__count"] > 0));
        
        return $system_info;
    }
    
    private function get_partiton_info($system_info)
    {
        $all_partitions = $this->get_partition_data();
        if(sizeof($all_partitions) == 0)
            return $system_info;
        
        $system_info = $this->get_bound_partitons($system_info, $all_partitions);
        
        $system_info = $this->populate_default_partition($system_info, $all_partitions);
        
        return $system_info;
    }

    private function get_ha_nodes($system_info)
    {
        if($this->get_cluster_instance_id() != null)
            return $system_info;
        
        $ha_nodes = array();

        $this->execute_command("gethanode", array());
        $result = $this->get_model()->get_result();
        if($result["rc"] != 0 || count($result["List"]) == 0 || !isset($result["List"][0]["state"]))
            return $system_info;

        foreach($result["List"] as $ha_node)
        {
            array_push($ha_nodes, $ha_node);
        }        

        $system_info["system_info"]["ha_nodes"] = $ha_nodes;

        return $system_info;        
    }  
    
    public function is_partiton_configured_and_is_not_default_partition()
    {
        $system_info = array("system_info" => array());
        $system_info = $this->get_partiton_info($system_info);

        if(isset($system_info["system_info"]["availablepartitions"]) && count($system_info["system_info"]["availablepartitions"]) > 0 && $system_info["system_info"]["defaultpartition"] != "default")
            return true;

        return false;
    }

    private function populate_default_partition($system_info, $all_partitions)
    {
        $current_partition = $system_info["system_info"]["defaultpartition"];
        $partiton_info = $this->get_default_partition($all_partitions);

        if(sizeof($partiton_info) > 0)
        {
            $system_info["system_info"]["defaultpartition"] = $partiton_info["partitionname"];   
        }
        
        return $system_info;
    }
    
    private function get_bound_partitons($system_info, $all_partitions)
    {
        $partiton_info = array();
        {
            for ($i = 0; $i < count($all_partitions); $i++) 
            {
                if(isset($all_partitions[$i]["partitionname"]))
                {
                    $partiton_info[] = $all_partitions[$i]["partitionname"];
                }
            }
            
            sort($partiton_info);
            if($this->isSuperUser($_SESSION["username"]) == "true")
                array_unshift($partiton_info, "default");
        }
        
        // TODO: Remove this once it is fixed in CLI
        $system_info["system_info"]["defaultpartition"] = count($partiton_info) > 0 ? $partiton_info[0] : "";
        $system_info["system_info"]["availablepartitions"] = $partiton_info;
        
        return $system_info;
    }

    private function create_tmp_folder()
    {
        $data = $this->validate_arguments_for_post(array("object"), array(), false);
        $file_data = json_decode($data["object"], true);
        $tmp_folder_name = $file_data["createtmpfolder"]["name"];

        $path = "/tmp/" .$tmp_folder_name;

        require_once(APPPATH . "controllers/common/rapi_utils.php");
        if(($net_sftp = rapi_utils::get_sftp($sftp_response)) === true)
        {
            if(!file_exists(dirname($path)))
            {
                $this->recursive_mkdir($net_sftp, dirname($path));
            }

            $this->print_error_for_api_request("Done",0);
            return true;
        }

            $retval = $this->print_error_mess("TMP_FOLDER_CANNOT_BE_CREATED", false);
            $response["errorcode"] = -1;
            $response["message"] = $retval;
            print json_encode($response);

            return false;
        }

    private function download_as_file()
    {
        $filename = (isset($_POST['filename'])  && $_POST['filename'] !== "")? $_POST['filename'] : "ns_file";

        $content_type = (isset($_POST['content_type']) && $_POST['content_type'] !== "")? $_POST['content_type'] : "text/plain";
        
        $this->output->set_header("Cache-Control: ");
        $this->output->set_header("Content-Type:" .$content_type);
        
        if(isset($filename) && $filename !== "")
            $this->output->set_header('Content-Disposition:attachment; filename="'.$filename.'"');
        else
             $this->output->set_header('Content-Disposition:attachment;');
         
        $this->output->set_output($_POST['content']);
    }
    
    private function move_license_files()
    {

        $data = $this->validate_arguments_for_post(array("object"), array(), false);
        $file_data = json_decode($data["object"], true);
        $tmp_folder_name = $file_data["movelicensefiles"]["name"];

        $failed_files = "";
        $path = "/tmp/" .$tmp_folder_name;
        $file_copy_successful = true;

        require_once(APPPATH . "controllers/common/rapi_utils.php");
        if(($net_sftp = rapi_utils::get_sftp($sftp_response)) !== false)
        {
            $d = dir($path);

            while (false !== ($entry = $d->read()))
            {
                $filepath = "{$path}/{$entry}";
                if (is_file($filepath) && strpos($filepath,'.lic') !== false)
                {
                    $success = $net_sftp->put("/nsconfig/license/" .$entry , $filepath, NET_SFTP_LOCAL_FILE);

                    if($success === false)
                    {
                        $failed_files += strlen($failed_files) > 0 ? ", " : "";
                        $failed_files += $entry;
                        $file_copy_successful = false;
                    }
                    else
                    {
                        unlink($filepath);
                    }
                }
            }
        }

        if($file_copy_successful === false)
        {
            $this->print_error_for_api_request("File copy from " .$path ." to /nsconfig/licenses failed for following files : " . $failed_files, 999);
            return;
        }

        @rmdir($path);
        $this->print_error_for_api_request("Done",0);

        return;
    }
    
    private function move_customization_files()
    {

        $data = $this->validate_arguments_for_post(array("object"), array(), false);
        $file_data = json_decode($data["object"], true);
        $tmp_folder_name = $file_data["movecustomizationfiles"]["name"];
        $entry = $file_data["movecustomizationfiles"]["filename"];
        $failed_files = "";
        $path = "/var/nstmp";
        $file_copy_successful = true;

        require_once(APPPATH . "controllers/common/rapi_utils.php");
        if(($net_sftp = rapi_utils::get_sftp($sftp_response)) !== false)
        {
            $d = dir($path);
            $filepath = "{$path}/{$entry}";

            if (is_file($filepath))
            {
                $success = $net_sftp->put("/var/netscaler/logon/themes/" .$tmp_folder_name ."/custom_media/" .$entry , $filepath, NET_SFTP_LOCAL_FILE);
                
                if($success === false)
                {
                    $failed_files += strlen($failed_files) > 0 ? ", " : "";
                    $failed_files += $entry;
                    $file_copy_successful = false;
                }
                else
                {
                    $filenewpath = "/var/nstmp/customtmp";
                    rename($filepath, "/var/nstmp/customtmp");
                    unlink($filenewpath);
                }
            }            
        }

        if($file_copy_successful === false)
        {
            $this->print_error_for_api_request("Failed to copy the following file: " .$entry ."\nPlease try again.", 999);
            return;
        }

        $this->print_error_for_api_request("Done",0);

        return;
    }

    private function copy_customtheme_files()
    {
        $data = $this->validate_arguments_for_post(array("object"), array(), false);
        $file_data = json_decode($data["object"], true);
        $customthemepath = $file_data["copycustomthemefiles"]["customthemepath"];
        $basethemepath = $file_data["copycustomthemefiles"]["basethemepath"];

        require_once(APPPATH . "controllers/common/rapi_utils.php");
        if(($net_sftp = rapi_utils::get_sftp($sftp_response)) !== false)
        {
            $success = $net_sftp->put("/var/netscaler/logon/themes/" .$customthemepath , "/var/netscaler/logon/themes/" .$basethemepath, NET_SFTP_LOCAL_FILE);
        }
        if (!$success)
        {
            $this->print_error_for_api_request("Unable to copy theme file: " .$basethemepath, 257);
            return;
        }
        $this->print_error_for_api_request("Done",0);

        return ;   
    }

    private function delete_custom_theme_dir()
    {

        $data = $this->validate_arguments_for_post(array("object"), array(), false);
        $file_data = json_decode($data["object"], true);
        $tmp_folder_name = $file_data["deletcustomthemedir"]["name"];
        $path = "/var/netscaler/logon/themes/".$tmp_folder_name;
        
        require_once(APPPATH . "controllers/common/rapi_utils.php");
        if(($net_sftp = rapi_utils::get_sftp($sftp_response)) !== false)
        {
            $i = 0;
            $success = $net_sftp->_delete_recursive($path, $i);
        }
        if (!$success)
        {
            $this->print_error_for_api_request("Unable to delete Theme directory: " .$tmp_folder_name .". Please delete the directory manually.", 257);
            return;
        }
        $this->print_error_for_api_request("Done",0);

        return;
    }

    private function delete_custom_theme_image()
    {
        $data = $this->validate_arguments_for_post(array("object"), array(), false);
        $file_data = json_decode($data["object"], true);
        $theme_name = $file_data["deletcustomthemeimage"]["name"];
        $image_name = $file_data["deletcustomthemeimage"]["filename"];
        $path = "/var/netscaler/logon/themes/".$theme_name ."/custom_media";

        require_once(APPPATH . "controllers/common/rapi_utils.php");
        if(($net_sftp = rapi_utils::get_sftp($sftp_response)) !== false)
        {
            $filepath = "{$path}/{$image_name}";
            if(is_file($filepath)){
                    $i = 0;
                    $success = $net_sftp->delete($filepath);
                    if (!$success)
                    {
                        $this->print_error_for_api_request("Unable to delete Image file: " .$path .". Please delete the file manually.", 257);
                        return;
                    }
            }
        }
        $this->print_error_for_api_request("Done",0);

        return;
    }

    private function create_symbolic_link()
    {

        $data = $this->validate_arguments_for_post(array("object"), array(), false);
        $file_data = json_decode($data["object"], true);
        $target = $file_data["createsymboliclink"]["target"];
        $link = $file_data["createsymboliclink"]["linkname"];
        
        require_once(APPPATH . "controllers/common/rapi_utils.php");
        if(($net_sftp = rapi_utils::get_sftp($sftp_response)) !== false)
        {
            $success = $net_sftp->symlink($target, $link);
        }
        if (!$success)
        {
            $this->print_error_for_api_request("Unable to create symbolic link for target: " .$target, 257);
            return;
        }
        $this->print_error_for_api_request("Done",0);

        return;
    }

    private function is_clip()
    {
        $is_clip = false;
        $this->execute_command("getnsip", array("ipaddress" => $this->get_server_address()));
        $result = $this->get_model()->get_result();
        if($result["rc"] == 0 && count($result["List"]) == 1 && isset($result["List"][0]["iptype"]) &&
            in_array("CLIP", $result["List"][0]["iptype"]))
            $is_clip = true;

        return array("is_clip" => $is_clip);
    }

    private function get_sslpolicy($object_name,$arg_list, $query_params)
    {
        if(!(in_array($object_name, array("data","control"))))
                $this->show_404();
    
    	$is_count_call = false;
    	if(isset($query_params['count']))
    		$is_count_call = true;

        $data_action_array = array("NOOP","RESET","DROP");
        $control_action_array = array("CLIENTAUTH","NOCLIENTAUTH");
        
        $filtered_result = array();
        
        $decoded_name = "";
        if(isset($query_params["filter"]))
        {
            $name = preg_replace("/name:/", "", $query_params["filter"]);
            $decoded_name = urldecode($name);
        }
        $this->execute_command("getsslpolicy", array());
        $result = $this->get_model()->get_result();
	
        $pageno = (isset($query_params["pageno"])) ? ((int)$query_params["pageno"]) : 1;
        $pagesize = (isset($query_params["pagesize"])) ? (int)($query_params["pagesize"]) : 999999999;
        $policy_count = 0;
        for ($i = 0; $i < count($result["List"]); $i++) 
        {
            $action_name = $result["List"][$i]["action"];
    	    if((preg_match( "/ssl_nopolicy_action/",$action_name  )) > 0)
    	    {
                continue;
    	    }

            $is_predefined_control_action = in_array($action_name, $control_action_array);
            $is_predefined_data_action = in_array($action_name, $data_action_array);
	    
            if(strlen($decoded_name) > 0 && (preg_match( $decoded_name , $result["List"][$i]["name"])!=1))
                continue;
	    
            if($is_predefined_control_action && $object_name==="control") 
            {
                    if($policy_count >= (($pageno-1)*$pagesize) && $policy_count < ($pagesize*$pageno) && !$is_count_call)
                    {
                            array_push($filtered_result,$result["List"][$i]);
                    }
                    $policy_count++;
                
            }
            else if($is_predefined_data_action && $object_name==="data")
            {
                if($policy_count >= (($pageno-1)*$pagesize) && $policy_count < ($pagesize*$pageno) && !$is_count_call)
                {
                    array_push($filtered_result,$result["List"][$i]);
                }
                $policy_count++;
            }
            else 
            {
                $this->execute_command("getsslaction", array("name" => $action_name));
                $action_result = $this->get_model()->get_result();
                if(isset($action_result["List"][0]["clientauth"]) && ($action_result["List"][0]["clientauth"] == "DOCLIENTAUTH" 
                        || $action_result["List"][0]["clientauth"] == "NOCLIENTAUTH" ))
                {
                    if($object_name==="control")
                    {
                    if($policy_count >= (($pageno-1)*$pagesize) && $policy_count < ($pagesize*$pageno) && !$is_count_call)
                    {
                            array_push($filtered_result,$result["List"][$i]);
                    }
                    $policy_count++;
                    }
                    else continue;
                }
                else if($object_name==="data")
                {
                    if($policy_count >= (($pageno-1)*$pagesize) && $policy_count < ($pagesize*$pageno) && !$is_count_call)
                    {
                            array_push($filtered_result,$result["List"][$i]);
                    }
                    $policy_count++;
                }
            }
            
            if($policy_count >= ($pagesize*$pageno))
                break;
        }
        
        if($is_count_call === true)
        {
            $count_output = array();
            array_push($count_output,(array("__count" => $policy_count)));
            return array("sslpolicy" => $count_output);
        }

        return array("sslpolicy" => $filtered_result);
    }

    private function is_cco()
    {
        $is_cco = false;
        $this->execute_command("getclusterinstance", array());
        $result = $this->get_model()->get_result();
        if($result["rc"] == 0 && count($result["List"]) > 0)
        {
            foreach( $result["List"] as $cluster_node)
            {
               if($cluster_node["ipaddress"] ==  $this->get_http_host_ip() && $cluster_node["iscco"])
               {
                    $is_cco = true;
                    break;
               }
            }
        }

        return array("is_cco" => $is_cco);
    }

    private function clusternodeaddproxy()
    {
        $data = $this->validate_arguments_for_post(array("object"), array(), false);
        $cluster_node_add_request = $data["object"];
        $cluster_node = json_decode( $cluster_node_add_request ,true);

        if(!is_array($cluster_node))
            $this->show_404();

        $ip_to_connect = $this->get_nsip();
        if($ip_to_connect == null || $ip_to_connect == "")
        {
            $this->print_error_for_api_request("Failed to add cluster node.Unable to get NSIP" ,999);
            return;
        }

       $is_remote_node = $cluster_node["clusternode"]["ipaddress"] != $ip_to_connect;
       //If it is not Self Node
       if($is_remote_node)
       {
            $ip_to_connect = $this->get_clip();
            if($ip_to_connect == null || $ip_to_connect == "")
            {
                $this->print_error_for_api_request("Failed to add cluster node.Unable to get CLIP" ,999);
                return;
            }
       }
       else
       {
           $ip_to_connect = "127.0.0.1";
       }

       require_once(APPPATH. "controllers/common/neo_proxy.php");
       $request = new neo_proxy($ip_to_connect);
       $request->post(array(new request("post", $cluster_node, "Cluster node add")),$responses);
       $response = $responses[0]->get_response();

       $response["message"] = $cluster_node["clusternode"]["ipaddress"]  ." - "  .$response["message"];
       print json_encode($response) ;
       return;
    }

    private function remotemachinereboot()
    {
        $data = $this->validate_arguments_for_post(array("object"), array(), false);
        $remote_machine_reboot = $data["object"];
        $remote_machine_reboot = json_decode( $remote_machine_reboot ,true);

        if(!is_array($remote_machine_reboot))
            $this->show_404();

        if(!isset($remote_machine_reboot["remotemachinereboot"]["ipaddress"]))
        {
            $this->print_error_for_api_request("remote machine ip is required for reboot", -9999);
            return;
        }

        require_once(APPPATH. "controllers/common/neo_proxy.php");
        $remote_ip = $remote_machine_reboot["remotemachinereboot"]["ipaddress"];
        $username = $remote_machine_reboot["remotemachinereboot"]["username"];
        $password = $remote_machine_reboot["remotemachinereboot"]["password"];
        $request = new neo_proxy($remote_ip, $username, $password);
        $success = $request->post(array(new request("post", array( "reboot" => array("warm" => "true")), "Reboot")),$responses);

        if(!$success)
        {
            print json_encode($responses[0]->get_response()) ;
            return false;
        }

        $this->print_error_for_api_request("Done",0);
        return;
    }

    private function execute_shell_command()
    {
        $response = array();
        if(!$this->validate_session($response))
        {
            return $response;
        }
        
    	$data = $this->validate_arguments_for_post(array("object"), array(), false);

    	$command_data = json_decode($data["object"] ,true);
        $output = '';
        require_once(APPPATH . "controllers/common/rapi_utils.php");
        
        $suppress = true;
        if(isset($command_data["remote_shell"]["suppress"]))
            $suppress = $command_data["remote_shell"]["suppress"];
       	
        if(($net_ssh = rapi_utils::get_ssh($ssh_response)) !== false)
        {
            $net_ssh->enableQuietMode();
            if(!empty($command_data["remote_shell"]["command"]))
            {
                if($command_data["remote_shell"]["target"] === "shell")
                    $command = "shell '". $command_data["remote_shell"]["command"] ."'";
                else
                    $command = $command_data["remote_shell"]["command"];
                $output = $net_ssh->exec($command);
                $error_msg = trim($net_ssh->getStdError());
                $exit_status = $net_ssh->getExitStatus();
                
                // Remove redundant lines with CLI Done message and 0 bytes of 0 blocks message
                $lines = explode("\n",$output);
                $lines = array_slice($lines,1,-1);
                $output = implode("\n",$lines);
            }
        }
        else
        {
            $ssh_response["severity"] = "ERROR";
            print json_encode(rapi_utils::sanitize_response($ssh_response));
            return;
        }

    	$result = array();

        if($exit_status === 0 || $suppress === true)
    	{
            $result["errorcode"] = 0;
            $result["message"] = "Done";
            $result["severity"] = "NONE";
        }
        else
        {
            if(strpos(strtolower($error_msg), "warning") == 0)
            {
                $result["severity"] = "WARNING";
            }
            else
            {
                $result["severity"] = "ERROR";
            }
               
            $result["errorcode"] = $exit_status;
            $result["message"] = $error_msg;

        }

    	$result["remote_shell"] = array();
    	$result["remote_shell"]["prompt"] = $command_data["remote_shell"]["prompt"];
    	$result["remote_shell"]["output"] = $output;
    	print json_encode(rapi_utils::sanitize_response($result));

    	return;
    }
    
    private function evaluate_regex()
    {
        $response = array();
        if(!$this->validate_session($response))
        {
            return $response;
        }
        
    	$data = $this->validate_arguments_for_post(array("object"), array(), false);
    	$command_data = json_decode($data["object"], true);
        $regex = $command_data["regex_evaluator"]["regex"];
        $options = $command_data["regex_evaluator"]["options"];
        $subject = $command_data["regex_evaluator"]["subject"];
        $subject = str_replace("\xc2\xa0", " ", $subject);
        $pattern = '/' . $regex . '/' . $options;
        $result = FALSE;
        $exception_occured = false;
        $exception_message = "";
        $error_message = "";
        
        try
        {
            set_error_handler("exception_error_handler");
            $result = preg_match($pattern, $subject, $matches, PREG_OFFSET_CAPTURE);
        }
        catch(ErrorException $exception)
        {
            $exception_occured = true;
            $exception_message = $exception->getMessage();
        }
        restore_error_handler();

        if ((preg_last_error() == PREG_NO_ERROR) && $exception_occured)
        {
            $error_message = $exception_message;
        }
        else if (preg_last_error() == PREG_INTERNAL_ERROR)
        {
            $error_message = 'There is an internal error!';
        }
        else if (preg_last_error() == PREG_BACKTRACK_LIMIT_ERROR)
        {
            $error_message = 'Backtrack limit was exhausted!';
        }
        else if (preg_last_error() == PREG_RECURSION_LIMIT_ERROR)
        {
            $error_message = 'Recursion limit was exhausted!';
        }
        else if (preg_last_error() == PREG_BAD_UTF8_ERROR)
        {
            $error_message = 'Bad UTF8 error!';
        }
        else if (preg_last_error() == PREG_BAD_UTF8_ERROR)
        {
            $error_message = 'Bad UTF8 offset error!';
        }
        
        $match_information = array();
        $match_information["errorcode"] = 0;
    	$match_information["message"] = "Done";
    	$match_information["severity"] = "NONE";
        $match_information["match_information"] = array();

        if ($result === 1)
        {
            $count = count($matches);
            $matches_array = array();
            if ($count > 1)
            {
                for($i = 1; $i < $count; $i++)
                {
                    $match_object = $this->get_matched_information($i, $matches);
                    if ($match_object != null)
                        $matches_array[] = $match_object;
                    $substr_len = strlen($subject);
                    if (strlen($match_object["match_string"]) == $substr_len)
                        break;
                }
                $match_information["match_information"]["information"] = "The pattern matches with the subject string. Match groups are:";
                $match_information["match_information"]["matches"] = $matches_array;
            }
            else
            {
                $match_object = $this->get_matched_information(0, $matches);
                $matches_array[] = $match_object;
                $match_information["match_information"]["information"] = "The pattern matches with the subject string. No match groups were extracted.";
                $match_information["match_information"]["matches"] = $matches_array;
            }
        }
        else if ($result === 0)
        {
            $match_information["match_information"]["information"] = "The pattern does not match the subject string.";
        }
        else if ($result === FALSE)
        {
            $match_information["match_information"]["information"] = "Error in regex";
            $match_information["match_information"]["error"] = $error_message;
        }
        
        print json_encode($match_information);
        
    	return;
    }
    
    private function get_matched_information($index, $matches)
    {
        $value = $matches[$index];
        $substr = $value[0];
        $length = strlen($substr);
        if ($length == 0)
            return null;
        $start_index = $value[1] + 0;
        if ($start_index == -1)
            return null;
        $end_index = $start_index + $length;
        $match_object = array();
        $match_object["match_string"] = $substr;
        $match_object["start_index"] = $start_index;
        $match_object["end_index"] = $end_index;
        return $match_object;
    }
    
    private function get_ftu_resource()
    {
        $filedata = file_get_contents("/nsconfig/nstemplates/ns_ftu_resource.json");
        if(!isset($filedata))
            $filedata = "";
            
        $filedata = preg_replace("/\n/", "", $filedata);
        $response = (object) array( 'errorcode' => 0, 'message' => "DONE", 'severity' => "NONE", 'ns_ftu_resource' => (object) array('filedata' => ('' . $filedata . '')) );      
        $this->send_response($response);
        return;
    }
    
    private function update_ftu_resource()
    {
        $data = $this->validate_arguments_for_post(array("object"), array(), false);
        $file_data = json_decode($data["object"] ,true);
        $folder_name = "/nsconfig/nstemplates/";
        $file_name = "ns_ftu_resource.json";
        
        $handle = fopen("/var/nstmp/" . $file_name,'w+');
    	if (!$handle)
    	{
    		$this->print_error_for_api_request("Unable to create output file", 257);
    		return;
    	}
    	fwrite($handle, $file_data["ns_ftu_resource"]["filedata"]);
    	fclose($handle);
    	require_once(APPPATH . "controllers/common/rapi_utils.php");
    	if(($net_sftp = rapi_utils::get_sftp($sftp_response)) !== false)
    	{
    		if (is_file("/var/nstmp/{$file_name}"))
    		{
    			$success = $net_sftp->put("{$folder_name}/{$file_name}" , "/var/nstmp/{$file_name}", NET_SFTP_LOCAL_FILE);
    			if (!$success)
    			{
    				unlink("/var/nstmp/{$file_name}");
    				$this->print_error_for_api_request("Unable to create output file", 257);
    				return;
    			}
    		}
	}
		if (is_file("/var/nstmp/{$file_name}"))
			unlink("/var/nstmp/{$file_name}");
        $this->print_error_for_api_request("Done",0);
        return;
    }
    
    private function get_snips($nsips)
    {
        $nsips = $nsips["nsip"];
        $snips_vips_query_str = array();
        for($i = 0; $i < sizeof($nsips); $i++)
        {
            if ($nsips[$i]["type"] == "SNIP" || $nsips[$i]["type"] == "VIP")
            {
                array_push($snips_vips_query_str, $nsips[$i]["ipaddress"]);
            }
        }
        return $snips_vips_query_str;
    }
    
    private function read_webinterface_conf_file($path,$filename)
    {
        $file_path = "/var/wi/tomcat/webapps{$path}WEB-INF/{$filename}";
        return $this->read_file_function($file_path);
    }
    
    private function prepare_wiconfig_file()
    {
        $data = $this->validate_arguments_for_post(array("object"), array(), false);
        $file_data = json_decode($data["object"] ,true);
        
        $filedata = $file_data["wiconfig"]["filedata"];
        $filename = $file_data["wiconfig"]["filename"];
        $filedir = $file_data["wiconfig"]["filedir"];
        
        $handle = fopen("/tmp/{$filename}",'w+');
    	if (!$handle)
    	{
    		$this->print_error_for_api_request("Unable to create output file", 257);
    		return;
    	}
    	fwrite($handle,$filedata);
    	fclose($handle);
    	require_once(APPPATH . "controllers/common/rapi_utils.php");
    	if(($net_sftp = rapi_utils::get_sftp($sftp_response)) !== false)
    	{
            if (is_file("{$filedir}/{$filename}.4"))
            {
                unlink("{$filedir}/{$filename}.4");
            }
            if (is_file("{$filedir}/{$filename}.3"))
            {
                $this->move_and_check_file("{$filedir}/{$filename}.4", "{$filedir}/{$filename}.3", $net_sftp);
                unlink("{$filedir}/{$filename}.3");
            }
            if (is_file("{$filedir}/{$filename}.2"))
            {
                $this->move_and_check_file("{$filedir}/{$filename}.3", "{$filedir}/{$filename}.2", $net_sftp);
                unlink("{$filedir}/{$filename}.2");
            }
            if (is_file("{$filedir}/{$filename}.1"))
            {
                $this->move_and_check_file("{$filedir}/{$filename}.2", "{$filedir}/{$filename}.1", $net_sftp);
                unlink("{$filedir}/{$filename}.1");
            }
            if (is_file("{$filedir}/{$filename}.0"))
            {
                $this->move_and_check_file("{$filedir}/{$filename}.1", "{$filedir}/{$filename}.0", $net_sftp);
                unlink("{$filedir}/{$filename}.0");
            }
            if (is_file("{$filedir}/{$filename}"))
            {
                $this->move_and_check_file("{$filedir}/{$filename}.0", "{$filedir}/{$filename}", $net_sftp);
            }
            if (is_file("/tmp/{$filename}"))
            {
                $this->move_and_check_file("{$filedir}/{$filename}","/tmp/{$filename}", $net_sftp);
                unlink("/tmp/{$filename}");
            }
	}
	    
	$this->print_error_for_api_request("Done", 0);
        return ;
    }
    
    private function prepare_file_copy()
    {
        $data = $this->validate_arguments_for_post(array("object"), array(), false);
        $file_data = json_decode($data["object"] ,true);
        $filename = $file_data["copyfile"]["filename"];
        $filedir_dest = $file_data["copyfile"]["filedir_dest"];
        $filedir_src = $file_data["copyfile"]["filedir_src"];
       
    	require_once(APPPATH . "controllers/common/rapi_utils.php");
    	if(($net_sftp = rapi_utils::get_sftp($sftp_response)) !== false)
    	{
            if (is_file("{$filedir_src}/{$filename}"))
            {
                $this->move_and_check_file("{$filedir_dest}/{$filename}", "{$filedir_src}/{$filename}", $net_sftp);
            }
	}   
	$this->print_error_for_api_request("Done", 0);
        return ;
    }
    
    private function prepare_text_upload()
    {
    	$data = $this->validate_arguments_for_post(array("object"), array(), false);
    	$file_data = json_decode($data["object"] ,true);
    	$filedir = $file_data["uploadtext"]["filedir"];

		if (isset($file_data["uploadtext"]["double_encoded"])) 
        {
			$filedata = urlDecode(urlDecode($file_data["uploadtext"]["filedata"]));	// do double decode
		} 
        else 
        {
    		$filedata = $file_data["uploadtext"]["filedata"];
		}

    	$filename = trim($file_data["uploadtext"]["filename"]);
        $overwrite = ( isset($file_data["uploadtext"]["overwrite"]) && $file_data["uploadtext"]["overwrite"] == "false")? false : true;

        // Adding a trailing slash for filedir, if doesn't exist
        if ( substr($filedir, strlen($filedir) -1) != "/")
        {
            $filedir = $filedir."/";
        }   

        // If filename starts with slash, check if filedir is the prefix for filename
    	if ( strpos($filename,"/") === 0 )
        {
            $pos = strpos($filename,$filedir);
            if ( !($pos === FALSE) && $pos === 0)
            {
                $partitionpos = strrpos($filename,"/");
                $filedir = substr($filename, 0 , $partitionpos);
                $filename = substr($filename, $partitionpos + 1);
            }
            else
            {
                $this->print_error_for_api_request("Output filepath should be under the default directory ".$filedir, 257);
                return;
            }
        }
        // If filename has a slash inside the string reassign fildir and filename
        else
        {
            if ( strpos($filename, "/") > 0 )
            {
                $partitionpos = strrpos($filename,"/");
                $filedir = $filedir.substr($filename, 0 , $partitionpos);
                $filename = substr($filename, $partitionpos + 1);
            }
        }

        if (empty($filedir) || empty($filename) || empty($filedata))
    	{
    		$this->print_error_for_api_request("Some parameters are empty. Unable to create output file", 257);
    		return;
    	}

        // Check if file already exists, if yes, throw error
        if( is_file($filedir.$filename) && !$overwrite)
        {
            $this->print_error_for_api_request("Cannot create output file. File already exists", 1642);
            return;
        }
        
        // Check if sub directory exists, if not create
        if( !is_dir($filedir))
        {
            mkdir($filedir, 0744, true);
        }

        $tmpfilename = "/var/tmp/{$filename}";
        unlink($tmpfilename);	// make sure the file does not exist in /var/tmp
        $handle = fopen($tmpfilename,'w+');

    	if (!$handle)
    	{
    		$this->print_error_for_api_request("Unable to create output file", 257);
    		return;
    	}
    	fwrite($handle,$filedata);
    	fclose($handle);

        // Use sftp to move the file from /var/tmp to filedir if filedir is not /var/tmp 
        if($filedir != "/var/tmp/")
        {
            require_once(APPPATH . "controllers/common/rapi_utils.php");
        	if(($net_sftp = rapi_utils::get_sftp($sftp_response)) !== false)
        	{
        		if (is_file($tmpfilename))
        		{
        			$success = $net_sftp->put("{$filedir}/{$filename}" , $tmpfilename, NET_SFTP_LOCAL_FILE);
        			if (!$success)
        			{
        				unlink($tmpfilename);
        				$this->print_error_for_api_request("Unable to move output file", 257);
        				return;
        			}
        		}
    		}
    		if (is_file($tmpfilename))
    			unlink($tmpfilename);
        }

		$this->print_error_for_api_request("Done", 0);

    	return;
    }
    
    private function rmclusternode()
    {
        $data = $this->validate_arguments_for_post(array("object"), array(), false);
        $cluster_node_delete_request = $data["object"];
        $cluster_node_delete_request = json_decode($cluster_node_delete_request ,true);
        
        if(!is_array($cluster_node_delete_request))
            $this->show_404();
            
        $is_clip = $this->is_clip();
        if($is_clip["is_clip"] == false)
        {
            $this->print_error_for_api_request("Please connect to cluster ip address to remove a cluster node",999);
            return;   
        }

        $cluster_instance_id = $this->get_cluster_instance_id();
        if($cluster_instance_id == null)
        {
            $this->print_error_for_api_request("No cluster instances found on the appliance" ,999);
            return;
        }
        
        require_once(APPPATH. "controllers/common/neo_proxy.php");
        $remote_node_ip = $cluster_node_delete_request["clusternode"]["ipaddress"];
        $username = $cluster_node_delete_request["clusternode"]["username"];
        $password = $cluster_node_delete_request["clusternode"]["password"];
        
        $request = new neo_proxy($remote_node_ip, $username, $password);
        $req_obj = new request("delete", "", "Cluster instance delete");
        $req_obj->set_additional_command_parms("clusterinstance/" .$cluster_instance_id);
        $success = $request->delete(array($req_obj) ,$responses);
        $response_array = $responses[0]->get_response();

        $unable_to_login = preg_match( "/^Unable to login to/" , $response_array["message"]) ;
        
        if(($success == false) || $response_array["errorcode"] != 0)
        {
            $this->print_error_for_api_request("Unable to delete cluster instance from " .$remote_node_ip ." reason: " .$response_array["message"],999);
            return;
        }
        else
        {
            $save_request = new request("post", array( "params"=> array( "action" => "save"), "nsconfig" => array()), "Save Configuration") ;
            $save_request->set_timeout($this->extended_timeout);
            $success  = $request->post(array($save_request) ,$responses);
        }

        $this->print_error_for_api_request("Done",0);
        return;
    }
    
    private function clusterjoinproxy()
    {
        $data = $this->validate_arguments_for_post(array("object"), array(), false);
        $cluster_node_add_request = $data["object"];
        $cluster_node = json_decode( $cluster_node_add_request ,true);

        if(!is_array($cluster_node))
            $this->show_404();

        $ip_to_connect = $this->get_clip();
        if($ip_to_connect == null || $ip_to_connect == "")
        {
            $this->print_error_for_api_request("Failed to add cluster node.Unable to get CLIP" ,999);
            return;
        }

        $nsip = $this->get_nsip();
        if($nsip == null || $nsip == "")
        {
            $this->print_error_for_api_request("Failed to add cluster node.Unable to get NSIP" ,999);
            return;
        }

        $is_remote_node = $cluster_node["joinclusternode"]["ipaddress"] != $nsip;
        if($is_remote_node)
        {
            $remote_netscaler_ip = $cluster_node["joinclusternode"]["ipaddress"];
            $username = $cluster_node["joinclusternode"]["username"];
            $password = $cluster_node["joinclusternode"]["password"];
            $localnodepassword = $cluster_node["joinclusternode"]["localnodepassword"];

            require_once(APPPATH. "controllers/common/neo_proxy.php");
            $join_request = new request("post", array( "params"=> array( "action" => "join") , "cluster" => array("clip" => $ip_to_connect,"password" =>$localnodepassword)), "Cluster node join") ;
            $save_request = new request("post", array( "params"=> array( "action" => "save"), "nsconfig" => array()), "Save Configuration") ;
            $save_request->set_timeout($this->extended_timeout);
            $join_request->set_timeout($this->extended_timeout);
            $success  = $this->process_join_request($remote_netscaler_ip, array($join_request , $save_request), $username, $password);

            if($success)
                $this->print_error_for_api_request("Done", 0);
       }
       else
       {
            $this->print_error_for_api_request("Done", 0);
       }

       return;
    }

    private function process_join_request($netscaler_ip, $requests, $username, $password)
    {
        require_once(APPPATH. "controllers/common/neo_proxy.php");
        $request = new neo_proxy($netscaler_ip, $username, $password);
        $success = $request->post($requests ,$responses);

        if(!$success)
        {
            print json_encode($responses[0]->get_response()) ;
            return false;
        }

        foreach($responses as $response)
        {
           $response_array = $response->get_response();
           if($response_array["errorcode"] != 0)
           {
              $response_array["message"] = $netscaler_ip ." - " .$response_array["message"];
              print json_encode($response_array) ;
              return false;
           }
        }

        return true;
    }

    private function remotenetscalers()
    {
        $data = $this->validate_arguments_for_post(array("object"), array(), false);
        $iprange_request = $data["object"];

        $iprange= json_decode($iprange_request, true);

        if(!is_array($iprange))
            $this->show_404();

        $username = $iprange["remotensversion"]["username"];
        $password = $iprange["remotensversion"]["password"];
        $localnodepassword = $iprange["remotensversion"]["localnodepassword"];
        $default_backplane = $iprange["remotensversion"]["defaultbackplane"];
        $cluster_node_ids = $this->get_cluster_node_ids();
        
        $ips = explode(".",  $iprange["remotensversion"]["ip"]);
        $ip_str = $ips[0] . "."  .$ips[1] . "."  . $ips[2];

        $return_array = array("errorcode" => "0", "message" => "Done" , "remotensversion" => "") ;
        $return_array["remotensversion"] = array();

        $cluster_nodes = $this->get_cluster_nodes();
        $this->get_clip($cluster_nodes);

        set_time_limit((6* ($iprange["remotensversion"]["range"] -$ips[3])));

        if(($iprange["remotensversion"]["range"] -  $ips[3] )> 32)
        {
            $this->print_error_for_api_request("Maximum of 32 netscalers can be discovered per request" ,999);
            return;
        }

        for($i = $ips[3]; $i<=$iprange["remotensversion"]["range"]; $i++)
        {
            $ip_val = $ip_str . "." . $i;
            if(in_array($ip_val,$cluster_nodes))
                continue;
            require_once(APPPATH. "controllers/common/neo_proxy.php");
            $request = new neo_proxy($ip_val, $username, $password);
            $requests = array(new request("getInterface", null, "Get Interface"),new request("getclusterinstance", null, "Get cluster instance"));
            $success = $request->get($requests ,$responses);

            if(!$success)
                continue;

            $response_array = $responses[1]->get_response();
            if($response_array["errorcode"] != 0 || isset($response_array["clusterinstance"]))
                continue;

            $response_array = $responses[0]->get_response();
            if($response_array["errorcode"] == 0 )
            {
                $response["backplane_data"] = $this->get_valid_interfaces($response_array["Interface"]);
                $response["ip"] = $ip_val;
                $response["backplane"] = "";

                if($default_backplane != "" && in_array($default_backplane, $response["backplane_data"]))
                        $response["backplane"] =   $default_backplane;

                $response["nodeid"] = $this->get_next_node_id($cluster_node_ids);
                $return_array["remotensversion"][] = $response;
            }
        }

        print json_encode($return_array) ;
        return;
    }
    
    private function get_next_node_id(&$cluster_node_ids)
    {
        for($v = 0; $v <= 31; $v++)
        {
            if(in_array($v."", $cluster_node_ids) == false)
            {
                $cluster_node_ids[] = $v;
                return $v;
            }
        }

        return -1;    
    }
    
    private function get_cluster_node_ids()
    {
        $this->execute_command("getclusternode", array());
         $result = $this->get_model()->get_result();
        if($result["rc"] != 0 || count($result["List"]) == 0 || !isset($result["List"][0]["state"]))
            return array();

        $count = 0;
        $cluster_nodes = null ;
        foreach( $result["List"] as $cluster_node)
        {
            $cluster_nodes[$count++] = $cluster_node["nodeid"];
        }

        return $cluster_nodes;
    }

    private function connect_to_sdx()
    {    
        require_once(APPPATH . "controllers/common/cloudbridge.php"); 
        $cloudbridge = new cloudbridge($this->current_request_type);
        if(!isset($_SESSION["remote_sdx_ip"]) && !($_SERVER['REQUEST_METHOD'] == "GET"))
        {       
            $data = $this->validate_arguments_for_post(array("object"), array(), false);
            $sdx_ip= json_decode($data["object"], true);        
            $cloudbridge->set_session_properties($sdx_ip["sdx"]);
        }

        $proxy = $cloudbridge->get_proxy();         
        if($proxy == null)
            return;
        
        $requests = array(new request("getns", null, "Get NetScalers"));
        $success = $proxy->get($requests ,$responses, true);
        
        if(!$success)
        {
            print json_encode(array("errorcode" => "-1", "message" => "Cannot connect. Please provide valid SDX details." , "severity" => "ERROR"));
            return;
        }        
            
        foreach ($responses as $response)
        {                                               
            if($response["errorcode"] != 0 || !isset($response["ns"]))
                continue;

            foreach ($response["ns"] as $response_elem)
            {
                $json= array("ipaddress" => $response_elem["ip_address"]);
                $result_obj[] = $json;
            }
        }
        $return_array = array("errorcode" => "0", "message" => "Done" , "sdx" => $result_obj) ;
        
        print json_encode($return_array); 
        return;
    }

    private function get_cb_dashboard_resrc()
    {
        $result_tnl_IPSEC = array();
        $result_cb_dashboard = array();
        $this->execute_command("getiptunnel",  array());
        $result_iptunnel = $this->get_model()->get_result();
        if($result_iptunnel["rc"] == 0)
            $result_tnl_IPSEC += $result_iptunnel["List"];

        $this->execute_command("getnspbr",  array());
        $result_netbridge = $this->get_model()->get_result();
        if($result_netbridge["rc"] == 0)
            $result_cb_dashboard = $result_netbridge["List"];
            
        foreach ($result_tnl_IPSEC as $iptunnel)
        {
            if (isset($iptunnel["protocol"]) && $iptunnel["protocol"]== "IPSEC")
                array_push($result_cb_dashboard, array("name" => $iptunnel["sysname"], "tunnel" => $iptunnel["sysname"]));
        }
        print json_encode(array('errorcode' => 0, 'message' => 'Done', 'severity' => 'NONE', "nspbr" => $result_cb_dashboard));

        return ;        
    }
    
    private function filter_ipsec_logs(&$debug_filters)
    {
        $cmd = "";
        foreach ($debug_filters as $key => $value)
        {	
            $debug_filters[$key] = substr($debug_filters[$key], 1, strlen($debug_filters[$key])-2);
            if ($key!="tunnel_name")
            {
                $cmd = $cmd . (strlen($cmd) == 0 ? "" : "|");
                $cmd = $cmd . $debug_filters[$key];
            }
        }
        
        if (strlen($cmd) == 0)
            return null;
        
        require_once(APPPATH . "controllers/common/rapi_utils.php");
        
        if(($net_ssh = rapi_utils::get_ssh($ssh_response)) !== false)
        {
            $sanitized_cmd = escapeshellarg($cmd);
            $output = $net_ssh->exec("shell grep ".$sanitized_cmd." /tmp/iked.log");
            $lines = explode("\n",$output);
            $iked_debug = array_slice($lines,1,-1);
        }

        return $iked_debug;
    }
    
    private function ipsec_debug_logs($debug_filters)
    {
        $is_data_formatted = false;
        try
        {
            $this->output->set_header("Content-type: application/javascript");
            exec("cat /tmp/iked.debug >> /tmp/iked.log & sleep 3 ; kill $!");
            if (isset($debug_filters))
            {
                $iked_debug = $this->filter_ipsec_logs($debug_filters);
                if ($iked_debug == null)
                    $iked_debug = file_get_contents("/tmp/iked.log");
                else
                    $is_data_formatted = true;
            }
            else
                $iked_debug = file_get_contents("/tmp/iked.log");
        }
        catch (Exception $ex)
        {
            print json_encode(array('errorcode' => -1, 'message' => 'Error fetching IPSec logs', 'severity' => 'ERROR'));         
            return;
        }
        
        if (!isset($iked_debug) || sizeof($iked_debug) <= 0)
        {
            print json_encode(array("errorcode" => "-1", "message" => "No contents in file" , 'severity' => "ERROR"));
            return;
        }
        
        $result_obj = array();
        
        if ($is_data_formatted)
            $rows = $iked_debug;     
        else
            $rows = explode("\n", $iked_debug);

        $iptunnel_list = $this->get_tunnel_name();
        
        foreach($rows as $row => $row_data)
        {
            $tunnel_ips = array();
            if (strpos ($row_data, "PROTO") > 0)
            {                
                $tnlip_matches = array();
                $tnlip_regex = "/(([1-9]?\d|1\d\d|2[0-4]\d|25[0-5])[\.])(([1-9]?\d|1\d\d|2[0-4]\d|25[0-5])[\.])(([1-9]?\d|1\d\d|2[0-4]\d|25[0-5])[\.])(([1-9]?\d|1\d\d|2[0-4]\d|25[0-5])(?=\D))/";
                preg_match_all($tnlip_regex, $row_data, $tnlip_matches);
               
                if (!isset($tnlip_matches[0]) || sizeof($tnlip_matches[0]) < 2)
                    continue;
                
                /// FInd the position of the remote ip in $row_data, and then from there find the position of ":", following the ":" will be the message.
                $remote_ip_pos = strpos($row_data, $tnlip_matches[0][1]);
                $row_data_after_remote_ip = substr($row_data, $remote_ip_pos + strlen($tnlip_matches[0][1]));
                $pos = strpos ($row_data_after_remote_ip, ":");
                $message = substr($row_data_after_remote_ip, $pos+1);
                
                $tunnel_name="";
                
                $tnl_exists_in_config = false;                    
                foreach ($iptunnel_list as $iptunnel)
                {
                    if ($tnlip_matches[0][1] == $iptunnel["remote"])
                    {
                        $tunnel_name = $iptunnel["sysname"];
                        $tnl_exists_in_config = true;
                        break;
                    }
                }
                if (!$tnl_exists_in_config)
                    continue;
                
                $logs_data = array("tunnel_name" => $tunnel_name, "local" => $tnlip_matches[0][0], "remote" => $tnlip_matches[0][1], "message" => $message);              
                
                $is_match = true;
                foreach (array_keys($debug_filters) as $key)
                {
                    if ($debug_filters[$key] != $logs_data[$key])
                    {
			if ($key=="message" && strpos($logs_data[$key], $debug_filters[$key]) >= 0)
			    continue;
                        $is_match = false;
                        break;                        
                    }
                }
                
                if (!$is_match)
                    continue;
                                                         
                $result_obj[] = $logs_data;                
            }
            $return_array = array("errorcode" => "0", "message" => "Done" , "ipsec_logs" => $result_obj) ;            
        }
        print json_encode($return_array);
        return;
    }

    private function get_valid_interfaces($result)
    {
        $index = 0;
        $interfaces = array();
        foreach( $result as $interface)
        {
          if(stristr($interface["devicename"], "LO") == false)
          {
               $interfaces[$index++] =  $interface["id"];
          }
        }
        return $interfaces;
    }

    private function get_tunnel_name()
    {
        $this->execute_command("getiptunnel",  array());
        $result = $this->get_model()->get_result();
        if($result["rc"] != 0 || count($result["List"]) == 0)
            return null;
        return $result["List"];
    }
    
    private function get_nsip()
    {
        $this->execute_command("getnsip",  array("type" => "nsip"));
        $result = $this->get_model()->get_result();
        if($result["rc"] != 0 || count($result["List"]) == 0)
            return null;
        return $result["List"][0]["ipaddress"];
    }
    
    private function get_nsip6()
    {
        $this->execute_command("getnsip6",  array());
        $result = $this->get_model()->get_result();
        if($result["rc"] == 0 && count($result["List"]) > 0)
        {
            foreach( $result["List"] as $nsip6_obj )
            {
                if(in_array("NSIP", $nsip6_obj["iptype"], TRUE) && $nsip6_obj["scope"] == "global")
                {
                    $pos = strpos($nsip6_obj["ipv6address"],"/");
                    return substr($nsip6_obj["ipv6address"],0,$pos);
                }
            }
        }
        return null;
    }

    private function get_clip(&$cluster_nodes  = null)
    {
        $this->execute_command("getnsip", array());
        $result = $this->get_model()->get_result();
        if($result["rc"] != 0 || count($result["List"]) == 0)
            return;

        $clip = null ;
        foreach( $result["List"] as $nsip)
        {
           if(isset($nsip["iptype"]) && in_array("CLIP", $nsip["iptype"]))
            {
                if($cluster_nodes != null)
                    $cluster_nodes[] = $nsip["ipaddress"];
                else
                    return $nsip["ipaddress"];
            }
        }

        return $cluster_nodes;
    }

    private function get_cluster_instance_id()
    {

        $this->execute_command("getclusterinstance", array());
        $result = $this->get_model()->get_result();

        if($result["rc"] != 0 || count($result["List"]) == 0 || count($result["List"]) != 1)
            return array();

        if(isset($result["List"][0]["clid"]))
            return $result["List"][0]["clid"];

        return null;
    }

    private function get_cluster_node($nodeid)
    {
        $this->execute_command("getclusternode", array());
         $result = $this->get_model()->get_result();
        if($result["rc"] != 0 || count($result["List"]) == 0 || !isset($result["List"][0]["state"]))
            return array();

        $count = 0;
        $cluster_nodes = null ;
        foreach( $result["List"] as $cluster_node)
        {
             if($cluster_node["nodeid"] == $nodeid)
             {
                return $cluster_node;
             }
        }

        return null;
    }

    private function get_cluster_nodes()
    {
        $this->execute_command("getclusternode", array());
         $result = $this->get_model()->get_result();
        if($result["rc"] != 0 || count($result["List"]) == 0 || !isset($result["List"][0]["state"]))
            return array();

        $count = 0;
        $cluster_nodes = null ;
        foreach( $result["List"] as $cluster_node)
        {
            $cluster_nodes[$count++] = $cluster_node["ipaddress"];
        }

        return $cluster_nodes;
    }

    private function read_url_function($path)
    {
        $response = array();
        if(!$this->validate_session($response))
        {
            return $response;
        }
        $http_request = new HttpRequest($path, HttpRequest::METH_GET);
        try
        {
            $http_message = $http_request->send();
            $http_errorcode = $http_request->getResponseCode();
            $resp = $http_message->getBody();
            $resp_code = $http_message->getResponseCode();
            
            $filedata = $resp;
            $output = (object) array( 'errorcode' => 0, 'message' => "DONE", 'severity' => "NONE", 'read_url' => (object) array('response' => $filedata) );      
            print json_encode($output);
            return;
        
        } catch (HttpException $ex) {
            $this->print_error_for_api_request("No Internet Connection on the Appliance", 143);
        }
    }

    private function check_internet_connection($path)
    {
        $response = array();
        if(!$this->validate_session($response))
        {
            return $response;
        }
        $http_request = new HttpRequest($path, HttpRequest::METH_GET);
        try
        {
            $http_message = $http_request->send();
            $http_errorcode = $http_request->getResponseCode();
            
            $output = (object) array( 'errorcode' => 0, 'message' => "DONE", 'severity' => "NONE", 'check_internet_conn' => (object) array('response' => "Success"));      
            print json_encode($output);
            return;    
        } catch (HttpException $ex) {
            $this->print_error_for_api_request("No Internet Connection on the Appliance", 143);
        }
    }
    
    private function retrieve_ldap_attributes($data)
    {
        $response = array();
        if(!$this->validate_session($response))
        {
            return $response;
        }
        
        $output = auth_utils::ldap_retrieve_attributes($data);
        if($output != false)
            $this->output->set_output(json_encode($output));  
    }
    
    private function read_file_function($path)
    {
        $filedata = file_get_contents($path);
        $output = (object) array( 'errorcode' => 0, 'message' => "DONE", 'severity' => "NONE", 'read_file' => (object) array('response' => $filedata) );      
        $this->output->set_output(json_encode($output));   
    }

    // remove=1 to remove the path after download
    private function file_download($path, $filename, $remove=0)
    {
        $response = array();
        if(!$this->validate_session($response))
        {
            return $response;
        }
        
        if(!isset($filename))
            $filename = basename($path);

        require_once(APPPATH. "controllers/common/rapi_utils.php");        
        rapi_utils::download($path, $filename);

        if($remove) 
            unlink($path);
    }
    
    private function batch_config_download()
    {
        $response = array();
        if(!$this->validate_session($response))
        {
            return $response;
        }
        $this->check_permissions("shell");
    	$data = $this->validate_arguments_for_post(array("object"), array(), false);
        
        $batch_command = json_decode($data["object"] ,true);
        $commands = $batch_command["batch_command"]["commands"];
        $handle = fopen("/tmp/batch_commands",'w+');
    	if (!$handle)
    	{
    		$this->print_error_for_api_request("Unable to create output file", 257);
    		return;
    	}
    	fwrite($handle,$commands);
    	fclose($handle);
        
        $this->print_error_for_api_request("Done",0);
        return ;
    }
    
    private function make_directory()
    {
        $response = array();
        if(!$this->validate_session($response))
        {
            return $response;
        }
        
    	$data = $this->validate_arguments_for_post(array("object"), array(), false);

    	$mkdir_data = json_decode($data["object"] ,true);
        $basedir = $mkdir_data["mkdir"]["basedir"];
        $path = $mkdir_data["mkdir"]["path"];
    	require_once(APPPATH. "controllers/common/rapi_utils.php");
        if(($net_sftp = rapi_utils::get_sftp($sftp_response)) !== false)
        {
            if($net_sftp->mkdir($basedir.$path) === true)
            {
                $this->print_error_for_api_request("Done", 0);
                return;
            }
        }
        $this->print_error_for_api_request("Unable to create directory", 257);
    }

    private function halogincredential()
    {
        $data = $this->validate_arguments_for_post(array("object"), array(), false);        
        $ha_node_add_request = $data["object"];
        $ha_node = json_decode( $ha_node_add_request ,true);
        
        if(!is_array($ha_node))
        {
            $this->show_404();
        }

        $remote_netscaler_ip = $ha_node["hanode"]["ipaddress"];
        $username = $ha_node["hanode"]["username"];
        $password = $ha_node["hanode"]["password"];
        
        $secure_access = false;
        if($ha_node["hanode"]["secure_access"] == "ENABLED")
        {
            $secure_access = true;        
        }

        $inc = $ha_node["hanode"]["inc"];
        $ip_to_connect = $this->is_ipv6($ha_node["hanode"]["ipaddress"])? $this->get_nsip6(): $this->get_nsip();
        if(isset($ha_node["hanode"]["new_nsip"]))
            $ip_to_connect = $ha_node["hanode"]["new_nsip"];
        
        require_once(APPPATH. "controllers/common/neo_proxy.php");
        
        $request = new neo_proxy($remote_netscaler_ip, $username, $password, null, $secure_access);
        $responses = array();
        $success = $request->post(array(new request("post", array( "hanode" => array("id" => "1","ipaddress" =>$ip_to_connect,"inc"=>$inc)), "HA node added")), $responses);
        
        print json_encode($responses[0]->get_response()) ;
        
        return false;         
    }
    
    private function execute_import_command_in_secondarynode()
    {
        $data = $this->validate_arguments_for_post(array("object"), array(), false);        
        $sslfipskey_import_request = $data["object"];
        $fip_imp = json_decode($sslfipskey_import_request,true);

        $remote_netscaler_ip = $fip_imp["sslfipskey"]["ipaddress"];
        $username = $fip_imp["sslfipskey"]["username"];
        $password = $fip_imp["sslfipskey"]["password"];
        $fipskeyname = $fip_imp["sslfipskey"]["fipskeyname"];
        $key = $fip_imp["sslfipskey"]["fipskeyname"];
        $exponent = isset($fip_imp["sslfipskey"]["exponent"])? $fip_imp["sslfipskey"]["exponent"]:NULL;
    
        // Uploading key file to Secondary Netscaler
        if(!isset($remote_netscaler_ip, $username, $password))
        {
            $response["errorcode"] = NSERR_NOUSER;
            $response["message"] = NSERR_NOUSER_MESSAGE;
            return $response;
        }
        
        require_once("Net/SFTP.php");
        $remote_net_sftp = new Net_SFTP($remote_netscaler_ip);
        if(!$remote_net_sftp->login($username, $password))
        {
            $response["errorcode"] = NSERR_NOTAUTHORIZED;
            $response["message"] = NSERR_NOTAUTHORIZED_MESSAGE;
            return $response;
        }

        $ssl_dir = $this->get_directory_path("/nsconfig/ssl/");
        $remote_sftp_result = $remote_net_sftp->put($ssl_dir .$key,  $ssl_dir .$key ,  NET_SFTP_LOCAL_FILE);
        if($remote_sftp_result === false)
        {
            $response["errorcode"] = -1;
            $response["message"] = self::print_error_mess("IMPORT_REPORTS_ERR_FILE_COPY", false);
            return $response;
        }
    
        require_once(APPPATH. "controllers/common/neo_proxy.php");
        $request = new neo_proxy($remote_netscaler_ip, $username, $password);
        
        if(!empty($exponent))
        {
             $success = $request->post(array(new request("post", array( "params"=> array( "action" => "import"),"sslfipskey" => array("fipskeyname" =>$fipskeyname,"key" =>$key, "exponent"=>$exponent)),"Importing Fipskey to Secondary node")), $responses);        
        }
        else
        {
             $success = $request->post(array(new request("post", array( "params"=> array( "action" => "import"),"sslfipskey" => array("fipskeyname" =>$fipskeyname,"key" =>$key )),"Importing Fipskey to Secondary node")), $responses);        
        }
        if(!$success)
               $this->print_error_for_api_request("Failed to Import Fips Key on Secondary", 1567);

        return $response_array = $responses[0]->get_response();      
    }
    
    private function importing_fipskey_in_secondarynode()
    {

        $data = $this->validate_arguments_for_post(array("object"), array(), false);        
        $sslfipskey_import_request = $data["object"];
        $fip_imp = json_decode($sslfipskey_import_request,true);
        
        $remote_netscaler_ip = $fip_imp["sslfipskey"]["ipaddress"];
        $username = $fip_imp["sslfipskey"]["username"];
        $password = $fip_imp["sslfipskey"]["password"];
        $fipskeyname = $fip_imp["sslfipskey"]["fipskeyname"];
        $key = $fip_imp["sslfipskey"]["key"];
        if(!preg_match("/^\/nsconfig\/ssl\//", $key))
            $key = "/nsconfig/ssl/".$key;
        $inform=$fip_imp["sslfipskey"]["inform"];
        $exponent = isset($fip_imp["sslfipskey"]["exponent"])? $fip_imp["sslfipskey"]["exponent"]:NULL;
        // Uploading key file to Secondary Netscaler
        if(!isset($remote_netscaler_ip, $username, $password))
        {
            $response["errorcode"] = NSERR_NOUSER;
            $response["message"] = NSERR_NOUSER_MESSAGE;
            return $response;
        }
        
        require_once("Net/SFTP.php");
        $remote_net_sftp = new Net_SFTP($remote_netscaler_ip);
        if(!$remote_net_sftp->login($username, $password))
        {
            $response["errorcode"] = NSERR_NOTAUTHORIZED;
            $response["message"] = NSERR_NOTAUTHORIZED_MESSAGE;
            return $response;
        }

        $remote_sftp_result = $remote_net_sftp->put( $key, $key,  NET_SFTP_LOCAL_FILE);
        if($remote_sftp_result === false)
        {
            $response["errorcode"] = -1;
            $response["message"] = self::print_error_mess("IMPORT_REPORTS_ERR_FILE_COPY", false);
            return $response;
        }
    
        require_once(APPPATH. "controllers/common/neo_proxy.php");
        $request = new neo_proxy($remote_netscaler_ip, $username, $password);
        if(!empty($exponent))
        {
           $success = $request->post(array(new request("post", array( "params"=> array( "action" => "import"),"sslfipskey" => array("fipskeyname" =>$fipskeyname,"key" =>$key, "inform"=>$inform, "exponent"=>$exponent)),"Importing Fipskey to Secondary node")), $responses);         
        }
        else
            $success = $request->post(array(new request("post", array( "params"=> array( "action" => "import"),"sslfipskey" => array("fipskeyname" =>$fipskeyname,"key" =>$key, "inform"=>$inform )),"Importing Fipskey to Secondary node")), $responses);        
        if(!$success)
               $this->print_error_for_api_request("Failed to Import Fips Key on Secondary", 1567);

        return $response_array = $responses[0]->get_response();      
    }
    
    private function execute_command_secondarynode()
    {
        $data = $this->validate_arguments_for_post(array("object"), array(), false);        
        $init_request = $data["object"];
        $init = json_decode( $init_request ,true);
        if(!is_array($init))
            $this->show_404();

        $remote_netscaler_ip = $init["sslfipssimtarget"]["ipaddress"];
        $username = $init["sslfipssimtarget"]["username"];
        $password = $init["sslfipssimtarget"]["password"];
        $certfile = $init["sslfipssimtarget"]["certfile"];
        $keyvector = $init["sslfipssimtarget"]["keyvector"];
        $targetsecret = $init["sslfipssimtarget"]["targetsecret"];
        
        
        if(!isset($remote_netscaler_ip, $username, $password))
        {
            $response["errorcode"] = NSERR_NOUSER;
            $response["message"] = NSERR_NOUSER_MESSAGE;
            return $response;
        }
        
        require_once("Net/SFTP.php");
        $remote_net_sftp = new Net_SFTP($remote_netscaler_ip);
        if(!$remote_net_sftp->login($username, $password))
        {
            $response["errorcode"] = NSERR_NOTAUTHORIZED;
            $response["message"] = NSERR_NOTAUTHORIZED_MESSAGE;
            return $response;
        }
        
        $ssl_dir = $this->get_directory_path("/nsconfig/ssl/");
        $remote_sftp_result = $remote_net_sftp->put($ssl_dir .$certfile,  $ssl_dir .$certfile ,  NET_SFTP_LOCAL_FILE);
        if($remote_sftp_result === false)
        {
            $response["errorcode"] = -1;
            $response["message"] = self::print_error_mess("IMPORT_REPORTS_ERR_FILE_COPY", false);
            return $response;
        }
        
        require_once(APPPATH. "controllers/common/neo_proxy.php");
        $request = new neo_proxy($remote_netscaler_ip, $username, $password);
        $success = $request->post(array(new request("post", array( "params"=> array( "action" => "init"),"sslfipssimtarget" => array("certfile" => $certfile,"keyvector" =>$keyvector,"targetsecret" =>$targetsecret)),"Initializing fips SIM Target...")), $responses);
        
        return $response_array = $responses[0]->get_response();     
    }
    
    private function downloading_file_from_secondarynode()
    {
        $data = $this->validate_arguments_for_post(array("object"), array(), false);
        $ha_node_add_request = $data["object"];
        $file_data = json_decode( $ha_node_add_request ,true);
        $tmp_file_name = $file_data["sslfipssimtarget"]["targetsecret"];
        $remote_netscaler_ip = $file_data["sslfipssimtarget"]["ipaddress"];
        $username = $file_data["sslfipssimtarget"]["username"];
        $password = $file_data["sslfipssimtarget"]["password"];
 
        if(!isset($remote_netscaler_ip, $username, $password))
        {
            $response["errorcode"] = NSERR_NOUSER;
            $response["message"] = NSERR_NOUSER_MESSAGE;
            return $response["message"];
        }
       
        require_once("Net/SFTP.php");
        $remote_net_sftp = new Net_SFTP($remote_netscaler_ip);
        if(!$remote_net_sftp->login($username, $password))
        {
            $response["errorcode"] = NSERR_NOTAUTHORIZED;
            $response["message"] = NSERR_NOTAUTHORIZED_MESSAGE;
            return $response["message"];
        }
        
        $ssl_dir = $this->get_directory_path("/nsconfig/ssl/");
        $remote_sftp_result = $remote_net_sftp->get($ssl_dir. $tmp_file_name, "/tmp/". $tmp_file_name );
        if($remote_sftp_result === false)
        {
            return self::print_error_mess("IMPORT_REPORTS_ERR_FILE_COPY", true);
        } 
        $this->print_error_for_api_request("Done",0);

        return;
    }

    private function copy_file_from_tmp($path_to_folder, $file_name)
    {
            
        require_once(APPPATH . "controllers/common/rapi_utils.php");
        
        if(($net_sftp = rapi_utils::get_sftp($response)) === false)
        {
            return $response["message"];
        }

        $sftp_result = $net_sftp->put($path_to_folder. "/" . $file_name, "/tmp/". $file_name, NET_SFTP_LOCAL_FILE);
        if($sftp_result === false)
        {
            return self::print_error_mess("IMPORT_REPORTS_ERR_FILE_COPY", true);
        }
            
        $this->print_error_for_api_request("Done",0);
        return ;
    }
    
    private function enable_sslfipssimtarget()
    {
        $data = $this->validate_arguments_for_post(array("object"), array(), false);        
        $enable_sim_targt_req = $data["object"];
        $enable_target = json_decode( $enable_sim_targt_req ,true);
        if(!is_array($enable_target))
            $this->show_404();

        $remote_netscaler_ip = $enable_target["sslfipssimtarget"]["ipaddress"];
        $username = $enable_target["sslfipssimtarget"]["username"];
        $password = $enable_target["sslfipssimtarget"]["password"];
        $keyvector = $enable_target["sslfipssimtarget"]["keyvector"];
        $sourcesecret = $enable_target["sslfipssimtarget"]["sourcesecret"];
        
        // Uploading Sourcesecret to Secondary Netscaler
        if(!isset($remote_netscaler_ip, $username, $password))
        {
            $response["errorcode"] = NSERR_NOUSER;
            $response["message"] = NSERR_NOUSER_MESSAGE;
            return $response;
        }
        
        require_once("Net/SFTP.php");
        $remote_net_sftp = new Net_SFTP($remote_netscaler_ip);
        if(!$remote_net_sftp->login($username, $password))
        {
            $response["errorcode"] = NSERR_NOTAUTHORIZED;
            $response["message"] = NSERR_NOTAUTHORIZED_MESSAGE;
            return $response;
        }
        
        $ssl_dir = $this->get_directory_path("/nsconfig/ssl/");
        $remote_sftp_result = $remote_net_sftp->put($ssl_dir .$sourcesecret,  $ssl_dir .$sourcesecret ,  NET_SFTP_LOCAL_FILE);
        if($remote_sftp_result === false)
        {
            return self::print_error_mess("IMPORT_REPORTS_ERR_FILE_COPY", true);
        }
        
        require_once(APPPATH. "controllers/common/neo_proxy.php");
        $request = new neo_proxy($remote_netscaler_ip, $username, $password);
        $success = $request->post(array(new request("post", array( "params"=> array( "action" => "enable"),"sslfipssimtarget" => array("keyvector" =>$keyvector,"sourcesecret"=>$sourcesecret)),"Enabling target for SIM...")), $responses);
        return  $response_array = $responses[0]->get_response();     
    }
    
    private function uploading_certfile_to_remote_netscaler()
    {
        $data = $this->validate_arguments_for_post(array("object"), array(), false);
        $file_copy_request = $data["object"];
        $file_data = json_decode( $file_copy_request ,true);
        
        $file = $file_data["sslcertkey"]["cert"];
        $remote_netscaler_ip = $file_data["sslcertkey"]["ipaddress"];
        $username = $file_data["sslcertkey"]["username"];
        $password = $file_data["sslcertkey"]["password"];
    
        // Uploading key file to Secondary Netscaler
        if(!isset($remote_netscaler_ip, $username, $password))
        {
            $response["errorcode"] = NSERR_NOUSER;
            $response["message"] = NSERR_NOUSER_MESSAGE;
            return $response["message"];
        }
        
        require_once("Net/SFTP.php");
        $remote_net_sftp = new Net_SFTP($remote_netscaler_ip);
        if(!$remote_net_sftp->login($username, $password))
        {
            $response["errorcode"] = NSERR_NOTAUTHORIZED;
            $response["message"] = NSERR_NOTAUTHORIZED_MESSAGE;
            return $response["message"];
        }
        
        $ssl_dir = $this->get_directory_path("/nsconfig/ssl/");
        $remote_sftp_result = $remote_net_sftp->put($ssl_dir .$file,  $ssl_dir .$file ,  NET_SFTP_LOCAL_FILE);
        if($remote_sftp_result === false)
        {
            return self::print_error_mess("IMPORT_REPORTS_ERR_FILE_COPY", true);
        }
        // $remote_net_sftp->_disconnect("passed");
        
        $this->print_error_for_api_request("Done",0);
        return;
    }
    
                
    private function get_remote_file_browser()
    {
        require_once(APPPATH. "controllers/common/remotefilebrowser.php");
        require_once(APPPATH . "controllers/common/rapi_utils.php");
        
        $path = $this->args;
        $remotefilebrowser = new remotefilebrowser($this->current_request_type);
    
        return $remotefilebrowser->get_contents($path);    
    }
    
    private function nspartition_systemuser_binding($object_name,$query_params)
    {
        $is_count_call = isset($query_params['count']) ? true : false;
        $count = 0;
        $response = array("nspartition_systemuser_binding" => array());
        
        if($this->execute_command('getsystemuser', array()) == 0)
        {
            $users = $this->get_model()->get_result();
            if($users["rc"] == 0 && count($users["List"]) > 0)
            {
                foreach( $users["List"] as $user)
                {
                    if($this->execute_command('getsystemuser_nspartition_binding', array("username" => $user["username"])) == 0)
                    {
                        $partition_bindings = $this->get_model()->get_result();
                        if($partition_bindings["rc"] == 0 && count($partition_bindings["List"]) > 0)
                        {
                            foreach($partition_bindings["List"] as $partition_binding)
                            {
                                if($partition_binding["partitionname"] ==  $object_name && $is_count_call == false)
                                    $response["nspartition_systemuser_binding"][] = array("username" => $user["username"], "partitionname" =>$partition_binding["partitionname"]);
                                if($partition_binding["partitionname"] ==  $object_name && $is_count_call == true)
                                    ++$count;
                            }
                        }
                    }
                }
            }
        }
        
        if($is_count_call)
            $response["nspartition_systemuser_binding"][] = array("__count" => $count);
                                                                  
        return $response;
    }

    private function is_ipv6($ip_address)
    {
        if (preg_match("/^\s*((([0-9A-Fa-f]{1,4}:){7}([0-9A-Fa-f]{1,4}|:))|(([0-9A-Fa-f]{1,4}:){6}(:[0-9A-Fa-f]{1,4}|((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){5}(((:[0-9A-Fa-f]{1,4}){1,2})|:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){4}(((:[0-9A-Fa-f]{1,4}){1,3})|((:[0-9A-Fa-f]{1,4})?:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){3}(((:[0-9A-Fa-f]{1,4}){1,4})|((:[0-9A-Fa-f]{1,4}){0,2}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){2}(((:[0-9A-Fa-f]{1,4}){1,5})|((:[0-9A-Fa-f]{1,4}){0,3}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){1}(((:[0-9A-Fa-f]{1,4}){1,6})|((:[0-9A-Fa-f]{1,4}){0,4}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(:(((:[0-9A-Fa-f]{1,4}){1,7})|((:[0-9A-Fa-f]{1,4}){0,5}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:)))(%.+)?(\/(1[0-2][0-8]|[1-9][0-9]|[0-9]))?\s*$/",$ip_address) || preg_match("/^\*$/",$ip_address))
            return true;
        else
            return false;
        
    }
    
    private function export_cert_for_wi( $certificate_name)
    {
        $output = '';
        require_once(APPPATH . "controllers/common/rapi_utils.php");
        $result = array();
        
        if(($net_ssh = rapi_utils::get_ssh($ssh_response, 30)) !== false)
        {
            $net_ssh->setTimeout(10);
            $net_ssh->write("shell\n");
            $net_ssh->write("cd /netscaler/wi\n");
            $net_ssh->read('/[\z]/');
            $net_ssh->write("./export_cert.sh /nsconfig/ssl/{$certificate_name} \n");
            $output = $net_ssh->read('/[\z]/');
            
            $parts = explode("\r\n",$output);
            $parts = array_slice($parts,1,-1);
            
            $net_ssh->read('');
        }
        
        $result["errorcode"] = 0;
        $result["message"] = "Done";
        $result["severity"] = "NONE";
        
    	print json_encode($result);
    }
    
    private function extract_from_tar($dir, $filename, $epa_current_version)
    {
        $output = '';
        require_once(APPPATH . "controllers/common/rapi_utils.php");
        $result = array();

        $epa_array = array("epaPackage.exe", "version.xml", "Citrix_Endpoint_Analysis.dmg", "epamacversion.txt", "Citrix_Access_Gateway.dmg", "macversion.txt", "epa_expression_data.js.gz");
        
        if(($net_ssh = rapi_utils::get_ssh($ssh_response, 30)) !== false)
        {
            $net_ssh->setTimeout(10);
            $net_ssh->write("shell\n");
            $net_ssh->write("cd {$dir}\n");
            $net_ssh->read('/[\z]/');
            $net_ssh->write("tar -zxvf {$filename}\n");
            $output = $net_ssh->read('/[\z]/');
            
            for($count = 0; $count < count($epa_array); $count++)
            {
                if (stripos($output,$epa_array[$count]) === false)
                {
                    $result["errorcode"] = -1;
                    $result["message"] = "Required files are missing. Check whether the tar ball \"".$filename."\" is valid";
                    $result["severity"] = "NONE";
        
                    print json_encode($result);
                    return;
                }
            }
            
            $xml = simplexml_load_file("/var/tmp/version.xml");
            $xml = json_encode($xml);
            $xml = json_decode($xml,TRUE);
            $epa_version_after_upload = $xml["EPALib"];
            
            if($epa_current_version === $epa_version_after_upload)
            {
                $this->print_error_for_api_request("Both existing EPA Version and current EPA Version are same. Do you still want to Upgrade?" , 0);
                return;
            }
            
            $net_ssh->read('');
        }
        
        $result["errorcode"] = 0;
        $result["message"] = "Done";
        $result["severity"] = "NONE";
        
    	print json_encode($result);
    }
    
    private function move_epa_files_to_dir($dir)
    {
        require_once(APPPATH . "controllers/common/rapi_utils.php");
        $result = array();

        $epa_array = array("epaPackage.exe", "version.xml", "Citrix_Endpoint_Analysis.dmg", "epamacversion.txt", "Citrix_Access_Gateway.dmg", "macversion.txt", "epa_expression_data.js.gz");
        
        if(($net_ssh = rapi_utils::get_ssh($ssh_response, 30)) !== false)
        {   
            $net_ssh->setTimeout(10);
            $net_ssh->write("shell\n");
            
            foreach( $epa_array as $file)
            {
                switch($file)
                {
                    case "epaPackage.exe":
                        $net_ssh->write("mv {$dir}{$file} /var/netscaler/gui/epa/scripts/win\n");
                    case "version.xml":
                        $net_ssh->write("mv {$dir}{$file} /var/netscaler/gui/epa\n");
                    case "Citrix_Endpoint_Analysis.dmg":
                        $net_ssh->write("mv {$dir}{$file}  /var/netscaler/gui/epa/scripts/mac\n");
                    case "epamacversion.txt":
                        $net_ssh->write("mv {$dir}{$file}  /var/netscaler/gui/epa/scripts/mac\n");
                    case "Citrix_Access_Gateway.dmg":
                        $net_ssh->write("mv {$dir}{$file} /var/netscaler/gui/vpns/scripts/mac\n");
                    case "macversion.txt":
                        $net_ssh->write("mv {$dir}{$file} /var/netscaler/gui/vpns/scripts/mac\n");
                    case "epa_expression_data.js.gz":
                        $net_ssh->write("cp {$dir}{$file} /var/netscaler/gui/admin_ui/neo/js && mv {$dir}{$file} /netscaler/ns_gui/admin_ui/neo/js\n");
                }
            }

            $net_ssh->read('');
        }
        
        $result["errorcode"] = 0;
        $result["message"] = "Done";
        $result["severity"] = "NONE";
        
    	print json_encode($result);
    }
    
    private function load_xml_file($dir, $filename)
    {
        $xml = "";

        $this->execute_command("getsystemfile", array("filelocation" => $dir, "filename" => $filename));
        $systemfile_result = $this->get_model()->get_result();     

        if($systemfile_result["rc"] == 0)
        {
            $file_info = $systemfile_result["List"][0]; 
            $xml_content = base64_decode($file_info["filecontent"]); 

            $xml = simplexml_load_string($xml_content);
        }
       
        $result = array();
        $result["errorcode"] = $systemfile_result["rc"];
        $result["message"] = $systemfile_result["message"];
        $result["severity"] = $systemfile_result["severity"];
        $result["xml_data"] = $xml;
        
        print json_encode($result);
    }

    private function open_websocket_connection()
    {
        $response = array();
        if(!$this->validate_session($response))
        {
            return $response;
        }

        require_once(APPPATH. "controllers/common/WebSocketClient.php");
        $client = new WebSocketClient("ws://127.0.0.1:9999");
        $data = $this->validate_arguments_for_post(array("object"), array(), false);
        $ws_data_to_send = json_decode($data["object"] ,true);

        try
        {
            $sess_id = $ws_data_to_send["open_websocket"]["data"];

            if($sess_id === "")
            {
                $sess_id = trim($_COOKIE["SESSID"]);
            }

            $client->send(json_encode(array("type" => "openssh", "data" => $sess_id, "origin" => "rapi", "rand_key" => $_SESSION["rand"])));
            sleep(1);

            $ws_data_received = $client->receive();
            $recv_data = json_decode($ws_data_received, true);
        }
        catch (Exception $e)
        {
            $recv_data = "";
        }
        
        $client->close(); // Close Socket

        if(isset($recv_data["token"]) && isset($recv_data["status"]) && $recv_data["status"] === "done")
        {
            $result = array();
            $result["errorcode"] = 0;
            $result["message"] = "Done";
            $result["severity"] = "NONE";
            $result["output"] = $recv_data;
            print json_encode($result);    
        }
        else
        {
            $result = array();
            $result["errorcode"] = NSERR_INTERNAL_SERVER;
            $result["message"] = "Could not open websocket connection. Please try by login again.";
            $result["severity"] = "ERROR";
            print json_encode($result);              
        }
    }

    private function close_websocket_connection()
    {
        $response = array();
        if(!$this->validate_session($response))
        {
            return $response;
        }

        require_once(APPPATH. "controllers/common/WebSocketClient.php");
        $client = new WebSocketClient("ws://127.0.0.1:9999");
        $data = $this->validate_arguments_for_post(array("object"), array(), false);
        $ws_data_to_send = json_decode($data["object"] ,true);

        try
        {
            $client->send(json_encode(array("type" => "closessh", "token" => $ws_data_to_send["close_websocket"]["data"] , "origin" => "rapi", "rand_key" => $_SESSION["rand"])));
        }
        catch (Exception $e)
        {
            error_log("Exception in WebSocketClient send");
        }

        $client->close(); // Close Socket

        $this->print_error_for_api_request("Done",0);   
    }

    private function send_data_to_websocket()
    {
        $response = array();
        if(!$this->validate_session($response))
        {
            return $response;
        }

        require_once(APPPATH. "controllers/common/WebSocketClient.php");
        $client = new WebSocketClient("ws://127.0.0.1:9999");
        $data = $this->validate_arguments_for_post(array("object"), array(), false);
        $ws_data_to_send = json_decode($data["object"] ,true);
        $ws_data_to_send["websocket_send"]["origin"] = "rapi";
        $ws_data_to_send["websocket_send"]["rand_key"] = $_SESSION["rand"];

        try
        {
            $client->send(json_encode($ws_data_to_send["websocket_send"])); // Commands will be executed after escapeshellcmd in shell mode (applicable in direct shell exectution from CLI). Logic added in WebSocket daemon.
            sleep(1);

            $ws_data_received = $client->receive();
            $recv_data = json_decode($ws_data_received, true);
        }
        catch (Exception $e)
        {
            $recv_data = array("status" => "running", "data" => "", "type" => "cmd");
        }
        
        $client->close(); // Close Socket

        $result = array();
        $result["errorcode"] = 0;
        $result["message"] = "Done";
        $result["severity"] = "NONE";
        $result["output"] = $recv_data;   

        print json_encode($result);
    }

    /*
     *  Start of SSL certificate Management PHP functions.
     */
    
    /*
     *  The function detects whether the certfile is PFX or not.
     *  Following is the algo for that :-
        First byte == 0x30
        Mask second byte with 0x7f  ( val = (byte2 & 0x7f) ) and  skip next val number of bytes. then
        expect 4 bytes of  0x02 0x01 0x03 0x30
        mask next byte with 0x7f  ( val = (byte2 & 0x7f) ) and  skip next val number of  bytes .. then 
        expect  next 10 bytes as   0x06 0x09 0x2a 0x86 0x48 0x86 0xf7 0x0d 0x01 0x07
     *  
     */
    
    private function detect_pfx_cert_file($filename)
    {
        if (! isset($filename))
            return false;
        
        // Opening the given filename is binary read mode.
        $handle = fopen($filename, "rb");
        if (! $handle)
            return false;
        
        // Reading first byte of the file. and converting the binary byte to hexcode.
        $data_byte = fread($handle, 1);
        $data_in_hex = bin2hex($data_byte);
        $is_pfx_file = false;
        
        // Check if first byte is 30 as per the algo.
        if ($data_in_hex == "30")
        {
            // Now reading the second byte of the file. Masking it as per the second step of Algo.
            if (fseek($handle, 1) === 0)
            {
                $data_byte = fread($handle, 1);
                $data_in_hex = bin2hex($data_byte);
                $val = hexdec($data_in_hex) & 0x7f;
                $decimal_val = hexdec($val);
                
                // After getting the decimal value of second byte, skipping those many bytes by moving file pointer to apt position.
                if (fseek($handle, $decimal_val + 2) === 0)
                {
                    $next_4_bytes = fread($handle, 4);
                    $hex_of_next_4_bytes = bin2hex($next_4_bytes);
                    
                    // Checking if next 4 bytes matched the hexcode.
                    if ($hex_of_next_4_bytes == "02010330")
                    {
                        // Completimg the 3rd step of algo, i.e reading next byte and then masking and skiping those many bytes.
                        // 6 = 1st byte + 2nd byte + (len(02010330) = 4) = 1 + 1 + 4
                        if (fseek($handle, $decimal_val + 6) === 0)
                        {
                            $data_byte = fread($handle, 1);
                            $data_in_hex = bin2hex($data_byte);
                            $val = hexdec($data_in_hex) & 0x7f;
                            $next_decimal_val = hexdec($val);
                            
                            // Reading next 10 bytes, after skipping those many bytes as per value from 3rd step of algo.
                            if (fseek($handle, $next_decimal_val + $decimal_val + 7) === 0)
                            {
                                $next_10_bytes = fread($handle, 10);
                                $hex_of_next_10_bytes = bin2hex($next_10_bytes);
                                
                                // And now checking if 10 bytes euals the hexcode as per algo.And finally declaring the file as pfx.
                                if ($hex_of_next_10_bytes == "06092a864886f70d0107")
                                    $is_pfx_file = true;
                            }
                        }
                    }
                }
            }
        }
        return $is_pfx_file;
    }
    
    /*
     * This function takes a certificate file, and check if its a bundle certificate, i.e Keyfile is present itself in the file.
     * It also check if its password protected.
     * It also checks if the certificate is pfx format or not.
     * Also in cases of ICA, if the file contains a bundle i.e. occurence of more than one certificate, since in case of CA/ICA no keyfile is there.
     */
    private function get_sslfile_property($filename)
    {
        $response = array();
        if(!$this->validate_session($response))
        {
            return $response;
        }
        if (! isset($filename))
            return $response;
        
        // Check for if Filename contains the path "/nsconfig/ssl/. If not then add it to the filename.
        $filename = urldecode($filename);
        $filepath_present = strpos ($filename, "/nsconfig/ssl/");
        
        if (! $filepath_present)
        {
            $filename = "/nsconfig/ssl/". $filename;
        }
        
        $error_found = false;
        $is_bundle = false;
        $certificate = false;
        $keyfile = false;
        $password_protected = false;
        $is_pfx = false;
        $certifcate_count = 0;
        if ($fp = fopen($filename, "r"))
        {
            $line = fgets($fp);
            
            while ($line === '' && $line !== false) // THis is to check if there are extra space on top of the file.
                $line = fgets($fp);
            
            if ($line !== false) 
            {
                if($this->is_ascii($line)) /// Check for ASCII lines which will cofirm that File is PEM certificate
                {
                    $content = file_get_contents($filename);
                    if (strpos ($content, "-----BEGIN CERTIFICATE-----") || (strpos ($content, "-----BEGIN CERTIFICATE-----") === 0) || strpos ($content, "-----BEGIN CERTIFICATE-----\n") || (strpos ($content, "-----BEGIN CERTIFICATE-----\n") === 0))
                    {
                        $certificate = true;
                        $certifcate_count = substr_count($content, "-----BEGIN CERTIFICATE-----");
                    }
                    if (strpos ($content, "-----BEGIN DSA PRIVATE KEY-----\n") || strpos ($content, "-----BEGIN RSA PRIVATE KEY-----\n") || (strpos ($content, "-----BEGIN RSA PRIVATE KEY-----\n") === 0) || (strpos ($content, "-----BEGIN DSA PRIVATE KEY-----\n") === 0))
                    {
                        $keyfile = true;
                        if ($certificate)
                            $is_bundle = true;
                    }
                    if (strpos ($content, "Proc-Type: 4,ENCRYPTED\n"))
                    {
                        $password_protected = true;
                    }
                }
                else
                {
                    // PFX Format file
                    if ($this->detect_pfx_cert_file($filename))
                    {
                        $password_protected = true;
                        $is_pfx = true;
                        $is_bundle = true;
                    }
                    else
                    {
                        /// DER fomat Certificate
                    }
                }
            }
            if (! $is_bundle && ! $keyfile)
            {
                /// check if the certificate is CA or ICA.
                $is_ca = $this->is_certificate_ca($filename);
                if ($is_ca && $certifcate_count > 1)
                {
                    $is_bundle = true;
                }
            }
            
            fclose($fp);
        }
        else
        {
            $error_found = true;
        }
        
        if (! $error_found)
            $output = (object) array( 'errorcode' => 0, 'message' => "DONE", 'severity' => "NONE", 'ssl_files' => (object) array('filename' => $filename, 'is_pfx' => $is_pfx, 'password_protected' => $password_protected, 'bundle' => $is_bundle, 'keyfile' => $keyfile) );
        else
            $output = (object) array( 'errorcode' => -1, 'message' => "Invalid File", 'severity' => "ERROR");
            
        $this->output->set_output(json_encode($output));   
    }
    
    /*
     * This function checks if the string is ASCII string, i.e it lies in the range of hex value 80 to ff.
     */
    private function is_ascii($str)
    {
       return ( bool ) ! preg_match( '/[\\x80-\\xff]+/' , $str ); 
    }
    
    /*
     * Search the data with the query parameter send.
     */
    private function get_data_as_per_search($data, $query_params)
    {
        if (! isset($data))
            return $data;
        
        $data_succeeded_search = array();
        $search_params = isset($query_params["filter"]) ? $query_params["filter"] : -1; // Applicable for Get all only

        if (isset($query_params["filter"]))
        {
            foreach ($this->filter as $key => $value)
            {
                for ($i = 0; $i < count($data); $i++)
                {
                    if (isset($data[$i][$key]) && preg_match($value, $data[$i][$key]))
                    {
                        array_push($data_succeeded_search, $data[$i]);
                    }
                }
            }
        }

        return $data_succeeded_search;
    }
    
    /*
     * This function will return data as per the pageno specified in the query.
     * It will also send the count if the query is for count.
     */
    private function get_data_as_per_pageno($data, $query_params)
    {
        $pageno = isset($query_params["pageno"]) ? (int)$query_params["pageno"] : 1; // Applicable for Get all only
        $pagesize = isset($query_params["pagesize"]) ? (int)$query_params["pagesize"] : 999999999; // Applicable for Get all only            
        $is_count_call = isset($query_params['count']) ? true : false; // Applicable for Get all only
        
        if (! isset($data) || (! is_array($data)) || (count($data) == 0))
        {
            if ($is_count_call)
            {
                return 0;
            }
            else
            {
                return $data;
            }
        }
        
        if ($is_count_call)
        {
            return count($data);
        }
        
        $start_index = ($pageno - 1)* $pagesize;
        $end_index = $start_index + $pagesize;

        if (count($data) < $end_index)
        {
            $end_index = count($data);
        }
        
        $length = $end_index - $start_index;
        $data_by_pageno = array_splice($data, $start_index, $length);
        
        return $data_by_pageno;
    }
    
    /*
     * Function is used to see if there has been any modification in SSL Dir.
     */
    private function is_ssl_directory_modified()
    {
        $response = array();
        if(!$this->validate_session($response))
        {
            return $response;
        }
        
        $is_ssl_dir_modified = true;
        $this->execute_command("getsystemfile", array("fileLocation" => "/nsconfig/"));
        $nsconfig_dir_result = $this->get_model()->get_result();
        
        if($nsconfig_dir_result["rc"] == 0)
        {
            $nsconfig_files_list = $nsconfig_dir_result["List"];
            $ssl_dir_modified_time = "";
            for ($i = 0; $i < count($nsconfig_files_list); $i++)
            {
                if ($nsconfig_files_list[$i]["filename"] == "ssl")
                {
                    $ssl_dir_modified_time = $nsconfig_files_list[$i]["filemodifiedtime"];
                    break;
                }
            }
            
            $epoch_time = strtotime($ssl_dir_modified_time);
        
            if (! file_exists("/var/nstmp/ssl_dir_info.txt")) {
                $ssl_fp = fopen("/var/nstmp/ssl_dir_info.txt", "w");
                if ($ssl_fp)
                {
                    file_put_contents("/var/nstmp/ssl_dir_info.txt", $epoch_time);
                }
                else
                {
                    $error_found = true;  
                }
                fclose($ssl_fp);
            }
            else
            {
                $ssl_fp = fopen("/var/nstmp/ssl_dir_info.txt", "r");
                if ($ssl_fp)
                {
                    $stored_epoch_time = (int) file_get_contents("/var/nstmp/ssl_dir_info.txt");
                }
                else
                {
                    $error_found = true;
                }
                fclose($ssl_fp);
                
                if ($stored_epoch_time == $epoch_time)
                {
                    $is_ssl_dir_modified = false;    
                }
                else
                {
                    $ssl_fp = fopen("/var/nstmp/ssl_dir_info.txt", "w+");
                    if ($ssl_fp)
                    {
                        file_put_contents("/var/nstmp/ssl_dir_info.txt", $epoch_time);
                    }
                    fclose($ssl_fp);
                }
            }
        }
        
        return $is_ssl_dir_modified;
    }
    
    private function write_content_to_file($content, $filename)
    {
        $error_found = false;
        $file_storage = fopen("/var/nstmp/$filename.txt", "w+");
        if ($file_storage)
        {   
            if (flock($file_storage ,LOCK_EX))
            {
                $serialized = json_encode($content);
                fwrite($file_storage, $serialized);
                flock($file_storage ,LOCK_UN);
            }
            else
            {
                $error_found = true;
            }
        }
        else
        {
            $error_found = true;
        }
        fclose($file_storage);
        return $error_found;
    }
    
    private function update_ssl_key_csr_cert_tmp_files($file_type)
    {
        $key_file = array();
        $csr_file = array();
        $cert_file = array();
        $this->execute_command("getsystemfile", array("fileLocation" => "/nsconfig/ssl/"));
        $systemfile_result = $this->get_model()->get_result();
        if($systemfile_result["rc"] == 0)
        {
            $file_info = $systemfile_result["List"][0]; 
            $list = $systemfile_result["List"];
             
            $this->get_csr_key_certificates($list, 0, $key_file, $csr_file, $cert_file, "/nsconfig/ssl");
            
            $keyfile_update_successfully = $this->write_content_to_file($key_file, "sslkeyfiles");
            $csrfile_update_successfully = $this->write_content_to_file($csr_file, "sslcsrfiles");
            $certfile_update_successfully = $this->write_content_to_file($cert_file, "sslcertfiles");
            
            $error_found = $keyfile_update_successfully || $csrfile_update_successfully || $certfile_update_successfully;
        }
        else
            $error_found = true;
            
        if (! $error_found)
        {
            if ($file_type == "sslkeyfiles")
                return $key_file;
            else if ($file_type == "sslcsrfiles")
                return $csr_file;
            else if ($file_type == "sslcertfiles")
                return $cert_file;
        }
        else
            return NULL;
    }
    
    /*
     * This function will segregrate the files into Key, CSR or Certificate.
     * The parameter is key_counter, csr_counter and cert_counter are taken as parameter_by_reference.
     * 
     */
    private function get_csr_key_certificates($files_array, $index, &$key_counter, &$csr_counter, &$cert_counter, $dir_path)
    {
        if ($index >= count($files_array))
        {
            return;
        }
        
        $is_dir = isset($files_array[$index]["filemode"]) ? $files_array[$index]["filemode"][0] == "DIRECTORY" : false;
        // if the file found is directory then find the diles inside those directory and segregrate them first.
        if ($is_dir)
        {
            $filename = $files_array[$index]["filename"];
            $this->execute_command("getsystemfile", array("fileLocation" => $dir_path."/".$filename ));
            $systemdir_result = $this->get_model()->get_result();
            
            if($systemdir_result["rc"] == 0)
            {
                $file_list = $systemdir_result["List"];
                $this->get_csr_key_certificates($file_list, 0, $key_counter, $csr_counter, $cert_counter, $dir_path."/".$filename);
            }
            $this->get_csr_key_certificates($files_array, $index + 1, $key_counter, $csr_counter, $cert_counter, $dir_path);
        }
        else
        {
            $filename = $dir_path . "/" . $files_array[$index]["filename"];
            if ($fp = fopen($filename, "r"))
            {
                $line = fgets($fp);
                while ($line === '' && $line !== false) // THis is to check if there are extra space on top of the file.
                    $line = fgets($fp);
                    
                $file_content = file_get_contents($filename);
                //$encode = ( bool ) ! preg_match( '/[\\x80-\\xff]+/' , $line ); /// Checks for ASCII encoding. http://pageconfig.com/post/how-to-validate-ascii-text-in-php
                if($line !== false && $file_content)
                {
                    if ($line === "-----BEGIN CERTIFICATE-----\n" || $line === "-----BEGIN CERTIFICATE-----\r\n" || $line === "-----BEGIN TRUSTED CERTIFICATE-----\r\n")
                    {
                        array_push($cert_counter, $files_array[$index]);
                    }
                    if ($line === "-----BEGIN RSA PRIVATE KEY-----\n" || $line === "-----BEGIN DSA PRIVATE KEY-----\n")
                    {
                        $strpos_for_certfile = strpos($file_content, "-----BEGIN CERTIFICATE-----");
                        $strpos_for_trusted_certfile = strpos($file_content, "-----BEGIN TRUSTED CERTIFICATE-----");
                        if ($strpos_for_certfile || $strpos_for_trusted_certfile)
                            array_push($cert_counter, $files_array[$index]);
                        else
                            array_push($key_counter, $files_array[$index]);
                    }
                    else if ($line === "-----BEGIN NEW CERTIFICATE REQUEST-----\n")
                    {
                        array_push($csr_counter, $files_array[$index]);
                    }
                    else
                    {
                        $is_binary_file = strpos($line, "0");
                        if ($is_binary_file === 0)
                        {
                            $is_pfx = $this->detect_pfx_cert_file($filename);
                            if ($is_pfx)
                            {
                                array_push($cert_counter, $files_array[$index]);
                            }
                            else
                            {
                                /// For DER files
                                
                                require_once(APPPATH."controllers/common/phpseclib/File/X509.php");
                                $x509 = new File_X509();
                                $x509_str = file_get_contents($filename);
                                
                                $is_cert = $x509->loadX509($x509_str);
                                if ($is_cert) // Potential of causing error
                                    array_push($cert_counter, $files_array[$index]);
                                else
                                    array_push($key_counter, $files_array[$index]);
                            }
                        }
                    }
                    
                }
            }
            
            fclose($fp);
            $this->get_csr_key_certificates($files_array, $index + 1, $key_counter, $csr_counter, $cert_counter, $dir_path);
        }
    }
    
    /*
     * This function is used for finding keyfile, csrfile and certfile from /nsconfig/ssl directory.
     * The following is the algo :-
     *  a. Check the ssl directory modified time, and store it in epoch form in /tmp/ssl_dir_info.txt folder.
     *  b. Now read each file in /nsconfig/ssl folder one by one, and segregrate them into key, csr or cert bucket. Done by calling function get_csr_key_certificates.
     *  c. After the files are segregrated, put the data in serialize form in  files /tmp/keyfile.txt, /tmp/csrfile.txt and /tmp/certfile.txt respectively. This is done by function update_ssl_key_csr_cert_tmp_files.
     *
     *  Next time when the user query this call, first check if modified time of ssl directory has changed, by matching it with the stored epoch time done previoulsy.
     *  If No, then get the content from /tmp/keyfile.txt, /tmp/csrfile.txt and /tmp/certfile.txt respectively and unserialize it and return the data.
     *  If yes, then follow the above algo again.
     */
    private function get_sslfiles($file_type, $resrc_name, $query_params)
    {
        $response = array();

        if(!$this->validate_session($response))
        {
            return $response;
        }
        
        $error_found = "";
        $file_content_unserialize = "";
        $key_file = array();
        $csr_file = array();
        $cert_file = array();
        $is_count_call = isset($query_params['count']) ? true : false; // Applicable for Get all only
        
        $is_ssl_dir_modified = $this->is_ssl_directory_modified();
        
        $data_files_exist = false;    

        if ((file_exists("/var/nstmp/$file_type.txt"))) 
        {
            $data_files_exist = true;
        }
        
        if ($is_ssl_dir_modified || (!$data_files_exist))
        {
            $data = $this->update_ssl_key_csr_cert_tmp_files($file_type);

            if (isset($data))
            {
                if(isset($query_params["filter"]))
                {
                    $data = $this->get_data_as_per_search($data, $query_params);
                }

                $data_as_per_pageno = $this->get_data_as_per_pageno($data, $query_params);

                if ($is_count_call)
                {
                    $output = (object) array( 'errorcode' => 0, 'message' => "DONE", 'severity' => "NONE", $resrc_name => (object) array('__count' => $data_as_per_pageno) );      
                }
                else
                {
                    $output = (object) array( 'errorcode' => 0, 'message' => "DONE", 'severity' => "NONE", $resrc_name =>  $data_as_per_pageno );
                }
            }
            else
            {
                if($is_count_call)
                {
                    $output = (object) array( 'errorcode' => 0, 'message' => "DONE", 'severity' => "NONE", $resrc_name => (object) array('__count' => 0));      
                }
                else
                {                 
                    $output = (object) array( 'errorcode' => 0, 'message' => "DONE", 'severity' => "NONE", $resrc_name =>  array() );
                }
            }    
        }
        else
        {
            $file_storage = fopen("/var/nstmp/$file_type.txt", "r");

            if ($file_storage)
            {
                $file_content = file_get_contents("/var/nstmp/$file_type.txt");

                if($file_content)
                {
                    $file_content_unserialize = json_decode($file_content, true);
                }
            }
            else
            {
                if($is_count_call)
                {
                    $output = (object) array( 'errorcode' => 0, 'message' => "DONE", 'severity' => "NONE", $resrc_name => (object) array('__count' => 0));      
                }
                else
                {                
                    $output = (object) array( 'errorcode' => 0, 'message' => "DONE", 'severity' => "NONE", $resrc_name =>  array());
                }
            }

            if(isset($query_params["filter"]))
            {
                $file_content_unserialize = $this->get_data_as_per_search($file_content_unserialize, $query_params);
            }

            $file_data = $this->get_data_as_per_pageno($file_content_unserialize, $query_params);
            
            if ($is_count_call)
            {
                $output = (object) array( 'errorcode' => 0, 'message' => "DONE", 'severity' => "NONE", $resrc_name => (object) array('__count' => $file_data));      
            }
            else
            {
                $output = (object) array( 'errorcode' => 0, 'message' => "DONE", 'severity' => "NONE", $resrc_name =>  $file_data );      
            }

            fclose($file_storage);
        }
        
        $this->output->set_output(json_encode($output));  
    }
    
    /*
     * This function is used to segregrate Server / Client / CA Certificates using phpseclib
     * Here we first do get_all on sslcertkey resrc, and then pass each of the certificate to the function "segregrate_certificates".
     */
    private function get_segregrated_installed_certificates($query_params)
    {
        $response = array();

        if(!$this->validate_session($response))
        {
            return $response;
        }

        $is_count_call = isset($query_params['count']) ? true : false; // Applicable for Get all only
        
        $this->execute_command("getsslcertkey", "");
        $sslcertkey_result = $this->get_model()->get_result();
        $cert_array = array();
        
        if($sslcertkey_result["rc"] == 0)
        {
            $sslcertkey_list = $sslcertkey_result["List"];
        
            if(isset($query_params["filter"]))
            {
                $certificate_type = $this->filter["cert_type"];
                if ($certificate_type)
                {
                    for ($i = 0; $i < count($sslcertkey_list); $i++)
                    {
                        $match_type = $this->segregrate_certificates($sslcertkey_list[$i]["cert"], $sslcertkey_list[$i]["inform"], $certificate_type);
                        if ($match_type)
                            array_push($cert_array, $sslcertkey_list[$i]);
                    }
                }
                else
                {
                    $cert_array = $sslcertkey_list;
                }
            }
            else
            {
                $cert_array = $sslcertkey_list;
            }

            $data_as_per_pageno = $this->get_data_as_per_pageno($cert_array, $query_params);
            
            if(isset($query_params["filter"]) && (isset($this->filter["certkey"]) || isset($this->filter["status"])))
            {
                $data_as_per_pageno = $this->get_data_as_per_search($data_as_per_pageno, $query_params);
            }

            if ($is_count_call)
            {
                $output = (object) array( 'errorcode' => 0, 'message' => "DONE", 'severity' => "NONE", 'sslcertkey' => (object) array('__count' => $data_as_per_pageno) );      
            }
            else
            {
                $output = (object) array( 'errorcode' => 0, 'message' => "DONE", 'severity' => "NONE", 'sslcertkey' => $data_as_per_pageno );
            }
        }
        else
        {
            $output = (object) array( 'errorcode' => $sslcertkey_result["rc"], 'message' => $sslcertkey_result["message"]);
        }
       
        $this->output->set_output(json_encode($output));  
    }
    
    /*
     *  Here by using X509.php, trying to fetch the certtype.
     */
    private function segregrate_certificates($certificate_filename, $cert_format, $certtype)
    {
        require_once(APPPATH."controllers/common/phpseclib/File/X509.php");
        
        if ($cert_format === "PFX")
            $certificate_filename = $certificate_filename. ".ns";
            
        $x509 = new File_X509();
        $x509_str = file_get_contents("/nsconfig/ssl/".$certificate_filename);
        
        if (!$x509_str)
            return false;
        
    	$cert_content = "";
    	$cert_content = str_replace(PHP_EOL, '', $x509_str);
    	$verified_cert = false;

        if ($this->is_ascii($x509_str))
        {
            $is_trusted_pfx = false;
            $begin_cert = strpos ($x509_str, "-----BEGIN CERTIFICATE-----");
            if (! $begin_cert)
            {
                $begin_cert = strpos ($x509_str, "-----BEGIN TRUSTED CERTIFICATE-----");
                $is_trusted_pfx = true;
            }
            if ($begin_cert || ($begin_cert == 0))
            {
        		$end_str = "";
        		$end_cert = strpos ($x509_str, "-----END CERTIFICATE-----\n");
        		$end_str = "-----END CERTIFICATE-----\n";

        		if (! $end_cert)
                {
                    $end_cert = strpos ($x509_str, "-----END TRUSTED CERTIFICATE-----\n");
                    $end_str = "-----END TRUSTED CERTIFICATE-----\n";
                }

                if ($end_cert)
                {
                    $verified_cert = true;
                    $cert_content = substr($x509_str, $begin_cert, (($end_cert + strlen($end_str)) - $begin_cert));
                }
        		else
                {
                    $cert_content = $x509_str;
        		}
            }
        }
        else
            $verified_cert = true;
        
        $cert_content = str_replace(PHP_EOL, '', $cert_content);
            
        $is_client = false; $is_server = false; $is_ca = false; $is_intermediate_ca = false;
        // This will load the certificate to X509 php file.
        if ($verified_cert)
        {
            try
            {
                $cert = $x509->loadX509($cert_content);
            }
            catch(Exception $exception)
            {
                $cert = false;
            }
        
        /*
         * If the certificate is X509 encode i.e. either PEM or DER, it will succeed,
        * otherwise it will fail.
        */
        if ($cert)
        {
            $check_ca_from_extension = $x509->getExtension("id-ce-basicConstraints");
            $is_ca_from_extension = isset($check_ca_from_extension) ? $check_ca_from_extension["cA"] : false;
            
            $check_for_ca = ($certtype === "CA" || $certtype === "ICA") ? true : false;
            
            if (! $is_ca_from_extension && $check_for_ca)
                return false;
            
            if (! $is_ca_from_extension) 
            {
                $extensions = $x509->getExtensions();
                $ext_key_usage = $x509->getExtension("id-ce-extKeyUsage"); // it defines whether its client (id-kp-clientAuth) or server auth (id-kp-serverAuth)
                $ext_key_cert_type = $x509->getExtension("netscape-cert-type"); // it may or may not be defined, but if defined for Server the value is SSLServer and for Client its SSLClient
                if ($ext_key_usage)
                {
                    foreach ($ext_key_usage as $key => $value)
                    {
                            if ($value == "id-kp-clientAuth" || $value == "id-kp-emailProtection")
                        {
                            $is_client = true;
                            break;
                        }
                        else if ($value == "id-kp-serverAuth")
                        {
                            $is_server = true;
                            break;
                        }
                    }
                }
                else if ($ext_key_cert_type)
                {   
                    for ($i = 0; $i < count($ext_key_cert_type); $i++)
                    {
                        if ($ext_key_cert_type[$i] == "SSLClient")
                        {
                            $is_client = true;
                            break;
                        }
                        else if ($ext_key_cert_type[$i] == "SSLServer")
                        {
                            $is_server = true;
                            break;
                        }
                    }
                }
                else
                {
                    // In cases where its not able to determined the certificate, then by default let it be Server Certificate
                    $is_server = true;
                }
            }
            else
            {
                /*
                 * this means its a CA/Intermediate Cert
                  IF subject and issuer are same, then its a CA Cert otherwise its a Intermediate CA.
                */
                $get_issuer_dn = $x509->getIssuerDN(true);
                $get_cert_dn = $x509->getDN(true);
                
                if ($get_issuer_dn === $get_cert_dn)
                    $is_ca = true;
                else
                    $is_intermediate_ca = true;
            }
        }
        }
        
        if ($certtype === "SERVER")
            return $is_server;
        else if ($certtype === "CLIENT")
            return $is_client;
        else if ($certtype === "CA")
            return $is_ca || $is_intermediate_ca;
        else
            return false;
    }
    
     
    private function is_certificate_ca($certificate_filename)
    {
        require_once(APPPATH."controllers/common/phpseclib/File/X509.php");
        
        $x509 = new File_X509();
        $x509_str = file_get_contents($certificate_filename);
        $is_ca = false;
        
        $cert = $x509->loadX509($x509_str);
        if ($cert)
        {
            $check_ca_from_extension = $x509->getExtension("id-ce-basicConstraints");
            $is_ca = isset($check_ca_from_extension) ? $check_ca_from_extension["cA"] : false;
        }
        return $is_ca;
    }


    private function get_vpneula($object_name, $query_params)
    {
        $response = array();
        if(!$this->validate_session($response))
        {
            return $response;
        }

        $name = isset($object_name) ? $object_name : "";

        if($name == "") // Get all
        {
            $pageno = isset($query_params["pageno"]) ? (int)$query_params["pageno"] : 1; // Applicable for Get all only
            $pagesize = isset($query_params["pagesize"]) ? (int)$query_params["pagesize"] : 999999999; // Applicable for Get all only            
            $is_count_call = isset($query_params['count']) ? true : false; // Applicable for Get all only
            $vpneula_count = 0;              
            $vpneulas = array();     
            $filtered_vpneulas = array();

            $this->execute_command("getvpneula", "");
            $result = $this->get_model()->get_result();

            $response["errorcode"] = $result["rc"];
            $response["message"] = $result["message"];
            $response["severity"] = $result["severity"];
            if($result["rc"] != 0)
                return $response;     

            if($result["rc"] == 0 && count($result["List"]) > 0)
            {
                for($i = 0; $i < count($result["List"]); $i++)
                {
                    if(isset($query_params["filter"])) // Get filtered result only
                    {
                        require_once(APPPATH . "controllers/common/rapi_utils.php");
                        if(rapi_utils::does_filter_match($result["List"][$i], $this->filter))
                        {    
                            array_push($vpneulas, array("name" =>$result["List"][$i]["name"]));
                            $vpneula_count++;  
                        }    
                    }    
                    else // Get all
                    {    
                        array_push($vpneulas, array("name" =>$result["List"][$i]["name"]));
                        $vpneula_count++;                        
                    }
                }

                for($i = ($pageno-1)*$pagesize; $i < $vpneula_count; $i++)
                {
                    if(count($filtered_vpneulas) >= $pagesize)
                        break;                    

                    array_push($filtered_vpneulas, $vpneulas[$i]);
                }         
            }

            if($is_count_call)
            {    
                $counts = array();
                array_push($counts, array("__count" => $vpneula_count));
                $response["vpneula"] = $counts;
            }
            else
            {    
                if(count($filtered_vpneulas) > 0)
                    $response["vpneula"] = $filtered_vpneulas;  
            }
        }
        else 
        {    
            // Check this EULA already present or not
            $this->execute_command("getvpneula", array("name" => $name));
            $result = $this->get_model()->get_result();

 
            $response["errorcode"] = $result["rc"];
            $response["message"] = $result["message"];
            $response["severity"] = $result["severity"];
            if($result["rc"] != 0)
                return $response;        

            $langs = array("en","de","es", "fr", "ja");
            $eula_path = "/var/netscaler/logon/themes/EULA/resources/";
            $vpneula = array();
            $vpneula["name"] = $name;

            for($i = 0; $i < count($langs); $i++)
            {
                $lang_file = $langs[$i].".xml";
                $lang_content =  $langs[$i]."_content";

                $xml = simplexml_load_file($eula_path.$lang_file);

                if($xml == false)
                {
                    $response["errorcode"] = -1;
                    $response["message"] = "Cannot open EULA XML file ".$lang_file;
                    $response["severity"] = "ERROR";
                    return $response;
                }
                else 
                {
                    $partition_custom_strings_eula_entry = $xml->xpath("//Partition [@id='custom_strings'] /String [@id='".$name."']"); 

                    if($partition_custom_strings_eula_entry == false) // Entry missing
                    {
                        $vpneula[$lang_content] = "";
                        /* 
                        // Just ignore
                        $response["errorcode"] = 938;
                        $response["message"] = "Eula doesn't exist in file ".$lang_file." for ".$name;
                        $response["severity"] = "ERROR";
                        return $response;
                        */
                    }
                    else // Entry exists
                    {    
                        $vpneula[$lang_content] = (string)$partition_custom_strings_eula_entry[0][0];
                    }
                }
            }    

            $response["vpneula"] = array($vpneula);             
        }    

        return $response;        
    }

    private function update_vpneula($add_new)
    {
        $response = array();
        if(!$this->validate_session($response))
        {
            return $response;
        }

        if($add_new) // POST
            $data = $this->validate_arguments_for_post(array("object"), array(), false);
        else // PUT
            $data = $this->validate_arguments_for_put(array("object"), array(), false);

        $vpneula_data = json_decode($data["object"] ,true);
        $name = $vpneula_data["vpneula"]["name"];

        if($add_new) // Add new entry
        {    
            // Fire Nitro for add
            require_once(APPPATH. "controllers/common/neo_proxy.php");
            $request = new neo_proxy("127.0.0.1", null, null, $_SESSION["NSAPI"]);
            $req_obj = array("params" =>  array("warning" => "YES"), "vpneula" => array("name" => $name));            
            $request->post(array(new request("post", $req_obj, "EULA")), $responses);
            $response = $responses[0]->get_response();

            if($response["errorcode"] != 0 && $response["severity"] == "ERROR")
                return $response;  
        }
        else
        {
            $response["errorcode"] = 0;
            $response["message"] = "Done";
            $response["severity"] = "NONE";            
        }    
        
        // Proceed for xml entry update
        $langs = array("en","de","es", "fr", "ja");        
        $eula_path = "/var/netscaler/logon/themes/EULA/resources/";
        $tmp_path = "/var/nstmp/";

        require_once(APPPATH . "controllers/common/rapi_utils.php");
        if(($net_sftp = rapi_utils::get_sftp($sftp_response)) == false)
        {
            return $sftp_response;
        }

        for($i = 0; $i < count($langs); $i++)
        {
            $lang_file = $langs[$i].".xml";    

            if(!isset($vpneula_data["vpneula"][$langs[$i]."_content"]))
                continue; // Skip this language file

            $content = $vpneula_data["vpneula"][$langs[$i]."_content"];

            $xml = simplexml_load_file($eula_path.$lang_file);

            if($xml == false)
            {
                $response["errorcode"] = -1;
                $response["message"] = "EULA entry update success. But cannot open EULA XML file ".$lang_file." to update content.";
                $response["severity"] = "ERROR";
                return $response;
            }
            else 
            {
                $partition_custom_strings = $xml->xpath("//Partition [@id='custom_strings']"); 

                if($partition_custom_strings == false)
                {
                    $response["errorcode"] = -1;
                    $response["message"] = "EULA entry update success. But invalid EULA XML file ".$lang_file.". Cannot update content.";
                    $response["severity"] = "ERROR";
                    return $response;
                }    
                else
                {
                    $partition_custom_strings = $partition_custom_strings[0];
                    $partition_custom_strings_eula_entry = $partition_custom_strings->xpath("//String [@id='".$name."']");

                    if($partition_custom_strings_eula_entry == false) // Create new entry
                    {
                        $string_node = $partition_custom_strings->addChild("String");
                        $string_node->addAttribute('id', $name);
                        $string_node->{0} = $content;                        
                    }
                    else // If already present update
                    {    
                        $partition_custom_strings_eula_entry[0][0]->{0} = $content;
                    }

                    if($xml->saveXML($tmp_path.$lang_file) == false)
                    {
                        $response["errorcode"] = -1;
                        $response["message"] = "EULA entry update success. But cannot save EULA XML file ".$lang_file." to update content.";
                        $response["severity"] = "ERROR";
                        return $response;
                    }    
                    else
                    {    
                        $success = $net_sftp->put($eula_path.$lang_file, $tmp_path.$lang_file, NET_SFTP_LOCAL_FILE);

                        if($success === false)
                        {
                            $response["errorcode"] = -1;
                            $response["message"] = "EULA entry update success. But cannot save EULA XML file ".$lang_file." to update content.";
                            $response["severity"] = "ERROR";
                            return $response;
                        }
                    }    
                }
            }
        }

        return $response; 
    }

    private function delete_vpneula($object_name)
    {
        $response = array();
        if(!$this->validate_session($response))
        {
            return $response;
        }

        $name = isset($object_name) ? $object_name : ""; 

        // Fire Nitro for delete
        require_once(APPPATH. "controllers/common/neo_proxy.php");
        $request = new neo_proxy("127.0.0.1", null, null, $_SESSION["NSAPI"]);
        $req_obj = new request("delete", "", "EULA");
        $req_obj->set_additional_command_parms("vpneula/".rawurlencode(rawurlencode($name)));
        $request->delete(array($req_obj) ,$responses);
        $response = $responses[0]->get_response();        

        if($response["errorcode"] != 0 && $response["severity"] == "ERROR")            
            return $response;            
 
        // Proceed for xml entry delete
        $langs = array("en","de","es", "fr", "ja");
        $eula_path = "/var/netscaler/logon/themes/EULA/resources/";
        $tmp_path = "/var/nstmp/";

        require_once(APPPATH . "controllers/common/rapi_utils.php");
        if(($net_sftp = rapi_utils::get_sftp($sftp_response)) == false)
        {
            return $sftp_response;
        }        

        for($i = 0; $i < count($langs); $i++)
        {
            $lang_file = $langs[$i].".xml";

            $xml = simplexml_load_file($eula_path.$lang_file);

            if($xml == false)
            {
                $response["errorcode"] = -1;
                $response["message"] = "EULA deleted. But cannot open EULA file ".$lang_file." to update.";
                $response["severity"] = "ERROR";
                return $response;
            }
            else 
            {
                $partition_custom_strings_eula_entry = $xml->xpath("//Partition [@id='custom_strings']/String [@id='".$name."']"); 

                if($partition_custom_strings_eula_entry == false) // Entry missing
                {
                    /*
                    // Just ignore
                    $response["errorcode"] = 938;
                    $response["message"] = "EULA deleted. But EULA doesn't exist in file ".$lang_file." for ".$name." to update.";
                    $response["severity"] = "ERROR";
                    return $response;
                    */
                }
                else // Entry exists
                {   
                    foreach ($partition_custom_strings_eula_entry as $node) 
                        unset($node[0]);

                    if($xml->saveXML($tmp_path.$lang_file) == false)
                    {
                        $response["errorcode"] = -1;
                        $response["message"] = "EULA deleted. But cannot save EULA file ".$lang_file." to update.";
                        $response["severity"] = "ERROR";
                        return $response;
                    }    
                    else
                    {    
                        $success = $net_sftp->put($eula_path.$lang_file, $tmp_path.$lang_file, NET_SFTP_LOCAL_FILE);

                        if($success === false)
                        {
                            $response["errorcode"] = -1;
                            $response["message"] = "EULA deleted. But cannot save EULA file ".$lang_file." to update.";
                            $response["severity"] = "ERROR";
                            return $response;
                        }
                    }                    
                }
            }
        } 

        return $response;   
    }    

    private function set_import_filename_to_lowercase()
    {
		$imported_file_details = $_FILES["import_file_name"];
		$name = strtolower($imported_file_details["name"]);
		///$name = str_replace(" ", "%20", $name);	// must not replace space!
		$imported_file_details["name"] = $name;
		$_FILES["import_file_name"] = $imported_file_details;
	}

	// get and save list of scan names (Cenzic, Qualys...) based on files under /netscaler
	private function appfw_get_scan_xsl_files()
	{
		if (file_exists(APPFW_SCAN_XSL_FILES)) {
			$str = file_get_contents(APPFW_SCAN_XSL_FILES);
			$response =  unserialize($str);
			if ($response !== FALSE) {	// check if file is serializable or corrupted
				return $response;
			}
		}
		// get appfw scan xsl names (/netscaler/scan_*.xsl)
		require_once(APPPATH . "controllers/common/rapi_utils.php");
		$response = array();
		rapi_utils::populate_file_listing($response, "/netscaler/", "getappfwscanxsl", "/scan_(.*)\\.xsl$/");
		$response = $this->appfw_sort_scan_xsl_files($response, "getappfwscanxsl", "name");
		$serialized = serialize($response);
		file_put_contents(APPFW_SCAN_XSL_FILES, $serialized);
		return $response;
	}
	// sort scan names and returns $response ("name" => "Cenzic")
	private function appfw_sort_scan_xsl_files($response, $type, $key)
	{
		$names = array();
		$list = $response[$type];
        foreach($list as $item) {
			$names[] = $item[$key];
		}
		sort($names);
		$res = array();
        foreach($names as $name) {
			$item = array($key => $name);
			$res[] = $item;
		}
		$response[$type] = $res;
		return $response;
	}
}

?>
