#!/usr/bin/perl
#
# FILE:   $Id: //depot/main/rs_111/usr.src/netscaler/scripts/collector_upload.pl#1 $
# LAST CHECKIN:   $Author: build $
#                 $Date: 2016/06/13 $
#
# collector_upload.pl - upload data at https://callhome.citrix.com/upload or https://cis.citrix.com - 11.0
#

use strict;
use warnings;
use Digest::MD5 qw(md5_hex);
use HTTP::Request::Common;
use LWP::UserAgent;
use URI;
use Sys::Syslog;

#maximum 10 license number will be passed to backend server
use constant MAX_LIC_SNO => 10;

sub usage {
	print "Usage:\n";
	print "\t$0: Utility to upload collector archive file to Citrix upload servers.\n\n";
	print "\t$0 [-proxy [<proxy_user>:<proxy_password>@]<proxy_host>:<proxy_port>] [-user <user>] [-password <password>] [-sr <sr>] [-description <description>] [-debug] <file>\n\n";
	print "\t<proxy_user>\t\tUser name to authenticate at the proxy server\n";
	print "\t<proxy_password>\tUser password to authenticate at the proxy server\n";
	print "\t<proxy_host>\t\tHost name or IP of the proxy server\n";
	print "\t<proxy_port>\t\tPort to connect to the proxy server\n";
	print "\t<user>\t\t\tMy Citrix login user name\n";
	print "\t<password>\t\tMy Citrix login password\n";
	print "\t<sr>\t\t\tService request case number\n";
	print "\t<description>\t\tDescription message\n";
	print "\t<file>\t\t\tfile name to upload\n\n";
	print "\t<debug>\t\t\tTo debug the script manully\n\n";
	print "\nExit Status:\n";
	print "\t 1 = \tSuccess\n";
	print "\t 2 = \tFailed to verify Server certificate\n";
	print "\t 3 = \tFailed to register/unregister from Citrix upload server with Unknown reason\n";
	print "\t 4 = \tUnable to connect to Citrix upload server. Please check the internet connectivity\n";
	print "\t 5 = \tFailed to stat upload file\n";
	print "\t 6 = \tFailed to calculate message-digest of upload file\n";
	print "\t 7 = \tFailed to open upload file\n";
	print "\t 8 = \tFailed to start upload transaction\n";
	print "\t 9 = \tFailed to read upload file\n";
	print "\t10 = \tFailed to upload chunk\n";
	print "\t11 = \tFailed to close upload transaction\n";
	print "\t17 = \tFailed to login or accept legal terms\n\n";
	print "\t18 = \tIllegal option\n\n";
	exit(18);
}

# Global varuables

my $host            = "";
my $upload_file     = "";         # file name
my $proxy           = "";
my $hostid          = "";         # host id of a vpx instance
my $url             = "";
my $toUrl           = "";
my $ssl_cert        = "";         # server cert file
my $upload_tid      = "";         # transaction id
my $file_md5        = "";         # file MD5 checksum
my $file_size       = 0;          # file size
my $isCallhome      = 0;
my $isManual        = 0;
my $DEBUG           = 0;
my $retry           = 3;
my $chunk_size      = 256 * 1024; # built-in chunk size in bytes
my $tz              = $ENV{TZ};
my $port            = 443;
my $protocol        = "https";
my $syslog_facility = "";
my $SYS_LOG         = 1;
my $syslog_ident    = "collector_upload.pl: ";
my $syslog_opt      = "ndelay,pid,nofatal";
my $syslog_level    = "LOG_DEBUG";

if (!$tz) {
  chomp($tz = `date +%Z`);
}

# Callhome Globals
my $prod          = "";   # parameter "prod"
my $ver           = "";   # parameter "ver"
my $res           = "";
my $sno           = "";   # serial number
my $lic_sno       = "";   # VPX license serial number
my $contact       = "";   # contact email
my $event         = "";
my $reg           = 0;
my $unreg         = 0;
my $p_heartbeat   = 0;
my $reg_str       = '';
my $debug_str     = '';
my $retry_accept  = 0.02;
my $xml_script 	  = "/netscaler/callhome_xml_registration.pl";

