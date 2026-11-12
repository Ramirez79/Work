<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once("phpchartdir.php");
require_once("locale_utils.php");

abstract class base_chart
{
    public $width;
    public $height;

    public $bg_color = WHITE_COLOR;
    public $border_color = BORDER_COLOR;
    public $border_style = 0; //To have a raised effect, set > 0
    public $value_font = HELVETICA_FONT;
    public $value_font_color = BLUE_COLOR;
    public $value_font_size = 9;
    public $value_align = Center;
    public $text_font = HELVETICA_FONT;
    public $text_font_color = BLUE_COLOR;
    public $error_font_color = RED_COLOR;
    public $text_font_size = 8;
    public $text_align = Center;
    public $title_font_size = 9;
    public $raised_effect = 0;
    public $title = "";
    public $labels = array();
    public $label_format;
    public $zones_max = array(80, 90, 100);
    public $zones_color = array(GREEN_COLOR, YELLOW_COLOR, RED_COLOR);

    //Given an array of elements, this predicts the max width required in pixels
    protected function calculate_width($values)
    {
        $count = count($values);
        $max_length = 3;   //Required for all zero values
        for($i = 0; $i < $count; $i++)
        {
            if($values[$i] == NoValue)
                continue;
            $len = strlen(ceil($values[$i]));
            if($len > $max_length)
                $max_length = $len;
        }
        //to account for commas (,) at every thousand border
        //also if the max val is 999, labels could be 1,000 & 1,200. So give some more spacing
        $max_length += (ceil($max_length/3) - 1) + 1;
        return ($max_length * $this->value_font_size * 2/3 + 2); //we don't know exactly how much pixel each character takes
    }

    protected function calculate_max_value($values)
    {
        $count = count($values);
        $max = 0;
        for($i = 0; $i < $count; $i++)
        {
            if($values[$i] == NoValue)
                continue;
            if($values[$i] > $max)
                $max = $values[$i];
        }
        return $max;
    }

    public function print_chart($values)
    {
        if(!$this->is_data_available($values))
        {
            require_once(APPPATH."controllers/common/utils.php");
            return $this->print_warning_chart(utils::get_error_message("NO_DATA_AVAILABLE"));
        }
        setLicenseCode(LICENSE_CODE);
        header(IMAGE_HEADER);
        return $this->output_chart($values);
    }

    abstract protected function output_chart($values);

    protected function is_data_available($values)
    {
        return true;
    }

    public function print_warning_chart($error_message)
    {
        $this->error_font_color = $this->text_font_color;
        return $this->print_error_chart($error_message);
    }

    public function print_error_chart($error_message)
    {
        setLicenseCode(LICENSE_CODE);
        header(IMAGE_HEADER);
        if(method_exists($this, "initialize_width"))
            $this->initialize_width();
        $c = new XYChart($this->width, $this->height, $this->bg_color, $this->border_color, $this->raised_effect);
        if(method_exists($this, "set_chart_properties"))
            $this->set_chart_properties($c);
        $c->addTitle($error_message, $this->text_font, $this->title_font_size, $this->error_font_color);
        print($c->makeChart2(PNG));
        return null;
    }
}

class dial extends base_chart
{
    public $width = 150;
    public $height =130;

    public $span_left = -90;
    public $span_right = 90;
    public $scale_left = 0;
    public $scale_right = 100;
    public $major_tick_interval = 20;
    public $minor_tick_interval = 10;
    public $micro_tick_interval = 5;
    public $default_angular_arc = 0;
    public $major_tick_line_width = 1;
    public $minor_tick_line_width = 1;
    public $pointer_color;

    public $pointer_x;
    public $pointer_y;
    public $radius;
    public $text_x;
    public $text_y;
    public $value_x;
    public $value_y;

    public function __construct()
    {
        $this->label_format = "{value|2" . locale_utils::get_grouping_separator() . locale_utils::get_decimal_separator() . "}%";
        $this->zones_color = array(metalColor(GREEN_COLOR, 45), metalColor(YELLOW_COLOR, 135), metalColor(RED_COLOR, 135));
        $this->pointer_color = metalColor(BLUE_COLOR, 115);
    }

    protected function output_chart($values)
    {
        $values_count = count($values);

        $this->pointer_x = $this->width/2;
        $this->pointer_y = $this->height - $this->value_font_size - 20;
        $this->radius = $this->pointer_x - 10;
        $this->text_x = $this->width/2;
        $this->text_y = 20;
        $this->value_x = $this->width/2 + 6;
        $this->value_y = $this->height - $this->value_font_size + 3;

        //Set transparency only on non IE6 browsers (as IE6 can't display PNG files with alpha transparency)
        $is_client_browser_IE6 = locale_utils::is_client_browser_IE6();
        if(!$is_client_browser_IE6)
        {
            $this->bg_color = Transparent;
            $this->border_color = Transparent;
        }

        $multi_chart = new MultiChart($this->width * $values_count, $this->height, $this->bg_color, $this->border_color, $this->border_style);
        if(!$is_client_browser_IE6)
            $multi_chart->setTransparentColor(-1);
        $x_pos = 0;
        for($index = 0; $index < $values_count; $index++)
        {
            $value = $values[$index];
            $title = isset($this->title[$index]) ? $this->title[$index] : "";
            $m = new AngularMeter($this->width, $this->height, $this->bg_color, $this->border_color, $this->border_style);
            if(!$is_client_browser_IE6)
                $m->setTransparentColor(-1);
            $m->setMeter($this->pointer_x, $this->pointer_y, $this->radius, $this->span_left, $this->span_right);
            $m->setScale($this->scale_left, $this->scale_right, $this->major_tick_interval, $this->minor_tick_interval, $this->micro_tick_interval);
            $m->setLineWidth($this->default_angular_arc, $this->major_tick_line_width, $this->minor_tick_line_width);
            $m->setLabelStyle($this->text_font, $this->text_font_size, $this->text_font_color);
            for($i = 0; $i < count($this->zones_max); $i++)
            {
                if($i == 0)
                    $min = $this->scale_left;
                else
                    $min = $this->zones_max[$i-1];
                $m->addZone($min, $this->zones_max[$i], $this->zones_color[$i]);
            }
            $m->addText($this->value_x, $this->value_y, $m->formatValue($value, $this->label_format), $this->value_font, $this->value_font_size, $this->value_font_color, $this->value_align);
            $text_box_obj = $m->addText($this->text_x, $this->text_y, $title, $this->text_font, $this->text_font_size, $this->text_font_color, $this->text_align);
            $text_box_obj->setBackground($this->bg_color, $this->bg_color, $this->border_style);
            $m->addPointer($value, $this->pointer_color);
            $m->setCap(1, WHITE_COLOR, WHITE_COLOR);

            $multi_chart->addChart($x_pos, 0, $m);
            $x_pos += $this->width;
        }
        print($multi_chart->makeChart2(PNG));
        return null;
    }
}

