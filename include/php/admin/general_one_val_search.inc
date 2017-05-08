<html>
<?php
	include("connet_root_once.inc");
	$result = mysql_query($sql,$contid); 
	include('err_msg.inc');
	while ($myrow = mysql_fetch_array($result)) {
		$i=1;
	  	while (list($key,$val) = each($myrow)) {
	  		$i +=1;
	  		$j = 2*(int)($i/2);
			if ($i == $j) {
  				//printf('%s<br>',"$val");
  				$out = $val;
  			}
    	}
    }
	mysql_close();
	//echo 'Result is '.$out.'<br>';
?>
</html>
