<html>
<?php
	include("connet_root_once.inc");
	$sql = "SELECT company_name as name, date_end as end FROM timesheet.company WHERE date_end='0000-00-00'
		and company_name!='GENERAL-%' order by company_name;";
	echo "<option value=\"\">---Select One---";
	$result = mysql_query($sql,$contid);
	include("err_msg.inc");
if ($result) {
	if ($addvalue == "1") {
		while (list($name,$end) = mysql_fetch_array($result)) {
			if (ereg_replace("GENERAL-","", $name) == $name) {
				if ($dbcompany == $name) {
					echo "<option value=\"$name\" selected>$name";
				} else {
					echo "<option value=\"$name\">$name";
				}
			}
		}
	} else {
		while (list($name,$end) = mysql_fetch_array($result)) {
			if (ereg_replace("GENERAL-","", $name) == $name) {
				if ($dbcompany == $name) {
					echo "<option selected>$name";
				} else {
					echo "<option>$name";
				}
			}
		}
	}
}
	mysql_close();
?>
</html>