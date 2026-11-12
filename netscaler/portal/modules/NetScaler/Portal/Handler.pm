package NetScaler::Portal::Handler;

use Apache2::Const qw(:common);
use NetScaler::Portal::UserPrefs;
use CGI;
use Template;
use Apache::Session::File;
use NetScaler::Portal::Config;

sub handler {
  my $r = shift;
  
  if ($r->path_info() eq "/error.html"){
    errorpage($r->args());
  }
  $r->no_cache(1);
  my $user = NetScaler::Portal::UserPrefs->new();
  my $doc = $user->csd();


# Process Template
  my $tmplfile = $r->path_info();
  $tmplfile =~ s[^/][];
  my $template = Template->new({INCLUDE_PATH =>  NetScaler::Portal::Config::c->{template_dir},CACHE_SIZE => 64, COMPILE_DIR=> NetScaler::Portal::Config::c->{template_compile_dir}, COMPILE_EXT => '.ttc2'});
  if ($tmplfile =~/.*\.css$/){
  	$r->send_http_header('text/css');
  } else {
  	$r->send_http_header('text/html');
  }
  
  $template->process($tmplfile, $doc) || do {
    my $error = $template->error();
    my $lcError = lc($error);
    if ( $error->type() eq "file" && $lcError  =~ /^file error/ && $lcError =~ /.not found$/ ) {
      return NOT_FOUND;
    }
    print NetScaler::Portal::UserPrefs::html_escape_string($error), "\n";
  };

# finish
  return OK;
}

sub errorpage {
  my $msg = shift;
  my $doc = {};
  $doc->{msg} = "";#$msg;
  print "Content-type: text/html\n\n";
  my $tmplfile = "error.html";
  my $template = Template->new({INCLUDE_PATH =>  NetScaler::Portal::Config::c->{template_dir}});
  $template->process($tmplfile, $doc)	|| die "Template process failed: ", $template->error(), "\n";
  Apache::exit();
}

1;
