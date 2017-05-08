<html>

<head>
<title>MySQL DB Administration</title>
<LINK REL="StyleSheet" HREF="../style/style_admin.css" TYPE="text/css">
</head>

<body background="rlaemb.JPG" leftmargin="10">

<?php
## control page access
include("admin_access.inc");
include('rla_functions.inc');
include("connet_root_once.inc");
include("find_admin_default.inc");

echo "<center><a id=top><b>MySQL Query</b></a>";
echo "&nbsp;[<a href=\"$PHP_SELF\">Refresh</a>]</center><hr>";

$border = "0 cellspacing=\"0\" cellpadding=\"0\"";
$wid = 200;
###########################################
#	Form Builder: show db, table and fields
###########################################
include("find_user_db.inc");
	if (!$usedb) {
		$usedb = $user_db_list[0];
	}
//DB list $user_db_list[]
	echo "<form method=post>";
	echo "<table border=$border>";
	echo "<tr><th align=left>User DB on Server (".count($user_db_list).")</td></tr>";
	echo "<tr>";
	echo "<td><select name=usedb>";
	for ($i=0; $i<count($user_db_list); $i++) {
    	if ($user_db_list[$i] == $usedb) {
			echo "<option selected>".$user_db_list[$i];
       } else {
			echo "<option>".$user_db_list[$i];
		}
	}
	echo "</option></select></td>";
	echo "</tr>";

//Table List: $tablelist[]
	include("find_table_in_db.inc");
	$newdb = 1;
	for ($i=0; $i<count($tablelist); $i++) {
    	if ($tablelist[$i] == $usetable) {
			$newdb = 0;
			break;
		}
	}
	if ($newdb == 1) {
		$usetable = $tablelist[0];
	}
	echo "<tr><th align=left>Table in $usedb (".count($tablelist).")</th></tr>";
	echo "<tr><td><select name=usetable>";
	for ($i=0; $i<count($tablelist); $i++) {
		$tab = $tablelist[$i];
		$rcdno = " (".$tablercdno[$i].")";
		$nofld = $tablefldnnum[$i];
		if ($keyexist[$i] == 1) {
			$tmp = " *";
		}
		$val = "$tab: $nofld$rcdno$tmp";
    	if ($tablelist[$i] == $usetable) {
			echo "<option value=\"$tab\" selected>".$val;
       } else {
			echo "<option value=\"$tab\">".$val;
		}
	}
	echo "</option></select></td>";
	echo "</tr>";

//Field Properties LIST: $nofields $fldproplist[$i][0] name:type:len:flag
	include("find_field_in_table_db.inc");
/*
	echo "<tr><th align=left>Field in $usetable ($nofields)</th></tr>";
	echo "<tr><td><select name=fldlist>";
	for ($i=0; $i<$nofields; $i++) {
		echo "<option>".$fldproplist[$i][0];
	}
	echo "</option></select></td>";
	echo "</tr>";
//*/

	echo "<tr><td>&nbsp;</td></tr>";
	echo "<tr><th><button type=\"submit\" name=\"changedbortable\">
		<b>Change DB/Table</b></button></th></tr>";
	echo "</table>";
	echo "</form>";
	navigate();

###########################################
#	Form Builder: query db.table and fields
###########################################
	include("find_rcd_no.inc");
	echo "<form method=post action=\"admin_mysql_sys_main.php\" target=\"main\">";
	echo "<input type=hidden name=\"usedb\" value=\"$usedb\">";
	echo "<input type=hidden name=\"usetable\" value=\"$usetable\">";
	echo "<input type=hidden name=\"frmleft\" value=\"frmleft\">";
	echo "<table border=$border>";
	echo "<tr><th align=left colspan=5>Query (Record: $norcd)</td></tr>";
	$tcol = 6;
	echo "<tr class=td1><th>Field ($nofields)</th><th>List</th><th>S1</th><th>S2</th><th>S3</th><th>E</th></tr>";
	for ($i=0; $i<$nofields; $i++) {
		$tmp = $fldproplist[$i][0];
		$tmp1 = ereg_replace("_", " ", $tmp);
		$tmp1 = ucwords($tmp1);
		if ((int)($i/2)*2 == $i) {
			$sl = "tr1";
		} else {
			$sl = "tr2";
		}
       $statuscontext = $fldproplist[$i][1] ;
		include("self_status.inc");	
		if ($fldproplist[$i][2] || $fldproplist[$i][3]) {
			$status .= " class=nbb";
		}
		echo "<tr class=$sl><td><a $status>$tmp1</a></td>";
		echo "<th><input type=\"checkbox\" name=\"ckfldlist$i\" checked value=\"$tmp\"></th>";
		echo "<th><input type=\"radio\" name=\"rdofldsort0\" value=\"$tmp\"></th>";
		echo "<th><input type=\"radio\" name=\"rdofldsort1\" value=\"$tmp\"></th>";
		echo "<th><input type=\"radio\" name=\"rdofldsort2\" value=\"$tmp\"></th>";
		echo "<th><input type=\"checkbox\" name=\"ckeditlist$i\" value=\"$tmp\"></th>";
		echo "<th></th></tr>";
	}
	$tc = $tcol - 1;
	echo "<tr class=td1><td colspan=1><b>Sort</td><td colspan=$tc><select name=ascdec>
		<option value=\"0\">Ascending";
		if ($ascdec) {
			echo "<option value=\"DESC\" selected>Decending";
		} else {
			echo "<option value=\"DESC\">Decending";
		}
	echo "</option></select></b></td></tr>";
	echo "<tr><td>&nbsp;</td></tr>";
	echo "<tr><th colspan=$tcol><button type=\"submit\" name=\"querytable\">
		<b>Query</b></button></th></tr>";
	echo "</table>";

	echo "</form>";
	lastnavigate();
	
	echo "<a id=end>&nbsp;</a>";

function navigate(){
	echo "<center>[<a href=#top>Back to top</a>]&nbsp;[<a href=#end>To End</a>]</center><hr>";
}

function lastnavigate(){
	echo "<center>[<a href=#top>Back to top</a>]</center><hr>";
}
?>
<!--
            $fldproplist[$i][0] = name;
            $fldproplist[$i][1] = type;
            $fldproplist[$i][2] = max_length;
            $fldproplist[$i][3] = primary_key;
            $fldproplist[$i][4] = unique_key;
            $fldproplist[$i][5] = multiple_key;
            $fldproplist[$i][6] = not_null; //<
            $fldproplist[$i][7] = zerofill; //<
            $fldproplist[$i][8] = numeric; //<
            $fldproplist[$i][9] = unsigned; //<
            $fldproplist[$i][10] = blob; //<
            $fldproplist[$i][11] = table; //<
-->

</body>
