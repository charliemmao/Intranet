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

echo "<h2 align=center><a id=top>Manipulation of GHR Cost Center Code</a>";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[Refresh]</font></a></h2><hr>";
include("connet_root_once.inc");
//rla_fin_process_rla_ghr_code.inc
       
#################Process -- delete record from table
if ($modifyornewcode) {
	/* table: codeid. columns: code_id, description, codes, category, rlaactive, dateentry, datemod */
	$dateentry = date("Y-m-d");
	if ($code_id) {
		$sql = "UPDATE rlafinance.codeid 
        	SET description='$description', 
          	codes='$codes', category='$category', 
          	rlaactive='$rlaactive', datemod='$dateentry' 
        WHERE code_id='$code_id';";
        $tmp = "<b>$description ($codes-$category) is updateed sucessfully.</b>";
   } else {
   		if (!$description || !$codes) {
   			$tmp = "<font color=#ff0000><b>Description and code number can not be empty.</b></font><br>";
   			$tmp .= "<b>Clcik \"Refresh\" or \"Back\" to continue.</b><br>";
   			$sql = "";
   			//exit;
   		} else {
    		$sql = "INSERT INTO rlafinance.codeid
        	VALUES('null', '$description', '$codes', '$category', 
          '$rlaactive', '$dateentry', '$dateentry');";
        	$tmp = "<b>$description ($codes-$category) has been added sucessfully.</b>".
 			 	"<br><br>==================<br>".
				"Please go to \"Link RLA-GHR Codes\" section to establish links for Order System ".	
				"and Timesheet.<br>";
      }
   }
   if ($sql) {
    	$result = mysql_query($sql);
    	include("err_msg.inc");
    	if ($code_id) {
    		$sql = "UPDATE timesheet.ghrtorlacode 
       	 	SET ghrcode='$description', 
            		costcenter='$codes' 
       	 	WHERE ghrcode='$olddescription'";
    		$result = mysql_query($sql);
    		include("err_msg.inc");
    	} else {
    		$code_id = mysql_insert_id();
    		//echo $code_id."<br>";
    	}
    	$selghrcodeid = $code_id;
   }
	echo "<br>$tmp<br><hr>"; 
	$delormod = "Modify";
}

if ($deletecode) {
	$t = explode("-ghr-", $selghrcodeid);
	$selghrcodeid = $t[0];
	$code_id = $t[0];
	$description = $t[1];
	$codes = $t[2];
	$category = $t[3];
	if ($delormod == "Delete") {
    	$sql = "DELETE FROM rlafinance.codeid 
        	WHERE code_id='$selghrcodeid';";
    	$result = mysql_query($sql);
    	include("err_msg.inc");
		//echo $sql."<br>";
		echo "<br><b>".$t[1]." (".$t[2]."-".$t[3].") has been deleted successfully.</b>";
	}
}

echo "<form name=modifynewform>";
if ($delormod == "Modify") {
	echo "<h3>Modify following code</h3>";
} else {
	echo "<h3>Add new code</h3>";
}

echo '<input type="hidden" name="frm_str" value= "'.$userstr.'">';
echo '<input type="hidden" name="code_id" value= "'.$code_id.'">';            
echo '<input type="hidden" name="olddescription" value= "'.$description.'">';            
echo "<table border=0>";
echo "<tr><th align=left>Code Description</th><td>";
	echo '<input type="text" name="description" value= "'.$description.'" size=40></td></tr>';
echo "<tr><th align=left>Code Number</th><td>";
	echo '<input type="text" name="codes" value= "'.$codes.'"></td></tr>';
