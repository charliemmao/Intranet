<html>

<head>
<title>Add New Item</title>
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
if ($processnewlibitem){ // || $bulidnewlibitemformb
if ($priv == "00") {
	//echo "Name List<Br>";
	//exit;
}
$value0nly = 1;
include("lib_firstname_list.inc");
include("lib_middlename_list.inc");
include("lib_lastname_list.inc");
include("lib_publisher_list.inc");
}
$value0nly = 0;

include("userinfo.inc"); //$userinfo
$userstr	=	"?".base64_encode($userinfo);
echo "<a id=top><h2>Library: Add New Item   </a>";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[Refresh]</font></a></h2>";

echo "<fieldset><legend><font size=3>
	<b>Select Category and Number of Author</b></font></legend>";
echo "<form method=post action=$PHP_SELF>";
if ($no_author_lib =='') {
	$no_author_lib = 1;
}
include("userstr.inc");
echo "<b>".rlaspace(5)."Category</b> ";
include("lib_cat_list.inc");
echo rlaspace(5)."<b>No Author</b> ";
echo "<input type=\"text\" name=\"no_author_lib\" value=\"$no_author_lib\" size=\"2\">";
echo rlaspace(5);
echo "<button type=\"submit\" name=\"bulidnewlibitemformb\">
	<font color=#0000ff><b>GO</b></font></button>";//Form Preparation
//echo "&nbsp;&nbsp;<input type=\"submit\" name=\"bulidnewlibitemformb\" value=\"ADD NEW ITEM\" size=\"2\">";
echo "</form></fieldset>";

