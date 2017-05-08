<html>

<head>
<title>Convert  timesheet Database to Demo DB</title>
</head>

<body bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG">
<?php
include("admin_access.inc");
include('rla_functions.inc');
echo "<h1	align=center>Convert  timesheet Database to Demo DB</h1><br>";
echo '<p align=center><a href="'.$PHP_SELF.$admininfo.'">[Refresh]</a>
	<a href="/'.$phpdir.'/adminctl_top.php'.$admininfo.'">[Admin Main Page]</a><hr>';
	if ( getenv("server_name") != "roger.rla.com.au") {
		echo "This program can only run on ROGER.";
		exit;
	}
	include("connet_root_once.inc");	
	echo "<br><b>Starting: ".date("Y-m-d h:i:s")."</b><br><br>";	// ".getenv("server_name")."
	
	echo "see administrator: admin_timesheet_converter.php<br>";
	/*
	convertcode();
	deletesomercd();
	convertcountry();
	convertcompany();
	convertemailname();
	//*/
	exit;
	echo "<br><b>Ending: ".date("Y-m-d h:i:s")."</b><br><br>";

//	TSreceipt	code_prefix	company	employee	entry_no	ghrtorlacode
//	leave_entitle	leavercd	marketing	privatecode	projcodes	projleader
//	researchrcd	timedata	tsmailconf

