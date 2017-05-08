<html>
<head>
<title>Check Project Code</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<LINK REL="StyleSheet" HREF="../style/style.css" TYPE="text/css">

</head>
<body leftmargin="20" bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG">
<?php
include("admin_access.inc");
include('str_decode_parse.inc');
include("connet_root_once.inc");
include("rla_functions.inc");
include("userinfo.inc"); //$userinfo
$userstr	=	"?".base64_encode($userinfo);
echo "<h2 align=center><a id=top>Check Project Code</a>";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2> [Refresh]</font></a>";
echo "</h2><hr>";
//include("userstr.inc");

############Find project code from table: projcodes
	$sql = "SELECT brief_code as bc
       FROM timesheet.projcodes 
       ORDER BY brief_code;";
    $result = mysql_query($sql);
    include("err_msg.inc");
    $noproj = 0;
    while (list($bc) = mysql_fetch_array($result)) {
    	if ($bc) {
    		$projcodelist[$noproj] = $bc;
    		$noproj++;
       }
   	}

############Find project code from table: timedata
	$sql = "SELECT DISTINCT brief_code 
        FROM timesheet.timedata 
        ORDER BY brief_code;";
   $result = mysql_query($sql);
   include("err_msg.inc");
	$noprojcodeused = 0;
   //echo "$sql<br>";
   while (list($brief_code) = mysql_fetch_array($result)) {
    		$projcodeused[$noprojcodeused] = $brief_code;
    		$noprojcodeused++;
   }

	echo "<p><p><table border=1 align=center>";
	echo "<tr><th>No</th><th>Designed Code ($noproj)</th><th>Being Used ($noprojcodeused)</th>
		<th>No Entry</th><th>Hours</th></tr>";
	for ($i=0; $i<$noproj; $i++) {
		$k=$i+1;
		echo "<tr><th>$k</th><td>".$projcodelist[$i]."</td>";
		$find = "";
		for ($j=0; $j<$noprojcodeused; $j++) {
			if ($projcodeused[$j] == $projcodelist[$i]) {
				$brief_code=$projcodeused[$j];
				$projcodeused[$j] = "";
				$sql = "SELECT Count(brief_code) as noentry, sum(minutes) as minutes
        			FROM timesheet.timedata 
        			WHERE brief_code='$brief_code';";
				$result = mysql_query($sql);
   				include("err_msg.inc");
				list($noentry, $minutes) = mysql_fetch_array($result);
				echo "<td align=middle>used</td><td align=right>$noentry</td><td align=right>"
					.number_format($minutes/60,0)."</td>";
				$find = 1;
				break;
			}
		}
		if (!$find){
			echo "<td align=middle colspan=3><font color=#ff0000>not used</font></td>";
		}
		echo "</tr>";
	}
	$msg = "";
	for ($j=0; $j<$noprojcodeused; $j++) {
		if ($projcodeused[$j]){
			$msg = "\n".$projcodeused[$j];
			echo "<tr><th><font color=#ff0000>Error code</font></th><td><font color=#ff0000>".$projcodeused[$j]."</font></td></tr>";
		}
	}
	echo "</table><p>";
	if ($msg) {
		$from = "cmm\@rla.com.au";
		$to = "cmm\@rla.com.au";
		$cc = "";
		$subject = "Warning: Project Code Check";
		$msg = "The following code from timesheet.timedata has not found a match".
			" from timesheet.projcodes.\n$msg\n\nCodes in timesheet.projcodes are listed as following:";
		for ($i=0; $i<$noproj; $i++) {
			$msg = $msg."\n".$projcodelist[$i];
		}
		$msg = $msg."\n\nSystem\n".date("Y-m-d")."\n";
		system("./_mail.pl \"$from\" \"$to\" \"$cc\" \"$subject\" \"$msg\"",$var);
	}
?>
<hr><br><a href=#top><b>Back to top</b></a><br><br>
</html>
