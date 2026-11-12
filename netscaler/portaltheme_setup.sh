#!/bin/sh
logfile="/var/log/portaltheme.log"
themepath="/var/netscaler/logon/themes/"
caxton="/var/netscaler/logon/themes/Default/"
greenbubble="/var/netscaler/logon/themes/Greenbubble/"
X1="/var/netscaler/logon/themes/X1/"
RFWEBUI="/var/netscaler/logon/LogonPoint/"
eulapath="/var/netscaler/logon/themes/EULA/resources/"
eula_script="/netscaler/clear_eula.pl"
theme=$themepath"$2"
css=$theme"/css"
media=$theme"/custom_media"
if [ !  -e $logfile ]
then
        touch $logfile;
fi
case $1 in
        0)
        if [ -e $themepath ]
	then
		if [ $(find $themepath -maxdepth 1 ! -path $themepath -type d ! -name 'Default' ! -name 'Greenbubble' ! -name 'X1' ! -name 'EULA' -print0 | xargs -0 rm -rf) ]
		then
			echo "`date -u` : Clear config was fired.  But couldnt delete portal themes ." >> $logfile
		else
			echo "`date -u` :Clear config was fired.Deleting all user created portal themes." >> $logfile
		fi
		if [ -e $eulapath ]
		then
			if [$(perl $eula_script 0) ]
			then
				echo "EULA config clear was unsuccessful." >> $logfile
			fi
		else
			echo "$eulapath folder was not found. This condition does not normally occur. Netscaler reinstallation is recommended." >> $logfile
		fi
	else
                echo "`date -u` :Clear config was fired but $themepath does not exist. This condition does not normally occur. Netscaler reinstallation is recommended." >> $logfile
        fi
        ;;
        1)
        if [ -e "$theme" ]
        then	
                if [ $(rm -rf "$theme") ]
                then
			echo "`date -u` :Removal of '$2' theme's directory failed" >> $logfile
                else
			echo "`date -u` : Removal of portaltheme $theme is successful." >> $logfile
		fi
        else
                echo "`date -u` : Theme '$2' does not have a corresponding directory present. Nothing to remove." >> $logfile
        fi
        ;;
	2)
	if [  -e "$theme" ]
	then
		if [ $(rm -rf "$theme")]
		then
			echo "`date -u` :  Deleting old theme while adding theme with same name failed. Please delete manually." >> $logfile
		else
			echo "`date -u` : Theme name was already present. Deleting older theme first." >> $logfile
		fi

	fi
			   
		   if [ !  $(mkdir "$theme") ] 	
		   then
		   case	$3 in
			   1)
				if [ $(cp -r "$caxton"resources "$theme") ]
				then
					echo "`date -u` :Copying resources directory from default theme to $theme failed" >> $logfile
				else
					if [ $(rm -rf "$theme"/resources/config.xml) ]
					then
						echo "`date -u` :Failed to remove config.xml in $theme directory before making soft link" >> $logfile
					fi
					if [ $(ln -s "$caxton"resources/config.xml "$theme"/resources/config.xml) ]
					then
						echo "`date -u` :Failed to create soft link for config.xml while creating '$2' theme." >> $logfile
					fi

				fi
				if [  $(mkdir "$media") ]
				then
					echo "`date -u` :Creation of custom_media directory failed for $theme." >> $logfile
				fi
				if [ ! $(mkdir "$css") ]
				then
					if [ $(ln -s "$caxton"css/base.css "$css"/base.css) ]
					then
						echo "`date -u` :Failed to create symbolic link for base.css while creating '$2' theme" >> $logfile
					fi
					if [ $(ln -s "$caxton"css/styles.css "$css"/styles.css) ]
					then
						echo "`date -u` :Failed to create symbolic link for styles.css while creating '$2' theme" >> $logfile
					fi
					if [ !  -e "$css"/custom.css ]
					then
						if [ $(touch "$css"/custom.css) ]
						then
							echo "`date -u` :Failed to create custom.css file for '$2' theme." >> $logfile
						fi
					fi
				else
					echo "`date -u` :css directory creation for $theme failed."
				fi
				if [ $(cp "$caxton"homepage.html "$theme") ]
				then
					echo "`date -u` :Failed to copy homepage from default theme directory to '$2' theme's directory." >> $logfile
				fi
				if [ $(cp "$caxton"Default.json "$theme"/custom_theme.json) ]
				then
					echo "`date -u` :Failed to copy Default.json to '$2' theme's directory." >> $logfile
				fi
				;;
			   2)		
				if [ $(cp -r "$greenbubble"resources "$theme") ]
				then
					echo "`date -u` :Copying resources directory from greenbubble theme to $theme failed" >> $logfile
				else
					if [ $(rm -rf "$theme"/resources/config.xml) ]
					then
						echo "`date -u` :Failed to remove config.xml in $theme directory before making soft link" >> $logfile
					fi
					if [ $(ln -s "$greenbubble"resources/config.xml "$theme"/resources/config.xml) ]
					then
						echo "`date -u` :Failed to create soft link for config.xml while creating '$2' theme." >> $logfile
					fi
				fi
				if [  $(mkdir "$media") ]
				then
					echo "`date -u` : Creation of custom_media directory failed for $theme." >> $logfile
				fi
				if [ ! $(mkdir "$css") ]
				then 
					if [ $(ln -s "$greenbubble"css/base.css "$css"/base.css) ]
					then
						echo "`date -u` :Failed to create symbolic link for base.css while creating '$2' theme" >> $logfile
					fi
					if [ $(ln -s "$greenbubble"css/styles.css "$css"/styles.css) ]
					then
						echo "`date -u` :Failed to create symbolic link for styles.css while creating '$2' theme" >> $logfile
					fi
					if [ !  -e "$css"/custom.css ]
					then
						if [ $(touch "$css"/custom.css) ]
						then
							echo "`date -u` :Failed to create custom.css file for '$2' theme." >> $logfile
						fi
					fi
				else
					echo "`date -u` :css directory creation for $theme failed."
				fi
				if [ $(cp "$greenbubble"homepage.html "$theme") ]
				then
					echo "`date -u` :Failed to copy homepage from greenbubble theme directory to '$2' theme's directory." >> $logfile	
				fi
				if [ $(cp "$greenbubble"Greenbubble.json "$theme"/custom_theme.json) ]
				then
					echo "`date -u` :Failed to copy Greenbubble.json to '$2' theme's directory." >> $logfile	
				fi
				;;
			   3)	
				if [ $(cp -r "$X1"resources "$theme") ]
				then
					echo "`date -u` :Copying resources directory from X1 theme to $theme failed" >> $logfile
				else
					if [ $(rm -rf "$theme"/resources/config.xml) ]
					then
						echo "`date -u` :Failed to remove config.xml in $theme directory before making soft link" >> $logfile
					fi
					if [ $(ln -s "$X1"resources/config.xml "$theme"/resources/config.xml) ]
					then
						echo "`date -u` :Failed to create soft link for config.xml while creating '$2' theme." >> $logfile
					fi
				fi
				if [  $(mkdir "$media") ]
				then
					echo "`date -u` : Creation of custom_media directory failed for $theme." >> $logfile
				fi
				if [ ! $(mkdir "$css") ]
				then 
					if [ $(ln -s "$X1"css/base.css "$css"/base.css) ]
					then
						echo "`date -u` :Failed to create symbolic link for base.css while creating '$2' theme" >> $logfile
					fi
					if [ $(ln -s "$X1"css/styles.css "$css"/styles.css) ]
					then
						echo "`date -u` :Failed to create symbolic link for styles.css while creating '$2' theme" >> $logfile
					fi
					if [ !  -e "$css"/custom.css ]
					then
						if [ $(touch "$css"/custom.css) ]
						then
							echo "`date -u` :Failed to create custom.css file for '$2' theme." >> $logfile
						fi
					fi
				else
					echo "`date -u` :css directory creation for $theme failed."
				fi
				if [ $(cp "$X1"homepage.html "$theme") ]
				then
					echo "`date -u` :Failed to copy homepage from X1 theme directory to '$2' theme's directory." >> $logfile	
				fi
				if [ $(cp "$X1"X1.json "$theme"/custom_theme.json) ]
				then
					echo "`date -u` :Failed to copy X1.json to '$2' theme's directory." >> $logfile	
				fi
				;;
			    4)
				if [ $(cp "$RFWEBUI"RfWebUI.json "$theme"/custom_theme.json) ]
				then
					echo "`date -u` :Failed to copy RfWebUI.json to '$2' theme's directory." >> $logfile
				fi
				if [ $(cp -r "$X1"resources "$theme") ]
				then
					echo "`date -u` :Copying resources directory from X1 theme to $theme failed" >> $logfile
				else
					if [ $(rm -rf "$theme"/resources/config.xml) ]
					then
						echo "`date -u` :Failed to remove config.xml in $theme directory before making soft link" >> $logfile
					fi
					if [ $(ln -s "$X1"resources/config.xml "$theme"/resources/config.xml) ]
					then
						echo "`date -u` :Failed to create soft link for config.xml while creating '$2' theme." >> $logfile
					fi
				fi
				if [ ! $(mkdir "$css") ]
				then 
					if [ $(ln -s "$X1"css/base.css "$css"/base.css) ]
					then
						echo "`date -u` :Failed to create symbolic link for base.css while creating '$2' theme" >> $logfile
					fi
					if [ !  -e "$css"/theme.css ]
					then
						if [ $(touch "$css"/theme.css) ]
						then
							echo "`date -u` :Failed to create theme.css file for '$2' theme." >> $logfile
						fi
					fi
				else
					echo "`date -u` :css directory creation for $theme failed."
				fi
				if [  $(mkdir "$media") ]
				then
					echo "`date -u` : Creation of custom_media directory failed for $theme." >> $logfile
				fi
				for var in ja fr en es de
				do
					if [ $(ln -s "$RFWEBUI"custom/strings.$var.js "$theme"/strings.$var.js) ]
					then
						echo "`date -u` :Failed to copy strings.$var.js files to '$2' theme's directory." >> $logfile
					fi
					if [ $(touch "$theme"/strings.$var.json) ]
					then
						echo "`date -u` :Failed to create strings.$var.json files to '$2' theme's directory." >> $logfile
					fi
					if [ $(echo "{}" >> "$theme"/strings.$var.json) ]
					then
						echo "`date -u` :Failed to create a valid strings.$var.json file in '$2' theme's directory." >> $logfile
					fi
				done
				if [ !  -e "$theme"/plugins.xml ]
				then
					if [ $(touch "$theme"/plugins.xml) ]
					then
						echo "`date -u` :Failed to create pluginlist file for '$2' theme." >> $logfile
					fi
				else
					echo "`date -u` :Pluginlist file already present in '$2' theme's directory.Should not have ideally happenned." >> $logfile
				fi
				;;
				esac
			else 
				echo "`date -u` :Creation of '$2' theme's directory failed" >> $logfile
			fi
		
;;	
esac
