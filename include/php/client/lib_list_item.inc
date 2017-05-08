<html>
<?php
		$sql = "SELECT lib_item_id, cat_id, author_id, libtitle, keyword_id, abstract ".
			"FROM library.lib_primlist WHERE lib_item_id='$lib_item_id';";
		$result = mysql_query($sql);
		include("err_msg.inc");
		//echo $sql."<br><br>";
		list($lib_item_id, $cat_id, $author_id, $libtitle, $keyword_id, $abstract) 	= mysql_fetch_array($result);
		$cat_id_lib = $cat_id;
		/*
		echo $author_id."<br>";
		echo $lib_item_id."<br>";
		echo $lib_cat_list[$cat_id_lib]."<br>";
		echo $libtitle."<br>";
		echo $keyword_id."<br>";
		echo $abstract."<br>";
		//*/
		if ($priv == "00") {
			$sql1 = "<br>".$sql;
		}
		if ($lib_cat_list[$cat_id_lib] == "book") {
			$sql = "barcode, dewey, isbn, editor, edition, volume, "
			."no_pages, year_published, pub_id, copyright, year_in, "
			."year_cancellation, missing_record, owner "
			."FROM library.for_book ";
		} elseif ($lib_cat_list[$cat_id_lib] == "patent") {
			$sql = "country, patent_no, assignee, file_date, "
			."pub_date, patent_date, intnl_clsfcn, citations, no_pages, elec_copy_add "
			."FROM library.for_patent ";
		}
		$sql = "SELECT $sql where lib_item_id='$lib_item_id';";
		$result = mysql_query($sql);
		include("err_msg.inc");
		include("lib_search_result_client.inc");
		$i++;
?>
</html>