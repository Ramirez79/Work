<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
abstract class StatisticsRenderer
{
    function getRowColor()
    {
        return "bgcolor=\"#F7F6F7\"";
    }

    function get_string($str)
    {
        if ( isset($str) and $str != "" )
            return $str;
        else
            return "&nbsp;";
    }

    static function getcntrvalue($val, $cntr)
    {
        $retval;
        if ( count($val) > 1 )
            $retval = $val[0];
        else
            $retval = $val;
        require_once(APPPATH."views/common/locale_utils.php");
        if (StatisticsRenderer::isNumber($retval, $cntr))
            return locale_utils::get_formatted_string_from_long($retval);
        else if (StatisticsRenderer::isFloat($retval, $cntr))
            return locale_utils::get_formatted_string_from_double($retval);
        else if (isset($cntr, $cntr['cntrname']))
        {
            if($cntr['cntrname'] == "starttime" || $cntr['cntrname'] == "transtime")
                return view_utils::convert_gmt_to_client_time($val);
            else if($cntr['cntrname'] == "curlinkuptime" || $cntr['cntrname'] == "curlinkdowntime")
                return view_utils::convert_up_down_time($val);
        }
        return preg_replace("/\"/", "", $retval);
    }

    static function trim_value($val, $limit = 50)
    {
        if(strlen("" . $val) > $limit)
            return substr($val, 0, $limit) . "...";
        return $val;
    }

    static function isNumber($val, $cntr)
    {
        if(isset($cntr, $cntr['type']) && preg_match("/VALPORT|VALSTR/", $cntr['type']))
            return false;
        return (preg_match("/^-?\d+$/", $val));
    }

    static function isFloat($val, $cntr)
    {
        if(isset($cntr, $cntr['type']) && preg_match("/VALPORT|VALSTR/", $cntr['type']))
            return false;
        return (preg_match("/^-?\d*\.\d*$/", $val)) || is_float($val); //Ex: 1.7E-5 (0.000017) will not be matched with first regex.
    }

    static function print_relatedlink($dh,$colspan)
    {
        global $g_popup_url;
        if (!isset($dh['relatedlink_align']) or ($dh['relatedlink_align'] == ""))
            $dh['relatedlink_align'] = "right";
        print "\t<tr>\n";
        $align = $dh['relatedlink_align'];
        print "\t\t<td colspan=\"$colspan\" style=\"border: 0;\" align=\"$align\">";
        $first = 1;
        foreach ( $dh['relatedlink'] as $link )
        {
            if (!$first)
                print " | ";
            $first = 0;
            $f = $link['cmd'];
            $dview = "YES";
            if ($link['type'] == "summary")
                $dview = "NO";
            $aname = get_argumentname($f);
            $text = htmlspecialchars($link['text']);
            print "<a href=\"/menu/stc?func=$f&detailview=$dview$g_popup_url\">$text</a>";
        }
        print "</td>\n";
        print "\t</tr>\n";
    }

    //If value is to be shown as tooltip on image, we add a hidden div with the value so that
    //sorting works for that columns (sorting uses innerText of a cell).
    function process_value($val, $value_as_tooltip = false)
    {
        if (!strcmp($val, NS_S_UP) || !strcmp($val, NS_S_ENABLED))
        {
            if($value_as_tooltip)
                $val = "<img src='/admin_ui/common/images/nav_up_green.png' alt='$val' title='$val'><div style='display: none'>$val</div>";
            else
                $val = "<img src='/admin_ui/common/images/nav_up_green.png' alt='$val'>$val";
        }
        else if (!strcmp($val, NS_S_DOWN))
        {
            if($value_as_tooltip)
                $val = "<img src='/admin_ui/common/images/nav_down_red.png' alt='$val' title='$val'><div style='display: none'>$val</div>";
            else
                $val = "<img src='/admin_ui/common/images/nav_down_red.png' alt='$val'>$val";
        }
        else if (!strcmp($val, NS_S_OFS) || !strcmp($val, NS_S_DISABLED))
        {
            if($value_as_tooltip)
                $val = "<img src='/admin_ui/common/images/nav_down_yellow.png' alt='$val' title='$val'><div style='display: none'>$val</div>";
            else
                $val = "<img src='/admin_ui/common/images/nav_down_yellow.png' alt='$val'>$val";
        }
        else if (!strcmp($val, ""))
            $val = "&nbsp;";
        return $val;
    }

