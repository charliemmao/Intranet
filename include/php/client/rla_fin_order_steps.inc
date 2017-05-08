<html>
<?php
	include("rla_fin_orderstats.inc");
/*
	$status["b"] = "Back Order";
	$status["c"] = "Order Cancelled";
	$status["d"] = "Change P.M.";
	$status["h"] = "Change Supplier";
	$status["f"] = "Order Closed";
	$status["m"] = "Order Modified";
	$status["n"] = "New Order";
	$status["p"] = "Order Approved";
	$status["q"] = "Query Order";
	$status["r"] = "Goods Received";
	$status["s"] = "Order Faxed To Supplier";
//*/
	$authemail = "";
	$sql = "SELECT process FROM rlafinance.ordersteps 
		WHERE order_id='$order_id'";
	$result0 = mysql_query($sql);
	include("err_msg.inc");
	list($process) = mysql_fetch_array($result0);
	$prosteps = explode("@",$process);
	$steps = count($prosteps);
	//echo "<br><b>$steps; $process</b><br>";
 	for ($p=0; $p<$steps; $p++) {
 		//echo $prosteps[$p]."<br>";
		$prostepsdetails[$p] = explode(";",$prosteps[$p]);
		if ($prostepsdetails[$p][0] == "p") {
			$authemail = $prostepsdetails[$p][1];
		}
		/*
		for ($pi=0; $pi<count($prostepsdetails[$p]); $pi++) {
			echo $prostepsdetails[$p][$pi]."<br>";
		}
		//*/
 	}
?>
</html>
