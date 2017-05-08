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
	$tabno++;
	
	$tablelist[$tabno] = "Project vs People ($tmp-%)";
	$heading["tr"][$tabno] = "Project SubTotal (%)";
	$heading["bl"][$tabno] = "People (%)";
	$filelist["csv"][$tabno] = $filetitle0."_Project_People(percent).csv";
	$filelist["php"][$tabno] = $filetitle0."_Project_People(percent).php";
	$clkcap[$tabno] = "Click to Expand/Contract Rows.";
	$tabno++;
	
	$tablelist[$tabno] = "Project vs Weeks ($tmp-Hour)";
	$heading["tr"][$tabno] = "SubTotal (Project)";
	$heading["bl"][$tabno] = "SubTotal (RLA)";
	$filelist["csv"][$tabno] = $filetitle0."_Project_Weeks(hours).csv";
	$filelist["php"][$tabno] = $filetitle0."_Project_Weeks(hours).php";
	$clkcap[$tabno] = "Click to Expand/Contract Rows.";
	$tabno++;
	
	$tablelist[$tabno] = "People vs Weeks ($tmp-Hour)";
	$heading["tr"][$tabno] = "SubTotal (Staff)";
	$heading["bl"][$tabno] = "SubTotal (RLA)";	
	$filelist["csv"][$tabno] = $filetitle0."_People_Weeks(hours).csv";
	$filelist["php"][$tabno] = $filetitle0."_People_Weeks(hours).php";
	$clkcap[$tabno] = "Click Me to Show";
	$tabno++;

if ( $rlaserver == $thisserver ) {	
	$permin = "min";	//old
	$tablelist[$tabno] = "For GHR-People vs Project ($tmp-$permin, all codes included)";
	$TOGHRTIME = $tmp;
	$heading["bl"][$tabno] = "Project Total ($permin)";
	$heading["tr"][$tabno] = "People ($permin)";
	$filelist["csv"][$tabno] = $filetitle0."_PP_AllCodes($permin)_GHR.csv";
	$filelist["html"][$tabno] = $filetitle0."_PP_AllCodes($permin)_GHR.html";
	$tabno++;

	$tablelist[$tabno] = "For GHR-People vs Project ($tmp-%/min, including GHR required codes)";	
	$TOGHRTIME = $tmp;
	$heading["bl"][$tabno] = "Project Total (%)";
	$heading["tr"][$tabno] = "People (%)";
	$filelist["csv"][$tabno] = $filetitle0."_PP_SubSet(pc+min)_GHR.csv";
	$filelist["html"][$tabno] = $filetitle0."_PP_SubSet(pc+min)_GHR.html";
	$tabno++;

/*
	$tablelist[$tabno] = "For GHR-People vs Project ($tmp-Hour)";//Greenhill Rd
	$TOGHRTIME = $tmp;
	$heading["bl"][$tabno] = "Project Total (hours)";
	$heading["tr"][$tabno] = "People (hours)";
	$filelist["csv"][$tabno] = $filetitle0."_People_Project(hours).csv";
	$filelist["html"][$tabno] = $filetitle0."_People_Project(hours).html";
	$tabno++;
//*/
}
	$sql = "SELECT brief_code as tilcode FROM tshtbackup.projcodes 
		WHERE brief_code='RLA-OHD-Time_in_Lieu'";
	$r =mysql_query($sql);
	list($tilcode) = mysql_fetch_array($result);
	if ($tilcode) {
	$tablelist[$tabno] = "Time-in-Lieu ($tmp-Hour)";
	$heading["tr"][$tabno] = "";
	$heading["bl"][$tabno] = "";	
	$filelist["csv"][$tabno] = $filetitle0."_TIL(min).csv";
	$filelist["php"][$tabno] = $filetitle0."_TIL(min).php";
	$tabno++;
	}
	
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
if ( $rlaserver == $thisserver ) {	
	echo '<li>'.
		"<A href=\"#zipsection\"><font size=\"$bulsize\">
		To download zipped file.</font></A></li>";
	echo '<li><img border="0" src="../images/new01.gif">'.
		"<A href=\"#mailsection\"><font size=3 color=#0000ff><b>
		To send timesheet to GHR, please go to bottom of this page.</b></font></A></li>";
}
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
	
	include("ts_sum_rpt_dirm_ol_tr1to4.inc");
	
	$tabno = 4;
	if ( $rlaserver == $thisserver ) {	
		//include("ts_sum_rpt_dirm_ol_ghrm.inc");
		include("ts_sum_rpt_dirm_ol_ghrm_tab5_pp_fullcodes.inc"); //minutes full code + project groups
			//disabled on 4/11/2003 requested by Terry
		include("ts_sum_rpt_dirm_ol_ghrm_tab6_pp_subper.inc");	//percentage subset of data
		//include("ts_sum_rpt_dirm_ol_ghrm_tab7_pp_fullhr.inc");
	}	
	if ($tilcode) {
		include("ts_sum_rpt_dirm_ol_tilm.inc");
	}
	
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

