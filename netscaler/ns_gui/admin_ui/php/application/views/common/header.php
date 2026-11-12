<?php  if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?= $title;?></title>
<link href="/admin_ui/common/css/ns/ui.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/admin_ui/common/js/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript">
//rand is used in utils.js in the URL to logout and in the URL to update NSAPI token
//rand_key & rand are used in utils.js to avoid CSRF in all POST requests
<?php
print "var rand = \"" . (isset($_SESSION["rand"]) ? $_SESSION["rand"] : "") . "\";\n";
print "var rand_key = \"" . (isset($_SESSION["rand_key"]) ? $_SESSION["rand_key"] : "") . "\";\n";
print "var NSERR_SESSION_EXPIRED = " . NSERR_SESSION_EXPIRED . ";\n";
?>

</script>
<script type="text/javascript" src="/admin_ui/common/js/ns/utils.js"></script>
<script type="text/javascript">
var ns_page_max_width = 1366;
var actual_ns_page_max_width = ns_page_max_width - 20;
function partitonOnClick(new_partition)
{
    var current_val = $("#current_partition").text();
    if (current_val !== new_partition)
    {
        var confirm = window.confirm("Do you want to change to partiton " + new_partition);
        if(confirm === true)
        {
            var url = "/nitro/v1/config";
            var method = "POST";
            var params = "object={\"params\":{\"action\":\"switch\"},\"nspartition\":{\"partitionname\":\"" + new_partition  +"\"}}";
            
            var handler = new ns.ajax_handler(url, function(){set_cookie("drep", ""); window.location.reload();}, params, method, true, false, function(){alert('Failed to switch the partition')});
            handler.set_custom_header({"rand_key": window.rand, "NITRO_WEB_APPLICATION": "true"});
            handler.send();
        }
    }
    
};


function userInfoOnClick(id,container)
{
    var id = document.getElementById(id);
    var container = document.getElementById(container);
    if(!($(container).hasClass("header_dropdown_collapsed")))
    {
        $(container).addClass("header_dropdown_collapsed");
        $(id).slideDown();   
    }
    else
    {   
        $(container).removeClass("header_dropdown_collapsed"); 
        $(id).slideUp();
    }
    
}

function headerInfoOnClick(id,container)
{   
    var id = document.getElementById(id);
    var container = document.getElementById(container);
    if(!($(container).hasClass("header_dropdown_collapsed")))
    {
        $(container).addClass("header_dropdown_collapsed");
        $(container).removeClass("header_info_container"); 
        $(id).slideDown();   
    }
    else
    {   
        $(id).slideUp(); 
        $(container).removeClass("header_dropdown_collapsed"); 
        $(container).addClass("header_dropdown_container header_info_container");        
    }
   
}
function partitionInfoOnClick(id,container)
{   
    var id = document.getElementById(id);
    var container = document.getElementById(container);
    if(!($(container).hasClass("header_dropdown_collapsed")))
    {
        $(container).addClass("header_dropdown_collapsed");
        $(container).removeClass("header_info_container"); 
        $(id).slideDown();   
    }
    else
    {   
        $(id).slideUp();
        $(container).removeClass("header_dropdown_collapsed"); 
        $(container).addClass("header_dropdown_container_composite header_info_container");      
    }
}
</script>
<?php
if (isset($extraHeadContent)) {
    echo $extraHeadContent;
}
?>
<style type="text/css">
.ns_wp_header
{
    font-size: 150%;
}
</style>
<!--[if IE]>
<style type="text/css">
.ns_entity_div
{
    overflow: scroll;
    overflow-y: hidden;
    height: 100%;
    border: 1px;
}

.tree_links img
{
    vertical-align: middle;
}

.manage_custom_reports td
{
    vertical-align: text-top;
}

.toolbar a img
{
    border: none;
    margin: 2px 2px 2px 0px;
}

.import_reports_file
{
    filter: alpha(opacity = 0);
}

.import_reports_fake_div
{
    top: 0px;
}

.coc_combo
{
    width: auto;
}

.coc_combo_popup
{
    width: auto !important;
}
</style>
<![endif]-->
<!--[if IE 6]>
<style type="text/css">
.throughput table
{
    background-color: #ffffff;
}

.div_for_list
{
    width: 100%;
}

.change_text_size_div
{
    position: absolute;
    left: 0px;
}

