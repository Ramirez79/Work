#!/usr/bin/perl

use strict;
use warnings;
use Switch;
use File::Path;
use Sys::Syslog;
use File::Basename;
use File::Path qw(mkpath);

############################################################################################
# This script is to report if there are any Hard disc failure errors or Compact Flash errors
# or warm restart based on the logfile messages.
# Exit values:
#	  0 : No errors found
#	  1 : Errors found
#	100 : Any other errors
# Run with -HDD (or) -CF (or) -WR option, on Netscaler
# Usage: $0 [ -HDD ] [ -CF ] [ -WR ]
#

###################################################################
# Entry point of the script
##################################################################

my $SYSLOG_FACILITY = "local3.debug";

my $RUN_HDD = 0;
my $RUN_CF = 0;
my $RUN_WR = 0;
if ($#ARGV != 0){
	`logger -p $SYSLOG_FACILITY -i -t ns_monuploadd_err.pl "Usage: $0 [ -HDD | -CF | -WR ]"`;
        exit 100;
}

switch ($#ARGV) {
	case -1  {
		`logger -p $SYSLOG_FACILITY -i -t ns_monuploadd_err.pl "Usage: $0 [ -HDD | -CF | -WR ]"`;
		exit 100;
	}
	case 0 {
		switch ($ARGV[0]){
			case "-HDD"{
				$RUN_HDD = 1;
			}
			case "-CF"{
				$RUN_CF = 1;
			}
			case "-WR"{
				$RUN_WR = 1;
			}
			else {
				die "Usage: $0 [ -HDD | -CF | -WR ]\n";
		 		exit 100;
			}
		}
	}
}

############################################################################
# Set the TMPDIR
############################################################################
our ($TMPDIR, $logfile);
if (-d "/var/tmp") {
	$TMPDIR = "/var/tmp/ns_ch_tmp_$$/";
	eval {mkpath("$TMPDIR")};
	if ($@) {
		`logger -p $SYSLOG_FACILITY -i -t ns_monuploadd_err.pl "Error mkdir $TMPDIR"`;
		exit 100;
	}
}
else {
	$TMPDIR = "/tmp/ns_ch_tmp_$$/";
	eval {mkpath("$TMPDIR")};
	if ($@) {
		`logger -p $SYSLOG_FACILITY -i -t ns_monuploadd_err.pl "Error mkdir $TMPDIR"`;
		exit 100;
	}
}

if (!-d "/flash/nsconfig") {
	`logger -p $SYSLOG_FACILITY -i -t ns_monuploadd_err.pl "This is not a NetScaler. Exiting."`;
	`rm -rf $TMPDIR`;
	exit(100);
}

##########################################################################
# Collect all the set of files where we need to do the search
##########################################################################
#`dmesg -a > $TMPDIR/dmesg-a.out`;

# Uncompress last 25 message files
my $path = "/var/log";
chdir $path or die "Cant chdir to $path $!";

# We skip checking dmesg files for HDD errors because /var/log/messages and dmesg can overlap leading to duplicate reporting
#my $nslog_dmsgsfile = "`ls -1 /var/nslog/dmesg*`";

# Lists of log files, consider only those files which are less than a month old
my $mfiles = "find /var/log/ -maxdepth 1 -name messages\\* -mtime -31"; # Commnand to list files less that 31 days old 

# We skip checking dmesg files for HDD errors because /var/log/messages and dmesg can overlap leading to duplicate reporting
#my @files = ($mfiles, $nslog_dmsgsfile, "${TMPDIR}/dmesg-a.out");

