<html>

<head>
<meta http-equiv="Content-Language" content="en-au">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>Admin top page</title>
</head>

<body background="rlaemb.JPG">

<!--create or drop database-->
<?php
include("admin_access.inc");
include('rla_functions.inc');

	if ($create) {
		$action	=	$action;
		if ($action == "Create a DB"){
			$result = mysql_create_db($dbname,$contid);
		} elseif ($action == "Drop a DB"){
			$result = mysql_drop_db($dbname,$contid);
		}
		$outcome='<h2><font size="5">';
		if ($result){
			$outcome	=	"Database \"$dbname\" has been $action successfully.<br>";
		}else{
			$outcome	=	"Database \"$dbname\" has failed to be $action .<br>";
		}
		$outcome	=	 $outcome.'</font></h2>';
		$outcome	=	 $outcome.'<a href="admin_dbedit_top.php">Create or drop Database</a>';
	}
?>

<!--Change DB-->
<div align="center">
  <center>
<table border="0" width="80%" cellspacing="0" cellpadding="0">
  <tr>
	<td width="50%">
 	<?php	//echo "dbname = $dbname<br>"; ?>
	<form method="POST" action="admin_dbedit_left.php" target="contents">
  	<p align="left" style="margin: 0"><font size="4" face="Courier New" color="#0000FF">
  	<b>Database list</b></font></p>
  	<font size="1"><select size="1" name="dblist">
	
    <?php
 		//list database
  		include("connet_root.inc");
  		$result = mysql_list_dbs();
		$i = 0;
		while ($i < mysql_num_rows ($result)) {
    		$tb_names[$i] = mysql_tablename ($result, $i);
    		if ($tb_names[$i] != 'test'){
    		//if ($tb_names[$i] != 'mysql'){
    			if ($tb_names[$i] == "$dbname") {
					echo '<option selected>'; echo $tb_names[$i]; echo '</option>';
        		} else {
					echo '<option>'; echo $tb_names[$i]; echo '</option>';
				}
			}//}
    		$i++;
		}
	?>
	
	</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <font size="1">
  		<input type="submit" value="Change" name="chdb" width=50>
    </font>
	</form>
    </font>
	</td>

<!--Create DB-->
	<td width="50%">
		<?php
			if	($outcome != "") {
			echo $outcome;
			} else { 
		?>
		<style="margin-left: 0; margin-right: 0"><font size="1">
		<form method="post" action="<?php echo $PHP_SELF?>"
			<style="margin: 0" target="top"><font size="4" face="Courier New">
			<b><font color="#0000FF">Database name</font></b>&nbsp; 
				<input type="text" name="dbname" size="20">&nbsp;</font>
          <p style="margin: 0"><font size="4" face="Courier New"><b><font color="#0000FF">
				Action</font></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<select size="1" name="action">
					<option selected>Create a DB</option>
					<option>Drop a DB</option>
				</select></font></font><font size="4" face="Courier New">&nbsp;</font><font size="1"><font size="4"><input type="submit" value="Action" name="create">
			</font>
		</form>
		<?php } ?>
	</td>
	</tr>
</table>
  </center>
</div>
<HR>
</body>

</html>
