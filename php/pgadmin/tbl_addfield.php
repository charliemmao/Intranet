<?php
/* $Id: tbl_addfield.php,v 1.2 2001/02/02 06:03:08 dwilson Exp $ */

include("header.inc.php");

if (isset($submit)) {
	for ($i = 0; $i < count($field_name); $i++) {
		unset($query);
		$query .= "$cfgQuotes$field_name[$i]$cfgQuotes $field_type[$i] ";

		if (!empty($field_length[$i])) {
			$query .= "(".stripslashes($field_length[$i]).") ";
		}
		// if (!empty($field_null[$i])) {
			$query .= "$field_null[$i] " ;
		// }
		if (!empty($field_default[$i]) && $version >= 7.0 ) {
			$field_default[$i] = stripslashes($field_default[$i]);
			$query_default = "ALTER TABLE $cfgQuotes$table$cfgQuotes ALTER $cfgQuotes$field_name[$i]$cfgQuotes SET DEFAULT $field_default[$i];\n";
			// $query .= "DEFAULT ".stripslashes($field_default[$i])." ";
		}

		// $query .= "$field_null[$i] $field_extra[$i]";
		
		$sql_query .= "ALTER TABLE $cfgQuotes$table$cfgQuotes ADD $query; \n";
		$sql_query .= $query_default;

		$result = pg_exec($link, pre_query($sql_query)) or pg_die(pg_errormessage($link), $sql_query, __FILE__, __LINE__);
		$display_query .= $sql_query . "\n";
		unset($sql_query);
		
		if ((!empty($field_default[$i])) && ($version >= 7.0) && (trim($field_null[$i]) == "NOT NULL")) {
			$query_default = "UPDATE $cfgQuotes$table$cfgQuotes SET $cfgQuotes$field_name[$i]$cfgQuotes = $field_default[$i]";
			$result = pg_exec($link, pre_query($query_default)) or pg_die(pg_errormessage($link, $query_default, __FILE__, __LINE__));
		}
	}
	// $query = stripslashes(ereg_replace(", ADD $", "", $query)); //"
	

	for ($i = 0; $i < count($field_index); $i++) {
		$j = $field_index[$i];
		$sql_query .= "CREATE INDEX $cfgQuotes" . $field_name[$j] . "_" . $table . "_key$cfgQuotes ON $cfgQuotes$table$cfgQuotes($cfgQuotes$field_name[$j]$cfgQuotes); ";
	}

	for ($i = 0; $i < count($field_unique); $i++) {
		$j = $field_unique[$i];
		$sql_query .= "CREATE UNIQUE INDEX $cfgQuotes" . $field_name[$j] . "_" . $table . "_ukey$cfgQuotes ON $cfgQuotes$table$cfgQuotes($cfgQuotes$field_name[$j]$cfgQuotes); ";
	}

	if (!empty($sql_query)) {
		$sql_query = ereg_replace("; $", "", $sql_query);
		$result = pg_exec($link, pre_query($sql_query)) or pg_die(pg_errormessage(), $sql_query, __FILE__, __LINE__);
		$display_query .= $sql_query;
	}

	$sql_query = $display_query;
	$sql_query .= ";\n" . $query_default;

	$message .= "$strTable $table $strHasBeenAltered";
	include("tbl_properties.php");
	exit;
} else {
	$action = "tbl_addfield.php";
	include("tbl_properties.inc.php");
}

include ("footer.inc.php");
?>
