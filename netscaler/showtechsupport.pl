#!/usr/bin/perl
#
# File: $Id: //depot/main/rs_111/usr.src/netscaler/scripts/showtechsupport.pl#1 $
# Last checkin:   $Author: build $
# Date of last submission: $Date: 2016/06/13 $
# Revision number: $Revision: #1 $
# Changelist number: $Change: 520913 $

use strict;
use Switch;
use File::Path;
use File::Copy;
use Sys::Syslog;
use File::Basename;
use File::stat;
use Class::Struct;          # load struct-building module
use Getopt::Long;
use Fcntl qw/ :flock /;

#########################################################################
# Some global configurable variables. For now, they are set here, but in
# the future, we can make these as arguments passed as command line options
#########################################################################

my $NUM_FILES_TO_COLLECT = 5; #Number of files to be collected from a dir
my $NUM_PREV_DAYS_FILES = 7; #Number of days from which to look for files
my $NS_SHOW_IP_CNT = 20; #Max IP addresses information to display

######################################################################
# Other global variables (internally used - NOT TO BE MODIFIED!
######################################################################

my $configdb_loc = "/configdb/";
my $configdb = "/configdb/nscfg.db";
my $support_dir = "/var/tmp/support"; #This may be changed later
my $support_abbr_dir = "/var/tmp/support/abbr"; #This may be changed later
my $support_callhome_dir = "/var/tmp/support/callhome"; #This may be changed later
my $support_cluster_dir = "/var/tmp/support/cluster";
my $hd_support_dir = "/var/tmp/support"; #This is the one created on hard disk
my $collector_base_dir = "";	#This will be set later
my $collector_abs_path = "";	#This will be set later
my $ns_version = ""; #This will be set later
my $hd_device = ""; #This will be set later
my $flash_device = ""; #This will be set later
my $ncore; # are we on nCore? Beware: not initialized in all code paths
my $is_abbr_version = 0; #Run smaller version of showtechsupport. This is useful in cluster.
my $is_callhome_version = 0; #Run callhome version of showtechsupport.
my $is_cluster_version = 0; #Run showtechsupport on all cluster nodes and collect output.
my $cluster_instance = 0; #indicates that this node is part of a cluster instance
my $cluster_cco = 0; #indicates that this node is the configuration coordinator
my @show_cluster_node_output = ""; #Output of "show cluster node"
my $this_node_id = 64; #Node id of the local node; Initialize to a non-existent value
my $admin_partition = 0; # set to 1 if partitions are added
my $decapolis_22001x = 0; #Decapolis platform (sysid / 100 == 22001) 
my $partition_dir = ("var/partitions");
my $shell_partition_dir = ("shell/partitions");
my $nsconfig_partition_dir = ("nsconfig/partitions");
my $num_nodes = 0; #Total number of nodes in the cluster
my $is_gslb_node = 0;  #indicates that the node is part of GSLB deployment.

struct admin_partition_details => {		# create a definition for  admin_partition_details 
	ID		=> '$',						# ID field is partition id
	Name	=> '$',						# Name field is partition name
	Flag	=> '$',						# Flag field is set for collecting details if set to 1
};

my @admin_partition_arr; # Admin partition details are stored
my $partitionName 	= 0;

# Syslog related globals for syslog use in log_debug_message()
my $syslog_ident    = "showtechsupport.pl: ";
my $syslog_opt      = "ndelay,pid,nofatal";
my $syslog_facility = "LOG_USER";

# Constants for log_debug_message()
use constant {
	SYSLOG => 0, # Log target
	CONSOLE => 1, # Log target
	INFO => "LOG_INFO",
	NOTICE => "LOG_NOTICE",
	ERROR => "LOG_ERROR",
	DEBUG => "LOG_DEBUG",
};

# VPX on AWS
my $aws_url = "http://169.254.169.254/latest/meta-data";

# For uploading archive.
my $ifupload    = 0;
my $sr          = "";
my $description = "";
my $proxy       = "";
my $upload_file = "";

my @scope       = ();

# Globals for newnslog collection
my $timestamps_sane = 1;
my $compressed_files_size = 0;
my $compressed_files_sampled = 0;

my $ha_cur_node = "";

sub signal_handler {
	# cleanup 
	if (-d $collector_abs_path) {
		rmtree("$collector_abs_path", 0, 1) || &log_debug_message("\nUnable to remove $collector_abs_path\n");
	}

	# Close syslog facility
	closelog;

	die "\n Caught a signal $!. Exiting... \n\n";
}

$SIG{INT}  = \&signal_handler;


my %files_to_be_copied = (
);

##################################################################
# MAIN ENTRY POINT. Call main() that does the whole thing.
##################################################################

&main();

############
# usage
############

sub usage() {
	my $prog = basename $0;

	&log_debug_message("\nUsage: \n");
	&log_debug_message("\tshowtechsupport - Utility to generate a tar archive of system configuration data and statistics for submission to Citrix technical support.\n\n");
	&log_debug_message("$prog [-scope cluster|partition <partition name1 name2 ... nameN>] [-upload [-proxy <proxy_user>:<proxy_password>@]<proxy_host>:<proxy_port>] [-casenumber <ServiceRequest case number>] [-description <description text>] [-file <file>]] [-h|-help|-?]\n\n");
	&log_debug_message("  scope:\n");
	&log_debug_message("\t showtechsupport can be run with following scopes\n\n");
	&log_debug_message("\t cluster - Run showtechsupport on a cluster configuration. Collects the data from all the nodes in a cluster and generates a super tar archive. Should be run only on Configuration Coordinator.\n");
	&log_debug_message("\t partition -  Run showtechsupport to collect data from specific admin partition(s), along with default admin partition. Requires partition name(s) as values.\n\n");
	&log_debug_message ("\t Note: By default, showtechsupport collects data from all admin partitions if run without any scope option. For cluster scope, showtechsupport doesnot collect data from admin partitions.\n");
	&log_debug_message("\n  upload:\n");
	&log_debug_message("\t My Citrix credentials are mandatory for uploading archive to Citrix upload server. User will be prompted for credentials.\n");
    &log_debug_message("\t Following are only supported with upload option.\n\n");
	&log_debug_message("\t casenumber - Service request case number.\n");
	&log_debug_message("\t description - Text to describe the archive that is being uploaded.\n");
	&log_debug_message("\t proxy - Proxy server details like user Id, password if required and proxy ip and port number.\n");
	&log_debug_message("\t file - File name to upload.\n");
	&log_debug_message("\nExamples:\n\n");
	&log_debug_message("To generate archive for a single node:\n");
	&log_debug_message("\t$prog\n\n");
	&log_debug_message("To upload already generated archive:\n");
	&log_debug_message("\t$prog -upload -casenumber \"Service request case number\" -description \"Description text\" -file \"File name\"\n\n");
	&log_debug_message("To generate archive for a cluster:\n");
	&log_debug_message("\t$prog -scope cluster\n\n");
	&log_debug_message("To generate and upload archive using proxy:\n");
	&log_debug_message("\t$prog -upload -proxy [<proxy_user>:<proxy_password>@]<proxy_host>:<proxy_port> -casenumber \"Service request case number\" -description \"Description text\"\n\n");
	&log_debug_message("To generate and upload archive for a cluster:\n");
	&log_debug_message("\t$prog -scope cluster -upload -casenumber \"Service request case number\" -description \"Description text\"\n\n");
	&log_debug_message("To generate archive for partitions part1 and part2:\n");
	&log_debug_message("\t$prog -scope partition part1 part2\n\n");

	closelog;
	exit 0;
}

#####################################################################
# Main function that collects data step by step through separate functions
######################################################################

sub main()
{
	# Open syslog facility for logging anything.
	openlog $syslog_ident, $syslog_opt, $syslog_facility;

	my $p4_rev_string = '$Revision: #1 $';
	&log_debug_message("\nshowtechsupport data collector tool - $p4_rev_string!\n");

	# Get the arguments to the process
	&ns_getargs();

	# Prepare directory structure for the tool to collect data
	&prepare_support_dir();

	# Create MegaCli log files 
	&megacli_log();

	# copy a modified config db not only on the configuration co-ordinator 
	# but also the rest of the cluster nodes 
	if($cluster_instance && !$is_callhome_version) {
		&copy_config_db();
	}

	# Copy all config files
	&copy_nsconfig_dir();

	# Copy all WebInterface configuration files (only if WI is installed)
	&copy_wi_files(); 

	# Copy all admin partition files
	if($admin_partition) {
		&copy_admin_partition_files(); 
	}

	# Run BSD shell commands
	&exec_shell_cmds();

	# Run NetScaler CLI show commands
	&exec_show_cmds();

	# Run NetScaler CLI show commands for admin partition
	if($admin_partition) {
		&exec_show_cmds_partition();
	}

	# Run NetScaler CLI stat commands
	&exec_stat_cmds();

	# Run NetScaler CLI stat commands for admin partition
	if($admin_partition) {
		&exec_stat_cmds_partition();
	}

	# Run NetScaler vtysh commands
	&exec_vtysh_cmds();

	# Run NetScaler vtysh commands for admin partition
	if($admin_partition) {
		&exec_vtysh_cmds_partition();
	}

	#copy ZebOS cluster cfg files
	&copy_zebos_cfg_files();

	# Copy newnslog files
	&copy_newnslog_files();

	# Copy application core files
	&copy_core_files("/var/core","$collector_abs_path/var/core","*");

	# Copy kernel mini core files
	&copy_core_files("/var/crash","$collector_abs_path/var/crash","nsminicore.*");

	# Copy imported object files and mapping files from download dir
	&copy_download_dir_files();

	# Copy all GSLB location db files
	&copy_gslb_loc_db_files();

	# Copy all GSLB Auto Sync Log Files
	&copy_gslb_auto_sync_log_files();

	# Copy all Thales HSM related files
	&copy_thales_hsm_files();

	# Copy /var/waagent and /var/log/waagent dirs.
	&copy_waagent_dirs();

	# Copy /var/nsinstall/installns_state file
	&copy_nsinstall_files();

	# Copy all Safenet Gateway Log files
	&copy_safenet_gw_log_files();

	# Collect metadata server info and saves it in <collectordir>/var/VPX_AWS dir.
	# Also purges access keys and secret keys.
	&support_vpxonaws();

	# Collect the xenconsole data from the svm and vpx of SDX 
	&xenstore_info();

	# Collect files for nscollect process
	&copy_nscollect_files();

	# Copy all log files (ns.log*, messages*, dmesg.*, etc.)
	# XXX : Copying of the log files should be done as the last
	# step. PlS DO NOT ADD MORE CALLS BELOW THIS
	&copy_log_files();

	# Filter sensitive information from all files under collector archive.
	# XXX: This function is purposely called before prepare_tar_archive, Do not
	# change the location of this call.
	&fltr_all_sensitive_info();

	# Finally, create a tar archive of the collector directory that we populated.
	&prepare_tar_archive();

	if ($cluster_instance == 0 && $ha_cur_node !~ "Standalone") {
		&log_debug_message("========================== IMPORTANT ==========================\n");
		if ($ha_cur_node =~ "Primary") {
			&log_debug_message("This node is part of HA pair, please run it on the Secondary node also.\n");
		} else {
			&log_debug_message("This node is part of HA pair, please run it on the Primary node also.\n");
		}
		&log_debug_message("================================================================\n");
	}
	if ($cluster_instance == 1 && $is_cluster_version == 0) {
		&log_debug_message("========================== IMPORTANT ==========================\n");
		&log_debug_message("Please use '-scope cluster' on cluster IP to create single archive from all the cluster nodes. \nThis will help in troubleshooting cluster level issues.\n");
		&log_debug_message("================================================================\n");
	}
	if ($is_gslb_node) {
		&log_debug_message("========================== IMPORTANT ==========================\n");
		&log_debug_message("This NetScaler is part of GSLB. Please run this command on other site nodes as well.\nThis will help in troubleshooting any GSLB related issues.\n");
		&log_debug_message("================================================================\n");
	}

	if ($ifupload) {
		&upload_archive();
	}

	# Close syslog facility
	closelog;
}

#####################################################
# Upload the collected/given archive to upload server
# ###################################################

sub upload_archive()
{
	my $upload_args = "";

	# Form string of argumets to be passed to collector_upload
	$upload_args = $upload_args . " -sr $sr" if ($sr);
	$upload_args = $upload_args . " -proxy $proxy" if ($proxy);
	$upload_args = $upload_args . " -description \"$description\"" if ($description);

	# system() returns actual process exit code as multiple of 256.
	# So to get the actual exit status need to devide by 256 ( >> 8).
	my $ec = system("/netscaler/collector_upload.pl $upload_args $upload_file") >> 8;
	closelog;
	if ($ec != 1)
	{
		exit (-2);
	} else
	{
		exit (0);
	}
}

#####################################################################
# The function capture the xenconsole data from the svm and vpx of SDX
######################################################################

sub xenstore_info()
{ 
        
 	my $sysid = `sysctl -n netscaler.hypervised_ns`;
        chomp($sysid);
        if($sysid==1)
        {
             &log_debug_message("Running xenstore commands ....\n", SYSLOG, NOTICE);
             my $cmd1='xenstore-ls vm-data  2>&1 | egrep -v "mac_interface_list|priv2"';
             my $cmd2=`xenstore-read vm-data/mac_interface_list 2>&1 `;
             my $cmd3=`xenstore-read vm-data/priv2  2>&1 `;
             my $file= 'xenstore_info.out';
            `$cmd1 > $collector_abs_path/shell/$file `;
            `echo "Mac_interface_list = $cmd2 " >> $collector_abs_path/shell/$file `;
             `echo "Priv2 = $cmd3 " >> $collector_abs_path/shell/$file`;
      }
        
        return 0;
}

############################################################
# Function to collect files for debugging nscollect process
############################################################

