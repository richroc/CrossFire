<?php
exec ("echo '' > /tmp/ngrep.log");
exec ("echo /www/CrossFire/ngrep.sh | at now");
exec ("echo /www/CrossFire/update-ngrep.sh | at now");
?>
<html><head>
<meta http-equiv="refresh" content="0; url=/CrossFire/">
</head><body bgcolor="black" text="white"><pre>
<?php
echo "Entropy Bunny Grepping";
?>
</pre></head></body>
