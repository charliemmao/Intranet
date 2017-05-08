<html>
<?php	
	include('connet_root_once.inc');
	mysql_select_db("mysql");
	$sql = 'update user set password=password("'.$userpwd.'") where user="'.$user.'";';
	//echo $sql.'<br>';
	$result	=	mysql_query($sql,$contid);
	include('err_msg.inc');
	
	$result	=	mysql_query("FLUSH PRIVILEGES",$contid);
	include('err_msg.inc');
	mysql_close($contid);
	//echo $sql.'<br>';
?>
</html>