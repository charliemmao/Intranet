<html>
<?php
	$url	=	$frm_str;
	$url	=	base64_decode($url);
	$url	=	strtok('?'.$url,'?');
	$url	=	strtok('?');
	
	parse_str($url);
?>
</html>