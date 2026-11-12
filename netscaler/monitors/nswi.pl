#!/usr/bin/perl -w
################################################################
##								 
## Copyright 2011-2016 Citrix Systems, Inc. All rights reserved.	 	
## This software and documentation contain valuable trade	 
## secrets and proprietary property belonging to Citrix Systems, Inc. 
## None of this software and documentation may be copied,	 
## duplicated or disclosed without the express
## written permission of Citrix Systems, Inc.
## 
## Authors:  Rishi Mutnuru, Pratap Tiwary
################################################################
## This is a netscaler supplied script. Please dont modify this as it will be overwritten during 
## reboot. If you want to modify, please make a copy of this script and modify.

## This script is used to do Citrix WI monitoring using KAS feature. 
## The mandatory arguments are:
## 	1. sitepath whose existence will be checked.
##	2. User name: username to login to WI
##	 
##	3. password:  This is the password that will be used to 
##	  	      login into the server 
## The arguments must be of the following form:
##      sitepath=<wi site path>;user=<username>;password=<password>;widomain=<widomain>
## Example:
##	set monitor ...  -scriptArgs "sitepath=XenApp1;user=user1;password=password;widomain=dhop"

use Cwd;
#use Exporter;
use POSIX qw(ceil floor);
use LWP::UserAgent;
use HTTP::Request::Common;
use HTTP::Request::Common ('POST');
use HTTP::Request;
use HTTP::Response;
use Net::INET6Glue::INET_is_INET6;
use Netscaler::KAS;
use strict;
#use warnings;
sub Wi_probe 
{

## There must be at least 4 arguments to this function.
	## 	1. First argument is the IP that has to be probed.
	##	2. Second argument is the port to connect to.
	##	3. Arguments to be used during probing.
	##	4. Timeout

	if(scalar(@_) < 3)
	{
		return (1,"Insufficient number of arguments");
	}

## Parse the argument given, to get file,user name,password.
	## If parsing fails, it is monitoring probe failure.
	$_[2]=~/sitepath=([^;]+);user=([^;]+)(;password=([^;]+))?(;widomain=([^;]+))?/
		or return (1,"Invalid argument format");

	my ($site,$username,$password,$widomain)=($1,$2,$4,$6);
	my $timeout = $_[3];
	my $WI_user= $username;
	my $WI_passwd= $password; 
	my $host = "";
	if (Netscaler::KAS::is_ipv4_address($_[0]))
	{
	$host= "$_[0]";
	}
	else
	{
	$host = "[$_[0]]";
	}
	my $port= "$_[1]";
	my $domain= $widomain;
	my $wi_url= $site;
	my $proto;
	my $retries="3";
	my $launch = 1;

	if(!defined($_[4]) || ($_[4] == 0))
	{
		$proto = "http";
	}
	else 
	{
		$proto = "https";
	}

#print file1 "Data for user : $user1\n";
	my $base_url = "$proto://$host:$port";
	my $tot_url = "$base_url/$wi_url";


	$ENV{HTTPS_DEBUG} = 0;
###########SG login#############
	my $ua_sg_login = LWP::UserAgent->new(keep_alive => 1,) or return(1, "Resources not available to create keep-alive request");
	$ua_sg_login->timeout($timeout);
	my $url_sg_login = "$tot_url/auth/login.aspx";
	my %GETSGLogin_headers = (
		'Accept' => 'image/gif, image/x-xbitmap, image/jpeg, image/pjpeg, application/x-shockwave-flash, appli                     cation/vnd.ms-excel, application/vnd.ms-powerpoint, application/msword, application/ag-plugin, */*',
		'Accept-Language' => 'en-us',
		'UA-CPU' => 'x86',
		'User-Agent' => 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.1)',
		'Host' => $host,
		'Connection' => 'Keep-Alive',
		'Cookie' => 'WIUser=NFuse_AppColumns=3&NFuse_ShowFolder=Off&NFuse_ShowDetails=Off&NFuse_CurrentFolder=&NFuse_ShowIcon=On&NFuse_RememberFolder=On&NFuse_WindowSeamless=On&NFuse_AuthMethod=Explicit&NFuse_ShowName=On&NFuse_LogoffApps=On&NFuse_ReconnectButton=DisconnectedAndActive&NFuse_SilentAuthentication=On&NFuse_ReconnectAtLogin=DisconnectedAndActive&NFuse_Locale=en; WINGDevice=NFuse_ClientName=WI_TktMWLMX23Wfs8kt8',
	);
	my $request_sg_login = HTTP::Request->new( GET => $url_sg_login ) or return(1,"Resources not available to create login request") ;
	$request_sg_login->header(%GETSGLogin_headers);
	my $response_sg_login = $ua_sg_login->request($request_sg_login);
	my @temp1 = $response_sg_login->header('Set-Cookie');
	my $ASPSessID;
	foreach (@temp1) {
		($ASPSessID, undef) = split /;/, $_, 2;
		if ($ASPSessID =~ "ASP")
		{
			last;
		}
	}
	if ($ASPSessID !~ "ASP")
	{
		return (1,"ASP.NET cookie not returned in response");
	}

