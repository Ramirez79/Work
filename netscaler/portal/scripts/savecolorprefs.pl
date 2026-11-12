use strict;
use NetScaler::Portal::UserPrefs;
use NetScaler::Portal::Config;
use vars qw (%c);

my $cgi = new CGI;

my $user = NetScaler::Portal::UserPrefs->new();
my $doc = $user->csd();

#set the style choice
my $newStyle = {
	theme => 'custom',
	
} ;

foreach my $p ($cgi->param('.parameters')){
	if ($p  ne 'submit'){
		my @vals = split '-', $p;
		$newStyle->{$vals[0]}->{$vals[1]} = $cgi->param($p);
	}
}
$doc->{style} =  $newStyle;

#write the file
$user->filewrite($doc);

local *c = \%NetScaler::Portal::Config::c;
print "Location: " . $c{homepage} . "\n\n";	