//#####################################################
//	convert code ralated records
//#####################################################
function convertcode() {
    echo "<hr><b><font color=#0000ff>Convert Table	timesheet.code_prefix</font></b><br><br>";
    //$sql = "DELETE FROM logging.code_prefix";
    //$result = mysql_query($sql);
    //include("err_msg.inc");
	
	//RLA-OHD-Annual_Leave
	$oldcode="RLA-OHD-Annual Leave";
	$newcode="RLA-OHD-Annual-Leave";

	$sql = "UPDATE timesheet.ghrtorlacode SET rlacode='$newcode' WHERE rlacode='$oldcode'";
	//echo "$sql<br><br>";
    $result = mysql_query($sql);
    include("err_msg.inc");
	$sql = "UPDATE tshtbackup.ghrtorlacode SET rlacode='$newcode' WHERE rlacode='$oldcode'";
	//echo "$sql<br><br>";
    $result = mysql_query($sql);
    include("err_msg.inc");

	$oldcode="RLA-OHD-Annual_Leave";
	$sql = "UPDATE timesheet.projcodes SET brief_code='$newcode' WHERE brief_code='$oldcode'";
	//echo "$sql<br><br>";
    $result = mysql_query($sql);
    include("err_msg.inc");
	$sql = "UPDATE tshtbackup.projcodes SET brief_code='$newcode' WHERE brief_code='$oldcode'";
	//echo "$sql<br><br>";
    $result = mysql_query($sql);
    include("err_msg.inc");
	
	$sql = "UPDATE timesheet.leavercd SET brief_code='$newcode' WHERE brief_code='$oldcode'";
	//echo "$sql<br><br>";
    $result = mysql_query($sql);
    include("err_msg.inc");
	$sql = "UPDATE tshtbackup.leavercd SET brief_code='$newcode' WHERE brief_code='$oldcode'";
	//echo "$sql<br><br>";
    $result = mysql_query($sql);
    include("err_msg.inc");

	$sql = "UPDATE timesheet.timedata SET brief_code='$newcode' WHERE brief_code='$oldcode'";
	//echo "$sql<br><br>";
    $result = mysql_query($sql);
    include("err_msg.inc");
	$sql = "UPDATE tshtbackup.timedata SET brief_code='$newcode' WHERE brief_code='$oldcode'";
	//echo "$sql<br><br>";
    $result = mysql_query($sql);
    include("err_msg.inc");

	 $sql = "SELECT code_prefix, codelable FROM tshtbackup.code_prefix";
    $result = mysql_query($sql);
 	//echo "$sql<br><br>";
   include("err_msg.inc");
    $no = mysql_num_rows($result);
    $i=0;
    while (list($code_prefix, $codelable) = mysql_fetch_array($result)) {
		$oldprefix=$code_prefix;
		$prefix[$i][0]=$oldprefix;
		$prefix[$i][1]=$codelable;
		
		if ($i<10) {
			$k = "0".$i;
		} else {
			$k = $i;
		}
		//$k = "-".$k;
		$code_prefix="pgp".$k;
		$codelable="pgp-def".$k;
		$prefix[$i][2]=$code_prefix;
		$prefix[$i][3]=$codelable;
		$i++;
		
 		$sql = "UPDATE timesheet.code_prefix SET 
 			code_prefix='$code_prefix', codelable='$codelable' where code_prefix='$oldprefix';";
    	$resultnew = mysql_query($sql);
    	
    	$sql = "UPDATE timesheet.projcodes SET end_date='0000-00-00' where end_date!='0000-00-00';";
    	$r  = mysql_query($sql);
    	include("err_msg.inc");

		//$sql = "INSERT INTO logging.code_prefix VALUES('$oldprefix', '$code_prefix', '$codelable');";
    	//$resultnew = mysql_query($sql); 		
       //echo "$oldprefix, $code_prefix, $codelable<p>";
	}
	$noprefix=$i;
	
	//#####################################################
    echo "<b><font color=#0000ff>Convert Table timesheet.projcodes</font></b><br><br>";
    $sql = "DELETE FROM logging.projcodes";
    //$result = mysql_query($sql);
    //include("err_msg.inc");
	
	$sqlproj = "SELECT projcode_id, brief_code, description, special, div15, begin_date, end_date 
	FROM tshtbackup.projcodes ORDER BY projcode_id;";
    $resultproj = mysql_query($sqlproj);
    $i=0;
    while (list($projcode_id, $brief_code, $description, $special, $div15, 
    		$begin_date, $end_date) = mysql_fetch_array($resultproj)) {
        //echo "$i, $brief_code, $description<br>";
        $brief_code_old = $brief_code;
        
        for ($j=0; $j<$noprefix; $j++) {
        	$tmp = $prefix[$j][0];
        	$newcode = ereg_replace($tmp, "ff", $brief_code); 
        	if ($newcode != $brief_code) {
        		//echo $prefix[$j][0].", ".$prefix[$j][1].", ".$prefix[$j][2]."<br>";
 				if ($i<10) {
					$k = "0".$i;
				} else {
					$k = $i;
				}
				//$k = "-".$k;
       		$brief_code=$prefix[$j][2]."-proj$k";
        		$description="$brief_code desc";
        		//echo "$brief_code_old, $brief_code, $description<br>";
        		break;
        	}
        }
        $projcode[$i][0]=$brief_code_old;
        $projcode[$i][1]=$brief_code;
        $projcode[$i][2]=$description;
        $i++;
        
        $sql = "UPDATE timesheet.projcodes SET 
			brief_code='$brief_code', description='$description' WHERE brief_code='$brief_code_old';";
        $rupdate=mysql_query($sql);
        
        /*
        $sql = "INSERT INTO logging.projcodes SET projcode_id='$projcode_id', 
			brief_code_old='$brief_code_old', brief_code='$brief_code', 
			description='$description';";
        $rinsert=mysql_query($sql);
        //echo "$brief_code_old, $brief_code, $description<p>";
        //*/

        if ($brief_code_old == "RLA-OHD-Annual-Leave" || $brief_code_old == "RLA-OHD-Sick_Leave" 
        || $brief_code_old == "RLA-OHD-LSL") {
        	$sqlleave = "UPDATE timesheet.leavercd SET 
				brief_code='$brief_code' WHERE brief_code='$brief_code_old'";
			//echo "$sqlleave<br>";
			$r = mysql_query($sqlleave);
        }
        
        $sqlorher = "UPDATE timesheet.marketing SET 
			brief_code='$brief_code' WHERE brief_code='$brief_code_old'";
		 $r = mysql_query($sqlorher);
		 
		 $sqlorher = "UPDATE timesheet.researchrcd SET 
			brief_code='$brief_code', activity='unknown' WHERE brief_code='$brief_code_old'";
		 $r = mysql_query($sqlorher);

		 $sqlorher = "UPDATE timesheet.timedata SET 
			brief_code='$brief_code' WHERE brief_code='$brief_code_old'";
		 $r = mysql_query($sqlorher);
    }   
    $noprojcode=$i;
    
	//#####################################################
	/*
		$prefix[$i][0]=$code_prefix;
		$prefix[$i][1]=$codelable;
		$prefix[$i][2]=$code_prefix;
		$prefix[$i][3]=$codelable;
		$noprefix=
		
        $projcode[$i][0]=$brief_code_old;
        $projcode[$i][1]=$brief_code;
        $projcode[$i][2]=$description;
    	$noprojcode=$i;
		
	//*/

    echo "<b><font color=#0000ff>Convert Table timesheet.ghrtorlacode</font></b><br><br>";
	$sql = "SELECT ghrcode, rlacode FROM tshtbackup.ghrtorlacode";
    $result = mysql_query($sql);
    include("err_msg.inc");
    $no = mysql_num_rows($result);
    $i=0;
    while (list($ghrcode, $rlacode) = mysql_fetch_array($result)) {
       //echo "<br>$i: $ghrcode, $rlacode<br>";
 		if ($i<10) {
			$k = "0".$i;
		} else {
			$k = $i;
		}
		$k = "ghr-".$k;
       $i++;
        $update = "";
        for ($j=0; $j<$noprefix; $j++) {
        	if ($ghrcode== $prefix[$j][0]) {
        		//echo "<font color=#ff0000>find form code_prefix.</font><br>";
				$update= "UPDATE timesheet.ghrtorlacode SET ghrcode='".$k."', rlacode='".$prefix[$j][2]."' 
					WHERE ghrcode='".$prefix[$j][0]."'";	
        		break;
        	}
        	if ($ghrcode== $prefix[$j][1]) {
        		//echo "<font color=#00ff00>find form prefix lable.</font><br>";
				$update= "UPDATE timesheet.ghrtorlacode SET ghrcode='".$k."', rlacode='".$prefix[$j][2]."' 
					WHERE ghrcode='".$prefix[$j][1]."'";	
        		break;
        	}
        	
        	if ($rlacode== $prefix[$j][0]) {
        		//echo "<font color=#ff0000>find form code_prefix.</font><br>";
				$update= "UPDATE timesheet.ghrtorlacode SET ghrcode='".$k."', rlacode='".$prefix[$j][2]."' 
					WHERE rlacode='".$prefix[$j][0]."'";	
        		break;
        	}
        	if ($rlacode== $prefix[$j][1]) {
        		//echo "<font color=#00ff00>find form prefix lable.</font><br>";
				$update= "UPDATE timesheet.ghrtorlacode SET ghrcode='".$k."', rlacode='".$prefix[$j][2]."' 
					WHERE rlacode='".$prefix[$j][1]."'";	
        		break;
        	}
        }
        
        for ($j=0; $j<$noprojcode; $j++) {
        	if ($rlacode == $projcode[$j][0]) {
        		//echo "<font color=#0000ff>find form project code.</font><br>";
				$update= "UPDATE timesheet.ghrtorlacode SET ghrcode='".$k."', rlacode='".$projcode[$j][1]."' 
					WHERE rlacode='".$projcode[$j][0]."'";	
        		break;
        	}
		}
		if ($update != "") {
			//echo "$update<br>";
			$r = mysql_query($update);
    		include("err_msg.inc");
		}
    }
}
    
