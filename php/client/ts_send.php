<html>

<head>
<title>Process Timesheet</title>
</head>

<body background="rlaemb.JPG" leftmargin="20">
<?php
include('str_decode_parse.inc');
$debug = 0;
if ($priv == "00") {
	//echo "loadtimesheet:$loadtimesheet.<br>";
	//echo "sendts:$sendts.<br>";
	//exit;
}
if ($loadtimesheet) {
	include("ts_compose_loadts.inc");
	exit;
}

include("connet_other_once.inc");
$friday = "'$calfriday'";
# find out day series no for Today
	$qry = "select TO_DAYS($friday );";
	include('find_one_val.inc');
	$thisfriday = $out;
	//echo "Friday's day series no is ".$thisfriday.'<br>';
	for ($fi=1; $fi<=5; $fi++) {
		$j	=	$thisfriday + $fi - 5;
		$qry = "select FROM_DAYS($j);";
		include('find_one_val.inc');
		$year =	substr($out,0,4);
		$month =	(int)substr($out,5,2);
		$day =	(int)substr($out,8,2);
		$weekdays[$fi] = $year."-".$month."-".$day;
		//echo $weekdays[$fi].": $fi<br>";	
	}

//echo '<h1>Hi, '.$first_name.'</h1>';
##	section 0: remove all empty variables
$p = 0 ;
$postx_list[$p][0] = "_text";
$postx_list[$p][1] = strlen("_text");
$p++;
$postx_list[$p][0] = "_adate";
$postx_list[$p][1] = strlen("_adate");
$p++;
$postx_list[$p][0] = "_sdate";
$postx_list[$p][1] = strlen("_sdate");
$p++;
$postx_list[$p][0] = "_ldate";
$postx_list[$p][1] = strlen("_ldate");
$p++;
$postx_list[$p][0] = "_tcc";
$postx_list[$p][1] = strlen("_tcc");
$p++;
$postx_list[$p][0] = "_trvl";
$postx_list[$p][1] = strlen("_trvl");
$p++;

##	section 1: remove all empty variables
$sql = "SELECT company_name, country FROM timesheet.company;";
include("connet_other_once.inc");
$result = mysql_query($sql);
$no_company = 0;
if ($result) {
	while (list($company_name, $country) = mysql_fetch_array($result)) {
		$comp_coun[$no_company][0] = $company_name;
		$comp_coun[$no_company][1] = $country;
		$no_company++;
	}
}