    //Calculates width for charts associated with the display handler in percentage
    //Rest of the width can be allocated for text in tables.
    function get_width_for_text($dh)
    {
        if(isset($dh['chartwidthpercent']))
            return 100 - $dh['chartwidthpercent'];
        $width_chart = 0;
        $rows_count = count($dh['charts']);
        for($i = 0; $i < $rows_count; $i++)
        {
            $col_count = count($dh['charts'][$i]);
            if($col_count == 1)
                $width_row = 40;
            else
                $width_row = $col_count * 30;
            if($width_row > $width_chart)
                $width_chart = $width_row;
        }
        return (100 - $width_chart);
    }

    //Prints chart urls associated with the display handler
    function output_chart($dh)
    {
        print "<table class=\"inline_block_display\">\n";
        $rows_count = count($dh['charts']);
        $max_col_count = 0;
        for($i = 0; $i < $rows_count; $i++)
        {
            $col_count = count($dh['charts'][$i]);
            if($col_count > $max_col_count)
                $max_col_count = $col_count;
        }
        for($i = 0; $i < $rows_count; $i++)
        {
            $col_count = count($dh['charts'][$i]);
            if($col_count == 0)
                continue;
            $width_cell = 100/$col_count;
            print "\t<tr>\n";
            for($j = 0; $j < $col_count; $j++)
            {
                if(($col_count == $max_col_count) || ($j != ($col_count-1)))
                    print "\t\t<td width=\"$width_cell%\" align=\"center\">\n\t\t\t";
                else
                    print "\t\t<td width=\"$width_cell%\" colspan=\"".($max_col_count - $col_count + 1)."\" align=\"center\">\n\t\t\t";
                view_utils::print_chart_url($dh['charts'][$i][$j]);
                print "\n\t\t</td>\n";
            }
            print "\t</tr>\n";
        }
        print "</table>\n";
        print "</td></tr></table>\n";
    }

    function printBody($cmd, $dh, $result, $width, $function, $printBody)
    {
        if ( !isset($dh['title']) or $dh['title'] == "" )
            $dh['title'] = "&nbsp;";
        if ( $dh['title'] != "&nbsp;" )
        {
            $title = $dh['title'];
            print "<table class=\"ns_wp_body ns_table_data\"><tr><td>$title</td></tr></table>\n";
        }

        $table_class = "";
        if (isset($dh['header']) && count($dh['header']) >= 1)
            $table_class = "ns_table_with_header";

        if($printBody)
        {
            if(isset($dh['charts']))
            {
                print "<table class=\"ns_table_data\" cellspacing=\"0\" cellpadding=\"0\" width=\"$width%\">\n";
                $width_text = $this->get_width_for_text($dh);
                print "<tr><td class=\"no_wrap_td\" align=\"left\"><table class=\"ns_st_body\" align=\"left\" cellspacing=\"0\" cellpadding=\"0\" width=\"$width_text%\">";
            }
            else
            {

                print "<table class=\"ns_st_body ns_table_data $table_class\" cellspacing=\"0\" cellpadding=\"0\" width=\"$width%\">\n";
            }
        }
        else
            print "<table width=\"100%\" class=\"ns_table_data $table_class\" cellspacing=\"0\" cellpadding=\"0\">";
        if (isset($dh['header']))
            $this->printHeader($dh['header']);
    }

    abstract protected function printHeader($header);
    var $div_suffix = "";
}

