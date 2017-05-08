<html>

<head>
<title>MySQL DB Administration</title>
<LINK REL="StyleSheet" HREF="../style/style_admin.css" TYPE="text/css">
</head>
<body background="rlaemb.JPG" leftmargin="10">

<?php
## control page access
include("admin_access.inc");
include('rla_functions.inc');
include("find_user_db.inc");
include('str_decode_parse.inc');
include("find_admin_default.inc");

$showlen = 10;
$norow = 20;
$h = "h4";
$border = "0 cellspacing=\"0\" cellpadding=\"0\"";
if ($usedb) {
	include("find_field_in_table_db.inc"); //$fldproplist[$][$]
	//include("show_field_in_table_db.inc");
	include("find_rcd_no.inc");
} else {
	echo "<$h align=center><a id=top>MySQL Query Result";
	exit;
}

if ($frmleft) {
	include("find_field_list_order.inc");	//$showfld[] $fldorderby[]
}
//echo "norcd:  $norcd<br>MaxRows: $MaxRows<br>offset: $offset<br>curoffset: $curoffset<br><br>";

if (!$MaxRows) {
	$MaxRows = $norow;
}
if (!$offset ) {
	$offset = 0;
}

if ($part || $full) {
	$offset = $ppoffset;
} elseif ($prev) {
	$offset = $poffset;
} elseif ($next) {
	$offset = $noffset;
} elseif ($end) {
	$offset = $norcd - $MaxRows;
	$coffset= chkmax($coffset,$MaxRows,$norcd);
} elseif ($begin) {
	$offset = 0;
} elseif ($show)  {
	$offset = $coffset;
} elseif ($newsql)  {
	$fldselect = $fields;
	$orderby = $sortby;
}
//echo "norcd:  $norcd<br>MaxRows: $MaxRows<br>offset: $offset<br>";

if (!$fldselect) {
	$fldselect = "*";
}
$sql = "SELECT $fldselect FROM $usedb.$usetable ";
if ($where) {
	$where = stripslashes($where);
	$sql .= "WHERE $where ";
}
if ($orderby) {
	$sql .= "ORDER BY $orderby $ascdec ";
}
$sql .= "LIMIT $offset, $MaxRows";
//echo "<br>$sql<br><br>";
if (!$fldselect) {
	$fldselect = $fldproplist[0][0] ;
	for ($i=1; $i<$nofields; $i++) {
		$fldselect .= ", ".$fldproplist[$i][0];
	}
}
	echo "<$h></a><a id=top>MySQL Query Result from <font color=#0000ff>
		$usedb.$usetable</font> ($norcd)</a>";
	$querystr="usedb=$usedb&usetable=$usetable&ascdec=$ascdec&wstr=$wstr".
		"&fldeditconst=$fldeditconst&editfldno=$editfldno";
	if ($newsql) {
		$querystr .= "&fldselect=$fields&orderby=$sortby";
		$userstr = base64_encode($querystr);
	} else {
		$querystr .= "&fldselect=$fldselect&orderby=$orderby";
		$userstr = base64_encode($querystr);
	}
	echo "<a href=\"".$PHP_SELF."?$userstr\">[Refresh]</a>&nbsp;[<a href=#end>To End</a>]";
	echo "</$h>";
	
if ($delrcdconfirm || $editrcdconfirm) {
	if ($editdelwhere) {
		$editdelwhere = StripSlashes($editdelwhere);
	}
	echo "<hr>";
	if ($delrcdconfirm ) {
		$sql = "DELETE FROM $usedb.$usetable WHERE $editdelwhere";
		$result = mysql_query($sql);
		include("err_msg.inc");
		echo "<$h>Record has been deleted from $usedb.$usetable.</$h>";
		echo "$sql<br><br>";	
	} elseif ($editrcdconfirm) {
		$sql = "";
		for ($i=0; $i<$fieldsno; $i++) {
			if ($i != $fldcolno) {
				$fld = $fldproplist[$i][0];
				$val = $$fld;
				if ($val) {
					if ($sql) {
						$sql .= ", ".$fld."='$val'";
					} else {
						$sql = $fld."='$val'";
					}
				}
			}
		}
		$sql = "UPDATE $usedb.$usetable SET $sql WHERE $editdelwhere";
		$result = mysql_query($sql);
		include("err_msg.inc");
		echo "$sql<br><br>";	
		echo "<$h>Record has been updated in $usedb.$usetable.</$h>";
	}
	//lastnavigate();
	exit;
}

