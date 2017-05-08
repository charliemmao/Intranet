<?php
/* $Id: tbl_dump.php,v 1.2 2000/12/01 08:37:16 dwilson Exp $ */
@set_time_limit(600);
$crlf="\n";

if (empty($asfile)) { 
	include("header.inc.php");
	print "<div align=left><pre>\n";
} else {
	include("lib.inc.php");
	$ext = "sql";
	if ($what == "csv")
		$ext = "csv";
	header("Content-disposition: filename=$table.$ext");
	header("Content-type: application/octetstream");
	header("Pragma: no-cache");
	header("Expires: 0");

	// doing some DOS-CRLF magic...
	$client = getenv("HTTP_USER_AGENT");
	if (ereg('[^(]*\((.*)\)[^)]*',$client,$regs)) {
		$os = $regs[1];

		// this looks better under WinX
		if (eregi("Win",$os)) $crlf="\r\n";
	}
}

function my_handler($sql_insert) {
	global $crlf, $asfile;
	if (empty($asfile)) {
		echo htmlspecialchars("$sql_insert;$crlf");
	} else {
		echo "$sql_insert;$crlf";
	}
}

function my_csvhandler($sql_insert) {
	global $crlf, $asfile;
	if (empty($asfile)) {
		echo htmlspecialchars("$sql_insert;$crlf");
	} else {
		echo "$sql_insert;$crlf";
	}
}

if ($what != "csv") {


print "$crlf/* -------------------------------------------------------- $crlf";
print "  $cfgProgName $cfgVersion DB Dump$crlf";
print "  http://www.greatbridge.org/project/phppgadmin/$crlf";
print "  $strHost: " . $cfgServer['host'];

if (!empty($cfgServer['port'])) {
        print ":" . $cfgServer['port'];
}
print "$crlf  $strDatabase: $db$crlf";
print "  $strTableStructure $cfgQuotes$table$cfgQuotes $crlf";
print "  " . date("Y-d-m H:m:i") . $crlf;
print "-------------------------------------------------------- */ $crlf $crlf";
	
	print get_table_def($link, $table, $crlf)."$crlf";
	
	if ($what == "data") {

		print "$crlf/* -------------------------------------------------------- $crlf";
		print "  $strDumpingData $cfgQuotes$table$cfgQuotes $crlf";
		print "-------------------------------------------------------- */ $crlf";

		get_table_content($link, $table, "my_handler");
	}
} else { // $what != "csv"
	get_table_csv($link, $table, $separator, "my_csvhandler");
}

if (empty($asfile)) {
	print "</pre></div>\n";
	include ("footer.inc.php");
}
?>