//If any changes are made here, test Dashboard for App view along with normal Dashboard
class OneColumnRenderer extends StatisticsRenderer
{
    function printBody($cmd, $dh, $result, $width, $function)
    {
        parent::printBody($cmd, $dh, $result, $width, $function,1);

        foreach ($result['List'] as $obj)
        {
            if (isset($obj['entitytype']) and ($obj['entitytype'] != $function))
                continue;
            $cntrs = $cmd['cntrs'];
            foreach ($dh['counters'] as $cntrname)
            {
                if (!isset($cntrs[$cntrname]))
                    continue;
                $cntr = $cntrs[$cntrname];
                $val = $obj[$cntrname];
                if (isset($cntr['maxval']) and $val >= $cntr['maxval'])
                    continue;
                $attrname = view_utils::get_counter_descr($cntr, $cntrs);

                $tooltip = "";
                if(isset($cntr['tooltip']))
                    $tooltip = $cntr['tooltip'];
                print "\t<tr" . view_utils::get_counter_tooltip_html($attrname, $tooltip) . ">\n";
                print "\t\t<td align=\"left\" width=\"55%\" valign=\"middle\" class=\"td_pad_left\">";
                print "$attrname";
                print "</td>\n";
                print "\t\t<td align=\"left\" width=\"45%\" valign=\"middle\" class=\"td_pad_right\"> <div id=\"$function$cntrname" . $this->div_suffix . "\">";
                print parent::process_value(parent::getcntrvalue($val, $cntr));
                print "</div></td>\n";
                print "\t</tr>\n";
            }
            if (isset($dh['singleton']))
                break;
        }
        if (isset($dh['relatedlink']))
        {
            $colspan = count($dh['header']);
            if (!isset($colspan))
                $colspan = 2;
            parent::print_relatedlink($dh,$colspan);
        }
        print "</table>\n";
        if(isset($dh['charts']))
            parent::output_chart($dh);
    }

    protected function printHeader($header)
    {
        if( count($header) <= 0 )
            return;
        print "<thead>\n";
        print "\t<th valign=\"middle\" width=\"80%\">" . $header[0]. "</th>\n";
        for ($i = 1 ; $i < count($header) ; $i++)
        {
            print "\t<th valign=\"middle\" width=\"20%\"><div>" . $header[$i] ."</div></Th>\n";
        }
        print "</thead>\n";
    }
}

class TwoColumnRenderer extends StatisticsRenderer
{
    function printBody($cmd, $dh, $result, $width, $function)
    {
        parent::printBody($cmd, $dh, $result, $width, $function,0);
        print "\t<TR>\n";   
        print "<td valign=\"top\" width=\"50%\" class = \"td_right_border\">\n";
        if ( isset($dh['column1']['flags']) and $dh['column1']['flags'] == "ANDRATE" )
        {
            $renderer = new RateRenderer();
            $renderer->printBody($cmd, $dh['column1'], $result, 100, $function,1);
        }
        else
        {
            $renderer = new OneColumnRenderer();
            $renderer->printBody($cmd, $dh['column1'], $result, 100, $function,1);
        }
        print "</TD>\n";
        print "<td width=\"50%\" align=\"right\" valign=\"top\">\n";
        if ( isset($dh['column2']['flags']) and $dh['column2']['flags'] == "ANDRATE" )
        {
            $renderer = new RateRenderer();
            $renderer->printBody($cmd, $dh['column2'], $result, 100, $function,1);
        }
        else
        {
            $renderer = new OneColumnRenderer();
            $renderer->printBody($cmd, $dh['column2'], $result, 100, $function,1);
        }
        print "</TD>\n";
        print "\t</TR>\n";
        if (isset($dh['relatedlink']))
        {
            print_relatedlink($dh,2);
        }
        print "</TABLE>\n";
    }

    protected function printHeader($header)
    {
        print "";
    }
}