$i	=	0;
$Noempty	= -1;
$sumchkmsg = "";
//echo "priv: $priv<br>";
//exit;
$debug = 0;
if ($debug == 1 && $priv=="00") {
	echo "<table border=1>";
}
while (list($key, $val) = each($HTTP_POST_VARS)) {
	$val = trim($val);
	$tmpstr	= $key;
	$len	=	strlen($key);
	if ($len>7) {
		$prefix = substr($key,0,7);
	} else {
		$prefix = "";
	}
	if ($debug == 1 && $priv=="00") {
		echo "$i-$key-$val<br>";
		echo "<tr><td>$i</td><td>$key</td><td>$val</td></tr>";
	}
	if ($val && $prefix != "special" && $key!="confirmation") {
		if ($debug == 1 && $priv=="00") {
			echo "<tr><td>$i</td><td>$key</td><td>$val</td><td>$prefix</td></tr>";
		}
		$Noempty++;
    		//echo "Noempty: $Noempty, $key=$val.<br>";
    		//echo $i.': '.$key.' = "'.$val.'".<br>';
		## Is 15 divisible required?
		if ($len > 4) {
			if (substr($tmpstr,0,3) == 'd15') {
				$tmpstr	= substr($tmpstr,3,$len-3);
				$tsheet[2][$Noempty]	=	'd15';
			}
		}
		## Is special entry required?
		$XX	=	'';
		for ($pi=0; $pi<$p; $pi++) {
			$pxstr = $postx_list[$pi][0];
			$pxlen = $postx_list[$pi][1];
			if ($len > $pxlen) {
				$varpx = substr($key, $len-$pxlen, $pxlen);
				if ($priv=="00") {
					//echo "$tmpstr; pxstr= $pxstr? varpx=$varpx. pxlen: $pxlen.<br>";
				}
				if ($varpx == $pxstr) {
					$XX	=	$varpx;
					$tsheet[3][$Noempty]	=	$XX;	//postfix
					if ($priv=="00") {
						//echo "Ending string: varpx=$varpx. pxstr=$pxstr. len=$pxlen.<br>";
					}
				}
			}
		}
		## Get project code and time.
		$tmpstr = ereg_replace("d15", "", $key);
				if ($XX) {
					$tmpstr = ereg_replace($XX, "", $tmpstr);
				}

		if ($tsheet[0][$Noempty] == "") {
			$tsheet[0][$Noempty]	=	$tmpstr;	//code
		}
		$tsheet[1][$Noempty]	=	$val;		//time
		
		## Get special entry for project code.
		if ($XX == '_text') {
			$tmpstr	=	"special_".$tsheet[0][$Noempty].$XX."area";
			$tsheet[4][$Noempty]	= trim($$tmpstr);	//special entry record
			if ($tsheet[4][$Noempty] == "") {
				$sumchkmsg = $sumchkmsg.$tsheet[0][$Noempty].": empty.<br>";
			}
		}
		## Get date for the project code.
		if ($XX == '_adate') {
			$tmp0 = "";
			$sum0 = 0;
			$sumstr = "";
			for ($iadate=1; $iadate<=5; $iadate++) {
				$tmpstr	=	"special_annual".$iadate;
				if ($$tmpstr >0) {
					$sumstr = $sumstr."+".$$tmpstr;
					$sum0 = $sum0 + $$tmpstr;
					$tmp0	=	$tmp0.$$tmpstr.":".$weekdays[$iadate]." ";
				}
			}
			$tsheet[4][$Noempty]	= trim($tmp0);
			if ($tsheet[1][$Noempty] != $sum0) {
				$sumchkmsg = $sumchkmsg.$tsheet[0][$Noempty].": ".$tsheet[1][$Noempty]." <> ".$sumstr." = $sum0.<br>";
			}
		}
		if ($XX == '_sdate') {
			$tmp0 = "";
			$sum0 = 0;
			$sumstr = "";
			for ($isdate=1; $isdate<=5; $isdate++) {
				$tmpstr	=	"special_sick".$isdate;
				if ($$tmpstr >0) {
					$sumstr = $sumstr."+".$$tmpstr;
					$sum0 = $sum0 + $$tmpstr;
					$tmp0	=	$tmp0.$$tmpstr.":".$weekdays[$isdate]." ";
				}
			}
			$tsheet[4][$Noempty]	= trim($tmp0);
			if ($tsheet[1][$Noempty] != $sum0) {
				$sumchkmsg = $sumchkmsg.$tsheet[0][$Noempty].": ".$tsheet[1][$Noempty]." <> ".$sumstr." = $sum0.<br>";
			}
		}
		if ($XX == '_ldate') {
			$tmp0 = "";
			$sum0 = 0;
			$sumstr = "";
			for ($iadate=1; $iadate<=5; $iadate++) {
				$tmpstr	=	"special_lsl".$iadate;
				if ($$tmpstr >0) {
					$sumstr = $sumstr."+".$$tmpstr;
					$sum0 = $sum0 + $$tmpstr;
					$tmp0	=	$tmp0.$$tmpstr.":".$weekdays[$iadate]." ";
				}
			}
			$tsheet[4][$Noempty]	= trim($tmp0);
			if ($tsheet[1][$Noempty] != $sum0) {
				$sumchkmsg = $sumchkmsg.$tsheet[0][$Noempty].": ".$tsheet[1][$Noempty]." <> ".$sumstr." = $sum0.<br>";
			}
		}
		if ($XX == '_tcc') {
			$tmp0 = "";
			$sum0 = 0;
			$sumstr = "";
			$tmpstr0	=	"special_".$tsheet[0][$Noempty].$XX;
			if ($debug == 1 && $priv=="00") {
				//echo "<tr><td>".$tsheet[0][$Noempty]."</td><td>$tmpstr0</td></tr>";
			}

			for ($itcc=1; $itcc<=$pubtcc; $itcc++) {
				$tmpstr	=	$tmpstr0.$itcc."time";
				if ($debug == 1 && $priv=="00") {
					//echo "<tr><td>".$tsheet[0][$Noempty]."</td><td>$tmpstr</td></tr>";
				}
				if ($$tmpstr >0) {
					$sum0 = $sum0 + $$tmpstr;
					$sumstr = $sumstr."+".$$tmpstr;
					$tmp0	=	$tmp0.$$tmpstr."-";
					$tmpstr	=	$tmpstr0.$itcc."company";
					if ($$tmpstr != "") {
						$tmp0	=	$tmp0.$$tmpstr."-";
						for ($correct=0; $correct<$no_company; $correct++) {
							if ($comp_coun[$correct][0] == $$tmpstr) {
								//$tmp0	=	$tmp0.$comp_coun[$correct][1]." ";
								//chnage on 16/02/2001
								$tmp0	=	$tmp0.$comp_coun[$correct][1]."tt@tt";
								break;
							}
						}
					} else {
						$tmp0	=	$tmp0."NA"."-";
						$tmpstr	=	$tmpstr0.$itcc."country";

						if ($$tmpstr != "") {
							$tmp0	=	$tmp0.$$tmpstr." ";
						} else {
							$tmp0	=	$tmp0."NA"." ";
						}
					}
				}
			}
			$tsheet[4][$Noempty]	= trim($tmp0);
			if ($tsheet[1][$Noempty] != $sum0) {
				$sumchkmsg = $sumchkmsg.$tsheet[0][$Noempty].": ".$tsheet[1][$Noempty]." <> ".$sumstr." = $sum0.<br>";
			}
		}
		
		if ($XX == '_trvl') {
//Travel start
	//add on 08/03/2002
			$abortmsg = "";
			$tmp0 = "";
			$sum0 = 0;
			$sumstr = "";
			$tmpstr0	=	"special_".$tsheet[0][$Noempty].$XX;

			if ($debug == 1 && $priv=="00") {
				echo "<b>".$tsheet[0][$Noempty]." = ".$tsheet[1][$Noempty]." minutes<br>";
				echo "<table border=1>";
				echo "<tr><td>company</td><td>time</td><td>activity</td></tr>";
			}

			for ($itravel=1; $itravel<=$pubtravel; $itravel++) {
				$tmptime	=	$tmpstr0.$itravel."time";
				if ($$tmptime> 0) {
					$sum0 = $sum0 + $$tmptime;
					$sumstr = $sumstr."+".$$tmptime;
					
					$tmpcompany	=	$tmpstr0.$itravel."company";
					$tmpactivity	=	$tmpstr0.$itravel."activity";
					
					$tmp0	=	$tmp0.$$tmptime."-".$$tmpcompany."-".$$tmpactivity."travsep";
					//echo "itravel=$itravel: ".$tmp0."<br><br>";
					if ($debug == 1 && $priv=="00") {
						echo "<tr><td>".$$tmpcompany."</td><td>".$$tmptime."</td><td>".$$tmpactivity."</td></tr>";
					}
					if (!$$tmpcompany && !$$tmpactivity ) {
						$abortmsg = $abortmsg."Travel record $itravel: company and activity missing.<br>";
					}  elseif (!$$tmpactivity) {
						$abortmsg = $abortmsg."Travel record $itravel: activity missing.<br>";
					} elseif (!$$tmpcompany) {
						$abortmsg = $abortmsg."Travel record $itravel: company missing.<br>";
					}
				}
			}
			if ($abortmsg) {
				echo "<h2>$first_name</h2>";
				echo "<font color=#ff0000><b>$abortmsg</b></font><br>";
				//echo "Final: ".$tmp0."<br><br>";
				echo "Process aborted please click BACK from tool bar returning to previous screens.<br><br>";
				echo "System Administrator<br>". date("m-d-Y");
				exit;
			}
			$tsheet[4][$Noempty]	= trim($tmp0);
			if ($tsheet[1][$Noempty] != $sum0) {
				$sumchkmsg = $sumchkmsg.$tsheet[0][$Noempty].": ".$tsheet[1][$Noempty]." <> ".$sumstr." = $sum0.<br>";
			}
//Travel end
		}
	}
   	$i++;
}
if ($debug == 1 && $priv=="00") {
	echo "</table><p>";
	exit;
}

