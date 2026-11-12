use strict;
use NetScaler::Portal::Config;
use XML::Simple;
use vars qw (%c);

local *c = \%NetScaler::Portal::Config::c;

# Code shared by GET and POST requests
  my $cgi = new CGI;

  my $doc = ();
  my $datafile = '/var/vpn/global.xml';
  my $parser = XML::Simple->new();
  if (-e $datafile ){
    #file exists, populate document
	$doc = $parser->XMLin($datafile, ForceArray=>["bookmark", "filesystem"]);
  } 
#  else {
#    #missing doc, return to list of proper usernames
#    print "Location: $ENV{adminLoc}admin-users.pl\n\n";
#    return;
#  }

#GET request
if ($ENV{REQUEST_METHOD} eq "GET"){
  #load and process template 
  print "Content-type: text/html\n\n";
  my $vars = ();
  $vars->{global} = $doc;
  my $tmplfile = 'admin-global.html';
  my $template = Template->new({INCLUDE_PATH => $c{adminTemplate_dir}.":". $c{template_dir} });
  $template->process($tmplfile, $vars) || die "Template process failed: ", $template->error(), "\n";

## POST request
} elsif ($ENV{REQUEST_METHOD} eq "POST") {  
  my @parms = $cgi->param;
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
    if($bms[$i] && !$cgi->param("bm".$i."del")){
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
  open(FILEWRITE, "> $datafile") || die "can't open $datafile $!";
  print FILEWRITE $parser->XMLout($doc,  xmldecl => '<?xml version="1.0" encoding="UTF-8"?>', RootName => 'global', );
  close FILEWRITE;
  
  #write the templates
  my $template = Template->new({INCLUDE_PATH => $c{theme_dir} });
  my @things = (
                {name => 'bookmarks',
                 dest => '/var/vpn/templates/globalBK.tmpl',
                 tmpl => '[%- FOREACH bm = bookmarks.bookmark -%]
		<div class="NUI_Icon">
	        <table cellSpacing="0" cellPadding="0">
	        <tr>
	        <td valign=top><a href="[% bm.url %]" target="_blank">
	        <img src="/vpns/navui/images/32_UrlGroup_MSAM_URLGroup_h256_16.ico" border="0" /></a>
	        </td>
		 <td width="8px"></td>
		  <td><a href="[% bm.url %]" target="_blank">[% bm.title %]</a><span class="descr">[% bm.descr %]</span></td>
		   </tr>
	        </table>
	        </div>	                  
            	          [% END -%]',
            	},
	            {name => 'filesystems',
	             dest => '/var/vpn/templates/globalFS.tmpl',
	             tmpl => '[% USE env = EnvHash %][%- FOREACH fs = filesystems.filesystem -%]
	                      <div class="item"><a href="[% env.portalLoc %]filemanager.html?s=[% fs.url %]">[% fs.title %]</a><span class="descr">[% fs.descr %]</span></div>
	                      [% END -%]',
	            }
               );

  foreach my $thing (@things){
    my $output;            
    $template->process(\$thing->{tmpl}, $doc, \$output) || die "Template process failed: ", $template->error(), "\n";
    open(FILEWRITE, "> $thing->{dest}") || die "can't open $thing->{dest} $!";
    print FILEWRITE $output;
    close FILEWRITE;
  }
  
  #return to admin list
  print "Location: $ENV{adminLoc}admin-global.pl\n\n";
}
