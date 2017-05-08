<html>

<head>
<title></title>
</head>

<body bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG" leftmargin="50">

<?php
## control page access
include("admin_access.inc");
include('rla_functions.inc');
include('str_decode_parse.inc');

echo "<a id=top><h1 align=center>MySQL User & Privilege Manipulation</h1></a>";

echo "<p align=center>";
$f1 = "<font size=2><b>";
$f2 = "</b></font>";
echo "<a href=\"/$phpdir/adminctl_top.php$admininfo\">[$f1"."Admin Main Page$f2]</a>";
echo "<a href=\"".$PHP_SELF."?action=newuserform\">[$f1"."New User$f2]</a>";
//echo "<a href=\"".$PHP_SELF."?action=deleteuserform\">[$f1"."Delete User$f2]</a>";
echo "<a href=\"".$PHP_SELF."?action=addonedbtoalluser\">[$f1"."Add DB to ALL$f2]</a>";
echo "<a href=\"".$PHP_SELF."?action=rmonedbtoalluser\">[$f1"."Remove DB From ALL$f2]</a>";
echo "<hr>";

include("connet_root_once.inc");

if ($delete) {
	$sql = "DELETE FROM mysql.user 
        WHERE User='$user'";
	$result = mysql_query($sql);
	include("err_msg.inc");
   //echo "$sql<br>";
	$sql = "DELETE FROM mysql.db 
        WHERE User='$user'";
	$result = mysql_query($sql);
	include("err_msg.inc");
	echo "<h2>$user has been deleted successfully.</h2>";
   //echo "$sql<br>";

	backtotop();
}

if ($regdelete) {
	$sql = "DELETE FROM timesheet.employee 
        WHERE email_name='$user'";
	$result = mysql_query($sql);
	include("err_msg.inc");
   echo "$sql<br>";
	echo "<h2>$user has been deleted successfully from timesheet.employee.</h2>";
	backtotop();
}

if (!$action) {
	$action = "newuserform";
}

#######################################
#######################################
#	overall previlidges
$tmp = "Select_priv,Insert_priv,Update_priv,Delete_priv,Create_priv,Drop_priv,Reload_priv,".
	"Shutdown_priv,Process_priv,File_priv,Grant_priv,References_priv,Index_priv,Alter_priv";
$allprivlist = explode(",", $tmp);

#	db previlidges
$tmp = "Select_priv,Insert_priv,Update_priv,Delete_priv,Create_priv,Drop_priv,Grant_priv,".
	"References_priv,Index_priv,Alter_priv";
$dbprivlist = explode(",", $tmp);

#	table in mysql db
$tmp = "user,db,tables_priv,columns_priv,host,func";
$mysqltablelist[$notable]= explode(",", $tmp);

include("find_user_db.inc");
include("find_users.inc");

#######################################
#######################################

if ($tblaction == "Add DB To ALL User" || $tblaction == "Remove DB From ALL User") {
	$j=0;
	for ($i=0; $i<$nodb; $i++) {
		$tmp = "dblist$i";
		if ($$tmp) {
			$grantdblist[$j] = $$tmp;
			//echo "db $j = ".$grantdblist[$j]."<br>";
			$j++;
		}
	}
	if ($j==0) {
		echo "<h2>No Database is seleted.</h2>";
	} else {
		echo "<h2>$tblaction.</h2>";
		echo "<ul><b>DB list</b><br>";
		for ($i=0; $i<$j; $i++) {
			echo "<li>".$grantdblist[$i]."</li>";
		}
		echo "</ul>";
		if ($tblaction == "Remove DB From ALL User") {
			for ($i=0; $i<$j; $i++) {
				$Db = $grantdblist[$i] ;
				$sql = "DELETE FROM mysql.db WHERE Db='$Db' ";
				$result = mysql_query($sql);
				include("err_msg.inc");
   				echo "$sql<br>";
			}
			$action = "rmonedbtoalluser";
		} elseif ($tblaction == "Add DB To ALL User") {
			for ($i=0; $i<$nodbpriv; $i++) {
				$tmp = "dbpriviledge$i";
				if ($$tmp) {
					$grantdbprivlist[$i] = "Y";
				} else {
					$grantdbprivlist[$i] = "N";
				}
				//echo "db prev $i ".$dbprivlist[$i]." = ".$grantdbprivlist[$i]." (".$$tmp.")<br>";
			}
			for ($i=0; $i<$mysqluser; $i++) {
				$User = $mysqluserlist[$i][0];
				for ($k=0; $k<count($grantdblist); $k++) {
					$db = $grantdblist[$k];
					$sql ="INSERT INTO mysql.db VALUES('localhost','$db','$User'";
					for ($j=0; $j<$nodbpriv; $j++) {
						$sql .=",'".$grantdbprivlist[$j]."'";
					}
					$sql .=");";
					//echo "$i-$k: $sql.<br>";		
					echo "$sql<br>";
					$result = mysql_query($sql);
					include("err_msg.inc");
				}
			}
			$action = "addonedbtoalluser";
		}
   }
	backtotop();
}
##	processing
$b1 = "\"<font color=#0000ff>";
$b2 = "</font>\"";

