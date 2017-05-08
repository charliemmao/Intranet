<html>

<head>
<meta http-equiv="Content-Language" content="en-au">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>Admin footer page</title>
<base target="contents">
</head>

<body background="rlaemb.JPG" topmargin="5">
<table border="0" width="80%" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td width="40%"><font size="1">
    <?php
		echo 'Research Laboratories of Australia<br>'
		.'7 Valetta Rd, Kidman Park, SA 5025, Australia<br>'
		.'Tel: 61-8-83521322	    Fax: 61-8-83521359';
	?>
    </font></td>
    
    <td width="40%"><font size="1">
    <?php
    	$str	=	getenv('SERVER_NAME');
    	//echo $str.'<br>';
		echo 'Return to <a href="http://'.$str.'" target="_top">Intranet</a>. ';
		echo 'Visit Web Page <a href="http://'.$str.'/rla_pub_www/" target="_blank">Internal</a> or <a href="http://www.rla.com.au" target="_blank">External</a>.<br>';
		echo 'Please email your comments to <a href="mailto:admin@'.$str.'">Intranet Developer</a>.<br>';

		echo 'Intranet was last modified on ';
		print (date("F d, Y"));
	?>
</font></td></tr></table>
</body>
</html>
