<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH."views/common/view_utils.php");
require_once("reporting_customization.php");

// Utility functions used in reporting
class reporting_utils
{
    function get_cntrs_for_chart($element, $cores, $ds, $numcpus)
    {
        try
        {
            if(isset($element['alt_expr']))
                return eval($element['alt_expr']);
        }
        catch(Exception $e) {}

        return $element['cntrs'];
    }

    function print_images($elements, $entities, $errors, $cores, $ds, $numcpus, $last_default_value, $unit_default_value, $stime_default_value, $etime_default_value, $groups, $avail_counters, $internal, $is_tabular_view)
    {
        $images_count = count($elements);

        foreach($elements as $image)
        {
            $img_id = "img_" . $image['id'];
            $img_func = $image['func'];
            $img_cntrs = self::get_cntrs_for_chart($image, $cores, $ds, $numcpus);
            //This section of forming image row should be in sync with reporting.js
            $style_for_graphical_view = $is_tabular_view ? "style=\"display: none\"" : "";
            $style_for_tabular_view = !$is_tabular_view ? "style=\"display: none\"" : "";
            print "<tr id=\"images_table_" . $img_id . "_row\" $style_for_graphical_view><td class=\"padding_top\" align=\"center\">";
            print "<img id=\"" . $img_id  . "\" src=\"\"";
            $img_src = "get_image_url_with_width('/menu/repchart?func=" . $img_func . "&id=" . $img_id . "&type=" . report_definitions::get_report_element_graph_type($image);
            //If start and end time are there, use them else take last and unit
            if($stime_default_value != "" && $etime_default_value != "")
            {
                $img_src .= "&stime=" . $stime_default_value;
                $img_src .= "&etime=" . $etime_default_value;
            }
            else
                 $img_src .= "&last=" . $last_default_value . "&unit=" . $unit_default_value;

            $img_src .= "&ds=" . $ds;
            $cores_count = 1;

            if(!ns_empty($cores) && isset($image['cores']))
            {
                if($image['cores'] == ALL)
                {
                    $cores_count = count(explode(",", $cores));
                    $img_src .= "&cores=" . $cores;
                }
                else
                {
                    $cores_count = count($image["cores"]);
                    $img_src .= "&cores=" . implode(",", $image['cores']);
                }
            }

            if(!ns_empty($entities[$image['id']]))
            {
                $ents = $entities[$image['id']];
                if(isset($image['ents']))
                {
                    if($image["ents"] == ALL)
                    {
                        $image["ents"] = array();
                        $cntrs_count = count(explode(",", $img_cntrs));
                        $ents_count = count($ents);
                        $allowed_ents_count = floor(MAX_NO_OF_DATA_SETS/($cntrs_count * $cores_count));
                        for($i = 0; $i < $allowed_ents_count && $i < $ents_count; $i++)
                            $image["ents"][] = $ents[$i]["name"];
                    }
                    //Report definition might have some entities which are not there in the available list of entities
                    $new_image_ents = "";
                    foreach($image['ents'] as $image_ent)
                    {
                        if(view_utils::value_exists_in_array($ents, $image_ent))
                            $new_image_ents .= $image_ent . ",";
                    }
                    $new_image_ents = preg_replace("/,$/", "", $new_image_ents);
                    if(ns_empty($new_image_ents))
                        $new_image_ents = view_utils::select_entity($ents);
                    $img_src .= "&ents=" . urlencode(preg_replace("/\//", "%2F", $new_image_ents));
                }
                else
                {
                    $new_image_ents = view_utils::select_entity($ents);
                    $img_src .= "&ents=" . urlencode(preg_replace("/\//", "%2F", $new_image_ents));
                }
            }

            $alt = "";
            if(isset($image['title']))
            {
                $img_src .= "&title=" . $image['title'];
                $alt = $image['title'];
            }
            else
            {
                $funcs = explode(";", $img_func, 2);
                $alt = view_utils::get_report_element_title($funcs[0]);
            }

            $img_src .=  "&cntrs=" . $img_cntrs;
            $img_src .= reporting_customization::get_properties_for_chart($image) . "')";
            print " alt=\"$alt chart\" title=\"\" usemap=\"#" . $img_id . "_map\" border=\"0\"><map name=\"" . $img_id . "_map\"></map>";
            print "<script type=\"text/javascript\">document.getElementById(\"$img_id\").src = $img_src;</script>";
            print "</td></tr>";
            print "<tr id=\"tabular_view_" . $img_id . "_row\" $style_for_tabular_view><td class=\"padding_top full_width_table\" align=\"center\"><div id=\"tabular_view_" . $img_id . "_div\" class=\"ns_st_body_table_div\"></div></td></tr>";
            print "<tr id=\"images_table_" . $img_id . "_toolbar_row\" class=\"images_table_toolbar\"><td align=\"left\">";
            $sel_counters = self::create_counters_objects($img_cntrs, $img_func, $internal, $avail_counters[$image['id']]);
            self::print_image_toolbar($image['id'], $img_func, $sel_counters, $entities, (!ns_empty($new_image_ents)) ? explode(",", $new_image_ents) : null, $errors, $cores, isset($image['cores']) ? $image['cores'] : null, $groups, $avail_counters, $internal, isset($image['title']) ? $image['title'] : '', $is_tabular_view);
            print "</td></tr>";
        }
    }

