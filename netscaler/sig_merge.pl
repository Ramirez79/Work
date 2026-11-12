#!/usr/bin/perl
#
# FILE:         $Id: //depot/main/rs_111/usr.src/netscaler/scripts/sig_merge.pl#1 $
# LAST CHECKIN: $Author: build $
#               $DateTime: 2016/06/13 09:44:25 $

my $g_cur_schema_version = 5;

=for comment
 sig_merge.pl - merge 2 signatures objects
 schema history:
 5: added match_whitespace and allow fastmatch based on HeaderName and CookieName; 
    removed HTTP_RAW_HEADER, HTTP_RAW_COOKIE, HTTP_RAW_RESP_HEADER, HTTP_RAW_SET_COOKIE; added HTTP_SET_COOKIE_EVERYTHING
 4: added maxMatchLength, allow fastmatch on FieldName (107.1+)
 3: added SET_COOKIE and RESP_HEADER, RAW_RESP_HEADER, RAW_SET_COOKIE, HeaderName, CookieName, FieldName Expression
 2: added "type" (with Literal/PCRE) attribute in <Patterns>: <keyword> and <specialstring>
 1: added <RequestPatterns> and <ResponsePatterns> and Expression
 0: assume PCRE for all entries in <Patterns>

 [1] -transform: transform all rules in -src (g_transform)
 [2] -import -default: use -default as the base, merge already transformed -src (g_import)
     -src _xform_ -log: same as -import -default (g_import)
     *if -src is ver0 then do not add default rules.
=cut

use strict;
use warnings;
use Sys::Syslog;
use XML::Parser::Lite;
use Data::Dumper;
use POSIX qw(tzset);
use Switch;
$ENV{TZ} = 'America/Los_Angeles';

sub usage {
	print "Usage: merge signatures files (schema=$g_cur_schema_version)\n";
	print "  sig_merge -name <ObjectName> [-src <InputPath>|-default] -out <OutputFilePath> [-log <LogFilePath>] [-v]\n";
	print "  <ObjectName> is name of imported Signatures\n";
	print "  <Path> is path under /var/tmp/ directory\n";
	print "  <LogFilePath> list rows of ids that are new, replaced, duplicated or deleted during merge or transform\n";
	print "      header line for each section:\n"; 
	print "        >new=N\n";
	print "        >replaced=N\n";
	print "        >duplicated=N\n";
	print "        >deleted=N\n";
	print "  -v verbose\n";
	print "  e.g.:\n";
	print "   1. sig_merge -name Obj  -src my.xml -out myMerged.xml (merge Obj with -src)\n";
	print "   2. sig_merge -name Obj  -default    -out myMerged.xml (merge Obj with default Signatures)\n";
	print "   3. sig_merge -transform -src my.xml -out myNew.xml    ([1]transform -src to current schema)\n";
	print "   4. sig_merge -transform -name Obj   -out myNew.xml    ([1]transform -name to current schema)\n";
	print "   5. sig_merge -version                                 (show default Signatures version)\n";
	print "   6. sig_merge -name _xsl_x -src y -out _xsl_x        (merge -src y with -name where 'x' is the sig object)\n";
	print "   7. sig_merge -import    -src x -out y -default      ([2]replace all default rules)\n";
	print "   9. sig_merge -name nnn  -src _xform_x -out y -log z ([2]'_xform_'+log: replace all default rules)\n";
	print "Exit Status:\n";
	print "Error log file: /var/tmp/_sig_merge.err\n";
	print "  0 = Success\n";
	print "  1 = Error\n";
	exit(1);
}

my $OK = 0;
my $ERROR = 1;
my $SAMEVERSION = 2;

my $g_debug  = 0;
my $g_curTime = time();

my $SIG_MIN_LOCAL_RULE_ID = 1000000;
my $g_nextLocalRuleId = $SIG_MIN_LOCAL_RULE_ID;
my $g_LocalRuleIdUsed = 0;

my $SYS_LOG = 1;
my $testDir = "";
my $CUSTOMSETTINGS_DEFAULT_FILE_PATH = "$testDir/netscaler/default_signatures.xml";
my $UPDATED_SIGNATURE_FILE_PATH = "$testDir/nsconfig/updated_signatures.xml";
my $CUSTOMSETTINGS_DIR = "$testDir/var/download/custom/";
my $AS_IMPORT_LOCAL_SOURCE_PATH = "$testDir/var/tmp/";

my $NODE_SIGNATURESFILE = "SignaturesFile";
my $NODE_OLD_ROOT = "AppFwCustomSettings";

my $NODE_SIGNATURES     = "Signatures";
my $NODE_SIGNATURERULE  = "SignatureRule";
my $NODE_PATTERNS       = "Patterns";	# for injection and xss
my $NODE_COMMENT        = "Comment";	# Signatures comment
my $NODE_LOGSTRING      = "LogString";
my $NODE_PATTERNLIST    = "PatternList";
my $NODE_REQUEST_PATS   = "RequestPatterns";
my $NODE_RESPONSE_PATS  = "ResponsePatterns";
my $NODE_PATTERN        = "Pattern";	# for PatternList
my $NODE_INJECTION      = "injection";
my $NODE_XSS            = "xss";
my $NODE_KEYWORD        = "keyword";
my $NODE_SPECIAL_STRING = "specialstring";

my $ATTR_RULEID   = "id";
my $ATTR_VERSION  = "version";
my $ATTR_SCHEMA   = "schema_version";
my $ATTR_CATEGORY = "category";
my $ATTR_ACTIONS  = "actions";
my $ATTR_TYPE     = "type";
my $ATTR_ENABLE   = "enabled";

my $TYPE_LITERAL  = "Literal";
my $TYPE_PCRE     = "PCRE";
my $TYPE_FASTMATCH= "fastmatch";

my $TAG_NAME     = "name";	# node name
my $TAG_ATTRS    = "attrs";	# node attributes
my $TAG_DATA     = "data";	# node data
my $TAG_CHILDREN = "children"; # children of this node
my $TAG_HASH     = "hash";	# hashed string for use in isRedefinedSigRule(), used at the root of each rule.
my $TAG_PARENT   = "parent";# node parent, added after prepare2MergePatterns()

my $AREA_HTTP_RESP_BODY  = "AREA_HTTP_RESP_BODY";
my $AREA_HTTP_POST_BODY  = "HTTP_POST_BODY";
my $AREA_HTTP_FORM_FIELD = "HTTP_FORM_FIELD";

# signatures object:
my $SIG_TOP   = "top";	# reference to <SignaturesFile>
my $SIG_SIGS  = "sigs";	# reference to <Signatures> node
my $SIG_PATS  = "pats";	# reference to <Patterns> node
my $SIG_COMMENT  = "comment";	# signatures comment
my $SIG_RULES = "rules";# reference to array of <SignatureRule>'s

my $g_verbose = 0;
my $g_out = "";
my $g_outPath = "";
my $g_log = "";
my $g_logPath = "";
my $g_name = "";
my $g_namePath = "";
my $g_src = "";
my $g_inputPath = "";
my $g_srcPath = "";
my $g_default = 0;
my $g_defaultPath = "";
my $g_syslog_level = "LOG_DEBUG";
my $g_version = 0;
my $g_import = 0;
my $g_sig_version = 0;
my $g_schema_version = 0;

my @g_stack = ();
my $g_parser = "";
my %g_rules1;	# for original and merged rules
my %g_rules2;	# sig rules for the second file
my %g_masterRules;	# to be used by isRedefinedSigRule()

my %g_patXpaths;	# for use in mergePatterns()
my $g_pats1;		# for use in doMergePatterns() pointing to $obj1->{$SIG_PATS}

# rule ids to be output to -log
my @g_newRules = ();
my @g_replacedRules = ();
my @g_dupRules = ();
my %g_delRules;
my @g_errors = ();

my %g_old;
my $g_errorPath = $AS_IMPORT_LOCAL_SOURCE_PATH."_sig_merge.err";

my $g_transform    = 0;	# -transform (transform all rules unless src:_xform_ is specified)
my $g_is_log       = 0;	# -log
my $g_is_xform     = 0;	# -src:_xform_ operation - transform only the user-defined rules
my $g_is_mergexslt = 0; # -src:_xsl_ (merge with xslt-ed file)
my $g_is_replace_defaults = 0;	# replace default rules

