<html>
<?php
	//write message to a file
	$newfile	=	$patdir1.$feedbackfile;
	//echo $newfile."<br>";
	$fp	=	fopen($newfile,'w+');
	if (!$fp) {
		echo 'Problem to open file '.$newfile.'<br>';
		exit;		
	}
	$str = '<bod';
	$str = $str.'y tex';
	$str = $str.'t="#000000" lin';
	$str = $str.'k="#006600" vlin';
	$str = $str.'k="#993333" alin';
	$str = $str.'k="#CC6600" backgroun';
	$str = $str.'d="/images/rlaemb.JPG" leftmargi';
	$str = $str.'n="20">';
	fputs($fp,'<thml>');
	fputs($fp,"$str");
	fputs($fp,"$feedback_message");
	fputs($fp,'</thml>');
	fclose($fp);
	echo "<script language=\"JavaScript\">";
	echo 'msgWindow = window.open("'.$feedbackfile.'", "Window", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width='.$width.',height='.$height.'")';
	echo "</script>";
?>
</html>

