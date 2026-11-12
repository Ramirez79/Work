<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH. "views/common/chart_classes.php");

if(isset($error_message))
{
    $chart_obj = new xy_chart();
    if(isset($width))
        $chart_obj->width = $width;
    $chart_obj->print_error_chart($error_message);
    exit(0);
}

$chart_obj = new xy_chart();

//Set title
if(isset($title))
    $chart_obj->title = $title;
else if(!$internal)
    $chart_obj->title = view_utils::get_report_element_title($func[0]);

//Set labels, plot type
$labels = array();
if(!$internal)
{
    $_ns_stat_definitions = new _ns_stat_definitions();
    $nsstat_cmd = array();
    foreach($func as $f)
    {
        $nsstat_cmd[$f] = $_ns_stat_definitions->get_command($f);
        $nsstat_cntrs[$f] = $nsstat_cmd[$f]['cntrs'];
    }
}
if(ns_empty($ents)) //Not an entity based group
{
    if(!isset($cores))
    {
        foreach($cntrs as $f=>$cntr_names)
        {
            foreach($cntr_names as $cntr_name)
                $labels[] = (($internal || !isset($nsstat_cntrs[$f], $nsstat_cntrs[$f][$cntr_name])) ? $cntr_name : view_utils::get_counter_descr($nsstat_cntrs[$f][$cntr_name], $nsstat_cntrs[$f]));
        }
    }
    else
    {
        $k = 0;
        $cores = explode(",", $cores);
        for($j = 0; $j < count($cores); $j++)
        {
            $i = 0;
            foreach($cntrs as $f=>$cntr_names)
            {
                foreach($cntr_names as $cntr_name)
                    $labels[$k + $i++] = CPU_CORE . " " . $cores[$j] . " - " . (($internal || !isset($nsstat_cntrs[$f], $nsstat_cntrs[$f][$cntr_name])) ? $cntr_name : view_utils::get_counter_descr($nsstat_cntrs[$f][$cntr_name], $nsstat_cntrs[$f]));
            }
            $k += count($orig_cntrs);
        }
    }
}
else
{
    $entities = explode(",", $ents);
    if(!isset($cores))
    {
        $k = 0;
        for($j = 0; $j < count($entities); $j++)
        {
            for($i = 0; $i < count($orig_cntrs); $i++)
                $labels[$k + $i] = $entities[$j] . " - " . (($internal || !isset($nsstat_cntrs[$func[0]], $nsstat_cntrs[$func[0]][$orig_cntrs[$i]])) ? $orig_cntrs[$i] : view_utils::get_counter_descr($nsstat_cntrs[$func[0]][$orig_cntrs[$i]], $nsstat_cntrs[$func[0]]));
            $k += count($orig_cntrs);
        }
    }
    else
    {
        $k = 0;
        $cores = explode(",", $cores);
        for($h = 0; $h < count($cores); $h++)
        {
            for($j = 0; $j < count($entities); $j++)
            {
                for($i = 0; $i < count($orig_cntrs); $i++)
                    $labels[$k + $i] = CPU_CORE . " " . $cores[$h] . " - " .$entities[$j] . " - " . (($internal || !isset($nsstat_cntrs[$func[0]], $nsstat_cntrs[$func[0]][$orig_cntrs[$i]])) ? $orig_cntrs[$i] : view_utils::get_counter_descr($nsstat_cntrs[$func[0]][$orig_cntrs[$i]], $nsstat_cntrs[$func[0]]));
                $k += count($orig_cntrs);
            }
        }
    }
}
$chart_obj->labels = $labels;
$chart_obj->plot_type = $type;
if(isset($width))
    $chart_obj->width = $width;
$data_sets = parse_result($result, $orig_cntrs, $chart_obj, $ents, isset($cores) ? $cores : null, $internal, $file_request);
if($file_request)
    return generate_report($this, $chart_obj->title, $filetype, $data_sets, $chart_obj->labels, $chart_obj->x_labels);
populate_chart_properties($chart_obj, isset($props) ? $props : null);
if(isset($events))
    $chart_obj->events = process_events($events, $chart_obj->x_labels, $chart_obj->interval_in_seconds);
$image_map = $chart_obj->print_chart($data_sets);
if(!empty($image_map))
    $_SESSION[$id . '_map'] = $image_map . "###" . $chart_obj->chart_metrics . "###" . generate_tabular_report($data_sets, $chart_obj->title, $chart_obj->labels, $chart_obj->x_labels, $chart_obj->interval_in_seconds);
