#!/usr/bin/bash

# FILE:         $Id: //depot/main/rs_111/usr.src/netscaler/scripts/nsproflog.sh#1 $
# LAST CHECKIN: $Author: build $
#               $DateTime: 2016/06/13 09:44:25 $
#
# Copyright 2010-2016 Citrix Systems, Inc. All rights reserved.
# This software and documentation contain valuable trade
# secrets and proprietary property belonging to Citrix Systems, Inc.
# None of this software and documentation may be copied,
# duplicated or disclosed without the express
# written permission of Citrix Systems, Inc.

# NetScaler profiler log script
#

NETSCALER=${NETSCALER:-`cat /var/run/.NETSCALER 2>/dev/null`}
[ -r ${NETSCALER}/netscaler.conf ] && . ${NETSCALER}/netscaler.conf

LOG=${NSLOG}

NSPROFLOG_PID=${NSPID_DIR}/nsproflog.pid
NSPROFLOG_MGMTPID=${NSPID_DIR}/nsprofmgmt.pid
NSPROFLOG_SIZE=10485760
NSPROFLOG_FILENO=0
NSPROFLOG_HIT_PERCENTAGE=-1
NSPROFLOG_NEXTFILE=${NSPROFLOG_DIR}/nsproflog.nextfile
NSPROFLOG_CURFILE=${NSPROFLOG_DIR}/${NSPROFLOG_FILE}
NSKERNEL_DEBUG_FILE=""
NSPROFLOG_CPU_CURFILE=""
NSPROFLOG_END_FILENO=10
NSPROFLOG_SET_MODE=""
NSAPIMGR_SET_MODE=""
NSAPIMGR_UNSET_MODE=""
NSPROFLOG_USER_MODE=""
NSPROFLOG_INT_MODE=0
NSPROFLOG_INT_START=0
NSPROFLOG_CPU=-1
NSPROFLOG_CAPTURETIME=-1
NSPROFLOG_LOOP=0
NSPROFLOG_OLDFILES=0
NSPROFLOG_PROFBUF_SIZE=0
NSKERNEL_FILE=""
NSPROFLOG_CAPTURED_FILE=""
NSPROFLOG_DISPLAY=0
NSPROFLOG_KERNELHITS=0
NSPROFLOG_PPEHITS=0
NSPROFLOG_AGGRHITS=0
NSPROFLOG_PARAMETERS=""
NSTRACE_STATE=0
# Interval is in seconds. Recommended: 4-60
: ${NSPROFLOG_MGMTINTERVAL:=6}
# Number of processes listed.  Assume good procs use >20% CPU.
: ${NSPROFLOG_MGMTHISTORY:=6}

# expand on netscaler.conf mitigation of $PATH security exploit
CUT=/usr/bin/cut
DATE="/bin/date -u"
PS=/bin/ps
NSPROFMGMT=$NETSCALER/nsprofmgmt

NON_NETSCALER=0
NCORE=`sysctl -n netscaler.nCore 2>/dev/null`
if [ $? -ne 0 ]
then
	# Unable to get the netscaler.nCore value. The device might not be netscaler.
	# This option will be used only for displaying the output on upload servers.
	# Assuming that default option is NCORE for displaying.
	NON_NETSCALER=1
	NCORE=1
	NETSCALER=~upload/tools
fi

trap nsproflog_exit_signal 2 15

########################################################################

nsproflog_init()
{
	check_ns nsproflog
	
	# save the current log file into next file and compress it
	[ -f $NSPROFLOG_CPU_CURFILE ] && NSPROFLOG_OLDFILES=1 && nszip_proflogfiles
	$NETSCALER/nsapimgr $NSAPIMGR_SET_MODE
	$NETSCALER/nsprofmon $cpu -ys profbuf=$NSPROFLOG_PROFBUF_SIZE $NSPROFLOG_USER_MODE $NSPROFLOG_SET_MODE
	echo $NSPROFLOG_PARAMETERS > ${NSPROFLOG_DIR}/nsproflog_options 
}

