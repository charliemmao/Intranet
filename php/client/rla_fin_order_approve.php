<html>
<body background="rlaemb.JPG" leftmargin="20">
<?php
//js
include('str_decode_parse.inc');
include("rla_functions.inc");
include("regexp.inc");
include("rla_fin_order.inc");
include("userinfo.inc"); //$userinfo
include('connet_root_once.inc');
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

##pass client info to next page
$mystat = "general";	
// get all info for ordering and finance control
include("rla_fin_controldata.inc");
if (!$email_name && $time) {
	$mystat = "auth"; //open page from email
}

if ($mystat == "general" ||  $mystat == "exec" ||  $mystat == "poff") {
	exit;
}
if ($mystat == "auth") { 
	//echo "mystat = $mystat<br>";
}

//echo "time=$time <br>email_name=$email_name<br>rlaorderid=$rlaorderid<br>";
//exit;

$userstr	=	"?".base64_encode($userinfo."&mystat=$mystat".
	"&rlaorderid=$rlaorderid&action=$action&frommail=$frommail&frofn=$frofn&fromln=$fromln&time=$time");
echo '<input type="hidden" name="frm_str" value= "'.$userstr.'">';
echo "<h2 align=center><a id=top>Order Approval</a>";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[Refresh]</font></a>";
echo "</h2><hr>";
include("connet_root_once.inc");
//echo "rlaorderid=$rlaorderid&action=$action&frommail=$frommail&frofn=$frofn&fromln=$fromln&time=$time";

