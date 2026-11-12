/* Copyright 2003, NetScaler, Inc */
var browsertype;
var rootmenu = null;
var NSDrawBar = new Array();
var level;
var typefolder = "d";
var typefile = "f";
var menudoc, textdoc, addressdoc;
var FrameTitleName = "title";
var FrameTextName = "text";
var FrameMenuName = "menu";
var FrameAddressName = "address";
var frmtitle = parent.frames["title"];
var frmmenu = parent.frames["menu"];
var frmtext = parent.frames["text"];
var frmaddress = parent.frames["address"];
var activepath = "/";
var activemenu = null;
var calledbytext = false;
var hlitem = -1; // HighLight Item
var lastTR = null; // HighLight TD
var gid = 1;
var focusid = 0, focusypos = 0;
var enterurl=null;
var ajaxClient;
var fssso = 0;
var refresh_flag=false;
//---------------------------------------------------------------------------
// Entry Functions to change frames content
//---------------------------------------------------------------------------
function updateMenuOnly(url)
{
	calledbytext = false;
	NS_setexpandstatus(url, false);
	NSDisplayMenu();
}

function NSStartMenu() 
{
	setTimeout("NSDisplayMenu()",10);
}

function NSStartText() 
{
	setTimeout("NSDisplayText()",10);
}

function SortDirectories(a,b)
{
    if (a.length == 0 || b.length == 0)
        return b.length - a.length;
    var a1 = a[1].split("/");
    var b1 = b[1].split("/");
    var a2 = a1[a1.length-1];
    var b2 = b1[b1.length-1];
    var arr1 = a2.split("&#");
    var arr2 = b2.split("&#");
    var smaller = arr1.length < arr2.length ? arr1.length:arr2.length;
    var i;
    for (i=1; ((parseInt(arr1[i])|32) == (parseInt(arr2[i])|32)) &&
            (i < smaller); i++);
    if (i == smaller)
        return (arr1.length - arr2.length);
    if (parseInt(arr1[i]) >= 0x30 && parseInt(arr1[i]) < 0x40
            && parseInt(arr2[i]) >= 0x30 && parseInt(arr2[i]) < 0x40) {
        return arr1.length == arr2.length ?
             parseInt(arr1[i]) - parseInt(arr2[i]):
             arr1.length - arr2.length;
    }
    return (parseInt(arr1[i])|32)-(parseInt(arr2[i])|32);
}

function NSListHandler()
{
	var dirs, dir, i;
	if (ajaxClient.readyState == 4) {
		if (ajaxClient.responseText.indexOf("[[") != -1) {
			dirs = eval(ajaxClient.responseText);

		if(refresh_flag==true){
            for (i=dirs.length-1; i>=0; i--) {
				dir = dirs[i];
				if (dir.length == 2) {
					frmtitle.NSAddPath(dir[0], dir[1]);
				} else if (dir.length == 4) {
					frmtitle.NSAddPath(dir[0], dir[1], dir[2], dir[3]);
				}
			}
            refresh_flag=false;
        }
        else
        {
            
            for (i=0; i<dirs.length; i++) {
				dir = dirs[i];
				if (dir.length == 2) {
					frmtitle.NSAddPath(dir[0], dir[1]);
				} else if (dir.length == 4) {
					frmtitle.NSAddPath(dir[0], dir[1], dir[2], dir[3]);
				}
			}
        }




			frmtitle.NSUpdateAllwithoutPath();
		} else if (ajaxClient.responseText.indexOf("Session has expired")!=-1){
			parent.parent.document.location = "/vpns/index.html";
		} else {
			var msg = _('fsgotoerror::' + ajaxClient.responseText);
			if (msg.length == 0)
			{
			    var dom = document.createElement('div');
			    dom.innerHTML = ajaxClient.responseText;
			    var spanTags = dom.getElementsByTagName("SPAN");
			    if (spanTags.length > 0)
			    {
			        msg = _('fsgotoerror::' + spanTags[0].id);
			    }
			    else
			    {
			        msg = ajaxClient.responseText;
			    }
			    if (spanTags.length > 1 && spanTags[1])
			    {
			        var msg2 = _('fsgotoerror::' + spanTags[1].id);
			        if (msg2.length == 0)
			        {
			            msg2 = spanTags[1].innerText;
			        }
			        msg = msg + ' (' + msg2 + ')';
			    }
			}
		    msg = '<center>' + msg + '</center>';
		    frmtext.document.body.innerHTML = msg;
		}	
	}
}

