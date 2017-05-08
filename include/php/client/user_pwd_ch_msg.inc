<html>
<?php	
	$svrfld = "/usr/local/apache/htdocs/report/";
	$msgfile = $email_name."_msg.html";
	$filemsg = $svrfld.$msgfile;
	$fp	=	fopen($filemsg,'w+');
	if ($fp) {
		fputs($fp,"<ht"."ml><h1>Hi, $first_name</h1>".
			"<font size=2>(".date("l, F j Y h:i:s").")</font><br><br>");
		$msg = "Your password has been changed successfully.";
		fputs($fp,"<b>$msg</b>");
		fputs($fp,"</ht"."ml>");
		fclose($fp);
		echo "<script language=\"javascript\">";
		echo 'msgWindow = window.open("../report/'.$msgfile.'", "Window", 
			"toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=500,height=300")';
		echo "</script>";
		flush();
	}
?>
</html>

