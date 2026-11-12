#!/usr/bin/perl 

$ENV{PATH} = '/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin';
use lib "/usr/local/lib/perl5/site_perl/5.8.0";
use LWP::Simple;
use HTTP::Status;
use Switch;
use URI;
use File::Basename;
use Socket;

package URI::scp;
require URI::_login;
@ISA=qw(URI::_login);

my $Version='1.0';

sub default_port { 22 }

1;

package URI::sftp;
require URI::_login;
@ISA=qw(URI::_login);


sub default_port { 22 }

1;

package main; 

my $instpath = "/var/";
my $installoptswf=$instpath."wi/bin/.installoptswf";
my $installopts=$instpath."wi/bin/.installopts";
my $tc_startup=$instpath."wi/bin/start_tomcat.sh";
my $tc_stop=$instpath."wi/bin/stop_tomcat.sh";
my $wicontextdir=$instpath."wi/tomcat/conf/Catalina/localhost";
my $webapps_dir = "/var/wi/tomcat/webapps";
my $restore = 0;
my $restart_tomcat = 0;

my $tmpfile;
my $wi_file;
my $url = $ARGV[0]; 
my $uri;
my $scheme;
my $deletefiles=1;
my $deletewifile;
my $freemem;
my $extract_path;

$|++; #autoflush stdout 


open STDOUT, '>>', '/var/log/wfcmd.log' or warn "installwf: Failed to log";
open(STDERR, ">&STDOUT") or warn "installwf: Failed to log errors";

# printing additional debug information
print scalar(localtime()) , " install";
foreach $argnum (0 .. $#ARGV) {
  print " $ARGV[$argnum]";
}
print "\n";

prepare();
check_wf_version();

check_already_installed();

fetch();
$deletewifile=$deletefiles;

$deletefiles=1;
$url=$ARGV[1];
$max=$ARGV[2];

fetch();
install(); 
cleanup(); 


sub
check_wf_version()
{
    my $ret;
    $uri = URI->new("$url");
    $filename = basename($uri->path());
    $Version =~ m/(\d+).\d+/;
    $wi_file = $filename;
    $uri = URI->new("$ARGV[1]");
    $filename = basename($uri->path());
    if ($filename !~ m/^openjdk-7.*.tbz$/ ) {
	&err("Error: Unsupported JDK/JRE file. Specify location of openjdk7\n");
    }
    $extract_path = "$instpath/$wi_file";
}
sub extract_wf()
{
 out("$instpath: Extracting WF tar ball ... "); 
 out("Extract path = $extract_path\n");
 my $ret = system("tar xf $extract_path 1>/dev/null 2>/dev/null");
 switch ($ret)
 {
 	case "0"
    	{
      		out("Done.\n"); 
    	}
    	else
	{
      		out("Failed.\n"); 
      		&ret_err_after_restore("Error: Extraction failed, invalid tar archive?\n"); 
    	}
 }
 if(-e "/var/wi/bin/webfront.war") {
		my $instpathwf = $webapps_dir."/ROOT";
		my $confpath = "/var/wi/tomcat/conf/Catalina/localhost/";
		system("mkdir -p $instpathwf");
		my $ret = system("tar xf /var/wi/bin/webfront.war -C $instpathwf");
		if($ret == 0) {
			out("$instpath: Extracted WF Package Hulla\n ");
		}
		else {
			out("Not able to extract WF please check the tar\n ");
			exit(-1);
		}
		system("mkdir -p /var/wi/tomcat/conf/Catalina/localhost");
		chdir("$confpath");
		my $writeint = "<Context docBase=\"../webapps/ROOT\" debug=\"0\" override=\"true\" allowLinking=\"true\"/>";

		my $fileconf = "ROOT.xml";
		unless(open FILE, '>'.$fileconf) {
			                die "Unable to create $fileconf";
		}
		print FILE $writeint;
		close(FILE);
		out("$instpath: Writing to conf file done\n");
		return 0;
	}
	else {
		out("Web Front package is missing ... Exiting\n");
		exit(-1);
	}
}
	

