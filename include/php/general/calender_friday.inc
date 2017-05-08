<html>
<?php

	include('connet_root_once.inc');
	$str	=	"'".date('Y-m-d')."'";	
	# find day of the week for today
	$qry = "select DAYOFWEEK($str);";
	include('find_one_val.inc');
# find out days to Friday date of last week if today is not friday
    if ($out < 6){
    	$doffset	=	-1 -$out;
    	$temp	= $doffset.' day(s) to Friday.<br>';
    } elseif ($out == 6){
    	$doffset	=	0;
    	$temp	= 'Today is Friday.<br>';
    } elseif ($out == 7){
    	$doffset	=	$out -7;
    	$temp	= 'Today is Saturday.<br>';
	}
    //echo $temp.'<br>';
    
# find out day series no for Today
	$qry = "select TO_DAYS($str);";
	include('find_one_val.inc');
	//echo "Today's day series no is ".$out.'<br>';

# find out Friday date
   	$j	=	$out + $doffset;
	$qry = "select FROM_DAYS($j);";
	include('find_one_val.inc');
	$year =	substr($out,0,4);
	$month =	(int)substr($out,5,2);
	$day =	(int)substr($out,8,2);
	//echo "Friday ending on ".$out.'<br>';	
	//echo "Friday ending on ".$day .$month .$year .'<br>';
	
# find out day series no for Friday 
	$str	=	"'".$out."'";	
	$qry = "select TO_DAYS($str);";
	include('find_one_val.inc');
	//echo "Friday's series no is ".$out.'<br>';	
	$thisFriday	=	$out;
	
	if ($listlasrmonthtimesheet == "Y") {
		$daystart = mktime(0,0,0,date("m")-1,1,date("Y"));
	} else {
		$daystart = mktime(0,0,0,date("m"),1,date("Y"));
	}
	$w = date("w",$daystart);
	$daystart = date("Y-m-d", $daystart);
	//echo "$daystart; $w<br>";
	$qry = "select TO_DAYS('$daystart');";
	include('find_one_val.inc');
	$daystart = $out;
	if ($w < 5) {
		$daystart = $daystart + (5 - $w);
	} elseif ($w > 5) {
		$daystart = $daystart + (12 - $w);
	}
	if ($priv == "00") {
		include("connet_root_once.inc");
		$sql = "SELECT description as weeksback FROM logging.sysmastertable WHERE item='weeksback';";
		$result = mysql_query($sql);
		include("err_msg.inc");
		list($weeksback) = mysql_fetch_array($result);
	}
	if ($weeksback> 0) {
			$daystart = $daystart - $weeksback*7;
			$dayend = $daystart + (26+$weeksback)*7;
	} else {
		$dayend = $daystart + 26*7;
	}
	
	if ($yyyymmdd != '') {
		$str	=	"'".$yyyymmdd."'";	
		$qry = "select TO_DAYS($str);";
		include('find_one_val.inc');
		$thisFriday	=	$out;
		//echo '$thisFriday '.$thisFriday.'<br>';
	}
	
# make Friday list
	if ($lablestr ==	"") {
		$lablestr	=	"Friday is on";
	}
	echo '<td colspan="2"><b>'.$lablestr.'</b></td>';
	//friday list
	//if ($priv == "00") {
		$daystart -= $backweek;
	//}

	echo '<td colspan="2"><select name="calfriday" size="1">';
		for ($j=$daystart; $j<$dayend; $j=$j+7) {
			$qry = "select FROM_DAYS($j);";
			include('find_one_val.inc');
			if ($j==$thisFriday) {
				echo '<OPTION SELECTED>'.$out;
			} else {
				echo '<OPTION>'.$out;
			}
		}
		echo '</SELECT>&nbsp;';
	echo '</td>';
    	//echo "str = $iwantback<br> days=$backweek<br>";
?>
</html>
