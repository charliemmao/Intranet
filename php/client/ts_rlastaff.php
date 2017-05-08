<html>

<head>
<meta http-equiv="Content-Language" content="en-au">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>View Staff Info</title>
</head>
<body background="rlaemb.JPG" leftmargin="20">

<a id=top><p align=center><a href="<?php echo $PHP_SELF; ?>">[Refresh]</a></p><hr>
<?php
include('str_decode_parse.inc');
	include("connet_root_once.inc");

    $sql = "SELECT description as domain
        FROM logging.sysmastertable 
        WHERE item='Domain_Name';";

    $result = mysql_query($sql);
    include("err_msg.inc");
    list($domain) = mysql_fetch_array($result);

if (!$staffinfo) {
	$email_name = "all";
}
$all	= "All Staff";
if ($staffinfo != '') {
	$email_name = $staffname;
}
?>
<form method="POST" action="<?php echo $PHP_SELF ?>">
<p><table border=0>
<tr><td><b>Staff Name</b></td>
<?php include("stafflist.inc"); ?>
<td><input type="submit" name="staffinfo" value="Search"><td></tr></table></p><hr>

<?php
//if ($staffinfo) {
	include("connet_anyone.inc");
	$qrylist	= "email_name,title, first_name, middle_name, last_name, rla_ph_ext, private_ph";
	if ($email_name != "all") {
		$sql = "select $qrylist from timesheet.employee where email_name='$email_name' and email_name!='webmaster';";
	} else {
		$sql = "select $qrylist from timesheet.employee where email_name!='webmaster' order by first_name;";
	}
	$result = mysql_query($sql);
	include("err_msg.inc");
	if ($result) {
		echo "<p><table border =1>";
		if ($email_name != "all") {
			list($email_name, $title, $first_name, $middle_name, $last_name, $rla_ph_ext, $private_ph) = mysql_fetch_array($result);
			$title = ucwords($title);
			$first_name = ucwords($first_name);
			$middle_name = ucwords($middle_name);
			$last_name = ucwords($last_name);
			if ($middle_name != "") {
				echo "<tr><td><b>Name</b></td><td><a href=\"mailto:$email_name@$domain\">$first_name $middle_name $last_name</a></td></tr>";//$title 
			} else {
				echo "<tr><td><b>Name</b></td><td><a href=\"mailto:$email_name@$domain\">$first_name $last_name</a></td></tr>";//$title 
			}
			if ($rla_ph_ext == "") {
				$rla_ph_ext = "NA";
			}
			if ($private_ph == "") {
				$private_ph = "NA";
			}
			echo "<tr><td><b>Ext</b></td><td align=center>$rla_ph_ext</td></tr>";
			//echo "<tr><td><b>Private Tel</b></td><td align=center>$private_ph</td></tr>";
		} else {
			echo "<tr><th>Name</th><th>Ext</th></tr>";
			//echo "<tr><th>Name</th><th>Ext</th><th>Private Tel</th></tr>";
			while (list($email_name, $title, $first_name, $middle_name, $last_name, $rla_ph_ext, $private_ph)	=	mysql_fetch_array($result)) {
				$title = ucwords($title);
				$first_name = ucwords($first_name);
				$middle_name = ucwords($middle_name);
				$last_name = ucwords($last_name);
				if ($middle_name != "") {
					echo "<tr><td><a href=\"mailto:$email_name@$domain\"><b>$first_name $middle_name $last_name</b></a></td>";//$title 
				} else {
					echo "<tr><td><a href=\"mailto:$email_name@$domain\"><b>$first_name $last_name</b></a></td>";//$title 
				}
				if ($rla_ph_ext == "") {
					$rla_ph_ext = "---";
				}
				if ($private_ph == "") {
					$private_ph = "---";
				}
				echo "<td align=center>$rla_ph_ext</td>";
				echo "</tr>";
				//echo "<td align=center>$private_ph</td></tr>";
			}
		}
		echo "</table></p><hr><br>";
		if ($email_name == "all") {
			echo "<a href=#top>Back to top</a><br><br>";
		}
	}
//}
?>
</body>
</html>