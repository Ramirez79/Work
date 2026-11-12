<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// Don't allow this page to be embedded inside a frame
header("X-Frame-Options: SAMEORIGIN");

require_once(APPPATH."views/common/locale_utils.php");
$this->load->view("common/header");

$elements = $report['elements'];
$stime_default_value = report_definitions::get_report_stime($report);
$etime_default_value = report_definitions::get_report_etime($report);
$last_default_value = report_definitions::get_report_last($report);
$unit_default_value = report_definitions::get_report_unit($report);
$report_display_name = report_definitions::get_report_name($report);

$current_parent_node = isset($parent) ? $parent : CUSTOM_REPORTS;
?>
<link rel="stylesheet" type="text/css" href="/admin_ui/common/css/calendar/calendar-win2k-cold-1.css">
<link rel="stylesheet" type="text/css" href="/admin_ui/common/css/yui/menu.css">
<link rel="stylesheet" type="text/css" href="/admin_ui/common/css/yui/ns/menu.css">
<link rel="stylesheet" type="text/css" href="/admin_ui/common/css/yui/button.css">
<link rel="stylesheet" type="text/css" href="/admin_ui/common/css/yui/ns/button.css">
<link rel="stylesheet" type="text/css" href="/admin_ui/common/css/yui/slider.css">
<link rel="stylesheet" type="text/css" href="/admin_ui/common/css/yui/ns/slider.css">

<script type="text/javascript" src="/admin_ui/common/js/calendar/calendar.js"></script>
<script type="text/javascript" src="/admin_ui/common/js/calendar/calendar-en.js"></script>
<script type="text/javascript" src="/admin_ui/common/js/calendar/calendar-setup.js"></script>

<script type="text/javascript" src="/admin_ui/common/js/jscolor/jscolor.js"></script>

<script type="text/javascript" src="/admin_ui/common/js/yui/yahoo-dom-event.js"></script>
<script type="text/javascript" src="/admin_ui/common/js/yui/container_core-min.js"></script>
<script type="text/javascript" src="/admin_ui/common/js/yui/menu-min.js"></script>
<script type="text/javascript" src="/admin_ui/common/js/yui/dragdrop-min.js"></script>
<script type="text/javascript" src="/admin_ui/common/js/yui/animation-min.js"></script>
<script type="text/javascript" src="/admin_ui/common/js/yui/slider-min.js"></script>
<script type="text/javascript" src="/admin_ui/common/js/yui/element-min.js"></script>
<script type="text/javascript" src="/admin_ui/common/js/yui/button-min.js"></script>

<script type="text/javascript" src="/admin_ui/common/js/ns/list.js"></script>
<script type="text/javascript" src="/admin_ui/common/js/ns/sort.js"></script>
<script type="text/javascript" src="/admin_ui/common/js/ns/table.js"></script>
<style type="text/css">
/*Overrides definitions in ui.css*/
.ns_body
{
    min-width: 749px !important;
}

.ns_content
{
    padding: 0px;
}

.loading_panel
{
    height: 200px;
}

.ns_wp_title td
{
    color: #003399;
}
</style>
<!--[if IE 6]>
<style type="text/css">
.ns_body
{
    width: expression(this.parentNode.clientWidth < 759 ? "749px" : "100%" );
}
</style>
<![endif]-->
<script type="text/javascript">
//Following variables are used in reporting.js
var ds = '<?= $ds?>';
var is_default_data_source = <?=$is_default_data_source ? "true" : "false"?>;
var unit = '<?=$unit_default_value?>';
var last_default_value = <?=LAST_DEFAULT_VALUE?>;
var last = <?=$last_default_value?>;
var stime = '<?=$stime_default_value?>';
var etime = '<?=$etime_default_value?>';
var use_start_end_time = (stime != "" && etime != "");
var orig_unit = unit;
var orig_last = last;
var orig_stime = stime;
var orig_etime = etime;
var orig_use_start_end_time = use_start_end_time;
var report_display_name = '<?=$report_display_name?>';
var report_internal_name = '<?=htmlentities($name)?>';
var custom = '<?=$custom?>';
var prompt_save_warning = '<?=isset($new)?>';
var is_vmpe_mode = <?=is_vmpe_mode()?>;
var current_parent_node = '<?=$current_parent_node?>';
var grouping_separator = '<?=locale_utils::get_grouping_separator()?>';