# don't touch the followings as they must be synced with GUI:
my $g_XFORM = "_xform_";	# prefix name used to mean -transform only the user-defined rules
my $g_MERGEXSLT = "_xsl_";	# prefix name used to mean merge with xslt-ed file

# Get the latest version of default signature file among two(default/updated).
# Get current schema_version also (which should be the same)
sub getLatestVersion {
	my $updated_signature_version = 0;
	my $default_signature_version = 0;
	if (-e $UPDATED_SIGNATURE_FILE_PATH) {
		open (FILE, "< $UPDATED_SIGNATURE_FILE_PATH");
		while (<FILE>) {
			if (/<SignaturesFile.* version="(\d+)"/) {
				$updated_signature_version = $1;
				if (/schema_version="(\d+)"/) {
					$g_schema_version = $1;
				}
				debug("updated_signature_version=$updated_signature_version, g_schema_version=$g_schema_version");
				last;
			}
		}
		close(FILE);
	}	
	if (-e $CUSTOMSETTINGS_DEFAULT_FILE_PATH) {
		open (FILE, "< $CUSTOMSETTINGS_DEFAULT_FILE_PATH");
		while (<FILE>) {
			if (/<SignaturesFile.* version="(\d+)"/) {
				$default_signature_version = $1;
				if (/schema_version="(\d+)"/) {
					$g_schema_version = $1;
				}
				debug("default_signature_version=$default_signature_version, g_schema_version=$g_schema_version");
				last;
			}
		}
		close(FILE);
	}
	debug("default_signature_version:updated_signature_version=$default_signature_version:$updated_signature_version");
	if ($default_signature_version >= $updated_signature_version) {
		debug(">= $UPDATED_SIGNATURE_FILE_PATH");
		if (-e $UPDATED_SIGNATURE_FILE_PATH) {
			if (!unlink($UPDATED_SIGNATURE_FILE_PATH)) {
				debug("Cannot unlink $UPDATED_SIGNATURE_FILE_PATH");
			} else {
				debug("unlinked $UPDATED_SIGNATURE_FILE_PATH");
			}
		}
		$g_defaultPath = $CUSTOMSETTINGS_DEFAULT_FILE_PATH;
		$g_sig_version = $default_signature_version;
	} else {
		$g_defaultPath = $UPDATED_SIGNATURE_FILE_PATH;
		$g_sig_version = $updated_signature_version;
	}
	debug("sig-version: $g_sig_version sig-schema: $g_schema_version");
	return $g_sig_version;
}

# Handling command line argument
sub getOption {
	if ($#ARGV < 0 ) {
		usage();
	}
	my $needOutput = 1;
	log_args();
	LOOP: while ($#ARGV >= 0) {
		my $para = shift(@ARGV);
		switch ($para) {
		case "-h" {usage()}
		case "-v" {$g_verbose = 1;}
		case "-out" {
			$g_out = shift(@ARGV);
			$g_outPath = $AS_IMPORT_LOCAL_SOURCE_PATH.$g_out;
		} case "-log" {
			$g_log = shift(@ARGV);
			$g_logPath = $AS_IMPORT_LOCAL_SOURCE_PATH.$g_log;
			$g_is_log = 1;
		} case "-name" {
			$g_name = shift(@ARGV);
			if ($g_name =~ /^$g_MERGEXSLT/) {
				$g_is_mergexslt = 1;	# merge with xslt-ed file
				$g_name = substr($g_name, length($g_MERGEXSLT));	# get the real name 
				log_error("> xsl name=$g_name");
				# continue to treat this as a merge...
			} elsif ($g_name =~ /^$g_XFORM/) { # should rely on -src instead of the name
				next LOOP;	# continue with next argument
			}
			$g_namePath = $CUSTOMSETTINGS_DIR.$g_name;
			if (!(-e $g_namePath)) {
				print_error("'$g_name' Signatures does not exist.");
				return $ERROR;
			}
			#if ($g_outPath ne "") {
			#	$g_outPath = $g_namePath;	# could be used as output if -out is not specified
			#}
		} case  "-src" {
			$g_src = shift(@ARGV);
			if ($g_src =~ /^$g_XFORM/) {
				$g_is_xform = 1;	# transform only the user-defined rules
			}
			$g_srcPath = $AS_IMPORT_LOCAL_SOURCE_PATH.$g_src;
			$g_inputPath = $g_srcPath;
			if (!(-e $g_srcPath)) {
				print_error("Source '$g_srcPath' file does not exist.");
				return $ERROR;
			}
		} case "-default" {
			$g_default = 1;
			$g_sig_version = getLatestVersion();
			debug("default: $g_defaultPath");
		} case "-version" {
			$g_version = 1;
			$g_sig_version = getLatestVersion();
			$needOutput = 0;	# output is not mandatory for -version
		} case "-transform" {
			$g_transform = 1;	# [1]transform all rules in -src
		} case "-import" {
			$g_import = 1;		# [2]replace all default rules
		} else {
			print "Unknown parameter: $para\n";
			usage();
		}}
	}
	if ($g_is_xform && $g_is_log) {
		$g_import = 1;	# [2] same as -import -default
		if (!$g_default) {
			$g_default = 1;	# will merge with default signatures
			$g_sig_version = getLatestVersion();	# will set $g_defaultPath
		}
	}
	my $status = $OK;
	if ($needOutput && !$g_out) {
		print_error("Missing -out parameter");
		$status = $ERROR;
	} elsif ($g_transform) {
		# for transform:
		if (!$g_name && !$g_src){
			print_error("Missing either -name or -src for -transform");
			$status = $ERROR;
		}
	} elsif ($g_version) {
		# for -version:
		if ($g_out || $g_name || $g_src || $g_default) {
			print_error("Too many parameters for -version");
			$status = $ERROR;
		}
	} elsif ($g_import) {
		# for -import:
		if (!$g_src || !$g_default) {
			print_error("Must use -src and -default to import");
			$status = $ERROR;
		}
	} else {
		# for merge:
		if (!$g_name) {
			print_error("Missing -name for merge");
			$status = $ERROR;
		} elsif (!$g_is_xform && (($g_src && $g_default) || (!$g_src && !$g_default))) {
			print_error("Must use either -src or -default to merge");
			$status = $ERROR;
		}
	}
	return $status;
}

# for merge: validate if schema is the same and that obj2 is a newer version
# returns OK; or ERROR if version or schema are different;
sub validateSigVersions {
	my $obj1 = shift();
	my $obj2 = shift();
	my $top1 = $obj1->{$SIG_TOP};
	my $top2 = $obj2->{$SIG_TOP};

	my $schema1 = getSchemaVersion($top1);
	my $schema2 = getSchemaVersion($top2);
	debug("schema1=$schema1, schema2=$schema2");
	if ($schema1 != $schema2) {
		my $msg = "Different schema versions (existing:$schema1,  new:$schema2)";
		print_error($msg);
		return $ERROR;
	}
	my $ver1 = getVersion($top1);
	my $ver2 = getVersion($top2);	# -default
	debug("ver1=$ver1, ver2=$ver2");
	log_error("> ver1=$ver1, ver2=$ver2");
	if (!$g_default) {
		# for user-defined object:
		if ($g_is_mergexslt && $ver1 > $ver2) {
			# need to set top2 as ver1:
			setAttributeValue($top2, $ATTR_VERSION, $ver1);
			$ver2 = getVersion($top2);	# -default
			log_error("> xslt ver2=$ver2");
		}
		return $OK;
	}
	if ($g_is_replace_defaults) {
		return $OK;	# no need to compare; just replace the entire def rules
	}
	# for -default, object must have lower version than default_signature
	if ($ver1 > $ver2) {
		my $msg = "Signatures is already up-to-date (existing:$ver1, new:$ver2)";
		log_error($msg);
        return $SAMEVERSION;			# not considered as error for merge
	}
	return $OK;
}

