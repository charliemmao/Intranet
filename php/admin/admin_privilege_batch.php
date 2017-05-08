<html>

<head>
<title></title>
</head>

<body bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG" leftmargin="50">

<?php
## control page access
include("admin_access.inc");
include('rla_functions.inc');

echo "<a id=top><h1 align=center>Setup Privilege By Batch</h1></a>";

echo "<p align=center><a href=\"".$PHP_SELF."$admininfo\">[Refresh]</a>";
echo "<a href=\"/$phpdir/admin_privilege_one.php$admininfo\">[Individual Setup]</a>";
echo "<a href=\"/$phpdir/adminctl_top.php$admininfo\">[Admin Main Page]</a>";
echo "<hr>";

include("connet_root_once.inc");
	$sql = "SELECT email_name, first_name, last_name FROM timesheet.employee 
		WHERE email_name!='webmaster' and email_name!='heh';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$nouser = 0;
	while (list($email_name, $first_name, $last_name) = mysql_fetch_array($result)) {
		$userlist[$nouser][0] = $email_name;
		$userlist[$nouser][1] = "$first_name, $last_name";
		$nouser++;
	}
	
##	process
$b1 = "\"<font color=#0000ff>";
$b2 = "</font>\"";

if ($tblaction == "Add New DB to Everyone") {
	echo "<H2>The following person have access to DB $dblist</h2>";
	for ($i=0; $i<$nouser; $i++) {
		$adduser = $userlist[$i][0];
		$sql ="INSERT INTO mysql.db VALUES('localhost','$dblist','$adduser','Y','Y','Y','Y','N','N','N','N','N','N');";
		$result = mysql_query($sql);
		include("err_msg.inc");
		echo "$adduser<br>";
	}
	echo "<hr>";
}

if ($tblaction == "Add New User" && $addmod) {
	$newuser = trim($newuser);
	$tmp = explode("@", $newuser);
	$no = count($tmp);
	if ($newuser == "") {
		$no =0;
	}
	if ($no == 0) {
		$msg = "
<h2><font color=#ff0000>Please type new user in the textbox.</font></h2>";
	} elseif ($no == 1) {
		$msg = "<h2>The new user \"<font color=#0000ff>$tmp[0]</font>\" has been added.</h2>";
	} else {
		$msg = "<h2>These new users have been added.</h2>";
	}
	
	for ($i=0; $i<$no; $i++) {
		$adduser = $tmp[$i];
		$userpwd = $adduser."reslab";
		$sql = "INSERT INTO mysql.user VALUES('localhost','$adduser',PASSWORD('$userpwd'),'N',
			'N','Y','N','N','N','N','N','N','N','N','N','N','N');";
		$result = mysql_query($sql);
		include("err_msg.inc");
		$sql ="INSERT INTO mysql.db VALUES('localhost','timesheet','$adduser','Y','Y','Y','Y','N','N','N','N','N','N');";
		$result = mysql_query($sql);
		include("err_msg.inc");
		$sql ="INSERT INTO mysql.db VALUES('localhost','library','$adduser','Y','Y','Y','Y','N','N','N','N','N','N');";
		$result = mysql_query($sql);
		include("err_msg.inc");
		$sql ="INSERT INTO mysql.db VALUES('localhost','inventory','$adduser','Y','Y','Y','Y','N','N','N','N','N','N');";
		$result = mysql_query($sql);
		include("err_msg.inc");
		$sql ="INSERT INTO mysql.db VALUES('localhost','rlafinance','$adduser','Y','Y','Y','Y','N','N','N','N','N','N');";
		$result = mysql_query($sql);
		include("err_msg.inc");
		if ($no>1) {
			$msg = $msg."&nbsp;&nbsp;&nbsp;<b>$adduser</b><br>";
		}
	}
}

if ($tblaction == "Delete User" && $addmod) {
	//$sql = "DELETE FROM mysql.db WHERE Db='rladb';";
	//$result = mysql_query($sql);
	$no = count($curuser);
	if ($no == 0) {
		$msg = "<h2><font color=#ff0000>Please select user from the checkboxes.</font></h2>";
	} elseif ($no == 1) {
		$msg = "<h2>The user \"<font color=#0000ff>$curuser[0]</font>\" has been deleted.</h2>";
	} else {
		$msg = "<h2>These users have been deleted.</h2>";
	}
	for ($i=0; $i<$no; $i++) {
		$userdel = $curuser[$i];
		$sql = "DELETE FROM mysql.user WHERE User='$userdel';";
		$result = mysql_query($sql);
		include("err_msg.inc");
		$sql = "DELETE FROM mysql.db WHERE User='$userdel';";
		$result = mysql_query($sql);
		include("err_msg.inc");
		if ($no>1) {
			$msg = $msg."&nbsp;&nbsp;&nbsp;<b>$userdel</b><br>";
		}
	}
}