$rcdstart = 4;
$rcdend = $Noempty - 1;
$timesheetfor = $tsheet[0][0];
/*
	no = 0: timesheet for the person
	no = 1: timesheet for the week
	no = 2: frm_str
	no = 3: type of action
	no = $Noempty-1: send button itself
*/
$debug = 0;
if ($debug == 1 && $priv=="00") {
	echo "<table border=1>";
	echo "<tr><th>NO</th><td>code</td><td>time</td><td>d15</td><td>posx</td><td>special</td></tr>";
	for ($i=$rcdstart; $i<$rcdend; $i++) {
		echo "<tr><td>$i</td><td>".$tsheet[0][$i]."</td><td>".$tsheet[1][$i]."</td>
		<td>".$tsheet[2][$i]."</td><td>".$tsheet[3][$i]."</td><td>".$tsheet[4][$i]."</td></tr>";
	}
	echo "</table><p>";
	exit;
}
	//echo "<br>here";
	//exit;

##	section 2: check whether this person can send this time sheet
	$priviledge0	=	'50';
	include('tsforother.inc');
	$tsforother_ename = $out;
	if ($priv == "00") {
		//echo $tsforother_ename." can prepare timesheet for others.<br>";
		//exit;
	}
	//echo 'email name: '.$out.'<br>';
	include('findonestafffromemailname.inc');
	$tsforother_fname = $out;
	if ($tsforother_fname=="") {
		$tsforother_fname = $tsforother_ename;
	}
	//echo 'email name: '.$out.'<br>';
	$out	=	$tsheet[1][0]; //this timesheet is prepared for
	include('findonestafffromemailname.inc');
	$forperson_fname	=	$out;
	if ($priv == "00") {
		//echo "This timesheet is prepared for ".$tsheet[1][0]." (".$forperson_fname.").<br>";
	}

	if ($tsforother_ename != $tsheet[0][0] ) {
		if ($tsheet[0][0] != $tsheet[1][0]) {
			if ($priv != "00") {
			if ($priv != "10") {
			if ($email_name != "rma") {
				echo "<h1>Hi, $first_name</h1>".'<h3><font color=#ff0000>Excuse me, you are not autherised to send timesheet for '.$forperson_fname.'.</font></h3>';
				echo "<font color=#000000><b>The person who has been authorised to send timesheet for others is $tsforother_fname.<br></font>";
				echo 'Please send email to <a href="mailto:'.$tsforother_ename.'@rla.com.au">'.$tsforother_fname.'</a>.</b><br>';
				back();
				exit;
			}
			}
			}
		}
	}
	