sub
fetch()
{
  $uri = URI->new("$url");
  $filename = basename($uri->path());
  $tmpfile = $instpath.$filename;
  out("Fetching $url ...\n");
  $scheme = $uri->scheme();
  switch ($scheme)
  {
    case ("http")
    {
      fetch_bsd();
    }
    case ("https")
    {
      fetch_bsd();
    }
    case ("ftp")
    {
      fetch_bsd();
    }
    case ("scp")
    {
      fetch_scp(); 
    } 
    case ("sftp")
    {
      fetch_sftp(); 
    }
    case ("file")
    {
      fetch_file(); 
    }
    else
    {
      print_usage(); 
    }
  }
}

sub
fetch_bsd()
{
  my $ret = system("fetch -o $tmpfile $url");
  if ($ret != 0)
  {
    &err("Error: file transfer failed for $url\n");
  }
}

sub
fetch_scp()
{
  my ($user, $pass) =
  split(/:/, $uri->userinfo); 

  if (!defined($user))
  {
    $user = $pass; 
  }
  else
  {
    $ENV{NSPASS} = $pass; 
  }
  my $host = $uri->host(); 
  my $path = $uri->path(); 

 
  my $ret = system("scp $user\@$host:$path $tmpfile"); 
  if ($ret != 0)
  {
    &err("Error: file transfer failed for $url\n"); 
  }
}

sub
fetch_sftp()
{

  my ($user, $pass) =
  split(/:/, $uri->userinfo);

  if (!defined($user))
  {
    $user = $pass;
  }
  else
  {
    $ENV{NSPASS} = $pass; 
  }
  my $host = $uri->host();
  my $path = $uri->path();


  my $ret = system("sftp $user\@$host:$path $tmpfile");
  if ($ret != 0)
  {
    &err("Error: file transfer failed for $url\n");
  }

}

sub
fetch_file()
{

  my $path = $uri->path(); 

  if (stat($path) == NULL)
  {
    &err("Error: file $path does not exist\n"); 
  }

  if ($path ne $tmpfile)
  {
    my $ret = system("cp $path $tmpfile"); 

    if ($ret != 0)
    {
      &err("Error: failed to copy file $path to $tmpfile\n"); 
    }
  }
  else
  {
    $deletefiles=0;
  }
}



sub
print_usage()
{

print "Usage:\n\n";
print "  install <WF URL> <JRE URL>\n\n"; 
print "  URL Format:\n\n";
print "\thttp://[user]:[password]\@host/path/to/file
\thttps://[user]:[password]\@host/path/to/file
\tsftp://[user]:[password]\@host/path/to/file
\tscp://[user]:[password]\@host/path/to/file
\tftp://[user]:[password]\@host/path/to/file
\tfile://path/to/file \n";

  exit(-1);

}

sub
prepare()
{
    if (!defined($ARGV[0]) || !defined($ARGV[1])|| !defined($ARGV[2]))
    {
        print_usage(); 
    }
}

sub
wf_memory()
{
  if ($max == 192) {
      $permgensize = 64;
      $heapsize = 128;
  } 
  if ($max == 576) {
      $permgensize = 64;
      $heapsize = 512;
    }

  if (-e "tomcat/bin/setenv.sh") {
  	system ("grep -w -v CATALINA_PID tomcat/bin/setenv.sh | grep -w -v JAVA_OPTS > tomcat/bin/setenv.sh.bak");
  	system ("mv tomcat/bin/setenv.sh.bak tomcat/bin/setenv.sh");
  }
  open (MYFILE, '>>tomcat/bin/setenv.sh');
  print MYFILE "CATALINA_PID=\"/var/run/wi.pid\"\n";
  print MYFILE "JAVA_OPTS=\"-Xmx".$heapsize."m -XX:MaxPermSize=".$permgensize."m -XX:+DisableExplicitGC -XX:-UseGCOverheadLimit\"\n";
  close (MYFILE);
  chmod 0755, "tomcat/bin/setenv.sh";


  if (-e "bin/start_tomcat.sh") {
    system ("grep -v httpd bin/start_tomcat.sh > bin/start_tomcat.sh.bak");
    system ("mv bin/start_tomcat.sh.bak bin/start_tomcat.sh");
    chmod 0755, "bin/start_tomcat.sh";
  }
}

