<html>
<head>
<title>Compose Timesheet</title>
<style>
	.help {
		position: absolute; posTop: 0; posleft: 0;
		border-width: thin; border-style: solid;
		background-color: yellow; color: black;
	}
</style>
</head>

<body onload="ClearFlyOver(); addtime();" text="#000000" link="#006600" 
	vlink="#993333" alink="#CC6600" background="rlaemb.JPG" leftmargin="20">
<div id=FOArea class="help" style="dispaly: none"></div>

<?php
include("phpdir.inc");
	include('connet_root_once.inc');
$sumtimedb = 0;
include('str_decode_parse.inc');
//tscomp_debug("str_decode_parse.inc", 1);
//tscomp_debug("", 1);
//js
include('flyover.inc');
include('ts_change_content_send.inc');
$rlacurrentcode = 1;	//	list project codes are currently used  only.
include('codearray.inc');
if ($loadtimesheet == "Load Timesheet") {
	include("ts_one_process_id_search.inc");
	//echo  $loadtimesheet."<br>";
}
include('ts_codeprefix_list.inc');
$en = $email_name;
if ($tsfor) {
	$email_name = $tsfor;
}
include('codearray_private.inc');
$email_name = $en;
if ($tsfor) {
	//echo "$tsfor = $email_name";
}
## if no private code has been found, the program will ask for it.
	if ($no_select == 0 ) {
		include("userinfo.inc"); //$userinfo
		$qry0	=	$userinfo;
		$frm_str	=	base64_encode($qry0);
		echo "<script language=\"javascript\">";
			echo "window.location=\"http://".getenv("server_name")."/$phpdir/ts_code_selection.php?$frm_str\";";
			//echo "window.location=\"http://".getenv("server_name")."\"";
		echo "</script>";
		exit;

		echo '<h1>'.$title.' '.$last_name.'</h1>';
		echo 'Please select your private project code first. ';
		echo 'The code selection page will appear when you click "Select Project Code" from left frame.';
	}
	
## 	private codes have been found, a timeshhet composing form will displayed.
##	the form include:		all private codes list as "label" with text object to enter minutes
	echo '<h2 align="center">TIMESHEET PREPARATION FORM</h2>';
	echo '<font size=2><center><font color=#0000ff><b>
		Note</b></font>: The time you spent on the project should be in minutes.<br>';
	echo 'Monday - Thursday are 459 minutes each whereas Friday is 444 minutes.
		<br>Total time is 2280 minutes.</center>';//Public holiday 460 minutes. 
	echo "</font>";
	
	echo '<form method="POST" action="/'.$phpdir.'/ts_send.php" name="TSComposeForm" >';
	$noobject = 0;
	
