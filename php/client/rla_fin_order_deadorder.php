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

echo "<h2 align=center><a id=top>Undelivered Order List</a>";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[Refresh]</font></a></h2><hr>";
include("connet_root_once.inc");

//#######################################################################
//***********List All Dead Order
	$norcdtofind = 50;
	$sql = "SELECT count(*) as totalno FROM rlafinance.orderid";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($totalno) = mysql_fetch_array($result);
	//echo "<h2>Total Number of Order is $totalno.</h2>";

	$beforedate = strftime( "%Y-%m-%d", mktime (0,0,0,date("m")-1,date("d"),  date("Y")));	
	$afterdate = strftime( "%Y-%m-%d", mktime (0,0,0,date("m"),date("d"),  date("Y")-1));	
	
	$sql = "SELECT order_id, order_by, orderdate, updatestatus 
		FROM rlafinance.orderid 
		WHERE updatestatus<>'f' and updatestatus<>'q' and updatestatus<>'c' and updatestatus<>'r' and
			(orderdate<'$beforedate' and orderdate>'$afterdate') and ordercancelled='n';";
	#echo "$sql<br>";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	
	include("rla_fin_navbar.inc");

	if ($no) {
		echo "<b>$no order listed here.</b><br><br>";
		echo "<table border=1>";
		echo "<tr><th>ID</th><th>Date</th><th>Order By</th><th>Status</th><th>View</th></tr>";
		while (list($order_id, $order_by, $orderdate, $updatestatus) = mysql_fetch_array($result)) {
			include("rla_fin_order_steps.inc");
			echo "<tr><td>$order_id</td>";
			echo "<td>$orderdate</td>";	
			echo "<td>$order_by</td>";
			//echo "<td>".$status[$prostepsdetails[$steps-1][0]]."</td>";	
			echo "<td>".$status[$updatestatus]."</td>";	
			$userstr	=	"?".base64_encode($userinfo."&action=fullvieworder&order_id=$order_id");
			echo "<td><a href=\"".$PHP_SELF."$userstr\" target=\"_blank\">[VIEW]</a></td>";
			echo "</tr>";
		}
		echo "</table><p>";
	} else {
		echo "<b>No order found.</b><br><br>";
	}

	echo "<br><hr><br><a href=#top>Back to top</a><br><br>";

?>
</html>