sub 
install()
{
  chdir("$instpath");
  if (-d "wi")
  {
      if (-d "wi.bkup")
      {
	system("rm -rf wi.bkup");
      }
      rename("wi", "wi.bkup") || &err("Unable to move wi directory to wi.bkup");
      $restore = 1;
      out("$instpath: moved wi to wi.bkup\n"); 
  }
  $res = extract_wf();
  if($res == 1) {
	  &ret_err_after_restore("Cannot extract WF\n");
  }
  $tmppath=$instpath."wi/";
  chdir("$tmppath");
  extract_java();
  $java_home=$tmppath."java_home";

  if (! -d "tomcat" || ! -d "bin" || ! -e "tomcat/bin/startup.sh")
  {
      &ret_err_after_restore("Error: Missing tomcat in wf tar ball, incorrect wf tarball?\n"); 
  }

  system("mkdir -p tomcat/appbase/ROOT tomcat/logs tomcat/temp tomcat/webapps/ROOT tomcat/work");
  
  open(UNAME, "uname -m |");
  my $arch= <UNAME>;
  close(UNAME);
  open (MYFILE, '>bin/start_tomcat.sh');
  print MYFILE "#!/usr/bin/bash\n";
  print MYFILE "if [ ! -e /usr/lib/libstdc++.so ] ; then\n";
  print MYFILE "\tln -s $tmppath"."bin/libstdc++.so.5 /lib/\n";
  print MYFILE "\tln -s $tmppath"."bin/libstdc++.so.5 /usr/lib/libstdc++.so\n";
  print MYFILE "fi\n";
  print MYFILE "grep -w -v java /etc/monitrc | grep -w -v start_tomcat | grep -w -v monit_stop | grep -w -v 8080  > /etc/monitrc.new\n";
  print MYFILE "sed -E \"s/set daemon[ ]+[0-9]+/set daemon 30/\" /etc/monitrc.new > /etc/monitrc\n";
  print MYFILE "rm /etc/monitrc.new\n";
  print MYFILE "chmod 0600 /etc/monitrc\n";
  print MYFILE "killall -HUP monit\n";
  print MYFILE "if [ -e /var/run/wi.pid ] ; then\n";
  print MYFILE "\tkillall -KILL java\n";
  print MYFILE "\trm -f /var/run/wi.pid\n";
  print MYFILE "fi\n";
  if ($arch eq "i386\n") {
       print MYFILE "ulimit -d 300000\n";
  }
  print MYFILE "export JRE_HOME=$java_home\n";
  print MYFILE "$tmppath"."tomcat/bin/startup.sh\n";
  print MYFILE "i=0;\n";
  print MYFILE "while [ \"`curl --silent --connect-timeout 2 --max-time 2 -I http://127.0.0.1:8080/ | grep 'Apache'`\" == \"\" -a \$i -lt 72 ] ; do ((i++)); sleep 1; done\n";
  print MYFILE "printf \"check process java with pidfile /var/run/wi.pid\\n\" >> /etc/monitrc\n";
  print MYFILE 'printf "\tstart program = \"/var/wi/bin/start_tomcat.sh\"\n" >> /etc/monitrc';
  print MYFILE "\n";
  print MYFILE 'printf "\tstop program = \"/var/wi/bin/monit_stop.sh\"\n" >> /etc/monitrc';
  print MYFILE "\n";
  print MYFILE 'printf "\tif failed host 127.0.0.1 port 8080 protocol http and request \"/\" with timeout 25 seconds for 3 cycles then restart\n" >> /etc/monitrc';
  print MYFILE "\n";
  print MYFILE "killall -HUP monit\n";
  close (MYFILE);
  chmod 0755, "bin/start_tomcat.sh";
  wf_memory();

  open (MYFILE, '>bin/stop_tomcat.sh');
  print MYFILE "#!/usr/bin/bash\n";
  if ($arch eq "i386\n") {
       print MYFILE "ulimit -d 300000\n";
  }
  print MYFILE "grep -w -v java /etc/monitrc | grep -w -v start_tomcat | grep -w -v monit_stop | grep -w -v 8080 > /etc/monitrc.new\n";
  print MYFILE "mv /etc/monitrc.new /etc/monitrc\n";
  print MYFILE "chmod 0600 /etc/monitrc\n";
  print MYFILE "killall -HUP monit\n";
  print MYFILE "if [ -e /var/run/wi.pid ] ; then\n";
  print MYFILE "\tkillall -KILL java\n";
  print MYFILE "\trm -f /var/run/wi.pid\n";
  print MYFILE "fi\n";
  close (MYFILE);
  chmod 0755, "bin/stop_tomcat.sh";

  open (MYFILE, '>bin/monit_stop.sh');
  print MYFILE "#!/usr/bin/bash\n";
  if ($arch eq "i386\n") {
       print MYFILE "ulimit -d 300000\n";
  }
  print MYFILE "if [ -e /var/run/wi.pid ] ; then\n";
  print MYFILE "\tkillall -KILL java\n";
  print MYFILE "\trm -f /var/run/wi.pid\n";
  print MYFILE "fi\n";
  close (MYFILE);
  chmod 0755, "bin/monit_stop.sh";

  if ($restore == 1 && $ARGV[3] ne "-clearsites")
  {
      $restart_tomcat = 1;
  }

  $ret = start_tomcat();
  if ($ret != 0)
  {
      &ret_err_after_restore("$tmppath: Unable to start Tomcat - Make sure that JRE URL is for freebsd 8.x arch $arch");
  }

  open (INSTALLOPTS, '>bin/.installoptswf');
  print INSTALLOPTS "$ARGV[0]\n";
  print INSTALLOPTS "$ARGV[1]\n";
  close (INSTALLOPTS);

#Move any existing WI sites to new WI installation

#Move existing WI sites & certificate key store to new WI installation
  if ($restore == 1)
  {
      chdir("$instpath");
      if (-d "wi.bkup/tomcat/webapps")
      {
	  system("rm -rf wi/tomcat/webapps");
	  rename("wi.bkup/tomcat/webapps", "wi/tomcat/webapps") || out("Unable to restore wi.bkup/tomcat/webapps");
      }
      if (-d "wi.bkup/tomcat/conf/Catalina/localhost")
      {
	  system("rm -rf wi/tomcat/conf/Catalina/localhost");
	  rename("wi.bkup/tomcat/conf/Catalina/localhost", "wi/tomcat/conf/Catalina/localhost") || out("Unable to restore wi.bkup/tomcat/conf/Catalina/localhost");
      }
      if (-d "wi.bkup/cached")
      {
	  rename("wi.bkup/cached", "wi/cached") || out("Unable to restore wi.bkup/cached");
      }
      if (-e "wi.bkup/java_home/lib/security/cacerts")
      {
	  $srccertfile="wi.bkup/java_home/lib/security/cacerts";
      }
      elsif (-e "wi.bkup/java_home/jre/lib/security/cacerts")
      {
	  $srccertfile="wi.bkup/java_home/jre/lib/security/cacerts";
      }
      if (-e "wi/java_home/lib/security/cacerts")
      {
	  $dstcertfile="wi/java_home/lib/security/cacerts";
      }
      elsif (-e "wi/java_home/jre/lib/security/cacerts")
      {
	  $dstcertfile="wi/java_home/jre/lib/security/cacerts";
      }
      if (-e "$srccertfile" && -e "$dstcertfile")
      {
	  rename($dstcertfile, $dstcertfile.".bkup");
	  rename($srccertfile, $dstcertfile) || rename($dstcertfile.".bkup", $dstcertfile);
      }
      if (-e "wi.bkup/bin/WebInterface.jar")
      {
	  rename("wi.bkup/bin/WebInterface.jar", "wi/bin/WebInterface.jar") || out("Unable to restore WebInterface.jar");
      }
      if (-e "wi.bkup/bin/.installopts")
      {
	  rename("wi.bkup/bin/.installopts", "wi/bin/.installopts") || out("Unable to restore .installopts");
      }
      system("rm -rf wi.bkup");
  }

  out("Installed fresh Web Front on NetScaler successfully...\n");
}

