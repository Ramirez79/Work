#!/bin/sh
#ZEBOS_OLD file is created by the Netscaler migration utility whenever it detects a version mismatch.
#It should copy the old ZebOS to ZebOS.conf.migrate and ZebOS.conf.[NETSCALER VERSION]

#ZEBOS_NEW is the file that is saved with the new version of Netscaler.

ZEBOS_OLD='/nsconfig/ZebOS.conf.migrate'
ZEBOS_NEW='/nsconfig/ZebOS.conf'
ZEBOS_BACKUP='/nsconfig/ZebOS.conf.bak'
OUTPUT='/nsconfig/ZebOS.conf.mig'
TEMP='/nsconfig/ZebOS_extract'

if [ -f $ZEBOS_OLD ]
then
  if [ `grep -c "interface 1\/1" $ZEBOS_OLD ` -gt 0 ]
  then
    echo "Migrating $ZEBOS_OLD to $ZEBOS_NEW"
    echo ""

#waiting for all the protocols to start
    sleep 10

    echo "Saving current ZebOS configuration to $ZEBOS_NEW"
    /netscaler/vtysh -e 'write file'

#waiting for vtysh to save the configuration
    sleep 3

    echo "Backup $ZEBOS_NEW to $ZEBOS_BACKUP" 
    cp $ZEBOS_NEW $ZEBOS_BACKUP


    sed -n '/interface 1\/1/,/!/{p;}' $ZEBOS_OLD | sed -n '/ip ospf/p' > $TEMP 
    sed -n '/interface 1\/1/,/!/{p;}' $ZEBOS_OLD | sed -n '/ip rip/p' >> $TEMP 
    sed "/interface vlan/r $TEMP" $ZEBOS_NEW > $OUTPUT 

    echo "Deleting $ZEBOS_OLD"
    rm $ZEBOS_OLD $TEMP

    echo "Copying $OUTPUT to $ZEBOS_NEW"
    cp $OUTPUT $ZEBOS_NEW

    echo -e "\nMigration successful."

    if [ "$1" = "--restart-daemons" ]
    then
	echo "Restarting routing daemons with the migrated configuration."
	killall -SIGINT imi nsm ospfd ripd bgpd ospf6d
	sleep 3
	/netscaler/imi -d
        ps ax | grep imi; ps ax | grep nsm ; ps ax | grep ospfd;  ps ax | grep ripd; ps ax | grep bgpd ; ps ax | grep ospf6d
    else
	echo "Did not apply the migrated configuration. You have to restart the daemons to apply the new configuration"
    fi
 
  else
    rm $ZEBOS_OLD
    echo "Nothing to migrate in ZebOS configuration"
  fi

else
	echo "No need to migrate ZebOS configuration"
	exit
fi
