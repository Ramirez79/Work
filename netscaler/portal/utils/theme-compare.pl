#!/usr/bin/perl
use Data::Dumper;
use NetScaler::Portal::UserPrefs;

opendir THISDIR, NetScaler::Portal::Config::c->{theme_dir} or die "serious dainbramage: $!";
@allfiles = grep !/^\.\.?$/, grep !/CVS/, grep !/customizable/, grep !/nav.css/, readdir THISDIR;
closedir THISDIR;

print "Content-type: text/html\n\n";
print "<table border='1'><tr>";
foreach $theme (@allfiles){
print "<td valign='top'><pre>";
	my $t = {};
	$x = CSS::Tiny->read( NetScaler::Portal::Config::c->{theme_dir} .'/' . $theme . '/styles.css' );
	@keys = keys %{$x};
#	print "--------------------------------------------\n";
	print $theme . ": ";
	$keys = @keys;
	print $keys;
	print " defns\n";
	foreach $key (sort @keys){
	print $key . "\n";
	}
	#print @keys;
	print "\n";
#	print "Navcontainer\n";
#	print Dumper($x->{"#navcontainer"});
#	print "content\n";
#	print Dumper($x->{"#content"});
#	print "\n";
#	print "toolbar\n";
#	print Dumper($x->{"#toolbar"});
#	print "\n";
	print "\n";
	print "\n";
#	foreach $field (keys %{$fields}){
#		$modname = $field;
#		$modname =~ s/-/_/;
#		$t->{$modname} = $x->{$fields->{$field}[0]}->{$fields->{$field}[1]};
#	}
#
#	$t->{'name'} = $theme; 
#	$themes->{$theme} = $t;
#	print Dumper($x);
print "</pre></td>";
}
print "</tr></table>";