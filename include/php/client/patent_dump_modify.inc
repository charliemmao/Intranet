<html>
<?php

$str = "ACITELLI";
/*
$sql = "SELECT firstname_id, firstname FROM library.auth_first WHERE firstname like '%$str%';";
echo $sql."<br>";
$result = mysql_query($sql);
include("err_msg.inc");
while (list($firstname_id, $firstname) = mysql_fetch_array($result)) {
	$tmp = dquote($firstname);
	echo "$firstname_id, $firstname -> $tmp<br>";
} 

$sql = "SELECT middlename_id, middlename FROM library.auth_middle WHERE middlename like '%$str%';";
echo $sql."<br>";
$result = mysql_query($sql);
include("err_msg.inc");
while (list($middlename_id, $middlename) = mysql_fetch_array($result)) {
	$tmp = dquote($middlename);
	echo "$middlename_id, $middlename -> $tmp<br>";
} 

$sql = "SELECT lastname_id, lastname FROM library.auth_last WHERE lastname like '%$str%';"; 
echo $sql."<br>";
$result = mysql_query($sql);
include("err_msg.inc");
while (list($lastname_id, $lastname) = mysql_fetch_array($result)) {
	//$tmp = dquote($lastname);
	echo "$lastname_id, $lastname -> $tmp<br>";
	$sql1 = "DELETE FROM library.auth_last WHERE lastname_id='$lastname_id' LIMIT 1;";
	$res = mysql_query($sql1);
	include("err_msg.inc");
	$sql1 = "UPDATE library.auth_last SET lastname='$tmp' WHERE lastname_id='$lastname_id';";
	$res = mysql_query($sql1);
	include("err_msg.inc");
} 
//*/

$sql = "SELECT id, inventor FROM library.dumppatent WHERE inventor like '%$str%';";
echo $sql."<br>";
$result = mysql_query($sql);
include("err_msg.inc");
while (list($id, $inventor) = mysql_fetch_array($result)) {
	//$tmp = dquote($inventor);
	$tmp = ereg_replace("$str", "", $tmp);
	echo "$id, $inventor -> $tmp<br>";
	/*
	$sql1 = "UPDATE library.dumppatent SET inventor='$tmp' WHERE id='$id';";
	$res = mysql_query($sql1);
	include("err_msg.inc");
	//*/
} 

function dquote($str) {
	return ereg_replace('"',"",$str); 
}
exit;

?>
</html>