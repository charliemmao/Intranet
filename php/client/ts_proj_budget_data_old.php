<html>
<head>
<title>Project Budget Data</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<LINK REL="StyleSheet" HREF="../style/style.css" TYPE="text/css">
</head>
<body leftmargin="20" bgcolor="#FFFFCC" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG">
<script language=javascript>
var msg
function uploadverify() {
var ext,file,i
	msg="";
	file = document.uploadfileform.filename.value;
	if (file  == "") {
		msg = "No file has been selected.\n";
	} else {
		i = file.length;
		ext = file.substring(i-4) 
		if (ext != ".csv") {
			if (ext != ".CSV") {
				msg = msg + "Project data file has to be in excel csv format.\n";
			}
		}
 	}
	//window.alert("fileproblem: " + msg);
	twodates();
	if (msg){
		window.alert(msg);
		return false
	} else {
		return true;
	}	
}

function twodates() {
	var myregexp = /( )+/g;
	
	var tmp = document.uploadfileform.actualstart.value;
	tmp=tmp.replace(myregexp, "");
	if ( chkdate(tmp) == "-1") {
		msg = msg + "Please check date format for actual start date (yyyy-mm-dd).\n"; 
	}
	
	tmp = document.uploadfileform.actualend.value;	
	tmp=tmp.replace(myregexp, "");
	if ( chkdate(tmp) == "-1") {
		msg = msg + "Please check date format for actual end date (yyyy-mm-dd).\n"; 
	}
}
function datacheck(){
	msg= "";
	twodates();
	if (msg){
		window.alert(msg);
		return false
	} else {
		return true;
	}
}

</script>
<?php
include('str_decode_parse.inc');
include("connet_other_once.inc");
include("rla_functions.inc");
include("regexp.inc");
include("userinfo.inc"); //$userinfo
$userstr	=	"?".base64_encode($userinfo);
echo "<h2 align=center><a id=top>Project Budget Data </a>";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[Refresh]</font></a>";
echo "<a href=\"ts_proj_budget_report.php$userstr\"><font size=2>[Goto Report Page]</font></a>";
echo "</h2><hr>";

if ($modifyprojectfile) {
	echo "<h2>Modify Project Budget Data Entry</h2>";
	if ($modifyprojectfile) {
		$sql = "SELECT projcodeid, phaseno, actualstart, actualend 
        FROM timesheet.projbudgetfile 
        WHERE budgetfileid='$budgetfileid' ";

    	$result = mysql_query($sql);
    	include("err_msg.inc");
    	list($projcodeid, $phaseno, $actualstart, $actualend) = mysql_fetch_array($result);
	}
}

######################################################################################################
#####		Load project data form
	echo "<p><form ENCTYPE=\"multipart/form-data\" method=\"POST\" 
		action=\"$PHP_SELF\" name=\"uploadfileform\">";
	include("userstr.inc");
	echo '<input type="hidden" name="MAX_FILE_SIZE" value="20000000">';
	echo "<table>";
	echo "<input type=hidden name=copyfileto value=\"rladoc/\">";
	
	$sql = "SELECT projcode_id as id, brief_code 
       FROM timesheet.projcodes 
       WHERE brief_code not like '%OHD%'
	 	ORDER BY brief_code;";
       //WHERE brief_code not like '%OHD%'
       //WHERE brief_code like '%OHD%'
       //WHERE brief_code like '%OHD%'
    $result = mysql_query($sql);
    include("err_msg.inc");
    $no = mysql_num_rows($result);
	echo "<tr><th align=left>Select Project</th><td><select name=projectid>";	// ($no)
    while (list($id, $brief_code) = mysql_fetch_array($result)) {
    	$brcode[$id] = $brief_code;
    	if ($projectid == $id) {
			echo "<option selected value=$id>$brief_code";
		} else {
			echo "<option value=$id>$brief_code";
		}
    }	
	echo "</option></select></td></tr>";

	echo "<tr><th align=left>Project Phase</th><td><select name=phaseno>";
   	for ($i=1; $i<10; $i++) {
    	if ($phaseno == $i) {
			echo "<option selected>$i";
		} else {
			echo "<option>$i";
		}
    }	
	echo "</option></select></td></tr>";
	
	$dstr = date("Y-m-d");
	if (!$actualstart) {
		$actualstart = $dstr;
	}
	if (!$actualend ) {
		$actualend = $dstr;
	}
	echo "<tr><th align=left>Actual start date</th><td>
	<input name=actualstart value=\"$actualstart\"><b>If you don't change the date, the default</b></td></tr>";
	echo "<tr><th align=left>Actual end date</th><td>
	<input name=actualend value=\"$actualend\"><b>will be extracted from your file.</b></td></tr>";
	
