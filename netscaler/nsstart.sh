#!/bin/sh
# NetScaler Startup script - part 1
# FILE:         $Id: //depot/main/rs_111/usr.src/netscaler/scripts/nsstart.sh#2 $
# LAST CHECKIN: $Author: jacobsa $
#               $DateTime: 2016/06/27 11:21:54 $
#
# Copyright 1998-2016 Citrix Systems, Inc. All rights reserved.
# This software and documentation contain valuable trade
# secrets and proprietary property belonging to Citrix Systems, Inc.
# None of this software and documentation may be copied,
# duplicated or disclosed without the express
# written permission of Citrix Systems, Inc.
#
# Options:	NONE
#

# This script is installed in /netscaler.
# It runs before the Netscaler code starts up.
# It calls nsbefore.sh.

#create 2048bit cert/key by default on all platforms. If there is proper lic then load the 2048bit default cert.
#512 bits is not going to work with modern browsers
NS_SSL_KEYSIZE=2048
NS_DEFAULT_PARTITIONID=0
SYSID=`sysctl -n netscaler.sysid`
NSNCORE=`sysctl -n netscaler.nCore`
REALMEM=`sysctl -n hw.realmem`
NUMCPU=`sysctl -n hw.ncpu`
NSMEMINIT=`sysctl -n netscaler.dev_mem_init`
SDXVPX=`/sbin/sysctl -n netscaler.sdxvpx`
SETDEFAULTCPU=`cpuset -l 0 -s 1`
VPXONCLOUD=`/sbin/sysctl -n netscaler.vpx_on_cloud`
GW_DHCP=`/sbin/sysctl -n netscaler.is_simple_gateway` 

if [ $NSMEMINIT -eq 0 ] ; then
	echo "Nestscaler memory not initialized !"
	echo "Remove tunable netscaler.dev_mem_init in loader.conf and reboot !"
	exit
fi
if [ $(expr $SYSID / 1000) = "450" ] ; then
    if [ $NSNCORE -eq 1 ] && [ $REALMEM -lt 2000000000  -o $NUMCPU -lt 2  ] ; then
        echo "NetScaler on nCore VPX requires a minimum of 2 Gigabytes and 2 cpus to start"
        exit
    fi
    if [ $REALMEM -lt 1000000000  ] ; then
        echo "NetScaler on VPX requires a minimum of 1 Gigabyte to start"
        exit
    fi
    if [ $SYSID -eq 450010 ] || [ $SYSID -eq 450011 ] ||[ $SYSID -eq 450020 ] || [ $SYSID -eq 450021 ] || [ $SDXVPX -eq 1 ] || [ $SYSID -eq 450000 ] || [ $SYSID -eq 450001 ] || [ $VPXONCLOUD -eq 1 ] || [ $VPXONCLOUD -eq 2 ] || [ $VPXONCLOUD -eq 3 ] || [ $SYSID -eq 450110 ] && [ ! -e /flash/nsconfig/ns.conf ] ; then
        if [ -e /netscaler/NSVAconf ] ; then
            clear
            if [ $SYSID -eq 450010 -o $SYSID -eq 450011 ] && [ -e /var/vmtools/bin/vmtoolsd ] && [ $GW_DHCP -eq 0 ] ; then 
				 mkdir /tmp/esx
				/var/vmtools/bin/vmtoolsd --cmd="info-get guestinfo.ovfEnv" > /tmp/esx/ovf-env.xml
				/netscaler/NSVAconf  `/var/vmtools/bin/vmtoolsd --cmd 'machine.id.get'`
            else
               #Non Gateway DHCP cases
		if [ $VPXONCLOUD -eq 1 ] || [ $VPXONCLOUD -eq 3 ] ; then
			mkdir /var/db # to store lease files
			mkdir /var/empty
			if [ $VPXONCLOUD -eq 1 ] ; then
				mkdir /flash/nsconfig/.AWS
				mkdir -p /var/nstmp/awsconfigd
			else
				mkdir /flash/nsconfig/.AZURE
			fi
			if [ $SYSID -eq 450020 ] ; then
				/sbin/dhclient -l /var/db/dhclient.leases.1 0/1
			else 
				/sbin/dhclient -l /var/db/dhclient.leases.1 1/1
			fi
			echo "LEASE FILE "
			cat /var/db/dhclient.leases.1
			/netscaler/NSVAconf

			if [ $? -ne 0 ]; then
				echo "NSVAconfig failed to read DHCP lease files \n"
				return
			fi
			if [ $VPXONCLOUD -eq 1 ] ; then
				touch /flash/nsconfig/.AWS/new_instance
			fi
                #Handle Gateway DHCP cases. Should always occur exclusive of VPXONCLOUD 
		elif [ $GW_DHCP -eq 1 ] ; then
			mkdir /var/db
			mkdir /var/empty
			#Hyper-V and ESX use 0/1
			if [ $SYSID -eq 450020 ] || [ $SYSID -eq 450011 ] || [ $SYSID -eq 450010 ] ; then
				/sbin/dhclient -l /var/db/dhclient.leases.1 0/1
			#Xen uses 1/1
			else 
				/sbin/dhclient -l /var/db/dhclient.leases.1 1/1
			fi
			echo "LEASE FILE "
			cat /var/db/dhclient.leases.1
			/netscaler/NSVAconf

			if [ $? -ne 0 ]; then
				echo "NSVAconfig failed to read DHCP lease files \n"
				return
			fi
		else
			/netscaler/NSVAconf
		fi
	     fi
	fi
    fi