sub
check_already_installed()
{
  my $matched = 0;
  my $upgrade = 0;
  if (-e $installoptswf)
  {
      $matched = 1;
      open (INSTALLOPTS, $installoptswf);
      $old_url=<INSTALLOPTS>;
      chomp($old_url);
      if ($old_url ne $ARGV[0])
      {
	  $matched = 0;
	  $upgrade = 1;
	  out("WF URL for install was: $old_url\n");
      }
      else {
	      if(-e "/var/wi/tomcat/conf/Catalina/localhost/ROOT.xml") {
		      out("WF was installed using same file: skipping install\n");
	      }
	      else {
		      $matched = 0;
		      out("Netscaler was rebooted extracting only wf\n");
	      }
      }
      close (INSTALLOPTS);
  }
  elsif(-e $installopts) {
	  $matched = 0;
	  out("Web Interface present in the directory\n");
  }
 
  if(-e $installopts && -d $webapps_dir){
	  $extract_path = "$instpath/$wi_file wi/bin/webfront.war";
	  out("Tomcat already present. Extracting only Webfront.war \n");
  }
  if((-d "/var/wi") && $matched == 0) {
  fetch();
  check_java_version();
  $deletewifile=$deletefiles;
  
  $deletefiles=1;
  if($upgrade) {
      prepare_for_upgrade();
  }
  chdir("$instpath");
  my $ret2 = extract_wf();
  if($ret2 == 1) {
	  &ret_err_after_restore("Cannot extract WF\n");
  }

  if($upgrade) {
	  restore_after_upgrade();
  }
  chdir("$instpath");
  if(-e $webapps_dir."/ROOT/index.html") {
  	system("rm $webapps_dir/ROOT/index.html");
  }
  $matched = 1;
  $upgrade = 1;
  open (INSTALLOPTS, '>/var/wi/bin/.installoptswf');
  print INSTALLOPTS "$ARGV[0]\n";
  print INSTALLOPTS "$ARGV[1]\n";
  close (INSTALLOPTS);
  }
  
  open STDERR, '>/dev/null';
  if ($matched == 1 && -e $tc_startup)
  {
    if (-e "/var/run/wi.pid")
    {
      $ret = system("ps ax | grep bin/java | grep `cat /var/run/wi.pid` > /dev/null");
      if ($ret == 0 && $upgrade == 0)
      {
        exit(0);
      }
    }

    $ret = start_tomcat();
    if ($ret != 0)
    {
      &err("$instpath: Unable to start Tomcat \n");
    }
    else {
	    out("Tomcat restarted successfully \n");
    }
    exit(0);
  }

  $ret = system("ps ax | grep bin/java | grep -v grep > /dev/null");
  if ($ret == 0)
  {
    system("grep -w -v java /etc/monitrc | grep -w -v start_tomcat | grep -w -v monit_stop | grep -w -v 8080 > /etc/monitrc.new");
    system("mv /etc/monitrc.new /etc/monitrc");
    system("chmod 0600 /etc/monitrc");
    system("killall -HUP monit");
    system("killall -KILL java");
    system("rm -f /var/run/wi.pid");
  }
}

