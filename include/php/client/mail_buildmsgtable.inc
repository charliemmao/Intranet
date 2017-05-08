<html>
<?php
	echo "<form method=post action=\"$PHP_SELF\" name=\"mailauto\">";
	include("userstr.inc");
	echo "<input type=hidden name=\"msgfrom\" value=\"$email_name\">";
	echo "<input type=hidden name=\"action\" value=\"$action\">";
	echo "<table>";
	echo "<tr><th align=left>From</th><td><b>$first_name $last_name<b></td>";
	$sql = "SELECT email_name, first_name, last_name FROM timesheet.employee 
		WHERE date_unemployed='0000-00-00';";  
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	echo "<tr><th align=left valign=top>To</th><td><select multiple name=\"msgto[]\">";
		while (list($email_name, $first_name, $last_name) = mysql_fetch_array($result)) {
			$sel = 0;
			for ($i=0; $i<count($ename)-1; $i++) {
				if ($email_name == $ename[$i]) {
					$sel = 1;
					break;
				}
			}
			if ($sel == 1) {
				echo "<option value=\"$email_name\" selected>$last_name, $first_name";
			} else {
				echo "<option value=\"$email_name\">$last_name, $first_name";
			}
		}
	echo "</option></select>&nbsp;Press \"<b>Ctrl</b>\" or \"<b>Shift</b>\" to have multiple selection.";
	echo "</td>";

	echo "<tr><th align=left valign=top>Subject</th><td>";
	echo "<textarea name=\"msgsubject\" cols=60 rows=2>$msgsubject</textarea>";
	echo "</td>";

	echo "<tr><th align=left valign=top>Message</th><td>";
	echo "<textarea name=\"msgbody\" cols=60 rows=10>$msgbody</textarea>";

	echo "<tr><th align=left>Recurrence</th><td><select name=\"msgtype\" onChange=\"changeform();\">";
	$i=0;
	$msg[$i] = "Once only"; $i++;
	$msg[$i] = "Weekly"; $i++;
	$msg[$i] = "Monthly"; $i++;
	if (!$msgtype) {
		$msgtype = $msg[0];
	}
	for ($i=0; $i<3; $i++) {
		if ($msgtype == $msg[$i]) {
			echo "<option selected value=\"$msg[$i]\">".$msg[$i];
		} else {
			echo "<option value=\"$msg[$i]\">".$msg[$i];
		}
	}
	echo "</option></select></td>";
	echo "<tr><td><b>Date Start</b></td><td>";
		$calfromyear	=	"calyearstart";
		$calfrommonth	=	"calmonthstart";
		$calfromday	=	"caldaystart";

		if ($datastart) {
			$year =	rlagetyear($datastart);
			$month =	rlagetmonth($datastart);
			$day =	rlagetday($datastart);
		} else {
			$moffset = 0;
		}
		include("calender_mmddyyyy.inc");
	echo "</td></tr>";
	if ($msgtype == "Once only") {
		$style = "style=\"display: none\"";
	} else {
		$style = "";
	}
	echo "<tr id=dend $style><td><b>Date End</b></td><td>";
		$calfromyear	=	"calyearend";
		$calfrommonth	=	"calmonthend";
		$calfromday	=	"caldayend";
		if ($dateend) {
			$year =	rlagetyear($dateend);
			$month =	rlagetmonth($dateend);
			$day =	rlagetday($dateend);
			$moffset = 0;
		} else {
			$year =	"";
			$month =	"";
			$day =	"";
			$moffset = -1;
		}
	include("calender_mmddyyyy.inc");
	echo "</td></tr>";

	if ($msgtype == "Once only" || $msgtype == "Monthly") {
		$style = "style=\"display: none\"";
	} else {
		$style = "";
	}
	echo "<tr id=weekdays $style><th align=left valign=top>On This Day</th><td>";
		echo "<table><tr>";
		$b1 = "<font size=2><b>";
		$b2 = "</font>";
		$today = date("l");
		for ($i=0; $i<4; $i++) {
			if ($today == $wkdays[$i]) {
				echo "<td><input type=radio name=wksel[] value=\"$wkdays[$i]\" checked>$b1$wkdays[$i]$b2</td>";
			} else {
				echo "<td><input type=radio name=wksel[] value=\"$wkdays[$i]\">$b1$wkdays[$i]$b2</td>";
			}
		}
		echo "</tr><tr>";
		for ($i=4; $i<7; $i++) {
			if ($today == $wkdays[$i]) {
				echo "<td><input type=radio name=wksel[] value=\"$wkdays[$i]\" checked>$b1$wkdays[$i]$b2</td>";
			} else {
				echo "<td><input type=radio name=wksel[] value=\"$wkdays[$i]\">$b1$wkdays[$i]$b2</td>";
			}
		}
	echo "</tr></table></td><tr>";
	
	if ($msgtype == "Once only" || $msgtype == "Weekly") {
		$style = "style=\"display: none\"";
	} else {
		$style = "";
	}
	echo "<tr id=thisday $style><th align=left valign=top>On This Day</th>";
	echo "<td><select name=spedays>";
	if (!datesend) {
		$day = date("d");
	} else {
		$day = $datesend;
	}
	for ($i=1; $i<32; $i++) {
		if ($i<10) {
			$tmp = "0$i";
		} else {
			$tmp = $i;
		}
		if ($day == $tmp) {
			echo "<option selected>$tmp";
		} else {
			echo "<option>$tmp";
		}
		echo "<option>$tmp";
	}
	echo "</option></select></td></tr>";
	
	echo "<tr ><th align=left valign=top>At Time (hour)</th>";
	echo "<td><select name=athour>";
	if (!$hour) {
		$hour = date("H") + 1;
	}
	if ($hour < 10) {
		$hour = "0".$hour;
	}
	for ($i=1; $i<25; $i++) {
		if ($i<10) {
			$tmp = "0$i";
		} else {
			$tmp = $i;
		}
		if ($hour == $tmp) {
			echo "<option selected>$tmp";
		} else {
			echo "<option>$tmp";
		}
	}
	echo "</option></select></td></tr>";
	
	if ($action == "new") {
		echo "<input type=hidden name=\"active\" value=\"Y\">";
		echo "<input type=hidden name=\"msgid\" value=\"null\">";
		$sub = "Submit";
	} else {
		echo "<input type=hidden name=\"msgid\" value=\"$msgid\">";
		echo "<tr><th align=left valign=top>Type of Action</th>";
		echo "<td><select name=active>";
		echo "<option value=Y>Continue to send email message";
		echo "<option value=N>Stop to send email message";
		echo "</option></select></td></tr>";
		$sub = "Modify This Entry";
	}
	echo "<tr><td colspan=2>&nbsp;</td></tr>";
	echo "<tr><td colspan=2 align=middle><button type=\"submit\" name=\"placemsg\">
		<font size=4><b>$sub</b></font></button></td></tr>";
	echo "</table>";
?>
</html>
