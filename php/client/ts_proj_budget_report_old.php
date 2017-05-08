<html>
<head>
<title>Budget Report</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<LINK REL="StyleSheet" HREF="../style/style.css" TYPE="text/css">

</head>
<body leftmargin="20" bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG">
<script language=javascript>
function uploadverify() {
	if (document.uploadfileform.filename.value == "") {
		window.alert("No file has been selected.");
		return false
	} else {
		return true;
 	}
}
</script>
<?php
include('str_decode_parse.inc');
include("connet_other_once.inc");
include("rla_functions.inc");
include("userinfo.inc"); //$userinfo
$userstr	=	"?".base64_encode($userinfo);
$factormthtohours = 163;

echo "<h2 align=center><a id=top>Budget Report</a>";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2> [Refresh]</font></a>";
echo "<a href=\"ts_proj_budget_data.php$userstr\"><font size=2>[Goto Upload File Page]</font></a>";
echo "</h2><hr>";

$sql = "SELECT t1.email_name AS ename, t1.charge_code, t2.rate 
        FROM timesheet.chargeclassfn as t1, timesheet.chargecode as t2
        WHERE t1.charge_code=t2.charging_code";
$result = mysql_query($sql);
include("err_msg.inc");
while (list($ename, $charge_code, $rate) = mysql_fetch_array($result)) {
	$chargerate["$ename"] = $rate;
	$chargecode["$ename"] = $charge_code;
}

	//include("userstr.inc");
echo "<h2>Project Report Index</h2>";
echo "<ul>";
	echo "<li><b>Summary for Current Month </b>";
	$userstr	=	"?".base64_encode($userinfo."&thismonthactivity=y");
	echo "<a href=\"$PHP_SELF$userstr\">[Budget]</a>  ";
	$userstr	=	"?".base64_encode($userinfo."&thismonthexecution=y");
	echo "<a href=\"$PHP_SELF$userstr\">[Actual]</a>";
	
	if ($priv == "00" || $priv == "00" || $priv == "50") {
		$userstr	=	"?".base64_encode($userinfo."&reportforanyperiod=y");
		echo "<li><a href=\"$PHP_SELF$userstr\"><b>Summary for Any Period</b></a>";
	}
echo "</ul>";
if ($reportforanyperiod) {
	include("ts_proj_budget_report_anyperiod.inc");
	exit;
}

