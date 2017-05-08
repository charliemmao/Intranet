<html>

<head>
<title>Library modification</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 4.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
</head>
<body leftmargin="20" bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG">

<?php
$listauthor = "n";
//js
include('library_verify.inc');
include('str_decode_parse.inc');

include("connet_other_once.inc");
include("php_data_validation.inc");
include("rla_functions.inc");

include("userinfo.inc"); //$userinfo
$userstr	=	"?".base64_encode($userinfo);
echo "<a id=top><h2>Library: Item Modification   </a>";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[Refresh]</font></a></h2>";

echo "<fieldset><legend><font size=3>
	<b>Select a Category to Modify</b></font></legend>";
echo "<form method=post action=$PHP_SELF>";
	include("userstr.inc");
	echo rlaspace(5)."<b>Category</b> ";
	include("lib_cat_list.inc");
	echo rlaspace(5);
	//echo "<input type=\"submit\" name=\"modifylibsearchfrm\" value=\"Modify Library Entry\">";
	echo "<button type=\"submit\" name=\"modifylibsearchfrm\">
	<font color=#0000ff><b>GO</b></font></button>";//Form Preparation
echo "</form></fieldset>";
flush();
if ($buildlibsearchform || $modifylibrcd == "yes" || $processmodlibitem) {
if ($priv == "00") {
	//echo "Name List<Br>";
	//exit;
}
$value0nly = 1;
include("lib_firstname_list.inc");
include("lib_middlename_list.inc");
include("lib_lastname_list.inc");
include("lib_publisher_list.inc");
include("lib_keyword_list.inc");
$value0nly = 0;
}
##########################################################################
##################### 		Building Search Form 		#############################
##########################################################################
if ($modifylibsearchfrm) {
	##	search item for modification
	echo "<hr><h3>Find \"<font color=#ff0000>".
		strtoupper($lib_cat_list[$cat_id_lib])."</font>\" entry to modify.</h3>";
	echo "<p><form method=\"POST\" action=\"$PHP_SELF\">";
	include("userstr.inc");
	echo "<input type=\"hidden\" name=\"cat_id_lib\" value=\"$cat_id_lib\" size=\"2\">";
	echo "<p><table border=0>";
	echo "<tr><td colspan=2><b>Search By</td></tr>";
	if ($lib_cat_list[$cat_id_lib] == "book") {
		echo "<tr><td><b>ISBN<b></td>";
		$sql = "SELECT isbn FROM library.for_book order by isbn;";
		$result = mysql_query($sql);
		include("err_msg.inc");
		echo "<td><select name=\"fieldname_isbn\">";
		echo "<option value=\"\">---Select one---";
		$i = 0;
		while (list($isbn) = mysql_fetch_array($result)) {
			if ($isbn) {
				echo "<option value=\"$isbn\">$isbn";
				$i++;
			}
		}
		echo "</option></select>&nbsp;&nbsp;$i records.</td></tr>";		
		
		echo "<tr><td><b>BARCODE<b></td>";
		$sql = "SELECT barcode FROM library.for_book order by barcode;";
		$result = mysql_query($sql);
		include("err_msg.inc");
		echo "<td><select name=\"fieldname_barcode\">";
		echo "<option value=\"\">---Select one---";
		$i = 0;
		while (list($barcode) = mysql_fetch_array($result)) {
			if ($barcode) {
				echo "<option value=\"$barcode\">$barcode";
				$i++;
			}
		}
		echo "</option></select>&nbsp;&nbsp;$i records.</td></tr>";
		
		echo "<tr><td><b>DEWEY<b></td>";
		$sql = "SELECT dewey FROM library.for_book order by dewey;";
		$result = mysql_query($sql);
		include("err_msg.inc");
		echo "<td><select name=\"fieldname_dewey\">";
		echo "<option value=\"\">---Select one---";
		$i = 0;
		while (list($dewey) = mysql_fetch_array($result)) {
			if ($dewey) {
				echo "<option value=\"$dewey\">$dewey";
				$i++;
			}
		}
		echo "</option></select>&nbsp;&nbsp;$i records.</td></tr>";
	} elseif ($lib_cat_list[$cat_id_lib] == "patent") {
	//if ($priv == "00") {
		echo "<tr><td><b>Type Patent No<b></td>";
		echo "<td><input type=text name=patent_no_type size=14> <b>or</b></td></tr>";
	//}
		echo "<tr><td><b>Select Patent No<b></td>";
		echo "<td><select name=\"patent_no\">";
		echo "<option value=\"\">---Select one---";
		$sql = "SELECT patent_no FROM library.for_patent ORDER BY patent_no DESC;";
		$result = mysql_query($sql);
		include("err_msg.inc");
		$no = mysql_num_rows($result);
		while (list($patent_no) = mysql_fetch_array($result)) {
			echo "<option value=\"$patent_no\">$patent_no";
		}
		echo "</select></td><td>(Total Records: $no)</td></tr>";
	} elseif ($lib_cat_list[$cat_id_lib] == "technotes") { 
		exit;
	} elseif ($lib_cat_list[$cat_id_lib] == "article") { 
		exit;
	} elseif ($lib_cat_list[$cat_id_lib] == "report") { 
		exit;
	} elseif ($lib_cat_list[$cat_id_lib] == "journal") { 
		exit;
	} elseif ($lib_cat_list[$cat_id_lib] == "other") { 
		exit;
	}
	/*
	echo "<tr><td><b>Field Value</td>";
	echo "<td><input type=\"text\" name=\"fieldvalue\" size=\"40\"></td></tr>";
	//*/
	echo "<tr><td colspan=2 align=center>
		<button type=\"submit\" name=\"buildlibsearchform\"><font size=3><b>Search</b></font></button></td></tr>";
	echo "</table></form>";
}

