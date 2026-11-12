<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
include_once("pcidss_common.php");

global $g_pargs;
$g_pargs = $global_pargs;
$pdf = $g_pargs['pdf'];

$pdf->ezText("\n<b>Application Firewall Configuration</b>", 12);
displayEntity($appfwsettings, "appfwsettings");
displayEntity($appfwpolicy, "appfwpolicy");
displayEntity($appfwprofile, "appfwprofile");
$pdf->ezText("\n\n");
?>