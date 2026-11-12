use NetScaler::Portal::UserPrefs;
use NetScaler::Portal::Config;
use Encode;
use Fcntl qw(:flock);
use vars qw (%c);

my $cgi = new CGI;

my $user = NetScaler::Portal::UserPrefs->new();
my $doc1;
my $fd;

$user->csd(1);
my $filename = $user->{filename};
print "Content-Type: text; charset=utf-8\r\n";
print "X-Citrix-Application: Receiver for Web\r\n";
print "Cache-Control: no-cache, no-store\r\n";
print "Pragma: no-cache\r\n";
print "Connection: Close\r\n";
printf "\r\n";


#disallow get requests to make it difficult to launch XSRF attacks
if ($ENV{'REQUEST_METHOD'} ne 'POST') { 
	print "{\"status\":\"unsubscribed\"}";
	exit;
}

eval(handle_subscriptions());
if($@) {
	#Close the file to release file lock in case of error
	close $fd;
	print "Error - $@\n";
}

clean_exit();

sub handle_subscriptions {
	$cgi = new CGI;
	read_file();
	my $appID = Encode::decode('utf8', $cgi->param('appID'));
	my $operation = Encode::decode('utf8', $cgi->param('operation'));
	my $node_pos = check_if_subscription_node_exists($appID);
	if($operation eq 'subscribe' || $operation eq 'update') {
		my $position = Encode::decode('utf8', $cgi->param('dazzle:position'));
		if($operation eq 'update' || $node_pos ne "-1") {
			my $updateNode = @{$doc1->{subscriptions}->{subscription}}[$node_pos];
			$updateNode->{position} = $position;
		} else {
			#Operation is subscribe
			my $newNode = {
				appID => $appID,
				position => $position
			};
			push (@{$doc1->{subscriptions}->{subscription}}, $newNode);
		}
		write_file();
		printf "{\"status\":\"subscribed\"}";

	} elsif ($operation eq 'unsubscribe') {
		if($node_pos ne "-1") {
			splice(@{$doc1->{subscriptions}->{subscription}}, $node_pos, 1);
			write_file();
		}
		print "{\"status\":\"unsubscribed\"}";
	}
}

sub check_if_subscription_node_exists {
	my $name = shift;
	if ($doc1->{subscriptions}->{subscription}) {
		my $len = scalar @{$doc1->{subscriptions}->{subscription}};
		for (my $count = 0; $count < $len; $count++) {
			my $node = @{$doc1->{subscriptions}->{subscription}}[$count];
			if($node->{appID} eq $name) {
				return $count;
			}

		}
	}
	return "-1";
}

sub read_file {
	open $fd, '+<', $filename;
	flock ($fd, LOCK_EX) or die "Cannot lock file $filename $!\n"; #Lock the file to avoid race conditions
	read $fd, my $string, -s $fd;
	my $parser = XML::Simple->new();
	$doc1 = eval{$parser->XMLin($string, ForceArray=>["bookmark", "subscription", "filesystem"])};
}

sub write_file {
	$| = 1;
	seek $fd, 0, 0 or die "Cannot seek file $filename $!\n";
	truncate $fd, 0 or die "Cannot truncate file $filename $!\n";
	binmode($fd, ':utf8');
	my $parser = XML::Simple->new();
	print $fd $parser->XMLout($doc1,  xmldecl => '<?xml version="1.0" encoding="UTF-8"?>', RootName => 'user', valueattr => [ 'username', 'theme' ]);
	select((select($fd), $| = 1)[0]); #Force flush output to disk
	close $fd; #Close the file and release lock

}

sub clean_exit {
	undef $doc1;
	undef $fd;
	undef $cgi;
	undef $user;
	exit;
}
