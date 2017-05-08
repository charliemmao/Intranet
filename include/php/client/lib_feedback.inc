<html>
<?php
	echo $beginmsg;
	echo "<b><font color=#0000ff>Data from Section A:</font><b><br>"
		."<p><table border=1>";
	echo "<tr><th>No</th><th>First Name</th><th>Middle Name</th><th>Last Name</th></tr>";
	for ($i=0; $i<$no_valid_auth; $i++) {
		$j = $i + 1;
		echo "<tr><th>$j</th>";
		for ($j=1; $j<=3; $j++) {
			$k1 = 2*$j - 1;
			$k2 = 2*$j;
			if ($authorlist[$k1][$i] != "") {
				echo "<td>".$authorlist[$k1][$i]."</td>";
			} else {
				if ($j == 1) {
					$temp = $firstname_list[$authorlist[$k2][$i]];
				} elseif ($j == 2) {
					$temp = $middlename_list[$authorlist[$k2][$i]];
				} elseif ($j == 3) {
					$temp = $lastname_list[$authorlist[$k2][$i]];
				}
				echo "<td>".$temp."</td>";
			}
		}
		echo "</tr>";
	}
	echo "</table><br>";
	echo "<table border=1><tr><th align=left>Title</th><td>";
		val_col($libtitle);
		echo "</td></tr>";
	echo "<tr><th align=left>Keywords</th><td>";
		val_col($keyword_id_original);
		echo "</td></tr>";
	echo "<tr><th align=left>Abstract</th><td>";
		val_col($abstract);
		echo "</td></tr>";
	echo "</table>";
	echo "<br>";
	
	echo "<b><font color=#0000ff>Data from Section B:</font><b><br><p><table border=1>";
	echo "<p><table border=1>";
	if ($lib_cat_list[$cat_id_lib] == "book") {
		echo "<tr><th align=left>Barcode</th><td>";
			val_col($barcode);
			echo "</td></tr>";
		echo "<tr><th align=left>Dewey</th><td>";
			val_col($dewey);
			echo "</td></tr>";
		echo "<tr><th align=left>ISBN</th><td>";
			val_col($isbn);
			echo "</td></tr>";
		echo "<tr><th align=left>Editor</th><td>";
			val_col($editor);
			echo "</td></tr>";
		echo "<tr><th align=left>Edition</th><td>";
			val_col($edition);
			echo "</td></tr>";
		echo "<tr><th align=left>Volume</th><td>";
			val_col($volume);
			echo "</td></tr>";
		echo "<tr><th align=left>No Pages</th><td>";
			val_col($no_pages);
			echo "</td></tr>";
		echo "<tr><th align=left>Year Published</th><td>";
			val_col($year_published);
			echo "</td></tr>";
		if ($pub_id_new != 1) {
			$pub_name = $pub_list[$pub_id];
		}
		echo "<tr><th align=left>Publisher</th><td>";
			val_col($pub_name);
			echo "</td></tr>";
		echo "<tr><th align=left>Copyright</th><td>";
			val_col($copyright);
			echo "</td></tr>";
		echo "<tr><th align=left>Year In</th><td>";
			val_col($year_in);
			echo "</td></tr>";
		if ($newitem != 1) {
			echo "<tr><th align=left>Year Cancellation</th><td>";
				val_col($year_cancellation);
				echo "</td></tr>";
			echo "<tr><th align=left>Missing Record</th><td>";
				val_col($missing_record);
				echo "</td></tr>";
		}
		echo "<tr><th align=left>Owner</th><td>";
			val_col($owner);
			echo "</td></tr>";
	} elseif ($lib_cat_list[$cat_id_lib] == "patent") {
		echo "<tr><th align=left>Country</th><td>";
			val_col($country);
			echo "</td></tr>";
		echo "<tr><th align=left>Patent No</th><td>";
			val_col($patent_no);
			echo "</td></tr>";
		echo "<tr><th align=left>Assignee</th><td>";
			val_col($assignee);
			echo "</td></tr>";
		echo "<tr><th align=left>Date Filed</th><td>";
			val_col($file_date);
			echo "</td></tr>";
		echo "<tr><th align=left>Date Published</th><td>";
			val_col($pub_date);
			echo "</td></tr>";
		echo "<tr><th align=left>Patent Date</th><td>";
			val_col($patent_date);
			echo "</td></tr>";
		echo "<tr><th align=left>Int. Cl.</th><td>";
			val_col($intnl_clsfcn);
			echo "</td></tr>";
		echo "<tr><th align=left>Citations</th><td>";
			val_col($citations);
			echo "</td></tr>";
		echo "<tr><th align=left>No Pages</th><td>";
			val_col($no_pages);
			echo "</td></tr>";
		echo "<tr><th align=left>PDF File Title</th><td>";
			if ($elec_copy_add) {
				$str="$SERVER_NAME$patdir2"."rla/$elec_copy_add";
				$elec_copy_add="<A href=\"http://$str\" target=_blank><B>[View]</B></A>";
			}
			val_col($elec_copy_add);
			echo "</td></tr>";
	} elseif ($lib_cat_list[$cat_id_lib] == "article") {

	} elseif ($lib_cat_list[$cat_id_lib] == "report") {

	} elseif ($lib_cat_list[$cat_id_lib] == "journal") {

	} elseif ($lib_cat_list[$cat_id_lib] == "other") {

	}
	echo "</table>";
	echo $endingmsg;
	/*
	if ($lib_cat_list[$cat_id_lib] == "patent" && $elec_copy_add == "YES") {
		echo "<hr>";
		echo "<h3>Upload Electronic Copy</h3>";
		echo "<p><form ENCTYPE=\"multipart/form-data\" method=\"POST\" action=\"$PHP_SELF\" name=\"uploadcopyfrm\">";
		include("userstr.inc");
		echo "<input type=\"hidden\" name=\"patent_id\" value=\"$new_item_id\">";
		echo "<input type=\"hidden\" name=\"lib_item_id\" value=\"$lib_item_id\">";
		echo '<input type="hidden" name="MAX_FILE_SIZE" value="20000000">';
		echo "<b>Select file:</b> <input name=\"elec_copy_add\" TYPE=\"file\" size=\"50\">";
		echo '<br><br><input onClick="return (uploadverify());"';
		echo ' onSubmit="return (uploadverify());"';
		echo " type=\"submit\" value=\"Upload Electronic Copy\" name=\"upload\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		echo "<input ";
		echo " type=\"submit\" value=\"Cancel\" name=\"cancel\">";
		echo "</form>";
	}
	*/
?>