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

## This script is used to do MYSQL server monitoring
## using KAS feature.
## The mandatory arguments are:
##      1. Database to which the user is going to connect
##      2. User name
## The optional arguments are:
##      1. password:  This is the password that will be used to
##                    login into the server. If no password is
##                    given a blank password is used.
##      2. SQL query
## Example:
## 		set monitor ...  -scriptArgs "database=test;user=user1;password=password;
##										query=select column1 from table1"


use strict;
use DBI;
use Netscaler::KAS;

## This function is a handler for performing MYSQL probe in KAS mode
sub mysql_probe
{

	my $err_code = 0;
	my $err_string;
	## There must be at least 3 arguments to this function.
        ##      1. First argument is the IP that has to be probed.
        ##      2. Second argument is the port to connect to.
        ##      3. Arguments to be used during probing.
        if(scalar(@_) < 3)
	{
		return (1,"Insufficient number of arguments");
	}

	## Parse the argument given, to get database,user name,password,SQL query.
        ## If parsing fails, it is monitoring probe failure.
	$_[2]=~/database=([^;]+);user=([^;]+)(;password=([^;]+))?(;query=([^;]+))?/ 
		or return (1,"Invalid argument format");

	(my $database,my $username,my $password,my $sql_query)=($1,$2,$4,$6);

	## If no password is given, try blank password
	if(!defined($password))
	{
		$password="";
	}

	## Try to connect to the server
	my $db_handle = DBI->connect("dbi:mysql:database=$database:host=$_[0]:$_[1]",$username,$password)
    		or return (1,"Connection to database failed - $!");

	## If no query is given then it is probe success , else try executing the query
	if(!defined($sql_query))
	{
		$db_handle->disconnect();
		return 0;
	}	

	## Problem during query execution, report failure
	my $statement = $db_handle->prepare($sql_query) 
		or ($err_code = 1, $err_string = "Preparation of SQL query failed");

	if ($err_code == 0)
	{
		if (!$statement->execute()) {
		   $err_code = 1;
		   $err_string = "Execution of SQL query failed: " . $statement->errstr();
		}
	}

	## Cleanup statement handler.
	$statement->finish();
	## Probe Completed.
	$db_handle->disconnect();

	return 0
		if ($err_code == 0);
	return ($err_code, $err_string);
}

## Register MS SQL probe handler, to the KAS module.
probe(\&mysql_probe);
