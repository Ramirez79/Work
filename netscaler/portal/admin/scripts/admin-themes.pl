use strict;
#use CSS::Tiny;
use NetScaler::Portal::Config;
use NetScaler::Portal::SitePrefs;
use vars qw (%c);

local *c = \%NetScaler::Portal::Config::c;


if ($ENV{REQUEST_METHOD} eq "GET"){
  opendir THISDIR, $c{theme_dir}  or die "serious dainbramage: $!";
  my @allfiles = grep !/^\.\.?$/, grep !/^default$/, grep !/CVS/, grep !/customizable/, grep !/nav.css/, grep !/.cvsignore/, grep !/user.themes/, readdir THISDIR;
  closedir THISDIR;
  my $themes = { };
  foreach my $theme (@allfiles){
  	my $t = {};
  	$t->{'name'} = $theme; 
  	$themes->{$theme} = $t;
  }

##which is currently default1!!?!?!
  my $default =readlink ($c{theme_dir} . "/default");
  $default =~ s/.*[\/](\w+)\/*$/$1/;
  $themes->{$default}->{'default'} = "default";
  $themes->{$default}->{name} = "$default";


##which ones are currently selected for users to use
  open (FILE, $c{theme_dir} . "/user.themes" );
  my @selected=<FILE>;
  close FILE;
  
  foreach my $sel (@selected){
     chomp $sel;
     if (exists $themes->{$sel}){
       $themes->{$sel}->{'status'} = "selected";
     }
  }

  my $vars;
  $vars->{'themes'} = $themes;	
#print "Content-type: text/plain\n\n";
#use Data::Dumper;
#print Dumper($vars);
  print "Content-type: text/html\n\n"; 
  my $tmplfile = 'admin-themes.html';
  my $template = Template->new({INCLUDE_PATH => $c{adminTemplate_dir}.":". $c{template_dir} });
  $template->process($tmplfile, $vars) || die "Template process failed: ", $template->error(), "\n";
} elsif ($ENV{REQUEST_METHOD} eq "POST") {  # POST request
  my $cgi = new CGI;

    my $nt = $cgi->param('theme');
    if ($nt =~ /(\w+)/){
      $nt = $1;
      $nt =~ s|\./||g;
    }
    my $default = "default";
    chdir($c{theme_dir}) || die "Cannot chdir ($!)";
    unlink ($default);
    symlink ($nt, $default);
    print "Location: $ENV{adminLoc}admin.pl\n\n";

}
 

