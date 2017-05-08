<html>

<head>
<meta http-equiv="Content-Language" content="en-au">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>Admin top page</title>
<base target="contents">
</head>

<body background="rlaemb.JPG">
<?php
	$priviledge	=	'00';
	include('allow_to_show.inc');
?>
<form method="POST" action="admin_dbview_left.php">
  	<p align="center"><b>The following database found in MySQL server</b>&nbsp;&nbsp;&nbsp;
    <select size="1" name="dblist">
    <?php
  		//list database
  		include("connet_root.inc");
		//$dbname ="invalid";
		//mysql_select_db($dbname,$contid);
  		$result = mysql_list_dbs();
		$i = 0;

		while ($i < mysql_num_rows ($result)) {
    		$tb_names[$i] = mysql_tablename ($result, $i);
    		if ($i == 2) {
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

</body>

</html>
