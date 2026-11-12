<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<body>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Cloudbridge - setup</title>
<style type="text/css">
.login_button {
    background: url("login_sprite.png") no-repeat scroll left top transparent;
    border: 0 none;
    color: #FFFFFF;
    font-weight: bold;
    height: 33px !important;
    margin: 0 0 0 9px;
    outline: medium none;
    padding: 0 0 8px;
    width: 170px !important;
}

.button_cell {
    margin-right: 34px;
}
</style>
<script type="text/javascript">

function clear(layer) {

    var d = document.getElementById('results');
    d.style.display = 'none';

}

function show_applet(appletName){
 /*var doc = (this.contentWindow || this.contentDocument);
 if (doc.document)
    doc = doc.document;
    */

 var applet = document.applets[appletName];
 if (applet)
 {
     applet.show_view();
 }
}

</script>
</head>
<body>
<applet code="ns.im.view.Main" name="MainApplet1" archive="sinetfactory.jar,Main.jar"  width="0" height="0">
<PARAM name="username" value="nsroot">
<PARAM name="password" value="nsroot">
<PARAM name="hostname" value="10.102.126.37">
<PARAM name="remotepath" value="/var/nsinstall/build_kos_58/ns-10.0-58-gui.tar">
<PARAM name="localpath" value="C:\Users\raghum\Desktop">
<PARAM name="title" value="Regular download">
</applet>
<applet code="ns.im.view.Main" name="MainApplet2" archive="sinetfactory.jar,Main.jar"  width="0" height="0">
<PARAM name="username" value="nsroot">
<PARAM name="password" value="nsroot">
<PARAM name="hostname" value="10.102.126.37">
<PARAM name="remotepath" value="/var/nsinstall/build_kos_58/ns-10.0-58.gz">
<PARAM name="localpath" value="C:\Users\raghum\Desktop">
<PARAM name="title" value="Accelerated download">
</applet>
<div style="width : 98% ; height :98% ; border: 1px red; padding : 1px;">
<form action="setup.php" method="post">
<?php

$username = "nsroot";
$password = "nsroot";

if($_SERVER['REQUEST_METHOD'] == "GET")
    print_page();
if($_SERVER['REQUEST_METHOD'] == "POST")
    print_page(true);

function print_page($post_req=false)
{
     print "<table>";
     print "<tr>";
     print "<td >";
     print "<input type=submit name='One_Cloud' class='login_button' value='One Cloud' >";
     print "</td>";
     print "<td>";
     print "&nbsp;";
     print "</td>";
     print "<td class='button_cell'>";
     print "<input type='submit' name='Many_Clouds' class='login_button' value='Many Clouds' >";
     print "</td>";
     print "<td>";
     print "&nbsp;";
     print "</td>";
     print "<td>";
     print "<input type='button' name='Regular_Download' class='login_button' value='Regular Download' onclick=javascript:show_applet('MainApplet1');>";
     print "</td>";
     print "<td>";
     print "&nbsp;";
     print "</td>";
     print "<td>";
     print "<input type='button' name='Accelerated_Download'  class='login_button' value='Accelerated Download' onclick=javascript:show_applet('MainApplet2');>";
     print "</td>";
     print "<td>";
     print "&nbsp;";
     print "</td>";
     print "<td>";
     print "<input type=submit name='Clean_Up' class='login_button' value='Clean Up' >";
     print "</td>";
     print "<td>";
     print "&nbsp;";
     print "</td>";
     print "</tr>";
     print "</table>";

     print "</form>";
     print "<hr/>";


    if($post_req)
    {
        print "<div id='results'>";
        print "<a href='javascript:clear()' >Clear</a> </br> </br>";

        $search_array = array_keys($_POST);


        if (in_array('One_Cloud', $search_array))
        {
            print "<b> Executing One Cloud command </b> </br></br>";
            one_cloud();
        }

        if (in_array('Many_Clouds', $search_array))
        {
            print "<b> Executing Many Clouds command </b> </br></br>";
            many_clouds();
        }

        if (in_array('Clean_Up', $search_array))
        {
            print "<b> Executing Clean command </b> </br></br>";
            clean();

        }

        if (in_array('Regular_Download', $search_array))
        {
            print "<b> Executing Regular Download command </b> </br></br>";
           // accelarate();

        }

        if (in_array('Accelerated_Download', $search_array))
        {
            print "<b> Executing Accelerated Download command </b> </br></br>";
            //decelerate();

        }

        print "</div>";

    }
}

