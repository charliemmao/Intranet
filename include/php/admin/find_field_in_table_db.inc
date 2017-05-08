<html>
<?php
	include("connet_root_once.inc");
	$result = mysql_list_fields($usedb, $usetable);
	include("mysql_query_fld_list.inc");
?>
</html>