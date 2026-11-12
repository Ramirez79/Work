#!/bin/sh
# NetScaler Migrate APPFW related Imports script
# FILE:         $Id: //depot/main/rs_111/usr.src/netscaler/scripts/migrateAppfwImports.sh#1 $
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
# Options:      NONE
#

if [ ! $# -eq 2 ]
then
	echo "Invalid syntax..."
	echo ""
	echo "Syntax: $0 <user-name-of-remote-machine (source)> <IP-of-remote-machine (source)>"
else
	rsync  -a --delete $1@$2:/var/download/ /var/download/
	echo "Done"
fi
