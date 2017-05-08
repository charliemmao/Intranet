<?php
/* $Id: main.php,v 1.4 2000/12/21 17:37:40 dwilson Exp $ */

if (!isset($message)) {
	include("header.inc.php");
} else  {
	show_message($message);
}

echo "
	<script language=\"JavaScript\">
		if (this.name != \"main\") {
			location.href = \"index.php\";
		}
	</script>
";

echo "<h1>", $strWelcome, " ", $cfgProgName, " ", $cfgVersion, "</h1>";

if ($server > 0) {
	echo "<b>PostgreSQL $ver_num[0] $strRunning ";
	if (!$cfgServer[local]) {
		echo $cfgServer['host'];
	} else {
		echo "local";
	}
	echo ":" . $cfgServer['port'];

	echo "</b><br>\n";
}

if ($cfgServer['adv_auth'])
	echo "<br>$strLoggedInAs: <b>$PHP_PGADMIN_USER</b>";
?>
<div align="left">
<ul>
<?php
if (count($cfgServers) > 1) {
	echo "
		<li>
			<form action=\"index.php\" target=\"_top\">
	";
	
	reset($cfgServers);
	while(list($key, $val) = each($cfgServers)) {
		// echo "$key => $val[host]<br>";
		if ($val['local'] || !empty($val['host'])) {
			unset($host_display);
			if ($val['local']) {
				$host_display .= "local:" . $val['port'];
			} else {
				if (!empty($val['host'])) {
					$host_display .= $val['host'];
				}
				if (!empty($val['port'])) {
					$host_display .= ":" . $val['port'];
				}
			}
			$aryServers[$host_display] = $key;
		}
	}
	
	if (count($aryServers) > 1) {
		echo select_box(array("values"=>$aryServers, "name"=>"server", "selected"=>$server));
	}
	
	echo "<input type=\"submit\" value=\"$strGo\"><input type=\"hidden\" name=\"mode\" value=\"logout\"></form>";
}

if ($server > 0) {
	// Don't display server-related links if $server==0 (no server selected)
	if (empty($cfgServer['only_db'])) {
		if ($create_db == "t") {
?>
	
		<li>
		<form method="post" action="db_create.php">
		<?php echo $strCreateNewDatabase ?> <input type="Hidden" name="server" value="<?php echo $server ?>"><input type="hidden" name="reload" value="true"><input type="text" name="newdb" maxlength="31"><input type="submit" value="<?php echo $strCreate ?>">
		</form>
<?php 
		}
	}
}
?>
<li>
<a href="http://www.greatbridge.org/project/phppgadmin" target="_top">phpPgAdmin Homepage</a>
<li>
<a href="http://www.postgresql.org" target="new">PostgreSQL Homepage</a>
<li>
<a href="http://www.greatbridge.org/project/phppgadmin/download/download.php?branch=devel" target="_top">- Latest Code</a>
<li>
<a href="http://www.greatbridge.org/mailman/listinfo/phppgadmin" target="_top">- Mailing Lists</a>
<?php if ($cfgServer['adv_auth']) { ?>
<li>
<a target="_top" href="index.php?server=<?php echo $server;?>&mode=logout">- <?php echo $strLogout ?></a>
<?php } ?>
</ul>
</div>

<?php include ("footer.inc.php"); ?>
