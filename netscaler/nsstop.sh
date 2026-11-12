#!/bin/sh
# NetScaler Shutdown script
# FILE:         $Id: //depot/main/rs_111/usr.src/netscaler/scripts/nsstop.sh#1 $
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
# Options:	NONE
#

NETSCALER=${NETSCALER:-`cat /var/run/.NETSCALER`}
[ -r ${NETSCALER}/netscaler.conf ] && . ${NETSCALER}/netscaler.conf

NSNCORE=`sysctl -n netscaler.nCore`
if [ $NSNCORE -eq 1 ]
then
	echo " Executing nsshutdown on nCore Netscaler."
	/netscaler/nsshutdown
	exit
fi


echo 'NetScaler shutdown...'
if [ -d $NSLOG_DIR ] ; then
	echo '' >>$NSLOG
	echo nsstop: `date` ': shutdown' >>$NSLOG
fi

[ -r $NSCONFIG/nsdown.sh ]		&& sh $NSCONFIG/nsdown.sh

# stop the NS kernel -- TODO does not work in 6.0 - crash in vm manager
#$NSAPIMGR -yx

if [ -f $NSSTOPLIST ] ; then
	while read prc 
	do 
		[ -r $NETSCALER/$prc ] && sh $NETSCALER/$prc stop
	done <$NSSTOPLIST
	$RM -f $NSSTOPLIST
fi

if [ -f $NSKILLLIST ] ; then
    # first time give the process a chance
	while read prc 
	do 
		$KILLALL $prc >/dev/null 2>&1
	done <$NSKILLLIST
    # and now do it hard way
	while read prc 
	do 
		$KILLALL -9 $prc >/dev/null 2>&1
	done <$NSKILLLIST
	$RM -f $NSKILLLIST
fi
