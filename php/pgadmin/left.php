<?php
/* $Id: left.php,v 1.11 2001/03/16 18:49:25 dwilson Exp $ */

include("lib.inc.php");
?>

<html>
<head>
<title><?php echo $cfgProgName; ?></title>

 <script LANGUAGE="JavaScript">
   <!--
  // These scripts were originally found on cooltype.com. 
  // Modified 01/01/1999 by Tobias Ratschiller for linuxapps.com

  document.onmouseover = doDocumentOnMouseOver ;
  document.onmouseout = doDocumentOnMouseOut ;

  function doDocumentOnMouseOver() {
    var eSrc = window.event.srcElement ;
    if (eSrc.className == "item") {
      window.event.srcElement.className = "highlight";
    }
  }

  function doDocumentOnMouseOut() {
    var eSrc = window.event.srcElement ;
    if (eSrc.className == "highlight") {
      window.event.srcElement.className = "item";
    }
  }


var bV=parseInt(navigator.appVersion);
NS4=(document.layers) ? true : false;
IE4=((document.all)&&(bV>=4))?true:false;
ver4 = (NS4 || IE4) ? true : false;

function expandIt(){return}
function expandAll(){return}
//-->
</script>

<script LANGUAGE="JavaScript1.2">
<!--
isExpanded = false;

function getIndex(el) {
	ind = null;
	for (i=0; i<document.layers.length; i++) {
		whichEl = document.layers[i];
		if (whichEl.id == el) {
			ind = i;
			break;
		}
	}
	return ind;
}

function arrange() {
	nextY = document.layers[firstInd].pageY + document.layers[firstInd].document.height;
	for (i=firstInd+1; i<document.layers.length; i++) {
		whichEl = document.layers[i];
		if (whichEl.visibility != "hide") {
			whichEl.pageY = nextY;
			nextY += whichEl.document.height;
		}
	}
}

function initIt(){
	if (NS4) {
		for (i=0; i<document.layers.length; i++) {
			whichEl = document.layers[i];
			if (whichEl.id.indexOf("Child") != -1) whichEl.visibility = "hide";
		}
		arrange();
	}
	else {
		tempColl = document.all.tags("DIV");
		for (i=0; i<tempColl.length; i++) {
			if (tempColl(i).className == "child") tempColl(i).style.display = "none";
		}
	}
}

function expandIt(el) {
	if (!ver4) return;
	if (IE4) {expandIE(el)} else {expandNS(el)}
}

function expandIE(el) { 
	whichEl = eval(el + "Child");

        // Modified Tobias Ratschiller 01-01-99:
        // event.srcElement obviously only works when clicking directly
        // on the image. Changed that to use the images's ID instead (so
        // you've to provide a valid ID!).

	//whichIm = event.srcElement;
        whichIm = eval(el+"Img");

	if (whichEl.style.display == "none") {
		whichEl.style.display = "block";
		whichIm.src = "images/minus.gif";		
	}
	else {
		whichEl.style.display = "none";
		whichIm.src = "images/plus.gif";
	}
    window.event.cancelBubble = true ;
}

function expandNS(el) {
	whichEl = eval("document." + el + "Child");
	whichIm = eval("document." + el + "Parent.document.images['imEx']");
	if (whichEl.visibility == "hide") {
		whichEl.visibility = "show";
		whichIm.src = "images/minus.gif";
	}
	else {
		whichEl.visibility = "hide";
		whichIm.src = "images/plus.gif";
	}
	arrange();
}

function showAll() {
	for (i=firstInd; i<document.layers.length; i++) {
		whichEl = document.layers[i];
		whichEl.visibility = "show";
	}
}

