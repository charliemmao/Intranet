<html>
<body background="rlaemb.JPG" leftmargin="20">
<?php
include("connet_root_once.inc");
include("field_verification.inc");
include("regexp.inc");
include("rla_functions.inc");

//due to db design error
echo "<h1 align=center>Project Code Modification</h1><hr>";
	
if ($updatecode) {
	$print = "n";
	$projcode_id = $projcodeidtomod;
	//echo "$projcode_id $projbcodetomod  $brief_code, $description, $special, $div15, $begin_date, $end_date, $costcenter";
	$brief_code = ereg_replace(" ", "_", $brief_code);
	$brief_code = removenewline($brief_code);
	$description = removenewline($description);
   //update timesheet.projcodes
   $sql = "UPDATE timesheet.projcodes 
        SET brief_code='$brief_code', 
            description='$description', special='$special', 
            div15='$div15', begin_date='$begin_date', end_date='$end_date', costcenter='$costcenter' 
        WHERE projcode_id='$projcode_id'"; 
	if ($print == "y") {
   		echo "$sql<br><br>";
   	}
   	
	$result = mysql_query($sql);
	include("err_msg.inc");
   //update timesheet.timedata 
   $sql = "SELECT COUNT(*) as ctr 
        FROM timesheet.timedata 
        WHERE brief_code='$projbcodetomod';";
	$result = mysql_query($sql);
	include("err_msg.inc");
   	list($ctr) = mysql_fetch_array($result);
   	if ($ctr) {
		if ($print == "y") {
   			echo "$sql<br><br>";
   			echo "$ctr<br><br>";
   		}
   		$sql = "UPDATE timesheet.timedata 
        	SET brief_code='$brief_code'
        	WHERE brief_code='$projbcodetomod'";
		$result = mysql_query($sql);
		include("err_msg.inc");
		if ($print == "y") {
   			echo "$sql<br><br>";
   		}
   	}

   //update timesheet.marketing
   $sql = "SELECT COUNT(*) as ctr 
        FROM timesheet.marketing
        WHERE brief_code='$projbcodetomod';";
	$result = mysql_query($sql);
	include("err_msg.inc");
   	list($ctr) = mysql_fetch_array($result);
   	if ($ctr) {
		if ($print == "y") {
   			echo "$sql<br><br>";
   			echo "$ctr<br><br>";
   		}
   		$sql = "UPDATE timesheet.marketing
        	SET brief_code='$brief_code'
        	WHERE brief_code='$projbcodetomod'";
		$result = mysql_query($sql);
		include("err_msg.inc");
		if ($print == "y") {
   			echo "$sql<br><br>";
   		}
   	}
   
   //update timesheet.researchrcd
   $sql = "SELECT COUNT(*) as ctr 
        FROM timesheet.researchrcd
        WHERE brief_code='$projbcodetomod';";
	$result = mysql_query($sql);
	include("err_msg.inc");
   	list($ctr) = mysql_fetch_array($result);
   	if ($ctr) {
		if ($print == "y") {
   			echo "$sql<br><br>";
   			echo "$ctr<br><br>";
   		}
   		$sql = "UPDATE timesheet.researchrcd
        	SET brief_code='$brief_code'
        	WHERE brief_code='$projbcodetomod'";
		$result = mysql_query($sql);
		include("err_msg.inc");
		if ($print == "y") {
   			echo "$sql<br><br>";
   		}
   	}
   
   //update timesheet.leavercd
   $sql = "SELECT COUNT(*) as ctr 
        FROM timesheet.leavercd
        WHERE brief_code='$projbcodetomod';";
	$result = mysql_query($sql);
	include("err_msg.inc");
   	list($ctr) = mysql_fetch_array($result);
   	if ($ctr) {
		if ($print == "y") {
   			echo "$sql<br><br>";
   			echo "$ctr<br><br>";
   		}
   		$sql = "UPDATE timesheet.leavercd
        	SET brief_code='$brief_code'
        	WHERE brief_code='$projbcodetomod'";
		$result = mysql_query($sql);
		include("err_msg.inc");
		if ($print == "y") {
   			echo "$sql<br><br>";
   		}
   	}   
   	
   //update timesheet.ghrtorlacode
   $sql = "SELECT COUNT(*) as ctr 
        FROM timesheet.ghrtorlacode
        WHERE rlacode='$projbcodetomod';";
	$result = mysql_query($sql);
	include("err_msg.inc");
   	list($ctr) = mysql_fetch_array($result);
   	if ($ctr) {
		if ($print == "y") {
   			echo "$sql<br><br>";
   			echo "$ctr<br><br>";
   		}
   		$sql = "UPDATE timesheet.ghrtorlacode
        	SET rlacode='$brief_code'
        	WHERE rlacode='$projbcodetomod'";
		$result = mysql_query($sql);
		include("err_msg.inc");
		if ($print == "y") {
   			echo "$sql<br><br>";
   		}
   	} 
   	$projcodeid = $projcode_id;

   //update timesheet.projleader. columns: id, leader, codes, grpcode,  */
   $sql = "SELECT id, leader, codes, grpcode 
        FROM timesheet.projleader";
   $result = mysql_query($sql);
	include("err_msg.inc");
	while (list($id, $leader, $codes, $grpcode) = mysql_fetch_array($result)) {
		$codes_new = ereg_replace("$projbcodetomod","$brief_code",$codes);
		if ($codes_new != $codes) {
			$sqlldr = "UPDATE timesheet.projleader SET codes='$codes_new' WHERE id='$id';";
			$resultldr = mysql_query($sqlldr);
			/*
   			echo "<br><br>From $projbcodetomod to $brief_code<br>";
			echo "id=$id<br>leader=$leader<br>codes=$codes<br>grpcode=$grpcode<br>";
			echo "<b>$sqlldr</b><br>";
			//*/
		}
    }

   	echo "<h2>Update successful.</h2><hr>";
}

