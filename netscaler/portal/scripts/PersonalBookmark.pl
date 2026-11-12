use strict;

use lib '/netscaler/portal/modules/';
use NetScaler::Portal::UserPrefs;
use NetScaler::Portal::Config;
use Encode;
use vars qw (%c);
use XML::Simple;
use MIME::Base64;

my $cgi = new CGI;

my $user = NetScaler::Portal::UserPrefs->new();
my $doc = $user->csd();
my $xml = new XML::Simple(RootName => "UserData", XMLDecl => '<?xml version="1.0" encoding="utf-8"?>');

if ($ENV{'REQUEST_METHOD'} ne 'POST') {
 my $msg = "Access Denied";
 print "Location: " . $ENV{portalLoc} . "error.html?$msg\n\n";
 exit;
}
#Base 64 encoding so that Appnames which could be urls are not cvpnized. Same with Subscription id's
if ($doc->{bookmarks}->{bookmark}){
	for (my $count = 0; $count < @{$doc->{bookmarks}->{bookmark}};){
		@{$doc->{bookmarks}->{bookmark}}[$count]->{title} = encode_base64(encode('utf8', @{$doc->{bookmarks}->{bookmark}}[$count]->{title}),""); 	
		@{$doc->{bookmarks}->{bookmark}}[$count]->{descr} = encode_base64(encode('utf8', @{$doc->{bookmarks}->{bookmark}}[$count]->{descr}),"");
		$count++;
	}
}

if ($doc->{subscriptions}->{subscription}){
	for (my $count = 0; $count < @{$doc->{subscriptions}->{subscription}};){
	    @{$doc->{subscriptions}->{subscription}}[$count]->{appID} = encode_base64(@{$doc->{subscriptions}->{subscription}}[$count]->{appID},"");
	    $count++;
	}
}

my $bookmark = $doc->{bookmarks};
my $subscriptions = $doc->{subscriptions};
my $out = { Bookmarks => $bookmark, Subscriptions => $subscriptions};
print "Content-Type: text/xml\r\n";
print "X-Citrix-Application: Receiver for Web\r\n";  # Needed for the RfwebUI
print "\r\n";
print $xml->XMLout($out);
