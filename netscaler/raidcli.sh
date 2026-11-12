#!/usr/bin/bash
#set -x
sysid=`sysctl -n netscaler.sysid`
if [ $sysid -ne 250040 ] &&
   [ $sysid -ne 250041 ] &&
   [ $sysid -ne 250042 ] &&
   [ $sysid -ne 250048 ] &&
   [ $sysid -ne 250101 ] &&
   [ $sysid -ne 250119 ] &&
   [ $sysid -ne 250140 ] &&
   [ $sysid -ne 250141 ] &&
   [ $sysid -ne 250142 ] &&
   [ $sysid -ne 250148 ] &&
   [ $sysid -ne 250149 ]
then
        echo "Command not supported in this platform"
        exit
fi
case $1 in
[1])
STATUS="$(atacontrol status ar0 | sed -n -e 's/^.*status: //p')"
echo "RAID1 status: $STATUS"
echo "Drive:"
        STATUS="$(atacontrol status ar0 | sed -n -e 's/^.*ad4 //p')"
        if [ -n "$STATUS" ];
        then
                echo -e "\t1 $STATUS"
        fi
        STATUS="$(atacontrol status ar0 | sed -n -e 's/^.*ad6 //p')"
        if [ -n "$STATUS" ];
        then
                echo -e "\t2 $STATUS"
        fi
;;
*)
echo "Nothing to show"
;;
esac
