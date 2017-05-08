<html>

<head>
<title>Inventry modification</title>
</head>
<body leftmargin="20" bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG">

<?php
//js
include('inventory_verify.inc');
include('str_decode_parse.inc');
include("rla_functions.inc");
$process = 1;
include("inv_cat_list.inc");
include("inv_brand_list.inc");
include("inv_manf_list.inc");
include("userinfo.inc"); //$userinfo
$userstr	=	"?".base64_encode($userinfo);
echo "<h2 align=center><a id=top>Inventory Item Modification</a>";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[Refresh]</font></a></h2><hr>";
/* table: entry_id. columns: entry_id, email_name, computer_ip_addr, yyyymmdd */
/* table: primlist. columns: 
itemid, 				catid, 			brandid, 			manid, 		biref_description, 
barcode, 				size_xyz_cm, 		color, 			year_made, 	order_no, 
purchase_date, 		disposal, 			purchasing_price, 	purchased_by, 	
purchased_for, 	entry_id */
/* table: category. columns: catid, cat_name, */
/* table: brandname. columns: brandid, brand_name, */
/* table: manufacture. columns: manid, manufacture, */
/* table: tracking. columns: id, itemid, barcode, email_name, location, yyyymmdd, entry_id, */

##	Change ownership
if ($chownitemid || $chownbarcode) {
	$mybarcode = $barcode;
	if ($chownbarcode) {
		include('connet_other_once.inc');
		$sql = "SELECT itemid from inventory.primlist where barcode like '%$barcode%';";
		//echo "$sql .<br>";
		$filelog = __FILE__;
		$linelog = __LINE__;
		$result = mysql_query($sql);
		if (mysql_num_rows($result)) {
			list($itemid) = mysql_fetch_array($result);
			//echo "barcode $barcode.<br>";
			//echo "itemid $itemid.<br>";
		} else {
			echo "<h2>Item with barcode \"$barcode\" has not been found.</h2><hr>";
			$itemid = "";
			$msg = 0;		
		}
	} else {
		//echo "itemid $itemid.<br>";
	}

	if ($itemid) {
		$action = "noedit";
		$domodify = 1;
		$changeowner = 1;
		echo "<h2>Item details as follows:</h2>";
		include("inv_getoneitem.inc");
		$domodify = 0;
	} else {
		if ($msg != 0) {
			echo "<h2>Please enter correct \"Item ID\" or \"Barcode\".</h2><hr>";
		}
	}
}
if ($ChangeOwneritemid) {
	//step: 1 check ownership
	/* table: tracking. columns: id, itemid, barcode, email_name, location, yyyymmdd, entry_id, */
	$sql = "SELECT id, location as loc, yyyymmdd from inventory.tracking where itemid='$itemid' and "
		."email_name='$ename0' order by id desc limit 1;";
	//echo $sql."<br>";
	include("connet_other_once.inc");
	$result = mysql_query($sql);
	//echo mysql_error()."<br>";
	if (mysql_num_rows($result)) {
		list($id, $loc, $yyyymmdd) = mysql_fetch_array($result);
		//echo "tracking id=$id loc=$loc<br>";
	}

	//step: 2 change ownership?
	$temp = '';
	if ($location != $loc) {
		##	insert data to inventory.tracking.
		/* table: tracking. columns: id, itemid, barcode, email_name, location, yyyymmdd, entry_id, ownnow*/
		$sql = "UPDATE inventory.tracking SET ownnow='N' ".
			"WHERE itemid='$itemid' and barcode='$barcode';";
		$result = mysql_query($sql);
		$filelog = __FILE__;
		$linelog = __LINE__;
		include("err_msg.inc");
		
		$yyyymmdd = date("Ymd");
		if ($barcode) {
			$barcode = rlabarcode($barcode);
		}
		$sql = "INSERT INTO inventory.tracking VALUES("
			."'NULL','$itemid', '$barcode', '$ename0', '$location', '$yyyymmdd', '$entry_id0', 'Y');";
		//echo $sql."<br>";
		$result	=	mysql_query($sql);
		if ($result) {
			$temp = 'y';
			$action	=	"Inv item owner: $itemid.";
			include('logging.inc');
		} else {
			$temp = 'n';
		}
	}

	//step: 3 message
	if ($temp = 'y') {
		echo "<h2><font color=#0000ff>The ownership of item \"$itemid\" has been changed to $ename0 successfully.</font></h2>";
	} elseif ($temp = 'n') {
		echo "<h2><font color=#ff0000>The ownership of item \"$itemid\" has been failed to change.</font></h2>";
	} else {
		$t	= substr($yyyymmdd,0,4)."-".substr($yyyymmdd,4,2)."-".substr($yyyymmdd,6,2);
		echo "<h2>This item \"$itemid\" is already under your name since $t, no change has been made.</font></h2>";
	}
	echo "<hr>";
}

