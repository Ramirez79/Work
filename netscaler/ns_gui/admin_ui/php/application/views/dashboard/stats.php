<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// Don't allow this page to be embedded inside a frame
header("X-Frame-Options: SAMEORIGIN");

require_once(APPPATH. "views/dashboard/renderers.php");

global $g_function;
global $g_descriptivename;
global $g_argname;
global $g_argval;
global $g_detailview;
global $g_controller;
$g_controller = $this;
global $g_is_graphical_view_supported;
$g_is_graphical_view_supported = $is_graphical_view_supported;
global $g_is_graphical_view;
$g_is_graphical_view = $is_graphical_view;
global $g_popup_window;
$g_popup_window = $popup_window;
global $g_popup_url;
$g_popup_url = $g_popup_window ? "&popup=yes" : "";

global $g_pagesize;
$g_pagesize = isset($pagesize) ? $pagesize : null;
global $g_pageno;
$g_pageno = isset($pageno) ? $pageno : null;
global $g_pageno_url;
$g_pageno_url = $g_pageno != null ? "&pageno=$g_pageno" : "";

global $g_ns_stat_definitions;
$g_ns_stat_definitions = $_ns_stat_definitions;
$g_descriptivename = view_utils::get_stats_group_array();
global $g_random_key;
$g_random_key = $rand_key;
global $g_from_config;
$g_from_config = $fromconfig;

if (!isset($argname))
{
    $g_argname = "";
}
else
{
    $g_argname =  $argname;
}

if(isset($cmd["namearg"], $argval))
{
    $g_argval =  $argval;
}
else
{
    $g_argval = "";
}

if(!ns_empty($g_argval) || !isset($detailview))
{
    $g_detailview = "NO";
}
else
{
    $g_detailview =  $detailview;
}

$g_function = $cmd['cmdname'];
global $g_protection_policy_by_name;
$g_protection_policy_by_name = (in_array($g_function, array("statscpolicy", "statpqpolicy", "statdospolicy")) && !ns_empty($g_argval));
if($is_ajax_request)
{
    header(XML_HEADER);
    genDataLayer($result, $cmd, $graphical_view_definition);
    exit(0);
}
$tempname = getHeading();
if(!isset($title))
{
    $title = $tempname;
}
$this->load->view("common/header");
?>
<style type="text/css">
.ns_body
{
    min-width: 500px !important;
<?php
    global $g_from_config;
    if(!$g_from_config && !$g_popup_window && isset($_SERVER["HTTP_USER_AGENT"]) && stripos($_SERVER["HTTP_USER_AGENT"], "MSIE 9.0") !== false) //To avoid extra scrolls on IE9
        print "\toverflow: hidden !important;\n";
?>
}

