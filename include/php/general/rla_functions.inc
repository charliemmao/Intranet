<html>
<?php
$phpdir = "php";
$PHPconst = "PHP";
############################# Define Constant #############################
$sdq = '\"';
$thisfile = basename($PHP_SELF);

$m2d[1] = "01";
$m2d[2] = "02";
$m2d[3] = "03";
$m2d[4] = "04";
$m2d[5] = "05";
$m2d[6] = "06";
$m2d[7] = "07";
$m2d[8] = "08";
$m2d[9] = "09";
$m2d[10] = "10";
$m2d[11] = "11";
$m2d[12] = "12";

$yr = date("Y");
$febdays = 28;
if (number_format($yr/4.0,"0") * 4 == $yr) {
	$febdays = 29;
}
$daysinmth[2]=$febdays;
$daysinmth["02"]=$febdays;

$daysinmth[1]=31;
$daysinmth[3]=31;
$daysinmth[4]=30;
$daysinmth[5]=31;
$daysinmth[6]=30;
$daysinmth[7]=31;
$daysinmth[8]=31;
$daysinmth[9]=30;
$daysinmth[10]=31;
$daysinmth[11]=30;
$daysinmth[12]=31;
$daysinmth["01"]=31;
$daysinmth["03"]=31;
$daysinmth["04"]=30;
$daysinmth["05"]=31;
$daysinmth["06"]=30;
$daysinmth["07"]=31;
$daysinmth["08"]=31;
$daysinmth["09"]=30;
$daysinmth["10"]=31;
$daysinmth["11"]=30;
$daysinmth["12"]=31;

$wkdays[0] = "Sunday";
$wkdays[1] = "Monday";
$wkdays[2] = "Tuesday";
$wkdays[3] = "Wednesday";
$wkdays[4] = "Thursday";
$wkdays[5] = "Friday";
$wkdays[6] = "Saturday";

$mth["01"] = "January";
$mth["02"] = "February";
$mth["03"] = "March";
$mth["04"] = "April";
$mth["05"] = "May";
$mth["06"] = "June";
$mth["07"] = "July";
$mth["08"] = "August";
$mth["09"] = "September";
$mth["10"] = "October";
$mth["11"] = "November";
$mth["12"] = "December";
$mth[1] = "January";
$mth[2] = "February";
$mth[3] = "March";
$mth[4] = "April";
$mth[5] = "May";
$mth[6] = "June";
$mth[7] = "July";
$mth[8] = "August";
$mth[9] = "September";
$mth[10] = "October";
$mth[11] = "November";
$mth[12] = "December";

$mths["01"] = "Jan";
$mths["02"] = "Feb";
$mths["03"] = "Mar";
$mths["04"] = "Apr";
$mths["05"] = "May";
$mths["06"] = "Jun";
$mths["07"] = "Jul";
$mths["08"] = "Aug";
$mths["09"] = "Sep";
$mths["10"] = "Oct";
$mths["11"] = "Nov";
$mths["12"] = "Dec";

$mths["Jan"] = 1;
$mths["Feb"] = 2;
$mths["Mar"] = 3;
$mths["Apr"] = 4;
$mths["May"] = 5;
$mths["Jun"] = 6;
$mths["Jul"] = 7;
$mths["Aug"] = 8;
$mths["Sep"] = 9;
$mths["Oct"] = 10;
$mths["Nov"] = 11;
$mths["Dec"] = 12;

$mths[1] = "Jan";
$mths[2] = "Feb";
$mths[3] = "Mar";
$mths[4] = "Apr";
$mths[5] = "May";
$mths[6] = "Jun";
$mths[7] = "Jul";
$mths[8] = "Aug";
$mths[9] = "Sep";
$mths[10] = "Oct";
$mths[11] = "Nov";
$mths[12] = "Dec";

$qtrlist["01"] = "Mar";
$qtrlist["02"] = "Mar";
$qtrlist["03"] = "Mar";
$qtrlist["04"] = "Jun";
$qtrlist["05"] = "Jun";
$qtrlist["06"] = "Jun";
$qtrlist["07"] = "Sep";
$qtrlist["08"] = "Sep";
$qtrlist["09"] = "Sep";
$qtrlist["10"] = "Dec";
$qtrlist["11"] = "Dec";
$qtrlist["12"] = "Dec";
$qtrlist[1] = "Mar";
$qtrlist[2] = "Mar";
$qtrlist[3] = "Mar";
$qtrlist[4] = "Jun";
$qtrlist[5] = "Jun";
$qtrlist[6] = "Jun";
$qtrlist[7] = "Sep";
$qtrlist[8] = "Sep";
$qtrlist[9] = "Sep";
$qtrlist[10] = "Dec";
$qtrlist[11] = "Dec";
$qtrlist[12] = "Dec";

