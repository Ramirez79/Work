#!/bin/sh
#
# FILE:         $Id: //depot/main/rs_111/usr.src/netscaler/scripts/nslcdinit.sh#1 $
# LAST CHECKIN: $Author: build $
#               $DateTime: 2016/06/13 09:44:25 $
#
# Copyright 2002-2016 Citrix Systems, Inc. All rights reserved.
# This software and documentation contain valuable trade
# secrets and proprietary property belonging to Citrix Systems, Inc.
# None of this software and documentation may be copied,
# duplicated or disclosed without the express
# written permission of Citrix Systems, Inc.
#
# The purpose of this script is to initialize the LCD device
# It only needs to be run once in the life of the system
#
NETSCALER=${NETSCALER:-`cat /var/run/.NETSCALER`}

if [ `sysctl -n netscaler.sysid` != "450000" ] ; then
	pwscrnmsg="\ \ \ Netscaler\ \ \ \ \ \ \ POWER\ ON\ \ \ \ \ "
	$NETSCALER/nslcd -m "$pwscrnmsg"
	echo LCD power-up message is set! 
fi
