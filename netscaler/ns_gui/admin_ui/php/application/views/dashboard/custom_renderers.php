<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH. "views/dashboard/renderers.php");
//These renderers override the header formatting of original renderers
//to have headers without background
class CustomOneColumnRenderer extends OneColumnRenderer
{
    protected function printHeader($header)
    {
        print "<tr>\n";
        print "\t<td width=\"60%\">" . $header[0] . "</td>\n";
        print "\t<td width=\"40%\"><div><b>" . $header[1] ."</b></div></td>\n";
        print "</tr>\n";
    }
}

class CustomRateRenderer extends RateRenderer
{
    protected function printHeader($header)
    {
        print "<tr>\n";
        print "\t<td width=\"60%\">" . $header[0] ."</td>\n";
        print "\t<td width=\"20%\"><div><b>" . $header[1] ."</b></div></td>\n";
        print "\t<td width=\"20%\"><div><b>" . $header[2] ."</b></div></td>\n";
        print "</tr>\n";
    }
}

//This renderer is used in Dashboard for Applications view
class CustomTabularRenderer
{
    private $show_enable_disable;

    protected function printHeader($app_group)
    {
        $title = $app_group['title'];
        $tooltip_suffix = $app_group['tooltip_suffix'];
        $counters = $app_group['summary_counters'];
        $format_dispname = $app_group['format_dispname'];
        $group_name = $app_group['cmdname'];

        print "<div align=\"center\">\n";
        print "<table class=\"ns_wp_body\">\n";
        print "\t<tr>\n";
        print "\t\t<td>" . $title . "</td>\n";
        print "\t</tr>\n";
        print "</table>\n";
        print "<div class=\"ns_entity_div\">\n";
        print "<table id=\"" . $group_name . "_sort_table\" class=\"ns_st_body full_width_table\" cellspacing=\"0\" cellpadding=\"0\">\n";
        print "\t<thead>\n";
        if($this->show_enable_disable)
            view_utils::print_state_check_box_header($app_group);
        $header_index = 0;
        foreach($counters as $i=>$counter)
        {
            if($i == 0)
            {
                view_utils::print_sortable_header($group_name, $header_index, "VALSTR", $counter['cntrdescr'], $counter['tooltip'] . $tooltip_suffix, true, true);
                if($format_dispname)
                {
                    $header_index++;
                    print "\t\t<th></th>\n";
                }
            }
            else
                view_utils::print_sortable_header($group_name, $header_index, isset($counter['type']) ? $counter['type'] : "", $counter['cntrdescr'], $counter['tooltip'] . $tooltip_suffix, false, true);
        }
        print "\t</thead>\n";
    }

