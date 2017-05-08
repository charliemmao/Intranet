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
echo "<h2 align=center><a id=top>Order Approval</a>";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[New Order]</font></a>";
$userstr	=	"?".base64_encode($userinfo."&approvedorder=true");
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[Approved Order]</font></a>";
echo "</h2><hr>";
include("connet_root_once.inc");

//#######################################################################
//***********List All Order
	if ($approvedorder == "true") {
		$sql = "SELECT order_id, order_by, orderdate FROM rlafinance.orderid 
		where updatestatus='p' or updatestatus='q' or updatestatus='s'
		ORDER BY order_id DESC";
	} else {
		$sql = "SELECT order_id, order_by, orderdate FROM rlafinance.orderid 
		where updatestatus='n' or updatestatus='m'
		ORDER BY order_id DESC";
	}
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	if ($no) {
		echo "<h2>RLA Order List ($no)</h2>";
		echo "<table border=1>";
		echo "<tr><th>ID</th><th>Date</th><th>Order By</th><th>Status</th><th>Action</th></tr>";
		while (list($order_id, $order_by, $orderdate) = mysql_fetch_array($result)) {
			$order_by = trim($order_by);
			include("rla_fin_order_steps.inc");
			echo "<tr><td>$order_id</td>";
			echo "<td>$orderdate</td>";	
			echo "<td>$order_by</td>";
			$sql0 = "SELECT first_name as fna, middle_name as mna, last_name as lna
				FROM timesheet.employee WHERE email_name='$order_by'";
			//echo "$sql0 <br><br>";
			$result0 = mysql_query($sql0);
			include("err_msg.inc");
			list($fna, $mna, $lna) = mysql_fetch_array($result0);
			$info	=	"&order_id=$order_id&rlaorderid=$order_id&action=aproval&frommail=$order_by".
				"&frofn=$fna&fromln=$lna"; //&time=$orderdate
			//echo "info: $info<br>";
			echo "<td>".$status[$prostepsdetails[$steps-1][0]]."</td>";	
			$userstr	=	"?".base64_encode($userinfo.$info);
			echo "<td><a href=\"rla_fin_order_approve.php$userstr\" target=\"_blank\">[Approve]</a></td>";
			echo "</tr>";
		}
		echo "</table><p>";
	}


echo "<hr><br><a href=#top>Back to top</a><br><br>";
?>
</html>
