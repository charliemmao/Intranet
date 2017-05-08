<?php
/* $Id: tbl_replace.php,v 1.3 2001/04/23 05:41:39 dwilson Exp $ */

include("lib.inc.php");
$no_include = true;

reset($fields);
reset($funcs);
reset($field_type);

if (isset($primary_key)) {
	$primary_key = stripslashes($primary_key);
	unset($valuelist);
	while (list($key, $val) = each($fields)) {
		if (strtolower($val) != "null")
			$val = "'$val'";
		if (empty($funcs[$key])) {
			$valuelist .= "$cfgQuotes$key$cfgQuotes = $val, ";
		} else {
			$valuelist .= "$cfgQuotes$key$cfgQuotes = $funcs[$key]($val), ";
		}
	}
	$valuelist = ereg_replace(', $', '', $valuelist);
	$query = "UPDATE $cfgQuotes$table$cfgQuotes SET $valuelist WHERE $primary_key";
} else {
	unset($fieldlist);
	unset($valuelist);
	while (list($key, $val) = each($fields)) {
		$fieldlist .= "$cfgQuotes$key$cfgQuotes, ";
		$strValDelim = "'";
		// Check to see if there are already single quotes and are not a function
		if (!eregi("^'[[:alnum:][:punct:][:cntrl:][:space:]]*'$", $val) && empty($funcs[$key])) {
			if (eregi("char|date|bool|time", $field_type[$key])) {
				if (!empty($val)) {
					$strValDelim = "'";
				} else {
					$val = "NULL";
					unset($strValDelim);
				}
			}
			if (strtolower($val) != "null") {
				$val = $strValDelim . $val . $strValDelim;
			}
		}

		if (empty($funcs[$key])) {
			$valuelist .= "$val, ";
		} else {
			// $val = str_replace("'", "\"", $val);
			$valuelist .= "$funcs[$key](" . stripslashes($val) . "), ";
		}
	}
	$fieldlist = ereg_replace(", $", "", $fieldlist);
	$valuelist = ereg_replace(", $", "", $valuelist);
	$query = "INSERT INTO $cfgQuotes$table$cfgQuotes ($fieldlist) VALUES ($valuelist)";
	// echo $query, "<p>";
}

$sql_query = $query;
if (!$result = @pg_exec($link, pre_query($query))) {
	include("header.inc.php");
	pg_die(pg_errormessage(), $query, __FILE__, __LINE__);
} else {
	if (eregi("delete|insert|update", $sql_query)) {
		$affected_rows = @pg_cmdtuples($result);
	} else {
		unset($affected_rows);
	}
	if (file_exists("./$goto")) {
		include("header.inc.php");
		$message = $strModifications;
		include(preg_replace('/\.\.*/', '.', $goto));
	} else {
		Header("Location: $goto");
	}
	exit;
}

?>
