#!/bin/sh
# NetScaler Startup script - part 2
# FILE:         $Id: //depot/main/rs_111/usr.src/netscaler/scripts/nsstartpe.sh#1 $
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
# Options:	NONE
#

NETSCALER=${NETSCALER:-`cat /var/run/.NETSCALER`}
[ -r ${NETSCALER}/netscaler.conf ] && . ${NETSCALER}/netscaler.conf

NCORE=`/sbin/sysctl -n netscaler.nCore`
SYSID=`/sbin/sysctl -n netscaler.sysid`
SDXVPX=`/sbin/sysctl -n netscaler.sdxvpx`
VPXONCLOUD=`/sbin/sysctl -n netscaler.vpx_on_cloud`

issmallvar()
{
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
done
return 0;
}

runex()
{
	if [ -x $NETSCALER/$1 ]
	then
		$NETSCALER/$1 $2 &
		echo $1 >>$NSKILLLIST
	fi
}

runsh()
{
	if [ -r $NETSCALER/$1 ]
	then
		sh $NETSCALER/$1 $2 &
		echo $1 >>$NSSTOPLIST
	fi
}

run_constant_profiling()
{

	# Do not run constant profiling on non-nCore builds
	if [ $NCORE -ne 1 ]
	then
        	return 0;
	fi

	# Run constant profiling for all other platforms/builds
	runex nsproflog.sh "cpuuse=900 start"
}

mkdir_default_krb()
{
	BASE_PATH_KRB="/nsconfig/krb"
	BASE_PATH_LSCHEMA="/nsconfig/loginschema"

	if [ ! -d ${BASE_PATH_KRB} ]
	then
		mkdir -m 0777 ${BASE_PATH_KRB}
	fi

	if [ ! -d ${BASE_PATH_LSCHEMA} ]
	then
		mkdir -m 0777 ${BASE_PATH_LSCHEMA}
	fi
}

start_monit_on_iked()
{
	MONITRC=/etc/monitrc
        CHECK_IKED_PROCESS="check process iked with pidfile /var/run/iked.pid"

        if test "`grep \"$CHECK_IKED_PROCESS\" $MONITRC`"
        then
                return
        fi


	echo "## Check iked, which is a daemon to do IKE handshake for IPSEC" >> $MONITRC
	echo $CHECK_IKED_PROCESS >> $MONITRC
	echo "  start program  \"/bin/sh /etc/iked_ctl start\"" >> $MONITRC
	echo "  stop program   \"/bin/sh /etc/iked_ctl stop\"" >> $MONITRC

	killall -HUP monit
}

start_monit_on_nskrb()
{
	MONITRC=/etc/monitrc
        CHECK_NSKRB_PROCESS="check process nskrb with pidfile /var/run/nskrb.pid"

        if test "`grep \"$CHECK_NSKRB_PROCESS\" $MONITRC`"
        then
                return
        fi


	echo "## Check nskrb, the netscaler kerberos daemon" >> $MONITRC
	echo $CHECK_NSKRB_PROCESS >> $MONITRC
	echo "  start program  \"/bin/sh /etc/nskrb_ctl start\"" >> $MONITRC
	echo "  stop program   \"/bin/sh /etc/nskrb_ctl stop\"" >> $MONITRC

	killall -HUP monit
}

start_monit_on_nscrlrefresh()
{
	MONITRC=/etc/monitrc
	CHECK_NSCRLREFRESH_PROCESS="check process nscrlrefresh with pidfile /var/run/nscrlrefresh.pid"

	if test "`grep \"$CHECK_NSCRLREFRESH_PROCESS\" $MONITRC`"
	then
		return
	fi

	echo "## Check nscrlrefresh, a netscaler daemon" >> $MONITRC
	echo $CHECK_NSCRLREFRESH_PROCESS >> $MONITRC
	echo "  start program  \"/bin/sh /etc/nscrlrefresh_ctl start\"" >> $MONITRC
	echo "  stop program   \"/bin/sh /etc/nscrlrefresh_ctl stop\"" >> $MONITRC

	killall -HUP monit
}

