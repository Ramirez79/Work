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
## This is a netscaler supplied module. Please dont modify this as it will be overwritten during 
## reboot. If you want to modify, please make a copy of this script and modify.
## This script does a sanity check of a user provided script
## 1. It checks whether the user has included the Netscaler::KAS module
## 2. It checks whether user has registered a function for the KAS to execute.
## 3. Remaning all the sanity is left to perl -c
## 4. It then excutes the script with appropriate environment setup
## 5. Then reports the success status of the script.
## 6. Script take input in the following format, scriptname, IP , PORT, arguements to the script

use Switch;
my $iterator=0;
my $scriptname;

die "Usage: nsumon-debug.pl <scriptname> <IP> <port> <timeout> <partitionID> [scriptarguments] [is_secure]\n"
	if( $#ARGV < 5  || $#ARGV > 6);

for(;$iterator <= $#ARGV ; $iterator++)
{
	switch($iterator)
	{
		case "0"
			{
				die "$ARGV[$iterator] does not exist\n"
					if(!-e "$ARGV[$iterator]");
				die "$ARGV[$iterator] does not have executable permission\n"
					if(!-x "$ARGV[$iterator]");
				die "Netscaler::KAS is not included\n"
					if(!`grep 'Netscaler::KAS' $ARGV[$iterator]`);
				die "probe handler is not registered to KAS\n"
					if(!`grep 'probe(' $ARGV[$iterator]`);
				$scriptname=$ARGV[$iterator];
			}
		case "1"
			{
				$ARGV[$iterator] =~ /([\d.])+/;
				die "$ARGV[$iterator] is a invalid IP\n"
					if(!defined($1));
				$ENV{'nsumon_ip'}=$ARGV[$iterator];
			}
		case "2"
			{
				$ARGV[$iterator] =~ /([\d])/;
				die "$ARGV[$iterator] is a invalid port\n"
					if(!defined($1));
				$ENV{'nsumon_port'}=$ARGV[$iterator];
			}
		case "3"
			{
				$ARGV[$iterator] =~ /([\d])/;
				die "$ARGV[$iterator] is a invalid Response Timeout\n"
					if(!defined($1));
				$ENV{'nsumon_resptout'}=$ARGV[$iterator];
					
			}
		case "4"
			{
				 $ENV{'nsumon_partition'}=$ARGV[$iterator];
			}

		case "5"
			{
				$ENV{'nsumon_args'}=$ARGV[$iterator];
			}

		case "6"
			{
				$ENV{'nsumon_secure'}=$ARGV[$iterator];
			}

	}
}
$ENV{'PERLLIB'}='/netscaler/monitors/perl_mod';
die "Script has some compilation errors\n"
	if(`perl -c $scriptname`);
`perl $scriptname`;
