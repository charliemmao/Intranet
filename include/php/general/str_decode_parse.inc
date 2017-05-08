<html>
<?php
	$url	=	getenv('QUERY_STRING');
	$url	=	base64_decode($url);
	$url	=	strtok('?'.$url,'?');
	$url	=	strtok('?');
	parse_str($url);
	//echo "QUERY_STRING: ".getenv('QUERY_STRING').'<br><br>';
	
	if ($frm_str	!= "") {
		$url	=	$frm_str;
		$url	=	base64_decode($url);
		$url	=	strtok('?'.$url,'?');
		$url	=	strtok('?');
		parse_str($url);
	}
	//echo "url: ".$url.'<br><br>';
?>
</html>