<html>

<head>
<title>Mail Message Automation</title>
</head>
<body leftmargin="20" bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG">
<script language=javascript>
function changeform(){
var srcval;
	srcval = document.mailauto.msgtype.value;
	//window.alert(srcval);
	if (srcval == "Once only") {
		dend.style.display = "none";
		weekdays.style.display = "none";
		thisday.style.display = "none";
	}
	if (srcval == "Weekly" || srcval == "Monthly") {
		dend.style.display = "";
		if (srcval == "Weekly") {
			weekdays.style.display = "";
			thisday.style.display = "none";
		} else {
			weekdays.style.display = "none";
			thisday.style.display = "";
		}
	}
}

</script>
<?php
include('str_decode_parse.inc');
include("connet_other_once.inc");
include("rla_functions.inc");
include("userinfo.inc"); //$userinfo
$userstr	=	"?".base64_encode($userinfo."&action=$action");
echo "<id=top><h2 align=center>Mail Message Automation&nbsp;";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[Refresh]</font></a></h2><hr>";

if ($placemsg) {
	$datastart = $calyearstart."-".$calmonthstart."-".$caldaystart;
	$dateend = $calyearend."-".$calmonthend."-".$caldayend;
	$toperson = "";
	for ($i=0; $i<count($msgto); $i++) {
		if ($msgto[$i] == $email_name) {
			$myself = "y";
		}
		$toperson = $toperson.$msgto[$i]."@";
	}
	if (!$myself) {
		$toperson = $toperson.$email_name."@";
	}
	if ($action == "modify") {
		$procmsg = "<hr><tab"."le><table>";
	} else {
		$procmsg = "<tab"."le><table>";
	}
	$errmsg = "";
	$procmsg = $procmsg."<tr><th align=left>From</th><td>$msgfrom</td></tr>";
	if ($toperson) {
		$procmsg = $procmsg."<tr><th align=left>To</th><td>$toperson</td></tr>";
	} else {
		$errmsg = 1;
		$procmsg = $procmsg."<tr><th align=left>To</th><td><b><font color=#ff0000>Select receiver from the list.</font></td></tr>";
	}
	if ($msgsubject) {
		$procmsg = $procmsg."<tr><th align=left>Subject</th><td>$msgsubject</td></tr>";
	} else {
		$errmsg = 1;
		$procmsg = $procmsg."<tr><th align=left>Subject</th><td><b><font color=#ff0000>Type subject to the box.</font></td></tr>";
	}
	if ($msgbody) {
		$procmsg = $procmsg."<tr><th align=left>Message</th><td>$msgbody</td></tr>";
	} else {
		$errmsg = 1;
		$procmsg = $procmsg."<tr><th align=left>Message</th><td><font color=#ff0000><b>No message is entered.</b></td></tr>";
	}
	$procmsg = $procmsg."<tr><th align=left>Recurrence</th><td>$msgtype</td></tr>";
	
	if ($msgtype == "Once only") {
		$procmsg = $procmsg."<tr><th align=left>Send on</th>
			<td><b>$athour:00 o'clock, $datastart</td></tr>";
		$datesend = $datastart;
		$dateend = "";
	}
	if ($msgtype == "Weekly" || $msgtype == "Monthly") {
		$procmsg = $procmsg."<tr><th align=left>From Date</th><td>$datastart</td></tr>";
		$procmsg = $procmsg."<tr><th align=left>To Date</th><td>$dateend</td></tr>";
		if ($msgtype == "Weekly") {
			for ($i=0; $i<7; $i++) {
				if ($wkdays[$i] == $wksel[0]) {
					$wkyes = 1;
				}
			}
			if ($wkyes == 1) {
				$datesend = $wksel[0];
				$procmsg = $procmsg."<tr><th align=left>Send on</th>
					<td><b>$athour:00 o'clock, $wksel[0] every week.</b></td></tr>";
			} else {
				$procmsg = $procmsg."<tr><th align=left>Send on</th>
					<td><b><font color=#ff0000>Select weekday.</font></b></td></tr>";
			}

		} else {
			$procmsg = $procmsg."<tr><th align=left>Send on</th>
				<td><b>$athour:00 o'clock, day $spedays every month.</b></td></tr>";
			$datesend =  $spedays;
		}
	}
	$procmsg = $procmsg."</table><br>";
	if ($errmsg == 1) {
		$procmsg = $procmsg."<b><font size=4 color=#ff0000>No message has been sent to server.</b><br>";
		$procmsg = $procmsg."<hr><a href=#top>Back to top</a><br><br>";
		echo $procmsg;
		exit;
	}
	$timeplaced = date("Y-m-d H:i:s");
	if ($action == "new") {
		$sql = "INSERT INTO timesheet.auto_notice SET msgid='$msgid', 
		msgfrom='$msgfrom', msgto='$toperson', msgsubject='$msgsubject', 
		msgbody='$msgbody', msgtype='$msgtype', datastart='$datastart', 
		dateend='$dateend', datesend='$datesend', hour='$athour', 
		timeplaced='$timeplaced', active='$active';";
	} else {
		$sql = "UPDATE timesheet.auto_notice SET  
		msgfrom='$msgfrom', msgto='$toperson', msgsubject='$msgsubject', 
		msgbody='$msgbody', msgtype='$msgtype', datastart='$datastart', 
		dateend='$dateend', datesend='$datesend', hour='$athour', 
		timeplaced='$timeplaced', active='$active' WHERE msgid='$msgid';";
	}

	$result = mysql_query($sql);
	include("err_msg.inc");
	$procmsg = $procmsg."$sql<br><b><font size=4>Above message has been sent to server sucessfully.</b><br>";
}

