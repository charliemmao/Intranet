<html>
<?php
	//echo $sql."<br>";
	$tsname = "$first_name $last_name";
	$result	=	mysql_query($sql);
	if ($result) {
		while (list($id, $ymd) = mysql_fetch_array($result)) {
			$ts_list[$ymd] = $id;
			//echo "$id $ename $ymd (".$ts_list[$ymd].")<br>";
		}
		echo "<h4>Timesheet for the period from $start_date to $end_date.</h4>";
		echo "<table border=1>";
		if ($nowks > 5) {
			echo "<tr><th>Week</th><th>Status</th></tr>";
			for ($i=0; $i<$nowks; $i++) {
				$ymd = $wklist[$i];
				echo "<tr><td>$ymd</td><td align=center>";
				if ($ts_list[$ymd] == "") {
					echo "<font color=#ff0000><b>Not Sent</b></font>";
				} else {	
					$id = $ts_list[$ymd];
					$qry	=	$userinfo."&viewts=viewts&entry_no=$id&tsname=$tsname&yyyymmdd=$ymd&empty=true";
					$qry	=	"?".base64_encode($qry);
					echo "[<a href=\"$PHP_SELF$qry\">VIEW</a>]";
				}
				echo "</td></tr><tr>";
			}
		} elseif ($nowks <=5) {
			echo "<tr><td><b>Week</b></td>";
			for ($i=0; $i<$nowks; $i++) {
				echo "<th>".$wklist[$i]."</th>";
			}
			echo "</tr><tr><td><b>Status</b></td>";
			for ($i=0; $i<$nowks; $i++) {
				$ymd = $wklist[$i];
				echo "<td align=center>";
				if ($ts_list[$ymd] == "") {
					echo "<font color=#ff0000><b>Not Sent</b></font></td>";
				} else {	
					$id = $ts_list[$ymd];
					$qry	=	$userinfo."&viewts=viewts&entry_no=$id&tsname=$tsname&yyyymmdd=$ymd&empty=true";
					$qry	=	"?".base64_encode($qry);
					echo "[<a href=\"$PHP_SELF$qry\">VIEW</a>]</td>";
				}
			}
			echo "</tr>";
		}
		echo "</table><p>";
	} else {
		echo "<h2>No Timesheet Has Been Found for the Period from $start_date to $end_date.</h2>";
	}
?>
</html>