    public function printBody($sel_entities, $result, $app_group, $appname)
    {
        $this->show_enable_disable = view_utils::does_command_support_enable_disable($app_group);
        $this->printHeader($app_group);

        $group_name = $app_group['cmdname'];
        $arg_name = $app_group['namearg'];
        $summary_counters = $app_group['summary_counters'];
        $detail_counters = $app_group['detail_counters'];
        $detail_display_handlers = $app_group['detail_display_handlers'];
        $format_dispname = $app_group['format_dispname'];

        print "\t<tbody>\n";
        $row_color = "";
        $i = 1;
        $sel_entities = explode(",", $sel_entities);
        foreach($sel_entities as $sel_entity)
        {
            $obj = $this->get_obj_in_result($result, $sel_entity, $arg_name);
            if($obj == null)
                continue;
            $row_color = ($row_color == "") ? StatisticsRenderer::getRowColor() : "";
            print "\t<tr>\n";
            if($this->show_enable_disable)
                view_utils::print_state_check_box_cell($row_color, $app_group, $i, $sel_entity);
            if($format_dispname)
            {
                $orig_sel_entity = $sel_entity;
                $sel_entity = StatisticsRenderer::getcntrvalue(view_utils::get_displayable_app_name($sel_entity, $appname), null);
                print "\t\t<td $row_color align=\"left\" style=\"border-right: none\">\n";
                print "\t\t\t<a title=\"Show details of '$sel_entity'\" href=\"#noAnchor\" onclick=\"javascript:show_hide_row('$group_name$i', this)\"><img id=\"" . $group_name . $i . "_img\" src=\"/admin_ui/common/images/navigate_open.png\" style=\"width: 11px; height: 11px; padding-right: 3px\" border=\"0\" alt=\"Show details of '$sel_entity'\"><span id=\"" . $group_name . "name" . $i . "\">$sel_entity</span></a>\n";
                print "\t\t</td>\n";
                print "\t\t<td $row_color align=\"center\" style=\"border-left: none\">\n";
                print "<a href=\"#noAnchor\" onclick=\"javascript:openwindow('/menu/stau/au/" . urlencode($orig_sel_entity) ."/appname/" . urlencode($appname) . "', '_blank', 'Statistics', 800, 600, true)\" title=\"View the " . BOUND_SERVICE . "s of '$sel_entity'\"><img src=\"/admin_ui/common/images/server.png\" border=\"0\" alt=\"View the " . BOUND_SERVICE . "s of '$sel_entity'\"></a>\n";
                print "\t\t</td>\n";
            }
            else
            {
                $sel_entity = StatisticsRenderer::getcntrvalue($sel_entity, null);
                print "\t\t<td $row_color align=\"left\">\n";
                print "\t\t\t<a title=\"Show details of '$sel_entity'\" href=\"#noAnchor\" onclick=\"javascript:show_hide_row('$group_name$i', this)\"><img id=\"" . $group_name . $i . "_img\" src=\"/admin_ui/common/images/navigate_open.png\" style=\"width: 11px; height: 11px; padding-right: 3px\" border=\"0\" alt=\"Show details of '$sel_entity'\"><span id=\"" . $group_name . "name" . $i . "\">$sel_entity</span></a>\n";
                print "\t\t</td>\n";
            }

            foreach($summary_counters as $summary_counter)
            {
                $cntrname = $summary_counter['cntrname'];
                if($cntrname == "name")
                    continue;
                if(isset($summary_counter['type'], $summary_counter['format'])
                    && $summary_counter['type'] == "VALIP_PORT_PROTOCOL"
                    && $summary_counter['format'] == true)
                {
                    print "\t\t<td $row_color><div id=\"$group_name$cntrname$i\">" . StatisticsRenderer::getcntrvalue($this->get_formatted_ip($obj, $cntrname, $summary_counter['port_counter'], $summary_counter['type_counter']), null). "</div>\n";
                }
                else
                {
                    print "\t\t<td $row_color align=\"right\"><div id=\"$group_name$cntrname$i\">" . StatisticsRenderer::process_value(StatisticsRenderer::getcntrvalue($obj[$cntrname], $summary_counter), true) . "</div>\n";
                }
                print "\t\t</td>\n";
            }
            print "\t</tr>\n";

            print "\t<tr style=\"display: none\" id=\"$group_name$i\">\n";
            //We need to find a better way to group display handlers in different cells/rows
            $dh_group = null;
            foreach($detail_display_handlers as $dh_index=>$dh)
            {
                if($dh_group == null)
                {
                    if($dh_index == 0)
                        print "\t\t<td colspan=\"" . $dh['colspan'] . "\" class=\"stapp_details stapp_details_first\" valign=\"top\">\n";
                    else
                        print "\t\t<td colspan=\"" . $dh['colspan'] . "\" class=\"stapp_details\" valign=\"top\">\n";
                }
                if($dh['format'] == "1C")
                    $renderer = new CustomOneColumnRenderer();
                else if($dh['format'] == "ANDRATE")
                    $renderer = new CustomRateRenderer();
                $renderer->div_suffix = $i;
                $renderer->printBody(array("cntrs"  =>  $detail_counters), $dh, array("List"   =>  array($obj)), 100, $group_name);
                if(isset($dh['group']))
                {
                    $dh_group = $dh;
                    print "\t\t<br>\n";
                }
                else
                {
                    $dh_group = null;
                    print "\t\t</td>\n";
                }
            };
            print "\t</tr>\n";
            $i++;
        }
        if($this->show_enable_disable)
        {
            print "\t</tbody>\n";
            print "</table><table align=\"left\"><tbody>\n";
            view_utils::print_state_enable_disable_buttons(count($summary_counters), $app_group);
        }
        view_utils::print_entities_count_state($app_group, $i);
        print "\t</tbody>\n";
        print "</table>\n";
        print "</div>\n";
        print "</div>\n";
    }

