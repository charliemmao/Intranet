<html>
<?php
		echo "<form name=formnewcard>";
		echo '<input type="hidden" name="frm_str" value= "'.$userstr.'">';
		echo "<table border=1>";
		if ($cardmodify) {
			echo '<input type="hidden" name="card_id" value= "'.$card_id.'">';
		}
		echo "<tr><th align=left>Card Holder</th><td><select name=holder>";
		
		
		$sql = "SELECT details FROM rlafinance.control WHERE controllist='cardholder';";
		$result = mysql_query($sql);
		include("err_msg.inc");
		list($details) = mysql_fetch_array($result);
		$tmp =explode(";",$details);
		$j=count($tmp);
		for ($i=0; $i<$j; $i++) {
			$str = explode("@",$tmp[$i]);
			$cholder[$i][0]=$str[0];
			$cholder[$i][1]=$str[1];
		}
		for ($i=0; $i<$j; $i++) {
			if ($holder == $cholder[$i][0]) {
				echo "<option selected>".$cholder[$i][1];
			} else {
				echo "<option>".$cholder[$i][1];
			}
		}
		echo "</option></select></td></tr>";
		echo "<tr><th align=left>Card No</th>";
		echo "<td><input type=text name=cardno value=\"$cardno\" size=20></td></tr>";

		echo "<tr><th align=left>Card Type</th>";
		echo "<td><select name=cardtype>";
		
		$sql = "SELECT details FROM rlafinance.control WHERE controllist='cardtype';";
		$result = mysql_query($sql);
		include("err_msg.inc");
		list($details) = mysql_fetch_array($result);
		$clist =explode(";",$details);
		$j=count($clist);
		for ($i=0; $i<$j; $i++) {
			if ($cardtype == $clist[$i]) {
				echo "<option selected>".$clist[$i];
			} else {
				echo "<option>".$clist[$i];
			}
		}		
		echo "</option></select></td></tr>";
		
		if ($expiredate) {
			$year = rlagetyear($expiredate);
			$month = rlagetmonth($expiredate);
		} else {
			$year = date("Y");
			$month = date("m");
		}
		echo "<tr><th align=left>Expired Date</th>";
		echo "<td>"; //$month-$year
		echo "<select name=month>";
		for ($i=1; $i<13; $i++) {
			if ($i<10) {
				$j="0".$i;
			} else {
				$j=$i;
			}
			if ($j==$month) {
				echo "<option selected>$j";
			} else {
				echo "<option>$j";
			}
		}
		echo "</option></select>&nbsp;&nbsp;";
		echo "<select name=year>";
		$j = date("Y");
		for ($i=$j; $i<$j+10; $i++) {
			if ($i == $year) {
				echo "<option selected>$i";
			} else {
				echo "<option>$i";
			}
		}
		echo "</option></select></td></tr>";
		
		echo "<tr><th align=left>Card Issuer</th>";
		echo "<td><input type=text name=issuer value=\"$issuer\" size=20></td></tr>";
		if ($cardmodify) {
			$val = "Modify This Card";
		} else {
			$val = "Add New Card";
		}
		echo "<tr><td colspan=2 align=center><input type=submit name=createnewcard value=\"$val\">
			</td></tr>";
		//holder, cardtype, cardno, expiredate, issuer
		echo "</table>";
		echo "</form>";
?>
</html>