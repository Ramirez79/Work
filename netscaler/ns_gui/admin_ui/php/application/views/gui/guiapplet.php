<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
$jar_dir = "/admin_ui/php/application/views/applets";

$jar_dir_for_size=$_SERVER['DOCUMENT_ROOT'] . "/admin_ui/php/application/views/applets/";

$gui_images = filesize($jar_dir_for_size . "gui_images.jar.pack.gz");
$gson = filesize($jar_dir_for_size . "gson.jar.pack.gz");
$looks = filesize($jar_dir_for_size . "looks.jar.pack.gz");
$l2fprod_common_tasks = filesize($jar_dir_for_size . "l2fprod-common-tasks.jar.pack.gz");
$sinetfactory = filesize($jar_dir_for_size . "sinetfactory.jar.pack.gz");
$sslava = filesize($jar_dir_for_size . "sslava.jar.pack.gz");
$java40 = filesize($jar_dir_for_size . "java40.jar.pack.gz");
$commons_codec = filesize($jar_dir_for_size . "commons-codec.jar.pack.gz");
$prefuse = filesize($jar_dir_for_size . "prefuse.jar.pack.gz");
$pixl = filesize($jar_dir_for_size . "pixl.jar.pack.gz");
$guicommon = filesize($jar_dir_for_size . "guicommon.jar.pack.gz");
$ns = filesize($jar_dir_for_size . "ns.jar.pack.gz");
$gui_view1 = filesize($jar_dir_for_size . "gui_view1.jar.pack.gz");
$gui_view2 = filesize($jar_dir_for_size . "gui_view2.jar.pack.gz");
$gui_view3 = filesize($jar_dir_for_size . "gui_view3.jar.pack.gz");
$gui_view4 = filesize($jar_dir_for_size . "gui_view4.jar.pack.gz");
$gui_view5 = filesize($jar_dir_for_size . "gui_view5.jar.pack.gz");
$gui_view6 = filesize($jar_dir_for_size . "gui_view6.jar.pack.gz");
$gui_view7 = filesize($jar_dir_for_size . "gui_view7.jar.pack.gz");
$gui = filesize($jar_dir_for_size . "gui.jar.pack.gz");

?>

<script type="text/javascript">
    //Don't show "Loading" in Opera browser - to accept security warnings
    if(window.opera)
        set_state_loading_content(false);

    function doActionOnLoad()
    {
        setAppletHeight("ConfigApplet");
        scheduleKeepAlive();
    }

    function doActionOnResize()
    {
        setAppletHeight("ConfigApplet");
    }

    function doActionOnBeforeUnload(){
        try
        {
            var configSaved = document.applets["ConfigApplet"].isConfigSavedFromJS();
            if(!configSaved)
            {
                if(window.confirm("Click OK to save running configuration and proceed.\nClick Cancel to continue without saving the configuration."))
                {
                    var message = document.applets["ConfigApplet"].saveConfigFromJS();
                    if(message != null && message != "")
                    {
                	    alert("Save config failed, Reason: " + message);
                    }
                }
            }
        }
        catch(err) {}
    }

    function doActionOnUnLoad()
    {
	try
	{
	    var config_applet = document.applets["ConfigApplet"];
	    if((typeof(config_applet) != "undefined" && config_applet != null))
	    {
		config_applet.stop();
	    }       
	}
	catch(e)
	{
	    
	}
    }

	<? require_once(APPPATH. "views/common/plugin_check.php") ?>

