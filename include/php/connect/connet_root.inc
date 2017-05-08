<html>
<?php
if ($myrootid == "") {
	$user	=	"root";
	$pwd	=	$user."reslab";
	$host	=	"localhost";
	
	$contid 	= 	mysql_pconnect($host,$user,$pwd);
	include('mysql_conn.inc');
	$myrootid = $contid;
	/*
	$action_log = $user." ".(int)($contid);
	include("connet_logging.inc");
	//*/
} else {
	$contid = $myrootid;
	//$myid	$myrootid
}
?>
</html>
