<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
  *  Only title, cols & map contain value that will be displayed
  *
  */
class entity_table_definitions
{
    function __construct($useNitro = TRUE)
    {
        $this->entitydef = $this->entitydef_nitro;
    }

    var $entitydef;
    var $entitydef_nitro = Array(
"appfwprofile" =>
		Array(
            "NoEntityMsg"   =>   "<b><c:colorit:\$r=1;>(No Application Firewall Profiles)</c:colorit></b>",
			"title"	        =>	"Application Firewall Profiles",
            "cols"          =>   array('name' =>"<b>Name</b>",
                                       'policies' => "<b>Bound to Policies</b>",
                                       'type_disp' => "<b>Profile Type</b>",),
            "map"           =>   array(
                                    'type_disp'   => array("HTML" => "Web Application (HTML)",
                                                      "HTML XML" => "Web 2.0 Application (HTML, XML REST)",
                                                      "XML" => "XML Application (XML, SOAP)",
                                                      "BUILT-IN" => "Built-in Profile"),
                                    ),
            "command"       =>   "getappfwprofile",
            "col_options"   =>   array('name'=>array('width'=>150),'policies'=>array('width'=>150)),
		),
"appfwprofile_binding" =>
        Array(
            "title"            =>    "Application Firewall Profiles",
            "command"       =>   "getappfwprofile_binding",
        ),
"nsip" =>
        Array(
            "title"            =>    "Netscaler IP",
            "command"       =>   "getnsip",
        ),
"ntpserver" =>
        Array(
            "title"            =>    "NTP Servers",
            "command"       =>   "getntpserver",
        ),

"ntpsync" =>
        Array(
            "title"            =>    "NTP Synchronization data ip",
            "command"       =>   "getntpsync",
        ),
"appfwcustomsettings" =>
        Array(
            "title"            =>    "Signatures",
            "command"       =>   "getappfwcustomsettings",
        ),

"appfwprofilesettings" =>
        Array(
            "title"            =>    "Profile Settings",
            "cols"          =>   array('name' =>"<b>Parameter Name</b>",
                                       'value' => "<b>Value</b>",),
            "map"           =>   array(
                                    'value'   => array(
                                                      "iso-8859-1" => "English US (ISO-8859-1)",
                                                      "big5" => "Chinese Traditional (Big5)",
                                                      "gb2312" => "Chinese Simplified (GB2312)",
                                                      "sjis" => "Japanese (SJIS)",
                                                      "euc-jp" => "Japanese EUC-JP",
                                                      "utf-8" => "unicode UTF-8",
                                                      "euc-kr" => "Korean EUC-KR",
                                                      "iso-8859-9" => "Turkish (iso-8859-9)",
                                                      "apache_mode" => "Apache Mode",
                                                      "asp_mode" => "ASP Mode",
                                                      "secure_mode" => "Secure Mode",
                                                      "if_present" => "If Present",
                                                      "always" => "Always",
                                                      "all" => "All",
                                                      "exclude_script_tag"  => "Exclude Script Tag",
                                                      "none"  => "None"
                                                      ),
                                    ),
        ),
"appfwpolicy" =>
        Array(
            "NoEntityMsg"   =>   "<b><c:colorit:\$r=1;>(No Application Firewall Policies)</c:colorit></b>",
            "command"       =>   "getappfwpolicy",
            "title"         =>   "Application Firewall Policies",
            "cols"          =>   array('name' =>"<b>Policy</b>", 'rule' => "<b>Rule</b>",
                                       'profilename' => "<b>Action</b>",
                                       'hits' => "<b>Hits</b>",
									   'undefhits' => "<b>Undefined Hits</b>",
                                       'activepolicy' => "<b>Active</b>"),
            "col_options"   =>   array('name'=>array('width'=>100),'rule'=>array('width'=>100), 'profilename'=>array('width'=>100)),
        ),
"appfwsettings" =>
        Array(
            "NoEntityMsg"   =>   "<b><c:colorit:\$r=1;>(No Application Firewall Settings)</c:colorit></b>",
            "title"         =>   "Application Firewall Settings",
            "cols"          =>   array('name' =>"<b>Parameter Name</b>",
                                       'value' => "<b>Value</b>",),
            "command"       => "getappfwsettings"
        ),
"creditcard" =>
        Array(
            "NoEntityMsg"   =>   "<b><c:colorit:\$r=1;>(No Application Firewall profile defined to protect credit cards)</c:colorit></b>",
            "title"         =>   "Credit Cards Protection Status",
            "cols"          =>   array('profile' =>"<b>Profile</b>", 'ae' => "<b>American Express</b>",
                                       'dc' => "<b>Diners Club</b>", 'd' => "<b>Discover</b>",
                                       'jcb' => "<b>JCB</b>",        'mc' => "<b>Master Card</b>",
                                       'visa' => "<b>Visa</b>",      'actions' => "<b>Actions</b>",
                                       ),
            "table_width"   =>   "500",
            "action_attr"   =>   "creditcardaction",
            "actions"       =>   "\$this->eval_actions(\$defn['map']['creditcardxout'],\$profile,'creditcardxout')",
            "map"           =>   array(
                                      'creditcardxout' =>  array(
                                                  'ON' => ", X-Out",
                                                  'OFF' => ""),
                                    ),
        ),
"signatures" =>
        Array(
            "cols"          =>   array('id' => "<b>ID</b>", 'actions' => "<b>Actions</b>", 'pattern' =>"<b>Pattern</b>", 'references' =>"<b>References</b>"),
            "col_options"   =>   array('id'=>array('width'=>60), 'actions'=>array('width'=>70), 'pattern'=>array('width'=>250), 'references'=>array('width'=>100)),
        ),
"signatures_with_details" =>
        Array(
            "cols"          =>   array('id' => "<b>ID</b>", 'actions' => "<b>Actions</b>", 'status' => "<b>Enabled</b>", 'pattern' =>"<b>Pattern</b>", 'references' =>"<b>References</b>"),
            "col_options"   =>   array('id'=>array('width'=>60), 'actions'=>array('width'=>70), 'status'=>array('width'=>45), 'pattern'=>array('width'=>230), 'references'=>array('width'=>75)),
        ),
"signatures_same_as_default" =>
        Array(
            "cols"          =>   array('id0' => "<b>ID</b>", 'actions0' => "<b>Actions</b>",
                                       'id1' => "<b>ID</b>", 'actions1' => "<b>Actions</b>",
                                       'id2' => "<b>ID</b>", 'actions2' => "<b>Actions</b>",
                                       'id3' => "<b>ID</b>", 'actions3' => "<b>Actions</b>"
                                       ),
            "col_options"   =>   array('id0'=>array('width'=>40), 'actions0'=>array('width'=>70),
                                       'id1'=>array('width'=>40), 'actions1'=>array('width'=>70),
                                       'id2'=>array('width'=>40), 'actions2'=>array('width'=>70),
                                       'id3'=>array('width'=>40), 'actions3'=>array('width'=>70)
                                       ),
        ),
"starturl" =>
        Array(
            "NoEntityMsg"   =>   "(No Start URLs)",
            "title"         =>   "Start URL Check",
            "cols"          =>   array('state' =>"<b>Enabled</b>", 'starturl' => "<b>Start URL</b>", 'comment' => "<b>Comments</b>"),
            "col_options"   =>   array('starturl'=>array('width'=>250),'comment'=>array('width'=>150)),

            "map"           =>   array(
                                    'state'     => array("ENABLED" => "Yes", "DISABLED" => "No"),
                                    'starturlclosure' => array('ON' => ", Enforce URL Closure", 'OFF' => ""),
                                    'sessionlessurlclosure' => array('ON' => ", Sessionless URL Closure", 'OFF' => ""),
                                    'refererheadercheck' => array('if_present' => ", Check Referer Header If Present",
                                                               'always' => ", Always Check Referer Header",
                                                               'OFF' => "",
                                                               ),
                                    ),
            "action_attr"   =>   "starturlaction",
            "actions"       =>   "\$this->eval_actions(\$defn['map']['starturlclosure'],\$profile,'starturlclosure').
                                  \$this->eval_actions(\$defn['map']['refererheadercheck'],\$profile,'refererheadercheck')
                                  ",
        ),
"denyurl" =>
        Array(
            "NoEntityMsg"   =>   "(No Deny URLs)",
            "title"         =>   "Deny URL Check",
            "cols"          =>   array('state' =>"<b>Enabled</b>", 'denyurl' => "<b>Deny URL</b>", 'comment' => "<b>Comments</b>"),
            "col_options"   =>   array('denyurl'=>array('width'=>200),'comment'=>array('width'=>200)),
            "map"           =>   array('state'     => array("ENABLED" => "Yes", "DISABLED" => "No"),),
            "action_attr"   =>   "denyurlaction",
        ),
"bufferoverflow" =>
        Array(
            "title"         =>   "Buffer Overflow Check",
            "cols"          =>   array('s' =>"<b>Settings</b>", 'ml' => "<b>Maximum Length</b>",),
            "action_attr"   =>   "bufferoverflowaction",
            "data"          =>   "array('s' =>'Maximum URL Length', 'ml' => \$profile['bufferoverflowmaxurllength'],),
                                 array('s' =>'Maximum Cookie Length', 'ml' => \$profile['bufferoverflowmaxcookielength'],),
                                 array('s' =>'Maximum Header Length', 'ml' => \$profile['bufferoverflowmaxheaderlength'],)",

        ),
"cookieconsistency" =>
        Array(
            "title"         =>   "Cookie Protection",
            "NoEntityMsg"   =>   "(No Exempted Cookie Names)",
            "cols"          =>   array('state' =>"<b>Enabled</b>", 'cookieconsistency' => "<b>Cookie Name</b>",
                                       'isregex' => "<b>Regex</b>", 'comment' => "<b>Comments</b>"),
            "col_options"   =>   array('cookieconsistency'=>array('width'=>200),'comment'=>array('width'=>200)),
            "map"           =>   array(
                                    'isregex'   => array("REGEX" => "Yes", "NOTREGEX" => "No"),
                                    'state'     => array("ENABLED" => "Yes", "DISABLED" => "No"),
                                    'cookieencryption' => array('encryptSessionOnly' => ", Encrypt Server Session Cookies",
                                                               'decryptOnly' => ", Decrypt Server Session Cookies",
                                                               'encryptAll' => ", Encrypt All Cookies",
                                                               'none' => ", Server Cookies Not Encrypted",
                                                               ),
                                    'cookieproxying' => array('sessionOnly' => ", Proxy Server Session Cookies",
                                                               'none' => ", Server Cookies Not Proxied",
                                                               ),
                                    'addcookieflags' => array('httpOnly' => ", Add 'HTTP' Flag To Cookies",
                                                               'secure' => ", Add 'Secure' Flag To Cookies",
                                                               'all' => ", Add both 'HTTP' And 'Secure' Flags To Cookies",
                                                               'none' => ", No Flags Added To Cookies",
                                                               ),
                                    ),
            "action_attr"   =>   "cookieconsistencyaction",
            "actions"       =>   "((\$profile['cookietransforms'] == 'ON')? (\$this->eval_actions(\$defn['map']['cookieencryption'],\$profile,'cookieencryption').
                                  \$this->eval_actions(\$defn['map']['cookieproxying'],\$profile,'cookieproxying').
                                  \$this->eval_actions(\$defn['map']['addcookieflags'],\$profile,'addcookieflags')) : 'Cookie Transformation is OFF')",
        ),
