<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title></title>
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
</head>

<!--
<frameset framespacing="0" rows="23" border="0" frameborder="0">
  <noframes>
  <body>

  <p>This page uses frames, but your browser doesn't support them.</p>

  </body>
  </noframes>
<frameset framespacing="0" rows="26,115">
  <frame name="top" scrolling="auto" target="contents" src="admin_dbedit_top.php">
  
  <frameset cols="41,114">
    <frame name="contents" target="main" src="admin_dbedit_left.php">
    <frame name="main" src="admin_dbedit_main.php" scrolling="auto">
  </frameset>
  
</frameset>
-->

<?php
	include("admin_access.inc");
	include('rla_functions.inc');

   	$admintop	=	'?'.base64_encode($userinfo."&dbname=$dbname&d=d");
 	if ($dbname == "timesheet") {
   		$adminqry 	=	'?'.base64_encode($userinfo."&dbname=$dbname&tablename=projcodes&tabaction=editrcd&d=d");
	} elseif ($dbname == "mysql") {
   		$adminqry 	=	'?'.base64_encode($userinfo."&dbname=$dbname&tablename=user&tabaction=editrcd&d=d");
	} elseif ($dbname == "inventory") {
   		$adminqry 	=	'?'.base64_encode($userinfo."&dbname=$dbname&tablename=primlist&tabaction=editrcd&d=d");
	} elseif ($dbname == "library") {
   		$adminqry 	=	'?'.base64_encode($userinfo."&dbname=$dbname&tablename=lib_primlist&tabaction=editrcd&d=d");
   	} elseif ($dbname == "rlafinance") {
   		$adminqry 	=	'?'.base64_encode($userinfo."&dbname=$dbname&tablename=control&tabaction=editrcd&d=d");
	} elseif ($dbname == "logging") {
   		$adminqry 	=	'?'.base64_encode($userinfo."&dbname=$dbname&tablename=logout&tabaction=editrcd&d=d");
	}
	
	/*
   	$admintsh	=	'?'.base64_encode($userinfo."&dbname=$dbname&tablename=projcodes&tabaction=editrcd&d=d");
   	$adminmys	=	'?'.base64_encode($userinfo."&dbname=$dbname&tablename=user&tabaction=editrcd&d=d");
   	$admininv	=	'?'.base64_encode($userinfo."&dbname=$dbname&tablename=primlist&tabaction=editrcd&d=d");
   	$adminlib	=	'?'.base64_encode($userinfo."&dbname=$dbname&tablename=lib_primlist&tabaction=editrcd&d=d");
   	$adminlog	=	'?'.base64_encode($userinfo."&dbname=$dbname&tablename=logout&tabaction=editrcd&d=d");
	//*/
	$frameset = "frameset";
	$noframes = "noframes";
	$body = "body";
	$frame = "frame";
	$spe =   "<p>This page uses f"."rames, but your browser"." doesn't support them.</p>";

	echo "<$frameset framespacing=\"0\" rows=\"23\" border=\"0\" frameborder=\"0\">";
  		echo "<$noframes>";
  			echo "<$body>";
  			echo "$spe";
  			echo "</$body>";
  		echo "</$noframes>";
  		
  		echo "<$frameset framespacing=\"0\" rows=\"26,115\">";
  			echo "<$frame name=\"top\" scrolling=\"auto\" target=\"contents\" src=\"admin_dbedit_top.php$admintop\">";
  			
  			echo "<$frameset cols=\"41,114\">";
   			echo "<$frame name=\"contents\" target=\"main\" src=\"admin_dbedit_left.php$adminqry\">";
  			echo "<$frame name=\"main\" src=\"admin_dbedit_main.php$adminqry\" scrolling=\"auto\">";
 			echo "</$frameset>";
  			
		echo "</$frameset>";
	echo "</$frameset>";
	
?>
</html>