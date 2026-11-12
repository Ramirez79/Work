#!/bin/sh
#
# xe-linux-distribution	Write Linux distribution information to XenStore.
#
# chkconfig: 2345 14 86
# description: Writes Linux distribution version information to XenStore.

action()
{
    descr=$1 ; shift
    cmd=$@
    echo -n "$descr "
    $cmd
    ret=$?
    if [ $ret -eq 0 ] ; then
	echo "OK"
    else
	echo "Failed"
    fi
    return $ret
}

run_as_daemon() {
    (  exec &>/dev/null ; "$@" & )
}

NSVPX_DISTRIBUTION=/usr/local/sbin/nsvpx-distribution
NSVPX_DISTRIBUTION_CACHE=/var/cache/nsvpx-distribution
XE_DAEMON=/usr/local/sbin/xe-daemon

if [ ! -x "${NSVPX_DISTRIBUTION}" ] ; then
    exit 0
fi

if [ $(dmesg | sed -n -e 's/^xenstore0/&/p;' | wc -l) -eq 0 ]; then
	exit 0
fi

start()
{
	
	mkdir -p /var/cache

   action $"Detecting NS VPX distribution version:" \
	${NSVPX_DISTRIBUTION} ${NSVPX_DISTRIBUTION_CACHE}

    action $"Starting xe daemon: " /usr/bin/true
    run_as_daemon ${XE_DAEMON}
}

stop()
{
    action $"Stopping xe daemon: "   killall $(basename ${XE_DAEMON}) -TERM
}

case "$1" in
  start)
        start
        ;;
  stop)
	stop
	;;
  *)
        # do not advertise unreasonable commands that there is no reason
        # to use with this device
        echo $"Usage: $0 start"
        exit 1
esac

exit $?

