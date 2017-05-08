<html>
<SCRIPT LANGUAGE=JavaScript>
	//window.defaultStatus = "Hello and Welcome to Intranet: Timesheet";
</SCRIPT>
<?php
	include('connet_root_once.inc');
	include("phpdir.inc");
	include("find_admin_ip.inc");

	##pass client info to next page
	$qry	=	'?'.base64_encode("priv=$priv&".$userinfo);

	$year0 = date("Y");
	$month0 = date("m");
## last month date
	if ($month0 == "1") {
		$yearL1 = $year0 - 1;
		$yearL2 = $year0;
		$monthL1 = 12;
		$monthL2 = $month0;
	} else {
		$yearL1 = $year0;
		$yearL2 = $year0;
		$monthL1 = $month0 - 1;
		$monthL2 = $month0;
	}
	if ((int)($monthL1) < 10) {
		$monthL1 = "0".(int)($monthL1);
	}
	if ((int)($monthL2) < 10) {
		$monthL2 = "0".(int)($monthL2);
	}

	$start_date = $yearL1."-".$monthL1."-01";
	$end_date = $yearL2."-".$monthL2."-01";

## last month timesheet count for administrator
if ($priv	==	'00' || $priv	==	'10' || $priv	==	'50') {
	$sql = "SELECT email_name as ename, yyyymmdd FROM timesheet.entry_no 
		WHERE yyyymmdd>='$start_date' and yyyymmdd<'$end_date' order by yyyymmdd, email_name;";
	$lmdate = "&start_date=$start_date&end_date=$end_date&dd=dd";
	$result =	mysql_query($sql);
	$noL =	mysql_num_rows($result);
	if ($priv	==	'00') {
		//echo $sql."no TS for last month = $noL <br>";
	}
	$i = 0;
	while (list($ename, $yyyymmdd) = mysql_fetch_array($result)) {
		$ts["$ename"]["$yyyymmdd"] = 1;
	}
	
	$sql = "SELECT email_name as ename from timesheet.employee 
		WHERE date_unemployed=\"0000-00-00\" and 	email_name!='webmaster';";
	$result =	mysql_query($sql);
	$noE =	mysql_num_rows($result);
	if ($priv	==	'00') {
		//echo $sql." no of employee = $noE <br>";
	}
	$i = 0;
	while (list($ename) = mysql_fetch_array($result)) {
		$emp[$i] = $ename;
		$i++;
	}

	include("ts_findwks_list.inc");
	for ($i=0; $i<$nowks; $i++) {
		$w = $wklist[$i];
		$m_list[$i] = "";
		$m_no[$i] = 0;
		for ($j=0; $j<$noE; $j++) {
			$p = $emp[$j];
			if ($ts["$p"]["$w"] == "") {
				$m_list[$i] = $m_list[$i]." $p";
				$m_no[$i] = $m_no[$i] + 1;
			}
		}
	}
	
	$missing = "";
	for ($i=0; $i<$nowks; $i++) {
		if ($m_list[$i]) {
		$missing = $missing.substr($wklist[$i],5,10)."(".$m_no[$i]."): ".$m_list[$i].". ";
		}
	}

	$tmp = $nowks*$noE;
	$tmp0 = $tmp - $noL;
	if ($tmp0 == 0) {
		$tmp0 = "OK";
	} elseif($tmp0 > 0) {
		$tmp0 = "<font color=#ff0000>$tmp0</font>";
	} 
	$noE = "$tmp0 ($noL/$tmp)";
	$noE = "$noL/$tmp";
}

## this month date
	if ($month0 == "12") {
		$year1 = $year0;
		$year2 = $year0 + 1;
		$month1 = $month0;
		$month2 = "01";
	} else {
		$year1 = $year0;
		$year2 = $year0;
		$month1 = $month0;
		$month2 = $month0 + 1;
	}
	if ((int)($month1) < 10) {
		$month1 = "0".(int)($month1);
	}
	if ((int)($month2) < 10) {
		$month2 = "0".(int)($month2);
	}	
	$start_date = $year1."-".$month1."-01";
	$end_date = $year2."-".$month2."-01";

