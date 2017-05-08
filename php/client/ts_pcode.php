<html>

<head>
<title></title>
<style>
	.help {
		position: absolute; posTop: 0; posleft: 0;
		border-width: thin; border-style: solid;
		background-color: yellow; color: black;
		height=80; width=100;
	}
</style>

</head>

<body onload="ClearFlyOver();" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG" leftmargin="20">
<div id=FOArea class="help" style="dispaly: none"></div>

<?php
include('flyover.inc');
include('str_decode_parse.inc');
//include('user_info_output.inc');
	include("userinfo.inc"); //$userinfo
	$qry0	=	userinfo.'&dbname='.$dbname.'&dummy1=dummy';
	$rlacurrentcode = 1;
	include('codearray.inc');
	echo '<ul>';
	echo '<a id=top></a>';
	//echo '<li><a href="#code_list">To code list</a></li><br>';
	//echo '<li><a href="#code_help">To code help</a></li><br>';
	//echo '<li><a href="#code_select">To select code list</a></li><br>';
	echo '</ul>';
	
	//*
	echo '<a id=code_list></a>';
	include('code_list.inc');
	echo '<a href="#top">To top</a><br>';
	//*/
	
	//*
	echo '<a id=code_help></a>';
	include('code_fo_help.inc');
	echo '<a href="#top">To top</a><br>';
	//*/
	
	echo '<a id=code_select></a>';
	include('code_privatecode.inc');
	//echo '<a href="#top">To top</a><br>';

?>

</body>
</html>
