<html>

<head>
<title>Add New Item</title>
</head>
<body onload="firstele();" leftmargin="20" bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG">

<?php
//js
include('inventory_verify.inc');

include('str_decode_parse.inc');
include("userinfo.inc"); //$userinfo
$userstr	=	"?".base64_encode($userinfo);
echo "<a id=top><h1 align=center>Inventory: Add New Item";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[Refresh]</font></a></h1><hr>";
include("rla_functions.inc");

##	Process New Item Form
if ($addnewitem) {
	$process = 1;
	include("inv_cat_list.inc");
	include("inv_brand_list.inc");
	include("inv_manf_list.inc");
	include("inv_get_all_id_name.inc");

## set disposal field to nothing, only administrator(s) are allowed to fill this field.
	$disposal = '';

## based on barcode check whether this item has been registered
	$barcode = trim($barcode);
	if ($barcode) {
		$barcode = rlabarcode($barcode);
	}
	$new = "y";
	if ($barcode != "") {
		## based on barcode
		$sql = "SELECT itemid from inventory.primlist where barcode='$barcode';";
		//echo $sql."<br>";
		include("connet_other_once.inc");
		$result = mysql_query($sql);
		if (mysql_num_rows($result)) {
			list($itemid) = mysql_fetch_row($result);
			$new = "n";
		}
	} else {
		$barcode ="NA";
	}
	
## based on catid, brandid, manid, barcode, entry_id check whether this item has been registered
	$sql	=	"SELECT t1.itemid as id "
		."FROM inventory.primlist as t1, inventory.tracking as t2 where "
		."t1.catid='$catid1' and t1.brandid='$brandid1' and t1.manid='$manid1'  and t1.barcode='$barcode' "
		."and t1.itemid=t2.itemid and t1.entry_id=t2.entry_id;";
	//echo $sql."<br>";
	include("connet_other_once.inc");
	$result = mysql_query($sql);
	if (mysql_num_rows($result)) {
		list($id) = mysql_fetch_row($result);
		$itemid = $id;
		$new = "n";
	} else {
		$new = "y";
	}
		
	if ($new == "n") {
		if ($barcode == "NA") {
			echo "<h4>The item is not a new one.";
		} else {
			echo "<h4>Item with barcode \"<font color=#ff0000>$barcode</font>\" is not a new item.";
		}
		echo " It might be previously owned by you "
			."or has been transfered to you from someone else. "
			."If you want to register this item under your name, please click \"<font color=#0000ff>"
			."Modify My Entry</font>\" from left frame then query Item ID \"<font color=#ff0000>$itemid</font>\".</h4><hr>";	
	} else {
		//echo "<h4>This is a new item.</h4>";
	}
	
	if ($new == "y") {
		include('connet_other_once.inc');
##	step 1: get entry_id from inventory.entry_id by insert data
		/* table: entry_id. columns: entry_id, email_name, computer_ip_addr, yyyymmdd */
		$yyyymmdd = date("Ymd");
		$computer_ip_addr = getenv('REMOTE_ADDR');
		$sql = "INSERT INTO inventory.entry_id VALUES("
			."'NULL', '$ename0', '$computer_ip_addr', '$yyyymmdd');";
		//echo $sql."<br>";
		$fld = "entry_id";
		$table = "entry_id";
		newinvid($sql, &$entry_id, $fld, $table);
		$msg = "new entry_id: $entry_id";

##	step 2: insert data to inventory.primlist. each person has one entry_id per day
		$entry_id0 = $entry_id;
		include("inv_trim.inc");
		$sql = "INSERT INTO inventory.primlist VALUES("
			."'NULL', '$catid1', '$brandid1', '$manid1', '$biref_description', "
			."'$barcode', '$size_xyz_cm', '$color', '$year_made', '$order_no', "
			."'$purchase_date', '$disposal', '$purchasing_price', '$purchased_by', "
			."'$purchased_for', '$entry_id');";
		//echo $sql."<br>";
		$fld = "itemid";
		$table = "primlist";
		newinvid($sql, &$itemid, $fld, $table);
		$msg = $msg."<br>New Item ID: $itemid";
		$action	=	"Inv item Add: $itemid.";
		include('logging.inc');

##	step 3: insert data to inventory.tracking.
		/* table: tracking. columns: id, itemid, barcode, email_name, location, yyyymmdd, entry_id, ownnow*/
		$yyyymmdd = date('Ymd');
		$sql = "INSERT INTO inventory.tracking VALUES("
			."'NULL', '$itemid', '$barcode', '$email_name', '$location', '$yyyymmdd', '$entry_id0', 'Y');";
		//echo $sql."<br>";
		include('connet_other_once.inc');
		$result	=	mysql_query($sql);
		include('err_msg.inc');
		if ($result)	{
			$msg = $msg."<br>Tracking entry for ITEM ID $itemid from $email_name in $location.<br>";
		} else {
			$msg = $msg."<br>Tracking entry failed.<br>";
		}
		//echo $msg;

		$feedback = "<p><table border=0>";
		$feedback = $feedback."<tr><td><b>New Item ID</b></td><td>$itemid</td></tr>";
		$feedback = $feedback."<tr><td><b>Category</b></td><td>$catname</td></tr>";
		$feedback = $feedback."<tr><td><b>Manufacturer</b></td><td>$manfname</td></tr>";
		$feedback = $feedback."<tr><td><b>Bar Code</b></td><td>$barcode</td></tr>";
		$feedback = $feedback."<tr><td><b>Description</b></td><td>$biref_description</td></tr>";
		$feedback = $feedback."<tr><td><b>Location</b></td><td>$location</td></tr>";
		$feedback = $feedback."<tr><td><b>Owner</b></td><td>$ename</td></tr>";
	
		$feedback = $feedback."<tr><td><b>Model</b></td><td>$brandname</td></tr>";
		$feedback = $feedback."<tr><td><b>Dimension (xyz in cm)</b></td><td>$size_xyz_cm</td></tr>";
		$feedback = $feedback."<tr><td><b>Color</b></td><td>$color</td></tr>";
		$feedback = $feedback."<tr><td><b>Year Made</b></td><td>$year_made</td></tr>";
		$feedback = $feedback."<tr><td><b>Order No</b></td><td>$order_no</td></tr>";
		$feedback = $feedback."<tr><td><b>Purchasing Date</b></td><td>$purchase_date</td></tr>";
		//$feedback = $feedback."<tr><td><b>Disposal Date + Reason</b></td><td>$disposal</td></tr>";
		$feedback = $feedback."<tr><td><b>Purchasing Price ($)</b></td><td>$purchasing_price</td></tr>";
		$feedback = $feedback."<tr><td><b>Purchasing by (person)</b></td><td>$purchased_by</td></tr>";
		$feedback = $feedback."<tr><td><b>Purchasing for Project</b></td><td>$purchased_for</td></tr>";
		$feedback = $feedback."</table>";
		$feedback = $feedback."<h4>The above data has been successfully submitted. Please write down "
			."New Item ID \"<font color=#0000ff>$itemid</font>\" for your record.</h4>";
		$feedback = $feedback."<h4>To modify your entry click \"<font color=#0000ff>Modify My Entry</font>\" from left frame.</h4><hr>";
		echo $feedback;
	}
/* table: entry_id. columns: entry_id, email_name, computer_ip_addr, yyyymmdd */
/* table: primlist. columns: 
itemid, 				catid, 			brandid, 			manid, 		biref_description, 
barcode, 				size_xyz_cm, 		color, 			year_made, 	order_no, 
purchase_date, 		disposal, 
purchasing_price, 	purchased_by, 	purchased_for, 	entry_id */
/* table: category. columns: catid, cat_name, */
/* table: brandname. columns: brandid, brand_name, */
/* table: manufacture. columns: manid, manufacture, */
/* table: tracking. columns: id, itemid, barcode, email_name, location, entry_id, */
}

