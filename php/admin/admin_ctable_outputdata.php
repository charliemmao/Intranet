<html>

<head>
<title>New Tables Initialisation</title>
</head>

<body bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG" leftmargin="50">

<?php
## control page access
include("admin_access.inc");
include('rla_functions.inc');
echo "<a id=top><h1 align=center>MySQL: Create Table & Input DATA</h1></a>";

echo "<p align=center><a href=\"".$PHP_SELF."$admininfo\">[Refresh]</a>";
echo "<a href=\"/$phpdir/adminctl_top.php$admininfo\">[Admin Main Page]</a>";
echo "<hr>";

include("connet_root_once.inc");
$sqlstr = trim($sqlstr);
echo "<h2>Output Data From One Table By SQL</h2>";
if ($dataoutput && $sqlstr && $rcdaction != "SELECT DB or TABLE") {
	$sqlstr = stripslashes($sqlstr);
	echo "$sqlstr<br>$flds<br>";
	$sql = $sqlstr;
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	echo "<br>$no<br>";
   $fld_key   =   explode(", ", $fldstr);
	while ($myrow = mysql_fetch_array($result)) {
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
  	$result = mysql_list_dbs();
	$no = mysql_num_rows ($result);
	$i = 0;
	$j = 0;
	while ($i < mysql_num_rows ($result)) {
		$tmp = mysql_tablename ($result, $i);
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
  	$result = mysql_list_tables($dblist);
	$no = mysql_num_rows ($result);
	$i = 0;
	while ($i < mysql_num_rows ($result)) {
    	$table0[$i] = mysql_tablename ($result, $i);
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
	$result = mysql_list_fields($dblist, $tablelist);
	$fields = mysql_num_fields($result);
	$fld = "";
	$where = "";
	$flds = "";
    if ($fields) {
        echo '<tr><th>No<th align=left>Field Name</th><th align=left>Type</th>
        	<th align=left>Length</th><th align=left>Flags</th></tr>'; 
        $i = 0;
        while ($i < $fields) {
            $name  = mysql_field_name  ($result, $i);
            $type  = mysql_field_type  ($result, $i);
            $len   = mysql_field_len   ($result, $i);
            $flags = mysql_field_flags ($result, $i);
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
		<input type=\"submit\" value=\"Output Data\" name=\"dataoutput\"></td></tr>";
	echo "</table>";
	echo "</form>";

echo "<hr>";
echo "<h2>Create New Table Or Insert/Delete/Update Record</h2>";
if ($createnewtable) {
	$tabdefination= stripslashes($tabdefination);
	$sql = $tabdefination;
	echo "$sql<br>";
	$result = mysql_query($sql);
	include("err_msg.inc");
}

echo "<form method=post>";
echo "<textarea name=tabdefination cols=120 rows=10>$tabdefination</textarea><br>";
echo "<input type=submit name=createnewtable value=\"Create New Table\">";
echo "</form>";

backtotop();
function backtotop(){
echo "<br><hr><a href=#top>Back to top</a><a id=end></a><br><br><br>";
}

?>
</body>