if ($buildlibsearchform) {
	if ($lib_cat_list[$cat_id_lib] == "book") {
		if ($fieldname_dewey) {
			$fieldname = "dewey";
			$fieldvalue = $fieldname_dewey;
		} elseif ($fieldname_isbn) {
			$fieldname = "isbn";
			$fieldvalue = $fieldname_isbn;
		} elseif ($fieldname_barcode) {
			$fieldname = "barcode";
			$fieldvalue = $fieldname_barcode;
		}
	} elseif ($lib_cat_list[$cat_id_lib]  == "patent") {
		if (!$patent_no && !$patent_no_type) {
			echo "<hr><h3>Please select a patent no from the list. Press \"Back\" return to previou page.</b></h3><br>";
			exit;
		} 
		$fieldname = "patent_no";
		if ($patent_no_type) {
			$fieldvalue = $patent_no_type;
		} else {
			$fieldvalue = $patent_no;
		}
	}
	echo "<hr><font color=#0000ff><b>Search ".$lib_cat_list[$cat_id_lib];
	echo ", with field name: \"".$fieldname;
	echo "\" and field value \"".$fieldvalue."\".</b></font><br><br>";
	
	## step 1: organise SQL statement and show number of search results
	include("lib_search_sql.inc");
	$sql = $sqlt1.$sqlt2."WHERE t2.$fieldname='$fieldvalue' and t1.lib_item_id=t2.lib_item_id;";
	//echo $sql."<br>";

	$result = mysql_query($sql);
	$no = mysql_num_rows($result);
	echo "<b>Search Result: <font color=#0000ff> $no</font></b> record";
	if ($no) {
		if ($no != 1) {
			echo  "s";
		}
		echo " found.<br><br>";
	} else {
		echo "No result found.<br>";
		exit;
	}
	
	## step 2: display search results
	for ($i=1; $i<=$no; $i++) {
		include("lib_search_result.inc");
	}
}

