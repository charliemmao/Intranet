<html>
<?php
/*
	echo 'rcdaction: '.$rcdaction.'<br>';
	echo 'dbname: '.$dbname.'<br>';
	echo 'tablename: '.$tablename.'<br>';
	echo 'fldname: '.$fldname1.'<br>';
	echo 'fldvalue: '.$fldvalue1.'<br>';
//*/

	include('data_entry_rule.inc');
	$no_qry_fld	=	0;
	if ($rcdaction == 'addrcd') {
		//echo "limit 1<br>";
    	$sql	=	'SELECT * FROM '.$tablename.' LIMIT 1;';
    } else {	//$rcdaction=modify	$rcdaction=delete
    	$sql	=	'SELECT * FROM '.$tablename;
    	if ($fldname != "") {
    		$where	=	' WHERE '.$fldname.' = "'.$fldvalue.'"';
    		$qry_field[$no_qry_fld]	=	$fldname;
    		$qry_val[$no_qry_fld]	=	$fldvalue;
			$no_qry_fld++;
    	} else {
    		$where	=	' WHERE '.$fldname1.' = "'.$fldvalue1.'"';
    		$qry_field[$no_qry_fld]	=	$fldname1;
    		$qry_val[$no_qry_fld]	=	$fldvalue1;
    		$no_qry_fld++;
    		$icount	=	2	;
    		$tfldname	=	'fldname'.$icount;
    		$tfldvalue	=	'fldvalue'.$icount;

    		while ($$tfldname) {
    			$qry_field[$no_qry_fld]	=	$$tfldname;
    			$qry_val[$no_qry_fld]	=	$$tfldvalue;
    			$no_qry_fld++;
    			$where	=	$where.' and '.$$tfldname.' = "'.$$tfldvalue.'"';
    			$icount++;
    			$tfldname	=	'fldname'.$icount;
    			$tfldvalue	=	'fldvalue'.$icount;
    		}
     	$sql	=	$sql.$where.";";
	   	}
    }
    //echo $sql.'<br>';

	include("connet_root.inc");
	include('collectfieldinfo.inc');
    mysql_select_db($dbname);
    $result = mysql_query($sql);
    if (mysql_errno()){
    	include("err_msg.inc");
    	exit;
    }
    $fields = mysql_num_fields($result);
    $rows   = mysql_num_rows($result);
    //$table = mysql_field_table($result, 0);  // same as $tablename
    
# transfer data to an array
	while ($myrow = mysql_fetch_array($result)) {
		$m=1;
		$k=0;
		while (list($key,$val) = each($myrow)) {
           $m +=1;
           $j = 2*(int)($m/2);
           if ($m == $j) {
                 if ($rcdaction == 'addrcd') {
                    	$val_list[$k]	= "";
					} else {		
                    	$val_list[$k]	=	$val;
                 }
                 //echo 'Value'.$k.':  '.$val_list[$k].'<br>';
                 $k = $k+1;
            }
		}
	}

/*
	echo '<table border="1">';
	for ($row1=0; $row1<$row;$row1++) {
		echo '<tr>';
		for ($col1=0; $col1<$col;$col1++) {
			echo '<td>'.$field_prp[$row1][$col1].'</td>';
			// 0: field name; 1: field type; 2: null/not null
			// 3: key; 4: default; 5: extra (such as auto_increment)
			// 6: data length for varchar
		}
		echo '</tr>';
	}
	echo '</table>';
	echo '<br>';
//*/
	
	if ($rcdaction == 'modify' or $rcdaction == 'delete') {
		if ($rcdaction == 'modify') {
			echo '<h3>Record to modify is:</h3>';
		} else {
			echo '<h3>Record to delete is:</h3>';
		}
		
		echo '<table border="0" cellspacing="0" cellpadding="0">';
		for ($i=0; $i<$no_qry_fld; $i++) {
  			$temp	=	strtoupper($qry_field[$i]);
  			$temp	=	ereg_replace("_"," ",$temp);
  			echo '<tr>	<td><b>';
  			echo $temp;
  			echo '</b></td><td>&nbsp;</td>';
  			
  			echo '<td><b><font color="#0000FF">';
  			echo $qry_val[$i];
  			echo '</b></font></td></tr>';
		}
		echo '</table><br>';
	}

# display records
	echo '<table border="1">';
	for ($i=0; $i<$row;$i++) {
		$obname	=	$field_prp[$i][0];
		$obvalue	=	$val_list[$i];
		if ($obvalue == '') {
			$obvalue	=	$field_prp[$i][4];
		} 
		$lable	=	strtoupper($obname);
		$lable		= ereg_replace('_', ' ', $lable);
			
		if ($field_prp[$i][5] == 'auto_increment') {
			# if field has auto_increment properties, it will be hidden
				//echo '<tr><td>'.$lable.'</td>';
				echo '<td><input type="hidden" name="';
				echo $obname.'" size="30", value="'.$obvalue.'"></td></tr>';
		} else {
			$qryfld =	'n';
			for ($ii= 0; $ii<$no_qry_fld ;$ii++) {
				if ($tablename != 'acccodedef') {
				if ($tablename != 'accesslevel') {
    			 	if ($obname == $qry_field[$ii]) {
    					$qryfld =	'y';    			
    				}
    			}
    			}
			}
			$obvalue	=	trim($obvalue);
			echo '<tr>';
			if ($qryfld ==	'n') {
				if ($field_prp[$i][2] == 'NOT NULL') {
					echo '<td><font color="#FF0000">'.$lable.'</font></td>';
				} elseif ($field_prp[$i][1] == 'date') {
					echo '<td><font color="#0000FF">'.$lable.'</font></td>';
				} else {
					echo '<td>'.$lable.'</td>';				
				}
				if ($field_prp[$i][1] == 'text' || 
					($field_prp[$i][1] == 'varchar' && $field_prp[$i][1]>50)) {
					echo '<td><textarea rows="2" name="'.$obname.'" cols="60">'.$obvalue.'</textarea>';
				} else {
					echo '<td><input type="text" name="';
					echo $obname.'" size="60", value="'.$obvalue.'"></td>';
				}
			} elseif ($qryfld ==	'y') {
			/*
				if ($field_prp[$i][2] == 'NOT NULL') {
					echo '<td><font color="#FF0000">'.$lable.'</font></td>';
				} elseif ($field_prp[$i][1] == 'date') {
					echo '<td><font color="#0000FF">'.$lable.'</font></td>';
				} else {
					echo '<td>'.$lable.'</td>';				
				}
			//*/
				echo '<td><input type="hidden" name="';
				echo $obname.'" size="30", value="'.$obvalue.'"></td>';
			}
			echo '</tr>';
		}
	}
	echo '</table>';
function red_face($str){
	echo '<font color="#FF0000">'.$str.'</font>';
}

function blue_face($str){
	echo '<font color="#0000FF">'.$str.'</font>';
}

function date_type($str){
	echo date('Y-m-d');
}

?>
</html>