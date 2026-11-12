<?php if(!defined("BASEPATH")) exit("No direct script access allowed");
class graphical_view
{
    private $_ns_stat_definitions; //_ns_stat_definitions containing all stat command definitions
    private $cmds; //_ns_stat_definitions command object
    private $request_type; //PAGE_REQUEST or AJAX_REQUEST
    private $definition; //Could be a graphical_view_definition or comparative_chart_definition

    private $chart_properties = array();
    private $series = array();
    private $entities = array();        //List of all available entities (applicable only for entity based PAGE_REQUEST)
    private $entity_names = array();    //List of selected entities (applicable only for entity based PAGE_REQUEST)

    public function __construct($_ns_stat_definitions, $cmds, $request_type, $definition)
    {
        $this->_ns_stat_definitions = $_ns_stat_definitions;
        $this->cmds = $cmds;
        $this->request_type = $request_type;
        $this->definition = $definition;
    }

    private function initialize($results)
    {
        if(!$this->definition)
            return false;

        $timestamp = null;
        if(isset($this->definition["report_name"]))
            $this->chart_properties["link_url"] = "/menu/rep?name=" . $this->definition["report_name"];
        $properties = isset($this->definition["properties"]) ? $this->definition["properties"] : array();
        if(isset($properties["secondary_y_axis"]))
            $this->chart_properties["secondary_y_axis"] = $properties["secondary_y_axis"];
        if(isset($properties["plot_type"]))
            $this->chart_properties["type"] = $properties["plot_type"];

        foreach($this->cmds as $function => $cmd)
        {
            if(!isset($results[$function]))
                continue;
            $result = $results[$function];
            if(!view_utils::is_valid_stat_result($result, $this->request_type, false))
                return false;

            $is_entity = isset($cmd["namearg"]);
            $argname = $is_entity ? $cmd["namearg"] : null;

            if($is_entity && isset($this->definition["alt_expr"]))
                exit("No support for expression in entity based group");
            if($is_entity && $timestamp != null)
                exit("Entity based group cannot be clubbed with other global or entity based groups");

            if($is_entity && $this->request_type == AJAX_REQUEST && count($this->definition["ents"]) == 0)
                return false;

            $counter_name_to_compare_entities = $is_entity ? $this->definition["func_cntrs"][$function][0] : "";
            if($timestamp == null)
                $timestamp = $result["timestamp"] * 1000;
            $final_results = array();
            foreach($result["List"] as $obj)
            {
                if(isset($obj["entitytype"]) and ($obj["entitytype"] != $cmd["cmdname"]))
                    continue;
                if(!$is_entity)
                {
                    $final_results[] = $obj;
                    break;
                }
                $entity_name_from_result = $this->get_entity_name($obj, $argname);
                if($this->request_type == PAGE_REQUEST)
                    $this->entities[] = $entity_name_from_result;
                if($this->request_type == AJAX_REQUEST || (isset($this->definition["ents"]) && count($this->definition["ents"]) > 0))
                {
                    for($i = 0, $end_index = count($this->definition["ents"]); $i < $end_index; $i++)
                    {
                        if($entity_name_from_result == $this->definition["ents"][$i])
                        {
                            $final_results[] = $obj;
                            $this->entity_names[] = $entity_name_from_result;
                            break;
                        }
                    }
                }
                else
                {
                    if(count($final_results) == 0)
                    {
                        $final_results[] = $obj;
                        $this->entity_names[] = $entity_name_from_result;
                    }
                    else if($final_results[0][$counter_name_to_compare_entities] < $obj[$counter_name_to_compare_entities])
                    {
                        $final_results[0] = $obj;
                        $this->entity_names[0] = $entity_name_from_result;
                    }
                }
            }
            if(count($final_results) == 0)
                return false;

            if($this->request_type != AJAX_REQUEST && isset($this->definition["alt_expr"], $this->definition["alt_expr"][$function]))
            {
                if(!isset($this->definition["func_cntrs"]))
                    $this->definition["func_cntrs"] = array();
                $this->definition["func_cntrs"][$function] = eval(preg_replace("/RESULT/", "\$final_results[0]", $this->definition["alt_expr"][$function]));
            }
            if(!isset($this->definition["func_cntrs"][$function]))
                continue;
            $cntrs = $this->definition["func_cntrs"][$function];

            for($j = 0, $end_index_j = count($final_results); $j < $end_index_j; $j++)
            {
                for($i = 0, $end_index = count($cntrs); $i < $end_index; $i++)
                {
                    $counter_name = $cntrs[$i];
                    if(!isset($cmd["cntrs"][$counter_name]))
                        continue;

                    $series_name = view_utils::get_counter_descr($cmd["cntrs"][$counter_name], $cmd["cntrs"], true);
                    if($is_entity)
                        $series_name = $this->get_entity_name($final_results[$j], $argname) . " - " . $series_name;
                    $series_obj = array("name" => $series_name);
                    if(isset($properties[$i]) && $properties[$i]["plot_type"])
                        $series_obj["type"] = $properties[$i]["plot_type"];
                    $series_obj["data"] = array();
                    if(isset($final_results[$j][$counter_name]))
                        $series_obj["data"][] = array("x" => $timestamp, "y" => floatval($final_results[$j][$counter_name])); //Numeric data might be String in NITRO JSON response

                    $this->series[] = $series_obj;
                }
            }
        }
        return true;
    }

