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

my $Version='1.10';

sub default_port { 22 }

1;

package URI::sftp;
require URI::_login;
@ISA=qw(URI::_login);


sub default_port { 22 }

1;

package main; 

my $instpath = "/var/";
my $installopts=$instpath."wi/bin/.installopts";
my $tc_startup=$instpath."wi/bin/start_tomcat.sh";
my $maxsitefile=$instpath."wi/bin/.maxsites";
my $wicontextdir=$instpath."wi/tomcat/conf/Catalina/localhost";
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

my $tc7_contexts_dir;
my $tc7_sites_dir;
$|++; #autoflush stdout 

open STDOUT, '>>', '/var/log/wicmd.log' or warn "installwi: Failed to log";
open(STDERR, ">&STDOUT") or warn "installwi: Failed to log errors";

# printing additional debug information
print scalar(localtime()) , " install";
foreach $argnum (0 .. $#ARGV) {
  print " $ARGV[$argnum]";
}
print "\n";

prepare();
check_wi_version();
my $maxsites = $ARGV[2];

if( -d "/var/wi/cached")
{
	print "Cached folder exists. Applying Tomcat7 fix\n";
	$tc7_contexts_dir = "/var/wi/cached/contexts";
	$tc7_sites_dir = "/var/wi/cached/sites";
	tomcat7_fix();	
}

check_already_installed();

fetch();
$deletewifile=$deletefiles;

$deletefiles=1;
$url=$ARGV[1];
fetch();

install();
cleanup(); 

