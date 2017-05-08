<?php
/* $Id: tbl_privilege.php,v 1.8 2001/02/04 03:23:50 kkemp102294 Exp $ */
/* Used Parameters & possibles values :
 * action       : grant, revoke, grantuser, grantgroup
 * user         : $username, 'group $groupname'
 * todo         : grant, revoke
 * privileges[] : select, insert, delete, rule
 */

include("header.inc.php");

$query = "server=$server&db=$db&table=$table&goto=tbl_privilege.php";
$arrPrivileges = array('select', 'insert', 'delete', 'rule');
$arrAcl        = array('r',      'a',      'w',      'R');

function get_privilege ($table) {
	global $link, $strYes, $strNo, $arrPrivileges, $arrAcl;
	$sql_get_privilege = "SELECT relacl FROM pg_class WHERE relname = '$table'";

	if (!$res = @pg_exec($link, $sql_get_privilege)) {
		pg_die(pg_errormessage($link), $sql_get_privilege, __FILE__, __LINE__);
	} else {
		// query must return exactely one row (check this ?)
		$row = pg_fetch_array($res, 0);
		$priv = trim(ereg_replace("[\{\"]", "", $row[relacl]));
		
		$users = explode(",", $priv);
		for ($iUsers = 0; $iUsers < count($users); $iUsers++) {
			$aryUser = explode("=", $users[$iUsers]);
			$privilege = $aryUser[1]; 

			for ($i = 0; $i < 4; $i++) {
				// $result[$username][$arrPrivileges[$i]] = strchr($privilege, $arrAcl[$i]) ? $strYes : $strNo;
				$aryUser[0] = $aryUser[0] ? $aryUser[0] : "public";
				// echo $aryUser[0], ": ", $arrAcl[$i], ":", $privilege, "<br>";
				$result[trim($aryUser[0])][$arrPrivileges[$i]] = strchr($privilege, $arrAcl[$i]) ? $strYes : $strNo;
			}
		}
	}
	return $result;
}

function set_privilege ($sql_set_privilege) {
	global $link;
	if (!$res = @pg_exec($link, $sql_set_privilege)) {
		pg_die(pg_errormessage($link), $sql_set_privilege, __FILE__, __LINE__);
	} else {
		echo "<p>$sql_set_privilege</p>\n";
	}
}

if ($todo == "grant") {
	set_privilege ("GRANT ". implode(", ", $privileges) ." ON $cfgQuotes$table$cfgQuotes TO ". rawurldecode($user));
} elseif ($todo == "revoke") {
	set_privilege ("REVOKE ". implode(", ", $privileges) ." ON $cfgQuotes$table$cfgQuotes FROM ". rawurldecode($user));
}

if (!isset($action)) {
	$privileges = get_privilege($table);
	if (@count($privileges)) {
?>
		<table border=<?php echo $cfgBorder;?>>
		<TR>
		<TH><?php echo $strUserGroup;?></TH>
		<TH><?php echo $strSelect;?></TH>
		<TH><?php echo $strInsert;?></TH>
		<TH><?php echo $strUpdate;?></TH>
		<TH><?php echo $strRule;?></TH>
		<TH colspan="2"><?php echo $strAction;?></TH>
		</TR>
<?php
		$i = 0;
		while(list($name, $priv) = @each ($privileges)) {
			$bgcolor = $cfgBgcolorOne;
			$i % 2  ? 0: $bgcolor = $cfgBgcolorTwo;
			$i++;
?>
		<TR bgcolor="<?php echo $bgcolor;?>">
		<TD><?php echo $name;?></TD>
		<TD><?php echo $priv['select'];?></TD>
		<TD><?php echo $priv['insert'];?></TD>
		<TD><?php echo $priv['delete'];?></TD>
		<TD><?php echo $priv['rule'];?></TD>
		<TD><a href="tbl_privilege.php?<?php echo $query;?>&action=grant&user=<?php echo rawurlencode($name);?>">Grant</a></TD>
		<TD><a href="tbl_privilege.php?<?php echo $query;?>&action=revoke&user=<?php echo rawurlencode($name);?>">Revoke</a></TD>
		</TD>
<?php 
		} 
	} else {
		echo "No Privileges set";
	}
	echo "</table>";
?>
	<br>
	<li><a href="tbl_privilege.php?<?php echo $query;?>&action=grantuser"><?php echo $strAddUser; ?></a>
	<li><a href="tbl_privilege.php?<?php echo $query;?>&action=grantgroup"><?php echo $strAddGroup; ?></a>
<?php 

} else {
	$i = 0;
	while ($p = $arrPrivileges[$i]) {
		$cb_priv[$p] = '<input type="checkbox" name="privileges[]" value="'. "$p\"> ". ucfirst($p) ."</input>";
		$i++;
	}
	$Expected = $strYes;
	$Action = "grant";
	$strToFrom = "to";

	$privileges = get_privilege($table);
	switch ($action) {
		case "revoke":
			$Expected =  $strNo;
			$Action = "revoke";
			$strToFrom = "from";
		case "grant":
			$name = rawurldecode($user);

			$i = 0;
			while ($p = $arrPrivileges[$i]) {
				if ($privileges[$name][$p] == $Expected) {
					unset($cb_priv[$p]); }
				$i++;
			}
			$user = "$cfgQuotes$name$cfgQuotes";
			$user = eregi_replace("${cfgQuotes}group ", "GROUP $cfgQuotes", $user);
			$user = eregi_replace("${cfgQuotes}public$cfgQuotes", "PUBLIC", $user);
			$input_user = '<input type="hidden" name="user" value="'. rawurlencode($user) .'">';
			break;
		case "grantuser":
			$qrUsers = "SELECT 'public'::text AS thename UNION SELECT '$cfgQuotes' || usename || '$cfgQuotes' AS thename FROM pg_user WHERE usename NOT IN ('root', '$cfgSuperUser'";
			@reset($privileges);
			while (list($key) = @each ($privileges))
				if (!ereg("group ", $key))
					$qrUsers .= ", '$key'";
			$qrUsers .= ") ORDER BY thename";
		case "grantgroup":
			if (!isset($qrUsers)) {
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
			}
			if (!$res = @pg_exec($link, $qrUsers)) {
				pg_die(pg_errormessage($link), $qrUsers, __FILE__, __LINE__);
			} else {
				$name = '<select name="user">';
			        $num_rows = pg_numrows($res);
				for ($i = 0; $i < $num_rows; $i++) {
			                $row = pg_fetch_array($res, $i);
					$name .= '<option value="'.rawurlencode($row['thename']) . '">'. $row['thename'] ."</option>";
				}
				$name .= "</select>\n";
			}
		}
	unset($action);
?>

	<form method="post" action="tbl_privilege.php">
		<p><?php echo $Action;?></p>
		<p><?php 
			$i = 0;
			while ($p = $arrPrivileges[$i]) {
				if (isset($cb_priv[$p])) { 
					echo $cb_priv[$p], "<br>";
				}
				$i++;
			} ?> </p>
		<p>on <?php echo "$table $strToFrom $name"; ?></p>

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