function NSUpdateAll(url) 
{
	activepath=url;
	NSDisplayAddress();
	activemenu=NS_path2menu(activepath);
	if(NS_hassubmenu(url)==false) {
		NS_setexpandstatus(activepath, true);
		NSLoadFolderFromServer();
	}
	else {
		calledbytext = false;
		NS_setexpandstatus(activepath, true);
		setTimeout("NSDisplayMenu()",10);
		setTimeout("NSDisplayText()",10);
	}
}

function NSUpdateAllwithoutPath() 
{
	calledbytext = true;
	setTimeout("NSDisplayMenu()",10);
	setTimeout("NSDisplayText()",10);
}


//---------------------------------------------------------------------------
// File System Operation Functions 
//---------------------------------------------------------------------------
function NS_goto()
{
	frmtext.location="/vpns/fsgoto.html";
	return false;
}

function NS_upfolder()
{
	var url;
	if(activepath.lastIndexOf("/")==-1) {	
		window.alert(_("nscode::There is no up folder"));
		return false;
	}
	url=activepath.substring(0, activepath.lastIndexOf("/"));
	NSUpdateAll(url);
	return false;
}

function NS_refresh()
{
	var splitpath = activepath.split("/");
	var i;
    refresh_flag=true;
	if(splitpath.length <= 2) {
		return false;
	}
	activemenu=NS_path2menu(activepath);
	NS_setexpandstatus(activepath, true);
	for(i=0;i<activemenu.items.length;i++) {
		activemenu.items[i]=null;
	}
	NSLoadFolderFromServer();
	return false;
}

function NS_downloadfile()
{
	if(hlitem==-1) {
		window.alert(_("nscode::Please choose a file to be downloaded first."));
		return false;
	}
	activemenu=NS_path2menu(activepath);
	if(activemenu==null) return false; // Internal error
	if(activemenu.items[hlitem].type==typefolder) {
		window.alert(_("nscode::The feature to download a folder is not supported."));
		return false;
	}
	return NS_dogetfile("/fs/get"+NS_html2url(activepath)+"/"+NS_html2url(activemenu.items[hlitem].text));
}

function NS_uploadfile()
{
	var splitpath = activepath.split("/");
	if(splitpath.length <= 2) {
		window.alert(_("nscode::Cannot upload a file into root directory."));
		return false;
	}
	frmtext.location="/vpns/fsupload.html";
	return false;
}

function NS_mkdir()
{
	var splitpath = activepath.split("/");
	if(splitpath.length <= 2) {
		window.alert(_("nscode::Cannot create a directory in the root directory."));
		return false;
	}
	frmtext.location="/vpns/fsmkdir.html";
	return false;
}

function NS_del()
{
	var splitpath = activepath.split("/");
	if(splitpath.length <= 2) {
		window.alert(_("nscode::Root share folder cannot be deleted."));
		return false;
	}
	if(hlitem==-1) {
		window.alert(_("nscode::Please choose a file to be deleted first."));
		return false;
	}
	activemenu=NS_path2menu(activepath);
	if(activemenu==null) return false; // Internal error
	if(activemenu.items[hlitem].type==typefolder) {
		frmtext.location="/vpns/fsrmdir.html";
	}
	else
		frmtext.location="/vpns/fsdelfile.html";
	return false;
}

function NS_rename()
{
	var splitpath = activepath.split("/");
	if(splitpath.length <= 2) {
		window.alert(_("nscode::Root share folder cannot be renamed."));
		return false;
	}
	if(hlitem==-1) {
		window.alert(_("nscode::Please choose a file to be renamed first."));
		return false;
	}
	activemenu=NS_path2menu(activepath);
	if(activemenu==null) return; // Internal error
	frmtext.location="/vpns/fsrename.html";
	return false;
}

function NS_du()
{
	var splitpath = activepath.split("/");
	if(splitpath.length <= 2) {
		window.alert(_("nscode::Choose a directory to display usage first."));
		return false;
	}
	frmtext.location="/fs/du"+activepath;
	return false;
}

function NS_logout()
{
	frmtext.location="/vpns/fslogout.html";
	return false;
}

