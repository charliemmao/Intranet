<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>New Page 1</title>
</head>

<body>
<?php
	include("connet_root_once.inc");
	$no=0;

	$sql = "SELECT brief_code FROM timesheet.projcodes order by brief_code";
	$result = mysql_query($sql);
	$filelog = __FILE__;
	$linelog = __LINE__;
	include("err_msg.inc");
	$nop = mysql_num_rows($result);
	while (list($brief_code) = mysql_fetch_array($result)) {
		//echo "$no<br>$brief_code<br>";
		//$pcode[$no] = ereg_replace("__"," ",$brief_code);
		$pcode[$no] = ereg_replace("_"," ",$brief_code);
		$no++;
	}
	
	$sql = "SELECT codelable as brief_code FROM timesheet.code_prefix ";//, codelable code_prefix
	$result = mysql_query($sql);
	$filelog = __FILE__;
	$linelog = __LINE__;
	include("err_msg.inc");
	$nop = mysql_num_rows($result);
	while (list($brief_code) = mysql_fetch_array($result)) {
		//echo "$no<br>$brief_code<br>";
		$pcode[$no] = ereg_replace("__"," ",$brief_code);
		$pcode[$no] = ereg_replace("_"," ",$brief_code);
		$no++;
	}
	$nop=$no;
	
	echo "$nop<br>";

	$sql = "SELECT ghrcode, rlacode FROM timesheet.ghrtorlacode";
	$result = mysql_query($sql);
	$filelog = __FILE__;
	$linelog = __LINE__;
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	echo "<p><table border=1>";
	echo "<tr><th>GHR Code</th><th>Code-from GHR</th><th>Code Found</th></tr>";
	while (list($ghrcode, $rlacode) = mysql_fetch_array($result)) {
		echo "<tr><td>$ghrcode</td><td>$rlacode</td>";
		$matched = "---";
		for ($i=0; $i<=$nop; $i++) {
			if ($rlacode == $pcode[$i]) {
			//if (trim(strtolower($rlacode)) == trim(strtolower($pcode[$i]))) {
				$matched = "Yes";
				break;
			}
		}
		echo "<td>$matched</td></tr>";
	}
	echo "<p></table><br>";
		
	for ($i=0; $i<=$nop ; $i++) {
		echo $pcode[$i]."<br>";
	}
	echo "<br>";
?>
</body>

</html>
