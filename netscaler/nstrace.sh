#!/bin/sh
#
# FILE:         $Id: //depot/main/rs_111/usr.src/netscaler/scripts/nstrace.sh#1 $
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
# Options:	...
#
NETSCALER=${NETSCALER:-`cat /var/run/.NETSCALER`}
[ -r ${NETSCALER}/netscaler.conf ] && . ${NETSCALER}/netscaler.conf

TIME=${TIME:-3600}
NOF=${NOF:-24}
PACKET_SIZE=${PACKET_SIZE:-164}
MODE=${MODE:-18}
NICFILTER=${NICFILTER:-0}
STARTFILE=1
NSTRACE_TCPDUMP=0
NSTRACE_FILENAME=nstrace
NSTRACE_CONF_FILENAME=$NSCONFIG/nstrace.conf
NSTRACE_FILTER=''
LINK_OPTION=0
DATE=''
PID=''
SUFFIX=''
LABEL=$$
TX=1
TXB=2
RX=4
IPV6=8
NEW_RX=16
C2C=32
ENABLED=1
DISABLED=0
NSTRACE=0
TCPDUMP=1
STARTCMD="start nstrace "
STOPCMD="stop nstrace " 
SHOWCMD="show nstrace " 
NSCLI_FILE_EXEC=/netscaler/nscli
NSTRACE_OUT_FILE=/tmp/nstrace.out
ERRORVAL="ERROR:"

#Local variable
i=$STARTFILE
NSPROF_RUNNING=0

trap nstrace_exit 1 2 15
trap nstrace_quit 3 

nstrace_init()
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

nstrace_start()
{
	$NSCLI_FILE_EXEC -U %%:.:. $STARTCMD
	stty cols 10000
	$NSCLI_FILE_EXEC -U %%:.:. $SHOWCMD > $NSTRACE_OUT_FILE
	TRACELOCATION=$(grep "TraceLocation" $NSTRACE_OUT_FILE | awk '{ len=length($6); print substr($6, 2, len-5); }')
	echo "TraceLocation...."
	echo $TRACELOCATION
}

nstrace_exit()
{
	$NSCLI_FILE_EXEC -U %%:.:. $STOPCMD >> /dev/null
	exit 1
}

nstrace_stop()
{
	$NSCLI_FILE_EXEC -U %%:.:. $STOPCMD >> /dev/null
	exit 1
}

nstrace_quit()
{
	$NSCLI_FILE_EXEC -U %%:.:. $STOPCMD >> /dev/null
	exit 1
}
nstrace_usage()
{
	echo "nstrace - utility to start NetScaler packets trace"
	echo "usage: `basename $0` [-h] [-nf <number_of_files] [-time <time>] [-m <mode>] [-nic <boolean>]"
	echo "	-h	- prints this message - exclusive option"
	echo "	-nf 	- number of files to be generated in cycle (def. $NOF)"
	echo "	-time	- seconds per file (def. $TIME) (could be an expression)"
	echo "	-sz 	- size of the captured data (bytes from 60 to 1514)"
	echo "	-m	- Capturing mode: sum of the values (def. $MODE):"
	echo "			  1 - Transmitted packets (TX)"
	echo "			  2 - Packets buffered for transmission (TXB)"
	echo "			  4 - Received packets (RX) before NIC pipelining"
	echo "			  8 - Translated IPv6 packets"
	echo "			  16 - Received packets (NEW_RX) after NIC pipelining"								
	echo "			  32 - C2C messages to be captured in tracing"
	echo "	-tcpdump - 0=nstrace-format (default) or 1=tcpdump-format"
	echo "	-name	- name of the trace file"
	echo "	-filter	- Filter expression for nstrace. The maximum length of filter expression is 255 and it can of following format:
						<expression> [<relop> <expression>]
						
						<relop> = ( && | || )
						<expression> = the expression string in the format:
						<qualifier> <operator> <qualifier-value>
						
						<qualifier> = SOURCEIP.
						<qualifier-value> = A valid IP address.
						<qualifier> = SOURCEPORT.
						<qualifier-value> = A valid port number.
						<qualifier> = DESTIP.
						<qualifier-value> = A valid IP address.
						<qualifier> = DESTPORT.
						<qualifier-value> = A valid port number.
						<qualifier> = IP.
						<qualifier-value> = A valid IP address.
						<qualifier> = PORT.
						<qualifier-value> = A valid port number.
						<qualifier> = SVCNAME.
						<qualifier-value> = The name of a service.
						<qualifier> = VSVRNAME.
						<qualifier-value> = The name of a vserver.
						<qualifier> = CONNID
						<qualifier-value> = A valid PCB dev number.
						<qualifier> = VLAN
						<qualifier-value> = A valid VLAN ID.
						<qualifier> = INTF
						<qualifier-value> = A valid interface id in the form of x/y. 

						<operator> = ( == | eq | != | neq | > | gt
						               | < | lt | >= | ge | <= | le | BETWEEN )
					
					eg: nstrace.sh -filter SOURCEIP == 10.102.34.201 || (SVCNAME != s1 && SOURCEPORT > 80)
					Filter expression should be given in doble quotes."
	echo "	-link	- Log filtered connection's peer's (linked connection's) traffic. Works only with -filter option"
	echo "	-id 	- ID for the trace file name for uniqueness. Should be used only with -name option"
	echo "	-stop	- can be used to disable tracing (when 'nstrace.sh' is run in the background)"
	exit 0
}

