<html>
<?php
if ($email_name !='' && $logon_name !='') {	
	$user1og	=	"root";
	$pwd1og	=	$user1og."reslab";
	$host1og	=	"localhost";
	$idlog		= 	mysql_connect($host1og,$user1og,$pwd1og);
	include('mysql_conn.inc');
	mysql_select_db("logging");
	
	$entry_id		=	"NULL";
	if ($action =="") {
		$action	=	'logon';
	}
	$sql	=	"INSERT INTO access_rcd VALUES(";
	$sql	=	$sql.$entry_id.",";	
	$sql	=	$sql."'".$email_name."',";	
	$sql	=	$sql."'".$logon_name."',";
	$sql	=	$sql."'".getenv("remote_addr")."',";
	$sql	=	$sql."'".$action."',";
	$sql	=	$sql."'".date("Y-m-d H:i:s")."');";
	//echo $sql.'<br>';
	$result	=	mysql_query($sql,$idlog);
	include('err_msg.inc');
	mysql_close($idlog);
}
?>
<!--entry_id	email_name		logon_name		computer_ip_addr		action		timestamp-->
</html>
