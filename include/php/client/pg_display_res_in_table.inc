<html>
<?php
echo "<table border=1><tr>";
$resno = pg_numfields ($result);
for ($resi=0; $resi<$resno; $resi++) {
	$pgfldname = pg_fieldname($result, $resi);
	$pgfldname = ereg_replace("_"," ", $pgfldname);
	echo "<th>".ucwords($pgfldname)."</th>";
}
echo "</tr>";
$resno = pg_numrows ($result);
for ($resj=0; $resj<$resno; $resj++) {
	$arr = pg_fetch_row($result, $resj);
	echo "<tr>";
	for ($resi=0; $resi<count($arr); $resi++) {
		echo "<td>". $arr[$resi] . "</td>";
	}
	echo "</tr>";
}
echo "</table>";
?>
</html>
