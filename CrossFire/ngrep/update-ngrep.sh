#!/bin/sh
while true
do
#echo '' > /www/CrossFire/ngrep-clean.log
cat /tmp/ngrep.log | grep -e "Host:" -e "Cookie:" >> /www/CrossFire/ngrep-clean.log
echo '' > /tmp/ngrep.log
sleep 10
done
