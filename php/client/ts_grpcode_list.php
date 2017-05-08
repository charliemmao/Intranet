<html>
<body background="rlaemb.JPG" leftmargin="20">
<?php
	include("connet_root_once.inc");
	$sql = "SELECT codehead_id, code_prefix, codelable FROM timesheet.code_prefix ORDER BY code_prefix;";
	$result = mysql_query($sql);
	if ($result) {
		$i=0;
		echo "<h1 align=center>Project Code Group List</h1>";
		echo "<table border=1><tr><th>No</th><th>Group Code</th><th>Group Description</th></tr>";//<th>No</th>
		while (list($codehead_id, $code_prefix, $codelable) = mysql_fetch_array($result)) {
			$brief_code = ereg_replace("__", "&", $brief_code);
			$brief_code = ereg_replace("_", " ", $brief_code);
			$i++;
			echo "<tr><td>$i</td><td><b>$code_prefix</b></td><td>$codelable</td></tr>";//<th>$projcode_id</th>
		}
		echo "</table><br><br>";
	} else {
		echo "<h2 align=center>No project code has been found.</h2>";
	}
	mysql_close();
?>
</html>