sub
prepare_for_upgrade()
{
    #During upgrade, do the following:
    #1. Backup old property files
    #2. Stop tomcat
    #3. Delete old WF files
    
    out("Preparing for upgrade\n");
    out("Backing up properties files...");
    chdir($webapps_dir);
	system("mv ROOT/WEB-INF/classes/wfsite.properties .");
	system("mv ROOT/WEB-INF/classes/rfwebproxy.properties .");
	system("mv ROOT/WEB-INF/classes/log4j.properties .");
    out("Done\n");
    out("Stopping Tomcat...");
    if(-e $tc_stop)
	{
        system($tc_stop);
	}
    else
	{
        system("pkill -KILL java");
	}
	out("Done\n");
    out("Removing old WF files...");
    system("rm -rf ROOT/WEB-INF ROOT/META-INF");
    out("Done\n");
    
}

sub
restore_after_upgrade()
{
    #After upgrade, restore
    out("Restoring old properties files after upgrade...");
	chdir($webapps_dir);
	system("mv wfsite.properties ROOT/WEB-INF/classes/");
	system("mv rfwebproxy.properties ROOT/WEB-INF/classes/");
	system("mv log4j.properties ROOT/WEB-INF/classes/");
    out("Done\n");
}

sub
start_tomcat()
{
    my $retry_cnt = 0;

    system("kill -KILL `ps ax | grep start_tomcat.sh | grep -v grep  | awk '{print $1}'` > /dev/null 2>&1");
    system($tc_startup."&");
    do
    {
      $ret = system("ps ax | grep /var/wi/java_home/bin/java | grep -v grep > /dev/null");
      if ($ret != 0)
      {
      	sleep(1);
      	$retry_cnt = $retry_cnt + 1;
      }
    } while ($ret != 0 && $retry_cnt < 4);
    return $ret;
}

