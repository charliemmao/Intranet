<html>
<?php

############################################
##	Monthly Summary: Table Index
############################################	
	$tabno = 0;
	$tablelist[$tabno] = "Project vs People ($tmp-Hour)";
	$heading["tr"][$tabno] = "Project Sub Total (hrs)";
	$heading["bl"][$tabno] = "People Sub Total (hrs)";
	$filelist["csv"][$tabno] = $filetitle0."_Project_People(hours).csv";
	$filelist["html"][$tabno] = $filetitle0."_Project_People(hours).html";
	$tabno++;
	
	/*
	$tablelist[$tabno] = "Project vs People ($tmp-%)";
	$heading["tr"][$tabno] = "Project Total (%)";
	$heading["bl"][$tabno] = "People (%)";
	$filelist["csv"][$tabno] = $filetitle0."_Project_People(percent).csv";
	$filelist["html"][$tabno] = $filetitle0."_Project_People(percent).html";
	$tabno++;
	*/
	
	$tablelist[$tabno] = "Project vs Weeks ($tmp-Hour)";
	$heading["tr"][$tabno] = "Sub Total (Project)";
	$heading["bl"][$tabno] = "Sub Total (RLA)";
	$filelist["csv"][$tabno] = $filetitle0."_Project_Weeks(hours).csv";
	$filelist["html"][$tabno] = $filetitle0."_Project_Weeks(hours).html";
	$tabno++;
	
	$tablelist[$tabno] = "People vs Weeks ($tmp-Hour)";
	$heading["tr"][$tabno] = "Sub Total (Staff)";
	$heading["bl"][$tabno] = "Sub Total (RLA)";	
	$filelist["csv"][$tabno] = $filetitle0."_People_Weeks(hours).csv";
	$filelist["html"][$tabno] = $filetitle0."_People_Weeks(hours).html";
	$tabno++;
	
	if ($priv == "00"){
		echo "<hr>";
		flush();
	} else {
		echo "<br>";
	}
	echo "<a id=tabind><font size=3><b>Table Index</b></font></a>";
	
	$indexfile = $filetitle0."_index.html";
	echo "&nbsp;<font size=\"$bulsize\">(Open this [".
		"<a href=\"../report/$indexfile\" target=\"_blank\"><b>INDEX</b></a>] file.)</font><br><br>";
	$indexfile = $svrfld.$indexfile;
	$fp	=	fopen($indexfile,'w+');
	if ($fp) {
		fputs($fp,"<ht"."ml><h1>Monthly Summary</h1>$tailhtml<ul>");
	}
	
	echo "<ul>";
	for ($i=0; $i<count($tablelist); $i++) {
		$j = $i + 1;
		echo "<li><font size=2><a href=#$i>Table $j: $tablelist[$i]</a></font></li>";
		if ($fp) {
			$csvfile = $filelist["csv"][$i];
			$htmlfile = $filelist["html"][$i];
			fputs($fp,"<li><font size=\"$bulsize\">Download or open a [".
				"<a href=\"../report/$csvfile\" target=\"_blank\"><b>CSV</b></a>] or a [".
				"<a href=\"../report/$htmlfile\" target=\"_blank\"><b>HTML</b></a>]".
				" format file for table $j: $tablelist[$i]</a></font></li>");
		}
	}
	echo "</ul>";
	if ($fp) {
		fputs($fp,"<ul><br></ht"."ml>");
		fclose($fp);
	}

