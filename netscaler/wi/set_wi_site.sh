#!/usr/bin/bash

# Copyright 2009-2016 Citrix Systems, Inc. All Rights Reserved

exec 2>&1 >> /var/log/wicmd.log
echo `date` ": $0 $*"

#siteType can not be changed: allowed here for sanity checks
if [ $# -lt 3 -o $[ $# % 2 ] -eq 0 ]; then
	echo "Usage: $0 <sitePath> [<agURL> <staURL>] [-wiAuthMethods (Explicit | Anonymous | Anonymous,Explicit)] [-siteType (XenAppWeb | XenAppServices)] [-publishedResourceType (Online | Offline | DualMode)] [-authenticationPoint (WebInterface | AccessGateway)] [-kioskMode (On | Off)] [-useTwoTickets (On | Off) -secondSTAURL <staURL> ] [-sessionReliability (On | Off)] [-userInterfaceBranding (Applications | Desktops)] [-loginTitle <loginTitle>] [-defaultCustomTextLocale ( de | en | es | fr | ja | ko | ru )] [-defaultAccessMethod ( Direct | Translated | Alternate )] [-webSessionTimeout <webSessionTimeout>] [-ShowSearch (On | Off)] [-ShowRefresh (On | Off)] [-UserInterfaceMode (Simple | Advanced )] [-UserInterfaceLayout (Auto | Normal | Compact)] [-appWelcomeMessage <appWelcomeMessage>] [-welcomeMessage <WelcomeMessage>] [-footerText <footerText>] [-loginSysMessage <loginSysMessage>] [-preLoginButton <preLoginButton>] [-preLoginMessage <preLoginMessage>] [-preLoginTitle <preLoginTitle>] [-domainSelection <domain1,domain2,...>] [-RestrictDomains <RestrictDomains>] [-LoginDomains <LoginDomains>] [-HideDomainField <HideDomainField>] [-VpnVserver <VpnVserver>]"
	echo "E.g.: $0 /Citrix/XenApp http://sslvpn.mycompany.com http://stasever"
	exit 1
fi

if [ ! -d /var/wi ]; then
	echo "Web Interface not installed"
	exit 7
fi
cd /var/wi

javaprocs=`ps ax | grep java | grep -v grep | grep "$PWD"`
if [ `echo "$javaprocs" | wc -l` -lt 1 ]; then
	echo "Tomcat not running: install web interface first"
	exit 7
fi

JAVA_HOME=`grep JRE_HOME= bin/start_tomcat.sh | cut -f 2 -d =`
if [ ! -f "$JAVA_HOME/bin/java" ]; then
	echo "Java not found @ $JAVA_HOME : install web interface first"
	exit 7
fi

sitePath="$1"
CONFFILE="tomcat/webapps/$sitePath/WEB-INF/WebInterface.conf"
if [ ! -f "$CONFFILE" ]; then
	echo "Site $sitePath does not exist"
	exit 10
fi

#Parse & validate the options
accessMethod="Remote"
agURL=""
authenticationPoint=""
kioskMode="Off"
secondSTAURL=""
sessionReliability="Off"
siteType="XenAppWeb"
staURL=""
useTwoTickets="Off"
defaultCustomTextLocale="en"
wiAuthMethods="Explicit"
loginTitle="Welcome to Web Interface on NetScaler"
UserInterfaceBranding="Applications" 
defaultAccessMethod="Normal"
webSessionTimeout="20"
agAuthMethod="Explicit"
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
	arg[$i]="$argval"
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
		case  "${arg[$i]}" in
		Online)
			accessMethod="Remote"
			;;
		Offline)
			accessMethod="Streaming"
			;;
		DualMode)
			accessMethod="Remote,Streaming"
			;;
		*)
			echo "Specify Online or Offline or DualMode value for publishedResourceType"
			exit 1
			;;
		esac
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
		if [ "$siteType" != "XenAppWeb" -a "$siteType" != "XenAppServices" ]; then
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
	;;		
	-wiAuthMethods)
		((i++))
    		wiAuthMethods="${arg[$i]}"
		if [ "$wiAuthMethods" != "Explicit" -a "$wiAuthMethods" != "Anonymous" -a "$wiAuthMethods" != "Anonymous,Explicit" ]; then
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
		VpnVserver=`echo "$VpnVserver" | cut -s -f 1,2,3 -d '/' -`
	;;
	*)
		if [ "$i" == 2 ]; then
			agURL="${arg[$i]}"
			((i++))
			staURL="${arg[$i]}"
			CSG_SERVER=`echo "$agURL" | cut -s -f 3 -d '/' - | cut -f 1 -d ':' -`
			CSG_PORT=`echo "$agURL" | cut -s -f 3 -d '/' - | cut -s -f 2 -d ':' -`
			if [ -z "$CSG_PORT" ]; then
				CSG_PORT=443
			fi
			CSG_PROT=`echo "$agURL" | cut -s -f 1 -d ':' - | tr '[:upper:]' '[:lower:]'`
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