if ($editrcd || $delrcd) {
	$editdelwhere = base64_decode($editdelwhere);
	if ($editrcd) {
		echo "<b>Edit the record in $usedb.$usetable.</b><br><br>";
	}
	if ($delrcd) {
		echo "<b>Delete the record from $usedb.$usetable.</b><br><br>";
	}
	$sql = "SELECT * FROM $usedb.$usetable WHERE $editdelwhere";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$norcd = mysql_num_rows($result);
	echo "$sql<br><br>";
	if ($norcd >1) {
		echo "<b>$norcd</b> records found. Only the first record is shown below. ".
			"Please select right fields from left frame, try again.<br>";
	}
	
	include("show_record_for_edit_del.inc");
	lastnavigate();
	exit;
}

	include("rcd_nav.inc");
	include("query_mysql_db.inc");
	include("rcd_nav.inc");
	navigate();
	
	echo "<form method=post>";
	echo "<input type=\"hidden\" value=\"$usedb\" name=\"usedb\">";
	echo "<input type=\"hidden\" value=\"$usetable\" name=\"usetable\">";
	echo "<input type=\"hidden\" value=\"$fldeditconst\" name=\"fldeditconst\">";
	echo "<input type=\"hidden\" value=\"$editfldno\" name=\"editfldno\">";

	echo "<$h>Customise Query Statement</$h>";
	echo "<b>Current</b><br>";
	$sql = ereg_replace("FROM", "<br>&nbsp;&nbsp;FROM", $sql);
	$sql = ereg_replace("ORDER BY", "<br>&nbsp;&nbsp;ORDER BY", $sql);
	$sql = ereg_replace("LIMIT", "<br>&nbsp;&nbsp;LIMIT", $sql);
	echo "<br>$sql<br><br>";
	
	echo "<b>Edit</b><br>";
	echo "<table border=$border>";
	$fldselectall = $fldproplist[0][0] ;
	$wstr = $fldproplist[0][0]."=''";
	for ($i=1; $i<$fieldsno; $i++) {
		$fldselectall .= ", ".$fldproplist[$i][0];
		$wstr .= " and ".$fldproplist[$i][0]."=''";
	}
	$nr = (int)($nofields/5)+1;
	$sortby = $fldselect;	//ereg_replace(", ", " and ", $fldselect);
	echo "<tr><th align=left class=nbb>SELECT</th>";
	echo "<td><textarea cols=80 rows=$nr name=fields>$fldselect</textarea></td></tr>";
	
	echo "<tr><th align=left class=nbb>WHERE</th>";
	echo "<td><textarea cols=80 rows=$nr name=where>$where</textarea></td></tr>";

	echo "<tr><th align=left class=nbb>ORDER BY</th>";
	echo "<td><textarea cols=80 rows=$nr name=sortby>$orderby</textarea></td></tr>";

	echo "<tr><th align=left class=nbb>SORT</th><th align=left class=nbb>
		<select name=ascdec><option value=\"0\">Ascending";
		if ($ascdec) {
			echo "<option value=\"DESC\" selected>Decending";
		} else {
			echo "<option value=\"DESC\">Decending";
		}
	echo "</option></select>";
	echo "SHOW";
	echo "<input type=\"text\" value=\"$MaxRows\" name=\"MaxRows\" size=3>FROM";
	echo "<input type=\"text\" value=\"$offset\" name=\"offset\" size=3>";
	echo "</th></tr>";
	echo "<tr><td colspan=2>&nbsp;</td></tr>";
	echo "<tr><th align=left class=nbb>Fields</th>";
	echo "<td><b>SELECT</b>: $fldselectall<br><b>WHERE</b>: $wstr<br><b>SORT&nbsp;&nbsp;</b>: $sortby</td></tr>";
	echo "<tr><td colspan=2>&nbsp;</td></tr>";
	echo "<tr><th colspan=2><button type=submit name=newsql><b>SUBMIT</b></button></th></tr>";
	echo "</table>";
	echo "</form>";

lastnavigate();

############################################
############################################
echo "<a id=end>&nbsp;</a>";
function navigate(){
	echo "<center>[<a href=#top>Back to top</a>]&nbsp;[<a href=#end>To End</a>]</center><hr>";
}
function lastnavigate(){
	echo "<center>[<a href=#top>Back to top</a>]</center><hr>";
}

function chkvalid($from) {
	if ($from < 0 ) {
		return 0;
	} else {
		return $from;
	}
}

function chkmax($from,$MaxRows,$norcd) {
	if ($norcd - $MaxRows < $from) {
		$from = $norcd - $MaxRows;
		if ($from < 0 ) {
			return 0;
		} else {
			return $from;
		}
	} else {
		return $from;
	}
}

?>
</body>