# Manual run Globals.
my $user           = "";
my $password       = "";
my $sr             = "";
my $description    = "";
my $agreed_version = "";
my $legal_version  = "";
my $legal_content  = "";
my $src            = "PerlScript";

my $ua = LWP::UserAgent->new;
$ua->cookie_jar({});

sub getVPXLic {
	my $lic_sno_count = 0;
	my @files = glob("/nsconfig/license/*.lic");

	foreach my $file (@files)
	{
		my $line = `grep -m1 "SN=" "$file"`;
		if ($line =~ /SN=(.*):/) {
			$lic_sno = $lic_sno.$1;
			$lic_sno_count++;
			if ($lic_sno_count >= MAX_LIC_SNO) {
				last;
			} else {
				$lic_sno = $lic_sno.":";
			}
		}
	}
}

# Handling command line arguments for callhome
sub getCallhomeOptions {
	while ($#ARGV >= 0) {
		my $para = shift(@ARGV);
		if ($para eq "-sno") {
			$sno = shift(@ARGV);
			# sno will be passed as 'vpx' *only* for standalone VPX (non Triscale)
			if ($sno eq "vpx") {
				getVPXLic();
				$sno = ""; #we don't want to send any value as serial number in case of VPX.
			}
		} elsif ($para eq "-lic_sno") {
			$lic_sno = shift(@ARGV);
		} elsif ($para eq "-event") {
			$event = shift(@ARGV);
		} elsif ($para eq "-r") {
			$reg = 1;
			$reg_str = "yes";
		} elsif ($para eq "-contact") {
			$contact = shift(@ARGV);
		} elsif ($para eq "-u") {
			$unreg = 1;
		} elsif ($para eq "-p") {
			$p_heartbeat = 1;
			$reg_str = "heartbeat";
		} elsif ($para eq "-hostid") {
			$hostid = shift(@ARGV);
		} elsif ($para eq "-debug") {
			$DEBUG = 1;
		} elsif ($para eq "-proxy") {
			$proxy = shift(@ARGV);
			if (length $proxy == 0) {
				usage();
			}
			require Net::SSL;
			$ua->proxy(['https'], "connect://$proxy");
		} elsif (($para eq "-user") || ($para eq "-password") || ($para eq "-sr")) {
			debug ("Callhome related options and manual options cannot be used together!\n");
			usage();
		} else {
			$upload_file = $para;
		}
	}

	# sno is required
	# if is not reg, unreg or heartbeat, then it is upload, so both file and event are required
	if (!($lic_sno || $sno) || (!$reg && !$unreg && !$p_heartbeat && (!$upload_file || !$event))) {
		usage();
	}
}

# Handling command line arguments for manual
sub getManualOptions {
	while ($#ARGV >= 0) {
		my $para = shift(@ARGV);
		if ($para eq "-proxy") {
			$proxy = shift(@ARGV);
		} elsif ($para eq "-user") {
			$user = shift(@ARGV);
		} elsif ($para eq "-password") {
			$password = shift(@ARGV);
		} elsif ($para eq "-sr") {
			$sr = shift(@ARGV);
		} elsif ($para eq "-description") {
			$description = shift(@ARGV);
		} elsif ($para eq "-debug") {
			$DEBUG = 1;
		} elsif (($para eq "-c") || ($para eq "-sno") || ($para eq "-contact") || ($para eq "-event") || ($para eq "-r") || ($para eq "-u") || ($para eq "-hostid"))   {
			debug ("CallHome related options and manual options cannot be used together!\n");
			usage();
		} else {
			$upload_file = $para;
		}
	}

	if ($proxy ne "") {
		if ($proxy !~ /^(([^:@\/\s]+):([^:@\/\s]+)@)?([^:@\/\s]+:\d+)\/?$/) {
			print "!! Invalid Proxy Setting !!\n\n";
			usage();
		}
		#$ENV{HTTPS_PROXY} = "http://$4";
		#if ($2 && $3) {
		#	$ENV{HTTPS_PROXY_USERNAME} = $2;
		#	$ENV{HTTPS_PROXY_PASSWORD} = $3;
		#}
		$ua->proxy(['http', 'https'], "http://$proxy");
	}

	if (!$upload_file) {
		print "!! Missing Upload File Name !!\n\n";
		usage();
	}

}
# Handling command line argument
sub getOption {
	if ($#ARGV >= 0) {
		my $para = shift(@ARGV);
		if ($para eq "-h" || $para eq "-help" ) {
			usage();
		}
		if ($para eq "-c") {
			$isCallhome = 1;
			getCallhomeOptions();
		} else {
			#Push the argument back to ARGV array for processing
			unshift @ARGV, $para;
			$isManual = 1;
			getManualOptions();
		}
	} else {
		usage();
	}
}

