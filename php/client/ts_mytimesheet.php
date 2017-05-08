<html>

<head>
<title>Timesheet Count</title>
</head>
<body text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG" leftmargin="20">

<?php

########################################
##		Access control
########################################
include('str_decode_parse.inc');

include("userinfo.inc");
$qry	=	"?".base64_encode($userinfo);
echo '<h2 align=center>Timesheet Count</h2>';// onMouseOver=\"window.status='This is a test.'; return true;\"
$statuscontext = "Refresh";
include("self_status.inc");
include("find_domain.inc");	
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
		$moffset = 1;
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
		$year =	"";
		$month =	"";
		$day =	"";
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
	include("ts_findwks_list.inc");
	$sql = "SELECT entry_no as id, yyyymmdd as ymd FROM timesheet.entry_no WHERE yyyymmdd>='$start_date' and "
		."yyyymmdd<'$end_date' and email_name='$email_name' order by yyyymmdd;";
	include("ts_dispaly_mine.inc");
} elseif ($viewts) {
	$msg = "$tsname's timesheet for the week ending on $yyyymmdd";
	echo "<hr><h3>$msg. ($entry_no)</h3>";
	$feedback_message = "<b>Program searches $msg, please waiting...</b><br>";
	$width = 300;
	$height = 200;
	//include("feedback.inc");

	include("ts_display_one.inc");
	//include("close_feedback.inc");
	
} else {
	echo "<hr>";
	$year0 = date("Y");
	$month0 = date("m");
	if ($month0 == "12") {
		$year1 = $year0;
		$year2 = $year0 + 1;
		$month1 = $month0;
		$month2 = "01";
	} else {
		$year1 = $year0;
		$year2 = $year0;
		$month1 = $month0;
		$month2 = $month0 + 1;
	}
	if ((int)($month1) < 10) {
		$month1 = "0".(int)($month1);
	}
	if ((int)($month2) < 10) {
		$month2 = "0".(int)($month2);
	}
	$start_date = $year1."-".$month1."-01";
	$end_date = $year2."-".$month2."-01";
	include("ts_findwks_list.inc");
	$sql = "SELECT entry_no as id, yyyymmdd as ymd FROM timesheet.entry_no WHERE yyyymmdd>='$start_date' and "
		."yyyymmdd<'$end_date' and email_name='$email_name' order by yyyymmdd;";
	include("ts_dispaly_mine.inc");
}
$statuscontext = "Back To Top";
include("self_status.inc");
echo "<hr><a href=#top $status>Back to top</a><br>";
?>
</body>
