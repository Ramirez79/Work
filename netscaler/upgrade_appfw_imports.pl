#!/usr/bin/perl

# FILE:         $Id: //depot/main/rs_111/usr.src/netscaler/scripts/upgrade_appfw_imports.pl#1 $
# LAST CHECKIN: $Author: build $
#               $DateTime: 2016/06/13 09:44:25 $
#
# Copyright 1998-2016 Citrix Systems, Inc. All rights reserved.
# This software and documentation contain valuable trade
# secrets and proprietary property belonging to Citrix Systems, Inc.
# None of this software and documentation may be copied,
# duplicated or disclosed without the express
# written permission of Citrix Systems, Inc.

use strict;
use warnings;

use File::Copy;
use MIME::Base64;
use Sys::Syslog;
use Sys::Syslog qw(:standard :macros LOG_INFO LOG_WARNING);

sub main()
{
    my $before_ppe_start = 0;

    openlog($0, "ndelay", "local0");

	my $fname = "/nsconfig/updated_signatures.xml";
    unlink($fname);
    syslog(LOG_INFO, "Removed $fname");

	$fname = "/var/tmp/_appfw_scan_xsl_files";	# cleanup scan xsl files created by rapi.appfw_get_scan_xsl_files()
    unlink($fname);
    syslog(LOG_INFO, "Removed $fname");

    syslog(LOG_INFO, "Upgrading imported AppFw files using script: $0");

    if ($#ARGV >= 0 && $ARGV[0] eq "-before_ppe_start") {
        $before_ppe_start = 1;
    }

    if (-d "/var/download/xsd") {
        system("/bin/mv -f /var/download/xsd /var/download/xmlschema/");
    }

    if ( -f "/var/download/mapping-schema") {
        system("/bin/mv -f /var/download/mapping-schema /var/download/mapping-xmlschema");
    }

    if ( -d "/var/download/xmlerrorurl") {
        process_reimport_objects("xmlerrorpage", $before_ppe_start);
    }

    if ( -d "/var/download/htmlerrorurl") {
        process_reimport_objects("htmlerrorpage", $before_ppe_start);
    }

    if ( -d "/var/download/custom") {
        process_reimport_objects("signatures", $before_ppe_start);
    }
}


sub process_reimport_objects($$)
{
    my ($object_type, $before_ppe_start) = @_;
    my ($object_dir, $full_dir_path);
    my $import_dir = "/var/tmp";

    # syslog(LOG_INFO, "before_ppe_start: $before_ppe_start");

    if ($object_type eq "signatures") {
        $object_dir="custom";
    }
    elsif ($object_type eq "htmlerrorpage") {
        $object_dir="htmlerrorurl";
    }
    elsif ($object_type eq "xmlerrorpage") {
        $object_dir="xmlerrorurl";
    }

    $full_dir_path = "/var/download/$object_dir";

    if (-d $full_dir_path) {
        opendir (DIR, $full_dir_path);

        while (my $cur_file_name = readdir(DIR)) {
            my ($remove_args, $import_args);

            if (-f "$full_dir_path/$cur_file_name") {
                my ($new_file_name);
                my ($new_file_name_in_url);
                my ($remove_args, $import_args);
                my ($remove_args_64, $import_args_64);
                my ($rc);

                $new_file_name = lc($cur_file_name);
		$new_file_name_in_url = $new_file_name;
		$new_file_name_in_url =~ s/ /%20/g;

                copy("$full_dir_path/$cur_file_name", "$import_dir/$new_file_name");

                $remove_args = '"' . $cur_file_name . '"';
                if ($before_ppe_start) {
                    $remove_args = "-before_ppe_start " . $remove_args;
                }
                $remove_args_64 = encode_base64($remove_args, "");
                $rc = system("/netscaler/remove $object_type $remove_args_64");
                $rc >>= 8;
                if ($rc != 0) {
                    syslog(LOG_ERR, "Remove of object $cur_file_name failed: status = $rc\n");
                    return;
                }
                $import_args = "\"local://$new_file_name_in_url\" \"$new_file_name\"";
                if ($before_ppe_start) {
                    $import_args .= " -before_ppe_start";        # Option must be at the end
                }
                $import_args_64 = encode_base64($import_args, "");
                $rc = system("/netscaler/import $object_type $import_args_64");
                $rc >>= 8;
                if ($rc != 0) {
                    syslog(LOG_WARNING, "Import of $new_file_name failed.  Status = $rc ");
                }
                # import seems to remove the file, so commenting this out for now
                #if (unlink("$import_dir/$new_file_name") != 1) {
                #    syslog(LOG_WARNING, "Removing file $import_dir/$new_file_name failed: $!")
                #}
            }
        }
    }
}



main();
