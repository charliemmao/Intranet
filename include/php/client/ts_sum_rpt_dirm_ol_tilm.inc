<html>
<?php
//Table 7
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
	$mailmsg = "display";

	$filetitle = $newfile0[0][0];
	$newfile	=  $newfile0[1][0];
	$fpcsv	=	fopen($newfile,'w+');
	$filetitle = $newfile0[0][1];
	$newfile	=  $newfile0[1][1];
	$fphtml	=	fopen($newfile,'w+');
	
	echo "$sqlTIL sqlTIL<br>";
	exit;
	$result = mysql_query($sqlTIL);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	
	$htmlstr = "<table border=1><tr><th>Name</tr><th>Hour</th><th>Date</th></tr>";	
	echo $htmlstr;
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,"$head$tablecaption\n$tailcsv\n");		
		fputs($fpcsv,"Name,Minutes,Date\n");
		fputs($fphtml,"<ht"."ml><h1>$head$tablecaption</h1>$tailhtml<br>");
		fputs($fphtml,$htmlstr);
	}
	
	while (list($ymd, $ename, $minutes) = mysql_fetch_array($result)) 
	{
		$tmp = number_format($minutes/60, 2);
		$tmp = ereg_replace(",", "", $tmp);

		$htmlstr="<tr><td>$ename</td><td>$tmp</td><td>$ymd</td></tr>";
		echo $htmlstr;
		if ($fpcsv && $fphtml) {
			fputs($fphtml,$htmlstr);
			fputs($fpcsv, "$ename,$tmp,$ymd\n");
		}
	}
	
	if ($fpcsv && $fphtml) {
		fputs($fpcsv,"\n\n");
		fclose($fpcsv);
		fputs($fphtml,"</table><p>");
		fputs($fphtml,"</ht"."ml>");
		fclose($fphtml);
	}
	echo "</table><p>";
?>
</html>
