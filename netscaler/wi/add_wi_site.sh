#!/usr/bin/bash

# Copyright 2009-2016 Citrix Systems, Inc. All Rights Reserved

exec 2>&1 >> /var/log/wicmd.log
echo `date` ": $0 $*"
if [ $# -lt 1 -o $[ $# % 2 ] -eq 0 ]; then
	echo "Usage: $0 <sitePath> [<agURL> <staURL>] [-wiAuthMethods (Explicit | Anonymous | Anonymous,Explicit)] [-siteType (XenAppWeb | XenAppServices)] [-publishedResourceType (Online | Offline | DualMode)] [-authenticationPoint (WebInterface | AccessGateway)] [-kioskMode (On | Off)] [-useTwoTickets (On | Off) -secondSTAURL <staURL> ] [-sessionReliability (On | Off)] [-userInterfaceBranding (Applications | Desktops)] [-loginTitle <loginTitle>] [-defaultCustomTextLocale ( de | en | es | fr | ja | ko | ru )] [-defaultAccessMethod ( Direct | Alternate | Translated )] [-webSessionTimeout <webSessionTimeout>] [-ShowSearch (On | Off)] [-ShowRefresh (On | Off)] [-UserInterfaceMode (Simple | Advanced )] [-UserInterfaceLayout (Auto | Normal | Compact)] [-appWelcomeMessage <appWelcomeMessage>] [-welcomeMessage <WelcomeMessage>] [-footerText <footerText>] [-loginSysMessage <loginSysMessage>] [-preLoginButton <preLoginButton>] [-preLoginMessage <preLoginMessage>] [-preLoginTitle <preLoginTitle>] [-domainSelection <domain1,domain2,...>] [-RestrictDomains <RestrictDomains>] [-LoginDomains <LoginDomains>] [-HideDomainField <HideDomainField] [-VpnVserver <vpnvserver>]"
	echo "E.g.: $0 /Citrix/XenApp http://sslvpn.mycompany.com http://stasever"
	exit 1
fi

if [ ! -d /var/wi ]; then
	echo "Web Interface not installed"
	exit 7
fi
cd /var/wi

if [ -d tomcat -a -f tomcat/bin/startup.sh -a -f bin/start_tomcat.sh ]; then
	CATALINA_HOME="$PWD/tomcat"
else
	echo "Tomcat not installed: install web interface first"
	exit 7
fi

if [ ! -f bin/WebInterface.jar ]; then
	echo "WebInterface.jar missing: install web interface first"
	exit 7
fi

if [ ! -f bin/unzip ]; then
	echo "unzip missing: install web interface first"
	exit 7
fi

JAVA_HOME=`grep JRE_HOME= bin/start_tomcat.sh | cut -f 2 -d =`
if [ ! -f "$JAVA_HOME/bin/java" ]; then
	echo "Java not found @ $JAVA_HOME : install web interface first"
	exit 7
fi

javaproc=`ps ax | grep java | grep -v grep | grep $PWD`
if [ -z "$javaproc" ]; then
	echo "Tomcat not running : install web interface first"
	exit 7
fi

maxSites=3;
if [ -f bin/.maxsites ]; then
	maxSites=`cat bin/.maxsites`;
fi

#Parse & validate the options
sitePath="$1"
agURL=""
staURL=""
siteType="XenApp Web - users access resources through their Web browsers"
publishedResourceType="Online - resources are hosted on remote servers"
ShowSearch=""
ShowRefresh=""
UserInterfaceMode=""
UserInterfaceLayout=""
RestrictDomains=""
LoginDomains=""
HideDomainField=""
VpnVserver=""

i=1
for argval in "$@"; do
	arg["$i"]="$argval"
	((i++))
done

i=2
while [ "$i" -le $# ]; do
	case  "${arg[$i]}" in
	-authenticationPoint)
		((i++))
    		authenticationPoint="${arg[$i]}"
		if [ "$authenticationPoint" != "WebInterface" -a "$authenticationPoint" != "AccessGateway"  ]; then
			echo "Specify WebInterface or AccessGateway value for authenticationPoint"
			exit 1
		fi
	;;
	-kioskMode)
		((i++))
    		kioskMode="${arg[$i]}"
		if [ "$kioskMode" != "On" -a "$kioskMode" != "Off"  ]; then
			echo "Specify On or Off value for kioskMode"
			exit 1
		fi
	;;
	-publishedResourceType)
		((i++))
    		publishedResourceType="${arg[$i]}"
		if [ "$publishedResourceType" == "Online" ]; then
			publishedResourceType="Online - resources are hosted on remote servers"
		elif [ "$publishedResourceType" == "Offline" ]; then
			publishedResourceType="Offline - applications are streamed to users"
		elif [ "$publishedResourceType" == "DualMode" ]; then
			publishedResourceType="Dual mode - users can access both online and offline resources"
		else
			echo "Specify Online or Offline or DualMode value for publishedResourceType"
			exit 1
		fi
	;;
	-secondSTAURL)
		((i++))
    		secondSTAURL="${arg[$i]}"
		STA_SERVER2=`echo "$secondSTAURL" | cut -s -f 3 -d '/' -`
		STA_PROT2=`echo "$secondSTAURL" | cut -s -f 1 -d ':' - | tr '[:upper:]' '[:lower:]'`
		if [ -z "$STA_SERVER2" -o -z "$STA_PROT2" ]; then
			echo "Invalid second staURL"
			exit 12
		fi
		if [ "$STA_PROT2" != "http" -a "$STA_PROT2" != "https" ]; then
			echo "Invalid second staURL"
			exit 12
		fi
	;;
	-sessionReliability)
		((i++))
    		sessionReliability="${arg[$i]}"
		if [ "$sessionReliability" != "On" -a "$sessionReliability" != "Off"  ]; then
			echo "Specify On or Off value for sessionReliability"
			exit 1
		fi
	;;
	-siteType)
		((i++))
    		siteType="${arg[$i]}"
		if [ "$siteType" == "XenAppWeb" ]; then
			siteType="XenApp Web - users access resources through their Web browsers"
		elif [ "$siteType" == "XenAppServices" ]; then
			siteType="XenApp Services - users access resources though the Citrix online plug-in"
		else
			echo "Specify XenAppWeb or XenAppServices value for siteType"
			exit 1
		fi
	;;
	-useTwoTickets)
		((i++))
    		useTwoTickets="${arg[$i]}"
		if [ "$useTwoTickets" != "On" -a "$useTwoTickets" != "Off"  ]; then
			echo "Specify On or Off value for useTwoTickets"
			exit 1
		fi
	;;
	-agAuthMethod)
		((i++))
			agAuthMethod="${arg[$i]}"

		VN=`grep "nswi" "/var/wi/bin/.installopts" | cut -d. -f2` 	

		if [ "$VN" -lt "5" -a "$agAuthMethod" == "SmartCard" ]; then
			echo "SmartCard pass through not Supported in this verion of wi"
			exit 18 
		fi
	;;
	-wiAuthMethods)
		((i++))
    		wiAuthMethods="${arg[$i]}"
		if [ "$wiAuthMethods" != "Explicit" -a "$wiAuthMethods" != "Anonymous" -a "$wiAuthMethods" != "Anonymous,Explicit"  ]; then
			echo "Specify Explicit or Anonymous value for wiAuthMethods"
			exit 1
		fi
	;;
	-loginTitle)
		((i++))
		loginTitle="${arg[$i]}"
	;;

	-appWelcomeMessage)
		((i++))
		appWelcomeMessage="${arg[$i]}"
	;;
	-welcomeMessage)
		((i++))
		welcomeMessage="${arg[$i]}"
	;;
	-footerText)
		((i++))
		footerText="${arg[$i]}"
	;;
	-loginSysMessage)
		((i++))
		loginSysMessage="${arg[$i]}"
	;;
	-preLoginButton)
		((i++))
		preLoginButton="${arg[$i]}"
	;;
	-preLoginMessage)
		((i++))
		preLoginMessage="${arg[$i]}"
	;;
	-preLoginTitle)
		((i++))
		preLoginTitle="${arg[$i]}"
	;;
	-domainSelection)
		((i++))
		domainSelection="${arg[$i]}"
	;;
        -webSessionTimeout)
                ((i++))
                webSessionTimeout="${arg[$i]}"
       ;;
	-defaultCustomTextLocale)
		((i++))
		defaultCustomTextLocale="${arg[$i]}"
	;;	
	-defaultAccessMethod)
		((i++))
		defaultAccessMethod="${arg[$i]}"
	;;	
	-userInterfaceBranding)
		((i++))
    		UserInterfaceBranding="${arg[$i]}"
		if [ "$UserInterfaceBranding" != "Desktops" -a "$UserInterfaceBranding" != "Applications" ]; then
			echo "Specify Applications or Desktops value for userInterfaceBranding"
			exit 1
		fi
	;;
	-UserInterfaceMode)
		((i++))
    		UserInterfaceMode="${arg[$i]}"
		if [ "$UserInterfaceMode" != "Simple" -a "$UserInterfaceMode" != "Advanced" ]; then
			echo "Specify Simple or Advanced value for UserInterfaceMode"
			exit 1
		fi
	;;
	-UserInterfaceLayout)
		((i++))
    		UserInterfaceLayout="${arg[$i]}"
		if [ "$UserInterfaceLayout" != "Auto" -a "$UserInterfaceLayout" != "Normal" -a "$UserInterfaceLayout" != "Compact" ]; then
			echo "Specify Auto, Normal or Compact value for UserInterfaceLayout"
			exit 1
		fi
	;;
	-ShowSearch)
		((i++))
		ShowSearch="${arg[$i]}"
		if [ "$ShowSearch" != "On" -a "$ShowSearch" != "Off" ]; then
			echo "Specify On or Off value for ShowSearch"
			exit 1
		fi
	;;
	-ShowRefresh)
		((i++))
		ShowRefresh="${arg[$i]}"
		if [ "$ShowRefresh" != "On" -a "$ShowRefresh" != "Off" ]; then
			echo "Specify On or Off value for ShowRefresh"
			exit 1
		fi
	;;
	-RestrictDomains)
		((i++))
		RestrictDomains="${arg[$i]}"
		if [ "$RestrictDomains" != "On" -a "$RestrictDomains" != "Off" ]; then
			echo "Specify On or Off value for RestrictDomains"
			exit 1
		fi
	;;
	-LoginDomains)
		((i++))
		LoginDomains="${arg[$i]}"
	;;
	-HideDomainField)
		((i++))
		HideDomainField="${arg[$i]}"
		if [ "$HideDomainField" != "On" -a "$HideDomainField" != "Off" ]; then
			echo "Specify On or Off value for HideDomainField"
			exit 1
		fi
	;;
	-VpnVserver)
		((i++))
		VpnVserver="${arg[$i]}"
		CSG_SERVER=`echo "$VpnVserver" | cut -s -f 3 -d '/' -`
		CSG_PROT=`echo "$VpnVserver" | cut -s -f 1 -d ':' - | tr '[:upper:]' '[:lower:]'`
	;;
	*)
		if [ "$i" == 2 ]; then
			agURL="${arg[$i]}"
			((i++))
			staURL="${arg[$i]}"
			if [ -z "$VpnVserver" ]; then
				CSG_SERVER=`echo "$agURL" | cut -s -f 3 -d '/' -`
				CSG_PROT=`echo "$agURL" | cut -s -f 1 -d ':' - | tr '[:upper:]' '[:lower:]'`
			fi
			if [ -z "$CSG_SERVER" -o -z "$CSG_PROT" ]; then
				echo "Invalid agURL"
				exit 11
			fi
			if [ "$CSG_PROT" != "http" -a "$CSG_PROT" != "https" ]; then
				echo "Invalid agURL"
				exit 11
			fi
			STA_SERVER=`echo "$staURL" | cut -s -f 3 -d '/' -`
			STA_PROT=`echo "$staURL" | cut -s -f 1 -d ':' - | tr '[:upper:]' '[:lower:]'`
			if [ -z "$STA_SERVER" -o -z "$STA_PROT" ]; then
				echo "Invalid staURL"
				exit 12
			fi
			if [ "$STA_PROT" != "http" -a "$STA_PROT" != "https" ]; then
				echo "Invalid staURL"
				exit 12
			fi
    		else
			echo "Unrecognized option: ${arg[$i]}"
			exit 1
		fi
	;;
	esac
	((i++))
