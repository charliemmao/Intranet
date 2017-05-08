<html>
<?php
	include('connet_root_once.inc');
	$sql	=	"SELECT $select_list from timesheet.employee where $where;";
	
	$result	=	mysql_query($sql,$contid);
	include('err_msg.inc');
	if ($result) {
		$staffno	=	0;
		while ($myrow = mysql_fetch_array($result)) {
			$i=1;
			$s1	 = 0;
			while (list($key,$val) = each($myrow)) {
				$i +=1;
				$j = 2*(int)($i/2);
				if ($i != $j) {
				    $stafflist[$s1][$staffno]	=	$val;
                  //echo $val.'<br>';
                  $s1++;
				}
			}	
			$staffno++;
		}	
	}
	mysql_close($contid);
	//echo $staffno.' '.$s1.'<br>';
	for ($i=0; $i<$staffno; $i++) {
		$tmp = $stafflist[0][$i];
		$fromstafftono[$tmp] = $i;
	}
	?>
</html>