function one_cloud()
{
    $commands = array(
                    "add ipsec peer vpx0 199.34.126.161 199.16.206.60 -psk cloud",
                    "add route 1.1.0.0 255.255.255.0 2.2.2.254"
                    );
    exec_commands($commands);
}

function many_clouds()
{
    build();
    accelarate();
}

function build()
{
    $commands = array(
                        "add ipsec peer vpx2 199.34.126.161 199.34.126.162 -psk dd231f5573 -encrypted",
                        "add ipsec peer vpx3 199.34.126.161 199.34.126.163 -psk dd231f5573 -encrypted",
                        "add ipsec peer vpx4 199.34.126.161 199.34.126.164 -psk dd231f5573 -encrypted",
                        "add ipsec peer vpx5 199.34.126.161 199.34.126.165 -psk dd231f5573 -encrypted",
                        "add ipsec peer vpx6 199.34.126.161 199.34.126.166 -psk dd231f5573 -encrypted",
                        "add ipsec peer vpx7 199.34.126.161 199.34.126.167 -psk dd231f5573 -encrypted",
                        "add ipsec peer vpx8 199.34.126.161 199.34.126.168 -psk dd231f5573 -encrypted",
                        "add ipsec peer vpx9 199.34.126.161 199.34.126.169 -psk dd231f5573 -encrypted",
                        "add ipsec peer vpx10 199.34.126.161 199.34.126.170 -psk dd231f5573 -encrypted",
                        "add ipsec peer vpx11 199.34.126.161 199.34.126.171 -psk dd231f5573 -encrypted",
                        "add ipsec peer vpx12 199.34.126.161 199.34.126.172 -psk dd231f5573 -encrypted",
                        "add ipsec peer vpx13 199.34.126.161 199.34.126.173 -psk dd231f5573 -encrypted",
                        "add ipsec peer vpx14 199.34.126.161 199.34.126.174 -psk dd231f5573 -encrypted",
                        "add ipsec peer vpx15 199.34.126.161 199.34.126.175 -psk dd231f5573 -encrypted",
                        "add ipsec peer vpx16 199.34.126.161 199.34.126.176 -psk dd231f5573 -encrypted",
                        "add ipsec peer vpx17 199.34.126.161 199.34.126.177 -psk dd231f5573 -encrypted",
                        "add ipsec peer vpx21 199.34.126.161 199.16.206.41 -psk dd231f5573 -encrypted",
                        "add ipsec peer vpx22 199.34.126.161 199.16.206.42 -psk dd231f5573 -encrypted",
                        "add ipsec peer vpx23 199.34.126.161 199.16.206.43 -psk dd231f5573 -encrypted",
                        "add ipsec peer vpx24 199.34.126.161 199.16.206.44 -psk dd231f5573 -encrypted",
                        "add ipsec peer vpx25 199.34.126.161 199.16.206.45 -psk dd231f5573 -encrypted",
                        "add ipsec peer vpx26 199.34.126.161 199.16.206.46 -psk dd231f5573 -encrypted",
                        "add ipsec peer vpx27 199.34.126.161 199.16.206.47 -psk dd231f5573 -encrypted",
                        "add ipsec peer vpx28 199.34.126.161 199.16.206.48 -psk dd231f5573 -encrypted",
                        "add ipsec peer vpx29 199.34.126.161 199.16.206.49 -psk dd231f5573 -encrypted",
                        "add ipsec peer vpx30 199.34.126.161 199.16.206.50 -psk dd231f5573 -encrypted",
                        "add ipsec peer vpx31 199.34.126.161 199.16.206.51 -psk dd231f5573 -encrypted",
                        "add ipsec peer vpx32 199.34.126.161 199.16.206.52 -psk dd231f5573 -encrypted",
                        "add ipsec peer vpx33 199.34.126.161 199.16.206.53 -psk dd231f5573 -encrypted",
                        "add ipsec peer vpx34 199.34.126.161 199.16.206.54 -psk dd231f5573 -encrypted",
                        "add ipsec peer vpx35 199.34.126.161 199.16.206.55 -psk dd231f5573 -encrypted",
                        "add ipsec peer vpx36 199.34.126.161 199.16.206.56 -psk dd231f5573 -encrypted",
                        "add route 1.1.2.0 255.255.255.0 2.2.2.6",
                        "add route 1.1.3.0 255.255.255.0 2.2.2.10",
                        "add route 1.1.4.0 255.255.255.0 2.2.2.14",
                        "add route 1.1.5.0 255.255.255.0 2.2.2.18",
                        "add route 1.1.6.0 255.255.255.0 2.2.2.22",
                        "add route 1.1.7.0 255.255.255.0 2.2.2.26",
                        "add route 1.1.8.0 255.255.255.0 2.2.2.30",
                        "add route 1.1.9.0 255.255.255.0 2.2.2.34",
                        "add route 1.1.10.0 255.255.255.0 2.2.2.38",
                        "add route 1.1.11.0 255.255.255.0 2.2.2.42",
                        "add route 1.1.12.0 255.255.255.0 2.2.2.46",
                        "add route 1.1.13.0 255.255.255.0 2.2.2.50",
                        "add route 1.1.14.0 255.255.255.0 2.2.2.54",
                        "add route 1.1.15.0 255.255.255.0 2.2.2.58",
                        "add route 1.1.16.0 255.255.255.0 2.2.2.62",
                        "add route 1.1.17.0 255.255.255.0 2.2.2.66",
                        "add route 1.1.21.0 255.255.255.0 2.2.2.70",
                        "add route 1.1.22.0 255.255.255.0 2.2.2.74",
                        "add route 1.1.23.0 255.255.255.0 2.2.2.78",
                        "add route 1.1.24.0 255.255.255.0 2.2.2.82",
                        "add route 1.1.25.0 255.255.255.0 2.2.2.86",
                        "add route 1.1.26.0 255.255.255.0 2.2.2.90",
                        "add route 1.1.27.0 255.255.255.0 2.2.2.94",
                        "add route 1.1.28.0 255.255.255.0 2.2.2.98",
                        "add route 1.1.29.0 255.255.255.0 2.2.2.102",
                        "add route 1.1.30.0 255.255.255.0 2.2.2.106",
                        "add route 1.1.31.0 255.255.255.0 2.2.2.110",
                        "add route 1.1.32.0 255.255.255.0 2.2.2.114",
                        "add route 1.1.33.0 255.255.255.0 2.2.2.118",
                        "add route 1.1.34.0 255.255.255.0 2.2.2.122",
                        "add route 1.1.35.0 255.255.255.0 2.2.2.126",
                        "add route 1.1.36.0 255.255.255.0 2.2.2.130"
                        );
    exec_commands($commands);
}

