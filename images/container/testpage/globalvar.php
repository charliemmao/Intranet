<html>
<body>

<?php
function  db_query($sql) {
	global $db;
	$result = mysql_query($sql,$db);
	return $result;
}

$sql = "SELECT * FROM mytable";
$result = db_query($sql);
?>

</body>
</html>
