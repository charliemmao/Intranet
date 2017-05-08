<?php

if (!isset($message)) {
	include("header.inc.php");
} else {
	show_message($message);
}

$sql_operator_props = "
	SELECT
      po.oid,
      po.oprname,
      po.oprcanhash,
      po.oprcode,
      po.oprrest,
      po.oprjoin,
      po.oprcom,
      po.oprnegate,
      po.oprlsortop as oprlsortop_orig,
      po.oprrsortop as oprrsortop_orig,
		(select typname from pg_type pt where pt.oid=po.oprleft) as leftarg,
		(select typname from pg_type pt where pt.oid=po.oprright) as rightarg,
		(select oprname from pg_operator po1 where po1.oid=po.oprcom) as commutator,
		(select oprname from pg_operator po1 where po1.oid=po.oprnegate) as negator,
      (select oprname from pg_operator po1 where po1.oid=po.oprlsortop) as oprlsortop,
      (select oprname from pg_operator po1 where po1.oid=po.oprrsortop) as oprrsortop,
		(select typname from pg_type pt where pt.oid=po.oprresult) as result
	FROM
		pg_operator po
	WHERE
		po.oid = '$operator_oid'
";

if (!$res_props = pg_exec($link, pre_query($sql_operator_props))) {
	pg_die(pg_errormessage(), $sql_operator_props, __FILE__, __LINE__);
} else {
	$row = @pg_fetch_array($res_props, 0);

	// Construct operator link
   $query = "?db=$db&server=$server&rel_type=operator&operator_oid=%s";
  	$op = "<a href=\"oper_properties.php$query\">%s</a>";

	$pr_query = "?db=$db&rel_type=operator&server=$server";

	?>
	<table border=<?php echo $cfgBorder;?>>
	<TR>
	<TH><?php echo $strProperty; ?></TH>
	<TH><?php echo $strValue; ?></TH>
	</TR>

	<tr bgcolor="<?php echo $cfgBgcolorOne;?>">
	<td><?php echo $strOperator; ?></td><td><?php echo htmlspecialchars($row[oprname]); ?></td></tr>
	<tr bgcolor="<?php echo $cfgBgcolorOne;?>">
	<td><?php echo $strFunc; ?></td><td><?php echo $row[oprcode]; ?></td></tr>
	<tr bgcolor="<?php echo $cfgBgcolorOne;?>">
	<td><?php echo "$strLeft $strArg"; ?></td><td><?php echo $row[leftarg]; ?></td></tr>
	<tr bgcolor="<?php echo $cfgBgcolorOne;?>">
	<td><?php echo "$strRight $strArg"; ?></td><td><?php echo $row[rightarg]; ?></td></tr>
	<tr bgcolor="<?php echo $cfgBgcolorOne;?>">
	<td><?php echo $strCommutator; ?></td><td><?php printf($op, $row[oprcom], htmlspecialchars($row[commutator])) ?></td></tr>
	<tr bgcolor="<?php echo $cfgBgcolorOne;?>">
	<td><?php echo $strNegator; ?></td><td><?php printf($op, $row[oprnegate], htmlspecialchars($row[negator])) ?></td></tr>
	<tr bgcolor="<?php echo $cfgBgcolorOne;?>">
	<td><?php echo "$strRestrict $strFunc"; ?></td><td><?php echo $row[oprrest]; ?></td></tr>
	<tr bgcolor="<?php echo $cfgBgcolorOne;?>">
	<td><?php echo "$strJoin $strFunc"; ?></td><td><?php echo $row[oprjoin]; ?></td></tr>
	<tr bgcolor="<?php echo $cfgBgcolorOne;?>">
	<td><?php echo "$strHashes?"; ?></td><td><?php echo ($row[oprcanhash] == 't') ? 'Yes' : 'No'; ?></td></tr>
	<tr bgcolor="<?php echo $cfgBgcolorOne;?>">
	<td><?php echo "$strLeft $strSort $strOperator"; ?></td><td><?php printf($op, $row[oprlsortop_orig], htmlspecialchars($row[oprlsortop])) ?></td></tr>
	<tr bgcolor="<?php echo $cfgBgcolorOne;?>">
	<td><?php echo "$strRight $strSort $strOperator"; ?></td><td><?php printf($op, $row[oprrsortop_orig], htmlspecialchars($row[oprrsortop])) ?></td></tr>

	</table>
	<br><br>
   <?php
      $leftarg = ($row[leftarg] == '') ? 'none' : $row[leftarg];
      $rightarg = ($row[rightarg] == '') ? 'none' : $row[rightarg];
   ?>
	<li><a href="sql.php<?php echo $pr_query;?>&goto=db_details.php&sql_query=<?php echo urlencode("DROP OPERATOR $row[oprname] ($leftarg, $rightarg)");?>&zero_rows=<?php echo urlencode("$strOperator $row[oprname] $strHasBeenDropped");?>"><?php echo $strDrop; ?>
	<li><a href=db_details.php?db=<?php echo $db; ?>&server=<?php echo $server; ?>&rel_type=operator><?php echo "$strDisplay $strOperators"; ?></a>
	<?php
}

include ("footer.inc.php");
?>