class ThreeColumnRenderer extends StatisticsRenderer
{
    function printBody($cmd, $dh, $result, $width, $function)
    {
        parent::printBody($cmd, $dh, $result, $width, $function,1);
        print "<tbody>\n";
        foreach ( $result['List'] as $obj )
        {
            if (isset($obj['entitytype']) and ($obj['entitytype'] != $function))
                continue;
            for ( $i=0,$j=0; ($i <= (count($dh['counters1'])-1)) and ($j <= count($dh['counters2'])-1) ; $i++,$j++)
            {
                $str1 = $dh['counters1'][$i];
                $str2 = $dh['counters2'][$j];
                $cntrs = $cmd['cntrs'];
                $cntr1 = $cntrs[$str1];
                $attrname = view_utils::get_counter_descr($cntr1,$cntrs);
                $attrname1 = $attrname;
                foreach ($dh['header'] as $heading)
                    $attrname = preg_replace("/" . addcslashes($heading, "()") . "/i", "", $attrname);
                $attrname = trim($attrname);
                $attrname = ucfirst($attrname);
                $attrname1 = ucfirst(trim($attrname1));

                print "\t<TR>\n";
                print "\t\t<TD width=\"60%\" align=\"left\" valign=\"middle\">" . $attrname . "</TD>\n";
                $tooltip1 = "";
                if(isset($cntr1['tooltip']))
                    $tooltip1 = $cntr1['tooltip'];
                print "\t\t<TD" . view_utils::get_counter_tooltip_html($attrname1, $tooltip1) . "  width=\"20%\" valign=\"middle\">";
                $cntrname1 = $cntr1['cntrname'];
                print "\t<div id=\"$function$cntrname1" . $this->div_suffix . "\">".parent::process_value(parent::getcntrvalue($obj[$cntr1['cntrname']], $cntr1))."</div>";
                print "</TD>\n";
                if( isset($cntrs[$str2]) )
                {
                    $cntr2 = $cntrs[$str2];
                    $tooltip2 = "";
                    if(isset($cntr2['tooltip']))
                        $tooltip2 = $cntr2['tooltip'];
                    $attrname2 = view_utils::get_counter_descr($cntr2, $cntrs);
                    $attrname2 = ucfirst(trim($attrname2));
                    print "\t\t<TD" . view_utils::get_counter_tooltip_html($attrname2, $tooltip2) . " width=\"20%\" valign=\"middle\">";
                    $cntrname2 = $cntr2['cntrname'];
                    print "<div id=\"$function$cntrname2" . $this->div_suffix . "\">".parent::process_value(parent::getcntrvalue($obj[$cntrname2], $cntr2))."</div>";
                    print "</TD>\n";
                }
                else
                {
                    print "\t\t<TD align=\"left\" valign=\"middle\" width=\"20%\"> " . "-" . "</TD>\n";
                }
                print "\t</TR>\n";
            }
            if (isset($dh['singleton']))
                break;
        }
        if (isset($dh['relatedlink']))
        {
            $colspan = count($dh['header']);
            if (!isset($colspan))
                $colspan = 2;
            parent::print_relatedlink($dh,$colspan);
        }
        if(isset($dh['charts']))
        {
            parent::output_chart($dh);
            return;
        }
        print "<tbody>\n";
        print "</TABLE>\n";
    }

    protected function printHeader($header)
    {
        print "<thead>\n";
        if(count($header) > 0)
            print "\t<th width=\"60%\" valign=\"middle\">" . parent::get_string($header[0]). "</th>\n";

        for ($i = 1 ; $i < count($header) ; $i++)
            print "\t<th width=\"20%\" valign=\"middle\"><div>" . parent::get_string($header[$i]) . "</div></th>\n";
        print "</thead>\n";
    }
}

