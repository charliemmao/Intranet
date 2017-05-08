<html>
<head>
<title>Upload Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
</head>
<body leftmargin="20" bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG">
<script language=javascript>
function uploadverify() {
	if (document.uploadfileform.filename.value == "") {
		window.alert("No file has been selected.");
		return false
	} else {
		return true;
 	}
}
</script>
<?php
include('str_decode_parse.inc');
include("connet_other_once.inc");
include("userinfo.inc"); //$userinfo
$userstr	=	"?".base64_encode($userinfo);
echo "<h2 align=center><a id=top>Upload File From Local To Server</a>";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2> [Refresh]</font></a></h2><hr>";

	echo "<p><form ENCTYPE=\"multipart/form-data\" method=\"POST\" 
		action=\"$PHP_SELF\" name=\"uploadfileform\">";
	include("userstr.inc");
	echo '<input type="hidden" name="MAX_FILE_SIZE" value="20000000">';
	echo "<table align=center>";
	echo "<tr><th align=left>Upload File To</th><td><select name=copyfileto>";
	echo "<option>iptraflog/";
	echo "<option>rladoc/";
	echo "<option>upload/";
	echo "</option></select></td></tr>";	
	echo "<tr><th align=left>Select file:</th>";
	echo "<td><input name=\"filename\" TYPE=\"file\" value=\"".$filename_name."\" size=\"50\"><td></tr>";
	echo "<tr><th colspan=2>(Maximum file size are 20 MB)</th></tr>";
	echo "<tr><th colspan=2>&nbsp;</th></tr>";
		
	echo '<tr><td colspan=2 align=middle><input onClick="return (uploadverify());"';
	echo ' onSubmit="return (uploadverify());"';
	echo " type=\"submit\" name=\"upload\" value=\"Submit\">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	echo "<input type=\"submit\" name=\"cancel\" value=\"Cancel\"></td></tr>";
	/*
	echo '<tr><td colspan=2 align=middle><button onClick="return (uploadverify());"';
	echo ' onSubmit="return (uploadverify());"';
	echo " type=\"submit\" name=\"upload\"><font size=3><b>Upload File</b></font></button>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	echo "<button type=\"submit\" name=\"cancel\"><font size=3><b>Cancel</b></font></button>
	</td></tr>";
	//*/
	echo "</table>";
	echo "</form><hr>";

## process upload
if ($upload) {
	$fldcopy = "$patdir1/$copyfileto";
/*
	echo "<b>File copyed to</b>: $fldcopy<br>";
	echo "<b>System temperary file</b>: \"$filename\"<br>";
	echo "<b>File name</b>: \"$filename_name\"<br>";
	exit;
//*/

	if (!copy($filename, $fldcopy.$filename_name)) {
    	print("<br><br>Failed to upload file \"$filename_name\"...<br>\n");
	} else {
		echo "<b><br><br>\"$filename_name\" has been uploaded, and ".
    	" is available for <a href=\"/$copyfileto$filename_name\" target=\"_blank\">view</a>.";
		echo "<br>File absolute path on $SERVER_NAME is $fldcopy$filename_name.</b>";
	}
}

if ($cancel) {
	echo "<h3><br><br>You can upload file later.</h3>";
	exit;
}
//<hr><br><a href=#top><b>Back to top</b></a><br><br>
?>
</html>
