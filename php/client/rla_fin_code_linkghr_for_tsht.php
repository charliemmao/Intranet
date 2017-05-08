<html>
<body background="rlaemb.JPG" leftmargin="20">
<?php
//js
include('str_decode_parse.inc');
include("rla_functions.inc");
include("userinfo.inc"); //$userinfo $frm_str
//echo "$mystat<br>";
if (!($priv	==	'00' || $priv	==	'10')) {
	exit;
}
$userstr	=	"?".base64_encode($userinfo."&mystat=$mystat");
//echo '<input type="hidden" name="frm_str" value= "'.$userstr.'">';
//echo $PHP_SELF."<br>";

echo "<h2 align=center><a id=top>Link GHR Cost Center to RLA Code: For Timesheet</a>";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[Refresh]</font></a></h2><hr>";
include("connet_root_once.inc");

$server_name= "http://".getenv('SERVER_NAME');

#################Process
if ($linkcodefortsht) {
	//echo "$rlaprojcodestr<br>$ghrcodeid<br>$modify<br>$activecode<br><br>";
	$codeid = substr($rlaprojcodestr, 0, strlen($rlaprojcodestr)-1);
	$grporind = substr($rlaprojcodestr, strlen($rlaprojcodestr)-1, 1);
	//echo "$codeid<br>$grporind<br>";
	
	//find rlacode string
	if ($grporind == "i") {
		$sql = "SELECT projcode_id, brief_code, description, special, 
          	div15, begin_date, end_date, costcenter 
        	FROM timesheet.projcodes 
        	WHERE projcode_id='$codeid';";
    		$result = mysql_query($sql);
    	include("err_msg.inc");
    	list($projcode_id, $brief_code, $description, $special, 
            $div15, $begin_date, $end_date, $costcenter) = mysql_fetch_array($result);
       $rlacode0 = $brief_code;
       /*
       echo "<br>projcode_id=$projcode_id<br>brief_code=$brief_code<br>description=$description<br>special=$special<br>
            div15=$div15<br>begin_date=$begin_date<br>end_date=$end_date<br>costcenter=$costcenter<br>";
		//*/
	} else {
		$sql = "SELECT codehead_id, code_prefix, codelable 
        	FROM timesheet.code_prefix 
        	WHERE codehead_id='$codeid';";
    	$result = mysql_query($sql);
    	include("err_msg.inc");
    	list($codehead_id, $code_prefix, $codelable) = mysql_fetch_array($result);
       $rlacode0 = $codelable;
    	//echo "<br>codehead_id=$codehead_id<br>code_prefix=$code_prefix<br>codelable=$codelable<br>";
	}

	//find ghr code string and cost center
	$sql = "SELECT code_id, description, codes, category, 
            rlaactive, dateentry, datemod 
        FROM rlafinance.codeid 
        WHERE code_id='$ghrcodeid';";
    $result = mysql_query($sql);
    include("err_msg.inc");
    list($code_id, $description, $codes, $category, 
            $rlaactive, $dateentry, $datemod) = mysql_fetch_array($result);
   /*
	echo "<br>code_id=$code_id<br>description=$description<br>codes=$codes<br>category=$category<br>
            rlaactive=$rlaactive<br>dateentry=$dateentry<br>datemod=$datemod<br>";
	//*/
	
	/* table: ghrtorlacode. columns: ghrcode, rlacode, costcenter, dateenter, enddate, active, isgroup  */
	//check whether this ghr code has an entry and being linked to rla code
	$sql = "SELECT rlacode as rc
        FROM timesheet.ghrtorlacode 
        WHERE ghrcode='$description';";
    $result = mysql_query($sql);
    include("err_msg.inc");
    list($rc) = mysql_fetch_array($result);
    
	//check whether this rla code being linked to ghr code
	$sql = "SELECT ghrcode as gc
        FROM timesheet.ghrtorlacode 
        WHERE rlacode='$rlacode0';";
    $result = mysql_query($sql);
    include("err_msg.inc");
    list($gc) = mysql_fetch_array($result);
	
	if ($activecode == "Yes") {
		$activecode = "y";
	} else {
		$activecode = "n";
	}
	if ($grporind == "g") {
		$grporind = "y";
	} else {
		$grporind = "n";
	}
	$sql = "";
	$dateenter = date("Y-m-d");
	
	if ($rc && $gc) {	//both rla/ghr code have been linked
		if ($modify == "No") {
			$rsp = "<h3><font color=#ff0000>$gc was linked to $rc. If you want to modify this link, ".
				"please change modify option to \"Yes\" and submit again.</font></h3>";
		} else {
			if ($rc == $rlacode0 && $gc == $description){
				//activate or deactivate link between GHR/RLA codes
				$sql = "UPDATE timesheet.ghrtorlacode 
					SET active='$activecode'
        			WHERE ghrcode='$description';"; 
				$rsp = "<h4>Link between $gc and $rc has been modified successfully.".
					" This link is now ";
				if ($activecode == "y") {
				 	$rsp .= "<font color=#0000ff>activated and will";
				} else {
				 	$rsp .= "<font color=#ff0000>deactivated and will not";
				}
				$rsp .= "</font> be used in summary report to GHR.</h4>";
			} else {
    			$rsp = "<h4>Currently linking status:<font color=#ff0000><br>".
    				"&nbsp;&nbsp;$description (GHR code)<-->$rc (RLA code)<br>".
    				"&nbsp;&nbsp;$rlacode0 (RLA code)<-->$gc (GHR code)</font><br>Process aborted.<br></h4>";
			}
		}
	} elseif ($gc || $rc) {	//one of the code is linked to other code
		if ($modify == "No") {
			$rsp = "<h3><font color=#ff0000>";
			if ($gc) {
				$rsp .= "$rlacode0 has been linked to $gc.";
			} else {
				$rsp .= "$description has been linked to $rc.";
			}
			$rsp .= " If you want to modify this link, ".
			"please change modify option to \"Yes\" and submit again.</font></h3>";
		} else {
			if ($rc) {
				$sql = "UPDATE timesheet.ghrtorlacode 
					SET rlacode='$rlacode0', costcenter='$codes', 
						active='$activecode', isgroup='$grporind'
        			WHERE ghrcode='$description';"; //Primary key: ghrcode
				$rsp = "<h3><font color=#0000ff>$description has been successfully ".
					"modified and linked to $rlacode0 with ".
			 		"GHR cost center number $codes.</font></h3>";
			} else {
				//delete current rla link to ghr
				$sql = "DELETE FROM timesheet.ghrtorlacode 
        			WHERE rlacode='$rlacode0';";
   				$result = mysql_query($sql);
    			include("err_msg.inc");
				$rsp = "<h4><font color=#ff0000>Link between $rlacode0 and $gc has been removed.</font><br><br>".
					"<font color=#0000ff>New link $description and $rlacode0 has been successfully established.".
					"</font></h4>";
				$sql = "INSERT INTO timesheet.ghrtorlacode 
					VALUES('$description', '$rlacode0', '$codes', 
					'$dateenter', '0000-00-00', '$activecode', '$grporind');";
			}
		}
	} else {
		//insert new link
		$sql = "INSERT INTO timesheet.ghrtorlacode 
			VALUES('$description', '$rlacode0', '$codes', 
			'$dateenter', '0000-00-00', '$activecode', '$grporind');";
		$rsp = "<h3><font color=#0000ff>$rlacode0 has been successfully linked to $description with ".
			 "GHR cost center number $codes.</font></h3>";
	}
	if ($sql) {
		if ($priv == "00") {	echo "$sql<br>";}    
   		$result = mysql_query($sql);
    	include("err_msg.inc");
	}
	echo $rsp;
	echo "<br>==================<br>".
		"Please remember GHR cost center code and RLA project individual/group code ".
		"linking status has significant impact on the summary report to GHR. Special attention should be paid ".
		"in the case where a group code listing and an individual code listing from that group are required ".
		"by GHR, a change to web script that generates the report is necessary to ensure the report accurate.<br>".
		"<br>";
	echo "<hr>";
}

