<html>
<head>
<!--
	postgresql installation: program=/usr/lib/pgsql; data=var/lib/pgsql/data/
-->
<LINK REL="StyleSheet" HREF="pgstyle.css" TYPE="text/css">
<title>New Tables Initialisation</title>
</head>
<script language=javascript>
function copydatatype() {
	if (dbform.tochange.value == "n") {
		return false;
	}
	val = dbform.sqlstr.value;
	n = dbform.datatype.value;
	dbform.sqlstr.value = val + n + ", ";
	return true;
}

function copyquerytype() {
	if (dbform.tochange.value == "n") {
		return false;
	}
	val = dbform.sqlstr.value;
	n = dbform.querytype.value;
	dbform.sqlstr.value = val + n + " ";
	dbform.tochange.value = "y";
	return true;
}

function changeyn() {
	//alert("h");
	if (dbform.tochange.value == "y") {
		dbform.tochange.value = "n";
	} else {
		dbform.tochange.value = "y";
	} 
}
</script>
<body bgcolor="#bbbbbb" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" leftmargin="50">
<?php
## control page access	background="rlaemb.JPG" 
include("admin_access.inc");
include('rla_functions.inc');
$backforward = "<p align=center><a href=\"".$PHP_SELF."$admininfo\"><b>[Refresh]</b></a>".
	"<a href=\"/$phpdir/adminctl_top.php$admininfo\"><b>[Admin Main Page]</b></a><hr>";

echo "<a id=top><h1 align=center>PG: Create Table & Input DATA</h1></a>";
echo $backforward;

###########connect to database
$user="root";
$pgdb="test";
$table="alltypes";
include("pg_conn.inc");

##################################################
$nl = chr(13).chr(10);
$sqlstr = trim($sqlstr);
echo "<h2>Manipulation of PG's DB/Table/Record</h2>";
if ($execsubmit) {
	$t=explode(";",$sqlstr);
	$sqlstr = $t[0].";";
	$sqlstr= stripslashes($sqlstr);
	$sqlstr= ereg_replace("$nl"," ",$sqlstr);
	$sqlstr= ereg_replace("test=>","",$sqlstr);
	$sqlstr= ereg_replace("test->","",$sqlstr);
	$sqlstr= ereg_replace("test\(\>","",$sqlstr);
	$sqlstr= ereg_replace("     "," ",$sqlstr);
	$sqlstr= ereg_replace("     "," ",$sqlstr);
	$sqlstr= ereg_replace("   "," ",$sqlstr);
	$sqlstr= ereg_replace("   "," ",$sqlstr);
	$sqlstr= ereg_replace("  "," ",$sqlstr);
	$sqlstr= ereg_replace("  "," ",$sqlstr);
	//$sqlstr = pickprintablechr($sqlstr, 32, 255);
	$sql = $sqlstr;
	if ($sql && $tochange == "y") {
		$result = pg_exec("$sql");
		include("pg_errmsg.inc");
	
		//find table name
		include("pg_find_tabnamefrom_sql.inc");
		$table = find_pg_tablename($sql);
		echo "Query 1 ($table): $sql<br><br>";
		if ($table != "no") {
			if ($table != "select") {
				$sql = "select * from $table"; //oid
				echo "Query 2: $sql<br>";
				$result = pg_exec("$sql");
				include("pg_errmsg.inc");
			}
			include("pg_display_res_in_table.inc");
		}
	} elseif ($tochange == "n") {
	} else {
		echo "Query is empty";
	}
	echo $backforward;
}

##################################################
echo "<form method=post name=dbform><table>";
echo "<tr><td>";
echo "<textarea name=sqlstr cols=120 rows=4>$sqlstr</textarea></td></tr>";
echo "<tr><th><button type=submit name=execsubmit><b>SUBMIT</b></button></th></tr>";
echo "</table>";
echo "<table>";	//2 column
include("pg_action_type.inc");
include("pg_data_type.inc");
include("pg_support_variables.inc");
include("pg_query_example.inc");
include("pg_constraints.inc");
include("pg_psql_query_buffer_commands.inc");
include("pg_psql_listing_commands.inc");
echo "</table>";
echo "</form>";
echo $backforward;

