#!/usr/bin/bash
# FILE:         $Id: //depot/main/rs_111/usr.src/netscaler/scripts/fwupdate.sh#1 $
# LAST CHECKIN: $Author: build $
#               $DateTime: 2016/06/13 09:44:25 $
#
# Copyright 2011-2016 Citrix Systems, Inc. All rights reserved.
# This software and documentation contain valuable trade
# secrets and proprietary property belonging to Citrix Systems, Inc.
# None of this software and documentation may be copied,
# duplicated or disclosed without the express
# written permission of Citrix Systems, Inc.
#
#Usage execute the script from /var/tmp
set -e
# Declare array
declare -a ARRAY
declare -a SYSID
declare -a SMARTCTL
APPROVED_SYSID="1800100"
FWFILE="/usr/share/misc/2CV102M3.bin"
NULL=0

CMD="df -h "
CMD1="grep /flash"
CMD2="umount -f ${ARRAY[5]}"
CMD3="mount -f ${ARRAY[0]} ${ARRAY[5]}"
CMD4="sysctl netscaler.sysid"
CMD5="atacontrol fwupdate $FWFILE $device"

echo "Checking prequisites ... "
SYSID=(`$CMD4`)
echo "Script is approved to run on SYSID $APPROVED_SYSID"
echo "Netscaler has SYSID ${SYSID[1]}"
if [ $APPROVED_SYSID != ${SYSID[1]} ]; then
	echo "Script can be executed only from SYSID $APPROVED_SYSID ... Aborting"
	exit 1
fi
sleep 1
if [ -e $FWFILE ]; then
	echo "Firmware microcode found !"
else
	echo "Firmware microcode not found in current directory ... Aborting"
	exit 1
fi
sleep 1
echo
echo "Starting firmware update."
echo
echo "Examining devices ..." 
df -h
echo
#Remove exception cover
set +e
echo "Extracting mount point, partition and device"
ARRAY=(`$CMD|$CMD1`)
ELEMENTS=${#ARRAY[@]} 
set -e
if [ $ELEMENTS -eq $NULL ]; then
	echo "Unknown mount point and partition .. Aborting"
	exit 1
fi
device=${ARRAY[0]:5:3}
echo $CMD2
echo
echo "Unmounting SSD ..."
echo "umount -f ${ARRAY[5]}"
umount -f ${ARRAY[5]}
echo
sleep 1
df -h
echo
sleep 1
echo "Attempting firmware update ..."
echo $CMD5
atacontrol fwupdate $FWFILE $device
sleep 1
echo
echo "Mounting SSD ..."
echo $CMD3
mount -f ${ARRAY[0]} ${ARRAY[5]}
echo
sleep 1
df -h
echo
echo "Verifying SSD power mode"
echo "atacontrol check-power-mode $device"
atacontrol check-power-mode $device
echo
sleep 1
echo "Verifying firmware upgrade"
echo "smartctl -i /dev/$device"
smartctl -i /dev/$device
sleep 1
echo
echo "Analyzing smartcontrol output ..."
smartctl -i /dev/$device | grep ${FWFILE:16:8}
echo

#device value derived at a later stage
CMD6="smartctl -i /dev/$device"
CMD7="grep ${FWFILE:16:8}"

#Removing exception cover
set +e
SMARTCTL=(`$CMD6|$CMD7`)
VERIFY=${#SMARTCTL[@]}
set -e

if [ $VERIFY -ne $NULL ]; then
	echo "Firmware update verified !"
else
	echo "Firmware update failed !"
	exit 1
fi
echo
sleep 1
Ans="A"
yes="y"
Yes="Y"
no="n"
No="N"

while [[ $Ans != $Yes && $Ans != $yes && $Ans != $No && $Ans != $no ]]; do
	echo "Do you want to reboot (Y/N) ? "
	read Ans
done

if [[ $Ans = $Yes || $Ans = $yes ]]; then
echo "rebooting ..."
reboot
exit 0
else
echo "Script Exits !"
exit 0
fi
