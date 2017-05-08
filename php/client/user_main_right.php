<html>

<head>
<meta http-equiv="Content-Language" content="en-au">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>user main page</title>
</head>

<body background="rlaemb.JPG" leftmargin="20">
<?php
include("phpdir.inc"); 
include('getusersec.inc');
include('str_decode_parse.inc');
include("islogon.inc");
	include("userinfo.inc"); //$userinfo
	$qry0	=	$userinfo.'&dbname='.$defaultdb.'&dummy1=dummy';
	$qry	=	base64_encode($qry0);
	if ($defaultdb) {
		echo "<script language=\"javascript\">";
		if ($defaultdb == "timesheet") {
			if ($priv == "00") {
				echo "window.location=\"http://".getenv("server_name")."/$phpdir/ts_ana_rpt.php?$qry\";";
			} elseif ($email_name == "webmaster") {
				echo "window.location=\"http://".getenv("server_name")."/$phpdir/ts_ana_rpt.php?$qry\";";
			} else {
				echo "window.location=\"http://".getenv("server_name")."/$phpdir/ts_compose.php?$qry\";";
			}
		} elseif ($defaultdb == "inventory") {
			echo "window.location=\"http://".getenv("server_name")."/$phpdir/inv_new_item.php?$qry\";";
		} elseif ($defaultdb == "library") {
			echo "window.location=\"http://".getenv("server_name")."/$phpdir/user_main_right.php\";";
			//echo "window.location=\"http://".getenv("server_name")."/$phpdir/?$qry\";";
		} 
		echo "</script>";
		exit;
	}

	if ($priv != "1000" && $email_name) {
		//July 21 2000
		echo "<H1 align=center><font color=#ff0000>Timesheet's New Features</font></h1>";
		echo "<h4>Timesheet Compose Page</h4>";
		echo "<ul><li>Code definitions, total minutes and minutes short/over on the form are shown on the status bar at the bottom of your screen.</li>";
		echo "<li>The number is scaled up and selected if the 15 divisible rule is required.</li>";
		echo "<li>Load previous timesheet requires you selecte \"Load Timesheet\" from the \"Type of Action\" list.</li>";
		echo "</ul><h4>Project Codes Selection Page</h4><ul>";
		echo "<li>Code definitions and number of codes selected are shown on the status bar at the bottom of your screen.</li>";
		echo "<li>Select all project codes or de-selecte all your codes is completed by client side JAVA code</li>";
		
		echo "</ul><h4>New Pages</h4><ul>";
		echo "<li><font color=#0000ff><b>My TS Count</b></font>: a number of timesheet sent for the current month are displayed. You can view 
			your timesheet in a compressed format.</li>";
		echo "<li><font color=#0000ff><b>My Leave Record</b></font>: you can find out the details of annual and sick leave since July 1, 2000.</li>";
		//echo "</ul><h4></h4><ul>";
		//echo "<li></li>";
		exit;
	}

########################################################
#	First page without any choose selected from left panel
########################################################
if ($priv != "1000" && $email_name) {
	echo '<h2 align="center"> Intranet User'."'".'s Simple Guide</h2>
<ul>
  <li>
    <p align="left">Select a database by clicking one of the databases listed
    on the top
    of your screen;</li>
  <li>
    <p align="left">To take an action, click one of the options listed on the
    left section of your screen;</li>
  <li>
    <p align="left">If you want to abandon an action, click any other
    options, click &quot;BACK&quot; from your BROWSER'."'".'s toolbar,
    or close your BROWSER;</li>
  <li>
    <p align="left">For most of you, you can only take some actions related to you. For instance, in the case of &quot;Time
    Sheet&quot; and &quot;Inventory&quot; DB, the program tries to match your email
    name and your PC'."'".'s IP address, then takes further actions.</li>
</ul>';
}
?>
</body>
