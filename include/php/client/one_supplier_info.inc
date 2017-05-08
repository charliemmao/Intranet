<html>
<?php
	echo "<table border=0>";
	$sql = "SELECT company, address, contactperson, telno, faxno, mobno, 
		email, www FROM rlafinance.supplierid WHERE supid='$supid'";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($company, $address, $contactperson, $telno, $faxno, $mobno, $email, $www) = 
		mysql_fetch_array($result);
	$f1= "<font size=2>";
	$f2= "</font>";
	echo "<table>";
	if ($company) {
		echo "<tr><th align=left>$f1"."Company".$f2."</th><td>$f1$company$f2</td></tr>";
	}
	if ($contactperson) {
		echo "<tr><th align=left>$f1"."Contact Person".$f2."</th><td>$f1$contactperson$f2</td></tr>";
	}
	if ($faxno) {
		echo "<tr><th align=left>$f1"."Fax".$f2."</th><td>$f1$faxno$f2</td></tr>";
	}
	if ($telno) {
		echo "<tr><th align=left>$f1"."Tel".$f2."</th><td>$f1$telno$f2</td></tr>";
	}
	if ($mobno) {
		echo "<tr><th align=left>$f1"."Mobile".$f2."</th><td>$f1$mobno$f2</td></tr>";
	}
	if ($address) {
		echo "<tr><th align=left>$f1"."Address".$f2."</th><td>$f1$address$f2</td></tr>";
	}
	if ($email) {
		echo "<tr><th align=left>$f1"."email".$f2."</th><td>$f1$email$f2</td></tr>";
	}
	if ($www) {
		echo "<tr><th align=left>$f1"."WWW".$f2."</th><td><a href=$www target=\"_blank\"><b>$f1$www$f2
		</b></a></td></tr>";
	}
	echo "</table>";
?>
</html>
