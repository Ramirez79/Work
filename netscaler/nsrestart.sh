#!/bin/sh

#ignore SIGHUP
trap "" 1

NETSCALER=${NETSCALER:-`cat /var/run/.NETSCALER`}
[ -r ${NETSCALER}/netscaler.conf ] && . ${NETSCALER}/netscaler.conf

# NSLOG is set up by netscaler.conf
LOG=${NSLOG}

# Show techsupport will collect logs, removing reduntant log collection 
# done in nsrestart.sh script 

# restart nCore by issuing nsshutdown with restart flag.
echo nsrestart.sh: `date` ': Netscaler restarting due to critical failure or explicit restart' >>$LOG
/netscaler/nsshutdown -R -D $1 
RETVAL=$?
if [ $RETVAL -ne 0 ]; then
	echo nsrestart.sh: `date` ': Netscaler restart failed error ' $RETVAL >> $LOG
fi
exit $RETVAL

