<html>
<?php
	/* table: category. columns: catid, cat_name*/
	$sql	=	"SELECT catid, cat_name FROM inventory.category order by cat_name;";
	include("connet_other_once.inc");
	$result = mysql_query($sql);
	include("err_msg.inc");
	if ($result) {
		if ($process == 1) {
			while (list($catid, $cat_name) = mysql_fetch_array($result)) {
				$catlist[$catid]	=	$cat_name;
			}
		} else {
			echo '<select name="catid1" size="1">';
			echo '<OPTION selected value="0">---Select One---';
			while (list($catid, $cat_name) = mysql_fetch_array($result)) {
				if ($action == "edit" && $catname == $cat_name) {
					echo '<OPTION selected value="'.$catid.'">'.$cat_name;
				} else {
					echo '<OPTION value="'.$catid.'">'.$cat_name;
				}
			}
		}
	}
	echo '</select>';
?>
</html>