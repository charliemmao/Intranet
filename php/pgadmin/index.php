<?php
/* $Id: index.php,v 1.1.1.1 2000/11/10 04:35:56 dwilson Exp $ */

  include("lib.inc.php");
?>

<html>
<head>
<title><?php echo "$realm"; ?></title>
</head>

<frameset cols="150,*" rows="*" border="0" frameborder="0"> 
  <frame src="left.php?server=<?php echo $server;?>" name="nav">
  <frame src="main.php?server=<?php echo $server;?>" name="main">
</frameset>
<noframes>
<body bgcolor="#FFFFFF">
	You must use a frames enabled browser
</body>
</noframes>
</html>
