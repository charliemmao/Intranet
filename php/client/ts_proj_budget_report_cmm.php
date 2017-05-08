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
if ($priv == "00" || $priv	==	'10') {
} else {
	exit;
}
$userstr	=	"?".base64_encode($userinfo);
echo "<h2 align=center><a id=top>Budget Report</a>";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2> [Refresh]</font></a>";
echo "<a href=\"ts_proj_budget_data.php$userstr\"><font size=2>[Goto Upload File Page]</font></a>";
echo "</h2><hr>";
//include("userstr.inc");

//#####################################################
$yr = date("Y");
$yfm = $yr-1;
if ($yfm<2001) {
	$yfm = 2001;
}
$yto = $yr + 1;

echo "<h2>Project Report</h2>";
$rpttype = "proj";
$php_file = $PHP_SELF;
include("ts_proj_budget_period_writing.inc");
/*
	if ($priv == "00") {
		$userstr	=	"?".base64_encode($userinfo."&proj=y&caseany=y");
		echo "<ul><li><a href=\"$PHP_SELF$userstr\"><b>Any Period</b></a></li></ul>";
	}
//*/

//#####################################################
	if ($priv == "00" || $email_name == "olc") {//
		echo "<hr><h2>Resource Allocation Report</h2>";
		$rpttype = "rsc";
		include("ts_proj_budget_period_writing.inc");
		/*
		if ($priv == "00") {
			$userstr	=	"?".base64_encode($userinfo."&rsc=y&caseany=y");
			echo "<ul><li><a href=\"$PHP_SELF$userstr\"><b>Any Period</b></a></li></ul>";
		}
		//*/
	}

	$sql = "SELECT projcode_id as pid, brief_code as bc
       FROM timesheet.projcodes 
       WHERE brief_code!='RLA-OHD-Time_in_Lieu'
       ORDER BY brief_code;";
    $result = mysql_query($sql);
    include("err_msg.inc");
    $noproj = 0; 
    while (list($pid, $bc) = mysql_fetch_array($result)) {
    	if ($bc) {
    		$projcodelist[$noproj][0] = $pid;
    		$projcodelist[$noproj][1] = $bc;
    		$projcodelist[$pid][2] = $bc;
    		$noproj++;
       }
   	}

	$sql = "SELECT email_name as en, first_name as fn, last_name as ln, tsentry
      	FROM timesheet.employee
       ORDER BY fn;";
       //WHERE tsentry='y'
   	$result = mysql_query($sql);
   	include("err_msg.inc");
   	$nostaff = 0;
   	while (list($en, $fn, $ln, $tsentry) = mysql_fetch_array($result)) {
    if ($en) {
        	$staffname[$nostaff][0] = $en;
        	$staffname[$nostaff][1] = "$fn $ln";
        	$staffname[$nostaff][2] = $tsentry;
        	$nostaff++;
        }
    }

if ($caseany) {
	include("ts_proj_budget_report_anyperiod.inc");
}

################################################################
//Prepare date range
	if ($caseany) {
		$mthstart = formatmonth($mthstart);
		$mthend = formatmonth($mthend);
		$datefrom = "$yearstart-$mthstart-01";
		$nodays = finddaysinFeb($mthend, $yearend);
		$dateto = "$yearend-$mthend-$nodays";
	} else {
		$datefrom = $date1;
		$dateto = $date2;
	}

################################################################
#	PROJECT REPORT
	if ($proj) {
		$rptstr = "Project Report";
	} elseif ($rsc) {
		$rptstr = "Resource Report";
	}
	
	$title = "$rptstr Period from $datefrom to $dateto";
	if ($proj || $rsc) {
		reportline1($title);
		findmonthlist($datefrom, $dateto);
		for ($i=0; $i<$mthno; $i++) {
			$podmthno = $mthno;
			$podmonthlist[$i] = $monthlist[$i];
		}
		
		$title0 = $email_name."_".ereg_replace(" ","_", $title).".csv";
		$rptweburl = "/report/$title0";
		$rptserverfile = "/usr/local/apache/htdocs/report/$title0";
		//echo "<center><a href=\"$rptweburl\"><b>Download CSV file</b></a></center><br><br>";
		echo "<a href=\"$rptweburl\"><b>Download CSV file</b></a><br><br>";
		$csvfh = fopen($rptserverfile, "w");
		fputs($csvfh, $title."\n");
	}

