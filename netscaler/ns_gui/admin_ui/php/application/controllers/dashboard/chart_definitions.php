<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class chart_definitions
{
    var $charts = array(
        "cpu_mem_dial"  =>  array(
            "chart_type"    =>  "dial",
            "cmd"           =>  "statns",
            "link"          =>  array("cmd" => "statns", "type" => "summary"),
            "alt_expr"      =>  "   if(is_vmpe_mode())
                                        return array('cntrs_name' => array('pktcpuusagepcnt','mgmtcpuusagepcnt','memusagepcnt'),
                                                     'title'      => array('Packet CPU Usage','Management CPU Usage','InUse Memory'));
                                    if(isset(RESULT['numcpus']) && RESULT['numcpus'] > 1)
                                        return array('cntrs_name' => array('rescpuusagepcnt','memusagepcnt'),
                                                     'title'      => array('Average CPU Usage','InUse Memory'));
                                    return array('cntrs_name' => array('cpuusagepcnt','memusagepcnt'),
                                                 'title'      => array('CPU Usage','InUse Memory'));
                            ",
            "title"         =>  "CPU/Memory Usage"
        ),
        "flash_used"  =>  array(
            "chart_type" =>  "pie_chart",
            "cmd"   =>  "statsystem",
            "cntrs_name" =>  array("disk0avail", "disk0used"),
            "cntrs_label" =>  array("Available", "Used"),
            "title" =>  "Disk Usage (/flash)",
        ),
        "var_used"  =>  array(
            "chart_type" =>  "pie_chart",
            "cmd"   =>  "statsystem",
            "cntrs_name" =>  array("disk1avail", "disk1used"),
            "cntrs_label" =>  array("Available", "Used"),
            "title" =>  "Disk Usage (/var)",
        ),
        "appfw_requests"  =>  array(
            "chart_type" =>  "pie_chart",
            "chart_properties"  =>  array("height"  =>  120),
            "cmd"   =>  "statappfw",
            "cntrs_name" =>  array("appfirewallresponses", "appfirewallaborts", "appfirewallredirects"),
            "cntrs_label" =>  array("Responses", "Aborts", "Redirects"),
            "title" =>  "Application Firewall Requests",
        ),
        "ca_hits"  =>  array(
            "chart_type" =>  "bar_chart",
            "cmd"   =>  "statca",
			"cntrs_name" => array("catotgetobjreq","catotlookuphit","catotobjstored","catotother","catotaudio","catotvideo"),
            "cntrs_value_function"  => "compute_ratio",
			"title" => "Content Accelerator Statistics - Summary",
        ),
        "content_accelerator_hits"  =>  array(
            "chart_type" =>  "bar_chart",
            "cmd"   =>  "statca",
			"cntrs_name" => array("catotgetobjreq","catotlookuphit","catotobjstored","catotother","catotaudio","catotvideo"),
            "cntrs_value_function"  => "compute_ratio",
			"title" => "Content Accelerator Hits",
        ),
        "http_method_requests"  =>  array(
            "chart_type" =>  "pie_chart",
            "chart_properties"  =>  array("height"  =>  120),
            "cmd"   =>  "statprotocolhttp",
            "cntrs_name" =>  array("httptotgets", "httptotposts", "httptotothers"),
            "cntrs_label" =>  array("GET", "POST", "Others"),
            "title" =>  "HTTP Request Methods",
		),
	    "bwc_requests_responses"  =>  array(
            "chart_type" =>  "pie_chart",
            "cmd"   =>  "statsystembw",
            "cntrs_name" =>  array("httpcltttlpmax", "httpcltttfpmax"),
            "cntrs_lable" => array("LPMAX", "FPMAX"),
            "title"   =>  "BWC Last/First packet Latency bands",
        ),

        "http_length_responses"  =>  array(
            "chart_type" =>  "pie_chart",
            "chart_properties"  =>  array("height"  =>  120),
            "cmd"   =>  "statprotocolhttp",
            "cntrs_name" =>  array("httptotclenresponses", "httptotchunkedresponses", "httperrnoreusemultipart", "httptotnoclenchunkresponses"),
            "cntrs_label" =>  array("Content-length", "Chunked", "Multi-part", "FIN-terminated"),
            "title" =>  "HTTP Responses",
        ),
        "ic_hits"  =>  array(
            "chart_type" =>  "pie_chart",
            "cmd"   =>  "statcache",
            "cntrs_name" =>  array("cachetothits", "cachetotmisses"),
            "cntrs_label" =>  array("Hits", "Misses"),
            "title" =>  "Integrated Cache Requests",
        ),
        "ip_lookups"  =>  array(
            "chart_type" =>  "pie_chart",
            "cmd"   =>  "statprotocolip",
            "cntrs_name" =>  array("NOCNTR", "iptotaddrlookupfail"),
            "cntrs_label" =>  array("Success", "Failure"),
            "title" =>  "IP Address Lookups",
            "NOCNTR"    =>  "return (RESULT['iptotaddrlookup'] - RESULT['iptotaddrlookupfail']);",
        ),
        "tcp_client_connections"  =>  array(
            "chart_type" =>  "pie_chart",
            "cmd"   =>  "statprotocoltcp",
            "cntrs_name" =>  array("tcpcurclientconnestablished", "tcpcurclientconnclosing", "tcpcurclientconnopening"),
            "cntrs_label" =>  array("Established", "Closing", "Opening"),
            "title" =>  "Client Connections",
        ),
        "tcp_server_connections"  =>  array(
            "chart_type" =>  "pie_chart",
            "cmd"   =>  "statprotocoltcp",
            "cntrs_name" =>  array("tcpcurserverconnestablished", "tcpcurserverconnclosing", "tcpcurserverconnopening"),
            "cntrs_label" =>  array("Established", "Closing", "Opening"),
            "title" =>  "Server Connections",
        ),
        "http_version_requests"  =>  array(
            "chart_type" =>  "bar_chart",
            "cmd"   =>  "statprotocolhttp",
            "cntrs_name" =>  array("httptot11requests", "httptot10requests"),
            "cntrs_value_function"  => "compute_ratio",
            "tooltip"   =>  "HTTP Request Versions",
        ),
        "http_length_requests"  =>  array(
            "chart_type" =>  "bar_chart",
            "cmd"   =>  "statprotocolhttp",
            "cntrs_name" =>  array("httptotchunkedrequests", "httptotclenrequests"),
            "cntrs_value_function"  => "compute_ratio",
            "tooltip"   =>  "HTTP Requests",
        ),
        "http_version_responses"  =>  array(
            "chart_type" =>  "bar_chart",
            "cmd"   =>  "statprotocolhttp",
            "cntrs_name" =>  array("httptot11responses", "httptot10responses"),
            "cntrs_value_function"  => "compute_ratio",
            "tooltip"   =>  "HTTP Response Versions",
        ),
        "ip_pkts"  =>  array(
            "chart_type" =>  "bar_chart",
            "chart_properties"  =>  array("linear_scale_max"  =>  "MAX_OF_VALUES", "label_format"   =>  "{value|g4%%GRP_SEP%%%%DEC_SEP%%}", "horizontal"    =>  false),
            "cmd"   =>  "statprotocolip",
            "cntrs_name" =>  array("iptottxpkts", "iptotrxpkts"),
            "cntrs_label" =>  array("Tx", "Rx"),
            "title" =>  "IP Packets",
        ),
        "ip_bytes"  =>  array(
            "chart_type" =>  "bar_chart",
            "chart_properties"  =>  array("linear_scale_max"  =>  "MAX_OF_VALUES", "label_format"   =>  "{value|g4%%GRP_SEP%%%%DEC_SEP%%}", "horizontal"    =>  false),
            "cmd"   =>  "statprotocolip",
            "cntrs_name" =>  array("iptottxbytes", "iptotrxbytes"),
            "cntrs_label" =>  array("Tx", "Rx"),
            "title" =>  "IP Bytes",
        ),
        "aaa_authentication"  =>  array(
            "chart_type" =>  "bar_chart",
            "cmd"   =>  "stataaa",
            "cntrs_name" =>  array("aaaauthsuccess"),
            "chart_properties"  =>  array("use_border_color"  =>  true),
            "total_cntr_expr" =>  "return (RESULT['aaaauthsuccess'] + RESULT['aaaauthfail']);",
            "cntrs_value_function"  => "compute_ratio",
            "title" =>  "Authentication Success Ratio",
        ),
        "aaa_http_authorization"  =>  array(
            "chart_type" =>  "bar_chart",
            "cmd"   =>  "stataaa",
            "cntrs_name" =>  array("aaaauthonlyhttpsuccess"),
            "chart_properties"  =>  array("use_border_color"  =>  true),
            "total_cntr_expr" =>  "return (RESULT['aaaauthonlyhttpsuccess'] + RESULT['aaaauthonlyhttpfail']);",
            "cntrs_value_function"  => "compute_ratio",
            "title" =>  "HTTP Authorization Success Ratio",
        ),
        "aaa_non_http_authorization"  =>  array(
            "chart_type" =>  "bar_chart",
            "cmd"   =>  "stataaa",
            "cntrs_name" =>  array("aaaauthnonhttpsuccess"),
            "chart_properties"  =>  array("use_border_color"  =>  true),
            "total_cntr_expr" =>  "return (RESULT['aaaauthnonhttpsuccess'] + RESULT['aaaauthnonhttpfail']);",
            "cntrs_value_function"  => "compute_ratio",
            "title" =>  "Non HTTP Authorization Success Ratio",
        ),
        "appfw_violations"  =>  array(
            "chart_type" =>  "bar_chart",
            "cmd"   =>  "statappfw",
            "cntrs_name" =>  array("appfirewallviolxmlgenviolations", "appfirewallviolxmlsoapfaultviolations","appfirewallviolxmlattachmentviolations","appfirewallviolxmlxssviolations","appfirewallviolxmlsqlviolations","appfirewallviolwsiviolations","appfirewallviolmsgvalviolations","appfirewallviolxdosviolations","appfirewallviolwellformednessviolations","appfirewallviolsignature","appfirewallviolsafeobject","appfirewallviolcreditcard","appfirewallviolfieldconsistency","appfirewallviolfieldformat","appfirewallviolsql","appfirewallviolxss","appfirewallviolcsrftag", "appfirewallviolcookie","appfirewallviolbufferoverflow","appfirewallviolrefererheader", "appfirewallvioldenyurl","appfirewallviolstarturl"),
            "total_cntr" => "appfirewalltotalviol",
            "cntrs_value_function"  => "compute_ratio",
            "title" =>  "HTML/XML Violations",
        ),
        "appfw_requests_per_profile"  =>  array(
            "chart_type" =>  "pie_chart",
            "chart_properties"  =>  array("height"  =>  180),
            "cmd"   =>  "statappfwprofile",
            "entity_cmd" =>  true,
            "cntrs_name" =>  array("appfirewallresponsesperprofile", "appfirewallabortsperprofile", "appfirewallredirectsperprofile"),
            "cntrs_label" =>  array("Responses", "Aborts", "Redirects"),
            "title" =>  "Application Firewall Requests Per Profile",
        ),
        "per_cpu_usage"  =>  array(
            "chart_type"        =>  "bar_chart",
            "chart_properties"  =>  array("bg_bar" => true, "use_zones" =>  true),
            "cmd"               =>  "statsystemcpu",
            "entity_cmd"        =>  true,
            "cntrs_name"        =>  array("percpuuse"),
            "title"             =>  "CPU Usage",
        ),
        "appfw_violations_per_profile"  =>  array(
            "chart_type" =>  "bar_chart",
            "cmd"   =>  "statappfwprofile",
            "entity_cmd" =>  true,
            "cntrs_name" =>  array("appfirewallviolxmlgenericviolationsperprofile", "appfirewallviolxmlsoapfaultviolationsperprofile","appfirewallviolxmlattachmentviolationsperprofile","appfirewallviolxmlxssviolationsperprofile","appfirewallviolxmlsqlviolationsperprofile","appfirewallviolwsiviolationsperprofile","appfirewallviolmsgvalviolationsperprofile","appfirewallviolxdosviolationsperprofile","appfirewallviolwellformednessviolationsperprofile","appfirewallviolsignatureperprofile","appfirewallviolsafeobjectperprofile","appfirewallviolcreditcardperprofile","appfirewallviolfieldconsistencyperprofile","appfirewallviolfieldformatperprofile","appfirewallviolsqlperprofile","appfirewallviolxssperprofile","appfirewallviolcsrftagperprofile", "appfirewallviolcookieperprofile","appfirewallviolbufferoverflowperprofile","appfirewallviolrefererheaderperprofile","appfirewallvioldenyurlperprofile","appfirewallviolstarturlperprofile"),
            "total_cntr" => "appfirewalltotalviolperprofile",
            "cntrs_value_function"  => "compute_ratio",
            "title" =>  "HTML/XML Violations",
        ),
    );

    public function get_chart_definition($chart_name)
    {
        if(isset($this->charts[$chart_name]))
            return $this->charts[$chart_name];
        return null;
    }

    static function compute_ratio($values, $total)
    {
        if($total == 0)
            return $values;
        $count = count($values);
    
        for($i = 0; $i < $count; $i++)
        {
            $values[$i] = (($values[$i])/$total) * 100;
            $values[$i] = sprintf("%.2f", $values[$i]);
        }
        return $values;
    }
}
?>
