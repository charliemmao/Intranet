<html>
<?php
include('connet_other_once.inc');	//contid

$dbname0 = "timesheet";
$table_field_list[0] = "entry_no";
$table_field_list[1] = "timedata";
$table_field_list[2] = "leavercd";
$table_field_list[3] = "researchrcd";
$table_field_list[4] = "marketing";
$table_field_list[5] = "travel";

for ($tablei=0; $tablei<6; $tablei++) {
	$db_table = $dbname0.".".$table_field_list[$tablei];
	$sql = "DELETE FROM $db_table WHERE entry_no='$entry_no';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	if (!$result) {
		echo "Failed to delete record from $db_table with $entry_no.<br>"; 
	}
}
?>
</html>
