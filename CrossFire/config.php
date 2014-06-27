<html>
<head>
<title>CrossFire Dashboard</title>
<script  type="text/javascript" src="includes/jquery.min.js"></script>
</head>
<body>

<?php require('includes/ascii.php'); ?>
<?php require('includes/navbar.php'); ?>

<pre>

<?php
	$filename =$_POST['filename'];
	$newdata = $_POST['newdata'];

	if ($newdata != "") { $newdata = ereg_replace(13,  "", $newdata);
		$fw = fopen($filename, 'w') or die('Could not open file!');
		$fb = fwrite($fw,stripslashes($newdata)) or die('Could not write to file');
		fclose($fw);
		echo "Updated " . $filename . "<br /><br />";
	}
?>

<?php
	if(isset($_POST[newSSID])){
		if(isset($_POST[newSSIDPersistent])){
			exec("echo \"$(sed 's/option ssid.*/option ssid ".$_POST[newSSID]."/g' /etc/config/wireless)\" > /etc/config/wireless");
			echo "Changes to SSID have been made persistently<br />";
		}
	exec("hostapd_cli -p /var/run/hostapd-phy0 karma_change_ssid \"".$_POST[newSSID]."\"");
	echo "Karma SSID changed to \"".$_POST[newSSID]."\" successfully <br /><br />";
	
	}
	
	if(isset($_POST[ssidBW])){
		if(isset($_POST[addSSID])){
			exec("hostapd_cli -p /var/run/hostapd-phy0 karma_add_ssid ".$_POST[ssidBW]);
			echo "Added \"".$_POST[ssidBW]."\" to the list. <br /><br />";
		}
	        if(isset($_POST[removeSSID])){
	                exec("hostapd_cli -p /var/run/hostapd-phy0 karma_del_ssid ".$_POST[ssidBW]);
	                echo "Deleted \"".$_POST[ssidBW]."\" from the list. <br /><br />";
	        }
	
	}
	
	if(isset($_POST[macBW])){
		if(isset($_POST[addMAC])){
			exec("hostapd_cli -p /var/run/hostapd-phy0 karma_add_black_mac  ".$_POST[macBW]);
			echo "Added \"".$_POST[macBW]."\" to the list. <br /><br />";
		}
	        if(isset($_POST[removeMAC])){
	                exec("hostapd_cli -p /var/run/hostapd-phy0 karma_add_white_mac ".$_POST[macBW]);
	                echo "Deleted \"".$_POST[macBW]."\" from the list. <br /><br />";
	        }
	
	}
?>

<?php
	if(isset($_GET[resetButton])){
	
		if($_GET[resetButton] == "enable"){
			echo "Reset button enabled.";
			exec("sh config/resetButton.sh enable");
			exec("echo enabled > config/resetButtonStatus");
		}
		if($_GET[resetButton] == "disable"){
			echo "Reset button disabled.";
			exec("sh config/resetButton.sh disable");
			exec("echo disabled > config/resetButtonStatus");
		}
	}
	$resetButton = trim(file_get_contents("config/resetButtonStatus"));
?>
<table border="0" width="100%">
<tr><td width="700">
<td valign="top" align="left">
Button Configuration.
</tr></td>
<tr><td>
Reset button 
<?php
	if($resetButton == "enabled"){
		echo "<enabled>enabled</enabled>. | <a href=\"$_SERVER[PHP_SELF]?resetButton=disable\"> Disable</a>";
	}else{
		echo "<disabled>disabled</disabled>. | <a href=\"$_SERVER[PHP_SELF]?resetButton=enable\">Enable</a>";
		}
?>
<br /><br />
<a name='wpsScript'><b>Custom script executed on WPS button press</b></a>
	<form action='<?php $_SERVER[php_self] ?>'  method= 'post' >
	<textarea name='newdata' class="configBox"><?php
            $filename = "/www/CrossFire/config/wpsScript.sh";
            $fh = fopen($filename, "r") or die("Could not open file!");
            $data = fread($fh, filesize($filename)) or die("Could not read file!");
            fclose($fh);
            echo $data;
        ?>
	</textarea>
	<input type='hidden' name='filename' value='/www/CrossFire/config/wpsScript.sh'>
	<br>
    <input type='submit' value='Update WPS script'>
	</form>

</tr></td>

<tr><td>
<tr><td width="700">
<td valign="top" align="left">
Karma configuration.
</tr></td>
<tr><td>
    <b>Change Karma SSID</b><br />
        <form action='<?php echo $_SERVER[php_self] ?>' method= 'post' >
        <input type="text" name="newSSID" value="New SSID" onFocus="if(this.value == 'New SSID') {this.value = '';}" onBlur="if (this.value == '') {this.value = 'New SSID';}" class="short">
        <br>Persistent?:<input type="checkbox" name="newSSIDPersistent">
        <br><input type='submit' value='Change SSID'>
    </form>
</tr></td>
<tr><td>

<b>SSID Black and White listing</b><br>
<?php
$BWMode = exec("hostapd_cli -p /var/run/hostapd-phy0 karma_get_black_white");
$changeLink = "<a href='karma/changeBW.php'>change</a>";
?>
<font color='lime' size='2'> Currently in <?php echo $BWMode ?> mode | <font color='red'><?php echo $changeLink ?></font></font><br>
<form action='<?php echo $_SERVER[php_self] ?>' method= 'post' >
<input type="text" name="ssidBW" value="SSID" onFocus="if(this.value == 'SSID') {this.value = '';}" onBlur="if (this.value == '') {this.value = 'SSID';}" class='short'>
<br><input type='submit' name='addSSID' value='Add to List'><input type='submit' name='removeSSID' value='Remove from List'>
</form>
</tr></td>
<tr><td>

