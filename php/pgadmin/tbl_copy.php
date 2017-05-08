<?php
/* $Id: tbl_copy.php,v 1.3 2001/04/19 02:55:27 kkemp102294 Exp $ */

$priv = trim(ereg_replace("[\{\"]", "", $row[relacl])); 
include("header.inc.php");


function my_handler($sql_insert)
{
	global $table, $link, $new_name;
	$sql_insert = ereg_replace($table, $new_name, $sql_insert);
	$result = pg_exec($link, pre_query($sql_insert)) or pg_die(pg_errormessage($link), $sql_insert, __FILE__, __LINE__);
	$sql_query = $sql_insert;
}

$sql_structure = get_table_def($link, $table, "\n");
$sql_structure = ereg_replace($table, $new_name, $sql_structure);

$result = @pg_exec($link, pre_query($sql_structure)) or pg_die(pg_errormessage($link), $sql_structure, __FILE__, __LINE__);
$sql_query .= "\n$sql_structure";

if ($what == "data") {
	get_table_content($link, $table, "my_handler");
}

eval("\$message = \"$strCopyTableOK\";");
include("db_details.php");
?>
