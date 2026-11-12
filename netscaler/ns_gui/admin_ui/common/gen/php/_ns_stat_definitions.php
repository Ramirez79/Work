<?php if(!defined("BASEPATH")) exit("No direct script access allowed");
class _ns_stat_definitions
{
	var $feature = Array (
		"NSKF1_WEBLOG_BIT_POS"  => "WL",
		"NSKF1_SURGEPROTECTION_BIT_POS"  => "SP",
		"NSKF1_LOADBALANCE_BIT_POS"  => "LB",
		"NSKF1_CONTENTSWITCH_BIT_POS"  => "CS",
		"NSKF1_CACHEREDIRECT_BIT_POS"  => "CR",
		"NSKF1_IOH_BIT_POS"  => "SC",
		"NSKF1_CMP_BIT_POS"  => "CMP",
		"NSKF1_DELTA_CMP_BIT_POS"  => "DELTA",
		"NSKF1_PQ_BIT_POS"  => "PQ",
		"NSKF1_SSL_BIT_POS"  => "SSL",
		"NSKF1_GSLB_BIT_POS"  => "GSLB",
		"NSKF1_GSLB_PROXM_BIT_POS"  => "GSLBP",
		"NSKF1_DOS_BIT_POS"  => "HDOSP",
		"NSKF1_ROUTING_BIT_POS"  => "Routing",
		"NSKF1_CONTENTFILTER_BIT_POS"  => "CF",
		"NSKF2_TCACHE_BIT_POS"  => "ContentAccelerator",
		"NSKF1_CACHE_BIT_POS"  => "IC",
		"NSKF1_SSLVPN_BIT_POS"  => "SSLVPN",
		"NSKF1_AAA_BIT_POS"  => "AAA",
		"NSKF1_OSPF_BIT_POS"  => "OSPF",
		"NSKF1_RIP_BIT_POS"  => "RIP",
		"NSKF1_BGP_BIT_POS"  => "BGP",
		"NSKF1_REWRITE_BIT_POS"  => "REWRITE",
		"NSKF1_IPV6_BIT_POS"  => "IPv6PT",
		"NSKF1_APPFIREWALL_BIT_POS"  => "AppFw",
		"NSKF1_RESPONDER_BIT_POS"  => "RESPONDER",
		"NSK_SSLVPNF1_AGEE"  => "AGEE",
		"NSK_NSXENF1"  => "NSXN",
		"NSKF1_HTMLINJECTION_BIT_POS"  => "HTMLInjection",
		"NSKF1_NSPUSH_BIT_POS"  => "push",
		"NSKF2_WIONNS_BIT_POS"  => "WIonNS",
		"NSKF2_APPFLOW_BIT_POS"  => "AppFlow",
		"NSKF2_CLOUDB_BIT_POS"  => "CloudBridge",
		"NSK_CLOUDBF1"  => "CloudBridgeAppliance",
		"NSK_CLOUDEF1"  => "CloudExtenderAppliance",
		"NSKF2_ISIS_BIT_POS"  => "ISIS",
		"NSKF2_CLUSTER_BIT_POS"  => "Cluster",
		"NSKF2_CALLHOME_BIT_POS"  => "CH",
		"NSKF2_APPQOS_BIT_POS"  => "AppQoE",
		"NSKF2_APPFLOWICA_BIT_POS"  => "APPFLOWICA",
		"NSKF2_STDLIC_BIT_POS"  => "isStandardLic",
		"NSKF2_ENTLIC_BIT_POS"  => "isEnterpriseLic",
		"NSKF2_PLTLIC_BIT_POS"  => "isPlatinumLic",
		"NSKF2_SGWY_BIT_POS"  => "isSGwyLic",
		"NSKF2_RISE_BIT_POS"  => "RISE",
		"NSKF2_DISK_BIT_POS"  => "DiskCaching",
		"NSKF2_VPATH_BIT_POS"  => "vPath",
		"NSKF2_CONTOPT_BIT_POS"  => "FEO",
		"NSKF2_LSN_BIT_POS"  => "LSN",
		"NSKF2_RDP_PROXY_BIT_POS"  => "RDPProxy",
		"NSKF2_REPUTATION_BIT_POS"  => "Rep",
	);
    var $cmds = Array(
"statservice" => Array(
	"cmddispname"		=>	"statservice",
	"enable"            =>      "1",
	"cmdname"		=>	"statservice",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"throughput" => Array(
				"cntrname"	=>	"throughput",
				"cntrdescr"	=>	"Throughput (Mbps)",
				"tooltip"	=>	"Number of bytes received or sent by this service (Mbps).",
			),
			"throughputrate" => array (
				"cntrname"	=>	"throughputrate",
				"cntrdescr"	=>	"",
			),
			"avgsvrttfb" => Array(
				"cntrname"	=>	"avgsvrttfb",
				"cntrdescr"	=>	"Average server TTFB",
				"tooltip"	=>	"Average TTFB between the NetScaler appliance and the server.TTFB is the time interval between sending the request packet to a service and receiving the first response from the service",
			),
			"primaryipaddress" => Array(
				"cntrname"	=>	"primaryipaddress",
				"cntrdescr"	=>	"IP address",
				"tooltip"	=>	"The IP address on which the service is running.",
				"type"		=>	"NS_VALIPSTR",
			),
			"primaryport" => Array(
				"cntrname"	=>	"primaryport",
				"cntrdescr"	=>	"Port",
				"tooltip"	=>	"The port on which the service is running.",
				"type"		=>	"NS_VALPORT",
			),
			"servicetype" => Array(
				"cntrname"	=>	"servicetype",
				"cntrdescr"	=>	"Service type",
				"tooltip"	=>	"The service type of this service.Possible values are ADNS, DNS, MYSQL, RTSP, SSL_DIAMETER, ADNS_TCP, DNS_TCP, NNTP, SIP_UDP, SSL_TCP, ANY, FTP, RADIUS, SNMP, TCP, DHCPRA, HTTP, RDP, SSL, TFTP, DIAMETER, MSSQL, RPCSVR, SSL_BRIDGE, UDP",
				"type"		=>	"NS_VALENUM",
			),
			"state" => Array(
				"cntrname"	=>	"state",
				"cntrdescr"	=>	"State",
				"tooltip"	=>	"Current state of the server. Possible values are UP, DOWN, UNKNOWN, OFS(Out of Service), TROFS(Transition Out of Service), TROFS_DOWN(Down When going Out of Service)",
				"type"		=>	"NS_VALENUM",
			),
			"totalrequests" => Array(
				"cntrname"	=>	"totalrequests",
				"cntrdescr"	=>	"Requests",
				"tooltip"	=>	"Total number of requests received on this service or virtual server. (This applies to HTTP/SSL services and servers.)",
			),
			"requestsrate" => array (
				"cntrname"	=>	"requestsrate",
				"cntrdescr"	=>	"",
			),
			"totalresponses" => Array(
				"cntrname"	=>	"totalresponses",
				"cntrdescr"	=>	"Responses",
				"tooltip"	=>	"Number of responses received on this service or virtual server. (This applies to HTTP/SSL services and servers.)",
			),
			"responsesrate" => array (
				"cntrname"	=>	"responsesrate",
				"cntrdescr"	=>	"",
			),
			"totalrequestbytes" => Array(
				"cntrname"	=>	"totalrequestbytes",
				"cntrdescr"	=>	"Request bytes",
				"tooltip"	=>	"Total number of request bytes received on this service or virtual server.",
			),
			"requestbytesrate" => array (
				"cntrname"	=>	"requestbytesrate",
				"cntrdescr"	=>	"",
			),
			"totalresponsebytes" => Array(
				"cntrname"	=>	"totalresponsebytes",
				"cntrdescr"	=>	"Response bytes",
				"tooltip"	=>	"Number of response bytes received by this service or virtual server.",
			),
			"responsebytesrate" => array (
				"cntrname"	=>	"responsebytesrate",
				"cntrdescr"	=>	"",
			),
			"curclntconnections" => Array(
				"cntrname"	=>	"curclntconnections",
				"cntrdescr"	=>	"Current client connections",
				"tooltip"	=>	"Number of current client connections.",
			),
			"surgecount" => Array(
				"cntrname"	=>	"surgecount",
				"cntrdescr"	=>	"Requests in surge queue",
				"tooltip"	=>	"Number of requests in the surge queue.",
			),
			"cursrvrconnections" => Array(
				"cntrname"	=>	"cursrvrconnections",
				"cntrdescr"	=>	"Current server connections",
				"tooltip"	=>	"Number of current connections to the actual servers behind the virtual server.",
			),
			"svrestablishedconn" => Array(
				"cntrname"	=>	"svrestablishedconn",
				"cntrdescr"	=>	"Current Server Est connections",
				"tooltip"	=>	"Number of server connections in ESTABLISHED state.",
			),
			"curreusepool" => Array(
				"cntrname"	=>	"curreusepool",
				"cntrdescr"	=>	"Connections in reuse pool",
				"tooltip"	=>	"Number of requests in the idle queue/reuse pool.",
			),
			"maxclients" => Array(
				"cntrname"	=>	"maxclients",
				"cntrdescr"	=>	"Maximum server connections",
				"tooltip"	=>	"Maximum open connections allowed on this service.",
			),
			"curload" => Array(
				"cntrname"	=>	"curload",
				"cntrdescr"	=>	"Current load on the service",
				"tooltip"	=>	"Load on the service that is calculated from the bound load based monitor.",
			),
			"curtflags" => Array(
				"cntrname"	=>	"curtflags",
				"cntrdescr"	=>	"Current flags on the service",
				"tooltip"	=>	"Current flags on the service for internal use in display handlers.",
			),
			"vsvrservicehits" => Array(
				"cntrname"	=>	"vsvrservicehits",
				"cntrdescr"	=>	"Service hits",
				"tooltip"	=>	"Number of times that the service has been provided.",
			),
			"vsvrservicehitsrate" => array (
				"cntrname"	=>	"vsvrservicehitsrate",
				"cntrdescr"	=>	"",
			),
			"activetransactions" => Array(
				"cntrname"	=>	"activetransactions",
				"cntrdescr"	=>	"ActvTrans",
				"tooltip"	=>	"Number of active transactions handled by this service. (Including those in the surge queue.)<br><br>    Active Transaction means number of transactions currently served by the server including those waiting in the SurgeQ",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Service Summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("primaryipaddress","primaryport","servicetype","state",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Service Stats:",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("totalrequests","totalresponses","totalrequestbytes","totalresponsebytes","curclntconnections","surgecount","cursrvrconnections","svrestablishedconn","curreusepool","maxclients","avgsvrttfb","curload",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Service(s) Summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("primaryipaddress","primaryport","servicetype","state","requestsrate",),
			),
			Array(
				"dhID"	=>	4,
				"title"	=>	"Bound Service(s) Summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"subent",
				"counters"	=>	array("primaryipaddress","primaryport","servicetype","state","vsvrservicehits","vsvrservicehitsrate","totalrequests","requestsrate","totalresponses","responsesrate","throughput","curclntconnections","surgecount","cursrvrconnections","curreusepool","maxclients","activetransactions","avgsvrttfb","curload",),
			),
		),
),


"statservicegroup" => Array(
	"cmddispname"		=>	"statservicegroup",
	"enable"            =>      "1",
	"cmdname"		=>	"statservicegroup",
	"cmddescr"	=>	"     ",
	"bindings"	=>	1,
	"args"	=>
		Array (
			"servicegroupname" => Array(
				"argname"		=>	"servicegroupname",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "servicegroupname",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"state" => Array(
				"cntrname"	=>	"state",
				"cntrdescr"	=>	"State",
				"tooltip"	=>	"Current state of the server. Possible values are UP, DOWN, UNKNOWN, OFS(Out of Service), TROFS(Transition Out of Service), TROFS_DOWN(Down When going Out of Service)",
				"type"		=>	"NS_VALENUM",
			),
			"servicetype" => Array(
				"cntrname"	=>	"servicetype",
				"cntrdescr"	=>	"Service type",
				"tooltip"	=>	"The service type of this service.Possible values are ADNS, DNS, MYSQL, RTSP, SSL_DIAMETER, ADNS_TCP, DNS_TCP, NNTP, SIP_UDP, SSL_TCP, ANY, FTP, RADIUS, SNMP, TCP, DHCPRA, HTTP, RDP, SSL, TFTP, DIAMETER, MSSQL, RPCSVR, SSL_BRIDGE, UDP",
				"type"		=>	"NS_VALENUM",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Service group Summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("servicetype","state",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Service group(s) Summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("servicetype","state",),
			),
		),
),


"statservicegroupmember" => Array(
	"cmddispname"		=>	"statservicegroupmember",
	"cmdname"		=>	"statservicegroupmember",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"servicegroupname" => Array(
				"argname"		=>	"servicegroupname",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"ip" => Array(
				"argname"		=>	"ip",
				"valtype"		=>	"NS_VALIPV46",
				"argdescr"	=>	"",
			),
			"servername" => Array(
				"argname"		=>	"servername",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"port" => Array(
				"argname"		=>	"port",
				"valtype"		=>	"NS_VALPORT",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "servicegroupname",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"avgsvrttfb" => Array(
				"cntrname"	=>	"avgsvrttfb",
				"cntrdescr"	=>	"Average server TTFB",
				"tooltip"	=>	"Average TTFB between the NetScaler appliance and the server.TTFB is the time interval between sending the request packet to a service and receiving the first response from the service",
			),
			"primaryipaddress" => Array(
				"cntrname"	=>	"primaryipaddress",
				"cntrdescr"	=>	"IP address",
				"tooltip"	=>	"The IP address on which the service is running.",
				"type"		=>	"NS_VALIPSTR",
			),
			"primaryport" => Array(
				"cntrname"	=>	"primaryport",
				"cntrdescr"	=>	"Port",
				"tooltip"	=>	"The port on which the service is running.",
				"type"		=>	"NS_VALPORT",
			),
			"servicetype" => Array(
				"cntrname"	=>	"servicetype",
				"cntrdescr"	=>	"Service type",
				"tooltip"	=>	"The service type of this service.Possible values are ADNS, DNS, MYSQL, RTSP, SSL_DIAMETER, ADNS_TCP, DNS_TCP, NNTP, SIP_UDP, SSL_TCP, ANY, FTP, RADIUS, SNMP, TCP, DHCPRA, HTTP, RDP, SSL, TFTP, DIAMETER, MSSQL, RPCSVR, SSL_BRIDGE, UDP",
				"type"		=>	"NS_VALENUM",
			),
			"state" => Array(
				"cntrname"	=>	"state",
				"cntrdescr"	=>	"State",
				"tooltip"	=>	"Current state of the server. Possible values are UP, DOWN, UNKNOWN, OFS(Out of Service), TROFS(Transition Out of Service), TROFS_DOWN(Down When going Out of Service)",
				"type"		=>	"NS_VALENUM",
			),
			"totalrequests" => Array(
				"cntrname"	=>	"totalrequests",
				"cntrdescr"	=>	"Requests",
				"tooltip"	=>	"Total number of requests received on this service or virtual server. (This applies to HTTP/SSL services and servers.)",
			),
			"requestsrate" => array (
				"cntrname"	=>	"requestsrate",
				"cntrdescr"	=>	"",
			),
			"totalresponses" => Array(
				"cntrname"	=>	"totalresponses",
				"cntrdescr"	=>	"Responses",
				"tooltip"	=>	"Number of responses received on this service or virtual server. (This applies to HTTP/SSL services and servers.)",
			),
			"responsesrate" => array (
				"cntrname"	=>	"responsesrate",
				"cntrdescr"	=>	"",
			),
			"totalrequestbytes" => Array(
				"cntrname"	=>	"totalrequestbytes",
				"cntrdescr"	=>	"Request bytes",
				"tooltip"	=>	"Total number of request bytes received on this service or virtual server.",
			),
			"requestbytesrate" => array (
				"cntrname"	=>	"requestbytesrate",
				"cntrdescr"	=>	"",
			),
			"totalresponsebytes" => Array(
				"cntrname"	=>	"totalresponsebytes",
				"cntrdescr"	=>	"Response bytes",
				"tooltip"	=>	"Number of response bytes received by this service or virtual server.",
			),
			"responsebytesrate" => array (
				"cntrname"	=>	"responsebytesrate",
				"cntrdescr"	=>	"",
			),
			"curclntconnections" => Array(
				"cntrname"	=>	"curclntconnections",
				"cntrdescr"	=>	"Current client connections",
				"tooltip"	=>	"Number of current client connections.",
			),
			"surgecount" => Array(
				"cntrname"	=>	"surgecount",
				"cntrdescr"	=>	"Requests in surge queue",
				"tooltip"	=>	"Number of requests in the surge queue.",
			),
			"cursrvrconnections" => Array(
				"cntrname"	=>	"cursrvrconnections",
				"cntrdescr"	=>	"Current server connections",
				"tooltip"	=>	"Number of current connections to the actual servers behind the virtual server.",
			),
			"svrestablishedconn" => Array(
				"cntrname"	=>	"svrestablishedconn",
				"cntrdescr"	=>	"Current Server Est connections",
				"tooltip"	=>	"Number of server connections in ESTABLISHED state.",
			),
			"curreusepool" => Array(
				"cntrname"	=>	"curreusepool",
				"cntrdescr"	=>	"Connections in reuse pool",
				"tooltip"	=>	"Number of requests in the idle queue/reuse pool.",
			),
			"maxclients" => Array(
				"cntrname"	=>	"maxclients",
				"cntrdescr"	=>	"Maximum server connections",
				"tooltip"	=>	"Maximum open connections allowed on this service.",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Service Group Member Summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("primaryipaddress","primaryport","servicetype","state",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Service Group Member Stats:",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("totalrequests","totalresponses","totalrequestbytes","totalresponsebytes","curclntconnections","surgecount","cursrvrconnections","svrestablishedconn","curreusepool","maxclients","avgsvrttfb",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Bound Service Group Member(s) Summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"subent",
				"counters"	=>	array("primaryipaddress","primaryport","servicetype","state","requestsrate","responsesrate","requestbytesrate","responsebytesrate","curclntconnections","cursrvrconnections","maxclients","surgecount","curreusepool","avgsvrttfb",),
			),
		),
),


"stataaa" => Array(
	"cmddispname"		=>	"stataaa",
	"cmdname"		=>	"stataaa",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"aaaauthsuccess" => Array(
				"cntrname"	=>	"aaaauthsuccess",
				"cntrdescr"	=>	"Authentication successes",
				"tooltip"	=>	"Count of authentication successes.",
			),
			"aaaauthsuccessrate" => array (
				"cntrname"	=>	"aaaauthsuccessrate",
				"cntrdescr"	=>	"",
			),
			"aaaauthfail" => Array(
				"cntrname"	=>	"aaaauthfail",
				"cntrdescr"	=>	"Authentication failures",
				"tooltip"	=>	"Count of authentication failures.",
			),
			"aaaauthfailrate" => array (
				"cntrname"	=>	"aaaauthfailrate",
				"cntrdescr"	=>	"",
			),
			"aaaauthonlyhttpsuccess" => Array(
				"cntrname"	=>	"aaaauthonlyhttpsuccess",
				"cntrdescr"	=>	"HTTP authorization successes",
				"tooltip"	=>	"Count of HTTP connections that succeeded authorization.",
			),
			"aaaauthonlyhttpsuccessrate" => array (
				"cntrname"	=>	"aaaauthonlyhttpsuccessrate",
				"cntrdescr"	=>	"",
			),
			"aaaauthonlyhttpfail" => Array(
				"cntrname"	=>	"aaaauthonlyhttpfail",
				"cntrdescr"	=>	"HTTP authorization failures",
				"tooltip"	=>	"Count of HTTP connections that failed authorization.",
			),
			"aaaauthonlyhttpfailrate" => array (
				"cntrname"	=>	"aaaauthonlyhttpfailrate",
				"cntrdescr"	=>	"",
			),
			"aaaauthnonhttpsuccess" => Array(
				"cntrname"	=>	"aaaauthnonhttpsuccess",
				"cntrdescr"	=>	"Non HTTP authorization successes",
				"tooltip"	=>	"Count of non HTTP connections that succeeded authorization.",
			),
			"aaaauthnonhttpsuccessrate" => array (
				"cntrname"	=>	"aaaauthnonhttpsuccessrate",
				"cntrdescr"	=>	"",
			),
			"aaaauthnonhttpfail" => Array(
				"cntrname"	=>	"aaaauthnonhttpfail",
				"cntrdescr"	=>	"Non HTTP authorization failures",
				"tooltip"	=>	"Count of non HTTP connections that failed authorization.",
			),
			"aaaauthnonhttpfailrate" => array (
				"cntrname"	=>	"aaaauthnonhttpfailrate",
				"cntrdescr"	=>	"",
			),
			"aaacursessions" => Array(
				"cntrname"	=>	"aaacursessions",
				"cntrdescr"	=>	"Current AAA sessions",
				"tooltip"	=>	"Count of current AAA sessions.",
			),
			"aaacursessionsrate" => array (
				"cntrname"	=>	"aaacursessionsrate",
				"cntrdescr"	=>	"",
			),
			"aaatotsessions" => Array(
				"cntrname"	=>	"aaatotsessions",
				"cntrdescr"	=>	"Total AAA sessions",
				"tooltip"	=>	"Count of all AAA sessions.",
			),
			"aaasessionsrate" => array (
				"cntrname"	=>	"aaasessionsrate",
				"cntrdescr"	=>	"",
			),
			"aaatotsessiontimeout" => Array(
				"cntrname"	=>	"aaatotsessiontimeout",
				"cntrdescr"	=>	"Timed out AAA sessions",
				"tooltip"	=>	"Count of AAA sessions that have timed out.",
			),
			"aaasessiontimeoutrate" => array (
				"cntrname"	=>	"aaasessiontimeoutrate",
				"cntrdescr"	=>	"",
			),
			"aaacuricasessions" => Array(
				"cntrname"	=>	"aaacuricasessions",
				"cntrdescr"	=>	"Current ICAOnly sessions",
				"tooltip"	=>	"Count of current ICA only sessions.",
			),
			"aaacuricasessionsrate" => array (
				"cntrname"	=>	"aaacuricasessionsrate",
				"cntrdescr"	=>	"",
			),
			"aaacuricaonlyconn" => Array(
				"cntrname"	=>	"aaacuricaonlyconn",
				"cntrdescr"	=>	"Current ICAOnly Conn",
				"tooltip"	=>	"Count of current ICA only connections.",
			),
			"aaacuricaonlyconnrate" => array (
				"cntrname"	=>	"aaacuricaonlyconnrate",
				"cntrdescr"	=>	"",
			),
			"aaacuricaconn" => Array(
				"cntrname"	=>	"aaacuricaconn",
				"cntrdescr"	=>	"Current ICA (Smart Access) Conn",
				"tooltip"	=>	"Count of current ICA connections.",
			),
			"aaacuricaconnrate" => array (
				"cntrname"	=>	"aaacuricaconnrate",
				"cntrdescr"	=>	"",
			),
			"aaacurtmsessions" => Array(
				"cntrname"	=>	"aaacurtmsessions",
				"cntrdescr"	=>	"Current TM sessions",
				"tooltip"	=>	"Count of current AAATM sessions.",
			),
			"aaacurtmsessionsrate" => array (
				"cntrname"	=>	"aaacurtmsessionsrate",
				"cntrdescr"	=>	"",
			),
			"aaatottmsessions" => Array(
				"cntrname"	=>	"aaatottmsessions",
				"cntrdescr"	=>	"TM sessions",
				"tooltip"	=>	"Count of all AAATM sessions.",
			),
			"aaatmsessionsrate" => array (
				"cntrname"	=>	"aaatmsessionsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("aaaauthsuccess","aaaauthfail","aaaauthonlyhttpsuccess","aaaauthonlyhttpfail","aaaauthnonhttpsuccess","aaaauthnonhttpfail","aaacursessions","aaatotsessions","aaatotsessiontimeout","aaacuricasessions","aaacuricaonlyconn","aaacuricaconn","aaacurtmsessions","aaatottmsessions",),
				"charts"	=>	array(	array("aaa_authentication",),
								array("aaa_http_authorization",),
								array("aaa_non_http_authorization",),
								),
			),
		),
),


"statappflow" => Array(
	"cmddispname"		=>	"statappflow",
	"cmdname"		=>	"statappflow",
	"license"		=>	Array("NSKF2_APPFLOW_BIT_POS","NSKF2_APPFLOW_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"appflowtotaltxoctets" => Array(
				"cntrname"	=>	"appflowtotaltxoctets",
				"cntrdescr"	=>	"AppFlow octets transmitted",
				"tooltip"	=>	"The total number of AppFlow (IPFIX) octets that the NetScaler has transmitted.",
			),
			"appflowtxoctetsrate" => array (
				"cntrname"	=>	"appflowtxoctetsrate",
				"cntrdescr"	=>	"",
			),
			"appflowtotalflows" => Array(
				"cntrname"	=>	"appflowtotalflows",
				"cntrdescr"	=>	"AppFlow flows transmitted",
				"tooltip"	=>	"The total number of AppFlow (IPFIX) flows that the NetScaler has transmitted.",
			),
			"appflowflowsrate" => array (
				"cntrname"	=>	"appflowflowsrate",
				"cntrdescr"	=>	"",
			),
			"appflowtotaltxmessagess" => Array(
				"cntrname"	=>	"appflowtotaltxmessagess",
				"cntrdescr"	=>	"AppFlow messages transmitted",
				"tooltip"	=>	"The total number of AppFlow (IPFIX) messages that the NetScaler has transmitted.",
			),
			"appflowtxmessagessrate" => array (
				"cntrname"	=>	"appflowtxmessagessrate",
				"cntrdescr"	=>	"",
			),
			"appflowtotalignoctets" => Array(
				"cntrname"	=>	"appflowtotalignoctets",
				"cntrdescr"	=>	"Octets ignored for AppFlow",
				"tooltip"	=>	"The total number of octets that the NetScaler has ignored for AppFlow (IPFIX).",
			),
			"appflowignoctetsrate" => array (
				"cntrname"	=>	"appflowignoctetsrate",
				"cntrdescr"	=>	"",
			),
			"appflowtotalignpacketss" => Array(
				"cntrname"	=>	"appflowtotalignpacketss",
				"cntrdescr"	=>	"Packets ignored for AppFlow",
				"tooltip"	=>	"The total number of packets that the NetScaler has ignored for AppFlow (IPFIX).",
			),
			"appflowignpacketssrate" => array (
				"cntrname"	=>	"appflowignpacketssrate",
				"cntrdescr"	=>	"",
			),
			"appflowtotalnotxoctets" => Array(
				"cntrname"	=>	"appflowtotalnotxoctets",
				"cntrdescr"	=>	"AppFlow octets not transmitted",
				"tooltip"	=>	"The total number of AppFlow (IPFIX) octets that the NetScaler has not transmitted.",
			),
			"appflownotxoctetsrate" => array (
				"cntrname"	=>	"appflownotxoctetsrate",
				"cntrdescr"	=>	"",
			),
			"appflowtotalnotxflows" => Array(
				"cntrname"	=>	"appflowtotalnotxflows",
				"cntrdescr"	=>	"AppFlow flows not transmitted",
				"tooltip"	=>	"The total number of AppFlow (IPFIX) flows that the NetScaler has not transmitted.",
			),
			"appflownotxflowsrate" => array (
				"cntrname"	=>	"appflownotxflowsrate",
				"cntrdescr"	=>	"",
			),
			"appflowtotalnotxpackets" => Array(
				"cntrname"	=>	"appflowtotalnotxpackets",
				"cntrdescr"	=>	"AppFlow packets not transmitted",
				"tooltip"	=>	"The total number of AppFlow (IPFIX) packets that the NetScaler has not transmitted.",
			),
			"appflownotxpacketsrate" => array (
				"cntrname"	=>	"appflownotxpacketsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Informational statistics -",
				"header"	=>	array("Counter","Total",),
				"format"	=>	"1C",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("appflowtotaltxoctets","appflowtotalflows","appflowtotaltxmessagess",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Error statistics -",
				"header"	=>	array("Counter","Total",),
				"format"	=>	"1C",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("appflowtotalignoctets","appflowtotalignpacketss","appflowtotalnotxoctets","appflowtotalnotxflows","appflowtotalnotxpackets",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Informational statistics -",
				"header"	=>	array("Counter","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("appflowtotaltxoctets","appflowtotalflows","appflowtotaltxmessagess",),
			),
			Array(
				"dhID"	=>	4,
				"title"	=>	"Error statistics -",
				"header"	=>	array("Counter","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("appflowtotalignoctets","appflowtotalignpacketss","appflowtotalnotxoctets","appflowtotalnotxflows","appflowtotalnotxpackets",),
			),
		),
),


"statappfw" => Array(
	"cmddispname"		=>	"statappfw",
	"cmdname"		=>	"statappfw",
	"license"		=>	Array("NSKF1_APPFIREWALL_BIT_POS","NSKF1_APPFIREWALL_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"appfirewalltotallog" => Array(
				"cntrname"	=>	"appfirewalltotallog",
				"cntrdescr"	=>	"total log messages",
				"tooltip"	=>	"Total number of security check log messages generated by the Application Firewall.",
			),
			"appfirewalllograte" => array (
				"cntrname"	=>	"appfirewalllograte",
				"cntrdescr"	=>	"",
			),
			"appfirewalltotalviol" => Array(
				"cntrname"	=>	"appfirewalltotalviol",
				"cntrdescr"	=>	"total violations",
				"tooltip"	=>	"Total number of security check violations seen by the Application Firewall.",
			),
			"appfirewallviolrate" => array (
				"cntrname"	=>	"appfirewallviolrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallshortavgresptime" => Array(
				"cntrname"	=>	"appfirewallshortavgresptime",
				"cntrdescr"	=>	"Recent Ave Response Time (ms)",
				"tooltip"	=>	"Average backend response time in milliseconds over the last 7 seconds",
			),
			"appfirewalllongavgresptime" => Array(
				"cntrname"	=>	"appfirewalllongavgresptime",
				"cntrdescr"	=>	"Long Term Ave Response Time (ms)",
				"tooltip"	=>	"Average backend response time in milliseconds since reboot",
			),
			"appfirewallrequests" => Array(
				"cntrname"	=>	"appfirewallrequests",
				"cntrdescr"	=>	"requests",
				"tooltip"	=>	"HTTP/HTTPS requests sent to your protected web servers via the Application Firewall.",
			),
			"appfirewallrequestsrate" => array (
				"cntrname"	=>	"appfirewallrequestsrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallreqbytes" => Array(
				"cntrname"	=>	"appfirewallreqbytes",
				"cntrdescr"	=>	"Request Bytes",
				"tooltip"	=>	"Number of bytes transfered for requests",
			),
			"appfirewallreqbytesrate" => array (
				"cntrname"	=>	"appfirewallreqbytesrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallresponses" => Array(
				"cntrname"	=>	"appfirewallresponses",
				"cntrdescr"	=>	"responses",
				"tooltip"	=>	"HTTP/HTTPS responses sent by your protected web servers via the Application Firewall.",
			),
			"appfirewallresponsesrate" => array (
				"cntrname"	=>	"appfirewallresponsesrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallresbytes" => Array(
				"cntrname"	=>	"appfirewallresbytes",
				"cntrdescr"	=>	"Response Bytes",
				"tooltip"	=>	"Number of bytes transfered for responses",
			),
			"appfirewallresbytesrate" => array (
				"cntrname"	=>	"appfirewallresbytesrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallaborts" => Array(
				"cntrname"	=>	"appfirewallaborts",
				"cntrdescr"	=>	"aborts",
				"tooltip"	=>	"Incomplete HTTP/HTTPS requests aborted by the client before the Application Firewall could finish processing them.",
			),
			"appfirewallabortsrate" => array (
				"cntrname"	=>	"appfirewallabortsrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallredirects" => Array(
				"cntrname"	=>	"appfirewallredirects",
				"cntrdescr"	=>	"redirects",
				"tooltip"	=>	"HTTP/HTTPS requests redirected by the Application Firewall to a different Web page or web server. (HTTP 302)",
			),
			"appfirewallredirectsrate" => array (
				"cntrname"	=>	"appfirewallredirectsrate",
				"cntrdescr"	=>	"",
			),
			"appfirewalltrapsdropped" => Array(
				"cntrname"	=>	"appfirewalltrapsdropped",
				"cntrdescr"	=>	"Traps Dropped",
				"tooltip"	=>	"AppFirewall SNMP traps dropped due to time limit.",
			),
			"appfirewallviolstarturl" => Array(
				"cntrname"	=>	"appfirewallviolstarturl",
				"cntrdescr"	=>	"start URL",
				"tooltip"	=>	"Number of Start URL security check violations seen by the Application Firewall.",
			),
			"appfirewallviolstarturlrate" => array (
				"cntrname"	=>	"appfirewallviolstarturlrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallvioldenyurl" => Array(
				"cntrname"	=>	"appfirewallvioldenyurl",
				"cntrdescr"	=>	"deny URL",
				"tooltip"	=>	"Number of Deny URL security check violations seen by the Application Firewall.",
			),
			"appfirewallvioldenyurlrate" => array (
				"cntrname"	=>	"appfirewallvioldenyurlrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolrefererheader" => Array(
				"cntrname"	=>	"appfirewallviolrefererheader",
				"cntrdescr"	=>	"referer header",
				"tooltip"	=>	"Number of Referer Header security check violations seen by the Application Firewall.",
			),
			"appfirewallviolrefererheaderrate" => array (
				"cntrname"	=>	"appfirewallviolrefererheaderrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolbufferoverflow" => Array(
				"cntrname"	=>	"appfirewallviolbufferoverflow",
				"cntrdescr"	=>	"buffer overflow",
				"tooltip"	=>	"Number of Buffer Overflow security check violations seen by the Application Firewall.",
			),
			"appfirewallviolbufferoverflowrate" => array (
				"cntrname"	=>	"appfirewallviolbufferoverflowrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolcookie" => Array(
				"cntrname"	=>	"appfirewallviolcookie",
				"cntrdescr"	=>	"cookie consistency",
				"tooltip"	=>	"Number of Cookie Consistency security check violations seen by the Application Firewall.",
			),
			"appfirewallviolcookierate" => array (
				"cntrname"	=>	"appfirewallviolcookierate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolcsrftag" => Array(
				"cntrname"	=>	"appfirewallviolcsrftag",
				"cntrdescr"	=>	"CSRF form tag",
				"tooltip"	=>	"Number of Cross Site Request Forgery form tag security check violations seen by the Application Firewall.",
			),
			"appfirewallviolcsrftagrate" => array (
				"cntrname"	=>	"appfirewallviolcsrftagrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolxss" => Array(
				"cntrname"	=>	"appfirewallviolxss",
				"cntrdescr"	=>	"HTML Cross-site scripting",
				"tooltip"	=>	"Number of HTML Cross-Site Scripting security check violations seen by the Application Firewall.",
			),
			"appfirewallviolxssrate" => array (
				"cntrname"	=>	"appfirewallviolxssrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolsql" => Array(
				"cntrname"	=>	"appfirewallviolsql",
				"cntrdescr"	=>	"HTML SQL injection",
				"tooltip"	=>	"Number of HTML SQL Injection security check violations seen by the Application Firewall.",
			),
			"appfirewallviolsqlrate" => array (
				"cntrname"	=>	"appfirewallviolsqlrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolfieldformat" => Array(
				"cntrname"	=>	"appfirewallviolfieldformat",
				"cntrdescr"	=>	"field format",
				"tooltip"	=>	"Number of Field Format security check violations seen by the Application Firewall.",
			),
			"appfirewallviolfieldformatrate" => array (
				"cntrname"	=>	"appfirewallviolfieldformatrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolfieldconsistency" => Array(
				"cntrname"	=>	"appfirewallviolfieldconsistency",
				"cntrdescr"	=>	"field consistency",
				"tooltip"	=>	"Number of Field Consistency security check violations seen by the Application Firewall.",
			),
			"appfirewallviolfieldconsistencyrate" => array (
				"cntrname"	=>	"appfirewallviolfieldconsistencyrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolcreditcard" => Array(
				"cntrname"	=>	"appfirewallviolcreditcard",
				"cntrdescr"	=>	"credit card",
				"tooltip"	=>	"Number of Credit Card security check violations seen by the Application Firewall.",
			),
			"appfirewallviolcreditcardrate" => array (
				"cntrname"	=>	"appfirewallviolcreditcardrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolsafeobject" => Array(
				"cntrname"	=>	"appfirewallviolsafeobject",
				"cntrdescr"	=>	"safe object",
				"tooltip"	=>	"Number of Safe Object security check violations seen by the Application Firewall.",
			),
			"appfirewallviolsafeobjectrate" => array (
				"cntrname"	=>	"appfirewallviolsafeobjectrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolsignature" => Array(
				"cntrname"	=>	"appfirewallviolsignature",
				"cntrdescr"	=>	"Signature Violations",
				"tooltip"	=>	"Number of Signature violations seen by the Application Firewall.",
			),
			"appfirewallviolsignaturerate" => array (
				"cntrname"	=>	"appfirewallviolsignaturerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolcontenttype" => Array(
				"cntrname"	=>	"appfirewallviolcontenttype",
				"cntrdescr"	=>	"content Type",
				"tooltip"	=>	"Number of Content type security check violations seen by the Application Firewall.",
			),
			"appfirewallviolcontenttyperate" => array (
				"cntrname"	=>	"appfirewallviolcontenttyperate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolwellformednessviolations" => Array(
				"cntrname"	=>	"appfirewallviolwellformednessviolations",
				"cntrdescr"	=>	"XML Format",
				"tooltip"	=>	"Number of XML Format security check violations seen by the Application Firewall.",
			),
			"appfirewallviolwellformednessviolationsrate" => array (
				"cntrname"	=>	"appfirewallviolwellformednessviolationsrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolxdosviolations" => Array(
				"cntrname"	=>	"appfirewallviolxdosviolations",
				"cntrdescr"	=>	"XML Denial of Service (XDoS)",
				"tooltip"	=>	"Number of XML Denial-of-Service security check violations seen by the Application Firewall.",
			),
			"appfirewallviolxdosviolationsrate" => array (
				"cntrname"	=>	"appfirewallviolxdosviolationsrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolmsgvalviolations" => Array(
				"cntrname"	=>	"appfirewallviolmsgvalviolations",
				"cntrdescr"	=>	"XML Message Validation",
				"tooltip"	=>	"Number of XML Message Validation security check violations seen by the Application Firewall.",
			),
			"appfirewallviolmsgvalviolationsrate" => array (
				"cntrname"	=>	"appfirewallviolmsgvalviolationsrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolwsiviolations" => Array(
				"cntrname"	=>	"appfirewallviolwsiviolations",
				"cntrdescr"	=>	"Web Services Interoperability",
				"tooltip"	=>	"Number of Web Services Interoperability (WS-I) security check violations seen by the Application Firewall.",
			),
			"appfirewallviolwsiviolationsrate" => array (
				"cntrname"	=>	"appfirewallviolwsiviolationsrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolxmlsqlviolations" => Array(
				"cntrname"	=>	"appfirewallviolxmlsqlviolations",
				"cntrdescr"	=>	"XML SQL Injection",
				"tooltip"	=>	"Number of XML SQL Injection security check violations seen by the Application Firewall.",
			),
			"appfirewallviolxmlsqlviolationsrate" => array (
				"cntrname"	=>	"appfirewallviolxmlsqlviolationsrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolxmlxssviolations" => Array(
				"cntrname"	=>	"appfirewallviolxmlxssviolations",
				"cntrdescr"	=>	"XML Cross-Site Scripting",
				"tooltip"	=>	"Number of XML Cross-Site Scripting (XSS) security check violations seen by the Application Firewall.",
			),
			"appfirewallviolxmlxssviolationsrate" => array (
				"cntrname"	=>	"appfirewallviolxmlxssviolationsrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolxmlattachmentviolations" => Array(
				"cntrname"	=>	"appfirewallviolxmlattachmentviolations",
				"cntrdescr"	=>	"XML Attachment",
				"tooltip"	=>	"Number of XML Attachment security check violations seen by the Application Firewall.",
			),
			"appfirewallviolxmlattachmentviolationsrate" => array (
				"cntrname"	=>	"appfirewallviolxmlattachmentviolationsrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolxmlsoapfaultviolations" => Array(
				"cntrname"	=>	"appfirewallviolxmlsoapfaultviolations",
				"cntrdescr"	=>	"SOAP Fault Violations",
				"tooltip"	=>	"Number of requests returning soap:fault from the backend server",
			),
			"appfirewallviolxmlsoapfaultviolationsrate" => array (
				"cntrname"	=>	"appfirewallviolxmlsoapfaultviolationsrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolxmlgenviolations" => Array(
				"cntrname"	=>	"appfirewallviolxmlgenviolations",
				"cntrdescr"	=>	"XML Generic Violations",
				"tooltip"	=>	"Number of requests returning XML generic error from the backend server",
			),
			"appfirewallviolxmlgenviolationsrate" => array (
				"cntrname"	=>	"appfirewallviolxmlgenviolationsrate",
				"cntrdescr"	=>	"",
			),
			"appfirewalllogstarturl" => Array(
				"cntrname"	=>	"appfirewalllogstarturl",
				"cntrdescr"	=>	"start URL logs",
				"tooltip"	=>	"Number of Start URL security check log messages generated by the Application Firewall.",
			),
			"appfirewalllogstarturlrate" => array (
				"cntrname"	=>	"appfirewalllogstarturlrate",
				"cntrdescr"	=>	"",
			),
			"appfirewalllogdenyurl" => Array(
				"cntrname"	=>	"appfirewalllogdenyurl",
				"cntrdescr"	=>	"deny URL logs",
				"tooltip"	=>	"Number of Deny URL security check log messages generated by the Application Firewall.",
			),
			"appfirewalllogdenyurlrate" => array (
				"cntrname"	=>	"appfirewalllogdenyurlrate",
				"cntrdescr"	=>	"",
			),
			"appfirewalllogrefererheader" => Array(
				"cntrname"	=>	"appfirewalllogrefererheader",
				"cntrdescr"	=>	"referer header logs",
				"tooltip"	=>	"Number of Referer Header security check log messages generated by the Application Firewall.",
			),
			"appfirewalllogrefererheaderrate" => array (
				"cntrname"	=>	"appfirewalllogrefererheaderrate",
				"cntrdescr"	=>	"",
			),
			"appfirewalllogbufferoverflow" => Array(
				"cntrname"	=>	"appfirewalllogbufferoverflow",
				"cntrdescr"	=>	"buffer overflow logs",
				"tooltip"	=>	"Number of Buffer Overflow security check log messages generated by the Application Firewall.",
			),
			"appfirewalllogbufferoverflowrate" => array (
				"cntrname"	=>	"appfirewalllogbufferoverflowrate",
				"cntrdescr"	=>	"",
			),
			"appfirewalllogcookie" => Array(
				"cntrname"	=>	"appfirewalllogcookie",
				"cntrdescr"	=>	"cookie consistency logs",
				"tooltip"	=>	"Number of Cookie Consistency security check log messages generated by the Application Firewall.",
			),
			"appfirewalllogcookierate" => array (
				"cntrname"	=>	"appfirewalllogcookierate",
				"cntrdescr"	=>	"",
			),
			"appfirewalllogcsrftag" => Array(
				"cntrname"	=>	"appfirewalllogcsrftag",
				"cntrdescr"	=>	"CSRF form tag logs",
				"tooltip"	=>	"Number of Cross Site Request Forgery form tag security check log messages generated by the Application Firewall.",
			),
			"appfirewalllogcsrftagrate" => array (
				"cntrname"	=>	"appfirewalllogcsrftagrate",
				"cntrdescr"	=>	"",
			),
			"appfirewalllogxss" => Array(
				"cntrname"	=>	"appfirewalllogxss",
				"cntrdescr"	=>	"HTML XSS logs",
				"tooltip"	=>	"Number of HTML Cross-Site Scripting security check log messages generated by the Application Firewall.",
			),
			"appfirewalllogxssrate" => array (
				"cntrname"	=>	"appfirewalllogxssrate",
				"cntrdescr"	=>	"",
			),
			"appfirewalllogtransformxss" => Array(
				"cntrname"	=>	"appfirewalllogtransformxss",
				"cntrdescr"	=>	"HTML XSS transform logs",
				"tooltip"	=>	"Number of HTML Cross-Site Scripting security check transform log messages generated by the Application Firewall.",
			),
			"appfirewalllogtransformxssrate" => array (
				"cntrname"	=>	"appfirewalllogtransformxssrate",
				"cntrdescr"	=>	"",
			),
			"appfirewalllogsql" => Array(
				"cntrname"	=>	"appfirewalllogsql",
				"cntrdescr"	=>	"HTML SQL logs",
				"tooltip"	=>	"Number of HTML SQL Injection security check log messages generated by the Application Firewall.",
			),
			"appfirewalllogsqlrate" => array (
				"cntrname"	=>	"appfirewalllogsqlrate",
				"cntrdescr"	=>	"",
			),
			"appfirewalllogtransformsql" => Array(
				"cntrname"	=>	"appfirewalllogtransformsql",
				"cntrdescr"	=>	"HTML SQL transform logs",
				"tooltip"	=>	"Number of HTML SQL Injection security check transform log messages generated by the Application Firewall.",
			),
			"appfirewalllogtransformsqlrate" => array (
				"cntrname"	=>	"appfirewalllogtransformsqlrate",
				"cntrdescr"	=>	"",
			),
			"appfirewalllogfieldformat" => Array(
				"cntrname"	=>	"appfirewalllogfieldformat",
				"cntrdescr"	=>	"field format logs",
				"tooltip"	=>	"Number of Field Format security check log messages generated by the Application Firewall.",
			),
			"appfirewalllogfieldformatrate" => array (
				"cntrname"	=>	"appfirewalllogfieldformatrate",
				"cntrdescr"	=>	"",
			),
			"appfirewalllogfieldconsistency" => Array(
				"cntrname"	=>	"appfirewalllogfieldconsistency",
				"cntrdescr"	=>	"field consistency",
				"tooltip"	=>	"Number of Field Consistency security check log messages generated by the Application Firewall.",
			),
			"appfirewalllogfieldconsistencyrate" => array (
				"cntrname"	=>	"appfirewalllogfieldconsistencyrate",
				"cntrdescr"	=>	"",
			),
			"appfirewalllogcreditcard" => Array(
				"cntrname"	=>	"appfirewalllogcreditcard",
				"cntrdescr"	=>	"credit cards",
				"tooltip"	=>	"Number of Credit Card security check log messages generated by the Application Firewall.",
			),
			"appfirewalllogcreditcardrate" => array (
				"cntrname"	=>	"appfirewalllogcreditcardrate",
				"cntrdescr"	=>	"",
			),
			"appfirewalllogsafeobject" => Array(
				"cntrname"	=>	"appfirewalllogsafeobject",
				"cntrdescr"	=>	"safe object logs",
				"tooltip"	=>	"Number of Safe Object security check log messages generated by the Application Firewall.",
			),
			"appfirewalllogsafeobjectrate" => array (
				"cntrname"	=>	"appfirewalllogsafeobjectrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallsignaturelogs" => Array(
				"cntrname"	=>	"appfirewallsignaturelogs",
				"cntrdescr"	=>	"Signature logs",
				"tooltip"	=>	"Number of Signature logs generated by the Application Firewall.",
			),
			"appfirewallsignaturelogsrate" => array (
				"cntrname"	=>	"appfirewallsignaturelogsrate",
				"cntrdescr"	=>	"",
			),
			"appfirewalllogcontenttype" => Array(
				"cntrname"	=>	"appfirewalllogcontenttype",
				"cntrdescr"	=>	"content Type logs",
				"tooltip"	=>	"Number of content type security check log messages generated by the Application Firewall.",
			),
			"appfirewalllogcontenttyperate" => array (
				"cntrname"	=>	"appfirewalllogcontenttyperate",
				"cntrdescr"	=>	"",
			),
			"appfirewallwellformednesslogs" => Array(
				"cntrname"	=>	"appfirewallwellformednesslogs",
				"cntrdescr"	=>	"XML Format logs",
				"tooltip"	=>	"Number of XML Format security check log messages generated by the Application Firewall.",
			),
			"appfirewallwellformednesslogsrate" => array (
				"cntrname"	=>	"appfirewallwellformednesslogsrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallxdoslogs" => Array(
				"cntrname"	=>	"appfirewallxdoslogs",
				"cntrdescr"	=>	"XML Denial of Service (XDoS) log messages",
				"tooltip"	=>	"Number of XML Denial-of-Service security check log messages generated by the Application Firewall.",
			),
			"appfirewallxdoslogsrate" => array (
				"cntrname"	=>	"appfirewallxdoslogsrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallmsgvallogs" => Array(
				"cntrname"	=>	"appfirewallmsgvallogs",
				"cntrdescr"	=>	"XML Message Validation logs",
				"tooltip"	=>	"Number of XML Message Validation security check log messages generated by the Application Firewall.",
			),
			"appfirewallmsgvallogsrate" => array (
				"cntrname"	=>	"appfirewallmsgvallogsrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallwsilogs" => Array(
				"cntrname"	=>	"appfirewallwsilogs",
				"cntrdescr"	=>	"WSI logs",
				"tooltip"	=>	"Number of Web Services Interoperability (WS-I) security check log messages generated by the Application Firewall.",
			),
			"appfirewallwsilogsrate" => array (
				"cntrname"	=>	"appfirewallwsilogsrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallxmlsqllogs" => Array(
				"cntrname"	=>	"appfirewallxmlsqllogs",
				"cntrdescr"	=>	"XML SQL Injection logs",
				"tooltip"	=>	"Number of XML SQL Injection security check log messages generated by the Application Firewall.",
			),
			"appfirewallxmlsqllogsrate" => array (
				"cntrname"	=>	"appfirewallxmlsqllogsrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallxmlxsslogs" => Array(
				"cntrname"	=>	"appfirewallxmlxsslogs",
				"cntrdescr"	=>	"XML XSS logs",
				"tooltip"	=>	"Number of XML Cross-Site Scripting (XSS) security check log messages generated by the Application Firewall.",
			),
			"appfirewallxmlxsslogsrate" => array (
				"cntrname"	=>	"appfirewallxmlxsslogsrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallxmlattachmentlogs" => Array(
				"cntrname"	=>	"appfirewallxmlattachmentlogs",
				"cntrdescr"	=>	"XML Attachment logs",
				"tooltip"	=>	"Number of XML Attachment security check log messages generated by the Application Firewall.",
			),
			"appfirewallxmlattachmentlogsrate" => array (
				"cntrname"	=>	"appfirewallxmlattachmentlogsrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallxmlsoapfaultlogs" => Array(
				"cntrname"	=>	"appfirewallxmlsoapfaultlogs",
				"cntrdescr"	=>	"SOAP Fault logs",
				"tooltip"	=>	"Number of requests generating soap:fault log messages",
			),
			"appfirewallxmlsoapfaultlogsrate" => array (
				"cntrname"	=>	"appfirewallxmlsoapfaultlogsrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallxmlgenlogs" => Array(
				"cntrname"	=>	"appfirewallxmlgenlogs",
				"cntrdescr"	=>	"XML Generic logs",
				"tooltip"	=>	"Number of requests generating XML generic error log messages",
			),
			"appfirewallxmlgenlogsrate" => array (
				"cntrname"	=>	"appfirewallxmlgenlogsrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallret4xx" => Array(
				"cntrname"	=>	"appfirewallret4xx",
				"cntrdescr"	=>	"HTTP Client Errors (4xx Resp)",
				"tooltip"	=>	"Number of requests returning HTTP 4xx from the backend server",
			),
			"appfirewallret4xxrate" => array (
				"cntrname"	=>	"appfirewallret4xxrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallret5xx" => Array(
				"cntrname"	=>	"appfirewallret5xx",
				"cntrdescr"	=>	"HTTP Server Errors (5xx Resp)",
				"tooltip"	=>	"Number of requests returning HTTP 5xx from the backend server",
			),
			"appfirewallret5xxrate" => array (
				"cntrname"	=>	"appfirewallret5xxrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Traffic Statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("appfirewallrequests","appfirewallreqbytes","appfirewallresponses","appfirewallresbytes","appfirewallaborts","appfirewallredirects","appfirewalllongavgresptime","appfirewallshortavgresptime","appfirewalltrapsdropped",),
				"charts"	=>	array(	array("appfw_requests",),
								),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"HTML/XML Violation Statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("appfirewallviolstarturl","appfirewallvioldenyurl","appfirewallviolrefererheader","appfirewallviolbufferoverflow","appfirewallviolcookie","appfirewallviolcsrftag","appfirewallviolxss","appfirewallviolsql","appfirewallviolfieldformat","appfirewallviolfieldconsistency","appfirewallviolcreditcard","appfirewallviolsafeobject","appfirewallviolsignature","appfirewallviolcontenttype","appfirewallviolwellformednessviolations","appfirewallviolxdosviolations","appfirewallviolmsgvalviolations","appfirewallviolwsiviolations","appfirewallviolxmlsqlviolations","appfirewallviolxmlxssviolations","appfirewallviolxmlattachmentviolations","appfirewallviolxmlsoapfaultviolations","appfirewallviolxmlgenviolations","appfirewalltotalviol",),
				"charts"	=>	array(	array("appfw_violations",),
								),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"HTML/XML Log Statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("appfirewalllogstarturl","appfirewalllogdenyurl","appfirewalllogrefererheader","appfirewalllogbufferoverflow","appfirewalllogcookie","appfirewalllogcsrftag","appfirewalllogxss","appfirewalllogtransformxss","appfirewalllogsql","appfirewalllogtransformsql","appfirewalllogfieldformat","appfirewalllogfieldconsistency","appfirewalllogcreditcard","appfirewalllogsafeobject","appfirewallsignaturelogs","appfirewalllogcontenttype","appfirewallwellformednesslogs","appfirewallxdoslogs","appfirewallmsgvallogs","appfirewallwsilogs","appfirewallxmlsqllogs","appfirewallxmlxsslogs","appfirewallxmlattachmentlogs","appfirewallxmlsoapfaultlogs","appfirewallxmlgenlogs","appfirewalltotallog",),
			),
			Array(
				"dhID"	=>	4,
				"title"	=>	"Server Error Response Statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("appfirewallret4xx","appfirewallret5xx",),
			),
		),
),


"statappfwprofile" => Array(
	"cmddispname"		=>	"statappfwprofile",
	"cmdname"		=>	"statappfwprofile",
	"license"		=>	Array("NSKF1_APPFIREWALL_BIT_POS","NSKF1_APPFIREWALL_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"appfirewallrequestsperprofile" => Array(
				"cntrname"	=>	"appfirewallrequestsperprofile",
				"cntrdescr"	=>	"requests",
				"tooltip"	=>	"HTTP/HTTPS requests sent to your protected web servers via the Application Firewall.",
			),
			"appfirewallrequestsperprofilerate" => array (
				"cntrname"	=>	"appfirewallrequestsperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallreqbytesperprofile" => Array(
				"cntrname"	=>	"appfirewallreqbytesperprofile",
				"cntrdescr"	=>	"Request Bytes",
				"tooltip"	=>	"Number of bytes transfered for requests",
			),
			"appfirewallreqbytesperprofilerate" => array (
				"cntrname"	=>	"appfirewallreqbytesperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallresponsesperprofile" => Array(
				"cntrname"	=>	"appfirewallresponsesperprofile",
				"cntrdescr"	=>	"responses",
				"tooltip"	=>	"HTTP/HTTPS responses sent by your protected web servers via the Application Firewall.",
			),
			"appfirewallresponsesperprofilerate" => array (
				"cntrname"	=>	"appfirewallresponsesperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallresbytesperprofile" => Array(
				"cntrname"	=>	"appfirewallresbytesperprofile",
				"cntrdescr"	=>	"Response Bytes",
				"tooltip"	=>	"Number of bytes transfered for responses",
			),
			"appfirewallresbytesperprofilerate" => array (
				"cntrname"	=>	"appfirewallresbytesperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallabortsperprofile" => Array(
				"cntrname"	=>	"appfirewallabortsperprofile",
				"cntrdescr"	=>	"aborts",
				"tooltip"	=>	"Incomplete HTTP/HTTPS requests aborted by the client before the Application Firewall could finish processing them.",
			),
			"appfirewallabortsperprofilerate" => array (
				"cntrname"	=>	"appfirewallabortsperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallredirectsperprofile" => Array(
				"cntrname"	=>	"appfirewallredirectsperprofile",
				"cntrdescr"	=>	"redirects",
				"tooltip"	=>	"HTTP/HTTPS requests redirected by the Application Firewall to a different Web page or web server. (HTTP 302)",
			),
			"appfirewallredirectsperprofilerate" => array (
				"cntrname"	=>	"appfirewallredirectsperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewalllongavgresptimeperprofile" => Array(
				"cntrname"	=>	"appfirewalllongavgresptimeperprofile",
				"cntrdescr"	=>	"Long Term Ave Response Time (ms)",
				"tooltip"	=>	"Average backend response time in milliseconds since reboot",
			),
			"appfirewallshortavgresptimeperprofile" => Array(
				"cntrname"	=>	"appfirewallshortavgresptimeperprofile",
				"cntrdescr"	=>	"Recent Ave Response Time (ms)",
				"tooltip"	=>	"Average backend response time in milliseconds over the last 7 seconds",
			),
			"appfirewallviolstarturlperprofile" => Array(
				"cntrname"	=>	"appfirewallviolstarturlperprofile",
				"cntrdescr"	=>	"start URL",
				"tooltip"	=>	"Number of Start URL security check violations seen by the Application Firewall.",
			),
			"appfirewallviolstarturlperprofilerate" => array (
				"cntrname"	=>	"appfirewallviolstarturlperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallvioldenyurlperprofile" => Array(
				"cntrname"	=>	"appfirewallvioldenyurlperprofile",
				"cntrdescr"	=>	"deny URL",
				"tooltip"	=>	"Number of Deny URL security check violations seen by the Application Firewall.",
			),
			"appfirewallvioldenyurlperprofilerate" => array (
				"cntrname"	=>	"appfirewallvioldenyurlperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolrefererheaderperprofile" => Array(
				"cntrname"	=>	"appfirewallviolrefererheaderperprofile",
				"cntrdescr"	=>	"referer header",
				"tooltip"	=>	"Number of Referer Header security check violations seen by the Application Firewall.",
			),
			"appfirewallviolrefererheaderperprofilerate" => array (
				"cntrname"	=>	"appfirewallviolrefererheaderperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolbufferoverflowperprofile" => Array(
				"cntrname"	=>	"appfirewallviolbufferoverflowperprofile",
				"cntrdescr"	=>	"buffer overflow",
				"tooltip"	=>	"Number of Buffer Overflow security check violations seen by the Application Firewall.",
			),
			"appfirewallviolbufferoverflowperprofilerate" => array (
				"cntrname"	=>	"appfirewallviolbufferoverflowperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolcookieperprofile" => Array(
				"cntrname"	=>	"appfirewallviolcookieperprofile",
				"cntrdescr"	=>	"cookie consistency",
				"tooltip"	=>	"Number of Cookie Consistency security check violations seen by the Application Firewall.",
			),
			"appfirewallviolcookieperprofilerate" => array (
				"cntrname"	=>	"appfirewallviolcookieperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolcsrftagperprofile" => Array(
				"cntrname"	=>	"appfirewallviolcsrftagperprofile",
				"cntrdescr"	=>	"CSRF form tag",
				"tooltip"	=>	"Number of Cross Site Request Forgery form tag security check violations seen by the Application Firewall.",
			),
			"appfirewallviolcsrftagperprofilerate" => array (
				"cntrname"	=>	"appfirewallviolcsrftagperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolxssperprofile" => Array(
				"cntrname"	=>	"appfirewallviolxssperprofile",
				"cntrdescr"	=>	"HTML Cross-site scripting",
				"tooltip"	=>	"Number of HTML Cross-Site Scripting security check violations seen by the Application Firewall.",
			),
			"appfirewallviolxssperprofilerate" => array (
				"cntrname"	=>	"appfirewallviolxssperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolsqlperprofile" => Array(
				"cntrname"	=>	"appfirewallviolsqlperprofile",
				"cntrdescr"	=>	"HTML SQL injection",
				"tooltip"	=>	"Number of HTML SQL Injection security check violations seen by the Application Firewall.",
			),
			"appfirewallviolsqlperprofilerate" => array (
				"cntrname"	=>	"appfirewallviolsqlperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolfieldformatperprofile" => Array(
				"cntrname"	=>	"appfirewallviolfieldformatperprofile",
				"cntrdescr"	=>	"field format",
				"tooltip"	=>	"Number of Field Format security check violations seen by the Application Firewall.",
			),
			"appfirewallviolfieldformatperprofilerate" => array (
				"cntrname"	=>	"appfirewallviolfieldformatperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolfieldconsistencyperprofile" => Array(
				"cntrname"	=>	"appfirewallviolfieldconsistencyperprofile",
				"cntrdescr"	=>	"field consistency",
				"tooltip"	=>	"Number of Field Consistency security check violations seen by the Application Firewall.",
			),
			"appfirewallviolfieldconsistencyperprofilerate" => array (
				"cntrname"	=>	"appfirewallviolfieldconsistencyperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolcreditcardperprofile" => Array(
				"cntrname"	=>	"appfirewallviolcreditcardperprofile",
				"cntrdescr"	=>	"credit card",
				"tooltip"	=>	"Number of Credit Card security check violations seen by the Application Firewall.",
			),
			"appfirewallviolcreditcardperprofilerate" => array (
				"cntrname"	=>	"appfirewallviolcreditcardperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolsafeobjectperprofile" => Array(
				"cntrname"	=>	"appfirewallviolsafeobjectperprofile",
				"cntrdescr"	=>	"safe object",
				"tooltip"	=>	"Number of Safe Object security check violations seen by the Application Firewall.",
			),
			"appfirewallviolsafeobjectperprofilerate" => array (
				"cntrname"	=>	"appfirewallviolsafeobjectperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolsignatureperprofile" => Array(
				"cntrname"	=>	"appfirewallviolsignatureperprofile",
				"cntrdescr"	=>	"Signature Violations",
				"tooltip"	=>	"Number of Signature violations seen by the Application Firewall.",
			),
			"appfirewallviolsignatureperprofilerate" => array (
				"cntrname"	=>	"appfirewallviolsignatureperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolcontenttypeperprofile" => Array(
				"cntrname"	=>	"appfirewallviolcontenttypeperprofile",
				"cntrdescr"	=>	"content Type",
				"tooltip"	=>	"Number of Content Type security check violations seen by the Application Firewall.",
			),
			"appfirewallviolcontenttypeperprofilerate" => array (
				"cntrname"	=>	"appfirewallviolcontenttypeperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolwellformednessviolationsperprofile" => Array(
				"cntrname"	=>	"appfirewallviolwellformednessviolationsperprofile",
				"cntrdescr"	=>	"XML Format",
				"tooltip"	=>	"Number of XML Format security check violations seen by the Application Firewall.",
			),
			"appfirewallviolwellformednessviolationsperprofilerate" => array (
				"cntrname"	=>	"appfirewallviolwellformednessviolationsperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolxdosviolationsperprofile" => Array(
				"cntrname"	=>	"appfirewallviolxdosviolationsperprofile",
				"cntrdescr"	=>	"XML Denial of Service (XDoS)",
				"tooltip"	=>	"Number of XML Denial-of-Service security check violations seen by the Application Firewall.",
			),
			"appfirewallviolxdosviolationsperprofilerate" => array (
				"cntrname"	=>	"appfirewallviolxdosviolationsperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolmsgvalviolationsperprofile" => Array(
				"cntrname"	=>	"appfirewallviolmsgvalviolationsperprofile",
				"cntrdescr"	=>	"XML Message Validation",
				"tooltip"	=>	"Number of XML Message Validation security check violations seen by the Application Firewall.",
			),
			"appfirewallviolmsgvalviolationsperprofilerate" => array (
				"cntrname"	=>	"appfirewallviolmsgvalviolationsperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolwsiviolationsperprofile" => Array(
				"cntrname"	=>	"appfirewallviolwsiviolationsperprofile",
				"cntrdescr"	=>	"Web Services Interoperability",
				"tooltip"	=>	"Number of Web Services Interoperability (WS-I) security check violations seen by the Application Firewall.",
			),
			"appfirewallviolwsiviolationsperprofilerate" => array (
				"cntrname"	=>	"appfirewallviolwsiviolationsperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolxmlsqlviolationsperprofile" => Array(
				"cntrname"	=>	"appfirewallviolxmlsqlviolationsperprofile",
				"cntrdescr"	=>	"XML SQL Injection",
				"tooltip"	=>	"Number of XML SQL Injection security check violations seen by the Application Firewall.",
			),
			"appfirewallviolxmlsqlviolationsperprofilerate" => array (
				"cntrname"	=>	"appfirewallviolxmlsqlviolationsperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolxmlxssviolationsperprofile" => Array(
				"cntrname"	=>	"appfirewallviolxmlxssviolationsperprofile",
				"cntrdescr"	=>	"XML Cross-Site Scripting",
				"tooltip"	=>	"Number of XML Cross-Site Scripting (XSS) security check violations seen by the Application Firewall.",
			),
			"appfirewallviolxmlxssviolationsperprofilerate" => array (
				"cntrname"	=>	"appfirewallviolxmlxssviolationsperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolxmlattachmentviolationsperprofile" => Array(
				"cntrname"	=>	"appfirewallviolxmlattachmentviolationsperprofile",
				"cntrdescr"	=>	"XML Attachment",
				"tooltip"	=>	"Number of XML Attachment security check violations seen by the Application Firewall.",
			),
			"appfirewallviolxmlattachmentviolationsperprofilerate" => array (
				"cntrname"	=>	"appfirewallviolxmlattachmentviolationsperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolxmlsoapfaultviolationsperprofile" => Array(
				"cntrname"	=>	"appfirewallviolxmlsoapfaultviolationsperprofile",
				"cntrdescr"	=>	"SOAP Fault Violations",
				"tooltip"	=>	"Number of requests returning soap:fault from the backend server",
			),
			"appfirewallviolxmlsoapfaultviolationsperprofilerate" => array (
				"cntrname"	=>	"appfirewallviolxmlsoapfaultviolationsperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolxmlgenericviolationsperprofile" => Array(
				"cntrname"	=>	"appfirewallviolxmlgenericviolationsperprofile",
				"cntrdescr"	=>	"XML Generic Violations",
				"tooltip"	=>	"Number of requests returning XML generic violation from the backend server",
			),
			"appfirewallviolxmlgenericviolationsperprofilerate" => array (
				"cntrname"	=>	"appfirewallviolxmlgenericviolationsperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewalltotalviolperprofile" => Array(
				"cntrname"	=>	"appfirewalltotalviolperprofile",
				"cntrdescr"	=>	"Total Violations",
				"tooltip"	=>	"Number of violations seen by the application firewall on per profile basis",
			),
			"appfirewallviolperprofilerate" => array (
				"cntrname"	=>	"appfirewallviolperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewalllogstarturlperprofile" => Array(
				"cntrname"	=>	"appfirewalllogstarturlperprofile",
				"cntrdescr"	=>	"start URL logs",
				"tooltip"	=>	"Number of Start URL security check log messages generated by the Application Firewall.",
			),
			"appfirewalllogstarturlperprofilerate" => array (
				"cntrname"	=>	"appfirewalllogstarturlperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewalllogdenyurlperprofile" => Array(
				"cntrname"	=>	"appfirewalllogdenyurlperprofile",
				"cntrdescr"	=>	"deny URL logs",
				"tooltip"	=>	"Number of Deny URL security check log messages generated by the Application Firewall.",
			),
			"appfirewalllogdenyurlperprofilerate" => array (
				"cntrname"	=>	"appfirewalllogdenyurlperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewalllogrefererheaderperprofile" => Array(
				"cntrname"	=>	"appfirewalllogrefererheaderperprofile",
				"cntrdescr"	=>	"referer header logs",
				"tooltip"	=>	"Number of Referer Header security check log messages generated by the Application Firewall.",
			),
			"appfirewalllogrefererheaderperprofilerate" => array (
				"cntrname"	=>	"appfirewalllogrefererheaderperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewalllogbufferoverflowperprofile" => Array(
				"cntrname"	=>	"appfirewalllogbufferoverflowperprofile",
				"cntrdescr"	=>	"buffer overflow logs",
				"tooltip"	=>	"Number of Buffer Overflow security check log messages generated by the Application Firewall.",
			),
			"appfirewalllogbufferoverflowperprofilerate" => array (
				"cntrname"	=>	"appfirewalllogbufferoverflowperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewalllogcookieperprofile" => Array(
				"cntrname"	=>	"appfirewalllogcookieperprofile",
				"cntrdescr"	=>	"cookie consistency logs",
				"tooltip"	=>	"Number of Cookie Consistency security check log messages generated by the Application Firewall.",
			),
			"appfirewalllogcookieperprofilerate" => array (
				"cntrname"	=>	"appfirewalllogcookieperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewalllogcsrftagperprofile" => Array(
				"cntrname"	=>	"appfirewalllogcsrftagperprofile",
				"cntrdescr"	=>	"CSRF form tag logs",
				"tooltip"	=>	"Number of Cross Site Request Forgery form tag security check log messages generated by the Application Firewall.",
			),
			"appfirewalllogcsrftagperprofilerate" => array (
				"cntrname"	=>	"appfirewalllogcsrftagperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewalllogxssperprofile" => Array(
				"cntrname"	=>	"appfirewalllogxssperprofile",
				"cntrdescr"	=>	"HTML XSS logs",
				"tooltip"	=>	"Number of HTML Cross-Site Scripting security check log messages generated by the Application Firewall.",
			),
			"appfirewalllogxssperprofilerate" => array (
				"cntrname"	=>	"appfirewalllogxssperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewalllogxformxssperprofile" => Array(
				"cntrname"	=>	"appfirewalllogxformxssperprofile",
				"cntrdescr"	=>	"HTML XSS transform logs",
				"tooltip"	=>	"Number of HTML Cross-Site Scripting security check transform log messages generated by the Application Firewall.",
			),
			"appfirewalllogxformxssperprofilerate" => array (
				"cntrname"	=>	"appfirewalllogxformxssperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewalllogsqlperprofile" => Array(
				"cntrname"	=>	"appfirewalllogsqlperprofile",
				"cntrdescr"	=>	"HTML SQL Injection logs",
				"tooltip"	=>	"Number of HTML SQL Injection security check log messages generated by the Application Firewall.",
			),
			"appfirewalllogsqlperprofilerate" => array (
				"cntrname"	=>	"appfirewalllogsqlperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewalllogxformsqlperprofile" => Array(
				"cntrname"	=>	"appfirewalllogxformsqlperprofile",
				"cntrdescr"	=>	"HTML SQL transform logs",
				"tooltip"	=>	"Number of HTML SQL Injection security check transform log messages generated by the Application Firewall.",
			),
			"appfirewalllogxformsqlperprofilerate" => array (
				"cntrname"	=>	"appfirewalllogxformsqlperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewalllogfieldformatperprofile" => Array(
				"cntrname"	=>	"appfirewalllogfieldformatperprofile",
				"cntrdescr"	=>	"field format logs",
				"tooltip"	=>	"Number of Field Format security check log messages generated by the Application Firewall.",
			),
			"appfirewalllogfieldformatperprofilerate" => array (
				"cntrname"	=>	"appfirewalllogfieldformatperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewalllogfieldconsistencyperprofile" => Array(
				"cntrname"	=>	"appfirewalllogfieldconsistencyperprofile",
				"cntrdescr"	=>	"field consistency logs",
				"tooltip"	=>	"Number of Field Consistency security check log messages generated by the Application Firewall.",
			),
			"appfirewalllogfieldconsistencyperprofilerate" => array (
				"cntrname"	=>	"appfirewalllogfieldconsistencyperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewalllogcreditcardperprofile" => Array(
				"cntrname"	=>	"appfirewalllogcreditcardperprofile",
				"cntrdescr"	=>	"credit cards",
				"tooltip"	=>	"Number of Credit Card security check log messages generated by the Application Firewall.",
			),
			"appfirewalllogcreditcardperprofilerate" => array (
				"cntrname"	=>	"appfirewalllogcreditcardperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallxformlogcreditcardperprofile" => Array(
				"cntrname"	=>	"appfirewallxformlogcreditcardperprofile",
				"cntrdescr"	=>	"credit card transform logs",
				"tooltip"	=>	"Number of Credit Card security check transform log messages generated by the Application Firewall.",
			),
			"appfirewallxformlogcreditcardperprofilerate" => array (
				"cntrname"	=>	"appfirewallxformlogcreditcardperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewalllogsafeobjectperprofile" => Array(
				"cntrname"	=>	"appfirewalllogsafeobjectperprofile",
				"cntrdescr"	=>	"safe object logs",
				"tooltip"	=>	"Number of Safe Object security check log messages generated by the Application Firewall.",
			),
			"appfirewalllogsafeobjectperprofilerate" => array (
				"cntrname"	=>	"appfirewalllogsafeobjectperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallviolsignatureperprofile" => Array(
				"cntrname"	=>	"appfirewallviolsignatureperprofile",
				"cntrdescr"	=>	"Signature logs",
				"tooltip"	=>	"Number of Signature log messages generated by the Application Firewall.",
			),
			"appfirewallviolsignatureperprofilerate" => array (
				"cntrname"	=>	"appfirewallviolsignatureperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewalllogcontenttypeperprofile" => Array(
				"cntrname"	=>	"appfirewalllogcontenttypeperprofile",
				"cntrdescr"	=>	"content Type logs",
				"tooltip"	=>	"Number of Content type security check log messages generated by the Application Firewall.",
			),
			"appfirewalllogcontenttypeperprofilerate" => array (
				"cntrname"	=>	"appfirewalllogcontenttypeperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallwellformednesslogsperprofile" => Array(
				"cntrname"	=>	"appfirewallwellformednesslogsperprofile",
				"cntrdescr"	=>	"XML Format logs",
				"tooltip"	=>	"Number of XML Format security check log messages generated by the Application Firewall.",
			),
			"appfirewallwellformednesslogsperprofilerate" => array (
				"cntrname"	=>	"appfirewallwellformednesslogsperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallxdoslogsperprofile" => Array(
				"cntrname"	=>	"appfirewallxdoslogsperprofile",
				"cntrdescr"	=>	"XML Denial of Service(XDoS) logs",
				"tooltip"	=>	"Number of XML Denial-of-Service security check log messages generated by the Application Firewall.",
			),
			"appfirewallxdoslogsperprofilerate" => array (
				"cntrname"	=>	"appfirewallxdoslogsperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallmsgvallogsperprofile" => Array(
				"cntrname"	=>	"appfirewallmsgvallogsperprofile",
				"cntrdescr"	=>	"XML Message Validation logs",
				"tooltip"	=>	"Number of XML Message Validation security check log messages generated by the Application Firewall.",
			),
			"appfirewallmsgvallogsperprofilerate" => array (
				"cntrname"	=>	"appfirewallmsgvallogsperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallwsilogsperprofile" => Array(
				"cntrname"	=>	"appfirewallwsilogsperprofile",
				"cntrdescr"	=>	"WSI logs",
				"tooltip"	=>	"Number of Web Services Interoperability (WS-I) security check log messages generated by the Application Firewall.",
			),
			"appfirewallwsilogsperprofilerate" => array (
				"cntrname"	=>	"appfirewallwsilogsperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallxmlsqllogsperprofile" => Array(
				"cntrname"	=>	"appfirewallxmlsqllogsperprofile",
				"cntrdescr"	=>	"XML SQL Injection logs",
				"tooltip"	=>	"Number of XML SQL Injection security check log messages generated by the Application Firewall.",
			),
			"appfirewallxmlsqllogsperprofilerate" => array (
				"cntrname"	=>	"appfirewallxmlsqllogsperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallxmlxsslogsperprofile" => Array(
				"cntrname"	=>	"appfirewallxmlxsslogsperprofile",
				"cntrdescr"	=>	"XML XSS logs",
				"tooltip"	=>	"Number of XML Cross-Site Scripting (XSS) security check log messages generated by the Application Firewall.",
			),
			"appfirewallxmlxsslogsperprofilerate" => array (
				"cntrname"	=>	"appfirewallxmlxsslogsperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallxmlattachmentlogsperprofile" => Array(
				"cntrname"	=>	"appfirewallxmlattachmentlogsperprofile",
				"cntrdescr"	=>	"XML Attachment logs",
				"tooltip"	=>	"Number of XML Attachment security check log messages generated by the Application Firewall.",
			),
			"appfirewallxmlattachmentlogsperprofilerate" => array (
				"cntrname"	=>	"appfirewallxmlattachmentlogsperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallxmlsoapfaultlogsperprofile" => Array(
				"cntrname"	=>	"appfirewallxmlsoapfaultlogsperprofile",
				"cntrdescr"	=>	"SOAP Fault logs",
				"tooltip"	=>	"Number of requests generating soap:fault log messages",
			),
			"appfirewallxmlsoapfaultlogsperprofilerate" => array (
				"cntrname"	=>	"appfirewallxmlsoapfaultlogsperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallxmlgenericlogsperprofile" => Array(
				"cntrname"	=>	"appfirewallxmlgenericlogsperprofile",
				"cntrdescr"	=>	"XML Generic logs",
				"tooltip"	=>	"Number of requests generating XML Generic log messages",
			),
			"appfirewallxmlgenericlogsperprofilerate" => array (
				"cntrname"	=>	"appfirewallxmlgenericlogsperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewalltotallogperprofile" => Array(
				"cntrname"	=>	"appfirewalltotallogperprofile",
				"cntrdescr"	=>	"Total log messages",
				"tooltip"	=>	"Number of log messages generated by the application firewall on per profile basis",
			),
			"appfirewalllogperprofilerate" => array (
				"cntrname"	=>	"appfirewalllogperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallret4xxperprofile" => Array(
				"cntrname"	=>	"appfirewallret4xxperprofile",
				"cntrdescr"	=>	"HTTP Client Errors (4xx Resp)",
				"tooltip"	=>	"Number of requests returning HTTP 4xx from the backend server",
			),
			"appfirewallret4xxperprofilerate" => array (
				"cntrname"	=>	"appfirewallret4xxperprofilerate",
				"cntrdescr"	=>	"",
			),
			"appfirewallret5xxperprofile" => Array(
				"cntrname"	=>	"appfirewallret5xxperprofile",
				"cntrdescr"	=>	"HTTP Server Errors (5xx Resp)",
				"tooltip"	=>	"Number of requests returning HTTP 5xx from the backend server",
			),
			"appfirewallret5xxperprofilerate" => array (
				"cntrname"	=>	"appfirewallret5xxperprofilerate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Appfw profile Traffic Statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("appfirewallrequestsperprofile","appfirewallreqbytesperprofile","appfirewallresponsesperprofile","appfirewallresbytesperprofile","appfirewallabortsperprofile","appfirewallredirectsperprofile","appfirewalllongavgresptimeperprofile","appfirewallshortavgresptimeperprofile",),
				"charts"	=>	array(	array("appfw_requests_per_profile",),
								),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"HTML/XML Violation Statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("appfirewallviolstarturlperprofile","appfirewallvioldenyurlperprofile","appfirewallviolrefererheaderperprofile","appfirewallviolbufferoverflowperprofile","appfirewallviolcookieperprofile","appfirewallviolcsrftagperprofile","appfirewallviolxssperprofile","appfirewallviolsqlperprofile","appfirewallviolfieldformatperprofile","appfirewallviolfieldconsistencyperprofile","appfirewallviolcreditcardperprofile","appfirewallviolsafeobjectperprofile","appfirewallviolsignatureperprofile","appfirewallviolcontenttypeperprofile","appfirewallviolwellformednessviolationsperprofile","appfirewallviolxdosviolationsperprofile","appfirewallviolmsgvalviolationsperprofile","appfirewallviolwsiviolationsperprofile","appfirewallviolxmlsqlviolationsperprofile","appfirewallviolxmlxssviolationsperprofile","appfirewallviolxmlattachmentviolationsperprofile","appfirewallviolxmlsoapfaultviolationsperprofile","appfirewallviolxmlgenericviolationsperprofile","appfirewalltotalviolperprofile",),
				"charts"	=>	array(	array("appfw_violations_per_profile",),
								),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"HTML/XML Log Statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("appfirewalllogstarturlperprofile","appfirewalllogdenyurlperprofile","appfirewalllogrefererheaderperprofile","appfirewalllogbufferoverflowperprofile","appfirewalllogcookieperprofile","appfirewalllogcsrftagperprofile","appfirewalllogxssperprofile","appfirewalllogxformxssperprofile","appfirewalllogsqlperprofile","appfirewalllogxformsqlperprofile","appfirewalllogfieldformatperprofile","appfirewalllogfieldconsistencyperprofile","appfirewalllogcreditcardperprofile","appfirewallxformlogcreditcardperprofile","appfirewalllogsafeobjectperprofile","appfirewallviolsignatureperprofile","appfirewalllogcontenttypeperprofile","appfirewallwellformednesslogsperprofile","appfirewallxdoslogsperprofile","appfirewallmsgvallogsperprofile","appfirewallwsilogsperprofile","appfirewallxmlsqllogsperprofile","appfirewallxmlxsslogsperprofile","appfirewallxmlattachmentlogsperprofile","appfirewallxmlsoapfaultlogsperprofile","appfirewallxmlgenericlogsperprofile","appfirewalltotallogperprofile",),
			),
			Array(
				"dhID"	=>	4,
				"title"	=>	"Server Error Response Statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("appfirewallret4xxperprofile","appfirewallret5xxperprofile",),
			),
			Array(
				"dhID"	=>	5,
				"title"	=>	"Appfw profile Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("appfirewallrequestsperprofile","appfirewallreqbytesperprofile","appfirewallresponsesperprofile","appfirewallresbytesperprofile","appfirewallabortsperprofile","appfirewallredirectsperprofile","appfirewalllongavgresptimeperprofile","appfirewallshortavgresptimeperprofile",),
			),
		),
),


"statappfwpolicy" => Array(
	"cmddispname"		=>	"statappfwpolicy",
	"cmdname"		=>	"statappfwpolicy",
	"license"		=>	Array("NSKF1_APPFIREWALL_BIT_POS","NSKF1_APPFIREWALL_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"pipolicyhits" => Array(
				"cntrname"	=>	"pipolicyhits",
				"cntrdescr"	=>	"Policy hits",
				"tooltip"	=>	"Number of hits on the policy",
			),
			"pipolicyhitsrate" => array (
				"cntrname"	=>	"pipolicyhitsrate",
				"cntrdescr"	=>	"",
			),
			"pipolicyundefhits" => Array(
				"cntrname"	=>	"pipolicyundefhits",
				"cntrdescr"	=>	"Policy undef hits",
				"tooltip"	=>	"Number of undef hits on the policy",
			),
			"pipolicyundefhitsrate" => array (
				"cntrname"	=>	"pipolicyundefhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Application firewall Policy Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pipolicyhits","pipolicyundefhits",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Application firewall Policy(s) Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pipolicyhits","pipolicyundefhits",),
			),
		),
),


"statappfwpolicylabel" => Array(
	"cmddispname"		=>	"statappfwpolicylabel",
	"cmdname"		=>	"statappfwpolicylabel",
	"license"		=>	Array("NSKF1_APPFIREWALL_BIT_POS","NSKF1_APPFIREWALL_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"labelname" => Array(
				"argname"		=>	"labelname",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "labelname",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"pipolicylabelhits" => Array(
				"cntrname"	=>	"pipolicylabelhits",
				"cntrdescr"	=>	"Policy Label Hits",
				"tooltip"	=>	"Number of times policy label was invoked.",
			),
			"pipolicylabelhitsrate" => array (
				"cntrname"	=>	"pipolicylabelhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Application firewall Policylabel Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pipolicylabelhits",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Application firewall Policylabel(s) Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pipolicylabelhits",),
			),
		),
),


"statappfwpolicybinding" => Array(
	"cmddispname"		=>	"statappfwpolicybinding",
	"cmdname"		=>	"statappfwpolicybinding",
	"license"		=>	Array("NSKF1_APPFIREWALL_BIT_POS","NSKF1_APPFIREWALL_BIT_POS"),
	"cmddescr"	=>	"     ",
	"hidden"	=>	1,
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"pipolicybindinghits" => Array(
				"cntrname"	=>	"pipolicybindinghits",
				"cntrdescr"	=>	"Policy binding hits",
				"tooltip"	=>	"Number of hits on the policy on this binding",
			),
			"pipolicybindinghitsrate" => array (
				"cntrname"	=>	"pipolicybindinghitsrate",
				"cntrdescr"	=>	"",
			),
			"pipolicybindingundefhits" => Array(
				"cntrname"	=>	"pipolicybindingundefhits",
				"cntrdescr"	=>	"Policy binding undef hits",
				"tooltip"	=>	"Number of undef hits on the policy on this binding",
			),
			"pipolicybindingundefhitsrate" => array (
				"cntrname"	=>	"pipolicybindingundefhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Application firewall Binding Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pipolicybindinghits","pipolicybindingundefhits",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Application firewall Binding(s) Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pipolicybindinghits","pipolicybindingundefhits",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Application firewall Binding(s) Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"subent",
				"counters"	=>	array("pipolicybindinghits","pipolicybindingundefhits",),
			),
		),
),


"statappqoe" => Array(
	"cmddispname"		=>	"statappqoe",
	"cmdname"		=>	"statappqoe",
	"license"		=>	Array("NSKF2_APPQOS_BIT_POS","NSKF2_APPQOS_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"totinmemrsp" => Array(
				"cntrname"	=>	"totinmemrsp",
				"cntrdescr"	=>	"In-Memory responses sent",
				"tooltip"	=>	"Total in-memory responses sent from NS",
			),
			"inmemrsprate" => array (
				"cntrname"	=>	"inmemrsprate",
				"cntrdescr"	=>	"",
			),
			"totfaultycookies" => Array(
				"cntrname"	=>	"totfaultycookies",
				"cntrdescr"	=>	"Faulty cookies received",
				"tooltip"	=>	"Total faulty cookies received",
			),
			"faultycookiesrate" => array (
				"cntrname"	=>	"faultycookiesrate",
				"cntrdescr"	=>	"",
			),
			"totvalidcookies" => Array(
				"cntrname"	=>	"totvalidcookies",
				"cntrdescr"	=>	"Valid cookies received",
				"tooltip"	=>	"Total valid cookies received",
			),
			"validcookiesrate" => array (
				"cntrname"	=>	"validcookiesrate",
				"cntrdescr"	=>	"",
			),
			"tothighprireq" => Array(
				"cntrname"	=>	"tothighprireq",
				"cntrdescr"	=>	"High priority requests served",
				"tooltip"	=>	"Total Requests served from higher priority queue",
			),
			"highprireqrate" => array (
				"cntrname"	=>	"highprireqrate",
				"cntrdescr"	=>	"",
			),
			"totmediumprireq" => Array(
				"cntrname"	=>	"totmediumprireq",
				"cntrdescr"	=>	"Medium priority requests served",
				"tooltip"	=>	"Total Requests served from medium priority queue",
			),
			"mediumprireqrate" => array (
				"cntrname"	=>	"mediumprireqrate",
				"cntrdescr"	=>	"",
			),
			"totlowprireq" => Array(
				"cntrname"	=>	"totlowprireq",
				"cntrdescr"	=>	"Low priority requests served",
				"tooltip"	=>	"Total Requests served from low priority queue",
			),
			"lowprireqrate" => array (
				"cntrname"	=>	"lowprireqrate",
				"cntrdescr"	=>	"",
			),
			"totlowestprireq" => Array(
				"cntrname"	=>	"totlowestprireq",
				"cntrdescr"	=>	"Lowest(Surge) priority requests served",
				"tooltip"	=>	"Total Requests served from surge priority queue",
			),
			"lowestprireqrate" => array (
				"cntrname"	=>	"lowestprireqrate",
				"cntrdescr"	=>	"",
			),
			"totaltsvrsubfailed" => Array(
				"cntrname"	=>	"totaltsvrsubfailed",
				"cntrdescr"	=>	"Alt. server substitution failed",
				"tooltip"	=>	"Total number of times alternate server substitution failed",
			),
			"tsvrsubfailedrate" => array (
				"cntrname"	=>	"tsvrsubfailedrate",
				"cntrdescr"	=>	"",
			),
			"totdostrig" => Array(
				"cntrname"	=>	"totdostrig",
				"cntrdescr"	=>	"HDOS condition triggered",
				"tooltip"	=>	"Total number of times HDOS condition triggered",
			),
			"dostrigrate" => array (
				"cntrname"	=>	"dostrigrate",
				"cntrdescr"	=>	"",
			),
			"totdosqvalidcookies" => Array(
				"cntrname"	=>	"totdosqvalidcookies",
				"cntrdescr"	=>	"Valid DOSQ cookies received",
				"tooltip"	=>	"Total DOSQ valid cookies received",
			),
			"dosqvalidcookiesrate" => array (
				"cntrname"	=>	"dosqvalidcookiesrate",
				"cntrdescr"	=>	"",
			),
			"totdoshvalidcookies" => Array(
				"cntrname"	=>	"totdoshvalidcookies",
				"cntrdescr"	=>	"Valid DOSH cookies received",
				"tooltip"	=>	"Total DOSH valid cookies received",
			),
			"doshvalidcookiesrate" => array (
				"cntrname"	=>	"doshvalidcookiesrate",
				"cntrdescr"	=>	"",
			),
			"totsidvalidcookies" => Array(
				"cntrname"	=>	"totsidvalidcookies",
				"cntrdescr"	=>	"Valid SID cookies received",
				"tooltip"	=>	"Total SID valid cookies received",
			),
			"sidvalidcookiesrate" => array (
				"cntrname"	=>	"sidvalidcookiesrate",
				"cntrdescr"	=>	"",
			),
			"totonhvalidcookies" => Array(
				"cntrname"	=>	"totonhvalidcookies",
				"cntrdescr"	=>	"Valid ONH cookies received",
				"tooltip"	=>	"Total ONH valid cookies received",
			),
			"onhvalidcookiesrate" => array (
				"cntrname"	=>	"onhvalidcookiesrate",
				"cntrdescr"	=>	"",
			),
			"totpriqvalidcookies" => Array(
				"cntrname"	=>	"totpriqvalidcookies",
				"cntrdescr"	=>	"Valid PRIQ cookies received",
				"tooltip"	=>	"Total PRIQ valid cookies received",
			),
			"priqvalidcookiesrate" => array (
				"cntrname"	=>	"priqvalidcookiesrate",
				"cntrdescr"	=>	"",
			),
			"totdosqfaultycookies" => Array(
				"cntrname"	=>	"totdosqfaultycookies",
				"cntrdescr"	=>	"Faulty DOSQ cookies received",
				"tooltip"	=>	"Total DOSQ faulty cookies received",
			),
			"dosqfaultycookiesrate" => array (
				"cntrname"	=>	"dosqfaultycookiesrate",
				"cntrdescr"	=>	"",
			),
			"totdoshfaultycookies" => Array(
				"cntrname"	=>	"totdoshfaultycookies",
				"cntrdescr"	=>	"Faulty DOSH cookies received",
				"tooltip"	=>	"Total DOSH faulty cookies received",
			),
			"doshfaultycookiesrate" => array (
				"cntrname"	=>	"doshfaultycookiesrate",
				"cntrdescr"	=>	"",
			),
			"totsidfaultycookies" => Array(
				"cntrname"	=>	"totsidfaultycookies",
				"cntrdescr"	=>	"Faulty SID cookies received",
				"tooltip"	=>	"Total SID faulty cookies received",
			),
			"sidfaultycookiesrate" => array (
				"cntrname"	=>	"sidfaultycookiesrate",
				"cntrdescr"	=>	"",
			),
			"totonhfaultycookies" => Array(
				"cntrname"	=>	"totonhfaultycookies",
				"cntrdescr"	=>	"Faulty ONH cookies received",
				"tooltip"	=>	"Total ONH faulty cookies received",
			),
			"onhfaultycookiesrate" => array (
				"cntrname"	=>	"onhfaultycookiesrate",
				"cntrdescr"	=>	"",
			),
			"totpriqfaultycookies" => Array(
				"cntrname"	=>	"totpriqfaultycookies",
				"cntrdescr"	=>	"Faulty PRIQ cookies received",
				"tooltip"	=>	"Total PRIQ faulty cookies received",
			),
			"priqfaultycookiesrate" => array (
				"cntrname"	=>	"priqfaultycookiesrate",
				"cntrdescr"	=>	"",
			),
			"totpriembedlinks" => Array(
				"cntrname"	=>	"totpriembedlinks",
				"cntrdescr"	=>	"Requests for valid embedded links",
				"tooltip"	=>	"Total requests for valid embedded links",
			),
			"priembedlinksrate" => array (
				"cntrname"	=>	"priembedlinksrate",
				"cntrdescr"	=>	"",
			),
			"totsessreq" => Array(
				"cntrname"	=>	"totsessreq",
				"cntrdescr"	=>	"Valid SIDQ req. within session",
				"tooltip"	=>	"Total valid SIDQ requests within session",
			),
			"sessreqrate" => array (
				"cntrname"	=>	"sessreqrate",
				"cntrdescr"	=>	"",
			),
			"totaltcntreq" => Array(
				"cntrname"	=>	"totaltcntreq",
				"cntrdescr"	=>	"Requests for alternate contents",
				"tooltip"	=>	"Total requests for alternate contents",
			),
			"tcntreqrate" => array (
				"cntrname"	=>	"tcntreqrate",
				"cntrdescr"	=>	"",
			),
			"totgetinmemrsp" => Array(
				"cntrname"	=>	"totgetinmemrsp",
				"cntrdescr"	=>	"In-Memory GET responses sent",
				"tooltip"	=>	"Total in-memory GET responses sent from NS",
			),
			"getinmemrsprate" => array (
				"cntrname"	=>	"getinmemrsprate",
				"cntrdescr"	=>	"",
			),
			"totpostinmemrsp" => Array(
				"cntrname"	=>	"totpostinmemrsp",
				"cntrdescr"	=>	"In-Memory POST responses sent",
				"tooltip"	=>	"Total in-memory POST responses sent from NS",
			),
			"postinmemrsprate" => array (
				"cntrname"	=>	"postinmemrsprate",
				"cntrdescr"	=>	"",
			),
			"totpostinmemrspbytes" => Array(
				"cntrname"	=>	"totpostinmemrspbytes",
				"cntrdescr"	=>	"In-Memory response bytes sent",
				"tooltip"	=>	"Total in-memory response bytes sent from NS",
			),
			"postinmemrspbytesrate" => array (
				"cntrname"	=>	"postinmemrspbytesrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"text" => "AppQoE Statistics - Summary",
				"flag" => "Summary",
				"key" => "default",
			),
			Array(
				"dhID"	=>	1,
				"title"	=>	"Queuing Stats:",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("tothighprireq","totmediumprireq","totlowprireq","totlowestprireq",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Cookie Stats:",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("totvalidcookies","totfaultycookies",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Alternate Response Stats:",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("totinmemrsp","totaltsvrsubfailed","totdostrig",),
			),
			Array(
				"dhID"	=>	4,
				"title"	=>	"Queuing Stats:",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("tothighprireq","totmediumprireq","totlowprireq","totlowestprireq",),
			),
			Array(
				"text" => "-----------------------",
				"flag" => "Detail",
				"key" => "detail",
			),
			Array(
				"dhID"	=>	5,
				"title"	=>	"Cookie Stats:",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("totvalidcookies","totdosqvalidcookies","totdoshvalidcookies","totsidvalidcookies","totonhvalidcookies","totpriqvalidcookies","totfaultycookies","totdosqfaultycookies","totdoshfaultycookies","totsidfaultycookies","totonhfaultycookies","totpriqfaultycookies","totpriembedlinks","totsessreq",),
			),
			Array(
				"text" => "-----------------------",
				"flag" => "Detail",
				"key" => "detail",
			),
			Array(
				"dhID"	=>	6,
				"title"	=>	"Alternate Response Stats:",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("totaltcntreq","totinmemrsp","totgetinmemrsp","totpostinmemrsp","totpostinmemrspbytes","totdostrig","totaltsvrsubfailed",),
			),
		),
),


"statappqoepolicy" => Array(
	"cmddispname"		=>	"statappqoepolicy",
	"cmdname"		=>	"statappqoepolicy",
	"license"		=>	Array("NSKF2_APPQOS_BIT_POS","NSKF2_APPQOS_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"qosavgserverttfb" => Array(
				"cntrname"	=>	"qosavgserverttfb",
				"cntrdescr"	=>	"Average Server TTFB",
				"tooltip"	=>	"Average Server Time-To-First-Byte in milliseconds calculated for this AppQoE policy.",
			),
			"qosavgserverttfbrate" => array (
				"cntrname"	=>	"qosavgserverttfbrate",
				"cntrdescr"	=>	"",
			),
			"qosavgserverttlb" => Array(
				"cntrname"	=>	"qosavgserverttlb",
				"cntrdescr"	=>	"Average Server TTLB",
				"tooltip"	=>	"Average Server Time-To-Last-Byte in milliseconds calculated for this AppQoE policy.",
			),
			"qosavgserverttlbrate" => array (
				"cntrname"	=>	"qosavgserverttlbrate",
				"cntrdescr"	=>	"",
			),
			"qosavgclientttlb" => Array(
				"cntrname"	=>	"qosavgclientttlb",
				"cntrdescr"	=>	"Average Client TTLB",
				"tooltip"	=>	"Average Client Time-To-Last-Byte in milliseconds calculated for this AppQoE policy.",
			),
			"qosavgclientttlbrate" => array (
				"cntrname"	=>	"qosavgclientttlbrate",
				"cntrdescr"	=>	"",
			),
			"totthroughput" => Array(
				"cntrname"	=>	"totthroughput",
				"cntrdescr"	=>	"ThroughPut(Kbps)",
				"tooltip"	=>	"Throughput in Kbps calculated on this AppQoE policy",
			),
			"throughputrate" => array (
				"cntrname"	=>	"throughputrate",
				"cntrdescr"	=>	"",
			),
			"totsvrlinkedto" => Array(
				"cntrname"	=>	"totsvrlinkedto",
				"cntrdescr"	=>	"Server TCP connections",
				"tooltip"	=>	"Total number of server connections that were established through this AppQoE Policy",
			),
			"svrlinkedtorate" => array (
				"cntrname"	=>	"svrlinkedtorate",
				"cntrdescr"	=>	"",
			),
			"totcltrequests" => Array(
				"cntrname"	=>	"totcltrequests",
				"cntrdescr"	=>	"Client TCP connections",
				"tooltip"	=>	"Total number of client connections that were requested through this AppQoE Policy",
			),
			"cltrequestsrate" => array (
				"cntrname"	=>	"cltrequestsrate",
				"cntrdescr"	=>	"",
			),
			"totrequests" => Array(
				"cntrname"	=>	"totrequests",
				"cntrdescr"	=>	"Requests received",
				"tooltip"	=>	"Total number of requests that were requested through this AppQoE policy",
			),
			"requestsrate" => array (
				"cntrname"	=>	"requestsrate",
				"cntrdescr"	=>	"",
			),
			"totrequestbytes" => Array(
				"cntrname"	=>	"totrequestbytes",
				"cntrdescr"	=>	"Requests bytes",
				"tooltip"	=>	"Total number of requests bytes that were requested through this AppQoE policy",
			),
			"requestbytesrate" => array (
				"cntrname"	=>	"requestbytesrate",
				"cntrdescr"	=>	"",
			),
			"totresponse" => Array(
				"cntrname"	=>	"totresponse",
				"cntrdescr"	=>	"Responses received",
				"tooltip"	=>	"Total number of responses received by this AppQoE policy",
			),
			"responserate" => array (
				"cntrname"	=>	"responserate",
				"cntrdescr"	=>	"",
			),
			"totresponsebytes" => Array(
				"cntrname"	=>	"totresponsebytes",
				"cntrdescr"	=>	"Response bytes",
				"tooltip"	=>	"Total number of response bytes received by this AppQoE policy",
			),
			"responsebytesrate" => array (
				"cntrname"	=>	"responsebytesrate",
				"cntrdescr"	=>	"",
			),
			"totjssent" => Array(
				"cntrname"	=>	"totjssent",
				"cntrdescr"	=>	"Alternate responses sent",
				"tooltip"	=>	"Total number of in-memory responses sent instead of expected responses through this AppQoE policy",
			),
			"jssentrate" => array (
				"cntrname"	=>	"jssentrate",
				"cntrdescr"	=>	"",
			),
			"totjsbytessent" => Array(
				"cntrname"	=>	"totjsbytessent",
				"cntrdescr"	=>	"Alternate responses bytes sent",
				"tooltip"	=>	"Total bytes of in-memory responses sent through this AppQoE policy",
			),
			"jsbytessentrate" => array (
				"cntrname"	=>	"jsbytessentrate",
				"cntrdescr"	=>	"",
			),
			"pipolicyhits" => Array(
				"cntrname"	=>	"pipolicyhits",
				"cntrdescr"	=>	"Policy hits",
				"tooltip"	=>	"Number of hits on the policy",
			),
			"pipolicyhitsrate" => array (
				"cntrname"	=>	"pipolicyhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"text" => "AppQoE Cumulative Policy Statistics Summary",
				"flag" => "Byname",
				"key" => "byname",
			),
			Array(
				"dhID"	=>	1,
				"title"	=>	"Cumulative Policy Stats:",
				"header"	=>	array("Counter","Rate(/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("totsvrlinkedto","totcltrequests","totrequests","totrequestbytes","totresponse","totresponsebytes","totthroughput","totjssent","totjsbytessent",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Timing Stats:",
				"header"	=>	array("Counter","Rate(/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("qosavgclientttlb","qosavgserverttlb","qosavgserverttfb",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"AppQoE Policy Statistics Summary",
				"header"	=>	array(" ","Hits","Rate(/s)",),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pipolicyhits",),
			),
		),
),


"stataudit" => Array(
	"cmddispname"		=>	"stataudit",
	"cmdname"		=>	"stataudit",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"auditsyslogmsgsent" => Array(
				"cntrname"	=>	"auditsyslogmsgsent",
				"cntrdescr"	=>	"Logs sent to syslog server(UDP)",
				"tooltip"	=>	"Syslog messages sent to the syslog server(s) over UDP.",
			),
			"auditsyslogmsgsentrate" => array (
				"cntrname"	=>	"auditsyslogmsgsentrate",
				"cntrdescr"	=>	"",
			),
			"auditsyslogmsggen" => Array(
				"cntrname"	=>	"auditsyslogmsggen",
				"cntrdescr"	=>	"Audit log messages generated",
				"tooltip"	=>	"Syslog messages about to be sent to the syslog server.",
			),
			"auditsyslogmsggenrate" => array (
				"cntrname"	=>	"auditsyslogmsggenrate",
				"cntrdescr"	=>	"",
			),
			"auditsyslogmsgsenttcp" => Array(
				"cntrname"	=>	"auditsyslogmsgsenttcp",
				"cntrdescr"	=>	"Logs sent to syslog server(TCP)",
				"tooltip"	=>	"Syslog messages sent to the syslog server(s) over TCP.",
			),
			"auditsyslogmsgsenttcprate" => array (
				"cntrname"	=>	"auditsyslogmsgsenttcprate",
				"cntrdescr"	=>	"",
			),
			"auditnsballocfail" => Array(
				"cntrname"	=>	"auditnsballocfail",
				"cntrdescr"	=>	"NAT allocation failed",
				"tooltip"	=>	"NAT allocation failed.",
			),
			"auditnsballocfailrate" => array (
				"cntrname"	=>	"auditnsballocfailrate",
				"cntrdescr"	=>	"",
			),
			"auditlog32errsyslogallocnsbfail" => Array(
				"cntrname"	=>	"auditlog32errsyslogallocnsbfail",
				"cntrdescr"	=>	"Nsb allocation failed",
				"tooltip"	=>	"Nsb allocation failed.",
			),
			"auditlog32errsyslogallocnsbfailrate" => array (
				"cntrname"	=>	"auditlog32errsyslogallocnsbfailrate",
				"cntrdescr"	=>	"",
			),
			"auditmemallocfail" => Array(
				"cntrname"	=>	"auditmemallocfail",
				"cntrdescr"	=>	"Memory allocation failed",
				"tooltip"	=>	"Failures in allocation of Access Gateway context structure. When an Access Gateway session is established, the NetScaler creates an internal context structure , which identifies the user and the IP address from which the user has logged in.",
			),
			"auditmemallocfailrate" => array (
				"cntrname"	=>	"auditmemallocfailrate",
				"cntrdescr"	=>	"",
			),
			"auditportallocfail" => Array(
				"cntrname"	=>	"auditportallocfail",
				"cntrdescr"	=>	"Port allocation failed",
				"tooltip"	=>	"Number of times the NetScaler failed to allocate a port when sending a syslog message to the syslog server(s).",
			),
			"auditportallocfailrate" => array (
				"cntrname"	=>	"auditportallocfailrate",
				"cntrdescr"	=>	"",
			),
			"auditcontextnotfound" => Array(
				"cntrname"	=>	"auditcontextnotfound",
				"cntrdescr"	=>	"Context not found",
				"tooltip"	=>	"Failures in finding the context structure for an Access Gateway session during attempts to send session-specific audit messages.<br><br><br><br>During an Access Gateway session, audit messages related to the session are queued up in the auditlog buffer for transmission to the audit log server(s). If the session is killed before the messages are sent, the context structure allocated at session creation is removed. This structure is needed for sending the queued auditlog messages. If it is not found, this counter is incremented.",
			),
			"auditcontextnotfoundrate" => array (
				"cntrname"	=>	"auditcontextnotfoundrate",
				"cntrdescr"	=>	"",
			),
			"nsbchainallocfail" => Array(
				"cntrname"	=>	"nsbchainallocfail",
				"cntrdescr"	=>	"Nsb chain allocation failed",
				"tooltip"	=>	"Nsb Chain allocation failed.",
			),
			"nsbchainallocfailrate" => array (
				"cntrname"	=>	"nsbchainallocfailrate",
				"cntrdescr"	=>	"",
			),
			"clientconnfail" => Array(
				"cntrname"	=>	"clientconnfail",
				"cntrdescr"	=>	"Client connect failed",
				"tooltip"	=>	"Failures in establishment of a connection between the NetScaler and the auditserver tool (the Netscaler's custom logging tool).",
			),
			"clientconnfailrate" => array (
				"cntrname"	=>	"clientconnfailrate",
				"cntrdescr"	=>	"",
			),
			"flushcmdcnt" => Array(
				"cntrname"	=>	"flushcmdcnt",
				"cntrdescr"	=>	"MP buffer flush command count",
				"tooltip"	=>	"Auditlog buffer flushes. In a multiprocessor NetScaler, both the main processor and the co-processor can generate auditlog messages and fill up the auditlog buffers. But only the primary processor can free up the buffers by sending auditlog messages to the auditlog server(s). The number of auditlog buffers is fixed. If the co-processor detects that all the auditlog buffers are full, it issues a flush command to the main processor.",
			),
			"flushcmdcntrate" => array (
				"cntrname"	=>	"flushcmdcntrate",
				"cntrdescr"	=>	"",
			),
			"systcpconnfail" => Array(
				"cntrname"	=>	"systcpconnfail",
				"cntrdescr"	=>	"TCP connect failed for syslog",
				"tooltip"	=>	"Failures in establishment of a connection between the NetScaler and the syslog server.",
			),
			"systcpconnfailrate" => array (
				"cntrname"	=>	"systcpconnfailrate",
				"cntrdescr"	=>	"",
			),
			"logunsentlbsys" => Array(
				"cntrname"	=>	"logunsentlbsys",
				"cntrdescr"	=>	"Logs unsent to LB syslog server",
				"tooltip"	=>	"Total auditlog messages which are not delivered to load balanced syslog servers",
			),
			"logunsentlbsysrate" => array (
				"cntrname"	=>	"logunsentlbsysrate",
				"cntrdescr"	=>	"",
			),
			"logsdropped" => Array(
				"cntrname"	=>	"logsdropped",
				"cntrdescr"	=>	"Logs dropped(max hold limit)",
				"tooltip"	=>	"Total number of log messages dropped by NetScaler after max hold limit is reached",
			),
			"logsdroppedrate" => array (
				"cntrname"	=>	"logsdroppedrate",
				"cntrdescr"	=>	"",
			),
			"logsdroppedtxminnsbs" => Array(
				"cntrname"	=>	"logsdroppedtxminnsbs",
				"cntrdescr"	=>	"Logs dropped(TX min NSBS)",
				"tooltip"	=>	"Total number of log messages dropped by NetScaler when NSBQ length is less than TX min NSBs",
			),
			"logsdroppedtxminnsbsrate" => array (
				"cntrname"	=>	"logsdroppedtxminnsbsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"",
				"header"	=>	array("","Total",),
				"format"	=>	"1C",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("auditsyslogmsgsent","auditsyslogmsggen",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"",
				"header"	=>	array("","Total",),
				"format"	=>	"1C",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("auditsyslogmsgsent","auditsyslogmsgsenttcp","auditsyslogmsggen",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Audit log Errors",
				"header"	=>	array("","Total",),
				"format"	=>	"1C",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("auditnsballocfail","auditlog32errsyslogallocnsbfail","auditmemallocfail","auditportallocfail","auditcontextnotfound","nsbchainallocfail","clientconnfail","flushcmdcnt","systcpconnfail","logunsentlbsys","logsdropped","logsdroppedtxminnsbs",),
			),
		),
),


"statauthenticationpolicy" => Array(
	"cmddispname"		=>	"statauthenticationpolicy",
	"cmdname"		=>	"statauthenticationpolicy",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"pipolicyhits" => Array(
				"cntrname"	=>	"pipolicyhits",
				"cntrdescr"	=>	"Policy hits",
				"tooltip"	=>	"Number of hits on the policy",
			),
			"pipolicyhitsrate" => array (
				"cntrname"	=>	"pipolicyhitsrate",
				"cntrdescr"	=>	"",
			),
			"pipolicyundefhits" => Array(
				"cntrname"	=>	"pipolicyundefhits",
				"cntrdescr"	=>	"Policy undef hits",
				"tooltip"	=>	"Number of undef hits on the policy",
			),
			"pipolicyundefhitsrate" => array (
				"cntrname"	=>	"pipolicyundefhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Authentication Policy Statistics",
				"header"	=>	array(" ","Hits","Rate(/s)","UndefHits","Rate(/s)",),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pipolicyhits","pipolicyundefhits",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Authentication Policy(s) Statistics",
				"header"	=>	array(" ","Hits","Rate(/s)","UndefHits","Rate(/s)",),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pipolicyhits","pipolicyundefhits",),
			),
		),
),


"statauthenticationvserver" => Array(
	"cmddispname"		=>	"statauthenticationvserver",
	"enable"            =>      "1",
	"cmdname"		=>	"statauthenticationvserver",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"primaryipaddress" => Array(
				"cntrname"	=>	"primaryipaddress",
				"cntrdescr"	=>	"IP address",
				"tooltip"	=>	"The IP address on which the service is running.",
				"type"		=>	"NS_VALIPSTR",
			),
			"primaryport" => Array(
				"cntrname"	=>	"primaryport",
				"cntrdescr"	=>	"Port",
				"tooltip"	=>	"The port on which the service is running.",
				"type"		=>	"NS_VALPORT",
			),
			"type" => Array(
				"cntrname"	=>	"type",
				"cntrdescr"	=>	"Vserver protocol",
				"tooltip"	=>	"Protocol associated with the vserver",
				"type"		=>	"NS_VALENUM",
			),
			"state" => Array(
				"cntrname"	=>	"state",
				"cntrdescr"	=>	"State",
				"tooltip"	=>	"Current state of the server. Possible values are UP, DOWN, UNKNOWN, OFS(Out of Service), TROFS(Transition Out of Service), TROFS_DOWN(Down When going Out of Service)",
				"type"		=>	"NS_VALENUM",
			),
			"totalrequests" => Array(
				"cntrname"	=>	"totalrequests",
				"cntrdescr"	=>	"Requests",
				"tooltip"	=>	"Total number of requests received on this service or virtual server. (This applies to HTTP/SSL services and servers.)",
			),
			"requestsrate" => array (
				"cntrname"	=>	"requestsrate",
				"cntrdescr"	=>	"",
			),
			"totalresponses" => Array(
				"cntrname"	=>	"totalresponses",
				"cntrdescr"	=>	"Responses",
				"tooltip"	=>	"Number of responses received on this service or virtual server. (This applies to HTTP/SSL services and servers.)",
			),
			"responsesrate" => array (
				"cntrname"	=>	"responsesrate",
				"cntrdescr"	=>	"",
			),
			"totalrequestbytes" => Array(
				"cntrname"	=>	"totalrequestbytes",
				"cntrdescr"	=>	"Request bytes",
				"tooltip"	=>	"Total number of request bytes received on this service or virtual server.",
			),
			"requestbytesrate" => array (
				"cntrname"	=>	"requestbytesrate",
				"cntrdescr"	=>	"",
			),
			"totalresponsebytes" => Array(
				"cntrname"	=>	"totalresponsebytes",
				"cntrdescr"	=>	"Response bytes",
				"tooltip"	=>	"Number of response bytes received by this service or virtual server.",
			),
			"responsebytesrate" => array (
				"cntrname"	=>	"responsebytesrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Vserver Summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("primaryipaddress","primaryport","type","state",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"VServer Stats:",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("totalrequests","totalresponses","totalrequestbytes","totalresponsebytes",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Vserver(s) Summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("primaryipaddress","primaryport","type","state","totalrequests",),
			),
		),
),


"statauthenticationsamlidppolicy" => Array(
	"cmddispname"		=>	"statauthenticationsamlidppolicy",
	"cmdname"		=>	"statauthenticationsamlidppolicy",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"pipolicyhits" => Array(
				"cntrname"	=>	"pipolicyhits",
				"cntrdescr"	=>	"Policy hits",
				"tooltip"	=>	"Number of hits on the policy",
			),
			"pipolicyhitsrate" => array (
				"cntrname"	=>	"pipolicyhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"IdP Policy Statistics",
				"header"	=>	array(" ","Hits","Rate(/s)",),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pipolicyhits",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"IdP Policy(s) Statistics",
				"header"	=>	array(" ","Hits","Rate(/s)",),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pipolicyhits",),
			),
		),
),


"statauthenticationpolicylabel" => Array(
	"cmddispname"		=>	"statauthenticationpolicylabel",
	"cmdname"		=>	"statauthenticationpolicylabel",
	"cmddescr"	=>	"     ",
	"bindings"	=>	1,
	"args"	=>
		Array (
			"labelname" => Array(
				"argname"		=>	"labelname",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "labelname",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"pipolicylabelhits" => Array(
				"cntrname"	=>	"pipolicylabelhits",
				"cntrdescr"	=>	"Policy Label Hits",
				"tooltip"	=>	"Number of times policy label was invoked.",
			),
			"pipolicylabelhitsrate" => array (
				"cntrname"	=>	"pipolicylabelhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Authentication Policylabel Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pipolicylabelhits",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Authentication Policylabel(s) Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pipolicylabelhits",),
			),
		),
),


"statauthenticationpolicybinding" => Array(
	"cmddispname"		=>	"statauthenticationpolicybinding",
	"cmdname"		=>	"statauthenticationpolicybinding",
	"cmddescr"	=>	"     ",
	"hidden"	=>	1,
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"pipolicybindinghits" => Array(
				"cntrname"	=>	"pipolicybindinghits",
				"cntrdescr"	=>	"Policy binding hits",
				"tooltip"	=>	"Number of hits on the policy on this binding",
			),
			"pipolicybindinghitsrate" => array (
				"cntrname"	=>	"pipolicybindinghitsrate",
				"cntrdescr"	=>	"",
			),
			"pipolicybindingundefhits" => Array(
				"cntrname"	=>	"pipolicybindingundefhits",
				"cntrdescr"	=>	"Policy binding undef hits",
				"tooltip"	=>	"Number of undef hits on the policy on this binding",
			),
			"pipolicybindingundefhitsrate" => array (
				"cntrname"	=>	"pipolicybindingundefhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Authentication Binding Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pipolicybindinghits","pipolicybindingundefhits",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Authentication Binding(s) Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pipolicybindinghits","pipolicybindingundefhits",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Authentication Binding(s) Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"subent",
				"counters"	=>	array("pipolicybindinghits","pipolicybindingundefhits",),
			),
		),
),


"statauthenticationloginschemapolicy" => Array(
	"cmddispname"		=>	"statauthenticationloginschemapolicy",
	"cmdname"		=>	"statauthenticationloginschemapolicy",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"pipolicyhits" => Array(
				"cntrname"	=>	"pipolicyhits",
				"cntrdescr"	=>	"Policy hits",
				"tooltip"	=>	"Number of hits on the policy",
			),
			"pipolicyhitsrate" => array (
				"cntrname"	=>	"pipolicyhitsrate",
				"cntrdescr"	=>	"",
			),
			"pipolicyundefhits" => Array(
				"cntrname"	=>	"pipolicyundefhits",
				"cntrdescr"	=>	"Policy undef hits",
				"tooltip"	=>	"Number of undef hits on the policy",
			),
			"pipolicyundefhitsrate" => array (
				"cntrname"	=>	"pipolicyundefhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"LoginSchema Policy Statistics",
				"header"	=>	array("Name","Hits","Rate(/s)","UndefHits","Rate(/s)",),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pipolicyhits","pipolicyundefhits",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"LoginSchema Policy(s) Statistics",
				"header"	=>	array("Name","Hits","Rate(/s)","UndefHits","Rate(/s)",),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pipolicyhits","pipolicyundefhits",),
			),
		),
),


"statauthorizationpolicylabel" => Array(
	"cmddispname"		=>	"statauthorizationpolicylabel",
	"cmdname"		=>	"statauthorizationpolicylabel",
	"cmddescr"	=>	"     ",
	"bindings"	=>	1,
	"args"	=>
		Array (
			"labelname" => Array(
				"argname"		=>	"labelname",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "labelname",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"pipolicylabelhits" => Array(
				"cntrname"	=>	"pipolicylabelhits",
				"cntrdescr"	=>	"Policy Label Hits",
				"tooltip"	=>	"Number of times policy label was invoked.",
			),
			"pipolicylabelhitsrate" => array (
				"cntrname"	=>	"pipolicylabelhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Authorization Policylabel Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pipolicylabelhits",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Authorization Policylabel(s) Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pipolicylabelhits",),
			),
		),
),


"statauthorizationpolicybinding" => Array(
	"cmddispname"		=>	"statauthorizationpolicybinding",
	"cmdname"		=>	"statauthorizationpolicybinding",
	"cmddescr"	=>	"     ",
	"hidden"	=>	1,
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"pipolicybindinghits" => Array(
				"cntrname"	=>	"pipolicybindinghits",
				"cntrdescr"	=>	"Policy binding hits",
				"tooltip"	=>	"Number of hits on the policy on this binding",
			),
			"pipolicybindinghitsrate" => array (
				"cntrname"	=>	"pipolicybindinghitsrate",
				"cntrdescr"	=>	"",
			),
			"pipolicybindingundefhits" => Array(
				"cntrname"	=>	"pipolicybindingundefhits",
				"cntrdescr"	=>	"Policy binding undef hits",
				"tooltip"	=>	"Number of undef hits on the policy on this binding",
			),
			"pipolicybindingundefhitsrate" => array (
				"cntrname"	=>	"pipolicybindingundefhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Authorization Binding Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pipolicybindinghits","pipolicybindingundefhits",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Authorization Binding(s) Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pipolicybindinghits","pipolicybindingundefhits",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Authorization Binding(s) Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"subent",
				"counters"	=>	array("pipolicybindinghits","pipolicybindingundefhits",),
			),
		),
),


"statautoscalepolicy" => Array(
	"cmddispname"		=>	"statautoscalepolicy",
	"cmdname"		=>	"statautoscalepolicy",
	"license"		=>	Array("NSKF1_LOADBALANCE_BIT_POS","NSKF1_LOADBALANCE_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"pipolicyhits" => Array(
				"cntrname"	=>	"pipolicyhits",
				"cntrdescr"	=>	"Policy hits",
				"tooltip"	=>	"Number of hits on the policy",
			),
			"pipolicyhitsrate" => array (
				"cntrname"	=>	"pipolicyhitsrate",
				"cntrdescr"	=>	"",
			),
			"pipolicyundefhits" => Array(
				"cntrname"	=>	"pipolicyundefhits",
				"cntrdescr"	=>	"Policy undef hits",
				"tooltip"	=>	"Number of undef hits on the policy",
			),
			"pipolicyundefhitsrate" => array (
				"cntrname"	=>	"pipolicyundefhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Autoscale Policy Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pipolicyhits","pipolicyundefhits",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Autoscale Policy(s) Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pipolicyhits","pipolicyundefhits",),
			),
		),
),


"statca" => Array(
	"cmddispname"		=>	"statca",
	"cmdname"		=>	"statca",
	"license"		=>	Array("NSKF2_TCACHE_BIT_POS","NSKF2_TCACHE_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"caaunindentifiedbyteshr" => Array(
				"cntrname"	=>	"caaunindentifiedbyteshr",
				"cntrdescr"	=>	"unindentified device bytes ratio",
				"tooltip"	=>	"This tells the hit ratio of unindentified bytes",
			),
			"caiosbyteshr" => Array(
				"cntrname"	=>	"caiosbyteshr",
				"cntrdescr"	=>	"Ios bytes ratio",
				"tooltip"	=>	"This tells the hit ratio of IOS bytes",
			),
			"caotherbyteshr" => Array(
				"cntrname"	=>	"caotherbyteshr",
				"cntrdescr"	=>	"Other device bytes ratio",
				"tooltip"	=>	"This tells the hit ratio of Other device",
			),
			"calaptpdsktpbyteshr" => Array(
				"cntrname"	=>	"calaptpdsktpbyteshr",
				"cntrdescr"	=>	"Laptop/desktop bytes ratio",
				"tooltip"	=>	"This tells the hit ratio of laptop_desktop bytes",
			),
			"caandroidbyteshr" => Array(
				"cntrname"	=>	"caandroidbyteshr",
				"cntrdescr"	=>	"Android bytes ratio",
				"tooltip"	=>	"This tells the hit ratio of 3GP bytes",
			),
			"ca3gpvidcachebyteshr" => Array(
				"cntrname"	=>	"ca3gpvidcachebyteshr",
				"cntrdescr"	=>	"3GP bytes ratio",
				"tooltip"	=>	"This tells the hit ratio of 3GP bytes",
			),
			"camp4vidcachebyteshr" => Array(
				"cntrname"	=>	"camp4vidcachebyteshr",
				"cntrdescr"	=>	"MP4 bytes ratio",
				"tooltip"	=>	"This tells the hit ratio of MP4 bytes",
			),
			"caflvvidcachebyteshr" => Array(
				"cntrname"	=>	"caflvvidcachebyteshr",
				"cntrdescr"	=>	"FLV bytes ratio",
				"tooltip"	=>	"This tells the hit ratio of FLV bytes",
			),
			"caaacaudiocachebyteshr" => Array(
				"cntrname"	=>	"caaacaudiocachebyteshr",
				"cntrdescr"	=>	"AAC bytes ratio",
				"tooltip"	=>	"This tells the hit ratio AAC bytes served",
			),
			"caadtsaudiocachebyteshr" => Array(
				"cntrname"	=>	"caadtsaudiocachebyteshr",
				"cntrdescr"	=>	"ADTS bytes ratio",
				"tooltip"	=>	"This tells the byte hit ratio of ADTS bytes",
			),
			"caaplelivestrmngplvidcachebyteshr" => Array(
				"cntrname"	=>	"caaplelivestrmngplvidcachebyteshr",
				"cntrdescr"	=>	"AppleLive PL bytes ratio",
				"tooltip"	=>	"This tells the byte hit ratio of AppleLive Playlist bytes",
			),
			"caapplelivestrmngvidcachebyteshr" => Array(
				"cntrname"	=>	"caapplelivestrmngvidcachebyteshr",
				"cntrdescr"	=>	"AppleLiveStream bytes ratio",
				"tooltip"	=>	"This tells the total number of AppleLive bytes",
			),
			"camsftsmthstrmngplvidcabyteshr" => Array(
				"cntrname"	=>	"camsftsmthstrmngplvidcabyteshr",
				"cntrdescr"	=>	"MSFT SmoothStreamPL bytes ratio",
				"tooltip"	=>	"This tells the byte hit ratio of MicrosoftSmoothStreaming Playlist bytes",
			),
			"camsftsmthstrmngvidcabyteshr" => Array(
				"cntrname"	=>	"camsftsmthstrmngvidcabyteshr",
				"cntrdescr"	=>	"MSFT SmoothStream bytes ratio",
				"tooltip"	=>	"This tells the Bytes hit ratio of MicrosoftSmoothStreaming bytes .",
			),
			"caunidentifiedhr" => Array(
				"cntrname"	=>	"caunidentifiedhr",
				"cntrdescr"	=>	"unidentified devices",
				"tooltip"	=>	"This tells the hit ratio of android requests",
			),
			"caioshr" => Array(
				"cntrname"	=>	"caioshr",
				"cntrdescr"	=>	"IOS",
				"tooltip"	=>	"This tells the hit ratio of ios requests",
			),
			"caotherhr" => Array(
				"cntrname"	=>	"caotherhr",
				"cntrdescr"	=>	"Other device",
				"tooltip"	=>	"This tells the hit ratio of other mobile device requests",
			),
			"calaptopdesktphr" => Array(
				"cntrname"	=>	"calaptopdesktphr",
				"cntrdescr"	=>	"Laptop/desktop",
				"tooltip"	=>	"This tells the hit ratio of laptop/desktop requests",
			),
			"caandroidhr" => Array(
				"cntrname"	=>	"caandroidhr",
				"cntrdescr"	=>	"Android",
				"tooltip"	=>	"This tells the hit ratio of android requests",
			),
			"ca3gpvidhr" => Array(
				"cntrname"	=>	"ca3gpvidhr",
				"cntrdescr"	=>	"3GP",
				"tooltip"	=>	"This tells the total number of 3GP requests",
			),
			"cacmp4vidhr" => Array(
				"cntrname"	=>	"cacmp4vidhr",
				"cntrdescr"	=>	"MP4",
				"tooltip"	=>	"This tells the hit ratio of MP4 requests",
			),
			"cacflvvidhr" => Array(
				"cntrname"	=>	"cacflvvidhr",
				"cntrdescr"	=>	"FLV",
				"tooltip"	=>	"This tells the hit ratio of FLV requests",
			),
			"cacaacaudiohr" => Array(
				"cntrname"	=>	"cacaacaudiohr",
				"cntrdescr"	=>	"AAC",
				"tooltip"	=>	"This tells the hit ratio of AAC requests",
			),
			"cacadtsaudiohr" => Array(
				"cntrname"	=>	"cacadtsaudiohr",
				"cntrdescr"	=>	"ADTS",
				"tooltip"	=>	"This tells the Hit Ratio of ADTS requests",
			),
			"cacapplelivestreamingplaylistvidhr" => Array(
				"cntrname"	=>	"cacapplelivestreamingplaylistvidhr",
				"cntrdescr"	=>	"AppleLive PL",
				"tooltip"	=>	"This tells the hit ratio of AppleLive Playlist requests",
			),
			"cacapplelivestreamingvidhr" => Array(
				"cntrname"	=>	"cacapplelivestreamingvidhr",
				"cntrdescr"	=>	"AppleLiveStream",
				"tooltip"	=>	"This tells the hit ratio of AppleLive requests",
			),
			"cacmsftsmthstrmplvidhr" => Array(
				"cntrname"	=>	"cacmsftsmthstrmplvidhr",
				"cntrdescr"	=>	"MSFT SmoothStreamPL",
				"tooltip"	=>	"This tells the hit ratio of MicrosoftSmoothStreaming Playlist requests",
			),
			"camsftsmthstrmvidhr" => Array(
				"cntrname"	=>	"camsftsmthstrmvidhr",
				"cntrdescr"	=>	"MSFT SmoothStream",
				"tooltip"	=>	"This tells the hit ratio of MicrosoftSmoothStreaming requests",
			),
			"catotother" => Array(
				"cntrname"	=>	"catotother",
				"cntrdescr"	=>	"Total Other Objects",
				"tooltip"	=>	"",
			),
			"caotherrate" => array (
				"cntrname"	=>	"caotherrate",
				"cntrdescr"	=>	"",
			),
			"catotvideo" => Array(
				"cntrname"	=>	"catotvideo",
				"cntrdescr"	=>	"Total Video Objects",
				"tooltip"	=>	"",
			),
			"cavideorate" => array (
				"cntrname"	=>	"cavideorate",
				"cntrdescr"	=>	"",
			),
			"catotaudio" => Array(
				"cntrname"	=>	"catotaudio",
				"cntrdescr"	=>	"Total Audio Objects",
				"tooltip"	=>	"",
			),
			"caaudiorate" => array (
				"cntrname"	=>	"caaudiorate",
				"cntrdescr"	=>	"",
			),
			"catotobjstored" => Array(
				"cntrname"	=>	"catotobjstored",
				"cntrdescr"	=>	"Total objects stored in cache",
				"tooltip"	=>	"",
			),
			"catotlookuphit" => Array(
				"cntrname"	=>	"catotlookuphit",
				"cntrdescr"	=>	"Hits being served",
				"tooltip"	=>	"This number should be close to the number of hits being served currently.",
			),
			"calookuphitrate" => array (
				"cntrname"	=>	"calookuphitrate",
				"cntrdescr"	=>	"",
			),
			"catotgetobjreq" => Array(
				"cntrname"	=>	"catotgetobjreq",
				"cntrdescr"	=>	"Total fetch request from cache",
				"tooltip"	=>	"",
			),
			"cagetobjreqrate" => array (
				"cntrname"	=>	"cagetobjreqrate",
				"cntrdescr"	=>	"",
			),
			"catotrequests" => Array(
				"cntrname"	=>	"catotrequests",
				"cntrdescr"	=>	"Total Lookup Requests",
				"tooltip"	=>	"",
			),
			"carequestsrate" => array (
				"cntrname"	=>	"carequestsrate",
				"cntrdescr"	=>	"",
			),
			"camsftsmthstrmvid" => Array(
				"cntrname"	=>	"camsftsmthstrmvid",
				"cntrdescr"	=>	"MSFT SmoothStream objects",
				"tooltip"	=>	"This tells the total number of MicrosoftSmoothStreaming requests served by NS",
			),
			"camsftsmthstrmvidrate" => array (
				"cntrname"	=>	"camsftsmthstrmvidrate",
				"cntrdescr"	=>	"",
			),
			"cacmsftsmthstrmvid" => Array(
				"cntrname"	=>	"cacmsftsmthstrmvid",
				"cntrdescr"	=>	"MSFT SmoothStream hits",
				"tooltip"	=>	"This tells the total number of MicrosoftSmoothStreaming requests served from cache",
			),
			"cacmsftsmthstrmvidrate" => array (
				"cntrname"	=>	"cacmsftsmthstrmvidrate",
				"cntrdescr"	=>	"",
			),
			"camsftsmthstrvid" => Array(
				"cntrname"	=>	"camsftsmthstrvid",
				"cntrdescr"	=>	"MSFT SmoothStreamPL objects",
				"tooltip"	=>	"This tells the total number of MicrosoftSmoothStreaming Playlist requests served by NS",
			),
			"camsftsmthstrvidrate" => array (
				"cntrname"	=>	"camsftsmthstrvidrate",
				"cntrdescr"	=>	"",
			),
			"cacmsftsmthstrmplvid" => Array(
				"cntrname"	=>	"cacmsftsmthstrmplvid",
				"cntrdescr"	=>	"MSFT SmoothStreamPL hits",
				"tooltip"	=>	"This tells the total number of MicrosoftSmoothStreaming Playlist requests served from cache",
			),
			"cacmsftsmthstrmplvidrate" => array (
				"cntrname"	=>	"cacmsftsmthstrmplvidrate",
				"cntrdescr"	=>	"",
			),
			"cacaplelivestrmngvid" => Array(
				"cntrname"	=>	"cacaplelivestrmngvid",
				"cntrdescr"	=>	"AppleLiveStream objects",
				"tooltip"	=>	"This tells the total number of AppleLive requests served by NS",
			),
			"cacaplelivestrmngvidrate" => array (
				"cntrname"	=>	"cacaplelivestrmngvidrate",
				"cntrdescr"	=>	"",
			),
			"cacapplelivestreamingvid" => Array(
				"cntrname"	=>	"cacapplelivestreamingvid",
				"cntrdescr"	=>	"AppleLiveStream hits",
				"tooltip"	=>	"This tells the total number of AppleLive requests served from cache",
			),
			"cacapplelivestreamingvidrate" => array (
				"cntrname"	=>	"cacapplelivestreamingvidrate",
				"cntrdescr"	=>	"",
			),
			"cacapplelivestrmngvid" => Array(
				"cntrname"	=>	"cacapplelivestrmngvid",
				"cntrdescr"	=>	"AppleLive PL objects",
				"tooltip"	=>	"This tells the total number of AppleLive Playlist requests served by NS",
			),
			"cacapplelivestrmngvidrate" => array (
				"cntrname"	=>	"cacapplelivestrmngvidrate",
				"cntrdescr"	=>	"",
			),
			"cacapplelivestreamingplaylistvid" => Array(
				"cntrname"	=>	"cacapplelivestreamingplaylistvid",
				"cntrdescr"	=>	"AppleLive PL hits",
				"tooltip"	=>	"This tells the total number of AppleLive Playlist requests served from cache",
			),
			"cacapplelivestreamingplaylistvidrate" => array (
				"cntrname"	=>	"cacapplelivestreamingplaylistvidrate",
				"cntrdescr"	=>	"",
			),
			"caadtsaudio" => Array(
				"cntrname"	=>	"caadtsaudio",
				"cntrdescr"	=>	"ADTS objects",
				"tooltip"	=>	"This tells the total number of ADTS requests served by NS",
			),
			"caadtsaudiorate" => array (
				"cntrname"	=>	"caadtsaudiorate",
				"cntrdescr"	=>	"",
			),
			"cacadtsaudio" => Array(
				"cntrname"	=>	"cacadtsaudio",
				"cntrdescr"	=>	"ADTS hits",
				"tooltip"	=>	"This tells the total number of ADTS requests served from cache",
			),
			"cacadtsaudiorate" => array (
				"cntrname"	=>	"cacadtsaudiorate",
				"cntrdescr"	=>	"",
			),
			"caaacaudio" => Array(
				"cntrname"	=>	"caaacaudio",
				"cntrdescr"	=>	"AAC objects",
				"tooltip"	=>	"This tells the total number of AAC requests served by NS",
			),
			"caaacaudiorate" => array (
				"cntrname"	=>	"caaacaudiorate",
				"cntrdescr"	=>	"",
			),
			"cacaacaudio" => Array(
				"cntrname"	=>	"cacaacaudio",
				"cntrdescr"	=>	"AAC hits",
				"tooltip"	=>	"This tells the total number of AAC requests served from cache",
			),
			"cacaacaudiorate" => array (
				"cntrname"	=>	"cacaacaudiorate",
				"cntrdescr"	=>	"",
			),
			"caflvvid" => Array(
				"cntrname"	=>	"caflvvid",
				"cntrdescr"	=>	"FLV objects",
				"tooltip"	=>	"This tells the total number of FLV requests served by NS",
			),
			"caflvvidrate" => array (
				"cntrname"	=>	"caflvvidrate",
				"cntrdescr"	=>	"",
			),
			"cacflvvid" => Array(
				"cntrname"	=>	"cacflvvid",
				"cntrdescr"	=>	"FLV hits",
				"tooltip"	=>	"This tells the total number of FLV requests served from cache",
			),
			"cacflvvidrate" => array (
				"cntrname"	=>	"cacflvvidrate",
				"cntrdescr"	=>	"",
			),
			"camp4vid" => Array(
				"cntrname"	=>	"camp4vid",
				"cntrdescr"	=>	"MP4 objects",
				"tooltip"	=>	"This tells the total number of MP4 requests served by NS",
			),
			"camp4vidrate" => array (
				"cntrname"	=>	"camp4vidrate",
				"cntrdescr"	=>	"",
			),
			"cacmp4vid" => Array(
				"cntrname"	=>	"cacmp4vid",
				"cntrdescr"	=>	"MP4 hits",
				"tooltip"	=>	"This tells the total number of MP4 requests served from cache",
			),
			"cacmp4vidrate" => array (
				"cntrname"	=>	"cacmp4vidrate",
				"cntrdescr"	=>	"",
			),
			"ca3pvid" => Array(
				"cntrname"	=>	"ca3pvid",
				"cntrdescr"	=>	"3GP Objects",
				"tooltip"	=>	"This tells the total number of 3GP requests served by NS",
			),
			"ca3pvidrate" => array (
				"cntrname"	=>	"ca3pvidrate",
				"cntrdescr"	=>	"",
			),
			"ca3gpvid" => Array(
				"cntrname"	=>	"ca3gpvid",
				"cntrdescr"	=>	"3GP hits",
				"tooltip"	=>	"This tells the hit ratio of 3GP requests served from cache",
			),
			"ca3gpvidrate" => array (
				"cntrname"	=>	"ca3gpvidrate",
				"cntrdescr"	=>	"",
			),
			"camsftsmthstrmvidbytes" => Array(
				"cntrname"	=>	"camsftsmthstrmvidbytes",
				"cntrdescr"	=>	"Total MSFT SmoothStream Bytes",
				"tooltip"	=>	"This tells the total number of MicrosoftSmoothStreaming bytes served by NS",
			),
			"camsftsmthstrmvidbytesrate" => array (
				"cntrname"	=>	"camsftsmthstrmvidbytesrate",
				"cntrdescr"	=>	"",
			),
			"camicrosoftsmoothstreamingvidcachebytes" => Array(
				"cntrname"	=>	"camicrosoftsmoothstreamingvidcachebytes",
				"cntrdescr"	=>	"CA MSFT SmoothStream Bytes",
				"tooltip"	=>	"This tells the total number of MicrosoftSmoothStreaming bytes served from cache.",
			),
			"camicrosoftsmoothstreamingvidcachebytesrate" => array (
				"cntrname"	=>	"camicrosoftsmoothstreamingvidcachebytesrate",
				"cntrdescr"	=>	"",
			),
			"camsftsmthstrmplvidbytes" => Array(
				"cntrname"	=>	"camsftsmthstrmplvidbytes",
				"cntrdescr"	=>	"Total MSFT SmoothStreamPL Bytes",
				"tooltip"	=>	"This tells the total number of MicrosoftSmoothStreaming Playlist bytes served by NS",
			),
			"camsftsmthstrmplvidbytesrate" => array (
				"cntrname"	=>	"camsftsmthstrmplvidbytesrate",
				"cntrdescr"	=>	"",
			),
			"camicrosoftsmoothstreamingplaylistvidcachebytes" => Array(
				"cntrname"	=>	"camicrosoftsmoothstreamingplaylistvidcachebytes",
				"cntrdescr"	=>	"CA MSFT SmoothStreamPL Bytes",
				"tooltip"	=>	"This tells the total number of MicrosoftSmoothStreaming Playlist bytes served from cache",
			),
			"camicrosoftsmoothstreamingplaylistvidcachebytesrate" => array (
				"cntrname"	=>	"camicrosoftsmoothstreamingplaylistvidcachebytesrate",
				"cntrdescr"	=>	"",
			),
			"caapplelivestreamingvidbytes" => Array(
				"cntrname"	=>	"caapplelivestreamingvidbytes",
				"cntrdescr"	=>	"Total AppleLiveStream Bytes",
				"tooltip"	=>	"This tells the total number of AppleLive bytes served by NS",
			),
			"caapplelivestreamingvidbytesrate" => array (
				"cntrname"	=>	"caapplelivestreamingvidbytesrate",
				"cntrdescr"	=>	"",
			),
			"caapplelivestreamingvidcachebytes" => Array(
				"cntrname"	=>	"caapplelivestreamingvidcachebytes",
				"cntrdescr"	=>	"CA AppleLiveStream Bytes",
				"tooltip"	=>	"This tells the total number of AppleLive bytes served from cache",
			),
			"caapplelivestreamingvidcachebytesrate" => array (
				"cntrname"	=>	"caapplelivestreamingvidcachebytesrate",
				"cntrdescr"	=>	"",
			),
			"caapplelivestreamingplaylistvidbytes" => Array(
				"cntrname"	=>	"caapplelivestreamingplaylistvidbytes",
				"cntrdescr"	=>	"Total AppleLive PL Bytes",
				"tooltip"	=>	"This tells the total number of AppleLive Playlist bytes served by NS",
			),
			"caapplelivestreamingplaylistvidbytesrate" => array (
				"cntrname"	=>	"caapplelivestreamingplaylistvidbytesrate",
				"cntrdescr"	=>	"",
			),
			"caapplelivestreamingplaylistvidcachebytes" => Array(
				"cntrname"	=>	"caapplelivestreamingplaylistvidcachebytes",
				"cntrdescr"	=>	"CA AppleLivePL Bytes",
				"tooltip"	=>	"This tells the total number of AppleLive Playlist bytes served from cache",
			),
			"caapplelivestreamingplaylistvidcachebytesrate" => array (
				"cntrname"	=>	"caapplelivestreamingplaylistvidcachebytesrate",
				"cntrdescr"	=>	"",
			),
			"caadtsaudiobytes" => Array(
				"cntrname"	=>	"caadtsaudiobytes",
				"cntrdescr"	=>	"Total ADTS bytes",
				"tooltip"	=>	"This tells the total number of ADTS bytes served by NS",
			),
			"caadtsaudiobytesrate" => array (
				"cntrname"	=>	"caadtsaudiobytesrate",
				"cntrdescr"	=>	"",
			),
			"caadtsaudiocachebytes" => Array(
				"cntrname"	=>	"caadtsaudiocachebytes",
				"cntrdescr"	=>	"CA ADTS bytes",
				"tooltip"	=>	"This tells the total number of ADTS bytes served from cache",
			),
			"caadtsaudiocachebytesrate" => array (
				"cntrname"	=>	"caadtsaudiocachebytesrate",
				"cntrdescr"	=>	"",
			),
			"caaacaudiobytes" => Array(
				"cntrname"	=>	"caaacaudiobytes",
				"cntrdescr"	=>	"Total AAC bytes",
				"tooltip"	=>	"This tells the total number of AAC bytes served by NS",
			),
			"caaacaudiobytesrate" => array (
				"cntrname"	=>	"caaacaudiobytesrate",
				"cntrdescr"	=>	"",
			),
			"caaacaudiocachebytes" => Array(
				"cntrname"	=>	"caaacaudiocachebytes",
				"cntrdescr"	=>	"CA AAC bytes",
				"tooltip"	=>	"This tells the total number of AAC bytes served from cache",
			),
			"caaacaudiocachebytesrate" => array (
				"cntrname"	=>	"caaacaudiocachebytesrate",
				"cntrdescr"	=>	"",
			),
			"caflvvidbytes" => Array(
				"cntrname"	=>	"caflvvidbytes",
				"cntrdescr"	=>	"Total FLV bytes",
				"tooltip"	=>	"This tells the total number of FLV bytes served by NS",
			),
			"caflvvidbytesrate" => array (
				"cntrname"	=>	"caflvvidbytesrate",
				"cntrdescr"	=>	"",
			),
			"caflvvidcachebytes" => Array(
				"cntrname"	=>	"caflvvidcachebytes",
				"cntrdescr"	=>	"CA FLV bytes",
				"tooltip"	=>	"This tells the total number of FLV bytes served from cache",
			),
			"caflvvidcachebytesrate" => array (
				"cntrname"	=>	"caflvvidcachebytesrate",
				"cntrdescr"	=>	"",
			),
			"camp4vidbytes" => Array(
				"cntrname"	=>	"camp4vidbytes",
				"cntrdescr"	=>	"Total MP4 bytes",
				"tooltip"	=>	"This tells the total number of MP4 bytes served by NS",
			),
			"camp4vidbytesrate" => array (
				"cntrname"	=>	"camp4vidbytesrate",
				"cntrdescr"	=>	"",
			),
			"camp4vidcachebytes" => Array(
				"cntrname"	=>	"camp4vidcachebytes",
				"cntrdescr"	=>	"CA MP4 bytes",
				"tooltip"	=>	"This tells the total number of MP4 bytes served from cache",
			),
			"camp4vidcachebytesrate" => array (
				"cntrname"	=>	"camp4vidcachebytesrate",
				"cntrdescr"	=>	"",
			),
			"ca3gpvidbytes" => Array(
				"cntrname"	=>	"ca3gpvidbytes",
				"cntrdescr"	=>	"Total 3GP Bytes",
				"tooltip"	=>	"This tells the total number of 3GP bytes served by NS",
			),
			"ca3gpvidbytesrate" => array (
				"cntrname"	=>	"ca3gpvidbytesrate",
				"cntrdescr"	=>	"",
			),
			"ca3gpvidcachebytes" => Array(
				"cntrname"	=>	"ca3gpvidcachebytes",
				"cntrdescr"	=>	"CA 3GP Bytes",
				"tooltip"	=>	"This tells the total number of 3GP bytes served from cache",
			),
			"ca3gpvidcachebytesrate" => array (
				"cntrname"	=>	"ca3gpvidcachebytesrate",
				"cntrdescr"	=>	"",
			),
			"caandroid" => Array(
				"cntrname"	=>	"caandroid",
				"cntrdescr"	=>	"Android requests",
				"tooltip"	=>	"Total number of android requests to netscaler",
			),
			"caandroidrate" => array (
				"cntrname"	=>	"caandroidrate",
				"cntrdescr"	=>	"",
			),
			"calaptopdesktp" => Array(
				"cntrname"	=>	"calaptopdesktp",
				"cntrdescr"	=>	"Laptop/Desktop requests",
				"tooltip"	=>	"Total number of laptop/desktop requests to netscaler",
			),
			"calaptopdesktprate" => array (
				"cntrname"	=>	"calaptopdesktprate",
				"cntrdescr"	=>	"",
			),
			"caios" => Array(
				"cntrname"	=>	"caios",
				"cntrdescr"	=>	"IOS requests",
				"tooltip"	=>	"Total number of iOs requests to netscaler",
			),
			"caiosrate" => array (
				"cntrname"	=>	"caiosrate",
				"cntrdescr"	=>	"",
			),
			"caother" => Array(
				"cntrname"	=>	"caother",
				"cntrdescr"	=>	"Other requests",
				"tooltip"	=>	"Total number of other mobile device requests to netscaler",
			),
			"caotherrate" => array (
				"cntrname"	=>	"caotherrate",
				"cntrdescr"	=>	"",
			),
			"caunidentified" => Array(
				"cntrname"	=>	"caunidentified",
				"cntrdescr"	=>	"Unidentified requests",
				"tooltip"	=>	"Total number of unidentified requests to netscaler",
			),
			"caunidentifiedrate" => array (
				"cntrname"	=>	"caunidentifiedrate",
				"cntrdescr"	=>	"",
			),
			"caandroidcache" => Array(
				"cntrname"	=>	"caandroidcache",
				"cntrdescr"	=>	"Android hits",
				"tooltip"	=>	"This tells android requests served from cache",
			),
			"caandroidcacherate" => array (
				"cntrname"	=>	"caandroidcacherate",
				"cntrdescr"	=>	"",
			),
			"caioscache" => Array(
				"cntrname"	=>	"caioscache",
				"cntrdescr"	=>	"IOS hits",
				"tooltip"	=>	"This tells iOS requests served from cache",
			),
			"caioscacherate" => array (
				"cntrname"	=>	"caioscacherate",
				"cntrdescr"	=>	"",
			),
			"caothercache" => Array(
				"cntrname"	=>	"caothercache",
				"cntrdescr"	=>	"Other device hits",
				"tooltip"	=>	"This tells Other device requests served from cache",
			),
			"caothercacherate" => array (
				"cntrname"	=>	"caothercacherate",
				"cntrdescr"	=>	"",
			),
			"calaptopdesktpcache" => Array(
				"cntrname"	=>	"calaptopdesktpcache",
				"cntrdescr"	=>	"laptop/desktop hits",
				"tooltip"	=>	"This tells laptop/desktop requests served from cache",
			),
			"calaptopdesktpcacherate" => array (
				"cntrname"	=>	"calaptopdesktpcacherate",
				"cntrdescr"	=>	"",
			),
			"caunidentifiedcache" => Array(
				"cntrname"	=>	"caunidentifiedcache",
				"cntrdescr"	=>	"Unidentified device hits",
				"tooltip"	=>	"This tells unidentified device requests served from cache",
			),
			"caunidentifiedcacherate" => array (
				"cntrname"	=>	"caunidentifiedcacherate",
				"cntrdescr"	=>	"",
			),
			"caandroidbytes" => Array(
				"cntrname"	=>	"caandroidbytes",
				"cntrdescr"	=>	"Total Android Bytes",
				"tooltip"	=>	"This tells the total number of Android bytes served by NS",
			),
			"caandroidbytesrate" => array (
				"cntrname"	=>	"caandroidbytesrate",
				"cntrdescr"	=>	"",
			),
			"caiosbytes" => Array(
				"cntrname"	=>	"caiosbytes",
				"cntrdescr"	=>	"Total IOS Bytes",
				"tooltip"	=>	"This tells the total number of IOS bytes served by NS",
			),
			"caiosbytesrate" => array (
				"cntrname"	=>	"caiosbytesrate",
				"cntrdescr"	=>	"",
			),
			"caotherbytes" => Array(
				"cntrname"	=>	"caotherbytes",
				"cntrdescr"	=>	"Total Other device Bytes",
				"tooltip"	=>	"This tells the total number of Other mobile device bytes served by NS",
			),
			"caotherbytesrate" => array (
				"cntrname"	=>	"caotherbytesrate",
				"cntrdescr"	=>	"",
			),
			"caalptopdsktpbytes" => Array(
				"cntrname"	=>	"caalptopdsktpbytes",
				"cntrdescr"	=>	"Total Laptop/desktop Bytes",
				"tooltip"	=>	"This tells the total number of Laptop/desktop bytes served by NS",
			),
			"caalptopdsktpbytesrate" => array (
				"cntrname"	=>	"caalptopdsktpbytesrate",
				"cntrdescr"	=>	"",
			),
			"caunidentifiedbytes" => Array(
				"cntrname"	=>	"caunidentifiedbytes",
				"cntrdescr"	=>	"Total unidentified device Bytes",
				"tooltip"	=>	"This tells the total number of unidentified device bytes served by NS",
			),
			"caunidentifiedbytesrate" => array (
				"cntrname"	=>	"caunidentifiedbytesrate",
				"cntrdescr"	=>	"",
			),
			"caandroididcachebytes" => Array(
				"cntrname"	=>	"caandroididcachebytes",
				"cntrdescr"	=>	"CA Android Bytes",
				"tooltip"	=>	"This tells the total number of Android bytes served from cache",
			),
			"caandroididcachebytesrate" => array (
				"cntrname"	=>	"caandroididcachebytesrate",
				"cntrdescr"	=>	"",
			),
			"caiosidcachebytes" => Array(
				"cntrname"	=>	"caiosidcachebytes",
				"cntrdescr"	=>	"CA IOS Bytes",
				"tooltip"	=>	"This tells the total number of IOS bytes served from cache",
			),
			"caiosidcachebytesrate" => array (
				"cntrname"	=>	"caiosidcachebytesrate",
				"cntrdescr"	=>	"",
			),
			"caotherididcachebytes" => Array(
				"cntrname"	=>	"caotherididcachebytes",
				"cntrdescr"	=>	"CA Other device Bytes",
				"tooltip"	=>	"This tells the total number of other device bytes served from cache",
			),
			"caotherididcachebytesrate" => array (
				"cntrname"	=>	"caotherididcachebytesrate",
				"cntrdescr"	=>	"",
			),
			"calaptpdektpbytes" => Array(
				"cntrname"	=>	"calaptpdektpbytes",
				"cntrdescr"	=>	"CA Laptop/desktop Bytes",
				"tooltip"	=>	"This tells the total number of Laptop/desktop bytes served from cache",
			),
			"calaptpdektpbytesrate" => array (
				"cntrname"	=>	"calaptpdektpbytesrate",
				"cntrdescr"	=>	"",
			),
			"caaunindentifiedbytes" => Array(
				"cntrname"	=>	"caaunindentifiedbytes",
				"cntrdescr"	=>	"CA unindentified device Bytes",
				"tooltip"	=>	"This tells the total number of unindentified device bytes served from cache",
			),
			"caaunindentifiedbytesrate" => array (
				"cntrname"	=>	"caaunindentifiedbytesrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("catotvideo","catotaudio","catotother","catotobjstored","catotlookuphit","catotgetobjreq",),
				"charts"	=>	array(	array("content_accelerator_hits",),
								),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Content Accelerator Statistics - Summary",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("catotvideo","catotaudio","catotother","catotobjstored","catotlookuphit","catotgetobjreq",),
				"charts"	=>	array(	array("ca_hits",),
								),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("catotrequests",),
			),
			Array(
				"dhID"	=>	4,
				"title"	=>	"Hit Statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("catotlookuphit","catotgetobjreq",),
			),
			Array(
				"dhID"	=>	5,
				"title"	=>	"Content type Statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("camsftsmthstrmvid","cacmsftsmthstrmvid","camsftsmthstrvid","cacmsftsmthstrmplvid","cacaplelivestrmngvid","cacapplelivestreamingvid","cacapplelivestrmngvid","cacapplelivestreamingplaylistvid","caadtsaudio","cacadtsaudio","caaacaudio","cacaacaudio","caflvvid","cacflvvid","camp4vid","cacmp4vid","ca3pvid","ca3gpvid",),
			),
			Array(
				"dhID"	=>	6,
				"title"	=>	"Bytes Served Statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("camsftsmthstrmvidbytes","camicrosoftsmoothstreamingvidcachebytes","camsftsmthstrmplvidbytes","camicrosoftsmoothstreamingplaylistvidcachebytes","caapplelivestreamingvidbytes","caapplelivestreamingvidcachebytes","caapplelivestreamingplaylistvidbytes","caapplelivestreamingplaylistvidcachebytes","caadtsaudiobytes","caadtsaudiocachebytes","caaacaudiobytes","caaacaudiocachebytes","caflvvidbytes","caflvvidcachebytes","camp4vidbytes","camp4vidcachebytes","ca3gpvidbytes","ca3gpvidcachebytes",),
			),
			Array(
				"dhID"	=>	7,
				"title"	=>	"Device type Statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("caandroid","calaptopdesktp","caios","caother","caunidentified","caandroidcache","caioscache","caothercache","calaptopdesktpcache","caunidentifiedcache","caandroidbytes","caiosbytes","caotherbytes","caalptopdsktpbytes","caunidentifiedbytes","caandroididcachebytes","caiosidcachebytes","caotherididcachebytes","calaptpdektpbytes","caaunindentifiedbytes",),
			),
			Array(
				"dhID"	=>	8,
				"title"	=>	"Transaction Hit Ratios",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("camsftsmthstrmvidhr","cacmsftsmthstrmplvidhr","cacapplelivestreamingvidhr","cacapplelivestreamingplaylistvidhr","cacadtsaudiohr","cacaacaudiohr","cacflvvidhr","cacmp4vidhr","ca3gpvidhr","caandroidhr","calaptopdesktphr","caotherhr","caioshr","caunidentifiedhr",),
			),
			Array(
				"dhID"	=>	9,
				"title"	=>	"Byte Hit Ratios",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("camsftsmthstrmngvidcabyteshr","camsftsmthstrmngplvidcabyteshr","caapplelivestrmngvidcachebyteshr","caaplelivestrmngplvidcachebyteshr","caadtsaudiocachebyteshr","caaacaudiocachebyteshr","caflvvidcachebyteshr","camp4vidcachebyteshr","ca3gpvidcachebyteshr","caandroidbyteshr","calaptpdsktpbyteshr","caotherbyteshr","caiosbyteshr","caaunindentifiedbyteshr",),
			),
		),
),


"statcache" => Array(
	"cmddispname"		=>	"statcache",
	"cmdname"		=>	"statcache",
	"license"		=>	Array("NSKF1_CACHE_BIT_POS","NSKF1_CACHE_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"cachemaxmemorykb" => Array(
				"cntrname"	=>	"cachemaxmemorykb",
				"cntrdescr"	=>	"Maximum memory(KB) Deprecated",
				"tooltip"	=>	"Largest amount of memory the NetScaler can dedicate to caching, up to 50% of available memory. A 0 value disables caching, but the caching module continues to run.",
			),
			"cacherecentpercentsuccessfulrevalidation" => Array(
				"cntrname"	=>	"cacherecentpercentsuccessfulrevalidation",
				"cntrdescr"	=>	"Recent successful reval ratio(%)",
				"tooltip"	=>	"Recently recorded percentage of times stored content was successfully revalidated by a 304 response rather than by a full response",
			),
			"cacherecentpercentstoreablemiss" => Array(
				"cntrname"	=>	"cacherecentpercentstoreablemiss",
				"cntrdescr"	=>	"Recent storable miss ratio(%)",
				"tooltip"	=>	"Recently recorded ratio of store-able misses to all misses expressed as percentage.",
			),
			"cacherecentpercentparameterizedhits" => Array(
				"cntrname"	=>	"cacherecentpercentparameterizedhits",
				"cntrdescr"	=>	"Recent parameterized 304 hit ratio(%)",
				"tooltip"	=>	"Recently recorded ratio of parameterized 304 hits to all parameterized hits expressed as a percentage",
			),
			"cacherecentpercentoriginbandwidthsaved" => Array(
				"cntrname"	=>	"cacherecentpercentoriginbandwidthsaved",
				"cntrdescr"	=>	"Recent origin bandwidth saved(%)",
				"tooltip"	=>	"Bytes served from cache divided by total bytes served to client. This ratio can be greater than 1 because of the assumption that all compression has been done in the NetScaler.",
			),
			"cacherecentpercenthit" => Array(
				"cntrname"	=>	"cacherecentpercenthit",
				"cntrdescr"	=>	"Recent hit ratio(%)",
				"tooltip"	=>	"Recently recorded cache hit ratio expressed as percentage",
			),
			"cacherecentpercentbytehit" => Array(
				"cntrname"	=>	"cacherecentpercentbytehit",
				"cntrdescr"	=>	"Recent byte hit ratio(%)",
				"tooltip"	=>	"Recently recorded cache byte hit ratio expressed as percentage. Here we define byte hit ratio as ((number of bytes served from the cache)/(total number of bytes served to the client)). This is the standard definition of Byte Hit Ratio. If compression is turned ON in NS then this ratio doesn't mean much. This might under or over estimate the origin-to-cache bandwidth saving (depending upon whether bytes served by CMP in NetScaler are more or less than compressed bytes served from the cache). If CMP is turned OFF in NS then this ratio is same as cacheRecentPercentOriginBandwidthSaved.",
			),
			"cacherecentpercent304hits" => Array(
				"cntrname"	=>	"cacherecentpercent304hits",
				"cntrdescr"	=>	"Recent 304 hit ratio(%)",
				"tooltip"	=>	"Recently recorded ratio of 304 hits to all hits expressed as percentage",
			),
			"cacheutilizedmemorykb" => Array(
				"cntrname"	=>	"cacheutilizedmemorykb",
				"cntrdescr"	=>	"Utilized memory(KB)",
				"tooltip"	=>	"Amount of memory the integrated cache is currently using.",
			),
			"cachemaxmemoryactivekb" => Array(
				"cntrname"	=>	"cachemaxmemoryactivekb",
				"cntrdescr"	=>	"Maximum memory active value(KB)",
				"tooltip"	=>	"Currently active value of maximum memory",
			),
			"cache64maxmemorykb" => Array(
				"cntrname"	=>	"cache64maxmemorykb",
				"cntrdescr"	=>	"Maximum memory(KB)",
				"tooltip"	=>	"Largest amount of memory the NetScaler can dedicate to caching, up to 50% of available memory. A 0 value disables caching, but the caching module continues to run.",
			),
			"cachepercentpethits" => Array(
				"cntrname"	=>	"cachepercentpethits",
				"cntrdescr"	=>	"Poll every time hit ratio(%)",
				"tooltip"	=>	"Percentage of cache hits in content groups that have Poll Every Time enabled, relative to all searches of content groups with Poll Every Time enabled.",
			),
			"cachetotpethits" => Array(
				"cntrname"	=>	"cachetotpethits",
				"cntrdescr"	=>	"Poll every time hits",
				"tooltip"	=>	"Number of times a cache hit was found during a search of a content group that has Poll Every Time enabled.",
			),
			"cachepethitsrate" => array (
				"cntrname"	=>	"cachepethitsrate",
				"cntrdescr"	=>	"",
			),
			"cachepercentparameterized304hits" => Array(
				"cntrname"	=>	"cachepercentparameterized304hits",
				"cntrdescr"	=>	"Parameterized 304 hit ratio(%)",
				"tooltip"	=>	"Percentage of parameterized 304 hits relative to all parameterized hits.",
			),
			"cachetotparameterizedhits" => Array(
				"cntrname"	=>	"cachetotparameterizedhits",
				"cntrdescr"	=>	"Total parameterized hits",
				"tooltip"	=>	"Parameterized requests resulting in either a 304 or non-304 hit.",
			),
			"cacheparameterizedhitsrate" => array (
				"cntrname"	=>	"cacheparameterizedhitsrate",
				"cntrdescr"	=>	"",
			),
			"cachepercentsuccessfulrevalidation" => Array(
				"cntrname"	=>	"cachepercentsuccessfulrevalidation",
				"cntrdescr"	=>	"Successful reval ratio(%)",
				"tooltip"	=>	"Percentage of times stored content was successfully revalidated by a 304 (Object Not Modifed) response rather than by a full response",
			),
			"cachepercentstoreablemiss" => Array(
				"cntrname"	=>	"cachepercentstoreablemiss",
				"cntrdescr"	=>	"Storable miss ratio(%)",
				"tooltip"	=>	"Responses that were fetched from the origin, stored in the cache, and then served to the client, as a percentage of all cache misses.",
			),
			"cachetotfulltoconditionalrequest" => Array(
				"cntrname"	=>	"cachetotfulltoconditionalrequest",
				"cntrdescr"	=>	"Conversions to conditional req",
				"tooltip"	=>	"Number of user-agent requests for a cached  Poll Every Time (PET) response that were sent to the origin server as conditional requests.",
			),
			"cachefulltoconditionalrequestrate" => array (
				"cntrname"	=>	"cachefulltoconditionalrequestrate",
				"cntrdescr"	=>	"",
			),
			"cachetotsuccessfulrevalidation" => Array(
				"cntrname"	=>	"cachetotsuccessfulrevalidation",
				"cntrdescr"	=>	"Successful revalidations",
				"tooltip"	=>	"Total number of times stored content was successfully revalidated by a 304 Not Modified response from the origin.",
			),
			"cachesuccessfulrevalidationrate" => array (
				"cntrname"	=>	"cachesuccessfulrevalidationrate",
				"cntrdescr"	=>	"",
			),
			"cachetotrevalidationmiss" => Array(
				"cntrname"	=>	"cachetotrevalidationmiss",
				"cntrdescr"	=>	"Revalidations",
				"tooltip"	=>	"Responses that an intervening cache revalidated with the integrated cache before serving, as determined by a Cache-Control: Max-Age header configurable in the integrated cache",
			),
			"cacherevalidationmissrate" => array (
				"cntrname"	=>	"cacherevalidationmissrate",
				"cntrdescr"	=>	"",
			),
			"cachetotnonstoreablemisses" => Array(
				"cntrname"	=>	"cachetotnonstoreablemisses",
				"cntrdescr"	=>	"Non-storable misses",
				"tooltip"	=>	"Cache misses for which the fetched response is not stored in the cache. These responses match policies with a NOCACHE action or are affected by Poll Every Time.",
			),
			"cachenonstoreablemissesrate" => array (
				"cntrname"	=>	"cachenonstoreablemissesrate",
				"cntrdescr"	=>	"",
			),
			"cachetotstoreablemisses" => Array(
				"cntrname"	=>	"cachetotstoreablemisses",
				"cntrdescr"	=>	"Storable misses",
				"tooltip"	=>	"Cache misses for which the fetched response is stored in the cache before serving it to the client. Storable misses conform to a built-in or user-defined caching policy that contains a CACHE action.",
			),
			"cachestoreablemissesrate" => array (
				"cntrname"	=>	"cachestoreablemissesrate",
				"cntrdescr"	=>	"",
			),
			"cachecompressedbytesserved" => Array(
				"cntrname"	=>	"cachecompressedbytesserved",
				"cntrdescr"	=>	"Compressed bytes from cache",
				"tooltip"	=>	"Number of compressed bytes served from the cache",
			),
			"cachecompressedbytesservedrate" => array (
				"cntrname"	=>	"cachecompressedbytesservedrate",
				"cntrdescr"	=>	"",
			),
			"cachepercentbytehit" => Array(
				"cntrname"	=>	"cachepercentbytehit",
				"cntrdescr"	=>	"Byte hit ratio(%)",
				"tooltip"	=>	"Bytes served from the cache divided by total bytes served to the client. If compression is On in the NetScaler, this ratio may not reflect the bytes served by the compression module. If the compression is Off, this ratio is the same as cachePercentOriginBandwidthSaved.",
			),
			"cachebytesserved" => Array(
				"cntrname"	=>	"cachebytesserved",
				"cntrdescr"	=>	"Bytes served by cache",
				"tooltip"	=>	"Total number of bytes served from the integrated cache",
			),
			"cachebytesservedrate" => array (
				"cntrname"	=>	"cachebytesservedrate",
				"cntrdescr"	=>	"",
			),
			"cachetotresponsebytes" => Array(
				"cntrname"	=>	"cachetotresponsebytes",
				"cntrdescr"	=>	"Bytes served by NetScaler",
				"tooltip"	=>	"Total number of HTTP response bytes served by NetScaler from both the origin and the cache",
			),
			"cacheresponsebytesrate" => array (
				"cntrname"	=>	"cacheresponsebytesrate",
				"cntrdescr"	=>	"",
			),
			"cachepercent304hits" => Array(
				"cntrname"	=>	"cachepercent304hits",
				"cntrdescr"	=>	"304 hit ratio(%)",
				"tooltip"	=>	"304 responses as a percentage of all responses that the NetScaler served.",
			),
			"cachenummarker" => Array(
				"cntrname"	=>	"cachenummarker",
				"cntrdescr"	=>	"Marker objects",
				"tooltip"	=>	"Marker objects created when a response exceeds the maximum or minimum size for entries in its content group or has not yet received the minimum number of hits required for items in its content group.",
			),
			"cachepercentoriginbandwidthsaved" => Array(
				"cntrname"	=>	"cachepercentoriginbandwidthsaved",
				"cntrdescr"	=>	"Origin bandwidth saved(%)",
				"tooltip"	=>	"Percentage of origin bandwidth saved, expressed as number of bytes served from the integrated cache divided by all bytes served. The assumption is that all compression is done in the NetScaler.",
			),
			"cachepercenthit" => Array(
				"cntrname"	=>	"cachepercenthit",
				"cntrdescr"	=>	"Hit ratio(%)",
				"tooltip"	=>	"Cache hits as percentage of the total number of requests",
			),
			"cachetotmisses" => Array(
				"cntrname"	=>	"cachetotmisses",
				"cntrdescr"	=>	"Misses",
				"tooltip"	=>	"Intercepted HTTP requests requiring fetches from origin server.",
			),
			"cachemissesrate" => array (
				"cntrname"	=>	"cachemissesrate",
				"cntrdescr"	=>	"",
			),
			"cachetothits" => Array(
				"cntrname"	=>	"cachetothits",
				"cntrdescr"	=>	"Hits",
				"tooltip"	=>	"Responses served from the integrated cache. These responses match a policy with a CACHE action.",
			),
			"cachehitsrate" => array (
				"cntrname"	=>	"cachehitsrate",
				"cntrdescr"	=>	"",
			),
			"cachetotrequests" => Array(
				"cntrname"	=>	"cachetotrequests",
				"cntrdescr"	=>	"Requests",
				"tooltip"	=>	"Total cache hits plus total cache misses.",
			),
			"cacherequestsrate" => array (
				"cntrname"	=>	"cacherequestsrate",
				"cntrdescr"	=>	"",
			),
			"cachenumcached" => Array(
				"cntrname"	=>	"cachenumcached",
				"cntrdescr"	=>	"Cached objects",
				"tooltip"	=>	"Responses currently in integrated cache. Includes responses fully downloaded, in the process of being downloaded, and expired or flushed but not yet removed.",
			),
			"cachecurhits" => Array(
				"cntrname"	=>	"cachecurhits",
				"cntrdescr"	=>	"Hits being served",
				"tooltip"	=>	"This number should be close to the number of hits being served currently.",
			),
			"cachecurmisses" => Array(
				"cntrname"	=>	"cachecurmisses",
				"cntrdescr"	=>	"Misses being handled",
				"tooltip"	=>	"Responses fetched from the origin and served from the cache. Should approximate storable misses. Does not include non-storable misses.",
			),
			"cachetotnon304hits" => Array(
				"cntrname"	=>	"cachetotnon304hits",
				"cntrdescr"	=>	"Non-304 hits",
				"tooltip"	=>	"Total number of full (non-304) responses served from the cache. A 304 status code indicates that a response has not been modified since the last time it was served",
			),
			"cachenon304hitsrate" => array (
				"cntrname"	=>	"cachenon304hitsrate",
				"cntrdescr"	=>	"",
			),
			"cachetot304hits" => Array(
				"cntrname"	=>	"cachetot304hits",
				"cntrdescr"	=>	"304 hits",
				"tooltip"	=>	"Object not modified responses served from the cache. (Status code 304 served instead of the full response.)",
			),
			"cache304hitsrate" => array (
				"cntrname"	=>	"cache304hitsrate",
				"cntrdescr"	=>	"",
			),
			"cachetotsqlhits" => Array(
				"cntrname"	=>	"cachetotsqlhits",
				"cntrdescr"	=>	"sql hits",
				"tooltip"	=>	"sql response served from cache",
			),
			"cachesqlhitsrate" => array (
				"cntrname"	=>	"cachesqlhitsrate",
				"cntrdescr"	=>	"",
			),
			"cachetotexpireatlastbyte" => Array(
				"cntrname"	=>	"cachetotexpireatlastbyte",
				"cntrdescr"	=>	"Expire at last byte",
				"tooltip"	=>	"Instances of content expiring immediately after receiving the last body byte due to the Expire at Last Byte setting for the content group.",
			),
			"cacheexpireatlastbyterate" => array (
				"cntrname"	=>	"cacheexpireatlastbyterate",
				"cntrdescr"	=>	"",
			),
			"cachetotflashcachemisses" => Array(
				"cntrname"	=>	"cachetotflashcachemisses",
				"cntrdescr"	=>	"Flashcache misses",
				"tooltip"	=>	"Number of requests to a content group with flash cache enabled that were cache misses. Flash cache distributes the response to all the clients in aqueue.",
			),
			"cacheflashcachemissesrate" => array (
				"cntrname"	=>	"cacheflashcachemissesrate",
				"cntrdescr"	=>	"",
			),
			"cachetotflashcachehits" => Array(
				"cntrname"	=>	"cachetotflashcachehits",
				"cntrdescr"	=>	"Flashcache hits",
				"tooltip"	=>	"Number of requests to a content group with flash cache enabled that were cache hits. The flash cache setting queues requests that arrive simultaneously and distributes the response to all the clients in the queue.",
			),
			"cacheflashcachehitsrate" => array (
				"cntrname"	=>	"cacheflashcachehitsrate",
				"cntrdescr"	=>	"",
			),
			"cachetotparameterizedinvalidationrequests" => Array(
				"cntrname"	=>	"cachetotparameterizedinvalidationrequests",
				"cntrdescr"	=>	"Parameterized inval requests",
				"tooltip"	=>	"Requests matching a policy with an invalidation (INVAL) action and a content group that uses an invalidation selector or parameters.",
			),
			"cacheparameterizedinvalidationrequestsrate" => array (
				"cntrname"	=>	"cacheparameterizedinvalidationrequestsrate",
				"cntrdescr"	=>	"",
			),
			"cachetotnonparameterizedinvalidationrequests" => Array(
				"cntrname"	=>	"cachetotnonparameterizedinvalidationrequests",
				"cntrdescr"	=>	"Full inval requests",
				"tooltip"	=>	"Requests that match an invalidation policy where the invalGroups parameter is configured and expires one or more content groups.",
			),
			"cachenonparameterizedinvalidationrequestsrate" => array (
				"cntrname"	=>	"cachenonparameterizedinvalidationrequestsrate",
				"cntrdescr"	=>	"",
			),
			"cachetotinvalidationrequests" => Array(
				"cntrname"	=>	"cachetotinvalidationrequests",
				"cntrdescr"	=>	"Inval requests",
				"tooltip"	=>	"Requests that match an invalidation policy and result in expiration of specific cached responses or entire content groups.",
			),
			"cacheinvalidationrequestsrate" => array (
				"cntrname"	=>	"cacheinvalidationrequestsrate",
				"cntrdescr"	=>	"",
			),
			"cachetotparameterizedrequests" => Array(
				"cntrname"	=>	"cachetotparameterizedrequests",
				"cntrdescr"	=>	"Parameterized requests",
				"tooltip"	=>	"Total number of requests where the content group has hit and invalidation parameters or selectors.",
			),
			"cacheparameterizedrequestsrate" => array (
				"cntrname"	=>	"cacheparameterizedrequestsrate",
				"cntrdescr"	=>	"",
			),
			"cachetotparameterizednon304hits" => Array(
				"cntrname"	=>	"cachetotparameterizednon304hits",
				"cntrdescr"	=>	"Parameterized non-304 hits",
				"tooltip"	=>	"Parameterized requests resulting in a full response (not status code 304: Object Not Updated) served from the cache.",
			),
			"cacheparameterizednon304hitsrate" => array (
				"cntrname"	=>	"cacheparameterizednon304hitsrate",
				"cntrdescr"	=>	"",
			),
			"cachetotparameterized304hits" => Array(
				"cntrname"	=>	"cachetotparameterized304hits",
				"cntrdescr"	=>	"Parameterized 304 hits",
				"tooltip"	=>	"Parameterized requests resulting in an object not modified (status code 304) response.",
			),
			"cacheparameterized304hitsrate" => array (
				"cntrname"	=>	"cacheparameterized304hitsrate",
				"cntrdescr"	=>	"",
			),
			"cachetotpetrequests" => Array(
				"cntrname"	=>	"cachetotpetrequests",
				"cntrdescr"	=>	"Poll every time requests",
				"tooltip"	=>	"Requests that triggered a search of a content group that has Poll Every Time (PET) enabled (always consult the origin server before serving cached data).",
			),
			"cachepetrequestsrate" => array (
				"cntrname"	=>	"cachepetrequestsrate",
				"cntrdescr"	=>	"",
			),
			"cacheerrmemalloc" => Array(
				"cntrname"	=>	"cacheerrmemalloc",
				"cntrdescr"	=>	"Memory allocation failures",
				"tooltip"	=>	"Total number of times the cache failed to allocate memory to store responses.",
			),
			"cachelargestresponsereceived" => Array(
				"cntrname"	=>	"cachelargestresponsereceived",
				"cntrdescr"	=>	"Largest response so far(B)",
				"tooltip"	=>	"Size, in bytes, of largest response sent to client from the cache or the origin server.",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("cachetothits","cachetotmisses","cachetotrequests","cachepercenthit","cachepercentoriginbandwidthsaved","cachenumcached","cachenummarker",),
				"charts"	=>	array(	array("ic_hits",),
								),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"",
				"header"	=>	array(),
				"format"	=>	"1C",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("cachecurhits","cachecurmisses",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Integrated Cache Statistics - Summary",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("cachetothits","cachetotmisses","cachetotrequests","cachepercenthit","cachepercentoriginbandwidthsaved","cachenumcached","cachenummarker",),
				"charts"	=>	array(	array("ic_hits",),
								),
			),
			Array(
				"dhID"	=>	4,
				"title"	=>	"",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("cachetotrequests",),
			),
			Array(
				"dhID"	=>	5,
				"title"	=>	"Hit Statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("cachetotnon304hits","cachetot304hits","cachetotsqlhits","cachetothits","cachepercent304hits","cachepercenthit","cachepercentoriginbandwidthsaved",),
			),
			Array(
				"dhID"	=>	6,
				"title"	=>	"Byte Statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("cachetotresponsebytes","cachebytesserved","cachepercentbytehit","cachecompressedbytesserved",),
			),
			Array(
				"dhID"	=>	7,
				"title"	=>	"Miss Statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("cachetotstoreablemisses","cachetotnonstoreablemisses","cachetotmisses","cachetotrevalidationmiss","cachetotsuccessfulrevalidation","cachetotfulltoconditionalrequest","cachepercentstoreablemiss","cachepercentsuccessfulrevalidation",),
			),
			Array(
				"dhID"	=>	8,
				"title"	=>	"Flashcache Statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("cachetotexpireatlastbyte","cachetotflashcachemisses","cachetotflashcachehits",),
			),
			Array(
				"dhID"	=>	9,
				"title"	=>	"Invalidation Statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("cachetotparameterizedinvalidationrequests","cachetotnonparameterizedinvalidationrequests","cachetotinvalidationrequests",),
			),
			Array(
				"dhID"	=>	10,
				"title"	=>	"Parameterized Caching Statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("cachetotparameterizedrequests","cachetotparameterizednon304hits","cachetotparameterized304hits","cachetotparameterizedhits","cachepercentparameterized304hits",),
			),
			Array(
				"dhID"	=>	11,
				"title"	=>	"Poll Every Time (PET) Statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("cachetotpetrequests","cachetotpethits","cachepercentpethits",),
			),
			Array(
				"dhID"	=>	12,
				"title"	=>	"Memory Usage Statistics",
				"header"	=>	array("","Total",),
				"format"	=>	"1C",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("cache64maxmemorykb","cachemaxmemoryactivekb","cacheutilizedmemorykb","cacheerrmemalloc","cachelargestresponsereceived","cachenumcached","cachenummarker",),
			),
			Array(
				"dhID"	=>	13,
				"title"	=>	"",
				"header"	=>	array(),
				"format"	=>	"1C",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("cachecurhits","cachecurmisses",),
			),
		),
),


"statcachepolicy" => Array(
	"cmddispname"		=>	"statcachepolicy",
	"cmdname"		=>	"statcachepolicy",
	"license"		=>	Array("NSKF1_CACHE_BIT_POS","NSKF1_CACHE_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"policyname" => Array(
				"argname"		=>	"policyname",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "policyname",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"pipolicyhits" => Array(
				"cntrname"	=>	"pipolicyhits",
				"cntrdescr"	=>	"Policy hits",
				"tooltip"	=>	"Number of hits on the policy",
			),
			"pipolicyhitsrate" => array (
				"cntrname"	=>	"pipolicyhitsrate",
				"cntrdescr"	=>	"",
			),
			"pipolicyundefhits" => Array(
				"cntrname"	=>	"pipolicyundefhits",
				"cntrdescr"	=>	"Policy undef hits",
				"tooltip"	=>	"Number of undef hits on the policy",
			),
			"pipolicyundefhitsrate" => array (
				"cntrname"	=>	"pipolicyundefhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Cache Policy Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pipolicyhits","pipolicyundefhits",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Cache Policy(s) Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pipolicyhits","pipolicyundefhits",),
			),
		),
),


"statcachepolicylabel" => Array(
	"cmddispname"		=>	"statcachepolicylabel",
	"cmdname"		=>	"statcachepolicylabel",
	"license"		=>	Array("NSKF1_CACHE_BIT_POS","NSKF1_CACHE_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"labelname" => Array(
				"argname"		=>	"labelname",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "labelname",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"pipolicylabelhits" => Array(
				"cntrname"	=>	"pipolicylabelhits",
				"cntrdescr"	=>	"Policy Label Hits",
				"tooltip"	=>	"Number of times policy label was invoked.",
			),
			"pipolicylabelhitsrate" => array (
				"cntrname"	=>	"pipolicylabelhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Cache Policylabel Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pipolicylabelhits",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Cache Policylabel(s) Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pipolicylabelhits",),
			),
		),
),


"statcachecontentgroup" => Array(
	"cmddispname"		=>	"statcachecontentgroup",
	"cmdname"		=>	"statcachecontentgroup",
	"license"		=>	Array("NSKF1_CACHE_BIT_POS","NSKF1_CACHE_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"groupnon304hit" => Array(
				"cntrname"	=>	"groupnon304hit",
				"cntrdescr"	=>	"non304 Hits for Content group",
				"tooltip"	=>	"Non304 hits for ContentGroup",
			),
			"group304hit" => Array(
				"cntrname"	=>	"group304hit",
				"cntrdescr"	=>	"304 Hits for Content group",
				"tooltip"	=>	"304 hits for ContentGroup",
			),
			"totcell" => Array(
				"cntrname"	=>	"totcell",
				"cntrdescr"	=>	"Number of objects in contentgroup",
				"tooltip"	=>	"Number of objects in contentgroup",
			),
			"totmarkercell" => Array(
				"cntrname"	=>	"totmarkercell",
				"cntrdescr"	=>	"Number of marker objects in contentgroup",
				"tooltip"	=>	"Number of marker objects in contentgroup",
			),
			"timesflushed" => Array(
				"cntrname"	=>	"timesflushed",
				"cntrdescr"	=>	"Number of times contentgroup is flushed",
				"tooltip"	=>	"Number of times contentgroup is flushed",
			),
			"totmemory" => Array(
				"cntrname"	=>	"totmemory",
				"cntrdescr"	=>	"current memory usage",
				"tooltip"	=>	"current memory usage",
			),
			"maxmemory" => Array(
				"cntrname"	=>	"maxmemory",
				"cntrdescr"	=>	"maximum memory usage limit",
				"tooltip"	=>	"maximum memory usage limit",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Cache ContentGroup Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("groupnon304hit","group304hit","totcell","totmarkercell","timesflushed","totmemory","maxmemory",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Cache ContentGroup(s) Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("groupnon304hit","group304hit","totcell","totmarkercell","timesflushed","totmemory","maxmemory",),
			),
		),
),


"statcachepolicybinding" => Array(
	"cmddispname"		=>	"statcachepolicybinding",
	"cmdname"		=>	"statcachepolicybinding",
	"license"		=>	Array("NSKF1_CACHE_BIT_POS","NSKF1_CACHE_BIT_POS"),
	"cmddescr"	=>	"     ",
	"hidden"	=>	1,
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"pipolicybindinghits" => Array(
				"cntrname"	=>	"pipolicybindinghits",
				"cntrdescr"	=>	"Policy binding hits",
				"tooltip"	=>	"Number of hits on the policy on this binding",
			),
			"pipolicybindinghitsrate" => array (
				"cntrname"	=>	"pipolicybindinghitsrate",
				"cntrdescr"	=>	"",
			),
			"pipolicybindingundefhits" => Array(
				"cntrname"	=>	"pipolicybindingundefhits",
				"cntrdescr"	=>	"Policy binding undef hits",
				"tooltip"	=>	"Number of undef hits on the policy on this binding",
			),
			"pipolicybindingundefhitsrate" => array (
				"cntrname"	=>	"pipolicybindingundefhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Cache Binding Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pipolicybindinghits","pipolicybindingundefhits",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Cache Binding(s) Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pipolicybindinghits","pipolicybindingundefhits",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Cache Binding(s) Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"subent",
				"counters"	=>	array("pipolicybindinghits","pipolicybindingundefhits",),
			),
		),
),


"statclusterinstance" => Array(
	"cmddispname"		=>	"statclusterinstance",
	"enable"            =>      "1",
	"cmdname"		=>	"statclusterinstance",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clid" => Array(
				"argname"		=>	"clid",
				"valtype"		=>	"NS_VALUINT",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "clid",
	"nameargtype"	=> "NS_VALUINT",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"clnumnodes" => Array(
				"cntrname"	=>	"clnumnodes",
				"cntrdescr"	=>	"Cluster size",
				"tooltip"	=>	"Number of nodes in the cluster.",
			),
			"clcurstatus" => Array(
				"cntrname"	=>	"clcurstatus",
				"cntrdescr"	=>	"Cluster status",
				"tooltip"	=>	"State of the cluster.",
				"type"		=>	"NS_VALENUM",
			),
			"clviewleader" => Array(
				"cntrname"	=>	"clviewleader",
				"cntrdescr"	=>	"Configuration coordinator",
				"tooltip"	=>	"NSIP address of the Configuration Coordinator of the cluster.",
				"type"		=>	"NS_VALIP",
			),
			"totsteeredpkts" => Array(
				"cntrname"	=>	"totsteeredpkts",
				"cntrdescr"	=>	"Total steered packets",
				"tooltip"	=>	"Total number of packets steered on the cluster backplane.",
			),
			"clbkplanerx" => Array(
				"cntrname"	=>	"clbkplanerx",
				"cntrdescr"	=>	"Traffic received",
				"tooltip"	=>	"Traffic received on backplane (in mbits)",
			),
			"clbkplanerxrate" => array (
				"cntrname"	=>	"clbkplanerxrate",
				"cntrdescr"	=>	"",
			),
			"clbkplanetx" => Array(
				"cntrname"	=>	"clbkplanetx",
				"cntrdescr"	=>	"Traffic transmitted",
				"tooltip"	=>	"Traffic transmitted from backplane (in mbits)",
			),
			"clbkplanetxrate" => array (
				"cntrname"	=>	"clbkplanetxrate",
				"cntrdescr"	=>	"",
			),
			"numdfddroppkts" => Array(
				"cntrname"	=>	"numdfddroppkts",
				"cntrdescr"	=>	"Dropped steered packets",
				"tooltip"	=>	"Number of steered packets that are dropped.",
			),
			"totpropagationtimeout" => Array(
				"cntrname"	=>	"totpropagationtimeout",
				"cntrdescr"	=>	"Propagation timeout",
				"tooltip"	=>	"Number of times the update to the client timed-out.",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Cluster Instance Summary",
				"header"	=>	array(),
				"format"	=>	"1C",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("clnumnodes","clcurstatus","clviewleader","totsteeredpkts",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Backplane Statistics",
				"header"	=>	array("","Rate (Mbps)","Total (Mbits)",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("clbkplanerx","clbkplanetx",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Cluster Instance Summary",
				"header"	=>	array(),
				"format"	=>	"1C",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("clnumnodes","clcurstatus","clviewleader","totsteeredpkts",),
			),
			Array(
				"dhID"	=>	4,
				"title"	=>	"Backplane Statistics",
				"header"	=>	array("","Rate (Mbps)","Total (Mbits)",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("clbkplanerx","clbkplanetx",),
			),
			Array(
				"dhID"	=>	5,
				"title"	=>	"Error Statistics",
				"header"	=>	array(),
				"format"	=>	"1C",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("numdfddroppkts","totpropagationtimeout",),
			),
		),
),


"statclusternode" => Array(
	"cmddispname"		=>	"statclusternode",
	"cmdname"		=>	"statclusternode",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"nodeid" => Array(
				"argname"		=>	"nodeid",
				"valtype"		=>	"NS_VALUINT",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "nodeid",
	"nameargtype"	=> "NS_VALUINT",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"clsyncstate" => Array(
				"cntrname"	=>	"clsyncstate",
				"cntrdescr"	=>	"Sync state",
				"tooltip"	=>	"Sync state of the cluster node.",
				"type"		=>	"NS_VALENUM",
			),
			"clnodeeffectivehealth" => Array(
				"cntrname"	=>	"clnodeeffectivehealth",
				"cntrdescr"	=>	"Health",
				"tooltip"	=>	"Health of the cluster node.",
				"type"		=>	"NS_VALENUM",
			),
			"clnodeip" => Array(
				"cntrname"	=>	"clnodeip",
				"cntrdescr"	=>	"Node IP",
				"tooltip"	=>	"NSIP address of the cluster node.",
				"type"		=>	"NS_VALIP",
			),
			"clmasterstate" => Array(
				"cntrname"	=>	"clmasterstate",
				"cntrdescr"	=>	"Operational state",
				"tooltip"	=>	"Operational state of the cluster node.",
				"type"		=>	"NS_VALENUM",
			),
			"cltothbtx" => Array(
				"cntrname"	=>	"cltothbtx",
				"cntrdescr"	=>	"Heartbeats transmitted",
				"tooltip"	=>	"Number of heartbeats sent. When executed from the NSIP address, shows the statistics for local node only. For remote node it shows a value of 0. When executed from the cluster IP address, shows all the statistics.",
			),
			"cltothbrx" => Array(
				"cntrname"	=>	"cltothbrx",
				"cntrdescr"	=>	"Heartbeats received",
				"tooltip"	=>	"Number of heartbeats received. When executed from the NSIP address, shows the statistics for local node only. For remote node it shows a value of 0. When executed from the cluster IP address, shows all the statistics.",
			),
			"nnmcurconn" => Array(
				"cntrname"	=>	"nnmcurconn",
				"cntrdescr"	=>	"Current node-node connections",
				"tooltip"	=>	"Number of connections open for node-to-node communication.",
			),
			"nnmtotconntx" => Array(
				"cntrname"	=>	"nnmtotconntx",
				"cntrdescr"	=>	"Node-node messages transmitted",
				"tooltip"	=>	"Number of node-to-node messages sent. When executed from the NSIP address, shows the statistics for local node only. For remote node it shows a value of 0. When executed from the cluster IP address, shows all the statistics.",
			),
			"nnmtotconnrx" => Array(
				"cntrname"	=>	"nnmtotconnrx",
				"cntrdescr"	=>	"Node-node messages received",
				"tooltip"	=>	"Number of node-to-node messages received. When executed from the NSIP address, shows the statistics for local node only. For remote node it shows a value of 0. When executed from the cluster IP address, shows all the statistics.",
			),
			"clptpstate" => Array(
				"cntrname"	=>	"clptpstate",
				"cntrdescr"	=>	"PTP operational state",
				"tooltip"	=>	"PTP state of the node. This state is Master for one node and Slave for the rest. When executed from the NSIP address, shows the statistics for local node only. For remote node it shows UNKNOWN. When executed from the cluster IP address, shows all the statistics.",
				"type"		=>	"NS_VALENUM",
			),
			"clptptx" => Array(
				"cntrname"	=>	"clptptx",
				"cntrdescr"	=>	"PTP packets transmitted",
				"tooltip"	=>	"Number of PTP packets transmitted by the node. When executed from the NSIP address, shows the statistics for local node only. For remote node it shows a value of 0. When executed from the cluster IP address, shows all the statistics.",
			),
			"clptprx" => Array(
				"cntrname"	=>	"clptprx",
				"cntrdescr"	=>	"PTP packets received",
				"tooltip"	=>	"Number of PTP packets received on the node. When executed from the NSIP address, shows the statistics for local node only. For remote node it shows a value of 0. When executed from the cluster IP address, shows all the statistics.",
			),
			"nnmerrmsend" => Array(
				"cntrname"	=>	"nnmerrmsend",
				"cntrdescr"	=>	"Multicast/Broadcast send errors",
				"tooltip"	=>	"Number of errors in sending node-to-node multicast/broadcast messages. When executed from the NSIP address, shows the statistics for local node only. For remote node it shows a value of 0. When executed from the cluster IP address, shows all the statistics.",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"",
				"header"	=>	array(),
				"format"	=>	"1C",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("clnodeip","clmasterstate","clnodeeffectivehealth","clsyncstate","cltothbtx","cltothbrx",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Node-Node Messaging Statistics",
				"header"	=>	array(),
				"format"	=>	"1C",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("nnmcurconn","nnmtotconntx","nnmtotconnrx",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Precision Time Protocol(PTP) Statistics",
				"header"	=>	array(),
				"format"	=>	"1C",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("clptpstate","clptptx","clptprx",),
			),
			Array(
				"dhID"	=>	4,
				"title"	=>	"Cluster Node Summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("clnodeip","clmasterstate","clnodeeffectivehealth","clsyncstate","cltothbtx","cltothbrx",),
			),
		),
),


"statcmp" => Array(
	"cmddispname"		=>	"statcmp",
	"cmdname"		=>	"statcmp",
	"license"		=>	Array("NSKF1_CMP_BIT_POS","NSKF1_CMP_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"delbwsaving" => Array(
				"cntrname"	=>	"delbwsaving",
				"cntrdescr"	=>	"Bandwidth saving (%)",
				"cntrdescr2"	=>	"Delta Compression Bandwidth saving (%)",
				"tooltip"	=>	"Bandwidth saving from delta compression expressed as percentage.",
			),
			"delcmpratio" => Array(
				"cntrname"	=>	"delcmpratio",
				"cntrdescr"	=>	"Delta compression ratio",
				"tooltip"	=>	"Ratio of compressible data received to compressed data transmitted.If this ratio is one (uncmp:1.0) that means compression is disabled or we are not able to compress even a single compressible packet.",
			),
			"decomptcpratio" => Array(
				"cntrname"	=>	"decomptcpratio",
				"cntrdescr"	=>	"Decompression ratio",
				"tooltip"	=>	"Ratio of decompressed data transmitted to compressed data received.",
			),
			"decomptcpbandwidthsaving" => Array(
				"cntrname"	=>	"decomptcpbandwidthsaving",
				"cntrdescr"	=>	"Bandwidth saving (%)",
				"cntrdescr2"	=>	"Decompression Bandwidth saving (%)",
				"tooltip"	=>	"Bandwidth saving from TCP compression expressed as percentage.",
			),
			"comptcpratio" => Array(
				"cntrname"	=>	"comptcpratio",
				"cntrdescr"	=>	"TCP compression ratio",
				"tooltip"	=>	"Ratio of compressible data received to compressed data transmitted.If this ratio is one (uncmp:1.0) that means compression is disabled or we are not able to compress even a single compressible packet.",
			),
			"comptcpbandwidthsaving" => Array(
				"cntrname"	=>	"comptcpbandwidthsaving",
				"cntrdescr"	=>	"TCP Bandwidth saving (%)",
				"tooltip"	=>	"Bandwidth saving from TCP compression expressed as percentage.",
			),
			"comptotaldatacompressionratio" => Array(
				"cntrname"	=>	"comptotaldatacompressionratio",
				"cntrdescr"	=>	"Total HTTP compression ratio",
				"tooltip"	=>	"Ratio of total HTTP data received to total HTTP data transmitted.",
			),
			"comphttpbandwidthsaving" => Array(
				"cntrname"	=>	"comphttpbandwidthsaving",
				"cntrdescr"	=>	"HTTP Bandwidth saving (%)",
				"tooltip"	=>	"Bandwidth saving from TCP compression expressed as percentage.",
			),
			"compratio" => Array(
				"cntrname"	=>	"compratio",
				"cntrdescr"	=>	"HTTP compression ratio",
				"tooltip"	=>	"Ratio of the compressible data received from the server to the compressed data sent to the client.",
			),
			"comptotalrequests" => Array(
				"cntrname"	=>	"comptotalrequests",
				"cntrdescr"	=>	"HTTP compression requests",
				"tooltip"	=>	"Number of HTTP compression requests the NetScaler receives for which the response is successfully compressed. For example, after you enable compression and configure services, if you send HTTP requests to the NetScaler using a HTTP client that supports compression, and NetScaler compresses the corresponding response, this counter is incremented.",
			),
			"comprequestsrate" => array (
				"cntrname"	=>	"comprequestsrate",
				"cntrdescr"	=>	"",
			),
			"comptotalrxbytes" => Array(
				"cntrname"	=>	"comptotalrxbytes",
				"cntrdescr"	=>	"Compressible bytes received",
				"cntrdescr2"	=>	"HTTP Compressible bytes received",
				"tooltip"	=>	"Number of bytes that can be compressed, which the NetScaler receives from the server. This gives the content length of the response that the NetScaler receives from server.",
			),
			"comprxbytesrate" => array (
				"cntrname"	=>	"comprxbytesrate",
				"cntrdescr"	=>	"",
			),
			"comptotaltxbytes" => Array(
				"cntrname"	=>	"comptotaltxbytes",
				"cntrdescr"	=>	"Compressed bytes transmitted",
				"cntrdescr2"	=>	"HTTP Compressed bytes transmitted",
				"tooltip"	=>	"Number of bytes the NetScaler sends to the client after compressing the response from the server.",
			),
			"comptxbytesrate" => array (
				"cntrname"	=>	"comptxbytesrate",
				"cntrdescr"	=>	"",
			),
			"comptotalrxpackets" => Array(
				"cntrname"	=>	"comptotalrxpackets",
				"cntrdescr"	=>	"Compressible packets received",
				"cntrdescr2"	=>	"HTTP Compressible packets received",
				"tooltip"	=>	"Number of HTTP packets that can be compressed, which the NetScaler receives from the server.",
			),
			"comprxpacketsrate" => array (
				"cntrname"	=>	"comprxpacketsrate",
				"cntrdescr"	=>	"",
			),
			"comptotaltxpackets" => Array(
				"cntrname"	=>	"comptotaltxpackets",
				"cntrdescr"	=>	"Compressed packets transmitted",
				"cntrdescr2"	=>	"HTTP Compressed packets transmitted",
				"tooltip"	=>	"Number of HTTP packets that the NetScaler sends to the client after compressing the response from the server.",
			),
			"comptxpacketsrate" => array (
				"cntrname"	=>	"comptxpacketsrate",
				"cntrdescr"	=>	"",
			),
			"comptcptotalrxbytes" => Array(
				"cntrname"	=>	"comptcptotalrxbytes",
				"cntrdescr"	=>	"Compressible bytes received",
				"cntrdescr2"	=>	"TCP Compressible bytes received",
				"tooltip"	=>	"Number of bytes that can be compressed, which the NetScaler receives from the server. This gives the content length of the response that the NetScaler receives from server.",
			),
			"comptcprxbytesrate" => array (
				"cntrname"	=>	"comptcprxbytesrate",
				"cntrdescr"	=>	"",
			),
			"comptcptotalrxpackets" => Array(
				"cntrname"	=>	"comptcptotalrxpackets",
				"cntrdescr"	=>	"Compressible packets received",
				"cntrdescr2"	=>	"TCP Compressible packets received",
				"tooltip"	=>	"Total number of compressible packets received by NetScaler.",
			),
			"comptcprxpacketsrate" => array (
				"cntrname"	=>	"comptcprxpacketsrate",
				"cntrdescr"	=>	"",
			),
			"comptcptotaltxbytes" => Array(
				"cntrname"	=>	"comptcptotaltxbytes",
				"cntrdescr"	=>	"Compressed bytes transmitted",
				"cntrdescr2"	=>	"TCP Compressed bytes transmitted",
				"tooltip"	=>	"Number of bytes that the NetScaler sends to the client after compressing the response from the server.",
			),
			"comptcptxbytesrate" => array (
				"cntrname"	=>	"comptcptxbytesrate",
				"cntrdescr"	=>	"",
			),
			"comptcptotaltxpackets" => Array(
				"cntrname"	=>	"comptcptotaltxpackets",
				"cntrdescr"	=>	"Compressed packets transmitted",
				"cntrdescr2"	=>	"TCP Compressed packets transmitted",
				"tooltip"	=>	"Number of TCP packets that the NetScaler sends to the client after compressing the response from the server.",
			),
			"comptcptxpacketsrate" => array (
				"cntrname"	=>	"comptcptxpacketsrate",
				"cntrdescr"	=>	"",
			),
			"comptcptotalquantum" => Array(
				"cntrname"	=>	"comptcptotalquantum",
				"cntrdescr"	=>	"Quantum compression",
				"tooltip"	=>	"Number of times the NetScaler compresses a quantum of data.  NetScaler buffers the data received from the server till it reaches the quantum size and then compresses the buffered data and transmits to the client.",
			),
			"comptcpquantumrate" => array (
				"cntrname"	=>	"comptcpquantumrate",
				"cntrdescr"	=>	"",
			),
			"comptcptotalpush" => Array(
				"cntrname"	=>	"comptcptotalpush",
				"cntrdescr"	=>	"Push flag compression",
				"tooltip"	=>	"Number of times the NetScaler compresses data on receiving a TCP PUSH flag from the server. The PUSH flag ensures that data is compressed immediately without waiting for the buffered data size to reach the quantum size.",
			),
			"comptcppushrate" => array (
				"cntrname"	=>	"comptcppushrate",
				"cntrdescr"	=>	"",
			),
			"comptcptotaleoi" => Array(
				"cntrname"	=>	"comptcptotaleoi",
				"cntrdescr"	=>	"End Of Input compression",
				"tooltip"	=>	"Number of times the NetScaler compresses data on receiving End Of Input (FIN packet).  When the NetScaler receives End Of Input (FIN packet), it compresses the buffered data immediately without waiting for the buffered data size to reach the quantum size.",
			),
			"comptcpeoirate" => array (
				"cntrname"	=>	"comptcpeoirate",
				"cntrdescr"	=>	"",
			),
			"comptcptotaltimer" => Array(
				"cntrname"	=>	"comptcptotaltimer",
				"cntrdescr"	=>	"Timer compression",
				"tooltip"	=>	"Number of times the NetScaler compresses data on expiration of data accumulation timer. The timer expires if the server response is very slow and consequently, the NetScaler does not receive response for a certain amount of time.  Under such a condition, the NetScaler compresses the buffered data immediately without waiting for the buffered data size to reach the quantum size.",
			),
			"comptcptimerrate" => array (
				"cntrname"	=>	"comptcptimerrate",
				"cntrdescr"	=>	"",
			),
			"decomptcprxbytes" => Array(
				"cntrname"	=>	"decomptcprxbytes",
				"cntrdescr"	=>	"Compressed bytes received",
				"tooltip"	=>	"Total number of compressed bytes received by NetScaler.",
			),
			"decomptcprxbytesrate" => array (
				"cntrname"	=>	"decomptcprxbytesrate",
				"cntrdescr"	=>	"",
			),
			"decomptcprxpackets" => Array(
				"cntrname"	=>	"decomptcprxpackets",
				"cntrdescr"	=>	"Compressed packets received",
				"tooltip"	=>	"Total number of compressed packets received by NetScaler.",
			),
			"decomptcprxpacketsrate" => array (
				"cntrname"	=>	"decomptcprxpacketsrate",
				"cntrdescr"	=>	"",
			),
			"decomptcptxbytes" => Array(
				"cntrname"	=>	"decomptcptxbytes",
				"cntrdescr"	=>	"Decompressed bytes transmitted",
				"tooltip"	=>	"Total number of decompressed bytes transmitted by NetScaler.",
			),
			"decomptcptxbytesrate" => array (
				"cntrname"	=>	"decomptcptxbytesrate",
				"cntrdescr"	=>	"",
			),
			"decomptcptxpackets" => Array(
				"cntrname"	=>	"decomptcptxpackets",
				"cntrdescr"	=>	"Decompressed packets transmitted",
				"tooltip"	=>	"Total number of decompressed packets transmitted by NetScaler.",
			),
			"decomptcptxpacketsrate" => array (
				"cntrname"	=>	"decomptcptxpacketsrate",
				"cntrdescr"	=>	"",
			),
			"decomptcperrdata" => Array(
				"cntrname"	=>	"decomptcperrdata",
				"cntrdescr"	=>	"Wrong data",
				"tooltip"	=>	"Number of data errors encountered while decompressing.",
			),
			"decomptcperrdatarate" => array (
				"cntrname"	=>	"decomptcperrdatarate",
				"cntrdescr"	=>	"",
			),
			"decomptcperrlessdata" => Array(
				"cntrname"	=>	"decomptcperrlessdata",
				"cntrdescr"	=>	"Less Data",
				"tooltip"	=>	"Number of times NetScaler received less data than declared by protocol.",
			),
			"decomptcperrlessdatarate" => array (
				"cntrname"	=>	"decomptcperrlessdatarate",
				"cntrdescr"	=>	"",
			),
			"decomptcperrmoredata" => Array(
				"cntrname"	=>	"decomptcperrmoredata",
				"cntrdescr"	=>	"More Data",
				"tooltip"	=>	"Number of times NetScaler received more data than declared by protocol.",
			),
			"decomptcperrmoredatarate" => array (
				"cntrname"	=>	"decomptcperrmoredatarate",
				"cntrdescr"	=>	"",
			),
			"decomptcperrmemory" => Array(
				"cntrname"	=>	"decomptcperrmemory",
				"cntrdescr"	=>	"Memory failures",
				"tooltip"	=>	"Number of times memory failures occurred while decompressing.",
			),
			"decomptcperrmemoryrate" => array (
				"cntrname"	=>	"decomptcperrmemoryrate",
				"cntrdescr"	=>	"",
			),
			"decomptcperrunknown" => Array(
				"cntrname"	=>	"decomptcperrunknown",
				"cntrdescr"	=>	"Unknown",
				"tooltip"	=>	"Number of times unknown errors occurred while decompressing.",
			),
			"decomptcperrunknownrate" => array (
				"cntrname"	=>	"decomptcperrunknownrate",
				"cntrdescr"	=>	"",
			),
			"delcomptotalrequests" => Array(
				"cntrname"	=>	"delcomptotalrequests",
				"cntrdescr"	=>	"Delta compression requests",
				"tooltip"	=>	"Total number of delta compression requests received by NetScaler.",
			),
			"delcomprequestsrate" => array (
				"cntrname"	=>	"delcomprequestsrate",
				"cntrdescr"	=>	"",
			),
			"delcompdone" => Array(
				"cntrname"	=>	"delcompdone",
				"cntrdescr"	=>	"Delta compression applied",
				"tooltip"	=>	"Total number of delta compressions done by NetScaler.",
			),
			"delcompdonerate" => array (
				"cntrname"	=>	"delcompdonerate",
				"cntrdescr"	=>	"",
			),
			"delcomptcprxbytes" => Array(
				"cntrname"	=>	"delcomptcprxbytes",
				"cntrdescr"	=>	"Compressible bytes received",
				"cntrdescr2"	=>	"Delta Compressible bytes received",
				"tooltip"	=>	"Total number of delta-compressible bytes received by NetScaler.",
			),
			"delcomptcprxbytesrate" => array (
				"cntrname"	=>	"delcomptcprxbytesrate",
				"cntrdescr"	=>	"",
			),
			"delcomptcptxbytes" => Array(
				"cntrname"	=>	"delcomptcptxbytes",
				"cntrdescr"	=>	"Compressed bytes transmitted",
				"cntrdescr2"	=>	"Delta Compressed bytes transmitted",
				"tooltip"	=>	"Total number of delta-compressed bytes transmitted by NetScaler.",
			),
			"delcomptcptxbytesrate" => array (
				"cntrname"	=>	"delcomptcptxbytesrate",
				"cntrdescr"	=>	"",
			),
			"delcompfirstaccess" => Array(
				"cntrname"	=>	"delcompfirstaccess",
				"cntrdescr"	=>	"First-time access",
				"tooltip"	=>	"Total number of delta compression first accesses.",
			),
			"delcompfirstaccessrate" => array (
				"cntrname"	=>	"delcompfirstaccessrate",
				"cntrdescr"	=>	"",
			),
			"delcomptcprxpackets" => Array(
				"cntrname"	=>	"delcomptcprxpackets",
				"cntrdescr"	=>	"Compressible packets received",
				"cntrdescr2"	=>	"Delta Compressible packets received",
				"tooltip"	=>	"Number of delta-compressible packets received.",
			),
			"delcomptcprxpacketsrate" => array (
				"cntrname"	=>	"delcomptcprxpacketsrate",
				"cntrdescr"	=>	"",
			),
			"delcomptcptxpackets" => Array(
				"cntrname"	=>	"delcomptcptxpackets",
				"cntrdescr"	=>	"Compressed packets transmitted",
				"cntrdescr2"	=>	"Delta Compressed packets transmitted",
				"tooltip"	=>	"Total number of delta-compressed packets transmitted by NetScaler.",
			),
			"delcomptcptxpacketsrate" => array (
				"cntrname"	=>	"delcomptcptxpacketsrate",
				"cntrdescr"	=>	"",
			),
			"delcompbaseserved" => Array(
				"cntrname"	=>	"delcompbaseserved",
				"cntrdescr"	=>	"Basefile requests served",
				"tooltip"	=>	"Total number of basefile requests served by NetScaler.",
			),
			"delcompbaseservedrate" => array (
				"cntrname"	=>	"delcompbaseservedrate",
				"cntrdescr"	=>	"",
			),
			"delcompbasetcptxbytes" => Array(
				"cntrname"	=>	"delcompbasetcptxbytes",
				"cntrdescr"	=>	"Basefile bytes transmitted",
				"tooltip"	=>	"Number of basefile bytes transmitted by NetScaler.",
			),
			"delcompbasetcptxbytesrate" => array (
				"cntrname"	=>	"delcompbasetcptxbytesrate",
				"cntrdescr"	=>	"",
			),
			"delcomperrbypassed" => Array(
				"cntrname"	=>	"delcomperrbypassed",
				"cntrdescr"	=>	"Delta compression bypassed",
				"tooltip"	=>	"Number of times delta-compression bypassed by NetScaler.",
			),
			"delcomperrbypassedrate" => array (
				"cntrname"	=>	"delcomperrbypassedrate",
				"cntrdescr"	=>	"",
			),
			"delcomperrbfilewhdrfailed" => Array(
				"cntrname"	=>	"delcomperrbfilewhdrfailed",
				"cntrdescr"	=>	"Basefile write header failed",
				"tooltip"	=>	"Number of times basefile could not be updated in NetScaler cache.",
			),
			"delcomperrbfilewhdrfailedrate" => array (
				"cntrname"	=>	"delcomperrbfilewhdrfailedrate",
				"cntrdescr"	=>	"",
			),
			"delcomperrnostoremiss" => Array(
				"cntrname"	=>	"delcomperrnostoremiss",
				"cntrdescr"	=>	"Basefile no-store miss",
				"tooltip"	=>	"Number of times basefile was not found in NetScaler cache.",
			),
			"delcomperrnostoremissrate" => array (
				"cntrname"	=>	"delcomperrnostoremissrate",
				"cntrdescr"	=>	"",
			),
			"delcomperrreqinfotoobig" => Array(
				"cntrname"	=>	"delcomperrreqinfotoobig",
				"cntrdescr"	=>	"Request information too big",
				"tooltip"	=>	"Number of times basefile request URL was too large.",
			),
			"delcomperrreqinfotoobigrate" => array (
				"cntrname"	=>	"delcomperrreqinfotoobigrate",
				"cntrdescr"	=>	"",
			),
			"delcomperrreqinfoallocfail" => Array(
				"cntrname"	=>	"delcomperrreqinfoallocfail",
				"cntrdescr"	=>	"Request info alloc failed",
				"tooltip"	=>	"Number of times requested basefile could not be allocated.",
			),
			"delcomperrreqinfoallocfailrate" => array (
				"cntrname"	=>	"delcomperrreqinfoallocfailrate",
				"cntrdescr"	=>	"",
			),
			"delcomperrsessallocfail" => Array(
				"cntrname"	=>	"delcomperrsessallocfail",
				"cntrdescr"	=>	"Session allocation failed",
				"tooltip"	=>	"Number of times delta compression session could not be allocated.",
			),
			"delcomperrsessallocfailrate" => array (
				"cntrname"	=>	"delcomperrsessallocfailrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"HTTP Compression",
				"header"	=>	array("","Value",),
				"format"	=>	"1C",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("comptotalrequests","comptotalrxbytes","comptotaltxbytes","compratio",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"HTTP Compression Statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("comptotalrequests","comptotalrxbytes","comptotalrxpackets","comptotaltxbytes","comptotaltxpackets",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"",
				"header"	=>	array("","Value",),
				"format"	=>	"1C",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("comphttpbandwidthsaving","compratio",),
			),
			Array(
				"dhID"	=>	4,
				"title"	=>	"",
				"header"	=>	array("","",),
				"format"	=>	"1C",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("comptotaldatacompressionratio",),
			),
			Array(
				"dhID"	=>	5,
				"title"	=>	"TCP Compression Statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("comptcptotalrxbytes","comptcptotalrxpackets","comptcptotaltxbytes","comptcptotaltxpackets",),
			),
			Array(
				"dhID"	=>	6,
				"title"	=>	"",
				"header"	=>	array("","Value",),
				"format"	=>	"1C",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("comptcpbandwidthsaving","comptcpratio",),
			),
			Array(
				"dhID"	=>	7,
				"title"	=>	"",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("comptcptotalquantum","comptcptotalpush","comptcptotaleoi","comptcptotaltimer",),
			),
			Array(
				"dhID"	=>	8,
				"title"	=>	"Decompression Statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("decomptcprxbytes","decomptcprxpackets","decomptcptxbytes","decomptcptxpackets",),
			),
			Array(
				"dhID"	=>	9,
				"title"	=>	"",
				"header"	=>	array("","Value",),
				"format"	=>	"1C",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("decomptcpbandwidthsaving","decomptcpratio",),
			),
			Array(
				"dhID"	=>	10,
				"title"	=>	"Decompression Errors",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("decomptcperrdata","decomptcperrlessdata","decomptcperrmoredata","decomptcperrmemory","decomptcperrunknown",),
			),
		),
),


"statcmppolicy" => Array(
	"cmddispname"		=>	"statcmppolicy",
	"cmdname"		=>	"statcmppolicy",
	"license"		=>	Array("NSKF1_CMP_BIT_POS","NSKF1_CMP_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"pipolicyhits" => Array(
				"cntrname"	=>	"pipolicyhits",
				"cntrdescr"	=>	"Policy hits",
				"tooltip"	=>	"Number of hits on the policy",
			),
			"pipolicyhitsrate" => array (
				"cntrname"	=>	"pipolicyhitsrate",
				"cntrdescr"	=>	"",
			),
			"pipolicyundefhits" => Array(
				"cntrname"	=>	"pipolicyundefhits",
				"cntrdescr"	=>	"Policy undef hits",
				"tooltip"	=>	"Number of undef hits on the policy",
			),
			"pipolicyundefhitsrate" => array (
				"cntrname"	=>	"pipolicyundefhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Compression Policy Statistics",
				"header"	=>	array(" ","Hits","Rate(/s)","UndefHits","Rate(/s)",),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pipolicyhits","pipolicyundefhits",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Compression Policy(s) Statistics",
				"header"	=>	array(" ","Hits","Rate(/s)","UndefHits","Rate(/s)",),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pipolicyhits","pipolicyundefhits",),
			),
		),
),


"statcmppolicylabel" => Array(
	"cmddispname"		=>	"statcmppolicylabel",
	"cmdname"		=>	"statcmppolicylabel",
	"license"		=>	Array("NSKF1_CMP_BIT_POS","NSKF1_CMP_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"labelname" => Array(
				"argname"		=>	"labelname",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "labelname",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"pipolicylabelhits" => Array(
				"cntrname"	=>	"pipolicylabelhits",
				"cntrdescr"	=>	"Policy Label Hits",
				"tooltip"	=>	"Number of times policy label was invoked.",
			),
			"pipolicylabelhitsrate" => array (
				"cntrname"	=>	"pipolicylabelhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Compression Policylabel Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pipolicylabelhits",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Compression Policylabel(s) Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pipolicylabelhits",),
			),
		),
),


"statcmppolicybinding" => Array(
	"cmddispname"		=>	"statcmppolicybinding",
	"cmdname"		=>	"statcmppolicybinding",
	"license"		=>	Array("NSKF1_CMP_BIT_POS","NSKF1_CMP_BIT_POS"),
	"cmddescr"	=>	"     ",
	"hidden"	=>	1,
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"pipolicybindinghits" => Array(
				"cntrname"	=>	"pipolicybindinghits",
				"cntrdescr"	=>	"Policy binding hits",
				"tooltip"	=>	"Number of hits on the policy on this binding",
			),
			"pipolicybindinghitsrate" => array (
				"cntrname"	=>	"pipolicybindinghitsrate",
				"cntrdescr"	=>	"",
			),
			"pipolicybindingundefhits" => Array(
				"cntrname"	=>	"pipolicybindingundefhits",
				"cntrdescr"	=>	"Policy binding undef hits",
				"tooltip"	=>	"Number of undef hits on the policy on this binding",
			),
			"pipolicybindingundefhitsrate" => array (
				"cntrname"	=>	"pipolicybindingundefhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"compression Binding Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pipolicybindinghits","pipolicybindingundefhits",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"compression Binding(s) Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pipolicybindinghits","pipolicybindingundefhits",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"compression Binding(s) Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"subent",
				"counters"	=>	array("pipolicybindinghits","pipolicybindingundefhits",),
			),
		),
),


"statfeo" => Array(
	"cmddispname"		=>	"statfeo",
	"cmdname"		=>	"statfeo",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"optcacheobjects" => Array(
				"cntrname"	=>	"optcacheobjects",
				"cntrdescr"	=>	"Optimized cache objects",
				"tooltip"	=>	"Total number of optimized cache objects ready to be served.",
			),
			"optcacheobjectsrate" => array (
				"cntrname"	=>	"optcacheobjectsrate",
				"cntrdescr"	=>	"",
			),
			"origcacheobjects" => Array(
				"cntrname"	=>	"origcacheobjects",
				"cntrdescr"	=>	"Original cache objects",
				"tooltip"	=>	"Total number of original cache objects ready to be served.",
			),
			"origcacheobjectsrate" => array (
				"cntrname"	=>	"origcacheobjectsrate",
				"cntrdescr"	=>	"",
			),
			"totalimgsdomainsharded" => Array(
				"cntrname"	=>	"totalimgsdomainsharded",
				"cntrdescr"	=>	"Domain Sharded",
				"tooltip"	=>	"Total no of images whose domain has been set from shards.",
			),
			"imgsdomainshardedrate" => array (
				"cntrname"	=>	"imgsdomainshardedrate",
				"cntrdescr"	=>	"",
			),
			"totalimgslazyloaded" => Array(
				"cntrname"	=>	"totalimgslazyloaded",
				"cntrdescr"	=>	"Images lazy loaded",
				"tooltip"	=>	"Total number of images modified for lazy loading.",
			),
			"imgslazyloadedrate" => array (
				"cntrname"	=>	"imgslazyloadedrate",
				"cntrdescr"	=>	"",
			),
			"toturireplaced" => Array(
				"cntrname"	=>	"toturireplaced",
				"cntrdescr"	=>	"URI Replaced",
				"tooltip"	=>	"Total number of URI replaced.",
			),
			"urireplacedrate" => array (
				"cntrname"	=>	"urireplacedrate",
				"cntrdescr"	=>	"",
			),
			"totalimgsinlinedincss" => Array(
				"cntrname"	=>	"totalimgsinlinedincss",
				"cntrdescr"	=>	"Inlined Imgs in CSS",
				"tooltip"	=>	"Total number of images inlined in CSS.",
			),
			"imgsinlinedincssrate" => array (
				"cntrname"	=>	"imgsinlinedincssrate",
				"cntrdescr"	=>	"",
			),
			"totalinlinedjs" => Array(
				"cntrname"	=>	"totalinlinedjs",
				"cntrdescr"	=>	"Inlined JS",
				"tooltip"	=>	"Total number of inlined JS files.",
			),
			"inlinedjsrate" => array (
				"cntrname"	=>	"inlinedjsrate",
				"cntrdescr"	=>	"",
			),
			"totalinlinedcss" => Array(
				"cntrname"	=>	"totalinlinedcss",
				"cntrdescr"	=>	"Inlined CSS",
				"tooltip"	=>	"Total number of inlined CSS files.",
			),
			"inlinedcssrate" => array (
				"cntrname"	=>	"inlinedcssrate",
				"cntrdescr"	=>	"",
			),
			"totalinlinedimgs" => Array(
				"cntrname"	=>	"totalinlinedimgs",
				"cntrdescr"	=>	"Inlined images",
				"tooltip"	=>	"Total number of inlined images in HTML.",
			),
			"inlinedimgsrate" => array (
				"cntrname"	=>	"inlinedimgsrate",
				"cntrdescr"	=>	"",
			),
			"totaldatasavings" => Array(
				"cntrname"	=>	"totaldatasavings",
				"cntrdescr"	=>	"Data Savings in KB",
				"tooltip"	=>	"Total data savings in bytes.",
			),
			"datasavingsrate" => array (
				"cntrname"	=>	"datasavingsrate",
				"cntrdescr"	=>	"",
			),
			"htmlcommentsremoved" => Array(
				"cntrname"	=>	"htmlcommentsremoved",
				"cntrdescr"	=>	"HTML comments removed",
				"tooltip"	=>	"The total number of HTML comments removed.",
			),
			"htmlcommentsremovedrate" => array (
				"cntrname"	=>	"htmlcommentsremovedrate",
				"cntrdescr"	=>	"",
			),
			"totalcacheextended" => Array(
				"cntrname"	=>	"totalcacheextended",
				"cntrdescr"	=>	"Cache extended",
				"tooltip"	=>	"The total number of objects cache extended.",
			),
			"cacheextendedrate" => array (
				"cntrname"	=>	"cacheextendedrate",
				"cntrdescr"	=>	"",
			),
			"totalcsscombined" => Array(
				"cntrname"	=>	"totalcsscombined",
				"cntrdescr"	=>	"CSS combined",
				"tooltip"	=>	"The total number of CSS combined.",
			),
			"csscombinedrate" => array (
				"cntrname"	=>	"csscombinedrate",
				"cntrdescr"	=>	"",
			),
			"totalimporttolink" => Array(
				"cntrname"	=>	"totalimporttolink",
				"cntrdescr"	=>	"Import to Links",
				"tooltip"	=>	"The total number of CSS imports converted to links",
			),
			"importtolinkrate" => array (
				"cntrname"	=>	"importtolinkrate",
				"cntrdescr"	=>	"",
			),
			"totaljsmoved" => Array(
				"cntrname"	=>	"totaljsmoved",
				"cntrdescr"	=>	"JS moved to end",
				"tooltip"	=>	"The total number of JS moved to end.",
			),
			"jsmovedrate" => array (
				"cntrname"	=>	"jsmovedrate",
				"cntrdescr"	=>	"",
			),
			"totalcssmoved" => Array(
				"cntrname"	=>	"totalcssmoved",
				"cntrdescr"	=>	"CSS moved to head",
				"tooltip"	=>	"The total number of CSS moved to head.",
			),
			"cssmovedrate" => array (
				"cntrname"	=>	"cssmovedrate",
				"cntrdescr"	=>	"",
			),
			"totaljsmin" => Array(
				"cntrname"	=>	"totaljsmin",
				"cntrdescr"	=>	"JS Minifed",
				"tooltip"	=>	"The total number of JS files minified.",
			),
			"jsminrate" => array (
				"cntrname"	=>	"jsminrate",
				"cntrdescr"	=>	"",
			),
			"totalcssmin" => Array(
				"cntrname"	=>	"totalcssmin",
				"cntrdescr"	=>	"CSS Minifed",
				"tooltip"	=>	"The total number of CSS files minified.",
			),
			"cssminrate" => array (
				"cntrname"	=>	"cssminrate",
				"cntrdescr"	=>	"",
			),
			"totalimgstojxr" => Array(
				"cntrname"	=>	"totalimgstojxr",
				"cntrdescr"	=>	"Images to Jpegxr",
				"tooltip"	=>	"The total number of images converted to JPEGXR format.",
			),
			"imgstojxrrate" => array (
				"cntrname"	=>	"imgstojxrrate",
				"cntrdescr"	=>	"",
			),
			"totalimgstowebp" => Array(
				"cntrname"	=>	"totalimgstowebp",
				"cntrdescr"	=>	"Images to WebP",
				"tooltip"	=>	"The total number of images converted to WEBP format.",
			),
			"imgstowebprate" => array (
				"cntrname"	=>	"imgstowebprate",
				"cntrdescr"	=>	"",
			),
			"totaljpegsoptimized" => Array(
				"cntrname"	=>	"totaljpegsoptimized",
				"cntrdescr"	=>	"JPEGs Optimized",
				"tooltip"	=>	"The total number of JPEG format images optimized.",
			),
			"jpegsoptimizedrate" => array (
				"cntrname"	=>	"jpegsoptimizedrate",
				"cntrdescr"	=>	"",
			),
			"totalgifstopng" => Array(
				"cntrname"	=>	"totalgifstopng",
				"cntrdescr"	=>	"Gif to PNGs",
				"tooltip"	=>	"The total number of images converted from GIF to PNG format.",
			),
			"gifstopngrate" => array (
				"cntrname"	=>	"gifstopngrate",
				"cntrdescr"	=>	"",
			),
			"totalimgsresized" => Array(
				"cntrname"	=>	"totalimgsresized",
				"cntrdescr"	=>	"Images Resized",
				"tooltip"	=>	"The total number of images resized to dimensions in the <img> tag.",
			),
			"imgsresizedrate" => array (
				"cntrname"	=>	"imgsresizedrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("totalimgsresized","totalgifstopng","totaljpegsoptimized","totalimgstowebp","totalimgstojxr","totalcssmin","totaljsmin","totalcssmoved","totaljsmoved","totalimporttolink","totalcsscombined","totalcacheextended","htmlcommentsremoved","totaldatasavings","totalinlinedimgs","totalinlinedcss","totalinlinedjs","totalimgsinlinedincss","toturireplaced","totalimgslazyloaded","totalimgsdomainsharded",),
				"charts"	=>	array(	array("feo_default_stats",),
								),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"",
				"header"	=>	array(),
				"format"	=>	"1C",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("origcacheobjects","optcacheobjects",),
			),
		),
),


"statcrvserver" => Array(
	"cmddispname"		=>	"statcrvserver",
	"enable"            =>      "1",
	"cmdname"		=>	"statcrvserver",
	"license"		=>	Array("NSKF1_CACHEREDIRECT_BIT_POS","NSKF1_CACHEREDIRECT_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"primaryipaddress" => Array(
				"cntrname"	=>	"primaryipaddress",
				"cntrdescr"	=>	"IP address",
				"tooltip"	=>	"The IP address on which the service is running.",
				"type"		=>	"NS_VALIPSTR",
			),
			"primaryport" => Array(
				"cntrname"	=>	"primaryport",
				"cntrdescr"	=>	"Port",
				"tooltip"	=>	"The port on which the service is running.",
				"type"		=>	"NS_VALPORT",
			),
			"type" => Array(
				"cntrname"	=>	"type",
				"cntrdescr"	=>	"Vserver protocol",
				"tooltip"	=>	"Protocol associated with the vserver",
				"type"		=>	"NS_VALENUM",
			),
			"state" => Array(
				"cntrname"	=>	"state",
				"cntrdescr"	=>	"State",
				"tooltip"	=>	"Current state of the server. Possible values are UP, DOWN, UNKNOWN, OFS(Out of Service), TROFS(Transition Out of Service), TROFS_DOWN(Down When going Out of Service)",
				"type"		=>	"NS_VALENUM",
			),
			"tothits" => Array(
				"cntrname"	=>	"tothits",
				"cntrdescr"	=>	"Vserver hits",
				"tooltip"	=>	"Total vserver hits",
			),
			"hitsrate" => array (
				"cntrname"	=>	"hitsrate",
				"cntrdescr"	=>	"",
			),
			"totalrequests" => Array(
				"cntrname"	=>	"totalrequests",
				"cntrdescr"	=>	"Requests",
				"tooltip"	=>	"Total number of requests received on this service or virtual server. (This applies to HTTP/SSL services and servers.)",
			),
			"requestsrate" => array (
				"cntrname"	=>	"requestsrate",
				"cntrdescr"	=>	"",
			),
			"totalresponses" => Array(
				"cntrname"	=>	"totalresponses",
				"cntrdescr"	=>	"Responses",
				"tooltip"	=>	"Number of responses received on this service or virtual server. (This applies to HTTP/SSL services and servers.)",
			),
			"responsesrate" => array (
				"cntrname"	=>	"responsesrate",
				"cntrdescr"	=>	"",
			),
			"totalrequestbytes" => Array(
				"cntrname"	=>	"totalrequestbytes",
				"cntrdescr"	=>	"Request bytes",
				"tooltip"	=>	"Total number of request bytes received on this service or virtual server.",
			),
			"requestbytesrate" => array (
				"cntrname"	=>	"requestbytesrate",
				"cntrdescr"	=>	"",
			),
			"totalresponsebytes" => Array(
				"cntrname"	=>	"totalresponsebytes",
				"cntrdescr"	=>	"Response bytes",
				"tooltip"	=>	"Number of response bytes received by this service or virtual server.",
			),
			"responsebytesrate" => array (
				"cntrname"	=>	"responsebytesrate",
				"cntrdescr"	=>	"",
			),
			"totalpktsrecvd" => Array(
				"cntrname"	=>	"totalpktsrecvd",
				"cntrdescr"	=>	"Total Packets rcvd",
				"tooltip"	=>	"Total number of packets received by this service or virtual server.",
			),
			"pktsrecvdrate" => array (
				"cntrname"	=>	"pktsrecvdrate",
				"cntrdescr"	=>	"",
			),
			"totalpktssent" => Array(
				"cntrname"	=>	"totalpktssent",
				"cntrdescr"	=>	"Total Packets sent",
				"tooltip"	=>	"Total number of packets sent.",
			),
			"pktssentrate" => array (
				"cntrname"	=>	"pktssentrate",
				"cntrdescr"	=>	"",
			),
			"invalidrequestresponse" => Array(
				"cntrname"	=>	"invalidrequestresponse",
				"cntrdescr"	=>	"Invalid Request/Response",
				"tooltip"	=>	"Number invalid requests/responses on this vserver",
			),
			"invalidrequestresponsedropped" => Array(
				"cntrname"	=>	"invalidrequestresponsedropped",
				"cntrdescr"	=>	"Invalid Request/Response Dropped",
				"tooltip"	=>	"Number invalid requests/responses dropped on this vserver",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Vserver Summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("primaryipaddress","primaryport","type","state",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"VServer Stats:",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("tothits","totalrequests","totalresponses","totalrequestbytes","totalresponsebytes","totalpktsrecvd","totalpktssent","invalidrequestresponse","invalidrequestresponsedropped",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Vserver(s) Summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("primaryipaddress","primaryport","type","state","requestsrate",),
			),
		),
),


"statcsvserver" => Array(
	"cmddispname"		=>	"statcsvserver",
	"enable"            =>      "1",
	"cmdname"		=>	"statcsvserver",
	"license"		=>	Array("NSKF1_CONTENTSWITCH_BIT_POS","NSKF1_CONTENTSWITCH_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"establishedconn" => Array(
				"cntrname"	=>	"establishedconn",
				"cntrdescr"	=>	"Current Client Est connections",
				"tooltip"	=>	"Number of client connections in ESTABLISHED state.",
			),
			"primaryipaddress" => Array(
				"cntrname"	=>	"primaryipaddress",
				"cntrdescr"	=>	"IP address",
				"tooltip"	=>	"The IP address on which the service is running.",
				"type"		=>	"NS_VALIPSTR",
			),
			"primaryport" => Array(
				"cntrname"	=>	"primaryport",
				"cntrdescr"	=>	"Port",
				"tooltip"	=>	"The port on which the service is running.",
				"type"		=>	"NS_VALPORT",
			),
			"type" => Array(
				"cntrname"	=>	"type",
				"cntrdescr"	=>	"Vserver protocol",
				"tooltip"	=>	"Protocol associated with the vserver",
				"type"		=>	"NS_VALENUM",
			),
			"state" => Array(
				"cntrname"	=>	"state",
				"cntrdescr"	=>	"State",
				"tooltip"	=>	"Current state of the server. Possible values are UP, DOWN, UNKNOWN, OFS(Out of Service), TROFS(Transition Out of Service), TROFS_DOWN(Down When going Out of Service)",
				"type"		=>	"NS_VALENUM",
			),
			"tothits" => Array(
				"cntrname"	=>	"tothits",
				"cntrdescr"	=>	"Vserver hits",
				"tooltip"	=>	"Total vserver hits",
			),
			"hitsrate" => array (
				"cntrname"	=>	"hitsrate",
				"cntrdescr"	=>	"",
			),
			"totalrequests" => Array(
				"cntrname"	=>	"totalrequests",
				"cntrdescr"	=>	"Requests",
				"tooltip"	=>	"Total number of requests received on this service or virtual server. (This applies to HTTP/SSL services and servers.)",
			),
			"requestsrate" => array (
				"cntrname"	=>	"requestsrate",
				"cntrdescr"	=>	"",
			),
			"totalresponses" => Array(
				"cntrname"	=>	"totalresponses",
				"cntrdescr"	=>	"Responses",
				"tooltip"	=>	"Number of responses received on this service or virtual server. (This applies to HTTP/SSL services and servers.)",
			),
			"responsesrate" => array (
				"cntrname"	=>	"responsesrate",
				"cntrdescr"	=>	"",
			),
			"totalrequestbytes" => Array(
				"cntrname"	=>	"totalrequestbytes",
				"cntrdescr"	=>	"Request bytes",
				"tooltip"	=>	"Total number of request bytes received on this service or virtual server.",
			),
			"requestbytesrate" => array (
				"cntrname"	=>	"requestbytesrate",
				"cntrdescr"	=>	"",
			),
			"totalresponsebytes" => Array(
				"cntrname"	=>	"totalresponsebytes",
				"cntrdescr"	=>	"Response bytes",
				"tooltip"	=>	"Number of response bytes received by this service or virtual server.",
			),
			"responsebytesrate" => array (
				"cntrname"	=>	"responsebytesrate",
				"cntrdescr"	=>	"",
			),
			"totalpktsrecvd" => Array(
				"cntrname"	=>	"totalpktsrecvd",
				"cntrdescr"	=>	"Total Packets rcvd",
				"tooltip"	=>	"Total number of packets received by this service or virtual server.",
			),
			"pktsrecvdrate" => array (
				"cntrname"	=>	"pktsrecvdrate",
				"cntrdescr"	=>	"",
			),
			"totalpktssent" => Array(
				"cntrname"	=>	"totalpktssent",
				"cntrdescr"	=>	"Total Packets sent",
				"tooltip"	=>	"Total number of packets sent.",
			),
			"pktssentrate" => array (
				"cntrname"	=>	"pktssentrate",
				"cntrdescr"	=>	"",
			),
			"curclntconnections" => Array(
				"cntrname"	=>	"curclntconnections",
				"cntrdescr"	=>	"Current client connections",
				"tooltip"	=>	"Number of current client connections.",
			),
			"cursrvrconnections" => Array(
				"cntrname"	=>	"cursrvrconnections",
				"cntrdescr"	=>	"Current server connections",
				"tooltip"	=>	"Number of current connections to the actual servers behind the virtual server.",
			),
			"sothreshold" => Array(
				"cntrname"	=>	"sothreshold",
				"cntrdescr"	=>	"Spill Over Threshold",
				"tooltip"	=>	"Spill Over Threshold set on the VServer.",
			),
			"totspillovers" => Array(
				"cntrname"	=>	"totspillovers",
				"cntrdescr"	=>	"Spill Over Hits",
				"tooltip"	=>	"Number of times vserver experienced spill over.",
			),
			"labelledconn" => Array(
				"cntrname"	=>	"labelledconn",
				"cntrdescr"	=>	"Labeled Connection",
				"tooltip"	=>	"Number of Labeled connection on this vserver",
			),
			"pushlabel" => Array(
				"cntrname"	=>	"pushlabel",
				"cntrdescr"	=>	"Push Labeled Connection",
				"tooltip"	=>	"Number of labels for this push vserver.",
			),
			"deferredreq" => Array(
				"cntrname"	=>	"deferredreq",
				"cntrdescr"	=>	"Deferred Request",
				"tooltip"	=>	"Number of deferred request on this vserver",
			),
			"deferredreqrate" => array (
				"cntrname"	=>	"deferredreqrate",
				"cntrdescr"	=>	"",
			),
			"invalidrequestresponse" => Array(
				"cntrname"	=>	"invalidrequestresponse",
				"cntrdescr"	=>	"Invalid Request/Response",
				"tooltip"	=>	"Number invalid requests/responses on this vserver",
			),
			"invalidrequestresponsedropped" => Array(
				"cntrname"	=>	"invalidrequestresponsedropped",
				"cntrdescr"	=>	"Invalid Request/Response Dropped",
				"tooltip"	=>	"Number invalid requests/responses dropped on this vserver",
			),
			"totvserverdownbackuphits" => Array(
				"cntrname"	=>	"totvserverdownbackuphits",
				"cntrdescr"	=>	"Vserver Down Backup Hits",
				"tooltip"	=>	"Number of times traffic was diverted to backup vserver since primary vserver was DOWN.",
			),
			"curmptcpsessions" => Array(
				"cntrname"	=>	"curmptcpsessions",
				"cntrdescr"	=>	"Current Multipath TCP sessions",
				"tooltip"	=>	"Current Multipath TCP sessions",
			),
			"cursubflowconn" => Array(
				"cntrname"	=>	"cursubflowconn",
				"cntrdescr"	=>	"Current Multipath TCP subflows",
				"tooltip"	=>	"Current Multipath TCP subflows",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Vserver Summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("primaryipaddress","primaryport","type","state",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"VServer Stats:",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("tothits","totalrequests","totalresponses","totalrequestbytes","totalresponsebytes","totalpktsrecvd","totalpktssent","curclntconnections","establishedconn","cursrvrconnections","sothreshold","totspillovers","labelledconn","pushlabel","deferredreq","invalidrequestresponse","invalidrequestresponsedropped","totvserverdownbackuphits","curmptcpsessions","cursubflowconn",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Vserver(s) Summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("primaryipaddress","primaryport","type","state","requestsrate",),
			),
		),
),


"statdns" => Array(
	"cmddispname"		=>	"statdns",
	"cmdname"		=>	"statdns",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"dnstotqueries" => Array(
				"cntrname"	=>	"dnstotqueries",
				"cntrdescr"	=>	"Dns queries",
				"tooltip"	=>	"Total number of DNS queries received.",
			),
			"dnsqueriesrate" => array (
				"cntrname"	=>	"dnsqueriesrate",
				"cntrdescr"	=>	"",
			),
			"dnstotmultiquery" => Array(
				"cntrname"	=>	"dnstotmultiquery",
				"cntrdescr"	=>	"Multi queries",
				"tooltip"	=>	"Total number of Multi Query request received.",
			),
			"dnstotanswers" => Array(
				"cntrname"	=>	"dnstotanswers",
				"cntrdescr"	=>	"Dns responses",
				"tooltip"	=>	"Total number of DNS responses received.",
			),
			"dnsanswersrate" => array (
				"cntrname"	=>	"dnsanswersrate",
				"cntrdescr"	=>	"",
			),
			"dnstotserverresponse" => Array(
				"cntrname"	=>	"dnstotserverresponse",
				"cntrdescr"	=>	"Server responses",
				"tooltip"	=>	"Total number of Server responses received.",
			),
			"dnsserverresponserate" => array (
				"cntrname"	=>	"dnsserverresponserate",
				"cntrdescr"	=>	"",
			),
			"dnstotrecupdate" => Array(
				"cntrname"	=>	"dnstotrecupdate",
				"cntrdescr"	=>	"Total Record updates",
				"tooltip"	=>	"Total number of record updates.",
			),
			"dnstotauthans" => Array(
				"cntrname"	=>	"dnstotauthans",
				"cntrdescr"	=>	"Auth answers",
				"tooltip"	=>	"Number of queries which were authoritatively answered.",
			),
			"dnstotserverquery" => Array(
				"cntrname"	=>	"dnstotserverquery",
				"cntrdescr"	=>	"Server queries",
				"tooltip"	=>	"Total number of Server queries sent.",
			),
			"dnsserverqueryrate" => array (
				"cntrname"	=>	"dnsserverqueryrate",
				"cntrdescr"	=>	"",
			),
			"dnstotcacheflush" => Array(
				"cntrname"	=>	"dnstotcacheflush",
				"cntrdescr"	=>	"Cache flush called",
				"tooltip"	=>	"Total number of times cache was flushed.",
			),
			"dnstotcacheentriesflush" => Array(
				"cntrname"	=>	"dnstotcacheentriesflush",
				"cntrdescr"	=>	"Cache entries flushed",
				"tooltip"	=>	"Total number of cache entries flushed.",
			),
			"dnscurnoauthentries" => Array(
				"cntrname"	=>	"dnscurnoauthentries",
				"cntrdescr"	=>	"Non-authoritative entries",
				"tooltip"	=>	"Total number of non-authoritative entries.",
			),
			"dnscurauthentries" => Array(
				"cntrname"	=>	"dnscurauthentries",
				"cntrdescr"	=>	"Authoritative entries",
				"tooltip"	=>	"Total number of authoritative entries.",
			),
			"dnstotauthnonames" => Array(
				"cntrname"	=>	"dnstotauthnonames",
				"cntrdescr"	=>	"Nonexistent domain",
				"tooltip"	=>	"Number of queries for which no record was found.",
			),
			"dnstotunsupportedresponseclass" => Array(
				"cntrname"	=>	"dnstotunsupportedresponseclass",
				"cntrdescr"	=>	"Response class unsupported",
				"tooltip"	=>	"Total number of responses for which response types were unsupported.",
			),
			"dnstotinvalidqueryformat" => Array(
				"cntrname"	=>	"dnstotinvalidqueryformat",
				"cntrdescr"	=>	"Invalid query format",
				"tooltip"	=>	"Total number of queries whose format was invalid.",
			),
			"dnstotstrayanswer" => Array(
				"cntrname"	=>	"dnstotstrayanswer",
				"cntrdescr"	=>	"Stray answers",
				"tooltip"	=>	"Total number of stray answers.",
			),
			"dnstotresponsebadlen" => Array(
				"cntrname"	=>	"dnstotresponsebadlen",
				"cntrdescr"	=>	"Incorrect RD length",
				"tooltip"	=>	"Number of DNS responses received with invalid resoure data length.",
			),
			"dnstotreqrefusals" => Array(
				"cntrname"	=>	"dnstotreqrefusals",
				"cntrdescr"	=>	"Requests refused",
				"tooltip"	=>	"Number of DNS requests refused.",
			),
			"dnserrnullattack" => Array(
				"cntrname"	=>	"dnserrnullattack",
				"cntrdescr"	=>	"NULL Attack",
				"tooltip"	=>	"Total number of queries received where all the counts are 0.",
			),
			"dnstotunsupportedresponsetype" => Array(
				"cntrname"	=>	"dnstotunsupportedresponsetype",
				"cntrdescr"	=>	"Response type unsupported",
				"tooltip"	=>	"Total number of responses for which response type requested was unsupported.",
			),
			"dnstotunsupportedqueryclass" => Array(
				"cntrname"	=>	"dnstotunsupportedqueryclass",
				"cntrdescr"	=>	"Query class unsupported",
				"tooltip"	=>	"Total number of queries for which query class was unsupported.",
			),
			"dnstotnonauthnodatas" => Array(
				"cntrname"	=>	"dnstotnonauthnodatas",
				"cntrdescr"	=>	"Invalid response format",
				"tooltip"	=>	"Total number of responses for which there was a format error.",
			),
			"dnstotnodataresps" => Array(
				"cntrname"	=>	"dnstotnodataresps",
				"cntrdescr"	=>	"No answer responses",
				"tooltip"	=>	"Number of DNS responses received without answer.",
			),
			"dnstotmultiquerydisableerror" => Array(
				"cntrname"	=>	"dnstotmultiquerydisableerror",
				"cntrdescr"	=>	"Multi queries disabled",
				"tooltip"	=>	"Total number of times a multi query was disabled and received a multi query.",
			),
			"dnstotothererrors" => Array(
				"cntrname"	=>	"dnstotothererrors",
				"cntrdescr"	=>	"Other errors",
				"tooltip"	=>	"Total number of other errors.",
			),
			"dns64totqueries" => Array(
				"cntrname"	=>	"dns64totqueries",
				"cntrdescr"	=>	"DNS64 queries",
				"tooltip"	=>	"Total number of DNS64 queries recieved.",
			),
			"dns64queriesrate" => array (
				"cntrname"	=>	"dns64queriesrate",
				"cntrdescr"	=>	"",
			),
			"dns64totanswers" => Array(
				"cntrname"	=>	"dns64totanswers",
				"cntrdescr"	=>	"DNS64 answers",
				"tooltip"	=>	"Total number of DNS64 answers served.",
			),
			"dns64answersrate" => array (
				"cntrname"	=>	"dns64answersrate",
				"cntrdescr"	=>	"",
			),
			"dns64totrwanswers" => Array(
				"cntrname"	=>	"dns64totrwanswers",
				"cntrdescr"	=>	"DNS64 rewrite answers",
				"tooltip"	=>	"Total number of DNS64 answers served after rewriting the response.",
			),
			"dns64rwanswersrate" => array (
				"cntrname"	=>	"dns64rwanswersrate",
				"cntrdescr"	=>	"",
			),
			"dns64totresponses" => Array(
				"cntrname"	=>	"dns64totresponses",
				"cntrdescr"	=>	"DNS64 responses",
				"tooltip"	=>	"Total number of responses recieved from backend in DNS64 context.",
			),
			"dns64responsesrate" => array (
				"cntrname"	=>	"dns64responsesrate",
				"cntrdescr"	=>	"",
			),
			"dns64totgslbqueries" => Array(
				"cntrname"	=>	"dns64totgslbqueries",
				"cntrdescr"	=>	"DNS64 GSLB Queries",
				"tooltip"	=>	"Total number of DNS64 queries for GSLB domain",
			),
			"dns64gslbqueriesrate" => array (
				"cntrname"	=>	"dns64gslbqueriesrate",
				"cntrdescr"	=>	"",
			),
			"dns64totgslbanswers" => Array(
				"cntrname"	=>	"dns64totgslbanswers",
				"cntrdescr"	=>	"DNS64 GSLB Answers",
				"tooltip"	=>	"Total number of DNS64 queries served.",
			),
			"dns64gslbanswersrate" => array (
				"cntrname"	=>	"dns64gslbanswersrate",
				"cntrdescr"	=>	"",
			),
			"dns64tottcanswers" => Array(
				"cntrname"	=>	"dns64tottcanswers",
				"cntrdescr"	=>	"DNS64 Total truncated answers",
				"tooltip"	=>	"Total number of Answers served with TC bit set in DNS64 context.",
			),
			"dns64tcanswersrate" => array (
				"cntrname"	=>	"dns64tcanswersrate",
				"cntrdescr"	=>	"",
			),
			"dns64totsvraqueries" => Array(
				"cntrname"	=>	"dns64totsvraqueries",
				"cntrdescr"	=>	"DNS64 Total A queries to server",
				"tooltip"	=>	"Total number of Queries sent by DNS64 module to backend.",
			),
			"dns64svraqueriesrate" => array (
				"cntrname"	=>	"dns64svraqueriesrate",
				"cntrdescr"	=>	"",
			),
			"dns64totaaaabypass" => Array(
				"cntrname"	=>	"dns64totaaaabypass",
				"cntrdescr"	=>	"DNS64 Total times AAAA query bypassed",
				"tooltip"	=>	"Total number of times AAAA query has been bypassed in DNS64 trnsaction.",
			),
			"dns64aaaabypassrate" => array (
				"cntrname"	=>	"dns64aaaabypassrate",
				"cntrdescr"	=>	"",
			),
			"dns64tottcpqueries" => Array(
				"cntrname"	=>	"dns64tottcpqueries",
				"cntrdescr"	=>	"DNS64 Total TCP  queries",
				"tooltip"	=>	"Total number of dns64 queries over TCP",
			),
			"dns64tcpqueriesrate" => array (
				"cntrname"	=>	"dns64tcpqueriesrate",
				"cntrdescr"	=>	"",
			),
			"dns64activepolicies" => Array(
				"cntrname"	=>	"dns64activepolicies",
				"cntrdescr"	=>	"DNS64 Total Active policies",
				"tooltip"	=>	"Total number of active dns64 policies",
			),
			"dns64totnodataresp" => Array(
				"cntrname"	=>	"dns64totnodataresp",
				"cntrdescr"	=>	"DNS64 Total NODATA Responses",
				"tooltip"	=>	"Total number of responses recieved from backend with ancount 0",
			),
			"dns64nodataresprate" => array (
				"cntrname"	=>	"dns64nodataresprate",
				"cntrdescr"	=>	"",
			),
			"dnstotnsrecqueries" => Array(
				"cntrname"	=>	"dnstotnsrecqueries",
				"cntrdescr"	=>	"NS queries",
				"tooltip"	=>	"Total number of NS queries received.",
			),
			"dnsnsrecqueriesrate" => array (
				"cntrname"	=>	"dnsnsrecqueriesrate",
				"cntrdescr"	=>	"",
			),
			"dnstotsoarecqueries" => Array(
				"cntrname"	=>	"dnstotsoarecqueries",
				"cntrdescr"	=>	"SOA queries",
				"tooltip"	=>	"Total number of SOA queries received.",
			),
			"dnssoarecqueriesrate" => array (
				"cntrname"	=>	"dnssoarecqueriesrate",
				"cntrdescr"	=>	"",
			),
			"dnstotptrrecqueries" => Array(
				"cntrname"	=>	"dnstotptrrecqueries",
				"cntrdescr"	=>	"PTR queries",
				"tooltip"	=>	"Total number of PTR queries received.",
			),
			"dnsptrrecqueriesrate" => array (
				"cntrname"	=>	"dnsptrrecqueriesrate",
				"cntrdescr"	=>	"",
			),
			"dnstotsrvrecqueries" => Array(
				"cntrname"	=>	"dnstotsrvrecqueries",
				"cntrdescr"	=>	"SRV queries",
				"tooltip"	=>	"Total number of SRV queries received.",
			),
			"dnssrvrecqueriesrate" => array (
				"cntrname"	=>	"dnssrvrecqueriesrate",
				"cntrdescr"	=>	"",
			),
			"dnstotaresponse" => Array(
				"cntrname"	=>	"dnstotaresponse",
				"cntrdescr"	=>	"A responses",
				"tooltip"	=>	"Total number of A responses received.",
			),
			"dnsaresponserate" => array (
				"cntrname"	=>	"dnsaresponserate",
				"cntrdescr"	=>	"",
			),
			"dnstotcnameresponse" => Array(
				"cntrname"	=>	"dnstotcnameresponse",
				"cntrdescr"	=>	"CNAME responses",
				"tooltip"	=>	"Total number of CNAME responses received.",
			),
			"dnscnameresponserate" => array (
				"cntrname"	=>	"dnscnameresponserate",
				"cntrdescr"	=>	"",
			),
			"dnstotmxresponse" => Array(
				"cntrname"	=>	"dnstotmxresponse",
				"cntrdescr"	=>	"MX responses",
				"tooltip"	=>	"Total number of MX responses received.",
			),
			"dnsmxresponserate" => array (
				"cntrname"	=>	"dnsmxresponserate",
				"cntrdescr"	=>	"",
			),
			"dnstotanyresponse" => Array(
				"cntrname"	=>	"dnstotanyresponse",
				"cntrdescr"	=>	"ANY responses",
				"tooltip"	=>	"Total number of ANY responses received.",
			),
			"dnsanyresponserate" => array (
				"cntrname"	=>	"dnsanyresponserate",
				"cntrdescr"	=>	"",
			),
			"dnstotnsrecupdate" => Array(
				"cntrname"	=>	"dnstotnsrecupdate",
				"cntrdescr"	=>	"NS updates",
				"tooltip"	=>	"Total number of NS record updates.",
			),
			"dnstotsoarecupdate" => Array(
				"cntrname"	=>	"dnstotsoarecupdate",
				"cntrdescr"	=>	"SOA updates",
				"tooltip"	=>	"Total number of SOA record updates.",
			),
			"dnstotptrrecupdate" => Array(
				"cntrname"	=>	"dnstotptrrecupdate",
				"cntrdescr"	=>	"PTR updates",
				"tooltip"	=>	"Total number of PTR record updates.",
			),
			"dnstotsrvrecupdate" => Array(
				"cntrname"	=>	"dnstotsrvrecupdate",
				"cntrdescr"	=>	"SRV updates",
				"tooltip"	=>	"Total number of SRV record updates.",
			),
			"dnstotaaaarecqueries" => Array(
				"cntrname"	=>	"dnstotaaaarecqueries",
				"cntrdescr"	=>	"AAAA queries",
				"tooltip"	=>	"Total number of AAAA queries received.",
			),
			"dnsaaaarecqueriesrate" => array (
				"cntrname"	=>	"dnsaaaarecqueriesrate",
				"cntrdescr"	=>	"",
			),
			"dnstotarecqueries" => Array(
				"cntrname"	=>	"dnstotarecqueries",
				"cntrdescr"	=>	"A queries",
				"tooltip"	=>	"Total number of A queries received.",
			),
			"dnsarecqueriesrate" => array (
				"cntrname"	=>	"dnsarecqueriesrate",
				"cntrdescr"	=>	"",
			),
			"dnstotcnamerecqueries" => Array(
				"cntrname"	=>	"dnstotcnamerecqueries",
				"cntrdescr"	=>	"CNAME queries",
				"tooltip"	=>	"Total number of CNAME queries received.",
			),
			"dnscnamerecqueriesrate" => array (
				"cntrname"	=>	"dnscnamerecqueriesrate",
				"cntrdescr"	=>	"",
			),
			"dnstotmxrecqueries" => Array(
				"cntrname"	=>	"dnstotmxrecqueries",
				"cntrdescr"	=>	"MX queries",
				"tooltip"	=>	"Total number of MX queries received.",
			),
			"dnsmxrecqueriesrate" => array (
				"cntrname"	=>	"dnsmxrecqueriesrate",
				"cntrdescr"	=>	"",
			),
			"dnstotanyqueries" => Array(
				"cntrname"	=>	"dnstotanyqueries",
				"cntrdescr"	=>	"ANY queries",
				"tooltip"	=>	"Total number of ANY queries received.",
			),
			"dnsanyqueriesrate" => array (
				"cntrname"	=>	"dnsanyqueriesrate",
				"cntrdescr"	=>	"",
			),
			"dnstotaaaaresponse" => Array(
				"cntrname"	=>	"dnstotaaaaresponse",
				"cntrdescr"	=>	"AAAA responses",
				"tooltip"	=>	"Total number of AAAA responses received.",
			),
			"dnsaaaaresponserate" => array (
				"cntrname"	=>	"dnsaaaaresponserate",
				"cntrdescr"	=>	"",
			),
			"dnstotnsresponse" => Array(
				"cntrname"	=>	"dnstotnsresponse",
				"cntrdescr"	=>	"NS responses",
				"tooltip"	=>	"Total number of NS responses received.",
			),
			"dnsnsresponserate" => array (
				"cntrname"	=>	"dnsnsresponserate",
				"cntrdescr"	=>	"",
			),
			"dnstotsoaresponse" => Array(
				"cntrname"	=>	"dnstotsoaresponse",
				"cntrdescr"	=>	"SOA responses",
				"tooltip"	=>	"Total number of SOA responses received.",
			),
			"dnssoaresponserate" => array (
				"cntrname"	=>	"dnssoaresponserate",
				"cntrdescr"	=>	"",
			),
			"dnstotptrresponse" => Array(
				"cntrname"	=>	"dnstotptrresponse",
				"cntrdescr"	=>	"PTR responses",
				"tooltip"	=>	"Total number of PTR responses received.",
			),
			"dnsptrresponserate" => array (
				"cntrname"	=>	"dnsptrresponserate",
				"cntrdescr"	=>	"",
			),
			"dnstotsrvresponse" => Array(
				"cntrname"	=>	"dnstotsrvresponse",
				"cntrdescr"	=>	"SRV responses",
				"tooltip"	=>	"Total number of SRV responses received.",
			),
			"dnssrvresponserate" => array (
				"cntrname"	=>	"dnssrvresponserate",
				"cntrdescr"	=>	"",
			),
			"dnstotaaaarecupdate" => Array(
				"cntrname"	=>	"dnstotaaaarecupdate",
				"cntrdescr"	=>	"AAAA updates",
				"tooltip"	=>	"Total number of AAAA record updates.",
			),
			"dnstotarecupdate" => Array(
				"cntrname"	=>	"dnstotarecupdate",
				"cntrdescr"	=>	"A updates",
				"tooltip"	=>	"Total number of A record updates.",
			),
			"dnstotmxrecupdate" => Array(
				"cntrname"	=>	"dnstotmxrecupdate",
				"cntrdescr"	=>	"MX updates",
				"tooltip"	=>	"Total number of MX record updates.",
			),
			"dnstotcnamerecupdate" => Array(
				"cntrname"	=>	"dnstotcnamerecupdate",
				"cntrdescr"	=>	"CNAME updates",
				"tooltip"	=>	"Total number of CNAME record updates.",
			),
			"dnscuraaaarecord" => Array(
				"cntrname"	=>	"dnscuraaaarecord",
				"cntrdescr"	=>	"AAAA records",
				"tooltip"	=>	"Total number of AAAA records.",
			),
			"dnscurarecord" => Array(
				"cntrname"	=>	"dnscurarecord",
				"cntrdescr"	=>	"A records",
				"tooltip"	=>	"Total number of A records.",
			),
			"dnscurmxrecord" => Array(
				"cntrname"	=>	"dnscurmxrecord",
				"cntrdescr"	=>	"MX records",
				"tooltip"	=>	"Total number of MX records.",
			),
			"dnscurcnamerecord" => Array(
				"cntrname"	=>	"dnscurcnamerecord",
				"cntrdescr"	=>	"CNAME records",
				"tooltip"	=>	"Total number of CNAME records.",
			),
			"dnscurnsrecord" => Array(
				"cntrname"	=>	"dnscurnsrecord",
				"cntrdescr"	=>	"NS records",
				"tooltip"	=>	"Total number of NS records.",
			),
			"dnscursoarecord" => Array(
				"cntrname"	=>	"dnscursoarecord",
				"cntrdescr"	=>	"SOA records",
				"tooltip"	=>	"Total number of SOA records.",
			),
			"dnscurptrrecord" => Array(
				"cntrname"	=>	"dnscurptrrecord",
				"cntrdescr"	=>	"PTR records",
				"tooltip"	=>	"Total number of PTR records.",
			),
			"dnscursrvrecord" => Array(
				"cntrname"	=>	"dnscursrvrecord",
				"cntrdescr"	=>	"SRV records",
				"tooltip"	=>	"Total number of SRV records.",
			),
			"dnstotaaaarecfailed" => Array(
				"cntrname"	=>	"dnstotaaaarecfailed",
				"cntrdescr"	=>	"No AAAA records",
				"tooltip"	=>	"Total number of times AAAA record lookup failed.",
			),
			"dnstotarecfailed" => Array(
				"cntrname"	=>	"dnstotarecfailed",
				"cntrdescr"	=>	"No A records",
				"tooltip"	=>	"Total number of times A record lookup failed.",
			),
			"dnstotmxrecfailed" => Array(
				"cntrname"	=>	"dnstotmxrecfailed",
				"cntrdescr"	=>	"No MX records",
				"tooltip"	=>	"Total number of times MX record lookup failed.",
			),
			"dnstotptrrecfailed" => Array(
				"cntrname"	=>	"dnstotptrrecfailed",
				"cntrdescr"	=>	"No PTR records",
				"tooltip"	=>	"Total number of times PTR record lookup failed.",
			),
			"dnstotnsrecfailed" => Array(
				"cntrname"	=>	"dnstotnsrecfailed",
				"cntrdescr"	=>	"No NS records",
				"tooltip"	=>	"Total number of times NS record lookup failed.",
			),
			"dnstotcnamerecfailed" => Array(
				"cntrname"	=>	"dnstotcnamerecfailed",
				"cntrdescr"	=>	"No CNAME records",
				"tooltip"	=>	"Total number of times CNAME record lookup failed.",
			),
			"dnstotsoarecfailed" => Array(
				"cntrname"	=>	"dnstotsoarecfailed",
				"cntrdescr"	=>	"No SOA records",
				"tooltip"	=>	"Total number of times SOA record lookup failed.",
			),
			"dnstotsrvrecfailed" => Array(
				"cntrname"	=>	"dnstotsrvrecfailed",
				"cntrdescr"	=>	"No SRV records",
				"tooltip"	=>	"Total number of times SRV record lookup failed.",
			),
			"dnstotanyrecfailed" => Array(
				"cntrname"	=>	"dnstotanyrecfailed",
				"cntrdescr"	=>	"No ANY records",
				"tooltip"	=>	"Total number of times ANY query lookup failed.",
			),
			"dnstotunsupportedqueries" => Array(
				"cntrname"	=>	"dnstotunsupportedqueries",
				"cntrdescr"	=>	"Unsupported queries",
				"tooltip"	=>	"Total number of requests for which query type requested was unsupported.",
			),
		),
	"displayhandler"	=>
		Array(
			array (
			"format"	=>	"2C",
				"title"	=>	"Runtime Statistics",
			"column1" => 			Array(
				"dhID"	=>	1,
				"title"	=>	"",
				"header"	=>	array(),
				"dispflag"	=>	"Summary",
				"counters"	=>	array("dnstotqueries","dnstotmultiquery","dnstotanswers","dnstotserverresponse","dnstotrecupdate",),
			),
			"column2" => 			Array(
				"dhID"	=>	2,
				"title"	=>	"",
				"header"	=>	array(),
				"dispflag"	=>	"Summary",
				"counters"	=>	array("dnstotauthans","dnstotserverquery","dnstotcacheflush","dnstotcacheentriesflush",),
			),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Configuration Statistics",
				"header"	=>	array(),
				"format"	=>	"1C",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("dnscurnoauthentries","dnscurauthentries",),
			),
			array (
			"format"	=>	"2C",
				"title"	=>	"Error Statistics",
			"column1" => 			Array(
				"dhID"	=>	4,
				"title"	=>	"",
				"header"	=>	array(),
				"dispflag"	=>	"Summary",
				"counters"	=>	array("dnstotauthnonames","dnstotunsupportedresponseclass","dnstotinvalidqueryformat","dnstotstrayanswer","dnstotresponsebadlen","dnstotreqrefusals","dnserrnullattack",),
			),
			"column2" => 			Array(
				"dhID"	=>	5,
				"title"	=>	"",
				"header"	=>	array(),
				"dispflag"	=>	"Summary",
				"counters"	=>	array("dnstotunsupportedresponsetype","dnstotunsupportedqueryclass","dnstotnonauthnodatas","dnstotnodataresps","dnstotmultiquerydisableerror","dnstotothererrors",),
			),
			),
			array (
			"format"	=>	"2C",
				"title"	=>	"DNS64 Statistics",
			"column1" => 			Array(
				"dhID"	=>	6,
				"title"	=>	"",
				"header"	=>	array(),
				"dispflag"	=>	"Summary",
				"counters"	=>	array("dns64totqueries","dns64totanswers","dns64totrwanswers","dns64totresponses","dns64totgslbqueries","dns64totgslbanswers",),
			),
			"column2" => 			Array(
				"dhID"	=>	7,
				"title"	=>	"",
				"header"	=>	array(),
				"dispflag"	=>	"Summary",
				"counters"	=>	array("dns64tottcanswers","dns64totsvraqueries","dns64totaaaabypass","dns64tottcpqueries","dns64activepolicies","dns64totnodataresp",),
			),
			),
		),
),


"statdnsrecords" => Array(
	"cmddispname"		=>	"statdnsrecords",
	"cmdname"		=>	"statdnsrecords",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"dnsrecordtype" => Array(
				"argname"		=>	"dnsrecordtype",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "dnsrecordtype",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"dnstotentries" => Array(
				"cntrname"	=>	"dnstotentries",
				"cntrdescr"	=>	"Total entries",
				"tooltip"	=>	"Total number of DNS record entries",
			),
			"dnstotupdates" => Array(
				"cntrname"	=>	"dnstotupdates",
				"cntrdescr"	=>	"Total updates",
				"tooltip"	=>	"Total number of DNS proactive updates",
			),
			"dnstotresponses" => Array(
				"cntrname"	=>	"dnstotresponses",
				"cntrdescr"	=>	"Total responses",
				"tooltip"	=>	"Total number of DNS server responses",
			),
			"dnstotrequests" => Array(
				"cntrname"	=>	"dnstotrequests",
				"cntrdescr"	=>	"Total requests",
				"tooltip"	=>	"Total number of DNS queries recieved",
			),
			"dnscurentries" => Array(
				"cntrname"	=>	"dnscurentries",
				"cntrdescr"	=>	"Current entries",
				"tooltip"	=>	"Current number of DNS entries",
			),
			"dnstoterrlimits" => Array(
				"cntrname"	=>	"dnstoterrlimits",
				"cntrdescr"	=>	"Total limit errors",
				"tooltip"	=>	"Total number of times we have recieved dns record with more entries than we support",
			),
			"dnstoterrrespform" => Array(
				"cntrname"	=>	"dnstoterrrespform",
				"cntrdescr"	=>	"Total response format errors",
				"tooltip"	=>	"Total number of times we have recieved malformed responses from the backend",
			),
			"dnstoterraliasex" => Array(
				"cntrname"	=>	"dnstoterraliasex",
				"cntrdescr"	=>	"Total alias exist errors",
				"tooltip"	=>	"Total number of times we have recieved non-cname record for a domain for which an alias exists",
			),
			"dnstoterrnodomains" => Array(
				"cntrname"	=>	"dnstoterrnodomains",
				"cntrdescr"	=>	"Total cache misses",
				"tooltip"	=>	"Total number of cache misses",
			),
			"dnscurrecords" => Array(
				"cntrname"	=>	"dnscurrecords",
				"cntrdescr"	=>	"Current records",
				"tooltip"	=>	"Current number of DNS Records",
			),
		),
	"displayhandler"	=>
		Array(
			array (
			"format"	=>	"2C",
				"title"	=>	"DNS statistics",
			"column1" => 			Array(
				"dhID"	=>	1,
				"title"	=>	"",
				"header"	=>	array(),
				"dispflag"	=>	"Byname",
				"counters"	=>	array("dnstotentries","dnstotupdates","dnstotresponses","dnstotrequests","dnscurentries",),
			),
			"column2" => 			Array(
				"dhID"	=>	2,
				"title"	=>	"",
				"header"	=>	array(),
				"dispflag"	=>	"Byname",
				"counters"	=>	array("dnstoterrlimits","dnstoterrrespform","dnstoterraliasex","dnstoterrnodomains","dnscurrecords",),
			),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Runtime Statistics summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("dnstotresponses","dnstotrequests","dnscurentries","dnscurrecords",),
			),
		),
),


"statdnspolicylabel" => Array(
	"cmddispname"		=>	"statdnspolicylabel",
	"cmdname"		=>	"statdnspolicylabel",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"labelname" => Array(
				"argname"		=>	"labelname",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "labelname",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"pipolicylabelhits" => Array(
				"cntrname"	=>	"pipolicylabelhits",
				"cntrdescr"	=>	"Policy Label Hits",
				"tooltip"	=>	"Number of times policy label was invoked.",
			),
			"pipolicylabelhitsrate" => array (
				"cntrname"	=>	"pipolicylabelhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"DNS Policylabel Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pipolicylabelhits",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"DNS Policylabel(s) Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pipolicylabelhits",),
			),
		),
),


"statdnspolicybinding" => Array(
	"cmddispname"		=>	"statdnspolicybinding",
	"cmdname"		=>	"statdnspolicybinding",
	"cmddescr"	=>	"     ",
	"hidden"	=>	1,
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"pipolicybindinghits" => Array(
				"cntrname"	=>	"pipolicybindinghits",
				"cntrdescr"	=>	"Policy binding hits",
				"tooltip"	=>	"Number of hits on the policy on this binding",
			),
			"pipolicybindinghitsrate" => array (
				"cntrname"	=>	"pipolicybindinghitsrate",
				"cntrdescr"	=>	"",
			),
			"pipolicybindingundefhits" => Array(
				"cntrname"	=>	"pipolicybindingundefhits",
				"cntrdescr"	=>	"Policy binding undef hits",
				"tooltip"	=>	"Number of undef hits on the policy on this binding",
			),
			"pipolicybindingundefhitsrate" => array (
				"cntrname"	=>	"pipolicybindingundefhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"DNS Binding Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pipolicybindinghits","pipolicybindingundefhits",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"DNS Binding(s) Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pipolicybindinghits","pipolicybindingundefhits",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"DNS Binding(s) Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"subent",
				"counters"	=>	array("pipolicybindinghits","pipolicybindingundefhits",),
			),
		),
),


"statdos" => Array(
	"cmddispname"		=>	"statdos",
	"cmdname"		=>	"statdos",
	"license"		=>	Array("NSKF1_DOS_BIT_POS","NSKF1_DOS_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"dostotconditiontriggered" => Array(
				"cntrname"	=>	"dostotconditiontriggered",
				"cntrdescr"	=>	"DOS condition triggered",
				"tooltip"	=>	"Number of times the NetScaler appliance triggered the DOS JavaScript due to a condition match.",
			),
			"dosconditiontriggeredrate" => array (
				"cntrname"	=>	"dosconditiontriggeredrate",
				"cntrdescr"	=>	"",
			),
			"dostotvalidcookies" => Array(
				"cntrname"	=>	"dostotvalidcookies",
				"cntrdescr"	=>	"Valid DOS clients",
				"tooltip"	=>	"Number of clients from whom the NetScaler appliance received a valid DOS cookie.",
			),
			"dosvalidcookiesrate" => array (
				"cntrname"	=>	"dosvalidcookiesrate",
				"cntrdescr"	=>	"",
			),
			"dostotdospriorityclients" => Array(
				"cntrname"	=>	"dostotdospriorityclients",
				"cntrdescr"	=>	"DOS priority clients",
				"tooltip"	=>	"Number of valid clients that were given DOS priority.",
			),
			"dosdospriorityclientsrate" => array (
				"cntrname"	=>	"dosdospriorityclientsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"text" => "NONE",
				"flag" => "Summary",
				"key" => "default",
				"relatedlink_align"	=> "right",
				"relatedlink"	=>
					Array(
						array(
							 "text" => "Per Policy statistics", 
							 "cmd" => "statdospolicy", 
							 "type" => "summary", 
						),
					),
			),
			Array(
				"dhID"	=>	1,
				"title"	=>	"",
				"header"	=>	array("Name","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("dostotconditiontriggered","dostotvalidcookies","dostotdospriorityclients",),
			),
		),
),


"statdospolicy" => Array(
	"cmddispname"		=>	"statdospolicy",
	"cmdname"		=>	"statdospolicy",
	"license"		=>	Array("NSKF1_DOS_BIT_POS","NSKF1_DOS_BIT_POS"),
	"cmddescr"	=>	"     ",
	"bindings"	=>	1,
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"doscurrentcltdetectrate" => Array(
				"cntrname"	=>	"doscurrentcltdetectrate",
				"cntrdescr"	=>	"Client detect rate",
				"tooltip"	=>	"Current ratio of JavaScript send rate to the server response rate (Client detect rate)",
			),
			"dosphysicalserviceip" => Array(
				"cntrname"	=>	"dosphysicalserviceip",
				"cntrdescr"	=>	"Physical service IP",
				"tooltip"	=>	"IP address of the service to which this policy is bound.",
				"type"		=>	"NS_VALIPSTR",
			),
			"dosphysicalserviceport" => Array(
				"cntrname"	=>	"dosphysicalserviceport",
				"cntrdescr"	=>	"Physical service port",
				"tooltip"	=>	"Port address of the service to which this policy is bound.",
				"type"		=>	"NS_VALPORT",
			),
			"doscurrentqueuesize" => Array(
				"cntrname"	=>	"doscurrentqueuesize",
				"cntrdescr"	=>	"Current server queue size",
				"tooltip"	=>	"Current queue size of the server to which this policy is bound.",
			),
			"doscurrentqueuesizerate" => array (
				"cntrname"	=>	"doscurrentqueuesizerate",
				"cntrdescr"	=>	"",
			),
			"dostotjssent" => Array(
				"cntrname"	=>	"dostotjssent",
				"cntrdescr"	=>	"DOS transactions",
				"tooltip"	=>	"Total number of DoS JavaScript transactions performed for this policy.",
			),
			"dosjssentrate" => array (
				"cntrname"	=>	"dosjssentrate",
				"cntrdescr"	=>	"",
			),
			"dostotjsrefused" => Array(
				"cntrname"	=>	"dostotjsrefused",
				"cntrdescr"	=>	"Client detect rate mismatch",
				"tooltip"	=>	"Number of times the DoS JavaScript was not sent because the set JavaScript rate was not met for this policy.",
			),
			"dosjsrefusedrate" => array (
				"cntrname"	=>	"dosjsrefusedrate",
				"cntrdescr"	=>	"",
			),
			"dostotvalidclients" => Array(
				"cntrname"	=>	"dostotvalidclients",
				"cntrdescr"	=>	"Valid clients",
				"tooltip"	=>	"Total number of valid DoS cookies received for this policy.",
			),
			"dosvalidclientsrate" => array (
				"cntrname"	=>	"dosvalidclientsrate",
				"cntrdescr"	=>	"",
			),
			"dostotjsbytessent" => Array(
				"cntrname"	=>	"dostotjsbytessent",
				"cntrdescr"	=>	"DOS JavaScript bytes served",
				"tooltip"	=>	"Total number of DoS JavaScript bytes sent for this policy.",
			),
			"dosjsbytessentrate" => array (
				"cntrname"	=>	"dosjsbytessentrate",
				"cntrdescr"	=>	"",
			),
			"dostotnongetpostrequests" => Array(
				"cntrname"	=>	"dostotnongetpostrequests",
				"cntrdescr"	=>	"Non GET, POST requests",
				"tooltip"	=>	"Number of non-GET and non-POST requests for which DOS JavaScript was sent.",
			),
			"dosnongetpostrequestsrate" => array (
				"cntrname"	=>	"dosnongetpostrequestsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"DOS policy summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("dosphysicalserviceip","dosphysicalserviceport",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Runtime statistics",
				"header"	=>	array("","Value",),
				"format"	=>	"1C",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("doscurrentqueuesize","doscurrentcltdetectrate",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Cumulative statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("dostotjssent","dostotjsrefused","dostotvalidclients","dostotjsbytessent","dostotnongetpostrequests",),
			),
			Array(
				"text" => "NONE",
				"flag" => "Summary",
				"key" => "default",
				"relatedlink_align"	=> "right",
				"relatedlink"	=>
					Array(
						array(
							 "text" => "Global Statistics", 
							 "cmd" => "statdos", 
							 "type" => "summary", 
						),
					),
			),
			Array(
				"dhID"	=>	4,
				"title"	=>	"Dos policy summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("dosphysicalserviceip","dosphysicalserviceport","doscurrentqueuesize","dostotvalidclients",),
			),
		),
),


"statgslbsite" => Array(
	"cmddispname"		=>	"statgslbsite",
	"cmdname"		=>	"statgslbsite",
	"license"		=>	Array("NSKF1_GSLB_BIT_POS","NSKF1_LOADBALANCE_BIT_POS","NSKF1_GSLB_BIT_POS","NSKF1_LOADBALANCE_BIT_POS"),
	"cmddescr"	=>	"     ",
	"bindings"	=>	1,
	"args"	=>
		Array (
			"sitename" => Array(
				"argname"		=>	"sitename",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "sitename",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"sitepublicip" => Array(
				"cntrname"	=>	"sitepublicip",
				"cntrdescr"	=>	"Gslb Site Public IP address",
				"tooltip"	=>	"The public IP address of this GSLB site.",
				"type"		=>	"NS_VALIP",
			),
			"siteip" => Array(
				"cntrname"	=>	"siteip",
				"cntrdescr"	=>	"Gslb Site private IP address",
				"cntrdescr2"	=>	"Gslb Site private IPV4 address",
				"tooltip"	=>	"The private IP address of this GSLB site.",
				"type"		=>	"NS_VALIP",
			),
			"sitemepstatus" => Array(
				"cntrname"	=>	"sitemepstatus",
				"cntrdescr"	=>	"Metric Exchange State",
				"tooltip"	=>	"Indicates the status of the Metric Exchange Policy at this GSLB site.",
				"type"		=>	"NS_VALENUM",
			),
			"persexchange" => Array(
				"cntrname"	=>	"persexchange",
				"cntrdescr"	=>	"Persistence Exchange",
				"tooltip"	=>	"Indicates whether Persistence entries exchange is enabled or disabled at this GSLB site.",
				"type"		=>	"NS_VALENUM",
			),
			"nwmetricexchange" => Array(
				"cntrname"	=>	"nwmetricexchange",
				"cntrdescr"	=>	"Network Metric Exchange",
				"tooltip"	=>	"Indicates whether network metric exchange is enabled or disabled at this GSLB site.",
				"type"		=>	"NS_VALENUM",
			),
			"sitemetricexchange" => Array(
				"cntrname"	=>	"sitemetricexchange",
				"cntrdescr"	=>	"Metric Exchange",
				"tooltip"	=>	"Indicates whether metric exchange is enabled or disabled at this GSLB site.",
				"type"		=>	"NS_VALENUM",
			),
			"sitetype" => Array(
				"cntrname"	=>	"sitetype",
				"cntrdescr"	=>	"GSLB Site type",
				"tooltip"	=>	"Indicates whether this GSLB site is local or remote.",
				"type"		=>	"NS_VALENUM",
			),
			"siteipstr" => Array(
				"cntrname"	=>	"siteipstr",
				"cntrdescr"	=>	"Gslb Site private IP address",
				"tooltip"	=>	"The private IP address of this GSLB site.",
				"type"		=>	"NS_VALIPSTR",
			),
			"sitepublicipstr" => Array(
				"cntrname"	=>	"sitepublicipstr",
				"cntrdescr"	=>	"Gslb Site public IP address",
				"tooltip"	=>	"The public IP address of this GSLB site.",
				"type"		=>	"NS_VALIPSTR",
			),
			"sitemetricmepstatus" => Array(
				"cntrname"	=>	"sitemetricmepstatus",
				"cntrdescr"	=>	"Site Metric Metric Exchange State",
				"tooltip"	=>	"Indicates the status of the site metric Metric Exchange connection at this GSLB site.",
				"type"		=>	"NS_VALENUM",
			),
			"nwmetricmepstatus" => Array(
				"cntrname"	=>	"nwmetricmepstatus",
				"cntrdescr"	=>	"Network Metric Metric Exchange State",
				"tooltip"	=>	"Indicates the status of the network metric Metric Exchange connection at this GSLB site.",
				"type"		=>	"NS_VALENUM",
			),
			"sitetotalrequestbytes" => Array(
				"cntrname"	=>	"sitetotalrequestbytes",
				"cntrdescr"	=>	"Request bytes",
				"tooltip"	=>	"Total number of request bytes received by the virtual servers represented by all GSLB services associated with this GSLB site.",
			),
			"siterequestbytesrate" => array (
				"cntrname"	=>	"siterequestbytesrate",
				"cntrdescr"	=>	"",
			),
			"sitetotalresponsebytes" => Array(
				"cntrname"	=>	"sitetotalresponsebytes",
				"cntrdescr"	=>	"Response bytes",
				"tooltip"	=>	"Number of response bytes received by the virtual servers represented by all GSLB services associated with this GSLB site.",
			),
			"siteresponsebytesrate" => array (
				"cntrname"	=>	"siteresponsebytesrate",
				"cntrdescr"	=>	"",
			),
			"sitetotalrequests" => Array(
				"cntrname"	=>	"sitetotalrequests",
				"cntrdescr"	=>	"Requests",
				"tooltip"	=>	"Total number of requests received by the virtual servers represented by all GSLB services associated with this GSLB site.",
			),
			"siterequestsrate" => array (
				"cntrname"	=>	"siterequestsrate",
				"cntrdescr"	=>	"",
			),
			"sitetotalresponses" => Array(
				"cntrname"	=>	"sitetotalresponses",
				"cntrdescr"	=>	"Responses",
				"tooltip"	=>	"Number of responses received by the virtual servers represented by all GSLB services associated with this GSLB site.",
			),
			"siteresponsesrate" => array (
				"cntrname"	=>	"siteresponsesrate",
				"cntrdescr"	=>	"",
			),
			"sitecurclntconnections" => Array(
				"cntrname"	=>	"sitecurclntconnections",
				"cntrdescr"	=>	"Current client connections",
				"tooltip"	=>	"Number of current client connections to the virtual servers represented by all GSLB services associated with this GSLB site.",
			),
			"sitecursrvrconnections" => Array(
				"cntrname"	=>	"sitecursrvrconnections",
				"cntrdescr"	=>	"Current server connections",
				"tooltip"	=>	"Number of current connections to the real servers behind the virtual servers represented by all GSLB services associated with this GSLB site.",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"GSLBsite Summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("siteipstr","sitepublicipstr","sitetype","sitemetricmepstatus","nwmetricmepstatus","sitemetricexchange","nwmetricexchange","persexchange",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"gslbsite Stats:",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("sitetotalrequestbytes","sitetotalresponsebytes",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"GSLBsite(s) Summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("siteipstr","sitepublicipstr","sitetype",),
			),
		),
),


"statgslbservice" => Array(
	"cmddispname"		=>	"statgslbservice",
	"cmdname"		=>	"statgslbservice",
	"license"		=>	Array("NSKF1_GSLB_BIT_POS","NSKF1_LOADBALANCE_BIT_POS","NSKF1_GSLB_BIT_POS","NSKF1_LOADBALANCE_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"servicename" => Array(
				"argname"		=>	"servicename",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "servicename",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"establishedconn" => Array(
				"cntrname"	=>	"establishedconn",
				"cntrdescr"	=>	"Current Client Est connections",
				"tooltip"	=>	"Number of client connections in ESTABLISHED state.",
			),
			"primaryipaddress" => Array(
				"cntrname"	=>	"primaryipaddress",
				"cntrdescr"	=>	"IP address",
				"tooltip"	=>	"The IP address on which the service is running.",
				"type"		=>	"NS_VALIPSTR",
			),
			"primaryport" => Array(
				"cntrname"	=>	"primaryport",
				"cntrdescr"	=>	"Port",
				"tooltip"	=>	"The port on which the service is running.",
				"type"		=>	"NS_VALPORT",
			),
			"servicetype" => Array(
				"cntrname"	=>	"servicetype",
				"cntrdescr"	=>	"Service type",
				"tooltip"	=>	"The service type of this service.Possible values are ADNS, DNS, MYSQL, RTSP, SSL_DIAMETER, ADNS_TCP, DNS_TCP, NNTP, SIP_UDP, SSL_TCP, ANY, FTP, RADIUS, SNMP, TCP, DHCPRA, HTTP, RDP, SSL, TFTP, DIAMETER, MSSQL, RPCSVR, SSL_BRIDGE, UDP",
				"type"		=>	"NS_VALENUM",
			),
			"state" => Array(
				"cntrname"	=>	"state",
				"cntrdescr"	=>	"State",
				"tooltip"	=>	"Current state of the server. Possible values are UP, DOWN, UNKNOWN, OFS(Out of Service), TROFS(Transition Out of Service), TROFS_DOWN(Down When going Out of Service)",
				"type"		=>	"NS_VALENUM",
			),
			"totalrequestbytes" => Array(
				"cntrname"	=>	"totalrequestbytes",
				"cntrdescr"	=>	"Request bytes",
				"tooltip"	=>	"Total number of request bytes received on this service or virtual server.",
			),
			"requestbytesrate" => array (
				"cntrname"	=>	"requestbytesrate",
				"cntrdescr"	=>	"",
			),
			"totalresponsebytes" => Array(
				"cntrname"	=>	"totalresponsebytes",
				"cntrdescr"	=>	"Response bytes",
				"tooltip"	=>	"Number of response bytes received by this service or virtual server.",
			),
			"responsebytesrate" => array (
				"cntrname"	=>	"responsebytesrate",
				"cntrdescr"	=>	"",
			),
			"curload" => Array(
				"cntrname"	=>	"curload",
				"cntrdescr"	=>	"Current load on the service",
				"tooltip"	=>	"Load on the service that is calculated from the bound load based monitor.",
			),
			"totalrequests" => Array(
				"cntrname"	=>	"totalrequests",
				"cntrdescr"	=>	"Requests",
				"tooltip"	=>	"Total number of requests received on this service or virtual server. (This applies to HTTP/SSL services and servers.)",
			),
			"requestsrate" => array (
				"cntrname"	=>	"requestsrate",
				"cntrdescr"	=>	"",
			),
			"totalresponses" => Array(
				"cntrname"	=>	"totalresponses",
				"cntrdescr"	=>	"Responses",
				"tooltip"	=>	"Number of responses received on this service or virtual server. (This applies to HTTP/SSL services and servers.)",
			),
			"responsesrate" => array (
				"cntrname"	=>	"responsesrate",
				"cntrdescr"	=>	"",
			),
			"curclntconnections" => Array(
				"cntrname"	=>	"curclntconnections",
				"cntrdescr"	=>	"Current client connections",
				"tooltip"	=>	"Number of current client connections.",
			),
			"cursrvrconnections" => Array(
				"cntrname"	=>	"cursrvrconnections",
				"cntrdescr"	=>	"Current server connections",
				"tooltip"	=>	"Number of current connections to the actual servers behind the virtual server.",
			),
			"vsvrservicehits" => Array(
				"cntrname"	=>	"vsvrservicehits",
				"cntrdescr"	=>	"Service hits",
				"tooltip"	=>	"Number of times that the service has been provided.",
			),
			"vsvrservicehitsrate" => array (
				"cntrname"	=>	"vsvrservicehitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Service Summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("primaryipaddress","primaryport","servicetype","state",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Service Stats:",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("totalrequestbytes","totalresponsebytes","establishedconn","curload",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Service(s) Summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("primaryipaddress","primaryport","servicetype","state",),
			),
			Array(
				"dhID"	=>	4,
				"title"	=>	"Bound Service(s) Summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"subent",
				"counters"	=>	array("primaryipaddress","primaryport","servicetype","state","vsvrservicehits","totalrequestbytes","totalresponsebytes","establishedconn","curload",),
			),
		),
),


"statgslbvserver" => Array(
	"cmddispname"		=>	"statgslbvserver",
	"enable"            =>      "1",
	"cmdname"		=>	"statgslbvserver",
	"license"		=>	Array("NSKF1_GSLB_BIT_POS","NSKF1_LOADBALANCE_BIT_POS","NSKF1_GSLB_BIT_POS","NSKF1_LOADBALANCE_BIT_POS"),
	"cmddescr"	=>	"     ",
	"bindings"	=>	1,
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"establishedconn" => Array(
				"cntrname"	=>	"establishedconn",
				"cntrdescr"	=>	"Current Client Est connections",
				"tooltip"	=>	"Number of client connections in ESTABLISHED state.",
			),
			"inactsvcs" => Array(
				"cntrname"	=>	"inactsvcs",
				"cntrdescr"	=>	"total INACTIVE services",
				"tooltip"	=>	"number of INACTIVE services bound to a vserver",
			),
			"vslbhealth" => Array(
				"cntrname"	=>	"vslbhealth",
				"cntrdescr"	=>	"Vserver Health",
				"tooltip"	=>	"Health of the vserver. This gives percentage of UP services bound to this vserver.",
			),
			"type" => Array(
				"cntrname"	=>	"type",
				"cntrdescr"	=>	"Vserver protocol",
				"tooltip"	=>	"Protocol associated with the vserver",
				"type"		=>	"NS_VALENUM",
			),
			"state" => Array(
				"cntrname"	=>	"state",
				"cntrdescr"	=>	"State",
				"tooltip"	=>	"Current state of the server. Possible values are UP, DOWN, UNKNOWN, OFS(Out of Service), TROFS(Transition Out of Service), TROFS_DOWN(Down When going Out of Service)",
				"type"		=>	"NS_VALENUM",
			),
			"actsvcs" => Array(
				"cntrname"	=>	"actsvcs",
				"cntrdescr"	=>	"total ACTIVE services",
				"tooltip"	=>	"number of ACTIVE services bound to a vserver",
			),
			"tothits" => Array(
				"cntrname"	=>	"tothits",
				"cntrdescr"	=>	"Vserver hits",
				"tooltip"	=>	"Total vserver hits",
			),
			"hitsrate" => array (
				"cntrname"	=>	"hitsrate",
				"cntrdescr"	=>	"",
			),
			"totalrequestbytes" => Array(
				"cntrname"	=>	"totalrequestbytes",
				"cntrdescr"	=>	"Request bytes",
				"tooltip"	=>	"Total number of request bytes received on this service or virtual server.",
			),
			"requestbytesrate" => array (
				"cntrname"	=>	"requestbytesrate",
				"cntrdescr"	=>	"",
			),
			"totalresponsebytes" => Array(
				"cntrname"	=>	"totalresponsebytes",
				"cntrdescr"	=>	"Response bytes",
				"tooltip"	=>	"Number of response bytes received by this service or virtual server.",
			),
			"responsebytesrate" => array (
				"cntrname"	=>	"responsebytesrate",
				"cntrdescr"	=>	"",
			),
			"sothreshold" => Array(
				"cntrname"	=>	"sothreshold",
				"cntrdescr"	=>	"Spill Over Threshold",
				"tooltip"	=>	"Spill Over Threshold set on the VServer.",
			),
			"totspillovers" => Array(
				"cntrname"	=>	"totspillovers",
				"cntrdescr"	=>	"Spill Over Hits",
				"tooltip"	=>	"Number of times vserver experienced spill over.",
			),
			"totvserverdownbackuphits" => Array(
				"cntrname"	=>	"totvserverdownbackuphits",
				"cntrdescr"	=>	"Vserver Down Backup Hits",
				"tooltip"	=>	"Number of times traffic was diverted to backup vserver since primary vserver was DOWN.",
			),
			"totalrequests" => Array(
				"cntrname"	=>	"totalrequests",
				"cntrdescr"	=>	"Requests",
				"tooltip"	=>	"Total number of requests received on this service or virtual server. (This applies to HTTP/SSL services and servers.)",
			),
			"requestsrate" => array (
				"cntrname"	=>	"requestsrate",
				"cntrdescr"	=>	"",
			),
			"totalresponses" => Array(
				"cntrname"	=>	"totalresponses",
				"cntrdescr"	=>	"Responses",
				"tooltip"	=>	"Number of responses received on this service or virtual server. (This applies to HTTP/SSL services and servers.)",
			),
			"responsesrate" => array (
				"cntrname"	=>	"responsesrate",
				"cntrdescr"	=>	"",
			),
			"curclntconnections" => Array(
				"cntrname"	=>	"curclntconnections",
				"cntrdescr"	=>	"Current client connections",
				"tooltip"	=>	"Number of current client connections.",
			),
			"cursrvrconnections" => Array(
				"cntrname"	=>	"cursrvrconnections",
				"cntrdescr"	=>	"Current server connections",
				"tooltip"	=>	"Number of current connections to the actual servers behind the virtual server.",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Gslb Vserver Summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("type","state","vslbhealth","actsvcs","inactsvcs",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"VServer Stats:",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("tothits","totalrequestbytes","totalresponsebytes","establishedconn","sothreshold","totspillovers","totvserverdownbackuphits",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Vserver(s) Summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("type","state","hitsrate","vslbhealth",),
			),
		),
),


"statgslbdomain" => Array(
	"cmddispname"		=>	"statgslbdomain",
	"cmdname"		=>	"statgslbdomain",
	"license"		=>	Array("NSKF1_GSLB_BIT_POS","NSKF1_LOADBALANCE_BIT_POS","NSKF1_GSLB_BIT_POS","NSKF1_LOADBALANCE_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"dnsrecordtype" => Array(
				"argname"		=>	"dnsrecordtype",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"dnstotalqueries" => Array(
				"cntrname"	=>	"dnstotalqueries",
				"cntrdescr"	=>	"domainHits",
				"tooltip"	=>	"Total number of DNS queries received.",
			),
			"dnsqueriesrate" => array (
				"cntrname"	=>	"dnsqueriesrate",
				"cntrdescr"	=>	"",
			),
			"gslbdnsrectype" => Array(
				"cntrname"	=>	"gslbdnsrectype",
				"cntrdescr"	=>	"Dns Record Type",
				"tooltip"	=>	"Type of DNS record returned",
				"type"		=>	"NS_VALENUM",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"GSLB Record Type",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("gslbdnsrectype",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"GSLB Domain Summary",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("dnstotalqueries",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"GSLB Domain(s) Summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("dnsqueriesrate","gslbdnsrectype",),
			),
			Array(
				"dhID"	=>	4,
				"title"	=>	"Bound Domain(s) Summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"subent",
				"counters"	=>	array("dnstotalqueries","gslbdnsrectype",),
			),
		),
),


"stathanode" => Array(
	"cmddispname"		=>	"stathanode",
	"cmdname"		=>	"stathanode",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"hacurstatus" => Array(
				"cntrname"	=>	"hacurstatus",
				"cntrdescr"	=>	"High Availability",
				"tooltip"	=>	"Whether a NetScaler appliance is configured for high availability. Possible values are YES and NO. If the value is NO, the high availability statistics below are invalid.",
				"type"		=>	"NS_VALENUM",
			),
			"hacurstate" => Array(
				"cntrname"	=>	"hacurstate",
				"cntrdescr"	=>	"System state",
				"tooltip"	=>	"State of the HA node, based on its health, in a high availability setup. Possible values are: <br><br>UP – Indicates that the node is accessible and can function as either a primary or secondary node.<br><br>DISABLED – Indicates that the high availability status of the node has been manually disabled. Synchronization and propagation cannot take place between the peer nodes.<br><br>INIT – Indicates that the node is in the process of becoming part of the high availability configuration. <br><br>PARTIALFAIL – Indicates that one of the high availability monitored interfaces has failed because of a card or link failure. This state triggers a failover.<br><br>COMPLETEFAIL – Indicates that all the interfaces of the node are unusable, because the interfaces on which high availability monitoring is enabled are not connected or are manually disabled. This state triggers a failover.<br><br>DUMB – Indicates that the node is in listening mode. It does not participate in high availability transitions or transfer configuration from the peer node. This is a configured value, not a statistic.<br><br>PARTIALFAILSSL – Indicates that the SSL card has failed. This state triggers a failover.<br><br>ROUTEMONITORFAIL – Indicates that the route monitor has failed. This state triggers a failover.",
				"type"		=>	"NS_VALENUM",
			),
			"hacurmasterstate" => Array(
				"cntrname"	=>	"hacurmasterstate",
				"cntrdescr"	=>	"Master state",
				"tooltip"	=>	"Indicates the high availability state of the node. Possible values are: <br><br>STAYSECONDARY – Indicates that the selected node remains the secondary node in a high availability setup. In this case a forced failover does not change the state but, instead, returns an appropriate error message. This is a configured value and not a statistic.<br><br>PRIMARY – Indicates that the selected node is the primary node in a high availability setup. <br><br>SECONDARY – Indicates that the selected node is the secondary node in a high availability setup. <br><br>CLAIMING – Indicates that the secondary node is in the process of taking over as the primary node. This is the intermediate state in the transition of the secondary node to primary status. <br><br>FORCE CHANGE - Indicates that the secondary node is forcibly changing its status to primary due to a forced failover issued on the secondary node.",
				"type"		=>	"NS_VALENUM",
			),
			"transtime" => Array(
				"cntrname"	=>	"transtime",
				"cntrdescr"	=>	"Last Transition time",
				"tooltip"	=>	"Time when the last master state transition occurred. You can use this statistic for debugging.",
				"type"		=>	"NS_VALDATE",
			),
			"hatotpktrx" => Array(
				"cntrname"	=>	"hatotpktrx",
				"cntrdescr"	=>	"Heartbeats received",
				"tooltip"	=>	"Number of heartbeat packets received from the peer node. Heartbeats are sent at regular intervals (default is 200 milliseconds) to determine the state of the peer node.",
			),
			"hapktrxrate" => array (
				"cntrname"	=>	"hapktrxrate",
				"cntrdescr"	=>	"",
			),
			"hatotpkttx" => Array(
				"cntrname"	=>	"hatotpkttx",
				"cntrdescr"	=>	"Heartbeats sent",
				"tooltip"	=>	"Number of heartbeat packets sent to the peer node. Heartbeats are sent at regular intervals (default is 200 milliseconds) to determine the state of the peer node.",
			),
			"hapkttxrate" => array (
				"cntrname"	=>	"hapkttxrate",
				"cntrdescr"	=>	"",
			),
			"haerrproptimeout" => Array(
				"cntrname"	=>	"haerrproptimeout",
				"cntrdescr"	=>	"Propagation timeouts",
				"tooltip"	=>	"Number of times propagation timed out.",
			),
			"haerrsyncfailure" => Array(
				"cntrname"	=>	"haerrsyncfailure",
				"cntrdescr"	=>	"Sync failure",
				"tooltip"	=>	"Number of times the configuration of the primary and secondary nodes failed to synchronize since that last transition. A synchronization failure results in mismatched configuration. It can be caused by a mismatch in the Remote Procedural Call (RPC) password on the two nodes forming the high availability pair.",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"HA Summary",
				"header"	=>	array(),
				"format"	=>	"1C",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("hacurstatus","hacurstate","hacurmasterstate","transtime","hatotpktrx","hatotpkttx",),
			),
			Array(
				"text" => "HA Statistics",
				"flag" => "Detail",
				"key" => "detail",
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Summary",
				"header"	=>	array(),
				"format"	=>	"1C",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("hacurstatus","hacurstate","hacurmasterstate","transtime","hatotpktrx","hatotpkttx",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Error Statistics",
				"header"	=>	array(),
				"format"	=>	"1C",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("haerrproptimeout","haerrsyncfailure",),
			),
		),
),


"staticapolicy" => Array(
	"cmddispname"		=>	"staticapolicy",
	"cmdname"		=>	"staticapolicy",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"pipolicyhits" => Array(
				"cntrname"	=>	"pipolicyhits",
				"cntrdescr"	=>	"Policy hits",
				"tooltip"	=>	"Number of hits on the policy",
			),
			"pipolicyhitsrate" => array (
				"cntrname"	=>	"pipolicyhitsrate",
				"cntrdescr"	=>	"",
			),
			"pipolicyundefhits" => Array(
				"cntrname"	=>	"pipolicyundefhits",
				"cntrdescr"	=>	"Policy undef hits",
				"tooltip"	=>	"Number of undef hits on the policy",
			),
			"pipolicyundefhitsrate" => array (
				"cntrname"	=>	"pipolicyundefhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"ICA Policy Statistics",
				"header"	=>	array("Name","Hits","Rate(/s)","UndefHits","Rate(/s)",),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pipolicyhits","pipolicyundefhits",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"ICA Policy Statistics",
				"header"	=>	array("Name","Hits","Rate(/s)","UndefHits","Rate(/s)",),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pipolicyhits","pipolicyundefhits",),
			),
		),
),


"statipseccounters" => Array(
	"cmddispname"		=>	"statipseccounters",
	"cmdname"		=>	"statipseccounters",
	"license"		=>	Array("NSKF2_CLOUDB_BIT_POS","NSKF2_CLOUDB_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"ipsectotrxbytes" => Array(
				"cntrname"	=>	"ipsectotrxbytes",
				"cntrdescr"	=>	"Bytes Received",
				"tooltip"	=>	"Bytes received during IPsec sessions.",
			),
			"ipsecrxbytesrate" => array (
				"cntrname"	=>	"ipsecrxbytesrate",
				"cntrdescr"	=>	"",
			),
			"ipsectottxbytes" => Array(
				"cntrname"	=>	"ipsectottxbytes",
				"cntrdescr"	=>	"Bytes Sent",
				"tooltip"	=>	"Bytes sent during IPsec sessions.",
			),
			"ipsectxbytesrate" => array (
				"cntrname"	=>	"ipsectxbytesrate",
				"cntrdescr"	=>	"",
			),
			"ipsectotrxpkts" => Array(
				"cntrname"	=>	"ipsectotrxpkts",
				"cntrdescr"	=>	"Packets Received",
				"tooltip"	=>	"Packets received during IPsec sessions.",
			),
			"ipsecrxpktsrate" => array (
				"cntrname"	=>	"ipsecrxpktsrate",
				"cntrdescr"	=>	"",
			),
			"ipsectottxpkts" => Array(
				"cntrname"	=>	"ipsectottxpkts",
				"cntrdescr"	=>	"Packets Sent",
				"tooltip"	=>	"Packets sent during IPsec sessions.",
			),
			"ipsectxpktsrate" => array (
				"cntrname"	=>	"ipsectxpktsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Secure tunnel(s) summary",
				"header"	=>	array(" ","  Rate (/s)","  Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("ipsectotrxbytes","ipsectottxbytes","ipsectotrxpkts","ipsectottxpkts",),
			),
		),
),


"statlbvserver" => Array(
	"cmddispname"		=>	"statlbvserver",
	"enable"            =>      "1",
	"cmdname"		=>	"statlbvserver",
	"license"		=>	Array("NSKF1_LOADBALANCE_BIT_POS","NSKF1_LOADBALANCE_BIT_POS"),
	"cmddescr"	=>	"     ",
	"bindings"	=>	1,
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
			"sortby" => Array(
				"argname"		=>	"sortby",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
			"sortorder" => Array(
				"argname"		=>	"sortorder",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"vsvrsurgecount" => Array(
				"cntrname"	=>	"vsvrsurgecount",
				"cntrdescr"	=>	"Requests in vserver's surgeQ",
				"tooltip"	=>	"Number of requests waiting on this vserver.",
			),
			"establishedconn" => Array(
				"cntrname"	=>	"establishedconn",
				"cntrdescr"	=>	"Current Client Est connections",
				"tooltip"	=>	"Number of client connections in ESTABLISHED state.",
			),
			"inactsvcs" => Array(
				"cntrname"	=>	"inactsvcs",
				"cntrdescr"	=>	"total INACTIVE services",
				"tooltip"	=>	"number of INACTIVE services bound to a vserver",
			),
			"vslbhealth" => Array(
				"cntrname"	=>	"vslbhealth",
				"cntrdescr"	=>	"Vserver Health",
				"tooltip"	=>	"Health of the vserver. This gives percentage of UP services bound to this vserver.",
			),
			"primaryipaddress" => Array(
				"cntrname"	=>	"primaryipaddress",
				"cntrdescr"	=>	"Vserver IP address",
				"tooltip"	=>	"IP address of the vserver",
				"type"		=>	"NS_VALIPSTR",
			),
			"primaryport" => Array(
				"cntrname"	=>	"primaryport",
				"cntrdescr"	=>	"Port",
				"tooltip"	=>	"The port on which the service is running.",
				"type"		=>	"NS_VALPORT",
			),
			"type" => Array(
				"cntrname"	=>	"type",
				"cntrdescr"	=>	"Vserver protocol",
				"tooltip"	=>	"Protocol associated with the vserver",
				"type"		=>	"NS_VALENUM",
			),
			"state" => Array(
				"cntrname"	=>	"state",
				"cntrdescr"	=>	"State",
				"tooltip"	=>	"Current state of the server. Possible values are UP, DOWN, UNKNOWN, OFS(Out of Service), TROFS(Transition Out of Service), TROFS_DOWN(Down When going Out of Service)",
				"type"		=>	"NS_VALENUM",
			),
			"actsvcs" => Array(
				"cntrname"	=>	"actsvcs",
				"cntrdescr"	=>	"total ACTIVE services",
				"tooltip"	=>	"number of ACTIVE services bound to a vserver",
			),
			"tothits" => Array(
				"cntrname"	=>	"tothits",
				"cntrdescr"	=>	"Vserver hits",
				"tooltip"	=>	"Total vserver hits",
			),
			"hitsrate" => array (
				"cntrname"	=>	"hitsrate",
				"cntrdescr"	=>	"",
			),
			"totalrequests" => Array(
				"cntrname"	=>	"totalrequests",
				"cntrdescr"	=>	"Requests",
				"tooltip"	=>	"Total number of requests received on this service or virtual server. (This applies to HTTP/SSL services and servers.)",
			),
			"requestsrate" => array (
				"cntrname"	=>	"requestsrate",
				"cntrdescr"	=>	"",
			),
			"totalresponses" => Array(
				"cntrname"	=>	"totalresponses",
				"cntrdescr"	=>	"Responses",
				"tooltip"	=>	"Number of responses received on this service or virtual server. (This applies to HTTP/SSL services and servers.)",
			),
			"responsesrate" => array (
				"cntrname"	=>	"responsesrate",
				"cntrdescr"	=>	"",
			),
			"totalrequestbytes" => Array(
				"cntrname"	=>	"totalrequestbytes",
				"cntrdescr"	=>	"Request bytes",
				"tooltip"	=>	"Total number of request bytes received on this service or virtual server.",
			),
			"requestbytesrate" => array (
				"cntrname"	=>	"requestbytesrate",
				"cntrdescr"	=>	"",
			),
			"totalresponsebytes" => Array(
				"cntrname"	=>	"totalresponsebytes",
				"cntrdescr"	=>	"Response bytes",
				"tooltip"	=>	"Number of response bytes received by this service or virtual server.",
			),
			"responsebytesrate" => array (
				"cntrname"	=>	"responsebytesrate",
				"cntrdescr"	=>	"",
			),
			"totalpktsrecvd" => Array(
				"cntrname"	=>	"totalpktsrecvd",
				"cntrdescr"	=>	"Total Packets rcvd",
				"tooltip"	=>	"Total number of packets received by this service or virtual server.",
			),
			"pktsrecvdrate" => array (
				"cntrname"	=>	"pktsrecvdrate",
				"cntrdescr"	=>	"",
			),
			"totalpktssent" => Array(
				"cntrname"	=>	"totalpktssent",
				"cntrdescr"	=>	"Total Packets sent",
				"tooltip"	=>	"Total number of packets sent.",
			),
			"pktssentrate" => array (
				"cntrname"	=>	"pktssentrate",
				"cntrdescr"	=>	"",
			),
			"curclntconnections" => Array(
				"cntrname"	=>	"curclntconnections",
				"cntrdescr"	=>	"Current client connections",
				"tooltip"	=>	"Number of current client connections.",
			),
			"cursrvrconnections" => Array(
				"cntrname"	=>	"cursrvrconnections",
				"cntrdescr"	=>	"Current server connections",
				"tooltip"	=>	"Number of current connections to the actual servers behind the virtual server.",
			),
			"surgecount" => Array(
				"cntrname"	=>	"surgecount",
				"cntrdescr"	=>	"Requests in surge queue",
				"tooltip"	=>	"Number of requests in the surge queue.",
			),
			"svcsurgecount" => Array(
				"cntrname"	=>	"svcsurgecount",
				"cntrdescr"	=>	"Requests in service's surgeQs",
				"tooltip"	=>	"Total number of requests in the surge queues of all the services bound to this LB-vserver.",
			),
			"sothreshold" => Array(
				"cntrname"	=>	"sothreshold",
				"cntrdescr"	=>	"Spill Over Threshold",
				"tooltip"	=>	"Spill Over Threshold set on the VServer.",
			),
			"totspillovers" => Array(
				"cntrname"	=>	"totspillovers",
				"cntrdescr"	=>	"Spill Over Hits",
				"tooltip"	=>	"Number of times vserver experienced spill over.",
			),
			"labelledconn" => Array(
				"cntrname"	=>	"labelledconn",
				"cntrdescr"	=>	"Labeled Connection",
				"tooltip"	=>	"Number of Labeled connection on this vserver",
			),
			"pushlabel" => Array(
				"cntrname"	=>	"pushlabel",
				"cntrdescr"	=>	"Push Labeled Connection",
				"tooltip"	=>	"Number of labels for this push vserver.",
			),
			"deferredreq" => Array(
				"cntrname"	=>	"deferredreq",
				"cntrdescr"	=>	"Deferred Request",
				"tooltip"	=>	"Number of deferred request on this vserver",
			),
			"deferredreqrate" => array (
				"cntrname"	=>	"deferredreqrate",
				"cntrdescr"	=>	"",
			),
			"invalidrequestresponse" => Array(
				"cntrname"	=>	"invalidrequestresponse",
				"cntrdescr"	=>	"Invalid Request/Response",
				"tooltip"	=>	"Number invalid requests/responses on this vserver",
			),
			"invalidrequestresponsedropped" => Array(
				"cntrname"	=>	"invalidrequestresponsedropped",
				"cntrdescr"	=>	"Invalid Request/Response Dropped",
				"tooltip"	=>	"Number invalid requests/responses dropped on this vserver",
			),
			"totvserverdownbackuphits" => Array(
				"cntrname"	=>	"totvserverdownbackuphits",
				"cntrdescr"	=>	"Vserver Down Backup Hits",
				"tooltip"	=>	"Number of times traffic was diverted to backup vserver since primary vserver was DOWN.",
			),
			"curmptcpsessions" => Array(
				"cntrname"	=>	"curmptcpsessions",
				"cntrdescr"	=>	"Current Multipath TCP sessions",
				"tooltip"	=>	"Current Multipath TCP sessions",
			),
			"cursubflowconn" => Array(
				"cntrname"	=>	"cursubflowconn",
				"cntrdescr"	=>	"Current Multipath TCP subflows",
				"tooltip"	=>	"Current Multipath TCP subflows",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Virtual Server Summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("primaryipaddress","primaryport","type","state","vslbhealth","actsvcs","inactsvcs",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Virtual Server Statistics",
				"header"	=>	array(" ","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("tothits","totalrequests","totalresponses","totalrequestbytes","totalresponsebytes","totalpktsrecvd","totalpktssent","curclntconnections","establishedconn","cursrvrconnections","surgecount","vsvrsurgecount","svcsurgecount","sothreshold","totspillovers","labelledconn","pushlabel","deferredreq","invalidrequestresponse","invalidrequestresponsedropped","totvserverdownbackuphits","curmptcpsessions","cursubflowconn",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Virtual Server(s) Summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("primaryipaddress","primaryport","type","state","requestsrate",),
			),
			Array(
				"dhID"	=>	4,
				"title"	=>	"Virtual Server(s) Summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("primaryipaddress","primaryport","type","state","requestsrate","hitsrate",),
			),
		),
),


"statlsn" => Array(
	"cmddispname"		=>	"statlsn",
	"cmdname"		=>	"statlsn",
	"license"		=>	Array("NSKF2_LSN_BIT_POS","NSKF2_LSN_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"lsntottcprxpkts" => Array(
				"cntrname"	=>	"lsntottcprxpkts",
				"cntrdescr"	=>	"LSN TCP Received Packets",
				"tooltip"	=>	"Number of LSN TCP Received packets.",
			),
			"lsntcprxpktsrate" => array (
				"cntrname"	=>	"lsntcprxpktsrate",
				"cntrdescr"	=>	"",
			),
			"lsntottcprxbytes" => Array(
				"cntrname"	=>	"lsntottcprxbytes",
				"cntrdescr"	=>	"LSN TCP Received Bytes",
				"tooltip"	=>	"Number of LSN TCP Received bytes.",
			),
			"lsntcprxbytesrate" => array (
				"cntrname"	=>	"lsntcprxbytesrate",
				"cntrdescr"	=>	"",
			),
			"lsntottcptxpkts" => Array(
				"cntrname"	=>	"lsntottcptxpkts",
				"cntrdescr"	=>	"LSN TCP Transmitted Packets",
				"tooltip"	=>	"Number of LSN TCP Transmitted packets.",
			),
			"lsntcptxpktsrate" => array (
				"cntrname"	=>	"lsntcptxpktsrate",
				"cntrdescr"	=>	"",
			),
			"lsntottcptxbytes" => Array(
				"cntrname"	=>	"lsntottcptxbytes",
				"cntrdescr"	=>	"LSN TCP Transmitted Bytes",
				"tooltip"	=>	"Number of LSN TCP Transmitted bytes.",
			),
			"lsntcptxbytesrate" => array (
				"cntrname"	=>	"lsntcptxbytesrate",
				"cntrdescr"	=>	"",
			),
			"lsntottcpdrppkts" => Array(
				"cntrname"	=>	"lsntottcpdrppkts",
				"cntrdescr"	=>	"LSN TCP Dropped Packets",
				"tooltip"	=>	"Number of LSN TCP Dropped packets.",
			),
			"lsntcpdrppktsrate" => array (
				"cntrname"	=>	"lsntcpdrppktsrate",
				"cntrdescr"	=>	"",
			),
			"lsncurtcpsessions" => Array(
				"cntrname"	=>	"lsncurtcpsessions",
				"cntrdescr"	=>	"LSN TCP Current Sessions",
				"tooltip"	=>	"Number of LSN TCP Current Sessions.",
			),
			"lsncurtcpsessionsrate" => array (
				"cntrname"	=>	"lsncurtcpsessionsrate",
				"cntrdescr"	=>	"",
			),
			"lsntotudprxpkts" => Array(
				"cntrname"	=>	"lsntotudprxpkts",
				"cntrdescr"	=>	"LSN UDP Received Packets",
				"tooltip"	=>	"Number of LSN UDP Received packets.",
			),
			"lsnudprxpktsrate" => array (
				"cntrname"	=>	"lsnudprxpktsrate",
				"cntrdescr"	=>	"",
			),
			"lsntotudprxbytes" => Array(
				"cntrname"	=>	"lsntotudprxbytes",
				"cntrdescr"	=>	"LSN UDP Received Bytes",
				"tooltip"	=>	"Number of LSN UDP Received bytes.",
			),
			"lsnudprxbytesrate" => array (
				"cntrname"	=>	"lsnudprxbytesrate",
				"cntrdescr"	=>	"",
			),
			"lsntotudptxpkts" => Array(
				"cntrname"	=>	"lsntotudptxpkts",
				"cntrdescr"	=>	"LSN UDP Transmitted Packets",
				"tooltip"	=>	"Number of LSN UDP Transmitted packets.",
			),
			"lsnudptxpktsrate" => array (
				"cntrname"	=>	"lsnudptxpktsrate",
				"cntrdescr"	=>	"",
			),
			"lsntotudptxbytes" => Array(
				"cntrname"	=>	"lsntotudptxbytes",
				"cntrdescr"	=>	"LSN UDP Transmitted Bytes",
				"tooltip"	=>	"Number of LSN UDP Transmitted bytes.",
			),
			"lsnudptxbytesrate" => array (
				"cntrname"	=>	"lsnudptxbytesrate",
				"cntrdescr"	=>	"",
			),
			"lsntotudpdrppkts" => Array(
				"cntrname"	=>	"lsntotudpdrppkts",
				"cntrdescr"	=>	"LSN UDP Dropped Packets",
				"tooltip"	=>	"Number of LSN UDP Dropped packets.",
			),
			"lsnudpdrppktsrate" => array (
				"cntrname"	=>	"lsnudpdrppktsrate",
				"cntrdescr"	=>	"",
			),
			"lsncurudpsessions" => Array(
				"cntrname"	=>	"lsncurudpsessions",
				"cntrdescr"	=>	"LSN UDP Current Sessions",
				"tooltip"	=>	"Number of LSN UDP Current Sessions.",
			),
			"lsncurudpsessionsrate" => array (
				"cntrname"	=>	"lsncurudpsessionsrate",
				"cntrdescr"	=>	"",
			),
			"lsntoticmprxpkts" => Array(
				"cntrname"	=>	"lsntoticmprxpkts",
				"cntrdescr"	=>	"LSN ICMP Received Packets",
				"tooltip"	=>	"Number of LSN ICMP Received packets.",
			),
			"lsnicmprxpktsrate" => array (
				"cntrname"	=>	"lsnicmprxpktsrate",
				"cntrdescr"	=>	"",
			),
			"lsntoticmprxbytes" => Array(
				"cntrname"	=>	"lsntoticmprxbytes",
				"cntrdescr"	=>	"LSN ICMP Received Bytes",
				"tooltip"	=>	"Number of LSN ICMP Received bytes.",
			),
			"lsnicmprxbytesrate" => array (
				"cntrname"	=>	"lsnicmprxbytesrate",
				"cntrdescr"	=>	"",
			),
			"lsntoticmptxpkts" => Array(
				"cntrname"	=>	"lsntoticmptxpkts",
				"cntrdescr"	=>	"LSN ICMP Transmitted Packets",
				"tooltip"	=>	"Number of LSN ICMP Transmitted packets.",
			),
			"lsnicmptxpktsrate" => array (
				"cntrname"	=>	"lsnicmptxpktsrate",
				"cntrdescr"	=>	"",
			),
			"lsntoticmptxbytes" => Array(
				"cntrname"	=>	"lsntoticmptxbytes",
				"cntrdescr"	=>	"LSN ICMP Transmitted Bytes",
				"tooltip"	=>	"Number of LSN ICMP Transmitted bytes.",
			),
			"lsnicmptxbytesrate" => array (
				"cntrname"	=>	"lsnicmptxbytesrate",
				"cntrdescr"	=>	"",
			),
			"lsntoticmpdrppkts" => Array(
				"cntrname"	=>	"lsntoticmpdrppkts",
				"cntrdescr"	=>	"LSN ICMP Dropped Packets",
				"tooltip"	=>	"Number of LSN ICMP Dropped packets.",
			),
			"lsnicmpdrppktsrate" => array (
				"cntrname"	=>	"lsnicmpdrppktsrate",
				"cntrdescr"	=>	"",
			),
			"lsncuricmpsessions" => Array(
				"cntrname"	=>	"lsncuricmpsessions",
				"cntrdescr"	=>	"LSN ICMP Current Sessions",
				"tooltip"	=>	"Number of LSN ICMP Current Sessions.",
			),
			"lsncuricmpsessionsrate" => array (
				"cntrname"	=>	"lsncuricmpsessionsrate",
				"cntrdescr"	=>	"",
			),
			"lsncursessions" => Array(
				"cntrname"	=>	"lsncursessions",
				"cntrdescr"	=>	"LSN sessions",
				"tooltip"	=>	"Current number of LSN sessions.",
			),
			"lsncursessionsrate" => array (
				"cntrname"	=>	"lsncursessionsrate",
				"cntrdescr"	=>	"",
			),
			"lsncursubscribers" => Array(
				"cntrname"	=>	"lsncursubscribers",
				"cntrdescr"	=>	"LSN Subscribers",
				"tooltip"	=>	"Current number of LSN subscribers.",
			),
			"lsncursubscribersrate" => array (
				"cntrname"	=>	"lsncursubscribersrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"",
				"header"	=>	array("","Rate(/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("lsntottcprxpkts","lsntottcprxbytes","lsntottcptxpkts","lsntottcptxbytes","lsntottcpdrppkts","lsncurtcpsessions","lsntotudprxpkts","lsntotudprxbytes","lsntotudptxpkts","lsntotudptxbytes","lsntotudpdrppkts","lsncurudpsessions","lsntoticmprxpkts","lsntoticmprxbytes","lsntoticmptxpkts","lsntoticmptxbytes","lsntoticmpdrppkts","lsncuricmpsessions","lsncursessions","lsncursubscribers",),
			),
		),
),


"statlsngroup" => Array(
	"cmddispname"		=>	"statlsngroup",
	"cmdname"		=>	"statlsngroup",
	"license"		=>	Array("NSKF2_LSN_BIT_POS","NSKF2_LSN_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"groupname" => Array(
				"argname"		=>	"groupname",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "groupname",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"lsngrpcursessions" => Array(
				"cntrname"	=>	"lsngrpcursessions",
				"cntrdescr"	=>	"Current Sessions",
				"tooltip"	=>	"Number of Current Sessions for LSN group",
			),
			"lsngrpcursessionsrate" => array (
				"cntrname"	=>	"lsngrpcursessionsrate",
				"cntrdescr"	=>	"",
			),
			"lsngrptottranslbytes" => Array(
				"cntrname"	=>	"lsngrptottranslbytes",
				"cntrdescr"	=>	"Translated Bytes",
				"tooltip"	=>	"Number of Translated Bytes for LSN group",
			),
			"lsngrptranslbytesrate" => array (
				"cntrname"	=>	"lsngrptranslbytesrate",
				"cntrdescr"	=>	"",
			),
			"lsngrptottranslpkts" => Array(
				"cntrname"	=>	"lsngrptottranslpkts",
				"cntrdescr"	=>	"Translated Pkts",
				"tooltip"	=>	"Number of Translated Pkts for LSN group",
			),
			"lsngrptranslpktsrate" => array (
				"cntrname"	=>	"lsngrptranslpktsrate",
				"cntrdescr"	=>	"",
			),
			"lsngrptottcptranslpkts" => Array(
				"cntrname"	=>	"lsngrptottcptranslpkts",
				"cntrdescr"	=>	"TCP Translated Pkts",
				"tooltip"	=>	"Number of TCP Translated Pkts for LSN group",
			),
			"lsngrptcptranslpktsrate" => array (
				"cntrname"	=>	"lsngrptcptranslpktsrate",
				"cntrdescr"	=>	"",
			),
			"lsngrptottcptranslbytes" => Array(
				"cntrname"	=>	"lsngrptottcptranslbytes",
				"cntrdescr"	=>	"TCP Translated Bytes",
				"tooltip"	=>	"Number of TCP Translated Bytes for LSN group",
			),
			"lsngrptcptranslbytesrate" => array (
				"cntrname"	=>	"lsngrptcptranslbytesrate",
				"cntrdescr"	=>	"",
			),
			"lsngrptottcpdrppkts" => Array(
				"cntrname"	=>	"lsngrptottcpdrppkts",
				"cntrdescr"	=>	"TCP Dropped Pkts",
				"tooltip"	=>	"Number of TCP Dropped Pkts for LSN group",
			),
			"lsngrptcpdrppktsrate" => array (
				"cntrname"	=>	"lsngrptcpdrppktsrate",
				"cntrdescr"	=>	"",
			),
			"lsngrpcurtcpsessions" => Array(
				"cntrname"	=>	"lsngrpcurtcpsessions",
				"cntrdescr"	=>	"TCP Current Sessions",
				"tooltip"	=>	"Number of TCP Current Sessions for LSN group",
			),
			"lsngrpcurtcpsessionsrate" => array (
				"cntrname"	=>	"lsngrpcurtcpsessionsrate",
				"cntrdescr"	=>	"",
			),
			"lsngrptotudptranslpkts" => Array(
				"cntrname"	=>	"lsngrptotudptranslpkts",
				"cntrdescr"	=>	"UDP Translated Pkts",
				"tooltip"	=>	"Number of UDP Translated Pkts for LSN group",
			),
			"lsngrpudptranslpktsrate" => array (
				"cntrname"	=>	"lsngrpudptranslpktsrate",
				"cntrdescr"	=>	"",
			),
			"lsngrptotudptranslbytes" => Array(
				"cntrname"	=>	"lsngrptotudptranslbytes",
				"cntrdescr"	=>	"UDP Translated Bytes",
				"tooltip"	=>	"Number of UDP Translated Bytes for LSN group",
			),
			"lsngrpudptranslbytesrate" => array (
				"cntrname"	=>	"lsngrpudptranslbytesrate",
				"cntrdescr"	=>	"",
			),
			"lsngrptotudpdrppkts" => Array(
				"cntrname"	=>	"lsngrptotudpdrppkts",
				"cntrdescr"	=>	"UDP Dropped Pkts",
				"tooltip"	=>	"Number of UDP Dropped Pkts for LSN group",
			),
			"lsngrpudpdrppktsrate" => array (
				"cntrname"	=>	"lsngrpudpdrppktsrate",
				"cntrdescr"	=>	"",
			),
			"lsngrpcurudpsessions" => Array(
				"cntrname"	=>	"lsngrpcurudpsessions",
				"cntrdescr"	=>	"UDP Current Sessions",
				"tooltip"	=>	"Number of UDP Current Sessions for LSN group",
			),
			"lsngrpcurudpsessionsrate" => array (
				"cntrname"	=>	"lsngrpcurudpsessionsrate",
				"cntrdescr"	=>	"",
			),
			"lsngrptoticmptranslpkts" => Array(
				"cntrname"	=>	"lsngrptoticmptranslpkts",
				"cntrdescr"	=>	"ICMP Translated Pkts",
				"tooltip"	=>	"Number of ICMP Translated Pkts for LSN group",
			),
			"lsngrpicmptranslpktsrate" => array (
				"cntrname"	=>	"lsngrpicmptranslpktsrate",
				"cntrdescr"	=>	"",
			),
			"lsngrptoticmptranslbytes" => Array(
				"cntrname"	=>	"lsngrptoticmptranslbytes",
				"cntrdescr"	=>	"ICMP Translated Bytes",
				"tooltip"	=>	"Number of ICMP Translated Bytes for LSN group",
			),
			"lsngrpicmptranslbytesrate" => array (
				"cntrname"	=>	"lsngrpicmptranslbytesrate",
				"cntrdescr"	=>	"",
			),
			"lsngrptoticmpdrppkts" => Array(
				"cntrname"	=>	"lsngrptoticmpdrppkts",
				"cntrdescr"	=>	"ICMP Dropped Pkts",
				"tooltip"	=>	"Number of ICMP Dropped Pkts for LSN group",
			),
			"lsngrpicmpdrppktsrate" => array (
				"cntrname"	=>	"lsngrpicmpdrppktsrate",
				"cntrdescr"	=>	"",
			),
			"lsngrpcuricmpsessions" => Array(
				"cntrname"	=>	"lsngrpcuricmpsessions",
				"cntrdescr"	=>	"ICMP Current Sessions",
				"tooltip"	=>	"Number of ICMP Current Sessions for LSN group",
			),
			"lsngrpcuricmpsessionsrate" => array (
				"cntrname"	=>	"lsngrpcuricmpsessionsrate",
				"cntrdescr"	=>	"",
			),
			"lsngrpcursubscribers" => Array(
				"cntrname"	=>	"lsngrpcursubscribers",
				"cntrdescr"	=>	"Current Subscribers",
				"tooltip"	=>	"Number of ICMP Current Sessions for LSN group",
			),
			"lsngrpcursubscribersrate" => array (
				"cntrname"	=>	"lsngrpcursubscribersrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"LSN Group Statistics",
				"header"	=>	array(" ","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("lsngrptottcptranslpkts","lsngrptottcptranslbytes","lsngrptottcpdrppkts","lsngrpcurtcpsessions","lsngrptotudptranslpkts","lsngrptotudptranslbytes","lsngrptotudpdrppkts","lsngrpcurudpsessions","lsngrptoticmptranslpkts","lsngrptoticmptranslbytes","lsngrptoticmpdrppkts","lsngrpcuricmpsessions","lsngrpcursubscribers",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"LSN Group(s) Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("lsngrptottranslpkts","lsngrptottranslbytes","lsngrpcursessions","lsngrpcursubscribers",),
			),
		),
),


"statlsndslite" => Array(
	"cmddispname"		=>	"statlsndslite",
	"cmdname"		=>	"statlsndslite",
	"license"		=>	Array("NSKF2_LSN_BIT_POS","NSKF2_LSN_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"lsntotdsliterxpkts" => Array(
				"cntrname"	=>	"lsntotdsliterxpkts",
				"cntrdescr"	=>	"DS-Lite Received Packets",
				"tooltip"	=>	"Total number of LSN DS-Lite rx pkts.",
			),
			"lsndsliterxpktsrate" => array (
				"cntrname"	=>	"lsndsliterxpktsrate",
				"cntrdescr"	=>	"",
			),
			"lsntotdsliterxbytes" => Array(
				"cntrname"	=>	"lsntotdsliterxbytes",
				"cntrdescr"	=>	"DS-Lite Received Bytes",
				"tooltip"	=>	"Total number of LSN DS-Lite rx bytes.",
			),
			"lsndsliterxbytesrate" => array (
				"cntrname"	=>	"lsndsliterxbytesrate",
				"cntrdescr"	=>	"",
			),
			"lsntotdslitetxpkts" => Array(
				"cntrname"	=>	"lsntotdslitetxpkts",
				"cntrdescr"	=>	"DS-Lite Transmitted Packets",
				"tooltip"	=>	"Total number of LSN DS-Lite tx pkts.",
			),
			"lsndslitetxpktsrate" => array (
				"cntrname"	=>	"lsndslitetxpktsrate",
				"cntrdescr"	=>	"",
			),
			"lsntotdslitetxbytes" => Array(
				"cntrname"	=>	"lsntotdslitetxbytes",
				"cntrdescr"	=>	"DS-Lite Transmitted Bytes",
				"tooltip"	=>	"Total number of LSN DS-Lite tx bytes.",
			),
			"lsndslitetxbytesrate" => array (
				"cntrname"	=>	"lsndslitetxbytesrate",
				"cntrdescr"	=>	"",
			),
			"lsntotdslitetcprxpkts" => Array(
				"cntrname"	=>	"lsntotdslitetcprxpkts",
				"cntrdescr"	=>	"DS-Lite TCP Received Packets",
				"tooltip"	=>	"Number of LSN DS-Lite TCP Received packets.",
			),
			"lsndslitetcprxpktsrate" => array (
				"cntrname"	=>	"lsndslitetcprxpktsrate",
				"cntrdescr"	=>	"",
			),
			"lsntotdslitetcprxbytes" => Array(
				"cntrname"	=>	"lsntotdslitetcprxbytes",
				"cntrdescr"	=>	"DS-Lite TCP Received Bytes",
				"tooltip"	=>	"Number of LSN DS-Lite TCP Received bytes.",
			),
			"lsndslitetcprxbytesrate" => array (
				"cntrname"	=>	"lsndslitetcprxbytesrate",
				"cntrdescr"	=>	"",
			),
			"lsntotdslitetcptxpkts" => Array(
				"cntrname"	=>	"lsntotdslitetcptxpkts",
				"cntrdescr"	=>	"DS-Lite TCP Transmitted Packets",
				"tooltip"	=>	"Number of LSN DS-Lite TCP Transmitted packets.",
			),
			"lsndslitetcptxpktsrate" => array (
				"cntrname"	=>	"lsndslitetcptxpktsrate",
				"cntrdescr"	=>	"",
			),
			"lsntotdslitetcptxbytes" => Array(
				"cntrname"	=>	"lsntotdslitetcptxbytes",
				"cntrdescr"	=>	"DS-Lite TCP Transmitted Bytes",
				"tooltip"	=>	"Number of LSN DS-Lite TCP Transmitted bytes.",
			),
			"lsndslitetcptxbytesrate" => array (
				"cntrname"	=>	"lsndslitetcptxbytesrate",
				"cntrdescr"	=>	"",
			),
			"lsntotdslitetcpdrppkts" => Array(
				"cntrname"	=>	"lsntotdslitetcpdrppkts",
				"cntrdescr"	=>	"DS-Lite TCP Dropped Packets",
				"tooltip"	=>	"Number of LSN DS-Lite TCP Dropped packets.",
			),
			"lsndslitetcpdrppktsrate" => array (
				"cntrname"	=>	"lsndslitetcpdrppktsrate",
				"cntrdescr"	=>	"",
			),
			"lsncurdslitetcpsessions" => Array(
				"cntrname"	=>	"lsncurdslitetcpsessions",
				"cntrdescr"	=>	"DS-Lite TCP Current Sessions",
				"tooltip"	=>	"Number of LSN DS-Lite TCP Current Sessions.",
			),
			"lsncurdslitetcpsessionsrate" => array (
				"cntrname"	=>	"lsncurdslitetcpsessionsrate",
				"cntrdescr"	=>	"",
			),
			"lsntotdsliteudprxpkts" => Array(
				"cntrname"	=>	"lsntotdsliteudprxpkts",
				"cntrdescr"	=>	"DS-Lite UDP Received Packets",
				"tooltip"	=>	"Number of LSN DS-Lite UDP Received packets.",
			),
			"lsndsliteudprxpktsrate" => array (
				"cntrname"	=>	"lsndsliteudprxpktsrate",
				"cntrdescr"	=>	"",
			),
			"lsntotdsliteudprxbytes" => Array(
				"cntrname"	=>	"lsntotdsliteudprxbytes",
				"cntrdescr"	=>	"DS-Lite UDP Received Bytes",
				"tooltip"	=>	"Number of LSN DS-Lite UDP Received bytes.",
			),
			"lsndsliteudprxbytesrate" => array (
				"cntrname"	=>	"lsndsliteudprxbytesrate",
				"cntrdescr"	=>	"",
			),
			"lsntotdsliteudptxpkts" => Array(
				"cntrname"	=>	"lsntotdsliteudptxpkts",
				"cntrdescr"	=>	"DS-Lite UDP Transmitted Packets",
				"tooltip"	=>	"Number of LSN DS-Lite UDP Transmitted packets.",
			),
			"lsndsliteudptxpktsrate" => array (
				"cntrname"	=>	"lsndsliteudptxpktsrate",
				"cntrdescr"	=>	"",
			),
			"lsntotdsliteudptxbytes" => Array(
				"cntrname"	=>	"lsntotdsliteudptxbytes",
				"cntrdescr"	=>	"DS-Lite UDP Transmitted Bytes",
				"tooltip"	=>	"Number of LSN DS-Lite UDP Transmitted bytes.",
			),
			"lsndsliteudptxbytesrate" => array (
				"cntrname"	=>	"lsndsliteudptxbytesrate",
				"cntrdescr"	=>	"",
			),
			"lsntotdsliteudpdrppkts" => Array(
				"cntrname"	=>	"lsntotdsliteudpdrppkts",
				"cntrdescr"	=>	"DS-Lite UDP Dropped Packets",
				"tooltip"	=>	"Number of LSN DS-Lite UDP Dropped packets.",
			),
			"lsndsliteudpdrppktsrate" => array (
				"cntrname"	=>	"lsndsliteudpdrppktsrate",
				"cntrdescr"	=>	"",
			),
			"lsncurdsliteudpsessions" => Array(
				"cntrname"	=>	"lsncurdsliteudpsessions",
				"cntrdescr"	=>	"DS-Lite UDP Current Sessions",
				"tooltip"	=>	"Number of LSN DS-Lite UDP Current Sessions.",
			),
			"lsncurdsliteudpsessionsrate" => array (
				"cntrname"	=>	"lsncurdsliteudpsessionsrate",
				"cntrdescr"	=>	"",
			),
			"lsntotdsliteicmprxpkts" => Array(
				"cntrname"	=>	"lsntotdsliteicmprxpkts",
				"cntrdescr"	=>	"DS-Lite ICMP Received Packets",
				"tooltip"	=>	"Number of LSN DS-Lite ICMP Received packets.",
			),
			"lsndsliteicmprxpktsrate" => array (
				"cntrname"	=>	"lsndsliteicmprxpktsrate",
				"cntrdescr"	=>	"",
			),
			"lsntotdsliteicmprxbytes" => Array(
				"cntrname"	=>	"lsntotdsliteicmprxbytes",
				"cntrdescr"	=>	"DS-Lite ICMP Received Bytes",
				"tooltip"	=>	"Number of LSN DS-Lite ICMP Received bytes.",
			),
			"lsndsliteicmprxbytesrate" => array (
				"cntrname"	=>	"lsndsliteicmprxbytesrate",
				"cntrdescr"	=>	"",
			),
			"lsntotdsliteicmptxpkts" => Array(
				"cntrname"	=>	"lsntotdsliteicmptxpkts",
				"cntrdescr"	=>	"DS-Lite ICMP Transmitted Packets",
				"tooltip"	=>	"Number of LSN DS-Lite ICMP Transmitted packets.",
			),
			"lsndsliteicmptxpktsrate" => array (
				"cntrname"	=>	"lsndsliteicmptxpktsrate",
				"cntrdescr"	=>	"",
			),
			"lsntotdsliteicmptxbytes" => Array(
				"cntrname"	=>	"lsntotdsliteicmptxbytes",
				"cntrdescr"	=>	"DS-Lite ICMP Transmitted Bytes",
				"tooltip"	=>	"Number of LSN DS-Lite ICMP Transmitted bytes.",
			),
			"lsndsliteicmptxbytesrate" => array (
				"cntrname"	=>	"lsndsliteicmptxbytesrate",
				"cntrdescr"	=>	"",
			),
			"lsntotdsliteicmpdrppkts" => Array(
				"cntrname"	=>	"lsntotdsliteicmpdrppkts",
				"cntrdescr"	=>	"DS-Lite ICMP Dropped Packets",
				"tooltip"	=>	"Number of LSN DS-Lite ICMP Dropped packets.",
			),
			"lsndsliteicmpdrppktsrate" => array (
				"cntrname"	=>	"lsndsliteicmpdrppktsrate",
				"cntrdescr"	=>	"",
			),
			"lsncurdsliteicmpsessions" => Array(
				"cntrname"	=>	"lsncurdsliteicmpsessions",
				"cntrdescr"	=>	"DS-Lite ICMP Current Sessions",
				"tooltip"	=>	"Number of LSN DS-Lite ICMP Current Sessions.",
			),
			"lsncurdsliteicmpsessionsrate" => array (
				"cntrname"	=>	"lsncurdsliteicmpsessionsrate",
				"cntrdescr"	=>	"",
			),
			"lsncurdslitesessions" => Array(
				"cntrname"	=>	"lsncurdslitesessions",
				"cntrdescr"	=>	"DS-Lite Sessions",
				"tooltip"	=>	"Current number of LSN DS-Lite sessions.",
			),
			"lsncurdslitesessionsrate" => array (
				"cntrname"	=>	"lsncurdslitesessionsrate",
				"cntrdescr"	=>	"",
			),
			"lsndslitecursubscribers" => Array(
				"cntrname"	=>	"lsndslitecursubscribers",
				"cntrdescr"	=>	"DS-Lite Subscribers",
				"tooltip"	=>	"Current number of LSN DS-Lite subscribers.",
			),
			"lsndslitecursubscribersrate" => array (
				"cntrname"	=>	"lsndslitecursubscribersrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"",
				"header"	=>	array("","Rate(/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("lsntotdsliterxpkts","lsntotdsliterxbytes","lsntotdslitetxpkts","lsntotdslitetxbytes","lsntotdslitetcprxpkts","lsntotdslitetcprxbytes","lsntotdslitetcptxpkts","lsntotdslitetcptxbytes","lsntotdslitetcpdrppkts","lsncurdslitetcpsessions","lsntotdsliteudprxpkts","lsntotdsliteudprxbytes","lsntotdsliteudptxpkts","lsntotdsliteudptxbytes","lsntotdsliteudpdrppkts","lsncurdsliteudpsessions","lsntotdsliteicmprxpkts","lsntotdsliteicmprxbytes","lsntotdsliteicmptxpkts","lsntotdsliteicmptxbytes","lsntotdsliteicmpdrppkts","lsncurdsliteicmpsessions","lsncurdslitesessions","lsndslitecursubscribers",),
			),
		),
),


"statlsnnat64" => Array(
	"cmddispname"		=>	"statlsnnat64",
	"cmdname"		=>	"statlsnnat64",
	"license"		=>	Array("NSKF2_LSN_BIT_POS","NSKF2_LSN_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"lsncurnat64sessions" => Array(
				"cntrname"	=>	"lsncurnat64sessions",
				"cntrdescr"	=>	"LSN NAT64 Sessions",
				"tooltip"	=>	"Current number of LSN NAT64 sessions.",
			),
			"lsncurnat64sessionsrate" => array (
				"cntrname"	=>	"lsncurnat64sessionsrate",
				"cntrdescr"	=>	"",
			),
			"lsnnat64cursubscribers" => Array(
				"cntrname"	=>	"lsnnat64cursubscribers",
				"cntrdescr"	=>	"LSN NAT64 Subscribers",
				"tooltip"	=>	"Current number of LSN NAT64 subscribers.",
			),
			"lsnnat64cursubscribersrate" => array (
				"cntrname"	=>	"lsnnat64cursubscribersrate",
				"cntrdescr"	=>	"",
			),
			"lsntotnat64rxpkts" => Array(
				"cntrname"	=>	"lsntotnat64rxpkts",
				"cntrdescr"	=>	"LSN NAT64 Received Packets",
				"tooltip"	=>	"Total number of LSN NAT64 rx pkts.",
			),
			"lsnnat64rxpktsrate" => array (
				"cntrname"	=>	"lsnnat64rxpktsrate",
				"cntrdescr"	=>	"",
			),
			"lsntotnat64rxbytes" => Array(
				"cntrname"	=>	"lsntotnat64rxbytes",
				"cntrdescr"	=>	"LSN NAT64 Received Bytes",
				"tooltip"	=>	"Total number of LSN NAT64 rx bytes.",
			),
			"lsnnat64rxbytesrate" => array (
				"cntrname"	=>	"lsnnat64rxbytesrate",
				"cntrdescr"	=>	"",
			),
			"lsntotnat64txpkts" => Array(
				"cntrname"	=>	"lsntotnat64txpkts",
				"cntrdescr"	=>	"LSN NAT64 Transmitted Packets",
				"tooltip"	=>	"Total number of LSN NAT64 tx pkts.",
			),
			"lsnnat64txpktsrate" => array (
				"cntrname"	=>	"lsnnat64txpktsrate",
				"cntrdescr"	=>	"",
			),
			"lsntotnat64txbytes" => Array(
				"cntrname"	=>	"lsntotnat64txbytes",
				"cntrdescr"	=>	"LSN NAT64 Transmitted Bytes",
				"tooltip"	=>	"Total number of LSN NAT64 tx bytes.",
			),
			"lsnnat64txbytesrate" => array (
				"cntrname"	=>	"lsnnat64txbytesrate",
				"cntrdescr"	=>	"",
			),
			"lsncurnat64tcpsessions" => Array(
				"cntrname"	=>	"lsncurnat64tcpsessions",
				"cntrdescr"	=>	"LSN NAT64 TCP Current Sessions",
				"tooltip"	=>	"Number of LSN NAT64 TCP Current Sessions.",
			),
			"lsncurnat64tcpsessionsrate" => array (
				"cntrname"	=>	"lsncurnat64tcpsessionsrate",
				"cntrdescr"	=>	"",
			),
			"lsntotnat64tcprxpkts" => Array(
				"cntrname"	=>	"lsntotnat64tcprxpkts",
				"cntrdescr"	=>	"LSN NAT64 TCP Received Packets",
				"tooltip"	=>	"Number of LSN NAT64 TCP Received packets.",
			),
			"lsnnat64tcprxpktsrate" => array (
				"cntrname"	=>	"lsnnat64tcprxpktsrate",
				"cntrdescr"	=>	"",
			),
			"lsntotnat64tcprxbytes" => Array(
				"cntrname"	=>	"lsntotnat64tcprxbytes",
				"cntrdescr"	=>	"LSN NAT64 TCP Received Bytes",
				"tooltip"	=>	"Number of LSN NAT64 TCP Received bytes.",
			),
			"lsnnat64tcprxbytesrate" => array (
				"cntrname"	=>	"lsnnat64tcprxbytesrate",
				"cntrdescr"	=>	"",
			),
			"lsntotnat64tcptxpkts" => Array(
				"cntrname"	=>	"lsntotnat64tcptxpkts",
				"cntrdescr"	=>	"LSN NAT64 TCP Transmitted Packets",
				"tooltip"	=>	"Number of LSN NAT64 TCP Transmitted packets.",
			),
			"lsnnat64tcptxpktsrate" => array (
				"cntrname"	=>	"lsnnat64tcptxpktsrate",
				"cntrdescr"	=>	"",
			),
			"lsntotnat64tcptxbytes" => Array(
				"cntrname"	=>	"lsntotnat64tcptxbytes",
				"cntrdescr"	=>	"LSN NAT64 TCP Transmitted Bytes",
				"tooltip"	=>	"Number of LSN NAT64 TCP Transmitted bytes.",
			),
			"lsnnat64tcptxbytesrate" => array (
				"cntrname"	=>	"lsnnat64tcptxbytesrate",
				"cntrdescr"	=>	"",
			),
			"lsntotnat64tcpdrppkts" => Array(
				"cntrname"	=>	"lsntotnat64tcpdrppkts",
				"cntrdescr"	=>	"LSN NAT64 TCP Dropped Packets",
				"tooltip"	=>	"Number of LSN NAT64 TCP Dropped packets.",
			),
			"lsnnat64tcpdrppktsrate" => array (
				"cntrname"	=>	"lsnnat64tcpdrppktsrate",
				"cntrdescr"	=>	"",
			),
			"lsncurnat64udpsessions" => Array(
				"cntrname"	=>	"lsncurnat64udpsessions",
				"cntrdescr"	=>	"LSN NAT64 UDP Current Sessions",
				"tooltip"	=>	"Number of LSN NAT64 UDP Current Sessions.",
			),
			"lsncurnat64udpsessionsrate" => array (
				"cntrname"	=>	"lsncurnat64udpsessionsrate",
				"cntrdescr"	=>	"",
			),
			"lsntotnat64udprxpkts" => Array(
				"cntrname"	=>	"lsntotnat64udprxpkts",
				"cntrdescr"	=>	"LSN NAT64 UDP Received Packets",
				"tooltip"	=>	"Number of LSN NAT64 UDP Received packets.",
			),
			"lsnnat64udprxpktsrate" => array (
				"cntrname"	=>	"lsnnat64udprxpktsrate",
				"cntrdescr"	=>	"",
			),
			"lsntotnat64udprxbytes" => Array(
				"cntrname"	=>	"lsntotnat64udprxbytes",
				"cntrdescr"	=>	"LSN NAT64 UDP Received Bytes",
				"tooltip"	=>	"Number of LSN NAT64 UDP Received bytes.",
			),
			"lsnnat64udprxbytesrate" => array (
				"cntrname"	=>	"lsnnat64udprxbytesrate",
				"cntrdescr"	=>	"",
			),
			"lsntotnat64udptxpkts" => Array(
				"cntrname"	=>	"lsntotnat64udptxpkts",
				"cntrdescr"	=>	"LSN NAT64 UDP Transmitted Packets",
				"tooltip"	=>	"Number of LSN NAT64 UDP Transmitted packets.",
			),
			"lsnnat64udptxpktsrate" => array (
				"cntrname"	=>	"lsnnat64udptxpktsrate",
				"cntrdescr"	=>	"",
			),
			"lsntotnat64udptxbytes" => Array(
				"cntrname"	=>	"lsntotnat64udptxbytes",
				"cntrdescr"	=>	"LSN NAT64 UDP Transmitted Bytes",
				"tooltip"	=>	"Number of LSN NAT64 UDP Transmitted bytes.",
			),
			"lsnnat64udptxbytesrate" => array (
				"cntrname"	=>	"lsnnat64udptxbytesrate",
				"cntrdescr"	=>	"",
			),
			"lsntotnat64udpdrppkts" => Array(
				"cntrname"	=>	"lsntotnat64udpdrppkts",
				"cntrdescr"	=>	"LSN NAT64 UDP Dropped Packets",
				"tooltip"	=>	"Number of LSN NAT64 UDP Dropped packets.",
			),
			"lsnnat64udpdrppktsrate" => array (
				"cntrname"	=>	"lsnnat64udpdrppktsrate",
				"cntrdescr"	=>	"",
			),
			"lsncurnat64icmpsessions" => Array(
				"cntrname"	=>	"lsncurnat64icmpsessions",
				"cntrdescr"	=>	"LSN NAT64 ICMP Current Sessions",
				"tooltip"	=>	"Number of LSN NAT64 ICMP Current Sessions.",
			),
			"lsncurnat64icmpsessionsrate" => array (
				"cntrname"	=>	"lsncurnat64icmpsessionsrate",
				"cntrdescr"	=>	"",
			),
			"lsntotnat64icmprxpkts" => Array(
				"cntrname"	=>	"lsntotnat64icmprxpkts",
				"cntrdescr"	=>	"LSN NAT64 ICMP Received Packets",
				"tooltip"	=>	"Number of LSN NAT64 ICMP Received packets.",
			),
			"lsnnat64icmprxpktsrate" => array (
				"cntrname"	=>	"lsnnat64icmprxpktsrate",
				"cntrdescr"	=>	"",
			),
			"lsntotnat64icmprxbytes" => Array(
				"cntrname"	=>	"lsntotnat64icmprxbytes",
				"cntrdescr"	=>	"LSN NAT64 ICMP Received Bytes",
				"tooltip"	=>	"Number of LSN NAT64 ICMP Received bytes.",
			),
			"lsnnat64icmprxbytesrate" => array (
				"cntrname"	=>	"lsnnat64icmprxbytesrate",
				"cntrdescr"	=>	"",
			),
			"lsntotnat64icmptxpkts" => Array(
				"cntrname"	=>	"lsntotnat64icmptxpkts",
				"cntrdescr"	=>	"LSN NAT64 ICMP Transmitted Packets",
				"tooltip"	=>	"Number of LSN NAT64 ICMP Transmitted packets.",
			),
			"lsnnat64icmptxpktsrate" => array (
				"cntrname"	=>	"lsnnat64icmptxpktsrate",
				"cntrdescr"	=>	"",
			),
			"lsntotnat64icmptxbytes" => Array(
				"cntrname"	=>	"lsntotnat64icmptxbytes",
				"cntrdescr"	=>	"LSN NAT64 ICMP Transmitted Bytes",
				"tooltip"	=>	"Number of LSN NAT64 ICMP Transmitted bytes.",
			),
			"lsnnat64icmptxbytesrate" => array (
				"cntrname"	=>	"lsnnat64icmptxbytesrate",
				"cntrdescr"	=>	"",
			),
			"lsntotnat64icmpdrppkts" => Array(
				"cntrname"	=>	"lsntotnat64icmpdrppkts",
				"cntrdescr"	=>	"LSN NAT64 ICMP Dropped Packets",
				"tooltip"	=>	"Number of LSN NAT64 ICMP Dropped packets.",
			),
			"lsnnat64icmpdrppktsrate" => array (
				"cntrname"	=>	"lsnnat64icmpdrppktsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"",
				"header"	=>	array("","Rate(/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("lsncurnat64sessions","lsnnat64cursubscribers","lsntotnat64rxpkts","lsntotnat64rxbytes","lsntotnat64txpkts","lsntotnat64txbytes","lsncurnat64tcpsessions","lsntotnat64tcprxpkts","lsntotnat64tcprxbytes","lsntotnat64tcptxpkts","lsntotnat64tcptxbytes","lsntotnat64tcpdrppkts","lsncurnat64udpsessions","lsntotnat64udprxpkts","lsntotnat64udprxbytes","lsntotnat64udptxpkts","lsntotnat64udptxbytes","lsntotnat64udpdrppkts","lsncurnat64icmpsessions","lsntotnat64icmprxpkts","lsntotnat64icmprxbytes","lsntotnat64icmptxpkts","lsntotnat64icmptxbytes","lsntotnat64icmpdrppkts",),
			),
		),
),


"statvlan" => Array(
	"cmddispname"		=>	"statvlan",
	"cmdname"		=>	"statvlan",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"id" => Array(
				"argname"		=>	"id",
				"valtype"		=>	"NS_VALUINT",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "id",
	"nameargtype"	=> "NS_VALUINT",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"vlantotrxpkts" => Array(
				"cntrname"	=>	"vlantotrxpkts",
				"cntrdescr"	=>	"Packets received",
				"tooltip"	=>	"Packets received on the VLAN.",
			),
			"vlanrxpktsrate" => array (
				"cntrname"	=>	"vlanrxpktsrate",
				"cntrdescr"	=>	"",
			),
			"vlantotrxbytes" => Array(
				"cntrname"	=>	"vlantotrxbytes",
				"cntrdescr"	=>	"Bytes received",
				"tooltip"	=>	"Bytes of data received on the VLAN.",
			),
			"vlanrxbytesrate" => array (
				"cntrname"	=>	"vlanrxbytesrate",
				"cntrdescr"	=>	"",
			),
			"vlantottxpkts" => Array(
				"cntrname"	=>	"vlantottxpkts",
				"cntrdescr"	=>	"Packets sent",
				"tooltip"	=>	"Packets transmitted on the VLAN.",
			),
			"vlantxpktsrate" => array (
				"cntrname"	=>	"vlantxpktsrate",
				"cntrdescr"	=>	"",
			),
			"vlantottxbytes" => Array(
				"cntrname"	=>	"vlantottxbytes",
				"cntrdescr"	=>	"Bytes sent",
				"tooltip"	=>	"Bytes of data transmitted on the VLAN.",
			),
			"vlantxbytesrate" => array (
				"cntrname"	=>	"vlantxbytesrate",
				"cntrdescr"	=>	"",
			),
			"vlantotdroppedpkts" => Array(
				"cntrname"	=>	"vlantotdroppedpkts",
				"cntrdescr"	=>	"Packets dropped",
				"tooltip"	=>	"Inbound packets dropped by the VLAN upon reception.",
			),
			"vlantotbroadcastpkts" => Array(
				"cntrname"	=>	"vlantotbroadcastpkts",
				"cntrdescr"	=>	"Broadcast pkts sent & received",
				"tooltip"	=>	"Broadcast packets sent and received on the VLAN.",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("vlantotrxpkts","vlantotrxbytes","vlantottxpkts","vlantottxbytes","vlantotdroppedpkts","vlantotbroadcastpkts",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"VLAN Summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("vlantotrxpkts","vlantotrxbytes","vlantottxpkts","vlantottxbytes","vlantotdroppedpkts","vlantotbroadcastpkts",),
			),
		),
),


"statinat" => Array(
	"cmddispname"		=>	"statinat",
	"cmdname"		=>	"statinat",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"nat46tottcp46" => Array(
				"cntrname"	=>	"nat46tottcp46",
				"cntrdescr"	=>	"TCP Packets translated (V4->V6)",
				"tooltip"	=>	"Total TCP packets translated (V4->v6).",
			),
			"nat46tcp46rate" => array (
				"cntrname"	=>	"nat46tcp46rate",
				"cntrdescr"	=>	"",
			),
			"nat46totudp46" => Array(
				"cntrname"	=>	"nat46totudp46",
				"cntrdescr"	=>	"UDP Packets translated (V4->V6)",
				"tooltip"	=>	"Total UDP packets translated (V4->v6).",
			),
			"nat46udp46rate" => array (
				"cntrname"	=>	"nat46udp46rate",
				"cntrdescr"	=>	"",
			),
			"nat46toticmp46" => Array(
				"cntrname"	=>	"nat46toticmp46",
				"cntrdescr"	=>	"ICMP Packets translated (V4->V6)",
				"tooltip"	=>	"Total ICMP packets translated (V4->v6).",
			),
			"nat46icmp46rate" => array (
				"cntrname"	=>	"nat46icmp46rate",
				"cntrdescr"	=>	"",
			),
			"nat46totdrop46" => Array(
				"cntrname"	=>	"nat46totdrop46",
				"cntrdescr"	=>	"Total IPV4 packets dropped",
				"tooltip"	=>	"Total IPV4 packets dropped.",
			),
			"nat46drop46rate" => array (
				"cntrname"	=>	"nat46drop46rate",
				"cntrdescr"	=>	"",
			),
			"nat46tottcp64" => Array(
				"cntrname"	=>	"nat46tottcp64",
				"cntrdescr"	=>	"TCP Packets translated (V6->V4)",
				"tooltip"	=>	"Total TCP packets translated (V6->v4).",
			),
			"nat46tcp64rate" => array (
				"cntrname"	=>	"nat46tcp64rate",
				"cntrdescr"	=>	"",
			),
			"nat46totudp64" => Array(
				"cntrname"	=>	"nat46totudp64",
				"cntrdescr"	=>	"UDP Packets translated (V6->V4)",
				"tooltip"	=>	"Total UDP packets translated (V6->v4).",
			),
			"nat46udp64rate" => array (
				"cntrname"	=>	"nat46udp64rate",
				"cntrdescr"	=>	"",
			),
			"nat46toticmp64" => Array(
				"cntrname"	=>	"nat46toticmp64",
				"cntrdescr"	=>	"ICMP Packets translated (V6->V4)",
				"tooltip"	=>	"Total ICMP packets translated (V6->v4).",
			),
			"nat46icmp64rate" => array (
				"cntrname"	=>	"nat46icmp64rate",
				"cntrdescr"	=>	"",
			),
			"nat46totdrop64" => Array(
				"cntrname"	=>	"nat46totdrop64",
				"cntrdescr"	=>	"Total IPV6 packets dropped",
				"tooltip"	=>	"Total IPV6 packets dropped.",
			),
			"nat46drop64rate" => array (
				"cntrname"	=>	"nat46drop64rate",
				"cntrdescr"	=>	"",
			),
			"inatnat46tcp46" => Array(
				"cntrname"	=>	"inatnat46tcp46",
				"cntrdescr"	=>	"TCP Packets translated (V4->V6)",
				"tooltip"	=>	"TCP packets translated (V4->v6).",
			),
			"inatnat46tcp46rate" => array (
				"cntrname"	=>	"inatnat46tcp46rate",
				"cntrdescr"	=>	"",
			),
			"inatnat46udp46" => Array(
				"cntrname"	=>	"inatnat46udp46",
				"cntrdescr"	=>	"UDP Packets translated (V4->V6)",
				"tooltip"	=>	"UDP packets translated (V4->v6).",
			),
			"inatnat46udp46rate" => array (
				"cntrname"	=>	"inatnat46udp46rate",
				"cntrdescr"	=>	"",
			),
			"inatnat46icmp46" => Array(
				"cntrname"	=>	"inatnat46icmp46",
				"cntrdescr"	=>	"ICMP Packets translated (V4->V6)",
				"tooltip"	=>	"ICMP packets translated (V4->v6).",
			),
			"inatnat46icmp46rate" => array (
				"cntrname"	=>	"inatnat46icmp46rate",
				"cntrdescr"	=>	"",
			),
			"inatnat46drop46" => Array(
				"cntrname"	=>	"inatnat46drop46",
				"cntrdescr"	=>	"IPV4 packets dropped",
				"tooltip"	=>	"IPV4 packets dropped.",
			),
			"inatnat46drop46rate" => array (
				"cntrname"	=>	"inatnat46drop46rate",
				"cntrdescr"	=>	"",
			),
			"inatnat46tcp64" => Array(
				"cntrname"	=>	"inatnat46tcp64",
				"cntrdescr"	=>	"TCP Packets translated (V6->V4)",
				"tooltip"	=>	"TCP packets translated (V6->v4).",
			),
			"inatnat46tcp64rate" => array (
				"cntrname"	=>	"inatnat46tcp64rate",
				"cntrdescr"	=>	"",
			),
			"inatnat46udp64" => Array(
				"cntrname"	=>	"inatnat46udp64",
				"cntrdescr"	=>	"UDP Packets translated (V6->V4)",
				"tooltip"	=>	"UDP packets translated (V6->v4).",
			),
			"inatnat46udp64rate" => array (
				"cntrname"	=>	"inatnat46udp64rate",
				"cntrdescr"	=>	"",
			),
			"inatnat46icmp64" => Array(
				"cntrname"	=>	"inatnat46icmp64",
				"cntrdescr"	=>	"ICMP Packets translated (V6->V4)",
				"tooltip"	=>	"ICMP packets translated (V6->v4).",
			),
			"inatnat46icmp64rate" => array (
				"cntrname"	=>	"inatnat46icmp64rate",
				"cntrdescr"	=>	"",
			),
			"inatnat46drop64" => Array(
				"cntrname"	=>	"inatnat46drop64",
				"cntrdescr"	=>	"IPV6 packets dropped",
				"tooltip"	=>	"IPV6 packets dropped.",
			),
			"inatnat46drop64rate" => array (
				"cntrname"	=>	"inatnat46drop64rate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("inatnat46tcp46","inatnat46udp46","inatnat46icmp46","inatnat46drop46","inatnat46tcp64","inatnat46udp64","inatnat46icmp64","inatnat46drop64",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Stateless NAT46 statistics",
				"header"	=>	array(" ","  Rate (/s)","  Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"singleton"	=>	1,
				"dispflag"	=>	"Summary",
				"counters"	=>	array("nat46tottcp46","nat46totudp46","nat46toticmp46","nat46totdrop46","nat46tottcp64","nat46totudp64","nat46toticmp64","nat46totdrop64",),
			),
		),
),


"statinatsession" => Array(
	"cmddispname"		=>	"statinatsession",
	"cmdname"		=>	"statinatsession",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"inattothits" => Array(
				"cntrname"	=>	"inattothits",
				"cntrdescr"	=>	"INAT total sessions",
				"tooltip"	=>	"INAT total sessions",
			),
			"inathitsrate" => array (
				"cntrname"	=>	"inathitsrate",
				"cntrdescr"	=>	"",
			),
			"inatcursessions" => Array(
				"cntrname"	=>	"inatcursessions",
				"cntrdescr"	=>	"INAT Current sessions",
				"tooltip"	=>	"INAT current sessions",
			),
			"inatcursessionsrate" => array (
				"cntrname"	=>	"inatcursessionsrate",
				"cntrdescr"	=>	"",
			),
			"inattotreceivebytes" => Array(
				"cntrname"	=>	"inattotreceivebytes",
				"cntrdescr"	=>	"INAT total Received Bytes",
				"tooltip"	=>	"INAT total Received Bytes",
			),
			"inatreceivebytesrate" => array (
				"cntrname"	=>	"inatreceivebytesrate",
				"cntrdescr"	=>	"",
			),
			"inattotsentbytes" => Array(
				"cntrname"	=>	"inattotsentbytes",
				"cntrdescr"	=>	"INAT total Sent Bytes",
				"tooltip"	=>	"INAT total Sent Bytes",
			),
			"inatsentbytesrate" => array (
				"cntrname"	=>	"inatsentbytesrate",
				"cntrdescr"	=>	"",
			),
			"inattotpktreceived" => Array(
				"cntrname"	=>	"inattotpktreceived",
				"cntrdescr"	=>	"INAT total Packets Received",
				"tooltip"	=>	"INAT total Packets Received",
			),
			"inatpktreceivedrate" => array (
				"cntrname"	=>	"inatpktreceivedrate",
				"cntrdescr"	=>	"",
			),
			"inattotpktsent" => Array(
				"cntrname"	=>	"inattotpktsent",
				"cntrdescr"	=>	"INAT total Packets Sent",
				"tooltip"	=>	"INAT total Packets Sent",
			),
			"inatpktsentrate" => array (
				"cntrname"	=>	"inatpktsentrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("inattothits","inatcursessions","inattotreceivebytes","inattotsentbytes","inattotpktreceived","inattotpktsent",),
			),
		),
),


"statinterface" => Array(
	"cmddispname"		=>	"statinterface",
	"enable"            =>      "1",
	"cmdname"		=>	"statinterface",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"id" => Array(
				"argname"		=>	"id",
				"valtype"		=>	"NS_VALIFN",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "id",
	"nameargtype"	=> "NS_VALIFN",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"curintfstate" => Array(
				"cntrname"	=>	"curintfstate",
				"cntrdescr"	=>	"Interface State",
				"tooltip"	=>	"Current state of the specified interface.  The interface state set to UP only if the link state is UP and administrative state is ENABLED .",
				"type"		=>	"NS_VALENUM",
			),
			"curlinkuptime" => Array(
				"cntrname"	=>	"curlinkuptime",
				"cntrdescr"	=>	"Link uptime",
				"tooltip"	=>	"Duration for which the link is UP. This statistic is reset when the state changes to DOWN.",
				"type"		=>	"NS_VALTIME",
			),
			"curlinkdowntime" => Array(
				"cntrname"	=>	"curlinkdowntime",
				"cntrdescr"	=>	"Link downtime",
				"tooltip"	=>	"Duration for which the link is DOWN. This statistic is reset when the state changes to UP.",
				"type"		=>	"NS_VALTIME",
			),
			"totrxbytes" => Array(
				"cntrname"	=>	"totrxbytes",
				"cntrdescr"	=>	"Bytes received",
				"tooltip"	=>	"Number of bytes received by an interface since the NetScaler appliance was started or the interface statistics were cleared.",
			),
			"rxbytesrate" => array (
				"cntrname"	=>	"rxbytesrate",
				"cntrdescr"	=>	"",
			),
			"tottxbytes" => Array(
				"cntrname"	=>	"tottxbytes",
				"cntrdescr"	=>	"Bytes transmitted",
				"tooltip"	=>	"Number of bytes transmitted by an interface since the NetScaler appliance was started or the interface statistics were cleared.",
			),
			"txbytesrate" => array (
				"cntrname"	=>	"txbytesrate",
				"cntrdescr"	=>	"",
			),
			"totrxpkts" => Array(
				"cntrname"	=>	"totrxpkts",
				"cntrdescr"	=>	"Packets received",
				"tooltip"	=>	"Number of packets received by an interface since the NetScaler appliance was started or the interface statistics were cleared.",
			),
			"rxpktsrate" => array (
				"cntrname"	=>	"rxpktsrate",
				"cntrdescr"	=>	"",
			),
			"tottxpkts" => Array(
				"cntrname"	=>	"tottxpkts",
				"cntrdescr"	=>	"Packets transmitted",
				"tooltip"	=>	"Number of packets transmitted by an interface since the NetScaler appliance was started or the interface statistics were cleared.",
			),
			"txpktsrate" => array (
				"cntrname"	=>	"txpktsrate",
				"cntrdescr"	=>	"",
			),
			"jumbopktsreceived" => Array(
				"cntrname"	=>	"jumbopktsreceived",
				"cntrdescr"	=>	"Jumbo Packets Received",
				"tooltip"	=>	"Number of Jumbo Packets received on this interface.",
			),
			"jumbopktsreceivedrate" => array (
				"cntrname"	=>	"jumbopktsreceivedrate",
				"cntrdescr"	=>	"",
			),
			"jumbopktstransmitted" => Array(
				"cntrname"	=>	"jumbopktstransmitted",
				"cntrdescr"	=>	"Jumbo Packets Transmitted",
				"tooltip"	=>	"Number of Jumbo packets transmitted on this interface by upper layer, with TSO enabled actual trasmission size could be non Jumbo.",
			),
			"jumbopktstransmittedrate" => array (
				"cntrname"	=>	"jumbopktstransmittedrate",
				"cntrdescr"	=>	"",
			),
			"trunkpktsreceived" => Array(
				"cntrname"	=>	"trunkpktsreceived",
				"cntrdescr"	=>	"Tagged Packets Received on Trunk interface through allowed vlan list",
				"tooltip"	=>	"Number of Tagged Packets received on this Trunk interface through Allowed VLan List.",
			),
			"trunkpktsreceivedrate" => array (
				"cntrname"	=>	"trunkpktsreceivedrate",
				"cntrdescr"	=>	"",
			),
			"trunkpktstransmitted" => Array(
				"cntrname"	=>	"trunkpktstransmitted",
				"cntrdescr"	=>	"Tagged Packets transmitted on Trunk interface through allowed vlan list",
				"tooltip"	=>	"Number of Tagged Packets transmitted on this Trunk interface through Allowed VLan List.",
			),
			"trunkpktstransmittedrate" => array (
				"cntrname"	=>	"trunkpktstransmittedrate",
				"cntrdescr"	=>	"",
			),
			"nictotmulticastpkts" => Array(
				"cntrname"	=>	"nictotmulticastpkts",
				"cntrdescr"	=>	"Multicast packets",
				"tooltip"	=>	"Number of multicast packets received by the specified interface since the NetScaler appliance was started or the interface statistics were cleared.",
			),
			"nicmulticastpktsrate" => array (
				"cntrname"	=>	"nicmulticastpktsrate",
				"cntrdescr"	=>	"",
			),
			"totnetscalerpkts" => Array(
				"cntrname"	=>	"totnetscalerpkts",
				"cntrdescr"	=>	"NetScaler packets",
				"tooltip"	=>	"Number of packets, destined to the NetScaler, received by an interface since the NetScaler appliance was started or the interface statistics were cleared. The packets destined to NetScaler are those that have the same MAC address as that of an interface or a VMAC address owned by the NetScaler.",
			),
			"netscalerpktsrate" => array (
				"cntrname"	=>	"netscalerpktsrate",
				"cntrdescr"	=>	"",
			),
			"rxlacpdu" => Array(
				"cntrname"	=>	"rxlacpdu",
				"cntrdescr"	=>	"LACPDUs received",
				"tooltip"	=>	"Number of Link Aggregation Control Protocol Data Units(LACPDUs) received by the specified interface since the NetScaler appliance was started or the interface statistics were cleared.",
			),
			"rxlacpdurate" => array (
				"cntrname"	=>	"rxlacpdurate",
				"cntrdescr"	=>	"",
			),
			"txlacpdu" => Array(
				"cntrname"	=>	"txlacpdu",
				"cntrdescr"	=>	"LACPDUs transmitted",
				"tooltip"	=>	"Number of Link Aggregation Control Protocol Data Units(LACPDUs) transmitted by the specified interface since the NetScaler appliance was started or the interface statistics were cleared.",
			),
			"txlacpdurate" => array (
				"cntrname"	=>	"txlacpdurate",
				"cntrdescr"	=>	"",
			),
			"errpktrx" => Array(
				"cntrname"	=>	"errpktrx",
				"cntrdescr"	=>	"Error packets received (hw)",
				"tooltip"	=>	"Number of inbound packets dropped by the hardware on a specified interface once the NetScaler appliance starts or the interface statistics are cleared. This happens due to following reasons:<br><br>			1)	The hardware receives packets at a rate higher rate than that at which the software is processing packets. In this case, the hardware FIFO overruns and starts dropping the packets .<br><br>			2)	The specified interface fails to receive inbound packets from the appliance because of insufficient memory.<br><br>			3)	The specified interface receives packets with CRC errors (Alignment or Frame Check Sequence).<br><br>			4)	The specified interface receives overly long packets.<br><br>			5)	The specified interface receives packets with alignment errors.<br><br>			6)	The software does less buffering because it is running out of available memory. When hardware detects that there is no space into which to push newly arrived packets, it starts dropping them.<br><br>			7)	The specified interface receives packets with Frame Check Sequence (FCS) errors.<br><br>			8)	The specified interface receives packets smaller than 64 bytes.<br><br>			9)	The specified interface discards error-free inbound packets because of insufficient resources. For example: NIC buffers.<br><br>			10)	Packets are missed because of collision detection, link lost, physical decoding error, or MAC abort.",
			),
			"errpktrxrate" => array (
				"cntrname"	=>	"errpktrxrate",
				"cntrdescr"	=>	"",
			),
			"errpkttx" => Array(
				"cntrname"	=>	"errpkttx",
				"cntrdescr"	=>	"Error packets transmitted (hw)",
				"tooltip"	=>	"Number of outbound packets dropped by the hardware on a specified interface since the NetScaler appliance was started or the interface statistics were cleared. This could happen due to length (undersize or oversize) errors and lack of resources. This statistic is available only for: <br><br>			(1) Loop back interface (LO) of all platforms.<br><br>			(2) All data ports on the NetScaler 12000 platform.<br><br>			(3) Management ports on the MPX 15000 and 17000 platforms.",
			),
			"errpkttxrate" => array (
				"cntrname"	=>	"errpkttxrate",
				"cntrdescr"	=>	"",
			),
			"errifindiscards" => Array(
				"cntrname"	=>	"errifindiscards",
				"cntrdescr"	=>	"Inbound packets discarded(hw)",
				"tooltip"	=>	"Number of error-free inbound packets discarded by the specified interface due to a lack of resources, for example, insufficient receive buffers.",
			),
			"errifindiscardsrate" => array (
				"cntrname"	=>	"errifindiscardsrate",
				"cntrdescr"	=>	"",
			),
			"nicerrifoutdiscards" => Array(
				"cntrname"	=>	"nicerrifoutdiscards",
				"cntrdescr"	=>	"Outbound packets discarded(hw)",
				"tooltip"	=>	"Number of error-free outbound packets discarded by the specified interface due to a lack of resources. This statistic is not available on:<br><br>			(1) 10G ports of NetScaler MPX 12500/12500/15500-10G  platforms. <br><br>			(2) 10G data ports on NetScaler MPX 17500/19500/21500 platforms.",
			),
			"nicerrifoutdiscardsrate" => array (
				"cntrname"	=>	"nicerrifoutdiscardsrate",
				"cntrdescr"	=>	"",
			),
			"errdroppedrxpkts" => Array(
				"cntrname"	=>	"errdroppedrxpkts",
				"cntrdescr"	=>	"Packets dropped in Rx (sw)",
				"tooltip"	=>	"Number of inbound packets dropped by the specified interface. Commonly dropped packets are multicast frames, spanning tree BPDUs, packets destined to a MAC not owned by the NetScaler when L2 mode is disabled, or packets tagged for a VLAN that is not bound to the interface.  This statistic will increment in most healthy networks at a steady rate regardless of traffic load.  If a sharp spike in dropped packets occurs, it generally indicates an issue with connected L2 switches, such as a forwarding database overflow resulting in packets being broadcast on all ports.",
			),
			"errdroppedrxpktsrate" => array (
				"cntrname"	=>	"errdroppedrxpktsrate",
				"cntrdescr"	=>	"",
			),
			"errdroppedtxpkts" => Array(
				"cntrname"	=>	"errdroppedtxpkts",
				"cntrdescr"	=>	"Packets dropped in Tx (sw)",
				"tooltip"	=>	"Number of packets dropped in transmission by the specified interface due to one of the following reasons.<br><br>			(1) VLAN mismatch.<br><br>			(2) Oversized packets.<br><br>			(3) Interface congestion.  <br><br>			(4) Loopback packets sent on non loop back interface.",
			),
			"errdroppedtxpktsrate" => array (
				"cntrname"	=>	"errdroppedtxpktsrate",
				"cntrdescr"	=>	"",
			),
			"errlinkhangs" => Array(
				"cntrname"	=>	"errlinkhangs",
				"cntrdescr"	=>	"NIC hangs",
				"tooltip"	=>	"Number of times the specified interface detected hangs in the transmit and receive paths since the NetScaler appliance was started or the interface statistics were cleared.",
			),
			"nicstsstalls" => Array(
				"cntrname"	=>	"nicstsstalls",
				"cntrdescr"	=>	"Status stalls",
				"tooltip"	=>	"Number of times the status updates for a specified interface were stalled since the NetScaler appliance was started or the interface statistics were cleared. A status stall is detected when the status of the interface is not updated by the NIC hardware within 0.8 seconds of the last update.",
			),
			"nictxstalls" => Array(
				"cntrname"	=>	"nictxstalls",
				"cntrdescr"	=>	"Transmit stalls",
				"tooltip"	=>	"Number of times the interface stalled, when transmitting packets, since the NetScaler appliance was started or the interface statistics were cleared. Transmit (Tx) stalls are detected when a packet posted for transmission is not transmitted in 4 seconds.",
			),
			"nicrxstalls" => Array(
				"cntrname"	=>	"nicrxstalls",
				"cntrdescr"	=>	"Receive stalls",
				"tooltip"	=>	"Number of times the interface stalled, when receiving packets, since the NetScaler appliance was started or the interface statistics were cleared. Receive (Rx) stalls are detected when the following conditions are met:<br><br>			(1)The link is up for more than 10 minutes. <br><br>			(2)Packets are transmitted, but no packets is received for 16 seconds.",
			),
			"nicerrdisables" => Array(
				"cntrname"	=>	"nicerrdisables",
				"cntrdescr"	=>	"Error-disables",
				"tooltip"	=>	"Number of times the specified interface is disabled by the NetScaler, due to continuous Receive (Rx) or Transmit (Tx) stalls, since the NetScaler appliance was started or the interface statistics were cleared. The NetScaler disables an interface when one of the following conditions is met:<br><br>			(1) Three consecutive transmit stalls occurs with at most gap of 10 seconds between any two stalls.<br><br>			(2) Three consecutive receive stalls occurs with at most gap of 120 seconds between any two stalls.",
			),
			"errduplexmismatch" => Array(
				"cntrname"	=>	"errduplexmismatch",
				"cntrdescr"	=>	"Duplex mismatches",
				"tooltip"	=>	"Number of times duplex mismatches were detected on the specified interface since the NetScaler appliance was started or the interface statistics were cleared. A mismatch will occur if the duplex mode is not identically set on both ends of the link. This statistic is only available on the NetScaler Classic edition.",
			),
			"linkreinits" => Array(
				"cntrname"	=>	"linkreinits",
				"cntrdescr"	=>	"Link re-initializations",
				"tooltip"	=>	"Number of times the link has been re-initialized. A re-initialization occurs due to link state change, configuration parameter change, or administrative reset operation.",
			),
			"totmacmoved" => Array(
				"cntrname"	=>	"totmacmoved",
				"cntrdescr"	=>	"MAC moves registered",
				"tooltip"	=>	"Number of MAC moves between ports. If a high rate of MAC moves is observed, it is likely that there is a bridge loop between two interfaces.",
			),
			"macmovedrate" => array (
				"cntrname"	=>	"macmovedrate",
				"cntrdescr"	=>	"",
			),
			"errnicmuted" => Array(
				"cntrname"	=>	"errnicmuted",
				"cntrdescr"	=>	"Times NIC became muted",
				"tooltip"	=>	"Number of times the specified interface stopped transmitting and receiving packets due to MAC moves between ports.",
			),
			"interfacealias" => Array(
				"cntrname"	=>	"interfacealias",
				"cntrdescr"	=>	"Interface Alias",
				"tooltip"	=>	"Alias Name for the Interface",
			),
			"curlinkstate" => Array(
				"cntrname"	=>	"curlinkstate",
				"cntrdescr"	=>	"Link State",
				"tooltip"	=>	"The current state of the link associated with the interface. For logical interfaces (LA), the state of the link is dependent on the state of the slave interfaces. For the link to be UP at least one of the slave interfaces needs to be UP.",
				"type"		=>	"NS_VALENUM",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"",
				"header"	=>	array(),
				"format"	=>	"1C",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("curintfstate","curlinkuptime","curlinkdowntime",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Throughput Statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("totrxbytes","tottxbytes","totrxpkts","tottxpkts","jumbopktsreceived","jumbopktstransmitted","trunkpktsreceived","trunkpktstransmitted",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Packet Statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("nictotmulticastpkts","totnetscalerpkts",),
			),
			Array(
				"dhID"	=>	4,
				"title"	=>	"LACP Statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("rxlacpdu","txlacpdu",),
			),
			Array(
				"dhID"	=>	5,
				"title"	=>	"Error Statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("errpktrx","errpkttx","errifindiscards","nicerrifoutdiscards","errdroppedrxpkts","errdroppedtxpkts","errlinkhangs","nicstsstalls","nictxstalls","nicrxstalls","nicerrdisables","errduplexmismatch","linkreinits","totmacmoved","errnicmuted",),
			),
			Array(
				"dhID"	=>	6,
				"title"	=>	"Interface Summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("curintfstate","interfacealias","totrxbytes","tottxbytes","totrxpkts","tottxpkts",),
			),
		),
),


"statrnat" => Array(
	"cmddispname"		=>	"statrnat",
	"cmdname"		=>	"statrnat",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"rnattotrxbytes" => Array(
				"cntrname"	=>	"rnattotrxbytes",
				"cntrdescr"	=>	"Bytes Received",
				"tooltip"	=>	"Bytes received during RNAT sessions.",
			),
			"rnatrxbytesrate" => array (
				"cntrname"	=>	"rnatrxbytesrate",
				"cntrdescr"	=>	"",
			),
			"rnattottxbytes" => Array(
				"cntrname"	=>	"rnattottxbytes",
				"cntrdescr"	=>	"Bytes Sent",
				"tooltip"	=>	"Bytes sent during RNAT sessions.",
			),
			"rnattxbytesrate" => array (
				"cntrname"	=>	"rnattxbytesrate",
				"cntrdescr"	=>	"",
			),
			"rnattotrxpkts" => Array(
				"cntrname"	=>	"rnattotrxpkts",
				"cntrdescr"	=>	"Packets Received",
				"tooltip"	=>	"Packets received during RNAT sessions.",
			),
			"rnatrxpktsrate" => array (
				"cntrname"	=>	"rnatrxpktsrate",
				"cntrdescr"	=>	"",
			),
			"rnattottxpkts" => Array(
				"cntrname"	=>	"rnattottxpkts",
				"cntrdescr"	=>	"Packets Sent",
				"tooltip"	=>	"Packets sent during RNAT sessions.",
			),
			"rnattxpktsrate" => array (
				"cntrname"	=>	"rnattxpktsrate",
				"cntrdescr"	=>	"",
			),
			"rnattottxsyn" => Array(
				"cntrname"	=>	"rnattottxsyn",
				"cntrdescr"	=>	"Syn Sent",
				"tooltip"	=>	"Requests for connections sent during RNAT sessions.",
			),
			"rnattxsynrate" => array (
				"cntrname"	=>	"rnattxsynrate",
				"cntrdescr"	=>	"",
			),
			"rnatcursessions" => Array(
				"cntrname"	=>	"rnatcursessions",
				"cntrdescr"	=>	"Current RNAT sessions",
				"tooltip"	=>	"Currently active RNAT sessions.",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"RNAT summary",
				"header"	=>	array(" ","  Rate (/s)","  Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("rnattotrxbytes","rnattottxbytes","rnattotrxpkts","rnattottxpkts","rnattottxsyn","rnatcursessions",),
			),
		),
),


"statbridge" => Array(
	"cmddispname"		=>	"statbridge",
	"cmdname"		=>	"statbridge",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"tcptotbdgmacmoved" => Array(
				"cntrname"	=>	"tcptotbdgmacmoved",
				"cntrdescr"	=>	"Loops",
				"tooltip"	=>	"The number of times bridging registered MAC moved",
			),
			"tcpbdgmacmovedrate" => array (
				"cntrname"	=>	"tcpbdgmacmovedrate",
				"cntrdescr"	=>	"",
			),
			"tcptotbdgcollisions" => Array(
				"cntrname"	=>	"tcptotbdgcollisions",
				"cntrdescr"	=>	"Collisions",
				"tooltip"	=>	"The number of bridging table collisions",
			),
			"tcpbdgcollisionsrate" => array (
				"cntrname"	=>	"tcpbdgcollisionsrate",
				"cntrdescr"	=>	"",
			),
			"tcperrbdgmuted" => Array(
				"cntrname"	=>	"tcperrbdgmuted",
				"cntrdescr"	=>	"Interface muted",
				"tooltip"	=>	"The number of bridging related interface mutes",
			),
			"tcperrbdgmutedrate" => array (
				"cntrname"	=>	"tcperrbdgmutedrate",
				"cntrdescr"	=>	"",
			),
			"totbdgpkts" => Array(
				"cntrname"	=>	"totbdgpkts",
				"cntrdescr"	=>	"Total bridged packets",
				"tooltip"	=>	"The total number of bridged packets",
			),
			"bdgpktsrate" => array (
				"cntrname"	=>	"bdgpktsrate",
				"cntrdescr"	=>	"",
			),
			"totbdgmbits" => Array(
				"cntrname"	=>	"totbdgmbits",
				"cntrdescr"	=>	"Total bridged Mbits",
				"tooltip"	=>	"The total number of bridged Mbits",
			),
			"bdgmbitsrate" => array (
				"cntrname"	=>	"bdgmbitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("tcptotbdgmacmoved","tcptotbdgcollisions","tcperrbdgmuted","totbdgpkts","totbdgmbits",),
			),
		),
),


"statrnatip" => Array(
	"cmddispname"		=>	"statrnatip",
	"cmdname"		=>	"statrnatip",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"Rnatip" => Array(
				"argname"		=>	"Rnatip",
				"valtype"		=>	"NS_VALIPV",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "Rnatip",
	"nameargtype"	=> "NS_VALIPV",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"iptd" => Array(
				"cntrname"	=>	"iptd",
				"cntrdescr"	=>	"Traffic domain",
				"tooltip"	=>	"Traffic domain for ipaddr.",
			),
			"iprnattotrxbytes" => Array(
				"cntrname"	=>	"iprnattotrxbytes",
				"cntrdescr"	=>	"Bytes Received",
				"tooltip"	=>	"Bytes received on this IP address during RNAT sessions.",
			),
			"iprnatrxbytesrate" => array (
				"cntrname"	=>	"iprnatrxbytesrate",
				"cntrdescr"	=>	"",
			),
			"iprnattottxbytes" => Array(
				"cntrname"	=>	"iprnattottxbytes",
				"cntrdescr"	=>	"Bytes Sent",
				"tooltip"	=>	"Bytes sent from this IP address during RNAT sessions.",
			),
			"iprnattxbytesrate" => array (
				"cntrname"	=>	"iprnattxbytesrate",
				"cntrdescr"	=>	"",
			),
			"iprnattotrxpkts" => Array(
				"cntrname"	=>	"iprnattotrxpkts",
				"cntrdescr"	=>	"Packets Received",
				"tooltip"	=>	"Packets received on this IP address during RNAT sessions.",
			),
			"iprnatrxpktsrate" => array (
				"cntrname"	=>	"iprnatrxpktsrate",
				"cntrdescr"	=>	"",
			),
			"iprnattottxpkts" => Array(
				"cntrname"	=>	"iprnattottxpkts",
				"cntrdescr"	=>	"Packets Sent",
				"tooltip"	=>	"Packets sent from this IP address during RNAT sessions.",
			),
			"iprnattxpktsrate" => array (
				"cntrname"	=>	"iprnattxpktsrate",
				"cntrdescr"	=>	"",
			),
			"iprnattottxsyn" => Array(
				"cntrname"	=>	"iprnattottxsyn",
				"cntrdescr"	=>	"Syn Sent",
				"tooltip"	=>	"Requests for connections sent from this IP address during RNAT sessions.",
			),
			"iprnattxsynrate" => array (
				"cntrname"	=>	"iprnattxsynrate",
				"cntrdescr"	=>	"",
			),
			"iprnatcursessions" => Array(
				"cntrname"	=>	"iprnatcursessions",
				"cntrdescr"	=>	"Current RNAT sessions",
				"tooltip"	=>	"Currently active RNAT sessions started from this IP address.",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"",
				"header"	=>	array(" ","  Rate (/s)","  Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("iptd","iprnattotrxbytes","iprnattottxbytes","iprnattotrxpkts","iprnattottxpkts","iprnattottxsyn","iprnatcursessions",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Per RNATIP summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("iptd","iprnattotrxbytes","iprnattottxbytes","iprnattotrxpkts","iprnattottxpkts","iprnattottxsyn","iprnatcursessions",),
			),
		),
),


"statnat64" => Array(
	"cmddispname"		=>	"statnat64",
	"cmdname"		=>	"statnat64",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"nat64tottcpsessions" => Array(
				"cntrname"	=>	"nat64tottcpsessions",
				"cntrdescr"	=>	"TCP Sessions",
				"tooltip"	=>	"Total number of TCP sessions created by NAT64.",
			),
			"nat64tcpsessionsrate" => array (
				"cntrname"	=>	"nat64tcpsessionsrate",
				"cntrdescr"	=>	"",
			),
			"nat64totudpsessions" => Array(
				"cntrname"	=>	"nat64totudpsessions",
				"cntrdescr"	=>	"UDP Sessions",
				"tooltip"	=>	"Total number of UDP sessions created by NAT64.",
			),
			"nat64udpsessionsrate" => array (
				"cntrname"	=>	"nat64udpsessionsrate",
				"cntrdescr"	=>	"",
			),
			"nat64toticmpsessions" => Array(
				"cntrname"	=>	"nat64toticmpsessions",
				"cntrdescr"	=>	"ICMP Sessions",
				"tooltip"	=>	"Total number of ICMP sessions created by NAT64.",
			),
			"nat64icmpsessionsrate" => array (
				"cntrname"	=>	"nat64icmpsessionsrate",
				"cntrdescr"	=>	"",
			),
			"nat64totsessions" => Array(
				"cntrname"	=>	"nat64totsessions",
				"cntrdescr"	=>	"Total Sessions",
				"tooltip"	=>	"Total number of sessions created by NAT64.",
			),
			"nat64sessionsrate" => array (
				"cntrname"	=>	"nat64sessionsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"NAT64 summary",
				"header"	=>	array(" ","  Rate (/s)","  Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"singleton"	=>	1,
				"dispflag"	=>	"Summary",
				"counters"	=>	array("nat64tottcpsessions","nat64totudpsessions","nat64toticmpsessions","nat64totsessions",),
			),
		),
),


"stattunnelip" => Array(
	"cmddispname"		=>	"stattunnelip",
	"cmdname"		=>	"stattunnelip",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"tunnelip" => Array(
				"argname"		=>	"tunnelip",
				"valtype"		=>	"NS_VALIPV",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "tunnelip",
	"nameargtype"	=> "NS_VALIPV",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"tnltotrxpkts" => Array(
				"cntrname"	=>	"tnltotrxpkts",
				"cntrdescr"	=>	"Packets received on tunnel",
				"tooltip"	=>	"Total number of packets received on the tunnel.",
			),
			"tnlrxpktsrate" => array (
				"cntrname"	=>	"tnlrxpktsrate",
				"cntrdescr"	=>	"",
			),
			"tnltottxpkts" => Array(
				"cntrname"	=>	"tnltottxpkts",
				"cntrdescr"	=>	"Packets transmitted on tunnel",
				"tooltip"	=>	"Total number of packets transmitted on the tunnel.",
			),
			"tnltxpktsrate" => array (
				"cntrname"	=>	"tnltxpktsrate",
				"cntrdescr"	=>	"",
			),
			"tnltotrxbytes" => Array(
				"cntrname"	=>	"tnltotrxbytes",
				"cntrdescr"	=>	"Bytes received on tunnel",
				"tooltip"	=>	"Total number of bytes received on the tunnel.",
			),
			"tnlrxbytesrate" => array (
				"cntrname"	=>	"tnlrxbytesrate",
				"cntrdescr"	=>	"",
			),
			"tnltottxbytes" => Array(
				"cntrname"	=>	"tnltottxbytes",
				"cntrdescr"	=>	"Bytes transmitted on tunnel",
				"tooltip"	=>	"Total number of bytes transmitted on the tunnel.",
			),
			"tnltxbytesrate" => array (
				"cntrname"	=>	"tnltxbytesrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"IP Tunnel Stats",
				"header"	=>	array(" "," Rate (/s)"," Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("tnltotrxpkts","tnltottxpkts","tnltotrxbytes","tnltottxbytes",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"IPTUNNEL Summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("tnltotrxpkts","tnltottxpkts","tnltotrxbytes","tnltottxbytes",),
			),
		),
),


"stattunnelip6" => Array(
	"cmddispname"		=>	"stattunnelip6",
	"cmdname"		=>	"stattunnelip6",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"tunnelip6" => Array(
				"argname"		=>	"tunnelip6",
				"valtype"		=>	"NS_VALIPV6",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "tunnelip6",
	"nameargtype"	=> "NS_VALIPV6",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"tnltotrxpkts" => Array(
				"cntrname"	=>	"tnltotrxpkts",
				"cntrdescr"	=>	"Packets received on tunnel",
				"tooltip"	=>	"Total number of packets received on the tunnel.",
			),
			"tnlrxpktsrate" => array (
				"cntrname"	=>	"tnlrxpktsrate",
				"cntrdescr"	=>	"",
			),
			"tnltottxpkts" => Array(
				"cntrname"	=>	"tnltottxpkts",
				"cntrdescr"	=>	"Packets transmitted on tunnel",
				"tooltip"	=>	"Total number of packets transmitted on the tunnel.",
			),
			"tnltxpktsrate" => array (
				"cntrname"	=>	"tnltxpktsrate",
				"cntrdescr"	=>	"",
			),
			"tnltotrxbytes" => Array(
				"cntrname"	=>	"tnltotrxbytes",
				"cntrdescr"	=>	"Bytes received on tunnel",
				"tooltip"	=>	"Total number of bytes received on the tunnel.",
			),
			"tnlrxbytesrate" => array (
				"cntrname"	=>	"tnlrxbytesrate",
				"cntrdescr"	=>	"",
			),
			"tnltottxbytes" => Array(
				"cntrname"	=>	"tnltottxbytes",
				"cntrdescr"	=>	"Bytes transmitted on tunnel",
				"tooltip"	=>	"Total number of bytes transmitted on the tunnel.",
			),
			"tnltxbytesrate" => array (
				"cntrname"	=>	"tnltxbytesrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"IPv6 Tunnel Stats",
				"header"	=>	array(" "," Rate (/s)"," Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("tnltotrxpkts","tnltottxpkts","tnltotrxbytes","tnltottxbytes",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"IP6TUNNEL Summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("tnltotrxpkts","tnltottxpkts","tnltotrxbytes","tnltottxbytes",),
			),
		),
),


"statvxlan" => Array(
	"cmddispname"		=>	"statvxlan",
	"cmdname"		=>	"statvxlan",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"id" => Array(
				"argname"		=>	"id",
				"valtype"		=>	"NS_VALUINT",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "id",
	"nameargtype"	=> "NS_VALUINT",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"vxlantotrxpkts" => Array(
				"cntrname"	=>	"vxlantotrxpkts",
				"cntrdescr"	=>	"Packets received",
				"tooltip"	=>	"Packets received on the VXLAN.",
			),
			"vxlanrxpktsrate" => array (
				"cntrname"	=>	"vxlanrxpktsrate",
				"cntrdescr"	=>	"",
			),
			"vxlantotrxbytes" => Array(
				"cntrname"	=>	"vxlantotrxbytes",
				"cntrdescr"	=>	"Bytes received",
				"tooltip"	=>	"Bytes of data received on the VXLAN.",
			),
			"vxlanrxbytesrate" => array (
				"cntrname"	=>	"vxlanrxbytesrate",
				"cntrdescr"	=>	"",
			),
			"vxlantottxpkts" => Array(
				"cntrname"	=>	"vxlantottxpkts",
				"cntrdescr"	=>	"Packets sent",
				"tooltip"	=>	"Packets transmitted on the VXLAN.",
			),
			"vxlantxpktsrate" => array (
				"cntrname"	=>	"vxlantxpktsrate",
				"cntrdescr"	=>	"",
			),
			"vxlantottxbytes" => Array(
				"cntrname"	=>	"vxlantottxbytes",
				"cntrdescr"	=>	"Bytes sent",
				"tooltip"	=>	"Bytes of data transmitted on the VXLAN.",
			),
			"vxlantxbytesrate" => array (
				"cntrname"	=>	"vxlantxbytesrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("vxlantotrxpkts","vxlantotrxbytes","vxlantottxpkts","vxlantottxbytes",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"VXLAN Summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("vxlantotrxpkts","vxlantotrxbytes","vxlantottxpkts","vxlantottxbytes",),
			),
		),
),


"statns" => Array(
	"cmddispname"		=>	"statns",
	"cmdname"		=>	"statns",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"rescpuusagepcnt" => Array(
				"cntrname"	=>	"rescpuusagepcnt",
				"cntrdescr"	=>	"Average CPU usage (%)",
				"maxval"	=>     4294967294,
				"tooltip"	=>	"Average CPU utilization percentage. Not applicable for a single-CPU system.",
			),
			"cpuusagepcnt" => Array(
				"cntrname"	=>	"cpuusagepcnt",
				"cntrdescr"	=>	"CPU usage (%)",
				"maxval"	=>     4294967294,
				"tooltip"	=>	"CPU utilization percentage.",
			),
			"cachemaxmemorykb" => Array(
				"cntrname"	=>	"cachemaxmemorykb",
				"cntrdescr"	=>	"Maximum memory(KB) Deprecated",
				"tooltip"	=>	"Largest amount of memory the NetScaler can dedicate to caching, up to 50% of available memory. A 0 value disables caching, but the caching module continues to run.",
			),
			"delcmpratio" => Array(
				"cntrname"	=>	"delcmpratio",
				"cntrdescr"	=>	"Delta compression ratio",
				"tooltip"	=>	"Ratio of compressible data received to compressed data transmitted.If this ratio is one (uncmp:1.0) that means compression is disabled or we are not able to compress even a single compressible packet.",
			),
			"rescpuusage" => Array(
				"cntrname"	=>	"rescpuusage",
				"cntrdescr"	=>	"Average CPU usage",
				"maxval"	=>     4294967294,
				"tooltip"	=>	"Shows average CPU utilization percentage if more than 1 CPU is present.",
			),
			"cpuusage" => Array(
				"cntrname"	=>	"cpuusage",
				"cntrdescr"	=>	"CPU usage",
				"cntrdescr2"	=>	"CPU usage (%) rounded off",
				"maxval"	=>     4294967294,
				"tooltip"	=>	"CPU utilization percentage.",
			),
			"resmemusage" => Array(
				"cntrname"	=>	"resmemusage",
				"cntrdescr"	=>	"Memory usage",
				"tooltip"	=>	"Percentage of memory utilization on NetScaler.",
			),
			"comptotaldatacompressionratio" => Array(
				"cntrname"	=>	"comptotaldatacompressionratio",
				"cntrdescr"	=>	"Total HTTP compression ratio",
				"tooltip"	=>	"Ratio of total HTTP data received to total HTTP data transmitted.",
			),
			"compratio" => Array(
				"cntrname"	=>	"compratio",
				"cntrdescr"	=>	"HTTP compression ratio",
				"tooltip"	=>	"Ratio of the compressible data received from the server to the compressed data sent to the client.",
			),
			"cacheutilizedmemorykb" => Array(
				"cntrname"	=>	"cacheutilizedmemorykb",
				"cntrdescr"	=>	"Utilized memory(KB)",
				"tooltip"	=>	"Amount of memory the integrated cache is currently using.",
			),
			"cachemaxmemoryactivekb" => Array(
				"cntrname"	=>	"cachemaxmemoryactivekb",
				"cntrdescr"	=>	"Maximum memory active value(KB)",
				"tooltip"	=>	"Currently active value of maximum memory",
			),
			"cache64maxmemorykb" => Array(
				"cntrname"	=>	"cache64maxmemorykb",
				"cntrdescr"	=>	"Maximum memory(KB)",
				"tooltip"	=>	"Largest amount of memory the NetScaler can dedicate to caching, up to 50% of available memory. A 0 value disables caching, but the caching module continues to run.",
			),
			"cachepercentoriginbandwidthsaved" => Array(
				"cntrname"	=>	"cachepercentoriginbandwidthsaved",
				"cntrdescr"	=>	"Origin bandwidth saved(%)",
				"tooltip"	=>	"Percentage of origin bandwidth saved, expressed as number of bytes served from the integrated cache divided by all bytes served. The assumption is that all compression is done in the NetScaler.",
			),
			"cachetotmisses" => Array(
				"cntrname"	=>	"cachetotmisses",
				"cntrdescr"	=>	"Misses",
				"tooltip"	=>	"Intercepted HTTP requests requiring fetches from origin server.",
			),
			"cachemissesrate" => array (
				"cntrname"	=>	"cachemissesrate",
				"cntrdescr"	=>	"",
			),
			"cachetothits" => Array(
				"cntrname"	=>	"cachetothits",
				"cntrdescr"	=>	"Hits",
				"tooltip"	=>	"Responses served from the integrated cache. These responses match a policy with a CACHE action.",
			),
			"cachehitsrate" => array (
				"cntrname"	=>	"cachehitsrate",
				"cntrdescr"	=>	"",
			),
			"sslnumcardsup" => Array(
				"cntrname"	=>	"sslnumcardsup",
				"cntrdescr"	=>	"# SSL cards UP",
				"tooltip"	=>	"Number of SSL cards that are UP. If the number of cards UP is lower than a threshold, a failover is initiated.",
			),
			"memusagepcnt" => Array(
				"cntrname"	=>	"memusagepcnt",
				"cntrdescr"	=>	"InUse Memory (%)",
				"tooltip"	=>	"Percentage of memory utilization on NetScaler.",
			),
			"memuseinmb" => Array(
				"cntrname"	=>	"memuseinmb",
				"cntrdescr"	=>	"Memory usage (MB)",
				"tooltip"	=>	"Main memory currently in use, in megabytes.",
			),
			"mgmtcpuusagepcnt" => Array(
				"cntrname"	=>	"mgmtcpuusagepcnt",
				"cntrdescr"	=>	"Management CPU usage (%)",
				"maxval"	=>     4294967294,
				"tooltip"	=>	"Management CPU utilization percentage.",
			),
			"pktcpuusagepcnt" => Array(
				"cntrname"	=>	"pktcpuusagepcnt",
				"cntrdescr"	=>	"Packet CPU usage (%)",
				"maxval"	=>     4294967294,
				"tooltip"	=>	"Average CPU utilization percentage for all packet engines excluding management PE.",
			),
			"starttime" => Array(
				"cntrname"	=>	"starttime",
				"cntrdescr"	=>	"Up since",
				"tooltip"	=>	"Time when the NetScaler appliance was last started.",
				"type"		=>	"NS_VALDATE",
			),
			"transtime" => Array(
				"cntrname"	=>	"transtime",
				"cntrdescr"	=>	"Last Transition time",
				"tooltip"	=>	"Time when the last master state transition occurred. You can use this statistic for debugging.",
				"type"		=>	"NS_VALDATE",
			),
			"hacurstate" => Array(
				"cntrname"	=>	"hacurstate",
				"cntrdescr"	=>	"System state",
				"tooltip"	=>	"State of the HA node, based on its health, in a high availability setup. Possible values are: <br><br>UP – Indicates that the node is accessible and can function as either a primary or secondary node.<br><br>DISABLED – Indicates that the high availability status of the node has been manually disabled. Synchronization and propagation cannot take place between the peer nodes.<br><br>INIT – Indicates that the node is in the process of becoming part of the high availability configuration. <br><br>PARTIALFAIL – Indicates that one of the high availability monitored interfaces has failed because of a card or link failure. This state triggers a failover.<br><br>COMPLETEFAIL – Indicates that all the interfaces of the node are unusable, because the interfaces on which high availability monitoring is enabled are not connected or are manually disabled. This state triggers a failover.<br><br>DUMB – Indicates that the node is in listening mode. It does not participate in high availability transitions or transfer configuration from the peer node. This is a configured value, not a statistic.<br><br>PARTIALFAILSSL – Indicates that the SSL card has failed. This state triggers a failover.<br><br>ROUTEMONITORFAIL – Indicates that the route monitor has failed. This state triggers a failover.",
				"type"		=>	"NS_VALENUM",
			),
			"hacurmasterstate" => Array(
				"cntrname"	=>	"hacurmasterstate",
				"cntrdescr"	=>	"Master state",
				"tooltip"	=>	"Indicates the high availability state of the node. Possible values are: <br><br>STAYSECONDARY – Indicates that the selected node remains the secondary node in a high availability setup. In this case a forced failover does not change the state but, instead, returns an appropriate error message. This is a configured value and not a statistic.<br><br>PRIMARY – Indicates that the selected node is the primary node in a high availability setup. <br><br>SECONDARY – Indicates that the selected node is the secondary node in a high availability setup. <br><br>CLAIMING – Indicates that the secondary node is in the process of taking over as the primary node. This is the intermediate state in the transition of the secondary node to primary status. <br><br>FORCE CHANGE - Indicates that the secondary node is forcibly changing its status to primary due to a forced failover issued on the secondary node.",
				"type"		=>	"NS_VALENUM",
			),
			"sslcards" => Array(
				"cntrname"	=>	"sslcards",
				"cntrdescr"	=>	"# SSL cards present",
				"tooltip"	=>	"Number of SSL crypto cards present on the NetScaler appliance.",
			),
			"disk0perusage" => Array(
				"cntrname"	=>	"disk0perusage",
				"cntrdescr"	=>	"/flash Used (%)",
				"tooltip"	=>	"Used space in /flash partition of the disk, as a percentage. This is a critical counter.<br><br>You can configure /flash Used (%) by using the Set snmp alarm DISK-USAGE-HIGH command.",
			),
			"disk1perusage" => Array(
				"cntrname"	=>	"disk1perusage",
				"cntrdescr"	=>	"/var Used (%)",
				"tooltip"	=>	"Used space in /var partition of the disk, as a percentage. This is a critical counter. You can configure /var Used (%) by using the Set snmp alarm DISK-USAGE-HIGH command.",
			),
			"disk0avail" => Array(
				"cntrname"	=>	"disk0avail",
				"cntrdescr"	=>	"/flash Available (MB)",
				"tooltip"	=>	"Available space in /flash partition of the hard disk.",
			),
			"disk1avail" => Array(
				"cntrname"	=>	"disk1avail",
				"cntrdescr"	=>	"/var Available (MB)",
				"tooltip"	=>	"Available space in /var partition of the hard disk.",
			),
			"totrxmbits" => Array(
				"cntrname"	=>	"totrxmbits",
				"cntrdescr"	=>	"Megabits received",
				"tooltip"	=>	"Number of megabytes received by the NetScaler appliance.",
			),
			"rxmbitsrate" => array (
				"cntrname"	=>	"rxmbitsrate",
				"cntrdescr"	=>	"",
			),
			"tottxmbits" => Array(
				"cntrname"	=>	"tottxmbits",
				"cntrdescr"	=>	"Megabits transmitted",
				"tooltip"	=>	"Number of megabytes transmitted by the NetScaler appliance.",
			),
			"txmbitsrate" => array (
				"cntrname"	=>	"txmbitsrate",
				"cntrdescr"	=>	"",
			),
			"tcpcurclientconn" => Array(
				"cntrname"	=>	"tcpcurclientconn",
				"cntrdescr"	=>	"All client connections",
				"tooltip"	=>	"Client connections, including connections in the Opening, Established, and Closing state.",
			),
			"tcpcurclientconnestablished" => Array(
				"cntrname"	=>	"tcpcurclientconnestablished",
				"cntrdescr"	=>	"Established client connections",
				"tooltip"	=>	"Current client connections in the Established state, which indicates that data transfer can occur between the NetScaler and the client.",
			),
			"tcpcurserverconn" => Array(
				"cntrname"	=>	"tcpcurserverconn",
				"cntrdescr"	=>	"All server connections",
				"tooltip"	=>	"Server connections, including connections in the Opening, Established, and Closing state.",
			),
			"tcpcurserverconnestablished" => Array(
				"cntrname"	=>	"tcpcurserverconnestablished",
				"cntrdescr"	=>	"Established server connections",
				"tooltip"	=>	"Current server connections in the Established state, which indicates that data transfer can occur between the NetScaler and the server.",
			),
			"httptotrequests" => Array(
				"cntrname"	=>	"httptotrequests",
				"cntrdescr"	=>	"Total requests",
				"tooltip"	=>	"Total number of HTTP requests received.",
			),
			"httprequestsrate" => array (
				"cntrname"	=>	"httprequestsrate",
				"cntrdescr"	=>	"",
			),
			"httptotresponses" => Array(
				"cntrname"	=>	"httptotresponses",
				"cntrdescr"	=>	"Total responses",
				"tooltip"	=>	"Total number of HTTP responses sent.",
			),
			"httpresponsesrate" => array (
				"cntrname"	=>	"httpresponsesrate",
				"cntrdescr"	=>	"",
			),
			"httptotrxrequestbytes" => Array(
				"cntrname"	=>	"httptotrxrequestbytes",
				"cntrdescr"	=>	"Request bytes received",
				"tooltip"	=>	"Total number of bytes of HTTP request data received.",
			),
			"httprxrequestbytesrate" => array (
				"cntrname"	=>	"httprxrequestbytesrate",
				"cntrdescr"	=>	"",
			),
			"httptotrxresponsebytes" => Array(
				"cntrname"	=>	"httptotrxresponsebytes",
				"cntrdescr"	=>	"Response bytes received",
				"tooltip"	=>	"Total number of bytes of HTTP response data received.",
			),
			"httprxresponsebytesrate" => array (
				"cntrname"	=>	"httprxresponsebytesrate",
				"cntrdescr"	=>	"",
			),
			"ssltottransactions" => Array(
				"cntrname"	=>	"ssltottransactions",
				"cntrdescr"	=>	"SSL transactions",
				"tooltip"	=>	"Number of SSL transactions on the NetScaler appliance.",
			),
			"ssltransactionsrate" => array (
				"cntrname"	=>	"ssltransactionsrate",
				"cntrdescr"	=>	"",
			),
			"ssltotsessionhits" => Array(
				"cntrname"	=>	"ssltotsessionhits",
				"cntrdescr"	=>	"SSL session hits",
				"tooltip"	=>	"Number of SSL session reuse hits on the NetScaler appliance.",
			),
			"sslsessionhitsrate" => array (
				"cntrname"	=>	"sslsessionhitsrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallrequests" => Array(
				"cntrname"	=>	"appfirewallrequests",
				"cntrdescr"	=>	"requests",
				"tooltip"	=>	"HTTP/HTTPS requests sent to your protected web servers via the Application Firewall.",
			),
			"appfirewallrequestsrate" => array (
				"cntrname"	=>	"appfirewallrequestsrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallresponses" => Array(
				"cntrname"	=>	"appfirewallresponses",
				"cntrdescr"	=>	"responses",
				"tooltip"	=>	"HTTP/HTTPS responses sent by your protected web servers via the Application Firewall.",
			),
			"appfirewallresponsesrate" => array (
				"cntrname"	=>	"appfirewallresponsesrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallaborts" => Array(
				"cntrname"	=>	"appfirewallaborts",
				"cntrdescr"	=>	"aborts",
				"tooltip"	=>	"Incomplete HTTP/HTTPS requests aborted by the client before the Application Firewall could finish processing them.",
			),
			"appfirewallabortsrate" => array (
				"cntrname"	=>	"appfirewallabortsrate",
				"cntrdescr"	=>	"",
			),
			"appfirewallredirects" => Array(
				"cntrname"	=>	"appfirewallredirects",
				"cntrdescr"	=>	"redirects",
				"tooltip"	=>	"HTTP/HTTPS requests redirected by the Application Firewall to a different Web page or web server. (HTTP 302)",
			),
			"appfirewallredirectsrate" => array (
				"cntrname"	=>	"appfirewallredirectsrate",
				"cntrdescr"	=>	"",
			),
			"misccounter0" => Array(
				"cntrname"	=>	"misccounter0",
				"cntrdescr"	=>	"Misc. Counter 0",
				"tooltip"	=>	"Miscellaneous Counter 0.",
			),
			"misccounter1" => Array(
				"cntrname"	=>	"misccounter1",
				"cntrdescr"	=>	"Misc. Counter 1",
				"tooltip"	=>	"Miscellaneous Counter 1.",
			),
			"numcpus" => Array(
				"cntrname"	=>	"numcpus",
				"cntrdescr"	=>	"",
				"tooltip"	=>	"The number of CPUs on the NetScaler appliance.",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"text" => "NONE",
				"flag" => "Summary",
				"key" => "default",
				"relatedlink_align"	=> "right",
				"relatedlink"	=>
					Array(
						array(
							 "text" => "Load Balancing", 
							 "cmd" => "statlbvserver", 
							 "type" => "summary", 
						),
						array(
							 "text" => "Interface", 
							 "cmd" => "statinterface", 
							 "type" => "summary", 
						),
					),
			),
			array (
			"format"	=>	"2C",
				"title"	=>	"",
			"column1" => 			Array(
				"dhID"	=>	1,
				"title"	=>	"",
				"header"	=>	array(),
				"dispflag"	=>	"Summary",
				"counters"	=>	array("starttime","pktcpuusagepcnt","mgmtcpuusagepcnt","memuseinmb","memusagepcnt",),
			),
			"column2" => 			Array(
				"dhID"	=>	2,
				"title"	=>	"",
				"header"	=>	array(),
				"dispflag"	=>	"Summary",
				"counters"	=>	array("transtime","hacurstate","hacurmasterstate","sslnumcardsup","sslcards",),
			),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"",
				"header"	=>	array("System Disks","Used (%)","Available (MB)",),
				"format"	=>	"3C",
				"relatedlink_align"	=> "right",
				"relatedlink"	=>
					Array(
						array(
							 "text" => "more...", 
							 "cmd" => "statsystem", 
							 "type" => "detail", 
						),
					),
				"dispflag"	=>	"Summary",
				"counters1"	=>	array("disk0perusage","disk1perusage",),
				"counters2"	=>	array("disk0avail","disk1avail",),
			),
			Array(
				"dhID"	=>	4,
				"title"	=>	"",
				"header"	=>	array("Throughput Statistics","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("totrxmbits","tottxmbits",),
			),
			Array(
				"dhID"	=>	5,
				"title"	=>	"",
				"header"	=>	array("TCP Connections","Client","Server",),
				"format"	=>	"3C",
				"relatedlink_align"	=> "right",
				"relatedlink"	=>
					Array(
						array(
							 "text" => "more...", 
							 "cmd" => "statprotocoltcp", 
							 "type" => "detail", 
						),
					),
				"dispflag"	=>	"Summary",
				"counters1"	=>	array("tcpcurclientconn","tcpcurclientconnestablished",),
				"counters2"	=>	array("tcpcurserverconn","tcpcurserverconnestablished",),
			),
			Array(
				"dhID"	=>	6,
				"title"	=>	"",
				"header"	=>	array("HTTP","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"relatedlink_align"	=> "right",
				"relatedlink"	=>
					Array(
						array(
							 "text" => "more...", 
							 "cmd" => "statprotocolhttp", 
							 "type" => "detail", 
						),
					),
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("httptotrequests","httptotresponses","httptotrxrequestbytes","httptotrxresponsebytes",),
			),
			Array(
				"dhID"	=>	7,
				"title"	=>	"",
				"header"	=>	array("SSL","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"relatedlink_align"	=> "right",
				"relatedlink"	=>
					Array(
						array(
							 "text" => "more...", 
							 "cmd" => "statssl", 
							 "type" => "detail", 
						),
					),
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("ssltottransactions","ssltotsessionhits",),
			),
			Array(
				"dhID"	=>	8,
				"title"	=>	"",
				"header"	=>	array("Integrated Caching","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"relatedlink_align"	=> "right",
				"relatedlink"	=>
					Array(
						array(
							 "text" => "more...", 
							 "cmd" => "statcache", 
							 "type" => "detail", 
						),
					),
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("cachetothits","cachetotmisses","cachepercentoriginbandwidthsaved","cache64maxmemorykb","cachemaxmemoryactivekb","cacheutilizedmemorykb",),
			),
			Array(
				"dhID"	=>	9,
				"title"	=>	"",
				"header"	=>	array("Compression","Value",),
				"format"	=>	"1C",
				"relatedlink_align"	=> "right",
				"relatedlink"	=>
					Array(
						array(
							 "text" => "more...", 
							 "cmd" => "statcmp", 
							 "type" => "summary", 
						),
					),
				"dispflag"	=>	"Summary",
				"counters"	=>	array("compratio","comptotaldatacompressionratio",),
			),
			Array(
				"dhID"	=>	10,
				"title"	=>	"",
				"header"	=>	array("Application Firewall","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"relatedlink_align"	=> "right",
				"relatedlink"	=>
					Array(
						array(
							 "text" => "more...", 
							 "cmd" => "statappfw", 
							 "type" => "summary", 
						),
					),
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("appfirewallrequests","appfirewallresponses","appfirewallaborts","appfirewallredirects",),
			),
		),
),


"statnslimitidentifier" => Array(
	"cmddispname"		=>	"statnslimitidentifier",
	"cmdname"		=>	"statnslimitidentifier",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"pattern" => Array(
				"argname"		=>	"pattern",
				"valtype"		=>	"NS_VALSTR",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
			"sortby" => Array(
				"argname"		=>	"sortby",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
			"sortorder" => Array(
				"argname"		=>	"sortorder",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"ratelmtobjhits" => Array(
				"cntrname"	=>	"ratelmtobjhits",
				"cntrdescr"	=>	"Rate Limit Identifier Hits",
				"tooltip"	=>	"Total hits.",
			),
			"ratelmtobjdrops" => Array(
				"cntrname"	=>	"ratelmtobjdrops",
				"cntrdescr"	=>	"Rate Limit Identifier Drops",
				"tooltip"	=>	"Total drops",
			),
			"ratelmtsessionobjhits" => Array(
				"cntrname"	=>	"ratelmtsessionobjhits",
				"cntrdescr"	=>	"Rate Limit Session Hits",
				"tooltip"	=>	"Total hits.",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Limit Identifier Statistics",
				"header"	=>	array("Name","Hits","Actions",),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("ratelmtobjhits","ratelmtobjdrops",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Limit Identifier(s) Statistics",
				"header"	=>	array("Name","Hits","Actions",),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("ratelmtobjhits","ratelmtobjdrops",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Statistics of sessions created by limit identifier",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"subent",
				"counters"	=>	array("ratelmtsessionobjhits",),
			),
		),
),


"statnsacl" => Array(
	"cmddispname"		=>	"statnsacl",
	"enable"            =>      "1",
	"cmdname"		=>	"statnsacl",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"aclname" => Array(
				"argname"		=>	"aclname",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "aclname",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"acltotpktsbridged" => Array(
				"cntrname"	=>	"acltotpktsbridged",
				"cntrdescr"	=>	"Bridge ACL hits",
				"tooltip"	=>	"Packets matching a bridge ACL, which is in transparent mode and bypasses service processing.",
			),
			"aclpktsbridgedrate" => array (
				"cntrname"	=>	"aclpktsbridgedrate",
				"cntrdescr"	=>	"",
			),
			"acltotpktsdenied" => Array(
				"cntrname"	=>	"acltotpktsdenied",
				"cntrdescr"	=>	"Deny ACL hits",
				"tooltip"	=>	"Packets dropped because they match ACLs with processing mode set to DENY.",
			),
			"aclpktsdeniedrate" => array (
				"cntrname"	=>	"aclpktsdeniedrate",
				"cntrdescr"	=>	"",
			),
			"acltotpktsallowed" => Array(
				"cntrname"	=>	"acltotpktsallowed",
				"cntrdescr"	=>	"Allow ACL hits",
				"tooltip"	=>	"Packets matching ACLs with processing mode set to ALLOW. NetScaler processes these packets.",
			),
			"aclpktsallowedrate" => array (
				"cntrname"	=>	"aclpktsallowedrate",
				"cntrdescr"	=>	"",
			),
			"acltotpktsnat" => Array(
				"cntrname"	=>	"acltotpktsnat",
				"cntrdescr"	=>	"NAT ACL hits",
				"tooltip"	=>	"Packets matching a NAT ACL, resulting in a NAT session.",
			),
			"aclpktsnatrate" => array (
				"cntrname"	=>	"aclpktsnatrate",
				"cntrdescr"	=>	"",
			),
			"acltothits" => Array(
				"cntrname"	=>	"acltothits",
				"cntrdescr"	=>	"ACL hits",
				"tooltip"	=>	"Packets matching an ACL.",
			),
			"aclhitsrate" => array (
				"cntrname"	=>	"aclhitsrate",
				"cntrdescr"	=>	"",
			),
			"acltotmisses" => Array(
				"cntrname"	=>	"acltotmisses",
				"cntrdescr"	=>	"ACL misses",
				"tooltip"	=>	"Packets not matching any ACL.",
			),
			"aclmissesrate" => array (
				"cntrname"	=>	"aclmissesrate",
				"cntrdescr"	=>	"",
			),
			"acltotcount" => Array(
				"cntrname"	=>	"acltotcount",
				"cntrdescr"	=>	"ACL Count",
				"tooltip"	=>	"Total number of ACL rules configured.",
			),
			"aclperhits" => Array(
				"cntrname"	=>	"aclperhits",
				"cntrdescr"	=>	"Hits for this ACL",
				"tooltip"	=>	"Number of times the acl was hit",
			),
			"aclperhitsrate" => array (
				"cntrname"	=>	"aclperhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("aclperhits",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"ACL Statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"singleton"	=>	1,
				"dispflag"	=>	"Summary",
				"counters"	=>	array("acltotpktsallowed","acltotpktsnat","acltotpktsdenied","acltotpktsbridged","acltothits","acltotmisses","acltotcount",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"ACL Statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"singleton"	=>	1,
				"dispflag"	=>	"Detail",
				"counters"	=>	array("acltotpktsallowed","acltotpktsnat","acltotpktsdenied","acltotpktsbridged","acltothits","acltotmisses","acltotcount",),
			),
			Array(
				"dhID"	=>	4,
				"title"	=>	"Per ACL Statistics",
				"header"	=>	array("ACL Name","Hits","Rate",),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("aclperhits",),
			),
		),
),


"statnsacl6" => Array(
	"cmddispname"		=>	"statnsacl6",
	"enable"            =>      "1",
	"cmdname"		=>	"statnsacl6",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"acl6name" => Array(
				"argname"		=>	"acl6name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "acl6name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"acl6totpktsbridged" => Array(
				"cntrname"	=>	"acl6totpktsbridged",
				"cntrdescr"	=>	"Bridge ACL6 hits",
				"tooltip"	=>	"Packets matching a bridge IPv6 ACL, which is in transparent mode and bypasses service processing.",
			),
			"acl6pktsbridgedrate" => array (
				"cntrname"	=>	"acl6pktsbridgedrate",
				"cntrdescr"	=>	"",
			),
			"acl6totpktsdenied" => Array(
				"cntrname"	=>	"acl6totpktsdenied",
				"cntrdescr"	=>	"Deny ACL6 hits",
				"tooltip"	=>	"Packets dropped because they match IPv6 ACLs with processing mode set to DENY.",
			),
			"acl6pktsdeniedrate" => array (
				"cntrname"	=>	"acl6pktsdeniedrate",
				"cntrdescr"	=>	"",
			),
			"acl6totpktsallowed" => Array(
				"cntrname"	=>	"acl6totpktsallowed",
				"cntrdescr"	=>	"Allow ACL6 hits",
				"tooltip"	=>	"Packets matching IPv6 ACLs with processing mode set to ALLOW. NetScaler processes these packets.",
			),
			"acl6pktsallowedrate" => array (
				"cntrname"	=>	"acl6pktsallowedrate",
				"cntrdescr"	=>	"",
			),
			"acl6totpktsnat" => Array(
				"cntrname"	=>	"acl6totpktsnat",
				"cntrdescr"	=>	"NAT ACL6 hits",
				"tooltip"	=>	"Packets matching a NAT ACL6, resulting in a NAT session.",
			),
			"acl6pktsnatrate" => array (
				"cntrname"	=>	"acl6pktsnatrate",
				"cntrdescr"	=>	"",
			),
			"acl6tothits" => Array(
				"cntrname"	=>	"acl6tothits",
				"cntrdescr"	=>	"ACL6 hits",
				"tooltip"	=>	"Packets matching an IPv6 ACL.",
			),
			"acl6hitsrate" => array (
				"cntrname"	=>	"acl6hitsrate",
				"cntrdescr"	=>	"",
			),
			"acl6totmisses" => Array(
				"cntrname"	=>	"acl6totmisses",
				"cntrdescr"	=>	"ACL6 misses",
				"tooltip"	=>	"Packets not matching any IPv6 ACL.",
			),
			"acl6missesrate" => array (
				"cntrname"	=>	"acl6missesrate",
				"cntrdescr"	=>	"",
			),
			"acl6totpktsnat64" => Array(
				"cntrname"	=>	"acl6totpktsnat64",
				"cntrdescr"	=>	"NAT64 ACL6 hits",
				"tooltip"	=>	"Packets matching a NAT64 ACL6, resulting in a NAT64 translation.",
			),
			"acl6pktsnat64rate" => array (
				"cntrname"	=>	"acl6pktsnat64rate",
				"cntrdescr"	=>	"",
			),
			"acl6totcount" => Array(
				"cntrname"	=>	"acl6totcount",
				"cntrdescr"	=>	"ACL6 Count",
				"tooltip"	=>	"Total number of ACL6 rules configured.",
			),
			"acl6perhits" => Array(
				"cntrname"	=>	"acl6perhits",
				"cntrdescr"	=>	"Hits for this ACL6",
				"tooltip"	=>	"Number of times the acl6 was hit",
			),
			"acl6perhitsrate" => array (
				"cntrname"	=>	"acl6perhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("acl6perhits",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"ACL6 Statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"singleton"	=>	1,
				"dispflag"	=>	"Summary",
				"counters"	=>	array("acl6totpktsallowed","acl6totpktsnat","acl6totpktsdenied","acl6totpktsbridged","acl6tothits","acl6totmisses","acl6totpktsnat64","acl6totcount",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"ACL6 Statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"singleton"	=>	1,
				"dispflag"	=>	"Detail",
				"counters"	=>	array("acl6totpktsallowed","acl6totpktsnat","acl6totpktsdenied","acl6totpktsbridged","acl6tothits","acl6totmisses","acl6totpktsnat64","acl6totcount",),
			),
			Array(
				"dhID"	=>	4,
				"title"	=>	" Per ACL6 Statistics ",
				"header"	=>	array("ACL6 Name","Hits","Rate",),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("acl6perhits",),
			),
		),
),


"statnssimpleacl" => Array(
	"cmddispname"		=>	"statnssimpleacl",
	"cmdname"		=>	"statnssimpleacl",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"sacltothits" => Array(
				"cntrname"	=>	"sacltothits",
				"cntrdescr"	=>	"SimpleACL hits",
				"tooltip"	=>	"Packets matching a SimpleACL.",
			),
			"saclhitsrate" => array (
				"cntrname"	=>	"saclhitsrate",
				"cntrdescr"	=>	"",
			),
			"sacltotmisses" => Array(
				"cntrname"	=>	"sacltotmisses",
				"cntrdescr"	=>	"SimpleACL misses",
				"tooltip"	=>	"Packets not matching any SimpleACL.",
			),
			"saclmissesrate" => array (
				"cntrname"	=>	"saclmissesrate",
				"cntrdescr"	=>	"",
			),
			"saclscount" => Array(
				"cntrname"	=>	"saclscount",
				"cntrdescr"	=>	"SimpleACLs count",
				"tooltip"	=>	"Number of SimpleACLs configured.",
			),
			"sacltotpktsallowed" => Array(
				"cntrname"	=>	"sacltotpktsallowed",
				"cntrdescr"	=>	"Allow SimpleACL hits",
				"tooltip"	=>	"Total packets that matched a SimpleACL with action ALLOW and got consumed by NetScaler.",
			),
			"saclpktsallowedrate" => array (
				"cntrname"	=>	"saclpktsallowedrate",
				"cntrdescr"	=>	"",
			),
			"sacltotpktsbridged" => Array(
				"cntrname"	=>	"sacltotpktsbridged",
				"cntrdescr"	=>	"Bridge SimpleACL hits",
				"tooltip"	=>	"Total packets that matched a SimpleACL with action BRIDGE and got bridged by NetScaler.",
			),
			"saclpktsbridgedrate" => array (
				"cntrname"	=>	"saclpktsbridgedrate",
				"cntrdescr"	=>	"",
			),
			"sacltotpktsdenied" => Array(
				"cntrname"	=>	"sacltotpktsdenied",
				"cntrdescr"	=>	"Deny SimpleACL hits",
				"tooltip"	=>	"Packets dropped because they match SimpleACL (Access Control List) with processing mode set to DENY.",
			),
			"saclpktsdeniedrate" => array (
				"cntrname"	=>	"saclpktsdeniedrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("sacltothits","sacltotmisses","saclscount",),
			),
		),
),


"statnssimpleacl6" => Array(
	"cmddispname"		=>	"statnssimpleacl6",
	"cmdname"		=>	"statnssimpleacl6",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"sacl6tothits" => Array(
				"cntrname"	=>	"sacl6tothits",
				"cntrdescr"	=>	"SimpleACL6 hits",
				"tooltip"	=>	"Packets matching a SimpleACL6.",
			),
			"sacl6hitsrate" => array (
				"cntrname"	=>	"sacl6hitsrate",
				"cntrdescr"	=>	"",
			),
			"sacl6totmisses" => Array(
				"cntrname"	=>	"sacl6totmisses",
				"cntrdescr"	=>	"SimpleACL6 misses",
				"tooltip"	=>	"Packets not matching any SimpleACL6.",
			),
			"sacl6missesrate" => array (
				"cntrname"	=>	"sacl6missesrate",
				"cntrdescr"	=>	"",
			),
			"sacl6scount" => Array(
				"cntrname"	=>	"sacl6scount",
				"cntrdescr"	=>	"SimpleACLs6 count",
				"tooltip"	=>	"Number of SimpleACL6s configured.",
			),
			"sacl6totpktsallowed" => Array(
				"cntrname"	=>	"sacl6totpktsallowed",
				"cntrdescr"	=>	"Allow SimpleACL6 hits",
				"tooltip"	=>	"Total packets that matched a SimpleACL6 with action ALLOW and got consumed by NetScaler.",
			),
			"sacl6pktsallowedrate" => array (
				"cntrname"	=>	"sacl6pktsallowedrate",
				"cntrdescr"	=>	"",
			),
			"sacl6totpktsbridged" => Array(
				"cntrname"	=>	"sacl6totpktsbridged",
				"cntrdescr"	=>	"Bridge SimpleACL6 hits",
				"tooltip"	=>	"Total packets that matched a SimpleACL6 with action BRIDGE and got bridged by NetScaler.",
			),
			"sacl6pktsbridgedrate" => array (
				"cntrname"	=>	"sacl6pktsbridgedrate",
				"cntrdescr"	=>	"",
			),
			"sacl6totpktsdenied" => Array(
				"cntrname"	=>	"sacl6totpktsdenied",
				"cntrdescr"	=>	"Deny SimpleACL6 hits",
				"tooltip"	=>	"Packets dropped because they match SimpleACL6 with processing mode set to DENY.",
			),
			"sacl6pktsdeniedrate" => array (
				"cntrname"	=>	"sacl6pktsdeniedrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("sacl6tothits","sacl6totmisses","sacl6scount",),
			),
		),
),


"statnspbr" => Array(
	"cmddispname"		=>	"statnspbr",
	"enable"            =>      "1",
	"cmdname"		=>	"statnspbr",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"pbrtotpktsallowed" => Array(
				"cntrname"	=>	"pbrtotpktsallowed",
				"cntrdescr"	=>	"Allow PBR hits",
				"tooltip"	=>	"Total packets that matched the PBR (Policy-Based Routes) with action ALLOW",
			),
			"pbrpktsallowedrate" => array (
				"cntrname"	=>	"pbrpktsallowedrate",
				"cntrdescr"	=>	"",
			),
			"pbrtotpktsdenied" => Array(
				"cntrname"	=>	"pbrtotpktsdenied",
				"cntrdescr"	=>	"Deny PBR hits",
				"tooltip"	=>	"Total packets that matched the PBR with action DENY",
			),
			"pbrpktsdeniedrate" => array (
				"cntrname"	=>	"pbrpktsdeniedrate",
				"cntrdescr"	=>	"",
			),
			"pbrtothits" => Array(
				"cntrname"	=>	"pbrtothits",
				"cntrdescr"	=>	"PBR hits",
				"tooltip"	=>	"Total packets that matched one of the configured PBR",
			),
			"pbrhitsrate" => array (
				"cntrname"	=>	"pbrhitsrate",
				"cntrdescr"	=>	"",
			),
			"pbrtotmisses" => Array(
				"cntrname"	=>	"pbrtotmisses",
				"cntrdescr"	=>	"PBR misses",
				"tooltip"	=>	"Total packets that did not match any PBR",
			),
			"pbrmissesrate" => array (
				"cntrname"	=>	"pbrmissesrate",
				"cntrdescr"	=>	"",
			),
			"pbrtotnulldrop" => Array(
				"cntrname"	=>	"pbrtotnulldrop",
				"cntrdescr"	=>	"PBR Null Drop",
				"tooltip"	=>	"Total packets that are dropped due to null nexthop",
			),
			"pbrnulldroprate" => array (
				"cntrname"	=>	"pbrnulldroprate",
				"cntrdescr"	=>	"",
			),
			"pbrperhits" => Array(
				"cntrname"	=>	"pbrperhits",
				"cntrdescr"	=>	"Hits for this PBR",
				"tooltip"	=>	"Number of times the pbr was hit",
			),
			"pbrperhitsrate" => array (
				"cntrname"	=>	"pbrperhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pbrperhits",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"PBR Statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"singleton"	=>	1,
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pbrtotpktsallowed","pbrtotpktsdenied","pbrtothits","pbrtotmisses","pbrtotnulldrop",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"PBR Statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"singleton"	=>	1,
				"dispflag"	=>	"Detail",
				"counters"	=>	array("pbrtotpktsallowed","pbrtotpktsdenied","pbrtothits","pbrtotmisses",),
			),
			Array(
				"dhID"	=>	4,
				"title"	=>	"Per PBR Statistics",
				"header"	=>	array("PBR Name","Hits","Rate",),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("pbrperhits",),
			),
		),
),


"statnslimitsessions" => Array(
	"cmddispname"		=>	"statnslimitsessions",
	"cmdname"		=>	"statnslimitsessions",
	"cmddescr"	=>	"     ",
	"hidden"	=>	1,
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
			"sortby" => Array(
				"argname"		=>	"sortby",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
			"sortorder" => Array(
				"argname"		=>	"sortorder",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"ratelmtsessionobjremtime" => Array(
				"cntrname"	=>	"ratelmtsessionobjremtime",
				"cntrdescr"	=>	"Rate Limit Session Remaining Time",
				"tooltip"	=>	"Remaining Time",
			),
			"ratelmtsessionobjhits" => Array(
				"cntrname"	=>	"ratelmtsessionobjhits",
				"cntrdescr"	=>	"Rate Limit Session Hits",
				"tooltip"	=>	"Total hits.",
			),
			"ratelmtsessionobjdrops" => Array(
				"cntrname"	=>	"ratelmtsessionobjdrops",
				"cntrdescr"	=>	"Rate Limit Session Drops",
				"tooltip"	=>	"Total drops",
			),
			"ratelmtsessionobjtransactions" => Array(
				"cntrname"	=>	"ratelmtsessionobjtransactions",
				"cntrdescr"	=>	"Rate Limit Session Active Transactions",
				"tooltip"	=>	"Total Active Transactions",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Statistics of sessions created by rate limit identifier",
				"header"	=>	array("Name","TimeLeft","Hits","Actions","Cur Refs",),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("ratelmtsessionobjremtime","ratelmtsessionobjhits","ratelmtsessionobjdrops","ratelmtsessionobjtransactions",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Statistics of sessions created by rate limit identifier",
				"header"	=>	array("Name","TimeLeft","Hits","Actions","Cur Refs",),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"subent",
				"counters"	=>	array("ratelmtsessionobjremtime","ratelmtsessionobjhits","ratelmtsessionobjdrops","ratelmtsessionobjtransactions",),
			),
		),
),


"statnsmemory" => Array(
	"cmddispname"		=>	"statnsmemory",
	"cmdname"		=>	"statnsmemory",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"pool" => Array(
				"argname"		=>	"pool",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "pool",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"memtotallocfailed" => Array(
				"cntrname"	=>	"memtotallocfailed",
				"cntrdescr"	=>	"Maximum Allocation Failed (MB)",
				"tooltip"	=>	"Total system memory allocation failed.",
			),
			"allocf" => Array(
				"cntrname"	=>	"allocf",
				"cntrdescr"	=>	"Allocation failure",
				"tooltip"	=>	"Memory allocation failure for particular feature.",
			),
			"memcurallocper" => Array(
				"cntrname"	=>	"memcurallocper",
				"cntrdescr"	=>	"Percentage of memory allocated",
				"tooltip"	=>	"Percentage of NetScaler memory used by the feature.",
			),
			"memcurinkb" => Array(
				"cntrname"	=>	"memcurinkb",
				"cntrdescr"	=>	"Total memory Allocated (KB)",
				"tooltip"	=>	"Total current NetScaler memory available for use by the feature, in kilobytes.",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"NetScaler Feature Memory Stats",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("memcurinkb","memcurallocper","allocf",),
			),
			Array(
				"text" => "NONE",
				"flag" => "Summary",
				"key" => "default",
				"relatedlink_align"	=> "right",
				"relatedlink"	=>
					Array(
						array(
							 "text" => "Global Memory Statistics", 
							 "cmd" => "statsystemmemory", 
							 "type" => "summary", 
						),
					),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"NetScaler Feature Memory Stats",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("memcurinkb","memcurallocper","allocf",),
			),
		),
),


"statnspbr6" => Array(
	"cmddispname"		=>	"statnspbr6",
	"enable"            =>      "1",
	"cmdname"		=>	"statnspbr6",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"pbr6totpktsallowed" => Array(
				"cntrname"	=>	"pbr6totpktsallowed",
				"cntrdescr"	=>	"Allow PBR6 hits",
				"tooltip"	=>	"Total packets that matched the PBR6 with action ALLOW",
			),
			"pbr6pktsallowedrate" => array (
				"cntrname"	=>	"pbr6pktsallowedrate",
				"cntrdescr"	=>	"",
			),
			"pbr6totpktsdenied" => Array(
				"cntrname"	=>	"pbr6totpktsdenied",
				"cntrdescr"	=>	"Deny PBR6 hits",
				"tooltip"	=>	"Total packets that matched PBR6 with action DENY",
			),
			"pbr6pktsdeniedrate" => array (
				"cntrname"	=>	"pbr6pktsdeniedrate",
				"cntrdescr"	=>	"",
			),
			"pbr6tothits" => Array(
				"cntrname"	=>	"pbr6tothits",
				"cntrdescr"	=>	"PBR6 hits",
				"tooltip"	=>	"Total packets that matched one of the configured PBR6",
			),
			"pbr6hitsrate" => array (
				"cntrname"	=>	"pbr6hitsrate",
				"cntrdescr"	=>	"",
			),
			"pbr6totmisses" => Array(
				"cntrname"	=>	"pbr6totmisses",
				"cntrdescr"	=>	"PBR6 misses",
				"tooltip"	=>	"Total packets that did not match any PBR6",
			),
			"pbr6missesrate" => array (
				"cntrname"	=>	"pbr6missesrate",
				"cntrdescr"	=>	"",
			),
			"pbr6totnulldrop" => Array(
				"cntrname"	=>	"pbr6totnulldrop",
				"cntrdescr"	=>	"PBR6 Null Drop",
				"tooltip"	=>	"Total packets that are dropped due to null next hop",
			),
			"pbr6nulldroprate" => array (
				"cntrname"	=>	"pbr6nulldroprate",
				"cntrdescr"	=>	"",
			),
			"pbr6perhits" => Array(
				"cntrname"	=>	"pbr6perhits",
				"cntrdescr"	=>	"Hits for this PBR6",
				"tooltip"	=>	"Number of times the pbr6 was hit",
			),
			"pbr6perhitsrate" => array (
				"cntrname"	=>	"pbr6perhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pbr6perhits",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"PBR6 Statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"singleton"	=>	1,
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pbr6totpktsallowed","pbr6totpktsdenied","pbr6tothits","pbr6totmisses","pbr6totnulldrop",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"PBR6 Statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"singleton"	=>	1,
				"dispflag"	=>	"Detail",
				"counters"	=>	array("pbr6totpktsallowed","pbr6totpktsdenied","pbr6tothits","pbr6totmisses",),
			),
			Array(
				"dhID"	=>	4,
				"title"	=>	" Per PBR6 Statistics ",
				"header"	=>	array("PBR6 Name","Hits","Rate",),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("pbr6perhits",),
			),
		),
),


"statnstrafficdomain" => Array(
	"cmddispname"		=>	"statnstrafficdomain",
	"enable"            =>      "1",
	"cmdname"		=>	"statnstrafficdomain",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"td" => Array(
				"argname"		=>	"td",
				"valtype"		=>	"NS_VALUINT",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "td",
	"nameargtype"	=> "NS_VALUINT",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"nstdtotrxpkts" => Array(
				"cntrname"	=>	"nstdtotrxpkts",
				"cntrdescr"	=>	"Packets received",
				"tooltip"	=>	"Packets received on this TD.",
			),
			"nstdrxpktsrate" => array (
				"cntrname"	=>	"nstdrxpktsrate",
				"cntrdescr"	=>	"",
			),
			"nstdtottxpkts" => Array(
				"cntrname"	=>	"nstdtottxpkts",
				"cntrdescr"	=>	"Packets sent",
				"tooltip"	=>	"Packets transmitted from this TD.",
			),
			"nstdtxpktsrate" => array (
				"cntrname"	=>	"nstdtxpktsrate",
				"cntrdescr"	=>	"",
			),
			"nstdtotdroppedpkts" => Array(
				"cntrname"	=>	"nstdtotdroppedpkts",
				"cntrdescr"	=>	"Packets dropped",
				"tooltip"	=>	"Inbound packets dropped on this TD by reception.",
			),
			"nstddroppedpktsrate" => array (
				"cntrname"	=>	"nstddroppedpktsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("nstdtotrxpkts","nstdtottxpkts","nstdtotdroppedpkts",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Traffic Domain Summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("nstdtotrxpkts","nstdtottxpkts","nstdtotdroppedpkts",),
			),
		),
),


"statnspartition" => Array(
	"cmddispname"		=>	"statnspartition",
	"cmdname"		=>	"statnspartition",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"partitionname" => Array(
				"argname"		=>	"partitionname",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "partitionname",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"minbandwidth" => Array(
				"cntrname"	=>	"minbandwidth",
				"cntrdescr"	=>	"Minimum Bandwidth",
				"tooltip"	=>	"Minimum Bandwidth required by the partition.",
			),
			"maxbandwidth" => Array(
				"cntrname"	=>	"maxbandwidth",
				"cntrdescr"	=>	"Maximum Bandwidth",
				"tooltip"	=>	"Maximum Banwidth allowed for the partition.",
			),
			"maxconnection" => Array(
				"cntrname"	=>	"maxconnection",
				"cntrdescr"	=>	"Maximum Connection",
				"tooltip"	=>	"Maximum Connection allowed for the partition.",
			),
			"maxmemory" => Array(
				"cntrname"	=>	"maxmemory",
				"cntrdescr"	=>	"Maximum Memory",
				"tooltip"	=>	"Maximum memory limit for the partition.",
			),
			"currentbandwidth" => Array(
				"cntrname"	=>	"currentbandwidth",
				"cntrdescr"	=>	"Current Bandwith",
				"tooltip"	=>	"Current Bandwidth usage for the partition.",
			),
			"currentconnections" => Array(
				"cntrname"	=>	"currentconnections",
				"cntrdescr"	=>	"Current Connections",
				"tooltip"	=>	"Current Connections on this partition.",
			),
			"memoryusagepcnt" => Array(
				"cntrname"	=>	"memoryusagepcnt",
				"cntrdescr"	=>	"Memory Usage(%)",
				"tooltip"	=>	"Memory usage(%) on this partition.",
			),
			"totaldrops" => Array(
				"cntrname"	=>	"totaldrops",
				"cntrdescr"	=>	"Total Packet Drops",
				"tooltip"	=>	"Total packet drops for the partition.",
			),
			"dropsrate" => array (
				"cntrname"	=>	"dropsrate",
				"cntrdescr"	=>	"",
			),
			"totaltokendrops" => Array(
				"cntrname"	=>	"totaltokendrops",
				"cntrdescr"	=>	"Total Drops(KB)",
				"tooltip"	=>	"Total drops(KB) for the partition.",
			),
			"tokendropsrate" => array (
				"cntrname"	=>	"tokendropsrate",
				"cntrdescr"	=>	"",
			),
			"totalconnectiondrops" => Array(
				"cntrname"	=>	"totalconnectiondrops",
				"cntrdescr"	=>	"Total Connection Drops",
				"tooltip"	=>	"Total connection drops for the partition.",
			),
			"connectiondropsrate" => array (
				"cntrname"	=>	"connectiondropsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Partition(s) Summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("minbandwidth","maxbandwidth","maxconnection","maxmemory",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Partition Stats:",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("currentbandwidth","currentconnections","memoryusagepcnt","totaldrops","totaltokendrops","totalconnectiondrops",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Partition(s) Summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("currentbandwidth","minbandwidth","maxbandwidth","currentconnections","maxconnection","memoryusagepcnt","maxmemory",),
			),
		),
),


"statpq" => Array(
	"cmddispname"		=>	"statpq",
	"cmdname"		=>	"statpq",
	"license"		=>	Array("NSKF1_PQ_BIT_POS","NSKF1_PQ_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"pqtotalpolicymatches" => Array(
				"cntrname"	=>	"pqtotalpolicymatches",
				"cntrdescr"	=>	"Policy hits",
				"tooltip"	=>	"Number of times the Netscaler appliance matched an incoming request using any priority queuing policy.",
			),
			"pqpolicymatchesrate" => array (
				"cntrname"	=>	"pqpolicymatchesrate",
				"cntrdescr"	=>	"",
			),
			"pqtotalthresholdfailed" => Array(
				"cntrname"	=>	"pqtotalthresholdfailed",
				"cntrdescr"	=>	"Threshold failed",
				"tooltip"	=>	"Number of times the Netscaler appliance failed to match an incoming request to any of priority queing policy.",
			),
			"pqthresholdfailedrate" => array (
				"cntrname"	=>	"pqthresholdfailedrate",
				"cntrdescr"	=>	"",
			),
			"pqpriority1requests" => Array(
				"cntrname"	=>	"pqpriority1requests",
				"cntrdescr"	=>	"Priority 1 requests",
				"tooltip"	=>	"Number of priority 1 requests that the Netscaler appliance received.",
			),
			"pqpriority1requestsrate" => array (
				"cntrname"	=>	"pqpriority1requestsrate",
				"cntrdescr"	=>	"",
			),
			"pqpriority2requests" => Array(
				"cntrname"	=>	"pqpriority2requests",
				"cntrdescr"	=>	"Priority 2 requests",
				"tooltip"	=>	"Number of priority 2 requests that the Netscaler appliance received.",
			),
			"pqpriority2requestsrate" => array (
				"cntrname"	=>	"pqpriority2requestsrate",
				"cntrdescr"	=>	"",
			),
			"pqpriority3requests" => Array(
				"cntrname"	=>	"pqpriority3requests",
				"cntrdescr"	=>	"Priority 3 requests",
				"tooltip"	=>	"Number of priority 3 requests that the Netscaler appliance received.",
			),
			"pqpriority3requestsrate" => array (
				"cntrname"	=>	"pqpriority3requestsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"text" => "NONE",
				"flag" => "Summary",
				"key" => "default",
				"relatedlink_align"	=> "right",
				"relatedlink"	=>
					Array(
						array(
							 "text" => "Per Policy statistics", 
							 "cmd" => "statpqpolicy", 
							 "type" => "summary", 
						),
					),
			),
			Array(
				"dhID"	=>	1,
				"title"	=>	"",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pqtotalpolicymatches","pqtotalthresholdfailed","pqpriority1requests","pqpriority2requests","pqpriority3requests",),
			),
		),
),


"statpqpolicy" => Array(
	"cmddispname"		=>	"statpqpolicy",
	"cmdname"		=>	"statpqpolicy",
	"license"		=>	Array("NSKF1_PQ_BIT_POS","NSKF1_PQ_BIT_POS"),
	"cmddescr"	=>	"     ",
	"bindings"	=>	1,
	"args"	=>
		Array (
			"policyname" => Array(
				"argname"		=>	"policyname",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "policyname",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"pqtotavgqueuewaittime" => Array(
				"cntrname"	=>	"pqtotavgqueuewaittime",
				"cntrdescr"	=>	"Avg queue wait time",
				"tooltip"	=>	"Average wait time for clients for this priority queuing policy.",
			),
			"pqavgqueuewaittimerate" => array (
				"cntrname"	=>	"pqavgqueuewaittimerate",
				"cntrdescr"	=>	"",
			),
			"pqavgclienttransactiontimems" => Array(
				"cntrname"	=>	"pqavgclienttransactiontimems",
				"cntrdescr"	=>	"Avg clt transaction time",
				"tooltip"	=>	"Average time taken by a priority queuing client to complete its transaction for this  priority queuing policy.",
			),
			"pqavgclienttransactiontimemsrate" => array (
				"cntrname"	=>	"pqavgclienttransactiontimemsrate",
				"cntrdescr"	=>	"",
			),
			"pqvserverip" => Array(
				"cntrname"	=>	"pqvserverip",
				"cntrdescr"	=>	"Vserver IP",
				"tooltip"	=>	"IP address of the virtual server to which this priority queuing policy is bound.",
				"type"		=>	"NS_VALIPSTR",
			),
			"pqvserverport" => Array(
				"cntrname"	=>	"pqvserverport",
				"cntrdescr"	=>	"Vserver port",
				"tooltip"	=>	"Port number of the virtual server to which this priority queuing policy is bound.",
				"type"		=>	"NS_VALPORT",
			),
			"pqqdepth" => Array(
				"cntrname"	=>	"pqqdepth",
				"cntrdescr"	=>	"Current queue depth",
				"tooltip"	=>	"Number of clients waiting currently for this priority queuing policy.",
			),
			"pqqdepthrate" => array (
				"cntrname"	=>	"pqqdepthrate",
				"cntrdescr"	=>	"",
			),
			"pqcurrentclientconnections" => Array(
				"cntrname"	=>	"pqcurrentclientconnections",
				"cntrdescr"	=>	"Current server connections",
				"tooltip"	=>	"Current number of server connections established for serving clients for this priority queuing policy.",
			),
			"pqcurrentclientconnectionsrate" => array (
				"cntrname"	=>	"pqcurrentclientconnectionsrate",
				"cntrdescr"	=>	"",
			),
			"pqtotclientconnections" => Array(
				"cntrname"	=>	"pqtotclientconnections",
				"cntrdescr"	=>	"Server TCP connections",
				"tooltip"	=>	"Total number of server connections established for serving clients for this priority queuing policy.",
			),
			"pqclientconnectionsrate" => array (
				"cntrname"	=>	"pqclientconnectionsrate",
				"cntrdescr"	=>	"",
			),
			"pqdropped" => Array(
				"cntrname"	=>	"pqdropped",
				"cntrdescr"	=>	"Client requests dropped",
				"tooltip"	=>	"Total number of dropped transactions for this priority queuing policy.",
			),
			"pqdroppedrate" => array (
				"cntrname"	=>	"pqdroppedrate",
				"cntrdescr"	=>	"",
			),
			"totclienttransactions" => Array(
				"cntrname"	=>	"totclienttransactions",
				"cntrdescr"	=>	"Client HTTP transactions",
				"tooltip"	=>	"Total number of client transactions for this priority queuing policy.",
			),
			"clienttransactionsrate" => array (
				"cntrname"	=>	"clienttransactionsrate",
				"cntrdescr"	=>	"",
			),
			"pqtotqueuedepth" => Array(
				"cntrname"	=>	"pqtotqueuedepth",
				"cntrdescr"	=>	"Queue depth",
				"tooltip"	=>	"Total number of waiting clients for this priority queuing policy.",
			),
			"pqqueuedepthrate" => array (
				"cntrname"	=>	"pqqueuedepthrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"PQ policy summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pqvserverip","pqvserverport",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Runtime statistics",
				"header"	=>	array("","Value",),
				"format"	=>	"1C",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pqqdepth","pqcurrentclientconnections",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Cumulative statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pqtotclientconnections","pqdropped","totclienttransactions","pqtotqueuedepth",),
			),
			Array(
				"dhID"	=>	4,
				"title"	=>	"Timing statistics",
				"header"	=>	array("","Value (milliseconds)",),
				"format"	=>	"1C",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pqavgclienttransactiontimems","pqtotavgqueuewaittime",),
			),
			Array(
				"text" => "NONE",
				"flag" => "Summary",
				"key" => "default",
				"relatedlink_align"	=> "right",
				"relatedlink"	=>
					Array(
						array(
							 "text" => "Global Statistics", 
							 "cmd" => "statpq", 
							 "type" => "summary", 
						),
					),
			),
			Array(
				"dhID"	=>	5,
				"title"	=>	"PQ policy summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pqvserverip","pqvserverport","pqqdepth","totclienttransactions",),
			),
		),
),


"statprotocoltcp" => Array(
	"cmddispname"		=>	"statprotocoltcp",
	"cmdname"		=>	"statprotocoltcp",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"tcpactiveserverconn" => Array(
				"cntrname"	=>	"tcpactiveserverconn",
				"cntrdescr"	=>	"Server active connections",
				"tooltip"	=>	"Connections to a server currently responding to requests.",
			),
			"tcpcurserverconnopening" => Array(
				"cntrname"	=>	"tcpcurserverconnopening",
				"cntrdescr"	=>	"Opening server connections",
				"tooltip"	=>	"Server connections in the Opening state, which indicates that the handshakes are not yet complete.",
			),
			"tcpcurclientconnopening" => Array(
				"cntrname"	=>	"tcpcurclientconnopening",
				"cntrdescr"	=>	"Opening client connections",
				"tooltip"	=>	"Client connections in the Opening state, which indicates that the handshakes are not yet complete.",
			),
			"tcpcurclientconnestablished" => Array(
				"cntrname"	=>	"tcpcurclientconnestablished",
				"cntrdescr"	=>	"Established client connections",
				"tooltip"	=>	"Current client connections in the Established state, which indicates that data transfer can occur between the NetScaler and the client.",
			),
			"tcpcurserverconnestablished" => Array(
				"cntrname"	=>	"tcpcurserverconnestablished",
				"cntrdescr"	=>	"Established server connections",
				"tooltip"	=>	"Current server connections in the Established state, which indicates that data transfer can occur between the NetScaler and the server.",
			),
			"tcptotrxpkts" => Array(
				"cntrname"	=>	"tcptotrxpkts",
				"cntrdescr"	=>	"TCP packets received",
				"tooltip"	=>	"TCP packets received.",
			),
			"tcprxpktsrate" => array (
				"cntrname"	=>	"tcprxpktsrate",
				"cntrdescr"	=>	"",
			),
			"tcptotrxbytes" => Array(
				"cntrname"	=>	"tcptotrxbytes",
				"cntrdescr"	=>	"TCP bytes received",
				"tooltip"	=>	"Bytes of TCP data received.",
			),
			"tcprxbytesrate" => array (
				"cntrname"	=>	"tcprxbytesrate",
				"cntrdescr"	=>	"",
			),
			"tcptottxpkts" => Array(
				"cntrname"	=>	"tcptottxpkts",
				"cntrdescr"	=>	"TCP packets transmitted",
				"tooltip"	=>	"TCP packets transmitted.",
			),
			"tcptxpktsrate" => array (
				"cntrname"	=>	"tcptxpktsrate",
				"cntrdescr"	=>	"",
			),
			"tcptottxbytes" => Array(
				"cntrname"	=>	"tcptottxbytes",
				"cntrdescr"	=>	"TCP bytes transmitted",
				"tooltip"	=>	"Bytes of TCP data transmitted.",
			),
			"tcptxbytesrate" => array (
				"cntrname"	=>	"tcptxbytesrate",
				"cntrdescr"	=>	"",
			),
			"tcpcurclientconn" => Array(
				"cntrname"	=>	"tcpcurclientconn",
				"cntrdescr"	=>	"All client connections",
				"tooltip"	=>	"Client connections, including connections in the Opening, Established, and Closing state.",
			),
			"tcpcurclientconnclosing" => Array(
				"cntrname"	=>	"tcpcurclientconnclosing",
				"cntrdescr"	=>	"Closing client connections",
				"tooltip"	=>	"Client connections in the Closing state, which indicates that the connection termination process has initiated but is not complete.",
			),
			"tcptotclientconnopened" => Array(
				"cntrname"	=>	"tcptotclientconnopened",
				"cntrdescr"	=>	"Opened client connections",
				"tooltip"	=>	"Client connections opened by the NetScaler since startup (after three-way handshake). This counter is reset when the NetScaler is restarted.",
			),
			"tcpclientconnopenedrate" => array (
				"cntrname"	=>	"tcpclientconnopenedrate",
				"cntrdescr"	=>	"",
			),
			"tcpcurserverconn" => Array(
				"cntrname"	=>	"tcpcurserverconn",
				"cntrdescr"	=>	"All server connections",
				"tooltip"	=>	"Server connections, including connections in the Opening, Established, and Closing state.",
			),
			"tcpcurserverconnclosing" => Array(
				"cntrname"	=>	"tcpcurserverconnclosing",
				"cntrdescr"	=>	"Closing server connections",
				"tooltip"	=>	"Server connections in the Closing state, which indicates that the connection termination process has initiated but is not complete.",
			),
			"tcptotserverconnopened" => Array(
				"cntrname"	=>	"tcptotserverconnopened",
				"cntrdescr"	=>	"Opened server connections",
				"tooltip"	=>	"Server connections initiated by the NetScaler since startup. This counter is reset when the NetScaler is restarted.",
			),
			"tcpserverconnopenedrate" => array (
				"cntrname"	=>	"tcpserverconnopenedrate",
				"cntrdescr"	=>	"",
			),
			"tcpsurgequeuelen" => Array(
				"cntrname"	=>	"tcpsurgequeuelen",
				"cntrdescr"	=>	"Surge queue",
				"tooltip"	=>	"Connections in the surge queue. When the NetScaler cannot open a connection to the server, for example when maximum connections have been reached, the NetScaler queues these requests.",
			),
			"tcpspareconn" => Array(
				"cntrname"	=>	"tcpspareconn",
				"cntrdescr"	=>	"Spare connections",
				"tooltip"	=>	"Spare connections available. To save time and resources in establishing another connection for a new client, the connection on the server is not closed after completing the request from the first client and is available for serving future requests.",
			),
			"tcptotzombiecltconnflushed" => Array(
				"cntrname"	=>	"tcptotzombiecltconnflushed",
				"cntrdescr"	=>	"Client idle flushed",
				"tooltip"	=>	"Client connections that are flushed because the client has been idle for some time.",
			),
			"tcpzombiecltconnflushedrate" => array (
				"cntrname"	=>	"tcpzombiecltconnflushedrate",
				"cntrdescr"	=>	"",
			),
			"tcptotzombiehalfopencltconnflushed" => Array(
				"cntrname"	=>	"tcptotzombiehalfopencltconnflushed",
				"cntrdescr"	=>	"Client half opened flushed",
				"tooltip"	=>	"Half-opened client connections that are flushed because the three-way handshakes are not complete.",
			),
			"tcpzombiehalfopencltconnflushedrate" => array (
				"cntrname"	=>	"tcpzombiehalfopencltconnflushedrate",
				"cntrdescr"	=>	"",
			),
			"tcptotzombieactivehalfclosecltconnflushed" => Array(
				"cntrname"	=>	"tcptotzombieactivehalfclosecltconnflushed",
				"cntrdescr"	=>	"Client active half closed flushed",
				"tooltip"	=>	"Active half-closed client connections that are flushed because the client has closed the connection and there has been no activity on the connection.",
			),
			"tcpzombieactivehalfclosecltconnflushedrate" => array (
				"cntrname"	=>	"tcpzombieactivehalfclosecltconnflushedrate",
				"cntrdescr"	=>	"",
			),
			"tcptotzombiepassivehalfclosecltconnflushed" => Array(
				"cntrname"	=>	"tcptotzombiepassivehalfclosecltconnflushed",
				"cntrdescr"	=>	"Client passive half closed flushed",
				"tooltip"	=>	"Passive half-closed client connections that are flushed because the NetScaler has closed the connection and there has been no activity on the connection.",
			),
			"tcpzombiepassivehalfclosecltconnflushedrate" => array (
				"cntrname"	=>	"tcpzombiepassivehalfclosecltconnflushedrate",
				"cntrdescr"	=>	"",
			),
			"tcptotzombiesvrconnflushed" => Array(
				"cntrname"	=>	"tcptotzombiesvrconnflushed",
				"cntrdescr"	=>	"Server idle connections flushed",
				"tooltip"	=>	"Server connections that are flushed because there have been no client requests in the queue for some time.",
			),
			"tcpzombiesvrconnflushedrate" => array (
				"cntrname"	=>	"tcpzombiesvrconnflushedrate",
				"cntrdescr"	=>	"",
			),
			"tcptotzombiehalfopensvrconnflushed" => Array(
				"cntrname"	=>	"tcptotzombiehalfopensvrconnflushed",
				"cntrdescr"	=>	"Server half opened flushed",
				"tooltip"	=>	"Half-opened server connections that are flushed because the three-way handshakes are not complete.",
			),
			"tcpzombiehalfopensvrconnflushedrate" => array (
				"cntrname"	=>	"tcpzombiehalfopensvrconnflushedrate",
				"cntrdescr"	=>	"",
			),
			"tcptotzombieactivehalfclosesvrconnflushed" => Array(
				"cntrname"	=>	"tcptotzombieactivehalfclosesvrconnflushed",
				"cntrdescr"	=>	"Server active half closed flushed",
				"tooltip"	=>	"Active half-closed server connections that are flushed because the server has closed the connection and there has been no activity on the connection.",
			),
			"tcpzombieactivehalfclosesvrconnflushedrate" => array (
				"cntrname"	=>	"tcpzombieactivehalfclosesvrconnflushedrate",
				"cntrdescr"	=>	"",
			),
			"tcptotzombiepassivehalfclosesrvconnflushed" => Array(
				"cntrname"	=>	"tcptotzombiepassivehalfclosesrvconnflushed",
				"cntrdescr"	=>	"Server passive half closed flushed",
				"tooltip"	=>	"Passive half-closed server connections that are flushed because the NetScaler has closed the connection and there has been no activity on the connection.",
			),
			"tcpzombiepassivehalfclosesrvconnflushedrate" => array (
				"cntrname"	=>	"tcpzombiepassivehalfclosesrvconnflushedrate",
				"cntrdescr"	=>	"",
			),
			"pcbtotzombiecall" => Array(
				"cntrname"	=>	"pcbtotzombiecall",
				"cntrdescr"	=>	"Zombie cleanup calls",
				"tooltip"	=>	"Times the Zombie cleanup function is called. Every time a connection is flushed, it is marked for cleanup. The Zombie cleanup function clears all these connections at predefined intervals.",
			),
			"pcbzombiecallrate" => array (
				"cntrname"	=>	"pcbzombiecallrate",
				"cntrdescr"	=>	"",
			),
			"tcptotsyn" => Array(
				"cntrname"	=>	"tcptotsyn",
				"cntrdescr"	=>	"SYN packets received",
				"tooltip"	=>	"SYN packets received",
			),
			"tcpsynrate" => array (
				"cntrname"	=>	"tcpsynrate",
				"cntrdescr"	=>	"",
			),
			"tcptotsynprobe" => Array(
				"cntrname"	=>	"tcptotsynprobe",
				"cntrdescr"	=>	"Server probes",
				"tooltip"	=>	"Probes from the NetScaler to a server. The NetScaler sends a SYN packet to the server to check its availability and expects a SYN_ACK packet from the server before a specified response timeout.",
			),
			"tcpsynproberate" => array (
				"cntrname"	=>	"tcpsynproberate",
				"cntrdescr"	=>	"",
			),
			"tcptotsvrfin" => Array(
				"cntrname"	=>	"tcptotsvrfin",
				"cntrdescr"	=>	"FIN packets from server",
				"tooltip"	=>	"FIN packets received from the server.",
			),
			"tcpsvrfinrate" => array (
				"cntrname"	=>	"tcpsvrfinrate",
				"cntrdescr"	=>	"",
			),
			"tcptotcltfin" => Array(
				"cntrname"	=>	"tcptotcltfin",
				"cntrdescr"	=>	"FIN packets from client",
				"tooltip"	=>	"FIN packets received from the clients.",
			),
			"tcpcltfinrate" => array (
				"cntrname"	=>	"tcpcltfinrate",
				"cntrdescr"	=>	"",
			),
			"tcpwaittosyn" => Array(
				"cntrname"	=>	"tcpwaittosyn",
				"cntrdescr"	=>	"Time wait to SYN",
				"tooltip"	=>	"SYN packets (packets used to initiate a TCP connection) received on connections that are in the TIME_WAIT state. Packets cannot be transferred on a connection in this state.",
			),
			"tcpwaittosynrate" => array (
				"cntrname"	=>	"tcpwaittosynrate",
				"cntrdescr"	=>	"",
			),
			"tcpwaittodata" => Array(
				"cntrname"	=>	"tcpwaittodata",
				"cntrdescr"	=>	"Data in TIME_WAIT",
				"tooltip"	=>	"Bytes of data received on connections that are in the TIME_WAIT state. Data cannot be transferred on a connection that is in this state.",
			),
			"tcpwaittodatarate" => array (
				"cntrname"	=>	"tcpwaittodatarate",
				"cntrdescr"	=>	"",
			),
			"tcptotsynheld" => Array(
				"cntrname"	=>	"tcptotsynheld",
				"cntrdescr"	=>	"SYN packets held",
				"tooltip"	=>	"SYN packets held on the NetScaler that are waiting for a server connection.",
			),
			"tcpsynheldrate" => array (
				"cntrname"	=>	"tcpsynheldrate",
				"cntrdescr"	=>	"",
			),
			"tcptotsynflush" => Array(
				"cntrname"	=>	"tcptotsynflush",
				"cntrdescr"	=>	"SYN packets flushed",
				"tooltip"	=>	"SYN packets flushed on the NetScaler because of no response from the server for three or more seconds.",
			),
			"tcpsynflushrate" => array (
				"cntrname"	=>	"tcpsynflushrate",
				"cntrdescr"	=>	"",
			),
			"tcptotfinwaitclosed" => Array(
				"cntrname"	=>	"tcptotfinwaitclosed",
				"cntrdescr"	=>	"TIME_WAIT connections closed",
				"tooltip"	=>	"Connections closed on the NetScaler because the number of connections in the TIME_WAIT state has exceeded the default value of 7000.",
			),
			"tcpfinwaitclosedrate" => array (
				"cntrname"	=>	"tcpfinwaitclosedrate",
				"cntrdescr"	=>	"",
			),
			"tcperrbadchecksum" => Array(
				"cntrname"	=>	"tcperrbadchecksum",
				"cntrdescr"	=>	"Bad TCP checksum",
				"tooltip"	=>	"Packets received with a TCP checksum error.",
			),
			"tcperrbadchecksumrate" => array (
				"cntrname"	=>	"tcperrbadchecksumrate",
				"cntrdescr"	=>	"",
			),
			"tcperrdataafterfin" => Array(
				"cntrname"	=>	"tcperrdataafterfin",
				"cntrdescr"	=>	"Data after FIN",
				"tooltip"	=>	"Packets received following a connection termination request. This error is usually caused by a reordering of packets during transmission.",
			),
			"tcperrdataafterfinrate" => array (
				"cntrname"	=>	"tcperrdataafterfinrate",
				"cntrdescr"	=>	"",
			),
			"tcperrsyninsynrcvd" => Array(
				"cntrname"	=>	"tcperrsyninsynrcvd",
				"cntrdescr"	=>	"SYN in SYN_RCVD state",
				"tooltip"	=>	"SYN packets received on a connection that is in the SYN_RCVD state. A connection goes into the SYN_RCVD state after receiving a SYN packet.",
			),
			"tcperrsyninsynrcvdrate" => array (
				"cntrname"	=>	"tcperrsyninsynrcvdrate",
				"cntrdescr"	=>	"",
			),
			"tcperrsyninest" => Array(
				"cntrname"	=>	"tcperrsyninest",
				"cntrdescr"	=>	"SYN in ESTABLISHED state",
				"tooltip"	=>	"SYN packets received on a connection that is in the ESTABLISHED state. A SYN packet is not expected on an ESTABLISHED connection.",
			),
			"tcperrsyninestrate" => array (
				"cntrname"	=>	"tcperrsyninestrate",
				"cntrdescr"	=>	"",
			),
			"tcperrsynsentbadack" => Array(
				"cntrname"	=>	"tcperrsynsentbadack",
				"cntrdescr"	=>	"SYN_SENT incorrect ACK packet",
				"tooltip"	=>	"Incorrect ACK packets received on a connection that is in the SYN_SENT state. An incorrect ACK packet is the third packet in the three-way handshake that has an incorrect sequence number.",
			),
			"tcperrsynsentbadackrate" => array (
				"cntrname"	=>	"tcperrsynsentbadackrate",
				"cntrdescr"	=>	"",
			),
			"tcperrrst" => Array(
				"cntrname"	=>	"tcperrrst",
				"cntrdescr"	=>	"RST packets received",
				"tooltip"	=>	"Reset packets received from a client or a server.",
			),
			"tcperrrstrate" => array (
				"cntrname"	=>	"tcperrrstrate",
				"cntrdescr"	=>	"",
			),
			"tcperrrstnonest" => Array(
				"cntrname"	=>	"tcperrrstnonest",
				"cntrdescr"	=>	"RST on not ESTABLISHED",
				"tooltip"	=>	"Reset packets received on a connection that is not in the ESTABLISHED state.",
			),
			"tcperrrstnonestrate" => array (
				"cntrname"	=>	"tcperrrstnonestrate",
				"cntrdescr"	=>	"",
			),
			"tcperrrstoutofwindow" => Array(
				"cntrname"	=>	"tcperrrstoutofwindow",
				"cntrdescr"	=>	"RST out of window",
				"tooltip"	=>	"Reset packets received on a connection that is out of the current TCP window.",
			),
			"tcperrrstoutofwindowrate" => array (
				"cntrname"	=>	"tcperrrstoutofwindowrate",
				"cntrdescr"	=>	"",
			),
			"tcperrrstintimewait" => Array(
				"cntrname"	=>	"tcperrrstintimewait",
				"cntrdescr"	=>	"RST in TIME_WAIT",
				"tooltip"	=>	"Reset packets received on a connection that is in the TIME_WAIT state. Packets cannot be transferred on a connection in the TIME_WAIT state.",
			),
			"tcperrrstintimewaitrate" => array (
				"cntrname"	=>	"tcperrrstintimewaitrate",
				"cntrdescr"	=>	"",
			),
			"tcperrsvroutoforder" => Array(
				"cntrname"	=>	"tcperrsvroutoforder",
				"cntrdescr"	=>	"Server out of order packets",
				"tooltip"	=>	"Out of order TCP packets received from a server.",
			),
			"tcperrsvroutoforderrate" => array (
				"cntrname"	=>	"tcperrsvroutoforderrate",
				"cntrdescr"	=>	"",
			),
			"tcperrcltoutoforder" => Array(
				"cntrname"	=>	"tcperrcltoutoforder",
				"cntrdescr"	=>	"Client out of order packets",
				"tooltip"	=>	"Out of order TCP packets received from a client.",
			),
			"tcperrcltoutoforderrate" => array (
				"cntrname"	=>	"tcperrcltoutoforderrate",
				"cntrdescr"	=>	"",
			),
			"tcperrclthole" => Array(
				"cntrname"	=>	"tcperrclthole",
				"cntrdescr"	=>	"TCP hole on client connection",
				"tooltip"	=>	"TCP holes created on a client connection. When out of order packets are received from a client, a hole is created on the NetScaler for each group of missing packets.",
			),
			"tcperrcltholerate" => array (
				"cntrname"	=>	"tcperrcltholerate",
				"cntrdescr"	=>	"",
			),
			"tcperrsvrhole" => Array(
				"cntrname"	=>	"tcperrsvrhole",
				"cntrdescr"	=>	"TCP hole on server connection",
				"tooltip"	=>	"TCP holes created on a server connection. When out of order packets are received from a server, a hole is created on the NetScaler for each group of missing packets.",
			),
			"tcperrsvrholerate" => array (
				"cntrname"	=>	"tcperrsvrholerate",
				"cntrdescr"	=>	"",
			),
			"tcperrcookiepktseqreject" => Array(
				"cntrname"	=>	"tcperrcookiepktseqreject",
				"cntrdescr"	=>	"Seq number SYN cookie reject",
				"tooltip"	=>	"SYN cookie packets rejected because they contain an incorrect sequence number.",
			),
			"tcperrcookiepktseqrejectrate" => array (
				"cntrname"	=>	"tcperrcookiepktseqrejectrate",
				"cntrdescr"	=>	"",
			),
			"tcperrcookiepktsigreject" => Array(
				"cntrname"	=>	"tcperrcookiepktsigreject",
				"cntrdescr"	=>	"Signature SYN cookie reject",
				"tooltip"	=>	"SYN cookie packets rejected because they contain an incorrect signature.",
			),
			"tcperrcookiepktsigrejectrate" => array (
				"cntrname"	=>	"tcperrcookiepktsigrejectrate",
				"cntrdescr"	=>	"",
			),
			"tcperrcookiepktseqdrop" => Array(
				"cntrname"	=>	"tcperrcookiepktseqdrop",
				"cntrdescr"	=>	"Seq number SYN cookie drop",
				"tooltip"	=>	"SYN cookie packets dropped because the sequence number specified in the packets is outside the current window.",
			),
			"tcperrcookiepktseqdroprate" => array (
				"cntrname"	=>	"tcperrcookiepktseqdroprate",
				"cntrdescr"	=>	"",
			),
			"tcperrcookiepktmssreject" => Array(
				"cntrname"	=>	"tcperrcookiepktmssreject",
				"cntrdescr"	=>	"MSS SYN cookie reject",
				"tooltip"	=>	"SYN cookie packets rejected because the maximum segment size (MSS) specified in the packets is incorrect.",
			),
			"tcperrcookiepktmssrejectrate" => array (
				"cntrname"	=>	"tcperrcookiepktmssrejectrate",
				"cntrdescr"	=>	"",
			),
			"tcperranyportfail" => Array(
				"cntrname"	=>	"tcperranyportfail",
				"cntrdescr"	=>	"Any IP port allocation failure",
				"tooltip"	=>	"Port allocations that have failed on a mapped IP address because the maximum limit of 65536 has been exceeded.",
			),
			"tcperranyportfailrate" => array (
				"cntrname"	=>	"tcperranyportfailrate",
				"cntrdescr"	=>	"",
			),
			"tcperripportfail" => Array(
				"cntrname"	=>	"tcperripportfail",
				"cntrdescr"	=>	"IP port allocation failure",
				"tooltip"	=>	"Port allocations that have failed on a subnet IP address or vserver IP address because the maximum limit of 65536 has been exceeded.",
			),
			"tcperripportfailrate" => array (
				"cntrname"	=>	"tcperripportfailrate",
				"cntrdescr"	=>	"",
			),
			"tcperrstraypkt" => Array(
				"cntrname"	=>	"tcperrstraypkt",
				"cntrdescr"	=>	"Stray packets",
				"tooltip"	=>	"Number of stray or misrouted packets.",
			),
			"tcperrstraypktrate" => array (
				"cntrname"	=>	"tcperrstraypktrate",
				"cntrdescr"	=>	"",
			),
			"tcperrsentrst" => Array(
				"cntrname"	=>	"tcperrsentrst",
				"cntrdescr"	=>	"RST packets sent",
				"tooltip"	=>	"Reset packets sent to a client or a server.",
			),
			"tcperrsentrstrate" => array (
				"cntrname"	=>	"tcperrsentrstrate",
				"cntrdescr"	=>	"",
			),
			"tcperrbadstateconn" => Array(
				"cntrname"	=>	"tcperrbadstateconn",
				"cntrdescr"	=>	"Bad state connections",
				"tooltip"	=>	"Connections that are not in a valid TCP state.",
			),
			"tcperrbadstateconnrate" => array (
				"cntrname"	=>	"tcperrbadstateconnrate",
				"cntrdescr"	=>	"",
			),
			"tcperrrstthreshold" => Array(
				"cntrname"	=>	"tcperrrstthreshold",
				"cntrdescr"	=>	"RST threshold dropped",
				"tooltip"	=>	"Reset packets dropped because the default threshold of 100 resets per 10 milliseconds has been exceeded. This is a configurable value using the set rateControl command.",
			),
			"tcperrrstthresholdrate" => array (
				"cntrname"	=>	"tcperrrstthresholdrate",
				"cntrdescr"	=>	"",
			),
			"tcperroutofwindowpkts" => Array(
				"cntrname"	=>	"tcperroutofwindowpkts",
				"cntrdescr"	=>	"Packets out of window",
				"tooltip"	=>	"Packets received that are out of the current advertised window.",
			),
			"tcperroutofwindowpktsrate" => array (
				"cntrname"	=>	"tcperroutofwindowpktsrate",
				"cntrdescr"	=>	"",
			),
			"tcperrsyndroppedcongestion" => Array(
				"cntrname"	=>	"tcperrsyndroppedcongestion",
				"cntrdescr"	=>	"SYNs dropped (Congestion)",
				"tooltip"	=>	"SYN packets dropped because of network congestion.",
			),
			"tcperrsyndroppedcongestionrate" => array (
				"cntrname"	=>	"tcperrsyndroppedcongestionrate",
				"cntrdescr"	=>	"",
			),
			"tcperrcltretrasmit" => Array(
				"cntrname"	=>	"tcperrcltretrasmit",
				"cntrdescr"	=>	"Client retransmissions",
				"tooltip"	=>	"Packets retransmitted by a client. This usually occurs because the acknowledgement from the NetScaler has not reached the client.",
			),
			"tcperrcltretrasmitrate" => array (
				"cntrname"	=>	"tcperrcltretrasmitrate",
				"cntrdescr"	=>	"",
			),
			"tcperrfullretrasmit" => Array(
				"cntrname"	=>	"tcperrfullretrasmit",
				"cntrdescr"	=>	"Full packet retransmissions",
				"tooltip"	=>	"Full packets retransmitted by the client or the server.",
			),
			"tcperrfullretrasmitrate" => array (
				"cntrname"	=>	"tcperrfullretrasmitrate",
				"cntrdescr"	=>	"",
			),
			"tcperrsynretry" => Array(
				"cntrname"	=>	"tcperrsynretry",
				"cntrdescr"	=>	"SYN packet retries",
				"tooltip"	=>	"SYN packets resent to a server.",
			),
			"tcperrsynretryrate" => array (
				"cntrname"	=>	"tcperrsynretryrate",
				"cntrdescr"	=>	"",
			),
			"tcperrsyngiveup" => Array(
				"cntrname"	=>	"tcperrsyngiveup",
				"cntrdescr"	=>	"SYN packets timeout",
				"tooltip"	=>	"Attempts to establish a connection on the NetScaler that timed out.",
			),
			"tcperrsyngiveuprate" => array (
				"cntrname"	=>	"tcperrsyngiveuprate",
				"cntrdescr"	=>	"",
			),
			"tcperrretransmit" => Array(
				"cntrname"	=>	"tcperrretransmit",
				"cntrdescr"	=>	"TCP retransmission",
				"tooltip"	=>	"TCP packets retransmitted. The NetScaler attempts to retransmit the packet up to seven times, after which it resets the other half of the TCP connection.",
			),
			"tcperrretransmitrate" => array (
				"cntrname"	=>	"tcperrretransmitrate",
				"cntrdescr"	=>	"",
			),
			"tcperrfirstretransmissions" => Array(
				"cntrname"	=>	"tcperrfirstretransmissions",
				"cntrdescr"	=>	"1st retransmission",
				"tooltip"	=>	"Packets retransmitted once by the NetScaler.",
			),
			"tcperrfirstretransmissionsrate" => array (
				"cntrname"	=>	"tcperrfirstretransmissionsrate",
				"cntrdescr"	=>	"",
			),
			"tcperrthirdretransmissions" => Array(
				"cntrname"	=>	"tcperrthirdretransmissions",
				"cntrdescr"	=>	"3rd retransmission",
				"tooltip"	=>	"Packets retransmitted three times by the NetScaler.",
			),
			"tcperrthirdretransmissionsrate" => array (
				"cntrname"	=>	"tcperrthirdretransmissionsrate",
				"cntrdescr"	=>	"",
			),
			"tcperrfifthretransmissions" => Array(
				"cntrname"	=>	"tcperrfifthretransmissions",
				"cntrdescr"	=>	"5th retransmission",
				"tooltip"	=>	"Packets retransmitted five times by the NetScaler.",
			),
			"tcperrfifthretransmissionsrate" => array (
				"cntrname"	=>	"tcperrfifthretransmissionsrate",
				"cntrdescr"	=>	"",
			),
			"tcperrseventhretransmissions" => Array(
				"cntrname"	=>	"tcperrseventhretransmissions",
				"cntrdescr"	=>	"7th retransmission",
				"tooltip"	=>	"Packets retransmitted seven times by the NetScaler. If this fails, the NetScaler terminates the connection.",
			),
			"tcperrseventhretransmissionsrate" => array (
				"cntrname"	=>	"tcperrseventhretransmissionsrate",
				"cntrdescr"	=>	"",
			),
			"tcperrfastretransmissions" => Array(
				"cntrname"	=>	"tcperrfastretransmissions",
				"cntrdescr"	=>	"Fast retransmits",
				"tooltip"	=>	"TCP packets on which the NetScaler performs a fast retransmission in response to three duplicate acknowledgements or a partial acknowledgement.  The NetScaler assumes that the packet is lost and retransmits the packet before its time-out.",
			),
			"tcperrfastretransmissionsrate" => array (
				"cntrname"	=>	"tcperrfastretransmissionsrate",
				"cntrdescr"	=>	"",
			),
			"tcperrsvrretrasmit" => Array(
				"cntrname"	=>	"tcperrsvrretrasmit",
				"cntrdescr"	=>	"Server retransmissions",
				"tooltip"	=>	"Packets retransmitted by a server. This usually occurs because the acknowledgement from the NetScaler has not reached the server.",
			),
			"tcperrsvrretrasmitrate" => array (
				"cntrname"	=>	"tcperrsvrretrasmitrate",
				"cntrdescr"	=>	"",
			),
			"tcperrpartialretrasmit" => Array(
				"cntrname"	=>	"tcperrpartialretrasmit",
				"cntrdescr"	=>	"Partial packet retransmissions",
				"tooltip"	=>	"Partial packet retransmits by a client or server due to congestion on the connection. This usually occurs because the window advertised by the NetScaler is not big enough to hold the full packet.",
			),
			"tcperrpartialretrasmitrate" => array (
				"cntrname"	=>	"tcperrpartialretrasmitrate",
				"cntrdescr"	=>	"",
			),
			"tcperrfinretry" => Array(
				"cntrname"	=>	"tcperrfinretry",
				"cntrdescr"	=>	"FIN packet retries",
				"tooltip"	=>	"FIN packets resent to a server or a client.",
			),
			"tcperrfinretryrate" => array (
				"cntrname"	=>	"tcperrfinretryrate",
				"cntrdescr"	=>	"",
			),
			"tcperrfingiveup" => Array(
				"cntrname"	=>	"tcperrfingiveup",
				"cntrdescr"	=>	"FIN packets timeout",
				"tooltip"	=>	"Connections that were timed out by the NetScaler because of not receiving the ACK packet after retransmitting the FIN packet four times.",
			),
			"tcperrfingiveuprate" => array (
				"cntrname"	=>	"tcperrfingiveuprate",
				"cntrdescr"	=>	"",
			),
			"tcperrsecondretransmissions" => Array(
				"cntrname"	=>	"tcperrsecondretransmissions",
				"cntrdescr"	=>	"2nd retransmission",
				"tooltip"	=>	"Packets retransmitted twice by the NetScaler.",
			),
			"tcperrsecondretransmissionsrate" => array (
				"cntrname"	=>	"tcperrsecondretransmissionsrate",
				"cntrdescr"	=>	"",
			),
			"tcperrforthretransmissions" => Array(
				"cntrname"	=>	"tcperrforthretransmissions",
				"cntrdescr"	=>	"4th retransmission",
				"tooltip"	=>	"Packets retransmitted four times by the NetScaler.",
			),
			"tcperrforthretransmissionsrate" => array (
				"cntrname"	=>	"tcperrforthretransmissionsrate",
				"cntrdescr"	=>	"",
			),
			"tcperrsixthretransmissions" => Array(
				"cntrname"	=>	"tcperrsixthretransmissions",
				"cntrdescr"	=>	"6th retransmission",
				"tooltip"	=>	"Packets retransmitted six times by the NetScaler.",
			),
			"tcperrsixthretransmissionsrate" => array (
				"cntrname"	=>	"tcperrsixthretransmissionsrate",
				"cntrdescr"	=>	"",
			),
			"tcperrretransmitgiveup" => Array(
				"cntrname"	=>	"tcperrretransmitgiveup",
				"cntrdescr"	=>	"TCP retransmission giveup",
				"tooltip"	=>	"Number of times NetScaler terminates a connection after retransmitting the packet seven times on that connection.Retrasnmission happens when recieving end doesn't acknowledges the packet.",
			),
			"tcperrretransmitgiveuprate" => array (
				"cntrname"	=>	"tcperrretransmitgiveuprate",
				"cntrdescr"	=>	"",
			),
			"tcperrcipalloc" => Array(
				"cntrname"	=>	"tcperrcipalloc",
				"cntrdescr"	=>	"TCP level cip failure",
				"tooltip"	=>	"Number of times TCP level client header insertion failure",
			),
			"tcperrcipallocrate" => array (
				"cntrname"	=>	"tcperrcipallocrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"text" => "TCP and Connections Statistics",
				"flag" => "Summary",
				"key" => "default",
			),
			Array(
				"dhID"	=>	1,
				"title"	=>	"System",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("tcptotrxpkts","tcptotrxbytes","tcptottxpkts","tcptottxbytes",),
			),
			Array(
				"text" => "TCP and Connections Statistics",
				"flag" => "Detail",
				"key" => "detail",
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"",
				"header"	=>	array("Connections","Client","Server",),
				"format"	=>	"3C",
				"dispflag"	=>	"Detail",
				"counters1"	=>	array("tcpcurclientconn","tcpcurclientconnopening","tcpcurclientconnestablished","tcpcurclientconnclosing","tcptotclientconnopened","tcpclientconnopenedrate",),
				"counters2"	=>	array("tcpcurserverconn","tcpcurserverconnopening","tcpcurserverconnestablished","tcpcurserverconnclosing","tcptotserverconnopened","tcpserverconnopenedrate",),
				"charts"	=>	array(	array("tcp_client_connections","tcp_server_connections",),
								),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"",
				"header"	=>	array("Zombie connections","Client","Server",),
				"format"	=>	"3C",
				"dispflag"	=>	"Detail",
				"counters1"	=>	array("tcptotzombiecltconnflushed","tcptotzombiehalfopencltconnflushed","tcptotzombieactivehalfclosecltconnflushed","tcptotzombiepassivehalfclosecltconnflushed",),
				"counters2"	=>	array("tcptotzombiesvrconnflushed","tcptotzombiehalfopensvrconnflushed","tcptotzombieactivehalfclosesvrconnflushed","tcptotzombiepassivehalfclosesrvconnflushed",),
			),
			Array(
				"dhID"	=>	4,
				"title"	=>	"",
				"header"	=>	array("","Total",),
				"format"	=>	"1C",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("pcbtotzombiecall",),
			),
			Array(
				"dhID"	=>	5,
				"title"	=>	"TCP statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("tcptotrxpkts","tcptotrxbytes","tcptottxpkts","tcptottxbytes","tcptotsyn","tcptotsynprobe","tcptotsvrfin","tcptotcltfin","tcpwaittosyn","tcpwaittodata","tcptotsynheld","tcptotsynflush","tcptotfinwaitclosed",),
			),
			Array(
				"dhID"	=>	6,
				"title"	=>	"TCP errors",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("tcperrbadchecksum","tcperrdataafterfin","tcperrsyninsynrcvd","tcperrsyninest","tcperrsynsentbadack","tcperrrst","tcperrrstnonest","tcperrrstoutofwindow","tcperrrstintimewait","tcperrsvroutoforder","tcperrcltoutoforder","tcperrclthole","tcperrsvrhole","tcperrcookiepktseqreject","tcperrcookiepktsigreject","tcperrcookiepktseqdrop","tcperrcookiepktmssreject","tcperranyportfail","tcperripportfail","tcperrstraypkt","tcperrsentrst","tcperrbadstateconn","tcperrrstthreshold","tcperroutofwindowpkts","tcperrsyndroppedcongestion",),
			),
			array (
			"format"	=>	"2C",
				"title"	=>	"",
			"column1" => 			Array(
				"dhID"	=>	7,
				"title"	=>	"",
				"header"	=>	array(),
				"dispflag"	=>	"Detail",
				"counters"	=>	array("tcperrcltretrasmit","tcperrfullretrasmit","tcperrsynretry","tcperrsyngiveup","tcperrretransmit","tcperrfirstretransmissions","tcperrthirdretransmissions","tcperrfifthretransmissions","tcperrseventhretransmissions","tcperrfastretransmissions",),
			),
			"column2" => 			Array(
				"dhID"	=>	8,
				"title"	=>	"",
				"header"	=>	array(),
				"dispflag"	=>	"Detail",
				"counters"	=>	array("tcperrsvrretrasmit","tcperrpartialretrasmit","tcperrfinretry","tcperrfingiveup","tcperrretransmitrate","tcperrsecondretransmissions","tcperrforthretransmissions","tcperrsixthretransmissions","tcperrretransmitgiveup","tcperrcipalloc",),
			),
			),
		),
),


"statprotocolhttp" => Array(
	"cmddispname"		=>	"statprotocolhttp",
	"cmdname"		=>	"statprotocolhttp",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"spdytotstreams" => Array(
				"cntrname"	=>	"spdytotstreams",
				"cntrdescr"	=>	"Total SPDY requests",
				"tooltip"	=>	"Total number of requests received over SPDYv2 and SPDYv3",
			),
			"spdystreamsrate" => array (
				"cntrname"	=>	"spdystreamsrate",
				"cntrdescr"	=>	"",
			),
			"httptotrequests" => Array(
				"cntrname"	=>	"httptotrequests",
				"cntrdescr"	=>	"Total requests",
				"tooltip"	=>	"Total number of HTTP requests received.",
			),
			"httprequestsrate" => array (
				"cntrname"	=>	"httprequestsrate",
				"cntrdescr"	=>	"",
			),
			"httptotresponses" => Array(
				"cntrname"	=>	"httptotresponses",
				"cntrdescr"	=>	"Total responses",
				"tooltip"	=>	"Total number of HTTP responses sent.",
			),
			"httpresponsesrate" => array (
				"cntrname"	=>	"httpresponsesrate",
				"cntrdescr"	=>	"",
			),
			"httptotrxrequestbytes" => Array(
				"cntrname"	=>	"httptotrxrequestbytes",
				"cntrdescr"	=>	"Request bytes received",
				"tooltip"	=>	"Total number of bytes of HTTP request data received.",
			),
			"httprxrequestbytesrate" => array (
				"cntrname"	=>	"httprxrequestbytesrate",
				"cntrdescr"	=>	"",
			),
			"httptotrxresponsebytes" => Array(
				"cntrname"	=>	"httptotrxresponsebytes",
				"cntrdescr"	=>	"Response bytes received",
				"tooltip"	=>	"Total number of bytes of HTTP response data received.",
			),
			"httprxresponsebytesrate" => array (
				"cntrname"	=>	"httprxresponsebytesrate",
				"cntrdescr"	=>	"",
			),
			"httptotgets" => Array(
				"cntrname"	=>	"httptotgets",
				"cntrdescr"	=>	"GETs",
				"tooltip"	=>	"Total number of HTTP requests received with the GET method.",
			),
			"httpgetsrate" => array (
				"cntrname"	=>	"httpgetsrate",
				"cntrdescr"	=>	"",
			),
			"httptotposts" => Array(
				"cntrname"	=>	"httptotposts",
				"cntrdescr"	=>	"POSTs",
				"tooltip"	=>	"Total number of HTTP requests received with the POST method.",
			),
			"httppostsrate" => array (
				"cntrname"	=>	"httppostsrate",
				"cntrdescr"	=>	"",
			),
			"httptotothers" => Array(
				"cntrname"	=>	"httptotothers",
				"cntrdescr"	=>	"Other methods",
				"tooltip"	=>	"Total number of HTTP requests received with methods other than GET and POST. Some of the other well-defined HTTP methods are HEAD, PUT, DELETE, OPTIONS, and TRACE. User-defined methods are also allowed.",
			),
			"httpothersrate" => array (
				"cntrname"	=>	"httpothersrate",
				"cntrdescr"	=>	"",
			),
			"httptot10requests" => Array(
				"cntrname"	=>	"httptot10requests",
				"cntrdescr"	=>	"HTTP/1.0 requests",
				"tooltip"	=>	"Total number of HTTP/1.0 requests received.",
			),
			"http10requestsrate" => array (
				"cntrname"	=>	"http10requestsrate",
				"cntrdescr"	=>	"",
			),
			"httptot11requests" => Array(
				"cntrname"	=>	"httptot11requests",
				"cntrdescr"	=>	"HTTP/1.1 requests",
				"tooltip"	=>	"Total number of HTTP/1.1 requests received.",
			),
			"http11requestsrate" => array (
				"cntrname"	=>	"http11requestsrate",
				"cntrdescr"	=>	"",
			),
			"httptotclenrequests" => Array(
				"cntrname"	=>	"httptotclenrequests",
				"cntrdescr"	=>	"Content-length requests",
				"tooltip"	=>	"Total number of HTTP requests in which the Content-length field of the HTTP header has been set. Content-length specifies the length of the content, in bytes, in the associated HTTP body.",
			),
			"httpclenrequestsrate" => array (
				"cntrname"	=>	"httpclenrequestsrate",
				"cntrdescr"	=>	"",
			),
			"httptotchunkedrequests" => Array(
				"cntrname"	=>	"httptotchunkedrequests",
				"cntrdescr"	=>	"Chunked requests",
				"tooltip"	=>	"Total number of HTTP requests in which the Transfer-Encoding field of the HTTP header has been set to chunked.",
			),
			"httpchunkedrequestsrate" => array (
				"cntrname"	=>	"httpchunkedrequestsrate",
				"cntrdescr"	=>	"",
			),
			"httptottxrequestbytes" => Array(
				"cntrname"	=>	"httptottxrequestbytes",
				"cntrdescr"	=>	"Request bytes transmitted",
				"tooltip"	=>	"Total number of bytes of HTTP request data transmitted.",
			),
			"httptxrequestbytesrate" => array (
				"cntrname"	=>	"httptxrequestbytesrate",
				"cntrdescr"	=>	"",
			),
			"httptot10responses" => Array(
				"cntrname"	=>	"httptot10responses",
				"cntrdescr"	=>	"HTTP/1.0 responses",
				"tooltip"	=>	"Total number of HTTP/1.0 responses sent.",
			),
			"http10responsesrate" => array (
				"cntrname"	=>	"http10responsesrate",
				"cntrdescr"	=>	"",
			),
			"httptot11responses" => Array(
				"cntrname"	=>	"httptot11responses",
				"cntrdescr"	=>	"HTTP/1.1 responses",
				"tooltip"	=>	"Total number of HTTP/1.1 responses sent.",
			),
			"http11responsesrate" => array (
				"cntrname"	=>	"http11responsesrate",
				"cntrdescr"	=>	"",
			),
			"httptotclenresponses" => Array(
				"cntrname"	=>	"httptotclenresponses",
				"cntrdescr"	=>	"Content-length responses",
				"tooltip"	=>	"Total number of HTTP responses sent in which the Content-length field of the HTTP header has been set. Content-length specifies the length of the content, in bytes, in the associated HTTP body.",
			),
			"httpclenresponsesrate" => array (
				"cntrname"	=>	"httpclenresponsesrate",
				"cntrdescr"	=>	"",
			),
			"httptotchunkedresponses" => Array(
				"cntrname"	=>	"httptotchunkedresponses",
				"cntrdescr"	=>	"Chunked responses",
				"tooltip"	=>	"Total number of HTTP responses sent in which the Transfer-Encoding field of the HTTP header has been set to chunked. This setting is used when the server wants to start sending the response before knowing its total length. The server breaks the response into chunks and sends them in sequence, inserting the length of each chunk before the actual data. The message ends with a chunk of size zero.",
			),
			"httpchunkedresponsesrate" => array (
				"cntrname"	=>	"httpchunkedresponsesrate",
				"cntrdescr"	=>	"",
			),
			"httperrnoreusemultipart" => Array(
				"cntrname"	=>	"httperrnoreusemultipart",
				"cntrdescr"	=>	"Multi-part responses",
				"tooltip"	=>	"Total number of HTTP multi-part responses sent. In multi-part responses, one or more entities are encapsulated within the body of a single message.",
			),
			"httperrnoreusemultipartrate" => array (
				"cntrname"	=>	"httperrnoreusemultipartrate",
				"cntrdescr"	=>	"",
			),
			"httptotnoclenchunkresponses" => Array(
				"cntrname"	=>	"httptotnoclenchunkresponses",
				"cntrdescr"	=>	"FIN-terminated responses",
				"tooltip"	=>	"Total number of FIN-terminated responses sent. In FIN-terminated responses, the server finishes sending the data and closes the connection.",
			),
			"httpnoclenchunkresponsesrate" => array (
				"cntrname"	=>	"httpnoclenchunkresponsesrate",
				"cntrdescr"	=>	"",
			),
			"httptottxresponsebytes" => Array(
				"cntrname"	=>	"httptottxresponsebytes",
				"cntrdescr"	=>	"Response bytes transmitted",
				"tooltip"	=>	"Total number of bytes of HTTP response data transmitted.",
			),
			"httptxresponsebytesrate" => array (
				"cntrname"	=>	"httptxresponsebytesrate",
				"cntrdescr"	=>	"",
			),
			"httperrincompleteheaders" => Array(
				"cntrname"	=>	"httperrincompleteheaders",
				"cntrdescr"	=>	"Incomplete headers",
				"tooltip"	=>	"Total number of HTTP requests and responses received in which the HTTP header spans more than one packet.",
			),
			"httperrincompleterequests" => Array(
				"cntrname"	=>	"httperrincompleterequests",
				"cntrdescr"	=>	"Incomplete request headers",
				"tooltip"	=>	"Total number of HTTP requests received in which the header spans more than one packet.",
			),
			"httperrincompleterequestsrate" => array (
				"cntrname"	=>	"httperrincompleterequestsrate",
				"cntrdescr"	=>	"",
			),
			"httperrincompleteresponses" => Array(
				"cntrname"	=>	"httperrincompleteresponses",
				"cntrdescr"	=>	"Incomplete response headers",
				"tooltip"	=>	"Total number of HTTP responses received in which the header spans more than one packet.",
			),
			"httperrincompleteresponsesrate" => array (
				"cntrname"	=>	"httperrincompleteresponsesrate",
				"cntrdescr"	=>	"",
			),
			"httperrserverbusy" => Array(
				"cntrname"	=>	"httperrserverbusy",
				"cntrdescr"	=>	"HTTP 500 Server-busy Responses",
				"tooltip"	=>	"Total number of HTTP error responses received. Some of the error responses are: <br><br>500 	Internal Server Error<br><br>501 	Not Implemented<br><br>502 	Bad Gateway<br><br>503 	Service Unavailable<br><br>504 	Gateway Timeout<br><br>505 	HTTP Version Not Supported",
			),
			"httperrserverbusyrate" => array (
				"cntrname"	=>	"httperrserverbusyrate",
				"cntrdescr"	=>	"",
			),
			"httperrlargecontent" => Array(
				"cntrname"	=>	"httperrlargecontent",
				"cntrdescr"	=>	"Large/Invalid messages",
				"tooltip"	=>	"Total number of requests and responses received with large body.",
			),
			"httperrlargechunk" => Array(
				"cntrname"	=>	"httperrlargechunk",
				"cntrdescr"	=>	"Large/Invalid chunk requests",
				"tooltip"	=>	"Total number of requests received with large chunk size, in which the Transfer-Encoding field of the HTTP header has been set to chunked.",
			),
			"httperrlargectlen" => Array(
				"cntrname"	=>	"httperrlargectlen",
				"cntrdescr"	=>	"Large/Invalid content-length",
				"tooltip"	=>	"Total number of requests received with large content, in which the Content-length field of the HTTP header has been set. Content-length specifies the length of the content, in bytes, in the associated HTTP body.",
			),
			"spdyv2totstreams" => Array(
				"cntrname"	=>	"spdyv2totstreams",
				"cntrdescr"	=>	"SPDYv2 requests",
				"tooltip"	=>	"Total number of requests received over SPDYv2",
			),
			"spdyv2streamsrate" => array (
				"cntrname"	=>	"spdyv2streamsrate",
				"cntrdescr"	=>	"",
			),
			"spdyv3totstreams" => Array(
				"cntrname"	=>	"spdyv3totstreams",
				"cntrdescr"	=>	"SPDYv3 requests",
				"tooltip"	=>	"Total number of requests received over SPDYv3",
			),
			"spdyv3streamsrate" => array (
				"cntrname"	=>	"spdyv3streamsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"HTTP Statistics - Summary",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("httptotrequests","httptotresponses","httptotrxrequestbytes","httptotrxresponsebytes",),
			),
			Array(
				"text" => "HTTP Statistics",
				"flag" => "Detail",
				"key" => "detail",
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Requests:",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("httptotrequests","httptotgets","httptotposts","httptotothers",),
				"charts"	=>	array(	array("http_method_requests",),
								),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"",
				"header"	=>	array(),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("httptot10requests","httptot11requests",),
				"charts"	=>	array(	array("http_version_requests",),
								),
			),
			Array(
				"dhID"	=>	4,
				"title"	=>	"",
				"header"	=>	array(),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("httptotclenrequests","httptotchunkedrequests",),
				"charts"	=>	array(	array("http_length_requests",),
								),
			),
			Array(
				"dhID"	=>	5,
				"title"	=>	"",
				"header"	=>	array(),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("httptotrxrequestbytes","httptottxrequestbytes",),
			),
			Array(
				"dhID"	=>	6,
				"title"	=>	"Responses:",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("httptotresponses",),
			),
			Array(
				"dhID"	=>	7,
				"title"	=>	"",
				"header"	=>	array(),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("httptot10responses","httptot11responses",),
				"charts"	=>	array(	array("http_version_responses",),
								),
			),
			Array(
				"dhID"	=>	8,
				"title"	=>	"",
				"header"	=>	array(),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("httptotclenresponses","httptotchunkedresponses","httperrnoreusemultipart","httptotnoclenchunkresponses",),
				"charts"	=>	array(	array("http_length_responses",),
								),
			),
			Array(
				"dhID"	=>	9,
				"title"	=>	"",
				"header"	=>	array(),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("httptotrxresponsebytes","httptottxresponsebytes",),
			),
			Array(
				"dhID"	=>	10,
				"title"	=>	"Errors:",
				"header"	=>	array("","Total",),
				"format"	=>	"1C",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("httperrincompleteheaders","httperrincompleterequests","httperrincompleteresponses","httperrserverbusy",),
			),
			Array(
				"dhID"	=>	11,
				"title"	=>	"",
				"header"	=>	array(),
				"format"	=>	"1C",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("httperrlargecontent","httperrlargechunk","httperrlargectlen",),
			),
			Array(
				"dhID"	=>	12,
				"title"	=>	"SPDY:",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("spdytotstreams","spdyv2totstreams","spdyv3totstreams",),
			),
		),
),


"statprotocolicmp" => Array(
	"cmddispname"		=>	"statprotocolicmp",
	"cmdname"		=>	"statprotocolicmp",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"icmptotrxpkts" => Array(
				"cntrname"	=>	"icmptotrxpkts",
				"cntrdescr"	=>	"ICMP packets received",
				"tooltip"	=>	"ICMP packets received.",
			),
			"icmprxpktsrate" => array (
				"cntrname"	=>	"icmprxpktsrate",
				"cntrdescr"	=>	"",
			),
			"icmptotrxbytes" => Array(
				"cntrname"	=>	"icmptotrxbytes",
				"cntrdescr"	=>	"ICMP bytes received",
				"tooltip"	=>	"Bytes of ICMP data received.",
			),
			"icmprxbytesrate" => array (
				"cntrname"	=>	"icmprxbytesrate",
				"cntrdescr"	=>	"",
			),
			"icmptottxpkts" => Array(
				"cntrname"	=>	"icmptottxpkts",
				"cntrdescr"	=>	"ICMP packets transmitted",
				"tooltip"	=>	"ICMP packets transmitted.",
			),
			"icmptxpktsrate" => array (
				"cntrname"	=>	"icmptxpktsrate",
				"cntrdescr"	=>	"",
			),
			"icmptottxbytes" => Array(
				"cntrname"	=>	"icmptottxbytes",
				"cntrdescr"	=>	"ICMP bytes transmitted",
				"tooltip"	=>	"Bytes of ICMP data transmitted.",
			),
			"icmptxbytesrate" => array (
				"cntrname"	=>	"icmptxbytesrate",
				"cntrdescr"	=>	"",
			),
			"icmptotrxechoreply" => Array(
				"cntrname"	=>	"icmptotrxechoreply",
				"cntrdescr"	=>	"ICMP echo replies received",
				"tooltip"	=>	"ICMP Ping echo replies received.",
			),
			"icmprxechoreplyrate" => array (
				"cntrname"	=>	"icmprxechoreplyrate",
				"cntrdescr"	=>	"",
			),
			"icmptottxechoreply" => Array(
				"cntrname"	=>	"icmptottxechoreply",
				"cntrdescr"	=>	"ICMP echo replies transmitted",
				"tooltip"	=>	"ICMP Ping echo replies transmitted.",
			),
			"icmptxechoreplyrate" => array (
				"cntrname"	=>	"icmptxechoreplyrate",
				"cntrdescr"	=>	"",
			),
			"icmptotrxecho" => Array(
				"cntrname"	=>	"icmptotrxecho",
				"cntrdescr"	=>	"ICMP echos received",
				"tooltip"	=>	"ICMP Ping Echo Request and Echo Reply packets received.",
			),
			"icmprxechorate" => array (
				"cntrname"	=>	"icmprxechorate",
				"cntrdescr"	=>	"",
			),
			"icmptotdstiplookup" => Array(
				"cntrname"	=>	"icmptotdstiplookup",
				"cntrdescr"	=>	"MTU lookup on dst ip info recvd",
				"tooltip"	=>	"Total number of MTU lookup on destination IP info received on a need fragmentation ICMP error message failed.",
			),
			"icmpcurratethreshold" => Array(
				"cntrname"	=>	"icmpcurratethreshold",
				"cntrdescr"	=>	"ICMP rate threshold (pkts/sec)",
				"tooltip"	=>	"Limit for ICMP packets handled every 10 milliseconds. Default value, 0, applies no limit.<br><br>This is a configurable value using the set rateControl command.",
			),
			"icmptotportunreachablerx" => Array(
				"cntrname"	=>	"icmptotportunreachablerx",
				"cntrdescr"	=>	"ICMP port unreachable received",
				"tooltip"	=>	"ICMP Port Unreachable error messages received. This error is generated when there is no service is running on the port.",
			),
			"icmptotportunreachabletx" => Array(
				"cntrname"	=>	"icmptotportunreachabletx",
				"cntrdescr"	=>	"ICMP port unreachable generated",
				"tooltip"	=>	"ICMP Port Unreachable error messages generated. This error is generated when there is no service is running on the port.",
			),
			"icmptotneedfragrx" => Array(
				"cntrname"	=>	"icmptotneedfragrx",
				"cntrdescr"	=>	"Need fragmentation received",
				"tooltip"	=>	"ICMP Fragmentation Needed error messages received for packets that need to be fragmented but for which Don't Fragment is specified the header.",
			),
			"icmptotthresholdexceeds" => Array(
				"cntrname"	=>	"icmptotthresholdexceeds",
				"cntrdescr"	=>	"ICMP rate threshold exceeded",
				"tooltip"	=>	"Times the ICMP rate threshold is exceeded. If this counter continuously increases, first make sure the ICMP packets received are genuine. If they are, increase the current rate threshold.",
			),
			"icmptotpktsdropped" => Array(
				"cntrname"	=>	"icmptotpktsdropped",
				"cntrdescr"	=>	"ICMP packets dropped",
				"tooltip"	=>	"ICMP packets dropped because the rate threshold has been exceeded.",
			),
			"icmptotbadchecksum" => Array(
				"cntrname"	=>	"icmptotbadchecksum",
				"cntrdescr"	=>	"Bad ICMP checksum",
				"tooltip"	=>	"ICMP Fragmentation Needed error messages received with an ICMP checksum error.",
			),
			"icmptotnonfirstipfrag" => Array(
				"cntrname"	=>	"icmptotnonfirstipfrag",
				"cntrdescr"	=>	"PMTU non-first IP fragments",
				"tooltip"	=>	"ICMP Fragmentation Needed error messages received that were generated by an IP fragment other than the first one.",
			),
			"icmptotinvalidbodylen" => Array(
				"cntrname"	=>	"icmptotinvalidbodylen",
				"cntrdescr"	=>	"PMTU Invalid body len received",
				"tooltip"	=>	"ICMP Fragmentation Needed error messages received that specified an invalid body length.",
			),
			"icmptotnotcpconn" => Array(
				"cntrname"	=>	"icmptotnotcpconn",
				"cntrdescr"	=>	"PMTU no tcp connection",
				"tooltip"	=>	"ICMP Need Fragmentation error messages received for TCP packets. The state of the connection for these packets is not maintained on the NetScaler.",
			),
			"icmptotnoudpconn" => Array(
				"cntrname"	=>	"icmptotnoudpconn",
				"cntrdescr"	=>	"PMTU no udp conection",
				"tooltip"	=>	"ICMP Need Fragmentation error messages received for UDP packets. The state of the connection for these packets is not maintained on the NetScaler.",
			),
			"icmptotinvalidtcpseqno" => Array(
				"cntrname"	=>	"icmptotinvalidtcpseqno",
				"cntrdescr"	=>	"PMTU invalid tcp seqno recvd",
				"tooltip"	=>	"ICMP Fragmentation Needed error messages received for packets that contain an invalid TCP address.",
			),
			"icmptotinvalidnextmtuval" => Array(
				"cntrname"	=>	"icmptotinvalidnextmtuval",
				"cntrdescr"	=>	"Invalid next MTU value recvd",
				"tooltip"	=>	"ICMP Fragmentation Needed error messages received in which the Maximum Transmission Unit (MTU) for the next hop is out of range. The range for the MTU is 576-1500.",
			),
			"icmptotbignextmtu" => Array(
				"cntrname"	=>	"icmptotbignextmtu",
				"cntrdescr"	=>	"Next MTU > Current MTU",
				"tooltip"	=>	"ICMP Fragmentation Needed error messages received in which the value for the next MTU is higher than that of the current MTU.",
			),
			"icmptotinvalidprotocol" => Array(
				"cntrname"	=>	"icmptotinvalidprotocol",
				"cntrdescr"	=>	"PMTU Invalid protocol recvd",
				"tooltip"	=>	"ICMP Fragmentation Needed error messages received that contain a protocol other than TCP and UDP.",
			),
			"icmptotbadpmtuipchecksum" => Array(
				"cntrname"	=>	"icmptotbadpmtuipchecksum",
				"cntrdescr"	=>	"PMTU IP check sum error",
				"tooltip"	=>	"ICMP Fragmentation Needed error messages received with an IP checksum error.",
			),
			"icmptotpmtunolink" => Array(
				"cntrname"	=>	"icmptotpmtunolink",
				"cntrdescr"	=>	"PMTU pcb with no link",
				"tooltip"	=>	"ICMP Fragmentation Needed error messages received on a Protocol Control Block (PCB) with no link. The PCB maintains the state of the connection.",
			),
			"icmptotpmtudiscoverydisabled" => Array(
				"cntrname"	=>	"icmptotpmtudiscoverydisabled",
				"cntrdescr"	=>	"PMTU Discovery not enabled",
				"tooltip"	=>	"ICMP Need Fragmentation error messages received when the PMTU Discovery mode is not enabled.",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"ICMP Summary:",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("icmptotrxpkts","icmptotrxbytes","icmptottxpkts","icmptottxbytes","icmptotrxechoreply","icmptottxechoreply","icmptotrxecho","icmptotdstiplookup",),
			),
			Array(
				"text" => "ICMP Statistics",
				"flag" => "Detail",
				"key" => "detail",
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Configuration :",
				"header"	=>	array(),
				"format"	=>	"1C",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("icmpcurratethreshold",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Run Time:",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("icmptotrxpkts","icmptotrxbytes","icmptottxpkts","icmptottxbytes","icmptotrxechoreply","icmptottxechoreply","icmptotrxecho","icmptotportunreachablerx","icmptotportunreachabletx","icmptotneedfragrx",),
			),
			array (
			"format"	=>	"2C",
				"title"	=>	"Errors:",
			"column1" => 			Array(
				"dhID"	=>	4,
				"title"	=>	"",
				"header"	=>	array(),
				"dispflag"	=>	"Detail",
				"counters"	=>	array("icmptotthresholdexceeds","icmptotpktsdropped","icmptotbadchecksum","icmptotnonfirstipfrag","icmptotinvalidbodylen","icmptotnotcpconn","icmptotnoudpconn",),
			),
			"column2" => 			Array(
				"dhID"	=>	5,
				"title"	=>	"",
				"header"	=>	array(),
				"dispflag"	=>	"Detail",
				"counters"	=>	array("icmptotinvalidtcpseqno","icmptotinvalidnextmtuval","icmptotbignextmtu","icmptotinvalidprotocol","icmptotbadpmtuipchecksum","icmptotpmtunolink","icmptotpmtudiscoverydisabled",),
			),
			),
		),
),


"statprotocolipv6" => Array(
	"cmddispname"		=>	"statprotocolipv6",
	"cmdname"		=>	"statprotocolipv6",
	"license"		=>	Array("NSKF1_IPV6_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"ipv6totrxpkts" => Array(
				"cntrname"	=>	"ipv6totrxpkts",
				"cntrdescr"	=>	"IPv6 packets received",
				"tooltip"	=>	"IPv6 packets received.",
			),
			"ipv6rxpktsrate" => array (
				"cntrname"	=>	"ipv6rxpktsrate",
				"cntrdescr"	=>	"",
			),
			"ipv6totrxbytes" => Array(
				"cntrname"	=>	"ipv6totrxbytes",
				"cntrdescr"	=>	"IPv6 bytes received",
				"tooltip"	=>	"Bytes of IPv6 data received.",
			),
			"ipv6rxbytesrate" => array (
				"cntrname"	=>	"ipv6rxbytesrate",
				"cntrdescr"	=>	"",
			),
			"ipv6tottxpkts" => Array(
				"cntrname"	=>	"ipv6tottxpkts",
				"cntrdescr"	=>	"IPv6 packets transmitted",
				"tooltip"	=>	"IPv6 packets transmitted",
			),
			"ipv6txpktsrate" => array (
				"cntrname"	=>	"ipv6txpktsrate",
				"cntrdescr"	=>	"",
			),
			"ipv6tottxbytes" => Array(
				"cntrname"	=>	"ipv6tottxbytes",
				"cntrdescr"	=>	"IPv6 bytes transmitted",
				"tooltip"	=>	"Bytes of IPv6 data transmitted.",
			),
			"ipv6txbytesrate" => array (
				"cntrname"	=>	"ipv6txbytesrate",
				"cntrdescr"	=>	"",
			),
			"ipv6totroutepkts" => Array(
				"cntrname"	=>	"ipv6totroutepkts",
				"cntrdescr"	=>	"Total Routed IPv6 packets",
				"tooltip"	=>	"IPv6 packets routed.",
			),
			"ipv6routepktsrate" => array (
				"cntrname"	=>	"ipv6routepktsrate",
				"cntrdescr"	=>	"",
			),
			"ipv6totroutembits" => Array(
				"cntrname"	=>	"ipv6totroutembits",
				"cntrdescr"	=>	"Total Routed IPv6 Mbits",
				"tooltip"	=>	"IPv6 total Mbits.",
			),
			"ipv6routembitsrate" => array (
				"cntrname"	=>	"ipv6routembitsrate",
				"cntrdescr"	=>	"",
			),
			"ipv6fragtotrxpkts" => Array(
				"cntrname"	=>	"ipv6fragtotrxpkts",
				"cntrdescr"	=>	"IPv6 Fragments received.",
				"tooltip"	=>	"IPv6 fragments received.",
			),
			"ipv6fragrxpktsrate" => array (
				"cntrname"	=>	"ipv6fragrxpktsrate",
				"cntrdescr"	=>	"",
			),
			"ipv6fragtottcpreassembled" => Array(
				"cntrname"	=>	"ipv6fragtottcpreassembled",
				"cntrdescr"	=>	"TCP Fragments reassembled.",
				"tooltip"	=>	"TCP fragments processed after reassembly.",
			),
			"ipv6fragtcpreassembledrate" => array (
				"cntrname"	=>	"ipv6fragtcpreassembledrate",
				"cntrdescr"	=>	"",
			),
			"ipv6fragtotudpreassembled" => Array(
				"cntrname"	=>	"ipv6fragtotudpreassembled",
				"cntrdescr"	=>	"UDP Fragments reassembled.",
				"tooltip"	=>	"UDP fragments processed after reassembly.",
			),
			"ipv6fragudpreassembledrate" => array (
				"cntrname"	=>	"ipv6fragudpreassembledrate",
				"cntrdescr"	=>	"",
			),
			"ipv6fragtotpktsprocessnoreass" => Array(
				"cntrname"	=>	"ipv6fragtotpktsprocessnoreass",
				"cntrdescr"	=>	"IPv6 Fragments processed without reassembly.",
				"tooltip"	=>	"IPv6 fragments processed without reassembly.",
			),
			"ipv6fragpktsprocessnoreassrate" => array (
				"cntrname"	=>	"ipv6fragpktsprocessnoreassrate",
				"cntrdescr"	=>	"",
			),
			"ipv6fragtotpktsforward" => Array(
				"cntrname"	=>	"ipv6fragtotpktsforward",
				"cntrdescr"	=>	"IPv6 Fragments bridged.",
				"tooltip"	=>	"IPv6 fragments forwarded to the client or server without reassembly.",
			),
			"ipv6fragpktsforwardrate" => array (
				"cntrname"	=>	"ipv6fragpktsforwardrate",
				"cntrdescr"	=>	"",
			),
			"ipv6errhdr" => Array(
				"cntrname"	=>	"ipv6errhdr",
				"cntrdescr"	=>	"IPv6 error hdr packets ",
				"tooltip"	=>	"Packets received that contain an error in one or more components of the IPv6 header.",
			),
			"ipv6nextheadererr" => Array(
				"cntrname"	=>	"ipv6nextheadererr",
				"cntrdescr"	=>	"IPv6 unsupported next header",
				"tooltip"	=>	"Packets received that contain an unsupported next header. The supported next headers are TCP, ICMP, UDP, OSPF, and FRAGMENT.",
			),
			"ipv6landattack" => Array(
				"cntrname"	=>	"ipv6landattack",
				"cntrdescr"	=>	"IPv6 Land-attacks",
				"tooltip"	=>	"Land-attack packets received. The source and destination addresses are the same. If not dropped, these packets can lock up the appliance.",
			),
			"ipv6fragpkttoobig" => Array(
				"cntrname"	=>	"ipv6fragpkttoobig",
				"cntrdescr"	=>	"Reassembled data too big",
				"tooltip"	=>	"Packets received for which the reassembled data exceeds the Ethernet packet data length of 1500 bytes.",
			),
			"ipv6fragzerolenpkt" => Array(
				"cntrname"	=>	"ipv6fragzerolenpkt",
				"cntrdescr"	=>	"Zero fragment length received",
				"tooltip"	=>	"Packets received with a fragment length of 0 bytes.",
			),
			"ipv6toticmpnarx" => Array(
				"cntrname"	=>	"ipv6toticmpnarx",
				"cntrdescr"	=>	"ICMPv6 NA packets received",
				"tooltip"	=>	"Number of ICMPv6 NA packets received by NetScaler (OBSOLETE).",
			),
			"ipv6icmpnarxrate" => array (
				"cntrname"	=>	"ipv6icmpnarxrate",
				"cntrdescr"	=>	"",
			),
			"ipv6toticmpnsrx" => Array(
				"cntrname"	=>	"ipv6toticmpnsrx",
				"cntrdescr"	=>	"ICMPv6 NS packets received",
				"tooltip"	=>	"Number of ICMPv6 NS packets received by NetScaler (OBSOLETE).",
			),
			"ipv6icmpnsrxrate" => array (
				"cntrname"	=>	"ipv6icmpnsrxrate",
				"cntrdescr"	=>	"",
			),
			"ipv6toticmpnatx" => Array(
				"cntrname"	=>	"ipv6toticmpnatx",
				"cntrdescr"	=>	"ICMPv6 NA packets transmitted",
				"tooltip"	=>	"Number of ICMPv6 NA packets transmitted by NetScaler (OBSOLETE).",
			),
			"ipv6icmpnatxrate" => array (
				"cntrname"	=>	"ipv6icmpnatxrate",
				"cntrdescr"	=>	"",
			),
			"ipv6toticmpnstx" => Array(
				"cntrname"	=>	"ipv6toticmpnstx",
				"cntrdescr"	=>	"ICMPv6 NS packets transmitted",
				"tooltip"	=>	"Number of ICMPv6 NS packets transmitted by NetScaler (OBSOLETE).",
			),
			"ipv6icmpnstxrate" => array (
				"cntrname"	=>	"ipv6icmpnstxrate",
				"cntrdescr"	=>	"",
			),
			"ipv6toticmprarx" => Array(
				"cntrname"	=>	"ipv6toticmprarx",
				"cntrdescr"	=>	"ICMPv6 RA packets received",
				"tooltip"	=>	"Number of ICMPv6 RA packets received by NetScaler (OBSOLETE).",
			),
			"ipv6icmprarxrate" => array (
				"cntrname"	=>	"ipv6icmprarxrate",
				"cntrdescr"	=>	"",
			),
			"ipv6toticmprstx" => Array(
				"cntrname"	=>	"ipv6toticmprstx",
				"cntrdescr"	=>	"ICMPv6 RS packets transmitted",
				"tooltip"	=>	"Number of ICMPv6 RS packets transmitted by NetScaler (OBSOLETE).",
			),
			"ipv6icmprstxrate" => array (
				"cntrname"	=>	"ipv6icmprstxrate",
				"cntrdescr"	=>	"",
			),
			"ipv6toticmprx" => Array(
				"cntrname"	=>	"ipv6toticmprx",
				"cntrdescr"	=>	"ICMPv6 packets received",
				"tooltip"	=>	"Number of ICMPv6 packets received by NetScaler (OBSOLETE).",
			),
			"ipv6icmprxrate" => array (
				"cntrname"	=>	"ipv6icmprxrate",
				"cntrdescr"	=>	"",
			),
			"ipv6toticmptx" => Array(
				"cntrname"	=>	"ipv6toticmptx",
				"cntrdescr"	=>	"ICMPv6 packets transmitted",
				"tooltip"	=>	"Number of ICMPv6 packets transmitted by NetScaler (OBSOLETE).",
			),
			"ipv6icmptxrate" => array (
				"cntrname"	=>	"ipv6icmptxrate",
				"cntrdescr"	=>	"",
			),
			"ipv6errrxhdr" => Array(
				"cntrname"	=>	"ipv6errrxhdr",
				"cntrdescr"	=>	"IPv6 error hdr packets",
				"tooltip"	=>	"Number of erroneous header packets received (OBSOLETE).",
			),
			"ipv6errrxpkt" => Array(
				"cntrname"	=>	"ipv6errrxpkt",
				"cntrdescr"	=>	"IPv6 error packets",
				"tooltip"	=>	"Number of erroneous packets received (OBSOLETE).",
			),
			"ipv6badcksum" => Array(
				"cntrname"	=>	"ipv6badcksum",
				"cntrdescr"	=>	"IPv6 bad checksum",
				"tooltip"	=>	"Number of bad checksum packets received (OBSOLETE).",
			),
			"icmpv6error" => Array(
				"cntrname"	=>	"icmpv6error",
				"cntrdescr"	=>	"ICMPv6 error packets",
				"tooltip"	=>	"Number of erroneous ICMPv6 packets received (OBSOLETE).",
			),
			"icmpv6unspt" => Array(
				"cntrname"	=>	"icmpv6unspt",
				"cntrdescr"	=>	"unsupported ICMPv6 packets",
				"tooltip"	=>	"Number of ICMPv6 unsupported packets received (OBSOLETE).",
			),
			"icmpv6rtthsld" => Array(
				"cntrname"	=>	"icmpv6rtthsld",
				"cntrdescr"	=>	"Rate threshold exceeded packets",
				"tooltip"	=>	"Number of ICMPv6 packets dropped for rate threshold exceeded (OBSOLETE).",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"IPV6 Statistics - Summary",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("ipv6totrxpkts","ipv6totrxbytes","ipv6tottxpkts","ipv6tottxbytes","ipv6totroutepkts","ipv6totroutembits",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("ipv6totrxpkts","ipv6totrxbytes","ipv6tottxpkts","ipv6tottxbytes","ipv6totroutepkts","ipv6totroutembits","ipv6fragtotrxpkts","ipv6fragtottcpreassembled","ipv6fragtotudpreassembled","ipv6fragtotpktsprocessnoreass","ipv6fragtotpktsforward",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Errors:",
				"header"	=>	array(),
				"format"	=>	"1C",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("ipv6errhdr","ipv6nextheadererr","ipv6landattack","ipv6fragpkttoobig","ipv6fragzerolenpkt",),
			),
		),
),


"statprotocolicmpv6" => Array(
	"cmddispname"		=>	"statprotocolicmpv6",
	"cmdname"		=>	"statprotocolicmpv6",
	"license"		=>	Array("NSKF1_IPV6_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"icmpv6totrxpkts" => Array(
				"cntrname"	=>	"icmpv6totrxpkts",
				"cntrdescr"	=>	"ICMPv6 packets received",
				"tooltip"	=>	"ICMPv6 packets received.",
			),
			"icmpv6rxpktsrate" => array (
				"cntrname"	=>	"icmpv6rxpktsrate",
				"cntrdescr"	=>	"",
			),
			"icmpv6totrxbytes" => Array(
				"cntrname"	=>	"icmpv6totrxbytes",
				"cntrdescr"	=>	"ICMPv6 bytes received",
				"tooltip"	=>	"Bytes of ICMPv6 data received.",
			),
			"icmpv6rxbytesrate" => array (
				"cntrname"	=>	"icmpv6rxbytesrate",
				"cntrdescr"	=>	"",
			),
			"icmpv6tottxpkts" => Array(
				"cntrname"	=>	"icmpv6tottxpkts",
				"cntrdescr"	=>	"ICMPv6 packets transmitted",
				"tooltip"	=>	"ICMPv6 packets transmitted.",
			),
			"icmpv6txpktsrate" => array (
				"cntrname"	=>	"icmpv6txpktsrate",
				"cntrdescr"	=>	"",
			),
			"icmpv6tottxbytes" => Array(
				"cntrname"	=>	"icmpv6tottxbytes",
				"cntrdescr"	=>	"ICMPv6 bytes transmitted",
				"tooltip"	=>	"Bytes of ICMPv6 data transmitted.",
			),
			"icmpv6txbytesrate" => array (
				"cntrname"	=>	"icmpv6txbytesrate",
				"cntrdescr"	=>	"",
			),
			"icmpv6totrxna" => Array(
				"cntrname"	=>	"icmpv6totrxna",
				"cntrdescr"	=>	"ICMPv6 NA packets received",
				"tooltip"	=>	"ICMPv6 neighbor advertisement packets received. These packets are received in response to a neighbor solicitation message sent out by this node, or if the link layer address of a neighbor has changed.",
			),
			"icmpv6rxnarate" => array (
				"cntrname"	=>	"icmpv6rxnarate",
				"cntrdescr"	=>	"",
			),
			"icmpv6totrxns" => Array(
				"cntrname"	=>	"icmpv6totrxns",
				"cntrdescr"	=>	"ICMPv6 NS packets received",
				"tooltip"	=>	"ICMPv6 neighbor solicitation packets received. These packets are received if the link layer address of a neighbor has changed, or in response to a neighbor solicitation message sent out by this node.",
			),
			"icmpv6rxnsrate" => array (
				"cntrname"	=>	"icmpv6rxnsrate",
				"cntrdescr"	=>	"",
			),
			"icmpv6totrxra" => Array(
				"cntrname"	=>	"icmpv6totrxra",
				"cntrdescr"	=>	"ICMPv6 RA packets received",
				"tooltip"	=>	"ICMPv6 router advertisement packets received. These are received at defined intervals or in response to a router solicitation message.",
			),
			"icmpv6rxrarate" => array (
				"cntrname"	=>	"icmpv6rxrarate",
				"cntrdescr"	=>	"",
			),
			"icmpv6totrxrs" => Array(
				"cntrname"	=>	"icmpv6totrxrs",
				"cntrdescr"	=>	"ICMPv6 RS packets received",
				"tooltip"	=>	"ICMPv6 router solicitation packets received. These could be sent by a neighboring router to initiate address resolution.",
			),
			"icmpv6rxrsrate" => array (
				"cntrname"	=>	"icmpv6rxrsrate",
				"cntrdescr"	=>	"",
			),
			"icmpv6totrxechoreq" => Array(
				"cntrname"	=>	"icmpv6totrxechoreq",
				"cntrdescr"	=>	"ICMPv6 Echo Request packets received",
				"tooltip"	=>	"ICMPv6 Ping Echo Request packets received.",
			),
			"icmpv6rxechoreqrate" => array (
				"cntrname"	=>	"icmpv6rxechoreqrate",
				"cntrdescr"	=>	"",
			),
			"icmpv6totrxechoreply" => Array(
				"cntrname"	=>	"icmpv6totrxechoreply",
				"cntrdescr"	=>	"ICMPv6 Echo Reply packets received",
				"tooltip"	=>	"ICMPv6 Ping Echo Reply packets received.",
			),
			"icmpv6rxechoreplyrate" => array (
				"cntrname"	=>	"icmpv6rxechoreplyrate",
				"cntrdescr"	=>	"",
			),
			"icmpv6tottxna" => Array(
				"cntrname"	=>	"icmpv6tottxna",
				"cntrdescr"	=>	"ICMPv6 NA packets transmitted",
				"tooltip"	=>	"ICMPv6 neighbor advertisement packets transmitted. These packets are sent in response to a neighbor solicitation packet, or if the link layer address of this node has changed.",
			),
			"icmpv6txnarate" => array (
				"cntrname"	=>	"icmpv6txnarate",
				"cntrdescr"	=>	"",
			),
			"icmpv6tottxns" => Array(
				"cntrname"	=>	"icmpv6tottxns",
				"cntrdescr"	=>	"ICMPv6 NS packets transmitted",
				"tooltip"	=>	"ICMPv6 neighbor solicitation packets transmitted. These packets are sent to get the link layer addresses of neighboring nodes or to confirm that they are reachable.",
			),
			"icmpv6txnsrate" => array (
				"cntrname"	=>	"icmpv6txnsrate",
				"cntrdescr"	=>	"",
			),
			"icmpv6tottxra" => Array(
				"cntrname"	=>	"icmpv6tottxra",
				"cntrdescr"	=>	"ICMPv6 RA packets transmitted",
				"tooltip"	=>	"ICMPv6 router advertisement packets transmitted. These packets are sent at regular intervals or in response to a router solicitation packet from a neighbor.",
			),
			"icmpv6txrarate" => array (
				"cntrname"	=>	"icmpv6txrarate",
				"cntrdescr"	=>	"",
			),
			"icmpv6tottxrs" => Array(
				"cntrname"	=>	"icmpv6tottxrs",
				"cntrdescr"	=>	"ICMPv6 RS packets transmitted",
				"tooltip"	=>	"ICMPv6 router solicitation packets transmitted. These packets are sent to request neighboring routers to generate router advertisements immediately rather than wait for the next defined time.",
			),
			"icmpv6txrsrate" => array (
				"cntrname"	=>	"icmpv6txrsrate",
				"cntrdescr"	=>	"",
			),
			"icmpv6tottxechoreq" => Array(
				"cntrname"	=>	"icmpv6tottxechoreq",
				"cntrdescr"	=>	"ICMPv6 Echo Request packets transmitted",
				"tooltip"	=>	"ICMPv6 Ping Echo Request packets transmitted.",
			),
			"icmpv6txechoreqrate" => array (
				"cntrname"	=>	"icmpv6txechoreqrate",
				"cntrdescr"	=>	"",
			),
			"icmpv6tottxechoreply" => Array(
				"cntrname"	=>	"icmpv6tottxechoreply",
				"cntrdescr"	=>	"ICMPv6 Echo Reply packets transmitted",
				"tooltip"	=>	"ICMP Ping Echo Reply packets transmitted.",
			),
			"icmpv6txechoreplyrate" => array (
				"cntrname"	=>	"icmpv6txechoreplyrate",
				"cntrdescr"	=>	"",
			),
			"icmpv6errra" => Array(
				"cntrname"	=>	"icmpv6errra",
				"cntrdescr"	=>	"ICMPv6 RA error packets",
				"tooltip"	=>	"ICMPv6 router advertisement error packets received that contain an error in the header, such as an incorrect source IP address, destination IP address, or packet length.",
			),
			"icmpv6errna" => Array(
				"cntrname"	=>	"icmpv6errna",
				"cntrdescr"	=>	"ICMPv6 NA error packets",
				"tooltip"	=>	"ICMPv6 neighbor advertisement error packets received that contain an error in the header, such as an incorrect source IP address, destination IP address, or packet length.",
			),
			"icmpv6errns" => Array(
				"cntrname"	=>	"icmpv6errns",
				"cntrdescr"	=>	"ICMPv6 NS error packets",
				"tooltip"	=>	"ICMPv6 neighbor solicitation error packets received that contain an error in the header, such as an incorrect source IP address, destination IP address, or packet length.",
			),
			"icmpv6badchecksums" => Array(
				"cntrname"	=>	"icmpv6badchecksums",
				"cntrdescr"	=>	"ICMPv6 bad checksum",
				"tooltip"	=>	"Packets received with an ICMPv6 checksum error.",
			),
			"icmpv6unspt" => Array(
				"cntrname"	=>	"icmpv6unspt",
				"cntrdescr"	=>	"unsupported ICMPv6 packets",
				"tooltip"	=>	"ICMPv6 packets received that are not supported by the NetScaler.",
			),
			"icmpv6rtthsld" => Array(
				"cntrname"	=>	"icmpv6rtthsld",
				"cntrdescr"	=>	"Rate threshold exceeded packets",
				"tooltip"	=>	"Packets dropped because the default threshold of 100 requests per 10 milliseconds has been exceeded.<br><br>This is a configurable value using the set rateControl command.",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"ICMPV6 Statistics - Summary",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("icmpv6totrxpkts","icmpv6totrxbytes","icmpv6tottxpkts","icmpv6tottxbytes","icmpv6totrxna","icmpv6totrxns","icmpv6totrxra","icmpv6totrxrs","icmpv6totrxechoreq","icmpv6totrxechoreply","icmpv6tottxna","icmpv6tottxns","icmpv6tottxra","icmpv6tottxrs","icmpv6tottxechoreq","icmpv6tottxechoreply",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("icmpv6totrxpkts","icmpv6totrxbytes","icmpv6tottxpkts","icmpv6tottxbytes","icmpv6totrxna","icmpv6totrxns","icmpv6totrxra","icmpv6totrxrs","icmpv6totrxechoreq","icmpv6totrxechoreply","icmpv6tottxna","icmpv6tottxns","icmpv6tottxra","icmpv6tottxrs","icmpv6tottxechoreq","icmpv6tottxechoreply",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Errors:",
				"header"	=>	array(),
				"format"	=>	"1C",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("icmpv6errra","icmpv6errna","icmpv6errns","icmpv6badchecksums","icmpv6unspt","icmpv6rtthsld",),
			),
		),
),


"statprotocolip" => Array(
	"cmddispname"		=>	"statprotocolip",
	"cmdname"		=>	"statprotocolip",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"iptotrxpkts" => Array(
				"cntrname"	=>	"iptotrxpkts",
				"cntrdescr"	=>	"IP packets received",
				"tooltip"	=>	"IP packets received.",
			),
			"iprxpktsrate" => array (
				"cntrname"	=>	"iprxpktsrate",
				"cntrdescr"	=>	"",
			),
			"iptotrxbytes" => Array(
				"cntrname"	=>	"iptotrxbytes",
				"cntrdescr"	=>	"IP bytes received",
				"tooltip"	=>	"Bytes of IP data received.",
			),
			"iprxbytesrate" => array (
				"cntrname"	=>	"iprxbytesrate",
				"cntrdescr"	=>	"",
			),
			"iptottxpkts" => Array(
				"cntrname"	=>	"iptottxpkts",
				"cntrdescr"	=>	"IP packets transmitted",
				"tooltip"	=>	"IP packets transmitted.",
			),
			"iptxpktsrate" => array (
				"cntrname"	=>	"iptxpktsrate",
				"cntrdescr"	=>	"",
			),
			"iptottxbytes" => Array(
				"cntrname"	=>	"iptottxbytes",
				"cntrdescr"	=>	"IP bytes transmitted",
				"tooltip"	=>	"Bytes of IP data transmitted.",
			),
			"iptxbytesrate" => array (
				"cntrname"	=>	"iptxbytesrate",
				"cntrdescr"	=>	"",
			),
			"iptotrxmbits" => Array(
				"cntrname"	=>	"iptotrxmbits",
				"cntrdescr"	=>	"Megabits received",
				"tooltip"	=>	"Megabits of IP data received.",
			),
			"iprxmbitsrate" => array (
				"cntrname"	=>	"iprxmbitsrate",
				"cntrdescr"	=>	"",
			),
			"iptottxmbits" => Array(
				"cntrname"	=>	"iptottxmbits",
				"cntrdescr"	=>	"Megabits transmitted",
				"tooltip"	=>	"Megabits of IP data transmitted.",
			),
			"iptxmbitsrate" => array (
				"cntrname"	=>	"iptxmbitsrate",
				"cntrdescr"	=>	"",
			),
			"iptotroutedpkts" => Array(
				"cntrname"	=>	"iptotroutedpkts",
				"cntrdescr"	=>	"Total routed IP packets",
				"tooltip"	=>	"Total routed packets.",
			),
			"iproutedpktsrate" => array (
				"cntrname"	=>	"iproutedpktsrate",
				"cntrdescr"	=>	"",
			),
			"iptotroutedmbits" => Array(
				"cntrname"	=>	"iptotroutedmbits",
				"cntrdescr"	=>	"Total routed IP Mbits",
				"tooltip"	=>	"Total routed Mbits",
			),
			"iproutedmbitsrate" => array (
				"cntrname"	=>	"iproutedmbitsrate",
				"cntrdescr"	=>	"",
			),
			"iptotfragments" => Array(
				"cntrname"	=>	"iptotfragments",
				"cntrdescr"	=>	"IP fragments received",
				"tooltip"	=>	"IP fragments received.",
			),
			"iptotsuccreassembly" => Array(
				"cntrname"	=>	"iptotsuccreassembly",
				"cntrdescr"	=>	"Successful reassembly",
				"tooltip"	=>	"Fragmented IP packets successfully reassembled on the NetScaler.",
			),
			"iptotreassemblyattempt" => Array(
				"cntrname"	=>	"iptotreassemblyattempt",
				"cntrdescr"	=>	"Reassembly attempted",
				"tooltip"	=>	"IP packets that the NetScaler attempts to reassemble. If one of the fragments is missing, the whole packet is dropped.",
			),
			"iptotaddrlookup" => Array(
				"cntrname"	=>	"iptotaddrlookup",
				"cntrdescr"	=>	"IP address lookups",
				"tooltip"	=>	"IP address lookups performed by the NetScaler. When a packet is received on a non-established session, the NetScaler checks if the destination IP address is one of the NetScaler owned IP addresses.",
			),
			"iptotaddrlookupfail" => Array(
				"cntrname"	=>	"iptotaddrlookupfail",
				"cntrdescr"	=>	"IP address lookup failure",
				"tooltip"	=>	"IP address lookups performed by the NetScaler that have failed because the destination IP address of the packet does not match any of the NetScaler owned IP addresses.",
			),
			"iptotudpfragmentsfwd" => Array(
				"cntrname"	=>	"iptotudpfragmentsfwd",
				"cntrdescr"	=>	"UDP fragments forwarded",
				"tooltip"	=>	"UDP fragments forwarded to the client or the server.",
			),
			"iptottcpfragmentsfwd" => Array(
				"cntrname"	=>	"iptottcpfragmentsfwd",
				"cntrdescr"	=>	"TCP fragments forwarded",
				"tooltip"	=>	"TCP fragments forwarded to the client or the server.",
			),
			"iptotfragpktsgen" => Array(
				"cntrname"	=>	"iptotfragpktsgen",
				"cntrdescr"	=>	"Fragmentation packets created",
				"tooltip"	=>	"Fragmented packets created by the NetScaler.",
			),
			"iptotbadchecksums" => Array(
				"cntrname"	=>	"iptotbadchecksums",
				"cntrdescr"	=>	"Bad IP checksums",
				"tooltip"	=>	"Packets received with an IP checksum error.",
			),
			"iptotunsuccreassembly" => Array(
				"cntrname"	=>	"iptotunsuccreassembly",
				"cntrdescr"	=>	"Unsuccessful reassembly",
				"tooltip"	=>	"Packets received that could not be reassembled. This can occur when there is a checksum failure, an identification field mismatch, or when one of the fragments is missing.",
			),
			"iptottoobig" => Array(
				"cntrname"	=>	"iptottoobig",
				"cntrdescr"	=>	"Reassembled data too big",
				"tooltip"	=>	"Packets received for which the reassembled data exceeds the Ethernet packet data length of 1500 bytes.",
			),
			"iptotzerofragmentlen" => Array(
				"cntrname"	=>	"iptotzerofragmentlen",
				"cntrdescr"	=>	"Zero fragment length received",
				"tooltip"	=>	"Packets received with a fragment length of 0 bytes.",
			),
			"iptotdupfragments" => Array(
				"cntrname"	=>	"iptotdupfragments",
				"cntrdescr"	=>	"Duplicate fragments received",
				"tooltip"	=>	"Duplicate IP fragments received. This can occur when the acknowledgement was not received within the expected time.",
			),
			"iptotoutoforderfrag" => Array(
				"cntrname"	=>	"iptotoutoforderfrag",
				"cntrdescr"	=>	"Out of order fragment received",
				"tooltip"	=>	"Fragments received that are out of order.",
			),
			"iptotunknowndstrcvd" => Array(
				"cntrname"	=>	"iptotunknowndstrcvd",
				"cntrdescr"	=>	"Unknown destination received",
				"tooltip"	=>	"Packets received in which the destination IP address was not reachable or not owned by the NetScaler.",
			),
			"iptotbadtransport" => Array(
				"cntrname"	=>	"iptotbadtransport",
				"cntrdescr"	=>	"Bad Transport",
				"tooltip"	=>	"Packets received in which the protocol specified in the IP header is unknown to the NetScaler.",
			),
			"iptotvipdown" => Array(
				"cntrname"	=>	"iptotvipdown",
				"cntrdescr"	=>	"VIP down",
				"tooltip"	=>	"Packets received for which the VIP is down. This can occur when all the services bound to the VIP are down or the VIP is manually disabled.",
			),
			"iptotfixheaderfail" => Array(
				"cntrname"	=>	"iptotfixheaderfail",
				"cntrdescr"	=>	"Fix header failure",
				"tooltip"	=>	"Packets received that contain an error in one or more components of the IP header.",
			),
			"iptotttlexpired" => Array(
				"cntrname"	=>	"iptotttlexpired",
				"cntrdescr"	=>	"TTL expired during transit",
				"tooltip"	=>	"Packets for which the time-to-live (TTL) expired during transit. These packets are dropped.",
			),
			"iptotmaxclients" => Array(
				"cntrname"	=>	"iptotmaxclients",
				"cntrdescr"	=>	"max non-TCP clients",
				"tooltip"	=>	"Attempts to open a new connection to a service for which the maximum limit has been exceeded. Default value, 0, applies no limit.",
			),
			"iptotunknownsvcs" => Array(
				"cntrname"	=>	"iptotunknownsvcs",
				"cntrdescr"	=>	"Unknown services",
				"tooltip"	=>	"Packets received on a port or service that is not configured.",
			),
			"iptotlandattacks" => Array(
				"cntrname"	=>	"iptotlandattacks",
				"cntrdescr"	=>	"land-attacks",
				"tooltip"	=>	"Land-attack packets received. The source and destination addresses are the same.",
			),
			"iptotinvalidheadersz" => Array(
				"cntrname"	=>	"iptotinvalidheadersz",
				"cntrdescr"	=>	"Invalid IP header size",
				"tooltip"	=>	"Packets received in which an invalid data length is specified, or the value in the length field and the actual data length do not match. The range for the Ethernet packet data length is 0-1500 bytes.",
			),
			"iptotinvalidpacketsize" => Array(
				"cntrname"	=>	"iptotinvalidpacketsize",
				"cntrdescr"	=>	"Invalid IP packet size",
				"tooltip"	=>	"Total number of packets received by NetScaler with invalid IP packet size.",
			),
			"iptottruncatedpackets" => Array(
				"cntrname"	=>	"iptottruncatedpackets",
				"cntrdescr"	=>	"Truncated IP packet",
				"tooltip"	=>	"Truncated IP packets received. An overflow in the routers along the path can truncate IP packets.",
			),
			"noniptottruncatedpackets" => Array(
				"cntrname"	=>	"noniptottruncatedpackets",
				"cntrdescr"	=>	"Truncated non-IP packet",
				"tooltip"	=>	"Truncated non-IP packets received.",
			),
			"iptotzeronexthop" => Array(
				"cntrname"	=>	"iptotzeronexthop",
				"cntrdescr"	=>	"ZERO next hop",
				"tooltip"	=>	"Packets received that contain a 0 value in the next hop field. These packets are dropped.",
			),
			"iptotbadlens" => Array(
				"cntrname"	=>	"iptotbadlens",
				"cntrdescr"	=>	"Packets with len > 1514 rcvd",
				"tooltip"	=>	"Packets received with a length greater than the normal maximum transmission unit of 1514 bytes.",
			),
			"iptotbadmacaddrs" => Array(
				"cntrname"	=>	"iptotbadmacaddrs",
				"cntrdescr"	=>	"Packets with bad MAC sent",
				"tooltip"	=>	"IP packets transmitted with a bad MAC address.",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"IP Summary:",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("iptotrxpkts","iptotrxbytes","iptottxpkts","iptottxbytes","iptotrxmbits","iptottxmbits","iptotroutedpkts","iptotroutedmbits",),
				"charts"	=>	array(	array("ip_pkts","ip_bytes",),
								),
				"chartwidthpercent"	=>	40,
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Run Time:",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("iptotrxpkts","iptotrxbytes","iptottxpkts","iptottxbytes","iptotrxmbits","iptottxmbits","iptotroutedpkts","iptotroutedmbits","iptotfragments","iptotsuccreassembly","iptotreassemblyattempt","iptotaddrlookup","iptotaddrlookupfail","iptotudpfragmentsfwd","iptottcpfragmentsfwd","iptotfragpktsgen",),
				"charts"	=>	array(	array("ip_pkts","ip_bytes",),
								array("ip_lookups",),
								),
				"chartwidthpercent"	=>	40,
			),
			array (
			"format"	=>	"2C",
				"title"	=>	"Errors:",
			"column1" => 			Array(
				"dhID"	=>	3,
				"title"	=>	"",
				"header"	=>	array(),
				"dispflag"	=>	"Detail",
				"counters"	=>	array("iptotbadchecksums","iptotunsuccreassembly","iptottoobig","iptotzerofragmentlen","iptotdupfragments","iptotoutoforderfrag","iptotunknowndstrcvd","iptotbadtransport","iptotvipdown","iptotfixheaderfail","iptotttlexpired",),
			),
			"column2" => 			Array(
				"dhID"	=>	4,
				"title"	=>	"",
				"header"	=>	array(),
				"dispflag"	=>	"Detail",
				"counters"	=>	array("iptotmaxclients","iptotunknownsvcs","iptotlandattacks","iptotinvalidheadersz","iptotinvalidpacketsize","iptottruncatedpackets","noniptottruncatedpackets","iptotzeronexthop","iptotbadlens","iptotbadmacaddrs",),
			),
			),
		),
),


"statprotocoludp" => Array(
	"cmddispname"		=>	"statprotocoludp",
	"cmdname"		=>	"statprotocoludp",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"udptotrxpkts" => Array(
				"cntrname"	=>	"udptotrxpkts",
				"cntrdescr"	=>	"Packets received",
				"tooltip"	=>	"Total number of UDP packets received.",
			),
			"udprxpktsrate" => array (
				"cntrname"	=>	"udprxpktsrate",
				"cntrdescr"	=>	"",
			),
			"udptotrxbytes" => Array(
				"cntrname"	=>	"udptotrxbytes",
				"cntrdescr"	=>	"Bytes received",
				"tooltip"	=>	"Total number of UDP data received in bytes.",
			),
			"udprxbytesrate" => array (
				"cntrname"	=>	"udprxbytesrate",
				"cntrdescr"	=>	"",
			),
			"udptottxpkts" => Array(
				"cntrname"	=>	"udptottxpkts",
				"cntrdescr"	=>	"Packets transmitted",
				"tooltip"	=>	"Total number of UDP packets transmitted.",
			),
			"udptxpktsrate" => array (
				"cntrname"	=>	"udptxpktsrate",
				"cntrdescr"	=>	"",
			),
			"udptottxbytes" => Array(
				"cntrname"	=>	"udptottxbytes",
				"cntrdescr"	=>	"Bytes transmitted",
				"tooltip"	=>	"Total number of UDP data transmitted in bytes.",
			),
			"udptxbytesrate" => array (
				"cntrname"	=>	"udptxbytesrate",
				"cntrdescr"	=>	"",
			),
			"udpcurratethreshold" => Array(
				"cntrname"	=>	"udpcurratethreshold",
				"cntrdescr"	=>	"Current rate threshold",
				"tooltip"	=>	"Limit for UDP packets handled every 10 milliseconds. Default value, 0, applies no limit.<br><br>This is a configurable value using the set rateControl command.",
			),
			"udptotunknownsvcpkts" => Array(
				"cntrname"	=>	"udptotunknownsvcpkts",
				"cntrdescr"	=>	"Unknown service",
				"tooltip"	=>	"Stray UDP packets dropped due to no configured listening  service.",
			),
			"udpbadchecksum" => Array(
				"cntrname"	=>	"udpbadchecksum",
				"cntrdescr"	=>	"Bad UDP checksum",
				"tooltip"	=>	"Packets received with a UDP checksum error.",
			),
			"udpcurratethresholdexceeds" => Array(
				"cntrname"	=>	"udpcurratethresholdexceeds",
				"cntrdescr"	=>	"Rate threshold exceeded",
				"tooltip"	=>	"Number of times the UDP rate threshold is exceeded. If this counter continuously increases, first make sure the UDP packets received are genuine. <br><br>	If they are, increase the current rate threshold. This is a configurable value using the set rateControl command.",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"UDP Statistics - Summary",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("udptotrxpkts","udptotrxbytes","udptottxpkts","udptottxbytes",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("udpcurratethreshold",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"",
				"header"	=>	array(),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("udptotrxpkts","udptotrxbytes",),
			),
			Array(
				"dhID"	=>	4,
				"title"	=>	"",
				"header"	=>	array(),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("udptottxpkts","udptottxbytes",),
			),
			Array(
				"dhID"	=>	5,
				"title"	=>	"Errors:",
				"header"	=>	array("","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("udptotunknownsvcpkts","udpbadchecksum","udpcurratethresholdexceeds",),
			),
		),
),


"statqos" => Array(
	"cmddispname"		=>	"statqos",
	"cmdname"		=>	"statqos",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"ipcmessagessent" => Array(
				"cntrname"	=>	"ipcmessagessent",
				"cntrdescr"	=>	"IPC messages sent from QoS",
				"tooltip"	=>	"IPC messages sent from qos system.",
			),
			"ipcmessagessentrate" => array (
				"cntrname"	=>	"ipcmessagessentrate",
				"cntrdescr"	=>	"",
			),
			"ipcmessagesfailed" => Array(
				"cntrname"	=>	"ipcmessagesfailed",
				"cntrdescr"	=>	"IPC messages QoS failed to send",
				"tooltip"	=>	"IPC messages failed to send from qos system.",
			),
			"ipcmessagesfailedrate" => array (
				"cntrname"	=>	"ipcmessagesfailedrate",
				"cntrdescr"	=>	"",
			),
			"ipcmessagesreceived" => Array(
				"cntrname"	=>	"ipcmessagesreceived",
				"cntrdescr"	=>	"IPC messages QoS received",
				"tooltip"	=>	"IPC messages received by qos.",
			),
			"ipcmessagesreceivedrate" => array (
				"cntrname"	=>	"ipcmessagesreceivedrate",
				"cntrdescr"	=>	"",
			),
			"ipcpe2qossent" => Array(
				"cntrname"	=>	"ipcpe2qossent",
				"cntrdescr"	=>	"IPC messages sent to QoS",
				"tooltip"	=>	"IPC messages sent to qos system.",
			),
			"ipcpe2qossentrate" => array (
				"cntrname"	=>	"ipcpe2qossentrate",
				"cntrdescr"	=>	"",
			),
			"ipcpe2qosfailed" => Array(
				"cntrname"	=>	"ipcpe2qosfailed",
				"cntrdescr"	=>	"IPC messages failed to send QoS",
				"tooltip"	=>	"IPC messages failed to send to qos system.",
			),
			"ipcpe2qosfailedrate" => array (
				"cntrname"	=>	"ipcpe2qosfailedrate",
				"cntrdescr"	=>	"",
			),
			"ipcpe2qosreceived" => Array(
				"cntrname"	=>	"ipcpe2qosreceived",
				"cntrdescr"	=>	"IPC messages received from QoS",
				"tooltip"	=>	"IPC messages received from qos system.",
			),
			"ipcpe2qosreceivedrate" => array (
				"cntrname"	=>	"ipcpe2qosreceivedrate",
				"cntrdescr"	=>	"",
			),
			"qosbytesdropped" => Array(
				"cntrname"	=>	"qosbytesdropped",
				"cntrdescr"	=>	"Bytes QoS marked for drop",
				"tooltip"	=>	"Bytes QoS marked for drop",
			),
			"qosbytesdroppedrate" => array (
				"cntrname"	=>	"qosbytesdroppedrate",
				"cntrdescr"	=>	"",
			),
			"qosbytessentnotclassified" => Array(
				"cntrname"	=>	"qosbytessentnotclassified",
				"cntrdescr"	=>	"QoS bytes sent not classified",
				"tooltip"	=>	"Bytes scheduled by QoS that were not classified.",
			),
			"qosbytessentnotclassifiedrate" => array (
				"cntrname"	=>	"qosbytessentnotclassifiedrate",
				"cntrdescr"	=>	"",
			),
			"qosbytesdroppednoconnection" => Array(
				"cntrname"	=>	"qosbytesdroppednoconnection",
				"cntrdescr"	=>	"QoS bytes dropped no connection",
				"tooltip"	=>	"Bytes dropped by QoS when no connection was found.",
			),
			"qosbytesdroppednoconnectionrate" => array (
				"cntrname"	=>	"qosbytesdroppednoconnectionrate",
				"cntrdescr"	=>	"",
			),
			"qosinputpackets" => Array(
				"cntrname"	=>	"qosinputpackets",
				"cntrdescr"	=>	"Packets sent to QoS",
				"tooltip"	=>	"Packets sent to QoS for scheduling.",
			),
			"qosinputpacketsrate" => array (
				"cntrname"	=>	"qosinputpacketsrate",
				"cntrdescr"	=>	"",
			),
			"qosoutputpackets" => Array(
				"cntrname"	=>	"qosoutputpackets",
				"cntrdescr"	=>	"Packets from QoS to be sent",
				"tooltip"	=>	"Packets from QoS to be sent",
			),
			"qosoutputpacketsrate" => array (
				"cntrname"	=>	"qosoutputpacketsrate",
				"cntrdescr"	=>	"",
			),
			"qosdroppackets" => Array(
				"cntrname"	=>	"qosdroppackets",
				"cntrdescr"	=>	"Packets Dropped by QoS",
				"tooltip"	=>	"Packets Dropped by QoS.",
			),
			"qosdroppacketsrate" => array (
				"cntrname"	=>	"qosdroppacketsrate",
				"cntrdescr"	=>	"",
			),
			"qosrewritemacs" => Array(
				"cntrname"	=>	"qosrewritemacs",
				"cntrdescr"	=>	"Classified source MAC rewritten",
				"tooltip"	=>	"Number of packets with inband classification in source MAC.",
			),
			"qosrewritemacsrate" => array (
				"cntrname"	=>	"qosrewritemacsrate",
				"cntrdescr"	=>	"",
			),
			"qospacketsunclassified" => Array(
				"cntrname"	=>	"qospacketsunclassified",
				"cntrdescr"	=>	"QoS packets unclassified",
				"tooltip"	=>	"Number of packets without classification.",
			),
			"qospacketsunclassifiedrate" => array (
				"cntrname"	=>	"qospacketsunclassifiedrate",
				"cntrdescr"	=>	"",
			),
			"qospacketsclassified" => Array(
				"cntrname"	=>	"qospacketsclassified",
				"cntrdescr"	=>	"QoS packets classified",
				"tooltip"	=>	"Number of packets with classification.",
			),
			"qospacketsclassifiedrate" => array (
				"cntrname"	=>	"qospacketsclassifiedrate",
				"cntrdescr"	=>	"",
			),
			"qoslearnedmac" => Array(
				"cntrname"	=>	"qoslearnedmac",
				"cntrdescr"	=>	"QoS learned true MAC",
				"tooltip"	=>	"QoS learned true MAC",
			),
			"qoslearnedmacrate" => array (
				"cntrname"	=>	"qoslearnedmacrate",
				"cntrdescr"	=>	"",
			),
			"qosinputbytes" => Array(
				"cntrname"	=>	"qosinputbytes",
				"cntrdescr"	=>	"QoS Input Bytes",
				"tooltip"	=>	"Bytes sent to QoS for scheduling",
			),
			"qosinputbytesrate" => array (
				"cntrname"	=>	"qosinputbytesrate",
				"cntrdescr"	=>	"",
			),
			"qosoutputbytes" => Array(
				"cntrname"	=>	"qosoutputbytes",
				"cntrdescr"	=>	"QoS Output Bytes",
				"tooltip"	=>	"Bytes received from QoS to be sent",
			),
			"qosoutputbytesrate" => array (
				"cntrname"	=>	"qosoutputbytesrate",
				"cntrdescr"	=>	"",
			),
			"qosfreeheldlist" => Array(
				"cntrname"	=>	"qosfreeheldlist",
				"cntrdescr"	=>	"QoS Free Held List",
				"tooltip"	=>	"No. more packets QoS can hold onto.",
			),
			"qoslink00sent" => Array(
				"cntrname"	=>	"qoslink00sent",
				"cntrdescr"	=>	"QoS Link 00 Bytes Sent",
				"tooltip"	=>	"QoS bytes sent on Link 00",
			),
			"qoslink00sentrate" => array (
				"cntrname"	=>	"qoslink00sentrate",
				"cntrdescr"	=>	"",
			),
			"qoslink00drop" => Array(
				"cntrname"	=>	"qoslink00drop",
				"cntrdescr"	=>	"QoS Link 00 Bytes Dropped",
				"tooltip"	=>	"QoS bytes dropped on Link 00",
			),
			"qoslink00droprate" => array (
				"cntrname"	=>	"qoslink00droprate",
				"cntrdescr"	=>	"",
			),
			"qoslink01sent" => Array(
				"cntrname"	=>	"qoslink01sent",
				"cntrdescr"	=>	"QoS Link 01 Bytes Sent",
				"tooltip"	=>	"QoS bytes sent on Link 01",
			),
			"qoslink01sentrate" => array (
				"cntrname"	=>	"qoslink01sentrate",
				"cntrdescr"	=>	"",
			),
			"qoslink01drop" => Array(
				"cntrname"	=>	"qoslink01drop",
				"cntrdescr"	=>	"QoS Link 01 Bytes Dropped",
				"tooltip"	=>	"QoS bytes dropped on Link 01",
			),
			"qoslink01droprate" => array (
				"cntrname"	=>	"qoslink01droprate",
				"cntrdescr"	=>	"",
			),
			"qoslink02sent" => Array(
				"cntrname"	=>	"qoslink02sent",
				"cntrdescr"	=>	"QoS Link 02 Bytes Sent",
				"tooltip"	=>	"QoS bytes sent on Link 02",
			),
			"qoslink02sentrate" => array (
				"cntrname"	=>	"qoslink02sentrate",
				"cntrdescr"	=>	"",
			),
			"qoslink02drop" => Array(
				"cntrname"	=>	"qoslink02drop",
				"cntrdescr"	=>	"QoS Link 02 Bytes Dropped",
				"tooltip"	=>	"QoS bytes dropped on Link 02",
			),
			"qoslink02droprate" => array (
				"cntrname"	=>	"qoslink02droprate",
				"cntrdescr"	=>	"",
			),
			"qoslink03sent" => Array(
				"cntrname"	=>	"qoslink03sent",
				"cntrdescr"	=>	"QoS Link 03 Bytes Sent",
				"tooltip"	=>	"QoS bytes sent on Link 03",
			),
			"qoslink03sentrate" => array (
				"cntrname"	=>	"qoslink03sentrate",
				"cntrdescr"	=>	"",
			),
			"qoslink03drop" => Array(
				"cntrname"	=>	"qoslink03drop",
				"cntrdescr"	=>	"QoS Link 03 Bytes Dropped",
				"tooltip"	=>	"QoS bytes dropped on Link 03",
			),
			"qoslink03droprate" => array (
				"cntrname"	=>	"qoslink03droprate",
				"cntrdescr"	=>	"",
			),
			"qoslink04sent" => Array(
				"cntrname"	=>	"qoslink04sent",
				"cntrdescr"	=>	"QoS Link 04 Bytes Sent",
				"tooltip"	=>	"QoS bytes sent on Link 04",
			),
			"qoslink04sentrate" => array (
				"cntrname"	=>	"qoslink04sentrate",
				"cntrdescr"	=>	"",
			),
			"qoslink04drop" => Array(
				"cntrname"	=>	"qoslink04drop",
				"cntrdescr"	=>	"QoS Link 04 Bytes Dropped",
				"tooltip"	=>	"QoS bytes dropped on Link 04",
			),
			"qoslink04droprate" => array (
				"cntrname"	=>	"qoslink04droprate",
				"cntrdescr"	=>	"",
			),
			"qoslink05sent" => Array(
				"cntrname"	=>	"qoslink05sent",
				"cntrdescr"	=>	"QoS Link 05 Bytes Sent",
				"tooltip"	=>	"QoS bytes sent on Link 05",
			),
			"qoslink05sentrate" => array (
				"cntrname"	=>	"qoslink05sentrate",
				"cntrdescr"	=>	"",
			),
			"qoslink05drop" => Array(
				"cntrname"	=>	"qoslink05drop",
				"cntrdescr"	=>	"QoS Link 05 Bytes Dropped",
				"tooltip"	=>	"QoS bytes dropped on Link 05",
			),
			"qoslink05droprate" => array (
				"cntrname"	=>	"qoslink05droprate",
				"cntrdescr"	=>	"",
			),
			"qoslink06sent" => Array(
				"cntrname"	=>	"qoslink06sent",
				"cntrdescr"	=>	"QoS Link 06 Bytes Sent",
				"tooltip"	=>	"QoS bytes sent on Link 06",
			),
			"qoslink06sentrate" => array (
				"cntrname"	=>	"qoslink06sentrate",
				"cntrdescr"	=>	"",
			),
			"qoslink06drop" => Array(
				"cntrname"	=>	"qoslink06drop",
				"cntrdescr"	=>	"QoS Link 06 Bytes Dropped",
				"tooltip"	=>	"QoS bytes dropped on Link 06",
			),
			"qoslink06droprate" => array (
				"cntrname"	=>	"qoslink06droprate",
				"cntrdescr"	=>	"",
			),
			"qoslink07sent" => Array(
				"cntrname"	=>	"qoslink07sent",
				"cntrdescr"	=>	"QoS Link 07 Bytes Sent",
				"tooltip"	=>	"QoS bytes sent on Link 07",
			),
			"qoslink07sentrate" => array (
				"cntrname"	=>	"qoslink07sentrate",
				"cntrdescr"	=>	"",
			),
			"qoslink07drop" => Array(
				"cntrname"	=>	"qoslink07drop",
				"cntrdescr"	=>	"QoS Link 07 Bytes Dropped",
				"tooltip"	=>	"QoS bytes dropped on Link 07",
			),
			"qoslink07droprate" => array (
				"cntrname"	=>	"qoslink07droprate",
				"cntrdescr"	=>	"",
			),
			"qoslink08sent" => Array(
				"cntrname"	=>	"qoslink08sent",
				"cntrdescr"	=>	"QoS Link 08 Bytes Sent",
				"tooltip"	=>	"QoS bytes sent on Link 08",
			),
			"qoslink08sentrate" => array (
				"cntrname"	=>	"qoslink08sentrate",
				"cntrdescr"	=>	"",
			),
			"qoslink08drop" => Array(
				"cntrname"	=>	"qoslink08drop",
				"cntrdescr"	=>	"QoS Link 08 Bytes Dropped",
				"tooltip"	=>	"QoS bytes dropped on Link 08",
			),
			"qoslink08droprate" => array (
				"cntrname"	=>	"qoslink08droprate",
				"cntrdescr"	=>	"",
			),
			"qoslink09sent" => Array(
				"cntrname"	=>	"qoslink09sent",
				"cntrdescr"	=>	"QoS Link 09 Bytes Sent",
				"tooltip"	=>	"QoS bytes sent on Link 09",
			),
			"qoslink09sentrate" => array (
				"cntrname"	=>	"qoslink09sentrate",
				"cntrdescr"	=>	"",
			),
			"qoslink09drop" => Array(
				"cntrname"	=>	"qoslink09drop",
				"cntrdescr"	=>	"QoS Link 09 Bytes Dropped",
				"tooltip"	=>	"QoS bytes dropped on Link 09",
			),
			"qoslink09droprate" => array (
				"cntrname"	=>	"qoslink09droprate",
				"cntrdescr"	=>	"",
			),
			"qoslink10sent" => Array(
				"cntrname"	=>	"qoslink10sent",
				"cntrdescr"	=>	"QoS Link 10 Bytes Sent",
				"tooltip"	=>	"QoS bytes sent on Link 10",
			),
			"qoslink10sentrate" => array (
				"cntrname"	=>	"qoslink10sentrate",
				"cntrdescr"	=>	"",
			),
			"qoslink10drop" => Array(
				"cntrname"	=>	"qoslink10drop",
				"cntrdescr"	=>	"QoS Link 10 Bytes Dropped",
				"tooltip"	=>	"QoS bytes dropped on Link 10",
			),
			"qoslink10droprate" => array (
				"cntrname"	=>	"qoslink10droprate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"QoS Statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"singleton"	=>	1,
				"dispflag"	=>	"Summary",
				"counters"	=>	array("ipcmessagessent","ipcmessagesfailed","ipcmessagesreceived","ipcpe2qossent","ipcpe2qosfailed","ipcpe2qosreceived","qosbytesdropped","qosbytessentnotclassified","qosbytesdroppednoconnection","qosinputpackets","qosoutputpackets","qosdroppackets","qosrewritemacs","qospacketsunclassified","qospacketsclassified","qoslearnedmac","qosinputbytes","qosoutputbytes","qosfreeheldlist","qoslink00sent","qoslink00drop","qoslink01sent","qoslink01drop","qoslink02sent","qoslink02drop","qoslink03sent","qoslink03drop","qoslink04sent","qoslink04drop","qoslink05sent","qoslink05drop","qoslink06sent","qoslink06drop","qoslink07sent","qoslink07drop","qoslink08sent","qoslink08drop","qoslink09sent","qoslink09drop","qoslink10sent","qoslink10drop",),
			),
		),
),


"statresponderpolicy" => Array(
	"cmddispname"		=>	"statresponderpolicy",
	"cmdname"		=>	"statresponderpolicy",
	"license"		=>	Array("NSKF1_RESPONDER_BIT_POS","NSKF1_RESPONDER_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"pipolicyhits" => Array(
				"cntrname"	=>	"pipolicyhits",
				"cntrdescr"	=>	"Policy hits",
				"tooltip"	=>	"Number of hits on the policy",
			),
			"pipolicyhitsrate" => array (
				"cntrname"	=>	"pipolicyhitsrate",
				"cntrdescr"	=>	"",
			),
			"pipolicyundefhits" => Array(
				"cntrname"	=>	"pipolicyundefhits",
				"cntrdescr"	=>	"Policy undef hits",
				"tooltip"	=>	"Number of undef hits on the policy",
			),
			"pipolicyundefhitsrate" => array (
				"cntrname"	=>	"pipolicyundefhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Responder Policy Statistics",
				"header"	=>	array("Name","Hits","Rate(/s)","UndefHits","Rate(/s)",),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pipolicyhits","pipolicyundefhits",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Responder Policy Statistics",
				"header"	=>	array("Name","Hits","Rate(/s)","UndefHits","Rate(/s)",),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pipolicyhits","pipolicyundefhits",),
			),
		),
),


"statresponderpolicylabel" => Array(
	"cmddispname"		=>	"statresponderpolicylabel",
	"cmdname"		=>	"statresponderpolicylabel",
	"license"		=>	Array("NSKF1_RESPONDER_BIT_POS","NSKF1_RESPONDER_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"labelname" => Array(
				"argname"		=>	"labelname",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "labelname",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"pipolicylabelhits" => Array(
				"cntrname"	=>	"pipolicylabelhits",
				"cntrdescr"	=>	"Policy Label Hits",
				"tooltip"	=>	"Number of times policy label was invoked.",
			),
			"pipolicylabelhitsrate" => array (
				"cntrname"	=>	"pipolicylabelhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Responder Policylabel Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pipolicylabelhits",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Responder Policylabel(s) Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pipolicylabelhits",),
			),
		),
),


"statresponderpolicybinding" => Array(
	"cmddispname"		=>	"statresponderpolicybinding",
	"cmdname"		=>	"statresponderpolicybinding",
	"license"		=>	Array("NSKF1_RESPONDER_BIT_POS","NSKF1_RESPONDER_BIT_POS"),
	"cmddescr"	=>	"     ",
	"hidden"	=>	1,
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"pipolicybindinghits" => Array(
				"cntrname"	=>	"pipolicybindinghits",
				"cntrdescr"	=>	"Policy binding hits",
				"tooltip"	=>	"Number of hits on the policy on this binding",
			),
			"pipolicybindinghitsrate" => array (
				"cntrname"	=>	"pipolicybindinghitsrate",
				"cntrdescr"	=>	"",
			),
			"pipolicybindingundefhits" => Array(
				"cntrname"	=>	"pipolicybindingundefhits",
				"cntrdescr"	=>	"Policy binding undef hits",
				"tooltip"	=>	"Number of undef hits on the policy on this binding",
			),
			"pipolicybindingundefhitsrate" => array (
				"cntrname"	=>	"pipolicybindingundefhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Responder Binding Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pipolicybindinghits","pipolicybindingundefhits",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Responder Binding(s) Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pipolicybindinghits","pipolicybindingundefhits",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Responder Binding(s) Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"subent",
				"counters"	=>	array("pipolicybindinghits","pipolicybindingundefhits",),
			),
		),
),


"statrewritepolicy" => Array(
	"cmddispname"		=>	"statrewritepolicy",
	"cmdname"		=>	"statrewritepolicy",
	"license"		=>	Array("NSKF1_REWRITE_BIT_POS","NSKF1_REWRITE_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"pipolicyhits" => Array(
				"cntrname"	=>	"pipolicyhits",
				"cntrdescr"	=>	"Policy hits",
				"tooltip"	=>	"Number of hits on the policy",
			),
			"pipolicyhitsrate" => array (
				"cntrname"	=>	"pipolicyhitsrate",
				"cntrdescr"	=>	"",
			),
			"pipolicyundefhits" => Array(
				"cntrname"	=>	"pipolicyundefhits",
				"cntrdescr"	=>	"Policy undef hits",
				"tooltip"	=>	"Number of undef hits on the policy",
			),
			"pipolicyundefhitsrate" => array (
				"cntrname"	=>	"pipolicyundefhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Rewrite Policy Statistics",
				"header"	=>	array("Name","Hits","Rate(/s)","UndefHits","Rate(/s)",),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pipolicyhits","pipolicyundefhits",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Rewrite Policy Statistics",
				"header"	=>	array("Name","Hits","Rate(/s)","UndefHits","Rate(/s)",),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pipolicyhits","pipolicyundefhits",),
			),
		),
),


"statrewritepolicylabel" => Array(
	"cmddispname"		=>	"statrewritepolicylabel",
	"cmdname"		=>	"statrewritepolicylabel",
	"license"		=>	Array("NSKF1_REWRITE_BIT_POS","NSKF1_REWRITE_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"labelname" => Array(
				"argname"		=>	"labelname",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "labelname",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"pipolicylabelhits" => Array(
				"cntrname"	=>	"pipolicylabelhits",
				"cntrdescr"	=>	"Policy Label Hits",
				"tooltip"	=>	"Number of times policy label was invoked.",
			),
			"pipolicylabelhitsrate" => array (
				"cntrname"	=>	"pipolicylabelhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Rewrite Policylabel Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pipolicylabelhits",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Rewrite Policylabel(s) Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pipolicylabelhits",),
			),
		),
),


"statrewritepolicybinding" => Array(
	"cmddispname"		=>	"statrewritepolicybinding",
	"cmdname"		=>	"statrewritepolicybinding",
	"license"		=>	Array("NSKF1_REWRITE_BIT_POS","NSKF1_REWRITE_BIT_POS"),
	"cmddescr"	=>	"     ",
	"hidden"	=>	1,
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"pipolicybindinghits" => Array(
				"cntrname"	=>	"pipolicybindinghits",
				"cntrdescr"	=>	"Policy binding hits",
				"tooltip"	=>	"Number of hits on the policy on this binding",
			),
			"pipolicybindinghitsrate" => array (
				"cntrname"	=>	"pipolicybindinghitsrate",
				"cntrdescr"	=>	"",
			),
			"pipolicybindingundefhits" => Array(
				"cntrname"	=>	"pipolicybindingundefhits",
				"cntrdescr"	=>	"Policy binding undef hits",
				"tooltip"	=>	"Number of undef hits on the policy on this binding",
			),
			"pipolicybindingundefhitsrate" => array (
				"cntrname"	=>	"pipolicybindingundefhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Rewrite Binding Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pipolicybindinghits","pipolicybindingundefhits",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Rewrite Binding(s) Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pipolicybindinghits","pipolicybindingundefhits",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Rewrite Binding(s) Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"subent",
				"counters"	=>	array("pipolicybindinghits","pipolicybindingundefhits",),
			),
		),
),


"statsnmp" => Array(
	"cmddispname"		=>	"statsnmp",
	"cmdname"		=>	"statsnmp",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"snmptotrxpkts" => Array(
				"cntrname"	=>	"snmptotrxpkts",
				"cntrdescr"	=>	"SNMP packets received",
				"tooltip"	=>	"SNMP packets received.",
			),
			"snmprxpktsrate" => array (
				"cntrname"	=>	"snmprxpktsrate",
				"cntrdescr"	=>	"",
			),
			"snmptottxpkts" => Array(
				"cntrname"	=>	"snmptottxpkts",
				"cntrdescr"	=>	"SNMP packets sent",
				"tooltip"	=>	"SNMP packets transmitted.",
			),
			"snmptxpktsrate" => array (
				"cntrname"	=>	"snmptxpktsrate",
				"cntrdescr"	=>	"",
			),
			"snmptotgetreqs" => Array(
				"cntrname"	=>	"snmptotgetreqs",
				"cntrdescr"	=>	"Get requests receieved",
				"tooltip"	=>	"SNMP Get-Request PDUs that have been accepted and processed.",
			),
			"snmpgetreqsrate" => array (
				"cntrname"	=>	"snmpgetreqsrate",
				"cntrdescr"	=>	"",
			),
			"snmptotgetnextreqs" => Array(
				"cntrname"	=>	"snmptotgetnextreqs",
				"cntrdescr"	=>	"Get-next requests receieved",
				"tooltip"	=>	"SNMP Get-Next PDUs that have been accepted and processed.",
			),
			"snmpgetnextreqsrate" => array (
				"cntrname"	=>	"snmpgetnextreqsrate",
				"cntrdescr"	=>	"",
			),
			"snmptotgetbulkreqs" => Array(
				"cntrname"	=>	"snmptotgetbulkreqs",
				"cntrdescr"	=>	"Get-bulk requests received",
				"tooltip"	=>	"SNMP Get-Bulk PDUs that have been accepted and proŽcessed.",
			),
			"snmpgetbulkreqsrate" => array (
				"cntrname"	=>	"snmpgetbulkreqsrate",
				"cntrdescr"	=>	"",
			),
			"snmptotresponses" => Array(
				"cntrname"	=>	"snmptotresponses",
				"cntrdescr"	=>	"Responses sent",
				"tooltip"	=>	"SNMP Get-Response PDUs that have been generated by the NetScaler.",
			),
			"snmpresponsesrate" => array (
				"cntrname"	=>	"snmpresponsesrate",
				"cntrdescr"	=>	"",
			),
			"snmptottraps" => Array(
				"cntrname"	=>	"snmptottraps",
				"cntrdescr"	=>	"Traps messages sent",
				"tooltip"	=>	"SNMP Trap PDUs that have been generated by the NetScaler.",
			),
			"snmptoterrreqdropped" => Array(
				"cntrname"	=>	"snmptoterrreqdropped",
				"cntrdescr"	=>	"Requests dropped",
				"tooltip"	=>	"SNMP requests dropped.",
			),
			"snmptotparseerrs" => Array(
				"cntrname"	=>	"snmptotparseerrs",
				"cntrdescr"	=>	"ASN.1/BER errors in requests",
				"tooltip"	=>	"Number of ASN.1 or BER errors encountered when decoding received SNMP Messages.",
			),
			"snmptotbadversions" => Array(
				"cntrname"	=>	"snmptotbadversions",
				"cntrdescr"	=>	"Unsupported SNMP version",
				"tooltip"	=>	"Number of SNMP messages received, which were for an unsupported SNMP version.",
			),
			"snmptotbadcommname" => Array(
				"cntrname"	=>	"snmptotbadcommname",
				"cntrdescr"	=>	"Unknown community name",
				"tooltip"	=>	"SNMP messages received, which used an SNMP community name not known to the NetScaler.",
			),
			"snmptotbadcommuse" => Array(
				"cntrname"	=>	"snmptotbadcommuse",
				"cntrdescr"	=>	"No permission on community",
				"tooltip"	=>	"The total number of SNMP Messages received that represented an SNMP operation which was not allowed by the SNMP community named in the Message.",
			),
			"snmpunsupportedsecuritylevel" => Array(
				"cntrname"	=>	"snmpunsupportedsecuritylevel",
				"cntrdescr"	=>	"Unsupported security level",
				"tooltip"	=>	"SNMP packets that were dropped because they requested a security level that was<br><br>unknown to the NetScaler or otherwise unavailable.",
			),
			"snmpnotintimewindow" => Array(
				"cntrname"	=>	"snmpnotintimewindow",
				"cntrdescr"	=>	"Not in time window",
				"tooltip"	=>	"SNMP packets that were dropped because they appeared outside of the authoritative SNMP engine's window.",
			),
			"snmpunknownusername" => Array(
				"cntrname"	=>	"snmpunknownusername",
				"cntrdescr"	=>	"Unknown user name",
				"tooltip"	=>	"SNMP packets that were dropped because they referenced a user that was  not  known to the SNMP engine.",
			),
			"snmpunknownengineids" => Array(
				"cntrname"	=>	"snmpunknownengineids",
				"cntrdescr"	=>	"Unknown engine Id",
				"tooltip"	=>	"SNMP packets that were dropped because they referenced an SNMP engine ID that was not known to the NetScaler.",
			),
			"snmpwrongdigests" => Array(
				"cntrname"	=>	"snmpwrongdigests",
				"cntrdescr"	=>	"Wrong digest value",
				"tooltip"	=>	"SNMP packets that were dropped because they did not contain the expected digest value.",
			),
			"snmpdecryptionerrors" => Array(
				"cntrname"	=>	"snmpdecryptionerrors",
				"cntrdescr"	=>	"Decryption errors",
				"tooltip"	=>	"SNMP packets that were dropped because they could not be decrypted.",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Run Time:",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("snmptotrxpkts","snmptottxpkts","snmptotgetreqs","snmptotgetnextreqs","snmptotgetbulkreqs","snmptotresponses","snmptottraps",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Errors:",
				"header"	=>	array(),
				"format"	=>	"1C",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("snmptoterrreqdropped","snmptotparseerrs","snmptotbadversions","snmptotbadcommname","snmptotbadcommuse","snmpunsupportedsecuritylevel","snmpnotintimewindow","snmpunknownusername","snmpunknownengineids","snmpwrongdigests","snmpdecryptionerrors",),
			),
		),
),


"statspilloverpolicy" => Array(
	"cmddispname"		=>	"statspilloverpolicy",
	"cmdname"		=>	"statspilloverpolicy",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"pipolicyhits" => Array(
				"cntrname"	=>	"pipolicyhits",
				"cntrdescr"	=>	"Policy hits",
				"tooltip"	=>	"Number of hits on the policy",
			),
			"pipolicyhitsrate" => array (
				"cntrname"	=>	"pipolicyhitsrate",
				"cntrdescr"	=>	"",
			),
			"pipolicyundefhits" => Array(
				"cntrname"	=>	"pipolicyundefhits",
				"cntrdescr"	=>	"Policy undef hits",
				"tooltip"	=>	"Number of undef hits on the policy",
			),
			"pipolicyundefhitsrate" => array (
				"cntrname"	=>	"pipolicyundefhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Spillover Policy Statistics",
				"header"	=>	array(" ","Hits","Rate(/s)","UndefHits","Rate(/s)",),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pipolicyhits","pipolicyundefhits",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Spillover Policy(s) Statistics",
				"header"	=>	array(" ","Hits","Rate(/s)","UndefHits","Rate(/s)",),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pipolicyhits","pipolicyundefhits",),
			),
		),
),


"statsc" => Array(
	"cmddispname"		=>	"statsc",
	"cmdname"		=>	"statsc",
	"license"		=>	Array("NSKF1_IOH_BIT_POS","NSKF1_IOH_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"sctotcondtriggered" => Array(
				"cntrname"	=>	"sctotcondtriggered",
				"cntrdescr"	=>	"SC condition triggered",
				"tooltip"	=>	"Number of times that SureConnect conditions were triggered.",
			),
			"sccondtriggeredrate" => array (
				"cntrname"	=>	"sccondtriggeredrate",
				"cntrdescr"	=>	"",
			),
			"scpolicyurlhits" => Array(
				"cntrname"	=>	"scpolicyurlhits",
				"cntrdescr"	=>	"Policy matches",
				"tooltip"	=>	"Total number of incoming requests that matched configured sureconnect policies.",
			),
			"scpolicyurlhitsrate" => array (
				"cntrname"	=>	"scpolicyurlhitsrate",
				"cntrdescr"	=>	"",
			),
			"scpopups" => Array(
				"cntrname"	=>	"scpopups",
				"cntrdescr"	=>	"SC responses sent",
				"tooltip"	=>	"Total number of in-memory java script  served which throws the pop-up window.",
			),
			"scpopupsrate" => array (
				"cntrname"	=>	"scpopupsrate",
				"cntrdescr"	=>	"",
			),
			"sctotreissuedrequests" => Array(
				"cntrname"	=>	"sctotreissuedrequests",
				"cntrdescr"	=>	"Reissued requests",
				"tooltip"	=>	"Total number of reissued SureConnect requests.",
			),
			"screissuedrequestsrate" => array (
				"cntrname"	=>	"screissuedrequestsrate",
				"cntrdescr"	=>	"",
			),
			"scsessionreqs" => Array(
				"cntrname"	=>	"scsessionreqs",
				"cntrdescr"	=>	"Valid reissued requests",
				"tooltip"	=>	"Total number of requests that were  handled in a single SureConnect session.",
			),
			"scsessionreqsrate" => array (
				"cntrname"	=>	"scsessionreqsrate",
				"cntrdescr"	=>	"",
			),
			"scaltconturls" => Array(
				"cntrname"	=>	"scaltconturls",
				"cntrdescr"	=>	"Alternate content requests",
				"tooltip"	=>	"Total number of alternate content served which throws the pop-up window.",
			),
			"scaltconturlsrate" => array (
				"cntrname"	=>	"scaltconturlsrate",
				"cntrdescr"	=>	"",
			),
			"scpostreqs" => Array(
				"cntrname"	=>	"scpostreqs",
				"cntrdescr"	=>	"SC POST requests",
				"tooltip"	=>	"Total number of   HTTP POST requests that triggered SureConnect feature.",
			),
			"scpostreqsrate" => array (
				"cntrname"	=>	"scpostreqsrate",
				"cntrdescr"	=>	"",
			),
			"scresetstats" => Array(
				"cntrname"	=>	"scresetstats",
				"cntrdescr"	=>	"SC statistics timeout",
				"tooltip"	=>	"Toal number of times that SureConnect statistics were reset.",
			),
			"scresetstatsrate" => array (
				"cntrname"	=>	"scresetstatsrate",
				"cntrdescr"	=>	"",
			),
			"scunsupbrow" => Array(
				"cntrname"	=>	"scunsupbrow",
				"cntrdescr"	=>	"Unsupported browsers",
				"tooltip"	=>	"Total number of requests that came from all unsupported browsers.",
			),
			"scunsupbrowrate" => array (
				"cntrname"	=>	"scunsupbrowrate",
				"cntrdescr"	=>	"",
			),
			"scfaultycookies" => Array(
				"cntrname"	=>	"scfaultycookies",
				"cntrdescr"	=>	"Tampered SC cookies",
				"tooltip"	=>	"Total number of corrupted SureConnect cookies.",
			),
			"scfaultycookiesrate" => array (
				"cntrname"	=>	"scfaultycookiesrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"text" => "NONE",
				"flag" => "Summary",
				"key" => "default",
				"relatedlink_align"	=> "right",
				"relatedlink"	=>
					Array(
						array(
							 "text" => "Per Policy statistics", 
							 "cmd" => "statscpolicy", 
							 "type" => "summary", 
						),
					),
			),
			Array(
				"dhID"	=>	1,
				"title"	=>	"",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("scpolicyurlhits","sctotcondtriggered","scpopups","sctotreissuedrequests","scsessionreqs","scaltconturls","scpostreqs","scresetstats",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"SureConnect Errors",
				"header"	=>	array("","Total",),
				"format"	=>	"1C",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("scunsupbrow","scfaultycookies",),
			),
		),
),


"statscpolicy" => Array(
	"cmddispname"		=>	"statscpolicy",
	"cmdname"		=>	"statscpolicy",
	"license"		=>	Array("NSKF1_IOH_BIT_POS","NSKF1_IOH_BIT_POS"),
	"cmddescr"	=>	"     ",
	"bindings"	=>	1,
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"avgservertransactiontime" => Array(
				"cntrname"	=>	"avgservertransactiontime",
				"cntrdescr"	=>	"Average server TTLB",
				"tooltip"	=>	"Average server transaction time in seconds for this SureConnect Policy.",
			),
			"avgservertransactiontimerate" => array (
				"cntrname"	=>	"avgservertransactiontimerate",
				"cntrdescr"	=>	"",
			),
			"scaverageclientttlb" => Array(
				"cntrname"	=>	"scaverageclientttlb",
				"cntrdescr"	=>	"Average client TTLB",
				"tooltip"	=>	"Average value of the client Time-To-Last-Byte in seconds for this SureConnect policy.",
			),
			"scaverageclientttlbrate" => array (
				"cntrname"	=>	"scaverageclientttlbrate",
				"cntrdescr"	=>	"",
			),
			"scphysicalserviceip" => Array(
				"cntrname"	=>	"scphysicalserviceip",
				"cntrdescr"	=>	"Physical service IP",
				"tooltip"	=>	"IP address of the service in dotted notation for which these statistics are maintained.",
				"type"		=>	"NS_VALIPSTR",
			),
			"scphysicalserviceport" => Array(
				"cntrname"	=>	"scphysicalserviceport",
				"cntrdescr"	=>	"Physical service port",
				"tooltip"	=>	"Port of the service for which these statistics are maintained.",
				"type"		=>	"NS_VALPORT",
			),
			"sccurrentclientconnections" => Array(
				"cntrname"	=>	"sccurrentclientconnections",
				"cntrdescr"	=>	"Current client connections",
				"tooltip"	=>	"Number of clients currently  allowed a server connection by this SureConnect policy.",
			),
			"sccurrentclientconnectionsrate" => array (
				"cntrname"	=>	"sccurrentclientconnectionsrate",
				"cntrdescr"	=>	"",
			),
			"sccurrentwaitingclients" => Array(
				"cntrname"	=>	"sccurrentwaitingclients",
				"cntrdescr"	=>	"Current SC queue length",
				"tooltip"	=>	"Current number of SureConnect priority clients that are waiting for a server connection.",
			),
			"sccurrentwaitingclientsrate" => array (
				"cntrname"	=>	"sccurrentwaitingclientsrate",
				"cntrdescr"	=>	"",
			),
			"totopenconn" => Array(
				"cntrname"	=>	"totopenconn",
				"cntrdescr"	=>	"Current server connections",
				"tooltip"	=>	"Current number of open connections to the servers matching this policy.",
			),
			"openconnrate" => array (
				"cntrname"	=>	"openconnrate",
				"cntrdescr"	=>	"",
			),
			"sccurrentwaitingtime" => Array(
				"cntrname"	=>	"sccurrentwaitingtime",
				"cntrdescr"	=>	"Estimated waiting time (Sec)",
				"tooltip"	=>	"Value of the currently estimated waiting time in seconds for the configured URL.",
			),
			"sccurrentwaitingtimerate" => array (
				"cntrname"	=>	"sccurrentwaitingtimerate",
				"cntrdescr"	=>	"",
			),
			"sctotalclientconnections" => Array(
				"cntrname"	=>	"sctotalclientconnections",
				"cntrdescr"	=>	"Client TCP connections",
				"tooltip"	=>	"Total number of clients that were allowed a server connection by this SureConnect policy.",
			),
			"scclientconnectionsrate" => array (
				"cntrname"	=>	"scclientconnectionsrate",
				"cntrdescr"	=>	"",
			),
			"sctotalserverconnections" => Array(
				"cntrname"	=>	"sctotalserverconnections",
				"cntrdescr"	=>	"Server TCP connections",
				"tooltip"	=>	"Total number of server connections that were established through this SureConnect policy.",
			),
			"scserverconnectionsrate" => array (
				"cntrname"	=>	"scserverconnectionsrate",
				"cntrdescr"	=>	"",
			),
			"totclienttransaction" => Array(
				"cntrname"	=>	"totclienttransaction",
				"cntrdescr"	=>	"Client HTTP transactions",
				"tooltip"	=>	"Total number of client transactions processed by this SureConnect policy.",
			),
			"clienttransactionrate" => array (
				"cntrname"	=>	"clienttransactionrate",
				"cntrdescr"	=>	"",
			),
			"sctotalservertransactions" => Array(
				"cntrname"	=>	"sctotalservertransactions",
				"cntrdescr"	=>	"Server HTTP transactions",
				"tooltip"	=>	"Number of 200 OK responses received from the web server by this SureConnect policy.",
			),
			"scservertransactionsrate" => array (
				"cntrname"	=>	"scservertransactionsrate",
				"cntrdescr"	=>	"",
			),
			"sctotalrequestsreceived" => Array(
				"cntrname"	=>	"sctotalrequestsreceived",
				"cntrdescr"	=>	"Requests received",
				"tooltip"	=>	"Total number of requests received by this SureConnect policy.",
			),
			"screquestsreceivedrate" => array (
				"cntrname"	=>	"screquestsreceivedrate",
				"cntrdescr"	=>	"",
			),
			"sctotalrequestbytes" => Array(
				"cntrname"	=>	"sctotalrequestbytes",
				"cntrdescr"	=>	"Request bytes received",
				"tooltip"	=>	"Total number of request bytes received by this SureConnect policy.",
			),
			"screquestbytesrate" => array (
				"cntrname"	=>	"screquestbytesrate",
				"cntrdescr"	=>	"",
			),
			"sctotalresponsesreceived" => Array(
				"cntrname"	=>	"sctotalresponsesreceived",
				"cntrdescr"	=>	"Server responses received",
				"tooltip"	=>	"Total number of server responses received by this SureConnect policy.",
			),
			"scresponsesreceivedrate" => array (
				"cntrname"	=>	"scresponsesreceivedrate",
				"cntrdescr"	=>	"",
			),
			"sctotalresponsebytes" => Array(
				"cntrname"	=>	"sctotalresponsebytes",
				"cntrdescr"	=>	"Response bytes received",
				"tooltip"	=>	"Total number of response bytes received by this SureConnect policy.",
			),
			"scresponsebytesrate" => array (
				"cntrname"	=>	"scresponsebytesrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"SC policy summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("scphysicalserviceip","scphysicalserviceport",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Runtime statistics",
				"header"	=>	array("","Value",),
				"format"	=>	"1C",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("sccurrentclientconnections","sccurrentwaitingclients","totopenconn","sccurrentwaitingtime",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Cumulative statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("sctotalclientconnections","sctotalserverconnections","totclienttransaction","sctotalservertransactions","sctotalrequestsreceived","sctotalrequestbytes","sctotalresponsesreceived","sctotalresponsebytes",),
			),
			Array(
				"dhID"	=>	4,
				"title"	=>	"Timing statistics",
				"header"	=>	array("","Value (milliseconds)",),
				"format"	=>	"1C",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("scaverageclientttlb","avgservertransactiontime",),
			),
			Array(
				"text" => "NONE",
				"flag" => "Summary",
				"key" => "default",
				"relatedlink_align"	=> "right",
				"relatedlink"	=>
					Array(
						array(
							 "text" => "Global Statistics", 
							 "cmd" => "statsc", 
							 "type" => "summary", 
						),
					),
			),
			Array(
				"dhID"	=>	5,
				"title"	=>	"SC policy summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("scphysicalserviceip","scphysicalserviceport","sccurrentclientconnections","sccurrentwaitingclients","totopenconn",),
			),
		),
),


"statssl" => Array(
	"cmddispname"		=>	"statssl",
	"cmdname"		=>	"statssl",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"sslcryptoutilizationstat" => Array(
				"cntrname"	=>	"sslcryptoutilizationstat",
				"cntrdescr"	=>	"SSL crypto utilization (%)",
				"maxval"	=>     4294967294,
				"tooltip"	=>	"Utilization of the hardware crypto resource. Only valid values are 0-100%. Only works for platform with N3 chips",
			),
			"sslnumcardsup" => Array(
				"cntrname"	=>	"sslnumcardsup",
				"cntrdescr"	=>	"# SSL cards UP",
				"tooltip"	=>	"Number of SSL cards that are UP. If the number of cards UP is lower than a threshold, a failover is initiated.",
			),
			"sslcardstatus" => Array(
				"cntrname"	=>	"sslcardstatus",
				"cntrdescr"	=>	"SSL crypto card status",
				"tooltip"	=>	"Status of the SSL card(s). The value should be interpreted in binary form, with each set bit indicates a card as UP.",
			),
			"sslcards" => Array(
				"cntrname"	=>	"sslcards",
				"cntrdescr"	=>	"# SSL cards present",
				"tooltip"	=>	"Number of SSL crypto cards present on the NetScaler appliance.",
			),
			"sslenginestatus" => Array(
				"cntrname"	=>	"sslenginestatus",
				"cntrdescr"	=>	"SSL engine status",
				"tooltip"	=>	"State of the SSL Engine (1=UP/0=DOWN). This state is decided based on SSL Feature/License status and minimum number of cards UP.",
			),
			"ssltotsessions" => Array(
				"cntrname"	=>	"ssltotsessions",
				"cntrdescr"	=>	"SSL sessions ",
				"tooltip"	=>	"Number of SSL sessions on the NetScaler appliance.",
			),
			"sslsessionsrate" => array (
				"cntrname"	=>	"sslsessionsrate",
				"cntrdescr"	=>	"",
			),
			"ssltottransactions" => Array(
				"cntrname"	=>	"ssltottransactions",
				"cntrdescr"	=>	"SSL transactions",
				"tooltip"	=>	"Number of SSL transactions on the NetScaler appliance.",
			),
			"ssltransactionsrate" => array (
				"cntrname"	=>	"ssltransactionsrate",
				"cntrdescr"	=>	"",
			),
			"ssltotsslv2transactions" => Array(
				"cntrname"	=>	"ssltotsslv2transactions",
				"cntrdescr"	=>	"SSLv2 transactions",
				"tooltip"	=>	"Number of SSLv2 transactions on the NetScaler appliance.",
			),
			"sslsslv2transactionsrate" => array (
				"cntrname"	=>	"sslsslv2transactionsrate",
				"cntrdescr"	=>	"",
			),
			"ssltotsslv3transactions" => Array(
				"cntrname"	=>	"ssltotsslv3transactions",
				"cntrdescr"	=>	"SSLv3 transactions",
				"tooltip"	=>	"Total number of SSLv3 transactions on the NetScaler appliance.",
			),
			"sslsslv3transactionsrate" => array (
				"cntrname"	=>	"sslsslv3transactionsrate",
				"cntrdescr"	=>	"",
			),
			"ssltottlsv1transactions" => Array(
				"cntrname"	=>	"ssltottlsv1transactions",
				"cntrdescr"	=>	"TLSv1 transactions",
				"tooltip"	=>	"Number of TLSv1 transactions on the NetScaler appliance.",
			),
			"ssltlsv1transactionsrate" => array (
				"cntrname"	=>	"ssltlsv1transactionsrate",
				"cntrdescr"	=>	"",
			),
			"ssltottlsv11transactions" => Array(
				"cntrname"	=>	"ssltottlsv11transactions",
				"cntrdescr"	=>	"TLSv1.1 transactions",
				"tooltip"	=>	"Number of TLSv1.1 transactions on the NetScaler appliance.",
			),
			"ssltlsv11transactionsrate" => array (
				"cntrname"	=>	"ssltlsv11transactionsrate",
				"cntrdescr"	=>	"",
			),
			"ssltottlsv12transactions" => Array(
				"cntrname"	=>	"ssltottlsv12transactions",
				"cntrdescr"	=>	"TLSv1.2 transactions",
				"tooltip"	=>	"Number of TLSv1.2 transactions on the NetScaler appliance.",
			),
			"ssltlsv12transactionsrate" => array (
				"cntrname"	=>	"ssltlsv12transactionsrate",
				"cntrdescr"	=>	"",
			),
			"ssltotsslv2sessions" => Array(
				"cntrname"	=>	"ssltotsslv2sessions",
				"cntrdescr"	=>	"SSLv2 sessions",
				"tooltip"	=>	"Number of SSLv2 sessions on the NetScaler appliance.",
			),
			"sslsslv2sessionsrate" => array (
				"cntrname"	=>	"sslsslv2sessionsrate",
				"cntrdescr"	=>	"",
			),
			"ssltotsslv3sessions" => Array(
				"cntrname"	=>	"ssltotsslv3sessions",
				"cntrdescr"	=>	"SSLv3 sessions",
				"tooltip"	=>	"Number of SSLv3 sessions on the NetScaler appliance.",
			),
			"sslsslv3sessionsrate" => array (
				"cntrname"	=>	"sslsslv3sessionsrate",
				"cntrdescr"	=>	"",
			),
			"ssltottlsv1sessions" => Array(
				"cntrname"	=>	"ssltottlsv1sessions",
				"cntrdescr"	=>	"TLSv1 sessions",
				"tooltip"	=>	"Number of TLSv1 sessions on the NetScaler appliance.",
			),
			"ssltlsv1sessionsrate" => array (
				"cntrname"	=>	"ssltlsv1sessionsrate",
				"cntrdescr"	=>	"",
			),
			"ssltottlsv11sessions" => Array(
				"cntrname"	=>	"ssltottlsv11sessions",
				"cntrdescr"	=>	"TLSv1.1 sessions",
				"tooltip"	=>	"Number of TLSv1.1 sessions on the NetScaler appliance.",
			),
			"ssltlsv11sessionsrate" => array (
				"cntrname"	=>	"ssltlsv11sessionsrate",
				"cntrdescr"	=>	"",
			),
			"ssltottlsv12sessions" => Array(
				"cntrname"	=>	"ssltottlsv12sessions",
				"cntrdescr"	=>	"TLSv1.2 sessions",
				"tooltip"	=>	"Number of TLSv1.2 sessions on the NetScaler appliance.",
			),
			"ssltlsv12sessionsrate" => array (
				"cntrname"	=>	"ssltlsv12sessionsrate",
				"cntrdescr"	=>	"",
			),
			"ssltotnewsessions" => Array(
				"cntrname"	=>	"ssltotnewsessions",
				"cntrdescr"	=>	"new SSL sessions",
				"tooltip"	=>	"Number of new SSL sessions created on the NetScaler appliance.",
			),
			"sslnewsessionsrate" => array (
				"cntrname"	=>	"sslnewsessionsrate",
				"cntrdescr"	=>	"",
			),
			"ssltotsessionmiss" => Array(
				"cntrname"	=>	"ssltotsessionmiss",
				"cntrdescr"	=>	"SSL session misses",
				"tooltip"	=>	"Number of SSL session reuse misses on the NetScaler appliance.",
			),
			"sslsessionmissrate" => array (
				"cntrname"	=>	"sslsessionmissrate",
				"cntrdescr"	=>	"",
			),
			"ssltotsessionhits" => Array(
				"cntrname"	=>	"ssltotsessionhits",
				"cntrdescr"	=>	"SSL session hits",
				"tooltip"	=>	"Number of SSL session reuse hits on the NetScaler appliance.",
			),
			"sslsessionhitsrate" => array (
				"cntrname"	=>	"sslsessionhitsrate",
				"cntrdescr"	=>	"",
			),
			"sslbetotsessions" => Array(
				"cntrname"	=>	"sslbetotsessions",
				"cntrdescr"	=>	"SSL sessions",
				"tooltip"	=>	"Number of back-end SSL sessions on the NetScaler appliance.",
			),
			"sslbesessionsrate" => array (
				"cntrname"	=>	"sslbesessionsrate",
				"cntrdescr"	=>	"",
			),
			"sslbetotsslv3sessions" => Array(
				"cntrname"	=>	"sslbetotsslv3sessions",
				"cntrdescr"	=>	"SSLv3 sessions",
				"cntrdescr2"	=>	"Backend SSLv3 sessions",
				"tooltip"	=>	"Number of back-end SSLv3 sessions on the NetScaler appliance.",
			),
			"sslbesslv3sessionsrate" => array (
				"cntrname"	=>	"sslbesslv3sessionsrate",
				"cntrdescr"	=>	"",
			),
			"sslbetottlsv1sessions" => Array(
				"cntrname"	=>	"sslbetottlsv1sessions",
				"cntrdescr"	=>	"TLSv1 sessions",
				"cntrdescr2"	=>	"Backend TLSv1 sessions",
				"tooltip"	=>	"Number of back-end TLSv1 sessions on the NetScaler appliance.",
			),
			"sslbetlsv1sessionsrate" => array (
				"cntrname"	=>	"sslbetlsv1sessionsrate",
				"cntrdescr"	=>	"",
			),
			"sslbetottlsv11sessions" => Array(
				"cntrname"	=>	"sslbetottlsv11sessions",
				"cntrdescr"	=>	"TLSv1.1 sessions",
				"cntrdescr2"	=>	"Backend TLSv1.1 sessions",
				"tooltip"	=>	"Number of back-end TLSv1.1 sessions on the NetScaler appliance.",
			),
			"sslbetlsv11sessionsrate" => array (
				"cntrname"	=>	"sslbetlsv11sessionsrate",
				"cntrdescr"	=>	"",
			),
			"sslbetottlsv12sessions" => Array(
				"cntrname"	=>	"sslbetottlsv12sessions",
				"cntrdescr"	=>	"TLSv1.2 sessions",
				"cntrdescr2"	=>	"Backend TLSv1.2 sessions",
				"tooltip"	=>	"Number of back-end TLSv1.2 sessions on the NetScaler appliance.",
			),
			"sslbetlsv12sessionsrate" => array (
				"cntrname"	=>	"sslbetlsv12sessionsrate",
				"cntrdescr"	=>	"",
			),
			"sslbetotsessionmultiplexattempts" => Array(
				"cntrname"	=>	"sslbetotsessionmultiplexattempts",
				"cntrdescr"	=>	"Session multiplex attempts",
				"tooltip"	=>	"Number of back-end SSL session multiplex attempts on the NetScaler appliance.",
			),
			"sslbesessionmultiplexattemptsrate" => array (
				"cntrname"	=>	"sslbesessionmultiplexattemptsrate",
				"cntrdescr"	=>	"",
			),
			"sslbetotsessionmultiplexattemptsuccess" => Array(
				"cntrname"	=>	"sslbetotsessionmultiplexattemptsuccess",
				"cntrdescr"	=>	"Session multiplex successes",
				"tooltip"	=>	"Number of back-end SSL session multiplex successes on the NetScaler appliance.",
			),
			"sslbesessionmultiplexattemptsuccessrate" => array (
				"cntrname"	=>	"sslbesessionmultiplexattemptsuccessrate",
				"cntrdescr"	=>	"",
			),
			"sslbetotsessionmultiplexattemptfails" => Array(
				"cntrname"	=>	"sslbetotsessionmultiplexattemptfails",
				"cntrdescr"	=>	"Session multiplex failures",
				"tooltip"	=>	"Number of back-end SSL session multiplex failures on the NetScaler appliance.",
			),
			"sslbesessionmultiplexattemptfailsrate" => array (
				"cntrname"	=>	"sslbesessionmultiplexattemptfailsrate",
				"cntrdescr"	=>	"",
			),
			"ssltotenc" => Array(
				"cntrname"	=>	"ssltotenc",
				"cntrdescr"	=>	"Bytes encrypted",
				"tooltip"	=>	"Number of bytes encrypted on the NetScaler appliance.",
			),
			"sslencrate" => array (
				"cntrname"	=>	"sslencrate",
				"cntrdescr"	=>	"",
			),
			"ssltotdec" => Array(
				"cntrname"	=>	"ssltotdec",
				"cntrdescr"	=>	"Bytes decrypted",
				"tooltip"	=>	"Number of bytes decrypted on the NetScaler appliance.",
			),
			"ssldecrate" => array (
				"cntrname"	=>	"ssldecrate",
				"cntrdescr"	=>	"",
			),
			"ssltotsslserverinrecords" => Array(
				"cntrname"	=>	"ssltotsslserverinrecords",
				"cntrdescr"	=>	"Server in record",
				"tooltip"	=>	"Number server in record on the NetScaler appliance.",
			),
			"sslsslserverinrecordsrate" => array (
				"cntrname"	=>	"sslsslserverinrecordsrate",
				"cntrdescr"	=>	"",
			),
			"ssltotrenegsessions" => Array(
				"cntrname"	=>	"ssltotrenegsessions",
				"cntrdescr"	=>	"SSL session renegotiations",
				"tooltip"	=>	"Number of SSL session renegotiations on the NetScaler appliance.",
			),
			"sslrenegsessionsrate" => array (
				"cntrname"	=>	"sslrenegsessionsrate",
				"cntrdescr"	=>	"",
			),
			"ssltotsslv3renegsessions" => Array(
				"cntrname"	=>	"ssltotsslv3renegsessions",
				"cntrdescr"	=>	"SSLv3 session renegotiations",
				"tooltip"	=>	"Number of session renegotiations done on SSLv3.",
			),
			"sslsslv3renegsessionsrate" => array (
				"cntrname"	=>	"sslsslv3renegsessionsrate",
				"cntrdescr"	=>	"",
			),
			"ssltottlsv1renegsessions" => Array(
				"cntrname"	=>	"ssltottlsv1renegsessions",
				"cntrdescr"	=>	"TLSv1 session renegotiations",
				"tooltip"	=>	"Number of SSL session renegotiations done on TLSv1.",
			),
			"ssltlsv1renegsessionsrate" => array (
				"cntrname"	=>	"ssltlsv1renegsessionsrate",
				"cntrdescr"	=>	"",
			),
			"ssltottlsv11renegsessions" => Array(
				"cntrname"	=>	"ssltottlsv11renegsessions",
				"cntrdescr"	=>	"TLSv1.1 session renegotiations",
				"tooltip"	=>	"Number of SSL session renegotiations done on TLSv1.1.",
			),
			"ssltlsv11renegsessionsrate" => array (
				"cntrname"	=>	"ssltlsv11renegsessionsrate",
				"cntrdescr"	=>	"",
			),
			"ssltottlsv12renegsessions" => Array(
				"cntrname"	=>	"ssltottlsv12renegsessions",
				"cntrdescr"	=>	"TLSv1.2 session renegotiations",
				"tooltip"	=>	"Number of SSL session renegotiations done on TLSv1.2.",
			),
			"ssltlsv12renegsessionsrate" => array (
				"cntrname"	=>	"ssltlsv12renegsessionsrate",
				"cntrdescr"	=>	"",
			),
			"ssltotrsa512keyexchanges" => Array(
				"cntrname"	=>	"ssltotrsa512keyexchanges",
				"cntrdescr"	=>	"RSA 512-bit key exchanges",
				"tooltip"	=>	"Number of RSA 512-bit key exchanges on the NetScaler appliance.",
			),
			"sslrsa512keyexchangesrate" => array (
				"cntrname"	=>	"sslrsa512keyexchangesrate",
				"cntrdescr"	=>	"",
			),
			"ssltotrsa1024keyexchanges" => Array(
				"cntrname"	=>	"ssltotrsa1024keyexchanges",
				"cntrdescr"	=>	"RSA 1024-bit key exchanges",
				"tooltip"	=>	"Number of RSA 1024-bit key exchanges on the NetScaler appliance.",
			),
			"sslrsa1024keyexchangesrate" => array (
				"cntrname"	=>	"sslrsa1024keyexchangesrate",
				"cntrdescr"	=>	"",
			),
			"ssltotrsa2048keyexchanges" => Array(
				"cntrname"	=>	"ssltotrsa2048keyexchanges",
				"cntrdescr"	=>	"RSA 2048-bit key exchanges",
				"tooltip"	=>	"Number of RSA 2048-bit key exchanges on the NetScaler appliance.",
			),
			"sslrsa2048keyexchangesrate" => array (
				"cntrname"	=>	"sslrsa2048keyexchangesrate",
				"cntrdescr"	=>	"",
			),
			"ssltotrsa4096keyexchanges" => Array(
				"cntrname"	=>	"ssltotrsa4096keyexchanges",
				"cntrdescr"	=>	"RSA 4096-bit key exchanges",
				"tooltip"	=>	"Number of RSA 4096-bit key exchanges on the NetScaler appliance.",
			),
			"sslrsa4096keyexchangesrate" => array (
				"cntrname"	=>	"sslrsa4096keyexchangesrate",
				"cntrdescr"	=>	"",
			),
			"ssltotdh512keyexchanges" => Array(
				"cntrname"	=>	"ssltotdh512keyexchanges",
				"cntrdescr"	=>	"DH 512-bit key exchanges",
				"tooltip"	=>	"Number of Diffie-Helman 512-bit key exchanges on the NetScaler appliance.",
			),
			"ssldh512keyexchangesrate" => array (
				"cntrname"	=>	"ssldh512keyexchangesrate",
				"cntrdescr"	=>	"",
			),
			"ssltotdh1024keyexchanges" => Array(
				"cntrname"	=>	"ssltotdh1024keyexchanges",
				"cntrdescr"	=>	"DH 1024-bit key exchanges",
				"tooltip"	=>	"Number of Diffie-Helman 1024-bit key exchanges on the NetScaler appliance.",
			),
			"ssldh1024keyexchangesrate" => array (
				"cntrname"	=>	"ssldh1024keyexchangesrate",
				"cntrdescr"	=>	"",
			),
			"ssltotdh2048keyexchanges" => Array(
				"cntrname"	=>	"ssltotdh2048keyexchanges",
				"cntrdescr"	=>	"DH 2048-bit key exchanges",
				"tooltip"	=>	"Number of Diffie-Helman 2048-bit key exchanges on the NetScaler appliance.",
			),
			"ssldh2048keyexchangesrate" => array (
				"cntrname"	=>	"ssldh2048keyexchangesrate",
				"cntrdescr"	=>	"",
			),
			"ssltotecdhe521keyexchanges" => Array(
				"cntrname"	=>	"ssltotecdhe521keyexchanges",
				"cntrdescr"	=>	"ECDHE 521 curve key exchanges",
				"tooltip"	=>	"Number of 521 Elliptical Curve Diffie-Helman on the NetScaler appliance.",
			),
			"sslecdhe521keyexchangesrate" => array (
				"cntrname"	=>	"sslecdhe521keyexchangesrate",
				"cntrdescr"	=>	"",
			),
			"ssltotecdhe384keyexchanges" => Array(
				"cntrname"	=>	"ssltotecdhe384keyexchanges",
				"cntrdescr"	=>	"ECDHE 384 curve key exchanges",
				"tooltip"	=>	"Number of 384 Elliptical Curve Diffie-Helman on the NetScaler appliance.",
			),
			"sslecdhe384keyexchangesrate" => array (
				"cntrname"	=>	"sslecdhe384keyexchangesrate",
				"cntrdescr"	=>	"",
			),
			"ssltotecdhe256keyexchanges" => Array(
				"cntrname"	=>	"ssltotecdhe256keyexchanges",
				"cntrdescr"	=>	"ECDHE 256 curve key exchanges",
				"tooltip"	=>	"Number of 256 Elliptical Curve Diffie-Helman on the NetScaler appliance.",
			),
			"sslecdhe256keyexchangesrate" => array (
				"cntrname"	=>	"sslecdhe256keyexchangesrate",
				"cntrdescr"	=>	"",
			),
			"ssltotecdhe224keyexchanges" => Array(
				"cntrname"	=>	"ssltotecdhe224keyexchanges",
				"cntrdescr"	=>	"ECDHE 224 curve key exchanges",
				"tooltip"	=>	"Number of 224 Elliptical Curve Diffie-Helman on the NetScaler appliance.",
			),
			"sslecdhe224keyexchangesrate" => array (
				"cntrname"	=>	"sslecdhe224keyexchangesrate",
				"cntrdescr"	=>	"",
			),
			"ssltot40bitrc4ciphers" => Array(
				"cntrname"	=>	"ssltot40bitrc4ciphers",
				"cntrdescr"	=>	"RC4 40-bit encryptions",
				"tooltip"	=>	"Number of RC4 40-bit cipher encryptions on the NetScaler appliance.",
			),
			"ssl40bitrc4ciphersrate" => array (
				"cntrname"	=>	"ssl40bitrc4ciphersrate",
				"cntrdescr"	=>	"",
			),
			"ssltot56bitrc4ciphers" => Array(
				"cntrname"	=>	"ssltot56bitrc4ciphers",
				"cntrdescr"	=>	"RC4 56-bit encryptions",
				"tooltip"	=>	"Number of RC4 56-bit cipher encryptions on the NetScaler appliance.",
			),
			"ssl56bitrc4ciphersrate" => array (
				"cntrname"	=>	"ssl56bitrc4ciphersrate",
				"cntrdescr"	=>	"",
			),
			"ssltot64bitrc4ciphers" => Array(
				"cntrname"	=>	"ssltot64bitrc4ciphers",
				"cntrdescr"	=>	"RC4 64-bit encryptions",
				"tooltip"	=>	"Number of RC4 64-bit cipher encryptions on the NetScaler appliance.",
			),
			"ssl64bitrc4ciphersrate" => array (
				"cntrname"	=>	"ssl64bitrc4ciphersrate",
				"cntrdescr"	=>	"",
			),
			"ssltot128bitrc4ciphers" => Array(
				"cntrname"	=>	"ssltot128bitrc4ciphers",
				"cntrdescr"	=>	"RC4 128-bit encryptions",
				"tooltip"	=>	"Number of RC4 128-bit cipher encryptions on the NetScaler appliance.",
			),
			"ssl128bitrc4ciphersrate" => array (
				"cntrname"	=>	"ssl128bitrc4ciphersrate",
				"cntrdescr"	=>	"",
			),
			"ssltot40bitdesciphers" => Array(
				"cntrname"	=>	"ssltot40bitdesciphers",
				"cntrdescr"	=>	"DES 40-bit encryptions",
				"tooltip"	=>	"Number of DES 40-bit cipher encryptions on the NetScaler appliance.",
			),
			"ssl40bitdesciphersrate" => array (
				"cntrname"	=>	"ssl40bitdesciphersrate",
				"cntrdescr"	=>	"",
			),
			"ssltot56bitdesciphers" => Array(
				"cntrname"	=>	"ssltot56bitdesciphers",
				"cntrdescr"	=>	"DES 56-bit encryptions",
				"tooltip"	=>	"Number of DES 56-bit cipher encryptions on the NetScaler appliance.",
			),
			"ssl56bitdesciphersrate" => array (
				"cntrname"	=>	"ssl56bitdesciphersrate",
				"cntrdescr"	=>	"",
			),
			"ssltot168bit3desciphers" => Array(
				"cntrname"	=>	"ssltot168bit3desciphers",
				"cntrdescr"	=>	"3DES 168-bit encryptions",
				"tooltip"	=>	"Number of DES 168-bit cipher encryptions on the NetScaler appliance.",
			),
			"ssl168bit3desciphersrate" => array (
				"cntrname"	=>	"ssl168bit3desciphersrate",
				"cntrdescr"	=>	"",
			),
			"ssltotcipheraes128" => Array(
				"cntrname"	=>	"ssltotcipheraes128",
				"cntrdescr"	=>	"AES 128-bit encryptions",
				"tooltip"	=>	"Number of AES 128-bit cipher encryptions on the NetScaler appliance.",
			),
			"sslcipheraes128rate" => array (
				"cntrname"	=>	"sslcipheraes128rate",
				"cntrdescr"	=>	"",
			),
			"ssltotcipheraes256" => Array(
				"cntrname"	=>	"ssltotcipheraes256",
				"cntrdescr"	=>	"AES 256-bit encryptions",
				"tooltip"	=>	"Number of AES 256-bit cipher encryptions on the NetScaler appliance.",
			),
			"sslcipheraes256rate" => array (
				"cntrname"	=>	"sslcipheraes256rate",
				"cntrdescr"	=>	"",
			),
			"ssltot40bitrc2ciphers" => Array(
				"cntrname"	=>	"ssltot40bitrc2ciphers",
				"cntrdescr"	=>	"RC2 40-bit encryptions",
				"tooltip"	=>	"Number of RC2 40-bit cipher encryptions on the NetScaler appliance.",
			),
			"ssl40bitrc2ciphersrate" => array (
				"cntrname"	=>	"ssl40bitrc2ciphersrate",
				"cntrdescr"	=>	"",
			),
			"ssltot56bitrc2ciphers" => Array(
				"cntrname"	=>	"ssltot56bitrc2ciphers",
				"cntrdescr"	=>	"RC2 56-bit encryptions",
				"tooltip"	=>	"Number of RC2 56-bit cipher encryptions on the NetScaler appliance.",
			),
			"ssl56bitrc2ciphersrate" => array (
				"cntrname"	=>	"ssl56bitrc2ciphersrate",
				"cntrdescr"	=>	"",
			),
			"ssltot128bitrc2ciphers" => Array(
				"cntrname"	=>	"ssltot128bitrc2ciphers",
				"cntrdescr"	=>	"RC2 128-bit encryptions",
				"tooltip"	=>	"Number of RC2 128-bit cipher encryptions on the NetScaler appliance.",
			),
			"ssl128bitrc2ciphersrate" => array (
				"cntrname"	=>	"ssl128bitrc2ciphersrate",
				"cntrdescr"	=>	"",
			),
			"ssltot128bitaesgcmciphers" => Array(
				"cntrname"	=>	"ssltot128bitaesgcmciphers",
				"cntrdescr"	=>	"AES-GCM 128-bit encryptions",
				"tooltip"	=>	"Number of AEC-GCM 128-bit cipher encryptions on the NetScaler appliance.",
			),
			"ssl128bitaesgcmciphersrate" => array (
				"cntrname"	=>	"ssl128bitaesgcmciphersrate",
				"cntrdescr"	=>	"",
			),
			"ssltot256bitaesgcmciphers" => Array(
				"cntrname"	=>	"ssltot256bitaesgcmciphers",
				"cntrdescr"	=>	"AES-GCM 256-bit encryptions",
				"tooltip"	=>	"Number of AEC-GCM 256-bit cipher encryptions on the NetScaler appliance.",
			),
			"ssl256bitaesgcmciphersrate" => array (
				"cntrname"	=>	"ssl256bitaesgcmciphersrate",
				"cntrdescr"	=>	"",
			),
			"ssltotnullciphers" => Array(
				"cntrname"	=>	"ssltotnullciphers",
				"cntrdescr"	=>	"Null cipher encryptions",
				"tooltip"	=>	"Number of Null cipher encryptions on the NetScaler appliance.",
			),
			"sslnullciphersrate" => array (
				"cntrname"	=>	"sslnullciphersrate",
				"cntrdescr"	=>	"",
			),
			"ssltotmd5mac" => Array(
				"cntrname"	=>	"ssltotmd5mac",
				"cntrdescr"	=>	"MD5 hashes",
				"tooltip"	=>	"Number of MD5 hashes on the NetScaler appliance.",
			),
			"sslmd5macrate" => array (
				"cntrname"	=>	"sslmd5macrate",
				"cntrdescr"	=>	"",
			),
			"ssltotshamac" => Array(
				"cntrname"	=>	"ssltotshamac",
				"cntrdescr"	=>	"SHA hashes",
				"tooltip"	=>	"Number of SHA hashes on the NetScaler appliance.",
			),
			"sslshamacrate" => array (
				"cntrname"	=>	"sslshamacrate",
				"cntrdescr"	=>	"",
			),
			"ssltotsslv2handshakes" => Array(
				"cntrname"	=>	"ssltotsslv2handshakes",
				"cntrdescr"	=>	"SSLv2 SSL handshakes",
				"tooltip"	=>	"Number of handshakes on SSLv2 on the NetScaler appliance.",
			),
			"sslsslv2handshakesrate" => array (
				"cntrname"	=>	"sslsslv2handshakesrate",
				"cntrdescr"	=>	"",
			),
			"ssltotsslv3handshakes" => Array(
				"cntrname"	=>	"ssltotsslv3handshakes",
				"cntrdescr"	=>	"SSLv3 SSL handshakes",
				"tooltip"	=>	"Number of handshakes on SSLv3 on the NetScaler appliance.",
			),
			"sslsslv3handshakesrate" => array (
				"cntrname"	=>	"sslsslv3handshakesrate",
				"cntrdescr"	=>	"",
			),
			"ssltottlsv1handshakes" => Array(
				"cntrname"	=>	"ssltottlsv1handshakes",
				"cntrdescr"	=>	"TLSv1 SSL handshakes",
				"tooltip"	=>	"Number of SSL handshakes on TLSv1 on the NetScaler appliance.",
			),
			"ssltlsv1handshakesrate" => array (
				"cntrname"	=>	"ssltlsv1handshakesrate",
				"cntrdescr"	=>	"",
			),
			"ssltottlsv11handshakes" => Array(
				"cntrname"	=>	"ssltottlsv11handshakes",
				"cntrdescr"	=>	"TLSv1.1 SSL handshakes",
				"tooltip"	=>	"Number of SSL handshakes on TLSv1.1 on the NetScaler appliance.",
			),
			"ssltlsv11handshakesrate" => array (
				"cntrname"	=>	"ssltlsv11handshakesrate",
				"cntrdescr"	=>	"",
			),
			"ssltottlsv12handshakes" => Array(
				"cntrname"	=>	"ssltottlsv12handshakes",
				"cntrdescr"	=>	"TLSv1.2 SSL handshakes",
				"tooltip"	=>	"Number of SSL handshakes on TLSv1.2 on the NetScaler appliance.",
			),
			"ssltlsv12handshakesrate" => array (
				"cntrname"	=>	"ssltlsv12handshakesrate",
				"cntrdescr"	=>	"",
			),
			"ssltotsslv2clientauthentications" => Array(
				"cntrname"	=>	"ssltotsslv2clientauthentications",
				"cntrdescr"	=>	"SSLv2 client authentications",
				"tooltip"	=>	"Number of client authentications done on SSLv2.",
			),
			"sslsslv2clientauthenticationsrate" => array (
				"cntrname"	=>	"sslsslv2clientauthenticationsrate",
				"cntrdescr"	=>	"",
			),
			"ssltotsslv3clientauthentications" => Array(
				"cntrname"	=>	"ssltotsslv3clientauthentications",
				"cntrdescr"	=>	"SSLv3 client authentications",
				"tooltip"	=>	"Number of client authentications done on SSLv3.",
			),
			"sslsslv3clientauthenticationsrate" => array (
				"cntrname"	=>	"sslsslv3clientauthenticationsrate",
				"cntrdescr"	=>	"",
			),
			"ssltottlsv1clientauthentications" => Array(
				"cntrname"	=>	"ssltottlsv1clientauthentications",
				"cntrdescr"	=>	"TLSv1 client authentications",
				"tooltip"	=>	"Number of client authentications done on TLSv1.",
			),
			"ssltlsv1clientauthenticationsrate" => array (
				"cntrname"	=>	"ssltlsv1clientauthenticationsrate",
				"cntrdescr"	=>	"",
			),
			"ssltottlsv11clientauthentications" => Array(
				"cntrname"	=>	"ssltottlsv11clientauthentications",
				"cntrdescr"	=>	"TLSv1.1 client authentications",
				"tooltip"	=>	"Number of client authentications done on TLSv1.1.",
			),
			"ssltlsv11clientauthenticationsrate" => array (
				"cntrname"	=>	"ssltlsv11clientauthenticationsrate",
				"cntrdescr"	=>	"",
			),
			"ssltottlsv12clientauthentications" => Array(
				"cntrname"	=>	"ssltottlsv12clientauthentications",
				"cntrdescr"	=>	"TLSv1.2 client authentications",
				"tooltip"	=>	"Number of client authentications done on TLSv1.2.",
			),
			"ssltlsv12clientauthenticationsrate" => array (
				"cntrname"	=>	"ssltlsv12clientauthenticationsrate",
				"cntrdescr"	=>	"",
			),
			"ssltotrsaauthorizations" => Array(
				"cntrname"	=>	"ssltotrsaauthorizations",
				"cntrdescr"	=>	"RSA authentications",
				"tooltip"	=>	"Number of RSA authentications on the NetScaler appliance.",
			),
			"sslrsaauthorizationsrate" => array (
				"cntrname"	=>	"sslrsaauthorizationsrate",
				"cntrdescr"	=>	"",
			),
			"ssltotdhauthorizations" => Array(
				"cntrname"	=>	"ssltotdhauthorizations",
				"cntrdescr"	=>	"DH authentications",
				"tooltip"	=>	"Number of Diffie-Helman authentications on the NetScaler appliance.",
			),
			"ssldhauthorizationsrate" => array (
				"cntrname"	=>	"ssldhauthorizationsrate",
				"cntrdescr"	=>	"",
			),
			"ssltotdssauthorizations" => Array(
				"cntrname"	=>	"ssltotdssauthorizations",
				"cntrdescr"	=>	"DSS (DSA) authentications",
				"tooltip"	=>	"Total number of times DSS authorization is used on the NetScaler appliance.",
			),
			"ssldssauthorizationsrate" => array (
				"cntrname"	=>	"ssldssauthorizationsrate",
				"cntrdescr"	=>	"",
			),
			"ssltotecdsaauthorizations" => Array(
				"cntrname"	=>	"ssltotecdsaauthorizations",
				"cntrdescr"	=>	"ECDSA authentications",
				"tooltip"	=>	"Total number of times ECDSA authorization is used on the NetScaler appliance.",
			),
			"sslecdsaauthorizationsrate" => array (
				"cntrname"	=>	"sslecdsaauthorizationsrate",
				"cntrdescr"	=>	"",
			),
			"ssltotnullauthorizations" => Array(
				"cntrname"	=>	"ssltotnullauthorizations",
				"cntrdescr"	=>	"Null authentications",
				"tooltip"	=>	"Number of Null authentications on the NetScaler appliance.",
			),
			"sslnullauthorizationsrate" => array (
				"cntrname"	=>	"sslnullauthorizationsrate",
				"cntrdescr"	=>	"",
			),
			"ssltotbkendsessionrenegotiate" => Array(
				"cntrname"	=>	"ssltotbkendsessionrenegotiate",
				"cntrdescr"	=>	"SSL session renegotiations",
				"cntrdescr2"	=>	"Backend SSL session renegotiations",
				"tooltip"	=>	"Number of back-end SSL session renegotiations on the NetScaler appliance.",
			),
			"sslbkendsessionrenegotiaterate" => array (
				"cntrname"	=>	"sslbkendsessionrenegotiaterate",
				"cntrdescr"	=>	"",
			),
			"ssltotbkendsslv3renego" => Array(
				"cntrname"	=>	"ssltotbkendsslv3renego",
				"cntrdescr"	=>	"SSLv3 session renegotiations",
				"cntrdescr2"	=>	"Backend SSLv3 session renegotiations",
				"tooltip"	=>	"Number of back-end SSLv3 session renegotiations on the NetScaler appliance.",
			),
			"sslbkendsslv3renegorate" => array (
				"cntrname"	=>	"sslbkendsslv3renegorate",
				"cntrdescr"	=>	"",
			),
			"ssltotbkendtlsvlrenego" => Array(
				"cntrname"	=>	"ssltotbkendtlsvlrenego",
				"cntrdescr"	=>	"TLSv1 session renegotiations",
				"cntrdescr2"	=>	"Backend TLSv1 session renegotiations",
				"tooltip"	=>	"Number of back-end TLSv1 session renegotiations on the NetScaler appliance.",
			),
			"sslbkendtlsvlrenegorate" => array (
				"cntrname"	=>	"sslbkendtlsvlrenegorate",
				"cntrdescr"	=>	"",
			),
			"ssltotbkendtlsv11renego" => Array(
				"cntrname"	=>	"ssltotbkendtlsv11renego",
				"cntrdescr"	=>	"TLSv1.1 back-end session renegotiations",
				"cntrdescr2"	=>	"Backend TLSv1.1 session renegotiations",
				"tooltip"	=>	"Number of back-end TLSv1.1 session renegotiations on the NetScaler appliance.",
			),
			"sslbkendtlsv11renegorate" => array (
				"cntrname"	=>	"sslbkendtlsv11renegorate",
				"cntrdescr"	=>	"",
			),
			"ssltotbkendtlsv12renego" => Array(
				"cntrname"	=>	"ssltotbkendtlsv12renego",
				"cntrdescr"	=>	"TLSv1.2 back-end session renegotiations",
				"cntrdescr2"	=>	"Backend TLSv1.2 session renegotiations",
				"tooltip"	=>	"Number of back-end TLSv1.2 session renegotiations on the NetScaler appliance.",
			),
			"sslbkendtlsv12renegorate" => array (
				"cntrname"	=>	"sslbkendtlsv12renegorate",
				"cntrdescr"	=>	"",
			),
			"sslbetotrsa512keyexchanges" => Array(
				"cntrname"	=>	"sslbetotrsa512keyexchanges",
				"cntrdescr"	=>	"RSA 512-bit key exchanges",
				"cntrdescr2"	=>	"Backend RSA 512-bit key exchanges",
				"tooltip"	=>	"Number of back-end RSA 512-bit key exchanges on the NetScaler appliance.",
			),
			"sslbersa512keyexchangesrate" => array (
				"cntrname"	=>	"sslbersa512keyexchangesrate",
				"cntrdescr"	=>	"",
			),
			"sslbetotrsa1024keyexchanges" => Array(
				"cntrname"	=>	"sslbetotrsa1024keyexchanges",
				"cntrdescr"	=>	"RSA 1024-bit key exchanges",
				"cntrdescr2"	=>	"Backend RSA 1024-bit key exchanges",
				"tooltip"	=>	"Number of back-end RSA 1024-bit key exchanges on the NetScaler appliance.",
			),
			"sslbersa1024keyexchangesrate" => array (
				"cntrname"	=>	"sslbersa1024keyexchangesrate",
				"cntrdescr"	=>	"",
			),
			"sslbetotrsa2048keyexchanges" => Array(
				"cntrname"	=>	"sslbetotrsa2048keyexchanges",
				"cntrdescr"	=>	"RSA 2048-bit key exchanges",
				"cntrdescr2"	=>	"Backend RSA 2048-bit key exchanges",
				"tooltip"	=>	"Number of back-end RSA 2048-bit key exchanges on the NetScaler appliance.",
			),
			"sslbersa2048keyexchangesrate" => array (
				"cntrname"	=>	"sslbersa2048keyexchangesrate",
				"cntrdescr"	=>	"",
			),
			"sslbetotdh512keyexchanges" => Array(
				"cntrname"	=>	"sslbetotdh512keyexchanges",
				"cntrdescr"	=>	"DH 512-bit key exchanges",
				"cntrdescr2"	=>	"Backend DH 512-bit key exchanges",
				"tooltip"	=>	"Number of back-end DH 512-bit key exchanges on the NetScaler appliance.",
			),
			"sslbedh512keyexchangesrate" => array (
				"cntrname"	=>	"sslbedh512keyexchangesrate",
				"cntrdescr"	=>	"",
			),
			"sslbetotdh1024keyexchanges" => Array(
				"cntrname"	=>	"sslbetotdh1024keyexchanges",
				"cntrdescr"	=>	"DH 1024-bit key exchanges",
				"cntrdescr2"	=>	"Backend DH 1024-bit key exchanges",
				"tooltip"	=>	"Number of back-end DH 1024-bit key exchanges on the NetScaler appliance.",
			),
			"sslbedh1024keyexchangesrate" => array (
				"cntrname"	=>	"sslbedh1024keyexchangesrate",
				"cntrdescr"	=>	"",
			),
			"sslbetotdh2048keyexchanges" => Array(
				"cntrname"	=>	"sslbetotdh2048keyexchanges",
				"cntrdescr"	=>	"DH 2048-bit key exchanges",
				"cntrdescr2"	=>	"Backend DH 2048-bit key exchanges",
				"tooltip"	=>	"Number of back-end DH 2048-bit key exchanges on the NetScaler appliance.",
			),
			"sslbedh2048keyexchangesrate" => array (
				"cntrname"	=>	"sslbedh2048keyexchangesrate",
				"cntrdescr"	=>	"",
			),
			"sslbetotecdhecurve521" => Array(
				"cntrname"	=>	"sslbetotecdhecurve521",
				"cntrdescr"	=>	"ECDHE 521 curve key exchanges",
				"cntrdescr2"	=>	"Backend ECDHE 521 curve key exchanges",
				"tooltip"	=>	"Number of back-end ECDHE 521 curve Key exchanges  on the NetScaler appliance.",
			),
			"sslbeecdhecurve521rate" => array (
				"cntrname"	=>	"sslbeecdhecurve521rate",
				"cntrdescr"	=>	"",
			),
			"sslbetotecdhecurve384" => Array(
				"cntrname"	=>	"sslbetotecdhecurve384",
				"cntrdescr"	=>	"ECDHE 384 curve key exchanges",
				"cntrdescr2"	=>	"Backend ECDHE 384 curve key exchanges",
				"tooltip"	=>	"Number of back-end ECDHE 384 curve Key exchanges  on the NetScaler appliance.",
			),
			"sslbeecdhecurve384rate" => array (
				"cntrname"	=>	"sslbeecdhecurve384rate",
				"cntrdescr"	=>	"",
			),
			"sslbetotecdhecurve256" => Array(
				"cntrname"	=>	"sslbetotecdhecurve256",
				"cntrdescr"	=>	"ECDHE 256 curve key exchanges",
				"cntrdescr2"	=>	"Backend ECDHE 256 curve key exchanges",
				"tooltip"	=>	"Number of back-end ECDHE 256 curve Key exchanges  on the NetScaler appliance.",
			),
			"sslbeecdhecurve256rate" => array (
				"cntrname"	=>	"sslbeecdhecurve256rate",
				"cntrdescr"	=>	"",
			),
			"sslbetotecdhecurve224" => Array(
				"cntrname"	=>	"sslbetotecdhecurve224",
				"cntrdescr"	=>	"ECDHE 224 curve key exchanges",
				"cntrdescr2"	=>	"Backend ECDHE 224 curve key exchanges",
				"tooltip"	=>	"Number of back-end ECDHE 224 curve Key exchanges  on the NetScaler appliance.",
			),
			"sslbeecdhecurve224rate" => array (
				"cntrname"	=>	"sslbeecdhecurve224rate",
				"cntrdescr"	=>	"",
			),
			"sslbetot40bitrc4ciphers" => Array(
				"cntrname"	=>	"sslbetot40bitrc4ciphers",
				"cntrdescr"	=>	"RC4 40-bit encryptions",
				"cntrdescr2"	=>	"Backend RC4 40-bit encryptions",
				"tooltip"	=>	"Number of back-end RC4 40-bit cipher encryptions on the NetScaler appliance.",
			),
			"sslbe40bitrc4ciphersrate" => array (
				"cntrname"	=>	"sslbe40bitrc4ciphersrate",
				"cntrdescr"	=>	"",
			),
			"sslbetot56bitrc4ciphers" => Array(
				"cntrname"	=>	"sslbetot56bitrc4ciphers",
				"cntrdescr"	=>	"RC4 56-bit encryptions",
				"cntrdescr2"	=>	"Backend RC4 56-bit encryptions",
				"tooltip"	=>	"Number of back-end RC4 56-bit cipher encryptions on the NetScaler appliance.",
			),
			"sslbe56bitrc4ciphersrate" => array (
				"cntrname"	=>	"sslbe56bitrc4ciphersrate",
				"cntrdescr"	=>	"",
			),
			"sslbetot64bitrc4ciphers" => Array(
				"cntrname"	=>	"sslbetot64bitrc4ciphers",
				"cntrdescr"	=>	"RC4 64-bit encryptions",
				"cntrdescr2"	=>	"Backend RC4 64-bit encryptions",
				"tooltip"	=>	"Number of back-end RC4 64-bit cipher encryptions on the NetScaler appliance.",
			),
			"sslbe64bitrc4ciphersrate" => array (
				"cntrname"	=>	"sslbe64bitrc4ciphersrate",
				"cntrdescr"	=>	"",
			),
			"sslbetot128bitrc4ciphers" => Array(
				"cntrname"	=>	"sslbetot128bitrc4ciphers",
				"cntrdescr"	=>	"RC4 128-bit encryptions",
				"cntrdescr2"	=>	"Backend RC4 128-bit encryptions",
				"tooltip"	=>	"Number of back-end RC4 128-bit cipher encryptions on the NetScaler appliance.",
			),
			"sslbe128bitrc4ciphersrate" => array (
				"cntrname"	=>	"sslbe128bitrc4ciphersrate",
				"cntrdescr"	=>	"",
			),
			"sslbetot40bitdesciphers" => Array(
				"cntrname"	=>	"sslbetot40bitdesciphers",
				"cntrdescr"	=>	"DES 40-bit encryptions",
				"cntrdescr2"	=>	"Backend DES 40-bit encryptions",
				"tooltip"	=>	"Number of back-end DES 40-bit cipher encryptions on the NetScaler appliance.",
			),
			"sslbe40bitdesciphersrate" => array (
				"cntrname"	=>	"sslbe40bitdesciphersrate",
				"cntrdescr"	=>	"",
			),
			"sslbetot56bitdesciphers" => Array(
				"cntrname"	=>	"sslbetot56bitdesciphers",
				"cntrdescr"	=>	"DES 56-bit encryptions",
				"cntrdescr2"	=>	"Backend DES 56-bit encryptions",
				"tooltip"	=>	"Number of back-end DES 56-bit cipher encryptions on the NetScaler appliance.",
			),
			"sslbe56bitdesciphersrate" => array (
				"cntrname"	=>	"sslbe56bitdesciphersrate",
				"cntrdescr"	=>	"",
			),
			"sslbetot168bit3desciphers" => Array(
				"cntrname"	=>	"sslbetot168bit3desciphers",
				"cntrdescr"	=>	"3DES 168-bit encryptions",
				"cntrdescr2"	=>	"Backend 3DES 168-bit encryptions",
				"tooltip"	=>	"Number of back-end 3DES 168-bit cipher encryptions on the NetScaler appliance.",
			),
			"sslbe168bit3desciphersrate" => array (
				"cntrname"	=>	"sslbe168bit3desciphersrate",
				"cntrdescr"	=>	"",
			),
			"ssltotbkendcipheraes128" => Array(
				"cntrname"	=>	"ssltotbkendcipheraes128",
				"cntrdescr"	=>	"AES 128-bit encryptions",
				"cntrdescr2"	=>	"Backend AES 128-bit encryptions",
				"tooltip"	=>	"Back-end AES 128-bit cipher encryptions on the NetScaler appliance.",
			),
			"sslbkendcipheraes128rate" => array (
				"cntrname"	=>	"sslbkendcipheraes128rate",
				"cntrdescr"	=>	"",
			),
			"ssltotbkendcipheraes256" => Array(
				"cntrname"	=>	"ssltotbkendcipheraes256",
				"cntrdescr"	=>	"AES 256-bit encryptions",
				"cntrdescr2"	=>	"Backend AES 256-bit encryptions",
				"tooltip"	=>	"Back-end AES 256-bit cipher encryptions on the NetScaler appliance.",
			),
			"sslbkendcipheraes256rate" => array (
				"cntrname"	=>	"sslbkendcipheraes256rate",
				"cntrdescr"	=>	"",
			),
			"sslbetot40bitrc2ciphers" => Array(
				"cntrname"	=>	"sslbetot40bitrc2ciphers",
				"cntrdescr"	=>	"RC2 40-bit encryptions",
				"cntrdescr2"	=>	"Backend RC2 40-bit encryptions",
				"tooltip"	=>	"Number of back-end RC2 40-bit cipher encryptions on the NetScaler appliance.",
			),
			"sslbe40bitrc2ciphersrate" => array (
				"cntrname"	=>	"sslbe40bitrc2ciphersrate",
				"cntrdescr"	=>	"",
			),
			"sslbetot56bitrc2ciphers" => Array(
				"cntrname"	=>	"sslbetot56bitrc2ciphers",
				"cntrdescr"	=>	"RC2 56-bit encryptions",
				"cntrdescr2"	=>	"Backend RC2 56-bit encryptions",
				"tooltip"	=>	"Number of back-end RC2 56-bit cipher encryptions on the NetScaler appliance.",
			),
			"sslbe56bitrc2ciphersrate" => array (
				"cntrname"	=>	"sslbe56bitrc2ciphersrate",
				"cntrdescr"	=>	"",
			),
			"sslbetot128bitrc2ciphers" => Array(
				"cntrname"	=>	"sslbetot128bitrc2ciphers",
				"cntrdescr"	=>	"RC2 128-bit encryptions",
				"cntrdescr2"	=>	"Backend RC2 128-bit encryptions",
				"tooltip"	=>	"Number of back-end RC2 128-bit cipher encryptions on the NetScaler appliance.",
			),
			"sslbe128bitrc2ciphersrate" => array (
				"cntrname"	=>	"sslbe128bitrc2ciphersrate",
				"cntrdescr"	=>	"",
			),
			"sslbetotnullciphers" => Array(
				"cntrname"	=>	"sslbetotnullciphers",
				"cntrdescr"	=>	"null encryptions",
				"tooltip"	=>	"Number of back-end null cipher encryptions on the NetScaler appliance.",
			),
			"sslbenullciphersrate" => array (
				"cntrname"	=>	"sslbenullciphersrate",
				"cntrdescr"	=>	"",
			),
			"sslbetotmd5mac" => Array(
				"cntrname"	=>	"sslbetotmd5mac",
				"cntrdescr"	=>	"MD5 hashes",
				"cntrdescr2"	=>	"Backend MD5 hashes",
				"tooltip"	=>	"Number of back-end MD5 hashes on the NetScaler appliance.",
			),
			"sslbemd5macrate" => array (
				"cntrname"	=>	"sslbemd5macrate",
				"cntrdescr"	=>	"",
			),
			"sslbetotshamac" => Array(
				"cntrname"	=>	"sslbetotshamac",
				"cntrdescr"	=>	"SHA hashes",
				"cntrdescr2"	=>	"Backend SHA hashes",
				"tooltip"	=>	"Number of back-end SHA hashes on the NetScaler appliance.",
			),
			"sslbeshamacrate" => array (
				"cntrname"	=>	"sslbeshamacrate",
				"cntrdescr"	=>	"",
			),
			"sslbetotsslv3handshakes" => Array(
				"cntrname"	=>	"sslbetotsslv3handshakes",
				"cntrdescr"	=>	"SSLv3 handshakes",
				"tooltip"	=>	"Number of back-end SSLv3 handshakes on the NetScaler appliance.",
			),
			"sslbesslv3handshakesrate" => array (
				"cntrname"	=>	"sslbesslv3handshakesrate",
				"cntrdescr"	=>	"",
			),
			"sslbetottlsv1handshakes" => Array(
				"cntrname"	=>	"sslbetottlsv1handshakes",
				"cntrdescr"	=>	"TLSv1 handshakes",
				"tooltip"	=>	"Number of back-end TLSv1 handshakes on the NetScaler appliance.",
			),
			"sslbetlsv1handshakesrate" => array (
				"cntrname"	=>	"sslbetlsv1handshakesrate",
				"cntrdescr"	=>	"",
			),
			"sslbetottlsv11handshakes" => Array(
				"cntrname"	=>	"sslbetottlsv11handshakes",
				"cntrdescr"	=>	"TLSv1.1 handshakes",
				"tooltip"	=>	"Number of back-end TLSv1.1 handshakes on the NetScaler appliance.",
			),
			"sslbetlsv11handshakesrate" => array (
				"cntrname"	=>	"sslbetlsv11handshakesrate",
				"cntrdescr"	=>	"",
			),
			"sslbetottlsv12handshakes" => Array(
				"cntrname"	=>	"sslbetottlsv12handshakes",
				"cntrdescr"	=>	"TLSv1.2 handshakes",
				"tooltip"	=>	"Number of back-end TLSv1.2 handshakes on the NetScaler appliance.",
			),
			"sslbetlsv12handshakesrate" => array (
				"cntrname"	=>	"sslbetlsv12handshakesrate",
				"cntrdescr"	=>	"",
			),
			"sslbetotsslv3clientauthentications" => Array(
				"cntrname"	=>	"sslbetotsslv3clientauthentications",
				"cntrdescr"	=>	"SSLv3 client authentications",
				"cntrdescr2"	=>	"Backend SSLv3 client authentications",
				"tooltip"	=>	"Number of back-end SSLv3 client authentications on the NetScaler appliance.",
			),
			"sslbesslv3clientauthenticationsrate" => array (
				"cntrname"	=>	"sslbesslv3clientauthenticationsrate",
				"cntrdescr"	=>	"",
			),
			"sslbetottlsv1clientauthentications" => Array(
				"cntrname"	=>	"sslbetottlsv1clientauthentications",
				"cntrdescr"	=>	"TLSv1 client authentications",
				"cntrdescr2"	=>	"Backend TLSv1 client authentications",
				"tooltip"	=>	"Number of back-end TLSv1 client authentications on the NetScaler appliance.",
			),
			"sslbetlsv1clientauthenticationsrate" => array (
				"cntrname"	=>	"sslbetlsv1clientauthenticationsrate",
				"cntrdescr"	=>	"",
			),
			"sslbetottlsv11clientauthentications" => Array(
				"cntrname"	=>	"sslbetottlsv11clientauthentications",
				"cntrdescr"	=>	"TLSv1.1 client authentications",
				"cntrdescr2"	=>	"Backend TLSv1.1 client authentications",
				"tooltip"	=>	"Number of back-end TLSv1.1 client authentications on the NetScaler appliance.",
			),
			"sslbetlsv11clientauthenticationsrate" => array (
				"cntrname"	=>	"sslbetlsv11clientauthenticationsrate",
				"cntrdescr"	=>	"",
			),
			"sslbetottlsv12clientauthentications" => Array(
				"cntrname"	=>	"sslbetottlsv12clientauthentications",
				"cntrdescr"	=>	"TLSv1.2 client authentications",
				"cntrdescr2"	=>	"Backend TLSv1.2 client authentications",
				"tooltip"	=>	"Number of back-end TLSv1.2 client authentications on the NetScaler appliance.",
			),
			"sslbetlsv12clientauthenticationsrate" => array (
				"cntrname"	=>	"sslbetlsv12clientauthenticationsrate",
				"cntrdescr"	=>	"",
			),
			"sslbetotrsaauthorizations" => Array(
				"cntrname"	=>	"sslbetotrsaauthorizations",
				"cntrdescr"	=>	"RSA authentications",
				"cntrdescr2"	=>	"Backend RSA authentications",
				"tooltip"	=>	"Number of back-end RSA authentications on the NetScaler appliance.",
			),
			"sslbersaauthorizationsrate" => array (
				"cntrname"	=>	"sslbersaauthorizationsrate",
				"cntrdescr"	=>	"",
			),
			"sslbetotdhauthorizations" => Array(
				"cntrname"	=>	"sslbetotdhauthorizations",
				"cntrdescr"	=>	"DH authentications",
				"cntrdescr2"	=>	"Backend DH authentications",
				"tooltip"	=>	"Number of back-end DH authentications on the NetScaler appliance.",
			),
			"sslbedhauthorizationsrate" => array (
				"cntrname"	=>	"sslbedhauthorizationsrate",
				"cntrdescr"	=>	"",
			),
			"sslbetotdssauthorizations" => Array(
				"cntrname"	=>	"sslbetotdssauthorizations",
				"cntrdescr"	=>	"DSS authentications",
				"tooltip"	=>	"Number of back-end DSS authentications on the NetScaler appliance.",
			),
			"sslbedssauthorizationsrate" => array (
				"cntrname"	=>	"sslbedssauthorizationsrate",
				"cntrdescr"	=>	"",
			),
			"sslbetotnullauthorizations" => Array(
				"cntrname"	=>	"sslbetotnullauthorizations",
				"cntrdescr"	=>	"Null authentications",
				"cntrdescr2"	=>	"Backend Null authentications",
				"tooltip"	=>	"Number of back-end null authentications on the NetScaler appliance.",
			),
			"sslbenullauthorizationsrate" => array (
				"cntrname"	=>	"sslbenullauthorizationsrate",
				"cntrdescr"	=>	"",
			),
			"ssltotoffloadrsakeyexchanges" => Array(
				"cntrname"	=>	"ssltotoffloadrsakeyexchanges",
				"cntrdescr"	=>	"RSA key exchanges offloaded",
				"tooltip"	=>	"Number of RSA key exchanges offloaded to the cryptography card.",
			),
			"ssloffloadrsakeyexchangesrate" => array (
				"cntrname"	=>	"ssloffloadrsakeyexchangesrate",
				"cntrdescr"	=>	"",
			),
			"ssltotoffloadsignrsa" => Array(
				"cntrname"	=>	"ssltotoffloadsignrsa",
				"cntrdescr"	=>	"RSA sign operations offloaded",
				"tooltip"	=>	"Number of RSA sign operations offloaded to the cryptography card.",
			),
			"ssloffloadsignrsarate" => array (
				"cntrname"	=>	"ssloffloadsignrsarate",
				"cntrdescr"	=>	"",
			),
			"ssltotoffloaddhkeyexchanges" => Array(
				"cntrname"	=>	"ssltotoffloaddhkeyexchanges",
				"cntrdescr"	=>	"DH key exchanges offloaded",
				"tooltip"	=>	"Number of DH key exchanges offloaded to the cryptography card.d",
			),
			"ssloffloaddhkeyexchangesrate" => array (
				"cntrname"	=>	"ssloffloaddhkeyexchangesrate",
				"cntrdescr"	=>	"",
			),
			"ssltotoffloadbulkrc4" => Array(
				"cntrname"	=>	"ssltotoffloadbulkrc4",
				"cntrdescr"	=>	"RC4 encryptions offloaded",
				"tooltip"	=>	"Number of RC4 encryptions offloaded to the cryptography card.",
			),
			"ssloffloadbulkrc4rate" => array (
				"cntrname"	=>	"ssloffloadbulkrc4rate",
				"cntrdescr"	=>	"",
			),
			"ssltotoffloadbulkdes" => Array(
				"cntrname"	=>	"ssltotoffloadbulkdes",
				"cntrdescr"	=>	"DES encryptions offloaded",
				"tooltip"	=>	"Number of DES encryptions offloaded to the cryptography card.",
			),
			"ssloffloadbulkdesrate" => array (
				"cntrname"	=>	"ssloffloadbulkdesrate",
				"cntrdescr"	=>	"",
			),
			"ssltotoffloadbulkaes" => Array(
				"cntrname"	=>	"ssltotoffloadbulkaes",
				"cntrdescr"	=>	"AES encryptions offloaded",
				"tooltip"	=>	"Number of AES encryptions offloaded to the cryptography card.",
			),
			"ssloffloadbulkaesrate" => array (
				"cntrname"	=>	"ssloffloadbulkaesrate",
				"cntrdescr"	=>	"",
			),
			"ssltotoffloadbulkaesgcm128" => Array(
				"cntrname"	=>	"ssltotoffloadbulkaesgcm128",
				"cntrdescr"	=>	"AES-GCM 128-bit encryptions offloaded",
				"tooltip"	=>	"Number of AES-GCM 128-bit encryptions offloaded to the cryptography card.",
			),
			"ssloffloadbulkaesgcm128rate" => array (
				"cntrname"	=>	"ssloffloadbulkaesgcm128rate",
				"cntrdescr"	=>	"",
			),
			"ssltotoffloadbulkaesgcm256" => Array(
				"cntrname"	=>	"ssltotoffloadbulkaesgcm256",
				"cntrdescr"	=>	"AES-GCM 256-bit encryptions offloaded",
				"tooltip"	=>	"Number of AES-GCM 256-bit encryptions offloaded to the cryptography card.",
			),
			"ssloffloadbulkaesgcm256rate" => array (
				"cntrname"	=>	"ssloffloadbulkaesgcm256rate",
				"cntrdescr"	=>	"",
			),
			"ssltotenchw" => Array(
				"cntrname"	=>	"ssltotenchw",
				"cntrdescr"	=>	"Bytes encrypted in hardware",
				"tooltip"	=>	"Number of bytes encrypted in hardware.",
			),
			"sslenchwrate" => array (
				"cntrname"	=>	"sslenchwrate",
				"cntrdescr"	=>	"",
			),
			"ssltotencsw" => Array(
				"cntrname"	=>	"ssltotencsw",
				"cntrdescr"	=>	"Bytes encrypted in software.",
				"tooltip"	=>	"Number of bytes encrypted in software.",
			),
			"sslencswrate" => array (
				"cntrname"	=>	"sslencswrate",
				"cntrdescr"	=>	"",
			),
			"ssltotencfe" => Array(
				"cntrname"	=>	"ssltotencfe",
				"cntrdescr"	=>	"Bytes encrypted on the front end.",
				"tooltip"	=>	"Number of bytes encrypted on the front end.",
			),
			"sslencferate" => array (
				"cntrname"	=>	"sslencferate",
				"cntrdescr"	=>	"",
			),
			"ssltothwencfe" => Array(
				"cntrname"	=>	"ssltothwencfe",
				"cntrdescr"	=>	"Bytes encrypted in hardware on the front end.",
				"tooltip"	=>	"Number of bytes encrypted in hardware on the front end.",
			),
			"sslhwencferate" => array (
				"cntrname"	=>	"sslhwencferate",
				"cntrdescr"	=>	"",
			),
			"ssltotswencfe" => Array(
				"cntrname"	=>	"ssltotswencfe",
				"cntrdescr"	=>	"Bytes encrypted in software on front-end",
				"tooltip"	=>	"Number of bytes encrypted in software on the front end.",
			),
			"sslswencferate" => array (
				"cntrname"	=>	"sslswencferate",
				"cntrdescr"	=>	"",
			),
			"ssltotencbe" => Array(
				"cntrname"	=>	"ssltotencbe",
				"cntrdescr"	=>	"Bytes encrypted on back-end",
				"tooltip"	=>	"Number of bytes encrypted on the back end.",
			),
			"sslencberate" => array (
				"cntrname"	=>	"sslencberate",
				"cntrdescr"	=>	"",
			),
			"ssltothwencbe" => Array(
				"cntrname"	=>	"ssltothwencbe",
				"cntrdescr"	=>	"Bytes encrypted in hardware on back-end",
				"tooltip"	=>	"Number of bytes encrypted in hardware on the back end.",
			),
			"sslhwencberate" => array (
				"cntrname"	=>	"sslhwencberate",
				"cntrdescr"	=>	"",
			),
			"ssltotswencbe" => Array(
				"cntrname"	=>	"ssltotswencbe",
				"cntrdescr"	=>	"Bytes encrypted in software on back-end",
				"tooltip"	=>	"Number of bytes encrypted in software on the back end.",
			),
			"sslswencberate" => array (
				"cntrname"	=>	"sslswencberate",
				"cntrdescr"	=>	"",
			),
			"ssltotdechw" => Array(
				"cntrname"	=>	"ssltotdechw",
				"cntrdescr"	=>	"Bytes decrypted in hardware",
				"tooltip"	=>	"Number of bytes decrypted in hardware.",
			),
			"ssldechwrate" => array (
				"cntrname"	=>	"ssldechwrate",
				"cntrdescr"	=>	"",
			),
			"ssltotdecsw" => Array(
				"cntrname"	=>	"ssltotdecsw",
				"cntrdescr"	=>	"Bytes decrypted in software",
				"tooltip"	=>	"Number of bytes decrypted in software.",
			),
			"ssldecswrate" => array (
				"cntrname"	=>	"ssldecswrate",
				"cntrdescr"	=>	"",
			),
			"ssltotdecfe" => Array(
				"cntrname"	=>	"ssltotdecfe",
				"cntrdescr"	=>	"Bytes decrypted on front-end",
				"tooltip"	=>	"Number of bytes decrypted on the front end.",
			),
			"ssldecferate" => array (
				"cntrname"	=>	"ssldecferate",
				"cntrdescr"	=>	"",
			),
			"ssltothwdecfe" => Array(
				"cntrname"	=>	"ssltothwdecfe",
				"cntrdescr"	=>	"Bytes decrypted in hardware on front-end",
				"tooltip"	=>	"Number of bytes decrypted in hardware on the front end.",
			),
			"sslhwdecferate" => array (
				"cntrname"	=>	"sslhwdecferate",
				"cntrdescr"	=>	"",
			),
			"ssltotswdecfe" => Array(
				"cntrname"	=>	"ssltotswdecfe",
				"cntrdescr"	=>	"Bytes decrypted in software on front-end",
				"tooltip"	=>	"Number of bytes decrypted in software on the front end.",
			),
			"sslswdecferate" => array (
				"cntrname"	=>	"sslswdecferate",
				"cntrdescr"	=>	"",
			),
			"ssltotdecbe" => Array(
				"cntrname"	=>	"ssltotdecbe",
				"cntrdescr"	=>	"Bytes decrypted on back-end",
				"tooltip"	=>	"Number of bytes decrypted on the back end.",
			),
			"ssldecberate" => array (
				"cntrname"	=>	"ssldecberate",
				"cntrdescr"	=>	"",
			),
			"ssltothwdecbe" => Array(
				"cntrname"	=>	"ssltothwdecbe",
				"cntrdescr"	=>	"Bytes decrypted in hardware on back-end",
				"tooltip"	=>	"Number of bytes decrypted in hardware on the back end.",
			),
			"sslhwdecberate" => array (
				"cntrname"	=>	"sslhwdecberate",
				"cntrdescr"	=>	"",
			),
			"ssltotswdecbe" => Array(
				"cntrname"	=>	"ssltotswdecbe",
				"cntrdescr"	=>	"Bytes decrypted in software on the back end.",
				"tooltip"	=>	"Number of bytes decrypted in software on back-end",
			),
			"sslswdecberate" => array (
				"cntrname"	=>	"sslswdecberate",
				"cntrdescr"	=>	"",
			),
			"sslcursslinfospcbinusecount" => Array(
				"cntrname"	=>	"sslcursslinfospcbinusecount",
				"cntrdescr"	=>	"Total SPCB in use",
				"tooltip"	=>	"Number of SPCB in use.",
			),
			"sslcursslinfospcbinusecountrate" => array (
				"cntrname"	=>	"sslcursslinfospcbinusecountrate",
				"cntrdescr"	=>	"",
			),
			"sslcursessions" => Array(
				"cntrname"	=>	"sslcursessions",
				"cntrdescr"	=>	"Active SSL sessions",
				"tooltip"	=>	"Number of active SSL sessions on the NetScaler appliance.",
			),
			"sslcursessionsrate" => array (
				"cntrname"	=>	"sslcursessionsrate",
				"cntrdescr"	=>	"",
			),
			"sslcurqsize" => Array(
				"cntrname"	=>	"sslcurqsize",
				"cntrdescr"	=>	"Current queue size",
				"tooltip"	=>	"Current queue size",
			),
			"sslcurqsizerate" => array (
				"cntrname"	=>	"sslcurqsizerate",
				"cntrdescr"	=>	"",
			),
			"sslcursslinfonscardinqcount" => Array(
				"cntrname"	=>	"sslcursslinfonscardinqcount",
				"cntrdescr"	=>	"In Q count for current card",
				"tooltip"	=>	"Number of current SSL card InQ count.",
			),
			"sslcursslinfonscardinqcountrate" => array (
				"cntrname"	=>	"sslcursslinfonscardinqcountrate",
				"cntrdescr"	=>	"",
			),
			"sslcursslinfocardinblkq" => Array(
				"cntrname"	=>	"sslcursslinfocardinblkq",
				"cntrdescr"	=>	"In BulkQ count for current card",
				"tooltip"	=>	"Number of current SSL card In BulkQ count.",
			),
			"sslcursslinfocardinblkqrate" => array (
				"cntrname"	=>	"sslcursslinfocardinblkqrate",
				"cntrdescr"	=>	"",
			),
			"sslcursslinfocardinkeyq" => Array(
				"cntrname"	=>	"sslcursslinfocardinkeyq",
				"cntrdescr"	=>	"In KeyQ count for current card",
				"tooltip"	=>	"Number of current SSL card In KeyQ count.",
			),
			"sslcursslinfocardinkeyqrate" => array (
				"cntrname"	=>	"sslcursslinfocardinkeyqrate",
				"cntrdescr"	=>	"",
			),
			"sslbemaxmultiplexedsessions" => Array(
				"cntrname"	=>	"sslbemaxmultiplexedsessions",
				"cntrdescr"	=>	"Backend SSL sessions reused",
				"tooltip"	=>	"Number of back-end SSL sessions reused on the NetScaler appliance.",
			),
			"sslbemaxmultiplexedsessionsrate" => array (
				"cntrname"	=>	"sslbemaxmultiplexedsessionsrate",
				"cntrdescr"	=>	"",
			),
			"ssltot128bitideaciphers" => Array(
				"cntrname"	=>	"ssltot128bitideaciphers",
				"cntrdescr"	=>	"IDEA 128-bit encryptions",
				"tooltip"	=>	"Number of IDEA 128-bit cipher encryptions on the NetScaler appliance.",
			),
			"ssl128bitideaciphersrate" => array (
				"cntrname"	=>	"ssl128bitideaciphersrate",
				"cntrdescr"	=>	"",
			),
			"sslbetot128bitideaciphers" => Array(
				"cntrname"	=>	"sslbetot128bitideaciphers",
				"cntrdescr"	=>	"IDEA 128-bit encryptions",
				"cntrdescr2"	=>	"Backend IDEA 128-bit encryptions",
				"tooltip"	=>	"Number of back-end IDEA 128-bit cipher encryptions on the NetScaler appliance.",
			),
			"sslbe128bitideaciphersrate" => array (
				"cntrname"	=>	"sslbe128bitideaciphersrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"",
				"header"	=>	array(),
				"format"	=>	"1C",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("sslcards","sslnumcardsup","sslenginestatus","sslsessionsrate","sslcryptoutilizationstat",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"System",
				"header"	=>	array("Transactions","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("ssltottransactions","ssltotsslv2transactions","ssltotsslv3transactions","ssltottlsv1transactions","ssltottlsv11transactions","ssltottlsv12transactions",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Front End",
				"header"	=>	array("Sessions","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("ssltotsessions","ssltotsslv2sessions","ssltotsslv3sessions","ssltottlsv1sessions","ssltottlsv11sessions","ssltottlsv12sessions","ssltotnewsessions","ssltotsessionmiss","ssltotsessionhits",),
			),
			Array(
				"dhID"	=>	4,
				"title"	=>	"Back End",
				"header"	=>	array("Sessions","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("sslbetotsessions","sslbetotsslv3sessions","sslbetottlsv1sessions","sslbetottlsv11sessions","sslbetottlsv12sessions","sslbetotsessionmultiplexattempts","sslbetotsessionmultiplexattemptsuccess","sslbetotsessionmultiplexattemptfails",),
			),
			Array(
				"dhID"	=>	5,
				"title"	=>	"Encryption/Decryption statistics",
				"header"	=>	array("Crypto Operation","Rate (bytes/s)","Total Bytes",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("ssltotenc","ssltotdec",),
			),
			Array(
				"dhID"	=>	6,
				"title"	=>	"",
				"header"	=>	array(),
				"format"	=>	"1C",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("sslcards","sslnumcardsup","sslenginestatus","sslsessionsrate","sslcryptoutilizationstat",),
			),
			Array(
				"dhID"	=>	7,
				"title"	=>	"System",
				"header"	=>	array("Transactions","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("ssltottransactions","ssltotsslv2transactions","ssltotsslv3transactions","ssltottlsv1transactions","ssltottlsv11transactions","ssltottlsv12transactions","ssltotsslserverinrecords",),
			),
			Array(
				"dhID"	=>	8,
				"title"	=>	"Front End",
				"header"	=>	array("Sessions","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("ssltotsessions","ssltotsslv2sessions","ssltotsslv3sessions","ssltottlsv1sessions","ssltottlsv11sessions","ssltottlsv12sessions","ssltotnewsessions","ssltotsessionmiss","ssltotsessionhits",),
			),
			Array(
				"dhID"	=>	9,
				"title"	=>	"Session Renegotiation",
				"header"	=>	array(),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("ssltotrenegsessions","ssltotsslv3renegsessions","ssltottlsv1renegsessions","ssltottlsv11renegsessions","ssltottlsv12renegsessions",),
			),
			Array(
				"dhID"	=>	10,
				"title"	=>	"Key Exchanges",
				"header"	=>	array(),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("ssltotrsa512keyexchanges","ssltotrsa1024keyexchanges","ssltotrsa2048keyexchanges","ssltotrsa4096keyexchanges","ssltotdh512keyexchanges","ssltotdh1024keyexchanges","ssltotdh2048keyexchanges","ssltotecdhe521keyexchanges","ssltotecdhe384keyexchanges","ssltotecdhe256keyexchanges","ssltotecdhe224keyexchanges",),
			),
			Array(
				"dhID"	=>	11,
				"title"	=>	"Ciphers Negotiated",
				"header"	=>	array(),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("ssltot40bitrc4ciphers","ssltot56bitrc4ciphers","ssltot64bitrc4ciphers","ssltot128bitrc4ciphers","ssltot40bitdesciphers","ssltot56bitdesciphers","ssltot168bit3desciphers","ssltotcipheraes128","ssltotcipheraes256","ssltot40bitrc2ciphers","ssltot56bitrc2ciphers","ssltot128bitrc2ciphers","ssltot128bitaesgcmciphers","ssltot256bitaesgcmciphers","ssltotnullciphers",),
			),
			Array(
				"dhID"	=>	12,
				"title"	=>	"Hashes",
				"header"	=>	array(),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("ssltotmd5mac","ssltotshamac",),
			),
			Array(
				"dhID"	=>	13,
				"title"	=>	"Handshakes",
				"header"	=>	array(),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("ssltotsslv2handshakes","ssltotsslv3handshakes","ssltottlsv1handshakes","ssltottlsv11handshakes","ssltottlsv12handshakes",),
			),
			Array(
				"dhID"	=>	14,
				"title"	=>	"Client Authentications",
				"header"	=>	array(),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("ssltotsslv2clientauthentications","ssltotsslv3clientauthentications","ssltottlsv1clientauthentications","ssltottlsv11clientauthentications","ssltottlsv12clientauthentications",),
			),
			Array(
				"dhID"	=>	15,
				"title"	=>	"Authentications",
				"header"	=>	array(),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("ssltotrsaauthorizations","ssltotdhauthorizations","ssltotdssauthorizations","ssltotecdsaauthorizations","ssltotnullauthorizations",),
			),
			Array(
				"dhID"	=>	16,
				"title"	=>	"Back End",
				"header"	=>	array("Sessions","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("sslbetotsessions","sslbetotsslv3sessions","sslbetottlsv1sessions","sslbetottlsv11sessions","sslbetottlsv12sessions","sslbetotsessionmultiplexattempts","sslbetotsessionmultiplexattemptsuccess","sslbetotsessionmultiplexattemptfails",),
			),
			Array(
				"dhID"	=>	17,
				"title"	=>	"Session Renegotiation",
				"header"	=>	array(),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("ssltotbkendsessionrenegotiate","ssltotbkendsslv3renego","ssltotbkendtlsvlrenego","ssltotbkendtlsv11renego","ssltotbkendtlsv12renego",),
			),
			Array(
				"dhID"	=>	18,
				"title"	=>	"Key Exchanges",
				"header"	=>	array(),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("sslbetotrsa512keyexchanges","sslbetotrsa1024keyexchanges","sslbetotrsa2048keyexchanges","sslbetotdh512keyexchanges","sslbetotdh1024keyexchanges","sslbetotdh2048keyexchanges","sslbetotecdhecurve521","sslbetotecdhecurve384","sslbetotecdhecurve256","sslbetotecdhecurve224",),
			),
			Array(
				"dhID"	=>	19,
				"title"	=>	"Ciphers Negotiated",
				"header"	=>	array(),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("sslbetot40bitrc4ciphers","sslbetot56bitrc4ciphers","sslbetot64bitrc4ciphers","sslbetot128bitrc4ciphers","sslbetot40bitdesciphers","sslbetot56bitdesciphers","sslbetot168bit3desciphers","ssltotbkendcipheraes128","ssltotbkendcipheraes256","sslbetot40bitrc2ciphers","sslbetot56bitrc2ciphers","sslbetot128bitrc2ciphers","sslbetotnullciphers",),
			),
			Array(
				"dhID"	=>	20,
				"title"	=>	"Hashes",
				"header"	=>	array(),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("sslbetotmd5mac","sslbetotshamac",),
			),
			Array(
				"dhID"	=>	21,
				"title"	=>	"Handshakes",
				"header"	=>	array(),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("NOCNTR","sslbetotsslv3handshakes","sslbetottlsv1handshakes","sslbetottlsv11handshakes","sslbetottlsv12handshakes",),
			),
			Array(
				"dhID"	=>	22,
				"title"	=>	"Client Authentications",
				"header"	=>	array(),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("NOCNTR","sslbetotsslv3clientauthentications","sslbetottlsv1clientauthentications","sslbetottlsv11clientauthentications","sslbetottlsv12clientauthentications",),
			),
			Array(
				"dhID"	=>	23,
				"title"	=>	"Authentications",
				"header"	=>	array(),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("sslbetotrsaauthorizations","sslbetotdhauthorizations","sslbetotdssauthorizations","sslbetotnullauthorizations",),
			),
			Array(
				"text" => "System",
				"flag" => "Detail",
				"key" => "detail",
			),
			Array(
				"dhID"	=>	24,
				"title"	=>	"",
				"header"	=>	array(),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("ssltotoffloadrsakeyexchanges","ssltotoffloadsignrsa","ssltotoffloaddhkeyexchanges","ssltotoffloadbulkrc4","ssltotoffloadbulkdes","ssltotoffloadbulkaes","ssltotoffloadbulkaesgcm128","ssltotoffloadbulkaesgcm256",),
			),
			Array(
				"dhID"	=>	25,
				"title"	=>	"Encryption/Decryption statistics",
				"header"	=>	array("Crypto Operation","Rate (bytes/s)","Total Bytes",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("ssltotenc","ssltotenchw","ssltotencsw","ssltotencfe","ssltothwencfe","ssltotswencfe","ssltotencbe","ssltothwencbe","ssltotswencbe","ssltotdec","ssltotdechw","ssltotdecsw","ssltotdecfe","ssltothwdecfe","ssltotswdecfe","ssltotdecbe","ssltothwdecbe","ssltotswdecbe",),
			),
			Array(
				"dhID"	=>	26,
				"title"	=>	"SSL",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("sslcursslinfospcbinusecount","sslcursessions","sslcurqsize",),
			),
			Array(
				"dhID"	=>	27,
				"title"	=>	"CardQ",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("sslcursslinfonscardinqcount","sslcursslinfocardinblkq","sslcursslinfocardinkeyq",),
			),
		),
),


"statsslvserver" => Array(
	"cmddispname"		=>	"statsslvserver",
	"cmdname"		=>	"statsslvserver",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"vservername" => Array(
				"argname"		=>	"vservername",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "vservername",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"vslbhealth" => Array(
				"cntrname"	=>	"vslbhealth",
				"cntrdescr"	=>	"Vserver Health",
				"tooltip"	=>	"Health of the vserver. This gives percentage of UP services bound to this vserver.",
			),
			"primaryipaddress" => Array(
				"cntrname"	=>	"primaryipaddress",
				"cntrdescr"	=>	"Vserver IP address",
				"tooltip"	=>	"IP address of the vserver",
				"type"		=>	"NS_VALIPSTR",
			),
			"primaryport" => Array(
				"cntrname"	=>	"primaryport",
				"cntrdescr"	=>	"Port",
				"tooltip"	=>	"The port on which the service is running.",
				"type"		=>	"NS_VALPORT",
			),
			"type" => Array(
				"cntrname"	=>	"type",
				"cntrdescr"	=>	"Vserver protocol",
				"tooltip"	=>	"Protocol associated with the vserver",
				"type"		=>	"NS_VALENUM",
			),
			"state" => Array(
				"cntrname"	=>	"state",
				"cntrdescr"	=>	"State",
				"tooltip"	=>	"Current state of the server. Possible values are UP, DOWN, UNKNOWN, OFS(Out of Service), TROFS(Transition Out of Service), TROFS_DOWN(Down When going Out of Service)",
				"type"		=>	"NS_VALENUM",
			),
			"actsvcs" => Array(
				"cntrname"	=>	"actsvcs",
				"cntrdescr"	=>	"total ACTIVE services",
				"tooltip"	=>	"number of ACTIVE services bound to a vserver",
			),
			"ssltotclientauthsuccess" => Array(
				"cntrname"	=>	"ssltotclientauthsuccess",
				"cntrdescr"	=>	"Client Authentication Success",
				"tooltip"	=>	"Number of successful client authentication on this vserver",
			),
			"sslclientauthsuccessrate" => array (
				"cntrname"	=>	"sslclientauthsuccessrate",
				"cntrdescr"	=>	"",
			),
			"ssltotclientauthfailure" => Array(
				"cntrname"	=>	"ssltotclientauthfailure",
				"cntrdescr"	=>	"Client Authentication Failure",
				"tooltip"	=>	"Number of failure client authentication on this vserver",
			),
			"sslclientauthfailurerate" => array (
				"cntrname"	=>	"sslclientauthfailurerate",
				"cntrdescr"	=>	"",
			),
			"sslctxtotencbytes" => Array(
				"cntrname"	=>	"sslctxtotencbytes",
				"cntrdescr"	=>	"Total encrypted bytes",
				"tooltip"	=>	"Number of encrypted bytes per SSL vserver",
			),
			"sslctxencbytesrate" => array (
				"cntrname"	=>	"sslctxencbytesrate",
				"cntrdescr"	=>	"",
			),
			"sslctxtotdecbytes" => Array(
				"cntrname"	=>	"sslctxtotdecbytes",
				"cntrdescr"	=>	"Total decrypted bytes",
				"tooltip"	=>	"Number of decrypted bytes per SSL vserver",
			),
			"sslctxdecbytesrate" => array (
				"cntrname"	=>	"sslctxdecbytesrate",
				"cntrdescr"	=>	"",
			),
			"sslctxtothwencbytes" => Array(
				"cntrname"	=>	"sslctxtothwencbytes",
				"cntrdescr"	=>	"Total hardware encrypted bytes",
				"tooltip"	=>	"Number of hardware encrypted bytes per SSL vserver",
			),
			"sslctxhwencbytesrate" => array (
				"cntrname"	=>	"sslctxhwencbytesrate",
				"cntrdescr"	=>	"",
			),
			"sslctxtothwdec_bytes" => Array(
				"cntrname"	=>	"sslctxtothwdec_bytes",
				"cntrdescr"	=>	"Total hardware decrypted bytes",
				"tooltip"	=>	"Number of hw decrypted bytes per SSL vserver",
			),
			"sslctxhwdec_bytesrate" => array (
				"cntrname"	=>	"sslctxhwdec_bytesrate",
				"cntrdescr"	=>	"",
			),
			"sslctxtotsessionnew" => Array(
				"cntrname"	=>	"sslctxtotsessionnew",
				"cntrdescr"	=>	"Total new sessions created",
				"tooltip"	=>	"Number of new sessions created",
			),
			"sslctxsessionnewrate" => array (
				"cntrname"	=>	"sslctxsessionnewrate",
				"cntrdescr"	=>	"",
			),
			"sslctxtotsessionhits" => Array(
				"cntrname"	=>	"sslctxtotsessionhits",
				"cntrdescr"	=>	"Total session hits",
				"tooltip"	=>	"Number of session hits",
			),
			"sslctxsessionhitsrate" => array (
				"cntrname"	=>	"sslctxsessionhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Virtual Server Summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("primaryipaddress","primaryport","type","state","vslbhealth","actsvcs",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"VServer Stats:",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("ssltotclientauthsuccess","ssltotclientauthfailure","sslctxtotencbytes","sslctxtotdecbytes","sslctxtothwencbytes","sslctxtothwdec_bytes","sslctxtotsessionnew","sslctxtotsessionhits",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Vserver(s) Client Authentication Summary",
				"header"	=>	array("Name","Success","Failure",),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("ssltotclientauthsuccess","ssltotclientauthfailure",),
			),
			Array(
				"dhID"	=>	4,
				"title"	=>	"VServer Stats",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("sslctxtotencbytes","sslctxtotdecbytes","sslctxtothwencbytes","sslctxtothwdec_bytes","sslctxtotsessionnew","sslctxtotsessionhits",),
			),
		),
),


"statstreamidentifier" => Array(
	"cmddispname"		=>	"statstreamidentifier",
	"cmdname"		=>	"statstreamidentifier",
	"cmddescr"	=>	"     ",
	"bindings"	=>	1,
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"pattern" => Array(
				"argname"		=>	"pattern",
				"valtype"		=>	"NS_VALSTR",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
			"sortby" => Array(
				"argname"		=>	"sortby",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
			"sortorder" => Array(
				"argname"		=>	"sortorder",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"streamobjreq" => Array(
				"cntrname"	=>	"streamobjreq",
				"cntrdescr"	=>	"Stream Session Requests",
				"tooltip"	=>	"Total number of Stream Requests recieved.",
			),
			"streamobjbandw" => Array(
				"cntrname"	=>	"streamobjbandw",
				"cntrdescr"	=>	"Stream Session Bandwidth",
				"tooltip"	=>	"Total Bandwidth consumed.",
			),
			"streamobjresptime" => Array(
				"cntrname"	=>	"streamobjresptime",
				"cntrdescr"	=>	"Stream Session Response Time",
				"tooltip"	=>	"Average response time of the stream session.",
			),
			"streamobjconn" => Array(
				"cntrname"	=>	"streamobjconn",
				"cntrdescr"	=>	"Stream Session Connections",
				"tooltip"	=>	"Current connections on the stream session.",
			),
			"streamobjbreachcnt" => Array(
				"cntrname"	=>	"streamobjbreachcnt",
				"cntrdescr"	=>	"Stream Session Breaching Transactions",
				"tooltip"	=>	"Total Breaching Transactions in configured interval.",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Statistics of sessions created by stream identifier",
				"header"	=>	array("SessionName","Req","BW(kbps)","RspTime(ms)","CurConn","BreachCnt",),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"subent",
				"counters"	=>	array("streamobjreq","streamobjbandw","streamobjresptime","streamobjconn","streamobjbreachcnt",),
			),
		),
),


"statstreamsession" => Array(
	"cmddispname"		=>	"statstreamsession",
	"cmdname"		=>	"statstreamsession",
	"cmddescr"	=>	"     ",
	"hidden"	=>	1,
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALSTR",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALSTR",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"streamobjreq" => Array(
				"cntrname"	=>	"streamobjreq",
				"cntrdescr"	=>	"Stream Session Requests",
				"tooltip"	=>	"Total number of Stream Requests recieved.",
			),
			"streamobjbandw" => Array(
				"cntrname"	=>	"streamobjbandw",
				"cntrdescr"	=>	"Stream Session Bandwidth",
				"tooltip"	=>	"Total Bandwidth consumed.",
			),
			"streamobjresptime" => Array(
				"cntrname"	=>	"streamobjresptime",
				"cntrdescr"	=>	"Stream Session Response Time",
				"tooltip"	=>	"Average response time of the stream session.",
			),
			"streamobjconn" => Array(
				"cntrname"	=>	"streamobjconn",
				"cntrdescr"	=>	"Stream Session Connections",
				"tooltip"	=>	"Current connections on the stream session.",
			),
			"streamobjbreachcnt" => Array(
				"cntrname"	=>	"streamobjbreachcnt",
				"cntrdescr"	=>	"Stream Session Breaching Transactions",
				"tooltip"	=>	"Total Breaching Transactions in configured interval.",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Stream Session statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("streamobjreq","streamobjbandw","streamobjresptime","streamobjconn","streamobjbreachcnt",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("streamobjreq","streamobjbandw","streamobjresptime","streamobjconn","streamobjbreachcnt",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Stream Session statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"subent",
				"counters"	=>	array("streamobjreq","streamobjbandw","streamobjresptime","streamobjconn","streamobjbreachcnt",),
			),
		),
),


"statsystem" => Array(
	"cmddispname"		=>	"statsystem",
	"cmdname"		=>	"statsystem",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"voltagev12n" => Array(
				"cntrname"	=>	"voltagev12n",
				"cntrdescr"	=>	"-12.0 V Supply Voltage",
				"maxval"	=>     -0.0001,
				"tooltip"	=>	"Power supply -12V output. Acceptable range is -13.20 through -10.80 volts. 9800 and 9960 platforms display standard value of -12.0V.",
			),
			"voltagev5n" => Array(
				"cntrname"	=>	"voltagev5n",
				"cntrdescr"	=>	"-5.0 V Supply Voltage",
				"maxval"	=>     -0.0001,
				"tooltip"	=>	"Power supply -5V output. Acceptable range is -5.50 through -4.50 volts. 9800 and 9960 platforms display standard value of -5.0V.",
			),
			"cpuusage" => Array(
				"cntrname"	=>	"cpuusage",
				"cntrdescr"	=>	"CPU usage",
				"cntrdescr2"	=>	"CPU usage (%) rounded off",
				"maxval"	=>     4294967294,
				"tooltip"	=>	"CPU utilization percentage.",
			),
			"rescpuusage" => Array(
				"cntrname"	=>	"rescpuusage",
				"cntrdescr"	=>	"Average CPU usage",
				"maxval"	=>     4294967294,
				"tooltip"	=>	"Shows average CPU utilization percentage if more than 1 CPU is present.",
			),
			"slavecpuusage" => Array(
				"cntrname"	=>	"slavecpuusage",
				"cntrdescr"	=>	"CPU1",
				"maxval"	=>     4294967294,
				"tooltip"	=>	"CPU 1 (currently the slave CPU) utilization, as percentage of capacity. Not applicable for a single-CPU system.",
			),
			"mastercpuusage" => Array(
				"cntrname"	=>	"mastercpuusage",
				"cntrdescr"	=>	"CPU0",
				"maxval"	=>     4294967294,
				"tooltip"	=>	"CPU 0 (currently the master CPU) utilization, as percentage of capacity.",
			),
			"auxvolt7" => Array(
				"cntrname"	=>	"auxvolt7",
				"cntrdescr"	=>	"Voltage 7 (Volts)",
				"tooltip"	=>	"Voltage of a device connected to health monitoring chip through pin 7.",
			),
			"auxvolt6" => Array(
				"cntrname"	=>	"auxvolt6",
				"cntrdescr"	=>	"Voltage 6 (Volts)",
				"tooltip"	=>	"Voltage of a device connected to health monitoring chip through pin 6.",
			),
			"auxvolt5" => Array(
				"cntrname"	=>	"auxvolt5",
				"cntrdescr"	=>	"Voltage 5 (Volts)",
				"tooltip"	=>	"Voltage of a device connected to health monitoring chip through pin 5.",
			),
			"auxvolt4" => Array(
				"cntrname"	=>	"auxvolt4",
				"cntrdescr"	=>	"Voltage 4 (Volts)",
				"tooltip"	=>	"Voltage of a device connected to health monitoring chip through pin 4.",
			),
			"auxvolt3" => Array(
				"cntrname"	=>	"auxvolt3",
				"cntrdescr"	=>	"Voltage 3 (Volts)",
				"tooltip"	=>	"Voltage of a device connected to health monitoring chip through pin 3.",
			),
			"auxvolt2" => Array(
				"cntrname"	=>	"auxvolt2",
				"cntrdescr"	=>	"Voltage 2 (Volts)",
				"tooltip"	=>	"Voltage of a device connected to health monitoring chip through pin 2.",
			),
			"auxvolt1" => Array(
				"cntrname"	=>	"auxvolt1",
				"cntrdescr"	=>	"Voltage 1 (Volts)",
				"tooltip"	=>	"Voltage of a device connected to health monitoring chip through pin 1.",
			),
			"auxvolt0" => Array(
				"cntrname"	=>	"auxvolt0",
				"cntrdescr"	=>	"Voltage 0 (Volts)",
				"tooltip"	=>	"Voltage of a device connected to health monitoring chip through pin 0.",
			),
			"voltagevsen2" => Array(
				"cntrname"	=>	"voltagevsen2",
				"cntrdescr"	=>	"Voltage Sensor2(Volts) ",
				"tooltip"	=>	"Voltage Sensor 2 Input. Currently only 13k Platforms will have valid value for this counter and for older platforms this will be 0.",
			),
			"voltagev5sb" => Array(
				"cntrname"	=>	"voltagev5sb",
				"cntrdescr"	=>	"5V Standby Voltage(Volts) ",
				"tooltip"	=>	"Power Supply 5V Standby Voltage. Currently only 13k Platforms will have valid value for this counter and for older platforms this will be 0.",
			),
			"voltagevtt" => Array(
				"cntrname"	=>	"voltagevtt",
				"cntrdescr"	=>	"Intel CPU Vtt Power(Volts) ",
				"tooltip"	=>	"Intel CPU Vtt power. Currently only 13k Platforms will have valid value for this counter and for older platforms this will be 0.",
			),
			"voltagevbat" => Array(
				"cntrname"	=>	"voltagevbat",
				"cntrdescr"	=>	"Battery Voltage (Volts) ",
				"tooltip"	=>	"Onboard battery power supply output. 9800 and 9950 platforms display standard value of 5.0V.",
			),
			"voltagev12p" => Array(
				"cntrname"	=>	"voltagev12p",
				"cntrdescr"	=>	"+12.0 V Supply Voltage",
				"tooltip"	=>	"Power supply +12V output. Acceptable range is 10.80 through 13.20 volts.",
			),
			"voltagev5p" => Array(
				"cntrname"	=>	"voltagev5p",
				"cntrdescr"	=>	"+5.0 V Supply Voltage",
				"tooltip"	=>	"Power supply +5V output. Acceptable range is 4.50 through 5.50 volts.",
			),
			"voltagev33stby" => Array(
				"cntrname"	=>	"voltagev33stby",
				"cntrdescr"	=>	"Standby 3.3 V Supply Voltage",
				"tooltip"	=>	"Standby power supply +3.3V output. Acceptable range is 2.970 through 3.630 volts. 9800 and 9960 platforms display standard value of 3.3V.<br><br>You can configure Standby 3.3V Supply Voltage by using the Set snmp alarm VOLTAGE-LOW command to set the lower limit and the Set snmp alarm VOLTAGE-HIGH command to set the upper limit.",
			),
			"voltagev33main" => Array(
				"cntrname"	=>	"voltagev33main",
				"cntrdescr"	=>	"Main 3.3 V Supply Voltage",
				"tooltip"	=>	"Main power supply +3.3V output. Acceptable range is 2.970 through 3.630 volts. This is a critical counter.<br><br>You can configure Main 3.3V Supply Voltage, by using the Set snmp alarm VOLTAGE-LOW command to set the lower limit and the Set snmp alarm VOLTAGE-HIGH command to set the upper limit.",
			),
			"voltagevcc1" => Array(
				"cntrname"	=>	"voltagevcc1",
				"cntrdescr"	=>	"CPU 1 Core Voltage (Volts)",
				"tooltip"	=>	"CPU core 1 voltage. Acceptable range is 1.080 through 1.650 volts. If CPU 1 is not connected to the health monitoring chip, display shows voltage of CPU 0.",
			),
			"voltagevcc0" => Array(
				"cntrname"	=>	"voltagevcc0",
				"cntrdescr"	=>	"CPU 0 Core Voltage (Volts)",
				"tooltip"	=>	"CPU core 0 voltage. Acceptable range is 1.080 through 1.650 volts.",
			),
			"numcpus" => Array(
				"cntrname"	=>	"numcpus",
				"cntrdescr"	=>	"Number of CPUs",
				"maxval"	=>     4294967294,
				"tooltip"	=>	"The number of CPUs on the NetScaler appliance.",
			),
			"memusagepcnt" => Array(
				"cntrname"	=>	"memusagepcnt",
				"cntrdescr"	=>	"InUse Memory (%)",
				"tooltip"	=>	"Percentage of memory utilization on NetScaler.",
			),
			"memuseinmb" => Array(
				"cntrname"	=>	"memuseinmb",
				"cntrdescr"	=>	"Memory usage (MB)",
				"tooltip"	=>	"Main memory currently in use, in megabytes.",
			),
			"mgmtcpuusagepcnt" => Array(
				"cntrname"	=>	"mgmtcpuusagepcnt",
				"cntrdescr"	=>	"Management CPU usage (%)",
				"maxval"	=>     4294967294,
				"tooltip"	=>	"Management CPU utilization percentage.",
			),
			"pktcpuusagepcnt" => Array(
				"cntrname"	=>	"pktcpuusagepcnt",
				"cntrdescr"	=>	"Packet CPU usage (%)",
				"maxval"	=>     4294967294,
				"tooltip"	=>	"Average CPU utilization percentage for all packet engines excluding management PE.",
			),
			"cpuusagepcnt" => Array(
				"cntrname"	=>	"cpuusagepcnt",
				"cntrdescr"	=>	"CPU usage (%)",
				"maxval"	=>     4294967294,
				"tooltip"	=>	"CPU utilization percentage.",
			),
			"rescpuusagepcnt" => Array(
				"cntrname"	=>	"rescpuusagepcnt",
				"cntrdescr"	=>	"Average CPU usage (%)",
				"maxval"	=>     4294967294,
				"tooltip"	=>	"Average CPU utilization percentage. Not applicable for a single-CPU system.",
			),
			"starttime" => Array(
				"cntrname"	=>	"starttime",
				"cntrdescr"	=>	"Up since",
				"tooltip"	=>	"Time when the NetScaler appliance was last started.",
				"type"		=>	"NS_VALDATE",
			),
			"disk0perusage" => Array(
				"cntrname"	=>	"disk0perusage",
				"cntrdescr"	=>	"/flash Used (%)",
				"tooltip"	=>	"Used space in /flash partition of the disk, as a percentage. This is a critical counter.<br><br>You can configure /flash Used (%) by using the Set snmp alarm DISK-USAGE-HIGH command.",
			),
			"disk1perusage" => Array(
				"cntrname"	=>	"disk1perusage",
				"cntrdescr"	=>	"/var Used (%)",
				"tooltip"	=>	"Used space in /var partition of the disk, as a percentage. This is a critical counter. You can configure /var Used (%) by using the Set snmp alarm DISK-USAGE-HIGH command.",
			),
			"cpufan0speed" => Array(
				"cntrname"	=>	"cpufan0speed",
				"cntrdescr"	=>	"CPU Fan 0 Speed (RPM)",
				"tooltip"	=>	"CPU Fan 0 speed. Acceptable range is 3000 through 6000 RPM. This is a critical counter.<br><br>You can configure CPU Fan 0 Speed by using the Set snmp alarm FAN-SPEED-LOW command to set the lower limit.",
			),
			"cpufan1speed" => Array(
				"cntrname"	=>	"cpufan1speed",
				"cntrdescr"	=>	"CPU Fan 1 Speed (RPM)",
				"tooltip"	=>	"CPU Fan 1 speed. Acceptable range is 3000 through 6000 RPM. 7000 platform displays speed of CPU fan 0. This is a critical counter.<br><br>You can configure CPU Fan 1 Speed by using the Set snmp alarm FAN-SPEED-LOW command to set the lower limit.",
			),
			"systemfanspeed" => Array(
				"cntrname"	=>	"systemfanspeed",
				"cntrdescr"	=>	"System Fan Speed (RPM)",
				"tooltip"	=>	"System fan speed. Acceptable range is 3000 through 6000 RPM. This is a critical counter.<br><br>You can configure System Fan Speed by using the Set snmp alarm FAN-SPEED-LOW command to set the lower limit.",
			),
			"fan0speed" => Array(
				"cntrname"	=>	"fan0speed",
				"cntrdescr"	=>	"System Fan 1 Speed (RPM)",
				"tooltip"	=>	"System fan 1 speed. For new platforms associated pin is connected to CPU supporting fans. For platforms in which it is not connected, it will point to System Fan.",
			),
			"fanspeed" => Array(
				"cntrname"	=>	"fanspeed",
				"cntrdescr"	=>	"System Fan 2 Speed (RPM)",
				"tooltip"	=>	"System fan 2 speed. For new platforms associated pin is connected to CPU supporting fans. For platforms in which it is not connected, it will point to System Fan",
			),
			"cpu0temp" => Array(
				"cntrname"	=>	"cpu0temp",
				"cntrdescr"	=>	"CPU 0 Temperature (Celsius)",
				"tooltip"	=>	"CPU 0 temperature. 9800 and 9960 platforms display internal chip temperature. This is a critical counter.<br><br>You can configure CPU 0 Temperature by using the Set snmp alarm TEMPERATURE-HIGH command to set the upper limit.",
			),
			"cpu1temp" => Array(
				"cntrname"	=>	"cpu1temp",
				"cntrdescr"	=>	"CPU 1 Temperature (Celsius)",
				"tooltip"	=>	"CPU 1 temperature. 9800 and 9960 platforms display internal chip temperature. 7000, 9010 and 10010 platforms display CPU 0 temperature. This is a critical counter.<br><br>You can configure CPU 1 Temperature by using the Set snmp alarm TEMPERATURE-HIGH command to set the upper limit.",
			),
			"internaltemp" => Array(
				"cntrname"	=>	"internaltemp",
				"cntrdescr"	=>	"Internal Temperature (Celsius)",
				"tooltip"	=>	"Internal temperature of health monitoring chip. This is a critical counter.<br><br>You can configure Internal Temperature by using the Set snmp alarm TEMPERATURE-HIGH command to set the upper limit.",
			),
			"powersupply1status" => Array(
				"cntrname"	=>	"powersupply1status",
				"cntrdescr"	=>	"Power supply 1 status",
				"tooltip"	=>	"Power supply 1 failure status.",
				"type"		=>	"NS_VALENUM",
			),
			"powersupply2status" => Array(
				"cntrname"	=>	"powersupply2status",
				"cntrdescr"	=>	"Power supply 2 status",
				"tooltip"	=>	"Power supply 2 failure status.",
				"type"		=>	"NS_VALENUM",
			),
			"powersupply3status" => Array(
				"cntrname"	=>	"powersupply3status",
				"cntrdescr"	=>	"Power supply 3 status",
				"tooltip"	=>	"Power supply 3 failure status.",
				"type"		=>	"NS_VALENUM",
			),
			"powersupply4status" => Array(
				"cntrname"	=>	"powersupply4status",
				"cntrdescr"	=>	"Power supply 4 status",
				"tooltip"	=>	"Power supply 4 failure status.",
				"type"		=>	"NS_VALENUM",
			),
			"disk0size" => Array(
				"cntrname"	=>	"disk0size",
				"cntrdescr"	=>	"/flash Size (MB)",
				"tooltip"	=>	"Size of /flash partition of the hard disk.",
			),
			"disk0used" => Array(
				"cntrname"	=>	"disk0used",
				"cntrdescr"	=>	"/flash Used (MB)",
				"tooltip"	=>	"Used space in /flash partition of the hard disk.",
			),
			"disk0avail" => Array(
				"cntrname"	=>	"disk0avail",
				"cntrdescr"	=>	"/flash Available (MB)",
				"tooltip"	=>	"Available space in /flash partition of the hard disk.",
			),
			"disk1size" => Array(
				"cntrname"	=>	"disk1size",
				"cntrdescr"	=>	"/var Size (MB)",
				"tooltip"	=>	"Size of /var partition of the hard disk.",
			),
			"disk1used" => Array(
				"cntrname"	=>	"disk1used",
				"cntrdescr"	=>	"/var Used (MB)",
				"tooltip"	=>	"Used space in /var partition of the hard disk.",
			),
			"disk1avail" => Array(
				"cntrname"	=>	"disk1avail",
				"cntrdescr"	=>	"/var Available (MB)",
				"tooltip"	=>	"Available space in /var partition of the hard disk.",
			),
			"fan2speed" => Array(
				"cntrname"	=>	"fan2speed",
				"cntrdescr"	=>	"Fan 0 Speed (RPM)",
				"tooltip"	=>	"Speed of Fan 0 if associated pin is connected to health monitoring chip.",
			),
			"fan3speed" => Array(
				"cntrname"	=>	"fan3speed",
				"cntrdescr"	=>	"Fan 1 Speed (RPM)",
				"tooltip"	=>	"Speed of Fan 1 if associated pin is connected to health monitoring chip.",
			),
			"fan4speed" => Array(
				"cntrname"	=>	"fan4speed",
				"cntrdescr"	=>	"Fan 2 Speed (RPM)",
				"tooltip"	=>	"Speed of Fan 2 if associated pin is connected to health monitoring chip.",
			),
			"fan5speed" => Array(
				"cntrname"	=>	"fan5speed",
				"cntrdescr"	=>	"Fan 3 Speed (RPM)",
				"tooltip"	=>	"Speed of Fan 3 if associated pin is connected to health monitoring chip.",
			),
			"auxtemp0" => Array(
				"cntrname"	=>	"auxtemp0",
				"cntrdescr"	=>	"Temperature 0 (Celsius)",
				"tooltip"	=>	"Temperature of a device connected to health monitoring chip through pin 0.",
			),
			"auxtemp1" => Array(
				"cntrname"	=>	"auxtemp1",
				"cntrdescr"	=>	"Temperature 1 (Celsius)",
				"tooltip"	=>	"Temperature of a device connected to health monitoring chip through pin 1.",
			),
			"auxtemp2" => Array(
				"cntrname"	=>	"auxtemp2",
				"cntrdescr"	=>	"Temperature 2 (Celsius)",
				"tooltip"	=>	"Temperature of a device connected to health monitoring chip through pin 2.",
			),
			"auxtemp3" => Array(
				"cntrname"	=>	"auxtemp3",
				"cntrdescr"	=>	"Temperature 3 (Celsius)",
				"tooltip"	=>	"Temperature of a device connected to health monitoring chip through pin 3.",
			),
			"timesincestart" => Array(
				"cntrname"	=>	"timesincestart",
				"cntrdescr"	=>	"Up time",
				"tooltip"	=>	"Seconds since the NetScaler appliance started.",
				"type"		=>	"NS_VALTIME",
			),
			"memsizemb" => Array(
				"cntrname"	=>	"memsizemb",
				"cntrdescr"	=>	"System memory (MB)",
				"tooltip"	=>	"Total amount of system memory, in megabytes.",
			),
		),
	"displayhandler"	=>
		Array(
			array (
			"format"	=>	"2C",
				"title"	=>	"System Information:",
			"column1" => 			Array(
				"dhID"	=>	1,
				"title"	=>	"",
				"header"	=>	array(),
				"dispflag"	=>	"Summary",
				"counters"	=>	array("starttime","disk0perusage","rescpuusagepcnt","cpuusagepcnt","pktcpuusagepcnt","mgmtcpuusagepcnt","NOCNTR",),
			),
			"column2" => 			Array(
				"dhID"	=>	2,
				"title"	=>	"",
				"header"	=>	array(),
				"dispflag"	=>	"Summary",
				"counters"	=>	array("memuseinmb","memusagepcnt","disk1perusage","NOCNTR",),
			),
			),
			array (
			"format"	=>	"2C",
				"title"	=>	"System Information:",
			"column1" => 			Array(
				"dhID"	=>	3,
				"title"	=>	"",
				"header"	=>	array(),
				"dispflag"	=>	"Detail",
				"counters"	=>	array("starttime","memuseinmb","memusagepcnt","rescpuusagepcnt","cpuusagepcnt","NOCNTR",),
			),
			"column2" => 			Array(
				"dhID"	=>	4,
				"title"	=>	"",
				"header"	=>	array(),
				"dispflag"	=>	"Detail",
				"counters"	=>	array("numcpus","NOCNTR",),
			),
			),
			Array(
				"dhID"	=>	5,
				"title"	=>	"System Health Statistics (Standard):",
				"header"	=>	array(),
				"format"	=>	"1C",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("voltagevcc0","voltagevcc1","voltagev33main","voltagev33stby","voltagev5p","voltagev12p","voltagevbat","voltagevtt","voltagev5sb","voltagevsen2","cpufan0speed","cpufan1speed","systemfanspeed","fan0speed","fanspeed","cpu0temp","cpu1temp","internaltemp","powersupply1status","powersupply2status","powersupply3status","powersupply4status",),
			),
			Array(
				"dhID"	=>	6,
				"title"	=>	"System Disk Statistics:",
				"header"	=>	array(),
				"format"	=>	"1C",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("disk0size","disk0used","disk0avail","disk0perusage","disk1size","disk1used","disk1avail","disk1perusage",),
				"charts"	=>	array(	array("flash_used","var_used",),
								),
			),
			Array(
				"dhID"	=>	7,
				"title"	=>	"System Health Statistics(Auxiliary):",
				"header"	=>	array(),
				"format"	=>	"1C",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("auxvolt0","auxvolt1","auxvolt2","auxvolt3","auxvolt4","auxvolt5","auxvolt6","auxvolt7","fan2speed","fan3speed","fan4speed","fan5speed","auxtemp0","auxtemp1","auxtemp2","auxtemp3",),
			),
		),
),


"statsystembw" => Array(
	"cmddispname"		=>	"statsystembw",
	"cmdname"		=>	"statsystembw",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"httpcltpoolinactive" => Array(
				"cntrname"	=>	"httpcltpoolinactive",
				"cntrdescr"	=>	"Number of HTTP Requests Sent",
				"tooltip"	=>	"No of requests sent from BW client.",
			),
			"httpcltpooloutactive" => Array(
				"cntrname"	=>	"httpcltpooloutactive",
				"cntrdescr"	=>	"Number of HTTP responses received",
				"tooltip"	=>	"No of responses Received.",
			),
			"httpsvr200okresp" => Array(
				"cntrname"	=>	"httpsvr200okresp",
				"cntrdescr"	=>	"Http responses (200 OK)",
				"tooltip"	=>	"Number of 200 Ok response sent from the BW appliance.",
			),
			"httpsvr200okresprate" => array (
				"cntrname"	=>	"httpsvr200okresprate",
				"cntrdescr"	=>	"",
			),
			"httpsvr404notfound" => Array(
				"cntrname"	=>	"httpsvr404notfound",
				"cntrdescr"	=>	"Http response error (404 NotFound)",
				"tooltip"	=>	"Number of 404 Not Found responses sent",
			),
			"httpsvr404notfoundrate" => array (
				"cntrname"	=>	"httpsvr404notfoundrate",
				"cntrdescr"	=>	"",
			),
			"httpclterrstray" => Array(
				"cntrname"	=>	"httpclterrstray",
				"cntrdescr"	=>	"No of stray packets received without request",
				"tooltip"	=>	"Number of stray packets received from server without HTTP request",
			),
			"httpclterrstrayrate" => array (
				"cntrname"	=>	"httpclterrstrayrate",
				"cntrdescr"	=>	"",
			),
			"httpcltttfplwm" => Array(
				"cntrname"	=>	"httpcltttfplwm",
				"cntrdescr"	=>	"RTT Value < 0.5 ms",
				"tooltip"	=>	"Number of Responses Falling on LWM for TTFP.",
			),
			"httpcltttfplwmrate" => array (
				"cntrname"	=>	"httpcltttfplwmrate",
				"cntrdescr"	=>	"",
			),
			"httpcltttfp_0" => Array(
				"cntrname"	=>	"httpcltttfp_0",
				"cntrdescr"	=>	"RTT Value > 0.5 ms AND <= 1.5 ms",
				"tooltip"	=>	"Number of Responses Falling on Band-0 for TTFP.",
			),
			"httpcltttfp_0rate" => array (
				"cntrname"	=>	"httpcltttfp_0rate",
				"cntrdescr"	=>	"",
			),
			"httpcltttfp_1" => Array(
				"cntrname"	=>	"httpcltttfp_1",
				"cntrdescr"	=>	"RTT Value > 1.5 ms AND <= 2.5 ms",
				"tooltip"	=>	"Number of Responses Falling on Band-1 for TTFP.",
			),
			"httpcltttfp_1rate" => array (
				"cntrname"	=>	"httpcltttfp_1rate",
				"cntrdescr"	=>	"",
			),
			"httpcltttfp_2" => Array(
				"cntrname"	=>	"httpcltttfp_2",
				"cntrdescr"	=>	"RTT Value > 2.5 ms AND <= 3.5 ms",
				"tooltip"	=>	"Number of Responses Falling on Band-2 for TTFP.",
			),
			"httpcltttfp_2rate" => array (
				"cntrname"	=>	"httpcltttfp_2rate",
				"cntrdescr"	=>	"",
			),
			"httpcltttfp_3" => Array(
				"cntrname"	=>	"httpcltttfp_3",
				"cntrdescr"	=>	"RTT Value > 3.5 ms AND <= 4.5 ms",
				"tooltip"	=>	"Number of Responses Falling on Band-3 for TTFP.",
			),
			"httpcltttfp_3rate" => array (
				"cntrname"	=>	"httpcltttfp_3rate",
				"cntrdescr"	=>	"",
			),
			"httpcltttfp_4" => Array(
				"cntrname"	=>	"httpcltttfp_4",
				"cntrdescr"	=>	"RTT Value > 4.5 ms AND <= 5.5 ms",
				"tooltip"	=>	"Number of Responses Falling on Band-4 for TTFP.",
			),
			"httpcltttfp_4rate" => array (
				"cntrname"	=>	"httpcltttfp_4rate",
				"cntrdescr"	=>	"",
			),
			"httpcltttfp_5" => Array(
				"cntrname"	=>	"httpcltttfp_5",
				"cntrdescr"	=>	"RTT Value > 5.5 ms AND <= 6.5 ms",
				"tooltip"	=>	"Number of Responses Falling on Band-5 for TTFP.",
			),
			"httpcltttfp_5rate" => array (
				"cntrname"	=>	"httpcltttfp_5rate",
				"cntrdescr"	=>	"",
			),
			"httpcltttfp_6" => Array(
				"cntrname"	=>	"httpcltttfp_6",
				"cntrdescr"	=>	"RTT Value > 6.5 ms AND <= 7.5 ms",
				"tooltip"	=>	"Number of Responses Falling on Band-6 for TTFP.",
			),
			"httpcltttfp_6rate" => array (
				"cntrname"	=>	"httpcltttfp_6rate",
				"cntrdescr"	=>	"",
			),
			"httpcltttfp_7" => Array(
				"cntrname"	=>	"httpcltttfp_7",
				"cntrdescr"	=>	"RTT Value > 7.5 ms AND <= 8.5 ms",
				"tooltip"	=>	"Number of Responses Falling on Band-7 for TTFP.",
			),
			"httpcltttfp_7rate" => array (
				"cntrname"	=>	"httpcltttfp_7rate",
				"cntrdescr"	=>	"",
			),
			"httpcltttfphwm" => Array(
				"cntrname"	=>	"httpcltttfphwm",
				"cntrdescr"	=>	"RTT Value > 8.5 ms",
				"tooltip"	=>	"Number of Responses Falling on HWM for TTFP.",
			),
			"httpcltttfphwmrate" => array (
				"cntrname"	=>	"httpcltttfphwmrate",
				"cntrdescr"	=>	"",
			),
			"httpcltttfpmax" => Array(
				"cntrname"	=>	"httpcltttfpmax",
				"cntrdescr"	=>	"TTFP Peak RTT",
				"tooltip"	=>	"Peak RTT observed for Time to First response packet.",
			),
			"httpcltttlplwm" => Array(
				"cntrname"	=>	"httpcltttlplwm",
				"cntrdescr"	=>	"RTT Value < 0.5 ms",
				"tooltip"	=>	"Number of Responses Falling on LWM for TTLP.",
			),
			"httpcltttlplwmrate" => array (
				"cntrname"	=>	"httpcltttlplwmrate",
				"cntrdescr"	=>	"",
			),
			"httpcltttlp_0" => Array(
				"cntrname"	=>	"httpcltttlp_0",
				"cntrdescr"	=>	"RTT Value > 0.5 ms AND <= 1.5 ms",
				"tooltip"	=>	"Number of Responses Falling on Band-0 for TTLP.",
			),
			"httpcltttlp_0rate" => array (
				"cntrname"	=>	"httpcltttlp_0rate",
				"cntrdescr"	=>	"",
			),
			"httpcltttlp_1" => Array(
				"cntrname"	=>	"httpcltttlp_1",
				"cntrdescr"	=>	"RTT Value > 1.5 ms AND <= 2.5 ms",
				"tooltip"	=>	"Number of Responses Falling on Band-1 for TTLP.",
			),
			"httpcltttlp_1rate" => array (
				"cntrname"	=>	"httpcltttlp_1rate",
				"cntrdescr"	=>	"",
			),
			"httpcltttlp_2" => Array(
				"cntrname"	=>	"httpcltttlp_2",
				"cntrdescr"	=>	"RTT Value > 2.5 ms AND <= 3.5 ms",
				"tooltip"	=>	"Number of Responses Falling on Band-2 for TTLP.",
			),
			"httpcltttlp_2rate" => array (
				"cntrname"	=>	"httpcltttlp_2rate",
				"cntrdescr"	=>	"",
			),
			"httpcltttlp_3" => Array(
				"cntrname"	=>	"httpcltttlp_3",
				"cntrdescr"	=>	"RTT Value > 3.5 ms AND <= 4.5 ms",
				"tooltip"	=>	"Number of Responses Falling on Band-3 for TTLP.",
			),
			"httpcltttlp_3rate" => array (
				"cntrname"	=>	"httpcltttlp_3rate",
				"cntrdescr"	=>	"",
			),
			"httpcltttlp_4" => Array(
				"cntrname"	=>	"httpcltttlp_4",
				"cntrdescr"	=>	"RTT Value > 4.5 ms AND <= 5.5 ms",
				"tooltip"	=>	"Number of Responses Falling on Band-4 for TTLP.",
			),
			"httpcltttlp_4rate" => array (
				"cntrname"	=>	"httpcltttlp_4rate",
				"cntrdescr"	=>	"",
			),
			"httpcltttlp_5" => Array(
				"cntrname"	=>	"httpcltttlp_5",
				"cntrdescr"	=>	"RTT Value > 5.5 ms AND <= 6.5 ms",
				"tooltip"	=>	"Number of Responses Falling on Band-5 for TTLP.",
			),
			"httpcltttlp_5rate" => array (
				"cntrname"	=>	"httpcltttlp_5rate",
				"cntrdescr"	=>	"",
			),
			"httpcltttlp_6" => Array(
				"cntrname"	=>	"httpcltttlp_6",
				"cntrdescr"	=>	"RTT Value > 6.5 ms AND <= 7.5 ms",
				"tooltip"	=>	"Number of Responses Falling on Band-6 for TTLP.",
			),
			"httpcltttlp_6rate" => array (
				"cntrname"	=>	"httpcltttlp_6rate",
				"cntrdescr"	=>	"",
			),
			"httpcltttlp_7" => Array(
				"cntrname"	=>	"httpcltttlp_7",
				"cntrdescr"	=>	"RTT Value > 7.5 ms AND <= 8.5 ms",
				"tooltip"	=>	"Number of Responses Falling on Band-7 for TTLP.",
			),
			"httpcltttlp_7rate" => array (
				"cntrname"	=>	"httpcltttlp_7rate",
				"cntrdescr"	=>	"",
			),
			"httpcltttlphwm" => Array(
				"cntrname"	=>	"httpcltttlphwm",
				"cntrdescr"	=>	"RTT Value > 8.5 ms",
				"tooltip"	=>	"Number of Responses Falling on HWM for TTLP.",
			),
			"httpcltttlphwmrate" => array (
				"cntrname"	=>	"httpcltttlphwmrate",
				"cntrdescr"	=>	"",
			),
			"httpcltttlpmax" => Array(
				"cntrname"	=>	"httpcltttlpmax",
				"cntrdescr"	=>	"TTLP peak RTT",
				"tooltip"	=>	"Peak RTT observed for Time to Last response packet.",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Response Statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("httpsvr200okresp",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Error Statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("httpsvr404notfound","httpclterrstray",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"RTT for First Response Packet",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("httpcltttfplwm","httpcltttfp_0","httpcltttfp_1","httpcltttfp_2","httpcltttfp_3","httpcltttfp_4","httpcltttfp_5","httpcltttfp_6","httpcltttfp_7","httpcltttfphwm","httpcltttfpmax",),
			),
			Array(
				"dhID"	=>	4,
				"title"	=>	"RTT for Last Response Packet",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("httpcltttlplwm","httpcltttlp_0","httpcltttlp_1","httpcltttlp_2","httpcltttlp_3","httpcltttlp_4","httpcltttlp_5","httpcltttlp_6","httpcltttlp_7","httpcltttlphwm","httpcltttlpmax",),
			),
		),
),


"statsystemcpu" => Array(
	"cmddispname"		=>	"statsystemcpu",
	"cmdname"		=>	"statsystemcpu",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"id" => Array(
				"argname"		=>	"id",
				"valtype"		=>	"NS_VALUINT",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "id",
	"nameargtype"	=> "NS_VALUINT",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"percpuuse" => Array(
				"cntrname"	=>	"percpuuse",
				"cntrdescr"	=>	"CPU Usage",
				"tooltip"	=>	"CPU utilization percentage.",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"CPU statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("percpuuse",),
				"charts"	=>	array(	array("per_cpu_usage",),
								),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"CPU statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("percpuuse",),
				"charts"	=>	array(	array("per_cpu_usage",),
								),
			),
		),
),


"statsystemmemory" => Array(
	"cmddispname"		=>	"statsystemmemory",
	"cmdname"		=>	"statsystemmemory",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"cacmemmaxmemlimitpcnt" => Array(
				"cntrname"	=>	"cacmemmaxmemlimitpcnt",
				"cntrdescr"	=>	"InUse Memory (%)",
				"tooltip"	=>	"Integrated Cache memory insue percent.",
			),
			"cacmemmaxmemlimit" => Array(
				"cntrname"	=>	"cacmemmaxmemlimit",
				"cntrdescr"	=>	"InUse Memory (MB)",
				"tooltip"	=>	"Integrated Cache memory insue, in megabytes.",
			),
			"shmemerrallocfailed" => Array(
				"cntrname"	=>	"shmemerrallocfailed",
				"cntrdescr"	=>	"Maximum Allocation Failed (MB)",
				"tooltip"	=>	"Total shared memory allocation failed.",
			),
			"shmemallocpcnt" => Array(
				"cntrname"	=>	"shmemallocpcnt",
				"cntrdescr"	=>	"Shared Memory InUse (%)",
				"tooltip"	=>	"Shared memory insue percent.",
			),
			"shmemallocinmb" => Array(
				"cntrname"	=>	"shmemallocinmb",
				"cntrdescr"	=>	"Shared Memory InUse (MB)",
				"tooltip"	=>	"Shared memory insue, in megabytes.",
			),
			"shmemtotinmb" => Array(
				"cntrname"	=>	"shmemtotinmb",
				"cntrdescr"	=>	"Total Shared Memory (MB)",
				"tooltip"	=>	"Total shared memory allowed to allocate, in megabytes.",
			),
			"memtotallocfailed" => Array(
				"cntrname"	=>	"memtotallocfailed",
				"cntrdescr"	=>	"Maximum Allocation Failed (MB)",
				"tooltip"	=>	"Total system memory allocation failed.",
			),
			"memtotfree" => Array(
				"cntrname"	=>	"memtotfree",
				"cntrdescr"	=>	"Free Memory (MB)",
				"tooltip"	=>	"Total Free PE Memory in the System.",
			),
			"memusagepcnt" => Array(
				"cntrname"	=>	"memusagepcnt",
				"cntrdescr"	=>	"InUse Memory (%)",
				"tooltip"	=>	"Percentage of memory utilization on NetScaler.",
			),
			"memtotuseinmb" => Array(
				"cntrname"	=>	"memtotuseinmb",
				"cntrdescr"	=>	"InUse Memory (MB)",
				"tooltip"	=>	"Total NetScaler Memory in use, in megabytes.",
			),
			"memtotallocpcnt" => Array(
				"cntrname"	=>	"memtotallocpcnt",
				"cntrdescr"	=>	"Memory Allocated (%)",
				"tooltip"	=>	"Currently allocated memory in percent.",
			),
			"memtotallocmb" => Array(
				"cntrname"	=>	"memtotallocmb",
				"cntrdescr"	=>	"Memory Allocated (MB)",
				"tooltip"	=>	"Currently allocated memory, in megabytes.",
			),
			"memtotinmb" => Array(
				"cntrname"	=>	"memtotinmb",
				"cntrdescr"	=>	"Memory Currently Available (MB)",
				"tooltip"	=>	"Total memory available (grabbed) for use by packet engine (PE), in megabytes.",
			),
			"memtotavail" => Array(
				"cntrname"	=>	"memtotavail",
				"cntrdescr"	=>	"Maximum Memory Available (MB)",
				"tooltip"	=>	"Total system memory available for PE to grab from the system.",
			),
			"cacmemmaxsyslimitmb" => Array(
				"cntrname"	=>	"cacmemmaxsyslimitmb",
				"cntrdescr"	=>	"Maximum Memory (MB)",
				"tooltip"	=>	"Integrated Cache memory, in megabytes.",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"NetScaler Memory Information:",
				"header"	=>	array(),
				"format"	=>	"1C",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("memtotavail","memtotinmb","memtotallocmb","memtotallocpcnt","memtotuseinmb","memusagepcnt","memtotfree","memtotallocfailed",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"NetScaler Shared Memory Information:",
				"header"	=>	array(),
				"format"	=>	"1C",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("shmemtotinmb","shmemallocinmb","shmemallocpcnt","shmemerrallocfailed",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"NetScaler Integrated Cache Memory Information:",
				"header"	=>	array(),
				"format"	=>	"1C",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("cacmemmaxsyslimitmb","cacmemmaxmemlimit","cacmemmaxmemlimitpcnt",),
			),
			Array(
				"text" => "NONE",
				"flag" => "Summary",
				"key" => "default",
				"relatedlink_align"	=> "right",
				"relatedlink"	=>
					Array(
						array(
							 "text" => "Feature Memory Statistics", 
							 "cmd" => "statnsmemory", 
							 "type" => "summary", 
						),
					),
			),
		),
),


"stattmsessionpolicy" => Array(
	"cmddispname"		=>	"stattmsessionpolicy",
	"cmdname"		=>	"stattmsessionpolicy",
	"license"		=>	Array("NSKF1_AAA_BIT_POS","NSKF1_AAA_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"pipolicyhits" => Array(
				"cntrname"	=>	"pipolicyhits",
				"cntrdescr"	=>	"Policy hits",
				"tooltip"	=>	"Number of hits on the policy",
			),
			"pipolicyhitsrate" => array (
				"cntrname"	=>	"pipolicyhitsrate",
				"cntrdescr"	=>	"",
			),
			"pipolicyundefhits" => Array(
				"cntrname"	=>	"pipolicyundefhits",
				"cntrdescr"	=>	"Policy undef hits",
				"tooltip"	=>	"Number of undef hits on the policy",
			),
			"pipolicyundefhitsrate" => array (
				"cntrname"	=>	"pipolicyundefhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"tmsession Policy Statistics",
				"header"	=>	array(" ","Hits","Rate(/s)","UndefHits","Rate(/s)",),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pipolicyhits","pipolicyundefhits",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"tmsession Policy(s) Statistics",
				"header"	=>	array(" ","Hits","Rate(/s)","UndefHits","Rate(/s)",),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pipolicyhits","pipolicyundefhits",),
			),
		),
),


"stattmtrafficpolicy" => Array(
	"cmddispname"		=>	"stattmtrafficpolicy",
	"cmdname"		=>	"stattmtrafficpolicy",
	"license"		=>	Array("NSKF1_AAA_BIT_POS","NSKF1_AAA_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"pipolicyhits" => Array(
				"cntrname"	=>	"pipolicyhits",
				"cntrdescr"	=>	"Policy hits",
				"tooltip"	=>	"Number of hits on the policy",
			),
			"pipolicyhitsrate" => array (
				"cntrname"	=>	"pipolicyhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"TM Traffic Policy Statistics",
				"header"	=>	array(" ","Hits","Rate(/s)",),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pipolicyhits",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"TM Traffic Policy(s) Statistics",
				"header"	=>	array(" ","Hits","Rate(/s)",),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pipolicyhits",),
			),
		),
),


"stattransformpolicy" => Array(
	"cmddispname"		=>	"stattransformpolicy",
	"cmdname"		=>	"stattransformpolicy",
	"license"		=>	Array("NSKF1_REWRITE_BIT_POS","NSKF1_REWRITE_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"pipolicyhits" => Array(
				"cntrname"	=>	"pipolicyhits",
				"cntrdescr"	=>	"Policy hits",
				"tooltip"	=>	"Number of hits on the policy",
			),
			"pipolicyhitsrate" => array (
				"cntrname"	=>	"pipolicyhitsrate",
				"cntrdescr"	=>	"",
			),
			"pipolicyundefhits" => Array(
				"cntrname"	=>	"pipolicyundefhits",
				"cntrdescr"	=>	"Policy undef hits",
				"tooltip"	=>	"Number of undef hits on the policy",
			),
			"pipolicyundefhitsrate" => array (
				"cntrname"	=>	"pipolicyundefhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Transform Policy Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pipolicyhits","pipolicyundefhits",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Transform Policy(s) Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pipolicyhits","pipolicyundefhits",),
			),
		),
),


"stattransformpolicylabel" => Array(
	"cmddispname"		=>	"stattransformpolicylabel",
	"cmdname"		=>	"stattransformpolicylabel",
	"license"		=>	Array("NSKF1_REWRITE_BIT_POS","NSKF1_REWRITE_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"labelname" => Array(
				"argname"		=>	"labelname",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "labelname",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"pipolicylabelhits" => Array(
				"cntrname"	=>	"pipolicylabelhits",
				"cntrdescr"	=>	"Policy Label Hits",
				"tooltip"	=>	"Number of times policy label was invoked.",
			),
			"pipolicylabelhitsrate" => array (
				"cntrname"	=>	"pipolicylabelhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Transform Policylabel Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pipolicylabelhits",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Transform Policylabel(s) Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pipolicylabelhits",),
			),
		),
),


"stattransformpolicybinding" => Array(
	"cmddispname"		=>	"stattransformpolicybinding",
	"cmdname"		=>	"stattransformpolicybinding",
	"license"		=>	Array("NSKF1_REWRITE_BIT_POS","NSKF1_REWRITE_BIT_POS"),
	"cmddescr"	=>	"     ",
	"hidden"	=>	1,
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"pipolicybindinghits" => Array(
				"cntrname"	=>	"pipolicybindinghits",
				"cntrdescr"	=>	"Policy binding hits",
				"tooltip"	=>	"Number of hits on the policy on this binding",
			),
			"pipolicybindinghitsrate" => array (
				"cntrname"	=>	"pipolicybindinghitsrate",
				"cntrdescr"	=>	"",
			),
			"pipolicybindingundefhits" => Array(
				"cntrname"	=>	"pipolicybindingundefhits",
				"cntrdescr"	=>	"Policy binding undef hits",
				"tooltip"	=>	"Number of undef hits on the policy on this binding",
			),
			"pipolicybindingundefhitsrate" => array (
				"cntrname"	=>	"pipolicybindingundefhitsrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Transform Binding Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pipolicybindinghits","pipolicybindingundefhits",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Transform Binding(s) Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pipolicybindinghits","pipolicybindingundefhits",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Transform Binding(s) Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"subent",
				"counters"	=>	array("pipolicybindinghits","pipolicybindingundefhits",),
			),
		),
),


"statvpn" => Array(
	"cmddispname"		=>	"statvpn",
	"cmdname"		=>	"statvpn",
	"license"		=>	Array("NSKF1_SSLVPN_BIT_POS","NSKF1_SSLVPN_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"indexhtmlhit" => Array(
				"cntrname"	=>	"indexhtmlhit",
				"cntrdescr"	=>	"Login-page requests received",
				"tooltip"	=>	"Number of requests for VPN login page.",
			),
			"indexhtmlnoserved" => Array(
				"cntrname"	=>	"indexhtmlnoserved",
				"cntrdescr"	=>	"Login-page delivery failures",
				"tooltip"	=>	"Number of failures to display VPN login page.",
			),
			"cfghtmlserved" => Array(
				"cntrname"	=>	"cfghtmlserved",
				"cntrdescr"	=>	"Client-configuration requests",
				"tooltip"	=>	"Number of client configuration requests received by VPN server.",
			),
			"cfghtmlservedrate" => array (
				"cntrname"	=>	"cfghtmlservedrate",
				"cntrdescr"	=>	"",
			),
			"dnsreqhit" => Array(
				"cntrname"	=>	"dnsreqhit",
				"cntrdescr"	=>	"DNS queries resolved",
				"tooltip"	=>	"Number of DNS queries resolved by VPN server.",
			),
			"dnsreqhitrate" => array (
				"cntrname"	=>	"dnsreqhitrate",
				"cntrdescr"	=>	"",
			),
			"winsrequesthit" => Array(
				"cntrname"	=>	"winsrequesthit",
				"cntrdescr"	=>	"WINS queries resolved",
				"tooltip"	=>	"Number of WINS queries resolved by VPN server.",
			),
			"winsrequesthitrate" => array (
				"cntrname"	=>	"winsrequesthitrate",
				"cntrdescr"	=>	"",
			),
			"csrequesthit" => Array(
				"cntrname"	=>	"csrequesthit",
				"cntrdescr"	=>	"Number of SSLVPN tunnels",
				"tooltip"	=>	"Number of SSL VPN tunnels formed between VPN server and client.",
			),
			"csrequesthitrate" => array (
				"cntrname"	=>	"csrequesthitrate",
				"cntrdescr"	=>	"",
			),
			"csnonhttpprobehit" => Array(
				"cntrname"	=>	"csnonhttpprobehit",
				"cntrdescr"	=>	"Backend non-HTTP server probes",
				"tooltip"	=>	"Number of probes from VPN to back-end non-HTTP servers that have been accessed by the VPN client.",
			),
			"csnonhttpprobehitrate" => array (
				"cntrname"	=>	"csnonhttpprobehitrate",
				"cntrdescr"	=>	"",
			),
			"cshttpprobehit" => Array(
				"cntrname"	=>	"cshttpprobehit",
				"cntrdescr"	=>	"Backend HTTP server probes",
				"tooltip"	=>	"Number of probes from VPN to back-end HTTP servers that have been accessed by the VPN client.",
			),
			"cshttpprobehitrate" => array (
				"cntrname"	=>	"cshttpprobehitrate",
				"cntrdescr"	=>	"",
			),
			"totalcsconnsucc" => Array(
				"cntrname"	=>	"totalcsconnsucc",
				"cntrdescr"	=>	"Backend server probe successes",
				"tooltip"	=>	"Number of successful probes to all back-end servers.",
			),
			"csconnsuccrate" => array (
				"cntrname"	=>	"csconnsuccrate",
				"cntrdescr"	=>	"",
			),
			"totalfsrequest" => Array(
				"cntrname"	=>	"totalfsrequest",
				"cntrdescr"	=>	"File-system requests received",
				"tooltip"	=>	"Number of file system requests received by VPN server.",
			),
			"fsrequestrate" => array (
				"cntrname"	=>	"fsrequestrate",
				"cntrdescr"	=>	"",
			),
			"iipdisabledmipused" => Array(
				"cntrname"	=>	"iipdisabledmipused",
				"cntrdescr"	=>	"IIP disabled and SNIP used",
				"tooltip"	=>	"Number of times SNIP is used as IIP is disabled.",
			),
			"iipdisabledmipusedrate" => array (
				"cntrname"	=>	"iipdisabledmipusedrate",
				"cntrdescr"	=>	"",
			),
			"iipfailedmipused" => Array(
				"cntrname"	=>	"iipfailedmipused",
				"cntrdescr"	=>	"IIP failed and SNIP used",
				"tooltip"	=>	"Number of times SNIP is used as IIP assignment failed.",
			),
			"iipfailedmipusedrate" => array (
				"cntrname"	=>	"iipfailedmipusedrate",
				"cntrdescr"	=>	"",
			),
			"iipspillovermipused" => Array(
				"cntrname"	=>	"iipspillovermipused",
				"cntrdescr"	=>	"IIP spillover and SNIP used",
				"tooltip"	=>	"Number of times SNIP is used on IIP Spillover.",
			),
			"iipspillovermipusedrate" => array (
				"cntrname"	=>	"iipspillovermipusedrate",
				"cntrdescr"	=>	"",
			),
			"iipdisabledmipdisabled" => Array(
				"cntrname"	=>	"iipdisabledmipdisabled",
				"cntrdescr"	=>	"IIP disabled and SNIP disabled",
				"tooltip"	=>	"Both IIP and SNIP is disabled.",
			),
			"iipdisabledmipdisabledrate" => array (
				"cntrname"	=>	"iipdisabledmipdisabledrate",
				"cntrdescr"	=>	"",
			),
			"iipfailedmipdisabled" => Array(
				"cntrname"	=>	"iipfailedmipdisabled",
				"cntrdescr"	=>	"IIP failed and SNIP disabled",
				"tooltip"	=>	"Number of times IIP assignment failed and SNIP is disabled.",
			),
			"iipfailedmipdisabledrate" => array (
				"cntrname"	=>	"iipfailedmipdisabledrate",
				"cntrdescr"	=>	"",
			),
			"socksmethreqrcvd" => Array(
				"cntrname"	=>	"socksmethreqrcvd",
				"cntrdescr"	=>	"SOCKS method request received",
				"tooltip"	=>	"Number of received SOCKS method request.",
			),
			"socksmethreqrcvdrate" => array (
				"cntrname"	=>	"socksmethreqrcvdrate",
				"cntrdescr"	=>	"",
			),
			"socksmethreqsent" => Array(
				"cntrname"	=>	"socksmethreqsent",
				"cntrdescr"	=>	"SOCKS method request sent",
				"tooltip"	=>	"Number of sent SOCKS method request.",
			),
			"socksmethreqsentrate" => array (
				"cntrname"	=>	"socksmethreqsentrate",
				"cntrdescr"	=>	"",
			),
			"socksmethresprcvd" => Array(
				"cntrname"	=>	"socksmethresprcvd",
				"cntrdescr"	=>	"SOCKS method response received",
				"tooltip"	=>	"Number of received SOCKS method response.",
			),
			"socksmethresprcvdrate" => array (
				"cntrname"	=>	"socksmethresprcvdrate",
				"cntrdescr"	=>	"",
			),
			"socksmethrespsent" => Array(
				"cntrname"	=>	"socksmethrespsent",
				"cntrdescr"	=>	"SOCKS method response sent",
				"tooltip"	=>	"Number of sent SOCKS method response.",
			),
			"socksmethrespsentrate" => array (
				"cntrname"	=>	"socksmethrespsentrate",
				"cntrdescr"	=>	"",
			),
			"socksconnreqrcvd" => Array(
				"cntrname"	=>	"socksconnreqrcvd",
				"cntrdescr"	=>	"SOCKS connect request received",
				"tooltip"	=>	"Number of received SOCKS connect request.",
			),
			"socksconnreqrcvdrate" => array (
				"cntrname"	=>	"socksconnreqrcvdrate",
				"cntrdescr"	=>	"",
			),
			"socksconnreqsent" => Array(
				"cntrname"	=>	"socksconnreqsent",
				"cntrdescr"	=>	"SOCKS connect request sent",
				"tooltip"	=>	"Number of sent SOCKS connect request.",
			),
			"socksconnreqsentrate" => array (
				"cntrname"	=>	"socksconnreqsentrate",
				"cntrdescr"	=>	"",
			),
			"socksconnresprcvd" => Array(
				"cntrname"	=>	"socksconnresprcvd",
				"cntrdescr"	=>	"SOCKS connect response received",
				"tooltip"	=>	"Number of received SOCKS connect response.",
			),
			"socksconnresprcvdrate" => array (
				"cntrname"	=>	"socksconnresprcvdrate",
				"cntrdescr"	=>	"",
			),
			"socksconnrespsent" => Array(
				"cntrname"	=>	"socksconnrespsent",
				"cntrdescr"	=>	"SOCKS connect response sent",
				"tooltip"	=>	"Number of sent SOCKS connect response.",
			),
			"socksconnrespsentrate" => array (
				"cntrname"	=>	"socksconnrespsentrate",
				"cntrdescr"	=>	"",
			),
			"socksservererror" => Array(
				"cntrname"	=>	"socksservererror",
				"cntrdescr"	=>	"SOCKS server error",
				"tooltip"	=>	"Number of SOCKS server error.",
			),
			"socksservererrorrate" => array (
				"cntrname"	=>	"socksservererrorrate",
				"cntrdescr"	=>	"",
			),
			"socksclienterror" => Array(
				"cntrname"	=>	"socksclienterror",
				"cntrdescr"	=>	"SOCKS client error",
				"tooltip"	=>	"Number of SOCKS client error.",
			),
			"socksclienterrorrate" => array (
				"cntrname"	=>	"socksclienterrorrate",
				"cntrdescr"	=>	"",
			),
			"staconnsuccess" => Array(
				"cntrname"	=>	"staconnsuccess",
				"cntrdescr"	=>	"STA connection success",
				"tooltip"	=>	"Number of STA connection success.",
			),
			"staconnsuccessrate" => array (
				"cntrname"	=>	"staconnsuccessrate",
				"cntrdescr"	=>	"",
			),
			"staconnfailure" => Array(
				"cntrname"	=>	"staconnfailure",
				"cntrdescr"	=>	"STA connection failure",
				"tooltip"	=>	"Number of STA connection failure.",
			),
			"staconnfailurerate" => array (
				"cntrname"	=>	"staconnfailurerate",
				"cntrdescr"	=>	"",
			),
			"cpsconnsuccess" => Array(
				"cntrname"	=>	"cpsconnsuccess",
				"cntrdescr"	=>	"CPS connection success",
				"tooltip"	=>	"Number of CPS connection success.",
			),
			"cpsconnsuccessrate" => array (
				"cntrname"	=>	"cpsconnsuccessrate",
				"cntrdescr"	=>	"",
			),
			"cpsconnfailure" => Array(
				"cntrname"	=>	"cpsconnfailure",
				"cntrdescr"	=>	"CPS connection failure",
				"tooltip"	=>	"Number of CPS connection failure.",
			),
			"cpsconnfailurerate" => array (
				"cntrname"	=>	"cpsconnfailurerate",
				"cntrdescr"	=>	"",
			),
			"starequestsent" => Array(
				"cntrname"	=>	"starequestsent",
				"cntrdescr"	=>	"STA request sent",
				"tooltip"	=>	"Number of STA request sent.",
			),
			"starequestsentrate" => array (
				"cntrname"	=>	"starequestsentrate",
				"cntrdescr"	=>	"",
			),
			"staresponserecvd" => Array(
				"cntrname"	=>	"staresponserecvd",
				"cntrdescr"	=>	"STA response received",
				"tooltip"	=>	"Number of STA response received.",
			),
			"staresponserecvdrate" => array (
				"cntrname"	=>	"staresponserecvdrate",
				"cntrdescr"	=>	"",
			),
			"icalicensefailure" => Array(
				"cntrname"	=>	"icalicensefailure",
				"cntrdescr"	=>	"ICA license failure",
				"tooltip"	=>	"Number of ICA license failure.",
			),
			"icalicensefailurerate" => array (
				"cntrname"	=>	"icalicensefailurerate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"",
				"header"	=>	array("","Total",),
				"format"	=>	"1C",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("indexhtmlhit","indexhtmlnoserved","cfghtmlserved","dnsreqhit","winsrequesthit","csrequesthit","csnonhttpprobehit","cshttpprobehit","totalcsconnsucc","totalfsrequest",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Login Success:",
				"header"	=>	array("","Total",),
				"format"	=>	"1C",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("iipdisabledmipused","iipfailedmipused","iipspillovermipused",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Login Failure:",
				"header"	=>	array("","Total",),
				"format"	=>	"1C",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("iipdisabledmipdisabled","iipfailedmipdisabled",),
			),
			Array(
				"dhID"	=>	4,
				"title"	=>	"CSG Statistics:",
				"header"	=>	array("","Total",),
				"format"	=>	"1C",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("socksmethreqrcvd","socksmethreqsent","socksmethresprcvd","socksmethrespsent","socksconnreqrcvd","socksconnreqsent","socksconnresprcvd","socksconnrespsent","socksservererror","socksclienterror","staconnsuccess","staconnfailure","cpsconnsuccess","cpsconnfailure","starequestsent","staresponserecvd","icalicensefailure",),
			),
		),
),


"statvpnvserver" => Array(
	"cmddispname"		=>	"statvpnvserver",
	"enable"            =>      "1",
	"cmdname"		=>	"statvpnvserver",
	"license"		=>	Array("NSKF1_SSLVPN_BIT_POS","NSKF1_SSLVPN_BIT_POS"),
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"primaryipaddress" => Array(
				"cntrname"	=>	"primaryipaddress",
				"cntrdescr"	=>	"IP address",
				"tooltip"	=>	"The IP address on which the service is running.",
				"type"		=>	"NS_VALIPSTR",
			),
			"primaryport" => Array(
				"cntrname"	=>	"primaryport",
				"cntrdescr"	=>	"Port",
				"tooltip"	=>	"The port on which the service is running.",
				"type"		=>	"NS_VALPORT",
			),
			"type" => Array(
				"cntrname"	=>	"type",
				"cntrdescr"	=>	"Vserver protocol",
				"tooltip"	=>	"Protocol associated with the vserver",
				"type"		=>	"NS_VALENUM",
			),
			"state" => Array(
				"cntrname"	=>	"state",
				"cntrdescr"	=>	"State",
				"tooltip"	=>	"Current state of the server. Possible values are UP, DOWN, UNKNOWN, OFS(Out of Service), TROFS(Transition Out of Service), TROFS_DOWN(Down When going Out of Service)",
				"type"		=>	"NS_VALENUM",
			),
			"totalrequests" => Array(
				"cntrname"	=>	"totalrequests",
				"cntrdescr"	=>	"Requests",
				"tooltip"	=>	"Total number of requests received on this service or virtual server. (This applies to HTTP/SSL services and servers.)",
			),
			"requestsrate" => array (
				"cntrname"	=>	"requestsrate",
				"cntrdescr"	=>	"",
			),
			"totalresponses" => Array(
				"cntrname"	=>	"totalresponses",
				"cntrdescr"	=>	"Responses",
				"tooltip"	=>	"Number of responses received on this service or virtual server. (This applies to HTTP/SSL services and servers.)",
			),
			"responsesrate" => array (
				"cntrname"	=>	"responsesrate",
				"cntrdescr"	=>	"",
			),
			"totalrequestbytes" => Array(
				"cntrname"	=>	"totalrequestbytes",
				"cntrdescr"	=>	"Request bytes",
				"tooltip"	=>	"Total number of request bytes received on this service or virtual server.",
			),
			"requestbytesrate" => array (
				"cntrname"	=>	"requestbytesrate",
				"cntrdescr"	=>	"",
			),
			"totalresponsebytes" => Array(
				"cntrname"	=>	"totalresponsebytes",
				"cntrdescr"	=>	"Response bytes",
				"tooltip"	=>	"Number of response bytes received by this service or virtual server.",
			),
			"responsebytesrate" => array (
				"cntrname"	=>	"responsebytesrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"Vserver Summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("primaryipaddress","primaryport","type","state",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"VServer Stats:",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("totalrequests","totalresponses","totalrequestbytes","totalresponsebytes",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Vserver(s) Summary",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("primaryipaddress","primaryport","type","state","totalrequests",),
			),
		),
),


"statlldp" => Array(
	"cmddispname"		=>	"statlldp",
	"cmdname"		=>	"statlldp",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"ifnum" => Array(
				"argname"		=>	"ifnum",
				"valtype"		=>	"NS_VALIFN",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "ifnum",
	"nameargtype"	=> "NS_VALIFN",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"rxportframestotal" => Array(
				"cntrname"	=>	"rxportframestotal",
				"cntrdescr"	=>	"LLDP received Packets",
				"tooltip"	=>	"Total LLDP Packets received.",
			),
			"rxportframesrate" => array (
				"cntrname"	=>	"rxportframesrate",
				"cntrdescr"	=>	"",
			),
			"rxportbytestotal" => Array(
				"cntrname"	=>	"rxportbytestotal",
				"cntrdescr"	=>	"LLDP bytes received",
				"tooltip"	=>	"Total LLDP bytes received",
			),
			"rxportbytesrate" => array (
				"cntrname"	=>	"rxportbytesrate",
				"cntrdescr"	=>	"",
			),
			"txportframestotal" => Array(
				"cntrname"	=>	"txportframestotal",
				"cntrdescr"	=>	"LLDP packets transmitted",
				"tooltip"	=>	"Total LLDP Packets transmitted",
			),
			"txportframesrate" => array (
				"cntrname"	=>	"txportframesrate",
				"cntrdescr"	=>	"",
			),
			"txportbytestotal" => Array(
				"cntrname"	=>	"txportbytestotal",
				"cntrdescr"	=>	"LLDP bytes transmitted",
				"tooltip"	=>	"Total LLDP bytes transmitted.",
			),
			"txportbytesrate" => array (
				"cntrname"	=>	"txportbytesrate",
				"cntrdescr"	=>	"",
			),
			"rxportframeserrors" => Array(
				"cntrname"	=>	"rxportframeserrors",
				"cntrdescr"	=>	"Errors in LLDP Packets",
				"tooltip"	=>	"Total errors in LLDP packets.",
			),
			"rxportframeserrorsrate" => array (
				"cntrname"	=>	"rxportframeserrorsrate",
				"cntrdescr"	=>	"",
			),
			"rxporttlvsdiscardedtotal" => Array(
				"cntrname"	=>	"rxporttlvsdiscardedtotal",
				"cntrdescr"	=>	"Discarded LLDP Packets",
				"tooltip"	=>	"Total discarded LLDP packets.",
			),
			"rxporttlvsdiscardedrate" => array (
				"cntrname"	=>	"rxporttlvsdiscardedrate",
				"cntrdescr"	=>	"",
			),
			"rxporttlvsunrecognizedtotal" => Array(
				"cntrname"	=>	"rxporttlvsunrecognizedtotal",
				"cntrdescr"	=>	"TLVs not Recognised",
				"tooltip"	=>	"Total TLVs not Recognised.",
			),
			"rxporttlvsunrecognizedrate" => array (
				"cntrname"	=>	"rxporttlvsunrecognizedrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("rxportframestotal","rxportbytestotal","txportframestotal","txportbytestotal","rxportframeserrors","rxporttlvsdiscardedtotal","rxporttlvsunrecognizedtotal",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"LLDP Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("rxportframestotal","txportframestotal","rxportframeserrors","rxporttlvsdiscardedtotal",),
			),
		),
),


"statmediaclassification" => Array(
	"cmddispname"		=>	"statmediaclassification",
	"cmdname"		=>	"statmediaclassification",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"mcencvideo" => Array(
				"cntrname"	=>	"mcencvideo",
				"cntrdescr"	=>	"Total encrypted video streams",
				"tooltip"	=>	"Total number of encrypted video streams",
			),
			"mcencvideorate" => array (
				"cntrname"	=>	"mcencvideorate",
				"cntrdescr"	=>	"",
			),
			"mctotother" => Array(
				"cntrname"	=>	"mctotother",
				"cntrdescr"	=>	"Total others served",
				"tooltip"	=>	"",
			),
			"mcotherrate" => array (
				"cntrname"	=>	"mcotherrate",
				"cntrdescr"	=>	"",
			),
			"mctotvideo" => Array(
				"cntrname"	=>	"mctotvideo",
				"cntrdescr"	=>	"Total videos served",
				"tooltip"	=>	"",
			),
			"mcvideorate" => array (
				"cntrname"	=>	"mcvideorate",
				"cntrdescr"	=>	"",
			),
			"mctotaudio" => Array(
				"cntrname"	=>	"mctotaudio",
				"cntrdescr"	=>	"Total audios served",
				"tooltip"	=>	"",
			),
			"mcaudiorate" => array (
				"cntrname"	=>	"mcaudiorate",
				"cntrdescr"	=>	"",
			),
			"mcmssmthstrmvid" => Array(
				"cntrname"	=>	"mcmssmthstrmvid",
				"cntrdescr"	=>	"MSsmoothStream videos",
				"tooltip"	=>	"This tells the total number of Microsoft SmoothStreaming videos served by NS",
			),
			"mcmssmthstrmvidrate" => array (
				"cntrname"	=>	"mcmssmthstrmvidrate",
				"cntrdescr"	=>	"",
			),
			"mcmssmthstrvidpl" => Array(
				"cntrname"	=>	"mcmssmthstrvidpl",
				"cntrdescr"	=>	"MSsmoothStream playlist",
				"tooltip"	=>	"This tells the total number of Microsoft SmoothStreaming playlist served by NS",
			),
			"mcmssmthstrvidplrate" => array (
				"cntrname"	=>	"mcmssmthstrvidplrate",
				"cntrdescr"	=>	"",
			),
			"mccaplelivestrmngvid" => Array(
				"cntrname"	=>	"mccaplelivestrmngvid",
				"cntrdescr"	=>	"AppleLive videos",
				"tooltip"	=>	"This tells the total number of AppleLive videos served by NS",
			),
			"mccaplelivestrmngvidrate" => array (
				"cntrname"	=>	"mccaplelivestrmngvidrate",
				"cntrdescr"	=>	"",
			),
			"mccapplelivestrmngvidpl" => Array(
				"cntrname"	=>	"mccapplelivestrmngvidpl",
				"cntrdescr"	=>	"AppleLive playlist",
				"tooltip"	=>	"This tells the total number of AppleLive Playlist served by NS",
			),
			"mccapplelivestrmngvidplrate" => array (
				"cntrname"	=>	"mccapplelivestrmngvidplrate",
				"cntrdescr"	=>	"",
			),
			"mcadtsaudio" => Array(
				"cntrname"	=>	"mcadtsaudio",
				"cntrdescr"	=>	"ADTS audios",
				"tooltip"	=>	"This tells the total number of ADTS audios served by NS",
			),
			"mcadtsaudiorate" => array (
				"cntrname"	=>	"mcadtsaudiorate",
				"cntrdescr"	=>	"",
			),
			"mcaacaudio" => Array(
				"cntrname"	=>	"mcaacaudio",
				"cntrdescr"	=>	"AAC audios",
				"tooltip"	=>	"This tells the total number of AAC audios served by NS",
			),
			"mcaacaudiorate" => array (
				"cntrname"	=>	"mcaacaudiorate",
				"cntrdescr"	=>	"",
			),
			"mcflvvid" => Array(
				"cntrname"	=>	"mcflvvid",
				"cntrdescr"	=>	"FLV videos",
				"tooltip"	=>	"This tells the total number of FLV videos served by NS",
			),
			"mcflvvidrate" => array (
				"cntrname"	=>	"mcflvvidrate",
				"cntrdescr"	=>	"",
			),
			"mcmp4vid" => Array(
				"cntrname"	=>	"mcmp4vid",
				"cntrdescr"	=>	"MP4 videos",
				"tooltip"	=>	"This tells the total number of MP4 videos served by NS",
			),
			"mcmp4vidrate" => array (
				"cntrname"	=>	"mcmp4vidrate",
				"cntrdescr"	=>	"",
			),
			"mc3pvid" => Array(
				"cntrname"	=>	"mc3pvid",
				"cntrdescr"	=>	"3GP videos",
				"tooltip"	=>	"This tells the total number of 3GP videos served by NS",
			),
			"mc3pvidrate" => array (
				"cntrname"	=>	"mc3pvidrate",
				"cntrdescr"	=>	"",
			),
			"mcyoutubedash" => Array(
				"cntrname"	=>	"mcyoutubedash",
				"cntrdescr"	=>	"Youtube DASH",
				"tooltip"	=>	"This tells the total number of YouTube DASH served by NS",
			),
			"mcyoutubedashrate" => array (
				"cntrname"	=>	"mcyoutubedashrate",
				"cntrdescr"	=>	"",
			),
			"mcmssmthstrmvidbytes" => Array(
				"cntrname"	=>	"mcmssmthstrmvidbytes",
				"cntrdescr"	=>	"Total MSsmoothstream video bytes",
				"tooltip"	=>	"This tells the total number of Microsoft SmoothStreaming video bytes served by NS",
			),
			"mcmssmthstrmvidbytesrate" => array (
				"cntrname"	=>	"mcmssmthstrmvidbytesrate",
				"cntrdescr"	=>	"",
			),
			"mcmssmthstrmplvidbytespl" => Array(
				"cntrname"	=>	"mcmssmthstrmplvidbytespl",
				"cntrdescr"	=>	"Total MSsmoothStreamPL bytes",
				"tooltip"	=>	"This tells the total number of Microsoft SmoothStreaming playlist bytes served by NS",
			),
			"mcmssmthstrmplvidbytesplrate" => array (
				"cntrname"	=>	"mcmssmthstrmplvidbytesplrate",
				"cntrdescr"	=>	"",
			),
			"mcapplelivestreamingvidbytes" => Array(
				"cntrname"	=>	"mcapplelivestreamingvidbytes",
				"cntrdescr"	=>	"Total AppleLive video bytes",
				"tooltip"	=>	"This tells the total number of AppleLive video bytes served by NS",
			),
			"mcapplelivestreamingvidbytesrate" => array (
				"cntrname"	=>	"mcapplelivestreamingvidbytesrate",
				"cntrdescr"	=>	"",
			),
			"mcapplelivestreamingplaylistvidbytespl" => Array(
				"cntrname"	=>	"mcapplelivestreamingplaylistvidbytespl",
				"cntrdescr"	=>	"Total AppleLive PL bytes",
				"tooltip"	=>	"This tells the total number of AppleLive Playlist bytes served by NS",
			),
			"mcapplelivestreamingplaylistvidbytesplrate" => array (
				"cntrname"	=>	"mcapplelivestreamingplaylistvidbytesplrate",
				"cntrdescr"	=>	"",
			),
			"mcadtsaudiobytes" => Array(
				"cntrname"	=>	"mcadtsaudiobytes",
				"cntrdescr"	=>	"Total ADTS audio bytes",
				"tooltip"	=>	"This tells the total number of ADTS audio bytes served by NS",
			),
			"mcadtsaudiobytesrate" => array (
				"cntrname"	=>	"mcadtsaudiobytesrate",
				"cntrdescr"	=>	"",
			),
			"mcaacaudiobytes" => Array(
				"cntrname"	=>	"mcaacaudiobytes",
				"cntrdescr"	=>	"Total AAC audio bytes",
				"tooltip"	=>	"This tells the total number of AAC bytes served by NS",
			),
			"mcaacaudiobytesrate" => array (
				"cntrname"	=>	"mcaacaudiobytesrate",
				"cntrdescr"	=>	"",
			),
			"mcflvvidbytes" => Array(
				"cntrname"	=>	"mcflvvidbytes",
				"cntrdescr"	=>	"Total FLV video bytes",
				"tooltip"	=>	"This tells the total number of FLV bytes served by NS",
			),
			"mcflvvidbytesrate" => array (
				"cntrname"	=>	"mcflvvidbytesrate",
				"cntrdescr"	=>	"",
			),
			"mcmp4vidbytes" => Array(
				"cntrname"	=>	"mcmp4vidbytes",
				"cntrdescr"	=>	"Total MP4 video bytes",
				"tooltip"	=>	"This tells the total number of MP4 video bytes served by NS",
			),
			"mcmp4vidbytesrate" => array (
				"cntrname"	=>	"mcmp4vidbytesrate",
				"cntrdescr"	=>	"",
			),
			"mc3gpvidbytes" => Array(
				"cntrname"	=>	"mc3gpvidbytes",
				"cntrdescr"	=>	"Total 3GP video bytes",
				"tooltip"	=>	"This tells the total number of 3GP bytes served by NS",
			),
			"mc3gpvidbytesrate" => array (
				"cntrname"	=>	"mc3gpvidbytesrate",
				"cntrdescr"	=>	"",
			),
			"mcyoutubedashbytes" => Array(
				"cntrname"	=>	"mcyoutubedashbytes",
				"cntrdescr"	=>	"Total YouTube DASH bytes",
				"tooltip"	=>	"This tells the total number of YouTube DASH bytes served by NS",
			),
			"mcyoutubedashbytesrate" => array (
				"cntrname"	=>	"mcyoutubedashbytesrate",
				"cntrdescr"	=>	"",
			),
			"mcandroid" => Array(
				"cntrname"	=>	"mcandroid",
				"cntrdescr"	=>	"Android requests",
				"tooltip"	=>	"Total number of android requests to netscaler",
			),
			"mcandroidrate" => array (
				"cntrname"	=>	"mcandroidrate",
				"cntrdescr"	=>	"",
			),
			"mclaptopdesktp" => Array(
				"cntrname"	=>	"mclaptopdesktp",
				"cntrdescr"	=>	"Laptop/Desktop requests",
				"tooltip"	=>	"Total number of laptop/desktop requests to netscaler",
			),
			"mclaptopdesktprate" => array (
				"cntrname"	=>	"mclaptopdesktprate",
				"cntrdescr"	=>	"",
			),
			"mcios" => Array(
				"cntrname"	=>	"mcios",
				"cntrdescr"	=>	"IOS requests",
				"tooltip"	=>	"Total number of IOS requests to netscaler",
			),
			"mciosrate" => array (
				"cntrname"	=>	"mciosrate",
				"cntrdescr"	=>	"",
			),
			"mcother" => Array(
				"cntrname"	=>	"mcother",
				"cntrdescr"	=>	"Other requests",
				"tooltip"	=>	"Total number of other mobile device requests to netscaler",
			),
			"mcotherrate" => array (
				"cntrname"	=>	"mcotherrate",
				"cntrdescr"	=>	"",
			),
			"mcunidentified" => Array(
				"cntrname"	=>	"mcunidentified",
				"cntrdescr"	=>	"Unidentified requests",
				"tooltip"	=>	"Total number of unidentified requests to netscaler",
			),
			"mcunidentifiedrate" => array (
				"cntrname"	=>	"mcunidentifiedrate",
				"cntrdescr"	=>	"",
			),
			"mcenchls" => Array(
				"cntrname"	=>	"mcenchls",
				"cntrdescr"	=>	"AppleLive streams",
				"tooltip"	=>	"Total number of encrypted AppleLive video streams",
			),
			"mcenchlsrate" => array (
				"cntrname"	=>	"mcenchlsrate",
				"cntrdescr"	=>	"",
			),
			"mcencdash" => Array(
				"cntrname"	=>	"mcencdash",
				"cntrdescr"	=>	"Youtube DASH streams",
				"tooltip"	=>	"Total number of encrypted Youtube DASH video streams",
			),
			"mcencdashrate" => array (
				"cntrname"	=>	"mcencdashrate",
				"cntrdescr"	=>	"",
			),
			"mcencandroid" => Array(
				"cntrname"	=>	"mcencandroid",
				"cntrdescr"	=>	"Android",
				"tooltip"	=>	"Total number of encrypted video streams requested by Android devices",
			),
			"mcencandroidrate" => array (
				"cntrname"	=>	"mcencandroidrate",
				"cntrdescr"	=>	"",
			),
			"mcencios" => Array(
				"cntrname"	=>	"mcencios",
				"cntrdescr"	=>	"IOS",
				"tooltip"	=>	"Total number of encrypted video streams requested by IOS devices",
			),
			"mcenciosrate" => array (
				"cntrname"	=>	"mcenciosrate",
				"cntrdescr"	=>	"",
			),
			"mcencother" => Array(
				"cntrname"	=>	"mcencother",
				"cntrdescr"	=>	"Other devices",
				"tooltip"	=>	"Total number of encrypted video streams requested by non-Android and non-IOS devices",
			),
			"mcencotherrate" => array (
				"cntrname"	=>	"mcencotherrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("mctotvideo","mctotaudio","mctotother","mcencvideo",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"Media classification statistics - summary",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("mctotvideo","mctotaudio","mctotother","mcencvideo",),
			),
			Array(
				"dhID"	=>	3,
				"title"	=>	"Media type statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("mcmssmthstrmvid","mcmssmthstrvidpl","mccaplelivestrmngvid","mccapplelivestrmngvidpl","mcadtsaudio","mcaacaudio","mcflvvid","mcmp4vid","mc3pvid","mcyoutubedash",),
			),
			Array(
				"dhID"	=>	4,
				"title"	=>	"Statistics for media type bytes served",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("mcmssmthstrmvidbytes","mcmssmthstrmplvidbytespl","mcapplelivestreamingvidbytes","mcapplelivestreamingplaylistvidbytespl","mcadtsaudiobytes","mcaacaudiobytes","mcflvvidbytes","mcmp4vidbytes","mc3gpvidbytes","mcyoutubedashbytes",),
			),
			Array(
				"dhID"	=>	5,
				"title"	=>	"Device type statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("mcandroid","mclaptopdesktp","mcios","mcother","mcunidentified",),
			),
			Array(
				"dhID"	=>	6,
				"title"	=>	"Media type statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("mcenchls","mcencdash",),
			),
			Array(
				"dhID"	=>	7,
				"title"	=>	"Device type statistics",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Detail",
				"counters"	=>	array("mcencandroid","mcencios","mcencother",),
			),
		),
),


"statpcpserver" => Array(
	"cmddispname"		=>	"statpcpserver",
	"cmdname"		=>	"statpcpserver",
	"cmddescr"	=>	"     ",
	"args"	=>
		Array (
			"name" => Array(
				"argname"		=>	"name",
				"valtype"		=>	"NS_VALNAME",
				"argdescr"	=>	"",
			),
			"clearstats" => Array(
				"argname"		=>	"clearstats",
				"valtype"		=>	"NS_VALENUM",
				"argdescr"	=>	"",
			),
		),
	"namearg"	=> "name",
	"nameargtype"	=> "NS_VALNAME",
	"attrs"	=>
		Array(
		),
	"cntrs"	=>
		Array(
			"pcptotrequests" => Array(
				"cntrname"	=>	"pcptotrequests",
				"cntrdescr"	=>	"Total PCP Requests received",
				"tooltip"	=>	"PCP Request received.",
			),
			"pcprequestsrate" => array (
				"cntrname"	=>	"pcprequestsrate",
				"cntrdescr"	=>	"",
			),
			"pcptotresponses" => Array(
				"cntrname"	=>	"pcptotresponses",
				"cntrdescr"	=>	"Total Responses sent",
				"tooltip"	=>	"Number PCP responces sent.",
			),
			"pcpresponsesrate" => array (
				"cntrname"	=>	"pcpresponsesrate",
				"cntrdescr"	=>	"",
			),
			"pcptotmaprequests" => Array(
				"cntrname"	=>	"pcptotmaprequests",
				"cntrdescr"	=>	"Total PCP MAP Rquests rcvd",
				"tooltip"	=>	"PCP MAP Requests received.",
			),
			"pcpmaprequestsrate" => array (
				"cntrname"	=>	"pcpmaprequestsrate",
				"cntrdescr"	=>	"",
			),
			"pcptotpeerrequests" => Array(
				"cntrname"	=>	"pcptotpeerrequests",
				"cntrdescr"	=>	"Total PCP PEER Requests rcvd",
				"tooltip"	=>	"PCP PEER Requests received.",
			),
			"pcppeerrequestsrate" => array (
				"cntrname"	=>	"pcppeerrequestsrate",
				"cntrdescr"	=>	"",
			),
			"pcptoterrinrquest" => Array(
				"cntrname"	=>	"pcptoterrinrquest",
				"cntrdescr"	=>	"Total requests with errors",
				"tooltip"	=>	"total PCP request with error.",
			),
			"pcperrinrquestrate" => array (
				"cntrname"	=>	"pcperrinrquestrate",
				"cntrdescr"	=>	"",
			),
			"pcptoterrinres" => Array(
				"cntrname"	=>	"pcptoterrinres",
				"cntrdescr"	=>	"Total responses with errors",
				"tooltip"	=>	"Total PCP responses with errors.",
			),
			"pcperrinresrate" => array (
				"cntrname"	=>	"pcperrinresrate",
				"cntrdescr"	=>	"",
			),
			"pcptotunsuppvers" => Array(
				"cntrname"	=>	"pcptotunsuppvers",
				"cntrdescr"	=>	"Total unsupported version request",
				"tooltip"	=>	"PCP request with unsupported version.",
			),
			"pcpunsuppversrate" => array (
				"cntrname"	=>	"pcpunsuppversrate",
				"cntrdescr"	=>	"",
			),
			"pcptotmalformedreq" => Array(
				"cntrname"	=>	"pcptotmalformedreq",
				"cntrdescr"	=>	"Total Malformed Requests",
				"tooltip"	=>	"total PCP request having malformed PCP packets.",
			),
			"pcpmalformedreqrate" => array (
				"cntrname"	=>	"pcpmalformedreqrate",
				"cntrdescr"	=>	"",
			),
			"pcptotunsuppopcode" => Array(
				"cntrname"	=>	"pcptotunsuppopcode",
				"cntrdescr"	=>	"Total Unsupported OPCODES",
				"tooltip"	=>	"total Unsupproted OPCODES received Requests.",
			),
			"pcpunsuppopcoderate" => array (
				"cntrname"	=>	"pcpunsuppopcoderate",
				"cntrdescr"	=>	"",
			),
			"pcptotunsuppoption" => Array(
				"cntrname"	=>	"pcptotunsuppoption",
				"cntrdescr"	=>	"Total Unsupported OPTIONS",
				"tooltip"	=>	"total Unsupproted OPTIONS received in requests.",
			),
			"pcpunsuppoptionrate" => array (
				"cntrname"	=>	"pcpunsuppoptionrate",
				"cntrdescr"	=>	"",
			),
			"pcptotmalformedoption" => Array(
				"cntrname"	=>	"pcptotmalformedoption",
				"cntrdescr"	=>	"Total malformed OPTIONS",
				"tooltip"	=>	"total malformed OPTIONS received in requests.",
			),
			"pcpmalformedoptionrate" => array (
				"cntrname"	=>	"pcpmalformedoptionrate",
				"cntrdescr"	=>	"",
			),
			"pcptotnetfailure" => Array(
				"cntrname"	=>	"pcptotnetfailure",
				"cntrdescr"	=>	"Total Network Failures",
				"tooltip"	=>	"Total Network Failures.",
			),
			"pcpnetfailurerate" => array (
				"cntrname"	=>	"pcpnetfailurerate",
				"cntrdescr"	=>	"",
			),
			"pcptotnoresources" => Array(
				"cntrname"	=>	"pcptotnoresources",
				"cntrdescr"	=>	"Total No resources reponse",
				"tooltip"	=>	"no resources",
			),
			"pcpnoresourcesrate" => array (
				"cntrname"	=>	"pcpnoresourcesrate",
				"cntrdescr"	=>	"",
			),
			"pcptotunsupportedprotocol" => Array(
				"cntrname"	=>	"pcptotunsupportedprotocol",
				"cntrdescr"	=>	"Total Unsupported Protocols requests",
				"tooltip"	=>	"Total Unsupported Protocols requests.",
			),
			"pcpunsupportedprotocolrate" => array (
				"cntrname"	=>	"pcpunsupportedprotocolrate",
				"cntrdescr"	=>	"",
			),
			"pcptotuserexqouta" => Array(
				"cntrname"	=>	"pcptotuserexqouta",
				"cntrdescr"	=>	"Total User Ex Qouta",
				"tooltip"	=>	"Total user ex quota.",
			),
			"pcpuserexqoutarate" => array (
				"cntrname"	=>	"pcpuserexqoutarate",
				"cntrdescr"	=>	"",
			),
			"pcptotnoexternal" => Array(
				"cntrname"	=>	"pcptotnoexternal",
				"cntrdescr"	=>	"Total cannot provide extrnl resp",
				"tooltip"	=>	"Total responses with opcode can not provide external.",
			),
			"pcpnoexternalrate" => array (
				"cntrname"	=>	"pcpnoexternalrate",
				"cntrdescr"	=>	"",
			),
			"pcptotaddrmismatch" => Array(
				"cntrname"	=>	"pcptotaddrmismatch",
				"cntrdescr"	=>	"Total address mismatch rqsts",
				"tooltip"	=>	"Total address mismatch.",
			),
			"pcpaddrmismatchrate" => array (
				"cntrname"	=>	"pcpaddrmismatchrate",
				"cntrdescr"	=>	"",
			),
			"pcptotexcesspeers" => Array(
				"cntrname"	=>	"pcptotexcesspeers",
				"cntrdescr"	=>	"Total Excess Remote Peers",
				"tooltip"	=>	"Total responses with opcode excessive remote peers.",
			),
			"pcpexcesspeersrate" => array (
				"cntrname"	=>	"pcpexcesspeersrate",
				"cntrdescr"	=>	"",
			),
		),
	"displayhandler"	=>
		Array(
			Array(
				"dhID"	=>	1,
				"title"	=>	"",
				"header"	=>	array("","Rate (/s)","Total",),
				"format"	=>	"ANDRATE",
				"flags"	=>	"ANDRATE",
				"dispflag"	=>	"Byname",
				"counters"	=>	array("pcptotrequests","pcptotresponses","pcptotmaprequests","pcptotpeerrequests","pcptoterrinrquest","pcptoterrinres","pcptotunsuppvers","pcptotmalformedreq","pcptotunsuppopcode","pcptotunsuppoption","pcptotmalformedoption","pcptotnetfailure","pcptotnoresources","pcptotunsupportedprotocol","pcptotuserexqouta","pcptotnoexternal","pcptotaddrmismatch","pcptotexcesspeers",),
			),
			Array(
				"dhID"	=>	2,
				"title"	=>	"PCP Statistics",
				"header"	=>	array(),
				"format"	=>	"TABULAR",
				"dispflag"	=>	"Summary",
				"counters"	=>	array("pcptotrequests","pcptotresponses","pcptoterrinrquest","pcptoterrinres",),
			),
		),
),


		);
 
			 public function get_command($funname) {
			      if(!isset($this->cmds[$funname]))
			      	return false;
			      return $this->cmds[$funname]; 
			 }
	    }
?>