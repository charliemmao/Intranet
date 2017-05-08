<html>

<?php
	//update: (a) delete previous record from DB "timesheet"
	$dbname0	=	'timesheet';
	$deleteentry_no	=	'no';
	include("connet_root_once.inc");
	include('for_ts_process_id_delete_rcd.inc');
	
	//update: (b) find records from DB "updatets" and write them to DB "timesheet"
	$sql	= "INSERT timesheet.timedata SELECT * from updatets.timedata where entry_no='$process_id';";
	include("connet_root_once.inc");
	$result = mysql_query($sql,$contid); 
	include('err_msg.inc');
	
	include("connet_root_once.inc");
	$sql	= "INSERT timesheet.researchrcd SELECT * from updatets.researchrcd where entry_no='$process_id';";
	$result = mysql_query($sql,$contid); 
	include('err_msg.inc');
	
	include("connet_root_once.inc");
	$sql	= "INSERT timesheet.leavercd SELECT * from updatets.leavercd where entry_no='$process_id.';";
	$result = mysql_query($sql,$contid); 
	include('err_msg.inc');
	
	//update: (d) delete record from DB "updatets"
	include("connet_root_once.inc");
	$dbname0	=	'updatets';
	$deleteentry_no	= 'yes';
	include('for_ts_process_id_delete_rcd.inc');

	//mysql_close();
?>
</html>