# merge obj2 into obj1
# returns reference of merged objects
sub mergeSigs {
	my $obj1 = shift();
	my $obj2 = shift();
	my %rules1 = %{$obj1->{$SIG_RULES}};
	my %rules2 = %{$obj2->{$SIG_RULES}};
	my $for_ver0 = 0;

	my $ver1 = getObjVersion($obj1);
    if ($g_import) {
		if ($ver1 == 0) {
			$for_ver0 = 1;	# merge to ver0 object: maintains ver0 and do not add default rules
		}
	}
	# merge rules2 into rules1:
    # 1.Loop through all the rules2,  if (g_import and default rule) add this one to rules1;
    #       and if it exists already then replace it and copy the actions and enabled flag.
    # 2.Else do the normal merge process.
	my ($id, $newRule, $oldRule);
	# merge rules2 into rules1
	LOOP:while(($id,$newRule) = each %rules2) {
        # 1. if (g_import and default rule) add this one to rules1;
        #       and if it exists already then replace it and copy the actions and enabled flag.
		#log_error("> id=$id");
        if ($g_import && $id < $SIG_MIN_LOCAL_RULE_ID) {
           	if (exists($rules1{$id})) {
				#log_error("> copyActions: id=$id, actions=".getAttributeValue($newRule, $ATTR_ACTIONS).", enable=".getAttributeValue($newRule, $ATTR_ENABLE));
               	copyActions($newRule, $rules1{$id});  # copy actions to rules1 (-default)
               	copyEnable($newRule, $rules1{$id});   # copy enabled flag to nrules1
           	}
           	# not needed: $rules1{$id} = $newRule;  # replace with new rule
            next LOOP;
        }
		# xform: user-defined rules only: ------
        # 3.Else do the normal merge process.
		if (!exists($rules1{$id})) {
			# --- for new id ---
			if (!isRedefinedSigRule($newRule, $id)) {
				$rules1{$id} = $newRule;	# add new rule
				push(@g_newRules, $id);
			} else {
				delete($rules1{$id});		# delete this duplicated new rule
				push(@g_dupRules, $id);
				debug("Delete duplicated new rule: $id");
			}
		} elsif (getVersion($newRule) > getVersion($rules1{$id})) {
			# --- for same id with higher version ---
			if (!isRedefinedSigRule($newRule, $id)) {
				#log_error(">> copyActions: id=$id, actions=".getAttributeValue($rules2{$id}, $ATTR_ACTIONS).", enable=".getAttributeValue($rules2{$id}, $ATTR_ENABLE));
				copyActions($rules2{$id}, $newRule);	# copy actions to newRule
				copyEnable($rules2{$id}, $newRule);	# copy enabled flag to newRule
				$rules1{$id} = $newRule;	# replace with new rule
				push(@g_replacedRules, $id);
				debug("Replaced rule with higher version: $id");
			} else {
				delete($rules1{$id});		# delete this duplicated new rule
				push(@g_dupRules, $id);
				debug("Delete duplicated rule with higher version: $id");
			}
		} else {
			$g_old{$id} = 1;
		}
	}
	debug("# new rules  =".scalar(@g_newRules));
	debug("# replaced   =".scalar(@g_replacedRules));
	debug("# duplicated =".scalar(@g_dupRules));
	debug("# old rules  =".keys(%g_old));
	debug("# merged     =".keys(%rules1));
	debug("# deleted rules  =".keys(%g_delRules));
	$obj1->{$SIG_RULES} = \%rules1;	# replace with the merged rules
	# do not change base version (-default) for -import:
	if (!$g_import) {
		$obj1->{$SIG_TOP} = $obj2->{$SIG_TOP};	# use the new root node for normal merge
	}
	if (exists($obj2->{$SIG_COMMENT})) {
		$obj1->{$SIG_COMMENT} = $obj2->{$SIG_COMMENT};	# use the new comment node
	}
	$ver1 = getObjVersion($obj1);
	log_error("> merged version=$ver1");
	# merge <Patterns> into obj1:
	mergePatterns($obj1, $obj2);
	return $obj1;
}

# copy old actions to new rule:
sub copyActions {
	my $old = shift();
	my $new = shift();

	my $attrs;
	if (exists($new->{$TAG_ATTRS})) {
		$attrs = $new->{$TAG_ATTRS};
		delete($attrs->{$ATTR_ACTIONS});
	} else {
		$attrs = {};	# reference to empty hash
	}
	$attrs->{$ATTR_ACTIONS} = getAttributeValue($old, $ATTR_ACTIONS);
}

# copy old enabled flag to new rule:
sub copyEnable {
	my $old = shift();
	my $new = shift();

	my $attrs;
	if (exists($new->{$TAG_ATTRS})) {
		$attrs = $new->{$TAG_ATTRS};
		delete($attrs->{$ATTR_ENABLE});
	} else {
		$attrs = {};	# reference to empty hash
	}
	$attrs->{$ATTR_ENABLE} = getAttributeValue($old, $ATTR_ENABLE);
}

# Note: group patterns with child nodes as data
my %g_patternGroups = (
	'injection/transformrules/transform/' => 1
	);

# Note: all patterns can be sorted unless specified below in xpath format:
my %g_unsortedPatterns = (
	'injection/transformrules/transform/' => 1
	);

# merge <Patterns> nodes: $pat2 into $pat1
sub mergePatterns {
	my $obj1 = shift();
	my $obj2 = shift();

	my $pat1 = $obj1->{$SIG_PATS};
	my $pat2 = $obj2->{$SIG_PATS};

	if (!defined($pat2) || !exists($pat2->{$TAG_CHILDREN})) {
		return;	# nothing to merge from pat2
	}
	if (!defined($pat1) || !exists($pat1->{$TAG_CHILDREN})) {
		$obj1->{$SIG_PATS} = $pat1;	# use all pat2
		return;
	}
	# create %g_patXpaths for all pattern entries in $pat1
	prepare2MergePatterns($pat1, '');
	#print Dumper(\%g_patXpaths);

	$g_pats1 = $pat1;	# make this globally accessible in doMergePatterns()
	doMergePatterns($pat2, '');
}

# merge given pat2 entries into $g_pats1 via %g_patXpaths:
# only append new entries into $g_pats1
# will add new directory if not exist
# e.g. injection/keyword/:select
sub doMergePatterns {
	my $node = shift();
	my $xpath = shift();

	if (!exists($node->{$TAG_CHILDREN})) {
		return;
	}
	LOOP: for my $child (@{$node->{$TAG_CHILDREN}}) {
		my $xpath2 = $xpath.$child->{$TAG_NAME}.'/';
		my $isGroup = exists($g_patternGroups{$xpath2});
		if (!$isGroup && exists($child->{$TAG_CHILDREN})) {
			# drill down:
			doMergePatterns($child, $xpath2);
			next LOOP;	# continue with next child
		}
		# no more child or is a group:
		$xpath2 .= ':';
		if ($isGroup) {
			$xpath2 .= getTreeHash($child);
		} elsif (!exists($child->{$TAG_DATA})) {		# data is always at the end except for groups
			next LOOP;	# ignore empty data
		} else {
			$xpath2 .= $child->{$TAG_DATA};
		}
		if (!exists($g_patXpaths{$xpath2})) {
			# append this new pattern to parent of pat1:
			my $parent = getParentNodeFromXpath($g_pats1, getParentXpath($xpath2));
			$child->{$TAG_PARENT} = $parent;		# point to new parent
			if (!exists($parent->{$TAG_CHILDREN})) {
				my @newChildren = ($child);
				$parent->{$TAG_CHILDREN} = \@newChildren;	# first child
			} else {
				push(@{$parent->{$TAG_CHILDREN}}, $child);	# append element to array
			}
		}
	}
}


sub getParentXpath {
	my $xpath = shift();
	# extract parent path: e.g. injection/
	if ($xpath =~ m/^(.+\/)([^\/]+)\/:(.+)$/) {
		return $1;
	} else {
		return '';
	}
}

