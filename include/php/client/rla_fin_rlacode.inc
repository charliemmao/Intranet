<html>
<?php
$sql = "SELECT projcode_id as id, brief_code as code 
	FROM timesheet.projcodes 
	WHERE costcenter='y'
	ORDER BY code";
$result = mysql_query($sql);
include("err_msg.inc");
$norlacharge = mysql_num_rows($result);
//echo "$norlacharge <br>";
$i=0;
while (list($id, $code) = mysql_fetch_array($result)) {
	//echo "$id=$code<br>";
	$code = ereg_replace("__"," ",$code);
	$code = ereg_replace("_"," ",$code);
	$code = ereg_replace("Equipment","Equip",$code);
	$code = ereg_replace("Laboratory","Lab",$code);
	$code = ereg_replace("Maintenance","Maint",$code);
	$code = ereg_replace("Environmental","Environ",$code);
	$code = ereg_replace("Software","SW",$code);
	$code = ereg_replace("Administration","Admin",$code);
	$code = ereg_replace("Instrument","Instr",$code);
	$code = ereg_replace("Materials","Mat",$code);
	$code = ereg_replace("Computer","Comp",$code);
	$code = ereg_replace("Systems","Sys",$code);
	$code = ereg_replace("Technology","Tech",$code);
	$code = ereg_replace("Electronics","Elec",$code);
	$code = ereg_replace("Fixing Fusing","F+F",$code);
	$code = ereg_replace("Communication","Comm",$code);
	$code = ereg_replace("searching","Search",$code);
	$code = ereg_replace("Searching","Search",$code);
	$code = ereg_replace("Review","Rev",$code);
	$code = ereg_replace("Education","Edu",$code);
	$code = ereg_replace("Applications","App",$code);
	$code = ereg_replace("Digital Print","D+P",$code);
	//$code = ereg_replace("Electropress","ElecPrs",$code);
	//$code = ereg_replace("","",$code);	
	$rlacharge[$i][0]=$id;
	$rlacharge[$i][1]=$code;
	$rlacode[$id] = $code;
	$i++;
}
?>
</html>