.ns_body
{
    width: expression(this.parentNode.clientWidth < 940 ? "935px" : "100%" );
}

.counter_tooltip_div
{
    width: 300px;
}

.counter_tooltip_content_div
{
    height: expression(this.parentNode.clientHeight > 105 ? "100px" : "100%" );
}
</style>
<![endif]-->
</head>
<?php
//In case of standalone clients (webstart), don't print top banner
if(!isset($standalone) || (strcasecmp($standalone, "YES") != 0))
    print_top_banner();
else
{
    print "<body class=\"ns_body\">\n";
    print "<div id=\"ns_header\">\n";
}

function get_product_link_name()
{
    if(is_agee())
        return "accessgateway";
    if(is_bytemobile())
        return "";
    return "netscaler";
}

function is_tab_selected($url)
{
    return preg_match("/" . addcslashes($url, "/") . "/", $_SERVER["REQUEST_URI"]);
}

function print_tabs($links)
{
    $found_selected_tab = false;
    foreach($links as $obj)
    {
        $link = $obj["link"];
        $link_text = $obj["text"];
        //There can't be two selected tabs. Once found the selected tab, avoid looking at URL for the rest.
        if(!$found_selected_tab && is_tab_selected($link))
        {
            print "<span class=\"current_tab\"><a href=\"$link\"><span>$link_text</span></a></span>";
            $found_selected_tab = true;
        }
        else
            print "<span><a href=\"$link\"><span>$link_text</span></a></span>";
    }
}

function print_menu($links){
    $found_selected_tab = false;
    $count = 0;
    foreach($links as $obj)
    {
        $link = $obj["link"];
        $link_text = $obj["text"];
        //There can't be two selected tabs. Once found the selected tab, avoid looking at URL for the rest.
        if(!$found_selected_tab && is_tab_selected($link))
        {
             if($count == 0){
                print "<li class=\"active\"><a href=\"$link\">$link_text</a><div>^</div></li>";
            }else{
                print "<li class=\"active\"><a href=\"$link\">$link_text</a><div>^</div></li>";
            }
            $found_selected_tab = true;
        }
        else{
            if($count == 0){
                print "<li><a href=\"$link\">$link_text</a><div>^</div></li>";
            }else{
                print "<li><a href=\"$link\">$link_text</a><div>^</div></li>";
            }
        }
        $count = $count + 1;
    }
}

function print_short_version_str()
{
    $version_str = $_SESSION["nsversion"];
    $version_str_short = "";
    
    if(!preg_match('/Not authorized to execute this command/', $version_str))
    {
    $version_array = explode(",", $version_str);
    $version_array = explode(":" , $version_array[0]);
    $version_str_short = $version_array[0] . str_replace( "Build ", "" ,  $version_array[1]);
    }
    
    print $version_str_short;
}

function is_ipv4($ip_address)
    {
        if (preg_match("/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/",$ip_address) || preg_match("/^\*$/",$ip_address))
        return true;
        else
            return false;
        
    }
function is_ipv6($ip_address)
    {
        if (preg_match("/^\s*((([0-9A-Fa-f]{1,4}:){7}([0-9A-Fa-f]{1,4}|:))|(([0-9A-Fa-f]{1,4}:){6}(:[0-9A-Fa-f]{1,4}|((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){5}(((:[0-9A-Fa-f]{1,4}){1,2})|:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3})|:))|(([0-9A-Fa-f]{1,4}:){4}(((:[0-9A-Fa-f]{1,4}){1,3})|((:[0-9A-Fa-f]{1,4})?:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){3}(((:[0-9A-Fa-f]{1,4}){1,4})|((:[0-9A-Fa-f]{1,4}){0,2}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){2}(((:[0-9A-Fa-f]{1,4}){1,5})|((:[0-9A-Fa-f]{1,4}){0,3}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(([0-9A-Fa-f]{1,4}:){1}(((:[0-9A-Fa-f]{1,4}){1,6})|((:[0-9A-Fa-f]{1,4}){0,4}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:))|(:(((:[0-9A-Fa-f]{1,4}){1,7})|((:[0-9A-Fa-f]{1,4}){0,5}:((25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)(\.(25[0-5]|2[0-4]\d|1\d\d|[1-9]?\d)){3}))|:)))(%.+)?(\/(1[0-2][0-8]|[1-9][0-9]|[0-9]))?\s*$/",$ip_address) || preg_match("/^\*$/",$ip_address))
        return true;
        else
            return false;
        
    }