################################################################
#	REPORT SECTION
if ($thismonthactivity) {
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
        $datastartfromcol = 0;
        for ($j=0; $j<count($datearray); $j++) {
        	if ($datearray[$j]) {
        		if ($datastartfromcol == 0) {
        			$datastartfromcol = $j;
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
        	if ($strarray[$colno] && $strarray[$datastartfromcol-1] ) {
        		echo "<tr>";
        		for ($k=0; $k<$datastartfromcol; $k++) {
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
} elseif ($thismonthexecution) {
	$thismonth = date("F Y");
	reportline1("Project Summary for $thismonth.");
	$todaydate = date("Y-m-d");
	if ($priv == "00" || $priv == "10") {
		$sql = "SELECT t1.email_name as ename, t1.projcode_id, t1.phaseno, 
            t1.budgetfile, t1.dateinrow, t1.budgetstart, t1.budgetend, 
            t1.actualstart, t1.actualend,
            t2.brief_code
        FROM timesheet.projbudgetfile as t1, timesheet.projcodes as t2
        WHERE t1.budgetstart<='$todaydate' and t1.budgetend>='$todaydate' and t1.neworold='y'
        	and t1.projcode_id=t2.projcode_id 
        ORDER BY t2.brief_code;";
   } elseif ($priv == "50") {
		$sql = "SELECT t1.email_name as ename, t1.projcode_id, t1.phaseno, 
            t1.budgetfile, t1.dateinrow, t1.budgetstart, t1.budgetend, 
            t1.actualstart, t1.actualend,
            t2.brief_code
        FROM timesheet.projbudgetfile as t1, timesheet.projcodes as t2
        WHERE t1.budgetstart<='$todaydate' and t1.budgetend>='$todaydate' and t1.neworold='y'
        	and t1.projcode_id=t2.projcode_id and t1.email_name='$email_name'
        ORDER BY t2.brief_code;";
	}

    $result = mysql_query($sql);
    include("err_msg.inc");
    $no = mysql_num_rows($result);
	$i=1;
	echo "<table border=1>";
	$projtotalmonthbudgeted = 0;
	$projtotalmonthreal = 0;
    while (list($ename, $projcode_id, $phaseno, $budgetfile, 
        $dateinrow, $budgetstart, $budgetend, 
        $actualstart, $actualend, 
        $brief_code) = mysql_fetch_array($result)) {
        echo "<tr><th align=left colspan=7>Project $i: $brief_code, Phase $phaseno</th></tr>";
        echo "<tr><th>Leader</th><th>Start</th><th>End</th><th>Activity</th>
        	<th>Budget Time (hrs)</th><th>Actual Time (hr)</th><th>Comments</th></tr>";        
        echo "<tr><td>$ename</td>";
        echo "<td>$budgetstart<br>($actualstart)</td>";
        echo "<td>$budgetend<br>($budgetend)</td>";
        
        #find column and real budget data starting row for this month
        $todaydateno = date("Ymd");
        $budgetdataarray = explode("<br>",$budgetfile);
        $nolines = count($budgetdataarray);
        $datearray = explode(",",$budgetdataarray[$dateinrow]);
        $datastartfromcol = 0;
        for ($j=0; $j<count($datearray); $j++) {
        	if ($datearray[$j]) {
        		if ($datastartfromcol == 0) {
        			$datastartfromcol = $j;
        		}
        		$mthdayno = ereg_replace("-", "", $datearray[$j]);
        		if ($mthdayno > $todaydateno) {
        			$colno = $j - 1;
        			break;
        		} 
        	}
        }        
        $projectsubbudget = 0;
        $totalcost_budget = 0;
        $totalcost_real = 0;
        echo "<td>";
        for ($j=$dateinrow+1; $j<$nolines-1; $j++) {
        	$strarray = explode(",",$budgetdataarray[$j]);
        	$tmp = $strarray[0];
        	if ($strarray[$colno] && $strarray[0] ) {
        		for ($k=1; $k<$datastartfromcol; $k++) {
        			if ($strarray[$k]) {
        				$tmp .= ": ".$strarray[$k];
        			}
        		}
        		echo "$tmp<br>";
        		$projectsubbudget = $projectsubbudget + $strarray[$colno];
        	}
        }		
        echo "</td>";
        echo "<td align=middle>".number_format($projectsubbudget*163, 2)."</td>";
        	
        #find column and real budget data starting row for this month
        $tshtstart = substr($todaydate,0,8)."01";
		 $mth2d = substr($todaydate,5,2);
		 $tshtend = substr($todaydate,0,8).$daysinmth["$mth2d"];
        #$tshtstart = "2001-11-01";
        #$tshtend = "2001-11-30";
        $sqlsub = "SELECT t1.email_name as ename, sum(t2.minutes) 
        	FROM timesheet.entry_no as t1, timesheet.timedata as t2
        	WHERE t1.yyyymmdd>='$tshtstart' and t1.yyyymmdd<='$tshtend'
        		and t2.brief_code='$brief_code' and t1.entry_no=t2.entry_no
        	GROUP BY ename
        	ORDER BY ename;";
        #echo "$sqlsub<br>";
    	$resultsub = mysql_query($sqlsub);
    	include("err_msg.inc");
        $projectsubreal = 0;
        echo "<td><table>";
        //
        $realcharge = 0;
        while (list($ename, $minutes) = mysql_fetch_array($resultsub)) {
        	$hr = number_format($minutes/60, 2);
        	$ch = $hr * $chargerate["$ename"];
        	$realcost = $realcost + $ch;
        	$cd = $chargecode["$ename"];
        	echo "<tr><td>$ename</td><td align=right>$hr</td><td align=right>@$cd</td></tr>";
        	$projectsubreal = $projectsubreal  + $minutes;
        }
        echo "<tr><th>Sub</th><th align=right>".number_format($projectsubreal/60, 2)."</th>".
        	"<th align=right>".number_format($realcost, 2)."</th></tr>";
        echo "</table></td>";
        echo "<td align=right>".number_format(($projectsubreal/0.6)/($projectsubbudget*$factormthtohours), 2)."%</td>";
        
        echo "</tr>";
        $projtotalmonthbudgeted = $projtotalmonthbudgeted + $projectsubbudget ;
        $projtotalmonthreal = $projtotalmonthreal + $projectsubreal;
        $totalcost_budget = $totalcost_budget  + 0;
        $totalcost_real = $totalcost_real + $realcost;
        $i++;
    }
    echo "<tr><th align=left colspan=2>Total</th><th colspan=2>Budget</th><th colspan=2>
    Actual</th><th>Completed By</th></tr>";
    echo "<tr><th align=right colspan=2>Hours</th><th colspan=2>".
    	number_format($projtotalmonthbudgeted*$factormthtohours,2)."</th><th colspan=2>".
    	number_format($projtotalmonthreal/60, 2)."</th><th>".
		number_format(($projtotalmonthreal/0.6)/($projtotalmonthbudgeted*$factormthtohours), 2).
    	"%</th></tr>";
	
	if ($totalcost_budget) {
		$comp = number_format($totalcost_real/$totalcost_budget, 2)."%";
	} else {
		$comp = "NA";
	}
    echo "<tr><th align=right colspan=2>Cost</th><th colspan=2>".
    	number_format($totalcost_budget, 2)."</th><th colspan=2>".
    	number_format($totalcost_real, 2)."</th><th>".
		$comp.
		"</th></tr>";

    echo "</table><p>";
}
function reportline1($hd) {
	echo "<hr><h2>$hd</h2>";
}

?>
<hr><br><a href=#top><b>Back to top</b></a><br><br>
</html>