//---------------------------------------------------------------------------
// Functions to change the status to open/close a folder
//---------------------------------------------------------------------------
// alwaystrue=true: always expanded
//	     =false: change the status
function NS_setexpandstatus(path, alwaystrue) {
	var splitpath = path.split("/");
	var i, n;
        var menu;

        menu = rootmenu;
	for(n=1;n<splitpath.length;n++) {
	   splitpath[n] = purify(splitpath[n]);
	   if(menu==null) return; // somehow the menu tree is broken
	   for(i=0;i<menu.items.length;i++) {
		if(menu.items[i]==null) continue;
		if(menu.items[i].text==splitpath[n])
		   break;
	   }
	   if(i==menu.items.length) return; // Cannot find it
	   if(n==splitpath.length-1) { // Last one
		if(alwaystrue) menu.items[i].expanded=true;
		else menu.items[i].expanded=(menu.items[i].expanded)
			? false : true;
		return;
	   }
	   menu = menu.items[i].submenu;
	}
}

function NS_hassubmenu(path) {
	var splitpath = path.split("/");
	var i, n;
        var menu;

        menu = rootmenu;
	for(n=1;n<splitpath.length;n++) {
	   splitpath[n] = purify(splitpath[n]);
	   if(menu==null) return false; // somehow the menu tree is broken
	   for(i=0;i<menu.items.length;i++) {
		if(menu.items[i]==null) continue;
		if(menu.items[i].text==splitpath[n])
		   break;
	   }
	   if(i==menu.items.length) return false; // Cannot find it
	   if(n==splitpath.length-1) { // Last one
		if(menu.items[i]==null) continue;
		if(menu.items[i].submenu==null)
			return false;
		else
			return true;
	   }
	   menu = menu.items[i].submenu;
	}
}

// Given any menuitem, return the path
function NS_menuitem2path(menuitem) {
	var menu;
	var path;

	menu=menuitem.parentMenu;
	path = "/" + menuitem.text;
	while(1) {
		if(menu==rootmenu) break;
		path="/" + menu.parentMenuitem.text + path;
		menu=menu.parentMenuitem.parentMenu;
	}
	return path;
}

// Given a path, returns the menu of the menuitem
function NS_path2menu(path) {
	var splitpath = path.split("/");
	var i, n;
	var menu;
	
        menu = rootmenu;
	for(n=1;n<splitpath.length;n++) {
	   splitpath[n] = purify(splitpath[n]);
	   if(menu==null) return null; // somehow the menu tree is broken
	   for(i=0;i<menu.items.length;i++) {
		if(menu.items[i] &&
		   (menu.items[i].text==splitpath[n]))
		   break;
           }
	   if(i==menu.items.length) return null; // Cannot find it
	   if(n==splitpath.length-1) 
		   return menu.items[i].submenu; // We got it
	   menu = menu.items[i].submenu;
	}
	return null;
}

// Given a path, returns the menu of the menuitem
function NS_ispathexist(path) {
	var splitpath = path.split("/");
	var i, n;
	var menu;
	
        menu = rootmenu;
	for(n=1;n<splitpath.length;n++) {
	   splitpath[n] = purify(splitpath[n]);
	   if(menu==null) break; // somehow the menu tree is broken
	   for(i=0;i<menu.items.length;i++) {
		if(menu.items[i] &&
		   (menu.items[i].text==splitpath[n]))
		   return true;
           }
	   if(i==menu.items.length) break; // Cannot find it
	   menu = menu.items[i].submenu;
	}
	return false;
}

//---------------------------------------------------------------------------
// Functions to build the menu memories
//---------------------------------------------------------------------------
function NSMenuItem(type, text, size, timestr, closeIcon, openIcon) {
	this.type = type;	// type=='d' or 'f'
	this.text = text; 
	this.size = size; 
	this.timestr = timestr;
	this.closeIcon = closeIcon ? closeIcon : "folder_closed.gif";
	this.openIcon = openIcon ? openIcon : "folder_open.gif"; 
	this.id = gid++;

	this.parentMenu	 = null;
	this.submenu     = null;
	this.expanded    = false;
}

function NSMenu() {
	this.items   = new Array();
	this.parentMenuitem = null;
	this.addItem = NSAddItem;
}

