<?php
$fntL = "<font color=#0000ff>";
$fntR = "</font>";

echo "<table><tr><th align=left valign=top>Monthly</th><td>";
echo "<table>";
for ($y = $yfm; $y <= $yto; $y++) {
	$febdays = 28;
	if (number_format($y/4.0,"0") * 4 == $y) {
		$febdays = 29;
	}
	$daysinmth[2]=$febdays;
	echo "<tr><th>$fntL$y$fntR</th>";
	for ($m=1; $m<=12; $m++) {
		$mm = $m2d[$m];
		$dd = $daysinmth[$m];
		$date10 = "$y-$mm-01";
		$date20 = "$y-$mm-$dd";
		$userstr	=	"?".base64_encode($userinfo."&$rpttype=y&date1=$date10&date2=$date20");
		echo "<th><a href=\"$php_file$userstr\">".$mths[$m]."</a></th>";
	}
	echo "</tr>";
}
echo "</table></td></tr>";
	
echo "<tr><th align=left valign=top>Quarterly</th>";
echo "<td><table>";
for ($y = $yfm; $y <= $yto; $y++) {
	echo "<tr><th>$fntL$y$fntR</th>";
	for ($q=1; $q<=4; $q++) {
		$m1 = $qstm[$q][1];
		$m2 = $qstm[$q][2];
		$dd = $qstm[$q][3];
		$date10 = "$y-$m1-01";
		$date20 = "$y-$m2-$dd";
		$userstr	=	"?".base64_encode($userinfo."&$rpttype=y&date1=$date10&date2=$date20");
		echo "<th><a href=\"$php_file$userstr\">".$qstm[$q][0]."</a></th>";
	}
	echo "</tr>";
}
echo "</table></td></tr>";
	
echo "<tr><th align=left valign=top>Half Yearly</th>";
echo "<td><table><tr>";
for ($y = $yfm; $y <= $yto; $y++) {
	echo "<th>$fntL$y$fntR</th>";
	for ($q=1; $q<=2; $q++) {
		$m1 = $hymstend[$q][1];
		$m2 = $hymstend[$q][2];
		$dd = $hymstend[$q][3];
		$date10 = "$y-$m1-01";
		$date20 = "$y-$m2-$dd";
		$userstr	=	"?".base64_encode($userinfo."&$rpttype=y&date1=$date10&date2=$date20");
		echo "<th><a href=\"$php_file$userstr\">".$hymstend[$q][0]."</a></th>";
	}
}
echo "</tr></table></td></tr>";
	
echo "<tr><th align=left valign=top>Yearly</th>";
echo "<td><table><tr>";
for ($y = $yfm-1; $y <= $yto+1; $y++) {
	$date10 = "$y-07-01";
	$y2 = $y+1;
	$date20 = "$y2-06-30";
	$userstr	=	"?".base64_encode($userinfo."&$rpttype=y&date1=$date10&date2=$date20");
	echo "<th><a href=\"$php_file$userstr\">$y/$y2</a></th>";
}
echo "</tr></table></td></tr>";
echo "</table><p>";
?>