<?php
/* $Id: tbl_change.php,v 1.2 2000/12/15 03:24:46 dwilson Exp $ */

include("header.inc.php");

?>

<form method="POST" action="tbl_replace.php">
<input type="hidden" name="server" value="<?php echo $server;?>">
<input type="hidden" name="db" value="<?php echo $db;?>">
<input type="hidden" name="table" value="<?php echo $table;?>">
<input type="hidden" name="goto" value="<?php echo $goto;?>">

<?php 
$show_defaults = true;
$show_ops = false;
include("tbl_form.inc.php"); 
?>

<p>
<input type="submit" value="<?php echo $strSave; ?>">
</form>

<?php
include ("footer.inc.php");
?>
