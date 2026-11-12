<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
global $g_pargs;
$g_pargs = $global_pargs;
$pdf = $g_pargs['pdf'];
$links = "";
$ip = getenv("HTTP_HOST");
if($g_pargs['which_report'] != "main")
	$links = "<c:alink:http://$ip/pcidss/launch_report?type=main>Home</c:alink>";
else
        $links = "<c:alink:http://$ip/pcidss/launch_report?type=fwconfig>Application Firewall Configuration</c:alink>";

if($g_pargs['which_report'] == "fwprofile")
	$links = $links . " | <c:alink:http://$ip/pcidss/launch_report?type=fwconfig>Application Firewall Configuration</c:alink>";
if($g_pargs['which_report'] == "fwconfig")
        $links = $links . " | <c:alink:http://$ip/pcidss/launch_report?type=allprofiles>All Profiles</c:alink>";
if($g_pargs['which_report'] != "full_report")
	$links = $links . " | <c:alink:http://$ip/pcidss/launch_report?type=full_report>Entire Report</c:alink>";

if($g_pargs['which_report'] != "main")
	if($g_pargs['which_report'] != "all_signatures")
		$links = $links . " | <c:alink:http://$ip/pcidss/launch_report?type=all_signatures>Signatures Report</c:alink>";

$pdf->ezText($links, $g_pargs['fontSize'],array('justification'=>'centre'));
$pdf->ezText("\n",8);

$pdf->ezText("<b>Citrix NetScaler</b>",14,array('justification'=>'centre'));
$pdf->ezText("<b>PCI-DSS v3.2 Compliance Report</b>",14,array('justification'=>'centre'));
$pdf->ezText("Netscaler : ". $g_pargs['nslocaltime'],12,array('justification'=>'centre'));
if(isset($g_pargs['client_time']))
    $pdf->ezText("Local : ". $g_pargs['client_time'],12,array('justification'=>'centre'));
$pdf->ezText("By : " . $g_pargs['username'],12,array('justification'=>'centre'));
?>