clientAddressMap=`grep ^ClientAddressMap "$CONFFILE"`

if [ $? -ne 0 ]; then
	clientAddressMap="ClientAddressMap="	
else
	clientAddressMap=`echo $clientAddressMap | cut -d "*" -f1`
fi

clientAddressMap="$clientAddressMap""*"",""$defaultAccessMethod"

myfile="/var/wi/tomcat/webapps/$sitePath/version"
if [ -f "$myfile" ];then
	flag=0
	#Below code is for future purpose when we will introduce parameter in 10.5 which are not present in 10.1
	#prevver=$(cat $myfile)
	#curver=`what /netscaler/nsppe | grep NS | cut -d' ' -f 2 | cut -d':' -f1 | cut -c3-6`
	#flag=`echo | awk   '{if ('$curver'>'$prevver') print 1; else print 0}'`
else
	flag=1
	ver=`what /netscaler/nsppe | grep NS | cut -d' ' -f 2 | cut -d':' -f1 | cut -c3-6`
	echo "$ver" > "/var/wi/tomcat/webapps/$sitePath/version"
fi	
if [ $flag -eq 1 ]
then
	ShowSearch=`grep -v ..ShowSearch "$CONFFILE" | grep ShowSearch | grep -v "#" |cut -d'=' -f2 | tr -d '\b\r'`
	ShowRefresh=`grep ShowRefresh "$CONFFILE" | grep -v "#"|cut -d'=' -f2 | tr -d '\b\r'`
	UserInterfaceLayout=`grep UserInterfaceLayout  "$CONFFILE" | grep -v "#"|cut -d'=' -f2 | tr -d '\b\r'`
	UserInterfaceMode=`grep UserInterfaceMode  "$CONFFILE" | grep -v "#"|cut -d'=' -f2 | tr -d '\b\r'`
	RestrictDomains=`grep RestrictDomains  "$CONFFILE" | grep -v "#"|cut -d'=' -f2 | tr -d '\b\r'`
	HideDomainField=`grep HideDomainField  "$CONFFILE" | grep -v "#"|cut -d'=' -f2 | tr -d '\b\r'`

	#Text parameters starts
	preLoginButton=`grep PreLoginMessageButton  "$CONFFILE" | grep -v "^#"|cut -d'=' -f2- | tr -d '\b\r'`
	if [ ${#preLoginButton} -gt 255 ]; then 
		preLoginButton=`echo $preLoginButton | cut -c1-255`
	fi
	preLoginMessage=`grep PreLoginMessageText  "$CONFFILE" | grep -v "^#"|cut -d'=' -f2- | tr -d '\b\r'`
	if [ ${#preLoginMessage} -gt 2048 ]; then 
		preLoginMessage=`echo $preLoginMessage | cut -c1-2048`
	fi
	preLoginTitle=`grep PreLoginMessageTitle  "$CONFFILE" | grep -v "^#"|cut -d'=' -f2- | tr -d '\b\r'`
	if [ ${#preLoginTitle} -gt 255 ]; then 
		preLoginTitle=`echo $preLoginTitle | cut -c1-255`
	fi
	welcomeMessage=`grep -v ..WelcomeMessage_ "$CONFFILE" | grep WelcomeMessage_ | grep -v "^#" |cut -d'=' -f2- | tr -d '\b\r'`
	if [ ${#welcomeMessage} -gt 255 ]; then 
		welcomeMessage=`echo $welcomeMessage | cut -c1-255`
	fi
	appWelcomeMessage=`grep AppWelcomeMessage_  "$CONFFILE" | grep -v "^#"|cut -d'=' -f2- | tr -d '\b\r'`
	if [ ${#appWelcomeMessage} -gt 255 ]; then 
		appWelcomeMessage=`echo $appWelcomeMessage | cut -c1-255`
	fi
	footerText=`grep FooterText  "$CONFFILE" | grep -v "^#"|cut -d'=' -f2- | tr -d '\b\r'`
	if [ ${#footerText} -gt 255 ]; then 
		footerText=`echo $footerText | cut -c1-255`
	fi
	loginSysMessage=`grep LoginSysMessage_  "$CONFFILE" | grep -v "^#"|cut -d'=' -f2- | tr -d '\b\r'`
	if [ ${#loginSysMessage} -gt 255 ]; then 
		loginSysMessage=`echo $loginSysMessage | cut -c1-255`
	fi
	domainSelection=`grep DomainSelection  "$CONFFILE" | grep -v "^#"|cut -d'=' -f2- | tr -d '\b\r'`
	if [ ${#domainSelection} -gt 255 ]; then 
		domainSelection=`echo $domainSelection | cut -c1-255`
	fi
	LoginDomains=`grep LoginDomains  "$CONFFILE" | grep -v "^#"|cut -d'=' -f2- | tr -d '\b\r'`
	if [ ${#LoginDomains} -gt 255 ]; then 
		LoginDomains=`echo $LoginDomains | cut -c1-255`
	fi

	COMBINE=$ShowSearch'\0'$ShowRefresh'\0'$UserInterfaceLayout'\0'$UserInterfaceMode'\0'$RestrictDomains'\0'$HideDomainField'\0'$preLoginButton'\0'$preLoginMessage'\0'$preLoginTitle'\0'$welcomeMessage'\0'$appWelcomeMessage'\0'$footerText'\0'$loginSysMessage'\0'$domainSelection'\0'$LoginDomains'\0'
	# Now creating a file to write the parameters in it
	WIFILE="/var/wi/tomcat/webapps/$sitePath/wifile"
	echo -e "$COMBINE" > "$WIFILE"
else
	WIFILE="/var/wi/tomcat/webapps/$sitePath/wifile"
	if [ -f "$WIFILE" ];then
		rm "$WIFILE"
	fi	
fi
	
egrep -v "AGEWebServiceURL|ApplicationAccessMethods|AlternateAddress|AuthenticationPoint|ClientAddressMap|CSG_EnableSessionReliability|CSG_Server|CSG_ServerPort|CSG_STA_URL1|CSG_STA_URL2|CSG_UseTwoTickets|WIAuthenticationMethods|KioskMode|NetScaler|UserInterfaceBranding|UserInterfaceMode|UserInterfaceLayout|ShowSearch|ShowRefresh|LoginTitle|DefaultCustomTextLocale|ClientAddressMap|WebSessionTimeout|AGAuthenticationMethod|AppWelcomeMessage|WelcomeMessage|LoginSysMessage|FooterText|DomainSelection|PreLoginMessageText|PreLoginMessageButton|PreLoginMessageTitle|RestrictDomains|LoginDomains|HideDomainField|VpnVserver" "$CONFFILE" > "$CONFFILE.new"

echo "# NetScaler generated configuration starts from here: DO NOT MODIFY BELOW LINES" >> "$CONFFILE.new"
echo ApplicationAccessMethods="$accessMethod" >> "$CONFFILE.new"
echo KioskMode="$kioskMode" >> "$CONFFILE.new"

if [ "$agURL" != "" -a "$staURL" != "" ] ; then
#Configure WI to be used behind AG, enable Auth on AG and SmartAccess by default
	if [ -z "$authenticationPoint" ] ; then
		authenticationPoint="AccessGateway"
	fi
	if [ "$authenticationPoint" == "AccessGateway" ]; then
		echo "AuthenticationPoint=AccessGateway" >> "$CONFFILE.new"
	else
		echo "AuthenticationPoint=WebInterface" >> "$CONFFILE.new"
	fi
	if [ "$VpnVserver" != "" ] ; then
		echo "AGEWebServiceURL=$VpnVserver/CitrixAuthService/AuthService.asmx" >> "$CONFFILE.new"
	else
		if [ "$CSG_PORT" == 443 ]; then
			echo "AGEWebServiceURL=$CSG_PROT://$CSG_SERVER/CitrixAuthService/AuthService.asmx" >> "$CONFFILE.new"
		else
			echo "AGEWebServiceURL=$CSG_PROT://$CSG_SERVER:$CSG_PORT/CitrixAuthService/AuthService.asmx" >> "$CONFFILE.new"
		fi
	fi	
#	echo "ClientAddressMap=*,SG" >> "$CONFFILE.new"
	if [ ! -z "$sessionReliability" ]; then
		echo "CSG_EnableSessionReliability=$sessionReliability" >> "$CONFFILE.new"
	fi
	echo "CSG_Server=$CSG_SERVER" >> "$CONFFILE.new"
	echo "CSG_ServerPort=$CSG_PORT" >> "$CONFFILE.new"
	echo "CSG_STA_URL1=$STA_PROT://$STA_SERVER/Scripts/CtxSTA.dll" >> "$CONFFILE.new"
	if [ ! -z "$secondSTAURL" ]; then
		echo "CSG_STA_URL2=$STA_PROT2://$STA_SERVER2/Scripts/CtxSTA.dll" >> "$CONFFILE.new"
	fi
	echo "CSG_UseTwoTickets=$useTwoTickets" >> "$CONFFILE.new"
else
	echo "AuthenticationPoint=WebInterface" >> "$CONFFILE.new"
fi

if [ "$ShowSearch" != "" ] ; then
	echo "ShowSearch=$ShowSearch" >> "$CONFFILE.new"
fi

if [ "$ShowRefresh" != "" ] ; then
	echo "ShowRefresh=$ShowRefresh" >> "$CONFFILE.new"
fi

if [ "$UserInterfaceMode" != "" ] ; then
	echo "UserInterfaceMode=$UserInterfaceMode" >> "$CONFFILE.new"
fi

if [ "$UserInterfaceLayout" != "" ] ; then
	echo "UserInterfaceLayout=$UserInterfaceLayout" >> "$CONFFILE.new"
fi

echo "$CONFFILE"
V1=`grep "^Version" "$CONFFILE" | cut -d= -f2 | cut -d. -f1`
V2=`grep "^Version" "$CONFFILE" | cut -d= -f2 | cut -d. -f2`
V3=`grep "^Version" "$CONFFILE" | cut -d= -f2 | cut -d. -f3`
VN="$V1$V2$V3"

if [ "$VN" -lt "543" -a "$agAuthMethod" == "SmartCard" ]; then
    echo "SmartCard pass through not Supported in this verion of wi"
    exit 18
fi

echo "AlternateAddress=Mapped" >> "$CONFFILE.new"
echo "WIAuthenticationMethods=$wiAuthMethods" >> "$CONFFILE.new"
if [ ! -z "$loginTitle" ]; then
	echo "LoginTitle_"$defaultCustomTextLocale"=$loginTitle" >> "$CONFFILE.new"
fi

if [ ! -z "$appWelcomeMessage" ]; then
	echo "AppWelcomeMessage_"$defaultCustomTextLocale"=$appWelcomeMessage" >> "$CONFFILE.new"
fi

if [ ! -z "$welcomeMessage" ]; then
	echo "WelcomeMessage_"$defaultCustomTextLocale"=$welcomeMessage" >> "$CONFFILE.new"
fi

if [ ! -z "$footerText" ]; then
	echo "FooterText_"$defaultCustomTextLocale"=$footerText" >> "$CONFFILE.new"
fi

if [ ! -z "$loginSysMessage" ]; then
	echo "LoginSysMessage_"$defaultCustomTextLocale"=$loginSysMessage" >> "$CONFFILE.new"
fi

if [ ! -z "$preLoginButton" ]; then
	echo "PreLoginMessageButton_"$defaultCustomTextLocale"=$preLoginButton" >> "$CONFFILE.new"
fi

if [ ! -z "$preLoginMessage" ]; then
	echo "PreLoginMessageText_"$defaultCustomTextLocale"=$preLoginMessage" >> "$CONFFILE.new"
fi

if [ ! -z "$preLoginTitle" ]; then
	echo "PreLoginMessageTitle_"$defaultCustomTextLocale"=$preLoginTitle" >> "$CONFFILE.new"
fi

if [ ! -z "$domainSelection" ]; then
	echo "DomainSelection=$domainSelection" >> "$CONFFILE.new"
fi

if [ ! -z "$LoginDomains" ]; then
	echo "LoginDomains=$LoginDomains" >> "$CONFFILE.new"
fi

echo "UserInterfaceBranding=$UserInterfaceBranding" >> "$CONFFILE.new"
echo "DefaultCustomTextLocale=$defaultCustomTextLocale" >> "$CONFFILE.new"
echo "$clientAddressMap" >> "$CONFFILE.new"
echo "WebSessionTimeout=$webSessionTimeout" >> "$CONFFILE.new"
echo "AGAuthenticationMethod=$agAuthMethod" >> "$CONFFILE.new"
echo "RestrictDomains=$RestrictDomains" >> "$CONFFILE.new"
echo "HideDomainField=$HideDomainField" >> "$CONFFILE.new"
mv "$CONFFILE.new" "$CONFFILE"
