<html>

<head>
<meta http-equiv="Content-Language" content="en-au">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>Admin left page</title>
<base target="main">
</head>

<body background="rlaemb.JPG">
<!--list tables -->

<?php
	if ($dbname == "") {
		$dbname = $dblist;
	}
	
  	echo '<b>Tables contained in <font color="#FF00FF"> '.getenv("dbname").'.</font></b><br><br>';
	echo '<b>';
	echo '<ul>';
	
  	include("connet_root.inc");
  	$result = mysql_list_tables ($dbname);
	//echo 'Number of tables is '.COUNT(mysql_num_rows ($result)).'<br>';
	
	$i = 0;
	while ($i < mysql_num_rows ($result)) {
    	$tb_names[$i] = mysql_tablename ($result, $i);
    	//echo '<li>'.$tb_names[$i]."</li><br>";
		$file_main		=	'admin_dbview_main.php';
    	echo '<li><a href='.$file_main.'?dbname='.$dbname.'&tablename='.$tb_names[$i].'>'.$tb_names[$i].'</a></li><br>';
    	//echo '<li><a href='.$PHP_SELF.'?tablename='.$tb_names[$i].'>'.$tb_names[$i].'</a></li><br>';
       $i++;
	}
	echo '</ul>';
	echo '</b>';
	//printf("<a href=\"%s?id=%s\">%s %s</a><br>\n", $PHP_SELF, $myrow["id"], $myrow["first"], $myrow["last"]);
	exit;
?>

Select one table from the list<br>
<form method="POST" action="php.php">
	<p><input type="text" name="dblist" size="20" value=
	<?php
		echo $dbname;
	?>
	></p>

    <p align="center">
    <select size="1" name="tablelist">
    <?php
		$i = 0;
		while ($i < mysql_num_rows ($result)) {
    		$tb_names[$i] = mysql_tablename ($result, $i);
    		if ($i == 5) {
				echo '<option selected>';
        			echo $tb_names[$i];
       		echo '</option>';
        	}else{
				echo '<option>';
        			echo $tb_names[$i];
				echo '</option>';
			}
    		$i++;
		}
	?>
	</select>
   <p> <input type="submit" value="Change Table" name="chtable"></p>
	
</form>

</body>

</html>
