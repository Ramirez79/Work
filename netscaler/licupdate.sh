#!/bin/sh
#DO NOT change the line endings in this file.

detectOS () {
	varOS=`uname -o`;
	if [ "${varOS}" == "FreeBSD" ]; 
	then
		varDetectedOS="FreeBSD"
	else
		varDetectedOS="Linux"
	fi
}

getConfPathFromCmdLine () {
	varConfPath=$1
}

getLicsPathFromCmdLine () {
	varLicsPath=$1
}

getPortsFromConf () {
	getLsPortFromConf
	getVdPortFromConf
}

validatePortsInConf () {
	if [ "isEmpty$varLsPortInConf" == "isEmpty" ] || [ "isEmpty$varVdPortInConf" == "isEmpty" ] || [ "$varLsPortInConf" == "$varVdPortInConf" ]
	then
		exit 1
	fi
}

updateLicsWithPorts () {
	find "${varLicsPath}" -name '*.lic' | while read varLicFile;
	do
		convertLineEnding2Lin

		getExistingLsPortFromLic
		if [ "isEmpty$varLsPortInLic" == "isEmpty" ]
		then
			insertLsPortInLic
		else
			updateLsPortInLic
		fi

		getExistingVdPortFromLic
		if [ "isEmpty$varVdPortInLic" == "isEmpty" ]
		then
			insertVdPortInLic
		else
			updateVdPortInLic
		fi

		convertLineEnding2Win
	done

}

getLsPortFromConf () {
	if [ "$varDetectedOS" == "FreeBSD" ]
	then
		varLsPortInConf=`grep -i LSPORT "$varConfPath" | sed -n "s/^[^#].*=[ 	]*\([0-9]\{1,5\}$\)/\1/p"`;
	else
		varLsPortInConf=`grep -i LSPORT "$varConfPath" | sed -n "s/^[^#].*=[ \t]*\([0-9]\{1,5\}$\)/\1/p"`;
	fi
}

getVdPortFromConf () {
	if [ "$varDetectedOS" == "FreeBSD" ]
	then
		varVdPortInConf=`grep -i VDPORT "$varConfPath" | sed -n "s/^[^#].*=[ 	]*\([0-9]\{1,5\}$\)/\1/p"`;
	else
		varVdPortInConf=`grep -i VDPORT "$varConfPath" | sed -n "s/^[^#].*=[ \t]*\([0-9]\{1,5\}$\)/\1/p"`;
	fi
}

getExistingLsPortFromLic () {
	if [ "$varDetectedOS" == "FreeBSD" ]
	then
		varLsPortInLic=`sed -E -n 's/^SERVER .* .* ([0-9]+)$/\1/p' <"$varLicFile"`;
	else
		varLsPortInLic=`sed -n 's/^SERVER\s.*\s.*\s\(.*\)$/\1/p' <"$varLicFile"`;
	fi
}

getExistingVdPortFromLic () {
	if [ "$varDetectedOS" == "FreeBSD" ]
	then
		varVdPortInLic=`sed -E -n 's/^VENDOR .* PORT\=([0-9]+)$/\1/p' <"$varLicFile"`;
	else
		varVdPortInLic=`sed -n 's/^VENDOR\s.*\+\sPORT\=\([0-9]\+\)$/\1/p' <"$varLicFile"`;
	fi
}

insertLsPortInLic () {
	if [ "$varDetectedOS" == "FreeBSD" ]
	then
		`sed -i '' "s/^\(SERVER .* .*$\)/\1 ${varLsPortInConf}/" "$varLicFile"`;
	else
		`sed -i "s/^\(SERVER\s.*\s.*$\)/\1 ${varLsPortInConf}/" "$varLicFile"`;
	fi
}

insertVdPortInLic () {
	if [ "$varDetectedOS" == "FreeBSD" ]
	then
		`sed -i '' "s/^\(VENDOR .*$\)/\1 PORT=${varVdPortInConf}/" "$varLicFile"`;
	else
		`sed -i "s/^\(VENDOR\s.*$\)/\1 PORT=${varVdPortInConf}/" "$varLicFile"`;
	fi
}

updateLsPortInLic () {
	if [ "$varDetectedOS" == "FreeBSD" ]
	then
		`sed -E -i '' "s/^(SERVER .* .* )[0-9]+$/\1${varLsPortInConf}/" "$varLicFile"`;
	else
		`sed -i "s/^\(SERVER\s.*\s.*\s\)[0-9]\+$/\1${varLsPortInConf}/" "$varLicFile"`;
	fi
}

updateVdPortInLic () {
	if [ "$varDetectedOS" == "FreeBSD" ]
	then
		`sed -E -i '' "s/^(VENDOR .* PORT\=)[0-9]+$/\1${varVdPortInConf}/" "$varLicFile"`;
	else
		`sed -i "s/^\(VENDOR\s.*\sPORT\=\)[0-9]\+$/\1${varVdPortInConf}/" "$varLicFile"`;
	fi
}

convertLineEnding2Lin () {
	if [ "$varDetectedOS" == "FreeBSD" ]
	then
		`sed -i '' 's///' "$varLicFile"`;
	else
		`sed -i 's/\r//' "$varLicFile"`;
	fi
}

convertLineEnding2Win () {
	if [ "$varDetectedOS" == "FreeBSD" ]
	then
		`sed -i '' 's/$//' "$varLicFile"`;
	else
		`sed -i 's/$/\r/' "$varLicFile"`;
	fi
}

detectOS
getConfPathFromCmdLine $1
getLicsPathFromCmdLine $2
getPortsFromConf
validatePortsInConf
updateLicsWithPorts
exit 0
