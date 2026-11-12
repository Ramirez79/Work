#!/usr/bin/bash

#
# Overview:
#
# Upgrade script to upgrade firmware on the IPMI BMC (Baseband Management
# Controller) on the motherboard.  We will use the internal USB link on the
# motherboard connecting the BMC to the South Bridge chipset when the firmware
# supports this method.  Otherwise, we will use the slower and more CPU
# intensive internal KCS serial link
#
# We have four types of upgrades.
# 1. From vendor AMI's version 2.52 to the vendor ATEN's 3.39 or later.
#	Uses AMI's yafuflash utility over USB, since AMI 2.52 has USB support.
# 2. From vendor ATEN's version 3.02 to the vendor ATEN's 3.39 or later.
#	Uses ATEN's fUpdate utility over KCS.  Saves the user's IPMI configuration.
#	Discards the IPMI single power supply sensor configuration to replace
#	it will the new dual power supply sensor configuration.
# 3. From vendor ATEN's version 3.34 to the vendor ATEN's 3.39 or later.
#	Uses ATEN's fUpdate utility over KCS.  Saves the user's IPMI configuration.
#	Saves the new IPMI dual power supply sensor configuration.
# 4. From vendor ATEN's version 3.38 to the vendor ATEN's 3.39 or later.
#	Uses ATEN's fUpdate utility over USB.  Saves the user's IPMI configuration.
#	Saves the sensor configuration.
#
#

YAFU_PATH=/usr/bin/
FUPDATE_PATH=/usr/local/bin/
FIRM_PATH=/var/tmp/
# The version of the new firmware
BASE_FIRM=_bmc.bin
NEW_FIRM="0"

# Explicit paths need to be used since the path variable has not been set
# at this point in the boot up.  Exporting the path does not seem to work here.
MD5=/sbin/md5
CUT=/usr/bin/cut
CAT=/bin/cat
GREP=/usr/bin/grep
RM=/bin/rm
ECHO=/bin/echo
SYSCTL=/sbin/sysctl
AWK=/usr/bin/awk
SED=/usr/bin/sed
PS=/bin/ps
KILL=/bin/kill
SLEEP=/bin/sleep
NOHUP=/usr/bin/nohup
REBOOT=/sbin/reboot
IPMITOOL=/usr/local/sbin/ipmitool
SYSHEALTHD=/netscaler/syshealthd
CAMCONTROL=/sbin/camcontrol
PB_TOOL=/netscaler/pb_tool
BMC_LOG_FILE=/flash/nsconfig/bmc_upgrade.log

syshealthd_pid=?
CURRENT_BMC_VERSION=?
sleep_pid=?
PLATFORM=0


function bmc_log () {

	$ECHO $1 | tee $BMC_LOG_FILE

}

function bmc_hung () {

	bmc_log ""
	bmc_log "BMC boot Failure.  "
	bmc_log "The BMC is hung and requires a full and complete power cycle to recover."
	bmc_log ""
	bmc_log ""
	bmc_log "PLEASE REMOVE ALL POWER CORDS FOR 60 SECONDS TO RECOVER THE BMC,"
	bmc_log "AND TRY AGAIN."
	bmc_log ""
	bmc_log ""
	bmc_log ""
	bmc_log "BMC boot Failure.  "
	bmc_log "The BMC is hung and requires a full and complete power cycle to recover."
	bmc_log ""
	bmc_log ""
	bmc_log "PLEASE REMOVE ALL POWER CORDS FOR 60 SECONDS TO RECOVER THE BMC,"
	bmc_log "AND TRY AGAIN."
	bmc_log ""
	bmc_log ""
	bmc_log ""
	syshealthd_exit_failure
}

# See what we have currently installed before we try an upgrade.
# If this command fails, then the BMC must be hung.
# We will abort the upgrade if the BMC is hung,
# and expect the operator to recover the BMC.
function get_current_version () {
	CURRENT_BMC_VERSION=`$IPMITOOL mc info | $GREP "Firmware Revision" | $AWK '{print $4}' | $CUT -d: -f2`

	if [[ "$CURRENT_BMC_VERSION" == "Get Device ID command failed" || "$CURRENT_BMC_VERSION" == "" ]]
	then
		bmc_hung
	fi
}

