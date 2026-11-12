use strict;

use NetScaler::Portal::UserPrefs;
use NetScaler::Portal::Config;
use Encode;
use vars qw (%c);

my $cgi = new CGI;

my $user = NetScaler::Portal::UserPrefs->new();
my $doc = $user->csd();

#disallow get requests to make it difficult to launch XSRF attacks
if ($ENV{'REQUEST_METHOD'} ne 'POST') { 
 my $msg = "Access Denied";
 print "Location: " . $ENV{portalLoc} . "error.html?$msg\n\n";  
 exit;
}

my $newurl = Encode::decode('utf8', $cgi->param('url'));
my $newtitle = Encode::decode('utf8', $cgi->param('title'));
my $newdesc = Encode::decode('utf8', $cgi->param('desc'));
my $UI_inuse = Encode::decode('utf8', $cgi->param('UI_inuse'));

$newurl =~ s/&/%26/g;
$newurl =~ s/</%3C/g;
$newurl =~ s/>/%3E/g;
$newurl =~ s/"/%22/g;
$newurl =~ s/'/&apos;/g;

my $newBM = {   url => $newurl,
    title => $newtitle,
    descr => $newdesc,
    UI_inuse => $UI_inuse,
};

if (!$newBM->{url} || $newBM->{url} eq ""){
  my $msg = "You must provide a URL for your bookmark."; 
  print "Location: " . $ENV{portalLoc} . "error.html?$msg\n\n"; 
  exit; #this is overridden by Apache::Registry to use a mod_perl safe Apache::exit
}
if ($newBM->{url} !~ /[\\]+/) { # is not bookmark
  if ($newBM->{url} !~ /^[^:]+:\/\// || $newBM->{url} =~ /^javascript:/ || $newBM->{url} =~ /^data:/){
     $newBM->{url} = 'http://' . $newBM->{url};
  }
}

sub is_valid_uri {
  my @chars = split(//, @_[0]);
  my $ascii_val;
  foreach (@chars) {
    $ascii_val = ord($_);
    if ($ascii_val <= 31) {return 0;} #Control chars are disallowed
    if ($ascii_val == 127) {return 0;}
    if ($ascii_val == 32) {return 0;} #Whitespace is disallowed
    if ($ascii_val == 60 || $ascii_val == 62 || $ascii_val == 35 || $ascii_val == 34)
      {return 0;} #'<', '>', '#' and '"' are disallowed
  }
  return 1;
}

#if (!is_valid_uri($newBM->{url})) {
#  my $msg = "The bookmark contains invalid URI characters. Please enter a valid Web address.";
#  print "Location: " . $ENV{portalLoc} . "error.html?$msg\n\n";
#  exit;
#}
if ($newBM->{title} =~ /<script/i || $newBM->{url} =~ /<\/script/i) {
  my $msg = "The bookmark is using the <script> tag, which is not allowed. Please enter a valid name.";
  print "Location: " . $ENV{portalLoc} . "error.html?$msg\n\n";
  exit;
}
if ($newBM->{descr} =~ /<script/i || $newBM->{url} =~ /<\/script/i) {
  my $msg = "The bookmark is using the <script> tag, which is not allowed. Please enter a valid description.";
  print "Location: " . $ENV{portalLoc} . "error.html?$msg\n\n";
  exit;
}
if (!$newBM->{title} || $newBM->{title} eq ""){
  $newBM->{title} = $newBM->{url};
}
if (!$newBM->{descr}){
  $newBM->{descr} = "";
}

if ($newBM->{url} =~ /^\\/){
  push @{$doc->{filesystems}->{filesystem}}, $newBM;
} else { # bookmark
  push @{$doc->{bookmarks}->{bookmark}}, $newBM;
}
undef(@{$doc->{escbk}->{bookmark}});
undef(@{$doc->{escbk}->{filesystem}});
$user->filewrite($doc);

local *c = \%NetScaler::Portal::Config::c;
if ($newBM->{UI_inuse} eq "RfWeb"){
  print "Content-Type: text; charset=utf-8\r\n";
  print "X-Citrix-Application: Receiver for Web\r\n";
  print "\r\n";
  print "Bookmark Added.";
} else {
  print "Location: /vpns/navui/refresh.html\n\n";
}