# Total list of all files
my @files = `$mfiles`;
chomp (@files);
# Find out the hard disk partition
my $HDD_PART1 = `df -k | egrep /var | cut -f1 -d" "`;
my $HDD_PART_FULLNAME = substr($HDD_PART1, 0, 11);		# Example: /dev/ad0s1e
my $HDD_PART = substr($HDD_PART1, 5, 3);			# Example: ad0
if (length ($HDD_PART) == 0) {
	`logger -p $SYSLOG_FACILITY -i -t ns_monuploadd_err.pl "Could not get HDD partition.  $(df -k)"`;
	`rm -rf $TMPDIR`;
	exit 1;
}
# Find out the compact flash partition
my $CF_PART1 = `df -k | egrep /flash | cut -f1 -d" "`;
my $CF_PART = substr($CF_PART1, 5, 3);
my $DISC_SPACE = `df -k`;
if (length ($CF_PART) == 0) {
	`logger -p $SYSLOG_FACILITY -i -t ns_monuploadd_err.pl "Could not get CF partition.  $DISC_SPACE`;
	`rm -rf $TMPDIR`;
	exit 1;
}
#########################################################################
# Hard disk errors.
# List of errors that we use for searching in the log files. List
# Contains two categories of errors. 1) Critical errors, from index 0
# to $if_once_indx_hdd. If found in log files callhome daemon will
# immediately trigger upload. 2) Major errors, from index
# $if_once_indx_hdd+1 till end. These errors can wait till the threshold
# $max_occur of occurrences before triggering upload.
# if adding any new ones to the first category, it needs to be added
# at the beginning of the list and the variable $if_once_indx_hdd has
# to be updated. For the second category of events, new ones can be
# added at the end.
########################################################################
my @hdd_errors = (
				"${HDD_PART_FULLNAME}.*Device not configured",
				"cannot find label",
				"chmod: /var/tmp: No such file or directory",
				"${HDD_PART}.*FAILURE - device detached",            # $if_once_indx_hdd
				"Deferred Error: HARDWARE FAILURE info",
				"READ command timeout tag=0 serv=0",
				"WRITE command timeout tag=0 serv=0",
				"UDMA ICRC error writing fsbn",
				"Aborting dump due to I/O error",
				"Synchronize cache failed, status == 0xb",
				"Unrecovered read error field replaceable unit",
				"MEDIUM ERROR info",
				"${HDD_PART}:.*FAILURE - WRITE_DMA",
				"${HDD_PART}:.*FAILURE - READ_DMA",
				"swap_pager: I/O error - pagein failed",
				"swap_pager: I/O error - pageout failed",
				"${HDD_PART}.*error reading fsbn",
				"${HDD_PART}.*error writing fsbn",
				"${HDD_PART}:.*FAILURE - .* DMA transfer",
				"g_vfs_done.*${HDD_PART}.*error",
				"Write error - auto reallocation failed");
############################################################################
# CF errors
# List of errors that we use for searching in the log files. List
# Contains two categories of errors. 1) Critical errors, from index 0
# to $if_once_indx_flash. If found in log files callhome daemon will
# immediately trigger upload. 2) Major errors, from index
# $if_once_indx_flash+1 till end. These errors can wait till the threshold
# $max_occur of occurrences before triggering upload.
# if adding any new ones to the first category, it needs to be added
# at the beginning of the list and the variable $if_once_indx_flash has
# to be updated. For the second category of events, new ones can be
# added at the end.
#############################################################################
my @flash_errors = (
				"${CF_PART}.*invalid primary partition table: no magic",
				"${CF_PART}:.*FAILURE - device detached",              # $if_once_indx_flash
				"${CF_PART}.*error reading fsbn",
				"${CF_PART}.*error writing fsbn",
				"${CF_PART}:.*FAILURE - .* DMA transfer",
				"${CF_PART}:.*FAILURE - .* timed out");

# Max limit to trigger upload for some of the errors
my $max_occur = 5;

# Total errors found with the specific patterns in all the log files
my $total_errors = 0;

# @hdd_errors array index up to which trigger upload even if error happens once
my $if_once_indx_hdd = 3;

# @flash_errors array index up to which trigger upload even if error happens once
my $if_once_indx_flash = 1;
###########################################################################
# HDD processing
###########################################################################
my $HDD_errors = 0;
my $HDDline;
my $ind;
my $last_ind = $#hdd_errors;
my $HDD_critical = 0;
my $HDD_major = 0;