//If any changes are made here, test Dashboard for App view along with normal Dashboard
class RateRenderer extends StatisticsRenderer
{
    function printBody($cmd, $dh, $result, $width, $function)
    {
        parent::printBody($cmd, $dh, $result, $width, $function, 1);
        print "<tbody>\n";
        if (isset($dh['header']) and (count($dh['header']) == 2))
        {
            array_unshift($dh['header'], "&nbsp;");
        }
        foreach ($result['List'] as $obj)
        {
            if (isset($obj['entitytype']) and ($obj['entitytype'] != $function))
            {
                continue;
            }
            $cntrs = $cmd['cntrs'];
            foreach ($dh['counters'] as $cntrname)
            {
                if (!isset($cntrs[$cntrname]))
                    continue;
                $cntr =  $cntrs[$cntrname];
                if(!view_utils::is_rate_counter($cntr['cntrname']))
                    continue;
                $attrname = view_utils::get_counter_descr($cntr,$cntrs);
                $attrname1 = $attrname;
                if (isset($dh['title']))
                {
                    $title = $dh['title'];
                    $title = addcslashes($title, "/");
                    $attrname = preg_replace("/$title/i", "", $attrname);
                }
                if (isset($dh['header'], $dh['header'][0]) and (strcmp($dh['header'][0], "&nbsp;") != 0))
                {
                    $header = trim($dh['header'][0]);
                    $attrname = preg_replace("/$header/i", "", $attrname);
                }
                $attrname = trim($attrname);
                $attrname = ucfirst($attrname);
                $attrname1 = ucfirst(trim($attrname1));

                print "\t<TR>\n";
                $tooltip = "";
                if(isset($cntr['tooltip']))
                    $tooltip = $cntr['tooltip'];

                print "\t\t<TD" . view_utils::get_counter_tooltip_html($attrname1, $tooltip) . " align=\"left\" width=\"60%\">" . $attrname . "</TD>\n";
                $ratecntrname = view_utils::get_rate_counter($cntr['cntrname']);

                if(isset($cntrs[$ratecntrname]))
                {
                    $ratecntr = $cntrs[$ratecntrname];
                    $attrname2 = view_utils::get_counter_descr($ratecntr, $cntrs);
                    $attrname2 = ucfirst(trim($attrname2));
                    print "\t\t<TD" . view_utils::get_counter_tooltip_html($attrname2, $tooltip . " (Rate)") . " align=\"right\" width=\"20%\"> <div id=\"$function$ratecntrname" . $this->div_suffix . "\">".parent::process_value(parent::getcntrvalue($obj[$ratecntrname], $ratecntr))."</div></TD>\n";
                }
                else
                {
                    print "\t\t<TD  align=\"right\" width=\"20%\"> " . "-" . "</TD>\n";
                }
                print "\t\t<TD" . view_utils::get_counter_tooltip_html($attrname1, $tooltip) . " align=\"right\" width=\"20%\"> <div id=\"$function$cntrname" . $this->div_suffix . "\">".parent::process_value(parent::getcntrvalue($obj[$cntrname], $cntr))."</div></TD>\n";
                print "\t</TR>\n";
            }
            if (isset($dh['singleton']))
                break;
        }
        if (isset($dh['relatedlink']))
            parent::print_relatedlink($dh,3);
        print "</tbody>\n";
        print "</TABLE>\n";
        if(isset($dh['charts']))
            parent::output_chart($dh);
    }

