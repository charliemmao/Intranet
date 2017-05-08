<html>

<head>
<meta http-equiv="Content-Language" content="en-au">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>user left page</title>
<base target="main">
</head>

<body background="rlaemb.JPG" topmargin="4" leftmargin="4">

<?php
include('getusersec.inc');

include('str_decode_parse.inc');
include("islogon.inc");
include("userinfo.inc"); //$userinfo
include("find_domain.inc");	
include("find_admin_ip.inc");

	$qry0	=	$userinfo.'&dbname='.$dbname.'&dummy1=dummy';
	if ($title == "" ) {
		$add = 'Hi, '.' '.$first_name;
	} else {
		$add = 'Hi, '.$title.' '. $last_name;
	}
	
if ( $rlaserver == $thisserver ) {
	//include("dev_only.inc");
	if ($priv == "00"){
		echo "<hr>";
		echo "<font size=2><b>Developer's Corner</b></font>";
		$qry	=	base64_encode($qry0);
		$statuscontext = "adminctl.php (Database managemant)";
		include("self_status.inc"); //'.$status.'
		echo '<ul><li><a href="adminctl.php?'.$qry.'" target="_blank"'.$status.'>'
		.'<font size="2" align="center">Database Setup</font></a></li>';

		$statuscontext = "uploadfile.php (Upload file to server)";
		include("self_status.inc"); //'.$status.'
		echo '<br><li><a href="uploadfile.php?'.$qry.'" target="main"'.$status.'>'
		.'<font size="2" align="center">Upload File</font></a></li>';

		$statuscontext = "lib_search_pat.php (Library Patent Search)";
		include("self_status.inc"); //'.$status.'
		echo '<br><li><a href="lib_search_pat.php?'.$qry.'" target="main"'.$status.'>'
		.'<font size="2" align="center">Library Patent Search</font></a></li>';

/*
		$statuscontext = "java_code_test.php (Test JAVAScript).";
		include("self_status.inc"); //'.$status.'
		echo '<br><li><a href="java_code_test.php?'.$qry.'" target="main"'.$status.'>'
		.'<font size="2" align="center">Test JAVAScript</font></a></li>';
		
		$statuscontext = "html_to_js_generator.php (HTML to JavaScript Generator)";
		include("self_status.inc"); //'.$status.'
		echo '<br><li><a href="html_to_js_generator.php" target="main"'.$status.'>'
		.'<font size="2" align="center">JavaScript Generator</font></a></li>';

//*/
		$statuscontext = "program_test.php (Program Test)";
		include("self_status.inc"); 
		echo '<br><li><a href="program_test.php?'.$qry.'" target="main"'.$status.'>'
		.'<font size="2" align="center">Test Program</font></a></li>';

		$statuscontext = "pg_test_program.php (PG Test)";
		include("self_status.inc"); 
		echo '<br><li><a href="pg_test_program.php?'.$qry.'" target="main"'.$status.'>'
		.'<font size="2" align="center">Test PG</font></a></li>';
				
		echo "</ul>";
	}
}
	if (!$dbname) {
		$dbname = $defaultdb;
	}
	
	if ($dbname) {
		//echo '<hr>';
		$dbname1 = strtoupper($dbname);
		//echo '<align="left"><font size="2"><b>Database: '.$dbname1.'</b></font><br><br>';
		if ($dbname=='timesheet') {
			include('client_timesheet.inc');
		} elseif ($dbname=='rlafinance') {
			include('client_rlafinance.inc');
		} elseif ($dbname=='inventory') {
			include('client_inventory.inc');
		} elseif ($dbname=='library') {
			include('client_library.inc');
		}
	}
	
	
	if ($email_name) {
		echo '<hr>';
		//if ($priv == "0000") {
		//	echo '<align="left"><font size="2">'.
		//	"<a href=\"rlaemployeehandbook.php\" target=_blank>Employee Handbook</a></font><br>";
		//} else {
			echo '<align="left"><font size="2">'.
			"<a href=\"viewrladoc.php\" target=_blank>RLA Policies</a></font><br>";
		//}
		
		//modify registration data
		$qry0	=	$qry0.'&rcdaction=modify&dbname=timesheet';
		$qry0	=	$qry0.'&fldname=computer_ip_addr&fldvalue='.getenv('remote_addr');
		$qry0	=	$qry0.'&tablename=employee&dummy=dummy';		
		//echo $qry0.'<br>';
		$qry	=	base64_encode($qry0);
		
		$statuscontext = "Forward Mail.";
		if ($priv == "00") {
			$statuscontext = "staffmailforward.php ($statuscontext)";
		}
		include("self_status.inc"); //'.$status.'
		echo '<align="left"><font size="2"><a href="staffmailforward.php?'.
	 	$qry.'target="main"'.$status.'>[New]</a></font>';

		$statuscontext = "Cancel Mail Forward.";
		if ($priv == "00") {
			$statuscontext = "staffmailcancelforward.php ($statuscontext)";
		}
		include("self_status.inc"); //'.$status.'
		echo '<align="left"><font size="2"><a href="staffmailcancelforward.php?'.
	 	$qry.'target="main"'.$status.'>[Cancel]</a>';
	 	echo " Mail Forward</font><br>";
		
		//check my email account
		$statuscontext = "Ckeck My Email Record.";
		if ($priv == "00") {
			$statuscontext = "user_email_rcd.php ($statuscontext)";
		}
		//if ($priv == "00" || $priv == "10" || $priv == "20") {
		//if ($priv == "00") {
			include("self_status.inc"); //'.$status.'
			echo '<align="left"><font size="2"><a href="user_email_rcd.php?'.
	 		$qry.'target="main"'.$status.'>My Email Log</a></font><br>';
		//}
		
		//Change password
		$statuscontext = "Change your password.";
		if ($priv == "00") {
			$statuscontext = "user_pwd_change.php ($statuscontext)";
		}
		include("self_status.inc"); //'.$status.'
		echo '<align="left"><font size="2"><a href="user_pwd_change.php?'.
	 	$qry.'target="main"'.$status.'>Change Password</a></font><br>';
		
		$statuscontext = "Modify your registration details.";
		if ($priv == "00") {
			$statuscontext = "user_data_modify.php ($statuscontext)";
		}
		include("self_status.inc"); //'.$status.'
		echo '<align="left"><font size="2"><a href="user_data_modify.php?'.
	 	$qry.'target="main"'.$status.'>Change Registration Data</a></font><br>';

		//logout
		$qry	=	"?".base64_encode("&priv=$priv&email_name=$email_name&first_name=$first_name");
		$statuscontext = "Logout.";
		if ($priv == "00") {
			$statuscontext = "user_logout.php ($statuscontext)";
		}
		include("self_status.inc"); //'.$status.'
		echo '<align="left"><font size="2">';
		if ($priv == "00") {
			echo '<a href="user_logout.php'.$qry.'" target="_top"'.$status.'>LOGOUT</a></font><br><hr>';
		} else {
			echo '<a href="user_logout.php'.$qry.'" target="_top"'.$status.'>LOGOUT</a></font><br><hr>';
		}
	} 
?>

</body>
</html>