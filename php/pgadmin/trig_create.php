<?php

include("header.inc.php");

if (!isset($submit)) {
	?>
	<form method=POST>
	<table border="<?php echo $cfgBorder; ?>">
	<tr>
		<th><?php echo $strTrigger; ?></th>
		<th><?php echo $strWhen; ?></th>
		<th><?php echo $strOnEvents; ?></th>
		<th><?php echo $strTable; ?></th>
	</tr>
	<tr>
		<td><input type="text" name="trigger"></td>
		<td>
			<select name="when">
				<option value="AFTER">AFTER</option>
				<option value="BEFORE">BEFORE</option>
			</select>
		</td>
		<td>
			<input type=checkbox name="events[INSERT]">Insert
			<input type=checkbox name="events[UPDATE]">Update
			<input type=checkbox name="events[DELETE]">Delete
		</td>
		<td>
		<?php
			$tables = pg_exec($link, "SELECT tablename FROM pg_tables WHERE tablename NOT LIKE 'pg%' ORDER BY tablename");
			$num_tables = @pg_numrows($tables);
			if ($num_tables > 0) {
				$i = 0;
				print "<select name=table>\n";
				while ($i < $num_tables) {
					$table = pg_result($tables, $i, 'tablename');
					print "<option value=\"$table\">$table</option>\n";
					$i++;
				}
				print "</select>\n";
			}
			else {
				print "<input type=text name=table>\n";
			}
		?>
		</td>
	</tr>
	<tr><th colspan=4><?php echo $strFunc; ?></th></tr>
	<tr>
		<td colspan=4><input size=70 name=procedure><br>eg. check_foreign('1', UNDEFINED, 'string')</td>
	</tr>
	<tr>
		<td align=center colspan=4>
		<input type=submit name=submit value="<?php echo "$strCreate $strTrigger"; ?>">
		<input type=reset>
		</td>
	</tr>
	</table>
	</form>
	<?php
} else {
	// Need to stripslash procedure - they'll have to slash it themselves
	if (get_magic_quotes_gpc()) $procedure = stripslashes($procedure);
	$sql_query = "CREATE TRIGGER $cfgQuotes$trigger$cfgQuotes $when";
	$findx = false;
	if (isset($events[INSERT])) {
		$sql_query .= " INSERT";
		$findx = true;
	}
	if (isset($events[UPDATE])) {
		$sql_query .= ($findx) ? ' OR' : '';
		$sql_query .= " UPDATE";
		$findx = true;
	}
	if (isset($events[DELETE])) {
		$sql_query .= ($findx) ? ' OR' : '';
		$sql_query .= " DELETE";
	}
	$sql_query .= " ON $cfgQuotes$table$cfgQuotes FOR EACH ROW EXECUTE PROCEDURE $procedure;";
	echo $strDoYouReally . " " . htmlspecialchars($sql_query);
	?>
	<form method=POST action="sql.php">
	<input type=hidden name="db" value="<?php echo $db; ?>">
	<input type=hidden name="server" value="<?php echo $server; ?>">
	<input type=hidden name="sql_query" value="<?php echo htmlspecialchars($sql_query); ?>">
	<input type=hidden name="goto" value="db_details.php">
	<input type=hidden name="rel_type" value="trigger">
	<input type=submit name="btnDrop" value="<?php echo $strYes; ?>">
	<input type=submit name="btnDrop" value="<?php echo $strNo; ?>">
	</form>
	<?php
}

include("footer.inc.php");
?>
