<html>
<?php
function makeselectionlist($from, $to, $default) {
	for ($ii=$from; $ii<=$to; $ii++) {
		if ($ii == $default) {
			echo "<option selected>$ii";
		} else {
			echo "<option>$ii";
		}
	}
	echo "</option></select>";
}
echo "<hr>";
echo "<form mehtod=post>";
echo "<input type=hidden name=caseany value=y>";
echo "<table border=1><tr><td valign=top>";

echo "<table>";
if ($rsc) {
	$frm_str	=	base64_encode($userinfo."&rsc=y");
} elseif ($proj) {
	$frm_str	=	base64_encode($userinfo."&proj=y");
}
echo "<input type=hidden name=frm_str value=\"$frm_str\">";
echo "<tr><th align=left colspan=4><font size=4 color=#0000ff>";
if ($btncaption) {
	echo "Select Period<br>";
	echo "<select name=timeframe>";
	$tframe[0][0] = "n";
	$tframe[0][1] = "All Time";
	$tframe[1][0] = "y";
	$tframe[1][1] = "Selected Period";
	for ($i=0; $i<2; $i++) {
		if ($timeframe == $tframe[$i][0]) {
			echo "<option selected value=\"".$tframe[$i][0]."\">".$tframe[$i][1];
		} else {
			echo "<option value=\"".$tframe[$i][0]."\">".$tframe[$i][1];
		}
	}
	echo "</option></select>";
} else {
	echo "Select Report Period";
}
echo "</font></th></tr>";
echo "<tr><th align=left>Start</th><td>Month&nbsp;<select name=mthstart>";
	if (!$mthstart) {
		$yearstart = date("Y");
		$mthstart = date("n")-1;
		if ($mthstart == 0) {
			$mthstart=12;
			$yearstart = $yearstart - 1;
		}
	}
	makeselectionlist(1, 12, $mthstart); 
echo "</td>";
echo "<td>Year&nbsp;<select name=yearstart>";
if ($btncaption) {
	makeselectionlist(2001, date("Y")+3, $yearstart);
} else {
	makeselectionlist(2000, date("Y")+3, $yearstart);
}
echo "</td></tr>";
echo "<tr><th align=left>End</th><td>Month&nbsp;<select name=mthend>";
	if (!$mthend) {
		$yearend= date("Y");
		$mthend= date("n");
	}
	makeselectionlist(1, 12, $mthend); 
echo "</td>";
echo "<td>Year&nbsp;<select name=yearend>";
if ($btncaption) {
	makeselectionlist(2001, date("Y")+10, $yearend);
} else {
	makeselectionlist(2000, date("Y")+10, $yearend);
}
echo "</td></tr></table>";
echo "</td><td valign=top><table>";

//For project or for resources
if ($proj) {
	if ($priv == "00" || $priv == "10") {
		echo "<tr><th align=left><font size=4 color=#0000ff>Project List ($noproj)</font></th></tr>";
		echo "<tr><td><select name=\"projectlist[]\" multiple>";
		$noprojsel = count($projectlist);
		if (!$noprojsel) {
       	echo "<option selected value=all>All Projects";
       } else {
       	if ($projectlist[0] == "all") {
       		echo "<option selected value=all>All Projects";
       		$noprojsel = 1;
       	} else {
       		echo "<option value=all>All Projects";
       	}
       }
    	for ($i=0; $i<$noproj; $i++) {
    		$selected = "";
    		for ($j=0; $j<$noprojsel; $j++) {
    			if ($projectlist[$j] == $projcodelist[$i][0]) {
        			$selected = "selected";
        			break;
        		}
        	}
        	echo "<option $selected value=".$projcodelist[$i][0].">".$projcodelist[$i][1];
    	}
    	echo "<input type=hidden name=proj value=y>";
    }
} else {
	if ($priv == "00" || $priv == "10") {
		echo "<tr><th align=left><font size=4 color=#0000ff>Staff List</font></th></tr>";
		echo "<tr><td><select name=\"stafflist[]\" multiple>";
		$nosatffsel = count($stafflist);
		if (!$nosatffsel) {
       	echo "<option selected value=all>All Staff";
       } else {
       	if ($stafflist[0] == "all") {
       		echo "<option selected value=all>All Staff";
       		$nosatffsel = 1;
       	} else {
       		echo "<option value=all>All Staff";
       	}
       }
    	for ($i=0; $i<$nostaff; $i++) {
    		$selected = "";
    		for ($j=0; $j<$nosatffsel; $j++) {
    			if ($stafflist[$j] == $staffname[$i][0]) {
        			$selected = "selected";
        			break;
        		}
        	}
        	if ($staffname[$i][2] == "y") {
        		echo "<option $selected value=".$staffname[$i][0].">".$staffname[$i][1];
        	}
    	}
    	echo "<input type=hidden name=rsc value=y>";
    }
}

echo "<option></select></td></tr>";
echo "</table></td></tr><tr><th colspan=2><button type=submit name=budgetreportanyperiod><b>";
if ($btncaption) {
	echo "$btncaption";
} else {
	echo "Create Report";
}
echo "</b></th>";
echo "</tr></table>";
echo "</form><p>";
?>
</html>
