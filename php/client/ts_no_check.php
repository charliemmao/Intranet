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
if ($priv	==	'00' || $priv	==	'10' || $priv	==	'50') {
	//echo "Email=$email_name. priv=$priv";
} else {
	//exit;
}
	
include("userinfo.inc");
include("rla_functions.inc");
$setpriv = "00";
$qry	=	"?".base64_encode($userinfo);
include("find_domain.inc");	
echo '<h2 align=center><b>Timesheet Count</b></h2>';// onMouseOver=\"window.status='This is a test.'; return true;\"
echo "<a id=top><p align=center><a href=\"".$PHP_SELF."$qry\">[Refresh]</a><hr>";
include('connet_root_once.inc');
/*
## staff list

	$todaystr=date("Y-m-d");
	$where	=	"date_unemployed='0000-00-00' or date_unemployed>='$start_date'";
	//echo "$where<br>";
	
	$sql = "SELECT email_name as ename, first_name as fname, last_name as lname 
		FROM timesheet.employee 
		where $where ORDER BY fname;";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$nostaff = 0;
	while (list($ename, $fname, $lname) = mysql_fetch_array($result)) {
		$name_list[$nostaff][0] = $ename;
		$name_list[$ename][1] = ucwords($fname);
		$name_list[$ename][2] = ucwords($lname);
		//echo "$ename $fname $lname.<br>";
		$nostaff ++;
	}
//*/
echo "<table border=0><tr><td>";
########################################
##		date range
########################################
echo '<form method=POST action='.$PHP_SELF.'>';
include("userstr.inc"); //$userstr
echo "<tr><td colspan=2><b>Select Period to Search</b></td></tr>";
echo "<tr><td>&nbsp;</td></tr>";
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
	echo "</td></tr>";
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
	echo "</td><th><input type=\"submit\" value=\"Search Timesheet\" name=\"searchtimesheet\"></th></tr>";
echo "</form></table>";
echo "<p>";
//*
if ($sendwarningeamil) {
	$emaillist = ereg_replace(";", ",", $emaillist);
	include("find_admin_ip.inc");
	//$to 		= "$adminname@rla.com.au";
	$to	 		= $emaillist;
	$header	=	"From: $email_name@rla.com.au\nReply-To: $email_name@rla.com.au\n";
	//mail_($to, $subject, $message, $header);
	
	//$emaillist="cmm@rla.com.au, cmao@rla.com.au, fail@rla.com.au";
	$from = "$email_name\@rla.com.au";			
	$to = trim($emaillist);
	$to = ereg_replace(",", "", $to );
	$to = ereg_replace("@", "\\@", $to);
	$cc = "";
	$msg = $message;
/*
	echo "From :$from<br>";
	echo "To :$to()<br><br>";
	echo "Subject :$subject<br>";
	echo "Msg :$msg";
//*/
	system("./_multirecmail.pl \"$from\" \"$to\" \"$cc\" \"$subject\" \"$msg\"",$var);
	//chmod 755 /usr/local/apache/php_script/_multirecmail.pl
	//exit;

	$emaillist = ereg_replace(",", ";", $emaillist);
	$message = ereg_replace("\n", "<br>", $message);
	echo "<hr><h2>The following message has been sent successfully.</h2><table border=1>";
	echo "<tr><th align=left valign=top>To</th><td>$emaillist</td></tr>";
	echo "<tr><th align=left valign=top>Subject</th><td>$subject</td></tr>";
	echo "<tr><th align=left valign=top>Message</th><td>$message</td></tr>";
	echo "</table>";
	echo "<hr><a href=#top>Back to top</a><br>";
	exit;
}
//*/
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
	
## staff list
	$sql = "SELECT email_name as ename, first_name as fname, last_name as lname 
		FROM timesheet.employee 
		where (date_unemployed=\"0000-00-00\" or date_unemployed>='$start_date') 
		and email_name!=\"webmaster\" ORDER BY fname;";
		//where date_unemployed=\"0000-00-00\" 

	$result = mysql_query($sql);
	include("err_msg.inc");
	$nostaff = 0;
	while (list($ename, $fname, $lname) = mysql_fetch_array($result)) {
		$name_list[$nostaff][0] = $ename;
		$name_list[$ename][1] = ucwords($fname);
		$name_list[$ename][2] = ucwords($lname);
		//echo "$ename $fname $lname.<br>";
		$nostaff ++;
	}

	$setpriv = "00";
	if ($priv == $setpriv) {
		$sql = "SELECT t1.entry_no as id, t1.email_name as ename, t1.yyyymmdd as ymd, sum(t2.minutes) as minutes "
		."FROM timesheet.entry_no as t1, timesheet.timedata as t2 "
		."WHERE t1.yyyymmdd>='$start_date' and "
		."t1.yyyymmdd<'$end_date' and t1.entry_no=t2.entry_no "
		."GROUP BY id "
		."ORDER BY ename, ymd;";
	} else {
		$sql = "SELECT entry_no as id, email_name as ename, yyyymmdd as ymd FROM timesheet.entry_no WHERE yyyymmdd>='$start_date' and "
		."yyyymmdd<'$end_date' order by email_name, yyyymmdd;";
	}

	include("ts_dispaly_summary.inc");
} elseif ($viewts) {
	$msg = "$tsname's timesheet for the week ending on $yyyymmdd";
	echo "<hr><h3>$msg. ($entry_no)</h3>";
	$feedback_message = "<b>Program searches $msg, please waiting...</b><br>";
	$width = 300;
	$height = 200;

	include("ts_display_one.inc");
	
} else {//fresh timesheet or click from left frame
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
	$listwarning = 1;
	if ($start_date == "") {
		$start_date = $year1."-".$month1."-01";
		$end_date = $year2."-".$month2."-01";
		$listwarning = 0;
	}
	include("ts_findwks_list.inc");
		
## staff list
	$sql = "SELECT email_name as ename, first_name as fname, last_name as lname 
		FROM timesheet.employee 
		where (date_unemployed=\"0000-00-00\" or date_unemployed>='$start_date') 
		and email_name!=\"webmaster\" ORDER BY fname;";
		//where date_unemployed=\"0000-00-00\" 

	$result = mysql_query($sql);
	include("err_msg.inc");
	$nostaff = 0;
	while (list($ename, $fname, $lname) = mysql_fetch_array($result)) {
		$name_list[$nostaff][0] = $ename;
		$name_list[$ename][1] = ucwords($fname);
		$name_list[$ename][2] = ucwords($lname);
		//echo "$ename $fname $lname.<br>";
		$nostaff ++;
	}

	if ($priv == "00") {
	$sql = "SELECT t1.entry_no as id, t1.email_name as ename, t1.yyyymmdd as ymd, sum(t2.minutes) as minutes "
		."FROM timesheet.entry_no as t1, timesheet.timedata as t2 "
		."WHERE t1.yyyymmdd>='$start_date' and "
		."t1.yyyymmdd<'$end_date' and t1.entry_no=t2.entry_no "
		."GROUP BY id "
		."ORDER BY ename, ymd;";
		//echo $sql."<br>";
	} else {
	$sql = "SELECT entry_no as id, email_name as ename, yyyymmdd as ymd FROM timesheet.entry_no WHERE yyyymmdd>='$start_date' and "
		."yyyymmdd<'$end_date' order by email_name, yyyymmdd;";
	}
	include("ts_dispaly_summary.inc");
}
?>
<hr><a href=#top>Back to top</a><br>
