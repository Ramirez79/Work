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

## This script is used to monitor more than one type of service.
## The mandatory arguments are:
##      1. service type, which tells what service to be monitored. 
## Argument to the script must be of the following format,
## 	monitor=<monitor-name>[;remaining-arguments]
##	Example:
##		set monitor ...  -scriptArgs "monitor=ftp;file=file.c;user=user1;password=password"


## Setting the log value to none, so nothing is dumped to stdin.
$ENV{'MAX_LOG_LEVEL'} = 'none';

use strict;
use Net::SMTP;
use Net::POP3;
use Net::LDAP;
use Net::NNTP;
use Net::FTP;
use DBI;
use Net::SNMP;
use LWP::UserAgent;
use Authen::NTLM;
use HTTP::Request::Common;
use Netscaler::KAS;

## This function is a handler for performing smtp probe.
sub smtp_probe
{
	## Try to connect to the server
	my $smtp=Net::SMTP->new($_[0].":".$_[1],Timeout=>$_[3]) 
		or return (1,"Connection to server failed - $!");

	## Probe succeeded
	$smtp->quit;
	return 0;
}

## This function is a handler for performing nntp probe. 

sub nntp_probe
{
	my $err_code = 0;
        my $err_string;	

	## If no script arguments are given, report it as script failure
	if(!defined($_[2]) || !length($_[2]))
	{
		return (1,"Arguments not given");
	}

	## Parse the argument given, to get group,user name,password.
        ## If parsing fails, it is monitoring probe failure.
	$_[2]=~/^group=([^;]+)(;user=([^;]+))?(;password=([^;]+))?/ 
		or return (1,"Invalid argument format");

	## Try to connect to the server
	my $nntp=Net::NNTP->new($_[0].":".$_[1],Timeout=>$_[3]) 
		or return (1,"Connection to server failed - $!");


	my ($group,$username,$password)=($1,$3,$5);
		
	## If user name, is given lookup for a group on that user
        ## If password, is given use it while querying for the group
	if(defined($username))
	{
	        if(!defined($password))
		{
		        $password="";
		}
	        $nntp->authinfo($username,$password);
	}

	## Group not found, report failure
	$nntp->group($group) 
		or ($err_code = 1, $err_string = "Group not found - $!" );

	$nntp->quit;
	## Group found hence probe success.
	return 0
                if ($err_code == 0);
        return ($err_code, $err_string);
}

## This function is a handler for performing ldap probe.
sub ldap_probe
{
	my $err_code = 0;
        my $err_string;
	
	## If no script arguments are given, report it as script failure
	if(!defined($_[2]) || !length($_[2]))
	{
		return (1,"Arguments not given");
	}

	## Parse the argument given, to get bdn,base,password,filter,attribute.
        ## If parsing fails, it is monitoring probe failure.
	$_[2]=~/^base=([^;]+)(;bdn=([^;]+))?(;password=([^;]+))?(;filter=([^;]+))?(;attribute=([^;]+))?/ 
		or return (1,"Invalid argument format");

	## Try to connect to the server
	my $ldap=Net::LDAP->new($_[0].":".$_[1],Timeout=>$_[3]) 
		or return (1,"Connection to server failed - $!");
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
		return (1,"Bind failed - $!");
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
		$mesg=$ldap->search(base=>$base,filter=>$filter,attrs=>$attribute);
	}

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

## This function is a handler for performing ftp probe.
sub ftp_probe
{
	my $err_code =0;
        my $err_string;
	
	## If no script arguments are given, report it as script failure
	if(!defined($_[2]) || !length($_[2]))
	{
		return (1,"Arguments not given");
	}

	## Parse the argument given, to get file,user name,password.
        ## If parsing fails, it is monitoring probe failure.
	$_[2]=~/^file=([^;]+)(;user=([^;]+))?(;password=([^;]+))?/ 
		or return (1,"Invalid argument format");

	## Try to connect to the server
	my $ftp=Net::FTP->new($_[0].":".$_[1],Timeout=>$_[3]) 
		or return (1,"Connection to server failed - $!");
	my ($file,$username,$password)=($1,$3,$5);

	## If no user name is given, try anonymous user
	if(!defined($username))
	{
		$username="anonymous";
	}

	## If no password is given, and it is anonymous user,
        ## password is defaulted to anonymous@anonymous.com
	if(!defined($password))
	{
		if($username eq "anonymous")
                {
                        $password='anonymous@anonymous.com';
                }
                else
                {
                        $password="";
                }
	}

	## Login failed, so report failure
        $ftp->login($username,$password) or
                ( $err_code = 1, $err_string = "Login failed - $!");
	
	## File not found, return failure
        if ($err_code == 0)
        {
                $ftp->ls($file) or
                        ( $err_code = 1, $err_string = "File not found - $!");
        }

	## Probe completed
	$ftp->quit;

	return 0
                if ($err_code == 0);

        return ($err_code, $err_string);
}

## This function is a handler for performing pop3 probe
sub pop3_probe
{
	## If no script arguments are given, report it as script failure
	if(!defined($_[2]) || !length($_[2]))
	{
		return (1,"Arguments not given");
	}

	$_[2]=~/^user=([^;]+)(;password=([^;]+))?/ 
		or return (1,"Invalid argument format");

	## Try to connect to the server
	my $pop3=Net::POP3->new($_[0].":".$_[1],Timeout=>$_[3]) 
		or return (1,"Connection to server failed - $!");
	

	my ($username,$password)=($1,$3);

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

## This function is a handler for performing MYSQL probe
sub mysql_probe
{
	my $err_code = 0;
        my $err_string;

	## If no script arguments are given, report it as script failure
	if(!defined($_[2]) || !length($_[2]))
	{
		return (1,"Arguments not given");
	}


	## Parse the argument given, to get database,user name,password,SQL query.
	## If parsing fails, it is monitoring probe failure.
	$_[2]=~/^database=([^;]+);user=([^;]+)(;password=([^;]+))?(;query=([^;]+))?/ 
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
                $statement->execute()
                        or ($err_code = 1, $err_string = "Execution of SQL query failed");
        }

        ## Probe Succeeded.
        $db_handle->disconnect();

        return 0
                if ($err_code == 0);
        return ($err_code, $err_string);
}

