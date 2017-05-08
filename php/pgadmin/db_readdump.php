<?php
/* $Id: db_readdump.php,v 1.3 2001/02/02 06:03:08 dwilson Exp $ */

include("header.inc.php");

$client = getenv("HTTP_USER_AGENT");
$crlf = "\n";
if (ereg('[^(]*\((.*)\)[^)]*',$client,$regs)) {
	$os = $regs[1];
	// this looks better under WinX
	if (eregi("Win",$os)) $crlf = "\r\n";
}

$orig_query = $sql_query;
// $sql_query = str_replace($crlf, "", $sql_query);

$pieces  = noQuoteSplit($sql_query, ";", "'");
// $pieces = explode($sql_query, ";");

if (count($pieces) == 1) {
	$sql_query = ereg_replace(";$", "", trim($sql_query));
	include ("sql.php");
	exit;
}

for ($i = 0; $i < count($pieces); $i++) {
	if (!$from_file) {
		$pieces[$i] = stripslashes($pieces[$i]);
	}
	$pieces[$i] = trim($pieces[$i]);
	if (!empty($pieces[$i]) && ereg(";", $pieces[$i])) {
		// echo $pieces[$i], "<br><br>";
		$result = pg_exec($link, pre_query($pieces[$i])) or pg_die(pg_errormessage(), $pieces[$i], __FILE__, __LINE__);
	}
}

if (eregi("create table|drop table|create database|drop database", $sql_query)) {
	$reload = "true";
}
$sql_query = nl2br(stripslashes($orig_query));

$message = $strSuccess;
include("db_details.php");

?>
