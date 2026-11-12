#!/usr/bin/bash 

## Copyright 2009-2016 Citrix Systems, Inc. All Rights Reserved

if [ ! -d /var/wi/tomcat/webapps ]; then
        echo "Web Interface not installed"
        exit 7
fi
cd /var/wi

# save and change IFS
OLDIFS=$IFS
IFS=$'\n'
if [ $# -eq 0 ]; then
	if [ -d tomcat/conf/Catalina/localhost ] ; then
		cd tomcat/conf/Catalina/localhost
		files=`ls`
		cd -
	else
		echo "tomcat/conf/Catalina/localhost missing: install web interface first"
		exit 7
	fi
elif [ $# -eq 1 ]; then
	files=$1
	while [ ${files:0:1} == "/" ]; do
		files=${files:1}
	done
else
	echo "Usage: $0 [<sitePath>]"
	exit 1
fi

cd tomcat/webapps

for file in $files;
do
	file="${file%.xml}"
	SITE=`echo $file | sed s/\\\\#/\\\\//g`
	CONFFILE="$SITE/WEB-INF/WebInterface.conf"
	if [ -f "$CONFFILE" ]; then
		agURL=`grep ^AGEWebServiceURL "$CONFFILE" | cut -f 2 -d = | cut -f 1-3 -d '/'`
		staURL=`grep ^CSG_STA_URL1 "$CONFFILE" | cut -f 2 -d = | cut -f 1-3 -d '/'`
		if [ -f "$SITE/index.html" ]; then
			siteType="XenAppWeb"
		elif [ -f "$SITE/launch.jsp" ]; then
			siteType="XenAppServices"
		fi
		ResourceType=`grep ApplicationAccessMethods "$CONFFILE" | cut -f 2 -d "=" | tr -d '\r'`
		if [ "$ResourceType" == "Remote,Streaming" ]; then
			ResourceType="DualMode"
		elif [ "$ResourceType" == "Remote" ]; then
			ResourceType="Online"
		elif [ "$ResourceType" == "Streaming" ]; then
			ResourceType="Offline"
		fi

		echo sitePath: "/"$SITE
		if [ ! -z $agURL ]; then
			echo agURL=$agURL
			echo staURL=$staURL
		fi
		echo siteType: $siteType
		echo publishedResourceType: $ResourceType
		echo
	elif [ $# -eq 1 ]; then
		echo "Site "/"$SITE does not exist"
        	exit 10
	fi
done


if [ $# -eq 1 ]; then
	cd -
	CONFFILE="tomcat/webapps/$SITE/WEB-INF/WebInterface.conf"
	numFarms=`grep ^Farm "$CONFFILE" | wc -l`
	i=1
	while [ $i -le $numFarms ]; do
		FarmDetails=`grep ^Farm$i "$CONFFILE"`
		echo name: `echo $FarmDetails | cut -f 2 -d : | cut -f 1 -d ,`
		echo xmlServerAddresses: `echo $FarmDetails | sed "s/,Name:/=/g" | cut -f 2 -d =`
		echo xmlPort: `echo $FarmDetails | cut -f 3 -d : | cut -f 1 -d ,`
		echo transport: `echo $FarmDetails | cut -f 4 -d : | cut -f 1 -d ,`
		echo loadBalance: `echo $FarmDetails | cut -f 7 -d : | cut -f 1 -d ,`
		echo
        	((i++))
	done	
fi
#Restore IFS
IFS=$OLDIFS

