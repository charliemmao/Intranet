<html>
<?php
	if ($email_name != "") {
		include("connet_root_once.inc");
		$lastaccess = date("Y-m-d H:i:s");
		$sqlaccess = "UPDATE logging.logout SET lastaccess='$lastaccess' WHERE email_name='$email_name';";
		$resultaccess = mysql_query($sqlaccess);
		include("err_msg.inc");
		//mysql_close($contid);
	}
?>
</html>