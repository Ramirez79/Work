function CreateMainTable()
{
	document.writeln("<table class=\"full_width_height\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\">");
}

function CreateBannerI()
{
	document.writeln("<tr>");
	document.writeln("<td valign=\"top\" class=\"full_width\">");
	document.writeln("<table class=\"full_width\" cellspacing=\"0\" cellpadding=\"0\">");
	document.writeln("<tr>");
}

function CreateBannerII()
{
	document.writeln("<td class=\"header_middle\">&nbsp;</td>");
	document.writeln("</tr>");
	document.writeln("<tr>");
	document.writeln("<td colspan=2 class=\"navbar\">&nbsp;</td>");
	document.writeln("</tr>");
	document.writeln("</table></td></tr>");
}

function AddHeaderAndBar()
{
	CreateBannerI();
	document.writeln("<td class=\"header_left\">&nbsp;</td>");
	CreateBannerII();	
}

function AddHeaderAndBarForTM()
{
	CreateBannerI();
	document.writeln("<td class=\"header_left_for_tm\">&nbsp;</td>");
	CreateBannerII();	
}

function AddHeaderAndBarForCitrix()
{
	CreateBannerI();
	document.writeln("<td class=\"header_ctx\">&nbsp;</td>");
	CreateBannerII();	
}

function AddFooter()
{
    document.writeln('<tr class="full_width_height">');
	document.writeln('<td valign="bottom">');
	document.writeln('<table class="full_width" cellspacing="0" cellpadding="0">');
	document.writeln('<tr>');
	document.writeln('<td class="watermark">');
	document.writeln('</td>');
	document.writeln('</tr>');
	document.writeln('</table>');
	document.writeln('</td>');
	document.writeln('</tr>');	
}

function AddBanner()
{
	document.writeln("<TABLE  cellSpacing=0 cellPadding=0 width=\"100%\" border=0>");
    document.writeln("<TBODY>");
    document.writeln("<TR height=94>");
    document.writeln("<TD style=\"BACKGROUND-IMAGE: url(/vpn/images/ctxBanner01.gif); BACKGROUND-REPEAT: no-repeat\" width=701></TD>");
    document.writeln("<TD style=\"BACKGROUND-IMAGE: url(/vpn/images/ctxBanner02.gif); BACKGROUND-REPEAT: repeat-x\">&nbsp;</TD>");
    document.writeln("</TR>");
    document.writeln("<TR>");
    document.writeln("<TD style=\"FONT-SIZE: 1px; HEIGHT: 8px; BACKGROUND-COLOR: white\" vAlign=bottom></TD>");
    document.writeln("</TR></TBODY></TABLE>");
}

var suitable_browser_to_use_png = false;
    
function canShowPNGWell()
{
	//As all morden browsers support PNG alfa transperancy by default this function will return true
	//except for IE 6 and lower, as IE 7 was first browser of IE series to support it
    var usrAgt = navigator.userAgent.toLowerCase();
	var msieOld = /(msie) [1-6]\.+/;
	if(msieOld.test(usrAgt)) {
		return false;
	}
	return true;
}

suitable_browser_to_use_png = canShowPNGWell();

function documentWriteGlowBoxUpper()
{
    if (suitable_browser_to_use_png == true)
        {
            document.write('<table class="CTXMSAM_LogonFont" cellpadding="0" cellspacing="0" align="center" border="0">\r\n');
            document.write('<tr>\r\n');
            document.write('<td class="glowBoxTop glowBoxLeft glowBoxTopLeftPng"></td>\r\n');
            document.write('<td class="glowBoxTop glowBoxTopMidPng"></td>\r\n');
            document.write('<td class="glowBoxTop glowBoxRight glowBoxTopRightPng"></td>\r\n');
            document.write('</tr>\r\n');
            document.write('<tr>\r\n');
            document.write('<td class="glowBoxLeft glowBoxMidLeftPng"></td>\r\n');
            document.write('<td class="glowBoxMidPng loginTableMidWidth">\r\n');
        }
        else
        {
            document.write('<table class="CTXMSAM_LogonFont" cellpadding="0" cellspacing="0" align="center" border="0">\r\n');
            document.write('<tr>\r\n');
            document.write('<td class="glowBoxTop glowBoxLeft glowBoxTopLeft"></td>\r\n');
            document.write('<td class="glowBoxTop glowBoxTopMid"></td>\r\n');
            document.write('<td class="glowBoxTop glowBoxRight glowBoxTopRight"></td>\r\n');
            document.write('</tr>\r\n');
            document.write('<tr>\r\n');
            document.write('<td class="glowBoxLeft glowBoxMidLeft"></td>\r\n');
            document.write('<td class="glowBoxMid loginTableMidWidth">\r\n');
        }
}

