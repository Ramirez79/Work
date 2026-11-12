    var _info = navigator.userAgent;
	var pluginURL = "https://www.java.com/";

    var _ie = (_info.indexOf("MSIE") > 0 && _info.indexOf("Win") > 0);
    var _ns = (navigator.appName.indexOf("Netscape") >= 0);
    var _firefox = (_ns && navigator.userAgent.indexOf("Firefox") >= 0);
    var _chrome_safari = (_ns && (navigator.userAgent.indexOf("Chrome") >= 0 || navigator.userAgent.indexOf("Safari") >= 0));
    var jre_on_mozilla = (navigator.mimeTypes && navigator.mimeTypes['application/x-java-applet;version=1.6']);
	var _is_ubuntu = (navigator.userAgent.indexOf("Linux") >= 0);
	var classid = "8AD9C840-044E-11D1-B3E9-00805F499D93";
    var _ie8 = false;
    var _ie10 = false;
    if(new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})").exec(navigator.userAgent) != null)
    {
        var version = parseFloat( RegExp.$1 );
        if(version >= 8 && version < 9)
            _ie8 = true;
        if(parseInt(version) == 10)
            _ie10 = true;
    }
    
	if (navigator.appVersion.indexOf("Win") != -1 && _ie)
	{
	    pluginURL = "https://java.sun.com/update/1.6.0/jinstall-6u31-windows-i586.cab";
	}