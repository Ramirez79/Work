#!/usr/bin/perl
use lib '/netscaler/portal/modules';
use XML::Simple;

my $varpath = "/var/netscaler";
my $EULA_path = "$varpath" . "/logon/themes/EULA/resources/";

open STDOUT, '>>', '/var/log/portaltheme.log' or warn "EULA_UPGRADE_LOG: Failed to open eula log file";
open(STDERR, ">&STDOUT") or warn "EULA_UPGRADE_LOG: Failed to log errors";

#Raw format for EULA xml file.Need to change only here if any node is added in future.
my $raw = <<EOF;
<Resources>
	<Partition id="custom_strings">
	</Partition>
	<Partition id="domain">
	</Partition>
</Resources>
EOF
		# create new XML object
		my $xml2 = new XML::Simple(RootName => "Resources", XMLDecl => '<?xml version="1.0" encoding="utf-8"?>',KeyAttr => { Partition => 'id' });
		if ( -f "$EULA_path/en.xml") 
		{
		#EULA present, have to take backup 
			my $eulapath = '$EULA_path';   

			for my $xfile (glob "$EULA_path/*.xml") {
				if ($xfile =~ /config/)
					{next;}
				
				my $xml  = XML::Simple->new(KeyAttr => { Partition => 'id' });
				my $file = eval{ $xml->XMLin($xfile, ForceArray=> 1); };
				if ($@){
					print scalar(localtime());
					print ":EULA_UPGRADE_LOG: An error occurred while parsing $xfile : $@ \n";
					exit 1;
				}
			my $eula;
			$eula = $file->{Partition}->{custom_strings};  
			
		# read XML file
			my $data2 = eval{ $xml2->XMLin($raw, ForceArray => 1); };
			if ($@){
				print scalar(localtime());
				print ":EULA_UPGRADE_LOG: An error occurred while trying to parse the raw format for EULA : $@ \n";
				exit 1;
			} 
			$data2->{Partition}->{custom_strings} = $eula ; #Merge EULA content
			my $edited_eula = eval{ $xml2->XMLout($data2, OutputFile => "$xfile"); };
			if ($@){
				print scalar(localtime());
				print ":EULA_UPGRADE_LOG: An error occurred while trying to write the merged EULA file $xfile : $@ \n";
				exit 1;
				}
			}
		exit 0;
		}
		else{ #create EULA template for each language
			my $data2 = eval{ $xml2->XMLin($raw, ForceArray => 1); };
			if ($@){
				print scalar(localtime());
				print ":EULA_UPGRADE_LOG: An error occurred while trying to parse the raw format for EULA : $@ \n";
				exit 1;
			} 
			my @lang = ("de.xml","en.xml","es.xml","fr.xml","ja.xml");
		  	foreach $xfile (@lang){
				my $actFile = "$EULA_path/" . $xfile;
				my $new_eula = eval{ $xml2->XMLout($data2, OutputFile => "$actFile"); };
				if ($@){
					print scalar(localtime());
					print ":EULA_UPGRADE_LOG: An error occurred while trying to create the fresh EULA file $xfile : $@ \n";
					exit 1;
				}
		  	}	
			exit 0;
		}