if ($modifyprojectfile) {
	echo "<input type=hidden name=budgetfileid value=$budgetfileid>";
	echo '<tr><td colspan=2 align=middle>
	<button type=submit name=modifybudgetdataentry onclick="return (datacheck());"';
	echo ' onSubmit="return (datacheck());"';
	echo '<font color=#0000ff size=3><b>Modify</b></font></button>';
	echo "</td></tr>";
} else {
	echo "<tr><th align=left>Project data file:</th>";
	echo "<td><input name=\"filename\" TYPE=\"file\" size=\"50\"><td></tr>";
	//echo "<tr><th colspan=2>(Maximum file size are 20 MB)</th></tr>";
	echo "<tr><th colspan=2 align=left>(<font color=#ff0000>Project data file has to be in excel csv format.</font>)</th></tr>";
	echo "<tr><th colspan=2>&nbsp;</th></tr>";
		
	if ($priv == "00") {
		echo "<tr><th align=left>Clear All Budget Data?</th><td><select name=clearallbudgetrecord>";
			echo "<option>no";
			echo "<option>yes";
	echo "</option></select></td></tr>";
	}
	echo '<tr><td colspan=2 align=middle><button onClick="return (uploadverify());"';
	echo ' onSubmit="return (uploadverify());"';
	echo " type=\"submit\" name=\"upload\" ><font color=#0000ff size=3><b>Submit</b></font></button>";
	echo "</td></tr>";
}
	/*
	//echo "<input type=\"submit\" name=\"cancel\" value=\"Cancel\">";
	echo '<tr><td colspan=2 align=middle><button onClick="return (uploadverify());"';
	echo ' onSubmit="return (uploadverify());"';
	echo " type=\"submit\" name=\"upload\"><font size=3><b>Upload File</b></font></button>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	echo "<button type=\"submit\" name=\"cancel\"><font size=3><b>Cancel</b></font></button>
	</td></tr>";
	//*/
	echo "</table>";
	echo "</form>";
	
######################################################################################################
#####		delete project file record
if ($deleteprojectfile) {
	$sql = "DELETE FROM timesheet.projbudgetfile 
        WHERE budgetfileid='$autoid' and projcode_id='$projectid';";
   $result = mysql_query($sql);
   include("err_msg.inc");
   echo "<hr><h2>Project budget file <font color=#ff0000>".$brcode[$projcode_id]."</font> has been removed.</h2>";
}

######################################################################################################
#####		view project file
if ($viewprojectfile) {
	$sql = "SELECT email_name as ename, budgetfile, phaseno, budgetstart, 
            budgetend, actualstart, actualend, date 
        FROM timesheet.projbudgetfile 
        WHERE budgetfileid='$budgetfileid' and projcode_id='$projcode_id';";
   $result = mysql_query($sql);
   include("err_msg.inc");
   list($ename, $budgetfile, $phaseno, 
   		$budgetstart, $budgetend, $actualstart, $actualend, $date ) = mysql_fetch_array($result);
	echo "<hr><h2>Budget File for Project <font color=#0000ff>".$brcode[$projcode_id]."</font> (Phase $phaseno).</h2>";
   echo "<h3>This budget file was provided by <font color=#ff0000>$ename</font> on $date.</h3>";
   echo "<table>";
   echo "<tr><th>&nbsp;</th><th>Budget</th><th>Actual</th></tr>";
   echo "<tr><th align=left>Start Date</th><td>$budgetstart</td><td>$actualstart</td></tr>";
   echo "<tr><th align=left>End Date</th><td>$budgetend</td><td>$actualend</td></tr>";
   echo "</table>";
   
	displaybudgetdata($budgetfile,$ref);
}

