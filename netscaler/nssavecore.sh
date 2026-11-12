#!/bin/sh -v
# NetScaler Savecore script
#
# FILE:         $Id: //depot/main/rs_111/usr.src/netscaler/scripts/nssavecore.sh#1 $
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
# Saves core and changes read permissions or group
# Options:	crash dump directory and other savecory options

NETSCALER=${NETSCALER:-`cat /var/run/.NETSCALER`}
[ -r ${NETSCALER}/netscaler.conf ] && . ${NETSCALER}/netscaler.conf

SCARG=${*:-/var/crash}

LOG=${NSLOG}
TMPLOG=/tmp/savecore.log

NS_RC_SUBR=/etc/ns_rc.subr

[ -f "${NS_RC_SUBR}" ] && . ${NS_RC_SUBR}
if [ -z "${SWAPD}" ]; then
	ns_disk_conf
fi

echo "nssavecore: `date`: check for kernel core dump on device ${SWAPD} " >>$LOG
$SAVECORE -z $SCARG /dev/${SWAPD} 1>$TMPLOG 2>&1
CORE_FILE=`$TR " " "\n" <$TMPLOG | $GREP vmcore`
if [ X$CORE_FILE != X ]
then
	CORE_FILE=/var/crash/$CORE_FILE
	$CHMOD 640 ${CORE_FILE}
	echo "nssavecore: `date`: core dump has been created: $CORE_FILE" >>$LOG
	cat $TMPLOG >>$LOG
fi