if ($deletelibrcd == "yes") {
	echo "<hr>";
	$sql = "DELETE FROM library.lib_primlist WHERE lib_item_id='$lib_item_id';";
	$result = mysql_query($sql);
	if (!$result) {
		echo "<font color=#ff0000>Failed to delete main library record.</font><hr>";
		exit;
	}
	$sql = "DELETE FROM library.prim_auth WHERE lib_item_id='$lib_item_id';";
	$result = mysql_query($sql);
	if (!$result) {
		echo "<font color=#ff0000>Failed to delete related author records from library.</font><hr>";
		exit;
	}
	$sql = "DELETE FROM library.prim_keyword WHERE lib_item_id='$lib_item_id';";
	$result = mysql_query($sql);
	if (!$result) {
		echo "<font color=#ff0000>Failed to delete related keyword records from library.</font><hr>";
		exit;
	}
	
	$temp = "<b>Record details:<b><br>";
	if ($lib_cat_list[$cat_id_lib] == "book") {
		$sql = "DELETE FROM library.for_book WHERE lib_item_id='$lib_item_id';";
		$result = mysql_query($sql);
		if (!$result) {
			echo "<font color=#ff0000>Failed to delete related book records from library.</font><hr>";
			exit;
		}
		
		$temp = "Book title: $libtitle.<br>"
			."Barcode: $barcode.<br>"
			."DEWEY: $dewey.<br>"
			."ISBN: $isbn.<br>";
	} elseif ($lib_cat_list[$cat_id_lib] == "patent") { 
		$sql = "DELETE FROM library.for_patent WHERE lib_item_id='$lib_item_id';";
		$result = mysql_query($sql);
		if (!$result) {
			echo "<font color=#ff0000>Failed to delete related book records from library.</font><hr>";
			exit;
		}

		$temp = "Patent title: $libtitle.<br>"
			."Patent no: $patent_no.<br>";
	} elseif ($lib_cat_list[$cat_id_lib] == "article") { 
			exit;
	} elseif ($lib_cat_list[$cat_id_lib] == "report") { 
			exit;
	} elseif ($lib_cat_list[$cat_id_lib] == "journal") { 
			exit;
	} elseif ($lib_cat_list[$cat_id_lib] == "other") { 
			exit;
	}
	
	echo $temp."<br><b>The above record has been deleted from library DB.</b><hr>";
	exit;
}

if ($modifylibrcd == "yes") {
	##	Build form: fields required by all categories
	include("lib_search_sql.inc");
	$sql = $sqlt1.$sqlt2."WHERE t1.lib_item_id='$lib_item_id' and t2.lib_item_id='$lib_item_id';";
	if ($priv == "00") {
		//echo "<br>Modify query: $sql<br>";
	}
	$result = mysql_query($sql);
	include("lib_search_result.inc");
	
	include("lib_displayauthor.inc");
	include("lib_displaykeywords.inc");

	$userstr	=	"?".base64_encode($userinfo);
	echo "<hr><h3>\"<font color=#ff0000>".strtoupper($lib_cat_list[$cat_id_lib])."</font>\" Modification.</h3>";
	echo "<p><form method=\"POST\" action=\"$PHP_SELF\" name=\"newlibitemfrm\">";
	include("userstr.inc");
	echo "<input type=\"hidden\" name=\"cat_id_lib\" value=\"$cat_id_lib\" size=\"2\">";
	echo "<input type=\"hidden\" name=\"no_author_lib\" value=\"$no_author_lib\" size=\"2\">";
	echo "<fieldset>";
	echo "<legend><font color=#0000ff size=4>
		<b>Section A: Data required by all categories</b></font></legend>";
	if ($lib_cat_list[$cat_id_lib] == "patent") {
		echo "<b>Inventor List:</b><br>";
	} else {
		echo "<b>Author List:</b><br>";
	}
	echo "<p><table border=1>";
	echo "<tr><th>No</th><th>First Name</th><th>Middle Name</th><th>Last Name</th></tr>";
	for ($i=0; $i<$no_author_lib; $i++) {
	$j = $i + 1;
	echo "<tr><th><b>$j</b></th>";
	$tem = "firstname".$i."0";
	echo "<td><input type=\"text\" name=\"$tem\" size=\"20\" value=\"".$auth_name_list[$i][0]."\"><br>";//Or Select<br>;
		$tem = "firstname".$i."1";
		if ($listauthor == "y") {
			echo "<select name=\"$tem\">";
			echo "<option value=\"0\">---Select One---";
			include("lib_firstname_list.inc");
		} else {
			echo "<input type=hidden name=\"$tem\" value=\"0\">";
		}
		echo "</td>";
		
	$tem = "middlename".$i."0";
	echo "<td><input type=\"text\" name=\"$tem\" size=\"20\" value=\"".$auth_name_list[$i][1]."\"><br>";//Or Select<br>;
		$tem = "middlename".$i."1";
		if ($listauthor == "y") {
			echo "<select name=\"$tem\">";
			echo "<option value=\"0\">---Select One---";
			include("lib_middlename_list.inc");
		} else {
			echo "<input type=hidden name=\"$tem\" value=\"0\">";
		}
		echo "</td>";
	
	$tem = "lastname".$i."0";
	echo "<td><input type=\"text\" name=\"$tem\" size=\"20\" value=\"".$auth_name_list[$i][2]."\"><br>";//Or Select<br>;
		$tem = "lastname".$i."1";
		if ($listauthor == "y") {
			echo "<select name=\"$tem\">";
			echo "<option value=\"0\">---Select One---";
			include("lib_lastname_list.inc");
		} else {
			echo "<input type=hidden name=\"$tem\" value=\"0\">";
		}
		echo "</td>";
	echo "</tr>";
	}
	echo "</table><br>";
	echo "<p><table border=0>";
	//echo "$lib_item_id, $cat_id, $author_id, $libtitle, $keyword_id, $abstract.";
	echo "<tr><td><b>Title</b></td><td><textarea rows=\"2\" cols=\"60\" name=\"libtitle\">$libtitle</textarea></td></tr>";
	echo "<tr><td><b>Keyword</b></td><td><textarea rows=\"3\" cols=\"60\" name=\"keyword_id\">$keywords</textarea></td></tr>";
	echo "<tr><td><b>Abstract</b></td><td><textarea rows=\"5\" cols=\"60\" name=\"abstract\">$abstract</textarea></td></tr>";
	echo "<input type=\"hidden\" name=\"lib_item_id\" value=\"$lib_item_id\" size=\"2\">";
	/* table: lib_primlist. columns: lib_item_id, cat_id, author_id, libtitle, keyword_id, abstract */
	echo "</table></fieldset>";
	echo "&nbsp;<fieldset>";
	echo "<legend><font color=#0000ff size=4>
		<b>Section B: Data required by \"$lib_cat_list[$cat_id_lib]\"</b></font></legend>";
	//echo "<hr><h4><font color=#0000ff>Section B: Data required by \"$lib_cat_list[$cat_id_lib]\"</font></h4>";
}

