<html>

<head>
<title>Modify Contract Staff List</title>
</head>
<body background="rlaemb.JPG" leftmargin="40">

<?php
include('str_decode_parse.inc');
include("rla_functions.inc");
echo "<a id=top><h1 align=center>Remove Following Staff<br>From Timesheet Summary Report to GHR</h1><hr>";

	include("connet_root_once.inc");
	$sql = "SELECT email_name as en, first_name as fn, last_name as ln, date_unemployed as noemp
        FROM timesheet.employee 
        order by fn";
    $result = mysql_query($sql);
    include("err_msg.inc");
    $i=0;
    while (list($en, $fn, $ln, $noemp) = mysql_fetch_array($result)) {
    	if ($en == "webmaster") {
    	} elseif ($en == "sharon") {
    	} else {
    		$enlist[$i] = $en;
    		$fnlist[$i] = "$fn $ln";
    		if ($noemp == "0000-00-00") {
    			$empstatus[$i] = "&nbsp";
    		} else {
    			$empstatus[$i] = "No";
    		}
    		$i++;
    	}
    }
    $slctr = $i;
    

########
if ($exstafftoghr) {
	$i = 0;
	while (list($key, $val) = each($HTTP_POST_VARS)) {
		$ex[$i] = $key;
		$i++;
		//echo "$i $key, $val<br>";
	}
	$nokey = $i;
	$excludelist = $ex[1];
	for ($j=2; $j<$nokey-1; $j++) {
		$excludelist .= "@".$ex[$j];
	}
	$nameex = "";
	for ($j=1; $j<$nokey-1; $j++) {
		for ($k=0; $k<$slctr ; $k++) {
			if ($ex[$j] == $enlist[$k]) {
				$nameex .= $fnlist[$k]."<br>";
			}
		}
	}
	
	#####check whether a list is existing
	$sql = "SELECT description 
        FROM logging.sysmastertable 
        WHERE item='rpt_ghr_ex_list';";
    $result = mysql_query($sql);
    include("err_msg.inc");
    list($description) = mysql_fetch_array($result);
	if (!$description) {
		$sql = "INSERT INTO logging.sysmastertable 
        SET id='null', item='rpt_ghr_ex_list', 
            description='$excludelist';";
	} else {
    	$sql = "UPDATE logging.sysmastertable 
        SET description='$excludelist'
        WHERE item='rpt_ghr_ex_list';";
	}
	
	$result = mysql_query($sql);
	include("err_msg.inc");
	echo "<h4>Following Satff are exculuded from timesheet summary report sent to GHR</h4>$nameex<hr>";;
}

##############build staff list
	$sql = "SELECT description 
        FROM logging.sysmastertable 
        WHERE item='rpt_ghr_ex_list';";
    $result = mysql_query($sql);
    include("err_msg.inc");
    list($description) = mysql_fetch_array($result);
	$exstaff = explode("@", $description);
	//for ($i=0; $i<count($exstaff); $i++) {
		//echo $exstaff[$i]."<br>";
	//}

	echo '<form method=POST action='.$PHP_SELF.'>';
	include("userstr.inc"); //$userstr
	echo "<table border=1><tr><th>Name</th><th>Exclude</th><th>Currently employed?</tr>";
	for ($i=0; $i<$slctr; $i++) {
		$chk = "";
		for ($j=0; $j<count($exstaff); $j++) {
			if ($enlist[$i] == $exstaff[$j]) {
				$chk = " checked";
			}
		}
		echo "<tr><td>".$fnlist[$i]."</td><td><input type=\"checkbox\" $chk name=\"".$enlist[$i].
		"\"></td><td>".$empstatus[$i]."</tr>";
	}
	echo '<tr><th colspan=3><input type="submit" value="Submit" name="exstafftoghr"></th></tr>';
	echo "</table>";
	echo "</form>";
	

echo "<hr><br></a><a href=#top>Back to top</a><br><br>";
?>
</body>