$yesno = "y";
if ($delormod == "Modify" && $description && $codes) {
	$sqlactive = "SELECT rlaactive as yesno 
        FROM rlafinance.codeid 
        WHERE description='$description' and codes='$codes' ;";
    $resultactive = mysql_query($sqlactive);
    include("err_msg.inc");
    list($yesno) = mysql_fetch_array($resultactive);
}
$cat[0][0]="ac"; 
$cat[0][1]="Account"; 
$cat[1][0]="sa"; 
$cat[1][1]="Sub Account"; 
$cat[2][0]="cc"; 
$cat[2][1]="Cost Center"; 
echo "<tr><th align=left>Account Type</th><td><select name=category>";
	if (!$category) {$category = "cc";}
	for ($i=0; $i<3; $i++) {
		$sel = "";
		if ($cat[$i][0] == $category) {
			$sel = "selected";
		}
		echo "<option $sel value=\"".$cat[$i][0]."\">".$cat[$i][1];
	}
	echo "</option></select></td></tr>";
	if ($delormod == "Modify") {
		$val = "Modify Code";
	} else {
		$val = "Add New Code";
	}
	echo "<tr><th align=left>In Ues?</th><td><select name=rlaactive>";
	if ($yesno == "y") {
		echo "<option value=\"y\">YES";
		echo "<option value=\"n\">NO";
	} else {
		echo "<option value=\"n\">NO";
		echo "<option value=\"y\">YES";
	}
	echo "</option></select></td></tr>";
echo "<tr><th colspan=2><input type=\"submit\" name=modifyornewcode value=\"$val\"></th></tr>";
echo "</table><p>";
echo "</form>";
echo "<hr>";

################ All GHR code (AC+SAC+CCC) List
#	GHR code
	$sql = "SELECT code_id, description, codes, category, rlaactive
		FROM rlafinance.codeid 
		ORDER BY description;";
		//WHERE rlaactive ='y'
    $result = mysql_query($sql);
    include("err_msg.inc");
    $i = 0;
    $ghrcdinuse=0;
    while (list($code_id, $description, $codes, $category, $rlaactive) = mysql_fetch_array($result)) {
        $ghrcode[$i][0] = $description;
        $ghrcode[$i][1] = $codes;
        $ghrcode[$i][2] = $category;
        $ghrcode[$i][3] = $code_id;
        $ghrcode[$i][4] = $rlaactive;
        if ($rlaactive == "y") {
        	$ghrcdinuse++;
        }
        $ghrcodehash[$codes] = $i;
        $i++;
    }
    $noghrcode = $i;

/*
	$dateentry = date("2001-04-15");
	for ($i=0; $i<$noghrcode; $i++) {
		$description = addslashes($ghrcode[$i][0]);
		$codes = $ghrcode[$i][1];
		$category = $ghrcode[$i][2];
		$code_id = $ghrcode[$i][3];
		$sql = "UPDATE rlafinance.codeid 
        	SET description='$description', 
          		codes='$codes', category='$category', 
          		dateentry='$dateentry' 
        	WHERE code_id='$code_id';";
       echo "$i: ".$sql."<br>";
    	$result = mysql_query($sql);
    	include("err_msg.inc");
    }
//*/

echo "<form name=codelink>";
echo '<input type="hidden" name="frm_str" value= "'.$userstr.'">';
echo "<table border=0>";

echo "<tr><th align=left colspan=2>Total $noghrcode GHR codes are listed in RLA DB,<br>".
	"$ghrcdinuse codes are currently used.<br><br></th></tr>";
echo "<tr><th align=left>Account List</th><td><select name=selghrcodeid>";
	$dateentry = date("2001-04-15");
	for ($i=0; $i<$noghrcode; $i++) {
		$description = $ghrcode[$i][0];
		$codes = $ghrcode[$i][1];
		$category = $ghrcode[$i][2];
		$code_id = $ghrcode[$i][3];

		$tmp =  "$description: ($category-$codes) (".$ghrcode[$i][4].")";
		$val =$code_id."-ghr-".$description."-ghr-".$codes."-ghr-".$category;
		if ($ghrcode[$i][3] == $selghrcodeid) {
			echo "<option selected value=\"$val\">$tmp";
		} else {
			echo "<option value=\"$val\">$tmp";
		}
	}
echo "</option></select></td></tr>";
echo "<tr><th align=left>Action</th><td>";
echo "<select name=delormod>";
	echo "<option>Modify";
	echo "<option>Delete";
echo "</option></select>";
echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"submit\" name=deletecode value=\"Action\">";
echo "</td></tr>";
echo "</table><p>";
echo "</form>";

###################
echo "<hr><br><a href=#top>Back to top</a><br><br>";
?>
</html>
