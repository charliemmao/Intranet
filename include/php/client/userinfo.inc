<html>
<?php
$patsym = ",";
$feedbackfile = "/tmp/".soundex("$first_name$las_tname").".htm";
$rladoc = $SERVER_NAME."/rladoc";
$rlapat = $SERVER_NAME."/patent/rla";
$loaddir = "/upload/";

$patent_server = "patent.rla.com.au/patent/";

$userinfo	=	"email_name=$email_name&logon_name=$logon_name"
	."&title=$title&first_name=$first_name&middle_name=$middle_name"
	."&last_name=$last_name&userpwd=$userpwd&priv=$priv&logouttime=$logouttime"
	."&patdir1=/usr/local/apache/htdocs&patdir2=/patent/&feedbackfile=$feedbackfile"
	."&patent_server=$patent_server"
	."&rladoc=$rladoc&rlapat=$rlapat&loaddir =$loaddir"
	."&defaultdb=$defaultdb&pubtcc=5&patsym=$patsym&no_in_col=1";
	if ($myid) {
		$userspecial	=	"&myid=$myid";
	}
	if ($myrootid) {
		$userspecial	=	$userspecial."&myrootid=$myrootid";
	}
	if ($yyyymmdd) {
		$userspecial	=	$userspecial."&yyyymmdd=$yyyymmdd";
	}
	if ($todayitem) {
		$userspecial	=	$userspecial."&todayitem=$todayitem";
	}
	/*
	if ($itemid) {
		$userspecial	=	$userspecial."&itemid=$itemid";
	}
	//*/
	$userinfo	=	$userinfo.$userspecial."&dummy=dummy";
	//echo "Specail: ".$userinfo."<br>";
?>
</html>