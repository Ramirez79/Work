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

## This script is used to do RDP (Windows Terminal Server) monitoring
## This does very basic monitoring of just sending a ISO dgram and checking for success
## using KAS feature.

use strict;
use IO::Socket; 
use IO::Socket::INET6;
use Netscaler::KAS;

my $ISO_PDU_CR=0xE0; # Connection Request
my $ISO_PDU_CC=0xD0; # Connection Confirm 
my $ISO_PDU_DR=0x80; # Disconnect Request
my $ISO_PDU_DT=0xE0; # Data 
my $ISO_PDU_ER=0x70; # Error

sub create_iso_connect_packet
{
	my $message_type=$_[0];	# Get message type	
	my $message="\03\00\00\013\06\xE0\00\00\00\00\00";
	return $message;	
}

sub rdp_probe
{
	## There must be at least 3 arguments to this function.
        ##      1. First argument is the IP that has to be probed.
        ##      2. Second argument is the port to connect to.
	my $err_code = 0;
	my $err_string;
        if(scalar(@_) < 2)
        {
		return (1,"Insufficient number of arguments");
        }
    my $sock;
	if (Netscaler::KAS::is_ipv4_address($_[0])) {
		$sock = new IO::Socket::INET ( 
			PeerAddr => "$_[0]", 
			PeerPort => "$_[1]", 
			Proto => 'tcp', 
			); 
		return (1,"Could not create sock- $!") unless $sock;
	} else {
		$sock = new IO::Socket::INET6 ( 
			PeerAddr => "$_[0]", 
			PeerPort => "$_[1]", 
			Proto => 'tcp', 
			); 
		return (1,"Could not create sock- $!") unless $sock;
    }
	binmode($sock);
	my $message=create_iso_connect_packet(); #Connection packet request from me
	$sock->syswrite($message,11);
	my $version;
	$sock->sysread($version,4);
	$version=unpack("N",$version); # Converting to little endian
	if((($version & 0xff000000)>>24) == 3)
	{
		my $length;
		$length=($version & 0xffff) - 6;
		my $code;	
		$sock->sysread($code,2);
		$code=unpack("n",$code);
		if(($code & 0xff) == $ISO_PDU_CC)
		{
			my $data;
			$sock->sysread($data,$length);
		}
		else
		{
			($err_code = 1, $err_string = "Connection not confirmed")
		}
	}
	else
	{
		($err_code = 1, $err_string = "Version mismatch");
	}
	close($sock);
	return 0	
		if ($err_code == 0);
	return ($err_code, $err_string);
}

## Register RDP probe handler, to the KAS module.
probe(\&rdp_probe);

