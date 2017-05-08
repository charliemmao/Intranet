<html>
<body background="rlaemb.JPG" leftmargin="20">
<?php
//js
include('inventory_verify.inc');
include('str_decode_parse.inc');
include("rla_functions.inc");
include("regexp.inc");
include("rla_fin_order.inc");
include("rla_fin_controldata.inc");

include("userinfo.inc"); //$userinfo
$userstr	=	"?".base64_encode($userinfo);

if ($action == "fullvieworder") {
	include("rla_fin_display_order.inc");
	exit;
}

echo "<h2 align=center><a id=top>Research Summary</a>";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[Refresh]</font></a></h2><hr>";
include("connet_root_once.inc");

//#######################################################################
//***********Search Travel Summary Form
	echo "<form name=searchform method=post>";
	echo "<table>";		
	echo "<tr><th align=left>Search for</th>";
	echo "<tr><th align=left>Staff</th><td><select name=staff>";
	echo "<option value=0>All";
	$sql = "SELECT distinct t1.email_name as ename, 
		t2.first_name as fname, t2.last_name as lname
		FROM timesheet.researchrcd as t1, timesheet.employee as t2
		WHERE t2.email_name=t1.email_name
		order by fname";
		
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	if ($no) {
		while (list($ename, $fname, $lname) = mysql_fetch_array($result)) {
			if ($staff == $ename) {
				echo "<option value=$ename selected>$fname $lname";
			} else {
				echo "<option value=$ename>$fname $lname";
			}
		}
	}
	echo "</option></select></td></tr>";

	echo "<tr><th align=left>Period</th>";
		if (!$mthst) {
			$mthst = date("m");
		}
		if (!$yearst) {
			$yearst = date("Y");
		}
		if (!$mthed) {
			$mthed = date("m");
		}
		if (!$yeared) {
			$yeared = date("Y");
		}
		$yearfrom = 2000;
		$yearto = date("Y");
		if (20 < $yearto - $yearfrom) {
			$yearfrom = $yearto - 20;
		}

		echo "<td><b>From&nbsp;</b><select name=mthst>";
		for ($i=1; $i<=12; $i++) {
			if ($i == $mthst) {
				echo "<option value=$i selected>".$mths[$i];
			} else {
				echo "<option value=$i>".$mths[$i];
			}
		}
		echo "</option></select>";
		echo "<select name=yearst>";
		for ($i=$yearfrom; $i<=$yearto ; $i++) {
			if ($i == $yearst) {
				echo "<option value=$i selected>".$i;
			} else {
				echo "<option value=$i>".$i;
			}
		}
		echo "</option></select>";
		
		echo "<b>&nbsp;To&nbsp;</b><select name=mthed>";
		for ($i=1; $i<=12; $i++) {
			if ($i == $mthed) {
				echo "<option value=$i selected>".$mths[$i];
			} else {
				echo "<option value=$i>".$mths[$i];
			}
		}
		echo "</option></select>";
		echo "<select name=yeared>";
		for ($i=$yearfrom; $i<=$yearto ; $i++) {
			if ($i == $yeared) {
				echo "<option value=$i selected>".$i;
			} else {
				echo "<option value=$i>".$i;
			}
		}
		echo "</option></select>";
	echo "</td></tr>";

	echo "<tr><th  colspan=2>&nbsp;</th></tr>";
	echo "<tr><th colspan=2><button type=\"submit\" name=researchsummary><b>Submit</b></button></td></tr>";
	echo "</table><p>";

if ($researchsummary) {
		if (10 > $mthst) {
			$mthst = "0".$mthst;
		}
		if ($mthed == 12) {
			$mthed = 1;
			$yeared = $yeared + 1;
		} else {
			$mthed++;
		}
		if (10 > $mthed ) {
			$mthed = "0".$mthed;
		}
		$dfrom = "$yearst-$mthst-01";
		$dto =	"$yeared-$mthed-01";
		
	echo "<hr><h2>RLA Research Summary For <font color=#0000ff>".
		$companyname[$companyid]."</font></h2>";
	echo "<b>For the period from $dfrom to $dto</b><br><br>";
		
//#######################################################################
//***********List Search Results
	if ($staff) {
		$andstaff = " and t2.email_name='$staff'";
	}
	$sql = "SELECT t1.entry_no, t1.yyyymmdd, t1.email_name as ename, 
		t2.activity,
		t3.minutes
		FROM timesheet.entry_no as t1, timesheet.researchrcd as t2, timesheet.timedata as t3
		WHERE t1.yyyymmdd>='$dfrom' and t1.yyyymmdd<'$dto' 
		and t1.entry_no=t2.entry_no 
		and t1.entry_no=t3.entry_no 
		and t3.brief_code='RLA-NT-New_Research' 		
		$andstaff
		order by t1.yyyymmdd desc";
	//echo "$sql<br>";

	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);

	if ($no) {
		$totalminute = 0;
		echo "<table border=1>";
		echo "<tr><th>Date</th><th>Name</th><th>Time (minute)</th><th>Activity</th></tr>";
		while (list($entry_no, $yyyymmdd, $ename, $activity, $time) = mysql_fetch_array($result)) {
			echo "<tr><td>$yyyymmdd</td>";
			echo "<td>$ename</td>";	
			echo "<td>$time</td>";
			echo "<td>$activity</td>";
			echo "</tr>";
			$totalminute += $time;
		}
		echo "<tr><th align=left colspan=5>Total Minutes: $totalminute.</th></tr>";
		echo "</table><p>";
	} else {
		echo "<h3><font color=#ff0000>No record has been found.</font></h3>";
	}
}
echo "<hr><br><a href=#top>Back to top</a><br><br>";

?>
</html>
