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

echo "<h2 align=center><a id=top>Link RLA Code To GHR Cost Center: For Order System</a>";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[Refresh]</font></a></h2><hr>";
include("connet_root_once.inc");

################ Form to Link RLA code to GHR code
#			get all codes
#	RLA project code
	$sql = "SELECT projcode_id as id, brief_code, costcenter 
        FROM timesheet.projcodes
        WHERE costcenter='y'
        ORDER BY brief_code;";
    $result = mysql_query($sql);
    include("err_msg.inc");
    $i = 0;
    while (list($id, $brief_code, $costcenter) = mysql_fetch_array($result)) {
        $rlacode[$i][0] = $brief_code;
        $rlacode[$i][1] = $id;
        $rlacodehash[$id] = $i;
        $i++;
    }
    $norla = $i;

#	GHR AC code
	$sql = "SELECT description, codes, rlaactive 
		FROM rlafinance.codeid 
		WHERE category='ac' 
		ORDER BY description;";
    $result = mysql_query($sql);
    include("err_msg.inc");
    $i = 0;
    while (list($description, $codes, $rlaactive) = mysql_fetch_array($result)) {
        $ghrac[$i][0] = $description;
        $ghrac[$i][1] = $codes;
        $ghrachash[$codes] = $i;   
        $i++;
    }
    $noghrac = $i;
 
#	GHR SubAC code
	$sql = "SELECT description, codes, rlaactive 
		FROM rlafinance.codeid 
		WHERE category='sa' 
		ORDER BY description;";
		//ORDER BY description
    $result = mysql_query($sql);
    include("err_msg.inc");
    $i = 0;
    while (list($description, $codes, $rlaactive) = mysql_fetch_array($result)) {
        $ghrsubac[$i][0] = $description;
        $ghrsubac[$i][1] = $codes;
        $ghrsubachash["$codes"] = $i;        
        $i++;
    }
    $noghrsubac = $i;

#	GHR Cost Center code
	$sql = "SELECT description, codes, rlaactive 
		FROM rlafinance.codeid 
		WHERE category='cc' 
		ORDER BY description;";
    $result = mysql_query($sql);
    include("err_msg.inc");
    $i = 0;
    while (list($description, $codes, $rlaactive) = mysql_fetch_array($result)) {
        $ghrccc[$i][0] = $description;
        $ghrccc[$i][1] = $codes;
        $ghrccchash["$codes"] = $i;        
        $i++;
    }
    $noghrcc = $i;
    
#################Process
if ($linkcode) {
	echo "<br><br>";
	$sql = "SELECT projcode_id as id, subac as sac, ccc as cc
        FROM rlafinance.rlapjcvsghrsccc 
        WHERE projcode_id='$projcode_id'";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($id, $sac, $cc) = mysql_fetch_array($result);
	if ($id && $modify == "No") {
		echo "<b>RLA code ".$rlacode[$id][0]." has been linked to GHR code. ".
			"If you want to modify this link, Please select modify and submit again.</b>";
	} elseif ($id && $modify == "Yes") {
		$sql = "UPDATE rlafinance.rlapjcvsghrsccc 
        	SET subac='$subac', ccc='$ccc' 
        	WHERE projcode_id='$projcode_id';";
		$result = mysql_query($sql);
		include("err_msg.inc");
		if ($priv == "00") {
			echo "$sql<br><br>";
		}
		echo "<b>RLA project code ".$rlacode[$rlacodehash[$projcode_id]][0]." has been linked to<br>";
		if ($subac && $ccc) {
			$i = $ghrsubachash[$subac];
			echo "GHR sub account ".$ghrsubac[$i][0]."-".$ghrsubac[$i][1]." and <br>";
			$i = $ghrccchash[$ccc];
			echo "GHR cost center ".$ghrccc[$i][0]."-".$ghrccc[$i][1].".</b><br>";
		}elseif ($subac) {
			$i = $ghrsubachash[$subac];
			echo "GHR sub account ".$ghrsubac[$i][0]."-".$ghrsubac[$i][1]." and <br>";
		}elseif ($ccc) {
			$i = $ghrccchash[$ccc];
			echo "GHR cost center ".$ghrccc[$i][0]."-".$ghrccc[$i][1].".</b><br>";
		}
		echo "<br><b>Modification Successful.</b><br>";
	} else {
		$sql = "INSERT INTO rlafinance.rlapjcvsghrsccc
        	VALUES('$projcode_id', '$subac', '$ccc');";
		$result = mysql_query($sql);
		include("err_msg.inc");
		echo "<b>RLA project code ".$rlacode[$rlacodehash[$projcode_id]][0]." has been linked to<br>";
		if ($subac && $ccc) {
			$i = $ghrsubachash[$subac];
			echo "GHR sub account ".$ghrsubac[$i][0]."-".$ghrsubac[$i][1]." and <br>";
			$i = $ghrccchash[$ccc];
			echo "GHR cost center ".$ghrccc[$i][0]."-".$ghrccc[$i][1].".</b><br>";
		}elseif ($subac) {
			$i = $ghrsubachash[$subac];
			echo "GHR sub account ".$ghrsubac[$i][0]."-".$ghrsubac[$i][1]." and <br>";
		}elseif ($ccc) {
			$i = $ghrccchash[$ccc];
			echo "GHR cost center ".$ghrccc[$i][0]."-".$ghrccc[$i][1].".</b><br>";
		}
		echo "<br><b>New link has been established.</b><br>";
	}
	echo "<br><br>";
	echo "<hr>";
}