# register unit: success return '', fail return error message
sub callhomeRegister {
	# Generate the XML data required for registration
	if ($reg_str eq "yes") {
		$debug_str = "register";
	} else {
		$debug_str = "heartbeat";
	}
	my $xml_data = `perl $xml_script`;
	if ($xml_data eq "") {
		# Could not generate XML data. Fallback to version 1 registration message
		logMessage("Error generating CallHome XML registration data. Falling back to version 1 registration\n");
		$ver = 1;
		debug("$debug_str, prod=[$prod], ver=[$ver], lic_sno=[$lic_sno], sno=[$sno], hostid=[$hostid], contact=[$contact], tz=[$tz]");
		$res = $ua->post("$toUrl",
				 [prod => $prod, ver => $ver, lic_sno => $lic_sno, sno => $sno, hostid => $hostid, contact => $contact, tz => $tz, reg => $reg_str],
				 Host => $host);
	} else {
		# XML data generated. Use version 2 of the registration message
		logMessage("Generated CallHome XML registration data. Using version 2 registration\n");
		$ver = 2;
		debug("$debug_str, prod=[$prod], ver=[$ver], lic_sno=[$lic_sno], sno=[$sno], hostid=[$hostid], contact=[$contact], tz=[$tz]");
		my $urlStr = "${toUrl}?prod=$prod&ver=$ver&lic_sno=$lic_sno&sno=$sno&hostid=$hostid&contact=$contact&tz=$tz&reg=$reg_str";
		$res = $ua->post("$urlStr",
				 Host => $host,
				 Content_Type => 'application/xml',
				 Content => $xml_data);
	}

	$res = $res->content;
	logMessage("Response from post method when doing a $debug_str, prod=[$prod], ver=[$ver], lic_sno=[$lic_sno], sno=[$sno], hostid=[$hostid], contact=[$contact], tz=[$tz]:\n$res\n");
	return ($res =~ /Ok/) ? '' : $res;
}

# unregister unit: success return '', fail return error message
sub callhomeUnregister {
	debug("unregister, prod=[$prod], ver=[$ver], lic_sno=[$lic_sno], sno=[$sno], hostid=[$hostid]");

	$res = $ua->post("$toUrl",
		[prod => $prod, ver => $ver, lic_sno => $lic_sno, sno => $sno, hostid => $hostid, reg => 'no'],
		Host => $host);

	$res = $res->content;
	logMessage("Response from post method while unregistering callhome, prod=[$prod], ver=[$ver], lic_sno=[$lic_sno], sno=[$sno], hostid=[$hostid]:\n$res\n");
	return ($res =~ /Ok/) ? '' : $res;
}

sub serverError {
	my $ec =shift();
	if ($ec =~ /certificate verify failed/) {
		return 2;
	} elsif ($ec =~ /no support contract/) {
		return 12;
	} elsif ($ec =~ /support contract not current/) {
		return 13;
	} elsif ($ec =~ /Unauthorized access/) {
		return 14;
	} elsif ($ec =~ /A server error occurred/) {
		return 15;
	} elsif ($ec =~ /Invalid serial number/) {
		return 16;
	}
	return 3; # Registration,Deregistration/Heartbearting failed for unknown reason.
}

