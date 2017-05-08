<html>
<?php
	include('connet_root_once.inc');
	$sqlacc	=	"SELECT * from logging.acccodedef";
	$resultacc	=	mysql_query($sqlacc,$contid);
	include('err_msg.inc');
	if ($resultacc) {
		$no_code	=	0;
		while ($myrow = mysql_fetch_array($resultacc)) {
			$i=1;
			$s1	=	0;
			while (list($key,$val) = each($myrow)) {
              //echo $key.': '.$val.'<br>';
				$i +=1;
				$j = 2*(int)($i/2);
				if ($i != $j) {
                  $acccodelist[$s1][$no_code]	=	$val;
              	 //echo $key.': '.$val.'<br>';
                  $s1++;
				}
			}	
			$no_code++;
		}	
	}
	mysql_close($contid);
	echo '<b>PRIVILEDGE Definition</b><br><SELECT name="code" size="1">';
	for ($j=0; $j<$no_code; $j++) {
		$str	=	$acccodelist[0][$j].': '.$acccodelist[1][$j];
		echo '<OPTION>'.$str;
	}
	echo '</SELECT><br>';

	//echo $no_code.' '.$s1.'<br>';
?>
</html>
