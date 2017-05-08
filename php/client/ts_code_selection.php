<html>

<head>
<title></title>
<style>
	.help {
		position: absolute; posTop: 0; posleft: 0;
		border-width: thin; border-style: solid;
		background-color: yellow; color: black;
	}
</style>
<!--		height=10; width=400;-->

</head>

<body onload="ClearFlyOver();" text="#000000" link="#006600" vlink="#993333" alink="#CC6600" background="rlaemb.JPG" leftmargin="20">
<div id=FOArea class="help" style="dispaly: none"></div>

<?php
//js
include('flyover.inc');
include('change_content_sel.inc');
include("phpdir.inc"); 

include('str_decode_parse.inc');
$fromselcode = "y";
$rlacurrentcode = 1;
include('codearray.inc');
include('ts_codeprefix_list.inc');
include('codearray_private.inc');
//include('calender_weekdays.inc');
include("find_domain.inc");	
	echo '<h1>Please select your private code from the list</h1>';
	include("userinfo.inc");
	if ($priv != "1000") {
		echo "<hr><center><a onClick=\"code_sel_des('clear')\"";
		echo ' onMouseOut="ClearFlyOver();" ';
		echo ' onMouseOver="FlyOver(\'Click me to deselect project codes.\')";';
		echo ">[Clear All Codes]</a>&nbsp;&nbsp;&nbsp;";
		
		echo "<a onClick=\"code_sel_des('select')\"";
		echo ' onMouseOut="ClearFlyOver();" ';
		echo ' onMouseOver="FlyOver(\'Click me to select all project codes.\')";';
		echo ">[Select All Codes]</a></center><hr>";
	} else {
		$qry	=	$userinfo."&deselect=deselect$empty=true";
		$qry	=	"?".base64_encode($qry);
		echo "<hr><center><a href=\"".$PHP_SELF."$qry\">[Clear All Codes]</a>&nbsp;&nbsp;&nbsp;";
		$qry	=	$userinfo."&selectall=selectall$empty=true";
		$qry	=	"?".base64_encode($qry);
		echo "<a href=\"".$PHP_SELF."$qry\">[Select All Codes]</a></center><hr>";
	}

	echo '<form method="POST" action="/'.$phpdir.'/ts_processpricode.php" name="codeSelectionForm" >';
	//echo "no_prefix: $no_prefix; codeno: $codeno.<br>";
	for ($j=0; $j<$no_prefix; $j++) {
		$code_id_list[$j] = "";
		//echo $codeprefix_list[$j][0]."-".$codeprefix_list[$j][1]."<br>";
	}
	
	for ($i=0; $i<$codeno; $i++) {
		$match = 0;
		$code = $codelist[0][$i];
		$code_len = strlen($code);
		//echo "code: $code; code_len: $code_len.<br>";

		for ($j=0; $j<$no_prefix; $j++) {
			$sub_h = $codeprefix_list[$j][1];
			$sub_h_len = $codeprefix_list[$j][0];
			//echo "sub_h: $sub_h; sub_h_len: $sub_h_len.<br>";
			
			if ($code_len >= $sub_h_len) {
				if (substr($code, 0, $sub_h_len) == $sub_h) {
					$match = 1;
					$code_id_list[$j] = $code_id_list[$j].$patsym.$i;
					//$code_id_list[$j] = $code_id_list[$j].$patsym.$code;
					//echo "$sub_h: $code_id_list[$j].<br>";
					break;
				}
			}
		}
		if ($match == 0) {
			$code_id_list[$no_prefix] = $code_id_list[$no_prefix].$patsym.$i;
			//$code_id_list[$no_prefix] = $code_id_list[$no_prefix].$patsym.$code;
			//echo "other: $code_id_list[$no_prefix].<br>";
		}
	}
	$ij = 0;
	for ($j=0; $j<=$no_prefix; $j++) {
		//echo "$j: $code_id_list[$j].<br>";
		$code_id_list[$j] = trim(substr($code_id_list[$j],1,strlen($code_id_list[$j])-1));
		$tmp = explode($patsym,$code_id_list[$j]);
		$tlen = strlen($code_id_list[$j]);
		if ($tlen > 0) {
			$subindex = -1-$j;
			$code_order[$ij] = $subindex;
			//echo "$subindex-$ij<b>".$codeprefix_list[$j][1]."</b><br>";
 			$ij++;
		}
		for ($i=0; $i<count($tmp); $i++) {
			$tmp[$i] = trim($tmp[$i]);
			if ($tmp[$i] != "") {
 				$code_order[$ij] = $tmp[$i];
 				$ij++;
 				//echo "$tmp[$i]<br>";
 			}
 		}
	}
	
	$color	=	"96c9d3";
	//echo $ij."<br>";
	echo '<table border="0" width=100%>';
	for ($kk=0; $kk<$ij; $kk++) {
		$val = $code_order[$kk];
		if ($val<0) {
			$val = abs($val)-1;
			if ($codeprefix_list[$val][1] == "") {
				$codeprefix_list[$val][1] = "Other";
			}
			echo "<tr><td bgcolor=$color><font color=#0000ff><b>"
			."Project code group: ".$codeprefix_list[$val][2]."</b></font></td></tr>";
		} else {
			$i = $val;
			$name = $codelist[0][$i];
			$value = ereg_replace("__", "&", $name);
			$value = ereg_replace("_", " ", $value);
			$popup = addslashes($codelist[3][$i]);
			echo '<tr><td';
			//echo ' onMouseOut="ClearFlyOver();" onMouseOver="FlyOver('."'".$popup."');\"'; //'
			echo ">";
			echo '&nbsp;&nbsp;&nbsp;&nbsp;<input onClick="checkbox_count();" type="checkbox" name="';
			echo $name;
			echo '" value="';
			if ($codelist[4][$i]	==	1) {
				echo $name.'" checked>'; 
			} else {
				echo $name.'">';
			}
			//if ($priv == "00") {
				echo "<a onMouseOver=\"self.status='$popup'; return true\" onMouseOut=\"self.status=''; return true\">$value</a>";
			//} else {
				//echo '&nbsp;'.$value;
			//}
			echo '</td></tr>';
		}
	}
	echo '<tr><td colspan="'.$no_in_col.'">&nbsp;</td>';
	include("userinfo.inc"); //$userinfo
	$qry0	=	$userinfo;
	$frm_str	=	base64_encode($qry0);
	echo '<input type="hidden" value="'.$frm_str.'" name="frm_str"></tr>';
	
	echo '<tr><td><table><tr><td><div ID="obtochange"><b>Number of project codes selected is  ';
	echo '<font color=#ff0000><b>'.$no_select.'</font>.</b></div></td>';

	echo '<th><input onClick="return (onSubmit());"';
	echo ' type="submit" value="Submit" name="privatecode"></th></tr></table></td></tr>';
	echo '</table><p><p></form>';
	$qry	=	$userinfo;
	$qry	=	"?".base64_encode($qry);
	if ($priv == "00" || $priv == "10") {
		$newfile = "/usr/local/apache/htdocs/report/projectlist.csv";
		$fpcsv	=	fopen($newfile,'w+');
		fputs($fpcsv,"Project Code List\n");
		$tmp = date("F j Y");	
		fputs($fpcsv,$tmp."\n\n");
		
		for ($kk=0; $kk<$ij; $kk++) {
			$val = $code_order[$kk];
			if ($val<0) {
				$val = abs($val)-1;
				if ($codeprefix_list[$val][1] == "") {
					$codeprefix_list[$val][1] = "Other";
				}
				$tmp = $codeprefix_list[$val][2];	
				fputs($fpcsv,$tmp."\n");
			} else {
				$i = $val;
				$name = $codelist[0][$i];
				$value = ereg_replace("__", "&", $name);
				$value = ereg_replace("_", " ", $value);
				$popup = addslashes($codelist[3][$i]);
				$tmp = "    $value,\"$popup\"";	
				fputs($fpcsv,$tmp."\n");
			}
		}
		
		fclose($fpcsv);
		echo "<br><b><font size=\"2\">&nbsp;&nbsp;&nbsp;&nbsp;Download or open a [".
		"<a href=\"../report/projectlist.csv\" target=\"_blank\"><b>CSV</b></a>] format file.</font></b><br><br>";
	}

?>
</body>
