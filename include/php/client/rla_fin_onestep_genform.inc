<html>
<?php
	//form orderform
	$show = "hidden"; //hidden text
	echo "<form name=orderform>";
	echo "<input type=\"$show\" name=\"frm_str\" value=\"$userstr\">";
	echo "<input type=\"$show\" name=\"supid\" value=\"$supid\">";
	echo "<input type=\"$show\" name=\"noitem\" value=\"$noitem\">";
	if ($createmodorderform) {
		echo "<input type=\"$show\" name=\"modifyclosedorder\" value=\"y\">";
		echo "<input type=\"$show\" name=\"order_id\" value=\"$order_id\">";
	}
	$gstwarning = "<font color=#ff0000>&nbsp;&nbsp;Including GST.</font>";
	echo "<table border=1>";
	echo "<tr class=tr1><th align=left colspan=2>Order Details</th></tr>";
	for ($i=0; $i<$noitem; $i++) {
		$goods_id = $orddet[$i][0];
		//$supid = $orddet[$i][1]; 
		$unit = $orddet[$i][2];
		$unit_price  = $orddet[$i][3];
		$code_id = $orddet[$i][6];
		$rlaprojid = $orddet[$i][7];
		//echo "code_id = $code_id; rlaprojid = $rlaprojid<br>";
		$k= $i+1;
		echo "<tr><th>$k</th>";
		echo "<td><table>";		
		
		echo "<tr><th align=left class=\"td1\">Product</th><td><select name=\"goods$i\"><option value=\"\">---select one---";
		for ($j=0; $j<$nogs; $j++) {
			$id = $glist[$j][0];
			$tmp = $glist[$j][1];
			if ($goods_id == $id) {
				echo "<option value=\"$id\" selected>$tmp";
			} else {
				echo "<option value=\"$id\">$tmp";
			}
		}
		echo "</option></select></td></tr>";
		echo "<tr><th align=left class=\"td1\">Quantity</th><td>
			<input type=\"text\" name=\"quantity$i\" value=\"$unit\" size=6></td></tr>";
		echo "<tr><th align=left class=\"td1\">Unit Price</th><td>
			<input type=\"text\" name=\"unitprice$i\" value=\"$unit_price\" size=6>$gstwarning</td></tr>";

		echo "<tr><th align=left class=\"td1\">RLA Project</th><td><select name=\"rlacharge$i\">";
		echo "<option value=\"\">---select one---";
		if ($i == 0) {
		} else {
			//echo "<option value=\"saa\">Same As Above";
		}
		for ($j=0; $j<$norlacharge; $j++) {
			$codeghrtmp = $rlacharge[$j][1];
			if ($rlaprojid  == $rlacharge[$j][0]) {
				echo "<option value=\"".$rlacharge[$j][0]."\" selected>".$codeghrtmp;
			} else {
				echo "<option value=\"".$rlacharge[$j][0]."\">".$codeghrtmp;
			}
		}
		echo "</td></tr>";
		
		//echo "code_id = $code_id; rlaprojid = $rlaprojid<br>";
		echo "<tr><th align=left class=\"td1\">GHR Cost Center</th><td>
			<select name=\"ghrcharge$i\">";
		echo "<option value=\"\">---select one---";
		if ($i == 0) {
		} else {
			//echo "<option value=\"saa\">Same As Above";
		}
		for ($j=0; $j<$noghrcharge; $j++) {
			$codeghrtmp = $ghrchcode[$j][1];
			if ($code_id == $ghrchcode[$j][0]) {
				echo "<option value=\"".$ghrchcode[$j][0]."\" selected>".$codeghrtmp;
			} else {
				echo "<option value=\"".$ghrchcode[$j][0]."\">".$codeghrtmp;
			}
		}
		echo "</td></tr>";		
		echo "</table></td></tr>";
	}
	
	echo "<tr><td colspan=2>&nbsp;</td></tr>";
	echo "<tr class=tr1><th align=left colspan=2>Invoice Entry</th></tr>";
	
	echo "<tr><td colspan=2 class=tab1><table border=0>";//class=tab1
	echo "<tr><th align=left class=\"td1\">Approved by</th><td colspan=1>";
	echo "<select name=\"authperson\">"; 
	$sql = "SELECT details FROM rlafinance.control 
			WHERE controllist='AuthPerson'";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($details) = mysql_fetch_array($result);
	$tmp = explode(";", $details);
	$machip = getenv("remote_addr");
	$machip = substr($machip ,strlen($machip)-3,3);
	for ($i=1; $i<count($tmp); $i++) {
		if ($tmp[$i]) {
			$person = explode("@",$tmp[$i]);
			$v = $person[0];
			if ($authemail == $person[0]) {
				echo "<option value=\"$v\" selected>".$person[1];
			} elseif ($machip == $person[2]) {
				echo "<option value=\"$v\" selected>".$person[1];
			} else {
				echo "<option value=\"$v\">".$person[1];
			}
		}
	}
	echo "</option></td></tr>";

	echo "<tr><th align=left class=\"td1\">Method</th>";
	echo "<td class=\"td1\"><select name=\"paymethod\">";
	//$sql = "SELECT method_id, description FROM rlafinance.paymethod";
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
	echo "</option></select><b>Select card or enter cheque no<br>
	 	if you pay by credit card or cheque.</b></td></tr>";

	echo "<tr><th align=left class=\"td1\">Select Card</th>";
	echo "<td><select name=\"card_id\">";	
	echo "<option>---select one---";
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
	echo "</option></select></td><tr>";
	
	echo "<tr><th align=left class=\"td1\">Cheque No</th>";
	echo "<td><input type=text name=checqueno size=15></td></tr>";
	
	echo "<tr><th align=left class=\"td1\">Payment Remarks</th>";
	echo "<td colspan=1><input type=text name=\"pay_remarks\" value=\"$pay_remarks\" size=40>";

	echo "<tr><td colspan=2>&nbsp;</td></tr>";
	$col1 = 1;
	$col2 = 1;
	echo "<tr><th align=left class=\"td1\" colspan=$col1>Invoice Date</th>
		<td colspan=$col2><input type=text name=\"invoice_date\" value=\"";
	if ($invoice_date) {
		echo $invoice_date;
	} else {
		echo date("Y-m-d");
	}		
	echo "\"></td></tr>";
	
	if (!$invoice_cost) {
		$invoice_cost = "0.00";
	} 
	if (!$invoice_gst) {
		$invoice_gst = "0.00";
	} 
	if (!$delivery_cost) {
		$delivery_gst = "0.00";
	} 
	if (!$delivery_gst) {
		$delivery_gst = "0.00";
	} 
	
	echo "<tr><th align=left class=\"td1\" colspan=$col1>Invoice No</th>
		<td colspan=$col2><input type=text name=\"invoice_no\" value=\"$invoice_no\"></td></tr>";

	$size = " size=10";
	echo "<tr><th align=left class=\"td1\" colspan=$col1>Goods Cost ($)</th>
		<td colspan=$col2><input type=text name=\"invoice_cost\" value=\"$invoice_cost\"$size>$gstwarning</td></tr>";
	echo "<tr><th align=right class=\"td1\" colspan=$col1>Goods GST ($)</th>
		<td colspan=$col2><input type=text name=\"invoice_gst\" value=\"$invoice_gst\"$size></td></tr>";

	echo "<tr><th align=left class=\"td1\" colspan=$col1>Delivery Cost ($)</th>
		<td colspan=$col2><input type=text name=\"delivery_cost\" value=\"$delivery_cost\"$size>$gstwarning</td></tr>";
	echo "<tr><th align=right class=\"td1\" colspan=$col1>Delivery GST ($)</th>
		<td colspan=$col2><input type=text name=\"delivery_gst\" value=\"$delivery_gst\"$size></td></tr>";
	
	echo "</table></td></tr>";
	
	echo "<tr><td colspan=2 align=center>";
	$vs = "v";
	echo "<button name=valdata ";
	echo "onclick=\"return chkonesteporder($noitem);\"><b>Validate Data</b></button>";
	$vs = "s";
	echo "&nbsp;&nbsp;<button type=\"submit\" name=neworder ";
	echo "onclick=\"return chkonesteporder($noitem);\"><b>Submit</b></button>";
	echo "</td></tr>";	
	echo "<tr><td colspan=4>If the goods you want to buy".
		" is not on the list,<br>please click <font color=#0000ff><b>\"Goods List\"</b></font>".
		" on left frame.</font></td></tr>";
	echo "</table>";
	echo "</form><p>";
	echo "<b>Supplier's Info</b><p>";	
	include("one_supplier_info.inc");
	echo "<hr>";
?>
</html>
