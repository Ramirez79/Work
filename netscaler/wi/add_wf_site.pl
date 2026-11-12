#!/usr/bin/perl 

$ENV{PATH} = '/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin';
use lib "/usr/local/lib/perl5/site_perl/5.8.0";
use LWP::Simple;
use HTTP::Status;
use Switch;
use URI;
use File::Basename;
package main;
$|++; #autoflush stdout 


open STDOUT, '>>', '/var/log/wfcmd.log' or warn "addwf: Failed to log";
open(STDERR, ">&STDOUT") or warn "addwf: Failed to log errors";

print scalar(localtime()) , "\nadd wf site\n";


my $site = $ARGV[0];
my $storefront = $ARGV[1];
my $store = $ARGV[2];
my $html5 = $ARGV[3];
my $workspace = $ARGV[4];
my $timeout = 0;
my $roaming = $ARGV[5];
my $xframe = $ARGV[6];
if(!-d "/var/wi") {
	print "/var/wi is not present. WebFront is not installed..\n";
	exit(19);
}
if(!-e "/var/wi/tomcat/conf/Catalina/localhost/ROOT.xml") {
	print "ROOT.xml is not present. WebFront is not installed..\n";
	exit(19);
}
chdir("/var/wi/tomcat/webapps");

if(-e "/var/wi/tomcat/conf/Catalina/localhost/$site.xml") {
	print "A Web Interface Site already exists by that name..\n";
	exit(9);
}
if(-e "/var/wi/tomcat/webapps/ROOT/$site") {
	print "A Web Front already exists by that name. Not creating another link\n";
	
}else {

    $ret = system("ln -s /var/wi/tomcat/webapps/ROOT/WEB-INF/views /var/wi/tomcat/webapps/ROOT/$site");
    if($ret ne 0) {
        print "Error! Could not create symlink!";
        exit(9);
    }
}
my $pos = $storefront."#".$store."#".$html5."#".$workspace."#".$timeout."#".$roaming."#".$xframe;
my $line = $site."=".$pos;
print "\nAdding WF site $site with params $pos\n";
chdir("ROOT/WEB-INF/classes/");
my $filename = 'wfsite.properties';
open(my $fh, '>>', $filename) or die "Could not open file '$filename' $!";
print $fh $line;
print $fh "\n";
close $fh;
close STDERR;
close STDOUT;
$ret = system("curl --silent  --request POST \"http://127.0.0.1:8080/addsite/$site\" --data \"$pos\" --header \"Content-Type: text/plain\" --header \"X-Via-NSCLI: true\" &");
print "Done adding WF site\n";
exit(0);
