<?php
	//	File:		user_admin.php
	//	Purpose:	Administration of postgres users
	//	Date:		21 May 2000
	
	
	include("lib.inc.php");
	

	if (!isset($message)) {
		include("header.inc.php");
	} else {
		show_message($message);
	}

	echo "<h1>$strUserAdmin</h1>";

	if (!empty($save_action)) {
		$qrUserSave = "$save_action USER $cfgQuotes$usr$cfgQuotes";

		if (!empty($password)) {
			if ($version >= 7) {
				$strPassDelim = "'";
			} else {
				unset($strPassDelim);
			}
			$qrUserSave .= " WITH PASSWORD $strPassDelim$password$strPassDelim";
		}
		
		if ($usecreatedb == "true") {
			$qrUserSave .= " CREATEDB";
		} else {
			$qrUserSave .= " NOCREATEDB";
		}
		if ($usesuper == "true") {
			$qrUserSave .= " CREATEUSER";
		} else {
			$qrUserSave .= " NOCREATEUSER";
		}
		
		if (!empty($valuntil)) {
			$qrUserSave .= " VALID UNTIL '$valuntil'";
		}
		
		
		// echo $qrUserSave, "<p>";
		if (@pg_exec($link, pre_query($qrUserSave))) {
			echo "$strUserUpdated!<p>";
		} else {
			pg_die(pg_errormessage(), $qrUserSave, __FILE__, __LINE__);
		}
		unset($save_action);
		unset($action);
	}
	
	// Display all users
	if (empty($action)) {
		$qrUsers = "SELECT * FROM pg_user WHERE usename NOT IN ('root', '$cfgSuperUser') ORDER BY usesysid";
		$rsUsers = pg_exec($link, pre_query($qrUsers));
		$iNumUsers = pg_numrows($rsUsers);
		
		if ($iNumUsers > 0) {
			echo "
				<table border=\"$cfgBorder\">
					<tr>
						<th>$strUserName</th>
						<th>$strSysID</th>
						<th>$strCreateDB</th>
						<th>$strSuperUser</th>
						<!--th>$strCatUpd</th-->
						<th>$strExpires</th>
						<th>$strAction</th>
					</tr>
			";

			for ($iUsers = 0; $iUsers < $iNumUsers; $iUsers++) {
				$aryUsers = pg_fetch_array($rsUsers, $iUsers);
				$strBGcolor = $cfgBgcolorOne;
				$iRows++ % 2  ? 0: $strBGcolor = $cfgBgcolorTwo;
				$qrDel = urlencode("DROP USER $cfgQuotes$aryUsers[usename]$cfgQuotes");
				$strDelZR = urlencode("User $aryUsers[usename] deleted successfully.");
				echo "
					<tr bgcolor=\"$strBGcolor\">
						<td>$aryUsers[usename]</td>
						<td>$aryUsers[usesysid]</td>
						<td>" . bool_YesNo($aryUsers[usecreatedb]) . "</td>
						<td>" . bool_YesNo($aryUsers[usesuper]) . "</td>
						<!--td>" . bool_YesNo($aryUsers[usecatupd]) . "</td-->
						<td>$aryUsers[valuntil]</td>
						<td>
							<a href=\"sql.php?server=$server&sql_query=$qrDel&goto=user_admin.php&zero_rows=$strDelZR\">$strDelete</a> |
							<a href=\"user_admin.php?server=$server&action=edit&usr=$aryUsers[usename]\">$strEdit</a>
						</td>
					</tr>
				";
			}
			echo "</table>";
		}
		
	} elseif ($action == "edit" || $action == "new_user") {
		$qrUserInfo = "SELECT * FROM pg_shadow WHERE usename = '$usr'";
		$rsUserInfo = @pg_exec($link, pre_query($qrUserInfo));
		$aryUser = @pg_fetch_array($rsUserInfo, 0);
		
		if ($aryUser[usecreatedb] == "t") {
			$strSelCreateDB = "checked";
		} else {
			unset($strSelCreateDB);
		}
		if ($aryUser[usesuper] == "t") {
			$strSelSuper = "checked";
		} else {
			unset($strSelSuper);
		}
		if ($aryUser[usecatupd] == "t") {
			$strSelCatUpd = "checked";
		} else {
			unset($strSelCatUpd);
		}
		
		if ($action == "edit") {
			echo "<b>$strEdit $usr</b><p>", show_docu("sql-alteruser.htm"), "<br>";
			$strUserField = "<b>$usr</b><input type=hidden name=usr value=\"$usr\">";
			$strActionVal = "ALTER";
		} else {
			echo "<b>$strNewUser</b><p>", show_docu("sql-createuser.htm"), "<br>";
			$strUserField = "<input type=\"text\" name=\"usr\">";
			$strActionVal = "CREATE";
		}

		if (empty($aryUser[valuntil])) {
			$aryUser[valuntil] = '2038-01-18';
		}
		
		echo "
			<table border=\"$cfgBorder\">
			<tr>
				<th>$strUserName</th>
				<th>$strCreateDB</th>
				<th>$strSuperUser</th>
				<!--th>$strCatUpd</th-->
				<th>$strPassword</th>
				<th>$strExpires</th>
			</tr>
			<form method=\"POST\">
			<tr bgcolor=\"$cfgBgcolorTwo\">
				<td align=center>$strUserField</td>
				<td align=center><input type=checkbox name=usecreatedb value=true $strSelCreateDB></td>
				<td align=center><input type=checkbox name=usesuper value=true $strSelSuper></td>
				<!--td align=center><input type=checkbox name=usecatupd value=true $strSelCatUpd></td-->
				<td align=center><input type=password name=password value=\"$aryUser[passwd]\"></td>
				<td align=center><input type=text name=valuntil value=\"$aryUser[valuntil]\"></td>
			</tr>
			<tr align=center>
				<td colspan=6>
					<input type=submit name=\"submit\" value=\"Save\"> &nbsp; 
					<input type=reset value=\"Cancel\" onclick=\"history.back()\"> &nbsp; 
				</td>
			</tr>
			<input type=\"hidden\" name=\"save_action\" value=\"$strActionVal\">
			</form>
		</table>
		";
	}
	
	echo "<p><a href=\"user_admin.php?server=$server&action=new_user\">$strNewUser</a>";
	
	include("footer.inc.php");
?>
