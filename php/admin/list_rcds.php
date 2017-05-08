<html>

<head>
<title></title>
</head>

<body bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG">

<?php
	$priviledge	=	'00';
	include('allow_to_show.inc');
	$dbcont="invalid";
  	include("connet_root.inc");
	mysql_select_db("timesheet");
	$result = mysql_query("SELECT * FROM iplist");
	$fields = mysql_num_fields($result);
	$rows   = mysql_num_rows($result);
	$i = 0;
	$table = mysql_field_table($result, $i);
	echo "<b>Table '".$table."' has ".$fields." fields and ".$rows." records.<br><br></b>";

	echo '<br><font size="2" color="#0000FF"><b>Records are:</b></font><BR>'; 	

	echo "<table border=\"1\">";
  		echo "<tr>";
  			$i=0;
  			while ($i < $fields) {
    			$name  = mysql_field_name  ($result, $i);
  				printf("<td>%s</td>",$name);
    			$i++;
    		}
    	echo "</tr>";
    	
		while ($myrow = mysql_fetch_array($result)) {
  			echo "<tr>";
  				$i=0;
		
				$i=(int)1.5;
	  			while (list($key,$val) = each($myrow)) {
	  				$i +=1;
	  				$j = 2*(int)($i/2);
					if ($i == $j) {
  						printf("<td>%s</td>","$val");
  					}
    			}
    		echo "</tr>";
    	}
	echo "</table><br>";	
	echo "</b><br>";
	echo '<a href="adminctl.php"><b><font size="6">Return</font></b></a>';
?>
</body>
