<html>
<?php
//Table 6
//if ($priv == "00") {
	$tablecaption = $tablelist[$tabno];
	$stafflist[0][$staffno] = $heading["tr"][$tabno];
	$ordered_code[0][$codeplusgroup] = $heading["bl"][$tabno];
	$ordered_code[3][$codeplusgroup] = $ordered_code[0][$codeplusgroup];
	$newfile0[0][0]= $filelist["csv"][$tabno];
	$newfile0[1][0]	=	$svrfld.$newfile0[0][0];
	$newfile0[0][1]= $filelist["html"][$tabno];
	$newfile0[1][1]	=	$svrfld.$newfile0[0][1];
	tabforbackword($tabno, count($tablelist), $bulsize, $tablecaption, $newfile0[0][0], $newfile0[0][1]);
	$tabno++;
	$rlatotal = $minarray[$codeplusgroup][$wrt_ind][$staffno];

	$filetitle = $newfile0[0][0];
	$newfile	=  $newfile0[1][0];
	$fpcsv	=	fopen($newfile,'w+');
	$filetitle = $newfile0[0][1];
	$newfile	=  $newfile0[1][1];
	$fphtml	=	fopen($newfile,'w+');
	
	$htmlstr = "<table border=$tabborder cellspacing=\"0\" cellpadding=\"0\">
		<tr><th width=$wid align=center><font size=$thfs>Name|Project</font></th>";	
	echo $htmlstr;
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,"$head$tablecaption\n$tailcsv\n");		
		fputs($fpcsv,"Name|Project");
		fputs($fphtml,"<ht"."ml><h1>$head$tablecaption</h1>$tailhtml<br>");
		fputs($fphtml,$htmlstr);
	}
	
	## Project List
	for ($i=0; $i<=$codeplusgroup; $i++) {
		if ($ordered_code[2][$i] == "") {
			$tmp = $ordered_code[3][$i];
			$tmp = underscores($tmp);
			$b1 = "<font size=$thfs color=#0000ff><b>";
			$b2 = "</font></b>";
			$htmlstr = "<td valign=top>".$b1.$tmp.$b2."</td>";
			echo $htmlstr;
			if ($fpcsv && $fphtml) {
				fputs($fphtml, $htmlstr);
				fputs($fpcsv,",".$tmp);
			}
		} else {
			$tmp = $ordered_code[0][$i];
			$tmp = underscores($tmp);
			$popup = $ordered_code[3][$i];
			$popup = ereg_replace("'", "", $popup);
			$popup = ereg_replace('"', "", $popup);
			$htmlstr = "<td valign=bottom><b><font size=$thfs>"
				."<a onMouseOver=\"self.status='$popup';\" onMouseOut=\"self.status='';\">"
				."$tmp</a></b></font></td>";
			echo $htmlstr;
			if ($fpcsv && $fphtml) {
				fputs($fpcsv,",".$tmp);
				fputs($fphtml,$htmlstr);
			}
		}
	}
	$htmlstr = "</tr>";
	echo $htmlstr;
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,"\n");
		fputs($fphtml,$htmlstr);
	}

	## staff data
	for ($j=0; $j<=$staffno; $j++) {
		$tmp = $stafflist[0][$j];
		$popup = $stafflist[2][$j]." ".$stafflist[3][$j];
		if ($j == $staffno) {
			$popup = $tmp;
		}
		$popup = strtolower($popup);
		$popup = ucwords($popup);
		$htmlstr = "<tr><td><font size=$thfs><b>$popup</b></font></td>";
		echo $htmlstr;
		if ($fpcsv && $fphtml) {
			fputs($fpcsv,$popup);
			fputs($fphtml,$htmlstr);
		}
		
		for ($i=0; $i<=$codeplusgroup; $i++) {
			if ($ordered_code[2][$i] == "") {
				$b1 = "<font size=$thfs color=#0000ff><b>";
				$b2 = "</font></b>";
			} else {
				if ($j == $staffno) {
					$b1 = "<font size=$thfs><b>";
					$b2 = "</b></font>";
				} else {
					$b1 = "<font size=$tdfs>";
					$b2 = "</font>";				
				}
			}
			
			$tmp = $minarray[$i][$wrt_ind][$j];
			if ($tmp) {
				$tmp = number_format($tmp/60, 2);
				$tmp = ereg_replace(",", "", $tmp);
				$htmlstr = "<td align=center>".$b1.$tmp.$b2."</td>";
			} else {
				$htmlstr = "<td align=center>&nbsp;</td>";
			}
			echo $htmlstr;
			if ($fpcsv && $fphtml) {
				fputs($fphtml,$htmlstr);
				fputs($fpcsv,",$tmp");
			}
		
		}
		echo "</tr>";
		if ($fpcsv && $fphtml) {
			fputs($fpcsv,"\n");
			fputs($fphtml,"</tr>");
		}
	}
	echo "</table><p>";
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,"\n\n");
		fclose($fpcsv);
		fputs($fphtml,"</table><p>");
		fputs($fphtml,"</ht"."ml>");
		fclose($fphtml);
	}
//}
?>
</html>
