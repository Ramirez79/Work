<?php if(!defined("BASEPATH")) exit("No direct script access allowed");
require_once(APPPATH . "views/common/view_utils.php");

class counter_selector_view
{
    private $_ns_stat_definitions; //_ns_stat_definitions containing all stat command definitions
    private $group; //_ns_stat_definitions command object
    private $counter_groups;    //Array of "counter_group" objects
    private $counters;  //Array of all "counter" objects
    private $entities;  //Array of all "entity" names
    private $prefix;    //Prefix to be used in "id" of DOM elements
    private $graphical_view; //Set to true for graphical view, false for comparative & custom charts

    public function __construct($_ns_stat_definitions, $group, $counter_groups, $counters, $entities, $prefix, $graphical_view)
    {
        $this->_ns_stat_definitions = $_ns_stat_definitions;
        $this->group = $group;
        $this->counter_groups = $counter_groups;
        $this->counters = $counters;
        $this->entities = $entities;
        $this->prefix = $prefix;
        $this->graphical_view = $graphical_view;
    }

    //$sel_counter_names - array of selected counter names indexed by group name Ex: array("statns" => array("cpuusage"))
    //$sel_entities - array of selected entity names
    public function render($sel_counter_names = array(), $sel_entities = null)
    {
        global $g_argval;
        $hide_entities_tab = !ns_empty($g_argval);
        $is_an_entity_based_group = view_utils::is_an_entity_based_function($this->group);
        $style_for_group_selection = $this->graphical_view ? "style=\"display: none\"" : "";
        print "<table id=\"" . $this->prefix . "_table\">";
        print "<tr id=\"" . $this->prefix . "_counters_drag_handle\" class=\"draggable\"><td align=\"right\"><img src=\"/admin_ui/common/images/navigate_cross.png\" alt=\"Close\" title=\"Close\" align=\"right\" onclick=\"popup_hide('" . $this->prefix . "_counters_div')\"></td></tr>";
        print "<tr $style_for_group_selection><td align=\"left\"><table class=\"negative_margin_left\"><tr id=\"" . $this->prefix . "_groups_radio_row\"><td>Plot chart for</td><td><input type=\"radio\" id=\"" . $this->prefix . "_groups_global_radio\" name=\"" . $this->prefix . "_groups_radio\" value=\"global\" title=\"Click to view global groups\" onclick=\"group_radio_changed('" . $this->prefix . "', this)\" ";
        if($is_an_entity_based_group)
            print ">";
        else
            print "checked>";
        print "System global statistics <input type=\"radio\"  id=\"" . $this->prefix . "_groups_entity_radio\" name=\"" . $this->prefix . "_groups_radio\" value=\"entity\" title=\"Click to view entity based groups\" onclick=\"group_radio_changed('" . $this->prefix . "', this)\" ";
        if(!$is_an_entity_based_group)
            print ">";
        else
            print "checked>";
        print "System entities statistics </td></tr>";
        print "<tr id=\"" . $this->prefix . "_counters_group_global_row\" ";
        if($is_an_entity_based_group)
            print "style=\"display: none\">\n";
        else
            print ">\n";
        print "<td>Select group</td><td>";
        print "<select id=\"" . $this->prefix . "_group_global_combo\" class=\"group_combo\" onchange=\"group_cmb_changed('" . $this->prefix . "', this.value)\">\n";
        $ent_groups = array();
        $license_map = isset($_SESSION["licenseMap"]) ? $_SESSION["licenseMap"] : array();
        foreach($this->counter_groups as $group)
        {
            #If a feature doesn't require licensing, skip the license check
            $license = $group->get_license();
            if(!empty($license))
            {
                $licensed = 1;
                foreach($license as $lic)
                {
                    if(!isset($license_map[$lic]))
                    {
                        $licensed = 0;
                        break;
                    }
                }
                if(!$licensed)
                    continue;
            }
            $name = $group->get_name();
            if(view_utils::is_an_entity_based_function($name))
            {
                $ent_groups[] = $group;
                continue;
            }
            $descr = $group->get_descr();
            if($name != $this->group)
                print "<option value=\"$name\">$descr</option>\n";
            else
                print "<option selected value=\"$name\">$descr</option>\n";
        }
        print "</select>";
        print "</td></tr>";
        print "<tr id=\"" . $this->prefix . "_counters_group_entity_row\" ";
        if(!$is_an_entity_based_group)
            print "style=\"display: none\">\n";
        else
            print ">\n";
        print "<td>Select group</td><td>";
        print "<select id=\"" . $this->prefix . "_group_entity_combo\" class=\"group_combo\" onchange=\"group_cmb_changed('" . $this->prefix . "', this.value)\">\n";
        foreach($ent_groups as $group)
        {
            $name = $group->get_name();
            $descr = $group->get_descr();
            if ($name != $this->group)
                print "<option value=\"$name\">$descr</option>\n";
            else
                print "<option selected value=\"$name\">$descr</option>\n";
        }
        print "</select>";
        print "</td></tr></table></td></tr>";
        if($hide_entities_tab)
            print "<tr><td><table class=\"negative_margin_left\"><tr><td>Entity name&nbsp;</td><td><input type=\"textfield\" value=\"$g_argval\" class=\"chart_name_input\" readonly/></td></tr></table></td></tr>";
        print "<tr><td align=\"left\" valign=\"top\" class=\"bottom_border\">";
        print "<div class=\"tab\" align=\"left\">";
        print "<span id=\"" . $this->prefix . "_tab_0\" class=\"current_tab\"><a href=\"#noAnchor\" onclick=\"switch_tab('$this->prefix', 0)\"><span>Counters</span></a></span>";
        if($is_an_entity_based_group && !$hide_entities_tab)
            print "<span id=\"" . $this->prefix . "_tab_1\" class=\"\"><a href=\"#noAnchor\" onclick=\"switch_tab('$this->prefix', 1)\"><span>Entities</span></a></span>";
        print "</div></td></tr>";

        print "<tr id=\"" . $this->prefix . "_counters_table_row\"><td align=\"left\" nowrap>";
        print "<table id=\"" . $this->prefix . "_counters_table\" cellpadding=\"0\" cellspacing=\"0\" class=\"full_width_table\">";
        print "<tr><td>";
        print "<table class=\"full_width_table\" cellpadding=\"0\" cellspacing=\"0\"><tr><td>Available</td><td align=\"right\">";
        print "<img src=\"/admin_ui/common/images/view.png\" alt=\"Show search panel\" id=\"" . $this->prefix . "_cntrs_avail_list_find_img\" title=\"Show search panel\" class=\"pointer_cursor\" onclick=\"show_hide_find_panel('" . $this->prefix . "_cntrs_avail_list', true)\"></td></tr>";
        self::print_find_panel_for_list($this->prefix . "_cntrs_avail_list");
        print "</table>";
        print "<select id=\"" . $this->prefix . "_cntrs_avail_list\" size=\"10\" multiple onchange=\"set_state('" . $this->prefix . "_cntrs', this, true)\" class=\"counters_list_box\" ondblclick=\"move_items('" . $this->prefix . "', '_cntrs', true, false)\">\n";

        $sel_counters = self::create_counters_objects($sel_counter_names);
        foreach($this->counters as $counter)
        {
            $counter_name = $counter->get_name();
            $counter_desc = $counter->get_descr();
            $counter_tooltip = $counter->get_tooltip();
            $counter_func = $counter->get_group();
            if(!view_utils::value_exists_in_array($sel_counters, $counter))
                print "<option value=\"" . $counter_name . " " . $counter_func . "\" title=\"$counter_tooltip\">$counter_desc</option>\n";
        }
        print "</select></td><td class=\"padding_right_left\">\n";
        print "<input type=\"button\" id=\"" . $this->prefix . "_cntrs_move_right_button\" class=\"counters_move_button\"  value=\">\" onclick=\"move_items('" . $this->prefix . "', '_cntrs', true, false)\" disabled=\"true\"/><br>\n";
        print "<input type=\"button\" id=\"" . $this->prefix . "_cntrs_move_left_button\" class=\"counters_move_button\"  value=\"<\" onclick=\"move_items('" . $this->prefix . "', '_cntrs', false, false)\" disabled=\"true\"/><br>\n";
        print "<input type=\"button\" id=\"" . $this->prefix . "_cntrs_move_all_left_button\" class=\"counters_move_button\"  value=\"<<\" onclick=\"move_items('" . $this->prefix . "', '_cntrs', false, true)\"/>\n";
        print "</td><td>Selected";
        print "<br><select id=\"" . $this->prefix . "_cntrs_sel_list\" size=\"10\" multiple onchange=\"set_state('" . $this->prefix . "_cntrs', this, false)\" class=\"counters_list_box\" ondblclick=\"move_items('" . $this->prefix . "', '_cntrs', false, false)\">\n";
        foreach($sel_counters as $counter)
        {
            $counter_name = $counter->get_name();
            $counter_desc = $counter->get_descr();
            $counter_tooltip = $counter->get_tooltip();
            $counter_func = $counter->get_group();
            print "<option value=\"" . $counter_name . " " . $counter_func . "\" title=\"$counter_tooltip\">$counter_desc</option>\n";
        }
        print "</select></td></tr></table></td></tr>\n";
        $ents_avail_list_id = "";
        if($is_an_entity_based_group)
        {
            print "<tr id=\"" . $this->prefix . "_entities_table_row\" style=\"display: none\"><td align=\"left\" nowrap>";
            print "<table id=\"" . $this->prefix . "_entities_table\" cellpadding=\"0\" cellspacing=\"0\" class=\"full_width_table\">";
            print "<tr><td><table class=\"full_width_table\"  cellpadding=0 cellspacing=0><tr><td>Available</td><td align=\"right\">";
            $ents_avail_list_id = $this->prefix . "_ents_avail_list";
            print "<img src=\"/admin_ui/common/images/view.png\" alt=\"Show search panel\" id=\"" . $ents_avail_list_id . "_find_img\" title=\"Show search panel\" class=\"pointer_cursor\" onclick=\"show_hide_find_panel('$ents_avail_list_id', true)\"></td></tr>";
            self::print_find_panel_for_list($ents_avail_list_id);
            print "</table>";
            print "<select id=\"$ents_avail_list_id\" size=\"10\" multiple onchange=\"set_state('" . $this->prefix . "_ents', this, true)\" class=\"counters_list_box\" ondblclick=\"move_items('" . $this->prefix . "', '_ents', true, false)\">\n";
            if(!ns_empty($this->entities))
            {
                if(!isset($sel_entities))
                    $sel_entities = array();
                sort($this->entities);
                foreach($this->entities as $entity)
                {
                    if(!view_utils::value_exists_in_array($sel_entities, $entity))
                        print "<option value=\"$entity\" title=\"$entity\">$entity</option>\n";
                }
            }
            print "</select>";
            print "</td><td class=\"padding_right_left\">\n";
            print "<input type=\"button\" id=\"" . $this->prefix . "_ents_move_right_button\" class=\"counters_move_button\"  value=\">\" onclick=\"move_items('" . $this->prefix . "', '_ents', true, false)\" disabled=\"true\"/><br>\n";
            print "<input type=\"button\" id=\"" . $this->prefix . "_ents_move_left_button\" class=\"counters_move_button\"  value=\"<\" onclick=\"move_items('" . $this->prefix . "', '_ents', false, false)\" disabled=\"true\"/><br>\n";
            print "<input type=\"button\" id=\"" . $this->prefix . "_ents_move_all_left_button\" class=\"counters_move_button\"  value=\"<<\" onclick=\"move_items('" . $this->prefix . "', '_ents', false, true)\"/>\n";
            print "</td><td>Selected<br><select id=\"" . $this->prefix . "_ents_sel_list\" size=\"10\" multiple onchange=\"set_state('" . $this->prefix . "_ents', this, false)\" class=\"counters_list_box\" ondblclick=\"move_items('" . $this->prefix . "', '_ents', false, false)\">\n";
            if(isset($sel_entities) && !ns_empty($this->entities))
            {
                foreach($sel_entities as $entity)
                {
                    if($sel_entity = view_utils::value_exists_in_array($this->entities, $entity))
                        print "<option value=\"$entity\" title=\"$entity\">$entity</option>\n";
                }
            }
            print "</select></td></tr></table></td></tr>\n";
        }
        print "<tr><td><table class=\"counters_popup_status\" cellpadding=\"0\" cellspacing=\"0\"><tr><td id=\"" . $this->prefix . "_cntrs_status_cell\">";
        if(get_branding()->show_help())
        {
            print "<a href=\"#noAnchor\" onclick=\"openwindow('/help/db/dashboard.htm#ns_html_dashboard_counters_entities_dialog_box_ref.html', 'Dashboard_Help3_" . $_SESSION["rand"] . "', 'Help', screen.width, screen.height)\" title=\"Click to obtain help related to this popup\"><img id=\"help_image\" src=\"/admin_ui/common/images/help.png\" alt=\"Help\" border=\"0\"></a>&nbsp;";
            print "<a href=\"#noAnchor\" onclick=\"openwindow('/help/db/dashboard.htm#ns_html_dashboard_counters_entities_dialog_box_ref.html', 'Dashboard_Help3_" . $_SESSION["rand"] . "', 'Help', screen.width, screen.height)\" title=\"Click to obtain help related to this popup\">Help</a>&nbsp;";
        }
        print "</td><td align=\"right\" class=\"padding_top\">";
        print "<input type=\"button\" class=\"flat_button\" value=\"OK\" onclick='hide_find_panel(\"" . $this->prefix . "_cntrs_avail_list\", true);hide_find_panel(\"$ents_avail_list_id\", true); popup_hide(\"" . $this->prefix . "_counters_div\", true);refresh_chart(\"$this->prefix\")' title=\"Click to view chart\"/>&nbsp;&nbsp;";
        print "<input type=\"button\" class=\"flat_button\" value=\"Close\" onclick='popup_hide(\"" . $this->prefix . "_counters_div\")' title=\"Click to close\"/>";
        print "</td></tr></table></td></tr>";
        print "</table>";
        print "<script type=\"text/javascript\">\n";
        print "var prefix = '" . $this->prefix . "'";
        print "</script>\n";
    }

