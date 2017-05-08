<html>
<?php
	$cstr = "user=$user dbname=$pgdb port=5432 password=";
	$conn = pg_Connect($cstr);
	//echo "$cstr<br>";
?>
</html>