##	New Item Form
echo "<p><form method=\"post\" method=\"$PHP_SELF\" name=\"invnewitem\">";
$process = 0;
$size = 40;
echo "<p><table border=1>";
echo "<tr><th colspan=2><font color=#0000ff size=4>Essential Info</font></th></tr>";
echo "<tr><th align=\"left\">Category</th><td>New Category<br>"
		."<input type=\"text\" name=\"catname\" size=\"$size\"><br>Or Select From List<br>";
	include("inv_cat_list.inc");
echo "</td></tr>";
echo "<tr><th align=\"left\">Manufacturer</th><td>New Manufacturer<br>"
		."<input type=\"text\" name=\"manfname\" size=\"$size\"><br>Or Select From List<br>";
	include("inv_manf_list.inc");
echo "</td></tr>";
echo "<tr><th align=\"left\">Bar Code</th><td><input type=\"text\" name=\"barcode\" size=\"$size\"></td></tr>";
echo "<tr><th align=\"left\">Description</th><td><input type=\"text\" name=\"biref_description\" size=\"$size\"></td></tr>";
if ($location == '') {
	echo "<tr><th align=\"left\">Location</th><td><input type=\"text\" name=\"location\" value=\"Room \" size=\"$size\"></td></tr>";
} else {
	echo "<tr><th align=\"left\">Location</th><td><input type=\"text\" name=\"location\" value=\"$location\" size=\"$size\"></td></tr>";
}

