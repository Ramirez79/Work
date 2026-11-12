#!/usr/bin/perl -w
# FILE:         $Id: //depot/main/rs_111/usr.src/netscaler/scripts/nsaws_dhconf.pl#1 $
# LAST CHECKIN: $Author: build $
#               $DateTime: 2016/06/13 09:44:25 $
#
# Copyright 1998-2016 Citrix Systems, Inc. All rights reserved.
# This software and documentation contain valuable trade
# secrets and proprietary property belonging to Citrix Systems, Inc.
# None of this software and documentation may be copied,
# duplicated or disclosed without the express
# written permission of Citrix Systems, Inc.
use strict;
use Sys::Syslog;

openlog("nsaws_dhconf", "pid,cons", "local0");

my ($intf, $ip, $netmask, $gateway, $nameserver);

# Effectively getting the last entries.
while (<>) {
    chomp;
    if (/^\s*interface\s+"([0-9]+(\/[0-9]+){1,})"/) {
        $intf = $1;
    } elsif (/^\s*fixed-address\s+([0-9]+(\.[0-9]+){3})/) {
        $ip = $1;
    } elsif (/^\s*option\s+subnet-mask\s+([0-9]+(\.[0-9]+){3})/) {
        $netmask = $1;
    } elsif (/^\s*option\s+routers\s+([0-9]+(\.[0-9]+){3})/) {
        $gateway = $1;
    } elsif (/^\s*option\s+domain-name-servers\s+([0-9]+(\.[0-9]+){3})/) {
        $nameserver = $1;
    }
}
defined($intf) or syslog("err", "no interface!\n"), exit -1;
defined($ip) or syslog("err", "no IP address!\n"), exit -1;
defined($netmask) or syslog("err", "no netmask!\n"), exit -1;
defined($gateway) or syslog("err", "no gateway!\n"), exit -1;
defined($nameserver) or syslog("err", "no name server!\n"), exit -1;

syslog("info", "$intf: $ip / $netmask\n");
syslog("info", "gateway: $gateway\n");
syslog("info", "nameserver: $nameserver\n");

my $cmd;
$cmd = "ifconfig $intf inet $ip netmask $netmask";
print $cmd, "\n", `$cmd`;
$cmd = "route add 0.0.0.0 $gateway 0.0.0.0";
print $cmd, "\n", `$cmd`;
# This will get created later.
#$cmd = "echo 'nameserver $nameserver' >> /etc/resolv.conf";
#print $cmd, "\n", `$cmd`;

exit 0;