    private function create_counters_objects($func_cntrs)
    {
        $sel_counters = array();
        foreach($func_cntrs as $function => $cntrs)
        {
            $nsstat_cmd = $this->_ns_stat_definitions->get_command($function);
            $nsstat_cntrs = $nsstat_cmd["cntrs"];
            foreach($cntrs as $counter_name)
            {
                if(!isset($nsstat_cntrs[$counter_name]))
                {
                    $sel_counters[] = new counter($counter_name, $counter_name, $counter_name, $function);
                    continue;
                }
                $cntr = $nsstat_cntrs[$counter_name];
                $counter_desc = view_utils::get_counter_descr($cntr, $nsstat_cntrs);
                $counter_tooltip = isset($cntr["tooltip"]) ? $cntr["tooltip"] : $counter_desc;
                $sel_counters[] = new counter($counter_name, $counter_desc, $counter_tooltip, $function);
            }
        }
        return $sel_counters;
    }

    private static function print_find_panel_for_list($list_id)
    {
        print "<tr id=\"" . $list_id . "_find_row\" style=\"display: none\"><td class=\"find_panel_reporting\">";
        print "Criterion <select id=\"" . $list_id . "_find_row_criterion\" title=\"Select the search criterion\" class=\"criterion_reporting\" onchange=\"filter_list('$list_id')\">";
        print "<option>contains</option>";
        print "<option>not contains</option>";
        print "<option>starts with</option>";
        print "<option>ends with</option>";
        print "</select>";
        print " Text <input type=\"textfield\" size=\"8\" maxlength=\"128\" id=\"" . $list_id . "_find_row_search_text\" title=\"Enter the search text (case insensitive)\" onpropertychange=\"return filter_list('$list_id')\" oninput=\"return filter_list('$list_id')\" class=\"search_text_reporting\">";
        print "</td><td align=\"right\"><img src=\"/admin_ui/common/images/navigate_cross.png\" alt=\"Hide search panel\" title=\"Hide search panel\" class=\"pointer_cursor\" onclick=\"show_hide_find_panel('$list_id', true)\">";
        print "</td></tr>";
    }
}
?>