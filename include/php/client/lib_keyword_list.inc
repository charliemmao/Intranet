<html>
<?php
	/* table: keywords. columns: keyword_id, keyword */
	$sql	=	"SELECT keyword_id as id, keyword as name FROM library.keywords order by name;";
	$result = mysql_query($sql,$contid);
	include("err_msg.inc");
	if ($result) {
		while (list($id, $name) = mysql_fetch_array($result)) {
			if ($value0nly == 0) {
				echo "<option value=\"$id\">$name";
			} elseif ($value0nly == 1) {
				$keyword_list[$id]	=	$name;
			}
		}
	}
?>
</html>