## This function is a handler for performing snmp probe in KAS mode
sub snmp_probe
{
	## If no script arguments are given, report it as script failure
	if(!defined($_[2]) || !length($_[2]))
	{
		return (1,"Arguments not given");
	}


	## Parse the argument given, to get community,oid,threshold.
        ## If parsing fails, it is monitoring probe failure.
	$_[2]=~/^community=([^;]+);oid=([^;]+)(;threshold=([0-9]+))?/
	        or return (1,"Invalid Argument format");

	my ($community,$oid,$threshold)=($1,$2,$4);

	## Try to connect to the server
	my ($session, $error) = Net::SNMP->session(
      -hostname  =>	$_[0], 
      -community => "$community",
      -port      =>	$_[1],
	-timeout => $_[3],
	-retries => 0
   	);

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

## This function is a handler for performing ntlm probe in KAS mode 
sub ntlm_probe
{
	## If no script arguments are given, report it as script failure
	if(!defined($_[2]) || !length($_[2]))
	{
		return (1,"Arguments not given");
	}

	my $err_code =0;
	my $err_string;	
	my $netloc;
	my ($url,$realm,$username,$password, $version)=('','','','', 1); ## default values

	## Parse the argument given, to get url,realm, use name,password, ntlm version.
	## If parsing fails, it is monitoring probe failure.
        $_[2]=~/url=([^;]+);user=([^;]+)(;password=([^;]+)?)?(;domain=([^;]+)?)?(;realm=([^;]+)?)?(;version=([^;]+))?;?/
	                or return (1,"Invalid argument format");
	
	$netloc = "$_[0]".":"."$_[1]";
	
	$url = $1;				
	if (defined $6) {			# check if domain is defined
		$username = $2;			# domain is given and username also contains domain. let's use username only
		if ( $2 !~ /\\/ ) {		# make sure user already doesn't contain domain
			$username = "$6"."\\"."$username";     # e.g.  'DOMAIN\NETSCALER'
		}
	} else {
		$username = $2;
		if ( $2 !~ /\\/ ) {
			$username= "\\". "$username"; 	# it seems LWP required the username to be "\user" if domain is not specified.
		} 
	}
	if (defined $4) { $password = $4;}
	if (defined $8) { $realm = $8;}
	if (defined $10) { $version = $10;}

	if (2 == $version) {
		ntlmv2(1);
	}
	
	my $ua = LWP::UserAgent->new(keep_alive=>1);
	$ua->timeout("$_[3]");
	$ua->credentials($netloc, $realm, $username, $password);

	my $request = GET $url;
	my $response = $ua->request($request);
	
	my $cache = $ua->conn_cache();
	$cache->drop();

	if ($response->is_success) {
		return 0;
	}
	else {
		return ( $err_code = 1, $err_string = "Authentication failed!-> " . $response->code." ".$response->status_line . " $!\n");
	}
}


## This function does the appropriate dispatching based on the monitor type
sub probe_handler
{
	## Temporary array that is used for parameter passing
	my @script_args;
	
	## There must be at least 4 arguments to this function.
        ##      1. First argument is the IP that has to be probed.
        ##      2. Second argument is the port to connect to.
        ##      3. Argument that contains, the probe type and other extra parameters
		##		4. Timeout.

	if(scalar(@_) < 3)
	{
		return (1,"Script arguments not given");
	} 

	my $script_name=$_[2];  

	## The third argument must have monitor=<type>, else it is script failure
	$script_name=~/^monitor=([^;]+)(;(.*))?/ 
		or return (1,"Invalid argument format");

	$script_name=$1;
	$script_args[0]=$_[0];
	$script_args[1]=$_[1];
	$script_args[3]=$_[3];
			
	## If parameters are given, apart from the monitor type,pass it to function
	if(defined($3))
	{
		$script_args[2]=$3;
	}
	else
	{
		undef($script_args[2]);
	}

	## Dispatch to the appropriate probe handler based on the probe type
	if($script_name=~/^[sS][mM][tT][pP]$/)
	{
		return smtp_probe(@script_args);
	}

	if($script_name=~/^[nN][nN][tT][pP]$/)
	{
		return nntp_probe(@script_args);
	}

	if($script_name=~/^[lL][dD][aA][pP]$/)
	{
		return ldap_probe(@script_args);
	}

	if($script_name=~/^[pP][oO][pP]3$/)
	{
		return pop3_probe(@script_args);
	}
	
	if($script_name=~/^[fF][tT][pP]$/)
	{
		return ftp_probe(@script_args);
	}

	if($script_name=~/^[mM][yY][sS][qQ][lL]$/)
	{
		return mysql_probe(@script_args);
	}

	if($script_name=~/^[sS][nN][mM][pP]$/)
	{
		return snmp_probe(@script_args);
	}

	if($script_name=~/^[nN][tT][lL][mM]$/)
	{
		return ntlm_probe(@script_args);
	}


	## Not any of the supported probe type, it is a failure
	return (1,"Monitor type unsupported");
}

## Register probe handler, to the KAS module.
probe(\&probe_handler);
