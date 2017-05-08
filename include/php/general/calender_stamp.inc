<html>
<?php

	include('connet_root.inc');
	$str	=	"'".date('Y-m-d')."'";	
	# find day of the week for today
	$qry = "select DAYOFWEEK($str);";
	include('find_one_val.inc');
# find out days to Friday date
    if ($out < 6){
    	$doffset	=	6-$out;
    	$temp	= $doffset.' day(s) to Friday.<br>';
    } elseif ($out == 6){
    	$doffset	=	0;
    	$temp	= 'Today is Friday.<br>';
    } elseif ($out == 7){
    	$doffset	=	$out -7;
    	$temp	= 'Today is Saturday.<br>';
	}
    //echo $temp.'<br>';
    
# find out day series no for Today
	$qry = "select TO_DAYS($str);";
	include('find_one_val.inc');
	//echo "Today's day series no is ".$out.'<br>';

# find out Friday date
   	$j	=	$out + $doffset;
	$qry = "select FROM_DAYS($j);";
	include('find_one_val.inc');
	//echo "Friday ending on ".$out.'<br>';	
	
# find out day series no for Friday 
	$str	=	"'".$out."'";	
	$qry = "select TO_DAYS($str);";
	include('find_one_val.inc');
	//echo "Friday's series no is ".$out.'<br>';	
	
	$daystart	=	$out - 26*7;
	$dayend	=	$out + 27*7;
	$thisFriday	=	$out;
	
# make Friday list
	echo '<tr>';
		echo '<td colspan="2"><b>Friday on</b></td>';
		//friday list
		echo '<td colspan="2"><select name="calstamp" size="1">';
			for ($js=$daystart; $js<$dayend; $js=$js+7) {
				$qry = "select FROM_DAYS($js);";
				include('find_one_val.inc');
				$out	=	ereg_replace("-","",$out);
				if ($js==$thisFriday) {
					echo '<OPTION SELECTED>'.$out;
				} else {
					echo '<OPTION>'.$out;
				}
			}
			echo '</SELECT>&nbsp;';
		echo '</td>';
	echo '</tr>';
?>
</html>
