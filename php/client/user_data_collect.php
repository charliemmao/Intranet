<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>Collect user IP address</title>
</head>
<!--
<SCRIPT LANGUAGE=JAVASCRIPT>
FUNCTION BACKTO() {
	back;
	//history.go(-1);
}
</SCRIPT>
-->
<body background="rlaemb.JPG" leftmargin="20">

<?php
include("phpdir.inc"); 
#########################################################
#		parsing string
#########################################################
include('str_decode_parse.inc');
/*
	echo 'rcdaction: '.$rcdaction.'<br>';
	echo 'fldname: '.$fldname.'<br>';
	echo 'fldvalue: '.$fldvalue.'<br>';
	include('user_info.inc');
//*/
    
#########################################################
#		connect to database
#########################################################
    include("connet_anyone.inc");
    mysql_select_db($dbname,$contid);

#################################################
#	process collected data
#################################################
if ($send) {
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

    include("connet_root_once.inc");
	$sqldm = "SELECT description as dm 
        			FROM logging.sysmastertable 
        			WHERE item='Domain_Name'";
   $resdm = mysql_query($sqldm);
   list($dm) = mysql_fetch_array($resdm);
	if ($email_name != ereg_replace("@"."$dm", "", $email_name)) {
    	echo "<H2>Hi $email_name, New Intranet User</H2>";
		echo "<H2><br>Please remove @$dm from your email name box.</H2>";
		exit;
	}
	if ($email_name != ereg_replace("$dm", "", $email_name)) {
    	echo "<H2>Hi $email_name, New Intranet User</H2>";
		echo "<H2><br>Please remove $dm from your email name box.</H2>";
		exit;
	}
	if ($email_name != ereg_replace("@", "", $email_name)) {
    	echo "<H2>Hi $email_name, New Intranet User</H2>";
		echo "<H2><br>Please remove @ from your email name box.</H2>";
		exit;
	}

#	find email address from mysql DB
	if ($email_name == "root") {
    	echo "<H2>Hi, New Intranet User</H2>";
		echo '<h4>'.$email_name.' is not a registrated email name.</h4>';
		echo '<H4>Please refill your registration form by click "BACK" on your browser'
			."'s toolbar.</H4>";
		exit;
	}
	include('findemailaddr.inc');
	if ($out	!=	$email_name) {
    	echo "<H2>Hi, New Intranet User</H2>";
		echo '<h4>'.$email_name.' is not a registrated email name.</h4>';
		echo '<H4>Please refill your registration form by click "BACK" on your browser'
			."'s toolbar.</H4>";
		exit;
	}
	
#	decide this is a new record or old record
	mysql_select_db($dbname,$contid);
	$ip = getenv("remote_addr");
	$sql    =   "SELECT * FROM ".$dbname.'.'.$tablename." WHERE computer_ip_addr='".$ip."';";
	$result = mysql_query($sql,$contid);
	include('err_msg.inc');
	$no =   mysql_num_rows($result);
	//echo $no.'<br>'; 
	//echo $rcdstr.'<br>';

	//echo $rcdaction.'<br>';
#	compose query string
	if ($rcdaction	== "modify") {
		#	Part A: modify record, not active
		//echo "It is a modification<br>";
        $newold="Update";
        $sql = "UPDATE ".$tablename;
        $sql = $sql.$rcdstr;
        $sql = $sql." WHERE ".$fldname."='".$fldvalue."';";
	
	} elseif ($rcdaction	== "addrcd") {
		#	Part B: Add new record or update
		if ($no) {
			//echo "should update.<br>";
			//echo "It is a New Record<br>";
        	$newold="Update";
        	$sql = "UPDATE ".$tablename;
        	$sql = $sql.$rcdstr;
        	$sql = $sql." WHERE computer_ip_addr='".$computer_ip_addr."';";
		} else {
			//echo "It is a New Record<br>";
        	$newold="Insert";
        	$sql = "INSERT INTO ".$tablename;
        	$sql = $sql.$rcdstr.';';
       }
	}	
    /*
    echo "Query statement:<br>".$sql."<br>";
    exit;
    //*/
    
 #		mysql query
		include('connet_root_once.inc');
    	$result = mysql_query($sql);
    	include('err_msg.inc');
    	//echo "Query results: ".$result."<br>";
		include('addressto.inc');
	
	if (!$result) {
    	 echo '<font color="#FF0000">';
        if ($newold == "Update"){
            echo "<H2><br>Failed to update.</H2>";
        }else{
            echo "<H2><br>Failed to register.</H2>";
        }
        echo '</font>';
        exit;
        
	} else {
    	 echo '<font color="#0000FF">';
        if ($newold == "Update"){
            echo "<H2><br>Update Successful.<br>Please check your updated registration data.</H2>";
        } else {
            echo "<H2><br>Your registration has been entered successfully.</H2>";
            echo "<br><br><b>Regards,<br><br><br><br>Intranet Administrator</b><br><br>";
        }
        echo '</font>';
	}
 	
	flush();
	sleep(2);
	echo "<script language=\"javascript\">";
		echo "window.location=\"http://".getenv("server_name")."\";";
	echo "</script>";
	exit;
	
 	$fldname	=	'computer_ip_addr';
	$fldvalue	=	$computer_ip_addr;
	$rcdaction	=	'modify';
	mysql_close($contid);
  echo '<form method="post" action="/'.$phpdir.'/user_logon.php">';
	//include('display_user_registration.inc'); 
	$rcdaction	=	'addrcd';
	$basestr	=	 '&dbname='.$dbname.'&tablename='.$tablename
				.'&fldname='.$fldname.'&fldvalue='.$fldvalue
				.'&email_name='.$email_name.'&logon_name='.$logon_name
				.'&title='.$title.'&first_name='.$first_name
				.'&middle_name='.$middle_name.'&last_name='.$last_name.'&dummy=dummy';
	$frm_str =   'rcdaction='.$rcdaction.$basestr;
	//echo $frm_str.'<br>';   
 
	$frm_str	=	base64_encode($frm_str);
	echo '<input type="hidden" name="frm_str" size="20" value= "'.$frm_str.'">';

/*
  echo '  
  <h2 align="center"><font face="Courier New"><b>Now you can modify your
  registration data</b></font></h2><p align="center">
  <input type="submit" value="modify" name="modify"></p>';
*/

  echo '<h2 align="left"><font face="Courier New"><b>Now you can logon to RLA
  Intranet</b></font></h2>
  <p align="left">';
    echo '<input type="submit" value="Logon" name="logon0">';
    echo '</p>
</form>';
      
	exit;
}