if ($modifybudgetdataentry) {
	echo "<hr>";
	$dstr = date("Y-m-d");
	if ($actualstart == $actualend) {
		$sql = "UPDATE timesheet.projbudgetfile 
        SET projcode_id='$projectid', phaseno='$phaseno'  
        WHERE budgetfileid='$budgetfileid'";
	} else {
		$sql = "UPDATE timesheet.projbudgetfile 
        SET projcode_id='$projectid', phaseno='$phaseno', actualstart='$actualstart', actualend='$actualend'  
        WHERE budgetfileid='$budgetfileid'";
   }
	#echo "$sql<br>";
   $result = mysql_query($sql);
   include("err_msg.inc");
	echo "<h2>Project Budget Data Modification Completed.</h2>";
}

######################################################################################################
#####		Process Loaded project data
if ($upload) {
	$fldcopy = "$patdir1/$copyfileto";
	/*
	echo "<b>File copyed to</b>: $fldcopy<br>";
	echo "<b>System temperary file</b>: \"$filename\"<br>";
	echo "<b>File name</b>: \"$filename_name\"<br>";
	//*/

	if (!copy($filename, $fldcopy.$filename_name)) {
    	print("<br><br>Failed to upload file \"$filename_name\"...<br>\n");
	} else {
		########file copied to system successfully
		echo "<hr>";		
		# check whether a project file has been in DB marked as y
		$sql = "SELECT budgetfileid
        FROM timesheet.projbudgetfile 
        WHERE email_name='$email_name' and projcode_id='$projectid'
        	and phaseno='$phaseno' and neworold='y';";
    	$result = mysql_query($sql);
    	include("err_msg.inc");
    	list($budgetfileid) = mysql_fetch_array($result);
		if ($budgetfileid) {		
			# a prevoius project file exist
    		$sql = "UPDATE timesheet.projbudgetfile 
        		SET neworold='n' 
        		WHERE budgetfileid='$budgetfileid';";
    		$result = mysql_query($sql);
    		include("err_msg.inc");
		}
		
		# enter new project file
		$fpcsv	=	fopen("$fldcopy$filename_name",'r');
		$nl = chr(13).chr(10);
		$noline = 0;
		$daterow = -1;
		if ($fpcsv) {
			while ($buffer = fgets($fpcsv, 5000)) {
				$buffer = ereg_replace("$nl","",$buffer);
				$buffer = trim($buffer);
				if ($buffer) {
					$linestr[$noline] = $buffer;
					//echo "$noline:    $buffer<br>";
					if ($daterow == -1) {
						$daterow = findlinecontaindate($buffer,$noline);
					}
					$noline++;
				}
			}
		} else {
			echo "<h2><font color=#ff0000>Failed to open uploaded file $filename_name.</font></h2>";
			exit;
		}
		
		########after open uploaded file and read all contents then remove it from system
		exec("rm -f \"$fldcopy$filename_name\"");
		
		###########################################################################
		###		convert date to standard yyyy-mm-dd

		$datelinestr = $linestr[$daterow];
		$datearray = explode(",", $datelinestr);
		for ($i=0; $i<count($datearray); $i++) {
			$date = trim($datearray[$i]);
			if ($date){
				$match = "";
				for ($j=0; $j<count($datepattren); $j++) {
					$pat = $datepattren[$j];
					if ( eregi( "$pat", $date, $regs ) ) {
						#echo "<b>&nbsp;&nbsp;Find match with pattern ($j) $pat: ";
						if ($j==0) {
							$datearray[$i] = convertyear($regs[1])."-".convertmdno($regs[2])."-".convertmdno($regs[3]);
						} elseif ($j==1) {
							$datearray[$i] = convertyear($regs[3])."-".convertmdno($regs[2])."-".convertmdno($regs[1]);
						} elseif ($j==2) {
							$datearray[$i] = convertyear($regs[3])."-".convertmdstr($regs[2])."-".convertmdno($regs[1]);
						} elseif ($j==3) {
							$datearray[$i] = convertyear($regs[3])."-".convertmdstr($regs[1])."-".convertmdno($regs[2]);
						} elseif ($j==4) {
							$datearray[$i] = convertyear($regs[2])."-".convertmdstr($regs[1])."-01";
						}
		  			 	$match = "yes";
		  			 	break;
					}
				}
				if (!$match) {
		 	  		echo "Invalid date format: $date";
			   	}
		   	}
		}
		$datelinestr = "";
		for ($i=0; $i<count($datearray)-1; $i++) {
			$datelinestr .= $datearray[$i].",";
		}
		$datelinestr .= $datearray[$i];
		$linestr[$daterow] = $datelinestr;
		$budgetstart = "";
		$budgetend = "";
		for ($i=0; $i<count($datearray); $i++) {
			if ($datearray[$i]) {
				$budgetstart = $datearray[$i];
				break;
			}
		}
		for ($i=count($datearray)-1; $i>=0; $i--) {
			if ($datearray[$i]) {
				$budgetend = $datearray[$i];
				$mth2d = substr($budgetend,5,2);
				$budgetend = substr($budgetend,0,8).$daysinmth["$mth2d"];
				break;
			}
		}

		$filedata = "";
		for ($i=0; $i<$noline; $i++) {
			$tmp = $linestr[$i];
			$filedata .= $tmp."<br>";
		}
		
		#clear table
		if ($clearallbudgetrecord == "yes") {
			$sql = "DELETE FROM timesheet.projbudgetfile";
			$result = mysql_query($sql);
			include("err_msg.inc");
		}
		
		$date = date("Y-m-d");
		if ($actualstart == $date) {
			$actualstart = $budgetstart;
		}
		if ($actualend == $date) {
			$actualend = $budgetend;
		}
    	$sql = "INSERT INTO timesheet.projbudgetfile 
        	SET budgetfileid='null', email_name='$email_name', 
            projcode_id='$projectid', phaseno='$phaseno', budgetfile='$filedata', 
            neworold='y', date='$date', 
            budgetstart='$budgetstart', budgetend='$budgetend', 
            actualstart='$actualstart', actualend='$actualend',
            dateinrow='$daterow';";

    	$result = mysql_query($sql);
    	include("err_msg.inc");
    	$autoid = mysql_insert_id();
    	//echo $autoid."<br>";
		echo "<h2>Contents in your project budget file are as follows.</font></h2>";
    	$userstr	=	"?".base64_encode($userinfo."&deleteprojectfile=y&projcode_id=$projectid&autoid=$autoid");
    	$ref = "<h3><a href=\"$PHP_SELF$userstr\">Delete This Budget File.</a></h3>";
		displaybudgetdata($filedata,$ref);

		echo "<h3><br><font color=#0000ff>Budget file for  project <font color=#ff0000>".
			$brcode[$projectid]."</font> have been successfully entered into DB.</font></h3><br>";
		
		/*
		echo "<b><br><br>\"$filename_name\" has been uploaded, and ".
    	" is available for <a href=\"/$copyfileto$filename_name\" target=\"_blank\">view</a>.";
		echo "<br>File absolute path on $SERVER_NAME is $fldcopy$filename_name.</b>";
		//*/
	}
}

