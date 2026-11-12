use strict;
use NetScaler::Portal::Config;
use NetScaler::Portal::SitePrefs;
use vars qw (%c);

local *c = \%NetScaler::Portal::Config::c;

if ($ENV{REQUEST_METHOD} eq "GET"){
  print "Content-type: text/html\n\n"; 
  my $vars;
  my $tmplfile = 'admin-new-theme.html';
  my $template = Template->new({INCLUDE_PATH => $c{adminTemplate_dir}.":". $c{template_dir} });
  $template->process($tmplfile, $vars) || die "Template process failed: ", $template->error(), "\n";
} elsif ($ENV{REQUEST_METHOD} eq "POST") {  # POST request
  my $cgi = new CGI;
    my $name = $cgi->param('name');
    if ($name =~ /(\w+)/){
      $name =~ s/\s/-/g;
      $name = $1;
      chdir($c{theme_dir}) || die "Cannot chdir ($!)";
      if (-e $name){
        print "Location: $ENV{adminLoc}admin-savecolorprefs.pl?theme=$name\n\n";
      } else {
        mkdir($name, 0755) || die "aargh! can't create dir $name ($!)" ;
      }
      print "Location: $ENV{adminLoc}admin-savecolorprefs.pl?theme=$name\n\n";
    } else {
      $name = "";
      print "Location: $ENV{portalLoc}error.html\n\n";
    }

}
 

