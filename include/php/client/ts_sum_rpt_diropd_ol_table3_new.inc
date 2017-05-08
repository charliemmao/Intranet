<html>
<?php
############################################
##	Table 3: Project_Weeks - rPjW
############################################
	$tablecaption = $tablelist[$tabno];
	$stafflist[0][$staffno] = $heading["tr"][$tabno];
	$ordered_code[0][$codeplusgroup] = $heading["bl"][$tabno];
	$ordered_code[3][$codeplusgroup] = $ordered_code[0][$codeplusgroup];
	$righthead = $heading["tr"][$tabno];
	$endhead = $heading["bl"][$tabno];
	$newfile0[0][0]= $filelist["csv"][$tabno];
	$newfile0[1][0]	=	$svrfld.$newfile0[0][0];
	$newfile0[0][1]= $filelist["php"][$tabno];
	$newfile0[1][1]	=	$svrfld.$newfile0[0][1];
	tabforbackword($tabno, count($tablelist), $bulsize, $tablecaption, $newfile0[0][0], $newfile0[0][1]);
	$tabno++;
	$wkstr = $wklist[$yhyqmw[$wrt_ind][1]];
	$wkstr = $wkstrtono[$wkstr];
	$wkend  = $wklist[$yhyqmw[$wrt_ind][2]];
	$wkend  = $wkstrtono[$wkend ];

	/*
	echo "tablecaption =$tablecaption<br>righthead =$righthead<br>endhead =$endhead<br>
		wrt_ind =$wrt_ind<br>wkstr =$wkstr<br>wkend  =$wkend";
	//*/
	
	#################outline overall heading start#############################
	$RCstr = "rPjW";
	$RCid = $RCstr;
	$code = "id=\"$RCid\"$click";
	$ctrall = "<font size=2 color=#ff0000><b>	<div $code>".$clkcap[$tabno-1]."</div></b></font><br>";
	echo $ctrall;
	#################outline overall heading end#############################
	$wids = 250;
	$widn = 100;
	$wide = 150;
	
	//echo "fromdate=$fromdate<br>	todate=$todate <br>";
	$fromyear3 = substr($fromdate,2,2);
	$frommonth3 = substr($fromdate,5,2);
	$toyear3 = substr($todate,2,2);
	if ($fromyear3 == $toyear3) {
		if ($frommonth3 < 7) {
			$fromyear3 = $fromyear3  - 1;
			if ($fromyear3 < 10) {
				$fromyear3= "'0".$fromyear3;
			}
		} else {
			$toyear3 = $toyear3 + 1;
			if ($toyear3 < 10) {
				$toyear3 = "'0".$toyear3 ;
			}
		}
	}
	$finyear = " FY".$fromyear3."-".$toyear3;
	
	if ($rpttypeS == "MthSum") {
		$widnt = $widn*($wkend - $wkstr);	
		$widt = $wids + $widnt + $wide;
	} elseif ($rpttypeS == "QtrSum") {
		$widnt = $widn*3;	
		$widt = $wids + $widnt + $wide;
		
		$tablecap = $tablecaption;
		$tablecaption = "Projects vs Months: ";
		$mstrs = substr($fromdate,5,2);
		$mstre = $mstrs + 2;
		//echo "fromyear3=$fromyear3; toyear3 =$toyear3 ;<br>";
		if (4 < strlen($mth[$mstrs])) {
			$ms = substr($mth[$mstrs],0,4);
		} else {
			$ms = $mth[$mstrs];
		}
		if (4 < strlen($mth[$mstre])) {
			$me = substr($mth[$mstre],0,4);
		} else {
			$me = $mth[$mstre];
		}
		//echo "fromyear3=$fromyear3; toyear3 =$toyear3 ;<br>";
		$tablecaption = $tablecaption.$ms.$fromyear3." - ".$me.$fromyear3;
		$tabklast = "Total for ".$qtrQN[$mstrs].$finyear;
		$tabklast = ereg_replace("'","",$tabklast);
	} elseif (	$rpttypeS == "HfySum") {
		$tabklast = "Total for ".$hylist[$frommonth3]." year".$finyear;
	} elseif (	$rpttypeS == "YlySum") {
		$tabklast = "Total for".$finyear;
	}
	##  MthSum "QtrSum"	"HfySum"	"YlySum"	"SlpSum" 	$rpttypeS
	
	$filetitle = $newfile0[0][0];
	$newfile	=  $newfile0[1][0];
	$fpcsv	=	fopen($newfile,'w+');
	$filetitle = $newfile0[0][1];
	$newfile	=  $newfile0[1][1];
	$fphtml	=	fopen($newfile,'w+');
	
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,"$head$tablecaption\n$tailcsv");
		fputs($fphtml,"<ht"."ml><h1>$head$tablecaption</h1>$tailhtml$ctrall<br>");
	}
	
	## table heading: left cell
	$htmlstr = "<table $cell0 width=$widt>";
	$csvhd = ",";
	
	//if ($rpttypeS == "QtrSum") {
		$htmlstr = $htmlstr."<tr><th>&nbsp;</th>";
		for ($i=0; $i<$nomonth; $i++) {
			$j = $monthindex[$i];
			$coldatano[$i] = $j;
			$datestr = $wklist[$yhyqmw[$j][1]];
			$mthno = substr($datestr,5,2);
			$fromyear3 = "'".substr($datestr,2,2);
			$datestr = $mth[$mthno];
			$htmlstr = $htmlstr."<th colspan=2><font size=$thfs>$datestr$fromyear3</font></th>";
			$csvhd = $csvhd."$datestr$fromyear3,,";
		}
		$htmlstr = $htmlstr."<th colspan=2><font size=$thfs>$tabklast</font></th></tr>";
		$csvhd = $csvhd."$tabklast\n"; 
		echo $htmlstr;
		if ($fpcsv && $fphtml) {
			fputs($fpcsv,$csvhd);
			fputs($fphtml,$htmlstr);
		}
	//}

	$htmlstr = "<tr><th width=$widn><font size=$thfs>Project</font></th>";
	$csvhd = "Project,";
	if ($rpttypeS == "MthSum") {
		for ($i=0; $i<$nowks; $i++) {
			$coldatano[$i] = $weekindex[$i];
			$htmlstr = "<th width=$widn><font size=$thfs>$wklist[$i]</font></th>";
			echo $htmlstr;
			if ($fpcsv && $fphtml) {
				fputs($fpcsv,",$wklist[$i]");
				fputs($fphtml,$htmlstr);
			}
		}
		$htmlstr = "<th width=$wide><font size=$thfs>$righthead</font></th></tr>";
		echo $htmlstr;
		if ($fpcsv && $fphtml) {
			fputs($fpcsv,",$righthead\n");
			fputs($fphtml,$htmlstr);
		}
		$coldatano[$noweek] = $monthindex[0];
		$totcol = $noweek+1;
	} else {	//($rpttypeS == "QtrSum")
	##	$rpttypeS == "MthSum" "QtrSum"	"HfySum"	"YlySum"	"SlpSum" 
		for ($i=0; $i<=$nomonth; $i++) {
			#$htmlstr = $htmlstr."<td width=$widn align=middle>Hours</td><td width=$widn align=middle>%</td>";
			$htmlstr = $htmlstr."<th width=$widn><font size=$thfs>Hours</font></th>
			<th width=$widn><font size=$thfs>%</font></th>";
			$csvhd = $csvhd."Hours,%,";
		}
		$htmlstr = $htmlstr."</tr>";
		$csvhd = $csvhd."\n"; 
		echo $htmlstr;
		if ($fpcsv && $fphtml) {
			fputs($fpcsv,$csvhd);
			fputs($fphtml,$htmlstr);
		}
	} 
	
	// find TIL related rows
	for ($i=0; $i<$codeplusgroup; $i++) {
		$tmp = $ordered_code[3][$i];
		$tmp = underscores($tmp);
		if ($tmp == "RLA-OVERHEADS") {
			$TILSumRow = $i;
			break;
		}
	}
	for ($i=0; $i<$codeplusgroup; $i++) {
		$tmp = $ordered_code[0][$i];
		$tmp = underscores($tmp);
		if ($tmp == "RLA-OHD-Time in Lieu") {
			$TILRow = $i;
			break;
		}
	}
	// Find TIL minutes
	for ($j=0; $j<=$nomonth; $j++) {
		if ($j<$nomonth) {
			$k = $monthindex[$j];
		} else {
			if ($rpttypeS == "QtrSum") {
				$k = $qtrindex[0];	
			} elseif ($rpttypeS == "HfySum") {
				$k = $hyindex[0];	
			} elseif ($rpttypeS == "YlySum") {
				$k = $yrindex[0];	
			//} elseif ($rpttypeS == "QtrSum") {
				//$k = $qtrindex[0];
			}	
		}
		$TILmthtot[$j] = $minarray[$TILRow][$k][$staffno];
		$TSmthtot_TIL[$j] = $minarray[$codeplusgroup][$k][$staffno] - $TILmthtot[$j];
		$tilstr = number_format($TILmthtot[$j]/60, 2);
		$tottilstr = number_format($TSmthtot_TIL[$j]/60, 2);
		#echo "<br>$j $TILmthtot[$j] $tilstr<br>$TSmthtot_TIL[$j] $tottilstr<br>";
	}

	for ($i=0; $i<=$codeplusgroup; $i++) { #rows
		//echo "$i: $TILSumRow  $TILRow<br>";
		### project title or project group title
		$ip1= $i + 1;
		$RCid = $RCstr.$ip1;
		if ($ordered_code[2][$i] == "") { //project group
			$disp = "";
			$code = "id=\"$RCid\"$click$disp";
			$tmp = $ordered_code[3][$i];
			$tmp = underscores($tmp);
			if ($tmp == "RLA-OVERHEADS") {
				$tmp = "RLA-OVERHEADS (-TIL)";
			}
			$b1 = "<font size=$tdfs color=#0000ff><b $code>";
			$b2 = "</b></font>";
			$htmlstr = "<tr><td width=$wids>".$b1.$tmp.$b2."</td>";
			echo $htmlstr;
			if ($fpcsv && $fphtml) {
				fputs($fpcsv,$tmp);
				fputs($fphtml,$htmlstr);
			}
			$b1 = "<font size=$tdfs color=#0000ff><b>";
		} else {
			if ($i<$codeplusgroup) {	//individual project
				$disp = $hide;
				$code = "id=\"$RCid\"$disp";
				$tmp = $ordered_code[0][$i];
				$tmp = underscores($tmp);
				$b1 = "";
				$b2 = "";
				$popup = $ordered_code[3][$i];
				$popup = ereg_replace("'", "", $popup);
				$popup = ereg_replace('"', "", $popup);
				$htmlstr = "<tr $code><td width=$wids><font size=$tdfs>"
					."<a onMouseOver=\"self.status='$popup';\" onMouseOut=\"self.status='';\">"
					."&nbsp;&nbsp;$tmp</a></font></td>";
				echo $htmlstr;
				if ($fpcsv && $fphtml) {
					fputs($fpcsv,"  ".$tmp);
					fputs($fphtml,$htmlstr);
				}
			} elseif ($i==$codeplusgroup) {
				### project total by month
				$endhead = "Sub Total (RLA) [-TIL]";
				$htmlstr = "<tr><td><font size=$thfs width=$wids><b>$endhead</b></font></td>";
				echo $htmlstr;
				if ($fpcsv && $fphtml) {
					fputs($fpcsv,"$endhead");
					fputs($fphtml,$htmlstr);
				}
			}
		}
		
		//"$TILSumRow  $TILRow<br>";
		//$weekindex[$noweek]	$monthindex[$nomonth]	$qtrindex[$noqtr]
		//$hyindex[$nohy]			$yrindex[$noyr]  

		### periodically sub total by project
		for ($j=0; $j<=$nomonth; $j++) {
			if ($j<$nomonth) {
				$k = $monthindex[$j];
			} else {	//for sub total
				if ($rpttypeS == "QtrSum") {
					$k = $qtrindex[0];	
				} elseif ($rpttypeS == "HfySum") {
					$k = $hyindex[0];	
				} elseif ($rpttypeS == "YlySum") {
					$k = $yrindex[0];	
				//} elseif ($rpttypeS == "QtrSum") {
					//$k = $qtrindex[0];
				}	
			}
			##	$rpttypeS == "MthSum" "QtrSum"	"HfySum"	"YlySum"	"SlpSum" 
			//$weekindex[$noweek]	$monthindex[$nomonth]	$qtrindex[$noqtr]
			//$hyindex[$nohy]			$yrindex[$noyr]  

			//$TILmthtot[$j] 		$TSmthtot_TIL[$j]
			if ($i == $TILSumRow || $i == $codeplusgroup) {
				$tmp_h = $minarray[$i][$k][$staffno] - $TILmthtot[$j];
				//echo "<br>$k: $tmp_h = $minarray[$i][$k][$staffno] - $TILmthtot[$j]<br>";
			} else {
				$tmp_h = $minarray[$i][$k][$staffno];
			}
			if ($tmp_h) {
				$tmp_p = 100*$tmp_h/$TSmthtot_TIL[$j];
				$tmp_p = number_format($tmp_p, 2)."%";
				$tmp_h = number_format($tmp_h/60, 2);
				$tmp_h = ereg_replace(",","",$tmp_h);
				$htmlstr_h = "<td align=center width=$widn><font size=$tdfs>$b1$tmp_h$b2</font></td>";
				$htmlstr_p = "<td align=center width=$widn><font size=$tdfs>$b1$tmp_p$b2</font></td>";
				$htmlstr = $htmlstr_h.$htmlstr_p;
				$tmp = ",$tmp_h,$tmp_p";
			} else {
				$htmlstr = "<td align=center width=$wid><font size=$tdfs>&nbsp;</font></td>";
				$htmlstr = $htmlstr.$htmlstr;
				$tmp = ",,";
			}
			echo $htmlstr;
			if ($fpcsv && $fphtml) {
				fputs($fphtml,$htmlstr);
				fputs($fpcsv,$tmp);
			}
		}
		$htmlstr = "</tr>";
		echo $htmlstr;
		if ($fpcsv && $fphtml) {
			fputs($fphtml,$htmlstr);
			fputs($fpcsv,"\n");
		}
	} 
		
	$htmlstr = "</table><p>"; 
	$tmp = "";
	echo $htmlstr;
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,",$tmp\n\n");
		fclose($fpcsv);
		fputs($fphtml,$htmlstr);
		fputs($fphtml,"</ht"."ml>");
		fclose($fphtml);	
	}
	if ($tablecap) {
		$tablecaption = $tablecap;
	}
#echo "end table 3";
//exit;
?>
</html>