if ($action == "new" && !$placemsg) {
	if ($priv == "00") {
		$msgsubject = "RE: mail automation test";
		$msgbody = "This is only a test for mail automation placement.";
	}
	include("mail_buildmsgtable.inc");
}

if ($action == "modify") {
	echo "<form method=post action=\"$PHP_SELF\" name=\"mailauto\">";
	include("userstr.inc");
	echo "<input type=hidden name=\"msgfrom\" value=\"$email_name\">";
	echo "<input type=hidden name=\"action\" value=\"$action\">";
	$sql = "SELECT msgid, msgsubject, timeplaced FROM timesheet.auto_notice 
		WHERE msgfrom='$email_name' ORDER BY timeplaced DESC;";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	echo "<table>";
	
	echo "<tr><td><b>My previous list ($no)</td><td><select name=\"mylist\">";
	while (list($msgid, $msgsubject, $timeplaced) = mysql_fetch_array($result)) {
		if ($mylist == $msgid) {
			echo "<option value=\"$msgid\" selected>$timeplaced. $msgsubject";
		} else {
			echo "<option value=\"$msgid\">$timeplaced. $msgsubject";
		}
	}
	echo "</option></select></tr>";
	echo "<tr><td colspan=2>&nbsp;</td></tr>";
	echo "<tr><td colspan=2 align=middle><button type=\"submit\" name=\"prelist\">
		<font size=4><b>Show Details</b></font></button></td></tr>";
	echo "</table></form>";
}

if ($action == "modify" && $prelist) {
	echo "<hr>";
	$sql = "SELECT msgid, msgfrom, msgto, msgsubject, msgbody, msgtype, datastart, 
		dateend, datesend, hour, timeplaced, active, sentctrtime FROM 
		timesheet.auto_notice  WHERE msgid='$mylist'";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($msgid, $msgfrom, $msgto, $msgsubject, $msgbody, $msgtype, $datastart, 
		$dateend, $datesend, $hour, $timeplaced, 
		$active, $sentctrtime) = mysql_fetch_array($result);
	$ename = explode("@",$msgto);
	include("mail_buildmsgtable.inc");
}

echo "</form>";
echo $procmsg;
echo "<hr><a href=#top>Back to top</a><br><br>";
?>
</body>
