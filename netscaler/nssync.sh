#!/bin/sh
# NetScaler Config sync script
#
# FILE:         $Id: //depot/main/rs_111/usr.src/netscaler/scripts/nssync.sh#1 $
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
# Options:	'start' or 'stop'
#
NETSCALER=${NETSCALER:-`cat /var/run/.NETSCALER`}
[ -r ${NETSCALER}/netscaler.conf ] && . ${NETSCALER}/netscaler.conf

LOG=${NSLOG}
NSSYNC_PID=${NSPID_DIR}/nssync.pid
NSSYNC_DIR="/$NSLOG_FS/nssynclog"

trap nssync_exit 2 15

nssync_exit()
{
	$KILLALL -9 nssync >/dev/null 2>&1
	$RM -f $NSSYNC_PID
	exit 2
}

nssync_usage()
{
	echo "nssync - utility to keep HA configurations synchronous"
	echo "usage: `basename $0` [start]"
	echo "usage: `basename $0` stop"
	exit 0
}

####################################################################

while [ $# -gt 0 ]
do
	case "$1" in
	stop )
		if [ -f $NSSYNC_PID ]
		then
			echo nslog: `date`: killed by STOP command >>$LOG
			$KILL -9 `$CAT $NSSYNC_PID`
			$KILLALL -9 nssync >/dev/null 2>&1
			$RM -f $NSSYNC_PID
			echo " nssync killed..."
		fi
		exit 0
		;;
	start )
 		if [ -f $NSSYNC_DIR ]
 		then
 		$RM -f $NSSYNC_DIR
 		fi
		if [ ! -d $NSSYNC_DIR ]
	 then
  	mkdir -p $NSSYNC_DIR
 	fi
		shift
		;;
	-*)
		nssync_usage
		;;
	esac
done

echo nssync: `date`: started as process $$ >>$LOG
echo $$ >$NSSYNC_PID
while :
do
	check_ns nssync
	$NETSCALER/nssync
	if [ $? -eq 1 ]
	then
		echo "nssync: `date`: unable to access password file" >>$LOG
		exit 1
	fi
done
