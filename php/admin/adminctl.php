<html>
<head>
<title>Administrator's Tool</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>New Page 1</title>
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
</head>
<?php
echo "<frame"."set rows=\"*,11%\">";
echo "<nof"."rames>";
echo "<bo"."dy>";
echo "<p>This page uses frames, but your browser doesn't support them.</p>";
echo "</nof"."rames>";
include('connet_root_once.inc');
include('str_decode_parse.inc');
include("userinfo.inc"); //$userinfo
include("phpdir.inc");
$qry	=	'?'.base64_encode($userinfo);

echo "<fra"."me name=\"main\" tar"."get=\"footnotes\" src=\"adminctl_top.php$qry\">";
echo "<fra"."me name=\"footnotes\" src=\"/$phpdir/frame_footer.php\">";
echo "</frame"."set>";
echo "</bo"."dy>";
?>

</html>