# given parent xpath, returns parent node of $g_pats1.
# may create intermidate directory nodes if needed
# e.g. injection/
sub getParentNodeFromXpath {
	my $node  = shift();
	my $xpath = shift();	# parent xpath

	#print "=> xpath=$xpath\n";
	if ($xpath eq '') {
		#print "  found!\n\n";
		return $node;	# returns parent node
	}
	# traverse the xpath to find the closest parent node:
	my ($newNode, $nodeName, $tail);
	if ($xpath =~ m/^([^\/]+)\/(.*)$/) {
		$nodeName = $1;
		$xpath = $2;	# tail xpath
	} else {
		print_error("Empty node!");
		return $node;	# returns parent node (won't happen)
	}
	#print "  nodeName=$nodeName, xpath=$xpath\n";
	# create a new empty node if not there:
	if (!exists($node->{$TAG_CHILDREN})) {
		$newNode = createNewNode($node, $nodeName);
		my @newChildren = ($newNode);
		$node->{$TAG_CHILDREN} = \@newChildren;	# create first element of array
		#print "  create new node: $nodeName\n";
		return getParentNodeFromXpath($newNode, $xpath);	# keep creating new parent nodes until done
	}
	# match the parent node if any:
	foreach (@{$node->{$TAG_CHILDREN}}) {
		my $child = $_;
		if ($child->{$TAG_NAME} eq $nodeName) {
			return getParentNodeFromXpath($child, $xpath);	# keep search for parent nodes until done
		}
	}
	# no such child, need to create a new parent node:
	$newNode = createNewNode($node, $nodeName);
	push(@{$node->{$TAG_CHILDREN}}, $newNode);	# append element to array
	return getParentNodeFromXpath($newNode, $xpath);	# keep creating new parent nodes until done
}

sub createNewNode {
	my $parent = shift();
	my $nodeName = shift();
	my %newNode = (
		$TAG_NAME   => $nodeName,
		$TAG_PARENT => $parent
	);
	return \%newNode;
}

# create %g_patXpaths for all pattern entries in $g_pats1:
# e.g. injection/keyword/:select
# for groups: injection/transformrules/transform/:transform|from|a|to|b
sub prepare2MergePatterns {
	my $node = shift();
	my $xpath = shift();

	if (!exists($node->{$TAG_CHILDREN})) {
		return;
	}
	LOOP: for my $child (@{$node->{$TAG_CHILDREN}}) {
		my $xpath2 = $xpath.$child->{$TAG_NAME}.'/';
		my $isGroup = exists($g_patternGroups{$xpath2});
		if (!$isGroup && exists($child->{$TAG_CHILDREN})) {
			# drill down:
			prepare2MergePatterns($child, $xpath2);
			next LOOP;	# continue with next child
		}
		# no more child or is a group:
		$xpath2 .= ':';
		if ($isGroup) {
			$xpath2 .= getTreeHash($child);
		} elsif (exists($child->{$TAG_DATA})) {		# data is always at the end except for groups
			$xpath2 .= $child->{$TAG_DATA};
		}
		$child->{$TAG_PARENT} = $node;	# remember the parent node
		$g_patXpaths{$xpath2} = $child;	#
	}
}

# check all rules in %g_rules1 for duplicated rule with the given new one:
# ignore rule with the same id in the existing list of rules
sub isRedefinedSigRule {
	my $newRule = shift();
	my $newId = shift();
	my ($id, $rule);
	my $newHash = $newRule->{$TAG_HASH};
	#debug("\n===> newId=$newId: $newHash");
	while(($id,$rule) = each %g_masterRules) {
		#debug("       id=$id");
		if ($id != $newId && $newHash eq $rule->{$TAG_HASH}) {
			#debug("       *** id=$id");
			return 1;	# is redefined rule
		}
	}
	return 0;
}

# *must call postGetRuleId() if local rule id is used or not
# returns the next available user-defined rule id if empty
sub getRuleId {
	my $rule = shift();
	my $id = getAttributeValue($rule, $ATTR_RULEID);
	if ($id eq '') {
		# use user-defined rule id
		$id = getNextLocalRuleId();
		setAttributeValue($rule, $ATTR_RULEID, $id);
		$g_LocalRuleIdUsed = 1;	# set the flag for postGetRuleId() call later
		debug("assigned Local rule id=$id");
	} elsif ($id >= $SIG_MIN_LOCAL_RULE_ID) {
		debug("explicit Local rule id=$id");
	}
	return $id;
}
# return the next smallest, unused local rule id
# $g_rules1 and $g_rules2 contains all the current rule ids in use
sub getNextLocalRuleId() {
	while ($g_rules1{$g_nextLocalRuleId} || $g_rules2{$g_nextLocalRuleId}) {
		$g_nextLocalRuleId++;
	}
	return $g_nextLocalRuleId;
}
# this is to try avoid having holes for local rule ids being assigned
sub postGetRuleId() {
	if ($g_LocalRuleIdUsed) {
		$g_nextLocalRuleId++;	# prepare for next new local rule id
		$g_LocalRuleIdUsed = 0;	# clear the flag
	}
}

sub getObjVersion {
	my $obj1 = shift();
	my $top1 = $obj1->{$SIG_TOP};
	return getVersion($top1);
}

sub getVersion {
	my $rule = shift();
	my $str = getAttributeValue($rule, $ATTR_VERSION);
	if ($str eq '') {
		return 0;	# assume version 0
	} else {
		return $str;
	}
}
sub getSchemaVersion {
	my $top = shift();
	my $str = getAttributeValue($top, $ATTR_SCHEMA);
	if ($str eq '') {
		return 0;	# assume version 0
	} else {
		return $str;
	}
}
# get the attribute value from this node
# returns '' if not found
sub getAttributeValue {
	my $rule = shift();
	my $key  = shift();
	if (exists($rule->{$TAG_ATTRS})) {
		my %attrs = %{$rule->{$TAG_ATTRS}};
		if (exists($attrs{$key})) {
			return $attrs{$key};
		}
	}
	return '';	# undefined
}
# given a reference of a node, set the attribute key with the given value
sub setAttributeValue {
	my $rule = shift();	# reference to node
	my $key  = shift();
	my $val  = shift();
	my $attrs = {};	# reference to empty hash
	if (exists($rule->{$TAG_ATTRS})) {
		$attrs = $rule->{$TAG_ATTRS};
	}
	$attrs->{$key} = $val;
	$rule->{$TAG_ATTRS} = $attrs;	# use reference
}

# returns 1 if node has attribute key
sub hasAttribute {
	my $node = shift();
	my $key  = shift();
	if (exists($node->{$TAG_ATTRS})) {
		my %attrs = %{$node->{$TAG_ATTRS}};
		if (exists($attrs{$key})) {
			return 1;
		}
	}
	return 0;
}

# returns 1 if node has attribute key and rename it
sub renameAttribute {
	my $node = shift();
	my $oldKey  = shift();
	my $newKey  = shift();
	if (exists($node->{$TAG_ATTRS})) {
		my %attrs = %{$node->{$TAG_ATTRS}};
		if (exists($attrs{$oldKey})) {
			$attrs{$newKey} = $attrs{$oldKey}; 
			delete $attrs{$oldKey};
			$node->{$TAG_ATTRS} = \%attrs;
			return 1;
		}
	}
	return 0;
}

sub getArgString {
	my @argvs = @ARGV;
	my $str = "";
	while ($#argvs >= 0) {
		my $para = shift(@argvs);
		$str .= "$para ";
	}
	return $str;
}
sub print_error {
	my $msg = shift();
	log_error($msg);	# remember this error
	print "ERROR: $msg\n";
}
sub log_error {
	my $msg = shift();
	push(@g_errors, $msg);	# remember this error
}
sub print_info {
	my $msg = shift();
	if ($g_verbose) {
		print "+ INFO: $msg\n";
	}
}

sub debug {
	my $msg = shift();
	if ($g_verbose) {
		print "+ $msg\n";
	}
	if ($g_debug) {
		flushError("+ $msg");
	}
}

sub logMessage {
	my $msg = shift();
	print "$msg\n";
	if ($SYS_LOG) {
		syslog $g_syslog_level,"%s",$msg;
	}
}

$SIG{'INT'} = sub {
	debug('Ctrl-C is pressed');
};

