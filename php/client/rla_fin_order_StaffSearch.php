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
include("rla_fin_orderstats.inc");

include("userinfo.inc"); //$userinfo
$userstr	=	"?".base64_encode($userinfo);

if ($action == "fullvieworder") {
	include("rla_fin_display_order.inc");
	exit;
}

echo "<h2 align=center><a id=top>Search Order by Staff</a>";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[Refresh]</font></a></h2><hr>";
include("connet_root_once.inc");

//#######################################################################
//***********Search Order Form
	echo "<form name=searchform method=post>";
	echo "<table>";
	/* table: goodsid. columns: goods_id, name, description, supid, product_code,  */
	
    $sql = "SELECT DISTINCT order_by oby
        FROM rlafinance.orderid
        ORDER BY order_by";
	$result = mysql_query($sql);
	include("err_msg.inc");
	
	echo "<tr><th align=left>Select Staff</th>";
	echo "<td><select name=staffemailname>";
	$i = 0;
	while (list($obye) = mysql_fetch_array($result)) {
		if ($staffemailname == $obye) {
			echo "<option selected>$obye";
		} else {
			echo "<option >$obye";
		}
		$i++;
	}
	echo "</option></select></td></tr>";			
	echo "<tr><th colspan=2><button type=\"submit\" name=searchorder><b>Search</b></button></td></tr>";
	echo "</table><p>";

if ($searchorder) {
	echo "<hr><h2>Following orders are placed by <font color=#0000ff>$staffemailname</font></h2>";
	if ($priv == "00") {
		//echo "$staffemailname<br><br>";
	}
	echo "<table border=1>";
	echo "<tr><th>No</th><th>Order Date</th><th>Status</th><th>Cost</th><th>Order No</th><th>Company</th></tr>";
			$sql = "SELECT DISTINCT t1.order_id, t1.orderdate, t1.estimate_cost, t1.updatestatus, t2.company
        		FROM rlafinance.orderid as t1, rlafinance.supplierid as t2, rlafinance.orderdetails as t3
        		WHERE  t1.order_by='$staffemailname' and t1.updatestatus!='c' and
        				t3.order_id=t1.order_id and t2.supid=t3.supid
        		ORDER BY order_id DESC;";
            
			$result = mysql_query($sql);
			include("err_msg.inc");
			$nofound = 0;
			$totalCost=0;
			while (list($order_id, $orderdate, $estimate_cost, $updatestatus, $company) = mysql_fetch_array($result)) {
				$nofound++;
				$totalCost += $estimate_cost;
				$userstr	=	"?".base64_encode($userinfo."&action=fullvieworder&order_id=$order_id");
				echo "<tr><td>$nofound</td><td>$orderdate</td>
				<td>".$status[$updatestatus]."</td><td align=right>$estimate_cost</td>
				<td align=middle><a href=\"rla_fin_order_fullview.php$userstr\" target=\"_blank\">$order_id</a></td>
				<td>$company</td></tr>";
			}
			echo "<h3>Total number of orders are <font color=#0000ff>$nofound</font>.<br>
				Valued at <font color=#0000ff>\$$totalCost</font>.</h3>";
	echo "</table><p>";
}

echo "<hr><br><a href=#top>Back to top</a><br><br>";
?>
</html>
