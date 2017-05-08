<?php
/* $Id: sql.php,v 1.8 2001/04/23 05:41:39 dwilson Exp $ */
// 	File:		sql.php
// 	Purpose:	Run any and a lot of the general straight sql statments

include("lib.inc.php");
$no_include = true;
// Go back to further page if table should not be dropped
if (isset($btnDrop) && $btnDrop != $strYes) {
	$sql_query = stripslashes($sql_query);
	if (file_exists("./$goto")) {
		include(preg_replace('/\.\.*/', '.', $goto));
	} else {
		Header("Location: $goto");
	}
	exit;
}


// Check if table should be dropped
// $is_drop_sql_query = eregi("DROP +TABLE|DATABASE|SEQUENCE|FUNCTION|VIEW|USER ALTER TABLE +[[:alnum:]]* +DROP|DELETE FROM", $sql_query); // Get word "drop"
$is_drop_sql_query = eregi("DROP|ALTER|DELETE", $sql_query); // Get word "drop"

if (!$cfgConfirm) {
   $btnDrop = $strYes;
}

if ($is_drop_sql_query && !isset($btnDrop)) {
   include("header.inc.php");
   echo $strDoYouReally.urldecode(stripslashes(nl2br(htmlentities($sql_query))))."?<br>";
	if (eregi("create table|drop table|create database|drop database", $sql_query)) {
		$reload = "true";
	}
   ?>
   <form action="sql.php" method="post" enctype="application/x-www-form-urlencoded">
   <input type="hidden" name="sql_query" value="<?php echo htmlspecialchars(stripslashes($sql_query)); ?>">
   <input type="hidden" name="server" value="<?php echo $server ?>">
   <input type="hidden" name="db" value="<?php echo $db ?>">
   <input type="hidden" name="zero_rows" value="<?php echo $zero_rows;?>">   
   <input type="hidden" name="table" value="<?php echo $table;?>">   
   <input type="hidden" name="goto" value="<?php echo $goto;?>">
   <input type="hidden" name="reload" value="<?php echo $reload;?>">   
   <input type="hidden" name="rel_type" value="<?php echo $rel_type;?>">   
   <input type="Submit" name="btnDrop" value="<?php echo $strYes; ?>">
   <input type="Submit" name="btnDrop" value="<?php echo $strNo; ?>">
   </form>
   <?php 
} else {
	if (eregi("create table|drop table|create database|drop database", $sql_query)) {
		$reload = "true";
	}
	if (!empty($sql_query)) {
		// Pull in the primary keys for this table
		$pri_query = "
			SELECT 
				a.attname AS column_name,
				i.indisunique AS unique_key,
				i.indisprimary AS primary_key
			FROM 
				pg_class bc,
				pg_class ic,
				pg_index i,
				pg_attribute a
			WHERE 
				i.indrelid = bc.oid
				and i.indexrelid = ic.oid
				and 
				(
					i.indkey[0] = a.attnum 
					or
					i.indkey[1] = a.attnum
					or
					i.indkey[2] = a.attnum
					or
					i.indkey[3] = a.attnum
					or
					i.indkey[4] = a.attnum
					or
					i.indkey[5] = a.attnum
					or
					i.indkey[6] = a.attnum
					or
					i.indkey[7] = a.attnum
				)
				and a.attrelid = bc.oid
				and i.indproc = '0'::oid
				and bc.relname = '$table'
				and (i.indisprimary = 't' or i.indisunique = 't')
		";
	
		$pri_result = @pg_exec($link, pre_query($pri_query)) or pg_die(pg_errormessage(), $pri_query, __FILE__, __LINE__);
		$pri_num = @pg_numrows($pri_result);
		
		for ($i_pri_rows = 0; $i_pri_rows < $pri_num; $i_pri_rows++) {
			 $my_pri_key = pg_result($pri_result, $i_pri_rows, "column_name");
			 // if (!eregi($my_pri_key, $sql_query)) {
			 	// Need to figure out a way to determine whether the primary key for the table has been selected
				// If not, then we need to pull it in.  This is for use with the actions.
			 // }
			 $pri_keys[] = $my_pri_key;
		}
		
		$sql_query = isset($sql_query) ? stripslashes($sql_query) : '';
		$sql_order = isset($sql_order) ? stripslashes($sql_order) : '';
		if (!$result = @pg_exec($link, pre_query($sql_query.$sql_order))) {
			include("header.inc.php");
			pg_die(pg_errormessage($link), $sql_query.$sql_order, __FILE__, __LINE__);
		}
		$num_rows = @pg_numrows($result);
	}
	
	if ($num_rows < 1 || ($rel_type == "sequence" && eregi($sql_query, "setval"))) {
		if (eregi("delete|insert|update", $sql_query)) {
			$affected_rows = @pg_cmdtuples($result);	
		} else {
			unset($affected_rows);
		}
		if (file_exists("./$goto")) {
			include("header.inc.php");
			if (isset($zero_rows) && !empty($zero_rows)) {
				$message = $zero_rows;
			} else {
				$message = $strEmptyResultSet;
			}
			include(preg_replace('/\.\.*/', '.', $goto));
		} else {
			$message = $zero_rows;
			Header("Location: $goto");
		}
		exit;
	} else {
		include("header.inc.php");
		$query = display_table($result);
		// $query = "server=$server&db=$db&table=$table&goto=$goto";
		echo "<br><li><a href=\"rep_create.php?server=$server&db_name=$db&rep_sql=", urlencode($sql_query), "\">$strCreateNew $strReport</a><br><br>";
		switch ($rel_type) {
			case "sequence"	:
				$sql_query = urlencode("SELECT SETVAL('$sequence', 1)");
				$goto = urlencode($QUERY_STRING);
				echo "<li><a href=\"sql.php?server=$server&db=$db&table=$table&sql_query=$sql_query&goto=$goto\">Reset</a>";
				break;
			default:
				if ($cfgShowSQL) {
					show_message("Your SQL statment");
				}
				if (!empty($query)) {
					echo "<br><li><a href=\"tbl_change.php?$query\">$strInsert</a>";
				}
				break;
		}
	}
}
include ("footer.inc.php");
?>
