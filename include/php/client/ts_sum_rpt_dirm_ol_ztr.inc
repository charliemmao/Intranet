<html>
<?php
$js = "outline_mjs_tr.inc";
include("$js");
echo $frmstr;

$headstr = '<he'.'ad>'
	.'<me'.'ta ht'.'tp-equi'.'v="Con'.'tent-Ty'.'pe" con'.'tent="te'.'xt/ht'.'ml; char'.'set=win'.'dows-12'.'52">'
	.'<met'.'a nam'.'e="GENERA'.'TOR" cont'.'ent="Micr'.'osoft Fro'.'ntPage '.'4.0">'
	.'<met'.'a nam'.'e="Pro'.'gId" con'.'tent="Fro'.'ntPage.Edi'.'tor.Doc'.'ument">'
	.'</he'.'ad>';

############################################
##	Monthly Summary: Table Index
############################################	
	$tailhtml = "<font size=2>(".date("l, F j Y").")</font><br><br>";
	$tailhtml = $tailhtml.'<?php include("'.$js.'"); ?>'.$frmstr;

	$tabno = 0;
	$tablelist[$tabno] = "Project vs People ($tmp-Hour)";
	$heading["tr"][$tabno] = "Project SubTotal (hrs)";
	$heading["bl"][$tabno] = "People SubTotal (hrs)";
	$filelist["csv"][$tabno] = $filetitle0."_Project_People(hours).csv";
	$filelist["php"][$tabno] = $filetitle0."_Project_People(hours).php";
	$clkcap[$tabno] = "Click to Expand/Contract Rows.";// (it may take quite a long time to complete the task)
	$tabno++;	//1
	
	$tablelist[$tabno] = "Project vs People ($tmp-%)";
	$heading["tr"][$tabno] = "Project SubTotal (%)";
	$heading["bl"][$tabno] = "People (%)";
	$filelist["csv"][$tabno] = $filetitle0."_Project_People(percent).csv";
	$filelist["php"][$tabno] = $filetitle0."_Project_People(percent).php";
	$clkcap[$tabno] = "Click to Expand/Contract Rows.";
	$tabno++;	//2
	
	$tablelist[$tabno] = "Project vs Weeks ($tmp-Hour)";
	$heading["tr"][$tabno] = "SubTotal (Project)";
	$heading["bl"][$tabno] = "SubTotal (RLA)";
	$filelist["csv"][$tabno] = $filetitle0."_Project_Weeks(hours).csv";
	$filelist["php"][$tabno] = $filetitle0."_Project_Weeks(hours).php";
	$clkcap[$tabno] = "Click to Expand/Contract Rows.";
	$tabno++;	//3
	
	$tablelist[$tabno] = "People vs Weeks ($tmp-Hour)";
	$heading["tr"][$tabno] = "SubTotal (Staff)";
	$heading["bl"][$tabno] = "SubTotal (RLA)";	
	$filelist["csv"][$tabno] = $filetitle0."_People_Weeks(hours).csv";
	$filelist["php"][$tabno] = $filetitle0."_People_Weeks(hours).php";
	$clkcap[$tabno] = "Click Me to Show";
	$tabno++;	//4
	
	$tablelist[$tabno] = "For Greenhill Rd-People vs Project ($tmp-%)";
	$TOGHRTIME = $tmp;
	$heading["bl"][$tabno] = "Project Total (%)";
	$heading["tr"][$tabno] = "People (%)";
	$filelist["csv"][$tabno] = $filetitle0."_People_Project(percent)_GHR.csv";
	$filelist["html"][$tabno] = $filetitle0."_People_Project(percent)_GHR.html";
	$tabno++;	//5

	$tablelist[$tabno] = "For Greenhill Rd-People vs Project ($tmp-hours)";
	$TOGHRTIME = $tmp;
	$heading["bl"][$tabno] = "Project Total (hours)";
	$heading["tr"][$tabno] = "People (hours)";
	$filelist["csv"][$tabno] = $filetitle0."_People_Project(hours).csv";
	$filelist["html"][$tabno] = $filetitle0."_People_Project(hours).html";
	$tabno++;	//6

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
		fputs($fp,$headstr."<h1>Monthly Summary</h1>$tailhtml<ul>");
	}
	
	echo "<ul>";
	for ($i=0; $i<count($tablelist); $i++) {
		$j = $i + 1;
		echo "<li><font size=2><a href=#$i>Table $j: $tablelist[$i]</a></font></li>";
		if ($fp) {
			$csvfile = $filelist["csv"][$i];
			$htmlfile = $filelist["php"][$i];
			fputs($fp,"<li><font size=\"$bulsize\">Download or open a [".
				"<a href=\"../report/$csvfile\" target=\"_blank\"><b>CSV</b></a>] or a [".
				"<a href=\"../report/$htmlfile\" target=\"_blank\"><b>HTML</b></a>]".
				" format file for table $j: $tablelist[$i]</a></font></li>");
		}
	}
	echo '<li><img border="0" src="../images/new01.gif">'.
		"<A href=\"#end\"><font size=3 color=#0000ff><b>
		To download zipped file, please go to bottom of this page.</b></font></A></li>";
	echo "</ul>";
	if ($fp) {
		fputs($fp,"<ul><br></ht"."ml>");
		fclose($fp);
	}

	$click = " onClick=\"mtabolclick();\" class=\"outline\" 
		onMouseOver=\"self.status='Click me to Expand or Contract.';\" 
		onMouseOut=\"self.status='TS Analysis and Summary.';\"";
	$hide = " style=\"display: none\"";
	$cell = " border=0 cellspacing=\"0\" cellpadding=\"0\"";
	$cell0 = " border=$tabborder cellspacing=\"0\" cellpadding=\"0\"";
	
	include("ts_sum_rpt_dirm_ol_tr1to3.inc");
	//$tabno = 3;

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
	
	include("ts_sum_rpt_ghrm.inc");
	
######################################
	$ti = $tabno - 1;
	echo "<font size=\"$bulsize\">To&nbsp;
		<a href=#tabind>[Table Index]</a>&nbsp;
		<a href=#$ti>[Top of this Table]</a>&nbsp;";
	echo "</font>";
	
function writehtml($fphtml,$htmlstr) {
	echo $htmlstr;
	if ($fphtml) {
		fputs($fphtml,$htmlstr);
	}
}
?>
</html>

