<html>

<head>
<title>Timesheet upadte message</title>
</head>
<body bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG" leftmargin="20">

<?php

########################################
##		Access control
########################################
include('str_decode_parse.inc');
if ($priv	==	'00') {
	include("connet_root_once.inc");
} else {
	exit;
}
include("ts_sum_ghr_rla_code_dbl_count.inc");

exit;
#############
#postgresql
//host=localhost port=5432 
echo " Before<br>";
$cstr = "user=root dbname=test";
$con = pg_Connect($cstr);
echo "$cstr: $con<br>";
echo " after<br>";


	
############
	include("find_admin_ip.inc");
	//mail("$adminname@rla.com.au","test","test","From:$adminname@rla.com.au\nCC: mmao@rokset.com.au cmao@rokset.com.au");
/*
	$cmdstr = "rm -f /usr/local/apache/htdocs/report/zipfile/*";
	exec($cmdstr);
	echo "$cmdstr<br>";
	exit;
//*/
	//$asql = "SELECT artist FROM artists WHERE artist='$art'";
	$sql = "SELECT * FROM rlafinance.chargingcode code_id='0';";
	$result = mysql_query($sql);
	echo mysql_affected_rows()."mysql_affected_rows<br>";
	#echo mysql_num_rows($result)."mysql_num_rows<br>";
	include("rla_ghr_code_comp.inc");

	$winos = "Mozilla/4.0 (compatible; MSIE 5.0; Windows 98; DigExt)";
	$winos = "Mozilla/4.0 (compatible; MSIE 5.5; Windows 98)";
	if ($winos) {
		$tmp = explode(";", $winos);
		//echo "$winos<br>";
		$explore = trim($tmp[1]);
		$winos= trim($tmp[2]);
		if (trim($tmp[3])) {
			$winos = $winos."; ".trim($tmp[3]);
		}
		$winos = ereg_replace(")","",$winos);
		echo "$winos<br>$explore<br>";
	}
exit;
	include("find_admin_ip.inc");
	$email_name = "$adminname";
	$sql = "INSERT INTO logging.sqlerrlog VALUES('$id', '$ename', '$file', 
		'$line', '$sql', '$err', '$date');";
	$filelog = __FILE__;
	$linelog = __LINE__;
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	//list($id, $ename, $file, $line, $sql, $err, $date) = sqlerrlog 

//echo getenv('HTTP_HOST');
$fromdate="2001-01-01";
$todate="2001-03-01";

			$rlaohdtil = "RLA-OHD-Time_in_Lieu";
			$sqlTIL = "SELECT t1.yyyymmdd as ymd, t1.email_name as ename, t2.minutes as minutes "
			."FROM timesheet.entry_no as t1, timesheet.timedata as t2 "
			."WHERE t1.yyyymmdd>='$fromdate' and t1.yyyymmdd<'$todate' "
			."and t1.entry_no=t2.entry_no and t2.brief_code='$rlaohdtil' "
			."ORDER BY ename, ymd;";
			$rlaohdtil = ereg_replace("_", " ", $rlaohdtil);
	//echo "$sqlTIL<br>";
	$filelog = __FILE__;
	$linelog = __LINE__;
	$result = mysql_query($sqlTIL);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	//echo "$no <br>";
	echo "<table border=1><tr><th>Name</tr><th>Minutes</th><th>Date</th></tr>";
	while (list($ymd, $ename, $minutes) = mysql_fetch_array($result)) 
	{
		echo "<tr><td>$ename</td><td>$minutes</td><td>$ymd</td></tr>";
	}
	
	echo "</table>";
	exit;
srand((double)microtime()*1000000);
$i = rand(1,59);
if ($i<10) {
	$i="00$i";
} else {
	$i="0$i";
}
		srand((double)microtime()*1000000);
		$i = rand(1,59);
		echo "<table border=1>";
		for ($i=1; $i<60; $i++) {
			if ($i<10) {
				$j="00$i";
			} else {
			$j="0$i";
			}
			$SERVERNAME = getenv('SERVER_NAME');
			$img = "/images/rlapeople/Pface$j.jpg";
		echo "<tr><td>http://$SERVERNAME$img</td></tr>";
		echo "<tr><td><img align=\"left\" src=\"$img\"></td></tr>";
	}
	echo "</table>";
//height=\"214\" width=\"318\"
exit;
$qry	=	"?".base64_encode('priv='.$priv);
echo "<h1 align=center>Test Program</h1>";
echo "<a id=top><p align=center><a href=\"".$PHP_SELF."$qry\">[Refresh]</a><hr>";
	
	echo "<h2>Directory Shortcut</h2>";
	echo "<a href=\"http://$SERVER_NAME/iptraflog/report\">http://$SERVER_NAME/iptraflog/report</a><br>";
	echo "<a href=\"/\">/</a><br>";
	echo "<a href=\"./\">./</a><br>";
	echo "<a href=\"../\">../</a><br>";
?>
<hr><a href=#top>Back to top</a><br>
</body>
