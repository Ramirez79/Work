<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
//Don't allow this page to be embedded inside a frame
header("X-Frame-Options: DENY");

require_once(APPPATH. "views/common/tree_utils.php");
$this->load->view("common/header");
require_once(APPPATH . "controllers/common/rapi.php");
$rapi = new rapi(true);
$is_partition  = $rapi->is_partiton_configured_and_is_not_default_partition();
if($is_partition == false)
{
print "<div id=\"enclosing_gauges_div\" class=\"full_width_table\">";
$this->load->view('common/gauges');
view_utils::print_auto_refresh_javascript($_SESSION[GAUGES_PAGE_NUMBER_KEY]);

?>

<div>
    <div class = "hline"></div>
    <div class = "navigate">
        <a id = "show_hide_gauges_href" href="#noAnchor" onclick="javascript:show_hide_gauges()" title="Hide gauges and stop auto update">
        <div id = "show_hide_gauges_img" class = "reporting_images show_img pointer_cursor"> </div>
        </a>
    </div>
</div>
<?php
if($hide_top_gauges)
    print "<script type=\"text/javascript\">show_hide_gauges();</script>\n";
?>
</div>
<?php
}
else{
?>
<div style="height:30px"></div>
<?php
}
?>

<style type="text/css">
/*Overrides definitions in ui.css*/
.ns_content
{
    padding: 2px;
}

.popup_info
{
    border: none;
}


</style>
<script type="text/javascript" src="/admin_ui/common/js/json/json2.js"></script>
<script type="text/javascript" src="/admin_ui/common/js/highcharts/highcharts.js"></script>
<script type="text/javascript" src="/admin_ui/common/js/ns/chart/chart_constants.js"></script>
<script type="text/javascript" src="/admin_ui/common/js/ns/chart/chart_utils.js"></script>
<script type="text/javascript" src="/admin_ui/common/js/ns/chart/chart.js"></script>
<link rel="stylesheet" type="text/css" href="/admin_ui/common/css/ns/chart/chart.css">
<script type="text/javascript" src="/admin_ui/common/js/ns/tree.js"></script>
<script type="text/javascript" src="/admin_ui/reporting/js/reporting_view.js"></script>

<!--START: Left tree-->
<table class="full_width_height_table" cellpadding="0" cellspacing="0">
<tr><td id="left_tree" class="tree_links" valign="top" align="left">
<div id="left_tree_div" class="full_width_height_div">
<table class="full_width_table reporting_tree smooth_all_corner" cellpadding="0" cellspacing="0">

<tr><td class="tree_header" onclick="show_hide_tree_node('built_in_reports_row')" title="Show/hide built-in reports"><img id="built_in_reports_row_img"  src="/admin_ui/common/images/TreeExpanded.png" alt="Show/hide built-in reports" title="" border="0">&nbsp;<b>Built-in Reports</b></td></tr><tr id="built_in_reports_row"><td>
<table class="reporting_left_table" width="93%" cellpadding="0" cellspacing="0">
<?php
$tree_utils = new tree_utils();
$tree_utils->id_prefix = "b_";
$tree_utils->table_class_name = "b_level";
$tree_utils->print_tree($name, $built_in_reports, $ds);
$id_of_node_to_expand = $tree_utils->id_of_node_to_expand;

