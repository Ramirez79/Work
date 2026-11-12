#!/bin/sh
# Interface clean up script  
#
# FILE:         $Id: //depot/main/rs_111/usr.src/netscaler/scripts/ns_intfcleanup.sh#1 $
# LAST CHECKIN: $Author: build $
#               $DateTime: 2016/06/13 09:44:25 $
#
# Copyright 2007-2016 Citrix Systems, Inc. All rights reserved.
# This software and documentation contain valuable trade
# secrets and proprietary property belonging to Citrix Systems, Inc.
# None of this software and documentation may be copied,
# duplicated or disclosed without the express
# written permission of Citrix Systems, Inc.
#
# Currently this script will clean all the IP's
# Learnt by BSD but in future it could be used for 
# othe rinterface clean up routine at shutdown  time

ifconfig -a | egrep -v '127.0.0.1|fe80::1%lo0|inet6 ::1' |egrep 'inet|flags=' | sed "s/: .*//" | sed "s/%.*//" | sed "s/netmask.*//" > /var/tmp/ifconfigout
while read f
do
  case $f in
          inet*)
                 $(ifconfig $intf $f delete)
                 ;;
	         *)
                 intf=$f
                 ;;
    esac
done < /var/tmp/ifconfigout
rm /var/tmp/ifconfigout