"contenttype" =>
        Array(
            "title"         =>   "Content Type Check",
            "NoEntityMsg"   =>   "(No Content Type Checks)",
            "cols"          =>   array('state' =>"<b>Enabled</b>", 'contenttype' => "<b>Content Type</b>", 'comment' => "<b>Comments</b>"),
            "col_options"   =>   array('contenttype'=>array('width'=>250),'comment'=>array('width'=>150)),
            "map"           =>   array(
                                    'state'     => array("ENABLED" => "Yes", "DISABLED" => "No"),
                                    ),
            "action_attr"   =>   "contenttypeaction",
            ),
"fieldconsistency" =>
        Array(
            "title"         =>   "Field Consistency Check",
            "NoEntityMsg"   =>   "(No Exempted Form Fields)",
            "cols"          =>   array('state' =>"<b>Enabled</b>", 'fieldconsistency' => "<b>Field Name</b>",
                                       'isregex_ffc' => "<b>Regex</b>",'formactionurl_ffc'=> '<b>Action URL</b>',
                                       'comment' => "<b>Comments</b>"),
            "col_options"   =>   array('fieldconsistency'=>array('width'=>100),
                                       'formactionurl_ffc'=>array('width'=>150),
                                       'comment'=>array('width'=>150)),
            "map"          =>    array( 'isregex_ffc'   => array("REGEX" => "Yes", "NOTREGEX" => "No"),
                                       'state'     => array("ENABLED" => "Yes", "DISABLED" => "No"),),
            "action_attr"   =>   "fieldconsistencyaction",
	    "map"           =>   array(
                                    'sessionlessfieldconsistency' => array('ON' => ", Check Sessionless Field Consistency ON",
                                                               'OFF' => "",
                                                               'postOnly' => "Check Sessionless Field Consistency postOnly",
                                                               ),
                                    ),
            "actions"       =>   "\$this->eval_actions(\$defn['map']['sessionlessfieldconsistency'],\$profile,'sessionlessfieldconsistency')",
        ),