class pie_chart extends base_chart
{
    public $width = 240;
    public $height = 150;

    public $is_3D = true;
    public $explode_index = -1;
    public $value_font_size = 7;
    public $value_font_color = BLACK_COLOR;
    public $border_color = WHITE_COLOR;
    public $colors = array(WHITE_COLOR, BLACK_COLOR, BLACK_COLOR, 0x808080, 0x808080, 0x808080, 0x808080, 0x808080);
    public $pie_colors = array( 0x8000ff00, 0x80ff0000, 0x800000ff, 0x80ffff00,
                                0x80ff00ff, 0x8066ffff, 0x80ffcc33, 0x80cccccc,
                                0x809966ff, 0x80339966, 0x80999900, 0x80cc3300,
                                0x8099cccc, 0x80006600, 0x80660066, 0x80cc9999,
                                0x80ff9966, 0x8099ff99, 0x809999ff, 0x80cc6600,
                                0x8033cc33, 0x80cc99ff, 0x80ff6666, 0x8099cc66,
                                0x80009999, 0x80cc3333, 0x809933ff, 0x80ff0000,
                                0x800000ff, 0x8000ff00, 0x80ffcc99, 0x80999999);

    public $center_x;
    public $center_y;
    public $radius;

    public function __construct()
    {
        $this->label_format = "{label}<*br*>{percent|2" . locale_utils::get_grouping_separator() . locale_utils::get_decimal_separator() . "}%";
    }

    protected function is_data_available($values)
    {
        foreach($values as $value)
        {
            if($value != 0)
                return true;
        }
        return false;
    }

    protected function output_chart($values)
    {
        $this->center_x = $this->width/2;
        $this->center_y = $this->height/2;
        $this->radius = $this->height/4;

        //Show only non-zero values and corresponding labels
        $new_labels = array("");
        $new_values = array(0);
        $new_pie_colors = array(0x8000ff00);
        $count = count($values);
        for($i = 0, $j = 0; $i < $count; $i++)
        {
            if($values[$i] == 0)
                continue;
            if(isset($this->labels[$i]))
                $new_labels[$j] = $this->labels[$i];
            $new_pie_colors[$j] = $this->pie_colors[$i];
            $new_values[$j++] = $values[$i];
        }
        $this->labels = $new_labels;
        $values = $new_values;
        $this->pie_colors = $new_pie_colors;

        $c = new PieChart($this->width, $this->height);
        $c->setPieSize($this->center_x, $this->center_y, $this->radius);
        $c->addTitle($this->title, $this->text_font, $this->text_font_size, $this->text_font_color);
        $c->setLabelFormat($this->label_format);
        $c->setColors(array_merge($this->colors, $this->pie_colors)); //TODO transparentPalette() doesn't work?
        //Use SideLayout to avoid labels overlapping. Set top bound to avoid label overlapping with title on top
        $c->setLabelLayout(SideLayout, -1, $this->text_font_size * 2);
        $c->setLineColor(SameAsMainColor, SameAsMainColor);
        $textbox = $c->setLabelStyle($this->value_font, $this->value_font_size, $this->value_font_color);
        $textbox->setBackground(SameAsMainColor, Transparent, glassEffect());
        $textbox->setRoundedCorners(5);
        if($this->is_3D)
            $c->set3D();
        if($this->explode_index >= 0)
            $c->setExplode($this->explode_index);
        $c->setData($values, $this->labels);
        print($c->makeChart2(PNG));
        return null;
    }
}

class bar_chart extends base_chart
{
    public $use_border_color = false;
    public $bar_thickness = null;
    public $horizontal = true;
    public $bar_gap = 0.1;
    public $linear_scale = true;
    public $linear_scale_min = 0;
    public $linear_scale_max = 100;
    public $bg_bar = false;
    public $linear_scale_bg_color = TRANSPARENT_WHITE_COLOR;
    public $aggregate_label = true;
    public $use_zones;

    public $plot_x = 0;
    public $plot_y = 0;
    public $plot_width;
    public $plot_height;

    public function __construct()
    {
        $this->label_format = "{value|" . locale_utils::get_grouping_separator() . locale_utils::get_decimal_separator() . "}%";
    }