//casethismth		caselastmth	caseyear	caseany
	if ($proj && $caseany) {
		//remove unselected projects
		if ($projectlist[0] != "all") {
    		for ($i=0; $i<$noproj; $i++) {
    			$selected = "";
    			for ($j=0; $j<$noprojsel; $j++) {
    				if ($projectlist[$j] == $projcodelist[$i][0]) {
     					//echo $projectlist[$j]."<br>";
   						$selected = 1;
    					break;
    				}
        		}
        		if (!$selected) {
        			$projcodelist[$i][0] = 0;
        		}
        	}
    	}
	} elseif ($rsc && $caseany) {
		//remove unselected staff
  		if ($stafflist[0] != "all") {
    		for ($i=0; $i<$nostaff; $i++) {
    			/*
    			echo $staffname[$i][0];
    			for ($j=0; $j<$nosatffsel; $j++) {
    				echo " ".$stafflist[$j];
      	 		}
      	 		echo "<br>";
      	 		//*/
    			$selected = "";
    			for ($j=0; $j<$nosatffsel; $j++) {
    				if ($stafflist[$j] == $staffname[$i][0]) {
    					$selected = 1;
    					//echo $stafflist[$j]."<br>";
    					break;
    				}
        		}
        		if (!$selected) {
        			$staffname[$i][0] = 0;
        		}
        	}
    	}
	}

	if ($datefrom && $dateto) {	//list month for report period
		$podstr = "($datefrom to $dateto)";
		//echo "List $datefrom to $dateto<br><br><br>";
		findMthListForPod($datefrom, $dateto);
		$RptMthCtr = $mthInPod;
		for ($i=0; $i<$mthInPod; $i++) {
			$RptMthList[$i] = $podMthList[$i];
			//echo $i." -:".$RptMthList[$i]."<br>";
		}
	}
	
$tabbg = " bgcolor=#D5DEFB";
$rowbg1 = " bgcolor=#D5BAF0";
$rowbg2 = " bgcolor=#D5DEFB";

