<html>
<head>
<title>Budget Report</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<LINK REL="StyleSheet" HREF="../style/style.css" TYPE="text/css">

</head>
<body leftmargin="20" bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG">
<?php
include('str_decode_parse.inc');
include("connet_other_once.inc");
include("rla_functions.inc");
include("userinfo.inc"); //$userinfo
if ($priv == "00" ) {//|| $priv	==	'10'
} else {
	exit;
}
//echo "&datefrom=$datefrom&dateto=$dateto&ename=$ename&fname=$fname&rpttitle=$rpttitle";
$timeframe = "(period from $datefrom to $dateto)";
//$userstr	=	"?".base64_encode($userinfo.$extra);
$h = "$rpttitle ($fname)";
echo "<h2 align=center>$h</h2><hr>";

findmonthlist($datefrom, $dateto);
for ($i=0; $i<$mthno; $i++) {
	$podmthno = $mthno;
	$podmonthlist[$i] = $monthlist[$i];
}

$title0 = $email_name."_$ename"."_".ereg_replace(" ","_", $rpttitle).".csv";
$rptweburl = "/report/$title0";
$rptserverfile = "/usr/local/apache/htdocs/report/$title0";
echo "<a href=\"$rptweburl\"><b>Download CSV file</b></a><br><br>";
$csvfh = fopen($rptserverfile, "w");
fputs($csvfh, $h."\n");

	echo "<h2>Summary: Hours</h2>";
	echo "<table border=1 $tabbg>";
	echo "<tr><th>Actual</th><th>Budgeted</th></tr>";
	fputs($csvfh, "Actual,Budgeted\nSummary: Hours\n");
####Summary section
		##actual hours
			$sql = "SELECT sum(t2.minutes) as actualhours
	        	FROM timesheet.entry_no as t1, timesheet.timedata as t2
   		     	WHERE (t1.yyyymmdd>='$datefrom' and t1.yyyymmdd<='$dateto') 
       	 		and t1.entry_no=t2.entry_no and t1.email_name='$ename'
       	 		and t2.brief_code!='RLA-OHD-Time_in_Lieu';";
	    	$result = mysql_query($sql);
   		 	include("err_msg.inc");
    		list($actualhours) = mysql_fetch_array($result);
       	//echo $sql."<br><br>$actualhours<br>";
    		
		##budgeted hours
			$sql = "SELECT t2.date_start as tskdateS, t2.date_end as tskdateE, 
						t2.nomths as nomths, t4.hours as hours
      	  		FROM timesheet.bgtfileidx as t1, timesheet.bgtfiletasks as t2,
      	  			 timesheet.bgtfileresource as t3, timesheet.bgtfileres_task_hr as t4
      	  		WHERE t1.active='y' and t1.end_date>='$datefrom' and t1.begin_date<='$dateto' and
      	  		        			
        			t2.bgtfileidx=t1.bgtfileidx and
        			t2.date_end>='$datefrom' and t2.date_start<='$dateto' and 
        			
        			t3.email_name='$ename' and t3.bgtfileidx=t1.bgtfileidx and
        			
        			t4.bgtrscidx=t3.bgtrscidx and t4.taskidx=t2.taskidx;";
        	/*table 1: bgtfileidx. columns: bgtfileidx, projcode_id, description, client, 
            begin_date, end_date, preparedby, uploaddate, active,  */
          /*table 2: bgtfiletasks. columns: taskidx, bgtfileidx, m_stasks, tasks, 
            date_start, date_end, nomths, hours,  */
          /*table 3: bgtfileresource. columns: bgtrscidx, bgtfileidx, email_name, title, level, hrateus,  */
          /*table 4: bgtfileres_task_hr. columns: rthidx, taskidx, bgtrscidx, hours,  */
       	//echo $sql."<br><br><br>";
       	
    		$result = mysql_query($sql);
    		include("err_msg.inc");
    		$budgethours = 0;
			while (list($tskdateS, $tskdateE, $nomths, $hours) = mysql_fetch_array($result)) {
				$hrsinmth = $hours/$nomths; 
				//echo "$tskdateS, $tskdateE, $nomths, $hours<br>";
				$budgethours += calhours($tskdateS, $tskdateE, $hrsinmth);
			}
    		//list($budgethours) = mysql_fetch_array($result);
    		if ($actualhours || $budgethours) {
     			$no1 = sprintf("%01.2f",$actualhours/60.0);
    			$no2 = sprintf("%01.2f",$budgethours);
    			
   				$actualhours= number_format($actualhours/60.0, 2);
    			$budgethours = number_format($budgethours, 2);

				echo "<tr>	<td align=middle>$actualhours</td>
					<td align=middle>$budgethours</td></tr>";
					fputs($csvfh, "$no1,$no2\n");
			} else {
				if ($projforanyperiod) {
					echo "<tr><td align=right>---</td><td align=right>---</td></tr>";
					fputs($csvfh, "---,---\n");
				}
			}
	echo "</table><p>";