if ($modifylibrcd && $lib_cat_list[$cat_id_lib] == "book") {
	##	Build form fields specifically to category: book
	echo "<p><table border=0>";
	echo "<tr><td><b>Barcode</b></td><td><input type=\"text\" name=\"barcode\" size=\"30\" value=\"$barcode\"></td></tr>";
	echo "<tr><th align=\"left\">Dewey</th><td><input type\"text\" name=\"dewey\" size=\"30\" value=\"$dewey\"></td></tr>";
	echo "<tr><th align=\"left\">ISBN</th><td><input type\"text\" name=\"isbn\" size=\"30\" value=\"$isbn\"></td></tr>";
	echo "<tr><th align=\"left\">Editor</th><td><input type\"text\" name=\"editor\" size=\"30\" value=\"$editor\"></td></tr>";
	echo "<tr><th align=\"left\">Edition</th><td><input type\"text\" name=\"edition\" size=\"30\" value=\"$edition\"></td></tr>";
	echo "<tr><th align=\"left\">Volume</th><td><input type\"text\" name=\"volume\" size=\"30\" value=\"$volume\"></td></tr>";
	echo "<tr><th align=\"left\">No Pages</th><td><input type\"text\" name=\"no_pages\" size=\"30\" value=\"$no_pages\"></td></tr>";
	echo "<tr><th align=\"left\">Year Published</th><td><input type\"text\" name=\"year_published\" size=\"30\" value=\"$year_published\"></td></tr>";
	echo "<tr><th align=\"left\">Publisher</th><td>";
	echo "<b>Type a Name</b><br><input type\"text\" name=\"pub_name\" size=\"60\" value=\"".$pub_list[$pub_id]."\"><br>";
	echo "<b>Or Select One</b><br><select name=\"pub_id\" size=\"1\">";
		echo "<option value=\"0\">---Select One---";
		include("lib_publisher_list.inc");
		echo "</select></td></tr>";
	echo "<tr><th align=\"left\">Copyright</th><td><textarea rows=\"2\" cols=\"60\" name=\"copyright\">$copyright</textarea></td></tr>";
	echo "<tr><td><b>Year In</b></td><td><input type=\"text\" name=\"year_in\" size=\"30\" value=\"$year_in\"></td></tr>";
	echo "<tr><td><b>Year Cancel</b></td><td><input type=\"text\" name=\"year_cancellation\" size=\"10\" value=\"$year_cancellation\"></td></tr>";
	echo "<tr><td><b>Missing Record</b></td><td><textarea rows=\"2\" cols=\"60\" name=\"missing_record\" value=\"$missing_record\"></textarea></td></tr>";

	echo "<tr><td><b>Owner</b></td><td><select name=\"owner\">";
	$sql = "select email_name as ename FROM timesheet.employee WHERE date_unemployed='0000-00-00' ORDER BY ename;";
	$result = mysql_query($sql);
	include("err_msg.inc");
	if ($owner == "RLA") {
		echo "<option selected>RLA";
	} else {
		$owner = "RLA";
		echo "<option>RLA";
	}
	while (list($ename) = mysql_fetch_array($result)) {
		if ($owner == $ename) {
			echo "<option selected>$ename";
		} else {
			echo "<option>$ename";
		}
	} 
	echo "</td></tr>";

	echo "</table><br>";
	echo "</fieldset><br>";
	
	$submit = ' onClick="return (bookmodverify());"';
	$submit = $submit.' onSubmit="return (bookmodverify());"';
} elseif ($modifylibrcd && $lib_cat_list[$cat_id_lib] == "patent") {
	##	Build form fields specifically to category: patent	
	echo "<p><table border=0>";
	echo "<tr><th align=\"left\">Country</th><td><input type\"text\" name=\"country\" value=\"$country\" size=\"30\"></td></tr>";
	echo "<tr><th align=\"left\">Patent No</th><td><input type\"text\" name=\"patent_no\" value=\"$patent_no\" size=\"30\"></td></tr>";
	echo "<tr><th align=\"left\">Assignee</th><td><textarea name=\"assignee\" rows=\"2\" cols=\"25\">$assignee</textarea></td></tr>";
	echo "<tr><th align=\"left\">Date Filed</th><td><input type\"text\" name=\"file_date\" value=\"$file_date\" size=\"30\"></td></tr>";
	echo "<tr><th align=\"left\">Date Published</th><td><input type\"text\" name=\"pub_date\" value=\"$pub_date\" size=\"30\"></td></tr>";
	echo "<tr><th align=\"left\">Patent Date</th><td><input type\"text\" name=\"patent_date\" value=\"$patent_date\" size=\"30\"></td></tr>";
	echo "<tr><th align=\"left\">Int. Cl.</th><td><input type\"text\" name=\"intnl_clsfcn\" value=\"$intnl_clsfcn\" size=\"30\"></td></tr>";
	echo "<tr><th align=\"left\">Citations</th><td><textarea rows=\"5\" cols=\"60\" name=\"citations\">$citations</textarea></td></tr>";

	echo "<tr><th align=\"left\">No Pages</th><td><input type\"text\" name=\"no_pages\" value=\"$no_pages\" size=\"30\"></td></tr>";
	echo "<tr><th align=\"left\">PDF File Title</th><td>";
		echo "<input type=text name=\"elec_copy_add\" value=\"$elec_copy_add\" size=\"30\">";
		/*
		echo "<select name=\"elec_copy_add\">";
		echo "<option selected>YES";
		echo "<option>NO</select>";
		*/
		echo "</td></tr>";
	echo "</table><br>";
	echo "<br>";
	$submit = ' onClick="return (patentverify());"';
	$submit = $submit.' onSubmit="return (patentverify());"';
} elseif ($modifylibrcd && $lib_cat_list[$cat_id_lib] == "journal") {
	##	Build form fields specifically to category: journal
	if ($priv	!=	'00') {
		exit;
	}
	include("lib_form_temp.inc");
	echo "<br>";
	$submit = ' onClick="return (journalverify());"';
	$submit = $submit.' onSubmit="return (journalverify());"';
} elseif  ($bulidnewlibitemformb && $lib_cat_list[$cat_id_lib] == "article") {
	##	Build form fields specifically to category: article
	if ($priv	!=	'00') {
		exit;
	}
	include("lib_form_temp.inc");
	echo "<br>";
	$submit = ' onClick="return (articleverify());"';
	$submit = $submit.' onSubmit="return (articleverify());"';
} elseif  ($modifylibrcd && $lib_cat_list[$cat_id_lib] == "report") {
	##	Build form fields specifically to category: report
	if ($priv	!=	'00') {
		exit;
	}
	include("lib_form_temp.inc");
	echo "<br>";
	$submit = ' onClick="return (reportverify());"';
	$submit = $submit.' onSubmit="return (reportverify());"';
} elseif  ($modifylibrcd && $lib_cat_list[$cat_id_lib] == "other") {
	##	Build form fields specifically to category: other
	if ($priv	!=	'00') {
		exit;
	}
	include("lib_form_temp.inc");
	echo "<br>";
	$submit = $submit.' onClick="return (otherverify());"';
	$submit = $submit.' onSubmit="return (otherverify());"';
}
if ($modifylibrcd) {
	echo "<center><button ";
	echo $submit;
	echo " type=\"submit\" name=\"processmodlibitem\">
		<font color=#0000ff size=4><b>SUBMIT</b></font></button>";
	echo "</form>";
}