# Incase of ESX VPX instances - Update the IP address with host using vmtoolsd
    if [ $SYSID -eq 450010 -o $SYSID -eq 450011 ]
    then
	IP=`/usr/bin/grep "set ns config -IPAddress" /nsconfig/ns.conf | cut -d' ' -f5`
	/var/vmtools/bin/vmtoolsd --cmd="info-set guestinfo.ip $IP"
    fi
if [ $VPXONCLOUD -eq 3 ] ; then
	cp /flash/nsconfig/.AZURE/resolv.conf /etc/resolv.conf
fi

# for AWS start the HA daemon

    if [ $VPXONCLOUD -eq 1 ] ; then
	cp /flash/nsconfig/.AWS/resolv.conf /etc/resolv.conf

	if [ $SYSID -eq 450040 ] ; then
		if [ -e /flash/nsconfig/.AWS/new_instance ]; then
			if [ -e /flash/nsconfig/sshd_config ]; then
#if AuthorizedKeyFile exists then copy from nsroot keys from original location to new location.
#pick only filename
				if [ ! -e /flash/nsconfig/.AWS/sshd_auth_file ]; then
					grep -i AuthorizedKeysFile /nsconfig/sshd_config > /tmp/key_loc
					if [ -s /tmp/key_loc ]; then 
						cp /nsconfig/ssh/authorized_keys  /var/pubkey/nsroot/.ssh/`awk -F"\/" '{print $NF}' /tmp/key_loc`
						touch /flash/nsconfig/.AWS/sshd_auth_file
					fi
				fi
			fi 
		fi
		chmod 755 /flash/nsconfig
		mkdir -p /var/nstmp/awsconfigd
		echo "Configuring the pre-PE networking..."
		/netscaler/nsaws_dhconf.pl < /var/db/dhclient.leases.1
		/netscaler/NSVAconf
	fi
    fi
fi

NETSCALER=${NETSCALER:-`cat /var/run/.NETSCALER`}
[ -r ${NETSCALER}/netscaler.conf ] && . ${NETSCALER}/netscaler.conf

LOG=${NSLOG}
LOG_NEW_SIZE=$(($NSLOG_MAX_SIZE/3/2))	# 512b blocks

NS_GEN_PROG=${NETSCALER}/nssslgen
NS_SSL_DIR=${NSCONFIG}/ssl
NS_ROOT_KEY=${NS_SSL_DIR}/ns-root.key
NS_ROOT_REQ=${NS_SSL_DIR}/ns-root.req
NS_ROOT_CERT=${NS_SSL_DIR}/ns-root.cert
NS_ROOT_SRL=${NS_SSL_DIR}/ns-root.srl
NS_SERVER_KEY=${NS_SSL_DIR}/ns-server.key
NS_SERVER_REQ=${NS_SSL_DIR}/ns-server.req
NS_SERVER_CERT=${NS_SSL_DIR}/ns-server.cert

