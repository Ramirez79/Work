use strict;
use Template;
use CGI;
use Apache::Session::File;
use NetScaler::Portal::Config;
use vars qw (%c);


# The tips messages are moved to resources

my $user = NetScaler::Portal::UserPrefs->new();
my $doc = $user->csd();


my $tipNum = $user->getsessionvalue('tipNum') || 0;
my $tips = 23; # the number of the tips messages
$tipNum++;
$tipNum = ($tipNum < $tips ? $tipNum : 0);
$user->sessionupdate('tipNum', $tipNum);


my $tmplfile = 'tips.html';
local *c = \%NetScaler::Portal::Config::c;
my $template = Template->new({INCLUDE_PATH =>  $c{template_dir} , CACHE_SIZE => 64, COMPILE_DIR=> $c{template_compile_dir}, COMPILE_EXT => '.ttc2'});

my $vars = $doc;
$vars->{'tip'} = '<span id=tip_' . $tipNum . '></span>';


print "Content-type: text/html\n\n";
$template->process($tmplfile, $vars) || die "Template process failed: ", $template->error(), "\n";