sub checkSSLCert {
	debug("checking SSL cert");

	if ($protocol ne 'https') {
		return 0;
	}

	$res = `openssl s_client -connect $host:$port -CAfile $ssl_cert -verify 3 < /dev/null 2>&1`;
	if ( $res =~ m/.*Verify return code: 0 \(ok\).*/ ) {
		# do nothing
	} else {
		logMessage("\nError: failed to verify server SSL certificate\n");
		return 1;
	}

	return 0
}

sub login {
	debug("login to $url");

	$res = $ua->post("$url/login/",
		[taasCitrixUser => $user, taasCitrixPassword => $password],
		Host => $host);

	if ($res->content =~ /Ok/) {
		# do nothing
	} else {
		#print "$res\n";
		logMessage("Error: failed to login\n");
		return 1;
	}

	return 0;
}

sub getAgree {
	debug("get legal agreement");

	$res = $ua->post("$url/legal/getagree/",
		[format => 'text'],
		Host => $host);

	if ($res->content =~ /agreedVersion=(\S+)\n*/) {
		$agreed_version = $1;
	} else {
		logMessage("\nError: failed to get the version of legal agreement\n");
		return 1;
	}

	return 0;
}

sub getLegal {
	debug("get legal content");

	$res = $ua->post("$url/legal/",
		[format => 'text'],
		Host => $host);

	if ($res->content =~ /version=(\S+)\n(.*)$/) {
		$legal_version = $1;
		$legal_content = $+;
	} else {
		logMessage("\nError: failed to get the latest legal agreement\n");
		return 1;
	}

	return 0;
}

sub setAgree {
	debug("set legal terms");

	$res = $ua->post("$url/legal/setagree/",
		[version => $legal_version, agree=> 'yes', format=>'text'],
		Host => $host);

	if ($res->content =~ /agreedVersion=(\S+)/) {
		$agreed_version = $1;
	} else {
		logMessage("\nError: failed to set agree to the legal terms\n");
		return 1;
	}

	return 0;
}

sub checkLegal {
	if (login() || getAgree() || getLegal()) {
		return 1;
	}

	if ($agreed_version ne $legal_version) {
		# Get away with xml or html tags 

		$legal_content =~ s/\n|\r//gs;                                    # remove text line breaks, in html it does not impact display
		$legal_content =~ s/<a\s*href\s*=\s*"(.*)"><.?>(.*)/$2 [$1]/gis;  # Process the url tags first other wise below lines will remov urls too.
		$legal_content =~ s/<\/p>/\n/gis;                                 # convert </p> to line break
		$legal_content =~ s/<\/div>/\n/gis;                               # convert </div> to line break
		$legal_content =~ s/<br\/?>/\n/gis;                               # convert <br> or <br/> to line break
		$legal_content =~ s/<[^>]*>//gs;                                  # remove any other tags <xxx>, </xxx> or <xxx/>
		$legal_content =~ s/&gt;/>/gis;
		$legal_content =~ s/&lt;/</gis;
		$legal_content =~ s/&nbsp;/ /gis;
		$legal_content =~ s/&amp;/&/gis;

		print STDERR "\n$legal_content\n";
		print STDERR "\nDo you accept? (yes/[no]): ";
		chomp(my $ans = <STDIN>);
		if ($ans eq "yes") {
			if (setAgree()) {
				return 1;
			}
		} else {
			logMessage("Legal agreement not accepted\n");
			return 1;
		}
	}

	return 0;
}

sub uploadInit {
	debug("init upload, prod=[$prod], ver=[$ver], lic_sno=[$lic_sno], sno=[$sno], hostid=[$hostid], event=[$event], fn=[$upload_file], bytes=[$file_size], tz=[$tz]");

	for (my $i = 0; $i < $retry; ++$i) {
		if ($isCallhome) {
			$res = $ua->post("$toUrl",
			[prod => $prod, ver => $ver, lic_sno => $lic_sno, sno => $sno, hostid => $hostid, event => $event, fn => $upload_file, bytes => $file_size, tz=>$tz],
			Host => $host);
		} elsif ($isManual) {
			$res = $ua->post("$toUrl",
			[cid => $sr, desc => $description, fn => $upload_file, bytes => $file_size, src => $src, tz => $tz],
			Host => $host);
		}
		if ($res->content =~ /Ok tid=(\S+)/) {
			$upload_tid = $1;
			return 0;
		}
	}

	logMessage($res->content."\nError: failed to initialize transaction\n");
	return 1;
}

