<?php
/* $Id: tbl_alter.php,v 1.2 2001/02/02 06:03:08 dwilson Exp $ */

include("header.inc.php");

if (isset($submit)) {
	if(!isset($query)) $query = "";
	//   $query .= " $field_orig[0] $field_name[0] $field_type[0] ";
	$query .= " $cfgQuotes$field_orig[0]$cfgQuotes TO $cfgQuotes$field_name[0]$cfgQuotes";
	
/*	This has not yet been implemented in Postgres
	if ($field_length[0] != "") {
		$query .= "($field_length[0]) ";
	}
	if ($field_attribute[0] != "") {
		$query .= "$field_attribute[0] ";
	}
	if ($field_default[0] != "") {
		$query .= "DEFAULT $field_default[0] ";
	}
*/

	$query = stripslashes($query);

	if ($field_orig[0] != $field_name[0]) {
		$rename_query = "ALTER TABLE $cfgQuotes$table$cfgQuotes RENAME $query";
	}
	
	if ($field_default[0] != "") {
		$default_query = "ALTER TABLE $cfgQuotes$table$cfgQuotes ALTER $cfgQuotes$field_name[0]$cfgQuotes SET DEFAULT $field_default[0]";
	} else {
		$default_query = "ALTER TABLE $cfgQuotes$table$cfgQuotes ALTER $cfgQuotes$field_name[0]$cfgQuotes DROP DEFAULT";
	}

	$default_query = stripslashes($default_query);
	
	$sql_query = $rename_query;
	if (!empty($sql_query)) {
		$sql_query .= ";";
	}
	$sql_query .= $default_query;
	
	$result = @pg_exec($link, pre_query($sql_query));
	if (!$result) {
		pg_die(pg_errormessage(), $sql_query, __FILE__, __LINE__);
	} else {
		$message = "$strTable $table $strHasBeenAltered";
		include("tbl_properties.php");
		exit;
	}
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
			and a.attname LIKE '$field'
		ORDER BY
			attnum
	";
	$result = pg_exec($link, pre_query($sql_get_field));
	if (!$result) {
		pg_die(pg_errormessage(), $sql_get_field, __FILE__, __LINE__);
	}
	$num_fields = pg_numrows($result);
	$action = "tbl_alter.php";
   
	$field_num = pg_result($result, 0, "attnum");
	$sql_get_default = "
		SELECT d.adsrc AS rowdefault
		FROM pg_attrdef d, pg_class c 
		WHERE 
			c.relname = '$table' AND 
			c.oid = d.adrelid AND
			d.adnum = $field_num
		";
	if (!$def_res = @pg_exec($link, pre_query($sql_get_default))) {
		pg_die(pg_errormessage(), $sql_get_default, __FILE__, __LINE__);
		$rowdefault = "";
	} else {
		$rowdefault = @pg_result($def_res, 0, "rowdefault");
	}
   
	include("tbl_properties.inc.php");
}

include ("footer.inc.php");
?>
