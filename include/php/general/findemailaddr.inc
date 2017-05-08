<html>

<head>
<title></title>
</head>

<?php
	include("connet_root_once.inc");
	mysql_select_db("mysql",$contid);
	$pws_str	=	$email_name."reslab";
	$sql = 'Select User from user where User="'.$email_name.'";';
	//echo $sql.'<br>';
	$result = mysql_query($sql,$contid); 
	include('err_msg.inc');
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
	mysql_close();
	//echo 'user is = '.$out.'<br>';
?>
</html>
