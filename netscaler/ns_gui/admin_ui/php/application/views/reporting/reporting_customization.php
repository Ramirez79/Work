<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH."views/common/locale_utils.php");
//Utility functions used in reporting for customizing charts
class reporting_customization
{
    function get_property_value_as_string($values, $key_map)
    {
        if(!is_array($values))
            return $values;
        $ret_value = "";
        foreach($values as $prop => $value)
        {
            if(!isset($key_map[$prop]))
                continue;
            if($ret_value != "")
                $ret_value .= ";";
            $ret_value .= $key_map[$prop] . ":" . $value;
        }
        return $ret_value;
    }

    function get_properties_for_chart($element)
    {
        $key_map = reporting::get_properties_key_map();
        $properties_url = "";
        if(!isset($element["properties"]))
        {
            if(isset($element["props"]) && $element["props"] != "null")
                return "&props=" . urlencode($element["props"]);
            return $properties_url;
        }
        $properties = $element["properties"];
        foreach($properties as $prop => $value)
        {
            if(isset($key_map[$prop]))
            {
                if($properties_url != "")
                    $properties_url .= "&";
                $properties_url .= $key_map[$prop] . "=" . $value;
            }
            else if(is_int($prop))
            {
                if($properties_url != "")
                    $properties_url .= "&";
                $properties_url .= $prop . "=" . self::get_property_value_as_string($value, $key_map);
            }
        }
        return "&props=" . urlencode($properties_url);
    }

    function print_global_conf()
    {
        print "\nvar global_conf = new Array();";
        $global_conf = reporting::get_global_conf();
        if(ns_empty($global_conf))
            return;
        foreach($global_conf as $prop => $value)
            print "\nglobal_conf[\"$prop\"] = \"$value\";";
    }

