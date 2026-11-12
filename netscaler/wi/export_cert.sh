#!/usr/bin/bash

# Copyright 2010-2016 Citrix Systems, Inc. All Rights Reserved
if [ $# -ne 1 ]; then
echo "Usage: $0 <certificatePath>"
echo "E.g.: $0 /nsconfig/ssl/ns-root.cert"
exit 1
fi

CERTFILE="$1"
STOREPASS=changeit
LOG=/var/log/wfcmd.log
echo `date` ": $0 $*" | tee -a $LOG
echo "export_cert.sh" | tee -a $LOG
if [ ! -d /var/wi ]; then
echo "Web interface not installed" | tee -a $LOG
exit 7
fi
cd /var/wi

JAVA_HOME=`grep JRE_HOME= bin/start_tomcat.sh | cut -f 2 -d =`
if [ -d "$JAVA_HOME/jre" ]; then
JAVA_HOME="$JAVA_HOME/jre"
fi
if [ ! -f "$JAVA_HOME/bin/keytool" -o ! -f "$JAVA_HOME/lib/security/cacerts" ]; then
echo "keytool or keystore not found @ $JAVA_HOME : install web interface first" | tee -a $LOG
exit 7
fi
if [ ! -f "$CERTFILE" ]; then
echo "Certificate file $CERTFILE not found" | tee -a $LOG
exit 14
fi

$JAVA_HOME/bin/keytool -list -alias $CERTFILE -keystore $JAVA_HOME/lib/security/cacerts -storepass $STOREPASS 2>&1 > /dev/null

if [ $? -eq 0 ]; then
echo "Certificate $CERTFILE already exists in keystore" | tee -a $LOG
exit 15
fi

$JAVA_HOME/bin/keytool -importcert -noprompt -alias $CERTFILE -file $CERTFILE -keystore $JAVA_HOME/lib/security/cacerts -storepass $STOREPASS | tee -a $LOG

#If WebFront is installed, send a request to inform WF to reload its SSLContext
if [ -e /var/wi/bin/webfront.war ]; then
curl --silent  --request POST "http://127.0.0.1:8080/reloadCerts" --header "Content-Type: text/plain" --header "X-Via-NSCLI: true" | tee -a $LOG
fi