function print_partiton_menu()
{
    require_once(APPPATH . "controllers/common/rapi.php");
    $rapi = new rapi(true);
    $result = $rapi->main("system_info");
    $availablepartitions = isset($result["system_info"]["availablepartitions"]) ?$result["system_info"]["availablepartitions"] : array();
    $defaultpartition = isset($result["system_info"]["defaultpartition"]) ?$result["system_info"]["defaultpartition"] : "" ;
   
    $version_str = "";
    $version_array = explode(",", $result["system_info"]["nsversion"]);
    $version_array = explode(":" , $version_array[0]);
    $version_str = $version_array[0] . str_replace( " Build ", "" ,  $version_array[1]);
    
    if(count($availablepartitions) > 0)
    {
        print "\n";
        print "<li class='header_info_outer_container'> \n";
        print "<div id ='partition_container'class='header_dropdown_container_composite header_info_container' onClick=partitionInfoOnClick(\"global_info_item\",\"partition_container\");> \n";
        print "<table cellpadding='0' cellspacing='0'> <tbody> \n";
        print "<tr><td><span class='header_info_item_value'>Partition</span></td> </tr>\n";
        print "<tr><td><span class='header_info_item_label' style='color: rgb(211, 211, 211); font-size:11px; '>".$defaultpartition ."</span></td></tr> \n";
        print "</tbody></table> \n";
        print   "<div id='global_info_item' class='header_dropdown_options_container' style='display: none;padding-bottom: 0px; min-width: 135px;'> \n";
        print      "<div class = 'global_info_item_header'> \n";
        for($i=0; $i< count($availablepartitions); $i++)
              print  "<div class='header_info_item_label' onClick=partitonOnClick(\"" .$availablepartitions[$i] ."\")>" .$availablepartitions[$i] ."</div> \n";
        print   "</div> </div>\n";
        print "</li> \n";
        
    }
}

    
function print_ha_node_status()
{
    require_once(APPPATH . "controllers/common/rapi.php");
    $rapi = new rapi(true);
    $result = $rapi->main("system_info");

    if(isset($result["system_info"]["ha_nodes"]))
    {  
        $ha_nodes = $result["system_info"]["ha_nodes"];
        print "\n";
        print "<li class='header_info_outer_container'> \n";
        print "<div class='header_status_container' style='background: none;''>\n";
        print "<table cellpadding='0' cellspacing='0'> <tbody>\n";
        print "<tr><td><label>HA Status</label></td></tr> \n";
        print "<tr><td><div>\n";

        if(count($ha_nodes) == 1)
        {
            print "<span class = 'node_status_image_unknown'> </span>\n";
            print "<span style = 'padding-left:5px; font-size:11px; color: #D3D3D3'>Not Configured</span>\n";
        }
        else
        {
            for ($i = 0; $i <= count($ha_nodes); $i++) 
            {
                if($ha_nodes[$i]["id"] == "0")
                {
                    $ha_state = $ha_nodes[$i]['state'];
                    $ha_status = $ha_nodes[$i]['hastatus'];
                    if(isset($ha_state) && isset($ha_status))
                    {    
                        if($ha_status == "UP" || $ha_status == "STAYPRIMARY" || $ha_status == "STAYSECONDARY")
                            print "<span class = 'node_status_image_up'> </span>\n";
                        else if ($ha_status == "DOWN" || $ha_status =="NOT UP")
                            print "<span class = 'node_status_image_down'> </span>\n";
                        else if ($ha_status == "DISABLED")
                            print "<span class = 'node_status_image_disabled'> </span>\n";
                        else 
                            print "<span class = 'node_status_image_unknown'> </span>\n";

                        print "<span class='status_label'>" .$ha_state. "</span>\n";
                    }

                    break;
                }    
            }
        }

        print "</div></td></tr> \n";
        print "</tbody></table></li> \n";
    }
}


