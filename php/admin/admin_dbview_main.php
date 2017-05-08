<html>

<head>
<meta http-equiv="Content-Language" content="en-au">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>Admin contents page</title>
</head>

<body background="rlaemb.JPG">
<?php
	$priviledge	=	'00';
	include('allow_to_show.inc');
// connect to database server
  	include("connet_root.inc");
	if (!$dbname) {
		exit;
	}

// get database and table name 
	//echo 'QUERY_STRING: '.getenv('QUERY_STRING').'<br>';
	//echo 'REQUEST_URI: '.getenv('REQUEST_URI').'<br>';
	//echo 'SCRIPT_NAME: '.getenv('SCRIPT_NAME').'<br>';
	$url	=	getenv('QUERY_STRING');
	$url	=	strtok($url,'?');
	$url	=	strtok('?');
	parse_str($url);
	if ($dbname == "") {
		$dbname	=	'mysql';
		$tablename	=	'user';
    	//echo 'no db and table are selected.<br>';
	} elseif ($dbname != "" && $tablename != "") {
    	//echo 'db and table are selected.<br>';
   }
   //echo 'Select database is '.$dbname.'<br>Select table is '.$tablename.'<br>';

	mysql_select_db($dbname);
	$result = mysql_query("SELECT * FROM $tablename");
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
?>
</body>

