<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
// This class has utility functions used in Dashboard & Reporting related views
class view_utils
{
    /*
     ********* IMPORTANT ********
     * KEEP THIS IN SORTED ORDER
     ********* IMPORTANT ********
     */
    
    function get_stats_group_array()
    {

        require_once(APPPATH . "controllers/common/rapi.php");
        $rapi = new rapi(true);
        if($rapi->is_partiton_configured_and_is_not_default_partition())
        {
            return array(
                "statnsacl" => "ACL Statistics",
                "statnsacl6" => "Extended ACL6 Statistics",
                "statbridge" => "Bridge Statistics",
                "statcachepolicylabel" => "Cache Policy Label Statistics",
                "statcachepolicy" => "Cache Policy Statistics",
                "statcmppolicylabel" => "Compression Policy Label Statistics",
                "statcmppolicy" => "Compression Policy Statistics",
                "statcmp" => "Compression Statistics",
                "statcsvserver" => "Content Switching Statistics",
                "statdnspolicylabel" => "DNS Policy Label Statistics",
                "statdnsrecords" => "DNS Records Statistics",
                "statdns" => "DNS Statistics",
                "statnsmemory" => "Feature Memory Statistics",
                "statprotocolhttp" => "HTTP Statistics",
                "statprotocolicmp" => "ICMP Statistics",
                "statprotocolicmpv6" => "ICMPv6 Statistics",
                "statinat" => "INAT Statistics",
                "statinatsession" => "INAT Session Statistics", 
                "statcache" => "Integrated Cache Statistics",               
                "statprotocolip" => "IP Statistics",
                "stattunnelip" => "IP Tunnel Statistics",                
                "statipseccounters" => "IPSec Statistics",
                "statprotocolipv6" => "IPv6 Statistics",
                "stattunnelip6" => "IPv6 Tunnel Statistics",                
                "statnslimitidentifier" => "Limit Identifier",
                "statlldp" => "LLDP Statistics",
                "statlbvserver" => "Load Balancing Statistics",
                "statmediaclassification" => "Media Classification Statistics",
                "statnat64" => "NAT64 Statistics",
                "statnspartition" => "Partition Statistics",
                "statnspbr" => "PBR Statistics",
                "statnspbr6" => "PBR6 Statistics",
                "statrnatip" => "RNAT IP Statistics",
                "statrnat" => "RNAT Statistics",
                "statresponderpolicylabel" => "Responder Policy Label Statistics",
                "statresponderpolicy" => "Responder Policy Statistics",
                "statrewritepolicylabel" => "Rewrite Policy Label Statistics",
                "statrewritepolicy" => "Rewrite Policy Statistics",
                "statsnmp" => "SNMP Statistics",
                "statssl" => "SSL Statistics",
                "statservicegroup" => "Service Groups Statistics",
                "statservice" => "Services Statistics",
                "statnssimpleacl" => "Simple ACL Statistics",
                "statnssimpleacl6" => "Simple ACL6 Statistics",
                "statprotocoltcp" => "TCP Statistics",
                "statnstrafficdomain" => "Traffic Domain",
                "stattransformpolicylabel" => "Transform Policy Label Statistics",
                "stattransformpolicy" => "Transform Policy Statistics",
                "statprotocoludp" => "UDP Statistics",
                "statvlan" => "VLAN Statistics"
            );
        }
	else if(is_sgw())
	{
	    return array(
                "stataaa" => "AAA Statistics",
		"stathanode" => "HA Statistics",
		"statprotocolhttp" => "HTTP Statistics",
		"statinterface" => "Interface Statistics",
		"statns" => "System Overview",	
		"statssl" => "SSL Statistics",
		"statvpn" => "VPN Statistics",
		"statvpnvserver" =>  "VPN Virtual Servers Statistics"
	    );
	}
        else if((is_ns() || is_bytemobile() || is_xenmobile()) && is_bw())
        {
            return array(
                "stataaa" => "AAA Statistics",
                "statnsacl" => "ACL Statistics",
                "statnsacl6" => "Extended ACL6 Statistics",
                "statappflow" => "AppFlow Statistics",
                "statappfwprofile" => "Application Firewall (per Profile) Statistics",
                "statappfwpolicylabel" => "Application Firewall Policy Label Statistics",
                "statappfwpolicy" => "Application Firewall Policy Statistics",
                "statappfw" =>  "Application Firewall Statistics",
                "statappqoe" => "Application QoE Statistics",
                "stataudit" => "Audit Log Statistics",
				"statappqoepolicy" =>	"Application QoE Policy Statistics",
                "statauthenticationvserver" => "Authentication Virtual Servers Statistics",
                "statauthorizationpolicylabel" => "Authorization Policy Label Statistics",
                "statbridge" => "Bridge Statistics",
                "statcachecontentgroup" => "Content Group",
                "statsystemcpu" => "CPU Statistics",
                "statcrvserver" =>  "CR Virtual Servers Statistics",
                "statcachepolicylabel" => "Cache Policy Label Statistics",
                "statcachepolicy" => "Cache Policy Statistics",
                "statca" => "Content Accelerator Statistics",
                "statclusterinstance" => "Cluster Instance Statistics",
                "statclusternode" => "Cluster Node Statistics",
                "statcmppolicylabel" => "Compression Policy Label Statistics",
                "statcmppolicy" => "Compression Policy Statistics",
                "statcmp" => "Compression Statistics",
                "statcsvserver" => "Content Switching Statistics",
                "statdnspolicylabel" => "DNS Policy Label Statistics",
                "statdnsrecords" => "DNS Records Statistics",
                "statdns" => "DNS Statistics",
                "statnsmemory" => "Feature Memory Statistics",
				"statfeo" => "Front End Optimization",
                "statgslbdomain" =>  "GSLB Domains Statistics",
                "statgslbservice" =>  "GSLB Services Statistics",
                "statgslbsite" =>  "GSLB Sites Statistics",
                "statgslbvserver" =>  "GSLB Virtual Servers Statistics",
                "statsystemmemory" => "Global Memory Statistics",
                "stathanode" => "HA Statistics",
                "statdos" =>  "HTTP DoS (Global) Statistics",
                "statdospolicy" =>  "HTTP DoS Policy Statistics",
                "statprotocolhttp" => "HTTP Statistics",
                "staticapolicy" => "ICA Policy Statistics",
                "statprotocolicmp" => "ICMP Statistics",
                "statprotocolicmpv6" => "ICMPv6 Statistics",
                "statinat" => "INAT Statistics",
                "statinatsession" => "INAT Session Statistics",                   
                "statprotocolip" => "IP Statistics",
                "stattunnelip" => "IP Tunnel Statistics",                
                "statipseccounters" => "IPSec Statistics",
                "statprotocolipv6" => "IPv6 Statistics",
                "stattunnelip6" => "IPv6 Tunnel Statistics",                
                "statcache" => "Integrated Cache Statistics",
                "statinterface" => "Interface Statistics",
                "statnslimitidentifier" => "Limit Identifier",
                "statlldp" => "LLDP Statistics",
                "statlbvserver" => "Load Balancing Statistics",
                "statmediaclassification" => "Media Classification Statistics",                
                "statnat64" => "NAT64 Statistics",
                "statnspartition" => "Partition Statistics",
                "statnspbr" => "PBR Statistics",
                "statnspbr6" => "PBR6 Statistics",
                "statpq" =>  "Priority Queuing (Global) Statistics",
                "statpqpolicy" =>  "Priority Queuing Policy Statistics",
				"statqos" =>  "QoS Statistics",
                "statrnatip" => "RNAT IP Statistics",
                "statrnat" => "RNAT Statistics",
                "statresponderpolicylabel" => "Responder Policy Label Statistics",
                "statresponderpolicy" => "Responder Policy Statistics",
                "statrewritepolicylabel" => "Rewrite Policy Label Statistics",
                "statrewritepolicy" => "Rewrite Policy Statistics",
                "statspilloverpolicy" => "Spillover Policy Statistics",
                "statsnmp" => "SNMP Statistics",
                "statssl" => "SSL Statistics",
                "statservicegroup" => "Service Groups Statistics",
                "statservice" => "Services Statistics",
                "statnssimpleacl" => "Simple ACL Statistics",
                "statnssimpleacl6" => "Simple ACL6 Statistics",
                "statsc" =>  "Sure Connect (Global) Statistics",
                "statscpolicy" =>  "Sure Connect Policy Statistics",
                "statsystem" => "System Health",
                "statns" => "System Overview",
                "statprotocoltcp" => "TCP Statistics",
                "statnstrafficdomain" => "Traffic Domain",
                "stattmtrafficpolicy" => "Traffic Policy Statistics",
                "stattransformpolicylabel" => "Transform Policy Label Statistics",
                "stattransformpolicy" => "Transform Policy Statistics",
                "statprotocoludp" => "UDP Statistics",
                "statvlan" => "VLAN Statistics",
                "statvpn" => "VPN Statistics",
                "statvpnvserver" =>  "VPN Virtual Servers Statistics",		
                "statvxlan" => "VXLAN Statistics",
                "statsystembw" =>  "BW Statistics"
            );
		}
		else if((is_ns() || is_bytemobile() || is_xenmobile()) && !is_bw())
        {
            return array(
                "stataaa" => "AAA Statistics",
                "statnsacl" => "ACL Statistics",
                "statnsacl6" => "Extended ACL6 Statistics",
                "statappflow" => "AppFlow Statistics",
                "statappfwprofile" => "Application Firewall (per Profile) Statistics",
                "statappfwpolicylabel" => "Application Firewall Policy Label Statistics",
                "statappfwpolicy" => "Application Firewall Policy Statistics",
                "statappfw" =>  "Application Firewall Statistics",
                "stataudit" => "Audit Log Statistics",
				"statappqoepolicy" =>	"Application QoE Policy Statistics",
                "statauthenticationvserver" => "Authentication Virtual Servers Statistics",
                "statauthorizationpolicylabel" => "Authorization Policy Label Statistics",
                "statbridge" => "Bridge Statistics",
                "statcachecontentgroup" => "Content Group",
                "statsystemcpu" => "CPU Statistics",
                "statcrvserver" =>  "CR Virtual Servers Statistics",
                "statca" => "Content Accelerator Statistics",
                "statcachepolicylabel" => "Cache Policy Label Statistics",
                "statcachepolicy" => "Cache Policy Statistics",
                "statclusterinstance" => "Cluster Instance Statistics",
                "statclusternode" => "Cluster Node Statistics",
                "statcmppolicylabel" => "Compression Policy Label Statistics",
                "statcmppolicy" => "Compression Policy Statistics",
                "statcmp" => "Compression Statistics",
                "statcsvserver" => "Content Switching Statistics",
                "statdnspolicylabel" => "DNS Policy Label Statistics",
                "statdnsrecords" => "DNS Records Statistics",
                "statdns" => "DNS Statistics",
                "statnsmemory" => "Feature Memory Statistics",
				"statfeo" => "Front End Optimization",
                "statgslbdomain" =>  "GSLB Domains Statistics",
                "statgslbservice" =>  "GSLB Services Statistics",
                "statgslbsite" =>  "GSLB Sites Statistics",
                "statgslbvserver" =>  "GSLB Virtual Servers Statistics",
                "statsystemmemory" => "Global Memory Statistics",
                "stathanode" => "HA Statistics",
                "statdos" =>  "HTTP DoS (Global) Statistics",
                "statdospolicy" =>  "HTTP DoS Policy Statistics",
                "statprotocolhttp" => "HTTP Statistics",
                "staticapolicy" => "ICA Policy Statistics",
                "statprotocolicmp" => "ICMP Statistics",
                "statprotocolicmpv6" => "ICMPv6 Statistics",
                "statinat" => "INAT Statistics",
                "statinatsession" => "INAT Session Statistics",                   
                "statprotocolip" => "IP Statistics",
                "stattunnelip" => "IP Tunnel Statistics",                
                "statipseccounters" => "IPSec Statistics",
                "statprotocolipv6" => "IPv6 Statistics",
                "stattunnelip6" => "IPv6 Tunnel Statistics",                
                "statcache" => "Integrated Cache Statistics",
                "statinterface" => "Interface Statistics",
		        "statnslimitidentifier" => "Limit Identifier",
		        "statlldp" => "LLDP Statistics",
                "statlbvserver" => "Load Balancing Statistics",
                "statmediaclassification" => "Media Classification Statistics",                
                "statnat64" => "NAT64 Statistics",
                "statnspartition" => "Partition Statistics",
                "statnspbr" => "PBR Statistics",
                "statnspbr6" => "PBR6 Statistics",
                "statpq" =>  "Priority Queuing (Global) Statistics",
                "statpqpolicy" =>  "Priority Queuing Policy Statistics",
				"statqos" =>  "QoS Statistics",
                "statrnatip" => "RNAT IP Statistics",
                "statrnat" => "RNAT Statistics",
                "statresponderpolicylabel" => "Responder Policy Label Statistics",
                "statresponderpolicy" => "Responder Policy Statistics",
                "statrewritepolicylabel" => "Rewrite Policy Label Statistics",
                "statrewritepolicy" => "Rewrite Policy Statistics",
                "statsnmp" => "SNMP Statistics",
                "statssl" => "SSL Statistics",
                "statservicegroup" => "Service Groups Statistics",
                "statservice" => "Services Statistics",
                "statnssimpleacl" => "Simple ACL Statistics",
                "statnssimpleacl6" => "Simple ACL6 Statistics",
                "statsc" =>  "Sure Connect (Global) Statistics",
                "statscpolicy" =>  "Sure Connect Policy Statistics",
                "statsystem" => "System Health",
                "statns" => "System Overview",
                "statprotocoltcp" => "TCP Statistics",
                "statnstrafficdomain" => "Traffic Domain",
                "stattmtrafficpolicy" => "Traffic Policy Statistics",
                "stattransformpolicylabel" => "Transform Policy Label Statistics",
                "stattransformpolicy" => "Transform Policy Statistics",
                "statprotocoludp" => "UDP Statistics",
                "statvlan" => "VLAN Statistics",
                "statvpn" => "VPN Statistics",
				"statvpnvserver" =>  "VPN Virtual Servers Statistics",				
                "statvxlan" => "VXLAN Statistics",
                "statlsn" => "Large Scale NAT"
            );
		} 
        else if(is_agee())
        {
            return array(
                "stataaa" => "AAA Statistics",
                "statnsacl" => "ACL Statistics",
                "stataudit" => "Audit Log Statistics",
                "statauthorizationpolicylabel" => "Authorization Policy Label Statistics",
                "statsystemcpu" => "CPU Statistics",
                "statcachepolicylabel" => "Cache Policy Label Statistics",
                "statcachepolicy" => "Cache Policy Statistics",
                "statcmppolicylabel" => "Compression Policy Label Statistics",
                "statcmppolicy" => "Compression Policy Statistics",
                "statcmp" => "Compression Statistics",
                "statdns" => "DNS Statistics",
                "statnsmemory" => "Feature Memory Statistics",
                "statsystemmemory" => "Global Memory Statistics",
                "stathanode" => "HA Statistics",
                "statprotocolhttp" => "HTTP Statistics",
                "staticapolicy" => "ICA Policy Statistics",
                "statprotocolicmp" => "ICMP Statistics",
                "statinat" => "INAT Statistics",
                "statinatsession" => "INAT Session Statistics",                   
                "statprotocolip" => "IP Statistics",
                "stattunnelip" => "IP Tunnel Statistics",                
                "statcache" => "Integrated Cache Statistics",
                "statinterface" => "Interface Statistics",
                "statnslimitidentifier" => "Limit Identifier",
                "statnat64" => "NAT64 Statistics",
                "statresponderpolicylabel" => "Responder Policy Label Statistics",
                "statresponderpolicy" => "Responder Policy Statistics",
                "statrewritepolicylabel" => "Rewrite Policy Label Statistics",
                "statrewritepolicy" => "Rewrite Policy Statistics",
                "statspilloverpolicy" => "Spillover Policy Statistics",
                "statsnmp" => "SNMP Statistics",
                "statssl" => "SSL Statistics",
                "statnssimpleacl" => "Simple ACL Statistics",
                "statsystem" => "System Health",
                "statns" => "System Overview",
                "statprotocoltcp" => "TCP Statistics",
                "statnstrafficdomain" => "Traffic Domain",
                "stattmtrafficpolicy" => "Traffic Policy Statistics",
                "statprotocoludp" => "UDP Statistics",
                "statvlan" => "VLAN Statistics",
                "statvpn" => "VPN Statistics",
                "statvpnvserver" =>  "VPN Virtual Servers Statistics",
                "statvxlan" => "VXLAN Statistics"
            );
        }
        else if(is_ocb() || is_occ())
        {
            return array(
                "statappflow" => "AppFlow Statistics",
                "stataudit" => "Audit Log Statistics",
                "statbridge" => "Bridge Statistics",
                "statsystemcpu" => "CPU Statistics",
                "statcsvserver" => "Content Switching Statistics",
                "statnsmemory" => "Feature Memory Statistics",
				"statfeo" => "Front End Optimization",
                "statgslbdomain" =>  "GSLB Domains Statistics",
                "statgslbservice" =>  "GSLB Services Statistics",
                "statgslbsite" =>  "GSLB Sites Statistics",
                "statgslbvserver" =>  "GSLB Virtual Servers Statistics",
                "statsystemmemory" => "Global Memory Statistics",
                "stathanode" => "HA Statistics",
                "statprotocolhttp" => "HTTP Statistics",
                "statprotocolicmp" => "ICMP Statistics",
                "statprotocolicmpv6" => "ICMPv6 Statistics",
                "statinat" => "INAT Statistics",
                "statinatsession" => "INAT Session Statistics",                   
                "statprotocolip" => "IP Statistics",
                "stattunnelip" => "IP Tunnel Statistics",                
                "statipseccounters" => "IPSec Statistics",
                "statprotocolipv6" => "IPv6 Statistics",
                "stattunnelip6" => "IPv6 Tunnel Statistics",                
                "statinterface" => "Interface Statistics",
                "statnslimitidentifier" => "Limit Identifier",
                "statlldp" => "LLDP Statistics",
                "statlbvserver" => "Load Balancing Statistics",
                "statnat64" => "NAT64 Statistics",
                "statnspbr" => "PBR Statistics",
                "statnspbr6" => "PBR6 Statistics",
				"statqos" =>  "QoS Statistics",
                "statrnatip" => "RNAT IP Statistics",
                "statrnat" => "RNAT Statistics",
                "statresponderpolicylabel" => "Responder Policy Label Statistics",
                "statresponderpolicy" => "Responder Policy Statistics",
                "statrewritepolicylabel" => "Rewrite Policy Label Statistics",
                "statrewritepolicy" => "Rewrite Policy Statistics",
                "statspilloverpolicy" => "Spillover Policy Statistics",
                "statsnmp" => "SNMP Statistics",
                "statssl" => "SSL Statistics",
                "statservicegroup" => "Service Groups Statistics",
                "statservice" => "Services Statistics",
                "statsystem" => "System Health",
                "statns" => "System Overview",
                "statprotocoltcp" => "TCP Statistics",               
                "statnstrafficdomain" => "Traffic Domain",
                "stattmtrafficpolicy" => "Traffic Policy Statistics",
                "stattransformpolicylabel" => "Transform Policy Label Statistics",
                "stattransformpolicy" => "Transform Policy Statistics",
                "statprotocoludp" => "UDP Statistics",
                "statvlan" => "VLAN Statistics"
            );
        }

        return array();
    }

