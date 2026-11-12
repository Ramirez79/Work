function resizeMe(name){
  //if IE, set height with scrollHeight 
  if(document.getElementById&&document.all||navigator.userAgent.indexOf("Safari") >-1){
    docHeight = window.frames[name].document.body.scrollHeight;
  } else { //netscape browser
    //set iframe to a tiny height to force expansion
    //otherwise iframe will remain at 150px
     var item = name.substring(0,name.length-6);
     docHeight = window.frames[name].document.getElementById(item).scrollHeight;
     document.getElementById(name).style.height = '10px';
  }
  document.getElementById(name).style.height = docHeight + 'px';
}

function popHomePage(text, link){
  var gbk = document.getElementById('globalbk').innerHTML;
  var cbk = buildBookmarkList();
  if (!(gbk != '' && cbk == '<div class="NUI_Icon">' + _('bookmark::none') + '</div>')){
     gbk = gbk + cbk;
  } 
  document.getElementById('globalbk').innerHTML = gbk;
  document.getElementById("dummy").innerHTML = '';
}

function popFSHomePage(text, link){
  var gfs = document.getElementById('globalFSbk').innerHTML;
  var cfs = buildFSBookmarkList(text, link);
  if (!(gfs != '' && cfs == '<div class="NUI_Icon">' + _('bookmark::none') + '</div>')){
     gfs = gfs + cfs;
  } 
  document.getElementById('globalFSbk').innerHTML = gfs;
}

function buildBookmarkList(){
	var i;
	var counter = 0; //for checking no of weblinks in the prompt array.
  output = '';
	
	for(i=0;i<bookmark_List.resources.length;i++) {
	  if(bookmark_List.resources[i].content.charAt(0)=='\\') continue;
		counter += 1;
			// Fetching first letter of bookmark, need to show as icon in green homepage.
                 var obj={
                         title:  bookmark_List.resources[i].name,
                         url:   bookmark_List.resources[i].content,
                         icon:  bookmark_List.resources[i].iconurl 
        	  	  };
                 var first_letter = obj.title.charAt(0);
                 var first_letter_span = '<span class="hide_text">' + first_letter + "</span>";
			if (gb_home != -1){	 
				if(bookmark_List)
						 output += '<div class="NUI_Icon" onmouseover="showTooltip(this)" onmouseout="hideTooltip(this)" style="background:#99aba4 url(' + obj.icon +') 50% 50% no-repeat">';
				else
						output += '<div class="NUI_Icon" onmouseover="showTooltip(this)" onmouseout="hideTooltip(this)">';
			 }else
			 	 output += '<div class="NUI_Icon">';
			 
    		 output += '<table cellSpacing="0" cellPadding="0">';
    		 output += '<tr>';
    		 output += '<td class="cell3"><a class="bookmark_icon_anchor" href="'+ obj.url +'" target="_blank">' + obj.title + '</a></td>';
			 output += '<td class="cell1" style="padding-left:8px;"><a href="'+ obj.url +'" target="_blank">';
    		 output += '<img src="/vpns/navui/images/bookmark_pop_icon.png" border="0" style="padding-bottom: 1px;"/></a></td>';
    		 output += '</tr>';
    		 output += '</table>';
			 output += '<div class="tooltip">';
			 output += '<h3>'+ obj.title + '</h3>';
			 output += '<span class="arrow"></span></div>';
    		 output += '</div>';
		 var dummy = document.getElementById("dummy");
		 dummy.innerHTML=output;
		 var anchor = dummy.getElementsByTagName("a");
		 var anchor_reqd = anchor[anchor.length-2];
         var td = anchor_reqd.parentNode;
         var anchor_html = td.innerHTML;
		 if (!bookmark_List || bookmark_List.resources[i].iconurl == ""){
         td.innerHTML = first_letter_span+anchor_html;}
		 output = dummy.innerHTML;	
	}
	if (counter == 0){  //If no weblinks configured, then "none" is shown
	output = '<div class="NUI_Icon"><span class="none">' + _('bookmark::none') + '</span></div>';
	}
  return output;
}

function buildFSBookmarkList(prompt, href){
	var i;
	output = '';
	var netbios=-1;
	var counter = 0;  // for checking no of fileshare links in the "prompt" array.
	var browserfamily=navigator.userAgent.indexOf("Gecko"); 
	
	var pathname = top.location.pathname.split('/');
	var homepage = pathname[pathname.length -1];
	
	for(i=0;i<prompt.length;i++) {
		if(href[i].charAt(0)!='\\') continue;
		counter += 1;
		output += '<div class="NUI_Icon">';
    	output += '<table cellSpacing="0" cellPadding="0">';
    	output += '<tr>';
		if(netbios==-1 || browserfamily>0) {            
    		 output += '<td><A HREF="/vpns/portal/'+ homepage +'?activetab=fs&?s='+href[i]+'" target="_BLANK">' + ' ' + prompt[i] + '</A>';
			 output += '<A HREF="/vpns/portal/'+ homepage +'?activetab=fs&?s='+href[i]+'" target="_BLANK" style="padding-left:8px;">';
    		 output += '<img src="/vpns/navui/images/bookmark_pop_icon.png" border="0"/></a>';
    		 output += '</td>';
		} else {
    		 output += '<td><A HREF="'+href[i]+'" target="_BLANK">' + ' ' + prompt[i] + '</A>';
			 output += '<A HREF="/vpns/portal/'+ homepage +'?activetab=fs&?s='+href[i]+'" target="_BLANK" style="padding-left:8px;">';
    		 output += '<img src="/vpns/navui/images/bookmark_pop_icon.png" border="0" /></a>';
    		 output += '</td>';
		}
		     output += '</tr>';
    		 output += '</table>';
    		 output += '</div>';
		}
		if (counter == 0){   //If no fileshare links are configured, then "none" is shown
			output += '<div class="NUI_Icon none_fs">' + _('bookmark::none') + '</div>';
		}
	return output;
	
}

function ns_popupbookmarkoppage(url)
{
window.open(url, "ns_bm", "toolbar=no,menubar=no,status=no,width=800,height=425,scrollbars=yes");
return false;
}

function ns_reload()
{
window.location.reload();
}
