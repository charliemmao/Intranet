<html>

<?php
//Table 6
	$rpthdstringidx = 0;
	
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
	$sql = "SELECT ghrcode as ghrc, rlacode as rlac, costcenter FROM timesheet.ghrtorlacode
		WHERE active='y'
		ORDER BY ghrc"; //enddate='0000-00-00' or enddate>'$fromdate'
	$result = mysql_query($sql);
	$filelog = __FILE__;
	$linelog = __LINE__;
	include("err_msg.inc");
	$noghr = mysql_num_rows($result);
	$noghrctr=0;
	$debug = "";
	if ($debug) {echo "<table border=1><th>Counter</th><th>RLA</th><th>GHR</th><th>Cost Center</th></tr>";}
	while (list($ghrc, $rlac, $costcenter) = mysql_fetch_array($result)) {
		$tmp = $rlac;
		$tmp = ereg_replace("__", " ", $tmp);
		$tmp = ereg_replace("_", " ", $tmp);
		$ghrcode[$noghrctr][0] = $ghrc;
		$ghrcode[$noghrctr][1] = $tmp;
		$ghrcode[$noghrctr][3] = $costcenter;
		$j= $noghrctr+1;
		if ($debug) {echo "<tr><td>$j</td><td>$ghrc</td><td>$rlac</td><td>$costcenter</td></tr>";}
		$noghrctr++;
	}
	
	if ($debug) {
		echo "</table>";
		echo "<table border=1><th>RLA</th><th>RLA</th></tr>";
		for ($irla=0; $irla<=$codeplusgroup; $irla++) {
			echo "<tr><td>".$rlacode[$irla][0]."</td><td>".$rlacode[$irla][1]."</td></tr>";
		}
		echo "</table>";
	}
	//find staff list which should be removed from report to GHR
	$sql = "SELECT description 
        FROM logging.sysmastertable 
        WHERE item='rpt_ghr_ex_list';";
    $result = mysql_query($sql);
    include("err_msg.inc");
    list($description) = mysql_fetch_array($result);
	$exstaff = explode("@", $description);

	$notestoGHR = "";
	for ($i=0; $i<count($exstaff); $i++) {
		//echo $exstaff[$i]."<br><br>";
		$exstaffhash[$exstaff[$i]] = 1;
		for ($j=0; $j<=$staffno; $j++) {
			if ($stafflist[0][$j] == $exstaff[$i] ) {
				$notestoGHR .= "\t".$stafflist[2][$j]." ".$stafflist[3][$j]."\n";
			}
		}
	}
	if (count($exstaff) > 1) {
		$tstrghr = "are";
	} else {
		$tstrghr = "is";
	}
	$notestoGHR = "The following contract staff $tstrghr removed from report to GHR.\n$notestoGHR";
	
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
		$notmatchcode = "Following GHR code(s) do not have a match\\n"
			."in RLA project codes during the report period:\\n\\n"
			."$notmatchcode\\nProbably due to the project's period setting.\\n"
			."Please advice your DB administrator to change it\\n"
			."if you think this is a problem.\\n";
		echo "<script language=\"javascript\">\n";
		echo "window.alert(\"$notmatchcode\");\n";
		echo "</script>";
	}

	##########	reorganise data for GHR report
	for ($j=0; $j<=$staffno; $j++) {
		$tmp = $stafflist[0][$j];
		if (!$exstaffhash[$tmp]) {
			for ($i=0; $i<$noghrctr; $i++) {
				$k = $ghrcode[$i][2];
				$ghrrpt[$j][$i] = $minarray[$k][$wrt_ind][$j];
			}
		} else {
			//echo "$tmp<br>";
		}
	}

//Overlapping subtraction: start
//*
	$olpctr = 0;
	$overlapcode[$olpctr][0] = "RLA-OHDS";	//group code
	$overlapcode[$olpctr][1] = "RLA-OHD-AL";	//individual GHR code
	$olpctr++;
	
	$overlapcode[$olpctr][0] = "RLA-NT";	//group code
	$overlapcode[$olpctr][1] = "RLA-AUSIND";	//individual GHR code
	$olpctr++;
