var text = new Array();
var link = new Array();
var bmprompt = new Array();
var bmuri = new Array();


function popHomePage(bmprompt, bmuri, text, link){
  document.getElementById('personalbk').innerHTML = buildBookmarkList(bmprompt, bmuri);
  document.getElementById('globalbk').innerHTML = buildBookmarkList(text, link);
  document.getElementById('personalFSbk').innerHTML = buildFSBookmarkList(bmprompt, bmuri);
  document.getElementById('globalFSbk').innerHTML = buildFSBookmarkList(text, link);
  document.getElementById('help').innerHTML = helplink();
}

function helplink(){
  if ((navigator.userAgent.indexOf("MSIE") >-1) && (navigator.userAgent.indexOf("Windows") >-1)){  //if IE
    return '<a href="/vpns/help/ActiveX/Title-Page.html">User Guide</a>';
  } else { //every one else
    return '<a href="/vpns/help/java/Title-Page.html">User Guide</a>';
  }
}

function buildBookmarkList(prompt, href){
	var i;
  output = '';
	if((prompt.length==0) || (href.length==0)) {
		output = '<div class="item">none</div>';
		return output;
	}
	for(i=0;i<prompt.length;i++) {
	  if(href[i].charAt(0)=='\\') continue;
		output += '<div class="item">'; 
		output += '<A HREF="'+href[i]+'" target="_top">' + prompt[i] + '</A>';
		output += '</div>';
	}
  return output;
}

function buildFSBookmarkList(prompt, href){
	var i;
	var j=0;
	output = '';
	if((prompt.length==0) || (href.length==0)) {
		output += '<div class="item">none</div>';
		return output;
	}
	for(i=0;i<prompt.length;i++) {
		if(href[i].charAt(0)=='\\') {
			j++;
		} else {
		  continue;
		}
		output += '<div class="item">';
		output += '<A HREF="/vpns/fslogin.html?s='+href[i]+'" target="_top">' + prompt[i] + '</A>';
		output += '</div>';
	}
	if(j==0) {output += '<div class="item">none</div>'; }
	return output;
}

function populateRMBookmarks(bmprompt, bmuri){
  document.getElementById('rmbmlist').innerHTML = buildRMBookmarkList(bmprompt, bmuri);
}

function buildRMBookmarkList(prompt, href){
  var i;
  output = '';
  if((prompt.length==0) || (href.length==0)) {
   output += '<div class="item">';
   output += 'You have not added any personal bookmark yet, Click Cancel to return.<P>';
   output += '</div>';
   return output;
  }
  for(i=0;i<prompt.length;i++) {
   output += '<div class="item">\n'; 
   output += '<INPUT TYPE="checkbox" name="id'+i+'" value="'+i+'">\n';
   output += '<label for="id'+i+'">' + prompt[i] + '</label>';
   output += '</div>';
  }
return output;
}