//#####################################################
//	convert username ralated records
//#####################################################
function convertemailname() {
    echo "<hr><b><font color=#0000ff>Convert Table	timesheet.employee</font></b><br>";
    //$sql = "DELETE FROM logging.employee";
    //$result = mysql_query($sql);
    //include("err_msg.inc");
	
    $sql = "SELECT title, email_name, logon_name, first_name, middle_name, last_name 
    	FROM tshtbackup.employee;";
    $result = mysql_query($sql);
    include("err_msg.inc");
    $no = mysql_num_rows($result);
    $i=1;
    $totalch=0;
    while (list($title, $email_name, $logon_name, $first_name, $middle_name, $last_name) = mysql_fetch_array($result)) {
		//echo "<br>$email_name, $logon_name, $first_name, $middle_name, $last_name<br>";
		$oldname =$email_name;
		if ($i<10) {
			$k = "0".$i;
		} else {
			$k = $i;
		}
		$email_name="user".$k;
		$logon_name="logon".$k;
		$first_name="F".$k;
		$middle_name="M".$k;
		$last_name="L".$k;
		$i++;
		
		//##########################################################
		//	mysql
		//##########################################################
		$sql = "UPDATE mysql.db SET User='$email_name' where User='$oldname'";
		//echo "$sql<br>";
    	$r = mysql_query($sql);
    	include("err_msg.inc");
		
		$sql = "UPDATE mysql.user SET User='$email_name' where User='$oldname'";
		//echo "$sql<br>";
    	$r = mysql_query($sql);
    	include("err_msg.inc");

		$sql = "update mysql.user set password=password(\"".$email_name."reslab\") where user='$email_name'";
    	$r = mysql_query($sql);
    	include("err_msg.inc");

		//##########################################################
		//	inventory
		//##########################################################
		$sql = "UPDATE inventory.tracking SET email_name='$email_name' where email_name='$oldname'";
		//echo "$sql<br>";
    	$r = mysql_query($sql);
    	include("err_msg.inc");

		//##########################################################
		//	logging
		//##########################################################
		$sql = "UPDATE logging.accesslevel SET email_name='$email_name' where 
		email_name='$oldname'";
		//echo "$sql<br>";
    	$r = mysql_query($sql);
    	include("err_msg.inc");

		$sql = "UPDATE logging.sysmastertable SET description='$email_name' where 
			description='$oldname'";
		//echo "$sql<br>";
    	$r = mysql_query($sql);
    	include("err_msg.inc");

		/*
		$sql = "UPDATE logging.access_rcd SET email_name='$email_name' where 
		email_name='$oldname'";
		//echo "$sql<br>";
    	$r = mysql_query($sql);
    	include("err_msg.inc");

		$sql = "UPDATE logging.logout SET email_name='$email_name' where 
		email_name='$oldname'";
		//echo "$sql<br>";
    	$r = mysql_query($sql);
    	include("err_msg.inc");

		$sql = "UPDATE logging.logsec SET email_name='$email_name' where 
		email_name='$oldname'";
		//echo "$sql<br>";
    	$r = mysql_query($sql);
    	include("err_msg.inc");

		$sql = "UPDATE logging.sqlerrlog SET ename='$email_name' where 
		ename='$oldname'";
		//echo "$sql<br>";
    	$r = mysql_query($sql);
    	include("err_msg.inc");
    	//*/
    	
    	/*
		$sql = "INSERT INTO logging.employee VALUES('$email_name', '$logon_name', 
		'$first_name', '$middle_name', '$last_name', '$oldname');";
    	$resultnew = mysql_query($sql);
 		//echo "$oldname, $email_name, $logon_name, $first_name, $middle_name, $last_name<br>";
    	//*/
 		
		//##########################################################
		//	timesheet
		//##########################################################
 		$sql = "UPDATE timesheet.employee SET email_name='$email_name', 
			logon_name='$logon_name', title='$title', first_name='$first_name', 
			middle_name='$middle_name', last_name='$last_name' where email_name='$oldname';";
    	$resultnew = mysql_query($sql);
    	include("err_msg.inc");
    	//echo "$sql<br>";
    	//echo mysql_affected_rows()."<br>";
    	
    	$sql = "UPDATE timesheet.TSreceipt SET email_name='$email_name' where email_name='$oldname';";
    	$resultnew = mysql_query($sql);
    	include("err_msg.inc");
    	//echo "$sql<br>";
    	//echo mysql_affected_rows()."<br>";
    	
    	$sql = "UPDATE timesheet.entry_no SET email_name='$email_name', logon_name='$logon_name'
    	 where email_name='$oldname';";
    	$resultnew = mysql_query($sql);
    	include("err_msg.inc");
    	//echo "$sql<br>";
    	//echo mysql_affected_rows()."<br>";
    	
    	$sql = "UPDATE timesheet.leave_entitle SET email_name='$email_name', 
			first_name='$first_name', middle_name='$middle_name', last_name='$last_name' ".
    	"where email_name='$oldname';";
    	$resultnew = mysql_query($sql);
    	include("err_msg.inc");
    	//echo "$sql<br>";
    	//echo mysql_affected_rows()."<br>";
    	
    	$sql = "
    		UPDATE timesheet.leavercd SET email_name='$email_name'
    		"."where email_name='$oldname';";
    	$resultnew = mysql_query($sql);
    	include("err_msg.inc");
    	//echo "$sql<br>";
    	//echo mysql_affected_rows()."<br>";

    	$sql = "
			UPDATE timesheet.marketing SET email_name='$email_name'
    		"." where email_name='$oldname';";
    	$resultnew = mysql_query($sql);
    	include("err_msg.inc");
    	//echo "$sql<br>";
    	//echo mysql_affected_rows()."<br>";
    	
    	$sql = "
 			UPDATE timesheet.privatecode SET email_name='$email_name'
    		"." where email_name='$oldname';";
    	$resultnew = mysql_query($sql);
    	include("err_msg.inc");
    	//echo "$sql<br>";
    	//echo mysql_affected_rows()."<br>";
    	
    	$sql = "
			UPDATE timesheet.projleader SET leader='$email_name'
    		"." where leader='$oldname';";
    	$resultnew = mysql_query($sql);
    	include("err_msg.inc");
    	//echo "$sql<br>";
    	//echo mysql_affected_rows()."<br>";
    	
    	$sql = "
			UPDATE timesheet.researchrcd SET email_name='$email_name'
    		"." where email_name='$oldname';";
    	$resultnew = mysql_query($sql);
    	include("err_msg.inc");
    	//echo "$sql<br>";
    	//echo mysql_affected_rows()."<br>";

    	$sql = "
			UPDATE timesheet.tsmailconf SET email_name='$email_name'
    		"." where email_name='$oldname';";
    	$resultnew = mysql_query($sql);
    	include("err_msg.inc");
    	//echo "$sql<br>";
    	//echo mysql_affected_rows()."<br>";

    	$totalch= $totalch + mysql_affected_rows();
    	echo "<p>";
    }
	//echo $totalch;
}

