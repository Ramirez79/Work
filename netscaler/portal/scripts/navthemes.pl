#!/usr/bin/perl
use strict;
use CSS::Tiny;
use NetScaler::Portal::Config;
use Data::Dumper;
use vars qw(%c);

local *c = \%NetScaler::Portal::Config::c;

my $user = NetScaler::Portal::UserPrefs->new();
my $doc = $user->csd();

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

my $vars;
my $themes = { };
if ((-e $c{theme_dir} . "/user.themes")  && (-s $c{theme_dir} . "/user.themes")) {
  open (FILE, $c{theme_dir} . "/user.themes" );
  my @allfiles=<FILE>;
  close FILE;
  
  foreach my $theme (@allfiles){
      chomp $theme;
    if (-e "$c{theme_dir}$theme"){
    	my $t = {};
    	my $x = CSS::Tiny->read( $c{theme_dir} . '/' . $theme . '/styles.css' );
    	foreach my $field (keys %{$fields}){
    		my $modname = $field;
    		$modname =~ s/-/_/;
    		$t->{$modname} = $x->{$fields->{$field}[0]}->{$fields->{$field}[1]};
    	}
    
    	$t->{'name'} = $theme; 
    	$themes->{$theme} = $t;
    }
  #	print Dumper($x);
  }
  $vars->{'themes'} = $themes;
  $themes->{'user'} = $doc->{'style'};
} else {
  $vars->{'message'} = "There are no optional themes available.  <br>The SSL VPN administrator has not configured any optional themes.";
}

$vars->{'style'} = $doc->{'style'};
$vars->{'themes'} = $themes;	

print "Content-type: text/html\n\n";

my $tmplfile = 'navthemes.html';
my $template = Template->new({INCLUDE_PATH => $c{template_dir} });
$template->process($tmplfile, $vars) || die "Template process failed: ", $template->error(), "\n";