    function print_customize_section($img_id)
    {
        print "<table style=\"width: 475px\"><tr id=\"" . $img_id . "_customize_drag_handle\" class=\"draggable\"><td><b>Customize:</b></td><td align=\"right\"><img src=\"/admin_ui/common/images/navigate_cross.png\" alt=\"Close\" title=\"Close\" onclick=\"popup_hide('".$img_id."_customize_div')\"></td></tr>";

        print "<tr><td class=\"bottom_border\" colspan=\"2\"><div class=\"tab full_width_table\" align=\"left\">";
        print "<span id=\"" . $img_id . "_customize_tab_0\" class=\"current_tab\"><a href=\"#noAnchor\" onclick=\"javascript:switch_customize_tab('$img_id', 0)\"><span>Chart</span></a></span>";
        print "<span id=\"" . $img_id . "_customize_tab_1\" class=\"\"><a href=\"#noAnchor\" onclick=\"javascript:switch_customize_tab('$img_id', 1)\"><span>Plot Area</span></a></span>";
        print "<span id=\"" . $img_id . "_customize_tab_2\" class=\"\"><a href=\"#noAnchor\" onclick=\"javascript:switch_customize_tab('$img_id', 2)\"><span>Data Set</span></a></span>";
        print "</div></td></tr>";

        print "<tr id=\"" . $img_id . "_customize_chart_row\"><td colspan=\"2\">";
        print "<table class=\"full_width_table\">";
        print "<tr><td valign=\"top\">Category:<br><select id=\"" . $img_id . "_customize_chart_list\" size=\"10\" onchange=\"customize_chart_list_changed('" . $img_id . "', this.value)\">";
        print "<option value=\"bg_color\">Background Color</option>";
        print "<option value=\"text_font_color\">Text Color</option>";
        print "<option value=\"plot_type\">Plot type</option>";
        print "<option value=\"left_y_axis\">Left Y-Axis</option>";
        print "<option value=\"right_y_axis\">Right Y-Axis</option>";
        print "<option value=\"multiple_axes\">Multiple Axes</option>";
        print "</select></td>";
        print "<td id=\"" . $img_id . "_customize_property_cell\" align=\"left\" valign=\"top\" width=\"75%\">";
        print "<div id=\"" . $img_id . "_customize_chart_cell\">Select from any of the categories</div>";
        self::print_bg_color_section($img_id);
        self::print_text_font_color_section($img_id);
        self::print_plot_type_section($img_id);
        $grouping_separator = locale_utils::get_grouping_separator();
        self::print_left_y_axis_section($img_id, $grouping_separator);
        self::print_right_y_axis_section($img_id, $grouping_separator);
        self::print_multiple_axes_section($img_id);
        print "</td></tr></table>";
        print "</td></tr>";

        print "<tr id=\"" . $img_id . "_customize_plot_area_row\" style=\"display: none\"><td colspan=\"2\">";
        print "<table class=\"full_width_table\">";
        print "<tr><td valign=\"top\">Category:<br><select id=\"" . $img_id . "_customize_plot_area_list\" size=\"10\" onchange=\"customize_plot_area_list_changed('" . $img_id . "', this.value)\">";
        print "<option value=\"plot_bg_color\">Background Color</option>";
        print "<option value=\"plot_edge_color\">Edge Color</option>";
        print "<option value=\"h_grid\">Horizontal Grids</option>";
        print "<option value=\"v_grid\">Vertical Grids</option>";
        print "</select></td>";
        print "<td id=\"" . $img_id . "_customize_plot_area_property_cell\" align=\"left\" valign=\"top\" width=\"75%\">";
        print "<div id=\"" . $img_id . "_customize_plot_area_cell\">Select from any of the categories</div>";
        self::print_plot_bg_color_section($img_id);
        self::print_plot_edge_color_section($img_id);
        self::print_h_grid_section($img_id);
        self::print_v_grid_section($img_id);
        print "</td></tr></table>";
        print "</td></tr>";

        print "<tr id=\"" . $img_id . "_customize_data_set_row\" style=\"display: none\"><td colspan=\"2\">";
        print "<table class=\"full_width_table bottom_border\">";
        print "<tr><td style=\"width: 130px\">Select Data Set</td><td><select id=\"" . $img_id . "_customize_data_set_list\" style=\"width: 100%\" onchange=\"customize_data_set_list_changed('$img_id', this)\">";
        for($i = 0; $i < MAX_NO_OF_DATA_SETS; $i++)
            print "<option value=\"$i\">Data Set " . ($i + 1 ) . "</option>";
        print "</select></td></tr>";
        print "</table>";

        //Defined as xy_chart->line_colors in chart_classes.php
        $data_set_colors = array("ff7f2a", "ffd332", "1bae44", "008fc7",
                                 "09c0e3", "CC5480", "ef3124", "ffbe00",
                                 "db4c3c", "c5881c", "6a93c7", "386398",
                                 "669999", "993333", "006600", "990099",
                                 "ff9966", "99ff99", "9999ff", "cc6600",
                                 "33cc33", "cc99ff", "ff6666", "99cc66",
                                 "009999", "cc3333", "9933ff", "ff0000",
                                 "0000ff", "00ff00", "ffcc99", "999999",
                                 "ff3333", "6666ff", "ffff00", "000000");
        for($i = 0; $i < MAX_NO_OF_DATA_SETS; $i++)
        {
            $table_style = $i == 0 ? "" : "style=\"display: none\"";
            print "<table class=\"full_width_table\" id=\"" . $img_id . "_customize_data_set_" . $i . "_table\" $table_style>";
            print "<tr><td valign=\"top\">Category:<br><select id=\"" . $img_id . "_customize_data_set_" . $i . "_list\" size=\"8\" onchange=\"customize_data_set_property_list_changed('$img_id', $i, this.value)\" style=\"width: 130px\">";
            print "<option value=\"data_set_color\">Color</option>";
            print "<option value=\"data_set_plot_type\">Plot type</option>";
            print "</select></td>";
            print "<td id=\"" . $img_id . "_customize_data_set_" . $i . "_property_cell\" align=\"left\" valign=\"top\" width=\"75%\">";
            print "<div id=\"" . $img_id . "_customize_data_set_" . $i . "_cell\">Select from any of the categories</div>";
            self::print_data_set_color_section($img_id, $i, $data_set_colors[$i]);
            self::print_data_set_plot_type_section($img_id, $i);
            print "</td></tr></table>";
        }
        print "</td></tr>";

        print "<tr><td><div id=\"" . $img_id . "_customize_apply_globally_div\" style=\"display: none\"><a href=\"#noAnchor\" onclick=\"apply_remove_globally('$img_id', true)\" title=\"Click to use this property across all charts/reports\"><img src=\"/admin_ui/common/images/global.png\" alt=\"Apply globally\" border=\"0\">Apply globally</a>&nbsp;&nbsp;<a href=\"#noAnchor\" onclick=\"apply_remove_globally('$img_id', false)\" title=\"Click to remove this property globally\"><img src=\"/admin_ui/common/images/global_delete.png\" alt=\"Remove globally\" border=\"0\">Remove globally</a></div></td><td align=\"right\" class=\"padding_top\">";
        print "<input type=\"button\" class=\"flat_button\" value=\"Apply\" onclick='on_customize_ok_clicked(\"$img_id\", false)' title=\"Click to apply customized properties\"/>&nbsp;&nbsp;";
        print "<input type=\"button\" class=\"flat_button\" value=\"OK\" onclick='on_customize_ok_clicked(\"$img_id\", true)' title=\"Click to view chart with customized properties\"/>&nbsp;&nbsp;";
        print "<input type=\"button\" class=\"flat_button\" value=\"Close\" onclick='javascript:popup_hide(\"".$img_id."_customize_div\")' title=\"Click to close\"/>";
        print "</td></tr></table>";
    }

