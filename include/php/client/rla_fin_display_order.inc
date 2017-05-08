<html>
<?php
//step 1: search rlafinance.orderid
	$sql = "SELECT order_by, orderdate, estimate_cost, spec_instruction, updatestatus, 
		paymethod, card_cheque, pay_remarks, invoice_no, invoice_date, invoice_cost, invoice_gst, 
		delivery_cost, delivery_gst, ordercancelled 
		FROM rlafinance.orderid 
		WHERE order_id='$order_id'";
	//echo "$sql<br>";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($order_by, $orderdate, $estimate_cost, $spec_instruction, $updatestatus, 
		$paymethod, $card_cheque, $pay_remarks, $invoice_no, $invoice_date, $invoice_cost, 
		$invoice_gst, $delivery_cost, $delivery_gst, $ordercancelled) = mysql_fetch_array($result);
	if (!$order_by) {
		echo "<h2><font color=#ff0000>Order ID $order_id doesn't exist,".
		" the record may have been removed from DB.</font></h2>";
		exit;
	}
	if ($ordercancelled == "y") {
		echo "<h2><font color=#ff0000>Order ID $order_id has been cancelled.</font></h2>";
		exit;
	}

	echo "<table border=0>";
	
//step 2: search timesheet.employee and display who ordered
//*********order procedures
	$sql = "SELECT title as t, first_name as f, middle_name as m, last_name as l
		FROM timesheet.employee WHERE email_name='$order_by'";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($t, $f, $m, $l) = mysql_fetch_array($result);	
	if ($action != "process") {
		echo "<tr><th align=left>Ordered By:</th><td>$f $l</td><td>&nbsp;</tr>";
		echo "<tr><th align=left>Order ID:</th><td>$order_id</td><td>&nbsp;</tr>";
	}
	
//step 3: search rlafinance.ordersteps to display order procedures
	if ($action != "process") {
		echo "<tr><th align= colspan=3>Order Procedures</th></tr>";
	}
	include("rla_fin_order_steps.inc");
	if ($action != "process") {
		for ($i=0; $i<$steps; $i++) {
			echo "<tr><td align=right>".$status[$prostepsdetails[$i][0]]."</td>";
			echo "<td>&nbsp;by ".$prostepsdetails[$i][1]."</td>";
			echo "<td>&nbsp;at ".$prostepsdetails[$i][2]."</td></tr>";
		}
	}	
	
//step 4: This Order Details- search rlafinance.orderdetails
	$sql = "SELECT detailsid, goods_id as id1, supid as id2, unit, unit_price, gst_applicable, 
		gst_percent, code_id, rlaprojid, to_inventory, inv_processed, checkin 
		FROM rlafinance.orderdetails 
		WHERE order_id='$order_id' ORDER BY detailsid";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$i = 0;
	while (list($detailsid, $id1, $id2, $unit, $unit_price, $gst_applicable, 
		$gst_percent, $code_id, $rlaprojid, $to_inventory, $inv_processed, $checkin) = 
		mysql_fetch_array($result)) {
		$orddet[$i][0] = $id1; //goods_id
		$orddet[$i][1] = $id2;	//supid 
		$orddet[$i][2] = $unit;
		$orddet[$i][3] = $unit_price;
		$orddet[$i][4] = $gst_applicable;
		$orddet[$i][5] = $gst_percent;
		$orddet[$i][6] = $code_id;
		$orddet[$i][7] = $rlaprojid;
		$orddet[$i][8] = $to_inventory;
		$orddet[$i][9] = $inv_processed;
		$orddet[$i][10] = $checkin;
		$orddet[$i][11] = $detailsid;
		$i++;
	}
	$noitemordered = $i;
	 
//step 5: all supplier info - search rlafinance.supplierid // and faxno!='' 
	$sql = "SELECT supid as id, company, address, faxno, telno, contactperson 
		FROM rlafinance.supplierid
		where telno!=''
		order by company";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$i = 0;
	while (list($id, $company, $address, $faxno, $telno, $contactperson) = mysql_fetch_array($result)) {
		$idsupno["$id"] = $i;
		$supinfo[$i][0] = $company;
		$supinfo[$i][1] = $address;
		$supinfo[$i][2] = $faxno;
		$supinfo[$i][3] = $telno;
		$supinfo[$i][4] = $contactperson;
		$i++;
	}
	$nosupplier = $i;
	
//step 6: all Goods info - search rlafinance.goodsid
	$sql = "SELECT goods_id as id, name, description, supid as id1, product_code 
		FROM rlafinance.goodsid";
	$result = mysql_query($sql);
	include("err_msg.inc");	
	while (list($id, $name, $description, $id1, $product_code) = mysql_fetch_array($result)) {
		$goodsinfo[$id][0] = $name;
		$goodsinfo[$id][1] = $description;
		$goodsinfo[$id][2] = $id1;
		$goodsinfo[$id][3] = $product_code;
	}
	
//step 7: RLA charging code
	include("rla_fin_rlacode.inc");
	
