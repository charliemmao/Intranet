<html>

<head>
<title></title>
</head>

<body text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG">

<!--list tables -->

<?php
	$priviledge	=	'00';
	include('allow_to_show.inc');
	$dbname='mysql';
  	echo '<b>Tables listed in '.$dbname.'.</b><br><br>';
	echo '<b>';
	echo '<ul>';
	
	$dbcont="invalid";
  	include("connet_root.inc");
  	$result = mysql_list_tables ($dbname);
	$i = 0;
	while ($i < mysql_num_rows ($result)) {
    	$tb_names[$i] = mysql_tablename ($result, $i);
    	//echo '<li>'.$tb_names[$i]."</li><br>";
    	echo '<li><a href='.$PHP_SELF.'?tablelist='.$tb_names[$i].'>'.$tb_names[$i].'</a></li><br>';
       $i++;
	}
	echo '</ul>';
	echo '</b>';
	//printf("<a href=\"%s?id=%s\">%s %s</a><br>\n", $PHP_SELF, $myrow["id"], $myrow["first"], $myrow["last"]);

?>
  	
<form method="POST" action="php.php">
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