    protected function output_chart($values)
    {
        $this->label_format = preg_replace("/%%GRP_SEP%%/", locale_utils::get_grouping_separator(), $this->label_format);
        $this->label_format = preg_replace("/%%DEC_SEP%%/", locale_utils::get_decimal_separator(), $this->label_format);
        if($this->horizontal)
        {
            if(strlen($this->title) > 0)
                $this->plot_y = $this->text_font_size * 2 + 6;
            else
                $this->plot_y = -5;
            /* Keep this in sync table rows height for proper alignment of bars
             * .fixed_header_column_table .fixed_headers table tr
             * .fixed_header_column_table .content_table tr
             * .ns_table_data tr
             */
            if($this->bar_thickness == null)
                $this->bar_thickness = 25.3;
            $this->width = 315;
            $this->height = (count($values) * $this->bar_thickness);
            if(strlen($this->title) > 0)
                $this->height += ($this->text_font_size * 2);
            if(count($this->labels) == 0)
                $this->plot_x = 0;
            else
                $this->plot_x = $this->calculate_width($this->labels);
            $this->plot_width = $this->width - $this->plot_x - $this->calculate_width($values) - 10;
            $this->plot_height = $this->height - $this->plot_y - 1;
        }
        else
        {
            if(strlen($this->title) > 0)
                $this->plot_y = $this->text_font_size * 2;
            /*$bar_thickness = 19;
            $this->width = ($this->calculate_width($values) * 2 * count($values));// * $this->bar_thickness);*/
            $this->width = 130;
            $this->height = 130;
            $this->plot_width = $this->width - $this->plot_x;
            $this->plot_height = $this->height - $this->plot_y - 18;
            $this->bar_gap = 0.5;
        }
        if(!$this->use_border_color)
            $this->border_color = Transparent;

        $c = new XYChart($this->width, $this->height, $this->bg_color);
        $c->swapXY($this->horizontal);
        $c->addTitle($this->title, $this->text_font, $this->text_font_size, $this->text_font_color);
        $c->setPlotArea($this->plot_x, $this->plot_y, $this->plot_width, $this->plot_height,
                        $this->bg_color, $this->bg_color, $this->border_color, Transparent, Transparent);
        if($this->linear_scale)
        {
            if($this->linear_scale_max == "MAX_OF_VALUES")
                $this->linear_scale_max = $this->calculate_max_value($values);
            $c->yAxis()->setLinearScale($this->linear_scale_min, $this->linear_scale_max);
        }
        $count = count($values);
        $data_color_array = array();
        for($i = 0; $i < $count; $i++)
        {
            if($this->use_zones)
            {
                for($j = 0; $j < count($this->zones_max); $j++)
                {
                    if($values[$i] <= $this->zones_max[$j])
                    {
                        $data_color_array[$i] = $this->zones_color[$j];
                        break;
                    }
                }
            }
            else
                $data_color_array[$i] = $this->value_font_color;
        }
        $layer = $c->addBarLayer3($values, $data_color_array);
        $layer->setBarShape(CircleShape);
        $layer->setBarGap($this->bar_gap);
        if($this->aggregate_label)
        {
            $layer->setAggregateLabelFormat($this->label_format);
            $layer->setAggregateLabelStyle($this->text_font, $this->text_font_size, $this->text_font_color);
        }
        else
        {
            $layer->setDataLabelFormat($this->label_format);
            $layer->setDataLabelStyle($this->text_font, $this->text_font_size, BLACK_COLOR);
        }
        if($this->linear_scale && $this->bg_bar)
        {
            $values1 = array();
            $data_color_array1 = array();
            for($i = 0; $i < $count; $i++)
            {
                $values1[$i] = $this->linear_scale_max;
                $data_color_array1[$i] = $this->linear_scale_bg_color;
            }
            $bg_layer = $c->addBarLayer3($values1, $data_color_array1);
            $bg_layer->setBarShape(CircleShape);
            $bg_layer->setBarGap($this->bar_gap);
        }
        $textbox = $c->xAxis->setLabels($this->labels);
        $textbox->setFontStyle($this->text_font);
        $textbox->setFontSize($this->text_font_size);
        $c->xAxis->setColors(Transparent, $this->text_font_color);
        $c->yAxis->setColors(Transparent, Transparent);
        print($c->makeChart2(PNG));
        return null;
    }
}

class xy_chart extends base_chart
{
    public $min_width = 700;
    public $max_width = 2000;
    public $height = 200;
    public $plot_x = 0;
    public $plot_y = 20;
    public $plot_width;
    public $plot_height = 140;  //fixed
    public $plot_bg_color = WHITE_COLOR;
    public $plot_edge_color = BLUE_COLOR;
    public $x_labels = "";
    public $x_labels_format;
    public $plot_type = LINE_GRAPH;
    public $_3d;
    public $h_grid_color = GREY_COLOR;
    public $v_grid_color = GREY_COLOR;
    public $h_grid_width = 1;
    public $v_grid_width = 1;
    public $legend_y = 0;
    public $legend_spacing = 10;
    public $percentile = 95;
    public $text_font = HELVETICA_FONT;
    public $max_data_symbols = 15;
    public $max_data_points_for_tooltip = 5000;
    public $max_data_points_for_tooltip_msg = "Tooltips are not available due to large number of data points.";
    public $interval_in_seconds = 300;
    public $events;
    public $events_symbol_offset = 0.15;
    public $chart_metrics;
    public $scale;
    public $scale2;
    public $y_axis2 = -1;
    public $multiple_axes;
    public $data_sets_properties = array();

