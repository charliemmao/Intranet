<html>
<?php
############################################
##	Table 1: Project_People(hours)-rPPh: 
##		detailed project codes can be hidden or display horizontally
##		initially hidden.
##		Only project code or code groups with onClick event.
##		ID assignment rule:
##			id is in the format of id=rPPh#, where # represent their absoulte row number
##		For project group row, id is given in the first cell or <td id=#id>
##		For normal project code, id is given in the row def, or <tr id=#id>
############################################

	$tabno = 0;
	$tablecaption = $tablelist[$tabno];
	$stafflist[0][$staffno] = $heading["tr"][$tabno];
	$ordered_code[0][$codeplusgroup] = $heading["bl"][$tabno];
	$ordered_code[3][$codeplusgroup] = $ordered_code[0][$codeplusgroup];
	$newfile0[0][0]= $filelist["csv"][$tabno];
	$newfile0[1][0]	=	$svrfld.$newfile0[0][0];
	$newfile0[0][1]= $filelist["php"][$tabno];
	$newfile0[1][1]	=	$svrfld.$newfile0[0][1];
	tabforbackword($tabno, count($tablelist), $bulsize, $tablecaption, $newfile0[0][0], $newfile0[0][1]);
	$tabno++;
	//$table1= ereg_replace("mtabolclick\(","mtabolclick(1",$click);

	#################outline overall heading start#############################
	$RCstr = "rPPh";
	$RCid = $RCstr;
	$code = "id=\"$RCid\"$click";
	$ctrall = "<font size=2 color=#ff0000><b>	<div $code>".$clkcap[$tabno-1]."</div></b></font><br>";
	echo $ctrall;
	//echo "div:$click:div<br>";
	//echo "<br>div:$click0:div<br>";
	
	#################outline overall heading end#############################
	$wids = 120;
	$widn = 30;
	$wide = 60;
	$widnt = $widn*($staffno);	
	$widt = $wids + $widnt + $wide;
	
	$filetitle = $newfile0[0][0];
	$newfile	=  $newfile0[1][0];
	$fpcsv	=	fopen($newfile,'w+');
	$filetitle = $newfile0[0][1];
	$newfile	=  $newfile0[1][1];
	$fphtml	=	fopen($newfile,'w+');
	
	## table heading: left cell
	$htmlstr = "<table $cell0 width=$widt>
		<tr><th width=$wids align=center><font size=$thfs>Project|Name</font></th>";	
	echo $htmlstr;
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,$head."$tablecaption\n$tailcsv\n");		
		fputs($fpcsv,"Project|Name");
		
		fputs($fphtml,"<ht"."ml><h1>$head$tablecaption</h1>$tailhtml$ctrall<br>");
		fputs($fphtml,$htmlstr);
	}
	## table heading: other cells
	for ($i=0; $i<=$staffno; $i++) {
		$tmp = $stafflist[0][$i];
		$popup = $stafflist[2][$i]." ".$stafflist[3][$i];
		$htmlstr = "<th width=$widn align=center>";
		if ($i == $staffno) {
			$htmlstr = "<th width=$wide align=center>";
		}
		$htmlstr = $htmlstr."<font size=$thfs>"
				."<a onMouseOver=\"self.status='$popup';\" onMouseOut=\"self.status='';\">"
				."$tmp</font></th>";
		echo $htmlstr;
		if ($fpcsv && $fphtml) {
			fputs($fpcsv,",".$tmp);
			fputs($fphtml,$htmlstr);
		}
	}
	$htmlstr = "</tr>";
	echo $htmlstr;
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,"\n");
		fputs($fphtml,$htmlstr);
	}
	
	//for ($i=0; $i<=7; $i++) {
	for ($i=0; $i<=$codeplusgroup; $i++) {
		$ip1= $i + 1;
		$RCid = $RCstr.$ip1;

		if ($ordered_code[2][$i] == "") {
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
		
		for ($j=0; $j<=$staffno; $j++) {
			$wid = $widn;
			if ($j==$staffno) {
				$wid = $wide;
			}
			$tmp = $minarray[$i][$wrt_ind][$j];
			if ($tmp) {
				$tmp = number_format($tmp/60, 2);
				$tmp = ereg_replace(",", "", $tmp);
				$htmlstr = "<td align=center width=$wid><font size=$tdfs>".$b1.$tmp.$b2."</font></td>";
			} else {
				$htmlstr = "<td align=center width=$wid><font size=$tdfs>&nbsp;</font></td>";
			}
			echo $htmlstr;
			if ($fpcsv && $fphtml) {			
				fputs($fpcsv,",$tmp");
				fputs($fphtml,$htmlstr);
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

############################################
##	Table 2: Project_People(%)- rPPp
############################################
	$tablecaption = $tablelist[$tabno];
	$stafflist[0][$staffno] = $heading["tr"][$tabno];
	$ordered_code[0][$codeplusgroup] = $heading["bl"][$tabno];
	$ordered_code[3][$codeplusgroup] = $ordered_code[0][$codeplusgroup];
	$newfile0[0][0]= $filelist["csv"][$tabno];
	$newfile0[1][0]	=	$svrfld.$newfile0[0][0];
	$newfile0[0][1]= $filelist["php"][$tabno];
	$newfile0[1][1]	=	$svrfld.$newfile0[0][1];
	tabforbackword($tabno, count($tablelist), $bulsize, $tablecaption, $newfile0[0][0], $newfile0[0][1]);
	$tabno++;
	$rlatotal = $minarray[$codeplusgroup][$wrt_ind][$staffno];

	#################outline overall heading start#############################
	$RCstr = "rPPp";
	$RCid = $RCstr;
	$code = "id=\"$RCid\"$click";
	$ctrall = "<font size=2 color=#ff0000><b><div $code>".$clkcap[$tabno-1]."</div></b></font><br>";
	echo $ctrall;
	#################outline overall heading end#############################
	$wids = 120;
	$widn = 30;
	$wide = 60;
	$widnt = $widn*($staffno);	
	$widt = $wids + $widnt + $wide;

	$filetitle = $newfile0[0][0];
	$newfile	=  $newfile0[1][0];
	$fpcsv	=	fopen($newfile,'w+');
	$filetitle = $newfile0[0][1];
	$newfile	=  $newfile0[1][1];
	$fphtml	=	fopen($newfile,'w+');
	
	## table heading: left cell
	$htmlstr = "<table $cell0 width=$widt>
		<tr><th width=$wids align=center><font size=$thfs>Project|Name</font></th>";	
	echo $htmlstr;
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,"$head$tablecaption\n$tailcsv\n");
		fputs($fpcsv,"Project|Name");
		fputs($fphtml,"<ht"."ml><h1>$head$tablecaption</h1>$tailhtml$ctrall<br>");
		fputs($fphtml,$htmlstr);
	}

	## table heading: other cells
	for ($i=0; $i<=$staffno; $i++) {
		$tmp = $stafflist[0][$i];
		$popup = $stafflist[2][$i]." ".$stafflist[3][$i];
		$htmlstr = "<th width=$widn align=center>";
		if ($i == $staffno) {
			$htmlstr = "<th width=$wide align=center>";
		}
		$htmlstr = $htmlstr."<font size=$thfs>"
				."<a onMouseOver=\"self.status='$popup';\" onMouseOut=\"self.status='';\">"
				."$tmp</font></th>";
		echo $htmlstr;
		if ($fpcsv && $fphtml) {
			fputs($fpcsv,",".$tmp);
			fputs($fphtml,$htmlstr);
		}
	}
	$htmlstr = "</tr>";
	echo $htmlstr;
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,"\n");
		fputs($fphtml,$htmlstr);
	}
		
	for ($i=0; $i<=$codeplusgroup; $i++) {
		$ip1= $i + 1;
		$RCid = $RCstr.$ip1;
		if ($ordered_code[2][$i] == "") {
			$disp = "";
			$code = "id=\"$RCid\"$click$disp";
			$tmp = $ordered_code[3][$i];
			$tmp = underscores($tmp);
			$b1 = "<font size=$tdfs color=#0000ff><b $code>";
			$b2 = "</font></b>";
			$htmlstr = "<tr><td width=$wids>".$b1.$tmp.$b2."</td>";
			echo $htmlstr;
			if ($fpcsv && $fphtml) {
				fputs($fphtml, $htmlstr);
				fputs($fpcsv,$tmp);
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
			
		for ($j=0; $j<$staffno; $j++) {
			$wid = $widn;
			if ($j==$staffno) {
				$wid = $wide;
			}
			$tmp = $minarray[$i][$wrt_ind][$j];
			if ($tmp) {
				$tmp = number_format(100*$tmp/$minarray[$codeplusgroup][$wrt_ind][$j], 2);
				$tmp = ereg_replace(",", "", $tmp);
				$htmlstr = "<td align=center width=$wid><font size=$tdfs>".$b1.$tmp.$b2."</font></td>";
			} else {
				$htmlstr = "<td align=center width=$wid><font size=$tdfs>&nbsp;</font></td>";
			}
			echo $htmlstr;
			if ($fpcsv && $fphtml) {
				fputs($fphtml,$htmlstr);
				fputs($fpcsv,",$tmp");
			}
		}
		
		$j = $staffno;
		$tmp = $minarray[$i][$wrt_ind][$j];
		if ($tmp) {
			$tmp = number_format(100*$tmp/$rlatotal, 2);
			$tmp = ereg_replace(",", "", $tmp);
			$htmlstr = "<td align=center><font size=$tdfs>".$b1.$tmp.$b2."</font></td></tr>";
		} else {
			$htmlstr = "<td align=center><font size=$tdfs>&nbsp;</font></td>";
		}
		echo $htmlstr;
		if ($fpcsv && $fphtml) {
			fputs($fpcsv,",$tmp\n");
			fputs($fphtml,$htmlstr);
		}
	}
	$htmlstr = "</table><p>";
	echo $htmlstr;
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,"\n\n");
		fclose($fpcsv);
		fputs($fphtml,$htmlstr."</ht"."ml>");
		fclose($fphtml);
	}

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
	$widnt = $widn*($wkend - $wkstr);	
	$widt = $wids + $widnt + $wide;

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
		fputs($fpcsv,"$head$tablecaption\n$tailcsv\nProject|Week");
		fputs($fphtml,"<ht"."ml><h1>$head$tablecaption</h1>$tailhtml$ctrall<br>");
		fputs($fphtml,$htmlstr);
	}
	
	## table heading: other cells
	for ($i=0; $i<$nowks; $i++) {
		$htmlstr = "<th width=$widn><font size=$thfs>$wklist[$i]</font></th>";
		echo $htmlstr;
		if ($fpcsv && $fphtml) {
			fputs($fpcsv,",$wklist[$i]");
			fputs($fphtml,$htmlstr);
		}
	}

	## table heading: right cell
	$htmlstr = "<th width=$wide><font size=$thfs>$righthead</font></th></tr>";
	echo $htmlstr;
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,",$righthead\n");
		fputs($fphtml,$htmlstr);
	}
	
	for ($i=0; $i<$codeplusgroup; $i++) {
		### project name
		$ip1= $i + 1;
		$RCid = $RCstr.$ip1;
		if ($ordered_code[2][$i] == "") {
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
		
		### project weekly sub total
		for ($j=$wkstr; $j<=$wkend; $j++) {
			$tmp = $minarray[$i][$j][$staffno];
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
		### project monthly sub total
		$tmp = $minarray[$i][$wrt_ind][$staffno];
		if ($tmp) {
			$tmp = number_format($tmp/60, 2);
			$tmp = ereg_replace(",","",$tmp);
			$htmlstr = "<td align=center width=$wide><font size=$tdfs>$b1$tmp$b2</font></td></tr>";
		} else {
			$htmlstr = "<td align=center width=$wide><font size=$tdfs>&nbsp;</font></td>";
		}
		echo $htmlstr;
		if ($fpcsv && $fphtml) {
			fputs($fphtml,$htmlstr);
			fputs($fpcsv,",$tmp\n");
		}
	} 
	
	### weekly total
	$htmlstr = "<tr><td><font size=$thfs width=$wids><b>$endhead</b></font></td>";
	echo $htmlstr;
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,"$endhead");
		fputs($fphtml,$htmlstr);
	}
	for ($j=$wkstr; $j<=$wkend; $j++) {
		$tmp = $minarray[$codeplusgroup][$j][$staffno];
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
	
	### monthly total
	$tmp = $minarray[$codeplusgroup][$wrt_ind][$staffno];
	$tmp = number_format($tmp/60, 2);
	$tmp = ereg_replace(",","",$tmp);
	$htmlstr = "<td align=center width=$wide><font size=$thfs color=#ff0000>
		<b>$tmp</b></font></td></tr></table><p>"; 
	echo $htmlstr;
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,",$tmp\n\n");
		fclose($fpcsv);
		fputs($fphtml,$htmlstr);
		fputs($fphtml,"</ht"."ml>");
		fclose($fphtml);	
	}

