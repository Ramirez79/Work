function ns_createcontrol(NSCOOKIE, NSLOC, NSVER, NSTRACE, NSTDI, NSVIP)
{
  var d = document.getElementById("nsloadbar");
  if(!d) {
	alert('Cant find element nsloadbar');
	return;
  }

  d.innerHTML = 
    '<OBJECT ID="nsload" CLASSID="CLSID:FD7C00A9-E676-11D6-A08E-00E09878F0CF" '+
    'WIDTH=300px HEIGHT=30px CODEBASE="' + file +'#Version=' + NSVER + '">' +
    '<PARAM NAME="cookie" VALUE='+NSCOOKIE+'>'+
    '<PARAM NAME="location" VALUE='+NSLOC+'>'+
    '<PARAM NAME="version" VALUE='+NSVER+'>'+
    '<PARAM NAME="trace" VALUE='+NSTRACE+'>'+
    '<PARAM NAME="tdi" VALUE='+NSTDI+'>'+
    '<PARAM NAME="vip" VALUE='+NSVIP +'>'+
    '<FONT COLOR=#000000><B>&nbsp;3004: '+ _('Err3004') + '</B></FONT>'+
    '</OBJECT>';


}