//*/

	$notestoGHR .= "\nThe following individual code entries are considered to be double counted".
		" therefore their time removed from group code entries.\n".
		"\tIndividual_Code\tGroup_Code\n";

	for ($ol=0; $ol<$olpctr; $ol++) {
		$grpcode = $overlapcode[$ol][0];
		$indcode = $overlapcode[$ol][1];
		$notestoGHR .= "\t$indcode\t$grpcode\n";
		for ($i=0; $i<$noghrctr; $i++) {
			if ($ghrcode[$i][0] == $grpcode) {
				$grpcode_posn = $i;
				break;
			}
		}
		for ($i=0; $i<$noghrctr; $i++) {
			if ($ghrcode[$i][0] == $indcode) {
				$indcode_posn = $i;
				break;
			}
		}
		##########subtract $ghrrpt[$j][$indcode_posn] from $ghrrpt[$j][$grpcode_posn]
		for ($j=0; $j<=$staffno; $j++) {
			//echo "$j: ".$ghrrpt[$j][$grpcode_posn]."-".$ghrrpt[$j][$indcode_posn]."=";		
			$ghrrpt[$j][$grpcode_posn] = $ghrrpt[$j][$grpcode_posn]-$ghrrpt[$j][$indcode_posn];
			//echo $ghrrpt[$j][$grpcode_posn]."<br>";
			$ghrrpt[$j][$noghrctr] = 0;
		}
	}
	$notestoGHR .= "\n";
//Overlapping subtraction: end	
	
	##########		compute Total Minutes + project Sub Total
	$rlatotal = 0;
	for ($i=0; $i<$noghrctr; $i++) {
		$ghrrpt[$staffno][$i] = 0;
		for ($j=0; $j<$staffno; $j++) {
			$tmp = $stafflist[0][$j];
			if (!$exstaffhash[$tmp]) {
				$ghrrpt[$staffno][$i] += $ghrrpt[$j][$i];
			}
		}
		$rlatotal += $ghrrpt[$staffno][$i];
	}
	//echo "$rlatotal<br>from projects<br>";

	##########		compute Total Minutes + Staff Sub Total
	$rlatotal = 0;
	for ($j=0; $j<=$staffno; $j++) {
		$tmp = $stafflist[0][$j];
		$ghrrpt[$j][$noghrctr] = 0;
		if (!$exstaffhash[$tmp]) {
			for ($i=0; $i<$noghrctr; $i++) {
				$ghrrpt[$j][$noghrctr] += $ghrrpt[$j][$i];
			}
			$rlatotal += $ghrrpt[$j][$noghrctr];
		} else {
			//echo $tmp."<br>";
		}
		//$popup = $stafflist[2][$j]." ".$stafflist[3][$j];
		//echo $popup.": ".$ghrrpt[$j][$noghrctr]."<br>";
	}
	
	$rlatotal -= $ghrrpt[$staffno][$noghrctr];
	//echo "$rlatotal<br>".$ghrrpt[$staffno][$noghrctr]."<br>";
	//exit;
	
//Percentage Table
	echo "<h3>Section I: Percentage Table</h3>";
	$htmlstr = "<table border=$tabborder cellspacing=\"0\" cellpadding=\"0\">
		<tr><th width=$wid align=center><font size=$thfs>Name|Project</font></th>";	
	echo $htmlstr;
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,"$head$tablecaption\n$tailcsv\nSection I: Percentage Table\n");		
		fputs($fpcsv,"Name|Project");
		fputs($fphtml,"<ht"."ml><h1>$head$tablecaption</h1>$tailhtml<br>");
		fputs($fphtml,$htmlstr);
	}
	
	## Project List
	for ($i=0; $i<$noghrctr; $i++) {
		$tmp = $ghrcode[$i][$rpthdstringidx];
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
			
//cost center list
	echo "<tr><td>&nbsp;</td>";
	if ($fpcsv && $fphtml) {
		fputs($fphtml, "<tr><td>&nbsp;</td>");
		//fputs($fpcsv,",");
	}
	for ($i=0; $i<$noghrctr; $i++) {
		$tmp = $ghrcode[$i][3];
		$htmlstr = "<td valign=bottom><b><font size=$thfs>$tmp</b></font></td>";
		echo $htmlstr;
		if ($fpcsv && $fphtml) {
			fputs($fphtml, $htmlstr);
			fputs($fpcsv,",".$tmp);
		}
	}
	$htmlstr = "<td>&nbsp;</td>";
	echo $htmlstr;
	if ($fpcsv && $fphtml) {
		fputs($fphtml, $htmlstr);
		fputs($fpcsv,"");
	}
	$htmlstr = "</tr>";
	echo $htmlstr;
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,"\n");
		fputs($fphtml,$htmlstr);
	}
