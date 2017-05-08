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
echo "<h2 align=center><a id=top>Receive Order</a>";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[Refresh]</font></a></h2><hr>";
include("connet_root_once.inc");

//#######################################################################
//display verification form
if ($action == "Verify"  && !$verifybackorder) {
	#echo "action=$action&order_id=$order_id";
	include("rla_fin_display_order.inc");
	echo "<hr>";
}

//#######################################################################
//Process verification form
if ($Verifyorder) {	
	$rlaorderid = $order_id;
	$backorder = 0;
	for ($i=0; $i<$noitemordered; $i++) {
		$unitordered	= "unitordered$i";
		$unitorcvd		= "unitorcvd$i";
		$product	= "product$i";
		$detids = "detids$i";
		if ($$unitordered && $$product && $$detids) {//$$unitorcvd &&
			$veryorder[$i][0] = $$unitordered;
			$veryorder[$i][1] = $$unitorcvd;
			$veryorder[$i][2] = $$product;
			$veryorder[$i][3] = $$detids;
			if ($$unitordered != $$unitorcvd ) {
				$backorder++;
			}
			#echo "unitordered=".$$unitordered."; unitorcvd=".$$unitorcvd."; product=".$$product."<br>";
		}
	}

//back order entry
	if ($backorder) {
		for ($i=0; $i<$noitemordered; $i++) {
			if ($veryorder[$i][0] != $veryorder[$i][1] ) {
				$unit_ord = $veryorder[$i][0]; 
				$unit_rcvd = $veryorder[$i][1]; 
				$product = $veryorder[$i][2];
				$detailsid = $veryorder[$i][3];
				$checkin = "n";
				$bdate = date("Y-m-d");
				$sql = "INSERT INTO rlafinance.backorder SET autoid='null', detailsid='$detailsid', 
				order_id='$order_id', unit_ord='$unit_ord', unit_rcvd='$unit_rcvd', 
				product='$product', checkin='$checkin', bdate='$bdate'";
				$result = mysql_query($sql);
				include("err_msg.inc");
				#echo "$sql<br>";
			}
		}
	}

//make an entry in rlafinance.ordersteps
	if ($backorder) {
		$stepstr = "b";
	} else {
		$stepstr = "r";
	}
	include("rla_fin_order_procedure_update.inc");

//make an entry in rlafinance.orderid
	$sql = "UPDATE rlafinance.orderid SET updatestatus='$stepstr'
	 	WHERE order_id='$order_id'";
	$result = mysql_query($sql);
	include("err_msg.inc");
	//echo "$sql<br>";

//update rlafinance.orderdetails
	for ($i=0; $i<$noitemordered; $i++) {
		$unit_ord = $veryorder[$i][0]; 
		$unit_rcvd = $veryorder[$i][1]; 
		$detailsid = $veryorder[$i][3];
		if ($unit_ord != $unit_rcvd) {
			$checkin = "b";
		} else {
			$checkin = "y";
		}
		$sql = "UPDATE rlafinance.orderdetails SET checkin='$checkin' 
			WHERE detailsid='$detailsid';";
		$result = mysql_query($sql);
		include("err_msg.inc");
		#echo "$sql<br>";
	}
	if ($backorder) {
		echo "<h2>Part of the Order Received.</h2>";
	} else {
		echo "<h2>Order Received.</h2>";
	}
	echo "<hr>";
	
#exit;
//mail to order initiator
	//echo "$supid. $noitemordered $order_id <br>"; 
	//echo "$backorder backorder<br>";
	//find order ename
	$sql = "SELECT order_by as ename FROM rlafinance.orderid 
        WHERE order_id='$order_id'";
    $result = mysql_query($sql);
    include("err_msg.inc");
    list($ename) = mysql_fetch_array($result);
            
	$sql = "SELECT first_name as fname FROM timesheet.employee 
        WHERE email_name='$ename'";
    $result = mysql_query($sql);
    include("err_msg.inc");
    $no = mysql_num_rows($result);
    list($fname) = mysql_fetch_array($result);
	$order_id = $rlaorderid ;
	$info	=	"action=view&order_id=$order_id";
	$info	=	base64_encode($info);
	$sername = getenv("server_name");
	$d = date("G:i F d, Y");
	$to 		= "$ename@$domain";
	$mfrm = "$email_name@$domain";
	$extra		= "From: $email_name@$domain\nReply-To: $email_name@$domain\nX-Mailer: $PHPconst/".phpversion();
	if ($backorder) {
		$part 	= " partial";
	} else {
		$part 	= "";
	}
	$subject 	= "RE: Your Order $order_id has been$part received";
	$message 	= "Dear $fname\n\n".
		"Your purchasing order ($company Internal Order No: $rlaorderid) ".
		"has been$part received.\n\n".
		"To view your order go to \n".
		"http://$sername/$phpdir/rla_fin_order_mod.php?$info\n\n".
		"Regards\n\n".
		"$first_name\n$d";
	if ($sendmail == "y" && ($to != $mfrm)) {
		//mail ($to, $subject, $message, $extra);
		$from = "$email_name\@$domain";
		$to = "$ename\@$domain";
		$cc = "";
		//$subject = "";
		$msg = "$message";
		system("./_mail.pl \"$from\" \"$to\" \"$cc\" \"$subject\" \"$msg\"",$var);
		
		$mentry = ordermailentry("$rlaorderid", "$email_name","$to",5);
	}
	$message = ereg_replace("\n", "<br>", $message);
	//echo "$to<br>$subject<br>$message<br>$extra<br>";
}

