<?php 
/* $Id: tbl_properties.inc.php,v 1.4 2001/02/02 06:03:08 dwilson Exp $ */
?>
<form method="post" action="<?php echo $action;?>">
<input type="hidden" name="server" value="<?php echo $server;?>">
<input type="hidden" name="db" value="<?php echo $db;?>">
<input type="hidden" name="table" value="<?php echo $table;?>">
<table border=<?php echo $cfgBorder;?>>
<tr>
<th><?php echo $strField; ?></th>
<?php
	if ($action != "tbl_alter.php") {
		$strDocsLink = "sql-altertable.htm";
	} else {
		$strDocsLink = "sql-createtable.htm";
	}
		// echo "<tr><th colspan=7 style=\"font-size : 12pt\">$strNotImp</font></th></tr>";
	echo "
		<th>$strType</th>
		<th>$strLengthSet</th>
		<th>$strNotNull</th>
		<th>$strDefault</th>
	";

	if ($action == "tbl_create.php" || $action == "tbl_addfield.php") {
		if ($action == "tbl_create.php") {
			echo "
				<th>$strReferences</th>
				<th>$strPrimary</th>
			";

			$sql_get_fields = "
				SELECT 
					a.attnum,
					a.attname AS field,
					c.relname AS table_name
				FROM 
					pg_class c, pg_attribute a
				WHERE 
					c.relname NOT LIKE 'pg%'
					AND relkind = 'r'
					AND a.attnum > 0
					AND a.attrelid = c.oid
				ORDER BY
					table_name, attnum
				";
			$fields = @pg_exec($link, pre_query($sql_get_fields)) or pg_die(pg_errormessage(), $sql_get_fields, __FILE__, __LINE__);
			$num_fields_fk = @pg_numrows($fields);
			
			for ($i_field = 0; $i_field < $num_fields_fk; $i_field++) {
				$field = pg_result($fields, $i_field, "field");
				$table_name = pg_result($fields, $i_field, "table_name");
				$strFKFields .= "<option value='" . addslashes("$cfgQuotes$table_name$cfgQuotes($cfgQuotes$field$cfgQuotes)") . "'>$table_name($field)\n";
				// $aryField[] = "$cfgQuotes$sel_tables[$i_tabs]$cfgQuotes.$cfgQuotes$field$cfgQuotes";
			}
		}
		echo "
			<th>$strIndex</th>
			<th>$strUnique</th>
		";

	}
	
	echo "</tr>";

	$sql_get_types = "
		SELECT typname 
		FROM pg_type pt
		WHERE typname NOT LIKE '\\\_%' AND typrelid = 0
		ORDER BY typname
	";

	$types = pg_exec($link, pre_query($sql_get_types)) or pg_die(pg_errormessage(), $sql_get_types);
	
	
	for ($i = 0; $i < $num_fields; $i++) {
		if (isset($result)) {
			$row = pg_fetch_array($result, $i);
		}
		$bgcolor = $cfgBgcolorOne;
	    $i % 2  ? 0: $bgcolor = $cfgBgcolorTwo;
?>
    <tr bgcolor="<?php echo $bgcolor;?>">
    <td valign=\"top\">
      <input type="text" name="field_name[]" size="20" value="<?php if(isset($row) && isset($row[field])) echo $row[field];?>">
      <input type="hidden" name="field_orig[]" value="<?php if(isset($row) && isset($row[field])) echo $row[field];?>">
	</td>
<?php
		if ($row["lengthvar"] > 0) {
			$Length = $row["lengthvar"] - 4;
		} else if ($row["length"] > 0) {
			$Length = $row["length"];
		} else {
			// $Length = "var";
			unset($Length);
		}

		if (eregi("bool", $row[type])) {
			$Length = 0;
		}
		
		if (eregi("numeric", $row[type])) {
			$Length = (($row[lengthvar] >> 16) & 0xffff) . "," . (($row[lengthvar] - 4) & 0xffff);
		}
		
	if ($action != "tbl_alter.php") {
		unset($strTypeStr);
		for ($i_type = 0; $i_type < @pg_numrows($types); $i_type++) {
			$typename = pg_result($types, $i_type, "typname");
			$strTypeStr .= "<option value=\"$typename\"";
			if ($row[type] == $typename) {
				$strTypeStr .= " selected";
			}
			$strTypeStr .= ">$typename";
		}

		$field_type		= "<select name=\"field_type[]\"><option value=\"SERIAL\">SERIAL $strTypeStr </select>";
	
		$field_len 		= "<input type=\"text\" name=\"field_length[]\" size=\"8\" value=\"$Length\">";

		$field_null 	= "<select name=\"field_null[]\">";
		if (!isset($row) || $row[notnull] == "t") {
			$field_null .= "<option value=\" NOT NULL\">NOT NULL</option><option value=\"\">NULL</option>";
		} else {
			$field_null .= "<option value=\"\">NULL</option><option value=\" NOT NULL\">NOT NULL</option>";
		}
	    $field_null 	.= "</select>";

		if (isset($row) && isset($rowdefault)) { 
			$strThisDefault = $rowdefault;
		} else {
			unset($strThisDefault);
		}
		$field_default	= "<input type=\"text\" name=\"field_default[]\" size=\"20\" value=\"$strThisDefault\">";
	} else {
		$field_type		= $row[type];
		$field_len		= $Length;
		$field_null		= bool_YesNo($row[notnull]);

		if ($version >= 7.0) {
			if (isset($row) && isset($rowdefault)) {
				$strThisDefault = $rowdefault;
			} else {
				unset($strThisDefault);
			}
			if (strstr($field_type, "text")) {
				$field_default = "<textarea wrap=\"virtual\" name=\"field_default[]\" style=\"width:$cfgMaxInputsize;\" rows=5>$strThisDefault</textarea>\n";
			} elseif (strstr($field_type, "varchar") && $field_len > 50) {
				$field_default = "<textarea wrap=\"virtual\" name=\"field_default[]\" style=\"width:$cfgMaxInputsize;\" rows=5>$strThisDefault</textarea>\n";
			} else {
				$field_default = "<input type=text name=\"field_default[]\" size=20 value=\"$strThisDefault\">";
			}
		}
	}
	
	echo "
		<td valign=\"top\">$field_type</td>
		<td valign=\"top\">$field_len</td>
		<td valign=\"top\">$field_null</td>
		<td valign=\"top\">$field_default</td>
	";

	if ($action == "tbl_create.php" || $action == "tbl_addfield.php") { 
		/*
		if (isset($row) && isset($row["Key"]) && $row["Key"] == "PRI") {
			$strPriKey = "checked";
		}
		if (isset($row) && isset($row["Key"]) && $row["Key"] == "MUL") {
			$strMULKey = "checked";
		}
		if (isset($row) && isset($row["Key"]) && $row["Key"] == "UNI") {
			$strUNIKey = "checked";
		}
		*/
		
		if ($action == "tbl_create.php") {
			echo "
				<td align=\"center\" valign=\"top\">
					<select name=\"fk_field[]\"><option value=\"\">$strFKFields</select>
				</td>
				<td align=\"center\" valign=\"top\">
					<input type=\"checkbox\" name=\"field_primary[]\" value=\"$i\" $strPriKey>
				</td>
			";

			
		}
		echo "
			<td align=\"center\" valign=\"top\">
				<input type=\"checkbox\" name=\"field_index[]\" value=\"$i\" $strMULKey>
			</td>
			<td align=\"center\" valign=\"top\">
				<input type=\"checkbox\" name=\"field_unique[]\" value=\"$i\" $strUNIKey>
			</td>
		";
	}
    echo "</tr>";
}
?>
	<tr>
		<td colspan="5">
			<input type="submit" name="submit" value="<?php echo $strSave;?>">
			<input type="hidden" name="table" value="<?php echo $table; ?>"
		</td>
	</tr>
	<tr>
		<td align="center" colspan="5">
			<?php print show_docu($strDocsLink);?>
		</td>
	</tr>
</table>
</form>
