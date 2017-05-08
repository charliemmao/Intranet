<?php
/* $Id: tbl_alter_drop.php,v 1.3 2001/04/23 05:41:39 dwilson Exp $ */

include("header.inc.php");

if (isset($btnDrop) && $btnDrop != $strYes) {
	if (file_exists("./$goto")) {
		include(preg_replace('/\.\.*/', '.', $goto));	
	} else {
		Header("Location: $goto");
	}
	exit;
}

if (!$cfgConfirm) {
   $btnDrop = $strYes;
}

$sql_query = "DROP FIELD $field OF TABLE $table";
$is_drop_sql_query = true;

if ($is_drop_sql_query && !isset($btnDrop)) {
   include("header.inc.php");
   echo $strDoYouReally.urldecode(stripslashes(nl2br($sql_query)))."?<br>";
   ?>
   <form action="tbl_alter_drop.php" method="post" enctype="application/x-www-form-urlencoded">
   <!--input type="hidden" name="sql_query" value="<?php echo urldecode(stripslashes($sql_query)); ?>"-->
   <input type="hidden" name="server" value="<?php echo $server ?>">
   <input type="hidden" name="db" value="<?php echo $db ?>">
   <input type="hidden" name="zero_rows" value="<?php echo $zero_rows;?>">   
   <input type="hidden" name="table" value="<?php echo $table;?>">   
   <input type="hidden" name="goto" value="<?php echo $goto;?>">
   <input type="hidden" name="field" value="<?php echo $field;?>">
   <input type="hidden" name="reload" value="<?php echo $reload;?>">   
   <input type="hidden" name="rel_type" value="<?php echo $rel_type;?>">   
   <input type="Submit" name="btnDrop" value="<?php echo $strYes; ?>">
   <input type="Submit" name="btnDrop" value="<?php echo $strNo; ?>">
   </form>
   <?php 
} else {
	$sql_get_field = "
		SELECT 
			a.attnum,
			a.attname AS field, 
			t.typname AS type, 
			a.attlen AS length,
			a.atttypmod AS lengthvar,
			a.attnotnull AS notnull
		FROM 
			pg_class c, 
			pg_attribute a, 
			pg_type t
		WHERE 
			c.relname = '$table'
			and a.attnum > 0
			and a.attrelid = c.oid
			and a.atttypid = t.oid
		ORDER BY
			attnum
	";

	if (!$result = pg_exec($link, pre_query($sql_get_field))) {
		pg_die(pg_errormessage(), $sql_get_field, __FILE__, __LINE__);
	}
	
	$num_fields = pg_numrows($result);
	
	for ($iFields = 0; $iFields < $num_fields; $iFields++) {
		$strCurField = pg_result($result, $iFields, "field");
		if ($strCurField != $field) {
			$strFieldList .= "$cfgQuotes$strCurField$cfgQuotes, ";
		}
	}
	
	$strFieldList = ereg_replace(", $", "", $strFieldList);
	
	$new_table = "$table" . "_" . date("U");

	// doing some DOS-CRLF magic...
	$client=getenv("HTTP_USER_AGENT");
	if (ereg('[^(]*\((.*)\)[^)]*',$client,$regs)) {
		$os = $regs[1];
		// this looks better under WinX
		if (eregi("Win",$os)) $crlf="\r\n";
	}
	
	$drop_field = $field;
	$create_table = get_table_def($link, $table, $crlf);
	
	unset($sql_query);
	
	$exec_query = "CREATE TABLE $cfgQuotes$new_table$cfgQuotes AS SELECT $strFieldList FROM $cfgQuotes$table$cfgQuotes";
	pg_exec($link, pre_query($exec_query)) or pg_die(pg_errormessage(), $exec_query, __FILE__, __LINE__);
	$sql_query .= $exec_query . ";\n";
	$exec_query = "DROP TABLE $cfgQuotes$table$cfgQuotes";
	pg_exec($link, pre_query($exec_query)) or pg_die(pg_errormessage(), $exec_query, __FILE__, __LINE__);
	$sql_query .= $exec_query . ";\n";
	$exec_query = $create_table;
	pg_exec($link, pre_query($exec_query)) or pg_die(pg_errormessage(), $exec_query, __FILE__, __LINE__);
	$sql_query .= $exec_query;
	$exec_query = "INSERT INTO $cfgQuotes$table$cfgQuotes SELECT * FROM $cfgQuotes$new_table$cfgQuotes";
	pg_exec($link, pre_query($exec_query)) or pg_die(pg_errormessage(), $exec_query, __FILE__, __LINE__);
	$sql_query .= $exec_query . ";\n";
	$exec_query = "DROP TABLE $cfgQuotes$new_table$cfgQuotes";
	pg_exec($link, pre_query($exec_query)) or pg_die(pg_errormessage(), $exec_query, __FILE__, __LINE__);
	$sql_query .= $exec_query . ";\n";
	
	$message = "$strTable $table $strHasBeenAltered";
	include("tbl_properties.php");
	exit;
}
include ("footer.inc.php");
?>
