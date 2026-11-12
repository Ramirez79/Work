#!/bin/sh
#
# FILE:         $Id: //depot/main/rs_111/usr.src/netscaler/scripts/nsumon.sh#1 $
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

NETSCALER=${NETSCALER:-`cat /var/run/.NETSCALER`}
[ -r ${NETSCALER}/netscaler.conf ] && . ${NETSCALER}/netscaler.conf

MYNAME=$(basename $0)
NSUMOND=nsumond
NSUMON_PID=${NSPID_DIR:-/var/run}/${NSUMOND}.pid
NSUMOD_DIR="/var/nslog/nsumond"
NSUMON_LOGILE=${NSUMOD_DIR}/nsumond.log
LOG=${NSLOG}
DUMMYCMD=/usr/bin/true	# dummy command to pass to lockf

# Here are the environmental variables (and default values) that nsumond looks at
# NSUMON_KAS: default no
# NSUMON_VERBOSELOGGING: default no
# NSUMON_NODAEMON: default no
# NSUMON_LISTENIP: default 127.0.0.1
# NSUMON_LISTENPORT: default 3013

# Load user-specified config to override defaults
[ -r ${NSCONFIG}/nsumon.conf ] && . ${NSCONFIG}/nsumon.conf

nsumon_start()
{
	echo "$NSUMOND: $(date): starting dispatcher daemon $NSUMOND" | tee -a $LOG

	rm $NSUMON_LOGILE
	touch $NSUMON_LOGILE
	chmod +w $NSUMON_LOGILE
	chown nsmonitor $NSUMON_LOGILE
	
	# Check for name collisions in /netscaler/monitors and  
	# /nsconfig/monitors. If any file with same name as buitins in 
	# /nsconfig/monitors are moved to conflicts directory.

	# find command is used to search /nsconfig/monitors directory
	# for name collisions. 
	# Here we need to exclude /nsconfig/monitors/conflicts
	# directory which is achieved by -prune option by giving the
	# directory path (-path).
	# -name option is used to specify the name of the file to
	# search.
	# If scriptname conflict is found then the command specified in
	# -exec is executed and the same is printed using -print
	# option.

	if [ -d ${NETSCALER}/monitors/ ]
	then 
		mkdir -p $NSMONUSERSCRIPTDIR
		mkdir -p ${NSMONUSERSCRIPTDIR}/conflicts
		for i in `ls ${NETSCALER}/monitors`
		do
			if [ ! -d ${NETSCALER}/monitors/$i ]
			then
				find ${NSMONUSERSCRIPTDIR} -path ${NSMONUSERSCRIPTDIR}/conflicts -prune -o -name $i -exec mv -f {} ${NSMONUSERSCRIPTDIR}/conflicts \; -print | while read f; do
					msg="$(date): Scriptname conflict found between /netscaler/monitors and /nsconfig/monitors. Moving $f to ${NSMONUSERSCRIPTDIR}/conflicts directory.\n"
					echo -e $msg >> $NSUMON_LOGILE;
					echo -e $msg >> $LOG;
				done

			fi
		done
		
		# Delete the conflicts directory if there are no
		# conflicts.
		if [ ! `ls ${NSMONUSERSCRIPTDIR}/conflicts` ]
		then
			rm -r ${NSMONUSERSCRIPTDIR}/conflicts
		fi

		$CHMOD +x ${NETSCALER}/monitors/*
	fi

	# make nsumond dump core - special sysctl needed to 
	# ensure setuid program dumps core.
	sysctl kern.sugid_coredump=1 >/dev/null
	if [ ! -d $NSUMOD_DIR ]
	then 
		mkdir $NSUMOD_DIR
		chown nsmonitor $NSUMOD_DIR
		chmod +w $NSUMOD_DIR
	fi

	$NETSCALER/$NSUMOND &
}

nsumon_exit()
{
	# nsumond is running if pidfile exists and lock also exists on it
	if [ -f $NSUMON_PID ] && ! lockf -t 0 $NSUMON_PID $DUMMYCMD >/dev/null 2>&1
	then
		echo "Sending $NSUMOND the TERM signal ..."
		$KILL -15 $($CAT $NSUMON_PID) 		>/dev/null 2>&1
		lockf -t 15 $NSUMON_PID $DUMMYCMD  	>/dev/null 2>&1
		if [ $? -eq 75 ]
		then
			# Lock still exists - nsumond hasn't exited. Send KILL signal
			# to the entire process group
			echo "Sending $NSUMOND the KILL signal ..."
			$KILL -9 -- -$($CAT $NSUMON_PID)	>/dev/null 2>&1
			echo "$NSUMOND: $(date): killed" | tee -a $LOG
		else
			echo "$NSUMOND: $(date): stopped" | tee -a $LOG
		fi
		$RM -f $NSUMON_PID
		exit 0
	fi
	echo "$NSUMOND not running"
	exit 1
}

nsumon_dumpstats ()
{
	if [ -f $NSUMON_PID ] && ! lockf -t 0 $NSUMON_PID $DUMMYCMD  >/dev/null 2>&1
	then
		echo "$NSUMOND: $(date): dumpstat command"	>>$LOG
		echo "Dumping statistics to /var/nstmp/monitors/nsumon_statdump.txt"
		$KILL -USR1 $($CAT $NSUMON_PID) 				>/dev/null 2>&1
		exit 0
	fi
	echo "$NSUMOND not running"
	exit 1
}

nsumon_flush()
{
	if [ -f $NSUMON_PID ] && ! lockf -t 0 $NSUMON_PID $DUMMYCMD  >/dev/null 2>&1
	then
		echo "$NSUMOND: $(date): flushcommand" >> $LOG
		echo "Expiring all processes"
		$KILL -HUP $($CAT $NSUMON_PID) >/dev/null 2>&1
		exit 0
	fi
	echo "$NSUMOND not running"
	exit 1
}

nsumon_usage()
{
	echo "$MYNAME - Scriptable monitor dispatcher"
	echo "Usage: $MYNAME action [options]"
	echo "actions: "
	echo " start		- Start the dispatcher."
	echo " stop		- Stop the dispatcher."
	echo " dumpstats	- Dump statistics to /var/nstmp/nsumon_statdump.txt."
	echo " flush- Expire all scripts processes."
	echo ""
	echo "options (only for start action): "
	echo " -listenip #	- IP Address to listen on."
	echo " -listenport #	- Port to listen on."
	echo " -verbose	- Log verbose (only if $NSUMOND is compiled with DEBUG option)."
	echo " -nodaemon	- Stay on terminal - do not daemonize."
	echo ""

	exit 1
}

#########################################################################

while [ $# -gt 0 ]
do
	case "$1" in
	start )
		ACTION="start"
		;;

	stop )
		[ $# -ne 1 ] && nsumon_usage
		nsumon_exit
		;;

	dumpstats )
		[ $# -ne 1 ] && nsumon_usage
		nsumon_dumpstats
		;;

	flush)
		[ $# -ne 1 ] && nsumon_usage
		nsumon_flush
		;;

	-listenip )
		shift
		[ $# -eq 0 ] && nsumon_usage
		export NSUMON_LISTENIP=$1
		;;

	-listenport )
		shift
		[ $# -eq 0 ] && nsumon_usage
		export NSUMON_LISTENPORT=$1
		;;

	-verbose )
		export NSUMON_VERBOSELOGGING=yes
		;;

	-nodaemon )
		export NSUMON_NODAEMON=yes
		;;

	-username )
		shift
		[ $# -eq 0 ] && nsumon_usage
		export NSUMON_USERNAME=$1
		;;

	*)
		nsumon_usage
		;;
	esac
	shift
done

[ -z "$ACTION" ] && nsumon_usage
nsumon_$ACTION
exit 0
