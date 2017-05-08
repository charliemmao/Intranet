<html>
<?php
$dbname     =   'timesheet';
$tablename      =   'employee';
$fldname		= 'computer_ip_addr';    
include("connet_root_once.inc");
mysql_select_db($dbname,$contid);
$ip = getenv("remote_addr");

#echo "email_name='$email_name'<br>";
$sql    =   "SELECT * FROM ".$dbname.'.'.$tablename." WHERE ".$fldname."='".$ip."';";
$result = mysql_query($sql,$contid);
include('err_msg.inc');

$no =   mysql_num_rows($result);
if ($no) {
    include('rcd_parse.inc');
    $email_name = $fld_value[0];
    $logon_name = $fld_value[1];
    $title = $fld_value[2];
    $first_name = $fld_value[3];
    $middle_name = $fld_value[4];
    $last_name = $fld_value[5];
    $fldname   = $fld_name[6];
    $fldvalue  = $fld_value[6]; 
}

$sql1 = $sql;

$sql = "SELECT priviledge as priv FROM logging.accesslevel WHERE email_name='$email_name';";
#echo "$sql<br>";

if ($email_name == "webmaster") {
	//echo "$sql<br>";
}
$result = mysql_query($sql);
include('err_msg.inc');
$priv = "";
if (mysql_num_rows($result)) {
	list($priv) = mysql_fetch_array($result);
}

mysql_close($contid);
$basestr	=	 '&dbname='.$dbname.'&tablename='.$tablename
				.'&email_name='.$email_name.'&logon_name='.$logon_name
				.'&title='.$title.'&first_name='.$first_name
				.'&middle_name='.$middle_name.'&last_name='.$last_name
				.'&priv='.$priv.'&fldname='.$fldname.'&fldvalue='.$fldvalue;
if ($ip == "192.168.223.174" or $ip == "192.168.223.122") {
	//echo "$sql1<br>email_name: $email_name<br>priv: $priv<br>$basestr<br>";
	//exit;
}

?>
</html>