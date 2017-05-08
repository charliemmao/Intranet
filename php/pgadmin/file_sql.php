<?php
/* $Id: file_sql.php,v 1.1.1.1 2000/11/10 04:35:55 dwilson Exp $ */
// 	File:		file_sql.php
// 	Purpose:	Enable upload of file for batch sql statments

set_time_limit(90);
include("header.inc.php");

$upload_file = implode("", file($userfile));

// echo "<br><br>", nl2br($upload_file);
$from_file = true;
$sql_query = $upload_file;

$no_include = true;
include("db_readdump.php");

?>
