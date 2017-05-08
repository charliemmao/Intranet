<html>
<?php
	//organise current order details
	$noitemordered = 0;
	$estimate_cost=0;
	for ($i=0; $i<$noitem; $i++) {
		$good1 	= "goods$i";
		$quan1		= "quantity$i";
		$uprice1 	= "unitprice$i";
		$charge1 	= "rlacharge$i";		
		if ($$good1 && $$quan1 && $$uprice1) {
			$item[$noitemordered] 		= $$good1;
			$noquan[$noitemordered]		= $$quan1;
			$tmp = $$good1;
			$gid["$tmp"] = $noitemordered;
			//echo '$gid["'.$tmp.'"] = '.$gid["$tmp"]."<br>";
			$priceunit[$noitemordered]	= $$uprice1;
			$charge[$noitemordered]		= $$charge1;
			if ($charge[$noitemordered] == "saa") {
				$charge[$noitemordered] = $charge[$noitemordered-1];
			}
			$estimate_cost = $estimate_cost + $$quan1*$$uprice1;
			$charge1 = $charge[$noitemordered];
			//echo "goods=".$$good1."; quan=".$$quan1."; uprice=".$$uprice1."; charge=".$charge[$noitemordered]."<br>";
			$noitemordered++;
		}
	}
	//echo "$estimate_cost<br>";
	ksort($gid);
	$ordnew = "";
	while (list($key, $i) = each($gid)) {
		/*
		echo "supid = $supid; goods=".$item[$i]."; quan=".$noquan[$i]."; uprice=".
			$priceunit[$i]."; charge=".$charge[$i]."<br>";
		//*/
		$ordnew = $ordnew."$supid;".$item[$i].";".$noquan[$i].";".	$priceunit[$i].";".$charge[$i]."@";
	}
	
	//check order in the DB
	$sql = "SELECT goods_id, supid as sid, unit, unit_price, rlaprojid 
		FROM rlafinance.orderdetails WHERE order_id='$order_id'
		ORDER BY goods_id ASC;";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$ordold = "";
	while (list($goods_id, $sid, $unit, $unit_price, $rlaprojid) = mysql_fetch_array($result)) {
		/*
		echo "supid = $sid; goods=".$goods_id."; quan=".$unit."; uprice=".
			$unit_price."; charge=".$rlaprojid."<br>";
		//*/
		$ordold = $ordold."$sid;".$goods_id.";".$unit.";".$unit_price.";".$rlaprojid."@";
	}
	
	//echo "$ordnew <br> $ordold <br>";
	if ( $ordnew == $ordold ) {
		echo "<h4><font color=#ff0000>You haven't made any change to your order.</font></h4><hr>";
	} else {
		//place previous order in rlafinance.ordermod
		$moddate = date("Y-m-d");
		$orddetbmod = $ordold;
		$sql = "INSERT INTO rlafinance.ordermod VALUES('$order_id', '$moddate', '$orddetbmod');";
		$result = mysql_query($sql);
		include("err_msg.inc");
		//echo "$sql<br>";
		
		//make an entry in rlafinance.ordersteps
		$stepstr = "m";
		include("rla_fin_order_procedure_update.inc");

		//make an entry in rlafinance.orderid
		if ($modifyapprovedorder != "y") {
			$sql = "UPDATE rlafinance.orderid SET updatestatus='m', 
			estimate_cost='$estimate_cost',
			spec_instruction='$spec_instruction'
	 		WHERE order_id='$order_id'";
			$result = mysql_query($sql);
			include("err_msg.inc");
			//echo "$sql<br>";
		}
		
		//remove order details from rlafinance.orderdetails
		$sql = "DELETE FROM rlafinance.orderdetails WHERE order_id='$order_id'";
		$result = mysql_query($sql);
		include("err_msg.inc");
		
		$reset = reset_autoincrement("rlafinance.orderdetails");
		
		/*
		$sql = "FLUSH TABLES";
		$result = mysql_query($sql);
		include("err_msg.inc");
		$sql = "FLUSH STATUS";
		$result = mysql_query($sql);
		include("err_msg.inc");
		//*/
		
		//enter new order details to rlafinance.orderdetails
		$rlaorderid = $order_id;
		include("rla_fin_rlacode.inc");
		include("rla_fin_onesupgoods.inc");	
		include("rla_fin_orderdetails_entry.inc");
		if ($askforapproval == "y") {
			echo "<h2>Order $order_id has been modified and <br><font color=#0000ff>has sent for approval</font>.</h2><hr>";
		} else {
			echo "<h2>Order $order_id has been modified and <br><font color=#ff0000>has not been sent for approval</font>.</h2><hr>";
		}
	}
?>
</html>
