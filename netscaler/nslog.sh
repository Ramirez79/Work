#!/bin/sh
# NetScaler log script
#
# FILE:         $Id: //depot/main/rs_111/usr.src/netscaler/scripts/nslog.sh#1 $
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
# This script runs the netscaler logger and maintains the backups
# Options:	'stop' or 'start' or '-time'

issmallvar() {
df -k /var | while read line
do
        set -- $line
        case "$6" in
	"/var" )
                if [ $2 -lt 1048001 ]
                then
                        return 1;
                else
                        return 0;
                fi
                ;;
        *)
                continue
                ;;
        esac
	return 0;
done
}
NETSCALER=${NETSCALER:-`cat /var/run/.NETSCALER`}
[ -r ${NETSCALER}/netscaler.conf ] && . ${NETSCALER}/netscaler.conf

LOG=${NSLOG}
NSLOG_PID=${NSPID_DIR}/nslog.pid
NSLOG_TIME=2*24*60*60  # 2 days worth seconds
NSLOG_NEXTFILE=${NSLOG_DIR}/nslog.nextfile
NXTZIPNUM_FILE=${NSLOG_DIR}/nslog.nextzip
LASTZIPPIDFILE=${NSLOG_DIR}/nslog.lastpid
ABS_INPROGRESS_FILE=${NSLOG_DIR}/nslog.absrecstatus
TIMEOFDAY="0-23"
MAXBATCHSIZE=20
ABSRECDMPWAIT=60
STAGGERINTVL=60
NSVPX=`sysctl -n netscaler.hypervised_ns`
NSTSCCOUNTER=`sysctl -n kern.timecounter.tc.TSC.counter`
# random number generator in the range 0-59, inclusive
SKEW=`expr $NSTSCCOUNTER % 60`

if [ $NSVPX -eq 1 ]
then
# On VPX we have less memory and hard disk space
NSLOG_SIZE=100m #100Mb
NSLOG_END_FILENO=100
else
NSLOG_SIZE=600m # Is 1Gb really too big?
NSLOG_END_FILENO=200
fi
DEFAULT_SIZE_TO_DELETE=4100000 # leave just over 4GB for core files (1k blocks)
[ -f $NSCONFIG/.developer ] && DEFAULT_SIZE_TO_DELETE=10000
SIZE_TO_DELETE=$DEFAULT_SIZE_TO_DELETE
LOGCONF=${NSLOG_DIR}/nsagg.conf

trap nslog_exit 2 15

########################################################################

nszip_logfile()
{
	local newfile="${NSLOG_FILE}.$1"
	cd $NSLOG_DIR >/dev/null 2>&1
	if [ -d $newfile ]; then
		echo nslog: `date`: Creating "${NSLOG_DIR}/${newfile}.tar.gz" >> $LOG
		nice -n 15 tar -czvf "${newfile}.tar.gz"  "$newfile" >/dev/null 2>&1
		if [ "$?" -eq "0" ]; then
			$RM -rf $newfile >/dev/null 2>&1 
		else
			echo nslog: `date`: TAR of "${NSLOG_DIR}/$newfile failed" >> $LOG
		fi
	elif [ -f $newfile ]; then
		echo nslog: `date`: Creating "${NSLOG_DIR}/${newfile}.gz" >> $LOG
		nice -n 15 $GZIP -f "$newfile" >/dev/null 2>&1
		if [ "$?" -ne "0" ]; then
			echo nslog: `date`: GZIP of "${NSLOG_DIR}/$newfile failed" >> $LOG
		fi
	else
		echo nslog: $newfile does not exists >>$LOG
	fi
}

ns_checkspace()
{
	local OLDEST_FILE SPACE_AVAILABLE first

	first=1
	while :
	do
		set `$DF -k | $GREP $NSLOG_FS`
		SPACE_AVAILABLE=$4
		if [ $SPACE_AVAILABLE -lt $DEFAULT_SIZE_TO_DELETE -o $SPACE_AVAILABLE -lt $SIZE_TO_DELETE ]
		then
			set `$LS -lrt $NSLOG_DIR/${NSLOG_FILE}.*.gz | wc -l`
			NUM_NSLOG_FILES=$1
			[ $first -ne 0 ] && echo nslog: `date`: need to free some space for log files >>$LOG
			first=0
			$LS -rtsk1 $NSLOG_DIR/${NSLOG_FILE}.*.gz >/dev/null 2>&1
			if [ $? -ne 0 -o $NUM_NSLOG_FILES -lt 11 ]
			then
				echo nslog: no space on /$NSLOG_FS partition! >>$LOG
				$DF -k | $GREP $NSLOG_FS >>$LOG
				$LS -rtsk1 | $GREP $NSLOG_FS >>$LOG
				echo nslog: exited >>$LOG
				echo "Failed to free disk space for NetScaler system log:"
				echo "No more file available for deletion"
				echo "Free some disk space on /var partition"
				echo "and start /netscaler/nslog.sh program manually"
				exit 1
			fi
			set `$LS -rtsk1 $NSLOG_DIR/${NSLOG_FILE}.*.gz | $HEAD -n 1`
			OLDEST_FILE=$2
			echo nslog: $OLDEST_FILE has been removed >>$LOG
			$RM -f $OLDEST_FILE >>$LOG
		else
			return
		fi
		sync
	done
	[ $first -eq 0 ] && echo nslog: free space now: `$DF -k | $GREP var` >>$LOG
}

