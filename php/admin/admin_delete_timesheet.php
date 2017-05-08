<html>

<head>
<title>Delete Timesheet Record</title>
</head>

<body bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG">
<?php
include("admin_access.inc");
include('rla_functions.inc');
echo "<h1	align=center>Delete Timesheet Record</h1><br>";
echo '<p align=center><a href="'.$PHP_SELF.$admininfo.'">[Refresh]</a>
	<a href="/'.$phpdir.'/adminctl_top.php'.$admininfo.'">[Admin Main Page]</a><hr>';

$delete =	'yes';
#echo $delete ."<br>";
#exit;
echo "<br><b>".date("Y-m-d h:i:s")."</b><br><br>";

if ($deletetsrcdwithid) {
	//password
	$sql	=	"SELECT email_name FROM logging.accesslevel WHERE priviledge='00';";
	include('general_one_val_search.inc');
	if ($password	!= $out.'00') {
		#echo "<h2>Please enter right password</h2>";
		#exit;
	}
	$entry_no = trim($entry_no);
	if ($entry_no<=0) {
		echo "Please enter a process ID";
		exit;
	}
	//echo $entry_no;
	include("connet_root_once.inc");
	$dbname0 = "timesheet";
	$table_field_list[0] = "entry_no";
	$table_field_list[1] = "timedata";
	$table_field_list[2] = "leavercd";
	$table_field_list[3] = "researchrcd";
	$table_field_list[4] = "marketing";

	$db_table = $dbname0.".".$table_field_list[0];
	$sql = "SELECT entry_no as no FROM $db_table WHERE entry_no='$entry_no';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($no) = mysql_fetch_array($result);
	if (!$no) {
		echo "<br><b>Timesheet entry <font color=#ff0000>$entry_no</font> doesn't exist.</b><br><br>";
	} else {
	  for ($tablei=0; $tablei<5; $tablei++) {
		$db_table = $dbname0.".".$table_field_list[$tablei];
		$sql = "DELETE FROM $db_table WHERE entry_no='$entry_no';";
		$result = mysql_query($sql);
		include("err_msg.inc");
		if (!$result) {
			echo "Failed to delete record from $db_table with $entry_no.<br>"; 
		} else {
			echo "$sql<br>";
		}
	  }
	}
	
	echo "<hr>";
} elseif ($deletegrptsrcd) {
	//idfrom	idto
	if ((int)($idfrom)	<= 0 && (int)($idto)	<= 0) {
		echo '<h2>Both <font color=#ff0000>'.$idfrom.'</font>&nbsp;and&nbsp;<font color=#ff0000>'.$idto.'</font> are not valid numbers.</h2><br>';
	} elseif ((int)($idfrom)	== 0) {
		echo '<h2><font color=#ff0000>'.$idfrom.'</font> is not a valid number.</h2><br>';
	} elseif ((int)($idto)	== 0) {
		echo '<h2><font color=#ff0000>'.$idto.'</font> is not a valid number.</h2><br>';
	} else {
		for ($idel = $idfrom; $idel<= $idto; $idel++) {
			$process_id	=	$idel;
			include('for_ts_process_id_delete_rcd.inc');
		}
	}
	exit;
} elseif ($deletetsrcdwithename) {	
	//email_name	date
	$tsfor	= $email_name;
	$yyyymmdd	=	$date;
	//echo 'Record to delete has email name '.$email_name.' and date of '.$date.'.<br>';
	include('for_ts_process_id_delete_rcd.inc');
	exit;
} elseif ($deletealltsrecord) {
	//password
	$sql	=	"SELECT email_name FROM logging.accesslevel WHERE priviledge='00';";
	include('general_one_val_search.inc');
	if ($password	== $out.'00') {
		
		$dbname0	=	"timesheet";
		$table_to_delete	=	"$dbname0.leavercd";
		include("delete_all_rcds.inc");
		$table_to_delete	=	"$dbname0.researchrcd";
		include("delete_all_rcds.inc");
		$table_to_delete	=	"$dbname0.timedata";
		include("delete_all_rcds.inc");
		$table_to_delete	=	"$dbname0.entry_no";
		include("delete_all_rcds.inc");
		$table_to_delete	=	"$dbname0.marketing";
		include("delete_all_rcds.inc");
		
		$dbname0	=	"updatets";
		$table_to_delete	=	"$dbname0.leavercd";
		include("delete_all_rcds.inc");
		$table_to_delete	=	"$dbname0.researchrcd";
		include("delete_all_rcds.inc");
		$table_to_delete	=	"$dbname0.timedata";
		include("delete_all_rcds.inc");
		$table_to_delete	=	"$dbname0.entry_no";
		include("delete_all_rcds.inc");
		$table_to_delete	=	"$dbname0.marketing";
		include("delete_all_rcds.inc");
		//$table_to_delete	=	"$dbname0.updatets";
		//include("delete_all_rcds.inc");
	} else {
		echo "Please check your password.";
	}
	exit;
}
?>

<form method="POST" target="main" action= "<?php echo $PHP_SELF ?>">
    <p style="margin-top: 0; margin-bottom: 0">
	<b><font face="Arial Narrow">Delete One Timesheet Record</font></b><br>
    <p style="margin-top: 0; margin-bottom: 0"><b><font face="Arial Narrow">Enter Password&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="password" name="password" size="20">
    <p style="margin-top: 0; margin-bottom: 0"><b><font face="Arial Narrow">Timesheet Entry
    ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="text" name="entry_no" size="20" value="<?php echo $entry_no ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="submit" value="GO" name="deletetsrcdwithid"></font></b></p>
</form>
<hr>
<!--
<form method="POST" target="main" action= "<?php echo $PHP_SELF ?>">
    <p style="margin-top: 0; margin-bottom: 0">
    <b><font face="Arial Narrow">Delete All Timesheet Records</font></b>
    <p style="margin-top: 0; margin-bottom: 0">
    <b><font face="Arial Narrow">Password&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="password" name="password" size="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="submit" value="GO" name="deletealltsrecord"></font></b>
</form>
<hr>

<form method="POST" target="main" action= "<?php echo $PHP_SELF ?>">
    <p style="margin-top: 0; margin-bottom: 0">
	<b><font face="Arial Narrow">Delete A Group Timesheet Records</font></b>
    <p style="margin-top: 0; margin-bottom: 0"><b><font face="Arial Narrow">Timesheet Entry ID From&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
    <input type="text" name="idfrom" size="20" value="<?php echo $idfrom ?>"></font></b></p>
    <p style="margin-top: 0; margin-bottom: 0"><b><font face="Arial Narrow">Timesheet Entry ID To&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="text" name="idto" size="20" value="<?php echo $idto ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="submit" value="GO" name="deletegrptsrcd"></font></b></p>
</form>

<hr>

<form method="POST" target="main" action= "<?php echo $PHP_SELF ?>">
    <p style="margin-top: 0; margin-bottom: 0">
	<b><font face="Arial Narrow">Delete One Timesheet Record</font></b>
    <p style="margin-top: 0; margin-bottom: 0"><font face="Arial Narrow"><b>Email
    Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
    <input type="text" name="email_name" size="20" value="<?php echo $email_name ?>"></b></font></p>
    <p style="margin-top: 0; margin-bottom: 0"><b><font face="Arial Narrow">Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="text" name="date" size="20" value="<?php echo $date ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="submit" value="GO" name="deletetsrcdwithename"></font></b></p>
</form>

<hr>
-->
</body>
</html>