    private function get_obj_in_result($result, $name, $arg_name)
    {
        foreach($result['List'] as $result_obj)
        {
            if(isset($result_obj[$arg_name]) && StatisticsRenderer::getcntrvalue($result_obj[$arg_name], null) == $name)
                return $result_obj;
        }
        return null;
    }

    //Returns merged IP, port and protocol value from the result
    private function get_formatted_ip($result, $ip_counter, $port_counter, $protocol_counter)
    {
        //For IPV4 ':' is the delimiter between IP & port. For IPV6 '.' is the delimiter.
        $delimiter = ":";
        if(preg_match("/:/", $result[$ip_counter]))
            $delimiter = ".";
        return $result[$ip_counter] . $delimiter . $result[$port_counter] . " - " . $result[$protocol_counter];
    }

    public function print_xml($sel_entities, $result, $app_group, $appname)
    {
        $group_name = $app_group['cmdname'];
        $arg_name = $app_group['namearg'];
        $summary_counters = $app_group['summary_counters'];
        $detail_counters = $app_group['detail_counters'];
        $detail_display_handlers = $app_group['detail_display_handlers'];
        $format_dispname = $app_group['format_dispname'];

        $i = 1;
        $sel_entities = explode(",", $sel_entities);
        foreach($sel_entities as $sel_entity)
        {
            $obj = $this->get_obj_in_result($result, $sel_entity, $arg_name);
            if($obj == null)
                continue;
            if($format_dispname)
                $sel_entity = StatisticsRenderer::getcntrvalue(view_utils::get_displayable_app_name($sel_entity, $appname), null);
            else
                $sel_entity = StatisticsRenderer::getcntrvalue($sel_entity, null);
            view_utils::put_xml_entry($group_name."name".$i, $sel_entity, null);
            foreach($summary_counters as $summary_counter)
            {
                $cntrname = $summary_counter['cntrname'];
                if($cntrname == "name")
                    continue;
                if(isset($summary_counter['type'], $summary_counter['format'])
                    && $summary_counter['type'] == "VALIP_PORT_PROTOCOL"
                    && $summary_counter['format'] == true)
                {
                    $arg_val = $this->get_formatted_ip($obj, $cntrname, $summary_counter['port_counter'], $summary_counter['type_counter']);
                    view_utils::put_xml_entry($group_name.$cntrname.$i, $arg_val, $summary_counter, true);
                }
                else
                    view_utils::put_xml_entry($group_name.$cntrname.$i, $obj[$cntrname], $summary_counter, true);
            }
            foreach($detail_display_handlers as $dh_index=>$dh)
            {
                foreach($dh['counters'] as $cntrname)
                {
                    view_utils::put_xml_entry($group_name.$cntrname.$i, $obj[$cntrname], $detail_counters[$cntrname], true);
                    if($dh['format'] == "ANDRATE")
                    {
                        $rate_cntrname = view_utils::get_rate_counter($cntrname);
                        if(isset($obj[$rate_cntrname]))
                            view_utils::put_xml_entry($group_name.$rate_cntrname.$i, $obj[$rate_cntrname], $detail_counters[$rate_cntrname], true);
                    }
                }
            };
            $i++;
        }
        print "<info colname=\"$group_name\" val=\"" . ($i - 1) . "\"/>\n";
    }
}
?>