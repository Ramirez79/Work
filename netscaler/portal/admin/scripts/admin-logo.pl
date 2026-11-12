use strict;
use CGI;
use CSS::Tiny;
use NetScaler::Portal::Config;
use Image::Size;
use vars qw (%c);

local *c = \%NetScaler::Portal::Config::c;

if ($ENV{REQUEST_METHOD} eq "GET"){
#  print "Content-type: text/plain\n\n"; 
  print "Content-type: text/html\n\n"; 

  my $vars = ();
  my $cgi = new CGI;
  my $theme = $cgi->param("theme");
  $vars->{name} = $theme;

  my $x = CSS::Tiny->read( $c{theme_dir} . '/' . $theme . '/styles.css' );
  my $tmp = $x->{'#logo'}->{'background-image'};
  if ($tmp){
     $tmp =~ s|url\((.*)\)|url\(\/vpns/themes/$theme\/$1\)|;
     $vars->{logo}->{image} = $tmp;
  }
  $vars->{logo}->{height} = $x->{'#logo'}->{'height'};
  $vars->{logo}->{width} = $x->{'#logo'}->{'width'};
#  print Dumper($vars);
#  print Dumper($x);  
  
  my $tmplfile = 'admin-logo.html';
  my $template = Template->new({INCLUDE_PATH => $c{adminTemplate_dir}.":". $c{template_dir} });
  $template->process($tmplfile, $vars) || die "Template process failed: ", $template->error(), "\n";
} else {  # POST request

  my $query = new CGI;

#  No longer setting POST_MAX here, edited CGI.pm instead
#  this didn't work anyway
#  $query::POST_MAX = 2048;
  
  my $filename = $query->param("logo");
  my $theme = $query->param("theme");
#  $theme =~ s/\.\///g;
  if ($theme =~ /(\w+)/){
    $theme = $1;
    $theme =~ s|\./||g;
  }
  my $upload_dir = $c{theme_dir} . "/$theme" ;
  $filename =~ s/.*[\/\\](.*)/$1/;
  my $upload_filehandle = $query->upload("logo");
  
  if (!$upload_filehandle) {
  	print "Location: $ENV{portalLoc}error.html?Logo upload not accepted. Invalid file or invalid size.\n\n";
  	exit;
  }
  open UPLOADFILE, ">$upload_dir/$filename";
  while ( <$upload_filehandle> ){
    print UPLOADFILE;
  }
  close UPLOADFILE;
  my $x;
  my $y;
  ($x, $y) = imgsize("$upload_dir/$filename");
  if (!$x || !$y){
  	print "Location: $ENV{portalLoc}error.html?The file you uploaded was not a valid image file.\n\n";
  	exit;
  }
  my $cssfilename =  $c{theme_dir} . $theme . '/styles.css' ;
  my $css = CSS::Tiny->read($cssfilename);
  $css->{'#logo'}->{'background-image'} = "url($filename)";
  $css->{'#logo'}->{'width'} = $x . "px";
  $css->{'#logo'}->{'height'} = $y . "px";
  $css->{'#loginlogo'}->{'background-image'} = "url($filename)";
  $css->{'#loginlogo'}->{'width'} = $x . "px";
  $css->{'#loginlogo'}->{'height'} = $y . "px";
  $css->write($cssfilename);

  print "Location: $ENV{adminLoc}admin.pl\n\n";
}