    function print_bg_color_section($img_id)
    {
        print "<div id=\"" . $img_id . "_customize_bg_color_cell\" style=\"display: none\">";
        print "<table><tr><td nowrap>Background Color</td><td><input id=\"" . $img_id . "_customize_bg_color_tf\" class=\"color\" value=\"FFFFFF\" readonly><input type=\"hidden\" id=\"" . $img_id . "_customize_bg_color_hidden\" value=\"0\"></td></tr>";
        print "<tr><td>Transparency</td><td>";
        print "<div id=\"" . $img_id . "_bg_color_transparency_div\" class=\"yui-skin-sam\"></div>";
        print "</td></tr>";
        print "<tr><td>Effects</td><td><input type=\"radio\" name=\"" . $img_id . "_customize_bg_color_effect_radio\" value=\"Gradient\" onclick=\"bg_color_effect_radio_changed('$img_id', this)\">Gradient</input>&nbsp;<input type=\"radio\" name=\"" . $img_id . "_customize_bg_color_effect_radio\" value=\"Modulation\" onclick=\"bg_color_effect_radio_changed('$img_id', this)\">Modulation</input>&nbsp;<input type=\"radio\" name=\"" . $img_id . "_customize_bg_color_effect_radio\" value=\"None\" onclick=\"bg_color_effect_radio_changed('$img_id', this)\" checked>None</input></td></tr>";
        print "<tr id=\"" . $img_id . "_customize_bg_color_0_row\" style=\"display: none\"><td>Gradient Color</td><td><input id=\"" . $img_id . "_customize_bg_color_gradient_color_tf\" class=\"color\" value=\"FFFFFF\" readonly></td></tr>";
        print "<tr id=\"" . $img_id . "_customize_bg_color_1_row\" style=\"display: none\"><td>Transparency</td><td>";
        print "<div id=\"" . $img_id . "_bg_color_gradient_transparency_div\" class=\"yui-skin-sam\"></div>";
        print "</td></tr>";
        print "<tr id=\"" . $img_id . "_customize_bg_color_2_row\" style=\"display: none\"><td>Modulation Angle</td><td>";
        print "<div id=\"" . $img_id . "_bg_color_modulation_angle_div\" class=\"yui-skin-sam\"></div>";
        print "</td></tr>";
        print "</table>";
        print "</div>";
    }

    function print_text_font_color_section($img_id)
    {
        print "<div id=\"" . $img_id . "_customize_text_font_color_cell\" style=\"display: none\">";
        print "<table><tr><td nowrap>Text Color</td><td><input id=\"" . $img_id . "_customize_text_font_color_tf\" class=\"color\" value=\"336699\" readonly><input type=\"hidden\" id=\"" . $img_id . "_customize_text_font_color_hidden\" value=\"0\"></td></tr>";
        print "<tr><td>Transparency</td><td>";
        print "<div id=\"" . $img_id . "_text_font_color_transparency_div\" class=\"yui-skin-sam\"></div>";
        print "</td></tr>";
        print "<tr><td>Effects</td><td><input type=\"radio\" name=\"" . $img_id . "_customize_text_font_color_effect_radio\" value=\"Gradient\" onclick=\"text_font_color_effect_radio_changed('$img_id', this)\">Gradient</input>&nbsp;<input type=\"radio\" name=\"" . $img_id . "_customize_text_font_color_effect_radio\" value=\"Modulation\" onclick=\"text_font_color_effect_radio_changed('$img_id', this)\">Modulation</input>&nbsp;<input type=\"radio\" name=\"" . $img_id . "_customize_text_font_color_effect_radio\" value=\"None\" onclick=\"text_font_color_effect_radio_changed('$img_id', this)\" checked>None</input></td></tr>";
        print "<tr id=\"" . $img_id . "_customize_text_font_color_0_row\" style=\"display: none\"><td>Gradient Color</td><td><input id=\"" . $img_id . "_customize_text_font_color_gradient_color_tf\" class=\"color\" value=\"FFFFFF\" readonly></td></tr>";
        print "<tr id=\"" . $img_id . "_customize_text_font_color_1_row\" style=\"display: none\"><td>Transparency</td><td>";
        print "<div id=\"" . $img_id . "_text_font_color_gradient_transparency_div\" class=\"yui-skin-sam\"></div>";
        print "</td></tr>";
        print "<tr id=\"" . $img_id . "_customize_text_font_color_2_row\" style=\"display: none\"><td>Modulation Angle</td><td>";
        print "<div id=\"" . $img_id . "_text_font_color_modulation_angle_div\" class=\"yui-skin-sam\"></div>";
        print "</td></tr>";
        print "</table>";
        print "</div>";
    }

