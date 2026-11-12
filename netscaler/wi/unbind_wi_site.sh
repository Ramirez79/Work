#!/usr/bin/bash

# Copyright 2009-2016 Citrix Systems, Inc. All Rights Reserved
exec 2>&1 >> /var/log/wicmd.log
unbind_access_type()
{
	pwd	
	CONFFILE="$1"
	translationInternalIp="$2"
	translationInternalPort="$3"
	translationExternalIp="$4"
	translationExternalPort="$5"
	paramname="$6"

	clientAddressMap=`grep ^ClientAddressMap "$CONFFILE.old"`
	serverAddressMap=`grep ^"$paramname" "$CONFFILE.old"`	
	
	if [ $? -ne 0 ]; then
		return 1
	else
		maps=`echo $serverAddressMap | cut -d "=" -f2`
		serverAddressMap=`echo $serverAddressMap | cut -d "=" -f1`


		key="$translationInternalIp"":""$translationInternalPort"
		key2="$translationExternalIp"":""$translationExternalPort"
		key3="$key"",""$key2"

		arr=$(echo $maps | tr "," "\n")
		found=0
		counter=0
		maps=""

		for x in $arr
		do
			if [ `expr $counter % 2` -eq 1 ]; then
				temp="$temp"",""$x"		
				if [ $key3 != $temp ]; then
					if [ -z "$maps" ]; then
						maps=$temp;
					else
						maps="$maps"",""$temp"	
					fi
				else
					found=1
				fi
			else
				temp="$x"
			fi
			counter=`expr $counter + 1`
		done
		
		if [ $found -eq 0 ]; then
			echo "Translation  Map does not exists"
                        return 1
		else
			serverAddressMap="$serverAddressMap""=""$maps"
			if [ "$paramname" = "ServerAddressMap" ]; then
				echo $clientAddressMap | grep "Translated"
			else
				echo $clientAddressMap | grep "SGTranslated"
			fi
				
			if [ $? -eq 0 ] && [ -z "$maps" ]; then
				echo "Can't Unbind Last Translation Map"
				exit 16
			fi
			grep -v ^"$paramname" "$CONFFILE.old" > "$CONFFILE.new"
			mv "$CONFFILE.new" "$CONFFILE.old"
			if [ -n "$maps" ]; then
				echo "$serverAddressMap" >> "$CONFFILE.old"	
			fi
		fi
	fi
	return 0
}

echo `date` ": $0 $*"
if [ $# -eq 2 ]; then
        sitePath="$1"
	FarmName="$2"
elif [ "$2" = "-clientIpAddress" ]; then
        sitePath="$1"
	clientIpAddress="$3"
	clientNetMask="$5"
elif [ "$2" = "-translationInternalIp" ]; then
        sitePath="$1"
        translationInternalIp=$3
	translationInternalPort=$5
	translationExternalIp=$7
	translationExternalPort=$9
else
        echo "Usage: $0 <sitePath> <FarmName>"
        exit 1
fi

if [ ! -d /var/wi ]; then
	echo "Web Interface not installed"
	exit 7
fi
cd /var/wi
CONFFILE="tomcat/webapps/$sitePath/WEB-INF/WebInterface.conf"

if [ -f "$CONFFILE" ]
then
	if [ "$2" = "-clientIpAddress" ]; then
	        clientAddressMap=`grep ^ClientAddressMap "$CONFFILE"`
                clientAddressMap=`echo $clientAddressMap | sed "s/$clientIpAddress\/$clientNetMask,[a-zA-Z]*,//g"`
		grep -v "ClientAddressMap" "$CONFFILE" > "$CONFFILE.new"
                mv "$CONFFILE.new" "$CONFFILE"
                echo "$clientAddressMap" >> "$CONFFILE"

	elif [ "$2" = "-translationInternalIp" ]; then
		cp "$CONFFILE" "$CONFFILE.old"
                unbind_access_type "$CONFFILE" "$translationInternalIp" "$translationInternalPort" "$translationExternalIp" "$translationExternalPort" "ServerAddressMap"
		r1=$?
                unbind_access_type "$CONFFILE" "$translationInternalIp" "$translationInternalPort" "$translationExternalIp" "$translationExternalPort" "InternalServerAddressMap"
		r2=$?
		
		if [ r1 -ne 0 ] && [ r2 -ne 0 ]; then
			echo "Translation Map does not Exists"
                	rm "$CONFFILE.old"
			exit 15
		fi

		cp "$CONFFILE.old" "$CONFFILE"
                rm "$CONFFILE.old"

	
	else                                                                                                                                                                                                                                                 
                FarmDetails=`grep ^Farm "$CONFFILE" | grep ,Name:"$FarmName",`
                RecoveryFarmDetails=`grep ^RecoveryFarm "$CONFFILE" | grep ,Name:"$FarmName",`
                if [ -z "$FarmDetails" -a -z "$RecoveryFarmDetails" ]; then
                        echo "Farm $FarmName does not exist"
                        exit 3
                fi
                if [ ! -z "$FarmDetails" ]; then
			count=`grep -E "^Farm[0-9]+=" "$CONFFILE" | wc -l`
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
                elif [ ! -z "$RecoveryFarmDetails" ]; then
			count=`grep -E "^RecoveryFarm[0-9]+=" "$CONFFILE" | wc -l`
                        grep -v ^RecoveryFarm "$CONFFILE" > "$CONFFILE.new"
                        i=1
                        j=1
                        while [ "$count" -ge "$i" ]; do
                                FarmDetails=`grep ^RecoveryFarm"$i" "$CONFFILE"`
                                Found=`echo "$FarmDetails" | grep ,Name:"$FarmName",`
                                if [ -z "$Found" ]; then
                                        FarmDetails=`echo "$FarmDetails" | cut -f 2- -d =`
                                        echo "RecoveryFarm$j=$FarmDetails" >> "$CONFFILE.new"
                                        ((j++))
                                fi
                                ((i++))
                        done
                        mv "$CONFFILE.new" "$CONFFILE"
                fi
	fi
else
	echo "Site $sitePath does not exist"
	exit 10
fi