    public $scales = array("K"  =>  1000,
                           "M"  =>  1000000,
                           "G"  =>  1000000000);
    //Default color palette, copied in print_customize_section() of reporting_customization.php.
    public $line_colors = array(0xff7f2a, 0xffd332, 0x1bae44, 0x008fc7,
                                0x09c0e3, 0xCC5480, 0xef3124, 0xffbe00,
                                0xdb4c3c, 0xc5881c, 0x6a93c7, 0x386398,
                                0x669999, 0x993333, 0x006600, 0x990099,
                                0xff9966, 0x99ff99, 0x9999ff, 0xcc6600,
                                0x33cc33, 0xcc99ff, 0xff6666, 0x99cc66,
                                0x009999, 0xcc3333, 0x9933ff, 0xff0000,
                                0x0000ff, 0x00ff00, 0xffcc99, 0x999999,
                                0xff3333, 0x6666ff, 0xffff00, 0x000000);
    public $symbols = array(TriangleShape, SquareShape, DiamondShape, CircleShape,
                            LeftTriangleShape, InvertedTriangleShape, RightTriangleShape);

    public function __construct()
    {
        $this->label_format = "{value|" . locale_utils::get_grouping_separator() . locale_utils::get_decimal_separator() . "}";
    }

    protected function is_data_available($data_sets)
    {
        foreach($data_sets as $data_set)
        {
            foreach($data_set as $data)
            {
                if($data != NoValue)
                    return true;
            }
        }
        return false;
    }

    private function get_mark_formats($values_count)
    {
        $this->x_labels_format = ($this->interval_in_seconds % 60) ? "{value|hh:nn:ss<*br*>mmm dd yyyy}" : "{value|hh:nn<*br*>mmm dd yyyy}";
        $gap = floor($values_count/6);
        $gap = ($gap < 2) ? 2 : $gap;
        $min_mark_index = 0;
        $max_mark_index = $values_count - $gap;
        $day_change = array();
        $mark_formats = array();
        $is_x_axis_label_wrapped = false;
        for($i = 0; $i < $values_count; $i++)
        {
            $mark_format = "{value|hh:nn<*br*>mmm dd}";
            if($this->interval_in_seconds <= 7)           //7 seconds interval
                $mark_format = "{value|hh:nn:ss";
            else if($this->interval_in_seconds <= 300)    //5 minutes interval
                $mark_format = "{value|hh:nn";
            else if($this->interval_in_seconds <= 350)    //350 seconds interval
                $mark_format = "{value|hh:nn:ss";
            else if($this->interval_in_seconds <= 3600)   //1 hour interval
            {
                if($values_count <= 134)        //6 days
                    $mark_format = "{value|hh:nn";
                else if($values_count <= 192)   //8 days
                    $mark_format = "{value|w mmm dd}";
                else
                    $mark_format = "{value|mmm dd}";
            }
            else if($this->interval_in_seconds <= 86400)  //1 day interval
            {
                if($values_count <= 186)         //6 months
                    $mark_format = "{value|mmm dd}";
                else
                    $mark_format = "{value|mmm \'yy}";
            }
            if(($values_count == 1) || ($i % $gap == 0 && $i >= $min_mark_index && $i <= $max_mark_index))
            {
                if(!preg_match("/}$/", $mark_format))
                {
                    $day = date("d", $this->x_labels[$i]);
                    if(!isset($day_change[$day]))
                    {
                        $mark_format .= "<*br*>mmm dd}";
                        $day_change[$day] = true;
                    }
                    else
                        $mark_format .= "}";
                }
                if(!$is_x_axis_label_wrapped && preg_match("/br/", $mark_format))
                    $is_x_axis_label_wrapped = true;
                $mark_formats[] = $mark_format;
            }
            else
                $mark_formats[] = null;
        }
        if($is_x_axis_label_wrapped)
            $this->legend_y += 10;
        return $mark_formats;
    }

