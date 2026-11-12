use strict;
use NetScaler::Portal::Config;
use vars qw (%c);

local *c = \%NetScaler::Portal::Config::c;

if ($ENV{REQUEST_METHOD} eq "GET"){
  print "Content-type: text/html\n\n"; 
  my $vars = ();
#  $vars->{doc} = $x->{doc};
  my $tmplfile = 'admin-user-page.html';
  my $template = Template->new({INCLUDE_PATH => $c{adminTemplate_dir}.":". $c{template_dir}});
  $template->process($tmplfile, $vars) || die "Template process failed: ", $template->error(), "\n";
} 