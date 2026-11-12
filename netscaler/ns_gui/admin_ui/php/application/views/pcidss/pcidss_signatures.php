<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

include_once("pcidss_common.php");

global $g_pargs;
$g_pargs = $global_pargs;
$ip = getenv("HTTP_HOST");
$pdf = $g_pargs['pdf'];
$nsEntitydef = $g_pargs['nsEntitydef'];
$fontSize = $g_pargs['fontSize'];

if(preg_match("/^_default_signature_/",$sig_name))
{
    $pdf->ezText("<c:colorit:\$r=0.5;\$g=0.5;>".  "Signature : Default Signature</c:colorit>\n", 12);
    displayEntity($sig_rules, "signatures");
}
else
{    if(!isset($detailed_view))
	{
		$pdf->ezText("<c:colorit:\$r=0.5;\$g=0.5;>".  "Signature : " . $sig_name . "</c:colorit>\n", 12);
		#$pdf->ezText("<c:alink:/pcidss/launch_report?type=signature&sig_name=$sig_name&detailed_view=true" . ">". "Detailed Rules report"  ."</c:alink>\n", $g_pargs['fontSize']);
		$detailed_link = "<c:alink:http://$ip/pcidss/launch_report?type=signature&sig_name=$sig_name&detailed_view=true" . ">". "Detailed Rules Report"  ."</c:alink>";
		if((isset($def_rules) && count($def_rules) > 0) || (isset($sig_rules) && count($sig_rules) > 0))
		{	
			$g_pargs['pdf']->ezText( "     The following tables list the rules that are enabled for signature " . $sig_name . " . For a report about both enabled and disabled rules in detail, see " . $detailed_link . ".\n", $g_pargs['fontSize']);
		}
		else
		{
			$g_pargs['pdf']->ezText( "     No rules are enabled for signature " . $sig_name  , $g_pargs['fontSize']);
		}
	}
	else
	{	
		$pdf->ezText("<c:colorit:\$r=0.5;\$g=0.5;>".  "Detailed Report for Signature : " . $sig_name . "</c:colorit>\n", 12);
	}
	
    if(isset($def_rules) && count($def_rules) > 0)
    {
        if(!isset($detailed_view))
		{
			$g_pargs['pdf']->ezText( "<b><i>Default Rules</i></b>\n", $g_pargs['fontSize']);
			$g_pargs['pdf']->ezText( "     Rules that are derived from the Default Signatures object.\n", $g_pargs['fontSize']);
			displayEntity($def_rules, "signatures_same_as_default");
		}
		else
		{
			$g_pargs['pdf']->ezText( "<b><i>Default Rules</i></b>\n", $g_pargs['fontSize']);
			displayEntity($def_rules, "signatures_with_details");
		}
    }

    if(isset($sig_rules) && count($sig_rules) > 0)
    {
        
        if(!isset($detailed_view))
		{
			$g_pargs['pdf']->ezText( "\n<b><i>User Defined Rules</i></b>\n", $g_pargs['fontSize']);
			displayEntity($sig_rules, "signatures");
		}
		else
		{
			displayEntity($sig_rules, "signatures_with_details");
			
		}	
    }
	
	if(isset($detailed_view) && !(isset($sig_rules) && count($sig_rules) > 0))
	{
		$g_pargs['pdf']->ezText( "     No rules found");
	}
}
$pdf->ezText("\n"); // to have gap between signatures
if(isset($next_link))
    $pdf->ezText($next_link);

?>