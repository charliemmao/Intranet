<html>

<head>
<title>New Tables Initialisation</title>
</head>

<body bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG" leftmargin="50">

<?php
## control page access
include("admin_access.inc");
include('rla_functions.inc');

echo "<a id=top><h1 align=center>New Tables Initialisation</h1></a>";

echo "<p align=center><a href=\"".$PHP_SELF."$admininfo\">[Refresh]</a>";
echo "<a href=\"/$phpdir/adminctl_top.php$admininfo\">[Admin Main Page]</a>";
echo "<hr>";

include("connet_root_once.inc");
$j = 0;
$newtablelist[$j] = "timesheet.leave_entitle"; $j++;
$newtablelist[$j] = "logging.sysmastertable"; $j++;
$newtablelist[$j] = "timesheet.tsmailconf"; $j++;
$numtable  = $j;
if ($newtable == "") {
	$newtable = $newtablelist[0];
}

##	new tables initialisation
echo "<form name=newtables action=\"$PHP_SELF\">";
	echo "<table border=1>";
	echo "<tr><th>New Tables List</th>";
	echo "<td><select name=newtable>";
	
	for ($j=0; $j<$numtable; $j++) {
    	if ($newtable == $newtablelist[$j]) {
  			echo "<option selected>$newtablelist[$j]";
  		} else {
  			echo "<option>$newtablelist[$j]";
  		}
  	}
	echo "</option></select></td>";
	
	echo "<td rowspan=2 align=middle>";
	if (getenv("server_addr") == "127.0.0.1") {
		echo "<input type=\"submit\" name=initialization value=GO></td></tr>";
	} else {
		echo "<button type=\"submit\" name=initialization>
		<font color=#0000ff size=3><b>&nbsp;&nbsp;GO&nbsp;&nbsp;</b></font></button>";
	}
		
	$sql = "SELECT email_name as ename, first_name as fname, last_name as lname FROM timesheet.employee 
		WHERE date_unemployed='0000-00-00' order by ename;";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$nouser = 0;
	while (list($ename, $fname, $lname) = mysql_fetch_array($result)) {
		$userlist[$nouser][0] = $ename;
		$userlist[$nouser][1] = "$fname, $lname";
		$nouser++;
	}
	
	echo "<tr><th align=left>Current User ($nouser)</th>";
	echo '<td><table>';
	if ($nouser>0) {
		$noinrow = 10;
		for ($i=0; $i<$nouser; $i++) {
			$k = (int)($i/$noinrow);
			$j = $k*$noinrow - $i;
			if ($j == 0) {
				echo "<tr>";
				$close = 0;
			}
			echo "<td><input type=checkbox name=curuser[] value=\"".$userlist[$i][0]."\" checked>";
			$statuscontext = $userlist[$i][1];
			$status = " onMouseOver=\"self.status='$statuscontext'; return true\" "
				."onMouseOut=\"self.status='Privilege Setup'; return true\" ";
			echo "<a $status>".$userlist[$i][0]."<a/></td>";
			if ($j == $noinrow - 1) {
				echo "</tr>";
				$close = 1;
			}
		}
		if ($close == 0) {
			$close = "</tr>";
		} else {
			$close = "";
		}
		echo "$close</table>";
	}
	echo '</td></tr>';
	echo "</table>";
echo "</form>";

