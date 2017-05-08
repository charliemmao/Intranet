<html>

<head>
<title></title>
</head>

<?php
	include("connet_root_once.inc");
	mysql_select_db("logging",$contid);
	$sql = "select email_name as out from accesslevel where priviledge='".$priviledge0."' and email_name!='webmaster';";
	//echo $sql.'<br>';
	$result = mysql_query($sql,$contid); 
	include('err_msg.inc');
	list($out) = mysql_fetch_array($result);
	mysql_close();
	//echo 'Result is '.$out.'<br>';
	//exit;
?>
</html>