    function get_reporting_group_array($skip_service_groups = false)
    {
        $descriptivename = self::get_stats_group_array();

        if(isset($descriptivename["statservicegroup"]) && $skip_service_groups == true)
            unset($descriptivename["statservicegroup"]);
        if(!is_agee() && !isset($descriptivename["statservicegroupmember"]))
            $descriptivename["statservicegroupmember"] = "Service Group Member Statistics";
        
        return $descriptivename;
    }

    function is_rate_counter($counter)
    {
        if(preg_match('/Rate$/i', $counter))
            return 0;
        else
            return 1;
    }

    function get_rate_counter($name)
    {
        //? - is for greedy match, to avoid httptotothers getting mapped to httptohersRate
        //instead of the expected httpothersRate. Currently first occurrence of "tot" can
        //be removed always. But in future, if we have a valid "tot" first, then this would
        //fail and the counter needs to be hard-coded here
        if(preg_match('/total/', strtolower($name)))
            $newname = preg_replace('/(\w*?)total(\w*)/i', '$1$2Rate', $name);
        else if(preg_match('/tot/', strtolower($name)))
            $newname = preg_replace('/(\w*?)Tot(\w*)/i', '$1$2Rate', $name);
        else
            $newname = $name."Rate";

        return strtolower($newname);
    }