    function print_plot_type_section($img_id)
    {
        print "<div id=\"" . $img_id . "_customize_plot_type_cell\" style=\"display: none\">";
        print "<table id=\"" . $img_id . "_plot_type_table\" class=\"plot_type_table\">";
        print "<tr>";
        print "<td id=\"" . $img_id . "_plot_type_line_cell\" class=\"plot_type_table_cell_selected\"><a href=\"#noAnchor\" onclick=\"select_plot_type('$img_id', this.parentNode)\"><img src=\"/admin_ui/common/images/line.png\" alt=\"Line Chart\" border=\"0\" title=\"Line Chart\"></a><input type=\"hidden\" id=\"" . $img_id . "_customize_plot_type_hidden\" value=\"0\"></td>";
        print "<td id=\"" . $img_id . "_plot_type_spline_cell\"><a href=\"#noAnchor\" onclick=\"select_plot_type('$img_id', this.parentNode)\"><img src=\"/admin_ui/common/images/spline.png\" alt=\"Spline Chart\" border=\"0\" title=\"Spline Chart\"></a></td>";
        print "<td id=\"" . $img_id . "_plot_type_step_cell\"><a href=\"#noAnchor\" onclick=\"select_plot_type('$img_id', this.parentNode)\"><img src=\"/admin_ui/common/images/step_line.png\" alt=\"Step Line Chart\" border=\"0\" title=\"Step Line Chart\"></a></td>";
        print "<td id=\"" . $img_id . "_plot_type_scatter_cell\"><a href=\"#noAnchor\" onclick=\"select_plot_type('$img_id', this.parentNode)\"><img src=\"/admin_ui/common/images/scatter.png\" alt=\"Scatter Chart\" border=\"0\" title=\"Scatter Chart\"></a></td>";
        print "</tr>";
        print "<tr>";
        print "<td id=\"" . $img_id . "_plot_type_area_cell\"><a href=\"#noAnchor\" onclick=\"select_plot_type('$img_id', this.parentNode)\"><img src=\"/admin_ui/common/images/area.png\" alt=\"Area chart\" border=\"0\" title=\"Area chart\"></a></td>";
        print "<td id=\"" . $img_id . "_plot_type_bar_cell\"><a href=\"#noAnchor\" onclick=\"select_plot_type('$img_id', this.parentNode)\"><img src=\"/admin_ui/common/images/bar.png\" alt=\"Bar chart\" border=\"0\" title=\"Bar chart\"></a></td>";
        print "<td id=\"" . $img_id . "_plot_type_sarea_cell\"><a href=\"#noAnchor\" onclick=\"select_plot_type('$img_id', this.parentNode)\"><img src=\"/admin_ui/common/images/sarea.png\" alt=\"Stacked Area chart\" border=\"0\" title=\"Stacked Area chart\"></a></td>";
        print "<td id=\"" . $img_id . "_plot_type_sbar_cell\"><a href=\"#noAnchor\" onclick=\"select_plot_type('$img_id', this.parentNode)\"><img src=\"/admin_ui/common/images/sbar.png\" alt=\"Stacked Bar chart\" border=\"0\" title=\"Stacked Bar chart\"></a></td>";
        print "</tr>";
        print "</table>";
        print "<input type=\"checkbox\" id=\"" . $img_id . "_plot_type_ckb\">Use 3D</input>";
        print "</div>";
    }

    function print_left_y_axis_section($img_id, $grouping_separator)
    {
        print "<div id=\"" . $img_id . "_customize_left_y_axis_cell\" style=\"display: none\">";
        print "<table class=\"full_width_table\"><tr><td colspan=\"2\">Left Y-Axis Scale<input type=\"hidden\" id=\"" . $img_id . "_customize_left_y_axis_hidden\" value=\"0\"></td></tr>";
        print "<tr><td colspan=\"2\"><input type=\"radio\" name=\"" . $img_id . "_customize_left_y_axis_scale_radio\" value=\"Linear\" onclick=\"left_y_axis_scale_radio_changed('$img_id', this)\" checked>Linear</input>&nbsp;<input type=\"radio\" name=\"" . $img_id . "_customize_left_y_axis_scale_radio\" value=\"Semi-Log\" onclick=\"left_y_axis_scale_radio_changed('$img_id', this)\">Semi-Log</input>&nbsp;<input type=\"radio\" name=\"" . $img_id . "_customize_left_y_axis_scale_radio\" value=\"Scaling Factor\" onclick=\"left_y_axis_scale_radio_changed('$img_id', this)\">Scaling Factor</input>&nbsp;<input type=\"radio\" name=\"" . $img_id . "_customize_left_y_axis_scale_radio\" value=\"Custom\" onclick=\"left_y_axis_scale_radio_changed('$img_id', this)\">Custom</input></td></tr>";
        print "<tr id=\"" . $img_id . "_customize_left_y_axis_scale_0_row\" style=\"display: none\"><td nowrap>Scaling Factor</td><td><select id=\"" . $img_id . "_customize_left_y_axis_scale_factor_list\"><option value=\"K\">Kilo (1" . $grouping_separator . "000s)</option><option value=\"M\">Mega (1" . $grouping_separator . "000". $grouping_separator ."000s)</option><option value=\"G\">Giga (1" . $grouping_separator ."000" . $grouping_separator ."000" . $grouping_separator ."000s)</option></select></td></tr>";
        print "<tr id=\"" . $img_id . "_customize_left_y_axis_scale_1_row\" style=\"display: none\"><td nowrap>Custom Scale Min</td><td><input id=\"" . $img_id . "_customize_left_y_axis_scale_custom_min_tf\" value=\"0\"></td></tr>";
        print "<tr id=\"" . $img_id . "_customize_left_y_axis_scale_2_row\" style=\"display: none\"><td nowrap>Custom Scale Max</td><td><input id=\"" . $img_id . "_customize_left_y_axis_scale_custom_max_tf\" value=\"100\"></td></tr>";
        print "</table>";
        print "</div>";
    }

