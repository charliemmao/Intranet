<html>

<head>
<title>Delete Project Codes Record</title>
</head>

<body bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG">

<?php
	include("find_admin_ip.inc");
	if (getenv("remote_addr") != $adminip) {
		exit;
	}
	$priviledge	=	'00';
	include('allow_to_show.inc');
  	include("connet_root.inc");
	echo "<h2 align=center>Delete Project Codes Record.</h2><br>";
?>
<p align=center><a href="<?php echo $PHP_SELF ?>">[Refresh]</a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="adminctl_top.php">[Back to Administrator's Main Page]</a></p>
<hr>
<?php
include("connet_root_once.inc");
if ($delallprocodercd) {
	//password
	$out = "";
	$sql	=	"SELECT email_name FROM logging.accesslevel WHERE priviledge='00';";
	include('general_one_val_search.inc');
	if ($password	== $out.'00') {
		$table_list[] = "code_prefix";
		$table_list[] = "projcodes";
		$table_list[] = "privatecode";

		for ($i=0; $i<count($table_list); $i++) {
			$table = $table_list[$i];
			$sql = "DELETE FROM timesheet.$table;";
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
} elseif ($dellogrcdwithename) {	
	$email_name = $staffname;
	$sql = "DELETE FROM logging.access_rcd WHERE email_name='$email_name';";
	$result = mysql_query($sql);
	if (mysql_affected_rows() != 0) {
		echo "<h2>".mysql_affected_rows().' records with email name of <font color=#0000ff>'.$email_name.'</font> have been deleted.</h2><hr>';
	} else {
		echo '<h2>No record with email name of <font color=#0000ff>'.$email_name.'</font> has been deleted.</h2><hr>';
	}
} elseif ($dellogrcdwithid) {	
	//process_id
	if ((int)($process_id)	== 0) {
		echo '<h2><font color=#ff0000>'.$process_id.'</font> is not a valid ID number.</h2><hr>';
	} else {
		$sql = "DELETE FROM timesheet.access_rcd WHERE entry_id='$process_id';";
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
		$sql = "DELETE FROM timesheet.access_rcd WHERE entry_id>='$idfrom' and entry_id<='$idto';";
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
    <tr><td colspan=3><b>Delete All Project Codes Records</font></b></td>
    <tr><td><b>Password</b></td>
    <td><input type="password" name="password" size="20"></td>
    <td><input type="submit" value="GO" name="delallprocodercd"></td></tr>
</form>
<tr><td colspan=3><hr></td></tr>

<!--
<form method="POST" target="main" action= "<?php echo $PHP_SELF ?>">
	 <tr><td colspan=3><b>Delete Project Codes Record For This Person</b></tr>
    <tr><td><b>Full Name</b></td>
    <?php include("stafflist.inc"); ?>
    <td><input type="submit" value="GO" name="dellogrcdwithename"></td></tr>
</form>
<tr><td colspan=3><hr></td></tr>

<form method="POST" target="main" action= "<?php echo $PHP_SELF ?>">
	<tr><td colspan=3><b>Delete A Group Project Codes Records</b></td></tr>
   <tr><td><b>Project Codes Entry ID From</b></td>
    <td colspan=2><input type="text" name="idfrom" size="20" value="<?php echo $idfrom ?>"></td></tr>
    <tr><td><b>Project Codes Entry ID To</td>
    <td><input type="text" name="idto" size="20" value="<?php echo $idto ?>"></td>
    <td><input type="submit" value="GO" name="delgrplogrcd"></td></tr>
</form>
<tr><td colspan=3><hr></td></tr>

<form method="POST" target="main" action= "<?php echo $PHP_SELF ?>">
	<tr><td colspan=3><b>Delete One Project Codes Record</b></td>
    <tr><td><b>Project Codes Entry ID</b></td>
    <td><input type="text" name="process_id" size="20" value="<?php echo $process_id ?>"></td>
    <td><input type="submit" value="GO" name="dellogrcdwithid"></td></tr>
</form>
<tr><td colspan=3><hr></td></tr>
-->
</table>
</body>
</html>