##	section 3: check data integrality
//calculate total time on the sheet
$time	=	0;
for ($i=$rcdstart; $i<$rcdend; $i++) {
	$time	=	$time + $tsheet[1][$i];
}
//time divisble by 15
$msgd15 = ''; 
for ($i=$rcdstart; $i<$rcdend; $i++) {
	if ($tsheet[2][$i] != '') {
		$d15 = $tsheet[1][$i]-(int)($tsheet[1][$i]/15)*15;
		if ($d15>0) {
			$msgd15	=	$msgd15.$tsheet[0][$i].': '.$tsheet[1][$i].' minutes<br>';
		}
	}
}
//msg block
if ($time == 0) {
	echo "<h3>The timesheet you sent is invalid because total time on your sheet is ";
	echo "<font color=#ff0000>$time</font> minutes.</h3><br>";
	back();
	exit;
}

if ($msgd15 != '' && $sumchkmsg != "") {
	echo "<h3>Following code(s) require your time be divisible by 15:</h3>";
	echo "<font color=#ff0000>$msgd15</font><br>";
	echo "<h3>Following code(s) require your attention:</h3>";
	echo "<br><font color=#ff0000>$sumchkmsg</font>";
	back();
	exit;
}
if ($msgd15 != '') {
	echo "<h3>Following code(s) require your time be divisible by 15:</h3>";
	echo "<font color=#ff0000>$msgd15</font><br>";
	back();
	exit;
}
if ($sumchkmsg != "") {
	echo "<h3>Following code(s) require your attention:</h3>";
	echo "<br><font color=#ff0000>$sumchkmsg</font>";
	back();
	exit;
}

