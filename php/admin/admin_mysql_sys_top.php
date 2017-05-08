<html>

<head>
<title>MySQL DB Administration</title>
<LINK REL="StyleSheet" HREF="style_admin.css" TYPE="text/css">
</head>
<body background="rlaemb.JPG" leftmargin="50">
<h2 align=center>MySQL Admin Main Page<font size=2>
<?php
	echo "[<a href=\"/$phpdir/adminctl_top.php\" target=_top>Admin Main Page</a>]</a>
	[<a href=\"/$phpdir/admin_mysql_sys.php\" target=_top>Refresh</a>]</a>";

	echo "<br>MySQL Server on ".getenv("server_name")."&nbsp;at ".date("Y-m-d H:i:s");
?>
</font></h2>
</body>
</html>