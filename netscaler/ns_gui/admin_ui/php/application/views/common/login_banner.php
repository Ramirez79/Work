<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Pragma" content="no-cache" />

<title>Challenge password</title>

<link rel="stylesheet" href="/admin_ui/common/css/ns/ui.css" type="text/css">
<style type="text/css">
.ns_wp_header
{
    font-size: 1em !important;
}
</style>
<script type="text/javascript">
function doActionOnLoad()
{
    document.getElementById("ok_btn").focus();
}
</script>
</head>

<body>
<div class="ns_login_wrapper ns_login_challenge_wrapper">
    <div class="ns_login_inner_wrapper">
        <div class="ns_login_top"></div>
        <div class="ns_login_center_content">
            <table class="ns_login_large_product_brand" align="center">
                <tr>
                    <td class="ns_login_logo ns_login_logo_padding ns_login_banner_logo">
                        <div class="login_banner">
                            <?=$banner_contents?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td align="left">
                        <div class="login_button rdx_blue_button login_challenge_button">
                            <a id="ok_btn" style="text-decoration: none; color: white" href="<?=$url?>" title="Click to proceed">&nbsp;&nbsp;&nbsp;&nbsp;OK&nbsp;&nbsp;&nbsp;&nbsp;</a>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
<?php
$this->load->view("common/footer");
?>