else if(isset($_SESSION[$id . "_map"]))
    unset($_SESSION[$id . "_map"]);

function parse_result($result, $orig_cntrs, &$chart_obj, $ents, $cores, $internal, $file_request)
{
    $values = array();
    $cntrs_count = count($orig_cntrs);
    if(isset($cores))
        $cntrs_count = $cntrs_count * count($cores);
    $times = array();
    $times_index = 0;
    if(ns_empty($ents)) //Not an entity based group
    {
        for($i = 0; $i < $cntrs_count; $i++)
            $values[$i] = array();
        if(!isset($cores))
        {
            $returned_counters = substr($result, 0, strpos($result, ","));
            $returned_counters = explode(" ", $returned_counters);
            $returned_counters_map = get_returned_counters_map($returned_counters, $chart_obj, $internal);
            $result = substr($result, strpos($result, ",") + 1);
            $result_array = explode(",", $result);
            foreach($result_array as $row)
            {
                if($row == "")
                    continue;
                $row_array = explode(" ", $row);
                $times[$times_index++] = view_utils::convert_local_to_client_time($row_array[0], null, !$file_request);
                for($i = 0; $i < $cntrs_count; $i++)
                {
                    if(!isset($returned_counters_map[$orig_cntrs[$i]], $row_array[$returned_counters_map[$orig_cntrs[$i]]+1]) || $row_array[$returned_counters_map[$orig_cntrs[$i]]+1] == "NaN")
                        $values[$i][] = NoValue;
                    else
                        $values[$i][] = $row_array[$returned_counters_map[$orig_cntrs[$i]]+1];
                }
            }
        }
        else
        {
            $k = 0;
            $m = 0;
            foreach($cores as $core)
            {
                $times_index = 0;
                $returned_counters = substr($result[$k], 0, strpos($result[$k], ","));
                $returned_counters = explode(" ", $returned_counters);
                $returned_counters_map = get_returned_counters_map($returned_counters, $chart_obj, $internal);
                $result[$k] = substr($result[$k], strpos($result[$k], ",") + 1);
                $result_array = explode(",", $result[$k]);
                foreach($result_array as $row)
                {
                    if($row == "")
                        continue;
                    $row = preg_replace("/ $/", "", $row);
                    $row_array = explode(" ", $row);
                    $times[$times_index++] = view_utils::convert_local_to_client_time($row_array[0], null, !$file_request);
                    for($i = 0; $i < count($row_array)-1; $i++)
                    {
                        $tmp = $i + $m;
                        if(!isset($returned_counters_map[$orig_cntrs[$i]], $row_array[$returned_counters_map[$orig_cntrs[$i]]+1]) || $row_array[$returned_counters_map[$orig_cntrs[$i]]+1] == "NaN")
                            $values[$tmp][] = NoValue;
                        else
                            $values[$tmp][] = $row_array[$returned_counters_map[$orig_cntrs[$i]]+1];
                    }
                }
                $m += count($orig_cntrs);
                $k++;
            }
        }
    }
    else
    {
        $entities = explode(",", $ents);
        $cntrs_count = count($entities) * $cntrs_count;
        for($i = 0; $i < $cntrs_count; $i++)
            $values[$i] = array();
        $times = array();
        if(!isset($cores))
        {
            $k = 0;
            $m = 0;
            foreach($entities as $entity)
            {
                $times_index = 0;
                $returned_counters = substr($result[$k], 0, strpos($result[$k], ","));
                $returned_counters = explode(" ", $returned_counters);
                $returned_counters_map = get_returned_counters_map($returned_counters, $chart_obj, $internal);
                $result[$k] = substr($result[$k], strpos($result[$k], ",") + 1);
                $result_array = explode(",", $result[$k]);
                foreach($result_array as $row)
                {
                    if($row == "")
                        continue;
                    $row = preg_replace("/ $/", "", $row);
                    $row_array = explode(" ", $row);
                    $times[$times_index++] = view_utils::convert_local_to_client_time($row_array[0], null, !$file_request);
                    for($i = 0; $i < count($row_array)-1; $i++)
                    {
                        $tmp = $i + $m;
                        if(!isset($returned_counters_map[$orig_cntrs[$i]], $row_array[$returned_counters_map[$orig_cntrs[$i]]+1]) || $row_array[$returned_counters_map[$orig_cntrs[$i]]+1] == "NaN")
                            $values[$tmp][] = NoValue;
                        else
                            $values[$tmp][] = $row_array[$returned_counters_map[$orig_cntrs[$i]]+1];
                    }
                }
                $m += count($orig_cntrs);
                $k++;
            }
        }
        else
        {
            $k = 0;
            $m = 0;
            foreach($cores as $core)
            {
                foreach($entities as $entity)
                {
                    $times_index = 0;
                    $returned_counters = substr($result[$k], 0, strpos($result[$k], ","));
                    $returned_counters = explode(" ", $returned_counters);
                    $returned_counters_map = get_returned_counters_map($returned_counters, $chart_obj, $internal);
                    $result[$k] = substr($result[$k], strpos($result[$k], ",") + 1);
                    $result[$k] = substr($result[$k], strpos($result[$k], ",") + 1);
                    $result_array = explode(",", $result[$k]);
                    foreach($result_array as $row)
                    {
                        if($row == "")
                            continue;
                        $row = preg_replace("/ $/", "", $row);
                        $row_array = explode(" ", $row);
                        $times[$times_index++] = view_utils::convert_local_to_client_time($row_array[0], null, !$file_request);
                        for($i = 0; $i < count($row_array)-1; $i++)
                        {
                            $tmp = $i + $m;
                            if(!isset($returned_counters_map[$orig_cntrs[$i]], $row_array[$returned_counters_map[$orig_cntrs[$i]]+1]) || $row_array[$returned_counters_map[$orig_cntrs[$i]]+1] == "NaN")
                                $values[$tmp][] = NoValue;
                            else
                                $values[$tmp][] = $row_array[$returned_counters_map[$orig_cntrs[$i]]+1];
                        }
                    }
                    $m += count($orig_cntrs);
                    $k++;
                }
            }
        }
    }
    $chart_obj->x_labels = $times;
    $chart_obj->interval_in_seconds = count($times) > 1 ? ($times[1] - $times[0]) : 300;
    return $values;
}