############################################
##	Table 4: People_Weeks - cPeW
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

	$filetitle = $newfile0[0][0];
	$newfile	=  $newfile0[1][0];
	$fpcsv	=	fopen($newfile,'w+');
	$filetitle = $newfile0[0][1];
	$newfile	=  $newfile0[1][1];
	$fphtml	=	fopen($newfile,'w+');
	
## step 1 write CSV file
	## table heading: left cell
	if ($fpcsv) {
		fputs($fpcsv,"$head$tablecaption\n$tailcsv\nName");
	}
	
	for ($i=0; $i<$nowks; $i++) {
		if ($fpcsv) {
			fputs($fpcsv,",$wklist[$i]");
		}
	}
	
	if ($fpcsv) {
		fputs($fpcsv,",$righthead\n");
	}
	
	for ($i=0; $i<$staffno; $i++) {
		### staff name
		//$tmp = $stafflist[0][$i];
		$tmp = $stafflist[2][$i]." ".$stafflist[3][$i];
		if ($fpcsv && $fphtml) {
			fputs($fpcsv,"$tmp");
		}
		
		### staff weekly sub total
		for ($j=$wkstr; $j<=$wkend ; $j++) {
			$tmp = $minarray[$codeplusgroup][$j][$i];
			if ($tmp) {
				$tmp = number_format($tmp/60, 2);
				$tmp = ereg_replace(",","",$tmp);
			}
			if ($fpcsv) {
				fputs($fpcsv,",$tmp");
			}
		}

		### staff monthly
		$tmp = $minarray[$codeplusgroup][$wrt_ind][$i];
		if ($tmp) {
			$tmp = number_format($tmp/60, 2);
			$tmp = ereg_replace(",","",$tmp);
		}
		if ($fpcsv) {
			fputs($fpcsv,",$tmp\n");
		}
	}
	
	### weekly sub total
	if ($fpcsv) {
		fputs($fpcsv,"$endhead");
	}
	for ($j=$wkstr; $j<=$wkend ; $j++) {
		$tmp = $minarray[$codeplusgroup][$j][$staffno];
		if ($tmp) {
			$tmp = number_format($tmp/60, 2);
			$tmp = ereg_replace(",","",$tmp);
		}
		if ($fpcsv) {
			fputs($fpcsv,",$tmp");
		}
	}
	
	### monthly sub total
	$tmp = $minarray[$codeplusgroup][$wrt_ind][$staffno];
	$tmp = number_format($tmp/60, 2);
	$tmp = ereg_replace(",","",$tmp);
	if ($fpcsvl) {
		fputs($fpcsv,",$tmp\n\n");
		fclose($fpcsv);
	}