if ($cancel) {
	echo "<h3><br><br>You can upload file later.</h3>";
	exit;
}

######################################################################################################
#####		List previous project data
	#####	for project leader
	$date = date("Y") - 5;
	$date = $date."-".date("m-d");
   $sql = "SELECT budgetfileid, projcode_id as pid, phaseno, date, 
   			budgetstart, budgetend, actualstart, actualend
        	FROM timesheet.projbudgetfile 
        	WHERE email_name='$email_name' and date>'$date' ORDER BY date DESC;";
   	//echo "<hr>$sql";
    $result = mysql_query($sql);
    include("err_msg.inc");
    $no = mysql_num_rows($result);
    if ($no) {
    	echo "<hr><h2>My Previous Project Budget File</h2>";
    	echo "<table border=1>";
    	echo "<tr><th>Project Title</th><th>Phase</th><th>Upload Date</th>
    	<th>Start Date (B)</th><th>End Date (B)</th>
    	<th>Start Date (A)</th><th>End Date (A)</th>
    	<th>Action</th></tr>";
    	while (list($budgetfileid, $pid, $phaseno, $date, 
    	$budgetstart, $budgetend, $actualstart, $actualend) = mysql_fetch_array($result)) {
    		echo "<tr><td>".$brcode[$pid]."</td><td>$phaseno</td>
    		<td>$date</td>
    		<td>$budgetstart</td><td>$budgetend</td>
    		<td>$actualstart</td><td>$actualend</td><td>";
    		$userstr	=	"?".base64_encode($userinfo."&viewprojectfile=y&budgetfileid=$budgetfileid&projcode_id=$pid");
    		echo "<a href=\"$PHP_SELF$userstr\">[View]</a>";
    		$userstr	=	"?".base64_encode($userinfo."&modifyprojectfile=y&budgetfileid=$budgetfileid&projcode_id=$pid");
    		echo "<a href=\"$PHP_SELF$userstr\">[Modify]</a>";
    		echo "</td></tr>";
       }
       echo "</table><p>";
    }
    
	#####	for company director
	if ($priv == "00" || $priv == "10") {	//	list all budget datafile
    	$sql = "SELECT budgetfileid, email_name as ename, projcode_id as pid, phaseno, date, 
    		budgetstart, budgetend, actualstart, actualend
        	FROM timesheet.projbudgetfile 
        	WHERE neworold='y' and date>'$date' ORDER BY date DESC;";
   }
    $result = mysql_query($sql);
    include("err_msg.inc");
    $no = mysql_num_rows($result);
    if ($no) {
    	echo "<hr><h2>All Project Budget Files</h2>";
    	echo "<table border=1>";
    	echo "<tr><th>Project Title</th><th>Phase</th><th>Submitted By</th><th>Upload Date</th>
    	<th>Start Date (B)</th><th>End Date (B)</th>
    	<th>Start Date (A)</th><th>End Date (A)</th>
    	<th>Action</th></tr>";
    	while (list($budgetfileid, $ename, $pid, $phaseno, $date, 
    		$budgetstart, $budgetend, $actualstart, $actualend) = mysql_fetch_array($result)) {
    		$userstr	=	"?".base64_encode($userinfo."&viewprojectfile=y&budgetfileid=$budgetfileid&projcode_id=$pid");
    		echo "<tr><td>".$brcode[$pid]."</td><td>$phaseno</td><td>$ename</td>
    		<td>$date</td>
    		<td>$budgetstart</td><td>$budgetend</td>
    		<td>$actualstart</td><td>$actualend</td><td>
    		<a href=\"$PHP_SELF$userstr\">[View]</a>
    		</td></tr>";
       }
       echo "</table><p>";
    }
   //echo "<hr>$sql";
  
