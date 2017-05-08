<html>
<?php
include('connet_root.inc');  
$friday = "'2000-06-30'";
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
		$weekdays[$j] = $year."-".$month."-".$day;
		//echo $weekdays[$j].'<br>';	
	}
?>
</html>