####Project break down: hours
	echo "<h2>Project Breakdown: Hours $timeframe</h2>";
	fputs($csvfh, "\nProject Breakdown: Hours\n");
	
	//project code list in actual recorded time
	$sql = "SELECT projcode_id as pid, brief_code as bc
       FROM timesheet.projcodes 
       WHERE brief_code!='RLA-OHD-Time_in_Lieu'
       ORDER BY brief_code;";
    $result = mysql_query($sql);
    include("err_msg.inc");
    while (list($pid, $bc) = mysql_fetch_array($result)) {
    	if ($bc) {
    		$projcodelist[$bc] = $pid;
    		$projidtocode[$pid] = $bc;
       }
   	}

	$sql = "SELECT DISTINCT t2.brief_code as brief_code
		FROM timesheet.entry_no as t1, timesheet.timedata as t2
		WHERE (t1.yyyymmdd>='$datefrom' and t1.yyyymmdd<='$dateto') 
       	 		and t1.entry_no=t2.entry_no and t1.email_name='$ename'
       	 		and t2.brief_code!='RLA-OHD-Time_in_Lieu'
		GROUP BY t2.brief_code
		ORDER BY t2.brief_code;";
    $result = mysql_query($sql);
    include("err_msg.inc");
    while (list($brief_code) = mysql_fetch_array($result)) {
    	$staffprojlist[$brief_code] = 1;
    	//echo "$brief_code<br>";
   	}
   	echo "<br>";
	$sql = "SELECT DISTINCT t1.projcode_id as pid
      	  		FROM timesheet.bgtfileidx as t1, timesheet.bgtfiletasks as t2,
      	  			 timesheet.bgtfileresource as t3, timesheet.bgtfileres_task_hr as t4
      	  		WHERE t1.active='y' and t1.end_date>='$datefrom' and t1.begin_date<='$dateto' and
        			t2.bgtfileidx=t1.bgtfileidx and
        			t2.date_end>='$datefrom' and t2.date_start<='$dateto' and 
        			t3.email_name='$ename' and t3.bgtfileidx=t1.bgtfileidx and
        			t4.bgtrscidx=t3.bgtrscidx and t4.taskidx=t2.taskidx;";
    $result = mysql_query($sql);
    include("err_msg.inc");
    while (list($pid) = mysql_fetch_array($result)) {
    	$staffprojlist[$projidtocode[$pid]] = 1;
    	//echo $projidtocode[$pid]."<br>";
   	}

	echo "<table border=1>";
	echo "<tr><th>No</th><th>Project</th><th>Actual Hours</th><th>Budgeted Hours</th></tr>";
	fputs($csvfh, "No,Project,Actual Hours,Budgeted Hours\n");
	
	ksort ($projcodelist);
	reset ($projcodelist);
	$actualhours = 0;
	$budgethours = 0;
	$pctr = 0;
	
	while (list ($brief_code, $projcode_id) = each ($projcodelist)) {
    	//echo "$projcode_id = $brief_code<br>";
		if ($staffprojlist[$brief_code]) {
			$projinvolved[$pctr] = $brief_code;
			$pctr++;

			$sql = "SELECT sum(t2.minutes) as minutes
				FROM timesheet.entry_no as t1, timesheet.timedata as t2
				WHERE (t1.yyyymmdd>='$datefrom' and t1.yyyymmdd<='$dateto') 
       	 		and t1.entry_no=t2.entry_no and t1.email_name='$ename'
       	 		and t2.brief_code='$brief_code'";
			$result = mysql_query($sql);
			include("err_msg.inc");
			list($minutes) = mysql_fetch_array($result);
			//echo "$minutes<br>$sql<br>";
			
			##budgeted hours
			$actualhours +=$minutes;
			$hra = number_format($minutes/60,2);
			$no1 = sprintf("%01.2f",$minutes/60.0);
			echo "<tr><td>$pctr</td><td>$brief_code</td><td align=middle>$hra</td>";
		
			$sql = "SELECT t2.date_start as tskdateS, t2.date_end as tskdateE, 
					t2.nomths as nomths, t4.hours as hours
      	  		FROM timesheet.bgtfileidx as t1, timesheet.bgtfiletasks as t2,
      	  			 timesheet.bgtfileresource as t3, timesheet.bgtfileres_task_hr as t4
      	  		WHERE t1.projcode_id='$projcode_id' and t1.active='y' and 
      	  			t1.end_date>='$datefrom' and t1.begin_date<='$dateto' and      	  		        			
        			t2.bgtfileidx=t1.bgtfileidx and
        			t2.date_end>='$datefrom' and t2.date_start<='$dateto' and 
        			t3.email_name='$ename' and t3.bgtfileidx=t1.bgtfileidx and
        			t4.bgtrscidx=t3.bgtrscidx and t4.taskidx=t2.taskidx;";
        	/*table 1: bgtfileidx. columns: bgtfileidx, projcode_id, description, client, 
            begin_date, end_date, preparedby, uploaddate, active,  */
          /*table 2: bgtfiletasks. columns: taskidx, bgtfileidx, m_stasks, tasks, 
            date_start, date_end, nomths, hours,  */
          /*table 3: bgtfileresource. columns: bgtrscidx, bgtfileidx, email_name, title, level, hrateus,  */
          /*table 4: bgtfileres_task_hr. columns: rthidx, taskidx, bgtrscidx, hours,  */
       	//echo $sql0."<br><br><br>";
       	
    		$result = mysql_query($sql);
    		include("err_msg.inc");
    		$budgeted = 0;
			while (list($tskdateS, $tskdateE, $nomths, $hours) = mysql_fetch_array($result)) {
					$hrsinmth = $hours/$nomths; 
					//echo "$tskdateS, $tskdateE, $nomths, $hours<br>";
					$budgeted += calhours($tskdateS, $tskdateE, $hrsinmth);
			}
			$hrb = 0;
			$budgethours += $budgeted;
    		if ($budgeted) {
    			$hrb = sprintf("%01.2f",$budgeted);
    			$budgeted = number_format($budgeted, 2);    			
				echo "<td align=middle>$budgeted</td></tr>";
			} else {
				echo "<td align=middle>---</td></tr>";
			}
			fputs($csvfh, "$hra,$hrb\n");
		}
	}
	$actualhours = number_format($actualhours/60, 2);
	$budgethours = number_format($budgethours, 2);

	echo "<tr><th colspan=2>Total</th><th>$actualhours</th><th>$budgethours</th></tr>";
	echo "</table><p>";
	fputs($csvfh, "$actualhours,$budgethours\n");
	
