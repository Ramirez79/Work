var nsloc=document.location.href;
var cookie=document.cookie;
var ns_aaac="";
var NameOfCookie="NSC_AAAC";
var nsyes=false;

if(cookie.length > 0) {
   begin=cookie.indexOf(NameOfCookie+"=");
   if(begin!=-1) {
        begin+=NameOfCookie.length+1;
        end=cookie.indexOf(";", begin);
        if(end==-1) end=cookie.length;
        ns_aaac=unescape(cookie.substring(begin, end));
   }
}

function SetCookie(cookieName,cookieValue) {
	var today = new Date();
	var expire = new Date();
	expire.setTime(today.getTime() + 3600000*24);
	document.cookie = cookieName+"="+escape(cookieValue)+";path=/;expires="+expire.toGMTString();
}

function UnsetCookie(cookieName) {
document.cookie=cookieName+"=nothing;expires=Thursday, 1 Jan 1970 00:00:00 GMT; path=/";
}

function success(evt){
	nsyes=true;
	SetCookie(NameOfCookie, ns_aaac);
	window.opener=top;
	window.close();
}

function nsprocess(){
for (var port = 3128; port < 3138; port++){
	var test = new Image();
	test.onload = success;
	test.src = "http://localhost:"+port+"/svc?NSC_AAAC="+ns_aaac+"&nsloc="+nsloc+"&nsversion=6,1,59,0&nstrace=EVENT&nsvip=255.255.255.255&nstdi=ON&nsspoofip=ON";
}
}

function nsexit(){
	if(nsyes==false) UnsetCookie(NameOfCookie);
}