function documentWriteGlowBoxLower()
{
    if (suitable_browser_to_use_png == true)
        {
        	document.write('</td>');
            document.write('<td class="glowBoxRight glowBoxMidRightPng"></td>\r\n');
            document.write('</tr>\r\n');
            document.write('<tr>\r\n');
            document.write('<td class="glowBoxFooter glowBoxLeft glowBoxFooterLeftPng"></td>\r\n');
            document.write('<td class="glowBoxFooter glowBoxFooterMidPng"></td>\r\n');
            document.write('<td class="glowBoxFooter glowBoxRight glowBoxFooterRightPng"></td>\r\n');
			document.write('</tr>\r\n');
			document.write('</table>\r\n');
        }
        else
        {
        	document.write('</td>');
            document.write('<td class="glowBoxRight glowBoxMidRight"></td>\r\n');
            document.write('</tr>\r\n');
            document.write('<tr>\r\n');
            document.write('<td class="glowBoxFooter glowBoxLeft glowBoxFooterLeft"></td>\r\n');
            document.write('<td class="glowBoxFooter glowBoxFooterMid"></td>\r\n');
            document.write('<td class="glowBoxFooter glowBoxRight glowBoxFooterRight"></td>\r\n');
			document.write('</tr>\r\n');
			document.write('</table>\r\n');
        }
}

function documentWriteActionPane()
{
	if (suitable_browser_to_use_png == true)
		{
			document.write('<div class="actionPanePng">\r\n');
		}
		else
		{
			document.write('<div class="actionPane">\r\n');
		}
}

function DialogueBodyTop()
{
	CreateMainTable();
	AddHeaderAndBarForCitrix();
	document.writeln('<tr class="mainPane">');
	document.writeln('<td class="carbonBoxBottom" valign="bottom">');
	documentWriteGlowBoxUpper();
	document.writeln('<table cellspacing="4" cellpadding="0" border="0" width=100%>');
	document.writeln('<tr><td class="CTX_ContentTitleHeader"><div id="dialogueHeader"></div></td></tr>');
	document.writeln('<tr><td class="CTXMSAM_LogonFont"><div id="dialogueInfo"></div></td></tr>');
	document.writeln('<tr><td valign="top"><div id=content>');
}

function DialogueBodyBottom()
{
	document.writeln('</div></td></tr></table>');
	documentWriteGlowBoxLower();
	document.writeln('</td></tr>');
	AddFooter();
	document.writeln('</table>');

	//change maxLength for new password field to 127, to be compatible with LDAP
	var dlgStr = document.getElementById("dialogueStr").innerHTML;
	dlgStr = dlgStr.toLowerCase();
	if ((dlgStr.indexOf("password expired") > -1) && (dlgStr.indexOf("new password") > -1)) {
		document.getElementById("response").maxLength = 127;
		var localizedStr = _("DIALOGUE_HTML::dialoguePasswordChange");
		if (localizedStr && localizedStr.length > 0) {
			document.getElementById("dialogueStr").innerHTML = localizedStr;
		}
	}

	if (dlgStr.indexOf("confirm password") > -1) {
		document.getElementById("response").maxLength = 127;
		var localizedStr = _("DIALOGUE_HTML::dialogueConfirmPassword");
		if (localizedStr && localizedStr.length > 0) {
			document.getElementById("dialogueStr").innerHTML = localizedStr;
		}
	}

}

function DialogInclude()
{
	document.writeln('<link href="/vpn/css/ctxs.mainstyle.css" rel="stylesheet" type="text/css" media="screen" />');
	document.writeln('<link href="/vpn/css/ctxs.authentication.css" rel="stylesheet" type="text/css" />');
}