##	SHOW pages for administrator 
if ($priv	==	'00' || $priv	==	'10' || $priv	==	'50') {
	include("ts_showlastmonthtsht.inc");
	echo '<hr>';
	echo "<font size=2><b>Section A: Admin's Pages</b></font><ul>";
	//	check whether any timesheet need to update	
	//	check how many timesheets has received for current MONTH
	//echo $start_date."-".$end_date."<br>";
	$sql = "SELECT email_name as ename, yyyymmdd FROM timesheet.entry_no 
		WHERE yyyymmdd>='$start_date' and yyyymmdd<'$end_date' order by yyyymmdd, email_name;";
	$result	=	mysql_query($sql);
	$no	=	mysql_num_rows($result);
	$tmdate = "&start_date=$start_date&end_date=$end_date&dd=dd";
	if ($priv	==	'00') {
		//echo $sql." $no<br>";
		//echo $tmdate."<br>";
	}
	
	$statuscontext = "List number of timesheet received for last current month or for a selected period of time.";
	include("self_status.inc"); //'.$status.'
	$status0 = $status;
	$statuscontext = $missing;
		if ($priv == "00") {
			$statuscontext = "ts_no_check.php ($statuscontext)";
		}
	include("self_status.inc"); //'.$status.'
	$statusl = $status;
	
	$statuscontext = "List number of timesheet received for this month.";
		if ($priv == "00") {
			$statuscontext = "ts_no_check.php ($statuscontext)";
		}
	include("self_status.inc"); //'.$status.'
	$statust = $status;
	echo "<li><a $status><font size=\"2\">TS Count&nbsp;";
	if ($description == "Y") {
		echo "[<a $statusl href=\"/$phpdir/ts_no_check.php$qry$lmdate target=\"main\">
			<font size=\"2\" color=#ff0000>LM: $noE</font></a>]";
	}
	echo "[<a $statust href=\"/$phpdir/ts_no_check.php$qry$tmdate target=\"main\">
			<font size=\"2\" color=#ff0000>TM: $no</font></a>]</font>".
		"</li><br>";
	/*
	include("find_admin_ip.inc");
	if ($email_name == "webmaster" || $privspec == 10) {
		echo "$qry<br>$lmdate<br>$tmdate<br>";
		echo "$userinfo<br>";
	}
	//*/
}

if ($priv	==	'00' || $priv	==	'10') {
	//	Summary review
	$statuscontext = "TS Analysis & Reports.";
		if ($priv == "00") {
			$statuscontext = "ts_ana_rpt.php ($statuscontext)";
		}
	include("self_status.inc"); //'.$status.'
	echo '<li><font size="2"><align="left">'
		.'<a href="/'.$phpdir.'/ts_ana_rpt.php'.$qry
		.'" target="main"'.$status.'>TS Analysis & Reports</a></font></li>';		
}
	
