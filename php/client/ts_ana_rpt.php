<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>Project Summary</title>
</head>

<body leftmargin="20" bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG">

<?php

$st = (24-date("H"))*3600 - date("i")*60 - date("s");
$svrfld = "/usr/local/apache/htdocs/report/";
$bulsize = 2;
$dateonly = 1;
include('str_decode_parse.inc');
include('rla_functions.inc');
include('ts_ana_sum.inc');
include("find_domain.inc");	
include("find_admin_ip.inc");

echo "<a id=top><h1 align=center>Timesheet Analysis & Reports</h1></a>";
if ($newpriv) {
	$priv = $newpriv;
}
if ($priv	==	'00' || $priv	==	'10' || $priv	==	'20') {
	
} else {
	echo "<p align=center><b>Under development</b></p><hr>";
	exit;
}

include("userinfo.inc"); //$userinfo
$userstr	=	"?".base64_encode($userinfo);
echo "<p align=center><a href=\"".$PHP_SELF."$userstr\">[Refresh]</a><hr>";
$rlaohdtil = "RLA-OHD-Time_in_Lieu";

if ($analysisreport) {
	include('connet_other_once.inc');
	$firstday = "2000-07-01";
	$sql = "SELECT TO_DAYS('$firstday') as firstday";
	//echo $sql."<br>";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($firstday) = mysql_fetch_array($result);
	//echo $firstday."<br>";

	$fromdate	=	$calyearstart.'-'.$calmonthstart.'-'.$caldaystart;
	$sql = "SELECT TO_DAYS('$fromdate') as fromdate";
	//echo $sql."<br>";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($fromdate) = mysql_fetch_array($result);
	//echo $fromdate."<br>";
	if ($fromdate < $firstday) {
		$calyearstart = 2000;
		$calmonthstart = 7;
		$caldaystart = 1;
		$fromdate = $firstday;
	}
	
	$todate	=	$calyearend.'-'.$calmonthend.'-'.$caldayend;
	$timeforslp = $todate;
	
	$result = mysql_query("SELECT TO_DAYS('$todate') as todate");
	include("err_msg.inc");
	list($todate) = mysql_fetch_array($result);
	//echo $todate."<br>";
	mysql_close();
	if ($todate < $fromdate) {
		$msg = "<hr><b><font color=#ff0000>Please check \"Date End\" value.</font></b>";
		$calyearend = $calyearstart + 1;
		$calmonthend = $calmonthstart;
		$caldayend = $caldaystart;
	}
	/*
	if ($msg) {
		echo $msg."<br>";
	}
	*/
}

########################################
##		build form for timesheet analysis & summary
include("ts_sum_form_build.inc");
########################################

if ($sendreadymsg) {
	$mailmsg = "send";
	include("mailattachment.inc");
	//include("ts_ready_for_GHR.inc");
	include("ts_sendmailto_GHR.inc");
}
	
if ($rebuild == "Yes") {
	exit;
}
flush();

