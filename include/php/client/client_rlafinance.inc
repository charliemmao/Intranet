<html>
<SCRIPT LANGUAGE=JavaScript>
	window.defaultStatus = "Hello and Welcome to Intranet: Ordering and Finance System";
</SCRIPT>
<?php
	$new = " (<font color=#ff0000>* NEW</font>)</a></font>";
	include('connet_root_once.inc');
	include("phpdir.inc");
	##pass client info to next page
	$mystat = "general";
	
	$sql = "SELECT MAX(order_id) as nextid
        FROM rlafinance.orderid";
	
    $result = mysql_query($sql);
    include("err_msg.inc");
    list($nextid) = mysql_fetch_array($result); 
    $nextid += 1;
    $nextid = "<br>(Next Order No: $nextid)";
##get all info for ordering and finance control
	include("rla_fin_controldata.inc");
	$ser = getenv("server_name");
	if ($priv	==	'00') {
		$mystat = "auth";
	} else {
		if ($priv	==	'general') {
			//exit;
		}
	}
	//echo $userinfo;
	
	if ($mystat == "poff") {
		$mystat = "exec";
	}

	$qry	=	'?'.base64_encode($userinfo."&mystat=$mystat");
	
##	SHOW pages for director, sec + ordering officer 
if ($mystat != "general") {
	echo '<hr>';
	echo "<font size=2><b>Section A:&nbsp;";
	if ($mystat == "auth" ) {
		echo "Admin";
	} elseif ($mystat == "exec") {
		echo "ExecSec";
	} elseif ($mystat == "poff") {
		echo "PurOfficer";
	}
	echo "'s Pages</b></font><ul>";
	
##Analysis & Reports
	if ($mystat == "nnn") {			
		$statuscontext = "Finance Reports.";
		if ($priv == '00') {
			$statuscontext = "rla_fin_anarpt.php ($statuscontext)";
		}
		include("self_status.inc"); //'.$status.'
		echo '<li><font size="2"><align="left">'
			.'<a href="/'.$phpdir.'/rla_fin_anarpt.php'.$qry
			.'" target="main"'.$status.'>Analysis & Reports</a></font></li><br>';
	}
	
	if ($mystat == "auth") {			
		$statuscontext = "Approve Order.";
		if ($priv == '00') {
			$statuscontext = "rla_fin_ordpro_list.php ($statuscontext)";
		}
		include("self_status.inc"); //'.$status.'
		echo '<li><font size="2"><align="left">'
			.'<a href="/'.$phpdir.'/rla_fin_ordpro_list.php'.$qry
			.'" target="main"'.$status.'>Approve Order</a></font></li><br>';
	}

	if ($priv == "00" || $mystat == "exec" || $mystat == "poff") {			
##Invoice Entry
		$statuscontext = "Invoice Entry.";
		if ($priv == '00') {
			$statuscontext = "rla_fin_order_invoice.php ($statuscontext)";
		}
		include("self_status.inc"); //'.$status.'
		echo '<li><font size="2"><align="left">'
			.'<a href="/'.$phpdir.'/rla_fin_order_invoice.php'.$qry
			.'" target="main"'.$status.'>Invoice Entry</a></font></li><br>';
			
##	Receive order
		$statuscontext = "Receive Order";
		if ($priv == "00") {
			$statuscontext = "rla_fin_order_verify.php ($statuscontext)";
		}
		include("self_status.inc"); //'.$status.'
		echo '<li><align="left"><font size="2"><a href="/'.$phpdir.'/rla_fin_order_verify.php'.
		$qry.'" target="main"'.$status.'>Receive Order</a></font></li><br>';

##One Step Order
		$statuscontext = "One Step Order.";
		if ($priv == '00') {
			$statuscontext = "rla_fin_order_onestep.php ($statuscontext)";
		}
		include("self_status.inc"); //'.$status.'
		echo '<li><font size="2"><align="left">'
			.'<a href="/'.$phpdir.'/rla_fin_order_onestep.php'.$qry
			.'" target="main"'.$status.'>One Step Order</a></font></li><br>';
	}
	
	if ($mystat == "auth" || $mystat == "exec") {			
##View Order
		$statuscontext = "View Order Details.";
		if ($priv == '00') {
			$statuscontext = "rla_fin_order_fullview.php ($statuscontext)";
		}
		include("self_status.inc"); //'.$status.'
		echo '<li><font size="2"><align="left">'
			.'<a href="/'.$phpdir.'/rla_fin_order_fullview.php'.$qry
			.'" target="main"'.$status.'>View Orders</a></font></li><br>';
	}
	
	if ($priv == "00" || $mystat == "exec" || $priv == "10" || $mystat == "poff") {			
##Search Order
		$statuscontext = "Search Order.";
		if ($priv == '00') {
			$statuscontext = "rla_fin_order_Search.php ($statuscontext)";
		}
		include("self_status.inc"); //'.$status.'
		echo '<li><font size="2">Search by <align="left">'
			.'<a href="/'.$phpdir.'/rla_fin_order_Search.php'.$qry
			.'" target="main"'.$status.'>[Supplier]</a><br> ';
			
		$statuscontext = "Search by keyword from goods.";
		if ($priv == '00') {
			$statuscontext = "rla_fin_order_KeywordSearch.php ($statuscontext)";
		}
		include("self_status.inc"); //'.$status.'
		echo '<a href="/'.$phpdir.'/rla_fin_order_KeywordSearch.php'.$qry
			.'" target="main"'.$status.'>[Goods Keyword]</a><br> ';
	
	if ($priv == "00") {
		$statuscontext = "Search by Staff.";
		if ($priv == '00') {
			$statuscontext = "rla_fin_order_StaffSearch.php ($statuscontext)";
		}
		include("self_status.inc"); //'.$status.'
		echo '<a href="/'.$phpdir.'/rla_fin_order_StaffSearch.php'.$qry
			.'" target="main"'.$status.'>[Staff]</a><br>';
	}
	
		$statuscontext = "Search by project code.";
		if ($priv == '00') {
			$statuscontext = "rla_fin_order_pcodeSearch.php ($statuscontext)";
		}
		include("self_status.inc"); //'.$status.'
		echo '<a href="/'.$phpdir.'/rla_fin_order_pcodeSearch.php'.$qry
			.'" target="main"'.$status.'>[Project Code]'.$new.'</a>';

		echo '</font></li><br>';
	}
	
	if ($priv == "00" || $mystat == "exec" || $mystat == "poff") {			
##Printing Order
		$statuscontext = "Print Order.";
		if ($priv == '00') {
			$statuscontext = "rla_fin_order_print.php ($statuscontext)";
		}
		include("self_status.inc"); //'.$status.'
		echo '<li><font size="2"><align="left">'
			.'<a href="/'.$phpdir.'/rla_fin_order_print.php'.$qry
			.'" target="main"'.$status.'>Print Orders</a></font></li><br>';
	}
	
	if ($priv == "00" || $mystat == "exec" || $priv == "10" || $mystat == "poff") {			
##	Undelivered order
		$statuscontext = "Undelivered Order (More than 1 month old)";
		if ($priv == "00") {
			$statuscontext = "rla_fin_order_deadorder.php ($statuscontext)";
		}
		include("self_status.inc"); //'.$status.'
		echo '<li><align="left"><font size="2"><a href="/'.$phpdir.'/rla_fin_order_deadorder.php'.
		$qry.'" target="main"'.$status.'>Undelivered Order</li><br>'; //'.$new.'
	}
	echo "<hr>";
##Change Payment Method
		$statuscontext = "Change Payment Method.";
		if ($priv == '00') {
			$statuscontext = "rla_fin_order_modpaymethods.php ($statuscontext)";
		}
		include("self_status.inc"); //'.$status.'
		echo '<li><font size="2"><align="left">'
			.'<a href="/'.$phpdir.'/rla_fin_order_modpaymethods.php'.$qry
			.'" target="main"'.$status.'>Change Payment Method</a></font></li><br>';
			
##Modify After Approved Order 
		$statuscontext = "Modify Approved Order.";
		if ($priv == '00') {
			$statuscontext = "rla_fin_order_modafterapproval.php ($statuscontext)";
		}
		include("self_status.inc"); //'.$status.'
		echo '<li><font size="2"><align="left">'
			.'<a href="/'.$phpdir.'/rla_fin_order_modafterapproval.php'.$qry
			.'" target="main"'.$status.'>Modify Approved Order</a></font></li><br>';
			
	if ($priv == "00" || $mystat == "exec") {			
##Modify After Order Closed
		$statuscontext = "Modify After Order Closed.";
		if ($priv == '00') {
			$statuscontext = "rla_fin_order_mod_afterclose.php ($statuscontext)";
		}
		include("self_status.inc"); //'.$status.'
		echo '<li><font size="2"><align="left">'
			.'<a href="/'.$phpdir.'/rla_fin_order_mod_afterclose.php'.$qry
			.'" target="main"'.$status.'>Modify Closed Order</a></font></li><br>';
	}
	
	if ($priv == "00" || $mystat == "exec" || $mystat == "poff") {			
##Change Order Status
			$statuscontext = "Change Order Status.";
			if ($priv == '00') {
				$statuscontext = "rla_fin_ch_order_status.php ($statuscontext)";
			}
			include("self_status.inc"); //'.$status.'
			echo '<li><font size="2"><align="left">'
				.'<a href="/'.$phpdir.'/rla_fin_ch_order_status.php'.$qry
				.'" target="main"'.$status.'>Change Order Status</a></font></li><br>';
	}
	
	if ($mystat == "auth" || $mystat == "exec") {			
##Edit Credit Card
		$statuscontext = "Edit Credit Card Info.";
		if ($priv == '00') {
			$statuscontext = "rla_fin_Ccard.php ($statuscontext)";
		}
		include("self_status.inc"); //'.$status.'
		echo '<hr><li><font size="2"><align="left">'
			.'<a href="/'.$phpdir.'/rla_fin_Ccard.php'.$qry
			.'" target="main"'.$status.'>Credit Card Info</a></font></li><br>';
		
		if ($priv == '00') {
##Edit Charging Code
			$statuscontext = "Edit Charging Code Table.";
			if ($priv == '00') {
				$statuscontext = "rla_fin_code.php ($statuscontext)";
			}
			include("self_status.inc"); //'.$status.'
			echo '<li><font size="2"><align="left">'
				.'<a href="/'.$phpdir.'/rla_fin_code.php'.$qry
				.'" target="main"'.$status.'>Charging Code</a></font></li><br>';
		}

/*
		if ($priv == '00' || $mystat == "auth") {
##Link RLA Project Code To GHR Charging Code
			$statuscontext = "Link RLA Project Code To GHR Charging Code.";
			if ($priv == '00') {
				$statuscontext = "rla_fin_code_linkghr.php ($statuscontext)";
			}
			include("self_status.inc"); //'.$status.'
			echo '<li><font size="2"><align="left">'
				.'<a href="/'.$phpdir.'/rla_fin_code_linkghr.php'.$qry
				.'" target="main"'.$status.'>Link RLA-GHR Codes</a></font></li><br>';
		}
//*/
	}

	echo '</ul><hr>';	// ($mystat)
	echo "<font size=2><b>Section B: $first_name's Order Pages$nextid</b></font>";
} else {
	echo "<br><hr><font size=2><b>$first_name's Order Pages$nextid</b></font>";
}

