<html>
<?php
	##	Process form fields required by all categories: author
	$namelist[1] = "firstname";
	$namelist[2] = "firstname index";
	$namelist[3] = "middlename";
	$namelist[4] = "middlename index";
	$namelist[5] = "lastname";
	$namelist[6] = "lastname index";
	$no_valid_auth = 0;
	
	for ($i=0; $i<$no_author_lib; $i++) {
		$firstname0 = "firstname".$i."0";
		$firstname1 = "firstname".$i."1";
		$middlename0 = "middlename".$i."0";
		$middlename1 = "middlename".$i."1";
		$lastname0 = "lastname".$i."0";
		$lastname1 = "lastname".$i."1";
	
		$authorlist[1][$i] = trim($$firstname0);
		$authorlist[2][$i] = trim($$firstname1);
		$authorlist[3][$i] = trim($$middlename0);
		$authorlist[4][$i] = trim($$middlename1);
		$authorlist[5][$i] = trim($$lastname0);
		$authorlist[6][$i] = trim($$lastname1);
	
		$auth_valid = 1;
		if ($authorlist[1][$i] == "" && $authorlist[2][$i] == 0) {
			break;
		} elseif ($authorlist[1][$i] != "" && $authorlist[2][$i] != 0) {
			break;
		} else {
			$no_valid_auth = $no_valid_auth + 1;
		}
	}
	$datavalidation_msg = "";
	##	Process form fields required by all categories:
	$libtitle = trim($libtitle);
	$keyword_id = trim($keyword_id);
	include("prohibited_chr_list.inc");
	for ($p=0; $p<count($prohibit_chr_in_keyword); $p++) {
		$needle = $prohibit_chr_in_keyword[$p];
		if (strstr($keyword_id, $needle)) {
			echo "<h2><font color=#ff0000>\"$needle\" is prohibited in the keyword character, data process has been aborted.</font>";
			exit;
		}
	}
	$keyword_id_original = $keyword_id;
	
	$abstract = trim($abstract);
	for ($p=0; $p<count($prohibit_chr_in_abstract); $p++) {
		$needle = $prohibit_chr_in_abstract[$p];
		if (strstr($abstract , $needle)) {
			echo "<h2><font color=#ff0000>\"$needle\" is prohibited character in the abstract, data process has been aborted.</font>";
			exit;
		}
	}

	$year_cancellation = trim($year_cancellation);
	$missing_record = trim($missing_record);
	
	if ($no_valid_auth == 0) {
		echo "<h2><font color=#ff0000>Author is not valid, data process has been cancelled.</font></h2>";
		back();
		exit;
	}
	if ($libtitle == "") {
		echo "<h2><font color=#ff0000>Title is not valid, data process has been cancelled.</font></h2>";
		back();
		exit;
	}

	if ($lib_cat_list[$cat_id_lib] == "book") {
		$year_in = trim($year_in);
		if ($year_in != "") {
			number_verify($year_in,"year","Year In");
		}

		$barcode = trim($barcode);	
		$dewey = trim($dewey);
		$isbn = trim($isbn);
		$editor = trim($editor);
		$edition = trim($edition);
		$no_pages = trim($no_pages);
		if ($no_pages != "") {
			number_verify($no_pages,"int","No Pages");
		} else {
			echo "<h2><font color=#ff0000>\"No Pages\" can not be empty, data process has been cancelled.</font></h2>";
			exit;
		}
		$year_published = trim($year_published);
		if ($year_published != "") {
			number_verify($year_published,"year","Year Published");
		} else {
			echo "<h2><font color=#ff0000>\"Year Published\" can not be empty, data process has been cancelled.</font></h2>";
			exit;
		}
		$pub_name = trim($pub_name);
		$pub_id = trim($pub_id);
		$pub_id_new = 1;
		if ($pub_name == "" && $pub_id >0) {
			$pub_id_new = 0;
		} elseif (($pub_name == "" && $pub_id == 0) || ($pub_name != "" && $pub_id > 0)) {
			echo "<h2><font color=#ff0000>Publisher is not valid, data process has been cancelled.</font></h2>";
			back();
			exit;
		}
		$copyright = trim($copyright);
	} elseif ($lib_cat_list[$cat_id_lib] == "patent") {
		$pat_msg = "";
		$country = trim($country);
		if ($country == "") {
			$pat_msg = $pat_msg."Country field: empty.<br>";
		}
		$patent_no = trim($patent_no);
		if ($patent_no == "") {
			$pat_msg = $pat_msg."Patent no field: empty.<br>";
		}
		$assignee = trim($assignee);
		if ($assignee == "") {
			$pat_msg = $pat_msg."Assignee field: empty.<br>";
		}
		$file_date = trim($file_date);
		if ($file_date == "") {
			$pat_msg = $pat_msg."Date filed field: empty.<br>";
		}
		$pub_date = trim($pub_date);
		if ($pub_date == "") {
			$pat_msg = $pat_msg."Publishing date field: empty.<br>";
		}
		$patent_date = trim($patent_date);
		if ($patent_date == "") {
			$pat_msg = $pat_msg."Patent date field: empty.<br>";
		}
		$intnl_clsfcn = trim($intnl_clsfcn);
		if ($intnl_clsfcn == "") {
			$pat_msg = $pat_msg."International classification field: empty.<br>";
		}
		$citations = trim($citations);
		if ($citations == "") {
			$pat_msg = $pat_msg."Citations field: empty.<br>";
		}
		$no_pages = trim($no_pages);
		if ($no_pages == "") {
			$pat_msg = $pat_msg."Number of pages field: empty.<br>";
		}
		if ($pat_msg != "") {
			echo "<hr><h3>Please check these fields:</h2><b><font color=#ff0000>".$pat_msg."</font></b>";
			//exit;
		}
	} elseif ($lib_cat_list[$cat_id_lib] == "article") {
		include("lib_dataprocess_temp.inc");
		exit;
	} elseif ($lib_cat_list[$cat_id_lib] == "report") {
		include("lib_dataprocess_temp.inc");
		exit;
	} elseif ($lib_cat_list[$cat_id_lib] == "journal") {
		include("lib_dataprocess_temp.inc");
		exit;
	} elseif ($lib_cat_list[$cat_id_lib] == "other") {
		include("lib_dataprocess_temp.inc");
		exit;
	}
	if ($datavalidation_msg) {
		//echo "<h2><font color=#ff0000>Check your form:<br>$datavalidation_msg</font></h2>";
		//back();
		//exit;
	}
	
	#######################################
	## enter data to table: lib_primlist. 
	#######################################
	# step 1: organise author id stream, add new names and author
	$no_auth = 0;
	for ($i=0; $i<$no_valid_auth; $i++) {
		$firstname_id = "";
		$middlename_id = ""; 
		$lastname_id = "";
		if ($debug){ echo "<br>author ".$i."<br>";}
		if ($authorlist[1][$i] != "") {
			## check whether is it a real new first name;
			/* table: auth_first. columns: firstname_id, firstname */
			isnewname(&$firstname_id, "firstname_id", "firstname", $authorlist[1][$i], "auth_first");
			if ($debug){ echo "firstname_id: $firstname_id<br>"; }
			if (!$firstname_id) {
				addnewname(&$firstname_id, $authorlist[1][$i], "auth_first");
				if ($debug){ echo "new firstname_id: $firstname_id<br>"; }
			}
		} else {
			$firstname_id = $authorlist[2][$i];
		}
		
		if ($authorlist[3][$i] != "") {
			## check whether is it a real new middle name;
			/* table: auth_middle. columns: middlename_id, middlename */
			isnewname(&$middlename_id, "middlename_id", "middlename", $authorlist[3][$i], "auth_middle");
			if ($debug){ echo "middlename_id: $middlename_id<br>"; }
			if (!$middlename_id) {
				addnewname(&$middlename_id, $authorlist[3][$i], "auth_middle");
				if ($debug){ echo "new middlename_id: $middlename_id<br>"; }
			}
		} elseif ($authorlist[4][$i]!=0) {
			$middlename_id = $authorlist[4][$i];
		}
		
		if ($authorlist[5][$i] != "") {
			## check whether is it a real new last name;
			/* table: auth_last. columns: lastname_id, lastname */
			isnewname(&$lastname_id, "lastname_id", "lastname", $authorlist[5][$i], "auth_last");
			if ($debug){ echo "lastname_id: $lastname_id<br>"; }
			if (!$lastname_id) {
				addnewname(&$lastname_id, $authorlist[5][$i], "auth_last");
				if ($debug){ echo "new lastname_id: $lastname_id<br>"; }
			}
		} else {
			$lastname_id = $authorlist[6][$i];
		}
		## check auth_id
		$sql = "SELECT auth_id FROM library.author where firstname_id='$firstname_id' and "
			."middlename_id='$middlename_id' and lastname_id='$lastname_id'";
		if ($debug){ echo $sql."<br>"; }
		$result = mysql_query($sql);
		$auth_id = 0;
		if (mysql_num_rows($result)) {
			list($auth_id) = mysql_fetch_array($result);			
		}
		if ($auth_id) {
			if ($debug){ echo "auth_id found: " .$auth_id."<br>"; }
		} else {
			//echo "<br>auth_id not found.<br>";
			// add new author
			$sql = "INSERT INTO library.author set auth_id='NULL', firstname_id='$firstname_id', "
				."middlename_id='$middlename_id', lastname_id='$lastname_id'";
			//echo $sql."<br>";
			include("connet_root_once.inc");
			$result	=	mysql_query($sql,$contid);
			include('err_msg.inc');
			if ($result) {
				$auth_id=	mysql_insert_id($contid);
				if ($debug){ echo "new auth_id: " .$auth_id."<br>"; }
			} else {
				$auth_id = 0;
			}
		}
		if ($auth_id) {
			$author_id_list[$no_auth] = $auth_id;
			$no_auth = $no_auth + 1;
		}
	}
	if ($no_auth != $no_valid_auth) {
		echo "<h2><font color=#ff0000>Problem to register authors, try again.</font></h2>";
		exit;
	}
	sort($author_id_list);
	$auth_id = implode($patsym, $author_id_list);

	if ($debug){ echo "Author ID text is $auth_id.<br>"; }
	/* table: author. columns: auth_id, title, firstname_id, middlename_id, 
		lastname_id, year_of_birth, year_of_death, email, homepage, telephone, fax, 
		addr_no_street, addr_suburb, addr_city, addr_state, addr_country, addr_postcode
	*/

	# step 2: check and register keywords
	/* table: keywords. columns: keyword_id, keyword */
	$keyword_list = explode(" ", $keyword_id);
	$keyword_no = 0;
 	for ($i=0; $i<count($keyword_list); $i++) {
 		$idfound = "";
 		if ($debug){ echo "Keyword $i: ".$keyword_list[$i]."<br>"; }
		isnewname(&$idfound, "keyword_id", "keyword", $keyword_list[$i], "keywords");
		if ($idfound) {
			if ($debug){ echo "Keyword id found: ".$idfound."<br>"; }
		} else {
			addnewname(&$idfound, $keyword_list[$i], "keywords");
			if ($idfound) {
				if ($debug){ echo "New keyword id: ".$idfound."<br>"; }
			}
		}
		if	($idfound) {
			$keyword_id_list[$keyword_no] = $idfound;
			$keyword_no = $keyword_no + 1;
		}
 	}
 	if ($keyword_no != count($keyword_list)) {
		echo "<h2><font color=#ff0000>Problem to register keywords, try again.</font></h2>";
		exit;
 	}
	$keyword_id = implode($patsym, $keyword_id_list);

	# step 3: check and register new publisher
	if ($lib_cat_list[$cat_id_lib] != "patent") { //book	patent	journal	article	report	other
		/* table: publisher. columns: pub_id, pub_name, email, homepage, telephone, fax, 
			po_box, addr_no_street, addr_suburb, addr_city, addr_state, addr_country, 
			addr_postcode, year_establish, year_of_closedown, */
		if ($pub_name != "") {
		 	isnewname(&$pub_id, "pub_id", "pub_name", $pub_name, "publisher");
		 	if ($pub_id) {
				if ($debug){ echo "Publisher id found: ".$pub_id."<br>"; }
			} else {
				$sql = "INSERT INTO library.publisher SET pub_id='null',pub_name='$pub_name';";
				if ($debug){ echo $sql."<br>";}
				include("connet_root_once.inc");
				$result	=	mysql_query($sql,$contid);
				include('err_msg.inc');
				if ($result)	{
					$pub_id=	mysql_insert_id($contid);
				}
				if ($pub_id) {
					if ($debug){ echo "New Publisher id: ".$pub_id."<br>"; }
				}
			}
		}
	}
?>
</html>