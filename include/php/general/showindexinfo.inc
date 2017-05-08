<html>
<?php

//$sql    =   'SHOW INDEX FROM '.$tablelist[$i].' FROM '.$dbname.';';
$sql    =   'SHOW INDEX FROM '.$dbname.'.'.$tablelist[$i].';';
//echo $sql.'<br>';

$result =   mysql_query($sql);
include('err_msg.inc');
$col_no =   mysql_num_rows ($result);
echo "<br><h4>Table ".$tablelist[$i].' has '.$col_no.' index</h4>';
if ($col_no) {
	echo '<table border="1">';
 	$myrow = mysql_fetch_array($result); 
	$k1=1;
	echo '<tr>';
	while (list($key,$val) = each($myrow)) {
		$k1 +=1;
		$k2 = 2*(int)($k1/2);
       if ($k1 != $k2) {
           echo '<td><align="center"><b>'.$key.'</b></td>';
       }
	}
	echo '</tr>';
	$result =   mysql_query($sql);
	include('err_msg.inc');
	$col_no =   mysql_num_rows ($result);
	$idx_def	=	"";
	if ($col_no) {		
 	while ($myrow = mysql_fetch_array($result)) {
		$k1=(int)1.5;
		echo '<tr>';
		while (list($key,$val) = each($myrow)) {
			$k1 +=1;
			$k2 = 2*(int)($k1/2);
            if ($k1 == $k2) {
                echo '<td>'.$val.'</td>';
                if ($val == '---') {
                		$val	=	"";
                }
                $indexvar[$key]	=	$val;
            }
        }
        echo '</tr>';
        
        if ($indexvar[2] != 'PRIMARY') {
        	$no_unique	=	$indexvar[1];
        	$key_name	=	$indexvar[2];
        	$column_name	=	$indexvar[4];
        	if ($no_unique == 1) {
        		$idx_def	=	$idx_def.'INDEX '.$key_name.' ('.$column_name.'),';
        	} else {
        		$idx_def	=	$idx_def.'UNIQUE '.$key_name.' ('.$column_name.'),';
        	}
        	/*
  			or    PRIMARY KEY (index_col_name,...)
  			or    KEY [index_name] (index_col_name,...)
  			or    INDEX [index_name] (index_col_name,...)
  			or    UNIQUE [INDEX] [index_name] (index_col_name,...)
        	for ($jj=0; $jj<7; $jj++) {
        		//echo $indexvar[$jj].'<br>';
        	}
  			*/
        }
	}
	echo '</table><br>';
	}
}
?>
</html>
