#!/usr/bin/perl
use Data::Dumper;
use NetScaler::Portal::UserPrefs;
use NetScaler::Portal::Config;

opendir THISDIR,  NetScaler::Portal::Config::c->{bookmark_dir} or die "serious dainbramage: $!";
@allfiles = grep !/^\.\.?$/, grep !/CVS/, grep !/.*\.xml?$/, readdir THISDIR;
closedir THISDIR;

foreach $file (@allfiles){
    print "converting to xml format: " . $file . " \n";
	$username = $file;
	$doc = NetScaler::Portal::UserPrefs->fileread($username, 1);
	
	$path = NetScaler::Portal::Config::c->{bookmark_dir} . $username;
	open(SOURCE, "< $path")
	    or die "Couldn't open $path for reading: $!\n";
	$hooey = {};    
	while (<SOURCE>) {
		$x = {};
		chomp;
	    split /=/;
	    my $key = @_[0];
		/bm(.*)\[(.*)\]/;
		my $itm = $1;
		my $num = $2;
	    my $val = @_[1];
	    $val =~ s/"(.*)"/$1/;
	    $hooey->{$num}->{$itm}=$val;
	}    
	close(SOURCE);
	
	foreach $key (keys %{$hooey}){
		my $newBM = {  url => $hooey->{$key}->{'uri'},
					 title => $hooey->{$key}->{'prompt'},
					 descr => '',
		};
		if ($newBM->{url} =~ /^\\/){
			push @{$doc->{filesystems}->{filesystem}}, $newBM;
		} else { # bookmark
			push @{$doc->{bookmarks}->{bookmark}}, $newBM;
		}
	}
	
	my $x = NetScaler::Portal::UserPrefs->filewrite($username, $doc);
}