    function get_counter_descr($cntr, $cntrs, $use_descr2 = false)
    {
        $descr = ($use_descr2 && isset($cntr["cntrdescr2"])) ? $cntr["cntrdescr2"] : $cntr['cntrdescr'];
        if($descr == "")
        {
            foreach($cntrs as $cntr1)
            {
                $ratecounterStr = self::get_rate_counter($cntr1['cntrname']);
                if($ratecounterStr == $cntr['cntrname'])
                {
                    $descr = ($use_descr2 && isset($cntr1["cntrdescr2"])) ? $cntr1["cntrdescr2"] : $cntr1['cntrdescr'];
                    $descr = $descr . " (Rate)";
                    break;
                }
            }
        }

        if($descr == "")
            $descr = $cntr['cntrname'];

        return $descr;
    }

    function is_valid_stat_result($result, $request, $output_error=true)
    {
        if($result['rc'] == 0 && isset($result['List']) && (count($result['List']) > 0))
            return 1;
        else if($result['rc'] == 0)
            return 0;
        else
        {
            if($output_error && ($request == PAGE_REQUEST))
                self::print_error_result_page($result);
            else if($output_error && ($request == AJAX_REQUEST))
                self::print_error_result_xml($result, "data", false);
            return 0;
        }
    }

    function print_error_string($error)
    {
        print "<p align=\"center\" class=\"ns_alert_text\"><b>$error</b></p>";
    }

