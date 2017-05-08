<html>
<body background="rlaemb.JPG" leftmargin="20">
<?php
//js
include('inventory_verify.inc');
include('str_decode_parse.inc');
include("rla_functions.inc");
include("regexp.inc");
include("rla_fin_order.inc");
include("userinfo.inc"); //$userinfo
$userstr	=	"?".base64_encode($userinfo);
if ($viewonly != "true") {
	echo "<h2 align=center><a id=top>Order View & Manipulation</a>";
	echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[Refresh]</font></a></h2>";
} 
echo "<hr>";
include("connet_root_once.inc");

//############# Cancel order
if ($cancelorder) {
	$sql = "UPDATE rlafinance.orderid SET ordercancelled='y', updatestatus='f'
	 	WHERE order_id='$order_id'";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$stepstr = "c";
	include("rla_fin_order_procedure_update.inc");
	echo "<h2>Order $order_id has been cancelled.</h2><hr>";
}

//############# process modified order
if ($ordermodification) {
	include("rla_fin_process_modified_order.inc");
}

//identify order for manipulation (modify, cancel and others)
//enter data to DB
/* table 1: orderid. columns:
	order_id, order_by, orderdate, estimate_cost, spec_instruction,	
	updatestatus	
	paymethod, pay_remarks	
	invoice_no, invoice_date, invoice_cost, invoice_gst, delivery_cost, delivery_gst
*/
/* table 2: orderdetails. columns: 
	order_id, goods_id, supid, unit, unit_price, rlaprojid,
	code_id
	gst_applicable, gst_percent, 
	to_inventory, inv_processed, 
	checkin
*/
/* table 3: ordersteps. columns: order_id, process, */
//$updatestatus: n=new;m=modified;c=cancelled;p=approved;s=sent;
//r=received;v=verified;b=backorder;f=completed

if ($action) {
	include("rla_fin_display_order.inc");
	echo "<hr>";
}

if ($viewonly == "true") {
	exit;
} 
//***********Current Order
	$sql = "SELECT order_id, orderdate, updatestatus as sta FROM rlafinance.orderid 
		WHERE order_by='$email_name' and updatestatus!='f' ORDER BY order_id DESC";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	if ($no) {
		echo "<h2>My Current Order</h2>";
		echo "<table border=1>";
		echo "<tr><th>ID</th><th>Date</th><th>Status</th>
			<th>Modify</th><th>Change Supplier</th><th>View</th><th>Supplier</th></tr>";
			//"<th>Modify</th><th>Change Supplier</th><th>Cancel</th><th>View</th></tr>";
		while (list($order_id, $orderdate, $sta) = mysql_fetch_array($result)) {
			include("rla_fin_order_steps.inc");
			//$prostepsdetails[$steps][$pi]	$status[]
			echo "<tr><td>$order_id</td>";					// order id
			echo "<td>$orderdate</td>";	// date ordered ".$prostepsdetails[0][2]."
			echo "<td>".$status[$prostepsdetails[$steps-1][0]]."</td>";	// current status
			
			$userstr	=	"?".base64_encode($userinfo."&action=modify&order_id=$order_id");
			$hidtext = $PHP_SELF.$userstr;
			if ($sta == "n" || $sta == "m" || $sta == "q") {
				echo "<td><a href=\"$hidtext\">[Modify]</a></td>";
			} else {
				echo "<th>---</a></th>";
			}
			
			$userstr	=	"?".base64_encode($userinfo."&action=changesupplier&order_id=$order_id");
			if ($sta == "n" || $sta == "m" || $sta == "q") {
				echo "<td align=middle><a href=\"rla_fin_order_ch_sup.php$userstr\">[Change]</a></td>";
			} else {
				echo "<th>---</a></th>";
			}
	/*/
			$userstr	=	"?".base64_encode($userinfo."&action=cancel&order_id=$order_id");
			if ($sta == "n" || $sta == "m") {
				echo "<td><a href=\"".$PHP_SELF."$userstr\">[Cancel]</a></td>";
			} else {
				echo "<th>---</a></th>";
			}
	//*/
	
			$userstr	=	"?".base64_encode($userinfo."&action=view&order_id=$order_id");
			echo "<td><a href=\"".$PHP_SELF."$userstr\">[View]</a></td>";
			include("rla_fin_find_supid.inc");
			echo "<td>$supstr</td>";
			echo "</tr>";
		}
		echo "</table><p>";
	} else {
		echo "<h2>$first_name</h2>";
		echo "<h4>You don't have any current order.</h4><hr>";
	}
	
//***********Completed Order
	$sql = "SELECT order_id, orderdate FROM rlafinance.orderid 
		WHERE order_by='$email_name' and updatestatus='f' and ordercancelled!='y'
		ORDER BY order_id DESC";// 
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	if ($no) {
		echo "<h2>Completed Order</h2>";	//$first_name
		echo "<table border=1>";
		echo "<tr><th>ID</th><th>Time Ordered</th><th>View</th><th>Supplier</th></tr>";
		while (list($order_id, $orderdate) = mysql_fetch_array($result)) {
			include("rla_fin_order_steps.inc");
			//$prostepsdetails[$steps][$pi]	$status["c"]
			echo "<tr><td>$order_id</td>";					// order id
			echo "<td>$orderdate</td>";	// date ordered ".$prostepsdetails[0][2]."
			$userstr	=	"?".base64_encode($userinfo."&action=view&order_id=$order_id");
			echo "<td><a href=\"".$PHP_SELF."$userstr\">[View]</a></td>";
			include("rla_fin_find_supid.inc");
			echo "<td>$supstr</td>";
			echo "</tr>";
		}
		echo "</table><p>";
	}
	
//***********Cancelled Order
	$sql = "SELECT order_id, orderdate FROM rlafinance.orderid 
		WHERE order_by='$email_name' and ordercancelled='y' 
		ORDER BY order_id DESC";// 
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	if ($no) {
		echo "<h2>Cancelled Order</h2>";	//$first_name
		echo "<table border=1>";
		echo "<tr><th>ID</th><th>Time Ordered</th><th>View</th><th>Supplier</th></tr>";
		while (list($order_id, $orderdate) = mysql_fetch_array($result)) {
			include("rla_fin_order_steps.inc");
			//$prostepsdetails[$steps][$pi]	$status["c"]
			echo "<tr><td>$order_id</td>";					// order id
			echo "<td>$orderdate</td>";	// date ordered ".$prostepsdetails[0][2]."
			$userstr	=	"?".base64_encode($userinfo."&action=view&order_id=$order_id");
			echo "<td><a href=\"".$PHP_SELF."$userstr\">[View]</a></td>";
			include("rla_fin_find_supid.inc");
			echo "<td>$supstr</td>";
			echo "</tr>";
		}
		echo "</table><p>";
	}
echo "<hr><br><a href=#top>Back to top</a><br><br>";
?>
</html>
