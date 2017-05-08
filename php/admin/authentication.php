<html>
<?php
$headers = getallheaders();
while (list($header, $value) = each($headers)) {
    echo "$header: $value<br>\n";
}
exit;
$meta = "meta";
if (!($PHP_AUTH_USER)) {
	Header("WWW-Authentication: basic realm=Restricted Area");
	Header("HTTP/1.0 401 Unauthorised");
	echo "<$meta http-equiv=\"refresh\" content=\"0;url=index.php\">";
	exit;
} else {
	include("connet_root_once.inc");	// test mysql connection
	
	$host	=	"localhost";
	$user = strtolower($PHP_AUTH_USER);
	$userpwd = $PHP_AUTH_PW;
	$contid 	= 	mysql_connect($host,$user,$userpwd);
	if (!$contid) {
		echo '<h2>User name, password or system error, please try again.</h2>';
		exit;
	}
	
	Header("WWW-Authentication: basic realm=Restricted Area");
	Header("HTTP/1.0 401 Unauthorised");
	echo "<center><p>&nbsp;";
	echo "<p><table width='350' border=1>
		<tr><td bgcolor=#d8e9d6 align='center'>
		<font face='helvetica' color=#000080 size=2>
		<b>Please check your ID and PASSWORD
		<br>Your login has FAILED</b>
		<br><font size=1>User your Browser's BACK key to return</font>
		</td></tr>
		</table></center>";
	exit;
}

?>
</html>