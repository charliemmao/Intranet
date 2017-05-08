<html>
<?php
	/* table: auth_last. columns: lastname_id, lastname*/
	$sql	=	"SELECT DISTINCT lastname as name, lastname_id as id FROM library.auth_last order by name;";
	$result = mysql_query($sql,$contid);
	include("err_msg.inc");
	if ($result) {
		while (list($name,$id) = mysql_fetch_array($result)) {
			if ($value0nly == 0) {
				echo "<option value=\"$id\">$name";
			} elseif ($value0nly == 1) {
				$lastname_list[$id]	=	$name;
			}
		}
	}
?>
</html>