my $g_ends = 0;
sub initParser {
	$g_parser = new XML::Parser::Lite;
	$g_parser->setHandlers(
		Init => sub { 
			@g_stack = ();
			debug("Init");
		},
		Start => sub { 
			shift(); 
			my $name = shift();
			my %tag = (
				$TAG_NAME  => $name
			);
			my %attrs = ();
			# attributes are in key-val pairs:
			while (scalar(@_) > 1) {
				my $key = shift();
				my $val = shift();
				$attrs{$key} = $val;
			}
			# add attrs only if it is not empty:
			if (keys(%attrs)) {
				$tag{$TAG_ATTRS} = \%attrs,
			}
			push(@g_stack, \%tag);
		},
		Char  => sub { 
			shift();
			my $data = join('',@_);
			#$data = trim($data);	# cannot trim during parse!
			my $ii = scalar(@g_stack);
			$g_stack[$ii-1]{$TAG_DATA} = $data;
		},
		End   => sub { 
			shift(); 
			my $ii = scalar(@g_stack);
			
			# root node has no parent:
			$g_ends++;
			if ($ii > 1) {
				my @children;
				my $curTag = $g_stack[$ii-1];
				if (exists($g_stack[$ii-2]->{$TAG_CHILDREN})) { 
					@children = @{$g_stack[$ii-2]->{$TAG_CHILDREN}};
					push(@children, $curTag);	# append last tag to parent's children
				} else {
					@children = ($curTag);	# first time define
				}
				$g_stack[$ii-2]->{$TAG_CHILDREN} = \@children;
				pop(@g_stack);
			}
		},
		Final => sub { 
			debug("Final:");
		},
	);
}

sub trim {
	my $str = shift();
	$str =~ s/^\s+//;
	$str =~ s/\s+$//;
	return $str;
}

sub getAttributeString {
	my $tag = shift();
	my $attrStr = "";
	if (!exists($tag->{$TAG_ATTRS})) {
		return $attrStr;
	}
	my $attrs = \%{$tag->{$TAG_ATTRS}};
	foreach my $key (sort(keys %{$attrs})) {
		$attrStr .= " $key=\"$attrs->{$key}\"";
	}
	return $attrStr;
}

# returns reference of signatures object with hashed signatures rules
# returns $ERROR if error
sub parseSigPath {
	my $path = shift();
	my $fileRules = shift();	# reference to hold all the rules of this file
	my $isDefault = shift();	# this obj is -default
	my $isTransform = 1;

	log_error("--> parseSigPath: $path; isDefault=$isDefault");
	debug("parseSigPath: $path; isDefault=$isDefault");
	my $text = readFile($path);
	debug("Begin parse");
	$g_parser->parse($text);	# output to g_stack
	debug("Done parse");
	my $count = scalar(@g_stack);
	if ($count == 0) {
		print_error("Empty tree");
		return $ERROR;	# not a legal sig file
	}
	# prepare to return sig obj:
	debug("prepare to return sig obj");
	my $top = \%{$g_stack[0]};
	my $sigs = ();		# reference to array
	my $pats = ();
	my %sigObj = (
		$SIG_TOP  => $top,		# all are references
		$SIG_SIGS => $sigs,
		# $SIG_COMMENT => $comment,	# don't define it
	);
	my $my_schema = 0;
	my $my_version = 0;
	$my_schema = getSchemaVersion($top);
	$my_version = getVersion($top);
	log_error("> version=$my_version, schema=$my_schema");
	debug("version=$my_version, schema=$my_schema");
	if (!$isDefault) {
		# validate schema first:
		if ($g_schema_version == 0) {
			getLatestVersion();	# get latest schema version from default sig
		}
		if ($my_schema > $g_schema_version) {
			print_error("Input file schema version is too new ($my_schema)");
			return $ERROR;
		}
		if ($g_schema_version == $my_schema) {
			debug("!!Schema version is already up-to-date ($my_schema)");
			$isTransform = 0;	# no need to transform from now on...
		}
		my $rootName = $top->{$TAG_NAME};
		# change old root <AppFwCustomSettings> to <SignaturesFile>:
		if ($rootName eq $NODE_OLD_ROOT) {
			$rootName = $NODE_SIGNATURESFILE;
			$top->{$TAG_NAME} = $rootName;	# use new root name
			debug("use new root name: $rootName");
		}
		if ($rootName ne $NODE_SIGNATURESFILE) {
			print_error("Input file is not a Signatures object.");
			return $ERROR;	# not a sig file!
		}
	}
	if (!exists($top->{$TAG_CHILDREN})) {
		debug("Input file has no child nodes.");
		my @rows = ();
		$top->{$TAG_CHILDREN} = \@rows;	# make sure it has an empty child
	}
	# clean up all empty node data:
	if (!$g_import) {
		removeEmptyData($top);	# no need to do cleanup for -import
	}

	if (!$isDefault && $isTransform && $my_schema <= 1) {
		doTransform1(\%sigObj);	# do basic transformation (for schema <= 1 only)
	}
	# look for <Signatures> and <Patterns>:
	my @childs = @{$top->{$TAG_CHILDREN}};
	foreach (@childs) {
		my $node = $_;
		if ($node->{$TAG_NAME} eq $NODE_SIGNATURES) {
			debug("Found <$NODE_SIGNATURES>");
			$sigObj{$SIG_SIGS} = $node;
			$sigs = $node;
		} elsif ($node->{$TAG_NAME} eq $NODE_PATTERNS) {
			debug("Found <$NODE_PATTERNS>");
			$sigObj{$SIG_PATS} = $node;
			$pats = $node;
		} elsif ($node->{$TAG_NAME} eq $NODE_COMMENT) {
			debug("Found <$NODE_COMMENT>");
			$sigObj{$SIG_COMMENT} = $node;
		}
	}
	if (!$isDefault && $isTransform) {
		doTransform2(\%sigObj, $my_schema);	# do secondary transformation
	}
	# --- create SIG_RULES array ---:
	# must handle empty signatures also:
	my $is_import_src = ($g_import && !$isDefault)?1:0;	# processing src file: no need to hash def rules
	my $is_import_default = ($g_import && $isDefault)?1:0;	# processing src file: no need to hash def rules
	my $is_check_redefine = ($g_transform || $g_import)?0:1; # redefined rules check will be done in -import step during merge
	my $is_del_raws = ($g_cur_schema_version >= 5 && $my_schema < 5)?1:0; # for obsolete RAW_* locations
	log_error("> g_import=$g_import, isDefault=$isDefault, is_import_src=$is_import_src, is_import_default=$is_import_default, is_check_redefine=$is_check_redefine");

	if ($sigs && exists($sigs->{$TAG_CHILDREN})) {
		my @sigRules = @{$sigs->{$TAG_CHILDREN}};
		LOOP2: foreach (@sigRules) {
			my $rule = $_;	# rule is a reference
			my $id = getRuleId($rule);	# will call postGetRuleId() below if new id is used
			#if ($is_import_src){log_error(">> id=$id");}
			if ($id < $SIG_MIN_LOCAL_RULE_ID) {
				if ($is_import_src) {	# rules already transformed for -import
					#log_error(">> saved id=$id");
					$fileRules->{$id} = $rule;	# just save this def rule for -import on the -src
					next LOOP2;
				}
			}
			# for -transform or user-defined rules only with -import
			my $hash = getRuleHash($rule);
			$rule->{$TAG_HASH} = $hash;	# save it for use in isValidFastmatch
			if ($g_import) {
				$fileRules->{$id} = $rule;
				next LOOP2;				# file already transformed for -import
			}
			# for -transform:
			# remove empty rules (without <Pattern>):
			if ($hash !~ m/\|Pattern\|/) {
				debug("*Removed empty rule: $id");
				next LOOP2;
			}
			#debug("hash=$hash");
			# schema < 5:
			# removed HTTP_ANY,HTTP_RAW_HEADER, HTTP_RAW_COOKIE, HTTP_RAW_RESP_HEADER, HTTP_RAW_SET_COOKIE
			# |PatternList||RequestPatterns||Pattern| type="fastmatch"|Location| area="HTTP_FORM_FIELD"|FieldName| keep_whitespace="yes" type="LITERAL"|
			#  ^uid$|Match| keep_whitespace="yes" type="LITERAL"|0|28|root|29||
			if (!$isDefault) {
				#log_error(">> filter id=$id");
				if ($is_del_raws) {
				  if (index($hash, "area=\"HTTP_RAW_HEADER\"") != -1 ||
				    index($hash, "area=\"HTTP_ANY\"") != -1 ||
				    index($hash, "area=\"HTTP_RAW_COOKIE\"") != -1 ||
				    index($hash, "area=\"HTTP_RAW_RESP_HEADER\"") != -1 ||
				    index($hash, "area=\"HTTP_RAW_SET_COOKIE\"") != -1) {
					$g_delRules{$id} = "RAW";	#$rule;
					debug("*Removed rule $id with HTTP_RAW_HEADER etc.");
					next LOOP2;
				  }
				}
				# check if there is any missing <Location>
				my $count1 = () = $hash =~ /\|Pattern\|/g;	
				my $count2 = () = $hash =~ /\|Location\|/g;	
				if ($count1 != $count2) {
					$g_delRules{$id} = "NoLocation";	#$rule;
					debug("*Removed rule $id with missing Location");
					next LOOP2;
				}
			}
			my $errmsg;
			if (!$isDefault && !isValidFastmatch($rule, \$errmsg)) {
				$g_delRules{$id} = "fastmatch";	#$rule;
				debug("*Removed rule $id with invalid fastmatch: $errmsg");
				next LOOP2;
			}
			if ($is_check_redefine) {
				#log_error(">> check_redefine id=$id");
				if (isRedefinedSigRule($rule, $id)) {
					push(@g_dupRules, $id);
					debug("Delete redefined rule: $id");
					next LOOP2;
				} else {
					$g_rules1{$id} = $rule;	# add new rule
					push(@g_newRules, $id);
				}
			}
			debug("id=$id, len=".scalar(keys(%g_rules1)).",hash=$hash");
			$fileRules->{$id} = $rule;	# good rule
			postGetRuleId();		# check if increment of local rule id is needed
		}
		debug("Total #rules=".scalar(keys(%$fileRules)));
		debug("Total #rules removed=".scalar(keys(%g_delRules)));
	}
	$sigObj{$SIG_RULES} = $fileRules;
	if (!$isDefault) {
		# make sure the schema and version attributes are set:
		if ($my_schema != $g_schema_version) {
			debug("setAttributeValue $ATTR_SCHEMA: $g_schema_version");
			setAttributeValue($top, $ATTR_SCHEMA, $g_schema_version);	# use the latest schema
		}
		if ($my_version == 0) {
			debug("setAttributeValue $ATTR_VERSION: $my_version");
			setAttributeValue($top, $ATTR_VERSION, $my_version);	# make sure there is a 'version' attribute
		}
	}
	return \%sigObj;
}