    function print_right_y_axis_section($img_id, $grouping_separator)
    {
        print "<div id=\"" . $img_id . "_customize_right_y_axis_cell\" style=\"display: none\">";
        print "<table class=\"full_width_table\">";
        print "<tr><td>Data set to plot</td><td><select id=\"" . $img_id . "_customize_right_y_axis_data_set_list\" style=\"width: 130px\"><option value=\"-1\">None</option>";
        for($i = 0; $i < MAX_NO_OF_DATA_SETS; $i++)
            print "<option value=\"$i\">Data Set " . ($i + 1 ) . "</option>";
        print "</select></td></tr>";
        print "<tr><td colspan=\"2\">Right Y-Axis Scale<input type=\"hidden\" id=\"" . $img_id . "_customize_right_y_axis_hidden\" value=\"0\"></td></tr>";
        print "<tr><td colspan=\"2\"><input type=\"radio\" name=\"" . $img_id . "_customize_right_y_axis_scale_radio\" value=\"Linear\" onclick=\"right_y_axis_scale_radio_changed('$img_id', this)\" checked>Linear</input>&nbsp;<input type=\"radio\" name=\"" . $img_id . "_customize_right_y_axis_scale_radio\" value=\"Semi-Log\" onclick=\"right_y_axis_scale_radio_changed('$img_id', this)\">Semi-Log</input>&nbsp;<input type=\"radio\" name=\"" . $img_id . "_customize_right_y_axis_scale_radio\" value=\"Scaling Factor\" onclick=\"right_y_axis_scale_radio_changed('$img_id', this)\">Scaling Factor</input>&nbsp;<input type=\"radio\" name=\"" . $img_id . "_customize_right_y_axis_scale_radio\" value=\"Custom\" onclick=\"right_y_axis_scale_radio_changed('$img_id', this)\">Custom</input></td></tr>";
        print "<tr id=\"" . $img_id . "_customize_right_y_axis_scale_0_row\" style=\"display: none\"><td nowrap>Scaling Factor</td><td><select id=\"" . $img_id . "_customize_right_y_axis_scale_factor_list\"><option value=\"K\">Kilo (1" . $grouping_separator . "000s)</option><option value=\"M\">Mega (1" . $grouping_separator . "000". $grouping_separator ."000s)</option><option value=\"G\">Giga (1" . $grouping_separator ."000" . $grouping_separator ."000" . $grouping_separator ."000s)</option></select></td></tr>";
        print "<tr id=\"" . $img_id . "_customize_right_y_axis_scale_1_row\" style=\"display: none\"><td nowrap>Custom Scale Min</td><td><input id=\"" . $img_id . "_customize_right_y_axis_scale_custom_min_tf\" value=\"0\"></td></tr>";
        print "<tr id=\"" . $img_id . "_customize_right_y_axis_scale_2_row\" style=\"display: none\"><td nowrap>Custom Scale Max</td><td><input id=\"" . $img_id . "_customize_right_y_axis_scale_custom_max_tf\" value=\"100\"></td></tr>";
        print "</table>";
        print "</div>";
    }

    function print_multiple_axes_section($img_id)
    {
        print "<div id=\"" . $img_id . "_customize_multiple_axes_cell\" style=\"display: none\">";
        print "<table class=\"full_width_table\">";
        print "<tr><td>Multiple Axes<input type=\"hidden\" id=\"" . $img_id . "_customize_multiple_axes_hidden\" value=\"0\"></td><td><input type=\"radio\" name=\"" . $img_id . "_customize_multiple_axes_radio\" value=\"\" checked>Disable</input>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"" . $img_id . "_customize_multiple_axes_radio\" value=\"1\">Enable</input></td></tr>";
        print "<tr><td colspan=\"2\" class=\"customize_help_text\">Enable multiple axes to plot each data set in its own hidden y-axis. It is useful to observe patterns of multiple data sets without scaling issues.</td></tr>";
        print "</table>";
        print "</div>";
    }

    function print_plot_bg_color_section($img_id)
    {
        print "<div id=\"" . $img_id . "_customize_plot_bg_color_cell\" style=\"display: none\">";
        print "<table><tr><td nowrap>Background Color</td><td><input id=\"" . $img_id . "_customize_plot_bg_color_tf\" class=\"color\" value=\"FFFFFF\" readonly><input type=\"hidden\" id=\"" . $img_id . "_customize_plot_bg_color_hidden\" value=\"0\"></td></tr>";
        print "<tr><td>Transparency</td><td>";
        print "<div id=\"" . $img_id . "_plot_bg_color_transparency_div\" class=\"yui-skin-sam\"></div>";
        print "</td></tr>";
        print "<tr><td>Effects</td><td><input type=\"radio\" name=\"" . $img_id . "_customize_plot_bg_color_effect_radio\" value=\"Gradient\" onclick=\"plot_bg_color_effect_radio_changed('$img_id', this)\">Gradient</input>&nbsp;<input type=\"radio\" name=\"" . $img_id . "_customize_plot_bg_color_effect_radio\" value=\"Modulation\" onclick=\"plot_bg_color_effect_radio_changed('$img_id', this)\">Modulation</input>&nbsp;<input type=\"radio\" name=\"" . $img_id . "_customize_plot_bg_color_effect_radio\" value=\"None\" onclick=\"plot_bg_color_effect_radio_changed('$img_id', this)\" checked>None</input></td></tr>";
        print "<tr id=\"" . $img_id . "_customize_plot_bg_color_0_row\" style=\"display: none\"><td>Gradient Color</td><td><input id=\"" . $img_id . "_customize_plot_bg_color_gradient_color_tf\" class=\"color\" value=\"FFFFFF\" readonly></td></tr>";
        print "<tr id=\"" . $img_id . "_customize_plot_bg_color_1_row\" style=\"display: none\"><td>Transparency</td><td>";
        print "<div id=\"" . $img_id . "_plot_bg_color_gradient_transparency_div\" class=\"yui-skin-sam\"></div>";
        print "</td></tr>";
        print "<tr id=\"" . $img_id . "_customize_plot_bg_color_2_row\" style=\"display: none\"><td>Modulation Angle</td><td>";
        print "<div id=\"" . $img_id . "_plot_bg_color_modulation_angle_div\" class=\"yui-skin-sam\"></div>";
        print "</td></tr>";
        print "</table>";
        print "</div>";
    }

