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
		$bylist[$i] = ""; $i++;
		$bylist[$i] = ""; $i++;
		$bylist[$i] = "patent"; $i++;
		$bylist[$i] = "book"; $i++;
		//$bylist[$i] = "technotes"; $i++;

		if ($searchfrom == "") {
			$searchfrom = $bylist[0];
		}

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
				$k = 3;
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
			$matchby = $bylist[0];
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
	$b1 = "<font color=#0000ff>";
	$b2 = "</font>";
	echo "<b>Find from $b1 ";
	if ($searchfrom != "all") {
		echo strtoupper($searchfrom);
	} else {
		echo "BOOK and PATENT";
	}
	echo " $b2 DATABASE for $b1".strtoupper($matchby)."$b2 match of $b1".
		strtoupper($searchby)."$b2:<br>";
	echo "<font size=2 color=#ff0000>\"$searchstring\"</font></b><br><br>";
	flush();
	$patsym = ",";
	$value0nly = 1;
	/*
	include("lib_firstname_list.inc");
	include("lib_middlename_list.inc");
	include("lib_lastname_list.inc");
	include("lib_keyword_list.inc");
	//*/
	include("lib_publisher_list.inc");
	$sql	=	"SELECT cat_id, category FROM library.library_cat where category!='technotes' order by cat_id;";
	$result = mysql_query($sql,$contid);
	include("err_msg.inc");
	if ($result) {
		while (list($cat_id, $category) = mysql_fetch_array($result)) {
			$lib_cat_list[$cat_id] = $category;
		}
	}

	if ($searchby == "author" || $searchby == "keyword" || $searchby == "title" || $searchby == "abstracts") {
		$ln1 = strlen($searchstring);
		$searchstring= ereg_replace("  "," ",$searchstring);
		$ln2 = strlen($searchstring);
		while ($ln1 != $ln2) {
			$searchstring= ereg_replace("  "," ",$searchstring);
			$ln1 = $ln2;
			$ln2 = strlen($searchstring);
		}
		$tmp = ereg_replace("@","",$searchstring);
		$ln1 = strlen($searchstring);
		if ($ln1 == $ln2) {
			$searchstring = ereg_replace(" ","@",$searchstring);
		}
	}

