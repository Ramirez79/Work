<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class stapp_definitions
{
    var $groups;
    public function __construct()
    {
        $this->groups = array(
            CS_GROUP =>  array(
                "cmdname"           =>  CS_GROUP,
                "namearg"           =>  CS_ARG_NAME,
                "nameargtype"       =>  CS_ARG_TYPE,
                "title"             =>  PUBLIC_END_POINTS,
                "tooltip_suffix"    =>  PUBLIC_END_POINT,
                "format_dispname"   =>  false,
                "summary_counters"  =>  array(
                    array(
                        "cntrname"      =>  "name",
                        "cntrdescr"     =>  "Name",
                        "tooltip"       =>  "Name of ",
                    ),
                    array(
                        "cntrname"      =>  "primaryipaddress",
                        "cntrdescr"     =>  "IP address",
                        "tooltip"       =>  "IP address, port and protocol of ",
                        "type"          =>  "VALIP_PORT_PROTOCOL",
                        "format"        =>  true,
                        "port_counter"  =>  "primaryport",
                        "type_counter"  =>  "type",
                    ),
                    array(
                        "cntrname"      =>  "state",
                        "cntrdescr"     =>  "State",
                        "tooltip"       =>  "State of ",
                        "type"          =>  "ENUMSVRSTATE",
                    ),
                    array(
                        "cntrname"      =>  "requestsrate",
                        "cntrdescr"     =>  "Requests (/s)",
                        "tooltip"       =>  "Rate of requests received on ",
                    ),
                    array(
                        "cntrname"      =>  "responsesrate",
                        "cntrdescr"     =>  "Responses (/s)",
                        "tooltip"       =>  "Rate of responses received on ",
                    ),
                    array(
                        "cntrname"      =>  "requestbytesrate",
                        "cntrdescr"     =>  "Request bytes (/s)",
                        "tooltip"       =>  "Rate of request bytes received on ",
                    ),
                    array(
                        "cntrname"      =>  "responsebytesrate",
                        "cntrdescr"     =>  "Response bytes (/s)",
                        "tooltip"       =>  "Rate of response bytes received on ",
                    )
                ),
                "detail_counters"   =>  array(
                    "totalrequests"     =>  array(
                        "cntrname"      =>  "totalrequests",
                        "cntrdescr"     =>  "Requests",
                        "tooltip"       =>  "Number of requests received",
                    ),
                    "totalrequestbytes" =>  array(
                        "cntrname"      =>  "totalrequestbytes",
                        "cntrdescr"     =>  "Request bytes",
                        "tooltip"       =>  "Number of request bytes received",
                    ),
                    "totalresponses"    =>  array(
                        "cntrname"      =>  "totalresponses",
                        "cntrdescr"     =>  "Responses",
                        "tooltip"       =>  "Number of responses received",
                    ),
                   "totalresponsebytes" =>  array(
                        "cntrname"      =>  "totalresponsebytes",
                        "cntrdescr"     =>  "Response bytes",
                        "tooltip"       =>  "Number of response bytes received",
                    ),
                    "sothreshold"     =>  array(
                        "cntrname"      =>  "sothreshold",
                        "cntrdescr"     =>  "Spill over threshold",
                        "tooltip"       =>  "Spill over threshold set on " . PUBLIC_END_POINT,
                    ),
                    "totspillovers" =>  array(
                        "cntrname"      =>  "totspillovers",
                        "cntrdescr"     =>  "Spill over hits",
                        "tooltip"       =>  "Number of times " . PUBLIC_END_POINT . " experienced spill over",
                    ),
                    "deferredreq" =>  array(
                        "cntrname"      =>  "deferredreq",
                        "cntrdescr"     =>  "Deferred requests",
                        "tooltip"       =>  "Number of deferred requests on " . PUBLIC_END_POINT,
                    ),
                ),
                "detail_display_handlers"    =>  array(
                    array(
                        "dhID"      =>  1,
                        "title"     =>  "",
                        "header"    =>  array("&nbsp;", "Total"),
                        "format"    =>  "1C",
                        "colspan"   =>  4,
                        "counters"  =>  array("totalrequests", "totalresponses", "sothreshold", "totspillovers"),
                    ),
                    array(
                        "dhID"      =>  2,
                        "title"     =>  "",
                        "header"    =>  array("&nbsp;", "Total"),
                        "format"    =>  "1C",
                        "colspan"   =>  3,
                        "counters"  =>  array("totalrequestbytes", "totalresponsebytes", "deferredreq"),
                    ),
                )
            ),
            LB_GROUP =>  array(
                "cmdname"           =>  LB_GROUP,
                "namearg"           =>  LB_ARG_NAME,
                "nameargtype"       =>  LB_ARG_TYPE,
                "title"             =>  APP_UNITS,
                "tooltip_suffix"    =>  APP_UNIT,
                "format_dispname"   =>  true,
                "summary_counters"  =>  array(
                    array(
                        "cntrname"      =>  "name",
                        "cntrdescr"     =>  "Name",
                        "tooltip"       =>  "Name of ",
                    ),
                    array(
                        "cntrname"      =>  "state",
                        "cntrdescr"     =>  "State",
                        "tooltip"       =>  "State of ",
                        "type"          =>  "ENUMSVRSTATE",
                    ),
                    array(
                        "cntrname"      =>  "vslbhealth",
                        "cntrdescr"     =>  "Health (%)",
                        "tooltip"       =>  "Health (percentage of UP services) of  ",
                    ),
                    array(
                        "cntrname"      =>  "hitsrate",
                        "cntrdescr"     =>  "Hits (/s)",
                        "tooltip"       =>  "Rate of hits on ",
                    ),
                    array(
                        "cntrname"      =>  "requestsrate",
                        "cntrdescr"     =>  "Requests (/s)",
                        "tooltip"       =>  "Rate of requests received on ",
                    ),
                    array(
                        "cntrname"      =>  "responsesrate",
                        "cntrdescr"     =>  "Responses (/s)",
                        "tooltip"       =>  "Rate of responses received on ",
                    ),
                    array(
                        "cntrname"      =>  "curclntconnections",
                        "cntrdescr"     =>  "Client connections",
                        "tooltip"       =>  "Number of current client connections to ",
                    ),
                    array(
                        "cntrname"      =>  "cursrvrconnections",
                        "cntrdescr"     =>  "Server connections",
                        "tooltip"       =>  "Number of current server connections from ",
                    )
                ),
                "detail_counters"  =>  array(
                    "tothits"           =>  array(
                        "cntrname"      =>  "tothits",
                        "cntrdescr"     =>  "Hits",
                        "tooltip"       =>  "Number of hits",
                    ),
                    "totalrequests"     =>  array(
                        "cntrname"      =>  "totalrequests",
                        "cntrdescr"     =>  "Requests",
                        "tooltip"       =>  "Number of requests received",
                    ),
                    "totalresponses"    =>  array(
                        "cntrname"      =>  "totalresponses",
                        "cntrdescr"     =>  "Responses",
                        "tooltip"       =>  "Number of responses received",
                    ),
                    "establishedconn"   =>  array(
                        "cntrname"      =>  "establishedconn",
                        "cntrdescr"     =>  "Established client connections",
                        "tooltip"       =>  "Number of current client connections in established state",
                    ),
                    "sothreshold"     =>  array(
                        "cntrname"      =>  "sothreshold",
                        "cntrdescr"     =>  "Spill over threshold",
                        "tooltip"       =>  "Spill over threshold set on " . APP_UNIT,
                    ),
                    "totspillovers"       =>  array(
                        "cntrname"      =>  "totspillovers",
                        "cntrdescr"     =>  "Spill over hits",
                        "tooltip"       =>  "Number of times " . APP_UNIT . " experienced spill over",
                    ),
                    "totalrequestbytes" =>  array(
                        "cntrname"      =>  "totalrequestbytes",
                        "cntrdescr"     =>  "Request bytes",
                        "tooltip"       =>  "Number of request bytes received",
                    ),
                    "requestbytesrate"  =>  array(
                        "cntrname"      =>  "requestbytesrate",
                        "cntrdescr"     =>  "Request bytes (/s)",
                        "tooltip"       =>  "Rate of request bytes received",
                    ),
                    "totalresponsebytes"=>  array(
                        "cntrname"      =>  "totalresponsebytes",
                        "cntrdescr"     =>  "Response bytes",
                        "tooltip"       =>  "Number of response bytes received",
                    ),
                    "responsebytesrate"  =>  array(
                        "cntrname"      =>  "responsebytesrate",
                        "cntrdescr"     =>  "Response bytes (/s)",
                        "tooltip"       =>  "Rate of response bytes received",
                    ),
                    "totalpktsrecvd"    =>  array(
                        "cntrname"      =>  "totalpktsrecvd",
                        "cntrdescr"     =>  "Packets received",
                        "tooltip"       =>  "Number of packets received",
                    ),
                    "pktsrecvdrate"     =>  array(
                        "cntrname"      =>  "pktsrecvdrate",
                        "cntrdescr"     =>  "Packets received (/s)",
                        "tooltip"       =>  "Rate of packets received",
                    ),
                    "totalpktssent"     =>  array(
                        "cntrname"      =>  "totalpktssent",
                        "cntrdescr"     =>  "Packets sent",
                        "tooltip"       =>  "Number of packets sent",
                    ),
                    "pktssentrate"      =>  array(
                        "cntrname"      =>  "pktssentrate",
                        "cntrdescr"     =>  "Packets sent (/s)",
                        "tooltip"       =>  "Rate of packets sent",
                    ),
                    "deferredreq" =>  array(
                        "cntrname"      =>  "deferredreq",
                        "cntrdescr"     =>  "Deferred requests",
                        "tooltip"       =>  "Number of deferred requests on " . APP_UNIT,
                    ),
                    "deferredreqrate"   =>  array(
                        "cntrname"      =>  "deferredreqrate",
                        "cntrdescr"     =>  "Deferred requests (/s)",
                        "tooltip"       =>  "Rate of deferred requests on " . APP_UNIT,
                    ),
                ),
                "detail_display_handlers"   =>  array(
                    array(
                        "dhID"      =>  1,
                        "title"     =>  "",
                        "header"    =>  array("&nbsp;", "Total"),
                        "format"    =>  "1C",
                        "colspan"   =>  6,
                        "counters"  =>  array("tothits", "totalrequests", "totalresponses", "establishedconn", "sothreshold", "totspillovers"),
                    ),
                    array(
                        "dhID"      =>  2,
                        "title"     =>  "",
                        "header"    =>  array("&nbsp;", "Rate (/s)", "Total"),
                        "format"    =>  "ANDRATE",
                        "colspan"   =>  3,
                        "counters"  =>  array("totalrequestbytes", "totalresponsebytes", "totalpktsrecvd", "totalpktssent", "deferredreq"),
                    ),
                )
            ),
            SVC_GROUP =>  array(
                "cmdname"           =>  SVC_GROUP,
                "enable"            =>  "1",
                "namearg"           =>  SVC_ARG_NAME,
                "nameargtype"       =>  SVC_ARG_TYPE,
                "title"             =>  BOUND_SERVICES,
                "tooltip_suffix"    =>  BOUND_SERVICE,
                "format_dispname"   =>  false,
                "summary_counters"  =>  array(
                    array(
                        "cntrname"      =>  "name",
                        "cntrdescr"     =>  "Name",
                        "tooltip"       =>  "Name of ",
                    ),
                    array(
                        "cntrname"      =>  "primaryipaddress",
                        "cntrdescr"     =>  "IP address",
                        "tooltip"       =>  "IP address, port and protocol of ",
                        "type"          =>  "VALIP_PORT_PROTOCOL",
                        "format"        =>  true,
                        "port_counter"  =>  "primaryport",
                        "type_counter"  =>  "servicetype",
                    ),
                    array(
                        "cntrname"      =>  "state",
                        "cntrdescr"     =>  "State",
                        "tooltip"       =>  "State of ",
                        "type"          =>  "ENUMSVRSTATE",
                    ),
                    array(
                        "cntrname"      =>  "vsvrservicehitsrate",
                        "cntrdescr"     =>  "Hits (/s)",
                        "tooltip"       =>  "Rate of hits on ",
                    ),
                    array(
                        "cntrname"      =>  "requestsrate",
                        "cntrdescr"     =>  "Requests (/s)",
                        "tooltip"       =>  "Rate of requests received on ",
                    ),
                    array(
                        "cntrname"      =>  "responsesrate",
                        "cntrdescr"     =>  "Responses (/s)",
                        "tooltip"       =>  "Rate of responses received on ",
                    ),
                    array(
                        "cntrname"      =>  "throughput",
                        "cntrdescr"     =>  "Throughput (Mbps)",
                        "tooltip"       =>  "Rate of bytes received/sent on ",
                    )
                ),
                "detail_counters"  =>  array(
                    "totalrequests"     =>  array(
                        "cntrname"      =>  "totalrequests",
                        "cntrdescr"     =>  "Requests",
                        "tooltip"       =>  "Number of requests received",
                    ),
                    "totalresponses"    =>  array(
                        "cntrname"      =>  "totalresponses",
                        "cntrdescr"     =>  "Responses",
                        "tooltip"       =>  "Number of responses received",
                    ),
                    "totalrequestbytes" =>  array(
                        "cntrname"      =>  "totalrequestbytes",
                        "cntrdescr"     =>  "Request bytes",
                        "tooltip"       =>  "Number of request bytes received",
                    ),
                    "requestbytesrate"  =>  array(
                        "cntrname"      =>  "requestbytesrate",
                        "cntrdescr"     =>  "Request bytes (/s)",
                        "tooltip"       =>  "Rate of request bytes received",
                    ),
                    "totalresponsebytes"=>  array(
                        "cntrname"      =>  "totalresponsebytes",
                        "cntrdescr"     =>  "Response bytes",
                        "tooltip"       =>  "Number of response bytes received",
                    ),
                    "responsebytesrate" =>  array(
                        "cntrname"      =>  "responsebytesrate",
                        "cntrdescr"     =>  "Response bytes (/s)",
                        "tooltip"       =>  "Rate of response bytes received",
                    ),
                    "curclntconnections"=>  array(
                        "cntrname"      =>  "curclntconnections",
                        "cntrdescr"     =>  "Client connections",
                        "tooltip"       =>  "Number of current client connections",
                    ),
                    "surgecount"        =>  array(
                        "cntrname"      =>  "surgecount",
                        "cntrdescr"     =>  "Requests in surge queue",
                        "tooltip"       =>  "Number of requests in the surge queue",
                    ),
                    "cursrvrconnections"=>  array(
                        "cntrname"      =>  "cursrvrconnections",
                        "cntrdescr"     =>  "Server connections",
                        "tooltip"       =>  "Number of current server connections",
                    ),
                    "svrestablishedconn"=>  array(
                        "cntrname"      =>  "svrestablishedconn",
                        "cntrdescr"     =>  "Established server connections",
                        "tooltip"       =>  "Number of current server connections in established state",
                    ),
                    "curreusepool"      =>  array(
                        "cntrname"      =>  "curreusepool",
                        "cntrdescr"     =>  "Connections in reuse pool",
                        "tooltip"       =>  "Number of requests in idle queue/reuse pool",
                    ),
                    "maxclients"        =>  array(
                        "cntrname"      =>  "maxclients",
                        "cntrdescr"     =>  "Maximum server connections",
                        "tooltip"       =>  "The maximum number of open connections allowed",
                    ),
                    "avgsvrttfb"        =>  array(
                        "cntrname"      =>  "avgsvrttfb",
                        "cntrdescr"     =>  "Average server TTFB",
                        "tooltip"       =>  "The average TTFB between the Netscaler and the server",
                    ),
                    "curload"           =>  array(
                        "cntrname"      =>  "curload",
                        "cntrdescr"     =>  "Current load",
                        "tooltip"       =>  "The load on the " . BOUND_SERVICE . " that is calculated from bound load based monitor",
                    ),
                    "vsvrservicehits"   =>  array(
                        "cntrname"      =>  "vsvrservicehits",
                        "cntrdescr"     =>  "Hits",
                        "tooltip"       =>  "Number of hits",
                    ),
                    "activetransactions"=>  array(
                        "cntrname"      =>  "activetransactions",
                        "cntrdescr"     =>  "Active transactions",
                        "tooltip"       =>  "Number of active transactions handled by this " . BOUND_SERVICE . " (includes the surgeQ count also)",
                    )
                ),
                "detail_display_handlers"   =>  array(
                    array(
                        "dhID"      =>  1,
                        "title"     =>  "",
                        "header"    =>  array("&nbsp;", "Total"),
                        "format"    =>  "1C",
                        "colspan"   =>  4,
                        "counters"  =>  array("vsvrservicehits", "totalrequests", "totalresponses", "curclntconnections", "cursrvrconnections", "svrestablishedconn", "maxclients"),
                    ),
                    array(
                        "dhID"      =>  2,
                        "title"     =>  "",
                        "group"     =>  "g1",
                        "header"    =>  array("&nbsp;", "Rate (/s)", "Total"),
                        "format"    =>  "ANDRATE",
                        "colspan"   =>  4,
                        "counters"  =>  array("totalrequestbytes", "totalresponsebytes"),
                    ),
                    array(
                        "dhID"      =>  3,
                        "title"     =>  "",
                        "header"    =>  array("&nbsp;", "Total"),
                        "format"    =>  "1C",
                        "colspan"   =>  3,
                        "counters"  =>  array("surgecount", "curreusepool", "avgsvrttfb", "curload", "activetransactions"),
                    )
                )
            )
        );
    }

    function get_group($group_name)
    {
        if(!isset($this->groups[$group_name]))
            return null;
        return $this->groups[$group_name];
    }
}

//Denotes CS VIPs (Public endpoints), LB VIPS (Application units) etc.
//Carries the selected entities, result of command and group definition
//from controller to view
class stapp_unit
{
    private $entities;
    private $result;
    private $group_definition;

    public function __construct($entities, $result, $group_definition)
    {
        $this->entities = $entities;
        $this->result = $result;
        $this->group_definition = $group_definition;
    }

    public function get_entities()
    {
        return $this->entities;
    }

    public function get_result()
    {
        return $this->result;
    }

    public function get_group_definition()
    {
        return $this->group_definition;
    }
}
?>