nszip_proflogfiles()
{
	local newfile 

	# get the next file number
	if [ -f $NSPROFLOG_NEXTFILE ]
	then
		NSPROFLOG_FILENO=$((`$CAT $NSPROFLOG_NEXTFILE` + 0))
	else
		echo nsproflog: cannot access $NSPROFLOG_NEXTFILE, use 0 >>$LOG
	fi

	if [ $NSPROFLOG_FILENO -ge $NSPROFLOG_END_FILENO ]
	then
		NSPROFLOG_FILENO=0
	fi

	if [ $NSPROFLOG_OLDFILES -eq 1 ]; then
		NSPROFLOG_OLDFILES=0
		# NSPROFLOG_FILE is defined in netscaler.conf
		newfile=${NSPROFLOG_FILE}."old"
		echo Saving old files into ${newfile}.tar.gz >> $LOG
		echo Saving old files into ${newfile}.tar.gz
	else 
		newfile=${NSPROFLOG_FILE}.${NSPROFLOG_FILENO}
	fi
	cd ${NSPROFLOG_DIR};
	echo nsproflog: `date`: renaming current nsproflog files into ${newfile}.tar.gz >>$LOG
	if [ -f $newfile -o -f ${newfile}.tar -o -f ${newfile}.tar.gz ] ; then
		echo nsproflog: target file $newfile already exists >>$LOG
		$RM -rf $newfile ${newfile}.tar ${newfile}.tar.gz >/dev/null 2>&1
	fi
	
	if [ ! -d $newfile ]
        then
        	mkdir -p $newfile
        fi
	
	$MV `basename ${NSPROFLOG_FILE}`"_cpu_"*.out ${NSPROFLOG_DIR}/nsproflog_args $newfile >/dev/null 2>&1
	if [ $? -ne 0 ]
	then
                 echo nsproflog: failed to move newproflog_cpu_*.out to $newfile >>$LOG
        fi

	tar cf ${newfile}.tar $newfile >/dev/null 2>&1
	if [ $? -eq 0 ]
	then
		$NICE -15 $GZIP -f ${newfile}.tar 
	else
		echo nsproflog: failed to tar $newfile >>$LOG
	fi
	echo "Saved profiler capture data in ${newfile}.tar.gz"
	NSPROFLOG_FILENO=$(($NSPROFLOG_FILENO + 1))
	echo $NSPROFLOG_FILENO >$NSPROFLOG_NEXTFILE
	echo nsproflog: next backup file number will be $NSPROFLOG_FILENO >>$LOG
	$RM -rf $newfile ${NSPROFLOG_FILE}"_cpu_"*.out >/dev/null 2>&1
}

nsproflog_run()
{
	check_ns nslog
	
	if [ ! -f ${NSPROFLOG_DIR}/nsproflog_args ]
        then
		echo $0 $NSPROFLOG_PARAMETERS > ${NSPROFLOG_DIR}/nsproflog_args
        fi

	if [ $NSPROFLOG_INT_MODE -eq 0 ]
	then
		echo "$NETSCALER/nsprofmon $cpu -O -k $NSPROFLOG_CPU_CURFILE
				-T $NSPROFLOG_CAPTURETIME -ye capture" >> ${NSPROFLOG_DIR}/nsproflog_args
		$NETSCALER/nsprofmon $cpu -O -k $NSPROFLOG_CPU_CURFILE -T $NSPROFLOG_CAPTURETIME -ye capture &
	else
		echo "$NETSCALER/nsprofmon $cpu $NSPROFLOG_SET_MODE -O -k $NSPROFLOG_CPU_CURFILE
				-s logsize=$NSPROFLOG_SIZE -ye capture" >> ${NSPROFLOG_DIR}/nsproflog_args
		$NETSCALER/nsprofmon $cpu $NSPROFLOG_SET_MODE -O -k $NSPROFLOG_CPU_CURFILE -s logsize=$NSPROFLOG_SIZE -ye capture &
	fi
}

nsproflogmgmt_cleanup()
{
	echo "Stopping mgmt profiler process"
	$NSPROFMGMT -q
}

nsproflog_cleanup()
{
	echo "Stopping all profiler processes"
	$KILLALL -9 nsprofmon >/dev/null 2>&1
	if [ -f $NSPROFLOG_PID ]
	then
		if [ $NSPROFLOG_INT_START -ne 1 ] 
		then
			# cleanup is being done for other script or 
			# in other words we are in 'stop' context
			$KILL -9 `$CAT $NSPROFLOG_PID` >/dev/null 2>&1
		fi
		for cpu in `cat /var/nsproflog/ppe_cores.txt`
		do
			cpu="-s cpu=$cpu"
			echo "Removing buffer for $cpu"
			$NETSCALER/nsprofmon $cpu -yS profbuf
		done
		if [ $NSPROFLOG_INT_START -eq 0 ] 
		then
			# Fetch the options given during start for proper cleanup
			if [ -f ${NSPROFLOG_DIR}/nsproflog_options ]
			then
				NSPROFLOG_PARAMETERS=`cat ${NSPROFLOG_DIR}/nsproflog_options`
				for i in $NSPROFLOG_PARAMETERS
				do
					case $i in
						cpuuse=[0-9]* )
							NSAPIMGR_UNSET_MODE=" -ys lctnetio=0"
							;;
						lctnetio=[0-9]* )
							NSAPIMGR_UNSET_MODE=" -ys lctnetio=0"
							;;
						lctidle=[0-9]* )
							NSAPIMGR_UNSET_MODE=" -ys lctidle=0"
							;;
						lctbsd=[0-9]* )
							NSAPIMGR_UNSET_MODE=" -ys lctbsd=0"
							;;
						lcttimer=[0-9]* )
							NSAPIMGR_UNSET_MODE=" -ys lcttimer=0"
							;;
						lcttimerexec=[0-9]* )
							NSAPIMGR_UNSET_MODE=" -ys lcttimerexec=0"
							;;
						lctoutnetio=[0-9]* )
							NSAPIMGR_UNSET_MODE=" -ys lctoutnetio=0"
							;;
						* )
							;;
					esac
				done
			fi
		fi
		nszip_proflogfiles
		$NETSCALER/nsapimgr $NSAPIMGR_UNSET_MODE
		$RM -f /var/nsproflog/ppe_cores.txt
		$RM -f $NSPROFLOG_PID
		echo " nsproflog killed..." >>$LOG
	fi

    NSPROFLOG_INT_START=0 nsproflogmgmt_cleanup
}