//form for update
	if ($createmodifyform) {
		echo "<form name=\"myform\" method=post>";
		echo "<table border=0>";
		$sql = "SELECT codehead_id, code_prefix, codelable 
        	FROM timesheet.code_prefix 
        	ORDER BY code_prefix;";
		$result = mysql_query($sql);
		$projgrp = "";
		echo "<table border=0><tr><th align=left>Project Group</th><td><select name=projgrplist>";
		while (list($codehead_id, $code_prefix, $codelable) = mysql_fetch_array($result)) {
			echo "<option value=$codehead_id selected>$code_prefix";
			$projgrp = $projgrp.$code_prefix."~";
		}
		echo "</td></tr>";
		echo "<input type=hidden name=\"projgrp\" value=\"$projgrp\">";
		echo "<tr><td>&nbsp;</td></tr>";
		
		echo "<tr><th>Item</th><th>Value</th></tr>";
    	$sql = "SELECT brief_code, description, special, 
            div15, begin_date, end_date, costcenter 
        FROM timesheet.projcodes 
        WHERE projcode_id='$projcodeid';";
    	$result = mysql_query($sql);
    	include("err_msg.inc");
    	list($brief_code, $description, $special, 
            $div15, $begin_date, $end_date, $costcenter) = mysql_fetch_array($result);
		echo "<input type=hidden name=projcodeidtomod value=$projcodeid>";
		echo "<input type=hidden name=projbcodetomod value=\"$brief_code\">";
       echo "<tr><th align=left>Project Code</th><td>";
       echo "<input onblur=\"projectcode();\" type=text name=\"brief_code\" value=\"$brief_code\" size=40>";
       echo "</td></tr>";
       
       echo "<tr><th align=left>Description</th><td>";
       echo "<textarea name=\"description\" cols=40 rows=4>$description</textarea>";
       echo "</td></tr>";
       
	 include("specialentrydef.inc");
       echo "<tr><th align=left>Special</th><td>";
       echo "<select name=\"special\">";
       for ($i=0; $i<count($splist); $i++) {
       	if ($splist[$i] == $special) {
        		echo "<option selected>".$splist[$i];
        	} else {
        		echo "<option>".$splist[$i];
        	}
       }
       echo "</option></select>";
       echo "</td></tr>";
       
       $d15[] = "Y";
       $d15[] = "N";
       echo "<tr><th align=left>15 Divisible</th><td>";
       echo "<select name=\"div15\">";
       for ($i=0; $i<count($d15); $i++) {
       	if ($d15[$i] == $div15) {
        		echo "<option selected>".$d15[$i];
        	} else {
        		echo "<option>".$d15[$i];
        	}
       }
       echo "</option></select>";
       echo "</td></tr>";
       
       echo "<tr><th align=left>Starting Date</th><td>";
    	echo "<input onblur=\"projcodeveri('date');\" ".
    		"size=\"10\" value=\"$begin_date\" name=\"begin_date\">";
       echo "</td></tr>";
       
       echo "<tr><th align=left>Ending Date</th><td>";
    	echo "<input onblur=\"field_verification('date');\" ".
    		"size=\"10\" value=\"$end_date\" name=\"end_date\">";
       echo "</td></tr>";
       
       echo "<tr><th align=left>To Cost Center</th><td>";
       $ccyn[] = "y";
       $ccyn[] = "n";
      	echo "<select name=\"costcenter\">";
       for ($i=0; $i<count($ccyn); $i++) {
       	if ($ccyn[$i] == $costcenter) {
        		echo "<option selected>".$ccyn[$i];
        	} else {
        		echo "<option>".$ccyn[$i];
        	}
       }
       echo "</option></select>";
       echo "</td></tr>";
		echo "<input type=hidden name=problem value=\"n\">";
		echo "<tr><td>&nbsp;</td></tr>";
		echo "<tr><th colspan=2><button onclick=\"return chkprojfield();\" type=submit name=updatecode>
		<b>Update Now</b></button></th></tr>";
		echo "</form>";
		echo "</table><hr>";
	}
	
// form to list project codes
	$sql = "SELECT projcode_id, brief_code, description FROM timesheet.projcodes 
		order by brief_code;";
	$result = mysql_query($sql);
	if ($result) {
		echo "<form method=post>";
		echo "<table border=0><tr><th>Code List</th><td><select name=projcodeid>";
		while (list($projcode_id, $brief_code, $description) = mysql_fetch_array($result)) {
			$brief_code = ereg_replace("__", "&", $brief_code);
			$brief_code = ereg_replace("_", " ", $brief_code);
			if ($projcode_id == $projcodeid) {
				echo "<option value=$projcode_id selected>$brief_code";
			} else {
				echo "<option value=$projcode_id>$brief_code";
			}
		}
		echo "</option></select>&nbsp;&nbsp;&nbsp;&nbsp;";
		//echo "</td></tr>";
		//echo "<tr><th colspan=2>";
		echo "<button type=submit name=createmodifyform><b>Modify This Code</b></button></td></tr>";
		echo "</form>";
		echo "</table>";
	} else {
		echo "<h2 align=center>No project code has been found.</h2>";
	}
	echo "<br><br>";
	mysql_close();
?>