.ns_content
{
    padding: 0px;
}
</style>
<?php
if ($result['rc'] != 0 and preg_match("/not enabled/", $result['message']))
{
    show_error_string_top($title, "Feature is not enabled",$cmd);
}
else if ( $result['rc'] == 0 and (!isset($result['List']) or count($result['List']) == 0 ))
{
    $errtext;
    if ( preg_match("/stat(\w*)/", $g_function, $matches) )
    {
        $var = $matches[1];
        $var = preg_replace("/^ns/", "", $var);
        if($var == "dospolicy")
            $errtext = "No DoS policies are bound";
        else if($var == "pqpolicy")
            $errtext = "No Priority Queuing policies are bound";
        else if($var == "scpolicy")
            $errtext = "No Sure Connect policies are bound";
        else if($var == "appfwpolicy")
            $errtext = "No advanced Application Firewall policies are configured";
        else if($var == "cmppolicy")
            $errtext = "No advanced Compression policies are configured";
        else
            $errtext = "No $var(s) configured";
    }
    show_error_string_top($title, $errtext,$cmd);
}
else if ($result['rc'] != 0)
{
    $returncode = $result['rc'];
    $message = $result['message'];
    if($returncode == NSERR_NOTAUTHORIZED)
    {
    	$errtext = $message;
    	show_error_string_top($title,$errtext,$cmd, false);
    }
    else
    {
        $errtext = "";
        if($returncode == NSERR_CLNOTEXIST)
            $errtext = $message;
        else
    	    $errtext = "Error retrieving data.<br>return code = $returncode.<br>Error message = $message.<br>";
    	show_error_string_top($title,$errtext,$cmd);
        print "</div>\n"; //close pane_right_wrapper_div
    }
    exit(0);
}
else
{
    $dhs = $cmd['displayhandler'];
    view_utils::print_state_check_constants();
    
    disp_header($title, $cmd);

    if ($g_argval != "")
    {
        $disp_val = $tempname . " [ " . $g_argval . " ] ";
        $disp_val = StatisticsRenderer::trim_value($disp_val, 100);
        print "<p class=\"ns_entity_header\" title=\"$tempname [$g_argval]\" align=\"left\">$disp_val</p>\n";
    }

    require_once(APPPATH."views/common/locale_utils.php");
    print  "<script type=\"text/javascript\">\n";
    print "var grouping_separator = '" . locale_utils::get_grouping_separator() . "';\n";
    print "var decimal_separator = '" . locale_utils::get_decimal_separator() . "';\n";
    print "</script>\n";
    print "<div id=\"tabular_view_div\"  " . ($g_is_graphical_view ? " style=\"display: none\"" : "") . ">\n";
    $use_tabular_view_definition = use_tabular_view_definition($cmd);
    $tabular_view_definitions = $use_tabular_view_definition ? get_tabular_view_definitions() : null;

    $sort_javascript_printed = false;
    $result_list_index = 0;
    $obj = $result;
    while(true)
    {
        if($g_protection_policy_by_name)
        {
            if(!isset($result['List'][$result_list_index]))
            {
                break;
            }
            $obj = array('List' =>  array($result['List'][$result_list_index++]));
        }
        foreach($dhs as $dh)
        {
            if ( isTextDH($dh) )
            {
                print_text($dh);
                continue;
            }
            else if ( isFunctionTabular($cmd, $dh) )
            {
                if(!$sort_javascript_printed)
                {
                    print "\n<script type=\"text/javascript\" src=\"/admin_ui/common/js/ns/sort.js\"></script>\n";
                    print "<link href=\"/admin_ui/common/css/ns/fixed_header_column_table.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
                    print "<script type=\"text/javascript\" src=\"/admin_ui/common/js/ns/fixed_header_column_table.js\"></script>\n";
                    $sort_javascript_printed = true;
                }
                print "<div align=\"center\" class=\"full_width_table\">\n";
                $renderer = new TabularRenderer();
                if($g_protection_policy_by_name)
                {
                    $renderer->div_suffix = $result_list_index;
                }
                $renderer->printBody($cmd, $dh, $obj, $g_function, "", $use_tabular_view_definition ? $tabular_view_definitions->get_tabular_view_definition($g_function) : null);
                print "</div>\n";
                print "<br>\n";
            }
            else if ( isValidDH($dh) )
            {
                print "<div align=\"center\" class=\"ns_data_outer\">\n";
                $renderer = getRenderer($dh['format']);
                if(isset($renderer))
                {
                    if($g_protection_policy_by_name)
                    {
                        $renderer->div_suffix = $result_list_index;
                    }
                    $renderer->printBody($cmd,$dh,$obj,100,$g_function);
                }
                print "</div>\n";
                print "<br>\n";
            }
        }
        if(!$g_protection_policy_by_name)
            break;
    }

    $subent;
    //bindings will come only on "get by name" call.
    if(isset($cmd['bindings']) && !ns_empty($g_argval))
    {
        foreach ($result['List'] as $obj)
        {
            if (!isset($obj['entitytype']))
                continue;
            $subfunc = $obj['entitytype'];
            if ($subfunc == $g_function)
                continue;
            if (isset($subent[$subfunc]))
                continue;
            $subcmd = $g_ns_stat_definitions->get_command($subfunc);
            if($subcmd)
                display_subent_dh($subfunc, $subcmd, $result);
            $subent[$subfunc] = 1;
        }
    }

    print "</div>\n"; //close tabular_view_div

}
view_utils::print_auto_refresh_javascript($_SESSION[ST_PAGE_NUMBER_KEY]);
print "<script type=\"text/javascript\" src=\"/admin_ui/dashboard/js/dashboard.js\"></script>\n";
print "<script type=\"text/javascript\" src=\"/admin_ui/dashboard/js/enable_disable.js\"></script>\n";