if ($proj) {
	$allprojtotalactual = 0;
	$allprojtotalbudget = 0;
	echo "<h3>Section A: Comparison of Actual and Budgeted Hours</h3>";
	echo "<table border=1 $tabbg>";// align=center
	echo "<tr><th rowspan=2>No</th><th rowspan=2>Project</th><th colspan=2>Hours</th></tr>";
		fputs($csvfh, "No,Project,Actual Hours,Budgeted Hours\n");
	echo "<tr><th>Actual</th><th>Budgeted</th></tr>";
	
	$noctr = 0;
	for ($i=0; $i<$noproj; $i++) {
		$projcode_id = $projcodelist[$i][0];
		$brief_code = $projcodelist[$i][1];
		if ($projcode_id) {
			##actual hours
			$sql = "SELECT sum(t2.minutes) as oneprojactual
	        	FROM timesheet.entry_no as t1, timesheet.timedata as t2
   		     	WHERE (t1.yyyymmdd>='$datefrom' and t1.yyyymmdd<='$dateto') 
       	 		and t1.entry_no=t2.entry_no and t2.brief_code='$brief_code';";
	    	$result = mysql_query($sql);
   		 	include("err_msg.inc");
    		list($oneprojactual) = mysql_fetch_array($result);
       	//echo $sql."<br><br><br>";
			##budgeted hours
			/* table: bgtfileidx. columns: bgtfileidx, projcode_id, description, client, 
          		  begin_date, end_date, preparedby, uploaddate, active,  */
       	/* table: bgtfiletasks. columns: taskidx, bgtfileidx, m_stasks, tasks, 
       	     date_start, date_end, nomths, hours,  */           
			$sql = "SELECT t2.hours as hours, t2.nomths as nomths,
					t2.date_start as date1, t2.date_end as date2
      	  		FROM timesheet.bgtfileidx as t1, timesheet.bgtfiletasks as t2
      	  		WHERE t1.projcode_id='$projcode_id' and t1.active='y' and 
        			t1.end_date>='$datefrom' and t1.begin_date<='$dateto' and
        			t1.bgtfileidx=t2.bgtfileidx and t2.m_stasks='s' and
        			t2.date_end>='$datefrom' and t2.date_start<='$dateto';";
    		$result = mysql_query($sql);
    		include("err_msg.inc");
    		$oneprojbudget = 0;
    		while (list($hours, $nomths, $date1, $date2) = mysql_fetch_array($result)) {
    			if ($hours>0) {
      				$hourspermth = $hours/$nomths;
       			//echo $sql."<br>";
       			if ($RptMthCtr == 1) {
    					$oneprojbudget += $hourspermth;
    				} else {
    					$mulmth = NoMthInPeriod($date1, $date2);
    					$oneprojbudget += $hourspermth*$mulmth;
    					/*
    					echo "Report period: $datefrom to $dateto ($RptMthCtr)<br>";
     					echo "Sub task period:  $date1 to $date2 ($nomths). budget hours= $hours. $hourspermth hrs/month<br>";
     					echo "Months in report period: $mulmth<br>";
    					exit;
    					//*/
					}
    			}
			}
    		if ($oneprojactual || $oneprojbudget) {
    			$noctr++;
    			$allprojtotalactual += $oneprojactual;
    			$allprojtotalbudget += $oneprojbudget;
     			$no1 = sprintf("%01.2f",$oneprojactual/60.0);
    			$no2 = sprintf("%01.2f",$oneprojbudget);
    			
   				$oneprojactual= number_format($oneprojactual/60.0, 2);
    			$oneprojbudget = number_format($oneprojbudget, 2);
    			$brief_code = ereg_replace("_"," ",$brief_code);
				echo "<tr><td>$noctr</td><td>$brief_code</td><td align=right>$oneprojactual</td>
					<td align=right>$oneprojbudget</td></tr>";
					fputs($csvfh, "$noctr,$brief_code,$no1,$no2\n");
			} else {
				if ($projforanyperiod) {
					$noctr++;
					echo "<tr><td>$noctr</td><td>$brief_code</td><td align=right>---</td><td align=right>---</td></tr>";
					fputs($csvfh, "$noctr,$brief_code,---,---\n");
				}
			}
		}
    }
    $no10 = sprintf("%01.2f",$allprojtotalactual/60.0);
    $no20 = sprintf("%01.2f",$allprojtotalbudget);

    $no1 = number_format($no10, 2);
    $no2 = number_format($no20, 2);
    echo "<tr><th colspan=2>Total</th><th>$no1</th><th>$no2</th></tr>";
		fputs($csvfh, ",Total,$no10,$no20\n");
    echo "</table><p>";
    
    ###################################################
	echo "<hr><h3>Section B: Budgeted Project Activities</h3>";
	$sql = "SELECT bgtfileidx, projcode_id, description, client, begin_date, end_date 
        FROM timesheet.bgtfileidx 
        WHERE begin_date<='$dateto' and end_date>='$datefrom' and active='y'
        ORDER BY bgtfileidx DESC;";
             
	$result = mysql_query($sql);
   	include("err_msg.inc");
	$nobdgtctr = 0;
   //echo "$sql<br>";
   while (list($bgtfileidx, $projcode_id, $description, $client, $begin_date, $end_date) = mysql_fetch_array($result)) {
   		$bdgtfound[$nobdgtctr]["bid"] = $bgtfileidx;
   		$bdgtfound[$nobdgtctr]["pid"] = $projcode_id;
   		$bdgtfound[$nobdgtctr]["des"] = $description;
   		$bdgtfound[$nobdgtctr]["cli"] = $client;
   		$bdgtfound[$nobdgtctr]["dtb"] = $begin_date;
   		$bdgtfound[$nobdgtctr]["dte"] = $end_date;
   		$nobdgtctr++;
   		//echo "$nobdgtctr<br>$bgtfileidx<br>$projcode_id<br>$description<br>$client<br>$begin_date<br>$end_date<br><br>";
   }
	if ($nobdgtctr) {
		if ($nobdgtctr > 1) {
			echo "<h4><font color=#0000ff size=6>$nobdgtctr</font> project budget data have been found for this period $podstr.</h4><p>";
			fputs($csvfh, "\n\n\n$nobdgtctr project budget data have been found for this period $podstr.\n");
		} else {
			echo "<h4><font color=#0000ff>Found $nobdgtctr project budget data for this period $podstr.</font></h4><p>";
			fputs($csvfh, "\n\n\n$nobdgtctr project budget data has found for this period $podstr.\n");
		}

		echo "<table border=1 $tabbg>";
		echo "<tr><th>No</th><th>Project</th><th>Total</th><th>Period</th><th>Date Start</th><th>Date End</th><th>Description</th></tr>";
		fputs($csvfh, "\nNo,Project,Total,Period,Date Start,Date End,Description\n");
		$ctr = " align=middle";
		$right = " align=right";
		for ($i=0; $i<$nobdgtctr; $i++) {
			$No = $i+1;
			$bgtfileidx = $bdgtfound[$i]["bid"];
			$projcode_id = $bdgtfound[$i]["pid"];
			$Project = $projcodelist[$projcode_id][2];
			$Description = $bdgtfound[$i]["des"];
			$Client = $bdgtfound[$i]["cli"];
			$DateStart = $bdgtfound[$i]["dtb"];
			$DateEnd = $bdgtfound[$i]["dte"];
			echo "<tr$rowbg1><th>$No</th><td><b>$Project</b></td><td$ctr><b>
				&nbsp;</b></td><td>&nbsp;</td><td$ctr>$DateStart</td><td$ctr>$DateEnd</td><td><b>$Description</b></td></tr>";
			fputs($csvfh, "$No,$Project,,,,$DateStart,$DateEnd,$Description\n");
			
			//find milestones
			$sql = "SELECT mlstone, date 
        		FROM timesheet.bgtfilemlstone 
        		WHERE bgtfileidx='$bgtfileidx' and date<='$dateto' and date>='$datefrom';";
			
			$result = mysql_query($sql);
   			include("err_msg.inc");
			$mstonectr=0;
			while (list($mlstone, $date) = mysql_fetch_array($result)) {
				$mstone[$mstonectr][0] = $mlstone;
				$mstone[$mstonectr][1] = $date;
				$mstonectr++;
			}

			//find tasks
			$sql = "SELECT m_stasks, tasks, date_start, date_end, hours, nomths
        		FROM timesheet.bgtfiletasks 
        		WHERE bgtfileidx='$bgtfileidx' and 
            		date_start<='$dateto' and date_end>='$datefrom';";
			//echo "$sql<br>";
			
			$result = mysql_query($sql);
   			include("err_msg.inc");
			$no = mysql_num_rows($result);
			if (!$no) {
				echo "<tr><td>&nbsp;</td><td colspan=5>No budgeted activity.</td></tr>";
				fputs($csvfh, "No budgeted activity\n");
			} else {
				$tolrow = $mstonectr + $no;
				echo "<tr><td rowspan=$tolrow>&nbsp;</td>";
				$rowctr=0;
 				//echo "$no <br>$sql<br>";
				while (list($m_stasks, $tasks, $date_start, $date_end, $hours, $nomths) = mysql_fetch_array($result)) {
					$hours = number_format($hours, 2);
					$hours = ereg_replace(",", "", $hours);
					//echo "$m_stasks, $tasks, $date_start, $date_end, $hours<br>";
					if ($rowctr) {
						echo "<tr>";
					}
					$rowctr++;
					//$tstr = " $rowctr-$no-$tolrow";
					if ($m_stasks == "m") {
						echo "<td><b>Main Task$tstr</b></td>";
						$tmp = "<td><b>";
						$tmp2="</b>";
						fputs($csvfh, ",Main Task,");
					} else {
						echo "<td align=right>Sub Task$tstr</td>";
						$tmp = "<td>";//$right
						$tmp2="";
						fputs($csvfh, ",    Sub Task,");
					}

					$hourspermth = $hours/$nomths;
       			if ($RptMthCtr == 1) {
       				$hrsforpod = $hourspermth;
       			} else {
    					$mulmth = NoMthInPeriod($date_start, $date_end);
    					$hrsforpod = $hourspermth*$mulmth;
					}

					$tasks = ereg_replace(",", "_", $tasks);
					$hrsforpod = number_format($hrsforpod , 2);
					$hrsforpod = ereg_replace(",", "", $hrsforpod);
					$hrstr = $hours;
					if ($m_stasks == "m") {
						if ($hrpod == 0) {
							$hrstr = "&nbsp;</td><td>&nbsp;";
						} else {
							$hrstr .= "</td><td>&nbsp;";
						}
					} else {		
						if ($hrstr == 0) {
							$hrstr = "&nbsp;</td><td>&nbsp;";
						} else {
							$hrstr .= "</td><td$right>$hrsforpod";
						}
					}
					echo "<td$right>$hrstr</td><td$ctr>$date_start</td><td$ctr>$date_end</td>$tmp$tasks$tmp2</td></tr>";
					if ($m_stasks == "m") {
						fputs($csvfh, "\"$hours\",,$date_start,$date_end,$tasks\n");
					} else {
						fputs($csvfh, "\"$hours\",\"$hrsforpod\",$date_start,$date_end,$tasks\n");
					}
				}
				if ($mstonectr) {
					for ($ii=0; $ii<$mstonectr; $ii++) {
						$ml = $mstone[$ii][0];
						$dstr = substr($mstone[$ii][1],0,7);
						echo "<tr><td colspan=4 align=middle><b>Milestone</b></td><td align=middle>$dstr</td>
							<td align=middle><b>$ml</b></td></tr>";
						fputs($csvfh, ",Milestone,,,$dstr,$ml\n");
					}
				}
				flush();
			}			
		}
		echo "</table><p>";
	} else {
		echo "<h3><font color=#ff0000>No project budget data found for this period $podstr.</font></h3><p>";
		fputs($csvfh, "\n\n\nNo project budget data found for this period $podstr.\n");
	}
	fclose($csvfh);
}

