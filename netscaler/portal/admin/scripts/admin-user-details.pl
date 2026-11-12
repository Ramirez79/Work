use strict;
use NetScaler::Portal::Config;
use NetScaler::Portal::UserPrefs;
use vars qw (%c);

local *c = \%NetScaler::Portal::Config::c;

# Code shared by GET and POST requests
# Get UserPrefs document  
  my $cgi = new CGI;
  #get username from query string
  my $username = $cgi->param('user');
  if (!$username){
    #missing username, return to list of proper usernames
    # about:blank has been abused on IE and is associated with several
    # exploits, this blank code is safer
    #  "Location: about:blank\n\n";
    print "Content-type: text/html\n\n";
    print "<html><head><title></title></head><body></body></html>";
    return;
  }
  #de-taint username
  if ($username =~ /^([-\@\w.]+)$/){
   $username = $1;
  } else {
   #Malformed username, return to list of proper usernames
   print "Location: $ENV{adminLoc}admin-user-page.pl\n\n";
   return;
  }
  my $doc = ();
  my $datafile = $c{bookmark_dir} . $username . '.xml';
  if (-e $datafile ){
    #file exists, populate document
    $doc = NetScaler::Portal::UserPrefs->fileread($username);
  } else {
    #missing doc, return to list of proper usernames
    print "Location: $ENV{adminLoc}admin-users.pl\n\n";
    return;
  }

#GET request
if ($ENV{REQUEST_METHOD} eq "GET"){
  #load and process template 
  print "Content-type: text/html\n\n";
  my $vars = ();
  $vars->{user} = $doc;
  my $tmplfile = 'admin-user-details.html';
  my $template = Template->new({INCLUDE_PATH => $c{adminTemplate_dir}.":". $c{template_dir} });
  $template->process($tmplfile, $vars) || die "Template process failed: ", $template->error(), "\n";

# POST request
} elsif ($ENV{REQUEST_METHOD} eq "POST") {  
  my @parms = $cgi->param;
  # if reset checked, delete existing style preferences
  my $style = $cgi->param('style') || "";
  if ($style eq "reset"){
    delete $doc->{'style'};
  }
  #build arrays of bookmarks and filesystems 
  #with the data submitted from the form
  #edited or not
  my @bms;
  my @fss;
  foreach my $p (sort @parms){
    if ($p =~ /bm(\d+)(\w+)/){
      if ($cgi->param($p) ne ""){
        $bms[$1]->{$2} = $cgi->param($p);
      }
    }
    if ($p =~ /fs(\d+)(\w+)/){
     if ($cgi->param($p) ne ""){
      $fss[$1]->{$2} = $cgi->param($p);
     }
    }
  }

  #remove existing bookmarks and filesystems
  #from the user doc, they will be replaced
  #by what was submitted from the form
  delete $doc->{'bookmarks'};
  delete $doc->{'filesystems'};

  #add new bookmarks and filesystems to the 
  #user doc as long as "delete" checkbox
  #is empty
  my $count = 0;
  for (my $i = 0; $i < @bms; $i++){
    if($bms[$i] && !$cgi->param("bm".$i."del") && $bms[$i]->{url} ne ""){
      if ($bms[$i]->{url} !~ /^[^:]+:\/\//){
 		$bms[$i]->{url} = 'http://' . $bms[$i]->{url};
	  }
      $doc->{bookmarks}->{bookmark}[$count] = $bms[$i];
      $count++;
    }
  }
  $count = 0;
  for (my $i = 0; $i < @fss; $i++){
    if($fss[$i] && !$cgi->param("fs".$i."del")){
      $doc->{filesystems}->{filesystem}[$count] = $fss[$i];
      $count++;
    }
  }

  #write the doc
  my $x = NetScaler::Portal::UserPrefs->filewrite($doc);
  #return to admin list
print "Content-type: text/html\n\n";
print "<html>
 <head>
 <link type=\"text/css\" id=\"mainCSS\" rel=\"stylesheet\" href=\"/vpns/themes/default/styles.css\">  
 </head>
 <body>
 <div id=\"selector\" style=\"padding:10px;\">
   <h2>User $username updated successfully.</h2>
 </div>
 </body>
 </html>";
;
}
