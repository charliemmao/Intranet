<html>
<?php
$pat = '"';
//$update = "Y";
echo "Find patten: \"$pat\" update = $update<br>";
###############First Name#######################
$sql = "SELECT firstname_id, firstname FROM library.auth_first WHERE firstname LIKE '%\$pat%';";
$result = mysql_query($sql);
include("err_msg.inc");
while (list($firstname_id, $firstname) = mysql_fetch_array($result)) {
	echo "$firstname<br>";
	$firstname = ereg_replace("\"","",$firstname);//"
	$sql0 = "UPDATE library.auth_first SET firstname='$firstname' WHERE firstname_id='$firstname_id';";
	echo $sql0."<br>";
	if ($update = "Y") {
		$res = mysql_query($sql0);
	}
}
###############Last Name#######################
$sql = "SELECT lastname_id, lastname FROM library.auth_last WHERE lastname LIKE '%\$pat%';";
$result = mysql_query($sql);
include("err_msg.inc");
while (list($lastname_id, $lastname) = mysql_fetch_array($result)) {
	echo "$lastname<br>";
	$lastname = ereg_replace("\"","",$lastname);//"
	$sql0 = "UPDATE library.auth_last SET lastname='$lastname' WHERE lastname_id='$lastname_id';";
	echo $sql0."<br>";
	if ($update = "Y") {
		$res = mysql_query($sql0);
	}
}
###############Middle Name#######################
$sql = "SELECT middlename_id, middlename FROM library.auth_middle WHERE middlename LIKE '%\$pat%';";
$result = mysql_query($sql);
include("err_msg.inc");
while (list($middlename_id, $middlename) = mysql_fetch_array($result)) {
	echo "$middlename<br>";
	$middlename = ereg_replace("\"","",$middlename);//"
	$sql0 = "UPDATE library.auth_middle SET middlename='$middlename' WHERE middlename_id='$middlename_id';";
	echo $sql0."<br>";
	if ($update = "Y") {
		$res = mysql_query($sql0);
	}
}
?>
</html>