##################################################
if ($texttransform) {
	$sp = "";
	for ($i=0; $i<4; $i++) {
		$sp = $sp."&nbsp;";
	}
	$tr="tr";
	$th1="th class=td1";
	$th2="/th";
	$td="td";
	$transformname = trim($transformname);
	$transformname = ereg_replace("  ", " ", $transformname);
	$transformname = ereg_replace("  ", " ", $transformname);
	$transformname = ereg_replace("  ", " ", $transformname);
	$transformname = ereg_replace(" ", "_", $transformname);
	$transformname = strtolower($transformname);
	$newtext = "pg_$transformname.inc\ninclude(\"pg_$transformname.inc\");\n";
	$newtext = "$newtext<$tr><$th1>$transformlable<$th2>\n";
	$tmp = trim($texttochange);
	$ta = explode("$nl",$tmp);
	sort($ta);
	if ($tranformtype == "select") {
		$newtext = $newtext."$sp<$td><select name=\"$transformname\">\n";
		for ($i=0; $i<count($ta); $i++) {
			$t1 = trim($ta[$i]);
			if (trim($t1)) {
				$newtext = $newtext."$sp$sp<option>$t1\n";
			}
		}
		$newtext = $newtext."$sp</option></select><$th2>\n</$tr>\n";
	}
	echo "<h2>New Text</h2>";
	echo "<form method=post>";
	echo "<textarea name=gg cols=120 rows=10>$newtext</textarea>";
	echo "</form>";
	echo $backforward;
}
echo "<h2>Text to be transformed</h2>";
echo "<form method=post>";
echo "<table>";
echo "<tr><td colspan=1><textarea name=texttochange cols=120 rows=10>$texttochange</textarea></td></tr>"; 
echo "<tr><th colspan=1><button type=submit name=texttransform><b>Transform</b></button></th></tr>";
echo "<tr><td><b>Transform Type </b>
	<select name=tranformtype>
	<option>select
	</option></select>
	<b>Lable </b><input type=text name=transformlable value=\"$transformlable\">
	<b>Name </b><input type=text name=transformname value=\"$transformname\">
	</td></tr>";
echo "</table>";
echo "</form>";
echo $backforward;
##################################################