//cost center list end

	$digits = 2;
	## staff data
	for ($j=0; $j<=$staffno; $j++) {
		$tmp = $stafflist[0][$j];
		if (!$exstaffhash[$tmp]) {
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
	}
	echo "</table><p>";
	
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,"\n\n");
		fputs($fphtml,"</table><p>");
	}
	
//minutes Table
	$stafflist[0][$staffno] = "People (min)";
	echo "<h3>Section II: Minutes Table</h3>";
	$htmlstr = "<table border=$tabborder cellspacing=\"0\" cellpadding=\"0\">
		<tr><th width=$wid align=center><font size=$thfs>Name|Project</font></th>";	
	echo $htmlstr;
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,"$head$tablecaption\n$tailcsv\nSection II: Minutes Table\n");		
		fputs($fpcsv,"Name|Project");
		fputs($fphtml,"<h1>$head$tablecaption</h1>$tailhtml<br>");
		fputs($fphtml,$htmlstr);
	}
	
	## Project List
	for ($i=0; $i<$noghrctr; $i++) {
		$tmp = $ghrcode[$i][$rpthdstringidx];
		$htmlstr = "<td valign=bottom><b><font size=$thfs>$tmp</b></font></td>";
		echo $htmlstr;
		if ($fpcsv && $fphtml) {
			fputs($fphtml, $htmlstr);
			fputs($fpcsv,",".$tmp);
		}
	}
	$htmlstr = "<td valign=bottom><b><font size=$thfs>Total(min)</b></font></td>";
	echo $htmlstr;
	if ($fpcsv && $fphtml) {
		fputs($fphtml, $htmlstr);
		fputs($fpcsv,",Total(min)");
	}
	$htmlstr = "</tr>";
	echo $htmlstr;
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,"\n");
		fputs($fphtml,$htmlstr);
	}
			
//cost center list
	echo "<tr><td>&nbsp;</td>";
	if ($fpcsv && $fphtml) {
		fputs($fphtml, "<tr><td>&nbsp;</td>");
		//fputs($fpcsv,",");
	}
	for ($i=0; $i<$noghrctr; $i++) {
		$tmp = $ghrcode[$i][3];
		$htmlstr = "<td valign=bottom><b><font size=$thfs>$tmp</b></font></td>";
		echo $htmlstr;
		if ($fpcsv && $fphtml) {
			fputs($fphtml, $htmlstr);
			fputs($fpcsv,",".$tmp);
		}
	}
	$htmlstr = "<td>&nbsp;</td>";
	echo $htmlstr;
	if ($fpcsv && $fphtml) {
		fputs($fphtml, $htmlstr);
		fputs($fpcsv,"");
	}
	$htmlstr = "</tr>";
	echo $htmlstr;
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,"\n");
		fputs($fphtml,$htmlstr);
	}
//cost center list end

	$digits = 2;
	## staff data
	$i0 = $staffno - count($exstaff);
	for ($j=0; $j<=$staffno; $j++) {
		$tmp = $stafflist[0][$j];
		if (!$exstaffhash[$tmp]) {
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
	}
	echo "</table><p>";
	//echo "$rlatotal<br><br>";
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,"\n\n");
		$i = count($wklist);
		$estimatemin = $i*2280*($staffno - count($exstaff));
		fputs($fpcsv,"Weeks,,,$i\n");
		fputs($fpcsv,"Staff number,,,$i0\n");
		fputs($fpcsv,"Estimated minutes,,,$estimatemin\n");
		fputs($fpcsv,"Actual minutes,,,$rlatotal\n");
		$tmp = number_format(100*$rlatotal/$estimatemin, 2);
		fputs($fpcsv,"Actual/Estimation,,,$tmp%\n");

		fputs($fphtml,"</table><p>");
		fclose($fpcsv);
		fputs($fphtml,"</ht"."ml>");
		fclose($fphtml);
	}
?>
</html>
