<html>

<head>
<title></title>
</head>

<body bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG" leftmargin="50">

<?php
## control page access
include("admin_access.inc");
include('rla_functions.inc');

echo "<a id=top><h1 align=center>Setup Privilege Individually</h1></a>";
echo "<p align=center><a href=\"$PHP_SELF$admininfo\">[Refresh]</a>";
echo "<a href=\"/$phpdir/admin_privilege_batch.php$admininfo\">[Batch Setup]</a>";
echo "<a href=\"/$phpdir/adminctl_top.php$admininfo\">[Admin Main Page]</a>";
echo "<hr>";

##	list tables in MySQL and action selection
echo "<form name=seltable action=\"$PHP_SELF\">";
	echo "<table border=0>";
	echo "<tr><th>Tables in MySQL DB</th>";
	echo "<td><select name=mysqltable>";

  	include("connet_root_once.inc");
  	/*
  	$dbname = "mysql";
  	$result = mysql_list_tables($dbname);

	if (mysql_num_rows ($result)) {
		if ($mysqltable == "") {
			$mysqltable = "user";
		}
		$i = 0;
		while ($i < mysql_num_rows ($result)) {
    		$tblnames = mysql_tablename ($result, $i);
    		if ($mysqltable == $tblnames) {
  				echo "<option selected>$tblnames";
  			} else {
  				echo "<option>$tblnames";
  			}
       	$i++;
  		}
	}
	*/
	$i = 0;
	$mysqltablelist[$i]= "user"; $i++;
	$mysqltablelist[$i]= "db"; $i++;
	$mysqltablelist[$i]= "tables_priv"; $i++;
	$mysqltablelist[$i]= "columns_priv"; $i++;
	//$mysqltablelist[$i]= "host"; $i++;
	//$mysqltablelist[$i]= "func"; $i++;
	if ($mysqltable == "") {
		$mysqltable = $mysqltablelist[0];
	}
	for ($j=0; $j<$i; $j++) {
    	if ($mysqltable == $mysqltablelist[$j]) {
  			echo "<option selected>$mysqltablelist[$j]";
  		} else {
  			echo "<option>$mysqltablelist[$j]";
  		}
  	}
	echo "</option></select></td>";
	
	echo "<td rowspan=2 align=middle>";
	if (getenv("server_addr") == "127.0.0.1") {
		echo "<input type=\"submit\" name=addmod value=GO></td></tr>";
	} else {
		echo "<button type=\"submit\" name=addmod><font color=#0000ff size=3><b>GO</b></font></button></td></tr>";
	}
	
	echo "<tr><th align=left>Actions</th>";
	echo "<td><select name=tblaction>";
	$actlist[0] = "Add New Record";
	$actlist[1] = "Modify Record";
	if ($tblaction == "") {
		$tblaction = $actlist[0];
	}
	for ($i=0; $i<2; $i++) {
		if ($tblaction == $actlist[$i]) {
  			echo "<option selected>$actlist[$i]";
  		} else {
  			echo "<option>$actlist[$i]";
  		}
	}
	echo "</option></select></td></tr>";
	echo "</table>";
echo "</form>";
echo "<hr>";