exit;
echo "<h2>Output Data From One Table By SQL</h2>";
if ($dataoutput && $sqlstr && $rcdaction != "SELECT DB or TABLE") {
	$sqlstr = stripslashes($sqlstr);
	echo "$sqlstr<br>$flds<br>";
	$sql = $sqlstr;
	$result = pg_mysqlquery($sql);
	include("pg_errmsg.inc");
	$no = pg_mysqlnum_rows($result);
	echo "<br>$no<br>";
   $fld_key   =   explode(", ", $fldstr);
	while ($myrow = pg_mysqlfetch_array($result)) {
    	$m=(int)1.5;
    	$k=0;
    	$rcd = "";
   	 	while (list($key,$val) = each($myrow)) {
        	$m +=1;
        	$j = 2*(int)($m/2);
        	if ($m == $j) {
                $fld_value[$k]   =   $val;
                $rcd = $rcd."'".$fld_value[$k]."', ";
                $k = $k+1;
        	}
    	}
    	$rcd = substr($rcd, 0, strlen($rcd)-2);
    	if ($rcdaction != "UPDATE") {
    		echo "$rcdaction $dblist0.$tablelist0 VALUES($rcd);<br>";
    	} else {
    		$rcd = "";
    		for ($kk=0; $kk<count($fld_key); $kk++) {
    			$rcd  = $rcd .$fld_key[$kk]."='".$fld_value[$kk]."', ";
    		}
    		$rcd = substr($rcd, 0, strlen($rcd)-2);
    		echo "$rcdaction $dblist0.$tablelist0 SET $rcd WHERE ".$fld_key[0]."='".$fld_value[0]."';<br>";
    	}
	}
	echo "<hr>";
}

	echo "<form method=post>";
	echo "<table border=0>";
	//DB list
  	$result = pg_mysqllist_dbs();
	$no = pg_mysqlnum_rows ($result);
	$i = 0;
	$j = 0;
	while ($i < pg_mysqlnum_rows ($result)) {
		$tmp = pg_mysqltablename ($result, $i);
    	if ($tmp != 'test'){
    		$db[$j] = $tmp;
    		$j++;
    	}
    	$i++;
    }
    $i = $j;
	if (!$dblist) {
		$dblist = "rlafinance";
	}
	echo "<input type=hidden name=predb value=$dblist>";
	echo "<tr><th align=left colspan=2>Database LIST ($no)</th><td>";
	echo "<select name=dblist>";
	for ($j=0; $j<$i; $j++) {
    	if ($db[$j] == $dblist) {
			echo "<option selected>".$db[$j];
       } else {
			echo "<option>".$db[$j];
		}
	}
	echo "</option></select></td></tr>";

	//Table List
  	$result = pg_mysqllist_tables($dblist);
	$no = pg_mysqlnum_rows ($result);
	$i = 0;
	while ($i < pg_mysqlnum_rows ($result)) {
    	$table0[$i] = pg_mysqltablename ($result, $i);
    	$i++;
    }
	if (!$tablelist || $predb != $dblist) {
		$tablelist = $table0[0];
	}
	echo "<tr><th align=left colspan=2>Table LIST ($no)</th><td>";
	echo "<select name=tablelist>";
	for ($j=0; $j<$i; $j++) {
    	if ($table0[$j] == $tablelist) {
			echo "<option selected>".$table0[$j];
       } else {
			echo "<option>".$table0[$j];
		}
	}
	echo "</option></select></td></tr>";
	
	echo "<tr><th align=left colspan=2>Action</th><td>";
	echo "<select name=rcdaction>";
	$rcdo[0] = "UPDATE";
	$rcdo[1] = "INSERT INTO";
	$rcdo[2] = "DELETE FROM";
	$rcdo[3] = "SELECT DB or TABLE";
  	for ($i=0; $i<4; $i++) {
    	if ($rcdo[$i] == $rcdaction) {
			echo "<option selected>".$rcdo[$i];
       } else {
			echo "<option>".$rcdo[$i];
		}
  	}
	echo "</option></select></td></tr>";

	//Field LIST
	$result = pg_mysqllist_fields($dblist, $tablelist);
	$fields = pg_mysqlnum_fields($result);
	$fld = "";
	$where = "";
	$flds = "";
    if ($fields) {
        echo '<tr><th>No<th align=left>Field Name</th><th align=left>Type</th>
        	<th align=left>Length</th><th align=left>Flags</th></tr>'; 
        $i = 0;
        while ($i < $fields) {
            $name  = pg_mysqlfield_name  ($result, $i);
            $type  = pg_mysqlfield_type  ($result, $i);
            $len   = pg_mysqlfield_len   ($result, $i);
            $flags = pg_mysqlfield_flags ($result, $i);
            $fld = $fld."$name, ";
            $flds = $flds."$"."$name, ";
            $where = $where."$name!='' and ";
            
            $str_w = $str_w.$name.' ';
            if ($len <= 4 && strtolower($type) == 'string') {
            		$str_w = $str_w.'CHAR('.$len.') ';
            } elseif ($len > 4 && strtolower($type) == 'string') {
            		$str_w = $str_w.'VARCHAR('.$len.') ';
            } else {
            		$str_w = $str_w.$type.' ';
            }
            if (strtolower($flags) == "not_null") {
            		$flags	=	"NOT NULL";
            } elseif (strtolower($flags) == "not_null primary_key") {
            		$flags	=	"NOT NULL";
            } elseif(strtolower($flags) == "not_null") {
            		$flags	=	"NOT NULL";
            }
            
            if ($i < $fields-1){
            		$str_w = $str_w.$flags.",";
            } else {
            		$str_w = $str_w.$flags.");";
            }
            $i++;
            echo "<tr><th>$i</th><td>$name</td><td>$type</td><td>$len</td><td>$flags</td></tr>";
        }
	}
   $fld = substr($fld, 0, strlen($fld)-2);
   $flds = substr($flds, 0, strlen($flds)-2);
   $where = substr($where, 0, strlen($where)-5);
	$sqlstr = "SELECT $fld FROM $dblist.$tablelist WHERE $where ORDER BY DESC";
	echo "<tr><td colspan=5><textarea name=sqlstr cols=80 rows=5>$sqlstr</textarea>";
	echo "<input type=hidden name=flds value=\"$flds\"></td></tr>";
	echo "<input type=hidden name=fldstr value=\"$fld\"></td></tr>";
	echo "<input type=hidden name=dblist0 value=\"$dblist\"></td></tr>";
	echo "<input type=hidden name=tablelist0 value=\"$tablelist\"></td></tr>";

	echo "<tr><td colspan=5 align=middle>
		<button type=\"submit\" name=\"dataoutput\"><b>Output Data</b></button></td></tr>";
	echo "</table>";
	echo "</form>";

echo "<hr>";

backtotop();
function backtotop(){
echo "<br><hr><a href=#top>Back to top</a><a id=end></a><br><br><br>";
}

?>
</body>
