<html>
<body background="rlaemb.JPG" leftmargin="20">
<?php
//collect data
	$noitemordered = 0;
	$estimate_cost=0;
	for ($i=0; $i<$noitem; $i++) {
		$good 	= "goods$i";
		$quan		= "quantity$i";
		$uprice 	= "unitprice$i";
		$chargerla 	= "rlacharge$i";		
		$chargeghr 	= "ghrcharge$i";
		if ($$good && $$quan && $$uprice) {
			$goodsid[$noitemordered] 		= $$good;
			$noquan[$noitemordered]			= $$quan;
			$priceunit[$noitemordered]		= $$uprice;
			$rlacharge[$noitemordered]		= $$chargerla;
			$ghrcharge[$noitemordered]		= $$chargeghr;
			//echo "goods=".$$good."; quan=".$$quan."; uprice=".$$uprice."; chargerla=".$$chargerla."; chargeghr=".$$chargeghr."<br>";
			$estimate_cost = $estimate_cost + $$quan*$$uprice;
			$noitemordered++;
		}
	}
	$order_by = $email_name;
	$orderdate = date("Y-m-d");
	$updatestatus = "f";
	$ordercancelled = "n";
	//cash=3; cheque=2; credit=1
	$card_cheque = "";
	if ($paymethod == 1) {
		$card_cheque = $card_id;
	} elseif ($paymethod == 2) {
		$card_cheque = $checqueno;
	}

	//$status: n=new;m=modified;c=cancelled;p=approved;s=sent;r=received;v=verified;b=backorder;f=completed
	
//****************insert to table orderid
if (!$modifyclosedorder) {
	//chech whether this order is in the DB
	$sql = "SELECT order_id FROM rlafinance.orderid 
		WHERE order_by='$order_by' and orderdate='$orderdate' and estimate_cost='$estimate_cost' and 
		spec_instruction='$spec_instruction' and updatestatus='$updatestatus' and 
		ordercancelled='$ordercancelled' and paymethod='$paymethod' and 
		card_cheque='$card_cheque' and pay_remarks='$pay_remarks' and 
		invoice_no='$invoice_no' and invoice_date='$invoice_date' and 
		invoice_cost='$invoice_cost' and invoice_gst='$invoice_gst' and 
		delivery_cost='$delivery_cost' and delivery_gst='$delivery_gst';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	if ($no) {
		list($order_id) = mysql_fetch_array($result);
		echo "<h2>The order has been existed in the DB with order ID $order_id. Process cancelled</h2>";
		exit;
	}
} 
//echo "$supid, $noitem modifyclosedorder=$modifyclosedorder estimate_cost=$estimate_cost <br>";

if ($modifyclosedorder) {
	$sql = "UPDATE rlafinance.orderid SET  
		estimate_cost='$estimate_cost', 
		spec_instruction='$spec_instruction', 
		paymethod='$paymethod', 
		card_cheque='$card_cheque', pay_remarks='$pay_remarks', 
		invoice_no='$invoice_no', invoice_date='$invoice_date', 
		invoice_cost='$invoice_cost', invoice_gst='$invoice_gst', 
		delivery_cost='$delivery_cost', delivery_gst='$delivery_gst'
		WHERE order_id='$order_id';";
	//echo "$sql<br>$order_id<br>";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$sql = "DELETE FROM rlafinance.orderdetails 
        WHERE order_id='$order_id'";
	$result = mysql_query($sql);
	include("err_msg.inc");
} else {
	$sql = "INSERT INTO rlafinance.orderid SET order_id='null', 
		order_by='$order_by', orderdate='$orderdate', estimate_cost='$estimate_cost', 
		spec_instruction='$spec_instruction', updatestatus='$updatestatus', 
		ordercancelled='$ordercancelled', paymethod='$paymethod', 
		card_cheque='$card_cheque', pay_remarks='$pay_remarks', 
		invoice_no='$invoice_no', invoice_date='$invoice_date', 
		invoice_cost='$invoice_cost', invoice_gst='$invoice_gst', 
		delivery_cost='$delivery_cost', delivery_gst='$delivery_gst';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$order_id = mysql_insert_id();
	//echo "$sql<br>$order_id<br>";
}

//****************insert to table orderdetails
	$sql = "SELECT details as gst_percent FROM rlafinance.control WHERE controllist='gst'";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($gst_percent) = mysql_fetch_array($result);
	$gst_applicable	=	"y";
	for ($i=0; $i<$noitemordered; $i++) {
		$goods_id		=	$goodsid[$i];
		$unit			=	$noquan[$i];
		$unit_price	=	$priceunit[$i];
		$code_id		=	$ghrcharge[$i];
		$rlaprojid		=	$rlacharge[$i];
		$sql = "INSERT INTO rlafinance.orderdetails SET detailsid='null', 
			order_id='$order_id', goods_id='$goods_id', supid='$supid', unit='$unit', 
			unit_price='$unit_price', gst_applicable='$gst_applicable', 
			gst_percent='$gst_percent', code_id='$code_id', rlaprojid='$rlaprojid';";
		$result = mysql_query($sql);
		include("err_msg.inc");
	}
?>
</html>