    protected function output_chart($values)
    {
        $this->initialize_width();
        //For each data set, find out the max width required to plot y-axis labels and set plot_x and plot_width accordingly
        $max_width_array = array();
        $max_width_y_axis2 = 0;
        $this->y_axis2 = $this->process_number($this->y_axis2, -1);
        $this->scale = $this->process_scale($this->scale, -1);
        $this->scale2 = $this->process_scale($this->scale2, -1);
        if(in_array($this->plot_type, array(SBAR_GRAPH, SAREA_GRAPH))) //For stacked charts, nullify y-axis2, scale & scale2
        {
            $this->y_axis2 = -1;
            $this->scale = -1;
            $this->scale2 = -1;
        }
        $i = 0;
        $min_array = array();
        $max_array = array();
        $percentile_array = array();
        foreach($values as &$value)
        {
            $scaling_symbol = "";
            if(!$this->multiple_axes)
            {
                $scale = ($i == $this->y_axis2) ? $this->scale2 : $this->scale;
                $scaling_symbol = $this->get_scaling_symbol($scale);
                if($scaling_symbol)
                {
                    foreach($value as &$v)
                    {
                        if($v != NoValue)
                            $v = number_format($v / $scale, 2, ".", "");
                    }
                }
                $max_width = $this->calculate_width($value) + strlen($scaling_symbol);
                if($i == $this->y_axis2)
                    $max_width_y_axis2 = $max_width;
                else
                    $max_width_array[] = $max_width;
            }
            $array_math = new ArrayMath($value);
            $min_array[$i] = $array_math->min();
            $min_array[$i] = ($min_array[$i] == NoValue) ? NO_DATA : locale_utils::get_formatted_string_from_double($min_array[$i]) . $scaling_symbol;
            $max_array[$i] = $array_math->max();
            $max_array[$i] = ($max_array[$i] == -NoValue) ? NO_DATA : locale_utils::get_formatted_string_from_double($max_array[$i]) . $scaling_symbol;
            $percentile_array[$i] = ($min_array[$i] == NO_DATA && $max_array[$i] == NO_DATA) ? NO_DATA : locale_utils::get_formatted_string_from_double($array_math->percentile($this->percentile)) . $scaling_symbol;
            $i++;
        }
        $this->plot_x = $this->calculate_max_value($max_width_array) + 7;
        $this->plot_width = $this->width - $this->plot_x - 10 - $max_width_y_axis2;

        $labels_count = count($this->labels);
        if(in_array($this->plot_type, array(BAR_GRAPH, AREA_GRAPH, SBAR_GRAPH, SAREA_GRAPH))) //these charts are shown in 3D
        {
            $offset_3d = 5;
            if(in_array($this->plot_type, array(BAR_GRAPH, AREA_GRAPH))) //Uses more 3D depth proportional to number of data sets
                $offset_3d = $labels_count * 4;
            $this->plot_y += $offset_3d;
            $this->plot_width -= $offset_3d;
        }
        $this->legend_y = $this->plot_y + $this->plot_height + 15;
        $values_count = count($this->x_labels);
        $mark_formats = $this->get_mark_formats($values_count);

        //plot_height is fixed but the chart height is sum of legend area height and plot height
        //Here we set height as "plot height" alone, later we add "legend area height" in layout_legend
        $this->height = $this->legend_y;

        $c = new XYChart($this->width, $this->height);
        $is_client_browser_IE6 = locale_utils::is_client_browser_IE6();
        if(!$is_client_browser_IE6)
            $c->setTransparentColor(-1);
        $this->set_chart_properties($c);

        $legend_box = $c->addLegend($this->plot_x - 10, $this->legend_y, false, $this->text_font, $this->text_font_size);
        $legend_box->setBackground(Transparent, Transparent);
        $legend_box->setFontColor($this->text_font_color);
        $legend_box->setKeySpacing($this->legend_spacing);

        $plot_area = $c->setPlotArea($this->plot_x, $this->plot_y, $this->plot_width, $this->plot_height);
        $this->set_plot_area_properties($c, $plot_area);

        $textBoxObj = $c->addTitle($this->title, $this->text_font, $this->title_font_size, $this->text_font_color);
        $textBoxObj->setBackground(Transparent, -1);

        $this->initialize_y_axis($c->yAxis, $this->text_font_color, $this->scale, $c);

        //Add all labels with format so that tooltip works.
        //Add only specific marks on x-axis.
        $events_y_data = array();
        $events_text = array();
        for($i = 0; $i < $values_count; $i++)
        {
            $converted_x_label = chartTime2($this->x_labels[$i]);
            $c->xAxis->addLabel($i, $c->formatValue($converted_x_label, $this->x_labels_format));
            if($mark_formats[$i] != null)
            {
                $mark = $c->xAxis->addMark($i, $this->v_grid_color, $c->formatValue($converted_x_label, $mark_formats[$i]), $this->text_font, $this->text_font_size);
                $mark->setMarkColor($this->v_grid_color, $this->text_font_color);
                $mark->setLineWidth($this->v_grid_width);
                $mark->setDrawOnTop(false);
            }
            if($this->events != null)
            {
                if(isset($this->events[$this->x_labels[$i]]))
                {
                    //Find the maximum value at this time point (among all data series)
                    //Add required offset and store it as event's y data.
                    $max_y_value = -NoValue;
                    foreach($values as $val)
                    {
                        if($val[$i] != NoValue && $val[$i] > $max_y_value)
                            $max_y_value = $val[$i];
                    }
                    if($max_y_value < 0)
                        $max_y_value = 0;
                    $events_y_data[] = $max_y_value + (($max_y_value == 0 ? 1 : $max_y_value) * $this->events_symbol_offset);
                    $events_arr = $this->events[$this->x_labels[$i]];
                    $event_text = "";
                    foreach($events_arr as $event_obj)
                        $event_text .= $c->formatValue(chartTime2($event_obj[0]), $this->x_labels_format) . " " . $event_obj[1] . "<br>";
                    $events_text[] = preg_replace("/<br>$/", "", $event_text);
                }
                else
                {
                    $events_y_data[] = NoValue;
                    $events_text[] = NoValue;
                }
            }
        }
        $c->xAxis->setColors($this->text_font_color, $this->bg_color, $this->bg_color, $this->bg_color);
        $c->xAxis->setLabelStep($values_count);

        if($this->events != null)
        {
            $events_layer = $c->addLineLayer2();
            $events_layer->setLineWidth(0);
            $events_layer->addExtraField($events_text);
            $events_layer_data_set = $events_layer->addDataSet($events_y_data);
            $events_layer_data_set->setDataSymbol(dirname(__FILE__) . "/../../../../common/images/events.png");
        }

        $stacked_bar_layer = $stacked_area_layer = null;
        $stacked_bar_min_array = array();
        $stacked_bar_max_array = array();
        $stacked_bar_percentile_array = array();
        $stacked_area_min_array = array();
        $stacked_area_max_array = array();
        $stacked_area_percentile_array = array();
        $layers = array();
        $scaling_symbols = array();
        for($i = 0; $i < $labels_count; $i++)
        {
            $data_set_plot_type = $this->plot_type;
            if(isset($this->data_sets_properties[$i], $this->data_sets_properties[$i]["plot_type"]))
                $data_set_plot_type = $this->data_sets_properties[$i]["plot_type"];

            $data_set_color = $this->line_colors[$i];
            if(in_array($data_set_plot_type, array(BAR_GRAPH, AREA_GRAPH)))
                $data_set_color = TRANSPARENT_COLOR | $data_set_color;
            if(isset($this->data_sets_properties[$i], $this->data_sets_properties[$i]["color"]))
                $data_set_color = $this->process_color($c, $this->data_sets_properties[$i]["color"], $data_set_color);

            $data_set_3d = ($this->_3d !== null) ? $this->_3d : in_array($data_set_plot_type, array(BAR_GRAPH, AREA_GRAPH, SBAR_GRAPH, SAREA_GRAPH));
            if(isset($this->data_sets_properties[$i], $this->data_sets_properties[$i]["3d"]))
                $data_set_3d = $this->data_sets_properties[$i]["3d"];

            $data_set_width = in_array($data_set_plot_type, array(BAR_GRAPH, SBAR_GRAPH)) ? 10 : 1;
            if(isset($this->data_sets_properties[$i], $this->data_sets_properties[$i]["width"]))
                $data_set_width = $this->process_number($this->data_sets_properties[$i]["width"], $data_set_width);

            $data_set_effect = null;
            if(isset($this->data_sets_properties[$i], $this->data_sets_properties[$i]["effect"]))
                $data_set_effect = $this->process_effect($this->data_sets_properties[$i]["effect"], $data_set_effect);

            $data_set_shape = null;
            if(isset($this->data_sets_properties[$i], $this->data_sets_properties[$i]["shape"]))
                $data_set_shape = $this->process_shape($this->data_sets_properties[$i]["shape"], $data_set_shape);

            $scale = $this->scale;
            if($i == $this->y_axis2 || ($this->multiple_axes && $i == 1))
            {
                $this->initialize_y_axis($c->yAxis2, $data_set_color, $this->scale2, $c);
                $scale = $this->scale2;
            }

            $layer = null;
            switch($data_set_plot_type)
            {
                case LINE_GRAPH:
                    $layer = $c->addLineLayer2();
                    $layer->setLineWidth($data_set_width);
                    $line_data_set = $layer->addDataSet($values[$i], $data_set_color, $this->labels[$i]);
                    if($values_count <= $this->max_data_symbols)
                    {
                        $line_data_set->setDataSymbol(CircleShape, 4, $data_set_color, $data_set_color);
                        $this->chart_metrics = "dsw=4&";
                    }
                    break;
                case SCATTER_GRAPH:
                    $layer = $c->addScatterLayer(array(), $values[$i], $this->labels[$i], $this->symbols[$i % count($this->symbols)], 5, $data_set_color, $data_set_color);
                    break;
                case SPLINE_GRAPH:
                    $layer = $c->addSplineLayer();
                    $layer->setLineWidth($data_set_width);
                    $spline_data_set = $layer->addDataSet($values[$i], $data_set_color, $this->labels[$i]);
                    if($values_count <= $this->max_data_symbols)
                    {
                        $spline_data_set->setDataSymbol(CircleShape, 4, $data_set_color, $data_set_color);
                        $this->chart_metrics = "dsw=4&";
                    }
                    $c->setClipping();
                    break;
                case STEP_LINE_GRAPH:
                    $layer = $c->addStepLineLayer();
                    $layer->setLineWidth($data_set_width);
                    $step_line_data_set = $layer->addDataSet($values[$i], $data_set_color, $this->labels[$i]);
                    if($values_count <= $this->max_data_symbols)
                    {
                        $step_line_data_set->setDataSymbol(CircleShape, 4, $data_set_color, $data_set_color);
                        $this->chart_metrics = "dsw=4&";
                    }
                    break;
                case BAR_GRAPH:
                    $layer = $c->addBarLayer($values[$i], $data_set_color, $this->labels[$i]);
                    $layer->setBarWidth($data_set_width);
                    if($data_set_effect !== null)
                        $layer->setBorderColor(Transparent, $data_set_effect);
                    if($data_set_shape !== null)
                        $layer->setBarShape($data_set_shape);
                    break;
                case AREA_GRAPH:
                    $layer = $c->addAreaLayer($values[$i], $data_set_color, $this->labels[$i]);
                    break;
                case SBAR_GRAPH:
                    if($stacked_bar_layer == null)
                    {
                        $layer = $stacked_bar_layer = $c->addBarLayer2(Stack);
                        $stacked_bar_layer->setBarWidth($data_set_width);
                        if($data_set_shape !== null)
                            $stacked_bar_layer->setBarShape($data_set_shape);
                    }
                    $stacked_bar_layer->addDataSet($values[$i], $data_set_color, $this->labels[$i]);
                    $stacked_bar_min_array[] = $min_array[$i];
                    $stacked_bar_max_array[] = $max_array[$i];
                    $stacked_bar_percentile_array[] = $percentile_array[$i];
                    break;
                case SAREA_GRAPH:
                    if($stacked_area_layer == null)
                        $layer = $stacked_area_layer = $c->addAreaLayer2(Stack);
                    $stacked_area_layer->addDataSet($values[$i], $data_set_color, $this->labels[$i]);
                    $stacked_area_min_array[] = $min_array[$i];
                    $stacked_area_max_array[] = $max_array[$i];
                    $stacked_area_percentile_array[] = $percentile_array[$i];
                    break;
            }
            if($layer != null)
            {
                if($this->multiple_axes)
                {
                    if($i == 1)
                        $layer->setUseYAxis2();
                    else if($i != 0)
                    {
                        $new_y_axis = $c->addAxis(Left, 0);
                        $this->initialize_y_axis($new_y_axis, null, null, null);
                        $layer->setUseYAxis($new_y_axis);
                    }
                }
                else if($i == $this->y_axis2)
                    $layer->setUseYAxis2();
                if($data_set_3d)
                    $layer->set3D();
                $layers[] = $layer;
                $scaling_symbols[] = $this->get_scaling_symbol($scale);
                if($layer != $stacked_bar_layer && $layer != $stacked_area_layer)
                    $this->add_extra_fields_to_layer($layer, $min_array, $max_array, $percentile_array, $i);
            }
        }
        if($stacked_bar_layer != null)
            $this->add_extra_fields_to_layer($stacked_bar_layer, $stacked_bar_min_array, $stacked_bar_max_array, $stacked_bar_percentile_array);
        if($stacked_area_layer != null)
            $this->add_extra_fields_to_layer($stacked_area_layer, $stacked_area_min_array, $stacked_area_max_array, $stacked_area_percentile_array);
        $legend_box = $this->layout_legend($c);
        print($c->makeChart2(PNG));
        $legend_map = $legend_box->getHTMLImageMap("", " ", "title=\"Minimum  Value: '{dsField0}' \nMaximum Value: '{dsField1}' \n" . $this->percentile . "th Percentile: '{dsField2}'\"");
        if($this->events != null)
            $legend_map .= $events_layer->getHTMLImageMap("#noAnchor", " ", "onmouseover=\"show_events_popup(event, '{field0}')\"");
        $this->chart_metrics .= $this->get_chart_metrics($c, $values_count);
        if(($values_count * count($values)) <= $this->max_data_points_for_tooltip)
        {
            foreach($layers as $i => $layer)
                $legend_map .= $layer->getHTMLImageMap("", " ", "title=\"{dataSetName}: '" . $this->label_format . $scaling_symbols[$i] . "' \n@ {xLabel}\"");
        }
        else
        {
            $metrics = explode("&", $this->chart_metrics);
            $new_metrics = array();
            foreach($metrics as $metric)
            {
                list($prop, $value) = explode("=", $metric);
                $new_metrics[$prop] = $value;
            }
            $x1 = isset($new_metrics["vl"]) ? $new_metrics["vl"] : 0;
            $y1 = isset($new_metrics["vt"]) ? $new_metrics["vt"] : 0;
            $x2 = isset($new_metrics["vw"]) ? $new_metrics["vw"] + $x1 : $c->getDrawArea()->getWidth();
            $y2 = isset($new_metrics["vh"]) ? $new_metrics["vh"] + $y1 : $c->getDrawArea()->getHeight();

            $legend_map .= "<area shape=\"rect\" coords=\"$x1,$y1,$x2,$y2\" title=\"{$this->max_data_points_for_tooltip_msg}\">";
        }
        return $legend_map;
    }

