#!/bin/sh

#
# FILE:         $Id: //depot/main/rs_111/usr.src/netscaler/tools/mergenslogfiles.sh#1 $
# LAST CHECKIN: $Author: build $
#               $DateTime: 2016/06/13 09:44:25 $
#
# Copyright 2011-2016 Citrix Systems, Inc. All Rights Reserved

OUTFILE="merged_ns.log"

if [ $# -lt 2 ]; then
	echo "Merges ns.log files of multiple nodes in a cluster in file $OUTFILE"
	echo "Usage: $0 <ns.log.file1> <ns.log.file2.gz> ... "
	echo "Example: $0 ns.log*"
	exit 1
fi

files=""
tempfiles=""
for argval in "$@"; do
	ext=${argval##*.};
	if [ "a$ext" = "agz" ]; then
		argval=`basename $argval .gz`
		zcat $argval.gz > $argval
		tempfiles="$tempfiles $argval"
	fi
	if [ "$argval" != "$OUTFILE" ] ; then
		files="$files $argval"
	fi
done
if [ -f $OUTFILE ]; then
	rm -f $OUTFILE
fi

sort -m -n -k 14 -o $OUTFILE $files
if [ $? -eq 0 ]; then
	echo "Consolidated logs are stored in file: $OUTFILE"
fi
if [ "a$tempfiles" != "a" ]; then
	rm -f $tempfiles
fi
