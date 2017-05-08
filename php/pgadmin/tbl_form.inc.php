<?php 
//	File:		tbl_form.inc.php
//	Purpose:		Display the form for editing/inserting/selecting from a table
//	Author:		Dan Wilson
//	Date:		19 Feb 2000

$sql_get_fields = "
	SELECT 
		a.attnum,
		a.attname AS field, 
		t.typname AS type, 
		a.attlen AS length,
		a.atttypmod AS lengthvar,
		a.attnotnull AS notnull,
		a.atthasdef as hasdefault
	FROM 
		pg_class c, 
		pg_attribute a, 
		pg_type t
	WHERE 
		c.relname = '$table'
		and a.attnum > 0
		and a.attrelid = c.oid
		and a.atttypid = t.oid
";

if ($cfgDoOrder) {
	$sql_get_fields .= "
		ORDER BY
			attname
	";
} else {
	$sql_get_fields .= "
		ORDER BY
			attnum
	";
}

$table_def = pg_exec($link, pre_query($sql_get_fields));
if (!empty($primary_key)) {
	$primary_key = stripslashes($primary_key);
	$qrPriKeys = "SELECT * FROM $cfgQuotes$table$cfgQuotes WHERE " . urldecode($primary_key);
	$result = @pg_exec($link, pre_query($qrPriKeys)) or pg_die(pg_errormessage($link), $qrPriKeys, __FILE__, __LINE__);
	$row = @pg_fetch_array($result, 0);
} else {
	$qrTopRow = "SELECT * FROM $cfgQuotes$table$cfgQuotes LIMIT 1";
	$result = @pg_exec($link, pre_query($qrTopRow)) or pg_die(pg_errormessage(), $qrTopRow, __FILE__, __LINE__);
}
if (!$table_def) {
	pg_die(pg_errormessage(), $sql_get_fields, __FILE__, __LINE__);
} else {

	if (isset($primary_key)) {
		echo '<input type="hidden" name="primary_key" value="' . htmlspecialchars($primary_key) . '">' . "\n";
	}
?>
	<table border="<?php echo $cfgBorder;?>">
	<tr>
	<th><?php echo $strField; ?></th>
	<th><?php echo $strType; ?></th>
	<th><?php echo $strFunction; ?></th>
<?php 
	if ($show_ops) { 

		$arOps['LIKE (~~)'] = "~~";
		$arOps['NOT LIKE (!~~)'] = "!~~";
		$arOps['='] = "=";
		$arOps['!='] = "!=";
		$arOps['>'] = ">";
		$arOps['>='] = ">=";
		$arOps['<'] = "<";
		$arOps['<='] = "<=";
		$arOps['IN'] = "IN";
		$arOps['NOT IN (!!=)'] = "!!=";
		$arOps['BETWEEN'] = "BETWEEN";
		$arOps['CASE MATCH (~*)'] = "~*";
		$arOps['MATCH (~)'] = "~";
		$arOps['CASE NOT MATCH (!~)'] = "!~";
		$arOps['NOT MATCH (!~*)'] = "!~*";

?>

	<th><?php echo $strOperator; ?></th>
<?php } ?>
	<th><?php echo $strValue; ?></th>
	</tr>
<?php

	for ($i = 0; $i < pg_numrows($table_def); $i++) {
	
		$row_table_def = pg_fetch_array($table_def, $i);
		$field = $row_table_def["field"];
		if (($row_table_def["type"]  == "timestamp") && (empty($row[$field])) && ($show_defaults)) {
			$row[$field] = date("Y-m-d H:i:s", time());
		}
		if (($row_table_def["type"]  == "date") && (empty($row[$field])) && ($show_defaults)) {
			$row[$field] = date("Y-m-d", time());
		}
		if ($row_table_def["lengthvar"] > 0) {
			$len = $row_table_def["lengthvar"] - 4;
		} else if ($row_table_def["length"] > 0) {
			$len = $row_table_def["length"];
		} else {
			// $len = "var";
			unset($len);
		}

		unset($selected_func);
		unset($funcs);

		if (!$edit && $row_table_def["hasdefault"] == "t"  && ($show_defaults)) {
			$sql_get_default = "
				SELECT d.adsrc AS \"default_val\"
				FROM pg_attrdef d, pg_class c 
				WHERE 
					c.relname = '$table' AND 
					c.oid = d.adrelid AND
					d.adnum = $row_table_def[attnum]
			";
			// echo "<p>", $sql_get_default, "<p>";
			if (!$def_res = @pg_exec($link, pre_query($sql_get_default))) {
				pg_die(pg_errormessage(), $sql_get_default, __FILE__, __LINE__);
				unset($row[$field]);
			} else {
				if (pg_numrows($def_res) > 0) {
					$row[$field] = @pg_result($def_res, 0, "default_val");
					if (eregi("([[:alnum:]_]+)\(([[:alnum:][:punct:][:cntrl:][:space:]]*)\)$", $row[$field], $vals)) {
						$selected_func = $vals[1];
						$row[$field] = $vals[2];
						$row[$field] = ereg_replace("^'", "'$cfgQuotes", $row[$field]);
						$row[$field] = ereg_replace("'::", "$cfgQuotes'::", $row[$field]);
						$row[$field] = ereg_replace("'$", "$cfgQuotes'", $row[$field]);
					}
				} else {
					unset($row[$field]);
				}
			}
			if (eregi("^'[[:alnum:][:punct:][:cntrl:][:space:]]*'$", $row[$field])) {
				$row[$field] = ereg_replace("^'|'$", "", $row[$field]);
			}
		}
		
		if ($row_table_def[notnull] == 't') {
			$strStyle = "style=\"font-weight:bold\"";
		} else {
			unset($strStyle);
		}


		$bgcolor = $cfgBgcolorOne;
		$i % 2  ? 0: $bgcolor = $cfgBgcolorTwo;
		echo "<tr bgcolor=".$bgcolor.">\n";
		echo "<td valign=\"top\" $strStyle>$field</td>\n";       
		echo "<td valign=\"top\">$row_table_def[type] <input type=hidden name=\"field_type[$field]\" value=\"$row_table_def[type]\"></td>\n";
		echo "<td valign=\"top\"><select name=\"funcs[$field]\"><option>\n";
		for ($j = 0; $j < count($cfgFunctions); $j++) {
			if (trim(strtoupper($cfgFunctions[$j])) == trim(strtoupper($selected_func))) {
				echo "<option selected>$cfgFunctions[$j]\n";
			} else {
				echo "<option>$cfgFunctions[$j]\n";
			}
		}
		echo "</select></td>\n";

		if ($show_ops) {
			echo "<td valign=\"top\">";
		
			if (eregi("char|text", $row_table_def[type])) {
				$selOp = "LIKE";
			} else {
				$selOp = "=";
			}
			
			echo select_box(array("name"=>"ops[$field]", "values"=>$arOps, "selected"=>$selOp));
			echo "</td>";
		}
		
		if (isset($row) && isset($row[$field]))
			$special_chars = htmlspecialchars($row[$field]);
		else
			unset($special_chars);
			
		if (strstr($row_table_def["type"], "text")) {
			echo "<td><textarea wrap=\"virtual\" name=\"fields[$field]\" style=\"width:$cfgMaxInputsize;\" rows=5>$special_chars</textarea></td>\n";
		} else if (strstr($row_table_def["type"], "varchar") && $len > 50) {
			echo "<td><textarea wrap=\"virtual\" name=\"fields[$field]\" style=\"width:$cfgMaxInputsize;\" rows=5>$special_chars</textarea></td>\n";
		} else if (strstr($row_table_def["type"], "bool")) {
			echo "<td><select name=\"fields[$field]\">";
			if ($special_chars == "'t'" || $special_chars == "t") {
				echo "<option value=\"t\" selected>$strYes";
				echo "<option value=\"f\">$strNo";
			} else if ($special_chars == "'f'" || $special_chars == "f"){
				echo "<option value=\"t\">$strYes";
				echo "<option value=\"f\" selected>$strNo";
			} else {
				echo "<option value=\"\">";
				echo "<option value=\"t\">$strYes";
				echo "<option value=\"f\">$strNo";
			}
			echo "</select></td>\n";
		} else {
			if (ereg("char", $row_table_def["type"])) {
				$maxlen = $len;
			} else {
				unset($maxlen);
			}
			echo "<td><input type=text name=\"fields[$field]\" value=\"".$special_chars."\" style=\"width:$cfgMaxInputsize;\"></td>"; // maxlength=$maxlen
		}
		echo "</tr>\n";
	}
echo "</table>";
}
?>
