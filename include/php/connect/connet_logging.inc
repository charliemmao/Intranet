<html>
<?php
	/* table: myconid. columns: id, conn_no, yyyymmdd, */
	$time = date("Y-m-d h:i:s");
	$sqllog = "INSERT INTO timesheet.myconid values('NULL','$action_log','$time');";
	$result = mysql_query($sqllog ,$contid);
	//*
	$sqllog = "DELETE from timesheet.myconid;";
	$result = mysql_query($sqllog ,$contid);
	//*/
?>
</html>