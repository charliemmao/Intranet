<html>
<?php
	include("connet_root_once.inc");
	$sql = "SELECT logouttime FROM logging.logout WHERE email_name='$email_name';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	if (mysql_num_rows($result)) {
		list($logouttime) = mysql_fetch_array($result);
	}

	$ip = getenv("remote_addr");
	$sql = "SELECT email_name as ename FROM logging.logsec WHERE ip='$ip';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($ename) = mysql_fetch_array($result);
	if (!$ename) {
		if ($logouttime) {
			$logouttime = "You have logout at $logouttime.";
		} else {
			$logouttime = "";
		}
		echo "$logouttime Please re-login to Intranet.";
		exit;
	}
?>
</html>