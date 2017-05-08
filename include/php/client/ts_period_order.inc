<html>
<?php
	$str	=	"'$fromdate'";	
	# find day of the week for fromdate
	$sql = "select DAYOFWEEK($str) as wkds;";
	//echo "$sql <br>";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($wkds) = mysql_fetch_array($result);
	if ($wkds == 6){
    	$doffset	=	0;
	} elseif ($wkds == 7){
    	$doffset	=	6;
	} else {
    	$doffset	=	6 - $wkds;
   	}
	$wkds = $doffset;

	$sql = "select TO_DAYS($str) daynos;";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($daynos) = mysql_fetch_array($result);
	$daynos = $daynos + $wkds;
	
	$str	=	"'$todate'";	
	$sql = "select TO_DAYS($str) daynoe;";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($daynoe) = mysql_fetch_array($result);
	$i=$daynos;
	$nowks = 0;
	while ($i<$daynoe) {
		$sql = "select FROM_DAYS($i) yyyymmdd;";
		$result = mysql_query($sql);
		include("err_msg.inc");
		list($yyyymmdd) = mysql_fetch_array($result);
		$wklist[$nowks] = $yyyymmdd;
		//echo $nowks.": ".$yyyymmdd."<br>";
		$nowks++;
		$i = $i + 7;
	}

	for ($i=0; $i<$nowks; $i++) {
		$tmp = $wklist[$i];
		$tm = substr($wklist[$i],5,2);
		if (substr($tm,0,1) == "0") {
			$tm = substr($tm,1,1);
		}
		$mwk[$i] = $tm.substr($wklist[$i],8,2);
		//echo $wklist[$i].", ".$mwk[$i]."<br>";
	}

	//echo $wklist[0]."<br>";
	$mthfirst = substr($wklist[0],5,2);
	//echo $mthfirst."<br>";
	if (substr($mthfirst,0,1) == "0") {
		$mthfirst= substr($mthfirst,1,1);
	}
	
	# define financial ending date
	$month_day_end = "0630";
	$year0 = substr($wklist[0],0,4) + 1;
	$year_end = $year0.$month_day_end;

	# define half yearly ending date
	$halfy[0] = "101";
	$halfy[1] = "701";
	//echo $halfy[$hystart]."<br>";

	# define quarterly date
	$qtrly[0] = "101";
	$qtrly[1] = "401";
	$qtrly[2] = "701";
	$qtrly[3] = "1001";
	//echo $qtrly[0]."-".$qtrly[1]."-".$qtrly[2]."-".$qtrly[3]."<br>";

	# define monthly ending date
	for ($i=1; $i<13; $i++) {
		$mthly[$i-1] = $i."01";
	}

	$mthfirst = $mthfirst - 1;
	//echo $mthly[$mthfirst]."<br>";

	$timeseries = 0;
	$yhyqmw[$timeseries][0] = "y";
	$yhyqmw[$timeseries][1] = 0; //starting data
	$lastyly = $timeseries;
	$timeseries++;
	
	$yhyqmw[$timeseries][0] = "hy";
	$yhyqmw[$timeseries][1] = 0; //starting data
	$lasthyly =  $timeseries;
	$lasthyly_date = $mwk[0];
	$timeseries++;

	$yhyqmw[$timeseries][0] = "q";
	$yhyqmw[$timeseries][1] = 0; //starting data
	$lastqly =  $timeseries;
	$lastqrtly_date = $mwk[0];
	$timeseries++;

	$yhyqmw[$timeseries][0] = "m";
	$yhyqmw[$timeseries][1] = 0; //starting data
	$lastmly =  $timeseries;
	$lastmly_date = $mwk[0];
	$timeseries++;
	
	$yhyqmw[$timeseries][0] = "w";
	$yhyqmw[$timeseries][1] = 0; //starting data
	$wkstrtono[$wklist[0]] = $timeseries;
	$timeseries++;

	for ($i=1; $i<$nowks; $i++) {
		$yeartmp = substr($wklist[$i],0,4).substr($wklist[$i],5,2).substr($wklist[$i],8,2);
		$mdd = $mwk[$i];
		
		## yearly
		if ($yeartmp > $year_end) {
			$yhyqmw[$lastyly][2] = $i - 1;
			$yhyqmw[$timeseries][0] = "y";
			$yhyqmw[$timeseries][1] = $i;
			$lastyly = $timeseries;
			$year0++;
			$year_end = $year0.$month_day_end;
			$timeseries++;
		}
		
		## half yearly
		$hy_new = 0;
		
		if ($mdd < $halfy[1] && $lasthyly_date > $halfy[1]) {
			$hy_new = 1; // from 1231 to 0101
		} elseif ($mdd > $halfy[1] && $lasthyly_date < $halfy[1]) {
			$hy_new = 1; // from 0630 to 0701
		}
		if ($hy_new == 1) {
			$yhyqmw[$lasthyly][2] = $i - 1;
			$yhyqmw[$timeseries][0] = "hy";
			$yhyqmw[$timeseries][1] = $i;
			$lasthyly= $timeseries;
			$timeseries++;
		}
		$lasthyly_date = $mdd;

		## quarterly
		$qrt_new = 0;
		//echo $lastqrtly_date."-".$mdd."<br>";
		for ($iq=1; $iq<count($qtrly); $iq++) {
			if ($lastqrtly_date <= $qtrly[$iq] && $mdd >= $qtrly[$iq]) {
				$qrt_new = 1;
				//echo "New quarter.<br>";
				break;
			}
		}
		if ($lastqrtly_date > $qtrly[3] && $mdd < $qtrly[1]) {
			$qrt_new = 1; // from 1231 to 0101
			//echo "New quarter.<br>";
		}
		if ($qrt_new == 1) {
			$yhyqmw[$lastqly][2] = $i - 1;
			$yhyqmw[$timeseries][0] = "q";
			$yhyqmw[$timeseries][1] = $i;
			$lastqly= $timeseries;
			$timeseries++;
		}
		$lastqrtly_date = $mdd;

		## monthly
		$mth_new = 0;
		//echo $lastmly_date."-".$mdd."<br>";
		for ($im=1; $im<count($mthly); $im++) {
			if ($lastmly_date < $mthly[$im] && $mdd >= $mthly[$im]) {
				$mth_new = 1;
				//echo "New month-$mthly[$im]<br>";
				break;
			}
		}
		if ($lastmly_date > $mthly[11] && $mdd < $mthly[1]) {
			$mth_new = 1; // from 1231 to 0101
			//echo "New month 12.<br>";
		}
		if ($mth_new == 1) {
			$yhyqmw[$lastmly][2] = $i - 1;
			$yhyqmw[$timeseries][0] = "m";
			$yhyqmw[$timeseries][1] = $i;
			$lastmly= $timeseries;
			$timeseries++;
		}
		$lastmly_date = $mdd;

		$yhyqmw[$timeseries][0] = "w";
		$yhyqmw[$timeseries][1] = $i;
		$wkstrtono[$wklist[$i]] = $timeseries;
		$timeseries++;
	}

	$yhyqmw[$lastyly][2] = $i - 1;
	$yhyqmw[$lasthyly][2] = $i - 1;
	$yhyqmw[$lastqly][2] = $i - 1;
	$yhyqmw[$lastmly][2] = $i - 1;
	
	for ($i=0; $i<$timeseries; $i++) {
		if ($yhyqmw[$i][0] == "w") {
			$yhyqmw[$i][3] = 1;
		} else {
			$t = $yhyqmw[$i][2]-$yhyqmw[$i][1]+1;
			$yhyqmw[$i][3] = $t;
			//$yhyqmw[$i][3] = "(".$yhyqmw[$i][2]."-".$yhyqmw[$i][1].") = $t";
		}
 	}
 	
