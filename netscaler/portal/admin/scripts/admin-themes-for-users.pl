use strict;
use NetScaler::Portal::Config;
use vars qw (%c);

local *c = \%NetScaler::Portal::Config::c;

if ($ENV{REQUEST_METHOD} eq "GET"){
  opendir THISDIR, $c{theme_dir}  or die "serious dainbramage: $!";
  my @allfiles = grep !/^\.\.?$/, grep !/CVS/, grep !/customizable/,  grep !/default/,grep !/nav.css/, grep !/.cvsignore/, grep !/user.themes/, readdir THISDIR;
  closedir THISDIR;

  open (FILE, $c{theme_dir} . "/user.themes" );
  my @selected=<FILE>;
  close FILE;
  
  
  my $themes = { };
  foreach my $theme (@allfiles){
  	my $t = {};
  	$t->{'name'} = $theme; 
  	$themes->{$theme} = $t;
  }
  
  foreach my $sel (@selected){
     chomp $sel;
     if (exists $themes->{$sel}){
       $themes->{$sel}->{'status'} = "selected";
     }
  }
  my $vars;
  $vars->{'themes'} = $themes;	
  print "Content-type: text/html\n\n"; 
  my $tmplfile = 'admin-themes-for-users.html';
  my $template = Template->new({INCLUDE_PATH => $c{adminTemplate_dir}.":". $c{template_dir} });
  $template->process($tmplfile, $vars) || die "Template process failed: ", $template->error(), "\n";
} else {  # POST request
  my $cgi = new CGI;
  my $output = "";
  foreach my $theme ($cgi->param('theme')){
    $output .= $theme . "\n";
  }
  my $filename = $c{theme_dir} . "user.themes";
  open(FILEWRITE, "> $filename") || die "can't open $filename $!";
  print FILEWRITE $output;
  close FILEWRITE;
  print "Location: $ENV{adminLoc}admin.pl?\n\n";
}