require_once("graphical_view.php");
$graphical_view = new graphical_view($g_ns_stat_definitions, array($g_function => $cmd), PAGE_REQUEST, $graphical_view_definition);
$graphical_view->render(array($g_function => $result), $g_is_graphical_view, false, $counter_groups, $counters);

print "</div>\n"; //close pane_right_wrapper_div

exit(0);

function use_tabular_view_definition($cmd)
{
    global $g_argval;
    return (isset($cmd["namearg"]) && ns_empty($g_argval));
}

function get_tabular_view_definitions()
{
    require_once(APPPATH . "controllers/dashboard/tabular_view_definitions.php");
    return new tabular_view_definitions();
}

function genDataLayer($result, $cmd, $graphical_view_definition)
{
    global $g_ns_stat_definitions;
    global $g_function;
    global $g_argname;
    global $g_argval;
    global $g_controller;
    print "<messages>\n";

    require_once("graphical_view.php");
    $graphical_view = new graphical_view($g_ns_stat_definitions, array($g_function => $cmd), AJAX_REQUEST, $graphical_view_definition);
    $graphical_view->render(array($g_function => $result));

    $dhs = $cmd['displayhandler'];
    $valid_result = view_utils::is_valid_stat_result($result, AJAX_REQUEST);

    $use_tabular_view_definition = use_tabular_view_definition($cmd);
    $tabular_view_definitions = $use_tabular_view_definition ? get_tabular_view_definitions() : null;
    if($valid_result)
    {
        foreach($dhs as $dh)
        {
            if ( isFunctionTabular($cmd,$dh) )
                genTabularData($g_function, $cmd, $g_argname, $result, $dh, false, $use_tabular_view_definition ? $tabular_view_definitions->get_tabular_view_definition($g_function) : null);
            else
            {
                if (isValidDH($dh))
                {
                    if ( $dh['format'] == "2C" )
                    {
                        if ( isset($dh['column1']) )
                            process_xml_dh($cmd['cntrs'],$result,$dh['column1']);
                        if ( isset($dh['column2']) )
                            process_xml_dh($cmd['cntrs'],$result,$dh['column2']);
                    }
                    else if ( $dh['format'] == "3C" )
                        process_xml_dh_3C($cmd['cntrs'],$result,$dh);
                    else
                        process_xml_dh($cmd['cntrs'],$result,$dh);
                }
            }
        }
    }
    $subent;
    //bindings will come only on "get by name" call.
    if(isset($cmd['bindings']) && !ns_empty($g_argval) && $valid_result)
    {
        foreach ($result['List'] as $obj)
        {
            if (!isset($obj['entitytype']))
                continue;
            $subfunc = $obj['entitytype'];
            if ($subfunc == $g_function)
                continue;
            if (isset($subent[$subfunc]))
                continue;
            $subent[$subfunc] = 1;
            display_subent_data($subfunc,$result);
        }
    }
    print "</messages>\n";
}

