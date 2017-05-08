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
echo "<h2 align=center><a id=top>Invoice Entry</a>";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[Refresh]</font></a></h2><hr>";
include("connet_root_once.inc");

//process invoice entry
if ($invoiceentry) {
	//echo "$order_id, $invoice_no, $invoice_date, $invoice_cost, $invoice_gst, $delivery_cost, $delivery_gst";
	//$status: n=new;m=modified;c=cancelled;p=approved;s=sent;q=query;r=received;b=backorder;f=completed
	//update rlafinance.ordersteps
	$stepstr = "f";
	include("rla_fin_order_procedure_update.inc");

	//update rlafinance.orderid
	$sql = "UPDATE rlafinance.orderid SET 
		updatestatus='$stepstr',
		invoice_no='$invoice_no',
		invoice_date='$invoice_date',
		invoice_cost='$invoice_cost',
		invoice_gst='$invoice_gst',
		delivery_cost='$delivery_cost',
		delivery_gst='$delivery_gst'
		WHERE order_id='$order_id';";
	//echo "$sql<br>";
	$result = mysql_query($sql);
	include("err_msg.inc");
	echo "<h2>Order $order_id invoice entry has been completed and the order closed.</h2>";
	exit;
}

if ($action == "invoice") {
	include("rla_fin_display_order.inc");
	exit;
}
if ($action == "pleaseverify") {
	$sql = "SELECT updatestatus FROM rlafinance.orderid WHERE order_id='$order_id'";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($updatestatus) = mysql_fetch_array($result);
	if ($updatestatus == "b") {
		$bo = "back ";
	}
	//send mail message to order initiator
	//action=pleaseverify&order_id=$order_id&order_by=$order_by
	$sql = "SELECT email_name as en, title as t, first_name as fn, middle_name as mn, last_name as ln
		FROM timesheet.employee WHERE email_name='$order_by'";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($en, $t, $fn, $mn, $ln) = mysql_fetch_array($result);
	$to = "$en@$domain";
	$mfrm = "$email_name@$domain";
	$extra		= "From: $email_name@$domain\nReply-To: $email_name@$domain\nX-Mailer: $PHPconst/".phpversion();
	$subject 	= "RE: Request for Order Receiving Confirmation(Order ID: $order_id)";
	$message 	= "Dear $fn\n\n".
		"Your $bo"."order ($company Internal Order No: $order_id) ".
		"has been received according to our record. ".
		"Please verify your order from Intranet so I can process Invoice and close your order.\n\n".
		"Regards\n\n".
		"$first_name $last_name\n".date("G:i F d, Y");
	if ($sendmail == "y" && ($to != $mfrm)) {
		//mail ($to, $subject, $message, $extra);

		$from = "$email_name\@rla.com.au";
		$to = "$en\@$domain";
		$cc = "";
		$msg = "$message";
		system("./_mail.pl \"$from\" \"$to\" \"$cc\" \"$subject\" \"$msg\"",$var);
		
		$mentry = ordermailentry("$order_id", "$email_name","$to", 7);
	}
	$message = ereg_replace("\n", "<br>", $message);
	//echo "$to<br>$subject<br>$message<br>$extra<br>";
	echo "<h2> Mail has been sent to $fn $ln asking for order receiving confirmation.</h2>";
	exit;
}

//#######################################################################
//***********List All Order
	$sql = "SELECT order_id, order_by, orderdate, updatestatus FROM rlafinance.orderid 
		WHERE updatestatus='r' or updatestatus='s' or updatestatus='b'
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
			echo "<td align=middle>$order_by</td>";
			//echo "<td>".$status[$prostepsdetails[$steps-1][0]]."</td>";
			echo "<td>".$status[$updatestatus]."</td>";
			if ($updatestatus == 'r') {
				$userstr	=	"?".base64_encode($userinfo."&action=invoice&order_id=$order_id&order_by=$order_by");
				echo "<th><a href=\"".$PHP_SELF."$userstr\" target=\"_blank\">[Invoice Entry]</a></th>";
			} elseif ($updatestatus == 's' || $updatestatus == 'b') {
				$userstr	=	"?".base64_encode($userinfo."&action=pleaseverify&order_id=$order_id&order_by=$order_by");
				echo "<th><a href=\"rla_fin_order_verify.php$userstr\">[Receive]</a></th>";
			}
			echo "</tr>";
		}
		echo "</table><p>";
	}

echo "<hr><br><a href=#top>Back to top</a><br><br>";

?>
</html>