## Step 2 write html
	##############################################################################
	##	id is given in first row in the format of
	##	<td id=#id>
	##	table is write in the column, each column has one table 
	##	($staffno rows x one column)
	##############################################################################
	#################outline overall heading start#############################
	$RCstr = "cPeW";
	$RCid = $RCstr;
	$code = "id=\"$RCid\"$click";
	$ctrall = "<font size=2 color=#ff0000><b>	<div $code>".$clkcap[$tabno-1]."</div></b></font>";
	$ctrall = "<form name=\"showpd\"><table border=0><tr><td>$ctrall</td>";
	$ctrall = $ctrall."<td><select name=\"period\">";
	$ctrall = $ctrall."<option value=\"m\" selected>Monthly";
	$ctrall = $ctrall."<option value=\"w\">Weekly";
	$ctrall = $ctrall."</option></select>";
	$ctrall = $ctrall."<th><font size=2>Data Only.</font></th>";
	$ctrall = $ctrall."</tr></table></form>";
	echo $ctrall;
	//$ctrall = "";
	#################outline overall heading end#############################
	
	## determine which columns are shown at beginning
	for ($i=0; $i<$timeseries ; $i++) {	
		$ptype = $yhyqmw[$i][0];
		$tsdate = $wklist[$yhyqmw[$i][1]];
		$year0 = rlagetyear($tsdate);
		$month0 = rlagetmonth($tsdate);
		$day0 = rlagetday($tsdate);
		$showall = "n";
		if ($ptype == "y") {
			if ($showall == "y"){
				$dispn[$i] = "";
			} else {
				$dispn[$i] = $hide;
			}
			$tmp = $year0 + 1;
			$caption[$i] = "$year0/$tmp";
			$color[$i] = "color=#ff0000";
		} elseif ($ptype == "hy") {
			if ($showall == "y"){
				$dispn[$i] = "";
			} else {
				$dispn[$i] = $hide;
			}
			$caption[$i] = $year0.": ".$hylist[$month0];
			$color[$i] = "color=#0000ff";
		} elseif ($ptype == "q") {
			if ($showall == "y"){
				$dispn[$i] = "";
			} else {
				$dispn[$i] = $hide;
			}
			$caption[$i] = $qtrlist[$month0]."-Qtr, ".$year0;
			$color[$i] = "color=#ff00ff";
		} elseif ($ptype == "m") {
			$dispn[$i] = "";
			$caption[$i] = $mths[$month0].", ".$year0;
			$color[$i] = "color=#007c8e";
		} elseif ($ptype == "w") {
			$dispn[$i] = $hide;
			$caption[$i] = $tsdate;
		}
	}

	$setwh = 1;
	$wids = 150;
	$widn = 80;
	$widnt = 0;//$widn*$timeseries;	
	$widt = 0;//$wids + $widnt;
	$cellh = 8;
	$tabh = 0;//$cellh*($staffno + l);
	
	if ($setwh == 0) {
		$wids_str = "";
		$widn_str = "";
		$widnt_str = "";	
		$widt_str = "";
		$cellh_str = "";
		$tabh_str = "";
	} else {
		$wids_str = "width=$wids";
		$widn_str = "width=$widn";
		$widnt_str = "width=$widnt";	
		$widt_str = "width=$widt";
		$cellh_str = "height=$cellh";
		$tabh_str = "height=$tabh";
	}
	
	if ($fphtml) {
		fputs($fphtml,"<ht"."ml><h1>$head$tablecaption</h1>$tailhtml$ctrall<br>");
	}
	## define table
	$htmlstr = "<table $cell0 $widt_str $tabh_str>";
	writehtml($fphtml,$htmlstr);
	
