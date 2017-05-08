<?php
/* $Id: db_dump.php,v 1.11 2001/02/04 03:51:55 kkemp102294 Exp $ */

$crlf="\n";
if (empty($asfile)) {
	include("header.inc.php");
	print "<div align=left><pre>\n";
} else {
	include("lib.inc.php");
	header("Content-disposition: filename=$db.sql");
	header("Content-type: application/octetstream");
	header("Pragma: no-cache");
	header("Expires: 0");

	// doing some DOS-CRLF magic...
	$client=getenv("HTTP_USER_AGENT");
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

print "$crlf/* -------------------------------------------------------- $crlf"; 
print "  $cfgProgName $cfgVersion DB Dump$crlf";
print "  http://www.greatbridge.org/project/phppgadmin/$crlf";
print "  $strHost: " . $cfgServer['host'];

if (!empty($cfgServer['port'])) {
	print ":" . $cfgServer['port'];
}
print "$crlf  $strDatabase: $db$crlf";
print "  " . date("Y-d-m H:m:i") . $crlf;
print "-------------------------------------------------------- */ $crlf";

$get_seq_sql = "
	SELECT relname 
	FROM pg_class 
	WHERE 
		NOT relname ~ 'pg_.*' 
		AND relkind ='S' 
	ORDER BY relname
	";

$seq = @pg_exec($link, pre_query($get_seq_sql));
if (!$num_seq = @pg_numrows($seq)) {
	print "/* $strNo $strSequences $strFound */";
} else {
	print "$crlf/* -------------------------------------------------------- $crlf";
	print "  $strSequences $crlf";
	print "-------------------------------------------------------- */ $crlf";

	while ($i_seq < $num_seq) {
		$sequence = @pg_result($seq, $i_seq, "relname");
		
		$sql_get_props = "SELECT * FROM $cfgQuotes$sequence$cfgQuotes";
		$seq_props = @pg_exec($link, pre_query($sql_get_props));
		if (@pg_numrows($seq_props)) {
			$row = @pg_fetch_array($seq_props, 0);
			if ($what != "data") {
				$row[last_value] = 1;
			}
			print "CREATE SEQUENCE $cfgQuotes$sequence$cfgQuotes start $row[last_value] increment $row[increment_by] maxvalue $row[max_value] minvalue $row[min_value] cache $row[cache_value]; $crlf";
		}
		if (($row[last_value] > 1) && ($what == "data")) {
			print "SELECT NEXTVAL('$sequence'); $crlf";
			unset($row[last_value]);
		}
		$i_seq++;
	}
}

$tables = @pg_exec($link, "SELECT tablename FROM pg_tables WHERE tablename !~ 'pg_.*' ORDER BY tablename");

$num_tables = @pg_numrows($tables);
if (!$num_tables) {
	echo $strNoTablesFound;
} else {
	
	for ($i = 0; $i < $num_tables; $i++) {
		$table = pg_result($tables, $i, "tablename");
	
		print "$crlf/* -------------------------------------------------------- $crlf";
		print "  $strTableStructure $cfgQuotes$table$cfgQuotes $crlf";
		print "-------------------------------------------------------- */";

		echo $crlf;
		if (!$asfile) {
			echo htmlentities(get_table_def($link, $table, $crlf));
		} else {
			echo get_table_def($link, $table, $crlf);
		}
		echo $crlf;
		
		if ($what == "data") {
		
			print "$crlf/* -------------------------------------------------------- $crlf";
			print "  $strDumpingData $cfgQuotes$table$cfgQuotes $crlf";
			print "-------------------------------------------------------- */ $crlf";
		
			get_table_content($link, $table, "my_handler");
		}
	}
}

// tablename !~ 'pg_.*'
$sql_get_views = "SELECT * FROM pg_views WHERE viewname !~ 'pg_.*'";

$views = @pg_exec($link, pre_query($sql_get_views));
if (!$num_views = @pg_numrows($views)) {
	print "$crlf/* $strNo $strViews $strFound */$crlf";
} else {
	print "$crlf/* -------------------------------------------------------- $crlf";
	print "  $strViews $crlf";
	print "-------------------------------------------------------- */ $crlf";

	for ($i_views = 0; $i_views < $num_views; $i_views++) {
		$view = pg_fetch_array($views, $i_views);
		print "CREATE VIEW $cfgQuotes$view[viewname]$cfgQuotes AS $view[definition] $crlf";
	}
}

// Output triggers

// Some definitions
$TRIGGER_TYPE_ROW			=	(1 << 0);
$TRIGGER_TYPE_BEFORE		=	(1 << 1);
$TRIGGER_TYPE_INSERT		=	(1 << 2);
$TRIGGER_TYPE_DELETE		=	(1 << 3);
$TRIGGER_TYPE_UPDATE		=	(1 << 4);

$sql_get_triggers = "
	SELECT 
		pt.*, pp.proname, pc.relname
	FROM 
		pg_trigger pt, pg_proc pp, pg_class pc
	WHERE 
		pp.oid=pt.tgfoid
		and pt.tgrelid=pc.oid
		and relname !~ '^pg_'
";

$triggers = @pg_exec($link, pre_query($sql_get_triggers));
if (!$num_triggers = @pg_numrows($triggers)) {
	print "$crlf/* $strNo $strTriggers $strFound */$crlf";
} else {
	print "$crlf/* -------------------------------------------------------- $crlf";
	print "  $strTriggers $crlf";
	print "-------------------------------------------------------- */ $crlf";

	for ($i_triggers = 0; $i_triggers < $num_triggers; $i_triggers++) {
		$trigger = pg_fetch_array($triggers, $i_triggers);
		// Constraint or not
		if ($trigger[tgisconstraint] == 't')
			print "CREATE CONSTRAINT TRIGGER";
		else
			print "CREATE TRIGGER";
		// Name
		print " $cfgQuotes$trigger[tgname]$cfgQuotes";

		// before/after
		if ($trigger[tgtype] & $TRIGGER_TYPE_BEFORE)
			print " BEFORE";
		else
			print " AFTER";

		// Insert
		$findx = 0;
		if ($trigger[tgtype] & $TRIGGER_TYPE_INSERT) {
			print " INSERT";
			$findx++;
		}

		// Delete
		if ($trigger[tgtype] & $TRIGGER_TYPE_DELETE) {
			if ($findx > 0)
				print " OR DELETE";
			else
				print " DELETE";
			$findx++;
		}
		
		// Update
		if ($trigger[tgtype] & $TRIGGER_TYPE_UPDATE) {
			if ($findx > 0)
				print " OR UPDATE";
			else
				print " UPDATE";
		}

		// On
		print " ON $cfgQuotes$trigger[relname]$cfgQuotes";

		// Contraints, deferrable
		if ($trigger[tgisconstraint] == 't') {
			if ($trigger[tgdeferrable] == 'f') print " NOT";
			print " DEFERRABLE INITIALLY ";

			if ($trigger[tginitdeferred] == 't')
				print "DEFERRED";
			else
				print "IMMEDIATE";
		}
		echo " FOR EACH ROW";
		echo " EXECUTE PROCEDURE $cfgQuotes$trigger[proname]$cfgQuotes ('";

		// Strip of trailing delimiter
		$tgargs = trim(substr($trigger[tgargs], 0, strlen($trigger[tgargs]) - 4));
		$params = explode('\000', $tgargs);

		for ($i = 0; $i < sizeof($params); $i++) {
			$params[$i] = str_replace("'", "\\'", $params[$i]);
		}
		$params = implode("', '", $params);
		echo htmlspecialchars($params), "');$crlf";
	}
}

// Output functions

// Max built-in oidi
//$sql_get_max = "SELECT oid FROM pg_database WHERE datname = 'template1'";  
//$maxes = pg_exec($link, $sql_get_max);
//$max = pg_result($maxes, 0, "oid");
//$max = $row[datlastsysoid];
//$max = 16384;

$max = $builtin_max;

// Skips system functions
$sql_get_funcs = "
	SELECT 
		proname, 
		l.lanname AS language, 
		pronargs AS num_args, 
		t.typname AS return_type,
		oidvectortypes(pc.proargtypes) AS arguments,
		prosrc AS source
	FROM 
		pg_proc pc, pg_user u, pg_language l, pg_type t
	WHERE 
		pc.oid > '$max'::oid 
		AND l.oid = pc.prolang
		AND t.oid = pc.prorettype
		AND pc.proowner = u.usesysid
	UNION
	SELECT 
		proname, 
		l.lanname AS language, 
		pronargs AS num_args, 
		'OPAQUE' AS return_type,
		oidvectortypes(pc.proargtypes) AS arguments,
		prosrc AS source
	FROM 
		pg_proc pc, pg_user u, pg_language l
	WHERE 
		pc.oid > '$max'::oid 
		AND l.oid = pc.prolang
		AND pc.prorettype = 0
		AND pc.proowner = u.usesysid
	";

print $crlf;

$funcs = pg_exec($link, pre_query($sql_get_funcs)) or pg_die(pg_errormessage(), $sql_get_funcs, __FILE__, __LINE__);

if (!$num_funcs = pg_numrows($funcs)) {
	print "/* $strNo $strFuncs $strFound */$crlf";
} else {
	print "$crlf/* -------------------------------------------------------- $crlf";
	print "  $strFuncs $crlf";
	print "-------------------------------------------------------- */ $crlf";

	for ($i_funcs = 0; $i_funcs < $num_funcs; $i_funcs++) {
		$func_info = @pg_fetch_array($funcs, $i_funcs);

		$strArgList = str_replace(" ", ",", trim($func_info[arguments])); 
		
		echo "CREATE FUNCTION $func_info[proname]($strArgList) RETURNS $func_info[return_type] AS '$func_info[source]' LANGUAGE '$func_info[language]'; $crlf";
	}
}


if(empty($asfile)) {
	print "</pre></div>\n";
	include ("footer.inc.php");
}
?>