"fieldformat" =>
        Array(
            "NoEntityMsg"   =>   "(No Field Formats)",
            "title"         =>   "Field Formats Check",
            "cols"          =>   array('state' =>"<b>Enabled</b>", 'fieldformat' => "<b>Field Name</b>",
                                       'isregex_ff' => "<b>Regex</b>", 'formactionurl_ff'=> '<b>Action URL</b>',
                                       'fieldtype' => '<b>Format Type</b>',
                                       'fieldformatminlength' => '<b>Min Length</b>',
                                       'fieldformatmaxlength'=> '<b>Max Length</b>',
                                       'comment' => "Comments"),
            "col_options"   =>   array('fieldformat'=>array('width'=>100),
                                       'formactionurl_ff'=>array('width'=>100),
                                       'fieldformatminlength'=>array('width'=>40),
                                       'fieldformatmaxlength'=>array('width'=>40),
                                       'comment'=>array('width'=>90)),
            "map"           =>   array('isregex_ff'   => array("REGEX" => "Yes", "NOTREGEX" => "No"),
                                       'state'     => array("ENABLED" => "Yes", "DISABLED" => "No"),),
            "action_attr"   =>   "fieldformataction",
        ),
"csrf" =>
        Array(
            "NoEntityMsg"   =>   "(No Exempted Cross-Site Request Forgery Tagging)",
            "title"         =>   "Cross-Site Request Forgery Tagging",
            "cols"          =>   array('state' =>"<b>Enabled</b>", 'csrftag'=> '<b>Form Origin URL</b>',
                                       'csrfformactionurl'=> '<b>Form Action URL</b>', 'comment' => "<b>Comments</b>"),
            "col_options"   =>   array('csrfformoriginurl'=>array('width'=>150),
                                       'csrfformactionurl'=>array('width'=>150),
                                       'comment'=>array('width'=>150)),
            "map"          =>   array(
                                    'state'      => array("ENABLED" => "Yes", "DISABLED" => "No"),
                                    ),
            "action_attr"   =>   "csrftagaction",
        ),
