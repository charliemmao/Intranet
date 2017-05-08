<html>
<?php
	include('connet_other_once.inc');
	$sql = "SELECT codes, grpcode FROM timesheet.projleader WHERE leader='$email_name';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($codes, $grpcode) = mysql_fetch_array($result);

	echo "<tr><td colspan=2>";
	if ($priv == "00" || $priv == "10") {
		if (!$leader) {
			$leader = "n";
		}
		if ($leader == "n") {
			echo "<div id=codeselfieldset style=\"display: none\">";
		} elseif ($leader == "y") {
			echo "<div id=codeselfieldset>";
		}
	}	
	echo "<fieldset><legend><font color=#0000ff size=2><b>Select Codes</b></font></legend>";

	echo "<table><tr><td><select name=grporind onchange=\"displayhide();\">";
	echo "<option value=\"\">---Select One---";
if ($ptype != "SlpSum") {
	if ($grporind == "Y") {
		echo "<option value=\"Y\" selected>Project Group List</option>";
		echo "<option value=\"N\">Individual Project List";
		$vis = "group";
	} elseif ($grporind == "N") {
		echo "<option value=\"Y\">Project Group List</option>";
		echo "<option value=\"N\" selected>Individual Project List";
		$vis = "ind";
	} else {
		if ($grpcode) {
			echo "<option value=\"Y\" selected>Project Group List</option>";
			echo "<option value=\"N\">Individual Project List";
			$vis = "group";
		} else {
			echo "<option value=\"Y\">Project Group List</option>";
			echo "<option value=\"N\" selected>Individual Project List";
			$vis = "ind";
		}
	}
	echo "</option></select><td><td><font color=#ff00ff size=2>
		<b>Select Your Project Group/Projects List.</b></font></td></tr>";	
} elseif ($ptype == "SlpSum") {
	$grporind = "N";
	echo "<option value=\"N\" selected>Individual Project List";
		$vis = "ind";
	echo "</option></select></tr>";
	//<td><font color=#ff00ff size=2><b>Please Tick One Code Only.</b></font></td>	
}
	flush();

	if ($analysisreport) {
		$groupctr = 0;
		for ($i=0; $i<$ldrgrouplist; $i++) {
			$var = "ldrgrouplist$i";
			if ($$var) {
				//echo $var.", ".$$var.", ".$groupctr."<br>";
				$groupcodes[$groupctr] = $$var;
				$groupctr++;
			}
		}
		
		$indctr = 0;
		for ($i=0; $i<$ldrcodelist; $i++) {
			$var = "ldrcodelist$i";
			if ($$var) {
				//echo $var.", ".$$var.", ".$indctr."<br>";
				$indcodes[$indctr] = $$var;
				$indctr++;
			}
		}
	}
	
	##########################
	if ($vis == "group") {
		echo "<tr><td colspan=2><div id=grpcode>";
	} else {
		echo "<tr><td colspan=2><div id=grpcode style=\"display: none\">";
	}
	$tmp = explode("@",$grpcode);
	$no = count($tmp);
	$b1 = "<font size=\"1\">";
	$b2 = "</font>";
	$nocode = 0;

	for ($i=0; $i<$no; $i++) {
		if (trim($tmp[$i])) {
			$nocode++;
			$str0 = $tmp[$i];
			$str1 = ereg_replace("__", "&", $str0);
			$str1 = ereg_replace("_", " ", $str1);
			$str1 = ucwords($str1);
			$namestr = "ldrgrouplist$i";
			if ($analysisreport) {
				$sel = 0;
				for ($j=0; $j<$groupctr; $j++) {
					if ($groupcodes[$j] == $str0) {
						$sel = 1;
					}
				}
				if ($sel == 1) {
					echo "<input type=checkbox name=\"$namestr\" checked value=\"$str0\">$b1$str1$b2<br>";
				} else {
					echo "<input type=checkbox name=\"$namestr\" value=\"$str0\">$b1$str1$b2<br>";
				}
			} else {
				echo "<input type=checkbox name=\"$namestr\" checked value=\"$str0\">$b1$str1$b2<br>";
			}
		}
	}
	
	echo "<input type=\"hidden\" name=\"ldrgrouplist\" value=\"$nocode\">";
	if ($grpcode == "") {
		echo "<font color=#ff0000><b>No code group has been selected.</b></font>";
	}
	echo "</div></td></tr>";
	
	##########################
	if ($vis == "ind") {
		echo "<tr><td colspan=2><div id=indcode>";
	} else {
		echo "<tr><td colspan=2><div id=indcode style=\"display: none\">";
	}
	$tmp = explode("@",$codes);
	$no = count($tmp);
	$nocode = 0;
	$selpdno = 0;
	for ($i=0; $i<$no; $i++) {
		if ($tmp[$i]) {
			$nocode++;
			$str0 = $tmp[$i];
			$str1 = ereg_replace("__", "&", $str0);
			$str1 = ereg_replace("_", " ", $str1);
			$str1 = ucwords($str1);
			$namestr = "ldrcodelist$i";
			if ($analysisreport) {
				$sel = 0;
				for ($j=0; $j<$indctr; $j++) {
					if ($indcodes[$j] == $str0) {
						if ($ptype == "SlpSum") {
							$selpdno++;
						}
						$sel = 1;
					}
				}
				if ($sel == 1) {
					echo "<input type=checkbox name=\"$namestr\" checked value=\"$str0\">$b1$str1$b2<br>";
				} else {
					echo "<input type=checkbox name=\"$namestr\" value=\"$str0\">$b1$str1$b2<br>";
				}
			} else {
				echo "<input type=checkbox checked name=\"$namestr\" value=\"$str0\">$b1$str1$b2<br>";
			}
		}
	}
	echo "<input type=\"hidden\" name=\"ldrcodelist\" value=\"$nocode\">";
	if ($codes == "") {
		echo "<font color=#ff0000><b>No individual project code has been selected.</b></font>";
	}
	echo "</div></td></tr>";
		
	##########################
	echo "</table>";
	//if ($priv == "00") {
		echo "<b><a onClick=\"code_sel_des('clear')\"";
		//echo ' onMouseOut="ClearFlyOver();" ';
		//echo ' onMouseOver="FlyOver(\'Click me to deselect project codes.\')";';
		echo ">[Clear All Codes]</a>&nbsp;&nbsp;&nbsp;";
		
		echo "<a onClick=\"code_sel_des('select')\"";
		//echo ' onMouseOut="ClearFlyOver();" ';
		//echo ' onMouseOver="FlyOver(\'Click me to select all project codes.\')";';
		echo ">[Select All Codes]</a><b>";
	//}

	echo "</fieldset>";
	if ($priv == "00" || $priv == "10") {
		echo "</div>";
	}	
	echo "</td></tr>";
?>
</html>