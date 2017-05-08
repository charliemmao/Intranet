<?php

/* $Id: grp_admin.php,v 1.3 2001/02/02 06:03:08 dwilson Exp $ */

	include("lib.inc.php");

	if (!isset($message)) {
		include("header.inc.php");
	}else{
		show_message($message);
	}
	echo "<h1>$strGroupAdmin</h1>";

	if (!empty($save_action)) {

		if( $todo == "add" ) {		
		     $qIns = "CREATE GROUP $cfgQuotes$gName$cfgQuotes";
  		
		     if (@pg_exec($link, pre_query($qIns))) {
			echo "$strGroupUpdated!<p>";
		     } else {
			pg_die(pg_errormessage(), $qIns, __FILE__, __LINE__);
		     }
	   	} elseif ($todo == "del") {
		  $qDel = "DROP GROUP $cfgQuotes$groname$cfgQuotes";
		  
		  if (@pg_exec($link, pre_query($qDel))) {
			echo "$strGroupDeleted!<p>";
		  }else {
			pg_die(pg_errormessage(), $qDel, __FILE__, __LINE__);
		  }
		} elseif ($todo == "edit") {
		     $qUpdate = "UPDATE pg_group SET groname = '$gName' WHERE grosysid = $grosysid";
		     if (@pg_exec($link, pre_query($qUpdate))) {
			   echo "$strGroupUpdated!<p>";
		     } else {
			   pg_die(pg_errormessage($link), $qUpdate, __FILE__, __LINE__);
		     }
		} elseif ($todo == "modify") {
			// modify group users.
			$bModifid = false;

			if (!empty($mem)) {
				while (list($key, $val) = each ($mem)) {
					$strMemDrop .= "$cfgQuotes$val$cfgQuotes, ";
				}
				$strMemDrop = ereg_replace(", $", "", $strMemDrop);
				$qrDrop = "ALTER GROUP $cfgQuotes$groname$cfgQuotes DROP USER $strMemDrop";
				@pg_exec($link, pre_query($qrDrop)) or pg_die(pg_errormessage($link), $qrDrop, __FILE__, __LINE__);
				$bModifid = true;
			}

			if (!empty($non)) {
				while (list($key, $val) = each ($non)) {
					$strAdd .= "$cfgQuotes$val$cfgQuotes, ";
				}
				$strAdd = ereg_replace(", $", "", $strAdd);
				$qrAdd = "ALTER GROUP $cfgQuotes$groname$cfgQuotes ADD USER $strAdd";
				@pg_exec($link, pre_query($qrAdd)) or pg_die(pg_errormessage($link), $qrAdd, __FILE__, __LINE__);
				$bModifid = true;
			}
			
			if ($bModified) {
				echo "$strGroupUpdated!<p>";
			}
		}
		if ($todo == "modify" || $todo == "add") {
			if (empty($groname)) {
				$groname = $gName;
			}
			$action = "alter";
		} else {
			unset($action);
		}
		unset($save_action);
	}
	
	// Display all groups
	if (empty($action)) {

		$qrGroups = "SELECT groname,grolist,grosysid FROM pg_group ORDER BY groname";
    	$rsGroups = @pg_exec($link, pre_query($qrGroups));
    
    	$iNumGroups = @pg_numrows($rsGroups);
    		
    	if ($iNumGroups > 0) {
		  echo "<table border=\"$cfgBorder\">
		      <tr><th>$strGroupField</th>
		      <th>$strGroupID</th>
		      <th>$strAction</th>
		      </tr>";

		  for ($iGroups = 0; $iGroups < $iNumGroups; $iGroups++) {
			$aryGroups = pg_fetch_array($rsGroups, $iGroups);
			$strBGcolor = $cfgBgcolorOne;
			$iRows++ % 2  ? 0: $strBGcolor = $cfgBgcolorTwo;
			$groname = urlencode($aryGroups[groname]);
			echo "
			<tr bgcolor=\"$strBGcolor\">
			<td>$aryGroups[groname]</td>
			<td>$aryGroups[grosysid]</td>
			<td>
			<a href=$PHP_SELF?server=$server&action=edit&grosysid=$aryGroups[grosysid]&groname=$groname>$strRename</a> | 
			<a href=$PHP_SELF?server=$server&action=delete&grosysid=$aryGroups[grosysid]&groname=$groname>$strDelete</a> | 
			<a href=$PHP_SELF?server=$server&action=alter&grosysid=$aryGroups[grosysid]&groname=$groname>$strEdit</a></td></tr>";
		  }
		  echo "</table>";

   		  echo "<p><a href=$PHP_SELF?server=$server&action=new_group>$strNewGroup</a><br>";

    	} else {
		  // pg_group table empty!
		  echo "<p>$strGroupMessage<br>";
		  echo "<a href=$PHP_SELF?server=$server&action=new_group>$strNewGroup</a><br></p>";
		}	
	} elseif ($action == "alter") {
		echo "<table border=$cfgBorder>
				<form method=\"post\">
		";

		$qrGroups = "SELECT groname, grolist, grosysid FROM pg_group WHERE groname = '$groname' ORDER BY groname";
    	$rsGroups = @pg_exec($link, pre_query($qrGroups));
		
		$aryGroups = pg_fetch_array($rsGroups, 0);
		$members = $aryGroups[grolist];
		$members = ereg_replace("\{|\}","",$members);

		echo "
			<tr><th colspan=\"2\">$strGroup: $aryGroups[groname]</th><th>$strGroupModify</th></tr>
			<tr bgcolor=$cfgMember><td colspan=\"3\"><b>$strMember</b></td></tr>
		";
 			
		if (!empty($members)) {
			$strCheckMem = "usesysid NOT IN ($members) AND ";
			$qMember = "SELECT usename, 1 as dummy, usesysid 
			            FROM pg_user 
						WHERE usesysid in ($members) AND 
						      usename NOT IN ('root', '$cfgSuperUser')
			";
			$rsMember 	= pg_exec($link, pre_query($qMember)) or pg_die(pg_errormessage($link), $qMember, __FILE__, __LINE__);
			$nMemRows 	= pg_numrows($rsMember);
		}
		
		$qNon 	= "SELECT usename, 0 as dummy, usesysid 
					FROM pg_user 
					WHERE $strCheckMem 
						  usename NOT IN ('root', '$cfgSuperUser')
   				    ORDER BY dummy, usename
		";
		
		// echo $qNon, "<br>";
				
		$rsNon 		= pg_exec($link, pre_query($qNon)) or pg_die(pg_errormessage($link), $qNon, __FILE__, __LINE__);
		$nNonRows 	= pg_numrows($rsNon);
		
		for ($iMem = 0; $iMem < $nMemRows; $iMem++) {
   			$bgcolor = $cfgBgcolorOne;
   			$iMem % 2  ? 0: $bgcolor = $cfgBgcolorTwo;
   		  	$aryMem = pg_fetch_array ($rsMember, $iMem);
   			echo "
				<tr bgcolor=$bgcolor>
					<td>&nbsp;</td>
					<td>$aryMem[usename]</td>
					<td>
						<input name=\"mem[$aryMem[usesysid]]\" value=\"$aryMem[usename]\" type=checkbox>
					</td>
				</tr>
			";
		}

		echo "<tr bgcolor=\"$cfgNonMember\"><td colspan=3><b>$strNonMember</b></td></tr>";
   
		for ($iNon = 0; $iNon < $nNonRows; $iNon++) {
   			$bgcolor = $cfgBgcolorOne;
   			$iNon % 2  ? 0: $bgcolor = $cfgBgcolorTwo;
   		  	$aryNon = pg_fetch_array ($rsNon, $iNon);
   			echo "
				<tr bgcolor=$bgcolor>
					<td>&nbsp;</td>
					<td>$aryNon[usename]</td>
					<td>
						<input name=\"non[$aryNon[usesysid]]\" value=\"$aryNon[usename]\" type=checkbox>
					</td>
				</tr>
			";
		}

		echo "
				<tr><td colspan=3>&nbsp;</td></tr>
				<tr>
					<td colspan=3>
						<input type=\"submit\" value=\"$strGroupModify\">
						<input type=\"button\" value=\"$strCancel\" onClick=\"history.back()\">
						<input type=\"hidden\" name=\"todo\" value=\"modify\">
						<input type=\"hidden\" name=\"save_action\" value=\"1\">
						<input type=\"hidden\" name=\"groname\" value=\"$groname\">
						<input type=\"hidden\" name=\"server\" value=\"$server\">
					</td>
				</tr>
				</form>
			</table>
		";

	} elseif ($action == "delete") {
		$groname = urldecode($groname);
		echo "
			<table border=\"$cfgBorder\">
			<tr><th>$strGroupName</th></tr>
			<tr><th bgcolor=\"$cfgBgcolorTwo\" align=center>$strGroupField</th></tr>
			<form method=\"POST\">
			<tr>
				<td>$groname</td>
			</tr>
			
			<tr align=center>
				<td>
					<input type=submit name=\"submit\" value=$strDelete> &nbsp; 
					<input type=reset value=\"$strCancel\" onclick=\"history.back()\"> &nbsp; 
				</td>
			</tr>
			<tr>
				<td>
					<input type=\"hidden\" name=\"grosysid\" value=\"$grosysid\">
					<input type=\"hidden\" name=\"groname\" value=\"$groname\">
					<input type=\"hidden\" name=\"save_action\" value=\"1\">
					<input type=\"hidden\" name=\"todo\" value=\"del\">
					<input type=\"hidden\" name=\"server\" value=\"$server\">
				</td>
			</tr>

			</form>
		</table>
		";
	} elseif ($action == "edit" || $action == "new_group") {
		if ($action == "new_group" ) {
		   $groname = "";
		   $todo = "add";
		}else{
		   $groname = urldecode($groname);
		   $strSave = $strGroupUpdate;
		   $todo = "edit";
		}
		
		echo "
			<table border=\"$cfgBorder\">
			<tr><th>$strGroupName</th></tr>
			<tr><th bgcolor=\"$cfgBgcolorTwo\" align=center>$strGroupField</th></tr>
			<form method=\"POST\">
			<tr>
				  <td><input type=text name=\"gName\" value=\"$groname\"></td>
			</tr>
			<tr align=center>
				<td>
					<input type=submit name=\"submit\" value=$strSave> &nbsp; 
					<input type=reset value=\"$strCancel\" onclick=\"history.back()\"> &nbsp; 
				</td>
			</tr>
			<tr><td><input type=\"hidden\" name=\"grosysid\" value=$grosysid></td></tr>
			<tr><td><input type=\"hidden\" name=\"save_action\" value=\"1\"></td></tr>
			<tr><td><input type=\"hidden\" name=\"todo\" value=$todo></td></tr>
			<tr><td><input type=\"hidden\" name=\"server\" value=$server></td></tr>
			</form>
		</table>
		";
	}
	
	include("footer.inc.php");
?>
