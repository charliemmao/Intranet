<html>
<?php
include("phpdir.inc");
	if ($ordermodification && $stepstr == "m") {
		include("rla_fin_display_sup_info.inc");
	}

//****************insert to table ordersteps 		
	$sql = "SELECT details as gst_percent FROM rlafinance.control 
		WHERE controllist='gst';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($gst_percent) = mysql_fetch_array($result);

	for ($i=0; $i<$noitemordered; $i++) {
//****************insert to table orderdetails
		$goods_id = $item[$i]; //$prod[$item[$i]];
		$unit = $noquan[$i];
		$unit_price = $priceunit[$i];
		$gst_applicable = "y";
		$checkin = "n";
		$inv_processed = "n";
		$rlaprojid = $charge[$i]; //$rlacode[$charge[$i]];
		$sql = "INSERT INTO rlafinance.orderdetails SET detailsid='null', order_id='$order_id', 
			goods_id='$goods_id', supid='$supid', unit='$unit', unit_price='$unit_price', 
			gst_applicable='$gst_applicable', gst_percent='$gst_percent', 
			rlaprojid='$rlaprojid', to_inventory='$to_inventory', 
			inv_processed='$inv_processed', checkin='$checkin';";
		//echo "$sql<br>";
		//echo mysql_insert_id()."<br><br>";
		$result = mysql_query($sql);
		include("err_msg.inc");
		
//****************insert to table priceref
		if ($unit_price > 0) {
			//check previous price
			$sql = "SELECT price as priceprev, ymd FROM rlafinance.priceref 
				WHERE goods_id='$goods_id' ORDER BY ymd DESC limit 1;";
			$result = mysql_query($sql);
			include("err_msg.inc");
			$ymd = date("Y-m-d");
			if (mysql_num_rows($result)) {
				list($priceprev, $ymd) = mysql_fetch_array($result);
				if ($priceprev == 0) {
					$sql = "UPDATE rlafinance.priceref 
						SET price='$unit_price', ymd='$ymd' 
						WHERE goods_id='$goods_id' and price='$priceprev'";
				} elseif ($priceprev != $unit_price) {
					$sql = "INSERT INTO rlafinance.priceref VALUES('$goods_id', '$unit_price', '$ymd');";
				}
				//echo "$sql<br><br>";	
				$result = mysql_query($sql);
				include("err_msg.inc");
			} else {
				$sql = "INSERT INTO rlafinance.priceref VALUES('$goods_id', '$unit_price', '$ymd');";
				//echo "$sql<br><br>";	
				$result = mysql_query($sql);
				include("err_msg.inc");
			}
		}
	}
	
//mail to person for authorisation
	$sql = "SELECT details as sendmail FROM rlafinance.control WHERE controllist='sendmail'";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($sendmail) = mysql_fetch_array($result);

	$sql = "SELECT details as domain FROM rlafinance.control WHERE controllist='domain'";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($domain) = mysql_fetch_array($result);

	$sql = "SELECT details as company FROM rlafinance.control WHERE controllist='company'";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($company) = mysql_fetch_array($result);

	$extra		= "From: $email_name@$domain\nReply-To: $email_name@$domain\nX-Mailer: $PHPconst/".phpversion();
	$sql = "SELECT title as ta, first_name as fa, middle_name as ma, last_name as la
		from timesheet.employee 
		where email_name='$authperson'";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($ta, $fa, $ma, $la) = mysql_fetch_array($result);
	
	$to 		= "$authperson@$domain";
	//echo "$authperson - $domain<br>";
	if ($ordermodification && $stepstr == "m") {
		$orderstr = "modified";
	} else {
		$orderstr = "placed";
	}
	$subject 	= "RE: Please Approval $company Order $rlaorderid ($orderstr by $email_name)";
	$sername = getenv("server_name");
	$d = date("G:i F d, Y");
	$info	=	"rlaorderid=$rlaorderid&action=aproval&frommail=$email_name".
		"&frofn=$first_name&fromln=$last_name&time=$d";
	//echo $info."<br>";
	$info	=	base64_encode($info);
	if ($ordermodification && $stepstr == "m") {
		$orderstr = "modified the";
	} else {
		$orderstr = "placed an";
	}
	$message 	= "Dear $fa\n\n".
		"I have $orderstr order on the Intranet ($company Internal Order No: $rlaorderid). ".
		"Please authorise the order.\n\n".
		"To approve $first_name's order go to \n".
		"http://$sername/$phpdir/rla_fin_order_approve.php?$info\n\n".
		"Regards\n\n".
		"$first_name $last_name\n$d";
	if ($askforapproval == "n") {
		$sendmail = "n";
	}
	//echo "askforapproval = $askforapproval <br> sendmail = $sendmail <br>";
	if ($sendmail == "y") {
	
		//mail ($to, $subject, $message, $extra);
		$from = "$email_name\@$domain";
		$to = "$authperson\@$domain";
		$cc = "";
		$msg = "$message";
		system("./_mail.pl \"$from\" \"$to\" \"$cc\" \"$subject\" \"$msg\"",$var);

		$mentry = ordermailentry("$rlaorderid", "$email_name","$to",0);
	}
	$message = ereg_replace("\n", "<br>", $message);
	//echo "$to<br>$subject<br>$message<br>$extra<br>";

