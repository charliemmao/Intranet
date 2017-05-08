<html>
<head>
<title>Project Budget</title>
<LINK REL="StyleSheet" HREF="../style/style.css" TYPE="text/css">
</head>
<body text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG" leftmargin="20">

<?php
########################################
##		Access control
########################################
include('str_decode_parse.inc');

include("userinfo.inc");
$qry	=	"?".base64_encode($userinfo);
include("find_domain.inc");	
if ($priv == "00" || $priv == "10") {
} else {
	exit;
}

echo '<h2 align=center>Project Budget</h2>';// onMouseOver=\"window.status='This is a test.'; return true;\"
$statuscontext = "Refresh";
include("self_status.inc");
echo "<a id=top><p align=center><a href=\"".$PHP_SELF."$qry\" $status>[Refresh]</a><hr>";
include('connet_root_once.inc');
#include("uploadfile.inc");

echo "<ul>";
echo "<li><a href=ts_proj_budget_data.php$qry target=_blank>Budget Data Upload</a></li>";
if ($priv == "00") {
	echo "<li><a href=ts_proj_budget_report_cmm.php$qry target=_blank>Project Report</a></li>";
} else {
	echo "<li><a href=ts_proj_budget_report.php$qry target=_blank>Project Report</a></li>";
}
echo "<li><a href=ts_proj_budget_deactivedata.php$qry target=_blank>Remove Project File</a></li>";
echo "</ul>";
$statuscontext = "Back To Top";
include("self_status.inc");
echo "<hr><a href=#top $status>Back to top</a><br>";
?>
</body>
