<html>
<body background="rlaemb.JPG" leftmargin="20">
<?php
	include("connet_root_once.inc");
	$today = date("Y-m-d");
	$sql = "SELECT projcode_id, brief_code, description FROM timesheet.projcodes "
		."WHERE  end_date='0000-00-00' || end_date>$today order by brief_code;";
	if ($priv == "00") {
		echo "$sql<br>";
	}
	$result = mysql_query($sql);
	if ($result) {
		$i=0;
		echo "<h1 align=center>Project Code List</h1>";
		echo "<table border=1><tr><th>No</th><th>Code</th><th>Description</th></tr>";//<th>No</th>
		while (list($projcode_id, $brief_code, $description) = mysql_fetch_array($result)) {
			$brief_code = ereg_replace("__", "&", $brief_code);
			$brief_code = ereg_replace("_", " ", $brief_code);
			$i++;
			echo "<tr><td>$i</td><td><b>$brief_code</b></td><td>$description</td></tr>";//<th>$projcode_id</th>
		}
		echo "</table><br><br>";
	} else {
		echo "<h2 align=center>No project code has been found.</h2>";
	}
	mysql_close();
?>
</html>
