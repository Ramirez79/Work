#!/bin/sh
#
# FILE:         $Id: //depot/main/rs_111/usr.src/netscaler/scripts/nsdiskspace.sh#1 $
# LAST CHECKIN: $Author: build $
#               $DateTime: 2016/06/13 09:44:25 $
#
# Copyright 1998-2016 Citrix Systems, Inc. All rights reserved.
# This software and documentation contain valuable trade
# secrets and proprietary property belonging to Citrix Systems, Inc.
# None of this software and documentation may be copied,
# duplicated or disclosed without the express
# written permission of Citrix Systems, Inc.
#

NETSCALER=${NETSCALER:-`cat /var/run/.NETSCALER`}
[ -r ${NETSCALER}/netscaler.conf ] && . ${NETSCALER}/netscaler.conf

LOG=${NSLOG}
NSDISKSPACE_PID=${NSPID_DIR}/nsdiskspace.pid
SLP_TIME=${NSDSPACE_SLP_TIME:-10}
DME=${NETSCALER}/nsdme

usage()
{
	echo "nsdiskspace.sh - utility to populate diskspace details to kernel"
	echo "usage: `basename $0` [-t timeout_minutes] start | stop"
	exit 0
}

####################################################################

if [ $# -eq 0 ]
then
	usage
fi
START=
while [ $# -gt 0 ]
do
	case "$1" in
	[sS][tT][oO][pP] )
		if [ -f $NSDISKSPACE_PID ]
		then
			echo nslog: `date`: killed by STOP command >>$LOG
			$KILL -9 `$CAT $NSDISKSPACE_PID` >/dev/null 2>&1
			$KILLALL -9 nsdiskspace >/dev/null 2>&1
			$RM -f $NSDISKSPACE_PID >/dev/null 2>&1
			echo " nsdiskspace killed..."
		fi
		shift
		;;
	[sS][tT][aA][rR][tT] )
		START=1
		shift
		;;
	-t )
		shift
		test $# -eq 0 && { usage; }
		SLP_TIME=$1
		shift
		;;
	*)
		usage
		;;
	esac
done
if [ ! "$START" ]
then
	exit 0
fi
echo "nsdiskspace: `date`: started as process $$" >>$LOG
echo $$ >$NSDISKSPACE_PID
while :
do
	df -k | while read line
	do
		set -- $(echo $line)
		case "$6" in
		"/var" )
			pr="disk1"
			;;
		"/flash" )
			pr="disk0"
			;;
		*)
			continue
			;;
		esac
		size_s=${pr}size=$(($2 / 1024))
		used_s=${pr}used=$(($3 / 1024))
		avail_s=${pr}avail=$(($4 / 1024))
		per_usg_s=${pr}pusage=${5%[%]}
		$DME $size_s $used_s $avail_s $per_usg_s
	done 
	sleep $(($SLP_TIME * 60))
done