##########################################################################
##################### 		Processing  Form		#############################
##########################################################################
if ($processmodlibitem) {
	//Process form fields required by all categories and step 1 to step 3
	include("lib_new_modprocess.inc"); 
	
	# step 4: update data to table lib_primlist
	if ($debug){ echo "Author ID text is $auth_id.<br>"; }
	if ($debug){ echo "Keyword ID text is $keyword_id.<br>"; }
	if ($debug){ echo "Publisher ID is $pub_id.<br>"; }
	$cat_id = $cat_id_lib;
	$sql = "UPDATE library.lib_primlist SET "
		."author_id='$auth_id', libtitle='$libtitle', keyword_id='$keyword_id', abstract='$abstract' "
		."WHERE lib_item_id='$lib_item_id';";

	if ($debug){ echo $sql."<br>";	}
	include("connet_root_once.inc");
	$result = mysql_query($sql, $contid);
	include("err_msg.inc");
	if ($result) {
		//echo "<h2><font color=#0000ff>Main library table updated successfull.</font></h2>";
	} else {
		echo "<h2><font color=#ff0000>Main library table failed to update.</font></h2>";
		exit;
	}
	
	# step 5: update data to table to specific category tables
	if ($lib_cat_list[$cat_id_lib] == "book") {
		$sql = "UPDATE library.for_book SET "
		."barcode='$barcode', dewey='$dewey', isbn='$isbn', 
		editor='$editor', edition='$edition', volume='$volume', no_pages='$no_pages', 
		year_published='$year_published', pub_id='$pub_id', copyright='$copyright', 
		year_in='$year_in', year_cancellation='$year_cancellation', 
		missing_record='$missing_record', owner='$owner' "
		."WHERE lib_item_id='$lib_item_id';";	
	} elseif ($lib_cat_list[$cat_id_lib] == "patent") { 
		$elec_copy_add = trim($elec_copy_add);
		$sql = "UPDATE library.for_patent SET "
		."country='$country', patent_no='$patent_no', 
		assignee='$assignee', file_date='$file_date', pub_date='$pub_date', 
		patent_date='$patent_date', intnl_clsfcn='$intnl_clsfcn', 
		citations='$citations', no_pages='$no_pages',elec_copy_add='$elec_copy_add' "
		."WHERE lib_item_id='$lib_item_id';";
			//echo $sql."<br>";
	} elseif ($lib_cat_list[$cat_id_lib] == "article") {
	} elseif ($lib_cat_list[$cat_id_lib] == "report") { 
	} elseif ($lib_cat_list[$cat_id_lib] == "journal") { 
	} elseif ($lib_cat_list[$cat_id_lib] == "other") { 
	}
	if ($debug){ echo $sql."<br>";	}
	$result = mysql_query($sql, $contid);
	include("err_msg.inc");
	if ($result) {
		//echo "<h2><font color=#0000ff>\"$lib_cat_list[$cat_id_lib]\" table updated successfull.</font></h2>";
	} else {
		echo "<h2><font color=#ff0000>\"$lib_cat_list[$cat_id_lib]\" table failed to update.</font></h2>";
		exit;
	}

	# step 6: update data to table prim_auth
	//remove previous record
	$sql = "DELETE FROM library.prim_auth WHERE lib_item_id='$lib_item_id';";
	if ($debug){ echo $sql."<br>";	}
	$result = mysql_query($sql);
	include("err_msg.inc");

	for ($i=0; $i<$no_auth; $i++) {
		$auth_id = $author_id_list[$i];
		$sql = "INSERT INTO library.prim_auth VALUES('$lib_item_id', '$auth_id');";
		$result = mysql_query($sql);
		if ($result) {
			//echo "<h2><font color=#0000ff>\"Author\" table updated successfull.</font></h2>";
		} else {
			echo "<h2><font color=#ff0000>\"Author\" table failed to update.</font></h2>";
			exit;
		}
	}
	
	# step 7: insert data to table prim_keyword
	//remove previous record
	$sql = "DELETE FROM library.prim_keyword WHERE lib_item_id='$lib_item_id';";
	if ($debug){ echo $sql."<br>";	}
	$result = mysql_query($sql);
	include("err_msg.inc");
	for ($i=0; $i<$keyword_no; $i++) {
		$keyword_id = $keyword_id_list[$i];
		$sql = "INSERT INTO library.prim_keyword VALUES('$lib_item_id', '$keyword_id');";
		$result = mysql_query($sql);
		if ($result) {
			//echo "<h2><font color=#0000ff>\"Keywords\" table updated successfull.</font></h2>";
		} else {
			echo "<h2><font color=#ff0000>\"Keywords\" table failed to update.</font></h2>";
			exit;
		}
	}

	# step 8: process feedback
	$beginmsg = "<hr><h3>Modify <font size=5 color=#0000ff>\"".
		strtoupper($lib_cat_list[$cat_id_lib])."\"</font> Record.</h3>";
	$endingmsg = "<br>The above data has been upadted successfully.<br><br>"
		."<br>If any error exists you can modify it again.<br><br>";
	include("lib_feedback.inc");
	
	# step 10: logging
	if ($priv != "00") {
		$date = date("Y-m-d");
		$sql = "INSERT INTO library.lib_entry VALUES('NULL', 'modify', 
			'$lib_item_id', '$cat_id_lib', '$email_name', '$date');";
		$result = mysql_query($sql);
	}
}

