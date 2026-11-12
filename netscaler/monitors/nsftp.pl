#!/usr/bin/perl -w
################################################################
##								 
## Copyright 1998-2016 Citrix Systems, Inc. All rights reserved.	 	
## This software and documentation contain valuable trade	 
## secrets and proprietary property belonging to Citrix Systems, Inc. 
## None of this software and documentation may be copied,	 
## duplicated or disclosed without the express
## written permission of Citrix Systems, Inc.
##
################################################################
## This is a netscaler supplied script. Please dont modify this as it will be overwritten during 
## reboot. If you want to modify, please make a copy of this script and modify.

## This script is used to do ftp monitoring using KAS feature. 
## The mandatory arguments are:
## 	1. File name whose existence will be checked.
## The optional arguments are:
##	1. User name: When no username is given anonymous login
##	              is attempted
##	2. password:  This is the password that will be used to 
##	  	      login into the server 
## The arguments must be of the following form:
##      file=<filename>[;user=<username>][;password=<password>]
## Example:
##	set monitor ...  -scriptArgs "file=file.c;user=user1;password=password"

use strict;
use Net::FTP;
use Netscaler::KAS;

sub is_ipv4_address
{
	my $address = $_[0];
	if ($address =~ m/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)/) {
		return 1;
	}
	return 0;
}

## This function is a handler for performing ftp probe in KAS mode 
sub ftp_probe
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

	my $err_code =0;
	my $err_string;	

	## Parse the argument given, to get file,user name,password.
	## If parsing fails, it is monitoring probe failure.
        $_[2]=~/file=([^;]+)(;user=([^;]+))?(;password=([^;]+))?/
	                or return (1,"Invalid argument format");

        my ($file,$username,$password)=($1,$3,$5);

	## If no user name is given, try anonymous user
        if(!defined($username))
	{
        	$username="anonymous";
		$err_string = "With Anonymous user:$username;";
	}

	## If no password is given, and it is anonymous user, 
	## password is defaulted to anonymous@
        if(!defined($password))
	{
		if($username eq "anonymous")
		{
			$password='anonymous@';
			$err_string = $err_string . "Anonymous password:$password;"
		}
		else
		{
        		$password="";
		}
	}

	my $secure_ftp = 0;
	if(defined($_[4]) && ($_[4] == 1))
	{
		$secure_ftp = 1;
	}

	## Try to connect to the server
	my $ftp;

	if ($secure_ftp == 0)
	{
		if (is_ipv4_address($_[0])) {
			$ftp=Net::FTP->new($_[0].":".$_[1],Timeout=>$_[3])
				or return (1,"Connection timed out - $@");
		}
		else { #IPV6 adress
			require Net::INET6Glue::FTP;
			$ftp=Net::FTP->new($_[0], PeerPort => $_[1], Timeout=>$_[3])
				or return (1,"Connection timed out - $@");
		}
	}
	else
	{
			require Net::SSLGlue::FTP;
			$ftp = Net::FTP->new($_[0],PeerPort =>$_[1], Timeout=>$_[3] )
				or return  (1,"Failed to connect - $!");
			$ftp->starttls(SSL_verify_mode=>0);
	}

	## Login failed, so report failure
        $ftp->login($username,$password) or 
		( $err_code = 1, $err_string = $err_string . "Login failed");

	## File not found, return failure
	if ($err_code == 0)
	{
		my @file_array;
		@file_array = $ftp->ls($file) or
		( $err_code = 1, $err_string = $err_string . "File not found");
	}
	## Probe completed.
	$ftp->quit;
	return 0 
		if ($err_code == 0);
	
	return ($err_code, $err_string);
}

## Register ftp probe handler, to the KAS module.

probe(\&ftp_probe);
