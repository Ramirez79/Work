#!/bin/sh
# piping the packet trace to the tcpdump
#
# FILE:         $Id: //depot/main/rs_111/usr.src/netscaler/scripts/nstcpdump.sh#2 $
# LAST CHECKIN: $Author: anilkuma $
#               $DateTime: 2016/06/17 02:31:55 $
#
# Copyright 1998-2016 Citrix Systems, Inc. All rights reserved.
# This software and documentation contain valuable trade
# secrets and proprietary property belonging to Citrix Systems, Inc.
# None of this software and documentation may be copied,
# duplicated or disclosed without the express
# written permission of Citrix Systems, Inc.
#
# Options:	any TCPDUMP options
#
TCPDUMP_PIPE=/var/tmp/tcpdump_pipe

NETSCALER=${NETSCALER:-`cat /var/run/.NETSCALER`}
[ -r ${NETSCALER}/netscaler.conf ] && . ${NETSCALER}/netscaler.conf

TIME=${TIME:-3600}
MODE=${MODE:-6}
STARTCMD="start nstrace -size 0 -traceformat PCAP -merge ONTHEFLY -filetype PIPE -skipLocalSSH ENABLED"
STOPCMD="stop nstrace "
SHOWCMD="show nstrace "
NSCLI_FILE_EXEC=/netscaler/nscli
NSTRACE_OUT_FILE=/tmp/nstrace.out
NS_STARTTRACE_PIDFILE=/tmp/nstcpdump.pid
TRACESTATE=$(nsapimgr -d allvariables | grep tracestate | awk '{ print $2}')

trap nstcpdump_exit 1 2 15

nstcpdump_init()
{
	echo "##### WARNING #####"
	echo "This command has been deprecated."
	echo "Please use 'start nstrace...' command from CLI to capture nstrace."
	echo "trace will now start with all default options"
	echo "###################"
	if [ ! -d $NSTRACE_DIR ]
	then
		echo "$NSTRACE_DIR directory doesn't exist."
		echo "Possible reason: partition is not mounted."
		echo "Check partitions using mount program and try again."
		exit 1
	fi

	if [ ! -x $NSCLI_FILE_EXEC ]
	then
		echo "$NSCLI_FILE_EXEC binary doesn't exist"
		exit 1
	fi

	if [ -e $NSTRACE_OUT_FILE ]
	then 
		rm $NSTRACE_OUT_FILE
		echo "" >> $NSTRACE_OUT_FILE
	fi

}
nstcpdump_start_petrace()
{
	sleep 0.5;
	$NSCLI_FILE_EXEC -U %%:.:. $STARTCMD >/tmp/nstcpdump.sh.out
	rm -f ${NS_STARTTRACE_PIDFILE}
}

nstcpdump_start()
{
	# exit if trace is already running
	if [ $TRACESTATE -ne 0 ]
	then
		echo "Error: one instance of nstrace is already running"
		exit 2
	fi

	nstcpdump_start_petrace &
	echo $! > ${NS_STARTTRACE_PIDFILE}
	tcpdump  -n -r - $TCPDUMPOPTIONS < ${TCPDUMP_PIPE}
	nstcpdump_exit
	exit 1
}

nstcpdump_exit()
{
	if [ -f ${NS_STARTTRACE_PIDFILE} ]
	then
		kill `cat ${NS_STARTTRACE_PIDFILE}`
		rm ${NS_STARTTRACE_PIDFILE}
	fi

	$NSCLI_FILE_EXEC -U %%:.:. $STOPCMD >> /dev/null
	exit 1
}

nstcpdump_usage()
{
	echo `basename $0`: utility to view/save/sniff LIVE packet capture on NETSCALER box
	tcpdump -h
	echo
	echo NOTE: tcpdump options -i, -r and -F are NOT SUPPORTED by this utility
	exit 0
}

########################################################################

while [ $# -gt 0 ]
do
	case "$1" in
	-h )
		nstcpdump_usage
		;;
	-i )
		nstcpdump_usage
		;;
	-r )
		nstcpdump_usage
		;;
	-F )
		nstcpdump_usage
		;;
	esac
	break;
done

TCPDUMPOPTIONS="$@"
check_ns nstcpdump
#nstcpdump_init
#set -e
if [ ! -e ${TCPDUMP_PIPE} ]
then
	mkfifo $TCPDUMP_PIPE
	if [ $? -ne 0 ]
	then
		echo "Failed creating pipe [$TCPDUMP_PIPE]"
		exit 1;
	fi
fi

nstcpdump_start
