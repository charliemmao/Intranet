<html>

<head>
<title>Process Private Code Selection</title>
</head>
<body background="rlaemb.JPG" leftmargin="20">
<!--
<body onload="ClearFlyOver();" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG">
<div id=FOArea class="help" style="dispaly: none"></div>
-->
<?php
include('str_decode_parse.inc');
//include('user_info.inc');
$j	 =	count($HTTP_POST_VARS);
//echo '<h1>'.$title.' '.$last_name.'</h1>';
echo '<h1>Hi, '.$first_name.'</h1>';
if ($j == 2) {
	echo '<h2><font color=#ff0000>You have not selected any project code, please select code.</font></h2><br>';
	exit;
} else {
	$j	=	$j - 2;
}

$codesel	=	'';
$codefeedback	=	"";
$i	=	1;
while (list($key, $val) = each($HTTP_POST_VARS)) {
   if ($i<=$j) {
   		$codesel = $codesel.$patsym.$val;
   		$codefeedback	=	"$codefeedback<tr><td>$i</td><td>$val</td></tr>";
   	}
   	$i++;
}
$codesel = substr($codesel,1,strlen($codesel)-1);
$codesel	=	" codelist='".$codesel."', time='".date("Y-m-d")."'";
include('codearray_private.inc');
if ($no_select == 0) {
	$codesel	=	" SET email_name='".$email_name."',".$codesel;
	$sql	=	"INSERT INTO timesheet.privatecode $codesel;";
} else {
	$codesel	=	" SET ".$codesel;
	$sql	=	"UPDATE timesheet.privatecode $codesel where email_name='".$email_name."';";
}
//echo $sql.'<br>';

include('connet_other_once.inc');
$result	=	mysql_query($sql);
include('err_msg.inc');
if ($result) {
	if ($no_select == 0) {
		echo '<h2>You have successfully selected project codes.</h2>';
	} else {
		echo '<h2>You have successfully updated your private project codes.</h2>';
	}
	echo '<b>Now you have following codes:</b>';
	echo "<table border=0><tr><th>No</th><th>Code</th></tr>".$codefeedback.'</table><br>';
	$action	=	"select code";
	include('logging.inc');
} else {
	echo '<h2><font colot=#ff0000>Due to server problem,<br>';
	if ($no_select == 0) {
		echo 'your project codes have not been registered.</h2>';
	} else {
		echo 'your project codes have not been updated.';
	}
	echo '</font>';
}
?>
</html>