<html>
<?php
if ($staffname !='') {
	$email_name	=	$staffname;
}
echo "<fieldset><legend><font size=4 color=#0000ff>
	Report Type and Period Selection</font></legend>";
echo '<form  name=periodform method=POST target=main action= "'.$PHP_SELF.'">';
include("userstr.inc");

if ($dateonly == 0) {
	$no_staff_sel	= count($staffname);
	$allstaff	=	0;
	if ($no_staff_sel) {
		if ($staffname[0] == "all") {
			$no_staff_sel = 1;
			$allstaff	=	1;
		}
		for ($i=0; $i<$no_staff_sel; $i++) {
			$sel_staff[$i] = $staffname[$i];
		}
	} else {
		$no_staff_sel = 1;
		$sel_staff[0] = $email_name;
	}

	$no_code_sel	= count($projcode);
	$allcode	=	0;
	if ($no_code_sel) {
		if ($projcode[0] == "all") {
			$no_code_sel = 1;
			$allcode	=	1;
		}
		for ($i=0; $i<$no_code_sel; $i++) {
			$sel_code[$i] = $projcode[$i];
		}
	} else {
		$no_code_sel = 2;
		$sel_code[0] = "LVE";
		$sel_code[1] = "SCK";
	}
}

echo '<p><table border=0 cellspacing=\"0\" cellpadding=\"0\">';
if ($dateonly == 0) {
	## Code List
	echo "<td valign=\"top\"><b>Project Code&nbsp;</b></td><td>
		<select name='projcode[]' size='4' multiple>";
	if ($allcode	==	0) {
		echo '<OPTION value="all">All Projects';
	} else {
		echo '<OPTION SELECTED value="all">All Projects';
	}
	for ($i=0; $i<$codeno; $i++) {
		$sel =	0;
		$code	= $codelist[0][$i];
		for ($j=0; $j<$no_code_sel; $j++) {
			if ($sel_code[$j] == $code) {
				$sel =	1;
				break;
			}
		}
		if ($sel ==	1) {
			echo "<option selected>".$code;
		} else {
			echo "<option>".$code;
		}
	}
	echo "</select></td></tr>";

	## Staff list
	echo '<tr><td valign=\"top\"><b>Staff Name&nbsp;</b></td>';
	echo '<td><select name="staffname[]" size="4" multiple>';
	if ($allstaff	==	0) {
		echo '<OPTION value="all">All Staff';
	} else {
		echo '<OPTION SELECTED value="all">All Staff';
	}
	for ($i=0; $i<$staffno; $i++) {
		$ename	=	$stafflist[0][$i];
		$fname	=	$stafflist[1][$i].' '.$stafflist[2][$i].' '.$stafflist[3][$i];
		$sel =	0;
		for ($j=0; $j<$no_staff_sel; $j++) {
			if ($sel_staff[$j] == $ename) {
				$sel =	1;
				break;
			}
		}
		if ($sel == 	1) {
			echo '<OPTION SELECTED value="'.$ename.'">'.$fname;
		} else {
			echo '<OPTION value="'.$ename.'">'.$fname;
		}
	}
	echo '</slelct></td></tr><tr>';
}
##########################################################################
## Type of summary
$incjava = 1;
$j = 0;						# for all groups
$sum_type_array[$j][0] = "MthSum";
$sum_type_array[$j][1] = "Monthly";
$j++;
if ($priv == "10" ) {				# for director group
	$sum_type_array[$j][0] = "QtrSum";
	$sum_type_array[$j][1] = "Quarterly";
	$j++;
	$sum_type_array[$j][0] = "HfySum";
	$sum_type_array[$j][1] = "Half Yearly";
	$j++;
}
if ($priv == "00") {				# for developer
	$sum_type_array[$j][0] = "QtrSum";
	$sum_type_array[$j][1] = "Quarterly";
	$j++;
	$sum_type_array[$j][0] = "HfySum";
	$sum_type_array[$j][1] = "Half Yearly";
	$j++;
	$sum_type_array[$j][0] = "YlySum";
	$sum_type_array[$j][1] = "Yearly";
	$j++;
	$sum_type_array[$j][0] = "SlpSum";
	$sum_type_array[$j][1] = "Selected Period";
	$j++;
}
if ($priv == "20" ) {				# for project leaders group
	$sum_type_array[$j][0] = "SlpSum";
	$sum_type_array[$j][1] = "Selected Period";
	$j++;
}