if ($bulidnewlibitemformb) {
	echo " type=\"submit\" value=\"SUBMIT\" name=\"processnewlibitem\">";
	echo "</form>";
}

if ($upload) {
	//*
	echo "Maximum allowed file size: ".$MAX_FILE_SIZE."<br>";	
	echo "File size: ".$elec_copy_add_size."<br>";	
	echo "File type: ".$elec_copy_add_type."<br>";
	echo "Original File Name: ".$elec_copy_add_name."<br>";
	echo "Temperay File Name: ".$elec_copy_add."<br>";
	if (chdir("$patdir1$patdir2")) {
		//echo "Success.<br>";
	} else {
		print("Failed to upload file $elec_copy_add_name...<br>\n");
		exit;
	}
	
	//echo "$elec_copy_add, $elec_copy_add_name<br>";
	if (!copy($elec_copy_add, $elec_copy_add_name)) {
    	print("Failed to upload file $elec_copy_add_name...<br>\n");
	} else {
    	//print("Succeded to upload file $elec_copy_add_name...<br>\n");
		$sql = "UPDATE library.for_patent SET elec_copy_add='$elec_copy_add_name' where patent_id='$patent_id' and lib_item_id='$lib_item_id'";
		//echo $sql;
		$result = mysql_query($sql);
		if ($result) {
			echo "<b>Patent record has been updated, and ";
		} else {
			echo "<b>Failed to update patent record, but <br>";
		}
    	echo "the file \"$elec_copy_add_name\" is available for <a href=\"http://$SERVER_NAME$patdir2$elec_copy_add_name\" target=\"_blank\">view</a>.</b>";
	}
	exit;
	//*/
}

