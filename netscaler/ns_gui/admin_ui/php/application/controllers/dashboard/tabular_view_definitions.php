<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
class tabular_view_definitions
{
    private $tabular_views = array(
        "statappfwprofile" => array(
            "cntrs" => array("appfirewallrequestsperprofile", "appfirewallrequestsperprofilerate", "appfirewallreqbytesperprofile", "appfirewallreqbytesperprofilerate", "appfirewallresponsesperprofile", "appfirewallresponsesperprofilerate", "appfirewallresbytesperprofile", "appfirewallresbytesperprofilerate", "appfirewallabortsperprofile", "appfirewallabortsperprofilerate", "appfirewallredirectsperprofile", "appfirewallredirectsperprofilerate", "appfirewalllongavgresptimeperprofile", "appfirewallshortavgresptimeperprofile", "appfirewallviolstarturlperprofile", "appfirewallviolstarturlperprofilerate", "appfirewallvioldenyurlperprofile", "appfirewallvioldenyurlperprofilerate", "appfirewallviolrefererheaderperprofile", "appfirewallviolrefererheaderperprofilerate", "appfirewallviolbufferoverflowperprofile", "appfirewallviolbufferoverflowperprofilerate", "appfirewallviolcookieperprofile", "appfirewallviolcookieperprofilerate", "appfirewallviolcsrftagperprofile", "appfirewallviolcsrftagperprofilerate", "appfirewallviolxssperprofile", "appfirewallviolxssperprofilerate", "appfirewallviolsqlperprofile", "appfirewallviolsqlperprofilerate", "appfirewallviolfieldformatperprofile", "appfirewallviolfieldformatperprofilerate", "appfirewallviolfieldconsistencyperprofile", "appfirewallviolfieldconsistencyperprofilerate", "appfirewallviolcreditcardperprofile", "appfirewallviolcreditcardperprofilerate", "appfirewallviolsafeobjectperprofile", "appfirewallviolsafeobjectperprofilerate", "appfirewallviolsignatureperprofile", "appfirewallviolsignatureperprofilerate", "appfirewallviolwellformednessviolationsperprofile", "appfirewallviolwellformednessviolationsperprofilerate", "appfirewallviolxdosviolationsperprofile", "appfirewallviolxdosviolationsperprofilerate", "appfirewallviolmsgvalviolationsperprofile", "appfirewallviolmsgvalviolationsperprofilerate", "appfirewallviolwsiviolationsperprofile", "appfirewallviolwsiviolationsperprofilerate", "appfirewallviolxmlsqlviolationsperprofile", "appfirewallviolxmlsqlviolationsperprofilerate", "appfirewallviolxmlxssviolationsperprofile", "appfirewallviolxmlxssviolationsperprofilerate", "appfirewallviolxmlattachmentviolationsperprofile", "appfirewallviolxmlattachmentviolationsperprofilerate", "appfirewallviolxmlsoapfaultviolationsperprofile", "appfirewallviolxmlsoapfaultviolationsperprofilerate", "appfirewallviolxmlgenericviolationsperprofile", "appfirewallviolxmlgenericviolationsperprofilerate", "appfirewalltotalviolperprofile", "appfirewallviolperprofilerate", "appfirewallret4xxperprofile", "appfirewallret4xxperprofilerate", "appfirewallret5xxperprofile", "appfirewallret5xxperprofilerate"),
            "properties" => array("appfirewallrequestsperprofile" => array("title" => "Requests"),
                                  "appfirewallrequestsperprofilerate" => array("title" => "Requests (Rate)"),
                                  "appfirewallresponsesperprofile" => array("title" => "Responses"),
                                  "appfirewallresponsesperprofilerate" => array("title" => "Responses (Rate)"),
                                  "appfirewallabortsperprofile" => array("title" => "Aborts"),
                                  "appfirewallabortsperprofilerate" => array("title" => "Aborts (Rate)"),
                                  "appfirewallredirectsperprofile" => array("title" => "Redirects"),
                                  "appfirewallredirectsperprofilerate" => array("title" => "Redirects (Rate)"),
                                  "appfirewallviolstarturlperprofile" => array("title" => "Start URL"),
                                  "appfirewallviolstarturlperprofilerate" => array("title" => "Start URL (Rate)"),
                                  "appfirewallvioldenyurlperprofile" => array("title" => "Deny URL"),
                                  "appfirewallvioldenyurlperprofilerate" => array("title" => "Deny URL (Rate)"),
                                  "appfirewallviolrefererheaderperprofile" => array("title" => "Referer header"),
                                  "appfirewallviolrefererheaderperprofilerate" => array("title" => "Referer header (Rate)"),
                                  "appfirewallviolbufferoverflowperprofile" => array("title" => "Buffer overflow"),
                                  "appfirewallviolbufferoverflowperprofilerate" => array("title" => "Buffer overflow (Rate)"),
                                  "appfirewallviolcookieperprofile" => array("title" => "Cookie consistency"),
                                  "appfirewallviolcookieperprofilerate" => array("title" => "Cookie consistency (Rate)"),
                                  "appfirewallviolfieldformatperprofile" => array("title" => "Field format"),
                                  "appfirewallviolfieldformatperprofilerate" => array("title" => "Field format (Rate)"),
                                  "appfirewallviolfieldconsistencyperprofile" => array("title" => "Field consistency"),
                                  "appfirewallviolfieldconsistencyperprofilerate" => array("title" => "Field consistency (Rate)"),
                                  "appfirewallviolcreditcardperprofile" => array("title" => "Credit card"),
                                  "appfirewallviolcreditcardperprofilerate" => array("title" => "Credit card (Rate)"),
                                  "appfirewallviolsafeobjectperprofile" => array("title" => "Safe object"),
                                  "appfirewallviolsafeobjectperprofilerate" => array("title" => "Safe object (Rate)"))
        ),
        "statauthenticationvserver" => array(
            "cntrs" => array("primaryipaddress", "primaryport", "state", "type", "totalrequests", "requestsrate", "totalresponses", "responsesrate", "totalrequestbytes", "requestbytesrate", "totalresponsebytes", "responsebytesrate"),
            "properties" => array("primaryipaddress" => array("title" => "IP Address"),
                                  "type" => array("title" => "Protocol"))
        ),
        "statcrvserver"  => array(
            "cntrs" => array("primaryipaddress", "primaryport", "state", "type", "totalrequests", "requestsrate", "totalresponses", "responsesrate", "totalrequestbytes", "requestbytesrate", "totalresponsebytes", "responsebytesrate"),
            "properties" => array("primaryipaddress" => array("title" => "IP Address"),
                                  "type" => array("title" => "Protocol"))
        ),
        "statclusternode"  => array(
            "cntrs" => array("clnodeip", "clmasterstate", "clnodeeffectivehealth", "clsyncstate", "cltothbrx", "cltothbtx", "clptpstate", "clptprx", "clptptx", "nnmcurconn", "nnmtotconnrx", "nnmtotconntx", "nnmerrmsend")
        ),
        "statcsvserver"  => array(
            "cntrs" => array("primaryipaddress", "primaryport", "state", "type", "tothits", "hitsrate", "totalrequests", "requestsrate", "totalresponses", "responsesrate", "totalrequestbytes", "requestbytesrate", "totalresponsebytes", "responsebytesrate", "totalpktsrecvd", "pktsrecvdrate", "totalpktssent", "pktssentrate", "curclntconnections", "cursrvrconnections", "sothreshold", "totspillovers", "labelledconn", "pushlabel", "deferredreq", "deferredreqrate", "invalidrequestresponse", "invalidrequestresponsedropped"),
            "properties" => array("primaryipaddress" => array("title" => "IP Address"),
                                  "type" => array("title" => "Protocol"))
        ),
        "statdnsrecords"  => array(
            "cntrs" => array("dnstotresponses", "dnstotrequests", "dnscurrecords", "dnscurentries", "dnstotentries", "dnstotupdates", "dnstoterrlimits", "dnstoterrrespform", "dnstoterraliasex", "dnstoterrnodomains")
        ),
        "statgslbdomain"  => array(
            "cntrs" => array("dnstotalqueries", "dnsqueriesrate", "gslbdnsrectype"),
            "properties" => array("dnstotalqueries" => array("title" => "Hits"),
                                  "dnsqueriesrate" => array("title" => "Hits (Rate)"))
        ),
        "statgslbservice"  => array(
            "cntrs" => array("primaryipaddress", "primaryport", "servicetype", "state", "totalrequestbytes", "requestbytesrate", "totalresponsebytes", "responsebytesrate", "establishedconn", "curload"),
            "properties" => array("primaryipaddress" => array("title" => "IP Address"),
                                  "servicetype" => array("title" => "Protocol"))
        ),
        "statgslbsite"  => array(
            "cntrs" => array("siteipstr", "sitepublicipstr", "sitetype", "sitemetricmepstatus", "nwmetricmepstatus", "sitemetricexchange", "nwmetricexchange", "persexchange", "sitetotalrequestbytes", "siterequestbytesrate", "sitetotalresponsebytes", "siteresponsebytesrate"),
            "properties" => array("siteipstr" => array("title" => "Private IP"),
                                  "sitepublicipstr" => array("title" => "Public IP"),
                                  "sitetype" => array("title" => "Site Type"),
                                  "sitemetricmepstatus" => array("title" => "Metric Exchange State"),
                                  "nwmetricmepstatus" => array("title" => "Network Metric Exchange State"))
        ),
        "statgslbvserver"  => array(
            "cntrs" => array("type", "state", "vslbhealth", "tothits", "hitsrate", "totalrequestbytes", "requestbytesrate", "totalresponsebytes", "responsebytesrate", "establishedconn", "sothreshold", "totspillovers"),
            "properties" => array("type" => array("title" => "Protocol"),
                                  "vslbhealth" => array("title" => "Health"),
                                  "tothits" => array("title" => "Hits"),
                                  "hitsrate" => array("title" => "Hits (Rate)"))
        ),
        "statdospolicy"  => array(
            "cntrs" => array("dosphysicalserviceip", "dosphysicalserviceport", "doscurrentqueuesize", "doscurrentcltdetectrate", "dostotjssent", "dosjssentrate", "dostotjsrefused", "dosjsrefusedrate", "dostotvalidclients", "dosvalidclientsrate", "dostotjsbytessent", "dosjsbytessentrate", "dostotnongetpostrequests", "dosnongetpostrequestsrate"),
            "properties" => array("dosphysicalserviceip" => array("title" => "Service IP"),
                                  "dosphysicalserviceport" => array("title" => "Service Port"))
        ),
        "statinterface"  => array(
            "cntrs" => array("curintfstate", "totrxbytes", "rxbytesrate", "tottxbytes", "txbytesrate", "totrxpkts", "rxpktsrate", "tottxpkts", "txpktsrate", "nictotmulticastpkts", "nicmulticastpktsrate", "totnetscalerpkts", "netscalerpktsrate", "rxlacpdu", "rxlacpdurate", "txlacpdu", "txlacpdurate")
        ),
        "statlldp"  =>  array(
            "cntrs" =>  array("rxportframestotal", "rxportbytestotal","txportframestotal","txportbytestotal","rxportframeserrors","rxporttlvsdiscardedtotal","rxporttlvsunrecognizedtotal")
        ),
        "statvxlan"  =>  array(
            "cntrs" =>  array("vxlantotrxpkts","vxlantotrxbytes","vxlantottxpkts","vxlantottxbytes")
        ),
        "statlbvserver"  => array(
            "cntrs" => array("primaryipaddress", "primaryport", "state", "type", "vslbhealth", "tothits", "hitsrate", "totalrequests", "requestsrate", "totalresponses", "responsesrate", "totalrequestbytes", "requestbytesrate", "totalresponsebytes", "responsebytesrate", "totalpktsrecvd", "pktsrecvdrate", "totalpktssent", "pktssentrate", "curclntconnections", "establishedconn", "cursrvrconnections", "sothreshold", "totspillovers", "labelledconn", "pushlabel", "deferredreq", "deferredreqrate", "invalidrequestresponse", "invalidrequestresponsedropped"),
            "properties" => array("primaryipaddress" => array("title" => "IP Address"),
                                  "type" => array("title" => "Protocol"),
                                  "vslbhealth" => array("title" => "Health"),
                                  "tothits" => array("title" => "Hits"),
                                  "hitsrate" => array("title" => "Hits (Rate)"))
        ),
        "statpqpolicy"  => array(
            "cntrs" => array("pqvserverip", "pqvserverport", "pqqdepth", "pqcurrentclientconnections", "pqtotclientconnections", "pqclientconnectionsrate", "pqdropped", "pqdroppedrate", "totclienttransactions", "clienttransactionsrate", "pqtotqueuedepth", "pqqueuedepthrate", "pqavgclienttransactiontimems", "pqtotavgqueuewaittime"),
            "properties" => array("pqvserverip" => array("title" => "Virtual Server IP"),
                                  "pqvserverport" => array("title" => "Virtual Server Port"))
        ),
        "statrnatip"  => array(
            "cntrs" => array("iprnattotrxbytes", "iprnatrxbytesrate", "iprnattottxbytes", "iprnattxbytesrate", "iprnattotrxpkts", "iprnatrxpktsrate", "iprnattottxpkts", "iprnattxpktsrate", "iprnattottxsyn", "iprnattxsynrate", "iprnatcursessions")
        ),
        "statservicegroupmember"  => array(
            "cntrs" => array("primaryipaddress", "primaryport", "state", "servicetype", "totalrequests", "requestsrate", "totalresponses", "responsesrate", "totalrequestbytes", "requestbytesrate", "totalresponsebytes", "responsebytesrate", "curclntconnections", "cursrvrconnections", "svrestablishedconn", "surgecount", "curreusepool", "maxclients", "avgsvrttfb")
        ),
        "statservice"  => array(
            "cntrs" => array("primaryipaddress", "primaryport", "state", "servicetype", "totalrequests", "requestsrate", "totalresponses", "responsesrate", "totalrequestbytes", "requestbytesrate", "totalresponsebytes", "responsebytesrate", "curclntconnections", "cursrvrconnections", "svrestablishedconn", "surgecount", "curreusepool", "maxclients", "avgsvrttfb", "curload"),
            "properties" => array("servicetype" => array("title" => "Protocol"))
        ),
        "statscpolicy"  => array(
            "cntrs" => array("scphysicalserviceip", "scphysicalserviceport", "sccurrentclientconnections", "sccurrentwaitingclients", "totopenconn", "sccurrentwaitingtime", "sctotalclientconnections", "scclientconnectionsrate", "sctotalserverconnections", "scserverconnectionsrate", "totclienttransaction", "clienttransactionrate", "sctotalservertransactions", "scservertransactionsrate", "sctotalrequestsreceived", "screquestsreceivedrate", "sctotalrequestbytes", "screquestbytesrate", "sctotalresponsesreceived", "scresponsesreceivedrate", "sctotalresponsebytes", "scresponsebytesrate", "scaverageclientttlb", "avgservertransactiontime")
        ),
        "statvlan"  => array(
            "cntrs" => array("vlantotrxbytes", "vlanrxbytesrate", "vlantottxbytes", "vlantxbytesrate", "vlantotrxpkts", "vlanrxpktsrate", "vlantottxpkts", "vlantxpktsrate", "vlantotdroppedpkts", "vlantotbroadcastpkts")
        ),
        "statvpnvserver"  => array(
            "cntrs" => array("primaryipaddress", "primaryport", "state", "type", "totalrequests", "requestsrate", "totalresponses", "responsesrate", "totalrequestbytes", "requestbytesrate", "totalresponsebytes", "responsebytesrate"),
            "properties" => array("primaryipaddress" => array("title" => "IP Address"),
                                  "type" => array("title" => "Protocol"))
        )
    );

    public function get_tabular_view_definition($cmd_name)
    {
        if(isset($this->tabular_views[$cmd_name]))
            return $this->tabular_views[$cmd_name];
        return null;
    }
}
?>