function get_returned_counters_map($returned_counters, $chart_obj, $internal)
{
    $returned_counters_map = array();
    foreach($returned_counters as $index=>$ret_cntr)
    {
        if(!$internal)
            $returned_counters_map[preg_replace("/Rate$/", "rate", $ret_cntr)] = $index;
        else
        {
            if($ret_cntr == "")
                continue;
            if(strpos($ret_cntr, ":") === false)
            {
                $cntr_codename = $ret_cntr;
                $cntr_varname = $ret_cntr;
            }
            else
                list($cntr_codename, $cntr_varname) = explode(":", $ret_cntr);
            foreach($chart_obj->labels as &$chart_obj_label)
            {
                $chart_obj_label = preg_replace("/^" . $cntr_codename . "$/", $cntr_varname, $chart_obj_label);
                $chart_obj_label = preg_replace("/ \- " . $cntr_codename . "$/", " - " . $cntr_varname, $chart_obj_label);
            }
            $returned_counters_map[$cntr_codename] = $index;
            $returned_counters_map[$cntr_varname] = $index;
        }
    }
    return $returned_counters_map;
}

function generate_report($controller, $file_name, $file_type, $data_sets, $labels, $time_points)
{
    $file_name = preg_replace("/ /", "_", $file_name) . "_" . date('m_d_Y_H_i') . "." . $file_type;
    $controller->output->set_header("Cache-Control: ");
    $controller->output->set_header("Content-type: text/$file_type");
    $controller->output->set_header("Content-disposition: attachment; filename=\"$file_name\"");
    print "Time," . implode(",", $labels). "\n";
    $time_points_count = count($time_points);
    for($i = 0; $i < $time_points_count; $i++)
    {
        print $time_points[$i];
        foreach($data_sets as $data_set)
        {
            print ",";
            if($data_set[$i] == NoValue)
                print "NaN";
            else
                print $data_set[$i];
        }
        print "\n";
    }
    return true;
}

function process_events($events, $times, $interval_in_seconds)
{
    $tokenizer = "\n";
    $events = explode($tokenizer, $events);
    $new_events = array();
    foreach($events as $event)
    {
        if(strpos($event, " ") === false)
            continue;
        list($time, $event_text) = explode(" ", $event, 2);
        if($time % $interval_in_seconds)
            $new_time = $time + ($interval_in_seconds - $time % $interval_in_seconds);
        else
            $new_time = $time;
        $new_time = view_utils::convert_local_to_client_time($new_time, null, true);
        $time = view_utils::convert_local_to_client_time($time, null, true);
        $event_text = preg_replace("/'/", "", $event_text);
        if(!isset($new_events[$new_time]))
            $new_events[$new_time] = array(array($time, $event_text));
        else
            $new_events[$new_time][] = array($time, $event_text);
    }
    return $new_events;
}

