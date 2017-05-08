<?php
/* $Id: trig_properties.php,v 1.3 2001/02/02 06:03:08 dwilson Exp $ */

if (!isset($message)) {
	include("header.inc.php");
} else {
	show_message($message);
}

$sql_trigger_props = "
	SELECT 
		pt.oid,
		pt.*, 
		pp.proname, 
		pc.relname, 
		py.typname
	FROM 
		pg_trigger pt, pg_proc pp, pg_class pc, pg_type py
	WHERE 
		pp.oid = pt.tgfoid
		AND pt.tgtype = py.oid
		AND pt.tgrelid = pc.oid
		AND tgname = '$trigger'
";

if (!$res_props = pg_exec($link, pre_query($sql_trigger_props))) {
	pg_die(pg_errormessage(), $sql_trigger_props, __FILE__, __LINE__);
} else {
	$row = @pg_fetch_array($res_props, 0);

	// Construct function definition
	$query = "?db=$db&server=$server&rel_type=function&function_oid=$row[tgfoid]";
	$fn = "<a href=\"func_properties.php$query\">" . $row[proname] . "</a>";

	// Strip off trailing delimiter
	$tgargs = trim(substr($row[tgargs], 0, strlen($row[tgargs]) - 4));
	$params = explode('\000', $tgargs);

	for ($i = 0; $i < sizeof($params); $i++) {
		$params[$i] = str_replace("'", "\\'", $params[$i]);
	}
	$defn =  implode("', '", $params);

	$tg_query = "?db=$db&rel_type=trigger&server=$server";
		
	?>
	<table border=<?php echo $cfgBorder;?>>
	<TR>
	<TH><?php echo $strTrigger; ?></TH>
	<TH><?php echo $strTable; ?></TH>
	<TH><?php echo $strRetType; ?></TH>
	<TH><?php echo $strEnabled; ?></TH>
	<TH><?php echo $strIsConstraint; ?></TH>
	<TH><?php echo $strConstraintName; ?></TH>
	<TH><?php echo $strDeferrable; ?></TH>
	<TH><?php echo $strInitDeferred; ?></TH>

	</TR>

	<tr bgcolor="<?php echo $cfgBgcolorOne;?>">
	<td class=data><b><?php echo $row[tgname];?></b></td>
	<td><?php echo $row[relname]; ?></td>
	<td><?php echo $row[typname]; ?></td>
	<td><?php echo ($row[tgenabled] == 't') ? 'Y' : 'N'; ?></td>
	<td><?php echo ($row[tgisconstraint] == 't') ? 'Y' : 'N'; ?></td>
	<td><?php echo $row[tgconstrname]; ?></td>
	<td><?php echo ($row[tgdeferrable] == 't') ? 'Y' : 'N'; ?></td>
	<td><?php echo ($row[tginitdeferred] == 't') ? 'Y' : 'N'; ?></td>
	<tr><th colspan=8><?php echo $strFunc;?></th></tr>
	<tr>
		<td colspan=8 bgcolor="<?php echo $cfgBgcolorTwo; ?>"><?php echo $fn, " ('", htmlspecialchars($defn), "')"; ?></td>
	</tr>
	</table>
	<br><br>
	<!--li><a href="trig_edit.php<?php echo $tg_query; ?>"><?php echo $strChange; ?></a-->
	<li><a href="sql.php<?php echo $tg_query;?>&goto=db_details.php&sql_query=<?php echo urlencode("DROP TRIGGER $row[tgname] ON $row[relname]");?>&zero_rows=<?php echo urlencode("$strTrigger $row[tgname] $strHasBeenDropped");?>"><?php echo $strDrop; ?>
	<li><a href=db_details.php?db=<?php echo $db; ?>&server=<?php echo $server; ?>&rel_type=trigger><?php echo "$strDisplay $strTriggers"; ?></a>
	<?php
}

include ("footer.inc.php");
?>

