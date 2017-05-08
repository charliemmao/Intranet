<html>
<?php
	$result	=	mysql_query($sql);
	include("err_msg.inc");
	$no	=	mysql_num_rows($result);
	if ($priv == "100") {
		echo $sql."<br><br>";
		echo $no."<br><br>";
	}
	if ($no) {
		if ($priv == $setpriv) {
			while (list($id, $ename, $ymd, $minutes) = mysql_fetch_array($result)) {
				$name_list[$ename][$ymd][0] = $id;
				$name_list[$ename][$ymd][1] = $minutes;
				//echo "$id $ename $ymd (".$name_list[$ename][$ymd][1].")<br>";
			}
		} else {
			while (list($id, $ename, $ymd) = mysql_fetch_array($result)) {
				$name_list[$ename][$ymd][0] = $id;
				//echo "$id $ename $ymd (".$name_list[$ename][$ymd][0].")<br>";
			}
		}
		
		echo "<h4>Timesheet for the period from $start_date to $end_date. ";
		if ($listwarning == 1) {
			echo "<a href=#emailwarning>[To email warning section]</a>";
		}
		echo "</h4>";
		echo "<table border=1>";
		echo "<tr><th rowspan=2>No</th><th rowspan=2>Name</th>";
		for ($i=0; $i<$nowks; $i++) {
			$j = $i + 1;
			echo "<th>Week $j</th>";
		}
		echo "</tr><tr>";
		for ($i=0; $i<$nowks; $i++) {
			echo "<th>".$wklist[$i]."</th>";
		}
		echo "</tr>";

		for ($i=0; $i<$nostaff; $i++) {
			$ename = $name_list[$i][0];
			$fname = $name_list[$ename][1]." ".$name_list[$ename][2];
			$tsname = $fname;
			$k = $i + 1;
			echo "<tr><td>$k</td><td>$fname</td>";
			for ($j=0; $j<$nowks; $j++) {
				$ymd = $wklist[$j];
				if ($priv == $setpriv) {
					$id = $name_list[$ename][$ymd][0];
					$minutes = $name_list[$ename][$ymd][1];
					$view = $minutes;
				} else {
					$id = $name_list[$ename][$ymd][0];
					$view = "VIEW";
				}
				$qry	=	$userinfo."&viewts=viewts&entry_no=$id&tsname=$tsname&yyyymmdd=$ymd&empty=true";
				$qry	=	"?".base64_encode($qry);
				if ($id == "") {
					echo "<th>---</th>";
					$warning_list[$j] = $warning_list[$j]." $ename";
				} else {
					echo "<th>[<a href=\"$PHP_SELF$qry\">$view</a>]</th>";//$id
					$sum_ts[$j] = $sum_ts[$j] + 1;
				}
			}
			echo "</tr>";
		}
		$colspan = 2+$nowks;
		echo "<tr><td colspan=$colspan>&nbsp;</td><tr>";
		echo "<tr><td colspan=2>TS Received</td>";
		for ($j=0; $j<$nowks; $j++) {
			if ($sum_ts[$j] == "") {
				$sum_ts[$j] = 0;
			}
			echo "<th>".$sum_ts[$j]."</th>";
		}
		echo "</tr>";

		echo "<tr><td colspan=2>Warning List</td>";
		for ($j=0; $j<$nowks; $j++) {
			$warning_list[$j] = trim($warning_list[$j]);
			if ($warning_list[$j] == "") {
				$emaillist = "---";
			} else {
				$tmp = explode(" ", $warning_list[$j]);
				$emaillist = "";
				for ($i=0; $i<count($tmp)-1; $i++) {
					$emaillist = $emaillist.$tmp[$i]."@rla.com.au;";
				}
				$emaillist = $emaillist.$tmp[$i]."@rla.com.au";
				$emaillist = "<a href=\"mailto:$emaillist\">mail</a>";
			}
			echo "<th>".$emaillist."</th>";
		}
		echo "</tr>";
		
		echo "</table><p>";
	} else {
		echo "<h2>No timesheet has been found for the period from $start_date to $end_date.</h2>";
	}
	$emaillist = "";
	if ($listwarning == 1) {
		$domain = "; ";
		$mainbody = "";
		$nouniquelist = 0;
		for ($j=0; $j<$nowks; $j++) {
			$warning_list[$j] = trim($warning_list[$j]);
			$emaillist = "";
			if ($warning_list[$j]) {
				$tmp = explode(" ", $warning_list[$j]);
				$no = count($tmp);
				$tmpx = explode(" ", $warning_list[$j]);
				for ($k=0; $k<$nouniquelist; $k++) {
					for ($i=0; $i<$no; $i++) {
						if ($uniquelist[$k] == $tmpx[$i]) {
							$tmpx[$i] = "";
						}
					}
				}
				
				for ($i=0; $i<$no; $i++) {
					if ($tmpx[$i]) {
						$uniquelist[$nouniquelist] = $tmpx[$i];
						$nouniquelist++;
					}
				}
				
				for ($i=0; $i<$no-1; $i++) {
					$emaillist = $emaillist.$tmp[$i].$domain;
				}
				$emaillist = $wklist[$j].": ".$emaillist.$tmp[$i].".";
			}
			if ($emaillist) {
				$mainbody = $mainbody.$emaillist."\n";
			}
		}
		
		$sql = "SELECT email_name as ename FROM timesheet.employee WHERE computer_ip_addr='';";
		$result = mysql_query($sql);
		include("err_msg.inc");
		$nopc = mysql_num_rows($result);
		while (list($ename) = mysql_fetch_array($result)) {
			$nopclist[$nopc] = $ename;
			$nopc++;
		}		
		for ($i=0; $i<$nouniquelist; $i++) {
			for ($j=0; $j<$nopc; $j++) {
				if ($uniquelist[$i] == $nopclist[$j]) {
					$uniquelist[$i] = "";
					break;
				}
			}
		}
		
		$sql = "SELECT email_name as sect FROM logging.accesslevel WHERE priviledge='50';";
		$result = mysql_query($sql);
		include("err_msg.inc");
		list($sect) = mysql_fetch_array($result);
		for ($i=0; $i<$nouniquelist; $i++) {
			if ($uniquelist[$i] == $sect) {
				$addyes = "no";
				break;
			}
		}
		
		if ($addyes != "no") {
			for ($i=0; $i<$nouniquelist; $i++) {
				if ($uniquelist[$i] == "") {
					$uniquelist[$i] = $sect;
					break;
				}
			}
		}
		
		echo "<hr><h4><a id=emailwarning>Email warning for the period from $start_date to $end_date.</h4>";
		if ($nouniquelist) {
			$domain = "@rla.com.au; ";
			$emaillist = "";
			for ($i=0; $i<$nouniquelist; $i++) {
				if ($uniquelist[$i]) {
					$emaillist = "$emaillist$uniquelist[$i]$domain";
				}
			}
			$mthts = rlagetmonth($wklist[0]);
			$mthts = $mth[$mthts];
			$mainbody0 = "Hi, Everyone\n\n";
			$mainbody0 =	$mainbody0."You have not completed your timesheets for the Month of $mthts, ";
			$mainbody0 =	$mainbody0."please complete in the next day so that the end of month reports can be completed.";
			$mainbody0 = $mainbody0."\n\n".$mainbody;
			$mainbody0 =	$mainbody0."\n\nRegards\n\n\n$first_name $last_name\n\n".date("d/m/Y H:i");
			
			echo "<form method=post action=\"$PHP_SELF\">";
			echo "<table>";
			include("userstr.inc");
			$subject = "RE: Late Timesheets Warning for $mthts, ".rlagetyear($wklist[0]);
			echo "<tr><th align=left valign=top>To</th><td><textarea name=\"emaillist\" cols=60 rows=4>$emaillist</textarea></td></tr>";
			echo "<tr><th align=left valign=top>Subject</th><td><input name=\"subject\" value=\"$subject\" size=60></td></tr>";
			echo "<tr><th align=left valign=top>Message</th><td><textarea name=\"message\" cols=60 rows=20>$mainbody0</textarea></td></tr>";

			echo "<tr><td colspan=2 align=middle>
				<button type=\"submit\" name=\"sendwarningeamil\" onClick=\"return confirm('Do you really want to send this message?');\">
				<font size=4>Send This Message Now</font></button></td></tr>";
			echo "</table></form><p>";

		} else {
			echo "<b>No one on the email warning list.</b><br>";
		}
	} 
?>
</html>
