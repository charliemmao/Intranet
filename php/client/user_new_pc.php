<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>Collect user IP address</title>
</head>
<body background="rlaemb.JPG" leftmargin="20">

<?php
include("connet_root_once.inc");
if ($changeip) {
	$computer_ip_addr = getenv("remote_addr");
	$sql = "UPDATE timesheet.employee SET computer_ip_addr='$computer_ip_addr' "
		."WHERE email_name='$email_name';";
	$result = mysql_query($sql);
	include("err_msg.inc");

	echo "<script language=\"javascript\">";
		echo "window.location=\"http://".getenv("server_name")."\";";
	echo "</script>";
	exit;
}
	echo "<form method=post action=\"$PHP_SELF\">";
	echo "<h1>Dear Intranet User<br></h1>";
	echo "<h3>Please select your name from the list then logon.</h3>";
	$sql = "SELECT email_name, first_name, middle_name, last_name FROM timesheet.employee ".
		"WHERE email_name!='heh' ORDER BY email_name;";
	
	$result = mysql_query($sql);
	include("err_msg.inc");
	echo "<b>My Name </b><select name=\"email_name\">";
	while (list($email_name, $first_name, $middle_name, $last_name) = mysql_fetch_array($result)) {
		if ($middle_name) {
			$str = "$first_name $middle_name $last_name";
		} else {
			$str = "$first_name $last_name";
		}
		echo "<option value=\"$email_name\">$str";
	}
	echo "</option></select>";
	echo "&nbsp;&nbsp;";
	echo "<input type=\"submit\" name=\"changeip\" value=\"SUBMIT\">";
	echo "</form>";
?>

</body>