    function create_counters_objects($cntrs, $func, $internal, $counters_result)
    {
        $sel_counters = array();
        $cntrs = explode(";", $cntrs);
        $func = explode(";", $func);
        $i = 0;

        if($internal)
        {
            foreach($func as $f)
            {
                $c = explode(",", $cntrs[$i++]);
                foreach($c as $counter_name)
                {
                    $cntr_codename = $counter_name;
                    $cntr_varname = $counter_name;
                    $avail_counters = $counters_result->get_counters();
                    foreach($avail_counters as $avail_counter)
                    {
                        if($counter_name == $avail_counter->get_name())
                        {
                            $cntr_varname = $avail_counter->get_descr();
                            break;
                        }
                    }
                    $sel_counters[] = new counter($cntr_codename, $cntr_varname, $cntr_codename, preg_replace("/^stat/", "", $f));
                }
            }
        }
        else
        {
            foreach($func as $f)
            {
                $_ns_stat_definitions = new _ns_stat_definitions();
                $nsstat_cmd = $_ns_stat_definitions->get_command($f);
                $c = explode(",", $cntrs[$i++]);
                if(!$nsstat_cmd)
                    continue;
                $nsstat_cntrs = $nsstat_cmd['cntrs'];
                foreach($c as $counter_name)
                {
                    $rate_cntr = preg_replace("/Rate/", "rate", $counter_name);
                    if(!isset($nsstat_cntrs[$rate_cntr]))
                    {
                        $sel_counters[] = new counter($counter_name, $counter_name, $counter_name, $f);
                        continue;
                    }
                    $cntr = $nsstat_cntrs[$rate_cntr];
                    $counter_desc = view_utils::get_counter_descr($cntr, $nsstat_cntrs);
                    $counter_tooltip = isset($cntr['tooltip']) ? $cntr['tooltip'] : $counter_desc;
                    $sel_counters[] = new counter($counter_name, $counter_desc, $counter_tooltip, $f);
                }
            }
        }

        return $sel_counters;
    }