function NSAddItem(i, type, text, size, timestr, closeIcon, openIcon) {
	var newitem = new NSMenuItem(type, text, size, timestr, closeIcon, openIcon);
	newitem.parentMenu = this;
	if(i==-1) this.items[this.items.length] = newitem;
	else this.items[i]=newitem;
}

// To create the Menu Tree
function NSAddPath(type, path, size, timestr) {
	var splitpath = path.split("/");
	var i, n;
	var menu;
	var nulli;
	var esc_flag;

        menu = rootmenu;
	nulli=-1;
	for(n=1;n<splitpath.length;n++) {
	   for(i=0;i<menu.items.length;i++) {
		esc_flag = /&#\d+;*/.test(splitpath[n]);
		if(!esc_flag) {
		    splitpath[n] = purify(splitpath[n]);
		}
		if(menu.items[i]==null) {
			nulli=i;
			continue;
		}
		if(menu.items[i].text==splitpath[n])
		break;
	   }
	   if(n==splitpath.length-1) {
	        if(i!=menu.items.length) return;
	        menu.addItem(nulli,type,splitpath[n],size,timestr);
	        return;
	   }
	   if(i==menu.items.length) {
		menu.addItem(nulli,typefolder,splitpath[n],"&nbsp;","&nbsp;");
	   }
	   if(menu.items[i].submenu==null) {
		menu.items[i].submenu=new NSMenu();
		menu.items[i].submenu.parentMenuitem=menu.items[i];
	   }
	   menu=menu.items[i].submenu;
	}
	return;
}

//---------------------------------------------------------------------------
// Functions to display menu tree
//---------------------------------------------------------------------------
function NS_menulistoneitem(menuitem, layer, isLast, insertbold) {
	var i;
	var subHTML;
	var onClickPlusMinus, onClickFolder;
	var usePlusMinus;
	var url;
	
	subHTML="";
	for (i = 0; i < level; i++) 
		subHTML += (NSDrawBar[i]) ? NS_makeimg("bar.gif") : NS_makeimg("pixel.gif");

    	url = NS_menuitem2path(menuitem);
	onClickPlusMinus = "return frmtitle.updateMenuOnly('" + url + "');";
	
	if(menuitem.submenu)
		subHTML += NS_makelink(null, 0, menuitem.id, null, onClickPlusMinus, null, null);
		
	if(isLast) {
	    usePlusMinus = false;
	    if(menuitem.submenu) {
		for(i=0;i<menuitem.submenu.items.length;i++) {
		     if(menuitem.submenu.items[i]==null) continue;
		     if(menuitem.submenu.items[i].type==typefolder) {
			usePlusMinus = true;
			break;
		     }
		 }
	    }
	    if(usePlusMinus) {
		if(menuitem.expanded)
		      subHTML += NS_makeimg("corner_minus.gif");
		else
		      subHTML += NS_makeimg("corner_plus.gif");
	    }
	    else
		subHTML += NS_makeimg("corner.gif");
	}
	else {
	    usePlusMinus = false;
	    if(menuitem.submenu) {
		for(i=0;i<menuitem.submenu.items.length;i++) {
		     if(menuitem.submenu.items[i]==null) continue;
		     if(menuitem.submenu.items[i].type==typefolder) {
			usePlusMinus = true;
			break;
		     }
		}
	    }
	    if(usePlusMinus) {
	    	if(menuitem.expanded)
		    subHTML += NS_makeimg("tee_minus.gif");
	    	else
		    subHTML += NS_makeimg("tee_plus.gif");
	    }
	    else
	    	subHTML += NS_makeimg("tee.gif");
	}
	if(menuitem.submenu)
	    subHTML += '</a>';

	onClickFolder = "return frmtitle.NSUpdateAll('" + url + "');";
	subHTML += NS_makelink(null, 1, menuitem.id, null, onClickFolder, null, null);

	if(menuitem.parentMenu == rootmenu)
	    subHTML += NS_makeimg("computer.gif");
	else {
	    if(menuitem.submenu!=null) {
	    	subHTML += (menuitem.expanded)
			? NS_makeimg(menuitem.openIcon)
			: NS_makeimg(menuitem.closeIcon);
	    }
	    else
	    	subHTML += NS_makeimg(menuitem.closeIcon);
	}
	
	subHTML += NS_url2html(menuitem.text);
	subHTML += '</A>';
	
	NS_menuaddcell(subHTML, insertbold);
}


