<html>
<?php
	$user	=	$email_name;
	$userpwd	=	$userpwd;
	$host	=	"localhost";
	$contid 	= 	mysql_connect($host,$user,$userpwd);
    if (!$contid) {
        echo '<h2>Password or system error, please try again.</h2>';
        exit;
    }
?>
</html>