if ($priv	==	'00' || $priv	==	'10') {
	$statuscontext = "Add new records to tables.";
		if ($priv == "00") {
			$statuscontext = "admin_add_rcd.php ($statuscontext)";
		}
	include("self_status.inc"); //'.$status.'
	echo '<br><br><li><a href="admin_add_rcd.php'.$qry.'" target="main" '.$status.'>'
		.'<font size="2" align="center">Add New Records</font></a></li>';

	$statuscontext = "Modify Project Code.";
		if ($priv == "00") {
			$statuscontext = "admin_mod_projcode.php ($statuscontext)";
		}
	include("self_status.inc"); //'.$status.'
	echo '<li><a href="admin_mod_projcode.php'.$qry.'" target="main" '.$status.'>'
		.'<font size="2" align="center">Modify Project Code</font></a></li>';

	$statuscontext = "Contract Staff.";
		if ($priv == "00") {
			$statuscontext = "admin_contract_staff_list.php ($statuscontext)";
		}
	include("self_status.inc"); //'.$status.'
	echo '<li><a href="admin_contract_staff_list.php'.$qry.'" target="main" '.$status.'>'
		.'<font size="2" align="center">Modify Contract Staff</font></a></li>';

	$statuscontext = "Create GHR Cost Center.";
		if ($priv == "00") {
			$statuscontext = "rla_fin_add_GHRccc.php ($statuscontext)";
		}
	include("self_status.inc"); //'.$status.'
	echo '<li><a href="rla_fin_add_GHRccc.php'.$qry.'" target="main" '.$status.'>'
		.'<font size="2" align="center">Edit GHR Account Codes</font></a></li>';

	##Link RLA Project Code To GHR Charging Code for summary report to greenhill rd and for 
	##ordering system
	$statuscontext = "Link RLA Project Code To GHR Charging Code.";
	if ($priv == '00') {
		$statuscontext = "rla_fin_code_linkghr.php ($statuscontext)";
	}
	include("self_status.inc"); //'.$status.'
	echo '<li><font size="2"><align="left">Link RLA-GHR Codes For:<br>'
		.'<a href="/'.$phpdir.'/rla_fin_code_linkghr.php'.$qry
		.'" target="main"'.$status.'>[<font color=#ff0000>Order</font>]</a>';
	//*
	if ($priv == '00') {
		$statuscontext = "rla_fin_code_linkghr_for_tsht.php ($statuscontext)";
	}
	include("self_status.inc"); //'.$status.'
	echo '<a href="/'.$phpdir.'/rla_fin_code_linkghr_for_tsht.php'.$qry
		.'" target="main"'.$status.'>[<font color=#ff0000>Timesheet</font>]</font></a></font>';
	//*/
	echo '</li>';

	$statuscontext = "AL+LSL+SL Leave.";//+TIL
		if ($priv == "00") {
			$statuscontext = "admin_al_lsl_sl_til.php ($statuscontext)";
		}
	include("self_status.inc"); //'.$status.'
	echo '<br><br><li><a href="admin_al_lsl_sl_til.php'.$qry.'" target="main" '.$status.'>'
		.'<font size="2" align="center">AL+LSL+SL</font></a></li>';//+TIL
		
	//	Project budget
	$statuscontext = "Project budget For Project Leader.";
	if ($priv == "00") {
		$statuscontext = "ts_proj_ctrl.php ($statuscontext)";
	}
	if ($priv == "00" || $priv == "10") {//|| $priv	==	'50'
		include("self_status.inc"); //'.$status.'
		echo '<br><li><font size="2"><align="left">'
			.'<a href="/'.$phpdir.'/ts_proj_ctrl.php'.$qry
			.'" target="main"'.$status.'>Project Budget</a></font></li>';
	}
	
	echo "<li><font size=\"2\">Summary for:<br>";
	//	Marketing Summary
	if ( $rlaserver == $thisserver ) {
		$statuscontext = "TS Marketing Summary Reports.";
		if ($priv == "00") {
				$statuscontext = "ts_sum_market.php ($statuscontext)";
		}
		include("self_status.inc"); //'.$status.'
		echo '<align="left">'
		.'<a href="/'.$phpdir.'/ts_sum_market.php'.$qry
		.'" target="main"'.$status.'>[Marketing]</a></font>';
	}

	//	Travel Summary
	$statuscontext = "Travel Summary.";
	if ($priv == "00") {
		$statuscontext = "ts_sum_trvl.php ($statuscontext)";
	}
	include("self_status.inc"); //'.$status.'
	if ($priv == "00" ) {//|| $priv == "10" || $priv	==	'50'
		echo '<font size="2"><align="left">'
			.'<a href="/'.$phpdir.'/ts_sum_trvl.php'.$qry
			.'" target="main"'.$status.'>[Travel]</a></font>';
	}

	//	Research Summary
	$statuscontext = "Research Summary.";
	if ($priv == "00") {
		$statuscontext = "ts_sum_resch.php ($statuscontext)";
	}
	include("self_status.inc"); //'.$status.'
	if ($priv == "00" ) {//|| $priv == "10" || $priv	==	'50'
		echo '<br><font size="2"><align="left">'
			.'<a href="/'.$phpdir.'/ts_sum_resch.php'.$qry
			.'" target="main"'.$status.'>[Research]</a></font>';
	}
	
	$statuscontext = "Proj Cost";
	if ($priv == "00") {
		$statuscontext = "ts_sum_projcost.php ($statuscontext)";
	}
	include("self_status.inc"); //'.$status.'
	if ($priv == "00" ) {//|| $priv == "10" || $priv	==	'50'
		echo '<font size="2"><align="left">'
			.'<a href="/'.$phpdir.'/ts_sum_projcost.php'.$qry
			.'" target="main"'.$status.'>[Cost]</a></font>';
	}
	
	echo "</li>";
}

if ($priv	==	'00' || $priv	==	'10' || $priv	==	'50') {
	$section = "Section B: ";
	mysql_close();
	echo '</ul>';
}
	
	echo '<hr>';
	echo "<font size=2><b>$section$first_name's Pages</b></font><ul>";

##	Compose timesheet
	$statuscontext = "Timesheet preparation. You can compose a new timesheet from scratch or "
	.	"load a timesheet to modify.";
		if ($priv == "00") {
			$statuscontext = "ts_compose.php ($statuscontext)";
		}
	include("self_status.inc"); //'.$status.'
	echo '<li><align="left"><font size="2"><a href="/'.$phpdir.'/ts_compose.php'.
	$qry.'" target="main"'.$status.'>Compose Timesheet</a></font></li><br>';

