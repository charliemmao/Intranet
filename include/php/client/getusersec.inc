<html>
<?php
	include("connet_root_once.inc");
	if (getenv('QUERY_STRING')) {
		$url	=	getenv('QUERY_STRING');
	} else {
		$dd = date("m-d");
		$ip = getenv("remote_addr");
		$sql = "SELECT email_name as ename 
        FROM timesheet.employee 
        WHERE computer_ip_addr='$ip'";
		$result = mysql_query($sql);
		include("err_msg.inc");
		list($ename) = mysql_fetch_array($result);

		$sql = "SELECT logsec as userstr, logtext as userinfo FROM logging.logsec 
			WHERE ip='$ip' and email_name='$ename'";
		//echo "sql = $sql <br>";
		$result = mysql_query($sql);
		include("err_msg.inc");
		list($userstr, $userinfo) = mysql_fetch_array($result);
		$url	=	base64_decode($userstr);
	}
	//echo "url = $url<br>sql = $sql";
	//exit;
	$url	=	strtok('?'.$url,'?');
	$url	=	strtok('?');
	parse_str($url);
/*
	//echo "$userstr<br><br>$userinfo<br><br>";
	echo "$email_name<br>$logon_name<br>$title<br>$first_name<br>$middle_name
		<br>$last_name<br>$userpwd<br>$priv<br>$logouttime<br>$patdir1
		<br>$patdir2<br>$feedbackfile<br>$rladoc<br>$rlapat<br>$loaddir 
		<br>$db<br>$pubtcc<br>$patsym<br>$no_in_col<BR>";
		exit;
//*/
?>

</html>