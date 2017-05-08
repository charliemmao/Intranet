<html>
<?php
	if ($matchmsg) {
		echo "<b>No $i:$matchmsg.</b><br>";
	} else {
		echo "<b>No $i.</b><br>";
	}
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

			echo displayval("Country", $country);
			echo displayval("Patent No", $patent_no);
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
			echo displayval("Patent Title", $libtitle);
			echo displayval("Assignee", $assignee);

			include("lib_displayaukey_clientsearch.inc");
			
			echo displayval("Date filed", $file_date);
			echo displayval("Date published", $pub_date);
			echo displayval("Patent date", $patent_date);
			echo displayval("Intnl classifn", $intnl_clsfcn);
			$abstract = rmlastdot($abstract);
			echo displayval("Abstract", $abstract);
			$citations= rmlastdot($citations);
			echo displayval("Citations", $citations);
			echo displayval("No pages", $no_pages);
			if ($elec_copy_add) {
			/*
				$sl = strlen($elec_copy_add);
				if (strtolower(substr($elec_copy_add,$sl-4,$sl)) != ".pdf") {
					$elec_copy_add = $elec_copy_add.".pdf";
				}
			//*/
				$statuscontext = "File title: $elec_copy_add.";
				include("self_status.inc"); //'.$status.'

				echo "<br>	<img border=\"0\" height=\"13\" src=\"../images/A22.GIF\" width=\"15\">
				<a $status href=\"http://$patent_server$elec_copy_add\" target=\"_blank\"><B>
				Link to electronic copy.</B></a><br>";
			}
//if ($priv == "00"){
			$statuscontext = "Make Comments to Patent: $patent_no.";
			include("self_status.inc"); //'.$status.'

			echo "<br><font color=#ff0000><b>NEW</b></font>
			<a $status href=\"http://patent.rla.com.au/php/lib_pat_comments.php?patentno=$patent_no\" target=\"_blank\"><B>Submit comment for patent $patent_no.</B></a><br>";
//}
			echo "<br><br>";
	} elseif ($lib_cat_list[$cat_id_lib] == "technotes") { 
	}
?>
</html>