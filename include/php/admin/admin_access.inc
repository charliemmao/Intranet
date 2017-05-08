<html>
<?php
	include('connet_root_once.inc');
	include('str_decode_parse.inc');
	$pc_addr = getenv("remote_addr");
	
	$sql = "SELECT item 
        FROM logging.sysmastertable 
        WHERE description='$pc_addr';";
    $result = mysql_query($sql);
    include("err_msg.inc");
    list($item) = mysql_fetch_array($result);
    if (!$item) {
    	exit;
    } else {
		if ($email_name == "") {
			$sql = "SELECT description as email_name
        		FROM logging.sysmastertable 
        		WHERE item='Admin_Name';";
    		$result = mysql_query($sql);
    		include("err_msg.inc");
    		list($email_name) = mysql_fetch_array($result);
    		
			$sql = "SELECT priviledge as priv
        		FROM logging.accesslevel 
        		WHERE email_name='$email_name'";
    		$result = mysql_query($sql);
    		include("err_msg.inc");
    		list($priv) = mysql_fetch_array($result);
			if ($priv != "00") {
				exit;
			}
			
			$sql = "SELECT logon_name, title, first_name, middle_name, last_name from timesheet.employee 
				where email_name='$email_name';";
			$result = mysql_query($sql);
			include("err_msg.inc");
			list($logon_name, $title, $first_name, $middle_name, $last_name) = mysql_fetch_array($result);
			include("userinfo.inc"); //$userinfo
			$entrypoint = "<font size=1>&nbsp;&nbsp;[By Special Arrangement]</font>";
		} else {
			$entrypoint = "<font size=1>&nbsp;&nbsp;[from $pc_addr]</font>";
		}
	}
	include("userinfo.inc"); //$userinfo
	$admininfo	=	'?'.base64_encode($userinfo);
?>
</html>