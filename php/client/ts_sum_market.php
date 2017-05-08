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
echo '<h2 align=center>Marketing Summary</h2>';// onMouseOver=\"window.status='This is a test.'; return true;\"
$statuscontext = "Refresh";
include("self_status.inc");
echo "<a id=top><p align=center><a href=\"".$PHP_SELF."$qry\" $status>[Refresh]</a><hr>";


include('connet_root_once.inc');

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
echo "<tr><td colspan=3><b>Select Period to Search</b></td></tr>";
echo "<tr><td colspan=3>&nbsp;</td></tr>";
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
	echo "</td><th valign=bottom><input type=\"submit\" value=\"Search Timesheet\" name=\"searchtimesheet\"></th></tr>";
echo "</form></table>";
echo "</td></tr></table><p>";

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
/* table: marketing. columns: email_name, brief_code, time, company_name, country, entry_no, */
$sql = "SELECT t1.entry_no, t1.yyyymmdd, t1.email_name as ename, t2.time, t2.country, t2.company_name
	FROM timesheet.entry_no as t1, timesheet.marketing as t2
	WHERE t1.yyyymmdd>='$start_date' and t1.yyyymmdd<'$end_date' 
		and t1.entry_no=t2.entry_no
	order by t1.yyyymmdd";
//echo "$sql<br>";
$result = mysql_query($sql);
include("err_msg.inc");
//echo "<br><table border=1><th>Entry ID</th><th>Date</th><th>Person</th><th>Minutes</th><th>Country</th><th>Company</th>";
$i = 0;
$totalminutes = 0;
$nocomp=0;
$nocountry=0;
while (list($entry_no, $yyyymmdd, $ename, $time, $country, $company_name) = mysql_fetch_array($result)) {
	$country = trim($country);
	$company_name = trim($company_name);
	if ( $country == "NA" &&  $company_name == "NA") {
		$country = "GENERAL";
		$company_name = "GENERAL";
	}
	if ( $country == "" &&  $company_name == "GENERAL") {
		$country = "GENERAL";
	}
	if ($country &&  $company_name == "NA") {
		$company_name = "GENERAL";
	}
	if ($country  == "AUST.") {
		$country = "AUSTRALIA";
	}
	
	if ( !$country &&  $company_name) {
		if ($company_name == "BEMIS" ) {
			$company_name = "BEMIS CURWOOD";
		}
		if ($company_name == "MAN" ) {
			$company_name = "MAN ROLAND";
		}
		$sql = "SELECT country FROM timesheet.company WHERE company_name like '%$company_name%'";
		//echo "$sql<br>";
		$result1 = mysql_query($sql);
		include("err_msg.inc");
		list($country) = mysql_fetch_array($result1);
	}
	
	if ($time) {
		$time = number_format($time/60, 2);
		$i++;
		$marketsum[$country][$company_name] = $marketsum[$country][$company_name] + $time;
		$totalminutes = $totalminutes + $time;
		//echo "<tr><td>$entry_no</td><td>$yyyymmdd</td><td>$ename</td><td>$time</td><td>$country</td><td>$company_name</td></tr>";;
		
		$new=1;
		for ($j=0; $j<$nocomp; $j++) {
			if ($comp[$j] == $company_name) {
				$new=0;
				break;
			}
		}
		if ($new == 1) {
			$comp[$nocomp] = $company_name;
			$nocomp++;
		}
			
		$new=1;
		for ($j=0; $j<$nocountry; $j++) {
			if ($countries[$j] == $country) {
				$new=0;
				break;
			}
		}
		if ($new == 1) {
			$countries[$nocountry] = $country;
			$nocountry++;
		}
	}
}
//echo "</table><p>";
sort($countries);
sort($comp);
$totentry = $i;

/*
echo "<b>Number of Country = $nocountry</b><br>";
for ($i=0; $i<$nocountry; $i++) {
	echo $i."&nbsp;&nbsp;".$countries[$i]."<br>";
}
echo "<b>Number of Company = $nocomp</b><br>";
for ($i=0; $i<$nocomp; $i++) {
	echo $i."&nbsp;&nbsp;".$comp[$i]."<br>";
}
echo "Number of entry = $totentry<br>";
echo "Total Minutes = $totalminutes<br>";

$sql = "SELECT company_name FROM timesheet.company ORDER BY company_name";
$result = mysql_query($sql);
include("err_msg.inc");
$nocomp = 0;
while (list($company_name) = mysql_fetch_array($result)) {
	//echo "$company_name<br>";
	$comp[$nocomp] = $company_name;
	$nocomp++;
}

$sql = "SELECT country FROM timesheet.country where country!='AUST.' ORDER BY country";
$result = mysql_query($sql);
include("err_msg.inc");
$nocountry=0;
while (list($country) = mysql_fetch_array($result)) {
	//echo "$country<br>";
	$countries[$nocountry] = $country;
	$nocountry++;
}
*/

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