done

WAR_FILENAME=`basename "$sitePath"`
WAR_DIR=`dirname "$sitePath"`

while [ "$WAR_DIR" != "/" -a "${WAR_DIR:0:1}" == "/" ]; do
	WAR_DIR="${WAR_DIR:1}"
done

if [ "$WAR_DIR" == "." -o "$WAR_DIR" == "/" ]; then
	SITE_PATH="$WAR_FILENAME"
else
	SITE_PATH="$WAR_DIR/$WAR_FILENAME"
fi

CONTEXTFILE=`echo "$SITE_PATH" | sed s/\\\\//\\\\#/g`.xml
CONFFILE="$CATALINA_HOME/webapps/$SITE_PATH/WEB-INF/WebInterface.conf"

if [ -f "$CATALINA_HOME/conf/Catalina/localhost/$CONTEXTFILE" -a -f "$CONFFILE" ]; then
	echo "Site $sitePath already exists"
	exit 9
fi

CONTEXTFILEWOXML=`echo "$SITE_PATH" | sed s/\\\\//\\\\#/g`
ls "$CATALINA_HOME/conf/Catalina/localhost/$CONTEXTFILEWOXML"\#*.xml >/dev/null 2>&1
if [ $? -eq 0 ]; then
	echo "Site $sitePath is within another site"
	exit 14
