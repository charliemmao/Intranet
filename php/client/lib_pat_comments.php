<html>

<head>
<title>Patent comments</title>
</head>
<body text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG" leftmargin="20">

<?php
echo '<h2 align=center>Patent comments</h2>';// onMouseOver=\"window.status='This is a test.'; return true;\"
$statuscontext = "Refresh";
include("self_status.inc");
echo "<a id=top><p align=center><a href=\"".$PHP_SELF."?patentno=$patentno\" $status>[Refresh]</a><hr>";
include('connet_root_once.inc');
$remoreip = getenv("remote_addr");
$sql = "SELECT email_name, first_name, last_name
        FROM timesheet.employee 
        WHERE computer_ip_addr='$remoreip'";
$result = mysql_query($sql);
include("err_msg.inc");
list($email_name, $first_name, $last_name) = mysql_fetch_array($result);
 
if (!$patentno) {
	echo "<h2>Hi $first_name</h2>";
	echo "Please ente patent no for comments.<br><br><hr>";
	exit;
}

$sql = "SELECT patent_id
        FROM library.for_patent 
        WHERE patent_no='$patentno'";
$result = mysql_query($sql);
include("err_msg.inc");
list($patent_id) = mysql_fetch_array($result);

if (!$patent_id) {
	echo "<h2>Hi $first_name</h2>";
	echo "Patent no $patentno doesn't exist in RLA library DB.<br><br><hr>";
	exit;
}

if ($makecomments) {
	$datestr = date("Y-m-d");
	$comments = trim($comments);
	echo "<h2>Hi $first_name</h2>";
	if (!$comments) {
		echo "<font color=#ff0000>Comments field is empty.</font><br><br>";
	} else {
    	$sql = "INSERT INTO library.patcomments 
        	SET comid='null', patent_no='$patentno', 
            email_name='$email_name', comments='$comments', 
            date='$datestr';";
		$result = mysql_query($sql);
		include("err_msg.inc");
		echo "Your comments has been submitted successfully.<br><br>";
	}
   echo "<hr>";
}

# form for comments
echo "<form method=post>";
echo "<input type=hidden name=patentno value=$patentno>";
echo "<table>";
echo "<tr><th align=left>Patent No</th><td>$patentno</td></tr>";
echo "<tr><th align=left>From</th><td>$first_name $last_name</td></tr>";
echo "<tr><th align=left colspan=2>Comments</th></tr>";
echo '<tr><th colspan=2><textarea rows="10" name="comments" cols="80">'.$comments.'</textarea></TH></TR>';
echo "<tr><th colspan=2><button type=submit name=makecomments><b>Submit Comment</b></th></tr>";
echo "</table>";
echo "</form><p>";

#list current comment related to this patent
$sql = "SELECT email_name as ename, comments, date 
        FROM library.patcomments 
        WHERE patent_no='$patentno'
        ORDER BY date DESC;";
$result = mysql_query($sql);
include("err_msg.inc");
$no = mysql_num_rows($result);
echo "<hr>";
if (!$no) {
	echo "<b>No comment(s) found for patent $patentno.</b>";
} else {
	echo "<h3>$no comment(s) found for patent $patentno.</h3>";
    while (list($ename, $comments, $date) = mysql_fetch_array($result)) {
    	echo "<br><br><b>Commented by $ename on $date</b><br>";
       echo $comments;
    }
}
echo "<hr><a href=#top $status>Back to top</a><br>";
?>
</body>
