<html>

<head>
<title></title>
</head>

<body bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG">

<?php
include("admin_access.inc");
include('rla_functions.inc');

echo "<h2 align=center>Delete Logging Record.</h2><br>";
echo '<p align=center><a href="admin_delete_logging.php'.$admininfo.'">[Refresh]</a>
	<a href="/'.$phpdir.'/adminctl_top.php'.$admininfo.'">[Admin Main Page]</a> 
	<hr>';

include("connet_root_once.inc");
if ($dellogrcdwithename) {	
	$email_name = $staffname;
	$sql = "DELETE FROM logging.access_rcd WHERE email_name='$email_name';";
	$result = mysql_query($sql);
	if (mysql_affected_rows() != 0) {
		echo "<h2>".mysql_affected_rows().' records with email name of <font color=#0000ff>'.$email_name.'</font> have been deleted.</h2><hr>';
	} else {
		echo '<h2>No record with email name of <font color=#0000ff>'.$email_name.'</font> has been deleted.</h2><hr>';
	}
} elseif ($delalllogrcd) {
	//password
	$sql	=	"SELECT email_name FROM logging.accesslevel WHERE priviledge='00';";
	include('general_one_val_search.inc');
	if ($password	== $out.'00') {
		$sql = "DELETE FROM logging.access_rcd;";
		$result = mysql_query($sql);
		if ($result) {
			echo "<h2>All records have been deleted.</h2><hr>";
		} else {
			echo '<h2>No records has been deleted.</h2><hr>';
		}
	} else {
		echo "<h2>Please check your password.</h2><hr>";
	}
} elseif ($dellogrcdwithid) {	
	//process_id
	if ((int)($process_id)	== 0) {
		echo '<h2><font color=#ff0000>'.$process_id.'</font> is not a valid ID number.</h2><hr>';
	} else {
		$sql = "DELETE FROM logging.access_rcd WHERE entry_id='$process_id';";
		$result = mysql_query($sql);
		if (mysql_affected_rows() != 0) {
			echo "<h2>".mysql_affected_rows().' record (ID: <font color=#0000ff>'.$process_id.'</font>) has been deleted.</h2><hr>';
		} else {
			echo '<h2>No record has been deleted.</h2><hr>';
		}
	}
} elseif ($delgrplogrcd) {
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
		$sql = "DELETE FROM logging.access_rcd WHERE entry_id>='$idfrom' and entry_id<='$idto';";
		$result = mysql_query($sql);
		if (mysql_affected_rows() != 0) {
			echo "<h2>".mysql_affected_rows().' record (ID: <font color=#0000ff>'.$process_id.'</font>) has been deleted.</h2><hr>';
		} else {
			echo '<h2>No record has been deleted.</h2><hr>';
		}
	}
}
?>

<p><table>
<form method="POST" target="main" action= "<?php echo $PHP_SELF ?>">
	 <tr><td colspan=3><b>Delete Logging Record For This Person</b></tr>
    <tr><td><b>Full Name</b></td>
    <?php include("stafflist.inc"); ?>
    <td><input type="submit" value="GO" name="dellogrcdwithename"></td></tr>
</form>
<tr><td colspan=3><hr></td></tr>

<form method="POST" target="main" action= "<?php echo $PHP_SELF ?>">
    <tr><td colspan=3><b>Delete All Logging Records</font></b></td>
    <tr><td><b>Password</b></td>
    <td><input type="password" name="password" size="20"></td>
    <td><input type="submit" value="GO" name="delalllogrcd"></td></tr>
</form>
<tr><td colspan=3><hr></td></tr>

<form method="POST" target="main" action= "<?php echo $PHP_SELF ?>">
	<tr><td colspan=3><b>Delete A Group Logging Records</b></td></tr>
   <tr><td><b>Logging Entry ID From</b></td>
    <td colspan=2><input type="text" name="idfrom" size="20" value="<?php echo $idfrom ?>"></td></tr>
    <tr><td><b>Logging Entry ID To</td>
    <td><input type="text" name="idto" size="20" value="<?php echo $idto ?>"></td>
    <td><input type="submit" value="GO" name="delgrplogrcd"></td></tr>
</form>
<tr><td colspan=3><hr></td></tr>


<form method="POST" target="main" action= "<?php echo $PHP_SELF ?>">
	<tr><td colspan=3><b>Delete One Logging Record</b></td>
    <tr><td><b>Logging Entry ID</b></td>
    <td><input type="text" name="process_id" size="20" value="<?php echo $process_id ?>"></td>
    <td><input type="submit" value="GO" name="dellogrcdwithid"></td></tr>
</form>
<tr><td colspan=3><hr></td></tr>
</table>
</body>
</html>
