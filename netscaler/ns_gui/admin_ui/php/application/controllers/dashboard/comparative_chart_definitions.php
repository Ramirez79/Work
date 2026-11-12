<?php if(!defined("BASEPATH")) exit("No direct script access allowed");
class comparative_chart_definitions
{
    var $_sys0 = array(
        "name"          =>  "CPU vs. Memory Usage and HTTP Requests Rate",
        "key"           =>  "_sys0",
        "report_name"   =>  "sys0",
        "func"          =>  "statns",
        "alt_expr"      =>  array("statns" => "if(is_vmpe_mode())
                                    return array('pktcpuusagepcnt', 'memusagepcnt', 'httprequestsrate', 'mgmtcpuusagepcnt');
                                if(isset(RESULT['numcpus']) && RESULT['numcpus'] > 1)
                                    return array('rescpuusagepcnt', 'memusagepcnt', 'httprequestsrate');
                                return array('cpuusagepcnt', 'memusagepcnt', 'httprequestsrate');"),
        "properties"    =>  array("secondary_y_axis" =>  2,
                                  "plot_type" => "spline",
                                  0 => array("plot_type" => "areaspline")));
    var $_http0 = array(
        "name"          =>  "GETs vs. POSTs vs. Non-GET/POSTs Requests received",
        "key"           =>  "_http0",
        "report_name"   =>  "http0",
        "func_cntrs"    =>  array("statprotocolhttp" => array("httpgetsrate", "httppostsrate", "httpothersrate")));
    var $_http1 = array(
        "name"          =>  "HTTP/1.0 vs. HTTP/1.1 requests received",
        "key"           =>  "_http1",
        "report_name"   =>  "http1",
        "func_cntrs"    =>  array("statprotocolhttp" => array("http10requestsrate", "http11requestsrate")));
    var $_http2 = array(
        "name"          =>  "Request vs. Response bytes received",
        "key"           =>  "_http2",
        "report_name"   =>  "http2",
        "func_cntrs"    =>  array("statprotocolhttp" => array("httprxrequestbytesrate", "httprxresponsebytesrate")));
    var $_http3 = array(
        "name"          =>  "Request vs. Response bytes transmitted",
        "key"           =>  "_http3",
        "report_name"   =>  "http3",
        "func_cntrs"    =>  array("statprotocolhttp" => array("httptxrequestbytesrate", "httptxresponsebytesrate")));
    var $_http4 = array(
        "name"          =>  "HTTP/1.0 vs. HTTP/1.1 responses received",
        "key"           =>  "_http4",
        "report_name"   =>  "http4",
        "func_cntrs"    =>  array("statprotocolhttp" => array("http10responsesrate", "http11responsesrate")));
    var $_ha0 = array(
        "name"          =>  "Heartbeats received vs. Heartbeats sent",
        "key"           =>  "_ha0",
        "report_name"   =>  "ha0",
        "func_cntrs"    =>  array("stathanode" => array("hapktrxrate", "hapkttxrate")));
    var $_cli0 = array(
        "name"          =>  "Total Steered Packets",
        "key"           =>  "_cli0",
        "report_name"   =>  "cli0",
        "func_cntrs"    =>  array("statclusterinstance" => array("totsteeredpkts")));
    var $_cli1 = array(
        "name"          =>  "Current DFD Sessions",
        "key"           =>  "_cli1",
        "report_name"   =>  "cli1",
        "func_cntrs"    =>  array("statclusterinstance" => array("numdfdsession")));
    var $_cln0 = array(
        "name"          =>  "Heartbeats Received vs. Sent",
        "key"           =>  "_cln0",
        "report_name"   =>  "cln0",
        "func_cntrs"    =>  array("statclusternode" => array("cltothbrx", "cltothbtx")));
    var $_tcp0 = array(
        "name"          =>  "Established Client vs. Server Connections",
        "key"           =>  "_tcp0",
        "report_name"   =>  "tcp0",
        "func_cntrs"    =>  array("statprotocoltcp" => array("tcpcurclientconnestablished", "tcpcurserverconnestablished")));
    var $_tcp1 = array(
        "name"          =>  "Established Server vs. Active Server Connections",
        "key"           =>  "_tcp1",
        "report_name"   =>  "tcp1",
        "func_cntrs"    =>  array("statprotocoltcp" => array("tcpcurserverconnestablished", "tcpactiveserverconn")));
    var $_tcp2 = array(
        "name"          =>  "Surge Queue vs. Spare connections",
        "key"           =>  "_tcp2",
        "report_name"   =>  "tcp2",
        "func_cntrs"    =>  array("statprotocoltcp" => array("tcpsurgequeuelen", "tcpspareconn")));
    var $_ip0 = array(
        "name"          =>  "IP packets received vs. transmitted",
        "key"           =>  "_ip0",
        "report_name"   =>  "ip0",
        "func_cntrs"    =>  array("statprotocolip" => array("iprxpktsrate", "iptxpktsrate")));
    var $_ip1 = array(
        "name"          =>  "IP bytes received vs. transmitted",
        "key"           =>  "_ip1",
        "report_name"   =>  "ip1",
        "func_cntrs"    =>  array("statprotocolip" => array("iprxbytesrate", "iptxbytesrate")));
    var $_ip2 = array(
        "name"          =>  "Megabits received vs. transmitted",
        "key"           =>  "_ip2",
        "report_name"   =>  "ip2",
        "func_cntrs"    =>  array("statprotocolip" => array("iprxmbitsrate", "iptxmbitsrate")));
    var $_ipv60 = array(
        "name"          =>  "IPv6 packets received vs. transmitted",
        "key"           =>  "_ipv60",
        "report_name"   =>  "ipv60",
        "func_cntrs"    =>  array("statprotocolipv6" => array("ipv6rxpktsrate", "ipv6txpktsrate")));
    var $_ipv61 = array(
        "name"          =>  "IPv6 bytes received vs. transmitted",
        "key"           =>  "_ipv61",
        "report_name"   =>  "ipv61",
        "func_cntrs"    =>  array("statprotocolipv6" => array("ipv6rxbytesrate", "ipv6txbytesrate")));
    var $_icmp0 = array(
        "name"          =>  "ICMP packets received vs. transmitted",
        "key"           =>  "_icmp0",
        "report_name"   =>  "icmp0",
        "func_cntrs"    =>  array("statprotocolicmp" => array("icmprxpktsrate", "icmptxpktsrate")));
    var $_icmp1 = array(
        "name"          =>  "ICMP bytes received vs. transmitted",
        "key"           =>  "_icmp1",
        "report_name"   =>  "icmp1",
        "func_cntrs"    =>  array("statprotocolicmp" => array("icmprxbytesrate", "icmptxbytesrate")));
    var $_icmpv60 = array(
        "name"          =>  "ICMPv6 packets received vs. transmitted",
        "key"           =>  "_icmpv60",
        "report_name"   =>  "icmpv60",
        "func_cntrs"    =>  array("statprotocolicmpv6" => array("icmpv6rxpktsrate", "icmpv6txpktsrate")));
    var $_icmpv61 = array(
        "name"          =>  "ICMPv6 bytes received vs. transmitted",
        "key"           =>  "_icmpv61",
        "report_name"   =>  "icmpv61",
        "func_cntrs"    =>  array("statprotocolicmpv6" => array("icmpv6rxbytesrate", "icmpv6txbytesrate")));
    var $_icmpv62 = array(
        "name"          =>  "ICMPv6 NA packets received vs. transmitted",
        "key"           =>  "_icmpv62",
        "report_name"   =>  "icmpv62",
        "func_cntrs"    =>  array("statprotocolicmpv6" => array("icmpv6rxnarate", "icmpv6txnarate")));
    var $_icmpv63 = array(
        "name"          =>  "ICMPv6 NS packets received vs. transmitted",
        "key"           =>  "_icmpv63",
        "report_name"   =>  "icmpv63",
        "func_cntrs"    =>  array("statprotocolicmpv6" => array("icmpv6rxnsrate", "icmpv6txnsrate")));
    var $_icmpv64 = array(
        "name"          =>  "ICMPv6 RA packets received vs. transmitted",
        "key"           =>  "_icmpv64",
        "report_name"   =>  "icmpv64",
        "func_cntrs"    =>  array("statprotocolicmpv6" => array("icmpv6rxrarate", "icmpv6txrarate")));
    var $_icmpv65 = array(
        "name"          =>  "ICMPv6 RS packets received vs. transmitted",
        "key"           =>  "_icmpv65",
        "report_name"   =>  "icmpv65",
        "func_cntrs"    =>  array("statprotocolicmpv6" => array("icmpv6rxrsrate", "icmpv6txrsrate")));
    var $_icmpv66 = array(
        "name"          =>  "ICMPv6 Echo Request packets received vs. transmitted",
        "key"           =>  "_icmpv66",
        "report_name"   =>  "icmpv66",
        "func_cntrs"    =>  array("statprotocolicmpv6" => array("icmpv6rxechoreqrate", "icmpv6txechoreqrate")));
    var $_icmpv67 = array(
        "name"          =>  "ICMPv6 Echo Reply packets received vs. transmitted",
        "key"           =>  "_icmpv67",
        "report_name"   =>  "icmpv67",
        "func_cntrs"    =>  array("statprotocolicmpv6" => array("icmpv6rxechoreplyrate", "icmpv6txechoreplyrate")));
    var $_ssl0 = array(
        "name"          =>  "Front-end SSLv2 vs. SSLv3 vs. TLSv1 Client Auth",
        "key"           =>  "_ssl0",
        "report_name"   =>  "ssl0",
        "func_cntrs"    =>  array("statssl" => array("ssltotsslv2clientauthentications", "ssltotsslv3clientauthentications", "ssltottlsv1clientauthentications")));
    var $_ssl1 = array(
        "name"          =>  "Front-end RSA vs. DH Key Exchange",
        "key"           =>  "_ssl1",
        "report_name"   =>  "ssl1",
        "func_cntrs"    =>  array("statssl" => array("ssltotrsa512keyexchanges", "ssltotrsa1024keyexchanges", "ssltotrsa2048keyexchanges", "ssltotdh512keyexchanges", "ssltotdh1024keyexchanges", "ssltotdh2048keyexchanges")));
    var $_ssl2 = array(
        "name"          =>  "All Front-end Ciphers",
        "key"           =>  "_ssl2",
        "report_name"   =>  "ssl2",
        "func_cntrs"    =>  array("statssl" => array("ssltot40bitrc4ciphers", "ssltot56bitrc4ciphers", "ssltot64bitrc4ciphers", "ssltot128bitrc4ciphers", "ssltot40bitdesciphers", "ssltot56bitdesciphers", "ssltot168bit3desciphers", "ssltotcipheraes128", "ssltotcipheraes256", "ssltot40bitrc2ciphers", "ssltot56bitrc2ciphers", "ssltot128bitrc2ciphers", "ssltotnullciphers")));
    var $_ssl3 = array(
        "name"          =>  "Backend RSA vs. DH Key Exchange",
        "key"           =>  "_ssl3",
        "report_name"   =>  "ssl3",
        "func_cntrs"    =>  array("statssl" => array("sslbetotrsa512keyexchanges", "sslbetotrsa1024keyexchanges", "sslbetotrsa2048keyexchanges", "sslbetotdh512keyexchanges", "sslbetotdh1024keyexchanges", "sslbetotdh2048keyexchanges")));
    var $_ssl4 = array(
        "name"          =>  "All Backend Ciphers",
        "key"           =>  "_ssl4",
        "report_name"   =>  "ssl4",
        "func_cntrs"    =>  array("statssl" => array("sslbetot40bitrc4ciphers", "sslbetot56bitrc4ciphers", "sslbetot64bitrc4ciphers", "sslbetot128bitrc4ciphers", "sslbetot40bitdesciphers", "sslbetot56bitdesciphers", "sslbetot168bit3desciphers", "ssltotbkendcipheraes128", "ssltotbkendcipheraes256", "sslbetot40bitrc2ciphers", "sslbetot56bitrc2ciphers", "sslbetot128bitrc2ciphers", "sslbetotnullciphers")));
    var $_hcmp0 = array(
        "name"          =>  "HTTP Compressible (Rx) vs. Compressed (Tx) - bytes",
        "key"           =>  "_hcmp0",
        "report_name"   =>  "hcmp0",
        "func_cntrs"    =>  array("statcmp" => array("comprxbytesrate", "comptxbytesrate")));
    var $_hcmp1 = array(
        "name"          =>  "HTTP compression ratio vs. Total HTTP compression ratio",
        "key"           =>  "_hcmp1",
        "report_name"   =>  "hcmp1",
        "func_cntrs"    =>  array("statcmp" => array("compratio", "comptotaldatacompressionratio")));
    var $_tcmp0 = array(
        "name"          =>  "TCP Compressible (Rx) vs. Compressed (Tx) - bytes",
        "key"           =>  "_tcmp0",
        "report_name"   =>  "tcmp0",
        "func_cntrs"    =>  array("statcmp" => array("comptcprxbytesrate", "comptcptxbytesrate")));
    var $_tcmp1 = array(
        "name"          =>  "TCP compression ratio vs. Bandwidth saving (%)",
        "key"           =>  "_tcmp1",
        "report_name"   =>  "tcmp1",
        "func_cntrs"    =>  array("statcmp" => array("comptcpratio", "comptcpbandwidthsaving")));
    /*This functionality will come back in future
    var $_dcmp0 = array(
        "name"          =>  "Delta Compressible (Rx) vs. Compressed (Tx) - bytes",
        "key"           =>  "_dcmp0",
        "report_name"   =>  "dcmp0",
        "func_cntrs"    =>  array("statcmp" => array("delcomptcprxbytesrate", "delcomptcptxbytesrate")));
    var $_dcmp1 = array(
        "name"          =>  "Delta compression ratio vs. Bandwidth saving (%)",
        "key"           =>  "_dcmp1",
        "report_name"   =>  "dcmp1",
        "func_cntrs"    =>  array("statcmp" => array("delcmpratio", "delbwsaving")));*/
    var $_decmp0 = array(
        "name"          =>  "Compressed (Rx) vs. Decompressed (Tx) - bytes",
        "key"           =>  "_decmp0",
        "report_name"   =>  "decmp0",
        "func_cntrs"    =>  array("statcmp" => array("decomptcprxbytesrate", "decomptcptxbytesrate")));
    var $_decmp1 = array(
        "name"          =>  "Decompression ratio vs. Bandwidth saving (%)",
        "key"           =>  "_decmp1",
        "report_name"   =>  "decmp1",
        "func_cntrs"    =>  array("statcmp" => array("decomptcpratio", "decomptcpbandwidthsaving")));
    var $_ic0 = array(
        "name"          =>  "Requests vs. Hits",
        "key"           =>  "_ic0",
        "report_name"   =>  "ic0",
        "func_cntrs"    =>  array("statcache" => array("cacherequestsrate", "cachehitsrate")));
    var $_ic1 = array(
        "name"          =>  "Hit ratio(%)",
        "key"           =>  "_ic1",
        "report_name"   =>  "ic1",
        "func_cntrs"    =>  array("statcache" => array("cachepercenthit")));
    var $_ic2 = array(
        "name"          =>  "Origin bandwidth saved(%)",
        "key"           =>  "_ic2",
        "report_name"   =>  "ic2",
        "func_cntrs"    =>  array("statcache" => array("cachepercentoriginbandwidthsaved")));
    var $_ic3 = array(
        "name"          =>  "Bytes served by NetScaler vs. Bytes served by cache",
        "key"           =>  "_ic3",
        "report_name"   =>  "ic3",
        "func_cntrs"    =>  array("statcache" => array("cacheresponsebytesrate", "cachebytesservedrate")));
    var $_ic4 = array(
        "name"          =>  "Byte hit ratio(%)",
        "key"           =>  "_ic4",
        "report_name"   =>  "ic4",
        "func_cntrs"    =>  array("statcache" => array("cachepercentbytehit")));
    var $_ic5 = array(
        "name"          =>  "Flashcache misses vs. hits",
        "key"           =>  "_ic5",
        "report_name"   =>  "ic5",
        "func_cntrs"    =>  array("statcache" => array("cacheflashcachemissesrate", "cacheflashcachehitsrate")));
    var $_ic6 = array(
        "name"          =>  "Misses vs. Storable misses",
        "key"           =>  "_ic6",
        "report_name"   =>  "ic6",
        "func_cntrs"    =>  array("statcache" => array("cachemissesrate", "cachestoreablemissesrate")));
    var $_ic7 = array(
        "name"          =>  "Misses vs. Non-storable misses",
        "key"           =>  "_ic7",
        "report_name"   =>  "ic7",
        "func_cntrs"    =>  array("statcache" => array("cachemissesrate", "cachenonstoreablemissesrate")));
    var $_ic8 = array(
        "name"          =>  "Revalidations vs. Successful revalidations",
        "key"           =>  "_ic8",
        "report_name"   =>  "ic8",
        "func_cntrs"    =>  array("statcache" => array("cacherevalidationmissrate", "cachesuccessfulrevalidationrate")));
    var $_ic9 = array(
        "name"          =>  "Inval requests vs. Parameterized inval requests",
        "key"           =>  "_ic9",
        "report_name"   =>  "ic9",
        "func_cntrs"    =>  array("statcache" => array("cacheinvalidationrequestsrate", "cacheparameterizedinvalidationrequestsrate")));
    var $_ic10 = array(
        "name"          =>  "Inval requests vs. Full inval requests",
        "key"           =>  "_ic10",
        "report_name"   =>  "ic10",
        "func_cntrs"    =>  array("statcache" => array("cacheinvalidationrequestsrate", "cachenonparameterizedinvalidationrequestsrate")));
    var $_ic11 = array(
        "name"          =>  "Parameterized requests vs. Total parameterized hits",
        "key"           =>  "_ic11",
        "report_name"   =>  "ic11",
        "func_cntrs"    =>  array("statcache" => array("cacheparameterizedrequestsrate", "cacheparameterizedhitsrate")));
    var $_ic12 = array(
        "name"          =>  "Parameterized non-304 vs. 304 hits",
        "key"           =>  "_ic12",
        "report_name"   =>  "ic12",
        "func_cntrs"    =>  array("statcache" => array("cacheparameterizednon304hitsrate", "cacheparameterized304hitsrate")));
    var $_ic13 = array(
        "name"          =>  "Parameterized 304 hit ratio(%)",
        "key"           =>  "_ic13",
        "report_name"   =>  "ic13",
        "func_cntrs"    =>  array("statcache" => array("cachepercentparameterized304hits")));
    var $_ic14 = array(
        "name"          =>  "Poll every time requests vs. hits",
        "key"           =>  "_ic14",
        "report_name"   =>  "ic14",
        "func_cntrs"    =>  array("statcache" => array("cachepetrequestsrate", "cachepethitsrate")));
    var $_ic15 = array(
        "name"          =>  "Poll every time hit ratio(%)",
        "key"           =>  "_ic15",
        "report_name"   =>  "ic15",
        "func_cntrs"    =>  array("statcache" => array("cachepercentpethits")));
    var $_ic16 = array(
        "name"          =>  "Cached objects vs. Utilized memory(KB)",
        "key"           =>  "_ic16",
        "report_name"   =>  "ic16",
        "func_cntrs"    =>  array("statcache" => array("cachenumcached", "cacheutilizedmemorykb")),
        "properties"    =>  array("secondary_y_axis" =>  1));
    var $_aaa0 = array(
        "name"          =>  "Authentication successes vs. failures",
        "key"           =>  "_aaa0",
        "report_name"   =>  "aaa0",
        "func_cntrs"    =>  array("stataaa" => array("aaaauthsuccessrate", "aaaauthfailrate")));
    var $_aaa1 = array(
        "name"          =>  "HTTP authorization successes vs. failures",
        "key"           =>  "_aaa1",
        "report_name"   =>  "aaa1",
        "func_cntrs"    =>  array("stataaa" => array("aaaauthonlyhttpsuccessrate", "aaaauthonlyhttpfailrate")));
    var $_aaa2 = array(
        "name"          =>  "Non-HTTP authorization successes vs. failures",
        "key"           =>  "_aaa2",
        "report_name"   =>  "aaa2",
        "func_cntrs"    =>  array("stataaa" => array("aaaauthnonhttpsuccessrate", "aaaauthnonhttpfailrate")));
    var $_aaa3 = array(
        "name"          =>  "Current AAA sessions",
        "key"           =>  "_aaa3",
        "report_name"   =>  "aaa3",
        "func_cntrs"    =>  array("stataaa" => array("aaacursessions")));
    var $_aaa4 = array(
        "name"          =>  "AAA sessions",
        "key"           =>  "_aaa4",
        "report_name"   =>  "aaa4",
        "func_cntrs"    =>  array("stataaa" => array("aaatotsessions")));
    var $_aaa5 = array(
        "name"          =>  "Current ICAOnly sessions",
        "key"           =>  "_aaa5",
        "report_name"   =>  "aaa5",
        "func_cntrs"    =>  array("stataaa" => array("aaacuricasessions")));
    var $_aaa6 = array(
        "name"          =>  "Current ICAOnly connections",
        "key"           =>  "_aaa6",
        "report_name"   =>  "aaa6",
        "func_cntrs"    =>  array("stataaa" => array("aaacuricaonlyconn")));
    var $_aaa7 = array(
        "name"          =>  "Current ICA (smart access) connections",
        "key"           =>  "_aaa7",
        "report_name"   =>  "aaa7",
        "func_cntrs"    =>  array("stataaa" => array("aaacuricaconn")));
    var $_vpn0 = array(
        "name"          =>  "WINS queries received vs. No. of SSLVPN tunnels",
        "key"           =>  "_vpn0",
        "report_name"   =>  "vpn0",
        "func_cntrs"    =>  array("statvpn" => array("winsrequesthitrate", "csrequesthitrate")));
    var $_vpn1 = array(
        "name"          =>  "Backend HTTP vs. non-HTTP server probes",
        "key"           =>  "_vpn1",
        "report_name"   =>  "vpn1",
        "func_cntrs"    =>  array("statvpn" => array("cshttpprobehitrate", "csnonhttpprobehitrate")));
    var $_appfw0 = array(
        "name"          =>  "Requests",
        "key"           =>  "_appfw0",
        "report_name"   =>  "appfw0",
        "func_cntrs"    =>  array("statappfw" => array("appfirewallrequestsrate", "appfirewallresponsesrate", "appfirewallabortsrate", "appfirewallredirectsrate")));
    var $_appfw1 = array(
        "name"          =>  "Application Firewall Requests vs. Total Requests",
        "key"           =>  "_appfw1",
        "report_name"   =>  "appfw1",
        "func_cntrs"    =>  array("statappfw" => array("appfirewallrequestsrate"), "statns" => array("httprequestsrate")));
    var $_appfw12 = array(
        "name"          =>  "Request Bytes vs. Response Bytes",
        "key"           =>  "_appfw12",
        "report_name"   =>  "appfw5",
        "func_cntrs"    =>  array("statappfw" => array("appfirewallreqbytesrate", "appfirewallresbytesrate")));
    var $_appfw2 = array(
        "name"          =>  "Total Violations",
        "key"           =>  "_appfw2",
        "report_name"   =>  "appfw2",
        "func_cntrs"    =>  array("statappfw" => array("appfirewallviolrate")));
    var $_appfw3 = array(
        "name"          =>  "Common Violations",
        "key"           =>  "_appfw3",
        "report_name"   =>  "appfw3",
        "func_cntrs"    =>  array("statappfw" => array("appfirewallviolstarturl", "appfirewallvioldenyurl", "appfirewallviolrefererheader", "appfirewallviolbufferoverflow", "appfirewallviolcookie", "appfirewallviolcreditcard")));
    var $_appfw4 = array(
        "name"          =>  "HTML Violations",
        "key"           =>  "_appfw4",
        "report_name"   =>  "appfw3",
        "func_cntrs"    =>  array("statappfw" => array("appfirewallviolcsrftag", "appfirewallviolxss", "appfirewallviolsql", "appfirewallviolfieldformat", "appfirewallviolfieldconsistency")));
    var $_appfw5 = array(
        "name"          =>  "XML Violations",
        "key"           =>  "_appfw5",
        "report_name"   =>  "appfw3",
        "func_cntrs"    =>  array("statappfw" => array("appfirewallviolwellformednessviolations", "appfirewallviolxdosviolations", "appfirewallviolmsgvalviolations", "appfirewallviolwsiviolations", "appfirewallviolxmlsqlviolations", "appfirewallviolxmlxssviolations", "appfirewallviolxmlattachmentviolations", "appfirewallviolxmlsoapfaultviolations")));
    var $_appfw6 = array(
        "name"          =>  "Profile Usage Rate",
        "key"           =>  "_appfw6",
        "report_name"   =>  "appfw6",
        "func_cntrs"    =>  array("statappfwprofile" => array("appfirewallrequestsperprofilerate", "appfirewallresponsesperprofilerate", "appfirewallreqbytesperprofilerate", "appfirewallresbytesperprofilerate")),
        "properties"    =>  array("plot_type" => "column"));
    var $_appfw7 = array(
        "name"          =>  "Backend Performance",
        "key"           =>  "_appfw7",
        "report_name"   =>  "appfw6",
        "func_cntrs"    =>  array("statappfwprofile" => array("appfirewallshortavgresptimeperprofile", "appfirewalllongavgresptimeperprofile")));
    var $_appfw8 = array(
        "name"          =>  "HTTP Errors Rate",
        "key"           =>  "_appfw8",
        "report_name"   =>  "appfw6",
        "func_cntrs"    =>  array("statappfwprofile" => array("appfirewallret4xxperprofilerate", "appfirewallret5xxperprofilerate")),
        "properties"    =>  array("plot_type" => "sbar"));
    var $_appfw9 = array(
        "name"          =>  "Common Violations Per Profile",
        "key"           =>  "_appfw9",
        "report_name"   =>  "appfw4",
        "func_cntrs"    =>  array("statappfwprofile" => array("appfirewallviolstarturlperprofile", "appfirewallvioldenyurlperprofile", "appfirewallviolrefererheaderperprofile", "appfirewallviolbufferoverflowperprofile", "appfirewallviolcookieperprofile", "appfirewallviolcreditcardperprofile")));
    var $_appfw10 = array(
        "name"          =>  "HTML Violations Per Profile",
        "key"           =>  "_appfw10",
        "report_name"   =>  "appfw4",
        "func_cntrs"    =>  array("statappfwprofile" => array("appfirewallviolcsrftagperprofile", "appfirewallviolxssperprofile", "appfirewallviolsqlperprofile", "appfirewallviolfieldformatperprofile", "appfirewallviolfieldconsistencyperprofile")));
    var $_appfw11 = array(
        "name"          =>  "XML Violations Per Profile",
        "key"           =>  "_appfw11",
        "report_name"   =>  "appfw4",
        "func_cntrs"    =>  array("statappfwprofile" => array("appfirewallviolwellformednessviolationsperprofile", "appfirewallviolxdosviolationsperprofile", "appfirewallviolmsgvalviolationsperprofile", "appfirewallviolwsiviolationsperprofile", "appfirewallviolxmlsqlviolationsperprofile", "appfirewallviolxmlxssviolationsperprofile", "appfirewallviolxmlattachmentviolationsperprofile", "appfirewallviolxmlsoapfaultviolationsperprofile")));
    var $_inat0 = array(
        "name"          =>  "TCP Packets translated v4->v6 vs. v6->v4",
        "key"           =>  "_inat0",
        "report_name"   =>  "inat0",
        "func_cntrs"    =>  array("statinat" => array("nat46tcp46rate", "nat46tcp64rate")));
	var $_bw0 = array(
        "name"          =>  "Latency Statistics",
        "key"           =>  "_bw0",
        "report_name"   =>  "bw0",
        "func_cntrs"    =>  array("statsystembw" => array("httpcltttlpmax","httpcltttfpmax")));
	var $_bw1 = array(
        "name"          =>  "Response statistics",
        "key"           =>  "_bw1",
        "report_name"   =>  "bw1",
		"func_cntrs"    =>  array("statsystembw" => array("httpsvr200okresprate")));
	var $_bw2 = array(
        "name"          =>  "Error statistics",
        "key"           =>  "_bw2",
        "report_name"   =>  "bw2",
		"func_cntrs"    =>  array("statsystembw" => array("httpsvr404notfoundrate","httpclterrstrayrate")));


    private $comparative_charts;
    public function __construct()
    {

        require_once(APPPATH . "controllers/common/rapi.php");
        $rapi = new rapi(true);
        if($rapi->is_partiton_configured_and_is_not_default_partition())
{
    $this->comparative_charts = array(
    array("name"  =>  "System",
          "nodes" =>  array($this->_sys0,
                            array("name"    =>  "HTTP",
                                  "nodes"   =>  array($this->_http0, $this->_http1, $this->_http2, $this->_http3, $this->_http4)),
                    
                            )),
    array("name"  =>  "Network",
          "nodes" =>  array(array("name"    =>  "TCP",
                                  "nodes"   =>  array($this->_tcp0, $this->_tcp1, $this->_tcp2)),
                            array("name"    =>  "IP",
                                  "nodes"   =>  array($this->_ip0, $this->_ip1, $this->_ip2)),
                            array("name"    =>  "IPv6",
                                  "nodes"   =>  array($this->_ipv60, $this->_ipv61)),
                            array("name"    =>  "ICMP",
                                  "nodes"   =>  array($this->_icmp0, $this->_icmp1)),
                            array("name"    =>  "ICMPv6",
                                  "nodes"   =>  array($this->_icmpv60, $this->_icmpv61, $this->_icmpv62, $this->_icmpv63, $this->_icmpv64, $this->_icmpv65, $this->_icmpv66, $this->_icmpv67)),
                           array("name"    =>  "INAT",
                                  "nodes"   =>  array($this->_inat0))
                                  )),
    array("name"  =>  "SSL",
          "nodes" =>  array($this->_ssl0, $this->_ssl1, $this->_ssl2, $this->_ssl3, $this->_ssl4)),
    array("name"  =>  "Compression",
          "nodes" =>  array(array("name"    =>  "HTTP Compression",
                                  "nodes"   =>  array($this->_hcmp0, $this->_hcmp1)),
                            array("name"    =>  "TCP Compression",
                                  "nodes"   =>  array($this->_tcmp0, $this->_tcmp1)),
                            /*This functionality will come back in future
                            array("name"    =>  "Delta Compression",
                                  "nodes"   =>  array($this->_dcmp0, $this->_dcmp1)),*/
                            array("name"    =>  "Decompression",
                                  "nodes"   =>  array($this->_decmp0, $this->_decmp1))))
);
}
else if(is_sgw())
{
$this->comparative_charts = array(
    array("name"  =>  "NetScaler Gateway",
          "nodes" =>  array(array("name"    =>  "AAA",
                                  "nodes"   =>  array($this->_aaa0, $this->_aaa1, $this->_aaa2, $this->_aaa5, $this->_aaa6)),
                            $this->_vpn1)),
    array("name"  =>  "System",
          "nodes" =>  array($this->_sys0,
                            array("name"    =>  "HA",
                                  "nodes"   =>  array($this->_ha0)))),
    array("name"  =>  "Network",
          "nodes" =>  array(array("name"    =>  "TCP",
                                  "nodes"   =>  array($this->_tcp0, $this->_tcp1, $this->_tcp2)),
                            array("name"    =>  "IP",
                                  "nodes"   =>  array($this->_ip0, $this->_ip1, $this->_ip2)),
                            array("name"    =>  "ICMP",
                                  "nodes"   =>  array($this->_icmp0, $this->_icmp1)),
                            array("name"    =>  "INAT",
                                  "nodes"   =>  array($this->_inat0))
                                  )),
    array("name"  =>  "SSL",
          "nodes" =>  array($this->_ssl0, $this->_ssl1, $this->_ssl2, $this->_ssl3, $this->_ssl4))
    );
}
	else if((is_ns() || is_xenmobile()) && is_bw())
	{
$this->comparative_charts = array(
    array("name"  =>  "System",
          "nodes" =>  array($this->_sys0,
                            array("name"    =>  "HTTP",
                                  "nodes"   =>  array($this->_http0, $this->_http1, $this->_http2, $this->_http3, $this->_http4)),
                            array("name"    =>  "HA",
							      "nodes"   =>  array($this->_ha0)),
							array("name"    =>  "BW",
                                  "nodes"   =>  array($this->_bw0, $this->_bw1, $this->_bw2)),
                            array("name"    =>  "Cluster Instance",
                                  "nodes"   =>  array($this->_cli0, $this->_cli1)),
                            array("name"    =>  "Cluster Node",
                                  "nodes"   =>  array($this->_cln0)))),
    array("name"  =>  "Network",
          "nodes" =>  array(array("name"    =>  "TCP",
                                  "nodes"   =>  array($this->_tcp0, $this->_tcp1, $this->_tcp2)),
                            array("name"    =>  "IP",
                                  "nodes"   =>  array($this->_ip0, $this->_ip1, $this->_ip2)),
                            array("name"    =>  "IPv6",
                                  "nodes"   =>  array($this->_ipv60, $this->_ipv61)),
                            array("name"    =>  "ICMP",
                                  "nodes"   =>  array($this->_icmp0, $this->_icmp1)),
                            array("name"    =>  "ICMPv6",
                                  "nodes"   =>  array($this->_icmpv60, $this->_icmpv61, $this->_icmpv62, $this->_icmpv63, $this->_icmpv64, $this->_icmpv65, $this->_icmpv66, $this->_icmpv67)),
                           array("name"    =>  "INAT",
                                  "nodes"   =>  array($this->_inat0))
                                  )),
    array("name"  =>  "SSL",
          "nodes" =>  array($this->_ssl0, $this->_ssl1, $this->_ssl2, $this->_ssl3, $this->_ssl4)),
    array("name"  =>  "Compression",
          "nodes" =>  array(array("name"    =>  "HTTP Compression",
                                  "nodes"   =>  array($this->_hcmp0, $this->_hcmp1)),
                            array("name"    =>  "TCP Compression",
                                  "nodes"   =>  array($this->_tcmp0, $this->_tcmp1)),
                            /*This functionality will come back in future
                            array("name"    =>  "Delta Compression",
                                  "nodes"   =>  array($this->_dcmp0, $this->_dcmp1)),*/
                            array("name"    =>  "Decompression",
                                  "nodes"   =>  array($this->_decmp0, $this->_decmp1)))),
    array("name"  =>  "Integrated Cache",
          "nodes" =>  array($this->_ic0, $this->_ic1, $this->_ic2, $this->_ic3, $this->_ic4, $this->_ic5, $this->_ic6, $this->_ic7, $this->_ic8, $this->_ic9, $this->_ic10, $this->_ic11, $this->_ic12, $this->_ic13, $this->_ic14, $this->_ic15, $this->_ic16)),
    array("name"  =>  "NetScaler Gateway",
          "nodes" =>  array(array("name"    =>  "AAA",
                                  "nodes"   =>  array($this->_aaa0, $this->_aaa1, $this->_aaa2, $this->_aaa3, $this->_aaa4, $this->_aaa5, $this->_aaa6, $this->_aaa7)),
                            $this->_vpn0, $this->_vpn1)),
    array("name"  =>  "Application Firewall",
          "nodes" =>  array(array("name"    =>  "Traffic",
                                  "nodes"   =>  array($this->_appfw0, $this->_appfw1, $this->_appfw12)),
                            array("name"    =>  "Violations",
                                  "nodes"   =>  array($this->_appfw2, $this->_appfw3, $this->_appfw4, $this->_appfw5)))),
    array("name"  =>  "Application Firewall (per Profile)",
          "nodes" =>  array(array("name"    =>  "Per Profile Monitoring",
                                  "nodes"   =>  array($this->_appfw6, $this->_appfw7, $this->_appfw8)),
                            array("name"    =>  "Violations",
                                  "nodes"   =>  array($this->_appfw9, $this->_appfw10, $this->_appfw11))))
);
}
else if((is_ns() || is_xenmobile()) && !is_bw())
{
$this->comparative_charts = array(
    array("name"  =>  "System",
          "nodes" =>  array($this->_sys0,
                            array("name"    =>  "HTTP",
                                  "nodes"   =>  array($this->_http0, $this->_http1, $this->_http2, $this->_http3, $this->_http4)),
                            array("name"    =>  "HA",
                                  "nodes"   =>  array($this->_ha0)),
                            array("name"    =>  "Cluster Instance",
                                  "nodes"   =>  array($this->_cli0, $this->_cli1)),
                            array("name"    =>  "Cluster Node",
                                  "nodes"   =>  array($this->_cln0)))),
    array("name"  =>  "Network",
          "nodes" =>  array(array("name"    =>  "TCP",
                                  "nodes"   =>  array($this->_tcp0, $this->_tcp1, $this->_tcp2)),
                            array("name"    =>  "IP",
                                  "nodes"   =>  array($this->_ip0, $this->_ip1, $this->_ip2)),
                            array("name"    =>  "IPv6",
                                  "nodes"   =>  array($this->_ipv60, $this->_ipv61)),
                            array("name"    =>  "ICMP",
                                  "nodes"   =>  array($this->_icmp0, $this->_icmp1)),
                            array("name"    =>  "ICMPv6",
                                  "nodes"   =>  array($this->_icmpv60, $this->_icmpv61, $this->_icmpv62, $this->_icmpv63, $this->_icmpv64, $this->_icmpv65, $this->_icmpv66, $this->_icmpv67)),
                           array("name"    =>  "INAT",
                                  "nodes"   =>  array($this->_inat0))
                                  )),
    array("name"  =>  "SSL",
          "nodes" =>  array($this->_ssl0, $this->_ssl1, $this->_ssl2, $this->_ssl3, $this->_ssl4)),
    array("name"  =>  "Compression",
          "nodes" =>  array(array("name"    =>  "HTTP Compression",
                                  "nodes"   =>  array($this->_hcmp0, $this->_hcmp1)),
                            array("name"    =>  "TCP Compression",
                                  "nodes"   =>  array($this->_tcmp0, $this->_tcmp1)),
                            /*This functionality will come back in future
                            array("name"    =>  "Delta Compression",
                                  "nodes"   =>  array($this->_dcmp0, $this->_dcmp1)),*/
                            array("name"    =>  "Decompression",
                                  "nodes"   =>  array($this->_decmp0, $this->_decmp1)))),
    array("name"  =>  "Integrated Cache",
          "nodes" =>  array($this->_ic0, $this->_ic1, $this->_ic2, $this->_ic3, $this->_ic4, $this->_ic5, $this->_ic6, $this->_ic7, $this->_ic8, $this->_ic9, $this->_ic10, $this->_ic11, $this->_ic12, $this->_ic13, $this->_ic14, $this->_ic15, $this->_ic16)),
    array("name"  =>  "NetScaler Gateway",
          "nodes" =>  array(array("name"    =>  "AAA",
                                  "nodes"   =>  array($this->_aaa0, $this->_aaa1, $this->_aaa2, $this->_aaa3, $this->_aaa4, $this->_aaa5, $this->_aaa6, $this->_aaa7)),
                            $this->_vpn0, $this->_vpn1)),
    array("name"  =>  "Application Firewall",
          "nodes" =>  array(array("name"    =>  "Traffic",
                                  "nodes"   =>  array($this->_appfw0, $this->_appfw1, $this->_appfw12)),
                            array("name"    =>  "Violations",
                                  "nodes"   =>  array($this->_appfw2, $this->_appfw3, $this->_appfw4, $this->_appfw5)))),
    array("name"  =>  "Application Firewall (per Profile)",
          "nodes" =>  array(array("name"    =>  "Per Profile Monitoring",
                                  "nodes"   =>  array($this->_appfw6, $this->_appfw7, $this->_appfw8)),
                            array("name"    =>  "Violations",
                                  "nodes"   =>  array($this->_appfw9, $this->_appfw10, $this->_appfw11))))
);
}
else if(is_agee())
{
$this->comparative_charts = array(
    array("name"  =>  "NetScaler Gateway",
          "nodes" =>  array(array("name"    =>  "AAA",
                                  "nodes"   =>  array($this->_aaa0, $this->_aaa1, $this->_aaa2, $this->_aaa3, $this->_aaa4, $this->_aaa5, $this->_aaa6, $this->_aaa7)),
                            $this->_vpn0, $this->_vpn1)),
    array("name"  =>  "System",
          "nodes" =>  array($this->_sys0,
                            array("name"    =>  "HA",
                                  "nodes"   =>  array($this->_ha0)))),
    array("name"  =>  "Network",
          "nodes" =>  array(array("name"    =>  "TCP",
                                  "nodes"   =>  array($this->_tcp0, $this->_tcp1, $this->_tcp2)),
                            array("name"    =>  "IP",
                                  "nodes"   =>  array($this->_ip0, $this->_ip1, $this->_ip2)),
                            array("name"    =>  "ICMP",
                                  "nodes"   =>  array($this->_icmp0, $this->_icmp1)),
                            array("name"    =>  "INAT",
                                  "nodes"   =>  array($this->_inat0))
                                  )),
    array("name"  =>  "SSL",
          "nodes" =>  array($this->_ssl0, $this->_ssl1, $this->_ssl2, $this->_ssl3, $this->_ssl4)),
    array("name"  =>  "Compression",
          "nodes" =>  array(array("name"    =>  "TCP Compression",
                                  "nodes"   =>  array($this->_tcmp0, $this->_tcmp1)),
                            /*This functionality will come back in future
                            array("name"    =>  "Delta Compression",
                                  "nodes"   =>  array($this->_dcmp0, $this->_dcmp1)),*/
                            array("name"    =>  "Decompression",
                                  "nodes"   =>  array($this->_decmp0, $this->_decmp1)))),
    array("name"  =>  "Integrated Cache",
          "nodes" =>  array($this->_ic0, $this->_ic1, $this->_ic2, $this->_ic3, $this->_ic4, $this->_ic5, $this->_ic6, $this->_ic7, $this->_ic8, $this->_ic9, $this->_ic10, $this->_ic11, $this->_ic12, $this->_ic13, $this->_ic14, $this->_ic15, $this->_ic16))
);
}
else if(is_ocb() || is_occ())
{
$this->comparative_charts = array(
    array("name"  =>  "System",
          "nodes" =>  array($this->_sys0,
                            array("name"    =>  "HTTP",
                                  "nodes"   =>  array($this->_http0, $this->_http1, $this->_http2, $this->_http3, $this->_http4)),
                            array("name"    =>  "HA",
                                  "nodes"   =>  array($this->_ha0)))),
    array("name"  =>  "Network",
          "nodes" =>  array(array("name"    =>  "TCP",
                                  "nodes"   =>  array($this->_tcp0, $this->_tcp1, $this->_tcp2)),
                            array("name"    =>  "IP",
                                  "nodes"   =>  array($this->_ip0, $this->_ip1, $this->_ip2)),
                            array("name"    =>  "IPv6",
                                  "nodes"   =>  array($this->_ipv60, $this->_ipv61)),
                            array("name"    =>  "ICMP",
                                  "nodes"   =>  array($this->_icmp0, $this->_icmp1)),
                            array("name"    =>  "ICMPv6",
                                  "nodes"   =>  array($this->_icmpv60, $this->_icmpv61, $this->_icmpv62, $this->_icmpv63, $this->_icmpv64, $this->_icmpv65, $this->_icmpv66, $this->_icmpv67)),
                            array("name"    =>  "INAT",
                                  "nodes"   =>  array($this->_inat0))
                                  )),
    array("name"  =>  "SSL",
          "nodes" =>  array($this->_ssl0, $this->_ssl1, $this->_ssl2, $this->_ssl3, $this->_ssl4))
);
}
else if(is_bytemobile())
{
$this->comparative_charts = array(
    array("name"  =>  "System",
          "nodes" =>  array($this->_sys0,
                            array("name"    =>  "HTTP",
                                  "nodes"   =>  array($this->_http0, $this->_http1, $this->_http2, $this->_http3, $this->_http4)),
                            array("name"    =>  "HA",
                                  "nodes"   =>  array($this->_ha0)),
                            array("name"    =>  "Cluster Instance",
                                  "nodes"   =>  array($this->_cli0, $this->_cli1)),
                            array("name"    =>  "Cluster Node",
                                  "nodes"   =>  array($this->_cln0)))),
    array("name"  =>  "Network",
          "nodes" =>  array(array("name"    =>  "TCP",
                                  "nodes"   =>  array($this->_tcp0, $this->_tcp1, $this->_tcp2)),
                            array("name"    =>  "IP",
                                  "nodes"   =>  array($this->_ip0, $this->_ip1, $this->_ip2)),
                            array("name"    =>  "IPv6",
                                  "nodes"   =>  array($this->_ipv60, $this->_ipv61)),
                            array("name"    =>  "ICMP",
                                  "nodes"   =>  array($this->_icmp0, $this->_icmp1)),
                            array("name"    =>  "ICMPv6",
                                  "nodes"   =>  array($this->_icmpv60, $this->_icmpv61, $this->_icmpv62, $this->_icmpv63, $this->_icmpv64, $this->_icmpv65, $this->_icmpv66, $this->_icmpv67)),
                            array("name"    =>  "INAT",
                                  "nodes"   =>  array($this->_inat0))
                                  )),
    array("name"  =>  "SSL",
          "nodes" =>  array($this->_ssl0, $this->_ssl1, $this->_ssl2, $this->_ssl3, $this->_ssl4)),
    array("name"  =>  "Compression",
          "nodes" =>  array(array("name"    =>  "HTTP Compression",
                                  "nodes"   =>  array($this->_hcmp0, $this->_hcmp1)),
                            array("name"    =>  "TCP Compression",
                                  "nodes"   =>  array($this->_tcmp0, $this->_tcmp1)),
                            /*This functionality will come back in future
                            array("name"    =>  "Delta Compression",
                                  "nodes"   =>  array($this->_dcmp0, $this->_dcmp1)),*/
                            array("name"    =>  "Decompression",
                                  "nodes"   =>  array($this->_decmp0, $this->_decmp1)))),
    array("name"  =>  "NetScaler Gateway",
          "nodes" =>  array(array("name"    =>  "AAA",
                                  "nodes"   =>  array($this->_aaa0, $this->_aaa1, $this->_aaa2, $this->_aaa3, $this->_aaa4, $this->_aaa5)),
                            $this->_vpn0, $this->_vpn1))
);
}
    }

    public function get_comparative_charts()
    {
        return $this->comparative_charts;
    }

    public function get_default_comparative_chart_name($id)
    {
        if(is_agee())
            return $id == "0" ? "_aaa3" : "_aaa5";
        return $id == "0" ? "_http0" : "_tcp0";
    }

    public function get_comparative_chart($comparative_chart_name)
    {
        try
        {
            $prop = new ReflectionProperty("comparative_chart_definitions", $comparative_chart_name);
            return $prop->getValue($this);
        }
        catch(Exception $e)
        {
            return null;
        }
    }
}
?>
