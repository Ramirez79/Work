#!/usr/local/bin/perl
#
# FILE:   $Id: //depot/main/rs_111/usr.src/netscaler/scripts/callhome_xml_registration.pl#1 $
# LAST CHECKIN:   $Author: build $
#                 $Date: 2016/06/13 $

use strict;
use warnings;
use XML::Writer;
use MIME::Base64;
use Sys::Syslog;

# This script generates XML data required for CallHome registration (analytics).
# The XML data has basic configuration/runtime related information + the entire
# ns.conf in a gzipped and base64 encoded format. This script is invoked from
# collector_upload.pl.

my $syslog_facility = "LOG_LOCAL3";
my $SYS_LOG = 1;
my $syslog_ident = "callhome_xml_registration.pl: ";
my $syslog_opt = "ndelay,pid,nofatal";
my $syslog_level = "LOG_DEBUG";

my @values = "";
my $line = "";
my $xml_data = "";
my $latest_ns_conf = "/nsconfig/ns.conf";
my @ns_conf_lines = "";

my $xmlNS = "http://www.w3.org/2001/XMLSchema-instance";
my $schema_location = "https://uploads.citrix.com/static/xsd/v1/ns_callhome_registration.xsd";
my $nspace = "http://www.citrix.com/callhome/netscaler";

my $writer = XML::Writer->new(
                  OUTPUT => $xml_data,
                  ENCODING => 'utf-8',
                  DATA_MODE => 1,
                  DATA_INDENT => 4,
                  NAMESPACES => 1,
                  PREFIX_MAP => {$nspace => 'ns', $xmlNS => 'xsi'});

# MAIN ENTRY POINT. Call main() that does the whole thing

&main();

# Main function that collects data step by step through separate functions
# and populates the XML file
sub main()
{
   # Open /var/log/callhomedebug.log
   openlog($syslog_ident, $syslog_opt, $syslog_facility);

   logMessage("\nExecuting $0\n");

   # Read the latest ns.conf to be parsed later by other subroutines
   @ns_conf_lines = `cat $latest_ns_conf`;

   $writer->xmlDecl("UTF-8");

   $writer->startTag([$nspace, "record"], [$xmlNS, "schemaLocation"], "$schema_location");

   # Release and build information
   &get_release_and_build();

   # License Type
   &get_license_type();

   # Hypervisor information
   &check_hypervisor();

   # Platform information
   &get_platform_details();

   # List of enabled features and modes
   &get_features_and_modes();

   # Determine the deployment type (HA, Cluster, etc)
   &get_deployment();

   # Get the latest ns.conf file
   &get_nsconf();

   $writer->endTag([$nspace, "record"]);

   $writer->end();

   return $xml_data;
}

# Populate the Release and build information
sub get_release_and_build()
{
   $writer->comment("Release and Build");
   $writer->startTag([$nspace, "release"]);

   # Sample sysctl output: "NetScaler NS11.0: Build 34.9.nc, Date: Feb 15 2015, 13:42:36"
   my $version_string = `sysctl -n netscaler.version`;
   chomp($version_string);

   # Extract the version (example: 11.0)
   my $ns_version = (split(/NS/, $version_string))[1];
   @values = split(/:/, $ns_version);
   $writer->dataElement([$nspace, "version"], $values[0]);

   # Extract the build (example: 34.9 or 34.9.e) and type (example: nc)
   $ns_version = (split(/Build /, $version_string))[1];
   my $build = (split(/[,]/, $ns_version))[0];
   @values = split(/[.]/, $build);
   my $type = pop(@values);
   $writer->dataElement([$nspace, "build"], join('.',@values));   
   $writer->dataElement([$nspace, "type"], $type);

   $writer->endTag([$nspace, "release"]);
}

# Populate the license information
sub get_license_type()
{
   $writer->comment("License Type");
   my @show_license = `nscli -U %%:nsroot:. show license 2>&1`;
   foreach $line (reverse @show_license) {
      $_ = $line;
      if (/License Type/) {
         # Sample output format: "License Type: Platinum License"
         # Extract the keyword "Platinum"
         chomp($_);
         @values = (split /: /);
         $writer->dataElement([$nspace, "license"], (split(/ /, $values[1]))[0]);
      }
   }
}

# Determine the hypervisor information (if any)
sub check_hypervisor()
{
   # The values representing the different hypervisors are predefined
   # in usr.src/sys/sys/systm.h
   my @hypervisors = ("None", "Xen", "ESX", "HyperV", "KVM", "AWS");

   $writer->comment("Hypervisor");
   my $virtual = `sysctl -n netscaler.hypervised_ns`;
   chomp($virtual);
   $writer->dataElement([$nspace, "hypervisor"], $hypervisors[$virtual]);
}

