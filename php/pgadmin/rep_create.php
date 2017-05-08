<?php
/* $Id: rep_create.php,v 1.2 2001/02/02 06:03:08 dwilson Exp $ */
//		File: 		rep_create.php
//		Purpose: 	Create Report
//		Author:		Dan Wilson
//		Date:		05 Dec 2000

include("lib.inc.php");
include("header.inc.php");

echo "<h1>$strReports</h1>";

$qrGetDB = "SELECT datname FROM pg_database WHERE datname NOT IN ('phppgadmin', 'template1') ORDER BY datname";
$rsGetDB = @pg_exec($link, $qrGetDB) or pg_die(pg_errormessage(), $qrGetDB, __FILE__, __LINE__);
$nmGetDB = pg_numrows($rsGetDB);
for ($intDB = 0; $intDB < $nmGetDB; $intDB++) {
	$arDB[] = pg_result($rsGetDB, $intDB, "datname");
}

if (isset($rep_id)) {
	$qrGetRep = "SELECT * FROM ppa_reports WHERE report_id = $rep_id";
	$rsGetRep = @pg_exec($link, $qrGetRep) or pg_die(pg_errormessage(), $qrGetRep, __FILE__, __LINE__);
	if (pg_numrows($rsGetRep) > 0) {
		$rep_row = pg_fetch_array($rsGetRep, 0);
	}
	$strRepAction = "edit";
	$strSubmitTxt = $strUpdate;
} else {
	$rep_row[db_name] 		= $db_name;
	$rep_row[report_sql] 	= stripslashes($rep_sql);
	$strRepAction = "create";
	$strSubmitTxt = $strCreate;
}

$strSelGetDB =  select_box(array("name"=>"rep_db", "values"=>$arDB, "selected"=>$rep_row[db_name]));

echo "
	<form action=\"reports.php\" method=\"POST\">
	<table border=\"$cfgBorder\">
	<tr>
		<td>$strReportName:</td>
		<td>
			<input type=\"text\" name=\"rep_name\" value=\"$rep_row[report_name]\" maxlength=\"50\" size=\"50\">
		</td>
	</tr>
	<tr>
		<td>$strDatabase:</td>
		<td>$strSelGetDB</td>
	</tr>
	<tr>
		<td valign=\"top\">$strDescription:</td>
		<td>
			<textarea name=\"rep_descr\" wrap=\"virtual\" style=\"width:$cfgMaxInputsize;\" rows=\"3\">$rep_row[descr]</textarea>
		</td>
	</tr>
	<tr>
		<td valign=\"top\">$strDefinition:</td>
		<td>
			<textarea name=\"rep_source\" wrap=\"virtual\" style=\"width:$cfgMaxInputsize;\" rows=\"5\">$rep_row[report_sql]</textarea>
		</td>
	</tr>
	<tr>
		<td align=\"center\" colspan=\"2\">
			<input type=\"submit\" name=\"submit_report\" value=\"$strSubmitTxt\">
			<input type=\"reset\" value=\"$strReset\">
			<input type=\"button\" value=\"$strCancel\" onClick=\"history.back();\">
		</td>
	</tr>
	<input type=\"hidden\" name=\"rep_action\" value=\"$strRepAction\">
	<input type=\"hidden\" name=\"rep_id\" value=\"$rep_id\">
	</form>
";

include("footer.inc.php");
?>