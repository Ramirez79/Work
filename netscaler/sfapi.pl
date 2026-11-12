#!/usr/bin/env perl

use LWP::UserAgent;
use HTTP::Request::Common;
use HTTP::Request;
use HTTP::Response;
use Net::INET6Glue::INET_is_INET6;
use URI::URL;
use IO::Socket::SSL;
use JSON::PP;
use Data::Dumper;
use strict;

use constant {
	SF_URL => "/Citrix/Roaming/Accounts/",
	SF_API_URL => "/Citrix/Configuration/Deployment/GetSummary",
};

use constant {
	DOWN => 2,
	UP => 1,
	YES => 1,
	NO => 2,
	UNSET => 0,
};

use constant {
	SERVER => 1,
	SFAPI => 2,
	SF => 3,
	STORE => 4,
};

sub main {

	if (scalar(@ARGV) < 2) {
		print("Wrong number of arguments");
		return 0;
	}

	my $input_url = new URI::URL($ARGV[0]); #NSCLI will ensure this is URL formatted like "http(s)://...", but we still need to inspect the path
	my $timeout = $ARGV[1];
	
	if (! $input_url)  {
		print("URL parsing failed");
		return 0;
	}

	my $path =  $input_url->epath;
	my $base_url = $input_url->scheme . "://" . $input_url->host . ":" . $input_url->port;
	my $request_url;


	my $connection = LWP::UserAgent->new(timeout => "$timeout", keep_alive => 1, ssl_opts => {verify_hostname => 0, SSL_verify_mode => SSL_VERIFY_NONE}, requests_redirectable => []);

	# These values map back to CLI enums
	my $serverstatus = UNSET;
        my $serverissf = UNSET;
	my $apisupport = UNSET; 
	my $storelist = "";
	my $storestatus = UNSET;
	my $skip_oldsf = 0;
	my $ret;

	my $operation = SERVER;

	my $do_run = 1;
	while($do_run) {
		if ($operation == SERVER) {
			if (($serverstatus = get_server_status($base_url, $connection)) == DOWN) {
				last; # abort
			}

			$operation = SFAPI;
			next;
		}
		elsif ($operation == SFAPI) {
			($storelist, $apisupport) = is_storefront_api($base_url, $connection);
			if ($apisupport == -1) {
				$serverstatus = DOWN;
				last;
			}
			elsif ($apisupport == YES) {
				$serverissf = YES;

				# Skip next SF detection, we already marked it as SF.
				$operation = STORE;
			}
			else {#NO
				# Might be old SF, check it
				$operation = SF;
			}

			next;
		}
		elsif ($operation == SF) {
			if (($serverissf = is_storefront($base_url, $connection)) == -1) {
				$serverstatus = DOWN;
				last;
			}

			$operation = STORE;
			next;
		}
		elsif ($operation == STORE) {
			chomp($path);
			
			# Only check a store when a path has been applied
			if ($path ne "/") {
				if (($storestatus = is_available_store($base_url . $path, $connection)) == -1) {
					$serverstatus = DOWN;
				}
			}

			#Otherwise, we're done
			$do_run = 0;
			last;
		}
	}

	print($serverstatus . ";" . $serverissf . ";" . $apisupport . ";" . $storestatus . ";" . $storelist . "\n");
	exit 0;

}

sub get_server_status {
	my $request_url = $_[0];
	my $connection = $_[1];
	my $response;
	my $ret;
	my @valid_statuses = ("");

	if (($response = get_response_for_request($request_url, "GET", $connection)) == -1) {
		return DOWN;
	}
	if (($ret = scan_response_for_status($response, \@valid_statuses)) == -1) {
		return DOWN;
	}
	return UP;
}

sub is_storefront_api {
	my $request_url = $_[0] . SF_API_URL;
	my $connection = $_[1];
	my $response;
	my $store_list;
	my $ret;
	my @valid_statuses = ("200"); 

	if (($response = get_response_for_request($request_url, "POST", $connection)) == -1) {
		return ($store_list, -1);
	}

	if (($ret = scan_response_for_status($response, \@valid_statuses)) == -1) {
		return ($store_list, -1);
	}
	elsif ($ret == YES) {
		#get store list also
		$store_list = get_store_list_str_from_api_response($response);
		return ($store_list, YES);
	}

	return ($store_list, NO);
}

sub get_store_list_str_from_api_response {
	my $response = $_[0];
	my $json = JSON::PP->new;
	my $store_list_str = "";
	my $i = 0;

	my $stores;
	my $store;

	my $rfwebs;
	my $rfweb;
	my $rfweb_url;

	my $deserialized_json;

	my $json_payload = $response->decoded_content;

	#print $response->decoded_content;
	eval {
		$deserialized_json = $json->decode($json_payload);
	};
	if ($@) {
		#Return empty store list
		return $store_list_str;
	}
	$stores = $deserialized_json->{result}->{stores};

	foreach $store (@$stores) {
		$rfwebs = $store->{receiverForWebSites};
		foreach $rfweb (@$rfwebs) {
			if ($i) {
				$store_list_str .= ", ";
			}
			$rfweb_url = new URI::URL($rfweb->{url});
			$store_list_str .= $rfweb_url->epath;
			$i++;
		}
	}
	return $store_list_str;
}

sub is_storefront {
	my $request_url = $_[0] . SF_URL;
	my $connection =  $_[1];
	my $response;
	my $ret;
	my @valid_statuses = ("200", "401");

	if (($response = get_response_for_request($request_url, "GET", $connection)) == -1) {
		return -1;
	}

	if (($ret = scan_response_for_status($response, \@valid_statuses)) == -1) {
		return -1;
	}
	elsif($ret == 1) {
		return YES;
	}

	return NO;
}

sub is_available_store {
	my $request_url = $_[0];
	my $connection =  $_[1];
	my $response;
	my $ret;
	my @valid_statuses = ("200", "401");

	if (($response = get_response_for_request($request_url, "GET", $connection)) == -1) {
		return -1;
	}
	
	if (($ret = scan_response_for_status($response, \@valid_statuses)) == -1) {
		return -1;

	}
	elsif ($ret == 1) {
		return YES;
	}

	return NO;

}

sub get_response_for_request {
	my $url = $_[0];
	my $reqtype = $_[1];
	my $connection = $_[2];

	my $request = HTTP::Request->new($reqtype, $url);

	if ($reqtype == "POST") {
		$request->header('content-length' => '0');

	}
	my $response = $connection->request($request);

	if (! $request || ! $response) {
		print("Internal error, failed to send request.");
		return -1;
	}
	return $response;
}

# 1 = matched status, 0 = unmatched status, -1 = connection error
sub scan_response_for_status {
	my $response = $_[0];
	my $desired_statuses = $_[1];
	my $desired_status;

	my $status = $response->status_line;

	foreach $desired_status  (@$desired_statuses) {
			
		if(index($status, "500") !=  -1) {
			# Error on a timeout 500
			if (index($status, "timeout")) {
				#connection error
				return -1;
			}
				# Otherwise pass the 500 through, the user may want it
		}
		
		if(index($status, $desired_status) != -1) {
			return 1;
		}
	}
	# No statuses matched, not a connection error
	return 0;
}



&main;
