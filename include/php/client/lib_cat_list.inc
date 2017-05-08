<html>
<?php
	/* table: library_cat. columns: cat_id, category*/
	$sql	=	"SELECT cat_id, category FROM library.library_cat where category!='technotes' order by cat_id;";
	$result = mysql_query($sql,$contid);
	include("err_msg.inc");
	if ($result) {
		echo '<select name="cat_id_lib" size="1">';
		echo '<OPTION selected value="0">---Select One---';
		while (list($cat_id, $category) = mysql_fetch_array($result)) {
			$lib_cat_list[$cat_id] = $category;
			if ($cat_id_lib == $cat_id || ($cat_id_lib == "" && $category =="book")) {
				echo '<OPTION selected value="'.$cat_id.'">'.strtoupper($category);
			} else {
				echo '<OPTION value="'.$cat_id.'">'.strtoupper($category);
			}
		}
		echo '</select>';
	}
?>
</html>