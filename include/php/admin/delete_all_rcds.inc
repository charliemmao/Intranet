<html>
<?php
	$user	=	"root";
	$pwd	=	$user."reslab";
	$host	=	"localhost";
	$contid 	= 	mysql_pconnect($host,$user,$pwd);
	include('mysql_conn.inc');
	$sql = "DELETE FROM $table_to_delete";
	$result	=	mysql_query($sql,$contid);
	if ($result) {
		echo '<H4><font color=#0000ff>All records in Table "'.strtoupper($table_to_delete).'" have been deleted.</font></H4>';
	} else {
		echo '<H4><font color=#0000ff>Failed to delete records in Table "'.strtoupper($table_to_delete).'".</font></H4>';
		exit;
	}
?>
</html>