"sqlinjection" =>
        Array(
            "NoEntityMsg"   =>   "(No Exempted HTML SQL Injection Relaxations)",
            "title"         =>   "HTML SQL Injection Check",
            "cols"          =>   array('state' =>"<b>Enabled</b>", 'sqlinjection' => "<b>Field Name</b>", 'isregex_sql' => "<b>Regex</b>",
                                       'formactionurl_sql'=> '<b>Form URL</b>', 'as_scan_location_sql' => "<b>Location</b>", 'comment' => "<b>Comments</b>"),
            "col_options"   =>   array('sqlinjection'=>array('width'=>100),
                                       'formactionurl_sql'=>array('width'=>150),
                                       'comment'=>array('width'=>100)),
            "map"          =>   array(
                                    'isregex_sql'   => array("REGEX" => "Yes", "NOTREGEX" => "No"),
                                    'state'      => array("ENABLED" => "Yes", "DISABLED" => "No"),
                                    'pcom' =>  array(
                                                  'checkall'      => " Check all SQL comments",
                                                  'ansinested'   => " Check ANSI/Nested comments",
                                                  'nested'        => " Check SQL Nested comments",
                                                  'ansi'          => " Check SQL ANSI comments",),
                                    'onlychars' =>  array(
                                                  'ON' => "Restrict checks to fields containing SQL special characters",
                                                  'OFF' => ""),
                                    'trans' => array(
                                                  'ON' => "Transform SQL special characters",
                                                  'OFF' => ""),
				    'wildchars' => array(
                                                  'ON' => "Check for SQL Wildcard Characters ON",
                                                  'OFF' => ""),
                                    ),
            "action_attr"  => "sqlinjectionaction",
            "actions"  => "\$this->eval_actions(\$defn['map']['trans'],\$profile,'sqlinjectiontransformspecialchars').', '.
                          \$this->eval_actions(\$defn['map']['onlychars'],\$profile,'sqlinjectiononlycheckfieldswithsqlchars').', '.
			  \$this->eval_actions(\$defn['map']['wildchars'],\$profile,'sqlinjectionchecksqlwildchars').', '.
                          \$this->eval_actions(\$defn['map']['pcom'],\$profile,'sqlinjectionparsecomments')",
        ),
