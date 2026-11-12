#!/usr/bin/bash

# Copyright 2009-2016 Citrix Systems, Inc. All Rights Reserved
exec 2>&1 >> /var/log/wfcmd.log
echo `date` ":" $0 $*

if [ ! -e /var/wi/bin/webfront.war ]; then
	echo "Web Front not installed"
	exit 19
fi
if [ -e /var/wi/bin/WebInterface.jar ]; then
	cd /var/wi/tomcat/webapps
	rm -rf ROOT
	cd /var/wi/tomcat/conf/Catalina/localhost
	rm -f ROOT.xml
	cd /var/wi/bin
	rm .installoptswf
	rm webfront.war
	exit 0
fi	
	
grep -w -v java /etc/monitrc | grep -w -v start_tomcat | grep -w -v monit_stop | grep -w -v 8080 > /tmp/1
mv /tmp/1 /etc/monitrc
chmod 0700 /etc/monitrc
killall -HUP monit

cd /var/wi

until [ ! -d $PWD ]; do
	pid=`ps ax | grep java | grep -v grep | grep $PWD`
	if [ ! -z "$pid" ]; then
		kill -KILL `echo " $pid" | awk -F'\ *' '{ print $2 }'`
	fi

	pid=`ps ax | grep nsfsyncd | grep -v grep | grep -v " -d"`
	if [ ! -z "$pid" ]; then
        	kill -KILL `echo " $pid" | awk -F'\ *' '{ print $2 }'`
	fi
	killall rsync >/dev/null 2>&1

	rm -rf $PWD
done;
