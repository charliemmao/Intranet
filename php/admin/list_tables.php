<html>

<head>
<title></title>
</head>

<body bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG">

<?php
	$priviledge	=	'00';
	include('allow_to_show.inc');
  	//list tables
	$dbcont="invalid";
  	include("connet_root.inc");
  	$dbname="mysql";
	echo "<h2>The following table are found in database ".$dbname.".</h2><br>";
	echo "<b>";
  	$result = mysql_list_tables ($dbname);
	$i = 0;
		while ($i < mysql_num_rows ($result)) {
    	$tb_names[$i] = mysql_tablename ($result, $i);
    	echo $tb_names[$i] . "<BR>";
    	$i++;
	}
	echo "</table><br>";	
	echo '<a href="adminctl.php"><b><font size="6">Return</font></b></a>';
?>
</body>
</html>