function genTabularData($function, $cmd, $g_argname, $result, $dh, $bndent, $tabular_view_definition)
{
    global $g_popup_url;
    global $g_pageno_url;
    $cntrs = $cmd['cntrs'];
    $i = 1;
    view_utils::slice_result($result);
    foreach ($result['List'] as $obj)
    {
        if (isset($obj['entitytype']) and (strcmp($obj['entitytype'], $function)))
            continue;
        $argval = $obj[$g_argname];
        if (isset($g_argname))
        {
            $is_svcgrp_member = view_utils::is_service_group_member($obj, $function);
            $link_function = $is_svcgrp_member ? "statservicegroupmember" : $function;
            $link_argname = $is_svcgrp_member ? "servicegroupname" : $g_argname;
            if(($bndent || $dh["dispflag"] == "Summary" || $dh["dispflag"] == "Detail") && !isset($cmd["hidden"]))
            {
                $argval = StatisticsRenderer::getcntrvalue($argval, array('type' => 'VALSTR'));
                $display_val = StatisticsRenderer::trim_value($argval);
                $val_to_print = "<a title='Click to view details of &#39;$argval&#39;' href='/menu/stc?func=$link_function&argname=$link_argname&argval=".urlencode(preg_replace("/\//", "%2F", $argval))."&detailview=YES$g_popup_url$g_pageno_url'>$display_val</a>";
            }
            else
                $val_to_print = StatisticsRenderer::trim_value($argval);
            view_utils::put_xml_entry($function.$g_argname.$i, $val_to_print, array('type' => 'VALSTR'));
        }
        $cntrs_to_iterate = isset($tabular_view_definition) ? $tabular_view_definition["cntrs"] : $dh["counters"];
        foreach($cntrs_to_iterate as $str)
        {
            /**
                * This change is required to check whether the GSLB Site is LOCAL, if so MEP state/status is not applicable for it.
                * Only for REMOTE GSLB Site, MEP Status is valid. BUG0617267
            **/
            if(view_utils::is_local_site($obj, $str))
            {
                $obj["sitemetricmepstatus"] = " ";
                $obj["nwmetricmepstatus"] = " ";
            }
            view_utils::put_xml_entry($function.$str.$i, $obj[$str], $cntrs[$str]);
            if(isset($dh['flags']) and $dh['flags'] == "ANDRATE")
            {
                $ratecntrname = view_utils::get_rate_counter($str);
                if(isset($cntrs[$ratecntrname], $obj[$ratecntrname]))
                    view_utils::put_xml_entry($function.$ratecntrname.$i, $obj[$ratecntrname], $cntrs[$ratecntrname]);
            }
        }
        $i++;
    }
    print "<info colname=\"$function\" val=\"" . ($i - 1) . "\"/>\n";
}

function process_xml_dh($cntrs,$result,$dh)
{
    global $g_function;
    global $g_protection_policy_by_name;
    $div_suffix = $g_protection_policy_by_name ? 0 : "";
    foreach ($result['List'] as $obj)
    {
        if (isset($obj['entitytype']) and ($obj['entitytype'] != $g_function))
            continue;
        if(!isset($dh['counters']))
            continue;
        if($g_protection_policy_by_name)
            $div_suffix++;
        foreach ($dh['counters'] as $str)
        {
            if(!isset($obj[$str]))
                continue;
            if(isset($cntrs[$str]['maxval']) and $obj[$str] >= $cntrs[$str]['maxval'])
                continue;
            view_utils::put_xml_entry($g_function.$str.$div_suffix,$obj[$str],$cntrs[$str]);
            if( ((isset($dh['format']) && $dh['format'] == "ANDRATE") ||
                (isset($dh['flags']) && $dh['flags'] == "ANDRATE")) &&
                view_utils::is_rate_counter($str))
            {
                $ratecounter = view_utils::get_rate_counter($str);
                if(isset($obj[$ratecounter]))
                    view_utils::put_xml_entry($g_function.$ratecounter.$div_suffix,$obj[$ratecounter],$cntrs[$ratecounter]);
            }
        }
    }
}

function process_xml_dh_3C($cntrs,$result,$dh)
{
    global $g_function;
    global $g_protection_policy_by_name;
    $div_suffix = $g_protection_policy_by_name ? 0 : "";
    foreach ($result['List'] as $obj)
    {
        if (isset($obj['entitytype']) and ($obj['entitytype'] != $g_function))
            continue;
        if($g_protection_policy_by_name)
            $div_suffix++;
        $counters_name = array('counters1', 'counters2');
        foreach($counters_name as $text)
        {
            if(!isset($dh[$text]))
                continue;
            foreach ($dh[$text] as $str)
            {
                if(!isset($obj[$str]))
                    continue;
                view_utils::put_xml_entry($g_function.$str.$div_suffix,$obj[$str],$cntrs[$str]);
            }
        }
    }
}

function display_subent_data($func,$result)
{
    global $g_ns_stat_definitions;
    global $g_argname;
    $cmd = $g_ns_stat_definitions->get_command($func);
    if(!$cmd)
        return;
    foreach ($cmd['displayhandler'] as $dh)
    {
        if ($dh['dispflag'] and $dh['dispflag'] == "subent")
            genTabularData($func, $cmd, get_argumentname($func), $result, $dh, true, null);
    }
}