function NS_menulistall(menu, layer, splitpath) {
	var i, isLast, lastfolder, isActive, insertbold, escaped_splitpath_layer;
	
	lastfolder = 0;
	isActive = false;
	for (i = 0; i < menu.items.length; i++) {
	    	if(menu.items[i] == null) continue;
		if(menu.items[i].type == typefolder)
			lastfolder = i;
	}

	for (i = 0; i < menu.items.length; i++) {
	    	if(menu.items[i]==null) continue;
		if(menu.items[i].type == typefile) continue;
		insertbold = false;
		if(splitpath) {
			escaped_splitpath_layer = purify(splitpath[layer]);
			if(escaped_splitpath_layer == menu.items[i].text)
			{
				isActive = true;
				insertbold=true;
				focusid = focusypos;
			}
		}
		isLast = (i == lastfolder);
		NS_menulistoneitem(menu.items[i], layer, isLast, insertbold);
		if((menu.items[i].expanded) && menu.items[i].submenu )
		{
			NSDrawBar[level] = (isLast) ? false : true;
			level++;
			if(isActive)
			NS_menulistall(menu.items[i].submenu, layer+1, splitpath);
			else
			NS_menulistall(menu.items[i].submenu, layer+1, null);
			level--;
		} else {
			NSDrawBar[level] = false;
		}
	}
}

function NS_menupage(splitpath) {
	var subHTML;
	
	subHTML =
	'<HEAD><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><TITLE></TITLE>' +
	'<link rel="stylesheet" type="text/css" href="/vpns/images/fs.css" />' +
	'</HEAD>' +
	'<script type="text/javascript">' +
	'var frmtitle = parent.frames["title"];' +
	'var frmtext = parent.frames["text"];' +
	'var frmmenu = parent.frames["menu"];' +
	'</script>' +
	'<BODY>' +
	'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
	menudoc.writeln(subHTML);
	
	focusypos = 0;
	subHTML = NS_makeimg("root.gif");
	subHTML += NS_makelink(_("nscode::Neighborhood"), 1, 0, "fsgoto.html?neighbor=true", null, null, null)
	NS_menuaddcell(subHTML);
	
	level = 0;
	NS_menulistall(rootmenu, 1, activepath.split("/"));

	subHTML = '</TABLE></BODY>';
	menudoc.writeln(subHTML);
}

function NSDisplayMenu() {
	menudoc = parent.frames['menu'].document;
	if(browsertype=="MSIE") { menudoc.open("text/html", "replace"); }
	else { menudoc.open(); }
	NS_menupage();
	menudoc.close();
	frmmenu.scrollTo(0, focusid*18);
}

function NS_menuaddcell(thisHTML, insertbold) {
	menudoc.writeln('<tr valign="top"><td nowrap class="nsTree">');
	if(insertbold) 
	menudoc.writeln('<b>' + thisHTML + '<\/b>');
	else
	menudoc.writeln(thisHTML);
	menudoc.writeln('<\/td><\/tr>');
	focusypos++;
}

