use strict;
use NetScaler::Portal::Config;
use NetScaler::Portal::UserPrefs;
use CSS::Tiny;

use vars qw (%c);

local *c = \%NetScaler::Portal::Config::c;

if ($ENV{REQUEST_METHOD} eq "GET"){
  my $cgi = new CGI;
  my $theme = $cgi->param('theme');
  my $vars = ();
  $vars->{theme} = $theme;

    #read theme values
    #populate form
    my $fields;
    $fields->{'block-border'}     = ['.block', 'border-color'];
    $fields->{'block-background'} = ['.block', 'background-color'];
    $fields->{'h2-color'}         = ['.block h2', 'color'];
    $fields->{'h2-background'}    = ['.block h2', 'background-color'];
    $fields->{'h3-color'}         = ['.block h3', 'color'];
    $fields->{'h3-background'}    = ['.block h3', 'background-color'];
    $fields->{'h3-background'}    = ['.block h3', 'background-color'];
    $fields->{'font-family'}      = ['body', 'font-family'];
    $fields->{'font-size'}        = ['body', 'font-size'];
    $fields->{'header-background'}= ['#header', 'background'];
    $fields->{'footer-background'}= ['#footer', 'background'];
    $fields->{'footer-color'}     = ['#footer', 'color'];

    my $t = {};
    my $x = CSS::Tiny->read( $c{theme_dir} . '/' . $theme . '/styles.css' );
   
	foreach my $field (keys %{$fields}){
		my $modname = $field;
		$modname =~ s/-/_/;
		$t->{$modname} = $x->{$fields->{$field}[0]}->{$fields->{$field}[1]};
	}


	$vars->{style} = $t;  
  
  print "Content-type: text/html\n\n"; 
  my $tmplfile = 'admin-colorpicker.html';
  my $template = Template->new({INCLUDE_PATH => $c{adminTemplate_dir}.":". $c{template_dir} });
  $template->process($tmplfile, $vars) || die "Template process failed: ", $template->error(), "\n";
  
}else{ #POST REQUEST
  my $cgi = new CGI;
  my $style = ();
  my @parms = $cgi->param;
  foreach my $parm (sort @parms){
    if ($parm ne "submit" && $parm ne "theme"){
      my $xparm = $parm;
      $xparm =~ s/^pr//;
      my @vars = split '-', $xparm;
      $style->{$vars[0]}->{$vars[1]} = $cgi->param($parm);
    }
  }
  my $tmplfile =  'customizable/styles.css';
  
  if (! -e $c{theme_dir} . "/$tmplfile"){
    system ('cp -r /netscaler/portal/themes/customizable /var/vpn/themes/');
  }
  
  my $tmplfile =  'customizable/styles.css';
  
  my $template = Template->new({INCLUDE_PATH => $c{theme_dir} });
  my $output = "";
  my $theme = $cgi->param('theme');
  if ($theme =~ /(\w+)/){
    $theme = $1;
    $theme =~ s|\./||g;
  }
  my $dest = $c{theme_dir} . "$theme/styles.css";
  my $x = CSS::Tiny->read( $dest );
  my $logo = $x->{'#logo'};
  
  $template->process($tmplfile, $style, \$output) || die "Template process failed: ", $template->error(), "\n";
  open(FILEWRITE, "> $dest") || die "can't open $dest $!";
  print FILEWRITE $output;
  close FILEWRITE;

  my $css = CSS::Tiny->read( $dest );
  $css->{'#logo'} = $logo;
  $css->write($dest);
  

  print "Location: $ENV{adminLoc}admin-logo.pl?theme=$theme\n\n";	
}