################ Form to Link RLA code to GHR code for timesheet summary
#	get all codes
#	RLA project code (individual)
	$sql = "SELECT projcode_id as id, brief_code as bc, description as des
        FROM timesheet.projcodes
        WHERE costcenter='y'
        ORDER BY brief_code;";
    $result = mysql_query($sql);
    include("err_msg.inc");
    $i = 0;
    while (list($id, $bc, $des) = mysql_fetch_array($result)) {
        $rlacode[$i][0] = $id;
        $rlacode[$i][1] = $bc;
        $rlacode[$i][2] = $des;
        $rlacodehash[$bc] = $i;
        $i++;
    }
    $norla_i = $i;
    
#	RLA project code (group)
	$sql = "SELECT codehead_id as id, code_prefix as cp, codelable 
        FROM timesheet.code_prefix 
        ORDER BY codelable;";
    $result = mysql_query($sql);
    include("err_msg.inc");
    $j = 0;
    while (list($id, $cp, $codelable) = mysql_fetch_array($result)) {
        $rlacode[$i][0] = $id;
        $rlacode[$i][1] = $codelable;
        $rlacode[$i][2] = $cp;
        $rlacodehash[$codelable] = $i;
        $i++;
        $j++;
    }
    $norla_g = $j;
    $norla = $i;