//#######################################################################
if ($action == "backorder") {
	$order_id=$bkorder_id;
	#echo "here<br>bkorder_id=$bkorder_id";
	#exit;
	include("rla_fin_display_order.inc");
	echo "<hr>";
}

//#######################################################################
if ($verifybackorder) {
	$debug = "n";
	$order_id = $bkveryorder_id; 
	$backorder = 0;
	for ($i=0; $i<$nobackorder; $i++) {
		$autoid	= "autoid$i";
		$detids	= "detids$i";
		$product	= "product$i";
		$bkordered = "bkordered$i";
		$unitorcvd = "unitorcvd$i";
		
		if ($$autoid && $$detids && $$product && $$bkordered ) { //&& $$unitorcvd
			$veryorder[$i][0] = $$autoid;
			$veryorder[$i][1] = $$detids;
			$veryorder[$i][2] = $$product;
			$veryorder[$i][3] = $$bkordered;
			$veryorder[$i][4] = $$unitorcvd;
			if ($$bkordered  != $$unitorcvd ) {
				$backorder++;
			}
			if ($debug == "y") {
				echo "autoid=".$$autoid."; detids=".$$detids."; product=".$$product.
				"<br>bkordered=".$$bkordered."; unitorcvd=".$$unitorcvd."<br><br>";
			}
		}
	}
	
//clear current back order entry
	for ($i=0; $i<$noitemordered; $i++) {
		$autoid = $veryorder[$i][0];
		$unit_rcvd = $veryorder[$i][4];
		if (!$unit_rcvd) {
			$unit_rcvd = 0;
		}
	  if ($autoid) {
		$checkin = "y";
		$rdate = date("Y-m-d");
		$sql = "UPDATE rlafinance.backorder SET unit_rcvd='$unit_rcvd', 
			checkin='$checkin', rdate='$rdate'
			WHERE autoid='$autoid'";
		if ($debug == "y") {
			echo "clear current back order entry $i:<br>$sql<br><br>";
		} else {
			$result = mysql_query($sql);
			include("err_msg.inc");
		}
	  }
	}

//back order entry if still not receive all orders
	if ($backorder) {
		for ($i=0; $i<$noitemordered; $i++) {
			if ($veryorder[$i][3] != $veryorder[$i][4] ) {
				$detailsid = $veryorder[$i][1];
				$product = $veryorder[$i][2];
				$unit_ord = $veryorder[$i][3]; 
				$unit_rcvd = $veryorder[$i][4]; 
				$checkin = "n";
				$bdate = date("Y-m-d");
				$sql = "INSERT INTO rlafinance.backorder SET autoid='null', detailsid='$detailsid', 
				order_id='$order_id', unit_ord='$unit_ord', unit_rcvd='$unit_rcvd', 
				product='$product', checkin='$checkin', bdate='$bdate'";
				if ($debug == "y") {
					echo "new back order entry $i:<br>$sql<br><br>";
				} else {
					$result = mysql_query($sql);
					include("err_msg.inc");
				}
			}
		}
	}
	
//update rlafinance.orderdetails 
	for ($i=0; $i<$nobackorder; $i++) {
		$detailsid = $veryorder[$i][1];
		$unit_ord = $veryorder[$i][3]; 
		$unit_rcvd = $veryorder[$i][4]; 
		if ($unit_ord != $unit_rcvd) {
			$checkin = "b";
		} else {
			$checkin = "y";
		}
		$sql = "UPDATE rlafinance.orderdetails SET checkin='$checkin' 
			WHERE detailsid='$detailsid';";
		if ($debug == "y") {
			echo "orderdetails entry $i<br>$sql<br><br>";
		} else {
			$result = mysql_query($sql);
			include("err_msg.inc");
		}
	}

//make an entry in rlafinance.ordersteps and rlafinance.orderid
	if ($backorder) {
		$stepstr = "b";
	} else {
		$stepstr = "r";
	}
	include("rla_fin_order_procedure_update.inc");

	$sql = "UPDATE rlafinance.orderid SET updatestatus='$stepstr'
	 	WHERE order_id='$order_id'";
	if ($debug == "y") {
		echo "orderid entry<br>$sql<br><br>";
	} else {
		$result = mysql_query($sql);
		include("err_msg.inc");
	}

	echo "<h2>Back order ($order_id) verification completed.</h2>";
	echo "<hr>";
	/*
	echo "stepstr =$stepstr verifybackorder";
	echo " $order_id $nobackorder<br>";
	exit;
	//*/
}