if ($orderquery) {
	if ($supplier) {
		$message = $message ."Please change supplier to $supplier.\n\n";
	}
	
	$person = explode("@",$authperson);
	for ($i=0; $i<$noitemordered; $i++) {
		$ordercancel	= "ordercancel$i";
		$ckCancel		= "ckCancel$i";
		$msg = "";
		if ($$ckCancel) {
			$msg = $msg .$$ordercancel."\n";
		}
	}
	if ($msg) {
		$message = $message."Please remove following item(s) from your order ($order_id):\n$msg\n";
	}
	
	if ($remarks) {
		$message = $message."$remarks\n";
	}
/*
	echo "ordercancel0 = $ordercancel0<br>";
	echo "ckCancel0= $ckCancel0<br>";
	echo "noitemordered =$noitemordered<br>";
	echo "supplier =$supplier<br>";
	echo "$order_id, $order_by, $fname<br>";
	echo "ename=".$person[0]."; fname=".$person[1]."<br>";
//*/
	if ($message) {
		//mail to person who initiate the order 
		$from = $person[0];
		$mfrm = "$from@$domain";
		$extra		= "From: $from@$domain\nReply-To: $from@$domain\nX-Mailer: $PHPconst/".phpversion();
		$to 		= "$order_by@$domain";
		$subject 	= "RE: Query to $company Order $order_id";
		$sername = getenv("server_name");
		$d = date("G:i F d, Y");
		$message 	= "Dear $fname\n\n$message\n".
			"Regards\n\n".$person[1].
			"\n$d";
		if ($to != $mfrm) {
			//mail ($to, $subject, $message , $extra);
			$from = "$from\@$domain";			
			$to = "$order_by@$domain";
			$cc = "";
			$msg = $message;
			system("./_mail.pl \"$from\" \"$to\" \"$cc\" \"$subject\" \"$msg\"",$var);
		
			$mentry = ordermailentry("$order_id", "$from","$to",6);
			$message = ereg_replace("\n", "<br>", $message );
			echo "To: $to<br>Subjuct: $subject<br>Message: $message<br>";
		}
		
		//update rlafinance.ordersteps
		$stepstr = "q";
		$email_name= $from;
		include("rla_fin_order_procedure_update.inc");
		
		//update rlafinance.orderid
		$sql = "UPDATE rlafinance.orderid 
        	SET updatestatus='$stepstr'
        	where order_id='$order_id';";
       $result = mysql_query($sql);
    	include("err_msg.inc");
    	
 		//update rlafinance.orderid
       $sql = "INSERT INTO rlafinance.queryorder
        	VALUES('$order_id', '$from', '$message');";     
       $result = mysql_query($sql);
    	include("err_msg.inc");
		
	} else {
		echo "<b>No mail has been sent because no query has been selected.<b>";
	}
	exit;
}
if ($rlaorderid && !$approvalorder) {
	$order_id = $rlaorderid;
}
if ($action == "aproval" && !$approvalorder) {
###########################
	echo "<h2>Approve Order</h2>";
	include("rla_fin_display_order.inc");
	
###########################
	echo "<hr>";
	echo "<h2>Query Order</h2>";
	//echo "$order_id, $order_by $t, $f, $m, $l <br>";

	echo "<form method=post>";
	echo "<input type=hidden name=\"order_id\" value=\"$order_id\">";
	echo "<input type=hidden name=\"order_by\" value=\"$order_by\">";
	echo "<input type=hidden name=\"fname\" value=\"$f\">";
	echo "<input type=hidden name=\"noitemordered\" value=\"$noitemordered\">";

	echo "<table>"; 
	echo "<tr><th align=left>Change Supplier to</th><td>";
	echo "<select name=supplier>";
	echo "<option value=\"\">---select one---";
	$sql = "SELECT company, telno, faxno
        FROM rlafinance.supplierid 
        WHERE telno!='' ORDER BY company;"; //and faxno!=''
    $result = mysql_query($sql);
    include("err_msg.inc");
    while (list($company, $telno, $faxno) = mysql_fetch_array($result)) {
        echo "<option>$company (Tel: $telno, Fax: $faxno)";
    }
	echo "</option></select></td>";
	echo "</table><table border=1>";
		$col = 6;
		echo "<tr><th>No</th><th>Qty</th><th>$/Unit</th><th>Product</th>
			<th>For Project</th><th>Suggestion</th></tr>";
		for ($i=0; $i<$noitemordered; $i++) {
			$k= $i+1;
			$l = $orddet[$i][0];
			echo "<tr><td>$k</td>";
			 
			echo "<td>".$orddet[$i][2]."</td>";
			echo "<td>".$orddet[$i][3]."</td><td>";
			for ($j=0; $j<$nogs; $j++) {
				$id = $glist[$j][0];
				$tmp = $glist[$j][1];
				if ($l == $glist[$j][0]) {
					echo "$tmp";
					$gdsdet = $tmp." (".$orddet[$i][2].")";
					break;
				}
			}
			echo "</td><td>";
			$kk = $orddet[$i][7];	
			for ($j=0; $j<$norlacharge; $j++) {
				$ll= $rlacharge[$j][0];
				if ($kk == $ll) {
					echo $rlacharge[$j][1];
				}
			}
			echo "</td>";
			echo "<td><input type=\"checkbox\" name=\"ckCancel$i\">Cancel</td>";
			echo "<input type=\"hidden\" name=\"ordercancel$i\" value=\"".$gdsdet."\">";
			echo "</tr>";
		} 
	
		echo "<tr><th align=left>From</th><td colspan=5>";
		echo "<select name=\"authperson\">"; 
		include("find_admin_ip.inc");
		if (getenv("remote_addr") == $adminip) {
			$k=1;
		} else {
			$k=1;
		}
		$sql = "SELECT details FROM rlafinance.control 
			WHERE controllist='AuthPerson'";
		$result = mysql_query($sql);
		include("err_msg.inc");
		list($details) = mysql_fetch_array($result);
		$tmp = explode(";", $details);
		$machip = getenv("remote_addr");
		$machip = substr($machip ,strlen($machip)-3,3);
		for ($i=$k; $i<count($tmp); $i++) {
			$person = explode("@",$tmp[$i]);
			$v = $person[0]."@".$person[1];
			if ($machip == $person[2]) {
				echo "<option value=\"$v\" selected>".$person[1];
			} else {
				echo "<option value=\"$v\">".$person[1];
			}
		}
		echo "</option></td></tr>";

	echo "<tr><th align=left>Remarks</th><td colspan=5>
		<textarea name=remarks cols=60 rows=5></textarea>";
	echo "<tr><td colspan=6 align=middle><button type=submit name=orderquery><b>Send mail to $f</b></button>";
	echo "</table>";
	echo "<br><hr>";
	
###########################
	$m = date("m");
	$dateorders = date("Y")."-$m-01";
	$dateordere = date("Y")."-$m-".$daysinmth["$m"];
	$m = $mth["$m"];
	echo "<h2>Order Summary For $m.</h2>";

	$sql = "SELECT order_id, order_by, orderdate, estimate_cost, invoice_cost, 
			delivery_cost, updatestatus
        FROM rlafinance.orderid 
        WHERE (orderdate>='$dateorders' and orderdate<='$dateordere') and ordercancelled='n' 
        ORDER BY order_id DESC;";
	//echo "$sql";

    $result = mysql_query($sql);
    include("err_msg.inc");
    $no = mysql_num_rows($result);

	$totalcost = 0;
	if ($no) {
		$i=0;
  		echo "<table border=1>";
	   	while (list($order_id, $order_by, $orderdate, $estimate_cost, $invoice_cost, 
			$delivery_cost, $updatestatus) = mysql_fetch_array($result)) {
 			$info	=	"?".base64_encode("action=view&order_id=$order_id&viewonly=true");
			$info = "<a href=\"rla_fin_order_mod.php$info\" target=_blank><b>$order_id</b></a>";
			$sumorder[$i] = "<tr><td align=middle>$info</td><td>$order_by</td>
				<td>$orderdate</td><td>".$status[$updatestatus]."</td>";
			if ($invoice_cost>0) {//<
				$tc = $invoice_cost + $delivery_cost;
				$totalcost = $totalcost + $tc;
				$sumorder[$i] = $sumorder[$i]."<td align=right>$tc</td><td align=middle>---</td>";
			} else {
				if (0<$estimate_cost) {
					$totalcost = $totalcost + $estimate_cost;
					$sumorder[$i] = $sumorder[$i]."<td align=middle>---</td><td align=right>$estimate_cost</td>";
				} else {
					$sumorder[$i] = $sumorder[$i]."<td align=middle>---</td><td align=middle>NA</td>";
				}
			}
			$i++;
    	}
    	echo "<h3>Total order value for this month is $totalcost.</h3>";
		echo "<tr><th>Order No</th><th>Order By</th><th>Date</th><th>Order Status</th>
			<th>Invoice Cost</th><th>Estimated Cost</th></tr>";
		for ($j=0; $j<$no; $j++){
			echo $sumorder[$j]."</tr>";
		}
   		echo "</table>";
    } else {
    	echo "<b>No order found for this month.</b>";
    }
}

