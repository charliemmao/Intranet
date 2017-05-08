<html>
<body background="rlaemb.JPG" leftmargin="20">
<?php
//js
include('inventory_verify.inc');
include('str_decode_parse.inc');
include("rla_functions.inc");
include("regexp.inc");
include("rla_fin_order.inc");
include("userinfo.inc"); //$userinfo
$userstr	=	"?".base64_encode("$userinfo&action=changesupplier&order_id=$order_id");

if ($viewonly != "true") {
	echo "<h2 align=center><a id=top>Change Supplier</a>";
	echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[Refresh]</font></a></h2>";
} 
echo "<hr>";
include("connet_root_once.inc");
	
	$sql = "SELECT supid, company
        FROM rlafinance.supplierid 
        WHERE telno!='' order by company;";
   $result = mysql_query($sql);
   include("err_msg.inc");
   $i = 0;
   while (list($supid, $company) = mysql_fetch_array($result)) {
        $supnoid[$i] = $supid;
        $suplist[$supid] = $company;
        $i++;
   }

//############# process modified order
if ($changesupplier && ($oldsup != $newsup)) {
	$userstr	=	"/$phpdir/rla_fin_order_mod.php?".base64_encode("$userinfo&action=modify&order_id=$order_id");
	//echo $userinfo;
	//organise current order details
	
	echo "<br>Old Supplier ($oldsup): ".$suplist[$oldsup]."<br>";
	echo "<br>New Supplier ($newsup): ".$suplist[$newsup]."<br>";
	echo "<br><br><b>Processing, please wait......</b><br>";
	flush();
	
	//Copy goods from old supplier to new supplier in table rlafinance.goodsid
	#step 1: find goods from this order
	$sql = "SELECT detailsid, goods_id, unit_price
		 FROM rlafinance.orderdetails 
        WHERE order_id='$order_id' and supid='$oldsup' ";
   $result = mysql_query($sql);
   include("err_msg.inc");

   while (list($detailsid, $goods_id, $unit_price) = mysql_fetch_array($result)) {
		#step 2.1: find goods name in rlafinance.goodsid for oldsup
        $sql21 = "SELECT name, description 
        FROM rlafinance.goodsid 
        WHERE goods_id='$goods_id';";
    	$result21 = mysql_query($sql21);
    	include("err_msg.inc");
    	list($name, $description) = mysql_fetch_array($result21);
		//echo "$detailsid, $name, $description<br>";
		
		#step 2.2: find goods id in rlafinance.goodsid for newsup
        $sql22 = "SELECT goods_id as id 
        FROM rlafinance.goodsid 
        WHERE name='$name' and description='$description' and supid='$newsup';";
    	$result22 = mysql_query($sql22);
    	include("err_msg.inc");
    	list($id) = mysql_fetch_array($result22);

		#step 2.3: decide whether enter goods to rlafinance.goodsid for newsup
		if (!$id) {
			$sql23 = "INSERT INTO rlafinance.goodsid 
        		SET goods_id='null', name='$name', 
            	description='$description', supid='$newsup';";
    		$result23 = mysql_query($sql23);
    		include("err_msg.inc");
    		$id = mysql_insert_id();   		
       }
		//echo "$id = idnew<br>";
		
		#step 2.4: update rlafinance.orderdetails
		$sql24 = "UPDATE rlafinance.orderdetails 
        	SET goods_id='$id', supid='$newsup'
          where detailsid='$detailsid';";
    	$result24 = mysql_query($sql24);
    	include("err_msg.inc");
		//echo "$sql24<br><br>";
   }
	$stepstr = "h";
	include("rla_fin_order_procedure_update.inc");
	
	sleep(5);
	echo "<script language=\"javascript\">";
			echo "window.location=\"http://".getenv("server_name").$userstr."\"";
	echo "</script>";
}
if ($action == "changesupplier") {
	//include("rla_fin_display_order.inc");
	//echo "order_id=$order_id<br>";
	
	$sql = "SELECT supid
        FROM rlafinance.orderdetails
        WHERE order_id='$order_id' limit 1;";
   $result = mysql_query($sql);
   include("err_msg.inc");
   list($supid) = mysql_fetch_array($result);
	
	$frm_str	=	"".base64_encode("$userinfo&action=changesupplier&order_id=$order_id");
	echo "<form>";
	echo "<input type=hidden name=frm_str value=\"$frm_str\">";
	echo "<input type=hidden name=order_id value=\"$order_id\">";
	echo "<input type=hidden name=oldsup value=\"$supid\">";
	echo "<table border=1>";
	echo "<tr><th align=left>Order No</th><td>$order_id</td></tr>";
	echo "<tr><th align=left>Current Supplier</th><td>".$suplist[$supid]."</td></tr>";
	echo "<tr><th align=left>New Supplier</th><td><select name=newsup>";
	for ($j=0; $j<$i; $j++) {
		$k = $supnoid[$j];
		if ($k == $supid) {
			echo "<option value=\"$k\" selected>".$suplist[$k];
		} else {
			echo "<option value=\"$k\">".$suplist[$k];
		}
	}
	echo "</option></select></td></tr>";
	
	echo "<tr><td colspan=2 align=middle>
		<button type=submit name=changesupplier><b>Change</b></button></td></tr>";
	echo "</table>";
	echo "</form>";
}	
/*
for ($i=9; $i<=13; $i++) {
	$sql = "DELETE FROM rlafinance.goodsid 
        WHERE goods_id='$i';";
   $result = mysql_query($sql);
   include("err_msg.inc");
}    
//*/

echo "<hr><br><a href=#top>Back to top</a><br><br>";
?>
</html>