var hours_default_value = '<?=HOURS?>';
var days_default_value = '<?=DAYS?>';
var weeks_default_value = '<?=WEEKS?>';
var months_default_value = '<?=MONTHS?>';
var years_default_value = '<?=YEARS?>';
var cpu_core_default_value = '<?=CPU_CORE?>';
var graph_type_default_value = '<?=LINE_GRAPH?>';
var max_no_of_data_sets = <?=MAX_NO_OF_DATA_SETS?>;
var max_no_of_charts_per_report = <?=MAX_NO_OF_CHARTS_PER_REPORT?>;
var success_result = '<?=SUCCESS_RESULT?>';
var ACTIVE_ENTITY = <?=ACTIVE_ENTITY?>;
var INACTIVE_ENTITY = <?=INACTIVE_ENTITY?>;
var DELETED_ENTITY = <?=DELETED_ENTITY?>;
var CUSTOM_REPORTS = '<?=CUSTOM_REPORTS?>';
</script>
<script type="text/javascript" src="/admin_ui/common/js/ns/tree.js"></script>
<?php
require_once(APPPATH . "views/common/view_utils.php");
view_utils::print_counter_tooltip_popup();
?>
<script type="text/javascript" src="/admin_ui/reporting/js/reporting.js"></script>
<script type="text/javascript" src="/admin_ui/reporting/js/reporting_chart_utils.js"></script>
<script type="text/javascript" src="/admin_ui/reporting/js/reporting_customization.js"></script>
<?php
if(!$is_default_data_source)
    print "<script type=\"text/javascript\" src=\"/admin_ui/reporting/js/reporting_debug.js\"></script>\n";
?>

<div id="custom_reports_tree_nodes_div" class="popup tree_nodes_div"></div>

<div align="left" valign="middle" class="report_name">
<b>Report:</b> <?=$report_display_name?>
</div>
<div class = "reporting_content_div">

<div id ="toolbar" class="full_width_table reporting_toolbar">
<div id ="left_reporting_toolbar" class="toolbar_cell">
<div class = "floated_ellipsed_divs" id= "duration_div" ><a href="#noAnchor" onclick="show_duration_menu()" title="Click to select duration"><div class="reporting_images duration_img pointer_cursor" title="Duration"/></div>Duration</a></div>
<div class = "floated_ellipsed_divs" id= "create_div" ><a href="#noAnchor" onclick="javascript:popup_show('create_report_div', 'create_report_span', 0, 19)" title="Click to create a new report"><div id="create_report_span" class="reporting_images create_img pointer_cursor" title="Create"/></div>Create</a></div>
<?php
if($custom)    //show "save" only for custom report
{
?>
<div class = "floated_ellipsed_divs" style = "width:75px;"><a href="#noAnchor" onclick="javascript:save_report()" title="Click to save the report"><div class="reporting_images save_img pointer_cursor" title="Save"/></div>Save</a></div>
<?php
}
?>
<div class = "floated_ellipsed_divs" id = "saveas_div"><a href="#noAnchor" onclick="javascript:popup_show('save_as_report_div', 'save_as_report_span', 0, 19)" title="Click to save the report in a different name"><div id="save_as_report_span" class= "reporting_images save_as_img pointer_cursor" title="Save As"/></div>Save As</a></div>
<?php
if($custom)    //show "delete" only for custom report
{
    $li_style = "style=\"display: inline\"";
    if(isset($new)) //Create report request, don't show delete until report gets saved.
        $li_style = "style=\"display: none\"";
?>
<div class = "floated_ellipsed_divs" id="delete_div" <?=$li_style?> ><a href="#noAnchor" onclick="javascript:delete_report()" title="Click to delete the report"><div class="reporting_images delete_img pointer_cursor" title="Delete"/></div>Delete</a></div>
<?php
}
?>