## first row:  Table heading
	$bgch = "bgcolor=\"#f2eece\"";
	$htmlstr = "<tr><th align=middle $wids_str $cellh_str $bgch>
			<font size=$thfs>People|Week</font></th>";	
	for ($i=0; $i<$timeseries; $i++) {	
		$ip1 = $i + 1;
		$RCid = $RCstr.$ip1;
		if ($yhyqmw[$i][0] != "w") {
			$codeclick = "id=\"$RCid\"".$click;
		} else {
			$codeclick = "id=\"$RCid\"";
		}
		$RCid = $RCstr."a".$ip1;
		$codedisp = "id=\"$RCid\"".$dispn[$i];
		$htmlstr = $htmlstr."<th $codedisp align=middle $widn_str $cellh_str $bgch>
			<font $color[$i] size=$thfs $codeclick>$caption[$i]</font></th>";
	}
	$htmlstr = $htmlstr."</tr>";
	writehtml($fphtml,$htmlstr);
	
## second row:  Table body, staff name
	$cell0 = " border=0 cellspacing=\"0\" cellpadding=\"0\"";
	$htmlstr = "<tr><td $wids_str $cellh_str><table $cell0 $tabh_str>";
	writehtml($fphtml,$htmlstr);
	$htmlstr = "";
	$b1 = "<b>";
	$b2 = "</b>";
	for ($i=0; $i<$staffno; $i++) {
		$j = (int)($i/2);
		if (2*$j == $i) {
			$bgc[$i] = "bgcolor=\"#fed9f6\"";
		} else {
			$bgc[$i] = "bgcolor=\"#aef5fd\"";
		}
		$tmp = $stafflist[2][$i]." ".$stafflist[3][$i];
		$htmlstr = $htmlstr."<tr><td $wids_str $cellh_str $bgc[$i]>
			<font size=$tdfs>$b1$tmp$b1</font></td></tr>";
	}
	$htmlstr = $htmlstr."</table></td>";
	writehtml($fphtml,$htmlstr);

