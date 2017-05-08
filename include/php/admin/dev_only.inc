<html>
<?php
	include("find_admin_ip.inc");
	if (getenv("remote_addr") == $adminip) {
		$dev_access = 1;
	} else {
		$dev_access = 0;
	}
?>
</html>
