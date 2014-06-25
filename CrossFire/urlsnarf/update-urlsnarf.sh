#!/bin/sh
while true
do cat /www/CrossFire/logs/urlsnarf.log | awk {'print $1 $8'} | sed 's,http://, ,' | sed 's/.lan//' | sed 's%/.*$%%' | uniq > /www/CrossFire/logs/urlsnarf-clean.log
sleep 10
done
