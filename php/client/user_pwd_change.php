<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>Collect user IP address</title>
</head>

<body background="rlaemb.JPG" leftmargin="20" topmargin="30">

<?php
include("phpdir.inc"); 
include('str_decode_parse.inc');
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

	include("userinfo.inc"); //$userinfo
	$qry	=	$userinfo;
	$qry	=	base64_encode($qry);
	
	include('addressto.inc');
	echo '<h2>Your password has been changed successfully.<br></h2><p><b><font size="4">To
insure you are not refused to access Database, you must select one of the
options below:</font></b></p>
<ul>';
	$str= '<a href="/'.$phpdir.'/user_main.php?'.$qry.'" target="_top">Back</a>';
  	echo '<li><h3>'.$str.' or </h3></li>';
  	
	$str	=	getenv('SERVER_NAME');
	$str	= '<a href="http://'.$str.'" target="_top">Re-LOGON</a>';
  	echo '<li><h3>'.$str.'</h3></li>';
echo '</ul><br>';
	
	exit;
}

################################################
#	prepare for client password change 
################################################
//include('addressto.inc');
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
	exit;

function msg($str) {
	echo '<h1><font color="#FF0000">You entered wrong '.$str.'. Please try again.</font></h1><br>';
}
?>

</body>
</html>