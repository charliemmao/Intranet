<html>
<?php
	$olpctr = 0;
/*
	$overlapcode[$olpctr][0] = "RLA-NT";	//group code
	$overlapcode[$olpctr][1] = "RLA-AUSIND";	//individual GHR code
	$olpctr++;
	$overlapcode[$olpctr][0] = "RLA-OHDS";	//group code
	$overlapcode[$olpctr][1] = "RLA-OHD-AL";	//individual GHR code
	$olpctr++;
	
	echo "<table border=1><tr><th>Count</th><th>Group Code</th><th>Individual Code</th></tr>";
	for ($i=0; $i<$olpctr; $i++) {
		$j=$i+1;
		$gc = $overlapcode[$i][0]; //group code
		$ic = $overlapcode[$i][1]; //individual GHR code
		echo "<tr><td>$j</td><td>$gc</td><td>$ic</td></tr>";
	}
	echo "</table><p>";
//*/

	$sql = "SELECT ghrcode, rlacode 
        FROM timesheet.ghrtorlacode 
        WHERE  isgroup='y' ORDER BY ghrcode;";
    $result = mysql_query($sql);
    include("err_msg.inc");
	$i=0;
    while (list($ghrcode, $rlacode) = mysql_fetch_array($result)) {
    	$sql2 = "SELECT code_prefix 
        FROM timesheet.code_prefix 
        WHERE codelable='$rlacode'";
    	$result2 = mysql_query($sql2);
    	include("err_msg.inc");
       list($code_prefix) = mysql_fetch_array($result2);
       $grpcode[$i][0] = $ghrcode;
       $grpcode[$i][1] = $code_prefix;
       $grpcode[$i][2] = $rlacode;
		$i++;
    }
	$grpctr = $i;
    	
	$sql = "SELECT ghrcode, rlacode 
        FROM timesheet.ghrtorlacode 
        WHERE  isgroup='n' ORDER BY ghrcode;";
    $result = mysql_query($sql);
    include("err_msg.inc");
	$i=0;
    while (list($ghrcode, $rlacode) = mysql_fetch_array($result)) {
		$idvcode[$i][0] = $ghrcode;
		$idvcode[$i][1] = $rlacode;
		$idvcodehash[$rlacode] = $i+1;
		$i++;
    }
	$idvctr = $i;

//search code list under group heading to find double listed project code
	$olpctr = 0;
	for ($i=0; $i<$grpctr; $i++) {
		$str = $grpcode[$i][1];
		$sql = "SELECT projcode_id, brief_code, description, special, 
            div15, begin_date, end_date, costcenter 
        FROM timesheet.projcodes 
        WHERE brief_code like '%$str%' ORDER BY brief_code;";
    	$result = mysql_query($sql);
    	include("err_msg.inc");
 		//echo "$i<br>$sql <br><table border=1><tr><th>Count</th><th>projcode_id</th><th>brief_code</th></tr>";
   		while (list($projcode_id, $brief_code, $description, $special, 
            $div15, $begin_date, $end_date, $costcenter) = mysql_fetch_array($result)) {
        	//echo "<td>$j</td><td>$projcode_id</td><td>$brief_code</td></tr>";
        	$j = $idvcodehash[$brief_code];
        	if ($j) {
        		$overlapcode[$olpctr][0] = $grpcode[$i][1];
        		$overlapcode[$olpctr][1] = $idvcode[$j-1][0];
        		$olpctr++;
        	}
    	}
       //echo "</table><p>";
	}


/*
	echo "<h3>RLA Group Code</h3>$sql<br><br>";
	echo "<table border=1><tr><th>Count</th><th>GHR Code</th><th>Code Prefix</th><th>Code Lable</th></tr>";
	for ($i=0; $i<$grpctr; $i++) {
		$j = $i + 1;
		echo "<tr><td>$j</td><td>".$grpcode[$i][0].
			"</td><td>".$grpcode[$i][1]."</td><td>".$grpcode[$i][2]."</td></tr>";
	}
	echo "</table><p>";

	echo "<h3>RLA Individual Code</h3>$sql<br><br>";
	echo "<table border=1><tr><th>Count</th><th>GHR Code</th><th>RLA Code</th></tr>";
	for ($i=0; $i<$idvctr; $i++) {
		$j = $i + 1;
		echo "<tr><td>$j</td><td>".$idvcode[$i][0]."</td><td>".$idvcode[$i][1]."</td></tr>";
	}
	echo "</table><p>";

//*/
	echo "Group code $grpctr. Individual code $idvctr.<br>";

	echo "<table border=1><tr><th>Count</th><th>Group Code</th><th>Individual Code</th></tr>";
	for ($i=0; $i<$olpctr; $i++) {
		$j=$i+1;
		$gc = $overlapcode[$i][0]; //group code
		$ic = $overlapcode[$i][1]; //individual GHR code
		echo "<tr><td>$j</td><td>$gc</td><td>$ic</td></tr>";
	}
	echo "</table>";

?>
</html>