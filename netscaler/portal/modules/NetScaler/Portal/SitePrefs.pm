package NetScaler::Portal::SitePrefs;
use NetScaler::Portal::Config;
use fields qw(doc);
use Data::Dumper;

my %fields = (
    doc        => undef,
);
sub new {
	my     $obref = {};         # ref to empty hash
    bless  $obref;              # make it an object in this class
    return $obref;              # return it
}
 
sub filewrite {
	my $self = shift;
	my $output = "[%\n";
	foreach my $key (sort keys %{$self->{doc}}){
	  $output .= "\t" . $key . " = \"" . $self->{doc}->{$key} . "\"\n";
	}
	$output .= "%]";
    if ( ! -d '/var/vpn/templates/'){
      chdir('/var/vpn') || die "Cannot chdir ($!)";
      mkdir('templates', 0755) || die "aargh! can't create dir /var/vpn/templates ($!)" ;
    }
    $filename =  "/var/vpn/templates/boilerplate.tmpl";
	open(FILEWRITE, ">$filename") || die "can't open $filename $!";
	print FILEWRITE $output;
	close FILEWRITE;
	return 1;
}

sub doc{
	my $self = shift;
    if (@_) { $self->{doc} = shift } 
    return $self->{doc};		
}

sub addparam {
    my $self = shift;
    my $key = shift;
    my $value = shift;
    $self->{doc}->{$key} = $value;
}

sub fileread {
    my $self = shift;
    $self->{doc} = ();
    if (-e '/var/vpn/templates/boilerplate.tmpl'){
      $filename = '/var/vpn/templates/boilerplate.tmpl';
    } else {
      $filename = NetScaler::Portal::Config::c->{base_template_dir} . "/boilerplate.tmpl";
    }
    open (FILE, "< $filename")  or die "Can't find $filename: $!\n";
    while (<FILE>) {
       #stripping tt2 chars
       $_ =~ s/\[\%//;
       $_ =~ s/\%\]//;
       $_ =~ s/^\s*//g;
       $_ =~ s/\s*$//g;
       chomp;
       if ($_){
          my @vals = split "=";
          $vals[0] =~ s/^\s*"//;
          $vals[0] =~ s/\s$//;
          $vals[1] =~ s/^\s*\"//;
          $vals[1] =~ s/\"$//;
          $self->{doc}->{$vals[0]}= $vals[1];
       }
    }
    close FILE;
	return $self->{doc};
}
1;	

