#!/usr/bin/bash

exec 2>&1 >> /var/log/wfcmd.log
echo `date` ": $0 $*"
echo "Show WF site"
if [ $# -lt 1 ]; then
		echo "Incorrect number of arguments!"
		exit 9
fi

echo "Show WF Site $1"
resp=`curl --silent  --request POST "http://127.0.0.1:8080/shsite/$1" --header "Content-Type: text/plain" --header "X-Via-NSCLI: true"`
echo " Curl Resp = $resp"

if [ "$resp" -ge 1 ]; then
		echo "Got valid status code!"
		exit $resp
fi
echo "Got invalid resp code"
exit 9
