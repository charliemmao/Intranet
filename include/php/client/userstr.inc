<html>
<?php
include("userinfo.inc");
$frm_str	=	$userinfo	;

$frm_str	=	base64_encode($frm_str);
echo '<input type="hidden" name="frm_str" size="20" value= "'.$frm_str.'">';
?>

</html>