    private function get_chart_metrics($c, $values_count)
    {
        //getChartMetrics returns something like "w=749&h=211&vl=33&vt=20&vw=706&vh=140"
        $chart_metrics = $c->getChartMetrics() . "&iis=" . $this->interval_in_seconds . "&vc=" . $values_count;
        if($values_count > 0)
        {
            $time_zone_offset = isset($_SESSION['timezone_offset']) ? $_SESSION['timezone_offset'] : 0;
            $chart_metrics .= "&st=" . ($this->x_labels[0] - $time_zone_offset);
            $chart_metrics .= "&et=" . ($this->x_labels[$values_count - 1] - $time_zone_offset);
        }
        return $chart_metrics;
    }

    private function layout_legend($c)
    {
        $legend_box = $c->layoutLegend();
        $legend_height = $legend_box->getHeight();
        $draw_area = $c->getDrawArea();
        $draw_area->resize($this->width, $this->legend_y + $legend_height);
        $draw_area->rect(0, 0, $this->width - 1, $this->legend_y + $legend_height - 1, $this->border_color, Transparent, $this->raised_effect);
        return $legend_box;
    }

    protected function initialize_width()
    {
        if(ns_empty($this->width))
            $this->width = $this->min_width;
        else
        {
            $this->width = intval($this->width);
            if($this->width < $this->min_width)
                $this->width = $this->min_width;
            else if($this->width > $this->max_width)
                $this->width = $this->max_width;
        }
    }

