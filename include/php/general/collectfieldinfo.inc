<html>
<?php

$qrystr    =   'SHOW COLUMNS FROM '.$dbname.'.'.$tablename.';';
//echo $qrystr.'<br>';

$result =   mysql_query($qrystr);
include('err_msg.inc');
$col_no =   mysql_num_rows ($result);

if ($col_no) {		
	//echo '<table border="1">';
	$row	=	0;
 	while ($myrow = mysql_fetch_array($result)) {
		$col	=	0;
		$k1 = 1;
		//echo '<tr>';
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
            
            if ($k1 == $k2) {
                //echo '<td>'.$val.'</td>';
                $field_prp[$row][$col]	=	$val;
                $col++;
            }
        }
        //echo '</tr>';
        $row++;
	}
	for ($row1 = 0; $row1<$row; $row1++) {
		$temp1	=	$field_prp[$row1][1];
		$long	=	strlen($temp1);
		if ($long > 7) {
			$temp	=	substr($temp1, 0, 7);
			if ($temp == 'varchar') {
				$field_prp[$row1][1]	=	$temp;
				$temp	=	substr($temp1, 7, $long-7);
				$long	=	strlen($temp);
				$temp	=	substr($temp, 1, $long-2);
				$field_prp[$row1][6]	=	$temp;
			}
		}
	}
	$col++;
/*
	echo "<table border=1>";
	for ($row1=0; $row1<$row;$row1++) {
		echo '<tr>';
		for ($col1=0; $col1<$col;$col1++) {
			echo '<td>'.$field_prp[$row1][$col1].'</td>';
		}
		echo '</tr>';
	}
	echo "</table>";
	exit;
//*/

	//echo '</table><br>';
}

?>
</html>

