<?php
	//rla_fin_find_supid.inc
	$sqlsup = "SELECT t2.company as supstr
        FROM rlafinance.orderdetails as t1, rlafinance.supplierid as t2
        WHERE t1.order_id='$order_id' and t1.supid=t2.supid LIMIT 1;";

    $resultsup = mysql_query($sqlsup);
    include("err_msg.inc");
	list($supstr) = mysql_fetch_array($resultsup );
?>