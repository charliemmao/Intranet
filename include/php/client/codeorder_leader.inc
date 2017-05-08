<html>
<?php
include('codearray.inc');
$isprefix = $grporind;
$codes = "";
if ($grporind == "Y") {
	for ($i=0; $i<$ldrgrouplist; $i++) {
		$var = "ldrgrouplist$i";
		if ($$var) {
			$codes = $codes.$$var."@";
		}
	}
} elseif ($grporind == "N") {
	for ($i=0; $i<$ldrcodelist; $i++) {
		$var = "ldrcodelist$i";
		if ($$var) {
			$codes = $codes.$$var."@";
		}
	}
}
if ($codes == "") {
	echo "<h2>No code or code group has been selected.</h2>";
	exit;
}
$tmp = explode("@",$codes);
$nosel = 0;
for ($i=0; $i<count($tmp); $i++) {
	if ($tmp[$i]) {
		//echo $nosel.": ".$tmp[$i]."<br>";
		$nosel++;
	}
}

$no_prefix = 0;

if ($isprefix == "Y") {
	include('ts_codeprefix_list.inc');	
	for ($i=0; $i<$nosel; $i++) {
		for ($j=0; $j<$no_prefix; $j++) {
			if ($codeprefix_list[$j][1] == $tmp[$i]) {
				$notmp[$i][0] = $codeprefix_list[$j][0];
				$notmp[$i][1] = $codeprefix_list[$j][1];
				$notmp[$i][2] = $codeprefix_list[$j][2];
				//echo "<br>$i-".$notmp[$i][0].", ".$notmp[$i][1]." ".$notmp[$i][2];
				break;
			}
		}
	}
	$no_prefix = $nosel;
	for ($i=0; $i<$no_prefix; $i++) {
		for ($j=0; $j<=2; $j++) {
			$codeprefix_list[$i][$j]= $notmp[$i][$j];
		}
		//echo "<br>$i - ".$codeprefix_list[$i][0].", ".$codeprefix_list[$i][1].", ".$codeprefix_list[$i][2];
	}
	//echo "$codes, $isprefix<br>";

	##	reorder prefix and project code
	/*
	echo "<table border=1>";
	echo "<tr><th>No</th><th>Prefix</th><th>Lable</th></tr>";
	//*/
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
} elseif ($isprefix == "N") {
	for ($i=0; $i<$nosel; $i++) {
		for ($j=0; $j<$codeno; $j++) {
			if ($codelist[0][$j] == $tmp[$i]) {
				$ordered_code[0][$i] = $codelist[0][$j];	//brief_code
				$ordered_code[1][$i] = $codelist[1][$j];	//special
				$ordered_code[2][$i] = $codelist[2][$j];	//div15
				$ordered_code[3][$i] = $codelist[3][$j];	//description
				break;
			}
		}
	}
	for ($i=0; $i<$nosel; $i++) {
		$codelist[0][$i] = $ordered_code[0][$i];	//brief_code
		$fromcodetono[$codelist[0][$i]] = $i;
		$codelist[1][$i] = $ordered_code[1][$i];	//special
		$codelist[2][$i] = $ordered_code[2][$i];	//div15
		$codelist[3][$i] = $ordered_code[3][$i];	//description
	}
	$codeplusgroup = $nosel;
	$codeno = $nosel;
	//$codegrprange[0][$j-1];
	/*
	for ($i=0; $i<$nosel; $i++) {
		echo "<br><br>$i";
		for ($j=0; $j<4; $j++) {
			echo "<br>".$codelist[$j][$i]." (".$ordered_code[$j][$i].")";
		}
	}
	//*/
}
?>
</html>