function expandAll(isBot) {
	newSrc = (isExpanded) ? "images/plus.gif" : "images/minus.gif";

	if (NS4) {
        // TR-02-01-99: Don't need that
        // document.images["imEx"].src = newSrc;
		for (i=firstInd; i<document.layers.length; i++) {
			whichEl = document.layers[i];
			if (whichEl.id.indexOf("Parent") != -1) {
				whichEl.document.images["imEx"].src = newSrc;
			}
			if (whichEl.id.indexOf("Child") != -1) {
				whichEl.visibility = (isExpanded) ? "hide" : "show";
			}
		}

		arrange();
		if (isBot && isExpanded) scrollTo(0,document.layers[firstInd].pageY);
	}
	else {
		divColl = document.all.tags("DIV");
		for (i=0; i<divColl.length; i++) {
			if (divColl(i).className == "child") {
				divColl(i).style.display = (isExpanded) ? "none" : "block";
			}
		}
		imColl = document.images.item("imEx");
		for (i=0; i<imColl.length; i++) {
			imColl(i).src = newSrc;
		}
	}

	isExpanded = !isExpanded;
}

with (document) {
	write("<STYLE TYPE='text/css'>");
	if (NS4)
        {
        write(".parent {font-family: Verdana, Arial, Helvetica, sans-serif; color: #000000; text-decoration:none; position:absolute; visibility:hidden; color: black;}");
        write(".child {font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 8pt;color: #000000; position:absolute; visibility:hidden}");
        write(".item { color: darkblue; text-decoration:none; font-size: 8pt;}");
        write(".regular {font-family: Arial,Helvetica,sans-serif; position:absolute; visibility:hidden}");
		write("A:link.nav {  font-family: Verdana, Arial, Helvetica, sans-serif; color: #000000}");
		write("A:visited.nav {  font-family: Verdana, Arial, Helvetica, sans-serif; color: #000000}");
		write("A:hover.nav {  font-family: Verdana, Arial, Helvetica, sans-serif; color: red;}");
		write(".nav {  font-family: Verdana, Arial, Helvetica, sans-serif; color: #000000}");
        write("DIV { color:black; }")
        }
	else
        {
        write(".child {font-family: Verdana, Arial, Helvetica, sans-serif; color: #000000; text-decoration:none; width:auto; display:none}");
        write(".parent {font-family: Verdana, Arial, Helvetica, sans-serif; color: #000000; text-decoration:none; position:relative;}");
        write(".item { color: darkblue; text-decoration:none; font-size: 8pt;}");
        write(".highlight { color: red; font-size: 8pt;}");
        write(".heada { font: 12px/13px; Times}");
		write("A:link.nav {  font-family: Verdana, Arial, Helvetica, sans-serif; color: #000000}");
		write("A:visited.nav {  font-family: Verdana, Arial, Helvetica, sans-serif; color: #000000}");
		write("A:hover.nav {  font-family: Verdana, Arial, Helvetica, sans-serif; color: red;}");
		write(".nav {  font-family: Verdana, Arial, Helvetica, sans-serif; color: #000000}");
        write("DIV { color:black; }")
	    }
	write("</STYLE>");

}

onload = initIt;

//-->
</script>
<base target="main">
<style type="text/css">
//<!--
body {  font-family: Arial, Helvetica, sans-serif; font-size: 10pt}
//-->
</style>
</head>

<body bgcolor="#D0DCE0">
<DIV ID="el1Parent" CLASS="parent">
      
      <A class="item" HREF="main.php?server=<?php echo $server; ?>"><FONT color="black" class="heada"><?php echo $strHome;?></FONT></A><br>
	  <?php if ($server) { ?>
      <A class="item" HREF="all_db.php?server=<?php echo $server; ?>"><FONT color="black" class="heada"><?php echo $strAllDatabases;?></FONT></A>
	  <?php } ?>
	  
</DIV>

