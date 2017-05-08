<html>
<?php
	$select_list	=	'email_name, title, first_name, last_name';
if (!$where) {
	if ($where_heh == "y") {
		$where	=	'date_unemployed="0000-00-00" and email_name!="webmaster" and email_name!="heh" order by last_name';
	} else {
		$where	=	'date_unemployed="0000-00-00" and email_name!="webmaster" order by last_name';
	}
}	
	include('search_staff.inc');
if ($staffmulti	!=	"multi") {
	echo '<td><select ';
	echo ' name="staffname" size="1">';
	if ($all	== "All Staff") {
		if ($email_name == "all") {
			echo '<OPTION SELECTED value="all">'.$all;
		} else {
			echo '<OPTION value="all">'.$all;
		}
	}
	for ($i=0; $i<$staffno; $i++) {
		$ename	=	$stafflist[0][$i];
		$fname	=	$stafflist[2][$i].' '.$stafflist[3][$i];
		//$fname	=	$stafflist[1][$i].' '.$stafflist[2][$i].' '.$stafflist[3][$i];
		$fname = ucwords($fname);
		if ($ename == $email_name) {
			echo '<OPTION SELECTED value="'.$ename.'">'.$fname;
		} else {
			echo '<OPTION value="'.$ename.'">'.$fname;
		}
	}
	echo '</select></td>';
}
?>
</html>