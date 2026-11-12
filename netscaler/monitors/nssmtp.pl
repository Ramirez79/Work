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

## This script is used to do smtp monitoring using KAS feature.

use strict;
use Net::SMTP;
use Net::SMTP6;
use Netscaler::KAS;

sub is_ipv4_address
{
	my $address = $_[0];
	if ($address =~ m/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)/) {
		return 1;
	}
	return 0;
}

## This function is a handler for performing smtp probe in KAS mode
sub smtp_probe
{
	
	## There must be at least 4 arguments to this function.
        ##      1. First argument is the IP that has to be probed.
        ##      2. Second argument is the port to connect to.
		##      3. Timeout, it is present in index 3

	if(scalar(@_) < 2)
	{
		return (1,"Invalid number of arguments");
	}	

	## Try to connect to the server
	my $smtp;
	if (is_ipv4_address($_[0])) {
		$smtp=Net::SMTP->new($_[0].":".$_[1],Timeout=>$_[3])
			or return (1,"Unable to connect to server - $!");
		## Probe succeeded
		$smtp->quit;
		return 0;
	}
	else {	#IPV6 adress
		$smtp=Net::SMTP6->new($_[0], PeerPort => $_[1], Timeout=>$_[3])
			or return (1,"Unable to connect to server - $!");
		## Probe succeeded
		$smtp->quit;
		return 0;
	}
}

## Register smtp probe handler, to the KAS module.
probe(\&smtp_probe);
