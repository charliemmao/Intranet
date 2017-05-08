<html>

<head>
<meta http-equiv="Content-Language" content="en-au">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>user top page</title>
<base target="contents">
</head>

<body background="rlaemb.JPG" leftmargin="20">

<?php

include('getusersec.inc');
//include('str_decode_parse.inc');
include("islogon.inc");

	//search user priviledge
	$sql = "select priviledge from logging.accesslevel where email_name='".$email_name."';";
		//echo $email_name." 2<br>";

	include('general_one_val_search.inc');
	$priv= $out;
	$curip = getenv('REMOTE_ADDR');
	if ($curip == "192.168.223.174" || $curip == "192.168.223.122") {
         //echo "ip=$curip; email_name = $email_name<br>";   
   }
	include("userinfo.inc"); //$userinfo
	//$userinfo="";
	if ($email_name) {			
		$qry_ts	= $userinfo.'&dbname=timesheet';		
		$qry_ts	=	'?'.base64_encode($qry_ts);

		$qry_fin	= $userinfo.'&dbname=rlafinance';		
		$qry_fin	=	'?'.base64_encode($qry_fin);

		$qry_lib	= $userinfo.'&dbname=library';		
		$qry_lib	=	'?'.base64_encode($qry_lib);

		$qry_inv	= $userinfo.'&dbname=inventory';		
		$qry_inv	=	'?'.base64_encode($qry_inv);
	}
	
	if ($title == "" ) {
		$add = 'Hi, '.' '.$first_name;
	} else {
		//$add = 'Hi '.$title.' '. $last_name;
		$add = 'Hi '.' '.$first_name;
	}
?>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td width="25%" rowspan="2">
      <p style="margin-top: 6; margin-bottom: 0">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <img border="0" src="rla_tr_small.gif" width="100" height="73"></td>
    <td width="75%" colspan="4">
      <p align="center" style="margin-top: 6"><b><font size="5">
      <?php 
		include("find_admin_ip.inc");
	$sqldes = "SELECT description as des
        FROM logging.sysmastertable 
        WHERE item='contractorlist'";
    $resultdes = mysql_query($sqldes);
    include("err_msg.inc");
    list($des) = mysql_fetch_array($resultdes);
    $rlastaff = 1;
    
    $ip = getenv('REMOTE_ADDR');
    if ($ip == "192.168.223.174" or $ip == "192.168.223.122") {
       //echo "$userinfo";
	  	//echo "email_name<br>$email_name<br>des<br>$des test<br>";
    }
    if ($des <> ereg_replace($email_name, "", $des)) {
    	$rlastaff = "";
    	$priv = "100";
    }
      		if ($priv != "00") {
      			if ($rlastaff == 1) {
      				echo $add .',&nbsp;please select DB.';
      			} else {
      				echo $add .',&nbsp;Welcome you to use RLA timesheet database.';
      			}
      		} elseif ($priv == "00") {
      			echo 'Change Database';
      		} else {
      			echo '<p align="center"><h2>You have successfully logout from Intranet.</h2>';
      			exit;
      		}
      ?>
      </font></b></td>
  </tr>
  <tr>
<?php
	
	if ($rlastaff) {
		include("find_admin_ip.inc");
		$statuscontext = "Send new or modify timesheet.";
		include("self_status.inc"); //'.$status.'
		echo '<td width="20%">';
		echo '<p align="center"><b><font size="4">[<a href="user_main_left.php?'.$qry_ts.'" '.$status;
		echo ' target="left">Time Sheet</a>]</font></b></td>';
		
		$statuscontext = "Ordering and Finance.";
		include("self_status.inc"); //'.$status.'
		echo '<td width="20%">';
		echo '<p align="center"><b><font size="4">[<a href="user_main_left.php?'.$qry_fin.'" '.$status;
		echo ' target="left">Order</a>]</font></b></td>';
	
		$statuscontext = "Library search or add new or modify library entries.";
		include("self_status.inc"); //'.$status.'
		echo '<td width="20%">';
		echo '<p align="center"><b><font size="4">[<a href="user_main_left.php?'.$qry_lib.'" '.$status;
		echo ' target="left">Library</a>]</font></b></td>';
	
		$statuscontext = "Add new or modify inventory entries.";
		include("self_status.inc"); //'.$status.'
		echo '<td width="20%">';
		echo '<p align="center"><b><font size="4">[<a href="user_main_left.php?'.$qry_inv.'" '.$status;
		echo ' target="left">ASSETS</a>]</font></b></td>';
	}
?>
  </tr>
</table>
</body>

</html>
