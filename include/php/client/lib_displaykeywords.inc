<html>
<?php
	if ($keyword_id != "") {
		$keywords = "";
		if ($modifylibrcd != "yes") {echo strtoblue("Keywords: "); }
		$id_tmp = explode($patsym, $keyword_id);
		$noid = count($id_tmp);
		for ($ai=0; $ai<$noid-1; $ai++) {
			$tmpid = $id_tmp[$ai];
			if ($modifylibrcd != "yes") {
				echo $keyword_list[$tmpid].", "; 
			} else {
				$keywords = $keywords." ".$keyword_list[$tmpid];
			}
		}
		$tmpid = $id_tmp[$noid-1];
		if ($modifylibrcd != "yes") {
			echo $keyword_list[$tmpid].". ";
		} else {
			$keywords = $keywords." ".$keyword_list[$tmpid];
		}
	}
	$keywords = trim($keywords);
?>
</html>