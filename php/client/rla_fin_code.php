<html>
<body background="rlaemb.JPG" leftmargin="20">
<?php
//js
include('str_decode_parse.inc');
include("rla_functions.inc");
include("userinfo.inc"); //$userinfo $frm_str
//echo "$mystat<br>";
if ($mystat == "auth" || $mystat == "exec") { // $mystat == "poff"
} else {
	exit;
}
$userstr	=	"?".base64_encode($userinfo."&mystat=$mystat");
//echo '<input type="hidden" name="frm_str" value= "'.$userstr.'">';

echo "<h2 align=center><a id=top>Charging Code Manipulation</a>";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[Refresh]</font></a></h2><hr>";
include("connet_root_once.inc");

if ($code_id) {
	$code_id_from=$code_id;
} 
#################Code Manipulation
if ($codemanipulation ) {
	if ($code_id) {
		// update code
		$sql = "UPDATE rlafinance.codeid SET  
			description='$description', codes='$codes', category='$category', 
			rlaactive='$rlaactive' WHERE code_id='$code_id';";
	} else {
		//add new code
		$sql = "INSERT INTO rlafinance.codeid VALUES('null', '$description', '$codes', '$category', '$rlaactive');";
	}
	//echo $sql."<br>";
	$result = mysql_query($sql);
	include("err_msg.inc");
	if ($code_id) {
		echo "<h2>Code has been updated.</h2>";
	} else {
		echo "<h2>Code has been added.</h2>";
	}
	echo "<hr>";
}
#################	 list current code record
##	account code
$sql = "SELECT code_id, description, codes, rlaactive FROM rlafinance.codeid WHERE category='ac' ORDER BY description;";
$result = mysql_query($sql);
include("err_msg.inc");
$no = mysql_num_rows($result);
echo "<h2>Current Codes List</h2>";
echo "<table border=0>
  <td>";
echo "<form name=ac>";
echo '<input type="hidden" name="frm_str" value= "'.$userstr.'">';
echo "<tr><th align=left>Account Codes (6 digits): $no</th></tr>";
echo "<tr><td><select name=code_ac>";
while (list($code_id, $description, $codes, $rlaactive) = mysql_fetch_array($result)) {
	if ($code_ac == $code_id || $code_id == $code_id_from) {
		echo "<option selected value=\"$code_id\">$description: $codes ($rlaactive)";
	} else {
		echo "<option value=\"$code_id\">$description: $codes ($rlaactive)";
	}
}
echo "</td></tr>";
echo "<tr><td align=center>";
echo "<input type=\"submit\" name=acmodify value=\"Modify\">";
echo "&nbsp;&nbsp;";
//echo "<input type=\"submit\" name=acdelete value=\"Delete\">";
echo "</td></tr>
      <td>";
echo "</form>";

##	subaccount code
$sql = "SELECT code_id, description, codes, rlaactive FROM rlafinance.codeid WHERE category='sa' ORDER BY description;";
$result = mysql_query($sql);
include("err_msg.inc");
$no = mysql_num_rows($result);
echo "<form name=sa>";
echo '<input type="hidden" name="frm_str" value= "'.$userstr.'">';
echo "<tr><th align=left>Subaccount Codes (2 digits): $no</th></tr>";
echo "<tr><td><select name=code_sa>";
while (list($code_id, $description, $codes, $rlaactive) = mysql_fetch_array($result)) {
	if ($code_sa == $code_id || $code_id == $code_id_from) {
		echo "<option selected value=\"$code_id\">$description: $codes ($rlaactive)";
	} else {
		echo "<option value=\"$code_id\">$description: $codes ($rlaactive)";
	}
}
echo "</td></tr>";
echo "<tr><td align=center>";
echo "<input type=\"submit\" name=samodify value=\"Modify\">";
echo "&nbsp;&nbsp;";
//echo "<input type=\"submit\" name=sadelete value=\"Delete\">";
echo "</td></tr>
          <td>";
echo "</form>";

##	cost center code
$sql = "SELECT code_id, description, codes, rlaactive FROM rlafinance.codeid WHERE category='cc' ORDER BY description;";
$result = mysql_query($sql);
include("err_msg.inc");
$no = mysql_num_rows($result);
echo "<form name=cc>";
echo '<input type="hidden" name="frm_str" value= "'.$userstr.'">';
echo "<tr><th align=left>Cost Center Codes (3 digits): $no</th></tr>";
echo "<tr><td><select name=code_cc>";
while (list($code_id, $description, $codes, $rlaactive) = mysql_fetch_array($result)) {
	if ($code_cc == $code_id || $code_id == $code_id_from) {
		echo "<option selected value=\"$code_id\">$description: $codes ($rlaactive)";
	} else {
		echo "<option value=\"$code_id\">$description: $codes ($rlaactive)";
	}
}
echo "</td></tr>";
echo "<tr><td align=center>";
echo "<input type=\"submit\" name=ccmodify value=\"Modify\">";
echo "&nbsp;&nbsp;";
//echo "<input type=\"submit\" name=ccdelete value=\"Delete\">";
echo "</td></tr>";
echo "</form>";
echo "</table><p>";

################Modify code: collect data
if ($acmodify  || $samodify  || $ccmodify) {
	if ($acmodify) {
		$category = "ac";
		$code_id = $code_ac;
	}elseif ($samodify) {
		$category = "sa";
		$code_id = $code_sa;
	}elseif ($ccmodify) {
		$category = "cc";
		$code_id = $code_cc;
	}
	$sql = "SELECT description, codes, rlaactive FROM rlafinance.codeid 
		WHERE category='$category' and code_id='$code_id';";
	//echo $sql."<br>";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($description, $codes, $rlaactive) = mysql_fetch_array($result);
	//echo "$code_id, $description, $codes, $rlaactive<br>";
}

#################	 Create New Code
include("rla_fin_new_mod_code.inc");
###################
echo "<hr><br><a href=#top>Back to top</a><br><br>";
?>
</html>
