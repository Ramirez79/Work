<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
if(isset($custom_error))
    $error = $custom_error;
else
{
    $error = htmlspecialchars(urldecode($error));
    $error = utils::get_error_message($error);
}

$error = $error . "<div class='ns_alert_text_grey' style='padding-left: 300px'> Please click <a href=javascript:redirectToRoot()><u>here</u></a> to login again</div>";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Error</title>
<link href="/admin_ui/common/css/ns/ui.css" type="text/css" rel="stylesheet">
<script>
     redirectToRoot = function()
     {
        window.location.href = "/";
     }
</script>
</head>
<body class="ns_body">
<br>
<p align="center" class="ns_alert_text_grey"> <?= $error; ?> </p>
</body>
</html>