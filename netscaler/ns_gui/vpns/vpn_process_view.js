
rdx.vpn_process_view = function(parent, view_definition, resource, base_view)
{   
    this._base_view = base_view;
    this._parent = parent;
        
    this._DEFAULT_PROPERTIES =
    {
    };
    
};

rdx.vpn_process_view.prototype.make_view = function()
{
	this._base_view.set_crumb_handler("") ;
	var commonbox = $("<div></div>").addClass("plain");
	var title = $("<div></div>").attr({'id':'Citrix_Access_Gateway',"class":"CTX_ContentTitleHeader"});
	var waitingmsg = $("<div></div>").attr('id','waitingmsg');
	var proxy_msg1= $("<span></span>").attr('id','If_a_proxy_server_is_configured');
	var proxy_msg2 = $("<span></span>").attr('id','For_more_information_contact_your_help_desk_or_system_administrator');
	var downloadArea = $("<div></div>").attr({'id':'downloadArea',"style":"display:none"});
	var downloadlink = $("<a></a>").attr({"id":"pluginDonwloadlink","href":"#"}).click(function(){DonwloadlinkClick();}).append($('<input type="button"></input>').attr({"class":"custombutton",'id':'Download'}));
	var downloadmsgBox = $("<div></div>").attr("id","downloadmsgBox");
	var msgtitle = $("<span></span>").attr("id","To_install_the_Access_Gateway_Plugin_for_Windows");
	var msg1 = $("<p></p>").append($("<span></span>").attr("id","Click_Download"));
	var msg2 = $("<p></p>").append($("<span></span>").attr("id","In_the_File_Download_dialog_box_click_Run"));
	var msg3 = $("<p></p>").append($("<span></span>").attr("id","In_the_Internet_Explorer_dialog_box_click_Run_to_install_the_Access_Gateway_Plugin_for_Windows"));
	var plugindiv = $("<div></div>").attr("id","plugindiv");
	downloadmsgBox.append(msgtitle,msg1,msg2,msg3);
	downloadArea.append(downloadlink,downloadmsgBox);
	commonbox.append(title,"<br>",waitingmsg,"<br>",proxy_msg1,"<br>",proxy_msg2,downloadArea,plugindiv);
	this._parent.append(commonbox);
	Resources.Load();
	setTimeout(function() { detectAgent();}, 3000);
		
};

rdx.vpn_process_view.prototype.get_callback_data = function()
{
	return {};  
};