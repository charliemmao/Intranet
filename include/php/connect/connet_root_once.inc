<html>
<?php
	$user1	=	"root";
	$pwd1	=	$user1."reslab";
	$host1	=	"localhost";
	$contid 	= 	mysql_connect($host1,$user1,$pwd1);
	include('mysql_conn.inc');
	//$action_log = $user1." (1) ".(int)($contid);
	//include("connet_logging.inc");
?>
</html>
