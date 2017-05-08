<html>

<head>
<title>Marketing Summary</title>
</head>
<body text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG" leftmargin="20">

<?php
########################################
##		Access control
########################################
include('str_decode_parse.inc');

include("userinfo.inc");
$qry	=	"?".base64_encode($userinfo);
include("find_domain.inc");	
echo '<h2 align=center>Summary for Project Cost</h2>';// onMouseOver=\"window.status='This is a test.'; return true;\"
$statuscontext = "Refresh";
include("self_status.inc");
echo "<a id=top><p align=center><a href=\"".$PHP_SELF."$qry\" $status>[Refresh]</a><hr>";


include('connet_root_once.inc');

$sql = "SELECT codes 
        FROM timesheet.projleader 
        WHERE leader='$email_name';";
    $result = mysql_query($sql);
    list($codes) = mysql_fetch_array($result);
    $tmp = explode("@", $codes);
	for ($i=0; $i<count($tmp); $i++) {
		//echo "$tmp[$i]<br>";
		$mycodes[$tmp[$i]] = 1;
	}
echo "<table border=0><tr><td>";
########################################
##		date range
########################################
echo '<form method=POST action='.$PHP_SELF.'>';
include("userstr.inc"); //$userstr
if ($priv != "00") {
	echo "<table border=0>";
} else {
	echo "<table border=0>";
}
echo "<tr><td colspan=2><b>Select Period to Search</b></td></tr>";
echo "<tr><td colspan=2>&nbsp;</td></tr>";
echo "<tr><td><b>Date Start</b></td><td>";
	$calfromyear	=	"calyearstart";
	$calfrommonth	=	"calmonthstart";
	$calfromday	=	"caldaystart";
	if ($calyearstart) {
		$year =	$calyearstart;
		$month =	$calmonthstart;
		$day =	$caldaystart;
	} else {
		$year =	date("Y")-1;
		$month =	"07";
		$day =	"01";
		$calyearstart = $year;
		$calmonthstart = $month;
		$caldaystart = $day ;
	}
	include("calender_mmddyyyy.inc");
	echo "</td><td>&nbsp;</td></tr>";
echo "<tr><td><b>Date End</b></td><td>";
	$calfromyear	=	"calyearend";
	$calfrommonth	=	"calmonthend";
	$calfromday	=	"caldayend";
	$moffset = 0;
	if ($calyearend) {
		$year =	$calyearend;
		$month =	$calmonthend;
		$day =	$caldayend;
	} else {
		$year =	date("Y");
		$month =	"07";
		$day =	"01";
		$calyearend = $year;
		$calmonthend = $month;
		$caldayend = $day ;
	}
	include("calender_mmddyyyy.inc");
	echo "</td></tr>";
	
	echo "<tr><td><b>Project Code</b></td><td>";
	echo "<select name=projcodeid>";
		$sql = "SELECT projcode_id, brief_code 
        	FROM timesheet.projcodes 
        	ORDER BY brief_code;";
		$result = mysql_query($sql);
    	include("err_msg.inc");

    	while (list($projcode_id, $brief_code) = mysql_fetch_array($result)) { 
    	if ($priv == "20") {
    		if ($mycodes[$brief_code]) {
    			if ($projcodeid == $projcode_id) {
        			echo "<option value=$projcode_id selected>$brief_code</option>";
        		} else {
        			echo "<option value=$projcode_id>$brief_code</option>";
        		}
        	}
        } elseif ($priv == "00" || $priv == "10") {
    			if ($projcodeid == $projcode_id) {
        			echo "<option value=$projcode_id selected>$brief_code</option>";
        		} else {
        			echo "<option value=$projcode_id>$brief_code</option>";
        		}
        }
        	$pcodeid_br[$projcode_id] = $brief_code;
    	}
	echo "</select></td></tr>";
	
	echo "<tr><th colspan=2><input type=\"submit\" value=\"Search Timesheet\" name=\"searchtimesheet\"></th></tr>";
echo "</form></table>";
echo "</td></tr></table><p>";

##################
	$sql = "SELECT charging_code, rate 
        FROM timesheet.chargecode;";
	$result = mysql_query($sql);
	include("err_msg.inc");
	while (list($charging_code, $rate) = mysql_fetch_array($result)) {
		$ccodecost[$charging_code] = $rate;
	}

	$sql = "SELECT email_name as ename1, first_name as fn, 
            last_name as ln 
       FROM timesheet.employee;";
	$result = mysql_query($sql);
	include("err_msg.inc");
	while (list($ename1, $fn, $ln) = mysql_fetch_array($result)) {
		$sql = "SELECT email_name as ename2, charge_code 
        	FROM timesheet.chargeclassfn 
        	WHERE email_name='$ename1';";
    	$result1 = mysql_query($sql);
    	include("err_msg.inc");
    	list($ename2, $charge_code) = mysql_fetch_array($result1);
    	$namecost[$ename2] = $ccodecost[$charge_code];
    	$fullname[$ename2] = "$fn $ln";
    	//echo "$ename2: $charge_code: ".$namecost[$ename2]."<br>";
		if (!$charge_code) {
			if ($ename1 != "njp@rla.com.au") {
			$sql = "INSERT INTO timesheet.chargeclassfn
				VALUES('$ename1', 'D');";
    		echo "$sql<br>";
    		$result2 = mysql_query($sql);
    		include("err_msg.inc");
    		}
		}
	}
//exit;

###############
if (!$projcodeid) {
	exit;
}

