<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
global $g_chart_list;
$g_chart_list = array();
if(!isset($http_result, $event_result))
    return;

require_once(APPPATH. "views/dashboard/renderers.php");
require_once("view_utils.php");

define("THROUGHPUT_RX", "throughput_rx");
define("THROUGHPUT_TX", "throughput_tx");
define("HTTP_REQUESTS_PER_SEC", "http_requests_per_second");

global $g_function;
global $g_descriptivename;
global $g_detailview;
global $g_http_result;
global $g_event_result;
global $g_request;
global $g_load_links_in_frame;

$g_function = $func;
$g_descriptivename = view_utils::get_stats_group_array();
$g_detailview = "NO";
$g_http_result = $http_result;
$g_event_result = $event_result;
$g_request = ($is_ajax_request ? AJAX_REQUEST : PAGE_REQUEST);
$g_load_links_in_frame = isset($load_links_in_frame) ? $load_links_in_frame : null;
if($is_ajax_request)
{
    print "<message colname=\"" . THROUGHPUT_RX . "\" val=\"" . calculate_throughput(true) . "\"/>\n";
    print "<message colname=\"" . THROUGHPUT_TX . "\" val=\"" . calculate_throughput(false) . "\"/>\n";
    print "<message colname=\"" . HTTP_REQUESTS_PER_SEC . "\" val=\"" . get_http_requests_rate() . "\"/>\n";
    print_system_events_xml();
}
else
    print_gauges();

function print_gauges()
{
    require_once(APPPATH. "views/common/chart_classes.php");
    $chart_obj = chart_factory::get_chart_object("dial");
    print "<div id=\"gauges_div\">\n";
    print "<div id =\"gauges\" class=\"gauges\">\n";
    print "<div class=\"dials_div \">";
    print_dials();
    print "</div>";
    print "<div id =\"sys_events\" class=\"sys_events\">";
    print "<div class =\"sys_events_list\">";
    print_system_events_html();
    print "</div></div></div>";
    print "<div class=\"throughput_container\">";
    print_throughput($chart_obj->height);
    print "</div>\n";
    print "</div>\n";
}

function print_dials()
{
    global $g_load_links_in_frame;
    print "<div class =\"dials_span\"> ";
    view_utils::print_chart_url("cpu_mem_dial", $g_load_links_in_frame);
    print "</div>";
}

function print_throughput($height)
{
    $link_title = get_link_title("statinterface");
    print "<div class =\"throughput_table\">";
    print "<div id =\"throughput_title\" class =\"throughput_title\">";
    print "<div title =\"$link_title\" style =\"line-height:40px\" >";
    if($link_title)
        print_link("statinterface");
    print "Throughput";
    print "<span class=\"throughput_unit\"> (Mbps)</span>";
    if($link_title)
        print "</a>";
    print "</div></div>";
    print "<div class =\"throughput_data\">";
    print "<div class=\"throughput_input_data\"><div class=\"throughput_label\">In<span id=\"".THROUGHPUT_RX."\" class=\"throughput_number\">".calculate_throughput(true)."</span></div></div>";
    print "<div class=\"throughput_output_data\"><div class=\"throughput_label\">Out<span id=\"".THROUGHPUT_TX."\" class=\"throughput_number\">".calculate_throughput(false)."</span></div></div>";
    print "</div>";
    print "</div>"; 

    $link_title_http = get_link_title("statprotocolhttp");
    print "<div class =\"throughput_table http_stat_table\">";
    print "<div id =\"http_stat_title\" class =\"throughput_title\">";
    print "<div title = \"$link_title\" style =\"line-height:40px\" >";
    if($link_title_http)
        print_link("statprotocolhttp");
    print "HTTP Requests/s";
    if($link_title_http)
        print "</a>";
    print "</div></div>";
    print "<div class=\"throughput_number\"><span id=\"" . HTTP_REQUESTS_PER_SEC . "\">" . get_http_requests_rate() . "</span></div>";
    print "</div>";
}

function print_link($function)
{
    global $g_load_links_in_frame;
    if($g_load_links_in_frame != null)
        print "<a href='/menu/stc?func=$function' target=\"$g_load_links_in_frame\">";
    else
        print "<a href='/menu/st?cfunc=$function'>";
}

function get_link_title($link_function)
{
    global $g_descriptivename;
    return "Click to view " . $g_descriptivename[$link_function];
}

