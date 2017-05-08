<html>

<head>
<title>Inventory Data Uniformity</title>
</head>
<body background="rlaemb.JPG" leftmargin="40">

<?php
include('str_decode_parse.inc');
include("rla_functions.inc");
include("connet_other_once.inc");
if ($priv == "00" || $priv	==	'10') {
} else {
	exit;
}
echo "<a id=top><h1 align=center></h1><hr>";
$userstr	=	"?".base64_encode($userinfo);
echo "<h2 align=center><a id=top>Disposal Item</a>";
//echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2> [Refresh]</font></a>";
echo "</h2><hr>";
//include("userstr.inc");

#############################
if ($disposalitem) {
	$disposal = "disposed on ".date("Y-m-d");
	if ($reason) {
		$disposal .= " ($reason)";
	}
	$sql = "UPDATE inventory.primlist 
        SET disposal='$disposal' 
        WHERE itemid='$barcodesel';";
	include("connet_root_once.inc");
	$result = mysql_query($sql);
	include("err_msg.inc");
	$disposal = "";
	$sql = "SELECT itemid, catid, brandid, manid, 
            biref_description, barcode, size_xyz_cm, color, 
            year_made, order_no, purchase_date, disposal, 
            purchasing_price, purchased_by, purchased_for, entry_id 
        FROM inventory.primlist 
        WHERE itemid='$barcodesel';";
    $result = mysql_query($sql);
    include("err_msg.inc");
    $no = mysql_num_rows($result);
    list($itemid, $catid, $brandid, $manid, 
            $biref_description, $barcode, $size_xyz_cm, $color, 
            $year_made, $order_no, $purchase_date, $disposal, 
            $purchasing_price, $purchased_by, $purchased_for, $entry_id) = mysql_fetch_array($result);
	$str="$itemid, $catid, $brandid, $manid, 
            $biref_description, $barcode, $size_xyz_cm, $color, 
            $year_made, $order_no, $purchase_date, $disposal, 
            $purchasing_price, $purchased_by, $purchased_for, $entry_id";
    $str = ereg_replace(",", "<br>", $str);
	$disstr = "<hr><h2>Item $barcode has been $disposal.</h2>";
}

#############################
echo "<form method=post>";
echo "<input type=hidden name=frm_str value=\"$userstr\">";
	$sql = "SELECT itemid, barcode, disposal
        FROM inventory.primlist
        ORDER BY barcode";
	$result = mysql_query($sql);
   include("err_msg.inc");
   echo "<table>";
   echo "<tr><th>Select Barcode</th>";
   echo "<td><select name=barcodesel>";
	while (list($itemid, $barcode, $disposal) = mysql_fetch_array($result)) {
		if ($disposal) {
			$disposal = " (disposed)";
		}	
		echo "<option value=$itemid>$barcode $disposal";
	}
	echo "</option></select></td></tr>";
	echo "<tr><th align=left>Reason</th><td><textarea name=reason cols=40 rows=2></textarea></td></tr>";
	echo "<tr><th colspan=2><button type=submit name=disposalitem><b>Disposal This Item</b></button></th></tr>";
	echo "</table>";
echo "</form>";

if ($disstr) {
    echo $disstr;
}

echo "<hr><br><a href=#top>Back to top</a><br><br>";
?>
</body>