    function print_plot_edge_color_section($img_id)
    {
        print "<div id=\"" . $img_id . "_customize_plot_edge_color_cell\" style=\"display: none\">";
        print "<table><tr><td nowrap>Background Color</td><td><input id=\"" . $img_id . "_customize_plot_edge_color_tf\" class=\"color\" value=\"336699\" readonly><input type=\"hidden\" id=\"" . $img_id . "_customize_plot_edge_color_hidden\" value=\"0\"></td></tr>";
        print "<tr><td>Transparency</td><td>";
        print "<div id=\"" . $img_id . "_plot_edge_color_transparency_div\" class=\"yui-skin-sam\"></div>";
        print "</td></tr>";
        print "<tr><td>Effects</td><td><input type=\"radio\" name=\"" . $img_id . "_customize_plot_edge_color_effect_radio\" value=\"Gradient\" onclick=\"plot_edge_color_effect_radio_changed('$img_id', this)\">Gradient</input>&nbsp;<input type=\"radio\" name=\"" . $img_id . "_customize_plot_edge_color_effect_radio\" value=\"Modulation\" onclick=\"plot_edge_color_effect_radio_changed('$img_id', this)\">Modulation</input>&nbsp;<input type=\"radio\" name=\"" . $img_id . "_customize_plot_edge_color_effect_radio\" value=\"None\" onclick=\"plot_edge_color_effect_radio_changed('$img_id', this)\" checked>None</input></td></tr>";
        print "<tr id=\"" . $img_id . "_customize_plot_edge_color_0_row\" style=\"display: none\"><td>Gradient Color</td><td><input id=\"" . $img_id . "_customize_plot_edge_color_gradient_color_tf\" class=\"color\" value=\"FFFFFF\" readonly></td></tr>";
        print "<tr id=\"" . $img_id . "_customize_plot_edge_color_1_row\" style=\"display: none\"><td>Transparency</td><td>";
        print "<div id=\"" . $img_id . "_plot_edge_color_gradient_transparency_div\" class=\"yui-skin-sam\"></div>";
        print "</td></tr>";
        print "<tr id=\"" . $img_id . "_customize_plot_edge_color_2_row\" style=\"display: none\"><td>Modulation Angle</td><td>";
        print "<div id=\"" . $img_id . "_plot_edge_color_modulation_angle_div\" class=\"yui-skin-sam\"></div>";
        print "</td></tr>";
        print "</table>";
        print "</div>";
    }

    function print_h_grid_section($img_id)
    {
        print "<div id=\"" . $img_id . "_customize_h_grid_cell\" style=\"display: none\">";
        print "<table class=\"full_width_table\">";
        print "<tr><td>Horizontal Grids<input type=\"hidden\" id=\"" . $img_id . "_customize_h_grid_hidden\" value=\"0\"></td><td><input type=\"radio\" name=\"" . $img_id . "_customize_h_grid_radio\" onclick=\"h_grid_radio_changed('$img_id', this)\" value=\"Off\">Off</input>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"" . $img_id . "_customize_h_grid_radio\" onclick=\"h_grid_radio_changed('$img_id', this)\" value=\"On\" checked>On</input></td></tr>";
        print "<tr id=\"" . $img_id . "_customize_h_grid_0_row\"><td>Grids Width</td><td><select id=\"" . $img_id . "_customize_h_grid_width_list\" style=\"width: 130px\"><option value=\"1\">1</option><option value=\"2\">2</option><option value=\"3\">3</option><option value=\"4\">4</option><option value=\"5\">5</option></td></tr>";
        print "<tr id=\"" . $img_id . "_customize_h_grid_1_row\"><td>Grids Color</td><td><input id=\"" . $img_id . "_customize_h_grid_color_tf\" class=\"color\" value=\"CCCCCC\" readonly></td></tr>";
        print "<tr id=\"" . $img_id . "_customize_h_grid_2_row\"><td>Transparency</td><td>";
        print "<div id=\"" . $img_id . "_h_grid_color_transparency_div\" class=\"yui-skin-sam\"></div>";
        print "</td></tr>";
        print "<tr id=\"" . $img_id . "_customize_h_grid_3_row\"><td>Effects</td><td><input type=\"radio\" name=\"" . $img_id . "_customize_h_grid_color_effect_radio\" value=\"Gradient\" onclick=\"h_grid_color_effect_radio_changed('$img_id', this, true)\">Gradient</input>&nbsp;<input type=\"radio\" name=\"" . $img_id . "_customize_h_grid_color_effect_radio\" value=\"Modulation\" onclick=\"h_grid_color_effect_radio_changed('$img_id', this, true)\">Modulation</input>&nbsp;<input type=\"radio\" name=\"" . $img_id . "_customize_h_grid_color_effect_radio\" value=\"None\" onclick=\"h_grid_color_effect_radio_changed('$img_id', this, true)\" checked>None</input></td></tr>";
        print "<tr id=\"" . $img_id . "_customize_h_grid_4_row\" style=\"display: none\"><td>Gradient Color</td><td><input id=\"" . $img_id . "_customize_h_grid_color_gradient_color_tf\" class=\"color\" value=\"FFFFFF\" readonly></td></tr>";
        print "<tr id=\"" . $img_id . "_customize_h_grid_5_row\" style=\"display: none\"><td>Transparency</td><td>";
        print "<div id=\"" . $img_id . "_h_grid_color_gradient_transparency_div\" class=\"yui-skin-sam\"></div>";
        print "</td></tr>";
        print "<tr id=\"" . $img_id . "_customize_h_grid_6_row\" style=\"display: none\"><td>Modulation Angle</td><td>";
        print "<div id=\"" . $img_id . "_h_grid_color_modulation_angle_div\" class=\"yui-skin-sam\"></div>";
        print "</td></tr>";
        print "</table>";
        print "</div>";
    }