//step 8: GHR charging code
	include("rla_fin_ghrcode.inc");

//step 9: display heading
	if ($action == "modify") {
		echo "<h2>Order Modification</h2>";
	} elseif ($action == "cancel") {
		echo "<h2>Cancel Order</h2>";
	} elseif ($action == "Verify") {
		echo "<h2>Receive Order</h2>";
	} elseif ($action == "view") {
		if (!$noshowview) {
			echo "<h2>View Order</h2>";
		}		
	} elseif ($action == "aproval") {
		//echo "<h2>Aproval Order</h2>";
	} elseif ($action == "process") {
		//echo "<h2>Send Order to Supplier</h2>";
	} elseif ($action == "invoice") {
		//echo "<h2>Order Invoice Entry</h2>";
	} elseif ($action == "backorder") {
		echo "<h2>Back Order</h2>";
	}

//step 10: display supplier info
	if ($action == "process") {
		echo "<br><font size=3><b>SUPPLIER:</b></font>";
		$b1 = "<br align=left><font size=2>&nbsp;&nbsp;&nbsp;&nbsp;";
		$b2 = "</font>";
		$id3 = $idsupno[$orddet[0][1]];
		echo "<br>".$b1.$supinfo[$id3][0];
		echo "; ".$supinfo[$id3][1]."$b2";
		echo $b1."Fax:  ".$supinfo[$id3][2].";  ";
		echo "Tel:  ".$supinfo[$id3][3].$b2;
		if ($supinfo[$id3][4]) {
			echo "$b1"."Contact Person:  ".$supinfo[$id3][4]."$b2";
		}
		echo "<br><br><font size=3><b>Please supply the following goods</b></font><br>";
		for ($i=0; $i<$j; $i++) {
			echo "=";
		}
		echo "<br>";
	} else {
		if (!$createmodorderform) {
		echo "<tr><th align=left colspan=3>Supplier Info</th></tr>";
		$id3 = $idsupno[$orddet[0][1]];
		$b1 = "<font size=2>";
		$b2 = "</font>";
		echo "<tr><td></td><td>$b1"."Company$b2</td><td>$b1".$supinfo[$id3][0]."$b2</td></tr>";
		echo "<tr><td></td><td>$b1"."Address$b2</td><td>$b1".$supinfo[$id3][1]."$b2</td></tr>";
		echo "<tr><td></td><td>$b1"."Fax$b2</td><td>$b1".$supinfo[$id3][2]."$b2</td></tr>";
		echo "<tr><td></td><td>$b1"."Tel$b2</td><td>$b1".$supinfo[$id3][3]."$b2</td></tr>";
		if ($supinfo[$id3][4]) {
			echo "<tr><td></td><td>$b1"."Contact Person$b2</td><td>$b1".$supinfo[$id3][4]."$b2</td></tr>";
		}
		}
	}
	