//#######################################################################
//***********List Order to be received
	$sql = "SELECT order_id, orderdate, order_by FROM rlafinance.orderid 
		WHERE updatestatus='s' ORDER BY order_id DESC";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	if ($no) {
		echo "<h2>RLA Current Order ($no)</h2>";
		echo "<table border=1>";
		echo "<tr><th>ID</th><th>Date</th><th>Order By</th><th>Receive</th></tr>";
		while (list($order_id, $orderdate, $order_by) = mysql_fetch_array($result)) {
			include("rla_fin_order_steps.inc");
			//$prostepsdetails[$steps][$pi]	$status[]
			echo "<tr><td>$order_id</td>";	
			echo "<td>$orderdate</td>";	
			echo "<td>$order_by</td>";	
			$userstr	=	"?".base64_encode($userinfo."&action=Verify&order_id=$order_id");
			echo "<td><a href=\"".$PHP_SELF."$userstr\">[Receive]</a></td>";
			echo "</tr>";
		}
		echo "</table><p>";
	} else {
		//echo "<h2>$first_name</h2>";
		//echo "You don't have any order need to be verified.<br>";
	}

//#######################################################################
//***********List Back Order to be veryfied
	$sql = "SELECT order_id, orderdate, order_by 
		FROM rlafinance.orderid 
		WHERE updatestatus='b' ORDER BY order_id DESC";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	if ($no) {
		echo "<h2>RLA Back Order</h2>";
		echo "<table border=1>";
		echo "<tr><th>ID</th><th>Date</th><th>Order By</th><th>Receive</th></tr>";
		while (list($order_id, $orderdate, $order_by) = mysql_fetch_array($result)) {
			include("rla_fin_order_steps.inc");
			//$prostepsdetails[$steps][$pi]	$status[]
			echo "<tr><td>$order_id</td>";	
			echo "<td>$orderdate</td>";	
			echo "<td>$order_by</td>";	
			$userstr	=	"?".base64_encode($userinfo."&action=backorder&bkorder_id=$order_id");
			echo "<td><a href=\"".$PHP_SELF."$userstr\">[Receive]</a></td>";
			echo "</tr>";
		}
		echo "</table><p>";
	} else {
		//echo "<h2>$first_name</h2>";
		//echo "You don't have any back order need to be verified.<br>";
	}

echo "<hr><br><a href=#top>Back to top</a><br><br>";

?>
</html>
