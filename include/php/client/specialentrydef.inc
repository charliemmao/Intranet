<?php
	include("connet_root_once.inc");
	$sql = "SELECT description 
        FROM logging.sysmastertable 
        WHERE item='tsspeentry';";
    $result = mysql_query($sql);
    include("err_msg.inc");
    list($description) = mysql_fetch_array($result);
	$entrycat = explode("@",$description);
	$splist[0] = "";
	for ($si = 0; $si < count($entrycat); $si++) {
		 $entrycat[$si] = trim( $entrycat[$si]);
		if ( $entrycat[$si] ) {
			$splist[$si+1] = $entrycat[$si];
		}
	}
/*
	for ($si = 0; $si < count($splist); $si++) {
		echo "$si: ".	$splist[$si] ."<br>";
	}
	exit;
//*/
?>