<div class = "floated_ellipsed_divs" id= "scroll_div" ><a href="#noAnchor" onclick="set_zoom_state(NORMAL_STATE)" title="Click to select scroll mode. Click inside any of the charts and drag left or right to scroll"><div class="reporting_images scroll_img pointer_cursor" title="Scroll" ></div>Scroll</a></div>
<div class = "floated_ellipsed_divs" id= "zoomin_div" ><a href="#noAnchor" onclick="set_zoom_state(ZOOM_IN_STATE)" title="Click to select zoom in mode. Click inside any of the charts to zoom in or click and drag to zoom in using a box"><div class="reporting_images zoomin_img pointer_cursor" title="Zoom In"/></div>Zoom In</a></div>
<div class = "floated_ellipsed_divs" id= "zoomout_div"><a href="#noAnchor" onclick="set_zoom_state(ZOOM_OUT_STATE)" title="Click to select zoom out mode. Click inside any of the charts to zoom out"><div class="reporting_images zoomout_img pointer_cursor" title="Zoom Out"/></div>Zoom Out</a></div>
<div class = "floated_ellipsed_divs" id = "reset_div" ><a href="#noAnchor" class="toolbar_last" onclick="reset_duration()" title="Click to reset duration"><div class="reporting_images reset_img pointer_cursor" title="Reset"/></div>Reset</a></div>
</div>
<div id = "right_reporting_toolbar" class="toolbar_cell toolbar_cell_right" align="right">
<div class = "floated_ellipsed_divs" style = "width:90px;"><a href="#noAnchor" class="toolbar_last" onclick="javascript:popup_show('settings_div', 'settings_span', 0, 19)" title="Click to see settings"><div id="settings_span" class="reporting_images setting_img pointer_cursor" title="Settings"/></div>Settings</a></div>
</div>
</div>

<div id ="popup_options">

<div id="settings_div" class="popup"><table><tr><td><b>Settings:</b></td><td align="right">
<img src="/admin_ui/common/images/navigate_cross.png" alt=Close"" title="Close" onclick="popup_hide('settings_div')"></td></tr><tr><td>
Data Source</td><td><select id="data_source_combo" title="Select the data source" class="data_source_combo">
<?php
$sel_end_time = "";
$numcpus = 1;
foreach($data_sources as $data_source)
{
    $temp_ds = $data_source["name"];
    $title = $temp_ds . " \nLast update @ " . $data_source["end_time"];
    if($temp_ds == $ds)
    {
        $sel_end_time = $data_source["end_time"];
        $numcpus = $data_source["numcpus"];
        print "<option value=\"$temp_ds\" title=\"$title\" selected>$temp_ds</option>\n";
    }
    else
        print "<option value=\"$temp_ds\" title=\"$title\">$temp_ds</option>\n";
}
?>
</select>&nbsp;<a href="#noAnchor" onclick="javascript:refresh_data_sources()">
<div class = "dashboard_images refresh_img pointer_cursor" title="Refresh data sources"> </div></a></td></tr>
<tr><td colspan="2" title="Select this to remember duration for which a chart is plotted."><input type="checkbox" id="sticky_time_ckb" <?=(isset($_COOKIE['stst']) && $_COOKIE['stst'] == "stst") ? "checked" : ""?>> Remember time selection for charts</td></tr>
<?php if(!isset($_SESSION['timezone_offset']) || $_SESSION['timezone_offset'] != 0)
{
?>
<tr><td colspan="2" title="Select this to use the time zone settings of NetScaler appliance"><input type="checkbox" id="convert_time_ckb" <?=(isset($_COOKIE['uatz']) && $_COOKIE['uatz'] == "uatz") ? "checked" : ""?>> Use Appliance's time zone</td></tr>
<?php
}
?>
<tr><td align="right" colspan="2" class="padding_top">
<input type="button" class="flat_button" value="OK" onclick="javaScript:settings_ok_clicked()" title="Click to apply the changes"/>
<input type="button" class="flat_button" value="Close" onclick="popup_hide('settings_div')" title="Click to close"/>
</td></tr></table></div>