# Check the md5 checksum before we upgrade to ensure
# we don't start with a corrupted image file.
function md5_checksum () {
	CALC_MD5=`$MD5 ${FIRM_PATH}${NEW_FIRM} | $CUT -d" " -f4`
	if [ "$CALC_MD5" != "$GOOD_MD5" ]
	then
		bmc_log ""
		bmc_log "LOM Upgrade aborted.  MD5 checksum failed on upgrade image file."
		bmc_log ""
		exit
	fi
}

function cleanup () {
	# Clean up files after upgrade.
	$RM $FIRM_PATH/*_bmc.bin
	$RM $FIRM_PATH/bmc_releases
	# Ensure we only try to upgrade if it has not been done successfully before.
}

function syshealthd_exit () {
	cleanup
	# Restart Syshealthd
	$NOHUP $SYSHEALTHD
	# Give syshealthd a second to start
	$SLEEP 1
	# Get the syshealthd PID.
	syshealthd_pid=`$PS auxlc | $GREP syshea | $AWK '{print $2}'`
	if  [ "$syshealthd_pid" != "" ]
	then
		# Turn on Pit Boss monitoring of syshealthd
		$PB_TOOL -t $syshealthd_pid
	fi
}

function syshealthd_exit_failure () {
	syshealthd_exit
	bmc_log "BMC_UPGRADE_FAILURE"
	exit 0
}

function syshealthd_exit_success () {
	syshealthd_exit
	bmc_log "BMC_UPGRADE_SUCCESS"
	exit 0
}

function warn_poweroff () {

	bmc_log
	bmc_log
	bmc_log "WARNING:  Please do not power off or reboot the machine during the BMC upgrade."
	bmc_log
	bmc_log "Starting BMC firmware upgrade."
	bmc_log "Process will take approximately 6 to 20 minutes to complete."
	bmc_log "The machine will reboot after the BMC firmware is updated."
	bmc_log
}

# For Corinth we have various upgrade methods depending on what we find.
# Use USB interface for AMI 2.52 to ATEN 3.3x or later
# Use KCS interface for 3.02 to 3.34.
# Discard sensor configuration for 3.02, d
# due to support added in 3.34 for dual power supply sensors.
# Use USB interface for 3.38 and later.

# Backup the user's IPMI configuration using USB
function do_aten_usb_backup () {
	${FUPDATE_PATH}fUpdate -c -d backup.bin -i usb
}

# Backup the user's IPMI configuration using KCS
function do_aten_kcs_backup () {
	${FUPDATE_PATH}fUpdate -c -d backup.bin -i kcs
}

# Restore the user's IPMI configuration using USB
function do_aten_usb_restore () {
	${FUPDATE_PATH}fUpdate -c -f backup.bin -i usb
}
# Restore the user's IPMI configuration using KCS
function do_aten_kcs_restore () {
	${FUPDATE_PATH}fUpdate -c -f backup.bin -i kcs
}

# Temporarily speed up the kcs interface.
# CPU use will go to almost 100%.
# So don't do leave it this way for production traffic.
function set_ipmi_busy_wait () {

	set_busy_wait=`$SYSCTL netscaler.ipmi_busy_wait=1`
}

function clear_ipmi_busy_wait () {
	clear_busy_wait=`$SYSCTL netscaler.ipmi_busy_wait=0`
}

# Upgrade using KCS.
# For upgrading from 3.02 to later
function do_aten_kcs_upgrade_discard_config () {
	# Do not save the sensor config due to the
	# addition of dual power supply sensor support
	# in 3.34.  Response option is "n"

	${FUPDATE_PATH}fUpdate -f ${FIRM_PATH}${NEW_FIRM} -i kcs -r n
}

# Upgrade using USB.
# Send a "0" to get past the "Press any key to continue prompt".
function do_aten_usb_upgrade () {
	$ECHO 0 | ${FUPDATE_PATH}fUpdate -f ${FIRM_PATH}${NEW_FIRM} -i usb -r y
}

# Upgrade using KCS.
# For upgrading from Corinth 3.34/Rome 3.21 to later
function do_aten_kcs_upgrade_save_config () {
	# Save the sensor config.  Response option is "y"

	${FUPDATE_PATH}fUpdate -f ${FIRM_PATH}${NEW_FIRM} -i kcs -r y
}

# See if new firmware version has been installed.
function check_current_version () {

	# Check to see that the new firmware is installed.
	INSTALLED_VERSION=`$IPMITOOL mc info | $GREP "Firmware Revision" | $AWK '{print $4}' | $CUT -d: -f2`

	INSTALLED_MAJOR_VERSION=`$ECHO $INSTALLED_VERSION | $CUT -d. -f1`
	INSTALLED_MAJOR_VERSION=${INSTALLED_MAJOR_VERSION#0} # Remove leading zeroes

    INSTALLED_MINOR_VERSION=`$ECHO $INSTALLED_VERSION | $CUT -d. -f2`
    INSTALLED_MINOR_VERSION=${INSTALLED_MINOR_VERSION#0} # Remove leading zeroes

	if [ "$INSTALLED_MAJOR_VERSION" -gt "$MAJOR_VERSION" ]
	then
		return 0
	fi
	if [ "$INSTALLED_MAJOR_VERSION" -eq "$MAJOR_VERSION" ]
	then
		if [ "$INSTALLED_MINOR_VERSION" -ge "$MINOR_VERSION" ]
		then
			return 0
		fi
	fi
	# 1 = FALSE
	# 0 = TRUE
	return 1
}

function wait_for_bmc_reboot () {

	# Wait for the new firmware to be flashed and the BMC to reboot
	bmc_log "It will take several minutes for the BMC to upgrade its flash and reboot itself"
	bmc_log "We will check its status every fifteen seconds"
	count_sleeps=0
	while :
	do
		if check_current_version
		then
			break;
		fi
		bmc_log "Waiting for upgrade to complete. Sleeping for fifteen seconds."
		$SLEEP 15
				let count_sleeps=count_sleeps+1
		# Wait a max of 180 seconds = 3 minutes
		if [ $count_sleeps -gt 12 ]
		then
			bmc_hung
		fi
	done
	# Wait for the bmc to become ready for the restore configuration command.
	# Otherwise, we will get command failed errors.
	$SLEEP 10
	bmc_log "BMC has been upgraded and rebooted itself"
	bmc_log ""
}

function upgrade_ami () {
	# Save the mac address in a file
	bmc_log "Writing MAC Address to file /var/tmp/my_bmc_mac"
	$IPMITOOL lan print 1 | $GREP "MAC A" | $CUT -d" " -f16 > /var/tmp/my_bmc_mac
	MACVAL=$(</var/tmp/my_bmc_mac)
	if [ "$MACVAL" == "" ]
	then
		bmc_log "BMC_IPMITOOL_SAVING_MAC_FAILURE"
		bmc_log "BMC_BMC_NOT_RESPONDING"
		syshealthd_exit_failure
	fi

	bmc_log "MAC Address:"
	bmc_log $MACVAL
	bmc_log
	bmc_log "Upgrading IPMI BMC Firmware"
	bmc_log

	# Attach the BMC USB Virtual CD-ROM device
	$IPMITOOL raw 0x30 0x70 0x0b 0x00

	bmc_log "It will take a couple of minutes for the BMC virtual device to be discovered."
	bmc_log "We will check its status every fifteen seconds"
	count_sleeps=0
	while [ "$IS_NOT_CDROM" == "" ]
	do
		IS_NOT_CDROM=`$CAMCONTROL devlist | $GREP CD 2>/dev/null`
		bmc_log "Waiting for device discovery. Sleeping for fifteen seconds."
		$SLEEP 15
		let count_sleeps=count_sleeps+1
		# Wait a max of 180 seconds = 3 minutes
		if [ $count_sleeps -gt 12 ]
		then
			bmc_log "BMC_DEVICE_DISCOVERY_FAILURE"
			syshealthd_exit_failure
		fi
	done

	# Send the image to the BMC using the CD-ROM device
	bmc_log "Send the image to the BMC"
	# Confirm that we really do want to do it with the "$ECHO 0" below
	$ECHO 0 | ${YAFU_PATH}yafuflash -full -force-boot -cd ${FIRM_PATH}${NEW_FIRM}

	# Wait for the new firmware to be flashed and the BMC to reboot
	# AMI has SNMP Community String of "AMI"
	# ATEN has "public"

	bmc_log "It will take several minutes for the BMC to upgrade its flash and reboot itself"
	bmc_log "We will check its status every fifteen seconds"
	while :
	do
		IS_NOT_ATEN=`$IPMITOOL lan print | $GREP Community | $GREP public 2>/dev/null`
		if [ "$IS_NOT_ATEN" != "" ]
		then
			break;
		fi
		bmc_log "Waiting for upgrade to complete. Sleeping for fifteen seconds."
		$SLEEP 15
	done

	bmc_log "BMC has been upgraded and rebooted itself"
	bmc_log ""
	bmc_log "Writing the MAC address back to the BMC"
	$IPMITOOL lan set 1 macaddr $MACVAL
	$SLEEP 10
	bmc_log ""
	bmc_log ""
	bmc_log "Please check the MAC address below is not zero."
	bmc_log ""
	bmc_log ""
	$IPMITOOL lan print | $GREP "MAC Address"
	bmc_log ""
	bmc_log ""
}

function upgrade_not_needed () {

		bmc_log ""
		bmc_log "An upgrade is not needed."
		bmc_log "The current installed BMC version is the same or newer than the upgrade candidate."
		bmc_log "Upgrade aborted."
		bmc_log ""
		syshealthd_exit_success
}

function upgrade_usb () {

	# Lets do an upgrade.
	warn_poweroff
	do_aten_usb_backup
	do_aten_usb_upgrade
	wait_for_bmc_reboot
	do_aten_usb_restore
}

function upgrade_kcs_save_config () {
	# Lets do an upgrade.
	warn_poweroff
	set_ipmi_busy_wait
	do_aten_kcs_backup
	do_aten_kcs_upgrade_save_config
	wait_for_bmc_reboot
	do_aten_kcs_restore
	clear_ipmi_busy_wait
}

# Due to sensor changes we need to discard the old
# sensor configuration.
function upgrade_kcs_discard_config () {
	# Lets do an upgrade.
	warn_poweroff
	set_ipmi_busy_wait
	do_aten_kcs_backup
	do_aten_kcs_upgrade_discard_config
	wait_for_bmc_reboot
	do_aten_kcs_restore
	clear_ipmi_busy_wait
}

function upgrade_8k2 () {

	# Check if current version is the same or newer than the upgrade candidate.
	if check_current_version
	then
		upgrade_not_needed
	fi

	if    [ "$INSTALLED_MAJOR_VERSION" -ge "3" ]
	then
		# Use USB interface for 3.21 and later.
		if  [ "$INSTALLED_MINOR_VERSION" -ge "21" ]
		then
			upgrade_usb
		fi
	elif  [ "$INSTALLED_MAJOR_VERSION" -ge "2" ]
	then
        	# Use KCS interface for upgrades from 2.xx
        	upgrade_kcs_save_config
	fi
}

function upgrade_11k5 () {

	# Check if current version is the same or newer than the upgrade candidate.
	if check_current_version
	then
		upgrade_not_needed
	fi

	# Use the AMI method over USB for older AMI firmware.
	if  [[ "$INSTALLED_MAJOR_VERSION" -eq "2" && "$INSTALLED_MINOR_VERSION" -eq "52" ]]
	then
		upgrade_ami
	elif  [ "$INSTALLED_MAJOR_VERSION" -ge "3" ]
	then
		# Use USB interface for 3.38 and later.
		if  [ "$INSTALLED_MINOR_VERSION" -ge "38" ]
		then
			upgrade_usb
		elif  [ "$INSTALLED_MINOR_VERSION" -ge "34" ]
		then
			# Use KCS interface for upgrades from 3.02 to 3.34 (there is no 3.35 to 3.37)
			upgrade_kcs_save_config
		elif  [ "$INSTALLED_MINOR_VERSION" -ge "2" ]
		then
			upgrade_kcs_discard_config
		fi
	fi
}

function upgrade_22k () {

	# Check if current version is the same or newer than the upgrade candidate.
	if check_current_version
	then
		upgrade_not_needed
	fi

	# Lets do an upgrade.
	warn_poweroff
	if    [ "$INSTALLED_MAJOR_VERSION" -ge "3" ]
	then
		# Use USB interface for 3.24 and later.
		if  [ "$INSTALLED_MINOR_VERSION" -ge "24" ]
		then
			upgrade_usb
		fi
    elif  [ "$INSTALLED_MAJOR_VERSION" -ge "2" ]
	then
        # Use KCS interface for upgrades from 2.63 to 3.23
        upgrade_kcs_save_config
	fi
}


function upgrade_19k () {

	# Check if current version is the same or newer than the upgrade candidate.
	if check_current_version
	then
		upgrade_not_needed
	fi
	# Newer platforms support USB upgrade from FCS.
	upgrade_usb

}

function upgrade_30k () {

	# Check if current version is the same or newer than the upgrade candidate.
	if check_current_version
	then
		upgrade_not_needed
	fi
	# Newer platforms support USB upgrade from FCS.
	upgrade_usb
}


# 5k5 and 8k2 have the same sysid range and share the same firmware,
# but different lan mode configuration.
# We do a save/restore configuration before/after to preserve the lan
# configuration.
check_platform_8k2 () {
	if [ "$PLATFORM" == "0" ]
	then
		# Check for Rome family based on the X9SPU Motherboard
		SYS_ID_67=`$ECHO $SYS_ID | $SED -ne '/6753[0-9][0-9]/p'`
		if [ "$SYS_ID_67" != "" ]
		then
			PLATFORM=8k2
		else
			PLATFORM=0
		fi
	fi
}

check_platform_11k5 () {
	if [ "$PLATFORM" == "0" ]
	then
		# Check for Corinth/Galata families based on the X8DTE Motherboard
		SYS_ID_50=`$ECHO $SYS_ID | $SED -ne '/14051[00|30|50|52]/p'`
		SYS_ID_10=`$ECHO $SYS_ID | $SED -ne '/1400[1|2][0|1]0/p'`
		if [[ "$SYS_ID_50" != "" || "$SYS_ID_10" != "" ]]
		then
			PLATFORM=11k5
		else
			PLATFORM=0
		fi
	fi
}

check_platform_22k () {
	if [ "$PLATFORM" == "0" ]
	then
		# Check for Decapolis family based on the X9DRX Motherboard
		# Needs a different firmware to talk to the FPC (secondary BMC)
		SYS_ID_22=`$ECHO $SYS_ID | $SED -ne '/2200[0-9][0-9][0-9]/p'`
		if [ "$SYS_ID_22" != "" ]
		then
			PLATFORM=22k
		else
			PLATFORM=0
		fi
	fi
}

check_platform_19k () {
	if [ "$PLATFORM" == "0" ]
	then
		# Check for Zela families based on the X9DRX Motherboard
		SYS_ID_19=`$ECHO $SYS_ID | $SED -ne '/250[0|1][0-9][0-9]/p'`
		if [ "$SYS_ID_19" != "" ]
		then
			PLATFORM=19k
		else
			PLATFORM=0
		fi
	fi
}

check_platform_30k () {
	if [ "$PLATFORM" == "0" ]
	then
		# Check for Gladius/Hasta families based on the X10SPU Motherboard
		SYS_ID_22=`$ECHO $SYS_ID | $SED -ne '/300[0-9][0-9]/p'`
		if [ "$SYS_ID_22" != "" ]
		then
			PLATFORM=30k
		else
			PLATFORM=0
		fi
	fi
}

check_platform () {

	#
	#  Find out the model family for this platform
	#
	SYS_ID=`$SYSCTL netscaler.sysid | $AWK '{print $2}'`

	check_platform_8k2
	check_platform_11k5
	check_platform_19k
	check_platform_22k
	check_platform_30k

	if [ "$PLATFORM" == "0" ]
	then
		bmc_log "LOM Upgrade aborted. This platform is not supported."
		return 1
	fi

	#
	# Setup the filename and check the md5
	#
	NEW_FIRM=${PLATFORM}${BASE_FIRM}
	if [ -f "${FIRM_PATH}${NEW_FIRM}" ]
	then
		if [ -f "${FIRM_PATH}bmc_releases" ]
		then
			GOOD_MD5=`$CAT ${FIRM_PATH}bmc_releases | $GREP ${NEW_FIRM} | $CUT -d" " -f3`
			NEW_VERSION=`$CAT ${FIRM_PATH}bmc_releases | $GREP ${NEW_FIRM} | $CUT -d" " -f6`

			MAJOR_VERSION=`$ECHO $NEW_VERSION | $CUT -d. -f1`
			MAJOR_VERSION=${MAJOR_VERSION#0} # Remove leading zeroes

			MINOR_VERSION=`$ECHO $NEW_VERSION | $CUT -d. -f2`
			MINOR_VERSION=${MINOR_VERSION#0} # Remove leading zeroes

			md5_checksum
		else
			bmc_log ""
	 		bmc_log "LOM Upgrade aborted.  Upgrade releases file ${FIRM_PATH}bmc_releases missing."
			bmc_log ""
			exit
		fi
	else
		bmc_log ""
		bmc_log "LOM Upgrade aborted.  Upgrade image file ${FIRM_PATH}${NEW_FIRM} missing."
		bmc_log ""
		exit
	fi

    return 0

}

function stop_syshealthd () {
	# Stop Syshealthd if it is running
	# Get the syshealthd PID.
	syshealthd_pid=`$PS auxlc | $GREP syshea | $AWK '{print $2}'`
	# Turn off Pit Boss monitoring of syshealthd
	if  [ "$syshealthd_pid" != "" ]
	then
		$PB_TOOL -d $syshealthd_pid
		$SLEEP 1
	fi
	count_kills=0
	while [ "$syshealthd_pid" != "" ]
	do
		$KILL -9 $syshealthd_pid
		# Give the kill command a second to work
		$SLEEP 1
		let count_kills=count_kills+1
		if [ $count_kills -gt 60 ]
		then
			bmc_log "BMC_FAILED_TO_KILL_SYSHEALTHD"
			syshealthd_exit_failure
		fi
		syshealthd_pid=`$PS auxlc | $GREP syshea | $AWK '{print $2}'`
	done
	syshealthd_pid=`$PS auxlc | $GREP syshea | $AWK '{print $2}'`
	if  [ "$syshealthd_pid" != "" ]
	then
		bmc_log "BMC_FAILED_TO_KILL_SYSHEALTHD"
		syshealthd_exit_failure
	fi

}

function upgrade_complete () {
	bmc_log ""
	bmc_log "Completed Upgrading IPMI BMC Firmware"
	bmc_log ""
	bmc_log "	 F I N I S H E D "
	bmc_log ""
	bmc_log " R E B O O T I N G   I N   10   S E C O N D S"
	cleanup
	$SLEEP 10
	$REBOOT
}

# Check for a platform where we support BMC firmware upgrade.
if check_platform
then
	# Check for the existence of ipmitool
	if [ -f "$IPMITOOL" ]
	then
		stop_syshealthd
		# Get the BMC firmware revision
		get_current_version
		# Lets see if an upgrade is needed
		# and use the correct method for the platform.
		upgrade_${PLATFORM}
		upgrade_complete
	else
		# No ipmitool found
		bmc_log "BMC_IPMITOOL_NOT_FOUND"
		cleanup
		exit 0
	fi # endif we have ipmitool
else
	# No sysid found that supports an upgrade.
	cleanup
	exit 0
fi # endif we have completed a BMC upgrade.


