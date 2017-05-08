<html>
<?php
	if ($priviledge != '' && $email_name == '') {
		$sql =	"select email_name from logging.accesslevel where priviledge='".$priviledge."';";
		include('general_one_val_search.inc');
		$ename	=	$out;
	
		$sql =	"select computer_ip_addr from logging.accesslevel where priviledge='".$priviledge."';";
		include('general_one_val_search.inc');
		if (getenv("remote_addr") == $out) {
			//echo 'match ip<br>';
		} elseif ($ename != '' && $ename == $email_name) {
			//echo 'match ip<br>';
		} elseif ($ename == '') {
			//echo 'Email name not found.<br>';
			exit;
		} elseif ($ename != $email_name) {
			//echo "Email name found but not equal to current user's email name.<br>";
			exit;
		}
	} elseif ($email_name != '') {
		$sql =	"select priviledge from logging.accesslevel where email_name='".$email_name."';";
		include('general_one_val_search.inc');
		$priv	=	$out;
	
		$sql =	"select computer_ip_addr from logging.accesslevel where email_name='".$email_name."';";
		include('general_one_val_search.inc');
		if ($priv == $priviledge ) {
			//echo 'match priviledge <br>';
		} elseif ($out == $email_name) {
			//echo 'match email name<br>';
		} else {
			//echo "Email name and IP address don't match, exit program.<br>";
			exit;
		}
	}
?>
</html>
