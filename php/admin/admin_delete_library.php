<html>

<head>
<title>Delete Library Record</title>
</head>

<body bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG">

<?php

include("admin_access.inc");
include('rla_functions.inc');
echo "<h1	align=center>Delete Library Record</h1><br>";
echo '<p align=center><a href="'.$PHP_SELF.$admininfo.'">[Refresh]</a>
	<a href="/'.$phpdir.'/adminctl_top.php'.$admininfo.'">[Admin Main Page]</a><hr>';
?>	
<p><table>
<form method="POST" target="main" action= "<?php echo $PHP_SELF ?>">
	 <tr><td colspan=3><b>Delete Library Record(s)</b></tr>
    <tr><td><b>Library Item ID</b></td><td><input type=text name="lib_item_id" 
    value="<?php echo $lib_item_id ?>" size=10> For a range, separated two number by "@".</td>
    <td><input type="submit" value="GO" name="delonerecord"></td></tr>
</form>

<!--
<form method="POST" target="main" action= "<?php echo $PHP_SELF ?>">
    <tr><td colspan=3><b>Delete All Library Records</font></b></td>
    <tr><td><b>Password</b></td>
    <td><input type="password" name="password" size="20"></td>
    <td><input type="submit" value="GO" name="del_all_lib_rcd"></td></tr>
</form>
-->

</table><hr>

<?php
if ($del_all_lib_rcd) {
	//password
	$out = "";
	$sql	=	"SELECT email_name FROM logging.accesslevel WHERE priviledge='00';";
	include('general_one_val_search.inc');
	if ($password	== $out.'00') {
		$table_list[] = "author";
		$table_list[] = "lib_primlist";
		$table_list[] = "prim_auth";
		$table_list[] = "prim_keyword";
		$table_list[] = "lib_entry";
		$table_list[] = "for_book";
		$table_list[] = "for_patent";//
		$table_list[] = "technotes";
		/*	
		$table_list[] = "for_journal";
		$table_list[] = "for_article";
		$table_list[] = "for_report";
		$table_list[] = "for_other";
		//*/
		$table_list[] = "keywords";

		for ($i=0; $i<count($table_list); $i++) {
			$table = $table_list[$i];
			$sql = "DELETE FROM library.$table;";
			$result = mysql_query($sql);
			if ($result) {
				$msg = "All records in $table have been deleted.<br>";
			} else {
				$msg = "No records in $table has been deleted.<br>";
			}
			echo $msg;
		}
	} else {
		echo "<h2>Please check your password.</h2><hr>";
	}
} elseif ($delonerecord) {
	$tmp = ereg_replace("@","",$lib_item_id);
	if ($tmp == $lib_item_id) {
		$idfrom = $lib_item_id;
		$idto = $lib_item_id;
	} else {
		$tmp = explode("@",	$lib_item_id);
		$idfrom = trim($tmp[0]);
		$idto = trim($tmp[1]);
	}
	echo "Delete Library Records From $idfrom to $idto<br>";
for ($lib_item_id=$idfrom; $lib_item_id<=$idto; $lib_item_id++) {
	echo "<b>Delete LIB ITEM ID $lib_item_id<b><br>";
	$sql = "SELECT cat_id FROM library.lib_primlist WHERE lib_item_id='$lib_item_id';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($cat_id) = mysql_fetch_array($result);
	if (!$cat_id) {
		echo "<b>No record has a Library Item ID of <font color=#ff0000>$lib_item_id</font>.</b><br>";
		exit;
	}
	
	$sql = "SELECT category FROM library.library_cat WHERE cat_id='$cat_id';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($category) = mysql_fetch_array($result);

####################### Delete record
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
	
	if ($category == "book") {
		$sql = "DELETE FROM library.for_book WHERE lib_item_id='$lib_item_id';";
		$result = mysql_query($sql);
		include("err_msg.inc");
		echo $sql."<br>";
	} elseif ($category == "patent") {
		$sql = "DELETE FROM library.for_patent WHERE lib_item_id='$lib_item_id';";
		$result = mysql_query($sql);
		include("err_msg.inc");
		echo $sql."<br>";
	} elseif ($category == "technotes") {

	}
	echo "<b>Library Item ID of <font color=#0000ff>$lib_item_id</font> is a ".
		"<font color=#0000ff>$category</font>. It has been deleted from database.</b><br>";
}
}
?>

</body>
</html>
