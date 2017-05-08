<html>
<?php
	$pdflist = "/usr/local/apache/htdocs/patent/patent_list.csv";
	echo "Open $pdflist.<br>";
	flush();
	$fpdate = fopen($pdflist, "r");
	$rcdin=0;
	if ($fpdate) {
		$i=1;
		echo "<table border=1>";
		echo "<tr><th>No</th><th>String</th><th>Patent No</th><th>PDF File</th><th>Result</th></tr>";
		flush();
		while ($buffer = fgets($fpdate, 5000)) {
			$tmp=explode(",",trim($buffer));
			$patent_no = $tmp[1];
			$elec_copy_add = $tmp[0];
			$sql = "UPDATE library.for_patent SET elec_copy_add='$elec_copy_add' "
				."WHERE patent_no='$patent_no';";
			$sql = "SELECT patent_id from library.for_patent WHERE patent_no='$patent_no';";
			//echo $sql."<br>";
			$result = mysql_query($sql);
			if ($result) {
				list($patent_id) = mysql_fetch_array($result);
				if ($patent_id) {
					$result = $patent_id;
					$rcdin++;
				} else {
					$result = "<font color=#ff0000>No</font>";
				}
			} else {
				$result = "<font color=#ff0000>No</font>";
			}
			echo "<tr><td>$i</td><td>$buffer</td><td>$patent_no</td>
				<td>$elec_copy_add</td><td>$result</td></tr>";
			$i++;
			flush();
			//sleep(1);
		}
		echo "</table>";
	} else {
		echo "Probelm to open $pdflist.<br>";
	}
	echo "No patent entered: $rcdin.<br> Completed.<br>";
?>
</html>