echo "<form name=codelink>";
echo '<input type="hidden" name="frm_str" value= "'.$userstr.'">';
echo "<table border=0>";
#	List RLA project code
echo "<tr><th align=left>RLA Project Code</th><td><select name=projcode_id>";
	if (!$projcode_id) {
		$projcode_id = 0;
	}
	for ($i=0; $i<$norla; $i++) {
		if ($rlacode[$i][1] == $projcode_id ) {
			echo "<option selected value=\"".$rlacode[$i][1]."\">".$rlacode[$i][0].": ".$rlacode[$i][1];
		} else {
			echo "<option value=\"".$rlacode[$i][1]."\">".$rlacode[$i][0].": ".$rlacode[$i][1];
		}
	}
echo "</option></select></td></tr>";

/*
##	List GHR account code
echo "<tr><th align=left>GHR account code</th><td><select name=ghrac>";
	echo "<option value=\"\">";
	for ($i=0; $i<$norla; $i++) {
		if ($ghrac[$i][1] == $ghrac ) {
			echo "<option selected value=\"".$ghrac[$i][1]."\">".$ghrac[$i][0].": ".$ghrac[$i][1];
		} else {
			echo "<option value=\"".$ghrac[$i][1]."\">".$ghrac[$i][0].": ".$ghrac[$i][1];
		}
	}
echo "</option></select></td></tr>";
//*/

##	List GHR sub account code
echo "<tr><th align=left>GHR Sub Account</th><td><select name=subac>";
	echo "<option value=\"\">";
	for ($i=0; $i<$noghrsubac; $i++) {
		if ($ghrsubac[$i][1] == $subac) {
			echo "<option selected value=\"".$ghrsubac[$i][1]."\">".$ghrsubac[$i][0].": ".$ghrsubac[$i][1];
		} else {
			echo "<option value=\"".$ghrsubac[$i][1]."\">".$ghrsubac[$i][0].": ".$ghrsubac[$i][1];
		}
	}
echo "</option></select></td></tr>";

##	List GHR Cost center code
echo "<tr><th align=left>GHR Cost Center</th><td><select name=ccc>";
	echo "<option value=\"\">";
	for ($i=0; $i<$noghrsubac; $i++) {
		if ($ghrccc[$i][1] == $ccc) {
			echo "<option selected value=\"".$ghrccc[$i][1]."\">".$ghrccc[$i][0].": ".$ghrccc[$i][1];
		} else {
			echo "<option value=\"".$ghrccc[$i][1]."\">".$ghrccc[$i][0].": ".$ghrccc[$i][1];
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
echo "</option></select></td></tr>";

echo "<tr><td colspan=2 align=center>";
echo "<input type=\"submit\" name=linkcode value=\"Link Code\">";
echo "</td></tr>";
echo "</table><p>";
echo "</form>";

###################
echo "<hr><br><a href=#top>Back to top</a><br><br>";
?>
</html>
