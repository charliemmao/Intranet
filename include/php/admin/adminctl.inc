<html>

<head>
<title>Administrator Page</title>
</head>
<body>
<?php
	include("find_admin_ip.inc");
	include("phpdir.inc"); 
	if ($iamadmin){
		echo '<br><br>';
		echo '<p align="center"><br>';
		echo '<a href="'.$phpdir.'/adminctl.php" target="_top"><b><font size="6">Administrators Access Only</font></b></a>';
	}
?>

</body>
</html>