## write to csv file
	$filetitle = "Staff_PrefixPlusCode_PeriodSeries".date("Ymd").".csv";
	$newfile	=	"/usr/local/apache/htdocs/report/$filetitle";
	$fplist	=	fopen($newfile,'w+');
	if (!$fplist) {
		$wmsg = 0;
	} else {
		$wmsg = 1;
		if ($priv == "00" && $rlaserver == $thisserver){
			echo "<li><a href=\"../report/$filetitle\" target=\"_blank\"><font size=\"$bulsize\">View or download staff, prefix+code and period series.</font></a></li>";
		}
	}
	if ($wmsg = 1) {
		$tblno=1;
 		fputs($fplist,"Table $tblno, Period Series\n\n");
 		fputs($fplist,"No,Period,Date Start,Date End,Weeks\n");
 		fputs($fplist,"$"."i,$"."yhyqmw[$"."i][0],$"."wklist[$"."yhyqmw[$"."i][1]],$"."wklist[$"."yhyqmw[$"."i][2]],$"."yhyqmw[$"."i][3]\n");
		for ($i=0; $i<$timeseries; $i++) {
 			fputs($fplist,"$i,".$yhyqmw[$i][0].",".
			$wklist[$yhyqmw[$i][1]].",".
			$wklist[$yhyqmw[$i][2]].",".
			$yhyqmw[$i][3]."\n");
		}
 		fputs($fplist,"$"."timeseries\n\n");
		$tblno++;

 		fputs($fplist,"Table $tblno, Week No Convertion\n\n");
 		fputs($fplist,"No,DerivedNo,Week\n");
 		fputs($fplist,"$"."i,$"."wkstrtono[$"."yhyqmw[$"."i][0]],$"."wklist[$"."i]\n");
		for ($i=0; $i<$nowks; $i++) {
			$j = $wkstrtono[$wklist[$i]];
			fputs($fplist,"$i,$j,$wklist[$i]\n");
		}
 		fputs($fplist,"$"."nowks\n\n");
		$tblno++;

		fputs($fplist,"Table $tblno,Staff List ($staffno)\n\n");
		fputs($fplist,"No,Email Name,Derived No\n");
		fputs($fplist,"$"."i,$"."stafflist[0][$"."i],$"."fromstafftono[$"."ename]\n");
		for ($i=0; $i<$staffno; $i++) {
			$ename	=	$stafflist[0][$i];
			$tmp = $fromstafftono[$ename];
			fputs($fplist,"$i,$ename,$tmp\n");
		}
		fputs($fplist,"$"."staffno\n\n");
		$tblno++;
		
		fputs($fplist,"Table $tblno, Ordered Prefix and Code ($codeplusgroup)\n\n");
		fputs($fplist,"No,Derived No,Special,Div 15,Code\n");
		fputs($fplist,"$"."i,$"."fromcodetono[$"."code],$"."ordered_code[1][$"."i],$".
			"ordered_code[2][$"."i],$"."ordered_code[0][$"."i]\n");
		for ($i=0; $i<$codeplusgroup; $i++) {
			$j =	$i + 1;
			$code = $ordered_code[0][$i];
			$j = $fromcodetono[$code];
			fputs($fplist,"$i,$j");
			for ($k=1; $k<3; $k++) {
				$tmp = $ordered_code[$k][$i];
				//$tmp = underscores($tmp);
				$tmp = ereg_replace(",", "@",$tmp);
				fputs($fplist,",$tmp");
			}
			$tmp = $ordered_code[0][$i];
			//$tmp = underscores($tmp);
			$tmp = ereg_replace(",", "@",$tmp);
			fputs($fplist,",$tmp\n");
		}
		fputs($fplist,"$"."codeplusgroup\n\n");
		$tblno++;
		
		fputs($fplist,"Table $tblno,Project Code Prefix($no_prefix)\n\n");
		fputs($fplist,"No,Code,Lable,Length\n");
		fputs($fplist,"$"."i,$"."codeprefix_list[$"."i][1],$"."codeprefix_list[$"."i][2],$".
			"codeprefix_list[$"."i][0]\n");
		for ($i=0; $i<$no_prefix; $i++) {
			$j =	$i + 1;
			fputs($fplist,"$j,".$codeprefix_list[$i][1].",".$codeprefix_list[$i][2].",".$codeprefix_list[$i][0]."\n");
		}
		fputs($fplist,"$"."no_prefix\n\n");
		$tblno++;
		
		fputs($fplist,"Table $tblno,Project Code List ($codeno)\n\n");
		fputs($fplist,"No,Code\n");
		fputs($fplist,"$"."i,$"."codelist[0][$"."i]\n");
		for ($i=0; $i<$codeno; $i++) {
			$j =	$i + 1;
			$code	= $codelist[0][$i];
			$code = underscores($code);
			fputs($fplist,"$j,$code\n");
		}
		fputs($fplist,"$"."codeno\n\n");
		$tblno++;
		
		fputs($fplist,"Table $tblno,Prefix code and their positions ($no_prefix)\n\n");
		fputs($fplist,"No,Prefix,Lable,Sum Row,Row s,Row e\n");
		fputs($fplist,"$"."i,$"."codeprefix_list[$"."i][1],$"."codeprefix_list[$".
			"i][2],$"."codegrprange[0][$"."i],$"."codegrprange[1][$"."i],$".
			"codegrprange[2][$"."i]\n");
		for ($i=0; $i<$no_prefix; $i++) {
			$j =	$i + 1;
			fputs($fplist,"$i,".$codeprefix_list[$i][1].",".$codeprefix_list[$i][2].",".
				$codegrprange[0][$i].",".$codegrprange[1][$i].",".$codegrprange[2][$i]."\n");
		}
		fputs($fplist,"$"."no_prefix\n\n");
		$tblno++;
		//*/

		fclose($fplist);
	}

 	/*
	echo "<table border=1>";
	echo "<tr><th>No</th><th>Period</th><th>Date Start</th><th>Date End</th><th>Weeks</th></tr>";
	for ($i=0; $i<$timeseries; $i++) {
		echo "<tr><td>$i</td><td>".$yhyqmw[$i][0]."</td><td>".
			$wklist[$yhyqmw[$i][1]]."</td><td>".
			$wklist[$yhyqmw[$i][2]]."</td><td>".
			$yhyqmw[$i][3]."</td></tr>";;
	}
	echo "</table>";

	echo "<table border=1>";
	for ($i=0; $i<$nowks; $i++) {
		$j = $wkstrtono[$wklist[$i]];
		echo "<tr><td>$i</td><td>$wklist[$i]</td><td>$j</td></tr>";
	}
	echo "</table>";
	//*/
	
function leapyear($str) {
	//echo $str."<br>";
	$sql = "select TO_DAYS('$str') daynoe;";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($daynoe) = mysql_fetch_array($result);
	$i = $daynoe + 1;
	$sql = "select FROM_DAYS($i) yyyymmdd;";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($yyyymmdd) = mysql_fetch_array($result);
	//echo $yyyymmdd."<br>";
	return substr($yyyymmdd,8,2);
	/*
		$str = $year0."-02-28";
		$str = leapyear($str);
		if ($str == "29") {
			$mthly[7] = "229";
		} else {
			$mthly[7] = "228";
		}
	*/
}
?>
</html>
