<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
define("LOCAL_HOST", "127.0.0.1");

//UMPE
define("CPU_CORE", "Packet CPU");

//Banner Constants
define("LOGIN_BANNER_FILE", "/etc/motd");

//graph types
define("LINE_GRAPH", "line");   
define("BAR_GRAPH", "bar");
define("AREA_GRAPH", "area");
define("SBAR_GRAPH", "sbar");
define("SAREA_GRAPH", "sarea");
define("SCATTER_GRAPH", "scatter");
define("SPLINE_GRAPH", "spline");
define("STEP_LINE_GRAPH", "step");

//Color constants
define("BLACK_COLOR", 0x000000);
define("WHITE_COLOR", 0xFFFFFF);
define("TRANSPARENT_WHITE_COLOR", 0x80FFFFFF);
define("GREEN_COLOR", 0x00FF00);
define("YELLOW_COLOR", 0xFFFF00);
define("RED_COLOR", 0xFF0000);
define("BLUE_COLOR", 0x336699);
define("GREY_COLOR", 0xcccccc);
define("TRANSPARENT_COLOR", 0x80000000);
define("BORDER_COLOR", 0xE5E5E5);

//Font constants
define("HELVETICA_FONT", "helvR.pfc");
define("HELVETICA_BOLD_FONT", "helvB.pfc");

//Chart Director's license code
define("LICENSE_CODE", "RDST-34DJ-CY6Q-HRYL-71E4-D3D4");
define("IMAGE_HEADER", "Content-type: image/png");
define("XML_HEADER", "Content-type: text/xml");
define("JSON_HEADER", "Content-type: application/json; charset=utf-8");

//XML API unit for getsystemglobaldata
define("HOURS", "hours");
define("DAYS", "days");
define("WEEKS", "weeks");
define("MONTHS", "months");
define("YEARS", "years");

//default values for reporting
define("LAST_DEFAULT_VALUE", 1);
define("UNIT_DEFAULT_VALUE", DAYS);
define("GRAPH_TYPE_DEFAULT_VALUE", LINE_GRAPH);
define("MAX_NO_OF_DATA_SETS", 36);
define("ALL", "all");
define("NO_DATA", "No Data");

//Following constant's value is also used in reports.xsd for element's min & max occurances
define("MAX_NO_OF_CHARTS_PER_REPORT", 4);
define("MAX_NO_OF_CUSTOM_REPORTS", 256);
define("MAX_NO_OF_CUSTOM_FOLDERS", 128);
define("MAX_NO_OF_CHARS_IN_REPORT_NAME", 32);
define("SUCCESS_RESULT", "0");
define("CUSTOM_REPORTS_DIR", "/nsconfig/nstemplates/reports/");
define("CUSTOM_REPORTS_FILE_NAME", "reports.xml");
define("CUSTOM_REPORTS_FILE_PATH", CUSTOM_REPORTS_DIR . CUSTOM_REPORTS_FILE_NAME);
define("REPORTS_CONF_FILE_NAME", "reports.conf");
define("REPORTS_CONF_FILE_PATH", CUSTOM_REPORTS_DIR . REPORTS_CONF_FILE_NAME);
define("CUSTOM_REPORTS_TEMP_FILE_PATH", CUSTOM_REPORTS_FILE_PATH . ".temp");
define("CUSTOM_REPORTS_SCHEMA_FILE_PATH", $_SERVER['DOCUMENT_ROOT'] . "/admin_ui/php/" . APPPATH . "controllers/reporting/reports.xsd");
define("CUSTOM_REPORTS", "Custom Reports");
define("ALL_GROUP", "All");