<b>Client Black listing</b><br>
<form action='<?php echo $_SERVER[php_self] ?>' method= 'post' >
<input type="text" name="macBW" value="MAC" onFocus="if(this.value == 'MAC') {this.value = '';}" onBlur="if (this.value == '') {this.value = 'MAC';}" class='short'>
<br><input type='submit' name='addMAC' value='Add to List'><input type='submit' name='removeMAC' value='Remove from List'>
</form>
</td></tr>
<tr><td>

<?php
  $filename = "/etc/config/wireless";
  $fh = fopen($filename, "r") or die("Could not open file!");
  $data = fread($fh, filesize($filename)) or die("Could not read file!");
  fclose($fh);
 echo "<b>Wireless</b>
<form action='$_SERVER[php_self]' method= 'post' >
<textarea name='newdata' cols='80' rows='20' style='background-color:black; color:white; border-style:dashed;'>$data</textarea>
<input type='hidden' name='filename' value='/etc/config/wireless'>
<br><input type='submit' value='Update Wireless'>
</form>";
?>
</td><td valign="top" align="left">
Wireless configuration for non-karma mode. 
</td></tr>
<tr><td>

<?php
$filename = "/etc/config/network";
  $fh = fopen($filename, "r") or die("Could not open file!");
  $data = fread($fh, filesize($filename)) or die("Could not read file!");
  fclose($fh);
 echo "<b>Network</b>
<form action='$_SERVER[php_self]' method= 'post' >
<textarea name='newdata' cols='80' rows='20' style='background-color:black; color:white; border-style:dashed;'>$data</textarea>
<input type='hidden' name='filename' value='/etc/config/network'>
<br><input type='submit' value='Update Network'>
</form>";
?>
</td><td valign="top" align="left">
LAN Configurations. ipaddr specifies the device IP Address while the gateway specifies the IP address from which Internet access can be obtained. DNS specifies a DNS server necessary for name resolution.
</td></tr>
<tr><td>

<?php
$filename = "/etc/config/dhcp";
  $fh = fopen($filename, "r") or die("Could not open file!");
  $data = fread($fh, filesize($filename)) or die("Could not read file!");
  fclose($fh);
 echo "<b>DHCP</b>
<form action='$_SERVER[php_self]' method= 'post' >
<textarea name='newdata' cols='80' rows='20' style='background-color:black; color:white; border-style:dashed;'>$data</textarea>
<input type='hidden' name='filename' value='/etc/config/dhcp'>
<br><input type='submit' value='Update DHCP'>
</form>";
 ?>

</td><td valign="top" align="left"> 
Dynamic Host Configuration Protocol. Gives out IP and DNS information to connecting clients. dhcp_option #3 specifies the IP address of the gateway from which Internet access can be obtained. #6 specifies the DNS servers from which names may be resolved. 
</td></tr>
<tr><td>

<?php
$filename = "/www/CrossFire/ngrep/ngrep.sh";
  $fh = fopen($filename, "r") or die("Could not open file!");
  $data = fread($fh, filesize($filename)) or die("Could not read file!");
  fclose($fh);
 echo "<a name='ngrep'><b>Ngrep</b>
<form action='$_SERVER[php_self]' method= 'post' >
<textarea name='newdata' cols='80' rows='20' style='background-color:black; color:white; border-style:dashed;'>$data</textarea>
<input type='hidden' name='filename' value='/www/CrossFire/ngrep/ngrep.sh'>
<br><input type='submit' value='Update Ngrep'>
</form>";
 ?>

</td><td valign="top" align="left">
ngrep configuration. Like grep, but for the network.
</td></tr>
<tr><td>


<a name='spoofhost'><b>DNS Spoof Host</b></a>
    <form action='<?php $_SERVER[php_self] ?>' method= 'post' >
    	<textarea name='newdata' class='configBox'><?php
			$filename = "/www/CrossFire/config/spoofhost";
			$fh = fopen($filename, "r") or die("Could not open file!");
			$data = fread($fh, filesize($filename)) or die("Could not read file!");
			fclose($fh);
			echo $data;
		?>
		</textarea>
    	<input type='hidden' name='filename' value='/www/CrossFire/config/spoofhost'>
    	<br>
    	<input type='submit' value='Update Spoofhost'>
</form>

</td><td valign="top" align="left">
Spoofhost file used by DNSSPoof. Specifies new destination IP for source Domain. May contain wildcards such as *.example.com.
</td></tr>
<tr><td>

<a name='spoofhost'><b>Landing Page (phishing)</b>
    <form action='<?php $_SERVER[php_self] ?>' method= 'post' >
    	<textarea class='configBox'><?php
			$filename = "/www/index.php";
			$fh = fopen($filename, "r") or die("Could not open file!");
			$data = fread($fh, filesize($filename)) or die("Could not read file!");
			fclose($fh);
			echo $data;
		?></textarea>
    	<input type='hidden' name='filename' value='/www/index.php'>
    	<br>
        <input type='submit' value='Update Landing Page'>
</form>

</td><td valign="top" align="left">
Root landing page for devices web server. Can be configured as captive portal or phishing page using Spoofhost. PHP allowed.
</td></tr></table>



</pre>
</body>
</html>
