#!/bin/sh
# NetScaler Upgrade Import from 9.0-Beta-1 script
# FILE:         $Id: //depot/main/rs_111/usr.src/netscaler/scripts/upgradeAppfwImports.sh#1 $
# LAST CHECKIN: $Author: build $
#               $DateTime: 2016/06/13 09:44:25 $
#
# Copyright 1998-2016 Citrix Systems, Inc. All rights reserved.
# This software and documentation contain valuable trade
# secrets and proprietary property belonging to Citrix Systems, Inc.
# None of this software and documentation may be copied,
# duplicated or disclosed without the express
# written permission of Citrix Systems, Inc.
#
# Options:      NONE
#
# Called via installns creating ns_before_upgrade.sh which is called after
# boot but before NS startup.

process_reimport_objects()
{
	object_type=$1
	case $object_type in
		"signatures")
			object_dir="custom" ;;
		"htmlerrorpage")
			object_dir="htmlerrorurl" ;;
		"xmlerrorpage")
			object_dir="xmlerrorurl" ;;
	esac
	if [ -d /var/download/$object_dir ]
	then
	    for file in /var/download/$object_dir/*
	    do
		flag=0
		if [ -f "$file" ]
		then
		    # /bin/cp "$file" /var/tmp/
		    fileWithoutPath=`basename "$file"`
		    LowerCaseFileNameWithoutPath=`echo $fileWithoutPath | tr '[:upper:]' '[:lower:]'`
		    /bin/mv -f "$file" /var/tmp/
		    # This condition is added to remove the file with
		    # whitespace in name and import again without space in the
		    # name.
		    is_space=`echo $fileWithoutPath | grep \ | wc -l`
		    if [ "$is_space" -ne 0 ]
		    then
			flag=1
			arguments="\"$fileWithoutPath\""
                        if [ $before_ppe_start = 1 ]
                        then
                            logger -p local0.info "#1 Setting before_ppe_start for remove"
                            arguments="-before_ppe_start $arguments"
                        fi
			args_64=`echo $arguments | openssl base64 | tr \\\n \\\0`
			/netscaler/remove $object_type $args_64
			remove_status=$?
			if [ $remove_status != 0 ]
			then
			    logger -p local0.warn -t $0 "Removing of \"$fileWithoutPath\" failed.  Status = " $remove_status
			fi
			fileWithoutPathWithSpace=$fileWithoutPath
			fileWithoutPath=`echo "$fileWithoutPath" | tr -d ' '`
		        /bin/mv -f /var/tmp/$fileWithoutPathWithSpace /var/tmp/$LowerCaseFileNameWithoutPath
		    else
		        /bin/mv -f /var/tmp/$fileWithoutPath /var/tmp/$LowerCaseFileNameWithoutPath
		    fi
		    if [ "$flag" -ne 1 ]
		    then
		        arguments="\"$fileWithoutPath\""
                        if [ $before_ppe_start = 1 ]
                        then
                            logger -p local0.info "#2 Setting before_ppe_start for remove"
                            arguments="-before_ppe_start $arguments"
                        fi
		        args_64=`echo $arguments | openssl base64 | tr \\\n \\\0`
		        /netscaler/remove $object_type $args_64
		        remove_status=$?
		        if [ $remove_status != 0 ]
		        then
				logger -p local0.warn -t $0 "Remove of \"$fileWithoutPath\" failed.  Status = " $remove_status
		        fi
		    fi
		    #xmlimport reimports the file and also upgrades to latest signature version.
		    arguments="\"local://$LowerCaseFileNameWithoutPath\" \"$LowerCaseFileNameWithoutPath\""
                    if [ $before_ppe_start = 1 ]
                    then
                        logger -p local0.info "Setting before_ppe_start for import"
                        arguments="$arguments -before_ppe_start"        # Option must be at the end
                    fi
		    args_64=`echo $arguments | openssl base64 | tr \\\n \\\0`
		    /netscaler/import $object_type $args_64
		    import_status=$?
		    if [ $import_status != 0 ]
		    then
			logger -p local0.warn -t $0 "Import of \"$LowerCaseFileNameWithoutPath\" failed.  Status = " $import_status
		    fi
		    /bin/rm /var/tmp/"$LowerCaseFileNameWithoutPath"
		fi
	    done
	fi
}

logger -p local0.info "Upgrading imported AppFw files using script:" $0

if [ $# = 1 ] && [ $1 = "-before_ppe_start" ]; then
        logger -p local0.info "before_ppe_start is set"
        before_ppe_start=1
else
        logger -p local0.info "before_ppe_start is NOT set"
        before_ppe_start=0
fi



if [ -d /var/download/xsd ]
then
	/bin/mv -f /var/download/xsd /var/download/xmlschema/
fi

if [ -f /var/download/mapping-schema ]
then
	/bin/mv -f /var/download/mapping-schema /var/download/mapping-xmlschema
fi

if [ -d /var/download/xmlerrorurl ]
then
	process_reimport_objects "xmlerrorpage"
fi

if [ -d /var/download/htmlerrorurl ]
then
	process_reimport_objects "htmlerrorpage"
fi

if [ -d /var/download/custom ]
then
	process_reimport_objects "signatures"
fi

if [ -f /nsconfig/updated_signatures.xml ]
then
	/bin/rm -f /nsconfig/updated_signatures.xml
fi
