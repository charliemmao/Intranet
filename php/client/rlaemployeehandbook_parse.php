<html>
<body background="rlaemb.JPG" leftmargin="20">
<h1 align=center>Parse Employee Handbook</h1><hr>
<?php
	include("connet_root_once.inc");
	$description = date("F, Y");
	$sql = "UPDATE logging.sysmastertable 
        SET description='$description' 
            WHERE item='hdbldate'";
	$result = mysql_query($sql);
	include("err_msg.inc");

	$sql = "DELETE FROM library.rlahdbk;";
	$result = mysql_query($sql);
	include("err_msg.inc");
	
	$fileinput = "/usr/local/apache/htdocs/rladoc/Employee Handbook.txt";
	$fpinput = fopen($fileinput, "r");
	$ctr = 0;
	$printstr= "A"; //H	L	P	T A
	$typeofcurline = "";
	$tab1 = '<table border="1">';
	$tab2 = "</table><p><p>";
	$tabrow = 0;
	while ($curLineStr = fgets($fpinput, 5000)) {
		$curLineStr = trim($curLineStr);
		if ($curLineStr) {
			$strL = strlen($curLineStr);
			$ctr++;
			$line = "";//"Line $ctr<br>";
			$typeofcurline = "";
			
			############# is heading: : indicator = "<#head>" at the end
			if (7 <= $strL) {
				$lengthdiscard = 7;
				$typestr = substr($curLineStr ,$strL-5, $strL);
				if ($typestr == "head>") {
					$tabrow = 0;
					###########	output previous contents
					if ($cumparastr && ($printstr == "P" || $printstr == "A")) {
						$tmp = $cumparastr.$line;
						printhtml($tmp);
						enterdata("p",$tmp);
						$cumparastr = "";
					}
					if ($cumliststr && ($printstr == "L" || $printstr == "A")) {
						$tmp = "<ul>$cumliststr</ul><p><p>".$line;
						printhtml($tmp);
						enterdata("b",$tmp);
						$cumliststr= "";
					}
					if ($cumtabstr && ($printstr == "T" || $printstr == "A")) {
						$tmp = $tab1.$cumtabstr.$tab2.$line;
						printhtml($tmp);
						enterdata("t",$tmp);
						$cumtabstr = "";
					}
					###########	output heading
					$typestr = substr($curLineStr ,$strL-$lengthdiscard, $strL);
					$linestyle = substr($typestr, 1, 1);
					$newstr = substr($curLineStr ,0, $strL-$lengthdiscard);
					$newstr = "<h$linestyle>$newstr</h$linestyle>";
					if ($printstr == "H" || $printstr == "A") {
						$tmp = $newstr.$line;
						printhtml($tmp);
						enterdata("h$linestyle",$tmp);
					}
					$typeofcurline = "H";
				}
			}
			
			############# bullet list: indicator = "* " at beginning
			if (2 <= $strL && $typeofcurline == "") {
				$lengthdiscard = 2;
				$linestyle = substr($curLineStr, 0, 2);
				if ($linestyle == "* ") {
					$tabrow = 0;
					###########	output para or table
					if ($cumparastr && ($printstr == "P" || $printstr == "A")) {
						$tmp = $cumparastr.$line;
						printhtml($tmp);
						enterdata("p",$tmp);
						$cumparastr = "";
					}
					if ($cumtabstr && ($printstr == "T" || $printstr == "A")) {
						$tmp = $tab1.$cumtabstr.$tab2.$line;
						printhtml($tmp);
						enterdata("t",$tmp);
						$cumtabstr = "";
					}
					###########	collect bullet string
					$newstr = substr($curLineStr, 2, $strL-$lengthdiscard);
					$newstr = "<li>$newstr</li>";
					$cumliststr = $cumliststr.$newstr;
					$typeofcurline = "L";
				}
			}

			############# table: indicator = "@" in text
			if ($typeofcurline == "") {
				$tabsep = "@";
				$linestyle = ereg_replace($tabsep, "", $curLineStr);
				if ($linestyle != $curLineStr) {
					###########	output bullet and paragraph					
					if ($cumparastr && ($printstr == "P" || $printstr == "A")) {
						$tmp = $cumparastr.$line;
						printhtml($tmp);
						enterdata("p",$tmp);
						$cumparastr = "";
					}
					if ($cumliststr && ($printstr == "L" || $printstr == "A")) {
						$tmp= "<ul>$cumliststr</ul><p><p>".$line;
						printhtml($tmp);
						enterdata("b",$tmp);
						$cumliststr= "";
					}
					
					###########	Collect Table Data
					$tstr = explode($tabsep,$curLineStr);
					$wid = 100/count($tstr);
					$wid = " width=$wid%";
					
					if ($tabrow == 0) {
						$thtd = "td";
					} else {
						$thtd = "td";
					}
					$newstr = "<tr>";
					for ($i=0; $i<count($tstr); $i++) {
						if (!$tstr[$i]) {
							$tstr[$i] = "&nbsp;";
						}
						$newstr = $newstr."<$thtd$wid>$tstr[$i]</$thtd>";
					}
					$newstr = $newstr."</tr>";
					$cumtabstr= $cumtabstr.$newstr;
					$typeofcurline = "T";
					$tabrow++;
				} 
			}

			############# Normal text Paragraph
			if ($typeofcurline == "") {
				$tabrow = 0;
				###########	output bullet and table
				if ($cumliststr && ($printstr == "L" || $printstr == "A")) {
					$tmp = "<ul>$cumliststr</ul><p><p>".$line;
					printhtml($tmp);
					enterdata("b",$tmp);
					$cumliststr= "";
				}
				if ($cumtabstr && ($printstr == "T" || $printstr == "A")) {
					$tmp = "$tab1$cumtabstr$tab2".$line;
					printhtml($tmp);
					enterdata("t",$tmp);
					$cumtabstr = "";
				}
				###########	collect paragraph
				$cumparastr = $cumparastr.$curLineStr."<br><br>";
			}
		}
	}
	fclose($fpinput);
	if ($cumliststr && ($printstr == "L" || $printstr == "A")) {
		$tmp = "<ul>$cumliststr</ul><p><p>".$line;
		printhtml($tmp);
		enterdata("b",$tmp);
	}
	if ($cumtabstr && ($printstr == "T" || $printstr == "A")) {
		$tmp = "$tab1$cumtabstr$tab2".$line;
		printhtml($tmp);
		enterdata("t",$tmp);
	}
	if ($cumparastr && ($printstr == "P" || $printstr == "A")) {
		$tmp = $cumparastr.$line;
		printhtml($tmp);
		enterdata("p",$tmp);
	}

	$sql = "SELECT id, style, text FROM library.rlahdbk ORDER BY id";
	$result = mysql_query($sql);
	include("err_msg.inc");
	
	$no = mysql_num_rows($result);
	echo "Total Entry: $no.<br>";
	while (list($id, $style, $text) = mysql_fetch_array($result)) {
		//echo "<b>$id: $style</b><br>";
		echo $text;
	}
	
function enterdata($style,$text) {
	$text = ereg_replace("'","\'",$text);
	$text = ereg_replace('"','\"',$text);
	$sql = "INSERT INTO library.rlahdbk SET id='null', style='$style', text='$text';";
	$result = mysql_query($sql);
	include("err_msg.inc");

}
function printhtml($str1) {
	//echo $str1;
}
?>

</body>
