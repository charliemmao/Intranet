<html>
<?php
include("connet_root_once.inc");
include("find_admin_ip.inc");
include("phpdir.inc");

if ($email_name != "$adminname"."0") {
	if ($login_logout == "login") {
		$sql = "DELETE FROM logging.logout WHERE email_name='$email_name';";
		$result = mysql_query($sql);
		include("err_msg.inc");
		
		$logintime = date("F d Y H:i");
		$lastaccess= date("Y-m-d H:i:s");
		
		//email_name, logintime, lastaccess, logouttime
		$sql = "INSERT INTO logging.logout SET email_name='$email_name', 
			logintime='$logintime', lastaccess='$lastaccess';";
		if ($userip == $iptocheck) {
			echo "Before $sql<br><br>";
		}
		$result = mysql_query($sql);
		include("err_msg.inc");
		if ($userip == $iptocheck) {
			echo "After $sql<br><br>";
		}
		if ($email_name == "stp") {
			//echo "$sql<br>";
			//exit;
		}
	} elseif ($login_logout == "logout") {
		$logouttime = date("F d Y H:i");
		//$logouttime = date("l, F d Y H:i");
		$sql = "UPDATE logging.logout SET logouttime='$logouttime' WHERE email_name='$email_name';";
		$result = mysql_query($sql);
		include("err_msg.inc");
		echo "<h2>Hi, $first_name</h1><br><h2>You have successfully logout from Intranet.</h2>";
		echo "<br><br><b>Regards<br><br>Intranet Administrator</b><br>";
		echo "$logouttime<br>";
		flush();
		
		$ip = getenv("remote_addr");
		$sql = "DELETE FROM logging.logsec WHERE ip='$ip';";
		$result = mysql_query($sql);
		include("err_msg.inc");

		sleep(5);
		echo "<script language=\"javascript\">";
			//echo "window.location=\"http://".getenv("server_name")."/$phpdir/user_main.php\";";
			echo "window.location=\"http://".getenv("server_name")."\"";
		echo "</script>";
		//echo "<br><h2>You can <a href=http://".getenv("server_name").">LOGON</a> again.</h2>";
	}
}

if ($login_logout == "login") {
	####### record client operating system
	$sql = "SELECT email_name as ename FROM logging.OStable WHERE email_name='$email_name';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($ename) = mysql_fetch_array($result);
	$date = date("m-d H:i");
	/*	It doesnot work
	include("find_admin_ip.inc");
	if (getenv("remote_addr") == $adminip) {
		$winos="";
		echo "<script language=\"JAVASCRIPT\">\n";
		echo "winos = navigator.userAgent;\n";
		echo "</script>";
		echo "winos=$winos<br>";
		exit;
	}
	//*/

	if ($winos) {
		$tmp = explode(";", $winos);
		//echo "$winos<br>";
		$explore = trim($tmp[1]);
		$winos= trim($tmp[2]);
		if (trim($tmp[3])) {
			$winos = $winos."; ".trim($tmp[3]);
		}
		$winos = ereg_replace(")","",$winos);
		//echo "$winos<br>$explore<br>";
	}
	if ($ename) {
		$sql = "UPDATE logging.OStable SET winos='$winos', explore='$explore', date='$date' WHERE email_name='$email_name';";
	} else {
		$sql = "INSERT INTO logging.OStable VALUES('$email_name', '$winos', '$explore', '$date');";
	}
		if ($userip == $iptocheck) {
			echo "Before $sql<br><br>";
		}
	$result = mysql_query($sql);
	$filelog = __FILE__;
	$filelog = __LINE__;
	include("err_msg.inc");
		if ($userip == $iptocheck) {
			echo "After $sql<br><br>";
		}
	
	$ip = getenv("remote_addr");
	$sql = "SELECT email_name as ename FROM logging.logsec WHERE ip='$ip';";
	if ($userip == $iptocheck) {
		echo "Before $sql<br><br>";
	}
	$result = mysql_query($sql);
		if ($userip == $iptocheck) {
			echo "After $sql<br><br>";
			$logtext = "";
		}
	include("err_msg.inc");
	list($ename) = mysql_fetch_array($result);
	$logsec = $userstr;
	$logtext = $userinfo;
	
	if ($userip == $iptocheck) {
			$logtext = "";
	}
	
	$sql = "DELETE FROM logging.logsec 
        WHERE email_name='$email_name';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	//echo "$sql";
	//exit;
	$sql = "INSERT INTO logging.logsec SET email_name='$email_name', ip='$ip', 
			logsec='$logsec', logtext='$logtext', date='$date';";
	//echo $sql."<br>";
	if ($userip == $iptocheck) {
		echo "Before $sql<br><br>";
	}
	$result = mysql_query($sql);
	include("err_msg.inc");
	if ($userip == $iptocheck) {
		echo "After $sql<br><br>";
	}
	//exit;
}
?>
</html>