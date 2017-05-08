<html>

<?php
include("phpdir.inc");

Function mailattachments( $to, $subject, $body, $attacharray, $extraheaders)
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
//*
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
//*/
     // End of mail
     $mailbody .= "--$mail_boundary--";
     mail($to, $subject, $mailbody, $mailheaders);
   }
   
   	//"File Attachment Info" is an array:
   	$file1 = "/usr/local/apache/htdocs/report/test1.zip";
	$filearray[0] = array("filename" => $file1 , "mimetype" => "application/x-zip-compressed");
   	$file1 = "/usr/local/apache/htdocs/report/test2.zip";
	$filearray[1] = array("filename" => $file1 , "mimetype" => "application/x-zip-compressed");
   	$file1 = "/usr/local/apache/htdocs/report/csv.csv";
	$filearray[2] = array("filename" => $file1 , "mimetype" => "text/plain");
   	$file1 = "/usr/local/apache/htdocs/report/tml_MthSum_Mar_2001_People_Project(hours).html";
	$filearray[3] = array("filename" => $file1 , "mimetype" => "text/html");
	include("find_admin_ip.inc");
	$to = "$adminname@rla.com.au";
	$subject="mail attachment test";
	$body="Test mail attachemnt";
	$extraheaders="From: webmaster@$SERVER_NAME\nReply-To: webmaster@$SERVER_NAME\nX-Mailer: $PHPconst/" . phpversion();
	mailattachments($to, $subject, $body, $filearray, $extraheaders);

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