"crosssitescripting" =>
        Array(
            "NoEntityMsg"   =>   "(No Exempted HTML Cross-Site Scripting Relaxations)",
            "title"         =>   "HTML Cross-Site Scripting Check",
            "cols"          =>   array('state' =>"<b>Enabled</b>", 'crosssitescripting' => "<b>Field Name</b>",
                                       'isregex_xss' => "<b>Regex</b>", 'formactionurl_xss'=> '<b>Form URL</b>',
                                       'as_scan_location_xss' => "<b>Location</b>", 'comment' => "<b>Comments</b>"),
            "col_options"   =>   array('crosssitescripting'=>array('width'=>100),'formactionurl_xss'=>array('width'=>150),'comment'=>array('width'=>100)),
            "map"            =>   array(
                                    'isregex_xss'   => array("REGEX" => "Yes", "NOTREGEX" => "No"),
                                    'state'     => array("ENABLED" => "Yes", "DISABLED" => "No"),
                                    'trans' =>  array(
                                                  'ON' => "Transform cross-site scripts",
                                                  'OFF' => ""),
                                    'urls' =>  array(
                                                  'ON' => "Check complete URLs for cross-site scripting",
                                                  'OFF' => ""),
                                    ),
            "action_attr"  => "crosssitescriptingaction",
            "actions"  => "\$this->eval_actions(\$defn['map']['trans'],\$profile,'crosssitescriptingtransformunsafehtml').', '.
                          \$this->eval_actions(\$defn['map']['urls'],\$profile,'crosssitescriptingcheckcompleteurls')",
        ),
"safeobject" =>
        Array(
            "NoEntityMsg"   =>   "(No Safe Objects)",
            "title"         =>   "Safe Objects",
            "cols"          =>   array('state' =>"<b>Enabled</b>",
                                       'safeobject' => "<b>Name</b>",
                                       'action'=> '<b>Actions</b>',
                                       'expression' => "<b>Expression</b>",
                                       'maxmatchlength' => '<b>Max Match Length</b>',
                                       'comment' => "<b>Comments</b>"),
            "col_options"   =>   array('safeobject'=>array('width'=>100),
                                       'expression'=>array('width'=>150),
                                       'action'=>array('width'=>60),
                                       'maxmatchlength'=>array('width'=>40),
                                       'comment'=>array('width'=>100)),
            "map"          =>    array('state'     => array("ENABLED" => "Yes", "DISABLED" => "No"),
                                       'action'     => array("block"   => "Block",
                                                           "log"     => "Log",
                                                           "remove"  => "Remove",
                                                           "stats"   => "Stats",
                                                           "xout"    => "X-Out",),
                                                           ),
        ),
"xmlformat" =>
        Array(
            "title"         =>   "XML Format Check",
            "action_attr"  => "xmlformataction",
        ),
