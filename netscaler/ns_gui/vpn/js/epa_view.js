
rdx.epa_view = function(parent, view_definition, resource, base_view)
{   
    this._base_view = base_view;
    this._parent = parent;
        
    this._DEFAULT_PROPERTIES =
    {
    };
};


rdx.epa_view.prototype.make_view = function()
{
	this._base_view.set_crumb_handler("") ;
	var certform =$('<FORM name="certForm" autocomplete="off" style="margin:0"></FORM>');
	var certauth = $("<div></div>").attr({"id":"certAuth"});
	certform.append(certauth);
	var header_text = $("<div></div>").attr({'class': 'CTX_ContentTitleHeader','id': 'loginAgentCdaHeaderText'});
	var nsepadiv = $("<div></div>").attr('id','nsepadiv');
	var right_downloadbutton=$("<div></div>").addClass('right');
	var Download = $("<input type='button'></input>").attr({'id':'downloadbutton','value':'Download', 'class':'custombutton', 'style': 'margin-left:5px; float:right; display:none;'}).appendTo(right_downloadbutton).click(onDownload);
	var left = $("<div></div>").addClass('');
	var box = $("<div></div>").addClass("plain");
	var innerbox = $("<p></p>").attr('id','ctl08_scanInProgress');
	var epatext = $("<span></span>").attr('id','The Access Gateway must confirm that you have the minimum requirements on your device before you can log on.');
	var epatext2 = $("<span></span>").attr('id','Your device is checked automatically if the Citrix Endpoint Analysis Plug-in software is already installed.');
	var epatext3 = $("<span></span>").attr('id','To install or upgrade the software, click Download.');
	var Appinfo = $("<span></span>").attr('id','AppINFO');
	var timer = $("<span></span>").attr('id','timer');
	this._parent.append(certform,nsepadiv);
	certauth.append(header_text,left,right_downloadbutton);
	left.append(box.append(innerbox.append(epatext,"<br><br>",epatext2,"<br><br>",Appinfo,"<br><br>",timer,"<br><br>",epatext3)));
	var scriptArea = $("<div></div>");
	Resources.Load();
	detectEpaComponent();		
};

rdx.epa_view.prototype.get_callback_data = function()
{
	
    return {};  
};
