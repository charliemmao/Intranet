<?php
/* $Id: func_edit.php,v 1.7 2001/02/02 06:03:08 dwilson Exp $ */

include("header.inc.php");

if (!isset($submit)) {
	if (!$create) {
		$strRevArgs = ereg_replace(",", " ", $arg_list);

		$sql_func_props = "
			SELECT 
				proname, 
				lanname as language,
				pt.typname as return_type,
				pa.typname as argtype,
				prosrc as source,
				oidvectortypes(pc.proargtypes) AS arguments
			FROM 
				pg_proc pc, pg_language pl, pg_type pt, pg_type pa
			WHERE 
				pc.oid = '$function_oid'::oid
				AND pc.prolang = pl.oid
				AND pc.prorettype = pt.oid
			UNION
			SELECT 
				proname, 
				lanname AS language,
				'OPAQUE' AS return_type,
				pa.typname AS argtype,
				prosrc AS source,
				oidvectortypes(pc.proargtypes) AS arguments
			FROM 
				pg_proc pc, pg_language pl, pg_type pa
			WHERE 
				pc.oid = '$function_oid'::oid
				AND pc.prolang = pl.oid
				AND pc.prorettype = 0
			";

		$res_props = pg_exec($link, pre_query($sql_func_props))	or pg_die(pg_errormessage(), $sql_func_props, __FILE__, __LINE__);
		$func = @pg_fetch_array($res_props, 0);
		$strSubmitAction = $strSave;
		$strResetBtn = "<input type=reset value=\"$strReset\">";
	} else {
		$strSubmitAction = $strCreate;
	}
	
	?>
	<form method=POST>
	<table border="<?php echo $cfgBorder; ?>">
	<tr>
		<th><?php echo $strFunc; ?></th>
		<th><?php echo $strArgs; ?></th>
		<th><?php echo $strRetType; ?></th>
		<th><?php echo $strLang; ?></th>
	</tr>
	<tr>
		<td>
			<input type="text" name="function" value="<?php echo $func[proname]; ?>">
			<input type="hidden" name="orig_function" value="<?php echo $func[proname]; ?>">
		</td>
		<td>
			<input type="text" name="arglist" value="<?php echo ereg_replace(" ", ", ", trim($func[arguments])); ?>">
			<input type="hidden" name="orig_arglist" value="<?php echo ereg_replace(" ", ", ", trim($func[arguments])); ?>">
		</td>
		<td>
			<select name="returns">
			<?php
			$sql_get_types = "
				SELECT typname 
				FROM pg_type pt
				WHERE typname NOT LIKE '\\\_%' AND typname NOT LIKE 'pg\\\_%'
				EXCEPT
				SELECT relname
				FROM pg_class
				WHERE relkind = 'S'
				ORDER BY typname
			";
			
			$types = pg_exec($link, pre_query($sql_get_types)) or pg_die(pg_errormessage(), $sql_get_types, __FILE__, __LINE__);
			if ($func[return_type] == "OPAQUE") {
				$strSelOp = " selected";
			} else {
				unset($strSelOp);
			}
			echo "<option value=\"OPAQUE\"$strSelOp>OPAQUE";
			
			for ($i_type = 0; $i_type < @pg_numrows($types); $i_type++) {
				$typename = pg_result($types, $i_type, "typname");
				if (trim($typename) == trim($func[return_type])) {
					$strSelType = " selected";
				} else {
					unset($strSelType);
				}
				echo "<option value=\"$typename\"$strSelType>$typename";
			}
			?>
			</select>
		</td>
		<td>
			<select name="language">
			<?php
			$sql_get_langs = "SELECT lanname FROM pg_language ORDER BY lanname DESC";
			$langs = pg_exec($link, pre_query($sql_get_langs)) or pg_die(pg_errormessage(), $sql_get_langs, __FILE__, __LINE__);
			for ($i_lang = 0; $i_lang < @pg_numrows($langs); $i_lang++) {
				$langname = pg_result($langs, $i_lang, "lanname");
				if ($func[language] == $langname) {
					$strSelLang = " selected";
				} else {
					unset($strSelLang);
				}
				echo "<option value=\"$langname\"$strSelLang>$langname";
			}
			?>
			</select>
		</td>
	</tr>
	<tr><th colspan=4><?php echo $strSrc; ?></th></tr>
	<tr>
		<td colspan=4><textarea name=source wrap=virtual cols=80 rows=10><?php echo $func[source]; ?></textarea></td>
	</tr>
	<tr>
		<td align=center colspan=4>
			<input type=submit name=submit value="<?php echo "$strSubmitAction $strFunc"; ?>">
			<?php echo "$strResetBtn"; ?>
			<input type="button" onClick="history.back()" value="<?php echo $strCancel; ?>">
		</td>
	</tr>
	</table>
	</form>
	<?php
} else {
	// $source = stripslashes($source);
	if (!empty($orig_function)) {
		$sql_query = "DROP FUNCTION $cfgQuotes$orig_function$cfgQuotes($orig_arglist);\n";
		$strZeroRows = "$strFunction $orig_function($orig_arglist) $strHasBeenAltered";
	} else {
		$strZeroRows = "$strFunction $function($arglist) $strHasBeenCreated";
	}
	if ($version < 7.1 || empty($version)) {
		$source = ereg_replace("\n|\r", " ", $source);
	}
	$sql_query .= "CREATE FUNCTION $cfgQuotes$function$cfgQuotes($arglist) RETURNS $returns AS '$source' LANGUAGE '$language'";
	echo $strDoYouReally . "<br>" . nl2br($sql_query);
	
	// $sql_query = ereg_replace("+", "
	
	?>
	<form method=POST action="sql.php">
	<input type=hidden name="db" value="<?php echo $db; ?>">
	<input type=hidden name="server" value="<?php echo $server; ?>">
	<input type=hidden name="sql_query" value="<?php echo htmlspecialchars($sql_query); ?>">
	<input type=hidden name="goto" value="db_details.php">
	<input type=hidden name="rel_type" value="function">
	<input type="hidden" name="zero_rows" value="<?php echo $strZeroRows; ?>">
	<input type="submit" name="btnDrop" value="<?php echo $strYes; ?>">
	<input type="submit" name="btnDrop" value="<?php echo $strNo; ?>">
	</form>
	<?php
}

include("footer.inc.php");
?>