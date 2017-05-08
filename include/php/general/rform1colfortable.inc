<html>
<?php
/*
	echo $dbname.'<br>';
	echo $tablename.'<br>';
//*/
	
include("connet_root_once.inc");
$sql    =   'SHOW COLUMNS FROM '.$dbname.'.'.$tablename.';';

$result =   mysql_query($sql);
include('err_msg.inc');
$col_no =   mysql_num_rows ($result);

$fld_def	=	"";
$str = " SET ";
$str_screen = $str;
if ($col_no) {		
 	while ($myrow = mysql_fetch_array($result)) {
		$k1		=	1;
		while (list($key,$val) = each($myrow)) {
			$i2	=	(int)($k1/2.0);
			if ($k1 == $i2*2) {
			  if ($i2 == 3) {
			  	if ($val == "") {
			  		$val = "NOT NULL";
			  	}
			  }
            $itemkey[$i2]	=	$key;
            $itemval[$i2]	=	$val;
          }
			$k1 +=1;
       }
		for ($i0=1; $i0<=count($itemval);$i0++) {
			echo $i0.' key = '.$itemkey[$i0].'; val = '.$itemval[$i0].'<br>';
		}
		exit;
		/*
		1 key = Field; val = email_name
		2 key = Type; val = varchar(10)
		3 key = Null; val = NOT NULL
		4 key = Key; val = PRI
		5 key = Default; val = 
		6 key = Extra; val =
		*/
	}
	//$str = $str.$val."='$".$val."',";
	//$str_screen = $str_screen.$val."='$".$val."',<br>";
}

//*
echo $str_screen.'<br>';
$basefile = 'read_formdata_of_'.$tablename.'_in_'.$dbname.'.txt';
include("find_admin_ip.inc");
$newfile    =   "/home/$adminname/".$basefile;
$fp =   fopen($newfile,'w+');
if (!$fp) {
    echo 'Problem to open file '.$newfile.'<br>';
    exit;
}
fputs($fp,$str);
fclose($fp);
//*/
echo '<h2>Section of a statement for INSERT or UPDATE record to '.strtoupper($tablename).' in DB '.strtoupper($dbname).'</h2>';
echo $str;

?>
</html>