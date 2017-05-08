<html>

<head>
<title></title>
</head>

<body bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG">

<?php
	$priviledge	=	'00';
	include('allow_to_show.inc');
  	//list database
	$dbcont="invalid";
  	include("connet_root.inc");
  	$result = mysql_list_dbs();
	echo "<h2>The following database are found on the MySQL server.</h2><br>";
	echo "<b>";
	$i = 0;
	while ($i < mysql_num_rows ($result)) {
    	$tb_names[$i] = mysql_tablename ($result, $i);
    	echo $tb_names[$i]."<br><br>";
    	$i++;
	}
	echo "</b>";
	echo '<a href="adminctl.php"><b><font size="6">Return</font></b></a>';
?>
</body>
</html>
