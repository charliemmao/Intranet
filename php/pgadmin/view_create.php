<?php
/* $Id: view_create.php,v 1.6 2001/02/02 06:03:08 dwilson Exp $ */

include("header.inc.php");

if (empty($submit)) {
	$get_tables = "
		SELECT relname 
		FROM pg_class 
		WHERE 
			relname NOT LIKE 'pg%'
			AND relkind != 'S'
			AND relkind != 'i'
		ORDER BY relname
	";
	$tables = pg_exec($link, pre_query($get_tables)) or pg_die(pg_errormessage(), $get_tables, __FILE__, __LINE__);
	$num_tables = @pg_numrows($tables);
?>
	<table border="<?php echo $cfgBorder; ?>">
		<form method=POST>
		<tr><th><?php echo $strTables ?></th></tr>
		<tr>
			<td>
				<select name="sel_tables[]" multiple size=10>
			<?php
				for ($i_tbls = 0; $i_tbls < $num_tables; $i_tbls++) {
					$tablename = @pg_result($tables, $i_tbls, "relname");
					echo "<option value=\"$tablename\">$tablename";
				}
			?>
				</select>
			</td>
		</tr>
		<tr>
			<td align=center>
				<input type=submit name="submit" value="<?php echo $strNext; ?>">
				<input type=reset>
			<td>
		</tr>
		</form>
	</table>

<?php
} else if ($submit == "$strNext") {
	?>
	<form method=POST>
		<?php echo $strView, " ", $strName; ?>
		<br>
		<input type=text name="view_name" value=""><br>
		<?php echo $strFields, "<br>"; ?>
		<select name="sel_fields[]" multiple size=10>
	<?php
	
	for ($i_tabs = 0; $i_tabs < count($sel_tables); $i_tabs++) {
		$strSelTables .= "<input type=hidden name=\"sel_tables[]\" value=\"$sel_tables[$i_tabs]\">\n";
		$sql_get_fields = "
			SELECT 
				a.attnum,
				a.attname AS field
			FROM 
				pg_class c, pg_attribute a
			WHERE 
				c.relname = '$sel_tables[$i_tabs]'
				AND a.attnum > 0
				AND a.attrelid = c.oid
			ORDER BY
				attnum
			";
		$fields = @pg_exec($link, pre_query($sql_get_fields)) or pg_die(pg_errormessage(), $sql_get_fields, __FILE__, __LINE__);
		$num_fields = @pg_numrows($fields);
		for ($i_field = 0; $i_field < $num_fields; $i_field++) {
			$field = pg_result($fields, $i_field, "field");
			echo "<option value='" . addslashes("$cfgQuotes$sel_tables[$i_tabs]$cfgQuotes.$cfgQuotes$field$cfgQuotes") . "'>$cfgQuotes$sel_tables[$i_tabs]$cfgQuotes.$cfgQuotes$field$cfgQuotes\n";
			$aryField[] = "$cfgQuotes$sel_tables[$i_tabs]$cfgQuotes.$cfgQuotes$field$cfgQuotes";
		}
	}
	?>
		</select>
		
		<br>
		<?php 
	
			$arFields[] = " ";	
			for ($i_prim = 0; $i_prim < count($aryField); $i_prim++) {
				$arFields[] = $aryField[$i_prim];
				// echo "<option value='" . addslashes($aryField[$i_prim]) . "'>$aryField[$i_prim]\n";
			}

			echo $strLinkKeys, "<br>";
			echo $strSelTables;
			for ($i_tables = 0; $i_tables < count($sel_tables) + 1; $i_tables++) {
				echo select_box(array("name"=>"primary1[]", "values"=>$arFields));
				echo "<==>";
				echo select_box(array("name"=>"primary2[]", "values"=>$arFields));
				echo "<br>\n";
			}
				
			$arOps[] = "=";
			$arOps[] = "LIKE";
			$arOps[] = "!=";
			$arOps[] = ">";
			$arOps[] = "<";
			$arOps[] = "IN";
			$arOps[] = "!!=";
			$arOps[] = "BETWEEN";
	
			echo "<br>$strAddConditions: <br>";
	
			for ($iCond = 0; $iCond < 3; $iCond++) {
				echo select_box(array("name"=>"where_fields[]", "values"=>$arFields));
				echo select_box(array("name"=>"where_ops[]", "values"=>$arOps));
				echo "<input type=\"text\" name=\"where[]\"><br>";
			}

		?>
		<br>
		<br>
		<input type="submit" name="submit" value="<?php echo "$strNext >>"; ?>">
		&nbsp;&nbsp;
		<input type="reset">
	</form>
	<?php

} else {
	while (list($var, $val) = each($HTTP_POST_VARS)) {
		if (is_array($val)) {
			while (list($var2, $val2) = each($val)) {
			//	echo "array $var2: $val2<br>";
			}
		}
		// echo "$var: $val<br>";
	}

	for ($i_fields = 0; $i_fields < count($sel_fields); $i_fields++) {
		// echo $sel_fields[$i_fields], "<br>";
		$strSelFields .= stripslashes($sel_fields[$i_fields]) . ", ";
	}
	$strSelFields = ereg_replace(", $", "", $strSelFields);

	for ($i_keys = 0; $i_keys < count($primary1); $i_keys++) {
		if (!empty($primary1[$i_keys]) && $primary1[$i_keys] != "0") {
		//	echo $primary1[$i_keys], " ==> ", $primary2[$i_keys],  "<br>";
			$strKeys .= stripslashes($primary1[$i_keys]) . " = " . stripslashes($primary2[$i_keys]) . " AND ";
		}
	}
	// $strKeys = ereg_replace("AND $", "", $strKeys);

	for ($iWhere = 0; $iWhere < count($where); $iWhere++) {
		if (!empty($where[$iWhere]) && !empty($where_fields[$iWhere])) {
			$strWhere .= "$where_fields[$iWhere] $where_ops[$iWhere] $where[$iWhere] AND ";
		}
	}
	
	$strWhere = ereg_replace("AND $", "", $strKeys . $strWhere);
	
	for ($i_tables = 0; $i_tables < count($sel_tables); $i_tables++) {
		$strSelTables .= "$cfgQuotes" . stripslashes($sel_tables[$i_tables]) . "$cfgQuotes, ";
	}
	$strSelTables = ereg_replace(", $", "", $strSelTables);
	
	$sql_create_view = "CREATE VIEW $cfgQuotes$view_name$cfgQuotes AS SELECT $strSelFields FROM $strSelTables";
	
	if (!empty($strWhere)) {
		$sql_create_view .= " WHERE $strWhere";
	} else {
	//	echo "WHERE: $strWhere";
	}
	
	$sql_query = $sql_create_view;
	$rel_type = "view";
	$goto = "db_details.php";
	include("sql.php");
	// echo "<pre>$sql_create_view</pre>";
}

include("footer.inc.php");
?>
