<?php
/* $Id: tbl_properties.php,v 1.6 2001/02/02 06:03:08 dwilson Exp $ */

if (!isset($message)) {
	include("header.inc.php");
} else {
	show_message($message);
}

if (!empty($view)) 
	$table = $view;
   
$sql_get_fields = "
	SELECT 
		a.attnum,
		a.attname AS field, 
		t.typname AS type, 
		a.attlen AS length,
		a.atttypmod AS lengthvar,
		a.attnotnull AS notnull
	FROM 
		pg_class c, 
		pg_attribute a, 
		pg_type t
	WHERE 
		c.relname = '$table'
		and a.attnum > 0
		and a.attrelid = c.oid
		and a.atttypid = t.oid
	ORDER BY a.attnum
";

if (!$res_fields = @pg_exec($link, pre_query($sql_get_fields))) {
	pg_die(pg_errormessage(), $sql_get_fields, __FILE__, __LINE__);
} else {
	$num_rows = pg_numrows($res_fields);
	
	if (!empty($view)) {
		$sql_get_viewdef = "SELECT viewname, definition FROM pg_views WHERE viewname = '" . $table . "'";
		$res_viewdef = @pg_exec($link, pre_query($sql_get_viewdef));
		
		if (@pg_numrows($res_viewdef)) {
			$row = pg_fetch_array($res_viewdef, 0);
			//echo "<b>$strView $strDefinition:</b><br>", ereg_replace("\"", "", $row[definition]), "<p>\n";
			echo "<b>$strView $strDefinition:</b><br>", $row[definition], "<p>\n";
		}
	}
	
	?>
	<table border=<?php echo $cfgBorder;?>>
	<TR>
	<TH><?php echo $strField; ?></TH>
	<TH><?php echo $strType; ?></TH>
	<TH><?php echo $strLength; ?></TH>
	<TH><?php echo $strNotNull; ?></TH>
	<TH><?php echo $strDefault; ?></TH>
	<?php if (!$printview) { ?>
	<TH COLSPAN=4><?php echo $strAction; ?></TH>
	<?php } ?>
	</TR>

	<?php
	$i=0;
	if (empty($rel_type)) {
		$rel_type = "table";
	}
	for ($i_numrows = 0; $i_numrows < $num_rows; $i_numrows++) {
		$row = pg_fetch_array($res_fields, $i_numrows);
		$query = "server=$server&db=$db&$rel_type=$table&goto=tbl_properties.php";
		$bgcolor = $cfgBgcolorOne;
		$i % 2  ? 0: $bgcolor = $cfgBgcolorTwo;
		$i++;
		if ($row[type] == "bpchar") {
			// Internally stored as bpchar, but isn't accepted in a CREATE TABLE
			$row[type] = "char";
		}
		?>
		<tr bgcolor="<?php echo $bgcolor;?>">     
		<td valign="top"><?php echo $row[field];?>&nbsp;</td>
		<td valign="top"><?php echo $row[type]; ?>&nbsp;</td>
		<?php
		if (trim($row[type]) == "numeric") {
			echo "<td align=right valign=\"top\">";
			printf("%s,%s", ($row[lengthvar] >> 16) & 0xffff, ($row[lengthvar] - 4) & 0xffff);
			echo " &nbsp;</td>";
		} else {
			if ($row[length] > 0) {
				$i_length = $row[length];
			} else if($row[lengthvar] > 0) {
				$i_length = $row[lengthvar] - 4;
			} else {
				$i_length = "var";
			}
		echo "<td valign=\"top\" align=right>$i_length &nbsp;</td>";
		}
		?>
		<td valign="top"><?php echo bool_YesNo($row[notnull]); ?>&nbsp;</td>
		<?php
		$sql_get_default = "
			SELECT d.adsrc AS rowdefault
			FROM pg_attrdef d, pg_class c 
			WHERE 
				c.relname = '$table' AND 
				c.oid = d.adrelid AND
				d.adnum = $row[attnum]
		";
		if (!$def_res = pg_exec($link, pre_query($sql_get_default))) {
			pg_die(pg_errormessage(), $sql_get_default, __FILE__, __LINE__);
			$row[rowdefault] = "";
		} else {
			$row[rowdefault] = @pg_result($def_res, 0, "rowdefault");
		}
		?>
		<td valign="top"><?php echo htmlentities($row[rowdefault]); ?>&nbsp;</td>
		<?php if (!$printview) { ?>
		<td valign="top"><a href="tbl_alter.php?<?php echo $query;?>&field=<?php echo $row[field];?>"><?php echo $strChange; ?></a></td>
		<td valign="top"><a href="tbl_alter_drop.php?<?php echo $query;?>&field=<?php echo $row[field];?>"><?php echo $strDrop; ?></a></td>
		<td valign="top"><a href="sql.php?<?php echo $query;?>&sql_query=<?php echo urlencode("CREATE INDEX $cfgQuotes" . $row[field] . "_" . $table . "_key$cfgQuotes ON $cfgQuotes$table$cfgQuotes($cfgQuotes".$row[field]."$cfgQuotes)");?>&zero_rows=<?php echo urlencode($strAnIndex.$row[field]);?>"><?php echo $strIndex; ?></a></td>
		<td valign="top"><a href="sql.php?<?php echo $query;?>&sql_query=<?php echo urlencode("CREATE UNIQUE INDEX $cfgQuotes" . $row[field] . "_" . $table . "_ukey$cfgQuotes ON $cfgQuotes$table$cfgQuotes($cfgQuotes".$row[field]."$cfgQuotes)");?>&zero_rows=<?php echo urlencode($strAnIndex.$row[field]);?>"><?php echo $strUnique; ?></a></td>
		<?php } // end print view ?>
		</tr>
		<?php
	}
   ?>
   </table>
   <?php
   }
