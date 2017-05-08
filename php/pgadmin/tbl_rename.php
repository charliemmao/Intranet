<?php
/* $Id: tbl_rename.php,v 1.2 2001/02/02 06:03:08 dwilson Exp $ */
$old_name = $table;
$table = $new_name;
include("header.inc.php");

$sql_query = "ALTER TABLE $cfgQuotes$old_name$cfgQuotes RENAME TO $cfgQuotes$new_name$cfgQuotes";

$result = pg_exec($link, pre_query($sql_query)) or pg_die(pg_errormessage(), $sql_query, __FILE__, __LINE__);
$table = $old_name;
eval("\$message = \"$strRenameTableOK\";");
$table = $new_name;
include("tbl_properties.php");
exit;

include ("footer.inc.php");
?>