    function print_image_toolbar($id, $func, $cntrs, $entities, $sel_entities, $errors, $cores, $sel_cores, $groups, $avail_counters, $internal, $title, $is_tabular_view)
    {
        $img_id = "img_" . $id;
        $chart_type_toolbar_style = $is_tabular_view ? "style=\"display: none\"" : "style=\"display: inline\"";
        ?>
        <div class = "rep_toolbar_bottom">
        <?php
        require_once(APPPATH . "controllers/common/rapi.php");
        $rapi = new rapi(true);
        if($rapi->is_partiton_configured_and_is_not_default_partition())
        {
        ?>
            <div id ="report_left_toolbar" style = "display:none">
        <?php
    }
    else{
    ?>
            <div id ="report_left_toolbar">
            <?php
        }
        ?>
                <div id = "add_rep_element" class = "floated_ellipsed_divs">
                    <a href="#noAnchor" onclick="javascript:add_report_element('<?=$img_id;?>')">
                        <div  title="Add a report element" class = "reporting_images add_rep_img pointer_cursor"></div>
                    </a>
                </div>

                <div id = "del_rep_element" class = "floated_ellipsed_divs">
                    <a href="#noAnchor" onclick="javascript:delete_report_element('<?=$img_id;?>')">
                        <div title="Delete report element" class = "reporting_images del_rep_img pointer_cursor"></div>
                    </a>
                </div>

                <div id = "mv_up_rep_element" class = "floated_ellipsed_divs">
                    <a href="#noAnchor" onclick="javascript:move_report_element('<?=$img_id;?>', true)">
                        <div title="Move report element up" class = "reporting_images mv_up_img pointer_cursor"></div>
                    </a>
                </div>

                <div id = "mv_dwn_rep_element" class = "floated_ellipsed_divs">
                    <a href="#noAnchor" onclick="javascript:move_report_element('<?=$img_id;?>', false)">
                        <div title = "Move report element down" class = "reporting_images mv_dwn_img pointer_cursor"></div>
                    </a>
                </div>
            </div>
        

            <div id ="report_right_toolbar" style = "float:right;">
                <div id = "progress_img" class = "floated_ellipsed_divs">
                    <img id="<?=$img_id?>_progress_image" src="/admin_ui/common/images/in_progress.gif" height="16" width="16" alt="" title=""/>
                </div>

                <div id = "customize_div" class = "floated_ellipsed_divs">
                    <div id="<?=$img_id;?>_chart_types_toolbar" <?=$chart_type_toolbar_style?>>
                        <a href="#noAnchor" onclick="javascript:popup_show('<?=$img_id;?>_customize_div', '<?=$img_id;?>_customize_span', 0, 13)" title="Customize chart properties">
                            <div id="<?=$img_id;?>_customize_span" title="Customize chart properties" class = "reporting_images customize_img pointer_cursor"></div>
                            Customize
                        </a>
                    </div>
                </div>

                <div id = "counters_div" class = "floated_ellipsed_divs">
                    <a href="#noAnchor" onclick="javascript:popup_show('<?=$img_id;?>_counters_div', '<?=$img_id;?>_counters_span', 0, 20, null, '<?=$internal ? $img_id . "_counter_regex_tf" : "null"?>')" title="Select counters/entities">
                        <div id="<?=$img_id;?>_counters_span" title="Select counters/entities"class = "reporting_images conuters_img pointer_cursor" ></div>
                        Counters
                    </a>
                </div>
                
                <div id = "export_report_div" class = "floated_ellipsed_divs">
                    <a href="#noAnchor" onclick="javascript:export_data('<?=$img_id;?>')" title="Export data in CSV format (Comma Separated Values)">
                        <div title="Export data in CSV format (Comma Separated Values)" class = "reporting_images export_img pointer_cursor" ></div>
                        Export
                    </a>
                <iframe id="<?=$img_id;?>_export_iframe" style="display: none"></iframe>
               </div>
            </div>
        </div>

        <div id="<?=$img_id;?>_counters_div" class="popup">
        <?php self::print_counters_combo_section($img_id, $func, $cntrs, isset($entities[$id]) ? $entities[$id] : null, $sel_entities, isset($errors[$id]) ? $errors[$id] : null, $cores, $sel_cores, $groups, isset($avail_counters[$id]) ? $avail_counters[$id] : null, $internal, $title); ?>
        </div>
        <div id="<?=$img_id;?>_customize_div" class="popup">
        <?php reporting_customization::print_customize_section($img_id); ?>
        </div>
        <?php
    }
    // Expects unique image id, stat group name, selected counters, available entities, selected entities, errors while
    // retrieving entities, available cores, selected cores
    function print_counters_combo_section($img_id, $func, $sel_counters, $entities, $sel_entities, $error, $cores, $sel_cores, $groups_result, $counters_result, $internal, $title)
    {
        $func_arr = explode(";", $func);
        $func = $func_arr[0];
        if($internal && !preg_match("/^stat/", $func))
            $func = "stat" . $func;
        $is_an_entity_based_group = view_utils::is_an_entity_based_function($func);
        print "<table id=\"" . $img_id . "_full_table\">";
        print "<tr id=\"" . $img_id . "_counters_drag_handle\" class=\"draggable\"><td align=\"right\"><img src=\"/admin_ui/common/images/navigate_cross.png\" alt=\"Close\" title=\"Close\" align=\"right\" onclick=\"popup_hide('".$img_id."_counters_div')\"></td></tr>";
        $license_map = isset($_SESSION['licenseMap']) ? $_SESSION['licenseMap'] : "";
        $groups = $groups_result->get_counter_groups();
        $groups_return_code = $groups_result->get_return_code();
        $groups_return_message = $groups_result->get_return_message();

        if($title == null && $title != "")
        {
            if(!$internal)
                $title = view_utils::get_report_element_title($func);
            else
                $title = $func;
        }

        $title = htmlentities($title); // Avoid XSS

        print "<tr id=\"" . $img_id . "_title_row\"><td align=\"left\" class=\"bottom_border\">Title&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        print "<input type=\"textfield\" id=\"" . $img_id . "_title_tf\" maxlength=\"" . MAX_NO_OF_CHARS_IN_REPORT_NAME . "\" value=\"$title\" title=\"Enter title of report element\" onkeypress=\"return key_pressed(event, null, word_zero_check)\" class=\"chart_name_input\"/></td></tr>";
        print "<tr><td align=\"left\"><table class=\"negative_margin_left\"><tr id=\"" . $img_id . "_groups_radio_row\"><td>Plot chart for</td><td><input type=\"radio\" id=\"" . $img_id . "_groups_global_radio\" name=\"" . $img_id . "_groups_radio\" value=\"global\" title=\"Click to view global groups\" onclick=\"javascript:group_radio_changed('" . $img_id . "', this)\" ";
        
        if($is_an_entity_based_group)
            print ">";
        else
            print "checked>";
        
        print "System global statistics <input type=\"radio\"  id=\"" . $img_id . "_groups_entity_radio\" name=\"" . $img_id . "_groups_radio\" value=\"entity\" title=\"Click to view entity based groups\" onclick=\"javascript:group_radio_changed('" . $img_id . "', this)\" ";
        
        if(!$is_an_entity_based_group)
            print ">";
        else
            print "checked>";
        
        print "System entities statistics </td></tr>";
        print "<tr id=\"" . $img_id . "_counters_group_global_row\" ";
        
        if($is_an_entity_based_group || $internal)
            print "style=\"display: none\">\n";
        else
            print ">\n";
        
        print "<td>Select group</td><td>";
        print "<select id=\"".$img_id."_group_global_combo\" class=\"group_combo\" onchange=\"group_cmb_changed('" . $img_id . "', this.value)\">\n";
        $ent_groups = array();
        
        foreach($groups as $group)
        {
            #If a feature doesn't require licensing, skip the license check
            $license = $group->get_license();
            if(!empty($license))
            {
                $licensed = 1;
                foreach($license as $lic)
                {
                    if (!isset($license_map[$lic]))
                    {
                        $licensed = 0;
                        break;
                    }
                }
                if(!$licensed)
                    continue;
            }
            $name = $group->get_name();
            if($internal)
            {
                if(view_utils::is_an_entity_based_function("stat" . $name))
                {
                    $ent_groups[] = $group;
                    continue;
                }
                else
                    continue;
            }
            else
            {
                if(view_utils::is_an_entity_based_function($name))
                {
                    $ent_groups[] = $group;
                    continue;
                }
            }
            $descr = preg_replace("/Statistics/", "", $group->get_descr());
            if($internal)
            {
                if ($name != preg_replace("/^stat/", "", $func))
                    print "<option value=\"$name\">$descr</option>\n";
                else
                    print "<option selected value=\"$name\">$descr</option>\n";
            }
            else
            {
                if ($name != $func)
                    print "<option value=\"$name\">$descr</option>\n";
                else
                    print "<option selected value=\"$name\">$descr</option>\n";
            }
        }

        print "</select>";
        print "</td></tr>";
        print "<tr id=\"" . $img_id . "_counters_group_entity_row\" ";

        if(!$is_an_entity_based_group)
            print "style=\"display: none\">\n";
        else
            print ">\n";
   
        print "<td>Select group</td><td>";
        print "<select id=\"".$img_id."_group_entity_combo\" class=\"group_combo\" onchange=\"group_cmb_changed('" . $img_id . "', this.value)\">\n";
   
        foreach($ent_groups as $group)
        {
            $name = $group->get_name();
            $descr = preg_replace("/Statistics/", "", $group->get_descr());
            if($internal)
            {
                if ($name != preg_replace("/^stat/", "", $func))
                    print "<option value=\"$name\">$descr</option>\n";
                else
                    print "<option selected value=\"$name\">$descr</option>\n";
            }
            else
            {
                if ($name != $func)
                    print "<option value=\"$name\">$descr</option>\n";
                else
                    print "<option selected value=\"$name\">$descr</option>\n";
            }
        }

        print "</select>";
        print "</td></tr></table></td></tr>";
        print "<tr><td align=\"left\" valign=\"top\" class=\"bottom_border\">";
        print "<div class=\"tab\" align=\"left\">";
        print "<span id=\"" . $img_id . "_tab_0\" class=\"current_tab\"><a href=\"#noAnchor\" onclick=\"javascript:switch_tab('$img_id', 0)\"><span>Counters</span></a></span>";
        
        if($is_an_entity_based_group)
            print "<span id=\"" . $img_id . "_tab_1\" class=\"\"><a href=\"#noAnchor\" onclick=\"javascript:switch_tab('$img_id', 1)\"><span>Entities</span></a></span>";

        if(!ns_empty($cores))
            print "<span id=\"" . $img_id . "_tab_2\" class=\"\"><a href=\"#noAnchor\" onclick=\"javascript:switch_tab('$img_id', 2)\"><span>" . CPU_CORE . "s</span></a></span>";
        
        print "</div></td></tr>";

        print "<tr id=\"" . $img_id . "_counters_table_row\"><td align=\"left\" nowrap>";
        print "<table id=\"".$img_id."_counters_table\" cellpadding=\"0\" cellspacing=\"0\" class=\"full_width_table\">";
        print "<tr><td>";
        
        if(!$internal || $is_an_entity_based_group)
        {
            print "<table class=\"full_width_table\" cellpadding=\"0\" cellspacing=\"0\"><tr><td>Available</td><td align=\"right\">";
            print "<img src=\"/admin_ui/common/images/view.png\" alt=\"Show search panel\" id=\"" . $img_id . "_cntrs_avail_list_find_img\" title=\"Show search panel\" class=\"pointer_cursor\" onclick=\"show_hide_find_panel('" . $img_id . "_cntrs_avail_list', true)\"></td></tr>";
            self::print_find_panel_for_list($img_id . "_cntrs_avail_list");
            print "</table>";
        }
        else
            self::print_counters_search_section($img_id);

        $size_of_cntrs_avail_list = (!$internal || $is_an_entity_based_group) ? 10 : 9;
        print "<select id=\"" . $img_id . "_cntrs_avail_list\" size=\"$size_of_cntrs_avail_list\" multiple onchange=\"set_state('" . $img_id . "_cntrs', this, true)\" class=\"counters_list_box\" ondblclick=\"javascript:move_items('" . $img_id . "', '_cntrs', true, false)\">\n";

        $avail_counters = $counters_result->get_counters();

        foreach($avail_counters as $counter)
        {
            $counter_name = $counter->get_name();
            $counter_desc = $counter->get_descr();
            $counter_tooltip = $counter->get_tooltip();
            $counter_func = $counter->get_group();
            if(!view_utils::value_exists_in_array($sel_counters, $counter))
                print "<option value=\"" . $counter_name . " " . $counter_func . "\" title=\"$counter_tooltip\">$counter_desc</option>\n";
        }

        print "</select></td><td class=\"padding_right_left\">\n";
        print "<input type=\"button\" id=\"" . $img_id . "_cntrs_move_right_button\" class=\"counters_move_button\"  value=\">\" onclick=\"javascript:move_items('" . $img_id . "', '_cntrs', true, false)\" disabled=\"true\"/><br>\n";
        print "<input type=\"button\" id=\"" . $img_id . "_cntrs_move_left_button\" class=\"counters_move_button\"  value=\"<\" onclick=\"javascript:move_items('" . $img_id . "', '_cntrs', false, false)\" disabled=\"true\"/><br>\n";
        print "<input type=\"button\" id=\"" . $img_id . "_cntrs_move_all_left_button\" class=\"counters_move_button\"  value=\"<<\" onclick=\"javascript:move_items('" . $img_id . "', '_cntrs', false, true)\"/>\n";
        print "</td><td>Selected";
        print "<br><select id=\"" . $img_id . "_cntrs_sel_list\" size=\"10\" multiple onchange=\"set_state('" . $img_id . "_cntrs', this, false)\" class=\"counters_list_box\" ondblclick=\"move_items('" . $img_id . "', '_cntrs', false, false)\">\n";
        
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
        
        if(!empty($error))
        {
            print "<tr id=\"" . $img_id . "_entities_header_row\" style=\"display: none\"><td></td></tr>";
            print "<tr id=\"" . $img_id . "_entities_table_row\" style=\"display: none\"><td align=\"left\" nowrap>$error</td></tr>";
        }
        else if($is_an_entity_based_group)
        {
            print "<tr id=\"" . $img_id . "_entities_header_row\" style=\"display: none\"><td align=\"left\" nowrap>";
            print "<table id=\"".$img_id."_entities_table\" cellpadding=\"0\" cellspacing=\"0\" class=\"full_width_table\">";
            print "<tr id=\"" . $img_id . "_entities_table_row\"><td><table class=\"full_width_table\"  cellpadding=0 cellspacing=0><tr><td>Available</td><td align=\"right\">";
            $ents_avail_list_id = $img_id . "_ents_avail_list";
            print "<img src=\"/admin_ui/common/images/view.png\" alt=\"Show search panel\" id=\"" . $ents_avail_list_id . "_find_img\" title=\"Show search panel\" class=\"pointer_cursor\" onclick=\"show_hide_find_panel('$ents_avail_list_id', true)\"></td></tr>";
            self::print_find_panel_for_list($ents_avail_list_id);
            print "</table>";
            print "<select id=\"$ents_avail_list_id\" size=\"8\" multiple onchange=\"set_state('" . $img_id . "_ents', this, true)\" class=\"counters_list_box\" ondblclick=\"move_items('" . $img_id . "', '_ents', true, false)\">\n";
            
            if(!ns_empty($entities))
            {
                if(!isset($sel_entities))
                    $sel_entities = array();
                foreach($entities as $entity)
                {
                    $state = $entity["state"];
                    $entity = $entity["name"];
                    $style = view_utils::get_style_for_entity($state);
                    $readable_state = "(" . view_utils::get_state_of_an_entity($state) . ")";
                    if(!view_utils::value_exists_in_array($sel_entities, $entity))
                        print "<option value=\"$entity $state\" $style title=\"$entity $readable_state\">$entity</option>\n";
                }
            }

            print "</select>";
            print "<table cellpadding=\"0\" cellspacing=\"0\"><tr><td>Show&nbsp;</td><td title=\"Show/hide active entities for which data is being collected\"><input type=\"checkbox\" id=\"" . $ents_avail_list_id . "_active_entities_ckb\" onclick=\"filter_entities('$ents_avail_list_id')\" checked>Active&nbsp;</td>";
            print "<td title=\"Show/hide inactive entities for which data is not being collected\"><input type=\"checkbox\" id=\"" . $ents_avail_list_id . "_inactive_entities_ckb\" onclick=\"filter_entities('$ents_avail_list_id')\">Inactive&nbsp;</td>";
            //print "<td title=\"Show/hide deleted entities\"><input type=\"checkbox\" id=\"" . $ents_avail_list_id . "_deleted_entities_ckb\" onclick=\"filter_entities('$ents_avail_list_id')\">Deleted</td>";
            print "</tr></table>";
            print "</td><td class=\"padding_right_left\">\n";
            print "<input type=\"button\" id=\"" . $img_id . "_ents_move_right_button\" class=\"counters_move_button\"  value=\">\" onclick=\"javascript:move_items('" . $img_id . "', '_ents', true, false)\" disabled=\"true\"/><br>\n";
            print "<input type=\"button\" id=\"" . $img_id . "_ents_move_left_button\" class=\"counters_move_button\"  value=\"<\" onclick=\"javascript:move_items('" . $img_id . "', '_ents', false, false)\" disabled=\"true\"/><br>\n";
            print "<input type=\"button\" id=\"" . $img_id . "_ents_move_all_left_button\" class=\"counters_move_button\"  value=\"<<\" onclick=\"javascript:move_items('" . $img_id . "', '_ents', false, true)\"/>\n";
            print "</td><td>Selected<br><select id=\"" . $img_id . "_ents_sel_list\" size=\"10\" multiple onchange=\"set_state('" . $img_id . "_ents', this, false)\" class=\"counters_list_box\" ondblclick=\"move_items('" . $img_id . "', '_ents', false, false)\">\n";
            
            if(isset($sel_entities) && !ns_empty($entities))
            {
                foreach($sel_entities as $entity)
                {
                    if($sel_entity = view_utils::value_exists_in_array($entities, $entity))
                    {
                        $state = $sel_entity["state"];
                        $style = view_utils::get_style_for_entity($state);
                        $readable_state = "(" . view_utils::get_state_of_an_entity($state) . ")";
                        print "<option value=\"$entity $state\" $style title=\"$entity $readable_state\">$entity</option>\n";
                    }
                }
            }

            print "</select></td></tr></table></td></tr>\n";
        }

        if(isset($cores))
        {
            print "<tr id=\"" . $img_id . "_cores_header_row\" style=\"display: none\"><td><table cellpadding=\"0\" cellspacing=\"0\" class=\"full_width_table\"><tr><td align=\"left\"><input type=\"radio\" id=\"". $img_id . "_aggregate_radio\" name=\"" . $img_id . "_radio\" value=\"aggregate\" onclick=\"javascript:core_radio_changed('" . $img_id . "', 0)\" ";
            if(!isset($sel_cores))
                print "checked>";
            else
                print ">";

            print "Show aggregate data";
            print "</td></tr><tr><td align=\"left\" colspan=\"2\"><input type=\"radio\" id=\"". $img_id . "_per_core_radio\" name=\"" . $img_id . "_radio\" value=\"percore\" onclick=\"javascript:core_radio_changed('" . $img_id . "', 1)\" ";
            
            if(!self::is_per_core_applicable_for_group($func))
                print " title=\"Per '" . CPU_CORE . "' data is not applicable.\" disabled ";
            if(!isset($sel_cores))
                print ">";
            else
                print "checked>";
            
            print "Show per " . CPU_CORE . " data</td></tr></table></td></tr>";
            print "<tr id=\"" . $img_id . "_cores_table_row\" style=\"display: none\" ";
            
            if(!isset($sel_cores))
                print "disabled>";
            else
                print ">";
            
            print "<td align=\"left\" nowrap>";
            print "<table id=\"".$img_id."_cores_table\" cellpadding=\"0\" cellspacing=\"0\" class=\"full_width_table\">";
            print "<tr><td>Available<br><select id=\"" . $img_id . "_cores_avail_list\" size=\"7\" multiple onchange=\"set_state('" . $img_id . "_cores', this, true)\" class=\"counters_list_box\" ondblclick=\"move_items('" . $img_id . "', '_cores', true, false)\">\n";
            $cores = explode(",", $cores);
            usort($cores, "strcasecmp");
            
            foreach($cores as $core)
            {
                if(isset($sel_cores))
                {
                    if($sel_cores == ALL)
                        break;
                    if(!view_utils::value_exists_in_array($sel_cores, $core))
                        print "<option value=\"$core\" title=\"$core\">" . CPU_CORE . " " . $core . "</option>\n";
                }
                else
                    print "<option value=\"$core\" title=\"$core\">" . CPU_CORE . " " . $core . "</option>\n";
            }
            
            print "</select></td><td class=\"padding_right_left\">\n";
            print "<input type=\"button\" id=\"" . $img_id . "_cores_move_right_button\" class=\"counters_move_button\"  value=\">\" onclick=\"javascript:move_items('" . $img_id . "', '_cores', true, false)\" disabled=\"true\"/><br>\n";
            print "<input type=\"button\" id=\"" . $img_id . "_cores_move_left_button\" class=\"counters_move_button\"  value=\"<\" onclick=\"javascript:move_items('" . $img_id . "', '_cores', false, false)\" disabled=\"true\"/><br>\n";
            print "<input type=\"button\" id=\"" . $img_id . "_cores_move_all_left_button\" class=\"counters_move_button\"  value=\"<<\" onclick=\"javascript:move_items('" . $img_id . "', '_cores', false, true)\"/>\n";
            print "</td><td>Selected<br><select id=\"" . $img_id . "_cores_sel_list\" size=\"7\" multiple onchange=\"set_state('" . $img_id . "_cores', this, false)\" class=\"counters_list_box\" ondblclick=\"move_items('" . $img_id . "', '_cores', false, false)\">\n";
            
            if(isset($sel_cores))
            {
                if($sel_cores == ALL)
                {
                    foreach($cores as $core)
                        print "<option value=\"$core\" title=\"$core\">" . CPU_CORE . " " . $core . "</option>\n";
                }
                else
                {
                    foreach($sel_cores as $core)
                    {
                        if(view_utils::value_exists_in_array($cores, $core))
                            print "<option value=\"$core\" title=\"$core\">" . CPU_CORE . " " . $core . "</option>\n";
                    }
                }
            }

            print "</select></td></tr></table></td></tr>\n";
        }

        print "<tr><td><table class=\"counters_popup_status\" cellpadding=\"0\" cellspacing=\"0\"><tr><td id=\"" . $img_id . "_cntrs_status_cell\"></td><td align=\"right\" class=\"padding_top\">";
        print "<input type=\"button\" class=\"flat_button\" value=\"OK\" onclick='hide_find_panel(\"" . $img_id. "_cntrs_avail_list\", true);hide_find_panel(\"$ents_avail_list_id\", true);popup_hide(\"".$img_id."_counters_div\", true);refresh_image(\"$img_id\")' title=\"Click to view report\"/>&nbsp;&nbsp;";
        print "<input type=\"button\" class=\"flat_button\" value=\"Close\" onclick='javascript:popup_hide(\"".$img_id."_counters_div\")' title=\"Click to close\"/>";
        print "</td></tr></table></td></tr>";
        print "</table>";
        
        if(!empty($ents_avail_list_id))
        {
            print "<script type=\"text/javascript\">\n";
            print "filter_entities('$ents_avail_list_id');\n";
            print "</script>\n";
        }
    }