</script>
<script type="text/javascript"><!--
	var jar_files = "\"<?= $jar_dir;?>/guiquickloader.jar,<?= $jar_dir;?>/gui_images.jar,<?= $jar_dir;?>/gson.jar,<?= $jar_dir;?>/looks.jar,<?= $jar_dir;?>/l2fprod-common-tasks.jar,<?= $jar_dir;?>/sinetfactory.jar,<?= $jar_dir;?>/sslava.jar,<?= $jar_dir;?>/java40.jar,<?= $jar_dir;?>/commons-codec.jar,<?= $jar_dir;?>/prefuse.jar,<?= $jar_dir;?>/pixl.jar,<?= $jar_dir;?>/ns.jar,<?= $jar_dir;?>/gui_view1.jar,<?= $jar_dir;?>/gui_view2.jar,<?= $jar_dir;?>/gui_view3.jar,<?= $jar_dir;?>/gui_view4.jar,<?= $jar_dir;?>/gui_view5.jar,<?= $jar_dir;?>/gui_view6.jar,<?= $jar_dir;?>/gui_view7.jar,<?= $jar_dir;?>/guicommon.jar,<?= $jar_dir;?>/gui.jar\"";
	function print_params()
	{
		document.writeln('<PARAM NAME="appletToLoad" VALUE="ns.im.Gui">');
		document.writeln('<PARAM NAME="loadingMessage" VALUE="Loading Configuration Utility. Please wait...">');
		document.writeln('<PARAM NAME="raw_files" VALUE="folder.png,pixl/pi_syntax.xml">');
		document.writeln('<PARAM NAME="raw_files_jar_size" VALUE="<?= $gui_images;?>,<?= $pixl;?>">');
		document.writeln('<PARAM NAME="class_files" VALUE="com.google.gson.Gson,com.jgoodies.looks.FontPolicy,com.l2fprod.common.swing.JLinkButton,com.jscape.inet.terminal.Terminal,crysec.PrivateKey,netscape.javascript.JSObject,org.apache.commons.codec.BinaryDecoder,prefuse.Display,ns.branding.Branding_NS,ns.model.resrc.LBVServer,ns.im.view.A,ns.im.view.J,ns.im.view.K,ns.im.view.O,ns.im.view.Y,ns.im.view.Z,ns.im.view.W,ns.im.Gui" >');
		document.writeln('<PARAM NAME="class_jar_files_size" VALUE="<?= $gson;?>,<?= $looks;?>,<?= $l2fprod_common_tasks;?>,<?= $sinetfactory;?>,<?= $sslava;?>,<?= $java40;?>,<?= $commons_codec;?>,<?= $prefuse;?>,<?= $guicommon;?>,<?= $ns;?>,<?= $gui_view1;?>,<?= $gui_view2;?>,<?= $gui_view3;?>,<?= $gui_view4;?>,<?= $gui_view5;?>,<?= $gui_view6;?>,<?= $gui_view7;?>,<?= $gui;?>" >');
		document.writeln('<PARAM NAME="code" VALUE = "ns/QuickAppletLoader.class">');
		document.writeln('<PARAM NAME="cache_archive" VALUE = ' + jar_files + '>');
		document.writeln('<PARAM NAME="cache_option"  VALUE = "Plugin" >');
		if(_is_ubuntu == true)
		    document.writeln('<PARAM NAME="classloader_cache"  VALUE = "false" >');
		else
		    document.writeln('<PARAM NAME="separate_jvm"  VALUE = "true" >');
		document.writeln('<PARAM NAME="scriptable" VALUE="true">');
		document.writeln('<PARAM NAME="ns1" VALUE="<?= $ns1;?>">');
		document.writeln('<PARAM NAME="ns2" VALUE="<?= $ns2;?>">');
		document.writeln('<PARAM NAME="ns3" VALUE="<?= $ns3;?>">');
		document.writeln('<PARAM NAME="ns4" VALUE="<?= $ns4;?>">');
		document.writeln('<PARAM NAME="ns5" VALUE="<?= $ns5;?>">');
		document.writeln('<PARAM NAME="ns7" VALUE="<?= $ns7;?>">');
		document.writeln('<PARAM NAME="ns8" VALUE="<?= $ns8;?>">');
		document.writeln('<PARAM NAME="ns9" VALUE="<?= $ns9;?>">');
		document.writeln('<PARAM NAME="ns10" VALUE="<?= $ns10;?>">');
        <?php
            if($jvm_memory != "system_default")
            {
        ?>
                document.writeln('<PARAM NAME="java_arguments" VALUE="-Xms<?=$jvm_memory?> -Xmx<?=$jvm_memory?>">');
        <?php
            }
        ?>
		<?php
			if(config_item("disable_ns_version_check"))
			{
		?>
				document.writeln('<PARAM NAME="ns11" VALUE="true">');
		<?php
			}
		?>
		<?php
            if(isset($node_name))
            {
        ?>
                document.writeln('<PARAM NAME="node_name" VALUE="<?=$node_name?>">');
        <?php
            }
        ?>

	<?php
             if(isset($detail_view) && isset($detail_view_params) && isset($detail_view_title))
             {
         ?>
                 document.writeln('<PARAM NAME="detail_view" VALUE="<?=$detail_view?>">');
                 document.writeln('<PARAM NAME="detail_view_params" VALUE="<?=$detail_view_params?>">');
                 document.writeln('<PARAM NAME="detail_view_title" VALUE="<?=$detail_view_title?>">');
         <?php
             }
         ?>
	}

	pluginURL = "\"" + pluginURL + "\"";
    if (_ie == true)//internet explorer
    {
	 	document.writeln('<div id="loading">');
        document.writeln('</p></div>');
       	document.writeln('<OBJECT classid="clsid:' + classid + '" WIDTH = 100% HEIGHT = 100% codebase=' + pluginURL + ' name="ConfigApplet">');

        print_params();
		document.writeln('</OBJECT>');
    }
    else if (_firefox == true && !jre_on_mozilla)//Mozilla w/o JRE
    {
		document.writeln('<EMBED type="application/x-java-applet;version=1.6" WIDTH = 100% HEIGHT = 100% pluginspage=' + pluginURL + '>');
		document.writeln('</EMBED>');
    }
    else//Chrome, Safari and Mozilla with JRE etc
    {
    	document.writeln('<APPLET CODE = ns.QuickAppletLoader archive = ' + jar_files + ' WIDTH = 100% HEIGHT = 100% name="ConfigApplet" MAYSCRIPT>');
		print_params();
		document.writeln('</APPLET>');
    }

//--></script>