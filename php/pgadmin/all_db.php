<?php
/* $Id: all_db.php,v 1.3 2001/03/03 18:05:22 dwilson Exp $ */

if (!isset($message)) {
	include("header.inc.php");
} else {
	show_message($message);
	unset($sql_query);
}

echo "<h1>$strAllDatabases</h1>";

if ($cfgUserDatabases && !$bSuperUser) {
	$qrUserOnly = " AND pg_get_userbyid(datdba) = CURRENT_USER";
} else {
	unset($qrUserOnly);
}

$sql_get_dbs = "
	SELECT datname, usename AS owner, pg_encoding_to_char(encoding) AS enc
	FROM pg_database d, pg_user u 
	WHERE d.datdba = u.usesysid AND datname NOT LIKE 'template_' $qrUserOnly
	UNION
	SELECT datname, NULL AS owner, pg_encoding_to_char(encoding) AS enc
	FROM pg_database
	WHERE datdba NOT IN (SELECT usesysid FROM pg_user) AND datname NOT LIKE 'template_' $qrUserOnly
";

$dbs = pg_exec($link, pre_query($sql_get_dbs)) or pg_die(pg_errormessage(), $sql_get_dbs, __FILE__, __LINE__);
$num_dbs = pg_numrows($dbs);

if ($num_dbs == 0) {
	echo "<br><b>$strNo $strDatabases $strFound</b><br>";
} else {
	$i = 0;

	echo "
		<table border=$cfgBorder>
			<tr bgcolor=lightgrey>
				<th>$strDatabase</th>
				<th>$strOwner</th>
				<th>$strEncoding</th>
				<th colspan=\"9\">$strAction</th>
			</tr>
		";

	for ($i = 0; $i < $num_dbs; $i++) {
		$db_array = pg_fetch_array($dbs, $i);
		$db = $db_array[datname];
		$j = $i+2;

		$conn_str = "user=$cfgServer[stduser] password=$cfgServer[stdpass] ";
		if (!$cfgServer[local]) {
			$conn_str .= "host=$cfgServer[host] ";
		}
		$conn_str .= "port=$cfgServer[port] dbname='$db'";

		if ($dbh_tbl = @pg_connect($conn_str)) { // or pg_die(pg_errormessage(), $conn_str);
			$bgcolor = $cfgBgcolorOne;
			$i % 2 ? 0 : $bgcolor = $cfgBgcolorTwo;
			?>
			<tr bgcolor="<?php echo $bgcolor; ?>">
				<td class="data">
					<a href="db_details.php?server=<?php echo $server; ?>&db=<?php echo $db; ?>"><?php echo trim($db);?></a>
				</td>
				<td class="data"><?php echo $db_array[owner]; ?></td>
				<td class="data"><?php echo $db_array[enc]; ?></td>
				<td class="data"><a href="db_privilege.php?server=<?php echo $server;?>&db=<?php echo $db; ?>&goto=all_db.php"><?php echo $strPrivileges; ?></a></td>
				<td class="data"><a href="sql.php?server=<?php echo $server;?>&db=template1&sql_query=<?php echo urlencode("DROP DATABASE $cfgQuotes$db$cfgQuotes");?>&zero_rows=<?php echo urlencode($strDatabase." ".$db." ".$strHasBeenDropped);?>&goto=all_db.php&reload=true"><?php echo $strDrop;?></a></td>
				<td class="data"><?php echo "<a href=db_details.php?db=$db&server=$server&rel_type=table>$strTables</a>"; ?></td>
				<td class="data"><?php echo "<a href=db_details.php?db=$db&server=$server&rel_type=view>$strViews</a>"; ?></td>
				<td class="data"><?php echo "<a href=db_details.php?db=$db&server=$server&rel_type=sequence>$strSequences</a>"; ?></td>
				<td class="data"><?php echo "<a href=db_details.php?db=$db&server=$server&rel_type=function>$strFuncs</a>"; ?></td>
				<td class="data"><?php echo "<a href=db_details.php?db=$db&server=$server&rel_type=index>$strIndicies</a>"; ?></td>
				<td class="data"><?php echo "<a href=db_details.php?db=$db&server=$server&rel_type=trigger>$strTriggers</a>"; ?></td>
				<td class="data"><?php echo "<a href=db_details.php?db=$db&server=$server&rel_type=operator>$strOperators</a>"; ?></td>
			</tr>
			<?php 
		}
	}
	echo "</table>";
}

if (empty($cfgServer['only_db'])) {
	if ($create_db == "t") {
?>
		<p>
		<li>
		<form method="post" action="db_create.php">
		<?php echo $strCreateNewDatabase ?> <input type="hidden" name="server" value="<?php echo $server ?>"><input type="hidden" name="reload" value="true"><input type="text" name="newdb" maxlength="31"><input type="submit" value="<?php echo $strCreate ?>">
		<input type="hidden" name="goto" value="all_db.php">
		</form>
<?php 
	}
}
?>