if ($approvalorder) {
	for ($i=0; $i<$noitem; $i++) {
		$good1 	= "goods$i";
		$chargeghr 	= "ghrcharge$i";
		$detailsid = "detids$i";

		if ($$good1 && $$chargeghr && $$detailsid) {
			$item[$i] 		= $$good1;
			if ($$chargeghr == "saa") {
				$ghrcharge[$i] = $ghrcharge[$i-1];
			} else {
				$ghrcharge[$i] = $$chargeghr;
			}
			$iddetails[$i] = $$detailsid;
			//echo "$good1=".$item[$i]."; $chargeghr=".$ghrcharge[$i]."; $detailsid=".$iddetails[$i]."<br>";
		}
	}
	//credit=1; cheque=2; cash=3; account=4

//update rlafinance.ordersteps
	//$status: n=new;m=modified;c=cancelled;p=approved;s=sent;r=received;v=verified;b=backorder;f=completed
	$stepstr = "p";
	$strstr = $email_name;
	$email_name = $authperson;
	include("rla_fin_order_procedure_update.inc");
	$email_name = $strstr;

//update rlafinance.orderid
	if ($paymethod != 1) {
		$card_id = "";
	}
	$sql = "UPDATE rlafinance.orderid 
		SET updatestatus='$stepstr', paymethod='$paymethod', 
		card_cheque='$card_id', pay_remarks='$pay_remarks'
	 	WHERE order_id='$order_id'";
	$result = mysql_query($sql);
	include("err_msg.inc");
	//echo "$sql<br>";
/*	
	echo "supid = $supid<br>";;
	echo "noitem = $noitem<br>";;
	echo "order_id = $order_id<br>";;
	echo "authperson = $authperson<br>";;
	echo "paymethod = $paymethod<br>";;
	echo "card_id = $card_id<br>";;
	echo "pay_remarks = $pay_remarks<br>";;
	echo "$good1=".$item[$i]."; $chargeghr=".$ghrcharge[$i]."<br>";
//*/
		
//update rlafinance.orderdetails
	for ($i=0; $i<$noitem; $i++) {
		$goods_id = $item[$i]; 
		$code_id= $ghrcharge[$i];
		$detailsid = $iddetails[$i];
		$sql = "UPDATE rlafinance.orderdetails 
			SET code_id='$code_id'
			where detailsid='$detailsid' and order_id='$order_id' and goods_id='$goods_id';";
		//echo "$sql<br>";	
		$result = mysql_query($sql);
		include("err_msg.inc");
	}
	
//find info for auth
	$sql = "SELECT title as ta, first_name as fa, middle_name as ma, last_name as la
		from timesheet.employee 
		where email_name='$authperson'";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($ta, $fa, $ma, $la) = mysql_fetch_array($result);
	$mfrm = "$authperson@$domain";
	$extra		= "From: $authperson@$domain\nReply-To: $authperson@$domain\nX-Mailer: $PHPconst/".phpversion();
	//echo "$ta, $fa, $ma, $la <br>$extra";
	//echo "<br>&rlaorderid=$order_id&action=$action&frommail=$frommail&frofn=$frofn&fromln=$fromln";
//find info for purchasing officer
	$sql = "SELECT details FROM rlafinance.control WHERE controllist='OrderOfficer'";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($details) = mysql_fetch_array($result);
	$po = explode("@", $details);
	if ($testdb == "y") {
		include("find_admin_ip.inc");
		$po[0] = "$adminname";
		$po[1] = "Administrator";
	}
	$purchoffice = $po[0]."@$domain";

//mail to person who initiates the order
	$to = "$frommail@$domain";
	$putorder = $to;
	$subject 	= "RE: $company Order $rlaorderid has been approved";
	$sername = getenv("server_name");
	$d = date("G:i F d, Y");
	$message 	= "Dear $frofn\n\n".
		"Your order on the Intranet ($company Internal Order No: $rlaorderid) ".
		"has been approved.\n\n".
		"Regards\n\n".
		"$fa $la\n$d";
	if ($sendmail == "y" && ($to != $mfrm) && ($purchoffice != $putorder)) {
		//mail ($to, $subject, $message, $extra);
		$from = "$authperson\@$domain";
		$to = "$frommail\@$domain";
		$cc = "";
		$msg = "$message";
		system("./_mail.pl \"$from\" \"$to\" \"$cc\" \"$subject\" \"$msg\"",$var);
		$mentry = ordermailentry("$rlaorderid", "$authperson","$to", 2);
	}
	$message = ereg_replace("\n", "<br>", $message);
	//echo "To: $to<br>Subjuct: $subject<br>Message: $message<br>";

//mail to purchasing officer
	$to = $po[0]."@$domain";
	$subject 	= "RE: Purchasing Order $rlaorderid from $frofn";
	$info	=	"rlaorderid=$rlaorderid&frommail=$frommail&frofn=$frofn&fromln=$fromln";
	//echo $info."<br>";
	$info	=	base64_encode($info);
	$message 	= "Dear ".$po[1]."\n\n".
		"I have approved $frofn's purchasing order ($company Internal Order No: $rlaorderid), ".
		"please print the order and fax it to supplier.\n\n".
		"To print go to \n".
		"http://$sername/$phpdir/rla_fin_order_process.php?$info\n\n".
		"Regards\n\n".
		"$fa $la\n$d";
	if ($sendmail == "y") {
		//mail ($to, $subject, $message, $extra);
		
		$from = "$authperson\@$domain";
		$to = $po[0]."\@$domain";
		$cc = "";
		$msg = "$message";
		system("./_mail.pl \"$from\" \"$to\" \"$cc\" \"$subject\" \"$msg\"",$var);

		$mentry = ordermailentry("$rlaorderid", "$authperson","$to", 3);
	}

	$message = ereg_replace("\n", "<br>", $message);
	//echo "To: $to<br>Subjuct: $subject<br>Message: $message<br>";
	/*
	include("find_admin_ip.inc");
	if (getenv("remote_addr") == $adminip) {
		echo "<p><a href=\"http://$sername/$phpdir/rla_fin_order_process.php?$info\" target=\"_blank\">".
			"Print $company Purchasing Order $rlaorderid.</a><br>";
	}
	//*/

	if ($purchoffice != $putorder) {
		echo "<h4>Order approval process completed. Mail has been sent to ".$po[1]." and $frofn respectively.</h4>";
	} else {
		echo "<h4>Order approval process completed. Mail has been sent to ".$po[1].".</h4>";
	}
}
//echo "<hr><br><a href=#top>Back to top</a><br><br>";

?>
</html>
