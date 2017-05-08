<html>
<?php
if ($year == '') {
	$year =	date('Y');
	$month =	(int)(date('m'));
	$day =	(int)(date('d'));
}
	$month1	= $month - $moffset;
	if ( $month1 <= 0 ) {
		$year	=	$year - 1;
		$month = 12-$month1;
	} else {
		$month = $month1;
	}
	if ($calfromyear	== "") {
		$calfromyear	=	"calfromyear";
	}
	if ($calfrommonth	 == "") {
		$calfrommonth	=	"calfrommonth";
	}
	if ($calfromday	== "") {
		$calfromday	=	"calfromday";
	}
	if ($calfrommonth ==	"frommonth" && $month == (int)(date('m'))){
		if ($month	 == 1) {
			$year = $year -1;
			$month = 12;
		} else {
			$month = $month -1;
		}
	}
		//Month list
		if ($incjava == 1) {
			echo $fontl."Month&nbsp;$fontr<select onChange=\"timerangecheck();\" name=\"$calfrommonth\" size=\"1\">";
		} else {
			echo $fontl."Month&nbsp;$fontr".'<select name="'.$calfrommonth.'" size="1">';
		}
			for ($ical=1;$ical<13;$ical++) {
				if ($ical<10) {
					$str =	'0'.$ical;
				} else {
					$str =	$ical;
				}
				if ($ical==$month) {
					echo '<OPTION SELECTED>'.$str;
				} else {
					echo '<OPTION>'.$str;
				}
			}
		echo '</SELECT>';
		
		//day list
		if ($incjava == 1) {
			echo $fontl."Day&nbsp;$fontr"."<select onChange=\"timerangecheck();\" name=\"$calfromday\" size=\"1\">";
		} else {
			echo $fontl."Day&nbsp;$fontr".'<select name="'.$calfromday.'" size="1">';
		}
			for ($ical=1;$ical<=31;$ical++) {
				if ($ical<10) {
					$str =	'0'.$ical;
				} else {
					$str =	$ical;
				}
				if ($ical==$day) {
					echo '<OPTION SELECTED>'.$str;
				} else {
					echo '<OPTION>'.$str;
				}
			}
		echo '</SELECT>';

		//year list
		$ye = date("Y");
		if ($curyr - 20>0) {
			$ys = $ye -20;
		} else {
			$ys = 2000;
			$ye = 2020;
		}
		if ($incjava == 1) {
			echo $fontl."Year&nbsp;$fontr"."<select onChange=\"timerangecheck();\" name=\"$calfromyear\" size=\"1\">";
		} else {
			echo $fontl."Year&nbsp;$fontr".'<select name="'.$calfromyear.'" size="1">';
		}
		for ($ical=$ys;$ical<=$ye;$ical++) {
			if ($ical==$year) {
				echo '<OPTION SELECTED>'.$ical;
			} else {
				echo '<OPTION>'.$ical;
			}
		}
		echo '</SELECT>';
//calfromyear	calfromday calfrommonth
?>
</html>