    function print_error_result_page($result, $disp_name="data")
    {
        self::print_error_string(self::get_error_string($result, PAGE_REQUEST, $disp_name));
    }

    function print_error_result_xml($result, $disp_name="data", $print_messages_tag = true)
    {
        header(XML_HEADER);
        $errtext = self::get_error_string($result, AJAX_REQUEST, $disp_name);
        if($print_messages_tag)
            print "<messages>\n";
        print "<error val=\"$errtext\" rc=\"" . $result['rc'] . "\"/>\n";
        if($print_messages_tag)
            print "</messages>";
    }

    function get_error_string($result, $request, $disp_name)
    {
        if($request == IMAGE_REQUEST || $request == FILE_REQUEST)
            return "Error retrieving $disp_name.\nReturn code = " . $result['rc'] . ".\nError message = " . $result['message'] . ".\n";
        else if($request == PAGE_REQUEST)
            return "Error retrieving $disp_name.<br>Return code = " . $result['rc'] . ".<br>Error message = " . $result['message'] . ".<br>";
        else if($request == AJAX_REQUEST)
            return "Error retrieving $disp_name.\\nReturn code = " . $result['rc'] . ".\\nError message = " . $result['message'] . ".\\n";
        return null;
    }

    public function put_xml_entry($col_name, $arg_val, $counter, $value_as_tooltip = false)
    {
        $arg_val = StatisticsRenderer::getcntrvalue($arg_val, $counter);
        $arg_val = StatisticsRenderer::process_value($arg_val, $value_as_tooltip);
        $arg_val = htmlspecialchars($arg_val); //It can have <img src=""> for state counters
        print "<message colname=\"$col_name\" val=\"$arg_val\"/>\n";
    }

    function is_service_group_member($result, $group)
    {
        if($group == "statservicegroupmember")
            return true;

        //LB VIP bindings include services and service group members both
        //sent with entityType as statservice. Use flags to differentiate.
        if(isset($result["entitytype"], $result["curtflags"]) &&
                ($result["entitytype"] == "statservice") &&
                ($result["curtflags"] & NSI_T_SVCGRP_SVC))
            return true;
        return false;
    }
    
    function is_local_site($obj, $key)
    {
	if(strcmp($obj[$key],"LOCAL") === 0)
	    return true;
	
	return false;
    }

    // Used for enable/disable operations on Dashboard
    function does_command_support_enable_disable($cmd)
    {
        if(in_array($cmd['cmdname'], array("statnsacl", "statnsacl6", "statinterface", "statnspbr", "statnspbr6")))
            return false;
        if($cmd['cmdname'] == "statgslbservice" || $cmd["cmdname"] == "statservicegroupmember")
            return true;
        return isset($cmd['enable']);
    }

    function get_enable_disable_command_name($cmd, $enable)
    {
        $replacement = ENABLE;
        if(!$enable)
            $replacement = DISABLE;
        if($cmd['cmdname'] == "statgslbservice" || $cmd["cmdname"] == "statservicegroupmember")
            return $replacement . "service";
        return preg_replace("/^stat/", $replacement, $cmd['cmdname']);
    }

    function get_state_counter_name($cmd)
    {
        return STATE;
    }

    function print_state_check_box_header($cmd)
    {
        print "\t<th width=\"25px\"><input type=\"checkbox\" id=\"" . $cmd['cmdname'] . "_all_ckb\" onclick=\"javascript:select_all('" . $cmd['cmdname'] . "')\" style=\"height: 13px\" title=\"Click to select/unselect all the rows\"></th>\n";
    }