if($internal)
{
?>
</table></td></tr>
<tr><td class="padding_top"><img src="/admin_ui/common/images/spacer.gif" width=1 height=1 alt=""></td></tr>
<tr><td class="tree_header" onclick="show_hide_tree_node('debug_reports_row')" title="Show/hide debug reports">
<img id="debug_reports_row_img"  src="/admin_ui/common/images/TreeExpanded.png" alt="Show/hide debug reports" title="" border="0">&nbsp;<b>Debug Reports</b></td></tr><tr id="debug_reports_row"><td><table class="full_width_table reporting_left_table", cellpadding="0" cellspacing="0">
<?php
    $tree_utils = new tree_utils();
    $tree_utils->id_prefix = "dbg_";
    $tree_utils->table_class_name = "b_level";
    $tree_utils->print_tree($name, $debug_reports, $ds);
    if(strlen($id_of_node_to_expand) <= 0)
        $id_of_node_to_expand = $tree_utils->id_of_node_to_expand;
}
?>
</table></td></tr>
<tr><td class="padding_top"><img src="/admin_ui/common/images/spacer.gif" width=1 height=1 alt=""></td></tr>
<tr><td class="tree_header" onclick="show_hide_tree_node('custom_reports_row')" title="Show/hide custom reports">
<img id="custom_reports_row_img" src="/admin_ui/common/images/TreeExpanded.png" alt="Show/hide custom reports" title="" border="0">&nbsp;<b><?=CUSTOM_REPORTS?></b>
&nbsp;<a href="#noAnchor" onclick="return open_manage_custom_reports(event);"><img id = "manage_custom_reports" src="/admin_ui/common/images/documents_gear.png" alt="Manage custom reports" title="Manage custom reports" border="0"></a>
</td></tr><tr id="custom_reports_row"><td><table id="c_<?=CUSTOM_REPORTS?>_table" class="reporting_left_table" width="93%" cellpadding="0" cellspacing="0">
<?
$tree_utils = new tree_utils();
$tree_utils->create_separate_tree_with_nodes = true;
$tree_utils->id_used_for_separate_tree = CUSTOM_REPORTS;
$tree_utils->id_used_for_no_nodes = "no_custom_report_row";
$tree_utils->message_used_for_no_nodes = "No reports available";
$tree_utils->id_prefix = "c_";
$tree_utils->table_class_name = "b_level";
$tree_utils->is_current_node_new = isset($new);
$tree_utils->parent_of_current_node = isset($new) ? $parent : null;
$tree_utils->print_tree($name, $custom_reports, $ds);
$custom_reports_tree_nodes_div_inner_html = $tree_utils->separate_tree_with_nodes_inner_html;
if(strlen($id_of_node_to_expand) <= 0)
    $id_of_node_to_expand = $tree_utils->id_of_node_to_expand;

$current_parent_node = CUSTOM_REPORTS;
if(strlen($id_of_node_to_expand) > 0)
{
    print "\n<script type=\"text/javascript\">\n";
    $ids_to_expand = explode(",", $id_of_node_to_expand);
    if(!$custom)
        print "show_hide_tree_node('custom_reports_row');";
    if(!$debug && $internal)
        print "show_hide_tree_node('debug_reports_row');";
    if(!$built_in)
        print "show_hide_tree_node('built_in_reports_row');";
    if($custom)
        $current_parent_node = preg_replace("/^c_/", "", $ids_to_expand[0]);
    foreach($ids_to_expand as $id_to_expand)
        print "show_hide_tree_node('" . $id_to_expand . "');";
    print "\n</script>";
}
$name = htmlentities($name);
$content_url = "/menu/repcontent?name=$name&ds=$ds";
if(isset($new))
{
    $content_url .= "&new=$new";
    if(isset($parent))
        $content_url .= "&parent=$parent";
}
else if($current_parent_node != CUSTOM_REPORTS)
    $content_url .= "&parent=$current_parent_node";
?>
</table></td></tr>
</table></div>
<!--END: Left tree-->
<div id="custom_reports_tree_nodes_div" class="popup tree_nodes_div"><?=$custom_reports_tree_nodes_div_inner_html?></div>

</td><td valign="top" align="left" class="right_tree_div">
<?php
if(isset($reporting_disabled))
{
?>
<div id="enable_reporting_div" class="popup_info">
    Data collection utility (nscollect) is disabled.
    <a href="#noAnchor" onclick="enable_reporting()">Click here</a> to enable data collection utility.
</div>
<?php
}
?>
<iframe name="content_iframe" src="<?=$content_url?>" frameborder="0" marginheight="0" marginwidth="0" width="100%" height="100%" class="top_vertical_align" style="height: 100%; display: block"></iframe>
</td></tr></table>

<script type="text/javascript">
var rand_token = "<?= $rand_key ?>";
var cache = [];
var nserr_not_primary = "<?= isset($nserr_not_primary) ? $nserr_not_primary : "" ?>";
doActionOnResize();
</script>
