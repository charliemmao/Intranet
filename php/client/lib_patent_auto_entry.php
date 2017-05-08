<html>
<head>
<title>Library Patent Auto Entry From Buffer DB Table</title>
</head>
<body leftmargin="20" bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG">

<?php
echo "<id=top><h2 align=center>Output of Library Patent Auto Entry From Buffer DB Table</h2>";

$startstr = "<p align=center>Processing start at: ";
$startstr = $startstr.date('d/m/Y h:i:s');
echo "$startstr<hr>";

#include("admin_access.inc");
include('rla_functions.inc');
include("connet_root_once.inc");

############	Find out patent entries to be processed
$sql = "SELECT * FROM library.dumppatent";
$result = mysql_query($sql);
$filelog = __FILE__;
$linelog = __LINE__;
include("err_msg.inc");
$norcd = mysql_num_rows($result);

$sql = "SELECT * FROM library.dumppatent where processed='n'";
$result = mysql_query($sql);
$filelog = __FILE__;
$linelog = __LINE__;
include("err_msg.inc");
$notoprocess = mysql_num_rows($result);

$to = "admin\@rla.com.au";
$subject = "Patent DATA Auto-Process (Don't reply to this message.)";
$header	=	"From: admin@rla.com.au\nReply-To: admin@rla.com.au\n";
$msgbody = "Total records in the buffer DB are $norcd.\n";
$msgbody = $msgbody."Total unprocessed records in the buffer DB are $notoprocess.\n\n";

if ($notoprocess == 0) {
	echo "<h3>All Records ($norcd) in Buffer Database Table <font color=#0000ff>library.dumppatent
		</font> have been processed.</h3>";
	$msgbody = $msgbody.getenv("SERVER_NAME")."\n".date("Y-m-d h:i:s");
	//mail ($to, $subject, $msgbody, $header);
	
	$from = "admin\@rla.com.au";
	$cc = "";
	$msg = $msgbody;
	system("./_mail.pl \"$from\" \"$to\" \"$cc\" \"$subject\" \"$msg\"",$var);

	exit;
} else {
	echo "<h3>Number of Entry in Table <font color=#0000ff>library.dumppatent</font>: $notoprocess ($norcd).</h3>";
	$msgbody = $msgbody."\tProcess started at: ".date("Y-m-d h:i:s")."\n";
}
	
############	process
$cat_id = 2; //patent
$new1 = "<font color=#ff0000>";
$new2 = "</font>";
$sql = "SELECT id, country, patent_no, patent_title, assignee, inventor, 
		file_date, patent_date, classification, keycode, pct, keywords, abstract, 
		citations, abstract_only, electronic_copy, processed FROM library.dumppatent 
		WHERE processed='N';"; // and electronic_copy='y'
//echo "$sql<br>";
$result0 = mysql_query($sql);
$filelog = __FILE__;
$linelog = __LINE__;
include("err_msg.inc");
$patdup = 0;

