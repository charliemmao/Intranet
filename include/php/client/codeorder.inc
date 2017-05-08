<html>
<?php
//echo "$fromdate to $todate.<br>";
include('codearray.inc');
include('ts_codeprefix_list.inc');	

##	reorder prefix and project code
/*
echo "<table border=1>";
echo "<tr><th>No</th><th>Prefix</th><th>Lable</th></tr>";
*/
$codeplusgroup = 0;
for ($j=0; $j<$no_prefix; $j++) {
	if ($j>0) {
		$codegrprange[0][$j-1] = $first-1; //group lable position in array $ordered_code
		$codegrprange[1][$j-1] = $first;	 //first code position in this group
		$codegrprange[2][$j-1] = $last-1;	 //last code position in this group
	}
	$ordered_code[0][$codeplusgroup] = $codeprefix_list[$j][1];	//	prefix
	$ordered_code[3][$codeplusgroup] = $codeprefix_list[$j][2];	//	lable
	$fromcodetono[$codeprefix_list[$j][1]] = $codeplusgroup;
	/*
	echo "<tr><td>$j</td><td>".$ordered_code[0][$codeplusgroup]."</td><td>".
		$ordered_code[4][$codeplusgroup]."</td><td></tr>";
	*/
	$codeplusgroup++;
	$first = $codeplusgroup;
	
	$sub_h = $codeprefix_list[$j][1];
	$sub_h_len = $codeprefix_list[$j][0];
		
	for ($i=0; $i<$codeno; $i++) {
		$code = $codelist[0][$i];
		$code_len = strlen($code);			
		if ($code_len >= $sub_h_len) {
			if (substr($code, 0, $sub_h_len) == $sub_h) {
				$fromcodetono[$code] = $codeplusgroup;
					// this number is used to quickly put query result into minutes
				$ordered_code[0][$codeplusgroup] = $codelist[0][$i];	//brief_code
				$ordered_code[1][$codeplusgroup] = $codelist[1][$i];	//special
				$ordered_code[2][$codeplusgroup] = $codelist[2][$i];	//div15
				$ordered_code[3][$codeplusgroup] = $codelist[3][$i];	//description
				$codeplusgroup++;
				$last = $codeplusgroup;
			}
		}
	}
}
	$codegrprange[0][$no_prefix-1] = $first-1; //group lable position in array $ordered_code
	$codegrprange[1][$no_prefix-1] = $first;	 //first code position in this group
	$codegrprange[2][$no_prefix-1] = $last-1;	 //last code position in this group
	//echo "</table><p>";
	
/*
	echo "<b>Staff List ($staffno)</b><br>";
	echo "<table border=1><tr><th>No</th><th>ename</th><th>from ename</th></tr>";
	for ($i=0; $i<$staffno; $i++) {
		$ename	=	$stafflist[0][$i];
		$tmp = $fromstafftono[$ename];
		echo "<tr><td>$i</td><td>$ename</td><td>$tmp</td></tr>";
	}
	echo "</table><p>";

	echo "<br><b>Ordered Code Profix and Code ($codeplusgroup)</b><br>";
	echo "<table border=1><tr><th>No</th><th>Code</th>";
	echo "<th>Prefix?</th><th>Special</th><th>Div 15</th><th>Description</th></tr>";
	for ($i=0; $i<$codeplusgroup; $i++) {
		$j =	$i + 1;
		echo "<tr><td>$i</td>";
		for ($k=0; $k<5; $k++) {
			$tmp = $ordered_code[$k][$i];
			if ($tmp == "0" || $tmp == "") {
				$tmp = "---";
			} else {
				$tmp = underscores($tmp);
			}
			echo "<td>".$tmp."</td>";
		}
		echo "</tr>";
	}
	echo "</table><p>";

	echo "<b>Project Code Prefix($no_prefix)</b><br>";
	echo "<table border=1><tr><th>No</th><th>Code</th><th>Lable</th><th>Length</th></tr>";
	for ($i=0; $i<$no_prefix; $i++) {
		$j =	$i + 1;
		echo "<tr><td>$j</td><td>".$codeprefix_list[$i][1]."</td>"."<td>".$codeprefix_list[$i][2]."</td>"."<td>".$codeprefix_list[$i][0]."</td>"."</tr>";
	}
	echo "</table><p>";
	
	echo "<b>Project Code List ($codeno)</b><br>";
	echo "<table border=1><tr><th>No</th><th>Code</th></tr>";
	for ($i=0; $i<$codeno; $i++) {
		$j =	$i + 1;
		$code	= $codelist[0][$i];
		$code = underscores($code);
		echo "<tr><td>$j</td><td>$code</td></tr>";
	}
	echo "</table><p>";
	
	echo "<b>Prefix code and their positions ($no_prefix)</b><br>";
	echo "<table border=1><tr><th>No</th><th>Prefix</th><th>Lable</th>";
	echo "<th>Sum Row</th><th>Row 1</th><th>Row 2</th></tr>";
	for ($i=0; $i<$no_prefix; $i++) {
		$j =	$i + 1;
		echo "<tr><td>$i</td><td>".$codeprefix_list[$i][1]."</td>"."<td>".$codeprefix_list[$i][2]."</td>"
		."<td>".$codegrprange[0][$i]."</td><td>".$codegrprange[1][$i]."</td><td>"
		.$codegrprange[2][$i]."</td></tr>";
	}
	echo "</table><p>";	
//*/
?>
</html>