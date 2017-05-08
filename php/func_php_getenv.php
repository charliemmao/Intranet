<html>

<head>
<meta http-equiv="Content-Language" content="en-au">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>Admin contents page</title>
</head>

<body background="general/rlaemb.JPG">
<?php
include("phpdir.inc");
	//echo apache_lookup_uri(string filename)
		
	$headers = getallheaders();
	while (list($header, $value) = each($headers)) {
    	echo "$header: $value<br>\n";
	}
	
	//array parse_url(string url);
 	//This function returns an associative array returning any of the various components of the URL that are present. 
 	//This includes the "scheme", "host", "port", "user", "pass", "path", "query", 
 	//and "fragment". 

	$url='http://charlie/php/admin_dbview_main.php?dbname=mysql&tablename=columns_priv';
	$url=strtok($url,'?');
	$url=strtok('?');
	parse_str($url);

    echo 'DOCUMENT_ROOT: '.getenv('DOCUMENT_ROOT').'<br>';
    echo 'HTTP_ACCEPT: '.getenv('HTTP_ACCEPT').'<br>';
    echo 'HTTP_ACCEPT_ENCODING: '.getenv('HTTP_ACCEPT_ENCODING').'<br>';
    echo 'HTTP_ACCEPT_LANGUAGE: '.getenv('HTTP_ACCEPT_LANGUAGE').'<br>';
    echo 'HTTP_CONNECTION: '.getenv('HTTP_CONNECTION').'<br>';
    echo 'HTTP_HOST: '.getenv('HTTP_HOST').'<br>';
    echo 'HTTP_REFERER: '.getenv('HTTP_REFERER').'<br>';
    echo 'HTTP_USER_AGENT: '.getenv('HTTP_USER_AGENT').'<br>';
    echo 'PATH: '.getenv('PATH').'<br>';
    echo 'REMOTE_ADDR: '.getenv('REMOTE_ADDR').'<br>';
    echo 'REMOTE_PORT: '.getenv('REMOTE_PORT').'<br>';
    echo 'SCRIPT_FILENAME: '.getenv('SCRIPT_FILENAME').'<br>';
    echo 'SERVER_ADDR: '.getenv('SERVER_ADDR').'<br>';
    echo 'SERVER_ADMIN: '.getenv('SERVER_ADMIN').'<br>';
    echo 'SERVER_NAME: '.getenv('SERVER_NAME').'<br>';
    echo 'SERVER_PORT: '.getenv('SERVER_PORT').'<br>';
    echo 'SERVER_SIGNATURE: '.getenv('SERVER_SIGNATURE').'<br>';
    echo 'SERVER_SOFTWARE: '.getenv('SERVER_SOFTWARE').'<br>';
    echo 'GATEWAY_INTERFACE: '.getenv('GATEWAY_INTERFACE').'<br>';
    echo 'SERVER_PROTOCOL: '.getenv('SERVER_PROTOCOL').'<br>';
    echo 'REQUEST_METHOD: '.getenv('REQUEST_METHOD').'<br>';
    echo 'QUERY_STRING: '.getenv('QUERY_STRING').'<br>';
    echo 'REQUEST_URI: '.getenv('REQUEST_URI').'<br>';
    echo 'SCRIPT_NAME: '.getenv('SCRIPT_NAME').'<br>';
    
    phpinfo();
?>
</body>

</html>
