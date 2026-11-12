<?php if(!defined("BASEPATH")) exit("No direct script access allowed");
//Don't allow this page to be embedded inside a frame
header("X-Frame-Options: DENY");

$this->load->view("common/header");
?>
<script type="text/javascript" src="/admin_ui/common/js/jquery/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="/admin_ui/common/js/ns/ns_resize_handler.js"></script>
<div class = "dd_layout">
    <div class = "doc_ns_header_title"> Downloads </div>
    <div class = "doc_contents"> 
        <div class="content_div">
            <div class="ns_small_header"><div class = "downloads_images node_dwnload_img pointer_cursor"> </div>SNMP Files</div>
            <div style= "padding-left:20px;"> 
                <ul>
                    <li class="ns_download_node">
                        <div><a href="/support/docs/snmp/HP-Openview/NS-MIB-smiv1.mib" target="NS_MIB_smiv1_mib">SNMP v1 - MIB Object Definitions</a></div>
                        <div>This MIB definition can be used by SNMP managers to retrieve information from the device using SNMP Version 1.</div>
                    </li>
                    <li class="ns_download_node">
                        <div><a href="/support/docs/snmp/HP-Openview/NS-MIB-smiv2.mib" target="NS_MIB_smiv2_mib">SNMP v2 - MIB Object Definitions</a></div>
                        <div>This MIB definition can be used by SNMP managers to retrieve information from the device using SNMP Version 2</div>
                    </li>
                    <li class="ns_download_node">
                        <div><a href="/support/docs/snmp/WhatsUpGold/mib.txt" target="WhatsUpGold_MIB">WhatsUpGold MIB Object Definitions</a></div>
                        <div>This MIB can be used by WhatsUP Gold SNMP manager to retrieve information from the device.</div>
                    </li>
                    <li class="ns_download_node">
                        <div><a href="/support/docs/snmp/WhatsUpGold/traps.txt" target="WhatsUpGold_Traps">WhatsUpGold Trap Definitions</a></div>
                        <div>The trap definitions can be used by WhatsUP Gold SNMP manager to process traps from the device.</div>
                    </li>
                </ul>
            </div>

        </div>
        <?php
        if(!isset($is_sgw) || $is_sgw == "false")
        {
        ?>
        <div class="content_div">
            <div class="ns_small_header"><div class = "downloads_images node_dwnload_img pointer_cursor"> </div>NITRO</div>
             <div style= "padding-left:20px;">
                <ul>
                    <li class="ns_download_node">
                        <div><a href="/nitro-java.tgz" target="NetScaler_NITRO_API_SDK_JAVA">NITRO API SDK for Java</a></div>
                        <div>The NITRO API SDK for Java contains getting started guide, samples, documentation and libraries for using the NITRO API from Java.</div>
                    </li>
                    <li class="ns_download_node">
                        <div><a href="/nitro-csharp.tgz" target="NetScaler_NITRO_API_SDK_CSHARP">NITRO API SDK for C#</a></div>
                        <div>The NITRO API SDK for C# contains getting started guide, samples, documentation and libraries for using the NITRO API from C#.</div>
                    </li>
                    <li class="ns_download_node">
                        <div><a href="/nitro-python.tgz" target="NetScaler_NITRO_API_SDK_PYTHON">NITRO API SDK for Python</a></div>
                        <div>The NITRO API SDK for Python contains samples and libraries for using the NITRO API from Python.</div>
                    </li>
                    <li class="ns_download_node">
                        <div><a href="/nitro-perl-samples.tgz" target="NetScaler_NITRO_API_SAMPLES_PERL">NITRO API samples for Perl</a></div>
                        <div>The NITRO API samples for Perl contains samples and libraries for using the NITRO API from Perl.</div>
                    </li>
                    <li class="ns_download_node">
                        <div><a href="/nitro-rest.tgz" target="NetScaler_NITRO_API_DOCUMENTATION">NITRO API Documentation</a></div>
                        <div>The NITRO API Documentation contains details about configuring NetScaler and retrieving statistics using the NITRO REST API.</div>
                    </li>
                </ul>
            </div>

        </div>

        <div class="content_div">
            <div class="ns_small_header"><div class = "downloads_images node_dwnload_img pointer_cursor"> </div>SCOM Management Pack</div>
            <div style= "padding-left:20px;">
                <ul>
                    <li class="ns_download_node">
                        <div><a href="/msn/CitrixNetScalerManagementPackSCOM2012.msi" target="Netscaler_Management_Pack">NetScaler Management Pack for System Center Operation Manager 2012</a></div>
                        <div>The Citrix NetScaler Operation Manager pack provides monitors and rules to monitor the NetScaler systems deployed in your network.<br>
The Citrix NetScaler Performance and Resource Optimization (PRO) Management Pack (MP) provides monitors and rules to monitor the health of the virtual servers configured on the managed NetScaler systems and initiate corrective actions using the PRO feature of SCVMM when the virtual servers become unhealthy.</div>
                    </li>
                    <li class="ns_download_node">
                        <div><a href="/msn/CitrixNetScalerLoadBalancer.msi" target="Netscaler_Management_Pack">NetScaler LB Provider for Microsoft System Center Virtual Machine Manager 2012</a></div>
                        <div>
Microsoft System Center Virtual Machine Manager 2012 provides the ability to natively manage a Citrix NetScaler appliance through a provider package, which implements the interfaces described within System Center Virtual Machine Manager. This allows users to seamlessly create and manage virtual machines, and add those virtual machines to newly defined virtual servers on the NetScalers. Load Balancing becomes an integral part of application definitions, allowing for improved application resiliency. The provider package can be installed in just a few clicks and you can add a load balancer post installation. Next step is defining the VIP template, which is a key to define the load balancing parameters and properties for NetScaler.
NetScaler LB provider pack helps you choose the default LB properties supported by Virtual Machine Manager. It also allows you to use CUSTOM properties for Protocol, Persistence and Load Balancing Method. This opens up multiple new use cases where you can utilize NetScaler Load Balancing features.</div>
                    </li>
                </ul>
            </div>
        </div>

        <div class="content_div">
            <div class="ns_small_header"><div class = "downloads_images node_dwnload_img pointer_cursor"> </div>NetScaler Gateway Plug-in</div>
            <div style= "padding-left:20px;">
                <ul>
                    <li class="ns_download_node">
                        <div><a href="/vpns/scripts/vista/AGEE_setup.exe" target="NSVPNC_SETUP">Download NetScaler Gateway Plug-in for Windows</a></div>
                        <div>The NetScaler Gateway Plug-in for Windows can be installed on Windows XP or Windows Vista to securely access network resources through the NetScaler Gateway.</div>
                    </li>
                    <li class="ns_download_node">
                        <div><a href="/vpns/scripts/mac/Citrix_Access_Gateway.dmg" target="NSVPNC_SETUP">Download NetScaler Gateway Plug-in for Mac OS X</a></div>
                        <div>The NetScaler Gateway Plug-in for Mac OS X can be installed on Mac to securely access network resources through the NetScaler Gateway.</div>
                    </li>
                </ul>
            </div>
        </div>
         <?php
        }
        ?>
    </div>


</div>
<script type="text/javascript">
    $(window).bind("resize", ns_resize_handler);
    ns_resize_handler();
</script>