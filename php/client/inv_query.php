<html>

<head>
<title>Inventry Query</title>
</head>
<body leftmargin="20" bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG">
<script language=javascript>
function changeform(){
var srcval;
	srcval = document.inventorylist.listtype.value;
	//window.alert(srcval);
	Person.style.display = "none";
	//Loaction.style.display = "none";
	Category.style.display = "none";
	Manufacturer.style.display = "none";
	
	if (srcval == "Person") {
		Person.style.display = "";
	} else if (srcval == "Loaction") {
		//Loaction.style.display = "";
	} else if (srcval == "Category") {
		Category.style.display = "";
	} else if (srcval == "Manufacturer") {
		Manufacturer.style.display = "";
	}
}
</script>

<?php
include('str_decode_parse.inc');
if ($priv	==	'00' || $priv	==	'10') { //
} else {
	echo "<h2 align=center>You don't have permission to view this page.</h2>";
	exit;
}
include("connet_other_once.inc");
$process = 1;
include("inv_brand_list.inc");
include("userinfo.inc"); //$userinfo
$userstr	=	"?".base64_encode($userinfo);
echo "<h2 align=center><a id=top>Inventory Item Query</a>";
echo "<a href=\"$PHP_SELF$userstr\"><font size=2>[Refresh]</font></a></h2><hr>";
echo "<form method=post action=\"$PHP_SELF\" name=inventorylist>";
include("userstr.inc");
echo "<table>";
	$sql = "SELECT itemid FROM inventory.primlist";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result); 
	echo "<tr><th align=left valign=top>Number of Items</th>
		<th align=left><font color=#0000ff>$no</font></th></tr>";

	$i=0;
	$list[$i] = "Person"; $i++;
	$list[$i] = "Category"; $i++;
	$list[$i] = "Manufacturer"; $i++;
	//$list[$i] = "Loaction"; $i++;
	echo "<tr><th align=left valign=top>Search By</th><td>
		<select name=\"listtype\" onchange=\"changeform();\">";
		if (!$listtype) {
			$listtype = $list[0];
		}
		for ($j=0; $j<$i; $j++) {
			if ($listtype == $list[$j]) {
				echo "<option value=\"$list[$j]\" selected>$list[$j]";
			} else {
				echo "<option value=\"$list[$j]\">$list[$j]";			
			}
		}
	echo "</option></select></td></tr>";
	
	if ($listtype == "Category") {
		$style = "id=Category ";
	} else {
		$style = "id=Category style=\"display: none\"";
	}
	$sql = "SELECT catid, cat_name FROM inventory.category ORDER BY cat_name;";  
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	echo "<tr $style><th align=left valign=top>Category ($no)</th><td><select name=\"catid0\">";
		while (list($catid, $cat_name) = mysql_fetch_array($result)) {
			$catlist[$catid] = $cat_name;
			$sql1 = "SELECT itemid FROM inventory.primlist WHERE catid='$catid';";
			$result1 = mysql_query($sql1);
			include("err_msg.inc");
			$no1 = mysql_num_rows($result1);
			if ($catid0 == $catid) {
				echo "<option value=\"$catid\" selected>$cat_name ($no1)";
			} else {
				echo "<option value=\"$catid\">$cat_name ($no1)";
			}
		}
	echo "</option></select></td></tr>";

	if ($listtype == "Manufacturer") {
		$style = "id=Manufacturer";
	} else {
		$style = "id=Manufacturer style=\"display: none\"";
	}
	$sql = "SELECT manid, manufacture FROM inventory.manufacture ORDER BY manufacture;";  
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	echo "<tr $style><th align=left valign=top>Manufacturer ($no)</th><td><select name=\"manid0\">";
		while (list($manid, $manufacture) = mysql_fetch_array($result)) {
			$manlist[$manid] = $manufacture;
			$sql1 = "SELECT itemid FROM inventory.primlist WHERE manid='$manid';";
			$result1 = mysql_query($sql1);
			include("err_msg.inc");
			$no1 = mysql_num_rows($result1);
			if ($manid0 == $manid) {
				echo "<option value=\"$manid\" selected>$manufacture ($no1)";
			} else {
				echo "<option value=\"$manid\">$manufacture ($no1)";
			}
		}
	echo "</option></select></td></tr>";
	
	if ($listtype == "Person") {
		$style = "id=Person";
	} else {
		$style = "id=Person style=\"display: none\"";
	}
	$sql = "SELECT email_name as ename, first_name as fname, last_name as lname FROM timesheet.employee 
		WHERE date_unemployed='0000-00-00' ORDER BY lname;";  
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	if (!$ename0) {
		$ename0 = $email_name;
	}
	echo "<tr $style><th align=left valign=top>Staff List ($no)</th><td><select name=\"ename0\">";
		while (list($ename, $fname, $lname) = mysql_fetch_array($result)) {
			$stafflist[$ename] = "$lname, $fname";
			$sql1 = "SELECT entry_id FROM inventory.tracking WHERE email_name='$ename' and ownnow='Y';";
			$result1 = mysql_query($sql1);
			include("err_msg.inc");
			$no1 = mysql_num_rows($result1);
			if ($ename == $ename0) {
				echo "<option value=\"$ename\" selected>$lname, $fname ($no1)";
			} else {
				echo "<option value=\"$ename\">$lname, $fname ($no1)";
			}
		}
	echo "</option></select></td></tr>";

