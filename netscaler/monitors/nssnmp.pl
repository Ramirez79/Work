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

## This script is used to do SNMP monitoring using KAS feature.
## The mandatory arguments are:
##      1. Community string, that is equivalent to password.
##	2. OID , object identifier of the object to be retrieved
## The optional arguments are:
##	1. Threshold, when given the retrieved value is compared 
##	   against the threshold.  
## Argument to the script must be in the following format
##      community=<community-name>;oid=<object-id>[threshold=<threshold-value>]
## Example:
##	set monitor ...  -scriptArgs "community=samplepass;oid=CpuUsage;threshold=10"

## Setting the log value to none, so nothing is dumped to stdin.

use strict;
use Net::SNMP;
use Netscaler::KAS;

## This function is a handler for performing snmp probe in KAS mode
sub snmp_probe
{
	## There must be at least 3 arguments to this function.
        ##      1. First argument is the IP that has to be probed.
        ##      2. Second argument is the port to connect to.
        ##      3. Arguments to be used during probing.

        if(scalar(@_) < 3)
	{
		return (1,"Invalid number of arguments");
	}

	## Parse the argument given, to get community,oid,threshold.
        ## If parsing fails, it is monitoring probe failure.
	$_[2]=~/community=([^;]+);oid=([^;]+)(;threshold=([0-9]+))?/
	        or return (1,"Invalid Argument format");

	my ($community,$oid,$threshold)=($1,$2,$4);

	## Try to connect to the server
	my $session;
	my $error;
  	if (Netscaler::KAS::is_ipv4_address($_[0])) {
		($session, $error) = Net::SNMP->session(
      	-hostname  =>	$_[0], 
      	-community => "$community",
      	-port      =>	$_[1] ,
		-timeout => $_[3],
		-retries => 0
   		);
	} else {
		($session, $error) = Net::SNMP->session(
      	-hostname  =>	$_[0], 
      	-community => "$community",
      	-port      =>	$_[1] ,
		-timeout => $_[3],
		-domain => 'udp/ipv6',
		-retries => 0
   		);
	}

	## Connect to server failed, report error
	if(!defined($session))
	{
		return (1,"Could not create SNMP session- $error");
	}

	## Try to get the value of the given object identifier
	my $value= $session->get_request(
      -varbindlist => [$oid]
   	);

	## Failure trying to get the object, report error
	if(!defined($value))
	{
		my $error=$session->error;
		$session->close;
		return(1,"Object Identifier not found - ".$error);
	}


	## If threshold is configured, use the retrieved value to compare
	## against the threshold. If the given value is greater than or
	## equal to the threshold, it is a probe failure, else success.
	if(!defined($threshold))
	{
			$session->close;
	        return 0;
	}

	if($value->{$oid} >= $threshold)
	{
			$session->close;
	        return (1,"Exceeded threshold");
	}

	## Probe succeeded.
	$session->close;
	return 0;
}

## Register snmp probe handler, to the KAS module.

probe(\&snmp_probe);
