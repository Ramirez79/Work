<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require_once("abstract_controller.php");

class authorizer extends abstract_controller
{
    function __construct()
    {
        parent::__construct();
    }

    private function show_documentation_404($document_file_name)
    {
        //Document names start with "NS/AGEE" and end with "pdf/html"
        if(!preg_match("/^(NS|AGEE).*(pdf|html)$/", $document_file_name))
            $this->show_404();

        $file_names_to_keywords_map = array("AGEE_readme.html"      =>  "AGEE readme",
                                            "NS-LogMessages.html"   =>  "Log Message Reference",
                                            "NS-SNMPOIDs.html"      =>  "SNMP OID Reference",
                                            "NS-VPXGettingStarted-Guide.pdf"    =>  "NS VPX Getting Started Guide");
        if(isset($file_names_to_keywords_map[$document_file_name]))
            $document_file_name = $file_names_to_keywords_map[$document_file_name];

        $data = array("title"             =>  "Document not found",
                      "standalone"        =>  "YES",
                      "no_footer_link"    =>  true);
        print $this->load->view("common/header", $data, true);

?>
<div class="center_panel no_wrap_td" style="left: 21%">
The document is unavailable. To locate the document, you can search for the document<br>
on the Citrix Knowledge Center at <a href="http://support.citrix.com/search/advanced?searchQuery=<?=$document_file_name?>" title="Click to search the document in Knowledge Center" target="SEARCH_PAGE">http://support.citrix.com/</a>, or you can download the<br>
full documentation set from My Citrix.<br><br>
<b>To download the full documentation set</b><br>
1. Sign in to <a href="https://www.mycitrix.com" target="MY_CITRIX">http://mycitrix.com/</a>, and then click <b>Downloads</b>.<br>
2. In the <b>Search Downloads by Product list</b>, select <b>Citrix NetScaler</b>.<br>
3. Under <b>Firmware</b>, click the release and build number that you want, and then click <b>Get Firmware</b>.<br>
4. Click <b>Show Documentation</b>, and then click <b>Get Documentation</b>.<br>
5. Copy the compressed file to the /var/netscaler/doc directory on the appliance.<br>
6. Extract the files by typing the following command at the shell prompt:<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>tar xvfpz ns-</b>&lt;releasenumber&gt;-&lt;buildnumber&gt;<b>-doc.tgz -C /var/netscaler/doc</b><br><br>
<font class="find_panel_reporting">If you have comments or feedback on this procedure, please email us at <a href="mailto:nsdocs_feedback@citrix.com?subject=Feedback%20on%20documentation%20installation%20procedure" onclick="cancel_onbeforeunload()">nsdocs_feedback@citrix.com</a></font>.
</div>
<?php
        print $this->load->view("common/footer", $data, true);
    }

    //Sends 404 - File not found response.
    private function show404()
    {
        if(preg_match("/\/support\/docs\/pdf\/(.*)/", $_SERVER["REQUEST_URI"], $matches))
        {
            $this->show_documentation_404($matches[1]);
            exit;
        }
        $this->show_404();
    }

    public function other()
    {
        //Get the absolute physical path of the file
        $pat = "/".addcslashes($_SERVER['DOCUMENT_ROOT'], "/")."/";
        $helpURL = preg_replace($pat, "", $_SERVER['PATH_TRANSLATED']);
        $file_ext = substr($helpURL, -3);
        $file_exte = substr($helpURL, -4);
        $is_pdf = $file_ext == "pdf";
        $is_tgz = $file_ext == "tgz";
        $is_msi = ($file_ext == "msi" || $file_ext == "exe");
        if($is_pdf || $is_tgz || $is_msi)
            session_cache_limiter('must-revalidate');

        if(!$this->start_session(true))
            return;

        if(!file_exists($helpURL))
            $this->show404();
        if($is_pdf)
        {
            //Setting max age to 5 because on logout and directly pasting the URL in the same browser should not open the pdf
            $this->output->set_header("Cache-Control: max-age=5");
            $this->output->set_header("Content-type: application/pdf");
        }
        else if($file_ext == "htm" || $file_exte == "html")
        {
            $this->output->set_header("Content-type: text/html");
        }
        else if($is_tgz)
        {
            $this->output->set_header("Cache-Control: ");
            $this->output->set_header("Content-type: application/gzip");
            //Required for IE - http://support.microsoft.com/?id=260519
            $this->output->set_header("Content-disposition: attachment; filename=" . basename($helpURL));
            $this->output->set_header("Content-Length: " . filesize($helpURL));
        }
        else if($file_ext == "xml" || $file_exte == "wsdl")
        {
            $this->output->set_header("Content-type: text/xml");
        }
        else if($is_msi)
        {
            $this->output->set_header("Content-type: application/x-msdownload");
        }
        else
        {
            $this->output->set_header("Content-type: text/plain");
        }
        //Read the file and serve the contents in one go.
        //Can use http_send_file() with added plugin to send file in chunks
        $this->output->set_output(file_get_contents($helpURL));
    }

    public function help()
    {
        if(!$this->start_session(true))
            return;

        $helpURL = "/var/netscaler" . preg_replace("/\?.*/", "", $_SERVER["REQUEST_URI"]) . ".gz";
        if(!file_exists($helpURL))
        {
            $this->show404();
            return;
        }
        $this->output->set_header("Content-type: text/html");
        $this->output->set_header("Content-Encoding: gzip");
        //Read the file and serve the contents in one go.
        //Can use http_send_file() with added plugin to send file in chunks
        $this->output->set_output(file_get_contents($helpURL));
    }
}
?>