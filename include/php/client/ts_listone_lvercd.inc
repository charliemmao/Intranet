<?php
$lvind = $ename0."lvind";
$alve = $ename0."alve";
$slve = $ename0."slve";
$lslve = $ename0."lslve";
$tiliu = $ename0."tiliu";
$altaken = 0;
$lsltaken = 0;
$sltaken = 0;
$tiltaken = 0;
$lveIndex = "";
			$sql = "SELECT first_name as fname, last_name as lname, lsl, al, sl, til, onthisday ".
				"FROM timesheet.leave_entitle WHERE email_name='$ename0';";
			$result = mysql_query($sql);
			include("err_msg.inc");
			list($fname, $lname, $lsl, $al, $sl, $til, $onthisday) = mysql_fetch_array($result);
			//*
			$lsl= number_format($lsl, 2);
			$sl= number_format($sl, 2);
			$al= number_format($al, 2);
			$til= number_format($til, 2);

			$lsl= ereg_replace(",","",$lsl);
			$sl= ereg_replace(",","",$sl);
			$al= ereg_replace(",","",$al);
			$til= ereg_replace(",","",$til);
			//*/
			
			echo "<h2><a id=\"$lvind\" name=\"$lvind\"></a>Leave Record for <font color=#0000ff>$fname $lname</font> (hours).";

			if ($nono) {
				echo "<a href=#summary>[To Summary]</a>";
			}

			echo "</h2><br>";
			echo "<table border=1>";
			$today = date("Y-m-d");
			$sql = "select TO_DAYS('$onthisday') as dlast;";
			$result = mysql_query($sql);
			include("err_msg.inc");
			list($dlast) = mysql_fetch_array($result);
			$sql = "select TO_DAYS('$today') as dtoday;";
			$result = mysql_query($sql);
			include("err_msg.inc");
			list($dtoday) = mysql_fetch_array($result);
			$nodaysince = $dtoday - $dlast + 1;
			
			$newal = 20*7.6;
			$newal = $newal*($nodaysince/365.25);
			$newal = number_format($newal, 2);

			//$newsl = 10*7.6;
			$newsl = $newsl *($nodaysince/365.25);
			$newsl = number_format($newsl, 2);
			
			if (0< $lsl) {
				$newlsl= 1.3*5*7.6;
			} else {
				$newlsl = 0;
			}
			$newlsl = $newlsl*($nodaysince/365.25);
			$newlsl = number_format($newlsl, 2);
			$sql = "SELECT brief_code, sum(minutes) as minutes FROM timesheet.leavercd ".
				"WHERE email_name='$ename0' and startday>='$onthisday' and startday<='$today' GROUP BY brief_code;"; 
			$result = mysql_query($sql);
			include("err_msg.inc");
			$no = mysql_num_rows($result);
			while (list($brief_code, $minutes) = mysql_fetch_array($result)) {
				if ($brief_code == "RLA-OHD-Annual_Leave") {
					$altaken = $minutes;
				} elseif ($brief_code == "RLA-OHD-Sick_Leave") {
					$sltaken = $minutes;
				} elseif ($brief_code == "RLA-OHD-LSL") {
					$lsltaken = $minutes;
				}
			}
			if ($altaken) {
				$lveIndex = "<a href=#$alve><li>View Annual Leave details.</a></li>";
				$altaken = number_format($altaken/60, 2);
				$altitlemnt = $al + $newal - $altaken;
			} else {
				$altaken = "---";
				$altitlemnt = $al + $newal;
			}
			$tmp = number_format($altitlemnt/7.6, 1);
			$altitlemnt = $altitlemnt." ($tmp d)";
			$lvercd[$ii][1] = $tmp;

			if ($sltaken) {
				$lveIndex = $lveIndex."<li><a href=#$slve>View Sick Leave Details.</a></li>";
				$sltaken = number_format($sltaken/60, 2);
				$sltitlemnt = $sl + $newsl - $sltaken;
			} else {
				$sltaken = "---";
				$sltitlemnt = $sl + $newsl;
			}
			$tmp = number_format($sltitlemnt/7.6, 1);
			$sltitlemnt = $sltitlemnt." ($tmp d)";
			$lvercd[$ii][2] = $tmp;
			if ($lsltaken) {
				$lveIndex = $lveIndex."<li><a href=#$lslve>View Long Service Leave Details.</a></li>";
				$lsltaken = number_format($lsltaken/60, 2);
				$lsltitlemnt = $lsl + $newlsl - $lsltaken;
			} else {
				$lsltaken = "---";
				$lsltitlemnt = $lsl + $newlsl;
			}
			$tmp = number_format($lsltitlemnt/7.6, 1);
			$lsltitlemnt = $lsltitlemnt." ($tmp d)";
			$lvercd[$ii][3] = $tmp;
			
			$sql = "SELECT count(yyyymmdd) as noweeks "
				."FROM timesheet.entry_no "
				."WHERE yyyymmdd>='$onthisday' and yyyymmdd<'$today' "
				."and email_name='$ename0';";
			$result = mysql_query($sql);
			include("err_msg.inc");
			list($noweeks) = mysql_fetch_array($result);
			//echo "$sql<br>";
			//echo "$noweeks<br>";

			$sql = "SELECT sum(t2.minutes) as totmin "
				."FROM timesheet.entry_no as t1, timesheet.timedata as t2 "
				."WHERE t1.yyyymmdd>='$onthisday' and t1.yyyymmdd<'$today' "
				."and t1.email_name='$ename0' and t1.entry_no=t2.entry_no ";
			$result = mysql_query($sql);
			include("err_msg.inc");
			list($totmin) = mysql_fetch_array($result);
			//echo "$sql<br>";
			//echo "$totmin<br>";

			$sql = "SELECT sum(t2.minutes) as tiltaken "
				."FROM timesheet.entry_no as t1, timesheet.timedata as t2 "
				."WHERE t1.yyyymmdd>='$onthisday' and t1.yyyymmdd<'$today' "
				."and t1.email_name='$ename0' and t1.entry_no=t2.entry_no "
				."and t2.brief_code='RLA-OHD-Time_in_Lieu'";
			$result = mysql_query($sql);
			include("err_msg.inc");
			list($tiltaken) = mysql_fetch_array($result);
			//echo "$sql<br>";
			//echo "tiltaken = $til<br>";
			$wkmi = 2280;
			if ($ename0 == "pm") {
				$wkmi = 1140;
			}
			if ($ename0 == "cas") {
				$wkmi = 1620;
			}
			if ($ename0 == "rma") {
				$wkmi = 1800;
			}
			
			$newtil = $totmin - $noweeks*$wkmi;
			$tiltitlemnt = $newtil - $tiltaken;
			$newtil = number_format($newtil/60, 2);
			if ($tiltaken) {
				$tiltaken = number_format($tiltaken/60, 2);
				$lveIndex = $lveIndex."<li><a href=#$tiliu>View Time in Lieu Details.</a></li>";
			} else {
				$tiltaken = "---";
			}
			$tiltitlemnt = number_format($tiltitlemnt/60, 2);
			$tmp = number_format($tiltitlemnt/7.6, 1);
			$tiltitlemnt = $tiltitlemnt." ($tmp d)";
			$lvercd[$ii][4] = $tmp;
			
			echo "<tr><th>Leave Type</th><th>Before<br>$onthisday</th>".
				"<th>Time<br>Accumulation</th><th>Time<br>Taken</th><th>Entitlement on<br>$today</th></tr>";
			$b1 = "<font color=#0000ff><b>";
			$b2 = "<b></font>";
			echo "<tr><th align=left>Annual</th><td align=middle>$al</td>
				<td align=middle>$newal</td><td align=middle>$altaken</td><td align=middle>$b1$altitlemnt$b2</td></tr>";
			echo "<tr><th align=left>Long Service</th><td align=middle>$lsl</td>
				<td align=middle>$newlsl</td><td align=middle>$lsltaken</td><td align=middle>$b1$lsltitlemnt$b2</td></tr>";
			echo "<tr><th align=left>Sick</th><td align=middle>$sl</td>
				<td align=middle>$newsl</td><td align=middle>$sltaken</td><td align=middle>$b1$sltitlemnt$b2</td></tr>";
			/*
			echo "<tr><th align=left>Time in Lieu</th><td align=middle>$til</td>
				<td align=middle>$newtil</td><td align=middle>$tiltaken</td><td align=middle>$b1$tiltitlemnt$b2</td></tr>";
			//*/
			echo "</table>";
			$lvercd0[$ii][1] = $altaken;
			$lvercd0[$ii][2] = $sltaken;
			$lvercd0[$ii][3] = $lsltaken;
			$lvercd0[$ii][4] = $tiltaken;

			
			$sname = "$fname $lname";
			if ($lveIndex) {
				echo "<br><br>";
				echo "<b>Leave Record Index ($sname)</b><br><ul><font size=2>".$lveIndex."</font></ul>";
			}
			$b1 = "<font size=2>";
			$b2 = "</font>";
			if ($altaken != "---") {
				$sql = "SELECT startday, minutes FROM timesheet.leavercd ".
					"WHERE email_name='$ename0' and startday>='$onthisday' and startday<='$today' ".
					"and brief_code='RLA-OHD-Annual_Leave' ORDER BY startday DESC;";
				echo "<a id=$alve></a>";
				leavelist($sql, "Annual Leave Record", $sname);
				echo "<br>[<a href=#$lvind>$b1"."Back to $sname's Leave Index$b2</a>]
					[<a href=#top>$b1"."Back to top$b2</a>]<br>";				
			}
			if ($sltaken != "---") {
				$sql = "SELECT startday, minutes FROM timesheet.leavercd ".
					"WHERE email_name='$ename0' and startday>='$onthisday' and startday<='$today' ".
					"and brief_code='RLA-OHD-Sick_Leave' ORDER BY startday DESC;";
				echo "<a id=$slve></a>";
				leavelist($sql, "Sick Leave Record", $sname);
				echo "<br>[<a href=#$lvind>$b1"."Back to $sname's Leave Index$b2</a>]
					[<a href=#top>$b1"."Back to top$b2</a>]<br>";				
			}
			if ($lsltaken != "---") {
				$sql = "SELECT startday, minutes FROM timesheet.leavercd ".
					"WHERE email_name='$ename0' and startday>='$onthisday' and startday<='$today' ".
					"and brief_code='RLA-OHD-LSL' ORDER BY startday DESC;";
				echo "<a id=$lslve></a>";
				leavelist($sql, "Long Service Leave Record", $sname);
				echo "<br>[<a href=#$lvind>$b1"."Back to $sname's Leave Index</a>]
					[<a href=#top>$b1"."Back to top$b2</a>]<br>";				
			}
			/*	
			if ($tiltaken != "---") {
				$sql = "SELECT t1.yyyymmdd as startday, t2.minutes as minutes "
					."FROM timesheet.entry_no as t1, timesheet.timedata as t2 "
					."WHERE t1.yyyymmdd>='$onthisday' and t1.yyyymmdd<'$today' "
					."and t1.email_name='$ename0' and t1.entry_no=t2.entry_no "
					."and t2.brief_code='RLA-OHD-Time_in_Lieu'";
				echo "<a id=$tiliu></a>";
				leavelist($sql, "Time in Lieu", $sname);
				echo "<br>[<a href=$lvind>$b1"."Back to $sname's Leave Index</a>]
					[<a href=#top>$b1"."Back to top$b2</a>]<br>";				
			}
			//*/
?>
