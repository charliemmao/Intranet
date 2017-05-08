<html>

<head>
<title></title>
</head>

<?php
	include("connet_root_once.inc");
	mysql_select_db("timesheet",$contid);
	if ($process_id == '') {
		$caseX	=	'2'; //check whether ts just been already sent
		$sql = "select * from entry_no where email_name='".$tsfor."' and yyyymmdd='".$yyyymmdd."' LIMIT 1;";
	} else {
		$sql = "select * from entry_no where entry_no='".$process_id."';";
	}
	if ($delete != '') {
		$caseX	=	'1'; //collect info for ts deletion
	}	
	if ($successful	==	1) {
		$caseX	=	'3'; //new ts just been sent
	}
	//echo $sql.'<br>';
	//echo 'Case No:'.$caseX.'<br>';
	//exit;
	$result = mysql_query($sql,$contid); 
	include('err_msg.inc');
	$out	=	'';
	$k	=	0;
	while ($myrow = mysql_fetch_array($result)) {
		$i=1;
	  	while (list($key,$val) = each($myrow)) {
	  		$i +=1;
	  		$j = 2*(int)($i/2);
			if ($i == $j) {
  				//echo $k.' '.$val.'<br>';
  				$out_list[$k] = $val;
  				$k++;
  			}
    	}
    }
    $process_id	=	$out_list[0];
    if ($yyyymmdd		==	'') {
    	$yyyymmdd	= $out_list[4];
    }
    if ($out_list[0] != '') {
    	$out	=	"<p><table border=0>";
    	$out	=	$out."<tr><td><b>Process ID:</b></td><td>".$out_list[0].'</td></tr>';
    	$out	=	$out."<tr><td><b>Sender's Email Name:</b></td><td>".$out_list[1].'</td></tr>';
    	$out	=	$out."<tr><td><b>Sender's Logon Name:</b></td><td>".$out_list[2].'</td></tr>';
    	$out	=	$out."<tr><td><b>From PC with IP Address:</b></td><td>".$out_list[3].'</td></tr>';
    	$out	=	$out."<tr><td><b>At time:</b></td><td>".$out_list[5].'</td></tr>';
		$tsid_todelete	=	$out_list[0];	
		$tsename_todelete=	$out_list[1];
		if ($successful	==	1) {
			$out	=	"<p><table border=0>";
		}
    	// query the timesheet data for this process ID
    	//$process_id	=	$out_list[0];
    	include('for_ts_process_id_find_rcd.inc');
    }
	if ($out != "") {
		/*	$caseX	=	'1'; //collect info for ts deletion
			$caseX	=	'2'; //ts has been already sent
			$caseX	=	'3'; //new ts just been sent
		*/
    	if ($caseX		==	'1') {
			$out	=	"<b><b><font color=#ff0000>".strtoupper($out_list[2])."'s timesheet for the week ending on ".$yyyymmdd.
				' has been deleted. The details of that timesheet are:<b></font>'.$out;
			$out	=	$out.'<tr><td colspan=2>';
			$out	=	$out.'</td></tr>';
    		$out	=	$out.'</table></p>';
		} elseif ($caseX		==	'2') {
			echo	"<b><font color=#ff0000>Your can't send timesheet for the week ending on ".
					"$yyyymmdd again, because it has been sent.</font><br><br>";
			$out	=	"<hr>The sending details are:<b>$out";
			$out	=	$out.'<tr><td colspan=2>';
			$out	=	$out.'</td></tr>';
    		$out1	=	$out.'</table></p><br><br>';
			echo "<br><h2>Here are two choices for you:</h2>";
			echo '<form method="POST" action= "'.$PHP_SELF.'">';
			echo "<ul>";
			echo "<font color=#000000><li>If you select a wrong date, click</font> <b>"
				."<font color=#0000ff>\"back\"</font></b> from your Browser's tool bar returning to the previous page,"
				." change the date, then send it again.</li>";
			
			//or send email to the person who can delete timesheet record and enter timesheet for the person			
			$i	=	$Noempty - 2;
			$frm_str	=	'&tsid_todelete='.$tsid_todelete.'&tsename_todelete='.$tsename_todelete;
			$frm_str =		$frm_str.'&Noempty='.$i;
			$frm_str =		$frm_str.'&email_name='.$email_name.'&logon_name='.$logon_name;
			$frm_str =		$frm_str.'&userpwd='.$userpwd.'&title='.$title.'&last_name='.$last_name;
			$frm_str =		$frm_str.'&process_id='.$process_id;
			for ($i=0; $i<$Noempty-2; $i++) {
				for ($j=0; $j<=4; $j++) {
					$frm_str =	$frm_str.'&tsheet['.$j.']['.$i.']='.$tsheet[$j][$i];
				}
   				//echo $i.': '.$tsheet[0][$i].'; '.$tsheet[1][$i].'; '.$tsheet[2][$i].'; '
   		 		//.$tsheet[3][$i].'; '.$tsheet[4][$i].'<br>';
			}
    		echo '<input type="hidden" value="'.$frm_str.'" name="frm_str">';
			$priviledge0	=	'10';
			include('tsforother.inc');
			$briefname = $out;
			include('findonestafffromemailname.inc');
			$fullname = $out;
			echo "<li>If you really want to change your timesheet, however you can't do it for database security reason."
				." Please click the button "
    			.'<input type="submit" value="GO" name="updatets">'
				." to tell $fullname. $fullname will update your timesheet.</li>";
			echo '</ul>';
			echo '</form>';
			echo '<br>';
			echo $out1;
			//echo "ID to delete: ".$tsid_todelete.'<br>';	
			//echo "Email name is: ".$tsename_todelete."<br>";
			//echo $frm_str;
			exit;
		} elseif ($caseX		==	'3') {		
			$out	=	"<b><font color=#0000ff>Your timesheet for the week ending on ".
					"$yyyymmdd has been sent successfully.</font><br><br>$out";
			$out	=	$out."The details of your timesheet are:<b>";
			$out	=	$out.'<tr><td colspan=2>';
			$out	=	$out.'</td></tr>';
    		$out	=	$out.'</table></p><br><br>';
			echo $out;
		}
	}
	//entry_no		email_name		logon_name		computer_ip_addr	yyyymmdd	timestamp
	//mysql_close();
?>
</html>
