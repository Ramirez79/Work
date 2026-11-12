#!/usr/bin/perl -w
## This script is used to do ftp monitoring using KAS feature. 
## The mandatory arguments are:
## 	1. File name whose existence will be checked.
## The optional arguments are:
## The arguments must be of the following form:
##      file=<filename>[;content=<string>]
## Example:
##	set monitor ...  -scriptArgs "file=file"

# This sample code is provided to you “AS IS” with no representations,
# warranties or conditions of any kind. You may use, modify and
# distribute it at your own risk.  CITRIX DISCLAIMS ALL WARRANTIES
# WHATSOEVER, EXPRESS, IMPLIED, WRITTEN, ORAL OR STATUTORY, INCLUDING
# WITHOUT LIMITATION WARRANTIES OF MERCHANTABILITY, FITNESS FOR A
# PARTICULAR PURPOSE, TITLE AND NONINFRINGEMENT.  Without limiting the
# generality of the foregoing, you acknowledge and agree that (a) the
# sample code may exhibit errors, design flaws or other problems,
# possibly resulting in loss of data or damage to property; (b) it may
# not be possible to make the sample code fully functional; and (c)
# Citrix may, without notice or liability to you, cease to make
# available the current version and/or any future versions of the
# sample code.  In no event should the code be used to support of
# ultra-hazardous activities, including but not limited to life
# support or blasting activities.  NEITHER CITRIX NOR ITS AFFILIATES
# OR AGENTS WILL BE LIABLE, UNDER BREACH OF CONTRACT OR ANY OTHER
# THEORY OF LIABILITY, FOR ANY DAMAGES WHATSOEVER ARISING FROM USE OF
# THE SAMPLE CODE, INCLUDING WITHOUT LIMITATION DIRECT, SPECIAL,
# INCIDENTAL, PUNITIVE, CONSEQUENTIAL OR OTHER DAMAGES, EVEN IF
# ADVISED OF THE POSSIBILITY OF SUCH DAMAGES.  Although the copyright
# in the code belongs to Citrix, any distribution of the code should
# include only your own standard copyright attribution, and not that
# of Citrix.  You agree to indemnify and defend Citrix against any and
# all claims arising from your use, modification or distribution of
# the code.
#

use strict;
use Socket;
use Netscaler::KAS;
use lib "/flash/nsconfig/monitors/";
use Net::TFTP;

## This function is a handler for performing tftp probe in KAS mode 
sub tftp2_probe
{
  ## There must be at least 3 arguments to this function.
  ## 	1. First argument is the IP that has to be probed.
  ##	2. Second argument is the port to connect to.
  ##	3. Arguments to be used during probing.
  
  if(scalar(@_) < 3)
    {
      return (1,"Insufficient number of arguments");
    }
  
  ## Parse the argument given, to get file and the content
  
  my $FILENAME = ( ( $_[2]=~/file=([^;]+)/ )  ? $1 : "ARDBP32.BIN" );
  my $CONTENT =  ( ( $_[2]=~/content=([^;]+)/ ) ? $1 : "Provisioning Services bootstrap" );

  print $FILENAME . $CONTENT . "\n";
  # please ignore this
  # Variable Declaration and Initialization
  my $HOSTNAME = $_[0];
  my $PORTNO = $_[1];
  my $MAXLEN = 1024;

  my $tftp;

  if (Netscaler::KAS::is_ipv4_address($HOSTNAME)) {
    $tftp = Net::TFTP->new($HOSTNAME, Port => $PORTNO, BlockSize => 1024);
  }
  else {
    $tftp = Net::TFTP->new($HOSTNAME, Port => $PORTNO, BlockSize => 1024, IpMode => 'v6');
  }

  $tftp->ascii;

  my $fh = $tftp->get($FILENAME);
  

  my $store = "";

  while (my $line = <$fh>) {
    $store .= $line;
  }
  return (1, "Probe failed - file not retrieved ") if ( not $store ) ;
  return (1, "Probe failed - tftp error") if ( $tftp->error ) ;

  warn $store;

  if (( $_[2]=~/content=([^;]+)/ )) {
    unless ($store =~ /$CONTENT/ )
    {
      return (1, "Probe failed - Content not Found")
    }
  }
  
  ## Probe succeeded
  return 0;
}

## Register ftp probe handler, to the KAS module.

probe(\&tftp2_probe);
