<html>
<?php

//$sql    =   'SHOW COLUMNS FROM '.$tablelist[$i].' FROM '.$dbname.';';
$sql    =   'SHOW COLUMNS FROM '.$dbname.'.'.$tablelist[$i].';';
//echo $sql.'<br>';

$result =   mysql_query($sql);
include('err_msg.inc');
$col_no =   mysql_num_rows ($result);
echo '<br><br><h3><a id='.$tb_names[$i].'>Table <font color="#0000FF">'.$tablelist[$i].'</font> has '.$col_no.' fields.</a></h3>';
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
}

$result =   mysql_query($sql);
include('err_msg.inc');
$col_no =   mysql_num_rows ($result);
$fld_def	=	"";
if ($col_no) {		
 	while ($myrow = mysql_fetch_array($result)) {
		$k1=(int)1.5;
		echo '<tr>';
		while (list($key,$val) = each($myrow)) {
			$k1 +=1;
			$k2 = 2*(int)($k1/2);
            if ($val == "") {
                $val = " ";
            } elseif (strtoupper(trim($val)) == "YES") {
                $val = "NULL";
            } elseif (strtoupper(trim($val)) == "PRI") {
                $val = "PRIMARY KEY";
            }
            
            if ($k1 == "6" ) {
                if (trim($val) == "" ) {
                    $val = "NOT NULL";
                }
            }
            
            if ($k1 == "8" || $k1 == "10" || $k1 == "12") {
                if (trim($val) == "" ) {
                    $val = "---";
                }
            }
            
            if ($k1 == $k2) {
                echo '<td>'.$val.'</td>';
                if ($val == '---') {
                		$val	=	"";
                }
                if ($key == 4) {
                		$fld_def	=	$fld_def." DEFAULT '".$val."'";
                } else {
                	if ($val != '') {
                		$fld_def	=	$fld_def.' '.$val;
                	}
				  }
            }
        }
        echo '</tr>';
        $fld_def	=	$fld_def.',';
	}
	echo '</table><br>';
}
?>
</html>