########################################
##		timesheet analysis and summary
########################################
if ($analysisreport) {
	//remove other files in report directory
	$cmd = "rm -f $svrfld$email_name*";
	exec($cmd);

	$rpttypeS = $sum_type_array[$sumanatype][0];
	$rpttypeL = $sum_type_array[$sumanatype][1];
## define selected period
	if ($rpttypeS == "MthSum") {
		if ($selyear > date("Y")) {
			echo "<b><br>Sorry no data available for year $selyear.<b><br>";
			exit;
		}
		$periodtxt = $mths[$selmonth]."_$selyear";
		if ($selyear == date("Y") && $selmonth>date("m")) {
			echo "<b><br>Sorry no data available for $periodtxt .<b><br>";
			exit;
		}
		$theyear = $selyear; 	//date("Y");
		if ($selmonth == 12) {
			$fromdate	=	$theyear.'-'.$selmonth.'-01';
			$theyear = $theyear + 1;
			$todate	=	$theyear.'-01-01';
		} else {
			if ($selmonth < 10) {
				$tmp = "0".$selmonth;
			} else {
				$tmp = $selmonth;
			}
			$fromdate	=	$theyear.'-'.$tmp.'-01';
			$tmp = $selmonth + 1;
			if ($tmp < 10) {
				$tmp = "0".$tmp;
			} else {
				$tmp = $tmp;
			}
			$todate	=	$theyear.'-'.$tmp.'-01';
		}
		if ($priv == "00" ) {
			//echo  "$fromdate to $todate. $selyear<br>";
			//exit;
		}
	} elseif ($rpttypeS == "YlySum") {
		$tmp = $selyear + 1;
		$fromdate	=	"$selyear-07-01";
		$todate	=	"$tmp-07-01";
		$tmp = "July 1 $selyear to June 30 $tmp";
		$periodtxt = "$tmp";
	} elseif ($rpttypeS == "SlpSum") {
		if (strlen($calmonthstart) == 1) {
			$calmonthstart = "0".$calmonthstart;
		}
		if (strlen($caldaystart) == 1) {
			$caldaystart= "0".$caldaystart;
		}
		if (strlen($calmonthend) == 1) {
			$calmonthend= "0".$calmonthend;
		}
		if (strlen($caldayend) == 1) {
			$caldayend= "0".$caldayend;
		}
		$t1 = "$calyearstart$calmonthstart$caldaystart";
		$t2 = "$calyearend$calmonthend$caldayend";	
		$fromdate	=	$calyearstart.'-'.$calmonthstart.'-'.$caldaystart;
		$todate	=	$calyearend.'-'.$calmonthend.'-'.$caldayend;
		$periodtxt = "$fromdate to $todate";
	} else {
		$periodtxt = $otherp[$selopd]."_$selyear";
		if ($rpttypeS == "QtrSum") {
			$y1 = $selyear;
			$y2 = $y1;
			if ($selopd == 3) {
				$y2 = $y1 + 1;
			}
			$m1 = (1+$selopd)*3 - 2;
			$m2 = $m1 + 3;
			if ($m1 < 10) {
				$m1 = "0".$m1;
			}
			if ($m2 > 11) {
				$m2 = "01";
			} elseif ($m2 < 10) {
				$m2 = "0".$m2;
			}
			$fromdate	=	$y1."-".$m1."-01";
			$todate	=	$y2."-".$m2."-01";
		}
		if ($rpttypeS == "HfySum") {
			$y1 = $selyear;
			$y2 = $y1;
			$m1 = "01";
			$m2 = "07";
			if ($selopd == 1) {
				$y2 = $y1 + 1;
				$m1 = "07";
				$m2 = "01";
			}
			$fromdate	=	$y1."-".$m1."-01";
			$todate	=	$y2."-".$m2."-01";
		}
	}
	echo "<hr><h2>$rpttypeL ($periodtxt) Timesheet Summary.</h2>";

	$where	=	"date_unemployed='0000-00-00' or date_unemployed>='$fromdate' 
		and email_name!='webmaster' order by last_name";
	if ($priv == "00") {
		//echo "<br>$where.<br>";
		//exit;
	}
	
## validate period
	// "MthSum" "QtrSum"	"HfySum"	"YlySum"	"SlpSum";
	$back = "<hr><a href=#top>Back to top</a><a id=end></a><br><br><br>";
	$msg0 = "<h3><font color=#ff0000>There is no Timesheet Data on Intranet before ".$mth[7].", 2000.</h3><br>";
	$msg = "";
	if ($rpttypeS == "MthSum" && $selmonth) {
		if (date("Y") == "2000" && $selmonth < 7) {
			$msg = $msg0;
		}
	} elseif ($rpttypeS == "QtrSum") {
		if ($selyear == 2000 && $selopd<2) {
			$msg = $msg0;
		}
	} elseif ($rpttypeS == "HfySum") {
		if ($selyear == 2000 && $selopd<1) {
			$msg = $msg0;
		}
	} elseif ($rpttypeS == "SlpSum") {
		if ($t1 > $t2) {
			$msg = $msg0;
		}
	}
		
	if ($priv == "00" && $msg) {
		//echo "$t1 - $t2 $fromdate to $todate.<br>";
		//echo $msg.$back;
		//exit;
	}
	
	include('connet_other_once.inc');
	$staffmulti	=	"multi";
	$where_heh = "y";
	
	include("stafflist.inc");
	if ($leader == "y") {
		if ($priv == "00" || $priv == "10" || $priv == "20") {
			include('codeorder_leader.inc');
		} else {
			include('codeorder.inc');
			$isprefix = "Y";
		}
	} else {
		if ($priv == "20") {
			include('codeorder_leader.inc');
		} else {
			include('codeorder.inc');
			$isprefix = "Y";
		}
	}

##########################################
## define weeks
	include("connet_other_once.inc");
	## find most current date from timesheet DB
	$sql = "SELECT yyyymmdd as ymd "
		."FROM timesheet.entry_no "
		."WHERE yyyymmdd>='$fromdate' and yyyymmdd<'$todate' "
		."ORDER BY ymd DESC;";
	if ($priv == "00") {
		//echo "$sql<br>";
		//exit;
	}
	$sqldate = "<br>".$sql;
	$result = mysql_query($sql);
	include("err_msg.inc");
	$nowks = mysql_num_rows($result); 
	list($ymd) = mysql_fetch_array($result);
	$todate = $ymd;
	$str	=	"'$todate'";	
	$sql = "select TO_DAYS($str) daynos;";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($daynos) = mysql_fetch_array($result);
	$i = $daynos + 6;
	$sql = "select FROM_DAYS($i) todate;";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($todate) = mysql_fetch_array($result);

	include("ts_period_order.inc");
	/*
	echo "<b>Statistics.</b><br>";
	echo "<table border=1>";
	echo "<tr><td>Number of Project Code Group</td><td>$no_prefix</td></tr>";
	echo "<tr><td>Number of Project Code</td><td>$codeno</td></tr>";
	echo "<tr><td>Number of Staff</td><td>$staffno</td></tr>";
	echo "<tr><td>Number of weeks</td><td>$nowks</td></tr>";
	echo "</table><p>";
	*/
	
	if ($priv == "00") {
		//echo $sqldate."<br>";
		//exit;
	}
################################
##query timesheet DB
	$curtime = "_"."(".date("YmdHi").")";
	$curtime = "";

	//$filetitle0 = substr($email_name,0,3)."_".$rpttypeS."_".$periodtxt.$curtime;
	$filetitle0 = $email_name."_".$rpttypeS."_".$periodtxt.$curtime;
	if ($priv == "00") {
		//echo $filetitle0."<br>";
		//exit;
	}
	/*
	$display = 0;
	$filetitle = $filetitle0."_3c.csv";
	echo $filetitle."<br>";
	include("ts_sum_3column.inc");
	echo "<br><b>Total hours is $hrsum.<br><br>";
	//*/

################################
## query A:  for each project, each week and each person (it is not used see next)
################################
$tot = 0;
if ($isprefix == "N") {
$ctr = 0;
$display = 0;
for ($ldrcode=0; $ldrcode<$codeplusgroup; $ldrcode++){
	$codetmp = $codelist[0][$ldrcode];
	$sql = "SELECT t1.yyyymmdd as ymd, t1.email_name as ename, "
		."t2.brief_code as pcode, t2.minutes as minutes "
		."FROM timesheet.entry_no as t1, timesheet.timedata as t2 "
		."WHERE t1.yyyymmdd>='$fromdate' and "
		."t1.yyyymmdd<'$todate' and t1.entry_no=t2.entry_no "
		."and t2.brief_code='$codetmp' ORDER BY ymd, ename;";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$norcd = mysql_num_rows($result);
	if ($priv == "00") {
		//echo "<br>$sql<br><br>";
		//echo "<br>$norcd<br>";
	}
	if ($display == 1) {
		echo "<br><br>$sql<br>Number of records: $norcd.<br><br>";
		echo "<table border=1>";
		echo "<tr><th>No</th><th>Date</th><th>Code</th><th>Minutes</th>
			<th>Ename</th><th>(D-C-E)</th></tr>";
	}
	while (list($ymd, $ename, $pcode, $minutes) = mysql_fetch_array($result)) {
		## procedure 0: find relavent index for code, week and peoson
		//$fromcodetono[$pcode] = $j -> $ordered_code[0][$j] = $pcode
		//$wkstrtono[$ymd] -> $k -> $yhyqmw[$k][0] -> $wklist[$i] = $ymd
		//$fromstafftono[$ename] = $i -> $stafflist[0][$i] = $ename
		$tot = $tot + $minutes;
		$pcode_ind = $fromcodetono[$pcode];
		$ymd_ind = $wkstrtono[$ymd];
		$ename_ind = $fromstafftono[$ename];
		
		## procedure 1: distribute time to inner grid (code, week and person) 
		$minarray[$pcode_ind][$ymd_ind][$ename_ind] = $minutes;
		
		## procedure 2: week sub total by person against code. $pcode_ind = $codeplusgroup
		$minarray[$codeplusgroup][$ymd_ind][$ename_ind] = 
			$minarray[$codeplusgroup][$ymd_ind][$ename_ind] + $minutes;
		
		## procedure 3: week sub total by project against people. $ename_ind = $staffno
		$minarray[$pcode_ind][$ymd_ind][$staffno] = 
			$minarray[$pcode_ind][$ymd_ind][$staffno] + $minutes;

		/*
		if ($priv == "00") {
			echo "<br>$ctr,ALL,$pcode,$pcode_ind,$ymd_ind,$ename_ind";
		}
		//*/
		if ($display == 1) {
			$tmp = $ymd_ind."-".$pcode_ind."-".$ename_ind;
			echo "<tr><td>$ctr</td><td>$ymd</td><td>$pcode</td><td>$minutes</td>
				<td>$ename</td><td>$tmp</td></tr>";
		}
		flush();
		$ctr++;
		//*/
	}
	if ($display == 1) {
		echo "</table><p>";
	}
}
}
if ($priv == "00" && $display == 1) {
	echo "<br>$tot";
	$tot = 0;
}

################################
## query B: for project group, each week and each person
################################
if ($isprefix == "Y") {
	$display = 0;
	if ($display == 1) {
		echo "<table border=1>";
		echo "<tr><th>Code</th><th>No Record</th><th>Hours</th><th>Code Index</td></tr>";
	}
	$ctr = 0;
	$thrs = 0;
	for ($prefix=0; $prefix<$no_prefix; $prefix++) {
		$pxcode = $codeprefix_list[$prefix][1];
		$pcodelable = $codeprefix_list[$prefix][2];
		$pcode_grpind = $fromcodetono[$pxcode];
		$tmp = $pxcode."%";
		if ($pxcode == "RLA-OHD") {
			//$rlaohdtil = "RLA-OHD-Time_in_Lieu";
			$sql = "SELECT t1.yyyymmdd as ymd, t1.email_name as ename, t2.brief_code as pcode, t2.minutes as minutes "
			."FROM timesheet.entry_no as t1, timesheet.timedata as t2 "
			."WHERE t1.yyyymmdd>='$fromdate' and t1.yyyymmdd<'$todate' "
			."and t1.entry_no=t2.entry_no and ((t2.brief_code LIKE '$tmp') and (t2.brief_code<>'$rlaohdtil')) "
			."ORDER BY ymd, ename;";
			
			//Prepared for table 7 list time in lieu
			$sqlTIL = "SELECT t1.yyyymmdd as ymd, t1.email_name as ename, t2.minutes as minutes "
			."FROM timesheet.entry_no as t1, timesheet.timedata as t2 "
			."WHERE t1.yyyymmdd>='$fromdate' and t1.yyyymmdd<'$todate' "
			."and t1.entry_no=t2.entry_no and t2.brief_code='$rlaohdtil' "
			."ORDER BY ename, ymd;";

			$rlaohdtil = ereg_replace("_", " ", $rlaohdtil);
		} else {
			$sql = "SELECT t1.yyyymmdd as ymd, t1.email_name as ename, t2.brief_code as pcode, t2.minutes as minutes "
			."FROM timesheet.entry_no as t1, timesheet.timedata as t2 "
			."WHERE t1.yyyymmdd>='$fromdate' and t1.yyyymmdd<'$todate' "
			."and t1.entry_no=t2.entry_no and (t2.brief_code LIKE '$tmp') "
			."ORDER BY ymd, ename;";
		}		
		if ($priv == "00" ) {//&& $pxcode == "RLA-OHD"
			//$tmp = "(t2.brief_code LIKE '$tmp')";
			//echo "<br>$tmp; $rlaohdtil<br>";
			//echo "<br>$sql<br>";
		}
//		."and t1.entry_no=t2.entry_no and $tmp "
		
		$result = mysql_query($sql);
		include("err_msg.inc");
		$norcd = mysql_num_rows($result);
		$trcd = $trcd + $norcd;
		$tmin = 0;
		while (list($ymd, $ename, $pcode, $minutes) = mysql_fetch_array($result)) {
			//if ($ename == "pes") {
				//echo "<br>$ymd";
			//}
			## procedure 0: find relavent index for code, week and peoson
			//$fromcodetono[$pcode] = $j -> $ordered_code[0][$j] = $pcode
			//$wkstrtono[$ymd] - $k - $yhyqmw[$k][0] -> $wklist[$i] = $ymd
			//$fromstafftono[$ename] = $i -- $stafflist[0][$i] = $ename
			//$tmin = $tmin + $minutes;

			## procedure 1: time distribution grouped by code prefix
			$ename_ind = $fromstafftono[$ename];
			$ymd_ind = $wkstrtono[$ymd];
			$minarray[$pcode_grpind][$ymd_ind][$ename_ind] = 
				$minarray[$pcode_grpind][$ymd_ind][$ename_ind] + $minutes;

			## procedure 2: week sub total by person against code. $pcode_ind = $codeplusgroup
			$minarray[$codeplusgroup][$ymd_ind][$ename_ind] = 
				$minarray[$codeplusgroup][$ymd_ind][$ename_ind] + $minutes;
		
			## procedure 3: week sub total by project group against people. $ename_ind = $staffno
			$minarray[$pcode_grpind][$ymd_ind][$staffno] = 
				$minarray[$pcode_grpind][$ymd_ind][$staffno] + $minutes;
				
			$pcode_ind = $fromcodetono[$pcode];
			## procedure 4: distribute time to inner grid (code, week and person) 
			$minarray[$pcode_ind][$ymd_ind][$ename_ind] = $minutes;
		
			## procedure 5: week sub total by project against people. $ename_ind = $staffno
			$minarray[$pcode_ind][$ymd_ind][$staffno] = 
				$minarray[$pcode_ind][$ymd_ind][$staffno] + $minutes;
			/*
				if ($priv == "00") {
					echo "<br>$ctr,Grp,$pcode,$pcode_ind,$ymd_ind,$ename_ind";
					$ctr++;
				}
			//*/
		}
		//$thrs = $thrs + $tmin;
		if ($display == 1) {
			$tmp = number_format($tmin/60, 2);
			$tmp = ereg_replace(",", "", $tmp);
			echo "<tr><td>$pcodelable</td><td>$norcd</td><td>$tmp</td><td>$pcode_ind</td></tr>";
		}
	}
	$display =0;
	if ($display == 1 && $priv == "00") {
		echo "</table><p>";
		echo "Total records: $trcd<br>";
		$thrs = number_format($thrs/60, 2);
		echo "Total hours: $thrs<br>";
	}
	//include("ts_sumdata_independent_check.inc");
	$display = 0;
}

################################
## monthly cumulative
################################
if ($isprefix == "Y") {
	$factor = 0.5;
} elseif ($isprefix == "N") {
	$factor = 1;
}
	$tot = 0;
	//echo "<br>".date("m:i:s");
	for ($mctr=0; $mctr<$timeseries; $mctr++) {
		$ptype = $yhyqmw[$mctr][0];
		if ($ptype == "m") {
			//echo "<br><br>month. $mctr. (".$wklist[$yhyqmw[$mctr][1]].", ".$wklist[$yhyqmw[$mctr][2]].")";
			$nowk = 0;
			for ($wkctr=$mctr+1; $wkctr<$timeseries; $wkctr++) {
				$ptype = $yhyqmw[$wkctr][0];
				if ($ptype == "m") {
					break;
				} elseif ($ptype == "w") {
					$wkinmth[$nowk] = $wkctr;
					//echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;$ptype: $wkctr-$nowk. (".$wklist[$yhyqmw[$wkctr][1]].")";
					$nowk++;
				}
			}
			
			for ($wkctr=0; $wkctr<$nowk; $wkctr++) {
				$timeind = $wkinmth[$wkctr];
				//echo "<br>&nbsp;&nbsp;&nbsp;&nbsp;".$yhyqmw[$timeind][1].": $timeind. (".$wklist[$yhyqmw[$timeind][1]].")";
				for ($i=0; $i<$codeplusgroup; $i++) {
					for ($j=0; $j<$staffno; $j++) {
						if ($minarray[$i][$timeind][$j]) {
							$tmp = $minarray[$i][$timeind][$j];
							##inner grid
							$minarray[$i][$mctr][$j] = $minarray[$i][$mctr][$j] + $tmp;
							##sub total by people
							$minarray[$codeplusgroup][$mctr][$j] = $minarray[$codeplusgroup][$mctr][$j] + $factor*$tmp;
							##sub total by project
							$minarray[$i][$mctr][$staffno] = $minarray[$i][$mctr][$staffno] + $tmp;
							//$tot = $tot + $tmp;
						}
					}
				}
			}
		}
	}
	//echo "<br>Total hours (month): ".number_format($tot/60,2);
	//echo "<br>".date("m:i:s");

################################
## quarterly cumulative
################################
	//$tot = 0;
	for ($qctr=0; $qctr<$timeseries; $qctr++) {
		$ptype = $yhyqmw[$qctr][0];
		if ($ptype == "q") {
			//echo "<br><br>quarterly. $qctr. (".$wklist[$yhyqmw[$qctr][1]].", ".$wklist[$yhyqmw[$qctr][2]].")";
			$nomth = 0;
			for ($mctr=$qctr+1; $mctr<$timeseries; $mctr++) {
				$ptype = $yhyqmw[$mctr][0];
				if ($ptype == "q") {
					break;
				} elseif ($ptype == "m") {
					$mthlist[$nomth] = $mctr;
					//echo "<br><br>month. $mctr. (".$wklist[$yhyqmw[$mctr][1]].", ".$wklist[$yhyqmw[$mctr][2]].")";
					$nomth++;
				}
			}
			for ($mctr=0; $mctr<$nomth; $mctr++) {
				$timeind = $mthlist[$mctr];
				for ($i=0; $i<$codeplusgroup; $i++) {
					for ($j=0; $j<$staffno; $j++) {
						if ($minarray[$i][$timeind][$j]) {
							$tmp = $minarray[$i][$timeind][$j];
							##inner grid
							$minarray[$i][$qctr][$j] = $minarray[$i][$qctr][$j] + $tmp;
							##sub total by people
							$minarray[$codeplusgroup][$qctr][$j] = $minarray[$codeplusgroup][$qctr][$j] + $factor*$tmp;
							##sub total by project
							$minarray[$i][$qctr][$staffno] = $minarray[$i][$qctr][$staffno] + $tmp;
							//$tot = $tot + $tmp;
						}
					}
				}
			}
		}
	}
	//echo "<br>Total hours (quarter): ".number_format($tot/60,2);
	//echo "<br>".date("m:i:s");

################################
## half yearly cumulative
################################
	$tot = 0;
	for ($hyctr=0; $hyctr<$timeseries; $hyctr++) {
		$ptype = $yhyqmw[$hyctr][0];
		if ($ptype == "hy") {
			//echo "<br><br>half yearly. $hyctr. (".$wklist[$yhyqmw[$hyctr][1]].", ".$wklist[$yhyqmw[$hyctr][2]].")";
			$noqtr = 0;
			for ($qctr=$hyctr+1; $qctr<$timeseries; $qctr++) {
				$ptype = $yhyqmw[$qctr][0];
				if ($ptype == "hy") {
					break;
				} elseif ($ptype == "q") {
					$hylist[$noqtr] = $qctr;
					//echo "<br><br>quarterly. $qctr. (".$wklist[$yhyqmw[$qctr][1]].", ".$wklist[$yhyqmw[$qctr][2]].")";
					$noqtr++;
				}
			}
			for ($qctr=0; $qctr<$noqtr; $qctr++) {
				$timeind = $hylist[$qctr];
				for ($i=0; $i<$codeplusgroup; $i++) {
					for ($j=0; $j<$staffno; $j++) {
						if ($minarray[$i][$timeind][$j]) {
							$tmp = $minarray[$i][$timeind][$j];
							
							##inner grid
							$minarray[$i][$hyctr][$j] = $minarray[$i][$hyctr][$j] + $tmp;
							
							##sub total by people
							$minarray[$codeplusgroup][$hyctr][$j] = $minarray[$codeplusgroup][$hyctr][$j] + $factor*$tmp;
							
							##sub total by project
							$minarray[$i][$hyctr][$staffno] = $minarray[$i][$hyctr][$staffno] + $tmp;
							//$tot = $tot + $tmp;
						}
					}
				}
			}
		}
	}
	//echo "<br>Total hours (half year): ".number_format($tot/60,2);
	//echo "<br>".date("m:i:s");

################################
## yearly cumulative
################################
	$tot = 0;
	for ($yctr=0; $yctr<$timeseries; $yctr++) {
		$ptype = $yhyqmw[$yctr][0];
		if ($ptype == "y") {
			//echo "<br><br>yearly. $yctr. (".$wklist[$yhyqmw[$yctr][1]].", ".$wklist[$yhyqmw[$yctr][2]].")";
			$nohy = 0;
			for ($hyctr=$yctr+1; $hyctr<$timeseries; $hyctr++) {
				$ptype = $yhyqmw[$hyctr][0];
				if ($ptype == "y") {
					break;
				} elseif ($ptype == "hy") {
					$ylist[$nohy] = $hyctr;
					//echo "<br><br>half yearly. $hyctr. (".$wklist[$yhyqmw[$hyctr][1]].", ".$wklist[$yhyqmw[$hyctr][2]].")";
					$nohy++;
				}
			}
			for ($hyctr=0; $hyctr<$nohy; $hyctr++) {
				$timeind = $ylist[$hyctr];
				for ($i=0; $i<$codeplusgroup; $i++) {
					for ($j=0; $j<$staffno; $j++) {
						if ($minarray[$i][$timeind][$j]) {
							$tmp = $minarray[$i][$timeind][$j];
							##inner grid
							$minarray[$i][$yctr][$j] = $minarray[$i][$yctr][$j] + $tmp;
							
							##sub total by people
							$minarray[$codeplusgroup][$yctr][$j] = $minarray[$codeplusgroup][$yctr][$j] + $factor*$tmp;
							
							##sub total by project
							$minarray[$i][$yctr][$staffno] = $minarray[$i][$yctr][$staffno] + $tmp;
							//$tot = $tot + $tmp;
						}
					}
				}
			}
		}
	}
	//echo "<br>Total hours (year): ".number_format($tot/60,2);
	//echo "<br>".date("m:i:s");
	//exit;
## timesheet calculation: "y", "hy", "q", "m" and "w"
	
################################
##	periodically total
################################
if ($staffno>$codeplusgroup) {
	for ($j=0; $j<$timeseries; $j++) {
		for ($i=0; $i<$codeplusgroup; $i++) {
			if ($minarray[$i][$j][$staffno]) {
				$minarray[$codeplusgroup][$j][$staffno] = $minarray[$codeplusgroup][$j][$staffno] +
					$factor*$minarray[$i][$j][$staffno];
			}
		}
	}
} else {
	for ($j=0; $j<$timeseries; $j++) {
		for ($k=0; $k<$staffno; $k++) {
			if ($minarray[$codeplusgroup][$j][$k]) {
				$minarray[$codeplusgroup][$j][$staffno] = $minarray[$codeplusgroup][$j][$staffno] +
					$minarray[$codeplusgroup][$j][$k];
			}
		}
	}
}

###################################################################
#debug results strating point: for one staff
if ($priv == "0000") {
	$debugfile = "Staff.csv";
	echo "<br>Open this [".
		"<a href=\"../report/$debugfile\" target=\"_blank\"><b>$debugfile</b></a>] file<br><br><br>";
	$debugfile = $svrfld.$debugfile;
	$fp	=	fopen($debugfile,'w+');
	if (!$fp) {
		exit;
	}
	$k=13; 
	fputs($fp,$stafflist[0][$k]."\n");

	//projects
	fputs($fp,",,,,");
	for ($i=0; $i<$codeplusgroup; $i++) {
			//if ($minarray[$codeplusgroup-1][0][$k]>0) {
				fputs($fp,$ordered_code[0][$i].",");
			//}
		}
	fputs($fp,"\n");
	fputs($fp,"\n");
	for ($j=0; $j<$timeseries; $j++) {
		fputs($fp,"$j,".$yhyqmw[$j][0].",".
			$wklist[$yhyqmw[$j][1]].",".$wklist[$yhyqmw[$j][2]].",");
		for ($i=0; $i<$codeplusgroup; $i++) {
			//if ($minarray[$codeplusgroup-1][0][$k]>0) {
				fputs($fp,$minarray[$i][$j][$k].",");
			//}
		}
		fputs($fp,"\n");
	}
	echo "";
	#exit;
	#debug results ending point: for one staff

###################################################################
}
###################################################################
/*	data array structure
				$k (staff index) $staffno
				|
				|
				|
				|------------ $j (periodical series index, "y", "hy", "q", "m" and "w") $timeseries
             /
            /
           /
          /
         $i (project index) $codeplusgroup

	3D inner index: $minarray[$i][$j][$k]
									start 		end
	Project code no range ($i):		0		$codeplusgroup - 1
	Time series no range ($j):		0		$timeseries - 1
	Staff no range ($k):				0		$staffno - 1
	
	3D top plane index $minarray[$i][$j][$staffno], project subtotal which is against staff
									start to 	end
	Project code no range ($i):		0		$codeplusgroup - 1
	Time series no range ($j):		0		$timeseries - 1
	
	3D front plane index $minarray[$codeplusgroup][$j][$k], staff subtotal which is against project
									start 		end
	Time series no range ($j):		0		$timeseries - 1
	Staff no range ($k):				0		$staffno - 1

	3D top front corner index $minarray[$codeplusgroup][$j][$staffno ], total which is against staff & project
									start 		end
	Time series no range ($j):		0		$timeseries - 1
*/

###########################################################################
	$frmstr = "<form name=\"tsoutline\">";
	$staffename = "staffename@";
	$stafffname = "stafffname@";
	for ($i=0; $i<$staffno; $i++) {
		$staffename = $staffename.$stafflist[0][$i]."@";
		$stafffname = $stafffname.$stafflist[2][$i]." ".$stafflist[3][$i]."@";
	}
	$frmstr = $frmstr."<INPUT TYPE=\"hidden\" name=\"staffename\" value=\"$staffename\">";
	$frmstr = $frmstr."<INPUT TYPE=\"hidden\" name=\"stafffname\" value=\"$stafffname\">";
	
	$catpluscode = "catpluscode@";
	for ($i=0; $i<$codeplusgroup; $i++) {
		if ($ordered_code[2][$i] == "") {
			$catpluscode = $catpluscode."G@";
		} else {
			$catpluscode = $catpluscode."C@";
		}
	}
	$frmstr = $frmstr."<INPUT TYPE=\"hidden\" name=\"catpluscode\" value=\"$catpluscode\">";

	$timeperiod = "timeperiod@";
	for ($i=0; $i<$timeseries; $i++) {
		$timeperiod = $timeperiod.$yhyqmw[$i][0]."@";
	}
	$frmstr = $frmstr."<INPUT TYPE=\"hidden\" name=\"timeperiod\" value=\"$timeperiod\"></form>";

	$frmstrjs = "<script language=JAVASCRIPT>\n";
	$frmstrjs = $frmstrjs."var staffename=$staffename;\n";
	$frmstrjs = $frmstrjs."var stafffname=$stafffname;\n";
	$frmstrjs = $frmstrjs."var catpluscode=$catpluscode;\n";
	$frmstrjs = $frmstrjs."var timeperiod=$timeperiod;\n";
	$frmstrjs = $frmstrjs."</script>";
	//$frmstr = $frmstrjs;
###########################################################################

/*
echo "<br>";
for ($j=0; $j<$timeseries; $j++) {
	$ptype = $yhyqmw[$j][0];
	echo "<br>$j: $ptype-".number_format($minarray[$codeplusgroup][$j][$staffno]/60, 2);
	if ($ptype == "w") {
		echo " (".$wklist[$yhyqmw[$j][1]].")";
	} else {
		echo " (".$wklist[$yhyqmw[$j][1]].", ".$wklist[$yhyqmw[$j][2]].")";
	}
}
exit;
//*/

######################################################
## find index for various periods
######################################################
	## timesheet calculation: "y", "hy", "q", "m" and "w"
	include("find_admin_ip.inc");
	//if ($email_name=="$adminname") {echo "<br>";}
	$noweek = 0;
	for ($i=0; $i<$timeseries; $i++) {
		if ($yhyqmw[$i][0] == "w") {
			$weekindex[$noweek]  = $i;
			//if ($email_name=="$adminname") {echo "week index: $noweek, $i<br>";}
			$noweek++;
		}
	}
	$nomonth = 0;
	for ($i=0; $i<$timeseries; $i++) {
		if ($yhyqmw[$i][0] == "m") {
			$monthindex[$nomonth]  = $i;
			//if ($email_name=="$adminname") {echo "month index: $nomonth, $i<br>";}
			$nomonth++;
		}
	}	
	$noqtr = 0;
	for ($i=0; $i<$timeseries; $i++) {
		if ($yhyqmw[$i][0] == "q") {
			$qtrindex[$noqtr]  = $i;
			//if ($email_name=="$adminname") {echo "qtr index: $noqtr, $i<br>";}
			$noqtr++;
		}
	}
	$nohy = 0;
	for ($i=0; $i<$timeseries; $i++) {
		if ($yhyqmw[$i][0] == "hy") {
			$hyindex[$nohy]  = $i;
			//if ($email_name=="$adminname") {echo "half year index: $nohy, $i<br>";}
			$nohy++;
		}
	}
	$noyr = 0;
	for ($i=0; $i<$timeseries; $i++) {
		if ($yhyqmw[$i][0] == "y") {
			$yrindex[$noyr]  = $i;
			//if ($email_name=="$adminname") {echo "year index: $noyr, $i<br>";}
			$noyr++;
		}
	}

	$display = 1;
	$thfs = 2;
	$tdfs = 1;
	$tabborder = 1;
	$tailcsv = "(".date("l F j Y").")\n";
	$tailhtml = "<font size=2>(".date("l, F j Y").")</font><br><br>";
			
######################################################
######################################################
## write other csv+html report and display on screen
##  MthSum "QtrSum"	"HfySum"	"YlySum"	"SlpSum"
######################################################
######################################################
if ($rpttypeS != "MthSum") {	//any other period except monthly
	$head = "($periodtxt) $rpttypeL Report: ";	//  $rpttypeS
	$head = "$rpttypeL Report: ";	//  $rpttypeS
		
	$msg = "Server is currently preparing a $rpttypeL ($periodtxt) summary report, please waiting...";
	include("ts_sum_msg.inc");
	if ($leader == "y") {
		//echo '<br>leader == "y"<br>';
		include("ts_sum_rpt_ldr_anyperiod.inc");
		/*
		if ($priv == "00" || $priv == "10" || $priv == "20") {
		} else {
		}
		//*/
	} else {//$timeforslp
		if ($priv == "20") {
			include("ts_sum_rpt_ldr_anyperiod.inc");
		} else {
			if ($rptfmt == "normal") {
				include("ts_sum_rpt_diropd_ol_ctrl.inc");
			} elseif ($rptfmt == "OLrRorC") {// row or column outline format
				include("ts_sum_rpt_diropd_ol_ctrl.inc");
			} elseif ($rptfmt == "OLRandC") {// row and column outline format	
				
			}
		}
	}

	if ($priv == "00") {
		$et = (24-date("H"))*3600 - date("i")*60 - date("s");
		$et = $st - $et;
		$msg = "Server has completed the $rpttypeL ($periodtxt) summary report (in $et seconds).";
		include("ts_sum_msg.inc");
	}
} elseif ($rpttypeS == "MthSum") {
######################################################
######################################################
## write monthly csv report and display on screen
######################################################
######################################################
	//for ($mlyctr=0; $mlyctr<count($monthindex); $mlyctr++) {
	for ($mlyctr=0; $mlyctr<1; $mlyctr++) {
		$wrt_ind = $monthindex[$mlyctr];
		if ($selmonth) {
			$wkstr = $wklist[$yhyqmw[$wrt_ind][1]];
			$tmp = substr($wkstr,5,2);
			$tmp = $mth[$tmp]." ".substr($wkstr,0,4);
		} else {
			$tmp = "$nowks weeks from $fromdate";
		}
		$head = "Monthly Report: ";
		$msg = "Server is currently preparing a summary report for $tmp, please waiting...";
		include("ts_sum_msg.inc");
		if ($leader == "y") {
			if ($priv == "00" || $priv == "10" || $priv == "20") {
				include("ts_sum_rpt_ldr_monthly.inc");
			} else {
				include("ts_sum_rpt_dirm.inc");
			}
		} else {
			if ($priv == "20") {
				include("ts_sum_rpt_ldr_monthly.inc");
			} else {
				if ($rptfmt == "normal") {
					include("ts_sum_rpt_dirm.inc");
				} elseif ($rptfmt == "OLrRorC") {
					//include("ts_sum_rpt_dirm_ol_td.inc");
					include("ts_sum_rpt_dirm_ol_ctrl.inc");
					//include("ts_sum_rpt_dirm_ol_tr.inc");
				} elseif ($rptfmt == "OLRandC") {
				
				}
			}
		}
		if ($priv == "00") {
			$et = (24-date("H"))*3600 - date("i")*60 - date("s");
			$et = $st - $et;
			$msg = "Server has completed the $rpttypeL ($periodtxt) summary report (in $et seconds).";
			include("ts_sum_msg.inc");
			//echo "<br>$msg";
		}
	}
}
######################################################
######################################################
## zip files
######################################################
######################################################
	if ($priv == "00" || $priv == "10") {
		############################# Zip report
		if ($rpttypeS == "MthSum") {
			$str = substr($fromdate,0,7);
			$str = ereg_replace("-","",$str);
		} elseif ($rpttypeS == "QtrSum") {
			$str = $selyear."_".$qtrfour[$selopd];
		} elseif ($rpttypeS == "HfySum") {
        	if ($selopd ==0 ) {
				$str = $selyear."_Jan-June";
			} else {
				$str = $selyear."_Jul-Dec";
			}
		} elseif ($rpttypeS == "YlySum") {
			$str = $selyear;
			$selyear++;
			$str = $str."-".$selyear;
		} elseif ($rpttypeS == "SlpSum") {
			$strs = substr($fromdate,0,7);
			$strs = ereg_replace("-","",$strs);
			$stre = substr($todate,0,7);
			$stre = ereg_replace("-","",$stre);
			$stry = substr($stre,0,4);
			$strm = substr($stre,4,6);
			if ($strm == "01") {
				$stry = $stry-1;
				$strm = "12";
			} else {
				$strm--;
				if ($strm<10) {
					$strm = "0".$strm;
				}
			}
			$stre = $stry.$strm;
			$str=$strs."-".$stre;
		}
		$datestr = $str;
		$fileprefix = $email_name."_".$rpttypeS;
		$zipfolder = "zipfile";
		$rptfolder = "/usr/local/apache/htdocs/report";
	
		$echyes = "n";
		$cmdstr = "rm -f $rptfolder/$email_name*zip";
		exec($cmdstr );
		//echo "<br>$cmdstr<br>";
		if ($echyes == "y") { echo "$cmdstr<br>";}

		//$cmdstr = "chmod 777 $rptfolder/$email_name_*";
		//exec($cmdstr );
		//if ($echyes == "y") { echo "$cmdstr<br>";}
		
		echo "<a id=zipsection><hr></a>";
		
		########################### Process files for GHR	
		$filepat = "$fileprefix*GHR*";
		$filepat = "$fileprefix*\(pc\+min\)\_GHR.csv";
		/*
			$filelist["csv"][$tabno] = $filetitle0."_PP_AllCodes($permin)_GHR.csv";
			$filelist["html"][$tabno] = $filetitle0."_PP_AllCodes($permin)_GHR.html";
			$filelist["csv"][$tabno] = $filetitle0."_PP_SubSet(pc+min)_GHR.csv";
			$filelist["html"][$tabno] = $filetitle0."_PP_SubSet(pc+min)_GHR.html";
		//*/
		//requested by Terry on 04/11/2003

		if ( $rlaserver == $thisserver ) {
			$newzip = processzipfile($rptfolder, $zipfolder, $filepat, $email_name, $datestr, "_$rpttypeS"."_ghr");
		}
		if ($leader != "y") {
		//if ($priv == "00") {
		if ( $rlaserver == $thisserver ) {
			echo "<br><b>DOWNLOAD zippped file <a href=../report/$newzip>$newzip</a> for GHR.</b><br>";
		}
		//}
		}
		########################### Process all files
		$filepat = "$fileprefix*csv";
		$newzip = processzipfile($rptfolder, $zipfolder, $filepat, $email_name, $datestr, "_$rpttypeS"."_allcsv");
		//if ($priv == "00") {
			echo "<br><b>DOWNLOAD zippped CSV file <a href=../report/$newzip>$newzip</a> for all CSV files.</b><br>";
		//}
		
if ( $rlaserver == $thisserver ) {	
		echo "<hr>";
		include("ts_sendmailto_GHR.inc");
}		
		############################# script for root user
		/*
		//Files for GHR
		$fpshl	=	fopen($svrfld."zip_script_GHR",'w+');
		$lfolder1 = "/home/$adminname";
		$lfolder = $lfolder1."/GHR";
		if ($fpshl) {
			fputs($fpshl,"#!/bin/bash\n");
			fputs($fpshl,"rm -f $lfolder1/RLA*TSrpt.zip;\n");
			fputs($fpshl,"rm -f $lfolder/*;\n");
			$str = ereg_replace(" ", "\\ ", $newfile0[0][0]);
			$str = "cp -f ".$email_name."*GHR* $lfolder/;\n";
			fputs($fpshl,$str);
			fputs($fpshl,"zip -rp $lfolder $lfolder;\n");
			$str = substr($fromdate,0,7);
			$str = "RLA".ereg_replace("-","",$str)."TSrpt.zip";
			fputs($fpshl,"cp -f $lfolder.zip $lfolder1/$str;\n");
			fputs($fpshl,"rm -f $lfolder.zip\n");
			//echo $str."<br>";
			fclose($fpshl);
			if ($priv == "00") {
				echo "<hr>A shell script for zipping files for Greenhill Rd has been generated. ";
				echo "<FONT size=2>View [<A href=\"../report/zip_script_GHR\" target=_blank><B>Script</B></A>].<BR>";
				$msg = "Execute ./ghr_zip from root accout.<Br><Br>";
				$msg = $msg."<b>CSV and HTML files For GHR are at $lfolder<BR>";
				$msg = $msg."Zip file is $lfolder1/$str</b></FONT><BR>";
			}
		}

		//All Files
		$fpshl	=	fopen($svrfld."zip_script_all",'w+');
		$lfolder1 = "/home/$adminname";
		$lfolder = $lfolder1."/TSALL";
		if ($fpshl) {
			fputs($fpshl,"#!/bin/bash\n");
			fputs($fpshl,"rm -f $lfolder1/TSALL*TSrpt.zip;\n");
			fputs($fpshl,"rm -f $lfolder/*;\n");
			$str = ereg_replace(" ", "\\ ", $newfile0[0][0]);
			$str = "cp -f '".$filetitle0."_P'* $lfolder/;\n";
			fputs($fpshl,$str);
			fputs($fpshl,"zip -rp $lfolder $lfolder;\n");
			$str = substr($fromdate,0,7);
			$str = "TSALL".ereg_replace("-","",$str)."TSrpt.zip";
			fputs($fpshl,"cp -f $lfolder.zip $lfolder1/$str;\n");
			fputs($fpshl,"rm -f $lfolder.zip\n");
			//echo $str."<br>";
			fclose($fpshl);
			if ($priv == "00") {
				echo "A shell script for zipping all files has been generated. ";
				echo "<FONT size=2>View [<A href=\"../report/zip_script_all\" target=_blank><B>Script</B></A>].<BR>";
				$msg = $msg."<br><b>All CSV and HTML files are at $lfolder<BR>";
				$msg = $msg."Zip file is $lfolder1/$str</b></FONT><BR>";
				echo $msg."<br>";
			}
		}
		//*/
	}
###########################################################
	/*
		project code array:
			## Array $fromcodetono[$code] = $i: $codeplusgroup
				find code sequence number in Array $ordered_code
			## Array $ordered_code: $codeplusgroup
			$ordered_code[0][$i]: brief_code/prefix,
			$ordered_code[1][$i]: special
			$ordered_code[2][$i]: div15 if empty it is a group code
			$ordered_code[3][$i]: description/prefix lable,
			## Array $codegrprange: $no_prefix
			$codegrprange[0][$i]: group lable position in array $ordered_code
			$codegrprange[1][$i]: first code position in this group
			$codegrprange[2][$i]: last code position in this group
			## Array $codelist[0][$i]: $codeno
		satff array:
			## Array $stafflist[0][$i]: $staffno
			$stafflist[0][$i]: email_name
			$stafflist[1][$i]: title
			$stafflist[2][$i]: first_name
			$stafflist[3][$i]: last_name
			## Array $fromstafftono[$email_name] = $i: find email_name sequence number in Array $ordered_code
		weeks array:
			## Array $wklist[$i]: $nowks, in the format "yyyy-mm-dd"
		yearly, half yearly, quarterly, monthly and weekkly array:
			## Array $yhyqmw: $timeseries
			$yhyqmw[$i][0]: one of "y", "hy", "q", "m" and "w"
			$yhyqmw[$i][1]: starting date, integer, can be converted to wk str by using $wklist[$i]
			$yhyqmw[$i][2]: ending date, integer, can be converted to wk str by using $wklist[$i]
			$yhyqmw[$i][3]: number of weeks included
			## Array $wkstrtono["yyyy-mm-dd"] = $i: $timeseries
	*/

} elseif ($analysisreport && $msg !="") {
	echo $msg;
}
backtotop();

