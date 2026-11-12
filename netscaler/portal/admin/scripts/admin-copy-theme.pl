use strict;
use NetScaler::Portal::Config;
use File::Copy;
use vars qw (%c);

local *c = \%NetScaler::Portal::Config::c;

if ($ENV{REQUEST_METHOD} eq "GET"){
  my $cgi = new CGI;
  my $theme = $cgi->param('theme');
  my $newTheme = $cgi->param('new');
  if ($newTheme =~ /(\w+)/){
    $newTheme =~ s/\s/-/g;
    $newTheme = $1;
    chdir($c{theme_dir}) || die "Cannot chdir: ($!)";
    my $out = `cp -r $theme $newTheme`;
    print "Location: $ENV{adminLoc}admin-themes.pl?\n\n";
  } else {
    print "Location: $ENV{portalLoc}error.html\n\n";
  }
}