##	list tables in MySQL and action selection
echo "<form name=seltable action=\"$PHP_SELF\">";
	echo "<table border=1>";
	echo "<tr><th>Tables in MySQL DB</th>";
	echo "<td><select name=mysqltable>";
	$notable = 0;
	$mysqltablelist[$notable]= "user"; $notable++;
	$mysqltablelist[$notable]= "db"; $notable++;
	//$mysqltablelist[$notable]= "tables_priv"; $notable++;
	//$mysqltablelist[$notable]= "columns_priv"; $notable++;
	//$mysqltablelist[$notable]= "host"; $notable++;
	//$mysqltablelist[$notable]= "func"; $notable++;

	if ($mysqltable == "") {
		$mysqltable = $mysqltablelist[0];
	}
	for ($j=0; $j<$notable; $j++) {
    	if ($mysqltable == $mysqltablelist[$j]) {
  			echo "<option selected>$mysqltablelist[$j]";
  		} else {
  			echo "<option>$mysqltablelist[$j]";
  		}
  	}
	echo "</option></select></td>";
	
	echo "<td rowspan=4 align=middle>";
	if (getenv("server_addr") == "127.0.0.1") {
		echo "<input type=\"submit\" name=addmod value=GO></td></tr>";
	} else {
		echo "<button type=\"submit\" name=addmod>
		<font color=#0000ff size=3><b>&nbsp;&nbsp;GO&nbsp;&nbsp;</b></font></button>";
	}
	
	echo "<tr><th align=left>Actions</th>";
	echo "<td><select name=tblaction>";
	$j=0;	$actlist[$j] = "Add New User";
	$j++; 	$actlist[$j] = "Delete User";
	$j++;	$actlist[$j] = "Add New DB to Everyone";
	if ($tblaction == "") {
		$tblaction = $actlist[2];
	}
	$j++;
	for ($i=0; $i<$j; $i++) {
		if ($tblaction == $actlist[$i]) {
  			echo "<option selected>$actlist[$i]";
  		} else {
  			echo "<option>$actlist[$i]";
  		}
	}
	echo "</option></select></td></tr>";
	echo "<tr><th align=left>DB List</th><td><select size=\"1\" name=\"dblist\">";
  		$result = mysql_list_dbs();
		$i = 0;
		while ($i < mysql_num_rows ($result)) {
    		$tb_names[$i] = mysql_tablename ($result, $i);
    		if ($tb_names[$i] != 'test'){
    		//if ($tb_names[$i] != 'mysql'){
    			if ($tb_names[$i] == "$dbname") {
					echo '<option selected>'; echo $tb_names[$i]; echo '</option>';
        		} else {
					echo '<option>'; echo $tb_names[$i]; echo '</option>';
				}
			}//}
    		$i++;
		}
	echo "</select></td></tr>";
	
	echo "<tr><th align=left>New User: <br>separated by (@)<br>To Add New User</th>";
	$newuser = ereg_replace("$", "", $newuser);
	echo '<td><textarea rows="3" name="newuser" cols="30">'.$newuser.'</textarea></td></tr>';
	
	$no = $nouser;
	$sql = "SELECT User FROM mysql.user 
		WHERE User!='webmaster' and User!='root' 	and User!='anyone';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	while (list($User) = mysql_fetch_array($result)) {
		$User = trim($User);
		if ($User) {
			$newusr = 1;
			for ($i=0; $i<$no; $i++) {
				if ($User == $userlist[$i][0]) {
					$newusr = 0;
					break;
				}
			}
			if ($newusr == 1) {
				$userlist[$nouser][0] = $User;
				$nouser++;
			}
		}
	}
	
	echo "<tr><th align=left>Current User ($nouser):<br>To Delete User</th>";
	echo '<td><table>';
	if ($nouser>0) {
		$noinrow = 4;
		for ($i=0; $i<$nouser; $i++) {
			$k = (int)($i/$noinrow);
			$j = $k*$noinrow - $i;
			if ($j == 0) {
				echo "<tr>";
				$close = 0;
			}
			echo "<td><input type=checkbox name=curuser[] value=\"".$userlist[$i][0]."\">";
			$statuscontext = $userlist[$i][1];
			$status = " onMouseOver=\"self.status='$statuscontext'; return true\" "
				."onMouseOut=\"self.status='Privilege Setup'; return true\" ";
			if (!$statuscontext) {
				echo "<a $status><font color=#ff0000>".$userlist[$i][0]."</font><a/></td>";
			} else {
				echo "<a $status>".$userlist[$i][0]."<a/></td>";
			}
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
echo "<hr>";
echo $msg;
backtotop();
function backtotop(){
	echo "<br><hr><a href=#top>Back to top</a><a id=end></a><br><br><br>";
}

?>
</body>
