<html>
<?php

#######################################
$sql = "SELECT email_name, first_name, last_name FROM timesheet.employee 
	WHERE date_unemployed='0000-00-00' order by email_name;";
$result = mysql_query($sql);
include("err_msg.inc");
$noreguser = 0;
while (list($email_name, $first_name, $last_name) = mysql_fetch_array($result)) {
	$reguserlist[$noreguser][0] = $email_name;
	$reguserlist[$noreguser][1] = "$first_name, $last_name";
	$noreguser++;
}
#######################################
$sql = "SELECT DISTINCT User FROM mysql.user 
	where User!='root' and User!='anyone' and User!='webmaster'
	order by User;";
$result = mysql_query($sql);
include("err_msg.inc");
$mysqluser=0;
while (list($User) = mysql_fetch_array($result)) {
	$User = trim($User);
	if ($User) {
		$mysqluserlist[$mysqluser][0] = $User;
		for ($i=0; $i<$noreguser; $i++) {
			if ($User == $reguserlist[$i][0]) {
				$mysqluserlist[$mysqluser][1] = $reguserlist[$i][1];
				break;
			}
		}
		$mysqluser++;
	}
}
?>
</html>