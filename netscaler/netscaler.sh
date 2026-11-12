#!/bin/sh
# NetScaler startup file
#
# FILE:         $Id: //depot/main/rs_111/usr.src/netscaler/scripts/netscaler.sh#1 $
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

NETSCALER=${NETSCALER:-`cat /var/run/.NETSCALER`}
[ -r ${NETSCALER}/netscaler.conf ] && . ${NETSCALER}/netscaler.conf

case $1 in
[Ss][Tt][Aa][Rr][Tt] )
	sh $NETSCALER/nsstart.sh 
	;;
[Ss][Tt][Oo][Pp] )
	[ -r $NETSCALER/nsstop.sh ] && sh $NETSCALER/nsstop.sh &
	;;
*)
	echo usage: `basename $0` 'start | stop'
esac