function is_clearstats_defined($cmd)
{
    foreach ($cmd['args'] as $dh)
    {
        if ($dh['argname'] == "clearstats")
            return true;
    }

    return false;
}

function display_subent_dh($func, $cmd, $rs)
{
    foreach ($cmd['displayhandler'] as $dh)
    {
        if ($dh['dispflag'] and $dh['dispflag'] == "subent")
        {
            print "<br>";
            print "<div align=\"center\">\n";
            $renderer = new TabularRenderer();
            $renderer->printBody($cmd, $dh, $rs, $func, "YES", null);
            print "</div>\n";
            print "<br>\n";
        }
    }
}

function isTextDH($dh)
{
    if ( isset($dh['text']) and ($dh['text'] != "") )
        return 1;
    else
        return 0;
}

function print_text($dh)
{
    global $g_detailview;
    global $g_argval;
    $tflag;
    if($g_detailview == "YES" || !ns_empty($g_argval))
        $tflag = "Detail";
    else
        $tflag = "Summary";

    if ($dh['flag'] ==  $tflag)
    {
        if ($dh['text'] != "NONE")
            print "<table class=\"ns_wp_body\"><tr><td>" . $dh['text']. "</td></tr></table>\n ";
        if (isset($dh['relatedlink']))
        {
            print "<table class=\"ns_st_body full_width_table ns_st_options\" cellspacing=\"0\" cellpadding=\"0\">\n";
            StatisticsRenderer::print_relatedlink($dh, 1);
            print "</table>\n ";
        }
    }
}

function get_argumentname($f)
{
    global $g_ns_stat_definitions;
    $cmd = $g_ns_stat_definitions->get_command($f);
    if($cmd)
    {
        if(isset($cmd['namearg']))
            return $cmd['namearg'];
    }
    return "";
}

function show_error_string_top($title,$errtext,$cmd, $header=true)
{
    global $g_is_graphical_view_supported;
    if($header)
    {
        $g_is_graphical_view_supported = false;
    	disp_header($title, $cmd);
    }
    view_utils::print_error_string($errtext);
}

function getRenderer($format)
{
    if ( $format == "ANDRATE" )
        return new RateRenderer();
    else if ( $format == "1C" )
        return new OneColumnRenderer();
    else if ( $format == "2C" )
        return new TwoColumnRenderer();
    else if ( $format == "3C" )
        return new ThreeColumnRenderer();
}

function get_display_flag()
{
    global $g_detailview;
    global $g_argval;

    if(!ns_empty($g_argval))
        return "Byname";
    else if($g_detailview == "YES")
        return "Detail";
    return "Summary";
}

function isValidDH($dh)
{
    $display_flag = get_display_flag();
    return (isset($dh["dispflag"]) && $dh["dispflag"] == $display_flag) ||
            (isset($dh["format"]) && $dh["format"] == "2C" && $dh["column1"]["dispflag"] == $display_flag);
}

function isFunctionTabular($cmd, $dh)
{
    return isset($cmd["namearg"], $dh["format"], $dh["dispflag"]) && $dh["format"] == "TABULAR" && $dh["dispflag"] == get_display_flag();
}

function tweak_cmdname($cmdname)
{
    if ( preg_match('/stat(.*)/i', $cmdname) )
    {
        $cmdname = ucfirst(preg_replace('/stat(.*)/i', "$1 Statistics", $cmdname));
    }
    return $cmdname;
}

function getHeading()
{
    global $g_function;
    global $g_descriptivename;
    if (isset($g_descriptivename[$g_function]) )
        $tempname = $g_descriptivename[$g_function];
    else if($g_function == "statservicegroupmember")
        $tempname = "Service Group Member Statistics";
    else
        $tempname = tweak_cmdname($g_function);
    return $tempname;
}