if ($cancel) {
	echo "<h3>You can do it later from \"Modify An Item\" page.</h3>";
	exit;
}

function displayval($heading, $val){
	if ($val != "") {
		echo strtoblue(" $heading").": $val.";
	}
}

function val_col($val){
	if ($val == "") {
		echo "<font color=#ff0000><b>Empty</b></font>";
	} else {
		echo stripslashes($val);
	}
}

function isnewname(&$idfound, $fld1, $fld2, $fld2val, $table) {
	$sql = "SELECT $fld1 from library.$table where $fld2='$fld2val';";
	//echo $sql."<br>";
	$result	=	mysql_query($sql);
	include('err_msg.inc');
	if ($result)	{
		if (mysql_num_rows($result)) {
			list($fld1) = mysql_fetch_array($result);
			$idfound = $fld1;
			//echo "$fld2 id: " .$idfound."<br>";
		}
	}
}

function addnewname(&$idfound, $fld2val, $table) {
	$sql = "INSERT INTO library.$table VALUES('NULL','$fld2val');";
	//echo $sql."<br>";
	include("connet_root_once.inc");
	$result	=	mysql_query($sql,$contid);
	include('err_msg.inc');
	if ($result)	{
		$idfound =	mysql_insert_id($contid);
	}
}
echo "<hr><br><a href=#top><b>Back to top</b></a><br><br>";

function strtoblue($str) {
	echo "<font color=#0000ff><b>$str</b></font>";
}
?>
</body>
