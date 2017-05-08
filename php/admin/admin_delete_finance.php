<html>

<head>
<title>Delete rlafinance Record</title>
</head>

<body bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG">

<?php
include("admin_access.inc");
include('rla_functions.inc');
include("connet_root_once.inc");
echo "<h1	align=center>Delete Finance Record</h1><br>";
echo '<p align=center><a href="'.$PHP_SELF.$admininfo.'">[Refresh]</a>
	<a href="/'.$phpdir.'/adminctl_top.php'.$admininfo.'">[Admin Main Page]</a><hr>';
?>	
<p><table>
<form method="POST" target="main" action= "<?php echo $PHP_SELF ?>">
	 <tr><td colspan=3><b>Delete rlafinance Record(s)</b></tr>
    <tr><td><b>Order ID</b></td><td><input type=text name="delidrange" 
    value="<?php echo $delidrange ?>" size=10> For a range, separated two numbers by "-".</td>
    <td><input type="submit" value="GO" name="delonerecord"></td></tr>
</form>

<tr><td colspan=3><HR></TD></tr>

<tr><td colspan=3><form method="POST" target="main" action= "<?php echo $PHP_SELF ?>">
    <tr><td colspan=3><b>Delete All Order Records</font></b></td>
    <tr><td><b>Password</b></td>
    <td><input type="password" name="password" size="20"></td>
    <td><input type="submit" value="GO" name="del_all_fin_rcd"></td></tr>
</form>

</table><hr>

<?php
include("connet_root_once.inc");
$sql = "SELECT details as deletercd FROM rlafinance.control WHERE controllist='deletercd'";
$result = mysql_query($sql);
include("err_msg.inc");
list($deletercd) = mysql_fetch_array($result);
echo "<h3>Delete Record? $deletercd.</h3>";
if ($deletercd == "n") {
	echo "<h3><font color=#ff0000>If you really want to delete record, please go to admin page to change control setting.</font></h3>";
	exit;
}
if ($del_all_fin_rcd) {
	include("connet_root_once.inc");
	//password
	$out = "";
	$sql	=	"SELECT email_name FROM logging.accesslevel WHERE priviledge='00';";
	include('general_one_val_search.inc');
	include("connet_root_once.inc");
	if ($password	== $out.'00') {
//############## delete all record
		$sql = "DELETE FROM rlafinance.orderid";
		$result = mysql_query($sql);
		include("err_msg.inc");
		echo "$sql<br>";
		
		$sql = "DELETE FROM rlafinance.orderdetails ";
		$result = mysql_query($sql);
		include("err_msg.inc");
		echo "$sql<br>";
		
		$sql = "DELETE FROM rlafinance.ordersteps ";
		$result = mysql_query($sql);
		include("err_msg.inc");
		echo "$sql<br>";
		
		$sql = "DELETE FROM rlafinance.ordermod";
		$result = mysql_query($sql);
		include("err_msg.inc");
		echo "$sql<br>";
		
		$sql = "DELETE FROM rlafinance.backorder";
		$result = mysql_query($sql);
		include("err_msg.inc");
		echo "$sql<br>";
		
		$sql = "DELETE FROM rlafinance.queryorder"; 
		$result = mysql_query($sql);
		include("err_msg.inc");
		echo "$sql<br>";
	} else {
		echo "<h2>Please check your password.</h2><hr>";
	}
} elseif ($delonerecord) {
	$tmp = explode("-", $delidrange);
	$id1 = $tmp[0];
	if (count($tmp) == 1) {
		echo "<b>Delete order id ".$tmp[0].".</b><br>";
		$id2 = $id1;
	} else {
		echo "<b>Delete order id from ".$tmp[0]." to ".$tmp[1].".</b><br>";
		$id2 = $tmp[1];
	}
	
//############## delete one record
	$sql = "DELETE FROM rlafinance.orderid WHERE order_id>='$id1' and order_id<='$id2'";
	$result = mysql_query($sql);
	include("err_msg.inc");
	echo "$sql<br>";
		
	$sql = "DELETE FROM rlafinance.orderdetails WHERE order_id>='$id1' and order_id<='$id2'";
	$result = mysql_query($sql);
	include("err_msg.inc");
	echo "$sql<br>";
		
	$sql = "DELETE FROM rlafinance.ordersteps WHERE order_id>='$id1' and order_id<='$id2'";
	$result = mysql_query($sql);
	include("err_msg.inc");
	echo "$sql<br>";
		
	$sql = "DELETE FROM rlafinance.ordermod WHERE order_id>='$id1' and order_id<='$id2'";
	$result = mysql_query($sql);
	include("err_msg.inc");
	echo "$sql<br>";
		
	$sql = "DELETE FROM rlafinance.backorder WHERE order_id>='$id1' and order_id<='$id2'";
	$result = mysql_query($sql);
	include("err_msg.inc");
	echo "$sql<br>";
	
	$sql = "DELETE FROM rlafinance.queryorder WHERE order_id>='$id1' and order_id<='$id2'"; 
	$result = mysql_query($sql);
	include("err_msg.inc");
	echo "$sql<br>";
}
?>

</body>
</html>