<div id="save_as_report_div" class="popup"><table><tr><td><b>Save as:</b></td><td align="right">
<img src="/admin_ui/common/images/navigate_cross.png" alt="Close" title="Close" onclick="popup_hide('save_as_report_div')"></td></tr><tr><td>
Report Name</td><td><input type="textfield" id="report_new_name_tf" size="22" maxlength="<?=MAX_NO_OF_CHARS_IN_REPORT_NAME?>" value="" title="Enter name of the report" onkeypress="return key_pressed(event, save_as_report, word_check)"/></td>
</tr><tr><td>Save in</td><td><input type="textfield" id="save_in_tf" size="14" maxlength="<?=MAX_NO_OF_CHARS_IN_REPORT_NAME?>" title="Click to choose folder" value="<?=$current_parent_node?>" onclick="show_hide_custom_reports_tree_nodes('custom_reports_tree_nodes_save_span')" readonly/>&nbsp;<img src="/admin_ui/common/images/down.png" alt="Show folders" title="Show folders" class="pointer_cursor" onclick="show_hide_custom_reports_tree_nodes('custom_reports_tree_nodes_save_span')"><span id="custom_reports_tree_nodes_save_span">&nbsp;</span><img src="/admin_ui/common/images/folder_add.png" alt="Add folder" title="Add folder" class="pointer_cursor" onclick="show_hide_add_node_div('add_folder_save_span')"><span id="add_folder_save_span">&nbsp;</span></td></tr>
<tr><td align="right" colspan="2">
<input type="button" class="flat_button" value="OK" onclick="save_as_report()" title="Click to save the report in the specified name"/>
<input type="button" class="flat_button" value="Close" onclick="popup_hide('save_as_report_div')" title="Click to close"/>
</td></tr></table></div>


<div id="create_report_div" class="popup"><table><tr><td><b>Create:</b></td><td align="right">
<img src="/admin_ui/common/images/navigate_cross.png" alt="Close" title="Close" onclick="popup_hide('create_report_div')"></td></tr><tr><td>
Report Name</td><td><input type="textfield" id="report_name_tf" size="22" maxlength="<?=MAX_NO_OF_CHARS_IN_REPORT_NAME?>" value="" title="Enter name of the report" onkeypress="return key_pressed(event, create_report, word_check)"/></td>
</tr><tr>
<tr><td>Create in</td><td><input type="textfield" id="create_in_tf" size="14" maxlength="<?=MAX_NO_OF_CHARS_IN_REPORT_NAME?>" title="Click to choose folder" value="<?=$current_parent_node?>" onclick="show_hide_custom_reports_tree_nodes('custom_reports_tree_nodes_create_span')" readonly/>&nbsp;<img src="/admin_ui/common/images/down.png" alt="Show folders" title="Show folders" class="pointer_cursor" onclick="show_hide_custom_reports_tree_nodes('custom_reports_tree_nodes_create_span')"><span id="custom_reports_tree_nodes_create_span">&nbsp;</span><img src="/admin_ui/common/images/folder_add.png" alt="Add folder" title="Add folder" class="pointer_cursor" onclick="show_hide_add_node_div('add_folder_create_span')"><span id="add_folder_create_span">&nbsp;</span></td></tr>
<tr><td align="right" colspan="2">
<input type="button" class="flat_button" value="OK" onclick="create_report()" title="Click to create a new report"/>
<input type="button" class="flat_button" value="Close" onclick="popup_hide('create_report_div')" title="Click to close"/>
</td></tr></table></div>


<div id="add_folder_div" class="popup"><table><tr><td><b>Add Folder:</b></td><td align="right">
<img src="/admin_ui/common/images/navigate_cross.png" alt="Close" title="Close" onclick="popup_hide('add_folder_div')"></td></tr><tr><td>
Folder Name</td><td><input type="textfield" id="folder_name_tf" size="18" maxlength="<?=MAX_NO_OF_CHARS_IN_REPORT_NAME?>" value="" title="Enter name of the folder" onkeypress="return key_pressed(event, create_folder, word_check)"/></td>
</tr><tr>
<tr><td>Create in</td><td><input type="textfield" id="create_in_folder_tf" size="14" maxlength="<?=MAX_NO_OF_CHARS_IN_REPORT_NAME?>" title="Click to choose folder" value="<?=$current_parent_node?>" onclick="show_hide_custom_reports_tree_nodes('custom_reports_tree_nodes_create_folder_span')" readonly/>&nbsp;<img src="/admin_ui/common/images/down.png" alt="Show folders" title="Show folders" class="pointer_cursor" onclick="show_hide_custom_reports_tree_nodes('custom_reports_tree_nodes_create_folder_span')"><span id="custom_reports_tree_nodes_create_folder_span">&nbsp;</span></td></tr><tr>
<td align="right" colspan="2">
<input type="button" class="flat_button" value="OK" onclick="create_folder()" title="Click to create a new folder"/>
<input type="button" class="flat_button" value="Close" onclick="popup_hide('add_folder_div')" title="Click to close"/>
</td></tr></table></div>


