<html>
<?php
$chcat4[0] = "A";
$chcat4[1] = "B";
$chcat4[2] = "C";
$chcat4[3] = "D";
function makeselectionlist($from, $to, $default) {
	for ($ii=$from; $ii<=$to; $ii++) {
		if ($ii == $default) {
			echo "<option selected>$ii";
		} else {
			echo "<option>$ii";
		}
	}
	echo "</option></select>";
}
echo "<hr>";
echo "<form mehtod=post>";
echo "<table border=1><tr><td valign=top>";

echo "<table>";
if ($rscanyperiod) {
	$frm_str	=	base64_encode($userinfo."&rscforanyperiod=y");
} elseif ($projanyperiod) {
	$frm_str	=	base64_encode($userinfo."&projforanyperiod=y");
}
echo "<input type=hidden name=frm_str value=\"$frm_str\">";
echo "<tr><th align=left colspan=4><font size=4 color=#0000ff>Select Report Period</font></th></tr>";
echo "<tr><th align=left>Date to Start</th><td>Month&nbsp;<select name=mthstart>";
	if (!$mthstart) {
		$yearstart = date("Y");
		$mthstart = date("n")-1;
		if ($mthstart == 0) {
			$mthstart=12;
			$yearstart = $yearstart - 1;
		}
	}
	makeselectionlist(1, 12, $mthstart); 
echo "</td>";
echo "<td>Year&nbsp;<select name=yearstart>";
	makeselectionlist(2000, date("Y"), $yearstart); 
echo "</td></tr>";
echo "<tr><th align=left>Date to End</th><td>Month&nbsp;<select name=mthend>";
	if (!$mthend) {
		$yearend= date("Y");
		$mthend= date("n");
	}
	makeselectionlist(1, 12, $mthend); 
echo "</td>";
echo "<td>Year&nbsp;<select name=yearend>";
	makeselectionlist(2000, date("Y"), $yearend); 
echo "</td></tr></table>";
echo "</td><td valign=top><table>";

//For project or for resources
if ($projanyperiod) {
	if ($priv == "00" || $priv == "10") {
		echo "<tr><th align=left><font size=4 color=#0000ff>Project List ($noproj)</font></th></tr>";
		echo "<tr><td><select name=\"projectlist[]\" multiple>";
       echo "<option>---Select Project Code(s)---";
    	for ($i=0; $i<$noproj; $i++) {
        		echo "<option value=".$projcodelist[$i][0].">".$projcodelist[$i][1];
    	}
    }
} else {
	if ($priv == "00" || $priv == "10") {
		echo "<tr><th align=left><font size=4 color=#0000ff>Staff List ($nostaff)</font></th></tr>";
		echo "<tr><td><select name=\"stafflist[]\" multiple>";
       echo "<option>---Select Staff---";
    	for ($i=0; $i<$nostaff; $i++) {
        		echo "<option value=".$staffname[$i][0].">".$staffname[$i][1];
    	}
    }
}

echo "<option></select></td></tr>";
echo "</table></td></tr><tr><th colspan=2><button type=submit name=budgetreportanyperiod><b>Create Report</b></th>";
echo "</tr></table>";
echo "</form><p>";