nsproflog_exit_signal()
{
	echo nsproflog: `date`: killed >>$LOG
	#If nsproflog.sh with display option is killed, then cleanup is not needed.
	#Clean up will stop all the existing profiler processes on the system and
	#resets the profiler config in PEs and kernel.
	if [ $NSPROFLOG_DISPLAY -eq 0 ]
	then
		nsproflog_cleanup
	fi
	exit 2
}

nsproflog_exit_normal()
{
	echo nsproflog: `date`: exit after complete >>$LOG
	nsproflog_cleanup
	exit 0
}
	
	
nsproflog_usage_ncore()
{
	echo "nsproflog - utility to start/stop NetScaler profiler to capture data and to display the profiled data"
	echo "usage: `basename $0` [-h] [cpu=<cpu-id>] [cpuuse=<cpu_utilization_in_percentage*10> | lctnetio=<time_in_microseconds>"
	echo "                      | lctidle=<time_in_microseconds> | lctbsd=<time_in_microseconds> | lcttimer=<time_in_microseconds> | lcttimerexec=<time_in_microseconds>"
	echo "                      | lctoutnetio=<time_in_microseconds> | time=<time_in_seconds>] [loop=<count>] [hitperc=<value_in_percentage>]"
	echo "                     [display=<capture_file_path>] [kernel=<nsppe_file_path>] [start | stop]"
	echo ""
	echo "	   -h         - print this message - exclusive option"
	echo ""
	echo "Options used for starting the profiler:"
	echo "          start       - start the capture"
	echo " 	  cpu         - cpu-id on which profiler needs to capture data, default: on all cpus"
	echo "          cpuuse      - threshold value (in cpu_percentage*10) when cpu utilization exceeds above"
	echo "       		        this will trigger profiler to start capturing data in newproflog_cpu_<cpu-id>.out"
	echo "          lct* 	      - help to find Lost CPU Time (in microseconds),when CPU cycles are spent for longer duration in functions other than packet processing"
	echo "			LCT have following options:"
	echo "                          lctidle - Amount of time spent in idle function"
	echo "                          lctnetio - Amount of time spent in netio"
	echo "                          lcttimer - Amount of timer HA timer is not called"
	echo "                          lcttimerexec - Amount of time spent in executing NetScaler timeout functions e.g pe_dotimeout etc"
	echo "              		  lctbsd - Amount of time spent in freebsd"
	echo "              		  lctoutnetio - Amount of time spent since netio is called again"
	echo "          time        - time (in seconds) to capture the profiler data before restarting a new capture"
	echo "          loop        - number of iterations of the profiler captures" 
	echo ""
	echo "Options used for displaying the profiler data:"
	echo "          hitperc     - hit percentage threshold for displaying functions with Hitratio (Number of hits for the function in percentage)"
	echo "                        above the threshold, default: 1%"
	echo "          display     - display profiled data captured for specific cpu-id from capture file e.g newproflog_cpu_<cpu-id>.out"
	echo "          kernelhits  - display hits symbols for kernel profile data captured for specific cpu-id from capture file e.g newproflog_cpu_<cpu-id>.out"
	echo "          ppehits     - display hits symbols for PPE profile data captured for specific cpu-id from capture file e.g newproflog_cpu_<cpu-id>.out"
	echo "          aggrhits    - display aggregated hits symbol for combined kernel and PPE data captured for specific cpu-id from capture file e.g newproflog_cpu_<cpu-id>.out"
	echo "          kernel      - NetScaler nsppe file path, default: /netscaler/nsppe"
	echo ""
	echo "Options used for stopping the profiler:"
	echo "          cpu         - cpu-id on which profiler needs to be stopped, default: on all cpus"
	echo "          stop        - stop the capture and generate a .tar.gz file for the captured outputs"
	echo ""
	echo "Examples:"
	echo "To start the profiler with a threshold above 70% cpu utilization to capture data on all the cpus"
	echo "nsproflog.sh cpuuse=700 start"                    
	echo ""			
	echo "To start the profiler with capture when lost cpu time exceeds 2 milliseconds inside idle functions"
	echo "nsproflog.sh lctidle=2000 start"     
	echo ""		
	echo "To stop the profiler and generate the .tar.gz of all captured data"
	echo "nsproflog.sh stop"        
	echo ""		
	echo "To display captured data for all function with HitRatio > 1%"
	echo "nsproflog.sh display=/var/nsproflog/newproflog.0/newproflog_cpu_1.out"           
	echo ""		
	echo "To display captured data for all function with HitRatio > 0%"
	echo "nsproflog.sh hitperc=0 display=/var/nsproflog/newproflog.0/newproflog_cpu_1.out kernel=/netscaler/nsppe"
	echo ""		
	echo "To display captured data for kernelhits with HitRatio > 0%"
	echo "nsproflog.sh hitperc=0 kernelhits=1 display=/var/nsproflog/newproflog.0/newproflog_cpu_1.out kernel_debug=kernel.debug"
	echo ""		
	echo "To display captured data for ppehits with HitRatio > 0%"
	echo "nsproflog.sh hitperc=0 ppehits=1 display=/var/nsproflog/newproflog.0/newproflog_cpu_1.out"
	echo ""		
	echo "To display captured data for aggrhits with HitRatio > 0%"
	echo "nsproflog.sh hitperc=0 aggrhits=1 display=/var/nsproflog/newproflog.0/newproflog_cpu_1.out kernel_debug=kernel.debug kernel=/netscaler/nsppe"
	echo ""		
	echo "Notes:"
	echo "If another instance of profiler is already running, then please stop the current profiler, 
before running the new instance of profiler with a different CPU threshold"
	echo "For kernelhits/aggrhits please provide a valid kernel debug file. (kernel.debug)"
	exit 0
}

