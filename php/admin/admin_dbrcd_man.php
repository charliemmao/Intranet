<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>DB Record Manipulation</title>
</head>

<body background="../images/rlaemb.JPG">
<?php
include("admin_access.inc");
include('str_decode_parse.inc');
include("rla_functions.inc");
//include("userinfo.inc"); //$userinfo
//$userstr	=	"?".base64_encode($userinfo);

echo "<h2 align=center><a id=top>DB Record Manipulation</a>";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[Refresh]</font></a>";
echo "<a href=\"adminctl_top.php$userstr\"><font size=2>[Go TO Administrator's Tool]</font></a>";
echo "</h2><hr>";

echo '<form name=dblist method=post action="'.$PHP_SELF.'">';
	echo "dblist";

echo "</form>";

echo "<hr><br><a href=#top>Back to top</a><br><br>";
?>
</body>

</html>