####Project break down: Activity
	echo "<h2>Budgeted Project Activity Breakdown</h2>";
	fputs($csvfh, "\nBudgeted Project Activity Breakdown\n");
      	
	##budgeted activity
	for ($i=0; $i<$pctr; $i++) {
		$brief_code = $projinvolved[$i];
		$projcode_id = $projcodelist[$brief_code];
		$sql = "SELECT t2.date_start as tskdateS, t2.date_end as tskdateE, 
						t2.nomths as nomths, t2.tasks as tasks, t4.hours as hours
      	  		FROM timesheet.bgtfileidx as t1, timesheet.bgtfiletasks as t2,
      	  			 timesheet.bgtfileresource as t3, timesheet.bgtfileres_task_hr as t4
      	  		WHERE t1.projcode_id='$projcode_id' and t1.active='y' and 
      	  			t1.end_date>='$datefrom' and t1.begin_date<='$dateto' and      	  		        			
        			t2.bgtfileidx=t1.bgtfileidx and
        			t2.date_end>='$datefrom' and t2.date_start<='$dateto' and 
        			t3.email_name='$ename' and t3.bgtfileidx=t1.bgtfileidx and
        			t4.bgtrscidx=t3.bgtrscidx and t4.taskidx=t2.taskidx;"; 	
    		$result = mysql_query($sql);
    		include("err_msg.inc");
    		$noentry = 0;
    		$hrspod = 0;
    		$hrstotal = 0;
			while (list($tskdateS, $tskdateE, $nomths, $tasks, $hours) = mysql_fetch_array($result)) {
				if (!$noentry) {
					echo "<h3>$brief_code $timeframe</h3>";
					fputs($csvfh, "$brief_code\n");
					echo "<table border=1>";
					echo "<tr><th>Tasks</th><th>From</th><th>To</th><th>Month</th><th>Hours for the Period</th><th>Total Hours</th></tr>";
					fputs($csvfh, "Tasks,From,To,Month,Hours for the Period,Total Hours\n");
				}
				$noentry++;
				$hrsinmth = $hours/$nomths;
				$hrs = calhours($tskdateS, $tskdateE, $hrsinmth);
				$podhours = number_format($hrs, 2);
				$hrspod += $hrs;
				$hrstotal += $hours;
				echo "<tr><td>$tasks</td><td>$tskdateS</td><td>$tskdateE</td><td align=middle>$nomths</td>
					<td align=middle>$podhours</td><td align=middle>$hours</td></tr>";
				fputs($csvfh, "$tasks,$tskdateS,$tskdateE,$nomths,$podhours,$hours\n");
			}
			if ($noentry) {
				$hrspod = number_format($hrspod,2);
				$hrstotal = number_format($hrstotal,2);
				echo "<tr><th colspan=4>Sub Total</th>
					<th>$hrspod</th><th>$hrstotal</th></tr>";
				fputs($csvfh, ",,,,$hrspod,$hrstotal\n");
				echo "</table><p>";
			}
	}
	if (!$hrstotal) {
		echo "<h3><font color=#ff0000>No budgeted data available for $fname.</font></h3>";
	}
	fclose($csvfh);
?>
<hr><br><a href=#top><b>Back to top</b></a><br><br>
</html>
