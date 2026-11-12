<?php if (!defined("BASEPATH")) exit("No direct script access allowed");
require_once(APPPATH . "views/common/view_utils.php");
if($is_ajax_request)
{
    header(XML_HEADER);
    print "<messages>\n";
    require_once("graphical_view.php");
    $graphical_view = new graphical_view($_ns_stat_definitions, $cmds, AJAX_REQUEST, $comparative_chart);
    $graphical_view->render($results, true, true);
    print "</messages>\n";
    return;
}
$this->load->view("common/header");
?>
<style type="text/css">
.ns_body
{
    min-width: 330px !important;
}

.ns_content
{
    padding: 0px;
}
</style>
<script type="text/javascript" src="/admin_ui/common/js/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="/admin_ui/dashboard/js/dashboard.js"></script>
<script type="text/javascript" src="/admin_ui/dashboard/js/comparative_chart.js"></script>
<?php

if(!$popup_window)
{
    print "<div class=\"dashboard_block_title\" >";
    print "<div class=\"comparative_chart_select_div\" >";
    print "<select id=\"coc_combo\" class=\"header_select\" onchange=\"if(this.value) window.location.href = this.value;\">\n";
    print_combo($custom_chart->get_charts(), $name, $id);
    print_combo($comparative_chart_definitions->get_comparative_charts(), $name, $id);
    print "</select></div>";
    print "<div class = \"comparative_chart_popout\">";
    print "<a href=\"#noAnchor\" class=\"comparative_chart\" onclick=\"openwind('/menu/coc?id=$id&name=$name&popup=yes', 'COC_$id')\" title=\"Open in a new window, manage custom charts.\">\n";
    print "<div class = \"dashboard_images popout_img pointer_cursor\" style= \"margin-bottom: -2px\"> </div>";
    print "</a></div>";
    print "</div>";
}
else
{
    print "<table class=\"inline_display dashboard_block_title\" cellpadding=\"0\" cellspacing=\"0\"><tr><td></td><td><select id=\"coc_combo\" class=\"coc_combo coc_combo_popup\" onchange=\"if(this.value) window.location.href = this.value + '&popup=yes';\">\n";
    print_combo($custom_chart->get_charts(), $name, $id);
    print_combo($comparative_chart_definitions->get_comparative_charts(), $name, $id);
    print "</select></td></tr></table>\n";
}

require_once(APPPATH."views/common/locale_utils.php");
print  "<script type=\"text/javascript\">\n";
print "var grouping_separator = '" . locale_utils::get_grouping_separator() . "';\n";
print "var decimal_separator = '" . locale_utils::get_decimal_separator() . "';\n";
print "var popup_window = '" . $popup_window ."';\n";
print "var coc_id = '" . $id ."';\n";
print "var success_result = '" . SUCCESS_RESULT . "';\n";
print "var chart_already_exists_result = '" . CHART_ALREADY_EXISTS_RESULT . "';\n";
print "var chart_display_name = '" . ($custom ? $comparative_chart["name"] : "") . "';\n";
print "</script>\n";

view_utils::print_auto_refresh_javascript(-1);

require_once("graphical_view.php");
$graphical_view = new graphical_view($_ns_stat_definitions, $cmds, PAGE_REQUEST, $comparative_chart);
$graphical_view->render($results, true, true, isset($counter_groups) ? $counter_groups : null, isset($counters) ? $counters : null, $custom);

function print_combo($nodes, $name, $id)
{
    foreach($nodes as $node)
    {
        $node_name = $node["name"];
        if(isset($node["nodes"]))
        {
            print "<option value=\"\" class=\"node\" disabled=\"disabled\">[$node_name]</option>\n";
            print_combo($node["nodes"], $name, $id);
        }
        else
        {
            $key = isset($node["key"]) ? $node["key"] : $node_name;
            print "<option " . ($name == $key ? "selected " : "") . "value=\"/menu/coc?id=$id&name=$key\">$node_name</option>\n";
        }
    }
}
?>