<div id="custom_time_div" class="popup"><table><tr><td colspan="2"><b>Duration:</b></td><td align="right">
<img src="/admin_ui/common/images/navigate_cross.png" alt="Close" title="Close" onclick="popup_hide('custom_time_div')"></td></tr><tr><td>
From time</td><td><input type="textfield" id="from_time_tf" size="17" maxlength="16" value="<?=$stime_default_value?>" title="Enter time (mm-dd-yyyy hh:mm)" onkeypress="return key_pressed(event, custom_time_ok_clicked)"/></td><td>
<a href="#"><img src="/admin_ui/common/images/calendar.png" id="from_time_img" border="0" alt="Show calendar" title="Show calendar"></a></td></tr><tr>
<script language="Javascript">
Calendar.setup({
inputField     :    "from_time_tf",     // id of the input field
ifFormat       :    "%m-%d-%Y %H:%M",      // format of the input field
showsTime      :    true,
button         :    "from_time_img",  // trigger for the calendar (button ID)
timeFormat     :    "24",
step           :    1,
align          :    "Bl",           // alignment (defaults to "Bl")
singleClick    :    true
});
Calendar.prototype.isShowPopup = true;
</script>
</td></tr><tr><td>To time</td><td><input type="textfield" id="to_time_tf" size="17" maxlength="16" value="<?=$etime_default_value?>" title="Enter time (mm-dd-yyyy hh:mm)" onkeypress="return key_pressed(event, custom_time_ok_clicked)"/></td><td>
<a href="#"><img src="/admin_ui/common/images/calendar.png" id="to_time_img" border="0" alt="Show calendar" title="Show calendar"></a></td></tr><tr>
<script language="Javascript">
Calendar.setup({
inputField     :    "to_time_tf",     // id of the input field
ifFormat       :    "%m-%d-%Y %H:%M",      // format of the input field
showsTime      :    true,
button         :    "to_time_img",  // trigger for the calendar (button ID)
timeFormat     :    "24",
step           :    1,
align          :    "Bl",           // alignment (defaults to "Bl")
singleClick    :    true
});
Calendar.prototype.isShowPopup = true;
</script>
</td></tr>
<tr><td colspan="3"><hr></td></tr>
<tr title="Last update time"><td colspan="3">Last update @ <div id="ds_end_time_cell" class="inline_display"><?=$sel_end_time?></div></td></tr>
<tr><td align="right" colspan="3">
<input type="button" class="flat_button" value="OK" onclick="custom_time_ok_clicked()" title="Click to view report for the specified duration"/>
<input type="button" class="flat_button" value="Close" onclick="popup_hide('custom_time_div')" title="Click to close"/>
</td></tr></table>
</div>


</div>

<div class= "reporting_content_outer">
<div class = "report_options toolbar_cell">

<div id = "now_duration" class = "floated_ellipsed_divs" style = "width: 40%;"> Duration : <div id="now_showing_div" class="inline_display" title="Use the above links to change duration">
<?php
//Reverse functionality of set_unit() in reporting.js
function get_now_showing_text($last, $unit)
{
    if($last == 7 && $unit == DAYS)
    {
        $last = 1;
        $unit = WEEKS;
    }
    else if($last == 12 && $unit == MONTHS)
    {
        $last = 1;
        $unit = YEARS;
    }
    if($last == 1)
        return preg_replace("/s$/", "", $unit);
    return $last . " " . $unit;
}

if($stime_default_value != "" && $etime_default_value != "")
    print $stime_default_value . " to " . $etime_default_value;
else
    print "Last " . get_now_showing_text($last_default_value, $unit_default_value);
print "</div>";
if(!$is_default_data_source)
    print "&nbsp;[Data source: $ds]\n";
?>
</div>

<div id = "top_right_links_div">
<?php
$tabular_view_title = $is_tabular_view ? "Switch to graphical view" : "Switch to tabular view";
$tabular_view_text = $is_tabular_view ? "Graphical View" : "Tabular View";
$tabular_view_class = $is_tabular_view ? "dashboard_images graph_img pointer_cursor" : "dashboard_images table_img pointer_cursor";
?>

