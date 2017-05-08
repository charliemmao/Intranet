<html>

<head>
<title>Describle Database</title>
</head>

<body text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="../general/rlaemb.JPG">

<?php
	include('str_decode_parse.inc');
	/*
	echo 'dbname: '.$dbname.'<br>';
	echo 'tablename: '.$tablename.'<br>';
	echo 'fldname: '.$fldname.'<br>';
	echo 'fldvalue: '.$fldvalue.'<br>';
	//*/
	
  	include("connet_root.inc");
  	$result = mysql_list_tables ($dbname);
  	include('err_msg.inc');
  	$tableno	=	mysql_num_rows ($result);

	if ($tableno == 0){
		echo '<h1><align="center"><font color="#0000FF">
		No table has been defined in database '.$dbname.'.</font></h1>';
		exit;
	}
	
	echo '<h1><a id=top>In DB "<font color="#0000FF">'.strtoupper($dbname).'</font>", ';
	echo $tableno.' tables have been defined.</a></h1>';
	echo '<ul>';
	$i = 0;
	while ($i < $tableno) {
    	$tb_names[$i] = mysql_tablename ($result, $i);
		echo '<li><a href=#'.$tb_names[$i].'>'.strtoupper($tb_names[$i]).'</a><a></li>';
		$tablelist[$i]	=	$tb_names[$i];
       $i++;
	}
	echo '</ul>';
	
	$i	=	0;
	while ($i < $tableno) {
		include('showfieldinfo.inc');
		$createtable 	=	'CREATE TABLE '.$dbname.'.'.$tb_names[$i].' (';
		$str_temp	=	trim($fld_def);
		$pos	=	'$';
		$chr_move	= ',';
		include('remove_ch.inc');
		$fld_def	=	$str_temp;
	
		include('showindexinfo.inc');
		if ($idx_def != "") {
			$str_temp	=	trim($idx_def);
			$pos	=	'$';
			$chr_move	= ',';
			include('remove_ch.inc');
			$idx_def	=	$str_temp;
			$createtable 	=	$createtable.$fld_def.','.$idx_def.');';
		} else {
			$createtable 	=	$createtable.$fld_def.');';
		}
		
		echo '<h4>Table Definition:</h4>';
		$createtable = ereg_replace(" MUL ", " ", $createtable);
		echo $createtable;
		$newfile	=	"/tmp/".$dbname.'_'.$tb_names[$i].".txt";
		echo '<br><br>The table definition is saved as '.$newfile.'<br>';
		echo '</a><a href="#top"><b>Back to top</b></a><br>';
		
		$fp	=	fopen($newfile,'w+');
		if (!$fp) {
			echo 'Problem to open file '.$newfile.'<br>';
			//exit;		
		} else {
			fputs($fp,$createtable);
			fclose($fp);
		}
       $i++;
	}		
		
	/*
	SHOW DATABASES [LIKE wild]
		or SHOW TABLES [FROM db_name] [LIKE wild]
		or SHOW COLUMNS FROM tbl_name [FROM db_name] [LIKE wild]
		or SHOW INDEX FROM tbl_name [FROM db_name]
		or SHOW STATUS
		or SHOW VARIABLES [LIKE wild]
		or SHOW [FULL] PROCESSLIST
		or SHOW TABLE STATUS [FROM db_name] [LIKE wild]
		or SHOW GRANTS FOR user
	*/
	
	/*
	EXPLAIN tbl_name
	EXPLAIN SELECT select_options
	EXPLAIN tbl_name is a synonym for DESCRIBE tbl_name or SHOW COLUMNS FROM tbl_name
	*/
	
?>
</body>
