<html>

<head>
<LINK REL="StyleSheet" HREF="style.css" TYPE="text/css">
</head>

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
echo "<h2 align=center><a id=top>Change Order Status</a>";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[Refresh]</font></a></h2><hr>";
include("connet_root_once.inc");
//include("rla_fin_orderstats.inc");
//*
	$status["b"] = "Back Order";
	$status["r"] = "Goods Received";
	$status["p"] = "Order Approved";
	//$status["f"] = "Order Closed";
	$status["s"] = "Order Faxed To Supplier";
	$status["m"] = "Order Modified";
	$status["c"] = "Order Cancelled";
	//$status["d"] = "Change P.M.";
	//$status["h"] = "Change Supplier";
	//$status["q"] = "Query Order";
	//$status["n"] = "New Order";
//*/
//################################################
if ($makeststuschangeform) {
	$sql = "SELECT order_by, orderdate, updatestatus
	        FROM rlafinance.orderid 
        WHERE order_id='$rlaorderno';";
    $result = mysql_query($sql);
    include("err_msg.inc");
    list($order_by, $orderdate, $updatestatus) = mysql_fetch_array($result);
	echo "<form name=changeorderstatusform>";
	echo '<input type="hidden" name="frm_str" value= "'.$userstr.'">';
	echo '<input type="hidden" name="thisorderno" value= "'.$rlaorderno.'">';

	//echo "$order_by, $orderdate, $updatestatus<br>";
	echo "<table border=1>";
	echo "<tr><th>Item</th><th>Details</th></tr>";
	echo "<tr><th align=left>Order No</th><td>$rlaorderno</td></tr>";
	echo "<tr><th align=left>Ordered By</th><td>$order_by</td></tr>";
	echo "<tr><th align=left>Date Ordered</th><td>$orderdate</td></tr>";
	echo "<tr><th align=left>Current Status</th><td>".$status[$updatestatus]."</td></tr>";
	echo "<tr><th align=left>New Status</th><td><select name=changeststusto>";
		while (list($key, $val) = each($status)) {
  			echo "<option value=$key>$val";
		}
	echo "</option></select></td></tr>";
	
	echo "<tr><td colspan=2 align=center>";
	echo "<button type=\"submit\" name=changeorderstatus><b>Change Order Status</b></button>";
	echo "</td></tr>";
	echo "</table>";
	echo "</form><p><hr>";
}

//################################################
if ($changeorderstatus) {
	$sql = "UPDATE rlafinance.orderid 
        SET updatestatus='$changeststusto'
        WHERE order_id='$thisorderno';";
    $result = mysql_query($sql);
    include("err_msg.inc");
   
	$stepstr = $changeststusto;
	$order_id = $thisorderno;
	include("rla_fin_order_procedure_update.inc");
	echo "<h2>Order ($thisorderno) status has been changed to ".$status[$changeststusto].".</h2>";
/*
   echo $sql."<br><br>";
    $sql = "SELECT process 
        FROM rlafinance.ordersteps 
        WHERE order_id='$thisorderno';";
    $result = mysql_query($sql);
    include("err_msg.inc");
    list($process) = mysql_fetch_array($result);
	echo "$process<hr>";
//*/
}

//################################################
//List all unclosed order in reverse order
	$fdate =  date ("Y-m-d", mktime(0,0,0,date("m")-2,date("d"),date("Y")));
	$sql = "SELECT order_id, order_by, orderdate
	        FROM rlafinance.orderid 
        WHERE updatestatus<>'f' or (updatestatus='f' and orderdate>'$fdate')
        ORDER BY order_id DESC;";
    //echo "$sql";
    //exit;
    $result = mysql_query($sql);
    include("err_msg.inc");
    $no = mysql_num_rows($result);	
	echo "<form name=preparation>";
	echo '<input type="hidden" name="frm_str" value= "'.$userstr.'">';
	echo "<table border=0>";
	echo "<tr><th align=left>Select an order to change its status ($no)</th>";
	echo "<td><select name=rlaorderno>";
	//echo "<option value=\"\">--- select one ---";
	while (list($order_id, $order_by, $orderdate) = mysql_fetch_array($result)) {
		$tmp = "$order_id $orderdate $order_by";
		if ($rlaorderno== $order_id) {
			echo "<option value=\"$order_id\" selected>$tmp";
		} else {
			echo "<option value=\"$order_id\">$tmp";
		}
	}
	echo "</td></tr><tr><td>&nbsp;</td></tr>";
	
	echo "<tr><td colspan=2 align=center>";
	echo "<button type=\"submit\" name=makeststuschangeform><b>Create Order Status Change Form</b></button>";
	echo "</td></tr>";
	echo "</table>";
	echo "</form>";
	echo "<hr><br><a href=#top>Back to top</a><br><br>";
//*/
?>
</html>
