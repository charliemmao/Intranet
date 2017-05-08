<html>

<head>
<title></title>
</head>

<?php
	include("connet_root.inc");
	$pws_str	=	'"cm"';
	if ($de_encode== "decode") {
		$sql='select ENCODE('.$str.','.$pws_str.')';
	} elseif ($de_encode== "encode") {
		$sql='select DECODE('.$str.','.$pws_str.')';
	}
	$result = mysql_query($sql); 
	
	while ($myrow = mysql_fetch_array($result)) {
  		$i=0;
		$i=(int)1.5;
	  	while (list($key,$val) = each($myrow)) {
	  		$i +=1;
	  		$j = 2*(int)($i/2);
			if ($i == $j) {
  				//printf('%s<br>',"$val");
  				$out = $val;
  			}
    	}
    }
	//echo $str.' with '.$pws_str.' = '.$out.' after '.$de_encode.'<br>';
    
?>
</html>
