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
	return true;
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
if ($priv == "00" || $priv	==	'10') {
} else {
	exit;
}
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
	 	ORDER BY brief_code;";
       //WHERE brief_code not like '%OHD%'
       //WHERE brief_code like '%OHD%'
       //WHERE brief_code like '%OHD%'
    $result = mysql_query($sql);
    include("err_msg.inc");
    $no = mysql_num_rows($result);
	//echo "<tr><th align=left>Select Project</th><td><select name=projectid>";	// ($no)
	$i=0;
    while (list($id, $brief_code) = mysql_fetch_array($result)) {
    	$brcode[$id] = $brief_code;
    	$rlacodearray[$i] = $id;
    	$i++;
    	if ($projectid == $id) {
			//echo "<option selected value=$id>$brief_code";
		} else {
			//echo "<option value=$id>$brief_code";
		}
    }	
	//echo "</option></select></td></tr>";
	
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
	echo "<tr><th colspan=2 align=left>(<font color=#ff0000>Project data file has to be a text file.</font>)</th></tr>";
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
   echo $sql."<br>";
   exit;
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
		#clear budget data from all tables
		if ($clearallbudgetrecord == "yes") {
			clearallbudgetdata("bgtfileidx");
			clearallbudgetdata("bgtfilemlstone");
			clearallbudgetdata("bgtfileres_task_hr");
			clearallbudgetdata("bgtfileresource");
			clearallbudgetdata("bgtfiletasks");
		}

		# read project file
		$fpcsv	=	fopen("$fldcopy$filename_name",'r');
		$nl = chr(13).chr(10);
		$noline = 0;
		if ($fpcsv) {
			while ($buffer = fgets($fpcsv, 5000)) {
				$buffer = ereg_replace("$nl","",$buffer);
				$buffer = ereg_replace("\"","",$buffer);//\"
				$buffer = trim($buffer);
				if ($buffer) {
					//$buffer = ereg_replace("'","\\'", $buffer)
					$linestr[$noline] = $buffer;
					#echo "$noline:    $buffer<br>";
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
		###		parse uploaded file
		/*table: bgtfileidx
		bgtfileidx, brief_code, description, client, begin_date, end_date, preparedby, uploaddate, active
       //*/
       $fldseppat = $linestr[0];
		$briefcodefromfile = $linestr[1];
		$description = $linestr[2];
		$client = $linestr[3];
		$begin_date = $linestr[4];
		$end_date = $linestr[5];
		$preparedby = $linestr[6];
		$uploaddate = $linestr[7];
		$active = "y";
		
       /*table: bgtfiletasks
       taskidx, bgtfileidx, m_stasks, tasks, date_start, date_end, nomths, hours
       //*/ 
		$daterow = 8;
		$datelinestr = $linestr[$daterow];
		$datearray = explode("$fldseppat", $datelinestr);
		$notasks = $datearray[1];
		$daterow++;
		$k = $daterow+$notasks;
		$taskctr = 0;
		for ($i=$daterow; $i<$k; $i++) {
			$datelinestr = $linestr[$i];
			$datearray = explode("$fldseppat", $datelinestr);
			for ($j=0; $j<count($datearray); $j++) {
				$tasklist[$taskctr][$j] = $datearray[$j];
			}
			$taskctr++;
		}
		
       /* table: bgtfilemlstone. columns: mlsidx, bgtfileidx, mlstone, date,  */ 
		$daterow=$daterow+$notasks;
		$datelinestr = $linestr[$daterow];
		$datearray = explode("$fldseppat", $datelinestr);
		$nomilestone = $datearray[1];
		$daterow++;
		$k = $daterow+$nomilestone;
		$taskctr = 0;
		for ($i=$daterow; $i<$k; $i++) {
			$datelinestr = $linestr[$i];
			$datearray = explode("$fldseppat", $datelinestr);
			for ($j=0; $j<count($datearray); $j++) {
				$mlstonelist[$taskctr][$j] = $datearray[$j];
			}
			$taskctr++;
		}

       /*table: bgtfileresource
       bgtrscidx, bgtfileidx, email_name, title, level, hrateus */
		$daterow=$daterow+$nomilestone;
		$datelinestr = $linestr[$daterow];
		$datearray = explode("$fldseppat", $datelinestr);
		$nostaffworkon = $datearray[1];
		$daterow++;
		$k = $daterow+$nostaffworkon;
		$taskctr = 0;
		for ($i=$daterow; $i<$k; $i++) {
			$datelinestr = $linestr[$i];
			$datearray = explode("$fldseppat", $datelinestr);
			for ($j=0; $j<count($datearray); $j++) {
				$stafflist[$taskctr][$j] = $datearray[$j];
			}
			$taskctr++;
		}
		
		$daterow=$daterow+$nostaffworkon;
		$datelinestr = $linestr[$daterow];
		$k = $daterow+$notasks;
		$taskctr = 0;
		for ($i=$daterow; $i<$k; $i++) {
			$datelinestr = $linestr[$i];
			$datearray = explode("$fldseppat", $datelinestr);
			for ($j=0; $j<count($datearray); $j++) {
				$taskrsclist[$taskctr][$j] = $datearray[$j];
			}
			$taskctr++;
		}
		
		###########################################################################
		echo "<h2>Heading section</h2>";
		echo "<table border=1>";
		echo "<tr><th align=left>Project Code</th><td>$briefcodefromfile</td></tr>";
		echo "<tr><th align=left>Description</th><td>$description</td></tr>";
		echo "<tr><th align=left>Client</th><td>$client</td></tr>";
		echo "<tr><th align=left>Date Start</th><td>$begin_date</td></tr>";
		echo "<tr><th align=left>Date End</th><td>$end_date</td></tr>";
		echo "<tr><th align=left>Prepared by</th><td>$preparedby</td></tr>";
		//echo "<tr><th align=left></th><td>$uploaddate</td></tr>";
		//echo "<tr><th align=left></th><td>$active</td></tr>";
		echo "</table>";
		
		echo "<h2>Task list</h2>";
		echo "<table border=1>";
		echo "<tr><th>No</th><th>Main/Sub Task</th><th>Descriptions</th><th>From</th><th>To</th><th>Duration (Month)</th><th>Hours</th></tr>";
		for ($i=0; $i<$notasks; $i++) {
			$j = $i +1;
			echo "<tr><td>$j</td>";
			if ($tasklist[$i][0] == "m") {
				echo "<td><b>Main Task</b></td>";
			} else {
				echo "<td align=right>Sub Task</td>";
			}
			for ($j=1; $j<4; $j++) {
				echo "<td>".$tasklist[$i][$j]."</td>";
			}
			$dateS = $tasklist[$i][2];
			$dateS = ereg_replace("-", "", $dateS);
			$dateE = $tasklist[$i][3];
			$dateE = ereg_replace("-", "", $dateE);
			echo "<td align=middle>".$tasklist[$i][$j]."</td>";
			echo "<td align=right>".number_format($tasklist[$i][5],2)."</td>";
			echo "</tr>";
			if ($dateE<$dateS) {
				echo "</table><p>";
				echo "<h2><font color=#ff0000>Project ended before it started.<br>";
				echo "Please check last task date setup above.<br><br>File upload aborted.</font></h2>";
				exit;
			}
		}
		echo "</table>";
		
		echo "<h2>Staff list</h2>";
		echo "<table border=1>";
		echo "<tr><th>No</th><th>Name</th><th>Title</th><th>Level</th><th>Rate (\$US/hr)</th></tr>";
		for ($i=0; $i<$nostaffworkon; $i++) {
			$k = $i + 1;
			echo "<tr><td>$k</td>";
			for ($j=0; $j<2; $j++) {
				echo "<td>".$stafflist[$i][$j]."</td>";
			}
			echo "<td align=middle>".$stafflist[$i][2]."</td>";
			echo "<td align=middle>".number_format($stafflist[$i][3],2)."</td>";
			echo "</tr>";
		}
		echo "</table>";
		
		echo "<h2>Detailed resource allocation</h2>";
		echo "<table border=1>";
		echo "<tr><th>Task No</th>";
		for ($i=0; $i<$nostaffworkon; $i++) {
			echo "<th>".$stafflist[$i][0]."</th>";
		}		
		echo "</tr>";
		for ($i=0; $i<$notasks; $i++) {
			if ($taskrsclist[$i][0]) {
				$k = $i + 1;
				echo "<tr><td align=middle>$k</td>";
				for ($j=0; $j<$nostaffworkon; $j++) {
					echo "<td align=right>".number_format($taskrsclist[$i][$j],2)."</td>";
				}
				echo "</tr>";
			}
		}
		echo "</table>";
		
		echo "<h2>Project milestones</h2>";
		echo "<table border=1>";
		echo "<tr><th>No</th><th>Milestone</th><th>Date</th></tr>";
		for ($i=0; $i<$nomilestone; $i++) {
			$k = $i + 1;
			echo "<tr><td>$k</td>";
			for ($j=0; $j<2; $j++) {
				echo "<td>".$mlstonelist[$i][$j]."</td>";
			}
			echo "</tr>";
		}
		echo "</table>";

		###########################################################################
		###		check project code existence
		$brief_code = $briefcodefromfile;
		$projcodematch = "";
		for ($i=0; $i<count($rlacodearray);$i++) {
			$k = $rlacodearray[$i];	//project code id
			//echo "$i: $brief_code == ".$brcode[$k]."<br>";
			if ($brcode[$k] == $brief_code) {
				$projcodematch = 1;
				$projcode_id = $k;
				break;
			}
		}
    	if (!$projcodematch) {
			echo "<h3><br><font color=#ff0000>$brief_code</font> does not exist. Please create new project code".
			" for this budget file or change project code to an existing one from your excel file.".
			" Process aborted.</h3><br>";
    		exit;
    	} 
			
		###########################################################################
		###		enter uploaded file data to database
/*table: bgtfileidx
		bgtfileidx, brief_code, description, client, begin_date, end_date, preparedby, uploaddate, active
       //*/
		$sql = "INSERT INTO timesheet.bgtfileidx
        		VALUES('null', '$projcode_id', '$description', '$client', 
            '$begin_date', '$end_date', '$preparedby', '$uploaddate', 
            '$active');";
       $result = mysql_query($sql);
    	include("err_msg.inc");
		$bgtfileidx = mysql_insert_id();
 		//echo "$sql<br><br>";
		//echo "$bgtfileidx<br>";
		
/*table: bgtfilemlstone. columns: mlsidx, bgtfileidx, mlstone, date,  */
		for ($i=0; $i<$nomilestone; $i++) {
			$mlstone = $mlstonelist[$i][0] ;
			$date = $mlstonelist[$i][1] ;
			$mlstone = mysql_escape_string($mlstone);
			$sql = "INSERT INTO timesheet.bgtfilemlstone
        		VALUES('null', '$bgtfileidx', '$mlstone', '$date');";
       	$result = mysql_query($sql);
    		include("err_msg.inc");
			$mlsidx[$i] = mysql_insert_id();
			//echo "$mlsidx[$i]: $sql<br><br>";
		}

/*table: bgtfiletasks
       taskidx, bgtfileidx, m_stasks, tasks, date_start, date_end, nomths, hours*/ 
		for ($i=0; $i<$notasks ; $i++) {
			$m_stasks = $tasklist[$i][0] ;
			$tasks = mysql_escape_string($tasklist[$i][1]);
			$date_start = $tasklist[$i][2] ;
			$date_end = $tasklist[$i][3] ;
			$nomths = $tasklist[$i][4] ;
			$hours = $tasklist[$i][5] ;
			$sql = "INSERT INTO timesheet.bgtfiletasks
        		VALUES('null', '$bgtfileidx', '$m_stasks', '$tasks', 
            	'$date_start', '$date_end', '$nomths', '$hours');";
          $result = mysql_query($sql);
    		include("err_msg.inc");
			$taskidx[$i] = mysql_insert_id();
			//echo "$taskidx[$i]: $sql<br>";
		}
		
/*table: bgtfileresource
       bgtrscidx, bgtfileidx, email_name, title, level, hrateus */
		//echo "<br>";
		for ($i=0; $i<$nostaffworkon; $i++) {
			$ename = strtolower($stafflist[$i][0]);
			$title = $stafflist[$i][1];
			$level = $stafflist[$i][2];
			$hrateus = $stafflist[$i][3];
          $sql = "INSERT INTO timesheet.bgtfileresource
        		VALUES('null', '$bgtfileidx', '$ename', '$title', 
            	'$level', '$hrateus');";
          $result = mysql_query($sql);
    		include("err_msg.inc");
			$bgtrscidx[$i] = mysql_insert_id();
			//echo "$bgtrscidx[$i]: $sql<br>";
		}
		
		//echo "<br>";
		for ($i=0; $i<$notasks; $i++) {
			$taskidx0 = $taskidx[$i];
			for ($j=0; $j<$nostaffworkon; $j++) {
				$bgtrscidx0 = $bgtrscidx[$j];
				$hours = $taskrsclist[$i][$j];
				if ($hours > 0) {
					$sql = "INSERT INTO timesheet.bgtfileres_task_hr
        				VALUES('null', '$taskidx0', '$bgtrscidx0', '$hours');";
          			$result = mysql_query($sql);
    				include("err_msg.inc");
					$rthidx = mysql_insert_id();
					//echo "$rthidx: $sql<br>";
				}
			}
		}

		echo "<h3><br><font color=#0000ff>Budget file for  project <font color=#ff0000>".
			$brief_code."</font> have been successfully entered into DB.</font></h3><br>";

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

include("ts_proj_budget_deactivedata.inc");

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
function clearallbudgetdata($table) {
	$sql = "DELETE FROM timesheet.$table";
	#echo "<br>$sql.<br>";
	$result = mysql_query($sql);
	include("err_msg.inc");
}
?>
<hr><br><a href=#top><b>Back to top</b></a><br><br>
</html>