## second row:  Table body, main data body
	for ($i=0; $i<$timeseries; $i++) {	
		$htmlstr = "";
		$ip1 = $i + 1;
		$RCid = $RCstr."b".$ip1;
		$codedisp = "id=\"$RCid\"".$dispn[$i];
		$htmlstr = $htmlstr."<td $codedisp $widn_str $cellh_str><table $cell0 $tabh_str>";

		for ($j=0; $j<$staffno; $j++) {		
			$tmp = $minarray[$codeplusgroup][$i][$j];
			if ($tmp) {
				$tmp = number_format($tmp/60, 2);
				$tmp = ereg_replace(",","",$tmp);
			} else {
				$tmp = "&nbsp";
			}
			$htmlstr = $htmlstr."<tr><td align=center $widn_str $cellh_str $bgc[$j]>
				<font $color[$i] size=$tdfs>$tmp</font></td></tr>";
		}
		
		$htmlstr = $htmlstr."</table></td>";
		writehtml($fphtml,$htmlstr);
	}
	$htmlstr = "</tr>";
	writehtml($fphtml,$htmlstr);

## third row:  Summary
	$htmlstr = $htmlstr."<tr><td align=\"left\" $wids_str $cellh_str $bgch>
		<font size=$thfs><b>$endhead</b></font></td>";
	writehtml($fphtml,$htmlstr);
	$htmlstr = "";
	for ($i=0; $i<$timeseries; $i++) {	
		$ip1 = $i + 1;
		$RCid = $RCstr."c".$ip1;
		$codedisp = "id=\"$RCid\"".$dispn[$i];
		$tmp = $minarray[$codeplusgroup][$i][$staffno];
		if ($tmp) {
			$tmp = number_format($tmp/60, 2);
			$tmp = ereg_replace(",","",$tmp);
		} else {
			$tmp = "&nbsp";
		}
		//echo "$i: $tmp  $codedisp<br>";
		$htmlstr = $htmlstr."<td $codedisp $widn_str $tabh_str align=center $bgch>"
			."<font $color[$i] size=$thfs><b>$tmp</b></font></td>";
	}

	$htmlstr = $htmlstr."</tr></table>";
	writehtml($fphtml,$htmlstr);
	if ($fphtml) {
		fclose($fphtml);
	}
	
?>
</html>