##	section 4: check whether this person has sent timesheet for this week
	$dbname0	=	"timesheet";
	$tsfor	=	$tsheet[1][0];
	$yyyymmdd	= $tsheet[1][1];
	$entry_no = "";
	$sql = "SELECT entry_no, timestamp FROM timesheet.entry_no WHERE email_name='$tsfor' and yyyymmdd='$yyyymmdd';";
	include("connet_other_once.inc");
	$result = mysql_query($sql);
	include("err_msg.inc");
	if (count($result)) {
		list($entry_no, $timestamp) = mysql_fetch_array($result);
	}
	if ($priv == "00") {
		//echo $sql."<br>";
		//echo "$entry_no<br>";
		//exit;
	}
	if ($entry_no) {
		if ($actiontype != "Modify Timesheet") {
			echo "<h2>Hi, $first_name</h2><h3>Timesheet for the week ending on $yyyymmdd has been sent at $timestamp."
				."</h3><font size=4><ul><li>If you want to modify the timesheet please "
				."change Type of Action to \"Modify Timesheet\"; or</li><br><br><li>If this is a real new timesheet "
				."please change date value.</li></ul></font><br><br><h3>Please click \"Back\" from your "
				."BROWSER back to previous page.</h3>";
			exit;//with process ID of $entry_no.
		} else {
			include("ts_one_process_id_delete.inc");
		}
	}
	/*
	if ($priv == "00") {
		echo $sql."<br>";
		echo "This timesheet is prepared for ".$tsfor." ($forperson_fname).<br>";
		if ($entry_no) {
			echo "It has been sent and id is $entry_no.<br>";
		}
	}
	//*/
##	section 5: enter data to timesheet database table: entry_no
$noerror	=	0;
//	step 1: enter data to table "entry_no" and get an "entry_no" back for
			// enter data to table "timedata" &&
			// enter data to table "leavercd" &&
			// enter data to table "researchrcd"
	$entry_no		=	"NULL";
	$sql	=	"INSERT INTO $dbname0.entry_no	VALUES(";
	$sql	=	$sql.$entry_no.",";	
	$sql	=	$sql."'".$tsfor."',";	
	$sql	=	$sql."'".$logon_name."',";
	$sql	=	$sql."'".getenv("remote_addr")."',";
	$sql	=	$sql."'".$yyyymmdd."',";
	$sql	=	$sql."'".date("Y-m-d H:i:s")."');";
	$result	=	mysql_query($sql,$contid);
	include('err_msg.inc');
	$process_id	 =	mysql_insert_id($contid);
	/*
	if ($priv == "00") {
		echo $sql."<br>";
		echo "New process id is: $process_id.<br>";
		exit;
	}
	//*/
	$action	=	$action.$sql.'<br>';
	
//	step 2: enter data to table "timedata"
include('connet_other_once.inc');	//contid
//echo 'enter data to table "timedata"<br>';
for ($i=$rcdstart; $i<$rcdend; $i++) {
	$sql	=	"INSERT INTO $dbname0.timedata VALUES(";
	$sql	=	$sql."'".$tsheet[0][$i]."',";	
	$sql	=	$sql."'".$tsheet[1][$i]."',";	
	$sql	=	$sql."'".$process_id."');";
	//$k = $i - 1;
	//echo $k.': '.$sql.'<br>';
	//brief_code	minutes	entry_no
	//*
	$result	=	mysql_query($sql,$contid);
	if (!$result) {
		$noerror	=	$noerror + 1;	//fail
	} else {
		$action	=	$action.$sql.'<br>';
	}
	//*/
}
//	step 3: enter data to table "researchrcd"
//echo '<br>enter data to table "researchrcd"<br>';
for ($i=$rcdstart; $i<$rcdend; $i++) {
	if ($tsheet[3][$i] == '_text') {
		$sql	=	"INSERT INTO $dbname0.researchrcd VALUES(";
		$sql	=	$sql."'".$tsheet[1][0]."',";	
		$sql	=	$sql."'".$tsheet[0][$i]."',";	
		//$sql	=	$sql."'".addslashes($tsheet[4][$i])."',";	
		$sql	=	$sql."'".$tsheet[4][$i]."',";	
		$sql	=	$sql."'".$process_id."');";
		//echo $sql.'<br>';
		//*
		$result	=	mysql_query($sql,$contid);
		if (!$result) {
			$noerror	=	$noerror + 1;	//fail
		} else {
			$action	=	$action.$sql.'<br>';
		}
		//*/
		//email_name	brief_code		activity	entry_no
	}
}
//	step 4-1: enter data to table "marketing"
/* table: marketing. columns: email_name, brief_code, time, company_name, country, entry_no, */
for ($i=$rcdstart; $i<$rcdend; $i++) {
	if ($tsheet[3][$i] == "_tcc") {
		$tmpstr0 = $tsheet[4][$i];
		//$tmpstr1 = explode(" ", $tmpstr0);
		//change on 16/02/2001
		$tmpstr1 = explode("tt@tt", $tmpstr0);
		//tt@tt
		$noalve = count($tmpstr1);
		for ($j=0; $j<$noalve; $j++) {
			$timecomcty = explode("-", $tmpstr1[$j]);
			$time0 = $timecomcty[0];
			$company0 = $timecomcty[1];
			$country0 = $timecomcty[2];
			$sql	=	"INSERT INTO $dbname0.marketing VALUES(";
			$sql	=	$sql."'".$tsheet[1][0]."',";	
			$sql	=	$sql."'".$tsheet[0][$i]."',";			
			$sql	=	$sql."'".$time0."',";	
			$sql	=	$sql."'".$company0 ."',";	
			$sql	=	$sql."'".$country0 ."',";	
			$sql	=	$sql."'".$process_id."');";
			//echo $sql.'<br>';
			//*
			if ($time0 >0 ) {
				$result	=	mysql_query($sql,$contid);
				if (!$result) {
					$noerror	=	$noerror + 1;	//fail
				} else {
					$action	=	$action.$sql.'<br>';
				}
			}
		}
		//*/
		//email_name	brief_code		startday	minutes	entry_no
	}
}