"xmlcrosssitescripting" =>
        Array(
            "title"         =>   "XML Cross-Site Scripting Check",
            "action_attr"   =>   "xmlxssaction",
            "NoEntityMsg"   =>   "(No Exempted XML Cross-Site Scripting Relaxations)",
            "cols"          =>   array('state' =>"<b>Enabled</b>", 'xmlxss' => "<b>Field Name</b>",
                                       'isregex_xmlxss' => "<b>Regex</b>", 'as_scan_location_xmlxss' => "<b>Location</b>", 'comment' => "<b>Comments</b>"),
            "col_options"   =>   array('xmlxss'=>array('width'=>150),'comment'=>array('width'=>100)),
            "map"           =>   array(
                                    'isregex_xmlxss'   => array("REGEX" => "Yes", "NOTREGEX" => "No"),
                                    'state'     => array("ENABLED" => "Yes", "DISABLED" => "No"),
                                    ),
        ),
"xmlsoapfault" =>
        Array(
            "title"         =>   "XML SOAP Fault Filtering",
            "action_attr"  => "xmlsoapfaultaction",
        ),

"xmlwsi" =>
        Array(
            "title"         =>   "WS-I Check",
            "cols"          =>   array('state' => "<b>Enabled</b>",
                                       'ruleid' => "<b>Rule ID</b>",
                                       'rule'=> '<b>Rule</b>',),
            "action_attr"  => "xmlwsiaction",
            "table_width"   =>   "500",
        ),
"xmlvalidation" =>
        Array(
            "title"         =>   "XML Message Validation",
            "action_attr"  => "xmlvalidationaction",
            "map"           =>   array('xmlendpointcheck'     => array("ABSOLUTE" => "Absolute",
                                                                       "RELATIVE" => "Relative"),),
        ),
"xmlattachment" =>
        Array(
            "title"         =>   "XML Attachment Check",
            "cols"          =>   array('state' =>"<b>Enabled</b>",
                                       'name' => "<b>Check Name</b>",
                                       'val' => "<b>Check Value</b>",),
            "action_attr"  => "xmlattachmentaction",
            "map"           =>   array('state'     => array("ON" => "Yes", "OFF" => "No"),),
            "data"          =>   "array('state' =>\$profile['xmlmaxattachmentsizecheck'],
                                        'name' => 'Maximum Attachment Size',
                                        'val' => \$profile['xmlmaxattachmentsize'],),
                                  array('state' =>\$profile['xmlattachmentcontenttypecheck'],
                                        'name' => 'Attachment Content Type',
                                        'val' => \$profile['xmlattachmentcontenttype'],)",

            ),
"xmlsqlinjection" =>
        Array(
            "title"         =>   "XML SQL Injection Check",
            "NoEntityMsg"   =>   "(No Exempted XML SQL Injection Relaxations)",
            "cols"          =>   array('state' =>"<b>Enabled</b>", 'xmlsqlinjection' => "<b>Name</b>",
                                       'isregex_xmlsql' => "<b>Regex</b>", 'as_scan_location_xmlsql' => "<b>Location</b>", 'comment' => "<b>Comments</b>"),
            "col_options"   =>   array('xmlsqlinjection'=>array('width'=>150),'comment'=>array('width'=>100)),
            "map"           =>   array(
                                    'pcom' =>  array(
                                                  'checkall'      => " Check all SQL comments",
                                                  'ansinested'   => " Check ANSI/Nested comments",
                                                  'nested'        => " Check SQL Nested comments",
                                                  'ansi'          => " Check SQL ANSI comments",),
                                    'onlychars' =>  array(
                                                  'ON' => "Restrict checks to fields containing SQL special characters",
                                                  'OFF' => ""),
                                    'isregex_xmlsql'   => array("REGEX" => "Yes", "NOTREGEX" => "No"),
                                    'state'     => array("ENABLED" => "Yes", "DISABLED" => "No"),
				    'wildchars' => array(
                                                  'ON' => "Check for SQL Wildcard Characters ON",
                                                  'OFF' => ""),
                                    ),
            "action_attr"  => "xmlsqlinjectionaction",
            "actions"       => "\$this->eval_actions(\$defn['map']['onlychars'],\$profile,'xmlsqlinjectiononlycheckfieldswithsqlchars').', '.
				\$this->eval_actions(\$defn['map']['wildchars'],\$profile,'xmlsqlinjectionchecksqlwildchars').', '.
                               \$this->eval_actions(\$defn['map']['pcom'],\$profile,'xmlsqlinjectionparsecomments')",
        ),
