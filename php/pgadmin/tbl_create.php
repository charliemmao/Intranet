<?php
/* $Id: tbl_create.php,v 1.3 2001/02/02 06:03:08 dwilson Exp $ */

require("header.inc.php");

if (isset($submit)) {
	if(!isset($query)) 
		unset($query);
	for ($i = 0; $i < count($field_name); $i++) {
		if (!empty($field_name[$i])) {
			$query .= "$cfgQuotes$field_name[$i]$cfgQuotes $field_type[$i]";
			if (!empty($field_length[$i])) {
				$query .= " (".stripslashes($field_length[$i]).")";
			}
			if (!empty($field_attribute[$i])) {
				$query .= " $field_attribute[$i]";
			}
			if (!empty($field_default[$i])) {
				$query .= " DEFAULT ".stripslashes($field_default[$i]);
			}
			if (!empty($fk_field[$i])) {
				$query .= " REFERENCES ".stripslashes(stripslashes($fk_field[$i]));
			}
			if (!eregi("SERIAL", $field_type[$i])) {
				$query .= " $field_null[$i]";
				// $query .= " $field_extra[$i]";
			}
			$query .= ", \n";
		}
	}
	$query = ereg_replace(", \n$", "", $query);

	if(!isset($primary)) 
		$primary = "";
	for ($i=0;$i<count($field_primary);$i++) {
		$j = $field_primary[$i];
		$primary .= "$cfgQuotes$field_name[$j]$cfgQuotes, ";
	}
	$primary = ereg_replace(", $", "", $primary);
	if (count($field_primary) > 0) {
		$primary = ",\n PRIMARY KEY ($primary)";
	}

	if(!isset($index)) 
		$index = "";
	for ($i = 0; $i < count($field_index); $i++) {
		$j = $field_index[$i];
		$index .= "CREATE INDEX $cfgQuotes$table" . "_$field_name[$j]" . "_key$cfgQuotes ON $cfgQuotes$table$cfgQuotes($cfgQuotes$field_name[$j]$cfgQuotes); \n";
	}
	$index = ereg_replace(", $", "", $index);

	if(!isset($unique)) 
		$unique = "";
	for ($i = 0; $i < count($field_unique); $i++) {
		$j = $field_unique[$i];
		$unique .= "$cfgQuotes$field_name[$j]$cfgQuotes, ";
	}
	$unique = ereg_replace(", $", "", $unique);
	if (count($field_unique) > 0) {
		$unique = ", UNIQUE ($unique)";
	}
	$query_keys = $primary.$unique;
	$query_keys = ereg_replace(", $", "", $query_keys);

	$sql_query = "CREATE TABLE $cfgQuotes$table$cfgQuotes (\n".$query." ".$query_keys.");\n";
	if (!empty($index)) {
		$sql_query .= $index;
	}
	@pg_exec($link, pre_query($sql_query)) or pg_die(pg_errormessage(), $sql_query, __FILE__, __LINE__);

	$reload = true;
	$message = "$strTable $table $strHasBeenCreated";
	include("tbl_properties.php");
	exit;
} else  {
	$action = "tbl_create.php";
	include("tbl_properties.inc.php");
}

include ("footer.inc.php");
?>