    protected function set_chart_properties(&$c)
    {
        $this->text_font_color = $this->process_color($c, $this->text_font_color, BLUE_COLOR);
        $this->bg_color = $this->process_color($c, $this->bg_color, WHITE_COLOR);
        $c->setBackground($this->bg_color, Transparent, $this->raised_effect);
    }

    private function set_plot_area_properties($c, &$plot_area)
    {
        $this->plot_bg_color = $this->process_color($c, $this->plot_bg_color, $this->bg_color);
        $this->plot_edge_color = $this->process_color($c, $this->plot_edge_color, $this->text_font_color);

        $this->h_grid_color = $this->process_color($c, $this->h_grid_color, GREY_COLOR);
        $this->v_grid_color = $this->process_color($c, $this->v_grid_color, GREY_COLOR);

        $this->h_grid_width = $this->process_number($this->h_grid_width, 1);
        $this->v_grid_width = $this->process_number($this->v_grid_width, 1);

        $plot_area->setBackground($this->plot_bg_color, -1, $this->plot_edge_color);
        $plot_area->setGridColor($this->h_grid_color, $this->v_grid_color);
        $plot_area->setGridwidth($this->h_grid_width, $this->v_grid_width);
    }

    private function process_color($c, $color, $default_color)
    {
        if(!is_string($color))
            return $color;
        $values = explode(",", $color);
        $count = count($values);
        if($count == 1 && preg_match("/^0x/", $values[0]))
            return hexdec($values[0]);
        else if($count == 2 && preg_match("/^0x/", $values[0]))
        {
            if(preg_match("/^-?\d+$/", $values[1]))
                return metalColor(hexdec($values[0]), $values[1]);
            else if(preg_match("/^0x/", $values[1]))
                return $c->linearGradientColor(0, 0, $this->width, $this->height, hexdec($values[0]), hexdec($values[1]));
            else if(in_array($values[1] . "Line", array("DashLine", "DotLine", "DotDashLine", "AltDashLine")))
                return $c->dashLineColor(hexdec($values[0]), $this->eval_str($values[1] . "Line"));
        }
        return $default_color;
    }