##	build a form for add new record
//$mysqltable
$b1 = "\"<font color=#0000ff>";
$b2 = "</font>\"";
if ($tblaction == "Add New Record"  && !$addmodrcd) {
	echo "<h2>Add new record to Table $b1$mysqltable$b2.</h2>";
	include("mysqltableform.inc");
}
if ($tblaction == "Modify Record") {
	echo "<h2>Modify record in Table $b1$mysqltable$b2.</h2>";
	if ($mysqltable == "user") {
		$fld = "User";
		$sort = "User";
		//$desc = "DESC";
	} elseif ($mysqltable == "db") {
		$fld = "CONCAT(User, '__', Db)";
		$sort = "User";
	} elseif ($mysqltable == "tables_priv") {
		$fld = "CONCAT(User, '__', Db, '__', Table_name)";
		$sort = "User";
	} elseif ($mysqltable == "columns_priv") {
		$fld = "CONCAT(User, '__', Db, '__', Table_name, '__', Column_name)";
		$sort = "User";
	} elseif ($mysqltable == "host") {
		$fld = "CONCAT(Host, '__', Db)";
		$sort = "Host";
	} elseif ($mysqltable == "func") {
		$fld = "name";
		$sort = "name";
	}
	
	$sql = "SELECT $fld as field FROM mysql.$mysqltable order by $sort $desc;";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	echo "<form name=modrecord action=\"$PHP_SELF\">";
	echo "<input type=hidden name=mysqltable value=\"$mysqltable\">";
	echo "<input type=hidden name=tblaction value=\"$tblaction\">";
	echo "<input type=hidden name=fld value=\"$fld\">";
	echo "<table border=0>";
	echo "<tr><th>There are $no records in Table $mysqltable</th>";
	echo "<td><select name=rcd>";
	for ($i=0; $i<$no; $i++) {
		list($field) = mysql_fetch_array($result);
		$first = substr($field, 0, 2);
		if ($first != "__") {
			if ($rcd == $field) {
				echo "<option selected>$field";
			} else {
				echo "<option>$field";
			}
		}
	}
	echo "</option></select></td>";
	if (getenv("server_addr") == "127.0.0.1") {
		echo "<td><input type=\"submit\" name=modrcd value=\"Modify This Record\"></td></tr>";
	} else {
		echo "<td><button type=\"submit\" name=modrcd>
		<font size=3><b>Modify This Record</b></font></button></td></tr>";
	}
	echo "</table>";
	echo "</form>";
	echo "<hr>";

	if ($modrcd && !$addmodrcd) {
		if ($mysqltable == "user")  {
			echo "<h2>Modify record for $b1$rcd$b2 in Table $b1$mysqltable$b2.</h2>";
		} elseif ($mysqltable == "db") {
			$tmp = explode("__",$rcd);
			$User = $tmp[0];
			$Db = $tmp[1];
			echo "<h2>Modify record for USER: $b1$User$b2 DB: $b1$Db$b2 in Table $b1$mysqltable$b2.</h2>";
		} elseif ($mysqltable == "tables_priv") {
			$tmp = explode("__",$rcd);
			$User = $tmp[0];
			$Db = $tmp[1];
			$Table_name = $tmp[2];
			echo "<h2>Modify record for USER: $b1$User$b2 DB: $b1$Db$b2 Table: $b1$Table_name$b2 in Table $b1$mysqltable$b2.</h2>";
		} elseif ($mysqltable == "columns_priv") {
			$tmp = explode("__",$rcd);
			$User = $tmp[0];
			$Db = $tmp[1];
			$Table_name = $tmp[2];
			$Column_name = $tmp[3];
			echo "<h2>Modify record for USER: $b1$User$b2 DB: $b1$Db$b2 TABLE: $b1$Table_name$b2 "
				."COLUMN: $b1$Column_name$b2 in Table $b1$mysqltable$b2.</h2>";
		} elseif ($mysqltable == "host") {
			$tmp = explode("__",$rcd);
			$Host = $tmp[0];
			$Db = $tmp[1];
			echo "<h2>Modify record for HOST: $b1$Host$b2 DB: $b1$Db$b2 in Table $b1$mysqltable$b2.</h2>";
		} elseif ($mysqltable == "func")  {
			$name = $rcd;
			echo "<h2>Modify record for $b1$rcd$b2 in Table $b1$mysqltable$b2.</h2>";
		}
		include("mysqltableform.inc");
	}
}

