<?php
exec ("echo '' > /www/CrossFire/logs/urlsnarf.log");
exec ("echo /www/CrossFire/urlsnarf/urlsnarf.sh | at now");
exec ("echo /www/CrossFire/urlsnarf/update-urlsnarf.sh | at now");
?>
<html><head>
<meta http-equiv="refresh" content="0; url=/CrossFire/">
</head><body bgcolor="black" text="white"><pre>
<?php
echo "Entropy Bunny Pouncing on URLs";
?>
</pre></head></body>