"appfwglobal" =>
        Array(
            "title"         =>   "Application Firewall Global Binding",
            "command"       =>   "getappfwglobal",
        ),
"nsfeature" =>
        Array(
            "title"         =>   "Feature",
            "command"       =>   "getnsfeature",
        ),
"nsdefaultpasschanged" =>
        Array(
            "title"         =>   "Default Password Changed",
            "command"       =>   "getnsdefaultpasschanged",
        ),
"xdoscheck" =>
        Array(
            "title"         =>   "XDoS Check",
            "cols"          =>   array('state' =>"<b>Enabled</b>",
                                       'name' => "<b>Check Name</b>",
                                       'val' => "<b>Check Value</b>",),
            "action_attr"  => "xmldosaction",
            "map"          =>   array('state'     => array("ON" => "Yes", "OFF" => "No"),),
            "data"         =>   "array('state' => \$profile['xmlmaxelementdepthcheck'],
                                      'name' => 'Maximum Element Depth',
                                      'val' => \$profile['xmlmaxelementdepth'],),
                                array('state' => \$profile['xmlmaxelementnamelengthcheck'],
                                      'name' => 'Maximum Element Name Length',
                                      'val' => \$profile['xmlmaxelementnamelength'],),
                                array('state' => \$profile['xmlmaxelementscheck'],
                                      'name' => 'Maximum # Elements',
                                      'val' => \$profile['xmlmaxelements'],),
                                array('state' => \$profile['xmlmaxelementchildrencheck'],
                                      'name' => 'Maximum # Element Children',
                                      'val' => \$profile['xmlmaxelementchildren'],),
                                array('state' => \$profile['xmlmaxattributescheck'],
                                      'name' => 'Maximum # Attributes',
                                      'val' => \$profile['xmlmaxattributes'],),
                                array('state' => \$profile['xmlmaxattributenamelengthcheck'],
                                      'name' => 'Maximum Attribute Name Length',
                                      'val' => \$profile['xmlmaxattributenamelength'],),
                                array('state' => \$profile['xmlmaxattributevaluelengthcheck'],
                                      'name' => 'Maximum Attribute Value Length',
                                      'val' => \$profile['xmlmaxattributevaluelength'],),
                                array('state' => \$profile['xmlmaxchardatalengthcheck'],
                                      'name' => 'Maximum Character Data Length',
                                      'val' => \$profile['xmlmaxchardatalength'],),
                                array('state' => \$profile['xmlmaxfilesizecheck'],
                                      'name' => 'Maximum File Size',
                                      'val' => \$profile['xmlmaxfilesize'],),
                                array('state' => \$profile['xmlminfilesizecheck'],
                                      'name' => 'Minimum File Size',
                                      'val' => \$profile['xmlminfilesize'],),
                                array('state' => \$profile['xmlmaxentityexpansionscheck'],
                                      'name' => 'Maximum # Entity Expansions',
                                      'val' => \$profile['xmlmaxentityexpansions'],),
                                array('state' => \$profile['xmlmaxentityexpansiondepthcheck'],
                                      'name' => 'Maximum Entity Expansion Depth',
                                      'val' => \$profile['xmlmaxentityexpansiondepth'],),
                                array('state' => \$profile['xmlmaxnamespacescheck'],
                                      'name' => 'Maximum # Namespaces',
                                      'val' => \$profile['xmlmaxnamespaces'],),
                                array('state' => \$profile['xmlmaxnamespaceurilengthcheck'],
                                      'name' => 'Maximum Namespace URI Length',
                                      'val' => \$profile['xmlmaxnamespaceurilength'],),
                                array('state' => \$profile['xmlblockpi'],
                                      'name' => 'Block Processing Instructions',
                                      'val' => '-',),
                                array('state' => \$profile['xmlblockdtd'],
                                      'name' => 'Block DTD',
                                      'val' => '-',),
                                array('state' => \$profile['xmlblockexternalentities'],
                                      'name' => 'Block External Entities',
                                      'val' => '-',),
                                array('state' => \$profile['xmlsoaparraycheck'],
                                      'name' => 'SOAP Array Check',
                                      'val' => '-',),
                                array('state' => '',
                                      'name' => '    Maximum SOAP Array Size',
                                      'val' => \$profile['xmlmaxsoaparraysize'],),
                                array('state' => '',
                                      'name' => '    Maximum SOAP Array Rank',
                                      'val' => \$profile['xmlmaxsoaparrayrank'],),
                                      "
                                      ,
            ),
