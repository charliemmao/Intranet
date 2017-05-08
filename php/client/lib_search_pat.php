<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<title>Library Search</title>
</head>
<script language="JAVASCRIPT">
function firstele() {
	document.searchform.searchstring.focus(); 
	document.searchform.searchstring.select();
}

function verifysearchstring() {
var str, len, i;
	str = document.searchform.searchstring.value;
	//window.alert(str);
	if (str) {
		//window.alert(str);
		len = str.length
		for (i=0; i<len; i++) {
			if (str.substring(i,i+1) != " ") {
				return true;
				break;
			}
		}
	}
	window.alert("Please enter no empty string.");
	return false;
}
</script>
<body onload="firstele();" leftmargin="20" bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG">

<?php
include('str_decode_parse.inc');
include("connet_other_once.inc");
include("userinfo.inc"); //$userinfo
$userstr	=	"?".base64_encode($userinfo);
echo "<a id=top><h2 align=center>Library Search";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2><b>[Refresh]</b></font></a></h2><hr>";
/*
include("lib_modify_name.inc");
//*/
echo '<form method="post" action="'.$PHP_SELF.'" name="searchform">';
	echo "<table border=0>";
	include("userstr.inc");
	//search library by
	$b1 = "<font size=2><b>";
	$b2 = "</b></font>";
	$th1 = "<font size=2 color=#0000ff>";
	$th2 = "</font>";
	echo "<tr><th align=left colspan=4><font size=4>Set up criteria for library search</font></th></tr>";
	echo "<tr><th align=left>$th1 Find$th2</th>";
		$i = 0;
		$bylist[$i] = "all"; $i++;
		$bylist[$i] = "patent"; $i++;
		$bylist[$i] = "book"; $i++;
		$bylist[$i] = ""; $i++;
		$bylist[$i] = ""; $i++;
		//$bylist[$i] = "technotes"; $i++;

		//if ($searchfrom == "") {
			$searchfrom = $bylist[1];
		//}

		for ($j=0; $j<3; $j++) {
			if ($searchfrom == $bylist[$j]) {
				echo "<td><input type=\"radio\" name=\"searchfrom\" value=\"$bylist[$j]\" ".
				"checked>$b1".ucwords($bylist[$j])."$b2</td>";
			} else {
				if ($bylist[$j]) {
					echo "<td><input type=\"radio\" name=\"searchfrom\" value=\"$bylist[$j]\" ".
					">$b1".ucwords($bylist[$j])."$b2</td>";
				} else {
					echo "<td>&nbsp;</td>";
				}
			}
		}
		$l = (int)($i/3) + 5;;
		echo "<td rowspan=$l><button ";
		echo ' onClick="return verifysearchstring();"';
		echo " type=submit name=\"search\"><font size=3><b>Search</b></font></td><tr>";
		$l = (int)($i/3);
		if ($l*3 != $i) {
			$i = 3*($l+1);
		}
		$k = 3;
		while ($j < $i) {
			echo "<tr><td>&nbsp;</td>";
			for ($j=$k; $j<$k+3; $j++) {
				if ($searchfrom == $bylist[$j]) {
					echo "<td><input type=\"radio\" name=\"searchfrom\" value=\"$bylist[$j]\" ".
					"checked>$b1".ucwords($bylist[$j])."$b2</td>";
				} else {
					if ($bylist[$j]) {
						echo "<td><input type=\"radio\" name=\"searchfrom\" value=\"$bylist[$j]\" ".
						">$b1".ucwords($bylist[$j])."$b2</td>";
					} else {
						echo "<td>&nbsp;</td>";
					}
				}
			}
			echo "</tr>";
			$k = $k + 3;
		}
		
		$i = 0;
		$bylist[$i] = "author"; $strlist[$i] = "Hearle@Sparrow@Cross"; $i++;
		$bylist[$i] = ""; $i++;
		$bylist[$i] = ""; $i++;
		$bylist[$i] = "title"; $strlist[$i] = "photolithographing@thick@layer"; $i++;
		$bylist[$i] = "abstracts"; $strlist[$i] = "masted@photoresist"; $i++;
		$bylist[$i] = "keyword"; $strlist[$i] = "conductive@dielectric@substrate"; $i++;
		$bylist[$i] = "barcode"; $strlist[$i] = "000001791"; $i++;
		$bylist[$i] = "dewey"; $strlist[$i] = "539.77 IRA"; $i++;
		$bylist[$i] = "isbn"; $strlist[$i] = "0 408 00168 2"; $i++;
		$bylist[$i] = "patent no"; $strlist[$i] = "4711835"; $i++;
		$bylist[$i] = "assignee"; $strlist[$i] = "Thomson-CSF"; $i++;
		$head[1] = "$th1 From$th2";
		$head[2] = "-words";
		$head[3] = "-book";
		$head[4] = "-patent";
		if ($searchby == "") {
			if ($priv == "00") {
				$k = 9;
				$searchby = $bylist[$k];
				$searchstring = $strlist[$k];
			} else {
				$searchstring="Type search string here. Characters are case insensitive.";
			}
		}
		$j1 = 0;
		$row = 1;
		
	while ($j1 < $i) {
		if ($row == 1) {
			echo "<tr><th align=left>$head[$row]</th>";
		} else {
			echo "<tr><td align=right>$head[$row]</td>";
		}
		$j2 = $j1 + 3;
		for ($j=$j1; $j<$j2; $j++) {
			if ($searchby == $bylist[$j]) {
				$checked = " checked";
			} else {
				$checked = "";
			}
			if ($bylist[$j] == "") {
				echo "<td>&nbsp;</td>";
			} else {
				if ($bylist[$j] == "isbn") {
					$tmp = strtoupper($bylist[$j]);
				} else {
					$tmp = ucwords($bylist[$j]);
				}
				echo "<td><input type=\"radio\" name=\"searchby\" value=\"$bylist[$j]\" ".
				"$checked>$b1$tmp$b2</td>";
			}
		}
		echo "</tr>";
		$j1 = $j;
		$row++;
	}

	echo "<tr><th align=left>$th1 Match$th2</th>";
		$i = 0;
		$bylist[$i] = "partial"; $i++;
		$bylist[$i] = "exact"; $i++;
		if ($matchby == "") {
			$matchby = $bylist[1];
		}
		for ($j=0; $j<$i; $j++) {
			if ($matchby == $bylist[$j]) {
				echo "<td><input type=\"radio\" name=\"matchby\" value=\"$bylist[$j]\" ".
				"checked>$b1".ucwords($bylist[$j])."$b2</td>";
			} else {
				echo "<td><input type=\"radio\" name=\"matchby\" value=\"$bylist[$j]\" ".
				">$b1".ucwords($bylist[$j])."$b2</td>";
			}
		}
	echo "</tr>";
	$searchstring = trim($searchstring);
	$tmp =" $th1 Auther(s)$th2 or
		$th1 Keyword(s)$th2 or $th1 Barcode$th2 or$th1 Dewey$th2 or<br>
		$th1 ISBN$th2 or$th1 Patent No$th2 or$th1 Assignee$th2 ";
	echo "<tr><td colspan=5><textarea name=searchstring cols=50 rows=4>$searchstring</textarea></td></tr>";
	//echo "<tr><td colspan=5><font size=2><b>Type search string in the box.</b><br>";
	echo "<tr><td colspan=5>For $th1 Auther(s)$th2, please type <font color=#ff0000>Last Name Only</font>,
		 and separating Authors by <font color=#ff0000>@</font>.<br>
		For $th1 words$th2, please separating them by <font color=#ff0000>@</font>.<br><br>
		$th1 Barcode$th2, $th1 Dewey$th2 and$th1 ISBN$th2 are only available to BOOK search.<br>
		$th1 Patent No$th2 and $th1 Assignee$th2 are only available to PATENT search.<br>";
	echo "</font></td></tr>";//, First Name

	echo "</table>";
	echo "</form>";
	
if ($search) {
	echo "<hr>";
	$searchstring = trim($searchstring);
	$patlist = explode("@", $searchstring);
	//echo "$searchstring<br><br>";
	$multipatent = 1;
	echo "<table border=1><tr><th>No</th><th>Patent Abstract</th></tr>";
	for ($ipat = 1; $ipat<count($patlist); $ipat++) {
		if ($patlist[$ipat]) {
			$searchstring = $patlist[$ipat];
			echo "<tr><td>$ipat</td><td>";
			$matchby = "exact";
			//echo "$ipat: $searchstring<br>";
			include("lib_search_one_patent.inc");
			echo "</td></tr>";
			flush();
		}
	}
	echo "</table>";
}
echo "<hr><a href=#top>Back to top</a><br><br>";

function displayval($heading, $val){
	if ($val != "") {
		echo strtoblue(" $heading").": $val.";
	}
}

function strtoblue($str) {
	echo "<font color=#0000ff><b>$str</b></font>";
}

function rmlastdot(&$str) {
	$str = trim($str);
	$sl = strlen($str);
	if ($sl>1) {
		if (substr($str,$sl-1,$sl) == ".") {
			return substr($str,0,$sl-1);
		} else {
			return $str;
		}
	}
}

?>
</body>
