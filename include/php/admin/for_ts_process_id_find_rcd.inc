<html>
<?php
	include("connet_root_once.inc");
	if ($dbname0	!=	'updatets') {
		$dbname0	=	'timesheet';
	}	
	//step 1: find record from "$dbname0.timedata"
	//brief_code, minutes	entry_no
	$sql = "select brief_code, minutes from $dbname0.timedata where entry_no='".$process_id."';";
	//echo $sql.'<br>';
	$result = mysql_query($sql,$contid); 
	include('err_msg.inc');
    $out	=	$out."<tr><td colspan=2>&nbsp;</td></tr>";
    $out	=	$out."<tr><td colspan=2><b>The details on timesheet are as follows:</b></td></tr>";
    $out	=	"$out<tr><td><b>Porject Code</b></td><td><b>Minutes</b></td></tr>";
    $ts_total	=	0;
	while (list($brief_code, $minutes) = mysql_fetch_array($result)) {
		$ts_total	=	$ts_total + $minutes ;
    	$out	=	"$out<tr><td>$brief_code</td><td>$minutes</td></tr>";
    }
	if ($ts_total != 0) {
    	$out	=	"$out<tr><td><b>Total minutes</b></td><td><font color=#0000ff><b>$ts_total</b></font></td></tr>";
	}
	
	$spec	= '';
	//step 2: find record from "$dbname0.researchrcd"
	//brief_code, minutes	entry_no
	$sql = "select brief_code, activity from $dbname0.researchrcd where entry_no='".$process_id."';";
	$result = mysql_query($sql,$contid); 
	include('err_msg.inc');
	while (list($brief_code, $activity) = mysql_fetch_array($result)) {
    	$spec	=	"$spec<tr><td>$brief_code</td><td>$activity</td></tr>";
    }

	//step 3: find record from "$dbname0.leavercd"
	//email_name	brief_code		startday	minutes	entry_no
	$sql = "select brief_code, startday from $dbname0.leavercd where entry_no='".$process_id."';";
	$result = mysql_query($sql,$contid); 
	include('err_msg.inc');
	while (list($brief_code, $startday) = mysql_fetch_array($result)) {
    	$spec	=	"$spec<tr><td>$brief_code</td><td>$startday</td></tr>";
    }
	if ($spec !='') {
    	$out	=	$out."<tr><td colspan=2>&nbsp;</td></tr>";
    	$out	=	$out."<tr><td colspan=2><b>And there are special entries:</b></td></tr>";
	    $out	=	"$out<tr><td><b>Porject Code</b></td><td><b>Activity or Date</b></td></tr>";
    	$out	=	$out.$spec;
	}
	mysql_close();
?>
</html>
