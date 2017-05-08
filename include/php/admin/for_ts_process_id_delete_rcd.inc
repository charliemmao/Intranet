<html>
<?php
	//get info for this id record before to delete
	if ($dbname0	!=	'updatets') {
		$dbname0	=	'timesheet';
		include("tsalreadysent.inc");
	}	
	
	if ($out	==	'') {
		if ($process_id	!= '' ) {
			echo '<h2><font color=#ff0000>Timesheet with process ID of "'.$process_id.'" has not been found.<font></h2><br>';
		} elseif ($tsfor	!= '' && $yyyymmdd != '') {
			echo '<h2><font color=#ff0000>Timesheet with email name of "'.$tsfor.
			'" and date of "'.$yyyymmdd.'" has not been found.<font></h2><br>';
	}
		if ($dbname0	!=	'updatets') {
			exit;
		}
	}
	
	//delete all records with $process_id
	include("connet_root_once.inc");
	//step 1: delete record from "timesheet.entry_no"
	//brief_code, minutes	entry_no
	//if ($deleteentry_no	==	'yes') {
		$sql = "delete from $dbname0.entry_no where entry_no='".$process_id."';";
		//echo $sql.'<br>';
		$result = mysql_query($sql,$contid); 
		include('err_msg.inc');
	//}
	
	//step 2: delete record from "$dbname0.timedata"
	//brief_code, minutes	entry_no
	$sql = "delete from $dbname0.timedata where entry_no='".$process_id."';";
	//echo $sql.'<br>';
	$result = mysql_query($sql,$contid); 
	include('err_msg.inc');

	//step 3: delete record from "$dbname0.researchrcd"
	//brief_code, minutes	entry_no
	$sql = "delete from $dbname0.researchrcd where entry_no='".$process_id."';";
	$result = mysql_query($sql,$contid); 
	include('err_msg.inc');

	//step 4: delete record from "$dbname0.leavercd"
	//email_name	brief_code		startday	minutes	entry_no
	$sql = "delete from $dbname0.leavercd where entry_no='".$process_id."';";
	$result = mysql_query($sql,$contid); 
	include('err_msg.inc');

	if ($deleteentry_no =='') {
		echo $out;
	}
	mysql_close();
?>
</html>
