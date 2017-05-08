<?php
/* $Id: db_privilege.php,v 1.3 2001/02/02 06:03:08 dwilson Exp $ */
/* Used Parameters & possibles values :
 * action       : grant, revoke, grantuser, grantgroup
 * user         : $username, 'group $groupname'
 * todo         : grant, revoke
 * privileges[] : select, insert, delete, rule
 */

include("header.inc.php");

$query = "server=$server&db=$db&goto=db_details.php";
$arrPrivileges = array('select', 'insert', 'delete', 'rule');
$arrAcl        = array('r',      'a',      'w',      'R');

if (!$tables && !$seq && !$views && isset($todo)) {
	echo "You must select an object type (table, sequence, view) <a href=\"javascript:history.back()\">Back</a>";
	exit;
}
if (isset($todo)) {
	if (!$tables) {
		$strWhere .= " AND relkind != 'r'";
	}
	if (!$seq) {
		$strWhere .= " AND relkind != 'S'";
	}

	if ($views) {
		$strVwWhere .= " SELECT viewname FROM pg_views WHERE viewowner != '$cfgSuperUser'";
	}

	$qrGetObj = "
		SELECT relname FROM pg_class 
		WHERE 
			relname NOT LIKE 'pg\\_%' AND relkind NOT IN ('i','s')
			$strWhere
	";
	if (!empty($strVwWhere)) {
		$qrGetObj .= "UNION $strVwWhere";
	}
	
	// echo $qrGetObj, "<br>";
	
	$rsGetObj = @pg_exec($link, $qrGetObj) or pg_die(pg_errormessage(), $qrGetObj, __FILE__, __LINE__);
	for ($iObj = 0; $iObj < pg_numrows($rsGetObj); $iObj++) {
		$arGetObj = pg_fetch_array($rsGetObj, $iObj);
		$strObjList .= "$cfgQuotes$arGetObj[relname]$cfgQuotes, ";
	}
	
	$strObjList = ereg_replace(", $", "", $strObjList);
	
	if (!empty($strObjList)) {
		if ($todo == "grant") {
			$qrSetPriv = "GRANT ". implode(", ", $privileges) ." ON $strObjList TO ". rawurldecode($user);
		} elseif ($todo == "revoke") {
			$qrSetPriv = "REVOKE ". implode(", ", $privileges) ." ON $strObjList FROM ". rawurldecode($user);
		}
		
		pg_exec($link, $qrSetPriv) or pg_die(pg_errormessage(), $qrSetPriv, __FILE__, __LINE__);
		echo $strObjList, " $strHasBeenAltered!";
		
	} else {
		echo "No Objects found";
	}
}
	
if (!isset($action)) {

?>
	<br>
	<li><a href="db_privilege.php?<?php echo $query;?>&action=grantuser"><?php echo $strAddUser; ?></a>
	<li><a href="db_privilege.php?<?php echo $query;?>&action=grantgroup"><?php echo $strAddGroup; ?></a>
	<li><a href="db_privilege.php?<?php echo $query;?>&action=revokeuser"><?php echo $strRevokeUser; ?></a>
	<li><a href="db_privilege.php?<?php echo $query;?>&action=revokegroup"><?php echo $strRevokeGroup; ?></a>
<?php 

} else {
	$i = 0;
	while ($p = $arrPrivileges[$i]) {
		$cb_priv[$p] = '<input type="checkbox" name="privileges[]" value="'. "$p\"> ". ucfirst($p) ."</input>";
		$i++;
	}

	// $privileges = get_privilege($table);
	switch ($action) {
		case "revoke":
			$Expected =  $strNo;
			$Action = "revoke";
			$strToFrom = "from";
			break;
		case "grant":
			$Expected = $strYes;
			$Action = "grant";
			$strToFrom = "to";
			$name = rawurldecode($user);

			$i = 0;
			while ($p = $arrPrivileges[$i]) {
				if ($privileges[$name][$p] == $Expected) {
					unset($cb_priv[$p]); }
				$i++;
			}
			$user = "$cfgQuotes$name$cfgQuotes";
			$user = eregi_replace("${cfgQuotes}group ", "GROUP $cfgQuotes", $user);
			// $user = eregi_replace("${cfgQuotes}public$cfgQuotes", "PUBLIC", $user);
			$input_user = '<input type="hidden" name="user" value="'. rawurlencode($user) .'">';
			break;
		case "grantuser":
			$Expected = $strYes;
			$Action = "grant";
			$strToFrom = "to";
			break;
		case "revokeuser":
			$Expected =  $strNo;
			$Action = "revoke";
			$strToFrom = "from";
			break;
		case "grantgroup":
			$Expected = $strYes;
			$Action = "grant";
			$strToFrom = "to";
			break;
		case "revokegroup":
			$Expected =  $strNo;
			$Action = "revoke";
			$strToFrom = "from";
		}

		if (ereg("group", $action)) {
			$qrUsers = "SELECT 'group $cfgQuotes' || groname || '$cfgQuotes' AS thename FROM pg_group";
			@reset($privileges);
			while (list($key) = @each($privileges)) 
				if (ereg("^group (.+)$", $key, $regs))
					$tmp .=", '".$regs[1]."'";
			if (isset($tmp)) {
				$tmp[0] = '(';
				$qrUsers .= " WHERE groname NOT IN $tmp)";
			}
			$qrUsers .= " ORDER BY thename";
		} else {
			$qrUsers = "SELECT 'public'::text AS thename UNION SELECT '$cfgQuotes' || usename || '$cfgQuotes' AS thename FROM pg_user WHERE usename NOT IN ('root', '$cfgSuperUser'";
			@reset($privileges);
			while (list($key) = @each ($privileges))
				if (!ereg("group ", $key))
					$qrUsers .= ", '$key'";
			$qrUsers .= ") ORDER BY thename";
		}

		if (!empty($qrUsers)) {
			$res = @pg_exec($link, $qrUsers) or pg_die(pg_errormessage($link), $qrUsers, __FILE__, __LINE__);
			$name = '<select name="user">';
	        $num_rows = pg_numrows($res);
			for ($i = 0; $i < $num_rows; $i++) {
                $row = pg_fetch_array($res, $i);
				$name .= '<option value="'.rawurlencode($row['thename']) . '">'. $row['thename'] ."</option>";
			}
			$name .= "</select>\n";
		}
		
	unset($action);
?>

	<form method="post" action="db_privilege.php">
		<p><?php echo $Action;?></p>
		<p><?php 
			$i = 0;
			while ($p = $arrPrivileges[$i]) {
				if (isset($cb_priv[$p])) { 
					echo $cb_priv[$p], "<br>";
				}
				$i++;
			} ?> </p>
		<p>on <?php echo "$db $strToFrom $name"; ?></p>

		<input type="checkbox" name="tables" value="1" checked> <?php echo $strTables; ?>
		<input type="checkbox" name="seq" value="1"> <?php echo $strSequences; ?>
		<input type="checkbox" name="views" value="1"> <?php echo $strViews; ?>
		<br><br>
		<input type="hidden" name="server" value="<?php echo $server; ?>">
		<input type="hidden" name="table" value="<?php echo $table; ?>">
		<input type="hidden" name="db" value="<?php echo $db; ?>">
		<?php echo $input_user;?>
		<input type="submit" name="todo" value="<?php echo $Action;?>">
		<input type="button" value="<?php echo $strCancel;?>" onClick="history.back()">
	</form>
	<p>
<?php
}

include ("footer.inc.php");
?>
