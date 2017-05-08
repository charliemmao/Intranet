<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>Collect user IP address</title>
</head>
<script language=javascript>
function firstele() {
	document.logonform.userpwd.focus(); 
	document.logonform.userpwd.select();
	document.logonform.winos.value= navigator.userAgent;
}
</script>

<body onLoad="firstele();" background="rlaemb.JPG" leftmargin="20">

<?php
include("phpdir.inc");
################################################
#	preparation for logon
################################################
	if ($priv != $privstr) {
		echo "<h1>Dear $first_name </h1><br>";
		echo "<h2>Welcome to Intranet.</h2><br>";
	} else {
		include('addressto.inc');
	}
	include('search_pwd.inc');
	if ($out == $email_name) {
		# user is required to change password
		//echo 'password = '.$pws_str.', user is '.$out.'<br>';
		echo '<p>Probably this is your first time logon. Your password is <b>'.$email_name.'reslab</b>. And it is entered for you. Please click "LOGON".<br>';
	}
	if ($priv != $privstr) {
		$page = "/$phpdir/user_logon.php";
		if ($email_name == "webmaster") {
			//echo getenv('SERVER_NAME')."<br>";
			$page = "http://".getenv('SERVER_NAME')."/$phpdir/user_logon.php";
		}
		echo "<form name=logonform method=\"POST\" action=\"$page\">";
	} else {
		echo "<form name=logonform method=\"POST\" action=\"$PHP_SELF\">";
	}
   echo "<input type=hidden name=winos>";
   echo "<input type=hidden name=file value=\"".__FILE__."\">";
   echo "<input type=hidden name=curip value=\"$curip\">";
   
	echo "<table border=0>";
  	echo "<tr><td><font face=\"Courier New\"><b>Your Logon Name</b></font></td>"; 
  	echo "<td><input type=\"text\" name=\"user\" size=\"20\" value=\"$logon_name\"></td></tr>";
  	echo "<tr><td><font face=\"Courier New\"><b>Your Password</b></font></td><td>"; 
  	echo "<input type=\"password\" name=\"userpwd\" size=\"20\"";
	if ($out == $email_name) {
		echo ' value="'.$email_name.'reslab"></td></tr>';
	} else {
  		echo '></td></tr>';
	}
	echo "<tr><td>";
	include('userstr.inc');
	echo "</td></tr><tr><td colspan=2>&nbsp;</td></tr>";
	echo "<tr><td colspan=2 align=center>";
	if ($out == $email_name) {
  		echo '<input type="submit" value="LOGON" name="logon_chpwd"></p>';
	} else {
  		echo '<input type="submit" value="LOGON" name="logon"></p>';
	}
	echo '</td></tr></table></form><p>';
		include("connet_root_once.inc");
		$sql = "SELECT logintime, lastaccess FROM logging.logout WHERE email_name='$email_name';";
		$result = mysql_query($sql);
		include("err_msg.inc");
		if (mysql_num_rows($result)) {
			list($logintime, $lastaccess) = mysql_fetch_array($result);
			if ($lastaccess) {
				$t1 = substr($lastaccess, 0, 10);
				$lastaccess = ereg_replace(" ", "", $lastaccess);
				$lastaccess = ereg_replace("-", "", $lastaccess);
				$lastaccess = ereg_replace(":", "", $lastaccess);
				$lastaccess = substr($lastaccess, 0, 8);
				$tmp = date("l", $lastaccess);
				//echo "(Last time accessed on $tmp, $t1.)<br>";
				echo "(Last time accessed on $t1.)<br>";
			}
		}
		mysql_close();
?>
</body>
</html>