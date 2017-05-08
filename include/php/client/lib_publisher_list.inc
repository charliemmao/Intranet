<html>
<?php
	/* table: publisher. columns: pub_id, pub_name, email, homepage, telephone, fax, 
		po_box, addr_no_street, addr_suburb, addr_city, addr_state, addr_country, 
		addr_postcode, year_establish, year_of_closedown 
	*/
	$sql	=	"SELECT pub_id as id, pub_name as name FROM library.publisher order by name;";
	$result = mysql_query($sql,$contid);
	include("err_msg.inc");
	if ($result) {
		while (list($id, $name) = mysql_fetch_array($result)) {
			if ($value0nly == 0) {
				echo "<option value=\"$id\">$name";
			} elseif ($value0nly == 1) {
				$pub_list[$id]	=	$name;
				//echo "pub_id: $id, $name<br>";
			}
		}
	}
?>
</html>