//#######################################################################
if ($rsc) {	//resource allocation
	$allstafftotalactual  = 0;
	$allstafftotalbudget = 0;
	echo "<table border=1 $tabbg>";// align=center
	echo "<tr><th rowspan=2>No</th><th rowspan=2>Staff</th><th colspan=2>Hours</th></tr>";
	echo "<tr><th>Actual</th><th>Budgeted</th></tr>";
	fputs($csvfh, "No,Staff,Actual Hours,Budgeted Hours\n");
	$noctr = 0;
	
	for ($i=0; $i<$nostaff; $i++) {
		$ename = $staffname[$i][0];
		$fname = $staffname[$i][1];
		if ($ename) {
			##actual hours
			$sql = "SELECT sum(t2.minutes) as oneprojactual
	        	FROM timesheet.entry_no as t1, timesheet.timedata as t2
   		     	WHERE (t1.yyyymmdd>='$datefrom' and t1.yyyymmdd<='$dateto') 
       	 		and t1.entry_no=t2.entry_no and t1.email_name='$ename'
       	 		and t2.brief_code!='RLA-OHD-Time_in_Lieu';";
	    	$result = mysql_query($sql);
   		 	include("err_msg.inc");
    		list($oneprojactual) = mysql_fetch_array($result);
       	//echo $sql."<br><br>$oneprojactual<br>";
    		
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
    		$oneprojbudget = 0;
			while (list($tskdateS, $tskdateE, $nomths, $hours) = mysql_fetch_array($result)) {
				//$casethismth || $caselastmth || $caseyear || $caselastyear || $caseany)
				$hrsinmth = $hours/$nomths; 
				if ($casethismth || $caselastmth) {
					$oneprojbudget += $hrsinmth;
				} else {
					$thispod = calhours($tskdateS, $tskdateE, $hrsinmth);
					$oneprojbudget += $thispod;
					//echo "$tskdateS, $tskdateE, $nomths, $hours, $thispod<br>";
				}
			}
 			//echo "$oneprojbudget<br>";
   		//list($oneprojbudget) = mysql_fetch_array($result);
    		if ($oneprojactual || $oneprojbudget) {
    			$noctr++;
    			$allstafftotalactual  += $oneprojactual;
    			$allstafftotalbudget += $oneprojbudget;
     			$no1 = sprintf("%01.2f",$oneprojactual/60.0);
    			$no2 = sprintf("%01.2f",$oneprojbudget);
    			
   				$oneprojactual= number_format($oneprojactual/60.0, 2);
    			$oneprojbudget = number_format($oneprojbudget, 2);
    			$extra = "&datefrom=$datefrom&dateto=$dateto&ename=$ename&fname=$fname&rpttitle=$title";
    			$userstr	=	"?".base64_encode($userinfo.$extra);

				echo "<tr><td>$noctr</td><td>
					<a href=\"ts_bgt_stafftime.php$userstr\" target=\"_blank\">$ename</a></td>
					<td align=right>$oneprojactual</td>
					<td align=right>$oneprojbudget</td></tr>";
					fputs($csvfh, "$noctr,$ename,$no1,$no2\n");
			} else {
				if ($projforanyperiod) {
					$noctr++;
					echo "<tr><td>$noctr</td><td>$fname</td><td align=right>---</td><td align=right>---</td></tr>";
					fputs($csvfh, "$noctr,$fname,---,---\n");
				}
			}
		}
    }
    $no10 = sprintf("%01.2f",$allstafftotalactual /60.0);
    $no20 = sprintf("%01.2f",$allstafftotalbudget);

    $no1 = number_format($no10, 2);
    $no2 = number_format($no20, 2);
    echo "<tr><th colspan=2>Total</th><th>$no1</th><th>$no2</th></tr>";
		fputs($csvfh, ",Total,$no10,$no20\n");
    echo "</table><p>";
	fclose($csvfh);
}