# Below certs added by Gateway team to provide a 
# unique and persistant certificate by which an NS can be indentified.
NS_SFTRUST_ROOT_KEY=${NS_SSL_DIR}/ns-sftrust-root.key
NS_SFTRUST_ROOT_REQ=${NS_SSL_DIR}/ns-sftrust-root.req
NS_SFTRUST_ROOT_CERT=${NS_SSL_DIR}/ns-sftrust-root.cert
NS_SFTRUST_KEY=${NS_SSL_DIR}/ns-sftrust.key
NS_SFTRUST_REQ=${NS_SSL_DIR}/ns-sftrust.req
NS_SFTRUST_CERT=${NS_SSL_DIR}/ns-sftrust.cert
NS_SFTRUST_ROOT_SRL=${NS_SSL_DIR}/ns-sftrust-root.srl

# Load default values
[ -r /etc/rc.conf.defaults ] && . /etc/rc.conf.defaults
[ -f $NSCONFIG/rc.conf ] && . $NSCONFIG/rc.conf

set_hostname()
{
        /usr/bin/grep "hostname" /nsconfig/rc.conf > /dev/null 2>&1
        if [ $? -ne 0 ]
        then
                num=`/usr/bin/grep -c "set ns hostName" /nsconfig/ns.conf`
                if [ ${num} -eq 1 ]
                then
                        hostname=`grep "set ns hostName" /nsconfig/ns.conf | awk '{print $4}'`
                fi
        fi
        /bin/hostname ${hostname}
}


clean_nslogdir()
{
	if [ -d $NSLOG_DIR -a -x ${DU} ]
	then
		# to be sure that file does exist
		[ ! -e $LOG ] && echo nsstart: `date` ': log file has been created' >$LOG
		# check if the log file size bigger than LOG_MAX Kb
		set `$DU -k $LOG`
		if [ $1 -gt $NSLOG_MAX_SIZE ]
		then
			echo 'File ' $LOG ' is too big - attempting to trim.'
			echo nsstart: `date` ': log file has been trimmed' >$LOG
			$TAIL -b ${LOG_NEW_SIZE} $LOG.new >>$LOG && $MV $LOG.new $LOG
		fi
	fi
}