if ($sumanatype == "") {
	$sumanatype= 0;
}
echo "<tr><td valign=bottom><font size=2><b>Summary Type&nbsp;</b></font></td>";
echo "<td valign=middle><select name='sumanatype'>";
	for ($i=0; $i<count($sum_type_array); $i++) {
		$tmp = $sum_type_array[$i][1];
		if ($sumanatype == $i) {
			echo "<option selected value=\"$i\">$tmp";
		} else {
			echo "<option value=\"$i\">$tmp";
		}
	}
//echo "</option></select></td></tr>";
echo "</option></select>&nbsp;&nbsp;";
echo "<font size=2><b>Refresh This Form</b></font></th>";
echo "<select name=\"rebuild\">";
echo "<option>No";
echo "<option>Yes";
echo "</option></select>";
echo "</td></tr>";

##########################################################################
## period definition
$ptype = $sum_type_array[$sumanatype][0];
if($priv == "00") {
	//echo $ptype."<br>";
}
if ($ptype != "SlpSum") {
	echo "<tr><td align=right valign=bottom><font size=2 color=#0000ff><b>For&nbsp;</b></font></td>";
}
if ($ptype == "MthSum") {
	echo "<td><select name=\"selmonth\">";
	if ($selmonth == "") {
		$selmonth = date("m");
		if (substr($selmonth, 0, 1) == "0") {
			$selmonth = substr($selmonth, 1, 2);
		}
		$selmonth = $selmonth-1;
		if ($selmonth==0) {
			$selmonth = 12;
		}
	}
	for ($i=1; $i<13; $i++) {
		if ($selmonth == $i) {
			echo "<option selected value=$i>$mth[$i]";
		} else {
			echo "<option value=$i>$mth[$i]";
		}
	}
	echo "</option></select>&nbsp;";
}
if ($ptype == "QtrSum") {
	$otherp[0] = "March Qtr";
	$otherp[1] = "June Qtr";
	$otherp[2] = "September Qtr";
	$otherp[3] = "December Qtr";
	if ($selopd == "" || $selopd>3 || $rebuild == "Yes") {
		$selopd = (int)((date("n")-1)/3);
	}
	echo "<td><select name=\"selopd\">";
	for ($i=0; $i<4; $i++) {
		if ($i==$selopd) {
			echo "<option selected value=\"$i\">".$otherp[$i];
		} else {
			echo "<option value=\"$i\">".$otherp[$i];
		}
	}
	echo "</option></select>&nbsp;";
}
if ($ptype == "HfySum") {
	$otherp[0] = "Jan to June";
	$otherp[1] = "Jul to Dec";
	if ($selopd == "" ||  $selopd>1 || $rebuild == "Yes") {
		if (date("n")<=6) {
			$selopd = 0;
		} else {
			$selopd = 1;
		}
	}
	echo "<td><select name=\"selopd\">";
	for ($i=0; $i<2; $i++) {
		if ($i==$selopd) {
			echo "<option selected value=\"$i\">".$otherp[$i];
		} else {
			echo "<option value=\"$i\">".$otherp[$i];
		}
	}
	echo "</option></select>&nbsp;";
}
if ($ptype == "YlySum") {
	echo "<td>";
}
## show year selection
if ($ptype != "SlpSum") {
	if (!$selyear) {
		$selyear = date("Y");
		if ($ptype == "MthSum" && $selmonth == 12) {
			$selyear = $selyear -1;
		}
	}
	
	$yend = date("Y");
	if (($selyear - 2000) > 20) {
		$ys = $yend - 20;
	} else {
		$ys = 2000;
	}
	$yend = $yend + 1;
	echo "<select name=\"selyear\">";
	for ($i=$ys; $i<=$yend; $i++) {
		$tmp = $i + 1;
		if ($ptype == "YlySum") {
			$tmp = "$i/$tmp";
		} else {
			$tmp = $i;
		}
		if ($i == $selyear) {
			echo "<option selected value=\"$i\">$tmp";
		} else {
			echo "<option value=\"$i\">$tmp";
		}
	}
	echo "</tr>";
}

