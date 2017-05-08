<html>

<head>
<title>Empty Timesheet DB</title>
</head>

<?php
include("connet_root_once.inc");

$sql = "SELECT description as emptydb
        FROM logging.sysmastertable 
        WHERE item='emptydb' ;";
$result = mysql_query($sql);
include("err_msg.inc");
list($emptydb) = mysql_fetch_array($result);
//echo "$sql<br>";

if ($setemptydbtoy && $emptydb == "n") {
	$sql = "UPDATE logging.sysmastertable 
		SET description='y'
   		WHERE item='emptydb';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	echo "$sql<br>";
	echo "<p><a href=\"".$PHP_SELF."\"><b>Remove all records from timesheet database</b></a>";
	exit;
}

if ($emptydb == "n") {
	echo "If you want to remove all records from timesheet db, please &nbsp;&nbsp;";
	echo "<form method=post>";
	echo "<button type=submit name=setemptydbtoy><b>SET Remove All Records To \"Y\"</b></button>";
	echo "</form>";
} elseif ($emptydb == "y") {
	$usedb = "timesheet";
	include("find_table_in_db.inc");
	//Table List: $tablelist[]
	for ($i=0; $i<count($tablelist); $i++) {
		$usetable = $tablelist[$i];
		$sql = "DELETE FROM $usedb.$usetable";
    	echo "$i: $sql<br>";
    	$result = mysql_query($sql);
    	include("err_msg.inc");
	}
	
	$sql = "UPDATE logging.sysmastertable 
		SET description='n'
   		WHERE item='emptydb';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	echo "<p>$sql<br>";
	echo "<p><a href=\"".$PHP_SELF."\"><b>Refresh this page.</b></a>";
} else {
	$sql = "INSERT INTO logging.sysmastertable SET id='null',item='emptydb',description='n'";
	$result = mysql_query($sql);
	include("err_msg.inc");
	echo "<p>$sql<br>";
	echo "<p><a href=\"".$PHP_SELF."\"><b>Refresh this page.</b></a>";
}
?>
</html>