// find lib_item_id
//author
	if ($searchby == "author") {
		$no_au = 0;
		$authlist = explode("@",$searchstring);
		$au_key_no = 0;
		for ($i=0; $i<count($authlist); $i++) {
			if ($authlist[$i]) {
				$au_key_no++;
				//echo $authlist[$i]."<br>";
				$lastname_id = "";
				$firstname_id = "";
				$lastfirst = explode(",",$authlist[$i]);
				$lastname = $lastfirst[0];
				$firstname = $lastfirst[1];
				//echo "$lastname, $firstname.<br>";
				if ($lastname) {
					$sql = "SELECT lastname_id FROM library.auth_last WHERE lastname='$lastname';";
					//echo "$sql<br>";
					$result = mysql_query($sql);
					include("err_msg.inc");
					list($lastname_id) = mysql_fetch_array($result);
				}
				$lnameid[$no_au]=$lastname_id;
				if ($firstname) {
					$sql = "SELECT firstname_id FROM library.auth_first WHERE firstname='$firstname';";
					//echo "$sql<br>";
					$result = mysql_query($sql);
					include("err_msg.inc");
					list($firstname_id) = mysql_fetch_array($result);
				}
				if ($firstname_id) {
					$sql = "SELECT auth_id FROM library.author ".
					"WHERE firstname_id='$firstname_id' and lastname_id='$lastname_id';";
				} else {
					$sql = "SELECT auth_id FROM library.author WHERE lastname_id='$lastname_id';";
				}
				$sql = "SELECT auth_id FROM library.author WHERE lastname_id='$lastname_id';";
				//echo "$sql<br>";
				$result = mysql_query($sql);
				include("err_msg.inc");
				$no = mysql_num_rows($result);
				//echo "$no<br>";

				while (list($auth_id) = mysql_fetch_array($result)) {
					$au_list[$no_au] = $auth_id;
					$no_au++;
				}
			}
		}
		$item_no = 0;
		for ($i=0; $i<$no_au; $i++) {
			$auth_id = $au_list[$i];
			$sql = "SELECT lib_item_id FROM library.prim_auth WHERE auth_id='$auth_id';";
			$result = mysql_query($sql);
			include("err_msg.inc");
			$no = mysql_num_rows($result);
			//echo "<br>$no: $sql<br>";
			while (list($lib_item_id) = mysql_fetch_array($result)) {
				if ($item_no == 0) {
					$lib_item_id_list[$item_no][0] = $lib_item_id;
					$lib_item_id_list[$item_no][1] = 1;
					$item_no++;
					//echo "lib_item_id = $lib_item_id: $item_no (first)<br>";
				} else {
					$isnew = "Y";
					for ($j=0; $j<$item_no; $j++) {
						if ($lib_item_id_list[$j][0] == $lib_item_id) {
							//echo "lib_item_id = $lib_item_id: $item_no (exist)<br>";
							$lib_item_id_list[$j][1] = $lib_item_id_list[$j][1] + 1;
							$isnew = "N";
							break;
						}
					}
					if ($isnew == "Y")	{
						$lib_item_id_list[$item_no][0] = $lib_item_id;
						$lib_item_id_list[$item_no][1] = 1;
						$item_no++;
						//echo "lib_item_id = $lib_item_id: $item_no (new)<br>";
					}				
				}
			}
		}
		/*
		echo "Number of library item found: $item_no<br>";
		for ($i=0; $i<$item_no; $i++) {
			echo $lib_item_id_list[$i][0].": ".$lib_item_id_list[$i][1]."<br>";
		}
		//*/
//keyword
	} elseif ($searchby == "keyword" || $searchby == "title" || $searchby == "abstracts") {
		$keywordlist = explode("@",$searchstring);
		$au_key_no = 0;
		//echo "searchby1: $searchby. searchstring: $searchstring. ". count($keywordlist)."<br>";
		if ($searchby == "keyword") {
			for ($i=0; $i<count($keywordlist); $i++) {
				if ($keywordlist [$i]) {
					$keyword = trim($keywordlist[$i]);
					$sql = "SELECT keyword_id FROM library.keywords WHERE keyword='$keyword'";
					$result = mysql_query($sql);
					include("err_msg.inc");
					list($keyword_id) = mysql_fetch_array($result);
					$keyword_list[$au_key_no] = $keyword_id;
					$au_key_no++;
					//echo "$keyword ($au_key_no): $keyword_id<br>";
				}
			}
		} else {
			$au_key_no = count($keywordlist);
		}
		
		//Find from prim_keyword. columns: lib_item_id, keyword_id 
		//echo "searchby2: $searchby. searchstring: $searchstring. ". count($keywordlist)."<br>";
		$item_no = 0;
		for ($i=0; $i<$au_key_no; $i++) {
			if ($searchby == "keyword") {
				$keyword_id = $keyword_list[$i];
				$sql = "SELECT lib_item_id, keyword_id as abstract FROM library.prim_keyword WHERE keyword_id='$keyword_id';";
			} elseif ($searchby == "title") {
				$libtitle = $keywordlist[$i];
				$sql = "SELECT lib_item_id, abstract FROM library.lib_primlist WHERE libtitle LIKE '%$libtitle%';";
			} elseif ($searchby == "abstracts") {
				$abstract = $keywordlist[$i];
				$sql = "SELECT lib_item_id, abstract FROM library.lib_primlist WHERE abstract LIKE '%$abstract%';";
			}
			$result = mysql_query($sql);
			include("err_msg.inc");
			$no = mysql_num_rows($result);
			if ($priv == "00") {
				//echo "<br><hr>$no: $sql<br>";
			}
			flush();
			while (list($lib_item_id,$abstract) = mysql_fetch_array($result)) {
				if ($priv == "00" && $abstract != "") {
					//echo "<br>$lib_item_id";
					//echo "<br>lib_item_id = $lib_item_id.<br>Abstract: $abstract<br>";
				}
				if ($searchby != "abstracts" || ($searchby == "abstracts" && $abstract != "")) {
					if ($item_no == 0) {
						$lib_item_id_list[$item_no][0] = $lib_item_id;
						$lib_item_id_list[$item_no][1] = 1;
						$item_no++;
					//echo "lib_item_id = $lib_item_id: $item_no (first)<br>";
					} else {
						$isnew = "Y";
						for ($j=0; $j<$item_no; $j++) {
							if ($lib_item_id_list[$j][0] == $lib_item_id) {
								//echo "lib_item_id = $lib_item_id: $item_no (exist)<br>";
								$lib_item_id_list[$j][1] = $lib_item_id_list[$j][1] + 1;
								$isnew = "N";
								break;
							}
						}
						if ($isnew == "Y")	{
							$lib_item_id_list[$item_no][0] = $lib_item_id;
							$lib_item_id_list[$item_no][1] = 1;
							$item_no++;
							//echo "lib_item_id = $lib_item_id: $item_no (new)<br>";
						}
					}		
				}
			}
		}
//exit;
//barcode
	} elseif ($searchby == "barcode") {
		if ($matchby == "exact") {
			$sql = "SELECT lib_item_id FROM library.for_book WHERE barcode='$searchstring';";
		} else {
			$sql = "SELECT lib_item_id FROM library.for_book WHERE barcode LIKE '%$searchstring%';";
		}
//dewey
	} elseif ($searchby == "dewey") {
		if ($matchby == "exact") {
			$sql = "SELECT lib_item_id FROM library.for_book WHERE dewey='$searchstring';";
		} else {
			$sql = "SELECT lib_item_id FROM library.for_book WHERE dewey LIKE '%$searchstring%';";
		}
//isbn
	} elseif ($searchby == "isbn") {
		if ($matchby == "exact") {
			$sql = "SELECT lib_item_id FROM library.for_book WHERE isbn='$searchstring';";
		} else {
			$sql = "SELECT lib_item_id FROM library.for_book WHERE isbn LIKE '%$searchstring%';";
		}
//patent no
	} elseif ($searchby == "patent no") {
		if ($matchby == "exact") {
			$sql = "SELECT lib_item_id FROM library.for_patent WHERE patent_no='$searchstring';";
		} else {
			$sql = "SELECT lib_item_id FROM library.for_patent WHERE patent_no LIKE '%$searchstring%';";
		}
//assignee
	} elseif ($searchby == "assignee") {
		if ($matchby == "exact") {
			$sql = "SELECT lib_item_id FROM library.for_patent WHERE assignee='$searchstring';";
		} else {
			$sql = "SELECT lib_item_id FROM library.for_patent WHERE assignee LIKE '%$searchstring%';";
		}
	}
	
	if ($searchby == "author" || $searchby == "keyword" || $searchby == "title" || $searchby == "abstracts") {
		//sort $lib_item_id_list[][]
		for ($j=0; $j<$item_no; $j++) {
			//echo $j.": ".$lib_item_id_list[$j][1].", ".$lib_item_id_list[$j][0]."<br>";
			$nomatch[$j] = $lib_item_id_list[$j][1];
		}
		if ($item_no) {		
			arsort($nomatch);
			$i=0;
			for (reset($nomatch); list($key,$value) = each($nomatch); ) {
    			//echo "nomatch[$key] = ", $value, "<br>";
 		   		$nl[$i][0] = $lib_item_id_list[$key][0];
    			$nl[$i][1] = $value;
    			$i++;
			}
			//echo "<br><br>";
			for ($j=0; $j<$item_no; $j++) {
				$lib_item_id_list[$j][0] = $nl[$j][0];
				$lib_item_id_list[$j][1] = $nl[$j][1];
				//echo $lib_item_id_list[$j][0]."<br>";
				//echo $j.": ".$lib_item_id_list[$j][1].", ".$lib_item_id_list[$j][0]."<br>";
			}
		}
		######### find match type: book patent technotes or all
		if ($item_no>10) {//$matchby == "partial"
			echo "<br><font size=4 color=#ff0000><b>Total $item_no records have been found, please enter more words ".
				"to limit number of items.</b></font><br><br>";
			//$item_no = 10;
			//The top 10 matches are displayed here.
		}

		for ($j=0; $j<$item_no; $j++) {
			$lib_item_id = $lib_item_id_list[$j][0];
			$sql = "SELECT cat_id FROM library.lib_primlist WHERE lib_item_id='$lib_item_id';";
			
			$result = mysql_query($sql);
			include("err_msg.inc");
			list($cat_id) = mysql_fetch_array($result);
			$cat_id_list[$j] = $cat_id;
			//echo "No match = ".$lib_item_id_list[$j][1]." lib_item_id=$lib_item_id, cat_id=$cat_id<br>";
		}
		######### list result
		echo "<b>Search Result:</b><br><br>";
		$i = 1;
		//echo "<br>$item_no<br>";
		for ($j=0; $j<$item_no; $j++) {
			$lib_item_id = $lib_item_id_list[$j][0];
			$cat_id = $cat_id_list[$j];
			$authmatch = $lib_item_id_list[$j][1];
			if ($searchby == "author") {
				$matchmsg = " match $searchby <font color=#ff0000>$authmatch</font> out of <font color=#ff0000>$au_key_no</font>";
			} else {
				$matchmsg = " match words <font color=#ff0000>$authmatch</font> out of <font color=#ff0000>
					$au_key_no</font> from $searchby search";
			}
			if ($priv == "00") {
				//echo "cat_id: $cat_id. lib_item_id: $lib_item_id. authmatch: $authmatch<br>";
			}
			if ($matchby == "exact" && $authmatch == $au_key_no) {
				//echo "<br>(exact) $matchby-$searchfrom: $lib_item_id - $cat_id - $authmatch/$au_key_no.<br>";
				if ($searchfrom == "book" && $cat_id == 1) {
					include("lib_list_item.inc");
				} elseif ($searchfrom == "patent" && $cat_id == 2) {
					include("lib_list_item.inc");
				} elseif ($searchfrom == "technotes" && $cat_id == 3) {
					include("lib_list_item.inc");
				} elseif ($searchfrom == "all") {
					include("lib_list_item.inc");
				}
			} elseif ($matchby == "partial") {
				//echo "<br>(partial) $matchby-$searchfrom: $lib_item_id - $cat_id - $authmatch/$au_key_no.<br>";
				if ($searchfrom == "book" && $cat_id == 1) {
					include("lib_list_item.inc");
				} elseif ($searchfrom == "patent" && $cat_id == 2) {
					include("lib_list_item.inc");
				} elseif ($searchfrom == "technotes" && $cat_id == 3) {
					include("lib_list_item.inc");
				} elseif ($searchfrom == "all") {
					include("lib_list_item.inc");
				}
			}
			flush();
		}
		$i--;
		echo "<br><b><font color=#0000ff> $i</font> record";
		if ($i > 1) {
			echo  "s";
		}
		echo " found.</b><br><br>";
	} else {
		//echo "$sql <br>";
		$result0 = mysql_query($sql);
		include("err_msg.inc");
		$no = mysql_num_rows($result0);
		echo "<b>Search Result: <font color=#0000ff> $no</font></b> record";
		if ($no) {
			if ($no != 1) {
				echo  "s";
			}
			echo " found.<br><br>";
		} else {
			echo " found.<br><br>";
			exit;
		}
		$i = 1;
		if ($priv == "00") {
		$patstrall = "<h2>Patent Brief Description</h2><table border=1>";
		$patstrall .= "<tr><th>Patent No</th><th>File Date</th><th>Pub Date</th>
				<th>Patent Date</th><th>Inventor</th><th>Assignee</th><th>Patent Title</th></tr>";
		while (list($lib_item_id) = mysql_fetch_array($result0)) {
			include("lib_list_item.inc");
			$patstrall .= "<tr><td>$patent_no</td><td>$file_date</td><td>$pub_date</td>
				<td>$patent_date</td><td>$authorstr</td><td>$assignee</td><td>$libtitle</td></tr>";
		}
		$patstrall .= "</table>";
		echo "<hr><br>$patstrall<br>";
		}
	}
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
