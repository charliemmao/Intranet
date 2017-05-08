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
echo "<h2 align=center><a id=top>Modify After Order Closed</a>";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[Refresh]</font></a></h2><hr>";
include("connet_root_once.inc");


//################################################
if ($createmodorderform) {
	$order_id = $rlaorderno;
	include("rla_fin_display_order.inc");
	$noitem = $noitemordered + 1;
	$supid = $orddet[0][1]; 

	include("rla_fin_onesupgoods.inc");
	include("rla_fin_rlacode.inc");
	include("rla_fin_ghrcode.inc");
	include("rla_fin_onestep_genform.inc");
}

//################################################
//process new order
if ($neworder) {
//#################
	include("rla_fin_onestep_dataprocess.inc");

//rlafinance.ordersteps new/modify and completed	
	$tmp = date("Y-m-d H:i");
	if (!$modifyclosedorder) {
		$process = "n;$email_name;$tmp";
		$sql = "INSERT INTO rlafinance.ordersteps SET order_id='$order_id', process='$process';";
		$result = mysql_query($sql);
		//echo "$sql<br><br>";	
		include("err_msg.inc");
		$stepstr = "f";
		include("rla_fin_order_procedure_update.inc");
		//feedback
		echo "<h2>Order completed.</h2>";
	} else {
		$stepstr = "m";
		include("rla_fin_order_procedure_update.inc");
		echo "<h2>Order modified.</h2>";
	}
	
	echo "<hr>";
}

//################################################
//List all closed order in reverse order
	$sql = "SELECT order_id, order_by, orderdate
	        FROM rlafinance.orderid 
        WHERE updatestatus='f'
        ORDER BY order_id DESC;";
    $result = mysql_query($sql);
    include("err_msg.inc");
    $no = mysql_num_rows($result);	
	echo "<form name=preparation>";
	echo '<input type="hidden" name="frm_str" value= "'.$userstr.'">';
	echo "<table border=0>";
	echo "<tr><th align=left>Select an order to modify ($no)</th>";
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
	echo "<button type=\"submit\" name=createmodorderform><b>Modify This Order</b></button>";
	echo "</td></tr>";
	echo "</table>";
	echo "</form>";
	echo "<hr><br><a href=#top>Back to top</a><br><br>";
//*/
?>
</html>
