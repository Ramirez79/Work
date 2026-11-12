#!/usr/bin/perl -w
## This script is used to do StoreFront monitoring using KAS feature.
## The optional arguments are:
## 1. acctservice: If acctservice is '1' script probes for account service else we skip it. 
##	For multi-tenancy configuration users my skip account service for simplicity. Default: YES(1) 
## 2. storename: Storename to be used while probing. Default: Store.
## The arguments must be of the following form:
##	acctservice=<1/0>;storename=<name>;backendserver=<1/0>
## Example:
##	acctservice=1/0;storename=Store;backendserver=1/0;

use Cwd;
use POSIX qw(ceil floor);
use LWP::UserAgent;
use HTTP::Request::Common;
use HTTP::Request;
use HTTP::Response;
use Net::INET6Glue::INET_is_INET6;
use Netscaler::KAS;
use strict;

sub find_service{
	if($_[0]=~/<name>/){
		return(find_service($'));
	}
	else{
		if($_[0]=~/<\/name>/){
			return($`);
		}
	}
}

sub Sf_probe 
{
	$ENV{HTTPS_DEBUG} = 0;
	my $errorcode = 0;
	my $error = 1;
###############COLLECT USER INPUT######################
##  There must be at least 4 arguments to this function.
##  1. First argument is the server IP address.
##  2. Second argument is the server port.
##  3. Third argument is the list of arguments passed to the script in the format explained above. 
##  4. Timeout.
##  5. Secure (YES/NO).

	if(scalar(@_) < 3)
	{
		$errorcode = "Insufficient number of arguments";
		goto ERROR;
	}
	my ($store,$account_service,$proto,$backend_service)= ("Store",0,"http",0);
  	if(defined($_[2])){
		$_[2]=~/(acctservice=([^;]+))?(;?storename=([^;]+))?(;?backendserver=([^;]+))?/
          	or return (1,"Invalid argument format");
 		if($1){
       		$account_service = $2;
  		}
		if ($3){
    		$store="$4";
		}
		if ($5){
		$backend_service = $6;
		}
  	}
 
	my $ip = "";
	if (Netscaler::KAS::is_ipv4_address($_[0]))
  	{
	$ip = "$_[0]";
	}
	else
	{
	$ip = "[$_[0]]";
	}
  	my $port = "$_[1]";
  	if(!defined($_[4]) || ($_[4] == 0))
  	{
    	$proto = "http";
  	}
  	else 
  	{
    	$proto = "https";
  	}
	my $baseurl = "$proto://$ip:$port";
	my $conn = LWP::UserAgent->new(keep_alive => 1,)
        	or return(1, "Resources not available to create keep-alive request");
	$conn->timeout("$_[3]");
	my $cache = $conn->conn_cache();
##############backendserver status####################
	if($backend_service)
	{
		my $url = "$baseurl/StorefrontMonitor/getSFServicesStatus";
		my $request = HTTP::Request->new('GET', $url);
		unless($request){
			$errorcode = "Resources not available to create service request";
			goto ERROR;
		}
		my $response = $conn->request($request);
		unless($response && $response->code( ) == 200){
			if($response){
				$errorcode = $response->status_line;
			}
			else{
				$errorcode = "Service Timedout";
			}
			goto ERROR;
		}
		my $content = $response->content;
		my $servicen;
		my $degrade=0;
		$error = 0;
		{
			my @services = ("W3SVC","WAS","CitrixCredentialWallet","CitrixDefaultDomainService");
			foreach (@services)
			{
				if($content !~ /$_/){
					$error = 1;
					$errorcode = "$_ is not installed ";
					goto ERROR;
				}
			}
		}
		while($content){
			if($content =~ /<status>stopped<\/status>/){
				$servicen = find_service($`);
				if(($servicen !~ /W3SVC/) && ($servicen !~ /WAS/) && ($servicen !~ /CitrixCredentialWallet/) && ($servicen !~ /CitrixDefaultDomainService/)){
					$degrade = 1;
				}
				else{
					$error = 1;
				}
				if($errorcode){
					$errorcode = $errorcode . " $servicen";
				}
				else{
					$errorcode = "$servicen";
				}
			}
			unless($'!~$content){
				last;
			}
			$content = $';
		}
		if($errorcode){
			$errorcode = $errorcode . " stopped running.";
			if($degrade){
				$errorcode = $errorcode . "Degraded Services.";
			}
		}
		else{
			unless($response->content !~ /<status>running<\/status>/){
				goto SUCCESS;
			}
			$errorcode = "No services are installed on the server";
		}
		goto ERROR;
	}
###############Account Service#########################
	unless($account_service){
		goto SKIP_ACCSERVICE;
	}
	my $url = "$baseurl/Citrix/Roaming/accounts";
	my $request = HTTP::Request->new('GET', $url);
	unless($request){
		$errorcode = "Resources not available to create account service request";
		goto ERROR;	
	}
	my $response = $conn->request($request);

	if(($response->code( ) != 401) && ($response->code( ) != 200)){
		$errorcode = $response->status_line;
		goto ERROR;
	}
	if($response->code( ) == 200){
		goto SKIP_ACCSERVICE;
	}

	my $temp = $response->header('WWW-Authenticate');
	unless($temp){
		$errorcode = "WWW-Authenticate header not returned in account service response";
		goto ERROR;
	}	

	unless($temp=~/serviceroot-hint=([^,]+),?/){
		$errorcode = "1. Invalid WWW-Authenticate header format account service response";
		goto ERROR;
	}
	my $hint=$1;
	unless($hint =~ /\/Citrix\/Roaming\/accounts/){
		$errorcode = "2. Invalid WWW-Authenticate header format in account service response";
		goto ERROR;
	}

	unless($temp=~/locations=([^,]+),?/){
		$errorcode = "3. Invalid WWW-Authenticate header format account service response";
		goto ERROR;
	}
	my $locations=$1;
	unless($locations=~/\/Citrix\/Authentication\/auth\/v1\/token/){
		$errorcode = "4. Invalid WWW-Authenticate header format account service response";
		goto ERROR;
	}

SKIP_ACCSERVICE:
#######Discovery#################################
	my $auth_endpoint;
	my $endpoint;
	$url = "$baseurl/Citrix/$store/discovery";
	$request = HTTP::Request->new('GET', $url);
	unless($request){
		$errorcode = "Resources not available to create discovery request";
		goto ERROR;
	}
	$response = $conn->request($request);
	if($response->code( ) != 200){
		$errorcode = $response->status_line;
		goto ERROR;
	}
	if(!$response->header('Content-Type')||($response->header('Content-Type') ne 'application/vnd.citrix.receiver.configure')){
		$errorcode = "1. Invalid response for discovery request";
		goto ERROR;
	}
	unless($response->content =~ m(<Address>([^<]+)</Address>)){
		$errorcode = "2. Invalid response for discovery request";
		goto ERROR;
	}
	unless($1=~/\/Citrix\/$store\/discovery/i){
		$errorcode = "3. Invalid response for discovery request";
		goto ERROR;
	}
	unless($response->content =~ m/<AuthEnd-point>(.*)<\/AuthEnd-point>/){
		$errorcode = "4. Invalid response for discovery request";
		goto ERROR;
	}
	$auth_endpoint = $1;
	unless($response->content =~ m/<End-point>(.*)<\/End-point>/){
		$errorcode = "5. Invalid response for discovery request";
		goto ERROR;
	}
	$endpoint = $1;
	unless($auth_endpoint ne $endpoint){
#### Its an unauthenticated store no need of authentication ##################
		goto SUCCESS;
	}
### we probe based on ip not domain name so removing the domain name
## from the auth endpoint and replacing endpoints with auth as its the
## authentication service we are querying###
	$auth_endpoint =~ s/http[s]*:\/\/([^\/]+)//;	#eventhough configured protocol is http server can send in response authentication endpoint https
	$auth_endpoint =~ s/endpoints/auth/;
	

########Auth Service POST 1#################################
#######for new version of storefront we do authentication per store######
	my $auth_endpoint_token = $auth_endpoint."/token";
	my $auth_endpoint_protocol = $auth_endpoint."/protocols";
	$url = $baseurl.$auth_endpoint_token;
	$request = HTTP::Request->new('POST', $url);
	unless($request){
		$errorcode = "Resources not available to create auth service request";
		goto ERROR;
	}
	my %HEADER = (
        	'Content-Length' => 0,
	);
	$request->header(%HEADER);
	$response = $conn->request($request);
	if($response->code( ) != 401){
		$errorcode = $response->status_line;
	        goto ERROR;
	}	

	$temp = $response->header('WWW-Authenticate');
	unless($temp){
        	$errorcode = "WWW-Authenticate header not returned in auth service response";
		goto ERROR;
	}

	unless($temp=~/serviceroot-hint=([^,]+),?/){
		$errorcode = "1. Invalid WWW-Authenticate header format in auth service response";
		goto ERROR;
	}
	$hint=$1;
	unless($hint =~/$auth_endpoint_token/){
		$errorcode = "2. Invalid WWW-Authenticate header format in auth service response";
		goto ERROR;
	}	

	unless($temp=~/locations=([^,]+),?/){
		$errorcode = "3. Invalid WWW-Authenticate header format in auth service response";
		goto ERROR;
	}
	$locations=$1;
	unless($locations=~/$auth_endpoint_protocol/){
		$errorcode = "4. Invalid WWW-Authenticate header format in auth service response";
		goto ERROR;
	}
########Auth Service POST 2#################################
	$url = $baseurl.$auth_endpoint_protocol;
	$request = HTTP::Request->new('POST', $url);
	unless($request){
		$errorcode = "Resources not available to create second auth service request";
		goto ERROR;
	}
	my $body = '<?xml version="1.0" encoding="utf-8" ?>
<requesttoken xmlns="http://citrix.com/delivery-services/1-0/auth/requesttoken">
<for-service>x</for-service>
<for-service-url>https://host/path</for-service-url>
<reqtokentemplate></reqtokentemplate>
<requested-lifetime>0.01:00:00</requested-lifetime>
</requesttoken>';

	my $len = length($body);
	%HEADER = (
		'Content-Type' => 'application/vnd.citrix.requesttoken+xml',
		'Content-Length' => $len,
		'Accept' => 'application/vnd.citrix.requesttokenresponse+xml, application/vnd.citrix.requesttokenchoices+xml',
	);
	$request->header(%HEADER);
	$request->content($body);
	$response = $conn->request($request);
	if($response->code( ) != 300){
        	$errorcode = $response->status_line;
        	goto ERROR;
	}

	if(!$response->header('Content-Type') || ($response->header('Content-Type') ne 'application/vnd.citrix.requesttokenchoices+xml')){ 
		$errorcode = "Invalid header of respone for auth service request 2";
		goto ERROR;
	}

	unless($response->content=~m{http://citrix.com/delivery-services/1-0/auth/requesttokenchoices}){
		$errorcode = "Invalid body of respone for auth service request 2";
		goto ERROR;
	}

SUCCESS:
	$cache->drop();
	return (0);

ERROR:
	$cache->drop();
	return($error, $errorcode);
}

probe(\&Sf_probe);