function print_header()
{
    global $g_function;
    global $g_popup_url;
    print "<script lang=\"JavaScript\">\n";
    print "var prevval = '/menu/stc?func=$g_function$g_popup_url';\n";
    print "<!--\n";
    print "function callURL(text)\n";
    print "{\n";
    print "    if (prevval != text) {\n";
    print "        window.location.href = text\n";
    print "        prevval = text;\n";
    print "    }    \n";
    print "}\n";
    print "-->\n";
    print "</script>\n";
}

function genTopLink($cmd)
{
    global $g_function;
    global $g_argname;
    global $g_argval;
    global $g_detailview;
    global $g_popup_url;
    global $g_pageno_url;
    global $g_is_graphical_view_supported;
    global $g_is_graphical_view;
    global $g_random_key;
    $number_of_links = 0;
    if(!ns_empty($g_argval))
    {
        $my_argval = "argval=".urlencode(preg_replace("/\//", "%2F", $g_argval))."&";
    }
    else
    {
        $my_argval = "";
    }

    //Print "Graphical View/Tabular View" link
    if($g_is_graphical_view_supported)
    {
        $number_of_links ++;
        $tabular_view_title = !$g_is_graphical_view ? "Switch to graphical view" : "Switch to tabular view";
        $tabular_view_text = !$g_is_graphical_view ? "Graphical View" : "Tabular View";
        $tabular_view_class = !$g_is_graphical_view ? "dashboard_images graph_img pointer_cursor" : "dashboard_images table_img pointer_cursor";
        print "<div id = \"switch_views_div\" class = \"floated_ellipsed_divs\"><a href=\"#noAnchor\" class=\"anchor_links\" id=\"switch_view_href\" onclick=\"switch_mon_view()\" title=\"$tabular_view_title\">\n";
        print "<div id =\"switch_view_div\" class = \"$tabular_view_class\"></div>";
        print $tabular_view_text;
        print "</a></div>";
    }

    foreach ($cmd['displayhandler'] as $dh)
    {
        if((isset($dh["dispflag"]) && ($dh["dispflag"] == "Detail" || $dh["dispflag"] == "Byname")) || $g_function == "statdnsrecords")
        {
            if(ns_empty($g_argval) && $g_detailview == "NO")
            {
                if(!isset($cmd['namearg']) || in_array($cmd['cmdname'], array("statnsacl", "statnsacl6", "statnspbr", "statnspbr6", "statclusterinstance"))) //No need to show details link for entity based groups
                {
                    $number_of_links ++;
                    print "<div id = \"details_div\" class = \"floated_ellipsed_divs\">";
                    print "<a align=\"right\" class=\"anchor_links\" href=\"/menu/stc?func=$g_function&argname=$g_argname&".$my_argval."detailview=YES$g_popup_url\" title=\"View details\">\n";
                    print "<div class = \"dashboard_images details_img pointer_cursor\"> </div>Details";
                    print "</a></div>";
                }
            }
            else if($g_function != "statservicegroupmember")
            {
                $number_of_links ++;
                print "<div id = \"summary_div\" class = \"floated_ellipsed_divs\">";
                print "<a align=\"right\" class=\"anchor_links\" href=\"/menu/stc?func=$g_function&argname=$g_argname&detailview=NO$g_popup_url$g_pageno_url\" title=\"View summary\">\n";
                print "<div class = \"dashboard_images summary_img pointer_cursor\"> </div>Summary";
                print "</a></div>";
            }
            break;
        }
    }
  
    $width_div_links = (100/($number_of_links)) ."%";
    $style_width = "width:" .$width_div_links;
    print "<script type=\"text/javascript\"> update_css_style(\"summary_div\",\"width\",  '$width_div_links'); </script>";
    print "<script type=\"text/javascript\"> update_css_style(\"details_div\", \"width\", '$width_div_links'); </script>";
    print "<script type=\"text/javascript\"> update_css_style(\"switch_views_div\", \"width\" , '$width_div_links'); </script>";
   
}