##	section 1: name and friday date
	echo '<table width=100%>';
	echo '<tr><td><hr fgcolor=#ff0000></td></tr></table>';
	echo '<p><table  border=0>';
	echo '<tr><td colspan=2><b>Prepared for</b></td>';
			$select_list	=	'email_name, title, first_name, last_name';
			$where	=	'date_unemployed="0000-00-00" and email_name!="webmaster" order by first_name';
		
			if (!$tsfor) {
				!$tsfor = $email_name;
			}
			include('search_staff.inc');
			//echo $staffno." staffno<br>";
			echo '<td><select ';
			//echo ' onChange="ForOtherAllowed("heh");"';
			echo ' name="'.$email_name.'" size="1">';
			for ($i=0; $i<$staffno; $i++) {
				$ename	=	$stafflist[0][$i];
				$fname	=	$stafflist[2][$i].' '.$stafflist[3][$i];
				//$fname	=	$stafflist[1][$i].' '.$stafflist[2][$i].' '.$stafflist[3][$i];
				if ($ename == $tsfor) {
					echo '<OPTION SELECTED value="'.$ename.'">'.$fname;
				} else {
					echo '<OPTION value="'.$ename.'">'.$fname;
				}
			}
	echo '</slelct></td></tr>';	
	$noobject++;
	include("connet_root_once.inc");
	$sql = "SELECT description as listlasrmonthtimesheet FROM logging.sysmastertable WHERE item='listLMTS';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	list($listlasrmonthtimesheet) = mysql_fetch_array($result);
	$listlasrmonthtimesheet = strtoupper($listlasrmonthtimesheet);
	echo '<tr>';
		$backweek = 0;
		$sql = "SELECT description as iwantback
        		FROM logging.sysmastertable 
        		WHERE item='iwantback'";
    	$result = mysql_query($sql);
    	include("err_msg.inc");
    	list($iwantback) = mysql_fetch_array($result); 		
		$strmatch = ereg_replace("$email_name", "", $iwantback);
		if ($iwantback <> $strmatch) {
			$sql = "SELECT description as backweek
        		FROM logging.sysmastertable 
        		WHERE item='maxweekback'";
    		$result = mysql_query($sql);
    		include("err_msg.inc");
    		$no = mysql_num_rows($result);
    		list($backweek) = mysql_fetch_array($result); 			
			$backweek = $backweek * 7;
			if (strlen($iwantback)>8) {
				$stdate = substr($iwantback,strlen($iwantback)-8);
				$tddate = date("Ymd");
				if (1<$tddate - $stdate) {
					$backweek = 0;
				}
			}
			//if ($priv == "00") {
				//echo "$iwantback $backweek<br>";
				//echo "$stdate - $tddate = $backweek";
			//}
		}
		$lablestr	=	"Friday Ending on ";
		include('calender_friday.inc');	//calfriday
	echo '</tr>';
	$noobject++;
	
	echo '<tr><td colspan=2>';
	include("userinfo.inc"); //$userinfo
	$qry0	=	$userinfo;
	$frm_str	=	base64_encode($qry0);
	echo '<input type="hidden" value="'.$frm_str.'" name="frm_str"></td></tr>';
	$noobject++;
	
	echo "<tr><th colspan=2 align=left>Type of Action</th><td><select name=\"actiontype\">";
	$choice_list[0] = "New Timesheet";
	$choice_list[1] = "Modify Timesheet";
	$choice_list[2] = "Load Timesheet";
	if ($actiontype == "" || $actiontype == "Load Timesheet") {
		$actiontype = $choice_list[0];
	}
	for ($atype0=0; $atype0<=2; $atype0++) {
		if ($actiontype == $choice_list[$atype0]) {
			echo "<option selected value=\"".$choice_list[$atype0]."\">".$choice_list[$atype0];
		} else {
			echo "<option value=\"".$choice_list[$atype0]."\">".$choice_list[$atype0];
		} 
	}
	echo "</option></select></td>";
	$noobject++;
	
	echo "<td><input ";
	echo 'onClick="return (timesheetload());" ';
	echo " onMouseOut=\"ClearFlyOver();\" 
	onMouseOver=\"FlyOver('Please select Load Timesheet from the list first, then click me.');\"";
	echo "type=\"submit\" name=\"loadtimesheet\" value=\"Load Timesheet\"></td></tr>";
	echo '</table><hr>';
	$noobject++;
	if ($priv == "00") {
		//echo "noobject :".$noobject."<br>";
	}

##	section 2: sort code
	//$pri_codelist[0][$i]. 0: brief_code; 1:special; 2:div15; 3:description
	for ($i=0; $i<$no_select; $i++) {
		$match = 0;
		$code =$pri_codelist[0][$i];
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
		$code_id_list[$j] = trim(substr($code_id_list[$j],1,strlen($code_id_list[$j])-1));
		$tmp = explode($patsym,$code_id_list[$j]);
		$tlen = strlen($code_id_list[$j]);
		if ($tlen > 0) {
			//echo "$j: $code_id_list[$j].<br>";
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
	
	$wid_lable 	=	"30%";
	$wid_text	 	=	"70%";
	$wid_lable	=	$wid_lable/$no_in_col;
	$wid_text	=	$wid_text/$no_in_col;
	$wid	=	$wid_lable	 + $wid_text;

	$i	=	2*$no_in_col;
	$color	=	"96c9d3";
	$bgdcolor ="#d2ebb6";
?>
</html>