//---------------------------------------------------------------------------
// Functions to display text frame window
//---------------------------------------------------------------------------
function NS_textlistall(menu)
{
	var subHTML;
	var onClickIcon;
	var url;
	var newpath = activepath;
	newpath = purify(newpath);
	
    var i;
	for(i=0;i<menu.items.length;i++)
	{
	    if(menu.items[i]==null) continue;
	    subHTML = '<TR class=nsText align=left onclick="frmtitle.NS_hl(this, ' + i +');return false;">';
	    subHTML += '<TD WIDTH=18px nowrap>';
	    if(menu.items[i].type == typefolder) {
	      url= newpath + '/' + menu.items[i].text;
	      onClickIcon = "return frmtitle.NSUpdateAll('"+url+"');";
	      subHTML += NS_makelink(null, 0, 0, "/fs/err", onClickIcon, null, null);
	    }
	    else {
	      url="/fs/get"+newpath+"/"+menu.items[i].text;
	      onClickIcon = "return frmtitle.NS_dogetfile('"+url+"');";
	      subHTML += NS_makelink(null, 0, 0, url, onClickIcon, null, null);
	    }
	    subHTML += '<IMG SRC="/vpns/images/'+NS_getimg(menu.items[i])+'" border=0 hspace=0 vspace=0></A></TD>';
	    subHTML += '<TD nowrap>' + NS_url2html(menu.items[i].text) + '</TD>';
	    if(menu.items[i].type==typefolder) {
	    subHTML += '<TD align=right nowrap>&nbsp;</TD>';
	    subHTML += '<TD nowrap>' + _('nscode::folder') + '</TD>';
	    } else {
	    subHTML += '<TD align=right nowrap>' + menu.items[i].size + '&nbsp;&nbsp;</TD>';
	    subHTML += '<TD nowrap>' + _('nscode::file') + '</TD>';
	    }
	    var localeDateTime = "";
	    if (menu.items[i].timestr)
	    {
	      if (Resources.Language(true) == Resources.Language(false))
	      {
	        localeDateTime = menu.items[i].timestr;
	      }
	      else
	      { 
	        var modified = new Date(menu.items[i].timestr);
	        localeDateTime = modified.toLocaleString();
	      }
	    }
	    subHTML += '<TD nowrap>' + localeDateTime + '</TD>';
	    subHTML += '</TR>';
	    textdoc.writeln(subHTML);
	}  	
}


function NS_textpage(menu) {
	var subHTML;
	
	subHTML =
	'<HEAD><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><TITLE></TITLE>' +
	'<link rel="stylesheet" type="text/css" href="/vpns/images/fs.css">'+
	'<script type="text/javascript">' +
	'var frmtitle = parent.frames["title"];' +
	'var frmtext = parent.frames["text"];' +
	'var frmmenu = parent.frames["menu"];' +
	'</script>' +
	'<BODY>' +
	'<HEAD><body><TABLE ' +
	'style="BORDER-RIGHT: medium none; BORDER-TOP: medium none; BORDER-LEFT: medium none; BORDER-BOTTOM: medium none"' +
	'cellSpacing=0 cellPadding=0 bgColor=#ffffff width=100% border=0>' +
        '<TBODY>' +
        '<TR align=left>' +
        '<TH colspan=2 nowrap><TABLE cellSpacing=0 cellPadding=0 border=1 width=100%><TR><TD class=nsTitle>' + _('nscode::Name') + '</TD></TR></TABLE></TH>' +
        '<TH nowrap><TABLE cellSpacing=0 cellPadding=0 border=1 width=100%><TR><TD class=nsTitle align=right>' + _('nscode::Size') + '&nbsp;</TD></TR></TABLE></TH>' +
        '<TH nowrap><TABLE cellSpacing=0 cellPadding=0 border=1 width=100%><TR><TD class=nsTitle>&nbsp;' + _('nscode::Type') + '</TD></TR></TABLE></TH>' +
        '<TH nowrap><TABLE cellSpacing=0 cellPadding=0 border=1 width=100%><TR><TD class=nsTitle>&nbsp;' + _('nscode::Modified') + '</TD></TR></TABLE></TH>' +
        '</TR>';
	textdoc.writeln(subHTML);
	
	if(menu) NS_textlistall(menu);
	
	subHTML ='</TBODY></TABLE></BODY>';
    	textdoc.writeln(subHTML);
}


function NSDisplayText() {
	var ret;
	var menu = NS_path2menu(activepath); // skip 0

	hlitem = -1; // Init

	textdoc= parent.frames['text'].document;
	if(browsertype=="MSIE") { textdoc.open("text/html", "replace"); }
	else { textdoc.open(); }
	NS_textpage(menu);
	textdoc.close();
}

function filter(code){
	var str="";
	// Filter out the non-SGML characters
	if(code == 0x9 || code == 0xA || code == 0xD ||
		(code >= 0x20 && code <=0x7E) ||
		(code >= 0xA0 && code <= 0xD7FF) ||
		(code >= 0xE000 && code <= 0xFFFD) ||
		(code >= 0x10000 && code <= 0x10FFFF)) {
		   	// Escape every character
			str += "&#" + code + ";";
	}
	return str;
}

function purify(text){
	var escapedStr = '';
	if(!text) return "";
	for(i=0;i<text.length;i++) {
		if (text.charAt(i) == '%') {
			escapedStr += text.substring(i, i+3);
			i += 2;
			continue;
		}
		escapedStr += filter(text.charCodeAt(i));
	}
	return escapedStr;
}