    function print_counters_search_section($img_id)
    {
        print "<form onsubmit=\"return fetch_counters('$img_id');\" class=\"inline_display\"><table class=\"full_width_table\" cellpadding=\"0\">";
        print "<tr><td align=\"right\">Name</td>";
        print "<td>&nbsp;<input type=\"textfield\" id=\"" . $img_id . "_counter_regex_tf\" value=\"\" title=\"Enter counter name to search (can be a regular expression)\" style=\"width: 135px\"> </td>";
        print "<td>&nbsp;<input type=\"submit\" class=\"flat_button\" style=\"width: 70px; height: 17px\" value=\"Search\" title=\"Click to search for counters matching the given name\"></td>";
        print "</tr></table></form>";
    }

    function print_find_panel_for_list($list_id)
    {
        print "<tr id=\"" . $list_id . "_find_row\" style=\"display: none\"><td class=\"find_panel_reporting\">";
        print "Criterion <select id=\"" . $list_id . "_find_row_criterion\" title=\"Select the search criterion\" class=\"criterion_reporting\" onchange=\"filter_list('$list_id')\">";
        print "<option>contains</option>";
        print "<option>not contains</option>";
        print "<option>starts with</option>";
        print "<option>ends with</option>";
        print "</select>";
        print " Text <input type=\"textfield\" size=\"13\" maxlength=\"128\" id=\"" . $list_id . "_find_row_search_text\" title=\"Enter the search text (case insensitive)\" onpropertychange=\"return filter_list('$list_id')\" oninput=\"return filter_list('$list_id')\" class=\"search_text_reporting\">";
        print "</td><td align=\"right\"><img src=\"/admin_ui/common/images/navigate_cross.png\" alt=\"Hide search panel\" title=\"Hide search panel\" class=\"pointer_cursor\" onclick=\"show_hide_find_panel('$list_id', true)\">";
        print "</td></tr>";
    }

