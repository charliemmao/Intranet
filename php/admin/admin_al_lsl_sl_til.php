<html>

<head>
<meta http-equiv="Content-Language" content="en-au">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>Annual and Long Service Leave Record</title>
<base target="main">
</head>

<body background="rlaemb.JPG" topmargin="4" leftmargin="20">

<?php
include('str_decode_parse.inc');
include("rla_functions.inc");
include("userinfo.inc"); //$userinfo
$userstr	=	"?".base64_encode($userinfo);
echo "<a id=top name=top></a><p align=center><font size=5><b>Annual, Long Service and Sick Leave Records</b></font>";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2><b>[Refresh]</b></font></a>";
echo "</p><hr>";
include("connet_other_once.inc");

#############################################
#########	form one
#############################################
echo "<form method=post action=$thisfile>";
include("userstr.inc");
echo "<table>";
$sql = "SELECT email_name as ename, first_name as fname, last_name as lname ".
	"FROM timesheet.leave_entitle ORDER BY lname;";
$result = mysql_query($sql);
include("err_msg.inc");
$no = mysql_num_rows($result);
echo "<tr><th align=left>Staff List ($no)</td>";
echo "<td><select name=ename0>";
$i = 0;
if ($ename2) {
	$email1 = $ename2;
} elseif ($ename0) {
	$email1 = $ename0;
}
while (list($ename, $fname, $lname) = mysql_fetch_array($result)) {
	$stafflist[$i] = $ename;
	$stafflistf[$i] = "$lname, $fname";
	$i++;
	if ($email1 == $ename) {
		echo "<option selected value=\"$ename\">$lname, $fname";
	} else {
		echo "<option value=\"$ename\">$lname, $fname";
	}
}
if ($ename0 == "all") {
	echo "<option selected value=\"all\">All Staff";
} else {
	echo "<option value=\"all\">All Staff";
}

echo "</option></selected></td>";
echo "<td rowspan=2><button type=\"submit\" name=\"go\"><font size=4><b>SUBMIT</b></font></td>";
echo "</tr>";
echo "<tr><th align=left>Select an Action</th>";
$i = 0;
$actlist[$i] = "View Records"; $i++;
$actlist[$i] = "Edit Data"; $i++;
if (!$actiontype) {
	$actiontype = $actlist[0];
}
echo "<td><select name=\"actiontype\">";
for ($j=0; $j<$i; $j++) {
	if ($actiontype == $actlist[$j]) {
		echo "<option selected>".$actlist[$j];
	} else {
		echo "<option>".$actlist[$j];
	}
}
echo "</option></select></td></tr>";
echo "</table></form>";

if ($go) {
	echo "<hr>";
	if ($ename0 == "all" && $actiontype == "Edit Data") {
		echo "<b><font color=#ff0000>You can only edit data for one person each time.</b><br>";
		echo "<hr><br><a href=#top>Back to top</a><br><br>";
		exit;
	}
	if ($actiontype == "Edit Data") {
		$sql = "SELECT first_name as fname, last_name as lname, lsl, al, sl, til, onthisday ".
			"FROM timesheet.leave_entitle WHERE email_name='$ename0';";
		$result = mysql_query($sql);
		include("err_msg.inc");
		list($fname, $lname, $lsl, $al, $sl, $til, $onthisday) = mysql_fetch_array($result);
		//$onthisday = "2000-11-04";
		echo "<b>Edit Leave Record for <font color=#0000ff>$fname $lname</font>.<b><br>";
		
		echo "<form name=\"leaveform\" mehtod=post action=$thisfile>";
		include("userstr.inc");
		echo "<input type=hidden name=ename2 value=\"$ename0\">";
		echo "<input type=hidden name=fname2 value=\"$fname\">";
		echo "<input type=hidden name=lname2 value=\"$lname\">";
		
		/* email_name, first_name, middle_name, last_name, lsl, al, sl, onthisday, */
		echo "<table border=1>";
		echo "<tr><th align=left>First Name</th><td>$fname</td></tr>";
		echo "<tr><th align=left>Last Name</th><td>$lname</td></tr>";
		$lsl= number_format($lsl, 2);
		$sl= number_format($sl, 2);
		$al= number_format($al, 2);
		$til= number_format($til, 2);
		echo "<tr><th align=left>Annual Leave (hours)<td><input type=text name=al value=\"$al\" size=10></td></tr>";
		echo "<tr><th align=left>Long Service Leave (hours)<td><input type=text name=lsl value=\"$lsl\" size=10></td></tr>";
		echo "<tr><th align=left>Sick Leave (hours)<td><input type=text name=sl value=\"$sl\" size=10></td></tr>";
		//echo "<tr><th align=left>Time in Lieu (hours)<td><input type=text name=til value=\"$til\" size=10></td></tr>";
		echo "<tr><th align=left>Before (yyyy-mm-dd)<td><input type=text name=onthisday value=\"$onthisday\" size=10></td></tr>";
		echo "<tr><td colspan=2 align=middle><button type=submit name=editleave>".
			"<font size=4 color=#0000ff>UPDATE</font></button></td></tr>";
		echo "</table>";
		echo "</form>";
	} elseif ($actiontype == "View Records") {
		if ($ename0 != "all") {
			include("ts_listone_lvercd.inc");
		} else {
			$nono = $no;
			
			if ($priv == "00") {
				echo "<h2>Main Index [<a href=#summary>View Summary</a>]</h2>";
				echo "<table>";
				$i = 4;
				$k = 1;
				$j = 0;
				while ($j<$no) {
					if ($k == 1) {
						echo "<tr>";
					}
					echo "<td>[<a href=#$stafflist[$j]lvind>$b1$stafflistf[$j]$b2</a>]</td>";
					$k++;
					$j++;
					if ($k == 5) {
						echo "</tr>";
						$k = 1;
					}
				}
				echo "</table><hr>";
				
				for ($ii=0; $ii<$nono; $ii++) {
					$ename0 = $stafflist[$ii];
					include("ts_listone_lvercd.inc");
					echo "<hr>";
				}
				
				echo "<br><h2><a id=summary name=summary>Staff Leave Summary on $today (days)</a></h2>";
				echo "<table border=1>";
				echo "<tr><th rowspan=2 valign=center>Name</th><th colspan=2>Annual</th><th colspan=2>Sick</th>
					<th colspan=2>Long Service</th>";
					//echo "<th colspan=2>Time in Lieu</th>";
					echo "</tr>";
				echo "<tr><th>Taken</th><th>Entitlement</th><th>Taken</th><th>Entitlement</th>
					<th>Taken</th><th>Entitlement</th></tr>";//<th>Taken</th><th>Entitlement</th>
				for ($ii=0; $ii<$nono; $ii++) {
					$ename0 = $stafflist[$ii];
					$ind[1] = $ename0."alve";
					$ind[2] = $ename0."slve";
					$ind[3] = $ename0."lslve";
					$ind[4] = $ename0."tiliu";

					echo "<tr><td><b><a href=#$ename0"."lvind>$stafflistf[$ii]</a></b></td>";
					for ($j=1; $j<=3; $j++) {
						if ($lvercd0[$ii][$j] != "---") {
							//$newtil = "<a href=#$ind[$j]>".$lvercd0[$ii][$j]."/".number_format($lvercd0[$ii][$j]/7.6, 2)."</a>";
						} else {
							$newtil = "---";
						}
						if ($lvercd[$ii][$j] == "-0.0" || $lvercd[$ii][$j] == "0.0") {
							$tmp = "---";
						} else {
							$tmp = $lvercd[$ii][$j];
						}
						echo "<th>$newtil</th>"."<th>".$tmp."</th>";
					}
					echo "</tr>";
				}
				echo "</table><br>";
			} else {
				echo "<b>Under Development</b><br>";
			}
		}		
	}
}