function calculate_throughput($rx)
{
    global $g_http_result;
    global $g_request;
    if(!view_utils::is_valid_stat_result($g_http_result, $g_request, false))
        return ($g_request == AJAX_REQUEST) ? htmlspecialchars("&nbsp;") : "&nbsp;";
    $cntr_name = $rx ? "rxmbitsrate" : "txmbitsrate";
    $total = 0;
    foreach($g_http_result['List'] as $obj)
        return isset($obj[$cntr_name]) ? StatisticsRenderer::getcntrvalue($obj[$cntr_name], null) : "";
}

function get_http_requests_rate()
{
    global $g_http_result;
    global $g_request;
    if(!view_utils::is_valid_stat_result($g_http_result, $g_request, false))
        return ($g_request == AJAX_REQUEST) ? htmlspecialchars("&nbsp;") : "&nbsp;";
    foreach($g_http_result['List'] as $obj)
        return isset($obj['httprequestsrate']) ? StatisticsRenderer::getcntrvalue($obj['httprequestsrate'], null) : "";
}

function print_system_events_html()
{
    print "<div id=\"system_log_table\">";
    print "<div class=\"system_log_div\" ><div class=\"system_log_title\">System Log</div>";
    print "<div class=\"search_panel_div\">";
    print "<div class = \"system_log_img\" style = \"display: inline-block\">";
    print "<div class=\"dashboard_images search_img pointer_cursor\" id=\"system_events_list_find_img\" alt=\"Show search panel\" title=\"Show search panel\" class=\"pointer_cursor\" onclick=\"show_hide_find_panel('system_events_list', 'false', 'inline-block')\">";
     print "</div></div>";
    print_find_panel_for_list('system_events_list');
    print "</div>";
    if((isset($_COOKIE['uatz']) && ($_COOKIE['uatz'] == "uatz")) || (isset($_SESSION['timezone_offset']) && ($_SESSION['timezone_offset'] == 0)))
        print " (GMT" . date('P') .")";
    print "</div>";
    
    print "<div class=\"div_for_list\"><select id=\"system_events_list\" size=\"6\" ns-min-size=\"6\" multiple onkeyup=\"on_scroll_list(this)\">";
    print_system_events_options();
    print "</select></div>";
    print "</div>";   
}

function print_find_panel_for_list($list_id)
{
    print "<script type=\"text/javascript\" src=\"/admin_ui/common/js/ns/list.js\">\n";
    print "</script>\n";
    print "<div id=\"" . $list_id . "_find_row\" style=\"display: none\" class =\"list_find\">";
    print "<div class = \"find_panel_class\">";
    print "<select id=\"" . $list_id . "_find_row_criterion\" title=\"Select the search criterion\"  onchange=\"filter_list('$list_id')\">";
    print "<option>contains</option>";
    print "<option>not contains</option>";
    print "<option>starts with</option>";
    print "<option>ends with</option>";
    print "</select>";
    print "<input type=\"textfield\" maxlength=\"128\" id=\"" . $list_id . "_find_row_search_text\" title=\"Enter the search text (case insensitive)\" onpropertychange=\"return filter_list('$list_id')\" oninput=\"return filter_list('$list_id')\">";
    print "</div></div>";
}

function print_system_events_xml()
{
    print "<list id=\"system_events_list\">";
    print_system_events_options();
    print "</list>";
}

function print_system_events_options()
{
    global $g_event_result;
    global $g_request;
    if(!view_utils::is_valid_stat_result($g_event_result, $g_request, false))
        return;
    foreach($g_event_result['List'] as $obj)
    {
        if(!is_array($obj))
            continue;
        $event_no = $obj["eventno"];
        $event_time = view_utils::convert_local_to_client_time($obj["time"], 'D M j H:i:s');
        $event_text = preg_replace("/\"/", "", $obj["text"]);
        $event_text = preg_replace("/\\\'/", "'", $event_text);
        $style = "";
        if(!(stripos($event_text, NS_S_UP) === false) || !(stripos($event_text, NS_S_ENABLED) === false))
            $style = "style=\"color: #219f0d\"";
        if(!(stripos($event_text, NS_S_DOWN) === false) || !(stripos($event_text, THROUGHPUT_RATE_LIMIT) === false) || !(stripos($event_text, PPS_LIMIT) === false))
            $style = "style=\"color: #ff0000\"";
        else if(!(stripos($event_text, NS_S_OFS) === false) || !(stripos($event_text, NS_S_DISABLED) === false))
            $style = "style=\"color: #ffcc33\"";
        print "<option value=\"$event_no\" title=\"$event_text\" $style>$event_time $event_text</option>";
    }
}
?>