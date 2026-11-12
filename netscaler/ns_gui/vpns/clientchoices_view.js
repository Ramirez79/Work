rdx.client_choices_view = function(parent, view_definition, resource, base_view)
{   
    this._base_view = base_view;
    this._parent = parent;
        
    this._DEFAULT_PROPERTIES =
    {
    };
    
};


rdx.client_choices_view.prototype.make_view = function()
{
	this._base_view.set_crumb_handler("");
	var logonboxContainer = $("<div></div>").attr({"id":"logonboxContainer","class":"plain"});
	//start header code
	var header_row1= $("<tr></tr>").attr("id","row1").append($("<td></td>").attr("class","header_left"));
	var header_row2 = $("<tr></tr>").attr("id","row2").append($("<td></td>").attr({"colspan":"2","class":"navbar"}));
	var header_table = $("<table></table>").attr("class","full_width").append(header_row1,header_row2);
	var header_top= $("<div></div>").attr('class','header_top').append(header_table);
	//end header markup for default theme
	
	var header= $('<div class="header"></div>').append($('<div class="branding"></div>').append($('<div class="custom_logo"></div>'))).appendTo(logonboxContainer);   //header markup for greenbubbletheme 
	var hello = $("<div></div>").attr({"class":"username"}).append($("<span></span>").attr("id","hello"),$("<span></span>").attr({"id":"username","style":"display:inline"}));
	var header_right = $("<div></div>").attr({"class":"header-right"}).append(hello,$("<span></span>").addClass("down-arrow")).appendTo(header);
	var settings = $("<div></div>").attr({"id":"settings","style":"display:none","class":"setting-dropdown"}).append($("<div></div>").attr({"id":"Log Off","class":"dropdown-choices"}).click(function(){ ns_cleancookie(); window.location.href ="/cgi/logout";}));
	header_right.append(settings);
	logonboxContainer.append(header_top, header);

	$('body').mouseover(function(e) {
    if ($(e.target).closest('.header-right').length === 1) {
       	$("#settings").show();
    }else
		$("#settings").hide();
	});
	
	function ns_cleancookie()
	{
	document.cookie="NSC_VPNERR=nothing;expires=Thursday, 1 Jan 1970 00:00:00 GMT; path=/";
	return true;
	}

	function ns_getvpnerrcookie(NameOfCookie)
	{
	   var cookie=document.cookie;
	   if(cookie.length > 0) {
			begin=cookie.indexOf(NameOfCookie);
			if(begin!=-1) {
					begin+=NameOfCookie.length+1;
					end=cookie.indexOf(";", begin);
					if(end==-1) end=cookie.length;
					return decodeURIComponent(cookie.substring(begin, end));
			}
	   }
	   return null;
	}

	function ns_fillchoices()
	{	
	 
	  var errcookie= ns_getvpnerrcookie("NSC_VPNERR");
	  var outerbox = $("<div></div>").attr({"id":"outerbox","class":"choices_bkgrnd"});
	  var choices_content =$("<div></div>").attr({"class":"content_choices"});
	  outerbox.append(choices_content);
	  var heading = $("<div></div>").attr({"id":"messageTitle","class":"heading"}).appendTo(choices_content);
	  var client_choices_container =$("<div></div>").attr({"class":"block-container clearfix"}).appendTo(choices_content);
	  
	  var choicediv = $("<div></div>").attr({"class":"box"});
	  var anchor = $("<a></a>").appendTo(choicediv);
	  var choice_content = $("<div></div>").attr({"class":"box-content"}).appendTo(anchor);
	  var choice_title = $("<div></div>").addClass("box-content-title");
	  var title_first_line = $("<p></p>").appendTo(choice_title);
	  var title_second_line = $("<p></p>").appendTo(choice_title);
	  var choice_icon = $("<div></div>").addClass("box-icon");
	  var icon_span = $("<span></span>").appendTo(choice_icon);
	  var choice_description = $("<div></div>").addClass("description");
	  choice_content.append(choice_title,choice_icon,choice_description);

	  var plugindiv=choicediv.clone();
	  var javadiv=choicediv.clone();
	  var agentdiv=choicediv.clone();
	  var cvpndiv=choicediv.clone();
	  var wicadiv=choicediv.clone();
	  var maccdiv=choicediv.clone();

	  var network_access = $($(plugindiv.add(javadiv)).add(maccdiv)).add(agentdiv);
	  network_access.find("p:first").attr({"id":"Network Access"});
	  agentdiv.find("p").eq(1).attr({"id":"Connect with the Access Gateway Plug-in."});
	  javadiv.find("p").eq(1).attr({"id":"Connect with the Access Gateway Plug-in for Java."});
	  maccdiv.find("p").eq(1).attr({"id":"Connect with the Access Gateway Plug-in for MAC."});
	  

	  plugindiv.find("p").eq(1).attr({"id":"Connect with the Access Gateway Plug-in for ActiveX."});
	  network_access.find(".description").attr('id','agent_description');
	  network_access.find(".box-icon > span").addClass("full-access");
	  cvpndiv.find("p:first").attr({"id":"Clientless Access"});
	  cvpndiv.find(".box-icon > span").addClass("client-access");
	  cvpndiv.find(".description").attr('id','cvpn_description');
	
	  
	  wicadiv.find("p:first").attr({"id":"Citrix XenApp"});
	  wicadiv.find(".description").attr('id','Connect with Citrix XenApp Plug-in for Hosted Apps.');
	  wicadiv.find(".box-icon > span").addClass("ica-access");
	  agentdiv.click(function(){ns_cleancookie();});
	  plugindiv.click(function(){ns_cleancookie();});
	  maccdiv.click(function(){ns_cleancookie();});
	  javadiv.click(function(){ns_cleancookie();});
	  wicadiv.click(function(){ns_cleancookie();});
	  cvpndiv.click(function(){ns_cleancookie();});
	  agentdiv.find("a").attr({"href":"/cgi/setclient?agnt"});
	  plugindiv.find("a").attr({"href":"/cgi/setclient?plgn"});
	  javadiv.find("a").attr({"href":"/cgi/setclient?java"});
	  maccdiv.find("a").attr({"href":"/cgi/setclient?macc"});
	  cvpndiv.find("a").attr({"href":"/cgi/setclient?cvpn"});
	  wicadiv.find("a").attr({"href":"/cgi/setclient?wica"});
	  
	  if(errcookie.indexOf("plugin")!=-1){
		client_choices_container.append(plugindiv);
	  }
	  if(errcookie.indexOf("agent")!=-1) { 
		client_choices_container.append(agentdiv);
	  }
	  if(errcookie.indexOf("java")!=-1) { 
		client_choices_container.append(javadiv);
	  }
	   if(errcookie.indexOf("mac")!=-1) { 
		client_choices_container.append(maccdiv);
	  }
	  if(errcookie.indexOf("cvpn")!=-1) { 
		client_choices_container.append(cvpndiv);
	  }

	  if(errcookie.indexOf("wicps")!=-1) { 
		client_choices_container.append(wicadiv);
	  }
	  	  
	  logonboxContainer.append(outerbox);

	// Don't remove cookie here. Otherwise it cannot do refresh
	}
		
	ns_fillchoices(); //make markup for choices div
	this._parent.append(logonboxContainer);
	Resources.Load();
	
//ajax call to get username
	$.ajax({
                 url:"/cgi/GetUserName",
                 type: "get",
                 dataType: "text",
                 success: function(data){
							fetchuser(data);
										}	
	})
	
	function fetchuser(data) {
		document.getElementById("username").innerHTML = data;
		
		}	
};

rdx.client_choices_view.prototype.get_callback_data = function()
{
	return {};  
};


function choice_resize(){
	var maxHeight = Math.max.apply(null, $(".box-content").map(function ()
	{
		return $(this).outerHeight();
	}).get());
	
	$(".box").outerHeight(maxHeight+40);
	}