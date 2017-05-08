<html>
<?php
	$sqlt1 = "SELECT t1.lib_item_id as lib_item_id, t1.cat_id as cat_id, t1.author_id as author_id, "
			."t1.libtitle as libtitle, t1.keyword_id as keyword_id, t1.abstract as abstract, ";
	if ($lib_cat_list[$cat_id_lib] == "book") {
		$sqlt2 = "t2.barcode as barcode, "
			."t2.dewey as dewey, t2.isbn as isbn, t2.editor as editor, t2.edition as edition, "
			."t2.volume as volume, "
			."t2.no_pages as no_pages, t2.year_published as year_published, t2.pub_id as pub_id, "
			."t2.copyright as copyright, t2.year_in as year_in, t2.year_cancellation as "
			."year_cancellation, t2.missing_record as missing_record, t2.owner as owner "
			."FROM library.lib_primlist as t1, library.for_book as t2 ";
	} elseif ($lib_cat_list[$cat_id_lib] == "patent") {
		$sqlt2 = "t2.country as country, "
			."t2.patent_no as patent_no, t2.assignee as assignee, t2.file_date as file_date, "
			."t2.pub_date as pub_date, t2.patent_date as patent_date, t2.intnl_clsfcn as "
			."intnl_clsfcn, t2.citations as citations, t2.no_pages as no_pages, t2.elec_copy_add " 
			."as elec_copy_add "
			."FROM library.lib_primlist as t1, library.for_patent as t2 ";
	} elseif ($lib_cat_list[$cat_id_lib] == "article") { 
		exit;
	} elseif ($lib_cat_list[$cat_id_lib] == "report") { 
		exit;
	} elseif ($lib_cat_list[$cat_id_lib] == "journal") { 
		exit;
	} elseif ($lib_cat_list[$cat_id_lib] == "other") { 
		exit;
	}?>
</html>