#!/usr/bin/bash

# Copyright 2009-2016 Citrix Systems, Inc. All Rights Reserved
exec 2>&1 >> /var/log/wicmd.log
echo `date` ":" $0 $*

if [ ! -d /var/wi ]; then
	echo "Web Interface not installed"
	exit 7
fi

if [ -e /var/wi/bin/webfront.war ]; then
	cd /var/wi/tomcat/webapps
	mv  ROOT ../
	rm -rf *
	mv ../ROOT .
	cd /var/wi/tomcat/conf/Catalina/localhost
	mv  ROOT.xml ../
	rm -rf *
	mv ../ROOT.xml .
	cd /var/wi/bin
	rm .installopts
	rm WebInterface.jar
	rm .maxsites
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
