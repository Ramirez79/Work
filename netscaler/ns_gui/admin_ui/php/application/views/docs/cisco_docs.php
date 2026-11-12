<?php if(!defined("BASEPATH")) exit("No direct script access allowed");
$this->load->view("common/header");
?>
<script type="text/javascript" src="/admin_ui/common/js/ns/ns_resize_handler.js"></script>

<div class = "dd_layout">
    <div class = "doc_ns_header_title"> Documentation Library </div>
    <div class = "doc_contents" style= "font-size:14px;padding-left:10px"> 
        <p align="justify">
            <b>For Citrix NetScaler 1000V documentation, contact Cisco Support.</b>
        </p>
    </div>
</div>

<?php
?>

<script type="text/javascript">
    $(window).bind("resize", ns_resize_handler);
    ns_resize_handler();
</script>
