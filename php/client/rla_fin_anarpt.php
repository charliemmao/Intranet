<html>
<body background="rlaemb.JPG" leftmargin="20">
<?php
//js
include('inventory_verify.inc');
include('str_decode_parse.inc');
include("rla_functions.inc");
include("userinfo.inc"); //$userinfo
//echo "$mystat<br>";
if ($mystat == "auth" || $mystat == "exec") { // $mystat == "poff"
} else {
	exit;
}
$userstr	=	"?".base64_encode($userinfo."&mystat=$mystat");
echo "<h2 align=center><a id=top>Finance Analysis and Report</a>";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[Refresh]</font></a></h2><hr>";
include("connet_root_once.inc");

//echo '<input type="hidden" name="frm_str" value= "'.$userstr.'">';

echo "<hr><br><a href=#top>Back to top</a><br><br>";

?>
</html>
