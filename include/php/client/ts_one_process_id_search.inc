<html>
<?php
$dbname0 = "timesheet";
include('connet_other_once.inc');	//contid
if (!$tsfor) {
	list($key, $tsfor) = each($HTTP_POST_VARS);
}
$entry_no = $process_id;
if ($priv == "50" || $priv == "00" || $priv == "10" || $email_name == "rma") {
} else {
	$tsfor = $email_name;
}
$yyyymmdd = $calfriday;
if ($entry_no <= 0) {
	$sql = "SELECT entry_no FROM timesheet.entry_no WHERE email_name='$tsfor' and yyyymmdd='$yyyymmdd';";
	if ($priv == "00") {
		//echo "$sql<br>";
	}
	$result = mysql_query($sql);
	include("err_msg.inc");
	if (count($result)) {
		list($entry_no, $timestamp) = mysql_fetch_array($result);
	}
}
$sumtimedb = 0;
if ($entry_no>0) {
	//echo "Timesheet prepared for: $tsfor; Friday Ending on: $calfriday; Process_id: $entry_no.<br>";
##	step 1: search data from timesheet.timedata
	$sql = "SELECT brief_code, minutes FROM timesheet.timedata WHERE entry_no='$entry_no';";
if ($priv == "00") {
	echo $tmp."<br>";
}
	$result = mysql_query($sql);
	include("err_msg.inc");
	$i = 0;
	if ($result) {
		while (list($brief_code, $minutes) = mysql_fetch_array($result)) {
			$dbrcd_code[$i] = $brief_code;
			$dbrcd_list[$brief_code] = $minutes;
			$sumtimedb = $sumtimedb + $minutes;
			$i++;
		}
	}
if ($priv == "00") {
	/*
	for ($j=0; $j<$i; $j++) {
		$tmp = $dbrcd_code[$j];
		echo $tmp.": ".$dbrcd_list[$tmp]."<br>";
	}
	//*/
}
##	step 2: search data from timesheet.researchrcd
	$sql = "SELECT brief_code, activity FROM timesheet.researchrcd WHERE entry_no='$entry_no';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$i = 0;
	if ($result) {
		while (list($brief_code, $activity) = mysql_fetch_array($result)) {
			$dbrcd_research_code[$i] = $brief_code;
			$dbrcd_research_list[$brief_code] = $activity;
			$i++;
		}
	}
	/*
	for ($j=0; $j<$i; $j++) {
		$tmp = $dbrcd_research_code[$j];
		echo $tmp.": ".$dbrcd_research_list[$tmp]."<br>";
	}
	//*/

	##	step 3: search data from timesheet.marketing
	$sql = "SELECT brief_code, time, company_name, country FROM timesheet.marketing WHERE entry_no='$entry_no' order by brief_code, company_name;";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$j = 1;
	if ($result) {
		while (list($brief_code, $time, $company_name, $country) = mysql_fetch_array($result)) {
			if ($dbrcd_marketing_code[$j-1] != $brief_code) {
				$i = 1;
			}
			$dbrcd_marketing_code[$j] = $brief_code;
			$dbrcd_marketing_list[$brief_code][0][$i] = $time;
			$dbrcd_marketing_list[$brief_code][1][$i] = $company_name;
			$dbrcd_marketing_list[$brief_code][2][$i] = $country;
			$i++;
			$j++;
		}
	}
	/*
	$j = 1;
	for ($i=0; $i<=count($dbrcd_marketing_code); $i++) {
		if ($dbrcd_marketing_code[$j-1] != $dbrcd_marketing_code[$j]) {
			$j = 1;
		}
		$tmp = $dbrcd_marketing_code[$i];
		echo $tmp."<br>";
		echo $dbrcd_marketing_list[$tmp][0][$j]." ".$dbrcd_marketing_list[$tmp][1][$j]." "
			.$dbrcd_marketing_list[$tmp][2][$j]."<br>";
		$j++;
	}
	//*/

	##	step 4: search data from timesheet.leavercd
	$sql = "SELECT brief_code, minutes, startday FROM timesheet.leavercd WHERE entry_no='$entry_no' order by brief_code, startday;";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$j = 1;
	if ($result) {
		while (list($brief_code, $minutes, $startday) = mysql_fetch_array($result)) {
			if ($dbrcd_leavercd_code[$j-1] != $brief_code) {
				$i = 1;
			}
			$str	=	"'$startday'";	
			# find day of the week for today
			$qry = "select DAYOFWEEK($str) as daywk;";
			$result0 = mysql_query($qry);
			if ($result0) {
				list($daywk) = mysql_fetch_array($result0);
			}
			//echo $startday." $daywk<br>";
			$daywk = $daywk -1;
			$dbrcd_leavercd_code[$j] = $brief_code;
			$dbrcd_leavercd_list[$brief_code][$daywk] = $minutes;
			//echo $brief_code.": ".$minutes." $j-$i<br>";
			$i++;
			$j++;
		}
	}
	/*
	$j = 1;
	for ($i=0; $i<=count($dbrcd_leavercd_code); $i++) {
		if ($dbrcd_leavercd_code[$j-1] != $dbrcd_leavercd_code[$j]) {
			$j = 1;
		}
		$tmp = $dbrcd_leavercd_code[$i];
		echo $tmp.$dbrcd_leavercd_list[$tmp][$j]."<br>";
		$j++;
	}
	//*/

	##	step 5: search data from timesheet.travel
	$sql = "SELECT time, company, activity FROM timesheet.travel
		WHERE entry_no='$entry_no' order by company;";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$j = 1;
	if ($result) {
		while (list($time, $company, $activity) = mysql_fetch_array($result)) {
			$dbrcd_travel_list[0][$j] = $time;
			$dbrcd_travel_list[1][$j] = $company;
			$dbrcd_travel_list[2][$j] = $activity;
			$j++;
		}
		$travelrcd = $j;
	}
	/*
	echo $sql."<br>";
	for ($i=0; $i<=$travelrcd; $i++) {
		echo $dbrcd_travel_list[0][$i]."  ".$dbrcd_travel_list[1][$i]."  ".$dbrcd_travel_list[2][$i]."<br>";
	}
	exit;
	//*/

}
?>
</html>
