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
flush();
############################################
##	Summary Report For Any Selected Period: Table Index
############################################	
	$tailhtml = "<font size=2>(".date("l, F j Y").")</font><br><br>";
	$tailhtml = $tailhtml.'<?php include("'.$js.'"); ?>'.$frmstr;
	// "MthSum" "QtrSum"	"HfySum"	"YlySum"	"SlpSum";
	if ($rpttypeS == "QtrSum") {
		$tmp = "Quarter";
		$viscol = "q";
	} elseif ($rpttypeS == "HfySum") {
		$tmp = "HalfYear";
		$viscol = "hy";
	} elseif ($rpttypeS == "YlySum") {
		$tmp = "Year";
		$viscol = "y";
	} elseif ($rpttypeS == "SlpSum") {
		$tmp = "SP";
		$viscol = "y";
	}
	
	$tabno = 0;
	$tablelist[$tabno] = "Project vs People ($periodtxt-Hour)";
	$heading["tr"][$tabno] = "Project SubTotal (hrs)";
	$heading["bl"][$tabno] = "People SubTotal (hrs)";
	$filelist["csv"][$tabno] = $filetitle0."_Project_People(hours).csv";
	$filelist["php"][$tabno] = $filetitle0."_Project_People(hours).php";
	$clkcap[$tabno] = "Click to Expand/Contract Rows.";// (it may take quite a long time to complete the task)
	$tabno++;
	
	$tablelist[$tabno] = "Project vs People ($periodtxt-%)";
	$heading["tr"][$tabno] = "Project SubTotal (%)";
	$heading["bl"][$tabno] = "People (%)";
	$filelist["csv"][$tabno] = $filetitle0."_Project_People(percent).csv";
	$filelist["php"][$tabno] = $filetitle0."_Project_People(percent).php";
	$clkcap[$tabno] = "Click to Expand/Contract Rows.";
	$tabno++;
	
//if ($priv == "00") {
	$tablelist[$tabno] = "Project vs $tmp ($periodtxt-Hour-%)";
	$heading["tr"][$tabno] = "SubTotal (Project)";
	$heading["bl"][$tabno] = "SubTotal (RLA) [-TIL]";
	$filelist["csv"][$tabno] = $filetitle0."_Project_$tmp(hp).csv";
	$filelist["php"][$tabno] = $filetitle0."_Project_$tmp(hp).php";
	$clkcap[$tabno] = "Click to Expand/Contract Rows.";
	$tabno++;
/*
} else {
	$tablelist[$tabno] = "Project vs $tmp ($periodtxt-Hour)";
	$heading["tr"][$tabno] = "SubTotal (Project)";
	$heading["bl"][$tabno] = "SubTotal (RLA)";
	$filelist["csv"][$tabno] = $filetitle0."_Project_$tmp(hours).csv";
	$filelist["php"][$tabno] = $filetitle0."_Project_$tmp(hours).php";
	$clkcap[$tabno] = "Click to Expand/Contract Rows.";
	$tabno++;
}
//*/

	$tablelist[$tabno] = "People vs $tmp ($periodtxt-Hour)";
	$heading["tr"][$tabno] = "SubTotal (Staff)";
	$heading["bl"][$tabno] = "SubTotal (RLA)";	
	$filelist["csv"][$tabno] = $filetitle0."_People_$tmp(hours).csv";
	$filelist["php"][$tabno] = $filetitle0."_People_$tmp(hours).php";
	$clkcap[$tabno] = "Click Me to Show";
	$tabno++;
	
	$tablelist[$tabno] = "People vs Project ($periodtxt-%)";
	//$tablelist[$tabno] = "For Greenhill Rd-People vs Project ($periodtxt-%)";
	$TOGHRTIME = $tmp;
	$heading["bl"][$tabno] = "Project Total (%)";
	$heading["tr"][$tabno] = "People (%)";
	$filelist["csv"][$tabno] = $filetitle0."_People_Project(percent)_GHR.csv";
	$filelist["html"][$tabno] = $filetitle0."_People_Project(percent)_GHR.html";
	$tabno++;

	$tablelist[$tabno] = "People vs Project ($periodtxt-Hour)";
	//$tablelist[$tabno] = "For Greenhill Rd-People vs Project ($periodtxt-hours)";
	$TOGHRTIME = $tmp;
	$heading["bl"][$tabno] = "Project Total (hours)";
	$heading["tr"][$tabno] = "People (hours)";
	$filelist["csv"][$tabno] = $filetitle0."_People_Project(hours).csv";
	$filelist["html"][$tabno] = $filetitle0."_People_Project(hours).html";
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
		fputs($fp,$headstr."<h1>$rpttypeL ($periodtxt) Timesheet Summary.</h1>$tailhtml<ul>");
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
	flush();

	//$weekindex[$noweek]	$monthindex[$nomonth]	$qtrindex[$noqtr]
	//$hyindex[$nohy]			$yrindex[$noyr]  

