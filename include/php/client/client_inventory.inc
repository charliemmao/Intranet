<html>
<SCRIPT LANGUAGE=JavaScript>
	window.defaultStatus = "Hello and Welcome to Intranet: Inventory";
</SCRIPT>
<?php
include("phpdir.inc");
##pass client info to next page
//echo $userinfo.'<br>'; 
$qry	=	'?'.base64_encode($userinfo);
	
##	SHOW pages for administrator 
if ($priv	==	'00' || $priv	==	'10') {
	echo '<hr>';
	echo "<font size=3><b>Section A: Admin's Pages</b></font><ul>";	
	//	inventory query
	$statuscontext = "Inventory Query.";
		if ($priv	==	'00') {
			$statuscontext = "inv_query.php ($statuscontext)";
		}
	include("self_status.inc"); //'.$status.'
	echo '<li><font size="2"><align="left">'
	.'<a href="/'.$phpdir.'/inv_query.php?'.$qry
	.'" target="main"'.$status.'>Item Query</a></font></li><br>';
/*
	//	inventory report
	$statuscontext = "Inventory Report.";
		if ($priv	==	'00') {
			$statuscontext = "inv_report.php ($statuscontext)";
		}
	include("self_status.inc"); //'.$status.'
	echo '<li><font size="2"><align="left">'
	.'<a href="/'.$phpdir.'/inv_report.php?'.$qry
	.'" target="main"'.$status.'>Write Report</a></font></li><br>';
*/
	if ($priv	==	'00') {
		echo '<li align="left"><font size="2">Modify Data [</font>';
		$qry	=	'?'.base64_encode($userinfo."&action=uniformbarcode");
		$statuscontext = "Modify Inventroy Barcode to Nine Characters Long.";
		if ($priv	==	'00') {
			$statuscontext = "inv_room.php ($statuscontext)";
		}
		include("self_status.inc"); //'.$status.'
		echo '<a href="/'.$phpdir.'/inv_room.php?'.$qry
			.'" target="main"'.$status.'><font size="2">Barcode</font></a><font size="2">][</font>';
			
		$qry	=	'?'.base64_encode($userinfo."&action=roomno");
		$statuscontext = "Modify Inventroy Room as Room ##.";
		if ($priv	==	'00') {
			$statuscontext = "inv_room.php ($statuscontext)";
		}
		include("self_status.inc"); //'.$status.'
		echo '<a href="/'.$phpdir.'/inv_room.php?'.$qry
			.'" target="main"'.$status.'><font size="2">Room</font></a><font size="2">]</font>';
		echo '</li><br>';
	}

	if ($priv	==	'00' || $priv	==	'10') {
		echo '<li align="left">';
		$qry	=	'?'.base64_encode($userinfo."&action=disposal");
		$statuscontext = "Dispose Item.";
		if ($priv	==	'00') {
			$statuscontext = "inv_disposal.php ($statuscontext)";
		}
		include("self_status.inc"); //'.$status.'
		echo '<a href="/'.$phpdir.'/inv_disposal.php?'.$qry
			.'" target="main"'.$status.'><font size="2">Item Disposal</font></a><font size="2"></font>';
	}

	echo '</ul><hr>';
	echo "<font size=3><b>Section B: $first_name's Pages</b></font>";
} else {	#for everyone
	echo "<br><hr><font size=2><b>$first_name's Pages</b></font>";
}

##	Item Entry 
	$statuscontext = "Add New Item.";
		if ($priv	==	'00') {
			$statuscontext = "inv_new_item.php ($statuscontext)";
		}
	include("self_status.inc"); //'.$status.'
	echo '<ul><li><align="left"><font size="2"><a href="/'.$phpdir.'/inv_new_item.php?'.$qry
	.'" target="main"'.$status.'>Add New Item</a></font></li><br>';

##	Modify Item 
	$statuscontext = "Modify My Entry.";
		if ($priv	==	'00') {
			$statuscontext = "inv_modify.php ($statuscontext)";
		}
	include("self_status.inc"); //'.$status.'
	echo '<li><align="left"><font size="2"><a href="/'.$phpdir.'/inv_modify.php?'.$qry
	.'" target="main"'.$status.'>Modify My Entry</a></font></li></ul><br>';

?>
</html>
