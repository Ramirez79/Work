rdx.services_view = function(parent, view_definition, resource, base_view)
{   
    this._base_view = base_view;
    this._parent = parent;
        
    this._DEFAULT_PROPERTIES =
    {
    };
    
};

rdx.services_view.prototype.make_view = function()
{
	this._base_view.set_crumb_handler("") ;
	var commonbox = $("<div></div>").addClass("plain");
	var title = $("<div></div>").attr({'id':'NetScaler Gateway',"class":"CTX_ContentTitleHeader"});
	commonbox.append(title,"<br><br");
	var waitingmsg = $("<div></div>").attr('id','waitingmsg');
	var proxy_msg1= $("<span></span>").attr('id','If a proxy server is configured');
	var proxy_msg2 = $("<span></span>").attr('id','For more information contact your help desk or system administrator.');
	var proxy_msg3 = $("<span></span>").attr('id','For help or information, contact your help desk or system administrator.');
	var plugin_notsupported_msg = $("<span></span>").attr('id','The Access Gateway Plug-in is not supported on your operating system.');
	if( isXpOrHigher() ){
	commonbox.append(waitingmsg,"<br><br>",proxy_msg1,"<br>",proxy_msg2);
	}
	else
	{commonbox.append(plugin_notsupported_msg,"<br><br>",proxy_msg3);}
	this._parent.append(commonbox);
	
	Resources.Load();
	probeAgent();
};

rdx.services_view.prototype.get_callback_data = function()
{
	return {};  
};