##	View Item
if ($viewitemid) {
	//echo "test itemid $itemid<br>";
	if ($itemid != 0) {
		if ($itemview == "history") {
			echo "<h2>Item \"<font color=#0000ff>$itemid</font>\" details:</h2>";
		}
		$action = "noedit";
		include("inv_getoneitem.inc");
		
		if ($itemview == "history") {
			$itemid = $itemid_save;
			echo "<h2>Item \"<font color=#0000ff>$itemid</font>\" history:</h2>";
			/* table: tracking. columns: id, itemid, barcode, email_name, location, yyyymmdd, entry_id, */
			$sql = "select id, email_name as ename, location, yyyymmdd from inventory.tracking where itemid='$itemid'"
				." order by id desc;";
			include('connet_other_once.inc');
			$result = mysql_query($sql);
			if (mysql_num_rows($result)) {
				echo "<p><table border=1>";
				echo "<tr><th>Owner</th><th>Location</th><th>Date</th></tr>";
				while (list($id, $ename, $location, $yyyymmdd) = mysql_fetch_array($result)) {
					$t	= substr($yyyymmdd,0,4)."-".substr($yyyymmdd,4,2)."-".substr($yyyymmdd,6,2);
					echo "<tr><td>$ename</td><td>$location</td><td>$t</td></tr>";
				}
				echo "</table>";
			}
			echo "<hr>";
		}
	}
}

##	Show Modification Form
if ($modifyitem) {
	$action = "edit";
	include("inv_getoneitem.inc");
}

if ($domodify) {
	include('connet_other_once.inc');
	include('inv_get_all_id_name.inc');
	include("inv_trim.inc");
	$sql = "UPDATE inventory.primlist SET "
		."catid='$catid1', brandid='$brandid1', manid='$manid1', biref_description='$biref_description', "
		."barcode='$barcode', size_xyz_cm='$size_xyz_cm', color='$color', year_made='$year_made', order_no='$order_no', "
		."purchase_date='$purchase_date', disposal='$disposal', purchasing_price='$purchasing_price', purchased_by='$purchased_by', "
		."purchased_for='$purchased_for' where itemid='$itemid';";
	//echo $sql."<br>";
	$result	=	mysql_query($sql);
	include('err_msg.inc');

	if ($result) {
		$action	=	"Inv item mod: $itemid.";
		include('logging.inc');
		$itemupdate = 1;
		if ($oldlocation != $location) {
			$yyyymmdd = date("Ymd");
			$sql = "INSERT INTO inventory.tracking VALUES("
				."'NULL','$itemid', '$barcode', '$ename0', '$location', '$yyyymmdd', '$entry_id_old');";
			//echo $sql."<br>";
			//exit;
			$result	=	mysql_query($sql);
			if ($result)	{
				$msg = "owner update";
				$action	=	"Inv item owner: $itemid.";
				include('logging.inc');
			}
		}
	}
		
	if ($itemupdate == 1)	{
		echo "<h2>Item update successful.</h2>";
		$action = "noedit";
		include("inv_getoneitem.inc");
	} else {
		echo "<h2><font color=#ff0000>Item update failed.</font></h2>";
	}
}

###############################################################
################ Form section
###############################################################
##	Form 1: always shown
echo "<p><table border=0>
  <td>";
