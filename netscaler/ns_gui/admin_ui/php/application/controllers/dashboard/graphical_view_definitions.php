<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
class graphical_view_definitions
{
    private $graphical_views = array(
        "stataaa"  =>  array(
            "report_name"   =>  "aaa0",
            "cntrs"         =>  array("aaaauthsuccessrate", "aaaauthfailrate")
        ),
        "statnsacl"  =>  array(
            "cntrs"         =>  array("aclhitsrate", "aclmissesrate")
        ),
        "statnsacl6"  =>  array(
            "cntrs"         =>  array("acl6hitsrate", "acl6missesrate")
        ),
        "statappflow"  =>  array(
            "cntrs"         =>  array("appflowtxmessagessrate")
        ),
        "statappfwprofile"  =>  array(
            "report_name"   =>  "appfw6",
            "cntrs"         =>  array("appfirewallrequestsperprofilerate", "appfirewallresponsesperprofilerate", "appfirewallabortsperprofilerate", "appfirewallredirectsperprofilerate")
        ),
        "statappfwpolicylabel"  =>  array(
            "cntrs"         =>  array("pipolicylabelhitsrate")
        ),
        "statappfwpolicy"   =>  array(
            "cntrs"         =>  array("pipolicyhitsrate", "pipolicyundefhitsrate")
        ),
        "statappfw"  =>  array(
            "report_name"   =>  "appfw0",
            "cntrs"         =>  array("appfirewallrequestsrate", "appfirewallresponsesrate", "appfirewallabortsrate", "appfirewallredirectsrate")
        ),
        "stataudit"  =>  array(
            "cntrs"         =>  array("auditsyslogmsgsent")
        ),
        "statauthenticationvserver"  =>  array(
            "cntrs"         =>  array("requestsrate", "responsesrate")
        ),
        "statauthorizationpolicylabel"  =>  array(
            "cntrs"         =>  array("pipolicylabelhitsrate")
        ),
        "statbridge"  =>  array(
            "cntrs"         =>  array("tcpbdgmacmovedrate")
        ),
        "statsystemcpu"  =>  array(
            "cntrs"         =>  array("percpuuse")
        ),
        "statcrvserver"  =>  array(
            "cntrs"         =>  array("requestsrate", "responsesrate")
        ),
        "statcachepolicylabel"  =>  array(
            "cntrs"         =>  array("pipolicylabelhitsrate")
        ),
        "statcachepolicy"  =>  array(
            "cntrs"         =>  array("pipolicyhitsrate", "pipolicyundefhitsrate")
        ),
        "statclusterinstance"  =>  array(
            "cntrs"         =>  array("totsteeredpkts")
        ),
        "statclusternode"  =>  array(
            "cntrs"         =>  array("cltothbrx", "cltothbtx")
        ),
        "statcmppolicylabel"  =>  array(
            "cntrs"         =>  array("pipolicylabelhitsrate")
        ),
        "statcmppolicy"  =>  array(
            "cntrs"         =>  array("pipolicyhitsrate", "pipolicyundefhitsrate")
        ),
        "statcmp"  =>  array(
            "report_name"   =>  "hcmp0",
            "cntrs"         =>  array("comprxbytesrate", "comptxbytesrate")
        ),
        "statcsvserver"  =>  array(
            "cntrs"         =>  array("requestsrate", "responsesrate")
        ),
        "statdnspolicylabel"  =>  array(
            "cntrs"         =>  array("pipolicylabelhitsrate")
        ),
        "statdns"  =>  array(
            "cntrs"         =>  array("dnstotqueries")
        ),
        "statdnsrecords"  =>  array(
            "cntrs"         =>  array("dnscurrecords")
        ),
        "statnsmemory"  =>  array(
            "cntrs"         =>  array("memcurallocper")
        ),
        "statgslbdomain"  =>  array(
            "cntrs"         =>  array("dnsqueriesrate","gslbdnsrectype")
        ),
        "statgslbservice"  =>  array(
            "cntrs"         =>  array("requestbytesrate", "responsebytesrate")
        ),
        "statgslbsite"  =>  array(
            "cntrs"         =>  array("siterequestbytesrate", "siteresponsebytesrate")
        ),
        "statgslbvserver"  =>  array(
            "cntrs"         =>  array("hitsrate")
        ),
        "statsystemmemory"  =>  array(
            "cntrs"         =>  array("memtotallocpcnt", "memusagepcnt")
        ),
        "stathanode"  =>  array(
            "report_name"   =>  "ha0",
            "cntrs"         =>  array("hapktrxrate", "hapkttxrate")
        ),
        "statdos"  =>  array(
            "cntrs"         =>  array("dosconditiontriggeredrate")
        ),
        "statdospolicy"  =>  array(
            "cntrs"         =>  array("doscurrentcltdetectrate")
        ),
        "statprotocolhttp"  =>  array(
            "report_name"   =>  "http0",
            "cntrs"         =>  array("httpgetsrate", "httppostsrate", "httpothersrate")
        ),
        "statprotocolicmp"  =>  array(
            "report_name"   =>  "icmp0",
            "cntrs"         =>  array("icmprxpktsrate", "icmptxpktsrate")
        ),
        "statprotocolicmpv6"  =>  array(
            "report_name"   =>  "icmpv60",
            "cntrs"         =>  array("icmpv6rxpktsrate", "icmpv6txpktsrate")
        ),
        "statprotocolip"  =>  array(
            "report_name"   =>  "ip0",
            "cntrs"         =>  array("iprxpktsrate", "iptxpktsrate")
        ),
        "statipseccounters"  =>  array(
            "report_name"   =>  "ipsec0",
            "cntrs"         =>  array("ipsecrxpktsrate", "ipsectxpktsrate")
        ),
        "statprotocolipv6"  =>  array(
            "report_name"   =>  "ipv60",
            "cntrs"         =>  array("ipv6rxpktsrate", "ipv6txpktsrate")
        ),
        "statcache"  =>  array(
            "report_name"   =>  "ic0",
            "cntrs"         =>  array("cacherequestsrate", "cachehitsrate", "cachemissesrate")
        ),
        "statinterface"  =>  array(
            "cntrs"         =>  array("rxbytesrate", "txbytesrate")
        ),
        "statlldp"  =>  array(
            "cntrs" =>  array("rxportframestotal", "rxportbytestotal","txportframestotal","txportbytestotal","rxportframeserrors","rxporttlvsdiscardedtotal","rxporttlvsunrecognizedtotal")
        ),
        "statvxlan"  =>  array(
            "cntrs" =>  array("vxlantotrxpkts","vxlantotrxbytes","vxlantottxpkts","vxlantottxbytes")
        ),
        "statlbvserver"  =>  array(
            "cntrs"         =>  array("requestsrate", "responsesrate")
        ),
         "statinat"  => array(
            "cntrs" => array("nat46tcp46rate", "nat46udp46rate", "nat46icmp46rate", "nat46drop46rate", "nat46tcp64rate", "nat46udp64rate", "nat46icmp64rate", "nat46drop64rate")
        ),
        "statnspbr"  =>  array(
            "cntrs"         =>  array("pbrhitsrate", "pbrmissesrate")
        ),
        "statnspbr6"  =>  array(
            "cntrs"         =>  array("pbr6hitsrate", "pbr6missesrate")
        ),
        "statpq"  =>  array(
            "cntrs"         =>  array("pqpolicymatchesrate")
        ),
        "statpqpolicy"  =>  array(
            "cntrs"         =>  array("pqcurrentclientconnections")
        ),
        "statrnatip"  =>  array(
            "cntrs"         =>  array("iprnatrxbytesrate", "iprnattxbytesrate")
        ),
        "statrnat"  =>  array(
            "cntrs"         =>  array("rnatrxbytesrate", "rnattxbytesrate")
        ),
        "statresponderpolicylabel"  =>  array(
            "cntrs"         =>  array("pipolicylabelhitsrate")
        ),
        "statresponderpolicy"  =>  array(
            "cntrs"         =>  array("pipolicyhitsrate", "pipolicyundefhitsrate")
        ),
        "statrewritepolicylabel"  =>  array(
            "cntrs"         =>  array("pipolicylabelhitsrate")
        ),
        "statrewritepolicy"  =>  array(
            "cntrs"         =>  array("pipolicyhitsrate", "pipolicyundefhitsrate")
        ),
        "statsnmp"  =>  array(
            "cntrs"         =>  array("snmprxpktsrate", "snmptxpktsrate")
        ),
        "statssl"  =>  array(
            "report_name"   =>  "ssl0",
            "cntrs"         =>  array("sslsessionsrate")
        ),
        "statservicegroupmember"  =>  array(
            "cntrs"         =>  array("requestsrate", "responsesrate")
        ),
        "statservice"  =>  array(
            "cntrs"         =>  array("requestsrate", "responsesrate")
        ),
        "statnssimpleacl"  =>  array(
            "cntrs"         =>  array("saclhitsrate", "saclmissesrate")
        ),
        "statnssimpleacl6"  =>  array(
            "cntrs"         =>  array("sacl6hitsrate", "sacl6missesrate")
        ),
		"statqos"  =>  array(
            "cntrs"         =>  array("qosoutputpackets", "qosdroppackets", "qosbytesdropped", "qosbytessentnotclassified")
        ),
        "statsc"  =>  array(
            "cntrs"         =>  array("scpolicyurlhitsrate")
        ),
        "statscpolicy"  =>  array(
            "cntrs"         =>  array("sccurrentclientconnections")
        ),
        "statsystem"  =>  array(
            "report_name"   =>  "sys0",
            "alt_expr"      =>  "   if(is_vmpe_mode())
                                        return array('pktcpuusagepcnt', 'mgmtcpuusagepcnt', 'memusagepcnt');
                                    if(isset(RESULT['numcpus']) && RESULT['numcpus'] > 1)
                                        return array('rescpuusagepcnt', 'memusagepcnt');
                                    return array('cpuusagepcnt', 'memusagepcnt');"
        ),
        "statns"  =>  array(
            "report_name"   =>  "sys0",
            "alt_expr"      =>  "   if(is_vmpe_mode())
                                        return array('pktcpuusagepcnt', 'memusagepcnt', 'httprequestsrate', 'mgmtcpuusagepcnt');
                                    if(isset(RESULT['numcpus']) && RESULT['numcpus'] > 1)
                                        return array('rescpuusagepcnt', 'memusagepcnt', 'httprequestsrate');
                                    return array('cpuusagepcnt', 'memusagepcnt', 'httprequestsrate');",
            "properties"    =>  array("secondary_y_axis" =>  2,
                                      "plot_type" => "spline",
                                      0 => array("plot_type" => "areaspline"))
        ),
        "statprotocoltcp"  =>  array(
            "report_name"   =>  "tcp0",
            "cntrs"         =>  array("tcpcurclientconnestablished", "tcpcurserverconnestablished")
        ),
        "stattransformpolicylabel"  =>  array(
            "cntrs"         =>  array("pipolicylabelhitsrate")
        ),
        "stattransformpolicy"   =>  array(
            "cntrs"         =>  array("pipolicyhitsrate", "pipolicyundefhitsrate")
        ),
        "statprotocoludp"  =>  array(
            "cntrs"         =>  array("udprxpktsrate", "udptxpktsrate")
        ),
        "statvlan"  =>  array(
            "cntrs"         =>  array("vlanrxbytesrate", "vlantxbytesrate")
        ),
        "statvpn"  =>  array(
            "report_name"   =>  "vpn0",
            "cntrs"         =>  array("indexhtmlhit", "indexhtmlnoserved")
        ),
        "statvpnvserver"  =>  array(
            "cntrs"         =>  array("requestsrate", "responsesrate")
        )
    );

    public function get_graphical_view_definition($cmd_name)
    {
        if(isset($this->graphical_views[$cmd_name]))
            return $this->graphical_views[$cmd_name];
        return null;
    }
}
?>
