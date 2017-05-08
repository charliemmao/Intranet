<html>
<?php
if (mysql_errno()){
	$err = mysql_errno();
	$err = ereg_replace("'", "\'", $err);
	$errsql = ereg_replace("'", "\'", $sql);
	$date = date("Y-m-d h:i:s");
	//include("find_admin_ip.inc");
	if ($priv == "00") {
		echo '<br><b>Error message as follows:<br><font color="#FF0000">'.$err."</font><br>";
		echo "<br>Query Statement:<br>$sql<br>";
	} else {
		echo '<h2><font color="#FF0000">Sorry, MySQL query error, 
		process has been aborted.<br>Please contact system administrator.</font></h2><br>';
		include("connet_root_once.inc");
		$sqllog = "INSERT INTO logging.sqlerrlog VALUES('null', '$email_name', '$filelog', 
		'$linelog', '$errsql', '$err', '$date');";
		$result = mysql_query($sqllog);
	}
	exit;
}
?>
</html>