<html>
<body background="rlaemb.JPG" leftmargin="20">
<?php
//js
include('inventory_verify.inc');
include('str_decode_parse.inc');
include("rla_functions.inc");
include("regexp.inc");
include("rla_fin_order.inc");
include("rla_fin_controldata.inc");

include("userinfo.inc"); //$userinfo
$userstr	=	"?".base64_encode($userinfo);

if ($action == "fullvieworder") {
	include("rla_fin_display_order.inc");
	exit;
}

echo "<h2 align=center><a id=top>Search Order by Supplier</a>";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[Refresh]</font></a></h2><hr>";
include("connet_root_once.inc");

//#######################################################################
//***********Search Order Form
	echo "<form name=searchform method=post>";
	//echo "<h2>Search RLA Order</h2>";
	echo "<table>";
/*
	echo "<tr><th align=left>Select Supplier</th>";
		echo "<td><select name=searchcat>";
		echo "<option>Company";
		//echo "<option>Product";
		echo "</option></select></td></tr>";
//*/
	echo "<input type=hidden name=searchcat value=Company>";

	$sql = "SELECT supid as id, company, faxno, telno FROM rlafinance.supplierid 
		where telno!='' 
		order by company";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	if (!$companyid) {
		$companyid = 0;
	}
	echo "<tr><th align=left>Select Supplier</th>";
		echo "<td><select name=companyid>";
		while (list($id, $company, $faxno, $telno) = mysql_fetch_array($result)) {
			$companyname[$id] = $company;
			if ($companyid == $id) {
				echo "<option value=$id selected>$company: $telno";
			} else {
				echo "<option value=$id>$company: $telno";
			}
		}
		echo "</option></select></td></tr>";
/*
	$sql = "SELECT t2.company,
		t1.goods_id as id, t1.name, t1.description, t1.product_code 
		FROM rlafinance.goodsid as t1, rlafinance.supplierid as t2 
		WHERE t1.supid=t2.supid
		ORDER BY t1.name, t2.company";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	echo "<tr><th align=left>Produc List ($no)</th>";
		echo "<td><select name=productid>";
		while (list($company, $id, $name, $description, $product_code) 
			= mysql_fetch_array($result)) {
			echo "<option value=$id>$name";
		}
		echo "</option></select></td></tr>";
//*/
	echo "<tr><th align=left>Period</th>";
		if (!$mthst) {
			$mthst = date("m");
		}
		if (!$yearst) {
			$yearst = date("Y");
		}
		if (!$mthed) {
			$mthed = date("m");
		}
		if (!$yeared) {
			$yeared = date("Y");
		}
		$yearfrom = 2001;
		$yearto = date("Y");
		if (20 < $yearto - $yearfrom) {
			$yearfrom = $yearto - 20;
		}
		
		echo "<td><b>From&nbsp;</b><select name=mthst>";
		for ($i=1; $i<=12; $i++) {
			if ($i == $mthst) {
				echo "<option value=$i selected>".$mths[$i];
			} else {
				echo "<option value=$i>".$mths[$i];
			}
		}
		echo "</option></select>";
		echo "<select name=yearst>";
		for ($i=$yearfrom; $i<=$yearto ; $i++) {
			if ($i == $yearst) {
				echo "<option value=$i selected>".$i;
			} else {
				echo "<option value=$i>".$i;
			}
		}
		echo "</option></select>";
		
		echo "<b>&nbsp;To&nbsp;</b><select name=mthed>";
		for ($i=1; $i<=12; $i++) {
			if ($i == $mthed) {
				echo "<option value=$i selected>".$mths[$i];
			} else {
				echo "<option value=$i>".$mths[$i];
			}
		}
		echo "</option></select>";
		echo "<select name=yeared>";
		for ($i=$yearfrom; $i<=$yearto ; $i++) {
			if ($i == $yeared) {
				echo "<option value=$i selected>".$i;
			} else {
				echo "<option value=$i>".$i;
			}
		}
		echo "</option></select>";
	echo "</td></tr>";
	echo "<tr><th align=left>Date Type</th>";
	echo "<td><select name=typedate>";
	$dtype[0] = "Order date";
	$dtype[1] = "Invoice Date";
	if (!$typedate) {
		$typedate = 0;
	}
	for ($i=0; $i<2; $i++) {
		if ($i == $typedate) {
			echo "<option value=$i selected>".$dtype[$i];
		} else {
			echo "<option value=$i>".$dtype[$i];
		}
	}
	echo "</option></select></td></tr>";
	echo "<tr><th  colspan=2>&nbsp;</th></tr>";
	echo "<tr><th colspan=2><button type=\"submit\" name=searchorder><b>Search Order</b></button></td></tr>";
	echo "</table><p>";

if ($searchorder) {
	echo "<hr><h2>RLA Order Search Results for company<br><font color=#0000ff>".
		$companyname[$companyid]."</font></h2>";
		if (10 > $mthst) {
			$mthst = "0".$mthst;
		}
		if ($mthed == 12) {
			$mthed = 1;
			$yeared = $yeared + 1;
		} else {
			$mthed++;
		}
		if (10 > $mthed ) {
			$mthed = "0".$mthed;
		}
		$dfrom = "$yearst-$mthst-01";
		$dto =	"$yeared-$mthed-01";
		if ($typedate == 1) {
			$datecond = " (t1.invoice_date>='$dfrom' and t1.invoice_date<'$dto')";
		} else {
			$datecond = " (t1.orderdate>='$dfrom' and t1.orderdate<'$dto')";
		}	
//#######################################################################
//***********List Search Order
	$sql = "SELECT distinct t1.order_id, t1.order_by, t1.orderdate, t1.updatestatus, 
		t1.estimate_cost, t1.invoice_cost, t1.delivery_cost
		FROM rlafinance.orderid as t1, rlafinance.orderdetails as t2
		WHERE t2.supid='$companyid'  and t1.order_id=t2.order_id and $datecond 
		ORDER BY order_id DESC";
	//echo "$sql<br>";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	include("rla_fin_order_steps.inc");
	if ($no) {
		$totalcost = 0;
		echo "<table border=1>";
		echo "<tr><th>ID</th><th>Date</th><th>Order By</th><th>Status</th><th>Cost</th><th>View</th></tr>";
		while (list($order_id, $order_by, $orderdate, $updatestatus,
			$estimate_cost, $invoice_cost, $delivery_cost) = mysql_fetch_array($result)) {
			echo "<tr><td>$order_id</td>";
			echo "<td>$orderdate</td>";	
			echo "<td>$order_by</td>";
			//echo "<td>".$status[$prostepsdetails[$steps-1][0]]."</td>";	
			echo "<td>".$status[$updatestatus]."</td>";
			if (0 < $invoice_cost) {
				$cost = $invoice_cost + $delivery_cost;
			} else {
				$cost = $estimate_cost;
			}
			echo "<td>$cost</td>";
			$totalcost = $totalcost + $cost;
			$userstr	=	"?".base64_encode($userinfo."&action=fullvieworder&order_id=$order_id");
			echo "<td><a href=\"".$PHP_SELF."$userstr\" target=\"_blank\">[VIEW]</a></td>";
			echo "</tr>";
		}
		echo "<tr><th align=left colspan=6>Total purchase from <br>".
			$companyname[$companyid]."<br>are \$$totalcost.</th></tr>";
		echo "</table><p>";
	} else {
		echo "<h3><font color=#ff0000>No record has been found.</font></h3>";
	}
}
echo "<hr><br><a href=#top>Back to top</a><br><br>";

?>
</html>
