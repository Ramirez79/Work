rdx.vpn_process_linux_view = function(parent, view_definition, resource, base_view)
{   
    this._base_view = base_view;
    this._parent = parent;
        
    this._DEFAULT_PROPERTIES =
    {
    };
    
};

rdx.vpn_process_linux_view.prototype.make_view = function()
{
	this._base_view.set_crumb_handler("") ;
	var commonbox = $("<div></div>").addClass("plain");
	var title = $("<div></div>").attr({'id':'NetScaler Gateway',"class":"CTX_ContentTitleHeader"});
	var waitingmsg = $("<div></div>").attr('id','waitingmsg');
	var proxy_msg1= $("<span></span>").attr('id','If a proxy server is configured');
	var proxy_msg2 = $("<span></span>").attr('id','For more information contact your help desk or system administrator.');
	var downloadArea = $("<div></div>").attr({'id':'downloadArea',"style":"display:none"});
	var downloadlink = $("<a></a>").attr({"id":"downloadlink","href":"#"}).append($('<input type="button"></input>').attr({"class":"custombutton",'id':'Download'}));
	var downloadmsgBox = $("<div></div>").attr("id","downloadmsgBox");
	var msgtitle = $("<span></span>").attr("id","To install the Netscaler Access Gateway Client for Linux");
	var msg1 = $("<p></p>").append($("<span></span>").attr("id","Click Download"));
	var msg2 = $("<p></p>").append($("<span></span>").attr("id","Linux install using software center"));
	downloadmsgBox.append(msgtitle,msg1,msg2);
	downloadArea.append(downloadlink,downloadmsgBox);
	
	var scriptarea = $("<div></div>").attr({"id":"scriptArea","display":"none"});
	commonbox.append(title,"<br>",waitingmsg,"<br>",proxy_msg1,"<br>",proxy_msg2,downloadArea,scriptarea);
	this._parent.append(commonbox);
	Resources.Load();
	checkIfAgentReady(); //Call the function to invoke Linux Plugin
		
};

rdx.vpn_process_linux_view.prototype.get_callback_data = function()
{
	return {};  
};