echo "<form method=\"post\" method=\"$PHP_SELF\">";
include("userstr.inc");
$b1 = "<font color=#0000ff><b>";
$b2 = "</b></font>";
$b1s = "<font size=2><b>";
$b2s = "</b></font>";
echo "<tr><td colspan=2>$b1"."Update My Item Details$b2</td></tr>";
echo "<tr><td>$b1s"."My Item List$b2s</td><td>";
	/* table: tracking. columns: id, itemid, barcode, email_name, location, yyyymmdd, entry_id, */
	include("connet_other_once.inc");	//connet_root_once.inc	connet_other_once.inc
	$sql	=	"SELECT itemid FROM inventory.tracking where email_name='$email_name' order by itemid desc;";
	//echo $sql."<br>";
	$result = mysql_query($sql,$contid);
	if (mysql_error()) {
		echo 'MySQL query error'.': '.mysql_error();
	}
	if (mysql_num_rows($result)) {
		//echo "No: ".mysql_num_rows($result)."<br>";
	}
	if (mysql_num_rows($result)) {
		$firstlist = 0;
		while (list($itemid) = mysql_fetch_row($result)) {
			if ($itemid0 != $itemid) {
				$uniqitemid[$firstlist] = $itemid;
				$firstlist = $firstlist + 1;
			}
			$itemid0 = $itemid;
		}
		//echo "No of item under your name: ".$firstlist."<br>";
		$nomylist = 0;
		for ($i=0; $i<$firstlist; $i++) {
			$itemidserch = $uniqitemid[$i];
			$sql	=	"SELECT t1.itemid as itemid1, t1.catid as catid1, t1.brandid as brandid1, "
				."t2.id as id1, t2.email_name as ename "
				."FROM inventory.primlist as t1, inventory.tracking as t2 "
				."WHERE t1.itemid=t2.itemid and t2.itemid='$itemidserch' order by id1 desc limit 1;";
			//echo $sql."<br>";
			//echo "itemidserch $itemidserch.<br>";
			$result1 = mysql_query($sql,$contid);
			if (mysql_error()) {
				echo 'MySQL query error'.': '.mysql_error();
			}
			if (mysql_num_rows($result1)) {
				//echo "Number entry for item $itemidserch: ".mysql_num_rows($result1)."<br>";
			}
			while (list($itemid1, $catid1, $brandid1, $id1, $ename) = mysql_fetch_row($result1)) {
				//echo "Eentry ID for item $itemidserch in tracking table: $id0; itemid: $itemid1; ename: $email_name<br>";
				if ($id0 != $itemid1 && $ename == $email_name) {
					$itemidlist[$nomylist][0] = $itemid1;
					$itemidlist[$nomylist][1] = $catid1;
					$itemidlist[$nomylist][2] = $brandid1;
					//$itemidlist[$nomylist][3] = $id1;
					//$itemidlist[$nomylist][4] = $ename;
					$nomylist = $nomylist + 1;
					//echo "Eentry ID for item $itemidserch in tracking table: $id1.<br>";
				}
				$id0 = $itemid1;
			}
		}
	}

	echo "<select name=\"itemid\" size=1>";
	if ($nomylist !=0) {
 		echo "<option value=\"0\">---Select one---";
 		for ($i=0; $i<$nomylist; $i++) {
			$itemid1 = $itemidlist[$i][0];
			$catid1 = $itemidlist[$i][1];
			$brandid1 = $itemidlist[$i][2];
			//$id1 = $itemidlist[$i][3];
			//$ename = $itemidlist[$i][4] ;
 			if ($itemid_save  == $itemid1) {
 				echo "<option selected value=\"$itemid1\">$catlist[$catid1]: $brandlist[$brandid1]";
 			} else {
 					echo "<option value=\"$itemid1\">$catlist[$catid1]: $brandlist[$brandid1]";
 			}
 		}
 	} else {
 		echo "<option value=\"0\">---Nothing---";
 	}
 	echo "</select>";
echo "</td><td>$b1s"."View Details$b2s";
if (($itemview != "detail" && $itemview != "history") || $itemview == "detail") {
	echo "<input type=\"radio\" name=\"itemview\" checked value=\"detail\">$b1s"." or History$b2s";
	echo "<input type=\"radio\" name=\"itemview\" value=\"history\">";
} else {
	echo "<input type=\"radio\" name=\"itemview\" value=\"detail\"> Detail or";
	echo "<input type=\"radio\" name=\"itemview\" checked value=\"history\"> History";
}
echo "<br><input type=\"submit\" name=\"viewitemid\" value=\"Submit\"></td></tr><td>";
echo "</form><p><form method=\"post\" method=\"$PHP_SELF\">";
include("userstr.inc");
//echo "<tr><td colspan=2>&nbsp;</td><td></tr>";
$t = strtoupper($email_name);
echo "<tr><td colspan=2>$b1"."Change Item Ownership$b2</b></td><td></tr>";
echo "<tr><td>$b1s"."Barcode$b2s</td><td>
	<input type=\"text\" name=\"barcode\" value=\"$mybarcode\" size=10>
	<font color=#ff0000 size=2><b>(minimum 4 digits)</b></font></td><td>
	<input type=\"submit\" name=\"chownbarcode\" value=\"Find By Barcode\"></td></tr>";
echo "<tr><td>$b1s"."Item ID$b2s</td><td>
	<input type=\"text\" name=\"itemid\" value=\"$itemid_save\" size=10></td><td>
	<input type=\"submit\" name=\"chownitemid\" value=\"Find By ID\"></td></tr>";
echo "</form></p></table>";
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
</body>
