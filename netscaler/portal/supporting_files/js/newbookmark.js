function makeJSsafe(text) {
    
    // First replacement in this array has to be '&', else we will encode same character multiple times
    var replaceTable =[ 
        {"original" :/&/g , "replacement": '&amp;' },
        {"original" :/</g , "replacement": '&lt;' },
        {"original" :/>/g , "replacement": '&gt;' },
        {"original" :/"/g , "replacement": '&quot;' },
        {"original" :/'/g , "replacement": '&apos;' }
    ];
        
    for( i= 0; i<replaceTable.length; i++) {
        text = text.replace(replaceTable[i]["original"], replaceTable[i]["replacement"])
    }
    return text
}

function removeSpaces(text){
    text = text.replace(/^\s+|\s+$/gm,"");  //We are just removing leading an trailing spaces.
    return text;
}
function showRdpcheckbox(){
    if(HomePageConfig.UserDefinedRDP.Enabled == "True"){
        document.writeln("<tr><td><table><tbody><tr>");
        document.writeln("<td class=\"rdpCheckbox\"><input type=\"checkbox\" name=\"isRDP\" value=\"\" tabindex=\"1\"><span id=\"RDPLink\" class=\"bookmark_text\"></span></td></tr>");
        document.writeln("</tbody></table></td></tr>");
    }   
}
function bmCheck(){
    var url=document.forms['newbm'].url.value;
    if(url == "") {
        window.alert(_("You must provide a URL for your bookmark."));
        return false;
    }
    if(url.indexOf('\\')==0){
        var pattern= /[?*><\|/:\"%]/;
        if(url.indexOf('\\\\')!= 0){ //Shouldn't contain a single '\' at start
                window.alert(_("Incorrect_FS_address"));
                return false;
                }
        if(url.charAt(2) == "\\" || url.search(pattern) != -1){ //The third charcter shouldn't be a '\' and should should match the regex pattern.
            window.alert(_("Incorrect_FS_address"));
            return false;
        }
    }

    if(document.forms['newbm'].isRDP)
        var isRDP = document.forms['newbm'].isRDP.checked;
    if(isRDP){
        url = "rdp://" + url ;
    }
    // Don't reject input, if it has used unallowed character. Just escape it.
    
    // For URL, we can use function encodeURI to escape any unsafe characters
    // encodeURI encodes '\' character also. But for supporting fileshare links, we shouldn't encode it.
    // So, if URL starts with '\\', encode everything except first two '\' characters
    if(url.indexOf('\\\\')== 0) {
        document.forms['newbm'].url.value = '\\\\' + encodeURI(url.substring(2));
    }else{
        document.forms['newbm'].url.value = encodeURI(url);
    }
    
    // For text, there is no standard function. So convert unsafe characters to HTML entities using makeJSsafe function call. 
    // After encoding, they can be added to webpage without XSS vulnerability
    document.forms['newbm'].prompt.value    = makeJSsafe(prompt);
    document.forms['newbm'].desc.value      = makeJSsafe(desc);
    return true;
}

function ns_unload() {
  window.opener.ns_reload();
}
