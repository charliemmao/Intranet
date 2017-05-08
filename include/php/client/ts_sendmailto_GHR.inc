<html>
<?php
include("phpdir.inc");
if ($priv == "00" || $priv == "10") {
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
	$dir = "/usr/local/apache/htdocs/report/";
	$strzip="$email_name"."_".$datestr."_".strtolower($rpttypeS);
	$ghrzip = $strzip."_ghr.tar.gz";
	$allcsvzip = $strzip."_allcsv.tar.gz";

	/*
	echo "$dir<br>";
	echo "$ghrzip<br>";
	echo "$allcsvzip<br>";
	echo "$mailmsg mailmsg<br>";
	//*/
	include('connet_root_once.inc');
	$sql = "SELECT description as domain FROM logging.sysmastertable WHERE item='Domain_Name';";
	$result = mysql_query($sql);
    include("err_msg.inc");
    list($domain) = mysql_fetch_array($result); 
    //$domain ="rla.com.au";

	if ($mailmsg == "display") {
		echo "<H2>Send timesheet report to GHR</h2>";	
		echo "<a id=mailsection></a>";

		$sql = "SELECT description as sendto FROM logging.sysmastertable WHERE item='mailto';";
		$result = mysql_query($sql);
    	include("err_msg.inc");
		list($sendto) = mysql_fetch_array($result);
		$ss = explode(";", $sendto);
		$sendto = $ss[0];
		$sf = $ss[1];
		$dev = $ss[2];
		$to = $sendto;
		if (!$to) {
			//$to="sfitz@dawcorp.com.au";
		}
		
		$notestoGHR .= "File \"".$filelist["csv"][4]."\" includes all RLA individual project & group codes. ".
			"All numerical data has unit of minute. ".
			"File \"".$filelist["csv"][5]."\" is generated specifically for GHR including two sections, section I ".
			"contains data in percentage, section II contains data in minute, which are used to compute section I.\n\n" ;

		$subject = "RE: Timesheet Summary for $TOGHRTIME";
		$msgbody = "Hi $sf,\n\nPlease find attached Timesheet Summary for $TOGHRTIME.\n\n".
			"Remarks (from $dev):\n$notestoGHR\n".
			"Best Regards\n\n\n\n$first_name $last_name\n".
			"Research Laboratories of Australia Pty Ltd\n".
			"7 Valetta Rd, Kidman Park, SA 5025\n".
			"Australia\n".
			"Tel:   (618) 8352 1322\n".
			"Email: $email_name@$domain\n\n".
			"The information contained in this e-mail includes privileged and/or confidential information. ".
			"If you are not an intended recipient, you must not copy, distribute or take any action in ".
			"reliance on it.  If you have received this e-mail in error please notify us immediately by ".
			"a collect telephone call to (08) 8352 1322;  International + 618 8352 1322 or by e-mail ".
			"at rla@$domain.\n\n";
		echo "<form method=post action=\"$PHP_SELF\">";
		echo "<table>";
		include("userstr.inc");
		echo "<input type=hidden name=\"TOGHRTIME\" value=\"$TOGHRTIME\">";
		echo "<input type=hidden name=\"dir\" value=\"$dir\">";
		echo "<input type=hidden name=\"ghrzip\" value=\"$ghrzip\">";
		echo "<input type=hidden name=\"allcsvzip\" value=\"$allcsvzip\">";
		echo "<tr><th align=left valign=top>To</th><td>
			<input name=\"to\" value=\"$to\" size=60></td></tr>";
		    	
		$sql = "SELECT email_name as ename FROM logging.accesslevel WHERE priviledge='10';";
		$result = mysql_query($sql);
    	include("err_msg.inc");
    	$tmp="";

		while (list($email) = mysql_fetch_array($result)) {
			$tmp="$tmp$email@$domain;";
		}

		echo "<tr><th align=left valign=top>CC</th><td>
			<input name=\"sendcopyto\" value=\"$tmp\" size=60></td></tr>";
		
		echo "<tr><th align=left valign=top>Subject</th><td>
			<input name=\"subject\" value=\"$subject\" size=60></td></tr>";
		echo "<tr><th align=left valign=top>Attachment</th><td>
			<input name=\"attachment\" value=\"$ghrzip\" size=60></td></tr>";
					
		echo "<tr><th align=left valign=top>Message</th><td>
			<textarea name=\"msgbody\" cols=70 rows=15>$msgbody</textarea></td></tr>";

		echo "<tr><td colspan=2 align=middle>
			<button type=\"submit\" name=\"sendreadymsg\" onClick=\"return confirm('Do you really want to send this message?');\">
			<font size=4>Send The Above Message Now</font></button></td></tr>";
		echo "</table></form><p>";
	} elseif ($mailmsg == "send") {
		//check whether the recipetant has been changed
		include("connet_root_once.inc");
		$sql = "SELECT description as sendto FROM logging.sysmastertable WHERE item='mailto';";
		$result = mysql_query($sql);
    	include("err_msg.inc");
		list($sendto) = mysql_fetch_array($result);
		if ($sendto != $to) {
			$sql = "UPDATE logging.sysmastertable SET description='$to' WHERE item='mailto';";
			//echo "$sendto != $to <br>$sql<br>";
			$result = mysql_query($sql);
    		include("err_msg.inc");
		}
		
   		//"File Attachment Info" is an array:
   		$file1 = "$dir$attachment";
		$filearray[0] = array("filename" => $file1 , "mimetype" => "application/x-zip-compressed");
   		/*
    	$file1 = "/usr/local/apache/htdocs/report/test2.zip";
		$filearray[1] = array("filename" => $file1 , "mimetype" => "application/x-zip-compressed");
   		$file1 = "/usr/local/apache/htdocs/report/csv.csv";
		$filearray[2] = array("filename" => $file1 , "mimetype" => "text/plain");
   		$file1 = "/usr/local/apache/htdocs/report/tml_MthSum_Mar_2001_People_Project(hours).html";
		$filearray[3] = array("filename" => $file1 , "mimetype" => "text/html"); //<
		//*/
		
//send mail with attachment to Greenhill Rd
		$firstmailto = $to;
		//$to = "$to spatterson@rokset.com.au";
		//echo "To: $to<br><br>";
		//exit;
		$extraheaders="From: $email_name@$domain\nReply-To: $email_name@$domain\nX-Mailer: $PHPconst/" . phpversion();
		mailattachments($to, $subject, $msgbody, $filearray, $extraheaders, $email_name);
		//mailattachments("cmao@rokset.com.au", $subject, $msgbody, $filearray, $extraheaders, $email_name);

		$sql = "SELECT description as domain FROM logging.sysmastertable WHERE item='Domain_Name';";
		$result = mysql_query($sql);
    	include("err_msg.inc");
    	list($domain) = mysql_fetch_array($result); 
    			
//send copy to others (mamngement)
		$tmp = explode(";", $sendcopyto);
		$cc = ereg_replace(";", " ", $sendcopyto);
		for ($i=0; $i<count($tmp); $i++) {
			$to0 = trim($tmp[$i]);
			if ($to0 != "") {
				//mailattachments($to0, $subject, $msgbody, $filearray, $extraheaders, $email_name);
			}
		}

		//send mail to administrator
		$sql = "SELECT email_name as ename FROM logging.accesslevel WHERE priviledge='00';";
		$result = mysql_query($sql);
    	include("err_msg.inc");
    	list($ename) = mysql_fetch_array($result); 
		$to0 = $ename."@".$domain;
		$cc = $cc." $to0";
		//echo "CC: $cc<br><br>";
		$extraheaders="From: $email_name@$domain\nReply-To: $email_name@$domain\nCC: $cc\nX-Mailer: $PHPconst/" . phpversion();
		mailattachments($to, $subject, $msgbody, $filearray, $extraheaders, $email_name);
		
		//print mail message to screen
		$msgbody = ereg_replace("\n", "<br>", $msgbody);
		echo "<BR><BR><hr><table border=0>";
		echo "<tr><th align=left valign=top>TO</th><td>$to</td></tr>";
		echo "<tr><th align=left valign=top>CC</th><td>$cc</td></tr>";
		echo "<tr><th align=left valign=top>Subject</th><td>$subject</td></tr>";
		echo "<tr><th align=left valign=top>Attachment</th><td>$attachment</td></tr>";
		echo "<tr><th align=left valign=top>Message</th><td>$msgbody</td></tr>";
		echo "</table>";
		//echo "<h2>The above message has been sent successfully.</h2>";
		
		//make a flag to prevent further modification to timesheet DB
		if ($priv == "10")  {
			include("connet_root_once.inc");
			$sql = "UPDATE logging.sysmastertable SET description='N' WHERE item='listLMTS';";
			$result = mysql_query($sql);
			include("err_msg.inc");
			//echo "<h2>Last month's timesheet DB has been locked to prevent modification.</h2>";
		}
	}
}
?>
</html>