if ($tblaction == "Add New DB to Everyone") {
	echo "<H2>The following person have access to DB $dblist</h2>";
	for ($i=0; $i<$noreguser; $i++) {
		$adduser = $reguserlist[$i][0];
		$sql ="INSERT INTO mysql.db VALUES('localhost','$dblist','$adduser','Y','Y','Y','Y','N','N','N','N','N','N');";
		echo "$sql<br>";
		$result = mysql_query($sql);
		include("err_msg.inc");
		echo "$adduser<br>";
	}
	echo "<hr>";
}

if ($tblaction == "Add New User" && $addmod) {
	$j=0;
	for ($i=0; $i<$nodb; $i++) {
		$tmp = "dblist$i";
		if ($$tmp) {
			$grantdblist[$j] = $$tmp;
			//echo "db $j = ".$grantdblist[$j]."<br>";
			$j++;
		}
	}
		
	for ($i=0; $i<$noallpriv; $i++) {
		$tmp = "allpriviledge$i";
		if ($$tmp) {
			$grantallprivlist[$i] = "Y";
		} else {
			$grantallprivlist[$i] = "N";
		}
		//echo "all prev $i ".$allprivlist[$i]." = ".$grantallprivlist[$i]." (".$$tmp.")<br>";
	}

	for ($i=0; $i<$nodbpriv; $i++) {
		$tmp = "dbpriviledge$i";
		if ($$tmp) {
			$grantdbprivlist[$i] = "Y";
		} else {
			$grantdbprivlist[$i] = "N";
		}
		//echo "db prev $i ".$dbprivlist[$i]." = ".$grantdbprivlist[$i]." (".$$tmp.")<br>";
	}
		
	$newuser = trim($newuser);
	if (!$newuser) {
		echo "<h2><font color=#ff0000>Please type new user in the textbox.</font></h2>";
		backtotop();
		exit;
	}
	$newuserlist = explode("@", $newuser);
	if (count($newuserlist)>1) {
		echo "<h2>These new users have been added successfully.</h2>";
	} else {
		echo "<h2>New user has been added successfully.</h2>";
	}
	
	$sql = "SELECT description as defaultpwd
        FROM logging.sysmastertable 
        WHERE item='defaultpwd'";
 	$result = mysql_query($sql);
	include("err_msg.inc");
   list($defaultpwd) = mysql_fetch_array($result);
   
	for ($i=0; $i<count($newuserlist); $i++) {
		$adduser = $newuserlist[$i];
		$userpwd = $adduser.$defaultpwd;
		//insert to table mysql.user
		echo "User: $adduser; Password: $userpwd<br>";
		$sql = "INSERT INTO mysql.user VALUES('localhost','$adduser',PASSWORD('$userpwd')";
		for ($j=0; $j<$noallpriv; $j++) {
			$sql .=",'".$grantallprivlist[$j]."'";
		}
		$sql .=");";
		echo "$sql.<br>";
		$result = mysql_query($sql);
		include("err_msg.inc");
		
		for ($k=0; $k<count($user_db_list); $k++) {
			$db = $user_db_list[$k];
			$sql ="INSERT INTO mysql.db VALUES('localhost','$db','$adduser'";
			for ($j=0; $j<$nodbpriv; $j++) {
				$sql .=",'".$grantdbprivlist[$j]."'";
			}
			$sql .=");";
			echo "$sql.<br>";		
			$result = mysql_query($sql);
			include("err_msg.inc");
		}
	}
	backtotop();
	$action = "newuserform";
	include("find_users.inc");
}