    function print_state_check_box_cell($colorval, $cmd, $index, $val)
    {
        print "\t\t<td $colorval><input type=\"checkbox\" id=\"" . $cmd['cmdname'] . "_ckb_" . $index . "\" onclick=\"javascript:row_selected('" . $cmd['cmdname'] . "')\" value=\"" . urlencode(preg_replace("/\//", "%2F", $val)) . "\">";
        print "</td>\n";
    }

    function print_state_enable_disable_buttons($colspan, $cmd)
    {
		require_once(APPPATH."controllers/common/utils.php");
        $argname = utils::get_argname($cmd);
		
		print "\n\t<tr><td style=\"border: 0;\" colspan=\"" . $colspan . "\" align=\"left\"><input type=\"button\" value=\"Enable\" id=\"" . $cmd['cmdname'] . "_enable_button\" onclick=\"javascript:do_operation('enable', '" .$cmd['cmdname'] . "','" .$argname ."')\" class=\"flat_button enable_disable_button\" disabled>";
        print "\n\t<input type=\"button\" value=\"Disable\" id=\"" . $cmd['cmdname'] . "_disable_button\" onclick=\"javascript:do_operation('disable', '" .$cmd['cmdname']  . "','" .$argname ."')\" class=\"flat_button enable_disable_button\" disabled></td></TR>\n";
    }

    function print_entities_count_state($cmd, $index, $fixed_header_column_table = false)
    {
        print "<script type=\"text/javaScript\">";
        print "\nentities_count['" . $cmd['cmdname'] . "'] = " . ($index - 1) . ";";
        print "\nentities_state_counter['" . $cmd['cmdname'] . "'] = '" . self::get_state_counter_name($cmd) . "';";
        if($fixed_header_column_table)
            print "\nfixed_header_column_table_suffixes.push(\"" . $cmd["cmdname"] . "\");";
        print "\n</script>\n";
    }

    // Carries PHP state constants to Javascript
    function print_state_check_constants()
    {
        print "\n<script type=\"text/javascript\">";
        print "\nvar NS_S_OFS = \"" . NS_S_OFS . "\"";
        print "\nvar NS_S_TROFS = \"" . NS_S_TROFS . "\"";
        print "\nvar NS_S_TROFS_DOWN = \"" . NS_S_TROFS_DOWN . "\"";
        print "\nvar NS_S_UP = \"" . NS_S_UP . "\"";
        print "\nvar NS_S_DOWN = \"" . NS_S_DOWN . "\"";
        print "\nvar NS_S_BUSY = \"" . NS_S_BUSY . "\"";
        print "\nvar NS_S_UNKNOWN = \"" . NS_S_UNKNOWN . "\"";
        print "\nvar NS_S_DISABLED = \"" . NS_S_DISABLED . "\"";
        print "\nvar NS_S_ENABLED = \"" . NS_S_ENABLED . "\"";
        print "\nvar entities_count = new Array();";
        print "\nvar entities_state_counter = new Array();";
        print "\n</script>\n";
    }

    // Returns true if tables for a particular command is sortable
    function is_sortable($function)
    {
        return ($function != "statsystemcpu");
    }

    function get_counter_to_sort($function, &$ascending_sort)
    {
        switch($function)
        {
            case "statnsmemory":
            {
                $ascending_sort = false;
                return "memcurallocper";
            }
        }
        $ascending_sort = true;
        return null;
    }

    // Prints clickable header used to sort Dashboard tables
    function print_sortable_header($function, &$header_index, $counter_type, $header, $tooltip, $first_cell=false, $details_available = false)
    {
        $sort_str = "";
        $sortable = self::is_sortable($function);
        if($sortable)
            $sort_str = " class=\"pointer_cursor\" onclick=\"sort_column($header_index, '$function', '$counter_type', '$details_available')\"";
        print "\t<th" . self::get_counter_tooltip_html($header, $tooltip). "$sort_str>";
        if(!$first_cell)
            print "<div>";
        if($sortable)
            print "<img id=\"" . $function . "_sort_img_" . $header_index ."\" src=\"/admin_ui/common/images/sort_az_ascending.png\" alt=\"Sort ascending\" style=\"display: none\">";
        print $header;
        if(!$first_cell)
            print "</div>";
        print "</th>\n";
        $header_index++;
    }

    function print_sortable_fixed_header($function, &$header_index, $counter_type, $header, $tooltip, $first_cell=false)
    {
        $sort_str = "";
        $sortable = self::is_sortable($function);
        $align_class = $first_cell ? "" : " right_align";
        if($sortable)
            $sort_str = " class=\"header pointer_cursor$align_class\" onclick=\"sort_fixed_header_column_table($header_index, '$function', '$counter_type')\"";
        else
            $sort_str = " class=\"header $align_class\"";
        print "\t\t<td" . self::get_counter_tooltip_html($header, $tooltip). "$sort_str>";
        if(!$first_cell)
            print "<div>";
        if($sortable)
            print "<img id=\"sort_img" . $function . "_" . $header_index ."\" src=\"/admin_ui/common/images/sort_az_ascending.png\" alt=\"Sort ascending\" style=\"display: none\">";
        if(!$first_cell)
            print "<span>";
        print $header;
        if(!$first_cell)
            print "</span></div>";
        print "</td>\n";
        $header_index++;
    }

    // Prints HTML to show custom tooltips for counters
    function get_counter_tooltip_html($counter_name, $counter_tooltip)
    {
        if(empty($counter_tooltip))
            return "";
        $counter_tooltip = preg_replace("/'/", "\\'", $counter_tooltip);
        return " onmouseover=\"show_counter_tooltip(this, event, '$counter_name:', '$counter_tooltip')\" onmouseout=\"clear_counter_tooltip_timer()\"";
    }

    // Prints place holders to show custom tooltips for counters as popup
    function print_counter_tooltip_popup()
    {
?>
<script type="text/javascript" src="/admin_ui/common/js/ns/popup.js"></script>
<span id="counter_tooltip_span" class="counter_tooltip_span"></span>
<div id="counter_tooltip_div" class="popup counter_tooltip_div">
    <table class="full_width_table" cellpadding="0" cellspacing="0">
        <tr>
            <td id="counter_tooltip_header_cell" class="counter_tooltip_header_cell"></td>
            <td align="right"><b title="Close" onclick="hide_counter_tooltip()" class="pointer_cursor">X</b></td>
        </tr>
    </table>
    <div id="counter_tooltip_content_div" class="counter_tooltip_content_div"></div>
</div>
<?php
    }