if ($editleave) {
	$msg = "";
	$length = strlen($onthisday);
	if ($length != 10) {
		$msg = "<hr><b>Please enter date in correct format, such as ".date("Y-m-d");
	} else {
		$year = substr($onthisday, 0, 4);
		if ($year < date("Y")) {
			$msg = "<hr><b>Please enter a correct year, such as ".date("Y");
		}
	}
	if ($msg) {
		$msg = $msg.". Click <font color=#0000ff>SUBMIT</font> again.</b>";
		echo $msg;
		echo "<hr><br><a href=#top>Back to top</a><br><br>";
		exit;
	}
	$sql = "UPDATE timesheet.leave_entitle SET  
		lsl='$lsl', al='$al', sl='$sl', til='$til', onthisday='$onthisday' WHERE email_name='$ename2';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	
	$sql = "SELECT first_name as fname, last_name as lname, lsl, al, sl, til, onthisday ".
			"FROM timesheet.leave_entitle WHERE email_name='$ename2';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($fname, $lname, $lsl, $al, $sl, $til, $onthisday) = mysql_fetch_array($result);
	$lsl= number_format($lsl, 2);
	$sl= number_format($sl, 2);
	$al= number_format($al, 2);
	$til= number_format($til, 2);

	echo "<hr><H2>Leave Record for <font color=#0000ff>$fname $lname</font> has been updated.</H2><br>";
	echo "<table>";
	echo "<tr><th align=left>Annual Leave (hours)<td>$al</td></tr>";
	echo "<tr><th align=left>Long Service Leave (hours)<td>$lsl</td></tr>";
	echo "<tr><th align=left>Sick Leave (hours)<td>$sl</td></tr>";
	//echo "<tr><th align=left>Time in Lieu (hours)<td>$til</td></tr>";
	echo "<tr><th align=left>Before (yyyy-mm-dd)<td>$onthisday</td></tr>";
	echo "</table>";
}

function leavelist($sql, $str, $sname) {
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	echo "<br><b>---$str ($sname) ---<b><br><br>";
	echo "<table border=1>";
	echo "<tr><th>Date</th><th>Minutes</th></tr>";
	$summ = 0;
	while (list($startday, $minutes) = mysql_fetch_array($result)) {
		echo "<tr><td>$startday</td><td align=middle>$minutes</td></tr>";
		$summ = $summ + $minutes;
	}
	$sumh = number_format($summ/60, 2);
	$sumd = number_format($sumh/7.6, 1);
	echo "<tr><th colspan=2>$summ minutes or <br>$sumh hours or <br>$sumd days</td></tr>";
	echo "</table>";
}

?>
<hr><br><a href=#top>Back to top</a><br><br>

</body>
</html>