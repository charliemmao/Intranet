<html>

<head>
<title>Delete Inventory Record</title>
</head>

<body bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG">

<?php
include("admin_access.inc");
include('rla_functions.inc');
echo "<h1	align=center>Delete Inventory Record</h1><br>";
echo '<p align=center><a href="'.$PHP_SELF.$admininfo.'">[Refresh]</a>
	<a href="/'.$phpdir.'/adminctl_top.php'.$admininfo.'">[Admin Main Page]</a><hr>';

if ($delallrcdforoneperson) {
/*
	$email_name = $staffname;
	$sql = "DELETE FROM logging.access_rcd WHERE email_name='$email_name';";
	$result = mysql_query($sql);
	if (mysql_affected_rows() != 0) {
		echo "<h2>".mysql_affected_rows().' records with email name of <font color=#0000ff>'.$email_name.'</font> have been deleted.</h2><hr>';
	} else {
		echo '<h2>No record with email name of <font color=#0000ff>'.$email_name.'</font> has been deleted.</h2><hr>';
	}
*/
} elseif ($delallinventoryrcd) {
	//password
/*
	$out = "";
	$sql	=	"SELECT email_name FROM logging.accesslevel WHERE priviledge='00';";
	include('general_one_val_search.inc');
	if ($password	== $out.'00') {
		$table = "primlist";
		$sql = "DELETE FROM inventory.$table;";
		$result = mysql_query($sql);
		if ($result) {
			$msg = "<h2>All records in $table have been deleted.</h2>";
		} else {
			$msg = "<h2>No records in $table  has been deleted.</h2>";
		}
		$table = "entry_id";
		$sql = "DELETE FROM inventory.$table;";
		$result = mysql_query($sql);
		if ($result) {
			$msg = $msg."<h2>All records in $table have been deleted.</h2>";
		} else {
			$msg = $msg."<h2>No records in $table  has been deleted.</h2>";
		}
		$table = "tracking";
		$sql = "DELETE FROM inventory.$table;";
		$result = mysql_query($sql);
		if ($result) {
			$msg = $msg."<h2>All records in $table have been deleted.</h2><hr>";
		} else {
			$msg = $msg."<h2>No records in $table  has been deleted.</h2><hr>";
		}
		echo $msg;
	} else {
		echo "<h2>Please check your password.</h2><hr>";
	}
*/
} elseif ($deloneinventoryrcd) {
	//inventory_id
	if ((int)($inventory_id) == 0) {
		if ($inventory_id == "") {
			echo '<h2><font color=#ff0000>ID number cannot be empty.</font></h2><hr>';
		} else {
			echo '<h2><font color=#ff0000>'.$inventory_id.'</font> is not a valid ID number.</h2><hr>';
		}
	} else {
		$sql = "DELETE FROM inventory.primlist WHERE entry_id='$inventory_id';";
		$result = mysql_query($sql);
		include("err_msg.inc");
		//echo "$sql.<br>";
		$sql = "DELETE FROM inventory.entry_id WHERE entry_id='$inventory_id';";
		$result = mysql_query($sql);
		//echo "$sql.<br>";
		include("err_msg.inc");
		echo "<h2>Record with entry ID of $inventory_id has been deleted.</h2><hr>";
	}
} elseif ($delgroupinventroyrcds) {
/*
	//idfrom	idto
	if ((int)($idfrom)	<= 0 && (int)($idto)	<= 0) {
		echo '<h2>Both <font color=#ff0000>'.$idfrom.'</font>&nbsp;and&nbsp;<font color=#ff0000>'.$idto.'</font> are not valid ID numbers.</h2><hr>';
	} elseif ((int)($idfrom)	== 0) {
		echo '<h2><font color=#ff0000>'.$idfrom.'</font> is not a valid number.</h2><br>';
	} elseif ((int)($idto)	== 0) {
		echo '<h2><font color=#ff0000>'.$idto.'</font> is not a valid number.</h2><br>';
	} else {
		if ($idfrom > $idto) {
			$i = $idfrom;
			$idfrom = $idto;
			$idto = $i;
		}
		$sql = "DELETE FROM inventory.access_rcd WHERE entry_id>='$idfrom' and entry_id<='$idto';";
		$result = mysql_query($sql);
		if (mysql_affected_rows() != 0) {
			echo "<h2>".mysql_affected_rows().' record (ID: <font color=#0000ff>'.$inventory_id.'</font>) has been deleted.</h2><hr>';
		} else {
			echo '<h2>No record has been deleted.</h2><hr>';
		}
	}
*/
}
?>

<p><table>
<!--
<form method="POST" target="main" action= "<?php echo $PHP_SELF ?>">
    <tr><td colspan=3><b>Delete All Inventory Records</font></b></td>
    <tr><td><b>Password</b></td>
    <td><input type="password" name="password" size="20"></td>
    <td><input type="submit" value="GO" name="delallinventoryrcd"></td></tr>
</form>
<tr><td colspan=3><hr></td></tr>

<form method="POST" target="main" action= "<?php echo $PHP_SELF ?>">
	 <tr><td colspan=3><b>Delete Inventory Record For This Person</b></tr>
    <tr><td><b>Full Name</b></td>
    <?php include("stafflist.inc"); ?>
    <td><input type="submit" value="GO" name="delallrcdforoneperson"></td></tr>
</form>
<tr><td colspan=3><hr></td></tr>

<form method="POST" target="main" action= "<?php echo $PHP_SELF ?>">
	<tr><td colspan=3><b>Delete A Group Inventory Records</b></td></tr>
   <tr><td><b>Inventory Entry ID From</b></td>
    <td colspan=2><input type="text" name="idfrom" size="20" value="<?php echo $idfrom ?>"></td></tr>
    <tr><td><b>Inventory Entry ID To</td>
    <td><input type="text" name="idto" size="20" value="<?php echo $idto ?>"></td>
    <td><input type="submit" value="GO" name="delgroupinventroyrcds"></td></tr>
</form>
<tr><td colspan=3><hr></td></tr>
-->

<form method="POST" target="main" action= "<?php echo $PHP_SELF ?>">
	<tr><td colspan=3><b>Delete One Inventory Record</b></td>
    <tr><td><b>Inventory Entry ID</b></td>
    <td><input type="text" name="inventory_id" size="20" value="<?php echo $inventory_id ?>"></td>
    <td><input type="submit" value="GO" name="deloneinventoryrcd"></td></tr>
</form>
<tr><td colspan=3><hr></td></tr>
</table><p>
</body>
</html>
