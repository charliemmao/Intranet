<?php
/* $Id: seq_create.php,v 1.2 2001/02/02 06:03:08 dwilson Exp $ */

include("header.inc.php");


// $sql_query = "CREATE SEQUENCE $cfgQuotes$seq_name$cfgQuotes START $startval";

// I took out the quotes around the sequence name because it appears that Postgres 
// does not allow for it to be uppercase in a nextval function.  If is created as 
// uppercase, it won't be able to find it when you set the default of a column.
// -Dan

$sql_query = "CREATE SEQUENCE $seq_name START $startval";
@pg_exec($link, pre_query($sql_query)) or pg_die(pg_errormessage(), $sql_query, __FILE__, __LINE__);

$message = "$strSequence $seq_name $strHasBeenCreated";
include("db_details.php");

exit;
// include ("footer.inc.php");
?>