##	process
if ($initialization) {
	echo "<hr>";
	
	$b1 = "<font color=#0000ff>";
	$b2 = "</font>";
	if ($newtable == "timesheet.tsmailconf") {
		$numuser = count($curuser);
		$sql = "DELETE FROM $newtable;";
		$result = mysql_query($sql);
		include("err_msg.inc");
		for ($i=0; $i<$numuser; $i++) {
			$ename = $curuser[$i];
			$confirmation = "N";
			if ($ename == "") {
				$confirmation = "Y";
			}
			$sql = "INSERT INTO timesheet.tsmailconf VALUES('$ename', '$confirmation');";
			$result = mysql_query($sql);
			include("err_msg.inc");
		}
		$sql = "SELECT email_name, confirmation FROM timesheet.tsmailconf;";
		$result = mysql_query($sql);
		include("err_msg.inc");
		$no = mysql_num_rows($result);
		echo "<h2>New table <font color=#0000ff>$newtable</font> data list ".
			"<font color=#0000ff>($no)</font>.</h2>";
		echo "<table border=1>";
		echo "<tr><th>name</th><th>Require Email Confirmation?</th></tr>";
		while (list($email_name, $confirmation) = mysql_fetch_array($result)) {
			echo "<tr><td>$email_name</td><td>$confirmation</td></tr>";
		}
		echo "</table><p>";
	}
	
	if ($newtable == "logging.sysmastertable") {
		echo "<h2>Add new entry to table <font color=#0000ff>$newtable</font>.</h2>";
		$phpfile = basename($PHP_SELF);
		sysmastertable($newtable,$phpfile);
	}
	
	if ($newtable == "timesheet.leave_entitle") {
		$sql = "DELETE FROM $newtable;";
		$result = mysql_query($sql);
		include("err_msg.inc");
		$sql = "SELECT email_name, first_name, middle_name, last_name FROM timesheet.employee 
			WHERE date_unemployed='0000-00-00' order by first_name;";			
		$result = mysql_query($sql);
		include("err_msg.inc");
		while (list($email_name, $first_name, $middle_name, $last_name) = mysql_fetch_array($result) ) {
			$sql0 = "INSERT INTO timesheet.leave_entitle SET email_name='$email_name', 
				first_name='$first_name', middle_name='$middle_name', last_name='$last_name', onthisday='2000-07-01';";
			$result0 = mysql_query($sql0);
			include("err_msg.inc");
		}
		
		$sql = "SELECT email_name, first_name, middle_name, last_name, lsl, al, sl, til, onthisday ".
			"FROM timesheet.leave_entitle;";
		$result = mysql_query($sql);
		include("err_msg.inc");
		$no = mysql_num_rows($result);
		echo "<h2>New table <font color=#0000ff>$newtable</font> data list ($no).</h2>";
		echo "<table border=1>";
		$b1 = "<font size=2><b>";
		$b2 = "</b></font>";
		echo "<tr><th>F. Name</th><th>M. Name</th><th>L. Name</th>
			<th>LSL (hours)</th><th>AL (hours)</th><th>SL (hours)</th><th>TIL (hours)</th><th>Before</th></tr>";
		while (list($email_name, $first_name, $middle_name, $last_name, $lsl, $al, $sl, $til, $onthisday) 
			= mysql_fetch_array($result)) {
			if (!$middle_name) {
				$middle_name = "&nbsp;";
			}
			$lsl = number_format($lsl, 2);
			$al = number_format($al, 2);
			$sl = number_format($sl, 2);
			$til = number_format($til, 2);
			echo "<tr><td>$b1$first_name$b2</td><td>$b1$middle_name$b2</td><td>$b1$last_name$b2</td>
			<td align=middle>$b1$lsl$b2</td><td align=middle>$b1$al$b2</td>
			<td align=middle>$b1$sl$b2</td><td align=middle>$b1$til$b2</td>
			<td align=middle>$b1$onthisday$b2</td></tr>";
		}
		echo "</table>";
	}
}

if ($onenewrecord) {
	if ($newtable == "logging.sysmastertable") {
		echo "<h2>Add new entry to table <font color=#0000ff>$newtable</font>.</h2>";
		if ($item && $description) {
			$sql = "INSERT INTO logging.sysmastertable VALUES('$id', '$item', '$description');";
			$result = mysql_query($sql);
			include("err_msg.inc");
		}
		$phpfile = basename($PHP_SELF);
		sysmastertable($newtable,$phpfile);
		echo "<hr>";
		$sql = "SELECT id, item, description FROM logging.sysmastertable ORDER BY id DESC;";
		$result = mysql_query($sql);
		include("err_msg.inc");
		echo "<table border=1>";
		echo "<tr><th>ID</th><th>Item</th><th>Description</th></tr>";
		while (list($id, $item, $description) = mysql_fetch_array($result)) {
			echo "<tr><td>$id</td><td>$item</td><td>$description</td></tr>";
		}
		echo "</table><p>";
	}
}
function sysmastertable($newtable,$phpfile){
	echo "<form method=post action=$phpfile>";
		echo "<input type=hidden name=id value=null>";
		echo "<input type=hidden name=newtable value=$newtable>";
		$sql = "SELECT id, item, description FROM logging.sysmastertable ORDER BY id DESC;";
		$result = mysql_query($sql);
		include("err_msg.inc");
		$no = mysql_num_rows($result);
		if ($no) {
			echo "<table>";
   			echo "<tr><th align=left>Current List ($no)</th>";
			echo "<td><select name=list>";
			while (list($id, $item, $description) = mysql_fetch_array($result)) {
				echo "<option>($id) ($item) ($description)";
			}
			echo "</option></select></td></tr>";
		}
		echo "<table>";
		echo "<tr><th align=left>Item</th><td><input type=text name=item></td></tr>";
		echo "<tr><th align=left>Description</th><td><input type=text name=description></td></tr>";
		echo "<tr><td colspan=2 align=middle>
			<input type=submit name=onenewrecord value=\"Add New Record\">";
		echo "</table>";
	echo "</form>";
}
backtotop();
function backtotop(){
echo "<br><hr><a href=#top>Back to top</a><a id=end></a><br><br><br>";
}

?>
</body>
