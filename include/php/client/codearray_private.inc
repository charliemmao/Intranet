<html>
<?php
	include('connet_root_once.inc');
	$qry	=	"SELECT codelist from timesheet.privatecode where email_name='".$email_name."';";
	//echo $qry.'<br>';
	include('find_one_val.inc');
	if ($out) {
		//echo $out.'<br>';
		$tmp = explode($patsym,$out);
		$no_select	=	count($tmp);
		for ($i=0; $i<$no_select; $i++) {
			$pri_codelist[0][$i] = $tmp[$i];
			/*
			if ($priv == "00") {
				echo $i." ".$pri_codelist[0][$i].'<br>';
			}
			//*/
		}
	} else {
		$no_select	=	0;
	}
	mysql_close($contid);
	$prino = $no_select;	
	if (count($dbrcd_code)>0) {
		if ($priv == "00") {
			//echo "<br>Number of code from db search is ".count($dbrcd_code)."-$no_select<br>";
			for ($uu=0; $uu<count($dbrcd_code); $uu++) {
				//echo $dbrcd_code[$uu]."<br>";
			}
		}
		for ($j=0; $j<count($dbrcd_code); $j++) {
			$newcode = 1;
			$codedb = $dbrcd_code[$j];
			if ($priv == "00") {
				//echo $codedb."<br>";
			}
			for ($k=0; $k<$no_select; $k++) {
				$codepri = $pri_codelist[0][$k];
				if ($priv == "00") {
					//echo "&nbsp;&nbsp;&nbsp;".$codepri."<br>";
				}
				if ($codepri == $codedb) {
					$newcode = 0;
					break;
				}
			}
			if ($newcode == 1) {
				$pri_codelist[0][$prino] = $codedb;
				$prino++;
				if ($priv == "00") {
					//echo $codedb.": ".$dbrcd_list[$codedb]."<br>";
				}
			}
		}
	}
	if ($prino != $no_select) {
		$no_select = $prino;
	}
	if ($selectall) {
		$no_select = $codeno;
		for ($i=0; $i<$codeno; $i++) {
			$pri_codelist[0][$i] = $codelist[0][$i];
		}
	}
	if ($deselect) {
		$no_select = 0;
	}
	
	##	sort code
	$code_order_no = 0;
	for ($j=0; $j<$no_prefix; $j++) {
		$sub_h = $codeprefix_list[$j][1];
		$sub_h_len = $codeprefix_list[$j][0];
		//echo "<b>$sub_h</b><br>";
		for ($i=0; $i<$codeno; $i++) {
			$code = $codelist[0][$i];
			$code_len = strlen($code);
			if ($code_len >= $sub_h_len) {
				if (substr($code, 0, $sub_h_len) == $sub_h) {
					$code_ordered_list[$code_order_no] = $code;
					//echo "&nbsp;&nbsp;".$code_ordered_list[$code_order_no]."<br>";
					$code_order_no++;
				}
			}
		}
	}
	/*
	echo $code_order_no."-code_order<br>";
	echo "<br>";
	for ($i=0; $i<$no_select; $i++) {
		echo $i." ".$pri_codelist[0][$i]."<br>";
	}
	//*/
	$j = 0;
	for ($i=0; $i<$codeno; $i++) {
		$code = $code_ordered_list[$i];
		for ($k=0; $k<$no_select; $k++) {
			if ($code == $pri_codelist[0][$k]) {
				$pri_ordered[$j] = $code;
				$j++;
				break;
			}
		}
	}
	//echo "<br>";
	for ($i=0; $i<$no_select; $i++) {
		$pri_codelist[0][$i] = $pri_ordered[$i];
		//echo $i." ".$pri_codelist[0][$i]."<br>";
	}
	
# 	find definition for private selected code
#	both project and private codes are zero based
	for ($i=0; $i<$no_select; $i++) {
		$temp_pri	=	$pri_codelist[0][$i];
		for ($j=0; $j<$codeno; $j++) {
			if ($temp_pri == $codelist[0][$j] ) {
				//echo $temp_pri.'<br>';
				for ($k=1; $k<4; $k++) {
					$pri_codelist[$k][$i] = $codelist[$k][$j]; 
					//echo $codelist[$k][$j].'<br>';
				}
				$codelist[4][$j]	=	1; //used in the display of private code selection
				//exit;
			}
		}
	}
	
/*
#	show the matched code
	//0: brief_code; 1:special; 2:div15; 3:description
	echo '<table>';
	for ($i=0; $i<$no_select; $i++) {
		echo '<tr>';
		for ($j=0; $j<4; $j++) {
			echo '<td>'.$pri_codelist[$j][$i].'</td>';
		}
		echo '</tr>';
	}
	echo '</table><br>';
	//exit;
//*/
?>
</html>