sub uploadChunk {
	my $idx = shift();
	my $len = shift();
	my $content = shift();

	debug("sending chunk, tid=[$upload_tid], idx=[$idx]");

	my $md5 = md5_hex($content);
	my $urlStr = "";

	if ($isCallhome) {
		$urlStr = "${toUrl}?ver=$ver&tid=$upload_tid&idx=$idx&v=$md5";
	} elsif ($isManual) {
		$urlStr = "${toUrl}?tid=$upload_tid&idx=$idx&v=$md5";
	}
	for (my $i = 0; $i < $retry; ++$i) {
		$res = $ua->post("$urlStr",
			Host => $host,
			'Content-Type' => 'Application/octet-stream', 'Content-Length' => $len,
			Content => $content );

		if ($res->content =~ /Ok/) {
			return 0;
		}
	}

	logMessage("\nError: failed to send chunk $idx\n");
	return 1;
}

sub uploadFinal {
	my $num = shift();

	debug("finishing upload, tid=[$upload_tid], md5=[$file_md5]");

	for (my $i = 0; $i < $retry; ++$i) {
		for (my $j = 0; $j <= $num * $retry_accept; ++$j) {
			if ($isCallhome) {
			$res = $ua->post("$toUrl",
				[ver => $ver, tid => $upload_tid, v => $file_md5],
				Host => $host);
			} elsif ($isManual) {
			$res = $ua->post("$toUrl",
				[tid => $upload_tid, v => $file_md5],
				Host => $host);
			}
			if ($res->content !~ /Accepted/) {
				last;
			}
		}

		if ($res->content =~ /Ok/) {
			return 0;
		}
	}

	logMessage($res->content."\nError: failed to close transaction\n");
	return 1;
}

sub uploadKill {
	debug("kill upload, tid=[$upload_tid], fn=[$upload_file], bytes=[$file_size], tz=[$tz]");

	for (my $i = 0; $i < $retry; ++$i) {
		if ($isCallhome) {
			$res = $ua->post("$toUrl",
			[ver => $ver, tid => $upload_tid, fn => $upload_file, bytes => $file_size, tz => $tz],
			Host => $host);
		} elsif ($isManual) {
			$res = $ua->post("$toUrl",
			[tid => $upload_tid, fn => $upload_file, bytes => $file_size, src => $src, tz => $tz],
			Host => $host);
		}
		if ($res->content =~ /Ok/) {
			return 0;
		}
	}

	logMessage($res->content."\nError: failed to cancel transaction\n");
	return 1;
}

# upload a file: return success or fail status code
sub taasUpload {
	if ($isCallhome) {
		if ($reg == 1) {
			if (($res = callhomeRegister()) ne '' ) {
				logMessage($res."\nError: CallHome failed to register\n");
				return serverError($res);
			} else {
				logMessage("\nCallHome registration complete\n");
				return 1;
			}
		} elsif ($unreg == 1) {
			if (($res = callhomeUnregister()) ne '' ) {
				logMessage($res."\nError: CallHome failed to de-register\n");
				return serverError($res);

			} else {
				logMessage("\nCallHome de-registration complete\n");
				return 1;
			}
		} elsif ($p_heartbeat == 1) {
			if (($res = callhomeRegister()) ne '' ) {
				logMessage($res."\nError: CallHome failed to heartbeat\n");
				return serverError($res);

			} else {
				logMessage("\nCallHome heartbeat complete\n");
				return 1;
			}
		}
	} elsif ($isManual) {
		if (checkSSLCert()) {
			return 2;
		}

		if (checkLegal()) {
			return 17;
		}
	}

	unless (open(FILE, "$upload_file")) {
		logMessage("Error: failed to open file $upload_file\n");
		return 7;
	}
	binmode FILE;

	my $md5_ctx = Digest::MD5->new;
	my $status = 1;

	if (uploadInit()) {
		$status = 8;
		goto out;
	}

	logMessage("Uploading collector archive [$upload_file] to [$host]");
	print STDERR "Please wait. It may take a few minutes to complete ...\n";
	my $buf;
	my $len;
	my $idx = 0;
	while (($len = read(FILE, $buf, $chunk_size)) > 0) {
		if (uploadChunk($idx, $len, $buf)) {
			$status = 10;
			goto out;
		}
		print STDERR "...";

		$md5_ctx->add($buf);
		++$idx;
	}
	print STDERR "\n\n";

	$file_md5 = $md5_ctx->hexdigest();

	if (uploadFinal($idx)) {
		$status = 11;
		goto out;
	}

out:
	close(FILE);

	return $status;
}

