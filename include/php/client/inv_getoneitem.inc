<html>
<?php
include("connet_other_once.inc");	//connet_root_once.inc	connet_other_once.inc
$sql = "SELECT id, location from inventory.tracking where itemid='$itemid' and "
		."email_name='$email_name' order by id desc limit 1;";
//echo $sql."<br>";	
$result = mysql_query($sql,$contid);
if ($result) {
	while (list($id, $location) = mysql_fetch_row($result)) {
		$location1 	=	$location;
	}
}
$location = $location1;
//echo 'location: '.$location."<br>";	
$sql	=	"SELECT * FROM inventory.primlist where itemid=\"$itemid\";";
//echo $sql."<br>";	
$result = mysql_query($sql,$contid);
if ($result) {
	while (list($itemid, $catid, $brandid, $manid, $biref_description, $barcode, $size_xyz_cm, $color, $year_made, $order_no, 
		$purchase_date, $disposal, $purchasing_price, $purchased_by, $purchased_for, $entry_id) = mysql_fetch_row($result)) {
		$catname = $catlist[$catid];
		$brandname = $brandlist[$brandid];
		$manfname =	$manflist[$manid];
		$mybarcode = $barcode;
		$itemid_save = $itemid;
		if ($action == "noedit") {
			##	show Item data only
			echo "<p><form method=\"post\" method=\"$PHP_SELF\" name=invnewitem>";
			include("userstr.inc");
			$feedback = "<p><table border=0>";
			$feedback = $feedback."<tr><td><b>Item ID</b></td><td>$itemid</td></tr>";
			$feedback = $feedback."<tr><td><b>Category</b></td><td>$catname</td></tr>";
			$feedback = $feedback."<tr><td><b>Manufacturer</b></td><td>$manfname</td></tr>";
			$feedback = $feedback."<tr><td><b>Bar Code</b></td><td>$barcode</td></tr>";
			$feedback = $feedback."<tr><td><b>Description</b></td><td>$biref_description</td></tr>";
			$feedback = $feedback."<tr><td><b>Location</b></td><td>$location</td></tr>";
			$feedback = $feedback."<tr><td><b>Model</b></td><td>$brandname</td></tr>";
			$feedback = $feedback."<tr><td><b>Dimension (xyz in cm)</b></td><td>$size_xyz_cm</td></tr>";
			$feedback = $feedback."<tr><td><b>Color</b></td><td>$color</td></tr>";
			$feedback = $feedback."<tr><td><b>Year Made</b></td><td>$year_made</td></tr>";
			$feedback = $feedback."<tr><td><b>Order No</b></td><td>$order_no</td></tr>";
			$feedback = $feedback."<tr><td><b>Purchasing Date</b></td><td>$purchase_date</td></tr>";
			$feedback = $feedback."<tr><td><b>Disposal Date + Reason</b></td><td>$disposal</td></tr>";
			$feedback = $feedback."<tr><td><b>Purchasing Price ($)</b></td><td>$purchasing_price</td></tr>";
			$feedback = $feedback."<tr><td><b>Purchasing by (person)</b></td><td>$purchased_by</td></tr>";
			$feedback = $feedback."<tr><td><b>Purchasing for Project</b></td><td>$purchased_for</td></tr>";
			$feedback = $feedback."<tr><td colspan=2><input type=\"hidden\" value=\"$itemid\" name=\"itemid\">";
			$feedback = $feedback."<input type=\"hidden\" name=\"entry_id_old\" value=\"$entry_id\"></td></tr>";
			$feedback = $feedback."<tr><td colspan=2>&nbsp;</td></tr>";
			if ($changeowner) {
				$feedback = $feedback."<tr><td><b>Location</b></td><td>"
				."<input type=text name=location value=\"Room \"></td></tr>";// value=$location
				
				$feedback = $feedback."<tr><th align=\"left\">New Owner</th><td><select name=\"ename0\">";
				$sql = "SELECT email_name as ename, first_name as fname, last_name as lname 
				FROM timesheet.employee  where email_name!='webmaster' ORDER BY first_name;";
				$result = mysql_query($sql);
				include("err_msg.inc");
				$ename0 = $email_name;
				while (list($ename, $fname, $lname) = mysql_fetch_array($result)) {
					if ($email_name == $ename) {
						$feedback = $feedback."<option selected value=$ename>$fname $lname";
					} else {
						$feedback = $feedback."<option value=$ename>$fname $lname";
					}
				}
				$feedback = $feedback."<option value='RLA'>RLA";
				$feedback = $feedback."<option value='Engineering'>Engineering";
				$feedback = $feedback."</option></select>";
				$feedback = $feedback."</td></tr>";

				$feedback = $feedback."<tr><td colspan=2><td>"
					."<input type=hidden name=barcode value=$barcode></td></tr>";
				$feedback = $feedback."<tr><td colspan=2><td>"
					."<input type=hidden name=entry_id0 value=$entry_id></td></tr>";
				$feedback = $feedback."<tr><td colspan=2><input "
					.' onClick="return (invlocationverify());"'
				   	.' onSubmit="return (invlocationverify());"'
					."type=\"submit\" value=\"Change Ownership\" name=\"ChangeOwneritemid\"></td></tr>";
			}
			if (!$domodify && $itemview == "detail") {
				$feedback = $feedback."<tr><td colspan=2><input type=\"submit\" value=\"Modify This Item\" name=\"modifyitem\"></td></tr>";
			//} elseif (!$domodify && $itemview == "history") {
			}
			$feedback = $feedback."</table></p>";
			echo $feedback;		//itemid
			echo "</form></p>";
			if ($itemview != "history") {
				echo "<hr>";
			}
		} elseif ($action == "edit") {
			##	Item Modification Form
			$process = 0;
			echo "<p><form method=\"post\" method=\"$PHP_SELF\" name=\"invnewitem\">";
			echo "<h2>Inventory Item (No: $itemid) Modification</h2>";
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
			echo "<tr><th align=\"left\">Bar Code</th><td><input type=\"text\" name=\"barcode\" "
				."value=\"$barcode\" size=\"$size\"></td></tr>";
			echo "<tr><th align=\"left\">Description</th><td><input type=\"text\" name=\"biref_description\" "
				."value=\"$biref_description\" size=\"$size\"></td></tr>";
			echo "<tr><th align=\"left\">Location</th><td><input type=\"text\" name=\"location\" "
				."value=\"$location\" size=\"$size\">";
			echo "<input type=\"hidden\" name=\"oldlocation\" "
				."value=\"$location\" size=\"$size\"></td></tr>";

			echo "<tr><th colspan=2><font color=#0000ff size=4>If you know, please fill following fields.</font></th></tr>";
			echo "<tr><th align=\"left\">Model</th><td>New Model<br>"
					."<input type=\"text\" name=\"brandname\" size=\"$size\"><br>Or Select From List<br>";
				include("inv_brand_list.inc");
			echo "</td></tr>";
			echo "<tr><th align=\"left\">Dimension (xyz in cm)</th><td><input type=\"text\" name=\"size_xyz_cm\" "
				."value=\"$size_xyz_cm\" size=\"$size\"></td></tr>";
			echo "<tr><th align=\"left\">Color</th><td><input type=\"text\" name=\"color\"  "
				."value=\"$color\" size=\"$size\"></td></tr>";
			echo "<tr><th align=\"left\">Year Made</th><td><input type=\"text\" name=\"year_made\" "
				."value=\"$year_made\" size=\"$size\"></td></tr>";
			echo "<tr><th align=\"left\">Order No</th><td><input type=\"text\" name=\"order_no\" "
				."value=\"$order_no\" size=\"$size\"></td></tr>";
			echo "<tr><th align=\"left\">Purchasing Date</th><td><input type=\"text\" name=\"purchase_date\" "
				."value=\"$purchase_date\" size=\"$size\"></td></tr>";
			echo "<tr><th align=\"left\">Disposal Date + Reason</th><td><input type=\"text\" name=\"disposal\" "
				."value=\"$disposal\" size=\"$size\"></td></tr>";
			echo "<tr><th align=\"left\">Purchasing Price ($)</th><td><input type=\"text\" name=\"purchasing_price\" "
				."value=\"$purchasing_price\" size=\"$size\"></td></tr>";
			echo "<tr><th align=\"left\">Purchasing by (person)</th><td><input type=\"text\" name=\"purchased_by\" "
				."value=\"$purchased_by\" size=\"$size\"></td></tr>";
			echo "<tr><th align=\"left\">Purchasing for Project</th><td><input type=\"text\" name=\"purchased_for\" "
				."value=\"$purchased_for\" size=\"$size\"></td></tr>";
			include("userstr.inc");
			echo "<input type=\"hidden\" name=\"itemid\" value=\"$itemid\">";
			echo "<input type=\"hidden\" name=\"entry_id\" value=\"$entry_id\">";
			echo "<input type=\"hidden\" name=\"entry_id_old\" value=\"$entry_id\">";
			echo "<tr><td colspan= 2 align=\"center\"><input ";
				echo ' onClick="return (invverify());"';
				echo ' onSubmit="return (invverify());"';
				echo " type=\"submit\" value=\"UPDATE\" name=\"domodify\"></td></tr>";
			echo "</table></p></form></p><hr>";
 		}
 	}
 }
?>
</html>