//mail to order initiator
	$to 		= "$email_name@$domain";
	$subject 	= "RE: Your Purchasing Order $rlaorderid";
	$status = "To review your order status ";
	$status .= "please logon to Intranet, click \"Existing Order\" from left frame.\n\n";
	
	/*
		include("userinfo.inc"); //$userinfo
		$info	=	"?".base64_encode($userinfo."&action=view&order_id=$rlaorderid");
		$status .=	"go to \nhttp://$sername/$phpdir/rla_fin_order_mod.php?$info\n\n";
	//*/
	
	$message 	= "Dear $first_name\n\n".
		"Your purchasing order ($company Internal Order No: $rlaorderid) ".
		"has been sent to $ta $fa $la for authorisation.\n\n$status".
		"Regards\n\n".
		"Administrator\n$d";
	
	if ($sendmail == "y") {
		//mail ($to, $subject, $message, $extra);

		$from = "$email_name\@$domain";
		$to = "$email_name\@$domain";
		$cc = "";
		$msg = $message;
		system("./_mail.pl \"$from\" \"$to\" \"$cc\" \"$subject\" \"$msg\"",$var);

		$mentry = ordermailentry("$rlaorderid", "system","$to", 1);
	}
	$message = ereg_replace("\n", "<br>", $message);
	//echo "$to<br>$subject<br>$message<br>$extra<br>";
	if ($priv == "00") {
		echo "<p><a href=\"http://$sername/$phpdir/rla_fin_order_approve.php?$info\" target=\"_blank\">".
			"Approve $company Purchasing Order $rlaorderid.</a><br>";
		//echo "<a href=\"http://$sername/$phpdir/rla_fin_order_mod.php?$info\" target=\"_blank\">".
		//	"View $company Purchasing Order $rlaorderid.</a><br><p>";
	}
	
//feed back
	echo "<h4>Order Details:</h4>";
	echo "<table border=1>";
	echo "<tr><th>No</th><th>Product</th><th>Qty</th><th>$/Unit</th><th>Sub Total</th><th>For Project</th></tr>";
	for ($i=0; $i<$noitemordered; $i++) {
		$j=$i+1;
		$prod0 = $prod[$item[$i]];
		$char = $rlacode[$charge[$i]];
		$tmp = $noquan[$i]*$priceunit[$i];
		$tmp = number_format($tmp,2);
		echo "<tr><td>$j</td><td>$prod0</td><td align=right>".$noquan[$i]."</td>
		<td align=right>".$priceunit[$i]."</td><td align=right>$tmp</td><td>$char</td></tr>";
	}
	echo "</table>";
	$estimate_cost = number_format($estimate_cost,2);
	echo "<p><b>Total estimated cost: $estimate_cost.</b><br><br>";
	echo "<p><b>Please check your order details.</b><br>";
	echo "<ul>";
	echo "<li>To modify your order (order id: <b>".
		"<font color=#ff0000>$order_id</font></b>) please click ".
		"<font color=#0000ff>\"Existing Order\"</font> from left frame.";
	echo "</ul>";
?>
</html>