    function print_auto_refresh_javascript($page_number, $post_request = null)
    {
        global $g_chart_list;
        global $g_function;
        $auto_refresh_delay = get_auto_refresh_delay();
//        print "<div id=\"auto_refresh_info_div\" class=\"popup popup_info\">Auto-refresh is off</div>";
        ?>
        <script type="text/javascript" src="/admin_ui/dashboard/js/stats_controller.js"></script>
        <script type="text/javascript">
            <?php
                print "\tvar chart_args = new Array();\n";
                for($i = 0; $i < count($g_chart_list); $i++)
                    print "\tchart_args[".$i."] = {'name' : '".$g_chart_list[$i]["name"]."', 'extra_args' : '".$g_chart_list[$i]["extra_args"]."'};\n";
            ?>
            var controller = new stats_controller(<?=$auto_refresh_delay?>, <?=$page_number?>, chart_args, <?=$post_request ? "\"$post_request\"" : "null"?>);
            function doActionOnLoad()
            {
                do_action_on_load_for_auto_refresh();
            }

            function do_action_on_load_for_auto_refresh()
            {
                controller.start();
                if(typeof(scroll_down_list) == "function")
                    scroll_down_list("system_events_list");


                cpu_mem_dial_update();
            }

            function doActionOnBeforeUnload()
            {
                do_action_on_before_unload_for_auto_refresh();
            }

            function do_action_on_before_unload_for_auto_refresh()
            {
                controller.stop();
                <?php
                    if($page_number >= 0 && isset($g_function))
                        print "new ns.ajax_handler(controller.append_request_number(\"/menu/cc?func=$g_function\"), null, null, null, false).send();\n";
                ?>
            }

            function are_gauges_hidden(gauges)
            {
                if(!gauges)
                    gauges = document.getElementById("gauges_div");
                return (gauges && gauges.style && gauges.style.display != "" && gauges.style.display != "block");
            }

/*            function do_action_on_blur()
            {
                if(!is_on_blur() || are_gauges_hidden())
                    return;

                var auto_refresh_info_div = document.getElementById("auto_refresh_info_div");
                auto_refresh_info_div.style.left = (getWindowWidth() - auto_refresh_info_div.offsetWidth)/2 + "px";
                auto_refresh_info_div.style.visibility = "visible";

                controller.stop();
            }

            function do_action_on_focus()
            {
                if(are_gauges_hidden())
                    return;

                document.getElementById("auto_refresh_info_div").style.visibility = "hidden";
                controller.force_start(<?=$auto_refresh_delay?>);
            }*/

            function show_hide_gauges()
            {
                var href = document.getElementById("show_hide_gauges_href");
                var gauges = document.getElementById("gauges_div");
                if(!href || !gauges)
                    return;
                if(!are_gauges_hidden(gauges))
                {
                    document.getElementById("show_hide_gauges_img").className = "";
                    document.getElementById("show_hide_gauges_img").className= "reporting_images show_img pointer_cursor";
                    gauges.style.display = "none";
                    controller.stop();
                    rem_cookie("showdb");
                    href.title = href.title.replace(/Hide/, "Show");
                    href.title = href.title.replace(/stop/, "start");
                }
                else
                {
                    document.getElementById("show_hide_gauges_img").className = "";
                    document.getElementById("show_hide_gauges_img").className = "reporting_images hide_img pointer_cursor";
                    gauges.style.display = "block";
                    controller.force_start(10);
                    set_cookie("showdb", "showdb");
                    href.title = document.getElementById("show_hide_gauges_img").class;
                    href.title = href.title.replace(/Show/, "Hide");
                    href.title = href.title.replace(/start/, "stop");
                }
                doActionOnResize();
            }
        </script>
        <?php
    }

    //Returns the display name for application units
    function get_displayable_app_name($name, $appname)
    {
        if(self::get_app_type($name) == TYPE_APP)
            $name = substr($name, strpos($name, STR_APP . TYPE_APP) + strlen(STR_APP . TYPE_APP));
        else if(self::get_app_type($name) == TYPE_FILE_SHARE)
            $name = substr($name, strpos($name, STR_APP . TYPE_FILE_SHARE) + strlen(STR_APP . TYPE_FILE_SHARE));
        else if(self::get_app_type($name) == TYPE_NETWORK)
            $name = substr($name, strpos($name, STR_APP . TYPE_NETWORK) + strlen(STR_APP . TYPE_NETWORK));
        else if(preg_match("/^" . STR_APP . STR_APP_OTHER . "/", $name))
            return STR_OTHER;
        else if(preg_match("/^" . STR_APP . STR_APP_UNIT . "/", $name))
            $name = substr($name, strpos($name, STR_APP . STR_APP_UNIT) + strlen(STR_APP . STR_APP_UNIT));
        return substr($name, strpos($name, $appname) + strlen($appname));
    }

    //Returns the type of application unit
    function get_app_type($name)
    {
        return substr($name, strlen(STR_APP), 2);
    }

    function can_counter_be_shown($counter, $group)
    {
        //This should be removed once "delta compression" counters are exposed.
        if($group == "statcmp" && preg_match("/^del/", $counter["cntrname"]))
            return false;
        if($group == "statssl" && preg_match("/^ssl.*ideaciphers/", $counter["cntrname"]))
            return false;	
	
	$COUNTERS_TO_BE_SKIPPED = array();
	if(is_sgw()){
	    $COUNTERS_TO_BE_SKIPPED["stataaa"] = array("aaacuricaconn", "aaacuricaconnrate", "aaacurtmsessions", "aaacurtmsessionsrate", "aaatottmsessions", "aaatmsessionsrate");
	}
	if(isset($COUNTERS_TO_BE_SKIPPED[$group]) && in_array($counter["cntrname"], $COUNTERS_TO_BE_SKIPPED[$group])){
	    return false;
	}
        //Same filter is there in genStatConfig.pl also and hence both should be modified simultaneosuly
        //This needs to be in sync with backend also
        return !(isset($counter["type"]) && preg_match("/VALIP|VALIPSTR|VALPORT|VALTIME|VALDATE|ENUM/", $counter["type"]));
    }

    function value_exists_in_array($array, $value, $key = "name")
    {
        if(is_array($array))
        {
            foreach($array as $element)
            {
                if(is_array($element))
                {
                    if($element[$key] == $value)
                        return $element;
                }
                if($element == $value)
                    return true;
            }
        }
        return false;
    }

