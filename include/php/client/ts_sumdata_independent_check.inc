<html>
<?php
	$display = 0;
	## query project sum by week to check 
	## procedure 3: week cumulative by project against people in query A
	$norcd = 0;
	for ($wkno=0; $wkno<$nowks; $wkno++) {
		$tmp = $wklist[$wkno];
		$ymd_ind = $wkstrtono[$tmp];
		$sql = "SELECT t2.brief_code as pcode, sum(t2.minutes) as minutes "
		."FROM timesheet.entry_no as t1, timesheet.timedata as t2 "
		."WHERE t1.yyyymmdd='$tmp' and t1.entry_no=t2.entry_no GROUP BY t2.brief_code;";
		//echo "<br>$sql<br>";
		$result = mysql_query($sql);
		include("err_msg.inc");
		$norcd = $norcd + mysql_num_rows($result);
		while (list($pcode, $minutes) = mysql_fetch_array($result)) {
			//$fromcodetono[$pcode] = $j -> $ordered_code[0][$j] = $pcode
			//$wkstrtono[$ymd] - $k - $yhyqmw[$k][0] - $wklist[$i] = $ymd
			//$fromstafftono[$ename] = $i - $stafflist[0][$i] = $ename
			$pcode_ind = $fromcodetono[$pcode];
			$code_sum[$pcode_ind][$ymd_ind] = $minutes;
		}
	}

	if ($display == 1) {
		echo "<br><br><b>Project vs weeks ($norcd)</b><br><br>";
		echo "<table border=1><tr><th>Project Code</th>";
		for ($i=0; $i<$nowks; $i++) {
			echo "<th>$wklist[$i]</th>";
		}
		echo "</tr>";
		for ($i=0; $i<$codeplusgroup; $i++) {
			$tmp = $ordered_code[0][$i];
			echo "<tr><td>$tmp</td>";
			for ($j=0; $j<$timeseries; $j++) {
				if ($yhyqmw[$j][0] == "w") {
					$tmp = $code_sum[$i][$j];
					if ($tmp == "") {
						//$tmp = "---";
					}
					echo "<td>$tmp</td>";
				}
			}
			echo "</tr>";
		} 
		echo "</table><p>";
	}

	// subtotal: project vs people
	for ($i=0; $i<$codeplusgroup; $i++) {
		for ($j=0; $j<$staffno; $j++) {
			for ($k=0; $k<$timeseries; $k++) {
				if ($minarray[$i][$k][$j] != "") {
					$proj_people[$i][$j] = $proj_people[$i][$j] + $minarray[$i][$k][$j];//$minarray[$pcode_ind][$ymd_ind][$ename_ind]
					//echo $minarray[$i][$k][$j]."<br>";
				}
			}
		}
	}
	
	// sub total: people
	//echo "<br><b>People Sub Total</b><br>";
	for ($i=0; $i<$staffno; $i++) {
		$tmp = 0;
		for ($j=0; $j<$codeplusgroup; $j++) {
			$tmp = $tmp + $proj_people[$j][$i];
		}
		$proj_people[$codeplusgroup][$i] = $tmp;
		if ($tmp != 0) {
			//echo "$"."codeplusgroup($codeplusgroup, $i) = $tmp, ".$stafflist[0][$i]."<br>";
		}
		flush();
	}

	// sub total: project
	//echo "<br><b>Project Sub Total</b><br>";
	$totalt = 0;
	for ($i=0; $i<$codeplusgroup; $i++) {
		$tmp = 0;
		for ($j=0; $j<$staffno; $j++) {
			$tmp = $tmp + $proj_people[$i][$j];
		}
		$proj_people[$i][$staffno] = $tmp;
		$totalt = $totalt + $tmp;
		if ($tmp != 0) {
			//echo "$"."codeplusgroup($i, $staffno) = $tmp, ".$ordered_code[0][$i]."<br>";
		}
		flush();
	}
	$proj_people[$codeplusgroup][$staffno] = $totalt;
	$totalt = number_format($totalt/60, 2);
	$totalt= ereg_replace(",", "", $totalt);
	//echo "<br><b>Total hours is $totalt.<br><br>";

	## write monthly csv report and display on screen
	if ($priv == "00"){
		echo "<hr>";
		flush();
	}
	$filetitle = $filetitle0."_Code_vs_People.csv";
	$newfile	=	"/usr/local/apache/htdocs/report/$filetitle";
	$fp	=	fopen($newfile,'w+');
	if (!$fp) {
		$wmsg = 0;
	} else {
		$wmsg = 1;
	}
	if ($wmsg == 1) {
		fputs($fp,"Project vs People.\n\nNo,Code");
		for ($i=0; $i<$staffno; $i++) {
			fputs($fp,",".$stafflist[0][$i]);
		}
		fputs($fp,",Sub Total\n");
		
		for ($i=0; $i<=$codeplusgroup; $i++) {
			if ($proj_people[$i][$staffno]) {
				if ($i == $codeplusgroup) {
					fputs($fp,",Sub Total");
				} else {
					fputs($fp,$i.",".$ordered_code[0][$i]);
				}
				for ($j=0; $j<=$staffno; $j++) {
					$hour = $proj_people[$i][$j];
					if ($hour) {
						$hour = number_format($hour/60, 2);
						$hour = ereg_replace(",", "", $hour);
					}
					fputs($fp,",$hour");
				}
				fputs($fp,"\n");
			}
		}
	}
	
	if ($wmsg = 1) {
		fputs($fp,"\n\n");
		fclose($fp);	//\"$filetitle\"
		echo "<br><font size=\"$bulsize\">The above monthly summary for a period of $nowks weeks from $fromdate
			&nbsp;is available for <a href=\"../report/$filetitle\">download</a>.</font><br>";
	}
?>
</html>