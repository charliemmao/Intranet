<html>
<?php
	include("connet_root_once.inc");
	$fld = $fldproplist[0][0];
	$sql = "select count($fld) as norcd from $usedb.$usetable";
	//echo "$sql<br>";
  	$result = mysql_query($sql);
  	list($norcd) = mysql_fetch_array($result);
?>
</html>