while [ $# -gt 0 ]
do
	case "$1" in
	-time | -t )
		shift
		[ $# -eq 0 ] && nstrace_usage
		case $1 in
			(*[^0-9]*|'')
			nstrace_usage;;
		esac
		TIME=$1
		shift
		;;
	-nf | -f )
		shift
		[ $# -eq 0 ] && nstrace_usage
		case $1 in
			(*[^0-9]*|'')
			nstrace_usage;;
		esac
		NOF=$1
		shift
		;;
	-sz | -s | -size )
		shift
		[ $# -eq 0 ] && nstrace_usage
		if [ $1 -eq $1 2>/dev/null ]; then
			[ $1 -ne 0 -a \( $1 -lt 60 -o $1 -gt 1514 \) ] && nstrace_usage
			PACKET_SIZE=$1
		else
			case $1 in
				(*[^0-9]*|'')
				nstrace_usage;;
			esac
			echo "Error: Packet size must be integer number between 60 - 1514" && nstrace_usage 
		fi
		shift
		;;
	-m | -mode )
		shift
		[ $# -eq 0 ] && nstrace_usage
		MAX_MODE=$(($RX + $TX + $TXB + $IPV6 + $NEW_RX + $C2C))
		if [ $1 = "RX" -o $1 = "TX" -o $1 = "TXB" -o $1 = "IPV6" -o $1 = "NEW_RX" -o $1 = "C2C" ]
		then
			while [ "$1" = RX -o "$1" = TX -o "$1" = TXB -o "$1" = IPV6 -o "$1" = NEW_RX -o "$1" = C2C ]
			do
				MODE_VAL=$(( MODE_VAL + $1 ))
				shift
			done
		fi
		case $1 in
			(*[^0-9]*|'')
			nstrace_usage;;
		esac
		if [ $1 -gt 0 -a $1 -le $MAX_MODE ]
		then
			MODE_VAL=$1
			shift
		else
			echo "Error: Mode is invalid" 
			shift
		fi	
		MODE=$MODE_VAL
		;;
   -tcpdump | -format | -tcpDump)
		shift
		[ $# -eq 0 ] && nstrace_usage
		if [ $1 = ENABLED -o $1 = DISABLED -o $1 = NSTRACE -o $1 = TCPDUMP ]
		then
			NSTRACE_TCPDUMP=$(( $1 ))
		else
			case $1 in
				(*[^0-9]*|'')
				nstrace_usage;;
			esac
			NSTRACE_TCPDUMP=$1
		fi
		shift
		;;
	-nic | -split | -perNIC)
		shift
		[ $# -eq 0 ] && nstrace_usage
		if [ $1 = ENABLED -o $1 = DISABLED ]
		then
			NICFILTER=$(( $1 ))
		else
			case $1 in
				(*[^0-9]*|'')
				nstrace_usage;;
			esac
			NICFILTER=$1
		fi
		shift
		;;
	-name | -n )
		shift
		[ $# -eq 0 ] && nstrace_usage
		NSTRACE_FILENAME=$1
		shift
		;;
	-filter )
		shift
		[ $# -eq 0 ] && nstrace_usage
		NSTRACE_FILTER="$1"
		count=${#NSTRACE_FILTER}
		[ $count -ge 256 ] && nstrace_usage
		shift
		;;
	-id )
		shift
		[ $# -eq 0 ] && nstrace_usage
		case $1 in
			(*[^0-9]*|'')
			nstrace_usage;;
		esac
		LABEL=$1
		shift
		;;	
	-link | -l )
		shift
		[ $# -eq 0 ] && nstrace_usage
		if [ $1 = ENABLED -o $1 = DISABLED ]
		then
			LINK_OPTION=$(( $1 ))
		else
			case $1 in
				(*[^0-9]*|'')
				nstrace_usage;;
			esac
			LINK_OPTION=$1
		fi	
		shift
		;;	
	-stop )
		nstrace_stop
		;;
	-*)
		nstrace_usage
		;;
	esac
done
TIME=$(($TIME))
check_ns nstrace
nstrace_init
nstrace_start
