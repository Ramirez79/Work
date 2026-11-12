use strict;
use NetScaler::Portal::UserPrefs;
use NetScaler::Portal::Config;
use vars qw (%c);

my $cgi = new CGI;

my $userdoc = NetScaler::Portal::UserPrefs->new();
my $username = $cgi->param('user');

#disallow get requests to make it difficult to launch XSRF attacks
if ($ENV{'REQUEST_METHOD'} ne 'POST') { 	
 exit;
}

#disallow removing data if username is not provided
if (!$username || $username eq ""){
  exit;
}

$userdoc->initdoc($username);

