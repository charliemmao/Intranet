<html>
<?php
	/* table: manufacture. columns: manid, manufacture, */
	include("connet_other_once.inc");
	$sql	=	"SELECT manid, manufacture FROM inventory.manufacture order by manufacture;";
	$result = mysql_query($sql);
	include("err_msg.inc");
	if ($result) {
		if ($process == 1) {
			while (list($manid, $manufacture) = mysql_fetch_array($result)) {
				$manflist[$manid]	=	$manufacture;
			}
		} else {
			echo '<select name="manid1" size="1">';
			echo '<OPTION selected value="0">---Select One---';
			while (list($manid, $manufacture) = mysql_fetch_array($result)) {
				if ($action == "edit" && $manfname  == $manufacture) {
					echo '<OPTION selected value="'.$manid.'">'.$manufacture;
				} else {
					echo '<OPTION value="'.$manid.'">'.$manufacture;
				}
			}
		}
	}
	echo '</select>';
?>
</html>