function NoMthInPeriod($date1, $date2) {
global $RptMthList;
global $RptMthCtr;
global $podMthList;
global $mthInPod;
	$noMthInpod = 0;
    findMthListForPod($date1, $date2);
    for ($i=0; $i<$mthInPod; $i++) {
    	for ($j=0; $j<$RptMthCtr; $j++) {
			if ($podMthList[$i] == $RptMthList[$j]) {
				$noMthInpod += 1;
			}
		}
	}
	/*
	echo "Enter NoMthInPeriod function: $date1, $date2<br>";
	echo "RptMthCtr= $RptMthCtr<br>";
	echo "mthInPod = $mthInPod<br>";
	echo "Enter NoMthInPeriod: $date1, $date2<br>";
	echo "mthInRPTPod = $noMthInpod<br>";
	echo "exit NoMthInPeriod function<br><br>";
	//*/
	return $noMthInpod;
}

function findMthListForPod($date1, $date2) {
	global $podMthList;
	global $mthInPod;
	$podMthList[0] = $date1;
	$podMthList[1] = $date2;
	$mthInPod = 2;
    $mth1 = rlagetmonth($date1);
    $mth2 = rlagetmonth($date2);
    
    $yr1 = rlagetyear($date1);
    $yr2 = rlagetyear($date2);
    
    $NoYears = $yr2 - $yr1;
    $mthInPod = 0;
    $NoYears = 0;
    for ($i = $mth1; $i<=12; $i++) {
    	$mm=$i;
    	if ($i<10) {
    		$mm="0$i";
    		$mm = ereg_replace("00","0",$mm);
    	}
        $podMthList[$mthInPod] = "$yr1-$mm-01";
        //echo $mthInPod.": ".$podMthList[$mthInPod]."<br>";
        $mthInPod = $mthInPod + 1;
        if ($yr1 == $yr2 && $mm == $mth2) {
        	break;
        }
    }
    If ($yr2 - $yr1 > 0) {
        for ($j = $yr1 + 1; $j<=$yr2 - 1; $j++) {
    		for ($i = 1; $i<=12; $i++) {
    			$mm=$i;
    			if ($i<10) {
    				$mm="0$i";
    				$mm = ereg_replace("00","0",$mm);
    			}
        		$podMthList[$mthInPod] = "$j-$mm-01";
         		//echo $mthInPod.": ".$podMthList[$mthInPod]."<br>";
             $mthInPod = $mthInPod + 1;
           }
            $mthnoList[$NoYears][2] = $mthInPod - 1;
        }
    }
    //echo "$mth1 -m- $mth2<br>";
    If ($yr2 > $yr1) {
        for ($i=1; $i<=$mth2; $i++) {
    		$mm=$i;
    		if ($i<10) {
    			$mm="0$i";
    			$mm = ereg_replace("00","0",$mm);
    		}
        	$podMthList[$mthInPod] = "$yr2-$mm-01";
        	//echo $mthInPod.": ".$podMthList[$mthInPod]."<br>";
          $mthInPod = $mthInPod + 1;
        }
    }
}
?>
<hr><br><a href=#top><b>Back to top</b></a><br><br>
</html>
