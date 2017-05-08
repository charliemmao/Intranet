<html>
<?php
	//echo "fldeditconst = $fldeditconst<br>";
	$fldeditselect = explode("@", $fldeditconst);

	include("connet_root_once.inc");
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	$fldas = 1;
	include("mysql_query_fld_list.inc"); //obtain all fields properties
	//$fldproplistas[$i][0] = field name;
	echo "<table border=$border>";
	echo "<tr><th>No</th>";
		$tcols = $nofields+1;
		//if ($editdeletefldstr) {
			$tcols = $nofields+3;
			echo "<th>[EDIT]</th><th>[DELETE]</th>";
			$ahref = "<a href=\"$PHP_SELF?"; //\"
			$querystr="usedb=$usedb&usetable=$usetable&fldname=$editdeletefldstr";
		//}
    	for ($i=0; $i<$nofields; $i++) {
    	($i%2)?$sl = "td1":$sl = "td2";
   		$tmp = ereg_replace("_", " ", $fldproplistas[$i][0]);
    		$tmp = strtoupper($tmp);
    		echo "<th class=$sl>[$tmp]</th>";
    	}
	echo "</tr>";

	$i = 0;
	while($row = mysql_fetch_object($result)) {
    	($i%2)?$sl = "tr1":$sl = "tr2";
		echo "<tr class=$sl>";
		$j = $i+1;
		echo "<td>$j</td>";
    	for ($j=0; $j<$nofields; $j++) {
    		$fldvalue[$j] = $row->$fldproplistas[$j][0];
    		$align = "";
    		$tmpvalue = $fldvalue[$j];
    		if (!$tmpvalue) {
    			$tmpvalue = "---";
    			$align = "align=middle";
    		}
    		
    		if ($part) {
    			$tmpvalue = substr($tmpvalue, 0, $showlen);
    		}
    		$valshow[$j] = "<td $align>&nbsp;&nbsp;".$tmpvalue."</td>";
    	}
    	$editdelwhere = "";
		if ($nokey == 1) {
			$fldval = $fldvalue[$fldcolno];
			$editdelwhere = "$editdeletefldstr='$fldval'";
		}
		if ($editfldno && !$editdelwhere) {
			$editdelwhere = "";
    		for ($j=0; $j<$nofields; $j++) {
    			if ($fldeditselect[$j]) {
    				if ($editdelwhere) {
    					$editdelwhere .= " and ".$fldproplist[$j][0]."='".$fldvalue[$j]."'";
    				} else {
    					$editdelwhere = $fldproplist[$j][0]."='".$fldvalue[$j]."'";
    				}
    			}
    		}
		}
		if ($nokey || $editfldno) {
			$editdelwhere = base64_encode($editdelwhere);
			$tmp = $querystr."&fldeditconst=$fldeditconst&editfldno=$editfldno&editdelwhere=$editdelwhere";
			$qrystr = "&editrcd=y&".$tmp;
			$edit = "$ahref$qrystr\">"; 
			$qrystr = "&delrcd=y&".$tmp;
			$delete = "$ahref$qrystr\">"; 
			echo "<td>$edit [Edit]</a></td><td>$delete [Delete]</a></td>";
		} else {
			echo "<td align=middle>NA</td><td align=middle>NA</td>";
		}
    	for ($j=0; $j<$nofields; $j++) {
    		 echo $valshow[$j];
    	}
   		echo "</tr>";
   		$i++;
	}
	echo "</table>";
	echo "<p>";
?>
</html>