##	$rpttypeS == "MthSum" "QtrSum"	"HfySum"	"YlySum"	"SlpSum" 
	if ($rpttypeS == "MthSum") {
		$wrt_ind = $monthindex[0];
	} elseif ($rpttypeS == "QtrSum") {
		$wrt_ind = $qtrindex[0];
	} elseif ($rpttypeS == "HfySum") {
		$wrt_ind = $hyindex[0];
	} elseif ($rpttypeS == "YlySum") {
		$wrt_ind = $yrindex[0];
	} elseif ($rpttypeS == "SlpSum") {
		if ($datapresent == "w") {
			$wrt_ind = $weekindex[0];
		} elseif ($datapresent == "m") {
			$wrt_ind = $monthindex[0];
		} elseif ($datapresent == "q") {
			$wrt_ind = $qtrindex[0];
		} elseif ($datapresent == "hy") {
			$wrt_ind = $hyindex[0];
		} elseif ($datapresent == "y") {
			$wrt_ind = $yrindex[0];
		}
	}
/*
	echo "rpttypeS =$rpttypeS <br>";
	echo "wrt_ind=$wrt_ind<br>";
	//exit;	
//*/
################################################
#	control which table to be shown
if (!$tabletoshow) {
	$tabletoshow = "all";
}
	//echo "tabletoshow =$tabletoshow <br>";

############################################
##	Table 1: Project_People(hours)-rPPh
	$tabno = 0;	//"Project vs People ($periodtxt-Hour)";
	$tabnop1 = $tabno + 1;
	if ($tabletoshow == "all" || $tabletoshow == $tabnop1) {
		include("ts_sum_rpt_diropd_ol_table1.inc");
	}
	flush();

############################################
##	Table 2: Project_People(%)- rPPp
	$tabno = 1;	//"Project vs People ($periodtxt-%)";
	$tabnop1 = $tabno + 1;
	if ($tabletoshow == "all" || $tabletoshow == $tabnop1) {
		include("ts_sum_rpt_diropd_ol_table2.inc");
	}
	flush();

############################################
##	Table 3: Project_Weeks - rPjW
	$tabno = 2;	//"Project vs $tmp ($periodtxt-Hour)";
	$tabnop1 = $tabno + 1;
	if ($tabletoshow == "all" || $tabletoshow == $tabnop1) {
		include("ts_sum_rpt_diropd_ol_table3_new.inc");
	}
	flush();

############################################
##	Table 4: People_Weeks - cPeW
	$tabno = 3;	//"People vs $tmp ($periodtxt-Hour)";
	$tabnop1 = $tabno + 1;
	if ($tabletoshow == "all" || $tabletoshow == $tabnop1) {
		include("ts_sum_rpt_diropd_ol_table4.inc");
	}
	flush();

############################################
##	Table 5: 
	$tabno = 4;	//"For Greenhill Rd-People vs Project ($tmp-%)";
	$tabnop1 = $tabno + 1;
	if ($tabletoshow == "all" || $tabletoshow == $tabnop1) {
		include("ts_sum_rpt_diropd_ol_table5.inc");
	}
	flush();

############################################
##	Table 6: 
	$tabno = 5;	//"For Greenhill Rd-People vs Project ($tmp-hours)";
	$tabnop1 = $tabno + 1;
	if ($tabletoshow == "all" || $tabletoshow == $tabnop1) {
		include("ts_sum_rpt_diropd_ol_table6.inc");
	}
function writehtml($fphtml,$htmlstr) {
	echo $htmlstr;
	if ($fphtml) {
		fputs($fphtml,$htmlstr);
	}
}
?>
</html>