sub
check_wi_version()
{
    my $ret;
    $uri = URI->new("$url");
    $filename = basename($uri->path());
    $Version =~ m/(\d+).\d+/;
    if ( $filename !~ m/nswi-$1.\d+.tgz$/ )
    {
	&err("Error: NetScaler version mismatch. Specify location of nswi package.\n");
    }
    $wi_file = $filename;
    $uri = URI->new("$ARGV[1]");
    $filename = basename($uri->path());
    if ( $filename !~ m/^openjdk-7.*.tbz$/ ) {
	&err("Error: Unsupported JDK/JRE file. Specify location of openjdk7 JRE/JDK tar ball file for FreeBSD8.x/amd64, e.g. file downloaded from http://ftp.riken.jp/pub/FreeBSD/ports/amd64/packages-6-stable/java/openjdk6-b17_2.tbz\n");
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
write_file()
{
}

sub
print_usage()
{

print "Usage:\n\n";
print "  install <WI URL> <JRE URL> <MAXSITES>\n\n"; 
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
    if (!defined($ARGV[0]) || !defined($ARGV[1]) || !defined($ARGV[2]))
    {
        print_usage(); 
    }
}

sub
wi_setup_maxsites()
{
#Set up memory & site limits
  if ($maxsites < 25) {
      $permgensize = 64;
      $heapsize = 128;
      $maxsites = 3;
  } elsif ($maxsites < 50) {
      $permgensize = 256;
      $heapsize = 512;
      $maxsites = 25;
      $maxclients = 15;
  } elsif ($maxsites < 100) {
      $permgensize = 512;
      $heapsize = 768;
      $maxsites = 50;
      $maxclients = 20;
  } elsif ($maxsites < 200) {
      $permgensize = 1024;
      $heapsize = 1536;
      $maxsites = 100;
      $maxclients = 30;
  } elsif ($maxsites < 500) {
      $permgensize = 2048;
      $heapsize = 3072;
      $maxsites = 200;
      $maxclients = 50;
  } else {
      $permgensize = 5120;
      $heapsize = 7680;
      $maxsites = 500;
      $maxclients = 100;
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

  open (MYFILE, '>bin/.maxsites');
  print MYFILE "$maxsites\n";
  close (MYFILE);
  chmod 0644, "bin/.maxsites";

  if (-e "bin/start_tomcat.sh") {
    system ("grep -v httpd bin/start_tomcat.sh > bin/start_tomcat.sh.bak");
    system ("mv bin/start_tomcat.sh.bak bin/start_tomcat.sh");
    if ($maxclients > 30) {
      open (MYFILE, '>>bin/start_tomcat.sh');
      print MYFILE "sed \"s/MaxClients 30/MaxClients $maxclients/\" /etc/httpd.conf > /etc/httpd.conf.new\n";
      print MYFILE "mv /etc/httpd.conf.new /etc/httpd.conf\n";
      print MYFILE "killall -HUP httpd\n";
      close (MYFILE);
    }
    chmod 0755, "bin/start_tomcat.sh";
  }
}

sub 
install()
{
  chdir("$instpath");
  if (-d "wi" && !-e "/var/wi/bin/webfront.war") #Move wi to wi.bkp only when WF is not installed
  {
      if (-d "wi.bkup")
      {
	system("rm -rf wi.bkup");
      }
      rename("wi", "wi.bkup") || &err("Unable to move wi directory to wi.bkup");
      $restore = 1;
      out("$instpath: moved wi to wi.bkup\n"); 
  }
  out("$instpath: Extracting WI tar ball ... "); 

  my $ret = system("tar xfz $instpath/$wi_file 1>/dev/null 2>/dev/null"); 
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

  if (!-e "/usr/bin/bzip2")
  {
    $ret = system("ln -s $instpath/wi/bin/bzip2 /usr/bin/");
    if ($ret != 0)
    {
      &ret_err_after_restore("Error: Unable to link $instpath/wi/bin/bzip2 to /usr/bin\n"); 
    }
  }

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
      &ret_err_after_restore("Error: Extraction failed, invalid tar archive?\n"); 
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
  $java_home=$tmppath."java_home";

  if (! -d "tomcat" || ! -d "bin" || ! -e "tomcat/bin/startup.sh")
  {
      &ret_err_after_restore("Error: Missing tomcat in wi tar ball, incorrect wi tarball?\n"); 
  }

  system("mkdir -p tomcat/appbase/ROOT tomcat/conf/Catalina/localhost tomcat/logs tomcat/temp tomcat/webapps/ROOT tomcat/work");
  
  open (MYFILE, '>tomcat/appbase/ROOT/index.html');
  print MYFILE "Invalid path\n";
  close (MYFILE);
  chmod 0644, "tomcat/appbase/ROOT/index.html";
  system ("cp tomcat/appbase/ROOT/index.html tomcat/webapps/ROOT/index.html");

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
  wi_setup_maxsites();

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

  open (INSTALLOPTS, '>bin/.installopts');
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
      system("rm -rf wi.bkup");
  }

  out("Installed Web Interface on NetScaler successfully\n");
}

sub
check_already_installed()
{
  my $matched = 0;
  my $maxsitesmatched = 0;
  if (-e $installopts)
  {
      $matched = 1;
      open (INSTALLOPTS, $installopts);
      $old_url=<INSTALLOPTS>;
      chomp($old_url);
      if ($old_url ne $ARGV[0])
      {
	  $matched = 0;
	  out("WI URL for install was: $old_url\n");
      }
      $old_url=<INSTALLOPTS>;
      chomp($old_url);
      if ($old_url ne $ARGV[1])
      {
	  $matched = 0;
	  out("JRE URL for install was: $old_url\n");
      }
      close (INSTALLOPTS);

	  print "Old WI exists. Applying Tomcat7 fix\n";
	  $tc7_contexts_dir = "/var/wi/tomcat/conf/Catalina/localhost";
	  $tc7_sites_dir = "/var/wi/tomcat/webapps";
	  tomcat7_fix();
  }
  elsif(!-e "/var/wi/bin/webfront.war") #Perform port scan only if WF is not installed
  {
      my ($ip, $protocol, $port, $var1, $var2);
      out("Performing port scan...\n");
      $protocol = getprotobyname('tcp');
      $ip = "localhost";
      $port = 0;
      @ports = (8080, 8005, 8443, 8009);
      $msg="";
      for (; $port < 4; $port += 1) {
	          socket(SOCKET, PF_INET, SOCK_STREAM, $protocol);

      $var2 = inet_aton($ip);

      $var1 = sockaddr_in($ports[$port], $var2);

      if (connect(SOCKET, $var1))
      {
	      $a = "$ports[$port] port is already used\n";
	      $msg = $msg.$a;
      }

      }
	if($msg ne "")
	{
		&err($msg);
	}
	  

  }
  if (-e $maxsitefile)
  {
      $maxsitesmatched = 1;
      open (INSTALLOPTS, $maxsitefile);
      $old_maxsites=<INSTALLOPTS>;
      chomp($old_maxsites);
      if ($old_maxsites ne $maxsites)
      {
	  $maxsitesmatched = 0;
	  out("maxSites for install were: $old_maxsites\n");
      }
      if ($old_maxsites > $maxsites)
      {
	  opendir(DIR, $wicontextdir);
	  @count = readdir(DIR);
	  $count = @count - 2;
	  closedir(DIR);
	  if ($count > $maxsites)
	  {
	      out("Install unchanged: maxSites ".$maxsites." is less than configured sites(".$count.")\n");
	      exit 1;
	  }
      }
  }
  open STDERR, '>/dev/null';
  if ($matched == 1 && $maxsitesmatched == 1 && -e $tc_startup)
  {
    out("WI was installed using same files: skipping install\n");
    if (-e "/var/run/wi.pid")
    {
      $ret = system("ps ax | grep bin/java | grep `cat /var/run/wi.pid` > /dev/null");
      if ($ret == 0)
      {
        exit(0);
      }
    }
    $ret = start_tomcat();
    if ($ret != 0)
    {
      &err("$instpath: Unable to start Tomcat \n");
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
  chdir("$instpath");
  if ($matched == 1 && $maxsitesmatched == 0 && -e $tc_startup)
  {
    chdir("wi");
    wi_setup_maxsites();
    $ret = start_tomcat();
    if ($ret != 0)
    {
      &err("$instpath: Unable to start Tomcat \n");
    }
    out("WI was installed using same files: restarted Tomcat after changing maxsites to ".$maxsites."\n");
    exit(0);
  }
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
tomcat7_fix()
{
	print "TC Contexts dir = $tc7_contexts_dir\n";
	print "TC Sited dir = $tc7_sites_dir\n";
	opendir(D, $tc7_contexts_dir) || die "Can't open directory: $!\n";
	while (my $f = readdir(D)) 
	{
		if( $f ne "." && $f ne "..")
		{
			my $str = substr($f, 0, -4);
			$str =~ s/#/\//g;
			print "Searching for site  = $str\n";
			my $file = "$tc7_sites_dir/$str/auth/login.jsp";
			my $file_fix = $file."_fix";
			if( -e $file)
			{
				print "Fixing login.jsp $file\n";
				$ret = `sed s/type=\\\"template\\\"\\\ type=\\\"template\\\"/type=\\\"template\\\"/g $file >$file_fix`;
				$ret = rename $file_fix, $file;
			}
		}
	}
	closedir(D);
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

sub
out()
{
    print "@_";
}
