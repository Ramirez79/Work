#!/usr/bin/perl 

# todo
#    - write sftp and scp fetch() routines
#
#
$ENV{PATH} = '/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin';
use lib "/usr/local/lib/perl5/site_perl/5.8.0";
use LWP::Simple;
use HTTP::Status;
use Switch;
use URI;

package URI::scp;
require URI::_login;
@ISA=qw(URI::_login);


sub default_port { 22 }

1;

package URI::sftp;
require URI::_login;
@ISA=qw(URI::_login);


sub default_port { 22 }

1;

package main; 

#use Net::SCP qw(scp iscp);
#use Net::SFTP;


my $filebuf;
my $timeData = time;
my $tmpfile = "/var/tmp/$timeData/nsbuild$timeData.tar.gz";
my $tmppath = "/var/tmp/$timeData"; 
my $url = $ARGV[0]; 


my $uri = URI->new("$url"); 
my $scheme= $uri->scheme(); 
my $retval = -1;

$|++; #autoflush stdout 

prepare();
fetch(); 
install(); 
cleanup(); 



sub
fetch()
{

  if (!defined($url))
  {
    print_usage(); 
  }

  out("Starting upgrade @ $url\n"); 
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
      foreach my $str1(@ARGV) {
        if($str1 eq "-A") {
          $retval = 34;
          &err("Error: Invalid file transfer protocol.\n");
        }
      }
      print_usage(); 
    }
  }
}

sub 
fetch_lwp()
{
  out("$url: Starting download ... "); 

  my $rc = getstore($url, $tmpfile);

  switch ($rc)
  {
    case RC_OK
    {
      out("Done.\n");
      break; 
    }
    else
    {
      &err("Error:",status_message($rc), "\n"); 
    }
  }
}

sub
fetch_bsd()
{
  my $ret = system("fetch -o $tmpfile $url");
  if ($ret != 0)
  {
    $retval = 30;
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

 
  my $ret = system("scp -o StrictHostKeyChecking=no $user\@$host:$path $tmpfile"); 
  if ($ret != 0)
  {
    $retval = 30;
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
    $retval = 30;
    &err("Error: file transfer failed for $url\n");
  }

}

sub
fetch_file()
{

  my $path = $uri->path(); 

  if (stat($path) == NULL)
  {
    $retval = 31;
    &err("Error: file $path does not exist\n"); 
  }
  my $ret = system("cp $path $tmpfile"); 

  if ($ret != 0)
  {
    $retval = 32;
    &err("Error: failed to copy file $path to $tmpfile\n"); 
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
print "  install <url> -c -y\n\n"; 
print "  URL Format:\n\n";
print "\thttp://[user]:[password]\@host/path/to/file
\thttps://[user]:[password]\@host/path/to/file
\tsftp://[user]:[password]\@host/path/to/file
\tscp://[user]:[password]\@host/path/to/file
\tftp://[user]:[password]\@host/path/to/file
\tfile://path/to/file \n";

  exit(0);

}

sub
prepare()
{
  unless(mkdir $tmppath) {
        $retval = 35;
        &err("Error: Unable to create temporary directory"); 
    }
}

sub 
install()
{
  chdir("$tmppath"); 
  out("$tmppath: Extracting archive ... "); 

  my $ret = system("tar xvfz $tmpfile -C $tmppath 1>/dev/null 2>/dev/null"); 

  switch ($ret)
  {
    case "0"
    {
      out("Done.\n"); 
    }
    else
    {
      $retval = 33;
      &err("Error: Extraction failed, invalid tar archive?\n"); 
    }
  }

  out("$tmppath: Starting the installns program ... \n");
 
  $first = 1;
  $str = "";
  foreach my $str1(@ARGV) {
      #to skip url argument. 
      if($first == 1) { 
	  $first =0;
	  next;
      }
      $str=$str." ".$str1;
  }
  $ret = system("perl $tmppath/installns ".$str);

  switch ($ret)
  {
    case "0"
    {
      $retval = 0;
      out ("$tmppath: Installion succeeded\n");
      system("rm -rf $tmppath"); 
    }
    else
    {
      if ($ret > 0) {
          $retval = $ret/256; #System call returns error code in 2 bytes. Actual error in upper byte
      }
      &err("$tmppath: Error: Installation Failed \n"); 
    }
  }
}

sub
cleanup()
{

}

sub
err()
{
    system("rm -rf $tmppath"); 
    print "@_";
      exit($retval);
}

sub
out()
{
    print "@_";
}




