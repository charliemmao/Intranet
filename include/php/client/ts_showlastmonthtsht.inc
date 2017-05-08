<html>
<?php
	include("connet_root_once.inc");
	$sql = "SELECT description FROM logging.sysmastertable WHERE item='listLMTS';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($description) = mysql_fetch_array($result);
	$description = strtoupper($description);

	if ($description == "N" && date("j") <=4) {
		$sql = "UPDATE logging.sysmastertable SET description='Y' ".
			"WHERE item='listLMTS';";
		$result = mysql_query($sql);
		include("err_msg.inc");
	}
?>
</html>