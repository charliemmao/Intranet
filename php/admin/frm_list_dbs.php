<html>

<head>
<title></title>
</head>

<body text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG">

<?php
	$priviledge	=	'00';
	include('allow_to_show.inc');
?>
<form method="POST" action="php.php">
  	<p><b>The following database found in MySQL server</b>&nbsp;&nbsp;&nbsp;
    <select size="1" name="dblist">
    <?php
  		//list database
		$dbcont="invalid";
  		include("connet_root.inc");
  		$result = mysql_list_dbs();
		$i = 0;

		while ($i < mysql_num_rows ($result)) {
    		$tb_names[$i] = mysql_tablename ($result, $i);
    		if ($i == 0) {
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
	
	&nbsp;
	</select>&nbsp;&nbsp;&nbsp; <input type="submit" value="Change Database" name="chdb"></p>
</form>
	
<?php
	echo "</b><br>";
	echo '<a href="adminctl.php"><b><font size="6">Return</font></b></a>';
?>
</body>
</html>
