<html>
<head>
<title>Patent Duplication Check</title>
</head>
<body leftmargin="20" bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG">

<?php
include("admin_access.inc");
include('rla_functions.inc');
include("connet_root_once.inc");

echo "<id=top><h2 align=center>Patent Duplication Check</h2>";

$startstr = "<p align=center>Processing start at: ";
$startstr = $startstr.date('d/m/Y h:i:s');
echo "$startstr<hr>";

############	Find out patent entries to be processed
$sql = "SELECT * FROM library.dumppatent where processed='n'";
$result = mysql_query($sql);
$filelog = __FILE__;
$linelog = __LINE__;
include("err_msg.inc");
$notoprocess = mysql_num_rows($result);
if ($notoprocess == 0) {
	echo "<h3>All Records in Buffer Database Table <font color=#0000ff>library.dumppatent</font> have been processed.</h3>";
	exit;
} else {
	echo "<h3>Number of Entry in Table <font color=#0000ff>library.dumppatent</font>: $notoprocess.</h3>";
}
	
############	process
$cat_id = 2; //patent
$new1 = "<font color=#ff0000>";
$new2 = "</font>";
$sql = "SELECT id, patent_no, electronic_copy FROM library.dumppatent 
		WHERE processed='N';";
//echo "$sql<br>";
$result0 = mysql_query($sql);
$filelog = __FILE__;
$linelog = __LINE__;
include("err_msg.inc");
$NoDup=0;
$noerr=0;
while (list($id, $patent_no, $electronic_copy) = mysql_fetch_array($result0)) {
	if ($electronic_copy == "y") {
		$elec_new = "$patent_no.pdf";
	} else {
		$elec_new = "---";
	}
	
	$patent_no2 = substr($patent_no, 4, strlen($patent_no));
	# step 1: find out whether this patent is already in DB
	$sqlduplicate = "SELECT lib_item_id as libid, patent_no as patno, elec_copy_add as eladd
		FROM library.for_patent WHERE patent_no like '%$patent_no2%';";
	//echo "$sqlduplicate<br>";
	
	$resultduplicate = mysql_query($sqlduplicate);
	list($libid, $patno, $eladd) = mysql_fetch_array($resultduplicate);
	if ($libid) {
		$NoDup++;
		if (!$eladd) {
			$eladd = "---";
		}
		echo "<h4>Patent: $NoDup/$id (Library ID:	 $libid)</h4>";
		echo "<table border=1><tr><th>Item</th><th>New Data</th><th>Old Data</th></tr>";
		echo "<tr><td>Patent NO</td><td>$patent_no</td><td>$patno</td></tr>";
		echo "<tr><td>PDF File</td><td>$elec_new</td><td>$eladd</td></tr>";
		if ($patent_no != $patno || $elec_new != $eladd) {
			$noerr++;
			echo "<tr><td colspan=3><font color=#ff0000>Check Data ($noerr)</font></td></tr>";
		}
		echo "</table><p>";
		
##############################################################
####################### Delete record
		$lib_item_id = $libid;
	$sql = "DELETE FROM library.lib_primlist WHERE lib_item_id='$lib_item_id';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	echo $sql."<br>";
	
	$sql = "DELETE FROM library.prim_keyword WHERE lib_item_id='$lib_item_id';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	echo $sql."<br>";

	$sql = "DELETE FROM library.prim_auth WHERE lib_item_id='$lib_item_id';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	echo $sql."<br>";
	
	$sql = "DELETE FROM library.lib_entry WHERE lib_item_id='$lib_item_id';";
	$result = mysql_query($sql);
	include("err_msg.inc");	
	echo $sql."<br>";
	
	$sql = "DELETE FROM library.for_patent WHERE lib_item_id='$lib_item_id';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	echo $sql."<br>";
	echo "<b>Library Item ID of <font color=#0000ff>$lib_item_id</font> has been deleted from database.</b><br>";
##############################################################
	}
	//exit;
}
echo "<hr><h2>Total Duplicates: $NoDup</h2>";
echo "<b>$startstr<br><p align=center>Processing end at: ";
echo date('d/m/Y h:i:s');
echo "</b>";

echo "<hr><a href=#top>Back to top</a><br><br>";
?>
</body>
