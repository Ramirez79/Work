
rdx.epa_errorpage_view = function(parent, view_definition, resource, base_view)
{   
    this._base_view = base_view;
    this._parent = parent;
        
    this._DEFAULT_PROPERTIES =
    {
    };
    
};


rdx.epa_errorpage_view.prototype.make_view = function()
{
	this._base_view.set_crumb_handler("") ;
	var header = $("<div></div>").addClass("field CredentialTypenone");
	var left = $("<div></div>").addClass("");
	var box = $("<div></div>").addClass("plain");
	var error_msg= $("<TR></TR>").append($("<TD></TD>").attr('colspan','4').append($("<div></div>").addClass('label error CTX_ContentTitleHeader errorpage').append($("<span></span>").attr('id','Access Denied'))));
	var line1= $("<TR></TR>").append($("<TD></TD>").attr('colspan','4').append($('<span class="form_text"></span>').attr('id','Your device does not meet the requirements for logging on.')));
	var line2 = $("<TR></TR>").append($("<TD></TD>").attr('colspan','4').append($('<span class="form_text"></span>').attr('id','For more information, contact your help desk and provide the following information:')));
	var blank1 = $("<TR></TR>").append($("<TD></TD>").attr('colspan','4').html('&nbsp;'));
	var date = $("<TD></TD>").attr('width','20%').append($('<span class="form_text"></span>').attr('id','Date:'));
	var dateinfo = $("<TD></TD>").attr('width','70%').append($("<STRONG></STRONG>").append($('<div class="form_text"></div>').attr('id','dayinfo')));
	var blank2 = $("<TD></TD>").attr('width','10%').html('&nbsp;');   
	var datefield = $("<TR></TR>").append(date,dateinfo,blank2);
	var time = $("<TD></TD>").attr('width','20%').append($('<span class="form_text"></span>').attr('id','Time:'));
	var timeinfo = $("<TD></TD>").attr('width','70%').append($("<STRONG></STRONG>").append($('<div class="form_text"></div>').attr('id','timeinfo')));
	var blank3 = $("<TD></TD>").attr('width','10%').html('&nbsp;');
	var timefield = $("<TR></TR>").append(time,timeinfo,blank3);
	var error = $("<TD></TD>").attr({'width':'20%', "style":"vertical-align:top;" }).append($('<span class="form_text"></span>').attr('id','Error:'));
	var errmsg = $("<TD></TD>").attr('width','70%').append($("<STRONG></STRONG>").append($('<div class="form_text">').attr(
			{'id':'errmsg',"style":"width:450px;"})));
	var blank4 = $("<TD></TD>").attr('width','10%').html('&nbsp;');
	var errorfield = $("<TR></TR>").append(error,errmsg,blank4);
	var blank5 = $("<TR></TR>").append($("<TD></TD>").attr('colspan','4').html('&nbsp;'));
	var check_again= $("<TR></TR>").append($("<TD></TD>").attr('colspan','4').append($('<span class="form_text"></span>').attr('id','To check your device again, click Back.')));
	var blank6 = $("<TR></TR>").append($("<TD></TD>").attr('colspan','4').html('&nbsp;'));
	var back_button= $("<TR></TR>").append($("<TD></TD>").attr('colspan','4').append($("<CENTER></CENTER>").append($("<INPUT></INPUT>").attr({'id':'Button1','type':'button','value':'Back','name':'Button1','class':'custombutton','style':'float:right;'}).click(GoNextPage))));
	var table =$("<TABLE></TABLE>").attr('id','epaErrorTable').append($("<TBODY></TBODY>").append(error_msg,line1,line2,blank1,datefield,timefield,errorfield,blank5,check_again,blank6,back_button));
	header.append(left.append(box.append(table)));
	this._parent.append(header);
	Resources.Load();
	ns_fillinfo();
	
	
};

rdx.epa_errorpage_view.prototype.get_callback_data = function()
{
	
    return {};  
};


function formatError(errorText, isPostEPA)
{
	var allLines = errorText.split("\n")
	
	if (allLines.length < 3)			//if EPA error logging is not enabled display messages as it is 
	{
		return errorText;
	}
	
	
	var formatedText = "";
	var i = 0;
	while(allLines[i].length > 1)
	{
		formatedText += (allLines[i] + "<br />");
		i++
	}
	var startFrom = i + 1;
	
	formatedText += '<ul style="list-style-type: circle; margin: 0.5em;" >';
	for(var i = startFrom; i < allLines.length; i++)
	{
		if(allLines[i].length > 1)
			formatedText += ( "<li>" + allLines[i] + "</li>");
	}
	formatedText +="</ul>";
	
    return formatedText;
}
