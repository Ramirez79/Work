#!/usr/bin/perl
our $target="id=";
our  $id;
our @cName;
our $sId;
our $count;
our $idFlag=0;
our $cFlag=1;
our $hwId;
our $dir;
our $bId;
our $certDir;
#$hwId=pop(@ARGV);
$noOfArgs=@ARGV;
if($noOfArgs < 3){
  print "ctxNetboc.pl <LAC/hw-serial no> <license directory> <cert directory>\n";
  exit 1;
}
$hwId=shift;
chomp($hwId);
$dir=shift;
chomp($dir);
$certDir=shift;
chomp($certDir);
$cmd="/netscaler/ctxslsboc -l" . $hwId ." -e" . $certDir;
print "Running command...\n$cmd ";
`$cmd > .leT` ;
print "\nContacting the Citrix Back Office...";
open FH, ".leT" or die $!;
while(<FH>) {

   my @list=split /[ ]/, $_;
   foreach $item (@list) {
#Get the LA code
    if($item =~ /id=/) {
      $id=$';
      chomp($id);
      $idFlag=1;
      next;
      }
 # Get the Feature names 
    if($item =~ /code=/) {
         push(@cName,$');
         next;
        }
    if($item =~ /count_available=/){
       $count=$';
       chomp($count);
       next;
       }
    if($item =~ /sessionId=/){
       $sId=$';
       chomp($sId);
       next;
       }
    if($item =~ /bind_type=/){
       $bId=$';
       chomp($bId);
       next;
       }

    if($item =~ /\*/){
       foreach (@cName) {
              if(!((/CNS/) || (/CAG/))){
                   $cFlag=0;
              }
       }
       @cName=();
       if($cFlag!=0){
        $cmd="/netscaler/ctxslsboc -a -s $sId -b $bId -i $id -c $count -d $dir -e $certDir";
        print "\nRunning command...\n$cmd ";
        `$cmd`;
        next;
       }
       $cFlag=1;
    }
    next;
   }
 
 
    }

   
close(FH);
print "\nLicenses are installed in $dir ....";
#`rm .leT;
