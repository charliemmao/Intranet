<html>

<?php
	include("connet_root_once.inc");
	$sql = "select * from $dbname0.entry_no where entry_no='".$process_id."';";
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
    	$out	=	"<table border=0>";
    	if ($dbname0 == "timesheet") {
    		$out	=	$out."<tr><th colspan=2><b>Current Data</b></th></tr>";
    	} elseif ($dbname0 == "updatets") {			
    		$out	=	$out."<tr><th colspan=2><b>New Data</b></th></tr>";
    	}			
    	$out	=	$out."<tr><td><b>Process ID:</b></td><td>".$out_list[0].'</td></tr>';
    	$out	=	$out."<tr><td><b>Sender's Email Name:</b></td><td>".$out_list[1].'</td></tr>';
    	$out	=	$out."<tr><td><b>Sender's Logon Name:</b></td><td>".$out_list[2].'</td></tr>';
    	//$out	=	$out."<tr><td><b>From PC with IP Address:</b></td><td>".$out_list[3].'</td></tr>';
    	//$out	=	$out."<tr><td><b>At time:</b></td><td>".$out_list[5].'</td></tr>';
    	include('for_ts_process_id_find_rcd.inc');
    }
	if ($out != "") {
    	if ($dbname0 == "timesheet") {
    		$out_old = $out.'</table>';
    	} elseif ($dbname0 == "updatets") {			
    		$out_new = $out.'</table>';
    	}			
	}
	//entry_no		email_name		logon_name		computer_ip_addr	yyyymmdd	timestamp
	//mysql_close();
?>
</html>
