<html>

<head>
<title>Timesheet upadte message</title>
</head>
<body bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG" leftmargin="20">
<?php
########################################
##		Access control
########################################
include('str_decode_parse.inc');
if ($priv	==	'00' || $priv	==	'10') {
} else {
	exit;
}
$qry	=	"?".base64_encode('priv='.$priv);
echo "<h1 align=center>Update Timesheet</h1>";
echo "<a id=top><p align=center><a href=\"".$PHP_SELF."$qry\">[Refresh]</a><hr>";

########################################
##		Find details for one timesheet
########################################
if ($process_id != '' && $action=="details") {
	$dbname0	=	"timesheet";
	include('update_id_search.inc');
	$dbname0	=	"updatets";
	include('update_id_search.inc');
	echo "<p><table border=1><tr><td valign=\"top\">$out_old</td><td valign=\"top\">$out_new</td></tr>
  <td>";
	echo '<p><form method="POST" target="main" action= "'.$PHP_SELF.'">';
	echo '<input type="hidden" value="'.$priv.'" name="priv">';
	echo '<input type="hidden" value="'.$process_id.'" name=process_id>';
	echo '<tr><th colspan=2 ><input type="submit" value="Accept Update" name="updateone">';
	echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	echo '<input type="submit" value="Reject Update" name="rejectone"></th></tr>';
	echo '</form></p>';
	echo '</table>';
	echo '<hr>';
}

########################################
##		reject one timesheet
########################################
if ($rejectone) {
	//update: (a) record to be updated
	$dbname0	=	'updatets';
	include('update_id_search.inc');
	echo $out_new.'<br>';
	include("connet_root_once.inc");
	$dbname0	=	'updatets';
	$deleteentry_no	= 'yes';
	include('for_ts_process_id_delete_rcd.inc');

	//update: (c) display message
	echo '<h2><font color=#0000ff>The above record has been deleted from update list.</font></h2><hr>';
}

########################################
##		update one timesheet
########################################
if ($updateone) {
	//update: (a) record to be updated
	$dbname0	=	'updatets';
	include('update_id_search.inc');
	
	//update: (b) update
	include('update_id_done.inc');
	
	//update: (c) display message
	echo '<h2><font color=#0000ff>The above record has been updated sucessfully.</font></h2><hr>';
}

########################################
##		update all timesheets
########################################
if ($updateall) {
	include('connet_root_once.inc');
	$sql	=	"SELECT entry_no, email_name, yyyymmdd FROM updatets.entry_no;";
	$resultall	=	mysql_query($sql);
	$no	=	mysql_num_rows($resultall);
	while (list($entry_no, $email_name, $yyyymmdd) = mysql_fetch_array($resultall)) {
		$process_id	=	$entry_no;	
		$dbname0	=	'updatets';	
		include('update_id_done.inc');
		echo "<h4>$email_name's timesheet for the week of $yyyymmdd has been updated successfully.</h4>";
	}
	echo "<hr>";
}

########################################
##		Show all timesheets which are waiting for update
########################################
nosheets(&$no,&$result);
if ($no != 0) {
	echo '<h2><b><font color=#ff0000> "'.$no.'"</font></b>'.
		' timesheet require(s) update.</h2>';
} else {
	echo '<h2><b><font color=#0000ff>'.
		'Timesheet update list is empty.</font></b></h2><hr>';
	exit;
}
if ($no != 0) {
	echo "<p><table border=1>";
	echo "<tr><th>Process ID</th><th>Email Name</th><th>Week</th><th>Time Sent</th><th>Details</th></tr>";
}
while (list($entry_no, $email_name, $yyyymmdd, $timestamp) = mysql_fetch_array($result)) {
	$qry	=	"process_id=$entry_no&action=details&priv=$priv";
	$qry	=	'?'.base64_encode($qry);
	echo "<tr><td>$entry_no</td><td>$email_name</td><td>$yyyymmdd</td><td>$timestamp</td>"
	."<td><a href=\"$PHP_SELF$qry\">Details</a></td></tr>";
}
if ($no != 0) {
	echo '</table>';
}
	
########################################
##		update all form
########################################
?>
<form method="POST" target="main" action= "<?php echo $PHP_SELF ?>">
<input type="hidden" value="<?php echo $priv ?>" name="priv">
<ul>
    <font face="Arial Narrow">
    <li><font size=4><b>Update All</b></font>&nbsp;&nbsp;&nbsp; <input type="submit" value="Update All" name="updateall"></li>
</ul>    
</form>
<hr>

<?php
function nosheets(&$no,&$result) {
	include('connet_root_once.inc');
	$sql	=	"SELECT entry_no, email_name, yyyymmdd, timestamp FROM updatets.entry_no;";
	$result	=	mysql_query($sql);
	$no	=	mysql_num_rows($result);
	mysql_close();
}
?>
<a href=#top>Back to top</a><br>
</body>