create_sftrust_certificate()
{
	[ -f $NS_SFTRUST_CERT -a -f $NS_SFTRUST_KEY ] && return
	NEW_RANSTRING=`head -c 1024 /dev/urandom |tr -dc 'A-Z' |head -c${1:-6}`
	local COUNTRY="US"
	local STATE="California"
	local LOCATION="San Jose"
	local ORGANIZATION="Citrix Gateway"
	local ORGANIZATIONUNIT="NS SFTrust"
        local COMMONNAME="SFTrust default ""${NEW_RANSTRING}"

	clean_sftrust_certificate

	###
	echo nsstart: `date` ': Creating the RSA root key'
	###
	$NS_GEN_PROG rsakey $NS_DEFAULT_PARTITIONID $NS_SFTRUST_ROOT_KEY $NS_SSL_KEYSIZE > /dev/null 2>&1
	if [ $? -ne 0 ]
	then
		echo nsstart: `date` ': Error creating the RSA key file: $NS_SFTRUST_ROOT_KEY' >> $LOG
		clean_default_certificate
		return
	fi

	###
	echo nsstart: `date` ': Creating the CSR for the root certificate'
	###
        #fixed for ENH0451441 and BUG0405363
	$NS_GEN_PROG -GUI certreq $NS_DEFAULT_PARTITIONID $NS_SFTRUST_ROOT_REQ -keyFile $NS_SFTRUST_ROOT_KEY -C $COUNTRY -S $STATE -L $LOCATION -O $ORGANIZATION -OU $ORGANIZATIONUNIT -CN $COMMONNAME
	if [ $? -ne 0 ]
	then
		echo nsstart: `date` ': Error creating the Certificate Signing Request file (CSR): $NS_SFTRUST_ROOT_REQ' >> $LOG
		clean_default_certificate
		return
	fi

	###
	echo nsstart: `date` ': Create the Self-Signed Certificate root certificate'
	###
	$NS_GEN_PROG cert $NS_DEFAULT_PARTITIONID $NS_SFTRUST_ROOT_CERT $NS_SFTRUST_ROOT_REQ ROOT_CERT -keyFile $NS_SFTRUST_ROOT_KEY -days 1000000
	if [ $? -ne 0 ]
	then
		echo nsstart: `date` ': Error creating the Self-Signed Certificate: $NS_SFTRUST_ROOT_CERT' >> $LOG
		clean_default_certificate
		return
	fi


	echo nsstart: `date` ': Creating sftrust netscaler certificate for NetScaler StoreFront communication'
		
	###
	$NS_GEN_PROG rsakey $NS_DEFAULT_PARTITIONID $NS_SFTRUST_KEY $NS_SSL_KEYSIZE > /dev/null 2>&1
	if [ $? -ne 0 ]
	then
		echo nsstart: `date` ': Error creating the RSA key file: $NS_SFTRUST_KEY' >> $LOG
		clean_sftrust_certificate
		return
	fi
	
	###
	echo nsstart: `date` ': Create the CSR for server cert'
	###
#fixed for ENH0451441 and BUG0405363
	$NS_GEN_PROG -GUI certreq $NS_DEFAULT_PARTITIONID $NS_SFTRUST_REQ -keyFile $NS_SFTRUST_KEY -C $COUNTRY -S $STATE -L $LOCATION -O $ORGANIZATION -OU $ORGANIZATIONUNIT -CN $COMMONNAME
	if [ $? -ne 0 ]
	then
		echo nsstart: `date` ': Error creating Certificate Signing Request file (CSR): $NS_SFTRUST_REQ' >> $LOG
		clean_sftrust_certificate
		return
	fi

	###
	echo nsstart: `date` ': Create the Server Certificate'
	###
	$NS_GEN_PROG cert $NS_DEFAULT_PARTITIONID $NS_SFTRUST_CERT $NS_SFTRUST_REQ SRVR_CERT -CAcert $NS_SFTRUST_ROOT_CERT -CAkey $NS_SFTRUST_ROOT_KEY -CAserial $NS_SFTRUST_ROOT_SRL -days 1000000
	if [ $? -ne 0 ]
	then
		echo nsstart: `date` ': Error creating the server Certificate: $NS_SFTRUST_CERT' >> $LOG
		clean_sftrust_certificate
		return
	fi
	openssl x509 -outform der -in /nsconfig/ssl/ns-sftrust.cert -out /nsconfig/ssl/ns-sftrust.der
	openssl x509 -in /nsconfig/ssl/ns-sftrust.cert -fingerprint -noout > /nsconfig/ssl/ns-sftrust.sig.tmp
	cat /nsconfig/ssl/ns-sftrust.sig.tmp | awk 'BEGIN {FS="="}; {print $2}' | sed 's/://g' > /nsconfig/ssl/ns-sftrust.sig
	rm /nsconfig/ssl/ns-sftrust.sig.tmp
}

