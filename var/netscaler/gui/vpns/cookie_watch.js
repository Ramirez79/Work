(function() {


var NS_CC = new Array();
var NameOfCookie = "NSC_AAAC";
var TempCookie = "NSC_TEMP";
var omit = { NSC_USER: 1 , NSC_AAAC: 1 , citrix_ns_id: 1 , NSC_TEMP: 1 };

var aaac;
aaac = ns_getcookie(NameOfCookie);
var tempc = ns_getcookie(TempCookie);
if (aaac != null) {
	if (location.protocol == "https:" || location.port == 8080)
		document.cookie = NameOfCookie+"="+escape(aaac)+";path=/" + ";secure";
	else
		document.cookie = NameOfCookie+"="+escape(aaac)+";path=/";
}

var timer_id;

if (tempc != null) {
	var res;
	if (tempc == 'PROMPT') {
		document.cookie = "NSC_TEMP=PROMPTED;path=/;";
		res = confirm("Do you want to allow persistent cookie");
	}
	if (res == false) {
		send_tc_request("DENY");
	} else if ((tempc == 'START')||(res == true)) {
		send_tc_request("ACCEPT");
		timer_id = setInterval(function(){
				send_tc_request("ACCEPT");
				},120000);
	} else if (tempc != 'PROMPT' && tempc != 'PROMPTED'){
		var expiry = new Date();
		expiry.setMinutes(expiry.getMinutes() + 5);
		document.cookie = TempCookie+"="+escape(tempc)+";path=/"+";expires="+expiry.toGMTString();
	}
} else {
	clearInterval(timer_id);
}
function send_tc_request(body)
{
	var req;
	if (window.XMLHttpRequest) {
		req = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		req = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		return false;
        }
	req.onreadystatechange = function() {
		if (req.readyState == 4) {
			if (req.status != 200) {
				clearInterval(timer_id);
        			return;
        		}
        	}
        };
	req.open("POST", "/cvpn/tc", true);
	req.send(body);
}

function ns_getcookie(NameOfCookie)
{
        var cookie = document.cookie;
        var begin = cookie.indexOf(NameOfCookie + "=");
        if(begin != -1) {
                begin += NameOfCookie.length + 1;
                end = cookie.indexOf(";", begin);
                if(end == -1) 
			end = cookie.length;
                return unescape(cookie.substring(begin, end));
        }
        return null;
}

if (document.watch) {
	document.watch('cookie',function (id,ov,nv) {
	var $r = new XMLHttpRequest();
	$r.open("POST","/cvpn/cp",false);
	$r.send(nv);
	return nv;
    });

} else {
	function CC (n,v) {
		this.name = n.replace(/\s+/g,'');
		this.value = v.replace(/\s+/g,'');
	}
	function make_cc_arr () {
		var r = new Array();
		var cc = document.cookie.split(';');
		for (var i = 0; i < cc.length; i++ ) {
			var l = cc[i].indexOf('=');
			var newcc = new CC(cc[i].slice(0,l),cc[i].slice(l+1));
			if (newcc.name.length && newcc.value.length)
				r.push(newcc);
		}
		return r;
	}
	function arr_idxof (a,n) {
		for ( var j = 0; j < a.length; j++) {
			if (a[j].name == n ){
				return j;
			}
		}
		return -1;
	}

	function get_req () {
		var xml;
		try {
			xml = new ActiveXObject("Msxml2.XMLHTTP");
		} catch(e) {
			try {
				xml = new ActiveXObject("Microsoft.XMLHTTP");	
			} catch(e) {
				try {
					xml = new XMLHttpRequest();
				} catch(e) {
					xml = false;
				}
			}
		}
		return xml;
	}

	function check_cc(ARR) {
		var AN = make_cc_arr();
		NS_CC = ARR;
		for (var i =0; i < AN.length ; i++) {
			var idx = arr_idxof(NS_CC,AN[i].name);
			if (typeof(omit[AN[i].name]) === 'undefined') {
				if ( idx == -1 ) {
					var r = get_req();
					if (r) {
						r.open("POST","/cvpn/cp",false);
						r.send(AN[i].name + "=" + AN[i].value);
					}
				} else if (NS_CC[idx].value != AN[i].value) {
					var r = get_req();
					if (r) {
						r.open("POST","/cvpn/cp",false);
						r.send(AN[i].name + "=" + AN[i].value);
					}
				}
			}
			if ( idx != -1 ) {
				NS_CC.splice(idx,1);
			}
		}
		var d = new Date();
		d.setFullYear(1980);
		for (var i =0 ; i < NS_CC.length; i++) {
			if (typeof(omit[NS_CC[i].name] === 'undefined')) {
				var r = get_req();
				if (r) {
					r.open("POST","/cvpn/cp",false);
					r.send(NS_CC[i].name + "=" + NS_CC[i].value + "; expires=" + d.toGMTString());
				}
			}
		}
		return AN;
	}

	NS_CC = make_cc_arr();

	if (document.attachEvent && !document.opera) {
		document.attachEvent('onpropertychange', function () {
			if (event.propertyName == 'cookie') {
				NS_CC = check_cc(NS_CC);
			}
		});
	} else {
		for ( var i  = 0; i < NS_CC.length; i++ ) {
			if ( NS_CC[i].name == "WIClientInfo") {
				NS_CC[i].value = "empty";
			}
		}
		setInterval(function(){ NS_CC = check_cc(NS_CC) },500);
		if(((navigator.userAgent.indexOf("Chrome") ==-1)  && (navigator.userAgent.indexOf("AppleWebKit") ==-1) && (navigator.vendor.indexOf("Google Inc.") ==-1)) || ((location.href.indexOf("owa") == -1) && (location.href.indexOf("ecp") == -1)))
		{
			window.addEventListener("unload",function() { NS_CC = check_cc(NS_CC) },false);
		}

	}
}

})();