sub debug {
	my $msg = shift();

	if ($DEBUG) {
		print "$msg\n";
	}
}

sub logMessage {
	my $msg = shift();
	if (@_)
	{
		$msg = $msg . shift();
	}
	print STDERR "$msg\n";

	if ($SYS_LOG) {
		syslog $syslog_level,"%s",$msg;
	}
}

$SIG{'INT'} = sub {
	debug('Ctrl-C is pressed');

	if (!$upload_tid) {
		return;
	}

	uploadKill();
};

getOption();


if ($isCallhome) {

	$host = "callhome.citrix.com";
	$ssl_cert = "/nsconfig/license/ssl/BaltimoreCyberTrustRoot_CH.cert"; #server cert file

	$syslog_facility = "LOG_LOCAL3";
	$prod = "NS";			# parameter "prod"
	$ver = 1;				# parameter "ver"
}
if ($isManual) {
	$syslog_facility = "LOG_LOCAL0";
	$host = "cis.citrix.com";
	$ssl_cert = "/nsconfig/license/ssl/cis.citrix.com.pem";
	$user = $ENV{MYCX_USER} if ($ENV{MYCX_USER});
	$password = $ENV{MYCX_PASSWORD} if ($ENV{MYCX_PASSWORD});
}


# check whether hostname can be resolved. This check is for validating network
# connection/dns configuration.

my $addr = gethostbyname($host);
if (!$addr)
{
	print STDERR "\nUpload of collector archive [$upload_file] failed.\n";
	print STDERR "Check for internet connectivity or DNS configuration on this system. If proxy is configured, then run with -proxy option.\n"; 
	print STDERR "Check usage for more details.\n";
	exit (0);
}

# The following SSL options enable server certificate verification by LWP module
$ua->ssl_opts(verify_hostnames => 1);
$ua->ssl_opts(SSL_ca_file => $ssl_cert);

$url = "$protocol://$host:$port";
if ($isCallhome)
{
	$toUrl = "$url/callhome/";
} elsif ($isManual) {
	$toUrl = "$url/upload/";
}

openlog($syslog_ident, $syslog_opt, $syslog_facility);

if (!$reg && !$unreg && !$p_heartbeat) {
	# Check for file validity for upload operation
	my @statarray = stat($upload_file);
	if (@statarray) {
		$file_size = $statarray[7];
	} else {
		logMessage("Error: File $upload_file not found\n");
		logMessage("Upload of collector archive [$upload_file] failed.\n");
		exit(5);
	}
}

if ($isManual) {
	if (!$user) {
		print STDERR "Please provide My Citrix Login credentials to upload the archive.\n\n";
		print STDERR "My Citrix Login User Name: ";
		chomp($user = <STDIN>);
	}

	if (!$password) {
		print STDERR "My Citrix Login Password: ";
		system "stty -echo";
		chomp($password = <STDIN>);
		system "stty echo";
		print STDERR "\n";
	}
}
my $ret_code = taasUpload();

if (!$reg && !$unreg && !$p_heartbeat) {
	# Log status of upload operation
	if ($ret_code == 1) {
		logMessage("Upload of collector archive [$upload_file] succeeded.\n");
	} else {
		logMessage("Upload of collector archive [$upload_file] failed.\n");
	}
}

closelog();

exit($ret_code);