function genSelectionCombo()
{
    global $g_function;
    global $g_argname;
    global $g_argval;
    global $g_descriptivename;
    global $g_detailview;
    global $g_popup_window;
    global $g_popup_url;
    global $g_pageno_url;
    global $g_ns_stat_definitions;

    $argname_url = !ns_empty($g_argname) ? "&argname=$g_argname" : "";
    $argval_url = !ns_empty($g_argval) ? "&argval=".urlencode(preg_replace("/\//", "%2F", $g_argval)) : "";

    $cmds = $g_ns_stat_definitions->cmds;
    $feature = $g_ns_stat_definitions->feature;
    $function = ($g_function == "statservicegroupmember") ? "statservicegroup" : $g_function;
    print "<select name=\"groupselect\" id=\"groupselectid\" class=\"header_select\" onchange=\"JavaScript:callURL(this.value);\">\n";

    $license_map = isset($_SESSION["licenseMap"]) ? $_SESSION["licenseMap"] : array();
    foreach ($g_descriptivename as $name=>$val)
    {
        #If a feature doesn't require licensing, skip the license check
        if (isset($cmds[$name]['license']))
        {
            $licensed = 1;
            #Get an array of licenses required for this feature - NSKF1_GSLB,NSKF1_LOADBALANCE etc.
            foreach ($cmds[$name]['license'] as $lic1)
            {
                #Convert "NSKF1_LOADBALANCE" into "lb" using feature hashmap
                $license1 = strtolower($feature[$lic1]);
                #Check whether it is licensed
                if (!isset($license_map[$license1]))
                {
                    $licensed = 0;
                    break;
                }
            }
            if(!$licensed)
                continue;
        }
        $val = preg_replace("/Statistics/", "", $val);
        if ( $name != $function )
            print "<option value=\"/menu/stc?func=$name$g_popup_url\">$val</option>\n";
        else
            print "<option selected value=\"/menu/stc?func=$name$g_popup_url\">$val</option>\n";
    }
    print "</select>";
    
}