##	Select project code
	$statuscontext = "Select your private project codes. You can reselect your codes at any time.";
		if ($priv == "00") {
			$statuscontext = "ts_code_selection.php ($statuscontext)";
		}
	include("self_status.inc"); //'.$status.'
	echo '<li><align="left"><font size="2"><a href="/'.$phpdir.'/ts_code_selection.php'.
	$qry.'" target="main"'.$status.'>Select Project Codes</a></font></li><br>';

##	Place a mail automation notice
/*
if ($priv == "00") {
	$statuscontext = "Place a new mail message to be sent automatically by server.";
		if ($priv == "00") {
			$statuscontext = "mail_message.php ($statuscontext)";
		}
	include("self_status.inc");
	echo '<li><align="left"><font size="2">Message Automation<a href="/'.$phpdir.'/mail_message.php'.
	$qry.'&action=new" target="main"'.$status.'>[New]</a></font>';
	$statuscontext = "Stop a mail message to be sent automatically by server.";
		if ($priv == "00") {
			$statuscontext = "mail_message.php ($statuscontext)";
		}
	include("self_status.inc");
	echo '<font size="2"><a href="/'.$phpdir.'/mail_message.php'.
	$qry.'&action=modify" target="main"'.$status.'>[Modify]</a></font>';
	echo '</li><br>';
}
*/

##	project code list
	$statuscontext = "List all project codes and their definition in a new window.";
		if ($priv == "00") {
			$statuscontext = "ts_grpcode_list.php ($statuscontext)";
		}
	include("self_status.inc"); //'.$status.'
	echo '<li><align="left"><font size="2">[<a href="/'.$phpdir.'/ts_code_list.php'.
	$qry.'" target="_blank"'.$status.'>Project</a>]&nbsp;[</font>';
	$statuscontext = "List all Project Group and their definition in a new window.";
	echo '<align="left"><font size="2"><a href="/'.$phpdir.'/ts_grpcode_list.php'.
	$qry.'" target="_blank"'.$status.'>Group</a>]&nbsp;Code Definition</font></li><br>';

//(<font color=#ff0000>NEW*</font>)
##	add new country and company to list
	if ($priv	==	'00' || $priv	==	'10') {
	} else {
		$statuscontext = "Add new records to tables.";
		if ($priv == "00") {
			$statuscontext = "admin_add_rcd.php ($statuscontext)";
		}
		include("self_status.inc"); //'.$status.'
		echo '<li><a href="admin_add_rcd.php'.$qry.'" target="main" '.$status.'>'
		.'<font size="2" align="center">Add Company & Country</font></a></li>';
	}

if ($priv	==	'20') {
	//	Summary review for project leaders
	$statuscontext = "TS Analysis & Reports For Project Leader.";
		if ($priv == "00") {
			$statuscontext = "ts_ana_rpt.php ($statuscontext)";
		}
	include("self_status.inc"); //'.$status.'
	echo '<br><li><font size="2"><align="left">'
		.'<a href="/'.$phpdir.'/ts_ana_rpt.php'.$qry
		.'" target="main"'.$status.'>TS Analysis & Reports</a></font>';
		$statuscontext = "Proj Cost";
		
	$statuscontext = "Project Cost";
	include("self_status.inc"); //'.$status.'
	echo '<br><font size="2"><align="left">'
		.'<a href="/'.$phpdir.'/ts_sum_projcost.php'.$qry
		.'" target="main"'.$status.'>[Cost]</a></font>';

	echo '</li><br>';
/*	
	//	Project budget for project leaders
	$statuscontext = "Project budget For Project Leader.";
	include("self_status.inc"); //'.$status.'
	echo '<br><li><font size="2"><align="left">'
		.'<a href="/'.$phpdir.'/ts_proj_ctrl.php'.$qry
		.'" target="main"'.$status.'>Project Budget</a></font></li><br>';
//*/
}