//Dashboard page
define("MIN_TEXT_AREA_WIDTH", 75);
define("MAX_TEXT_AREA_WIDTH", 95);
define("INCREMENT_TEXT_AREA_WIDTH", 5);
define("MIN_FONT_SIZE", 8);
define("MAX_FONT_SIZE", 12);
define("INCREMENT_FONT_SIZE", 1);
define("NS_S_OFS", "OUT OF SERVICE");
define("NS_S_TROFS", "GOING OUT OF SERVICE");
define("NS_S_TROFS_DOWN", "DOWN WHEN GOING OUT OF SERVICE");
define("NS_S_UP", "UP");
define("NS_S_DOWN", "DOWN");
define("NS_S_BUSY", "BUSY");
define("NS_S_UNKNOWN", "UNKNOWN");
define("NS_S_DISABLED", "DISABLED");
define("NS_S_ENABLED", "ENABLED");
define("THROUGHPUT_RATE_LIMIT", "packets dropped due to licensed throughput rate being reached");
define("PPS_LIMIT", "packets dropped due to licensed PPS limits being reached");
define("ENABLE", "enable");
define("DISABLE", "disable");
define("STATE", "state");
define("AUTO_REFRESH_DELAY_KMPE", 14000); //In milli seconds
define("AUTO_REFRESH_DELAY_VMPE", 7000); //In milli seconds
define("MAX_NO_OF_SERIES", 16);
define("MAX_NO_OF_CUSTOM_CHARTS", 256);
define("MAX_NO_OF_CHARS_IN_CHART_NAME", 32);
define("CHART_ALREADY_EXISTS_RESULT", "CHART_ALREADY_EXISTS_RESULT");
define("CUSTOM_CHARTS_DIR", "/nsconfig/nstemplates/reports/");
define("CUSTOM_CHARTS_FILE_NAME", "charts.json");
define("CUSTOM_CHARTS_FILE_PATH", CUSTOM_CHARTS_DIR . CUSTOM_CHARTS_FILE_NAME);
define("CUSTOM_CHARTS_TEMP_FILE_PATH", CUSTOM_CHARTS_FILE_PATH . ".temp");
define("GAUGES_PAGE_NUMBER_KEY", "gauges_page_number");
define("ST_PAGE_NUMBER_KEY", "st_page_number");

define("NSERR_NOUSER", 354);
define("NSERR_NOUSER_MESSAGE", "Invalid username or password");
define("NSERR_SESSION_EXPIRED", 444);
define("NSERR_SESSION_EXPIRED_MESSAGE", "Session expired or cancelled. Please login again");
define("NSERR_TCPCONNFAIL", 1024);
define("NSERR_LOGINFAIL", 1025);
define("NSERR_NOLOGIN", 1026);
define("NSERR_AUTHTIMEOUT", 1027);
define("NSERR_NOTAUTHORIZED", 2138);
define("NSERR_NOTAUTHORIZED_MESSAGE", "Not authorized to execute this command");
define("NSERR_CLNOTEXIST", 2448);
define("NSERR_LICENSE", 0x164);
define("NSERR_FEATDISABLED", 0x42B);
define("NSERR_AS_DEFAULT_PROFILE_IS_BYPASS",0xCAA);
define("NSERR_INTERNAL_SERVER", 1011);

//Master state is secondary (defined in nsstrings.h & used in CLIenums.pm - masterstate)
define("NS_S_SECONDARY", "Secondary");
define("NSERR_NOT_PRIMARY", "You are connected to a secondary node; changes made to custom reports in this session will be overwritten as part of HA sync");

//This service belongs to a servicegroup (defined in sys/netscaler/server_info.h)
define("NSI_T_SVCGRP_SVC", 0x08000000);

//Dashboard page for application view
define("PUBLIC_END_POINT", "public endpoint");
define("PUBLIC_END_POINTS", "Public endpoints");
define("APP_UNIT", "application unit");
define("APP_UNITS", "Application units");
define("BOUND_SERVICE", "backend service");
define("BOUND_SERVICES", "Backend services");
define("TYPE_APP", "0_");
define("TYPE_FILE_SHARE", "1_");
define("TYPE_NETWORK", "2_");
define("STR_APP", "app_");
define("STR_APP_UNIT", "u_");
define("STR_APP_OTHER", "o_");
define("STR_OTHER", "default");
define("CS_GROUP", "statcsvserver");
define("CS_ARG_NAME", "name");
define("CS_ARG_TYPE", "NS_VALNAME");
define("LB_GROUP", "statlbvserver");
define("LB_ARG_NAME", "name");
define("LB_ARG_TYPE", "NS_VALNAME");
define("SVC_GROUP", "statservice");
define("SVC_ARG_NAME", "name");
define("SVC_ARG_TYPE", "NS_VALNAME");