start_monit_on_websocketd()
{
	MONITRC=/etc/monitrc
	CHECK_WEBSOCKETD_PROCESS="check process websocketd with pidfile /var/run/websocketd.pid"

	if test "`grep \"$CHECK_WEBSOCKETD_PROCESS\" $MONITRC`"
	then
		return
	fi

	echo "## Check websocketd, a netscaler daemon" >> $MONITRC
	echo $CHECK_WEBSOCKETD_PROCESS >> $MONITRC
	echo "  start program  \"/bin/sh /etc/websocketd_ctl start\"" >> $MONITRC
	echo "  stop program   \"/bin/sh /etc/websocketd_ctl stop\"" >> $MONITRC
	
	killall -HUP monit
}

start_monit_on_safenet_gw()
{
    BASE_PATH_SAFENET="/var/safenet/safenet_is_enrolled"

    if [ ! -r "${BASE_PATH_SAFENET}" ]
    then
        return
    fi

    MONITRC=/etc/monitrc
    CHECK_SAFENET_GW_PROCESS="check process safenet_gw with pidfile /var/run/safenet_gw.pid"
    if test "`grep \"$CHECK_SAFENET_GW_PROCESS\" $MONITRC`"
    then
        return
    fi

    echo "## Check safenet_gw, a netscaler daemon" >> $MONITRC
    echo $CHECK_SAFENET_GW_PROCESS >> $MONITRC
    echo " start program \"/bin/sh /etc/safenet_gw_ctl start\"" >> $MONITRC
    echo " stop program \"/bin/sh /etc/safenet_gw_ctl stop\"" >> $MONITRC

    killall -HUP monit
}


start_ptp_on_cluster()
{
    NS_CONF="/nsconfig/ns.conf"
    CHECK_CLUSTER_INSTANCE="add cluster instance"

    if test "`grep \"$CHECK_CLUSTER_INSTANCE\" \"$NS_CONF\"`"
    then
       CLUSTER=1
    else
       CLUSTER=0
    fi

    if [ $CLUSTER -eq 1 ] ;
    then
      runex ptpd
      echo "PTPd started!!\n"
    fi
}

start_provserverd()
{
    NS_CONF="/nsconfig/ns.conf"
    CHECK_CLUSTER_INSTANCE="add cluster instance"

    if test "`grep \"$CHECK_CLUSTER_INSTANCE\" \"$NS_CONF\"`"
    then
       CLUSTER=1
    else
       CLUSTER=0
    fi
    if [ $CLUSTER -eq 0 ] ;
    then
      echo "Starting AutoScale Daemon \n"
	  $NETSCALER/provserverd -S
	else
      echo "Not starting AutoScale daemon for cluster."
    fi
}


: >$NSSTOPLIST
: >$NSKILLLIST

smp=""
[ -f ${NSCONFIG}/.nssmp ] && smp="-s"

# The former contents of nsstart_pre_nsconfread.sh:
runex nsmap "-l"
if [ "$2" != "kmpemode" ]; then
	runex nsaggregatord
	runex nsclusterd
fi
# (end of former contents of nsstart_pre_nsconfread.sh)

if [ "$2" != "kmpemode" ]; then
runex monuploadd
fi

echo -e "\nStarting NSconfigd now..."
$NETSCALER/nsconfigd -S
echo -e "\nNsconfigd started successfully"

# tell pitboss we're in third stage initialization
$NETSCALER/pb_tool -s 3

if [ "$2" = "kmpemode" ]
then
	runex nswsrun "$smp"
fi

runex nsfsyncd -d

# start nsfsyncd client

if ! grep nsfsyncd /etc/crontab > /dev/null 2>&1; then
	# Avoid duplicate nsfsyncd entries in the case that /etc/crontab
	# is a link to /nsconfig/crontab, and this linked file has nsfsyncd
	# entries (by mistake).
	chmod u+w /etc/crontab
	echo "*       *       *       *       *       root    nsfsyncd -p" >> /etc/crontab
	chmod u-w /etc/crontab
fi

runex nsreadfile
runex imi "-d"
runex migrate_ZebOS.sh "--restart-daemons"
# -Q - check that we really have LCD at the serial port
#runex nslcd "-kQ"
# -Q (read on lcd port) is failing at many places [hardware problem]
if [ ! $(expr $SYSID / 1000) = "450" ]
then
	runex nslcd "-k"