##	My Timesheet Count
	//echo $start_date."-".$end_date."<br>";
	$sql = "SELECT yyyymmdd FROM timesheet.entry_no WHERE yyyymmdd>='$start_date' and yyyymmdd<'$end_date' and email_name='$email_name' order by yyyymmdd;";
	include('connet_root_once.inc');
	$result	=	mysql_query($sql);
	$no	=	mysql_num_rows($result);
	$statuscontext = "List timesheets you have sent for current month or for a period of time selected.";
		if ($priv == "00") {
			$statuscontext = "ts_mytimesheet.php ($statuscontext)";
		}
	include("self_status.inc"); //'.$status.'
	echo '<br><li><a href="/'.$phpdir.'/ts_mytimesheet.php'.$qry
	.$qry.'" target="main"'.$status.'><font size="2">My TS Count </font></font>'
	.'<b><font size="2" color=#ff0000> (This month: '.$no.')</b></font></a></li>';
	
##	Query Leave
if ($priv == "00") {// || $email_name == ""
	$statuscontext = "You can check your annual or sick leave record.";
		if ($priv == "00") {
			$statuscontext = "ts_myleave.php ($statuscontext)";
		}
	include("self_status.inc"); //'.$status.'
	echo '<br><li><align="left"><font size="2"><a href="/'.$phpdir.'/ts_myleave.php'.
	$qry.'" target="main"'.$status.'>My Leave Records</a></font></li><br>';
}

/*
##	Search staff info
	$statuscontext = "List staff internal telephone number and email address.";
		if ($priv == "00") {
			$statuscontext = "ts_rlastaff.php ($statuscontext)";
		}
	include("self_status.inc"); //'.$status.'
	echo '<li><align="left"><font size="2"><a href="/'.$phpdir.'/ts_rlastaff.php'.
	$qry.'" target="main"'.$status.'>Staff Info</a></font>';
//*/
	
	echo '</ul>';
	
####################################################
## client last month timesheet count warning
	if ($month0 == "01") {
		$year1 = $year0 - 1;
		$year2 = $year0;
		$month1 = 12;
		$month2 = $month0;
	} else {
		$year1 = $year0;
		$year2 = $year0;
		$month1 = $month0 - 1;
		$month2 = $month0;
	}
	if ((int)($month1) < 10) {
		$month1 = "0".(int)($month1);
	}
	if ((int)($month2) < 10) {
		$month2 = "0".(int)($month2);
	}	
	$start_date = $year1."-".$month1."-01";
	$end_date = $year2."-".$month2."-01";

	$sqlme = "SELECT yyyymmdd FROM timesheet.entry_no 
		WHERE yyyymmdd>='$start_date' and yyyymmdd<'$end_date' and email_name='$email_name' ".
		"order by yyyymmdd;";
	if ($priv == "00") {
		//echo "$sqlme<br>";
	}
	$result =	mysql_query($sqlme);
	$noL =	mysql_num_rows($result);
	$i = 0;
	while (list($yyyymmdd) = mysql_fetch_array($result)) {
		$myts["$yyyymmdd"] = 1;
	}
	$missingme = "";
	$mytsctr = 0;
	include("ts_findwks_list.inc");
	for ($i=0; $i<$nowks; $i++) {
		$w = $wklist[$i];
		if ($myts["$w"] == "") {
			$missingme = $missingme." $w<br>";
		} else {
			$mytsctr++;
		}
	}
	
	if ($mytsctr != $nowks) {
		$feedback_message = "<h1>Hi, $first_name</h1>"
			."<h3><font color=\"#ff0000\">You have not completed your timesheets "
			."for the following week(s).</font></h3>"
			."<br><b>$missingme</b><br><b>Please complete above timesheets ASAP.</b><br><br><br>"
			."Regards<br><br>Timesheet Server<br>".date("l, F j, Y H:i")
			.'<form><button type="submit" onClick="window.close();true;"><b>Close this window</b></button> 
</form>';
		$width = 500;
		$height = 400;
		include("find_admin_ip.inc");
		
//*
		if ($privspec == 10) {//owen
		} elseif ($tsentry =='n') {		
		} else {
			$sqlmsg = "SELECT tsentry FROM timesheet.employee WHERE email_name='$email_name'";
    		$resultmsg = mysql_query($sqlmsg );
    		include("err_msg.inc");
    		list($tsentry) = mysql_fetch_array($resultmsg);
			if (strtoupper($description) == "Y" && strtoupper($tsentry) == "Y") {
 				//include("feedback.inc");
   				//echo "$description == description  $tsentry == tsentry <br>";
				//include("ts_showlastmonthtsht.inc");
				//echo getenv("server_name");
				//exit;
				if (getenv("server_name") != "roger.rla.com.au") {
					include("feedback.inc");
				}
			}
		}
//*/
	}
?>
</html>