//Different types of http requests
define("PAGE_REQUEST", 0);
define("AJAX_REQUEST", 1);  //For AJAX calls on Monitoring, Reporting pages etc
define("IMAGE_REQUEST", 2);
define("FILE_REQUEST", 3);
define("API_REQUEST", 4);   //For API requests like rapi

//Different types of entities state in Reporting
define("ACTIVE_ENTITY", "0");
define("INACTIVE_ENTITY", "1");
define("DELETED_ENTITY", "2");

//Constants for NEO_PROXY
define("PROXY_REQ_TIMEOUT", 5);
//Constants for BW
define("BW_SET", 1);
define("BW_NOT_SET", 0);

//Similar to data source names (defined in netscaler/config/collector/nsrrdcollect.h)
define("NS_RRD_DEFAULT_DS", "default");
define("NS_RRD_CLUSTER_DS", "default_cluster");

//Model constants
define("NITRO_MODEL_COUNT_PARAM", "__count");

define("DEFAULT_SYSLOG_DIR", "/var/log/");
define("BASE_TEMPLATES_DIR", "/nsconfig/nstemplates/");
define("APP_TEMPLATES_DIR", BASE_TEMPLATES_DIR . "applications/");
define("BASE_ENTITY_TEMPLATES_DIR", BASE_TEMPLATES_DIR . "entities/");
define("LB_ENTITY_TEMPLATES_DIR", BASE_ENTITY_TEMPLATES_DIR . "lb vserver/");

//default values for reporting
define("DEFAULT_NSLOG_DIR", "/var/log/");
define("SYSTEM_LOG_FILES", serialize(array("ns", "nsvpn")));
define("DEFAULT_NSLOG_FILE_NAME", "ns.log");

function is_default_data_source($name)
{
    return in_array($name, array(NS_RRD_DEFAULT_DS, NS_RRD_CLUSTER_DS));
}

//Similar to NS_MODE enum in usr.src/netscaler/nslib/nsapilib.h
define("NS_KMPE_MODE", 0x1);    //Legacy kernel mode packet engine.
define("NS_VMPE_MODE", 0x2);    //Multi-core mode packet engine

function get_ns_mode()
{
    return isset($_SESSION['ns_mode']) ? $_SESSION['ns_mode'] : NS_KMPE_MODE;
}

function is_kmpe_mode()
{
    return (get_ns_mode() & NS_KMPE_MODE);
}

function is_vmpe_mode()
{
    return (get_ns_mode() & NS_VMPE_MODE);
}

define("BYTEMOBILE_SYSID_T1100", "1405150");
define("BYTEMOBILE_SYSID_T1100_16", "1405152");
define("BYTEMOBILE_SYSID_T1100_96", "1405153");
define("BYTEMOBILE_SYSID_T1010", "675360");
define("BYTEMOBILE_SYSID_T1200", "2200120");
define("BYTEMOBILE_SYSID_T1300", "250010");

define("BYTEMOBILE_SYSID_T1300_40G", "250042");
define("BYTEMOBILE_SYSID_T1120", "250142");

define("CISCO_PLATFORM_SYSID", "450110");
define("CISCO_PLATFORM_OEM", "1");

//Keep this in sync with branding.js.txt
define("NS", "NS");
define("AGEE", "AGEE");
define("OCB", "OCB");
define("OCC", "OCC");
define("XM", "XENMOBILE");
define("BYTEMOBILE", "BYTEMOBILE");

function is_bytemobile()
{
    global $g_session_started;
    if($g_session_started)
        return isset($_SESSION["sysid"]) && in_array($_SESSION["sysid"], array(BYTEMOBILE_SYSID_T1100, BYTEMOBILE_SYSID_T1100_16, BYTEMOBILE_SYSID_T1100_96, BYTEMOBILE_SYSID_T1010, BYTEMOBILE_SYSID_T1200, BYTEMOBILE_SYSID_T1300, BYTEMOBILE_SYSID_T1300_40G, BYTEMOBILE_SYSID_T1120));

    $sysid = get_sysid();
    return  in_array($sysid , array(BYTEMOBILE_SYSID_T1100, BYTEMOBILE_SYSID_T1100_16, BYTEMOBILE_SYSID_T1100_96, BYTEMOBILE_SYSID_T1010, BYTEMOBILE_SYSID_T1200, BYTEMOBILE_SYSID_T1300, BYTEMOBILE_SYSID_T1300_40G, BYTEMOBILE_SYSID_T1120));
}