# do basic transformation for schema #1 and before
# returns $OK or $ERROR
sub doTransform1 {
	my $sigObj = shift();	# a reference to sig obj
	my $top = $sigObj->{$SIG_TOP};
	debug("doTransform1");
	# for schema-1 and before: put all <injection> and <xss> nodes under <Patterns>
	my @childs = @{$top->{$TAG_CHILDREN}};
	my @newChilds = ();
	my @patterns = ();
	foreach (@childs) {
		my $node = $_;	# child node is a reference
		my $name = $node->{$TAG_NAME};
		if ($name eq $NODE_INJECTION || $name eq $NODE_XSS) {
			debug("Found old patterns node: <$name>");
			push(@patterns, $node);	# append node ref to array
		} else {
			push(@newChilds, $node);	# include <Signatures>
		}
	}
	if (scalar(@patterns) > 0) {
		debug("# <Patterns>=".scalar(@patterns));
		my %patNode = (
			$TAG_NAME     => $NODE_PATTERNS,
			$TAG_CHILDREN => \@patterns
		);
		push(@newChilds, \%patNode);	# include <Signatures> and <Patterns>
	}
	$top->{$TAG_CHILDREN} = \@newChilds;
	return $OK;
}

# do secondary transformation: add <RequestPatterns>; use "match_whitespace"; add type attribute to <Patterns>
# returns $OK or $ERROR
# only for non-default file
sub doTransform2 {
	my $sigObj = shift();	# a reference to sig obj
	my $mySchema = shift();
	my $sigs = $sigObj->{$SIG_SIGS};
	my ($rule, $node, $id, $hash);

	debug("doTransform2");
	# --- for <Signatures> ---
	if ($sigs) {
		# Add <RequestPatterns> if not exist under <PatternList>:
		if ($mySchema <= 2) {
			debug("add 'RequestPatterns'");
			foreach (@{$sigs->{$TAG_CHILDREN}}) {
				$rule = $_;	# rule is a reference
				foreach (@{$rule->{$TAG_CHILDREN}}) {
					$node = $_;	# rule is a reference
					if ($node->{$TAG_NAME} eq $NODE_PATTERNLIST) {
						fixOldPatternNode($node);
					}
				}
			}
		}
		# 10.1 schema 3 and 11.0 schema 4 changed from keep_whitespace to match_whitespace
		if ($g_cur_schema_version >= 5 && ($mySchema == 3 || $mySchema == 4)) {
			LOOP: foreach (@{$sigs->{$TAG_CHILDREN}}) {
				$rule = $_;	# rule is a reference
				$id = getRuleId($rule);
				postGetRuleId();		# check if increment of local rule id is needed
				if ($g_is_xform && $id < $SIG_MIN_LOCAL_RULE_ID) {
					next LOOP;	# no need to check for default rules
				}
				$hash = getRuleHash($rule);
				if (index($hash, "| keep_whitespace=") != -1) {
					rename2MatchWhitespace($rule, $id);
				}
			}
		}
	}
	# --- for <Patterns> ---
	my $pats = $sigObj->{$SIG_PATS};
	if ($pats) {
		# for schema-1: added type attr to keyword and specialstring 
		# try to use Literal if possible else specify PCRE explicitly
		if ($mySchema <= 1) {
			debug("add 'type' attr");
			foreach (@{$pats->{$TAG_CHILDREN}}) {
				my $node = $_;
				if ($node->{$TAG_NAME} eq $NODE_INJECTION) {
					foreach (@{$node->{$TAG_CHILDREN}}) {
						my $keyword = $_;
					 	if ($keyword->{$TAG_NAME} eq $NODE_KEYWORD || $keyword->{$TAG_NAME} eq $NODE_SPECIAL_STRING) {
							fixTypeAttribute($keyword);
						}
					}
				}
			}
		}
	}
	return $OK;
}

sub rename2MatchWhitespace {
	my $rule = shift();
	my $id   = shift();
	foreach (@{$rule->{$TAG_CHILDREN}}) {
		my $node = $_;	# rule is a reference
		if (renameAttribute($node, "keep_whitespace", "match_whitespace")) {
			debug("id=$id: Renamed to match_whitespace");
		}
		rename2MatchWhitespace($node, $id);
	}
}

# put all old-style <Pattern> nodes under <RequestPatterns>
sub fixOldPatternNode {
	my $node = shift();	# <PatternList>
	my @reqPats  = ();
	foreach (@{$node->{$TAG_CHILDREN}}) {
		my $pattern = $_;
		if ($pattern->{$TAG_NAME} ne $NODE_PATTERN) {
			return;	# already in new schema
		}
		push(@reqPats, $pattern);	# append node ref to array
	}
	my @newChilds = ();
	if (scalar(@reqPats) > 0) {
		#debug("# <RequestPatterns>=".scalar(@reqPats));
		my %reqPatNode = (
			$TAG_NAME     => $NODE_REQUEST_PATS,
			$TAG_CHILDREN => \@reqPats
		);
		push(@newChilds, \%reqPatNode);	# with single <RequestPatterns> child
	}
	$node->{$TAG_CHILDREN} = \@newChilds;
}