if ($searchtimesheet) {	
	echo "<hr>";
	flush();

	$year1 = $calyearstart;
	$month1 = $calmonthstart;
	$day1 = $caldaystart;
	if ($year1 <= 2000) {
		$year1 = 2000;
	}
	if ($year1 == 2000 && (int)($month1) <= 6) {
		$month1 = "07";
		$day1 = "01";
	}
	$year2 = $calyearend;
	$month2 = $calmonthend;
	$day2 = $caldayend;
	$start_date = $year1."-".$month1."-".$day1;
	$end_date = $year2."-".$month2."-".$day2;
	
} else {
	echo "<hr>";
	$start_date = "$calyearstart-$calmonthstart-$caldaystart";
	$end_date = "$calyearend-$calmonthend-$caldayend";
}

#	
$brief_code = $pcodeid_br[$projcodeid];

/* table: marketing. columns: email_name, brief_code, time, company_name, country, entry_no, */
$sql = "SELECT t1.email_name as ename, sum(t2.minutes) as minutes
	FROM timesheet.entry_no as t1, timesheet.timedata as t2
	WHERE t1.yyyymmdd>='$start_date' and t1.yyyymmdd<='$end_date' 
		and t1.entry_no=t2.entry_no
		and t2.brief_code='$brief_code'
	GROUP by ename";//
$result = mysql_query($sql);
include("err_msg.inc");
//echo "$sql<br>";
echo "<br>";
echo "<h3>Project $brief_code total cost<br>for the period from $start_date to $end_date</h3>";
echo "<table border=1><th>Full Name</th><th>Email Name</th>
	<th>$/Hour</th><th>Hour</th><th>Sub Total</th>";
if ($priv == "00") {
	echo "<th>Cost (%)</th>";
}
echo "</tr>";
$totalhrs = 0;
$totalcost = 0;
$i = 0;
while (list($ename, $minutes) = mysql_fetch_array($result)) {
		$hrs =$minutes/60;
		$hrs_o = number_format($hrs, 2);
		$totalhrs = $totalhrs + $hrs;
		$fname = $fullname[$ename];
		$crhrs = $namecost[$ename];
		$subcost[$i] = $hrs*$crhrs;
		$totalcost = $totalcost + $subcost[$i];
		$subcost0 = number_format($subcost[$i], 2);
		$tabrow[$i] = "<tr><td>$fname</td><td align=middle>$ename</td><td align=right>$crhrs</td>
			<td align=right>$hrs_o</td><td align=right>$subcost0</td>";
		
		$i++;
}
for ($j=0; $j<$i; $j++) {
	$subper =100.0*$subcost[$j]/$totalcost;
	if ($subper>5) {
		$subper = "<td align=right><b>".number_format($subper, 2)."</b></td>";
	} else {
		$subper = "<td align=right>".number_format($subper, 2)."</td>";
	}
	echo $tabrow[$j];
	if ($priv == "00") {
		echo $subper;
	}
	echo "</tr>";
}
echo "<b>Total Hours: $totalhrs.</b><br>";
echo "<b>Total Cost: US ".number_format($totalcost,0).".</b><br><br>";
//echo "<tr><th colspan=5 align=left></th></tr>";
//echo "<tr><th colspan=5 align=left>Total Cost: $totalcost</th></tr>";
echo "</table><p>";
exit;
$dir = "/usr/local/apache/htdocs";
$newfile = "/report/$email_name"."_mktsum.csv";
$fpcsv	=	fopen($dir.$newfile,'w+');
	
$tmp = "Marketing Summary for the Period from $start_date to $end_date";
echo "<h2>$tmp</h2>";
if ($fpcsv) {
	fputs($fpcsv,"$tmp\n\n");		
}

for ($i=0; $i<$nocountry; $i++) {
	for ($j=0; $j<$nocomp; $j++) {
		$ctytotal[$i] = $ctytotal[$i] + $marketsum[$countries[$i]][$comp[$j]];
	}
}

echo "<table border=1>";
echo "<tr><th>Country/Company</th><th>Time (hours)</th></tr>";
if ($fpcsv) {
	fputs($fpcsv,"Country/Company,Time (hours)\n");	
}
$totime=0;
for ($i=0; $i<$nocountry; $i++) {
	if ($ctytotal[$i]) {
		echo "<tr><td><b>".$countries[$i]."</b></td><td align=right><b>".$ctytotal[$i]."</b></td></tr>";
		if ($fpcsv) {
			fputs($fpcsv,$countries[$i].",".$ctytotal[$i]."\n");	
		}
		
		for ($j=0; $j<$nocomp; $j++) {
			$time = $marketsum[$countries[$i]][$comp[$j]];
			if ($time) {
				$totime= $totime + $time;
				$time =  number_format($time, 2);
				echo "<tr><td>&nbsp;&nbsp".$comp[$j]."</td><td align=right>$time</td></tr>";
				if ($fpcsv) {
					fputs($fpcsv,"  ".$comp[$j].",$time\n");		
				}
			}
		}
	}
}
echo "</table>";
echo "<p><b>Total Marketing Time is $totime hours.</b><br>";
if ($fpcsv) {
	fputs($fpcsv,"\nTotal Marketing Time is $totime hours.");		
}

echo "<br><a href=\"http://".getenv("server_name")."$newfile\">Download csv file.</a><br>";
$statuscontext = "Back To Top";
include("self_status.inc");
echo "<hr><a href=#top $status>Back to top</a><br>";
?>
</body>
