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

## This script is used to do ldap monitoring using KAS feature.
## The mandatory arguments are:
##	1. Base, the root from which the search would begin.
## The optional arguments are:
##      1. BindDN, the distinguished name which is used during binding 
##	   to ldap server.
##      2. secret: This is equivalent to the password.
##      3. filter: This is used to look for existence of particular 
##		   elements in the LDAP tree.
##	   4. attribute: This is the attribute name whose values need to be retrieved
## Arguments must be in the following format:
##	base=<search-base>;bdn=<bind-dn>[;password=<password>][;filter=<filter-string>]
## Example:
##      set monitor ...  -scriptArgs "base=cn=user1;bdn=cn=user,dc=abc;password=password;
##												filter=objectclass=*;attribute=attrs"


use strict;
use Net::LDAP;
use Netscaler::KAS;

## This function is a handler for performing ldap probe in KAS mode
sub ldap_probe
{

	## There must be at least 4 arguments to this function.
        ##      1. First argument is the IP that has to be probed.
        ##      2. Second argument is the port to connect to.
        ##      3. Arguments to be used during probing.
		##		4. Timeout

	my $err_code = 0;
	my $err_string;

        if(scalar(@_) < 3)
	{
		return (1,"Invalid number of arguments");
	}

	## Check the fifth argument to figure if secure-ldap 
	## has to be used
	my $scheme_str = "ldap";
  	if(defined($_[4]) && ($_[4] == 1))
  	{
    		$scheme_str = "ldaps";
  	}

	## Parse the argument given, to get bdn,base,password,filter.
        ## If parsing fails, it is monitoring probe failure.
	$_[2]=~/base=([^;]+)(;bdn=([^;]+))?(;password=([^;]+))?(;filter=([^;]+))?(;attribute=([^;]+))?/ 
		or return (1,"Invalid argument format");

	## Try to connect to the server
	my $ldap;
	if (Netscaler::KAS::is_ipv4_address($_[0])) {
		$ldap=Net::LDAP->new($_[0].":".$_[1],scheme=>$scheme_str,Timeout=>$_[3])
        	or return (1,"Failed to contact server - $!");
	} else {
		$ldap=Net::LDAP->new($_[0], port => $_[1], scheme=>$scheme_str,Timeout=>$_[3], inet6=>1)
		or return (1,"Failed to contact server - $!");
	}
        my $mesg;

	my ($base,$binddn,$password,$filter,$attribute)=($1,$3,$5,$7,$9);

	
	## Try binding to the ldap server.
	if(!defined($password))
	{
	        if(!defined($binddn))
	        {
	                $mesg=$ldap->bind;
	        }
	        else
	        {
	                $mesg=$ldap->bind($binddn);
	        }
	}
	else
	{
	        if(!defined($binddn))
        	{
	                $mesg=$ldap->bind(password=>$password);
	        }
	        else
	        {
	                $mesg=$ldap->bind($binddn,password=>$password);
	        }
	}

	## Bind failed, so report it 
        if($mesg->code)
	{
        	return (1, "Failed to bind to server - $!");
	}

	## If no filter is give, try to match all the classes
        if(!defined($filter))
	{
        	$filter="objectclass=*";
	}

	## Do a search, if it fails, report it
        if(!defined($attribute))
	{
		$mesg=$ldap->search(base=>$base,filter=>$filter);
	}	
	else
	{
		$mesg=$ldap->search(base=>$base,filter=>$filter,attrs=>[$attribute]);
	}

	#It is a success only when the match exists
	if(!($mesg->count))
	{	
		($err_code = 1,$err_string = "Search succeeded but no elements found - $!"); 
	}
	
	## Probe Completed.
	$ldap->unbind;
        return 0
		if ($err_code == 0);
	return ($err_code, $err_string);
}																															
## Register ldap probe handler, to the KAS module.
probe(\&ldap_probe);
