#!/bin/sh
# Called by nsshutdown to reset cron table and arp entries
# More may be done by this script in the future.
arp -d -a
grep -v nsfsyncd /etc/crontab > /tmp/.crontab.tmp
cp /tmp/.crontab.tmp /etc/crontab
#Any netscaler daemon that could be started and monitored by monit
#needs to be removed before attempting warm restart
grep -v 'iked\|nskrb\|websocketd\|nscrlrefresh\|ntpd' /etc/monitrc > /etc/monitrc.bak
chmod 600 /etc/monitrc.bak
mv /etc/monitrc.bak /etc/monitrc
killall -HUP monit
