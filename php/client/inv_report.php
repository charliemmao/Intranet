<html>

<head>
<title>Inventry Report</title>
</head>
<body leftmargin="20" bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG">

<?php
include('str_decode_parse.inc');
if ($priv	==	'00' || $priv	==	'10' || $priv	==	'30') {
} else {
	echo "<h2 align=center>You don't have permission to view this page.</h2>";
	exit;
}
include("userinfo.inc"); //$userinfo
$userstr	=	"?".base64_encode($userinfo);
echo "<h2 align=center><a id=top>Inventory Report </a>";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[Refresh]</font></a></h2><hr>";

echo "<h2 align=center>Under Development</h2>";
echo "<hr><a href=#top>Back to top</a><br><br>";
?>
</body>
</html>