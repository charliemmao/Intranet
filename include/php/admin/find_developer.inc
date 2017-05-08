<html>
<?php
	include("connet_root_once.inc");
	$thisip=getenv("remote_addr");
	$sql = "SELECT details 
        FROM rlafinance.control 
        WHERE controllist='developerIP';";

    $result = mysql_query($sql);
    include("err_msg.inc");
    list($controllist) = mysql_fetch_array($result);
	if ($thisip == $controllist) {
		$sql = "SELECT details 
        FROM rlafinance.control 
        WHERE controllist='developerInfo';";

    	$result = mysql_query($sql);
    	include("err_msg.inc");
    	list($controllist) = mysql_fetch_array($result);
    	$dinfo = explode(";", $controllist);
	} else {
		echo "<h2>You are not allowed to access this server.</H2>";
		exit;
	}
?>
</html>