$qtrQN["01"] = "Q3";
$qtrQN["02"] = "Q3";
$qtrQN["03"] = "Q3";
$qtrQN["04"] = "Q4";
$qtrQN["05"] = "Q4";
$qtrQN["06"] = "Q4";
$qtrQN["07"] = "Q1";
$qtrQN["08"] = "Q1";
$qtrQN["09"] = "Q1";
$qtrQN["10"] = "Q2";
$qtrQN["11"] = "Q2";
$qtrQN["12"] = "Q2";
$qtrQN[1] = "Q3";
$qtrQN[2] = "Q3";
$qtrQN[3] = "Q3";
$qtrQN[4] = "Q4";
$qtrQN[5] = "Q4";
$qtrQN[6] = "Q4";
$qtrQN[7] = "Q1";
$qtrQN[8] = "Q1";
$qtrQN[9] = "Q1";
$qtrQN[10] = "Q2";
$qtrQN[11] = "Q2";
$qtrQN[12] = "Q2";

$qstm[1][0] = "March";
$qstm[1][1] = "01";
$qstm[1][2] = "03";
$qstm[1][3] = "31";
$qstm[2][0] = "June";
$qstm[2][1] = "04";
$qstm[2][2] = "06";
$qstm[2][3] = "30";
$qstm[3][0] = "September";
$qstm[3][1] = "07";
$qstm[3][2] = "09";
$qstm[3][3] = "30";
$qstm[4][0] = "December";
$qstm[4][1] = "10";
$qstm[4][2] = "12";
$qstm[4][3] = "31";

$hymstend[1][0] = "Jan-Jun";
$hymstend[1][1] = "01";
$hymstend[1][2] = "06";
$hymstend[1][3] = "30";
$hymstend[2][0] = "Jul-Dec";
$hymstend[2][1] = "07";
$hymstend[2][2] = "12";
$hymstend[2][3] = "31";

$hylist["01"] = "2nd half";
$hylist["02"] = "2nd half";
$hylist["03"] = "2nd half";
$hylist["04"] = "2nd half";
$hylist["05"] = "2nd half";
$hylist["06"] = "2nd half";

$hylist["07"] = "1st half";
$hylist["08"] = "1st half";
$hylist["09"] = "1st half";
$hylist["10"] = "1st half";
$hylist["11"] = "1st half";
$hylist["12"] = "1st half";

$qtrfour[0] = "Mar";
$qtrfour[1] = "June";
$qtrfour[2] = "Sept";
$qtrfour[3] = "Dec";
$qtrfour["01"] = "Mar";
$qtrfour["02"] = "Mar";
$qtrfour["03"] = "Mar";
$qtrfour["04"] = "June";
$qtrfour["05"] = "June";
$qtrfour["06"] = "June";
$qtrfour["07"] = "Sept";
$qtrfour["08"] = "Sept";
$qtrfour["09"] = "Sept";
$qtrfour["10"] = "Dec";
$qtrfour["11"] = "Dec";
$qtrfour["12"] = "Dec";

function removenewline($str) {
	$nl = chr(13).chr(10);
	return ereg_replace("$nl"," ",$str);
}

function rlabarcode($barcode) {
	$bclen = strlen($barcode);
	$bclen = 9 - $bclen;
	return rlazero($bclen).$barcode;
}

function rlaspace($no) {
	$str = "";
	for ($i=0; $i<$no; $i++) {
		$str = $str."&nbsp;";
	}
	return $str;
}

function rlazero($no) {
	$str = "";
	for ($i=0; $i<$no; $i++) {
		$str = $str."0";
	}
	return $str;
}

function underscores($str) {
	$str = ereg_replace("__", "&", $str);
	$str = ereg_replace("_", " ", $str);
	return $str;
}

function space_n($n) {
	$str = "";
	for ($i=0; $i<$n; $i++) {
		$str = $str."&nbsp;";
	}
	return $str;
}

function dayhrmin(&$totminutes, &$nodays, &$nohrs, &$nomin) {
	$nodays	=	(int)($totminutes / 456);
	$nohrs		=	(int)(($totminutes - $nodays*456)/60);
	$nomin		=	$totminutes - $nodays*456 - $nohrs*60;
}

function zerofill($no, $digits) {
	//echo $no."<br>";
	$nolen = $digits-strlen($no);
	return zero_n($nolen).$no;
}

function zero_n($n) {
	$zero = "";
	for ($i=0; $i<$n; $i++) {
		$zero = $zero."0";
	}
	//echo $zero."<br>";
	return $zero;
}

function rlagetyear($tsdate) {
	return substr($tsdate, 0, 4);
}

function rlagetmonth($tsdate) {
	return substr($tsdate, 5, 2);
}

function rlagetday($tsdate) {
	return substr($tsdate, 8, 2);
}

function pickprintablechr($tmp, $from, $to) {
	$tmp = trim($tmp);
	$ret = "";
	for ($pi=0; $pi<strlen($tmp); $pi++) {
		$oc=substr($tmp,$pi,1);
		$pn = ord($oc);
		if ($from <= $pn && $pn <= $to) {
			$ret=$ret.$oc;
		}
	}
	return $ret;
}

