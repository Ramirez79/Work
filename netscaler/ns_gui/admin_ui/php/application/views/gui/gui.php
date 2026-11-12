<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view("common/header");
?>
<script type="text/javascript"> <? require_once(APPPATH. "views/common/plugin_check.php") ?> </script>
<? require_once(APPPATH. "views/common/check_ws_installed.inc") ?>
<script type="text/javascript" src="/admin_ui/common/js/ns/invoke_ws.js"></script>
<br>

<TABLE cellpadding=0 cellspacing=0 border=0 align="center" width="75%">

	<tr class="ns_wp_header">
		<td>System Configuration</td>
	</tr>


	<tr>
		<td align="justify">
		  <br>
		  The configuration utility is a comprehensive graphical user configuration interface to the system. Using the configuration GUI, it is possible to configure all aspects of system behavior, and also view summary system performance statistics.<br><br>
		  Using the applet option will invoke the JAVA based web client. Using the webstart client will download the relevant JAVA components that will make it possible to execute subsequent GUI sessions without opening the browser and keying in the System IP.<br><br>
		  Both the applet client and the webstart client require JRE 1.6+ to be installed on the client systems.<br><br>
		Web Client is the HTML version of the Applet Client. Currently some of the functionalities require JRE.<br><br>
		</td>
	</tr>

	<TR>
		<TD nowrap height="10" width=100%><IMG SRC="/admin_ui/common/images/spacer.gif" width=1 height=1 alt=""></TD>
	</TR>

	<tr>
          	<td>
			<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Configuration Utility:</b>
			<a href="/menu/guia" title="Applet Client">Applet Client</a>&nbsp;&nbsp;&nbsp;
            <a href="#noAnchor" onclick="javascript:invoke_webstart('guiw', '<?=$_SESSION['nsbrand']?>', '<?=$_SESSION['nsvpx']?>', 'GUI_WS_' + '<?=$ns_version?>');" title="Web Start Client">Web Start Client</a>&nbsp;&nbsp;
            <a href="/help/im/help.htm" target="_new">Online Help</a>
			&nbsp;|&nbsp;<a href="/menu/neo" title="Web Client">Web Client</a>
	        </td>
    </tr>

	<TR>
		<TD nowrap height="30" width=100%><IMG SRC="/admin_ui/common/images/spacer.gif" width=1 height=1 alt=""></TD>
	</TR>

	<tr> <td colspan="3"> <hr style="color: #003399"/> </td> </tr>

	<tr>
		<td class="ns_alert_text" align="center">
			Best viewed with 1024 x 768 screen resolution and above.
		</td>
	</tr>


</table>