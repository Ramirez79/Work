#!/usr/bin/bash

# Copyright 2009-2016 Citrix Systems, Inc. All Rights Reserved
exec 2>&1 >> /var/log/wicmd.log
			
clear_access_type()
{
	CONFFILE="$1"
	translationInternalIp="$2"
	translationInternalPort="$3"
	paramname="$4"
	accessType="$5"
 	clientAddressMap=`grep ^ClientAddressMap "$CONFFILE.old"`
	serverAddressMap=`grep ^"$paramname" "$CONFFILE.old"`


	if [ $? -ne 0 ]; then
		return 
	else
		maps=`echo $serverAddressMap | cut -d "=" -f2`
		serverAddressMap=`echo $serverAddressMap | cut -d "=" -f1`
	fi

	key="$translationInternalIp"":""$translationInternalPort"
	arr=$(echo $maps | tr "," "\n")
	found=0
	counter=0
	maps=""

	for x in $arr
	do
		if [ `expr $counter % 2` -eq 0 ] && [ $x = $key ]; then
			found=1
		elif [ $found -eq 1 ]; then
			found=0
		else 
			if [ -z "$maps" ];then
				maps=$x
			else
				maps="$maps"",""$x"
			fi
		fi
		counter=`expr $counter + 1`
	done

	serverAddressMap="$serverAddressMap""=""$maps"
	
	if [ "$accessType" != "Both" ]; then

		rv=1	
		if [ "$paramname" = "ServerAddressMap" ] && [ "$accessType" = "Gateway" ]; then
			echo $clientAddressMap | grep "Translated"
                        rv=$?
		elif [ "$paramname" = "InternalServerAddressMap" ] && [ "$accessType" = "UserDevice" ]; then 
			echo $clientAddressMap | grep "SGTranslated"
                        rv=$?
		fi

		if [ $rv -eq 0 ] && [ -z "$maps" ]; then
			echo "Can't Unbind Last Translation Map"
			exit 16
		fi		
	fi		

	grep -v ^"$paramname" "$CONFFILE.old" > "$CONFFILE.new"
	mv "$CONFFILE.new" "$CONFFILE.old"
	if [ -n "$maps" ]; then
		echo "$serverAddressMap" >> "$CONFFILE.old"
	fi

	return
}

bind_access_type()
{
	CONFFILE="$1"
	translationInternalIp="$2"
	translationInternalPort="$3"
	translationExternalIp="$4"
	translationExternalPort="$5"
	paramname="$6"
	found=1
	serverAddressMap=`grep ^"$paramname" "$CONFFILE"`
	
	if [ $? -ne 0 ]; then
		serverAddressMap="$paramname"
		found=0
	else
		maps=`echo $serverAddressMap | cut -d "=" -f2`
		serverAddressMap=`echo $serverAddressMap | cut -d "=" -f1`
	fi			
	key="$translationInternalIp"":""$translationInternalPort"
	key2="$translationExternalIp"":""$translationExternalPort"

	if [ $found -eq 1 ];then
		maps="$maps"",""$key"",""$key2"
	else
		maps="$key"",""$key2"
	fi
	serverAddressMap="$serverAddressMap""=""$maps"

	grep -v ^"$paramname" "$CONFFILE" > "$CONFFILE.new"
	mv "$CONFFILE.new" "$CONFFILE"
	echo "$serverAddressMap" >> "$CONFFILE"
}

