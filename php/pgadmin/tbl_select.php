<?php
/* $Id: tbl_select.php,v 1.7 2001/02/02 06:03:08 dwilson Exp $ */


include("header.inc.php");

if(!isset($param) || empty($param[0])) {
	if (!empty($view)) {
		$table = $view;
	}
	$sql_get_fields = "SELECT * FROM $cfgQuotes$table$cfgQuotes LIMIT 1";
	if (!$result = pg_exec($link, pre_query($sql_get_fields))) {
		pg_die(pg_errormessage(), $sql_get_fields, __FILE__, __LINE__);
	} else {
?>

		<form method="POST" ACTION="tbl_select.php">
		<input type="hidden" name="server" value="<?php echo $server;?>" >
		<input type="hidden" name="db" value="<?php echo $db;?>" >  
		<input type="hidden" name="table" value="<?php echo $table;?>" >
		<input type="hidden" name="goto" value="<?php echo $goto; ?>">
		<?php echo $strSelectFields; ?><br>
		<select multiple NAME="param[]" size="10">
		<?php
		for ($i = 0; $i < pg_numfields($result); $i++) {
			$field = pg_fieldname($result,$i);
			if($i >= 0) 
				echo "<option value=\"$field\" selected>$field</option>\n";
			else
				echo "<option value=\"$field\">$field</option>\n";
		}
		?>
		</select><br>
		<div align="left">
		<ul>
		<li><?php echo $strAddSearchConditions; ?><br>
		<input type="text" name="where"> <?php print show_docu("sql-select.htm#SQL-WHERE");?><br>
	
		<br>
		<li><?php echo $strDoAQuery; ?><br>
		
		<?php 
		
		$show_defaults = false;
		
		$show_ops = true;
		
		include("tbl_form.inc.php"); 
		
		?>

		<!--table border="<?php echo $cfgBorder;?>">
		<tr>
		<th><?php echo $strField; ?></th>
		<th><?php echo $strType; ?></th>
		<th><?php echo $strValue; ?></th>
		</tr>
		<?php
		$result = pg_exec($link, pre_query($sql_get_fields));
		for($i = 0; $i < pg_numfields($result); $i++) {
			$field = pg_fieldname($result, $i);
			$type = pg_fieldtype($result, $i);
			$len = pg_fieldsize($result, $i);
			
			if ($len < 1) {
				$len_disp = "var";
				$len = 50;
			} else {
				$len_disp = $len;
			}

			$bgcolor = $cfgBgcolorOne;
			$i % 2  ? 0: $bgcolor = $cfgBgcolorTwo;
			
			echo "<tr bgcolor=".$bgcolor.">";
			echo "<td>$field</td>";       
			echo "<td>$type ($len_disp)</td>";
			if ($type == "bool") {
				echo "<td><select name=fields[]><option value=\"t\">True<option value=\"f\">False</select></td>";
			} else {
				echo "<td><input type=text name=fields[] style=\"width: ".$cfgMaxInputsize."\" maxlength=".$len."></td>\n";
			}
			echo "<input type=hidden name=names[] value=\"$field\">\n";
			echo "<input type=hidden name=types[] value=\"$type\">\n";
			echo "</tr>";
		}
		echo "</table><br-->";
		?>
		
		<input name="SUBMIT" value="<?php echo $strGo; ?>" type="SUBMIT">
		</form></ul>
		
	<?php
	}
	include ("footer.inc.php");
} else {
	$sql_query = "SELECT $cfgQuotes$param[0]$cfgQuotes";

	$c = count($param);
	for ($i = 0; $i < $c; $i++) {
		if ( $i > 0) $sql_query .= ", $cfgQuotes$param[$i]$cfgQuotes";
	}

	$sql_query .= " FROM $cfgQuotes$table$cfgQuotes";

	if (!empty($where)) {
		$sql_query .= " WHERE $where";
	} else {
		while (list($field_name, $field_val) = each($fields)) {
			if (!empty($field_val)) {
				if (eregi("char|text|blob|bool|date|name", $field_type[$field_name])) {
				//	$compare = "LIKE";
				//	$quote = "'";
				} else {
				//	$compare = "=";
					unset($quote);
				}
				
				$compare = $ops[$field_name];

				$where .= "$cfgQuotes$field_name$cfgQuotes $compare $quote$field_val$quote AND ";
			}
		}
		
		$where = ereg_replace(" AND $", "", $where);
		
		if (!empty($where)) {
			$sql_query .= " WHERE $where";
		}

	}
	$goto = "db_details.php";
	include("sql.php");
}
?>
