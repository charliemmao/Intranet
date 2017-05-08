<?php

include("header.inc.php");

if (!isset($submit)) {
	// Set max built-in oid
	$max = 0;

	// First, get all available types
			$sql_get_types = "
				(
				SELECT typname
				FROM pg_type pt
				WHERE typname NOT LIKE '\\\_%'
				)
				EXCEPT
				(
				SELECT relname
				FROM pg_class
				WHERE 
					relkind = 'S' OR relname LIKE 'pg%'
				) ORDER BY typname
				";
			$types = pg_exec($link, pre_query($sql_get_types)) or pg_die(pg_errormessage(), $sql_get_types, __FILE__, __LINE__);
		
	// And get functions (userland) as well
			$sql_get_func = "
				SELECT 
					proname
				FROM 
					pg_proc pc, pg_user pu
				WHERE
					pc.proowner = pu.usesysid
					AND pc.oid > '$max'::oid
            ORDER BY proname
			";
			$funcs = pg_exec($link, pre_query($sql_get_func)) or pg_die(pg_errormessage(), $sql_get_func, __FILE__, __LINE__);

	?>
	<form method=POST>
	<table border="<?php echo $cfgBorder; ?>">
	<tr>
		<th><?php echo $strProperty; ?></th>
		<th><?php echo $strValue; ?></th>
	</tr>
	<tr><td><?php echo $strOperator ?></td><td><input type="text" name="operator"></td></tr>
	<tr><td><?php echo $strFunc ?></td><td>
	<?php
		if (@pg_numrows($funcs) <= 0)
			echo "<input type=\"text\" name=\"procedure\">\n";
		else {
			echo "<select name=\"procedure\">\n";
			for ($i_func = 0; $i_func < @pg_numrows($funcs); $i_func++) {
				$proname = pg_result($funcs, $i_func, "proname");
				echo "<option value=\"", htmlspecialchars($proname), "\">$proname\n";
			}
			echo "</select>\n";
		}
	?>
	</td></tr>
	<tr><td><?php echo "$strLeft $strArg" ?></td><td><select name="leftarg">
   <option value=""></option>
	<?php
		for ($i_type = 0; $i_type < @pg_numrows($types); $i_type++) {
			$typename = pg_result($types, $i_type, "typname");
			echo "<option value=\"", htmlspecialchars($typename), "\">$typename\n";
		}
	?>
	</select></td></tr>
	<tr><td><?php echo "$strRight $strArg" ?></td><td><select name="rightarg">
	<option value=""></option>
	<?php
		for ($i_type = 0; $i_type < @pg_numrows($types); $i_type++) {
			$typename = pg_result($types, $i_type, "typname");
			echo "<option value=\"", htmlspecialchars($typename), "\">$typename\n";
		}
	?>
	</td></tr>
	<tr><td><?php echo $strCommutator ?></td><td><input type="text" name="commutator"></td></tr>
	<tr><td><?php echo $strNegator ?></td><td><input type="text" name="negator"></td></tr>
	<tr><td><?php echo "$strRestrict $strFunc" ?></td><td><input type="text" name="restrict"></td></tr>
	<tr><td><?php echo "$strJoin $strFunc" ?></td><td><input type="text" name="join"></td></tr>
	<tr><td><?php echo "$strHashes?" ?></td><td><input type="checkbox" name="hashes"></td></tr>
	<tr><td><?php echo "$strLeft $strSort $strFunc" ?></td><td><input type="text" name="left_sort"></td></tr>
	<tr><td><?php echo "$strRight $strSort $strFunc" ?></td><td><input type="text" name="right_sort"></td></tr>
	<tr>
		<td align=center colspan=4>
		<input type=submit name=submit value="<?php echo "$strCreate $strOperator"; ?>">
		<input type=reset>
		</td>
	</tr>
	</table>
	</form>
	<?php
} else {

	if (get_magic_quotes_gpc()) {
      $operator = trim(stripslashes($operator));
      $procedure = trim(stripslashes($procedure));
      $leftarg = trim(stripslashes($leftarg));
      $rightarg = trim(stripslashes($rightarg));
      $commutator = trim(stripslashes($commutator));
      $negator = trim(stripslashes($negator));
      $restrict = trim(stripslashes($restrict));
      $join = trim(stripslashes($join));
      $left_sort = trim(stripslashes($left_sort));
      $right_sort = trim(stripslashes($right_sort));
   }

   // Begin composing SQL query
   $sql_query = "CREATE OPERATOR $operator (PROCEDURE = $procedure";
   if ($leftarg != '') $sql_query .= ", LEFTARG = $leftarg";
   if ($rightarg != '') $sql_query .= ", RIGHTARG = $rightarg";
   if ($commutator != '') $sql_query .= ", COMMUTATOR = $commutator";
   if ($negator != '') $sql_query .= ", NEGATOR = $negator";
   if ($restrict != '') $sql_query .= ", RESTRICT = $restrict";
   if ($join != '') $sql_query .= ", JOIN = $join";
   if (isset($hashes)) $sql_query .= ", HASHES";
   if ($left_sort != '') $sql_query .= ", SORT1 = $left_sort";
   if ($right_sort != '') $sql_query .= ", SORT2 = $right_sort";
   $sql_query .= ")";

	echo $strDoYouReally . " " . htmlspecialchars($sql_query);
	?>
	<form method=POST action="sql.php">
	<input type=hidden name="db" value="<?php echo $db; ?>">
	<input type=hidden name="server" value="<?php echo $server; ?>">
	<input type=hidden name="sql_query" value="<?php echo htmlspecialchars($sql_query); ?>">
	<input type=hidden name="goto" value="db_details.php">
	<input type=hidden name="rel_type" value="function">
	<input type=submit name="btnDrop" value="<?php echo $strYes; ?>">
	<input type=submit name="btnDrop" value="<?php echo $strNo; ?>">
	</form>
	<?php
}

include("footer.inc.php");
?>
