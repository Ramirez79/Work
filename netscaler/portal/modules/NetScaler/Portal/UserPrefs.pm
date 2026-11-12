package NetScaler::Portal::UserPrefs;

use NetScaler::Portal::Config;
use XML::Simple;
use Encode;
use fields qw(username doc);
use Clone qw(clone);
use Storable qw(nfreeze);

sub new {
	my     $obref = {};         # ref to empty hash
    bless  $obref;              # make it an object in this class
    return $obref;              # return it
}

sub csd {
	my $self = shift;
	my $skip_read = shift || "";
  # Santity Check
    my $cgi = new CGI;
    my $username = Encode::decode('utf8', $ENV{'HTTP_NSC_USER'}) || errorpage("Missing NSC_USER header.");
#     Allow any user name
#     if ($username =~ /^([\(\)-\@\w.#: ]+)$/){
#      $self->{username} = $1;  
#     } else {
#      errorpage("Invalid NSC_USER header."); 
#     }  

    $self->{username} = $username;  
  
  # Session Initialization  
    my %session;
    my $nsc_nonce = Encode::decode('utf8', $ENV{'HTTP_NSC_NONCE'}) || errorpage("Missing NSC_NONCE header.");
    my $id = unpack ('H*',$nsc_nonce);

    if($id =~ /^[0-9a-fA-F]*$/ ) {         #check if cookie is a proper hex string or not.
    	open(FILEWRITE, "> /var/run/nshttp_profile_ids/$id") || die "Can't create session file";
	my $serialized = nfreeze(\%session) ;
	print FILEWRITE $serialized;
    	close FILEWRITE;
    }
    else{
	    die "Error: Cookie doesn't contain valid characters!";
    }


    tie %session, 'Apache::Session::File', $id,
     {
        Directory => '/var/run/nshttp_profile_ids/',
        LockDirectory   => '/var/run/nshttp_profile_ids/',
    };

    $self->{session} = %session;
	$self->{filename} = NetScaler::Portal::Config::c->{bookmark_dir} . Encode::encode('utf8', $username) . '.xml';
	if($skip_read eq 1) {
		return;
	}
  # Get Doc
    my $doc = $session{doc} || "";
    if ($doc eq "" || $doc->{timestamp} < $self->timestamp()){
      $doc = $self->fileread($username);
  	  $session{doc}= $doc;
    }
    if ($doc->{style}->{theme} ne "custom" && ! -e NetScaler::Portal::Config::c->{theme_dir} . $doc->{style}->{theme}){
      delete $doc->{'style'};
      $self->filewrite($doc);
    }
    
    $self->{doc} = $doc;
    return $self->{doc};
}

sub sessiondelete {
  my $self = shift;
  my $cgi = new CGI;
  my $id = $cgi->cookie('NSC_AAAC') || "";
#  my %session;
#  tie %session, 'Apache::Session::File', $id,;
#  tied(%session)->delete;
# the next line can't possibly be correct
#  unlink "/tmp/$id"
}

sub sessionupdate {
  my $self = shift;
  my $var = shift;
  my $val = shift;
  $self->{session}->{$var} = $val;
}

sub getsessionvalue {
  my $self = shift;
  my $var = shift;
  return $self->{session}->{$var};
}

sub timestamp {
	my $self = shift;
	my $datafile = NetScaler::Portal::Config::c->{bookmark_dir} . Encode::encode('utf8', $self->{username}) . '.xml';
	return (stat($datafile))[9];
}

