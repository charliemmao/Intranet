<?php

if (!isset($message)) {
	include("header.inc.php");
} else {
	show_message($message);
}

$strRevArgs = ereg_replace(",", " ", $arg_list);

$sql_func_props = "
	SELECT 
		pc.oid,
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
		pc.oid,
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
	
// echo $sql_func_props;	
$res_props = pg_exec($link, pre_query($sql_func_props)) or pg_die(pg_errormessage(), $sql_func_props, __FILE__, __LINE__);

$row = @pg_fetch_array($res_props, 0);

$strArgList = ereg_replace(" ", ", ", $row[arguments]);

$query = "?server=$server&db=$db&rel_type=$rel_type&function_oid=$row[oid]";
$func_sql = "$cfgQuotes$row[proname]$cfgQuotes" . "($strArgList)";

?>
<table border=<?php echo $cfgBorder;?>>
<TR>
<TH><?php echo $strFunc; ?></TH>
<TH><?php echo $strRetType; ?></TH>
<TH><?php echo $strLang; ?></TH>
<TH><?php echo $strArgs; ?></TH>
</TR>

<tr bgcolor="<?php echo $cfgBgcolorOne;?>">
<td class=data><b><?php echo $row[proname];?></b></td>
<td><?php echo $row[return_type]; ?></td>
<td><?php echo $row[language]; ?></td>
<?php
$strArgList = ereg_replace(", $", "", $strArgList); //"
echo "<td>$strArgList&nbsp</td>";
?>
<tr><th colspan=4><?php echo $strSrc;?></th></tr>
<tr>
	<td colspan=4 bgcolor="<?php echo $cfgBgcolorTwo; ?>"><?php echo nl2br($row[source]); ?></td>
</tr>
</table>
<br><br>
<li><a href="func_edit.php<?php echo $query; ?>"><?php echo $strChange; ?></a>
<li><a href="sql.php<?php echo $query;?>&goto=db_details.php&sql_query=<?php echo urlencode("DROP FUNCTION $func_sql");?>&zero_rows=<?php echo urlencode("$strFunc $function $strHasBeenDropped.");?>"><?php echo $strDrop; ?>
<?php include ("footer.inc.php"); ?>
