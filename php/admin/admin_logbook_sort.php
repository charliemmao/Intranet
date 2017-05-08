<html>

<head>
<title>View LOGBOOK</title>
</head>

<body bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG" leftmargin="50">

<?php
## control page access

include("admin_access.inc");
include('rla_functions.inc');

echo "<a id=top><h1 align=center>View LOGBOOK</h1></a>";

echo "<p align=center><a href=\"$PHP_SELF$admininfo\">[Refresh]</a>";
echo "<a href=\"adminctl_top.php$admininfo\">[Admin Main Page]</a>";
echo "<hr>";
	
##	process
echo "<form name=\"sortlogbook\" method=\"post\" action=$PHP_SELF>";
include("userstr.inc");
$b1 = "<font color=#0000ff>";
$b2 = "</font>";
echo "<table>";
echo "<tr><th align=left>Staff List</th><td><select name=staffname>";
	$sql = "SELECT email_name as ename, first_name as fname, last_name as lname
		FROM timesheet.employee 
		WHERE date_unemployed='0000-00-00' ORDER BY fname;";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	while (list($ename, $fname, $lname) = mysql_fetch_array($result)) {
	if ($ename) {
		if ($staffname == $ename) {
			echo "<option selected value=$ename>$fname $lname";
		} else {
			echo "<option value=$ename>$fname $lname";
		}
	}
	}
	echo "</option></select></td>";
	echo "<td rowspan=2><button type=submit name=sortlogbook>
		<font size=4 color=#0000ff>Find</font></button></td>";
	echo "</tr>";

echo "<tr><th align=left>Activity</th><td><select name=activity>";
	$i = 0;
	$actions[$i] = "Logon"; $i++;
	$actions[$i] = "select code"; $i++;
	$actions[$i] = "New Timesheet"; $i++;
	$actions[$i] = "Modify Timesheet"; $i++;
	$actions[$i] = "Inv item Add"; $i++;
	$actions[$i] = "Inv item owner"; $i++;
	$actions[$i] = "Inv item mod"; $i++;
	$actions[$i] = "All Activities"; $i++;
	$actions[$i] = "First time logon"; $i++;
	for ($j=0; $j<$i; $j++) {
		if ($activity == $actions[$j]) {
			echo "<option selected>".$actions[$j];
		} else {
			echo "<option>".$actions[$j];
		}
	}
	echo "</option></select></td></tr>";
	
echo "</form></table>";

if ($sortlogbook) {
	echo "<hr><h2>Search Result:</h2>";
	if ($activity == "All Activities") {
		$sql = "SELECT action, timestamp 
		FROM logging.access_rcd 
		WHERE email_name='$staffname' 
		ORDER BY timestamp DESC;";
	} else {
		$sql = "SELECT action, timestamp 
		FROM logging.access_rcd 
		WHERE (action LIKE '$activity%') and email_name='$staffname' 
		ORDER BY timestamp DESC;";
	}
	//echo $sql."<br>";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	if (!$no) {
		echo "<h3><font color=#ff0000>No result has been found for $staffname.</h3>";
		backtotop();
		exit;
	}
	echo "<h3>$no records have been found for $staffname.</h3>";
	echo "<table border=1>";
	echo "<tr><th>No</th><th>Time</th><th>Activity</th></tr>";
	$i = 1;
	while (list($action, $timestamp) = mysql_fetch_array($result)) {
		echo "<tr><td>$i</td><td>$timestamp</td><td>$action</td></tr>";
		$i++;
	}
	echo "</table><p>";
}
backtotop();
function backtotop(){
	echo "<br><hr><a href=#top>Back to top</a><a id=end></a><br><br><br>";
}

?>
</body>
