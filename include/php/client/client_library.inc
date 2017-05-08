<html>
<SCRIPT LANGUAGE=JavaScript>
	window.defaultStatus = "Hello and Welcome to Intranet: Library";
</SCRIPT>
<?php
include("library_verify.inc");
include("phpdir.inc");
##pass client info to next page
$qry	=	'?'.base64_encode($userinfo);
//if ($priv	==	'00' || $email_name == "llm") {
if ($email_name != "nobody") {
	echo '<hr>';
	echo "<font size=3><b>Library Search</b></font>";
	$statuscontext = "Search Library.";
	$sql = "SELECT COUNT(book_id) as book_id FROM library.for_book;";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($book_id) = mysql_fetch_array($result);
	$sql = "SELECT COUNT(patent_id) as book_id FROM library.for_patent;";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($patent_id) = mysql_fetch_array($result);
	
		if ($clientip == $adminip) {
			$statuscontext = "lib_search.php ($statuscontext)";
		}
	include("self_status.inc"); //'.$status.'
	echo '<ul>';
	echo '<li><align="left"><font size="2"><a href="/'.$phpdir.'/lib_search.php?'.$qry
		.'" target="main"'.$status.'>Library Search <br>(Book: '.$book_id.", Patent: $patent_id)".
		'</a></font></li>';
	$frm_str="ename=$email_name&pwd=$userpwd";
	$frm_str	=	base64_encode($frm_str);
	echo '<li><align="left"><font size="2"><a href="/'.$phpdir.'/lib_book_loan_process.php?'.$frm_str
		.'" target="main">Book Loan<br></a></font></li>';
		
	echo '</ul>';
} elseif ($priv	==	'40' || $email_name == "llm") {
	/*
	echo '<hr>';
	echo "<font size=3><b>Library entry is currently unavailable.</b></font><hr>";
	exit;
	//*/
} else {	#for everyone
	echo '<hr>';
	//echo "<font size=3><b>Library search will be available soon.</b></font>";
	//exit;
}

##	SHOW pages to people who enter data
if ($priv	==	'00' || $priv	==	'40' || $email_name == "llm") {
##	Item Entry 
	echo "<hr><font size=3><b>Data Entry</b></font>";
	echo '<ul>';
	//*
	if ($priv	==	'00') {
##	Dump Patent Item 
	$statuscontext = "Dump Access Database or NEW HTML Data for Patent";
		if ($clientip == $adminip) {
			$statuscontext = "lib_patent_dump.php ($statuscontext)";
		}
	include("self_status.inc"); //'.$status.'
	echo '<li><align="left"><font size="2"><a href="/'.$phpdir.'/lib_patent_dump.php?'.$qry
	.'" target="main"'.$status.'>Patent Data Dump</a></font></li>';
	}
	//*/
	$statuscontext = "Add New Library Record.";
		if ($clientip == $adminip) {
			$statuscontext = "lib_new_item.php ($statuscontext)";
		}
	include("self_status.inc"); //'.$status.'
	echo '<li><align="left"><font size="2"><a href="/'.$phpdir.'/lib_new_item.php?'.$qry
	.'" target="main"'.$status.'>Add New Item</a></font></li><br>';
	
##	Modify Item 
	$statuscontext = "Modify Library Item.";
		if ($clientip == $adminip) {
			$statuscontext = "lib_modify.php ($statuscontext)";
		}
	include("self_status.inc"); //'.$status.'
	echo '<li><align="left"><font size="2"><a href="/'.$phpdir.'/lib_modify.php?'.$qry
	.'" target="main"'.$status.'>Modify An Item</a></font></li>';
	
##	List All Patents Number 
	$statuscontext = "List All Patents.";
		if ($clientip == $adminip) {
			$statuscontext = "lib_patent_list.php ($statuscontext)";
		}
	include("self_status.inc"); //'.$status.'
	echo '<li><align="left"><font size="2"><a href="/'.$phpdir.'/lib_patent_list.php?'.$qry
	.'" target="main"'.$status.'>List All Patents</a></font></li></ul>';
}
?>
</html>