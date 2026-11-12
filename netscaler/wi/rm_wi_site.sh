#!/usr/bin/bash

# Copyright 2009-2016 Citrix Systems, Inc. All Rights Reserved
exec 2>&1 >> /var/log/wicmd.log
echo `date` ": $0 $*"
if [ $# -ne 1 ]
then
	echo "Usage: $0 <sitePath>"
	exit 1
fi

if [ ! -d /var/wi ]; then
	echo "Web Interface not installed"
	exit 7
fi
cd /var/wi

CATALINA_HOME="$PWD/tomcat"
sitePath="$1"
WAR_FILENAME=`basename "$sitePath"`
WAR_DIR=`dirname "$sitePath"`

if [ ! -d "$CATALINA_HOME/webapps/$WAR_DIR/$WAR_FILENAME" ]; then
        echo "Site $sitePath does not exist"
        exit 10
fi

while [ "$WAR_DIR" != "/" -a "${WAR_DIR:0:1}" == "/" ]; do
	WAR_DIR="${WAR_DIR:1}"
done
if [ "$WAR_DIR" == "." -o "$WAR_DIR" == "/" ]; then
	SITE_PATH="$WAR_FILENAME"
else
	SITE_PATH="$WAR_DIR/$WAR_FILENAME"
fi

CONTEXTFILE=`echo "$SITE_PATH" | sed s/\\\\//\\\\#/g`.xml

if [ ! -e "$CATALINA_HOME/conf/Catalina/localhost/$CONTEXTFILE" ]; then
        echo "Site $sitePath does not exist"
        exit 10
fi

rm -f "$CATALINA_HOME/conf/Catalina/localhost/$CONTEXTFILE"
until [ ! -d "$CATALINA_HOME/webapps/$SITE_PATH" ]; do
	rm -rf "$CATALINA_HOME/webapps/$SITE_PATH"
done
