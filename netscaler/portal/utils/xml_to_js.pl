#!/usr/bin/perl
use Data::Dumper;
use NetScaler::Portal::UserPrefs;
use NetScaler::Portal::Config;

opendir THISDIR,  NetScaler::Portal::Config::c->{bookmark_dir} or die "serious dainbramage: $!";
@allfiles = grep !/^\.\.?$/, grep !/CVS/, grep /.*\.xml?$/, readdir THISDIR;
closedir THISDIR;

foreach $file (@allfiles){
    print "converting to js format: " . $file . " \n";
    $username = $file;
	$username =~ s/(.*)\.xml/$1/;
	$doc = NetScaler::Portal::UserPrefs->fileread($username);
    $count = 0;
    $output = "";
    foreach $bm (@{$doc->{'bookmarks'}->{'bookmark'}}){
      $output .= "bmprompt[$count]=\"$bm->{'title'}\"\n";
      $output .= "bmuri[$count]=\"$bm->{'url'}\"\n";
      $count++;
    }
    foreach $bm (@{$doc->{'filesystems'}->{'filesystem'}}){
      $output .= "bmprompt[$count]=\"$bm->{'title'}\"\n";
      $output .= "bmuri[$count]=\"$bm->{'url'}\"\n";
      $count++;
    }
print $username . "\n";    
print $output;
# the following should be uncommented in production
#	open(FILEWRITE, "> $file") || die "can't open $datafile $!";
#	print FILEWRITE $output;
#	close FILEWRITE;
}