echo `date` ": $0 $*"
if [ $# -lt 3 ]
then
	echo "Usage: $0 <sitePath> <FarmName> <xmlServerAddresses> [-xmlPort <PortNumber>] [-transport ( HTTP | HTTPS | SSLRELAY )] [-sslRelayPort <PortNumber>] [-loadBalance (ON | OFF)] [-recoveryFarm (ON | OFF)] [-groups <domain\UserGroup, ...>]"
	exit 1
fi

if [ ! -d /var/wi ]; then
	echo "Web Interface not installed"
	exit 7
fi
cd /var/wi

i=1
for argval in "$@"; do
	arg["$i"]="$argval"
	((i++))
done

sitePath="$1"

if [ "$2" = "-accessMethod" ]; then
	accessMethod=$3
elif [ "$2" = "-translationInternalIp" ]; then
	translationInternalIp=$3
else
	FarmName="$2"
	xmlServerAddresses="$3"
	XMLPort=80
	Transport=HTTP
	SSLRelayPort=443
	LoadBalance=ON
fi


i=4
while [ "$i" -le $# ]; do
	case "${arg[$i]}" in
	-loadBalance)
		((i++))
		LoadBalance="${arg[$i]}"
		if [ "$LoadBalance" != "ON" -a "$LoadBalance" != "OFF" ]; then
			echo "Specify ON or OFF value for LoadBalance"
			exit 1
		fi
	;;
	-recoveryFarm)
		((i++))
		RecoveryFarm="${arg[$i]}"
		if [ "$RecoveryFarm" != "ON" -a "$RecoveryFarm" != "OFF" ]; then
			echo "Specify ON or OFF value for RecoveryFarm"
			exit 1
		fi
	;;
	-groups)
		((i++))
		Groups="${arg[$i]}"
	;;
	-transport)
		((i++))
		Transport="${arg[$i]}"
		if [ "$Transport" == "SSLRELAY" ]; then
			Transport="SSL"
		elif [ "$Transport" != "HTTP" -a "$Transport" != "HTTPS" ]; then
			echo "Specify HTTP or HTTPS or SSLRELAY value for Transport"
			exit 1
		fi
	;;
	-xmlPort)
		((i++))
		XMLPort="${arg[$i]}"
	;;
	-sslRelayPort)
		((i++))
		SSLRelayPort="${arg[$i]}"
	;;
	-clientIpAddress)
		((i++))
		clientIpAddress="${arg[$i]}"
	;;
	-clientNetMask)
		((i++))
		clientNetMask="${arg[$i]}"
	;;
	-translationInternalPort)
		((i++))
		translationInternalPort="${arg[$i]}"
	;;
	-translationExternalIp)
		((i++))
		translationExternalIp="${arg[$i]}"
	;;
	-translationExternalPort)
		((i++))
		translationExternalPort="${arg[$i]}"
	;;
	-accessType)
		((i++))
		accessType="${arg[$i]}"
	;;
	* )
		echo "Only valid options are : -XMLPort, -Transport & -LoadBalance"
		exit 1
	;;
	esac
	((i++))
done

CONFFILE="tomcat/webapps/$sitePath/WEB-INF/WebInterface.conf"