function is_cisco_paltform()
{
    global $g_session_started;
    if($g_session_started)
        return isset($_SESSION["oemid"]) && in_array($_SESSION["oemid"], array(CISCO_PLATFORM_OEM));

    $oemid = get_oem_id();
    return  in_array($oemid , array(CISCO_PLATFORM_OEM));
}

function get_oem_id()
{
    if(!isset($_SESSION["oemid"]))
    {
        $oemid = trim((string)exec("sysctl -n netscaler.vpx_distro_oem"));
        if (is_numeric($oemid))
            $_SESSION["oemid"] = $oemid;
        else
            $_SESSION["oemid"] = -1;
        
    }

    return $_SESSION["oemid"] ;
}

function get_branding()
{
    global $g_branding;
    if(!isset($g_branding))
    {
        if(is_bytemobile())
        {
            require_once(APPPATH. "controllers/common/branding/bytemobile_branding.php");
            $g_branding = new bytemobile_branding();
        }
        else if(is_agee())
        {
            require_once(APPPATH. "controllers/common/branding/agee_branding.php");
            $g_branding = new agee_branding();
        }
        else if(is_ocb())
        {
            require_once(APPPATH. "controllers/common/branding/ocb_branding.php");
            $g_branding = new ocb_branding();
        }
        else if(is_xenmobile())
        {
            require_once(APPPATH. "controllers/common/branding/xenmobile_branding.php");
            $g_branding = new xenmobile_branding();
        }
        else
        {
            require_once(APPPATH. "controllers/common/branding/citrix_branding.php");
            $g_branding = new citrix_branding();
        }
   }

   return $g_branding;
}

function get_sysid()
{
    if(!isset($_SESSION["sysid"]))
        $_SESSION["sysid"]  = trim((string)exec("sysctl -n netscaler.sysid"));

    return $_SESSION["sysid"] ;
}

function is_sgw()
{
    return isset($_SESSION["ns_is_sgw"]) && $_SESSION["ns_is_sgw"] == "true";
}

function is_ns()
{
    return isset($_SESSION["nsbrand"]) && $_SESSION["nsbrand"] == NS;
}
function is_bw()
{
	return isset($_SESSION["nsbw"]) && $_SESSION["nsbw"] == BW_SET;
}
function is_agee()
{
    return isset($_SESSION["nsbrand"]) && $_SESSION["nsbrand"] == AGEE;
}

function is_ocb()
{
    return isset($_SESSION["nsbrand"]) && $_SESSION["nsbrand"] == OCB;
}

function is_xenmobile()
{
    return isset($_SESSION["deployment_type"]) && $_SESSION["deployment_type"] == XM;
}

function is_sdx_present($ns_hardware)
{
    $sysid_to_check = array("450142","450143","450144","450145");
     if(in_array($ns_hardware,$sysid_to_check,false))
     {
        return true;
     }
     return false;
}

function is_occ()
{
    return isset($_SESSION["nsbrand"]) && $_SESSION["nsbrand"] == OCC;
}

function get_auto_refresh_delay()
{
    return is_vmpe_mode() ? AUTO_REFRESH_DELAY_VMPE : AUTO_REFRESH_DELAY_KMPE;
}

//PHP's empty() returns true for "0" & 0 values. This function considers "0" & 0 as non-empty.
//Passing undefined variables to empty() won't throw PHP error as it is a language construct.
//But passing undefined variables to ns_empty() will throw PHP error "Undefined variable".
//To avoid this error, accept argument by reference (creates the variable if not defined).
function ns_empty(&$var)
{
    return empty($var) && ($var != "0");
}

function detect_xen_center()
{
    //If NS GUI is embedded within XenCenter, this header will be available only on first request.
    //Create a cookie for the current session alone, which can be used later to adapt NS GUI.
    if(isset($_SERVER["HTTP_X_XENCENTER"]))
        setcookie("xenc", "xenc", 0);
}

function is_xen_center()
{
    return isset($_COOKIE["xenc"]) && ($_COOKIE["xenc"] == "xenc");
}

function get_logout_url()
{
    return "/menu/lo?rand=" . $_SESSION["rand"];
}
?>
