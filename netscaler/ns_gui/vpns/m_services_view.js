rdx.vpn_process_mac_view = function(parent, view_definition, resource, base_view)
{
    this._base_view = base_view;
    this._parent = parent;

    this._DEFAULT_PROPERTIES =
    {
    };

};

rdx.vpn_process_mac_view.prototype.make_view = function()
{
	this._base_view.set_crumb_handler("") ;

	var commonbox = $("<div></div>").addClass("plain");
	var title = $("<div></div>").attr({'id':'NetScaler Gateway',"class":"CTX_ContentTitleHeader"});
	var plugin_installed = $("<p></p>").append($("<span>").attr('id','If the Plug-in is installed'));
  var waitingmsg = $("<div></div>").attr('id','Please wait for the VPN session to be established.')
	var plugin_not_installed = $("<div></div>").attr({'id':'If the Access Gateway Plug-in is not installed',"style":"display:none"});
	var proxy_msg1= $("<span></span>").attr('id','If a proxy server is configured');
	var proxy_msg2 = $("<span></span>").attr('id','For more information contact your help desk or system administrator.');
	var downloadArea = $("<div></div>").attr({'id':'downloadArea',"style":"display:none"});
	var downloadlink = $("<a></a>").attr({"id":"pluginDonwloadlink","href":"/vpns/scripts/mac/Citrix_Access_Gateway.dmg"}).append($('<input type="button"></input>').attr({"class":"custombutton",'id':'Download'}));
  var timerInfo = $("<div></div>").attr({"id":"Checking if the plug-in is installed","style":"display:none"});
  var timerValue = $("<div></div>").attr("id","timerValue");
	var msgtitle = $("<span></span>").attr("id","To install the Access Gateway Plug-in for Mac OS X");
	var scriptArea = $('<div id="scriptArea"></div>');

	downloadArea.append(downloadlink);
	commonbox.append(title,"<br>",waitingmsg,plugin_not_installed,downloadArea,"<br>",proxy_msg1," ",proxy_msg2,"<br><br>",timerInfo,timerValue);
	this._parent.append(commonbox,scriptArea);
	Resources.Load();
	checkIfAgentReady(); //Call plugin once DOM loaded.
};