//	step 4-2: enter data to table "travel"
/* table: marketing. columns: email_name, brief_code, time, company_name, country, entry_no, */
for ($i=$rcdstart; $i<$rcdend; $i++) {
	if ($tsheet[3][$i] == "_trvl") {
		$tmpstr0 = $tsheet[4][$i];
		//$tmpstr1 = explode(" ", $tmpstr0);
		//change on 08/03/2002
		$tmpstr1 = explode("travsep", $tmpstr0);
		$noalve = count($tmpstr1);
		for ($j=0; $j<$noalve; $j++) {
			$timecomcty = explode("-", $tmpstr1[$j]);
			$time0 = $timecomcty[0];
			$company0 = $timecomcty[1];
			$activity0 = $timecomcty[2];
			$sql	=	"INSERT INTO $dbname0.travel VALUES(";
			$sql	=	$sql."'null',";
			$sql	=	$sql."'".$process_id."',";
			$sql	=	$sql."'".$tsheet[1][0]."',";	
			$sql	=	$sql."'".$company0 ."',";	
			$sql	=	$sql."'".$time0."',";	
			$sql	=	$sql."'".$activity0 ."');";
			if ($time0 >0 ) {
				//echo $sql.'<br>';
				$result	=	mysql_query($sql,$contid);
				if (!$result) {
					$noerror	=	$noerror + 1;	//fail
				} else {
					$action	=	$action.$sql.'<br>';
				}
			}
		}
		//travelid, entry_no, email_name, company, time, activity
	}
}

//	step 5: enter data to table "leavercd"
//echo '<br>enter data to table "leavercd"<br>';
for ($i=$rcdstart; $i<$rcdend; $i++) {
	if ($tsheet[3][$i] == '_adate' || $tsheet[3][$i] == '_sdate' || $tsheet[3][$i] == '_ldate') {
		$tmpstr0 = $tsheet[4][$i];
		$tmpstr1 = explode(" ", $tmpstr0);
		$noalve = count($tmpstr1);
		for ($j=0; $j<$noalve; $j++) {
			$texp = explode(":", $tmpstr1[$j]);
			$time0 = $texp[0];
			$day0 = $texp[1];
			$sql	=	"INSERT INTO $dbname0.leavercd VALUES(";
			$sql	=	$sql."'".$tsheet[1][0]."',";	
			$sql	=	$sql."'".$tsheet[0][$i]."',";	
			$sql	=	$sql."'".$day0."',";	
			$sql	=	$sql."'".$time0."',";	
			$sql	=	$sql."'".$process_id."');";
			//echo $sql.'<br>';
			//*
			$result	=	mysql_query($sql,$contid);
			if (!$result) {
				$noerror	=	$noerror + 1;	//fail
			} else {
				$action	=	$action.$sql.'<br>';
			}
		}
		//*/
		//email_name	brief_code	 startday	minutes	entry_no
	}
}

