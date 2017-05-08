<html>

<head>
<title></title>
</head>

<body topmargin="0" leftmargin="50">

<?php
	echo "<form name=addormod action=\"$PHP_SELF\">";
	echo "<input type=hidden name=mysqltable value=\"$mysqltable\">";
	echo "<input type=hidden name=tblaction value=\"$tblaction\">";
	echo "<input type=hidden name=fld value=\"$fld\">";
	echo "<input type=hidden name=rcd value=\"$rcd\">";
	echo "<table border=1>";
	$sql = "";
	$b1 = "<font size=2><b>";
	$b2 = "</b></font>";
	if ($mysqltable == "user") {
		if ($tblaction == "Modify Record") {
			$sql = "SELECT Host, User, Password, Select_priv, Insert_priv, Update_priv, 
			Delete_priv, Create_priv, Drop_priv, Reload_priv, Shutdown_priv, Process_priv, 
			File_priv, Grant_priv, References_priv, Index_priv, Alter_priv FROM 
			mysql.$mysqltable where $fld='$rcd';";
			$result = mysql_query($sql);
			include("err_msg.inc");
			if ($result) {
				list($Host, $User, $Password, $Select_priv, $Insert_priv, $Update_priv, 
					$Delete_priv, $Create_priv, $Drop_priv, $Reload_priv, $Shutdown_priv,
			 		$Process_priv, $File_priv, $Grant_priv, $References_priv, $Index_priv, 
			 		$Alter_priv) = mysql_fetch_array($result);
			}
		}
		if ($Host == "") {
			$Host = "localhost";
		}
		echo "<tr><td>$b1"."Host$b2</td><td>
			<input type=text name=Host value=$Host size=10></td></tr>";
		echo "<tr><td>$b1"."User$b2</td><td>";
		if ($User) {
			echo "$b1"."$User$b2<input type=hidden name=User value=$User></td></tr>";
		} else {
			echo "<input type=text name=User size=10></td></tr>";
		}
		echo "<tr><td>$b1"."Password$b2</td>";
		if ($Password) {
			echo "<td>$b1"."Set by User$b2";
		} else {
			echo "<td>$b1"."Default$b2";
		}
			echo "<input type=hidden name=Password value=\"\" size=10></td></tr>";
 		echo "<tr><td>$b1"."Select_priv$b2</td><td><select name=Select_priv>";
			selectyesno("$Select_priv");
 		echo "<tr><td>$b1"."Insert_priv$b2</td><td><select name=\"Insert_priv\">";
			selectyesno("$Insert_priv");
 		echo "<tr><td>$b1"."Update_priv$b2</td><td><select name=\"Update_priv\">";
 		if (!$Update_priv) {
 			$Update_priv = "Y";
 		}
			selectyesno("$Update_priv"); 
 		echo "<tr><td>$b1"."Delete_priv$b2</td><td><select name=\"Delete_priv\">";
			selectyesno("$Delete_priv");
		echo "<tr><td>$b1"."Create_priv$b2</td><td><select name=\"Create_priv\">";
			selectyesno("$Create_priv");
  		echo "<tr><td>$b1"."Drop_priv$b2</td><td>	<select name=\"Drop_priv\">";
			selectyesno("$Drop_priv"); 
  		echo "<tr><td>$b1"."Reload_priv$b2</td><td><select name=\"Reload_priv\">";
			selectyesno("$Reload_priv");
 		echo "<tr><td>$b1"."Shutdown_priv$b2</td><td><select name=\"Shutdown_priv\">";
			selectyesno("$Shutdown_priv");
 		echo "<tr><td>$b1"."Process_priv$b2</td><td><select name=\"Process_priv\">";
			selectyesno("$Process_priv"); 
		echo "<tr><td>$b1"."File_priv$b2</td><td>	<select name=\"File_priv\">";
			selectyesno("$File_priv");
		echo "<tr><td>$b1"."Grant_priv$b2</td><td><select name=\"Grant_priv\">";
			selectyesno("$Grant_priv"); 
 		echo "<tr><td>$b1"."References_priv$b2</td><td><select name=\"References_priv\">";
			selectyesno("$References_priv");
 		echo "<tr><td>$b1"."Index_priv$b2</td><td><select name=\"Alter_priv\">";
			selectyesno("$Alter_priv"); 
 		echo "<tr><td>$b1"."Alter_priv$b2</td><td><select name=\"Alter_priv\">";
			selectyesno("$Alter_priv"); 
	} elseif ($mysqltable == "db") {
		if ($tblaction == "Modify Record") {
			$sql = "SELECT Host, Select_priv, Insert_priv, Update_priv, 
				Delete_priv, Create_priv, Drop_priv, Grant_priv, References_priv, Index_priv, 
				Alter_priv FROM mysql.db WHERE Db='$Db' and User='$User';";
			$result = mysql_query($sql);
			include("err_msg.inc");
			if ($result) {
				list($Host, $Select_priv, $Insert_priv, $Update_priv, 
					$Delete_priv, $Create_priv, $Drop_priv, $Grant_priv, $References_priv, $Index_priv, 
					$Alter_priv) = mysql_fetch_array($result);
			}
		}	
		if ($Host == "") {
			$Host = "localhost";
		}
		echo "<tr><td>$b1"."Host$b2</td><td>
			<input type=text name=Host value=$Host size=10></td></tr>";
			
		echo "<tr><td>$b1"."Db$b2</td><td>";
		if ($Db) {
			echo "$b1"."$Db$b2<input type=hidden name=Db value=$Db>";
		} else {
			echo "<select name=Db>";
  			$result = mysql_list_dbs();
			$i = 0;
			while ($i < mysql_num_rows ($result)) {
    			$dbnames = mysql_tablename ($result, $i);
    			if ($dbnames != 'test'){
    				if ($dbnames != 'mysql'){
    					if ($dbnames == "$Db") {
							echo "<option selected>$dbnames";
        				} else {
							echo "<option>$dbnames";
						}
					}
				}
    		$i++;
		}
		echo "</option></select>";
		}
		echo "</td></tr>";
		echo "<tr><td>$b1"."User$b2</td><td>";
		if ($User) {
			echo "$b1"."$User$b2<input type=hidden name=User value=$User></td></tr>";
		} else {
			echo "<input type=text name=User size=10></td></tr>";
		}
 		echo "<tr><td>$b1"."Select_priv$b2</td><td><select name=\"Select_priv\">";
 		if ($Select_priv == "") {
 			$Select_priv = "Y";
 		}
			selectyesno("$Select_priv"); 
 		echo "<tr><td>$b1"."Insert_priv$b2</td><td><select name=\"Insert_priv\">";
 		if ($Insert_priv == "") {
 			$Insert_priv = "Y";
 		}
			selectyesno("$Insert_priv");
 		echo "<tr><td>$b1"."Update_priv$b2</td><td><select name=\"Update_priv\">";
 		if ($Update_priv == "") {
 			$Update_priv = "Y";
 		}
			selectyesno("$Update_priv"); 
 		echo "<tr><td>$b1"."Delete_priv$b2</td><td><select name=\"Delete_priv\">";
 		if ($Delete_priv == "") {
 			$Delete_priv = "Y";
 		}
			selectyesno("$Delete_priv"); 
 		echo "<tr><td>$b1"."Create_priv$b2</td><td><select name=\"Create_priv\">";
			selectyesno("$Create_priv"); 
 		echo "<tr><td>$b1"."Drop_priv$b2</td><td><select name=\"Drop_priv\">";
			selectyesno("$Drop_priv"); 
 		echo "<tr><td>$b1"."Grant_priv$b2</td><td><select name=\"Grant_priv\">";
			selectyesno("$Grant_priv"); 
 		echo "<tr><td>$b1"."References_priv$b2</td><td><select name=\"References_priv\">";
			selectyesno("$References_priv"); 
 		echo "<tr><td>$b1"."Index_priv$b2</td><td><select name=\"Index_priv\">";
			selectyesno("$Index_priv"); 
 		echo "<tr><td>$b1"."Alter_priv$b2</td><td><select name=\"Alter_priv\">";
			selectyesno("$Alter_priv"); 
	} elseif ($mysqltable == "tables_priv") {
		if ($tblaction == "Modify Record") {
			$sql = "SELECT Host, Grantor, Timestamp, Table_priv, 
				Column_priv FROM mysql.tables_priv WHERE Db='$Db' and 
				User='$User' and Table_name='$Table_name';";
			$result = mysql_query($sql);
			include("err_msg.inc");
			if ($result) {
				list($Host, $Grantor, $Timestamp, $Table_priv, $Column_priv) = mysql_fetch_array($result);
			}
		}
		if ($Host == "") {
			$Host = "localhost";
		}
		echo "<tr><td>$b1"."Host$b2</td><td>
			<input type=text name=Host value=$Host size=10></td></tr>";
			
		echo "<tr><td>$b1"."User$b2</td><td>";
		include("mysql_inTabCol_user_priv.inc");
				
		$col = 0;
		include("mysql_inTabCol_privsetup.inc");
		echo "<tr><td>$b1"."Db.Table Name$b2</td><td>";
		if ($Table_name) {
			$Table_name = "$Db.$Table_name";
			echo "$b1"."$Table_name$b2<input type=hidden name=Table_name value=$Table_name></td></tr>";
		} else {
			echo "<select name=Table_name>";
			for ($j=0; $j<$notab; $j++) {
 					echo "<option>$tablist[$j]";
 			}
			echo "</option></select>$b1$notab tables.$b2</td></tr>";
		}
		
		echo "<tr><td>$b1"."Grantor$b2</td><td>";
		if ($Grantor) {
			echo "<input type=text name=Grantor value=$Grantor size=10></td></tr>";
		} else {
			echo "<input type=text name=Grantor size=10></td></tr>";
		}
		
		echo "<tr><td>$b1"."Table_priv$b2</td><td>";
		$tmp = 'Select@Insert@Update@Delete@Create@Drop@Grant@References@Index@Alter';
		$tmplist = explode("@",$tmp);
		echo "<table border=1>";
		if ($Table_priv) {
			$privlist = explode(",", $Table_priv);
			$no = count($privlist);
		}
		$noinrow = 4;
		for ($i=0; $i<count($tmplist); $i++) {
			$k = (int)($i/$noinrow);
			$j = $k*$noinrow - $i;
			if ($j == 0) {
				echo "<tr>";
				$close = 0;
			}
			$chked = "";
			for ($m=0; $m<$no; $m++) {
				if ($tmplist[$i] == $privlist[$m]) {
					$chked = "checked";
					break;
				}
			}
			echo "<td><input type=checkbox name=Table_priv[] value=\"$tmplist[$i]\" $chked>".$b1.$tmplist[$i].$b2."</td>";
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
		echo "</td></tr>";
		
		echo "<tr><td>$b1"."Column_priv$b2</td><td><table>";
		$tmp = 'Select@Insert@Update@References';
		$tmplist = explode("@",$tmp);
		if ($Column_priv) {
			$privlist = explode(",", $Column_priv);
			$no = count($privlist);
		}
		$noinrow = 4;
		for ($i=0; $i<count($tmplist); $i++) {
			$k = (int)($i/$noinrow);
			$j = $k*$noinrow - $i;
			if ($j == 0) {
				echo "<tr>";
				$close = 0;
			}
			$chked = "";
			for ($m=0; $m<$no; $m++) {
				if ($tmplist[$i] == $privlist[$m]) {
					$chked = "checked";
					break;
				}
			}
			echo "<td><input type=checkbox name=Column_priv[] value=\"$tmplist[$i]\" $chked>".$b1.$tmplist[$i].$b2."</td>";
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
		echo "</td></tr>";
	} elseif ($mysqltable == "columns_priv") {
		if ($tblaction == "Modify Record") {
			$sql = "SELECT Host, Column_priv 
				FROM mysql.columns_priv WHERE Db='$Db' and User='$User' and 
				Table_name='$Table_name' and Column_name='$Column_name';";
			$result = mysql_query($sql);
			include("err_msg.inc");
			if ($result) {
				list($Host, $Column_priv) = mysql_fetch_array($result);
			}
		}
		if ($Host == "") {
			$Host = "localhost";
		}
		echo "<tr><td>$b1"."Host$b2</td><td>
			<input type=text name=Host value=$Host size=10></td></tr>";
			
		echo "<tr><td>$b1"."User$b2</td><td>";
		include("mysql_inTabCol_user_priv.inc");
		
		$col = 1;
		include("mysql_inTabCol_privsetup.inc");
		echo "<tr><td>$b1"."Db.Table.Field Name$b2</td><td>";
		if ($Column_name) {
			$Column_name = "$Db.$Table_name.$Column_name";
			echo "$b1"."$Column_name$b2<input type=hidden name=Column_name value=$Column_name></td></tr>";
		} else {
			echo "<select name=Column_name>";
			for ($j=0; $j<$nocol; $j++) {
 					echo "<option>$collist[$j]";
 			}
			echo "</option></select>$b1$nocol tables.$b2</td></tr>";
		}
		
		echo "<tr><td>$b1"."Column_priv$b2</td><td><table>";
		$tmp = 'Select@Insert@Update@References';
		$tmplist = explode("@",$tmp);
		if ($Column_priv) {
			$privlist = explode(",", $Column_priv);
			$no = count($privlist);
		}
		$noinrow = 4;
		for ($i=0; $i<count($tmplist); $i++) {
			$k = (int)($i/$noinrow);
			$j = $k*$noinrow - $i;
			if ($j == 0) {
				echo "<tr>";
				$close = 0;
			}
			$chked = "";
			for ($m=0; $m<$no; $m++) {
				if ($tmplist[$i] == $privlist[$m]) {
					$chked = "checked";
					break;
				}
			}
			echo "<td><input type=checkbox name=Column_priv[] value=\"$tmplist[$i]\" $chked>".$b1.$tmplist[$i].$b2."</td>";
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
		echo "</td></tr>";
	} elseif ($mysqltable == "host") {
		if ($tblaction == "Modify Record") {
			$sql = "SELECT Select_priv, Insert_priv, Update_priv, Delete_priv, 
				Create_priv, Drop_priv, Grant_priv, References_priv, Index_priv, Alter_priv FROM 
				mysql.host WHERE Host='$Host' and Db='$Db';";
			$result = mysql_query($sql);
			include("err_msg.inc");
			if ($result) {
				list($Select_priv, $Insert_priv, $Update_priv, $Delete_priv, 
				$Create_priv, $Drop_priv, $Grant_priv, $References_priv, $Index_priv, 
				$Alter_priv) = mysql_fetch_array($result);
			}
		}
		
		echo "<tr><td>$b1"."Host$b2</td><td>";
		if ($Host) {
			echo "$b1"."$Host$b2<input type=hidden name=Host value=$Host></td></tr>";
		} else {
			echo "<input type=text name=Host size=10></td></tr>";
		}
			
		echo "<tr><td>$b1"."Db$b2</td><td>";
		if ($Db) {
			echo "$b1"."$Db$b2<input type=hidden name=Db value=$Db>";
		} else {
			echo "<select name=Db>";
  			$result = mysql_list_dbs();
			$i = 0;
			while ($i < mysql_num_rows ($result)) {
    			$dbnames = mysql_tablename ($result, $i);
    			if ($dbnames != 'test'){
    				if ($dbnames != 'mysql'){
    					if ($dbnames == "$Db") {
							echo "<option selected>$dbnames";
        				} else {
							echo "<option>$dbnames";
						}
					}
				}
    		$i++;
		}
		echo "</option></select>";
		}
		echo "</td></tr>";
		
  		echo "<tr><td>$b1"."Select_priv$b2</td><td>	<select name=\"Select_priv\">";
			selectyesno("$Select_priv"); 
  		echo "<tr><td>$b1"."Insert_priv$b2</td><td>	<select name=\"Insert_priv\">";
			selectyesno("$Insert_priv"); 
  		echo "<tr><td>$b1"."Update_priv$b2</td><td>	<select name=\"Update_priv\">";
			selectyesno("$Update_priv"); 
  		echo "<tr><td>$b1"."Delete_priv$b2</td><td>	<select name=\"Delete_priv\">";
			selectyesno("$Delete_priv"); 
  		echo "<tr><td>$b1"."Create_priv$b2</td><td>	<select name=\"Create_priv\">";
			selectyesno("$Create_priv"); 			
  		echo "<tr><td>$b1"."Drop_priv$b2</td><td>	<select name=\"Drop_priv\">";
			selectyesno("$Drop_priv"); 
  		echo "<tr><td>$b1"."Grant_priv$b2</td><td>	<select name=\"Grant_priv\">";
			selectyesno("$Grant_priv"); 
  		echo "<tr><td>$b1"."References_priv$b2</td><td>	<select name=\"References_priv\">";
			selectyesno("$References_priv"); 
  		echo "<tr><td>$b1"."Index_priv$b2</td><td>	<select name=\"Index_priv\">";
			selectyesno("$Index_priv"); 
  		echo "<tr><td>$b1"."Alter_priv$b2</td><td>	<select name=\"Alter_priv\">";
			selectyesno("$Alter_priv"); 
	} elseif ($mysqltable == "func") {
		if ($tblaction == "Modify Record") {
			$sql = "SELECT ret, dl, type FROM mysql.func WHERE name='$name';";
			$result = mysql_query($sql);
			include("err_msg.inc");
			if ($result) {
				list($ret, $dl, $type) = mysql_fetch_array($result);
			}
		}
		echo "<tr><td>$b1"."Function Name$b2</td><td>";
		if ($name) {
			echo "$b1"."$name$b2<input type=hidden name=name value=$name></td></tr>";
		} else {
			echo "<input type=text name=name size=20></td></tr>";
		}
		echo "<tr><td>$b1"."ret$b2</td><td>";
		if ($ret) {
			echo "$b1"."$ret$b2<input type=hidden name=ret value=$ret></td></tr>";
		} else {
			echo "<input type=text name=ret value=0 size=10></td></tr>";
		}
		echo "<tr><td>$b1"."dl$b2</td><td>";
		if ($dl) {
			echo "<textarea rows=\"2\" name=\"dl\" cols=\"20\">$dl</textarea>";
		} else {
			echo "<textarea rows=\"2\" name=\"dl\" cols=\"20\"></textarea>";
		}
		echo "</td></tr>";
		echo "<tr><td>$b1"."type$b2</td><td><select name=type>";
		$tmp = 'function@aggregate';
		$tmplist = explode("@",$tmp);
		for ($i=0; $i<count($tmplist); $i++) {
			if ($type == $tmplist[$i]) {
				echo "<option selected>".$tmplist[$i];
			} else {
				echo "<option >".$tmplist[$i];
			}
		}
		echo "</option></select></td></tr>";
	}
	
	//echo $sql."<br>";
	echo "<tr><td colspan=2 align=middle>";
	if (getenv("server_addr") == "127.0.0.1") {
		echo "<input type=\"submit\" name=addmodrcd value=\"$tblaction\"></td></tr>";
	
	} else {
		echo "<button type=\"submit\" name=addmodrcd><font size=3><b>$tblaction</b></font></button></td></tr>";
	}
	echo "</table>";
	echo "</form>";

function selectyesno($yesno){
	if ($yesno == "" || $yesno == "N") {
		echo "<opti"."on>N";
		echo "<opti"."on>Y";
	} else {
		echo "<opti"."on>Y";
		echo "<opti"."on>N";	
	}
	echo "</opti"."on></se"."lect>";
}
?>