<html>
<head>
<title>Patent Title List</title>
</head>
<body leftmargin="20" bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG">

<?php
include("admin_access.inc");
include('rla_functions.inc');
include("connet_root_once.inc");

echo "<id=top><h2 align=center>Patent Title List
<a href=\"$PHP_SELF$admininfo\"><font size=2>[Refresh]</font></a></h2><hr>";

$sql = "SELECT country, patent_no, elec_copy_add FROM library.for_patent ORDER BY patent_no DESC;";
$result = mysql_query($sql);
$filelog = __FILE__;
$linelog = __LINE__;
include("err_msg.inc");
$no = mysql_num_rows($result);
echo "<H3>Total Number of Patents are $no.</h3>";
echo "<table border=1>";
echo "<tr><th>No</th><th>Country</th><th>Patent No</th><th>PDF File</th></tr>";
$i=0;
while (list($country, $patent_no, $elec_copy_add) = mysql_fetch_array($result)) {
	if (!$elec_copy_add) {
		$elec_copy_add = "---";
	}
	$i++;
	echo "<tr><td>$i</th><td>$country</td><td>$patent_no</td><td>$elec_copy_add</td></tr>";
	flush();
}
echo "</table><p>";
echo "<hr><a href=#top>Back to top</a><br><br>";
?>
</body>
