#!/bin/sh
#############################################
# Declaration of PARAMETERS
#############################################

error_count=0

hex_convert() {
	root_path=$1
	dec_value=$2
	cmd=$3
	hex_value=`printf "%X" $dec_value`

	if [ $2 -lt 16 ]; then
	       hex_value="0"${hex_value}
	fi
	if [ $cmd -eq 1 ]; then
		echo "Creating directory: $root_path/$hex_value"
		mkdir -p $root_path/$hex_value
	else
		echo "Removing directory: $root_path/$hex_value"
		rm -r $root_path/$hex_value
	fi
	if [ $? -ne 0 ]; then
		error_count=$((error_count + 1))
	fi
}

#########################
# default values
# path = current directory
# count = 100
# command = Create directories
########################
path=""
count=256
flag=0

Usage() {
	echo "Usage: `basename $0` [-h] [-cr] -p <path> -n <#of directories> "
	echo "      -c -- create directories"
	echo "      -r -- remove directories"
}


# parse command line options
while getopts hcp:rn: o ; do
	case "$o" in
	h)
		Usage
		exit 0
		;;
	c)	# create directories
		if [ $flag -eq 2 ]; then
			echo "Cannot have -c and -r together"
			exit 1
		else
			flag=1
		fi
		;;
	r)	# remove directories
		if [ $flag -eq 1 ]; then
			echo "Cannot have -r and -c together"
			exit 1
		else
			flag=2
		fi
		;;
	p)
		path="$OPTARG"
		;;
	n)
		count="$OPTARG"
		;;
	*)
		Usage
		exit 0
		;;
	esac
done

# echo "Calling hex_convert with $path and $j $flag"

#if [ `expr $path = ""` = 1 ]
if [ "$path" = "" ]; then
	echo "Error: No directory path given .."
	Usage
	exit 1
fi

if [ $flag -eq 0 ]; then
	echo "Error: No -c or -r option given .."
	Usage
	exit 1
fi

j=0
while [ $j -lt $count ]; do
	hex_convert $path  $j $flag
	j=`expr $j + 1`
	echo "calling hex_convert with $path and $j $flag"
done

# Create/Delete directory for storing index files.
if [ $flag -eq 1 ]; then
	echo "Creating index file directory: $path/index_files"
	mkdir -p $path/index_files
else
	echo "Removing directory: $path/index_files"
	rm -r $path/index_files
fi

# Create/Delete directory for storing stat files.
if [ $flag -eq 1 ]; then
	echo "Creating stats file directory: $path/stats"
	mkdir -p $path/stats
else
	echo "Removing directory: $path/stats"
	rm -r $path/stats
fi

echo "Error count = $error_count .."

if [ $error_count -ne 0 ]; then
        exit 1
else
        exit 0
fi
