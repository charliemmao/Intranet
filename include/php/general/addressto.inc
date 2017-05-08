<html>
<?php
	if ($title == "" ) {
		echo '<h2>Hi, '.' '.$first_name.'</h2>';
	} else {
		echo '<h2>Hi, '.$title.' '. $last_name.'</h2>';
	}

/*
function addressto() {
	if ( $GLOBALS["title"] == "" ) {
		echo '<h2>Hi, '.' '.$GLOBALS["first_name"].'</h2>';
	} else {
		echo '<h2>Hi, '.$GLOBALS['title'].' '. $GLOBALS['last_name'].'</h2>';
	}
}
*/
?>
</html>
