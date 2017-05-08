<html>
<?php
#	user db on mysql server
$result = mysql_list_dbs();
$i = 0;
$j = 0;
while ($i < mysql_num_rows ($result)) {
   	$tmp = mysql_tablename ($result, $i);
	if ($tmp == 'test' || $tmp  == 'mysql'){ //$tmp  == 'logging' || 
   	} else {
		$user_db_list[$j]=$tmp;
		$j++;
	}
	$i++;
}
?>
</html>