<?php
// Don't display database info if $server==0 (no server selected)
if ($server > 0) {
?>
      <DIV ID="el1Child" CLASS="child">
      </DIV>
<?php

	if (empty($cfgServer["only_db"])) {
		// list all dbs
		// If the user isn't super then filter what they can see
		if (!$bSuperUser && $cfgUserDatabases) {
			$sql_get_dbs = "SELECT datname FROM pg_database WHERE datname NOT LIKE 'template_' AND pg_get_userbyid(datdba) = CURRENT_USER ORDER BY datname";
		} else {
			$sql_get_dbs = "SELECT datname FROM pg_database WHERE datname NOT LIKE 'template_' ORDER BY datname";
		}
		$dbs = pg_exec($link, pre_query($sql_get_dbs)) or pg_die(pg_errormessage(), $sql_get_dbs, __FILE__, __LINE__);
		$num_dbs = pg_numrows($dbs);
	
		for ($i = 0; $i < $num_dbs; $i++) {
			$db = pg_result($dbs, $i, "datname");
			$j = $i+2;

			$conn_str = "user=$cfgServer[stduser] password=$cfgServer[stdpass] ";
			if (!$cfgServer[local]) {
				$conn_str .= "host=$cfgServer[host] ";
			}
			$conn_str .= "port=$cfgServer[port] dbname='$db'";

			if ($dbh_tbl = @pg_connect($conn_str)) { // or pg_die(pg_errormessage(), $conn_str);
			
?>
			<div ID="el<?php echo $j;?>Parent" CLASS="parent">
				<a class="item" HREF="db_details.php?server=<?php echo $server; ?>&db=<?php echo $db; ?>" onClick="expandIt('el<?php echo trim($j);?>'); return false;"><img NAME="imEx" SRC="images/plus.gif" BORDER="0" ALT="+" width="9" height="9" ID="el<?php echo trim($j); ?>Img"></a>&nbsp;<a class="item" HREF="db_details.php?server=<?php echo $server; ?>&db=<?php echo $db; ?>"><font color="black" class="heada"><?php echo trim($db);?></font></a>
			</div>
			<div ID="el<?php echo $j; ?>Child" CLASS="child">
<?php
				if (!isInArray($db, $cfgNoTables)) {

					$sql_get_tbls = "SELECT tablename FROM pg_tables WHERE tablename !~* 'pg_*' ORDER BY tablename";
		
					$tbl_res = pg_exec($dbh_tbl, pre_query($sql_get_tbls));
					$num_tbl = pg_numrows($tbl_res);

					for ($j = 0; $j < $num_tbl; $j++) {
						$table = pg_result($tbl_res, $j, "tablename");
?>
<nobr>&nbsp;&nbsp;<a target="main" href="sql.php?sql_query=<?php echo urlencode("SELECT * FROM $cfgQuotes$table$cfgQuotes");?>&server=<?php echo $server;?>&db=<?php echo urlencode($db);?>&table=<?php echo urlencode($table);?>&goto=tbl_properties.php"><img src="images/browse.gif" border="0" alt="<?php echo "$strBrowse $strTable: $table"; ?>"></a>&nbsp;<a class="item" target="main" HREF="tbl_properties.php?server=<?php echo $server;?>&db=<?php echo $db;?>&table=<?php echo urlencode($table);?>"><?php echo $table;?></a></nobr><br>
<?php
					}
					pg_freeresult($tbl_res);
					pg_close($dbh_tbl);
				} else {
					echo "&nbsp;&nbsp;<span class=\"item\">$strNotDisplayed</span>";
				}
				echo "</div>\n";
			}
		}
	} else {
		// only_db is set
		$db = $cfgServer[only_db];
	?>

		<div ID="el100Parent" CLASS="parent">
			<a class="item" HREF="db_details.php?server=<?php echo $server; ?>&db=<?php echo $db; ?>" onClick="expandIt('el100'); return false;"><img NAME="imEx" SRC="images/plus.gif" BORDER="0" ALT="+" width="9" height="9" ID="el100Img"></a>&nbsp;<a class="item" HREF="db_details.php?server=<?php echo $server; ?>&db=<?php echo $db; ?>"><font color="black" class="heada"><?php echo trim($db);?></font></a>
		</div>
		<div ID="el100Child" CLASS="child">
<?php
		$conn_str = "user=$cfgServer[stduser] password=$cfgServer[stdpass] ";
		if (!$cfgServer[local]) {
			$conn_str .= "host=$cfgServer[host] ";
		}
		$conn_str .= "port=$cfgServer[port] dbname='$db'";

		$dbh_tbl = pg_connect($conn_str) or pg_die(pg_errormessage(), $conn_str, __FILE__, __LINE__);
		$sql_get_tbls = "SELECT tablename FROM pg_tables WHERE tablename !~* 'pg_*' ORDER BY tablename";

		$tbl_res = pg_exec($dbh_tbl, pre_query($sql_get_tbls));
		$num_tbl = pg_numrows($tbl_res);

		for ($j = 0; $j < $num_tbl; $j++) {
			$table = pg_result($tbl_res, $j, "tablename");
?>
<nobr>&nbsp;&nbsp;<a target="main" href="sql.php?sql_query=<?php echo urlencode("SELECT * FROM $cfgQuotes$table$cfgQuotes");?>&server=<?php echo $server;?>&db=<?php echo urlencode($db);?>&table=<?php echo urlencode($table);?>&goto=tbl_properties.php"><img src="images/browse.gif" border="0" alt="<?php echo "$strBrowse $strTable: $table"; ?>"></a>&nbsp;<a class="item" target="main" HREF="tbl_properties.php?server=<?php echo $server;?>&db=<?php echo $db;?>&table=<?php echo urlencode($table);?>"><?php echo $table;?></a></nobr><br>
<?php
		}
		pg_freeresult($tbl_res);
		pg_close($dbh_tbl);
		echo "</div>\n";
	}
}
?>
<?php
	if ($bSuperUser && $cfgSysTables) {
?>
		<div ID="el200Parent" CLASS="parent">
			<p><p>
			<a class="item" HREF="javascript:void(0)" onClick="expandIt('el200'); return false;"><img NAME="imEx" SRC="images/plus.gif" BORDER="0" ALT="+" width="9" height="9" ID="el200Img"></a>&nbsp;<a class="item"><font color="black" class="heada"><?php echo $strSystemTables; ?></font></a>
		</div>
		<div ID="el200Child" CLASS="child">
<?php
			$db = $cfgDefaultDB;

			$conn_str = "user=$cfgServer[stduser] password=$cfgServer[stdpass] ";
			if (!$cfgServer[local]) {
				$conn_str .= "host=$cfgServer[host] ";
			}
			$conn_str .= "port=$cfgServer[port] dbname=$db";

			$dbh_tbl = pg_connect($conn_str) or pg_die(pg_errormessage(), $conn_str, __FILE__, __LINE__);
			$sql_get_tbls = "select relname from pg_class where relname ~ 'pg_.*' and relkind = 'r' or relkind = 'v' order by relname";
			$tbl_res = pg_exec($dbh_tbl, pre_query($sql_get_tbls));
	
			$num_tables = @pg_numrows($tbl_res);
			for ($j = 0; $j < $num_tables; $j++) {
				$table = pg_result($tbl_res, $j, "relname");
				
				$query_str = "sql_query=" . urlencode("SELECT * FROM $cfgQuotes$table$cfgQuotes") . "&server=$server&db=$db&table=" . urlencode($table);
	?>
&nbsp;&nbsp;<a target="main" href="sql.php?<?php echo $query_str; ?>&goto=tbl_properties.php"><img src="images/browse.gif" border="0" alt="<?php echo "$strBrowse $strTable: $table"; ?>"></a>&nbsp;<a class="item" target="main" HREF="tbl_properties.php?server=<?php echo $server;?>&db=<?php echo $db;?>&table=<?php echo urlencode($table);?>"><?php echo $table;?></a><br>
<?php
			}
	echo "</div>\n";
	}	

	if ($bSuperUser && $cfgUserAdmin) {
		echo "
			<div ID=\"el300Parent\" CLASS=\"parent\">
				<p><p>
				<a class=\"item\" href=\"user_admin.php?server=$server\" target=main>$strUserAdmin</a><br>
				<a class=\"item\" href=\"grp_admin.php?server=$server\" target=main>$strGroupAdmin</a>
			</div>
		";
	}
	if ($cfgReports && $server) {
		echo "
			<div ID=\"el300Parent\" CLASS=\"parent\">
				<a class=\"item\" href=\"reports.php?server=$server\" target=main>$strReports</a>
			</div>
		";
	}
?>
<script LANGUAGE="JavaScript1.2">
<!--
if (NS4) {
	var firstInd;
	firstEl = "el1Parent";
	firstInd = getIndex(firstEl);
	showAll();
	arrange();
}
//-->
</script>
</body>
</html>
