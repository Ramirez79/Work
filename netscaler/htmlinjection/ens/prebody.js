<script language='javascript1.1' type="text/javascript">
// Script contents Copyright 2007-2016 Citrix Systems Inc., and implementation covered by international intellectual property laws.  
// change nothing below here
var g_csma_Start = _csma_NSISOD(new Date());
var _csma_NS_IID='%%SYS.IID%%';
var _vsvrname = _csma_trimmer('%%SYS.VSERVER%%');
var _vsvr_svc = _csma_trimmer('%%SYS.VSERVER.SERVICE%%');
function _csma_trimmer(s)
{
  var cB = s.indexOf(")");
  return s.substring(0,cB+1);
}
var g_csma_NS_PSD='&iid='+_csma_NS_IID+'&xid=%%HTTP.XID%%&esnsid=%%ES4NS.PAGEID%%&reqrtb=%%HTTP.REQ.RECV_TIME_BEG%%&reqstb=%%HTTP.REQ.SEND_TIME_BEG%%&resrtb=%%HTTP.RES.RECV_TIME_BEG%%&resstb=%%HTTP.RES.SEND_TIME_BEG%%&vsvrname='+ _vsvrname + '&vsvr_svc=' + _vsvr_svc;var g_csma_PageEndData='';var g_csma_i=0;var g_csma_c=0;
function _csma_NSGB(e){document.cookie='NSC_ESNS=reset; expires=Thu, 01 Jan 1970 00:00:01 GMT;';_csma_NSLR('test.aspx?s=x');}
function _csma_NSI(e){_csma_NSLR('test.aspx?s=i');g_csma_i=1;}
function _csma_NSDC(e){_csma_NSLR('test.aspx?s=c');g_csma_c=1;}
function _csma_NSDL(e){if(document.readyState=="complete"){_csma_NSDC(e);}else if(document.readyState=="interactive"){_csma_NSI(e);}}
function _csma_NSTC(o){var d=50,c=100,i=0;for(i=0;i<c;i++){if(!o.complete){setTimeout('return;',d);}else {break;}}}
function _csma_NSISOD(dt){return ''+dt.getUTCFullYear()+'-'+(dt.getUTCMonth()+1)+'-'+dt.getUTCDate()+'T'+dt.getUTCHours()+':'+dt.getUTCMinutes()+':'+dt.getUTCSeconds()+'.'+dt.getUTCMilliseconds()+'Z'+dt.getTimezoneOffset();}
if(window.attachEvent&&document.attachEvent){document.attachEvent('onreadystatechange',_csma_NSDL);window.attachEvent('onbeforeunload',_csma_NSGB);} else if(window.addEventListener){window.addEventListener('DOMContentLoaded',_csma_NSI,false );window.addEventListener('load',_csma_NSDC,false);window.addEventListener('unload',_csma_NSGB,false);}
function _csma_NSSS(o,t){ o.src='/ens10/app/ext/suser/'+t; }
function _csma_NSLR(m){var s=new String(document.location);var o=new Image ();if(o){var dt=new Date();var l=s.indexOf('?');var p=(l<0)?document.location:s.substr(0,l);_csma_NSSS(o,m+'&p='+escape(p)+'&dt='+_csma_NSISOD(dt)+'&st='+g_csma_Start+'&fi='+g_csma_i+'&fc='+g_csma_c+g_csma_NS_PSD+g_csma_PageEndData);_csma_NSTC(o);}}
</script>
