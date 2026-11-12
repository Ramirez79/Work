<?php if(!defined("BASEPATH")) exit("No direct script access allowed");
$this->load->view("common/header");
?>
<script type="text/javascript" src="/admin_ui/common/js/ns/ns_resize_handler.js"></script>

<table width="99%" align="center" cellpadding="0" cellspacing="0" class="smooth_all_corner dd_layout_border">
<colgroup width="80%">
<colgroup width="20%">
    <tr class="ns_wp_header">
        <td colspan="3" class="doc_ns_header_title">On-Line Documentation</td>
    </tr>
    <tr>
        <td colspan="3" height="11"></td>
    </tr>
    <tr>
        <td colspan="2" class="docs_top_vertical_align">
            <table width="99%" align="center" cellpadding="0" cellspacing="0" height="100%">
            <colgroup width="32">
            <colgroup width="5">
            <colgroup width="*">
                    <tr title="Provides information about known issues in this release of NetScaler Gateway.">
                        <td valign="top" class="ns_doc_img"><a href="/support/docs/pdf/AGEE_readme.html" target="AGEE_Release_Notes"><img src="/admin_ui/common/images/document.png" alt="HTML" class="negative_margin_left"></a></td>
                        <td>&nbsp;</td>
                        <td><a href="/support/docs/pdf/AGEE_readme.html" target="AGEE_Release_Notes">Release Notes</a></td>
                    </tr>
                    <tr title="Describes configuration of reusable components on the NetScaler, including named expressions for use in policy rules; rate limiting expressions for use in policies that analyze traffic rate; HTTP service callouts that send queries to backend services; pattern sets for use in policy rules; entity templates that simplify configuration of many types of NetScaler components; and AppExpert applications that contain complete, sharable configurations for processing Web application traffic.">
                        <td valign="top" class="ns_doc_img"><a href="/support/docs/pdf/NS-AppExpert-Guide.pdf" target="AppExpert_Guide"><img src="/admin_ui/common/images/pdf_icon.png" alt="PDF"></a></td>
                        <td>&nbsp;</td>
                        <td><a href="/support/docs/pdf/NS-AppExpert-Guide.pdf" target="AppExpert_Guide">AppExpert Guide</a></td>
                    </tr>
                    <tr title="Provides a detailed description of all the commands. The contents of this guide are identical to the man pages. The chapters in this guide reflect the command groups.">
                        <td valign="top" class="ns_doc_img"><a href="/support/docs/pdf/NS-CommandReference-Guide.pdf" target="AGEE_Command_Reference"><img src="/admin_ui/common/images/pdf_icon.png" alt="PDF"></a></td>
                        <td>&nbsp;</td>
                        <td><a href="/support/docs/pdf/NS-CommandReference-Guide.pdf" target="AGEE_Command_Reference">Command Reference</a></td>
                    </tr>
                    <tr title="Provides conceptual information and procedures for using the NetScaler Gateway Plug-in on a Windows computer.">
                        <td valign="top"><img src="/admin_ui/common/images/pdf_icon.png" alt="PDF"></td>
                        <td>&nbsp;</td>
                        <td>NetScaler Gateway Plug-in User Guide (Windows)<br>
                            <a href="/support/docs/pdf/AGEE_SACClient-EN.pdf" target="AGEE_SSLVPN_User_Guide_EN">English</a>&nbsp;
                            <a href="/support/docs/pdf/AGEE_SACClient-DE.pdf" target="AGEE_SSLVPN_User_Guide_DE">German</a>&nbsp;
                            <a href="/support/docs/pdf/AGEE_SACClient-ES.pdf" target="AGEE_SSLVPN_User_Guide_ES">Spanish</a>&nbsp;
                            <a href="/support/docs/pdf/AGEE_SACClient-FR.pdf" target="AGEE_SSLVPN_User_Guide_FR">French</a>&nbsp;
                            <a href="/support/docs/pdf/AGEE_SACClient-JA.pdf" target="AGEE_SSLVPN_User_Guide_JA">Japanese</a>
                         </td>
                    </tr>
                    <tr title="Provides conceptual information and procedures for using the NetScaler Gateway Plug-in on a Windows, Macintosh, Linux, or Unix client device.">
                        <td valign="top" class="ns_doc_img"><a href="/support/docs/pdf/AGEE_SAC_JavaClient.pdf" target="AGEE_SSLVPN_Java_Client_User_Guide"><img src="/admin_ui/common/images/pdf_icon.png" alt="PDF"></a></td>
                        <td>&nbsp;</td>
                        <td><a href="/support/docs/pdf/AGEE_SAC_JavaClient.pdf" target="AGEE_SSLVPN_Java_Client_User_Guide">NetScaler Gateway Plug-in User Guide (Java)</a></td>
                    </tr>
              </table>
        </td>
        <td class="docs_top_vertical_align">
            <div class="ns_doc_info_section">
                 <p align="justify">
                 The NetScaler Gateway documentation is available in the Citrix eDocs library at <a href="https://edocs.citrix.com" target="AGEE_EDOCS">https://edocs.citrix.com/</a>.
                 </p>
            </div>

            <div class="ns_doc_info_acrobat_reader_section">
                 <p align="justify">
                    PDF files require Adobe Acrobat Reader to be installed on your computer.
                 </p>
                  <p align="justify">
                  You can download Adobe Acrobat Reader from <a href="http://www.adobe.com/products/acrobat/readstep2.html" target="Adobe_Site">Adobe's Web site</a>.
                  </p>
                  <a href="http://www.adobe.com/products/acrobat/readstep2.html" target="Adobe_Site"><img src="/admin_ui/common/images/adobe.png" alt="Adobe"> </img></a>

            </div>
        </td>
    </tr>
 </table>
<?php
?>
<script type="text/javascript">
    $(window).bind("resize", ns_resize_handler);
    ns_resize_handler();
</script>