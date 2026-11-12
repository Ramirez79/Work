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

	var jar_files = "<?= $jar_dir;?>/guiquickloader.jar,<?= $jar_dir;?>/gui_images.jar,<?= $jar_dir;?>/gson.jar,<?= $jar_dir;?>/looks.jar,<?= $jar_dir;?>/l2fprod-common-tasks.jar,<?= $jar_dir;?>/sinetfactory.jar,<?= $jar_dir;?>/sslava.jar,<?= $jar_dir;?>/java40.jar,<?= $jar_dir;?>/commons-codec.jar,<?= $jar_dir;?>/prefuse.jar,<?= $jar_dir;?>/pixl.jar,<?= $jar_dir;?>/ns.jar,<?= $jar_dir;?>/gui_view1.jar,<?= $jar_dir;?>/gui_view2.jar,<?= $jar_dir;?>/gui_view3.jar,<?= $jar_dir;?>/gui_view4.jar,<?= $jar_dir;?>/gui_view5.jar,<?= $jar_dir;?>/gui_view6.jar,<?= $jar_dir;?>/gui_view7.jar,<?= $jar_dir;?>/guicommon.jar,<?= $jar_dir;?>/gui.jar";
	function print_params(container)
	{
                $(container).append($("<PARAM>").attr("NAME", "appletToLoad").attr("VALUE", "ns.im.Gui"));
                $(container).append($("<PARAM>").attr("NAME", "loadingMessage").attr("VALUE", "Loading Configuration Utility. Please wait..."));
                $(container).append($("<PARAM>").attr("NAME", "raw_files").attr("VALUE", "folder.png,pixl/pi_syntax.xml"));
		$(container).append($("<PARAM>").attr("NAME", "raw_files_jar_size").attr("VALUE", "<?= $gui_images;?>,<?= $pixl;?>"));
		$(container).append($("<PARAM>").attr("NAME", "class_files").attr("VALUE", "com.google.gson.Gson,com.jgoodies.looks.FontPolicy,com.l2fprod.common.swing.JLinkButton,com.jscape.inet.terminal.Terminal,crysec.PrivateKey,netscape.javascript.JSObject,org.apache.commons.codec.BinaryDecoder,prefuse.Display,ns.branding.Branding_NS,ns.model.resrc.LBVServer,ns.im.view.A,ns.im.view.J,ns.im.view.K,ns.im.view.O,ns.im.view.Y,ns.im.view.Z,ns.im.view.W,ns.im.Gui"));
		$(container).append($("<PARAM>").attr("NAME", "class_jar_files_size").attr("VALUE", "<?= $gson;?>,<?= $looks;?>,<?= $l2fprod_common_tasks;?>,<?= $sinetfactory;?>,<?= $sslava;?>,<?= $java40;?>,<?= $commons_codec;?>,<?= $prefuse;?>,<?= $guicommon;?>,<?= $ns;?>,<?= $gui_view1;?>,<?= $gui_view2;?>,<?= $gui_view3;?>,<?= $gui_view4;?>,<?= $gui_view5;?>,<?= $gui_view6;?>,<?= $gui_view7;?>,<?= $gui;?>"));
		$(container).append($("<PARAM>").attr("NAME", "code").attr("VALUE", "ns/QuickAppletLoader.class"));
                $(container).append($("<PARAM>").attr("NAME", "cache_archive").attr("VALUE", jar_files));
		$(container).append($("<PARAM>").attr("NAME", "cache_option").attr("VALUE", "Plugin"));
		if(_is_ubuntu == true)
                    $(container).append($("<PARAM>").attr("NAME", "classloader_cache").attr("VALUE", "false"));
		else
                    $(container).append($("<PARAM>").attr("NAME", "separate_jvm").attr("VALUE", "true"));
		$(container).append($("<PARAM>").attr("NAME", "scriptable").attr("VALUE", "true"));
                
                $(container).append($("<PARAM>").attr("NAME", "ns1").attr("VALUE", "<?= htmlentities($ns1);?>"));
                $(container).append($("<PARAM>").attr("NAME", "ns2").attr("VALUE", "<?= htmlentities($ns2);?>"));
                $(container).append($("<PARAM>").attr("NAME", "ns3").attr("VALUE", "<?= htmlentities($ns3);?>"));
                $(container).append($("<PARAM>").attr("NAME", "ns4").attr("VALUE", "<?= htmlentities($ns4);?>"));
                $(container).append($("<PARAM>").attr("NAME", "ns5").attr("VALUE", "<?= htmlentities($ns5);?>"));
                $(container).append($("<PARAM>").attr("NAME", "ns7").attr("VALUE", "<?= htmlentities($ns7);?>"));
                $(container).append($("<PARAM>").attr("NAME", "ns8").attr("VALUE", "<?= htmlentities($ns8);?>"));
                $(container).append($("<PARAM>").attr("NAME", "ns9").attr("VALUE", "<?= htmlentities($ns9);?>"));
                $(container).append($("<PARAM>").attr("NAME", "ns10").attr("VALUE", "<?= htmlentities($ns10);?>"));
        <?php
            if($jvm_memory != "system_default")
            {
        ?>
            $(container).append($("<PARAM>").attr("NAME", "java_arguments").attr("VALUE", "-Xms<?=$jvm_memory?> -Xmx<?=$jvm_memory?>"));
        <?php
            }
        ?>
		<?php
			if(config_item("disable_ns_version_check"))
			{
		?>
                            $(container).append($("<PARAM>").attr("NAME", "ns11").attr("VALUE", "true"));
		<?php
			}
		?>
		<?php
            if(isset($node_name))
            {
        ?>
                $(container).append($("<PARAM>").attr("NAME", "node_name").attr("VALUE", "<?=$node_name?>"));
        <?php
            }
        ?>

	<?php
             if(isset($detail_view) && isset($detail_view_params) && isset($detail_view_title))
             {
         ?>
                $(container).append($("<PARAM>").attr("NAME", "detail_view").attr("VALUE", "<?=$detail_view?>"));
                $(container).append($("<PARAM>").attr("NAME", "detail_view_params").attr("VALUE", "<?=$detail_view_params?>"));
                $(container).append($("<PARAM>").attr("NAME", "detail_view_title").attr("VALUE", "<?=$detail_view_title?>"));
         <?php
             }
         ?>
	}

        function add_back_button_tool_bar_for_applets(container)
        {
            var navigation_div = $("<div>").addClass("back_button_title_bar");
            var title = $("<span>").addClass("neo_applet_loading_title").append("Initializing JRE (Java Runtime Environment)");
            var cross_click = $("<a>").addClass("ui-dialog-titlebar-close ui-corner-all");
            cross_click.bind(rdx.events.CLICK, function(event)
            {
                rdx.applet_loader._frame.hide();
                rdx.mask.unmask_dom_element($(document.body), "__NEO_APPLET_LOADING");
            })
            var cross_cont = $("<span>").addClass("ui-icon ui-icon-closethick").append("close");
            cross_click.append(cross_cont);
            navigation_div.append(title).append(cross_click);
            rdx.applet_loader._frame_back_toolber = navigation_div;
            container.append(navigation_div);
        };
        
        function load_applet_from_neo()
        {
            var applet_container = $("<div>").addClass("neo_applet_container");
            add_back_button_tool_bar_for_applets(applet_container);
            pluginURL = "\"" + pluginURL + "\"";
            if (_ie == true  && (_ie8 == false && _ie10 == false))//internet explorer
            {
                applet_container.append($("<div>").attr("id", "loading"));
                var object_container = $('<OBJECT classid="clsid:' + classid + '" WIDTH = 100% HEIGHT = 100% codebase=' + pluginURL + ' name="ConfigApplet">');
                print_params(object_container);
                applet_container.append(object_container);
            }
            else if (_firefox == true && !jre_on_mozilla)//Mozilla w/o JRE
            {
                var object_container = $("<EMBED>").attr("type", "application/x-java-applet;version=1.6").attr("WIDTH", "100%").attr("HEIGHT", "100%").attr("pluginspage", pluginURL);
                print_params(object_container);
                applet_container.append(object_container);
            }
            else//Chrome, Safari and Mozilla with JRE etc
            {
                var object_container = $("<APPLET>").attr("CODE", "ns.QuickAppletLoader").attr("archive", jar_files).attr("WIDTH", "100%").attr("HEIGHT", "100%").attr("name", "ConfigApplet");
                print_params(object_container);
                applet_container.append(object_container);
            }
             
            rdx.mask.mask_dom_element($(document.body), "__NEO_APPLET_LOADING");
            applet_container.css("width", (650)).css("height", 400).css("top", rdx.page.get_header_height() + 10).css("left", "30%");
            $(document.body).append(applet_container);
            return applet_container;
        }
