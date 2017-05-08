<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>Collect user IP address</title>
</head>

<body background="rlaemb.JPG" leftmargin="20" topmargin="30">

<?php
include("regexp.inc");
include("phpdir.inc"); 
include('str_decode_parse.inc');
include("userinfo.inc"); //$userinfo
#echo "$userinfo<br>";

$frm_str	=	base64_encode($userinfo);
$time = date("YmdHis");

################################################
#	Forward Mail confirmation
################################################
if ($forwardmail && $email_name) {
	echo "<hr><H2>Process Mail Forward</h2>";
	
	$sql = "INSERT INTO logging.mailfwd 
        SET mailfwdid='$mailfwdid', email_name='$email_name', 
        addr='$addr', datestart='$datestart', time='$time';";
    $filename = "/usr/local/apache/htdocs/mailforward/$email_name"."_fwd_$time.qmail";
#Line 1: time to process this file
    $contents = "$datestart\n";
#Line 2: action to be taken activate/cancel the mail forward
    $contents .= "forward\n";
#Line 3: user name who requests
    $contents .= "$email_name\n";
#Line 4: file full path
    $contents .= "/home/users/$email_name/.qmail\n";
#Line 5: person name
    $contents .= "$first_name $last_name\n";
#Line 6+: new contents for the new .qmail
    $contents .= "./Maildir/\n";
    $maddrlist = explode(";", $addr);
    for ($i=0; $i<count($maddrlist); $i++) {
    	$contents .= "&".$maddrlist[$i]."\n";
    }
    if ($priv == "00") {
    	echo "$sql<br><br>";
    	echo "<b>Contents in file</b> \"$filename\"<br><br>";
    	$contentbr = ereg_replace("\n", "<br>", $contents);
    	echo "$contentbr<br>";
    }

#	entry to database
//*
    include("connet_root_once.inc");
    $result = mysql_query($sql);
    include("err_msg.inc");
//*/
#	write file
	echo "<b>Dear $first_name</b><br><br>";
	$fp	=	fopen($filename,'w+');
	if ($fp) {
		fputs($fp,$contents);
		fclose($fp);
		echo "Your mail forward request has been accepted. ".
			"You will receive a confirmation message in your mailbox on $datestart. ".
			"Please allow upto one hour delay.<br><br>";
	} else {
		echo "Sorry your mail forward request has not been accepted ".
		"due to system problem. Please ask your administrator to manually set up for you.<br><br>";
	}
	echo "Cheers<br><br>";
	echo "<b>RLA Intranet Server</b><br><br>";
}

################################################
#	form for new mail forward 
################################################
	echo "<hr><H2>Request a New Mail Forward</h2>";
	echo "<form method=\"POST\" name=frmmailforward>";
  	echo "<table>";
  	echo "<input type=hidden name=frm_str value=\"$frm_str\">";
  	echo "<tr><th align=left>Name</th><td>$first_name $last_name</td></tr>";
  	echo "<tr><th align=left>Mail Address</th><td>$email_name@rla.com.au</td></tr>";
  	echo "<tr><td align=left><b>Forward Mail To</b><br><font size=2>Use \"<font color=#ff0000><b>;</b></font>\" to seperate <br>multiple addresses.</font>
  		</td><td><textarea rows=4 cols=40 name=addr>$addr</textarea></td></tr>";
  	if (!$datestart) {
  		$datestart = date("Y-m-d");
   	}
  	echo "<tr><th align=left>From Date</th><td><input type=text name=datestart value=\"$datestart\" size=30></td></tr>";
  	echo "<tr><th colspan=2  align=center>
  	<button type=submit name=forwardmail onclick=\"return (datacheck());\"><b>Submit</b></button></th></tr>";
	echo "</table>";	
	
function msg($str) {
	echo '<h1><font color="#FF0000">You entered wrong '.$str.'. Please try again.</font></h1><br>';
}
?>

<script language=javascript>

function datacheck(){
	var tmp = document.frmmailforward.addr.value;
	var myregexp = /( )+/g;
	tmp=tmp.replace(myregexp, "");
	if ( tmp == "") {
		alert("Empty email address."); 
		return false;
	}
	tmp = document.frmmailforward.datestart.value;	
	if ( chkdate(tmp) == "-1") {
		alert("Please check date."); 
		return false;
	}
}

</script>
</body>