function processzipfile($rptfolder, $zipfolder, $filepat, $email_name, $datestr, $fextra) {
	$fextra = strtolower($fextra);

/*
cp -f /usr/local/apache/htdocs/report/$adminname_MthSum*GHR* /usr/local/apache/htdocs/report/zipfile/
cd /usr/local/apache/htdocs/report/
tar -czf /usr/local/apache/htdocs/report/zipfile.tar.gz zipfile
cp /usr/local/apache/htdocs/report/zipfile.tar.gz /usr/local/apache/htdocs/report/$adminname_200106_mthsum_ghr.tar.gz
rm -f /usr/local/apache/htdocs/report/zipfile.tar.gz
rm -f /usr/local/apache/htdocs/report/zipfile/*
//*/
	$debug = "n";
	# copy required files to $zipfolder folder
	$cmdstr = "cp -f $rptfolder/$filepat $rptfolder/$zipfolder/";
	exec($cmdstr );
	if ($debug == "y") {
		echo "$cmdstr<br>";
	}
	
	# zip files
	$strzip = "$rptfolder/$zipfolder";
	
/*
	$cmdstr = "tar -czf $strzip.tar.gz $strzip";
	exec($cmdstr);
	if ($debug == "y") {
		echo "$cmdstr<br>";
	}
	$cmdstr = "cd $rptfolder";
	exec($cmdstr);
	if ($debug == "y") {
		echo "$cmdstr<br>";
	}
//*/
	
	$cmdstr = "cd $rptfolder\ntar -czf $strzip.tar.gz $zipfolder";
	exec($cmdstr);
	if ($debug == "y") {
		echo "$cmdstr<br>";
	}
	
	# rename zip file
	$newzip = $email_name."_$datestr$fextra.tar.gz";
	$cmdstr = "cp $strzip.tar.gz $rptfolder/$newzip";
	exec($cmdstr);
	if ($debug == "y") {
		echo "$cmdstr<br>";
	}
		
	# remove old zip file
	$cmdstr = "rm -f $strzip.tar.gz";
	exec($cmdstr);
	if ($debug == "y") {
		echo "$cmdstr<br>";
	}

	$cmdstr = "rm -f $rptfolder/$zipfolder/*";
	exec($cmdstr );
	if ($debug == "y") {
		echo "$cmdstr<br>";
	}
	
	return $newzip;
}

