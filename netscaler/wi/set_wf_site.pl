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

open STDOUT, '>>', '/var/log/wfcmd.log' or warn "installwf: Failed to log";
open(STDERR, ">&STDOUT") or warn "installwf: Failed to log errors";

print scalar(localtime()), "\nset wf site\n";

if(!-d "/var/wi") {
	exit(19);
}
if(!-e "/var/wi/tomcat/conf/Catalina/localhost/ROOT.xml") {
	exit(19);
}
if(-e "/var/wi/tomcat/conf/Catalina/localhost/$site.xml") {
	exit(10);
}
if(!(-e "/var/wi/tomcat/webapps/ROOT/$site")) {
	exit(10);
}
$num_args = $#ARGV + 2;
$site = $ARGV[0];
my $j = 1;
for(my $i=1;$i<$num_args;$i++) {
	if($i == 5){
		$argu[$j] = 0;
		$j++;
	}
		$argu[$j] = $ARGV[$i];
		$j++;
}
print "\nSetting WF site $site\n";
my $newline;
my $params = "";
chdir("/var/wi/tomcat/webapps/ROOT/WEB-INF/classes/");
open(FILE,"<wfsite.properties");
@LINES = <FILE>;
close(FILE);
open(FILE,">wfsite.properties");
foreach $LINE (@LINES) {
		@array = split(/\=/,$LINE);
		print FILE $LINE unless ($array[0] eq $site);
		if($array[0] eq $site) {
				$newline= $site;
				$newline = $newline."=";
				for(my $i=1;$i<$num_args;$i++) {
						$params = $params.$argu[$i]."#";
				}
				chop($params);
				$newline = $newline.$params."\n";
				print FILE $newline;	
		}	
}
close(FILE);
print "Params = $params\n";
$ret = system("curl --silent --request POST \"http://127.0.0.1:8080/modsite/$site\" --data \"$params\" --header \"Content-Type: text/plain\" --header \"X-Via-NSCLI: true\" &");
print "Done setting wf site\n";
exit(0);