# Get the sysid and platform information
sub get_platform_details()
{
   $writer->comment("Platform");
   $writer->startTag([$nspace, "platform"]);

   # sysid from a sysctl variable
   my $sysid = `sysctl -n netscaler.sysid`;
   chomp($sysid);
   $writer->dataElement([$nspace, "sysid"], $sysid);

   # Platform description also from a sysctl
   my $hardware = `sysctl -n netscaler.descr`;
   chomp($hardware);
   $writer->dataElement([$nspace, "description"], $hardware);

   $writer->endTag([$nspace, "platform"]);

   # Determine the platform type (SDX or MPX or VPX)
   $writer->comment("Platform-Type");

   my $type = "";
   my $virtual = `sysctl -n netscaler.hypervised_ns`;
   chomp($virtual);
   my $sdxvpx = `sysctl -n netscaler.sdxvpx`;
   chomp($sdxvpx);

   if ($virtual > 0) {
      if ($sdxvpx == 1) {
         # VPX on SDX
         $type = "SDX";
      } else {
         # Standalone VPX (VPX on Xen, ESX, ...)
         $type = "VPX";
      }
   } else {
      # MPX
      $type = "MPX";
   }

   $writer->dataElement([$nspace, "platform_type"], $type);
}

# Get the list of enabled features and modes
sub get_features_and_modes()
{
   # List of enabled features. As there is going to be only one "enable ns
   # feature" line in ns.conf, we can safely assume there is going to be only
   # one element in the matched array
   $writer->comment("Features");
   my @features = grep { /enable ns feature/i } @ns_conf_lines;
   chomp($features[0]);
   $writer->dataElement([$nspace, "features"], (split(/feature /, $features[0]))[1]);

   # List of enabled modes
   $writer->comment("Modes");
   my @modes = grep { /enable ns mode/i } @ns_conf_lines;
   chomp($modes[0]);
   $writer->dataElement([$nspace, "mode"], (split(/mode /, $modes[0]))[1]);
}

# Determine the deployment mode (HA primary, secondary, etc)
sub get_deployment()
{
   my $deployment;

   $writer->comment("Deployment");

   my @cluster = grep { /add cluster instance/i } @ns_conf_lines;
   if (scalar @cluster == 0) {
      # Not in a cluster. Check if part of a HA setup
      my @ha = grep { /add ha node/i } @ns_conf_lines;
      if (scalar @ha == 0) {
         # Standalone node
         $deployment = "Standalone Primary";
      } else {
         # Part of HA. Check whether Primary or Secondary
         @values = `nscli -U %%:nsroot:. show ha node 2>&1`;
         foreach $line (@values) {
            $_ = $line;
            if (/Master State/) {
               chomp($_);
               $deployment = 'HA ' . (split /: /)[1];
               last;
            }
         }
      }
   } else {
      # Cluster configuration present
      $deployment = "Cluster Node";
   }

   $writer->dataElement([$nspace, "deployment"], $deployment);
}

# Copy the base64 encoded ns.conf
sub get_nsconf()
{
   my $scrub_ns_conf = "/var/tmp/ns.conf.callhome.scrub";
   my $ns_conf_zipped = "/var/tmp/ns.conf.callhome.scrub.gz";

   $writer->comment("ns.conf");

   # Copy the latest ns.conf file
   `cp $latest_ns_conf $scrub_ns_conf`;

   # Filter sensitive information from ns.conf
   # This logic is from fltr_all_sensitive_info() in showtechsupport.pl
   my $replStrng = "XXXX";
   my $tmp_file = "$scrub_ns_conf.$$";
   open my $in_file, '<', $scrub_ns_conf;
	open my $out_file, '>', $tmp_file;
	while( <$in_file> ){
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
	`mv $tmp_file $scrub_ns_conf`;

   # gzip the scrubbed ns.conf file
   `gzip -c $scrub_ns_conf > $ns_conf_zipped`;
   unless (-e $ns_conf_zipped) {
      logMessage("\nCould not create compressed configuration file\n");
      $writer->dataElement([$nspace, "ns_conf"], " ", 'encoding' => 'none');
      return;
   }

   # Encode it to base64 and add it as a XML element
   $writer->startTag([$nspace, "ns_conf"], 'encoding' => 'gzip,base64');
   my $encoded = `perl -MMIME::Base64 -0777 -ne 'print encode_base64(\$_)' < $ns_conf_zipped`;
   $writer->characters("\n");
   $writer->characters($encoded);
   $writer->endTag([$nspace, "ns_conf"]);

   # Delete the temporary files
   unlink("$ns_conf_zipped");
   unlink("$scrub_ns_conf");
}

# Routine to log errors/warnings to /var/log/callhomedebug.log. These errors/warnings are
# non fatal as it is ok to proceed with registration even if all the required data cannot
# be collected
sub logMessage()
{
	my $msg = shift();
	if (@_) {
		$msg = $msg . shift();
	}

	if ($SYS_LOG) {
		syslog $syslog_level,"%s",$msg;
	}
}