################################################################
#	REPORT SECTION
if ($budgetreportanyperiod) {
	//echo "<hr>";	
	##check number of projects are selected
	$noproj = count($projectlist);
	if ($noproj == 0) {
		$proj_no = "y0";
	} elseif ($noproj == 1) {
		if (!$projectlist[0]) {
			$proj_no = "y1";
		}
	}
	if ($proj_no) {
		echo "<h3>No project has been selected.</h3><p><hr>";
		exit;//$proj_no<br>projectlist[0] = $projectlist[0]<br>
	}
	
	if ($mthstart<10) {
		$mthstart= "0".$mthstart;
	}
	$podstart = "$yearstart-$mthstart-01";
	if ($mthend<10) {
		$mthend = "0".$mthend;
	}
	$d = $daysinmth[$mthend];
	$podend = "$yearend-$mthend-$d";
	//		$userstr	=	"?".base64_encode($userinfo."&reportforanyperiod=y");
	
   $activityandbudgetsummary = "y";
   
	##################	activity and budget summary activity
	if ($activityandbudgetsummary) {
		$thismonth = date("F Y");
		reportline1("Project Summary for Period from $podstart to $podend.");
		//echo "Start $podstart<br>";
		//echo "End $podend<br>";
		$sql = "SELECT t1.budgetfileid, t1.email_name as ename, t1.phaseno, 
            	t1.budgetfile, t1.dateinrow, t1.budgetstart, t1.budgetend, 
            	t1.actualstart, t1.actualend,
            	t2.brief_code
        	FROM timesheet.projbudgetfile as t1, timesheet.projcodes as t2
        	WHERE (t1.actualstart<='$podstart' and t1.actualend>='$podend') and t1.neworold='y'
        			and t1.projcode_id=t2.projcode_id 
        	ORDER BY t2.brief_code;";
		//echo "$sql<br><br>";
    	$result = mysql_query($sql);
    	include("err_msg.inc");
    	$no = mysql_num_rows($result);
    	
		$allprojtotaltimebudget = 0;
		$allprojtotaltimeactual = 0;
       $allprojtotalcostbudget = 0;
       $allprojtotalcostactual = 0;
		
		$i=0;
		echo "<table border=1>";
   		while (list($budgetfileid, $ename, $phaseno, $budgetfile, 
        	$dateinrow, $budgetstart, $budgetend, 
        	$actualstart, $actualend, $brief_code) = mysql_fetch_array($result)) {
        	$selected = "";    	
 			for ($j=0; $j<count($projectlist); $j++) {
 				if ($projectlist[$j] == $budgetfileid) {
 					$selected = "y";
 					$i++;
					break;
 				}
 			}
###################################################show one project start
			if ($selected == "y") {
        		$oneprojtotaltimebudget[$i] = 0;
        		$oneprojtotaltimeactual[$i] = 0;
        		$oneprojtotalcostbudget[$i] = 0;
        		$oneprojtotalcostactual[$i] = 0;			
			
        		echo "<tr><th align=left colspan=7>Project $i: $brief_code, Phase $phaseno</th></tr>";
        		echo "<tr><th>Leader</th><th>Start</th><th>End</th>
        			<th>Activity <font color=#0000ff>(Active during the period)</font></th>
        			<th>Budget (hrs/cost)</th><th>Actual Time (hr)</th><th>Comments</th></tr>";        
        		echo "<tr><td>$ename</td>";
        		echo "<td>$budgetstart<br>($actualstart)</td>";
        		echo "<td>$budgetend<br>($budgetend)</td>";
        		
        		# split budget file into rows
        		$budgetdataarray = explode("<br>",$budgetfile);
        		$nolines = count($budgetdataarray);
        		
        		# split budget file rows into columns
        		for ($j=0; $j<$nolines; $j++) {
        			$budgetdatarows[$j] = explode(",",$budgetdataarray[$j]);
        			//echo "$j: ".$budgetdatarows[$j][0]."<br>";
        		}

        		# find tasks starting row
        		for ($j=0; $j<$nolines; $j++) {
        			if ($budgetdatarows[$j][0]) {
        				$taskrowstartat = $j;
        				//echo "taskrowstartat = $taskrowstartat<br>";
        				break;
        			}
        		}

        		# find tasks ending row
        		for ($j=$taskrowstartat; $j<$nolines; $j++) {
        			if (!$budgetdatarows[$j][0]) {
        				$taskrowendat = $j - 1;
        				//echo "taskrowendat = $taskrowendat<br>";
        				break;
        			}
        		}
				$budgettimeinrow = $taskrowendat + 1;
				$budgetcostinrow = $budgettimeinrow + 1;				
				
        		# split date row
        		$datearray = $budgetdatarows[$dateinrow];	#explode(",",$budgetdataarray[$dateinrow]);
        		
         ###########################################################
      			#find time schedule starting column
         		$datestartfromcol  = 0;
        		for ($j=0; $j<count($datearray); $j++) {
        			if ($datearray[$j]) {
        				$datestartfromcol  = $j;
        				break;
        			}
 				} 
        		
      			#find start and end column in budget date array for the period
        		//$podstart		
        		$dateno = ereg_replace("-", "", $podstart);
        		for ($j=0; $j<count($datearray); $j++) {
        			if ($datearray[$j]) {
        				$mthdayno = ereg_replace("-", "", $datearray[$j]);
        				if ($mthdayno >= $dateno) {
        					$periodstartcolNO = $j;
        					break;
        				} 
        			}
        		}
        		//$podend
        		$dateno = ereg_replace("-", "", $podend);
        		for ($j=$periodstartcolNO; $j<count($datearray); $j++) {
        			if ($datearray[$j]) {
        				$mthdayno = ereg_replace("-", "", $datearray[$j]);
        				//echo "$mthdayno<br>";
        				if ($mthdayno >= $dateno) {
        					$periodendcolNO = $j-1;
        					break;
        				} 
        			}
        		}
        		/*
  					echo $budgetdataarray[$dateinrow]." <br>";
        			echo "datestartfromcol 	$datestartfromcol  ".$datearray[$datestartfromcol ]."<br>";
       			echo "$podstart	$podend	<br>";
        			echo "periodstartcolNO = $periodstartcolNO ".	$datearray[$periodstartcolNO] ."	<br>";
        			echo "periodendcolNO   = $periodendcolNO ".	$datearray[$periodendcolNO] ."	<br>";
				//*/
				
				#find tasks during the period
        		$taskinperiod = "";
        		for ($k=$taskrowstartat; $k<=$taskrowendat; $k++) {
        			$left = "";
        			$right= "";
        			$thisrow  = $budgetdatarows[$k][0];
        			for ($m=1; $m<$datestartfromcol; $m++) {
        				$tmp = trim($budgetdatarows[$k][$m]);
        				$thisrow .= ": ".$tmp;
        			}
        			for ($j=$periodstartcolNO; $j<=$periodendcolNO; $j++) {
         				$tmp = trim($budgetdatarows[$k][$j]);
       				if ($tmp) {	
        					$left= "<b><font color=#0000ff>";
        					$right= "</font></b>";
        					break;
						}
        			}
         			$taskinperiod .= $left.$thisrow.$right."<br>";;
       		}		
         		echo "<td>$taskinperiod</td>";
         		
				#find total budgeted time and cost during the period
        		for ($j=$periodstartcolNO; $j<=$periodendcolNO; $j++) {
        			$bhour = trim($budgetdatarows[$budgettimeinrow][$j])*$factormthtohours;
        			$oneprojtotaltimebudget[$i] = $oneprojtotaltimebudget[$i] + $bhour;
        			
        			$bcost = 1000.0*trim($budgetdatarows[$budgetcostinrow][$j]);
         			$oneprojtotalcostbudget[$i] = $oneprojtotalcostbudget[$i] + $bcost;
        			//echo $datearray[$j]."; month=".$budgetdatarows[$budgettimeinrow][$j]. 
        			//	"; hours=$bhour"."; bcost=$bcost <br>";
       		}
				#$taskrowstartat	$taskrowendat	$budgettimeinrow	$budgetcostinrow
        		echo "<td align=middle>".
        			number_format($oneprojtotaltimebudget[$i], 2)." hours<br>$".
        			number_format($oneprojtotalcostbudget[$i], 2)."<br></td>";
        		
        ###########################################################
        		#find actual hours used for the period
        		$sqlsub = "SELECT t1.email_name as ename, sum(t2.minutes) 
        			FROM timesheet.entry_no as t1, timesheet.timedata as t2
        			WHERE t1.yyyymmdd>='$podstart' and t1.yyyymmdd<='$podend'
        				and t2.brief_code='$brief_code' and t1.entry_no=t2.entry_no
        			GROUP BY ename
        			ORDER BY ename;";
        		//echo "$sqlsub<br><br>";
    			$resultsub = mysql_query($sqlsub);
    			include("err_msg.inc");
        		echo "<td><table>";
	        		
        		while (list($ename, $minutes) = mysql_fetch_array($resultsub)) {
        			$abcd = $chargecode["$ename"];
        			
        			# time(hr)
        			$hr = number_format($minutes/60.0, 2);
        			$projcathrs[$i][$abcd] = $projcathrs[$i][$abcd] + $hr;	#	in charge category by project
         			$allprojcathrs[$abcd] = $allprojcathrs[$abcd] + $hr;	#	in charge category for all proj
         			
        			# cost($)
        			$ch = $hr * $chargerate["$ename"];
        			$projcatcost[$i][$abcd] = $projcatcost[$i][$abcd] + $ch;			#	in charge category by project
        			$allprojcatcost[$abcd] = $allprojcatcost[$abcd] + $ch;				#	in charge category by all project
        			$oneprojtotalcostactual[$i] = $oneprojtotalcostactual[$i] + $ch; 	#	charge no category for all project
        			$oneprojtotaltimeactual[$i] = $oneprojtotaltimeactual[$i] + $hr;	#	hour no category for all proj
        			
        			echo "<tr><td>$ename</td><td align=right>$hr</td><td align=right>@$abcd</td></tr>";
        		}
        		
        		# stat for all projects
       		$allprojtotaltimebudget = $allprojtotaltimebudget + $oneprojtotaltimebudget[$i];
				$allprojtotaltimeactual = $allprojtotaltimeactual + $oneprojtotaltimeactual[$i];
       		$allprojtotalcostbudget = $allprojtotalcostbudget + $oneprojtotalcostbudget[$i];
       		$allprojtotalcostactual = $allprojtotalcostactual + $oneprojtotalcostactual[$i];
     		
        		echo "<tr><th colspan=4><hr></th>";

        		for ($j=0; $j<4; $j++) {
        			$abcd = $chcat4[$j];
        			echo "<tr><th>@$abcd</th><td align=right>".
        				number_format($projcathrs[$i][$abcd],2)."</td>".
        				"<td align=right>(".number_format(100*$projcathrs[$i][$abcd]/$oneprojtotaltimeactual[$i], 2)."%)</td>".
        				"<td align=right>".number_format($projcatcost[$i][$abcd],2)."</td>".
        			"</tr>";
        		}
        		echo "<tr><th colspan=4><hr></th>";
        		echo "<tr><th>Sub</th><th align=right>".number_format($oneprojtotaltimeactual[$i], 2)."</th>";
        		echo "<th align=right>(100.00%)</th>";
        		echo "<th align=right>".number_format($oneprojtotalcostactual[$i], 2)."</th>";
        		echo "</tr>";
        		echo "</table></td>";
        		
        		echo "<td align=right>".
        		number_format(100*$oneprojtotaltimeactual[$i]/$oneprojtotaltimebudget[$i], 2)."%</td>";
        		echo "</tr>";
			}
##################################################show one project end
    	}
    	
    	#	Summary section
    	echo "<tr><th colspan=7><hr></th></tr>";
    	echo "<tr><th align=left colspan=2>Total (All Projects)</th><th colspan=2>Budget</th><th colspan=2>
    		Actual</th><th>Completed By</th></tr>";
    	echo "<tr><th align=right colspan=2>Time (Hours)</th><th colspan=2>".
    		number_format($allprojtotaltimebudget ,2);    		
    		#budgeted hours break down by staff category: data are not available
    	echo "</th><th colspan=2 align=middle>";
     		#actual hours break down by staff category
    		echo "<table>";
        	for ($j=0; $j<4; $j++) {
        		$abcd = $chcat4[$j];
        		echo "<tr><th>@$abcd</th><td align=right>".number_format($allprojcathrs[$abcd],2)."</td><td align=right>".
        			number_format(100*$allprojcathrs[$abcd]/$allprojtotaltimeactual ,2)."%</td></tr>";
        	}   		
    		echo "<tr><th colspan=3><hr></th></tr>";
    		echo "<tr><td>&nbsp;</td><th align=right>".number_format($allprojtotaltimeactual , 2).
    			"</th><th align=right>100.00%</th></tr></table>";
    	echo "</th><th>".
			number_format(100*$allprojtotaltimeactual /$allprojtotaltimebudget, 2)."%"; 
    	echo "</th></tr>";

    	echo "<tr><th align=right colspan=2>Cost</th><th colspan=2>".
    		number_format($allprojtotalcostbudget , 2)."</th>";
     		#budgeted cost break down by staff category: data are not available
   		
    	echo "<th colspan=2 align=middle>";
    		#actual cost break down by staff category
    		echo "<table>";
        	for ($j=0; $j<4; $j++) {
        		$abcd = $chcat4[$j];
        		echo "<tr><th>@$abcd</th><td align=right>".number_format($allprojcatcost[$abcd],2)."</td><td align=right>".
        			number_format(100*$allprojcatcost[$abcd]/$allprojtotalcostactual,2)."%</td></tr>";
        	}   		
    		echo "<tr><th colspan=3><hr></th></tr>";
    		echo "<tr><td>&nbsp;</td><th align=right>".number_format($allprojtotalcostactual, 2).
    			"</th><th align=right>100.00%</th></tr></table>";
		if ($allprojtotalcostbudget ) {
			$comp = number_format(100*$allprojtotalcostactual/$allprojtotalcostbudget , 2)."%";
		} else {
			$comp = "NA";
		}
    	echo "</th><th>$comp</th></tr>";
    	echo "</table><p>";
    }
    
	##################	activity only
	if ($activityonly) {
		$thismonth = date("F Y");
		reportline1("Project Activity Summary for $thismonth.");
		$todaydate = date("Y-m-d");
		$sql = "SELECT t1.email_name as ename, t1.projcode_id, t1.phaseno, 
            t1.budgetfile, t1.dateinrow, t1.budgetstart, t1.budgetend, 
            t1.actualstart, t1.actualend,
            t2.brief_code
        	FROM timesheet.projbudgetfile as t1, timesheet.projcodes as t2
        	WHERE t1.budgetstart<='$todaydate' and t1.budgetend>='$todaydate' and t1.neworold='y'
        		and t1.projcode_id=t2.projcode_id 
        	ORDER BY t2.brief_code;";

    	$result = mysql_query($sql);
   	 	include("err_msg.inc");
    	$no = mysql_num_rows($result);
		$i=1;
    	while (list($ename, $projcode_id, $phaseno, $budgetfile, 
        	$dateinrow, $budgetstart, $budgetend, $actualstart, 
        	$actualend, $brief_code) = mysql_fetch_array($result)) {
        	echo "<h3>Project $i: $brief_code, Phase $phaseno</h3>";
        	echo "<table>";
        	echo "<tr><th align=left>Leader</th><td>$ename</td></tr>";
        	echo "<tr><th align=left>Date Start</th><td>$budgetstart (Budgeted) $actualstart (Actual)</td></tr>";
        	echo "<tr><th align=left>Date End</th><td>$budgetend (Budgeted) $actualend (Actual)</td></tr>";
        	echo "</table>";
        	$todaydateno = date("Ymd");
        	$budgetdataarray = explode("<br>",$budgetfile);
        	$nolines = count($budgetdataarray);
        	$datearray = explode(",",$budgetdataarray[$dateinrow]);
        	$datestartfromcol  = 0;
        	for ($j=0; $j<count($datearray); $j++) {
        		if ($datearray[$j]) {
        			if ($datestartfromcol  == 0) {
        				$datestartfromcol  = $j;
        			}
        			$mthdayno = ereg_replace("-", "", $datearray[$j]);
        			if ($mthdayno > $todaydateno) {
        				$colno = $j - 1;
        				break;
        			} 
        		}
        	}
        	echo "<p><table border=1>";
        	for ($j=$dateinrow+1; $j<$nolines-1; $j++) {
        		$strarray = explode(",",$budgetdataarray[$j]);
        		if ($strarray[$colno] && $strarray[$datestartfromcol -1] ) {
        			echo "<tr>";
        			for ($k=0; $k<$datestartfromcol ; $k++) {
        				if (!$strarray[$k]) {
        					$strarray[$k] = "&nbsp;";
        				}
        				echo "<th align=left>".$strarray[$k]."</th>";
        			}
        			echo "<td>".$strarray[$colno]."</td></tr>";
        		}
        	}		
        	echo "</table><p>";		
        
        	#echo "$budgetfile<br>";
        	$sep = "<b>=============================================================================</b><p>";
        	echo "$sep";
        	$i++;
    	}
   }

}
?>
<hr><br><a href=#top><b>Back to top</b></a><br><br>
</html>
