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
	if ($rpttypeS == "MthSum") {
		$widnt = $widn*($wkend - $wkstr);	
		$widt = $wids + $widnt + $wide;
	} elseif ($rpttypeS == "QtrSum") {
		$widnt = $widn*3;	
		$widt = $wids + $widnt + $wide;
	}
	$filetitle = $newfile0[0][0];
	$newfile	=  $newfile0[1][0];
	$fpcsv	=	fopen($newfile,'w+');
	$filetitle = $newfile0[0][1];
	$newfile	=  $newfile0[1][1];
	$fphtml	=	fopen($newfile,'w+');
	
	## table heading: left cell
	$htmlstr = "<table $cell0 width=$widt>
		<tr><th width=$wids align=center><font size=$thfs>Project|Week</font></th>";	
	echo $htmlstr;
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,"$head$tablecaption\n$tailcsv\nProject|Month");
		fputs($fphtml,"<ht"."ml><h1>$head$tablecaption</h1>$tailhtml$ctrall<br>");
		fputs($fphtml,$htmlstr);
	}

	## table heading: rest cells
	##	$rpttypeS == "MthSum" "QtrSum"	"HfySum"	"YlySum"	"SlpSum" 
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
	} elseif ($rpttypeS == "QtrSum") {
		## table heading: middle cell
		for ($i=0; $i<$nomonth; $i++) {
			$j = $monthindex[$i];
			$coldatano[$i] = $j;
			$datestr = $wklist[$yhyqmw[$j][1]];
			$mthno = substr($datestr,5,2);
			$datestr = $mth[$mthno];
			$htmlstr = "<th width=$widn><font size=$thfs>$datestr</font></th>";
			echo $htmlstr;
			if ($fpcsv && $fphtml) {
				fputs($fpcsv,",$datestr");
				fputs($fphtml,$htmlstr);
			}
		}
		$datestr = $qtrfour[$mthno];
		$righthead = $datestr." Qtr Sub Total";
		## table heading: right cell
		$htmlstr = "<th width=$wide><font size=$thfs>$righthead</font></th></tr>";
		echo $htmlstr;
		if ($fpcsv && $fphtml) {
			fputs($fpcsv,",$righthead\n");
			fputs($fphtml,$htmlstr);
		}
		$coldatano[$nomonth] = $qtrindex[0];
		$totcol = $nomonth+1;
	}
	//$weekindex[$noweek]	$monthindex[$nomonth]	$qtrindex[$noqtr]
	//$hyindex[$nohy]			$yrindex[$noyr]  

	for ($i=0; $i<$codeplusgroup; $i++) { #rows
		### project name
		$ip1= $i + 1;
		$RCid = $RCstr.$ip1;
		if ($ordered_code[2][$i] == "") {	#project title column
			$disp = "";
			$code = "id=\"$RCid\"$click$disp";
			$tmp = $ordered_code[3][$i];
			$tmp = underscores($tmp);
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
		}
		
		### periodically sub total by project
		for ($j=0; $j<$totcol; $j++) {
			$k = $coldatano[$j];
			$tmp = $minarray[$i][$k][$staffno];
			if ($tmp) {
				$tmp = number_format($tmp/60, 2);
				$tmp = ereg_replace(",","",$tmp);
				$htmlstr = "<td align=center width=$widn><font size=$tdfs>$b1$tmp$b2</font></td>";
			} else {
				$htmlstr = "<td align=center width=$wid><font size=$tdfs>&nbsp;</font></td>";
			}
			echo $htmlstr;
			if ($fpcsv && $fphtml) {
				fputs($fphtml,$htmlstr);
				fputs($fpcsv,",$tmp");
			}
		}
		$htmlstr = "</tr>";
		$tmp = "";
		echo $htmlstr;
		if ($fpcsv && $fphtml) {
			fputs($fphtml,$htmlstr);
			fputs($fpcsv,",$tmp\n");
		}
	} 
	
	### project total
	$htmlstr = "<tr><td><font size=$thfs width=$wids><b>$endhead</b></font></td>";
	echo $htmlstr;
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,"$endhead");
		fputs($fphtml,$htmlstr);
	}
	for ($j=0; $j<$totcol; $j++) {
		$k = $coldatano[$j];
		$tmp = $minarray[$codeplusgroup][$k][$staffno];
		if ($tmp) {
			$tmp = number_format($tmp/60, 2);
			$tmp = ereg_replace(",","",$tmp);
			$htmlstr = "<td align=center width=$widn><font size=$thfs><b>$tmp</b></font></td>";
		} else {
			$htmlstr = "<td align=center width=$widn><font size=$tdfs>&nbsp;</font></td>";
		}
		echo $htmlstr;
		if ($fpcsv && $fphtml) {
			fputs($fphtml,$htmlstr);
			fputs($fpcsv,",$tmp");
		}
	}
	
	$htmlstr = "</tr></table><p>"; 
	$tmp = "";
	echo $htmlstr;
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,",$tmp\n\n");
		fclose($fpcsv);
		fputs($fphtml,$htmlstr);
		fputs($fphtml,"</ht"."ml>");
		fclose($fphtml);	
	}
?>
</html>

