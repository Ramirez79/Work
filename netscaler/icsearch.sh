#!/bin/sh

#
# FILE:         $Id: //depot/main/rs_111/usr.src/netscaler/tools/icsearch.sh#1 $
# LAST CHECKIN: $Author: build $
#               $DateTime: 2016/06/13 09:44:25 $
#
# Copyright 2006-2016 Citrix Systems, Inc. All rights reserved. 
# This software and documentation contain valuable trade 
# secrets and proprietary property belonging to Citrix Systems, Inc.  
# None of this software and documentation may be copied, 
# duplicated or disclosed without the express 
# written permission of Citrix Systems, Inc.

NSCACHEMGR="nscachemgr -a"
GREP="grep -iE"

print_usage() {
	echo "Searches objects in IC based on the following criteria."
	echo "Regex patterns are supported."
	echo "	-host <host_pattern>"
	echo "	-url <url_pattern>"
	echo "	-port <port_pattern>"
	echo "	-method <request_method_pattern>"
	echo "	-group <group_pattern>"
	echo "	-cellid <cellid_pattern>"
}

check_for_argval() {
	if [ $2 = 1 ]; then
		echo "Missing argument after $1"
		exit
	fi
}

while [ $# != 0 ] ; do
	case "$1" in
		-host)	check_for_argval $1 $#
			host_pattern=$2
			shift; shift;;
		-url)	check_for_argval $1 $#
			url_pattern=$2
			shift; shift;;
		-port)	check_for_argval $1 $#
			port_pattern=$2
			shift; shift;;
		-method) check_for_argval $1 $#
			method_pattern=$2
			shift; shift;;
		-group) check_for_argval $1 $#
			group_pattern=$2
			shift; shift;;
		-cellid) check_for_argval $1 $#
			cellid_pattern=$2
			shift; shift;;
		*)	print_usage
			exit;;
	esac
done

$NSCACHEMGR | grep -v "Total URL" |
    while read obj; do
	i=`echo "$obj" | sed 's%^\(.*[PG][OE][ST][T]*\)	//\([^/]*\):\([0-9]*\)\(.*\)%\1	\2	\3	\4%'`
	# $i contains tab seperated list of (cellid, group, method, host, port, url)
	# Treat anything after 6th field as url
    	if [ ! -z $cellid_pattern ]; then
		tmp=`echo "$i" | cut -f1 | $GREP "$cellid_pattern"`
		[ -z $tmp ] && continue;
	fi
    	if [ ! -z $group_pattern ]; then
		tmp=`echo "$i" | cut -f2 | $GREP "$group_pattern"`
		[ -z $tmp ] && continue;
	fi
    	if [ ! -z $method_pattern ]; then
		tmp=`echo "$i" | cut -f3 | $GREP "$method_pattern"`
		[ -z $tmp ] && continue;
	fi
    	if [ ! -z $host_pattern ]; then
		tmp=`echo "$i" | cut -f4 | $GREP "$host_pattern"`
		[ -z $tmp ] && continue;
	fi
    	if [ ! -z $port_pattern ]; then
		tmp=`echo "$i" | cut -f5 | $GREP "$port_pattern"`
		[ -z $tmp ] && continue;
	fi
    	if [ ! -z $url_pattern ]; then
		tmp=`echo "$i" | cut -f6- | $GREP "$url_pattern"`
		[ -z $tmp ] && continue;
	fi
	echo "$obj"
    done
