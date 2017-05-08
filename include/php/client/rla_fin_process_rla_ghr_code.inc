<?php
/* table: ghrtorlacode. columns: ghrcode, rlacode, costcenter, dateenter, enddate, active, isgroup */
//*
	$sql = "SELECT ghrcode, rlacode, costcenter, dateenter, 
            enddate, active, isgroup 
        FROM timesheet.ghrtorlacode"; 
    $result = mysql_query($sql);
    include("err_msg.inc");
    echo "<table border=1><tr><th>No</th><th>GHRCODE</th><th>RLACODE</th><th>COSTCENTER</th><th>IsGroup</th></tr>";
    $ctr = 0;
    while (list($ghrcode, $rlacode, $costcenter, $dateenter, 
            $enddate, $active, $isgroup) = mysql_fetch_array($result)) {
       $ctr++;
		//check whether $rlacode a group code or individual code
		//$rlacode = ereg_replace(" ", "_", $rlacode);
		
		/*
		$sql0 = "UPDATE timesheet.ghrtorlacode 
        	SET rlacode='$rlacode', isgroup='n'
            WHERE ghrcode='$ghrcode'"; 
    	$result0 = mysql_query($sql0);
    	include("err_msg.inc");
       //*/
       
		$sql0 = "SELECT codehead_id 
        	FROM timesheet.code_prefix 
        	WHERE codelable='$rlacode'";
    	$result0 = mysql_query($sql0);
    	include("err_msg.inc");
    	list($codehead_id) = mysql_fetch_array($result0);
       if ($codehead_id) {
       	$tmp = "<font color=#0000ff>y - $codehead_id</font>";
			$sql0 = "UPDATE timesheet.ghrtorlacode 
        		SET rlacode='$rlacode', isgroup='y'
           	 WHERE ghrcode='$ghrcode'"; 
    		$result0 = mysql_query($sql0);
    		include("err_msg.inc");
       } else {
			$sql0 = "SELECT projcode_id 
        		FROM timesheet.projcodes 
        		WHERE brief_code='$rlacode' ";
    		$result0 = mysql_query($sql0);
    		include("err_msg.inc");
    		list($projcode_id) = mysql_fetch_array($result0);
    		if ($projcode_id) {
       		$tmp = "n - $projcode_id";
    		} else {
       		$tmp = "<font color=#ff0000>n - NOT FOUND</font>";
       		/*
				$rlacode = ereg_replace("_", " ", $rlacode);
				$sql0 = "UPDATE timesheet.ghrtorlacode 
        			SET rlacode='$rlacode', isgroup='n'
            		WHERE ghrcode='$ghrcode'"; 
    			$result0 = mysql_query($sql0);
    			include("err_msg.inc");
    			//*/
    		}
    		$sql0 = "UPDATE timesheet.ghrtorlacode 
        		SET isgroup='n' 
        		WHERE ghrcode='$ghrcode';";
    		$result0 = mysql_query($sql0);
    		include("err_msg.inc");
      }
       //*/
       echo "<tr><td>$ctr</td><td>$ghrcode</td><td>$rlacode</td><td>$costcenter</td><td>$tmp</td></tr>";
    
    //* 
    	//make entry to rlafinance.codeid from record from timesheet.ghrtorlacode
       $sql0 = "SELECT code_id 
        	FROM rlafinance.codeid 
        	WHERE description='$ghrcode';";
    	$result0 = mysql_query($sql0);
    	include("err_msg.inc");
    	list($code_id) = mysql_fetch_array($result0);
    	if ($code_id) {
    		echo "<font color=#0000ff>Exist</font><br>";
    	} else {
    		$sql0 = "INSERT INTO rlafinance.codeid
        		VALUES('null', '$ghrcode', '$costcenter', 'cc', 
            'y', '2002-12-06', '2002-12-06');";
     		$result0 = mysql_query($sql0);
    		include("err_msg.inc");
    		echo "<font color=#ff0000>$sql0</font><br>";
   		}
   	//*/
   	
    }
    echo "</table><p><p>";
    exit;
//*/
?>