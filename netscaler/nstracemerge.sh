#!/bin/sh
# FILE:         $Id: //depot/main/rs_111/usr.src/netscaler/scripts/nstracemerge.sh#1 $
# LAST CHECKED: $Author: build $
#               $DateTime: 2016/06/13 09:44:25 $
#
# Copyright 2012-2016 Citrix Systems, Inc. All rights reserved.
# This software and documentation contain valuable trade
# secrets and proprietary property belonging to Citrix Systems, Inc.
# None of this software and documentation may be copied,
# duplicated or disclosed without the express
# written permission of Citrix Systems, Inc.
#
# Options:	...
#
NETSCALER=${NETSCALER:-`cat /var/run/.NETSCALER`}
[ -r ${NETSCALER}/netscaler.conf ] && . ${NETSCALER}/netscaler.conf

SRCDIR="."
DSTDIR="."
FILENAME="nstrace"
NUMFILE=1
FILESIZE=0

trap nstracemerge_exit 1 2 15
trap nstracemerge_quit 3 

nstracemerge_exit()
{
	echo "nstracemerge: Killed by Signal"
	kill -15 `cat $NSPID_DIR/nsapimgr.pid`
	exit 1
}

nstracemerge_quit()
{
	echo "nstracemerge: Done.. "
	kill -15 `cat $NSPID_DIR/nsapimgr.pid`
	exit 1
}

nstracemerge_usage()
{
	echo "nstracemerge : Merges two or more capture files into one"
	echo " "
	echo "usage:"
	echo "nstracemerge.sh [-srcdir | -s] <DIR> [-dstdir | -d] <DIR> [-filename | -f] <name> [[-numfile | -nf] <num> | [-filesize | -sz] <num>]"
	echo " "
	echo "srcdir : specify the source directory of the input files to merge trace"
	echo "Default: current working directory"
	echo " "
	echo "dstdir : specify the destinaiton directory where final file is stored after merge"
	echo "Default: current working directory" 
	echo " "
	echo "filename : name of the merged output trace file"
	echo "Default: nstrace"
	echo " "
	echo "numfile : number of output files"
	echo "Default: 1"
	echo " "
	echo "filesize : size of output files in MB"
	echo " "
	echo "current working directory : "$PWD
	echo " "
	echo "Examples:"
	echo "(1) Merging CAP files :"
	echo "    nstracemerge.sh -srcdir /var/nstrace/19May2014_06_39_10/ -dstdir /var/ -filename final"
	echo "(2) Splitting large CAP file (can be done on single file as well)"
	echo "    nstracemerge.sh -srcdir /var/nstrace/19May2014_06_39_10/ -dstdir /var/ -filename final -numfile 5" 
}

nstrace_merge_start()
{	
	mergearg="-m"
	echo "nstrace merge"
	echo "source directory : "$SRCDIR
	echo "destination directory : "$DSTDIR 
	echo "output file name : "$FILENAME 
	echo "current working directory [.] :"$PWD  
	echo " "
	echo "Files in source directory:"

	for sslkeyfile in `find $SRCDIR -name *.sslkeys`
	do
		cat $sslkeyfile >> $SRCDIR/nstrace.sslkeys
	done

	if [ `find $SRCDIR -name "*.cap" | wc -l` -eq 0 ]
	then
		if [ `find $SRCDIR -name "*.pcap" | wc -l` -ne 0 ]
		then
		echo " "
			$NSAPIMGR -s srcdir=$SRCDIR -s dstdir=$DSTDIR -s outfile=$FILENAME -s outfilenum=$NUMFILE -s outfilesize=$FILESIZE -M >/dev/null 2>/dev/null
			return
		fi
		echo "no valid files"
		echo "exit"
		exit 1
	else
		echo " "
		$NSAPIMGR -s srcdir=$SRCDIR -s dstdir=$DSTDIR -s outfile=$FILENAME -s outfilenum=$NUMFILE -s outfilesize=$FILESIZE -m >/dev/null 2>/dev/null
	fi

}

while [ $# -gt 0 ]
do
	case "$1" in
	-srcdir | -s )
		shift
		[ $# -eq 0 ] && nstracemerge_usage && exit
		SRCDIR="$1"
		shift
		;;
	-dstdir | -d )
		shift
		[ $# -eq 0 ] && nstracemerge_usage && exit
		DSTDIR="$1"
		shift
		;;
	-filename | -f )
		shift
		[ $# -eq 0 ] && nstracemerge_usage && exit
		FILENAME="$1"
		shift
		;;
	-numfile | -nf )
		shift
		[ $# -eq 0 ] && nstracemerge_usage && exit
		NUMFILE=$1
		shift
		;;
	-filesize | -sz )
		shift
		[ $# -eq 0 ] && nstracemerge_usage && exit
		FILESIZE=$1
		shift
		;;
	-help | -h )
		nstracemerge_usage
		exit
		;;
	*)
		nstracemerge_usage
		exit
		;;
	esac
done

nstrace_merge_start
