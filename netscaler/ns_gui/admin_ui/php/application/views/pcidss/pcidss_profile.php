<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

include_once("pcidss_common.php");

global $g_pargs;
$g_pargs = $global_pargs;
$pdf = $g_pargs['pdf'];
$nsEntitydef = $g_pargs['nsEntitydef'];
$fontSize = $g_pargs['fontSize'];

$pdf->addDestination($profile['name'], 'FitH'); // to be referred from fwConfig Profile table
$profileTitle = ((isset($profile['profile_no']))? $profile['profile_no']. ". " : "") . "Profile : " . $profile['name'];
$pdf->ezText("<c:colorit:\$r=0.5;\$g=0.5;>".  $profileTitle . "</c:colorit>", 12);
$defn = $nsEntitydef->getDefinition('appfwprofile');
// if this is invoked after pcidss_fwconfig view is called then $profile['type_disp'] already contains the mapped values, if called directly it has to be mapped.
if(isset($defn['map']['type_disp'][$profile['type_disp']]))
    $profileType = $defn['map']['type_disp'][$profile['type_disp']];
else
    $profileType = $profile['type_disp'];

$pdf->ezText($g_pargs['tabChars']."<b><i>" . $profileType . "</i></b>", $fontSize);

#appfwprofilesettings
$settings = array();

if($profile['type_disp'] == "BUILT-IN")
{
    if($profile['name'] == "APPFW_BLOCK")
    {
        if($profile['usehtmlerrorobject'] == "ON")
            array_push( $settings, array('name' => "HTML Error Object", 'value' => $profile['htmlerrorobject']));
        else
            array_push( $settings, array('name' => "HTML Error Redirect URL", 'value' => $profile['errorurl']));
        array_push( $settings, array('name' => "XML Error Object", 'value' => $profile['xmlerrorobject']));
    }
    array_push( $settings, array('name' => "Log Every Policy Hit", 'value' => $profile['logeverypolicyhit']));
    displayEntity($settings, "appfwprofilesettings");
    $pdf->ezText("\n"); // to have gap between profiles
    return;
}

displayEntity($creditcard, "creditcard");

$isHTML = $isXML = false;
if(strpos($profile['type_disp'],'HTML') !== false)
    $isHTML = true;
if(strpos($profile['type_disp'],'XML') !== false)
    $isXML = true;


if(!isset($profile['signatures']) || trim($profile['signatures']) == "")
   array_push( $settings, array('name' => "Signature", 'value' => "(Not Set)"));
else
    array_push( $settings, array('name' => "Signature", 'value' => $profile['signatures']));

// HTML Settings
if($isHTML)
{
    if($profile['usehtmlerrorobject'] == "ON")
        array_push( $settings, array('name' => "HTML Error Object", 'value' => $profile['htmlerrorobject']));
    else
        array_push( $settings, array('name' => "HTML Error Redirect URL", 'value' => $profile['errorurl']));
    array_push( $settings,
                array('name' => "Charset", 'value' => $profile['defaultcharset']),
                array('name' => "Exclude Upload Files From Security Checks", 'value' => $profile['excludefileuploadfromchecks']),
                array('name' => "Exempt Closure URLs From Security Checks", 'value' => $profile['exemptclosureurlsfromsecuritychecks']),
                array('name' => "Enable Form Tagging", 'value' => $profile['enableformtagging']),
                array('name' => "Canonicalize HTML Response", 'value' => $profile['canonicalizehtmlresponse']),
                array('name' => "Maximum File Uploads", 'value' => $profile['fileuploadmaxnum']),
                array('name' => "Strip HTML Comments", 'value' => $profile['striphtmlcomments']),
                array('name' => "Invalid Percent Handling", 'value' => $profile['invalidpercenthandling']),
                array('name' => "Default Request Content Type", 'value' => isset($profile['requestcontenttype'])? $profile['requestcontenttype'] : ""),
                array('name' => "Default Response Content Type", 'value' => $profile['responsecontenttype'])
                );
    $inspectcontenttypes = "";
    foreach ($profile['inspectcontenttypes'] as $each) {
        $inspectcontenttypes = $inspectcontenttypes."\"".$each."\" ";
    }
    array_push( $settings,
                array('name' => "Inspected Content Types", 'value' => $inspectcontenttypes)
                );
}
// XML Settings
if($isXML)
    array_push( $settings, array('name' => "XML Error Object", 'value' => (trim($profile['xmlerrorobject']) == "") ? "(Not Set)": $profile['xmlerrorobject']));

// Common Settings
array_push( $settings,
        array('name' => "Post Body Limit", 'value' => $profile['postbodylimit']),
        array('name' => "Check Request Headers", 'value' => $profile['checkrequestheaders']),
        array('name' => "Log Every Policy Hit", 'value' => $profile['logeverypolicyhit'])
        );
displayEntity($settings, "appfwprofilesettings");

displayEntityWithActions(isset($starturl)? $starturl: null, "starturl", $starturl_actions);
displayEntityWithActions(isset($cookieconsistency)? $cookieconsistency: null, "cookieconsistency", $cookieconsistency_actions);
displayEntityWithActions(isset($contenttype)? $contenttype: null, "contenttype", $contenttype_actions);
if($isHTML)
    displayEntityWithActions(isset($fieldconsistency)? $fieldconsistency: null, "fieldconsistency", $fieldconsistency_actions);
displayEntityWithActions($bufferoverflow, "bufferoverflow", $bufferoverflow_actions);
if($isHTML)
{
    displayEntityWithActions(isset($fieldformat)? $fieldformat:null, "fieldformat", $fieldformat_actions);
    displayEntityWithActions(isset($csrf)? $csrf:null, "csrf", $csrf_actions);
    displayEntityWithActions(isset($crosssitescripting)? $crosssitescripting:null, "crosssitescripting", $crosssitescripting_actions);
    displayEntityWithActions(isset($sqlinjection)? $sqlinjection:null, "sqlinjection", $sqlinjection_actions);
}
displayEntityWithActions(isset($safeobject)? $safeobject:null, "safeobject", null);
displayEntityWithActions(isset($denyurl)? $denyurl:null, "denyurl", $denyurl_actions);
if($isXML)
{
    displayEntityWithActions(null, "xmlformat", $xmlformat_actions);
    displayEntityWithActions((isset($xdoscheck)? $xdoscheck: null), "xdoscheck", $xdoscheck_actions);
    displayEntityWithActions((isset($xmlcrosssitescripting)? $xmlcrosssitescripting: null), "xmlcrosssitescripting", $xmlcrosssitescripting_actions);
    displayEntityWithActions((isset($xmlsqlinjection)? $xmlsqlinjection: null), "xmlsqlinjection", $xmlsqlinjection_actions);
    displayEntityWithActions((isset($xmlattachment)? $xmlattachment: null), "xmlattachment", $xmlattachment_actions);
    displayEntityWithActions((isset($xmlwsi)? $xmlwsi: null), "xmlwsi", $xmlwsi_actions);
    displayEntityWithActions((isset($xmlvalidation)? $xmlvalidation: null), "xmlvalidation", $xmlvalidation_actions);
    displayEntityWithActions(null, "xmlsoapfault", $xmlsoapfault_actions);
}
$pdf->ezText("\n"); // to have gap between profiles
?>