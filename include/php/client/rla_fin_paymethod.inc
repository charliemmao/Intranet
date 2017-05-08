<html>
<?php
		echo "<tr><th align=left colspan=$cspan>Payment Method</th>";
		echo "<td colspan=$cspan><select name=\"paymethod\">";
		//$sql = "SELECT method_id, description FROM rlafinance.paymethod order by method_id";
		$sql = "SELECT method_id, description FROM rlafinance.paymethod order by description";
		$result = mysql_query($sql);
		include("err_msg.inc");
		$no = mysql_num_rows($result);
		while (list($method_id, $description) = mysql_fetch_array($result)) {
			$description = ucwords($description);
			if ($paymethod == $method_id) {
				echo "<option value=$method_id selected>$description";
			} else {
				echo "<option value=$method_id>$description";
			}
		}
		echo "</option></select>";	//</td>
		if ($newline) {
			echo "</td></tr><tr><th align=right>$fnts"."Select Card</font></th><td>";	
		} else {
			echo "&nbsp;<b>Select Card</b>&nbsp;";
		}
		echo "<select name=\"card_id\"><option>---select one---";
		$dt = date("Y-m-d");
		$sql = "SELECT card_id as ccd, holder, cardtype, cardno, expiredate, issuer 
			FROM rlafinance.creditcard 
			WHERE expiredate>'$dt' ORDER BY expiredate DESC";
		$result = mysql_query($sql);
		include("err_msg.inc");
		while (list($ccd, $holder, $cardtype, $cardno, $expiredate, $issuer) 
			= mysql_fetch_array($result)) {
			if ($ccd == $card_cheque) {
				echo "<option value=$ccd selected>$issuer-$cardtype -($holder)";
			} else {
				echo "<option value=$ccd>$issuer-$cardtype -($holder)";
			}
		}
		echo "</option></select>";
		echo "</td></tr>";
	
	?>
</html>