if ($RUN_HDD == 1) {
	`logger -p $SYSLOG_FACILITY -i -t ns_monuploadd_err.pl "Checking for HDD errors...\n"`;
	for ($ind = 0; $ind <= $last_ind; $ind++) {
		$HDDline = $hdd_errors[$ind];
		my $count = 0;
		`zegrep -i "$HDDline" @files >> $TMPDIR/grepfile`;
		$count = `egrep -c -v "shell_command=" $TMPDIR/grepfile`;
		if ($count != 0) {
			print "********************************************\n";
			print "FOUND $count HDD errors: $HDDline\n";
			print "********************************************\n";
			$HDD_errors = $HDD_errors + $count;
			`logger -p $SYSLOG_FACILITY -i -t ns_monuploadd_err.pl "FOUND $count HDD errors: $HDDline"`;
			if ($ind <= $if_once_indx_hdd) {
				$HDD_critical = $HDD_critical + $count;
			} else {
				$HDD_major = $HDD_major + $count;
			}
		}
		`rm $TMPDIR/grepfile`;
	}

	if ($HDD_errors == 0) {
		`logger -p $SYSLOG_FACILITY -i -t ns_monuploadd_err.pl "No HDD errors found"`;
	} else {
		`logger -p $SYSLOG_FACILITY -i -t ns_monuploadd_err.pl "A total of $HDD_errors HDD errors were found in log files"`;
		if ($HDD_critical) {
			`logger -p $SYSLOG_FACILITY -i -t ns_monuploadd_err.pl "Found $HDD_critical critical HDD errors, triggering upload"`;
			$total_errors++;
		} elsif ($HDD_major > $max_occur) {
			`logger -p $SYSLOG_FACILITY -i -t ns_monuploadd_err.pl "Found $HDD_major major HDD errors, exceeding the threshold of $max_occur, triggering upload"`;
			$total_errors++;
		} else {
			`logger -p $SYSLOG_FACILITY -i -t ns_monuploadd_err.pl "Found $HDD_major major HDD errors, not exceeding the threshold of $max_occur"`;
		}
	}
}
################################################################################
# CF processing
###############################################################################
my $CF_errors = 0;
$last_ind = $#flash_errors;
my $CF_critical = 0;
my $CF_major = 0;
if ($RUN_CF == 1) {
	if ($CF_PART eq $HDD_PART) {
		`logger -p $SYSLOG_FACILITY -i -t ns_monuploadd_err.pl "Flash and HDD partitions are on the same device, skipping CF check"`;
		print "*********************************************************************\n";
		print "Flash and HDD partitions are on the same device, skipping CF check\n";
		print "*********************************************************************\n";
	} else {
		`logger -p $SYSLOG_FACILITY -i -t ns_monuploadd_err.pl "Checking for flash errors..."`;
		for ($ind = 0; $ind <= $last_ind; $ind++) {
			my $flashline = $flash_errors[$ind];
			my $count = 0;
			`zegrep -i "$flashline" @files >> $TMPDIR/grepfile`;
			$count = `egrep -c -v "shell_command=" $TMPDIR/grepfile`;
			if ($count != 0) {
				print "********************************************\n";
				print "FOUND $count flash errors: $flashline\n";
				print "********************************************\n";
				$CF_errors = $CF_errors + $count;
				`logger -p $SYSLOG_FACILITY -i -t ns_monuploadd_err.pl "FOUND $count flash errors: $flashline"`;
				if ($ind <= $if_once_indx_flash) {
					$CF_critical = $CF_critical + $count;
				} else {
					$CF_major = $CF_major + $count;
				}
			}
			`rm $TMPDIR/grepfile`;
		}

		if ($CF_errors == 0) {
			`logger -p $SYSLOG_FACILITY -i -t ns_monuploadd_err.pl "No CF errors found"`;
		} else {
			`logger -p $SYSLOG_FACILITY -i -t ns_monuploadd_err.pl "FOUND A total of $CF_errors Flash errors in log files"`;
			if ($CF_critical) {
				`logger -p $SYSLOG_FACILITY -i -t ns_monuploadd_err.pl "Found $CF_critical flash errors, triggering upload"`;
				$total_errors++;
			} elsif ($CF_major > $max_occur) {
				`logger -p $SYSLOG_FACILITY -i -t ns_monuploadd_err.pl "Found $CF_major major flash errors, exceeding the threshold of $max_occur, triggering upload"`;
				$total_errors++;
			} else {
				`logger -p $SYSLOG_FACILITY -i -t ns_monuploadd_err.pl "Found $CF_major major flash errors, not exceeding the threshold of $max_occur"`;
			}
		}
	}
}
################################################################################
# WR Processing
################################################################################
if ($RUN_WR == 1) {
	`logger -p $SYSLOG_FACILITY -i -t ns_monuploadd_err.pl "Checking for nsppe core dumps..."`;
	my $OFF_DIR = "";
	my $NSPPE_BIN = "/netscaler/nsppe";
	my @WR_FILES = ("$OFF_DIR/var/log/ns.log.0", "$OFF_DIR/var/log/ns.log");
	my $CALLHOME_LOG = "$OFF_DIR/var/log/callhome.log";

	`gzip -d @WR_FILES 2>/dev/null`;

	my $WR_PPE_COREFILE_NAME = `egrep -i "pitboss.*PPE.*missed too many heartbeats|pitboss.*PPE.*unexpectedly died" @WR_FILES |\
	tail -1 | sed -e 's!.*NSPPE!NSPPE!g' -e 's!(!!g' -e 's!)!!g' | awk '{ print \$1"-"\$2 }'`;

	if (length($WR_PPE_COREFILE_NAME) == 0) {
		`egrep -i "pitboss.*PPE.*missed too many heartbeats|pitboss.*PPE.*unexpectedly died" @WR_FILES`;
		`logger -p $SYSLOG_FACILITY -i -t ns_monuploadd_err.pl "No warm restart found in @WR_FILES"`;
		`rm -rf $TMPDIR`;
		exit 100;
	}
	#find if the core file is present under /var/core The file name may be with .gz extension
	chomp($WR_PPE_COREFILE_NAME);
	my $WR_PPE_CORE = `find $OFF_DIR/var/core -name ${WR_PPE_COREFILE_NAME}* -print | tail -1`;
	if (length ($WR_PPE_CORE) == 0) {
		`logger -p $SYSLOG_FACILITY -i -t ns_monuploadd_err.pl "Error: No PPE core file for $WR_PPE_COREFILE_NAME found under $OFF_DIR/var/core"`;
		`rm -rf $TMPDIR`;
		exit 100;
	}
	# Get the proper core file name without .gz extension
	my $WR_PPE_DIR = dirname($WR_PPE_CORE);
	chdir $WR_PPE_DIR;

	`gzip -d $WR_PPE_CORE 2>/dev/null`;
	chomp($WR_PPE_CORE);
	$WR_PPE_CORE = `echo $WR_PPE_CORE | sed 's!\.gz!!g'`;

	`logger -p $SYSLOG_FACILITY -i -t ns_monuploadd_err.pl  "Generating stack trace for $WR_PPE_CORE \n"`;
	#gdb commands to be executed
	my @gdbcmds = (
		"backtrace\n",
		"quit\n",
	);
	# Create a file to copy the gdb commands
	my $gdbtmp_file = "$TMPDIR/cmds.txt";
	open(FILE,"> $gdbtmp_file") || die "Error in creating $gdbtmp_file!!\n\n";
	print FILE @gdbcmds;
	close(FILE);

	`logger -p $SYSLOG_FACILITY -i -t ns_monuploadd_err.pl "Running gdb $NSPPE_BIN $WR_PPE_CORE "` ;
	chomp($WR_PPE_CORE);
	my $gdbtmp_bt_file = "/var/tmp/monupload_bt.txt"; # file bt should go to, this file should persist across multiple execution of script.
	my @prev_bt_op = `grep "^#" $gdbtmp_bt_file | head -5`; # grep for older bt output. fetch first 5 frames. Currently we compare first 5 frame to detect dup stack trace.
	my $gdb_cmd2 = "gdb --command=$gdbtmp_file $NSPPE_BIN $WR_PPE_CORE > $gdbtmp_bt_file"; # We don't write bt output to syslog file anymore.
	my $gdb_cmd = "gdb --command=$gdbtmp_file $NSPPE_BIN $WR_PPE_CORE | logger -p $SYSLOG_FACILITY -i -t ns_monuploadd_err.pl";
	system($gdb_cmd);
	system($gdb_cmd2);
	unlink("$gdbtmp_file");

	if ( @prev_bt_op == 0) {
		`rm -rf $TMPDIR`;
		$total_errors++;
		print "No previous backtrace file.. exit 1\n";
		exit 1;  # upload.
	}
	my @cur_bt_op = `grep "^#" $gdbtmp_bt_file | head -5`;
	my $idx = 0;

	foreach my $line (@cur_bt_op) {
		$_ = $line;
		my ($prev_fno, $prev_adr, $dummy, $prev_fn) = split (' ',$prev_bt_op[$idx]);

		my ($cur_fno, $cur_adr, $dummy2, $cur_fn) = split (' ');

		print "old: $idx $prev_adr, $prev_fn\n";
		print "new: $cur_adr, $cur_fn\n";
		if ( !(($prev_adr eq $cur_adr) && ($prev_fn eq $cur_fn))) {
			print "backtraces does not match (old:$prev_adr,$prev_fn new:$cur_adr,$cur_fn)\n";
			last;
		}
		$idx++;
	}
	if ($idx == 5) {
		`rm -rf $TMPDIR`;
		print "Duplicate crash found, don't upload\n";
		`logger -p $SYSLOG_FACILITY -i -t ns_monuploadd_err.pl "Duplicate crash found, upload will be skipped"` ;
		exit 0; # Duplicate crash found, don't upload
	}
	$total_errors++;
}

# Clean up any temporary stuff
`rm -rf $TMPDIR`;

# Exit with proper status based on errors found
if ($total_errors == 0) {
	print "exit with 0\n";
	exit 0;
}
else {
	print "exit with 1\n";
	exit 1;
}