function DialogueBodyI()
{
	var ln = "";
	ln += '<div id="authentication">';
	ln += '<div id="logonbelt-topshadow"></div>';
	ln += '<div id="logonbox-container">';
	ln += '<div id="logonbox-innerbox">';
	ln += '<div id="logonbox-logoarea">';
	ln += '<div id="logonbox-logoimage">';
    ln += '</div></div>';
	ln += '<div id="logonbox-logonform">';
	ln += '<table cellspacing="4" cellpadding="0" border="0" width=100%>';
	ln += '<tr><td class="label plain CTX_ContentTitleHeader"><div id="dialogueHeader"></div></td></tr>';
	ln += '<tr><td class="label plain CTXMSAM_LogonFont"><div id="dialogueInfo"></div></td></tr>';
	ln += '<tr><td valign="top"><div id=content>';
	ln += '<FORM class="dialogueForm" METHOD=POST ACTION="/cgi/dlge" NAME="dlgform">';
	ln += '<table id="dialogueTable" class="CTXMSAM_LogonFont">';
	ln += '<tr><td>&nbsp;</td></tr>';
	ln += '<tr><td class="label plain dialogueChallengeCell">';
	document.writeln( ln );
}

function DialogueBodyII()
{
	var ln = "";
	ln += '</td></tr>';
	ln += '<tr><td class="dialogueResponseCell" style="float:left"><input size="35" maxlength="256" id="response" NAME=response TYPE=password tabindex="1"/></td></tr>';
	ln += '<tr><td></td></tr>';
	ln += '<tr><td class="dialogueSubmitCell" style="float:left">';
	ln += '<input id="SubmitButton" type="SUBMIT" value="Submit" tabindex="2" class="custombutton"/>';
	ln += '</td></tr></table>';
	ln += '</FORM>';
	ln += '</div></td></tr></table>';
	ln += '</div></div></div>';
	ln += '<div id="logonbelt-bottomshadow">';
	ln += '</div></div>';
	document.writeln( ln );
	//change maxLength for new password field to 127, to be compatible with LDAP
	var dlgStr = document.getElementById("dialogueStr").innerHTML;
	dlgStr = dlgStr.toLowerCase();
	if ((dlgStr.indexOf("password expired") > -1) && (dlgStr.indexOf("new password") > -1)) {
		document.getElementById("response").maxLength = 127;
		var localizedStr = _("DIALOGUE_HTML::dialoguePasswordChange");
		if (localizedStr && localizedStr.length > 0) {
			document.getElementById("dialogueStr").innerHTML = localizedStr;
		}
	}

	if (dlgStr.indexOf("confirm password") > -1) {
		document.getElementById("response").maxLength = 127;
		var localizedStr = _("DIALOGUE_HTML::dialogueConfirmPassword");
		if (localizedStr && localizedStr.length > 0) {
			document.getElementById("dialogueStr").innerHTML = localizedStr;
		}
	}
}

 function TransferInclude()
{
	document.writeln('<link href="/vpn/css/ctxs.mainstyle.css" rel="stylesheet" type="text/css" media="screen" />');
	document.writeln('<link href="/vpn/css/ctxs.authentication.css" rel="stylesheet" type="text/css" />');
	document.writeln('<link href="/vpn/css/ctxs.ui.messagebox.css" rel="stylesheet" type="text/css" />');

}

function TransferOnesessBodyI()
{

var ln;
ln='<div class="topBar"><div class="left_logo"></div></div>';
ln+='<div id="messagebox">';
ln+='<div id="messagebox" class="mainpane" style="top: 205px; position: relative; display: block; "><div class="ctxsui-messagebox" style="margin-left: auto; margin-right: auto; "><div class="messagebox-top"><div class="messagebox-topleft"></div><div class="messagebox-topbody"></div><div class="messagebox-topright"></div></div><div class="messagebox-middle"><div class="messagebox-middleleft"></div><div class="messagebox-middlebody">';
ln+= '<div id="content" class="warning ">';
ln+= '<div id="heading" class="CTX_ContentTitleHeader">Transfer Logon</div>';
ln+=  '<div id="description" class="messageStyle">';
ln+=	'<BR><span id="You are currently logged on to the Access Gateway on another device."></span>';
ln+=	'<BR><span id="Would you like to end that session?"></span><BR>';
ln+=	'</div>';
ln+=	 '<form class="dialogueForm" action="/cgi/tlogin" method="POST">';
ln+=	'<table>';
document.writeln(ln);
}



