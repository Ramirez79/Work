<?php if(!defined("BASEPATH")) exit("No direct script access allowed");

class syslog_regex_rules extends abstract_controller
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function get_regex_rules($file_name)
    {
        if ($file_name == "")
            return;
        
        $syslog_regex_rules = null;
        switch($file_name)
        {
            case "ns":
                $syslog_regex_rules = $this->get_nslog_regex();
                break;
            case "nsvpn":
                $syslog_regex_rules = $this->get_nsvpnlog_regex();
                break;
        }
        
        return $syslog_regex_rules;
    }
    
    private function get_nslog_regex()
    {
        $appfw_cef_on_fields = array(
            array("key" => "system_time", "regex" => "([a-z]{3} [\s\d]\d \d\d:\d\d:\d\d)", "type" => "time"),
            array("key" => "severity", "regex" => "<.*\d+\.(.*?)>", "uppercase" => true),
            array("key" => "server_ip", "regex" => "(.*?)"),
            array("key" => "version", "regex" => "CEF:0\|Citrix\|NetScaler\|(.*?)"),
            array("key" => "module", "regex" => "\|(.*?)"),
            array("key" => "event_type", "regex" => "\|(.*?)"),
            array("key" => "severity_num", "regex" => "\|(.*?)"),
            array("key" => "meta_fields", "regex" => "\|(.*)")
        );
        $appfw_partition_cef_off_fields = array(
            array("key" => "system_time", "regex" => "([a-z]{3} [\s\d]\d \d\d:\d\d:\d\d)", "type" => "time"),
            array("key" => "severity", "regex" => "<.*\d+\.(.*?)>", "uppercase" => true),
            array("key" => "server_ip", "regex" => "(.*?)", "to_send" => false),
            array("key" => "gs_time", "regex" => "(\d\d\/\d\d\/\d\d\d\d:\d\d:\d\d:\d\d)\s[a-z]*", "to_send" => false),
            array("key" => "host_name", "regex" => "(.*?)", "to_send" => false),
            array("key" => "ppe", "regex" => "(.*?)\s:", "to_send" => false),
            array("key" => "partition_name", "regex" => "(.*?)"),
            array("key" => "module", "regex" => "(.*?)"),
            array("key" => "event_type", "regex" => "(.*?)"),
            array("key" => "event_id", "regex" => "(.*?)", "type" => "int"),
            array("key" => "zero", "regex" => "(.*?)\s:", "to_send" => false),
            array("key" => "client_ip", "regex" => "\s*(.*?)"),
            array("key" => "transaction_id", "regex" => "(.*?)-PPE\d+"),
            array("key" => "session_id", "regex" => "(.*?)"),
            array("key" => "cs1", "regex" => "(.*?)", "to_send" => false),
            array("key" => "request_url", "regex" => "(.*?)", "to_send" => false),
            array("key" => "message", "regex" => "(.*?)"),
            array("key" => "action", "regex" => "<(.*?)>")
        );
        $appfw_cef_off_fields = array(
            array("key" => "system_time", "regex" => "([a-z]{3} [\s\d]\d \d\d:\d\d:\d\d)", "type" => "time"),
            array("key" => "severity", "regex" => "<.*\d+\.(.*?)>", "uppercase" => true),
            array("key" => "server_ip", "regex" => "(.*?)", "to_send" => false),
            array("key" => "gs_time", "regex" => "(\d\d\/\d\d\/\d\d\d\d:\d\d:\d\d:\d\d)\s[a-z]*", "to_send" => false),
            array("key" => "host_name", "regex" => "(.*?)", "to_send" => false),
            array("key" => "ppe", "regex" => "(.*?)\s:", "to_send" => false),
            array("key" => "module", "regex" => "(.*?)"),
            array("key" => "event_type", "regex" => "(.*?)"),
            array("key" => "event_id", "regex" => "(.*?)", "type" => "int"),
            array("key" => "zero", "regex" => "(.*?)\s:", "to_send" => false),
            array("key" => "client_ip", "regex" => "\s*(.*?)"),
            array("key" => "transaction_id", "regex" => "(.*?)-PPE\d+"),
            array("key" => "session_id", "regex" => "(.*?)"),
            array("key" => "cs1", "regex" => "(.*?)", "to_send" => false),
            array("key" => "request_url", "regex" => "(.*?)", "to_send" => false),
            array("key" => "message", "regex" => "(.*?)"),
            array("key" => "action", "regex" => "<(.*?)>")
        );
        $generic_partition_fields = array(
            array("key" => "system_time", "regex" => "([a-z]{3} [\s\d]\d \d\d:\d\d:\d\d)", "type" => "time"),
            array("key" => "severity", "regex" => "<.*\d+\.(.*?)>", "uppercase" => true),
            array("key" => "server_ip", "regex" => "(.*?)", "to_send" => false),
            array("key" => "gs_time", "regex" => "(\d\d\/\d\d\/\d\d\d\d:\d\d:\d\d:\d\d)\s[a-z]*", "to_send" => false), //GMT or system time based on configuration
            array("key" => "host_name", "regex" => "(.*?)", "to_send" => false), //get host name & use as regex
            array("key" => "ppe", "regex" => "(.*?)\s:", "to_send" => false),
            array("key" => "partition_name", "regex" => "(.*?)"),
            array("key" => "module", "regex" => "(.*?)"),
            array("key" => "event_type", "regex" => "(.*?)"),
            array("key" => "event_id", "regex" => "(.*?)", "type" => "int"),
            array("key" => "zero", "regex" => "(.*?)\s:", "to_send" => false),
            array("key" => "message", "regex" => "\s*(.*?)")
        );
        $generic_fields = array(
            array("key" => "system_time", "regex" => "([a-z]{3} [\s\d]\d \d\d:\d\d:\d\d)", "type" => "time"),
            array("key" => "severity", "regex" => "<.*\d+\.(.*?)>", "uppercase" => true),
            array("key" => "server_ip", "regex" => "(.*?)", "to_send" => false),
            array("key" => "gs_time", "regex" => "(\d\d\/\d\d\/\d\d\d\d:\d\d:\d\d:\d\d)\s[a-z]*", "to_send" => false), //GMT or system time based on configuration
            array("key" => "host_name", "regex" => "(.*?)", "to_send" => false), //get host name & use as regex
            array("key" => "ppe", "regex" => "(.*?)\s:", "to_send" => false),
            array("key" => "module", "regex" => "(.*?)"),
            array("key" => "event_type", "regex" => "(.*?)"),
            array("key" => "event_id", "regex" => "(.*?)", "type" => "int"),
            array("key" => "zero", "regex" => "(.*?)\s:", "to_send" => false),
            array("key" => "message", "regex" => "\s*(.*?)")
        );
        $info_fields = array(
            array("key" => "system_time", "regex" => "([a-z]{3} [\s\d]\d \d\d:\d\d:\d\d)", "type" => "time"),
            array("key" => "severity", "regex" => "<.*\d+\.(.*?)>", "uppercase" => true),
            array("key" => "host_name", "regex" => "(.*?)", "to_send" => false),
            array("key" => "message", "regex" => "\s*(.*?)")
        );
        $logfile_turnedover_fields = array(
            array("key" => "system_time", "regex" => "([a-z]{3} [\s\d]\d \d\d:\d\d:\d\d)", "type" => "time"),
            array("key" => "host_name", "regex" => "(.*?)", "to_send" => false), //get host name & use as regex
            array("key" => "message", "regex" => "\s*(.*?)")
        );
        $syslog_regex_details[] = array(
            "fields" => $appfw_cef_on_fields,
            "regex" => "/^([a-z]{3} [\\s\\d]\\d \\d\\d:\\d\\d:\\d\\d)\\s<.*\\d+\\.(.*?)>\\s(.*?)\\sCEF:0\\|Citrix\\|NetScaler\\|(.*?)\\|(.*?)\\|(.*?)\\|(.*?)\\|(.*)$/i"
        );
        $syslog_regex_details[] = array(
            "fields" => $appfw_partition_cef_off_fields,
            "regex" => "/^([a-z]{3} [\\s\\d]\\d \\d\\d:\\d\\d:\\d\\d)\\s<.*\\d+\\.(.*?)>\\s(.*?)\\s(\\d\\d\\/\\d\\d\\/\\d\\d\\d\\d:\\d\\d:\\d\\d:\\d\\d)\\s[a-z]*\\s(.*?)\\s(.*?)\\s:\\s(.*?)\\s(.*?)\\s(.*?)\\s(.*?)\\s(.*?)\\s:\\s\\s*(.*?)\\s(.*?)-PPE\\d+\\s(.*?)\\s(.*?)\\s(.*?)\\s(.*?)\\s<(.*?)>$/i"
        );
        $syslog_regex_details[] = array(
            "fields" => $appfw_cef_off_fields,
            "regex" => "/^([a-z]{3} [\\s\\d]\\d \\d\\d:\\d\\d:\\d\\d)\\s<.*\\d+\\.(.*?)>\\s(.*?)\\s(\\d\\d\\/\\d\\d\\/\\d\\d\\d\\d:\\d\\d:\\d\\d:\\d\\d)\\s[a-z]*\\s(.*?)\\s(.*?)\\s:\\s(.*?)\\s(.*?)\\s(.*?)\\s(.*?)\\s:\\s\\s*(.*?)\\s(.*?)-PPE\\d+\\s(.*?)\\s(.*?)\\s(.*?)\\s(.*?)\\s<(.*?)>$/i"
        );
        $syslog_regex_details[] = array(
            "fields" => $generic_partition_fields,
            "regex" => $this->get_syslog_regex($generic_partition_fields)
        );
        $syslog_regex_details[] = array(
            "fields" => $generic_fields,
            "regex" => $this->get_syslog_regex($generic_fields)
        );
        $syslog_regex_details[] = array(
            "fields" => $info_fields,
            "regex" => "/^([a-z]{3} [\\s\\d]\\d \\d\\d:\\d\\d:\\d\\d)\\s<.*\\d+\\.(.*?)>\\s(.*?)\\s\\s*(.*?)$/i"
        );
        $syslog_regex_details[] = array(
            "fields" => $logfile_turnedover_fields,
            "regex" => $this->get_syslog_regex($logfile_turnedover_fields)
        );
        
        return $syslog_regex_details;
    }
    
    private function get_nsvpnlog_regex()
    {
        $nsvpn_auth_fields = array(
            array("key" => "system_time", "regex" => "([a-z]{3} [\s\d]\d \d\d:\d\d:\d\d)", "type" => "time"),
            array("key" => "severity", "regex" => "<.*\d+\.(.*?)>", "uppercase" => true),
            array("key" => "host_name", "regex" => "(.*?)", "to_send" => false),
            array("key" => "process_id", "regex" => "\[(.*?)\]:"),
            array("key" => "auth_type", "regex" => "\s*(.*?)\sauth:"),
            array("key" => "method", "regex" => "\s*In\s(.*?):"),
            array("key" => "message", "regex" => "\s*(.*?)")
        );
        $nsvpn_fields = array(
            array("key" => "system_time", "regex" => "([a-z]{3} [\s\d]\d \d\d:\d\d:\d\d)", "type" => "time"),
            array("key" => "severity", "regex" => "<.*\d+\.(.*?)>", "uppercase" => true),
            array("key" => "host_name", "regex" => "(.*?)", "to_send" => false),
            array("key" => "process_id", "regex" => "\[(.*?)\]:"),
            array("key" => "method", "regex" => "\s*In\s(.*?):"),
            array("key" => "message", "regex" => "\s*(.*?)")
        );
        $info_fields = array(
            array("key" => "system_time", "regex" => "([a-z]{3} [\s\d]\d \d\d:\d\d:\d\d)", "type" => "time"),
            array("key" => "severity", "regex" => "<.*\d+\.(.*?)>", "uppercase" => true),
            array("key" => "host_name", "regex" => "(.*?)", "to_send" => false),
            array("key" => "message", "regex" => "\s*(.*?)")
        );
        $logfile_turnedover_fields = array(
            array("key" => "system_time", "regex" => "([a-z]{3} [\s\d]\d \d\d:\d\d:\d\d)", "type" => "time"),
            array("key" => "host_name", "regex" => "(.*?)", "to_send" => false),
            array("key" => "message", "regex" => "\s*(.*?)")
        );
        $syslog_regex_details[] = array(
            "fields" => $nsvpn_auth_fields,
            "regex" => "/^([a-z]{3} [\\s\\d]\\d \\d\\d:\\d\\d:\\d\\d)\\s<.*\\d+\\.(.*?)>\\s(.*?)\\s\\[(.*?)\\]:\\s*(.*?)\\sauth:\\s*In\\s(.*?):\\s*(.*?)$/i"
        );
        $syslog_regex_details[] = array(
            "fields" => $nsvpn_fields,
            "regex" => "/^([a-z]{3} [\\s\\d]\\d \\d\\d:\\d\\d:\\d\\d)\\s<.*\\d+\\.(.*?)>\\s(.*?)\\s\\[(.*?)\\]:\\s*In\\s(.*?):\\s*(.*?)$/i"
        );
        $syslog_regex_details[] = array(
            "fields" => $info_fields,
            "regex" => "/^([a-z]{3} [\\s\\d]\\d \\d\\d:\\d\\d:\\d\\d)\\s<.*\\d+\\.(.*?)>\\s(.*?)\\s\\s*(.*?)$/i"
        );
        $syslog_regex_details[] = array(
            "fields" => $logfile_turnedover_fields,
            "regex" => $this->get_syslog_regex($logfile_turnedover_fields)
        );
        
        return $syslog_regex_details;
    }
    
    private function get_syslog_regex($fields)
    {
        $regex = "";
        foreach($fields as $field)
        {
            if($regex != "")
                $regex .= "\s";
            $regex .= $field["regex"];
        }
        return "/^" . $regex . "$/i";
    }
}
?>