    //Expects "02:52:44" and returns "2h 52m 44s"
    //Another example is "1.05:05:03" to "29h 5m 3s"
    function convert_up_down_time($time)
    {
        $arr = explode(':', $time);
        if(count($arr) != 3)
            return $time;
        list($h, $m, $s) = $arr;
        if(!(strpos($h, ".") === false))
        {
            list($days, $hours) = explode('.', $h);
            $h = intval($days) * 24 + intval($hours);
        }
        return intval($h) . "h " . intval($m) . "m " . intval($s) . "s";
    }

    //Expects GMT time in "Thu Oct 16 06:41:56 2008" format
    //Converts GMT to local time zone and then converts it to client time zone
    function convert_gmt_to_client_time($gmt_time)
    {
        $time_in_seconds = strtotime($gmt_time);
        if(!$time_in_seconds)
            return $gmt_time;
        $time_in_seconds = $time_in_seconds + date('Z');
        return date('D M d H:i:s Y', $time_in_seconds + self::get_time_zone_offset());
    }

    //Expects client time in "mm-dd-yyyy hh:mm" format
    function convert_client_to_local_time($client_time)
    {
        list($date, $time) = explode(' ', $client_time);
        list($month, $day, $year) = explode('-', $date);
        list($hour, $min) = explode(':', $time);
        $client_time_in_seconds = mktime($hour, $min, 0, $month, $day, $year);
        return date('m-d-Y H:i', $client_time_in_seconds - self::get_time_zone_offset());
    }

    //Expects local time in epoch (number of seconds since 1970 Jan 1 00::00:00 GMT)
    function convert_local_to_client_time($local_time_in_seconds, $date_format = null, $use_epoch = false)
    {
        if($use_epoch)
            return $local_time_in_seconds + self::get_time_zone_offset();
        if($date_format)
            return date($date_format, $local_time_in_seconds + self::get_time_zone_offset());
        if($local_time_in_seconds % 60)
            return date('m-d-Y H:i:s', $local_time_in_seconds + self::get_time_zone_offset());
        else
            return date('m-d-Y H:i', $local_time_in_seconds + self::get_time_zone_offset());
    }

    function get_time_zone_offset()
    {
        if(isset($_SESSION['timezone_offset']) && !(isset($_COOKIE['uatz']) && ($_COOKIE['uatz'] == "uatz")))
                return $_SESSION['timezone_offset'];
        return 0;
    }

    //Returns true for entity groups like statservice, statinterface etc
    function is_an_entity_based_function($func)
    {
        $_ns_stat_definitions = new _ns_stat_definitions();
        $nsstat_cmd = $_ns_stat_definitions->get_command($func);
        if(!$nsstat_cmd)
            return false;
        return isset($nsstat_cmd['namearg']);
    }

    //Removes space, semi-colon at the end.
    function get_display_handler_title($title)
    {
        $title = trim($title);
        $title = preg_replace("/:$/", "", $title);
        return $title;
    }

    function get_report_element_title($group)
    {
        $stats_group = self::get_reporting_group_array();
        return preg_replace("/Statistics/", "Report", $stats_group[$group]);
    }

    function get_state_of_an_entity($state)
    {
        switch($state)
        {
            case ACTIVE_ENTITY:
                return "Active";
            case INACTIVE_ENTITY:
                return "Inactive";
            case DELETED_ENTITY:
                return "Deleted";
        }
    }

    //css class selector doesn't work for list options,
    //otherwise use selectors like "disabled_element", "ns_alert_text"
    function get_style_for_entity($state)
    {
        switch($state)
        {
            case ACTIVE_ENTITY:
                return "";
            case INACTIVE_ENTITY:
                return "style=\"color: #9D9DA1\"";
            case DELETED_ENTITY:
                return "style=\"color: #FF0000\"";
        }
    }

    //Given an array of entity objects, returns the first active entity's name.
    //If no active entities are found, returns the first inactive entity's name.
    function select_entity($entities)
    {
        if(!is_array($entities) || count($entities) == 0)
            return "";
        foreach($entities as $entity)
        {
            if($entity["state"] == ACTIVE_ENTITY)
                return $entity["name"];
        }
        return $entities[0]["name"];
    }

    function print_zoom_in_out_open_div()
    {
        $text_area_width = intval(input_validator::validate_and_get_cookie("taw"));
        $text_area_width = ($text_area_width >= MIN_TEXT_AREA_WIDTH && $text_area_width <= MAX_TEXT_AREA_WIDTH) ? $text_area_width : input_validator::get_default_value("taw");
        $text_font_size = intval(input_validator::validate_and_get_cookie("tfs"));
        $text_font_size = ($text_font_size >= MIN_FONT_SIZE && $text_font_size <= MAX_FONT_SIZE) ? $text_font_size : input_validator::get_default_value("tfs");
?>
<script type="text/javascript">
var MIN_TEXT_AREA_WIDTH = <?=MIN_TEXT_AREA_WIDTH ?>;
var MAX_TEXT_AREA_WIDTH = <?=MAX_TEXT_AREA_WIDTH?>;
var INCREMENT_TEXT_AREA_WIDTH = <?=INCREMENT_TEXT_AREA_WIDTH?>;
var DEFAULT_TEXT_AREA_WIDTH = <?=input_validator::get_default_value("taw")?>;
var INCREMENT_FONT_SIZE = <?=INCREMENT_FONT_SIZE?>;
var DEFAULT_FONT_SIZE = <?=input_validator::get_default_value("tfs")?>;
</script>
<div class="change_text_size_div">
    <a href="#noAnchor" onclick="change_text_size(null, true)"><img src="/admin_ui/common/images/best_fit.png" alt="Reset Zoom" border="0" title="Best Fit" class="padding_top"></a><br>
    <a href="#noAnchor" onclick="change_text_size(true)"><img src="/admin_ui/common/images/increase_font_size.png" alt="Zoom In" border="0" title="Zoom In" class="padding_top"></a><br>
    <a href="#noAnchor" onclick="change_text_size(false)"><img src="/admin_ui/common/images/decrease_font_size.png" alt="Zoom Out" border="0" title="Zoom Out" class="padding_top"></a>
</div>
<table align="center" id="resizeable_table" width="<?=$text_area_width?>%" cellspacing="0" cellpadding="0" style="font-size: <?=$text_font_size?>pt; table-layout: fixed">
    <tr>
        <td>
<?php
    }

    function print_zoom_in_out_close_div()
    {
        print "\n\t\t\t</td>\n";
        print "\t\t</tr>\n";
        print "\t</table>\n";
    }