#################################################
#	prepare form for data collection
#################################################
/*
	echo 'rcdaction: '.$rcdaction.'<br>';
	echo 'dbname: '.$dbname.'<br>';
	echo 'tablename: '.$tablename.'<br>';
	echo 'email_name: '.$email_name.'<br>';
	echo 'logon_name: '.$logon_name.'<br>';
	echo 'title: '.$title.'<br>';
	echo 'first_name: '.$first_name.'<br>';
	echo 'middle_name: '.$middle_name.'<br>';
	echo 'last_name: '.$last_name.'<br>';
	echo 'fldname: '.$fldname.'<br>';
	echo 'fldvalue: '.$fldvalue.'<br>';
//*/

if ($email_name	== "") {
	#Part A: heading for new registration
	echo '<h1>Hi, New Intranet User</h1>
	<h2>Please fill the following "Intranet User Registration Form" and 
	<font color=#0000ff>Send</font> to MySQL database server.</h2>';
	
} else {
	#Part B: heading for registration update
	include('addressto.inc');
/*
	if ($title == "" ) {
		echo '<h2>Hi, '.' '.$first_name.'</h2>';
	} else {
		echo '<h2>Hi, '.$title.' '. $last_name.'</h2>';
	}
*/
	echo '<p>Please feel free to make any change to your Intranet User Registration Form and <b>Send</b> to MySQL database server.</p><br>';
}

echo '<form method="post" action="'.$PHP_SELF.'">';
//echo $frm_str.'frm_str<br>';   
	
include('display_user_registration.inc'); 
$basestr	=	 '&dbname='.$dbname.'&tablename='.$tablename
				.'&fldname='.$fldname.'&fldvalue='.$fldvalue.'&dummy=dummy';
/*
				.'&email_name='.$email_name.'&logon_name='.$logon_name
				.'&title='.$title.'&first_name='.$first_name
				.'&middle_name='.$middle_name.'&last_name='.$last_name;
*/
$frm_str =   'rcdaction='.$rcdaction.$basestr;
 
$frm_str	=	base64_encode($frm_str);
echo '<input type="hidden" name="frm_str" size="20" value= "'.$frm_str.'">';
?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="submit" value="Send" name="send">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<!--<input type="reset" value="Reset" name="reset">-->
</form>
<p>&nbsp;</p>
<p>Thank you for your cooperation.</p>

<p font size="3">Intranet Administrator</font></p>

<?php
	echo date("F d, Y");
?>

</body>
