<?php
/* $Id: rep_properties.php,v 1.2 2001/02/02 06:03:08 dwilson Exp $ */
//		File: 		rep_properties.php
//		Purpose: 	Display properties and source of report
//		Author:		Dan Wilson
//		Date:		05 Dec 2000

include("lib.inc.php");
include("header.inc.php");

echo "<h1>$strReports</h1>";

$qrGetRep = "SELECT * FROM ppa_reports WHERE report_id = $rep_id";
$rsGetRep = @pg_exec($link, $qrGetRep) or pg_die(pg_errormessage(), $qrGetRep, __FILE__, __LINE__);
if (pg_numrows($rsGetRep) > 0) {
	$rep_row = pg_fetch_array($rsGetRep, 0);
} else {
	echo "Report Not Found... error";
	exit;
}

$query = "?server=$server&db=$db&goto=reports.php";
$rep_query = "?server=$server&db=$rep_row[db_name]&goto=rep_properties.php";

echo "
	<table border=\"$cfgBorder\">
	<tr>
		<th width=\"10%\">$strReportName:</th>
		<td bgcolor=\"$cfgBgcolorOne\">$rep_row[report_name]</td>
	</tr>
	<tr bgcolor=\"$cfgBgcolorTwo\">
		<td><b>$strDatabase:</b></td>
		<td>$rep_row[db_name]</td>
	</tr>
	<tr bgcolor=\"$cfgBgcolorOne\">
		<td valign=\"top\"><b>$strDescription:</b></td>
		<td valign=\"top\">$rep_row[descr]</td>
	</tr>
	<tr bgcolor=\"$cfgBgcolorTwo\">
		<td colspan=\"2\" valign=\"top\"><b>$strDefinition</b>:</td>
	</tr>
	<tr bgcolor=\"$cfgBgcolorOne\">
		<td colspan=\"2\" valign=\"top\">" . nl2br($rep_row[report_sql]) . "</td>
	</tr>
	</table>

	<br><br>
	<ul>
		<li><a href=\"sql.php$rep_query&sql_query=" . urlencode($rep_row[report_sql]) . "\">$strRun</a>
		<li><a href=\"rep_create.php$query&rep_id=$rep_row[report_id]\">$strChange</a>
		<li><a href=\"sql.php$query&sql_query=" . urlencode("DELETE FROM ppa_reports WHERE report_id = $rep_row[report_id]") . "&zero_rows=" . urlencode("$strReport $rep_row[report_name] $strHasBeenDropped") . "\">$strDrop</a>
	</ul>
";

include("footer.inc.php");
?>