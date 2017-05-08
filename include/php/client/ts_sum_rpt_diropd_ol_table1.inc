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
#	step one: write table head
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

	#################outline overall heading start#############################
	$RCstr = "rPPh";
	$RCid = $RCstr;
	$code = "id=\"$RCid\"$click";
	$ctrall = "<font size=2 color=#ff0000><b>	<div $code>".$clkcap[$tabno-1]."</div></b></font><br>";
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
	
	//echo "<br>Intable 1<br>rpttypeS =$rpttypeS <br>";
	//echo "wrt_ind=$wrt_ind<br>";
	//exit;	
	## table heading: left cell
	$htmlstr = "<table $cell0 width=$widt>
		<tr><th width=$wids align=center><font size=$thfs>Project|Name</font></th>";	
	echo $htmlstr;
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,$head."$tablecaption\n$tailcsv\n");	
		//echo "head=$head; tablecaption=$tablecaption<br>tailcsv=$tailcsv";
		//exit;	
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
			##  MthSum "QtrSum"	"HfySum"	"YlySum"	"SlpSum" 	$rpttypeS
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
/*
		$wrt_ind = 1;
		for ($j=0; $j<=$staffno; $j++) {
			##  MthSum "QtrSum"	"HfySum"	"YlySum"	"SlpSum" 	$rpttypeS
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
//*/
		flush();
	}
	echo "</table><p>";
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,"\n\n");
		fclose($fpcsv);
		fputs($fphtml,"</table><p>");
		fputs($fphtml,"</ht"."ml>");
		fclose($fphtml);
	}
?>
</html>

