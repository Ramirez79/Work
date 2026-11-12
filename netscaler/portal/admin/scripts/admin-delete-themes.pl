use File::Path;
use strict;
use NetScaler::Portal::Config;
use vars qw (%c);

local *c = \%NetScaler::Portal::Config::c;

if ($ENV{REQUEST_METHOD} eq "GET"){
  my $cgi = new CGI;
#  opendir THISDIR, $c{theme_dir}  or die "serious dainbramage: $!";
  my @allfiles = $cgi->param('theme');
#  ?= grep !/^\.\.?$/, grep !/CVS/, grep !/customizable/, grep !/nav.css/, grep !/default/,  grep !/.cvsignore/, grep !/user.themes/, readdir THISDIR;
#  closedir THISDIR;
#  
#  my $filename = $c{theme_dir} . "/user.themes";
#  open (FILE, "<$filename" ) || die "can't open $filename $!";
#  my @selected=<FILE>;
#  close FILE;
#  
  my $themes = { };
  foreach my $theme (@allfiles){
  	my $t = {};
  	$t->{'name'} = $theme; 
  	$themes->{$theme} = $t;
  }
  
#  foreach my $sel (@selected){
#     chomp $sel;
#     $themes->{$sel}->{'status'} = "selected";
#  }
#  
#  my $x = NetScaler::Portal::SitePrefs->new();
#  $x->fileread();
  my $default =readlink ($c{theme_dir} . "/default");
  $themes->{$default}->{'default'} = "default";

  my $vars;
  $vars->{'themes'} = $themes;	
#print "Content-type: text/plain\n\n";
#use Data::Dumper;
#print Dumper($vars);
  print "Content-type: text/html\n\n"; 
  my $tmplfile = 'admin-themes-to-delete.html';
  my $template = Template->new({INCLUDE_PATH => $c{adminTemplate_dir}.":". $c{template_dir} });
  $template->process($tmplfile, $vars) || die "Template process failed: ", $template->error(), "\n";
} else {  # POST request
  my $cgi = new CGI;
  foreach my $theme ($cgi->param('theme')){
    if ($theme =~ /(\w+)/){
     $theme = $1;
     $theme =~ s/\.\///g;
    } else {
#      print "Content-type: text/plain\n\n"; 
#      print "Error: bad data";
    }
   my $deldir = $c{theme_dir} . "$theme";
   chdir($c{theme_dir}) || die "Cannot chdir ($!)";
   rmtree ($theme) || die "Can't rmtree $deldir $!";
  }
  ##enabled
  my $filename = $c{theme_dir} . "user.themes";
  open (FILE, $filename);
  my @selected=<FILE>;
  close FILE;
  
  my $nt = $cgi->param('theme');
  my $now_enabled = 0;
  my $was_enabled = grep /^$nt$/, @selected;
 
  my $output;
  if ($now_enabled != $was_enabled){
    if ($now_enabled > $was_enabled){ #adding to list
      foreach my $theme (@selected){
        $output .= $theme;
      }
      $output .= $nt . "\n";
    }
    if ($now_enabled < $was_enabled){ #removing from list
      foreach my $theme (@selected){
        chomp $theme;
        if ($theme ne $nt){
          $output .= $theme;
        }
      }
    }
    #writing new list
    open(FILEWRITE, "> $filename") || die "can't open $filename $!";
    print FILEWRITE $output;
    close FILEWRITE;
  }
  print "Location: $ENV{adminLoc}admin-themes.pl?\n\n";
}
 