if ($addmodrcd) {
	if ($mysqltable == "user") {
		if ($tblaction == "Add New Record") {
			$Password = $User."reslab";
			$sql = "INSERT INTO mysql.user values('$Host', '$User', 
			Password('$Password'), '$Select_priv', '$Insert_priv', 
			'$Update_priv', '$Delete_priv', 
			'$Create_priv', '$Drop_priv', '$Reload_priv', 
			'$Shutdown_priv', '$Process_priv', 
			'$File_priv', '$Grant_priv', 
			'$References_priv', '$Index_priv', 
			'$Alter_priv');";
			/*
			$sql = "INSERT INTO mysql.user SET Host='$Host', User='$User', 
			".'password=password("'.$Password.'")'.", Select_priv='$Select_priv', Insert_priv='$Insert_priv', 
			Update_priv='$Update_priv', Delete_priv='$Delete_priv', 
			Create_priv='$Create_priv', Drop_priv='$Drop_priv', Reload_priv='$Reload_priv', 
			Shutdown_priv='$Shutdown_priv', Process_priv='$Process_priv', 
			File_priv='$File_priv', Grant_priv='$Grant_priv', 
			References_priv='$References_priv', Index_priv='$Index_priv', 
			Alter_priv='$Alter_priv';";
			//*/
		} elseif ($tblaction == "Modify Record")  {
			$sql = "UPDATE mysql.user SET Host='$Host', 
			Select_priv='$Select_priv', Insert_priv='$Insert_priv', 
			Update_priv='$Update_priv', Delete_priv='$Delete_priv', 
			Create_priv='$Create_priv', Drop_priv='$Drop_priv', Reload_priv='$Reload_priv', 
			Shutdown_priv='$Shutdown_priv', Process_priv='$Process_priv', 
			File_priv='$File_priv', Grant_priv='$Grant_priv', 
			References_priv='$References_priv', Index_priv='$Index_priv', 
			Alter_priv='$Alter_priv' where User='$User';";
		}
	}

	if ($mysqltable == "db") {
		if ($tblaction == "Add New Record") {
			$sql = "INSERT INTO mysql.db SET Host='$Host', Db='$Db', User='$User', 
			Select_priv='$Select_priv', Insert_priv='$Insert_priv', 
			Update_priv='$Update_priv', Delete_priv='$Delete_priv', 
			Create_priv='$Create_priv', Drop_priv='$Drop_priv', Grant_priv='$Grant_priv', 
			References_priv='$References_priv', Index_priv='$Index_priv', 
			Alter_priv='$Alter_priv';";
		} elseif ($tblaction == "Modify Record")  {
			$sql = "UPDATE mysql.db SET Host='$Host',  
				Select_priv='$Select_priv', Insert_priv='$Insert_priv', 
				Update_priv='$Update_priv', Delete_priv='$Delete_priv', 
				Create_priv='$Create_priv', Drop_priv='$Drop_priv', Grant_priv='$Grant_priv', 
				References_priv='$References_priv', Index_priv='$Index_priv', 
				Alter_priv='$Alter_priv' WHERE Db='$Db' and User='$User';";
		}
	}

	if ($mysqltable == "tables_priv") {
		$Timestamp = date("YmdHis");
		$tmp = explode(".", $Table_name);
		$Db = $tmp[0];
		$Table_name = $tmp[1];
		$tmp = "";
		$j = count($Table_priv)-1;
		for ($i=0; $i<$j; $i++) {
			$tmp = $tmp.$Table_priv[$i].",";
		}
		$tmp = $tmp.$Table_priv[$j];
		$Table_priv = $tmp;
		
		$tmp = "";
		$j = count($Column_priv)-1;
		for ($i=0; $i<$j; $i++) {
			$tmp = $tmp.$Column_priv[$i].",";
		}
		$tmp = $tmp.$Column_priv[$j];
		$Column_priv = $tmp;
		
		if ($tblaction == "Add New Record") {
			$sql = "INSERT INTO mysql.tables_priv SET Host='$Host', Db='$Db', User='$User', 
				Table_name='$Table_name', Grantor='$Grantor', Timestamp='$Timestamp', 
				Table_priv='$Table_priv', Column_priv='$Column_priv';";
		} elseif ($tblaction == "Modify Record") {
			$sql = "UPDATE mysql.tables_priv SET Host='$Host', Grantor='$Grantor', Timestamp='$Timestamp', 
				Table_priv='$Table_priv', Column_priv='$Column_priv' WHERE 
				Db='$Db' and User='$User' and Table_name='$Table_name';";
		}
	}

	if ($mysqltable == "columns_priv") {
		$Timestamp = date("YmdHis");
		$tmp = explode(".", $Column_name);
		$Db = $tmp[0];
		$Table_name = $tmp[1];
		$Column_name = $tmp[2];
		$tmp = "";
		$j = count($Column_priv)-1;
		for ($i=0; $i<$j; $i++) {
			$tmp = $tmp.$Column_priv[$i].",";
		}
		$tmp = $tmp.$Column_priv[$j];
		$Column_priv = $tmp;
		if ($tblaction == "Add New Record") {
			$sql = "INSERT INTO mysql.columns_priv SET Host='$Host', Db='$Db', User='$User', 
				Table_name='$Table_name', Column_name='$Column_name', Timestamp='$Timestamp', 
				Column_priv='$Column_priv';";
		} elseif ($tblaction == "Modify Record")  {
			$sql = "UPDATE mysql.columns_priv SET Host='$Host', Timestamp='$Timestamp', 
				Column_priv='$Column_priv' WHERE 
				Db='$Db' and User='$User' and Table_name='$Table_name' and Column_name='$Column_name';"; 
		}
	}

	if ($mysqltable == "host") {
		if ($tblaction == "Add New Record") {
			$sql = "INSERT INTO mysql.host SET Host='$Host', Db='$Db', 
				Select_priv='$Select_priv', Insert_priv='$Insert_priv', 
				Update_priv='$Update_priv', Delete_priv='$Delete_priv', 
				Create_priv='$Create_priv', Drop_priv='$Drop_priv', Grant_priv='$Grant_priv', 
				References_priv='$References_priv', Index_priv='$Index_priv', 
				Alter_priv='$Alter_priv';";
		} elseif ($tblaction == "Modify Record")  {
			$sql = "UPDATE mysql.host SET Db='$Db', 
				Select_priv='$Select_priv', Insert_priv='$Insert_priv', 
				Update_priv='$Update_priv', Delete_priv='$Delete_priv', 
				Create_priv='$Create_priv', Drop_priv='$Drop_priv', Grant_priv='$Grant_priv', 
				References_priv='$References_priv', Index_priv='$Index_priv', 
				Alter_priv='$Alter_priv' WHERE Host='$Host';";
		}
	}

	if ($mysqltable == "func") {
		if ($tblaction == "Add New Record") {
			$sql = "INSERT INTO mysql.func SET name='$name', ret='$ret', dl='$dl', type='$type';";
		} elseif ($tblaction == "Modify Record")  {
			$sql = "UPDATE mysql.func SET ret='$ret', dl='$dl', type='$type' WHERE name='$name';";
		}
	}

	$result = mysql_query($sql);
	include("err_msg.inc");
	if ($result) {
		echo "<h1><font color=#0000ff>$tblaction</font> has been successful.</h1><br>";
	} else {
		echo "<h1><font color=#ff0000>$tblaction</font> has failed.</h1><br>";
	}
	echo "$sql<br>";
/*
echo "modrcd<br>";
echo "Table=$mysqltable<br>";
echo "Action=$tblaction<br>";
echo "Field=$fld<br>";
echo "Record=$rcd<br>";
*/
}

backtotop();
function backtotop(){
	echo "<br><hr><a href=#top>Back to top</a><a id=end></a><br><br><br>";
}

?>
</body>
