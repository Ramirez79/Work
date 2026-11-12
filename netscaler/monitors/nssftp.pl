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

## This script is used to do SFTP monitoring using KAS feature.
## The mandatory arguments are:
##	1. File name whose existence will be checked.
##	2. User name
##	3. password:  This is the password that will be used to
##			login into the server
## The arguments must be of the following form:
##      file=<filename>;user=<username>;password=<password>
## Example:
##	set monitor ...  -scriptArgs "file=file.c;user=user1;password=password"
use strict;
use Netscaler::KAS;
use Net::SFTP::Foreign;

## This function is a handler for performing ftp probe in KAS mode
sub sftp_probe
{
	## There must be at least 4 arguments to this function.
	##	1. First argument is the IP that has to be probed.
        ##	2. Second argument is the port to connect to.
	##	3. Arguments to be used during probing.
	##	4. Timeout

	if(scalar(@_) < 3)
	{
		return (1,"Insufficient number of arguments");
	}

	my $err_code =0;
	my $err_string = "";

	## Parse the argument given, to get file,user name,password.
	## If parsing fails, it is monitoring probe failure.
        $_[2]=~/file=([^;]+)(;user=([^;]+))?(;password=([^;]+))?/
	                or return (1,"Invalid argument format");

        my ($file,$username,$password)=($1,$3,$5);
	my $sftp = 0;

	if (!defined($username) || !defined ($password))
	{
		return (1,"Username or Password not provided");
	}

	# Errors are set by verifying $sftp (and not the return value)
	$sftp = Net::SFTP::Foreign->new($_[0], port=>$_[1], timeout=>$_[3], user => $username, password => $password,more => [-o => 'StrictHostKeyChecking no']);
	$sftp->error and ( $err_code = 1, $err_string = $err_string . "Login failed - " . $sftp->error . "\n");

	if ($err_code == 0)
	{
		$sftp->stat($file);
		$sftp->error and ( $err_code = 1, $err_string = $err_string . $sftp->error . "\n");
	}

	undef $sftp;

	return 0
		if ($err_code == 0);

	return ($err_code, $err_string);
}

## Register sftp probe handler, to the KAS module.

probe(\&sftp_probe);

