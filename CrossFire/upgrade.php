<html>
<head>
<?php
##Don't touch this, it WILL brick your pineapple
##Wait for this to be integrated for auto OTA
##Start upgrade
if(isset($_GET[webDownload]) && trim(file_get_contents('webUpgrade.status')) == "idle"){
	exec("echo downloading > webUpgrade.status");
	exec("rm /tmp/upgrade.bin");
	exec("echo 'sh webUpgrade.sh' | at now");
}
##Reload to check for finished download
if(isset($_GET[webDownload]) && trim(file_get_contents('webUpgrade.status')) == "downloading"){
	echo '<meta http-equiv="refresh" content="5">';
}
##if finished 
if(isset($_GET[webDownload]) && trim(file_get_contents('webUpgrade.status')) == "doneDownloading"){
	echo '<meta http-equiv="refresh" content="0;url=?webUpgrade">';
}
?>
<title>CrossFire Dashboard</title>
<script  type="text/javascript" src="includes/jquery.min.js"></script>
</head>
<body>

<?php require('includes/ascii.php'); ?>
<?php require('includes/navbar.php'); ?>
<pre>
<?php
if(isset($_GET[webDownload])){
	echo '<center>Downloading...<br />Please be patient, this page will<br />reload automatically.</center>';
}
?>
<?php

if($_FILES[upgrade][error] > 0){
    $error = $_FILES[upgrade][error];
	echo "Error ($error), please check the file you specified.";
}
elseif(isset($_FILES[upgrade]) && $_FILES[upgrade][name] != "upgrade.bin"){
	echo "The upgrade file must be named upgrade.bin";
}
elseif(isset($_FILES[upgrade])){
	exec("rm /tmp/upgrade.bin");
	move_uploaded_file($_FILES[upgrade][tmp_name], "/tmp/".$_FILES[upgrade][name]);
if(exec("md5sum /tmp/upgrade.bin | grep -w ".$_POST[md5sum]) == ""){
	echo "Error, MD5Sum does not match!";
}else exec('sysupgrade -n /tmp/upgrade.bin');
}

?>
<div align=right>
<?php
if(isset($_GET[checkUpgrade])){
$remoteFile = explode("|", trim(file_get_contents("http://cloud.wifipineapple.com/mk4/downloads.php?currentVersion")));
$remoteMD5 = $remoteFile[1];
$remoteVersion = explode(".", $remoteFile[0]);
$localVersion = explode(".", file_get_contents("includes/fwversion"));

if($remoteVersion[0] > $localVersion[0]){
echo "Update ($remoteVersion[0].$remoteVersion[1].$remoteVersion[2]) found | <a href=\"http://cloud.wifipineapple.com/mk4/downloads.php?download\">Download</a>";
echo "<br />MD5: ".$remoteMD5."<br />";
}else if($remoteVersion[0] == $localVersion[0]){
//Go further
	if($remoteVersion[1] > $localVersion[1]){
		echo "Update ($remoteVersion[0].$remoteVersion[1].$remoteVersion[2]) found | <a href=\"http://cloud.wifipineapple.com/mk4/downloads.php?download\">Download</a>";
		echo "<br />MD5: ".$remoteMD5."<br />";
	}elseif($remoteVersion[1] == $localVersion[1]){
		//Go further
		if($remoteVersion[2] > $localVersion[2]){
			echo "Update ($remoteVersion[0].$remoteVersion[1].$remoteVersion[2]) found | <a href=\"http://cloud.wifipineapple.com/mk4/downloads.php?download\">Download</a>";
			echo "<br />MD5: ".$remoteMD5."<br />";
		}else echo "No upgrade found.";
	}else echo "No upgrade found.";
}else echo "No upgrade found.";
}
?>

Online Upgrade | <a href="<?php echo $_SERVER[PHP_SELF] ?>?checkUpgrade">Check</a>

<disabled>Warning:</disabled> This will establish a 
connection to wifipineapple.com
</div>
<center>The current firmware version is: <?php include('includes/fwversion'); ?>
Browse for an upgrade.bin and click upgrade:

<form action="<?php $_SERVER[php_self] ?>" method="post" enctype="multipart/form-data">
<input type="file" value="upgrade.bin" name="upgrade" disabled="disabled" id="upgrade" /><input type="submit" disabled="disabled" onClick="alert('Please note: If the upload is successful, the page will time out and give you an error. This is expected. Please wait patiently while the pineapple is working. It will reboot and be upgraded afterwards.');" value="Upgrade" name="Upgrade">
MD5Sum: <input type="text" disabled="disabled" name="md5sum">
</form>
<font color='orange' >Disabled due to the possibility of bricking your device. <br /></font>

Note: The upgrade can take a few minutes and will reboot the device. 
Please wait patiently.

<pre>

<disabled>Warning:</disabled>
Power cycle the WiFi Pineapple and disable Karma, SSH, 3G and other non-essential services before flashing.
Under most circumstances a firmware flash is perfectly safe, however please be advised:
 - Bootloader recovery options can only be accessed via serial. 
 - Do not flash firmware while running on battery power.
 - Do not flash firmware if memory is low.
 - Do not flash firmware via WiFi.
<!-- Do not feed Pineapple after midnight -->

</center><pre>
<b>Memory</b>
<?php
$cmd = "free";
exec ($cmd, $output);
foreach($output as $outputline) {
echo ("$outputline\n");}
?>

</body>
</html>