#####################################
if ($action) {
##	list tables in MySQL and action selection
	echo "<form name=seltable action=\"$PHP_SELF\">";
	echo "<table border=1>";
	
/*
	echo "<tr><th>Tables in MySQL DB</th>";
	echo "<td><select name=mysqltable>";
	if ($mysqltable == "") {
		$mysqltable = $mysqltablelist[0];
	}
	for ($j=0; $j<count($mysqltablelist); $j++) {
    	if ($mysqltable == $mysqltablelist[$j]) {
  			echo "<option selected>$mysqltablelist[$j]";
  		} else {
  			echo "<option>$mysqltablelist[$j]";
  		}
  	}
	echo "</option></select></td>";	
	echo "<td rowspan=4 align=middle></td></tr>";
//*/

	if ($action == "newuserform") {
		$actlist = "Add New User";
	}
	if ($action == "deleteuserform") {
		$actlist = "Delete User"; ;
	}
	if ($action == "addonedbtoalluser") {
		$actlist = "Add DB To ALL User";
	}
	if ($action == "rmonedbtoalluser") {
		$actlist = "Remove DB From ALL User";
	}

	echo "<tr><th align=left>Actions</th>";
	echo "<th><input type=hidden name=tblaction value=\"$actlist\">";
	
	if (getenv("server_addr") == "127.0.0.1") {
		echo "<input type=\"submit\" name=addmod value=\"$actlist\">";
	} else {
		echo "<button type=\"submit\" name=addmod>
		<font color=#0000ff size=2><b>$actlist</b></font></button>";
	}
	echo "</th></tr>";
	
	if ($action == "newuserform") {
		echo "<tr><th align=left>New ";
	}elseif ($action == "deleteuserform") {
		echo "<tr><th align=left>Remove ";
	}
	if ($action == "newuserform" || $action == "deleteuserform") {
		echo "Users: <font size=2 color=#ff0000><br>multiple users<br>separated by @</font></th>";
		$newuser = ereg_replace("$", "", $newuser);
		echo '<td><textarea rows="3" name="newuser" cols="30">'.$newuser.'</textarea></td></tr>';
	}
	if ($action == "newuserform" || $action == "addonedbtoalluser" || $action == "rmonedbtoalluser") {
		$chk = "";
		if ($action == "newuserform" || $action == "addonedbtoalluser") {
			echo "<tr><th align=left>Grant DB Access</th><td>";
			if ($action == "newuserform") {
				$chk = "checked";
			}
		} elseif ($action == "rmonedbtoalluser") {
			echo "<tr><th align=left>Revoke DB Access</th><td>";		
		}
  		$result = mysql_list_dbs();
		echo "<table border=0>";
		echo "<input type=hidden name=nodb value=\"".count($user_db_list)."\">";
		$k = 4;
		for ($i=0; $i< count($user_db_list); $i++) {
			$j = (int)($i/$k)*$k;
    		if ($j == $i) {
    			echo "<tr><td>";
    			$tail = "</td>";
    		} elseif ($j == $i && $i>=$k) {
    			echo "<td>";
    			$tail = "</td><tr>";
    		} else {
    			echo "<td>";
    			$tail = "</td>";
    		}
    		//$tmp= "$i-$j; ".$tmp;
			echo "<input type=\"checkbox\" name=\"dblist$i\" value=\"".$user_db_list[$i].
				"\" $chk>".ucwords($user_db_list[$i]).$tail;		
		}
		echo "</table>";
		echo "</td></tr>";
	}
	
	if ($action == "newuserform") {
		$k = 7;
		echo "<tr><th align=left>Grant Overall<br>Privileges to User</th><td>";
		echo "<table border=0>";
		echo "<input type=hidden name=noallpriv value=\"".count($allprivlist)."\">";
		for ($i=0; $i<count($allprivlist); $i++) {
			$tmp = ereg_replace("\_priv", "", $allprivlist[$i]);
			$j = (int)($i/$k)*$k;
    		if ($j == $i) {
    			echo "<tr><td>";
    			$tail = "</td>";
    		} elseif ($j == $i && $i>=$k) {
    			echo "<td>";
    			$tail = "</td><tr>";
    		} else {
    			echo "<td>";
    			$tail = "</td>";
    		}
    		//$tmp= "$i-$j; ".$tmp;
    		echo "<input type=checkbox name=\"allpriviledge$i\" value=\"".$allprivlist[$i]."\"";
			if ($i==2) {
				echo " checked";
			}
    		echo ">".$tmp.$tail;
		}
		echo "</table></td></tr>";
	}
	if ($action == "newuserform" || $action == "addonedbtoalluser") {
		if ($action == "newuserform") {
			$k = 7;
		} else {
			$k = 5;
		}
		echo "<tr><th align=left>Grant Privileges<br> to DB</th><td>";
		echo "<table border=0>";
		echo "<input type=hidden name=nodbpriv value=\"".count($dbprivlist)."\">";
		for ($i=0; $i<count($dbprivlist); $i++) {
			$tmp = ereg_replace("\_priv", "", $dbprivlist[$i]);
			$j = (int)($i/$k)*$k;
    		if ($j == $i) {
    			echo "<tr><td>";
    			$tail = "</td>";
    		} elseif ($j == $i && $i>=$k) {
    			echo "<td>";
    			$tail = "</td><tr>";
    		} else {
    			echo "<td>";
    			$tail = "</td>";
    		}
    		//$tmp= "$i-$j; ".$tmp;
    		echo "<input type=checkbox name=\"dbpriviledge$i\" value=\"".$dbprivlist[$i]."\"";
			if ($i<4) {
				echo " checked";
			}
    		echo ">".$tmp.$tail;
		}
		echo "</table>";
		echo "</td></tr>";
	}
		
	echo "<tr><th align=left>Current Mysql<br>Users ($mysqluser):</th>";
	echo '<td><table>';
	if ($mysqluser>0) {
		if ($action == "deleteuserform") {
			$noinrow = 8;
		} else {
			$noinrow = 16;
		}
		if ($action == "newuserform") {
			echo "<tr><td colspan=$noinrow><b>Click User Name To Delete MySQL User</b></td></tr>";
		}
		for ($i=0; $i<$mysqluser; $i++) {
			$k = (int)($i/$noinrow);
			$j = $k*$noinrow - $i;
			if ($j == 0) {
				echo "<tr>";
				$close = 0;
			}
			echo "<td>";
			if ($action == "deleteuserform") {
				echo "<input type=checkbox name=curuser[] value=\"".$mysqluserlist[$i][0]."\">";
			}
			$statuscontext = $mysqluserlist[$i][1];
			$status = " onMouseOver=\"self.status='$statuscontext'; return true\" "
				."onMouseOut=\"self.status='Privilege Setup'; return true\" ";
			if ($action == "newuserform") {
				$qry = "delete=delete&user=".$mysqluserlist[$i][0];
				$userstr	=	"?".base64_encode($qry);
				echo "<a href=\"".$PHP_SELF."$userstr\" $status>[";
				echo $mysqluserlist[$i][0]."]</a></td>";
			} else {
				if (!$statuscontext) {
					echo "<a $status><font color=#ff0000>[".$mysqluserlist[$i][0]."]</font>";
				} else {
					echo "[<a $status>".$mysqluserlist[$i][0]."]";
				}
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
	
	echo "<tr><th align=left>Registered<br>Intranet User<br>($noreguser):</th>";
	echo '<td><table>';
	if ($noreguser>0) {
		if ($action == "deleteuserform") {
			$noinrow = 8;
		} else {
			$noinrow = 16;
		}
		if ($action == "newuserform") {
			echo "<tr><td colspan=$noinrow><b>Click User Name To Delete User's Registration</b><br></td></tr>";
		}
		for ($i=0; $i<$noreguser; $i++) {
			$k = (int)($i/$noinrow);
			$j = $k*$noinrow - $i;
			if ($j == 0) {
				echo "<tr>";
				$close = 0;
			}
			echo "<td>";
			$statuscontext = $reguserlist[$i][1];
			$status = " onMouseOver=\"self.status='$statuscontext'; return true\" "
				."onMouseOut=\"self.status='Privilege Setup'; return true\" ";
			if ($action == "newuserform") {
				$qry = "regdelete=delete&user=".$reguserlist[$i][0];
				$userstr	=	base64_encode($qry);
				echo "<a href=\"".$PHP_SELF."?$userstr\" $status>[";
				echo $reguserlist[$i][0]."]</a></td>";
			} else {
				if (!$statuscontext) {
					echo "<a $status><font color=#ff0000>[".$reguserlist[$i][0]."]</font>";
				} else {
					echo "<a $status>[".$reguserlist[$i][0]."]";
				}
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
	backtotop();
}

function backtotop(){
	echo "<br><hr><a href=#top>Back to top</a><a id=end></a><br><br><br>";
}

?>
</body>