create_default_certificate()
{
# fixed for ENH0451441 and BUG0405363
	[ -f $NS_SERVER_CERT -a -f $NS_SERVER_KEY ] && return
	NEW_RANSTRING=`head -c 1024 /dev/urandom |tr -dc 'A-Z' |head -c${1:-6}`
	local COUNTRY="US"
	local STATE="California"
	local LOCATION="San Jose"
	local ORGANIZATION="Citrix ANG"
	local ORGANIZATIONUNIT="NS Internal"
        local COMMONNAME="default ""${NEW_RANSTRING}"

	clean_default_certificate
	
	echo nsstart: `date` ': Creating default netscaler certificate for NetScaler internal communication'
		
	###
	echo nsstart: `date` ': Creating the RSA root key'
	###
	$NS_GEN_PROG rsakey $NS_DEFAULT_PARTITIONID $NS_ROOT_KEY $NS_SSL_KEYSIZE > /dev/null 2>&1
	if [ $? -ne 0 ]
	then
		echo nsstart: `date` ': Error creating the RSA key file: $NS_ROOT_KEY' >> $LOG
		clean_default_certificate
		return
	fi

	###
	echo nsstart: `date` ': Creating the CSR for the root certificate'
	###
        #fixed for ENH0451441 and BUG0405363
	$NS_GEN_PROG -GUI certreq $NS_DEFAULT_PARTITIONID $NS_ROOT_REQ -keyFile $NS_ROOT_KEY -C $COUNTRY -S $STATE -L $LOCATION -O $ORGANIZATION -OU $ORGANIZATIONUNIT -CN $COMMONNAME
	if [ $? -ne 0 ]
	then
		echo nsstart: `date` ': Error creating the Certificate Signing Request file (CSR): $NS_ROOT_REQ' >> $LOG
		clean_default_certificate
		return
	fi

	###
	echo nsstart: `date` ': Create the Self-Signed Certificate root certificate'
	###
	$NS_GEN_PROG cert $NS_DEFAULT_PARTITIONID $NS_ROOT_CERT $NS_ROOT_REQ ROOT_CERT -keyFile $NS_ROOT_KEY -days 1000000
	if [ $? -ne 0 ]
	then
		echo nsstart: `date` ': Error creating the Self-Signed Certificate: $NS_ROOT_CERT' >> $LOG
		clean_default_certificate
		return
	fi


	###
	echo nsstart: `date` ': Creating the RSA key'
	###
	$NS_GEN_PROG rsakey $NS_DEFAULT_PARTITIONID $NS_SERVER_KEY $NS_SSL_KEYSIZE > /dev/null 2>&1
	if [ $? -ne 0 ]
	then
		echo nsstart: `date` ': Error creating the RSA key file: $NS_SERVER_KEY' >> $LOG
		clean_default_certificate
		return
	fi
	
	###
	echo nsstart: `date` ': Create the CSR for server cert'
	###
#fixed for ENH0451441 and BUG0405363
	$NS_GEN_PROG -GUI certreq $NS_DEFAULT_PARTITIONID $NS_SERVER_REQ -keyFile $NS_SERVER_KEY -C $COUNTRY -S $STATE -L $LOCATION -O $ORGANIZATION -OU $ORGANIZATIONUNIT -CN $COMMONNAME
	if [ $? -ne 0 ]
	then
		echo nsstart: `date` ': Error creating Certificate Signing Request file (CSR): $NS_SERVER_REQ' >> $LOG
		clean_default_certificate
		return
	fi

	###
	echo nsstart: `date` ': Create the Server Certificate'
	###
	$NS_GEN_PROG cert $NS_DEFAULT_PARTITIONID $NS_SERVER_CERT $NS_SERVER_REQ SRVR_CERT -CAcert $NS_ROOT_CERT -CAkey $NS_ROOT_KEY -CAserial $NS_ROOT_SRL -days 1000000
	if [ $? -ne 0 ]
	then
		echo nsstart: `date` ': Error creating the server Certificate: $NS_SERVER_CERT' >> $LOG
		clean_default_certificate
		return
	fi
}
start_late_daemons()
{
	echo -n 'Start additional daemons: '
	
	# monit needs to wait until the process tree is set up
	sleep 5

    case ${monit_enable} in
	[Yy][Ee][Ss])
		echo -n 'monit '
		${monit_program:-/usr/local/bin/monit} ${monit_flags}
		;;
	esac
	
	echo '.'
}
clean_default_certificate()
{
	rm $NS_ROOT_KEY >/dev/null 2>&1
	rm $NS_ROOT_REQ >/dev/null 2>&1
	rm $NS_ROOT_CERT >/dev/null 2>&1
	rm $NS_SERVER_KEY >/dev/null 2>&1
	rm $NS_SERVER_REQ >/dev/null 2>&1
	rm $NS_SERVER_CERT >/dev/null 2>&1
	rm $NS_ROOT_SRL >/dev/null 2>&1
}
clean_sftrust_certificate()
{
	rm $NS_SFTRUST_KEY >/dev/null 2>&1
	rm $NS_SFTRUST_REQ >/dev/null 2>&1
	rm $NS_SFTRUST_CERT >/dev/null 2>&1
}