/*
	$sql = "SELECT barcode FROM inventory.tracking WHERE email_name='an' and ownnow='Y' ORDER BY barcode;";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	echo $no."<br>";
	$b1 = "";
	while (list($barcode) = mysql_fetch_array($result)) {
		if ($b1 == $barcode) {
			echo "<b>$barcode</b><br>";
		} else {
			echo "$barcode<br>";
		}
	}
*/
/*
	if ($listtype == "Loaction") {
		$style = "id=Loaction";
	} else {
		$style = "id=Loaction style=\"display: none\"";
	}
	$sql = "SELECT distinct location FROM inventory.tracking ORDER BY location;";  
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	echo "<tr $style><th align=left valign=top>Location ($no)</th><td><select name=\"loaction0\">";
		while (list($location) = mysql_fetch_array($result)) {
			if ($loaction0 == $location) {
				echo "<option value=\"$location\" selected>$location";
			} else {
				echo "<option value=\"$location\">$location";
			}
		}
	echo "</option></select></td></tr>";
*/
	echo "<tr><td colspan=2 align=middle>&nbsp;</td><tr>";
	echo "<tr><td colspan=2 align=middle><button type=\"submit\" name=\"search\">
		<font size=4><b>Search</b></font></button></td></tr>";
echo "</table></form>";

