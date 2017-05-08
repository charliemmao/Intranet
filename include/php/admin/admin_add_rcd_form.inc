<html>
<?php
	echo "<form method=post action=\"$PHP_SELF\" name=\"fldform\">";
	include("userstr.inc");
	echo "<input type=\"hidden\" name=\"mantablename\" value=\"$mantablename\">";
	echo "<input type=\"hidden\" name=\"action\" value=\"$action\">";
		//projleader pub_holidays	country	company	chargecode		code_prefix	employee	projcodes
		if ($mantablename == "projleader") {
			$fld = "leader";
			$lbl = "Email Name";
		}
		if ($mantablename == "pub_holidays") {
			$fld = "name";
			$lbl = "Holiday";
		}
		if ($mantablename == "country") {
			$fld = "country";
			$lbl = "Country";
		}
		if ($mantablename == "company") {
			$fld = "company_name";
			$lbl = "Company";
		}
		if ($mantablename == "chargecode") {
			$fld = "charging_code";
			$lbl = "Charge Code";
		}
		if ($mantablename == "code_prefix") {
			$fld = "code_prefix";
			$lbl = "Code Prefix";
		}
		if ($mantablename == "employee") {
			$fld = "email_name";
			$lbl = "Employee";
		}
		if ($mantablename == "projcodes") {
			$fld = "brief_code";
			$lbl = "Project Codes";
		}
		$sql = "SELECT $fld as fld FROM timesheet.$mantablename;"; 
		$result = mysql_query($sql);
		include("err_msg.inc");
		$norcd = mysql_num_rows($result);
		$ldrno = 0;
		if ($norcd) {
			$lst = "";
			echo "<table><tr><td><b><font color=#ff0000>Current Records</font></b></td><td>";
			echo "<select name=\"rcdlist\">";
			while (list($fld) = mysql_fetch_array($result)) {
				if ($mantablename == "projleader") {
					$projleaderlist[$ldrno][0] = $fld;
					$ldrno++;
				}
				$fld = ereg_replace("__", "&", $fld);
				$fld = ereg_replace("_", " ", $fld);
				if ($mantablename != "employee") {
					$fld = ucwords($fld);
				}
				$fld = strtoupper($fld);
				echo "<option>$fld";
				$lst = $lst.$fld."~";
			}
			echo "</select>&nbsp;&nbsp;<font color=#0000ff><b>($norcd).</b></font>";
			echo "<input type=\"hidden\" name=\"rcdarray\" value=\"$lst\">";
			echo "</td></tr>";
		}
	if ($mantablename == "projleader") {
		for ($i=0; $i<$ldrno; $i++) {
			$tmpldr = $projleaderlist[$i][0];
			$sql = "SELECT codes as c1, grpcode as c2 FROM timesheet.projleader WHERE leader='$tmpldr';";
			$result = mysql_query($sql);
			include("err_msg.inc");
			list($c1,$c2) = mysql_fetch_array($result);
			if ($c1) {
				$n = $tmpldr."code";
				echo "<input type=hidden name=\"$n\" value=$c1>";
			}
			if ($c2) {
				$n = $tmpldr."group";
				echo "<input type=hidden name=\"$n\" value=$c2>";
			}
		}
	}

	if ($mantablename == "projleader") {
		echo "<tr><td><b>Select from &nbsp;</b></td><td><select name=grporind>";
		echo "<option value=\"\">---Select One---
			<option value=\"Y\" selected>Project Group List</option>
			<option value=\"N\">Individual Project List
			</option></select><td></tr>";
		$sql = "SELECT email_name as ename, first_name as fname, last_name as lname 
			FROM timesheet.employee order by fname;";
		$result = mysql_query($sql);
		include("err_msg.inc");
		$norcd = mysql_num_rows($result);
		echo "<tr><td colspan=2><input type=hidden name=nogrp value=$norcd></td><tr>";
		echo "<tr><td><b>Staff List</b></td><td><select name=leader onChange=\"selectcodecheck();\">";
		echo "<option value=\"\">---Select One---";
		while (list($ename,$fname,$lname) = mysql_fetch_array($result)) {
			$tmp = $fname." ".$lname;
			$tmp = ucwords($tmp)." (".strtoupper($ename).")";
			if ($email_name == $ename) {
				echo "<option value=\"$ename\" selected>".$tmp;
			} else {
				echo "<option value=\"$ename\">".$tmp;
			}
		}
		echo "</option></select>&nbsp;&nbsp;<font color=#0000ff><b>($norcd).</b></font></td></tr>";
		echo "</table><p>";
		echo "<hr><b>Please select from either <font color=#ff0000>\"Project Group\"</font> or 
		<font color=#ff0000>\"Project Code\"</font> list.</b><br><br>";
		
		echo "<p><a onClick=\"code_sel_des('clear')\"><font size=2><b>[Clear All Codes]</b></a>&nbsp;&nbsp;&nbsp;";
		echo "<a onClick=\"code_sel_des('select')\"><font size=2><b>[Select All Codes]</b></a></p>";

		echo "<fieldset><legend align=top><b><font color=#ff0000>Project Group</font><b></legend>";
		echo "<table border=0 cellspacing=\"0\" cellpadding=\"0\">";
		//echo "<caption>Project Group</caption>";
		$sql = "SELECT code_prefix as prex, codelable FROM timesheet.code_prefix;";
		$result = mysql_query($sql);
		include("err_msg.inc");
		$norcd = mysql_num_rows($result);
		$i = 0;
		while (list($prex, $codelable) = mysql_fetch_array($result)) {
			$codes = "codegrp".$i;
			echo "<tr><td><font color=#0000ff size=1><input type=\"checkbox\" name=\"$codes\" value=\"$prex\">
				$prex<font size=1>:&nbsp;&nbsp;$codelable</font></font></td></tr>";
    		$i++;
    	}
    	echo "</table></fieldset><p>";
		
		echo "<fieldset><legend align=top><b><font color=#ff0000>Individual Project List</font><b></legend>";
		echo "<table border=0 cellspacing=\"0\" cellpadding=\"0\">";
		$sql = "SELECT brief_code as bcode FROM timesheet.projcodes ORDER BY brief_code;";
		$result = mysql_query($sql);
		include("err_msg.inc");
		$norcd = mysql_num_rows($result);
		echo "<tr><td colspan=2><input type=hidden name=noind value=$norcd></td><tr>";
		$i = 0;
		if ($norcd) {
			while (list($bcode) = mysql_fetch_array($result)) {
				$fld = ereg_replace("__", "&", $bcode);
				$fld = ereg_replace("_", " ", $fld);
				$codes = "codeind".$i;
				echo "<tr><td><font color=#0000ff size=1><input type=\"checkbox\" name=\"$codes\" value=\"$bcode\">
					$fld</font></td></tr>";
    			$i++;
			}
		}
    	echo "</table></fieldset><p><table>";
	}

	if ($mantablename == "projcodes") {
		echo "&nbsp;";
		$sql = "SELECT codehead_id, code_prefix FROM timesheet.code_prefix ORDER BY codehead_id;";
		$result = mysql_query($sql);
		include("err_msg.inc");
		$norcd = mysql_num_rows($result);
		if ($norcd) {
			echo "<b><font color=#ff0000>Proj. Group </font></b>";
			echo "<select onChange=\"auto_fill();\" name=\"projgrplist\">";
			$projgrp = "";
			while (list($codehead_id, $code_prefix) = mysql_fetch_array($result)) {
				$projgrp = $projgrp.$code_prefix."~";
				$fld = ereg_replace("__", "&", $code_prefix);
				$fld = ereg_replace("_", " ", $fld);
				echo "<option value=\"$fld\">$fld";
			}
			echo "</option></select>&nbsp;&nbsp;<font color=#0000ff><b>($norcd).</b></font>";
		}
	}
	
	$column = 2;
	if ($mantablename != "projleader") {
	//echo "<table border=0>";
		for ($i=0; $i<$fields; $i++) {
			$name0 = ereg_replace("_"," ", $name_array[$i]);
			$name0 = ucwords($name0);
			$name0 = ereg_replace("Ip","IP", $name0);
			$name0 = ereg_replace("Of","of", $name0);
			if ($mantablename == "employee") {
				$name0 = ereg_replace("1","", $name0);
			} 
			if ($obj[$i] == "hidden") {
				echo "<tr><td colspan=\"$column\">";
				echo "<input type=\"$obj[$i]\" name=\"$name_array[$i]\" value=\"$val[$i]\">";
				echo "</td></tr>";
			} elseif ($obj[$i] == "text") {
				echo "<tr><td><b>$name0</b></td><td>";
				if ($mantablename == "employee" && $name_array[$i] == "title1") {
					echo "<select name=\"$name_array[$i]\">";
						echo "<option selected>Mr";
						echo "<option>Mrs";
						echo "<option>Ms";
						echo "<option>Miss";
						echo "<option>Dr";
						echo "<option>Sir";
						echo "</option>";
					echo "</select>";
				} elseif ($mantablename == "projcodes" && $name_array[$i] == "special") {
					echo "<select name=\"$name_array[$i]\">";
						include("specialentrydef.inc");
						for ($si = 0; $si < count($splist); $si++) {
							echo "<option>".$splist[$si];
						}
						echo "</option>";
					echo "</select>";
				} elseif ($mantablename == "projcodes" && $name_array[$i] == "div15") {
					echo "<select name=\"$name_array[$i]\">";
						echo "<option selected>Y";
						echo "<option>N";
						echo "</option>";
					echo "</select>";
				} else {
					if ($mantablename == "projcodes" && $name_array[$i] == "brief_code") {
						echo "<input onBlur=\"projectcode();\" ";
					} else {
						echo "<input onBlur=\"field_verification('$verify[$i]'); auto_check('$mantablename');\" ";
					}
					echo "type=\"$obj[$i]\" name=\"$name_array[$i]\" size=\"60\" ";
					if ($verify[$i] == "date") {
						if ($mantablename == "employee" && $name_array[$i] == "last_modification1") {
							echo 'value="'.date("Y-m-d").'"';
						} elseif ($name_array[$i] == "begin_date" || $name_array[$i] == "date_start" 
							|| $name_array[$i] == "start_date" || $name_array[$i] == "date") {
							echo 'value="'.date("Y-m-d").'"';
						} else {
							echo 'value="0000-00-00"';
						}
					}
					if ($mantablename == "employee" && $name_array[$i] == "homeadd_country1") {
						echo 'value="Australia"';
					}
					echo ">";
				}
				echo "</td></tr>";
			} elseif ($obj[$i] == "textarea") {
				echo "<tr><td><b>$name0</b></td>";
				echo "<td><textarea name=\"$name_array[$i]\" rows=\"3\" cols=\"51\"></textarea></tr>";
			}
		}		
		echo "<tr><td colspan=\"$column\">&nbsp;</td></tr>";	
	}
		//echo "<tr><td colspan=\"$column\">&nbsp;</td></tr>";
		echo "<tr><td colspan=\"$column\" align=center>";
		echo "<input onClick=\"return subconfirm('$mantablename');\" type=\"submit\" name=\"addnewrcd\" value=\"Add New Record\">";
		echo "</td></tr></table><p><br>";
		echo "<script language=javascript>\n";
		//echo "selectcodecheck();\n";
		/*
		echo "var str;\n";
		echo 'function code_sel_des(str) {\n';
		echo 'e = document.fldform.elements;\n';
		echo 'if (str == "clear") {\n';
		echo 'for (j=0; j<e.length; j++) {\n';
			echo 'if (e[j].type == "checkbox") {\n';
				echo 'e[j].checked = false;\n';
			echo '}\n';
			echo '}\n';
		echo '} else {\n';
			echo 'for (j=0; j<e.length; j++) {\n';
				echo 'if (e[j].type == "checkbox") {\n';
					echo 'e[j].checked = true;\n';
				echo '}\n';
			echo '}\n';
		echo '}\n';
		*/
		echo "</script><br>";
?>
	
</html>