<html>
<?php
$sql = "SELECT description as des, codes, category 
	FROM rlafinance.codeid WHERE rlaactive='y' ORDER BY des";
$result = mysql_query($sql);
include("err_msg.inc");
$noghrcharge = mysql_num_rows($result);
//echo "$noghrcharge <br>";
$i=0;
while (list($des, $codes, $category) = mysql_fetch_array($result)) {
	//echo "$category: $codes, $des<br>";
	//$des = ereg_replace(""," ",$des);
	$ghrchcode[$i][0]=$codes;
	$ghrchcode[$i][1]=$des;
	$ghrchcode[$i][2]=$category;
	$ghrcode["$codes"] = $des;
	$i++;
}
?>
</html>