?>
<?php


$sql_pri_keys = "
		SELECT 
			ic.relname AS index_name, 
			bc.relname AS tab_name, 
			ta.attname AS column_name,
			i.indisunique AS unique_key,
			i.indisprimary AS primary_key
		FROM 
			pg_class bc,
			pg_class ic,
			pg_index i,
			pg_attribute ta,
			pg_attribute ia
		WHERE 
			bc.oid = i.indrelid
			AND ic.oid = i.indexrelid
			AND ia.attrelid = i.indexrelid
			AND ta.attrelid = bc.oid
			AND bc.relname = '$table'
			AND ta.attrelid = i.indrelid
			AND ta.attnum = i.indkey[ia.attnum-1]
		ORDER BY 
			index_name, tab_name, column_name
	";

// echo $sql_pri_keys;

if (!$pri_result = pg_exec($link, pre_query($sql_pri_keys))) {
	pg_die(pg_errormessage(), $sql_pri_keys, __FILE__, __LINE__);
} else {
	$num_keys = @pg_numrows($pri_result);
//	echo "Num keys: ", $num_keys;
	if ($num_keys > 0) {
		?>
		<br>
		<table border=<?php echo $cfgBorder;?>>
		<tr>
		<th><?php echo $strKeyname; ?></th>
		<th><?php echo $strUnique; ?></th>
		<th><?php echo $strPrimary; ?></th>
		<th><?php echo $strField; ?></th>
		<?php if (!$printview) { ?>
		<th><?php echo $strAction; ?></th>
		<?php } // end printview ?>
		</tr>
		<?php
		for ($i = 0; $i < $num_keys; $i++) {
			$row = pg_fetch_array($pri_result, $i);
			echo "<tr>";
			$sql_query = urlencode("DROP INDEX $cfgQuotes".$row[index_name]."$cfgQuotes");
			$zero_rows = urlencode("");
			?>
			<td><?php echo $row[index_name];?></td>
			<td><?php echo bool_YesNo($row[unique_key]); ?></td>
			<td><?php echo bool_YesNo($row[primary_key]); ?></td>
			<td><?php echo $row[column_name];?></td>
			<?php if (!$printview) { ?>
			<td><?php echo "<a href=\"sql.php?$query&sql_query=$sql_query&zero_rows=$zero_rows\">$strDrop</a>";?></td>
			<?php
			} // end printview
			echo "</tr>";
		}
		print "</table>\n";
		if (!$printview) {
			print show_docu("indices.htm"); 
		}
	}
}

