<html>
<?php
	if ($User) {
		echo "$b1"."$User$b2<input type=hidden name=User value=$User></td></tr>";
	} else {
		echo "<select name=User>";
		$sql = "SELECT User FROM mysql.user WHERE User!='root' and User!='anyone';";
		$result = mysql_query($sql);
		include("err_msg.inc");
		$no = mysql_num_rows($result);
		if ($result) {
			while (list($User) = mysql_fetch_array($result)) {
				echo "<option>$User";
			}
		}
		echo "</option></select>$b1$no users.$b2</td></tr>";
	}
?>
</html>