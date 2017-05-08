<html>
<body background="rlaemb.JPG" leftmargin="20">
<?php
//js
include('inventory_verify.inc');
include('str_decode_parse.inc');
include("rla_functions.inc");
include("userinfo.inc"); //$userinfo
include("regexp.inc");
include("rla_fin_order.inc");
include("rla_fin_controldata.inc");

if ($mystat == "auth" || $mystat == "exec" || $mystat == "poff") { // 
} else {
	exit;
}
$userstr	=	"?".base64_encode($userinfo.
	"&rlaorderid=$rlaorderid&frommail=$frommail&frofn=$frofn&fromln=$fromln");
$order_id = $rlaorderid;
//$status: n=new;m=modified;c=cancelled;p=approved;s=sent;r=received;v=verified;b=backorder;f=completed

//processing order
if ($enterchequeno && $processby) {
	//update rlafinance.ordersteps
	//$status: n=new;m=modified;c=cancelled;p=approved;s=sent;q=query;r=received;b=backorder;
	//f=completed
	$stepstr = "s";
	$email_name = $processby;
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
			SET paymethod='$paymethod', card_cheque='$dataentry', updatestatus='$stepstr'
	 		WHERE order_id='$order_id'";
	$result = mysql_query($sql);
	include("err_msg.inc");
	//echo "$sql<br>";
	//echo "order_id = $order_id<br>processby = $processby<br>";
	//echo "paymethod=$paymethod<br>card_id=$card_id<br>chequeno=$chequeno";
	
	//mail to person who initiate the order 
	$from = $OrderOfficer[0][0];
	$mfrm = "$from@$domain";
	$extra		= "From: $from@$domain\nReply-To: $from@$domain\nX-Mailer: $PHPconst/".phpversion();
	$to 		= "$frommail@$domain";
	$subject 	= "RE: $company Order $rlaorderid has been faxed to supplier";
	$sername = getenv("server_name");
	$d = date("G:i F d, Y");
	$message 	= "Dear $frofn\n\n".
		"Your order on the Intranet ($company Internal Order No: $rlaorderid) ".
		"has been faxed to supplier.\n\n".
		"Regards\n\n".$OrderOfficer[0][1].
		"\n$d";
	if ($sendmail == "y" && $mailtoini == "yes" && ($to != $mfrm)) {
		//mail ($to, $subject, $message, $extra);
		
		$from = "$from\@rla.com.au";
		$to = "$frommail\@$domain";
		$cc = "";
		$msg = "$message";
		system("./_mail.pl \"$from\" \"$to\" \"$cc\" \"$subject\" \"$msg\"",$var);

		$mentry = ordermailentry("$rlaorderid", "$from","$to", 4);
	}
	$message = ereg_replace("\n", "<br>", $message);
	//echo "To: $to<br>Subjuct: $subject<br>Message: $message<br>";
}

//check payment method if pay by cheque present a page to enter cheque no.
$sql = "SELECT paymethod, card_cheque, updatestatus
	FROM rlafinance.orderid WHERE order_id='$order_id';";
$result = mysql_query($sql);
include("err_msg.inc");
list($paymethod, $card_cheque, $updatestatus) = mysql_fetch_array($result);
//echo "$paymethod, $card_cheque, $updatestatus<br>";
//cash=3; cheque=2; credit=1
if ($updatestatus == "p") {
	echo "<h2 align=center><a id=top>Processing Order</a>";
	echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[Refresh]</font></a></h2><hr>";
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
	echo "<td><input type=text name=chequeno size=20></td></tr>";
	/*
	if ($paymethod == 2 && !$card_cheque) {//cheque payemtn but no cheque no entered
		echo "<tr><th align=left>Payment Method</th>";
		echo "<th align=left>";
		//$sql = "SELECT method_id, description FROM rlafinance.paymethod order by method_id";
		$sql = "SELECT method_id, description FROM rlafinance.paymethod order by description";
		$result = mysql_query($sql);
		include("err_msg.inc");
		$no = mysql_num_rows($result);
		while (list($method_id, $description) = mysql_fetch_array($result)) {
			$description = ucwords($description);
			if ($paymethod == $method_id) {
				echo "$description";
				break;
			}
		}
		echo "</th></tr>";
		echo "<tr><th align=left>Cheque No</th>";
		echo "<td><input type=text name=chequeno size=20></td></tr>";
	}
	//*/
	
	echo "<tr><th align=left>Processed by</th><th align=left>".$OrderOfficer[0][1]."<th></tr>";
	echo "<tr><th align=left>Send mail to $frofn</th>";
	echo "<td><select name=mailtoini>
		<option>yes
		<option>no
		</option></select>
		</td></tr>";
	echo "<tr><td colspan=2>&nbsp;</td></tr>";
	echo "<tr><td colspan=2 align=center>";
	//echo "<input type=\"submit\" name=enterchequeno value=\"Submit\" ";
	//echo "onclick=\"return chequeconfirm();\" ";
	echo "<button type=\"submit\" name=enterchequeno ";
	echo "onclick=\"return chequeconfirm();\" ";
	echo "><b>Submit</b></button></td></tr>";	
		
	echo "</table>";
	echo "</form>";
} elseif ($updatestatus == "s") {
	include("rla_fin_print_one_order.inc");
}
//echo "&rlaorderid=$rlaorderid&frommail=$frommail&frofn=$frofn&fromln=$fromln";
?>
</html>