sub
cleanup()
{
  if ($deletewifile == 1)
  {
    unlink($instpath.$wi_file);
  }
  if ($deletefiles == 1)
  {
    unlink($tmpfile);
  }
}

sub
err()
{
    print "@_";
    cleanup();
    exit(-1);
}

sub
ret_err_after_restore()
{
    chdir("$instpath");
    if ($restore == 1)
    {
        system("rm -rf wi");
        rename("wi.bkup", "wi");
        print "Restored original WI\n";
        if ($restart_tomcat == 1)
        {
            $ret = start_tomcat();
            if ($ret != 0)
            {
                print "$instpath: Unable to start Tomcat for restored WI\n";
            }
       }
    }
    print "@_";
    cleanup();
    exit(-1);
}

sub extract_java()
{
	$tmppath=$instpath."wi/";
	chdir("$tmppath"); 
	out("$tmppath: Extracting JRE tar ball ... "); 
	$ret = system("tar xfy $tmpfile 1>/dev/null 2>/dev/null");

	switch ($ret)
	{
		case "0"
		{
			system("rm -f +* 2>/dev/null");
			out("Done.\n");
		}
		else
		{
			out("Failed.\n"); 
			&ret_err_after_restore("Error: Extraction failed, invalid tar archive of java?\n"); 
		}
	}

	my @dirs=<*>;
	my $java_home = "";
	foreach $dir (@dirs)
	{
		if (-d $dir && -e $dir."/bin/java") {
			$java_home=$dir;
		}
	}

	if ($java_home eq "")
	{
		&ret_err_after_restore("Error: Unable to find java in JRE tar ball\n"); 
	}

	system("mv $java_home java_home");
}


sub check_java_version()
{
	my $wi_java_version = "";
	open(WIINSTALLOPTS, $installopts);
	$wi_java_version = <WIINSTALLOPTS>;
	$wi_java_version = <WIINSTALLOPTS>;
	chomp($wi_java_version);
	out("\nJava version installed = $wi_java_version\n");
	if($wi_java_version !~ m/.*openjdk-7.*.tbz$/)
	{
		#Incorrect Java version was installed by WI. Remove
		#java_home and extract the correct one.
		out("Incorrect java version present. Installing new Java Package\n");
		$url = $ARGV[1];
		fetch();
		if(-e $tc_stop)
		{
			system($tc_stop);
		}
		out("Removing old Java folder...");
		system("rm -rf /var/wi/java_home");
		out("Done\n");
		extract_java();
	} else {
		out("Java version installed by WI is correct.\n");
	}
}

sub
out()
{
    print "@_";
}
