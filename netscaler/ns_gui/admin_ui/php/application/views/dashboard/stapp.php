<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once("custom_renderers.php");

if(isset($outputformat) && $outputformat == "DATA")
{
    header(XML_HEADER);
    $tabular_renderer = new CustomTabularRenderer();
    print "<messages>\n";
    $this->load->view('common/gauges');
    foreach($stapp_units as $stapp_unit)
    {
        $tabular_renderer->print_xml($stapp_unit->get_entities(), $stapp_unit->get_result(), $stapp_unit->get_group_definition(), $appname);
    }
    print "</messages>";
    exit(0);
}
$this->load->view("common/header");
print "<table align=\"center\" class=\"full_width_table\" cellspacing=\"0\" cellpadding=\"0\" style=\"table-layout: fixed\"><tr><td>\n";
$this->load->view('common/gauges');
print "\n</td></tr></table>";
print "<table class=\"ns_table_title\" align=\"center\" cellpadding=\"0\">\n";
print "\t<tr>\n";
print "\t\t<td class=\"full_width_table\"><hr></td>\n";
print "\t</tr>\n";
print "</table>\n";
view_utils::print_zoom_in_out_open_div();

//Print javascript and page title
print_javascript($base_url);
print_title($title);
view_utils::print_counter_tooltip_popup();

//Print each application units - CS VIPs (Public endpoints), LB VIPS (Application units) etc
$tabular_renderer = new CustomTabularRenderer();
foreach($stapp_units as $stapp_unit)
{
    $tabular_renderer->printBody($stapp_unit->get_entities(), $stapp_unit->get_result(), $stapp_unit->get_group_definition(), $appname);
    print "<br>\n";
}

view_utils::print_zoom_in_out_close_div();
$this->load->view("common/footer", array("allow_text_resize"  =>  true));

function print_title($title)
{
    print "\t\t\t<table class=\"ns_table_title\" align=\"center\">\n";
    print "\t\t\t\t<tr>\n";
    print "\t\t\t\t\t<td align=\"right\" class=\"relatively_less_font_size\">\n";
    print "\t\t\t\t\t\t<a href=\"#noAnchor\" onclick=\"reload_page()\" title=\"Refresh the page\"><img  src=\"/admin_ui/common/images/refresh.png\" alt=\"Refresh\" border=\"0\"></a>&nbsp;<a href=\"#noAnchor\" onclick=\"reload_page()\" title=\"Click to refresh the page\">Refresh</a>&nbsp;\n";
    print "\t\t\t\t\t\t<img id=\"progress_image\" src=\"/admin_ui/common/images/spacer.gif\" style=\"height: 16px !important; width: 16px !important\"/>";
    print "\t\t\t\t\t</td>\n";
    print "\t\t\t\t</tr>\n";
    print "\t\t\t</table>\n";
    print "\t\t\t<p class=\"ns_wp_header_plain\" align=\"center\">$title</p>\n";
}

function print_javascript($base_url)
{
//Print constants used in enable/disable operations
view_utils::print_state_check_constants();
view_utils::print_auto_refresh_javascript($_SESSION[GAUGES_PAGE_NUMBER_KEY], dashboard::post_params_to_string());
require_once(APPPATH."views/common/locale_utils.php");
?>
<script type="text/javascript">
var grouping_separator = '<?=locale_utils::get_grouping_separator()?>';
function show_hide_row(id, link_obj)
{
    var obj = document.getElementById(id);
    if(!obj.style || obj.style.display == "" || obj.style.display == display_flag)
    {
        link_obj.title = link_obj.title.replace(/Hide/, "Show");
        obj.style.display = "none";
        document.getElementById(id + "_img").src = "/admin_ui/common/images/navigate_open.png";
    }
    else
    {
        link_obj.title = link_obj.title.replace(/Show/, "Hide");
        obj.style.display = display_flag;
        document.getElementById(id + "_img").src = "/admin_ui/common/images/navigate_close.png";
    }
    document.getElementById(id + "_img").setAttribute("alt", link_obj.title);
}

//Overrides do_operation_result_arrived() in stats_controller.js
function do_operation_result_arrived()
{
    reload_page();
}

function reload_page()
{
    post_data("<?=$base_url . dashboard::post_params_to_string("/", "/")?>");
}
</script>
<script type="text/javascript" src="/admin_ui/common/js/ns/sort.js"></script>
<?php
} //End of print_javascript
?>