# <=1 schema 'keyword' and 'specialstring' default to PCRE type
# must explicitly set the 'type' attribute to Literal/PCRE
sub fixTypeAttribute {
	my $node = shift();	# a reference to node
	#debug(getXMLTree($node, ""));
	my $type = getAttributeValue($node, $ATTR_TYPE);
	if ($type ne "") {
		return;	# "type" already defined, don't touch it
	}
	$type = $TYPE_LITERAL;
	my $data = $node->{$TAG_DATA};
	if ($data =~ /[\.\$\^\*\+\?\(\[\{\|\-\!\\]/) {
		$type = $TYPE_PCRE;	# this is a PCRE pattern
	}
	setAttributeValue($node, $ATTR_TYPE, $type);
}

# remove all empty data with whitespaces only:
sub removeEmptyData {
	my $node = shift();
	my $data;
	if (exists($node->{$TAG_DATA})) {
		$data = $node->{$TAG_DATA};
		if (!($data =~ m/\S/)) {	# contains any non-whitespace?
			delete($node->{$TAG_DATA});	# remove data if whitespaces only
		}
	}
	if (!exists($node->{$TAG_CHILDREN})) {
		return;
	}
	my @children = @{$node->{$TAG_CHILDREN}};
	foreach (@children) {
		removeEmptyData($_);
	}
}

# return canonical string of the trie tree of given rule for use in isRedefinedSigRule()
sub getRuleHash {
	my $rule = shift();
	my $category = getAttributeValue($rule, $ATTR_CATEGORY);
	my $logString = getLogString($rule);
	my $str = "$category|$logString|";
	my $patternList = getChildNode($rule, $NODE_PATTERNLIST);
	if (defined($patternList)) {
		$str .= getTreeHash($patternList);
	}
	return $str;
}
sub getTreeHash {
	my $node = shift();
	my $name = $node->{$TAG_NAME};
	my $attrs = getAttributeString($node);
	my $str = "$name|$attrs|";
	if (exists($node->{$TAG_DATA})) {
		$str .= trim($node->{$TAG_DATA}).'|';
	}
	if (!exists($node->{$TAG_CHILDREN})) {
		return $str;
	}
	my @children = @{$node->{$TAG_CHILDREN}};
	foreach (@children) {
		$str .= getTreeHash($_);
	}
	return $str;
}

sub getLogString {
	my $rule = shift();
	my $node = getChildNode($rule, $NODE_LOGSTRING);
	if (defined($node)) {
		if (exists($node->{$TAG_DATA})) {
			return $node->{$TAG_DATA};
		}
	}
	return '';
}

# return named child node else undefined
sub getChildNode {
	my $node = shift();
	my $nodeName = shift();
	if (!exists($node->{$TAG_CHILDREN})) { 
		return;	# undefine
	}
	my @childs = @{$node->{$TAG_CHILDREN}};
	foreach (@childs) {
		my $child = $_;
		if ($child->{$TAG_NAME} eq $nodeName) {
			return $child;
		}
	}
	return;	# undefined
}

sub showAllIds {
	my $ids = shift();
	my $id;
	foreach (@$ids) {
		print "> $_\n";
	}
}

sub showAllRules {
	my $rules = shift();
	my ($id, $rule);
	while (($id,$rule) = each %$rules) {
		print "> $id\n";
	}
}

sub printHeader {
    sigWrite('<?xml version="1.0" encoding="UTF-8"?>'."\n");
    sigWrite("<!-- Copyright 2013-2016 Citrix Systems, Inc. All rights reserved. -->\n");
}

sub sigWrite {
	my $str = shift();
	print MYOUTPUT $str;
}

# always sorted
sub outputSigObject {
	my $obj = shift();

	#debug("Writing: $g_outPath");
	open(MYOUTPUT, ">$g_outPath");
	printHeader();
	# 1. print <SignaturesFile>
	my $top = $obj->{$SIG_TOP};
	my $rootName = $top->{$TAG_NAME};
	my $sigs = $obj->{$SIG_SIGS};
	my $rootSignatures = $sigs->{$TAG_NAME};
	my $rootAttrs = getAttributeString(\%$top);
	sigWrite "<$rootName$rootAttrs>\n";
	# 2. print <Comment> 
	if (exists($obj->{$SIG_COMMENT})) {
		my $tag = $obj->{$SIG_COMMENT};
		if (exists($tag->{$TAG_NAME})) {
			my $name = $tag->{$TAG_NAME};
			my $comment = "";
			if (exists($tag->{$TAG_DATA})) {
				$comment = $tag->{$TAG_DATA};
			}
			#print "===== name=$name, comment=$comment\n";
			#print Dumper($tag);
			my $str = "<$name>$comment</$name>\n";
			sigWrite($str);
		}
	}
	# 3. print <Signatures>
	# in case for blank signatures:
	if ($rootSignatures) {
		sigWrite "<$rootSignatures>\n";
	}
	my ($id, $rule);
	my $indent = "  ";
	my %rules = %{$obj->{$SIG_RULES}};
	my @ruleIds;
	@ruleIds = sort({$a <=> $b}keys(%rules));	# sort numerically
	foreach (@ruleIds) {
		my $rule = $rules{$_};
		#print "=> ".getRuleHash($rule)."\n";
		sigWrite(getXMLTree($rule, $indent)."\n");	# print each rule
	}
	if ($rootSignatures) {
		sigWrite "</$rootSignatures>\n\n";
	}
	# 4. print <Patterns> 
	$indent = "";
	printAllPatterns($obj->{$SIG_PATS}, $indent);
	sigWrite("</$rootName>\n");

	close(MYOUTPUT);

	if ($g_logPath ne "") {
		outputLogFile();
	}
	debug("Output: $g_outPath");
}

sub outputLogFile {
	#debug("Writing: $g_logPath");
	open(MYLOG, ">$g_logPath");
	outputLogArrayItems(\@g_newRules, "new");
	outputLogArrayItems(\@g_replacedRules, "replaced");
	outputLogArrayItems(\@g_dupRules, "duplicated");
	outputLogInfoItems(\%g_delRules, "deleted");
	close(MYLOG);
	debug("LogFile: $g_logPath");
}

sub log_args {
	shrinkErrorFile();
	my ($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime(time);
	$year += 1900;
	$mon++;
	my $args = getArgString();
	#log_error("+ $year/$mon/$mday $hour:$min:$sec sig_merge.pl $args");
	my $msg = "+ $year/$mon/$mday $hour:$min:$sec sig_merge.pl $args";
	flushError($msg);
}
sub flushError {
	my $msg = shift();
	open(MYERR, ">>$g_errorPath");	# append to error file
	print MYERR "$msg\n";
	close(MYERR);
}
sub outputErrorFile {
	my $ellapsed = "   Elapsed time: ".(time() - $g_curTime)." secs";
	if (scalar(@g_errors) == 0) {
		flushError($ellapsed);
		return;
	}
	open(MYERR, ">>$g_errorPath");	# append to error file
	foreach (@g_errors) {
		print MYERR "$_\n";
	}
	print MYERR "$ellapsed\n";
	close(MYERR);
	debug("ErrorFile: $g_errorPath");
}

my $g_maxErrorLen = 1000000;
sub shrinkErrorFile {
	my $filesize = -s $g_errorPath;
	if (!defined($filesize) || $filesize < $g_maxErrorLen) {
		return;
	}
	# read entire error file:
	open(FILE, $g_errorPath);
	my $str = "";
	while (<FILE>) {
		$str .= $_;
	}
	close (FILE);

	# shrink it:
	my $len = length($str);
	$len = int($len/4)*3;	# save recent 1/4 only
	$str = substr($str, $len);
	open(FILE2, ">$g_errorPath");
	print FILE2 $str;
	close (FILE2);
}

# print sorted ids for given item list
sub outputLogItems {
	my $items = shift();	# ref to %
	my $header = shift();
	my @ids = sort({$a <=> $b}keys(%$items));	# sort numerically
	my $count = scalar(@ids);
	if ($count == 0) {
		return;
	}
	my $title = ">$header=$count";
	debug($title);
	print MYLOG "$title\n";
	foreach (@ids) {
		print MYLOG "$_\n";
	}
}
sub outputLogInfoItems {
	my $elems = shift();	# ref to %
	my $header = shift();
	my %items = %$elems;
	my @ids = sort({$a <=> $b}keys(%items));	# sort numerically
	my $count = scalar(@ids);
	if ($count == 0) {
		return;
	}
	my $title = ">$header=$count";
	debug($title);
	print MYLOG "$title\n";
	foreach (@ids) {
		print MYLOG "$_: $items{$_}\n";
	}
}
sub outputLogArrayItems {
    my $ref = shift();  # ref to @
	my $header = shift();
	my @items = @$ref;
	@items = sort { $a <=> $b } @items;	# sort numerically
	my $count = scalar(@items);
	my $title = ">$header=$count";
	debug($title);
	print MYLOG "$title\n";
	for (my $i=0; $i<$count; $i++) {
		print MYLOG $items[$i] . "\n";
	}
}

sub printAllPatterns {
	my $pats = shift();
	my $indent = shift();
	sigWrite(getXMLTree($pats, $indent));
}

sub outputTree {
	my $count = scalar(@g_stack);
	if ($count == 0) {
		print "Empty tree\n";
		return;
	}
	print getXMLTree(\%{$g_stack[0]}, "");
}

sub getXMLTree {
	my $tag = shift();
	my $indent = shift();
	my $name = $tag->{$TAG_NAME};
	my $attrs = getAttributeString($tag);
	if (!$name) {
		return '';
	}
	my $str = "$indent<$name$attrs";
	my $data = '';
	if (exists($tag->{$TAG_DATA})) {
		$data = $tag->{$TAG_DATA};
	}
	if (!exists($tag->{$TAG_CHILDREN})) {
		if ($data eq '') {
			return "$str/>\n";
		} else {
			return "$str>$data</$name>\n";
		}
	}
	# with children:
	$str .= ">";
	my @children = @{$tag->{$TAG_CHILDREN}};
	if ($data ne '') {
		$str .= $data;
	}
	$str .= "\n";
	foreach (@children) {
		$str .= getXMLTree($_, $indent."  ");	# \%tag
	}
	return $str.$indent."</$name>\n";
}

sub readFile {
	my $path = shift();
	open my $fh, '<', $path or die "Error opening $path: $!\n";
	my $text = do { local $/; <$fh> };
	close $fh;
	return $text;
}

sub main {
	my $ret_code = getOption();
	if ($ret_code != $OK) {
		outputErrorFile();
		return $ret_code;
	}
	if ($g_version) {
		print "sig_version:$g_sig_version\nDone\n";
		return $OK;
	}
	initParser();
	# parse the -name object file (or the -src file if -transform)
	debug("--- Parse object file ---");
	if ($g_transform) {
		if (!$g_namePath) {
			$g_namePath = $g_srcPath;	# use -src as the namepath
		}
	}
	if ($g_src && $g_default && $g_import) {
		if (!$g_namePath) {
			$g_namePath = $g_srcPath;	# use -src as the namepath
		}
	}
	# parse -src file:

	%g_masterRules = %g_rules1;	# to be used by isRedefinedSigRule()
	my $sigObj1 = parseSigPath($g_namePath, \%g_rules1, 0);	# either from -name or -src; 0=not -default
	if ($sigObj1 == $ERROR) {
		outputErrorFile();
		return $ERROR;
	}
	my $ver1 = getObjVersion($sigObj1);
	# ------ for transform ---
	# don't merge with -default if ver is 0:
	if ($g_transform || ($g_import && $ver1 == 0)) {
		log_error("> transformed only file1=$g_namePath");
		debug("--- transformed only ---");
		outputSigObject($sigObj1);
		outputErrorFile();
		print "Done\n";	# _xform_: used for validation only
		return $OK;
	}
	# ------ for merge ---
	my $newFile;
	if ($g_default) {
		$newFile = $g_defaultPath;
	} else {
		$newFile = $g_srcPath;
	}
	# parse the -default or -src file
	debug("--- Parse input file ---");
	%g_masterRules = %g_rules2;	# to be used by isRedefinedSigRule()
	my $sigObj2 = parseSigPath($newFile, \%g_rules2, $g_default);	# true if it is -default
	my $status = validateSigVersions($sigObj1, $sigObj2);
	if ($status == $ERROR) {
		outputErrorFile();
		return $status;
	} 
	#log_error("> merge: default=$g_default, xform=$g_is_xform, import=$g_import");
	debug("--- merge ---");
	my $merged;
	if ($g_import) { # -import -default: 
		log_error("--> merge1: file1:$g_namePath -> file2:$newFile");
		%g_masterRules = %g_rules2;	# to be used by isRedefinedSigRule()
		$merged = mergeSigs($sigObj2, $sigObj1);	# use -default as the master copy (merge sigObj1 into sigObj2)
	} else {
		log_error("--> merge2: file1:$newFile -> file2:$g_namePath");
		%g_masterRules = %g_rules1;	# to be used by isRedefinedSigRule()
		$merged = mergeSigs($sigObj1, $sigObj2);	# merge sigObj2 into sigObj1
	}
	# --- output file ---
	outputSigObject($merged);
	outputErrorFile();
	print "Done\n"; # done printing
	return $OK;
}

# 1.Fastmatches cannot be in HTTP_RESP_BODY and HTTP_POST_BODY
# 2.Fastmatches in form fields must meet the following requirements:
#    There can be only one form field pattern in the rule
#    If there is form field name, the fast match must be on the name
# 3.Fastmatch should be used with Literal match unless if there is a literal in the beginning of the PCRE.  For example:
#    Abcdf*  - OK
#    Xyz{3,4}- OK
#    A*bccd  - not OK
#    (A|b|c) - not OK
# Returns 1 if rule has valid fastmatch else 0
sub isValidFastmatch {
	my $rule = shift();	# rule is a reference
	my $errmsg_ref = shift();	# return error msg if any
	$$errmsg_ref = "";
	my $hash = $rule->{$TAG_HASH};
	my $hasFastmatch = 0;
	my $index = -1;
	if (!$hash) {
		$hash = "";
	}
	$index = index($hash, " type=\"fastmatch\"");
	my $area = "";
	my ($h1, $h2, $type, $match);
	if ($index != -1) {
		# ------ has explicit fastmatch ------
		$hasFastmatch = 1;
		$h1 = substr($hash, $index);
		$index = index($h1, "|Pattern|");
		# extract this pattern:
		if ($index != -1) {
			$h1 = substr($h1, 0, $index);
		} else {
			$h1 = substr($h1, 0);
		}
		if ($h1 =~ / area="([^"]+)"/) {
			$area = $1;
			if ($area eq $AREA_HTTP_RESP_BODY || $area eq $AREA_HTTP_POST_BODY) {
				$$errmsg_ref = "!fastmatch on HTTP_RESP_BODY or HTTP_POST_BODY";
				return 0;
			}
			
		}
		if ($h1 =~ / type="Literal"/i) {
			if ($area eq $AREA_HTTP_FORM_FIELD) {
				if ($hash =~ / area="HTTP_FORM_FIELD".* area="HTTP_FORM_FIELD"/) {
					$$errmsg_ref = "!fastmatch with multiple FF";
					return 0;	# may not have multiple formField
				}
				if ($h1 =~ /\|FieldName\|.* type="([^"]+)\|([^\|]*)\|"/) {
					$type = $1;
					$match = $2;
					if (!($type =~ /Literal/i)) {
						if ($type =~ /PCRE/i) {
							# must have leading Literal
							if (index($match, "^") == 0) {
								$match = substr($match, 1);	# remove leading ^
							}
							if ($match =~ /^\(/ || $match =~ /^\[/ || $match =~ /^.\*/) {
								$$errmsg_ref = "!fastmatch on PCRE without leading literal string";
								return 0;	# leading (, [ or .* not accepted for literal fastmatch in PCRE
							}
							return 1;	# has leading literal in PCRE
						}
						$$errmsg_ref = "!fastmatch not in literal FieldName";
						return 0;	# must use FieldName for fastmatch
					}
				}
			}
			return 1;	# good fastmatch
		}
		$$errmsg_ref = "!fastmatch without literal match";
		return 0;	
	}
	# ------ no explicit fastmatch ------
	my $pats;
	foreach (@{$rule->{$TAG_CHILDREN}}) {
		$pats = $_;
		if ($pats->{$TAG_NAME} eq $NODE_PATTERNLIST) {
			last;
		}
	}
	if (!$pats) {
		$$errmsg_ref = "!Missing <PatternList>";
		return 0;	# no patterns at all
	}
	return 1;
}

exit(main());
