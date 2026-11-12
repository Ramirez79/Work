use strict;
use NetScaler::Portal::Config;
use vars qw (%c);

local *c = \%NetScaler::Portal::Config::c;



#read all usernames from bookmark directory
#look for files *.xml
opendir THISDIR, $c{bookmark_dir} or die "serious dainbramage: $!";
my @allfiles = grep !/^\.\.?$/, grep !/CVS/, grep /.*.xml/, readdir THISDIR;
closedir THISDIR;

#build doc for template
my $doc = ();
#create users array
$doc->{users} = [];
foreach my $user (@allfiles){
  #strip .xml from filename for plain username
  $user =~ s/\.xml//;
  #add username to array
  push @{$doc->{users}}, $user;
}

#process template
print "Content-type: text/html\n\n";
my $tmplfile = 'admin-users.html';
my $template = Template->new({INCLUDE_PATH => $c{adminTemplate_dir}.":". $c{template_dir} });
$template->process($tmplfile, $doc) || die "Template process failed: ", $template->error(), "\n";

 

