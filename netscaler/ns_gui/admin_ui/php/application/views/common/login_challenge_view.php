<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
//Don't allow this page to be embedded inside a frame
header("X-Frame-Options: DENY");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Pragma" content="no-cache" />
<title>Challenge password</title>

<link rel="stylesheet" href="/admin_ui/common/css/ns/ui.css" type="text/css">

<script language="JavaScript" type="text/javascript">
function checkform ( form )
{
  if (form.response.value == "") {
    alert( "Please enter the challenge response" );
    form.response.focus();
    return false ;
  }
  return true ;
}
</script>
</head>

<body class="ns_login_body" onload="document.form_challenge.response.focus();">
<noscript>
    <div class="center_panel ns_alert_text">
        JavaScript is either disabled in or not supported by the Web browser. To continue logon, use a Web browser that supports JavaScript or enable JavaScript in your current browser.
    </div>
    <div style="display: none">
</noscript>
<div class="ns_login_wrapper ns_login_challenge_wrapper">
    <div class="ns_login_inner_wrapper">
      <div class="ns_login_top"></div>
      <form name="form_challenge" action="/login/do_login" method="post" onsubmit="return checkform(this);">
      <div class="ns_login_center_content">
      <table class="ns_login_large_product_brand" align="center">
        <tr>
            <td class="ns_login_logo ns_login_logo_padding">
          <div></div>
          </td>
          <td></td>
        </tr>
        <tr>
          <td colspan="2" class="ns_form_description">
            <div>HELLO THERE WAS NOT ENTRY</div>
            <br>
          </td>
        </tr>
        <tr>
          <td colspan="2"><input type="password" name="response" size="31" onfocus=1></td>
        </tr>
        <tr>
           <td class="ns_login_button_padding" colspan="1">
           <input class="login_button rdx_blue_button login_challenge_button" type="submit" value="OK" title="Click to proceed">
           </td>
           <td></td>
        </tr>
      </table></div></form>
    </div>
</div>
<script language="JavaScript" type="text/javascript">
//Don't allow this page to be embedded inside a frame
if(self != top)
{
    document.getElementsByTagName("body")[0].style.display = "none";
    top.location = self.location;
}
</script>
<?php
$this->load->view("common/footer");
?>
</body></html>
