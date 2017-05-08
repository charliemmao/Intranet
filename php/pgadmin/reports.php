<?php
/* $Id: reports.php,v 1.3 2001/02/19 21:19:34 joe Exp $ */

include("lib.inc.php");
include("header.inc.php");

echo "<h1>$strReports</h1>";
echo $strReportsRequire, "<br><br>";
if ($btnCreateDB == $strYes) {
	$qrCreateDB = "CREATE DATABASE phppgadmin";
	if (!pg_exec($link, $qrCreateDB)) {
		pg_die(pg_errormessage(), $qrCreateDB, __FILE__, __LINE__);
	} else {
		$message = "$strDatabase phppgadmin $strHasBeenCreated";
		// $sql_query = $qrCreateDB;
		$reload = true;
	}
} elseif ($btnCreateDB == $strNo) {
}

if (!empty($message)) {
	show_message($message);	
}

$qrDBExist = "SELECT datname FROM pg_database WHERE datname = 'phppgadmin'";

if (!$rsDBExist = pg_exec($link, $qrDBExist)) {
	pg_die(pg_errormessage(), $qrDBExist, __FILE__, __LINE__);
}

if (pg_numrows($rsDBExist) < 1) {
	echo $strDoYouReally, " ", $strCreateDB, ": phppgadmin?";
	// Would you like to create the phpPgAdmin database?
	// echo $strCreateDBInvite;
?>
   <form action="reports.php" method="post" enctype="application/x-www-form-urlencoded">
   <input type="submit" name="btnCreateDB" value="<?php echo $strYes; ?>">
   <input type="submit" name="btnCreateDB" value="<?php echo $strNo; ?>" onClick="history.back() ">
   </form>
<?php
} else {

	$db = "phppgadmin";
	$conn_str = "user=$cfgServer[stduser] password=$cfgServer[stdpass] ";
	if (!$cfgServer[local]) {
		$conn_str .= "host=$cfgServer[host] ";
	}
	$conn_str .= "port=$cfgServer[port] dbname='$db'";
	
	$link_rep = @pg_connect($conn_str) or pg_die(pg_errormessage(), "Unable to connect: $conn_str", __FILE__, __LINE__);
	
	$qrTblExist = "SELECT tablename FROM pg_tables WHERE tablename = 'ppa_reports'";
	
	if (!$rsTblExist = pg_exec($link_rep, $qrTblExist)) {
		pg_die(pg_errormessage(), $qrTblExist, __FILE__, __LINE__);
	}
	
	if (pg_numrows($rsTblExist) < 1) {
		$qrCreateTbl = "
			CREATE TABLE ppa_reports (
				report_id SERIAL PRIMARY KEY, 
				report_name	varchar(50) NOT NULL,
				db_name varchar(32) NOT NULL, 
				date_created date DEFAULT 'now' NOT NULL,
				created_by varchar(40) NOT NULL,
				descr varchar(255),
				report_sql text NOT NULL
			);
			CREATE INDEX rep_db_name_idx ON ppa_reports(db_name);
			CREATE INDEX rep_report_name_idx ON ppa_reports(report_name);
			CREATE INDEX rep_created_idx ON ppa_reports(date_created);
		";
		
		if (!pg_exec($link_rep, $qrCreateTbl)) {
			pg_die(pg_errormessage(), $qrCreateTbl, __FILE__, __LINE__);
		} else {
			// $sql_query = $qrCreateTbl;
			$message = "$strTable ppa_reports $strHasBeenCreated";
			show_message($message);
		}
	}

	// If we are creating a new report, do it here
	if ($rep_action == "create") {
		$qrInsertRep = "INSERT INTO ppa_reports (report_name, db_name, created_by, descr, report_sql) VALUES ('$rep_name', '$rep_db', '$PHP_PGADMIN_USER', '$rep_descr', '$rep_source')";
		$rsInsertRep = @pg_exec($link_rep, $qrInsertRep) or pg_die(pg_errormessage(), $qrInsertRep, __FILE__, __LINE__);
		$message = "$strReport '$rep_name' $strHasBeenCreated";
		show_message($message);
	} elseif ($rep_action == "edit") {
		$qrUpdateRep = "UPDATE ppa_reports SET report_name ='$rep_name', db_name = '$rep_db', descr = '$rep_descr', report_sql = '$rep_source' WHERE report_id = $rep_id";
		$rsUpdateRep = @pg_exec($link_rep, $qrUpdateRep) or pg_die(pg_errormessage(), $qrUpdateRep, __FILE__, __LINE__);
		$message = "$strReport '$rep_name' $strHasBeenAltered";
		show_message($message);
	}
	
	$qrReports = "SELECT * FROM ppa_reports ORDER BY db_name, report_name";
	
	if (!empty($dbname)) {
		$qrReports .= " WHERE db_name = '$dbname'";
	}
	
	$rsReports = @pg_exec($link_rep, $qrReports) or pg_die(pg_errormessage(), $qrReports, __FILE__, __LINE__);
	
	$rnReports = @pg_numrows($rsReports);
	
	if ($rnReports > 0) {
		echo "
			<table border=\"$cfgBorder\">
				<tr bgcolor=\"lightgrey\">
					<th>$strReportName</th>
					<th>$strDatabase</th>
					<th>$strDate</th>
					<!--th>$strDescription</th-->
					<th colspan=\"4\">$strAction</th>
				</tr>
		";

		$query = "?db=$db&server=$server&goto=" . urlencode("reports.php");
	
		for ($intReports = 0; $intReports < $rnReports; $intReports++) {
			$bgcolor = $cfgBgcolorOne;
			$intReports % 2 ? 0 : $bgcolor = $cfgBgcolorTwo;
			
			$rep_row = @pg_fetch_array($rsReports, $intReports);
			
			$rep_query = "?server=$server&db=$rep_row[db_name]&goto=" . urlencode("reports.php");
			
			echo "
				<tr bgcolor=\"$bgcolor\">
					<td class=\"data\"><b>$rep_row[report_name]</b></td>
					<td class=\"data\">$rep_row[db_name]</td>
					<td class=\"data\">$rep_row[date_created]</td>
					<!--td class=\"data\">$rep_row[descr]</td-->
					<td class=\"data\">
						<a href=\"rep_properties.php$query&rep_id=$rep_row[report_id]\">$strProperties</a>
					</td>
					<td class=\"data\">
						<a href=\"rep_create.php$query&rep_id=$rep_row[report_id]\">$strChange</a>
					</td>
					<td class=\"data\">
						<a href=\"sql.php$query&sql_query=" . urlencode("DELETE FROM ppa_reports WHERE report_id = $rep_row[report_id]") . "&zero_rows=" . urlencode("$strReport $rep_row[report_name] $strHasBeenDropped") . "\">$strDrop</a>
					</td>
					<td class=\"data\">
						<a href=\"sql.php$rep_query&sql_query=" . urlencode($rep_row[report_sql]) . "\">$strRun</a>
					</td>
				</tr>
			";
		}
		echo "</table>";
	} else {
		echo "$strNo $strReports $strFound";
	}
	
	echo "<br><br><a href=\"rep_create.php?server=$server\">$strCreateNew $strReports</a>";
}
include("footer.inc.php");

?>
