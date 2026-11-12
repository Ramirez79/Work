package Netscaler::KAS;
require("syscall.ph");

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

use strict;

my $logfile = "/var/nslog/nsumond/nsumond.log"; 

BEGIN {
	use Exporter ();
	use vars qw($VERSION @ISA @EXPORT @EXPORT_OK %EXPORT_TAGS);


	$VERSION = 1.00;
	@ISA = qw(Exporter);
	@EXPORT = qw(&probe &script_wakeup);
	%EXPORT_TAGS = ( );
	@EXPORT_OK = qw($foo);
}
use vars @EXPORT_OK;
$foo = 100;

sub is_ipv4_address
{
	my $address = $_[0];
	if ($address =~ m/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)/) {
		return 1;
	}
	return 0;
}

sub log_monitor_failure_reason
{
	my $exit_code = "";
	my $exit_reason = "";
	# Unlikely, but a safety check
	if (!defined($_[0])) {
		return;
	} else {
		$exit_code = $_[0];
	}
	if (defined($_[1])) {
		$exit_reason = $_[1];
	}
	open(LOGFILE,">>".$logfile);
	syswrite(LOGFILE,"$exit_code$exit_reason");
	close(LOGFILE);
}

sub probe 
{
	my $input;
	if (!defined($_[0]))
	{
		die "Pass function pointer\n";
	}
	my ($fnptr) = $_[0];

	my @arg_arr;
	my $timeout;
	my $secure;
	# Store the value of the partition ID that was set previously
	my $partition_id_prev = -1;
	my $partition_id = 0;
	my $ret = 0;
	my $nsumon_debug=0;
	push @arg_arr, $ENV{'nsumon_ip'};
	push @arg_arr, $ENV{'nsumon_port'};
	push @arg_arr, $ENV{'nsumon_args'}; 
	$timeout = $ENV{'nsumon_resptout'}-1;
	if ($timeout < 1)
	{
		$timeout=1;
	}
	
	push @arg_arr, $timeout;
	$secure = $ENV{'nsumon_secure'};
	push @arg_arr, $secure;

	if (length $ENV{'nsumon_partition'})
	{
		$partition_id = $ENV{'nsumon_partition'} + 0;
	}

	# Before exec, nsumond had duped the connected pipe fd to <STDIN>. This is to make sure perl knows which fd to use for reading/writing. 
	# But as we cannot use filehandle STDIN for writing, so again creating another filehandle to write to the pipe.
	open(my $fh, "+<&=", 0) or die "Error: $!";

	while (1)
	{
		# Verify if invoking syscall() is needed
		if ($partition_id != $partition_id_prev)
		{
			# Invoke syscall() to set the partition ID
			$!=0;
			$ret=syscall(&SYS_setfib, $partition_id);
			if ($ret < 0 && $! != 0)
			{
				syswrite $fh,"500,Internal Error\n";
				# Do not execute the function - can result in
				# incorrect results
				my $time = localtime();
				log_monitor_failure_reason("$time: $0 Script failed. setfib() failed with error : $! (Partition ID: $partition_id)\n");
				goto WAIT_FOR_INPUT;
			}
		}

		# Save the partition ID set
		$partition_id_prev = $partition_id;
		my @retval= $fnptr->(@arg_arr);
		
		if(defined($retval[1]))
		{
			syswrite $fh, "$retval[0],$retval[1]\n";
		}
		else
		{
			syswrite $fh, "$retval[0]\n";
		}
		
		# Write monitor failure reason now.
		if((defined($retval[1]) || $retval[0]!=0 ))
		{
			my $time = localtime();
			my $exit_code = "";
			my $exit_reason = "";
			if ($retval[0]!=0) {
				$exit_code = "$time: $0 Script failed. Exit code : $retval[0] (Partition ID: $partition_id)\n";
			}
			if (defined($retval[1])) {
				$exit_reason = "$time: $0 Exit Reason : ($retval[1]) (Partition ID: $partition_id)\n"
			}
			log_monitor_failure_reason($exit_code,$exit_reason);
		}

WAIT_FOR_INPUT:
		chomp($input=<STDIN>);

		# Return if the input is empty
		if (!defined $input) {
			return;
		}

		@arg_arr = split(' ', $input, 6);

		# The order of input from nsumond: 
		# 		arg_arr[0] = IP Address, arg_arr[1] = port, 
		# 		arg_arr[2] = partition ID,
		# 		arg_arr[3] = timeout,
		# 		arg_arr[4] = secure setting, 
		# 		arg_arr[5] = script args

		$partition_id = $arg_arr[2]+0;
		$timeout = $arg_arr[3]-1;
		$secure = $arg_arr[4];

		if (defined $arg_arr[5])
		{
			$arg_arr[2] = $arg_arr[5];
		}
		else
		{
			#insert a dummy arg_arr[5] and then swap
			push @arg_arr, "";
			$arg_arr[2] = "";
		}

		pop @arg_arr;
		pop @arg_arr;
		pop @arg_arr;

		if ($timeout < 1)
		{
			$timeout = 1;
		}

		# Modified the order of input to script: 
		# 		arg_arr[0] = IP Address, arg_arr[1] = port, 
		# 		arg_arr[2] = script args, 
		# 		arg_arr[3] = timeout, 
		# 		arg_arr[4] = secure
		push @arg_arr,$timeout;
		push @arg_arr,$secure;
	}
	close $fh;
}
1;
