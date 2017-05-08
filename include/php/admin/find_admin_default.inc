<html>
<?php

$sql = "SELECT description 
    FROM logging.sysmastertable 
    WHERE item='admindefault'";   
$result = mysql_query($sql);
include("err_msg.inc");
if ($result) {
    list($description) = mysql_fetch_array($result);
}
if (!$usedb && !$usetable) {
	if ($description) {
	 	$t = explode("@", $description);
	 	$usedb = $t[0];
	 	$usetable= $t[1];
	 	$ascdec= $t[2];
	 	if (!$ascdec) {
	 		$ascdec = 0;
	 	}
	}
}

if ($usedb && $usetable) {
	if ($description) {
		$sql = "UPDATE logging.sysmastertable 
        	SET description='$usedb@$usetable@$ascdec' 
        	WHERE item='admindefault'";
    } else {
    	$sql = "INSERT INTO logging.sysmastertable 
        SET id='null', item='admindefault', 
            description='$usedb@$usetable@$ascdec';";
    }
    $result = mysql_query($sql);
    include("err_msg.inc");
	if ($ascdec == "0") {
	 	$ascdec = "";
	}

}
?>
</html>
