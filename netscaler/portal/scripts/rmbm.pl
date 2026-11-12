use strict;
use NetScaler::Portal::UserPrefs;
use NetScaler::Portal::Config;
use Encode;
use vars qw (%c);
use MIME::Base64;

my $cgi = new CGI;
my $user = NetScaler::Portal::UserPrefs->new();
my $doc = $user->csd();

#disallow get requests to make it difficult to launch XSRF attacks
if ($ENV{'REQUEST_METHOD'} ne 'POST') {
 my $msg = "Access Denied";
 print "Location: " . $ENV{portalLoc} . "error.html?msg=$msg\n\n";
 exit;
}

#Do Stuff
my @names = $cgi->param;
my $UI_inuse = Encode::decode('utf8', $cgi->param('UI_inuse'));
if ($doc->{bookmarks}->{bookmark}){
	for (my $count = 0; $count < @{$doc->{bookmarks}->{bookmark}};){
		my $bookmark = @{$doc->{bookmarks}->{bookmark}}[$count];
		$bookmark->{url} =~ s/&apos;/'/g;
		my $remove = 0;
		foreach my $name (@names){
			if ($UI_inuse eq "RfWeb") {
				my $title = Encode::decode('utf8', $cgi->param('AppToDelete'));
				if ($bookmark->{title} eq $title) {
					$remove = 1;
					last;
				}
			} else {
				if ($bookmark->{url} eq Encode::decode('utf8', $name)) {
					$remove = 1;
					last;
				}
			} 
		}
		if ($remove){
			splice(@{$doc->{bookmarks}->{bookmark}}, $count, 1);
		} else {
			$count++;		
		}
	}
}
if ($UI_inuse eq "RfWeb") { 
	if ($doc->{subscriptions}->{subscription}){
        	for (my $count = 0; $count < @{$doc->{subscriptions}->{subscription}}; $count++){
                	my $subscription = @{$doc->{subscriptions}->{subscription}}[$count];
                        my $appID =  Encode::decode('utf8', $cgi->param('appID'));
                        if ($subscription->{appID} eq $appID) {
                                splice(@{$doc->{subscriptions}->{subscription}}, $count, 1);
                                last;
                        } 	
        	}
	}
}

if ($UI_inuse ne "RfWeb"){
	if ($doc->{filesystems}->{filesystem}){
		for (my $count = 0; $count < @{$doc->{filesystems}->{filesystem}};){
			my $bookmark = @{$doc->{filesystems}->{filesystem}}[$count];
			$bookmark->{url} =~ s/&apos;/'/g;
			my $remove = 0;
			foreach my $name (@names){
				if ($bookmark->{url} eq Encode::decode('utf8', $name)) {
					$remove = 1;
					last;
				} 
			}
			if ($remove){
				splice(@{$doc->{filesystems}->{filesystem}}, $count, 1);
			} else {
				$count++;		
			}
		}
	}
}
undef(@{$doc->{escbk}->{bookmark}});
undef(@{$doc->{escbk}->{filesystem}});
$user->filewrite($doc);

local *c = \%NetScaler::Portal::Config::c;
if ($UI_inuse eq "RfWeb"){
	print "Content-Type: text; charset=utf-8\r\n";
	print "X-Citrix-Application: Receiver for Web\r\n";
	print "\r\n";
 	print "Bookmark Removed";
} else {
	print "Location: /vpns/navui/refresh.html\n\n";
}