if ($search) {
	echo "<hr>";
	echo "<h2>Search Inventory Batabase By $listtype:<font color=#0000ff> ";
	if ($listtype == "Person") {
		echo $stafflist[$ename0];
		$sql = "SELECT t1.itemid as itemid, t1.barcode as barcode, ".
			"t1.location as location, t1.yyyymmdd as yyyymmdd, ".
			"t2.catid as catid, t2.brandid as brandid, t2.manid as manid, ".
			"t2.biref_description as biref_description ".
			"FROM inventory.tracking as t1, inventory.primlist as t2 ".
			"WHERE t1.email_name='$ename0' and t1.itemid=t2.itemid and t1.ownnow='Y'".
			"ORDER BY barcode DESC;";
	} else	if ($listtype == "Category") {
		echo $catlist[$catid0];
		$sql = "SELECT t1.email_name as ename, t1.location as location, ".
			"t1.itemid as itemid, t1.barcode as barcode, t1.yyyymmdd as yyyymmdd, ".
			"t2.brandid as brandid, t2.manid as manid, ".
			"t2.biref_description as biref_description ".
			"FROM inventory.tracking as t1, inventory.primlist as t2 ".
			"WHERE t2.catid='$catid0' and t1.itemid=t2.itemid and t1.ownnow='Y'".
			"ORDER BY ename;";
	} else	if ($listtype == "Manufacturer") {
		echo $manlist[$manid0];
		$sql = "SELECT t1.email_name as ename, t1.location as location, ".
			"t1.itemid as itemid, t1.barcode as barcode, t1.yyyymmdd as yyyymmdd, ".
			"t2.catid as catid, t2.brandid as brandid, ".
			"t2.biref_description as biref_description ".
			"FROM inventory.tracking as t1, inventory.primlist as t2 ".
			"WHERE t2.manid='$manid0' and t1.itemid=t2.itemid and t1.ownnow='Y'".
			"ORDER BY ename;";
	}
	echo "</font>.</h2>";
	//echo "<br>$sql<br>";

	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	if (!$no) {
		echo "<b><font color=#ff0000>No item has been ound.</font></b><br>";
		echo "<hr><a href=#top>Back to top</a><br><br>";
		exit;
	}
	echo "<br><b>Total <font color=#0000ff>$no</font> item found.</b><br><br>";
	echo "<table border=1>";
	if ($listtype == "Person") {
		echo "<tr><th>Barcode</th><th>Category</th><th>Manufacturer</th><th>Model</th>
			<th>Brief Desc.</th><th>Location</th></tr>"; //<th>Date</th><th>Registration No</th>
		while (list($itemid, $barcode, $location, $yyyymmdd, 
			$catid, $brandid, $manid, $biref_description) = mysql_fetch_array($result)) {
			echo "<tr><td>".$barcode."</td>";
			echo "<td>".$catlist[$catid]."</td>";
			echo "<td>".$manlist[$manid]."</td>";
			$tmp = $brandlist[$brandid];
			if (!$tmp) {
				$tmp = "---";
			}
			echo "<td>".$tmp."</td>";
			echo "<td>".$biref_description."</td>";
			echo "<td>".$location."</td>";
			//echo "<td>".$yyyymmdd."</td>";
			//echo "<td>".$itemid."</td>";
			echo "</tr>";	
		}
	} elseif ($listtype == "Category") {
		echo "<tr><th>Barcode</th><th>Manufacturer</th><th>Model</th>
			<th>Brief Desc.</th><th>Cared By</th><th>Location</th></tr>";
		while (list($ename, $location, $itemid, $barcode, 
			$yyyymmdd, $brandid, $manid, $biref_description) = mysql_fetch_array($result)) {
			echo "<tr><td>".$barcode."</td>";
			echo "<td>".$manlist[$manid]."</td>";
			$tmp = $brandlist[$brandid];
			if (!$tmp) {
				$tmp = "---";
			}
			echo "<td>".$tmp."</td>";
			echo "<td>".$biref_description."</td>";
			echo "<td>".$ename."</td>";
			echo "<td>".$location."</td>";
			echo "</tr>";	
		}
	} elseif ($listtype == "Manufacturer") {
		echo "<tr><th>Barcode</th><th>Category</th><th>Model</th>
			<th>Brief Desc.</th><th>Cared By</th><th>Location</th></tr>";
		while (list($ename, $location, $itemid, $barcode, 
			$yyyymmdd, $catid, $brandid, $biref_description) = mysql_fetch_array($result)) {
			echo "<tr><td>".$barcode."</td>";
			echo "<td>".$catlist[$catid]."</td>";
			$tmp = $brandlist[$brandid];
			if (!$tmp) {
				$tmp = "---";
			}
			echo "<td>".$tmp."</td>";
			echo "<td>".$biref_description."</td>";
			echo "<td>".$ename."</td>";
			echo "<td>".$location."</td>";
			echo "</tr>";	
		}	
	}
	echo "</table><br>";
	
	/*
	$sql = "SELECT id, itemid, barcode, email_name, location, yyyymmdd, entry_id 
FROM inventory.tracking WHERE id='$id' and itemid='$itemid' and 
barcode='$barcode' and email_name='$email_name' and location='$location' and 
yyyymmdd='$yyyymmdd' and entry_id='$entry_id' 
	$sql = "SELECT itemid, catid, brandid, manid, biref_description, barcode, 
size_xyz_cm, color, year_made, order_no, purchase_date, disposal, 
purchasing_price, purchased_by, purchased_for, entry_id FROM inventory.primlist
	*/
}
echo "<hr><a href=#top>Back to top</a><br><br>";
?>
</body>
