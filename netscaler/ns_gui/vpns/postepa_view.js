
rdx.postepa_view = function(parent, view_definition, resource, base_view)
{   
    this._base_view = base_view;
    this._parent = parent;
        
    this._DEFAULT_PROPERTIES =
    {
    };
    
};

rdx.postepa_view.prototype.make_view = function()
{
	this._base_view.set_crumb_handler("") ;
	var certform =$('<FORM name="certForm" autocomplete="off" style="margin:0"></FORM>');
	var certauth = $("<div></div>").attr({"id":"certAuth"});
	certform.append(certauth);
	var header_text = $("<div></div>").attr({'class': 'CTX_ContentTitleHeader','id': 'Checking Your Device'});
	var nsepadiv = $("<div></div>").attr('id','nsepadiv');
	var right_downloadbutton=$("<div></div>").addClass('field right');
	var Download = $("<input type='button'></input>").attr({'id':'downloadbutton','value':'Download', 'class':'custombutton', 'style': 'margin-left:5px; float:right; display:none;'}).appendTo(right_downloadbutton).click(function(){onDownload();});
	var LaunchButton = $("<input type='button'></input>").attr({'id':'launchbutton','value':'Download', 'class':'custombutton', 'style': 'margin-left:5px; float:right; display:none;'}).appendTo(right_downloadbutton).click(function() {agmac_onLaunch();});
	var form_skipepa = $("<form></form>").attr({"id":"getform","action":"/epaskip","method":"GET","style":"margin:0"}).submit(function() {ns_skipepa();}).appendTo(right_downloadbutton);
	var skipbutton = $("<input type='submit'></input>").attr({"id":"skipbutton","class":"custombutton epabutton"}).appendTo(form_skipepa);
	var left = $("<div></div>").addClass('left');
	var box = $("<div></div>").addClass("plain");
	var innerbox = $("<p></p>").attr('id','ctl08_scanInProgress');
	var epatext = $("<span></span>").attr('id','The Access Gateway must confirm that you have the minimum requirements on your device before you can log on.');
	var epatext2 = $("<span></span>").attr('id','Your device is checked automatically if the Citrix Endpoint Analysis Plug-in software is already installed.');
	var epatext3 = $("<span></span>").attr('id','To install or upgrade the software, click Download.');
	var Appinfo = $("<span></span>").attr('id','AppINFO');
	var timer = $("<span></span>").attr('id','timer');
	var skipEpaText = $("<span></span>").attr({"id":"If you do not want your device checked, click Skip Check."})
	certauth.append(header_text,left,right_downloadbutton);
	this._parent.append(certform,nsepadiv);
	left.append(box.append(innerbox.append(epatext,"<br><br>",epatext2,"<br>","<br>",Appinfo,"<br>",timer,"<br>",epatext3,"<br>",skipEpaText)));
	Resources.Load();
	detectEpaComponent();		
};

rdx.postepa_view.prototype.get_callback_data = function()
{
	return {};  
};