function print_top_banner()
{
    $app_links = get_branding()->get_applinks();
    $info_links = get_branding()->get_infolinks();

    ?>
    <body class="ns_body ns_body_main ns_body_main_container">
    <div id="ns_header" class="base_view_no_view_top_container rdx_auto_cursor">
        <div class= "header_parent_div">
            <div class="ns_header">
                <table id="ns_header_view_top_panel" cellspacing="0" cellpadding="0" class="full_width_table ns_banner_table ns_page_center_align header_info">
                    <tbody>
                        <tr>
                            <td class="header_info_first"></td>
                            <td class="ns_logo">
                                <?php
                                $is_xen_center = is_xen_center();
                                if(!$is_xen_center)
                                {
                                ?>
                                    <a href="<?=get_branding()->get_brand_link()?>" target="PRODUCT_LINK">
                                        <div class="ns_logo_version ns_company_logo"><?=$_SESSION["nsbrandDesc"]?> <?php if($_SESSION["ns_model"] !== "") print "&nbsp;<span class=\"ns_model\" title=\"Platform Information\">(" . $_SESSION["ns_model"] . ")</span>";?></div>
                                </div>
                                <?php
                                }
                                ?>
                            </td>
                            <td>
                                <div class="header_info_right">
                                    <ul>
                                    <?php print_ha_node_status(); ?>
                                    <?php print_partiton_menu(); ?>                                      
  
                                      <li class="header_user_info_container" > 
                                          <div id ='user_logout_container' class='header_dropdown_container' onClick="userInfoOnClick('global_info_header','user_logout_container')";><span><?= $_SESSION["username"] ?></span>
                                           <div id = "global_info_header" class="header_dropdown_options_container" style="min-width: 116px; overflow: hidden; display: none;">
                                           <div class="header_info_item_value logout_label" >
                                                 <a href="<?=get_logout_url()?>" title="Logout"><span>Logout</span></a>
                                             </div>
                                            

                                            <div class ="header_global_info_item">
                                            <?php
                                                if(strpos($_SESSION["nshostDesc"],'('))
                                                {
                                                ?>
                                                <div class="header_info_item_label">IP Address</div>
                                                <?php
                                            }
                                            else
                                            {
                                                if(is_ipv4($_SESSION["nshostDesc"]) || is_ipv6($_SESSION["nshostDesc"]))
                                                {
                                            ?>
                                            <div class="header_info_item_label">IP Address</div>
                                            <?php
                                                }
                                                else
                                                {
                                            ?>
                                                <div class="header_info_item_label">Host Name</div> 
                                            <?php
                                                }
                                                } 
                                                                                
                                                $new_host_desc_with_ellipsis=$_SESSION["nshostDesc"];
                                                if(strlen($_SESSION["nshostDesc"])>50)
                                                $new_host_desc_with_ellipsis=substr($_SESSION["nshostDesc"],0,50)."...";
                                            ?>                               
                                                
                                                <div class="header_info_item_value"><?= $new_host_desc_with_ellipsis ?></div> 
                                            <div class="header_info_item_label">Release</div> 
                                                <?php
                                                $version_index=explode(",",$_SESSION["nsversion"]);
                                                ?>
                                            <div class="header_info_item_value"><?= $version_index[0] ?></div>
                                            </div>

                                           </div>
                                        </div>
                                      </li>
                                    </ul>
                                </div>
                            </td>
                            <td class="header_info_last"></td>
                        </tr>
                    </tbody>
                </table>
                <div class="full_width_table header_menu_center" style="width: 100%;">
                    <table id="ns_header_view_bottom_panel" cellspacing="0" cellpadding="0" class="full_width_table header_menu ns_page_center_align">
                        <tbody>
                            <tr>
                                <td class="header_menu_left">
                                </td>
                                <td>
                                    <ul class="left_menu">
                                        <?php print_menu($app_links); ?>
                                        <?php print_menu($info_links); ?>                                            
                                    </ul>
                                    <ul class="right_menu">
                                    </ul>
                                </td>
                                <td class="header_menu_right">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <?php
} //End of print_top_banner
?>
</div>
<?php
$ns_content_style = "";
if(!isset($no_loading_content))
{
    $ns_content_style = "style=\"display: none\"";
?>
<div id="loading_content" class="center_panel">
    <table align="center" class="loading_panel">
        <tr>
            <td class="loading_panel_img"><img src="/admin_ui/common/images/in_progress.gif"></td>
        </tr>
    </table>
</div>
<?php
}
?>
<div id="ns_content" class="ns_content" <?=$ns_content_style?>>
