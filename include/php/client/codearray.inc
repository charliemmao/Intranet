<html>
<?php
	include('connet_root_once.inc');
	$itemlist[]	=	'brief_code';
	$itemlist[]	=	'special';
	$itemlist[]	=	'div15';
	$itemlist[]	=	'description';
	$itemlist[]	=	'projcode_id';
	$awidth[]	=	'10%';
	$awidth[]	=	'10%';
	$awidth[]	=	'10%';
	$awidth[]	=	'70%';
	$list	=	$itemlist[0];
	for ($ilist=1; $ilist<count($itemlist); $ilist++) {
		$list	=	$list.','.$itemlist[$ilist];
	}
	if (!$rlaohdtil) {
		$sql	=	"SELECT $list from timesheet.projcodes 
			ORDER BY projcode_id;"; 
	} else {
		$sql	=	"SELECT $list from timesheet.projcodes 
			WHERE brief_code!='$rlaohdtil' 
			ORDER BY projcode_id;"; 
	}
	
	if ($rlacurrentcode) {
		if ($todate) {
			$t = $todate;
		} else {
			$t=date("Y-m-d");
		}
		
		if (!$rlaohdtil) {
			$sql	=	"SELECT $list from timesheet.projcodes 
				WHERE end_date='0000-00-00' or (begin_date<='$t' and end_date>='$t') 
				ORDER BY projcode_id;"; 
		} else {
			$sql	=	"SELECT $list from timesheet.projcodes 
				WHERE end_date='0000-00-00' or (begin_date<='$t' and end_date>='$t') and
					brief_code!='$rlaohdtil' 
				ORDER BY projcode_id;"; 
		}
	}
	
	if ($priv == "00") {
		//echo $sql.'<br>';
		//exit;
	}
	$result	=	mysql_query($sql,$contid);
	include('err_msg.inc');
	if ($result) {
		$codeno	=	0;
		while ($myrow = mysql_fetch_array($result)) {
			$i=1;
			$s1	=	0;
			while (list($key,$val) = each($myrow)) {
				$i +=1;
				$j = 2*(int)($i/2);
				if ($i != $j) {
                  $codelist[$s1][$codeno]	=	$val;
                  $s1++;
				}
			}	
			$codelist[4][$codeno]	=	"";
			$codeno++;
		}
	}			
	mysql_close($contid);
	//0: brief_code; 1:special; 2:div15; 3:description
	/*
	for ($i=0; $i<$codeno; $i++) {
		echo "'".$codelist[0][$i]."'<br>";
	}
	//*/
?>
</html>
