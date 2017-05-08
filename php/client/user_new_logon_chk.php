<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title></title>
</head>

<body bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="/images/rlaemb.JPG">
<?php
include("phpdir.inc"); 

	$userip = getenv("remote_addr");
	include("find_admin_ip.inc");
	$iptocheck = $adminip."00";
	include('str_decode_parse.inc');

	$userpwd = $clientpwd;
	$defaultdb = $db[0];
	if ($dialin == 1) {
		$email_name = $dialname;
		include("connet_root_once.inc");
		$sql = "SELECT priviledge as priv FROM logging.accesslevel WHERE email_name='$email_name';";
		$result = mysql_query($sql);
		include('err_msg.inc');
		$priv = "";
		if (mysql_num_rows($result)) {
			list($priv) = mysql_fetch_array($result);
		}

		$sql = "SELECT logon_name, title, first_name, middle_name, last_name, computer_ip_addr 
			FROM timesheet.employee WHERE email_name='$email_name';";
		$result = mysql_query($sql);
		include('err_msg.inc');
		if (mysql_num_rows($result)) {
			list($logon_name, $title, $first_name, $middle_name, $last_name, $computer_ip_addr) = mysql_fetch_array($result);
		}
		//echo $email_name." = email_name<br>";
		//echo $clientname." = logon name<br>";
		//echo $defaultdb." = db<br>";
		//echo $clientpwd." = clientpwd<br>";
		//echo $clientpwdc." = clientpwdc<br>";
		//exit;
	}
	if ($pwderr == 3) {
		echo "<h1>Hi, $first_name</h1>";
		//echo 'Please enter correct password.<br><br><br>';
		//find DB administrator
		include("connet_root_once.inc");
		$qry = "SELECT email_name FROM logging.accesslevel WHERE priviledge='00';";
		include("find_one_val.inc");
		$ename = $out;
		$qry = "SELECT first_name FROM timesheet.employee WHERE email_name='$out';";
		include("find_one_val.inc");		
		echo "<b>If you forgot your password, please send email to "
		."<a href=\"mailto:$ename@rla.com.au\">$out</a> to reset your password.</b>";
		echo "<br><br><br><br><b>Best Regards<br><br><br>Intranet Administrator</b><br>";
		exit;
	}
## whether password need to be changed
	if ($oldpwd) {
		//echo $oldpwd." = oldpwd<br>";
		## change passpord
		$user	=	$email_name;
		include('changepassword.inc');
		include("user_pwd_ch_msg.inc");
	}
	
## client logon to Intranet
	include("connet_root_once.inc");
	$sql = "select user from mysql.user where Password = PASSWORD('$clientpwd');";
	//echo "$sql<br>";
	
	if ($userip == $iptocheck) {
		//echo 'Password sql<br>';
	}
	$result = mysql_query($sql,$contid); 
	include('err_msg.inc');
	list($user) = mysql_fetch_array($result);
	if ($user != "" && $email_name == "webmaster") {
		$user = $email_name;
	}
	//debug_logon($user, $email_name);
	mysql_close();
	
	//echo "$user != $email_name";

	if ($user != $email_name){
		//wrong password
		$pwderr++;
		include("user_new_logon.inc");
		exit;
	}
	include("connet_other_once.inc");

## write logon time to db
	$login_logout = "login";
	include("userinfo.inc"); //$userinfo
	
	$userstr	=	base64_encode($userinfo);
	
	if ($userip == $iptocheck) {
		echo 'Before user_login_write.inc<br>';
	}
	include("user_login_write.inc");
		
	$action	=	"logon";
	if ($userip == $iptocheck) {
		echo 'Before logging.inc<br>';
	}
	include('logging.inc');
	if ($userip == $iptocheck) {
		echo 'After logging.inc<br>';
	}
	
	if ($priv == "00") {
		//echo $defaultdb." = db<br>";
		//echo $userinfo."<br>";
		//exit;
		//echo $defaultdb." = defaultdb<br>";
	}
	
## open client main page
	include("find_domain.inc");	
	//echo "$userinfo<br>";
	/*
	$userinfo="&email_name=$email_name";
	//echo $userinfo." 0<br>";
	$userstr	=	$userinfo;
	$userstr	=	base64_encode($userinfo);
	if ($userip == $iptocheck) {
		echo 'Before opening user_main.php<br>';
		//exit;
	}
	*/

	echo "<script language=\"javascript\">";
		//echo "window.location=\"http://".getenv("server_name")."/$phpdir/user_main.php?$userstr\";";
		//echo "window.location=\"/$phpdir/user_main.php?$userstr\";";

	if ( $rlaserver == $thisserver ) {
		echo "window.location=\"/$phpdir/user_main.php\";";
	} else {
		echo "window.location=\"/$phpdir/user_main_tsht.php\";";
	}

	echo "</script>";

function debug_logon($str, $email_name) {
	if ($email_name == "webmaster") {
		echo $str." debug<br>";
		exit;
	}
}
?>
</html>
