<html>
<body background="rlaemb.JPG">
<?php
	//list database
	include("connet_root.inc");
	$resultdb = mysql_list_dbs();
	for ($i=0; $i < mysql_num_rows($resultdb); $i++) {
		$db0 = mysql_tablename($resultdb, $i);
		//echo $db0."<br>";
		$resulttab = mysql_list_tables($db0, $contid);
		for ($j=0; $j < mysql_num_rows($resulttab); $j++) {
			$table0 = mysql_tablename($resulttab, $j);
			//echo $table0."<br>";
			$resultfld = mysql_list_fields($db0, $table0, $contid);
			for ($k=0; $k < mysql_num_rows($resultfld); $k++) {
				$field0 = mysql_tablename ($resultfld, $k);
				$k++;
				$sql = "INSERT INTO timesheet.dbtabfldlable VALUES('$db0', '$table0', '$field0', '$lable0');";
				echo $sql."<br>";				
			}
    		$j++;
		}
    	$i++;
	}
?>
<!--
$sql = "INSERT INTO timesheet.dbtabfldlable SET
$sql = "INSERT INTO timesheet.dbtabfldlable VALUES('$db0', '$table0', '$field0', 
'$lable0');";;end<BR><BR>$sql = "INSERT INTO timesheet.dbtabfldlable SET 
"."db0='$db0', table0='$table0', field0='$field0', 
lable0='$lable0';";end<BR><BR>$sql = "UPDATE timesheet.dbtabfldlable SET 
"."db0='$db0', table0='$table0', field0='$field0', lable0='$lable0' WHERE LIMIT 
#;";end<BR><BR>$sql = "DELETE FROM timesheet.dbtabfldlable WHERE "."db0='$db0', 
table0='$table0', field0='$field0', lable0='$lable0';";end<BR><BR>$sql = "SELECT 
db0, table0, field0, lable0, FROM timesheet.dbtabfldlable WHERE "."db0='$db0', 
table0='$table0', field0='$field0', lable0='$lable0';";end<BR><BR>
//*/
--!>
</html>