//	step 6: check result
//echo "number of error is ".$noerror.'<br>';
if ($noerror == 0 ) {
	//successful, dispaly timesheet record with $process_id
	$action	=	$actiontype." $yyyymmdd";
	include('logging.inc');
	$timesum = 0;
	for ($i=$rcdstart; $i<$rcdend; $i++) {
		$j = $i - $rcdstart + 1;
		$timesum = $timesum + $tsheet[1][$i];
	}
	//$forperson_fname
	if ($tsfor == $email_name) {
		$addressto = "Your";
	} else {
		$addressto ="$forperson_fname's";
	}
	$atype = "";
	if ($actiontype == "Modify Timesheet") {
		$atype = "<font color=#ff0000>modified</font> and";
	}
	$feedback_message = "<h1>Hi, $first_name</h1>"
		."<h3><font color=\"#0000ff\">$addressto timesheet ($timesum minutes in total) "
		."for the week ending on $yyyymmdd has been $atype sent successfully.</font></h3>"
		."<br><b>Please wait while your timesheet is queried from database and displayed."
		."<br><br>Please check your timesheet carefully. If you want, you can modify and submit it again.</b>";
	$feedback_message .='<form><button type="submit" onClick="window.close();true;"><b>Close this window</b></button> 
</form>';
	$width = 600;
	$height = 400;
	include("feedback.inc");

############## email confirmation
//if ($priv == "00") {
	include("connet_other_once.inc");
	$tsfor	=	$tsheet[0][0];
	$yyyymmdd	= $tsheet[1][1];
	$sql = "UPDATE timesheet.tsmailconf SET confirmation='$confirmation' WHERE email_name='$tsfor';";
	$result = mysql_query($sql);
	include("err_msg.inc");
	if ($confirmation == "Y") {
		$sql = "select first_name as n0, last_name as n2 from timesheet.employee where email_name='$tsfor';";
		$result = mysql_query($sql);
		include("err_msg.inc");
		list($n0, $n2) = mysql_fetch_array($result);
		//echo "$tsfor: $yyyymmdd<br>";
		$to	 		= $tsfor."@rla.com.au";
		$subject = "Timesheet Receipt: $yyyymmdd ($tsfor)";

		$header	=	"From: admin@rla.com.au\nReply-To: admin@rla.com.au\n";
		$message = "Dear $n0\n\n";
		$message =	 $message ."This message is to confirm that ".
			"your timesheet for the week ending on $yyyymmdd has been received by the system.";
		$message =	 $message."\n\nRegards\n\n\nIntranet Timesheet Server\n\n".date("d/m/Y H:i");
		//mail ($to, $subject, $message, $header);
		
		$from = "admin\@rla.com.au";
		$to	 = $tsfor."\@rla.com.au";
		$cc = "";
		$msg = $message;
		system("./_mail.pl \"$from\" \"$to\" \"$cc\" \"$subject\" \"$msg\"",$var);
		//$var return value if $var=0 sucessful, $var<>0 failed

		include("connet_root_once.inc");
		$timestamp = date("Y-m-d H:i:s");
		$sql = "INSERT INTO timesheet.TSreceipt VALUES('$tsfor', '$yyyymmdd', '$timestamp');";
		$result = mysql_query($sql);
		include("err_msg.inc");
	}
//}

	$loadtimesheet = "Load Timesheet";
	include('ts_compose_loadts.inc');
} else {
	//failed, remove timesheet record with $process_id if any record entered
	if ($process_id >0) {
		
		$delete =	'yes';
		include('for_ts_process_id_delete_rcd.inc');
		echo '<br><h2><font colot=#ff0000>Due to server problem,<br>';
		echo 'your timesheet have not been sent. Please try it late.</h2>';
		echo '</font>';
	}
}
function back() {
	echo "<br><font color=#000000>Click</font> <b><font color=#0000ff>\"back\"</font></b> from your Browser's tool bar returning to the previous page.<br><br>";
}
?>
</body>
