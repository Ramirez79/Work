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

## This script is used to do nntp monitoring using KAS feature.
## The mandatory arguments are:
##      1. group name whose existence will be checked.
## The optional arguments are:
##      1. User name
##      2. password
## The format of the script argument is as follows:
##      group=<group-name>[;user=<user-name>][;password=<password>]
## Example:
##	set monitor ...  -scriptArgs "group=testgroup;user=user1;password=password"

use strict;
use Net::NNTP;
use Net::NNTP6;
use Netscaler::KAS;

## This function is a handler for performing nntp probe in KAS mode
sub nntp_probe
{

	my $err_code = 0;
	my $err_string;
	
	## There must be at least 4 arguments to this function.
        ##      1. First argument is the IP that has to be probed.
        ##      2. Second argument is the port to connect to.
        ##      3. Arguments to be used during probing.
		##      4. Arguments to be used during probing.

	if(scalar(@_) < 3)
	{
		return (1,"Insufficient number of arguments");
	}

	## Parse the argument given, to get group,user name,password.
        ## If parsing fails, it is monitoring probe failure.
        $_[2]=~/group=([^;]+)(;user=([^;]+))?(;password=([^;]+))?/
                or return (1,"Invalid argument format");
			
	## Try to connect to the server
	my $nntp;
	if (Netscaler::KAS::is_ipv4_address($_[0])) {
		$nntp=Net::NNTP->new($_[0].":".$_[1],Timeout=>$_[3])
                or return (1,"Unable to connect to server - $!");
	} else {
		$nntp=Net::NNTP6->new($_[0], PeerPort => $_[1], Timeout=>$_[3])
                or return (1,"Unable to connect to server - $!");
	}

        my($group,$username,$password)=($1,$3,$5);

	## If user name, is given lookup for a group on that user
	## If password, is given use it while querying for the group 
        if(defined($username))
        {
                if(!defined($password))
		{
                	$password="";
		}
                $nntp->authinfo($username,$password) or return (1, "Could not authenticate user - $username");
        }

	## Group not found, report failure
        $nntp->group($group)
                or ($err_code = 1, $err_string = "Group not found - $!" );

	## Group found hence probe success.
        $nntp->quit;
        return 0
		if ($err_code == 0);
	return ($err_code, $err_string);
}

probe(\&nntp_probe);
