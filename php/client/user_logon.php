<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>Collect user IP address</title>
</head>

<body background="rlaemb.JPG" leftmargin="20">

<?php
include('str_decode_parse.inc');
include("phpdir.inc"); 
################################################
#	modification registration data
################################################
if ($modify) {
	echo'<h1 align="center">You try to modify your registration data.</h1><br>';
	echo'<h2 align="center">Please click <font color="#0000FF"> twice</font> &quot;<font color="#FF0000">Back</font>&quot; on your browser toolbar.</h2><br>';
	exit;
} 

################################################
#	password change confirmation
################################################
if ($pwdconfirmation) {
	if ($userpwd != $HTTP_POST_VARS["userpwd_old"]) {
		include('addressto.inc');
		echo "Please enter correct old password."		;
		exit;
	}

	$userpwd_new= $HTTP_POST_VARS["userpwd_new"];
	$userpwd_new_conf= $HTTP_POST_VARS["userpwd_new_conf"];
	if ($userpwd_new =="") {
		include('addressto.inc');
		echo "New password can't be empty.";
		exit;
	}
	
	if ($userpwd_new != $userpwd_new_conf) {
		include('addressto.inc');
		echo 'Please make sure you enter the same string in new password and ';
		echo 'confirmation boxes.';
		exit;
	}
	/*
	echo "Old password ".$userpwd .'<br>';
	echo "New password ".$userpwd_new.'<br>';
	echo "Conf password ".$userpwd_new_conf.'<br>';
	//*/
	
	$userpwd = $userpwd_new;
	$user	=	$email_name;
	include("changepassword.inc");

	include("connet_other_once.inc");
	include('err_msg.inc');
	mysql_close($contid);
	//echo 'connectted by user '.$user.' by using new password '.$userpwd .'<br><br>';
	
	include('addressto.inc');
	echo "<h2>Your password has been successfully changed.<br><br>";
	$qry	=	'email_name='.$email_name.'&logon_name='.$logon_name
				.'&title='.$title.'&first_name='.$first_name
				.'&middle_name='.$middle_name.'&last_name='.$last_name
				.'&userpwd='.$userpwd;
	$qry	=	base64_encode($qry);
	echo '<a href="/'.$phpdir.'/user_main.php?'.$qry.'">Go to Intranet</a></h2>';
	$action	=	"First time logon, change password";
	include('logging.inc');
	exit;
}

################################################
#	client logon with default password and require password change 
################################################
if ($logon_chpwd) {
	if ($user != $logon_name) {
		include('addressto.inc');
		msg("logon name");
		echo '<h2><br>Click "BACK" on your browser toolbar to return to the previous page.</h2>';
		exit;
	}
	
	while (list($key, $val) = each($HTTP_POST_VARS)) {
   		//echo "$key => $val<br>";
	}

	$userpwd = $HTTP_POST_VARS["userpwd"];
	include("connet_other_once.inc");
	mysql_close($contid);
	
	include('addressto.inc');
	echo "<h4>You have successfully logon to Intranet.<br>";
	echo "<br>Please change your password.</h4>";
?>
<form method="POST" action="<?php echo $PHP_SELF ?>">
  <p><font face="Courier New"><b>Your Logon Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <input type="text" name="user" size="20" value="<?php echo $logon_name ?>"></b></font></p>
  
  <p><font face="Courier New"><b>Your Old Password&nbsp;&nbsp;&nbsp;&nbsp;
  <input type="password" name="userpwd_old" size="20"></b></font></p>

  <p><font face="Courier New"><b>Your New Password&nbsp;&nbsp;&nbsp;&nbsp; <input type="password" name="userpwd_new" size="20"></b></font></p>

  <p><font face="Courier New"><b>Password Confirmation 
  <input type="password" name="userpwd_new_conf" size="20"></b></font></p>
<?php include('userstr.inc');
	echo '<input type="submit" value="CHANGE PASSWORD" name="pwdconfirmation">';
	echo '</form>';
	$action	=	"Logon";
	include('logging.inc');
	exit;
}

################################################
#	client logon with their own password
################################################
if ($logon) {
	$userpwd = $HTTP_POST_VARS["userpwd"];
	$user_frm	=	$HTTP_POST_VARS["user"];
	/*
	echo 'user name: '.$email_name.'<br>';
	echo 'logon name: '.$logon_name.'<br>';
	echo 'user form: '.$user_frm.'<br>';
	echo 'password: '.$userpwd.'<br>';
	//*/

	// check logon name
	if (strtolower($user_frm) != strtolower($logon_name)) {
		include('addressto.inc');
		echo 'Please enter your correct logon name.<br>';
		exit;
	}

	// check empty password
	if ($userpwd == "") {
		include('addressto.inc');
		echo 'Please enter your password.<br>';
		exit;
	}

	// match password
	include('match_pwd.inc');
	if ($mysql_username != $email_name) {	
		include('addressto.inc');
		echo 'Please enter correct password.<br><br><br>';
		//find DB administrator
		include("connet_root_once.inc");
		$qry = "SELECT email_name FROM logging.accesslevel WHERE priviledge='00';";
		include("find_one_val.inc");
		$ename = $out;
		$qry = "SELECT first_name FROM timesheet.employee WHERE email_name='$out';";
		include("find_one_val.inc");		
		echo "<b>If you forgot your password, please send email to "
		."<a href=\"mailto:$ename@rla.com.au\">$out</a> to reset your password.</b>";
		exit;
	}
	if ($mysql_username == $email_name) {
		include("connet_other_once.inc");
		mysql_close($contid);
		include('addressto.inc');
		echo "<h2>You have successfully logon to Intranet.<br><br>";
		include("userinfo.inc"); //$userinfo
		$qry	=	$userinfo;
		$qry	=	base64_encode($qry);
		echo '<a href="/'.$phpdir.'/user_main.php?'.$qry.'">Go to Intranet</a></h2>';
		$action	=	"logon";
		include('logging.inc');
		//delete and write logon record
		$login_logout = "login";
		include("user_login_write.inc");
		exit;
	}
}

################################################
#	preparation for logon
################################################
	include('addressto.inc');
	echo '<h2>Welcome to Intranet.</h2><br>';
	
	include('search_pwd.inc');
	if ($out == $email_name) {
		# user is required to change password
		//echo 'password = '.$pws_str.', user is '.$out.'<br>';
		echo '<p>Probably this is your first time logon. Your password is <b>'.$email_name.'reslab</b>. And it is entered for you. Please click "LOGON".<br>';
	}
?></p>

<form method="POST" action="<?php echo $PHP_SELF ?>">
  <p><font face="Courier New"><b>Your Logon Name&nbsp;&nbsp;&nbsp; 
  <input type="text" name="user" size="20" value="<?php echo $logon_name ?>"></b></font></p>
  <p><font face="Courier New"><b>Your Password&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
  
  <input type="password" name="userpwd" size="20" 
<?php
if ($out == $email_name) {
	echo ' value="'.$email_name.'reslab"></b></font></p>';
} else {
  echo '></b></font></p>';
}
?>
  <p align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<?php
include('userstr.inc');
if ($out == $email_name) {
  echo '<input type="submit" value="ENTER" name="logon_chpwd"></p>';
} else {
  echo '<input type="submit" value="ENTER" name="logon"></p>';
}
echo '</form>';
?>

<?php
function msg($str) {
	echo '<h1><font color="#FF0000">You entered wrong '.$str.'. Please try again.</font></h1><br>';
}
?>

</body>
</html>