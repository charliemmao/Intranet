<html>
<?php
	include("connet_root_once.inc");
	$sql = "SELECT description as momainname FROM logging.sysmastertable WHERE item='Domain_Name';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($momainname) = mysql_fetch_array($result);
	
	$sql = "SELECT description as netipprefix FROM logging.sysmastertable WHERE item='Net_IP';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($netipprefix) = mysql_fetch_array($result);

	$sql = "SELECT description as defaultpwdprefix FROM logging.sysmastertable WHERE item='defaultpwd';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($defaultpwdprefix) = mysql_fetch_array($result);

	$adminyes = "no";
	$sql = "SELECT description as adminip FROM logging.sysmastertable WHERE item='Admin_IP';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$thisip = getenv("remote_addr");
	while (list($adminip) = mysql_fetch_array($result)) {
		if ($thisip == $adminip) {
			$adminyes = "yes";
		}
		//echo "Administrator IP: ".$adminip."<br>";
	}
	
	$sql = "SELECT description as adminename FROM logging.sysmastertable WHERE item='Admin_Name';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	while (list($adminename) = mysql_fetch_array($result)) {
		if ($adminename == $email_name) {
			$adminyes = "yes";
		}
		//echo "Administrator Name:".$adminename."<br>";
	}
	/*
	echo "Domain: ".$momainname."<br>";
	echo "Network IP Prefix: ".$netipprefix."<br>";
	echo "Is he/she administrator: ".$adminyes."<br>";
	//*/
?>
</html>