    function is_per_core_applicable_for_group($group)
    {
        if($group == "statsystemcpu")
            return false;

        return true;
    }
}

class counter
{
    private $name;
    private $descr;
    private $tooltip;
    private $group;

    public function __construct($name, $descr, $tooltip, $group)
    {
        $this->name = $name;
        $this->descr = $descr;
        $this->tooltip = $tooltip;
        $this->group = $group;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function get_descr()
    {
        return $this->descr;
    }

    public function get_tooltip()
    {
        return $this->tooltip;
    }

    public function get_group()
    {
        return $this->group;
    }
}

class counters_result
{
    private $counters;
    private $return_code;
    private $return_message;

    public function __construct($counters, $return_code, $return_message)
    {
        $this->counters = $counters;
        $this->return_code = $return_code;
        $this->return_message = $return_message;
    }

    public function get_counters()
    {
        return $this->counters;
    }

    public function get_return_code()
    {
        return $this->return_code;
    }

    public function get_return_message()
    {
        return $this->return_message;
    }
}

class counter_group
{
    private $name;
    private $descr;
    private $license;

    public function __construct($name, $descr, $license)
    {
        $this->name = $name;
        $this->descr = $descr;
        $this->license = $license;
    }

    public function get_name()
    {
        return $this->name;
    }

    public function get_descr()
    {
        return $this->descr;
    }

    public function get_license()
    {
        return $this->license;
    }
}

class counter_groups_result
{
    private $counter_groups;
    private $return_code;
    private $return_message;

    public function __construct($counter_groups, $return_code, $return_message)
    {
        $this->counter_groups = $counter_groups;
        $this->return_code = $return_code;
        $this->return_message = $return_message;
    }

    public function get_counter_groups()
    {
        return $this->counter_groups;
    }

    public function get_return_code()
    {
        return $this->return_code;
    }

    public function get_return_message()
    {
        return $this->return_message;
    }
}
?>