    function print_v_grid_section($img_id)
    {
        print "<div id=\"" . $img_id . "_customize_v_grid_cell\" style=\"display: none\">";
        print "<table class=\"full_width_table\">";
        print "<tr><td>Vertical Grids<input type=\"hidden\" id=\"" . $img_id . "_customize_v_grid_hidden\" value=\"0\"></td><td><input type=\"radio\" name=\"" . $img_id . "_customize_v_grid_radio\" onclick=\"v_grid_radio_changed('$img_id', this)\" value=\"Off\">Off</input>&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"radio\" name=\"" . $img_id . "_customize_v_grid_radio\" onclick=\"v_grid_radio_changed('$img_id', this)\" value=\"On\" checked>On</input></td></tr>";
        print "<tr id=\"" . $img_id . "_customize_v_grid_0_row\"><td>Grids Width</td><td><select id=\"" . $img_id . "_customize_v_grid_width_list\" style=\"width: 130px\"><option value=\"1\">1</option><option value=\"2\">2</option><option value=\"3\">3</option><option value=\"4\">4</option><option value=\"5\">5</option></td></tr>";
        print "<tr id=\"" . $img_id . "_customize_v_grid_1_row\"><td>Grids Color</td><td><input id=\"" . $img_id . "_customize_v_grid_color_tf\" class=\"color\" value=\"CCCCCC\" readonly></td></tr>";
        print "<tr id=\"" . $img_id . "_customize_v_grid_2_row\"><td>Transparency</td><td>";
        print "<div id=\"" . $img_id . "_v_grid_color_transparency_div\" class=\"yui-skin-sam\"></div>";
        print "</td></tr>";
        print "<tr id=\"" . $img_id . "_customize_v_grid_3_row\"><td>Effects</td><td><input type=\"radio\" name=\"" . $img_id . "_customize_v_grid_color_effect_radio\" value=\"Gradient\" onclick=\"v_grid_color_effect_radio_changed('$img_id', this, true)\">Gradient</input>&nbsp;<input type=\"radio\" name=\"" . $img_id . "_customize_v_grid_color_effect_radio\" value=\"Modulation\" onclick=\"v_grid_color_effect_radio_changed('$img_id', this, true)\">Modulation</input>&nbsp;<input type=\"radio\" name=\"" . $img_id . "_customize_v_grid_color_effect_radio\" value=\"None\" onclick=\"v_grid_color_effect_radio_changed('$img_id', this, true)\" checked>None</input></td></tr>";
        print "<tr id=\"" . $img_id . "_customize_v_grid_4_row\" style=\"display: none\"><td>Gradient Color</td><td><input id=\"" . $img_id . "_customize_v_grid_color_gradient_color_tf\" class=\"color\" value=\"FFFFFF\" readonly></td></tr>";
        print "<tr id=\"" . $img_id . "_customize_v_grid_5_row\" style=\"display: none\"><td>Transparency</td><td>";
        print "<div id=\"" . $img_id . "_v_grid_color_gradient_transparency_div\" class=\"yui-skin-sam\"></div>";
        print "</td></tr>";
        print "<tr id=\"" . $img_id . "_customize_v_grid_6_row\" style=\"display: none\"><td>Modulation Angle</td><td>";
        print "<div id=\"" . $img_id . "_v_grid_color_modulation_angle_div\" class=\"yui-skin-sam\"></div>";
        print "</td></tr>";
        print "</table>";
        print "</div>";
    }