fi

cd  $CATALINA_HOME/conf/Catalina/localhost
FILES=`ls`
cd -
for file in $FILES ; do
	SITE=${file%.xml}
	SITESTAR="$SITE"\#*
	if [ a"${CONTEXTFILEWOXML%$SITESTAR}" == a ]; then
		SITE=`echo $SITE | sed s/\\\\#/\\\\//g`;
		echo "Site $SITE is within this site"
		exit 14
	fi
done

if [ -f "$CATALINA_HOME/conf/Catalina/localhost/$CONTEXTFILE" ]; then
	rm -f "$CATALINA_HOME/conf/Catalina/localhost/$CONTEXTFILE"
fi

NUM_SITES=`ls $CATALINA_HOME/conf/Catalina/localhost/ | grep -v ROOT.xml | wc -l`
if [ $NUM_SITES -ge $maxSites ]; then
	echo "Only up to $maxSites sites can be created"
	exit 13
fi

siteMatched=0
#Check if cached site can be restored
if [ -f "cached/sites/$SITE_PATH/WEB-INF/WebInterface.conf" -a -f "cached/contexts/$CONTEXTFILE" ]; then
	if [ "$siteType" == "XenApp Web - users access resources through their Web browsers" ]; then
		if [ -f "cached/sites/$SITE_PATH/index.html" ]; then
			siteMatched=1
			echo "siteType Web matched for /$SITE_PATH, using it"
		fi
	elif [ -f "cached/sites/$SITE_PATH/launch.jsp" ]; then
		siteMatched=1
		echo "siteType Services matched for /$SITE_PATH, using it"
	fi

	if [ "$siteMatched" -eq 1 ]; then
		if [ ! -d "$CATALINA_HOME/webapps/$WAR_DIR" ]; then
			mkdir -p "$CATALINA_HOME/webapps/$WAR_DIR"
		fi
		
		mv -f "cached/sites/$SITE_PATH" "$CATALINA_HOME/webapps/$WAR_DIR/"
	else
		rm -rf "cached/sites/$SITE_PATH"
	fi
	rm -f "cached/contexts/$CONTEXTFILE"