"appfwconfidfield"          =>
        Array(
            "command"       =>   "getappfwconfidfield",
            "NoEntityMsg"   =>   "(No Confidential Fields defined)",
            "title"         =>   "Confidential Fields not to be displayed",
            "cols"          =>   array('url'    =>"<b>URL</b>",
                                       'fieldname' => "<b>Field Name</b>",
                                       'isregex'   => "<b>Regex</b>",
                                       'state'     => "<b>Enabled</b>",
                                       'comment'   => "<b>Comments</b>"),
            "col_options"   =>   array('url'=>array('width'=>150),'fieldname'=>array('width'=>100),'comment'=>array('width'=>150)),
            "map"           =>   array('isregex'   => array("REGEX" => "Yes", "NOTREGEX" => "No"),
                                       'state'     => array("ENABLED" => "Yes", "DISABLED" => "No"),),
        ),
"appfwProfileExecutiveSummary" =>
        Array(
            "cols"          =>   array('num' =>"<b>#</b>",
                                       'requirement' => "<b>Requirement</b>",
                                       'remarks' => "<b>Remarks</b>"),),
	);

    var $appfwProfileExecutiveSummaryData = array(
    array('num' =>"1",
    'requirement' => "Install and maintain a firewall configuration to protect cardholder data",
    'remarks' => "Not Applicable"
    ),
    array('num' =>"<b>2</b>",
    'requirement' => "<b>Do not use vendor-supplied defaults for system passwords and other security parameters</b>",
    'remarks' => "<b>See below</b>"
    ),
    array('num' =>"<b>3</b>",
    'requirement' => "<b>Protect stored cardholder data</b>",
    'remarks' => "<b>See below</b>"
    ),
    array('num' =>"4",
    'requirement' => "Encrypt transmission of cardholder data across open, public networks",
    'remarks' => "Not Applicable"
    ),
    array('num' =>"5",
    'requirement' => "Use and regularly update anti-virus software or programs",
    'remarks' => "Not Applicable"
    ),
    array('num' =>"<b>6</b>",
    'requirement' => "<b>Develop and maintain secure systems and applications</b>",
    'remarks' => "<b>See below</b>"
    ),
    array('num' =>"7",
    'requirement' => "Restrict access to cardholder data by business need to know",
    'remarks' => "Not Applicable"
    ),
    array('num' =>"<b>8</b>",
    'requirement' => "<b>Assign a unique ID to each person with computer access</b>",
    'remarks' => "<b>See below</b>"
    ),
    array('num' =>"9",
    'requirement' => "Restrict physical access to cardholder data",
    'remarks' => "Not Applicable"
    ),
    array('num' =>"<b>10</b>",
    'requirement' => "<b>Track and monitor all access to network resources and cardholder data</b>",
    'remarks' => "<b>See below</b>"
    ),
    array('num' =>"11",
    'requirement' => "Regularly test security systems and processes",
    'remarks' => "Not Applicable"
    ),
    array('num' =>"12",
    'requirement' => "Maintain a policy that addresses information security for employees and contractors",
    'remarks' => "Not Applicable"
    ),
    );

    public function getDefinition($funname) {
		  if(!isset($this->entitydef[$funname]))
			return false;
		  return $this->entitydef[$funname];
	 }
     public function getAppfwProfileExecutiveSummaryData() {
         return $this->appfwProfileExecutiveSummaryData;
     }
  }
?>
