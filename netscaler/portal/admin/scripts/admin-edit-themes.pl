use strict;
use NetScaler::Portal::Config;
use vars qw (%c);

local *c = \%NetScaler::Portal::Config::c;

if ($ENV{REQUEST_METHOD} eq "GET"){
  my $cgi = new CGI;
   
  my $themes = { };
  foreach my $theme ($cgi->param('theme')){
  	my $t = {};
  	$t->{'name'} = $theme; 
  	$themes->{$theme} = $t;
  }
  open (FILE, $c{theme_dir} . "/user.themes" );
  my @selected=<FILE>;
  close FILE;
  
  foreach my $sel (@selected){
     chomp $sel;
     if (exists $themes->{$sel}){
       $themes->{$sel}->{'status'} = "selected";
     }
  }
  ##which is currently default1!!?!?!
  my $default =readlink ($c{theme_dir} . "/default");
  $default =~ s/.*[\/](\w+)\/*$/$1/;
  if (exists $themes->{$default}){
    $themes->{$default}->{'default'} = "default";
  }
  my $vars;
  $vars->{'themes'} = $themes;	
  $vars->{'default'} = $default;
  
  print "Content-type: text/html\n\n"; 
  my $tmplfile = 'admin-themes-to-edit.html';
  my $template = Template->new({INCLUDE_PATH => $c{adminTemplate_dir}.":". $c{template_dir} });
  $template->process($tmplfile, $vars) || die "Template process failed: ", $template->error(), "\n";
} 
if ($ENV{REQUEST_METHOD} eq "POST"){
  my $cgi = new CGI;
  my $nt = $cgi->param('theme');
  my $filename = $c{theme_dir} . "user.themes";

  ##default  
  if($cgi->param('default')){
    
    if ($nt =~ /(\w+)/){
      $nt = $1;
      $nt =~ s|\./||g;
    }
    my $default = "default";
    chdir($c{theme_dir}) || die "Cannot chdir ($!)";
    unlink ($default);
    symlink ($nt, $default);
  }
  ##enabled
  open (FILE, $filename);
  my @selected=<FILE>;
  close FILE;

  my $now_enabled = ($cgi->param('enabled') eq "enabled");
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
          $output .= $theme . "\n";
        }
      }
    }
    #writing new list
    open(FILEWRITE, "> $filename") || die "can't open $filename $!";
    print FILEWRITE $output;
    close FILEWRITE;
  } 
  ##finish
  print "Location: $ENV{adminLoc}admin-themes.pl?\n\n";

}