nslog_exit()
{
	$RM -f $NSLOG_PID
	exit 2
}

nslog_usage()
{
	echo "nslog - utility to start NetScaler event/statistics log"
	echo "usage: `basename $0` [start] [-h] [-time <time>] &"
	echo "	-h	  - prints this message - exclusive option"
	echo "	-nf   - number of files to be generated in cycle"
	echo "	-time - time per file - seconds (can be an expression)"
	echo "	-size - size per file (default in 'bytes') with minimum file-size as 4Kb"
	echo "		1. suffix 'm' for Mb eg : for 300Mb give -size 300m"
	echo "		2. suffix 'k' for Kb eg : for 300Kb give -size 300k"
	echo "usage: `basename $0` stop"
	exit 0
}

checkprocexist()
{
        read lastpid<$LASTZIPPIDFILE
        $RM -f $LASTZIPPIDFILE
        ps ax | grep -v grep | grep "nslog.sh" | while read psLine; do
                set -- $psLine
                if [ "$lastpid" -eq "$1" ]; then
                        echo nslog:`date`: Old nslog.sh: $lastpid still running. We will exit >>$LOG
                        echo $lastpid>$LASTZIPPIDFILE
                        return
                fi
        done
}

########################################################################
# Command line options:
while [ $# -gt 0 ]
do
	case "$1" in
	dozip )
		if [ -e "$LASTZIPPIDFILE" ]; then
			checkprocexist
			if [ -e "$LASTZIPPIDFILE" ]; then
				exit 0
			fi
		fi
		echo $$>$LASTZIPPIDFILE

		if [ ! -e "$NSLOG_NEXTFILE" ]; then
			$RM -f $LASTZIPPIDFILE
			exit 0
		fi
		read nxtfilenum<$NSLOG_NEXTFILE
		if [ ! -e "$NXTZIPNUM_FILE" ]; then
			nxtzipnum=0
		else
			read nxtzipnum<$NXTZIPNUM_FILE
		fi

		if [ "$nxtzipnum" -le "$nxtfilenum" ]; then
			batchcount=`expr $nxtfilenum - $nxtzipnum`
		else
			let batchcount="`expr $NSLOG_END_FILENO - $nxtzipnum` + $nxtfilenum"
		fi 

		if [ "$batchcount" -gt "$MAXBATCHSIZE" ]; then
				batchcount=$MAXBATCHSIZE
		fi

		file_count=0
		while [ "$file_count" -lt "$batchcount" ]
		do
			if [ -e "$ABS_INPROGRESS_FILE" ]; then
				sleep $ABSRECDMPWAIT
			fi

			if [ "$nxtzipnum" -eq "$nxtfilenum" ]; then
				break
			fi

			if [ -e "${NSLOG_DIR}/${NSLOG_FILE}.$nxtzipnum" ]; then
				nszip_logfile $nxtzipnum
				file_count=`expr $file_count + 1`
				sleep $STAGGERINTVL
			fi
			let nxtzipnum="`expr $nxtzipnum + 1` % $NSLOG_END_FILENO"
		done
		echo $nxtzipnum>$NXTZIPNUM_FILE
		$RM -f $LASTZIPPIDFILE
		exit 0
		;;
	stop )
		AGGREGATOR_COMMAND="COMMAND=STOP"
		echo nslog: `date`: stopped newnslog by STOP command >> $LOG
		shift
		;;
	start )
		AGGREGATOR_COMMAND="COMMAND=START"
		echo nslog: `date`: started logging by START command >> $LOG
		if ! grep nslog.sh /etc/crontab > /dev/null 2>&1; then
			chmod u+w /etc/crontab
			echo "$SKEW       $TIMEOFDAY       *       *       *       root    nslog.sh dozip" >> /etc/crontab
			chmod u-w /etc/crontab
		fi
		shift
		;;
	-time )
		shift
		[ $# -eq 0 ] && nslog_usage
		NSLOG_TIME=$1
		shift
		;;
	-nf )
		shift
		test $# -eq 0 && { nslog_usage; }
		NSLOG_END_FILENO=$1
		shift
		;;
	-size )
		shift
		NSLOG_SIZE=$1
		shift
		;;
	checkspace )
		check_ns nslog
		ns_checkspace
		exit 0
		;;
	*)
		nslog_usage
		exit 1
		;;
	esac
done

# For a *small* nsva disk image
issmallvar
if [ $? -eq 1 ]
then
	if [ -e /etc/smalldisk.conf ]
	then
		mv /etc/smalldisk.conf /etc/newsyslog.conf
	fi
	NSLOG_TIME=1*24*60*60	# 1 days worth of seconds
	NSLOG_END_FILENO=2		# drastic measures for small disk
fi

NSLOG_TIME=$(($NSLOG_TIME))
#For NCORE newnslog files are written by aggregator
echo "$AGGREGATOR_COMMAND" > $LOGCONF
echo "LOGSIZE=$NSLOG_SIZE" >> $LOGCONF
echo "LOGFILECNT=$NSLOG_END_FILENO" >> $LOGCONF
echo "LOGTIME=$NSLOG_TIME" >> $LOGCONF
if ps ax | grep -v grep | grep "nsaggregatord"
then
	killall -USR1 nsaggregatord
fi
echo "nslog: current settings => $AGGREGATOR_COMMAND, LOGSIZE=$NSLOG_SIZE, LOGFILECNT=$NSLOG_END_FILENO, LOGTIME=$NSLOG_TIME" >> $LOG
