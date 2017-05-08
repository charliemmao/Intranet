<?php
/* $Id: db_create.php,v 1.2 2001/02/02 06:03:08 dwilson Exp $ */

include("header.inc.php");

if (empty($goto)) $goto = "db_details.php";

$qrCreateDB = "CREATE DATABASE $cfgQuotes$newdb$cfgQuotes";
if (!@pg_exec($qrCreateDB)) {
	pg_die(pg_errormessage(), $qrCreateDB, __FILE__, __LINE__);
} else {
	$message = "$strDatabase $newdb $strHasBeenCreated";
	$db = $newdb;
	include($goto);
}

?>