if ($ptype == "SlpSum") {
	$fontl = "<font size=1><b>";
	$fontr = "</b></font>";
	echo "<tr><td align=right valign=bottom>
		<font size=2 color=#0000ff><b>Start from&nbsp;</b></font></td><td>";
	$calfromyear	=	"calyearstart";
	$calfrommonth	=	"calmonthstart";
	$calfromday	=	"caldaystart";
	if ($calyearstart) {
		$year =	$calyearstart;
		$month =	$calmonthstart;
		$day =	$caldaystart;
	} else {
		$year = 2000;
		$month = 7;
		$day = 1;
		$moffset = 0;
	}

	include("calender_mmddyyyy.inc");
	echo "</td></tr>";
	echo "<tr><td align=right valign=bottom><font size=2 color=#0000ff><b>End before&nbsp;</b></font></td><td>";
	$calfromyear	=	"calyearend";
	$calfrommonth	=	"calmonthend";
	$calfromday	=	"caldayend";
	$moffset = 0;
	if ($calyearend) {
		$year =	$calyearend;
		$month =	$calmonthend;
		$day =	$caldayend;
	} else {
		$year =	"";
		$month =	"";
		$day =	"";
	}
	include("calender_mmddyyyy.inc");
	echo "</td></tr>";
if ($priv != "20" ) {
	echo "<tr><td align=right valign=bottom><font size=2 color=#0000ff><b>
		Present &nbsp;</b></font></td>";
	$j=0;
	$dpv[$j]="a"; $dpcap[$j]="All Data"; $j++;
	$dpv[$j]="w"; $dpcap[$j]="Weekly"; $j++;
	$dpv[$j]="m"; $dpcap[$j]="Monthly"; $j++;
	$dpv[$j]="q"; $dpcap[$j]="Quarterly"; $j++;
	$dpv[$j]="hy"; $dpcap[$j]="Half Yearly"; $j++;
	$dpv[$j]="y"; $dpcap[$j]="Yearly"; $j++;
	echo "<td><select name=datapresent>";
		if ($datapresent == "") {
			$datapresent = $dpv[0];
		}
		for ($i=0; $i<$j; $i++) {
			if ($dpv[$i] == $datapresent) {
				echo "<option value=$dpv[$i] selected>$dpcap[$i]";
			} else {
				echo "<option value=$dpv[$i]>$dpcap[$i]";
			}
		}
	echo "</option></select></tr>";
}
}

