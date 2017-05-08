<html>
<?php
//echo "myid-in $myid<br>";
if ($myid == '') {
	$user	=	$email_name;
	$userpwd	=	$userpwd;
	$host	=	"localhost";
	/*
	echo 'user: '.$user.'<br>';
	echo 'password: '.$userpwd.'<br>';
	//*/
	$contid 	= 	mysql_pconnect($host,$user,$userpwd);
    if (!$contid) {
        echo '<h2>Please check your password and try again.</h2>';
        exit;
    }
	$myid = $contid;
	$action_log = $user." ".(int)($contid);
	include("connet_logging.inc");
} else {
	$contid = $myid;
	//$myid	$myrootid
}
//echo "myid-out $myid<br>";
?>
</html>