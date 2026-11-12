#!/usr/bin/perl -w
################################################################
## This is a netscaler supplied script. Please dont modify this as it will be overwritten during 
## reboot. If you want to modify, please make a copy of this script and modify.

## This script is used to do NTLM monitoring using KAS feature. 
#3 Argument LIST - *Don't change the ordr of argument.
## -> The mandatory arguments are:
## 	1. url		=> URL to be accessed. Give the full URL e.g. -> url=http://a.b.c.d/ntlm/file51.html;
##	2. user		=> User Name, e.g. -> user name to be used for authentication e.g. -> user=<USER>; or <DOMAIN>\<USER>;
##	3. password	=> This is the password that will be used to login into the server e.g. -> password=<password>
## 	4. domain	=> Domain to be used e.g. -> domain=NETSCALER;
## -> The optional arguments are:
#	5. realm	=> realm for the server. For testing purpose you can set it to null. Based on realm a differnt context is set on client.
#		  e.g. -> realm=''; relam=<realm>;
#	6. version:	=> ntlm version to be used. if it is 2, ntlmv2 will be used. default ntlmv1 
#
## The arguments must be of the following form:
##    	url=<url>;user=<username>[;password=<password>][domain=<domain>][realm=<realm>][version=<NTLM version to be used. default '1'>]
## Examples:
##	set monitor ...  -scriptArgs url='http://a.b.c.d/ntlm/file51.html';user=user1;password=password;domain=<domain>;realm='';version=2"
##	set monitor ...  -scriptArgs url='http://a.b.c.d/ntlm/file51.html';user=domain\user1;password=password;"

use strict;
use LWP::UserAgent;
use Authen::NTLM;
use HTTP::Request::Common;
use Net::INET6Glue::INET_is_INET6;
use Netscaler::KAS;

## This function is a handler for performing ftp probe in KAS mode 
sub ntlm_probe
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
	my $netloc;
	my $host;
	my ($url,$realm,$username,$password, $version)=('','','','', 1); ## default values

	## Parse the argument given, to get url,realm, use name,password, ntlm version.
	## If parsing fails, it is monitoring probe failure.
        $_[2]=~/url=([^;]+);user=([^;]+)(;password=([^;]+)?)?(;domain=([^;]+)?)?(;realm=([^;]+)?)?(;version=([^;]+))?;?/
	                or return (1,"Invalid argument format");
	
	
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

	if ($url =~ /http:\/\/([^\/]+).*/ )
	{
		my $ind = index($1, "[");
		if ($ind != -1)
		{
			my $inl = index($1, "]");
			if ($inl == -1)
			{
				return ( $err_code = 1, $err_string = "Error parsing IPv6 address\n");
			}
			my $ip6  = substr $1,$ind+1, ($inl-$ind)-1;
			$host = "[" . $ip6 . "]";
		}
		else
		{
			my @ip4 = split /:/, $1;
			$host = "$ip4[0]";
		}
		
	}
	else
	{
		$host = $_[0];
	}
	$netloc = "$host".":"."$_[1]";

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

## Register ntlm probe handler, to the KAS module.

probe(\&ntlm_probe);