##########################################################################
## Type of report format
if ($priv == "00" || $priv == "10") {
	$nofmt = 0;
	$reportfmt[$nofmt][0] = "normal";
	$reportfmt[$nofmt][1] = "Normal";
	$nofmt++;
	$reportfmt[$nofmt][0] = "OLrRorC";
	$reportfmt[$nofmt][1] = "Outline";//: Row or Column Expand/Contract Only
	$nofmt++;
	/*
	if ($priv=="00") {	// to control which format is available to others
		$reportfmt[$nofmt][0] = "OLRandC";
		$reportfmt[$nofmt][1] = "Outline: Row and Column Expand/Contract";
		$nofmt++;
	}
	//*/
	if ($rptfmt == "") {
		$rptfmt = $reportfmt[1][0];
	}
	
	if ($leader == "y") {
		$rptfmt = $reportfmt[0][0];
		$nofmt=1;
	} else {
		$rptfmt = $reportfmt[1][0];
	}
	
	echo "<tr><td>&nbsp;</td></tr>";
	echo "<tr><td valign=bottom><font size=2><b onMouseOver=\"self.status='Only Available to All Codes Selection.';\" 
		onMouseOut=\"self.status='Timesheet Analysis and Reports.';\">
		Report Format</b></font></td><td><select name=rptfmt>";
	for ($i=0; $i<$nofmt; $i++) {
		$tmp = $reportfmt[$i][0];
		if ($rptfmt == $tmp) {
			echo "<option value=\"$tmp\" selected>".$reportfmt[$i][1];
		} else {
			echo "<option value=\"$tmp\">".$reportfmt[$i][1];
		}
	}
	echo "</option></select></td></tr>";
	echo "<tr><td>&nbsp;</td></tr>";
}
##########################################################################
## Code selection
if ($priv == "00" || $priv == "10") {
	$tmp = "onChange=\"showcodeselfieldset();\"";
	echo "<tr><td valign=bottom><font size=2><b>Code Selection&nbsp;</b></font></td><td>
		<select name=leader $tmp>";
	if ($leader == "y") {
		echo "<option value=\"n\">All Codes";
		echo "<option value=\"y\" selected>Selected Codes";
	} else {
		echo "<option value=\"n\" selected>All Codes";
		echo "<option value=\"y\">Selected Codes";
	}
	echo "</option></select></td></tr>";
}

if ($priv == "00" || $priv == "10" || $priv == "20") {
	include("ts_leader_special.inc");
}

echo "<tr><td>&nbsp;</td></tr>";

if ($priv == "00" && $ptype != "MthSum") {
	echo "<tr><th align=left><font size=2><b>Display Table</b></font></th>";
	echo "<td><select name=\"tabletoshow\">";
	if ($tabletoshow == "all") {
		echo "<option selected>all";
	} else {
		echo "<option>all";
	}
	for ($i=1; $i<7; $i++) {
		if ($tabletoshow == $i) {
			echo "<option selected>$i";
		} else {
			echo "<option>$i";
		}
	}
	echo "</option></select></td></tr>";
}

/*
echo "<tr><th><font size=2><b>Refresh This Form</b></font></th>";
echo "<td><select name=\"rebuild\">";
echo "<option>No";
echo "<option>Yes";
echo "</option></select></td></tr>";
//*/
##########################################################################
## command button
if ($email_name == "webmaster") {
	echo "<tr><td colspan=2>&nbsp;</td></tr>";
	echo "<tr><td>New Priviledge&nbsp;<select name=\"newpriv\">";
		$i = -1;
		$i++; $p[$i] = "00";
		$i++; $p[$i] = "10";
		$i++; $p[$i] = "20";
		/*
		$i++; $p[$i] = "30";
		$i++; $p[$i] = "40";
		$i++; $p[$i] = "50";
		$i++; $p[$i] = "100";
		*/
		$j = $i + 1;
		echo "<option selected>$priv";
		for ($i=0; $i<$j; $i++){
			if ($p[$i] != $priv) {
				echo "<option>$p[$i]";
			}
		}
	echo '</option></select><th><input type="submit" name="analysisreport" 
		value="Timesheet Analysis & Reports"></th>';
} else {
	echo '<tr><td colspan=2>&nbsp;</td></tr><tr>
		<th colspan=2>
		<button type="submit" name="analysisreport">
		<font color=#ff0000><b>Timesheet Analysis & Reports</b></font></button></th>';
}
echo '</tr></table></form>';
echo "</fieldset>";
if ($leader == "y" && $ptype == "SlpSum") {
	if ($selpdno == 0) {// || $selpdno >1
		echo "<hr><font size=4 color=#ff0000>$selpdno code selected. Please select one code only.</font><br><br>";
		exit;
	}
}
?>
</html>