## process upload
if ($upload) {
//*
	if (!chdir("$patdir1$patdir2")) {
		print("Failed to upload file $elec_copy_add_name...<br>\n");
		exit;
	}
	
	if (!copy($elec_copy_add, $elec_copy_add_name)) {
    	print("Failed to upload file $elec_copy_add_name...<br>\n");
	} else {
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

##########################################################################
##################### 		Form Builder 		#############################
##########################################################################
if ($bulidnewlibitemformb) {
	##	Build form: fields required by all categories
	echo "<h3>Enter data for new \"<font color=#ff0000>"
		.strtoupper($lib_cat_list[$cat_id_lib])."</font>\" to library with \"<font color=#ff0000>"
		.$no_author_lib."</font>\" author(s).</h3>";
	echo "<p><form method=\"POST\" action=\"$PHP_SELF\" name=\"newlibitemfrm\">";
	$todayitem = $todayitem + 1;
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
	echo "<td><input type=\"text\" name=\"$tem\" size=\"20\"><br>";//Or Select<br>;
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
	echo "<td><input type=\"text\" name=\"$tem\" size=\"20\"><br>";//Or Select<br>;
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
	echo "<td><input type=\"text\" name=\"$tem\" size=\"20\"><br>";//Or Select<br>;
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
	echo "<tr><td><b>Title</b></td><td><textarea rows=\"2\" cols=\"60\" name=\"libtitle\"></textarea></td></tr>";
	if ($todayitem<=2) {
		$temp = "Please leave one empty space between keywords, do not use any punctuation. Delete this text.";
	}
	echo "<tr><td><b>Keyword</b></td><td><textarea rows=\"3\" cols=\"60\" name=\"keyword_id\">$temp</textarea></td></tr>";
	if ($todayitem<=2) {
		$temp = "Please avoid to use forward slash \"/\" or backword slash \"\\\". Delete this text.";
	}
	echo "<tr><td><b>Abstract</b></td><td><textarea rows=\"5\" cols=\"60\" name=\"abstract\">$temp</textarea></td></tr>";
	/* table: lib_primlist. columns: lib_item_id, cat_id, author_id, libtitle, keyword_id, abstract, */
	echo "</table></fieldset>";
	echo "&nbsp;<fieldset>";
	echo "<legend><font color=#0000ff size=4>
		<b>Section B: Data required by \"$lib_cat_list[$cat_id_lib]\"</b></font></legend>";
}
if ($bulidnewlibitemformb && $lib_cat_list[$cat_id_lib] == "book") {
	##	Build form fields specifically to category: book
	/* table: for_book. columns: book_id, lib_item_id, barcode, dewey, isbn, editor, 
		edition, volume, no_pages, year_published, pub_id, copyright, year_in, 
		year_cancellation, missing_record, */
	echo "<p><table border=0>";
	echo "<tr><td><b>Barcode</b></td><td><input type=\"text\" name=\"barcode\" size=\"30\"></td></tr>";
	echo "<tr><th align=\"left\">Dewey</th><td><input type\"text\" name=\"dewey\" size=\"30\"></td></tr>";
	echo "<tr><th align=\"left\">ISBN</th><td><input type\"text\" name=\"isbn\" size=\"30\"></td></tr>";
	echo "<tr><th align=\"left\">Editor</th><td><input type\"text\" name=\"editor\" size=\"30\"></td></tr>";
	echo "<tr><th align=\"left\">Edition</th><td><input type\"text\" name=\"edition\" size=\"30\"></td></tr>";
	echo "<tr><th align=\"left\">Volume</th><td><input type\"text\" name=\"volume\" size=\"30\"></td></tr>";
	echo "<tr><th align=\"left\">No Pages</th><td><input type\"text\" name=\"no_pages\" size=\"30\"></td></tr>";
	echo "<tr><th align=\"left\">Year Published</th><td><input type\"text\" name=\"year_published\" size=\"30\"></td></tr>";
	echo "<tr><th align=\"left\">Publisher</th><td>";
	echo "<b>Type a Name</b><br><input type\"text\" name=\"pub_name\" size=\"60\"><br>";
	echo "<b>Or Select One</b><br><select name=\"pub_id\" size=\"1\">";
		echo "<option value=\"0\">---Select One---";
		include("lib_publisher_list.inc");
		echo "</select></td></tr>";
	echo "<tr><th align=\"left\">Copyright</th><td><textarea rows=\"2\" cols=\"60\" name=\"copyright\"></textarea></td></tr>";
	echo "<tr><td><b>Year In</b></td><td><input type=\"text\" name=\"year_in\" size=\"30\"></td></tr>";
	//echo "<tr><td><b>Year Cancel</b></td><td><input type=\"text\" name=\"year_cancellation\" size=\"10\"></td></tr>";
	//echo "<tr><td><b>Missing Record</b></td><td><textarea rows=\"2\" cols=\"60\" name=\"missing_record\"></textarea></td></tr>";
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
	$submit = $submit.' onClick="return (bookverify());"';
	$submit = $submit.' onSubmit="return (bookverify());"';
	/*
	echo rlaspace(40)."<input ";
	echo ' onClick="return (bookverify());"';
	echo ' onSubmit="return (bookverify());"';
	//*/
} elseif ($bulidnewlibitemformb && $lib_cat_list[$cat_id_lib] == "patent") {
	##	Build form fields specifically to category: patent
	echo "<p><table border=0>";
	echo "<tr><th align=\"left\">Country</th><td><input type\"text\" name=\"country\" size=\"30\"></td></tr>";
	echo "<tr><th align=\"left\">Patent No</th><td><input type\"text\" name=\"patent_no\" size=\"30\"></td></tr>";
	echo "<tr><th align=\"left\">Assignee</th><td><textarea name=\"assignee\" rows=\"2\" cols=\"25\"></textarea></td></tr>";
	echo "<tr><th align=\"left\">Date Filed</th><td><input type\"text\" name=\"file_date\" size=\"30\"></td></tr>";
	echo "<tr><th align=\"left\">Date Published</th><td><input type\"text\" name=\"pub_date\" size=\"30\"></td></tr>";
	echo "<tr><th align=\"left\">Patent Date</th><td><input type\"text\" name=\"patent_date\" size=\"30\"></td></tr>";
	echo "<tr><th align=\"left\">Int. Cl.</th><td><input type\"text\" name=\"intnl_clsfcn\" size=\"30\"></td></tr>";
	echo "<tr><th align=\"left\">Citations</th><td><textarea rows=\"5\" cols=\"60\" name=\"citations\"></textarea></td></tr>";

	echo "<tr><th align=\"left\">No Pages</th><td><input type\"text\" name=\"no_pages\" size=\"30\"></td></tr>";
	echo "<tr><th align=\"left\">PDF File Title</th><td>";
		echo "<input type=text name=\"elec_copy_add\" size=\"30\">";
		/*
		echo "<select name=\"elec_copy_add\">";
		echo "<option selected>YES";
		echo "<option>NO</select>";
		*/
		echo "</td></tr>";
	echo "</table><br>";
	echo "</fiel"."dset><br>";
	$submit = $submit.' onClick="return (patentverify());"';
	$submit = $submit.' onSubmit="return (patentverify());"';
	/*
	echo "<input ";
	echo ' onClick="return (patentverify());"';
	echo ' onSubmit="return (patentverify());"';
	//*/
} elseif ($bulidnewlibitemformb && $lib_cat_list[$cat_id_lib] == "journal") {
	##	Build form fields specifically to category: journal
	if ($priv	!=	'00') {
		exit;
	}
	include("lib_form_temp.inc");
	echo "</fiel"."dset><br>";

	$submit = $submit.' onClick="return (journalverify());"';
	$submit = $submit.' onSubmit="return (journalverify());"';
/*	
	echo "<input ";
	echo ' onClick="return (journalverify());"';
	echo ' onSubmit="return (journalverify());"';
//*/
} elseif  ($bulidnewlibitemformb && $lib_cat_list[$cat_id_lib] == "article") {
	##	Build form fields specifically to category: article
	if ($priv	!=	'00') {
		exit;
	}
	include("lib_form_temp.inc");
	echo "</fiel"."dset><br>";
	$submit = $submit.' onClick="return (patentverify());"';
	$submit = $submit.' onSubmit="return (patentverify());"';
/*	
	echo "<input ";
	echo ' onClick="return (articleverify());"';
	echo ' onSubmit="return (articleverify());"';
//*/
} elseif  ($bulidnewlibitemformb && $lib_cat_list[$cat_id_lib] == "report") {
	##	Build form fields specifically to category: report
	if ($priv	!=	'00') {
		exit;
	}
	include("lib_form_temp.inc");
	echo "</fiel"."dset><br>";
	$submit = $submit.' onClick="return (reportverify());"';
	$submit = $submit.' onSubmit="return (reportverify());"';
/*	
	echo "<input ";
	echo ' onClick="return (reportverify());"';
	echo ' onSubmit="return (reportverify());"';
//*/
} elseif  ($bulidnewlibitemformb && $lib_cat_list[$cat_id_lib] == "other") {
	##	Build form fields specifically to category: other
	if ($priv	!=	'00') {
		exit;
	}
	include("lib_form_temp.inc");
	echo "</fiel"."dset><br>";
	$submit = $submit.' onClick="return (otherverify());"';
	$submit = $submit.' onSubmit="return (otherverify());"';
/*	
	echo "<input ";
	echo ' onClick="return (otherverify());"';
	echo ' onSubmit="return (otherverify());"';
//*/
}
if ($bulidnewlibitemformb) {
	echo "<center><button ";
	echo $submit;
	echo " type=\"submit\" name=\"processnewlibitem\">
		<font color=#0000ff size=4><b>SUBMIT</b></font></button>";
	//echo " type=\"submit\" value=\"SUBMIT\" name=\"processnewlibitem\">";
	echo "</form>";
}

##########################################################################
##################### 		Processing  Form		#############################
##########################################################################
if ($processnewlibitem) {
	include("lib_new_modprocess.inc"); //step 1 to step 3
	
	# step 4: check whether this item already entered
	if ($lib_cat_list[$cat_id_lib] == "book") {		
		$qry = "SELECT lib_item_id FROM library.for_book where barcode='$barcode' and dewey='$dewey' and isbn='$isbn';";
	} elseif ($lib_cat_list[$cat_id_lib] == "patent") { 
		$qry = "SELECT lib_item_id FROM library.for_patent where country='$country' and "
			."patent_no='$patent_no' and file_date='$file_date' and pub_date='$pub_date';";
	} elseif ($lib_cat_list[$cat_id_lib] == "journal") { 
		/* $qry = "SELECT lib_item_id FROM library.for_journal where ='$' and ='$' and "
			."='$' and  and ='$';"; */		
	} elseif ($lib_cat_list[$cat_id_lib] == "article") { 
		/* $qry = "SELECT lib_item_id FROM library.for_article where ='$' and ='$' and "
			."='$' and  and ='$';"; */		
	} elseif ($lib_cat_list[$cat_id_lib] == "report") { 
		/* $qry = "SELECT lib_item_id FROM library.for_report where ='$' and ='$' and "
			."='$' and  and ='$';"; */		
	} elseif ($lib_cat_list[$cat_id_lib] == "other") { 
		/* $qry = "SELECT lib_item_id FROM library.for_other where ='$' and ='$' and "
			."='$' and  and ='$';"; */		
	}
	if ($debug){ echo $qry."<br>";	}
	include("find_one_val.inc");
	$lib_item_id = $out;
	if ($lib_item_id) {
		echo "<h2><font color=#ff0000>This item has been entered previously with entry ID"
		." $lib_item_id, if you want to modify this item use \"Modify An Item\" from left frame.</font></h2>";
		exit;
	} else {
		if ($debug){ echo "This is a new ".$lib_cat_list[$cat_id_lib]." item entry.<br>"; }
	}
	
	# step 5: insert data to table lib_primlist
	if ($debug){ echo "Author ID text is $auth_id.<br>"; }
	if ($debug){ echo "Keyword ID text is $keyword_id.<br>"; }
	if ($debug){ echo "Publisher ID is $pub_id.<br>"; }
	$cat_id = $cat_id_lib;
	$sql = "INSERT INTO library.lib_primlist VALUES('NULL', '$cat_id', '$auth_id', "
		."'$libtitle', '$keyword_id', '$abstract')";
	if ($debug){ echo $sql."<br>";	}
	include("connet_root_once.inc");
	$result = mysql_query($sql, $contid);
	include("err_msg.inc");
	if ($result) {
		$lib_item_id = mysql_insert_id($contid);
		if (!$lib_item_id) {
			echo "<h2><font color=#ff0000>Failed to get new library item ID.</font></h2>";
			exit;
		}
	} else {
		echo "<h2><font color=#ff0000>Failed to enter data to library DB common table.</font></h2>";
		exit;
	}

	# step 6: insert data to table to specific category tables
	if ($lib_cat_list[$cat_id_lib] == "book") {		
		/* table: for_book. columns: book_id, lib_item_id, barcode, dewey, isbn, editor, 
		edition, volume, no_pages, year_published, pub_id, copyright, year_in, 
		year_cancellation, missing_record, */
		$sql = "INSERT INTO library.for_book VALUES('NULL', '$lib_item_id', 
			'$barcode', '$dewey', '$isbn', '$editor', '$edition', '$volume', '$no_pages', 
			'$year_published', '$pub_id', '$copyright', '$year_in', '$year_cancellation', 
			'$missing_record','$owner');";
	} elseif ($lib_cat_list[$cat_id_lib] == "patent") { 
		$elec_copy_add = trim($elec_copy_add);
		$sql = "INSERT INTO library.for_patent VALUES('NULL', '$lib_item_id', 
			'$country', '$patent_no', '$assignee', '$file_date', '$pub_date', 
			'$patent_date', '$intnl_clsfcn', '$citations', '$no_pages', 
			'$elec_copy_add');";
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
		$new_item_id = mysql_insert_id($contid);
		if (!$new_item_id) {
			echo "<h2><font color=#ff0000>Failed to get new $lib_cat_list[$cat_id_lib] item ID.</font></h2>";
			exit;
		}
	} else {
		echo "<h2><font color=#ff0000>Failed to enter data into $lib_cat_list[$cat_id_lib] table.</font></h2>";
		exit;
	}
	
	# step 7: insert data to table prim_auth
	for ($i=0; $i<$no_auth; $i++) {
		$auth_id = $author_id_list[$i];
		$sql = "INSERT INTO library.prim_auth VALUES('$lib_item_id', '$auth_id');";
		$result = mysql_query($sql);
		include("err_msg.inc");
	}
	
	# step 8: insert data to table prim_keyword
	for ($i=0; $i<$keyword_no; $i++) {
		$keyword_id = $keyword_id_list[$i];
		$sql = "INSERT INTO library.prim_keyword VALUES('$lib_item_id', '$keyword_id');";
		$result = mysql_query($sql);
		include("err_msg.inc");
	}
	
	# step 9: process feedback
	$newitem = 1;
	$beginmsg = "<h3>Process data for new \"$lib_cat_list[$cat_id_lib]\" to library with \"$no_valid_auth\" author(s).</h3>";
	$endingmsg = "<br>The above data has been processed successfully.<br><br>"
		."    Total number of library item is \"<font color=#0000ff>$lib_item_id</font>\".<br>"
		."    Total number of \"$lib_cat_list[$cat_id_lib]\" is \"<font color=#0000ff>$new_item_id</font>\".<br>"
		."<br>If any error exists you can modify it from "
		."modification page by click \"Modify An Item\" from left frame.<br><br>";
	include("lib_feedback.inc");
	
	# step 10: logging
	if ($priv != "00") {
		$date = date("Y-m-d");
		$sql = "INSERT INTO library.lib_entry VALUES('NULL', 'new', 
			'$lib_item_id', '$cat_id_lib', '$email_name', '$date');";
		$result = mysql_query($sql);
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
?>
</body>
