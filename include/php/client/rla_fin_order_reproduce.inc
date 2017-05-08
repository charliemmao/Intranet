<html>
<?php
	if ($noitemordered+5 < $nogs) {
		$noitem = $noitemordered+5;
	} else {
		$noitem = $nogs;
	}
	
	if ($action == "aproval") {
		$noitem = $noitemordered;
	}
	//echo "action = $action<br>";
	//modify or approval form 
	echo "<form name=orderform method=post>";
	echo '<input type="hidden" name="frm_str" value= "'.$userstr.'">';
	echo '<input type="hidden" name="supid" value= "'.$supid.'">';
	echo '<input type="hidden" name="noitem" value= "'.$noitem.'">';
	echo '<input type="hidden" name="noitemordered" value= "'.$noitemordered.'">';
	echo '<input type="hidden" name="order_id" value= "'.$order_id.'">';
	
	echo "<table border=1>";
//###########################################
	if ($action == "modify") {
		echo "<tr><th>No</th><th>Product ($nogs)</th><th>Qty</th><th>$/Unit</th><th>For Project</th></tr>";
		$col=5;
		for ($i=0; $i<$noitemordered; $i++) {
			$k= $i+1;
			$l = $orddet[$i][0];
			echo "<tr><td>$k</td><td><select name=\"goods$i\">";
			echo "<option value=\"\">---select one---";
			for ($j=0; $j<$nogs; $j++) {
				$id = $glist[$j][0];
				$tmp = $glist[$j][1];
				if ($l == $glist[$j][0]) {
					echo "<option value=\"$id\" selected>$tmp";
				} else {
					echo "<option value=\"$id\">$tmp";
				}
			}
			echo "</option></select></td>";
			echo "<td><input type=\"text\" name=\"quantity$i\" value=\"".$orddet[$i][2]."\" size=4></td>";

			echo "<td><input type=\"text\" name=\"unitprice$i\" value=\"".$orddet[$i][3]."\" size=6></td>";

			echo "<td><select name=\"rlacharge$i\">";
			$kk = $orddet[$i][7];	
			for ($j=0; $j<$norlacharge; $j++) {
				$ll= $rlacharge[$j][0];

				if ($kk == $ll) {
					echo "<option value=\"$ll\" selected>".$rlacharge[$j][1];
				} else {
					echo "<option value=\"$ll\">".$rlacharge[$j][1];
				}
			}
			echo "</td>";
			echo "</tr>";
		} 

		for ($i=$noitemordered ; $i<$noitem; $i++) {
			$k= $i+1;
			echo "<tr><td>$k</td><td><select name=\"goods$i\">";
			echo "<option value=\"\">---select one---";
			for ($j=0; $j<$nogs; $j++) {
				$id = $glist[$j][0];
				$tmp = $glist[$j][1];
				echo "<option value=\"$id\">$tmp";
			}
			echo "</option></select></td>";
			echo "<td><input type=\"text\" name=\"quantity$i\" size=4></td>";
			echo "<td><input type=\"text\" name=\"unitprice$i\" size=6></td>";
			echo "<td><select name=\"rlacharge$i\">";
			if ($i == 0) {
				echo "<option value=\"\">---select one---";
			} else {
				echo "<option value=\"saa\">Same As Above";
			}
			for ($j=0; $j<$norlacharge; $j++) {
				echo "<option value=\"".$rlacharge[$j][0]."\">".$rlacharge[$j][1];
			}
			echo "</td>";
			echo "</tr>";
		} 
		$sql = "SELECT details FROM rlafinance.control WHERE controllist='AuthPerson'";
		$result = mysql_query($sql);
		include("err_msg.inc");
		list($details) = mysql_fetch_array($result);
		$tmp = explode(";", $details);
		echo "<tr><th align=left colspan=2>Cost Estimation</th>
			<td colspan=3><input type=\"text\" name=\"estimate_cost\" value=\"$estimate_cost\"></td></tr>";
		if ($modifyapprovedorder == "y") {
			echo "<input type=hidden name=spec_instruction>";
			echo "<input type=hidden name=authperson value=tml>";
			echo "<input type=hidden name=askforapproval value=n>";
			
		} else {

		$tmpstr = "<br>&nbsp;&nbsp;";
		$tmpstr="";
		echo "<tr><td align=left colspan=2><b>Extra Order Info</b><font size=2 color=#0000ff>
		$tmpstr If you have too many items to order,
		$tmpstr enter at least one item to the list above and
		$tmpstr write others on a piece of paper deliver
		$tmpstr it to purchasing officer. Type instruction to box on the right to indicate 
		you have extra items to order.
		<td colspan=3><textarea rows=\"5\" name=\"spec_instruction\" cols=\"36\">".
			"$spec_instruction</textarea></tr>";
		
		echo "<tr><th align=left colspan=$col>Select person to approve this order ";
		echo "<select name=\"authperson\">";
		include("find_admin_ip.inc");
		if ($email_name == "$adminname") {
			$k=0;
		} else {
			$k=1;
		}
		for ($i=$k; $i<count($tmp); $i++) {
			if ($tmp[$i]){
				$person = explode("@",$tmp[$i]);
				$v = $person[0];
				echo "<option value=\"$v\">".$person[1];
			}
		}
		echo "</option></th></tr>";
	
		echo "<tr><th align=left colspan=$col>Do you want to send mail to above person?&nbsp;";
		echo "<select name=\"askforapproval\">";
		echo "<option value=\"y\">Yes";
		echo "<option value=\"n\">No";
		echo "</option></select>";
		echo "<br><font size=2 color=#0000ff>Select \"Yes\" to get your order approved.<br>";
		echo "Select \"No\" in case you want to modify it or some goods are not in the goods list.</font>";
		echo "</th></tr>";
		}
//###########################################
	} elseif ($action == "fullvieworder" || $action == "invoice") {
		$col=6;
		echo "<tr><th>No</th><th>Product</th><th>Quantity</th><th>$/Unit</th>
			<th>Cost Center</th><th>For Project</th></tr>";
		for ($i=0; $i<$noitemordered; $i++) {
			$k= $i+1;
			$l = $orddet[$i][0];
			echo "<tr><th>$k</th>";
			
			for ($j=0; $j<$nogs; $j++) {
				$id = $glist[$j][0];
				$tmp = $glist[$j][1];
				if ($l == $glist[$j][0]) {
					echo "<td>$tmp</td>";
					break;
				}
			}
			echo "<td align=right>".$orddet[$i][2]."</td>";
			echo "<td align=right>".$orddet[$i][3]."</td>";
			
			$ghrcodeid = $orddet[$i][6];
			$ghrcode="n";
			for ($j=0; $j<$noghrcharge; $j++) {
				$tt = $ghrchcode[$j][0];
				if ($ghrcodeid == $tt) {
					echo "<td>".$ghrchcode[$j][1]."</td>";
					$ghrcode="y";
					break;
				}
			}
			if ($ghrcode == "n") {
				echo "<td>&nbsp;</td>";
			}

			$kk = $orddet[$i][7];	
			for ($j=0; $j<$norlacharge; $j++) {
				$ll= $rlacharge[$j][0];
				if ($kk == $ll) {
					echo "<td>".$rlacharge[$j][1]."</td>";
					break;
				}
			}
			echo "</tr>";
		} 
		
		$col1 = 2;
		$col2 = $col - $col1;
		if ($spec_instruction) {
			echo "<tr><td align=left colspan=$col1><b>Extra Order Info</b></td>
			<td colspan=$col2>$spec_instruction</td></tr>";
		}
		if ($spec_instruction) {
			$coststr = " (cost for extra goods is not included)";
		} else {
			$coststr = "";
		}
		echo "<tr><th align=left colspan=$col1>Cost Estimation</th>
			<td colspan=$col2>$estimate_cost$coststr
			<input type=hidden name=\"estimate_cost\" value=\"$estimate_cost\">
			</td></tr>";
		
		echo "<tr><th align=left colspan=$col1>Payment Method</th><td colspan=$col2>";
		//$sql = "SELECT method_id, description FROM rlafinance.paymethod order by method_id";
		$sql = "SELECT method_id, description FROM rlafinance.paymethod order by description";
		$result = mysql_query($sql);
		include("err_msg.inc");
		$no = mysql_num_rows($result);
		while (list($method_id, $description) = mysql_fetch_array($result)) {
			$description = ucwords($description);
			if ($paymethod == $method_id) {
				echo "<b>$description</b>";
			}
		}
		//cash=3; cheque=2; credit=1
		if ($paymethod == 2) {
			echo "&nbsp;&nbsp;Cheque No: $card_cheque";
		} elseif ($paymethod == 1) {
			$sql = "SELECT holder, cardtype, cardno, expiredate, issuer 
				FROM rlafinance.creditcard WHERE card_id='$card_cheque'"; 
			$result = mysql_query($sql);
			include("err_msg.inc");
			list($holder, $cardtype, $cardno, $expiredate, ) = mysql_fetch_array($result);
			echo "&nbsp;&nbsp;$issuer-$cardtype ($holder)";
		}
		echo "</td></tr>";
	
		if ($pay_remarks) {
			echo "<tr><th align=left colspan=$col1>Payment Remarks</th>
			<td colspan=$col2>$pay_remarks</td></tr>";
		}
		
		//################################
		if ($action == "fullvieworder") {
			if ($invoice_date != "0000-00-00") {
				echo "<tr><th align=left colspan=$col1>Invoice Date</th>
				<td colspan=$col2>$invoice_date</td></tr>";
			}
			if ($invoice_no>0) {
				echo "<tr><th align=left colspan=$col1>Invoice No</th>
				<td colspan=$col2>$invoice_no</td></tr>";
			}
			if ($invoice_cost>0) {
				echo "<tr><th align=left colspan=$col1>Goods Cost</th>
				<td colspan=$col2>$"."$invoice_cost (Inc GST: $"."$invoice_gst)</td></tr>";
			}
			if ($delivery_cost>0) {
				echo "<tr><th align=left colspan=$col1>Delivery Charge</th>
				<td colspan=$col2>$"."$delivery_cost (Inc GST: $"."$delivery_gst)</td></tr>";
			}		
			if ($delivery_cost>0) {
				$tcost = $delivery_cost + $invoice_cost;
				$tgst = $invoice_gst + $delivery_gst;
				echo "<tr><th align=left colspan=$col1>Total Cost</th>
				<td colspan=$col2>$"."$tcost (Inc GST: $"."$tgst)</td></tr>";
			}
		//##########################
		} elseif ($action == "invoice") {		
			echo "<tr><th align=left colspan=$col>&nbsp;</th></tr>";
			echo "<tr><th align=left colspan=$col1>Invoice Date</th>
				<td colspan=$col2><input type=text name=\"invoice_date\" value=\"".date("Y-m-d")."\"></td></tr>";
				
			echo "<tr><th align=left colspan=$col1>Invoice No</th>
				<td colspan=$col2><input type=text name=\"invoice_no\"></td></tr>";

			echo "<tr><th align=left colspan=$col1>Goods Cost ($)</th>
				<td colspan=$col2><input type=text name=\"invoice_cost\" value=\"0.00\"></td></tr>";
			echo "<tr><th align=left colspan=$col1>Goods GST ($)</th>
				<td colspan=$col2><input type=text name=\"invoice_gst\" value=\"0.00\"></td></tr>";

			echo "<tr><th align=left colspan=$col1>Delivery Cost ($)</th>
				<td colspan=$col2><input type=text name=\"delivery_cost\" value=\"0.00\"></td></tr>";
			echo "<tr><th align=left colspan=$col1>Delivery GST ($)</th>
				<td colspan=$col2><input type=text name=\"delivery_gst\" value=\"0.00\"></td></tr>";
		}		
//###########################################
	} elseif ($action == "aproval") {
		$col=6;
		echo "<tr><th>No</th><th>Qty</th><th>$/Unit</th><th>Product ($nogs)</th>
			<th>Cost Center ($noghrcharge)</th><th>For Project</th></tr>";
		for ($i=0; $i<$noitemordered; $i++) {
			$k= $i+1;
			$l = $orddet[$i][0];
			echo "<tr><td>$k</td>";
			
			echo "<td>".$orddet[$i][2].
				"<input type=\"hidden\" name=\"detids$i\" value=\"".$orddet[$i][11]."\">";
			echo "</td>";
			echo "<td>".$orddet[$i][3]."</td>";
			echo "<td><input type=\"hidden\" name=\"goods$i\" ";
			for ($j=0; $j<$nogs; $j++) {
				$id = $glist[$j][0];
				$tmp = $glist[$j][1];
				if ($l == $glist[$j][0]) {
					echo "value=\"$id\" >$tmp";
					break;
				}
			}
			echo "</td>";
			
			$ghrcodeid = $orddet[$i][6];
			echo "<td><select name=\"ghrcharge$i\">";
			if ($i == 0) {
				echo "<option value=\"\">---select one---";
			} else {
				echo "<option value=\"saa\">Same As Above";
			}
			for ($j=0; $j<$noghrcharge; $j++) {
				$tt = $ghrchcode[$j][0];
				if ($ghrcodeid == $tt) {
					echo "<option value=\"".$tt."\" selected>".$ghrchcode[$j][1];
				} else {
					echo "<option value=\"".$tt."\">".$ghrchcode[$j][1];
				}
			}
			echo "</td>";

			echo "<td><input type=\"hidden\" name=\"rlacharge$i\" ";
			$kk = $orddet[$i][7];	
			for ($j=0; $j<$norlacharge; $j++) {
				$ll= $rlacharge[$j][0];
				if ($kk == $ll) {
					echo "value=\"$ll\">".$rlacharge[$j][1];
				}
			}
			echo "</td>";
			echo "</tr>";
		} 
		
		if ($spec_instruction) {
			$coststr = " (cost for extra goods is not included)";
		} else {
			$coststr = "";
		}
		echo "<tr><th align=left colspan=3>Cost Estimation</th>
			<td colspan=3>$estimate_cost$coststr</td></tr>";
		echo "<tr><td align=left colspan=3><b>Extra Order Info</b></td>
			<td colspan=3>$spec_instruction</td></tr>";

		$sqlreason = "SELECT orderreason 
        		FROM rlafinance.orderreason 
        		WHERE order_id='$order_id'";
    		$resultreason = mysql_query($sqlreason );
    		include("err_msg.inc");
    		list($orderreason) = mysql_fetch_array($resultreason);
		echo "<tr><td align=left colspan=3><b>Reason</b></td>
			<td colspan=3>$orderreason</tr>";

		echo "<tr><th align=left colspan=3>Approved by</th><td colspan=3>";
		echo "<select name=\"authperson\">"; 
		include("find_admin_ip.inc");
		if (getenv("remote_addr") == $adminip) {
			$k=1;
		} else {
			$k=1;
		}
		$sql = "SELECT details FROM rlafinance.control 
			WHERE controllist='AuthPerson'";
		$result = mysql_query($sql);
		include("err_msg.inc");
		list($details) = mysql_fetch_array($result);
		$tmp = explode(";", $details);
		$machip = getenv("remote_addr");
		$machip = substr($machip ,strlen($machip)-3,3);
		for ($i=$k; $i<count($tmp); $i++) {
			$person = explode("@",$tmp[$i]);
			$v = $person[0];
			if ($machip == $person[2]) {
				echo "<option value=\"$v\" selected>".$person[1];
			} else {
				echo "<option value=\"$v\">".$person[1];
			}
		}
		echo "</option></td></tr>";
		
		$cspan =3;
		include("rla_fin_paymethod.inc");

		echo "<tr><th align=left colspan=3>Payment Remarks</th>";
		echo "<td colspan=3><input type=text name=\"pay_remarks\" value=\"$pay_remarks\" size=60>";
//###########################################
	} elseif ($action == "Verify") {
		$col=5;
		echo "<tr><th>No</th><th>Product</th><th>Qty Ordered</th><th>Qty Received</th></tr>";
		for ($i=0; $i<$noitemordered; $i++) {
			$k= $i+1;
			$l = $orddet[$i][0];
			echo "<tr><td>$k</td>";	//No
				
			for ($j=0; $j<$nogs; $j++) {	//Product
				$id = $glist[$j][0];
				$tmp = $glist[$j][1];
				if ($l == $glist[$j][0]) {
					echo "<td>$tmp<input type=\"hidden\" name=\"product$i\" value=\"$tmp\"></td>";
					break;
				}
			}
			
			echo "<td align=right>".$orddet[$i][2].
				"<input type=\"hidden\" name=\"detids$i\" value=\"".$orddet[$i][11]."\"></td>";
			echo "<td><input type=\"hidden\" name=\"unitordered$i\" value=\"".$orddet[$i][2]."\">";
			echo "<input type=\"text\" name=\"unitorcvd$i\" value=\"".$orddet[$i][2]."\" ></td>";
			
			echo "</tr>";
		} 
//###########################################
	} elseif ($action == "backorder") {
		$col=6;
		echo "<tr><th>No</th><th>Product</th><th>Qty Ordered</th><th>Qty Back</th><th>Qty Received</th></tr>";
		$sql = "SELECT autoid, detailsid, unit_ord, unit_rcvd, product FROM rlafinance.backorder 
			WHERE order_id='$order_id' and checkin='n'";
		$result = mysql_query($sql);
		include("err_msg.inc");
		$nobackorder = mysql_num_rows($result);
		echo "<input type=\"hidden\" name=\"nobackorder\" value=\"$nobackorder\">";
		echo "<input type=\"hidden\" name=\"bkveryorder_id\" value=\"$order_id\">";
		$k = 0;
		while (list($autoid, $detailsid, $unit_ord, $unit_rcvd, $product) = 
			mysql_fetch_array($result)) {
			$k1=$k+1;
			echo "<tr><td>$k1</td>";	//No
			$tmp = $unit_ord - $unit_rcvd;
			echo "<td>$product
				<input type=\"hidden\" name=\"autoid$k\" value=\"$autoid\">
				<input type=\"hidden\" name=\"detids$k\" value=\"$detailsid\">
				<input type=\"hidden\" name=\"product$k\" value=\"$product\">
				<input type=\"hidden\" name=\"bkordered$k\" value=\"$tmp\">				
				</td>";
			
			echo "<td align=right>$unit_ord</td>";
			echo "<td align=right>$tmp</td>";
			echo "<td><input type=\"text\" name=\"unitorcvd$k\" value=\"".$tmp."\" ></td>";
			echo "</tr>";
			$k++;
		} 
	}	
	
	echo "<tr><td colspan=$col align=center>";
	if ($action == "modify") {
		echo "<input type=\"submit\" name=ordermodification value=\"Modify This Order\"";
		echo " onclick=\"return chkorder($noitem);\" >";
	} elseif ($action == "aproval") {
		echo "<input type=\"submit\" name=approvalorder value=\"Approval This Order\"";
		echo " onclick=\"return orderapproval($noitem);\" >";
	} elseif ($action == "Verify") {
		echo "<input type=\"submit\" name=Verifyorder value=\"Receive This Order\"";
		echo " onclick=\"return orderVerify($noitem);\" >";
	} elseif ($action == "backorder") {
		echo "<input type=\"submit\" name=verifybackorder value=\"Receive This Back Order\"";
		echo " onclick=\"return backorderverify($nobackorder);\" >";
	} elseif ($action == "invoice") {
		echo "<input type=\"submit\" name=invoiceentry value=\"Submit\"";
		echo " onclick=\"return invoiceentryconfirm();\" >";
	}
	echo "</td></tr>";	

	if ($action == "modify") {
		echo "<tr><td colspan=$col>If the goods you want to buy".
			" is not on the list, please click <font color=#0000ff><b>\"Goods List\"</b></font>".
			" on left frame.<br><br><font color=#ff0000>NB: Unit price includes GST.</font></td></tr>";
	}
	echo "</table>";
	echo "</form><p>";
?>
</html>
