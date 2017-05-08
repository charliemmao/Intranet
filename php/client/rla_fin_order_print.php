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

if ($action == "printorder") {
	//echo "ghr=$ghr<br>";
	include("rla_fin_print_one_order.inc");
	exit;
}
echo "<h2 align=center><a id=top>Print Order</a>";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[Refresh]</font></a></h2><hr>";
include("connet_root_once.inc");

//#######################################################################
//***********List All Order
	$norcdtofind = 50;
	$sql = "SELECT count(*) as totalno FROM rlafinance.orderid";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($totalno) = mysql_fetch_array($result);
	echo "<h2>Total Number of Order is $totalno.</h2>";
	if (!$ordoffset) {
		$ordoffset = 0;
	}

	$sql = "SELECT order_id, order_by, orderdate FROM rlafinance.orderid 
		ORDER BY order_id DESC
		LIMIT $ordoffset, $norcdtofind";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	
	include("rla_fin_navbar.inc");

	if ($no) {
		echo "<b>$no Order are listed here.</b> $navbar<br><br>";
		echo "<table border=1>";
		echo "<tr><th>ID</th><th>Date</th><th>Order By</th><th>Status</th><th>For RLA</th><th>For GHR</th></tr>";
		while (list($order_id, $order_by, $orderdate) = mysql_fetch_array($result)) {
			include("rla_fin_order_steps.inc");
			echo "<tr><td>$order_id</td>";
			echo "<td>$orderdate</td>";	
			echo "<td>$order_by</td>";
			echo "<td>".$status[$prostepsdetails[$steps-1][0]]."</td>";	
			$userstr	=	"?".base64_encode($userinfo."&action=printorder&order_id=$order_id");
			echo "<td><a href=\"".$PHP_SELF."$userstr\" target=\"_blank\">[PRINT]</a></td>";
				
			$userstr	=	"?".base64_encode($userinfo."&action=printorder&order_id=$order_id&ghr=ghr");
			echo "<td><a href=\"".$PHP_SELF."$userstr\" target=\"_blank\">[PRINT]</a></td>";
		echo "</tr>";
		}
		echo "</table><p>";
	}
	echo "<br><br>$navbar<br><br><hr><br><a href=#top>Back to top</a><br><br>";
?>
</html>
