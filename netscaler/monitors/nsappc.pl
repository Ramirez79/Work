#!/usr/bin/perl -w
## This script is used to do AppController(APPC)  monitoring using KAS feature.

use Cwd;
use POSIX qw(ceil floor);
use LWP::UserAgent;
use HTTP::Request::Common;
use HTTP::Request;
use HTTP::Response;
use Net::INET6Glue::INET_is_INET6;
use Netscaler::KAS;
use strict;

sub Appc_probe
{
        $ENV{HTTPS_DEBUG} = 0;
        my $errorcode = 0;
        my $error = 1;
###############COLLECT USER INPUT######################
##  There must be at least 4 arguments to this function.
##  1. First argument is the server IP address.
##  2. Second argument is the server port.
##  3. Third argument is empty. No argument is passed to the script.
##  4. Timeout.
##  5. Secure (YES/NO).

        if(scalar(@_) < 3)
        {
                $errorcode = "Insufficient number of arguments";
                goto ERROR;
        }
        my $proto;
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

########Auth Service POST 1#################################
        my $url = "$baseurl/Citrix/Authentication/auth/v1/token";
        my $request = HTTP::Request->new('POST', $url);
        unless($request){
                $errorcode = "Resources not available to create auth service request";
                goto ERROR;
        }
        my %HEADER = (
                'Content-Length' => 0,
        );
        $request->header(%HEADER);
        my $response = $conn->request($request);
        if($response->code( ) != 401){
                $errorcode = $response->status_line;
                goto ERROR;
        }

        my $temp = $response->header('WWW-Authenticate');
        unless($temp){
                $errorcode = "WWW-Authenticate header not returned in auth service response";
                goto ERROR;
        }

        unless($temp=~/serviceroot-hint=([^,]+),?/){
                $errorcode = "1. Invalid WWW-Authenticate header format in auth service response";
                gotr ERROR;
        }
        my $hint=$1;
        unless($hint =~/\/Citrix\/Authentication\/auth\/v1\/token/){
                $errorcode = "2. Invalid WWW-Authenticate header format in auth service response";
                goto ERROR;
        }

        unless($temp=~/locations=([^,]+),?/){
                $errorcode = "3. Invalid WWW-Authenticate header format in auth service response";
                goto ERROR;
        }
        my $locations=$1;
        unless($locations=~/\/Citrix\/Authentication\/auth\/v1\/protocols/){
                $errorcode = "4. Invalid WWW-Authenticate header format in auth service response";
                goto ERROR;
        }
########Auth Service POST 2#################################
        $url = "$baseurl/Citrix/Authentication/auth/v1/protocols";
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

        $errorcode = "Exited Successfully";
        $error = 0;
ERROR:
        $cache->drop();
        return($error, $errorcode);
}

probe(\&Appc_probe);
