<html>
<?php
	$result = mysql_list_dbs();
	$i = 0;
	$j = 0;
	while ($j < mysql_num_rows ($result)) {
   		$tmp = mysql_tablename ($result, $j);
   		if ($tmp != 'test'){
   			if ($tmp  != 'mysql'){
   				$dblist[$i] = $tmp;
				$i++;
			}
		}
		$j++;
   	}

	$notab = 0;
	for ($j=0; $j<count($dblist); $j++) {
		$dbname = $dblist[$j];
	  	$result = mysql_list_tables($dbname);
		if (mysql_num_rows ($result)) {
			$i = 0;
			while ($i < mysql_num_rows ($result)) {
   				$tblnames = mysql_tablename ($result, $i);
   				$tablist[$notab] = "$dbname.$tblnames";
   				$i++;
   				$notab++;
			}
		}
	}
	
if ($col == 1) {
	$nocol = 0;
	for ($j=0; $j<$notab; $j++) {
		$tmp = explode(".", $tablist[$j]);
		$dbname = $tmp[0];
		$tblnames = $tmp[1];
	  	$result = mysql_list_fields($dbname, $tblnames);
	  	include("err_msg.inc");
		$fields = mysql_num_fields($result);
		$i = 0;
		while ($i < $fields) {
			$fldnames = mysql_field_name($result,$i);
   			$collist[$nocol] = "$dbname.$tblnames.$fldnames ";
   			$nocol++;
   			$i++;
		}
	}
}
?>
</html>