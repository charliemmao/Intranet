<html>

<head>
<title></title>
</head>

<body text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG">

<?php
include('str_decode_parse.inc');
$subtract = 3;
if ($refresh || $main) {
	echo "<a id=top><h1 align=center><a id=tm>Table Modification ($tablename)</a></h1><hr>";
	$subtract = 5;
} 	
	if ($addnewrcd) {
		$basefile	=	'add';
		$sql_fld_list	=	" SET ";
	} elseif ($modifyrcd) {
		$basefile	=	'mod';
		$sql_fld_list	=	" SET ";
	} elseif ($deletercd) {
		$basefile	=	'del';
		$sql_fld_list	=	"";
	}	
	
	$i	= count($HTTP_POST_VARS)-$subtract;
	$j	= 0;
	while (list($key, $val) = each($HTTP_POST_VARS)) {
		if ($j <=$i) {
			$j1	=	$j+1;
			//echo $j1.'  '.$key.' = '.$val.'<br>';
			if ($j <$i) {
				$sql_fld_list	=	$sql_fld_list.$key."='".$val."',";
			} else {
				$sql_fld_list	=	$sql_fld_list.$key."='".$val."'";
			}
		}
		$j++;
	}
	include('str_decode_parse.inc');
	$pat	= chr(92).chr(92).'"';
	$where = ereg_replace($pat,'"',$where);
	/*
	echo 'dbname: '.$dbname.'<br>';
	echo 'tablename: '.$tablename.'<br>';
	echo 'where: '.$where.'<br>';
	echo 'sql_fld_list: '.$sql_fld_list.'<br>';
	//*/
		
	if ($addnewrcd) {
		$sql = "INSERT INTO ".$dbname.'.'.$tablename.$sql_fld_list.';';
	} elseif ($modifyrcd) {
		$sql = "UPDATE ".$dbname.'.'.$tablename.$sql_fld_list.$where;
	} elseif ($deletercd) {
		$sql = "DELETE FROM ".$dbname.'.'.$tablename.$where;
	} elseif ($cancel) {
		echo '<h2><font color="#0000FF"><p align="center">Action has been cancelled.</p></font></h2><br>';
		exit;
	}
	
	echo "<b>Query String:</b><p>".$sql.'<br>';

	$basefile = $dbname.'_'.$tablename.'_'.$id.'_'.$basefile.'.txt';
	$newfile	=	"/tmp/".$basefile;	//
	$fp	=	fopen($newfile,'w+');
	if (!$fp) {
		echo 'Problem to open file '.$newfile.'<br>';
		exit;		
	}
	fputs($fp,$sql);
	fclose($fp);
		
	//exit;
	include("connet_root.inc");
	$result = mysql_db_query($dbname,$sql,$contid);
	
	if (!$result) {
		if ($addnewrcd) {
			$str =	"add new record to";
		} elseif ($modifyrcd) {
			$str =	"update the record in";
		} elseif ($deletercd) {
			$str =	"delete the record in.";
		}	
		echo '<h2><font color="#FF0000">Failed to '.$str." $dbname.$tablename.</font></h2><br>";
	} else {
		if ($addnewrcd) {
			$str =	'New record has been successfully added to '.$tablename."$dbname.$tablename.";
		} elseif ($modifyrcd) {
			$str =	'MySQL server has been successfully update the record in '."$dbname.$tablename.";
		} elseif ($deletercd) {
			$str =	'MySQL server has been successfully delete the record from '."$dbname.$tablename.";
		}	
		echo '<h2><font color="#0000FF">'.$str.'</font></h2><br>';
	}

if ($refresh || $main) {
	echo "<hr><ul>";
	echo "<li><a href=\"$main\"><font size=4><b>Back to Main Page</b></font></a>";
	echo "<li><a href=\"$refresh\"><font size=4><b>Back to Previous Page</b></font></a>";
	echo "</u1><p>";
} 	
?>

<?php
function stafflist_all_str($HTTP_POST_VARS,$sql,$err) {
	$i	= count($HTTP_POST_VARS)-3;
	$j	= 0;
	while (list($key, $val) = each($HTTP_POST_VARS)) {
		if ($j < $i) {
   			echo 'No '.$j.' '.$key.' => '.$val.'<br>';
   			$j +=1;
   		}
	}
}
?>

</body>
</html>
