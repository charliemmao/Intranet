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

include("userinfo.inc"); //$userinfo
$userstr	=	"?".base64_encode($userinfo);
include("connet_root_once.inc");
echo "<h2 align=center><a id=top>Modify Payment Method For Approved Order";
echo "</a><a href=\"".$PHP_SELF."$userstr\"><font size=2>[Refresh]</font></a></h2><hr>";

if ($updatepaymethod) {
	$stepstr = "d";
	include("rla_fin_order_procedure_update.inc");

	//update rlafinance.orderid	
	if ($paymethod == 1) {
		$dataentry = $card_id;
	} elseif ($paymethod == 2) {
		$dataentry = $chequeno;
	} else {
		$dataentry = "";
	}
	$sql = "UPDATE rlafinance.orderid 
			SET paymethod='$paymethod', card_cheque='$dataentry'
	 		WHERE order_id='$order_id'";
	$result = mysql_query($sql);
	include("err_msg.inc");
	echo "<h3>Payment method has been changed.</h3><hr>";
	$action = "modpaymethod";
}

if ($action == "modpaymethod") {
	$action = "view";
	$noshowview = 1;
	include("rla_fin_display_order.inc");
	echo "<form method=post>";
	echo "<table>";
	echo '<input type="hidden" name="frm_str" value= "'.$userstr.'">';
	echo '<input type="hidden" name="processby" value= "'.$OrderOfficer[0][0].'">';

	$cspan = 1;
	$newline="<br>";
	$fnts = "<font size=2>";
	include("rla_fin_paymethod.inc");
	echo "<tr><th align=right>$fnts"."Cheque No</font></th>";
	if ($paymethod != 2) {
		$card_cheque = "";
	}
	echo "<td><input type=text name=chequeno value=\"$card_cheque\" size=20></td></tr>";	
	echo "<tr><td colspan=2>&nbsp;</td></tr>";
	echo "<tr><td colspan=2 align=center>";
	echo "<button type=\"submit\" name=updatepaymethod ";
	echo "onclick=\"return chequeconfirm();\" ";
	echo "><b>Submit</b></button></td></tr>";		
	echo "</table>";
	echo "</form>";
	exit;
}

//#######################################################################
//***********List Approved Order but not closed
	$sql = "SELECT order_id, order_by, orderdate, updatestatus FROM rlafinance.orderid
		WHERE updatestatus='s' ||  updatestatus='p'
		ORDER BY order_id DESC";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	if ($no) {
		echo "<h2>RLA Order List ($no)</h2>";
		echo "<table border=1>";
		echo "<tr><th>ID</th><th>Date</th><th>Order By</th><th>Status</th><th>Action</th></tr>";
		while (list($order_id, $order_by, $orderdate, $updatestatus) = mysql_fetch_array($result)) {
			include("rla_fin_order_steps.inc");
			echo "<tr><td>$order_id</td>";
			echo "<td>$orderdate</td>";	
			echo "<td>$order_by</td>";
			//echo "<td>".$status[$prostepsdetails[$steps-1][0]]."</td>";	
			echo "<td>".$status[$updatestatus]."</td>";	
			$userstr	=	"?".base64_encode($userinfo."&action=modpaymethod&order_id=$order_id");
			echo "<td><a href=\"".$PHP_SELF."$userstr\" >[Modify]</a></td>";// target=\"_blank\"
		echo "</tr>";
		}
		echo "</table><p>";
	}

echo "<hr><br><a href=#top>Back to top</a><br><br>";

?>
</html>
