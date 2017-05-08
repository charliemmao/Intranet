<html>
<?php
//echo "fldeditconst = $fldeditconst<br>";
$fldeditselect = explode("@", $fldeditconst);

$frm_str="usedb=$usedb&usetable=$usetable".
	"&editdelwhere=$editdelwhere".
	"&ascdec=$ascdec&wstr=$wstr";
	//echo "$frm_str<br>";
$frm_str = base64_encode($frm_str);
echo "<form method=post>";
echo "<input type=hidden name=\"frm_str\" value=\"$frm_str\">";
echo "<input type=\"hidden\" value=\"$fldeditconst\" name=\"fldeditconst\">";
echo "<input type=\"hidden\" value=\"$editfldno\" name=\"editfldno\">";

echo "<table border=$border>";
$row = mysql_fetch_object($result);
echo "<tr class=tr1><th>Field</th><th>Value</th></tr>";
for ($i=0; $i<$nofields; $i++) {
   ($i%2)?$sl = "tr1":$sl = "tr2";
	echo "<tr class=$sl>";
	echo "<th align=left>".formatfieldname($fldproplist[$i][0])."-".$fldproplist[$i][1]."</th>";
   if ($delrcd || $norcd > 1) { //<
    	$tmpvalue = $row->$fldproplist[$i][0];
    	if (!$tmpvalue) {
    		$tmpvalue = "---";
    	}
    	echo "<td>&nbsp;&nbsp;".$tmpvalue."</td></tr>";
    } elseif ($editrcd) {
    	$tmpvalue = $row->$fldproplist[$i][0]; //<
    	if ($i == $fldcolno || $fldeditselect[$i]) {
    		echo "<td>$tmpvalue</tr>";
    	} else {
    		echo "<td><input name=\"".$fldproplist[$i][0]."\" value=\"$tmpvalue\" size=80></td></tr>";
			/*
			TODO: field check based on field type
				   input/textarea box based on field max-length
            $fldproplistas[$i][0] = name;
            $fldproplist[$i][0] = name;
            $fldproplist[$i][1] = type;
            $fldproplist[$i][2] = max_length;
            $fldproplist[$i][3] = primary_key;
            $fldproplist[$i][4] = unique_key;
            $fldproplist[$i][5] = multiple_key;
            $fldproplist[$i][6] = not_null;
            $fldproplist[$i][7] = zerofill;
            $fldproplist[$i][8] = numeric;
            $fldproplist[$i][9] = unsigned;
            $fldproplist[$i][10] = blob;
            $fldproplist[$i][11] = table;
			//*/
    	}
    }
}
if ($norcd == 1) {
	echo "<tr><td colspan=2>&nbsp;</td></tr>";
	($i%2)?$sl = "tr1":$sl = "tr2";
	echo "<tr><th colspan=2><button type=submit ";
	if ($delrcd) {
		echo "name=delrcdconfirm><b>Delete This Record</b>";
	} elseif ($editrcd) {
		echo "name=editrcdconfirm><b>Update This Record</b>";
	}
	echo "</button></th></tr>";
}
echo "</table>";
?>
</html>