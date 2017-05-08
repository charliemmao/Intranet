<html>
<?php
	/* table: auth_middle. columns: middlename_id, middlename*/
	$sql	=	"SELECT middlename_id as id, middlename as name FROM library.auth_middle order by name;";
	$result = mysql_query($sql,$contid);
	include("err_msg.inc");
	if ($result) {
		while (list($id, $name) = mysql_fetch_array($result)) {
			if ($value0nly == 0) {
				echo "<option value=\"$id\">$name";
			} elseif ($value0nly == 1) {
				$middlename_list[$id]	=	$name;
			}
		}
	}
?>
</html>