##########For everyone##############
echo "<ul>";
##	Compose order
	$statuscontext = "New Order";
		if ($priv == '00') {
			$statuscontext = "rla_fin_order_new.php ($statuscontext)";
		}
	include("self_status.inc"); //'.$status.'
	echo '<li><align="left"><font size="2"><a href="/'.$phpdir.'/rla_fin_order_new.php'.
	$qry.'" target="main"'.$status.'>New Order</a></font></li><br>';

##	Order Manipulation and View
	$statuscontext = "Order Manipulation and View";
		if ($priv == '00') {
			$statuscontext = "rla_fin_order_mod.php ($statuscontext)";
		}
	include("self_status.inc"); //'.$status.'
	echo '<li><align="left"><font size="2"><a href="/'.$phpdir.'/rla_fin_order_mod.php'.
	$qry.'" target="main"'.$status.'>Existing Order</a></font></li><br>';

	echo '<li><align="left"><font size="2"><a href="/'.$phpdir.'/rla_fin_howto.php'.
	$qry.'" target="_blank">How to Place an Order'.$new.'</a></font></li><br>';

##	Edit Goods List
	$statuscontext = "Edit/Modify New Goods";
	if ($priv == '00') {
		$statuscontext = "rla_fin_goods.php ($statuscontext)";
	}
	include("self_status.inc"); //'.$status.'
	$sql = "SELECT goods_id	FROM rlafinance.goodsid";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	$qrygoods	=	'?'.base64_encode($userinfo."&mystat=$mystat&listmethod=goods");
	$qrycomp	=	'?'.base64_encode($userinfo."&mystat=$mystat&listmethod=company");
	echo '<hr><li><align="left"><font size="2">Goods List ('.$no.') By<br>
	<a href="/'.$phpdir.'/rla_fin_goods.php'.$qrycomp.'" target="main"'.$status.'>[Company]</a>
	<a href="/'.$phpdir.'/rla_fin_goods.php'.$qrygoods.'" target="main"'.$status.'>[Goods]</a>
	</font></li><br>';

##	Edit Supplier
	$statuscontext = "Edit/Modify Supplier";
		if ($priv == '00') {
			$statuscontext = "rla_fin_supplier.php ($statuscontext)";
		}
	include("self_status.inc"); //'.$status.'  and faxno!=''
	$sql = "SELECT supid as id, company, faxno, telno FROM rlafinance.supplierid 
		where telno!=''";
	$result = mysql_query($sql);
	include("err_msg.inc");
	$no = mysql_num_rows($result);
	echo '<li><align="left"><font size="2"><a href="/'.$phpdir.'/rla_fin_supplier.php'.
	$qry.'" target="main"'.$status.'>Supplier List ('.$no.')</a></font></li><br>';
	
#delete goods from database by company
	if ($priv == '00') {
		$statuscontext = "rla_fin_goods_delete.php ($statuscontext)";
		include("self_status.inc"); //'.$status.'
		echo '<li><align="left"><font size="2">
			<a href="/'.$phpdir.'/rla_fin_goods_delete.php'.$qrycomp.'" target="main"'.$status.'>Delete Goods List</a>
			</font></li><br>';
	}

echo "</ul>";
?>
</html>