add_epa_package()
{
	if [ ! -d "/netscaler/ns_gui/epa/scripts/win" ]
	then
		mkdir -p /netscaler/ns_gui/epa/scripts/win
	fi
	
	if [ ! -e "/netscaler/ns_gui/epa/scripts/win/epaPackage.exe" ]
	then
		ln -s /var/netscaler/gui/epa/scripts/win/epaPackage.exe /netscaler/ns_gui/epa/scripts/win/epaPackage.exe
	fi

	if [ ! -e "/netscaler/ns_gui/epa/version.xml" ]
	then
		ln -s /var/netscaler/gui/epa/version.xml /netscaler/ns_gui/epa/version.xml
	fi

	if [ ! -d "/netscaler/ns_gui/epa/scripts/mac" ]
	then
		mkdir -p /netscaler/ns_gui/epa/scripts/mac
	fi
	
	if [ ! -e "/netscaler/ns_gui/epa/scripts/mac/epamacversion.txt" ]
	then
		ln -s /var/netscaler/gui/epa/scripts/mac/epamacversion.txt /netscaler/ns_gui/epa/scripts/mac/epamacversion.txt
	fi
	
	if [ ! -e "/netscaler/ns_gui/epa/scripts/mac/Citrix_Endpoint_Analysis.dmg" ]
	then
		ln -s /var/netscaler/gui/epa/scripts/mac/Citrix_Endpoint_Analysis.dmg /netscaler/ns_gui/epa/scripts/mac/Citrix_Endpoint_Analysis.dmg
	fi
}

EULA_CHECK="/flash/nsconfig/.eula_run"
EULA_LOGFILE="/var/log/portaltheme.log"
EULA_UPGRADE="/var/netscaler/logon/themes/EULA/eula_upgrade.pl"

add_eula()
{
	if [ -e $EULA_UPGRADE ]
	then
		if [ ! -e $EULA_CHECK ]
		then
			perl /var/netscaler/logon/themes/EULA/eula_upgrade.pl
			if [ $? -eq 0 ]
			then
				touch $EULA_CHECK;
				echo "`date -u` :EULA_NSSTART: EULA file created on fresh installation\n" >> $EULA_LOGFILE
			else
				echo "`date -u` :EULA_NSSTART: Failed to call script for EULA file creation while installing HyperV image\n" >> $EULA_LOGFILE
			fi
		fi
	else
		echo "`date -u` :EULA_NSSTART: EULA upgrade script not found.Returning without creating EULA files\n" >> $EULA_LOGFILE
	fi
}

setup_dnssec()
{
	if [ ! -d $NSDNSSEC_DIR ]
	then
		mkdir -p $NSDNSSEC_DIR
	fi
}

modify_issuefile() {

        ipaddr=$(grep "set ns config" /nsconfig/ns.conf | awk '{print $5}')

        # The user has configured their own custom issue file
        issue_file="/nsconfig/issue"
        if [ -e "$issue_file" ]
                then
			append_or_update_issuefile "$issue_file"
			return 0;
	fi


        # There is no custom file, we need to append or update /etc/issue
        issue_file="/etc/issue"
	append_or_update_issuefile "$issue_file"
        return 0
}

append_or_update_issuefile() {
	issue_file=$1
	issue_file_is_modified "$issue_file"
	if [ "$?" == 1 ]
		then
			# Update
			update_ip_in_issue_file "$ipaddr" "$issue_file"
			return 0
	fi

	# Append
	append_ip_to_issue_file "$ipaddr" "$issue_file"
}

issue_file_is_modified() {
        issue_file=$1
        modified_hint="IP address:"
        if [ -z "$(grep "$modified_hint" "$issue_file")" ]
                then
                        return 0
        fi
        return 1
}

