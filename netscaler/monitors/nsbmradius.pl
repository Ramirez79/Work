#!/usr/bin/perl -w

## This script is used to do radius accounting server monitoring
## using KAS feature.
##
## The mandatory argument is:
##	1. secret:   The shared secret of the radius server.
## The optional arguments are radius accounting attributes:
##	1. user:      	Radius attribute User-Name. if not given, the value 'testuser' is used.
##	2. passwd:    	Radius attribute User-Password. if not given, the value 'testpass' is used.
##	3. nasip:     	Radius attribute NAS-IP-Address. if not given, the value '127.0.0.1' is used.
##	4. framedip:  	Radius attribute Framed-IP-Address. if not given, the value '127.0.0.1' is used.
##	5. apn:    	Radius attribute Called-Station-Id. if not given, the value 'internet' is used.
##	6. msisdn:   	Radius attribute Calling-Station-Id. if not given, the value '440000000000' is used.
##	7. accttype:  	Radius attribute Acct-Status-Type. if not given, the value '1' is used.
##	8. acctsession: Radius attribute Acct-Session-Id. if not given, the value 'NS-HEALTH-CHECK' is used.
## Example:
##              set monitor ...  -scriptArgs "secret=hardlyasecret;user=user1;password=password"

use strict;
use Authen::Radius;
use Netscaler::KAS;

## This function is a handler for performing radius accounting probe in KAS mode
sub radacct_probe
{

	## There must be at least 3 arguments to this function.
        ##      1. First argument is the IP that has to be probed.
        ##      2. Second argument is the port to connect to.
        ##      3. Arguments to be used during probing.
        if(scalar(@_) < 3)
        {
                return (1,"Insufficient number of arguments");
        }
	
	## Parse the argument given, to get secret, user, passwd, nasip, framedip, apn, msisdn, accttype, acctsession.
	## If parsing fails, it is monitoring probe failure.
	$_[2]=~/secret=([^;]+)
		(;user=([^;]+))?
		(;passwd=([^;]+))?
		(;nasip=([^;]+))?
		(;framedip=([^;]+))?
		(;apn=([^;]+))?
		(;msisdn=([^;]+))?
		(;accttype=([^;]+))?
		(;acctsession=([^;]+))?/x
	        	or return (1,"Invalid argument format");

	my $secret = $1;
	my $user = $3            || 'testuser';
	my $passwd = $5          || 'testpass';
	my $nasip = $7           || '127.0.0.1';
	my $framedip = $9        || '127.0.0.1';
	my $apn = $11  		 || 'internet';
	my $msisdn = $13         || '440000000000';
	my $accttype = $15       || '1';
	my $acctsession = $17    || 'NS-HEALTH-CHECK';
	
	my $radius;

	if (Netscaler::KAS::is_ipv4_address($_[0])) {
        	$radius=Authen::Radius->new(Host=>$_[0].":".$_[1],
                                      Secret=>$secret,
                                      Debug=>1);
	} else {
	require Authen::Radius6;
		$radius=Authen::Radius6->new(Host=>$_[0], Port=>$_[1],
                                      Secret=>$secret,
                                      Debug=>1);
	}


        $radius->add_attributes({Name=>1,  Value=>$user, Type=>"string"},           	# User-name
                                {Name=>4,  Value=>$nasip, Type=>"ipaddr"},             	# NAS-IP-Address
                                {Name=>8,  Value=>$framedip, Type=>"ipaddr"},          	# Framed-IP-Address
                                {Name=>30, Value=>$apn, Type=>"string"},  		# Called-Station-Id
                                {Name=>31, Value=>$msisdn, Type=>"string"}, 		# Calling-Station-Id
                                {Name=>40, Value=>$accttype, Type=>"integer"},		# Acct-Status-Type
                                {Name=>44, Value=>$acctsession, Type=>"string"});   	# Acct-Session-Id

        $radius->send_packet(ACCOUNTING_REQUEST);

        my $type=$radius->recv_packet();

        if(defined($type) && ($type==5)) {
          return 0;
        }
        else {
          return (1,"Invalid or no response")
        }
}

## Register radius accounting probe
probe(\&radacct_probe);

