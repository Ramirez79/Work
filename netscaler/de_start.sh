#!/bin/sh
# NetScaler Disk Engine Startup script
#
# FILE:         $Id: //depot/main/rs_111/usr.src/netscaler/scripts/de_start.sh#1 $
# LAST CHECKIN: $Author: build $
#               $DateTime: 2016/06/13 09:44:25 $
#
# Copyright 2011-2016 Citrix Systems, Inc. All rights reserved.
# This software and documentation contain valuable trade
# secrets and proprietary property belonging to Citrix Systems, Inc.
# None of this software and documentation may be copied,
# duplicated or disclosed without the express
# written permission of Citrix Systems, Inc.

# Start the disk engine - single threaded to work around an issue with 
# stdio seemingly not working once the process becomes multi-threaded.
# running fairly verbose here; defaults are much quieter and thus dont eat 
# performance

NSCONFIG=${NSCONFIG:=/nsconfig}
NETSCALER=${NETSCALER:=/netscaler}
DISK_CONF=${NSCONFIG}
DSCACHE_CONF=${DSCACHE_CONF:=$DISK_CONF/dscache.conf}
DISK_CACHE_ENABLE=NO

DE_THR=${DE_THR:=16} # number of worker threads
DE_VERBOSE=${DE_VERBOSE:=0} # How chatty should the disk engine be (0=silent)
DE_MAX_DISK_REQUESTS=${DE_MAX_DISK_REQUESTS:=256} # number of requests per client

if [ -f /etc/ns_raid.subr ]; then
	# Hiding all disk cache related info/msgs/config
	#. /etc/ns_raid.subr
else
	# Hiding all disk cache related info/msgs/config
	#echo "Error: Unable to source /etc/ns_raid.subr"
	exit 1
fi

calc_disk_capacity() {
	ret=0
	capacityKB_raid_fs
	SZ_KB=$?
	if [ $SZ_KB -gt 0 ]; then
		ret=$(( SZ_KB * 9 / 10 ))
	fi
	return $ret
}

# functin to check if disk cache is enable in configuration.
is_disk_enable() {
	set ICFeatured=no
	set DiskCacheFeatured=no
	feature=`grep -i "enable ns feature" ${NSCONFIG}/ns.conf`
	for F in $feature; do
		if [ "$F" = "IC" ]; then
			ICFeatured=YES
		fi
		if [ "$F" = "DiskCaching" ]; then
			DiskCachingFeatured=YES
		fi
	done
	if [ "$ICFeatured" = "YES" -a "$DiskCachingFeatured" = "YES" ]; then
		DISK_CACHE_ENABLE=YES
	fi
}

is_de_running() {
	CNT=`ps ax | grep $NETSCALER/de | grep -v de_start | grep -v grep | wc -l `
	if [ ${CNT} -ne 0 ]; then
		return 0;
	fi
	return 1;
}


# At this point disk cache config should exists (at least the default one)
if [ -f ${DSCACHE_CONF} ]; then
	# Hiding all disk cache related info/msgs/config
	#echo "Found disk cache configuration ..."
	#. ${DSCACHE_CONF}
else
	# Hiding all disk cache related info/msgs/config
	#echo "Missing disk cache configuration ..."
	exit 1
fi

is_disk_enable

de_start() {
	if [ "$DISK_CACHE_ENABLE" = "YES" ]; then
		echo "Disk cache is enabled .."

		calc_disk_capacity
		capSZ=$?

		if is_de_running; then
			echo "Disk Engine Already Running"
		else 
			echo "Starting Disk Engine .."
			nohup $NETSCALER/de -m netscaler -p ${DISK_PATH} -w ${DE_THR} -v ${DE_VERBOSE} -r ${DE_MAX_DISK_REQUESTS} -C -D syslog -L syslog -e 0 -Z $capSZ &
		fi
	else
		# Hiding all disk cache related info/msgs/config
		#echo "Disk cache is disabled .."
	fi;
}

de_stop() {
	echo "TBD -- implement de_stop "
}

case $1 in 
	stop)
		de_stop
		;;
	start|\
	*)
		de_start
		;;
esac

exit 0