echo "<tr><th align=\"left\">Owner</th><td><select name=\"ename0\">";
	$sql = "SELECT email_name as ename, first_name as fname, last_name as lname 
		FROM timesheet.employee where email_name!='webmaster' ORDER BY first_name;";
	include("connet_other_once.inc");
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	if (!$ename0) {
		$ename0 = $email_name;
	}
	while (list($ename, $fname, $lname) = mysql_fetch_array($result)) {
		if ($ename0 == $ename) {
			echo "<option selected value=$ename>$fname $lname";
		} else {
			echo "<option value=$ename>$fname $lname";
		}
	}
	echo "<option value='RLA'>RLA";
	echo "<option value='Engineering'>Engineering";
echo "</option></select>";
echo "</td></tr>";

echo "<tr><th colspan=2><font color=#0000ff size=4>If you know, please fill following fields.</font></th></tr>";
echo "<tr><th align=\"left\">Model</th><td>New Model<br>"
		."<input type=\"text\" name=\"brandname\" size=\"$size\"><br>Or Select From List<br>";
	include("inv_brand_list.inc");
echo "</td></tr>";
echo "<tr><th align=\"left\">Dimension (xyz in cm)</th><td><input type=\"text\" name=\"size_xyz_cm\" size=\"$size\"></td></tr>";
echo "<tr><th align=\"left\">Color</th><td><input type=\"text\" name=\"color\" size=\"$size\"></td></tr>";
echo "<tr><th align=\"left\">Year Made</th><td><input type=\"text\" name=\"year_made\" size=\"$size\"></td></tr>";
echo "<tr><th align=\"left\">Order No</th><td><input type=\"text\" name=\"order_no\" size=\"$size\"></td></tr>";
echo "<tr><th align=\"left\">Purchasing Date</th><td><input type=\"text\" name=\"purchase_date\" size=\"$size\"></td></tr>";
echo "<tr><th align=\"left\">Disposal Date + Reason</th><td><input type=\"text\" name=\"disposal\" size=\"$size\"></td></tr>";
echo "<tr><th align=\"left\">Purchasing Price ($)</th><td><input type=\"text\" name=\"purchasing_price\" size=\"$size\"></td></tr>";
echo "<tr><th align=\"left\">Purchasing by (person)</th><td><input type=\"text\" name=\"purchased_by\" size=\"$size\"></td></tr>";
echo "<tr><th align=\"left\">Purchasing for Project</th><td><input type=\"text\" name=\"purchased_for\" size=\"$size\"></td></tr>";

include("userstr.inc");

echo "<tr><td colspan= 2 align=\"center\"><input ";
	echo ' onClick="return (invverify());"';
	echo ' onSubmit="return (invverify());"';
	echo " type=\"submit\" value=\"SUBMIT\" name=\"addnewitem\"></td></tr>";
echo "</table></p></form>";
echo "<hr><br><a href=#top>Back to top</a><br><br>";

function newinvid($sql, &$id, $fld, $table) {
	include('connet_root_once.inc');
	$result	=	mysql_query($sql,$contid);
	include('err_msg.inc');
	if ($result)	{
		$id	 =	mysql_insert_id($contid);

		$sql = "SELECT $fld from inventory.$table ";
		$sql = $sql."where $fld='$id';";
		include('general_one_val_search.inc');
		if ($out != $id) {
			$id	 = $out;
			// send a record to logging
			$action	=	"inv_$table_pid_wrong";
			include('logging.inc');
		}
	}
}
?>
<script language="JAVASCRIPT">
function firstele() {
	document.invnewitem.catname.focus(); 
	document.invnewitem.catname.select();
}
</script>
</body>