//#####################################################
//	delete some records
//#####################################################
function deletesomercd() {
	$tabledel = "timesheet.TSreceipt";
	$sql = "DELETE FROM $tabledel";
    $result = mysql_query($sql);
    echo "<hr><b><font color=#0000ff>Delete records from $tabledel.</font></b><br><br>";

	$tabledel = "timesheet.auto_notice";
	$sql = "DELETE FROM $tabledel";
    $result = mysql_query($sql);
    echo "<b><font color=#0000ff>Delete records from $tabledel.</font></b><br><br>";

	$tabledel = "timesheet.chargecode";
	$sql = "DELETE FROM $tabledel";
    $result = mysql_query($sql);
    echo "<b><font color=#0000ff>Delete records from $tabledel.</font></b><br><br>";
    
	$tabledel = "timesheet.myconid";
	$sql = "DELETE FROM $tabledel";
    $result = mysql_query($sql);
    echo "<b><font color=#0000ff>Delete records from $tabledel.</font></b><br><br>";
    
	$tabledel = "timesheet.privatecode";
	$sql = "DELETE FROM $tabledel";
    $result = mysql_query($sql);
    echo "<b><font color=#0000ff>Delete records from $tabledel.</font></b><br><br>";
    
	$tabledel = "timesheet.projleader";
	$sql = "DELETE FROM $tabledel";
    $result = mysql_query($sql);
    echo "<b><font color=#0000ff>Delete records from $tabledel.</font></b><br><br>";

	$tabledel = "timesheet.tsmailconf";
	$sql = "DELETE FROM $tabledel";
    $result = mysql_query($sql);
    echo "<b><font color=#0000ff>Delete records from $tabledel.</font></b><br><br>";
    
	$tabledel = "logging.OStable";
	$sql = "DELETE FROM logging.OStable";
    $result = mysql_query($sql);
    echo "<b><font color=#0000ff>Delete records from $tabledel.</font></b><br><br>";
	
	$tabledel = "logging.access_rcd";
	$sql = "DELETE FROM $tabledel";
    $result = mysql_query($sql);
    echo "<b><font color=#0000ff>Delete records from $tabledel.</font></b><br><br>";

	$tabledel = "logging.logout";
	$sql = "DELETE FROM $tabledel";
    $result = mysql_query($sql);
    echo "<b><font color=#0000ff>Delete records from $tabledel.</font></b><br><br>";

	$tabledel = "logging.logsec";
	$sql = "DELETE FROM $tabledel";
    $result = mysql_query($sql);
    echo "<b><font color=#0000ff>Delete records from $tabledel.</font></b><br><br>";
    
	$tabledel = "logging.sqlerrlog";
	$sql = "DELETE FROM $tabledel";
    $result = mysql_query($sql);
    echo "<b><font color=#0000ff>Delete records from $tabledel.</font></b><br><br>";
}