function reset_autoincrement($table) {
	include("connet_root_once.inc");
	$sqlreset = "ALTER TABLE $table AUTO_INCREMENT = 1";
	$result_reset = mysql_query($sqlreset);
	if ($result_reset) {
		return 0;
	} else {
		return 1;
	}
}

function formatfieldname($fld) {
	$tmp = ereg_replace("_", " ", $fld);
	return strtoupper($tmp);
}

function ordermailentry($order_id, $mfrom,$mto, $i) {
	include("connet_root_once.inc");

	$reln[0] = "I to Auth";
	$reln[1] = "S to I";
	$reln[2] = "Auth to I";
	$reln[3] = "Auth to PO";
	$reln[4] = "PO to I";
	$reln[5] = "Sec to I";
	$reln[6] = "Auth Q to I";
	$reln[7] = "Sec Q to I";

	$tstamp = date("Y-m-d H:i:s");
	$relation = $reln[$i];
	$sqlmailrcd = "INSERT INTO rlafinance.mailrcd 
        SET mailid='', order_id='$order_id', 
            mfrom='$mfrom', mto='$mto', 
            relation='$relation', tstamp='$tstamp';";
	$result_mrcd = mysql_query($sqlmailrcd); 
	//echo "$sqlmailrcd ";
	return 0;
}

$datepattren[0] = "([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})"; 		#yyyy-mm-dd	2001-12-13
$datepattren[1] = "([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{2,4})"; 	#dd/mm/yyyyy	13/12/2001
$datepattren[2] = "([0-9]{1,2})-([a-z]{3,10})-([0-9]{1,4})";		#13-Dec-2001
$datepattren[3] = "([a-z]{3,10}) ([0-9]{1,2}), ([0-9]{1,4})";		#Dec 13, 2001
$datepattren[4] = "([a-z]{3,10})-([0-9]{1,4})";						#Dec-2001

$dateformat[0] = "01-12-2";
$dateformat[1] = "2/12/01";
$dateformat[2] = "2-Dec-01";
$dateformat[3] = "Dec 2, 01";
$dateformat[4] = "Dec-01";

/*
	for ($j=0; $j<count($datepattren); $j++) {
		$pat = $datepattren[$j];
		echo "($j) $pat<br>";
	}
//*/

function convertyear($y){
	if ($y<100) {
		return $y+2000;
	} else {
		return $y;
	}
}

function convertmdno($md){
	if ($md<10) {
		return "0".$md;
	} else {
		return $md;
	}
}

function convertmdstr($md){
global $mths;
	for ($im=1; $im<13; $im++) {
		$pat = $mths[$im];
		if ( eregi( "$pat", "$md", $regs) ) {
			return convertmdno($im);
		}
	}
	exit;
}

function finddaysinFeb($mth, $year) {
global $daysinmth;
	if ($mth == 2) {
		if (number_format($year/4, 0)*4 == $year) {
			return 29;
		} else {
			return 28;
		}
	}
	return $daysinmth[$mth];
}

function formatmonth($month){
	if ($month <10) {
		return "0".$month;
	} else {
		return $month;
	}
}

function findmonthlist($dateS, $dateE) {
	global $monthlist, $mthno;
	$ys = substr($dateS, 0, 4);
	$ms = substr($dateS, 5, 2);
	$ye = substr($dateE, 0, 4);
	$me = substr($dateE, 5, 2);
	
	$mthno = 0;
	for ($i=$ys; $i<=$ye; $i++) {
		if ($i == $ys) {
			if ($ys ==$ye) {
				$m1 = $ms;
				$m2 = $me;
			} else {
				$m1 = $ms;
				$m2 = 12;
			}
		} elseif ($i == $ye) {
			$m1 = 1;
			$m2 = $me;
		} else {
			$m1 = 1;
			$m2 = 12;
		}
		for ($j=$m1; $j<=$m2; $j++) {
			$monthlist[$mthno] = date ("Y-m-d", mktime (0,0,0,$j,1,$i));
			$mthno++;
		}
	}
	//echo date ("Y-m-d", mktime (0,0,0,12,32,1997))."<br>";
	//echo "$dateS, $dateE<br>";
	//echo $mthno."<br>";
}
function calhours($tskdateS, $tskdateE, $hrsinmth) {
	global $podmonthlist, $podmthno;
	global $monthlist, $mthno;
	findmonthlist($tskdateS, $tskdateE);
	$rtn = 0;
	for ($i=0; $i<$podmthno; $i++) {
		for ($j=0; $j<$mthno; $j++) {
			if ($podmonthlist[$i] == $monthlist[$j]) {
				$rtn += $hrsinmth;
			}
		}
	}
	return $rtn;
}
function reportline1($hd) {
	echo "<hr><h2>$hd</h2>";
}

?>

<script language=javascript>
function backhistory(no) {
	history.go(no);
}
</script>

</html>
