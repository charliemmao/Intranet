<html>
<?php
	include("connet_root_once.inc");
$sql = "SELECT country as name, end_date as end FROM timesheet.country WHERE end_date='0000-00-00' order by country;";
	$result = mysql_query($sql);
	include("err_msg.inc");
	echo "<option value=\"\">---Select One---";
	if ($result) {
		while (list($name, $end) = mysql_fetch_array($result)) {
			if ($dbcountry == $name) {
				echo "<option selected>$name";
			} else {
				echo "<option>$name";
			}
		}
	}
	mysql_close();
?>
</html>