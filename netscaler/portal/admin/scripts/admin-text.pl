use strict;
use NetScaler::Portal::Config;
use NetScaler::Portal::SitePrefs;
use vars qw (%c);

local *c = \%NetScaler::Portal::Config::c;


my $x = NetScaler::Portal::SitePrefs->new();
$x->fileread();
#GET request, display form
if ($ENV{REQUEST_METHOD} eq "GET"){
  print "Content-type: text/html\n\n"; 
  my $labels = ();
  $labels->{footer} = "Footer";
  $labels->{titledefault}= "Default Title";
  $labels->{titlepostfix}= "Title Suffix";
  $labels->{titleprefix}= "Title Prefix";
  
  my $vars = ();
  $vars->{doc} = $x->{doc};
  $vars->{labels} = $labels;
  my $tmplfile = 'admin-text.html';
  my $template = Template->new({INCLUDE_PATH => $c{adminTemplate_dir}.":". $c{template_dir} });
  $template->process($tmplfile, $vars) || die "Template process failed: ", $template->error(), "\n";
} else {  
# POST request, save form details
  my $cgi = new CGI;
  my @parms = $cgi->param;
  #for each paramater
  foreach my $parm (sort @parms){
    #skipping the submit button
    if ($parm ne "select"){
      #get value
      my $value = $cgi->param($parm);
      #add value to SitePrefs
      $x->addparam($parm, $value);
    }
  }
  #write SitePrefs to filesystem
  $x->filewrite();
  #return to admin page
  print "Location: $ENV{adminLoc}admin.pl\n\n";
}
 