function genCommonLinks($cmd)
{
    global $g_function;
    global $g_argname;
    global $g_argval;
    global $g_detailview;
    global $g_popup_window;
    global $g_popup_url;
    global $g_popup_window;
    global $g_pageno_url;
    global $g_is_graphical_view_supported;
    global $g_is_graphical_view;
    global $g_random_key;
   
    if(!ns_empty($g_argval))
    {
        $my_argval = "argval=".urlencode(preg_replace("/\//", "%2F", $g_argval))."&";
    }
    else
    {
        $my_argval = "";
    }

    $argname_url = !ns_empty($g_argname) ? "&argname=$g_argname" : "";

       //Print "Default Group" link
    print "<script type=\"text/javascript\">var rand_token_key = \"$g_random_key\"</script>";
    print "<script type=\"text/javascript\">\n";
    print "\tvar current_page_function = '$g_function';\n";
    print "</script>\n";
    $is_default_group = (isset($_COOKIE['dmon']) && $_COOKIE['dmon'] == $g_function);
    $default_group_title = $is_default_group ? "Remove as default group" : "Set as default group";
    $default_group_class = $is_default_group ? "dashboard_images remove_default pointer_cursor" : "dashboard_images set_default pointer_cursor";
    print "<div class = \"floated_ellipsed_divs\" style = \"width: 35%\"><a href = \"#noAnchor\" id=\"set_as_default_href\" class=\"anchor_links\" onclick=\"set_as_default('dmon', current_page_function)\" title=\"$default_group_title\" > ";
    print "<div id = \"set_as_default_image\" class = \"$default_group_class\"></div>";
    print "Default Group</a></div>";

      //Print Refresh link
    print "<div class = \"floated_ellipsed_divs\"  style = \"width: 25%\"><a class=\"anchor_links\" href=\"/menu/stc?func=$g_function&argname=$g_argname&".$my_argval."detailview=$g_detailview$g_popup_url$g_pageno_url\" title=\"Click to refresh the page\">";
    print "<div class = \"dashboard_images refresh_img pointer_cursor\"> </div>Refresh";
    print "</a></div>";

      //Print Clear Link
      if(is_clearstats_defined($cmd))
    {
        print "<div class = \"floated_ellipsed_divs\"  id = \"clear_div\"><a class=\"anchor_links\" style= \"padding-right:5px;\" href=\"#noAnchor\" class=\"toolbar_last\" onclick=\"javascript:popup_show('clearstats_div', 'clearstats_span', 0, 13)\" title=\"Click to clear the stats\">";
        print "<div id=\"clearstats_span\" class=\"dashboard_images clear_img pointer_cursor\"></div>Clear</a></div>";
        print "<div id=\"clearstats_div\" class=\"popup\"><table><tr><td><b>Clear:</b></td><td align=\"right\"> <img src=\"/admin_ui/common/images/navigate_cross.png\" alt=\"Close\" title=\"Close\" onclick=\"popup_hide('clearstats_div')\"></td></tr></td><td><select id=\"clear_stats_combo\" title=\"Select the type\" class=\"data_source_combo\">";
        print "<option value=\"Basic\" title=\"Basic\" selected>Basic</option>\n";
        print "<option value=\"Full\" title=\"Full\" >Full</option>\n";
        print "</select>&nbsp;</td></tr>";
        print "<tr><td colspan=\"2\">";
        print "<input id=\"clear_stats_all_cbx\"type=\"checkbox\"><span style=\"position:relative;top:-2px\">Clear Global</span>";
        print "</td></tr>";
        print"<tr><td align=\"right\" colspan=\"2\" class=\"padding_top\">";
        $arg_val_for_clear_stats = isset($g_argval) ? $g_argval: "";

        print "<input type=\"button\" class=\"flat_button\" value=\"OK\" onclick=\"javaScript:clear_stats_ok_clicked('".$cmd["cmdname"]."', '".$arg_val_for_clear_stats."')\" title=\"Click to clear the stats\"/>";
        print "<input type=\"button\" class=\"flat_button\" value=\"Close\" onclick=\"popup_hide('clearstats_div')\" title=\"Click to close\"/>";
        print "</td></tr></table></div>";
    } 
    
       //Print Help link
    if(get_branding()->show_help())
    {
        print "<div class = \"floated_ellipsed_divs\" style = \"width: 10%;\">";
        print "<a href=\"#noAnchor\" class=\"anchor_links\" onclick=\"openwindow('/help/db/dashboard.htm#ns_html_dashboard-landing_page_ref.html', 'Dashboard_Help1_" . $_SESSION["rand"] . "', 'Help', screen.width, screen.height)\" title=\"Click to obtain help related to this screen\">Help</a>";
        print "</div>";
        print "<div class = \"floated_ellipsed_divs\" style = \"width: 2%;\">&nbsp;&nbsp;</div>";

    }
     //Print popup icon
    if(!$g_popup_window)
    {
        print "<div class = \"floated_ellipsed_divs\" style = \"width: 6%\">";
        print "&nbsp;<a href=\"#noAnchor\" onclick=\"openwindow('/menu/stc?func=$g_function$argname_url$my_argval&detailview=$g_detailview&popup=yes$g_pageno_url', 'DASHBOARD_POPUP', 'Dashboard', screen.width, screen.height)\" title=\"Open in a new window.\">";
        print "<div class = \"dashboard_images popout_img pointer_cursor\"></div>";
        print "</a></div>&nbsp;";
    }
  
}



function disp_header($title, $cmd)
{
    global $g_function;
    global $g_controller;

    view_utils::print_counter_tooltip_popup();
    print "<script type=\"text/javascript\" src=\"/admin_ui/common/js/jquery/jquery-1.4.2.min.js\"></script>\n";

    print "<div id=\"right_dash_pane\" class =\"pane_right_wrapper\">";
    print_header();
    print "<div class=\"dashboard_block_title\" align=\"center\" cellspacing=\"0\">\n";
    print "<div style=\"float:left; width:30%; white-space: nowrap;height:40px;line-height: 40px;\">";
    genSelectionCombo($g_function);
    print "</div>";
    print "<div style=\"border-left: 1px solid #E7E7E7;width:26%; float: left;height:40px; padding: 0 5px;\" >\n";
    genTopLink($cmd);
    print "</div>\n";
    print "\t\t<div  style=\"border-left: 1px solid #E7E7E7; float: left;width:44%; height:40px; padding:0 5px;\">";
    genCommonLinks($cmd);
    print "</div>\n";
    print "</div>\n";
}
?>
