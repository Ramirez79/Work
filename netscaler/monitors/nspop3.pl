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

## This script is used to do pop3 monitoring using KAS feature.
## The mandatory arguments are:
##      1. User name.
## The optional arguments are:
##      1. password:  This is the password that will be used to
##                    login into the server
## Arguments must be given in the following format:
##      user=<user-name>[;password=<password>]
## Example:
##	set monitor ...  -scriptArgs "user=user1;password=password"

use strict;
use Net::POP3;
use Net::POP3V6;
use Netscaler::KAS;

## This function is a handler for performing pop3 probe in KAS mode
sub pop3_probe
{
	
	## There must be at least 3 arguments to this function.
        ##      1. First argument is the IP that has to be probed.
        ##      2. Second argument is the port to connect to.
        ##      3. Arguments to be used during probing.
		##		4. Timeout

        if(scalar(@_) < 3)
	{
		return (1,"Invalid number of arguments");
	}
	
	## Parse the argument given, to get user name,password.
        ## If parsing fails, it is monitoring probe failure.
        $_[2]=~/user=([^;]+)(;password=([^;]+))?/
                or return (1,"Invalid argument format");

	## Try to connect to the server
	my $pop3;
	if (Netscaler::KAS::is_ipv4_address($_[0])) {
		$pop3=Net::POP3->new($_[0].":".$_[1],Timeout=>$_[3])
			or return (1,"Failed connection to server - $!");
	} else {
		$pop3=Net::POP3V6->new($_[0], PeerPort => $_[1], Timeout=>$_[3])
			or return (1,"Failed connection to server - $!");
	}
        my($username,$password)=($1,$3);

	## No password is given, try blank password.
	if(!defined($password))
	{	
		$password="";
	}

	## Login failed, so report failure
        if(!defined($pop3->login($username,$password)))
	{
        	$pop3->quit;
        	return (1,"Login failed - $!");
	}

	## Probe succeeded
        $pop3->quit;
        return 0;

}

## Register ftp probe handler, to the KAS module.
probe(\&pop3_probe);
