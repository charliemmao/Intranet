<html>

<head>
<title>Inventory Data Uniformity</title>
</head>
<body background="rlaemb.JPG" leftmargin="40">

<?php
include('str_decode_parse.inc');
include("rla_functions.inc");
echo "<a id=top><h1 align=center>Inventory Data Uniformity</h1><hr>";

#############special: convert all barcode to 9 characters long################
if ($action == "uniformbarcode") {
	$sql = "SELECT barcode FROM inventory.primlist;";
	include("connet_other_once.inc");
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	$i = 0;
	while (list($barcode) = mysql_fetch_array($result)) {
		$barcode = trim($barcode);
		if (strlen($barcode)<9) {
			if ($barcode) {
				$barcode0 = rlabarcode($barcode);
			}
			echo "$barcode0-$barcode-$i<br>";
			$i++;
			$sql1 = "UPDATE inventory.primlist SET barcode='$barcode0' WHERE barcode='$barcode';";
			$result1 = mysql_query($sql1);
			include("err_msg.inc");
		}
	}
	if ($i>0) {
		echo "<h2>Total $i Barcode in Table primlist have been changed.</h2><br>";
	} else {
		echo "<h2>No Barcode needs to be changed in Table primlist.</h2><br>";
	}

	$sql = "SELECT barcode FROM inventory.tracking;";
	include("connet_other_once.inc");
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	$i = 0;
	while (list($barcode) = mysql_fetch_array($result)) {
		$barcode = trim($barcode);
		if (strlen($barcode)<9) {
			if ($barcode) {
				$barcode0 = rlabarcode($barcode);
			}
			echo "$barcode0-$barcode-$i<br>";
			$i++;
			$sql1 = "UPDATE inventory.tracking SET barcode='$barcode0' WHERE barcode='$barcode';";
			$result1 = mysql_query($sql1);
			include("err_msg.inc");
		}
	}
	if ($i>0) {
		echo "<h2>Total $i Barcode in Table tracking have been changed.</h2><br>";
	} else {
		echo "<h2>No Barcode needs to be changed in Table tracking.</h2><br>";
	}

}

if ($action == "roomno") {
	$sql = "SELECT location FROM inventory.tracking;";
	include("connet_other_once.inc");
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	$i = 0;
	while (list($location) = mysql_fetch_array($result)) {
		$location = trim($location);
		if ($location > 0 && $location <500) {
			$location0 = "Room $location";
			echo "$location0-$location-$i<br>";
			$i++;
			$sql1 = "UPDATE inventory.tracking SET location='$location0' WHERE location='$location';";
			echo $sql1."<br>";
			$result1 = mysql_query($sql1);
			include("err_msg.inc");
		}
	}
	if ($i>0) {
		echo "<h2>Total $i Room Number in Table tracking have been changed.</h2><br>";
	} else {
		echo "<h2>No Room Number needs to be changed in Table tracking.</h2><br>";
	}
}
echo "<hr><br><a href=#top>Back to top</a><br><br>";
?>
</body>