#	GHR Cost Center
	$sql = "SELECT code_id as id, description as des, codes 
        FROM rlafinance.codeid 
        WHERE category='cc' and rlaactive='y'
        ORDER BY des;";

    $result = mysql_query($sql);
    include("err_msg.inc");
    $i=0;
	
    while (list($id, $des, $codes) = mysql_fetch_array($result)) {
        $ghrcode[$i][0] = $id;
        $ghrcode[$i][1] = $des;
        $ghrcode[$i][2] = $codes;
        $ghrcodehash[$codes] = $i;
        $i++;
	}
    $noghr = $i;

//echo "<form name=codelink action=\"$server_name$PHP_SELF\" method=\"POST\">";
echo "<form>";
echo '<input type="hidden" name="frm_str" value= "'.$userstr.'">';
echo "<table border=0>";

$sql = "SELECT ghrcode as gc, rlacode as rc
        FROM timesheet.ghrtorlacode ORDER BY rlacode"; 
    $result = mysql_query($sql);
    include("err_msg.inc");
while (list($gc, $rc) = mysql_fetch_array($result)) {
	$keyhash[$gc] = $rc;
	$keyhash[$rc] = $gc;
}
##	List GHR Cost center code
echo "<tr><th align=left>GHR Cost Center ($noghr)</th><td><select name=ghrcodeid>";
	for ($i=0; $i<$noghr; $i++) {
		$val = $ghrcode[$i][0];
		if ($ghrcode[$i][0] == $ghrcodeid) {
			$sel = "selected";
		} else {
			$sel = "";
		}
		echo "<option $sel value=\"$val\">".$ghrcode[$i][1]." (".
			$ghrcode[$i][2].")". " <--> ".$keyhash[$ghrcode[$i][1]];
	}
echo "</option></select></td></tr>";

#	List RLA project code (individual + group)
echo "<tr><th align=left>RLA Code ($norla_i i + $norla_g g)</th>";
echo "<td><select name=rlaprojcodestr>";
	for ($i=0; $i<$norla; $i++) {//norla 
		if ($i >= $norla_i) {
			$tmp = " (g)";
			$val = "g";
		} else {
			$tmp = " (i)";
			$val = "i";
		}
		if ($rlacode[$i][0].$val == $rlaprojcodestr) {
			$sel = "selected";
		} else {
			$sel = "";
		}
		$val = $rlacode[$i][0].$val;
		echo "<option $sel value=\"$val\">".$rlacode[$i][1].$tmp. " <--> ".$keyhash[$rlacode[$i][1]];
	}
echo "</option></select></td></tr>";

echo "<tr><th align=left>Include in GHR Summary?</th><td><select name=activecode>";
	$mod[0] = "Yes";
	$mod[1] = "No";
	for ($i=0; $i<2; $i++) {
		if ($activecode == $mod[$i]) {
			echo "<option selected value=\"".$mod[$i]."\">".$mod[$i];
		} else {
			echo "<option value=\"".$mod[$i]."\">".$mod[$i];
		}
	}
echo "</option></select></td></tr>";

echo "<tr><th align=left>Modify?</th><td><select name=modify>";
	$mod[0] = "No";
	$mod[1] = "Yes";
	for ($i=0; $i<2; $i++) {
		if ($modify == $mod[$i]) {
			echo "<option selected value=\"".$mod[$i]."\">".$mod[$i];
		} else {
			echo "<option value=\"".$mod[$i]."\">".$mod[$i];
		}
	}
echo "</option></select>";

//echo "</td></tr><tr><td colspan=2 align=center>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
echo "<input type=\"submit\" name=linkcodefortsht value=\"Link These Two Codes\">";
echo "</td></tr>";
echo "</table><p>";
echo "</form>";

echo "<hr><h2>Current code link table</h2>";
echo "<table border=1><tr><th>Count</th><th>RLA Code</th><th>Group Code?<th>GHR Code</th>
	<th>GHR No</th></th><th>Active?</th></tr>";
        
	$sql = "SELECT ghrcode, rlacode, costcenter, dateenter, 
            enddate, active, isgroup 
        FROM timesheet.ghrtorlacode ORDER BY rlacode"; 
    $result = mysql_query($sql);
    include("err_msg.inc");
    $ctr = 0;
while (list($ghrcode, $rlacode, $costcenter, $dateenter, 
            $enddate, $active, $isgroup) = mysql_fetch_array($result)) {
   $ctr++;
   if ($isgroup == "y") {
   		$isgroup = "<font color=#ff0000><b>$isgroup</b></font>";
   	} else {
   		$isgroup = "&nbsp;";
   	}
   if ($active == "n") {
   		$active = "<font color=#ff0000><b>$active</b></font>";
   	}
	echo "<tr><td>$ctr</td><td>$rlacode</td><td align=center>$isgroup</td>
		<td>$ghrcode</td><td align=right>$costcenter</td><td align=center>$active</td></tr>";
}
echo "</table><p>";

###################
echo "<hr><br><a href=#top>Back to top</a><br><br>";
?>
</html>
