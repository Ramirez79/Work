<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
// Don't allow this page to be embedded inside a frame
header("X-Frame-Options: DENY");
$this->load->view("common/header");
?>
<script type="text/javascript" src="/admin_ui/common/js/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="/admin_ui/common/js/ns/ns_resize_handler.js"></script>
<div class = "dd_layout">
    <div class = "doc_ns_header_title"> Documentation Library </div>
    <div class = "doc_contents"> 
        <div class = "doc_content_left">
            <div class="ns_small_header"><div class = "docs_images product_img pointer_cursor"> </div><b>Product Documentation</b></div>
            <div style= "padding: 5px 20px;"> 
                <ul>
                    <?php
                    if(!isset($is_sgw) || $is_sgw == "false")
                    {
                    ?>
                    <li class="ns_doc_node">
                        <div><a href="http://docs.citrix.com/en-us/netscaler/11.html" target="NetScaler_Product_Documentation">NetScaler</a></div>
                    </li>
                    <?php
                    }
                    ?>
                    <li class="ns_doc_node">
                        <div><a href="http://docs.citrix.com/en-us/netscaler-gateway/11.html" target="NetScaler_Gateway_Product_Documentation">NetScaler Gateway</a></div>
                    </li>
                </ul>
            </div>
        </div>
        <div class = "doc_content_right">
            <div class="ns_small_header">
                <div class = "docs_images resource_img pointer_cursor"> </div><b>Developer Resources</b>
                <div class = "doc_help_div" title = "Click here to learn how to use eDocs">
                    <a target="Learn_How_To_Use_Edocs" href="https://support.citrix.com/proddocs/topic/infocenter/ic-how-to.html">
                        <div class = "docs_images doc_help_img pointer_cursor"></div>
                    </a>
                </div>
            </div>
            <div style= "padding:5px 20px;"> 
                <ul>
                    <li class="ns_doc_node">
                        <div title="A reference to application developers using the NITRO API (Java, .NET, and REST) to configure the NetScaler appliance.">
                            <a href="http://docs.citrix.com/en-us/netscaler/11/nitro-api.html" target="NITRO_Getting_Started">NITRO API</a>
                        </div>
                    </li>
                    <li class="ns_doc_node">
                        <div><a href="/admin_ui/nitro_client/index.html?rand=<?php print $_SESSION["rand"] ?>&nsverion=<?php print $_SESSION["nsversion"] ?>" target="_blank" >NITRO Client</a></div>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</div>    
<script type="text/javascript">
    $(window).bind("resize", ns_resize_handler);
    ns_resize_handler();
</script>


