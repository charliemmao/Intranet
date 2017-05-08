<html>

<head>
<title>Reset Password To Default</title>
</head>

<body background="../images/rlaemb.JPG">
<?php
include("admin_access.inc");
include('rla_functions.inc');

echo "<a id=top><h1 align=center><B><FONT size=7>Reset Password To Default</FONT></B> </h1></a>";

echo "<p align=center><a href=\"$PHP_SELF$admininfo\">[Refresh]</a>";
echo "<a href=\"adminctl_top.php$admininfo\"><font size=2>[Admin Main Page]</font></a>";
echo "<hr>";

if ($resetpwd) {	
##################################################
## change password
##################################################
	$email_name = $staffname;
	include('connet_root_once.inc');
	mysql_select_db("mysql");
	//check whether this person existed in DB: added on 4/11/2002
	$user	=	$email_name;
	$sql = "SELECT Password
        FROM mysql.user 
        WHERE User='$user';";
	$result	=	mysql_query($sql,$contid);
	include('err_msg.inc');
	list($Password) = mysql_fetch_array($result);
	if (!$Password) {
		echo $sql.' '.date("Y-m-d").'<br>';
		echo "$user doesn't exist in the mysql.user table.<br>";
		exit;
	}
	
	$userpwd = $user."reslab";
	$sql = 'update mysql.user set password=password("'.$userpwd.'") where user="'.$user.'";';
	$result	=	mysql_query($sql,$contid);
	include('err_msg.inc');
	$result	=	mysql_query("FLUSH PRIVILEGES",$contid);
	include('err_msg.inc');
	mysql_close($contid);
	echo $sql.' '.date("Y-m-d").'<br>';

##################################################
## send email message to confirm password
##################################################
	//bool mail (string to, string subject, string message, string [additional_headers]);
	$to 		= "$email_name@rla.com.au";
	$subject	=	"Reset Password";
	$message	=	"Hi, $email_name\n\n\nYour pawwsord has been reset to default."
	."\n\n\n\nRegards\n\n\nIntranet Team\n";
	$header	=	"From: admin@$SERVER_NAME\nReply-To: admin@$SERVER_NAME\n";
	//mail ($to, $subject, $message, $header);
	
	$from = "admin\@rla.com.au";
	$to = "$email_name\@rla.com.au";
	$cc = "";
	system("./_mail.pl \"$from\" \"$to\" \"$cc\" \"$subject\" \"$message\"",$var);

	echo "<h3 align=center>$email_name's password has been reset to $userpwd successfully and an email message also sent.</h3><hr>";
}
?>

<form method="post" action="<?php echo $PHP_SELF ?>">
	<p  align=center><table border=0>
	<tr><th>Staff Name</th>
	<?php	include("stafflist.inc"); ?> 
	<td><input type="submit" name="resetpwd" value="RESET PASSWORD"></td></tr></table></p>
</form>
	
 </body>
