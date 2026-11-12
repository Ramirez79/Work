#!/bin/sh
guipath="/netscaler/ns_gui"
guipath2="/netscaler/ns_gui2"
customguipath="/var/ns_gui_custom"
logfile="/var/log/uitheme.log"

if [ !  -e $logfile ]
then
	touch $logfile;
fi

case $1 in 
	1)
	if [ -e $guipath ] 
	then
		if [ -e $guipath2 ] 
		then
			rm /netscaler/ns_gui
			mv /netscaler/ns_gui2 /netscaler/ns_gui
		fi
	else
		echo "directory $guipath does not exist while changing to default theme" >> $logfile
	fi
	;;
	2)
	if [ -e $guipath ]
	then
		if [ -e $guipath2 ] 
		then
			rm /netscaler/ns_gui
			mv /netscaler/ns_gui2 /netscaler/ns_gui
		fi
	else
		echo "directory $guipath does not exist while changing to greenbubble theme" >> $logfile
	fi
	;;
	3)
	if [ -e $customguipath ] ;
	then
		cd $customguipath;
		if [ -e ./customtheme.tar.gz ]
		then
			if [ ! $(tar -xzf customtheme.tar.gz) ]
			then
				if [ ! -e $guipath2 ]
					then
						mv /netscaler/ns_gui /netscaler/ns_gui2
						ln -s /var/ns_gui_custom/ns_gui/ /netscaler/ns_gui
				fi
			else
				echo "Untarring custometheme.tar.gz failed" >> $logfile
			fi
		else
			echo "customtheme.tar.gz does not exist" >> $logfile
		fi
	else
		echo "directory $customguipath does not exist" >> $logfile
	fi
	;;
esac
