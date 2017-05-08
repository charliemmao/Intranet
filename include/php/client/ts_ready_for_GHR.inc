<html>
<?php
include("find_admin_ip.inc");
if ($privspec == 10 || $email_name == $adminname) {
	if ($rpttypeS == "MthSum") {
		$str = substr($fromdate,0,7);
		$str = ereg_replace("-","",$str);
	} elseif ($rpttypeS == "QtrSum") {
		$str = $selyear."_".$qtrfour[$selopd];
	} elseif ($rpttypeS == "HfySum") {
      	if ($selopd ==0 ) {
			$str = $selyear."_Jan-June";
		} else {
			$str = $selyear."_Jul-Dec";
		}
	} elseif ($rpttypeS == "YlySum") {
		$str = $selyear;
		$selyear++;
		$str = $str."-".$selyear;
	} elseif ($rpttypeS == "SlpSum") {
		$strs = substr($fromdate,0,7);
		$strs = ereg_replace("-","",$strs);
		$stre = substr($todate,0,7);
		$stre = ereg_replace("-","",$stre);
		$stry = substr($stre,0,4);
		$strm = substr($stre,4,6);
		if ($strm == "01") {
			$stry = $stry-1;
			$strm = "12";
		} else {
			$strm--;
			if ($strm<10) {
				$strm = "0".$strm;
			}
		}
		$stre = $stry.$strm;
		$str=$strs."-".$stre;
	}
	$datestr = $str;//HTTP_HOST	server_name
	$strzip = "http://".getenv('HTTP_HOST')."/report/$email_name"."_".$datestr."_".strtolower($rpttypeS);
	$ghrzip = $strzip."_ghr.zip";
	$allcsvzip = $strzip."_allcsv.zip";

	if ($mailmsg == "display") {
		$to = "$adminname@rla.com.au";
		$subject = "RE: Timesheet $TOGHRTIME Ready For GHR";
		$msgbody=	"Hi Charlie\n\nI have checked the timesheet summary for $TOGHRTIME. ";
		$msgbody=	$msgbody."It is OK to send them to Greenhill Rd.\n";
		$msgbody=	$msgbody."\nRegards\n\n$first_name $last_name\n".date("d/m/Y H:i");
		$msgbody=	$msgbody.	"\n\nZipfile can be downloaded at:\n$ghrzip\n$allcsvzip\n";
			
		echo "<form method=post action=\"$PHP_SELF\">";
		echo "<table>";
		include("userstr.inc");
		echo "<input type=hidden name=\"TOGHRTIME\" value=\"$TOGHRTIME\">";
		echo "<input type=hidden name=\"ghrzip\" value=\"$ghrzip\">";
		echo "<input type=hidden name=\"allcsvzip\" value=\"$allcsvzip\">";
		echo "<tr><th align=left valign=top>To</th><td>
			<input name=\"to\" value=$to></td></tr>";
		echo "<tr><th align=left valign=top>Subject</th><td>
			<input name=\"subject\" value=\"$subject\" size=60></td></tr>";
		echo "<tr><th align=left valign=top>Message</th><td>
			<textarea name=\"msgbody\" cols=70 rows=10>$msgbody</textarea></td></tr>";

		echo "<tr><td colspan=2 align=middle>
			<button type=\"submit\" name=\"sendreadymsg\" onClick=\"return confirm('Do you really want to send this message?');\">
			<font size=4>Send The Above Message Now</font></button></td></tr>";
		echo "</table></form><p>";
	} elseif ($mailmsg == "send") {
		$header	=	"From: $email_name@rla.com.au\nReply-To: $email_name@rla.com.au\n";
		//mail ($to, $subject, $msgbody, $header);
		$from = "$email_name\@rla.com.au";
		$to = "$adminname\@rla.com.au";
		$cc = "";
		//$subject = "";
		$msg = "$msgbody";
		system("./_mail.pl \"$from\" \"$to\" \"$cc\" \"$subject\" \"$msg\"",$var);

		$msgbody = ereg_replace("\n", "<br>", $msgbody);
		echo "<BR><BR><hr><table border=0>";
		echo "<tr><th align=left valign=top>To</th><td>$to</td></tr>";
		echo "<tr><th align=left valign=top>Subject</th><td>$subject</td></tr>";
		echo "<tr><th align=left valign=top>Message</th><td>$msgbody</td></tr>";
		echo "</table>";
		echo "<h2>The above message has been sent successfully.</h2>";
	//echo "$ghrzip<br>";
	//echo "$allcsvzip<br>";

		$header	=	"From: admin@".getenv("server_name")."\nReply-To: admin@".getenv("server_name")."\n";
		//$to = "$adminname@rla.com.au";
		$subject = "RE: Timesheet for $TOGHRTIME";
		$msgbody = "Hi, Sarah sphelan@dawcorp.com.au\n\nPlease find attached Timesheet Summary for $TOGHRTIME.\n\n".
			"Best Regards\n\n\n\nCharlie\n".
			"Research Laboratories of Australia Pty Ltd\n".
			"7 Valetta Rd, Kidman Park, SA 5025\n".
			"Australia\n".
			"Tel:   (618) 8352 1322\n".
			"Email: $adminname@rla.com.au\n\n".
			"The information contained in this e-mail includes privileged and/or confidential information. ".
			"If you are not an intended recipient, you must not copy, distribute or take any action in ".
			"reliance on it.  If you have received this e-mail in error please notify us immediately by ".
			"a collect telephone call to (08) 8352 1322;  International + 618 8352 1322 or by e-mail ".
			"at rla@rla.com.au.\n\n";			
		//mail ($to, $subject, $msgbody, $header);
		$from = "admin\@rla.com.au";
		$to = "admin\@rla.com.au";
		$cc = "";
		//$subject = "";
		$msg = "$";
		system("./_mail.pl \"$from\" \"$to\" \"$cc\" \"$subject\" \"$msg\"",$var);

		if ($email_name == "$adminname") {
			$msgbody = ereg_replace("\n", "<br>", $msgbody);
			echo "<BR><BR><hr><table border=0>";
			echo "<tr><th align=left valign=top>To</th><td>$to</td></tr>";
			echo "<tr><th align=left valign=top>Subject</th><td>$subject</td></tr>";
			echo "<tr><th align=left valign=top>Message</th><td>$msgbody</td></tr>";
			echo "</table>";
		}
		
		//make a mark to prevent further modification to timesheet DB
		if ($email_name != "$adminname") {
			include("connet_root_once.inc");
			$sql = "UPDATE logging.sysmastertable SET description='N' WHERE item='listLMTS';";
			$result = mysql_query($sql);
			include("err_msg.inc");
		}
	}
}
?>
</html>