########post auth login.aspx############################
	my $ua_post_sg_login = LWP::UserAgent->new(keep_alive => 1,);
	my %POSTSGLogin_headers = (
		'Accept' => 'image/gif, image/x-xbitmap, image/jpeg, image/pjpeg, application/x-shockwave-flash, application/vnd.ms-excel, application/vnd.ms-powerpoint, application/msword, application/ag-plugin, */*',
		'Referer' => "$tot_url/auth/login.aspx",
		'Accept-Language' => 'en-us',
		'Content-Type' => 'application/x-www-form-urlencoded',
		'UA-CPU' => 'x86',
		'User-Agent' => 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.1)',
		'Host' => $host,
		'Connection' => 'Keep-Alive',
		'Cache-Control' => 'no-cache',                     
		'Cookie' => 'WIClientInfo=Cookies_On=true&icaClientAvailable=true&icoLaunchPermitted=false&radeClientAvailable=false&radeLaunchPermitted=false&icaClientVersion=10.100.55836&icaIsPassThrough=3&icaScreenResolution=1024x768&clientConnSecure=true; WIUser=NFuse_AppColumns=3&NFuse_ShowFolder=Off&NFuse_ShowDetails=Off&NFuse_CurrentFolder=&NFuse_ShowIcon=On&NFuse_RememberFolder=On&NFuse_WindowSeamless=On&NFuse_AuthMethod=Explicit&NFuse_ShowName=On&NFuse_LogoffApps=On&NFuse_ReconnectButton=DisconnectedAndActive&NFuse_SilentAuthentication=On&NFuse_ReconnectAtLogin=DisconnectedAndActive&NFuse_Locale=en; WINGDevice=NFuse_ClientName=WI_TktMWLMX23Wfs8kt8; WINGSession=icoLaunchPermitted=false&clientConnSecure=true&icaScreenResolution=1024x768&icaClientAvailable=true&radeLaunchPermitted=false&radeClientAvailable=false&icaClientVersion=10.100.55836&icaIsPassThrough=3;'.'; '.$ASPSessID,
	);

	my $request_post_sg_login = POST($url_sg_login, [LoginType=>'Explicit', user=>$WI_user, password=>$WI_passwd, domain=>$domain, submitMode=>'submit', slLanguage=>'en', ReconnectAtLoginOption=>'DisconnectedAndActive'],);
	$request_post_sg_login->header(%POSTSGLogin_headers);
	my $response_post_sg_login = $ua_post_sg_login->request($request_post_sg_login);

	my @temp=$response_post_sg_login->header('Set-Cookie');
	my $WIAuthId;
	foreach (@temp) {
		($WIAuthId, undef) = split /;/, $_, 2;
		if ($WIAuthId =~ "WIAuthId")
		{
			last;
		}
	}
	if ($WIAuthId !~ "WIAuthId")
	{
		return (1,"Login failed : WIAuthId cookie not returned in response");
	}

#################GET default.aspx###########################
	my $ua_get_default = LWP::UserAgent->new(keep_alive => 1,);
	my $url_get_default = "$tot_url/site/default.aspx";
	my %GETdefault_headers = (
		'Accept' => 'image/gif, image/x-xbitmap, image/jpeg, image/pjpeg, application/x-shockwave-flash, application/vnd.ms-excel, application/vnd.ms-powerpoint, application/msword, application/ag-plugin, */*',
		'Referer' => "$tot_url/site/default.aspx",
		'Accept-Language' => 'en-us',
		'Content-Type' => 'application/x-www-form-urlencoded',
		'UA-CPU' => 'x86',
		'Accept-Encoding' => 'none',
		'User-Agent' => 'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.1)',
		'Host' => $host,
		'Connection' => 'Keep-Alive',
		'Cache-Control' => 'no-cache',
		'Cookie' => 'WIUser=NFuse_AppColumns=3&NFuse_ShowFolder=Off&NFuse_ShowDetails=Off&NFuse_CurrentFolder=&NFuse_ShowIcon=On&NFuse_RememberFolder=On&NFuse_WindowSeamless=On&NFuse_AuthMethod=Explicit&NFuse_ShowName=On&NFuse_LogoffApps=On&NFuse_ReconnectButton=DisconnectedAndActive&NFuse_SilentAuthentication=On&NFuse_ReconnectAtLogin=DisconnectedAndActive&NFuse_Locale=en; WINGDevice=NFuse_ClientName=WI_TktMWLMX23Wfs8kt8; WINGSession=icaScreenResolution=1024x768&radeLaunchPermitted=false&NFuse_LogonType=Explicit&clientConnSecure=true&icaClientAvailable=true&radeClientAvailable=false&icaClientVersion=10.100.55836&icoLaunchPermitted=false&icaIsPassThrough=3; '.$WIAuthId.';'.$ASPSessID,
	);

	my $request_get_default = HTTP::Request->new( GET => $url_get_default );
	$request_get_default->header(%GETdefault_headers);
	my $response_get_default = $ua_get_default->request($request_get_default);
#print file1 "\nGET $wi_url/site/applist.aspx ". $response_get_applist->status_line ."\n";
#$response_get_applist->as_string =~ /;id=(\w*)"/;
	my $html_content = $response_get_default->content;
	my $status = $response_get_default->status_line;

	if (($status !~ 200) && ($status !~ 302)) {
		return(1, "Unable to access default.aspx");
	}
	return(0);

}

probe(\&Wi_probe);
