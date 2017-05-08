<html>

<head>
<title></title>
</head>

<?php
	echo '<body bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="../'.$phpdir.'/rlaemb.JPG">';
include("phpdir.inc");


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
	echo '<font size="2" color="#0000FF"><b>Fields definitions are:</b></font><BR>'; 	

	echo "<table border=\"1\">";
  		echo "<tr>";
    		echo "<td align=\"center\"><b>Name</b></td>";
    		echo "<td align=\"center\"><b>Type</b></td>";
    		echo "<td align=\"center\"><b>Length</b></td>";
    		echo "<td align=\"center\"><b>Flags</b></td>";
    	echo "</tr>";
/*
	echo "<table border=\"1\" width=\"70%\">";
  		echo "<tr>";
    		echo "<td width=\"15%\" align=\"center\"><b>Name</b></td>";
    		echo "<td width=\"15%\" align=\"center\"><b>Type</b></td>";
    		echo "<td width=\"15%\" align=\"center\"><b>Length</b></td>";
    		echo "<td width=\"25%\" align=\"center\"><b>Flags</b></td>";
    	echo "</tr>";
*/
	while ($i < $fields) {
    	$name  = mysql_field_name  ($result, $i);
    	$type  = mysql_field_type  ($result, $i);
    	$len   = mysql_field_len   ($result, $i);
    	$flags = mysql_field_flags ($result, $i);
  		printf("<tr><td>%s</td><td>%s</td><td>%s</td><td>%s</td></tr>\n",
  			$name,$type,$len,$flags);
    	$i++;
	}
	echo "</table><br>";	
	
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
	echo "<br><br><a href=\"../$phpdir/view_db.php\">View Database Again</a>";
?>
</body>
