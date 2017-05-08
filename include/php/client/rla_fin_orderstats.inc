<html>
<?php
	$sql00 = "SELECT details FROM rlafinance.control WHERE controllist='status'";
	$result00 = mysql_query($sql00);
	include("err_msg.inc");
	list($details) = mysql_fetch_array($result00);
	//echo "$details<br>";
	$stsstr = explode(";", $details);
	for ($si = 0; $si <= count($stsstr); $si++) {
		//echo $stsstr[$si]."<br>";
		$tmpexp = explode("=", $stsstr[$si]);
		$str1 = $tmpexp[0];
       $status["$str1"] =  $tmpexp[1];
	}
?>
</html>
