#!/bin/sh
#
# FILE:         $Id: //depot/main/rs_111/usr.src/netscaler/scripts/nstracemergenclean.sh#1 $
# LAST CHECKIN: $Author: build $
#               $DateTime: 2016/06/13 09:44:25 $
#
# Copyright 1998-2016 Citrix Systems, Inc. All rights reserved.
# This software and documentation contain valuable trade
# secrets and proprietary property belonging to Citrix Systems, Inc.
# None of this software and documentation may be copied,
# duplicated or disclosed without the express
# written permission of Citrix Systems, Inc.
NETSCALER=${NETSCALER:-`cat /var/run/.NETSCALER`}
[ -r ${NETSCALER}/netscaler.conf ] && . ${NETSCALER}/netscaler.conf


SRCDIR="."
DSTDIR="."
CAPNAME="nstrace.cap"
PCAPNAME="nstrace.pcap"
CLEANUP=0
PCAP=0
DOSYNC=0
SYNCONLY=0
ROLLOVER=0
CLNODEID=0

nstrace_merge_files()
{

	if [ $ROLLOVER -eq 0 ]
	then
		for keyfile in `find ${SRCDIR%nstrace*} -name "nstrace_ppe*.sslkeys"`
		do
			cat $keyfile >> ${SRCDIR%nstrace*}/nstrace.sslkeys
			rm $keyfile
		done
	fi

	if [ $SYNCONLY -eq 0 ]
	then
		if [ $PCAP -eq 1 ]
		then
			if [ `find $SRCDIR -name "*.pcap" | wc -l` -eq 0 ]
			then
				exit 1
			fi
			$NSAPIMGR -s srcdir=$SRCDIR -s dstdir=$DSTDIR -s outfile=$PCAPNAME -s outfilenum=1 -s outfilesize=0 -s mergemode=$ROLLOVER -M >/dev/null 2>/dev/null
		else
			if [ `find $SRCDIR -name "*.cap" | wc -l` -eq 0 ]
			then
				exit 1
			fi
			$NSAPIMGR -s srcdir=$SRCDIR -s dstdir=$DSTDIR -s outfile=$CAPNAME -s outfilenum=1 -s outfilesize=0  -s mergemode=$ROLLOVER -m >/dev/null 2>/dev/null
		fi

		if [ $CLEANUP -eq 1 ]
		then
			rm -rf $SRCDIR >/dev/null 2>/dev/null
		fi
	fi
	
	if [ $DOSYNC -eq 1 ]
	then
		mkdir $DSTDIR./node$CLNODEID >/dev/null 2>/dev/null
		mv $DSTDIR* $DSTDIR./node$CLNODEID >/dev/null 2>/dev/null
		$NSAPIMGR -ys nstrace_sync=$DSTDIR >/dev/null 2>/dev/null
	fi
}

while [ $# -gt 0 ]
do
	case "$1" in
	-srcdir)
		shift
		[ $# -eq 0 ] && exit
		SRCDIR="$1"
		shift
		;;
	-dstdir)
		shift
		[ $# -eq 0 ] && exit
		DSTDIR="$1"
		shift
		;;
    -capname)
        shift
        [ $# -eq 0 ] && exit
  		CAPNAME=$1
	    shift
	    ;;
    -pcapname)
        shift
        [ $# -eq 0 ] && exit
  		PCAPNAME=$1
	    shift
	    ;;
    -cleanup)
        shift
        [ $# -eq 0 ] && exit
  		CLEANUP=$1
	    shift
	    ;;
    -tcpdump)
        shift
        [ $# -eq 0 ] && exit
  		PCAP=$1
	    shift
	    ;;
    -dosync)
        shift
        [ $# -eq 0 ] && exit
  		DOSYNC=$1
	    shift
	    ;;
    -synconly)
        shift
        [ $# -eq 0 ] && exit
  		SYNCONLY=$1
	    shift
	    ;;
    -rollover)
        shift
        [ $# -eq 0 ] && exit
  		ROLLOVER=$1
	    shift
	    ;;
    -nodeid)
        shift
        [ $# -eq 0 ] && exit
  		CLNODEID=$1
	    shift
	    ;;
	*)
		exit
		;;
	esac
done

nstrace_merge_files
