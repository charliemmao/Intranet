<html>
<?php
$dbname0 = "timesheet";
include('connet_root_once.inc');	//contid

$sumtimedb = 0;
##	step 1: search data from timesheet.timedata
	$sql = "SELECT brief_code, minutes FROM timesheet.timedata WHERE 
		entry_no='$entry_no' order by brief_code;";
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
		$total_code = $i;
	}
	
##	step 2: search data from timesheet.researchrcd
	$sql = "SELECT brief_code, activity FROM timesheet.researchrcd WHERE 
		entry_no='$entry_no' order by brief_code;";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$i = 0;
	if ($result) {
		while (list($brief_code, $activity) = mysql_fetch_array($result)) {
			$dbrcd_research_code[$i] = $brief_code;
			$dbrcd_research_list[$brief_code] = $activity;
			$i++;
		}
		$no_research = $i;
	} else {
		$no_research = 0;
	}

##	step 3: search data from timesheet.marketing
	$sql = "SELECT brief_code, time, company_name, country FROM timesheet.marketing 
		WHERE entry_no='$entry_no' order by brief_code, company_name;";
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
		$no_marketing = $j - 1;
	} else {
		$no_marketing = 0;
	}

##	step 4: search data from timesheet.leavercd
	$sql = "SELECT brief_code, minutes, startday FROM timesheet.leavercd 
		WHERE entry_no='$entry_no' order by brief_code, startday;";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$j = 1;
	if ($result) {
		while (list($brief_code, $minutes, $startday) = mysql_fetch_array($result)) {
			if ($dbrcd_leavercd_code[$j-1] != $brief_code) {
				$i = 1;
			}
			$dbrcd_leavercd_code[$j] = $brief_code;
			$dbrcd_leavercd_list[$brief_code][0][$i] = $minutes;
			$dbrcd_leavercd_list[$brief_code][1][$i] = $startday;
			//echo $brief_code.": ".$minutes." $j-$i<br>";
			$i++;
			$j++;
		}
		$no_leavercd = $j - 1;
	} else {
		$no_leavercd = 0;
	}

	##	step 5: search data from timesheet.travel
	$sql = "SELECT time, company, activity FROM timesheet.travel
		WHERE entry_no='$entry_no' order by company;";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	$traveldetails = "";
	if ($no) {
		$traveldetails = "<table border=1><tr><th>Company</th><th>Minutes</th><th>Activity</th></tr>";
		while (list($time, $company, $activity) = mysql_fetch_array($result)) {
			$traveldetails .= "<tr><td>$company</td><td>$time</td><td>$activity</td></tr>";
		}
		$traveldetails .= "</table>";
		/*
		echo $sql."<br>no=$no<br>";
		echo "$traveldetails";
		//*/
	}
	
##	step 6: Display Result
	/*
	if ($no_research != 0 || $no_marketing!= 0 || $no_leavercd != 0) {
		echo "$total_code-R:$no_research-M:$no_marketing-L:$no_leavercd<br>";
	}
	//*/
	echo "<table border=1>";
	echo "<tr><th>No</th><th>Code</th><th>Minutes</th>";
	if ($no_research != 0 || $no_marketing!= 0 || $no_leavercd != 0 || $traveldetails) {
		echo "<th>Special Entry</th>";
	}
	echo "</tr>";

	for ($i=0; $i<$total_code; $i++) {
		$tmp = $dbrcd_code[$i];
		$bcode = ereg_replace("__", "&", $tmp);
		$bcode = ereg_replace("_", " ", $bcode);
		$j = $i +1;
		echo "<tr><td>$j</td><td>$bcode</td><td>".$dbrcd_list[$tmp]."</td>";
		if ($no_research != 0 || $no_marketing!= 0 || $no_leavercd != 0 || $traveldetails) {
			//$tmp = $dbrcd_research_code[$j], $dbrcd_research_list[$tmp]
			$special = $dbrcd_research_list[$tmp];
			if ($special == "") {
				$mark = 1;
				$special = $dbrcd_marketing_list[$tmp][0][$mark];
				if ($special != "") {
					$special = $dbrcd_marketing_list[$tmp][0][$mark]."-".
						$dbrcd_marketing_list[$tmp][1][$mark]."-".
						$dbrcd_marketing_list[$tmp][2][$mark];
					$mark++;
					while ($dbrcd_marketing_list[$tmp][0][$mark] != "") {
						$special = $special."<br>".$dbrcd_marketing_list[$tmp][0][$mark]."-".
						$dbrcd_marketing_list[$tmp][1][$mark]."-".
						$dbrcd_marketing_list[$tmp][2][$mark];
						$mark++;
					}
				}
			}

			if ($special == "") {
				$leave = 1;
				$special = $dbrcd_leavercd_list[$tmp][0][$leave];
				if ($special != "") {
					$special = $dbrcd_leavercd_list[$tmp][0][$leave]."min., ".
						$dbrcd_leavercd_list[$tmp][1][$leave];
					$leave++;
					while ($dbrcd_leavercd_list[$tmp][0][$leave] != "") {
						$special = $special."<br>".$dbrcd_leavercd_list[$tmp][0][$leave]."min., ".
						$dbrcd_leavercd_list[$tmp][1][$leave];
						$leave++;
					}
				}
			}
			if ($special == "") {
				$special = $dbrcd_leavercd_list[$tmp][1];
			}
			
			if ($bcode == "RLA-OHD-TRVL") {
				$special = $traveldetails;
			}
			if (!$special) {
				$special = "&nbsp;";
			}
			echo "<td>$special</td>";
		}
		echo "</tr>";
	}
	if ($no_research != 0 || $no_marketing!= 0 || $no_leavercd != 0 || $traveldetails) {
		echo "<tr><td colspan=4>";
	} else {
		echo "<tr><td colspan=3>";
	}
	echo "<b>Total time is $sumtimedb minutes.</b></td></tr></table><p>";
?>
</html>