//---------------------------------------------------------------------------
// Functions to display address frame window
//---------------------------------------------------------------------------
function NSDisplayAddress() {
	var myap=activepath.replace(/\//g, "\\");
	myap = purify(myap);
	var tt=
'<HTML><HEAD><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' +
'<style type="text/css">' +
'TD' +
'{' +
'MARGIN: 0px 0px 0px 0px;' +
'FONT-FAMILY: Sans-Serif;' +
'FONT-SIZE: 12px;' +
'}' +
'</style>' +
'<script language="javascript">' +
'function getkey(e)' +
'{ if(window.event) return window.event.keyCode;' +
'if(e) return e.which;' +
'else return null; }' +
'function keyPress(enterurl)' +
'{ if(getkey(event)!=13) return;' +
'  parent.frames[\"title\"].enterurl=enterurl;' +
'  parent.frames[\"title\"].ns_handleaddrbar();' +
'}' +
'function dispaddress()' +
'{' +
'var width=document.body.offsetWidth-80;' +
'document.writeln(\'<INPUT TYPE=text value=\"\\\\'+decodeURIComponent(myap)+'\" STYLE=WIDTH:99%;HEIGHT:20px onKeyPress=keyPress(this.value)>\');' +
'}' +
'</script>' +
'</HEAD>' +
'<BODY BGCOLOR=#C7C7C7>' +
'<TABLE WIDTH=100%><TR><TD nowrap>' + _('fsaddress::id_Address') + '</TD><TD WIDTH=100%><LEFT>' +
'<script language="javascript">' +
'dispaddress();' +
'</script>' +
'</LEFT></TD></TR></TABLE>' +
'</BODY>' +
'</HTML>';

	addressdoc= parent.frames['address'].document;
	if(browsertype=="MSIE") { addressdoc.open("text/html", "replace"); }
	else { addressdoc.open(); }
    	addressdoc.writeln(tt);
	addressdoc.close();
}

function ns_handleaddrbar()
{
	var i=0;
	var svr1="";

	if(enterurl.charAt(0)!="\\"||enterurl.charAt(1)!="\\") {
		window.alert(_("nscode::Support file system access only")); 
		enterurl=null;
		return; 
	}
	svr=enterurl.split("\\");
	if(svr.length<=2) { 
		enterurl=null;
		return; 
	}
	for(i=2;i<svr.length;i++) {
		svr1 += "/" + svr[i]; 
	}
	if(NS_ispathexist(svr1)==true) {
		NSUpdateAll(svr1);
		enterurl=null;
	} else {
		frmtext.location.href="/vpns/fsgoto.html"; 
	}
}

//---------------------------------------------------------------------------
// Functions to load unload yet folder information
//---------------------------------------------------------------------------
function NSLoadFolderFromServer() {
	// We need to call NetScaler to download the folder
	if (window.XMLHttpRequest) {
		ajaxClient = new XMLHttpRequest()
	} else if (window.ActiveXObject) {
		ajaxClient = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		return;
	}
	ajaxClient.open("GET", "/fs/ls" + encodeURIComponent(decodeURIComponent(activepath)));
	ajaxClient.onreadystatechange = NSListHandler;
	ajaxClient.send(null);
	frmtext.document.body.innerHTML = _("nscode::Loading...");	
}

//---------------------------------------------------------------------------
// Tool Functions 
//---------------------------------------------------------------------------
function NS_makelink(addText, hasText, id, addURL, clickEvent, mouseOverEvent, mouseOutEvent) {
	var tempString = '<a href="';
	
	if(addURL) tempString += addURL + '" ';
	else tempString += 'javascript:;" ';
	if(id!=0) tempString += 'name=NS' + id + ' ';
	if(clickEvent) {
		tempString += 'onclick="' + clickEvent + '" ';
	}
	if(mouseOverEvent && mouseOverEvent != "") {
		tempString += 'onmouseover="' + mouseOverEvent + '" ';
	}
	if(mouseOutEvent && mouseOutEvent != "") {
		tempString += 'onmouseout="' + mouseOutEvent + '" ';
	}
	tempString += 'target="text" ';
	if(hasText)
	{
		tempString += 'STYLE="TEXT-DECORATION: NONE" ';
	}
	tempString += '>';
	if(addText)
	{
		tempString += addText + '</a>';
	}
	return(tempString);
}

function NS_makeimg(thisImage) {
	return('<img src="/vpns/images/' + thisImage + '" align="left" border="0" vspace="0" hspace="0" width="18" height="18">');
}

function NS_getimg(menuitem) {
	if(menuitem.type==typefolder) return "folder_closed.gif";
	var file=menuitem.text;
	var type=file.substring(file.lastIndexOf("&#46;")+5, file.length);
	if(type=="&#100;&#111;&#99;") return "doc.gif";	// doc
	if(type=="&#116;&#120;&#116;") return "txt.gif";	// txt
	if(type=="&#112;&#112;&#116;") return "ppt.gif";	// ppt
	if(type=="&#120;&#108;&#115;") return "xls.gif";	// xls
	if(type=="&#112;&#100;&#102;") return "pdf.gif";	// pdf
	if(type=="&#104;&#116;&#109;") return "ie.gif";	// htm
	if(type=="&#104;&#116;&#109;&#108;") return "ie.gif";	// html
	if(type=="&#105;&#110;&#105;") return "ini.gif";	// ini
	if(type=="&#106;&#97;&#118;&#97;") return "java.gif";	// java
	if(type=="&#98;&#97;&#116;") return "bat.gif";	// bat
	if(type=="&#101;&#120;&#101;") return "bat.gif";	// exe
	if(type=="&#103;&#105;&#102;") return "bmp.gif";	// gif
	if(type=="&#106;&#112;&#103;") return "bmp.gif";	// jpg
	if(type=="&#106;&#112;&#101;&#103;") return "bmp.gif";	// jpeg
	if(type=="&#98;&#109;&#112;") return "bmp.gif";	// bmp
	if(type=="&#104;&#108;&#112;") return "help.gif";	// hlp
	if(type=="&#100;&#108;&#108;") return "dll.gif";	// dll
	if(type=="&#111;&#99;&#120;") return "dll.gif";	// ocx
	return "win.gif";
}

function NS_url2html(url) {
        var i;
        var out="";
        var a;
        for(i=0; i<url.length; i++) {
                if(url.charAt(i) == '%') {
                    a = parseInt(url.charAt(i+1), 16)*16 + parseInt(url.charAt(i+2), 16);
                    out += "&#" + a + ";";
				i+=2;
                }
                else {
				out += url.charAt(i);
                }
        }
        return out;
}

function NS_html2url(url) {
	var elem = document.createElement("textarea");
	elem.innerHTML = url;
	return elem.value;
}

//---------------------------------------------------------------------------
function NS_getYPos(obj) {
	return (obj.offsetTop + ((obj.offsetParent)?NS_getYPos(obj.offsetParent) : 0));
}

//---------------------------------------------------------------------------
function NS_hl(tritem, i) {
	if(hlitem!=-1) {
		lastTR.className="nsText";
	}
	lastTR=tritem;
	lastTR.className="nsTextHigh";
	hlitem=i;
}

//---------------------------------------------------------------------------
function NS_do_rename(newname) {
	activemenu.items[hlitem].text=newname;
}

function NS_do_del(filename) {
	activemenu.items[hlitem]=null;
}

function NS_dogetfile(url) {
	window.open(url,
		    "_blank", 
		    "toolbar=yes,menubar=yes,status=yes,resizable=yes,scrollbars=yes");
	return false;
}

function fs_logout()
{
	frmtext.document.getElementById("fs_logout_form").submit();
	rootmenu = new NSMenu();
	activepath="";
	activemenu=NS_path2menu(activepath);
	NSDisplayAddress();
	NSDisplayMenu();
}

function NS_cancelAction() {
	hlitem = -1;
	history.go(-1);
}
//---------------------------------------------------------------------------
// Construstor Functions 
//---------------------------------------------------------------------------
if(window.navigator.userAgent.indexOf("MSIE")!=-1) {
	browsertype="MSIE";
} else if(window.navigator.userAgent.indexOf("Netscape")!=-1) {
	browsertype="Netscape";
} else if(window.navigator.userAgent.indexOf("Safari")!=-1) {
	browsertype="Safari";
} else {
	browsertype="unknown";
}
rootmenu = new NSMenu();
// -------------------------------------------------------------------------
