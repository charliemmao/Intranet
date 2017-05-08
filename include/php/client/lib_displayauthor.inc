<html>
<?php
	if ($priv == "00") {
		//echo "Author_ID: $author_id<br>";
	}
	if ($author_id != "") {
		if ($modifylibrcd != "yes") {
			echo strtoblue("Author: ");
		}
		$id_tmp = explode($patsym, $author_id);
		$no_author_lib = count($id_tmp);
		for ($ai=0; $ai<$no_author_lib; $ai++) {
			$tmpid = $id_tmp[$ai];
			$auth_sql = "SELECT firstname_id, middlename_id, lastname_id "
				."FROM library.author WHERE auth_id='$tmpid';";
			$auth_result = mysql_query($auth_sql);
			if ($priv == "00") {
				//echo "Author Query: $auth_sql<br>";
			}
			if (mysql_num_rows($auth_result)) {
				list($firstname_id, $middlename_id, $lastname_id) = mysql_fetch_array($auth_result);
				if ($modifylibrcd != "yes") {
					if ($ai < $no_author_lib-1) {
						echo $firstname_list[$firstname_id]." ".$middlename_list[$middlename_id]." ".$lastname_list[$lastname_id]."; ";
					} else {
						echo $firstname_list[$firstname_id]." ".$middlename_list[$middlename_id]." ".$lastname_list[$lastname_id].". ";
					}
				} else {
					$auth_name_list[$ai][0] = $firstname_list[$firstname_id];
					$auth_name_list[$ai][1] = $middlename_list[$middlename_id];
					$auth_name_list[$ai][2] = $lastname_list[$lastname_id];
					if ($priv == "00") {
						//echo "Author_First_Name: $firstname_id ".$auth_name_list[$ai][0]."<br>";
						//echo "Author_Mid_Name: $middlename_id ".$auth_name_list[$ai][1]."<br>";
						//echo "Author_Last_Name: $lastname_id ".$auth_name_list[$ai][2]."<br>";
					}
				}
			}
		}
	}
?>
</html>