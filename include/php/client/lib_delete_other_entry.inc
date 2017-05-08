<html>
<?php
	## find maximum auth_id is used currently
	$sql = "SELECT MAX(auth_id) as auth_id FROM library.prim_auth;";
	$result = mysql_query($sql);
	include("err_msg.inc");
	echo $sql."<br>";
	list($auth_id) = mysql_fetch_array($result);
	echo "$auth_id<br>";
	## delete auth_id entry from table author
	$sql = "DELETE FROM library.author WHERE auth_id>'$auth_id';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	echo $sql."<br><br>";

###########################################################################################

	## find maximum firstname_id is used currently
	$sql = "SELECT MAX(firstname_id) as firstname_id FROM library.author;";
	$result = mysql_query($sql);
	include("err_msg.inc");
	echo $sql."<br>";
	list($firstname_id) = mysql_fetch_array($result);
	echo "$firstname_id<br>";
	## delete firstname_id entry from table auth_first
	$sql = "DELETE FROM library.auth_first WHERE firstname_id>'$firstname_id';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	echo $sql."<br><br>";

###########################################################################################
	## find maximum middlename_id is used currently
	$sql = "SELECT MAX(middlename_id) as middlename_id FROM library.author;";
	$result = mysql_query($sql);
	include("err_msg.inc");
	echo $sql."<br>";
	list($middlename_id) = mysql_fetch_array($result);
	echo "$middlename_id<br>";
	## delete middlename_id entry from table auth_middle
	$sql = "DELETE FROM library.auth_middle WHERE middlename_id>'$middlename_id';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	echo $sql."<br><br>";
	
###########################################################################################
	## find maximum lastname_id is used currently
	$sql = "SELECT MAX(lastname_id) as lastname_id FROM library.author;";
	$result = mysql_query($sql);
	include("err_msg.inc");
	echo $sql."<br>";
	list($lastname_id) = mysql_fetch_array($result);
	echo "$lastname_id<br>";
	## delete lastname_id entry from table auth_last
	$sql = "DELETE FROM library.auth_last WHERE lastname_id>'$lastname_id';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	echo $sql."<br><br>";
	
###########################################################################################
####################delete keywords
	## find maximum keyword_id is used currently
	$sql = "SELECT MAX(keyword_id) as keyword_id FROM library.prim_keyword;";
	$result = mysql_query($sql);
	include("err_msg.inc");
	echo $sql."<br>";
	list($keyword_id) = mysql_fetch_array($result);
	echo "$keyword_id<br>";
	## delete keyword_id entry from table keywords 
	$sql = "DELETE FROM library.keywords WHERE keyword_id>'$keyword_id';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	echo $sql."<br><br>";
?>
</html>