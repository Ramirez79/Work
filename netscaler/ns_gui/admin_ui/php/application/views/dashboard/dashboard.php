<?php if(!defined("BASEPATH")) exit("No direct script access allowed");
//Don't allow this page to be embedded inside a frame
header("X-Frame-Options: DENY");

if($is_ajax_request)
{
    header(XML_HEADER);
    print "<messages>\n";
    $this->load->view("common/gauges");
    print "</messages>";
    return;
}
$this->load->view("common/header");
?>
<style type="text/css">
.ns_content
{
    padding: 2px;
}
</style>
<?php
$stc_iframe_name = "stc_iframe";

require_once(APPPATH . "controllers/common/rapi.php");
$rapi = new rapi(true);
$hide_interface_stat = $rapi->is_partiton_configured_and_is_not_default_partition();

if($hide_interface_stat == false)
    $this->load->view("common/gauges", array("load_links_in_frame" => $stc_iframe_name));

$content_url = "/menu/stc";
if(isset($cfunc))
    $content_url .= "?func=$cfunc";
?>

<script type="text/javascript" src="/admin_ui/common/js/highcharts/highcharts.js"></script>
<script type="text/javascript" src="/admin_ui/common/js/ns/chart/chart_constants.js"></script>
<script type="text/javascript" src="/admin_ui/common/js/ns/chart/chart_utils.js"></script>
<script type="text/javascript" src="/admin_ui/common/js/ns/chart/chart.js"></script>
<link rel="stylesheet" type="text/css" href="/admin_ui/common/css/ns/chart/chart.css">
<link rel="stylesheet" type="text/css" href="/admin_ui/common/css/ns/splitter/splitter.css">
<script type="text/javascript" src="/admin_ui/common/js/jquery/jquery.cookie.js"></script>
<script type="text/javascript" src="/admin_ui/common/js/jquery/splitter.js"></script>
<script type="text/javascript" src="/admin_ui/dashboard/js/dashboard.js"></script>
<div id="splitter_div" class="splitter">
    <div id="left_dash_pane" class="pane left_dash_pane">
        <iframe src="/menu/coc?id=0" frameborder="0" marginheight="0" marginwidth="0" width="96%" style="height:236px; display: block"></iframe>
        <iframe src="/menu/coc?id=1" frameborder="0" marginheight="0" marginwidth="0" width="96%"  style="height: 236px; display: block; margin-top: 18px"></iframe>
    </div>
    <div id="right_dash_pane" class="pane">
        <iframe name="<?=$stc_iframe_name?>" class='ns_frame' src="<?=$content_url?>" frameborder="0" marginheight="0" marginwidth="0" width="100%" style="height: 100%; display: block; padding-right: 10px;"></iframe>
    </div>
</div>
<?php
view_utils::print_auto_refresh_javascript($_SESSION[GAUGES_PAGE_NUMBER_KEY]);
?>