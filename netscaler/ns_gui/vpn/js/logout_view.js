rdx.logout_view = function(parent, view_definition, resource, base_view)
{   
    this._base_view = base_view;
    this._parent = parent;
        
    this._DEFAULT_PROPERTIES =
    {
    };
    //this._properties = $.extend({}, this._DEFAULT_PROPERTIES, view_definition.view_properties);
    //this._form_controller = new rdx.form_controller(base_view, parent, true);
};

rdx.logout_view.prototype.make_view = function()
{
	this._base_view.set_crumb_handler("") ;
	//var header = $("<div></div>").attr("id","header");
	//start header code
	var header_row1= $("<tr></tr>").attr("id","row1").append($("<td></td>").attr("class","header_left"));
	var header_row2 = $("<tr></tr>").attr("id","row2").append($("<td></td>").attr({"colspan":"2","class":"navbar"}));
	var header_table = $("<table></table>").attr("class","full_width").append(header_row1,header_row2);
	var header_top= $("<div></div>").attr('class','header_top');
	header_top.append(header_table);
	//end header markup for default theme
	var header= $('<div class="header"></div>').append($('<div class="branding"></div>').append($('<div class="custom_logo"></div>')));   //header markup for greenbubbletheme 
	
	var LinuxPluginObj = $('<object type="application/x-vpnd"></object>').attr({"id":"pluginobj","style":"display:none"}); 
	
	var messagebox = $("<div></div>").attr({'id':'messagebox','class':'','style':'top:205px; position:relative; display: block;'});
	var msg_innerbox = $("<div></div>").attr({"class":"ctxsui-messagebox","style":"margin-left: auto; margin-right: auto;"});
	var msgbox_middle = $("<div></div>").addClass("messagebox-middle");
	var msgbox_middlebody = $("<div></div>").addClass("messagebox-middlebody");
	var headermessage = $("<div></div>").append($('<h1 class="headermessage"></h1>').append($('<span id="Logoff is successful."></span>')));
 	var Logoffbutton = $("<center></center>").append($("<div></div>").append($('<input type="button" id="Log_On" value="Log_On" class="custombutton"></input>').click(function() {GoToLogonPage();})));
	messagebox.append(msg_innerbox.append(msgbox_middle.append(msgbox_middlebody.append(headermessage,Logoffbutton))));
	
	var LogOffForm = $("<form></form>").attr({"name":"pageForm","method":"post","action":"/","id":"pageForm"});
	var path = window.location.pathname.split('/')
	if (path[path.length-1] == "tmlogout.html"){
	LogOffForm.append(header_top,header,messagebox);
	}
	else{
	LogOffForm.append(LinuxPluginObj,header_top,header,messagebox);
	}
	var BlockerMessage = $("<div></div>").attr({"id":"popupBlockerMessage","class":"NUI_PopupBlockerMessage","style":"display:none; height:auto;"});
	//logonbox_innerbox.append(logonbox_logoarea,logonbox_logonform);
	
	this._parent.append(BlockerMessage,LogOffForm);
	Resources.Load();
};

rdx.logout_view.prototype.get_callback_data = function()
{
    return {};  
};