<html>
<?php
	$url	=	getenv('QUERY_STRING');
	//echo getenv('QUERY_STRING').'<br>';
	//echo $url.'<br>';

	$url	=	strtok('?'.$url,'?');
	$url	=	strtok('?');
	
	parse_str($url);
	/*
	$url	=	'va=a&vb=b&vc=c';
	$url	=	strtok('?'.$url,'?');
	$url	=	strtok('?');
	parse_str($url);
	echo '<br>'.$va.'=a1<br>';
	echo $vb.'=b1<br>';
	echo $vc.'=c1<br>';
	*/
?>
</html>