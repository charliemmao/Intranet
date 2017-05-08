<html>
<?php
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
?>
</html>