function generate_tabular_report($values, $title, $labels, $times, $interval_in_seconds)
{
    $counter_result = array();
    foreach($values as $i=>$value)
        $counter_result[$labels[$i]] = calculate($value, $times, $interval_in_seconds);
    return json_encode(array($title, $counter_result));
}

function calculate($values, $times, $interval_in_seconds)
{
    $date_format = ($interval_in_seconds % 60) ? "H:i:s M d Y" : "H:i M d Y";
    $count = count($values);

    $first = NO_DATA;
    $first_time = "";
    if(isset($values[0]) && $values[0] != NoValue)
    {
        $first = $values[0];
        $first_time = view_utils::convert_local_to_client_time($times[0], $date_format);
    }

    $last = NO_DATA;
    $last_time = "";
    if($count > 0 && isset($values[$count - 1]) && $values[$count - 1] != NoValue)
    {
        $last = $values[$count - 1];
        $last_time = view_utils::convert_local_to_client_time($times[$count - 1], $date_format);
    }

    $delta = NO_DATA;
    $delta_time = "";
    if($first !== NO_DATA && $last !== NO_DATA)
        $delta = locale_utils::get_formatted_string_from_double($last - $first);

    if($first !== NO_DATA)
        $first = locale_utils::get_formatted_string_from_double($first);
    if($last !== NO_DATA)
        $last = locale_utils::get_formatted_string_from_double($last);

    return array("v" => array($first, $last, $delta), "t" => array($first_time, $last_time, $delta_time));
}

function populate_chart_properties(&$chart_obj, $properties)
{
    //Global settings
    $conf = reporting::get_global_conf();
    if(!ns_empty($conf))
    {
        $key_map = reporting::get_properties_key_map();
        foreach($conf as $prop => $value)
        {
            if(isset($key_map[$prop]))
            {
                if(preg_match("/^\d/", $prop))
                    $prop = "_" . $prop;
                $chart_obj->$prop = $value;
            }
            else if(preg_match("/(.+)_(\d+)$/", $prop, $matches) && isset($matches[1], $matches[2], $key_map[$matches[1]]))
            {
                $data_set_index = intval($matches[2]);
                if(!isset($chart_obj->data_set_properties[$data_set_index]))
                    $chart_obj->data_set_properties[$data_set_index] = array();
                $chart_obj->data_sets_properties[$data_set_index][$matches[1]] = $value;
            }
        }
    }

    //Chart & data set level settings
    if($properties != null)
    {
        $key_reverse_map = reporting::get_properties_key_reverse_map();
        $properties = explode("&", $properties);
        foreach($properties as $property)
        {
            if(strpos($property, "=") === false)
                return;
            list($prop, $value) = explode("=", $property);
            if(isset($key_reverse_map[$prop]))
            {
                $prop = $key_reverse_map[$prop];
                if(preg_match("/^\d/", $prop))
                    $prop = "_" . $prop;
                $chart_obj->$prop = $value;
            }
            else if(is_numeric($prop))
            {
                $props = explode(";", $value);
                foreach($props as $p)
                {
                    if(strpos($p, ":") === false)
                        return;
                    list($a, $b) = explode(":", $p);
                    if(!isset($key_reverse_map[$a]))
                        continue;
                    if(!isset($chart_obj->data_set_properties[$prop]))
                        $chart_obj->data_set_properties[$prop] = array();
                    $chart_obj->data_sets_properties[$prop][$key_reverse_map[$a]] = $b;
                }
            }
        }
    }

    //Auto settings
    if(!$chart_obj->multiple_axes)
    {
        if(ns_empty($chart_obj->scale))
        {
            $percentage_chart = true;
            foreach($chart_obj->labels as $i => $label)
            {
                if($i != $chart_obj->y_axis2 && !is_percentage_counter($label))
                {
                    $percentage_chart = false;
                    break;
                }
            }
            if($percentage_chart)
                $chart_obj->scale = "0,100";
        }
        if(ns_empty($chart_obj->scale2))
        {
            if($chart_obj->y_axis2 >= 0 && $chart_obj->y_axis2 < count($chart_obj->labels) && is_percentage_counter($chart_obj->labels[$chart_obj->y_axis2]))
                $chart_obj->scale2 = "0,100";
        }
    }
}

function is_percentage_counter($counter_name)
{
    return preg_match("/ \(%\)$/", $counter_name) || preg_match("/_usage_pcnt$/", $counter_name);
}
?>