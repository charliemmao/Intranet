<?php
include("connet_root_once.inc");
$sql = "SELECT description as mydomain
        FROM logging.sysmastertable 
        WHERE item='Domain_Name';";

    $result = mysql_query($sql);
    include("err_msg.inc");
    list($mydomain) = mysql_fetch_array($result);
    $mname = getenv('HTTP_HOST');
    $mname0 = ereg_replace(".".$mydomain, "", $mname);
	$thisserver = "$mname0.$mydomain";
	$rlaserver = "$mname0.rla.com.au";
	if ( $rlaserver != $thisserver ) {
		echo "<br><br>";
	}
?>