function clean()
{
    $commands = array(   "rm route 1.1.2.0 255.255.255.0 2.2.2.6",
                         "rm route 1.1.3.0 255.255.255.0 2.2.2.10",
                         "rm route 1.1.4.0 255.255.255.0 2.2.2.14",
                         "rm route 1.1.5.0 255.255.255.0 2.2.2.18",
                         "rm route 1.1.6.0 255.255.255.0 2.2.2.22",
                         "rm route 1.1.7.0 255.255.255.0 2.2.2.26",
                         "rm route 1.1.8.0 255.255.255.0 2.2.2.30",
                         "rm route 1.1.9.0 255.255.255.0 2.2.2.34",
                         "rm route 1.1.10.0 255.255.255.0 2.2.2.38",
                         "rm route 1.1.11.0 255.255.255.0 2.2.2.42",
                         "rm route 1.1.12.0 255.255.255.0 2.2.2.46",
                         "rm route 1.1.13.0 255.255.255.0 2.2.2.50",
                         "rm route 1.1.14.0 255.255.255.0 2.2.2.54",
                         "rm route 1.1.15.0 255.255.255.0 2.2.2.58",
                         "rm route 1.1.16.0 255.255.255.0 2.2.2.62",
                         "rm route 1.1.17.0 255.255.255.0 2.2.2.66",
                         "rm route 1.1.21.0 255.255.255.0 2.2.2.70",
                         "rm route 1.1.22.0 255.255.255.0 2.2.2.74",
                         "rm route 1.1.23.0 255.255.255.0 2.2.2.78",
                         "rm route 1.1.24.0 255.255.255.0 2.2.2.82",
                         "rm route 1.1.25.0 255.255.255.0 2.2.2.86",
                         "rm route 1.1.26.0 255.255.255.0 2.2.2.90",
                         "rm route 1.1.27.0 255.255.255.0 2.2.2.94",
                         "rm route 1.1.28.0 255.255.255.0 2.2.2.98",
                         "rm route 1.1.29.0 255.255.255.0 2.2.2.102",
                         "rm route 1.1.30.0 255.255.255.0 2.2.2.106",
                         "rm route 1.1.31.0 255.255.255.0 2.2.2.110",
                         "rm route 1.1.32.0 255.255.255.0 2.2.2.114",
                         "rm route 1.1.33.0 255.255.255.0 2.2.2.118",
                         "rm route 1.1.34.0 255.255.255.0 2.2.2.122",
                         "rm route 1.1.35.0 255.255.255.0 2.2.2.126",
                         "rm route 1.1.36.0 255.255.255.0 2.2.2.130",
                         "rm route 1.1.37.0 255.255.255.0 2.2.2.134",
                         "rm route 1.1.0.0 255.255.255.0 2.2.2.254",
                         "rm ipsec peer vpx0",
                         "rm ipsec peer vpx2",
                         "rm ipsec peer vpx3",
                         "rm ipsec peer vpx4",
                         "rm ipsec peer vpx5",
                         "rm ipsec peer vpx6",
                         "rm ipsec peer vpx7",
                         "rm ipsec peer vpx8",
                         "rm ipsec peer vpx9",
                         "rm ipsec peer vpx10",
                         "rm ipsec peer vpx11",
                         "rm ipsec peer vpx12",
                         "rm ipsec peer vpx13",
                         "rm ipsec peer vpx14",
                         "rm ipsec peer vpx15",
                         "rm ipsec peer vpx16",
                         "rm ipsec peer vpx17",
                         "rm ipsec peer vpx21",
                         "rm ipsec peer vpx22",
                         "rm ipsec peer vpx23",
                         "rm ipsec peer vpx24",
                         "rm ipsec peer vpx25",
                         "rm ipsec peer vpx26",
                         "rm ipsec peer vpx27",
                         "rm ipsec peer vpx28",
                         "rm ipsec peer vpx29",
                         "rm ipsec peer vpx30",
                         "rm ipsec peer vpx31",
                         "rm ipsec peer vpx32",
                         "rm ipsec peer vpx33",
                         "rm ipsec peer vpx34",
                         "rm ipsec peer vpx35",
                         "rm ipsec peer vpx36",
                         "rm ipsec peer vpx37",

    );
    exec_commands($commands);
}
function accelarate()
{
    $commands = array("add lb vs br any * * -m mac -l2conn on",
                      "bind lb vs br br");
    exec_commands($commands);
}

function decelerate()
{
    $commands = array("rm lb vs br");
    exec_commands($commands);
}

function exec_commands($commands)
{
    global $username, $password;
    foreach( $commands as $cli_commad)
    {
        $shell_command = "nscli -U :". $username .":" .$password . " '" .$cli_commad . " ' 2>&1";
        $result = exec($shell_command);
        $result = preg_replace('/^Done/', '', $result, 1);
        $has_error = (strpos(strtolower($result),'error') !== false);

        print "<span ";
        if($has_error)
            print " style='color: red'>";
        else
            print ">";
        print "> " .$cli_commad ."</br>";
        print "$result </span> </br>";

    }
}


?>

<br/>

</div>
</body>
</html>