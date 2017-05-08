<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>Collect user IP address</title>
</head>

<body background="rlaemb.JPG" leftmargin="20">

<?php
include("phpdir.inc");
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

<form method="POST" action=\"/="<?php echo $phpdir ?>">/">
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
echo '<input type="submit" value="ENTER" name="logon_chpwd"></p>';
echo '</form>';
?>
</body>
</html>