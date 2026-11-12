<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
// Don't allow this page to be embedded inside a frame
header("X-Frame-Options: SAMEORIGIN");

require_once(APPPATH. "views/common/tree_utils.php");
$this->load->view("common/header");
?>
<style type="text/css">
.ns_body
{
    min-width: 300px !important;
}

.tree_header
{
    cursor: auto;
}

.tree_links
{
    border-right: none;
}
</style>
<script type="text/javascript">
var success_result = '<?=SUCCESS_RESULT?>';
var nserr_not_primary = "<?= isset($nserr_not_primary) ? $nserr_not_primary : "" ?>";
</script>
<script type="text/javascript" src="/admin_ui/common/js/ns/tree.js"></script>
<script type="text/javascript" src="/admin_ui/reporting/js/manage_custom_reports.js"></script>
<?php
print "<div id=\"manage_custom_reports_div\" class=\"manage_custom_reports\"><table cellspacing=\"0\" cellpadding=\"3\" class=\"tree_links\">\n";
print "<tr><td class=\"tree_header tree_sub_header\" style=\"padding-left: 3px !important\"><input type=\"checkbox\" value=\"all\" id=\"select_all_ckb\" onclick=\"row_selected(event, this)\" title=\"Click to select/unselect all reports and folders\">&nbsp;Custom Reports</td>";
print "<td class=\"tree_header tree_sub_header\" style=\"padding-left: 0px !important\" align=\"right\"><a href=\"#noAnchor\" onclick=\"window.location.reload()\"><img src=\"/admin_ui/common/images/refresh.png\" alt=\"Refresh\" title=\"Refresh\" border=\"0\"></a></td>";
print "</tr>\n";
$tree_utils = new tree_utils();
$tree_utils->create_separate_tree_with_nodes = true;
$tree_utils->id_used_for_separate_tree = CUSTOM_REPORTS;
$tree_utils->id_used_for_no_nodes = "no_custom_report_row";
$tree_utils->message_used_for_no_nodes = "No reports available";
$tree_utils->id_prefix = "c_";
$tree_utils->target_for_links = "_blank";
$tree_utils->select_leaf_onclick = false;
$tree_utils->controller_name = "rep";
$tree_utils->print_check_box = true;
$tree_utils->expand_all_nodes = true;
print "<tr><td><table id=\"" . $tree_utils->id_prefix . "all_table\" class=\"manage_custom_reports_table\">\n";
$tree_utils->print_tree("", $report_names);
$custom_reports_tree_nodes_div_inner_html = $tree_utils->separate_tree_with_nodes_inner_html;
$total_number_of_rows = $tree_utils->check_box_index;
print "</table></td></tr></table></div>\n";
?>
<script type="text/javascript">
    var total_number_of_rows = <?=$total_number_of_rows?>;
    var id_prefix = "<?=$tree_utils->id_prefix?>";
    doActionOnResize();
</script>
<table ><tr><td class="toolbar">
<a href="#noAnchor" onclick="delete_reports_folders()" title="Delete the selected reports/folders"><img src="/admin_ui/common/images/delete.png" alt="Delete"/><span>Delete</span></a>
<a href="#noAnchor" onclick="export_reports()" title="Export the selected reports/folders to local disk" class="export_reports_href"><img src="/admin_ui/common/images/export2.png" alt="Export"/><span>Export</span></a>
<a href="#noAnchor"><form name="import_reports_form" class="inline_display" enctype="multipart/form-data" action="/menu/impr" method="POST" target="import_reports_iframe" onsubmit="return false;"><input type="hidden" name="MAX_FILE_SIZE" value="<?=ini_get("upload_max_filesize")?>"/><div class="import_reports_div inline_display"><input type="hidden" name="<?=$_SESSION["rand_key"];?>" value="<?=$_SESSION["rand"];?>"/><input type="file" name="import_reports_file" size="1" class="import_reports_file pointer_cursor" onchange="import_reports()"/><div id="import_reports_fake_div" class="import_reports_fake_div" title="Import reports from local disk"><img src="/admin_ui/common/images/import1.png" alt="Import"/><span>Import</span></div></div></form></a>
</td></tr></table>
<iframe name="export_reports_iframe" style="display: none"></iframe>
<iframe name="import_reports_iframe" style="display: none"></iframe>