if (!$printview) {


$drop_query = "server=$server&db=$db&goto=db_details.php&reload=true";
?>

<div align="left">
<ul>
<li><a href="tbl_properties.php?printview=1&<?php echo $query;?>"><?php echo $strPrintScreen; ?></a>
<li><a href="sql.php?sql_query=<?php echo urlencode("SELECT * FROM $cfgQuotes$table$cfgQuotes");?>&<?php echo $query;?>"><?php echo $strBrowse; ?></a>
<li><a href="tbl_select.php?<?php echo $query;?>"><?php echo $strSelect; ?></a>
<li><a href="tbl_change.php?<?php echo $query;?>"><?php echo $strInsert; ?></a>
<li><a href="sql.php?sql_query=<?php echo urlencode("DROP TABLE $cfgQuotes$table$cfgQuotes");?>&<?php echo $drop_query;?>"><?php echo $strDrop; ?></a>
<li><a href="tbl_privilege.php?<?php echo $query;?>"><?php echo $strPrivileges; ?></a>
<li>
	<form method="post" action="tbl_addfield.php">
		<?php echo $strAddNewField; ?>: 
		<select name="num_fields">
		<?php 
		for ($i=1; $i<=5; $i++) 
			echo "<option value=\"$i\">$i</option>";
		?>
		</select>
		<input type="submit" value="<?php echo $strGo;?>">
		<input type="hidden" name="table" value="<?php echo $table; ?>">
		<input type="hidden" name="db" value="<?php echo $db; ?>">
		<input type="hidden" name="server" value="<?php echo $server; ?>">
	</form>
<!--li><a href="ldi_table.php?<?php echo $query;?>"><?php echo $strInsertTextfiles; ?></a-->
<li><form method="post" action="tbl_dump.php"><?php echo $strViewDump;?><br>
<table>
    <tr>
        <td>
            <input type="radio" name="what" value="structure" checked><?php echo $strStrucOnly;?>
        </td>
        <td>
            <!--input type="checkbox" name="drop" value="1"><?php echo $strStrucDrop;?> -->
        </td>
        <td colspan="3">
            <input type="submit" value="<?php echo $strGo;?>">
        </td>
    </tr>
    <tr>
        <td>
            <input type="radio" name="what" value="data"><?php echo $strStrucData;?>
        </td>
        <td>
            <input type="checkbox" name="asfile" value="sendit"><?php echo $strSend;?>
        </td>
    </tr>
    <tr>
        <td>
            <input type="radio" name="what" value="csv"><?php echo $strStrucCSV;?>
        </td>
        <td>
            <?php echo $strTerminatedBy;?> <input type="text" name="separator" size=1 value=";">
        </td>
    </tr>
</table>

 <input type="hidden" name="server" value="<?php echo $server;?>">
 <input type="hidden" name="db" value="<?php echo $db;?>">
 <input type="hidden" name="table" value="<?php echo $table;?>">
</form>

<li><form method="post" action="tbl_rename.php"><?php echo $strRenameTable;?>:<br>
 <input type="hidden" name="server" value="<?php echo $server;?>">
 <input type="hidden" name="db" value="<?php echo $db;?>">
 <input type="hidden" name="table" value="<?php echo $table;?>">
 <input type="hidden" name="reload" value="true"> 
 <input type="text" name="new_name"><input type="submit" value="<?php echo $strGo;?>">
</form>
<li><form method="post" action="tbl_copy.php"><?php echo $strCopyTable;?><br>
 <input type="hidden" name="server" value="<?php echo $server;?>">
 <input type="hidden" name="db" value="<?php echo $db;?>">
 <input type="hidden" name="table" value="<?php echo $table;?>">
 <input type="hidden" name="reload" value="true">
 <input type="text" name="new_name"><br>
 <input type="radio" name="what" value="structure" checked><?php echo $strStrucOnly;?>
 <input type="radio" name="what" value="data"><?php echo $strStrucData;?>
 <input type="submit" value="<?php echo $strGo;?>">
</form>

</ul>
</div>
<?php

} else {
	echo "<script language=\"JavaScript1.2\">window.print()</script>";
} // end the printview

include ("footer.inc.php");
?>
