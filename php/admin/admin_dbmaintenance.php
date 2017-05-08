<html>

<head>
<title>Database Maintenance</title>
</head>

<body bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG" leftmargin="50">

<?php
## control page access
include("admin_access.inc");
include('rla_functions.inc');
echo "<a id=top><h1 align=center>Database Maintenance</h1></a>";

echo "<p align=center><a href=\"".$PHP_SELF."$admininfo\">[Refresh]</a>";
echo "<a href=\"/$phpdir/adminctl_top.php$admininfo\">[Admin Main Page]</a>";
echo "<hr>";

include("connet_root_once.inc");
$ret = system("mysqladmin proc stat",$str);
echo $ret ."<br>";
echo $str."<br>";
/*
$sql = "mysqladmin proc stat";
$result = mysql_query($sql);
include("err_msg.inc");
while (list($key, $val) = each($result)) {
   echo "$key => $val<br>";
}

	#MySQL Utilites

	#Optimization
	mysqld --help
	mysqladmin variables
	
	--log-update=file_name
	If you want to update a database from update log files, you could do the 
	following (assuming your update logs have names of the form file_name.#
	ls -1 -t -r file_name.[0-9]* | xargs cat | mysql
//*/

backtotop();
function backtotop(){
echo "<br><hr><a href=#top>Back to top</a><a id=end></a><br><br><br>";
}

?>
</body>