if [ -f "$CONFFILE" ]
then
	if [ "$2" = "-accessMethod" ]; then
		echo "$accessMethod" | grep "SG"
		if [ $? -eq 0 ]; then
			grep "AGEWebServiceURL" "$CONFFILE"
			if [ $? -ne 0 ]; then
				echo "AGURL is not set"
				exit 17
			fi
		fi
 		clientAddressMap=`grep ^ClientAddressMap "$CONFFILE"`			
		key="$clientIpAddress""/""$clientNetMask"
		clientAddressMap=`echo $clientAddressMap | sed "s/$clientIpAddress\/$clientNetMask,[a-zA-Z]*,//g"`
		front=`echo $clientAddressMap | cut -d "*" -f1`
                back=`echo $clientAddressMap | cut -d "*" -f2`
		key="$key"",""$accessMethod"
		clientAddressMap="$front""$key"",""*""$back"
		grep -v ^"ClientAddressMap" "$CONFFILE" > "$CONFFILE.new"
		mv "$CONFFILE.new" "$CONFFILE"
		echo "$clientAddressMap" >> "$CONFFILE"

	elif [ "$2" = "-translationInternalIp" ]; then
		cp "$CONFFILE" "$CONFFILE.old" 
		clear_access_type "$CONFFILE" "$translationInternalIp" "$translationInternalPort" "ServerAddressMap" "$accessType"
		clear_access_type "$CONFFILE" "$translationInternalIp" "$translationInternalPort" "InternalServerAddressMap" "$accessType"
		cp "$CONFFILE.old" "$CONFFILE" 
		rm "$CONFFILE.old"

		if [ "$accessType" =  "UserDevice" ]; then
			bind_access_type "$CONFFILE" "$translationInternalIp" "$translationInternalPort" "$translationExternalIp" "$translationExternalPort" "ServerAddressMap"
		elif [ "$accessType" = "Gateway" ]; then
			bind_access_type "$CONFFILE" "$translationInternalIp" "$translationInternalPort" "$translationExternalIp" "$translationExternalPort" "InternalServerAddressMap"
		else
			bind_access_type "$CONFFILE" "$translationInternalIp" "$translationInternalPort" "$translationExternalIp" "$translationExternalPort" "ServerAddressMap"
			bind_access_type "$CONFFILE" "$translationInternalIp" "$translationInternalPort" "$translationExternalIp" "$translationExternalPort" "InternalServerAddressMap"
		fi		
	elif [ "$RecoveryFarm" = "ON" ]; then
		FarmDetails=`grep ^Farm "$CONFFILE" | grep ,Name:"$FarmName",`
		if [ ! -z "$FarmDetails" ]; then
			count=`grep -E "^Farm[0-9]+=" "$CONFFILE" | wc -l`
			echo "count=$count"
			if [ "$count" -eq 1 ]; then
				echo "At least one Farm should be configured: can not remove last farm"
				exit 4
			fi
			grep -v ^Farm "$CONFFILE" > "$CONFFILE.new"
			i=1
			j=1
			while [ "$count" -ge "$i" ]; do
				AttrDetails=(`grep ^Farm"$i" "$CONFFILE"`)
				FoundFarm=`echo "${AttrDetails[0]}" | grep ,Name:"$FarmName",`
				if [ -z "$FoundFarm" ]; then
					FarmDetails=`echo "${AttrDetails[0]}" | cut -f 2- -d =`
					echo "Farm$j=$FarmDetails" >> "$CONFFILE.new"
					GroupDetails=`echo "${AttrDetails[1]}" | cut -f 2- -d =`
					if [ ! -z "$GroupDetails" ]; then
						echo "Farm${j}Groups=$GroupDetails" >> "$CONFFILE.new"
					#else
						#echo "Farm${j}Groups=None" >> "$CONFFILE.new"
					fi
					((j++))
				fi
				((i++))
			done
			mv "$CONFFILE.new" "$CONFFILE"
		fi
		FarmDetails=`grep ^RecoveryFarm "$CONFFILE" | grep ,Name:"$FarmName",`
		if [ ! -z "$FarmDetails" ]; then
			Farmname=`echo "$FarmDetails" | cut -f 1 -d '='`
			grep -v "$FarmDetails" "$CONFFILE" > "$CONFFILE.wofarm"
			mv "$CONFFILE.wofarm" "$CONFFILE"
		else
			FarmNum=1
			while grep ^RecoveryFarm"$FarmNum" "$CONFFILE" > /dev/null
			do
				((FarmNum++))
			done
			Farmname=RecoveryFarm"$FarmNum"
		fi

		FarmDetails="$Farmname=$xmlServerAddresses,Name:$FarmName,XMLPort:$XMLPort,Transport:$Transport,SSLRelayPort:$SSLRelayPort,BypassDuration:60,LoadBalance:$LoadBalance,TicketTimeToLive:200,RADETicketTimeToLive:200"
		echo "$FarmDetails" >> "$CONFFILE"
		#echo "${Farmname}Groups=None" >> "$CONFFILE"
		curl -I -L "http://127.0.0.1:8080/$sitePath" > /dev/null &
	else
		FarmDetails=`grep ^RecoveryFarm "$CONFFILE" | grep ,Name:"$FarmName",`
		if [ ! -z "$FarmDetails" ]; then
			count=`grep -E "^RecoveryFarm[0-9]+=" "$CONFFILE" | wc -l`
			grep -v ^RecoveryFarm "$CONFFILE" > "$CONFFILE.new"
			i=1
			j=1
			while [ "$count" -ge "$i" ]; do
				AttrDetails=`grep ^RecoveryFarm"$i" "$CONFFILE"`
				FoundFarm=`echo "$AttrDetails" | grep ,Name:"$FarmName",`
				if [ -z "$FoundFarm" ]; then
					FarmDetails=`echo "$AttrDetails" | cut -f 2- -d =`
					echo "RecoveryFarm$j=$FarmDetails" >> "$CONFFILE.new"
					((j++))
				fi
				((i++))
			done
			mv "$CONFFILE.new" "$CONFFILE"
		fi
		FarmDetails=`grep ^Farm "$CONFFILE" | grep ,Name:"$FarmName",`
		if [ ! -z "$FarmDetails" ]; then
			echo "FarmDetails=$FarmDetails"
			Farmname=`echo "$FarmDetails" | cut -f 1 -d '='`
			grep -v "$FarmDetails" "$CONFFILE" > "$CONFFILE.wofarm"
			mv "$CONFFILE.wofarm" "$CONFFILE"
			# GroupDetails=`grep "^${Farmname}Groups" "$CONFFILE"`
			# echo "GroupDetails=$GroupDetails"
			grep -v "${Farmname}Groups" "$CONFFILE" > "$CONFFILE.wofarm"
			mv "$CONFFILE.wofarm" "$CONFFILE"
		else
			FarmNum=1
			while grep ^Farm"$FarmNum" "$CONFFILE" > /dev/null
			do
				((FarmNum++))
			done
			Farmname=Farm"$FarmNum"
			echo "FarmName=$Farmname"
		fi
		
		FarmDetails="$Farmname=$xmlServerAddresses,Name:$FarmName,XMLPort:$XMLPort,Transport:$Transport,SSLRelayPort:$SSLRelayPort,BypassDuration:60,LoadBalance:$LoadBalance,TicketTimeToLive:200,RADETicketTimeToLive:200"
		echo "$FarmDetails" >> "$CONFFILE"
		if [ ! -z "$Groups" ]; then
			echo "${Farmname}Groups=$Groups" >> "$CONFFILE"
		#else
			#echo "${Farmname}Groups=None" >> "$CONFFILE"
		fi
		curl -I -L "http://127.0.0.1:8080/$sitePath" > /dev/null &
	fi
else
        echo "Site $sitePath does not exist"
        exit 10
fi
