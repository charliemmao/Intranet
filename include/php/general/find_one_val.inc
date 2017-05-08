<html>
<?php
	$result	=	mysql_query($qry);
	include('err_msg.inc');
	$out = "";
	if (mysql_num_rows($result)) {
		while ($myrow = mysql_fetch_array($result)) {
			$daywk=1;
	  		while (list($key,$val) = each($myrow)) {
	  			$daywk +=1;
	  			$jdaywk = 2*(int)($daywk/2);
				if ($daywk == $jdaywk) {
  					//echo $key.' '.$val.'<br>';
  					$out = $val;
  				}
    		}
    	}
    }
?>
</html>
