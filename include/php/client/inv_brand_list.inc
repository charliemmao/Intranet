<html>
<?php
	/* table: brandname. columns: brandid, brand_name, */
	$sql	=	"SELECT brandid, brand_name FROM inventory.brandname order by brand_name;";
	include("connet_other_once.inc");
	$result = mysql_query($sql);
	include("err_msg.inc");
	if ($result) {
		if ($process == 1) {
			while (list($brandid, $brand_name) = mysql_fetch_array($result)) {
				$brandlist[$brandid]	=	$brand_name;
			}
		} else {
			echo '<select name="brandid1" size="1">';
			echo '<OPTION selected value="0">---Select One---';
			while (list($brandid, $brand_name) = mysql_fetch_array($result)) {
				if ($action == "edit" && $brandname == $brand_name) { 
					echo '<OPTION selected value="'.$brandid.'">'.$brand_name;
				} else {
					echo '<OPTION value="'.$brandid.'">'.$brand_name;
				}
			}
		}
	}
	echo '</select>';
?>
</html>