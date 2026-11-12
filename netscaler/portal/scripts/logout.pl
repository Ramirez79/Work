use strict;
use NetScaler::Portal::UserPrefs;

my $user = NetScaler::Portal::UserPrefs->new();
$user->sessiondelete();

print "Location: /vpn/\n\n";