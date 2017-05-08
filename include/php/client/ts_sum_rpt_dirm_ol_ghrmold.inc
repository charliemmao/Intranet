<html>
<?php
//Table 5
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
	$mailmsg = "display";
	include("ts_ready_for_GHR.inc");

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
				//$tmp = number_format(100*$tmp/$minarray[$codeplusgroup][$wrt_ind][$j], 2);
				//$tmp = ereg_replace(",", "", $tmp);
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
	
//New Table 5
#########################################################
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

	//$mailmsg = "display";
	//include("ts_ready_for_GHR.inc");

	$filetitle = $newfile0[0][0];
	$newfile	=  $newfile0[1][0];
	$fpcsv	=	fopen($newfile,'w+');
	$filetitle = $newfile0[0][1];
	$newfile	=  $newfile0[1][1];
	$fphtml	=	fopen($newfile,'w+');
	
	########	Transform Code
	for ($irla=0; $irla<=$codeplusgroup; $irla++) {
		$tmp = $ordered_code[0][$irla];
		$tmp = ereg_replace("__", " ", $tmp);
		$tmp = ereg_replace("_", " ", $tmp);
		$rlacode[$irla][0] = $tmp;
		
		$tmp = $ordered_code[3][$irla];
		$tmp = ereg_replace("__", " ", $tmp);
		$tmp = ereg_replace("_", " ", $tmp);
		$rlacode[$irla][1] = $tmp; 
	}
	########	Find RLA_GHR Code
	$sql = "SELECT ghrcode as ghrc, rlacode as rlac FROM timesheet.ghrtorlacode";
	$result = mysql_query($sql);
	$filelog = __FILE__;
	$linelog = __LINE__;
	include("err_msg.inc");
	$noghr = mysql_num_rows($result);
	$noghrctr=0;
	while (list($ghrc, $rlac) = mysql_fetch_array($result)) {
		$ghrcode[$noghrctr][0] = $ghrc;
		$ghrcode[$noghrctr][1] = $rlac;
		$noghrctr++;
	}
	########	Find GHR Code position in $ordered_code
	for ($ighr=0; $ighr<$noghrctr; $ighr++) {
		for ($irla=0; $irla<=$codeplusgroup; $irla++) {
			$tmp = $ghrcode[$ighr][1];
			if ( $tmp == $rlacode[$irla][0] || 
				  $tmp == $rlacode[$irla][1] ) {
				$ghrcode[$ighr][2] = $irla;
				break;
			}
		}
	}

	$notmatch = "";
	$notmatchcode = "";
	for ($i=0; $i<$noghrctr; $i++) {
		if (!$ghrcode[$i][2]) {
			$notmatch = $notmatch."<tr><td>$i</td><td>".$ghrcode[$i][0]."</td><td>".
			$ghrcode[$i][1]."</td><td>".$ghrcode[$i][2]."</td></tr>";
			$notmatchcode = $notmatchcode.$ghrcode[$i][0]."\\n";
		}
	}

	if ($notmatch != "") {
		echo "<p><table border=1>";
		echo "<tr><th>No</th><th>GHR Code</th><th>Code-from GHR</th><th>Position</th></tr>";
		echo $notmatch;
		echo "</table><br>";
		//echo $notmatchcode."<br>";
		$notmatchcode = "Following GHR code(s) do not have a match in RLA project codes:\\n\\n"
			."$notmatchcode\\nProbably due to the project's END DATE setting.\\n"
			."Please advice your DB administrator to fix it if you think this is a problem.\\n";
		echo "<script language=\"javascript\">\n";
		echo "window.alert(\"$notmatchcode\");\n";
		echo "</script>";
	}

	/*
	echo "<p>$codeplusgroup<table border=1>";
	echo "<tr><th>No</th><th>Code</th><th>Description</th></tr>";
	for ($i=0; $i<$codeplusgroup; $i++) {
			echo "<tr><td>$i</td><td>".$rlacode[$i][0]."</td><td>".
				$rlacode[$i][1]."</td></tr>";
	}
	echo "</table><br>";
	//*/
	
	##########		Find out "RLA-OHDS" & "RLA-OHD-AL"
	for ($i=0; $i<$noghrctr; $i++) {
		if ($ghrcode[$i][0] == "RLA-OHDS") {
			$RLAOHDS = $i;
			break;
		}
	}
	for ($i=0; $i<$noghrctr; $i++) {
		if ($ghrcode[$i][0] == "RLA-OHD-AL") {
			$RLAOHDAL = $i;
			break;
		}
	}
	##########	reorganise data report to GHR
	for ($j=0; $j<=$staffno; $j++) {
		for ($i=0; $i<$noghrctr; $i++) {
			$k = $ghrcode[$i][2];
			$ghrrpt[$j][$i] = $minarray[$k][$wrt_ind][$j];
		}
	}
	##########subtract $ghrrpt[$j][$RLAOHDAL] from $ghrrpt[$j][$RLAOHDS]
	for ($j=0; $j<=$staffno; $j++) {
		//echo "$j: ".$ghrrpt[$j][$RLAOHDS]."-".$ghrrpt[$j][$RLAOHDAL]."=";
		$ghrrpt[$j][$RLAOHDS] = $ghrrpt[$j][$RLAOHDS]-$ghrrpt[$j][$RLAOHDAL];
		//echo $ghrrpt[$j][$RLAOHDS]."<br>";
		$ghrrpt[$j][$noghrctr] = 0;
	}
	
	##########		Find Total Minutes + Staff Sub Total
	for ($j=0; $j<=$staffno; $j++) {
		for ($i=0; $i<$noghrctr; $i++) {
			$ghrrpt[$j][$noghrctr] = $ghrrpt[$j][$noghrctr]+$ghrrpt[$j][$i];
		}
		//$popup = $stafflist[2][$j]." ".$stafflist[3][$j];
		//echo $popup.": ".$ghrrpt[$j][$noghrctr]."<br>";
	}
	
	$rlatotal=$ghrrpt[$staffno][$noghrctr] ;

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
	for ($i=0; $i<$noghrctr; $i++) {
		$tmp = $ghrcode[$i][0];
		$htmlstr = "<td valign=bottom><b><font size=$thfs>$tmp</b></font></td>";
		echo $htmlstr;
		if ($fpcsv && $fphtml) {
			fputs($fphtml, $htmlstr);
			fputs($fpcsv,",".$tmp);
		}
	}
	$htmlstr = "<td valign=bottom><b><font size=$thfs>Total(%)</b></font></td>";
	echo $htmlstr;
	if ($fpcsv && $fphtml) {
		fputs($fphtml, $htmlstr);
		fputs($fpcsv,",Total(%)");
	}
	$htmlstr = "</tr>";
	echo $htmlstr;
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,"\n");
		fputs($fphtml,$htmlstr);
	}
			
	$digits = 2;
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
		$subtotal = $ghrrpt[$j][$noghrctr];
		if (!$subtotal) {
			$subtotal=1;
		}
		
		if ($j == $staffno) {
			$b1 = "<font size=$thfs><b>";
			$b2 = "</b></font>";
			$digits = 3;
		} else {
			$b1 = "<font size=$tdfs>";
			$b2 = "</font>";				
			$digits = 2;
		}
		for ($i=0; $i<=$noghrctr; $i++) {
			
			//$ghrrpt[$j][$noghrctr]	$noghrctr	$ghrcode[$i][2]
			$tmp = $ghrrpt[$j][$i];
			if ($tmp) {
				$tmp = number_format($tmp/$subtotal, $digits);
				//$tmp = ereg_replace(",", "", $tmp);
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
#########################################################

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