function displaybudgetdata($filedata,$ref){
	//echo $filedata;
	$linestr = explode("<br>",$filedata);
	$noline = count($linestr);
	$tmp0 = explode(",",$linestr[0]);
	$nocol = count($tmp0);
	$heading = ereg_replace(",", "", $linestr[0]);
	echo "<p><table border=1>";
	echo "<tr><th colspan=$nocol>$heading</th></tr>";
	for ($i=1; $i<$noline; $i++) {
		if ($linestr[$i]) {
			$budgetdata[$i] = explode(",",$linestr[$i]);
			echo "<tr>";
			for ($j=0; $j<count($budgetdata[$i]); $j++) {
				if (!$budgetdata[$i][$j]) {
					$budgetdata[$i][$j] = "&nbsp;";
				}
				if ($j<2) {
					echo "<th align=left>".$budgetdata[$i][$j]."</th>";
				} else {
					echo "<td align=right>".$budgetdata[$i][$j]."</td>";
				}
			}
			echo "</tr>";
		}
	}
	echo "</table><p>";
	if ($ref) {
		echo $ref."<p><p>";
	}
}

function findlinecontaindate($linestr,$noline) {
global $mths, $datepattren;

	$tmpstr = $linestr;
#step 1: determine date format
	for ($i=0; $i<count($datepattren); $i++) {
    	$pat = $datepattren[$i];
    	if ( eregi( "$pat", $tmpstr , $regs ) ) {
			$match = "yes";
    		//echo "$tmpstr <br>";
    		//echo "$pat<br>";
        	break;
    	}
    }
	if (!$match) {#This line doesnot contain date
		return -1;
	}

#step 2: find number of date entry
	$datearray = explode(",", $tmpstr);
	$match = 0;
	for ($i=0; $i<count($datearray); $i++) {
    	$tmp = trim($datearray[$i]);
    	if ($tmp) {
    		if ( eregi( "$pat", $tmp, $regs ) ) {
    			//echo "$i  $pat:  $tmp ".$regs[1]."-".$regs[2]."-".$regs[3]."=".$regs[0] ."<br>";
    			$match++;
    		}
    	}
    }
	if ($match>4) {
		//echo "$match date string have been found.<br>";
		return	$noline;
	} else {
		return -1;
	}
}
?>
<hr><br><a href=#top><b>Back to top</b></a><br><br>
</html>
