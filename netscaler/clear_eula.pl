#!/usr/bin/perl

use XML::Simple;

my $eulapath = "/var/netscaler/logon/themes/EULA/resources";

my $xml2 = new XML::Simple(RootName => "Resources", XMLDecl => '<?xml version="1.0" encoding="utf-8"?>',KeyAttr => { Partition => 'id' });
open STDOUT, '>>', '/var/log/portaltheme.log' or warn "EULA_LOG: Failed to open eula log file";
open(STDERR, ">&STDOUT") or warn "EULA_LOG: Failed to log errors";

my $eula = $ARGV[1];
my $command = $ARGV[0]; #command=0 for clear conf and command=1 for remove eula
my $files = 0;  #no of files edited

for my $xfile (glob "$eulapath/*.xml")  {  #loop over all lang files in EULA folder except config.xml and remove eula string
			if ($xfile =~ /config/)
				{next;}
			$files++;		
			my $xml  = XML::Simple->new(KeyAttr => { Partition => 'id' });
			my $file = eval{ $xml->XMLin($xfile, ForceArray=> 1); } ;
			if($@) {
				print scalar(localtime());  
				print ":EULA_LOG: An error occurred while parsing $xfile : $@ \n";
				die;
				}
					if($command == 1){
							for (my $count = 0; $count < @{$file->{Partition}->{custom_strings}->{String}}; $count++){
								my $eula_del= @{$file->{Partition}->{custom_strings}->{String}}[$count];
							
								if( $eula_del->{id} eq $eula){
									splice @{$file->{Partition}->{custom_strings}->{String}}, $count, 1;
									last;
								}
							}
					
					}
					else{
						splice @{$file->{Partition}->{custom_strings}->{String}}, 0, @{$file->{Partition}->{custom_strings}->{String}};
						}
		my $new_eula = eval{ $xml2->XMLout($file, OutputFile => "$xfile"); };
		if($@) {
			print scalar(localtime());
			print ":EULA_LOG: An error occurred while writing to $xfile : $@ \n";
			}	
		
	}
if ($files != 5){
	print scalar(localtime());
	print ":EULA_LOG: Changes to  EULA for all languages couldnt be made.The filesystem for EULA folder may be corrupt\n "
}
