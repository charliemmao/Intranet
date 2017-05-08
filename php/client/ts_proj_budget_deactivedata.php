<html>
<head>
<title>Project Budget Data</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<LINK REL="StyleSheet" HREF="../style/style.css" TYPE="text/css">
</head>
<body leftmargin="20" bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG">
<?php
include('str_decode_parse.inc');
include("connet_root_once.inc");
include("userinfo.inc"); //$userinfo
if ($priv == "00" || $priv	==	'10') {
} else {
	exit;
}
$removebgtfile = 1;
$userstr	=	"?".base64_encode($userinfo);
echo "<h2 align=center><a id=top>Remove Project File</a>";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[Refresh]</font></a>";
echo "</h2>";

######################################################################################################
	$sql = "SELECT projcode_id as id, brief_code 
       FROM timesheet.projcodes 
	 	ORDER BY brief_code;";
    $result = mysql_query($sql);
    include("err_msg.inc");
    $no = mysql_num_rows($result);
	$i=0;
    while (list($id, $brief_code) = mysql_fetch_array($result)) {
    	$brcode[$id] = $brief_code;
    	$rlacodearray[$i] = $id;
    	$i++;
    }	

######################################################################################################
if ($bgtfileidx) {
	echo "<hr>";
	$sql = "SELECT projcode_id, description, client, 
            begin_date, end_date, preparedby, uploaddate 
        FROM timesheet.bgtfileidx
        WHERE bgtfileidx='$bgtfileidx';";
    $result = mysql_query($sql);
    include("err_msg.inc");
    list($projcode_id, $description, $client, 
            $begin_date, $end_date, $preparedby, $uploaddate) = mysql_fetch_array($result);
    echo "<table>
            <tr><td><b>PROJCODE CODE</b></td><td>".$brcode[$projcode_id]."</td></tr>
            <tr><td><b>DESCRIPTION</b></td><td>$description</td></tr>
            <tr><td><b>CLIENT</b></td><td>$client</td></tr>
            <tr><td><b>BEGIN_DATE</b></td><td>$begin_date</td></tr>
            <tr><td><b>END_DATE</b></td><td>$end_date</td></tr>
            <tr><td><b>PREPAREDBY</b></td><td>$preparedby</td></tr>
            <tr><td><b>UPLOADDATE</b></td><td>$uploaddate</td></tr></table><p>";
    $sql = "UPDATE timesheet.bgtfileidx 
        SET active='n' 
        WHERE bgtfileidx='$bgtfileidx';";
    
    $result = mysql_query($sql);
    include("err_msg.inc");
	echo "<h2>The above project file has been removed from DB.</h2>";
}

include("ts_proj_budget_deactivedata.inc");

?>
<hr><br><a href=#top><b>Back to top</b></a><br><br>
</html>
