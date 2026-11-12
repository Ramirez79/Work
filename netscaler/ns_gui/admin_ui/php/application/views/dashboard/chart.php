<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH. "views/common/chart_classes.php");

//validate result
if ($result['rc'] != 0 || !isset($result['List'][0]))
    exit("Invalid result. Couldn't plot chart");
global $entity_cmd;
global $cmd;
$entity_cmd = isset($chart_definition['entity_cmd']) ? $chart_definition['entity_cmd'] : false;
$cmd = $chart_definition['cmd'];
$result = $entity_cmd ? $result['List'] : $result['List'][0];

//Read Mandatory parameters
if(isset($chart_definition['chart_type']))
    $chart_type = $chart_definition['chart_type'];
else
    exit("Chart type is not specified");

if(isset($chart_definition['cntrs_name']))
    $cntrs_name = $chart_definition['cntrs_name'];
else if(!isset($chart_definition['alt_expr']))
    exit("Chart counters name are not specified");

//Create a chart object based on type (bar, pie, dial etc)
//Set its properties that are specified in chart definitions
$chart_obj = chart_factory::get_chart_object($chart_type);
if(isset($chart_definition['title']))
    $chart_obj->title = $chart_definition['title'];
if(isset($chart_definition['alt_expr']))
{
    if($entity_cmd)
        exit("No support for expression in entity based group");
    $alt_definition = evaluate_expression($result, $chart_definition['alt_expr']);
    $chart_obj->title = $alt_definition['title'];
    $cntrs_name =$alt_definition['cntrs_name'];
}
if(isset($chart_definition['cntrs_label']))
    $chart_obj->labels = $chart_definition['cntrs_label'];
if(isset($chart_definition['chart_properties']))
{
    foreach($chart_definition['chart_properties'] as $key => $value)
        $chart_obj->$key = $value;
}
$chart_obj->print_chart(compute_values($result, $cntrs_name, $chart_definition));

//result is a map of name-value pairs for all counters
//retrieve the values of only those required counters
//and make any computations on the counter values
function compute_values($result, $cntrs_name, $chart_definition)
{
    global $entity_cmd;
    $values = get_default_cntrs_value($result, $cntrs_name, $chart_definition);
    if(!isset($chart_definition['cntrs_value_function']))
        return $values;
    $function = $chart_definition['cntrs_value_function'];
    $total = array_sum($values);
    if(isset($chart_definition['total_cntr']))
        $total = $entity_cmd ? $result[0][$chart_definition['total_cntr']] : $result[$chart_definition['total_cntr']];
    else if(isset($chart_definition['total_cntr_expr']))
        $total = evaluate_expression($result, $chart_definition['total_cntr_expr']);
    return call_user_func_array("chart_definitions::".$function, array($values, $total));
}

function get_default_cntrs_value($result, $cntrs_name, $chart_definition)
{
    global $entity_cmd, $cmd;
    $values = array();
    $count = count($cntrs_name);
    if($entity_cmd)
    {
        foreach($result as $obj)
        {
            if (isset($obj['entitytype']) && ($obj['entitytype'] != $cmd))
                continue;
            $cntrs_values = array();
            for($i = 0; $i < $count; $i++)
                $cntrs_values[] = isset($obj[$cntrs_name[$i]]) ? $obj[$cntrs_name[$i]] : "";
            $values = $chart_definition["chart_type"] == "bar_chart" ? array_merge($cntrs_values, $values) : array_merge($values, $cntrs_values);
        }
        return $values;
    }
    for($i = 0; $i < $count; $i++)
    {
        if($cntrs_name[$i] == 'NOCNTR')
            $values[$i] = evaluate_expression($result, $chart_definition['NOCNTR']);
        else
            $values[$i] = $result[$cntrs_name[$i]];
    }
    return $values;
}

function evaluate_expression($result, $expr)
{
    $expr = preg_replace("/RESULT/", "\$result", $expr);
    return eval($expr);
}
?>