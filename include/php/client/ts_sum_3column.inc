<html>
<?php
	$newfile	=	"/usr/local/apache/htdocs/report/$filetitle";
	$fp	=	fopen($newfile,'w+');
	if (!$fp) {
		$wmsg = 0;
	} else {
		$wmsg = 1;
	}
	if ($wmsg = 1) {
		if ($priv == "00"){
			echo "<li><a href=\"../report/$filetitle\"><font size=\"$bulsize\">
			View or download summary file for period of $nowks weeks from $fromdate (3 columns).</font></a></li>";
		}
	}
	// Step 1: timesheet summary by project
	$sql = "SELECT t2.brief_code as pcode, sum(t2.minutes) as minutes "
		."FROM timesheet.entry_no as t1, timesheet.timedata as t2 "
		."WHERE t1.yyyymmdd>='$fromdate' and "
		."t1.yyyymmdd<'$todate' and t1.entry_no=t2.entry_no GROUP BY pcode ORDER BY pcode;";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$norcd = mysql_num_rows($result);
	$str = "<br><br>Summary by project code for $nowks weeks from $fromdate.";
	if ($wmsg = 1) {
		fputs($fp,"$str\n\r");
	}
	if ($display == 1) {
		echo "<b>$str</b><br><br>";
	}
	$i = 0;
	$hrsum = 0;
	if ($display == 1) {
		echo "<table border=1>";
		echo "<tr><th>No</th><th>Brief Code</th><th>Hour</th></tr>";
	}
	if ($wmsg = 1) {
		fputs($fp,"No,Brief Code,Hour\n");
	}
	while (list($pcode, $minutes) = mysql_fetch_array($result)) {
		$hour = $minutes/60;
		$hour = number_format($hour, 2);//int decimals, string dec_point, string thousands_sep);
		$hrsum = $hrsum + $hour;
		$i++;
		$t = underscores($pcode);
		if ($display == 1) {
			echo "<tr><td>$i</td><td>$t</td><td align=right>$hour</td></tr>";
			flush();
		}
		if ($wmsg = 1) {
			fputs($fp,"$i,$t,$hour\n");
		}
	}
	if ($display == 1) {
		echo "</table><p>";
		echo "<br><b>Total hours is $hrsum.<br><br>";
	}
	if ($wmsg = 1) {
		fputs($fp,"\nTotal hours is $hrsum.\n\r\r");
	}
	
	// Step 2: timesheet summary by people
	$sql = "SELECT t1.email_name as ename, sum(t2.minutes) as minutes "
		."FROM timesheet.entry_no as t1, timesheet.timedata as t2 "
		."WHERE t1.yyyymmdd>='$fromdate' and "
		."t1.yyyymmdd<'$todate' and t1.entry_no=t2.entry_no GROUP BY ename ORDER BY ename;";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$norcd = mysql_num_rows($result);
	$str = "Summary by project code for $nowks weeks from $fromdate.";
	if ($display == 1) {
		echo "<b>$str</b><br><br>";
	}
	if ($wmsg = 1) {
		fputs($fp,"$str\n\r");
	}
	$i = 0;
	$hrsum = 0;
	if ($display == 1) {
		echo "<table border=1>";
		echo "<tr><th>No</th><th>People</th><th>Hour</th></tr>";
	}
	if ($wmsg = 1) {
		fputs($fp,"No,Peoplr,Hour\n");
	}
	while (list($ename, $minutes) = mysql_fetch_array($result)) {
		$hour = $minutes/60;
		$hour = number_format($hour, 2);
		$hrsum = $hrsum + $hour;
		$i++;
		if ($display == 1) {
			echo "<tr><td>$i</td><td>".$ename."</td><td align=right>$hour</td></tr>";
			flush();
		}
		if ($wmsg = 1) {
			fputs($fp,"$i,$ename,$hour\n");
		}
	}
	if ($wmsg = 1) {
		fputs($fp,"\nTotal hours is $hrsum.\n\r");
	}
	if ($display == 1) {
		echo "</table><p>";
		echo "<br><b>Total hours is $hrsum.<br><br>";
	}
?>
</html>