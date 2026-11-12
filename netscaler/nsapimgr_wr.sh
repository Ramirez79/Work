#! /usr/bin/bash

# Find the number of running PEs and fire the command sequentially to each running PE

num_pe=`sysctl -n netscaler.num_pe_running`
echo "Number of PEs running: $num_pe"
num=0
while [ $num -lt $num_pe ]
do
	# echo "============================= Nsapimgr output for PE$num ================================="
	nsapimgr -s nsppeid=$num $*
	num=$((num+1))
done
