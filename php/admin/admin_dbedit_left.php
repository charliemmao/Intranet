<html>

<head>
<meta http-equiv="Content-Language" content="en-au">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>Admin left page</title>
<base target="main">
</head>

<body background="rlaemb.JPG" topmargin="4" leftmargin="4">

<?php
include("admin_access.inc");
include("phpdir.inc");
	if ($dbname == "") {
		$dbname = $dblist;
	}
	$file_main		=	'admin_dbedit_main.php';
	
  	//include("connet_root_once.inc");
  	$result = mysql_list_tables ($dbname);
	//echo 'Number of tables is '.mysql_num_rows ($result).'<br>';
	if (mysql_num_rows ($result) == 0){
		echo '<h4>&nbsp;</h4>
  <p align="center"><font color="#0000FF">
		No table has been defined in database '.$dbname.'.</font></p>';
	}elseif (mysql_num_rows ($result)) {
?>

<table border="0" width="260" cellspacing="0" cellpadding="0">
  <tr>
    <td width="115" align="center"><b>Table List</b></td>
    <td width="45" align="center"></td>
    <td width="45" align="middle"><b>Action</b></td>
    <td width="55" align="center"></td>
  </tr>
  
<?php
}
	$i = 0;
	while ($i < mysql_num_rows ($result)) {
    	$tb_names[$i] = mysql_tablename ($result, $i);
		echo '<tr>';
  			echo '<td width="115">';	//align="center"
  			echo '<b>'.'&nbsp; '.$tb_names[$i].'</b></td>';
  		
    		echo '<td width="45" align="center">';
    			echo '<a href='.$file_main.'?dbname='.$dbname;
    			echo '&tablename='.$tb_names[$i];
    			echo '&tabaction=editfield>Field</a>';
    		echo '</td>';
    	
    		echo '<td width="45" align="center">';
    			echo '<a href='.$file_main.'?dbname='.$dbname;
    			echo '&tablename='.$tb_names[$i];
    			echo '&tabaction=editrcd>Record</a>';
    		echo '</td>';

    		echo '<td width="55" align="center">';
    			echo '<a href='.$file_main.'?dbname='.$dbname;
    			echo '&tablename='.$tb_names[$i];
    			echo '&tabaction=deltable>Del Tab</a>';
    		echo '</td>';
  		echo '</tr>';
       $i++;
	}
	echo '</table>';
?>
 	<br>
 	<br>

	<table border="0" width="250" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%">
          <p align="center"><b><font size="4">
          <?php
				echo '<a href='.$file_main.'?dbname='.$dbname;
    			echo '&tablename=';
    			echo '&tabaction=createtable>';
    			echo '<b><font size="4">Create New Table</font></b></a>';

    			echo '<br><br>GET DB "'.$dbname.'" INFO in<br>';
				echo '<a href="/'.$phpdir.'/showdbinfo.php?dbname='.$dbname.'" target="main">Current</a>&nbsp;or &nbsp;';
				echo '<a href="/'.$phpdir.'/showdbinfo.php?dbname='.$dbname.'" target="_blank">New</a> window';
          ?>
          </font></b></p>
        </td>
      </tr>
    </table>

</body>
