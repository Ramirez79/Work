var loginPrefilled = false;

function UnsetCookie(cookieName) {
document.cookie=cookieName+"=nothing;expires=Thursday, 1 Jan 1970 00:00:00 GMT; path=/";
}

function ns_check()
{
	var login = document.forms['vpnForm'].login.value;
	var passwd = document.forms['vpnForm'].passwd.value;
	if(login == "") {
		window.alert(_("You need to enter login name")); return false; 
	}
	if (passwd == "") {
		window.alert(_("You need to enter passwd")); return false; 
	}
	return true;
}
function ns_disperrmsg()
{
   var vpnerrCookieVal = ns_getcookie("NSC_VPNERR");
   if( !(isNaN(vpnerrCookieVal)) && (vpnerrCookieVal >= "4001") && (vpnerrCookieVal <= _("errorMessageLabelMax"))) {
	document.writeln('<div class="field CredentialTypenone"><div class="left"><div class="label error" id="feedbackStyle">');
	document.writeln('<span id="errorMessageLabel">');

	document.writeln(_(_("errorMessageLabelBase") + vpnerrCookieVal));

	document.writeln('</span></div></div></div>');
	UnsetCookie("NSC_VPNERR");
	return;
   }
   document.writeln("&nbsp;");
}

UnsetCookie("NSC_USER");
function ns_getcookie(name)
{
   var cookie=document.cookie;
   if(cookie.length > 0) {
        begin=cookie.indexOf(name+"=");
        if(begin!=-1) {
                begin+=name.length+1;
                end=cookie.indexOf(";", begin);
                if(end==-1) end=cookie.length;
                return decodeURIComponent(cookie.substring(begin, end));
        }
   }
   return null;
}
function ns_fillName()
{
   var value=ns_getcookie("NSC_NAME");

   document.vpnForm.login.focus();
   if(value!=null) {
	document.vpnForm.login.value = value;
	loginPrefilled = true;
	document.vpnForm.login.style.color = "#777";
	document.vpnForm.passwd.focus();
   }
}
function loginFieldCheck()
{
	if(loginPrefilled == true)
	{
		document.vpnForm.login.blur();
	}
}

function clean_name_cookie()
{
	UnsetCookie("NSC_NAME");
}

function ns_showpwd_default()
{
	var pwc = ns_getcookie("pwcount");
	document.write('<TR><TD align=right style="padding-right:10px;white-space:nowrap;"><SPAN class=CTXMSAM_LogonFont>' + _("Password"));
	if ( pwc == 2 ) { document.write('&nbsp;1'); }
	document.write(':</SPAN></TD>');
	document.write('<TD colspan=2 style="padding-right:8px;"><input class=CTXMSAM_ContentFont type="Password" title="' + _("Enter password") + '" name="passwd" id="passwd" size="30" maxlength="127" style="width:100%;"></TD></TR>');
	if ( pwc == 2 ) {
	document.write('<TR><TD align=right style="padding-right:10px;white-space:nowrap;"><SPAN class=CTXMSAM_LogonFont>' + _("Password2") + '</SPAN></TD> <TD colspan=2 style="padding-right:8px;"><input class=CTXMSAM_ContentFont type="Password" title="' + _("Enter password") + '" name="passwd1" id="passwd1" size="30" maxlength="127" style="width:100%;"></TD></TR>');
	}
	UnsetCookie("pwcount");
}


function ns_showpwd_greenbubble()
{
	var pwc = ns_getcookie("pwcount");
	document.write('<div class="field CredentialTypepassword"><div class="left"><label class="label plain"><SPAN>' + _("Password"));
	if ( pwc == 2 ) { document.write('&nbsp;1'); }
	document.write(':</SPAN></label></div>');
	document.write('<div class="right"><input class="prePopulatedCredential"  autocomplete="off" spellcheck="false" type="Password" title="' + _("Enter password") + '" name="passwd" id="passwd" size="30" maxlength="127"></div></div>');
	if ( pwc == 2 ) {
	document.write('<div class="field CredentialTypepassword"><div class="left"><label class="label plain"><SPAN>' + _("Password2") + '</SPAN></label></div><div class="right"><input class="prePopulatedCredential"  autocomplete="off" spellcheck="false" type="Password" title="' + _("Enter password") + '" name="passwd1" id="passwd1" size="30" maxlength="127"></div></div>');
	}
	UnsetCookie("pwcount");
}
