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
echo "<h2 align=center><a id=top>Modify unclosed order</a>";

//############# process modified order
if ($ordermodification) {
	echo "</center></h2><hr>";
	include("rla_fin_process_modified_order.inc");
	exit;
}

if ($action) {
	echo "</h2><hr>";
	$modifyapprovedorder = "y";
	include("rla_fin_display_order.inc");
	echo "<hr>";
	exit;
}

echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[Refresh]</font></a></h2><hr>";
include("connet_root_once.inc");

//#######################################################################
//***********List All Order
	$sql = "SELECT order_id, order_by, orderdate, updatestatus FROM rlafinance.orderid
		WHERE updatestatus!='f'
		ORDER BY order_id DESC";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	if ($no) {
		echo "<h2>RLA Order List ($no)</h2>";
		echo "<table border=1>";
		echo "<tr><th>ID</th><th>Date</th><th>Order By</th><th>Status</th><th>Action</th></tr>";
		while (list($order_id, $order_by, $orderdate, $updatestatus) = mysql_fetch_array($result)) {
			include("rla_fin_order_steps.inc");
			echo "<tr><td>$order_id</td>";
			echo "<td>$orderdate</td>";	
			echo "<td>$order_by</td>";
			//echo "<td>".$status[$prostepsdetails[$steps-1][0]]."</td>";	
			echo "<td>".$status[$updatestatus]."</td>";	
			$userstr	=	"?".base64_encode($userinfo."&action=modify&order_id=$order_id");
			echo "<td><a href=\"".$PHP_SELF."$userstr\" target=\"_blank\">[Modify]</a></td>";// target=\"_blank\"
		echo "</tr>";
		}
		echo "</table><p>";
	}

echo "<hr><br><a href=#top>Back to top</a><br><br>";

?>
</html>
