<html>
<body background="rlaemb.JPG" leftmargin="20">
<?php
//js
include('str_decode_parse.inc');
include("rla_functions.inc");

include("userinfo.inc"); //$userinfo
$userstr	=	"?".base64_encode($userinfo);

echo "<h2 align=center><a id=top>Search Order by Project Code</a>";
echo "<a href=\"".$PHP_SELF."$userstr\"><font size=2>[Refresh]</font></a></h2>";
include("connet_other_once.inc");
				
//#######################################################################
//***********Search Order Form
	$sql = "SELECT projcode_id as pid, brief_code as bc
       FROM timesheet.projcodes 
       WHERE brief_code!='RLA-OHD-Time_in_Lieu'
       ORDER BY brief_code;";
    $result = mysql_query($sql);
    include("err_msg.inc");
    $noproj = 0; 
    while (list($pid, $bc) = mysql_fetch_array($result)) {
    	if ($bc) {
    		$projcodelist[$noproj][0] = $pid;
    		$projcodelist[$noproj][1] = $bc;
    		$projcodelist[$pid][2] = $bc;
    		$noproj++;
       }
   	}
	$proj = 1;
	$btncaption = "Search";
	include("ts_proj_budget_report_anyperiod.inc");
	
if ($caseany) {	
	$mthstart = formatmonth($mthstart);
	$mthend = formatmonth($mthend);
	$datefrom = "$yearstart-$mthstart-01";
	$nodays = finddaysinFeb($mthend, $yearend);
	$dateto = "$yearend-$mthend-$nodays";
	
	//remove unselected projects
	if ($projectlist[0] != "all") {
   		for ($i=0; $i<$noproj; $i++) {
   			$selected = "";
   			for ($j=0; $j<$noprojsel; $j++) {
   				if ($projectlist[$j] == $projcodelist[$i][0]) {
   					//echo $projectlist[$j].": ".$projcodelist[$projectlist[$j]][2]."<br>";
					$selected = 1;
 					break;
    			}
        	}
      		if (!$selected) {
      			$projcodelist[$i][0] = 0;
      		}
      	}
   	}
	
	if ($timeframe == "y") {
		echo reportline1("Order search for period from $datefrom to $dateto.");
	} else {
		echo reportline1("Order search result for all time.");
	}
	
	$totalorder = 0;
	$totalrec = 0;
	echo "<table border=1>";
	echo "<tr><th>Project</th><th>Ordered</th><th>Received</th><th>Not Received</tr>";
	for ($i=0; $i<$noproj; $i++) {
		$rlaprojid = $projcodelist[$i][0];
		$brief_code = $projcodelist[$i][1];
		if ($rlaprojid) {
			//echo "$rlaprojid $brief_code<br>";
			$sql = "SELECT sum(t1.unit*t1.unit_price) as totcostreceived 
        		FROM rlafinance.orderdetails as t1, rlafinance.orderid as t2
        		WHERE t1.rlaprojid='$rlaprojid'
        				and t1.order_id=t2.order_id and t2.ordercancelled='n' 
        				and t2.updatestatus='f'";
			if ($timeframe == "y") {	//search for all time
        		$sql .= "and t2.orderdate>='$datefrom' and t2.orderdate<='$dateto'";//$datefrom to $dateto
        	}
    		$result = mysql_query($sql);
    		include("err_msg.inc");
    		list($totcostreceived) = mysql_fetch_array($result);

			$sql = "SELECT sum(t1.unit*t1.unit_price) as totcostordered 
        			FROM rlafinance.orderdetails as t1, rlafinance.orderid as t2
        			WHERE t1.rlaprojid='$rlaprojid'
        				and t1.order_id=t2.order_id and t2.ordercancelled='n'";
			if ($timeframe == "y") {	//search for all time
        		$sql .= "and t2.orderdate>='$datefrom' and t2.orderdate<='$dateto'";//$datefrom to $dateto
        	}
    		$result = mysql_query($sql);
    		include("err_msg.inc");
    		list($totcostordered) = mysql_fetch_array($result);
    		
    		$brief_code = ereg_replace("__"," ",$brief_code);
    		$brief_code = ereg_replace("_"," ",$brief_code);
    		$list = 0;
    		$nreceived = $totcostordered- $totcostreceived;
			$totalorder += $totcostordered;
			$totalrec += $totcostreceived;
    		if ($nreceived) {
    			$nreceived = "<font color=#ff0000>".number_format($nreceived,2)."</font>";
    		} else {
    			$nreceived = "&nbsp;";
    		}	
    		if (!$totcostreceived) {
    			$totcostreceived = "&nbsp;";
    		} else {
    			$totcostreceived = number_format($totcostreceived,2);
    			$list++;
    		}
    		if (!$totcostordered) {
    			$totcostordered= "&nbsp;";
    		} else {
    			$totcostordered= number_format($totcostordered,2);
    			$list++;
    		}
    		if ($list) {
    			$extra = "&timeframe=$timeframe&datefrom=$datefrom&dateto=$dateto&rlaprojid=$rlaprojid&brief_code=$brief_code";
				$extra = $extra."&totcostreceived=$totcostreceived&totcostordered=$totcostordered";
				$userstr	=	"?".base64_encode($userinfo.$extra);
				echo "<tr><td><a href=\"rla_fin_detailedordersearch.php$userstr\" target=_blank>
					$brief_code</a></td><td align=right>$totcostordered</td>
					<td align=right>$totcostreceived</td><td align=right>$nreceived</td></tr>";
			}
		}
	}
	$totalorder = number_format($totalorder,2) ;
	$totalrec = number_format($totalrec ,2);
	echo "<tr><th>Total</th><th>$totalorder</th><th>$totalrec</th><th>&nbsp;</th></tr>";
	echo "</table><p>";
}
function reportline2($hd) {
	echo "<hr><h2>$hd</h2>";
}
?>
<hr><br><a href=#top><b>Back to top</b></a><br><br>
</html>