//#####################################################
//	convert country ralated records
//#####################################################
function convertcountry() {
    echo "<hr><b><font color=#0000ff>Convert Table	timesheet.country </font></b><br><br>";	# tshtbackup
	
 	$sql = "SELECT country FROM tshtbackup.country"; 
    $result = mysql_query($sql);
    include("err_msg.inc");
    $no = mysql_num_rows($result);
    $i=0;
    while (list($country) = mysql_fetch_array($result)) {		
		if ($i<10) {
			$k = "0".$i;
		} else {
			$k = $i;
		}
		$i++;
		//$k = "-".$k;
		$sqlupdate = "UPDATE timesheet.country SET country='country$k' WHERE country='$country'";
		$r = mysql_query($sqlupdate);
		include("err_msg.inc");
		
		$sqlupdate = "UPDATE timesheet.company SET country='country$k' WHERE country='$country'";
		//echo "$sqlupdate<br>";
		$r = mysql_query($sqlupdate);
		include("err_msg.inc");
		
		$sqlupdate = "UPDATE timesheet.marketing SET country='country$k' WHERE country='$country'";
		//echo "$sqlupdate<br>";
		$r = mysql_query($sqlupdate);
		include("err_msg.inc");
	}
}

//#####################################################
//	convert company ralated records
//#####################################################
function convertcompany() {
    echo "<hr><b><font color=#0000ff>Convert Table	timesheet.company</font></b><br><br>";	# tshtbackup
	 $sql = "SELECT company_name FROM tshtbackup.company"; 
    $result = mysql_query($sql);
    include("err_msg.inc");
    $no = mysql_num_rows($result);
    $i=0;
    while (list($company_name) = mysql_fetch_array($result)) {		
		if ($i<10) {
			$k = "0".$i;
		} else {
			$k = $i;
		}
		$i++;
		//$k = "-".$k;
		
		//echo "|$company_name|<br>";
		$sql = "UPDATE timesheet.company SET company_name='Co$k', description='NO' 
			WHERE company_name='$company_name';";
		//echo "$sql<br>";
		$r = mysql_query($sql);
		include("err_msg.inc");

		$sql = "UPDATE timesheet.marketing SET company_name='Co$k'
			WHERE company_name='$company_name';";
		//echo "$sql<br>";
		$r = mysql_query($sql);
		include("err_msg.inc");
	}
}

//#####################################################
//	unused codes
//#####################################################
function unusedcode() {
	$sql = "DELETE FROM logging.code_prefix;";	
    //$result = mysql_query($sql);
    //include("err_msg.inc");

	//$sql = "DELETE FROM logging.projcodes;";
    $result = mysql_query($sql);
    include("err_msg.inc");
}

?>

<form method="POST" target="main" action= "<?php echo $PHP_SELF ?>">
</form>

<hr>

</body>
</html>