fi

#Create new web interface site
if [ "$siteMatched" -eq 0 ]; then
	WICONF="$WAR_FILENAME.txt"
	#Write new wi_config file
	rm -f "$WICONF" 2>&1 > /dev/null
	echo "site.type=$siteType" > "$WICONF"
	echo "xml.service.host=localhost" >> "$WICONF"
	echo "xml.service.protocol=HTTP" >> "$WICONF"
	echo "xml.service.port=80" >> "$WICONF"
	echo "user.interface.mode=Minimal - display only the logon fields" >> "$WICONF"
	echo "app.access.methods=$publishedResourceType" >> "$WICONF"
	echo "install.ica.clients=NO" >> "$WICONF"
	echo "war.file.path=$WAR_FILENAME.war" >> "$WICONF"
	
	export JRE_HOME="$JAVA_HOME"
	if [ `uname -m` == "i386" ]; then
		ulimit -d 300000
	fi
	rm -rf "$WAR_FILENAME" "$WAR_FILENAME.war"
	"$JAVA_HOME/bin/java" -jar bin/WebInterface.jar -a "$WICONF"
	rm -f context-tomcat*.xml "$WICONF"
	if [ ! -f "$WAR_FILENAME.war" ] ; then
		echo "Unable to generate war file $WAR_FILENAME.war"
		exit 8
	fi

	mkdir "$WAR_FILENAME"
	cd "$WAR_FILENAME"
	"$JAVA_HOME/bin/jar" -xf "../$WAR_FILENAME.war"
	cd ..
	rm -f "$WAR_FILENAME.war"
	if [ ! -f "$WAR_FILENAME/WEB-INF/WebInterface.conf" ]; then
		echo "Error in expanding war file $WAR_FILENAME.war"
		exit 6
	fi
	if [ ! -d "$CATALINA_HOME/webapps/$WAR_DIR" ]; then
		mkdir -p "$CATALINA_HOME/webapps/$WAR_DIR"
	fi
	rm -rf "$CATALINA_HOME/webapps/$WAR_DIR/$WAR_FILENAME"
	mv -f "$WAR_FILENAME" "$CATALINA_HOME/webapps/$WAR_DIR"
	if [ -d "$WAR_FILENAME" ]; then
		rm -rf "$WAR_FILENAME"
	fi
	ver=`what /netscaler/nsppe | grep NS | cut -d' ' -f 2 | cut -d':' -f1 | cut -c3-6`
	echo "$ver" > "$CATALINA_HOME/webapps/$WAR_DIR/$WAR_FILENAME/version"
