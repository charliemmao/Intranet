<html>
<?php
include("connet_root_once.inc");
//find_admin_ip.inc
$clientip = getenv("remote_addr");
$adminip = "";
$sql = "SELECT description as ipadmin
        FROM logging.sysmastertable 
        WHERE item='Admin_IP';";
    $result = mysql_query($sql);
    include("err_msg.inc");
    while (list($ipadmin) = mysql_fetch_array($result)) {
    	if ($clientip == $adminip) {
    		$iamadmin = "y";
    		$adminip = $ipadmin;
    	}
    }
if ($adminip) {
    $sql = "SELECT email_name as adminname 
        FROM logging.accesslevel 
        WHERE computer_ip_addr='$adminip'";
    $result = mysql_query($sql);
    include("err_msg.inc");
    list($adminname) = mysql_fetch_array($result);
}            

$sql = "SELECT description as netip
        FROM logging.sysmastertable 
        WHERE item='Net_IP';";
    $result = mysql_query($sql);
    include("err_msg.inc");
    list($netip) = mysql_fetch_array($result);
    
$sql = "SELECT priviledge as privspec 
        FROM logging.accesslevel 
        WHERE email_name='$email_name';";
    $result = mysql_query($sql);
    include("err_msg.inc");
    list($privspec) = mysql_fetch_array($result);
    if ($privspec == 10) {
    	$director = "$email_name";
    }
?>
</html>
