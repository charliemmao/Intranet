<html>
<?php
	$str	=	"'$start_date'";	
	# find day of the week for start_date
	$sql = "select DAYOFWEEK($str) as wkds;";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($wkds) = mysql_fetch_array($result);
	/*
		echo $start_date.", ".$end_date."<br>";
		echo "$sql <br>";
		echo $wkds."<br>";
	*/
	## find friday date
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
	
	$str	=	"'$end_date'";	
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
?>
</html>