    public function render($results, $is_graphical_view = true, $comparative_chart = false, $counter_groups = null, $counters = null, $custom_chart = false)
    {
        if($this->request_type == PAGE_REQUEST)
        {
            $height = "height: " . ($comparative_chart ? "180px" : "435px");
            print "<div id=\"graphical_view_div\"" . (!$is_graphical_view ? " style=\"$height; display: none\"" : " style=\"$height\"") . "></div>\n";

            if(!$this->initialize($results))
                return;

            if($counter_groups && $counters)
            {
                $prefix = "counter_selector";
?>
<table class="full_width_table" cellspacing="0" cellpadding="0">
    <tr id="counter_selector_row" <?=!$is_graphical_view ? "style=\"display: none\"" : ""?>>
<?php
    if($comparative_chart && get_branding()->show_help())
    {
?>
        <td>
            <a href="#noAnchor" onclick="openwindow('/help/db/dashboard.htm#ns_html_dashboard_custom_charts_ref.html', 'Dashboard_Help2_<?= $_SESSION['rand'];?>', 'Help', screen.width, screen.height)" title="Click to obtain help related to this screen"><img id="help_image" src="/admin_ui/common/images/help.png" alt="Help" border="0"></a>&nbsp;
            <a href="#noAnchor" onclick="openwindow('/help/db/dashboard.htm#ns_html_dashboard_custom_charts_ref.html', 'Dashboard_Help2_<?= $_SESSION['rand'];?>', 'Help', screen.width, screen.height)" title="Click to obtain help related to this screen">Help</a>
        </td>
<?
    }
?>
        <td align="right">
            <table>
                <tr>
                    <td class="toolbar" align="right">
                        <div id = "counters_div" class = "floated_ellipsed_divs">
                            <a href="#noAnchor" onclick="popup_show('<?=$prefix;?>_counters_div', '<?=$prefix;?>_counters_span', 0, 10, null)" title="Change counters/entities">
                                <div id="<?=$prefix;?>_counters_span" title="Change counters/entities"class = "reporting_images conuters_img pointer_cursor" ></div>
                                Counters
                            </a>
                        </div>
<?php
    if($comparative_chart)
    {
?>
                        <div style = "width: 75px" class = "floated_ellipsed_divs">
                            <a href="#noAnchor" onclick="popup_show('save_chart_div', 'save_chart_span', 0, 10, null)" title="Save as a custom chart">
                                <div id="save_chart_span" title="Save as a custom chart" class = "dashboard_images save_as_img pointer_cursor" ></div>
                                Save
                            </a>
                        </div>
<?php
        if($custom_chart)
        {
?>                      
                          <div style = "width: 90px" class = "floated_ellipsed_divs">
                            <a href="#noAnchor" onclick="delete_chart()" title="Delete the custom chart">
                                <div title="Delete the custom chart" class = "dashboard_images delete_img pointer_cursor" ></div>
                                Delete
                            </a>
                        </div>
<?
        }
    }
?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<?php
    if($comparative_chart)
    {
?>
<div id="save_chart_div" class="popup counter_selector_popup">
    <table>
        <tr>
            <td><b>Save:</b></td>
            <td align="right">
                <img src="/admin_ui/common/images/navigate_cross.png" alt="Close" title="Close" onclick="popup_hide('save_chart_div')">
            </td>
        </tr>
        <tr>
            <td>Chart Name</td>
            <td><input type="textfield" id="save_chart_name_tf" size="<?=MAX_NO_OF_CHARS_IN_CHART_NAME?>" maxlength="<?=MAX_NO_OF_CHARS_IN_CHART_NAME?>" value="<?=$custom_chart ? $this->definition["name"] : ""?>" title="Enter name of the custom chart" onkeypress="return key_pressed(event, save_chart)" onFocus="this.select()"/></td>
        </tr>
        <tr>
            <td align="right" colspan="2">
                <input type="button" class="flat_button" value="OK" onclick="save_chart()" title="Click to save the chart in the specified name"/>
                <input type="button" class="flat_button" value="Close" onclick="popup_hide('save_chart_div')" title="Click to close"/>
            </td>
        </tr>
    </table>
</div>
<?
    }
?>
<div id="<?=$prefix;?>_counters_div" class="popup counter_selector_popup">
<?php
                require_once("counter_selector_view.php");
                $groups = array_keys($this->cmds);
                $counter_selector = new counter_selector_view($this->_ns_stat_definitions, $groups[0], $counter_groups, $counters, $this->entities, $prefix, !$comparative_chart);
                $counter_selector->render($this->definition["func_cntrs"], $this->entity_names);
                print "</div>\n";
                print "<script type=\"text/javascript\" src=\"/admin_ui/common/js/yui/yahoo-dom-event.js\"></script>\n";
                print "<script type=\"text/javascript\" src=\"/admin_ui/common/js/yui/dragdrop-min.js\"></script>\n";
                print "<script type=\"text/javascript\" src=\"/admin_ui/common/js/ns/list.js\"></script>\n";
                print "<script type=\"text/javascript\" src=\"/admin_ui/common/js/ns/popup.js\"></script>\n";
                print "<script type=\"text/javascript\" src=\"/admin_ui/dashboard/js/counter_selector.js\"></script>\n";
                print "<script type=\"text/javascript\">\n";
                print "\tvar max_no_of_series = " . MAX_NO_OF_SERIES . ";\n";
                print "</script>\n";
            }
            print "<link rel=\"stylesheet\" type=\"text/css\" href=\"/admin_ui/common/css/ns/chart/chart.css\">\n";
            print "<script type=\"text/javascript\" src=\"/admin_ui/common/js/highcharts/highcharts.js\"></script>\n";
            print "<script type=\"text/javascript\" src=\"/admin_ui/common/js/ns/chart/chart_constants.js\"></script>\n";
            print "<script type=\"text/javascript\" src=\"/admin_ui/common/js/ns/chart/chart_utils.js\"></script>\n";
            print "<script type=\"text/javascript\" src=\"/admin_ui/common/js/ns/chart/chart.js\"></script>\n";
            print "<script type=\"text/javascript\" src=\"/admin_ui/dashboard/js/graphical_view.js\"></script>\n";
            print "<script type=\"text/javascript\">\n";
            print "\tvar graphical_view_controller = new graphical_view(" . (count($this->chart_properties) > 0 ? json_encode($this->chart_properties) : "{}") . ", " .  json_encode($this->series) . ")\n";
            $funcs = array();
            $cntrs = array();
            foreach($this->definition["func_cntrs"] as $function => $func_cntrs)
            {
                $funcs[] = $function;
                $cntrs[] = implode(",", $func_cntrs);
            }
            print "\tcontroller.update_text_collector_args({\"funcs\": \"" . implode(";", $funcs) . "\", \"cntrs\": \"" . implode(";", $cntrs) . "\"";
            if(count($this->entity_names) > 0)
                print ", \"ents\": \"" . urlencode(preg_replace("/\//", "%2F", implode(",", $this->entity_names))) . "\"";
            print "});\n";
            print "</script>\n";
        }
        else if($this->request_type == AJAX_REQUEST)
        {
            if(!$this->initialize($results))
                return;

            $timestamp_printed = false;
            for($i = 0, $end_index = count($this->series); $i < $end_index; $i++)
            {
                if(count($this->series[$i]["data"]) == 0)
                    continue;
                if(!$timestamp_printed)
                {
                    print "<series name=\"__timestamp\" val=\"" . $this->series[$i]["data"][0]["x"]. "\"/>\n";
                    $timestamp_printed = true;
                }
                $name = $this->series[$i]["name"];
                $val = $this->series[$i]["data"][0]["y"];
                print "<series name=\"$name\" val=\"$val\"/>";
            }
        }
    }

    private function get_entity_name($result, $argname)
    {
        return preg_replace("/\"/", "", isset($result[$argname]) ? $result[$argname] : "0");
    }
}
?>