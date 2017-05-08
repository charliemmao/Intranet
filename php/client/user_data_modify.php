<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>Modify User Registration Data</title>
</head>

<body background="rlaemb.JPG" leftmargin="20">

<?php
#########################################################
#		parsing string
#########################################################
include('str_decode_parse.inc');
/*
	echo 'rcdaction: '.$rcdaction.'<br>';
	echo 'dbname: '.$dbname.'<br>';
	echo 'tablename: '.$tablename.'<br>';
	echo 'fldname: '.$fldname.'<br>';
	echo 'fldvalue: '.$fldvalue.'<br>';
	echo 'patch: '.$patch.'<br>';
	include('user_info.inc');
//*/
    
#########################################################
#		connect to database
#########################################################
    include("connet_other_once.inc");
    mysql_select_db($dbname,$contid);

#################################################
#	build form to modify data
#################################################
if ($rcdaction	== "modify") {
	echo '<form method="post" action="'.$PHP_SELF.'">';
	//echo "here<br>";
	
	include('display_user_registration.inc'); 
	
	//exit;
	$basestr	=	 '&dbname='.$dbname.'&tablename='.$tablename
				.'&fldname='.$fldname.'&fldvalue='.$fldvalue
				.'&userpwd='.$userpwd
				.'&email_name='.$email_name.'&logon_name='.$logon_name
				.'&title='.$title.'&first_name='.$first_name
				.'&middle_name='.$middle_name.'&last_name='.$last_name;
	$frm_str =   $basestr;
	//echo $frm_str.'<br>';
 
	$frm_str	=	base64_encode($frm_str);
	echo '<input type="hidden" name="frm_str" size="20" value= "'.$frm_str.'">';

  	echo '<input type="submit" value="UPDATE" name="update"></p>';
 	echo '</form>';
	exit;
}

#################################################
#	precess form data
#################################################
if ($update) {
#	parse form data
	$offset	=	3;
	include('collect_form_value.inc');
    $rcdstr = " SET ".$frm_key_val_list;
	//echo $frm_key_val_list.'<br><br>';

#	check form data integraty
	$msg_chk = 'The following fields are empty:';
	if ($email_name) {
		$email_name= strtolower($email_name);
	} else {
		$msg_chk = $msg_chk.'<br>email name';
	}
	if (trim($logon_name) == "") {
		$msg_chk = $msg_chk.'<br>your logon name';
	}

	if (trim($title) == "") {
		$msg_chk = $msg_chk.'<br>your title';
	}
	
	if (trim($first_name) == "") {
		$msg_chk = $msg_chk.'<br>your first name';
	}
	
	if (trim($last_name)  == "") {
		$msg_chk = $msg_chk.'<br>your last name';
	}
	
	if ($msg_chk == 'The following fields are empty:') {
		$msg_chk	=	'';
	}
	
    if ($msg_chk != ''){
    	echo "<H2>Hi, New Intranet User</H2>";
    	echo "<H4><br>".$msg_chk."</H4>";
		echo '<H2><br>Please refill your registration form by click "BACK" on your browser'
			."'s toolbar.</H2>";
		exit;
	}

#	find email address from mysql DB
	include('findemailaddr.inc');
	if ($out	!=	$email_name) {
    	echo "<H2>Hi, New Intranet User</H2>";
		echo '<h4>'.$email_name.' is not a registrated email name.</h4>';
		echo '<H4>Please refill your registration form by click "BACK" on your browser'
			."'s toolbar.</H4>";
		exit;
	}

	$sql = "UPDATE ".$dbname.'.'.$tablename;
	$sql = $sql.$rcdstr;
	$sql = $sql." WHERE ".$fldname."='".$fldvalue."';";
	
    include("connet_other_once.inc");
   //echo "Query statement:<br>".$sql."<br>";
    	$result = mysql_query($sql);
    	include('err_msg.inc');
    	//echo "Query results: ".$result."<br>";
		include('addressto.inc');
	
    	if (!$result) {
    	 	echo '<font color="#FF0000">';
        	echo "<H2><br>Your registration data has been failed to update.</H2>";
        	echo '</font>';
    	} else {
    	 	echo '<font color="#0000FF">';
          	echo "<H2><br>Your registration data has been updated successfully.</H2>";
        	echo '</font>';
    	}	}
?>

</body>
