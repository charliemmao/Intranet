<html>
<?php
	include("connet_root_once.inc");		
	$lastaccess = date("Y-m-d H:i:s");
	$sql = "UPDATE logging.logout SET lastaccess='$lastaccess' WHERE email_name='$email_name';";
	$result = mysql_query($sql);
	include("err_msg.inc");
?>
</html>