fi

#Any daemon that would be monitored by monit needs 
#capabilities to dynamically edit the monitrc file in nsstartpe.sh
#monitrc file should not be hardcoded with netscaler daemons !
#Follow the below primer for future monit clients 

#spawning nscrlrefresh
killall nscrlrefresh
rm -f /var/run/nscrlrefresh.pid
/bin/sh /etc/nscrlrefresh_ctl start
#Dynamically append monitrc file to include nscrlrefresh
start_monit_on_nscrlrefresh

#spawning websocketd
killall websocketd
rm -f /var/run/websocketd.pid
/bin/sh /etc/websocketd_ctl start
#Dynamically append monitrc file to include websocketd
start_monit_on_websocketd

#Start nskrb daemon
if [ "$2" != "kmpemode" ]; then
  rm -f /var/run/nskrb.pid
  runex nskrb "nsauth"
  start_monit_on_nskrb
  mkdir_default_krb
fi

#start safenet_gw daemon
if [  -r "/var/safenet/safenet_is_enrolled" ]
then
    killall safenet_gw
    rm -f /var/run/safenet_gw.pid
    /bin/sh /etc/safenet_gw_ctl start
    #Dynamically append monitrc file to include safenet_gw
    start_monit_on_safenet_gw
fi

runex nsvpnd "7776"
runex nsslapapp
runex nsaaad "-n 10 -p 8766"
# In Tagma release, we are not supporting advanced EPA. So, now we no more need epa service running.
#runex nsepasvc /* Review with Bharat */
if [ "$2" != "kmpemode" ]; then
killall iked
rm -f /var/run/iked.pid
runex iked
start_monit_on_iked
fi
runex aslearn "-start -f /netscaler/aslearn.conf"
runex snmpd

$NETSCALER/nsclfsyncd -s
start_provserverd
$NETSCALER/nsrised -S

# XXX until nsaggregatord problem is resolved
runsh nslog.sh "start $NSLOG_OPTIONS"
runsh nssync.sh "start"
runsh nsumon.sh "start"
if [ $SYSID = "450000" -o $SDXVPX = "1" ]
then
	runsh nsvpx-distribution.sh "start"
fi

$NETSCALER/nscfsyncd -s

if [ "$2" = "kmpemode" ]; then
	runsh nsdiskspace.sh "start"
fi
if [ "$2" != "kmpemode" ]; then
	runex syshealthd
	runex nscac64p
fi
# Only start nscollect when there is enough room
issmallvar
if [ $? -eq 1 ]
then
	echo "/var size < 1GB: nscollect not started"
else
	runex nscollect "start"
	runex nscollect "aggmode"
fi
# Uncomment the line below to enable Netscaler apachemon daemon.
#runex apachemon
# Uncomment the line below to enable Netscaler syslog daemon.
#runex nsconmsg "-s syslog=1 -d eventwait"

# Run constant profiling on selected platforms/builds.
run_constant_profiling

if [ $SYSID = "450040" ]  ;then
	if [ -e /flash/nsconfig/.AWS/firstboot ] ; then
		echo "AWS configuring network ... calling NSVAconf "
		mkdir -p /var/nstmp/awsconfigd
       		cp /flash/nsconfig/.AWS/resolv.conf /etc/resolv.conf
	fi
	/netscaler/NSVAconf -f
	/netscaler/awsconfig
fi

# Start PTPd
start_ptp_on_cluster

# start nscopo daemon
runex nscopo "-vv -p"
if [ $VPXONCLOUD = "3" ] ; then
	cp /flash/nsconfig/.AZURE/resolv.conf /etc/resolv.conf
	$NETSCALER/waagent -run
fi

# start nstraceaggregator
runex nstraceaggregator

[ -r $NSCONFIG/nsafter.sh ] 	&& sh $NSCONFIG/nsafter.sh
[ -r $NSCONFIG/nsafterupgrade.sh ] 	&& sh $NSCONFIG/nsafterupgrade.sh && rm -f $NSCONFIG/nsafterupgrade.sh

# BUG0357011
sleep 5
[ -r $NSCONFIG/rc.netscaler ]	&& sh $NSCONFIG/rc.netscaler
exit 0
