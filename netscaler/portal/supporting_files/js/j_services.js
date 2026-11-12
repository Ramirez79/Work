var ispluginrunning=0;
var NameOfClientCookie="NSC_PLUGINC";

function ns_checksystem()
{
   if(document.cookie.length > 0) {
	begin=document.cookie.indexOf(NameOfClientCookie+"=");
	if(begin!=-1) {
		ispluginrunning=1;
		return; 
	}
   }
}

function ns_loadsessionwindow()
{
ns_checksystem();
if(ispluginrunning!=0) return;
var winx=screen.width-350; 
var winy=screen.height-175-60;
window.open("/vpns/vpn.html", "ns_sslvpn_java", "toolbar=no,menubar=no,status=no,width=350,height=175,resizable=no,left="+winx+",top="+winy);
ispluginrunning=1;
}
