<html>
<?php
	$patsym = ",";
	if ($lib_cat_list[$cat_id_lib] == "book") {
		list($barcode, $dewey, $isbn, $editor, $edition, $volume, $no_pages, $year_published, 
			$pub_id, $copyright, $year_in, $year_cancellation, $missing_record, $owner) = mysql_fetch_array($result);
		if ($priv == "00") {
			//echo $sql."<br>";
			//echo "$sql<br>";
			//echo $abstract."<br>";
		}
			if ($searchby == "title") {
				for ($i0=0; $i0<$au_key_no; $i0++) {
					$word0 = $keywordlist[$i0];
					$word0 = strtolower($word0);
					$len1 = strlen($libtitle);
					$word1 = "<font color=#ff0000><b>$word0</b></font>";
					$libtitle= ereg_replace($word0, $word1, $libtitle);
					$len2 = strlen($libtitle);
					if ($len1 == $len2) {
						$word0 = strtoupper($word0);
						$word1 = strtoupper($word1);
						$libtitle= ereg_replace($word0, $word1, $libtitle);
					}
				}
			}
			if ($searchby == "abstracts") {
				for ($i0=0; $i0<$au_key_no; $i0++) {
					$word0 = $keywordlist[$i0];
					$word0 = strtolower($word0);
					$len1 = strlen($libtitle);
					$word1 = "<font color=#ff0000><b>$word0</b></font>";
					$abstract = ereg_replace($word0, $word1, $abstract);
					$len2 = strlen($libtitle);
					if ($len1 == $len2) {
						$word0 = strtoupper($word0);
						$word1 = strtoupper($word1);
						$abstract= ereg_replace($word0, $word1, $abstract);
					}
				}
			}
			echo displayval("Book Title", $libtitle);
			echo "<br>";

			include("lib_displayaukey_clientsearch.inc");
			if ($abstract) {
				echo strtoblue("Abstract:")." $abstract ";
			}
			if ($dewey) {
				echo strtoblue("Dewey:")." $dewey. ";
			}
			if ($isbn) {
				echo strtoblue("ISBN:")." $isbn. ";
			}
			echo displayval('Editor', $editor).displayval('Edition', $edition);
			echo displayval('Volume', $volume).displayval('No Pages', $no_pages);
			echo displayval('Year Published', $year_published);
			$Publisher = rmlastdot($pub_list[$pub_id]);
			echo displayval('Publisher', $Publisher).displayval('Copyright', $copyright);
			//echo displayval('Owner', $owner);
			if ($barcode) {
				echo strtoblue("&nbspBarcode:")." $barcode. ";
			}
			
			//book status
			$sqlstatus = "SELECT status_id, borrower_email_name, date_out 
				FROM library.lib_item_status WHERE 
				lib_item_id='$lib_item_id' and barcode='$barcode' and date_return='$date_return'
				ORDER BY status_id;";
			$restatus = mysql_query($sqlstatus);
			include("err_msg.inc");
			list($status_id, $borrower_email_name, $date_out, $date_return) = mysql_fetch_array($restatus);
			//echo "<br>$sqlstatus<br>$lib_item_id, $barcode, $status_id, $borrower_email_name, $date_out, $date_return<br>";
			echo "<br>";
			if (!$status_id) {
				echo displayval("Status", "<b>In Library</b>");
				$msg="search=y&lib_item_id=$lib_item_id&barcode=$barcode";
				$msg="&barcode=$barcode";
				echo "<a href=lib_book_loan_process.php?$msg target=blank><b>[Action]</b></a>";
			} else {
				$sql = "SELECT first_name as fname, last_name as lname from timesheet.employee 
					where email_name='$borrower_email_name'";
				$restatus = mysql_query($sql);
				include("err_msg.inc");
				list($fname, $lname) = mysql_fetch_array($restatus);
				$tmp = "<font color=#ff00ff><b>The book was out on $date_out, please see $fname $lname if you want it</b></font>";
				echo displayval("Status", $tmp);
			}
			echo "<br><br>";
			
	} elseif ($lib_cat_list[$cat_id_lib] == "patent") {
		list($country, $patent_no, $assignee, $file_date, $pub_date, $patent_date, $intnl_clsfcn, 
			$citations, $no_pages, $elec_copy_add) = mysql_fetch_array($result);
			//echo "<b>Result $i:</b>";
			if (!$abstract) {
				$abstract = "NA";
			} else {
				$abstract = ucwords(strtolower($abstract));	
			}
			if (!$assignee) {
				$assignee= "NA";
			}
			if (!$file_date) {
				$file_date= "NA";
			}
			if (!$pub_date) {
				$pub_date= "NA";
			}
			if (!$patent_date) {
				$patent_date= "NA";
			}
			if (!$intnl_clsfcn) {
				$intnl_clsfcn= "NA";
			}
			if (!$citations) {
				$citations= "NA";
			}
			if (!$no_pages) {
				$no_pages= "NA";
			}
			
			$echostr = displayval("Country", $country);
			displayval("Patent No", $patent_no);
			if ($searchby == "title") {
				for ($i0=0; $i0<$au_key_no; $i0++) {
					$word0 = $keywordlist[$i0];
					$word0 = strtolower($word0);
					$len1 = strlen($libtitle);
					$word1 = "<font color=#ff0000><b>$word0</b></font>";
					$libtitle= ereg_replace($word0, $word1, $libtitle);
					$len2 = strlen($libtitle);
					if ($len1 == $len2) {
						$word0 = strtoupper($word0);
						$word1 = strtoupper($word1);
						$libtitle= ereg_replace($word0, $word1, $libtitle);
					}
				}
			}
			if ($searchby == "abstracts") {
				for ($i0=0; $i0<$au_key_no; $i0++) {
					$word0 = $keywordlist[$i0];
					$word0 = strtolower($word0);
					$len1 = strlen($libtitle);
					$word1 = "<font color=#ff0000><b>$word0</b></font>";
					$abstract = ereg_replace($word0, $word1, $abstract);
					$len2 = strlen($libtitle);
					if ($len1 == $len2) {
						$word0 = strtoupper($word0);
						$word1 = strtoupper($word1);
						$abstract = ereg_replace($word0, $word1, $abstract);
					}
				}
			}
			$libtitle = ucwords(strtolower($libtitle));	
			displayval("Patent Title", $libtitle);
			displayval("Assignee", $assignee);

		//echo "AUID: $author_id, KWID: $keyword_id";
		if ($author_id != "") {
			$id_tmp = explode($patsym, $author_id);
			$no_author_lib = count($id_tmp);
			$authorstr = "";
			for ($ai=0; $ai<$no_author_lib; $ai++) {
				if ($ai!=0) {
					$authorstr = $authorstr."; ";
				}
				$tmpid = $id_tmp[$ai];
				$auth_sql = "SELECT firstname_id, middlename_id, lastname_id "
						."FROM library.author WHERE auth_id='$tmpid';";
				$auth_result = mysql_query($auth_sql);
				if (mysql_num_rows($auth_result)) {
					list($firstname_id, $middlename_id, $lastname_id) = mysql_fetch_array($auth_result);
				}
				//echo "$firstname_id, $middlename_id, $lastname_id";
				if ($searchby == "author") {
					$fstr1 = "";
					$fstr2 = "";
					for ($i0=0; $i0<count($lnameid); $i0++) {
						if ($lnameid[$i0] == $lastname_id) {
							$fstr1 = "<font color=#ff0000><b>";
							$fstr2 = "</b></font>";
							break;
						}
					}
				}
	
				if ($lastname_id) {
					$sqlnameid = "SELECT lastname FROM library.auth_last WHERE lastname_id='$lastname_id';";
					$resultname = mysql_query($sqlnameid);
					include("err_msg.inc");
					list($lastname) = mysql_fetch_array($resultname);
					if ($lastname) {
						$authorstr = $authorstr.$fstr1."$lastname".$fstr2;
					}
				}
				if ($firstname_id) {
					$sqlnameid = "SELECT firstname FROM library.auth_first WHERE firstname_id='$firstname_id';";
					$resultname = mysql_query($sqlnameid);
					include("err_msg.inc");
					list($firstname) = mysql_fetch_array($resultname);
					if ($firstname) {
						$authorstr = $authorstr."$fstr1, $firstname$fstr2";
					}
				}
				if ($middlename_id) {
					$sqlnameid = "SELECT middlename FROM library.auth_middle WHERE middlename_id='$middlename_id';";
					$resultname = mysql_query($sqlnameid);
					include("err_msg.inc");
					list($middlename) = mysql_fetch_array($resultname);
					$middlename = ereg_replace("\.", "", $middlename);
					if ($middlename) {
						$authorstr = $authorstr."$fstr1 $middlename$fstr2";
					}
				}
			}

			displayval("Author", $authorstr);
		}

		if ($keyword_id != "") {
			$id_tmp = explode($patsym, $keyword_id);
			$noid = count($id_tmp);
			for ($ai=0; $ai<$noid; $ai++) {
				$tmpid = $id_tmp[$ai];
				if ($tmpid) {
					$sqlnameid = "SELECT keyword FROM library.keywords WHERE keyword_id='$tmpid';";
					$resultname = mysql_query($sqlnameid);
					include("err_msg.inc");
					list($keyword) = mysql_fetch_array($resultname);
				
					if ($searchby == "keyword") {
						for ($i0 =0; $i0<count($keywordlist); $i0++) {
							$tk = trim($keywordlist[$i0]);
							if ($tk == $keyword) {
								$keyword = "<font color=#ff0000><b>$keyword</b></font>";
								$keyword = strtoupper($keyword);
							}
						}
					}
					if ($ai==0) {
						$keywords = $keyword;
					} else {
						$keywords = $keywords." $keyword";
					}
				}
			}
		}
		$keywords = trim($keywords);
		displayval("Keywords", $keywords);
		$keywords = "";
			
		displayval("Date filed", $file_date);
		displayval("Date published", $pub_date);
		displayval("Patent date", $patent_date);
		displayval("Intnl classifn", $intnl_clsfcn);
		$abstract = rmlastdot($abstract);
		displayval("Abstract", $abstract);
		$citations= rmlastdot($citations);
		displayval("Citations", $citations);
		displayval("No pages", $no_pages);
		if ($elec_copy_add) {
			$statuscontext = "File title: $elec_copy_add.";
			include("self_status.inc"); //'.$status.'
			echo "<br>	<a $status href=\"http://$patent_server$elec_copy_add\" target=\"_blank\"><B>
				Link to electronic copy.</B></a>";
		}	
	} elseif ($lib_cat_list[$cat_id_lib] == "technotes") { 
	
	}
?>
</html>