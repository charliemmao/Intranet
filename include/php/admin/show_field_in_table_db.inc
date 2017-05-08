<html>
<?php
	echo "<table $border>";
	echo "<tr><th>No</th><th>Field</th><th>Type</th><th>Length</th><th>Flag</th></tr>";
	for ($ilist=0; $ilist<$nofields; $ilist++) {
		if ((int)($ilist/2)*2 == $ilist) {
			$sl = "tr1";
		} else {
			$sl = "tr2";
		}
		$ilistp1 = $ilist + 1;
		echo "<tr class=$sl><td>$ilistp1</td>";
		for ($jlist=0; $jlist<4; $jlist++) {
            echo "<td align=middle>".$fldproplist[$ilist][$jlist]."</td>";
       }
		echo "<tr>";
   	}
   	echo "</table>";
?>
</html>