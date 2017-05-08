<html>
<?php
	include('connet_root_once.inc');	//entry_no
	mysql_select_db("timesheet");
	$sql	=	"SELECT entry_no FROM entry_no ";
	if ($no_people	 = 1) {
		if ($fromdate != $todate) {
			$sql	=	$sql."where email_name='$email_name' and (yyyymmdd>='$fromdate' and yyyymmdd<='$todate') ORDER by yyyymmdd DESC;";
		} else {
			$sql	=	$sql."where email_name='$email_name' and yyyymmdd='$fromdate' ORDER by yyyymmdd DESC;";
		}
	} else {
		if ($fromdate != $todate) {
			$sql	=	$sql."where yyyymmdd>='$fromdate' and yyyymmdd<='$todate' ORDER by yyyymmdd DESC;";
		} else {
			$sql	=	$sql."where yyyymmdd='$fromdate' ORDER by yyyymmdd DESC;";
		}
	}
	//echo $sql.'<br>';
	$result	=	mysql_query($sql);
	$totminutes	=	0;
	//echo mysql_num_rows($result).'<br>';
	if (mysql_num_rows($result)) {
		$out	=	$out."<p><table border=1>";
		$out	=	$out."<tr><th>Date</th><th>minutes</th></tr>";
		while (list($entry_no)	=	mysql_fetch_array($result)) {
			$sql	=	"SELECT startday, minutes FROM $tabletosearch ";
			$sql	=	$sql."where email_name='$email_name' and brief_code='$brief_code' and entry_no='$entry_no';";
			//echo $sql.'<br>';
			$result1	=	mysql_query($sql);
			//echo mysql_num_rows($result1).'<br>';
			if (mysql_num_rows($result)) {
				while (list($startday, $minutes)	=	mysql_fetch_array($result1)) {
					$totminutes =	$totminutes + $minutes;
					$out	=	$out."<tr><td>$startday</td><td align=center>$minutes</td></tr>";
				}
			}
		}
		$out	=	$out."</table><br>";
		if ($totminutes !=0 ) {
			$out	=	$out."<b>Accumulative time for project \"<font color=#0000ff>$brief_code</font>\" ";
			$nodays	=	(int)($totminutes / 456);
			$nohrs		=	(int)(($totminutes - $nodays*456)/60);
			$nomin		=	$totminutes - $nodays*456 - $nohrs*60;
			$out	=	$out." for the period from $fromdate to $todate are $totminutes minutes. "
			."Or $nodays days $nohrs hours and $nomin minutes.</b><br>";
			echo $out	;
		} else {
			echo "<h2>No record has been found.</h2>";
		}
	} else {
		echo "<h2>No record has been found.</h2>";
	}
	mysql_close();
?>
</html>
