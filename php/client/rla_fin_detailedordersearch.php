<html>
<body background="rlaemb.JPG" leftmargin="20">
<?php
//js
include('str_decode_parse.inc');
include("rla_functions.inc");

include("userinfo.inc"); //$userinfo
echo "<h2 align=center><a id=top>Goods Ordered For Project $brief_code</a></h2>";
if ($timeframe == "y") {	//search for all time
	echo "<h4 align=center>(From $datefrom to $dateto)</h4>";
}
echo "<hr>";
include("connet_other_once.inc");
//$extra = "&timeframe=$timeframe&datefrom=$datefrom&dateto=$dateto&rlaprojid=$rlaprojid&brief_code=$brief_code";
//$extra = $extra."&totcostreceived=$totcostreceived&totcostordered=$totcostordered";

//obtain goods list
$sql = "SELECT goods_id, name, description 
        FROM rlafinance.goodsid 
        ORDER BY name;";
$result = mysql_query($sql);
include("err_msg.inc");
while (list($goods_id, $name, $description) = mysql_fetch_array($result)) {
	$glist[$goods_id] = "$name-$description";
}

//obtain supplier list
$sql = "SELECT supid, company 
        FROM rlafinance.supplierid 
        ORDER BY company;";
$result = mysql_query($sql);
include("err_msg.inc");
while (list($supid, $company) = mysql_fetch_array($result)) {
	$splist[$supid] = $company;
}

$c = " align=middle";
$r = " align=right";

echo "<p><table border=1>";
echo "<tr><th>No</th><th>Goods</th><th>Unit</th><th>Price</th><th>Cost</th>
	<th>Date</th><th>Order By</th><th>Order No</th><th>Status*</th><th>Supplier</th></tr>";    		
		//echo "$rlaprojid $brief_code<br>";
		$sql = "SELECT t1.order_id as order_id,
						t1.goods_id as goods_id,
						t1.supid as supid,
						t1.unit as unit,
						t1.unit_price as unit_price,						
						t1.unit*t1.unit_price as cost,
						t1.checkin as checkin,
						t2.order_by as order_by,
						t2.orderdate as orderdate,
						t2.updatestatus as updatestatus
        		FROM rlafinance.orderdetails as t1, rlafinance.orderid as t2
        		WHERE t2.ordercancelled='n' and t1.rlaprojid='$rlaprojid' and 
        				t1.order_id=t2.order_id";	
		if ($timeframe == "y") {	//search for all time
        	$sql .= " and t2.orderdate>='$datefrom' and t2.orderdate<='$dateto'";//$datefrom to $dateto
   		}
   		$sql .= " ORDER BY order_id DESC";
       //echo "$sql<br>";

    	$result = mysql_query($sql);
    	include("err_msg.inc");
    	$i=1;
    	$totcost = 0;
    	while (list($order_id, $goods_id, $supid, $unit, $unit_price, $cost, 
    		$checkin, $order_by, $orderdate, $updatestatus) = mysql_fetch_array($result)) {
    		//echo "$order_id, $goods_id, $supid, $unit, $unit_price, $cost, 
    		//$checkin, $order_by, $orderdate, $updatestatus<br>";
    		$Goods	= $glist[$goods_id];
    		$totcost += $cost;
    		$Supplier = $splist[$supid];
    		if ($updatestatus != "f") {
    			$cost = "<font color=#ff0000>$cost</font>";
    			$updatestatus = "<font color=#ff0000>$updatestatus</font>";
    		}
			echo "<tr><td>$i</td><td>$Goods</td><td$c>$unit</td><td$r>$unit_price</td><td$r>$cost</td>
				<td$c width=100>$orderdate</td><td$c>$order_by</td><td$c>$order_id</td>
				<td$c>$updatestatus</td><td>$Supplier</td></tr>";
			$i++;
    	}
		echo "</table><p>";
		echo "<p><h3>Total Cost: $totcost</h3>";
		
		echo "<p><b>Note:</b><br>";
		$sql = "SELECT details 
        	FROM rlafinance.control 
        	WHERE controllist='status';";
		$result = mysql_query($sql);
		include("err_msg.inc");
		list ($details) = mysql_fetch_array($result);
		$tmp = explode(";", $details);
		for ($i=0; $i<count($tmp); $i++) {
			echo "&nbsp;&nbsp;&nbsp;&nbsp;".$tmp[$i]."<br>";			 
		}
?>
<p><hr><br><a href=#top><b>Back to top</b></a><br><br>
</html>