while (list($id, $country, $patent_no, $patent_title, $assignee, $inventor, 
		$file_date, $patent_date, $classification, $keycode, $pct, $keywords, $abstract, 
		$citations, $abstract_only, $electronic_copy, $processed) = mysql_fetch_array($result0)) {

		$patent_title = trim($patent_title);
		$patent_title = ereg_replace("'","\'",$patent_title);
		$assignee = trim($assignee);
		$assignee = ereg_replace("'","\'",$assignee);		
		$inventor = trim($inventor);
		$inventor = ereg_replace("'","\'",$inventor);
		$classification = trim($classification);
		$classification = ereg_replace("'","\'",$classification);
		$keywords = trim($keywords);
		$keywords = ereg_replace("'","\'",$keywords);		
		$abstract = trim($abstract);
		$abstract = ereg_replace("'","\'",$abstract);
		$citations = trim($citations);
		$citations = ereg_replace("'","\'",$citations);
		if ($electronic_copy == "y") {
			$elec_copy_add = "$patent_no.pdf";
		}
	/*
		$classification = trim($classification);
		//$keywords = trim($keywords);
		$abstract = trim($abstract);
		$citations = trim($citations);
	//*/
		//echo "<br><br><b>Patent DumpDB ID: $id.</b><br>";
		/*
		echo "<table border=1>";
			echo "<tr><th align=left>ID</th><td>$id</td></tr>";
			echo "<tr><th align=left>Country</th><td>$country</td></tr>";
			echo "<tr><th align=left>NO</th><td>$patent_no</td></tr>";
			echo "<tr><th align=left>Title</th><td>$patent_title</td></tr>";
			echo "<tr><th align=left>Assignee</th><td>$assignee</td></tr>";
			echo "<tr><th align=left>Inventor</th><td>$inventor</td></tr>";
			echo "<tr><th align=left>File Date</th><td>$file_date</td></tr>";
			echo "<tr><th align=left>Patent Date</th><td>$patent_date</td></tr>";
			echo "<tr><th align=left>Classification</th><td>$classification</td></tr>";
			echo "<tr><th align=left>Keycode</th><td>$keycode</td></tr>";
			echo "<tr><th align=left>PCT</th><td>$pct</td></tr>";
			echo "<tr><th align=left>Keyword</th><td>$keywords</td></tr>";
			echo "<tr><th align=left>Abstract</th><td>$abstract</td></tr>";
			echo "<tr><th align=left>Citation</th><td>$citations</td></tr>";
			echo "<tr><th align=left>Abstract Only?</th><td>$abstract_only</td></tr>";
			echo "<tr><th align=left>Electronic Copy?</th><td>$electronic_copy</td></tr>";
			echo "<tr><th align=left>Is Processed?</th><td>$processed</td></tr>";
		echo "</table>";
		//*/
		
############################################################
	# step 1: find out whether this patent is already in DB
	$sqlduplicate = "SELECT patent_id as patid FROM library.for_patent WHERE patent_no='$patent_no';";
	//echo "$sqlduplicate<br>";
	$resultduplicate = mysql_query($sqlduplicate);
	$filelog = __FILE__;
	$linelog = __LINE__;
	include("err_msg.inc");
	list($patid) = mysql_fetch_array($resultduplicate);
	echo "<br><br>";
	if ($patid) {
		echo "<b><font color=#ff0000>Patent $patent_no ($notoprocess: $id) is already in the library.</font></b><br>";
		$sql = "UPDATE library.dumppatent SET processed='Y' WHERE id='$id';";
		//echo $sql."<br>";
		$result = mysql_query($sql);
		include("err_msg.inc");
		$patdup++;
	} else {
		echo "<b>Process patent $patent_no ($notoprocess: $id).</b><br>";

############################################################
	# step 2: insert data to table lib_primlist to find lib_item_id
		$sql = "INSERT INTO library.lib_primlist SET lib_item_id='NULL', 
			cat_id='$cat_id', libtitle='$patent_title', 
			abstract='$abstract';";
		$result = mysql_query($sql);
		include("err_msg.inc");
		if ($result) {
			$lib_item_id = mysql_insert_id();
			if (!$lib_item_id) {
				echo "<h2><font color=#ff0000>Failed to get new library item ID.</font></h2>";
				exit;
			}
		} else {
			echo "<h2><font color=#ff0000>Failed to enter data to library DB common table.</font></h2>";
			exit;
		}
		echo "lib_primlist ID: $lib_item_id. <br>"; //$sql<br>

###########################################################
	# step 3: insert data to table to patent tables
		$sql = "INSERT INTO library.for_patent SET patent_id='NULL', 
			lib_item_id='$lib_item_id', country='$country', patent_no='$patent_no', 
			assignee='$assignee', file_date='$file_date', pub_date='$pub_date', 
			patent_date='$patent_date', intnl_clsfcn='$classification', 
			citations='$citations', no_pages='$no_pages', elec_copy_add='$elec_copy_add';";
		$result = mysql_query($sql, $contid);
		include("err_msg.inc");
		if ($result) {
			$new_item_id = mysql_insert_id($contid);
			if (!$new_item_id) {
				echo "<h2><font color=#ff0000>Failed to get new patent item ID.</font></h2>";
				exit;
			}
		} else {
			echo "<h2><font color=#ff0000>Failed to enter data into patent table.</font></h2>";
			exit;
		}
		//echo "for_patent: $new_item_id. $sql<br>";
		
###########################################################
	# step 4: insert data to table prim_keyword
		## keyword start
		$keyword_no = 0;
		if ($keywords) {
			$keywords = ereg_replace(",", " ", $keywords);
			$keylist = explode(" ",$keywords);
			for ($ik=0; $ik<count($keylist); $ik++) {
				$tmpkws= trim($keylist[$ik]);
				if ($tmpkws) {
					$sql = "SELECT keyword_id FROM library.keywords WHERE keyword='$tmpkws';";
					$result = mysql_query($sql);
					include("err_msg.inc");
					list($keyword_id) = mysql_fetch_array($result);
					if (!$keyword_id) {
						$sql = "INSERT INTO library.keywords VALUES('$keyword_id', '$tmpkws');";
						$result = mysql_query($sql);
						include("err_msg.inc");
						$keyword_id = mysql_insert_id();
						//echo "$new1<b>New keyword: $tmpkws. ID: $keyword_id$new2<br>";
					} else {
						//echo "<b>Keyword: $tmpkws. ID: $keyword_id</b><br>";
					}
					$keyword_id_list[$keyword_no] = $keyword_id;
					$keyword_no++;
				}
			}
		}
		for ($i=0; $i<$keyword_no; $i++) {
			$keyword_id = $keyword_id_list[$i];
			$sql = "INSERT INTO library.prim_keyword VALUES('$lib_item_id', '$keyword_id');";
			$result = mysql_query($sql);
			include("err_msg.inc");
		}
		$keyword_id = "";
		for ($i=0; $i<$keyword_no-1; $i++) {
			$keyword_id = $keyword_id.$keyword_id_list[$i].",";
		}
		$keyword_id = $keyword_id.$keyword_id_list[$keyword_no-1];
		## keyword end */

###########################################################
	# step 5: insert data to table prim_auth
		## inventor start
		//echo "<br><b>ID: $id. Inventor List: $inventor</b>";
		//echo "<table border>";
		//echo "<tr><th>Full Name</th><th>First Name</th><th>Middle Name</th><th>Given Name</th></tr>";
		$auth = explode(";",$inventor);
		$no_auth = 0;
		for ($i=0; $i<count($auth); $i++) {
			$str1au = explode(",",$auth[$i]);
			if (trim($str1au[0]) && trim($str1au[0])) {
				$aulist[0][$no_auth] = trim($str1au[0]);	//given name
					$givenn = $aulist[0][$i];
				
				$str1au[1] = trim($str1au[1]);
				$str2au = explode(" ",$str1au[1]);
				$aulist[1][$no_auth] = trim($str2au[0]);	//first name
					$firstn = $aulist[1][$i];
				
				$aulist[2][$no_auth] = trim($str2au[1]);	//middle name
					$midnn = $aulist[2][$i];
				
				if (!$firstn) {
					$firstn = "&nbsp;";
				}
				if (!$midnn) {
					$midnn = "&nbsp;";
				}
				//echo "<tr><td>$auth[$i]</td><td>$firstn</td><td>$midnn</td><td>$givenn</td></tr>";
				$no_auth++;
			}
		}
		//echo "</table>";
		
		for ($i=0; $i<$no_auth; $i++) {
			$lastname = $aulist[0][$i];
			$firstname = $aulist[1][$i];
			$middlename = $aulist[2][$i];
			
			$sql = "SELECT firstname_id FROM library.auth_first ".
				"WHERE firstname='$firstname';";
			$result = mysql_query($sql);
			include("err_msg.inc");
			list($firstname_id) = mysql_fetch_array($result);
			if (!$firstname_id) {
				$sql = "INSERT INTO library.auth_first VALUES('NULL', '$firstname');";
				$result = mysql_query($sql);
				include("err_msg.inc");
				$firstname_id = mysql_insert_id();
				//echo "$new1<b>New First Name: $firstname ($firstname_id)$new2<br>";
			} else {
				//echo "<b>First Name ID: $firstname_id</b><br>";
			}

			$sql = "SELECT middlename_id FROM library.auth_middle ".
				"WHERE middlename='$middlename';";
			$result = mysql_query($sql);
			include("err_msg.inc");
			list($middlename_id) = mysql_fetch_array($result);
			if (!$middlename_id) {
				$sql = "INSERT INTO library.auth_middle VALUES('NULL', '$middlename');";
				$result = mysql_query($sql);
				include("err_msg.inc");
				$middlename_id = mysql_insert_id();
				//echo "$new1<b>New Middle Name: $middlename ($middlename_id)$new2<br>";
			} else {
				//echo "<b>Middle Name ID: $middlename_id</b><br>";
			}

			$sql = "SELECT lastname_id FROM library.auth_last ".
				"WHERE lastname='$lastname';";
			$result = mysql_query($sql);
			include("err_msg.inc");
			list($lastname_id) = mysql_fetch_array($result);
			if (!$lastname_id) {
				$sql = "INSERT INTO library.auth_last VALUES('NULL', '$lastname');";
				$result = mysql_query($sql);
				include("err_msg.inc");
				$lastname_id = mysql_insert_id();
				//echo "$new1<b>New Last Name: $lastname ($lastname_id)$new2<br>";
			} else {
				//echo "<b>Last Name ID: $lastname_id</b><br>";
			}

			$sql = "SELECT auth_id FROM library.author WHERE ". 
				"firstname_id='$firstname_id' and middlename_id='$middlename_id' and 
				lastname_id='$lastname_id';";
			$result = mysql_query($sql);
			include("err_msg.inc");
			list($auth_id) = mysql_fetch_array($result);
			if (!$auth_id) {
				$sql = "INSERT INTO library.author SET ".
					"firstname_id='$firstname_id', middlename_id='$middlename_id', ".
					"lastname_id='$lastname_id';";
				$result = mysql_query($sql);
				include("err_msg.inc");
				$auth_id = mysql_insert_id();
				//echo "$new1<b>New Author ID: $auth_id</b>$new2<br>";
			} else {
				//echo "<b>Author ID: $auth_id</b><br>";
			}
			$author_id_list[$i] = $auth_id;
		}
		for ($i=0; $i<$no_auth; $i++) {
			$auth_id = $author_id_list[$i];
			$sql = "INSERT INTO library.prim_auth VALUES('$lib_item_id', '$auth_id');";
			$result = mysql_query($sql);
			include("err_msg.inc");
		}
		$author_id = "";
		for ($i=0; $i<$no_auth-1; $i++) {
			$author_id = $author_id.$author_id_list[$i].",";
		}
		$author_id = $author_id.$author_id_list[$no_auth-1];
		// inventor end*/
		
		# step 5: update table prim_keyword
		$sql = "UPDATE library.lib_primlist SET author_id='$author_id', keyword_id='$keyword_id' ".
			"WHERE lib_item_id='$lib_item_id';";
		//echo $sql."<br>";
		$result = mysql_query($sql);
		include("err_msg.inc");

###########################################################
	# step 6: set processed="Y" in table dumppatent
		$sql = "UPDATE library.dumppatent SET processed='Y' WHERE id='$id';";
		//echo $sql."<br>";
		$result = mysql_query($sql);
		include("err_msg.inc");	
		//exit;
	}
}
echo "<hr>";
echo "<b>$startstr<br><p align=center>Processing end at: ";
echo date('d/m/Y h:i:s');
echo "</b>";
	//$country		$patent_no		$patent_title		$assignee		$inventor	$file_date		$patent_date	
	//$classification		$keycode	$pct	$keywords	$abstract	$citations		$abstract_only	
	//$electronic_copy	$processed

echo "<hr><a href=#top>Back to top</a><br><br>";
	$msgbody = $msgbody."\tProcess ended at: ".date("Y-m-d h:i:s")."\n\n";
	if ($patdup>0) {
		$msgbody = $msgbody."$patdup duplicates have been found and discarded.\n";
		$notoprocess = $notoprocess - $patdup;
	}
	$msgbody = $msgbody."$notoprocess new patent records have been processed successfully.\n\n";
	$msgbody = $msgbody.getenv("SERVER_NAME")."\n".date("Y-m-d h:i:s");
	//mail ($to, $subject, $msgbody, $header);
	$from = "admin\@rla.com.au";
	$to = "cmm\@rla.com.au";
	$cc = "";
	$msg = "$msgbody";
	system("./_mail.pl \"$from\" \"$to\" \"$cc\" \"$subject\" \"$msg\"",$var);
	
	$to = "llm\@rla.com.au";
	//mail ($to, $subject, $msgbody, $header);
	//system("./_mail.pl \"$from\" \"$to\" \"$cc\" \"$subject\" \"$msg\"",$var);
?>
</body>
