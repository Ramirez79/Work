<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 *************** Defining properties for charts & data sets ***************
 * Add "properties" (as an associative array) to each of the charts/elements
 *
 * Basic syntax for color values:
 *  "<hex>"             Ex: "0xFFOOOO" for red color, "Ox80FF0000" for partial transparent red color
 *  "<hex>,<int>"       Ex: "0xFF0000,135" for a metallic red color with brightness modulation in the specified angle
 *  "<hex>,<hex>"       Ex: "0xFF0000,0x0000FF" for a 2-point linear gradient color from red to blue
 *  "<hex>,<line type>" Ex: "0xFF0000,Dash" for a Dashed line. Possible values are "Dash", ""Dot", "DotDash" & "AltDash"
 *
 * Basic syntax for scale values:
 *  "<int>,<int>"       Ex: "0,100" for a linear scale (lower limit 0, upper limit 100 - percentage scale)
 *  "log"               (for a log scale)
 *  "K", "M", "G"       (for a scaling factor of 10^3, 10^6 & 10^9 respectively)
 *
 * Syntax of chart level properties:
 *  "bg_color"          =>  <see color specification above> [Background color of chart outside plot area, default: 0xFFFFFF]
 *  "plot_bg_color"     =>  <see color specification above> [Background color of plot area, default: 0xFFFFFF]
 *  "plot_edge_color"   =>  <see color specification above> [Edge color of plot area, default: 0x336699]
 *  "h_grid_color"      =>  <see color specification above> [Horizontal grid color, default: 0xcccccc]
 *  "v_grid_color"      =>  <see color specification above> [Vertical grid color, default: 0xcccccc]
 *  "h_grid_width"      =>  <int>   [Horizontal grid thickness, default: 1]
 *  "v_grid_width"      =>  <int>   [Vertical grid thickness, default: 1]
 *  "text_font_color"   =>  <see color specification above> [Text font color (legends, title, left y-axis & x-axis labels, default: 0x336699]
 *  "scale"             =>  <see scale specification above> [Scale of left y-axis, default: auto]
 *  "scale2"            =>  <see scale specification above> [Scale of right y-axis, applicable only if "y_axis2" is defined, default: auto]
 *  "y_axis2"           =>  <data set number>   [Ex: "y_axis2"  =>  0, to plot first data set on right y-axis, default: -1]
 *  "multiple_axes"     =>  <bool>  [To plot each data set in its own y-axis and axes won't be visible, default: false]
 *
 * Basic syntax of data set level properties:
 *  Suppose if counters are defined as "cntrs" =>  "httptotgets,httptotposts,httptotothers", to set color of "httptotgets"
 *  and to set plot type of "httptotposts" counter, define properties as
 *  0   =>  array("color"   =>  <color>,...), 1 =>  array("plot_type"   =>  <plot_type>...)
 *  Data set numbers (0, 1..) need not be the same as the order of "cntrs". If "ents" & "cores" are specified, first data
 *  set refers to "counter1 x entity 1 x core1"; second data set refers to "counter2 x entity1 x core1" and so on.
 *
 * Supported layers for data sets:
 *  Use predefined PHP constants:
 *  LINE_GRAPH, BAR_GRAPH, AREA_GRAPH, SBAR_GRAPH, SAREA_GRAPH, SCATTER_GRAPH, SPLINE_GRAPH & STEP_LINE_GRAPH
 *  There can be a maximum of one stacked area and one stacked bar layer.
 *
 * Basic syntax for effects:
 *  "<direction of lighting>"          Ex: "Top". Possible values are "Top", "Bottom", "Right" & "Left". Gives a soft lighting effect (a special shading effect that looks like gradient coloring)
 * "<glare>,<direction of lighting>"   Ex: "NormalGlare,Top". Possible values for glare are "NormalGlare", "ReducedGlare" & "NoGlare". Gives a glassy effect (complex shading effect that emulates tinted glass or semi-transparent plastic material)
 *
 * Basic syntax for bar shapes:
 *  "<shape>"           Ex: "Circle" to have a cylidrical bar. Possible values are "Square", "Diamond", "Triangle", "RightTriangle", "LeftTriangle", "InvertedTriangle", "Circle"
 *  "<shape>,<sides>"   Ex: "Star,6" to have a 6 sided star. Possible values for shape are "Star" & "Polygon". Default value for side is 4
 *  "<shape>,<width>"   Ex: "Cross,4" to have a plus sign of arm width 4. Possible values for shape are "Cross" & "Cross2". Default value is 5.
 *
 * Syntax of data set level properties:
 *  "color"             =>  <see color specification above> [Color of the layer (line, bar etc), default: comes from pre-defined color palette, BAR_GRAPH & AREA_GRAPH will have transparent effect]
 *  "plot_type"         =>  <see supported layers above>    [Plot/layer type, default: line]
 *  "3d"                =>  <bool>  [whether the layer is 3-dimensional or not, default: true for BAR_GRAPH, AREA_GRAPH, SBAR_GRAPH, SAREA_GRAPH & false for rest]
 *  "width"             =>  <int>   [Thickness of layer, default: 15 for BAR_GRAPH, SBAR_GRAPH & 1 for rest]
 *  "effect"            =>  <see effect specification above>    [soft lighting or glass effect, default: no effect]
 *  "shape"             =>  <see shape specification above> [Applicable only for BAR_GRAPH & SBAR_GRAPH, default: Square]
 *
 */
class report_definitions
{
    //Define leaves of tree (reports) in this format
    var $sys0 = array(
        "name"      =>  "CPU vs. Memory Usage and HTTP Requests Rate",
        "key"       =>  "sys0",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statns",
                                    //Should be in sync with get_cpu_usage_counters() defined in reporting.js
                                    "alt_expr"  =>  "   if(is_default_data_source(\$ds))
                                                        {
                                                            if(is_vmpe_mode())
                                                                return \"pktcpuusagepcnt,memusagepcnt,httprequestsRate\";
                                                        }
                                                        else
                                                        {
                                                            if(!ns_empty(\$cores))
                                                                return \"pktcpuusagepcnt,memusagepcnt,httprequestsRate\";
                                                        }
                                                        return (\$numcpus > 1) ? \"rescpuusagepcnt,memusagepcnt,httprequestsRate\" : ((\$numcpus == 1) ? \"cpuusagepcnt,memusagepcnt,httprequestsRate\" : \"memusagepcnt,httprequestsRate\");
                                                    ",
                                    "properties"    =>  array("y_axis2" =>  2))));
    var $http0 = array(
        "name"      =>  "GETs vs. POSTs vs. Non-GET/POSTs Requests received",
        "key"       =>  "http0",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statprotocolhttp",
                                    "cntrs" =>  "httpgetsRate,httppostsRate,httpothersRate"),
                              array("id"    =>  2,
                                    "func"  =>  "statprotocolhttp",
                                    "cntrs" =>  "httptotgets,httptotposts,httptotothers")));
    var $http1 = array(
        "name"      =>  "HTTP/1.0 vs. HTTP/1.1 requests received",
        "key"       =>  "http1",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statprotocolhttp",
                                    "cntrs" =>  "http10requestsRate,http11requestsRate"),
                              array("id"    =>  2,
                                    "func"  =>  "statprotocolhttp",
                                    "cntrs" =>  "httptot10requests,httptot11requests")));
    var $http2 = array(
        "name"      =>  "Request vs. Response bytes received",
        "key"       =>  "http2",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statprotocolhttp",
                                    "cntrs" =>  "httprxrequestbytesRate,httprxresponsebytesRate"),
                              array("id"    =>  2,
                                    "func"  =>  "statprotocolhttp",
                                    "cntrs" =>  "httptotrxrequestbytes,httptotrxresponsebytes",
                                    "properties"    =>  array("scale"   =>  "M"))));
    var $http3 = array(
        "name"      =>  "Request vs. Response bytes transmitted",
        "key"       =>  "http3",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statprotocolhttp",
                                    "cntrs" =>  "httptxrequestbytesRate,httptxresponsebytesRate"),
                              array("id"    =>  2,
                                    "func"  =>  "statprotocolhttp",
                                    "cntrs" =>  "httptottxrequestbytes,httptottxresponsebytes",
                                    "properties"    =>  array("scale"   =>  "M"))));
    var $http4 = array(
        "name"      =>  "HTTP/1.0 vs. HTTP/1.1 responses received",
        "key"       =>  "http4",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statprotocolhttp",
                                    "cntrs" =>  "http10responsesRate,http11responsesRate"),
                              array("id"    =>  2,
                                    "func"  =>  "statprotocolhttp",
                                    "cntrs" =>  "httptot10responses,httptot11responses")));
    var $ha0 = array(
        "name"      =>  "Heartbeats received vs. Heartbeats sent",
        "key"       =>  "ha0",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "stathanode",
                                    "cntrs" =>  "hapktrxRate,hapkttxRate"),
                              array("id"    =>  2,
                                    "func"  =>  "stathanode",
                                    "cntrs" =>  "hatotpktrx,hatotpkttx")));
    var $cli0 = array(
        "name"      =>  "Total Steered Packets",
        "key"       =>  "cli0",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statclusterinstance",
                                    "cntrs" =>  "totsteeredpkts")));
    var $cln0 = array(
        "name"      =>  "Heartbeats Received vs. Sent",
        "key"       =>  "cln0",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statclusternode",
                                    "cntrs" =>  "cltothbrx,cltothbtx")));
	var $bw0 = array(
        "name"      =>  "BWC Latency Stats",
        "key"       =>  "bw0",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statsystembw",
									"cntrs" =>  "httpcltpoolinactive,httpcltpooloutactive"),
		                      array("id"    =>  2,
                                    "func"  =>  "statsystembw",
									"cntrs" =>  "httpcltttlpmax,httpcltttfpmax")));
													
    var $tcp0 = array(
        "name"      =>  "Established Client vs. Server Connections",
        "key"       =>  "tcp0",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statprotocoltcp",
                                    "cntrs" =>  "tcpcurclientconnestablished,tcpcurserverconnestablished")));
    var $tcp1 = array(
        "name"      =>  "Established Server vs. Active Server Connections",
        "key"       =>  "tcp1",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statprotocoltcp",
                                    "cntrs" =>  "tcpcurserverconnestablished,tcpactiveserverconn")));
    var $tcp2 = array(
        "name"      =>  "Surge Queue vs. Spare connections",
        "key"       =>  "tcp2",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statprotocoltcp",
                                    "cntrs" =>  "tcpsurgequeuelen,tcpspareconn")));
    var $ip0 = array(
        "name"      =>  "IP packets received vs. transmitted",
        "key"       =>  "ip0",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statprotocolip",
                                    "cntrs" =>  "iprxpktsRate,iptxpktsRate"),
                              array("id"    =>  2,
                                    "func"  =>  "statprotocolip",
                                    "cntrs" =>  "iptotrxpkts,iptottxpkts",
                                    "properties"    =>  array("scale"   =>  "K"))));
    var $ip1 = array(
        "name"      =>  "IP bytes received vs. transmitted",
        "key"       =>  "ip1",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statprotocolip",
                                    "cntrs" =>  "iprxbytesRate,iptxbytesRate"),
                              array("id"    =>  2,
                                    "func"  =>  "statprotocolip",
                                    "cntrs" =>  "iptotrxbytes,iptottxbytes",
                                    "properties"    =>  array("scale"   =>  "M"))));
    var $ip2 = array(
        "name"      =>  "Megabits received vs. transmitted",
        "key"       =>  "ip2",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statprotocolip",
                                    "cntrs" =>  "iprxmbitsRate,iptxmbitsRate"),
                              array("id"    =>  2,
                                    "func"  =>  "statprotocolip",
                                    "cntrs" =>  "iptotrxmbits,iptottxmbits")));
    var $ipv60 = array(
        "name"      =>  "IPv6 packets received vs. transmitted",
        "key"       =>  "ipv60",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statprotocolipv6",
                                    "cntrs" =>  "ipv6rxpktsRate,ipv6txpktsRate"),
                              array("id"    =>  2,
                                    "func"  =>  "statprotocolipv6",
                                    "cntrs" =>  "ipv6totrxpkts,ipv6tottxpkts",
                                    "properties"    =>  array("scale"   =>  "K"))));
    var $ipv61 = array(
        "name"      =>  "IPv6 bytes received vs. transmitted",
        "key"       =>  "ipv61",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statprotocolipv6",
                                    "cntrs" =>  "ipv6rxbytesRate,ipv6txbytesRate"),
                              array("id"    =>  2,
                                    "func"  =>  "statprotocolipv6",
                                    "cntrs" =>  "ipv6totrxbytes,ipv6tottxbytes",
                                    "properties"    =>  array("scale"   =>  "M"))));
    var $icmp0 = array(
        "name"      =>  "ICMP packets received vs. transmitted",
        "key"       =>  "icmp0",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statprotocolicmp",
                                    "cntrs" =>  "icmprxpktsRate,icmptxpktsRate"),
                              array("id"    =>  2,
                                    "func"  =>  "statprotocolicmp",
                                    "cntrs" =>  "icmptotrxpkts,icmptottxpkts",
                                    "properties"    =>  array("scale"   =>  "K"))));
    var $icmp1 = array(
        "name"      =>  "ICMP bytes received vs. transmitted",
        "key"       =>  "icmp1",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statprotocolicmp",
                                    "cntrs" =>  "icmprxbytesRate,icmptxbytesRate"),
                              array("id"    =>  2,
                                    "func"  =>  "statprotocolicmp",
                                    "cntrs" =>  "icmptotrxbytes,icmptottxbytes",
                                    "properties"    =>  array("scale"   =>  "M"))));
    var $icmpv60 = array(
        "name"      =>  "ICMPv6 packets received vs. transmitted",
        "key"       =>  "icmpv60",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statprotocolicmpv6",
                                    "cntrs" =>  "icmpv6rxpktsRate,icmpv6txpktsRate"),
                              array("id"    =>  2,
                                    "func"  =>  "statprotocolicmpv6",
                                    "cntrs" =>  "icmpv6totrxpkts,icmpv6tottxpkts")));
    var $icmpv61 = array(
        "name"      =>  "ICMPv6 bytes received vs. transmitted",
        "key"       =>  "icmpv61",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statprotocolicmpv6",
                                    "cntrs" =>  "icmpv6rxbytesRate,icmpv6txbytesRate"),
                              array("id"    =>  2,
                                    "func"  =>  "statprotocolicmpv6",
                                    "cntrs" =>  "icmpv6totrxbytes,icmpv6tottxbytes")));
    var $icmpv62 = array(
        "name"      =>  "ICMPv6 NA packets received vs. transmitted",
        "key"       =>  "icmpv62",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statprotocolicmpv6",
                                    "cntrs" =>  "icmpv6totrxna,icmpv6tottxna")));
    var $icmpv63 = array(
        "name"      =>  "ICMPv6 NS packets received vs. transmitted",
        "key"       =>  "icmpv63",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statprotocolicmpv6",
                                    "cntrs" =>  "icmpv6totrxns,icmpv6tottxns")));
    var $icmpv64 = array(
        "name"      =>  "ICMPv6 RA packets received vs. transmitted",
        "key"       =>  "icmpv64",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statprotocolicmpv6",
                                    "cntrs" =>  "icmpv6totrxra,icmpv6tottxra")));
    var $icmpv65 = array(
        "name"      =>  "ICMPv6 RS packets received vs. transmitted",
        "key"       =>  "icmpv65",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statprotocolicmpv6",
                                    "cntrs" =>  "icmpv6totrxrs,icmpv6tottxrs")));
    var $icmpv66 = array(
        "name"      =>  "ICMPv6 Echo Request packets received vs. transmitted",
        "key"       =>  "icmpv66",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statprotocolicmpv6",
                                    "cntrs" =>  "icmpv6totrxechoreq,icmpv6tottxechoreq")));
    var $icmpv67 = array(
        "name"      =>  "ICMPv6 Echo Reply packets received vs. transmitted",
        "key"       =>  "icmpv67",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statprotocolicmpv6",
                                    "cntrs" =>  "icmpv6totrxechoreply,icmpv6tottxechoreply")));
    var $ssl0 = array(
        "name"      =>  "Front-end SSLv2 vs. SSLv3 vs. TLSv1 Client Auth",
        "key"       =>  "ssl0",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statssl",
                                    "cntrs" =>  "ssltotsslv2clientauthentications,ssltotsslv3clientauthentications,ssltottlsv1clientauthentications")));
    var $ssl1 = array(
        "name"      =>  "Front-end RSA vs. DH Key Exchange",
        "key"       =>  "ssl1",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statssl",
                                    "cntrs" =>  "ssltotrsa512keyexchanges,ssltotrsa1024keyexchanges,ssltotrsa2048keyexchanges,ssltotdh512keyexchanges,ssltotdh1024keyexchanges,ssltotdh2048keyexchanges")));
    var $ssl2 = array(
        "name"      =>  "All Front-end Ciphers",
        "key"       =>  "ssl2",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statssl",
                                    "cntrs" =>  "ssltot40bitrc4ciphers,ssltot56bitrc4ciphers,ssltot64bitrc4ciphers,ssltot128bitrc4ciphers,ssltot40bitdesciphers,ssltot56bitdesciphers,ssltot168bit3desciphers,ssltotcipheraes128,ssltotcipheraes256,ssltot40bitrc2ciphers,ssltot56bitrc2ciphers,ssltot128bitrc2ciphers,ssltotnullciphers")));
    var $ssl3 = array(
        "name"      =>  "Backend RSA vs. DH Key Exchange",
        "key"       =>  "ssl3",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statssl",
                                    "cntrs" =>  "sslbetotrsa512keyexchanges,sslbetotrsa1024keyexchanges,sslbetotrsa2048keyexchanges,sslbetotdh512keyexchanges,sslbetotdh1024keyexchanges,sslbetotdh2048keyexchanges")));
    var $ssl4 = array(
        "name"      =>  "All Backend Ciphers",
        "key"       =>  "ssl4",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statssl",
                                    "cntrs" =>  "sslbetot40bitrc4ciphers,sslbetot56bitrc4ciphers,sslbetot64bitrc4ciphers,sslbetot128bitrc4ciphers,sslbetot40bitdesciphers,sslbetot56bitdesciphers,sslbetot168bit3desciphers,ssltotbkendcipheraes128,ssltotbkendcipheraes256,sslbetot40bitrc2ciphers,sslbetot56bitrc2ciphers,sslbetot128bitrc2ciphers,sslbetotnullciphers")));
    var $hcmp0 = array(
        "name"      =>  "HTTP Compressible (Rx) vs. Compressed (Tx) - bytes",
        "key"       =>  "hcmp0",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statcmp",
                                    "cntrs" =>  "comptotalrxbytes,comptotaltxbytes")));
    var $hcmp1 = array(
        "name"      =>  "HTTP compression ratio vs. Total HTTP compression ratio",
        "key"       =>  "hcmp1",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statcmp",
                                    "cntrs" =>  "compratio,comptotaldatacompressionratio")));
    var $tcmp0 = array(
        "name"      =>  "TCP Compressible (Rx) vs. Compressed (Tx) - bytes",
        "key"       =>  "tcmp0",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statcmp",
                                    "cntrs" =>  "comptcptotalrxbytes,comptcptotaltxbytes")));
    var $tcmp1 = array(
        "name"      =>  "TCP compression ratio vs. Bandwidth saving (%)",
        "key"       =>  "tcmp1",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statcmp",
                                    "cntrs" =>  "comptcpratio,comptcpbandwidthsaving")));
    /*This functionality will come back in future
    var $dcmp0 = array(
        "name"      =>  "Delta Compressible (Rx) vs. Compressed (Tx) - bytes",
        "key"       =>  "dcmp0",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statcmp",
                                    "cntrs" =>  "delcomptcprxbytes,delcomptcptxbytes")));
    var $dcmp1 = array(
        "name"      =>  "Delta compression ratio vs. Bandwidth saving (%)",
        "key"       =>  "dcmp1",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statcmp",
                                    "cntrs" =>  "delcmpratio,delbwsaving")));*/
    var $decmp0 = array(
        "name"      =>  "Compressed (Rx) vs. Decompressed (Tx) - bytes",
        "key"       =>  "decmp0",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statcmp",
                                    "cntrs" =>  "decomptcprxbytes,decomptcptxbytes")));
    var $decmp1 = array(
        "name"      =>  "Decompression ratio vs. Bandwidth saving (%)",
        "key"       =>  "decmp1",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statcmp",
                                    "cntrs" =>  "decomptcpratio,decomptcpbandwidthsaving")));
    var $ic0 = array(
        "name"      =>  "Requests vs. Hits",
        "key"       =>  "ic0",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statcache",
                                    "cntrs" =>  "cachetotrequests,cachetothits")));
    var $ic1 = array(
        "name"      =>  "Hit ratio(%)",
        "key"       =>  "ic1",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statcache",
                                    "cntrs" =>  "cachepercenthit")));
    var $ic2 = array(
        "name"      =>  "Origin bandwidth saved(%)",
        "key"       =>  "ic2",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statcache",
                                    "cntrs" =>  "cachepercentoriginbandwidthsaved")));
    var $ic3 = array(
        "name"      =>  "Bytes served by NetScaler vs. Bytes served by cache",
        "key"       =>  "ic3",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statcache",
                                    "cntrs" =>  "cachetotresponsebytes,cachebytesserved")));
    var $ic4 = array(
        "name"      =>  "Byte hit ratio(%)",
        "key"       =>  "ic4",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statcache",
                                    "cntrs" =>  "cachepercentbytehit")));
    var $ic5 = array(
        "name"      =>  "Flashcache misses vs. hits",
        "key"       =>  "ic5",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statcache",
                                    "cntrs" =>  "cachetotflashcachemisses,cachetotflashcachehits")));
    var $ic6 = array(
        "name"      =>  "Misses vs. Storable misses",
        "key"       =>  "ic6",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statcache",
                                    "cntrs" =>  "cachetotmisses,cachetotstoreablemisses")));
    var $ic7 = array(
        "name"      =>  "Misses vs. Non-storable misses",
        "key"       =>  "ic7",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statcache",
                                    "cntrs" =>  "cachetotmisses,cachetotnonstoreablemisses")));
    var $ic8 = array(
        "name"      =>  "Revalidations vs. Successful revalidations",
        "key"       =>  "ic8",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statcache",
                                    "cntrs" =>  "cachetotrevalidationmiss,cachetotsuccessfulrevalidation")));
    var $ic9 = array(
        "name"      =>  "Inval requests vs. Parameterized inval requests",
        "key"       =>  "ic9",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statcache",
                                    "cntrs" =>  "cachetotinvalidationrequests,cachetotparameterizedinvalidationrequests")));
    var $ic10 = array(
        "name"      =>  "Inval requests vs. Full inval requests",
        "key"       =>  "ic10",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statcache",
                                    "cntrs" =>  "cachetotinvalidationrequests,cachetotnonparameterizedinvalidationrequests")));
    var $ic11 = array(
        "name"      =>  "Parameterized requests vs. Total parameterized hits",
        "key"       =>  "ic11",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statcache",
                                    "cntrs" =>  "cachetotparameterizedrequests,cachetotparameterizedhits")));
    var $ic12 = array(
        "name"      =>  "Parameterized non-304 vs. 304 hits",
        "key"       =>  "ic12",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statcache",
                                    "cntrs" =>  "cachetotparameterizednon304hits,cachetotparameterized304hits")));
    var $ic13 = array(
        "name"      =>  "Parameterized 304 hit ratio(%)",
        "key"       =>  "ic13",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statcache",
                                    "cntrs" =>  "cachepercentparameterized304hits")));
    var $ic14 = array(
        "name"      =>  "Poll every time requests vs. hits",
        "key"       =>  "ic14",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statcache",
                                    "cntrs" =>  "cachetotpetrequests,cachetotpethits")));
    var $ic15 = array(
        "name"      =>  "Poll every time hit ratio(%)",
        "key"       =>  "ic15",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statcache",
                                    "cntrs" =>  "cachepercentpethits")));
    var $ic16 = array(
        "name"      =>  "Cached objects vs. Utilized memory(KB)",
        "key"       =>  "ic16",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statcache",
                                    "cntrs" =>  "cachenumcached,cacheutilizedmemorykb")));
    var $aaa0 = array(
        "name"      =>  "Authentication successes vs. failures",
        "key"       =>  "aaa0",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "stataaa",
                                    "cntrs" =>  "aaaauthsuccess,aaaauthfail")));
    var $aaa1 = array(
        "name"      =>  "HTTP authorization successes vs. failures",
        "key"       =>  "aaa1",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "stataaa",
                                    "cntrs" =>  "aaaauthonlyhttpsuccess,aaaauthonlyhttpfail")));
    var $aaa2 = array(
        "name"      =>  "Non-HTTP authorization successes vs. failures",
        "key"       =>  "aaa2",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "stataaa",
                                    "cntrs" =>  "aaaauthnonhttpsuccess,aaaauthnonhttpfail")));
    var $aaa3 = array(
        "name"      =>  "Current AAA sessions",
        "key"       =>  "aaa3",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "stataaa",
                                    "cntrs" =>  "aaacursessions")));
    var $aaa4 = array(
        "name"      =>  "AAA sessions",
        "key"       =>  "aaa4",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "stataaa",
                                    "cntrs" =>  "aaatotsessions")));

    var $aaa5 = array(
        "name"      =>  "Current ICAOnly sessions",
        "key"       =>  "aaa5",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "stataaa",
                                    "cntrs" =>  "aaacuricasessions")));
    var $aaa6 = array(
        "name"          =>  "Current ICAOnly connections",
        "key"           =>  "aaa6",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "stataaa",
                                    "cntrs" =>  "aaacuricaonlyconn")));
    var $aaa7 = array(
        "name"          =>  "Current ICA (smart access) connections",
        "key"           =>  "aaa7",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "stataaa",
                                    "cntrs" =>  "aaacuricaconn")));
    var $vpn0 = array(
        "name"      =>  "WINS queries received vs. No. of SSLVPN tunnels",
        "key"       =>  "vpn0",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statvpn",
                                    "cntrs" =>  "winsrequesthit,csrequesthit")));
    var $vpn1 = array(
        "name"      =>  "Backend HTTP vs. non-HTTP server probes",
        "key"       =>  "vpn1",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statvpn",
                                    "cntrs" =>  "cshttpprobehit,csnonhttpprobehit")));
    var $appfw0 = array(
        "name"      =>  "Requests",
        "key"       =>  "appfw0",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statappfw",
                                    "cntrs" =>  "appfirewallrequests,appfirewallresponses,appfirewallaborts,appfirewallredirects"),
                              array("id"    =>  2,
                                    "func"  =>  "statappfw",
                                    "cntrs" =>  "appfirewallrequestsRate,appfirewallresponsesRate,appfirewallabortsRate,appfirewallredirectsRate")));
    var $appfw1 = array(
        "name"      =>  "Application Firewall Requests vs. Total Requests",
        "key"       =>  "appfw1",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statappfw;statns",
                                    "cntrs" =>  "appfirewallrequests;httptotrequests"),
                              array("id"    =>  2,
                                    "func"  =>  "statappfw;statns",
                                    "cntrs" =>  "appfirewallrequestsRate;httprequestsRate")));
    var $appfw5 = array(
        "name"      =>  "Request Bytes vs. Response Bytes",
        "key"       =>  "appfw5",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statappfw",
                                    "cntrs" =>  "appfirewallreqbytesRate,appfirewallresbytesRate"),
                              array("id"    =>  2,
                                    "func"  =>  "statappfw",
                                    "cntrs" =>  "appfirewallreqbytes,appfirewallresbytes")));
    var $appfw2 = array(
        "name"      =>  "Summary",
        "key"       =>  "appfw2",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statappfw",
                                    "title" =>  "Total Violations",
                                    "cntrs" =>  "appfirewalltotalviol"),
                              array("id"    =>  2,
                                    "func"  =>  "statappfw",
                                    "title" =>  "Total Violations Rate",
                                    "cntrs" =>  "appfirewallviolRate")));
    var $appfw3 = array(
        "name"      =>  "All Violations",
        "key"       =>  "appfw3",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statappfw",
                                    "title" =>  "Common Violations",
                                    "cntrs" =>  "appfirewallviolstarturl,appfirewallvioldenyurl,appfirewallviolrefererheader,appfirewallviolbufferoverflow,appfirewallviolcookie,appfirewallviolcreditcard"),
                              array("id"    =>  2,
                                    "func"  =>  "statappfw",
                                    "title" =>  "HTML Violations",
                                    "cntrs" =>  "appfirewallviolcsrftag,appfirewallviolxss,appfirewallviolsql,appfirewallviolfieldformat,appfirewallviolfieldconsistency"),
                              array("id"    =>  3,
                                    "func"  =>  "statappfw",
                                    "title" =>  "XML Violations",
                                    "cntrs" =>  "appfirewallviolwellformednessviolations,appfirewallviolxdosviolations,appfirewallviolmsgvalviolations,appfirewallviolwsiviolations,appfirewallviolxmlsqlviolations,appfirewallviolxmlxssviolations,appfirewallviolxmlattachmentviolations,appfirewallviolxmlsoapfaultviolations")));
    var $appfw4 = array(
        "name"      =>  "All Violations Per Profile",
        "key"       =>  "appfw4",
        "elements"  =>  array(array("id"    =>  1,
                                    "func"  =>  "statappfwprofile",
                                    "title" =>  "Common Violations",
                                    "cntrs" =>  "appfirewallviolstarturlperprofile,appfirewallvioldenyurlperprofile,appfirewallviolrefererheaderperprofile,appfirewallviolbufferoverflowperprofile,appfirewallviolcookieperprofile,appfirewallviolcreditcardperprofile"),
                              array("id"    =>  2,
                                    "func"  =>  "statappfwprofile",
                                    "title" =>  "HTML Violations",
                                    "cntrs" =>  "appfirewallviolcsrftagperprofile,appfirewallviolxssperprofile,appfirewallviolsqlperprofile,appfirewallviolfieldformatperprofile,appfirewallviolfieldconsistencyperprofile"),
                              array("id"    =>  3,
                                    "func"  =>  "statappfwprofile",
                                    "title" =>  "XML Violations",
                                    "cntrs" =>  "appfirewallviolwellformednessviolationsperprofile,appfirewallviolxdosviolationsperprofile,appfirewallviolmsgvalviolationsperprofile,appfirewallviolwsiviolationsperprofile,appfirewallviolxmlsqlviolationsperprofile,appfirewallviolxmlxssviolationsperprofile,appfirewallviolxmlattachmentviolationsperprofile,appfirewallviolxmlsoapfaultviolationsperprofile")));
    var $appfw6 = array(
        "name"      =>  "Per Profile Monitoring",
        "key"       =>  "appfw6",
        "elements"  =>  array(
                              array("id"    =>  1,
                                    "func"  =>  "statappfwprofile",
                                    "title" =>  "Profile Usage Rate",
                                    "cntrs" =>  "appfirewallrequestsperprofileRate,appfirewallresponsesperprofileRate,appfirewallreqbytesperprofileRate,appfirewallresbytesperprofileRate",
                                    "graphtype" => BAR_GRAPH),
                             array("id"    =>  2,
                                    "func"  =>  "statappfwprofile",
                                    "title" =>  "Backend Performance",
                                    "cntrs" =>  "appfirewallshortavgresptimeperprofile,appfirewalllongavgresptimeperprofile",
                                    "graphtype" => LINE_GRAPH),
                              array("id"    =>  3,
                                    "func"  =>  "statappfwprofile",
                                    "title" =>  "HTTP Errors Rate",
                                    "cntrs" =>  "appfirewallret4xxperprofileRate,appfirewallret5xxperprofileRate",
                                    "graphtype" => SBAR_GRAPH)));

    //Built-in reports is made up of nodes and leaves
    var $built_in_reports;
    public function __construct()
    {

        require_once(APPPATH . "controllers/common/rapi.php");
        $rapi = new rapi(true);
        if($rapi->is_partiton_configured_and_is_not_default_partition())
        {
            $this->built_in_reports = array(
            array("name"  =>  "System",
            "key"   =>  "sys",
            "nodes" =>  array(
                            array("name"    =>  "HTTP",
                                  "key"     =>  "sys_http",
                                  "nodes"   =>  array($this->http0, $this->http1, $this->http2, $this->http3, $this->http4)),
                            )),
            array("name"  =>  "Network",
            "key"   =>  "nw",
            "nodes" =>  array(array("name"    =>  "TCP",
                                  "key"     =>  "nw_tcp",
                                  "nodes"   =>  array($this->tcp0, $this->tcp1, $this->tcp2)),
                            array("name"    =>  "IP",
                                  "key"     =>  "nw_ip",
                                  "nodes"   =>  array($this->ip0, $this->ip1, $this->ip2)),
                            array("name"    =>  "IPv6",
                                  "key"     =>  "nw_ipv6",
                                  "nodes"   =>  array($this->ipv60, $this->ipv61)),
                            array("name"    =>  "ICMP",
                                  "key"     =>  "nw_icmp",
                                  "nodes"   =>  array($this->icmp0, $this->icmp1)),
                            array("name"    =>  "ICMPv6",
                                  "key"     =>  "nw_icmpv6",
                                  "nodes"   =>  array($this->icmpv60, $this->icmpv61, $this->icmpv62, $this->icmpv63, $this->icmpv64, $this->icmpv65, $this->icmpv66, $this->icmpv67)))),
            array("name"  =>  "SSL",
            "key"   =>  "ssl",
            "nodes" =>  array($this->ssl0, $this->ssl1, $this->ssl2, $this->ssl3, $this->ssl4)),
            array("name"  =>  "Compression",
            "key"   =>  "cmp",
            "nodes" =>  array(array("name"    =>  "HTTP Compression",
                                  "key"     =>  "cmp_hcmp",
                                  "nodes"   =>  array($this->hcmp0, $this->hcmp1)),
                            array("name"    =>  "TCP Compression",
                                  "key"     =>  "cmp_tcp",
                                  "nodes"   =>  array($this->tcmp0, $this->tcmp1)),
                            /*This functionality will come back in future
                            array("name"    =>  "Delta Compression",
                                  "key"     =>  "cmp_dcmp",
                                  "nodes"   =>  array($this->dcmp0, $this->dcmp1)),*/
                            array("name"    =>  "Decompression",
                                  "key"     =>  "cmp_decmp",
                                  "nodes"   =>  array($this->decmp0, $this->decmp1))))
            );
        }
        else if(is_sgw())
        {
            $this->built_in_reports = array(
    array("name"  =>  "System",
          "key"   =>  "sys",
          "nodes" =>  array($this->sys0,
                            array("name"    =>  "HA",
                                  "key"     =>  "sys_ha",
                                  "nodes"   =>  array($this->ha0)))),
    array("name"  =>  "NetScaler Gateway",
          "key"   =>  "vpn",
          "nodes" =>  array(array("name"    =>  "AAA",
                                  "key"     =>  "vpn_aaa",
                                  "nodes"   =>  array($this->aaa0, $this->aaa2, $this->aaa5, $this->aaa6)),
                            $this->vpn1)),
    array("name"  =>  "SSL",
          "key"   =>  "ssl",
          "nodes" =>  array($this->ssl0, $this->ssl1, $this->ssl2, $this->ssl3, $this->ssl4))
    );
}
else if((is_ns() || is_xenmobile()) && is_bw())
{
            $this->built_in_reports = array(
            array("name"  =>  "System",
            "key"   =>  "sys",
            "nodes" =>  array($this->sys0,
                            array("name"    =>  "HTTP",
                                  "key"     =>  "sys_http",
                                  "nodes"   =>  array($this->http0, $this->http1, $this->http2, $this->http3, $this->http4)),
                            array("name"    =>  "HA",
                                  "key"     =>  "sys_ha",
								  "nodes"   =>  array($this->ha0)),
							array("name"    =>  "BW",
                                  "key"     =>  "sys_bw",
                                  "nodes"   =>  array($this->bw0)),
                            array("name"    =>  "Cluster",
                                  "key"     =>  "sys_cl",
                                  "nodes"   =>  array(array("name"    =>  "Cluster Instance",
                                                            "key"     =>  "sys_cl_i",
                                                            "nodes"   =>  array($this->cli0)),
                                                      array("name"    =>  "Cluster Node",
                                                            "key"     =>  "sys_cl_n",
                                                            "nodes"   =>  array($this->cln0)))))),
            array("name"  =>  "Network",
            "key"   =>  "nw",
            "nodes" =>  array(array("name"    =>  "TCP",
                                  "key"     =>  "nw_tcp",
                                  "nodes"   =>  array($this->tcp0, $this->tcp1, $this->tcp2)),
                            array("name"    =>  "IP",
                                  "key"     =>  "nw_ip",
                                  "nodes"   =>  array($this->ip0, $this->ip1, $this->ip2)),
                            array("name"    =>  "IPv6",
                                  "key"     =>  "nw_ipv6",
                                  "nodes"   =>  array($this->ipv60, $this->ipv61)),
                            array("name"    =>  "ICMP",
                                  "key"     =>  "nw_icmp",
                                  "nodes"   =>  array($this->icmp0, $this->icmp1)),
                            array("name"    =>  "ICMPv6",
                                  "key"     =>  "nw_icmpv6",
                                  "nodes"   =>  array($this->icmpv60, $this->icmpv61, $this->icmpv62, $this->icmpv63, $this->icmpv64, $this->icmpv65, $this->icmpv66, $this->icmpv67)))),
            array("name"  =>  "SSL",
            "key"   =>  "ssl",
            "nodes" =>  array($this->ssl0, $this->ssl1, $this->ssl2, $this->ssl3, $this->ssl4)),
            array("name"  =>  "Compression",
            "key"   =>  "cmp",
            "nodes" =>  array(array("name"    =>  "HTTP Compression",
                                  "key"     =>  "cmp_hcmp",
                                  "nodes"   =>  array($this->hcmp0, $this->hcmp1)),
                            array("name"    =>  "TCP Compression",
                                  "key"     =>  "cmp_tcp",
                                  "nodes"   =>  array($this->tcmp0, $this->tcmp1)),
                            /*This functionality will come back in future
                            array("name"    =>  "Delta Compression",
                                  "key"     =>  "cmp_dcmp",
                                  "nodes"   =>  array($this->dcmp0, $this->dcmp1)),*/
                            array("name"    =>  "Decompression",
                                  "key"     =>  "cmp_decmp",
                                  "nodes"   =>  array($this->decmp0, $this->decmp1)))),
            array("name"  =>  "Integrated Cache",
            "key"   =>  "ic",
            "nodes" =>  array($this->ic0, $this->ic1, $this->ic2, $this->ic3, $this->ic4, $this->ic5, $this->ic6, $this->ic7, $this->ic8, $this->ic9, $this->ic10, $this->ic11, $this->ic12, $this->ic13, $this->ic14, $this->ic15, $this->ic16)),
            array("name"  =>  "NetScaler Gateway",
            "key"   =>  "vpn",
            "nodes" =>  array(array("name"    =>  "AAA",
                                  "key"     =>  "vpn_aaa",
                                  "nodes"   =>  array($this->aaa0, $this->aaa1, $this->aaa2, $this->aaa3, $this->aaa4, $this->aaa5, $this->aaa6, $this->aaa7)),
                            $this->vpn0, $this->vpn1)),
            array("name"  =>  "Application Firewall",
            "key"   =>  "appfw",
            "nodes" =>  array($this->appfw6,
                            array("name"    =>  "Traffic",
                                  "key"     =>  "appfw_trafiic",
                                  "nodes"   =>  array($this->appfw0, $this->appfw1, $this->appfw5)),
                            array("name"    =>  "Violations",
                                  "key"     =>  "appfw_violations",
                                  "nodes"   =>  array($this->appfw2,$this->appfw3,$this->appfw4))))
            );
}
else if((is_ns() || is_xenmobile()) && !is_bw())
{
$this->built_in_reports = array(
    array("name"  =>  "System",
          "key"   =>  "sys",
          "nodes" =>  array($this->sys0,
                            array("name"    =>  "HTTP",
                                  "key"     =>  "sys_http",
                                  "nodes"   =>  array($this->http0, $this->http1, $this->http2, $this->http3, $this->http4)),
                            array("name"    =>  "HA",
                                  "key"     =>  "sys_ha",
                                  "nodes"   =>  array($this->ha0)),
                            array("name"    =>  "Cluster",
                                  "key"     =>  "sys_cl",
                                  "nodes"   =>  array(array("name"    =>  "Cluster Instance",
                                                            "key"     =>  "sys_cl_i",
                                                            "nodes"   =>  array($this->cli0)),
                                                      array("name"    =>  "Cluster Node",
                                                            "key"     =>  "sys_cl_n",
                                                            "nodes"   =>  array($this->cln0)))))),
    array("name"  =>  "Network",
          "key"   =>  "nw",
          "nodes" =>  array(array("name"    =>  "TCP",
                                  "key"     =>  "nw_tcp",
                                  "nodes"   =>  array($this->tcp0, $this->tcp1, $this->tcp2)),
                            array("name"    =>  "IP",
                                  "key"     =>  "nw_ip",
                                  "nodes"   =>  array($this->ip0, $this->ip1, $this->ip2)),
                            array("name"    =>  "IPv6",
                                  "key"     =>  "nw_ipv6",
                                  "nodes"   =>  array($this->ipv60, $this->ipv61)),
                            array("name"    =>  "ICMP",
                                  "key"     =>  "nw_icmp",
                                  "nodes"   =>  array($this->icmp0, $this->icmp1)),
                            array("name"    =>  "ICMPv6",
                                  "key"     =>  "nw_icmpv6",
                                  "nodes"   =>  array($this->icmpv60, $this->icmpv61, $this->icmpv62, $this->icmpv63, $this->icmpv64, $this->icmpv65, $this->icmpv66, $this->icmpv67)))),
    array("name"  =>  "SSL",
          "key"   =>  "ssl",
          "nodes" =>  array($this->ssl0, $this->ssl1, $this->ssl2, $this->ssl3, $this->ssl4)),
    array("name"  =>  "Compression",
          "key"   =>  "cmp",
          "nodes" =>  array(array("name"    =>  "HTTP Compression",
                                  "key"     =>  "cmp_hcmp",
                                  "nodes"   =>  array($this->hcmp0, $this->hcmp1)),
                            array("name"    =>  "TCP Compression",
                                  "key"     =>  "cmp_tcp",
                                  "nodes"   =>  array($this->tcmp0, $this->tcmp1)),
                            /*This functionality will come back in future
                            array("name"    =>  "Delta Compression",
                                  "key"     =>  "cmp_dcmp",
                                  "nodes"   =>  array($this->dcmp0, $this->dcmp1)),*/
                            array("name"    =>  "Decompression",
                                  "key"     =>  "cmp_decmp",
                                  "nodes"   =>  array($this->decmp0, $this->decmp1)))),
    array("name"  =>  "Integrated Cache",
          "key"   =>  "ic",
          "nodes" =>  array($this->ic0, $this->ic1, $this->ic2, $this->ic3, $this->ic4, $this->ic5, $this->ic6, $this->ic7, $this->ic8, $this->ic9, $this->ic10, $this->ic11, $this->ic12, $this->ic13, $this->ic14, $this->ic15, $this->ic16)),
    array("name"  =>  "NetScaler Gateway",
          "key"   =>  "vpn",
          "nodes" =>  array(array("name"    =>  "AAA",
                                  "key"     =>  "vpn_aaa",
                                  "nodes"   =>  array($this->aaa0, $this->aaa1, $this->aaa2, $this->aaa3, $this->aaa4, $this->aaa5, $this->aaa6, $this->aaa7)),
                            $this->vpn0, $this->vpn1)),
    array("name"  =>  "Application Firewall",
          "key"   =>  "appfw",
          "nodes" =>  array($this->appfw6,
                            array("name"    =>  "Traffic",
                                  "key"     =>  "appfw_trafiic",
                                  "nodes"   =>  array($this->appfw0, $this->appfw1, $this->appfw5)),
                            array("name"    =>  "Violations",
                                  "key"     =>  "appfw_violations",
                                  "nodes"   =>  array($this->appfw2,$this->appfw3,$this->appfw4))))
);
}

else if(is_agee())
{
$this->built_in_reports = array(
    array("name"  =>  "System",
          "key"   =>  "sys",
          "nodes" =>  array($this->sys0,
                            array("name"    =>  "HA",
                                  "key"     =>  "sys_ha",
                                  "nodes"   =>  array($this->ha0)))),
    array("name"  =>  "Network",
          "key"   =>  "nw",
          "nodes" =>  array(array("name"    =>  "TCP",
                                  "key"     =>  "nw_tcp",
                                  "nodes"   =>  array($this->tcp0, $this->tcp1, $this->tcp2)),
                            array("name"    =>  "IP",
                                  "key"     =>  "nw_ip",
                                  "nodes"   =>  array($this->ip0, $this->ip1, $this->ip2)),
                            array("name"    =>  "ICMP",
                                  "key"     =>  "nw_icmp",
                                  "nodes"   =>  array($this->icmp0, $this->icmp1)))),
    array("name"  =>  "SSL",
          "key"   =>  "ssl",
          "nodes" =>  array($this->ssl0, $this->ssl1, $this->ssl2, $this->ssl3, $this->ssl4)),
    array("name"  =>  "Compression",
          "key"   =>  "cmp",
          "nodes" =>  array(array("name"    =>  "TCP Compression",
                                  "key"     =>  "cmp_tcp",
                                  "nodes"   =>  array($this->tcmp0, $this->tcmp1)),
                            /*This functionality will come back in future
                            array("name"    =>  "Delta Compression",
                                  "key"     =>  "cmp_dcmp",
                                  "nodes"   =>  array($this->dcmp0, $this->dcmp1)),*/
                            array("name"    =>  "Decompression",
                                  "key"     =>  "cmp_decmp",
                                  "nodes"   =>  array($this->decmp0, $this->decmp1)))),
    array("name"  =>  "Integrated Cache",
          "key"   =>  "ic",
          "nodes" =>  array($this->ic0, $this->ic1, $this->ic2, $this->ic3, $this->ic4, $this->ic5, $this->ic6, $this->ic7, $this->ic8, $this->ic9, $this->ic10, $this->ic11, $this->ic12, $this->ic13, $this->ic14, $this->ic15, $this->ic16)),
    array("name"  =>  "NetScaler Gateway",
          "key"   =>  "vpn",
          "nodes" =>  array(array("name"    =>  "AAA",
                                  "key"     =>  "vpn_aaa",
                                  "nodes"   =>  array($this->aaa0, $this->aaa1, $this->aaa2, $this->aaa3, $this->aaa4, $this->aaa5, $this->aaa6, $this->aaa7)),
                            $this->vpn0, $this->vpn1))
);
}
else if(is_ocb() || is_occ())
{
$this->built_in_reports = array(
    array("name"  =>  "System",
          "key"   =>  "sys",
          "nodes" =>  array($this->sys0,
                            array("name"    =>  "HTTP",
                                  "key"     =>  "sys_http",
                                  "nodes"   =>  array($this->http0, $this->http1, $this->http2, $this->http3, $this->http4)),
                            array("name"    =>  "HA",
                                  "key"     =>  "sys_ha",
                                  "nodes"   =>  array($this->ha0)))),
    array("name"  =>  "Network",
          "key"   =>  "nw",
          "nodes" =>  array(array("name"    =>  "TCP",
                                  "key"     =>  "nw_tcp",
                                  "nodes"   =>  array($this->tcp0, $this->tcp1, $this->tcp2)),
                            array("name"    =>  "IP",
                                  "key"     =>  "nw_ip",
                                  "nodes"   =>  array($this->ip0, $this->ip1, $this->ip2)),
                            array("name"    =>  "IPv6",
                                  "key"     =>  "nw_ipv6",
                                  "nodes"   =>  array($this->ipv60, $this->ipv61)),
                            array("name"    =>  "ICMP",
                                  "key"     =>  "nw_icmp",
                                  "nodes"   =>  array($this->icmp0, $this->icmp1)),
                            array("name"    =>  "ICMPv6",
                                  "key"     =>  "nw_icmpv6",
                                  "nodes"   =>  array($this->icmpv60, $this->icmpv61, $this->icmpv62, $this->icmpv63, $this->icmpv64, $this->icmpv65, $this->icmpv66, $this->icmpv67)))),
    array("name"  =>  "SSL",
          "key"   =>  "ssl",
          "nodes" =>  array($this->ssl0, $this->ssl1, $this->ssl2, $this->ssl3, $this->ssl4))
);
}
else if(is_bytemobile())
{
$this->built_in_reports = array(
    array("name"  =>  "System",
          "key"   =>  "sys",
          "nodes" =>  array($this->sys0,
                            array("name"    =>  "HTTP",
                                  "key"     =>  "sys_http",
                                  "nodes"   =>  array($this->http0, $this->http1, $this->http2, $this->http3, $this->http4)),
                            array("name"    =>  "HA",
                                  "key"     =>  "sys_ha",
                                  "nodes"   =>  array($this->ha0)),
                            array("name"    =>  "Cluster",
                                  "key"     =>  "sys_cl",
                                  "nodes"   =>  array(array("name"    =>  "Cluster Instance",
                                                            "key"     =>  "sys_cl_i",
                                                            "nodes"   =>  array($this->cli0)),
                                                      array("name"    =>  "Cluster Node",
                                                            "key"     =>  "sys_cl_n",
                                                            "nodes"   =>  array($this->cln0)))))),
    array("name"  =>  "Network",
          "key"   =>  "nw",
          "nodes" =>  array(array("name"    =>  "TCP",
                                  "key"     =>  "nw_tcp",
                                  "nodes"   =>  array($this->tcp0, $this->tcp1, $this->tcp2)),
                            array("name"    =>  "IP",
                                  "key"     =>  "nw_ip",
                                  "nodes"   =>  array($this->ip0, $this->ip1, $this->ip2)),
                            array("name"    =>  "IPv6",
                                  "key"     =>  "nw_ipv6",
                                  "nodes"   =>  array($this->ipv60, $this->ipv61)),
                            array("name"    =>  "ICMP",
                                  "key"     =>  "nw_icmp",
                                  "nodes"   =>  array($this->icmp0, $this->icmp1)),
                            array("name"    =>  "ICMPv6",
                                  "key"     =>  "nw_icmpv6",
                                  "nodes"   =>  array($this->icmpv60, $this->icmpv61, $this->icmpv62, $this->icmpv63, $this->icmpv64, $this->icmpv65, $this->icmpv66, $this->icmpv67)))),
    array("name"  =>  "SSL",
          "key"   =>  "ssl",
          "nodes" =>  array($this->ssl0, $this->ssl1, $this->ssl2, $this->ssl3, $this->ssl4)),
    array("name"  =>  "Compression",
          "key"   =>  "cmp",
          "nodes" =>  array(array("name"    =>  "HTTP Compression",
                                  "key"     =>  "cmp_hcmp",
                                  "nodes"   =>  array($this->hcmp0, $this->hcmp1)),
                            array("name"    =>  "TCP Compression",
                                  "key"     =>  "cmp_tcp",
                                  "nodes"   =>  array($this->tcmp0, $this->tcmp1)),
                            /*This functionality will come back in future
                            array("name"    =>  "Delta Compression",
                                  "key"     =>  "cmp_dcmp",
                                  "nodes"   =>  array($this->dcmp0, $this->dcmp1)),*/
                            array("name"    =>  "Decompression",
                                  "key"     =>  "cmp_decmp",
                                  "nodes"   =>  array($this->decmp0, $this->decmp1)))),
    array("name"  =>  "NetScaler Gateway",
          "key"   =>  "vpn",
          "nodes" =>  array(array("name"    =>  "AAA",
                                  "key"     =>  "vpn_aaa",
                                  "nodes"   =>  array($this->aaa0, $this->aaa1, $this->aaa2, $this->aaa3, $this->aaa4, $this->aaa5, $this->aaa6, $this->aaa7)),
                            $this->vpn0, $this->vpn1))
);
}
    }

    public function get_built_in_reports()
    {
        return $this->built_in_reports;
    }

    public static function get_default_report_name()
    {
        require_once(APPPATH . "controllers/common/rapi.php");
        $rapi = new rapi(true);
        if($rapi->is_partiton_configured_and_is_not_default_partition())
        {
            return "http0";
        }
        
        return is_agee() ? "aaa0" : "sys0";
    }

    public function get_default_report($name, $id)
    {
        return  array(
                    "name"      =>  $name,
                    "elements"  =>  array(
                        array(
                            "id"    =>  $id,
                            "func"  =>  "statns",
                            "cntrs" =>  "cpuusagepcnt",
                            "alt_expr"  =>  "   if(is_default_data_source(\$ds))
                                                {
                                                    if(is_vmpe_mode())
                                                        return \"pktcpuusagepcnt\";
                                                }
                                                else
                                                {
                                                    if(!ns_empty(\$cores))
                                                        return \"pktcpuusagepcnt\";
                                                }
                                                return (\$numcpus > 1) ? \"rescpuusagepcnt\" : \"cpuusagepcnt\";"
                        ),
                    ),
                );
    }

    public function get_report($rep_name)
    {
        try
        {
            $prop = new ReflectionProperty('report_definitions', $rep_name);
            return $prop->getValue($this);
        }
        catch(Exception $e)
        {
            return null;
        }
    }

    public static function get_report_last($report)
    {
        if(isset($_COOKIE['stst'], $_SESSION['rep_last']) && ($_COOKIE['stst'] == "stst"))
            return $_SESSION['rep_last'];
        if($report != null && isset($report['last']))
            return $report['last'];
        return LAST_DEFAULT_VALUE;
    }

    public static function get_report_unit($report)
    {
        if(isset($_COOKIE['stst'], $_SESSION['rep_unit']) && ($_COOKIE['stst'] == "stst"))
            return $_SESSION['rep_unit'];
        if($report != null && isset($report['unit']))
            return $report['unit'];
        return UNIT_DEFAULT_VALUE;
    }

    public static function get_report_stime($report)
    {
        if(isset($_COOKIE['stst'], $_SESSION['rep_stime']) && ($_COOKIE['stst'] == "stst"))
            return $_SESSION['rep_stime'];
        if($report != null && isset($report['stime']))
            return $report['stime'];
        return "";
    }

    public static function get_report_etime($report)
    {
        if(isset($_COOKIE['stst'], $_SESSION['rep_etime']) && ($_COOKIE['stst'] == "stst"))
            return $_SESSION['rep_etime'];
        if($report != null && isset($report['etime']))
            return $report['etime'];
        return "";
    }

    public static function get_report_element_graph_type($element)
    {
        if($element != null && isset($element['graphtype']))
            return $element['graphtype'];
        return GRAPH_TYPE_DEFAULT_VALUE;
    }

    public static function get_report_name($report)
    {
        if($report != null && isset($report['name']))
            return $report['name'];
        return "";
    }

    public static function get_report_func($report)
    {
        if($report != null && isset($report['func']))
            return $report['func'];
        return "ns";
    }

    public function get_report_functions(&$report)
    {
        $functions = array();
        //func could be something like "protocoltcp;cache;cmp". Prepend "stat" to each of them
        foreach($report['elements'] as &$report_element)
        {
            $temp_func = "";
            $func = explode(";", self::get_report_func($report_element));
            foreach($func as $f)
                $temp_func .= (!preg_match("/^stat/", $f) ? "stat" . $f : $f) . ";";
            $report_element['func'] = preg_replace("/;$/", "", $temp_func);
            $functions[$report_element['id']] = $report_element['func'];
        }
        return $functions;
    }
}
?>
