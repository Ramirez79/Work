#!/usr/bin/perl

$ENV{PATH} = '/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin';
use lib "/usr/local/lib/perl5/site_perl/5.8.0";
use LWP::Simple;
use HTTP::Status;
use Switch;
use URI;
use File::Basename;

open STDOUT, '>>', '/var/log/wfcmd.log' or warn "installwf: Failed to log";
open(STDERR, ">&STDOUT") or warn "installwf: Failed to log errors";

print scalar(localtime()) , "\nrm wf site\n";

my $site = $ARGV[0];

if(!-d "/var/wi") {
	exit(19);
}
if(!-e "/var/wi/tomcat/conf/Catalina/localhost/ROOT.xml") {
	exit(19);
}
if(-e "/var/wi/tomcat/conf/Catalina/localhost/$site.xml") {
	exit(0);
}
if(-e "/var/wi/tomcat/webapps/$site") {
	exit(10);
}
chdir("/var/wi/tomcat/webapps/ROOT/WEB-INF/classes/");
print "Removing WF site $site\n";
open(FILE,"<wfsite.properties");
@LINES = <FILE>;
close(FILE);
open(FILE,">wfsite.properties");
foreach $LINE (@LINES) {
@array = split(/\=/,$LINE);
print FILE $LINE unless ($array[0] eq $site);
}
close(FILE);
system("rm /var/wi/tomcat/webapps/ROOT/$site");
$ret = system("curl --silent --request POST \"http://127.0.0.1:8080/rmsite/$site\" --header \"X-Via-NSCLI: true\" &");
print "Done removing wf site\n";
exit(0);