//step 11: display Order Details
if ($createmodorderform) {
	echo "</table>";
} else {
	if ($action != "process") {
		echo "<tr><th align=left colspan=3>Order Details</th></tr>";
	}
	echo "</table>";
	echo "<form>";
	echo "<table border=1>";
}
	if ($action == "modify" || $action == "aproval" || $action == "Verify") {
		$supid = $orddet[0][1];
		include("rla_fin_onesupgoods.inc");
		include("rla_fin_order_reproduce.inc");
		/*
		echo "$noghrcharge <br>";
		$ghrcharge[$i][0]=$id;
		$ghrcharge[$i][1]=$des;
		$ghrcharge[$i][2]=$category;
		$ghrcode["$codes"] = $des;
		//*/
	} elseif ($action == "fullvieworder" || $action == "invoice" || $action == "backorder") {
		$supid = $orddet[0][1];
		include("rla_fin_onesupgoods.inc");
		include("rla_fin_order_reproduce.inc");
	} elseif ($action == "process") {
		echo "<table border=0>";
		$costcentercode= "";
		$extraline = 0;
		if ($ghr) {
			$costcentercode = "<th>Cost Center</th>";
			$sql = "SELECT projcode_id as rlapghr, subac, ccc 
        		FROM rlafinance.rlapjcvsghrsccc ORDER BY projcode_id";
    		$result = mysql_query($sql);
    		include("err_msg.inc");

    		while (list($rlapghr, $subac, $ccc) = mysql_fetch_array($result)) {
    			if (!$subac) {
    				$subac = "sa";
    			}
    			if (!$subac) {
    				$ccc= "cc";
    			}
        		$projcovsghr[$rlapghr] = "$subac-$ccc";
        	}   	
		}
		echo "<tr><th width=20>&nbsp;</th><th>Item</th>$costcentercode<th>Product</th><th>Quantity</th><th>
		&nbsp;$/Unit*&nbsp;</th><th>Sub Total</th></tr>";
		for ($i=0; $i<$noitemordered; $i++) {
			$id = $orddet[$i][0];
			$j = $i + 1;
			$costcentercode="";
			if ($ghr) {
				$gcc = $orddet[$i][6];
				if (!$gcc) {
					$gcc = "gcc";
				}
				$costcentercode = "<td align=middle>($gcc-".
					$projcovsghr[$orddet[$i][7]].")</td>";
				//ghrcodeno= $orddet[$i][6]/ rlacodeno= $orddet[$i][7];
			}
			$subtot = $orddet[$i][2]*$orddet[$i][3];
			$subtot = number_format($subtot, 2);
			$reorderid = "";
			if ($goodsinfo[$id][3]) {
				$reorderid = " (cat_no: <b>".$goodsinfo[$id][3]."</b>)";
			}
			$proddesc = $goodsinfo[$id][0]."-".$goodsinfo[$id][1].$reorderid;
			include("rla_fin_linesforproduct.inc");
			//echo "<tr><th>&nbsp;</th><th>$j</th>" Tony suggestion 23/01/2003
			echo "<tr><th>&nbsp;</th><th>.</th>
				$costcentercode
				<td>$proddesc</td>
				<td align=right>".$orddet[$i][2]."&nbsp;&nbsp;</td>
				<td align=right>".$orddet[$i][3]."&nbsp;&nbsp;</td>
				<th align=right>".$subtot."</th></tr>";
		}

		echo "<tr><td>&nbsp;</td></tr>";
		if ($ghr) {
			$colspans = 4;
			$spancol = 6;
		} else {
			$colspans = 3;
			$spancol = 5;
		}	
		if ($spec_instruction && !$printorder) {
			$coststr = "<th colspan=$colspans align=left>Total Cost Unknown";
		} else {
			$coststr = "<th colspan=$colspans align=right>A\$$estimate_cost";
		}
		echo "<tr><td></td><th align=left colspan=2>Cost Estimation</th>
			$coststr</th></tr>";
		if ($spec_instruction && !$printorder) {
			echo "<tr><td>&nbsp;</td><td align=left colspan=2><b>Extra Order Info</b></td>
			<th colspan=3 align=left>$spec_instruction</th></tr>";
			$noitemordered++;
		}
		echo "<tr><td></td><td colspan=$spancol><font size=2>* Price includes GST.</font></td></tr>";
		$emptyrow = 15 - $noitemordered - $extraline;
		if ($emptyrow > 0) {
			for ($er=0; $er<$emptyrow; $er++) {
				echo "<tr><td>&nbsp;</td></tr>";		
			}
		}
		
		echo "<tr><td>&nbsp;</td><td align=left colspan=5><b>Paid By ";
		//Payment method
		$paystring[1] = "Credit Card";
		$paystring[2] = "Cheque";
		$paystring[3] = "Cash";
		$paystring[4] = "Account";
		$paystring[5] = "EFT";
		echo 	$paystring[$paymethod];
		echo "</b>.</td></tr>";
		
		if ($pay_remarks) {
			echo "<tr><td>&nbsp;</td><td align=left colspan=5><b>Payment Instruction: ".
			"$pay_remarks</b></td></tr>";
		} else {
			echo "<tr><td>&nbsp;</td></tr>";
		}
		echo "</table>";
	} elseif ($action == "view" || $action == "cancel") {
		echo "<tr><th>No</th><th>Product</th><th>Qty</th><th>$/Unit</th><th>For Project</th></tr>";
		for ($i=0; $i<$noitemordered; $i++) {
			$id = $orddet[$i][0];
			$j = $i + 1;

			echo "<tr><td>$j</td>";
				if ($goodsinfo[$id][3]) {
					echo "<td>".$goodsinfo[$id][0]."-".$goodsinfo[$id][1]." (".$goodsinfo[$id][3].")</td>";
				} else {
					echo "<td>".$goodsinfo[$id][0]."-".$goodsinfo[$id][1]."</td>";
				}
				echo "<td>".$orddet[$i][2]."</td>
				<td>".$orddet[$i][3]."</td>
				<td>".$rlacode[$orddet[$i][7]]."</td></tr>";//$orddet[$i][7]."-"
		}
		
		if ($spec_instruction) {
			$coststr = " (cost for extra goods is not included)";
		} else {
			$coststr = "";
		}
		echo "<tr><th align=left colspan=2>Cost Estimation</th>
			<td colspan=3>A\$$estimate_cost$coststr</td></tr>";
		if ($spec_instruction) {
			echo "<tr><td align=left colspan=2><b>Extra Order Info</b></td>
			<td colspan=3>$spec_instruction</td></tr>";
		}
		if ($action == "cancel") {
			echo "<tr><td colspan=5 align=center>";
			echo '<input type="hidden" name="order_id" value= "'.$order_id.'">';
			echo '<input type="hidden" name="frm_str" value= "'.$userstr.'">';
			echo "<input type=\"submit\" name=cancelorder value=\"Cancel This Order\"";
			echo " onclick=\"return cancelorderconfirm($order_id);\" ";
			echo ">";
			echo "</td></tr>";
		}
	}
if (!$createmodorderform && !$printorder) {
	echo "</table></form>";
}
?>
</html>
