<html>
<?php
include("phpdir.inc");
$border=0;
	echo "<p><center><font size=4><b>OFFICIAL ORDER</b></font></center>";
	$w = 350;
	echo "<table border=$border>";
	echo "<tr><td><img height=\"73\" src=\"http://";
	echo getenv("server_name")."/$phpdir/rla_tr_small.gif\" width=\"100\" border=\"0\"></td>";
	echo "<td><font size=2>Laboratory: Tel-8352 1322 Fax-8352 1359
		<br>ABN 77 007 587 813</font>
		<br><br><font size=2>Date: ".date("M d, Y").
		"</font></td><tr>";
	echo "<tr><th colspan=2><font size=5>Research Laboratories of Australia Pty. Ltd.</font></th></tr>";
	echo "</table>";
	$j=85;
	for ($i=0; $i<$j; $i++) {
		echo "=";
	}
	//echo "<table border=$border><tr><td width=$w><font size=3>7 VALETTA RD, KIDMAN PARK S.A. 5025</font></td>";
	//echo "<td align=right width=$w><font size=3></td><tr></table>";
	echo "<br><font size=3>
		<b>DELIVERY ADDRESS: 7 Valetta Road, Kidman Park S.A. 5025</b></font><br>";
	for ($i=0; $i<$j; $i++) {
		//echo ".";
	}

	$action = "process";
	$printorder = "y";
	include("rla_fin_display_order.inc");

	$w2 = "45%";
	$w3 = "55%";
	$w2 = "300";
	$w3 = "400";
	$wspan = $w2+$w3;
	//echo "$w2+$w3";
	echo "<table border=$border>";
	echo "<tr><td width=$w2><b>RLA Internal Order NO: $order_id</b></td>
		<td width=$w3><font size=3>Research Laboratories of Australia Pty. Ltd.</td><tr>";
	echo "<tr><td><font size=2>Please include in your invoice:</font></td><td>&nbsp;</td></tr>";
	$sql = "SELECT title as t, first_name as f, middle_name as m, last_name as l
		FROM timesheet.employee WHERE email_name='$order_by'";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($t, $f, $m, $l) = mysql_fetch_array($result);
		
	echo "<tr><td width=$w2><b><i>Placed By: $f</i></td><td width=$w3><font size=3>per";
	if ($authemail) {
		$sql = "SELECT first_name, last_name 
        FROM timesheet.employee 
        WHERE email_name='$authemail'";
    	$result = mysql_query($sql);
    	include("err_msg.inc");
    	list($first_name, $last_name) = mysql_fetch_array($result);
    	$authemail = "<b>$first_name $last_name</b>";
		echo "      $authemail";
	} else {
		echo "___________________________________________";
	}
	echo "</td><tr>";
	echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>";
	//echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>";
	//echo "</table>";
	
	$fl = "<font size=2>";
	$fr = "</font>";
	/*
		echo "$paymethod, $card_cheque<br>";
	//*/
	$tmp = "(N.A.)";
	if ($paymethod == 1) {	//credit=1
		$sql = "SELECT holder, cardtype, cardno, expiredate, issuer 
			FROM rlafinance.creditcard WHERE card_id='$card_cheque'"; 
		$result = mysql_query($sql);
		include("err_msg.inc");
		$no = mysql_num_rows($result);
		list($holder, $cardtype, $cardno, $expiredate, $issuer) = mysql_fetch_array($result);
		$card_cheque = $tmp;	
		$cheque = "NO";
	} elseif ($paymethod == 2) {	//cheque=2
		$cheque = "YES";
		$cardtype = $tmp;
		$cardno = $tmp;
		$holder = $tmp;
		$expiredate = $tmp;
	} else {	//cash=3; account=4; EFT=5; 
		$cheque = "NO";
		$cardtype = $tmp;
		$cardno = $tmp;
		$holder = $tmp;
		$expiredate = $tmp;
		$card_cheque = $tmp;
	}
	
	echo "<tr><td width=$w2>$fl"."Cheque Required: <b>$cheque</b>$fr</td>
		<td width=$w3>$fl"."Cheque NO: <b>$card_cheque</b>$fr</td><tr>";
		
	echo "<tr><td width=$w2>$fl"."Card Type: <b>$cardtype</b>$fr</td>
		<td width=$w3>$fl 	Card Number: <b>$cardno</b>$fr</td><tr>";
	echo "<tr><td width=$w2>$fl"."Cardholders Name: <b>$holder</b>$fr</td>
		<td width=$w3><font size=3>$fl"."Expiry Date: <b>$expiredate</b>$fr</td><tr>";
	//account=4; cash=3; cheque=2; credit=1
	if ($paymethod == 1) {
		echo "<tr><td>&nbsp;</td><td>&nbsp;</td></tr>";
		echo "<tr><td width=$w2 colspan=2>$fl"."Signature:&nbsp;";
		echo "<b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$holder</b>";
		
		echo "$fr</td><tr>";
	} else {
		echo "<tr><td width=$wspan colspan=2>$fl"."Signature <b>$tmp</b>$fr</td><tr>";
	}
	echo "</table>";
	
	//Header: &w&bPage &p of &P
	//Footer: &u&b&d
	//L: 10mm; R: 5mm; T:10mm; B: 10mm
?>
</html>