update_ip_in_issue_file() {
        ip=$1
        issue_file=$2

	line_hint="IP address:"
	replace_ip_in_target_line "$line_hint" "$ip" "$issue_file"
	line_hint="configure your appliance"
	replace_ip_in_target_line "$line_hint" "$ip" "$issue_file"

}

replace_ip_in_target_line() {
	linehint=$1
	ip=$2
	issue_file=$3

        orig_line=$(grep "$line_hint" "$issue_file")
        new_line=$(echo "$orig_line" | sed "s/[0-9]\{1,3\}.[0-9]\{1,3\}.[0-9]\{1,3\}.[0-9]\{1,3\}/$ip/g")

	clean_orig_line=$(echo $orig_line | sed -e 's/[\/&]/\\&/g')
	clean_new_line=$(echo $new_line | sed -e 's/[\/&]/\\&/g')

	sed -i '' "s/$clean_orig_line/$clean_new_line/g" "$issue_file"

}

append_ip_to_issue_file() {
        ip=$1
        issue_file=$2
        echo "" >> $issue_file
        echo "IP address: $ip" >> $issue_file
        echo "" >> $issue_file
	echo "To configure your appliance, please connect to: http://$ip or https://$ip:8443 (SSL restricted to export ciphers until license is applied)." >> $issue_file

}
###################################################################

echo 'NetScaler...'
$SETDEFAULTCPU
if [ -d $NSLOG_DIR ] ; then
	echo '' >>$LOG
	echo nsstart: `date` ': startup' >>$LOG
fi

set_hostname
create_default_certificate
create_sftrust_certificate

setup_dnssec
add_epa_package
add_eula

[ -r $NSCONFIG/nsbefore.sh ] && sh $NSCONFIG/nsbefore.sh
[ -r $NSCONFIG/upgrade_before_pe_start.sh ] && sh $NSCONFIG/upgrade_before_pe_start.sh && /bin/rm $NSCONFIG/upgrade_before_pe_start.sh


# clean up old debug files
# keep them around for now.
#rm -f /var/tmp/NSPPE-*.log > /dev/null 2>&1

#removing nsepa.db as we don't require it in Tagma
rm -f /nsconfig/nsepa.db > /dev/null 2>&1

# nssetup collects information needed by nsnetsvc and nsconfigd
$NETSCALER/nssetup -f

# now tell pitboss we're starting up
$NETSCALER/pb_tool -s 1

[ -r $NETSCALER/de_start.sh ] && sh $NETSCALER/de_start.sh

# nsconfig will continue with nsstartpe.sh
# Temporarily disable confmon (-C) to isolate BUG251543
$NETSCALER/nsnetsvc -S -C
RETURN=$?
case $RETURN in	# check the configurator return status
0)
	clean_nslogdir
	echo -e "\nNetScaler has started successfully"
	;;
*)
	# NetScaler failed to start
	if [ -d $NSLOG_DIR ] ; then
		echo nsstart: `date` ': startup failed, config return code ' $RETURN >>$LOG
		echo 'Startup failed - writing dmesg to the log file...'
		dmesg >>$LOG
	fi
	exit $RETURN
	;;
esac

# gzip any core files - quietly
if [ ! -f $NSCONFIG/.nogzip_core ]; then
	nohup nice -n 20 sh -c "find /var/core -name bounds -prune -o -type f -print | xargs gzip" >/dev/null 2>/dev/null &
fi
[ -r /var/opt/nfast/bin/thales_hsm_is_enrolled ] && sh /var/opt/nfast/scripts/startup/hardserver
[ -r /var/safenet/safenet_is_enrolled ] && sh /var/safenet/config/safenet_config && sh /var/safenet/gateway/start_safenet_gw

#start daemons which need to wait until the items in this script are run.

start_late_daemons

if [ $GW_DHCP -eq 1 ] 
	then
		echo "Updating /etc/issue with IP and management UI details..."
		modify_issuefile
fi
# exit code determines whether the script has sucessfully completed.  
# non-zero exit code means nsstart.sh failed to complete
exit 0
