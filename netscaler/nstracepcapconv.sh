#!/bin/sh
#
# FILE:         $Id: //depot/main/rs_111/usr.src/netscaler/scripts/nstracepcapconv.sh#1 $
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

CAPFILE="nstrace.cap"
PCAPFILE="nstrace.pcap"
NICFILTER=0
DOSYNC=0
CAPCOUNT=0
WORKINGDIR="/var/nstrace"
CLNODEID=0

nstrace_convert_files()
{
	$NSAPIMGR -K $CAPFILE -o $PCAPFILE -s nicfilter=$NICFILTER -s dopcap=1 -d nstracev3 >/dev/null 2>/dev/null
	rm $CAPFILE >/dev/null 2>/dev/null

	CAPCOUNT=$(find $WORKINGDIR -name "*.cap" | wc -l)

	if [ $CAPCOUNT -eq 0 ]
	then
		if [ $DOSYNC -eq 1 ]
		then
			mkdir $DSTDIR./node$CLNODEID >/dev/null 2>/dev/null
			mv $DSTDIR* $DSTDIR./node$CLNODEID >/dev/null 2>/dev/null
			$NSAPIMGR -ys nstrace_sync=$DSTDIR >/dev/null 2>/dev/null
		fi
	fi
}

while [ $# -gt 0 ]
do
	case "$1" in
	-capfile)
		shift
		[ $# -eq 0 ] && exit
		CAPFILE="$1"
		shift
		;;
	-pcapfile)
		shift
		[ $# -eq 0 ] && exit
		PCAPFILE="$1"
		shift
		;;
    -nicfilter)
        shift
        [ $# -eq 0 ] && exit
  		NICFILTER=$1
	    shift
	    ;;
    -dosync)
        shift
        [ $# -eq 0 ] && exit
  		DOSYNC=$1
	    shift
	    ;;
    -wdir)
        shift
        [ $# -eq 0 ] && exit
  		WORKINGDIR=$1
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

nstrace_convert_files