function backtotop(){
	echo "<br><br><hr><a href=#top>Back to top</a><a id=end></a><br><br><br>";
}

function tabforbackword($tabno, $notable, $bulsize, $tablecaption, $csv, $htm) {
	echo "<a id=$tabno></a>";
	$j = $tabno + 1;
	echo "<br><b>Table $j: $tablecaption</b><br>";
	echo "<ul>";
	echo "<li><font size=\"$bulsize\">Download or open a [".
		"<a href=\"../report/$csv\" target=\"_blank\"><b>CSV</b></a>] or a [".
		"<a href=\"../report/$htm\" target=\"_blank\"><b>HTML</b></a>]".
		" format file.</font><br>";
	echo "<li><font size=\"$bulsize\">To ";
	echo "[<a href=#top>Top</a>]&nbsp;";
	echo "[<a href=#tabind>Table Index</a>]&nbsp;";
	if ($tabno) {
		$ptab = $tabno - 1;
		echo "[<a href=#$ptab>Previous Table</a>]&nbsp;";
	}
	if ($tabno < $notable-1) {
		$ptab = $tabno + 1;
		echo "[<a href=#$ptab>Next Table</a>]&nbsp;";
	}
	echo "[<a href=#end>End</a>]";		
	echo "</font><br></li>";
	echo "</ul>";
}

?></b></font>
<p>
</body>
