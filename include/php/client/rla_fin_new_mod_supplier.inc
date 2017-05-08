<html>
<?php

/*
	$sql = "SELECT supid id, company, telno, faxno	FROM rlafinance.supplierid 
		WHERE faxno='8234 0390';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$noentry = mysql_num_rows($result);
	while (list($id, $company, $telno, $faxno) = mysql_fetch_array($result)) {
		echo "$id, $company, $telno, $faxno<br>";
		$lastsupid = $supid;
	}
	if (1 < $noentry) {
		$sql = "DELETE FROM rlafinance.supplierid WHERE supid='$id'";
		echo "$sql<br>";
		$result = mysql_query($sql);
		include("err_msg.inc");
	}
//*/

if (!$supid) {
	echo "<hr>";
}


echo "<form name=supplierslistman>";
echo '<input type="hidden" name="frm_str" value= "'.$userstr.'">';
if ($supid) {
	echo '<input type="hidden" name="supid" value= "'.$supid.'">';
	echo "<hr><h2>Modify Following Supplier</h2>";
} else {
	echo "<h2>Add New Supplier</h2>";
}
/* table: supplier. columns: supid, company, address, contactperson, telno, faxno, mobno, email, www, */
	
echo "<table border=1>";
echo "<tr><th>Item</th><th>Value</th></tr>";

if ($suppliersmanipulation) {
	$www="";
}
$basic="<font color=#ff0000>&nbsp;*</font>";

$sql = "SELECT description as destmp 
        FROM logging.sysmastertable 
        WHERE item='chcomp'";
$result = mysql_query($sql);
include("err_msg.inc");
list($destmp) = mysql_fetch_array($result);

if ($destmp == ereg_replace("$email_name", "" ,$destmp)) {
	$beabletochangesupplier = "";
} else {
	$beabletochangesupplier = 1;
}

if ($supid) {
	echo "<tr><th align=left>Supplier$basic</th><td>";
	if ($beabletochangesupplier) {
		echo "<input type=text name=company value=\"$company\" size=50>";
	} else {
		echo "$company<input type=hidden name=company value=\"$company\" size=50>";
	}
	echo "</td></tr>";
} else {
	echo "<tr><th align=left>Supplier$basic</th><td>
	<input type=text name=company value=\"$company\" size=50></td></tr>";
}
echo "<tr><th align=left>Phone$basic</th><td>
	<input type=text name=telno value=\"$telno\" size=50></td></tr>";
echo "<tr><th align=left>Fax$basic</th><td>
	<input type=text name=faxno value=\"$faxno\" size=50></td></tr>";
echo "<tr><th align=left>Mobile</th><td>
	<input type=text name=mobno value=\"$mobno\" size=50></td></tr>";
echo "<tr><th align=left>Email</th><td>
	<input type=text name=email value=\"$email\" size=50></td></tr>";
echo "<tr><th align=left>Web Page</th><td>
	<input type=text name=www value=\"$www\" size=50></td></tr>";
echo "<tr><th align=left>Person to Contact</th><td>
	<input type=text name=contactperson value=\"$contactperson\" size=50></td></tr>";
echo "<tr><th align=left>Address</th><td>
	<textarea name=address cols=50 rows=4>$address</textarea></td></tr>";
echo "<tr><td colspan=2 align=middle>$basic indicates the field can't be empty.</td></tr>";

echo "<tr><td colspan=2 align=middle>";
echo "<input type=submit name=suppliersmanipulation ";
echo "onclick=\"return chksupplier();\" ";
if ($supid) {
	echo "value=\"Modify Supplier\">";
} else {
	echo "value=\"Add New Supplier\">";
}
echo "</td></tr>";
if ($www) {
	echo "<tr><th colspan=2 align=center><a href=\"$www\" target=\"_blank\">Or Visit $company's Webpage.</a></th></tr>";
}
echo "</table>";
echo "</form>";
?>
</html>