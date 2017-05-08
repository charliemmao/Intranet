<html>
<?php
	if ($lib_cat_list[$cat_id_lib] == "book") {
		list($lib_item_id, $cat_id, $author_id, $libtitle, $keyword_id, $abstract, 
			$barcode, $dewey, $isbn, $editor, $edition, $volume, $no_pages, $year_published, 
			$pub_id, $copyright, $year_in, $year_cancellation, $missing_record, $owner) = mysql_fetch_array($result);
		if ($modifylibrcd != "yes") {
			echo "<b>Result $i:</b>";
			echo displayval("Book Title", $libtitle);

			$temp = "&cat_id_lib=$cat_id_lib&lib_item_id=$lib_item_id&modifylibrcd=yes&end=end";
			include("userinfo.inc");
			$userstr= $userinfo.$temp;
			if ($priv == "00") {
				//echo "<br>Modify String: $temp.<br>";
			}
			$userstr	=	"?".base64_encode($userstr);
			$action = "[<a href=\"$PHP_SELF$userstr\"><b>Modify</b></a>] or ";
			$temp = "&cat_id_lib=$cat_id_lib&lib_item_id=$lib_item_id"
				."&libtitle=$libtitle&barcode=$barcode&dewey=$dewey"
				."&isbn=$isbn&deletelibrcd=yes&end=end";
			$userstr= $userinfo.$temp;
			if ($priv == "00") {
				//echo "<br>Delete String: $temp.<br><br>";
			}
			$userstr	=	"?".base64_encode($userstr);
			$action = $action."[<a href=\"$PHP_SELF$userstr\" ";
			$action = $action.'onClick="return (confirm_delete_lib_rcd())";';
			$action = $action."><b>Delete</b></a>]";
			$action = $action." this record.<br>";
			include("lib_displayauthor.inc");
			include("lib_displaykeywords.inc");
			if ($abstract) {
				echo strtoblue("Abstract:")." $abstract. ";
			}
			if ($barcode) {
				echo strtoblue("Barcode:")." $barcode. ";
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
			echo displayval('Publisher', $pub_list[$pub_id]).displayval('Copyright', $copyright);
			echo displayval('Owner', $owner);
			echo "<br>$action<br><br>";
		} else {
		/*
			if ($priv == "00") {
				echo "$lib_item_id, $cat_id, <br>author_id: $author_id, ".
					"<br>libtitle: $libtitle, <br>keyword_id: $keyword_id, <br>abstract: $abstract, <br>".
					"$barcode, $dewey, $isbn, $editor, $edition, $volume, $no_pages, $year_published, ".
					"$pub_id, $copyright, $year_in, $year_cancellation, $missing_record, $owner<br>";
			}
		//*/
		}

	} elseif ($lib_cat_list[$cat_id_lib] == "patent") {
		list($lib_item_id, $cat_id, $author_id, $libtitle, $keyword_id, $abstract, 
			$country, $patent_no, $assignee, $file_date, $pub_date, $patent_date, $intnl_clsfcn, 
			$citations, $no_pages, $elec_copy_add) = mysql_fetch_array($result);
		if ($modifylibrcd != "yes") {
			echo "<b>Result $i:</b>";
			echo displayval("Patent Title", $libtitle);

			$temp = "&cat_id_lib=$cat_id_lib&lib_item_id=$lib_item_id&modifylibrcd=yes&end=end";
			$userinfo = $userinfo.$temp;
			$userstr	=	"?".base64_encode($userinfo);
			$action = "[<a href=\"$PHP_SELF$userstr\"><b>Modify</b></a>] or ";
			$temp = "&cat_id_lib=$cat_id_lib&lib_item_id=$lib_item_id"
				."&libtitle=$libtitle&patent_no=$patent_no&deletelibrcd=yes&end=end";
			$userinfo = $userinfo.$temp;
			$userstr	=	"?".base64_encode($userinfo);
			$action = $action."[<a href=\"$PHP_SELF$userstr\" ";
			$action = $action.'onClick="return (confirm_delete_lib_rcd())";';
			$action = $action."><b>Delete</b></a>]";
			$action = $action." this record.<br>";
			include("lib_displayauthor.inc");
			include("lib_displaykeywords.inc");
			if (!$abstract) {
				$abstract = "NA";
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
			echo displayval("Abstract", $abstract);
			
			echo displayval("Country", $country);
			echo displayval("Patent No", $patent_no);
			echo displayval("Assignee", $assignee);
			echo displayval("Date filed", $file_date);
			echo displayval("Date published", $pub_date);
			echo displayval("Patent date", $patent_date);
			echo displayval("International classification", $intnl_clsfcn);
			echo displayval("Citations", $citations);
			echo displayval("No pages", $no_pages);
			if ($elec_copy_add) {
				$statuscontext = "PDF file title: $elec_copy_add.";
				include("self_status.inc"); //'.$status.'
				$elec_copy_add="rla/$elec_copy_add";
				echo "<br><b>Electronic copy of this patent is available for "
				."<a $status href=\"http://$patent_server$elec_copy_add\" target=\"_blank\">"
				."[View <img border=\"0\" src=\"../images/A22.GIF\" width=\"30\" height=\"26\">]</a>.</b>";		
			}
			echo "<br>$action<br><br>";
		} else {
		/*
			if ($priv == "00") {
				echo "$lib_item_id, $cat_id, <br>author_id: $author_id, <br>libtitle: $libtitle, ".
					"<br>keyword_id: $keyword_id, <br>abstract: $abstract, <br>".
					"$country, $patent_no, $assignee, $file_date, $pub_date, $patent_date, $intnl_clsfcn, ".
					"$citations, $no_pages, $elec_copy_add<br>";
			}
		//*/
		}
	} elseif ($lib_cat_list[$cat_id_lib] == "article") { 
		if ($modifylibrcd != "yes") {
			exit;
		}
	} elseif ($lib_cat_list[$cat_id_lib] == "report") { 
		if ($modifylibrcd != "yes") {
			exit;
		}
	} elseif ($lib_cat_list[$cat_id_lib] == "journal") { 
		if ($modifylibrcd != "yes") {
			exit;
		}
	} elseif ($lib_cat_list[$cat_id_lib] == "other") { 
		if ($modifylibrcd != "yes") {
			exit;
		}
	}
?>
</html>