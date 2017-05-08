<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>Collect user IP address</title>
</head>

<body background="rlaemb.JPG" leftmargin="20" topmargin="30">

<?php
include("regexp.inc");
include("phpdir.inc"); 
include('str_decode_parse.inc');
include("userinfo.inc"); //$userinfo
#echo "$userinfo<br>";

$frm_str	=	base64_encode($userinfo);
$time = date("YmdHis");

################################################
#	form for mail log check 
################################################
if ($priv == "00") {
	echo "<hr><H2>Check mail record (maximum <font color=#ff0000>10</font> minutes delay)</h2>";
} else {
	echo "<hr><H2>Check <font color=#0000ff>$first_name</font>'s mail record (maximum <font color=#ff0000>10</font> minutes delay)</h2>";
}
	echo "<a id=top></a>";
  	$curdate = date("Y-m-d");
  	$curtime = date("H:i:s");
	echo "<form method=\"POST\">";
  	echo "<table>";
  	echo "<input type=hidden name=frm_str value=\"$frm_str\">";
  	echo "<tr><th align=left>Current Date</th><td>$curdate</td></tr>";
  	echo "<tr><th align=left>Current Time</th><td>$curtime</td></tr>";

  	echo "<tr><th align=left>Check for Last</th><th>
  		<select name=novar>";
  		if (!$novar) {
  			$novar = 1;
  		}
  		for ($i=1; $i<=24; $i++) {
  			if ($i == $novar) {
  				echo "<option selected>$i";
  			} else {
  				echo "<option>$i";
  			}
  		}
  		echo "</option></select>";
  	echo "<select name=dayhour>";
  		$dh[0]="day(s)";
  		$dh[1]="hour(s)";
  		if (!$dayhour) {
  			$dayhour = 0;
  		}
  		for ($i=0; $i<=1; $i++) {
  			if ($i == $dayhour) {
  				echo "<option value=$i selected>".$dh[$i];
  			} else {
  				echo "<option value=$i>".$dh[$i];
  			}
  		}
  		echo "</option></select>";
  		
  	echo "<select name=mailtype>";
  		$mtype[0]="from";
  		$mtype[1]="to";
  		$mtype[2]="from/to";
  		if ($mailtype == "") {
  			$mailtype= 1;
  		}
  		for ($i=0; $i<=2; $i++) {
  			if ($i == $mailtype) {
  				echo "<option value=$i selected>".$mtype[$i];
  			} else {
  				echo "<option value=$i>".$mtype[$i];
  			}
  		}
		if ($priv != "00") {
  			echo "</option></select>$first_name";
  		}
  	echo "</th></tr>";
  	
	if ($priv == "00") {
  		echo "<tr><th align=left>Check for</th><td><select name=staffname>";
  		if (!$staffname) {
  			$staffname = $email_name;
  		}
  		include("connet_root_once.inc");
 		$sql = "SELECT email_name as ename, first_name as fname, last_name as lname
        	FROM timesheet.employee
        	Order By first_name";
    	$result = mysql_query($sql);
    	include("err_msg.inc");
		while (list($ename, $fname, $lname) = mysql_fetch_array($result)) {
            if ($staffname == $ename) {
            	echo "<option value=$ename selected>$fname $lname";
            } else {
              echo "<option value=$ename>$fname $lname";
            }
       }
       echo "</option></select></td></tr>";
  		//echo "<input type=hidden name=staffname value=\"$email_name\">";
  	} else {
  		echo "<input type=hidden name=staffname value=\"$email_name\">";
  	}
  	echo "<tr><th colspan=2  align=center>
  	<button type=submit name=chkmaillog><b>Submit</b></button></th></tr>";
	echo "</table>";	
	
################################################
#	process mail log file 
################################################
if ($chkmaillog) {
	echo "<hr><H2>Mail log ".$mtype[$mailtype]." <font color=#0000ff>$first_name</font> in last $novar ".
		$dh[$dayhour]."</h2>";
	include("maillogana.inc");
}
?>
<hr><a href=#top><b>Back to top</b></a>
</body>