    function print_data_set_color_section($img_id, $i, $default_color)
    {
        print "<div id=\"" . $img_id . "_customize_data_set_color_" . $i . "_cell\" style=\"display: none\">";

        print "<table><tr><td nowrap>Background Color</td><td><input id=\"" . $img_id . "_customize_data_set_color_" . $i . "_tf\" class=\"color\" value=\"$default_color\" readonly><input type=\"hidden\" id=\"" . $img_id . "_customize_data_set_color_" . $i . "_hidden\" value=\"0\"></td></tr>";
        print "<tr><td>Transparency</td><td>";
        print "<div id=\"" . $img_id . "_data_set_color_" . $i . "_transparency_div\" class=\"yui-skin-sam\"></div>";
        print "</td></tr>";
        print "<tr><td>Effects</td><td><input type=\"radio\" name=\"" . $img_id . "_customize_data_set_color_" . $i . "_effect_radio\" value=\"Gradient\" onclick=\"data_set_color_effect_radio_changed('$img_id', $i, this)\">Gradient</input>&nbsp;<input type=\"radio\" name=\"" . $img_id . "_customize_data_set_color_" . $i . "_effect_radio\" value=\"Modulation\" onclick=\"data_set_color_effect_radio_changed('$img_id', $i, this)\">Modulation</input>&nbsp;<input type=\"radio\" name=\"" . $img_id . "_customize_data_set_color_" . $i . "_effect_radio\" value=\"None\" onclick=\"data_set_color_effect_radio_changed('$img_id', $i, this)\" checked>None</input></td></tr>";
        print "<tr id=\"" . $img_id . "_customize_data_set_color_" . $i . "_0_row\" style=\"display: none\"><td>Gradient Color</td><td><input id=\"" . $img_id . "_customize_data_set_color_" . $i . "_gradient_color_tf\" class=\"color\" value=\"FFFFFF\" readonly></td></tr>";
        print "<tr id=\"" . $img_id . "_customize_data_set_color_" . $i . "_1_row\" style=\"display: none\"><td>Transparency</td><td>";
        print "<div id=\"" . $img_id . "_data_set_color_" . $i . "_gradient_transparency_div\" class=\"yui-skin-sam\"></div>";
        print "</td></tr>";
        print "<tr id=\"" . $img_id . "_customize_data_set_color_" . $i . "_2_row\" style=\"display: none\"><td>Modulation Angle</td><td>";
        print "<div id=\"" . $img_id . "_data_set_color_" . $i . "_modulation_angle_div\" class=\"yui-skin-sam\"></div>";
        print "</td></tr>";
        print "</table>";
        print "</div>";
    }

    function print_data_set_plot_type_section($img_id, $i)
    {
        print "<div id=\"" . $img_id . "_customize_data_set_plot_type_" . $i . "_cell\" style=\"display: none\">";
        print "<table id=\"" . $img_id . "_data_set_plot_type_" . $i . "_table\" class=\"plot_type_table\">";
        print "<tr>";
        print "<td id=\"" . $img_id . "_data_set_plot_type_" . $i . "_line_cell\" class=\"plot_type_table_cell_selected\"><a href=\"#noAnchor\" onclick=\"select_data_set_plot_type('$img_id', $i, this.parentNode)\"><img src=\"/admin_ui/common/images/line.png\" alt=\"Line Chart\" border=\"0\" title=\"Line Chart\"></a><input type=\"hidden\" id=\"" . $img_id . "_customize_data_set_plot_type_" . $i . "_hidden\" value=\"0\"></td>";
        print "<td id=\"" . $img_id . "_data_set_plot_type_" . $i . "_spline_cell\"><a href=\"#noAnchor\" onclick=\"select_data_set_plot_type('$img_id', $i, this.parentNode)\"><img src=\"/admin_ui/common/images/spline.png\" alt=\"Spline Chart\" border=\"0\" title=\"Spline Chart\"></a></td>";
        print "<td id=\"" . $img_id . "_data_set_plot_type_" . $i . "_step_cell\"><a href=\"#noAnchor\" onclick=\"select_data_set_plot_type('$img_id', $i, this.parentNode)\"><img src=\"/admin_ui/common/images/step_line.png\" alt=\"Step Line Chart\" border=\"0\" title=\"Step Line Chart\"></a></td>";
        print "<td id=\"" . $img_id . "_data_set_plot_type_" . $i . "_scatter_cell\"><a href=\"#noAnchor\" onclick=\"select_data_set_plot_type('$img_id', $i, this.parentNode)\"><img src=\"/admin_ui/common/images/scatter.png\" alt=\"Scatter Chart\" border=\"0\" title=\"Scatter Chart\"></a></td>";
        print "</tr>";
        print "<tr>";
        print "<td id=\"" . $img_id . "_data_set_plot_type_" . $i . "_area_cell\"><a href=\"#noAnchor\" onclick=\"select_data_set_plot_type('$img_id', $i, this.parentNode)\"><img src=\"/admin_ui/common/images/area.png\" alt=\"Area chart\" border=\"0\" title=\"Area chart\"></a></td>";
        print "<td id=\"" . $img_id . "_data_set_plot_type_" . $i . "_bar_cell\"><a href=\"#noAnchor\" onclick=\"select_data_set_plot_type('$img_id', $i, this.parentNode)\"><img src=\"/admin_ui/common/images/bar.png\" alt=\"Bar chart\" border=\"0\" title=\"Bar chart\"></a></td>";
        print "<td id=\"" . $img_id . "_data_set_plot_type_" . $i . "_sarea_cell\"><a href=\"#noAnchor\" onclick=\"select_data_set_plot_type('$img_id', $i, this.parentNode)\"><img src=\"/admin_ui/common/images/sarea.png\" alt=\"Stacked Area chart\" border=\"0\" title=\"Stacked Area chart\"></a></td>";
        print "<td id=\"" . $img_id . "_data_set_plot_type_" . $i . "_sbar_cell\"><a href=\"#noAnchor\" onclick=\"select_data_set_plot_type('$img_id', $i, this.parentNode)\"><img src=\"/admin_ui/common/images/sbar.png\" alt=\"Stacked Bar chart\" border=\"0\" title=\"Stacked Bar chart\"></a></td>";
        print "</tr>";
        print "</table>";
        print "<input type=\"checkbox\" id=\"" . $img_id . "_data_set_plot_type_" . $i . "_ckb\">Use 3D</input>";
        print "</div>";
    }
}
?>