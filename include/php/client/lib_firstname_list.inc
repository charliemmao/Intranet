<html>
<?php
	/* table: auth_first. columns: firstname_id, firstname*/
	$sql	=	"SELECT firstname_id as id, firstname as name FROM library.auth_first order by name;";
	$result = mysql_query($sql,$contid);
	include("err_msg.inc");
	if ($result) {
		while (list($id, $name) = mysql_fetch_array($result)) {
			if ($value0nly == 0) {
				echo "<option value=\"$id\">$name";
			} elseif ($value0nly == 1) {
				$firstname_list[$id]	=	$name;
			}
		}
	}
?>
</html>