<html>

<head>
<title>Update Timesheet</title>
</head>

<?php
$url	=	$frm_str;
$url	=	strtok('?'.$url,'?');
$url	=	strtok('?');	
parse_str($url);
$email_name	=	$tsheet[1][0];
$yyyymmdd	=	$tsheet[1][1];
/*
/echo $frm_str.'<br>';
echo 'tsid_todelete= '.$tsid_todelete.'<br>';
echo 'tsename_todelete= '.$tsename_todelete.'<br>';
echo 'Noempty= '.$Noempty.'<br>';
echo 'email_name= '.$email_name.'<br>';
echo 'yyyymmdd= '.$yyyymmdd.'<br>';
echo "process_id= ".$process_id.'<br>';

for ($i=2; $i<$Noempty; $i++) {
   	echo $i.': '.$tsheet[0][$i].'; '.$tsheet[1][$i].'; '.$tsheet[2][$i].'; '
   	.$tsheet[3][$i].'; '.$tsheet[4][$i].'<br>';
}
//*/

$dbname0	=	"updatets";
echo "<h1>$title $last_name</h1>";

//	step 0: update entry exist?
include('connet_root_once.inc');
$qry= "select entry_no from $dbname0.entry_no where email_name='".$email_name."' and yyyymmdd='".$yyyymmdd."';";
include('find_one_val.inc');
while ($out	!= '') {
	echo "<b><font color=#ff0000>You have sent update for the week ending on ".$yyyymmdd.
		". And that update has not yet processed.</font></b><br>";
	exit;
}

$noerror	=	0;
//	step 1: enter data to table "entry_no" and get an "entry_no" back for
			// enter data to table "timedata" &&
			// enter data to table "leavercd" &&
			// enter data to table "researchrcd"
			// enter data to table "updatets"	//this table will not be used.

//use same process id in update tables
include('get_tslog_id.inc');
$action	=	$action.$sql.'<br>';

if ($process_id =="") {
	echo "<H2><font color=#ff0000>Sending message has been abandoned.</fone></h2><br>";
	exit;
}

//	step 2: enter data to table "timedata"
//echo 'enter data to table "timedata"<br>';
for ($i=2; $i<$Noempty; $i++) {
	$sql	=	"INSERT INTO $dbname0.timedata VALUES(";
	$sql	=	$sql."'".$tsheet[0][$i]."',";	
	$sql	=	$sql."'".$tsheet[1][$i]."',";	
	$sql	=	$sql."'".$process_id."');";
	//brief_code	minutes	entry_no
	//echo $sql.'<br>';
	//*
	$result	=	mysql_query($sql,$contid);
	if (!$result) {
		$noerror	=	$noerror + 1;	//fail
	} else {
		$action	=	$action.$sql.'<br>';
	}
	//*/
}
//	step 3: enter data to table "researchrcd"
//echo '<br>enter data to table "researchrcd"<br>';
for ($i=2; $i<$Noempty; $i++) {
	if ($tsheet[3][$i] == 'text') {
		$sql	=	"INSERT INTO $dbname0.researchrcd VALUES(";
		$sql	=	$sql."'".$tsheet[1][0]."',";	
		$sql	=	$sql."'".$tsheet[0][$i]."',";	
		//$sql	=	$sql."'".addslashes($tsheet[4][$i])."',";	
		$sql	=	$sql."'".$tsheet[4][$i]."',";	
		$sql	=	$sql."'".$process_id."');";
		//email_name	brief_code		activity	entry_no
		//echo $sql.'<br>';
		//*
		$result	=	mysql_query($sql,$contid);
		if (!$result) {
			$noerror	=	$noerror + 1;	//fail
		} else {
			$action	=	$action.$sql.'<br>';
		}
		//*/
	}
}
//	step 4: enter data to table "leavercd"
//echo '<br>enter data to table "leavercd"<br>';
for ($i=2; $i<$Noempty; $i++) {
	if ($tsheet[3][$i] == 'date') {
		$sql	=	"INSERT INTO $dbname0.leavercd VALUES(";
		$sql	=	$sql."'".$tsheet[1][0]."',";	
		$sql	=	$sql."'".$tsheet[0][$i]."',";	
		$sql	=	$sql."'".$tsheet[4][$i]."',";	
		$sql	=	$sql."'".$tsheet[1][$i]."',";	
		$sql	=	$sql."'".$process_id."');";
		//email_name	brief_code		startday	minutes	entry_no
		//echo $sql.'<br>';
		//*
		$result	=	mysql_query($sql,$contid);
		if (!$result) {
			$noerror	=	$noerror + 1;	//fail
		} else {
			$action	=	$action.$sql.'<br>';
		}
		//*/
	}
}

/*
//	step 5: enter data to table "updatets"
//echo '<br>enter data to table "updatets"<br>';
$sql	=	"INSERT INTO $dbname0.updatets VALUES(";
$sql	=	$sql."'NULL',";	
$sql	=	$sql."'".$tsid_todelete."',";	
$sql	=	$sql."'".$tsename_todelete."');";
//entry_no 	old_id		email_name
//echo $sql.'<br>';

$result	=	mysql_query($sql,$contid);
if (!$result) {
	$noerror	=	$noerror + 1;	//fail
} else {
	$action	=	$action.$sql.'<br>';
}
//echo "Number of error is ".$noerror.'<br>';
//echo "Action ".$action.'<br>';
//*/

//	step 6: check result
if ($noerror == 0 ) {
	//successful, dispaly timesheet record with $process_id
	echo '<br><h2><font colot=#0000ff>Your timesheet update message has been sent successfully.</font><br>';
	$action = "TS update, $email_name, $yyyymmdd";
	include('logging.inc');
} else {
	//failed, remove timesheet record with $process_id if any record entered
	echo '<br><h2><font colot=#ff0000>Due to server problem,<br>';
	echo 'your timesheet update message is not able to sent, please report it to timesheet administrator.</h2>';
	echo '</font>';
}
mysql_close();

?>
</html>