<div class = "floated_ellipsed_divs" style = "width: 130px;">
<a href="#noAnchor" id="switch_view_href" onclick="switch_view('trep', 'trep')" title="<?=$tabular_view_title?>">
<div id ="switch_view_div" class = "<?=$tabular_view_class?>"></div><?=$tabular_view_text?> </a>
</div>
    

<?php
if(!$is_default_data_source) ////Ideally, should be for imported data sources alone
{
    $show_events = !(isset($_COOKIE['hideevt']) && $_COOKIE['hideevt'] == "hideevt");
    $show_events_title = $show_events ? "Click to hide events on chart" : "Click to show events on chart";
    $show_events_text = $show_events ? "Hide events" : "Show events";
    $show_events_div_style = $is_tabular_view ? "style=\"width: 100px;display: none;\"" : "style = \"width: 100px;\"";
?>
    <div class = "floated_ellipsed_divs" id="show_events_div" <?=$show_events_div_style?>>
    <a href="#noAnchor" id="show_events_href" onclick="show_hide_events()" title="<?=$show_events_title?>">
    <div id = "show_events_href_img" class = "reporting_images events_img pointer_cursor"> </div> <?=$show_events_text?> </a></div>

<?php
}
?>

 <div class = "floated_ellipsed_divs" style = "width: 74px;">
 <a href="#noAnchor" onclick="print_page()" title="Click to print the report">
 <div class = "reporting_images print_img pointer_cursor"> </div>Print </a></div>


<?php
$is_default_group = (isset($_COOKIE['drep']) && $_COOKIE['drep'] == $name);
$default_group_title = $is_default_group ? "Remove as default group" : "Set as default group";
$default_group_class = $is_default_group ? "dashboard_images remove_default pointer_cursor" : "dashboard_images set_default pointer_cursor";
?>
<div class = "floated_ellipsed_divs" style = "width: 130px;">
<a href="#noAnchor" id="set_as_default_href" onclick="set_as_default('drep', report_internal_name)" title="<?=$default_group_title?>">
<div  id = "set_as_default_image" class = "<?=$default_group_class?>"></div>Default Report </a>
</div>


<?php if(!$internal) //Ideally should be for all imported data sources which don't need Refresh
{
?>
    <div class = "floated_ellipsed_divs" style = "width: 80px;">
    <a href="#noAnchor" onclick="refresh_all_images()" title="Click to refresh all report elements">
    <div class = "dashboard_images refresh_img pointer_cursor"> </div>Refresh</a></div>
<?php
}
?>
</div>
<div id="close_print_view_div" style="display: none">
    <a href="#noAnchor" style = "color: #5b5e62"  onclick="print_page()" title="Click to see the normal view">Close</a>
</div>


</div>
</div>



<?php
print "<div><table id=\"images_table\" align=\"center\" class=\"full_width_table\" cellspacing=\"0\">";
reporting_utils::print_images($elements, $entities, $errors, $cores, $ds, $numcpus, $last_default_value, $unit_default_value, $stime_default_value, $etime_default_value, $groups, $counters, $internal, $is_tabular_view);
print "</table></div>";
$images_count = count($elements);
print "\n<script language=\"Javascript\">";
print "\nvar numcpus = $numcpus;";
print "\nvar img_count = $images_count;";
print "\nvar new_image_index = " . ($images_count + 1) . ";";
print "\nvar chart_metrics = new Array();";
print "\nvar chart_properties = new Array();";
print "\nvar img_types = new Array();";
foreach($elements as $image)
    print "\nimg_types['img_" . $image['id'] . "'] = '" . report_definitions::get_report_element_graph_type($image) . "';";
reporting_customization::print_global_conf();
print "\n</script>";
?>
<span id="events_on_chart_span" class="events_on_chart_span"></span>
<div id="events_on_chart_div" class="popup events_on_chart_div">
    <table class="full_width_table">
        <tr>
            <td><b>Events:</b></td>
            <td align="right"><img src="/admin_ui/common/images/navigate_cross.png" alt="Close" title="Close" onclick="popup_hide('events_on_chart_div')"></td>
        </tr>
    </table>
    <div class="events_text_on_chart_div">
    <table class="full_width_table">
        <tr>
            <td id="events_text_on_chart_cell" nowrap>
            </td>
        </tr>
    </table>
    </div>
</div>
<div id="draw_rect_div" class="draw_rect_div"></div>
<div id="duration_menu_div" class="yui-skin-sam"></div>

</div>

</div>