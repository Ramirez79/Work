use strict;
use NetScaler::Portal::UserPrefs;
use NetScaler::Portal::Config;
use vars qw (%c);

my $cgi = new CGI;
my $user = NetScaler::Portal::UserPrefs->new();
my $doc = $user->csd();

#set the style choice
  my $style = $cgi->param('name') || "";
  if ($style eq "reset"){
    delete $doc->{'style'};
  } else {
    $doc->{style} =  {
                  	     theme => $cgi->param('name'),
                     } ;
  }

#write the file
$user->filewrite($doc);

local *c = \%NetScaler::Portal::Config::c;
print "Location: " . $c{homepage} . "\n\n";	