function TransferMultsessBodyI()
{

var ln;
ln ='<div class="topBar"><div class="left_logo"></div></div>';
ln+='<div id="messagebox"  >';
ln+='<div id="messagebox" class="mainpane" style="top: 205px; position: relative; display: block; "><div class="ctxsui-messagebox" style="margin-left: auto; margin-right: auto; "><div class="messagebox-top"><div class="messagebox-topleft"></div><div class="messagebox-topbody"></div><div class="messagebox-topright"></div></div><div class="messagebox-middle"><div class="messagebox-middleleft"></div><div class="messagebox-middlebody">';
ln+='<div id="content" class="warning">';
ln+='<div id="heading" class="CTX_ContentTitleHeader">Transfer Logon</div>';
ln+='<div id="description" class="messageStyle">';
ln+='<BR><span id="You have reached the limit of the allowed Access Gateway sessions."></span>';
ln+='<BR><span id="Please select which session to release in order to continue with this session."></span><BR><BR></div>';
ln+='<form class="dialogueForm" action="/cgi/tlogin" method="POST">';
ln+='<table class="CTXMSAM_LogonFont">';
ln+='<tr><th></th><th><span id="Intranet IP Address"></span>&nbsp;&nbsp;</th><tab><th><span id="Idle Time"></span></th></tr>';
document.writeln(ln);

}

function TransferBodyII()
{
var ln;
ln='<tr><td colspan=3><BR></td></tr>';
ln+='</table>';
ln+='<div class="transferLogonButtonRow">';
ln+='<input type="submit" id="TransferButton" value="Transfer" name="cm" class="custombutton"/>';
ln+='<input type="submit" id="CancelButton" value="Cancel" name="cm"class="custombutton"/>';
ln+='</div></form></div></div>';
ln+='<div class="messagebox-middleright"></div></div><div class="messagebox-bottom"><div class="messagebox-bottomleft"></div><div class="messagebox-bottombody"></div><div class="messagebox-bottomright"></div></div></div></div>';
ln+='</div>';

document.writeln(ln);



}





/* --------------  Helper functions start -------------------- */

var util = { }		//Generic Util namespace

util.win = {}		// This namespace should be used for all Windows only util functions


//use this function instead IE check
util.win.ACTIVEX_SUPPORTED = ("ActiveXObject" in window);		


var winAgentPortForLauncher = 3198;

//function which does actual probing
util.prob = function (requestString, successCallback, errorCallback)
{
	image = new Image();
	image.onload = 	successCallback; 
	image.onerror = errorCallback; 
	image.src = "http://localhost:" + winAgentPortForLauncher + "/" + requestString + "&" + Math.random();
}

//will return empty string on any error
util.getStringFromLauncher =  function(requestName, callBack)
{
	var index = 0;
	var hexResult = [];
	
	var probeError 	= function () { callBack("") }
	
	var probSuccess =  function ()   
	{ 
		hexResult.push(this.height -1); 
		
		if(hexResult.length > 2)
		{
			if( (hexResult[hexResult.length - 1] == 0) &&  (hexResult[hexResult.length - 2] == 0) )		//found whole string
			{
				var endResult = "";
				for (var i = 0; i < hexResult.length-2; i += 2)
				{
					endResult += String.fromCharCode(hexResult[i]*16 + hexResult[i+1]);
				}
				
				callBack(endResult);
				return;
			}
		}
		
		index+=1;
		util.prob(requestName + "&"+ index, probSuccess, probeError);
	} 

	util.prob(requestName + "&"+ index, probSuccess, probeError);
}

util.maxAttempt 	= 20;
util.currentAttempt	= 0;

util._launchErrorHandler 	= util.emptyFunction;
util._launchSuccessHandler 	= util.emptyFunction;


util._checkIfLaunched =  function()
{
	util.currentAttempt+=1;
	if(util.currentAttempt > util.maxAttempt)
	{
		util._launchErrorHandler();
		return;
	}
	
	function errorhandler()
	{
		window.setTimeout(util._checkIfLaunched, 1000);
	}
	util.prob("NOP", util._launchSuccessHandler, errorhandler);
}

util.launchPlugin =  function(successCallback, errorCallback)
{
	window.location = "citrixNG://";
	util._launchErrorHandler 	= errorCallback;
	util._launchSuccessHandler 	= successCallback;
	window.setTimeout(util._checkIfLaunched(), 2000);		// give little time (2s) to start the plugin
}

util.useLauncherForChrome =  true;		//if set to false. Chrome will use NPAPI by default.
util.useLauncher = ( (util.useLauncherForChrome) && (window.chrome) );

util.winMaxTimerValue 		=  10;


/* --------------  Helper functions end -------------------- */
