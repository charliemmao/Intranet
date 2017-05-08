<html>
<?php
	$sql = "SELECT process FROM rlafinance.ordersteps 
		WHERE order_id='$order_id'";
	$result = mysql_query($sql);
	//echo "$sql<br><br>";	
	include("err_msg.inc");
	list($process) = mysql_fetch_array($result);
		 
	$tmp = date("Y-m-d H:i");
	$process ="$process@$stepstr;$email_name;$tmp";
	$sql = "UPDATE rlafinance.ordersteps SET process='$process'
		where order_id='$order_id';";
	$result = mysql_query($sql);
	//echo "$sql<br><br>";	
	include("err_msg.inc");
?>
</html>