nsproflog_usage_classic()
{
	echo "nsproflog - utility to start/stop NetScaler profiler log and to display the profiled data"
	echo "usage: `basename $0` start [cpuuse=<cpu utilization in percentage*10>] [-userprof] &"
	echo "			    [time=<capturetime in sec>] [loop=<number of iterations>] [-h] &"
	echo "  	-h        - prints this message - exclusive option"
	echo "usage: `basename $0` stop"
	echo "usage: `basename $0` [display=<Captured file name with full path>] [kernel=<kernel name with full path>]"
	exit 0
}

nsproflog_usage_non_netscaler()
{
	echo "nsproflog - utility to display the profiled data"
	echo "usage: `basename $0` [display=<Captured file name with full path>] [kernel=<kernel name with full path>] [-ncore|-classic {default is  -ncore}]"
	echo "  	-h        - prints this message - exclusive option"
	exit 0
}

nsproflog_display()
{
	NSPROFLOG_CAPTURE_BASENAME=`basename $NSPROFLOG_CAPTURED_FILE`
	NSPROFLOG_CAPTURE_DIRNAME=`dirname $NSPROFLOG_CAPTURED_FILE`
	if [ $NSPROFLOG_CAPTURE_DIRNAME = "." ]
	then
		NSPROFLOG_CAPTURE_DIRNAME=`pwd`
	else
		#If relative path is given, then create the absolute path.
		case "$NSPROFLOG_CAPTURE_DIRNAME" in

		/* )
			;;
		* )
			NSPROFLOG_CAPTURE_DIRNAME=`pwd`/$NSPROFLOG_CAPTURE_DIRNAME
			;;
		esac
	fi
	NSPROFLOG_CAPTURE_ARGS=${NSPROFLOG_CAPTURE_DIRNAME}/nsproflog_args

	# If the captured file is a compressed version, first uncomress in a directory,
	# then display output for each file.
	case "$NSPROFLOG_CAPTURE_BASENAME" in

	*.tar.gz )
		NSPROFLOG_UNZIP_OPTIONS="-zxvf"
		NSPROFLOG_TEMP_DIRECTORY=`echo $NSPROFLOG_CAPTURE_BASENAME | sed s/.tar.gz//`
		;;
	*.tar )
		NSPROFLOG_UNZIP_OPTIONS="-xvf"
		NSPROFLOG_TEMP_DIRECTORY=`echo $NSPROFLOG_CAPTURE_BASENAME | sed s/.tar//`
		;;
	* )
		if [ -f $NSPROFLOG_CAPTURE_ARGS ]
                then
			echo "Displaying the profiler command-line arguments used during start of capture"  
                        $CAT $NSPROFLOG_CAPTURE_ARGS
                fi

		if [ $NCORE -eq 1 ]
		then
			if [ $NSPROFLOG_KERNELHITS -eq 1 ]
			then
				$NETSCALER/nsprofmon -ys umpeprof=1 -K $NSKERNEL_DEBUG_FILE -k $NSPROFLOG_CAPTURED_FILE -s hitperc=$NSPROFLOG_HIT_PERCENTAGE -s sort=2 -d kernelhits
			elif [ $NSPROFLOG_PPEHITS -eq 1 ]
                        then
				$NETSCALER/nsprofmon -ys umpeprof=1 -K $NSKERNEL_FILE -k $NSPROFLOG_CAPTURED_FILE -s hitperc=$NSPROFLOG_HIT_PERCENTAGE -s sort=2 -d ppehits
			elif [ $NSPROFLOG_AGGRHITS -eq 1 ]
                        then
				$NETSCALER/nsprofmon -ys umpeprof=1 -K $NSKERNEL_FILE -K $NSKERNEL_DEBUG_FILE -k $NSPROFLOG_CAPTURED_FILE -s hitperc=$NSPROFLOG_HIT_PERCENTAGE -s sort=2 -d aggrhits
			else
				$NETSCALER/nsprofmon -ys umpeprof=1 -K $NSKERNEL_FILE -k $NSPROFLOG_CAPTURED_FILE -s hitperc=$NSPROFLOG_HIT_PERCENTAGE -s sort=2 -d alltexthitsym
			fi
		else
			$NETSCALER/nsprofmon -K $NSKERNEL_FILE -k $NSPROFLOG_CAPTURED_FILE -s hitperc=$NSPROFLOG_HIT_PERCENTAGE -s sort=2 -d alltexthitsym
		fi
		exit 0
		;;
	esac

	mkdir -p /var/tmp/$NSPROFLOG_TEMP_DIRECTORY.$$
	cd /var/tmp/$NSPROFLOG_TEMP_DIRECTORY.$$
	tar $NSPROFLOG_UNZIP_OPTIONS $NSPROFLOG_CAPTURE_DIRNAME/$NSPROFLOG_CAPTURE_BASENAME |  while read FILE; do
		echo "####################################################################"
		echo "Output for capture file $FILE"
		if [ $NCORE -eq 1 ]
		then
			$NETSCALER/nsprofmon -ys umpeprof=1 -K $NSKERNEL_FILE -k $FILE -s sort=2 -d alltexthitsym
		else
			$NETSCALER/nsprofmon -K $NSKERNEL_FILE -k $FILE -s sort=2 -d alltexthitsym
		fi
		echo "####################################################################"
	done
	cd -
	rm -rf /var/tmp/$NSPROFLOG_TEMP_DIRECTORY.$$
}

wait_for_any_child_exit()
{
	## Wait for any child to exit and kill all other children.
	while true
	do
		# kill -0 tests if the PID is a valid running process. No signal is actually sent.
		# If any process doesn't exist, then kill all other nsprofmon processes.
		for pid in $PROF_PIDS
		do
			kill -0 $pid > /dev/null 2>&1
			if [ $? -ne 0 ]
			then
				kill -9 $PROF_PIDS > /dev/null 2>&1
				return
			fi
		done
		sleep 60
	done
}

########################################################################
######			START PROFILING			################
########################################################################	

if [ $NON_NETSCALER -eq 1 ]
then
	NSPROFLOG_USAGE=nsproflog_usage_non_netscaler
elif [ $NCORE -eq 1 ]
then
	echo "nCore Profiling"
	NSPROFLOG_PROFBUF_SIZE=128
	NSPROFLOG_USAGE=nsproflog_usage_ncore
	NSKERNEL_FILE=/netscaler/nsppe
	NSPROFLOG_MINCPU=1
else
	echo "Classic Profiling"
	NSPROFLOG_PROFBUF_SIZE=16
	NSPROFLOG_USAGE=nsproflog_usage_classic
	NSPROFLOG_MINCPU=0
fi

#Save parameters in string
for i in $@
do
	case "$i" in

	start )
		continue
		;;
	stop )
		continue
		;;
	* )
		NSPROFLOG_PARAMETERS=$NSPROFLOG_PARAMETERS" "$i
		;;
	esac
done

while [ $# -gt 0 ]
do
	case "$1" in

	cpu=mgmt )
		if [ $NCORE -eq 0 ]
		then
			$NSPROFLOG_USAGE	
		fi
		NSPROFLOG_CPU=mgmt
		shift
		;;

	cpu=[0-9]* )
		if [ $NCORE -eq 0 ]
		then
			$NSPROFLOG_USAGE	
		fi
		NSPROFLOG_CPU=`echo $1 | cut -d "=" -f 2`
		if [ $NSPROFLOG_CPU -ge `sysctl kern.smp.cpus | cut -d ":" -f 2` -o $NSPROFLOG_CPU -lt $NSPROFLOG_MINCPU ]
		then
			echo "cpu $NSPROFLOG_CPU doesn't exist or cannot be profiled"
			exit 1
		fi
		shift
		;;

	stop )
		if [ "$NSPROFLOG_CPU" = mgmt ]
		then
			echo nsproflog: `date`: killed mgmt by STOP command >>$LOG
			nsproflogmgmt_cleanup
		else
			echo nsproflog: `date`: killed by STOP command >>$LOG
			nsproflog_cleanup
		fi
		exit 0
		;;

	start )
		shift
		#Check whether nstrace is running or not.
	    	#If it is already running, then wait till the process is completely exited.
		#Sleep is needed if nsproflog is restarted from nstrace.
		#Profiler should not be invoked when nstrace is already running to avoid 
		#capturing profiler data that is transfered from nsnetsvc.
		NSTRACE_STATE=$($NETSCALER/nsapimgr -d allvariables | grep tracestate | awk '{print $2;}')
		if [ $NSTRACE_STATE -eq 1 ]
		then
			sleep 3
			if [ $NSTRACE_STATE -eq 1 ]
			then
                        	echo "An instance of nstrace is running. Please stop it or wait till nstrace exits before running profiler."
                        	exit 1
			fi
		fi

		if [ "$NSPROFLOG_CPU" = mgmt ]
		then : skip nsprofmon check
		elif ps x | grep -v grep | grep nsprofmon > /dev/null
		then
                        echo "Another instance of profiler is already running."
						echo "If you want to run the profiler at a different CPU threshold, please stop the current profiler using"
						echo "# nsproflog.sh stop"
						echo "... and invoke again with the intended CPU threshold. Please see nsproflog.sh -h for the exact usage."
                        exit 1
		fi
		NSPROFLOG_INT_START=1
		;;

	cpuuse=[0-9]* )
		if [ "$NSPROFLOG_SET_MODE" != "" ]
		then
			$NSPROFLOG_USAGE	
		fi
		if [ `echo $1 | cut -d "=" -f 2` -ne 0 ]
		then
			NSPROFLOG_SET_MODE=" -ys $1 -ys profmode=cpuuse"
			NSPROFLOG_INT_MODE=1
			NSAPIMGR_SET_MODE=" -ys $1"
			NSAPIMGR_UNSET_MODE=" -ys lctnetio=0"
			NSPROFLOGMGMT_SET_MODE="$1"
		fi
		shift
		;;			

	lctnetio=[0-9]* )
		if [ "$NSPROFLOG_SET_MODE" != "" ]
		then
			$NSPROFLOG_USAGE	
		fi
		NSPROFLOG_SET_MODE=" -ys profmode=lctnetio"
		NSAPIMGR_SET_MODE=" -ys $1"
		NSAPIMGR_UNSET_MODE=" -ys lctnetio=0"
		NSPROFLOG_INT_MODE=2
		shift
		;;			

	lctidle=[0-9]* )
		if [ "$NSPROFLOG_SET_MODE" != "" ]
		then
			$NSPROFLOG_USAGE	
		fi
		NSPROFLOG_SET_MODE=" -ys profmode=lctidle"
		NSAPIMGR_SET_MODE=" -ys $1"
		NSAPIMGR_UNSET_MODE=" -ys lctidle=0"
		NSPROFLOG_INT_MODE=3
		shift
		;;			

	lctbsd=[0-9]* )
		if [ "$NSPROFLOG_SET_MODE" != ""  -o $NCORE -eq 1 ]
		then
			$NSPROFLOG_USAGE	
		fi
		NSPROFLOG_SET_MODE=" -ys profmode=lctbsd"
		NSAPIMGR_SET_MODE=" -ys $1"
		NSAPIMGR_UNSET_MODE=" -ys lctbsd=0"
		NSPROFLOG_INT_MODE=4
		shift
		;;

	lcttimer=[0-9]* )
		if [ "$NSPROFLOG_SET_MODE" != "" ]
		then
			$NSPROFLOG_USAGE
		fi
		NSPROFLOG_SET_MODE=" -ys profmode=lcttimer"
		NSAPIMGR_SET_MODE=" -ys $1"
		NSAPIMGR_UNSET_MODE=" -ys lcttimer=0"
		NSPROFLOG_INT_MODE=5
		shift
		;;

	lcttimerexec=[0-9]* )
		if [ "$NSPROFLOG_SET_MODE" != "" ]
		then
			$NSPROFLOG_USAGE
		fi
		NSPROFLOG_SET_MODE=" -ys profmode=lcttimerexec"
		NSAPIMGR_SET_MODE=" -ys $1"
		NSAPIMGR_UNSET_MODE=" -ys lcttimerexec=0"
		NSPROFLOG_INT_MODE=6
		shift
		;;

	lctoutnetio=[0-9]* )
		if [ "$NSPROFLOG_SET_MODE" != "" ]
		then
			$NSPROFLOG_USAGE
		fi
		NSPROFLOG_SET_MODE=" -ys profmode=lctoutnetio"
		NSAPIMGR_SET_MODE=" -ys $1"
		NSAPIMGR_UNSET_MODE=" -ys lctoutnetio=0"
		NSPROFLOG_INT_MODE=6
		shift
		;;

	-userprof )
		if [ $NCORE -eq 1 ]
		then
			$NSPROFLOG_USAGE
		fi
		shift
		NSPROFLOG_USER_MODE="-ys profmode=userprof"
		;;
	time=[0-9]* )
		NSPROFLOG_CAPTURETIME=`echo $1 | cut -d "=" -f 2`
		shift
		;;
	loop=[0-9]* )	
		NSPROFLOG_LOOP=`echo $1 | cut -d "=" -f 2`
		shift
		;;
	hitperc=[0-9]* )
		NSPROFLOG_HIT_PERCENTAGE=`echo $1 | cut -d "=" -f 2`
		shift
		;;
	display=* )
		NSPROFLOG_CAPTURED_FILE=`echo $1 | cut -d "=" -f 2`
		NSPROFLOG_DISPLAY=1
		shift
		;;
	kernelhits=[1] )
		NSPROFLOG_KERNELHITS=`echo $1 | cut -d "=" -f 2`
		shift
		;;
	ppehits=[1] )
		NSPROFLOG_PPEHITS=`echo $1 | cut -d "=" -f 2`
		shift
		;;
	aggrhits=[1] )
		NSPROFLOG_AGGRHITS=`echo $1 | cut -d "=" -f 2`
		shift
		;;
	kernel=* )
		NSKERNEL_FILE=`echo $1 | cut -d "=" -f 2`
		shift
		;;
	kernel_debug=* )
		NSKERNEL_DEBUG_FILE=`echo $1 | cut -d "=" -f 2`
		shift
		;;
	-ncore )
		shift
		;;
	-classic )
		NCORE=0
		shift
		;;
	*)
		$NSPROFLOG_USAGE
		;;
	esac
done

if [ $NON_NETSCALER -eq 1 ] && [ $NSPROFLOG_DISPLAY -ne 1 ]
then
	#In case of non netscaler, only displaying the output is possible.
	$NSPROFLOG_USAGE
fi

if [ $NSPROFLOG_DISPLAY -ne 1 -a $NSPROFLOG_HIT_PERCENTAGE -ne -1 ]
then
        echo "hitperc option is not valid with lct and cpuuse option"
        exit 1
fi

if [ $NSPROFLOG_KERNELHITS -eq 1  -o  $NSPROFLOG_AGGRHITS -eq 1 ]
then
    if [ ! -f "$NSKERNEL_DEBUG_FILE" ]
    then
        echo "Kernel debug file $NSKERNEL_DEBUG_FILE does not exist. Please provide a valid kernel debug file."
        exit 1
    fi
fi

[ $NSPROFLOG_HIT_PERCENTAGE -eq -1 -a $NSPROFLOG_DISPLAY -eq 1 ] && NSPROFLOG_HIT_PERCENTAGE=1

#If display and kernel options are given in the command line, 
#then display the output using the kernel given.
if [ $NSPROFLOG_DISPLAY -eq 1 ]
then
	if [ "$NSKERNEL_FILE" != "" ]
	then
		if [ ! -f "$NSPROFLOG_CAPTURED_FILE" ]
		then
		  echo "Capture file $NSPROFLOG_CAPTURED_FILE does not not exist. Please provide valid kernel and capture files"
			exit 1
		fi

		if [ ! -f "$NSKERNEL_FILE" ]
		then
		  echo "Kernel file $NSKERNEL_FILE does not exist. Please provide valid kernel and capture files"
			exit 1
		fi

		nsproflog_display
		exit 0
	else
		$NSPROFLOG_USAGE
		exit 1
	fi
fi

if [ $NSPROFLOG_INT_START -eq 0 ]
then
	$NSPROFLOG_USAGE
fi

if [ $NSPROFLOG_INT_MODE -ne 0 -a $NSPROFLOG_CAPTURETIME -ne -1 ]
then
	echo "time option is not valid with lct and cpuuse option"
	exit 1
fi

[ $NSPROFLOG_CAPTURETIME -eq -1 ] && NSPROFLOG_CAPTURETIME=10

if [ ! -d $NSPROFLOG_DIR ]
then
	mkdir -p $NSPROFLOG_DIR
fi

# # # BEGIN special mode for monitoring management CPU

if [ "$NSPROFLOG_CPU" = mgmt ]
then
	if [ $NSPROFLOG_INT_MODE -ne 1 ]
	then
		echo "only cpuuse is supported for cpu=mgmt"
		exit 1
	fi

	THRESHOLDx10=${NSPROFLOGMGMT_SET_MODE##*=}
	THRESHOLD=${THRESHOLDx10%?}
	THRESHOLD=$THRESHOLD.${THRESHOLDx10#$THRESHOLD}
	echo >>$LOG "$($DATE) <local0.info> nsproflog[$$]: starting mgmt monitor"
	exec $NSPROFMGMT $THRESHOLD

elif [ $NSPROFLOG_CPU -eq -1 -a $NSPROFLOG_INT_MODE -eq 1 ]
then
	THRESHOLDx10=${NSPROFLOGMGMT_SET_MODE##*=}
	THRESHOLD=${THRESHOLDx10%?}
	THRESHOLD=$THRESHOLD.${THRESHOLDx10#$THRESHOLD}
	echo >>$LOG "$($DATE) <local0.info> nsproflog[$$]: starting mgmt monitor"
	$NSPROFMGMT $THRESHOLD 2>&1 | while read line
	do	echo >>$LOG "$($DATE) <local0.err> nsprofmgmt: $line"
	done
fi
# # # END special mode for monitoring management CPU

echo $$ >$NSPROFLOG_PID
echo nsproflog: `date`: started as process $$ >>$LOG

if [ $NCORE -eq 0 ]
then
	echo 0 > /var/nsproflog/ppe_cores.txt
elif [ $NSPROFLOG_CPU -eq -1 ]
then
	top | grep "NSPPE-[0-9][0-9]" | awk '{printf "%d\n", $9}' > /var/nsproflog/ppe_cores.txt
else
	#Check whether nsppe is running on that CPU.
	NSPROFLOG_VALID_CPU=0
	for cpu in `top | grep "NSPPE-[0-9][0-9]" | awk '{printf "%d\n", $9}'`
	do
		if [ $NSPROFLOG_CPU -eq $cpu ]
		then
			echo $NSPROFLOG_CPU > /var/nsproflog/ppe_cores.txt
			NSPROFLOG_VALID_CPU=1
			break
		fi
	done
	if [ $NSPROFLOG_VALID_CPU -eq 0 ]
	then
		echo "nsppe is not running on cpu $NSPROFLOG_CPU."
		exit 1
	fi
fi

for cpu in `cat /var/nsproflog/ppe_cores.txt`
do
	NSPROFLOG_CPU_CURFILE=$NSPROFLOG_CURFILE"_cpu_"$cpu".out"
	cpu=" -s cpu=$cpu"
	nsproflog_init
done

[ $NSPROFLOG_LOOP -eq 0 ] && NSPROFLOG_LOOP=-1

while [ true ]
do
	PROF_PIDS=""
	for cpu in `cat /var/nsproflog/ppe_cores.txt`
	do
		echo "Collecting profile data for cpu $cpu"
		NSPROFLOG_CPU_CURFILE=$NSPROFLOG_CURFILE"_cpu_"$cpu".out"
		cpu=" -s cpu=$cpu"
		nsproflog_run
		# Collect the background process pid
		PROF_PIDS="$PROF_PIDS $!"
	done
	echo "Please wait for profiler to capture data"
	if [ $NCORE -eq 0 -o $NSPROFLOG_INT_MODE -eq 0 ]
	then
		# wait will wait for all nsprofmon processes to exit.
		wait
	else
		# If any of the child nsprofmon exits, this script will terminate other nsprofmons.
		wait_for_any_child_exit
	fi
	[ $NSPROFLOG_LOOP -gt 0 ] && NSPROFLOG_LOOP=$(($NSPROFLOG_LOOP - 1)) && [ $NSPROFLOG_LOOP -eq 0 ] && nsproflog_exit_normal
	nszip_proflogfiles	# exit nsproflog_exit_normal itself will do last tar/zip of files.
done
