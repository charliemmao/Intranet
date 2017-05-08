<html>

<?php

Function mailattachments( $to, $subject, $body, $attacharray, $extraheaders, $from)
   {
     // Generate a unique boundary
     $mail_boundary = "=_NextPart_".md5(uniqid(time()));

     // MIME-compliant headers
     $mailheaders = $extraheaders
                   ."MIME-Version: 1.0\r\n"
                   ."Content-type: multipart/mixed; boundary=\"$mail_boundary\"\r\n"
                   ."\r\n"
                   ."This is a multipart MIME message\r\n"
                   ."\r\n";

     // Body. The part that gets displayed as the message:
     $mailbody = "--$mail_boundary\r\n"
                ."Content-type: text/plain;\r\ncharset=iso-8859-1\r\n"
                ."Content-transfer-encoding: 7bit\r\n"
                ."\r\n"
                .$body
                ."\r\n";
     // Now, do the attachments
     for ($i = 0; $i < count($attacharray); $i++ )
     {
       	$fp = fopen($attacharray[$i][filename], "r");
       	$file = fread($fp, filesize($attacharray[$i][filename]));
       	$file = base64_encode($file);   //BASE64-encoded. Text. Nice.
       	$file = chunk_split($file);     // Now in handy bite-sized 76-char chunks.
       	$filename = basename($attacharray[$i][filename]);
       	
       	$mailbody .= "--$mail_boundary\r\n"
                   ."Content-type: ".$attacharray[$i][mimetype].";\r\n"
                   ."\tname=\"".$filename."\"\r\n"
                	  ."Content-transfer-encoding: base64\r\n"
                	  ."Content-Disposition: attachment;\r\n"
                   ."\tfilename=\"".$filename."\"\r\n"
                   ."\r\n"
                   .$file
                   ."\r\n"
                   ."\r\n";
           //echo $attacharray[$i][filename]."<br>";
           //echo $attacharray[$i][mimetype]."<br>";
           //echo $filename."<br>";
           //echo "<br>";
     }

     // End of mail
     $mailbody .= "--$mail_boundary--";
     mail($to, $subject, $mailbody, $mailheaders);
     
     //leave a record on DB for mail
     $tmpdate = date("Y-m-d");
     $sqlmail = "INSERT INTO timesheet.wmmail SET email_name='$from', receiver='$to', date='$tmpdate';";
	include("find_admin_ip.inc");

     if ($to != "$adminname@rla.com.au") {
     		echo "<br>Mail has been sent to: $to, $subject.<br>";
     }
     $result = mysql_query($sqlmail);
     include("err_msg.inc");
   }
   
/*
	Advatages:
 		* Quick :)
 		* It is a function, so you can call it from most places if it's include()'d.

	Disadvantages:
 		* Quick, so probably buggy :)
 		* I havenot put in any facility for sending a HTML version of your mail text yet. We don't send HTML mails @ Melbourne IT, so it wasn't needed.

	Usage:
   		mailattachments((DestinationAddress), (Subject), (Email Body), (File Attachment Info), (Extra Headers));
*/
?>

</html>