sub copy_nscollect_files()
{
	# set datapath to default path
	my $datapath = "/var/log/db";
	my @show_system_collectionparam_out = `nscli -U %%:nsroot:. show system collectionparam 2>&1`;

	foreach my $line (@show_system_collectionparam_out)
	{
		chomp($line);
		if ($line =~ /DataPath:/)
		{
			my @tmp_ar = split ' ', $line;
			$datapath = $tmp_ar[$#tmp_ar]; # Set datapath to configured path.
			$datapath =~ s/"//g;
			last;
		}
	}
	&log_debug_message("Copying files from $datapath ....\n", SYSLOG, NOTICE);

	my $d_dir = "$datapath/default";
	my $dc_dir = "$datapath/default_cluster";
	`mkdir -p "$collector_abs_path/$d_dir"`;
	`mkdir -p "$collector_abs_path/$dc_dir"`;

	`cp -p $d_dir/nsdevmap.txt $d_dir/nscollectmap.xml $d_dir/nscollectmap.0.xml $collector_abs_path/$d_dir > /dev/null 2>&1`;
	`cp -p $dc_dir/nscollectmap.xml $dc_dir/nscollectmap.0.xml $collector_abs_path/$dc_dir > /dev/null 2>&1`;

	return 0;
}

#####################################################################
# The function checks for Decapolis platform (sysid / 100 == 22001)
# and create LSI raid event log  
######################################################################

sub megacli_log()
{
 	my $sysid = `sysctl -n netscaler.sysid`;
        chomp($sysid);
	if (int($sysid/100) == 22001) {
		`/usr/local/bin/MegaCli64 -AdpEventLog -GetEvents -f /var/log/LSI-Raid-Controller.logs -aALL > /dev/null 2>&1`;
		$decapolis_22001x = 1;
		return 1;
	}
	return 0;
}

#####################################################################
# The function checks whether this device is Configuration Coordinator or not
######################################################################

sub is_device_cco()
{
	my @show_cluster_instace_output=`nscli -U %%:nsroot:. show cluster instance 2>&1`;
	foreach my $line (@show_cluster_instace_output) {
		$_ = $line;
		if (/Configuration Coordinator/ && /\*/) {
			$cluster_cco = 1;
			return 1;
		}
	}
	return 0;
}

####################################################################################
# The function checks whether if this node is part of a cluster instance.
# A node is **not** part of a cluster till the "add cluster instance" command is issued
#####################################################################################

sub is_cluster_instance()
{
	my @show_cluster_instace_output=`nscli -U %%:nsroot:. show cluster instance 2>&1`;
	foreach my $line (@show_cluster_instace_output) {
		$_ = $line;
		if (/Cluster ID:/) {
			$cluster_instance = 1;	#Set the global variable
			#This node is part of a cluster.. get its node id.
			get_cluster_node_id();
			return 1;
		}
	}

	return 0;
}

####################################################################################
# The function gets the node id of this node. If this node is not part of a 
# cluster instance, then there is nothing to check
#####################################################################################

sub get_cluster_node_id()
{
	my $line_no;
	my $node_id;

	if ($cluster_instance == 0) {
		return -1;
	}

	# Store this in a global variable, so that this can be used later
	@show_cluster_node_output = `nscli -U %%:nsroot:. show cluster node 2>&1`;

	# Parse the "show cluster node" output
	for ($line_no = 0; $line_no < scalar(@show_cluster_node_output); $line_no++) {
		$_ = $show_cluster_node_output[$line_no];
		chomp($_);

		# The "Node Id:" line should come first in the output. Remember the node id
		if (/Node ID:/) {
			($node_id) = (split /Node ID:/)[1];	#Use : as the delimiter
			($node_id) =~ s/\(\w+\)//;      #Remove paren, any alpha numeric char in between the paren
			($node_id) =~ s/^\s+//;		#Remove trailing whitespaces
			($node_id) =~ s/\s+$//;		#Remove ending whitespaces

		}

		# After the string with "Node Id:", we will have the IP address string. If a '*'
		# follows the IP address, then we are on that node. Use the remembered node id
		# and return back

		if (/IP:/ && /\*/) {
			$this_node_id = $node_id;
			return;
		}
	}
	# It shouldn't come here, but log just in case it comes here
	&log_debug_message("Warning! show cluster node output didn't show any local node\n");
	return 0;
}

####################################################################################
# The function gets admin partition details and populates it.
#####################################################################################

sub get_partition_details()
{
	my $line_no;
	my $partition_id;
	my $partition_name;

	# Store this in a global variable, so that this can be used later
	my @show_admin_partition_output = `nscli -U %%:nsroot:. show partition 2>&1`;

	# Parse the "show partition" output
	for ($line_no = 0; $line_no < scalar(@show_admin_partition_output); $line_no++) {
		$_ = $show_admin_partition_output[$line_no];
		chomp($_);

		# Parse the output and get partition id and partition name
		if (/Name:/) {
			($partition_name) = (split /Name:/)[1];	#Use : as the delimiter
			($partition_name) =~ s/\(\w+\)//;      #Remove paren, any alpha numeric char in between the paren
			($partition_name) =~ s/^\s+//;		#Remove trailing whitespaces
			($partition_name) =~ s/\s+$//;		#Remove ending whitespaces
		}

		if (/Partition ID:/) {
			($partition_id) = (split /Partition ID:/)[1];	#Use : as the delimiter
			($partition_id) =~ s/\(\w+\)//;      #Remove paren, any alpha numeric char in between the paren
			($partition_id) =~ s/^\s+//;		#Remove trailing whitespaces
			($partition_id) =~ s/\s+$//;		#Remove ending whitespaces
			my $admin_new = admin_partition_details->new();
			$admin_new->ID($partition_id);
			$admin_new->Name("$partition_name");
			$admin_new->Flag(0);
			push @admin_partition_arr, $admin_new;
			$admin_partition = 1;
		}
	}
}

#####################################################################
# The function gets the arguments to the process
######################################################################

sub ns_getargs()
{
	my $invalid_partition_name = 0;

	# Parse command line arguments
	GetOptions ("scope=s{1,}" => \@scope,      # -scope takes one or more strings as value.
		"upload"        => \$ifupload,         # -upload is a flag, so no values
		"casenumber=s"  => \$sr,
		"proxy=s"       => \$proxy,
		"file=s"        => \$upload_file,
		"description=s" => \$description,
		"help|h|?"      => \&usage)            # -help or -h or -? all prints usage.
	|| usage();

	if (!$ifupload && ($sr || $proxy || $description || $upload_file)) {
		print "-proxy,-description and -file works only wih -upload option.\n\n";
		usage();
	}
	if ($proxy ne "") {
		if ($proxy !~ /^(([^:@\/\s]+):([^:@\/\s]+)@)?([^:@\/\s]+:\d+)\/?$/) {
			print "!! Invalid Proxy Setting !!\n\n";
			usage();
		}
	}

	# If -file, upload given file and exit
	if ($upload_file) {
		&upload_archive();
	}

	# Get the NetScaler version and hard disk
	&get_ns_version();
	&get_hard_disk_device();

	# Determine if this node is part of a cluster and if it is, get the node id
	if(is_cluster_instance()) {
		# test and set CCO, if so
		&is_device_cco();
	}

	check_gslb_node();
	# Check if admin partition is configured.
	&get_partition_details();

	if ($#scope ge 0) {
		my $sc = shift @scope;
		switch (lc($sc)) {
			case "node" { return; } #Run techsupport on local node
			case "abbr" {
				&log_debug_message("Running abbreviated version of showtechsupport.\n");
				$is_abbr_version = 1;
				$NUM_FILES_TO_COLLECT = 1; #Number of newnslogs to be collected
			}
			case "callhome" {
				&log_debug_message("Running callhome version of showtechsupport.\n");
				$is_callhome_version = 1;
				$NUM_FILES_TO_COLLECT = 1; #Number of newnslogs to be collected
			}
			case "cluster" {
				$is_cluster_version=1;

				#Check whether this is Configuration Coordinator or not.
				if (!$cluster_cco) {
					closelog;
					die "Please connect to cluster IP and run showtechsupport on that node. Exiting ....\n\n";
				}
			}
			case "partition" {
				if(!$admin_partition) {
					closelog;
					die "Partitions are not configured\n";
				}
				$partitionName = 1;
				if ($#scope == -1) {
					closelog
					die "-scope partition - needs at least one partition name. Exiting ...\n\n";
				}
				my $argnum;
				&log_debug_message("Running Admin Partition version of showtechsupport.\n");
				foreach $argnum (0 .. $#scope) {
					my $partition_input_chk = 0;
					if($scope[$argnum] eq 0) {
						print "Invalid input $scope[$argnum]\n";
					} else {
						foreach my $num_id (0..$#admin_partition_arr) {
							my $name = scalar ($admin_partition_arr[$num_id]->Name);
							if($scope[$argnum] eq $name ) {
								$admin_partition_arr[$num_id]->Flag(1);
								 $partition_input_chk = 1;
							}
						}
						if(!$partition_input_chk) {
							print "Invalid partition name $scope[$argnum]\n";
							$invalid_partition_name = 1;
						}
					}
				}
				if($invalid_partition_name) {
					closelog
					exit (-1);
				}
				return;
			} else {
				closelog
				die "Invalid second argument $sc for -scope passed. Exiting..\n";
			}
		}
	}

	# if scope = cluster.
	if ($is_cluster_version) {
 		my $vpx = `sysctl -n netscaler.hypervised_ns`;
        	chomp($vpx);
		if ( $vpx > 0 ) {
 			my $model = `sysctl -n netscaler.model`;
        		chomp($model);
			# In VPX, model number also represent its bandwidth. 
			# Any VPX model <= 10Mbps is not allowed to run this script 
			# in cluster scope because pulling archives from different 
			# node can utilize full bandwidth for low bandwidth instance.  
			if ( $model <= 10 ) {
				closelog;
				die "This is a low bandwidth instance. Showtechsupport cannot be run with scope cluster. Please execute showtechsupport on each node.\n";
			}
		}

		&prepare_support_dir();
		&run_across_cluster_nodes();
		&log_debug_message("Generating a super tar archive ....\n");
		&prepare_tar_archive();
		if ($ifupload) {
			&upload_archive();
		}
		# Close syslog facility
		closelog;
		exit 0;
	}

	# Do not collect data for admin partitions if scope = cluster or callhome or abbr.
	# If scope = partition only collect for those partition not for all partitions.
	if($admin_partition && !($partitionName || $is_abbr_version || $is_callhome_version || $is_cluster_version)) {
		foreach my $num_id (0..$#admin_partition_arr) {
			$admin_partition_arr[$num_id]->Flag(1);
		}
	}
}

#########################################################################
# This routine creates the directory structure necessary to collect the files.
# /var/tmp/support is the base directory and beneath it is where all files will be
# collected.
#################################################################################

sub prepare_support_dir()
{
	# Because of the 'use strict pragma' we need to define the variables here
	# Variables declared with "my" are accessed faster than ordinary variables.
	my @months;
	my $dummy;
	my $min;
	my $hour;
	my $mon;
	my $year;
	my $mday;
	my $abbr_str = $is_abbr_version?"/abbr":"";
	my $callhome_str = $is_callhome_version?"/callhome":"";
	my $cluster_str = $is_cluster_version?"/cluster":"";

	if ($is_abbr_version) {
		$support_dir = $support_abbr_dir;
	} elsif ($is_cluster_version) {
		$support_dir = $support_cluster_dir;
	} elsif ($is_callhome_version) {
		$support_dir = $support_callhome_dir;
	}

	# The directory that we eventually create is of the form "collector_<IP>_<Date>_<Time>"
	# All the directories of this type will be under $support_dir

	# Check if the support dir exists and create if it does not. This is true for both if hard drive is present
	# and the case of hard drive not mounted
	if (!-d $support_dir) {
		&log_debug_message("Creating $support_dir ....\n");
		mkpath("$support_dir") || die "Unable to create $support_dir!\n\n";
	}

	# If hard disk is not mounted, the support directory will be in /flash/support, instead of
	# /var/tmp/support
	if ($hd_device eq "") {
		$support_dir = "/flash/support".$abbr_str.$cluster_str.$callhome_str;
		if (!-d $support_dir) {
			&log_debug_message("Creating $support_dir ....\n");
			mkpath("$support_dir") || die "Unable to create $support_dir!\n\n";
		}
	}

	chdir $support_dir;

	# Create a base directory of the form "collector_<IP>_<Date>_<Time>"
	my $nsip;
	if (($nsip = get_nsip_address()) eq "") {
		die "Could not get the NS IP address or HA state of this box. Exiting!!\n\n";
	}
	$collector_base_dir = "collector_" . $nsip;

	chomp($collector_base_dir);

	my @months = qw(Jan Feb Mar Apr May Jun Jul Aug Sep Oct Nov Dec);

	# The fields that we get using localtime are the following:
	#     ($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst)

	($dummy,$min,$hour,$mday,$mon,$year,$dummy,$dummy,$dummy) = localtime(time);
	$year += 1900;
	# If the hour and minutes are in single digits, prefix with a "0"
	if ($hour < 10) {
		$hour = "0". $hour;
	}
	if ($min < 10) {
		$min = "0". $min;
	}
	$collector_base_dir = 
		$collector_base_dir . $mday . $months[$mon] . $year . "_" . $hour . "_" . $min;

	if (-d $collector_base_dir) {
		# If the directory exists, this tool was run in the last minute.
		# Remove the existing directory and create again.
		&log_debug_message("This tool was just run in the last one minute!\n");
		&log_debug_message("The data in this directory will be overwritten!\n");
		rmtree("$collector_base_dir",0,1) || &log_debug_message("Unable to remove $collector_base_dir\n");
	}

	&log_debug_message("All the data will be collected under \n\t$support_dir/$collector_base_dir\n", SYSLOG, NOTICE);
	mkdir("$collector_base_dir") || die "Unable to create $collector_base_dir!!\n\n";
	$collector_abs_path = $support_dir . "/" . $collector_base_dir;

	$upload_file = "$collector_abs_path.tar.gz";

	#For clustering option, top level directory is enough
	if ($is_cluster_version) {
		return;
	}

	# Create additional directories below the top level
	my @sub_dirs = ("var", "shell", "nsconfig", "var/core", "var/log", "var/nslog",
			"var/crash", "etc", "var/nssynclog", "var/nsproflog", "var/download",
			"flash", "flash/.recovery", "/var/clusterd", "/var/cfsynclog", "var/nslog/nsumond",
			"var/nsclfsync", "var/opt", "var/opt/nfast", "var/nsinstall", "var/netscaler/",
			"var/nslog/iprep");
	if($admin_partition) {
		push @sub_dirs, $partition_dir;
		push @sub_dirs, "$shell_partition_dir";
		push @sub_dirs, "$nsconfig_partition_dir";
	}
	foreach my $dir (@sub_dirs) {
		mkdir("$collector_base_dir/$dir") ||
			die "Unable to create $collector_base_dir/$dir!!\n\n";
	}
	#copying configdb into each cluster node's techsupport bundle 
	if($cluster_instance && !$is_callhome_version) {	
		mkdir("$collector_base_dir/$configdb_loc") ||
		&log_debug_message("Unable to create $collector_base_dir/$configdb_loc!!\n\n");
	}
}

#########################################################################
# This routine gets the NS IP address of the box.
#################################################################################

sub get_nsip_address()
{
	my $line;
	my $ip = "";
	my $state;
	my $dummy;
	my $ha_state = "";
	my $abbr_str = $is_abbr_version ? "abbr_" : "";
	my $callhome_str = $is_callhome_version ? "callhome_" : "";
	my $cluster_str = $is_cluster_version?"cluster_":"";

	my @show_node_output = `nscli -U %%:nsroot:. show node 2>&1`;
	foreach $line (@show_node_output) {
		$_ = $line;
		if (/IP:/) {
			chomp($_);
			($ip) = (split /IP:/)[1];	#Use : as the delimiter
			$ip =~ s/^\s+//;		#Remove trailing whitespaces
			$ip =~ s/\s+\(.+\)//;	#Remove in between whitespaces and the (<hostname>) string
			$ip =~ s/\s+$//;		#Remove ending whitespaces
			&log_debug_message("The NS IP of this box is $ip\n");
		}
		if (/Master State:/) {
			# Output here is of the form   "Master State: Primary"
			# Print HA state only if the node is not part of the cluster
			if ($cluster_instance == 0) {
				($dummy,$state) = split(/:/);   #Use : as the delimiter
				if ($state =~ "Primary") {
					$ha_state = "P";
					my $node_count=0;
					foreach my $show_node_line (@show_node_output) {
						if ($show_node_line =~ "Master State:") {	#count no of times Master State occurs in show node;
							$node_count = $node_count + 1;			#if count==2 then HA config, else Standalone node.
						}
					}
                    if ($node_count == 2) {
                        &log_debug_message("Current HA state: Primary \n");
                        $ha_cur_node = "Primary";
                    } else {
                            &log_debug_message("This is not HA configuration\n");
                            $ha_cur_node = "Standalone";
                    }
				}
				else {
					if ($state =~ "Secondary") {
						$ha_state = "S";
						&log_debug_message("Current HA state: Secondary\n");
						$ha_cur_node = "Secondary";
					}
				}
			}
			else { #cluster_instance = 1
				&log_debug_message("This node is part of a cluster instance. \n");
			}

			# NOTE: We are returning here with the assumption that this line
			# in show node output appears after the IP address line
			# In this case, out of $abbr_str, $cluster_str and $callhome_str,
			# only one string will be set. Say if $abbr_str is set, the other
			# two would be NULL strings.


			if ($cluster_instance == 1) {
				my $node_str = "n".$this_node_id if ($this_node_id != -1);
				return ($abbr_str.$cluster_str.$callhome_str.$node_str."_".$ip."_");
			}
			else {
				return ($abbr_str.$cluster_str.$callhome_str.$ha_state."_".$ip."_");
			}
		}
	}
	# It shouldn't come here, as the "Master State:" line should be found above
	return ($abbr_str.$cluster_str.$callhome_str.$ip."_".$ha_state);
}

#########################################################################
# fltr_all_sensitive_info
# 	Filter sensitive information from collector bundle. The function
# 	scans through below files for sensitive information like
# 	passwords and keys and replaces them by string "XXXX".
#
# 		$collector_abs_path/nsconfig
# 			-ns.conf*
# 		$collector_abs_path/var/nssynclog
# 			-ns_com_cfg.conf*
# 			-sync_batch_status.log*
# 			-ns_nonclus_cfg.conf
# 			-ns_clus_cfg.conf
# 			-ns_loclclus_cfg.conf
# 			-clus_sync_batch_status.log
# 		$collector_abs_path/var/cfsynclog
# 			-ns_com_cfg.conf*
# 			-sync_batch_status.log*
# 			-ns_nonclus_cfg.conf
# 			-ns_clus_cfg.conf
# 			-ns_loclclus_cfg.conf
# 			-clus_sync_batch_status.log
# 		$collector_abs_path/shell
# 			-showcmds.txt
#######################################################################

sub  fltr_all_sensitive_info()
{
	my $replStrng = "XXXX";
	my @filelist  = ();
	my @dirlist   = qw(/nsconfig /var/nssynclog /var/cfsynclog /shell);
	my $dir;
	foreach my $dir (@dirlist) {
		if (-e "$collector_abs_path/$dir") {
			chdir "$collector_abs_path/$dir";
		} else {
			next;
		}

		if ($dir =~ /nsconfig/) {
			@filelist = glob("ns.conf*");
		} elsif ($dir =~ /nssynclog/) {
			@filelist = glob("ns_com_cfg.conf* sync_batch_status.log* ns_nonclus_cfg.conf* ns_clus_cfg.conf* ns_loclclus_cfg.conf* clus_sync_batch_status.log*");
		} elsif ($dir =~ /cfsynclog/) {
			@filelist = glob("ns_com_cfg.conf* sync_batch_status.log* ns_nonclus_cfg.conf* ns_clus_cfg.conf* ns_loclclus_cfg.conf* clus_sync_batch_status.log*");
		} elsif ($dir =~ /shell/) {
			push @filelist, "showcmds.txt";
		}

		foreach my $file (@filelist)
		{
			my $tmp_file = "$support_dir/$file.$$";
			open my $in_file, '<', $file;
			open my $out_file, '>', $tmp_file;
			while( <$in_file> )
			{
				if (/-encrypted/)
				{
					s/(\w+\s+)(?=-encrypted)/$replStrng /g;
					print $out_file $_;
					next;
				}
				if (/-passcrypt/)
				{
					s/(?<=-passcrypt).*?(?=(-|$))/ $replStrng /;
					print $out_file $_;
					next;
				}
				print $out_file $_;
			}
			close $in_file;
			close $out_file;
			`mv $tmp_file $file`;
		}
	}
}

#########################################################################
# Copy the nsconfig directory that has all the configuration files
#########################################################################

sub copy_nsconfig_dir()
{
	# Copy only selected files from /nsconfig directory
	&log_debug_message("Copying selected configuration files ....\n");

	# Copy all files first, but do clean up of passwords next
	chdir "/flash/nsconfig";
	`cp -p * $collector_abs_path/nsconfig 2>/dev/null`;
	`cp -R -p monitors $collector_abs_path/nsconfig 2>/dev/null`;
	`cp -p /flash/boot/loader.conf* $collector_abs_path/nsconfig 2>/dev/null`;

	# Clean up by removing passwords from ns.conf* files
        # Remove F*.key fragments used for KEK generation. F1.key & F2.key, 
	# files are generated when the command 'create system kek' is executed.
        `rm $collector_abs_path/nsconfig/F*.key 2>/dev/null`;
	# Copy nsconfig dir for admin partitions
	if($admin_partition == 1) {
		foreach my $num_id (0..$#admin_partition_arr) {
			if($admin_partition_arr[$num_id]->Flag) {
				my $Name = scalar ($admin_partition_arr[$num_id]->Name);
				`cp -p -R partitions/$Name $collector_abs_path/$nsconfig_partition_dir/ 2>/dev/null`;
			}
		}
	}
}

#########################################################################
# Copy a modified copy of the cluster config database 
# (1) copy the config database to a temporary location, strip this database
# off of any sensitive info (2) copy this modified version to the 
# collector archive
#########################################################################
sub copy_config_db()
{
	my $command = "";
	my $ret;
	my $configdb_copy_loc = "$collector_abs_path/$configdb_loc";
	my $configdb_copy = "$collector_abs_path/$configdb";

	&log_debug_message("Purging sensitive data from cluster configuration database ....\n");
	$command = `cp -f -p $configdb $configdb_copy_loc`;
	my @command_output = `$command 2>&1`;

	if($?) {
		&log_debug_message(join("\n",@command_output));
		&log_debug_message("Copying config db failed\n");
		return;
	}

	# invoke the C binary to modify the config database
	$command = "/netscaler/cfgdb_modify -f $configdb_copy";

	@command_output = `$command 2>&1`;
	&log_debug_message(join("\n",@command_output), SYSLOG, NOTICE);

	# print cfgdb_modify failure messages, if any..
	if ($?) {
		&log_debug_message("Purging sensitive data from cluster configuration database failed\n");
		return;
	}
}
##############################################################
#Check for huge log files of size greater than 100MB
#Copying all the files will increase the size of tar archive
#This function optimize and copy less log files
##############################################################
	
sub check_huge_log_files()
{
	my $pattern=$_[0];
	my $hugenslog_cnt=0;
	my $bignslog_cnt=0;
	$hugenslog_cnt = `find /var/log/ -name $pattern\* -type f -size +100M | wc -l`;
	if ($hugenslog_cnt != 0)
	{
		# collect only 2 files as file size is greater than 100MB
		$files_to_be_copied{"/var/log/$pattern"} = "var/log";
		$files_to_be_copied{"/var/log/$pattern.0"} = "var/log";
		$files_to_be_copied{"/var/log/$pattern.0.gz"} = "var/log";
		print("Copying only 2 $pattern files as some of them exceed the size limit\n");
	} else 
	{
		$bignslog_cnt = `find /var/log/ -name $pattern\* -type f -size +15M | wc -l`;
		if ($bignslog_cnt != 0)
		{
			# collect only 5 files as file size is greater than 15MB
			$files_to_be_copied{"/var/log/$pattern"} = "var/log";
			$files_to_be_copied{"/var/log/$pattern.[0-3]"} = "var/log";
			$files_to_be_copied{"/var/log/$pattern.[0-3].gz"} = "var/log";
			print("Copying only 5 $pattern files as some of them exceed the size limit\n");
		} else
		{
			#Collect all $pattern files
			$files_to_be_copied{"/var/log/$pattern*"} = "var/log";
		}
	}
}


#########################################################################
# Copy the messages.*, ns.log* and dmesg* files
#########################################################################

sub copy_log_files()
{
	# Define the set of files to be copied here. 
	# LHS is the set of files to be copied
	# RHS is the destination folder under $collector_abs_path
	
	# Files under /var/log
	$files_to_be_copied{"/var/log/callhome.log*"} =	"var/log";
	$files_to_be_copied{"/var/log/callhomedebug.log*"} = "var/log";
	$files_to_be_copied{"/var/log/*.log"} =	"var/log";
	$files_to_be_copied{"/var/log/httpaccess.log.*"} = "var/log";
	$files_to_be_copied{"/var/log/httperror.log.*"}	= "var/log";
	$files_to_be_copied{"/var/log/auth.log.*"} = "var/log";
	$files_to_be_copied{"/var/log/wicmd.log.*"} = "var/log";
	$files_to_be_copied{"/var/log/nitro.log.*"} = "var/log";
	$files_to_be_copied{"/var/log/sh*"} = "var/log";
	$files_to_be_copied{"/var/log/iprep.log*"} = "var/log";
	$files_to_be_copied{"/var/log/ProdlicSDK.log*"} = "var/log";

	# MegaCli LSI Raid control log
	$files_to_be_copied{"/var/log/LSI-Raid-Controller.logs*"} = "var/log";

	# Files under /var/nslog
	$files_to_be_copied{"/var/nslog/nslog.nextfile"} = "var/nslog";
	$files_to_be_copied{"/var/nslog/dmesg.boot"} = "var/nslog";
	$files_to_be_copied{"/var/nslog/dmesg.prev"} = "var/nslog";
	$files_to_be_copied{"/var/nslog/dmesg.last"} = "var/nslog";
	$files_to_be_copied{"/var/nslog/lspci*.boot"} = "var/nslog";
	$files_to_be_copied{"/var/nslog/lspci*.prev"} = "var/nslog";
	$files_to_be_copied{"/var/nslog/lspci*.last"} = "var/nslog";
	$files_to_be_copied{"/var/nslog/icstats.out"} = "var/nslog";
	$files_to_be_copied{"/var/nslog/*.log"} = "var/nslog";
	$files_to_be_copied{"/var/nslog/nsumond/*.log"} = "var/nslog/nsumond/";
	$files_to_be_copied{"/var/nslog/iprep/iprep.db"} = "var/nslog/iprep/";

	# Files under /tmp
	$files_to_be_copied{"/tmp/fsck.log"} = "var/log";
	$files_to_be_copied{"/tmp/savecore.log"} = "var/log";

	# Files under /etc
	$files_to_be_copied{"/etc/*.conf"} = "etc";
	$files_to_be_copied{"/etc/hosts"} = "etc";
	$files_to_be_copied{"/etc/localtime"} = "etc";
	$files_to_be_copied{"/etc/crontab"} = "etc";

	# Files under /var/nssynclog
	$files_to_be_copied{"/var/nssynclog/*.conf"} = "var/nssynclog";
	$files_to_be_copied{"/var/nssynclog/*.log*"} = "var/nssynclog";

	# Files under /var/nsproflog
	$files_to_be_copied{"/var/nsproflog/newproflog*"} = "var/nsproflog";
	$files_to_be_copied{"/var/nsproflog/nsproflog*"} = "var/nsproflog";

	# Files under /var
	$files_to_be_copied{"/var/.config_wipe_log"} = "var";

	# Files under /var
	$files_to_be_copied{"/flash/.recovery/.config_wipe_log"} = "flash/.recovery";

	# Files under /var/clusterd
	$files_to_be_copied{"/var/clusterd/*.conf"} = "var/clusterd";

	# Files under /var/cfsynclog
	$files_to_be_copied{"/var/cfsynclog/*.log*"} = "var/cfsynclog";

	# Copying all rsync log files from cluster specific dir /var/nsclfsync.
	# This folder is seen on any cluster instance.
	# Files under /var/nsclfsync
	$files_to_be_copied{"/var/nsclfsync/*.log*"} = "var/nsclfsync";

	# Handle directory that includes files that are pipes, eg. NSPPE-0.log.
	# We can't copy those because the copy will never complete.
	if ($hd_device ne "") {
		`find /var/nslog/ -name "*.log" -type f | xargs -n 1 -I {} cp -p {} $collector_abs_path/var/nslog 2>/dev/null`;
	}
	#Checking if huge file size is present for the following files and
	#reducing the number of files to copy
	&check_huge_log_files("ns.log");
	&check_huge_log_files ("bash.log");
	&check_huge_log_files ("notice.log");
	&check_huge_log_files ("messages");
	&log_debug_message("Copying messages, ns.log, dmesg and other log files ....\n");
	foreach my $fileset (keys(%files_to_be_copied))
	{
		#copy/syscopy routine from File::Copy does not take wildcards
		#copy("$fileset","$collector_abs_path/$files_to_be_copied{$fileset}") or
			##log_debug_message "Error in copying $fileset\n";
		`cp -p $fileset $collector_abs_path/$files_to_be_copied{$fileset} 2>/dev/null`;
	}
}

#######################################################################
# Copy vpn directory... check how useful it is...and call it later
########################################################################

sub copy_vpn_dir()
{
	`cp -p -R /var/vpn /var/tmp/support`; 
}

####################################################################
# Run BSD shell commands and capture the output
###################################################################

sub exec_shell_cmds()
{
	my $cmd;
	# Add the commands that need to be executed, in the following format
	#	<Command to be run>, <Output file to redirect the output>
	my %cmd_set = (
		"netstat -rn", "netstat-rn.out",
		"netstat -an", "netstat-an.out",
		"df -akinh", "df-akin.out",
		"du -h /", "du-h.out",
		"mount -v", "mount-v.out",
		"vmstat -m", "vmstat-m.out",
		"ps -o user,%cpu,%mem,start -alxr", "ps-aluxwr.out",
		"sysctl -a", "sysctl-a.out",
		"ifconfig -a", "ifconfig-a.out",
		"arp -a", "arp-a.out",
		"dmesg -a", "dmesg-a.out",
		"date", "date.out",
		"uname -a", "uname-a.out",
		"top -b 100", "top-b.out",
		"fstat", "fstat.out",
		"sockstat", "sockstat.out",
		"lsof -VlR", "lsof_VlR.out",
		"ls -lRtrph /flash", "ls_lRtrp_flash.out",
		"ls -lRtrph /var", "ls_lRtrp_var.out",
		"ls -lRtrph /", "ls_lRtrp.out",
		"nsapimgr -d allvariables","nsapimgr_allvariables.out",
		"nsapimgr -d mappedip","nsapimgr_mappedip.out",
		"nsapimgr -d freeports","nsapimgr_freeports.out",
		"nsapimgr -d cltmss","nsapimgr_cltmss.out",
		"nsapimgr -d svrmss","nsapimgr_svrmss.out",
		"atacontrol list","atacontrol_list.out",
		"uptime","uptime.out",
		"head -10 $0","showtech_info.out",	#Get the first 10 lines of this script to get the revision info
		"crontab -u nsroot -l","crontab-l.out",
		"pb_policy","pitboss_policy.out",
		"nsp query","nsp.out",
		"nsp nsnetsvc query","nsp_nsnetsvc.out",
		"ntpq -p","ntpq-p.out",
		"swapinfo -h","swapinfo-h.out",
		"pstat -Tt","pstat-Tt.out",
	        "netstat -s","netstat-s.out",
                "netstat -m","netstat-m.out",
                "netstat -ibnd","netstat-ibnd.out",
                "netstat -is","netstat-is.out",
        );

	#Add few commands related to MegaCli 

	if ($decapolis_22001x) {
		$cmd_set{"MegaCli64 -AdpAllInfo -aAll"} = "MegaCli64_AdpAllInfo.out";
		$cmd_set{"MegaCli64 -AdpBbuCmd -aAll"} = "MegaCli64_AdpBbuCmd.out";
		$cmd_set{"MegaCli64 -CfgDsply -aAll"} = "MegaCli64_CfgDsply.out";
#		$cmd_set{"MegaCli64 -adpalilog -a0"} = "MegaCli64_adpalilog_a0.out";
		$cmd_set{"MegaCli64 -fwtermlog -dsply -a0"} = "MegaCli64_fwtermlog_dsply_a0.out";
	}

	# Add a few commands related to PCI config. There is a problem that if we run pciconf/lspci
	# commands on FIPS boxes, the entire box hangs. Till this issue is fixed, we will run different
	# set of commands on  non-FIPS boxes

	# Run the commands only if it is a ncore system and if it is non-NGFIPS
	my $sysid_ngfips = "766600";  # for NGFIPS device
	my $sysid = `sysctl -n netscaler.sysid`;
	chomp($sysid);
	if ($ncore && ($sysid ne $sysid_ngfips)) {
		# Collect device tree for all devices
		$cmd_set{"lspci -tv"} = "lspci_tv.out"; 
		$cmd_set{"lspci -vvvxxx"} = "lspci_vvvxxx.out"; 
		$cmd_set{"lspci -vvvxxxx"} = "lspci_vvvxxxx.out"; 
	}

	# Collect memory fragments information for each PE
	my $ppeid=0;
	my $num_pe_running = `sysctl -n netscaler.num_pe_running`;
	chomp($num_pe_running);
	if ($ncore) {
		for ($ppeid=0;$ppeid<$num_pe_running;$ppeid++) {
			$cmd_set{"nsapimgr -s nsppeid=$ppeid -d mem_fragments"} = "nsapimgr_mem_fragments_nsppe_$ppeid.out";
		}
	} else {
		$cmd_set{"nsapimgr -d mem_fragments"}= "nsapimgr_mem_fragments.out";
	}

	# Collect cluster ARP cache output for each PE
	$ppeid=0;
	for ($ppeid=0;$ppeid<$num_pe_running;$ppeid++) {
		$cmd_set{"nsapimgr -s nsppeid=$ppeid -ys call=nscl_ArpCacheDump"} = "nscl_ArpCacheDump_pe$ppeid.out";
	}

	# Now all the commands are collected. Let's run these commands.
	&log_debug_message("Running shell commands ....\n");
	foreach $cmd (keys(%cmd_set)) {
		# Run the command and redirect the output into the corresponding file
		`$cmd > $collector_abs_path/shell/$cmd_set{$cmd} 2>&1`;
	}

	# Collect some OS specific shell commands in an array

	my %cmd_set_fbsd = (
		"atacontrol cap $hd_device ", "atacontrol_cap_hd_$hd_device.out",
	);
	# If flash is different from HD device, run atacontrol on flash also
	if ($flash_device ne $hd_device) {
		$cmd_set_fbsd{"atacontrol cap $flash_device"} = "atacontrol_cap_flash_$flash_device.out";
	}

	# Add running smartctl to the list of commands to be run
	# smartctl hangs on Rome/Rome10G/Thebes. Avoid running on these as of now (or until it is fixed). 

	my $sysid_rome = "675300";  # for Rome device
	my $sysid_thebes = "675310";  # for Thebes device
	my $sysid_rome10G = "675320";  # for Rome 10G device
	my $sysid_decap_21 = "2200100";  # Decapolis 36 port
	my $sysid_decap_22 = "2200110";  # Decapolis 24x10G port

	$cmd_set_fbsd{"smartctl -a /dev/$hd_device "} = "smartctl_hd_$hd_device.out"; 
	# If flash and HD are the same device, avoid running it twice
	if ($flash_device ne $hd_device) {
		$cmd_set_fbsd{"smartctl -a /dev/$flash_device"} = "smartctl_flash_$flash_device.out"; 
	}

	# Adding ipmitool commands only for NetScaler models that have IPMI driver loaded,
	# instead of checking through hardcoded sysids.
	# Check if /dev/impi0 exists and collect the output of ipmitool.
	my $ipmifile = '/dev/ipmi0';
	if (-e $ipmifile) {
		$cmd_set_fbsd{"ipmitool sel"} = "ipmitool_sel.out";
		$cmd_set_fbsd{"ipmitool sel info"} = "ipmitool_sel_info.out";
		$cmd_set_fbsd{"ipmitool sel elist"} = "ipmitool_sel_elist.out";
		$cmd_set_fbsd{"ipmitool sel list"} = "ipmitool_sel_list.out";
		$cmd_set_fbsd{"ipmitool bmc info"} = "ipmitool_bmc_info.out";
		$cmd_set_fbsd{"ipmitool sensor list"} = "ipmitool_sensor_list.out";
	}
	# The following commands are added for systems which contain LOM
	my $lom_wc = `grep "ipmi0: IPMI device rev" /var/nslog/dmesg.boot | wc -l`;
	if ($lom_wc > 0) {
		$cmd_set_fbsd{"ipmitool mc info"} = "ipmitool_mc_info.out";
		$cmd_set_fbsd{"ipmitool lan print"} = "ipmitool_lan_print.out";
	}

	# Now all the system specific commands are collected. Let's run these commands.
	foreach $cmd (keys(%cmd_set_fbsd)) {
		# Run the command and redirect the output into the corresponding file
		`$cmd > $collector_abs_path/shell/$cmd_set_fbsd{$cmd} 2>&1`;
	}
}

####################################################################
# Run vtysh commands and capture the output
###################################################################

sub exec_vtysh_cmds()
{
	# Add the commands that need to be executed, in the following format
	#	<Command to be run>
	my @cmd_set = (
		"show version",
		"show interface",
		"show nsm client",
		"show ip route",
		"show ip route summary",
		"show ip protocols rip",
		"show ip protocols bgp",
		"show ip protocols ospf",
		"show ip rip database",
		"show ip rip interface",
		"show ip rip route",
		"show ip ospf",
		"show ip ospf route",
		"show ip ospf database",
		"show ip ospf interface",
		"show ip ospf neighbor detail",
		"show bgp ipv4 neighbors",
		"show ipv6 route",
		"show ipv6 protocols rip",
		"show ipv6 rip database",
		"show ipv6 rip interface",
		"show ipv6 ospf route",
		"show ipv6 ospf database",
		"show ipv6 ospf interface",
		"show ipv6 ospf neighbor detail",
		"show bgp ipv6 neighbors",
		"show ip bgp",
		"show ip bgp summary",
		"show bgp ipv6",
		"show bgp ipv6 summary",
		"show ip protocols isis",
		"show ipv6 protocols isis",
		"show isis database detail",
		"show isis interface",
		"show clns neighbors",
		"show clns is-neighbors detail",
		"show isis topology",
		"show ip isis route",
		"show ipv6 isis topology",
		"show ipv6 isis route",
		"show running-config",
		"show process",
		"show cluster-config",
		"show cluster state",
		"sh ns sync-status"
	);

	&log_debug_message("Running vtysh commands ....\n");
	foreach my $cmd (@cmd_set) {
		# Run the command and redirect the output into the corresponding file
		`echo "------------------------------\n" >> $collector_abs_path/shell/vtyshcmds.txt 2>&1`;
		`echo "vtysh -e \\\"$cmd\\\"" >> $collector_abs_path/shell/vtyshcmds.txt 2>&1`;

		`vtysh -e \"$cmd\" >> $collector_abs_path/shell/vtyshcmds.txt 2>&1`;
	}
}

###########################################################################
## Run vtysh commands in admin partition and capture the output
#############################################################################

sub exec_vtysh_cmds_partition()
{
	my @cmd_set = (
		"show interface",
		"show ip route",
		"show ip route summary",
		"show ip protocols rip",
		"show ip protocols bgp",
		"show ip protocols ospf",
		"show ip rip database",
		"show ip rip interface",
		"show ip rip route",
		"show ip ospf",
		"show ip ospf route",
		"show ip ospf database",
		"show ip ospf interface",
		"show ip ospf neighbor detail",
		"show bgp ipv4 neighbors",
		"show ipv6 route",
		"show ipv6 protocols rip",
		"show ipv6 rip database",
		"show ipv6 rip interface",
		"show ipv6 ospf route",
		"show ipv6 ospf database",
		"show ipv6 ospf interface",
		"show ipv6 ospf neighbor detail",
		"show bgp ipv6 neighbors",
		"show ip bgp",
		"show ip bgp summary",
		"show bgp ipv6",
		"show bgp ipv6 summary",
		"show ip protocols isis",
		"show ipv6 protocols isis",
		"show isis database detail",
		"show isis interface",
		"show clns neighbors",
		"show clns is-neighbors detail",
		"show isis topology",
		"show ip isis route",
		"show ipv6 isis topology",
		"show ipv6 isis route",
		"show running-config",
	);

	foreach my $num_id (0..$#admin_partition_arr) {
		if($admin_partition_arr[$num_id]->Flag) {
			my $name = scalar ($admin_partition_arr[$num_id]->Name);
			open (FILE, ">$collector_abs_path/shell/partitions/$name/vtyshcmds.txt") ||
				die "Error in creating $collector_abs_path/shell/partitions/$name/vtyshcmds.txt!!\n\n";
			close (FILE);
			my $tmp_file = "$collector_abs_path/shell/partitions/$name/vtyshcmds.txt";
			foreach my $cmd (@cmd_set) {
				# Run the command and redirect the output into the corresponding file
				`echo "------------------------------\n" >>	$tmp_file 2>&1`;
				`echo "vtysh -v $name -e \\\"$cmd\\\"" >> $tmp_file 2>&1`;
				`vtysh -v $name -e \"$cmd\" >> $tmp_file 2>&1`;
			}
		}
	}
}



####################################################################
# Run NetScaler CLI show commands and capture the output
###################################################################

sub
exec_show_cmds()
{
	my $tmp_file;

	my @showcmds = (
		"show ns version\n",
		"show callhome\n",
		"show hardware\n",
		"show server\n",
		"show ha node\n",
		"show service\n",
		"show service -internal\n",
		"show serviceGroup\n",
		"show vserver\n",
		"show monitor\n",
		"show vlan\n",
		"show interface\n",
		"show channel\n",
		"show lacp\n",
		"show location\n",
		"show locationparameter\n",
		"show locationfile\n",
		"show route\n",
		"show aaa user\n",
		"show aaa group\n",
		"show aaa radiusparams\n",
		"show aaa ldapparams\n",
		"show aaa tacacsparams\n",
		"show aaa certparams\n",
		"show aaa parameter\n",
		#"show aaa session\n", #Masked because this command takes several hours to complete.
		"show appfw settings\n",
		"show appfw policy\n",
		"show appfw profile\n",
		"show appfw stats\n",
		"show audit syslogaction\n",
		"show audit syslogpolicy\n",
		"show audit syslogparams\n",
		"show audit nslogaction\n",
		"show audit nslogpolicy\n",
		"show audit nslogparams\n",
		"show audit messages\n",
		"show authentication radiusaction\n",
		"show authentication ldapaction\n",
		"show authentication tacacsaction\n",
		"show authentication certaction\n",
		"show authentication nt4action\n",
		"show authentication localpolicy\n",
		"show authentication radiuspolicy\n",
		"show authentication certpolicy\n",
		"show authentication ldappolicy\n",
		"show authentication tacacspolicy\n",
		"show authentication nt4policy\n",
		"show authorization policy\n",
		"show authentication samlAction \n",
		"show authentication samlIdPPolicy \n",
		"show authentication samlIdPProfile \n",
		"show authentication samlPolicy \n",
		"show authentication webAuthAction \n",
		"show authentication webAuthPolicy \n",
		"show cache policy\n",
		"show cache global\n",
		"show cache contentgroup\n",
		"show cache forwardProxy\n",
		"show cache selector\n",
		"show cache object\n",
		"show cache parameter\n",
		"show cli mode\n",
		"show cli prompt\n",
		"show cli attribute \n",
		"show cmp action\n",
		"show cmp policy\n",
		"show cmp global\n",
		"show cr policy\n",
		"show cr vserver\n",
		"show cs policy\n",
		"show cs vserver\n",
		"show dns addRec\n",
		"show dns cnameRec\n",
		"show dns mxRec\n",
		"show dns nsRec\n",
		"show dns parameter\n",
		"show dns soaRec\n",
		"show dns suffix\n",
		"show dns nameserver\n",
		"show dns action64 \n",
		"show dns naptrRec \n",
		"show dos policy\n",
		"show filter action\n",
		"show filter policy\n",
		"show filter global\n",
		"show gslb site\n",
		"show gslb service\n",
		"show gslb vserver\n",
		"show gslb binding\n",
		"show gslb parameter\n",
		"show gslb policy\n",
		"show gslb ldnsentries\n",
		"show gslb syncStatus\n",
		"show gslb domain\n",
		"show gslb runningConfig\n",
		"show lb group\n",
		"show lb vserver\n",
		"show lb route\n",
		"show lb profile \n",
		"show lb sipParameters \n",
		"show arp\n",
		"show vrID\n",
		"show bridgetable\n",
		"show pbr\n",
		"show pbr6\n",
		"show ns config\n",
		"show ns hostname\n",
		"show ns acl\n",
		"show simpleacl\n",
		"show simpleacl6\n",
		"show ns feature\n",
		"show ns info\n",
		"show ns ip\n",
		"show ns mode\n",
		"show fis\n",
		#"show ci\n", #This is shown in the output of "show node"
		"show ns license\n",
		"show rnat\n",
		"show route\n",
		"show ns param\n",
		"show ns spparams\n",
		"show ns tcpbufparam\n",
		"show ns tcpparam\n",
		"show ns tcpprofile\n",
		"show ns weblogparam\n",
		"show ns rateControl\n",
		"show ns rpcnode\n",
		"show policy expression\n",
		"show policy map\n",
		"show policy param \n",
		#"show pq binding\n", #TODO: This requires a vserver as an argument
		"show pq policy\n",
		"show rewrite policy\n",
		"show rewrite action\n",
		"show rewrite global\n",
		"show rewrite param\n",
		"show router ospf\n",
		"show ospf route\n",
		"show router rip\n",
		#"show router bgp\n", #TODO: This requires an argument
		"show snmp alarm\n",
		"show snmp community\n",
		"show snmp manager\n",
		"show snmp mib\n",
		"show snmp trap\n",
		"show snmp oid vserver\n",
		"show snmp oid service\n",
		"show snmp oid servicegroup\n", #This works only in >= 9.0
		"show sc parameter\n",
		"show sc policy\n",
		"show ssl certkey\n",
		"show ssl certlink\n",
		"show ssl cipher\n",
		"show ssl crl\n",
		"show ssl fips\n",
		"show ssl fipskey\n",
		#"show ssl service\n", #This requires an argument
		#"show ssl vserver\n", #This requires an argument
		"show ssl wrapkey\n",
		"show ssl parameter\n",
		"show ssl action\n",
		"show ssl global\n",
		"show ssl ocspResponder\n",
		"show ssl policy\n",
		"show ssl dtlsProfile \n",
		"show system session\n",
		"show system cmdPolicy\n",
		"show system user\n",
		"show system group\n",
		"show system global\n",
		"show tunnel trafficpolicy\n",
		"show tunnel global\n",
		"show vpn vserver\n",
		"show vpn intranetapplication\n",
		"show vpn global\n",
		"show vpn trafficpolicy\n",
		"show vpn trafficaction\n",
		"show vpn url\n",
		"show vpn sessionpolicy\n",
		"show vpn sessionaction\n",
		"show vpn parameter\n",
		"show vpn sfconfig \n",
		"show vpn alwaysONProfile \n",
		"show ns ip6\n",
                "show  nd6rAvariables\n",
                "show onLinkIPv6Prefix\n",
                "show ipv6 \n",
		"show route6\n",
		"show acl6\n",
		"show nd6\n",
		"show vrid6\n",
		"show ntp server\n",
		"show ntp sync\n",
		"show wi site\n",
		"show cluster node\n",
		"show cluster instance\n",
		"show cluster claMacs\n",
		"show httpBand type REQUEST\n",
		"show httpBand type RESPONSE\n",
		"show configstatus\n",
		"show trafficDomain\n",
		"show partition\n",
      	        "show responder action\n",
  	 	"show responder policy\n",
      	        "show responder policylabel\n",
      	        "show responder param\n",
      	        "show responder global\n",
       	        "show responder htmlpage\n",
                "show ipTunnel\n",
                "show ipTunnelParam\n",
                "show ip6Tunnel\n",
                "show ip6TunnelParam\n",
                "show lldp neighbors \n",
                "show lldp param \n",
                "show lldp stats \n",                               
                "show connectiontable -Listen\n",
                "show system parameter\n",
		"show wf site \n",
		"show wf package \n",
		"show appfw JSONContentType \n", 
		"show appfw XMLContentType \n",
		"show appfw xmlerrorpage \n",
		"show action64 \n",
		"show appAlgParam \n",
		"show attribute \n",
		"show bridgegroup \n",
		"show ns dhcpParams \n",
		"show diameter \n",
		"show domain \n",
		"show dtlsProfile \n",
		"show forwardingSession \n",
		"show policy httpCallout \n",
		"show httpParam \n",
		"show httpProfile \n",
		"show vpn icaConnection \n",
		"show icaDtlsConnection \n",
		"show ica latencyprofile \n",
		"show inat \n",
		"show inatparam \n",
		"show ipsec parameter \n",
		"show ipsec profile \n",
		"show aaa kcdAccount \n",
		"show dns key \n", 
		"show limitIdentifier \n",
		"show linkset \n",
		"show loginSchema \n",
		"show loginSchemaPolicy \n",
		"show naptrRec \n",
		"show nat64 \n",
		"show nat64param \n",
		"show netProfile \n",
		"show netbridge \n",
		"show vpn nextHopServer \n",
		"show cluster nodegroup \n",
		"show snmp option \n",
		"show dns ptrRec \n",
		"show rise param \n",
		"show rise profile \n",
		"show rise rhi \n",
		"show rise apbrSvc \n",
		"show rsskeytype \n",
		"show samlAction \n",
		"show samlIdPPolicy \n",
		"show samlIdPProfile \n",
		"show samlPolicy \n",
		"show sessionParameter \n",
		"show sipParameters \n",
		"show dns srvRec \n",
		"show timeout \n",
		"show transform action \n",
		"show transform global \n",
		"show transform policy \n",
		"show transform policylabel \n",
		"show transform profile \n",
		"show dns txtRec \n",
		"show vxlan \n",
		"show vxlanVlanMap \n",
		"show webAuthAction \n",
		"show webAuthPolicy \n",
		"show xmlerrorpage \n",
		"show xmlnamespace \n",
		"show dns zone \n",
		"show ns licenseserver \n",
		"show ns licenseserverpool \n",
		"show ns licenseproxyserver \n",
		"show ns sourceroutecachetable \n",
		"show ns centralmanagementserver \n",
		"show ns capacity \n",
		"show ns diameter \n",
		"show lsn session \n",
		"show lsn static \n",
		"show raid \n",
		"show ulfd server \n",
		"show pcp server \n",
		"show pcp profile \n",
		"show pcp map \n",
		"show tm sessionParameter \n",
	);

	#Add the show command for each interface to the list.
	my @temp_iface_output=`nscli -U %%:nsroot:. show interface 2>&1`;
	foreach(@temp_iface_output) {
		if (/Interface /) {
			my $tmp_str;
			$tmp_str=(split /Interface /)[1];
			$tmp_str=(split(/\(/,$tmp_str))[0];
			push(@showcmds, "show interface $tmp_str\n");
		}
	}

	#Add predefined number of IP addresses to the list
	my @temp_output=`nscli -U %%:nsroot:. show ns ip 2>&1`;
	my $local_ip_count=0;
	foreach(@temp_output) {
		#Print the first $NS_SHOW_IP_CNT IP addresses information
		if ($NS_SHOW_IP_CNT <= $local_ip_count) {
			last;
		}
		next if $_ =~ /VIP/;
		if ($_ =~ /(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})/) {
			$local_ip_count++;
			push(@showcmds, "show ns ip $1\n");
		}
	}
	
	# Add the show command for each cluster node if cluster configuration is set.
	my @temp_output=`nscli -U %%:nsroot:. show cluster node 2>&1`;
	my $node_id;
	foreach(@temp_output) {
		if (/Node ID:/) {
			($node_id) = (split /Node ID:/)[1];   #Use : as the delimiter
			($node_id) =~ s/\(\w+\)//;      #Remove paren, any alpha numeric char in between the paren
			($node_id) =~ s/^\s+//;         #Remove trailing whitespaces
			($node_id) =~ s/\s+$//;         #Remove ending whitespaces
			push(@showcmds, "show cluster node $node_id\n");
		}
	}

	my $lsn_feat_ena = `nscli -U %%:nsroot:. show feature 2>&1 | grep "Large Scale NAT" | grep \" ON$\"`;
	if ($lsn_feat_ena) {
        my @lsn_items = (
                "pool",
                "client",
                "group",
                "transportprofile",
                "httphdrlogprofile",
                "appsprofile",
                "static",
                "ip6profile",
                "sipalgprofile",
                "rtspalgprofile"
        );
        my @lsn_clients;
        foreach my $item (@lsn_items){
                my @temp_lsn=`nscli -U %%:nsroot:. show lsn $item 2>&1`;
                if ($item eq "client") {
                        @lsn_clients = @temp_lsn;
                }
                foreach(@temp_lsn) {
                        if (/Name/) {
                                my $tmp_str;
                                $tmp_str=(split /Name\: /)[1];
                                push(@showcmds, "show lsn $item $tmp_str\n");
                        }
                }
        }
        foreach (@lsn_clients) {
                if (/Name/) {
                        my $tmp_str;
                        $tmp_str=(split /Name\: /)[1];
                        push(@showcmds, "show lsn deterministicNat -clientname $tmp_str\n");
                }
        }
        push(@showcmds, "show lsn parameter\n");
    }
	# Create a temporary file to store all the commands
	$tmp_file = "$collector_abs_path/shell/showcmds.tmp";
	open (FILE,"> $tmp_file") || die "Error in creating $tmp_file!!\n\n";
	print FILE @showcmds;
	close(FILE);

	# Now run all the commands through nscli and redirect the output
	&log_debug_message("Running CLI show commands ....\n");
	open (FILE, ">$collector_abs_path/shell/showcmds.txt") || 
			die "Error in creating $collector_abs_path/shell/showcmds.txt!!\n\n";
	my $buf = `nscli -U %%:nsroot:. batch -f $tmp_file 2>&1`; 
	print FILE "$buf"; 
	close(FILE); 
	unlink("$tmp_file");

	# Collect 'show ns runningConfig' output to file shell/ns_running_config.conf
	&log_debug_message("Collecting ns running configuration....\n");
	open (FILE, ">$collector_abs_path/shell/ns_running_config.conf") || 
			die "Error in creating $collector_abs_path/shell/ns_running_config.conf!!\n\n";
	my $buf = `nscli -U %%:nsroot:. show runningconf -withdefaults 2>&1`; 
	print FILE "$buf"; 
	close(FILE); 

	# Collect 'show gslb runningConfig' output to file shell/gslb_running_config.conf
	&log_debug_message("Collecting running gslb configuration....\n");
	open (FILE, ">$collector_abs_path/shell/gslb_running_config.conf") || 
			die "Error in creating $collector_abs_path/shell/gslb_running_config.conf!!\n\n";
	my $buf = `nscli -U %%:nsroot:. show gslb runningConfig 2>&1`; 
	print FILE "$buf"; 
	close(FILE); 

}

###########################################################################
# Run NetScaler CLI show commands in admin partition and capture the output
############################################################################


sub exec_show_cmds_partition()
{
	my $tmp_file;

	my @showcmds = (
		"show server\n",
		"show service\n",
		"show serviceGroup\n",
		"show vserver\n",
		"show monitor\n",
		"show vlan\n",
		"show route\n",
		#"show aaa session\n", #Masked because this command takes several hours to complete.
		"show audit messages\n",
		"show cli mode\n",
		"show cmp action\n",
		"show cmp policy\n",
		"show cmp global\n",
		"show cs policy\n",
		"show cs vserver\n",
		"show dns parameter\n",
		"show dns suffix\n",
		"show dns nameserver\n",
		"show lb group\n",
		"show lb vserver\n",
		"show vrID\n",
		"show ns config\n",
		"show ns acl\n",
		"show ns feature\n",
		"show ns info\n",
		"show ns ip\n",
		"show ns mode\n",
		"show fis\n",
		#"show ci\n", #This is shown in the output of "show node"
		"show ns license\n",
		"show rnat\n",
		"show route\n",
		"show ns spparams\n",
		"show ns tcpbufparam\n",
		"show ns tcpparam\n",
		"show ns tcpprofile\n",
		"show ns rateControl\n",
		"show policy expression\n",
		#"show pq binding\n", #TODO: This requires a vserver as an argument
		"show rewrite policy\n",
		"show rewrite action\n",
		"show rewrite global\n",
		"show rewrite param\n",
		#"show router bgp\n", #TODO: This requires an argument
		"show snmp alarm\n",
		"show snmp community\n",
		"show snmp manager\n",
		"show snmp mib\n",
		"show snmp trap\n",
		"show snmp oid vserver\n",
		"show snmp oid service\n",
		"show snmp oid servicegroup\n", #This works only in >= 9.0
		"show sc parameter\n",
		"show ssl certkey\n",
		"show ssl certlink\n",
		"show ssl cipher\n",
		"show ssl crl\n",
		#"show ssl service\n", #This requires an argument
		#"show ssl vserver\n", #This requires an argument
		"show ssl parameter\n",
		"show ssl action\n",
		"show ssl global\n",
		"show ssl ocspResponder\n",
		"show ssl policy\n",
		"show system user\n",
		"show system global\n",
		"show runningconf -withdefaults\n",
		"show ns ip6\n",
		"show route6\n",
		"show acl6\n",
		"show vrid6\n",
		"show ntp server\n",
		"show ntp sync\n",
		"show configstatus\n",
		"show trafficDomain\n",
		"show dns addRec\n",
		"show dns cnameRec\n",
		"show dns mxRec\n",
		"show dns nsRec\n",
		"show dns soaRec\n",
		"show arp\n",
		"show bridgetable\n",
		"show ns rpcnode\n",
		"show ssl fips\n",
		"show ssl fipskey\n",
		"show httpBand type REQUEST\n",
		"show httpBand type RESPONSE\n",
	);

    my $showcmdscount = scalar @showcmds;
    my $filehdlr_pos = 0;
	# Create a temporary file to store all the commands
	$tmp_file = "$collector_abs_path/shell/showcmdspartition.tmp";
	open (FILE,"> $tmp_file") || die "Error in creating $tmp_file!!\n\n";
	print FILE @showcmds;
	$filehdlr_pos = tell(FILE); #Current position of file handler

	# Now run all the commands through nscli and redirect the output
	foreach my $num_id (0..$#admin_partition_arr) {
		if($admin_partition_arr[$num_id]->Flag) {
			my $name = scalar ($admin_partition_arr[$num_id]->Name);
			mkdir "$collector_abs_path/$shell_partition_dir/$name";
			open (FILE_OUT, ">$collector_abs_path/$shell_partition_dir/$name/showcmds.txt") || 
				die "Error in creating $collector_abs_path/$shell_partition_dir/$name/showcmds.txt!!\n\n";

			#Add predefined number of IP addresses to the list
			my @temp_nsip_output=`nscli -U %%:nsroot:. "switch partition $name; show ns ip" 2>&1`;
			my $local_ip_count=0;
			my @shownsipcmds = ();
			foreach(@temp_nsip_output) {
				if ($NS_SHOW_IP_CNT <= $local_ip_count) {
					    last;
				}
				next if $_ =~ /VIP/;
				if ($_ =~ /(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})/) {
					$local_ip_count++;
					push(@shownsipcmds, "show ns ip $1\n");
				}
			}
			if ($local_ip_count) {
				print FILE @shownsipcmds; #Append 'show ns ip' commands to existing list in tmp_file
			}
			my $buf = `nscli -U %%:nsroot:. "switch partition $name; batch -f $tmp_file" 2>&1`; 
			print FILE_OUT "$buf"; #Write show cmds output to showcmds.txt file
			close(FILE_OUT);

			# Seek old file handler position and ...
			# Truncate to make room for new set of NS IPs in the next iteration
			seek(FILE,$filehdlr_pos,0);
			truncate (FILE, $filehdlr_pos);
		}
	}
	close(FILE);
	unlink("$tmp_file");
}