    function print_chart_url($chart_name, $load_links_in_frame = null)
    {
        global $g_function;
        global $g_descriptivename;
        global $g_detailview;
        global $g_argname;
        global $g_argval;
        global $g_chart_list;
        require_once(APPPATH . "controllers/dashboard/chart_definitions.php");
        $chart_definitions = new chart_definitions();
        $chart_definition = $chart_definitions->get_chart_definition($chart_name);
        $title = "";
        if(isset($chart_definition['title']))
            $title = $chart_definition['title'];
        if(isset($chart_definition['tooltip']))
            $title = $chart_definition['tooltip'];
        $alt = $title;
        $is_link = true;
        if(isset($chart_definition['link']))
        {
            $link_func = $chart_definition['link']['cmd'];
            $link_dview = ($chart_definition['link']['type'] == "summary") ? "NO" : "YES";
            if($g_function == $link_func && $g_detailview == $link_dview)
                $is_link = false;
            if(true)
            {
                if($load_links_in_frame != null)
                    print "<a href='/menu/stc?func=$link_func&detailview=$link_dview' target=\"$load_links_in_frame\">";
                else
                    print "<a href='/menu/st?cfunc=$link_func'>";
                $title = "Click to view ".$g_descriptivename[$link_func];
            }
        }
        $extra_args = "";
        if(isset($chart_definition['entity_cmd']) && !ns_empty($g_argval) && !ns_empty($g_argname) && $chart_definition['entity_cmd'])
            $extra_args = "&argname=$g_argname&argval=" . urlencode(preg_replace("/\//", "%2F", $g_argval));

        if($chart_name == "cpu_mem_dial")
        {
            print "<div id = $chart_name alt='".$alt."' title='".$title."' border='0'>";
            print "<div class= 'layout_cell'><div id = 'packet_cpu_usage_chart_div'></div></div>";
            print "<div class= 'layout_cell'><div id = 'management_cpu_usage_chart_div'></div></div>";
            print "<div class= 'layout_cell'><div id = 'inuse_memory_chart_div'></div></div>";
            print "</div>";    
        }

        else 
        {
            print "<img id='img_".$chart_name."' src='/menu/chart?name=$chart_name$extra_args' alt='".$alt."' title='".$title."' border='0'>";
        }

        $g_chart_list[] = array("name" => $chart_name, "extra_args" => $extra_args);
        if(true)
            print "</a>";
    }

    function slice_result(&$result)
    {
        global $g_pageno;
        global $g_pagesize;
        global $g_argval;
        if($g_pageno == null || $g_pagesize == null || !ns_empty($g_argval))
            return;

        $result["List"] = array_splice($result["List"], ($g_pageno - 1) * $g_pagesize, $g_pagesize);
    }

    function print_pagination_components(&$result)
    {
        global $g_pageno;
        global $g_pagesize;
        global $g_argval;
        if($g_pageno == null || $g_pagesize == null || !ns_empty($g_argval))
            return;

        $total_count = count($result["List"]);
        $no_of_pages = ceil($total_count / $g_pagesize);
        if($g_pageno < 1 || $g_pageno > $no_of_pages)
            $g_pageno = 1;
        self::slice_result($result);

        print "<div class=\"pagination_div\"><table class=\"full_width_table\" cellspacing=\"3\" cellpadding=\"0\"><tr><td class=\"pagination_records_width\">";
        print "<span>Records per page: </span>";
        $first = true;
        $allowed_stat_page_sizes = input_validator::get_valid_values("stps");
        foreach($allowed_stat_page_sizes as $allowed_page_size)
        {
            if(!$first)
                print "<span> | </span>";
            $first = false;
            if($allowed_page_size == $g_pagesize)
                print $allowed_page_size;
            else
                print "<a href=\"#noAnchor\" onclick=\"stat_page_size_changed('$allowed_page_size')\">$allowed_page_size</a>";
        }
        print "</td><td align=\"center\" class=\"pagination_navigation_width\">";

        $is_first_page = ($g_pageno == 1);
        $extra_img_classes = $is_first_page ? " img_opacity" : " pointer_cursor";
        $first_img = "<img src=\"/admin_ui/common/images/firstpage.gif\" class=\"pagination_img_spacing$extra_img_classes\" alt=\"First\" title=\"First\" border=\"0\"/>";
        $prev_img = "<img src=\"/admin_ui/common/images/prevpage.gif\" class=\"pagination_img_spacing$extra_img_classes\" alt=\"Previous\" title=\"Previous\" border=\"0\"/>";
        if($is_first_page)
            print $first_img . $prev_img;
        else
        {
            print "<a href=\"#noAnchor\" onclick=\"switch_stat_page(1)\">$first_img</a>";
            print "<a href=\"#noAnchor\" onclick=\"switch_stat_page(" . ($g_pageno - 1) . ")\">$prev_img</a>";
        }

        $page_count = count($result["List"]);
        $start_index = (($g_pageno - 1) * $g_pagesize) + 1;
        $end_index = $page_count + $start_index - 1;
        if($start_index <= $end_index)
            print "<span>$start_index - $end_index of $total_count</span>";

        $is_last_page = ($g_pageno == $no_of_pages);
        $extra_img_classes = $is_last_page ? " img_opacity" : " pointer_cursor";
        $next_img = "<img src=\"/admin_ui/common/images/nextpage.gif\" class=\"pagination_img_spacing$extra_img_classes\" alt=\"Next\" title=\"Next\" border=\"0\"/>";
        $last_img = "<img src=\"/admin_ui/common/images/lastpage.gif\" class=\"pagination_img_spacing$extra_img_classes\" alt=\"Last\" title=\"Last\" border=\"0\"/>";
        if($is_last_page)
            print $next_img . $last_img;
        else
        {
            print "<a href=\"#noAnchor\" onclick=\"switch_stat_page(" . ($g_pageno + 1) . ")\">$next_img</a>";
            print "<a href=\"#noAnchor\" onclick=\"switch_stat_page($no_of_pages)\">$last_img</a>";
        }

        print "</td><td align=\"right\" class=\"pagination_goto_width\"><span>Goto Page: </span><select size=\"1\" class=\"pagination_goto_list\" onchange=\"switch_stat_page(this.value)\">";
        for($i = 1; $i <= $no_of_pages; $i++)
        {
            $selected_text = ($i == $g_pageno) ? " selected" : "";
            print "<option$selected_text value=\"$i\">$i</option>\n";
        }
        print "</select></td></tr></table></div>";
    }
}
?>
