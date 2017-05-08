<html>
<body background="rlaemb.JPG" leftmargin="20">
<?php
//js
include('str_decode_parse.inc');
include("rla_functions.inc");
include("userinfo.inc"); //$userinfo
//echo "$mystat<br>";
if ($mystat == "auth" || $mystat == "exec") { // $mystat == "poff"
} else {
	exit;
}
$userstr	=	"?".base64_encode($userinfo."&mystat=$mystat");
//echo '<input type="hidden" name="frm_str" value= "'.$userstr.'">';
echo "<h2 align=center><a id=top>Credit Card List and Modification</a>";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[Refresh]</font></a></h2><hr>";
include("connet_root_once.inc");

if ($cardmodify || $carddelete) {
	$sql = "SELECT holder, cardtype, cardno, expiredate, issuer FROM rlafinance.creditcard
		where card_id='$card_id';";
	//echo $sql."<br>";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($holder, $cardtype, $cardno, $expiredate, $issuer) = mysql_fetch_array($result);
	
	if ($cardmodify) {
		//echo "$card_id, $holder, $cardtype, $cardno, $expiredate, $issuer<br>";
		include("rla_fin_newcard.inc");
	}
	if ($carddelete) {
		$sql = "DELETE FROM rlafinance.creditcard WHERE card_id='$card_id';";
		$result = mysql_query($sql);
		include("err_msg.inc");
		echo "<p><table border=1>";
		echo "<tr><th>Card Holder</th><th>Card Type</th><th>Card No</th><th>Expired Date</th><th>Issuer</th>";
			echo "<tr><td>$holder</td>";
			echo "<td>$cardtype</td>";
			echo "<td>$cardno</td>";
			echo "<td>$expiredate</td>";
			echo "<td>$issuer</td>";
			echo "</tr>";
		echo "</table><p>";
		echo "<h3>The card above has been deleted.</h3>";
	}
	echo "<hr>";
}

if ($createnewcard) {
	$expiredate="$year-$month-".$daysinmth[$month];
/*
	echo "$holder<br>";
	echo "$cardtype<br>";	
	echo "$cardno<br>";
	echo "$expiredate<br>";	
	echo "$issuer<br>";	
	//holder, cardtype, cardno, expiredate, issuer
//*/
	if ($card_id) {
		$sql = "UPDATE rlafinance.creditcard SET holder='$holder', 
			cardtype='$cardtype', cardno='$cardno', expiredate='$expiredate', 
			issuer='$issuer' WHERE card_id='$card_id'";
	} else {
		$sql = "INSERT INTO rlafinance.creditcard VALUES('null', '$holder', 
			'$cardtype', '$cardno', '$expiredate', '$issuer');";
	}
	//echo $sql."<br>";
	$result = mysql_query($sql);
	include("err_msg.inc");
	if ($card_id) {
		echo "<h2>Credit card info has been updated successfully.</h2>";
	} else {
		echo "<h2>New credit card has been added successfully.</h2>";
	}
	echo "<hr>";
}

###################	current credit card
	$today=date('Y-m-d');
	$sql = "SELECT card_id, holder, cardtype, cardno, expiredate, issuer FROM rlafinance.creditcard
		where expiredate>='$today';";
	//echo $sql."<br>";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	if ($no == 0) {
		echo "<h4>No (or No Valid) Credit Cards in DB.</h4>";
	} else {
		echo "<h2>Current valid credit card list</h2>";
		echo "<table border=1>";
		echo "<tr><th>Card Holder</th><th>Card Type</th><th>Card No</th><th>Expired Date</th>
			<th>Issuer</th><th>Modify</th><th>Delete</th>";
		while (list($card_id, $holder, $cardtype, $cardno, $expiredate, $issuer) = 
			mysql_fetch_array($result)) {
			echo "<tr><td>$holder</td>";
			echo "<td>$cardtype</td>";
			echo "<td>$cardno</td>";
			echo "<td>$expiredate</td>";
			echo "<td>$issuer</td>";
			$frm_str	=	"?".base64_encode($userinfo."&mystat=$mystat&card_id=$card_id&cardmodify=yes");
			echo "<td><a href=\"$PHP_SELF$frm_str\">[Modify]</td>";
			$frm_str	=	"?".base64_encode($userinfo."&mystat=$mystat&card_id=$card_id&carddelete=yes");
			echo "<td><a href=\"$PHP_SELF$frm_str\">[DELETE]</td>";
			echo "</tr>";
			//echo "$card_id, $holder, $cardtype, $cardno, $expiredate, $issuer<br>";
		}
		echo "</table><p>";
	}

#############	form to add new credit card
	if ($action) {
		echo "<hr>";
		include("rla_fin_newcard.inc");
	}

###################	add new credit card button
	echo "<hr>";
	echo "<form name=form1>";
	echo '<input type="hidden" name="frm_str" value= "'.$userstr.'">';
	echo '<input type="submit" value="New Credit Card" name="action">';
	echo "</form>";
		
echo "<hr><br><a href=#top>Back to top</a><br><br>";
?>
</html>