############################################
##	Table 1: Project_People(hours)
############################################
	$tabno = 0;
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

	$filetitle = $newfile0[0][0];
	$newfile	=  $newfile0[1][0];
	$fpcsv	=	fopen($newfile,'w+');
	$filetitle = $newfile0[0][1];
	$newfile	=  $newfile0[1][1];
	$fphtml	=	fopen($newfile,'w+');
	
	## table heading
	$wid = "";
	$htmlstr = "<table border=$tabborder cellspacing=\"0\" cellpadding=\"0\">
		<tr><th width=$wid align=center><font size=$thfs>Project|Name</font></th>";	
	echo $htmlstr;
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,$head."$tablecaption\n$tailcsv\n");		
		fputs($fpcsv,"Project|Name");
		
		fputs($fphtml,"<ht"."ml><h1>$head$tablecaption</h1>$tailhtml<br>");
		fputs($fphtml,$htmlstr);
	}
	
	## staff list
	for ($i=0; $i<=$staffno; $i++) {
		if ($minarray[$codeplusgroup][$wrt_ind][$i]) {
			$write0[$i] = 1; 
			$tmp = $stafflist[0][$i];
			$popup = $stafflist[2][$i]." ".$stafflist[3][$i];
			$htmlstr = "<th><font size=$thfs>"
				."<a onMouseOver=\"self.status='$popup';\" onMouseOut=\"self.status='';\">"
				."$tmp</font></th>";
			echo $htmlstr;
			if ($fpcsv && $fphtml) {
				fputs($fpcsv,",".$tmp);
				fputs($fphtml,$htmlstr);
			}
		}
	}
	echo "</tr>";
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,"\n");
		fputs($fphtml,"</tr>");
	}	
	
	for ($i=0; $i<=$codeplusgroup; $i++) {
		if ($ordered_code[2][$i] == "") {
			$tmp = $ordered_code[3][$i];
			$tmp = underscores($tmp);
			$b1 = "<font size=$tdfs color=#0000ff><b>";
			$b2 = "</font></b>";
			$htmlstr = "<tr><td>".$b1.$tmp.$b2."</td>";
			echo $htmlstr;
			if ($fpcsv && $fphtml) {
				fputs($fpcsv,$tmp);
				fputs($fphtml,$htmlstr);
			}
		} else {	
			$tmp = $ordered_code[0][$i];
			$tmp = underscores($tmp);
			$b1 = "";
			$b2 = "";
			$popup = $ordered_code[3][$i];
			$popup = ereg_replace("'", "", $popup);
			$popup = ereg_replace('"', "", $popup);
			$htmlstr = "<tr><td><font size=$tdfs>"
				."<a onMouseOver=\"self.status='$popup';\" onMouseOut=\"self.status='';\">"
				."&nbsp;&nbsp;$tmp</a></font></td>";
			echo $htmlstr;
			if ($fpcsv && $fphtml) {
				fputs($fpcsv,"  ".$tmp);
				fputs($fphtml,$htmlstr);
			}
		}
		for ($j=0; $j<=$staffno; $j++) {
		if ($write0[$j] == 1) {
			$tmp = $minarray[$i][$wrt_ind][$j];
			if ($tmp) {
				$tmp = number_format($tmp/60, 2);
				$tmp = ereg_replace(",", "", $tmp);
				$htmlstr = "<td align=center><font size=$tdfs>".$b1.$tmp.$b2."</font></td>";
			} else {
				$htmlstr = "<td align=center><font size=$tdfs>&nbsp;</font></td>";
			}
			echo $htmlstr;
			if ($fpcsv && $fphtml) {			
				fputs($fpcsv,",$tmp");
				fputs($fphtml,$htmlstr);
			}
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

/*
############################################
##	Table 2: Project_People(%)
############################################
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
		<tr><th width=$wid align=center><font size=$thfs>Project|Name</font></th>";	
	echo $htmlstr;
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,"$head$tablecaption\n$tailcsv\n");		
		fputs($fpcsv,"Project|Name");
		fputs($fphtml,"<ht"."ml><h1>$head$tablecaption</h1>$tailhtml<br>");
		fputs($fphtml,$htmlstr);
	}
	
	for ($i=0; $i<=$staffno; $i++) {
		if ($minarray[$codeplusgroup][$wrt_ind][$i]) {
			$write0[$i] = 1; 
			$tmp = $stafflist[0][$i];
			$popup = $stafflist[2][$i]." ".$stafflist[3][$i];
			$htmlstr = "<th><font size=$thfs>"
				."<a onMouseOver=\"self.status='$popup';\" onMouseOut=\"self.status='';\">"
				."$tmp</font></th>";
			echo $htmlstr;
			if ($fpcsv && $fphtml) {
				fputs($fpcsv,",".$tmp);
				fputs($fphtml,$htmlstr);
			}
		}
	}
	echo "</tr>";
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,"\n");
		fputs($fphtml,"</tr>");
	}
		
	for ($i=0; $i<=$codeplusgroup; $i++) {
		if ($ordered_code[2][$i] == "") {
			$tmp = $ordered_code[3][$i];
			$tmp = underscores($tmp);
			$b1 = "<font size=$tdfs color=#0000ff><b>";
			$b2 = "</font></b>";
			$htmlstr = "<tr><td>".$b1.$tmp.$b2."</td>";
			echo $htmlstr;
			if ($fpcsv && $fphtml) {
				fputs($fphtml, $htmlstr);
				fputs($fpcsv,$tmp);
			}
		} else {
			$tmp = $ordered_code[0][$i];
			$tmp = underscores($tmp);
			$b1 = "";
			$b2 = "";
			$popup = $ordered_code[3][$i];
			$popup = ereg_replace("'", "", $popup);
			$popup = ereg_replace('"', "", $popup);
			$htmlstr = "<tr><td><font size=$tdfs>"
				."<a onMouseOver=\"self.status='$popup';\" onMouseOut=\"self.status='';\">"
				."&nbsp;&nbsp;$tmp</a></font></td>";
			echo $htmlstr;
			if ($fpcsv && $fphtml) {
				fputs($fpcsv,"  ".$tmp);
				fputs($fphtml,$htmlstr);
			}
		}
			
		for ($j=0; $j<$staffno; $j++) {
			if ($write0[$i] == 1) {
				$tmp = $minarray[$i][$wrt_ind][$j];
				if ($tmp) {
					$tmp = number_format(100*$tmp/$minarray[$codeplusgroup][$wrt_ind][$j], 2);
					$tmp = ereg_replace(",", "", $tmp);
					$htmlstr = "<td align=center><font size=$tdfs>".$b1.$tmp.$b2."</font></td>";
				} else {
					$htmlstr = "<td align=center><font size=$tdfs>&nbsp;</font></td>";
				}
				echo $htmlstr;
				if ($fpcsv && $fphtml) {
					fputs($fphtml,$htmlstr);
					fputs($fpcsv,",$tmp");
				}
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
	echo "</table><p>";
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,"\n\n");
		fclose($fpcsv);
		fputs($fphtml,"</table><p>");
		fputs($fphtml,"</ht"."ml>");
		fclose($fphtml);
	}
//*/

############################################
##	Table 3: Project_Weeks
############################################
	$tablecaption = $tablelist[$tabno];
	$stafflist[0][$staffno] = $heading["tr"][$tabno];
	$ordered_code[0][$codeplusgroup] = $heading["bl"][$tabno];
	$ordered_code[3][$codeplusgroup] = $ordered_code[0][$codeplusgroup];
	$righthead = $heading["tr"][$tabno];
	$endhead = $heading["bl"][$tabno];
	$newfile0[0][0]= $filelist["csv"][$tabno];
	$newfile0[1][0]	=	$svrfld.$newfile0[0][0];
	$newfile0[0][1]= $filelist["html"][$tabno];
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
	
	$htmlstr = "<table border=$tabborder cellspacing=\"0\" cellpadding=\"0\">
			<tr><th><font size=$thfs>PROJECT CODE</font></th>";
	echo $htmlstr;
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,"$head$tablecaption\n$tailcsv\nProject");
		fputs($fphtml,"<ht"."ml><h1>$head$tablecaption</h1>$tailhtml<br>");
		fputs($fphtml,$htmlstr);
	}
	
	for ($i=0; $i<$nowks; $i++) {
		$htmlstr = "<th><font size=$thfs>$wklist[$i]</font></th>";
		echo $htmlstr;
		if ($fpcsv && $fphtml) {
			fputs($fpcsv,",$wklist[$i]");
			fputs($fphtml,$htmlstr);
		}
	}

	$htmlstr = "<th><font size=$thfs>$righthead</font></th></tr>";
	echo $htmlstr;
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,",$righthead\n");
			fputs($fphtml,$htmlstr);
	}
	
	$projlist = "Project List:\n";
	$projlisthtml="<table><tr><th colspan=2 align=left>Project List</td><tr>";

	for ($i=0; $i<$codeplusgroup; $i++) {
		### project name
		if ($ordered_code[2][$i] == "") {
			$tmp = $ordered_code[3][$i];
			$tmp = underscores($tmp);
			$b1 = "<font size=$tdfs color=#0000ff><b>";
			$b2 = "</font></b>";
			$htmlstr = "<tr><td>$b1.$tmp.$b2</td>";
			echo $htmlstr;
			if ($fpcsv && $fphtml) {
				fputs($fphtml,$htmlstr);
				fputs($fpcsv,"$tmp");
			}
		} else {
			$tmp = $ordered_code[0][$i];
			$tmp = underscores($tmp);
			$ip1 = $i+1;
			$projlistcvs = $projlistcvs."    $ip1: ".$tmp."\n";
			$projlisthtml = $projlisthtml."<tr><td>$ip1:&nbsp;</td><td>$tmp</td></tr>";
			$b1 = "";
			$b2 = "";
			$popup = $ordered_code[3][$i];
			$popup = ereg_replace("'", "", $popup);
			$popup = ereg_replace('"', "", $popup);
			$htmlstr = "<tr><td><font size=$tdfs>"
					."<a onMouseOver=\"self.status='$popup';\" onMouseOut=\"self.status='';\">"
					."&nbsp;&nbsp;$tmp</a></font></td>";
			echo $htmlstr;
			if ($fpcsv && $fphtml) {
				fputs($fpcsv,"    $tmp");
				fputs($fphtml,$htmlstr);
			}
		}
		
		### project weekly sub total
		for ($j=$wkstr; $j<=$wkend ; $j++) {
			$tmp = $minarray[$i][$j][$staffno];
			if ($tmp) {
				$tmp = number_format($tmp/60, 2);
				$tmp = ereg_replace(",","",$tmp);
				$htmlstr = "<td align=center><font size=$tdfs>$b1$tmp$b2</font></td>";
			} else {
				$htmlstr = "<td align=center><font size=$tdfs>&nbsp;</font></td>";
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
			$htmlstr = "<td align=center><font size=$tdfs>$b1$tmp$b2</font></td></tr>";
		} else {
			$htmlstr = "<td align=center><font size=$tdfs>&nbsp;</font></td>";
		}
		echo $htmlstr;
		if ($fpcsv && $fphtml) {
			fputs($fphtml,$htmlstr);
			fputs($fpcsv,",$tmp\n");
		}
	} 
	
	### weekly total
	$htmlstr = "<tr><td><font size=$thfs><b>$endhead</b></font></td>";
	echo $htmlstr;
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,"$endhead");
		fputs($fphtml,$htmlstr);
	}
	for ($j=$wkstr; $j<=$wkend ; $j++) {
		$tmp = $minarray[$codeplusgroup][$j][$staffno];
		if ($tmp) {
			$tmp = number_format($tmp/60, 2);
			$tmp = ereg_replace(",","",$tmp);
			$htmlstr = "<td align=center><font size=$thfs><b>$tmp</b></font></td>";
		} else {
			$htmlstr = "<td align=center><font size=$tdfs>&nbsp;</font></td>";
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
	$htmlstr = "<td align=center><font size=$thfs color=#ff0000>
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
##	Table 4: People_Weeks
############################################
	$tablecaption = $tablelist[$tabno];
	$stafflist[0][$staffno] = $heading["tr"][$tabno];
	$ordered_code[0][$codeplusgroup] = $heading["bl"][$tabno];
	$ordered_code[3][$codeplusgroup] = $ordered_code[0][$codeplusgroup];
	$righthead = $heading["tr"][$tabno];
	$endhead = $heading["bl"][$tabno];
	$newfile0[0][0]= $filelist["csv"][$tabno];
	$newfile0[1][0]	=	$svrfld.$newfile0[0][0];
	$newfile0[0][1]= $filelist["html"][$tabno];
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
	
	$htmlstr = "<table border=$tabborder cellspacing=\"0\" cellpadding=\"0\">
		<tr><th width=$wid align=center><font size=$thfs>Name</font></th>";	
	echo $htmlstr;
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,"$head$tablecaption\n$tailcsv\n$projlistcvs\nName");
		$projlisthtml = $projlisthtml."</table>";
		fputs($fphtml,"<ht"."ml><h1>$head$tablecaption</h1>$tailhtml<br>$projlisthtml<br>");
		fputs($fphtml,$htmlstr);
	}
	
	for ($i=0; $i<$nowks; $i++) {
		$htmlstr = "<th><font size=$thfs>$wklist[$i]</font></th>";
		echo $htmlstr;
		if ($fpcsv && $fphtml) {
			fputs($fpcsv,",$wklist[$i]");
			fputs($fphtml,$htmlstr);
		}
	}
	
	$htmlstr = "<th><font size=$thfs>$righthead</font></th></tr>";
	echo $htmlstr;
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,",$righthead\n");
		fputs($fphtml,$htmlstr);
	}
	
	for ($i=0; $i<$staffno; $i++) {
	if ($minarray[$codeplusgroup][$wrt_ind][$i]) {
		### staff name
		//$tmp = $stafflist[0][$i];
		$tmp = $stafflist[2][$i]." ".$stafflist[3][$i];
		$htmlstr = "<tr><td><font size=$tdfs><b>$tmp<b></font></td>";
		echo $htmlstr;
		if ($fpcsv && $fphtml) {
			fputs($fphtml,$htmlstr);
			fputs($fpcsv,"$tmp");
		}
		
		### staff weekly sub total
		for ($j=$wkstr; $j<=$wkend ; $j++) {
			$tmp = $minarray[$codeplusgroup][$j][$i];
			if ($tmp) {
				$tmp = number_format($tmp/60, 2);
				$tmp = ereg_replace(",","",$tmp);
				$htmlstr = "<td align=center><font size=$tdfs>$tmp</font></td>";
			} else {
				$htmlstr = "<td align=center><font size=$tdfs>&nbsp;</font></td>";
			}
			echo $htmlstr;
			if ($fpcsv && $fphtml) {
				fputs($fpcsv,",$tmp");
				fputs($fphtml,$htmlstr);
			}
		}

		### staff monthly
		$tmp = $minarray[$codeplusgroup][$wrt_ind][$i];
		if ($tmp) {
			$tmp = number_format($tmp/60, 2);
			$tmp = ereg_replace(",","",$tmp);
			$htmlstr = "<td align=center><font size=$tdfs><b>$tmp</b></font></td></tr>";
		} else {
			$htmlstr = "<td align=center><font size=$tdfs>&nbsp;</font></td></tr>";
		}
		echo $htmlstr;
		if ($fpcsv && $fphtml) {
			fputs($fpcsv,",$tmp\n");
			fputs($fphtml,$htmlstr);
		}
	}
	}
	
	### weekly sub total
	$htmlstr = "<tr><td><font size=$thfs><b>$endhead</b></font></td>";
	echo $htmlstr;
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,"$endhead");
		fputs($fphtml,$htmlstr);
	}
	for ($j=$wkstr; $j<=$wkend ; $j++) {
		$tmp = $minarray[$codeplusgroup][$j][$staffno];
		if ($tmp) {
			$tmp = number_format($tmp/60, 2);
			$tmp = ereg_replace(",","",$tmp);
			$htmlstr = "<td align=center><font size=$thfs><b>$tmp</b></font></td>";
		} else {
			$htmlstr = "<td align=center><font size=$thfs>&nbsp;</font></td>";
		}
		echo $htmlstr;
		if ($fpcsv && $fphtml) {
			fputs($fpcsv,",$tmp");
			fputs($fphtml,$htmlstr);
		}
	}
	
	### monthly sub total
	$tmp = $minarray[$codeplusgroup][$wrt_ind][$staffno];
	$tmp = number_format($tmp/60, 2);
	$tmp = ereg_replace(",","",$tmp);
	$htmlstr = "<td align=center><font size=$thfs color=#ff0000>
		<b>$tmp</b></font></td></tr></table><p>";
	echo $htmlstr;
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,",$tmp\n\n");
		fclose($fpcsv);
		fputs($fphtml,$htmlstr);
		fputs($fphtml,"</ht"."ml>");
		fclose($fphtml);
	}
	
###################################################
	$ti = $tabno - 1;
	echo "<font size=\"$bulsize\">To&nbsp;
		<a href=#tabind>[Table Index]</a>&nbsp;
		<a href=#$ti>[Top of this Table]</a>&nbsp;";
	echo "</font>";
?>
</html>