    protected function printHeader($header)
    {
        print "<thead>\n";
        if ((count($header) > 0))
        {
            if ( count($header) == 2  )
                array_unshift($header, "&nbsp;");
            for ($i = 0 ; $i < 3 ; $i++)
            {
                if ( $i == 0 )
                    print "\t<th width=\"60%\">" . parent::get_string($header[$i]). "</th>\n";
                else
                    print "\t<th width=\"20%\"><div>" . parent::get_string($header[$i]). "</div></th>\n";
            }
        }
        print "</thead>\n";
    }
}
class TabularRenderer
{
    var $div_suffix = 1;
    function printBody($cmd, $dh, $result, $function, $bndent, $tabular_view_definition)
    {
        global $g_popup_window;
        global $g_popup_url;
        global $g_pageno_url;
        $argname = $cmd["namearg"];
        if(isset($dh["title"]) && $dh["title"] != "")
            print "<table class=\"ns_wp_body\"><tr><td>" . $dh["title"]. "</td></tr></table>\n ";

        view_utils::print_pagination_components($result);
        if(isset($dh["charts"]))
        {
            print "<table cellspacing=\"0\" cellpadding=\"0\" class=\"full_width_table\">\n";
            print "<tr><td width=\"" . StatisticsRenderer::get_width_for_text($dh) . "%\">";
        }

        print "<table cellspacing=\"0\" cellpadding=\"0\" class=\"fixed_header_column_table\">\n";
        print "\t<tr>\n";

        $sort_function = $function . ($this->div_suffix == 1 ? "" : $this->div_suffix);
        $header_index = 0;
        if(isset($dh["header"], $dh["header"][$header_index]))
            $header_name = $dh["header"][$header_index];
        else if($function == "statservicegroup" || $function == "statservicegroupmember")
            $header_name = "Name";
        else
            $header_name = ucfirst($argname);
        view_utils::print_sortable_fixed_header($sort_function, $header_index, "VALSTR", $header_name, "", true);
        print "\t\t<td class=\"fixed_headers\"><div id=\"fixed_header_div" . $sort_function . "\" class=\"fixed_div\"><table cellspacing=\"0\" cellpadding=\"0\"><tr>\n";

        $cntrs = $cmd["cntrs"];
        $show_enable_disable = view_utils::does_command_support_enable_disable($cmd);
        $cntrs_to_iterate = isset($tabular_view_definition) ? $tabular_view_definition["cntrs"] : $dh["counters"];
        foreach($cntrs_to_iterate as $cntrname)
        {
            $cntr =  $cntrs[$cntrname];
            if(isset($tabular_view_definition, $tabular_view_definition["properties"], $tabular_view_definition["properties"][$cntrname], $tabular_view_definition["properties"][$cntrname]["title"]))
                $attrname = $tabular_view_definition["properties"][$cntrname]["title"];
            else if(isset($dh["header"], $dh["header"][$header_index]))
                $attrname = $dh["header"][$header_index];
            else
                $attrname = view_utils::get_counter_descr($cntr, $cntrs);
            $tooltip = isset($cntr["tooltip"]) ? $cntr["tooltip"] : "";
            view_utils::print_sortable_fixed_header($sort_function, $header_index, isset($cntr["type"]) ? $cntr["type"] : "", $attrname, $tooltip);
            if(isset($dh["flags"]) && $dh["flags"] == "ANDRATE")
            {
                $ratecntrname = view_utils::get_rate_counter($cntrname);
                if(isset($cntrs[$ratecntrname]))
                {
                    $cntr =  $cntrs[$ratecntrname];
                    if(isset($dh["header"], $dh["header"][$header_index]))
                        $attrname = $dh["header"][$header_index];
                    else
                        $attrname = view_utils::get_counter_descr($cntr, $cntrs);
                    view_utils::print_sortable_fixed_header($sort_function, $header_index, "VALNUM", $attrname, $tooltip . " (Rate)");
                }
            }
        }
        print "\t\t</tr></table></div></td>\n\t</tr><tr>\n";
        $fixed_column_html = "\t\t<td class=\"fixed_column\"><div id=\"fixed_column_div$sort_function\" class=\"fixed_div\"><table cellspacing=\"0\" cellpadding=\"0\" class=\"content_table\">\n";
        $scrollable_div_class_names = $g_popup_window ? "scrollable_div scrollable_taller_div" : "scrollable_div";
        $scrollable_column_html = "\t\t<td><div id=\"scrollable_div$sort_function\" class=\"$scrollable_div_class_names\" onscroll=\"scroll_fixed_header_column_table('$sort_function')\"><table id=\"content_table$sort_function\" cellspacing=\"0\" cellpadding=\"0\" class=\"content_table\">\n";
        $index = $this->div_suffix;
        foreach($result["List"] as $obj)
        {
            if(isset($obj["entitytype"]) && ($obj["entitytype"] != $function))
                continue;
            $val = isset($obj[$argname]) ? $obj[$argname] : "0";

            $row_class = $index % 2 ? "" : " class=\"even\"";
            $fixed_column_html .= "<tr$row_class>\n";
            $scrollable_column_html .= "<tr$row_class>\n";

            $val = StatisticsRenderer::process_value(StatisticsRenderer::getcntrvalue($val, array("type" => "VALSTR")));
            $display_val = StatisticsRenderer::trim_value($val);
            $is_svcgrp_member = view_utils::is_service_group_member($obj, $function);
            $link_function = $is_svcgrp_member ? "statservicegroupmember" : $function;
            $link_argname = $is_svcgrp_member ? "servicegroupname" : $argname;
            $fixed_column_html .= "<td>";
            if($show_enable_disable)
                $fixed_column_html .= "<input type=\"text\" id=\"" . $function . "_name_txt_$index\" style=\"display: none\" value=\"" . urlencode(preg_replace("/\//", "%2F", $is_svcgrp_member ? $val . ";" . NSI_T_SVCGRP_SVC : $val)) . "\"/>";
            $fixed_column_html .= "<span id=\"$function$argname$index\">";
            if(($bndent || $dh["dispflag"] == "Summary" || $dh["dispflag"] == "Detail") && !isset($cmd["hidden"]))
                $fixed_column_html .= "<a title=\"Click to view details of '$val'\" href=\"/menu/stc?func=$link_function&argname=$link_argname&argval=" . urlencode(preg_replace("/\//", "%2F", $val)) . "&detailview=YES$g_popup_url$g_pageno_url\">$display_val</a>";
            else
                $fixed_column_html .= "$display_val";
            $fixed_column_html .= "</span></td>\n";
            foreach($cntrs_to_iterate as $cntrname)
            {
				$val1 = $obj[$cntrname];
                /**
                 * This change is required to check whether the GSLB Site is LOCAL, if so MEP state/status is not applicable for it.
                 * Only for REMOTE GSLB Site, MEP Status is valid. BUG0617267
                **/
                if(view_utils::is_local_site($obj, $cntrname))
                {
                    $obj["sitemetricmepstatus"] = " ";
                    $obj["nwmetricmepstatus"] = " ";
                }

                $val1 = StatisticsRenderer::process_value(StatisticsRenderer::getcntrvalue($val1, $cntrs[$cntrname]));
                $scrollable_column_html .= "<td class=\"right_align\"><span id=\"$function$cntrname$index\">$val1</span></td>\n";
                if(isset($dh["flags"]) && $dh["flags"] == "ANDRATE")
                {
                    $ratecntrname = view_utils::get_rate_counter($cntrname);
                    if(isset($cntrs[$ratecntrname], $obj[$ratecntrname]))
                    {
                        $rateval1 = $obj[$ratecntrname];
                        $rateval1 = StatisticsRenderer::process_value(StatisticsRenderer::getcntrvalue($rateval1, $cntrs[$ratecntrname]));
                        $scrollable_column_html .= "<td class=\"right_align\"><span id=\"$function$ratecntrname$index\">$rateval1</span></td>\n";
                    }
                }
            }
            $fixed_column_html .= "</tr>\n";
            $scrollable_column_html .= "</tr>\n";
            $index++;
        }
        $fixed_column_html .= "</table></div></td>\n";
        $scrollable_column_html .= "</table></div></td>\n";
        print $fixed_column_html;
        print $scrollable_column_html;
        print "</tr></table>\n";
        if($show_enable_disable)
        {
            print "<table class=\"full_width_table\">\n";
            view_utils::print_state_enable_disable_buttons(count($cntrs_to_iterate) + 1, $cmd);
            print "</table>\n";
        }
        if(isset($dh["charts"]))
        {
            print "</td><td align=\"left\">";
            StatisticsRenderer::output_chart($dh);
        }
        view_utils::print_entities_count_state($cmd, $index, true);

        //Default sort on first column is fully disabled as it was introduced very long back when it wasn't intuitive
        //that users can click on table header & sort the columns, so we applied sorting by default on page load
        //& shown to the end user that this table can be sorted. But now with new L&F changes, it is very intuitive
        //& we don't do this on configuration tables. Hence disabling it for statistics tables as well.
        if(false && view_utils::is_sortable($function))
        {
            $index_to_sort = 0;
            $counter_type_to_sort = "VALSTR";
            $ascending_sort = true;
            $counter_to_sort = view_utils::get_counter_to_sort($function, $ascending_sort);
            if($counter_to_sort)
            {
                foreach($cntrs_to_iterate as $index => $cntrname)
                {
                    if($cntrname == $counter_to_sort)
                    {
                        $index_to_sort = $index + 1; //first column is name (not a counter)
                        $cntr =  $cntrs[$cntrname];
                        $counter_type_to_sort = isset($cntr["type"]) ? $cntr["type"] : "";
                        break;
                    }
                }
            }
            print "<script type=\"text/javascript\">sort_fixed_header_column_table($index_to_sort, '$sort_function', '$counter_type_to_sort');";
            if(!$ascending_sort)
                print "sort_fixed_header_column_table($index_to_sort, '$sort_function', '$counter_type_to_sort');";
            print "</script>\n";
        }
    }
}
?>