fi



# Create symbolic link to /var/wi/Clients folder inside the WI site
if [ -d /var/wi/Clients/ -a ! -e "$CATALINA_HOME/webapps/$WAR_DIR/$WAR_FILENAME/Clients" -a ! -d "$CATALINA_HOME/webapps/$WAR_DIR/$WAR_FILENAME/Clients" ] ; then
	ln -s /var/wi/Clients/ "$CATALINA_HOME/webapps/$WAR_DIR/$WAR_FILENAME/Clients"
fi
if [ -h "$CATALINA_HOME/webapps/$WAR_DIR/$WAR_FILENAME/Clients" ] ; then
	echo "<Context docBase=\"../webapps/$SITE_PATH\" debug=\"0\" override=\"true\" allowLinking=\"true\" />" > "$CATALINA_HOME/conf/Catalina/localhost/$CONTEXTFILE"
else
	echo "<Context docBase=\"../webapps/$SITE_PATH\" debug=\"0\" override=\"true\" />" > "$CATALINA_HOME/conf/Catalina/localhost/$CONTEXTFILE"
fi

#Default site is created without any farm: gives Invalid config error :-(
grep -v ^Farm "$CONFFILE" > "$CONFFILE.wofarm"
mv "$CONFFILE.wofarm" "$CONFFILE"

#Configure WI as per the options passed
if [ $# -ge 3 ] ; then
	/netscaler/wi/set_wi_site.sh "$@"
	ret=$?
	if [ $ret -ne 0 ]; then
		#Should never happen as we have created site & validated all args above
		/netscaler/wi/rm_wi_site.sh "$sitePath"
		exit $ret
	fi
fi
echo "Check Tomcat logfile $CATALINA_HOME/logs/catalina.out"
echo "You can use http://127.0.0.1:8080/$SITE_PATH for WI site only after binding a Farm to it."
echo `date` ": Done adding $SITE_PATH "
