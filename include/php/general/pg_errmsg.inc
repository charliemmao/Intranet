<html>
<?php
if (pg_errormessage ($conn)){
	include("find_admin_ip.inc");
	if (getenv("remote_addr") == $adminip) {
		echo "Error in this Query: $sql<br>";
	} else {
		echo '<h2><font color="#FF0000">Sorry, query error, 
		process has been aborted.<br>Please contact system administrator.</font></h2><br>';		
		exit;
	}
}
?>
</html>