sub html_escape_string {
	my $msg = "";
	my @chars = split(//, @_[0]);
	foreach (@chars) {
		$msg = $msg . "&#" . ord($_) . ";";
	}
	return $msg;
}

sub fileread {
	my $self = shift;
	my $username = shift;
	my $obliterate = shift || "";
	my $datafile = NetScaler::Portal::Config::c->{bookmark_dir} . Encode::encode('utf8', $username) . '.xml';
	#if the datafile exists and the obliterate flag hasn't been set
	if (-e $datafile && !$obliterate){
        #read the file
		my $parser = XML::Simple->new();
		$self->{doc} = eval{$parser->XMLin($datafile, ForceArray=>["bookmark", "subscription", "filesystem"])};

		if(!$self->{doc}) {
	    		#if the file is corrupted or empty, create a new file for username
			$self->{doc} = $self->initdoc($username);
		}

	} else {
	    #if the file doesn't exist or obliterate has been chosen
	    #create a new default document for username
		$self->{doc} = $self->initdoc($username);
	}
	$self->{doc}->{timestamp} = (stat($datafile))[9];
	undef(@{$self->{doc}->{escbk}->{bookmark}});
	$self->{doc}->{escbk}->{bookmark} = clone($self->{doc}->{bookmarks}->{bookmark});
	foreach (@{$self->{doc}->{escbk}->{bookmark}}) {
		$_->{title} = html_escape_string($_->{title});
		$_->{descr} = html_escape_string($_->{descr});
	}
	undef(@{$self->{doc}->{escbk}->{filesystem}});
	$self->{doc}->{escbk}->{filesystem} = clone($self->{doc}->{filesystems}->{filesystem});
	foreach (@{$self->{doc}->{escbk}->{filesystem}}) {
		$_->{title} = html_escape_string($_->{title});
		$_->{descr} = html_escape_string($_->{descr});
	}
	return $self->{doc};
}

sub initdoc {
    #create new user doc with default data
	my $self = shift;
	my $username = shift;
	$self->migrate($username);		
	$self->filewrite($username, $self->{doc});
    return $self->{doc};
}

sub migrate {
    #create new user doc with data from 6.0 file if available
	my $self = shift;
	my $username = shift;
	
	my $doc = {};
	$doc->{username} = $username;
	
	# open old style javascript file
	# Sample entry
	# bmprompt[0]="starwars2"
    # bmuri[0]="http://starwars2/"
	
	$path = NetScaler::Portal::Config::c->{bookmark_dir} . Encode::encode('utf8', $username);
	if (-e $path){
	    #initialize hew hash for interim storage
	    $hooey = {};
	    #open 6.0 file
    	open(SOURCE, "< $path")
    	    or die "Couldn't open $path for reading: $!\n";
   	
    	while (<SOURCE>) {
            #remove trailing newline
    		chomp;
    	    split /=/;
    	    my $key = @_[0];
    	    # parse old format key into parts (num, item, value)
    		/bm(.*)\[(.*)\]/;
    		my $itm = $1;
    		my $num = $2;
    	    my $val = @_[1];
            #strip quotes and newline
    	    $val =~ s/"(.*)".*/$1/;

    	    #populate interim hash if value is valid text (not corrupt, malencoded bookmarks)
    	    my $n = ord($val);
            if ($n >= 0xFC  || $n >= 0xC0) { #if string contains chars that will freak out XML::SAX later
              $hooey->{$num}->{$itm}='Invalid Characters Removed';
    	    } else {
      	      $hooey->{$num}->{$itm}=$val;
    	    }
    	}    
    	close(SOURCE);
	
	    #parse through hash and populate new hash
    	foreach $key (keys %{$hooey}){
    		my $newBM = {  url => $hooey->{$key}->{'uri'},
    					 title => $hooey->{$key}->{'prompt'},
    					 descr => '',
    		};
    		if ($newBM->{url} =~ /^\\/){  #filesystem
    			push @{$doc->{filesystems}->{filesystem}}, $newBM;
    		} else { # bookmark
    			push @{$doc->{bookmarks}->{bookmark}}, $newBM;
    		}
    	}
	}
    $self->{doc} = $doc;
    return $self->{doc};
}
 
sub filewrite {
    #write data to filesystem
	my $self = shift;
	my $doc = shift;
	$doc = $self->{doc};
	my $parser = XML::Simple->new();
	$datafile = NetScaler::Portal::Config::c->{bookmark_dir} . Encode::encode('utf8', $self->{username}) . ".xml";
	if (exists $doc->{'tip'}){
       my $x =  delete $doc->{'tip'};
	}
	if (exists $doc->{'timestamp'}){
       my $x =  delete $doc->{'timestamp'};
	}
	open(FILEWRITE, "> $datafile") || die "can't open $datafile $!";
	#Lock the file to ensure Read/Write consistency
	flock(FILEWRITE, LOCK_EX);
	binmode(FILEWRITE, ':utf8');
	print FILEWRITE $parser->XMLout($doc,  xmldecl => '<?xml version="1.0" encoding="UTF-8"?>', RootName => 'user', valueattr => [ 'username', 'theme' ]);
	select((select(FILEWRITE), $| = 1)[0]); #Force flush output to disk
	close FILEWRITE;
	return 1;
}

sub doc{
	my $self = shift;
    if (@_) { $self->{doc} = shift } 
    return $self->{doc};		
}

sub username {
    #get or set username
	my $self = shift;
    if (@_) { $self->{username} = shift  } 
    return $self->{username};	
}

sub errorpage {
  my $msg = shift;
  print "Location: " . $ENV{portalLoc} . "error.html?$msg\n\n";	
  exit;  #this is overridden by Apache::Registry to use a mod_perl safe Apache::exit
}

1;	
	