###########################################################################
# This function runs all "stat" commands and redirects the output to
# <collector's base dir>/shell/statcmds.txt.
###########################################################################

sub exec_stat_cmds()
{
	my $tmp_file;
	my @statcmds = (
		"stat system -detail\n",
		"stat system cpu\n",
		"stat system memory\n",
		"stat service\n",
		"stat serviceGroup\n",
		"stat vlan\n",
		"stat interface\n",
		"stat aaa\n",
		"stat audit\n",
		"stat cmp\n",
		"stat cs vserver\n",
		"stat dns\n",
		"stat lb vserver\n",
		"stat ns\n",
		"stat bridge\n",
		"stat ha node -detail\n",
		"stat ns acl\n",
		"stat protocol tcp\n",
		"stat protocol http\n",
		"stat protocol icmp\n",
		"stat protocol ip\n",
		"stat protocol udp\n",
		"stat snmp\n",
		"stat ssl\n",
		"stat vpn\n",
		"stat icmpv6\n",
		"stat acl6\n",
		"stat protocol ipv6\n",
		"stat cluster instance -detail\n",
		"stat cluster node -detail\n",
		"stat trafficDomain\n",
		"stat ha node -detail\n",
		"stat cache -detail\n",
		"stat lldp -detail\n",
		"stat appflow\n",
		"stat appfw\n",
		"stat cr vserver\n",
		"stat domain\n",
		"stat feo\n",
		"stat gslb domain\n",
		"stat gslb service\n",
		"stat gslb site\n",
		"stat gslb vserver\n",
		"stat http\n",
		"stat icmp\n",
		"stat inat\n",
		"stat instance\n",
		"stat ipv6\n",
		"stat mediaclassification\n",
		"stat nat64\n",
		"stat partition\n",
		"stat pbr\n",
		"stat pbr6\n",
		"stat records\n",
		"stat rnat\n",
		"stat rnatip\n",
		"stat samlIdPPolicy\n",
		"stat simpleacl\n",
		"stat simpleacl6\n",
		"stat spillover policy\n",
		"stat tcp\n",
		"stat tm trafficPolicy\n",
		"stat tunnelip\n",
		"stat tunnelip6\n",
		"stat udp\n",
		"stat vpath\n",
		"stat vxlan\n",
	);

	#Add the stat command for each interface to the list.
	my @temp_iface_output=`nscli -U %%:nsroot:. show interface 2>&1`;
	foreach(@temp_iface_output) {
		if (/Interface /) {
			my $tmp_str;
			$tmp_str=(split /Interface /)[1];
			$tmp_str=(split(/\(/,$tmp_str))[0];
			push(@statcmds, "stat interface $tmp_str\n");
		}
	}

	my $lsn_feat_ena = `nscli -U %%:nsroot:. show feature 2>&1 | grep "Large Scale NAT" | grep \" ON$\"`;
    if ($lsn_feat_ena) {
        push(@statcmds, "stat lsn\n");
        push(@statcmds, "stat lsn dslite\n");
        my @temp_lsn_group_output=`nscli -U %%:nsroot:. show lsn group 2>&1`;
        foreach(@temp_lsn_group_output) {
                if (/Name/) {
                        my $tmp_str;
                        $tmp_str=(split /Name\: /)[1];
                        push(@statcmds, "stat lsn group $tmp_str\n");
                }
        }
	}

	#Add the stat command for each partition to the list.
	if ($admin_partition) {
		foreach my $num_id (0..$#admin_partition_arr) {
			my $name = scalar ($admin_partition_arr[$num_id]->Name);
			push(@statcmds, "stat partition $name -detail\n");
		}
	}
	# Create a temporary file to store all the commands
	$tmp_file = "$collector_abs_path/shell/statcmds.tmp";
	open (FILE,"> $tmp_file") ||
				die "Error in creating $tmp_file!!\n\n";
	print FILE @statcmds;
	close(FILE);

	# Now run all the commands through nscli and redirect the output
	&log_debug_message("Running CLI stat commands ....\n");
	open (FILE, ">$collector_abs_path/shell/statcmds.txt") ||
				die "Error in creating $tmp_file!!\n\n";
	my $buf = `nscli -U %%:nsroot:. batch -f $tmp_file 2>&1`; 
	print FILE "$buf"; 
	close(FILE); 

	unlink("$tmp_file");
}


###########################################################################
# This function runs all "stat" commands in admin partition and
# redirects the output to  <collector's base dir>/shell/partition_id/statcmds.txt.
###########################################################################

sub exec_stat_cmds_partition()
{
	my $tmp_file;
	my @statcmds = (
		"stat system -detail\n",
		"stat system cpu\n",
		"stat system memory\n",
		"stat service\n",
		"stat serviceGroup\n",
		"stat vlan\n",
		"stat audit\n",
		"stat cmp\n",
		"stat cs vserver\n",
		"stat dns\n",
		"stat lb vserver\n",
		"stat bridge\n",
		"stat ns acl\n",
		"stat protocol tcp\n",
		"stat protocol http\n",
		"stat protocol icmp\n",
		"stat protocol ip\n",
		"stat protocol udp\n",
		"stat snmp\n",
		"stat ssl\n",
		"stat icmpv6\n",
		"stat acl6\n",
		"stat protocol ipv6\n",
		"stat trafficDomain\n",
	);

	# Create a temporary file to store all the commands
	$tmp_file = "$collector_abs_path/shell/statcmds_partition.tmp";
	open (FILE,"> $tmp_file") ||
				die "Error in creating $tmp_file!!\n\n";
	print FILE @statcmds;
	close(FILE);

	# Now run all the commands through nscli for partition and redirect the output

	foreach my $num_id (0..$#admin_partition_arr) {
		if($admin_partition_arr[$num_id]->Flag) {
			my $name = scalar ($admin_partition_arr[$num_id]->Name);
			open (FILE, ">$collector_abs_path/shell/partitions/$name/statcmds.txt") || 
				die "Error in creating $collector_abs_path/shell/partitions/$name/statcmds.txt!!\n\n";
			my $buf = `nscli -U %%:nsroot:. "switch partition $name; batch -f $tmp_file" 2>&1`; 
			print FILE "$buf"; 
			close(FILE); 
		}
	}

	unlink("$tmp_file");
}


###########################################################################
# This group of functions collects newnslog files
###########################################################################

# Returns the next newnslog index to be used found in nslog.nextfile, or an error if its not found
sub newnslog_get_next_index() {
	my $newnslog_path = $_[0];

	if (! -f "$newnslog_path/nslog.nextfile") {
		&log_debug_message("ERROR: nslog.nextfile missing\n", SYSLOG, NOTICE);
		return -1;
	}

	my $newnslog_next_index = `cat $newnslog_path/nslog.nextfile`;
	# Trim off lead/tail whitespace
	$newnslog_next_index =~ s/^\s+|\s+$//g;
	chomp($newnslog_next_index);

	# Check for nondigit and empty string
	if ($newnslog_next_index =~ /\D/ || $newnslog_next_index eq "") {
		&log_debug_message("ERROR: Invalid next index value in nslog.nextfile\n", SYSLOG, NOTICE);
		return -1;
	}

	return $newnslog_next_index;
}

# Returns the maximum newnslog index found in ns.log, or an error if its not found
sub newnslog_get_max_index() {
	my $newnslog_path = $_[0];

	if (! -f "$newnslog_path/ns.log") {
		&log_debug_message("ERROR: $newnslog_path/ns.log missing\n", SYSLOG, NOTICE);
		return -1;
	}

	my $sed_string = q{sed -e 's/^.*LOGFILECNT=\([0-9]*\).*$/\1/g'};
	my $newnslog_max_index = `grep -m1 "current settings" $newnslog_path/ns.log | $sed_string`;
	$newnslog_max_index =~ s/^\s+|\s+$//g;

	# Check for nondigit and empty string
	if ($newnslog_max_index =~ /\D/ || $newnslog_max_index eq "") {
		&log_debug_message("ERROR: Invalid max index value in $newnslog_path/ns.log\n", SYSLOG, NOTICE);
		return -1;
	}

	return $newnslog_max_index;
}

# Returns a matching newnslog file or directory for the supplied index, or nothing if none is found
sub newnslog_get_filename_for_index() {
	my $newnslog_path = $_[0];
	my $index = $_[1];

	my $newnslog_basename = "newnslog";
	my $newnslog_file = "";

	$newnslog_file = "$newnslog_path/$newnslog_basename.$index";
	if ( -d $newnslog_file) {
		return $newnslog_file;
	}

	$newnslog_file = "$newnslog_path/$newnslog_basename.$index.tar.gz";
	if (-f $newnslog_file) {
		return $newnslog_file;
	}

	$newnslog_file = "$newnslog_path/$newnslog_basename.$index.tar";
	if (-f $newnslog_file) {
		return $newnslog_file
	}

	# Didnt find one
	return 0;
}

# Returns a list of newnslog files up to $list_size length, it will always contain at least "newnslog"as long as the file exists
sub newnslog_create_file_list() {
	my $newnslog_path = $_[0];
	my $list_size = $_[1];

	my @newnslog_list = ();
	my $current_newnslog = "";
	my $newnslog_next_index = 0;
	my $newnslog_max_index = 0;
	my $remaining_iterations = 0;
	my $current_index = 0;


	# If we dont get a valid next index, dont allow the loop to run and list to build.
	# Gather newnslog only.
	if ( ($newnslog_next_index = &newnslog_get_next_index($newnslog_path)) == -1) {
		$list_size = 0;
	}

	# Same if we dont get a valid max index. Gather newnslog only.
	if ( ($newnslog_max_index = &newnslog_get_max_index($newnslog_path)) == -1) {
		$list_size = 0;
	}

	# Final check on these values, we're sure they are numbers now but make sure they appear sane
	if ($newnslog_next_index > $newnslog_max_index) {
		$list_size = 0;
	}

	if(!$list_size) {
		&log_debug_message("There was a problem determining which files to gather, trying newnslog only...\n", SYSLOG, NOTICE);
	}

	# Decrement the NEXT index to get the CURRENT index. A -1 value is possible
	# and will cause us to wrap to $newnslog_max_index.
	$current_index = $newnslog_next_index - 1;

	# Always try to gather newnslog. If its not found here, and indexes are OK, we'll try to gather other files.
	# If the indexes were bad, and newnslog was not found, we'll gather nothing.
	if (-d "$newnslog_path/newnslog") {
		push @newnslog_list, "$newnslog_path/newnslog";

		# $list_size controls loop operation, dont make it -1 but still count newnslog against the list.
		if ($list_size) {
			$list_size--;
		}
	}

	#We want to loop over all the possible files, but we dont want to wrap around more than once.
	$remaining_iterations = $newnslog_max_index;
	while ($list_size && $remaining_iterations) {

		#Handle wrapping the index back around when we reach 0
		if ($current_index == -1) {
			$current_index = $newnslog_max_index;
		}

		$current_newnslog = &newnslog_get_filename_for_index($newnslog_path, $current_index);

		# If we find a file or dir, push it to the list for collection
		if ($current_newnslog) {
			push @newnslog_list, $current_newnslog;
		}
		# Discard it, dont decrement the list size because we still want to find another file to take the missing spot
		else {
			$current_index--;
			$remaining_iterations--;
			next;
		}

		$current_index--;
		$list_size--;
		$remaining_iterations--;

	}
	return @newnslog_list;
}

#Calculate real or estimated size of a newnslog file or directory. We do compression estimation because the final collector
#itself will be compressed, even though the newnslog we are currently working with may not be.
sub newnslog_get_size() {
	my $newnslog = $_[0];
	my $size = 0;
	my $avg_compressed_size = 0;
	# Compression is around 80%
	my $compression_fudge_factor = .8;

	&log_debug_message("Checking size for: $newnslog\n", SYSLOG, NOTICE);

	# If its a file, its already at least .tar or .tar.gz, just get the size
	if (-f "$newnslog") {
		$size = -s "$newnslog";

		# Sample the newslog for compression use if its compressed
		if (index($newnslog, ".gz") != -1) {
			$compressed_files_size += $size;
			$compressed_files_sampled++;
			&log_debug_message("\tUsing it for compressed size sampling, size was $size\n", SYSLOG, NOTICE);
		}
		# Otherwise just get the size (done in beginning of this if(){} block)
	}

	#If it a dir, perform magic
	if (-d "$newnslog") {
		# Since the collector itself will be compressed, we dont want to calculate the size based on the uncompressed contents. Use other compressed newnslogs as a clue
		# for what the averge size of a compressed newnslog should be.
		if ($compressed_files_sampled && $compressed_files_size) {
			#Rolling average size of compressed files
			$avg_compressed_size = $compressed_files_size/$compressed_files_sampled;
			&log_debug_message("\tUsing average size of compressed files: $avg_compressed_size\n", SYSLOG, NOTICE);
			$size = $avg_compressed_size;
		}
		# We havent sampled a compressed file yet, just estimate the size based on 80% compression
		else {
			#TODO: Either do or dont use globs
			#If this glob fails to return any files its ok, we'll return size 0 later.
			my @file_list = glob "$newnslog/*";
			foreach my $file (@file_list) {
				$size += -s "$file";
			}
			&log_debug_message("\tUsing average compression ratio: $compression_fudge_factor with total size $size\n", SYSLOG, NOTICE);
			$size = $size - ($size*$compression_fudge_factor);
		}
	}

	&log_debug_message("\tFinal size: $size bytes\n", SYSLOG, NOTICE);

	return $size;
}

# Calculate and return elapsed time in seconds between two files as long as timestamps appear to be unmodified.
sub newnslog_get_time_diff() {
	my $newnslog = $_[0];
	# prev_newnslog is the one we saw last, not one which happened earlier in time. In this case its later in time because
	# we start from now (newnslog) and work our way backwards.
	my $prev_newnslog = $_[1];

	# If we encounter "newnslog" dir we need to get mtime from a file inside it. ppe.0 should always be reliable.
	if (-d $newnslog) {
		$newnslog = "$newnslog/newnslog.ppe.0";
	}

	if (-d $prev_newnslog) {
		$prev_newnslog = "$prev_newnslog/newnslog.ppe.0";
	}

	# Make sure we found the fallback ppe.0 files, otherwise this check is not needed.
	if (! (-f $newnslog && -f $prev_newnslog)) {
		&log_debug_message("ERROR: Files for time calculation are invalid, skipping calculation\n", SYSLOG, NOTICE);
		return 0;
	}

	&log_debug_message("Calculating time difference between $newnslog and $prev_newnslog\n", SYSLOG, NOTICE);
	my $delta = stat($newnslog)->mtime - stat($prev_newnslog)->mtime;

	if ($delta <= 0) {
		# We could devise a scheme to just skip these and sample the next valid pair, but that doesnt benefit the overall goal
		# of reducing collector size.
		&log_debug_message("ERROR: Filesystem timestamps appear modified, DISABLING time based log collection.\n", SYSLOG, NOTICE);
		$timestamps_sane = 0;
		return 0;
	}

	# Convert seconds into days
	my $delta_days = $delta/60/60/24;
	return $delta_days;
}

# Link the newnslog to the collector bundle
sub newnslog_add_to_collector() {

	my $newnslog = $_[0];
	my $collector = $_[1];
	my @newnslog_files = ();

	# If its a file, we can hardlink it directly
	if (-f $newnslog) {
		if (!link $newnslog, "$collector/$newnslog") {
			&log_debug_message("ERROR: Failed to link $newnslog to $collector/$newnslog: $!\n", SYSLOG, NOTICE);
			return 0;
		}
	}

	# If its a dir, mirror the path and hardlink the files inside
	if (-d $newnslog) {

		if (!mkdir "$collector/$newnslog/") {
			&log_debug_message("ERROR: Could not create $collector/$newnslog directory: $!\n", SYSLOG, NOTICE);
			return 0;
		}

		# TODO: avoided globs in other places, should either fix glob or discontinue use throughout.
		@newnslog_files = glob "$newnslog/newnslog*";

		foreach my $file (@newnslog_files) {
			if (!link "$file", "$collector/$file") {
				&log_debug_message("ERROR: Failed to link $newnslog/$file to $collector/$file: $!\n", SYSLOG, NOTICE);
				return 0;
			}
		}
	}
	return 1;
}

# The function collects the backed up ZebOS cluster config files
sub copy_zebos_cfg_files() {

	if ($cluster_instance == 0) {
		return;
	}

	my $zebos_dir = "var/";

	if (-d "/$zebos_dir" )
	{
		&log_debug_message("Copying ZebOSconf files ....\n");
		`cp -p /$zebos_dir/ZebOScfg.conf* $collector_abs_path/nsconfig 2>/dev/null`;
	}
}

# Main entry point for newnslog collection
sub copy_newnslog_files() {

	my $collector = $collector_abs_path;
	my $newnslog_path = "/var/nslog";

	# Convert mB to bytes
	my $newnslog_max_files_size = 500 * 1000000;
	my $newnslog_max_days = 7;
	my $newnslog_max_files = 6;

	my $newnslog_cur_files_size = 0;
	my $newnslog_cur_days = 0;

	# Check for abbreviated or callhome cases and reduce max files
	if ($is_abbr_version || $is_callhome_version) {
		$newnslog_max_files = 1;
	}

	&log_debug_message("Copying newnslog files ....\n");

	my @newnslog_file_list = &newnslog_create_file_list($newnslog_path, $newnslog_max_files);

	my $i = 0;
	foreach my $newnslog (@newnslog_file_list) {
		# Check the size, and time limits. Max files is handled at list creation time
		if ( ($newnslog_cur_files_size <= $newnslog_max_files_size) && ($newnslog_cur_days <= $newnslog_max_days) ) {

			$newnslog_cur_files_size += &newnslog_get_size("$newnslog");

			if ($i && $timestamps_sane) {
				$newnslog_cur_days += &newnslog_get_time_diff($newnslog_file_list[$i - 1], $newnslog);
			}

			if (! &newnslog_add_to_collector($newnslog, $collector)) {
				&log_debug_message("ERROR: Failed to add $newnslog to $collector\n", SYSLOG, NOTICE);
				next;
			}

			$i++;
		}
	}

	&log_debug_message("Newnslog collection stats - Total files: $i - " . "Total size: " . ($newnslog_cur_files_size/1000000) . 
	"mBytes - Total days: $newnslog_cur_days" . "\n", SYSLOG, NOTICE);

}

###########################################################################
# This function copies 5 files with pattern (passed as arg3), modified within 
# the last one week
#     arg1 - dir from which the files need to be looked at
#     arg2 - dir to which the files need to be copied to
#     arg3 - pattern that needs to be matched 
###########################################################################

sub copy_core_files()
{
	my @filelist;
	my $file;
	my $GAP_IN_SECONDS = $NUM_PREV_DAYS_FILES * 24 * 3600;

	# In case of abbreviated and callhome versions, we dont need to collect.
	if ($is_abbr_version || $is_callhome_version) {
		return;
	}

	# If hard disk is not mounted, skip this step
	if ($hd_device eq "") {
		&log_debug_message("\t...Skipping collecting $_[0] files as hard drive is not mounted!\n");
		return;
	}

	chdir($_[0]);

	# There are two ways to get the last few modified files in the last
	# one week. 
	# 1. To get the entire file list using glob and sort based on the 
	#    modified time. Then pick the last files if they are within the
	#    last 5 days
	# 2. A simpler way is to use ls -ltr command itself and pick the last
	#    5 files or just the "find" command with -mtime option
	#    The second option is more simple to implement, although it
	#    is running a shell command in Perl.
	# Option 1 is implemented here.

	&log_debug_message("Copying core files from $_[0] .... \n");
	#@filelist = <$_[2] [1-100]/$_[2]>; # This is not working for some reason, the range is not "from-to"
	@filelist = <$_[2] */$_[2]>;
	# Sort the existing files based on the modification time
	@filelist = sort {(stat($a))[9] <=> (stat($b))[9]} @filelist;

	# Create a dummy file and insert at the end. This is needed for comparision of modification times
	`touch xx`;
	my $xx_mtime = (stat("xx"))[9]; #Save the file xx's modification time
	@filelist = (@filelist, "xx"); #Insert xx at the end, needed for finding the last modified files

=begin comment
	# This is for debugging only - to print all files sorted
	foreach $file (@filelist) {
		my $difftime = ($xx_mtime - (stat($file))[9]);
		#&log_debug_message("%-25s diff=%-10ld 7daysGap=%-10ld",$file,$difftime,$GAP_IN_SECONDS);
		if ($difftime > $GAP_IN_SECONDS) {
			#&log_debug_message("  MORE THAN A WEEK OLD!\n");
		}
		else {
			#&log_debug_message("  WITHIN A WEEK OLD!\n");
		}
	}
=end comment
=cut

	# If the file "bounds" exists, just collect it anyway (by doing symlink)
	if (-f "bounds") {
		symlink("$_[0]/bounds","$_[1]/bounds") ||
				&log_debug_message("Error in creating a symbolic link for bounds!\n");
	}

	my $count = 0; #count of files that we have collected
	my $num_files_in_dir = scalar(@filelist);
	my $index = $num_files_in_dir - 2; #'xx is at the end, and index starts at 0

	# Traverse the file list backwards and get 5 files - the list is already sorted by last modified time
	# $filelist[0] has the earliest modified file and $filelist[$num_files_in_dir-1] has the
	# latest modified file (which should be "xx")

	while ($count < $NUM_FILES_TO_COLLECT && $index >= 0) {
		##log_debug_message "============= count=%d index=%d File/dir = %s\n",$count,$index,$filelist[$index];
		$_ = $filelist[$index];
		# In nCore systems, there will be a file called bounds. Ignore this file while traversing
		# NSPPE-* files are PE core files that should not be at the top level. Just in case they
		# are there, ignore.
		if (/bounds/ || /^NSPPE-/ || /^nscac64p-/) {
			$index--;
			next; #don't increment $count here
		}

		# Look at only files and ignore directories
		if (-f $filelist[$index]) {
			my $file_mtime = (stat($filelist[$index]))[9];
			my $diff_mtime = $xx_mtime - $file_mtime;
			##log_debug_message "File=%s... mtime=%lu xx_mtime=%lu diff=%ld\n",$_,$file_mtime,$xx_mtime,$diff_mtime;
			# If the difference is less than 0 (negative), then date change must have happened on
			# this system. This file was created earlier and now the date has changed to an earlier date
			if ($diff_mtime < 0) {
				&log_debug_message("\tWarning! The file $_ has a date later than the current system date!\n");
			}
			elsif ($diff_mtime > $GAP_IN_SECONDS) {
				unlink("xx");
				##log_debug_message "Last sorted file reached.. breaking!\n";
				last;	#break out of the loop, as there won't be any more files within the last week
			}
			# Else, include this file. 
			##log_debug_message "...... Including this file... %s\n",$filelist[$index];

			# If this file/dir name has a path, split and get the basedir and the file name separately
			# Although File::Basename package is there with routines, this may be simpler in this context
			(my $dir, my $file) = split(/\//); # this works on $_, which is $filelist[$index]
			##log_debug_message "dir = %s   filename=%s\n",$dir,$file;

			# If after splitting, if $dir has a value and the $file is empty, then this is just a plain file
			# without any directory name in it.
			if ($dir ne "" && $file eq "") {
				symlink("$_[0]/$filelist[$index]","$_[1]/$filelist[$index]") ||
					&log_debug_message("Error in creating a symbolic link for $filelist[$index]!\n");
			}
			else {
				# If this is a NSPPE- core file, just warn and continue.. don't collect it
				# We are assuming that all these NSPPE- cores are within this subdirectories [1-100]
				if ($file =~ /^NSPPE-/) {
					&log_debug_message("\tNSPPE core ($_) file present. Skipping this file because of size restrictions ....\n");
					$index--;
					next;
				}
				#if this is a nscac64p- core file, just warn and continue.. do'nt collect it
				if ($file =~ /^nscac64p-/) {
					&log_debug_message("\tnscac64p core ($_) file present. Skipping this file because of size restrictions ....\n");
					$index--;
					next;
				}
				# If the directory does not exist, create that dir
				if (! -d "$_[1]/$dir") {
					##log_debug_message "%s does not exist! symlink %s, %s \n",$dir,$_[0],$_[1];
					mkdir("$_[1]/$dir") || &log_debug_message("Error in creating the directory $_[1]/$dir\n");
				}
				# Now create a symbolic link for the file
				symlink("$_[0]/$_","$_[1]/$_") ||
						&log_debug_message("Error in creating a symbolic link for %s under $dir,$_[1]\n");
			}
			$count++;
		}
		$index--;
	}
	unlink("xx");

	# Done finding out the last modified files! Present the info.
	if ($count == 0) {
		&log_debug_message("\t.... Nothing to copy .... No files created within the last one week.\n", SYSLOG, NOTICE);
	}
	else {
		&log_debug_message("\t.... copied $count file(/s) from this directory.\n", SYSLOG, NOTICE);
	}
}

###########################################################################################
# This function collects WebInterace files into the archive. All the configuration files
# of WI are present under /var/wi/tomcat/webapps. Users create sites using the command "add wi site"
# and the corresponding files related to the site will be under /var/wi/tomcat/webapps/<site name>.
# We only collect WebInterface.conf* files
##########################################################################################

sub copy_wi_files()
{
	&log_debug_message("Copying WebInterface configuration files (if WI is installed) ....\n", SYSLOG, NOTICE);

	# The test for the installation of WebInterface feature is done by checking the presence of the
	# directory /var/wi/tomcat/webapps

	if (! -d "/var/wi/tomcat/webapps") {
		&log_debug_message("\t.... WI is not installed on this system. Nothing to copy.\n", SYSLOG, NOTICE);
		return;
	}

	my @conf_files = `find /var/wi/tomcat/webapps -name WebInterface.conf\* -type f`;
	foreach my $file_name (@conf_files) {
		chomp($file_name);
		my $file_path = dirname($file_name);
		if (!-d "$collector_abs_path/$file_path") {
			mkpath("$collector_abs_path/$file_path") || return "Unable to create $collector_abs_path/$file_path";
		}
		`cp -p $file_name $collector_abs_path/$file_path 2>/dev/null`;
	}

}

###########################################################################################
# This function collects partition files into the archive. All the configuration files
# of admin partition is present under /var/partition_fs/. 
##########################################################################################

sub copy_admin_partition_files()
{
	if (! -d "/$partition_dir") {
		&log_debug_message("Admin partition file system not found. Nothing to copy.\n");
		return;
	}
	foreach my $num_id (0..$#admin_partition_arr) {
		if($admin_partition_arr[$num_id]->Flag) {
			my $Name = scalar ($admin_partition_arr[$num_id]->Name);
			mkdir("$collector_abs_path/$partition_dir/$Name");
			chdir "/$partition_dir/$Name";
			`cp -p -r /$partition_dir/$Name/netscaler $collector_abs_path/$partition_dir/$Name/`;
			`cp -p -r /$partition_dir/$Name/log $collector_abs_path/$partition_dir/$Name/`;
			`cp -p -r /$partition_dir/$Name/download $collector_abs_path/$partition_dir/$Name/`;
		}
	}
	return;
}


###########################################################################
# This function creates an archive of the collected files
###########################################################################

sub prepare_tar_archive()
{
	my $partial_tar_file_str = "$collector_abs_path/cluster_showtech_node_failure.log";

	# If the hard drive is not present, create a README file warning this condition.
	if ($hd_device eq "") {
		my $tmp_file = "$collector_abs_path/README.no_hard_disk";
		open (FILE,"> $tmp_file") || &log_debug_message("Error in creating $tmp_file!!\n\n");
		print FILE "\nWarning! This techsupport archive was created when hard drive was not mounted in NetScaler.\n";
		print FILE "The newnslog files and core files were **NOT** collected as a result of this.\n";
		print FILE "The log files from /var/log were collected, but they come from MFS, not from the hard disk.\n";
		print FILE "Other relevant artifacts were collected.\n";
		print FILE "It's possible that the features may be disabled.\n\n";
		close(FILE);
	}
	
	if( $is_cluster_version == 1 ) {
		if (-e $partial_tar_file_str) {
			if(open (my $partial_tar_file, '<:encoding(UTF-8)',$partial_tar_file_str)) 
			{
				my $failednode_count=0;
				while(my $line = <$partial_tar_file>) { 
					chomp $line;	

					if (index($line, "Failed") != -1) {
    						$failednode_count++;
					}
					last if $. == 2;
				}
				close $partial_tar_file;
				&log_debug_message("Warning: The cluster super tar archive contains showtechsupport data from subset of nodes: Total Nodes:$num_nodes, Failed Nodes:$failednode_count\n",
                );
		
				if( $num_nodes == $failednode_count ){
					&log_debug_message("Warning: show techsupport data collection has failed on all nodes. Cluster super tar archive will not be generated\n");	
					exit(-1);
				}
			}else {
				&log_debug_message("Warning: The cluster super tar archive could not be opened\n");
			}
		} else {
			#&log_debug_message("Warning : cluster showtech node failure log file: $partial_tar_file_str is not present\n");
		}
	}

	chdir $support_dir || die "Could not chdir to $support_dir!!\n";
	&log_debug_message("Creating archive ....\n");

	if ($is_cluster_version == 1) {
		# With cluster version, we want to retain the sym links at the top level (so no -h option)
		`tar -czvf  $collector_base_dir.tar.gz $collector_base_dir 2>/dev/null`;
	}
	else {
		# With regular version, we want to follow the sym links (so use -h option)
		`tar -chzvf  $collector_base_dir.tar.gz $collector_base_dir 2>/dev/null`;
	}

	my $old_support_archive = "$support_dir/support.tgz";
	#Unlink the file $old_support_archive, whether it is regular file or just symbolic link.
	if (-f $old_support_archive || -l $old_support_archive) {
		unlink($old_support_archive) || die "Could not unlink $old_support_archive";
	}

	if (symlink("$support_dir/$collector_base_dir.tar.gz",$old_support_archive)) {
		&log_debug_message("Created a symbolic link for the archive with $old_support_archive\n", SYSLOG, NOTICE);
		&log_debug_message("$old_support_archive  ---- points to ---> $support_dir/$collector_base_dir.tar.gz\n\n");
	}

	# Create a symlink for the latest archive with the name called "latest". This is useful when many archives
	# are present, and "latest" points to the latest one.
	unlink("$support_dir/latest");
	symlink("$support_dir/$collector_base_dir","latest");

	# In the case of hard drive not being present, the archive is created in /flash/support.
	# Just create a symlink for the created archive in /var/tmp/support also
	if ($hd_device eq "") {
		unlink("$hd_support_dir/support.tgz");
		symlink("$support_dir/$collector_base_dir.tar.gz","$hd_support_dir/$collector_base_dir.tar.gz");
		symlink("$support_dir/$collector_base_dir.tar.gz","$hd_support_dir/support.tgz");
	}

	rmtree("$collector_abs_path",0,1);
}

########################################################################
# Helper function to get the current NetScaler version
##############################################################

sub get_ns_version()
{
	my $line;

	# Get the info about ncore or classic and save it for later use
	$ncore = `sysctl -n netscaler.nCore`;
	chomp($ncore);

	my @show_version = `nscli -U %%:nsroot:. show version 2>&1`;
	foreach $line (@show_version) {
		$_ = $line;
		if (/NS/) {
			#Sample output format: "        NetScaler NS10.0: Build 10.004.cl, Date: Jul 10 2010, 16:24:57"
			chomp($_);
			($ns_version) = (split /NS/)[1];
			($ns_version) = (split(/:/,$ns_version))[0];
			&log_debug_message("NetScaler version $ns_version\n");
			return;
		}
	}
}

##########################################################
# Helper function to get the mounted flash and hard drives
##########################################################

sub get_hard_disk_device()
{
	# Get the hard disk device from df -k output
	$hd_device = `df -k | grep var`;
	# The output of the above shows like this:
	#     /dev/ad2s1e    69490770 3872132 60059378     6%    /var
	chomp($hd_device);

	# Check first if hard disk is mounted! If for whatever reason the hard disk is not
	# mounted, we need to alter what we collect
	if ($hd_device eq "") {
		&log_debug_message("\tWARNING!! HARD DISK IS NOT MOUNTED!!!\n");
		&log_debug_message("\tOnly relevant data will be collected!!\n");
	}
	else {
		$hd_device =~ s/ .*//; #Remove the output after the first blank (after ad2s1e)
		$hd_device =~ s/^\/dev\///; #Remove the "/dev/" string
		$hd_device =~ s/s[1-9].*//; #Remove the slice part of the device (s1e)
		#&log_debug_message("Hard disk = $hd_device\n");
	}

	# Get the flash disk device from df -k output
	$flash_device = `df -k | grep flash`;
	# The output of the above shows like this:
	#     /dev/ad0s1a      231774 194792 18442    91%    /flash
	chomp($flash_device);
	$flash_device =~ s/ .*//; #Remove the output after the first blank (after ad0s1a)
	$flash_device =~ s/^\/dev\///; #Remove the "/dev/" string
	$flash_device =~ s/s[1-9].*//; #Remove the slice part of the device (s1e)
	#&log_debug_message("Flash disk = $flash_device\n");

}

########################################################################
# Helper function to log the log_debug_messages onto console and into log files using syslog
########################################################################

# Can be called with args:
# "message"
# "message", CONSOLE
# "message", SYSLOG, LOGLEVEL
# Uses constants (located with globals in top of file)
sub log_debug_message() {

	my $numargs = scalar(@_);
	my $log_msg = $_[0];

	# Default case, syslog and console
	if ($numargs == 1) {

		print $log_msg;
		syslog NOTICE,"%s",$log_msg;

		return;
	}

	my $log_target = $_[1];
	if ($log_target == CONSOLE) {

		print "$log_msg";

	}

	elsif ($log_target == SYSLOG) {

		my $log_level = $_[2];
		syslog $log_level,"%s",$log_msg;

	}
}

########################################################################################
# API to record the show tech collection failures on the nodes to a log file 
# This is invoked in cluster context only by the child processes.
# Argument 1: showtech failure log file location name 
# Argument 2: ip address of the node 
# Argument 3: Node id
#######################################################################################

sub record_showtech_failure_to_file()
{
	my $partial_tar_file_str = $_[0];
	my $node_ip	     	 = $_[1];
	my $node_id	     	 = $_[2];
	if( open (my $partial_tar_file, '>>', $partial_tar_file_str)) {
		if (flock ($partial_tar_file, LOCK_EX)) {
			print $partial_tar_file "Node $node_ip  ID:$node_id] Failed\n";
		}else {
			&log_debug_message("Cluster showtech node failure log file couldn't be locked\n");
		}
		close $partial_tar_file;
	}else {
		&log_debug_message("Cluster showtech node failure log file couldn't be opened\n");
	}
}


########################################################################################
# API to invoke script on local/remote machine and to copy the files to target directory.
# This is invoked in the cluster context only.
# Argument 1: Whether the child process should run script locally or remotely.
#				1 ==> Invoke locally,  0 ==> Invoke remotely
# Argument 2: If script needs to be executed remotely, then remote ip address is passed.
# Argument 3: The node id of this specific node (to which the IP is associated)
#######################################################################################

sub execute_child()
{
	my $partial_tar_file_str = "$collector_abs_path/cluster_showtech_node_failure.log";

	my $command = "";
	my $line;
	my $file_name = "";
	my $local_remote_str = ($_[0] == 1) ? "Local " : "Remote";

	if ($_[0] == 1) {
		#Local node, invoke script.
		$command = "/netscaler/showtechsupport.pl -scope abbr";
	} else {
		$command = "/netscaler/nsremotexec $_[1] -exec \"shell /netscaler/showtechsupport.pl -scope abbr\"";
	}

	&log_debug_message("Running showtechsupport on node $_[1] ($local_remote_str : Node Id=$_[2]) ....\n");
	my @command_output = `$command 2>&1`;

	# print nsremotexec failure (error) messages, if any..
	if ($?) {
		&log_debug_message(join("\n",@command_output));
		&log_debug_message("the command that failed is $command\n");
		&log_debug_message("Running showtechsupport on node $_[1]...failed\n");
		&record_showtech_failure_to_file($partial_tar_file_str,$_[1], $_[2]);
		exit(-1);
	}

	foreach $line (@command_output) {
		if ($line =~ /---- points to ---> / ) {
			$file_name = (split /---- points to ---> /, $line)[1];
			chomp($file_name);
			my $node_str = "n".$_[2];
			my $base_name = basename($file_name);

			if ($_[0] == 1) {  #Local node
				`cp $file_name $collector_abs_path/$base_name`;
				&log_debug_message("Running showtechsupport on node $_[1]...success\n");
				    
				# Create a symbolic link for the invidiual node with n0,n1,n2 etc(based on node id)
				symlink("$base_name","$collector_abs_path/$node_str.tar.gz");
				$base_name =~ s/.tar.gz//; #Strip .tar.gz from the name
				symlink("$base_name","$collector_abs_path/$node_str");
				exit(0);
			} 
			else {   #Remote node
				$command = "/netscaler/nsremotexec $_[1] -get $file_name $collector_abs_path";
				my @cp_command_output = `$command 2>&1`;
				if ($?) {
					&log_debug_message("Error in copying remote $file_name:" . join("\n",@cp_command_output) . "\n");
					&record_showtech_failure_to_file($partial_tar_file_str,$_[1],$_[2]);
					last;
				} 
				else {
					&log_debug_message("Running showtechsupport on node $_[1]...success\n");
					# Create a symbolic link for the invidiual node with n0,n1,n2 etc(based on node id)
					symlink("$base_name","$collector_abs_path/$node_str.tar.gz");
					$base_name =~ s/.tar.gz//; #Strip .tar.gz from the name
					symlink("$base_name","$collector_abs_path/$node_str");
					exit(0);
				}
			}
		}
	}
	&log_debug_message("Running showtechsupport on node $_[1]...failed\n");
	&record_showtech_failure_to_file($partial_tar_file_str,$_[1],$_[2]);
	exit(-1);
}

####################################################################
# API to run create child processes to execute script and copy output files on all cluster nodes.
###################################################################

sub run_across_cluster_nodes()
{
	my $line_no;
	my $ip = "";
	my $childpid = 0;
	my $node_id = -1;

	my @show_node_output = `nscli -U %%:nsroot:. show cluster node 2>&1`;
	my $show_node_output_sz = scalar(@show_node_output);
	# Parse the "show cluster node" output
	for ($line_no=0; $line_no < $show_node_output_sz; $line_no++) {
		$_ = $show_node_output[$line_no];
		my $local_node = 0;

		# The "Node Id:" line should come first in the output
		if (/Node ID:/) {
			chomp($_);
			($node_id) = (split /Node ID:/)[1];	#Use : as the delimiter
			($node_id) =~ s/\(\w+\)//;      #Remove paren, any alpha numeric char in between the paren
			($node_id) =~ s/^\s+//;		#Remove trailing whitespaces
			($node_id) =~ s/\s+$//;		#Remove ending whitespaces

			# the warning below shows up only for multi-node cluster
			if(++$num_nodes == 2) {
				&log_debug_message("Warning: Authenticity of peer cluster node(s) *may* not have been established, CCO will connect anyways!\n");
			}
		}

		# Get the IP address from the output
		if (/IP:/) {
			chomp($_);
			($ip) = (split /IP:/)[1];	#Use : as the delimiter
			$ip =~ s/^\s+//;		#Remove trailing whitespaces
			$ip =~ s/\s+\(.+\)//;	#Remove in between whitespaces and the (<hostname>) string
			$ip =~ s/\s+$//;		#Remove ending whitespaces
			#The current node has * in the output
			if (index($ip, '*') != -1) {
				$local_node = 1;
				$ip = (split /\*/, $ip)[0];
			}

			if ($show_node_output[++$line_no] =~ /Configuration Coordinator/ ) {
				`echo $ip > $collector_abs_path/cco.txt`;
			}
			if (!defined($childpid = fork())) {
				die "Unable to fork. Exiting.\n";
			} elsif ($childpid == 0) {
				&execute_child($local_node, $ip, $node_id);
			}
		}
	}
	#Wait for all the child processes to exit
	while (wait() != -1) {}
}

######################################################################################
# This function copies all imported object files and mapping files from download dir
######################################################################################

sub copy_download_dir_files()
{
	chdir $collector_abs_path;
                
	my $download_dir = "var/download"; #Download dir under collector dir.

	# The download dir (var/download) was created in prepare_support_dir() and hence should exist.

	# Define the sets of files to be copied here.
	# First column is the set of files to be copied
	# Second column is the destination folder under $collector_abs_path

	my %files_to_be_copied = (

		# Imported Files in dirs under /var/download
		"/var/download/htmlerrorurl/",          "$download_dir/htmlerrorurl",
		"/var/download/xmlerrorurl/",           "$download_dir/xmlerrorurl",
		"/var/download/xmlschema/",             "$download_dir/xmlschema",
		"/var/download/wsdl/",                  "$download_dir/wsdl",
		"/var/download/custom/",                "$download_dir/custom",

		# Mapping Files under /var/download
		"/var/download/mapping-htmlerrorpage", "$download_dir",
		"/var/download/mapping-xmlerrorpage",  "$download_dir",
		"/var/download/mapping-xmlschema",     "$download_dir",
		"/var/download/mapping-wsdl",          "$download_dir",
		"/var/download/mapping-custom",        "$download_dir"

	);
	&log_debug_message("Copying imported files and mapping files ....\n", SYSLOG, NOTICE);
	foreach my $fileset (keys(%files_to_be_copied))
	{
		`cp -R -p $fileset $collector_abs_path/$files_to_be_copied{$fileset} 2>/dev/null`;
	}
}

# Copy all GSLB location db files
sub copy_gslb_loc_db_files()
{

	my $locdb_dir = "var/netscaler/locdb";

	if ( -d "/$locdb_dir" ) {
		&log_debug_message("Copying GSLB location database files ....\n");
		`cp -R -p /$locdb_dir $collector_abs_path/$locdb_dir 2>/dev/null`;
	}
}

# Copy all GSLB Auto Sync Log Files
sub copy_gslb_auto_sync_log_files()
{

	my $gslb_dir = "var/netscaler/gslb";

	if ( -d "/$gslb_dir" ) {
		&log_debug_message("Copying GSLB auto sync log files ....\n");
		`cp -R -p /$gslb_dir $collector_abs_path/$gslb_dir 2>/dev/null`;
	}

}

# Copy all Thales HSM related files
sub copy_thales_hsm_files()
{

	my $thales_hsm_log_dir = "var/opt/nfast/log";

	if ( -d "/$thales_hsm_log_dir" ) {
		&log_debug_message("Copying Thales HSM log files ....\n", SYSLOG, NOTICE);
		`cp -R -p /$thales_hsm_log_dir $collector_abs_path/$thales_hsm_log_dir 2>/dev/null`;
	}
}

# Copy /var/nsinstall/installns_state file
sub copy_nsinstall_files()
{
	my $nsinstall_dir = "var/nsinstall";

	if ( -d "/$nsinstall_dir" ) {
		&log_debug_message("Copying nsinstall/installns_state file ....\n", SYSLOG, NOTICE);
		`cp -p /$nsinstall_dir/installns_state $collector_abs_path/$nsinstall_dir 2>/dev/null`;
	}
}

# Copy all Safenet Gateway Log files
sub copy_safenet_gw_log_files()
{
	my $safenet_log_dir = "var/nslog";

	if ( -d "/$safenet_log_dir" ) {
		&log_debug_message("Copying Safenet Gateway log files ....\n");
		`cp -p /$safenet_log_dir/sfnt_gw.log $collector_abs_path/$safenet_log_dir 2>/dev/null`;
		`cp -p /$safenet_log_dir/sfnt_gw0.log $collector_abs_path/$safenet_log_dir 2>/dev/null`;
		`cp -p /$safenet_log_dir/sfnt_gw1.log $collector_abs_path/$safenet_log_dir 2>/dev/null`;
		`cp -p /etc/Chrystoki.conf $collector_abs_path/nsconfig 2>/dev/null`;
	}
}

######################################################################################
# This fn. Collects metadata server info and saves it in <collectordir>/var/VPX_AWS dir.
# It also purges access keys and secret keys.
######################################################################################

sub support_vpxonaws()
{
	my $vpxonaws_dir = "var/VPX_AWS"; #VPX on AWS (Amazon Web Services) dir 
					  #under collector dir.

	my $sysid_VPX_AWS = "450040";  # for VPX on AWS device

	my $sysid = `sysctl -n netscaler.sysid`;

	chomp($sysid);

	if (!($ncore && ($sysid eq $sysid_VPX_AWS))) {
		# It is a non AWS system and hence we don't need to continue further.
		return;
	}

	# If /flash/nsconfig/.AWS dir exists, then create VPX_AWS dir and collect metadata.
	if (-d "/flash/nsconfig/.AWS") {
		mkdir("$collector_abs_path/$vpxonaws_dir") || die "Unable to create $collector_abs_path/$vpxonaws_dir!\n\n";
	} else {
		# It is either a non AWS system or .AWS dir does not exist.
		# Hence we don't need to continue further.
		return;
	}

	# Collect static metadata
	&log_debug_message("Collecting static metadata for VPX on AWS ....\n");
	`cp -R -p /flash/nsconfig/.AWS/ $collector_abs_path/$vpxonaws_dir/.AWS`;

	# Define the sets of files to be removed here. 
	# Files listed are under $collector_abs_path/$vpxonaws_dir.

	chdir "$collector_abs_path/$vpxonaws_dir";
	`rm ssh/authorized_keys .AWS/authorized_keys .AWS/user_data 2>/dev/null`;

	# Collect dynamic metadata
	&log_debug_message("Collecting dynamic metadata for VPX on AWS ....\n");
	&get_dynamic_metadata();
}

sub get_dynamic_metadata()
{
	#@_ represents NULL at start and represents each of the metadata dirs.

	my $vpxonaws_dir = "var/VPX_AWS"; #VPX on AWS (Amazon Web Services) dir 
					  #under collector dir.

	my @aws_url_metadata = `GET $aws_url/@_`;

	foreach my $metadata (@aws_url_metadata) {

		chomp($metadata);
		# Create the dirs
		if ($metadata =~ /\/$/) {
			#Skip copying public keys
			if ($metadata eq "public-keys/"){
		        	next;
			}
			mkdir("$collector_abs_path/$vpxonaws_dir/@_/$metadata") || die "Unable to create $collector_abs_path/$vpxonaws_dir/@_/$metadata!\n\n";
		}
		else {
			# Not a metadata dir. So collect the metadata file.
			`GET $aws_url/@_/$metadata/ > $collector_abs_path/$vpxonaws_dir/@_/$metadata 2>/dev/null`;
		}

		if (! -f "$collector_abs_path/$vpxonaws_dir/@_/$metadata") {
			# This is called here recursively to collect dynamic
			# metadata upto several levels deep.
			&get_dynamic_metadata("@_/$metadata");
		}
	}
}

#########################################################################################
# This function copies all waagent dirs and its contents present under /var and /var/log
#########################################################################################

# Copy /var/waagent and /var/log/waagent dirs.

sub copy_waagent_dirs()
{
	# Waagent directories under var
	my $wdir = "var/waagent";
	my $wlogdir = "var/log/waagent";

	if ( -d "/$wdir" ) {

		&log_debug_message("Copying waagent dirs ....\n");
		`cp -R -p /$wdir $collector_abs_path/var`;

	} 

	if ( -d "/$wlogdir" ) {

		mkdir("$collector_abs_path/$wlogdir") ||
			die "Unable to create $collector_abs_path/$wlogdir!!\n\n";

		`cp -R -p /$wlogdir/*.log $collector_abs_path/$wlogdir`;
		`cp -R -p /$wlogdir/*.log.0 $collector_abs_path/$wlogdir`;

		if ( -f "/$wlogdir/nextfile" ) {
			my $last = `cat /$wlogdir/nextfile`;
			$last--;
			`cp -R -p /$wlogdir/*.log.$last $collector_abs_path/$wlogdir`;
		}

	}
}

#########################################################################################
# This function checks whether this NetScaler is GSLB site node. 
#########################################################################################

sub check_gslb_node()
{
	my $show_gslb_site_output = `nscli -U %%:nsroot:. show gslb site|egrep -iv "done|warning" 2>&1`;
	if( $show_gslb_site_output)
	{
		$is_gslb_node = 1;
	}
	return;
}