    private function process_number($number, $default_number)
    {
        if(!is_string($number))
            return $number;
        if(preg_match("/^\d+$/", $number))
            return intval($number);
        return $default_number;
    }

    private function process_scale($scale)
    {
        if(!is_string($scale))
            return NoValue;
        $values = explode(",", $scale);
        $values_count = count($values);
        if($values_count == 1)
        {
            if($values[0] == "log")
                return -NoValue;
            if(isset($this->scales[$values[0]]))
                return $this->scales[$values[0]];
        }
        else if($values_count == 2)
        {
            $linear_scale_min = $this->process_number($values[0], null);
            $linear_scale_max = $this->process_number($values[1], null);
            if($linear_scale_min !== null || $linear_scale_max !== null)
                return array($linear_scale_min, $linear_scale_max);
        }
        return NoValue;
    }

    private function process_effect($effect, $default_effect)
    {
        if(!is_string($effect))
            return $effect;
        $values = explode(",", $effect);
        $count = count($values);
        if($count == 1 && $this->validate_direction($values[0]))
            return softLighting($values[0]);
        else if($count == 2 && $this->validate_glare($values[0]) && $this->validate_direction($values[1]))
            return glassEffect($values[0], $values[1]);
        return $default_effect;
    }

    private function validate_direction(&$direction)
    {
        if(in_array($direction, array("Top", "Bottom", "Right", "Left")))
        {
            $direction = $this->eval_str($direction);
            return true;
        }
        return false;
    }

    private function validate_glare(&$glare)
    {
        if(in_array($glare, array("NormalGlare", "ReducedGlare", "NoGlare")))
        {
            $glare = $this->eval_str($glare);
            return true;
        }
        return false;
    }

    private function eval_str($str)
    {
        return eval("return $str;");
    }

    private function process_shape($shape, $default_shape)
    {
        if(!is_string($shape))
            return $shape;
        $values = explode(",", $shape);
        $count = count($values);
        if($count >= 1 && $this->validate_shape($values[0], $count >= 2 ? $values[1] : ""))
            return $values[0];
        return $default_shape;
    }

    private function validate_shape(&$shape, $arg)
    {
        $shape .= "Shape";
        if(in_array($shape, array("SquareShape", "DiamondShape", "TriangleShape", "RightTriangleShape", "LeftTriangleShape", "InvertedTriangleShape", "CircleShape")))
        {
            $shape = $this->eval_str($shape);
            return true;
        }
        else if(in_array($shape, array("StarShape", "PolygonShape")))
        {
            $arg = $this->process_number($arg, 4);
            $shape = $this->eval_str($shape . "(" . $arg . ")");
            return true;
        }
        else if(in_array($shape, array("CrossShape", "Cross2Shape")))
        {
            $arg = $this->process_number($arg, 5);
            $arg /= 10;
            $shape = $this->eval_str($shape . "(" . $arg . ")");
            return true;
        }
        return false;
    }

    private function add_extra_fields_to_layer($layer, $min_array, $max_array, $percentile_array, $i = null)
    {
        if($i === null)
        {
            $layer->addExtraField($min_array);
            $layer->addExtraField($max_array);
            $layer->addExtraField($percentile_array);
        }
        else
        {
            $layer->addExtraField(array($min_array[$i]));
            $layer->addExtraField(array($max_array[$i]));
            $layer->addExtraField(array($percentile_array[$i]));
        }
    }

    private function initialize_y_axis($y_axis, $font_color, $scale, $c)
    {
        if($this->multiple_axes)
        {
            $y_axis->setColors(Transparent, Transparent);
            return;
        }
        $y_axis->setTitle("");
        $y_axis->setColors($font_color, $font_color);
        $y_axis->setLabelStyle($this->text_font, $this->text_font_size, $font_color);
        $y_axis->setLabelFormat($this->label_format . $this->get_scaling_symbol($scale));
        //No decimals on y-axis
        $y_axis->setMinTickInc(1);
        $y_axis->setTickDensity($this->plot_height / 5);
        if($scale == -NoValue)
        {
            $y_axis->setLogScale();
            $c->setClipping();
            return;
        }
        else if(is_array($scale) && count($scale) > 1)
        {
            $y_axis->setLinearScale($scale[0], $scale[1]);
            $c->setClipping();
        }
    }

    private function get_scaling_symbol($scale)
    {
        if(!is_int($scale))
            return "";
        if(!isset($this->scales_reverse_map))
            $this->scales_reverse_map = array_flip($this->scales);
        return isset($this->scales_reverse_map[$scale]) ? $this->scales_reverse_map[$scale] : "";
    }
}

class chart_factory
{
    public static function get_chart_object($chart_type)
    {
        if(class_exists($chart_type))
            return eval("return new ".$chart_type."();");
        return null;
    }
}
?>