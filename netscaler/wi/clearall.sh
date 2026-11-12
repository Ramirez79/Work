#!/usr/bin/bash

# Copyright 2009-2016 Citrix Systems, Inc. All Rights Reserved
exec 2>&1 >> /var/log/wicmd.log
if [ ! -d /var/wi ]; then
	exit 0
fi
echo `date` ":" $0 $*

grep -w -v java /etc/monitrc | grep -w -v start_tomcat | grep -w -v monit_stop | grep -w -v 8080 > /tmp/1
mv /tmp/1 /etc/monitrc
chmod 0700 /etc/monitrc
killall -HUP monit

cd /var/wi

pid=`ps ax | grep java | grep -v grep | grep $PWD`
if [ ! -z "$pid" ]; then
        kill -KILL `echo " $pid" | awk -F'\ *' '{ print $2 }'`
fi
pid=`ps ax | grep nsfsyncd | grep -v grep | grep -v " -d"`
if [ ! -z "$pid" ]; then
       	kill -KILL `echo " $pid" | awk -F'\ *' '{ print $2 }'`
fi
killall rsync >/dev/null 2>&1

if [ ! -d cached/contexts ]; then
	mkdir -p cached/contexts
fi
if [ ! -d cached/sites ]; then
	mkdir cached/sites
fi

# save and change IFS
OLDIFS=$IFS
IFS=$'\n'
if [ -d tomcat/conf/Catalina/localhost ] ; then
	cd tomcat/conf/Catalina/localhost
	files=`ls`
	cd -
fi

for file in $files ; do
	mv tomcat/conf/Catalina/localhost/$file cached/contexts/
        SITE=${file%.xml}
        SITE=`echo $SITE | sed s/\\\\#/\\\\//g`
	if [ -e "cached/sites/$SITE" ]; then
		rm -rf "cached/sites/$SITE"
	fi
	CACHE_DIR="cached/sites/`dirname $SITE`"
	if [ ! -d "$CACHE_DIR" ]; then
		mkdir -p "$CACHE_DIR"
	fi
	mv -f "tomcat/webapps/$SITE" "$CACHE_DIR"
done
# restore IFS
IFS=$OLDIFS

rm -rf tomcat/webapps/*
